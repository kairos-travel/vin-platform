<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOfferIntegrationStep extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_offer_id',
        'provider',
        'step_order',
        'document_type',
    ];

    public function serviceOffer(): BelongsTo
    {
        return $this->belongsTo(ServiceOffer::class);
    }
}
