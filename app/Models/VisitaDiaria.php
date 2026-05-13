<?php
// app/Models/VisitaDiaria.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitaDiaria extends Model
{
    public $timestamps = false;

    protected $table = 'visitas_diarias';

    protected $primaryKey = 'data_visita';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['data_visita', 'quantidade'];

    public function getRouteKeyName(): string
    {
        return 'data_visita';
    }

    public static function registrar(): void
    {
        $row = static::firstOrNew(
            ['data_visita' => today()->toDateString()],
            ['quantidade' => 0]
        );
        if (! $row->exists) {
            $row->quantidade = 0;
            $row->save();
        }
        $row->increment('quantidade');
    }
}
