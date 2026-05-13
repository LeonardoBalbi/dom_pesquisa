<?php

namespace App\Filament\Resources\Edicaos;

use App\Filament\Resources\Edicaos\Pages\CreateEdicao;
use App\Filament\Resources\Edicaos\Pages\EditEdicao;
use App\Filament\Resources\Edicaos\Pages\ListEdicaos;
use App\Filament\Resources\Edicaos\Schemas\EdicaoForm;
use App\Filament\Resources\Edicaos\Tables\EdicaosTable;
use App\Models\Edicao;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EdicaoResource extends Resource
{
    protected static ?string $model = Edicao::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'numero_edicao';

    public static function form(Schema $schema): Schema
    {
        return EdicaoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EdicaosTable::configure($table);
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
            'index' => ListEdicaos::route('/'),
            'create' => CreateEdicao::route('/create'),
            'edit' => EditEdicao::route('/{record}/edit'),
        ];
    }
}
