<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'name',
        'phone',
        'invitation_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'invitation_sent_at' => 'datetime',
        ];
    }

    /**
     * Parent invitation.
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    /**
     * RSVP status submitted by this guest.
     */
    public function rsvp(): HasOne
    {
        return $this->hasOne(Rsvp::class);
    }

    /**
     * Message / prayer submitted by this guest.
     */
    public function guestMessage(): HasOne
    {
        return $this->hasOne(GuestMessage::class);
    }

    /**
     * Helper to generate WhatsApp invitation link with personalised message.
     */
    public function getInvitationUrlAttribute(): string
    {
        return url('/' . $this->invitation->slug . '?to=' . urlencode($this->name));
    }
}
