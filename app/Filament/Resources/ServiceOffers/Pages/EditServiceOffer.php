<?php

namespace App\Filament\Resources\ServiceOffers\Pages;

use App\Filament\Resources\ServiceOffers\ServiceOfferResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceOffer extends EditRecord
{
    protected static string $resource = ServiceOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
