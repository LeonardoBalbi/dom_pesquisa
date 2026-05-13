<?php

namespace App\Nova;

use App\Models\Edicao as EdicaoModel;
use Illuminate\Http\Resources\MergeValue;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Panel;
use Laravel\Nova\ResourceTool;

class Edicao extends Resource
{
    public static $model = EdicaoModel::class;

    public static $title = 'numero_edicao';

    public static $search = [
        'id', 'numero_edicao', 'palavras_chave', 'conteudo_indexado',
    ];

    public static $group = 'DOECA';

    /**
     * @return array<int, Field|Panel|ResourceTool|MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Categoria', 'categoria', Categoria::class)
                ->nullable()
                ->sortable()
                ->searchable(),

            Text::make('Número da edição', 'numero_edicao')
                ->sortable()
                ->rules('required', 'max:50'),

            Date::make('Data de publicação', 'data_publicacao')
                ->sortable()
                ->rules('required', 'date'),

            File::make('PDF da Edição', 'arquivo_path')
                ->disk('uploads')
                ->acceptedTypes('.pdf')
                ->rules('required', 'file', 'mimes:pdf')
                ->prunable()
                ->store(function (NovaRequest $request, $model, string $attribute, string $requestAttribute, ?string $disk, ?string $storageDir): array {
                    $file = $request->file($requestAttribute);
                    if (! $file?->isValid()) {
                        return [];
                    }

                    $categoriaId = $model->categoria_id ?? $request->input('categoria');
                    $categoriaId = $categoriaId !== null && $categoriaId !== '' ? (int) $categoriaId : null;

                    $dataPub = $model->data_publicacao ?? $request->input('data_publicacao');

                    $dir = EdicaoModel::diretorioUploadRelativo($categoriaId, $dataPub);
                    $path = $file->store($dir, $disk ?? 'uploads');

                    return [$attribute => $path];
                })
                ->help('O PDF é salvo em uploads/{categoria}/{ano}/{mês}/ conforme a categoria e a data de publicação (pastas criadas automaticamente).'),

            Text::make('Palavras-chave', 'palavras_chave')
                ->nullable()
                ->rules('max:500'),

            Textarea::make('Conteúdo indexado', 'conteudo_indexado')
                ->alwaysShow()
                ->nullable()
                ->help('Preenchido automaticamente ao salvar o PDF (Smalot\PdfParser → coluna no banco). Pode ajustar manualmente. Usado na busca FULLTEXT e no trecho da listagem.'),

            Number::make('Visualizações', 'visualizacoes')
                ->sortable()
                ->hideWhenCreating()
                ->readonly(),
        ];
    }

    /**
     * @return array<int, Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * @return array<int, Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * @return array<int, Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
