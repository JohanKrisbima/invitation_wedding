<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\GuestMessage;
use App\Models\Invitation;
use App\Models\Rsvp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvitationController extends Controller
{
    /**
     * Display the public digital wedding invitation.
     */
    public function show(Request $request, string $slug): View
    {
        // 1. Ambil data undangan beserta seluruh relasinya
        $invitation = Invitation::with([
            'template',
            'couple',
            'events',
            'galleryPhotos',
            'guestMessages' => fn($q) => $q->where('is_approved', true)->latest(),
            'rsvps' => fn($q) => $q->latest(),
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        // 2. Proteksi status publikasi: jika masih draft/archived, hanya admin login yang boleh preview
        if ($invitation->publication_status !== 'published') {
            if (!Auth::check()) {
                abort(404, 'Undangan ini belum dipublikasikan atau sedang dalam penyesuaian.');
            }
        }

        // 3. Ekstrak data utama
        $couple = $invitation->couple;
        $events = $invitation->events;
        $galleryPhotos = $invitation->galleryPhotos;
        $guestMessages = $invitation->guestMessages;
        $rsvps = $invitation->rsvps;

        // 4. Tangkap nama tamu dari parameter URL (?to=Nama+Tamu)
        $guestName = $request->query('to', 'Tamu Undangan');

        // Cari data guest jika sudah terdaftar di buku tamu
        $guest = $invitation->guests()
            ->where('name', 'ILIKE', $guestName)
            ->first();

        // Cari riwayat RSVP tamu jika ada
        $rsvp = null;
        if ($guest) {
            $rsvp = $invitation->rsvps()
                ->where('guest_id', $guest->id)
                ->first();
        }

        // 5. Cek ketersediaan view template secara dinamis
        $templateSlug = $invitation->template?->slug ?? 'floral-elegance';
        $viewName = "templates.{$templateSlug}.index";

        if (!view()->exists($viewName)) {
            // Fallback jika view template spesifik belum tersedia
            abort(404, "Template undangan [{$templateSlug}] sedang dalam tahap persiapan.");
        }

        return view($viewName, compact(
            'invitation',
            'couple',
            'events',
            'galleryPhotos',
            'guestMessages',
            'guestName',
            'guest',
            'rsvp',
            'rsvps'
        ));
    }

    /**
     * Handle RSVP attendance confirmation via AJAX.
     */
    public function storeRsvp(Request $request, string $slug): JsonResponse
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'attendance_status' => ['required', 'in:attending,tentative,declined'],
            'guest_count' => ['nullable', 'integer', 'min:1', 'max:10'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [
            'guest_name.required' => 'Nama lengkap wajib diisi.',
            'attendance_status.required' => 'Silakan pilih status konfirmasi kehadiran.',
            'attendance_status.in' => 'Pilihan kehadiran tidak valid.',
            'guest_count.min' => 'Jumlah tamu minimal 1 orang.',
            'guest_count.max' => 'Jumlah tamu maksimal 10 orang.',
        ]);

        // Cari apakah nama tamu cocok dengan list tamu terdaftar
        $guest = $invitation->guests()
            ->where('name', 'ILIKE', $validated['guest_name'])
            ->first();

        $rsvp = $invitation->rsvps()->updateOrCreate(
            [
                'invitation_id' => $invitation->id,
                'guest_name' => $validated['guest_name'],
            ],
            [
                'guest_id' => $guest?->id,
                'attendance_status' => $validated['attendance_status'],
                'guest_count' => $validated['attendance_status'] === 'attending' ? ($validated['guest_count'] ?? 1) : 0,
                'note' => $validated['note'] ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Konfirmasi kehadiran Anda berhasil disimpan.',
            'data' => [
                'id' => $rsvp->id,
                'guest_name' => $rsvp->guest_name,
                'status' => $rsvp->attendance_status,
                'guest_count' => $rsvp->guest_count,
                'note' => $rsvp->note,
                'created_at_human' => 'Baru saja',
            ],
        ]);
    }

    /**
     * Handle Guestbook message and prayers submission via AJAX.
     */
    public function storeMessage(Request $request, string $slug): JsonResponse
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
        ], [
            'guest_name.required' => 'Nama pengirim ucapan wajib diisi.',
            'message.required' => 'Pesan ucapan dan doa tidak boleh kosong.',
            'message.max' => 'Pesan ucapan maksimal 1000 karakter.',
        ]);

        $guest = $invitation->guests()
            ->where('name', 'ILIKE', $validated['guest_name'])
            ->first();

        $guestMessage = $invitation->guestMessages()->create([
            'guest_id' => $guest?->id,
            'guest_name' => $validated['guest_name'],
            'message' => $validated['message'],
            'is_approved' => true, // Otomatis disetujui atau siap dimoderasi di admin
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Doa dan ucapan hangat Anda berhasil terkirim.',
            'data' => [
                'id' => $guestMessage->id,
                'guest_name' => $guestMessage->guest_name,
                'message' => $guestMessage->message,
                'created_at_human' => 'Baru saja',
            ],
        ]);
    }
}
