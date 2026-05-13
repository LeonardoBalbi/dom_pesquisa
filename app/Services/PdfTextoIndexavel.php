<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use Throwable;

/**
 * Leitura da camada de texto do PDF (Smalot\PdfParser), equivalente conceitual a {@see \Smalot\PdfParser\Document::getText()}.
 *
 * O resultado deve ser persistido na coluna `conteudo_indexado` do model {@see \App\Models\Edicao} para FULLTEXT e snippets.
 * PDF só com imagem (sem texto embutido) tende a retornar string vazia — não há OCR aqui.
 */
final class PdfTextoIndexavel
{
    public static function extrairDeArquivo(string $caminhoAbsoluto): string
    {
        if ($caminhoAbsoluto === '' || ! is_readable($caminhoAbsoluto)) {
            return '';
        }

        try {
            $parser = new Parser;
            $pdf = $parser->parseFile($caminhoAbsoluto);
            $raw = $pdf->getText();
        } catch (Throwable) {
            return '';
        }

        return self::normalizarEspacos($raw);
    }

    public static function normalizarEspacos(string $raw): string
    {
        $t = preg_replace('/\s+/u', ' ', trim($raw));

        return is_string($t) ? $t : '';
    }
}
