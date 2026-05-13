<?php

namespace App\Observers;

use App\Models\Edicao;
use App\Services\PdfTextoIndexavel;
use Illuminate\Support\Facades\Log;

/**
 * Ao criar/atualizar edição com novo arquivo em {@see Edicao::$arquivo_path}, grava o texto extraído do PDF em {@see Edicao::$conteudo_indexado}.
 *
 * Fluxo público que consome esse campo: busca FULLTEXT na home e trecho com {@see Edicao::gerarSnippetDestacado()}.
 */
class EdicaoObserver
{
    public function saved(Edicao $edicao): void
    {
        if ($edicao->arquivo_path === '' || str_contains($edicao->arquivo_path, '..')) {
            return;
        }

        if (! $edicao->wasChanged('arquivo_path') && ! $edicao->wasRecentlyCreated) {
            return;
        }

        $full = storage_path('app/uploads/'.$edicao->arquivo_path);
        if (! is_file($full)) {
            return;
        }

        $texto = PdfTextoIndexavel::extrairDeArquivo($full);

        try {
            if ($texto !== ($edicao->conteudo_indexado ?? '')) {
                $edicao->forceFill(['conteudo_indexado' => $texto])->saveQuietly();
            }
        } catch (\Throwable $e) {
            Log::warning('EdicaoObserver: falha ao gravar conteudo_indexado', [
                'edicao_id' => $edicao->id,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
