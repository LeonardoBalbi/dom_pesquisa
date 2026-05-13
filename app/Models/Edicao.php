<?php
// app/Models/Edicao.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Rastreamento do texto exibido na busca pública (ex.: trecho com "JOSE CLAUDIO", PNCP):
 *
 * 1. Coluna `conteudo_indexado`: ao salvar o PDF no Nova, {@see \App\Observers\EdicaoObserver} chama
 *    {@see \App\Services\PdfTextoIndexavel} (Smalot\PdfParser, equivalente a getText()) e grava o texto extraído.
 * 2. Filtro na home: {@see \App\Http\Controllers\EdicaoPublicController::index()} — `numero_edicao LIKE` ou
 *    `MATCH(conteudo_indexado, palavras_chave) AGAINST` (índice FULLTEXT do Laravel; o PHP legado usava só `conteudo_indexado` se o índice permitir).
 * 3. Trecho na lista: {@see gerarSnippetDestacado()} (porta `gerarSnippet()` de `doeca/config.php`).
 *
 * @see \App\Http\Controllers\EdicaoPublicController::index() View `doeca.inicio`.
 */
class Edicao extends Model
{
    use LogsActivity;

    protected $table = 'edicoes';

    protected $fillable = [
        'numero_edicao',
        'data_publicacao',
        'arquivo_path',
        'conteudo_indexado',
        'palavras_chave',
    ];

    protected $casts = [
        'data_publicacao' => 'date',
    ];

    // ── Busca (equivalente a doeca/index.php: LIKE no número OU FULLTEXT no conteúdo) ─
    public function scopeBusca($query, string $termo)
    {
        return $query->where(function ($q) use ($termo) {
            $q->where('numero_edicao', 'like', "%{$termo}%")
                ->orWhereRaw(
                    'MATCH(conteudo_indexado, palavras_chave) AGAINST (? IN BOOLEAN MODE)',
                    [$termo]
                );
        });
    }

    // ── Caminho absoluto do arquivo ────────────────────────────────────────
    public function getCaminhoAbsolutoAttribute(): string
    {
        return storage_path('app/uploads/' . $this->arquivo_path);
    }

    // ── Helpers ───────────────────────────────────────────────────────────
    public static function gerarCaminhoRelativo(string $nomeArquivo): string
    {
        return now()->format('Y/m') . '/' . $nomeArquivo;
    }

    /**
     * Porta de `gerarSnippet()` de `doeca/config.php`: recorte ~200 caracteres, "..." nas pontas e
     * `<span class="highlight">` por palavra (operadores booleanos removidos para o destaque).
     */
    public static function gerarSnippetDestacado(?string $texto, string $termo, int $tamanho = 200): string
    {
        if ($texto === null || $texto === '') {
            return '';
        }

        $termo = trim($termo);
        if ($termo === '') {
            $plain = preg_replace('/\s+/u', ' ', strip_tags($texto));

            return e(mb_substr($plain, 0, $tamanho, 'UTF-8')).'...';
        }

        $textoLimpo = strip_tags($texto);
        $textoLimpo = preg_replace('/\s+/u', ' ', $textoLimpo);
        $textoLimpo = trim((string) $textoLimpo);

        $termosLimpos = preg_replace('/[+\-><()~*"]/u', ' ', $termo);
        $palavras = preg_split('/\s+/u', $termosLimpos, -1, PREG_SPLIT_NO_EMPTY);
        $palavras = array_values(array_filter(
            $palavras,
            fn ($p) => mb_strlen((string) $p, 'UTF-8') > 2
        ));

        if ($palavras === []) {
            return e(mb_substr($textoLimpo, 0, $tamanho, 'UTF-8')).'...';
        }

        $primeiraPos = -1;
        foreach ($palavras as $p) {
            $pos = mb_stripos($textoLimpo, $p, 0, 'UTF-8');
            if ($pos !== false && ($primeiraPos === -1 || $pos < $primeiraPos)) {
                $primeiraPos = $pos;
            }
        }

        if ($primeiraPos === -1) {
            $snippet = mb_substr($textoLimpo, 0, $tamanho, 'UTF-8').'...';
        } else {
            $inicio = max(0, (int) ($primeiraPos - ($tamanho / 2)));
            $snippet = mb_substr($textoLimpo, $inicio, $tamanho, 'UTF-8');
            if ($inicio > 0) {
                $snippet = '...'.$snippet;
            }
            if (mb_strlen($textoLimpo, 'UTF-8') > ($inicio + $tamanho)) {
                $snippet .= '...';
            }
        }

        $snippetEsc = e($snippet);
        foreach ($palavras as $p) {
            $pq = preg_quote($p, '/');
            $snippetEsc = preg_replace('/('.$pq.')/iu', '<span class="highlight">$1</span>', $snippetEsc);
        }

        return $snippetEsc;
    }

    /**
     * Destaque simples na página de detalhe (título / palavras-chave).
     */
    public static function htmlComDestaqueBusca(string $texto, string $termo): string
    {
        $termo = trim($termo);
        if ($termo === '') {
            return e($texto);
        }

        $palavras = preg_split('/\s+/u', $termo, -1, PREG_SPLIT_NO_EMPTY);
        $palavras = array_values(array_filter(
            $palavras,
            fn (string $p) => mb_strlen($p, 'UTF-8') >= 2
        ));
        if ($palavras === []) {
            return e($texto);
        }

        usort($palavras, fn (string $a, string $b) => mb_strlen($b, 'UTF-8') <=> mb_strlen($a, 'UTF-8'));

        $partes = array_map(fn (string $p) => preg_quote($p, '/'), $palavras);
        $pattern = '/('.implode('|', $partes).')/iu';

        return preg_replace($pattern, '<span class="highlight">$1</span>', e($texto));
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['numero_edicao', 'data_publicacao', 'arquivo_path'])
            ->setDescriptionForEvent(fn(string $event) => "Edição {$this->numero_edicao} foi {$event}");
    }
}
