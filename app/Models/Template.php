<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'category',
        'description',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get accessible thumbnail URL or placeholder
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        return null;
    }

    /**
     * Check if template is currently used by any orders or invitations
     */
    public function canBeDeleted(): bool
    {
        return $this->orders()->doesntExist() && $this->invitations()->doesntExist();
    }

    /**
     * Orders that ordered this template.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Invitations that use this template.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }
}
