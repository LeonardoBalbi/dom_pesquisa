<?php

namespace App\Nova;

use Illuminate\Http\Resources\MergeValue;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Panel;
use Laravel\Nova\ResourceTool;

class Categoria extends Resource
{
    public static $model = \App\Models\Categoria::class;

    public static $title = 'nome';

    public static $search = [
        'id', 'nome', 'descricao',
    ];

    public static $group = 'DOECA';

    /**
     * @return array<int, Field|Panel|ResourceTool|MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Nome', 'nome')
                ->sortable()
                ->rules('required', 'max:150')
                ->creationRules('unique:categorias,nome')
                ->updateRules('unique:categorias,nome,{{resourceId}}'),

            Textarea::make('Descrição', 'descricao')
                ->nullable()
                ->alwaysShow(),
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
