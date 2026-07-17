<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Название'),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->label('Описание'),
                TextInput::make('slug')
                    ->required()
                    ->label('Код в адресной строке'),
                Toggle::make('is_active')
                    ->required()
                    ->label('Активность'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(100)
                    ->label('Сортировка'),
            ]);
    }
}
