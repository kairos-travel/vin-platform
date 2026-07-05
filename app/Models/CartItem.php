<?php

namespace App\Models;

use App\Enums\InputType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cart_id',
        'service_offer_id',
        'input_type',
        'input_value',
    ];

    protected $casts = [
        'input_type' => InputType::class,
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function serviceOffer(): BelongsTo
    {
        return $this->belongsTo(ServiceOffer::class);
    }
}
