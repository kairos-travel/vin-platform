<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOffer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_id',
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
        'price',
        'document_types'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'document_types' => 'array'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function integrationSteps(): HasMany
    {
        return $this->hasMany(ServiceOfferIntegrationStep::class);
    }
}
