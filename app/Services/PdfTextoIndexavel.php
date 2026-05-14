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

        // Aumenta o limite de memória para processar PDFs grandes
        $oldLimit = ini_get('memory_limit');
        @ini_set('memory_limit', '1024M');

        try {
            $parser = new Parser;
            $pdf = $parser->parseFile($caminhoAbsoluto);
            $raw = $pdf->getText();
        } catch (Throwable) {
            return '';
        } finally {
            // Só tenta restaurar se o consumo atual for menor que o limite antigo
            // para evitar o erro "Failed to set memory limit"
            if (self::converterParaBytes($oldLimit) > memory_get_usage()) {
                @ini_set('memory_limit', $oldLimit);
            }
        }

        return self::normalizarEspacos($raw);
    }

    private static function converterParaBytes(string $val): int
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $val = (int) $val;
        switch ($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    public static function normalizarEspacos(string $raw): string
    {
        $t = preg_replace('/\s+/u', ' ', trim($raw));

        return is_string($t) ? $t : '';
    }
}
