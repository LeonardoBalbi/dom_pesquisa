<?php

namespace App\Filament\Resources\Edicaos\Pages;

use App\Filament\Resources\Edicaos\EdicaoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEdicao extends EditRecord
{
    protected static string $resource = EdicaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
