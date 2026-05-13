<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Categoria extends Model
{
    use LogsActivity;

    protected $table = 'categorias';

    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nome', 'descricao'])
            ->setDescriptionForEvent(fn (string $event) => "Categoria \"{$this->nome}\" foi {$event}");
    }
}
