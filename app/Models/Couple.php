<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Couple extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'partner_one_name',
        'partner_one_nickname',
        'partner_one_photo',
        'partner_two_name',
        'partner_two_nickname',
        'partner_two_photo',
        'story',
    ];

    /**
     * Parent invitation.
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}
