<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Models\Service;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceInfolist
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
                    ->visible(fn (Service $record): bool => $record->trashed())
                    ->label('Дата удаления'),
                TextEntry::make('name')
                    ->label('Название'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull()
                    ->label('Описание'),
                TextEntry::make('slug')
                    ->label('Код в адресной строке'),
                IconEntry::make('is_active')
                    ->boolean()
                    ->label('Активность'),
                TextEntry::make('sort_order')
                    ->numeric()
                    ->label('Сортировка'),
            ]);
    }
}
