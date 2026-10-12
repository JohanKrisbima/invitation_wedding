<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'template_id',
        'slug',
        'publication_status',
        'published_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Parent order of this invitation.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Selected design template.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Couple profile and information.
     */
    public function couple(): HasOne
    {
        return $this->hasOne(Couple::class);
    }

    /**
     * Event schedules (Akad, Resepsi, etc.).
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('sort_order');
    }

    /**
     * Prewedding photo gallery.
     */
    public function galleryPhotos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class)->orderBy('sort_order');
    }

    /**
     * Guest list.
     */
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    /**
     * Attendance RSVPs.
     */
    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class)->latest();
    }

    /**
     * Guestbook messages & prayers.
     */
    public function guestMessages(): HasMany
    {
        return $this->hasMany(GuestMessage::class)->latest();
    }
}
