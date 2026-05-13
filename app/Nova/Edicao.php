<?php

namespace App\Nova;

use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Edicao extends Resource
{
    public static $model = \App\Models\Edicao::class;

    public static $title = 'numero_edicao';

    public static $search = [
        'id', 'numero_edicao', 'palavras_chave', 'conteudo_indexado',
    ];

    public static $group = 'DOECA';

    /**
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel|\Laravel\Nova\ResourceTool|\Illuminate\Http\Resources\MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Número da edição', 'numero_edicao')
                ->sortable()
                ->rules('required', 'max:50'),

            Date::make('Data de publicação', 'data_publicacao')
                ->sortable()
                ->rules('required', 'date'),

            File::make('PDF da Edição', 'arquivo_path')
                ->disk('uploads')
                ->path(now()->format('Y/m'))
                ->acceptedTypes('.pdf')
                ->rules('required', 'file', 'mimes:pdf')
                ->prunable()
                ->help('Selecione o arquivo PDF da edição para upload.'),

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
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
