<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rsvp extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'guest_id',
        'guest_name',
        'attendance_status',
        'guest_count',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'guest_count' => 'integer',
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
     * Matching guest record (if matched).
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
