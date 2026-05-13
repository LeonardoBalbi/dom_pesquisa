<?php

namespace App\Filament\Resources\Edicaos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EdicaosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('categoria.nome')
                    ->label('Categoria')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('numero_edicao')
                    ->label('Número')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('data_publicacao')
                    ->label('Publicação')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('visualizacoes')
                    ->label('Visitas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
