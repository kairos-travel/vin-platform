<?php

namespace App\Filament\Resources\ServiceOffers;

use App\Filament\Resources\ServiceOffers\Pages\CreateServiceOffer;
use App\Filament\Resources\ServiceOffers\Pages\EditServiceOffer;
use App\Filament\Resources\ServiceOffers\Pages\ListServiceOffers;
use App\Filament\Resources\ServiceOffers\Pages\ViewServiceOffer;
use App\Filament\Resources\ServiceOffers\Schemas\ServiceOfferForm;
use App\Filament\Resources\ServiceOffers\Schemas\ServiceOfferInfolist;
use App\Filament\Resources\ServiceOffers\Tables\ServiceOffersTable;
use App\Models\ServiceOffer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceOfferResource extends Resource
{
    protected static ?string $model = ServiceOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'офферы';
    protected static ?string $pluralModelLabel = 'Офферы';

    public static function form(Schema $schema): Schema
    {
        return ServiceOfferForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceOfferInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceOffersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceOffers::route('/'),
            'create' => CreateServiceOffer::route('/create'),
            'view' => ViewServiceOffer::route('/{record}'),
            'edit' => EditServiceOffer::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
