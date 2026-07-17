<?php

namespace App\Filament\Resources\ServiceOffers\Pages;

use App\Filament\Resources\ServiceOffers\ServiceOfferResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceOffer extends ViewRecord
{
    protected static string $resource = ServiceOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
