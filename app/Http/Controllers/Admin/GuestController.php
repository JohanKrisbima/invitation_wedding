<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class GuestController extends Controller
{
    /**
     * Display a listing of wedding guests with live search, filters, and metrics.
     */
    public function index(Request $request)
    {
        // 1. All available invitations for the switcher dropdown
        $invitations = Invitation::with(['couple', 'order'])->latest()->get();

        // 2. Determine selected invitation (default to the first one)
        $selectedInvitationId = $request->input('invitation_id') ?: ($invitations->first()->id ?? null);
        $selectedInvitation = $selectedInvitationId 
            ? Invitation::with(['couple', 'order', 'events'])->find($selectedInvitationId) 
            : null;

        // If no invitations exist in the system yet
        if (!$selectedInvitation) {
            return view('admin.guests.index', [
                'invitations' => $invitations,
                'selectedInvitation' => null,
                'guests' => collect(),
                'totalGuests' => 0,
                'sentGuests' => 0,
                'unsentGuests' => 0,
                'attendingGuests' => 0,
            ]);
        }

        // 3. Metric Counts for this invitation
        $totalGuests = Guest::where('invitation_id', $selectedInvitationId)->count();
        $sentGuests = Guest::where('invitation_id', $selectedInvitationId)->whereNotNull('invitation_sent_at')->count();
        $unsentGuests = Guest::where('invitation_id', $selectedInvitationId)->whereNull('invitation_sent_at')->count();
        $attendingGuests = Rsvp::where('invitation_id', $selectedInvitationId)
            ->where('attendance_status', 'attending')
            ->sum('guest_count');

        // 4. Query Builder for Guests
        $query = Guest::where('invitation_id', $selectedInvitationId)
            ->with(['rsvp', 'invitation.couple'])
            ->latest();

        // Search Filter (guest name, phone, or address)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('phone', 'ILIKE', "%{$search}%")
                  ->orWhere('address', 'ILIKE', "%{$search}%");
            });
        }

        // RSVP Status Filter
        if ($rsvpStatus = $request->input('rsvp')) {
            if ($rsvpStatus === 'attending') {
                $query->whereHas('rsvp', fn($q) => $q->where('attendance_status', 'attending'));
            } elseif ($rsvpStatus === 'not_attending') {
                $query->whereHas('rsvp', fn($q) => $q->where('attendance_status', 'not_attending'));
            } elseif ($rsvpStatus === 'tentative') {
                $query->whereHas('rsvp', fn($q) => $q->where('attendance_status', 'tentative'));
            } elseif ($rsvpStatus === 'no_rsvp') {
                $query->whereDoesntHave('rsvp');
            }
        }

        // Sent Status Filter
        if ($sentStatus = $request->input('sent')) {
            if ($sentStatus === 'sent') {
                $query->whereNotNull('invitation_sent_at');
            } elseif ($sentStatus === 'unsent') {
                $query->whereNull('invitation_sent_at');
            }
        }

        $guests = $query->paginate(15)->withQueryString();

        // If AJAX request (live search, filter change, or pagination click), return table partial
        if ($request->ajax() || $request->header('X-Alpine-Request') || $request->boolean('ajax')) {
            return view('admin.guests._table', compact('guests', 'selectedInvitation'))->render();
        }

        return view('admin.guests.index', compact(
            'invitations',
            'selectedInvitation',
            'guests',
            'totalGuests',
            'sentGuests',
            'unsentGuests',
            'attendingGuests'
        ));
    }

    /**
     * Store a single newly created guest manually.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invitation_id' => ['required', 'exists:invitations,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_sent' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama tamu undangan wajib diisi.',
            'invitation_id.required' => 'Pilih undangan yang valid.',
        ]);

        $cleanPhone = $validated['phone'] ? preg_replace('/[^0-9]/', '', $validated['phone']) : null;
        if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        Guest::create([
            'invitation_id' => $validated['invitation_id'],
            'name' => trim($validated['name']),
            'phone' => $cleanPhone ?: $validated['phone'],
            'address' => !empty($validated['address']) ? trim($validated['address']) : null,
            'invitation_sent_at' => $request->boolean('is_sent') ? now() : null,
        ]);

        return redirect()->route('admin.guests.index', ['invitation_id' => $validated['invitation_id']])
            ->with('success', "Tamu '{$validated['name']}' berhasil ditambahkan ke daftar!");
    }

    /**
     * Update an existing guest's name, phone, and address.
     */
    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama tamu tidak boleh kosong.',
        ]);

        $cleanPhone = $validated['phone'] ? preg_replace('/[^0-9]/', '', $validated['phone']) : null;
        if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $guest->update([
            'name' => trim($validated['name']),
            'phone' => $cleanPhone ?: $validated['phone'],
            'address' => !empty($validated['address']) ? trim($validated['address']) : null,
        ]);

        return redirect()->route('admin.guests.index', ['invitation_id' => $guest->invitation_id])
            ->with('success', "Data tamu '{$guest->name}' berhasil diperbarui!");
    }

    /**
     * Delete a guest.
     */
    public function destroy(Guest $guest)
    {
        $invitationId = $guest->invitation_id;
        $guestName = $guest->name;
        $guest->delete();

        return redirect()->route('admin.guests.index', ['invitation_id' => $invitationId])
            ->with('success', "Tamu '{$guestName}' berhasil dihapus dari daftar.");
    }

    /**
     * Toggle sent status of an invitation.
     */
    public function toggleSent(Guest $guest)
    {
        $guest->update([
            'invitation_sent_at' => $guest->invitation_sent_at ? null : now(),
        ]);

        return redirect()->route('admin.guests.index', ['invitation_id' => $guest->invitation_id])
            ->with('success', $guest->invitation_sent_at 
                ? "Tamu '{$guest->name}' ditandai sudah dikirim via WhatsApp." 
                : "Status pengiriman tamu '{$guest->name}' direset.");
    }

    /**
     * Import a chunk of guests via AJAX to prevent Request Timeout (RTO).
     * Menerima batch 50-100 data tamu dan menyimpannya secara cepat & aman.
     */
    public function importChunk(Request $request)
    {
        $validated = $request->validate([
            'invitation_id' => ['required', 'exists:invitations,id'],
            'rows' => ['required', 'array'],
            'rows.*.name' => ['required', 'string', 'max:255'],
            'rows.*.phone' => ['nullable', 'string', 'max:50'],
            'rows.*.address' => ['nullable', 'string', 'max:255'],
        ]);

        $invitationId = $validated['invitation_id'];
        $now = now();
        $batch = [];

        foreach ($validated['rows'] as $row) {
            $name = trim($row['name'] ?? '');
            if (empty($name)) {
                continue;
            }

            $phone = !empty($row['phone']) ? trim($row['phone']) : null;
            $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
            if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            }

            $address = !empty($row['address']) ? trim($row['address']) : null;

            $batch[] = [
                'invitation_id' => $invitationId,
                'name' => $name,
                'phone' => $cleanPhone ?: $phone,
                'address' => $address,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (empty($batch)) {
            return response()->json([
                'success' => true,
                'imported' => 0,
                'message' => 'Tidak ada baris data yang valid pada batch ini.',
            ]);
        }

        DB::beginTransaction();
        try {
            // Eksekusi insert per sub-chunk 50 baris untuk efisiensi PostgreSQL
            foreach (array_chunk($batch, 50) as $subChunk) {
                Guest::insert($subChunk);
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'imported' => count($batch),
                'message' => 'Batch berhasil diproses.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses batch: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Import multiple guests from Quick Paste text box or fallback file.
     * Menggunakan chunking database agar aman dari RTO.
     */
    public function import(Request $request)
    {
        $request->validate([
            'invitation_id' => ['required', 'exists:invitations,id'],
            'file' => ['nullable', 'file', 'max:4096'],
            'raw_text' => ['nullable', 'string'],
        ], [
            'invitation_id.required' => 'Undangan tujuan wajib dipilih.',
        ]);

        $invitationId = $request->input('invitation_id');
        $batch = [];
        $now = now();

        // 1. Process Raw Text Box (Line-by-line copy paste)
        if ($request->filled('raw_text')) {
            $lines = preg_split('/\r\n|\r|\n/', $request->input('raw_text'));

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // Check comma-separated values: Name, Phone, Address or Name, Address
                if (str_contains($line, ',')) {
                    $parts = array_map('trim', explode(',', $line));
                    $name = $parts[0] ?? '';
                    $second = $parts[1] ?? null;
                    $third = $parts[2] ?? null;

                    if ($second && preg_match('/^[0-9+\s\-]+$/', $second)) {
                        $phone = $second;
                        $address = $third;
                    } else {
                        $address = $second;
                        $phone = $third;
                    }
                } else {
                    $name = $line;
                    $phone = null;
                    $address = null;
                }

                if (!empty($name)) {
                    $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
                    if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }

                    $batch[] = [
                        'invitation_id' => $invitationId,
                        'name' => $name,
                        'phone' => $cleanPhone ?: $phone,
                        'address' => $address ?: null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        // 2. Process CSV / TXT fallback
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $handle = fopen($file->getRealPath(), 'r');

            if ($handle !== false) {
                $rowIndex = 0;
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $rowIndex++;
                    if ($rowIndex === 1 && isset($data[0]) && preg_match('/name|nama/i', $data[0])) {
                        continue;
                    }

                    $name = isset($data[0]) ? trim($data[0]) : '';
                    $phone = isset($data[1]) ? trim($data[1]) : null;
                    $address = isset($data[2]) ? trim($data[2]) : null;

                    if (!empty($name)) {
                        $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
                        if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }

                        $batch[] = [
                            'invitation_id' => $invitationId,
                            'name' => $name,
                            'phone' => $cleanPhone ?: $phone,
                            'address' => $address ?: null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
                fclose($handle);
            }
        }

        if (empty($batch)) {
            return redirect()->route('admin.guests.index', ['invitation_id' => $invitationId])
                ->with('error', 'Tidak ada data nama tamu yang berhasil diimpor. Pastikan format teks atau file sudah benar.');
        }

        // Eksekusi insert menggunakan chunking 50 data per query agar performa maksimal & bebas RTO
        DB::beginTransaction();
        try {
            foreach (array_chunk($batch, 50) as $chunk) {
                Guest::insert($chunk);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('admin.guests.index', ['invitation_id' => $invitationId])
                ->with('error', 'Gagal menyimpan data tamu: ' . $e->getMessage());
        }

        $totalCount = count($batch);
        return redirect()->route('admin.guests.index', ['invitation_id' => $invitationId])
            ->with('success', "Berhasil mengimpor {$totalCount} tamu ke dalam daftar (diproses via batching aman)!");
    }

    /**
     * Download a sample CSV template for guest import.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="format-import-tamu.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, ['Nama Tamu', 'Nomor WhatsApp', 'Alamat / Kota']);
            fputcsv($handle, ['Bpk. Budi Santoso & Keluarga', '081234567890', 'Jakarta Selatan']);
            fputcsv($handle, ['Sdr. Dimas Pratama', '087811228899', 'Surabaya']);
            fputcsv($handle, ['Ibu Siti Rahmawati', '081398765432', 'Bandung']);
            fputcsv($handle, ['Keluarga Besar H. Ridwan', '', 'Yogyakarta']);
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export all guest data with personal links for Excel generation.
     */
    public function exportData(Request $request)
    {
        $invitationId = $request->input('invitation_id');
        $invitation = Invitation::with(['couple', 'order'])->find($invitationId);

        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Undangan tidak ditemukan.',
            ], 404);
        }

        $coupleNames = $invitation->couple 
            ? $invitation->couple->partner_one_name . ' & ' . $invitation->couple->partner_two_name 
            : $invitation->slug;

        $guests = Guest::where('invitation_id', $invitationId)
            ->with(['rsvp'])
            ->orderBy('id', 'asc')
            ->get();

        $rows = [];
        foreach ($guests as $index => $guest) {
            $location = !empty($guest->address) ? 'di ' . $guest->address : 'di Tempat';
            $invitationUrl = $guest->invitation_url;

            $waMessage = "Kepada Yth.\n"
                . "*{$guest->name}*\n"
                . "{$location}\n\n"
                . "Assalamu'alaikum Wr. Wb. / Salam Sejahtera,\n\n"
                . "Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri momen bahagia pernikahan kami:\n\n"
                . "✨ *{$coupleNames}* ✨\n\n"
                . "Informasi lengkap mengenai acara dan lokasi dapat diakses melalui tautan undangan digital berikut:\n"
                . "👉 {$invitationUrl}\n\n"
                . "Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.\n\n"
                . "Terima kasih.\n"
                . "Wassalamu'alaikum Wr. Wb.";

            $rsvpStatus = 'Belum Konfirmasi';
            $pax = '-';
            if ($guest->rsvp) {
                if ($guest->rsvp->attendance_status === 'attending') {
                    $rsvpStatus = 'Hadir';
                    $pax = $guest->rsvp->guest_count ?? 1;
                } elseif ($guest->rsvp->attendance_status === 'not_attending') {
                    $rsvpStatus = 'Tidak Hadir';
                    $pax = 0;
                } elseif ($guest->rsvp->attendance_status === 'tentative') {
                    $rsvpStatus = 'Ragu-ragu';
                    $pax = $guest->rsvp->guest_count ?? 1;
                }
            }

            $rows[] = [
                'no' => $index + 1,
                'name' => $guest->name,
                'address' => $guest->address ?: '-',
                'phone' => $guest->phone ?: '-',
                'invitation_url' => $invitationUrl,
                'wa_message' => $waMessage,
                'sent_status' => $guest->invitation_sent_at ? 'Sudah Dikirim' : 'Belum Dikirim',
                'rsvp_status' => $rsvpStatus,
                'guest_count' => $pax,
            ];
        }

        return response()->json([
            'success' => true,
            'invitation_slug' => $invitation->slug,
            'couple_names' => $coupleNames,
            'total' => count($rows),
            'guests' => $rows,
        ]);
    }

    /**
     * Fallback direct download of all guests and their links as Excel-compatible CSV.
     */
    public function exportFile(Request $request)
    {
        $invitationId = $request->input('invitation_id');
        $invitation = Invitation::with(['couple'])->findOrFail($invitationId);
        $slug = $invitation->slug;

        $guests = Guest::where('invitation_id', $invitationId)
            ->with(['rsvp'])
            ->orderBy('id', 'asc')
            ->get();

        $coupleNames = $invitation->couple 
            ? $invitation->couple->partner_one_name . ' & ' . $invitation->couple->partner_two_name 
            : $invitation->slug;

        $filename = "daftar-tamu-link-{$slug}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($guests, $coupleNames) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Nama Tamu',
                'Kota / Alamat',
                'Nomor WhatsApp',
                'Link Khusus Undangan',
                'Format Pesan WhatsApp Siap Kirim',
                'Status Kirim WA',
                'Konfirmasi RSVP',
                'Jumlah Hadir (Pax)'
            ]);

            foreach ($guests as $index => $guest) {
                $location = !empty($guest->address) ? 'di ' . $guest->address : 'di Tempat';
                $invitationUrl = $guest->invitation_url;

                $waMessage = "Kepada Yth. {$guest->name} {$location}, Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i ke pernikahan kami {$coupleNames}. Tautan: {$invitationUrl}";

                $rsvpStatus = 'Belum Konfirmasi';
                $pax = '-';
                if ($guest->rsvp) {
                    if ($guest->rsvp->attendance_status === 'attending') {
                        $rsvpStatus = 'Hadir';
                        $pax = $guest->rsvp->guest_count ?? 1;
                    } elseif ($guest->rsvp->attendance_status === 'not_attending') {
                        $rsvpStatus = 'Tidak Hadir';
                        $pax = 0;
                    } elseif ($guest->rsvp->attendance_status === 'tentative') {
                        $rsvpStatus = 'Ragu-ragu';
                        $pax = $guest->rsvp->guest_count ?? 1;
                    }
                }

                fputcsv($handle, [
                    $index + 1,
                    $guest->name,
                    $guest->address ?: '-',
                    $guest->phone ?: '-',
                    $invitationUrl,
                    $waMessage,
                    $guest->invitation_sent_at ? 'Sudah Dikirim' : 'Belum Dikirim',
                    $rsvpStatus,
                    $pax,
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }
}
