<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Couple;
use App\Models\Event;
use App\Models\GalleryPhoto;
use App\Models\Invitation;
use App\Models\Order;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    /**
     * Display a listing of all digital invitations with server-side filtering, live search, and pagination.
     */
    public function index(Request $request)
    {
        // 1. Metric Counts for Invitations Overview
        $totalInvitations = Invitation::count();
        $publishedInvitations = Invitation::where('publication_status', 'published')->count();
        $draftInvitations = Invitation::where('publication_status', 'draft')->count();
        $archivedInvitations = Invitation::where('publication_status', 'archived')->count();

        // 2. Query Builder for Invitations Table (Server-Side)
        $query = Invitation::with(['order', 'template', 'couple'])->latest();

        // Search Filter (slug, customer_name, partner_one_name, partner_two_name, order_number)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('slug', 'ILIKE', "%{$search}%")
                  ->orWhereHas('order', fn($oq) => $oq->where('customer_name', 'ILIKE', "%{$search}%")
                                                      ->orWhere('order_number', 'ILIKE', "%{$search}%"))
                  ->orWhereHas('couple', fn($cq) => $cq->where('partner_one_name', 'ILIKE', "%{$search}%")
                                                        ->orWhere('partner_two_name', 'ILIKE', "%{$search}%"));
            });
        }

        // Status Filter (published, draft, archived)
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('publication_status', $status);
            }
        }

        // Template Filter
        if ($template = $request->input('template')) {
            if ($template !== 'all') {
                $query->where('template_id', $template);
            }
        }

        // Paginate Server-Side (10 per page)
        $invitations = $query->paginate(10)->withQueryString();
        $templates = Template::where('is_active', true)->get();

        // If AJAX request (live search, filter change, or pagination click), return only table partial
        if ($request->ajax() || $request->header('X-Alpine-Request') || $request->boolean('ajax')) {
            return view('admin.invitations._table', compact('invitations'))->render();
        }

        return view('admin.invitations.index', compact(
            'invitations',
            'templates',
            'totalInvitations',
            'publishedInvitations',
            'draftInvitations',
            'archivedInvitations'
        ));
    }

    /**
     * Confirm manual payment and create an invitation from an order.
     */
    public function storeFromOrder(Order $order)
    {
        // 1. If invitation already exists, redirect directly to edit
        if ($order->invitation) {
            return redirect()->route('admin.invitations.edit', $order->invitation->id)
                ->with('info', 'Undangan untuk pesanan ini sudah ada.');
        }

        return DB::transaction(function () use ($order) {
            // 2. Update order status: payment_status = paid, order_status = in_progress
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'in_progress',
            ]);

            // 3. Generate clean unique slug
            $baseSlug = Str::slug($order->customer_name);
            $slug = $baseSlug ?: 'undangan-' . strtolower($order->order_number);
            $count = 1;
            while (Invitation::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            // 4. Create Invitation record
            $invitation = Invitation::create([
                'order_id' => $order->id,
                'template_id' => $order->template_id,
                'slug' => $slug,
                'publication_status' => 'draft',
            ]);

            // 5. Initialize Couple record
            Couple::create([
                'invitation_id' => $invitation->id,
                'partner_one_name' => $order->customer_name,
                'partner_two_name' => 'Pasangan',
                'partner_one_father' => '-',
                'partner_one_mother' => '-',
                'partner_two_father' => '-',
                'partner_two_mother' => '-',
            ]);

            return redirect()->route('admin.invitations.edit', $invitation->id)
                ->with('success', "Pembayaran pesanan {$order->order_number} berhasil divalidasi! Undangan digital telah dibuat, silakan lengkapi profil mempelai.");
        });
    }

    /**
     * Show the multi-tab management form for a specific invitation.
     */
    public function edit(Invitation $invitation)
    {
        $invitation->load(['order', 'template', 'couple', 'events', 'galleryPhotos']);
        $templates = Template::where('is_active', true)->get();

        return view('admin.invitations.edit', compact('invitation', 'templates'));
    }

    /**
     * Update couple profile (Pengantin Pria, Wanita, Orang Tua, & Cerita).
     */
    public function updateCouple(Request $request, Invitation $invitation)
    {
        $validated = $request->validate([
            'partner_one_name' => ['required', 'string', 'max:255'],
            'partner_one_father' => ['required', 'string', 'max:255'],
            'partner_one_mother' => ['required', 'string', 'max:255'],
            'partner_one_photo' => ['nullable', 'image', 'max:5120'],
            'partner_two_name' => ['required', 'string', 'max:255'],
            'partner_two_father' => ['required', 'string', 'max:255'],
            'partner_two_mother' => ['required', 'string', 'max:255'],
            'partner_two_photo' => ['nullable', 'image', 'max:5120'],
            'story' => ['nullable', 'string'],
        ], [
            'partner_one_name.required' => 'Nama pengantin pertama/pria wajib diisi.',
            'partner_one_father.required' => 'Nama ayah pengantin pria wajib diisi.',
            'partner_one_mother.required' => 'Nama ibu pengantin pria wajib diisi.',
            'partner_one_photo.image' => 'File foto pengantin pria harus berupa gambar.',
            'partner_one_photo.max' => 'Ukuran foto pengantin pria tidak boleh lebih dari 5 MB.',
            'partner_two_name.required' => 'Nama pengantin kedua/wanita wajib diisi.',
            'partner_two_father.required' => 'Nama ayah pengantin wanita wajib diisi.',
            'partner_two_mother.required' => 'Nama ibu pengantin wanita wajib diisi.',
            'partner_two_photo.image' => 'File foto pengantin wanita harus berupa gambar.',
            'partner_two_photo.max' => 'Ukuran foto pengantin wanita tidak boleh lebih dari 5 MB.',
        ]);

        $couple = $invitation->couple ?? new Couple(['invitation_id' => $invitation->id]);

        // Upload Partner One Photo if present
        if ($request->hasFile('partner_one_photo')) {
            if ($couple->partner_one_photo) {
                Storage::disk('public')->delete($couple->partner_one_photo);
            }
            $validated['partner_one_photo'] = $request->file('partner_one_photo')->store('couples', 'public');
        } else {
            unset($validated['partner_one_photo']);
        }

        // Upload Partner Two Photo if present
        if ($request->hasFile('partner_two_photo')) {
            if ($couple->partner_two_photo) {
                Storage::disk('public')->delete($couple->partner_two_photo);
            }
            $validated['partner_two_photo'] = $request->file('partner_two_photo')->store('couples', 'public');
        } else {
            unset($validated['partner_two_photo']);
        }

        $couple->fill($validated);
        $couple->invitation_id = $invitation->id;
        $couple->save();

        return redirect()->route('admin.invitations.edit', ['invitation' => $invitation->id, 'tab' => 'couple'])
            ->with('success', 'Data profil kedua mempelai berhasil diperbarui!');
    }

    /**
     * Update event schedules (Akad Nikah, Resepsi, dll).
     */
    public function updateEvents(Request $request, Invitation $invitation)
    {
        $validated = $request->validate([
            'events' => ['required', 'array', 'min:1'],
            'events.*.event_type' => ['required', 'string', 'max:255'],
            'events.*.start_at' => ['required', 'date'],
            'events.*.end_at' => ['nullable', 'date'],
            'events.*.venue_name' => ['required', 'string', 'max:255'],
            'events.*.address' => ['nullable', 'string'],
            'events.*.maps_url' => ['nullable', 'string'],
        ], [
            'events.required' => 'Minimal harus ada 1 jadwal acara.',
            'events.*.event_type.required' => 'Nama jenis acara wajib diisi (contoh: Akad Nikah).',
            'events.*.start_at.required' => 'Waktu mulai acara wajib diisi.',
            'events.*.venue_name.required' => 'Nama lokasi/gedung wajib diisi.',
        ]);

        // Delete previous events and recreate neatly
        $invitation->events()->delete();

        foreach ($validated['events'] as $index => $eventData) {
            Event::create([
                'invitation_id' => $invitation->id,
                'event_type' => $eventData['event_type'],
                'start_at' => $eventData['start_at'],
                'end_at' => !empty($eventData['end_at']) ? $eventData['end_at'] : null,
                'venue_name' => $eventData['venue_name'],
                'address' => $eventData['address'] ?? null,
                'maps_url' => $eventData['maps_url'] ?? null,
                'sort_order' => $index + 1,
            ]);
        }

        return redirect()->route('admin.invitations.edit', ['invitation' => $invitation->id, 'tab' => 'events'])
            ->with('success', 'Jadwal rangkaian acara pernikahan berhasil disimpan!');
    }

    /**
     * Upload prewedding gallery photos.
     */
    public function uploadGallery(Request $request, Invitation $invitation)
    {
        $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['image', 'max:5120'], // Max 5MB per photo
            'caption' => ['nullable', 'string', 'max:255'],
        ], [
            'photos.required' => 'Pilih minimal satu foto untuk diunggah.',
            'photos.*.image' => 'File harus berupa gambar (JPG, PNG, WEBP).',
            'photos.*.max' => 'Ukuran file foto melebihi batas maksimal 5 MB. Silakan pilih foto dengan ukuran di bawah 5 MB.',
        ]);

        $lastOrder = $invitation->galleryPhotos()->max('sort_order') ?? 0;

        foreach ($request->file('photos') as $photoFile) {
            $lastOrder++;
            $path = $photoFile->store('gallery/' . $invitation->id, 'public');

            GalleryPhoto::create([
                'invitation_id' => $invitation->id,
                'photo_path' => $path,
                'caption' => $request->input('caption'),
                'sort_order' => $lastOrder,
            ]);
        }

        return redirect()->route('admin.invitations.edit', ['invitation' => $invitation->id, 'tab' => 'gallery'])
            ->with('success', 'Foto prewedding berhasil diunggah ke galeri!');
    }

    /**
     * Delete a single prewedding gallery photo.
     */
    public function deleteGallery(Invitation $invitation, GalleryPhoto $galleryPhoto)
    {
        if ($galleryPhoto->invitation_id !== $invitation->id) {
            abort(403);
        }

        if (Storage::disk('public')->exists($galleryPhoto->photo_path)) {
            Storage::disk('public')->delete($galleryPhoto->photo_path);
        }

        $galleryPhoto->delete();

        return redirect()->route('admin.invitations.edit', ['invitation' => $invitation->id, 'tab' => 'gallery'])
            ->with('success', 'Foto berhasil dihapus dari galeri.');
    }

    /**
     * Update publication status and customized unique URL slug.
     */
    public function updatePublication(Request $request, Invitation $invitation)
    {
        $validated = $request->validate([
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('invitations')->ignore($invitation->id),
            ],
            'template_id' => ['required', 'exists:templates,id'],
            'publication_status' => ['required', 'in:draft,published,archived'],
        ], [
            'slug.required' => 'Slug URL wajib diisi.',
            'slug.unique' => 'Slug URL ini sudah digunakan oleh undangan lain.',
            'slug.alpha_dash' => 'Slug URL hanya boleh berupa huruf, angka, dan tanda hubung (-).',
            'template_id.required' => 'Pilih salah satu template katalog.',
        ]);

        if ($validated['publication_status'] === 'published' && $invitation->publication_status !== 'published') {
            $validated['published_at'] = now();
        }

        $invitation->update($validated);

        return redirect()->route('admin.invitations.edit', ['invitation' => $invitation->id, 'tab' => 'publication'])
            ->with('success', 'Status publikasi dan konfigurasi URL berhasil diperbarui!');
    }
}
