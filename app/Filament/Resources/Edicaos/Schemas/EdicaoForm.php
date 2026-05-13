<?php

namespace App\Filament\Resources\Edicaos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Edicao as EdicaoModel;
use App\Models\Categoria;
use Illuminate\Support\Str;

class EdicaoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('categoria_id')
                    ->relationship('categoria', 'nome')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                TextInput::make('numero_edicao')
                    ->required()
                    ->maxLength(50),
                DatePicker::make('data_publicacao')
                    ->required()
                    ->displayFormat('d/m/Y'),
                FileUpload::make('arquivo_path')
                    ->label('PDF da Edição')
                    ->disk('uploads')
                    ->directory(function ($get) {
                        $categoriaId = $get('categoria_id');
                        $dataPub = $get('data_publicacao');
                        return EdicaoModel::diretorioUploadRelativo($categoriaId, $dataPub);
                    })
                    ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                        $categoriaId = $get('categoria_id');
                        $numero = $get('numero_edicao');
                        $dataPub = $get('data_publicacao');
                        
                        $categoriaNome = 'sem-categoria';
                        if ($categoriaId) {
                            $categoriaNome = Categoria::whereKey($categoriaId)->value('nome') ?? 'categoria-'.$categoriaId;
                        }

                        $dataStr = \Illuminate\Support\Carbon::parse($dataPub)->format('d-m-Y');
                        return Str::slug($categoriaNome . '-' . $numero . '-' . $dataStr) . '.pdf';
                    })
                    ->acceptedFileTypes(['application/pdf'])
                    ->required(fn ($operation) => $operation === 'create')
                    ->preserveFilenames(false),
                Textarea::make('conteudo_indexado')
                    ->columnSpanFull()
                    ->rows(10),
                TextInput::make('palavras_chave')
                    ->maxLength(500),
                TextInput::make('visualizacoes')
                    ->disabled()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
