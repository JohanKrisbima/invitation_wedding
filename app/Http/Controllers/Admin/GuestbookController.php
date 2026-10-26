<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestMessage;
use App\Models\Invitation;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GuestbookController extends Controller
{
    /**
     * Display RSVP and Guestbook messages with metrics, search, and moderation.
     */
    public function index(Request $request)
    {
        // 1. All available invitations for switcher
        $invitations = Invitation::with(['couple', 'order'])->latest()->get();

        // 2. Determine selected invitation
        $selectedInvitationId = $request->input('invitation_id') ?: ($invitations->first()->id ?? null);
        $selectedInvitation = $selectedInvitationId 
            ? Invitation::with(['couple', 'order'])->find($selectedInvitationId) 
            : null;

        if (!$selectedInvitation) {
            return view('admin.guestbook.index', [
                'invitations' => $invitations,
                'selectedInvitation' => null,
                'rsvps' => collect(),
                'messages' => collect(),
                'totalRsvps' => 0,
                'attendingCount' => 0,
                'attendingPax' => 0,
                'notAttendingCount' => 0,
                'tentativeCount' => 0,
                'totalMessages' => 0,
                'approvedMessages' => 0,
                'hiddenMessages' => 0,
            ]);
        }

        // 3. Metric Calculations
        $totalRsvps = Rsvp::where('invitation_id', $selectedInvitationId)->count();
        $attendingCount = Rsvp::where('invitation_id', $selectedInvitationId)->where('attendance_status', 'attending')->count();
        $attendingPax = (int) Rsvp::where('invitation_id', $selectedInvitationId)->where('attendance_status', 'attending')->sum('guest_count');
        $notAttendingCount = Rsvp::where('invitation_id', $selectedInvitationId)->where('attendance_status', 'not_attending')->count();
        $tentativeCount = Rsvp::where('invitation_id', $selectedInvitationId)->where('attendance_status', 'tentative')->count();

        $totalMessages = GuestMessage::where('invitation_id', $selectedInvitationId)->count();
        $approvedMessages = GuestMessage::where('invitation_id', $selectedInvitationId)->where('is_approved', true)->count();
        $hiddenMessages = GuestMessage::where('invitation_id', $selectedInvitationId)->where('is_approved', false)->count();

        // 4. Query RSVPs
        $rsvpQuery = Rsvp::where('invitation_id', $selectedInvitationId)
            ->with(['guest'])
            ->latest();

        if ($search = $request->input('rsvp_search')) {
            $rsvpQuery->where(function ($q) use ($search) {
                $q->where('guest_name', 'ILIKE', "%{$search}%")
                  ->orWhere('note', 'ILIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('rsvp_status')) {
            if (in_array($status, ['attending', 'not_attending', 'tentative'])) {
                $rsvpQuery->where('attendance_status', $status);
            }
        }

        $rsvps = $rsvpQuery->paginate(15, ['*'], 'rsvp_page')->withQueryString();

        // 5. Query Guestbook Messages
        $msgQuery = GuestMessage::where('invitation_id', $selectedInvitationId)
            ->with(['guest'])
            ->latest();

        if ($search = $request->input('msg_search')) {
            $msgQuery->where(function ($q) use ($search) {
                $q->where('guest_name', 'ILIKE', "%{$search}%")
                  ->orWhere('message', 'ILIKE', "%{$search}%");
            });
        }

        if ($approval = $request->input('msg_status')) {
            if ($approval === 'approved') {
                $msgQuery->where('is_approved', true);
            } elseif ($approval === 'hidden') {
                $msgQuery->where('is_approved', false);
            }
        }

        $messages = $msgQuery->paginate(15, ['*'], 'msg_page')->withQueryString();

        // 6. Return AJAX partials if requested
        if ($request->ajax() || $request->header('X-Alpine-Request')) {
            $target = $request->input('partial', 'rsvp');
            if ($target === 'messages') {
                return view('admin.guestbook._messages_list', compact('messages', 'selectedInvitation'))->render();
            }
            return view('admin.guestbook._rsvp_table', compact('rsvps', 'selectedInvitation'))->render();
        }

        return view('admin.guestbook.index', compact(
            'invitations',
            'selectedInvitation',
            'rsvps',
            'messages',
            'totalRsvps',
            'attendingCount',
            'attendingPax',
            'notAttendingCount',
            'tentativeCount',
            'totalMessages',
            'approvedMessages',
            'hiddenMessages'
        ));
    }

    /**
     * Toggle approval status of a guestbook message.
     */
    public function toggleApproval(GuestMessage $message)
    {
        $message->update([
            'is_approved' => !$message->is_approved,
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_approved' => $message->is_approved,
                'message' => $message->is_approved ? 'Ucapan disetujui & ditampilkan ke publik.' : 'Ucapan disembunyikan dari publik.',
            ]);
        }

        return back()->with('success', $message->is_approved 
            ? "Ucapan dari '{$message->guest_name}' kini disetujui & tampil di undangan." 
            : "Ucapan dari '{$message->guest_name}' disembunyikan.");
    }

    /**
     * Delete a guestbook message.
     */
    public function destroyMessage(GuestMessage $message)
    {
        $guestName = $message->guest_name;
        $message->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Ucapan dari '{$guestName}' berhasil dihapus.",
            ]);
        }

        return back()->with('success', "Ucapan dari '{$guestName}' berhasil dihapus.");
    }

    /**
     * Delete an RSVP record.
     */
    public function destroyRsvp(Rsvp $rsvp)
    {
        $guestName = $rsvp->guest_name;
        $rsvp->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Data RSVP '{$guestName}' berhasil dihapus.",
            ]);
        }

        return back()->with('success', "Data RSVP '{$guestName}' berhasil dihapus.");
    }

    /**
     * JSON Data Endpoint for client-side SheetJS RSVP export.
     */
    public function exportRsvpData(Request $request)
    {
        $invitationId = $request->input('invitation_id');
        $invitation = Invitation::with(['couple'])->find($invitationId);

        if (!$invitation) {
            return response()->json(['success' => false, 'message' => 'Undangan tidak ditemukan.'], 404);
        }

        $rsvps = Rsvp::where('invitation_id', $invitationId)->with('guest')->latest()->get();

        $rows = [];
        foreach ($rsvps as $idx => $r) {
            $statusLabel = match($r->attendance_status) {
                'attending' => 'Hadir',
                'not_attending' => 'Tidak Hadir',
                'tentative' => 'Ragu-ragu',
                default => 'Belum Konfirmasi',
            };

            $rows[] = [
                'no' => $idx + 1,
                'guest_name' => $r->guest_name,
                'phone' => $r->guest->phone ?? '-',
                'address' => $r->guest->address ?? '-',
                'status' => $statusLabel,
                'guest_count' => $r->attendance_status === 'attending' ? $r->guest_count : 0,
                'note' => $r->note ?: '-',
                'created_at' => $r->created_at->translatedFormat('d M Y, H:i'),
            ];
        }

        return response()->json([
            'success' => true,
            'invitation_slug' => $invitation->slug,
            'total' => count($rows),
            'rsvps' => $rows,
        ]);
    }

    /**
     * JSON Data Endpoint for client-side SheetJS Messages export.
     */
    public function exportMessagesData(Request $request)
    {
        $invitationId = $request->input('invitation_id');
        $invitation = Invitation::with(['couple'])->find($invitationId);

        if (!$invitation) {
            return response()->json(['success' => false, 'message' => 'Undangan tidak ditemukan.'], 404);
        }

        $messages = GuestMessage::where('invitation_id', $invitationId)->with('guest')->latest()->get();

        $rows = [];
        foreach ($messages as $idx => $m) {
            $rows[] = [
                'no' => $idx + 1,
                'guest_name' => $m->guest_name,
                'phone' => $m->guest->phone ?? '-',
                'message' => $m->message,
                'status' => $m->is_approved ? 'Ditampilkan' : 'Disembunyikan',
                'created_at' => $m->created_at->translatedFormat('d M Y, H:i'),
            ];
        }

        return response()->json([
            'success' => true,
            'invitation_slug' => $invitation->slug,
            'total' => count($rows),
            'messages' => $rows,
        ]);
    }
}
