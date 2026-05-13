<?php
// app/Models/TermoPesquisado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermoPesquisado extends Model
{
    public $timestamps = false;

    protected $table = 'termos_pesquisados';

    protected $fillable = ['termo', 'quantidade', 'ultima_busca'];

    public static function registrar(string $termo): void
    {
        if (strlen($termo) <= 2) {
            return;
        }

        $row = static::firstOrNew(['termo' => $termo]);
        $row->ultima_busca = now();
        if ($row->exists) {
            $row->increment('quantidade');
        } else {
            $row->quantidade = 1;
            $row->save();
        }
    }
}
