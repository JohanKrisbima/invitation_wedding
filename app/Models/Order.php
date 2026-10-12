<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'template_id',
        'customer_name',
        'customer_address',
        'wedding_date',
        'customer_phone',
        'total_price',
        'payment_status',
        'order_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'wedding_date' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Selected template in this order.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * The invitation instance generated for this order.
     */
    public function invitation(): HasOne
    {
        return $this->hasOne(Invitation::class);
    }
}
