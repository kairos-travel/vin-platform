<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'paykeeper_id',
        'status',
        'paid_at',
        'amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'status' => PaymentStatus::class
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
