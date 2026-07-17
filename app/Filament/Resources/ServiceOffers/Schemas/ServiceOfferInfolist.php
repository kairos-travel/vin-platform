<?php

namespace App\Filament\Resources\ServiceOffers\Schemas;

use App\Models\ServiceOffer;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceOfferInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Дата создания'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Дата обновления'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ServiceOffer $record): bool => $record->trashed())
                    ->label('Дата удаления'),
                TextEntry::make('service.name')
                    ->label('Связанная услуга'),
                TextEntry::make('name')
                    ->label('Название'),
                TextEntry::make('slug')
                    ->label('Код в адресной строке'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull()
                    ->label('Описание'),
                IconEntry::make('is_active')
                    ->boolean()
                    ->label('Активность'),
                TextEntry::make('sort_order')
                    ->numeric()
                    ->label('Сортировка'),
                TextEntry::make('price')
                    ->money()
                    ->label('Цена'),
                TextEntry::make('document_types')
                    ->label('Типы документов'),
            ]);
    }
}
