<?php

namespace App\Models;

use App\Enums\InputType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'service_offer_id',
        'price_snapshot',
        'input_type',
        'input_value',
    ];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
        'input_type' => InputType::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function serviceOffer(): BelongsTo
    {
        return $this->belongsTo(ServiceOffer::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
