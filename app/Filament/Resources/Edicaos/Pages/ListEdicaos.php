<?php

namespace App\Filament\Resources\Edicaos\Pages;

use App\Filament\Resources\Edicaos\EdicaoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEdicaos extends ListRecords
{
    protected static string $resource = EdicaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
