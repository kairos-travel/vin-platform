<?php

namespace App\Filament\Resources\ServiceOffers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceOfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->required()
                    ->label('Связанная услуга'),
                TextInput::make('name')
                    ->required()
                    ->label('Название'),
                TextInput::make('slug')
                    ->required()
                    ->label('Код в адресной строке'),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->label('Описание'),
                Toggle::make('is_active')
                    ->required()
                    ->label('Активность'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(100)
                    ->label('Сортировка'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('Цена'),
                TextInput::make('document_types')
                    ->label('Типы документов'),
            ]);
    }
}
