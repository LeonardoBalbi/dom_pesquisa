<?php

namespace App\Http\Controllers;

use App\Models\Edicao;
use App\Models\TermoPesquisado;
use App\Models\VisitaDiaria;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EdicaoPublicController extends Controller
{
    /**
     * Home pública — alinhada ao legado `doeca/index.php`: parâmetro `busca` (ou `q`), filtros avançados,
     * MATCH…AGAINST + relevância, trecho `Edicao::gerarSnippetDestacado()` e tabela de resultados.
     */
    public function index(Request $request): View
    {
        try {
            VisitaDiaria::registrar();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao registrar visita: '.$e->getMessage());
        }

        $busca = trim((string) $request->get('busca', $request->get('q', '')));
        $edicaoFiltro = trim((string) $request->get('edicao', ''));
        $dataInicio = $request->get('data_inicio');
        $dataFim = $request->get('data_fim');

        $hasFilter = $busca !== '' || $edicaoFiltro !== '' || $dataInicio || $dataFim;

        if (strlen($busca) > 2) {
            TermoPesquisado::registrar($busca);
        }

        $query = Edicao::query();

        if ($busca !== '') {
            $query->where(function ($q) use ($busca) {
                $q->where('numero_edicao', 'like', '%'.$busca.'%')
                    ->orWhereRaw(
                        'MATCH(conteudo_indexado, palavras_chave) AGAINST (? IN BOOLEAN MODE)',
                        [$busca]
                    );
            });
            $query->selectRaw(
                'edicoes.*, MATCH(conteudo_indexado, palavras_chave) AGAINST (? IN BOOLEAN MODE) as relevancia',
                [$busca]
            )->orderByDesc('relevancia');
        }

        if ($edicaoFiltro !== '') {
            $query->where('numero_edicao', 'like', '%'.$edicaoFiltro.'%');
        }

        if ($dataInicio) {
            $query->whereDate('data_publicacao', '>=', $dataInicio);
        }

        if ($dataFim) {
            $query->whereDate('data_publicacao', '<=', $dataFim);
        }

        $query->orderByDesc('data_publicacao')->orderByDesc('id');

        $perPage = $hasFilter ? 25 : 100;
        $edicoes = $query->paginate($perPage)->withQueryString();

        return view('doeca.inicio', [
            'edicoes' => $edicoes,
            'busca' => $busca,
            'edicaoFiltro' => $edicaoFiltro,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'hasFilter' => $hasFilter,
        ]);
    }

    /**
     * Sugestões de busca (equivalente a `doeca/autocomplete.php`).
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $q = trim((string) $request->get('q', ''));
        if (mb_strlen($q, 'UTF-8') < 2) {
            return response()->json([]);
        }

        $like = '%'.$q.'%';
        $results = [];

        foreach (
            TermoPesquisado::query()
                ->where('termo', 'like', $like)
                ->orderByDesc('quantidade')
                ->limit(5)
                ->pluck('termo') as $termo
        ) {
            if (! in_array($termo, $results, true)) {
                $results[] = $termo;
            }
        }

        foreach (
            Edicao::query()
                ->where('numero_edicao', 'like', $like)
                ->orderByDesc('data_publicacao')
                ->limit(5)
                ->pluck('numero_edicao') as $num
        ) {
            if (! in_array($num, $results, true)) {
                $results[] = $num;
            }
        }

        return response()->json(array_values($results));
    }

    public function show(Edicao $edicao, Request $request): View
    {
        VisitaDiaria::registrar();
        $edicao->increment('visualizacoes');
        $edicao->refresh();

        $filtro = trim((string) $request->get('busca', $request->get('q', '')));

        return view('doeca.edicao-detalhe', [
            'edicao' => $edicao,
            'filtro' => $filtro,
        ]);
    }

    public function download(Edicao $edicao)
    {
        return $this->serveFile($edicao, 'attachment');
    }

    public function view(Edicao $edicao)
    {
        return $this->serveFile($edicao, 'inline');
    }

    private function serveFile(Edicao $edicao, string $disposition)
    {
        $path = $edicao->arquivo_path;
        if ($path === '' || str_contains($path, '..') || str_starts_with($path, '/')) {
            abort(404);
        }

        $full = storage_path('app/uploads/'.$path);
        if (! is_file($full)) {
            abort(404);
        }

        return response()->file($full, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition.'; filename="'.basename($path).'"',
        ]);
    }
}
