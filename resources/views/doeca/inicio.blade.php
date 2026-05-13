@extends('layouts.public')

@php
    use App\Models\Edicao;
@endphp

@section('title', 'Diário Oficial - Início')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        .search-container { max-width: 700px; margin: 0 auto; }
        .search-input-group {
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #dfe1e5;
            transition: all 0.3s ease;
            position: relative;
        }
        .search-input-group:focus-within {
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.25);
            border-color: #0d6efd;
        }
        .search-form-control {
            border: none;
            padding: 15px 25px;
            font-size: 1.1rem;
        }
        .search-form-control:focus { box-shadow: none; }
        .btn-search-custom {
            background-color: #0d6efd;
            color: white;
            padding: 0 30px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-search-custom:hover { background-color: #0b5ed7; color: white; }
        .card-custom { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .autocomplete-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border: 1px solid #dfe1e5;
            border-top: none;
            border-radius: 0 0 24px 24px;
            box-shadow: 0 4px 6px rgba(32, 33, 36, 0.28);
            margin-top: -1px;
            overflow: hidden;
            display: none;
        }
        .autocomplete-suggestion {
            padding: 10px 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .autocomplete-suggestion:hover, .autocomplete-suggestion.active { background-color: #f1f3f4; }
        .autocomplete-suggestion i { color: #70757a; font-size: 0.9rem; }
        .advanced-search-panel {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee;
            margin-top: 15px;
            padding: 20px;
            text-align: left;
        }
        .advanced-toggle {
            font-size: 0.9rem;
            color: #0d6efd;
            text-decoration: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
        }
        .advanced-toggle:hover { text-decoration: underline; }
        .snippet {
            font-size: 0.85rem;
            color: #555;
            margin-top: 5px;
            line-height: 1.4;
        }
        .doeca-inicio .highlight {
            background-color: #fff3cd;
            padding: 0 2px;
            font-weight: bold;
            color: #856404;
            border-radius: 0;
        }
    </style>
@endpush

@section('content')
    <div class="doeca-inicio text-center mb-4 mt-2">
        <h2 class="text-primary mb-3 fw-bold">Consulta de Diários Oficiais</h2>
        <p class="text-muted mb-4">Pesquise por número da edição, ano, ou termos dentro dos documentos.</p>

        <form method="GET" action="{{ route('doeca.inicio') }}" class="search-container text-start">
            <div class="input-group search-input-group">
                <input type="text" name="busca" id="inputBusca" class="form-control search-form-control"
                    placeholder='Pesquise por palavras, frases exatas entre aspas...'
                    value="{{ $busca }}" autocomplete="off">

                <button type="button" id="btnClear" class="btn btn-link text-muted position-absolute"
                    style="right: 140px; top: 50%; transform: translateY(-50%); z-index: 10; {{ $busca === '' ? 'display:none' : '' }};">
                    <i class="fas fa-times"></i>
                </button>

                <button type="submit" class="btn btn-search-custom">
                    <i class="fas fa-search"></i> PESQUISAR
                </button>

                <div id="suggestions" class="autocomplete-suggestions"></div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-2 px-2 flex-wrap gap-2">
                <button type="button" class="advanced-toggle border-0 bg-transparent p-0" id="btnAdvanced" aria-expanded="false">
                    <i class="fas fa-sliders-h"></i>
                    <span>Busca Avançada</span>
                </button>
                <div class="text-muted small">
                    Dica: use <strong>"frase"</strong> para busca exata e <strong>-termo</strong> para excluir.
                </div>
            </div>

            <div class="advanced-search-panel mt-2" id="panelAdvanced" style="display: {{ ($edicaoFiltro || $dataInicio || $dataFim) ? 'block' : 'none' }};">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Número da Edição</label>
                        <input type="text" name="edicao" class="form-control form-control-sm" placeholder="Ex: 123/2024" value="{{ $edicaoFiltro }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Data Inicial</label>
                        <input type="date" name="data_inicio" class="form-control form-control-sm" value="{{ $dataInicio }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Data Final</label>
                        <input type="date" name="data_fim" class="form-control form-control-sm" value="{{ $dataFim }}">
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="button" class="btn btn-sm btn-link text-danger" id="btnResetAdvanced">Limpar Filtros</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card card-custom bg-white mb-4">
        <div class="card-body p-4">
            @if($hasFilter)
                <div class="alert alert-info d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <span>
                        <i class="fas fa-filter me-2"></i> Filtros ativos:
                        <strong>
                            @php
                                $partes = [];
                                if ($busca !== '') {
                                    $partes[] = '"'.e($busca).'"';
                                }
                                if ($edicaoFiltro !== '') {
                                    $partes[] = 'Edição: '.e($edicaoFiltro);
                                }
                                if ($dataInicio) {
                                    $partes[] = 'Desde: '.\Carbon\Carbon::parse($dataInicio)->format('d/m/Y');
                                }
                                if ($dataFim) {
                                    $partes[] = 'Até: '.\Carbon\Carbon::parse($dataFim)->format('d/m/Y');
                                }
                            @endphp
                            {!! implode(' + ', $partes) !!}
                        </strong>
                    </span>
                    <a href="{{ route('doeca.inicio') }}" class="btn btn-sm btn-outline-dark">Limpar Todos</a>
                </div>
            @endif

            <details class="mb-3 small text-body-secondary">
                <summary class="fw-medium" style="cursor: pointer;">Sobre o trecho exibido na busca</summary>
                <div class="mt-2 lh-base">
                    <p class="mb-2">
                        O texto abaixo do número da edição vem do campo <span class="font-monospace">conteudo_indexado</span>
                        (texto extraído do PDF ao publicar). O MySQL usa <span class="font-monospace">MATCH…AGAINST</span>;
                        o trecho segue a lógica de <span class="font-monospace">gerarSnippet()</span> do projeto legado em <span class="font-monospace">doeca/config.php</span>.
                    </p>
                    <p class="mb-0">PDF escaneado sem camada de texto pode não gerar trecho útil (sem OCR neste fluxo).</p>
                </div>
            </details>

            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>Nº Edição</th>
                            <th>Data Publicação</th>
                            <th>Data Upload</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($edicoes as $ed)
                            <tr>
                                <td>
                                    <strong>{{ $ed->numero_edicao }}</strong>
                                    @if($busca !== '')
                                        <div class="snippet">{!! Edicao::gerarSnippetDestacado($ed->conteudo_indexado, $busca) !!}</div>
                                    @endif
                                </td>
                                <td>{{ $ed->data_publicacao?->format('d/m/Y') }}</td>
                                <td>{{ $ed->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="text-center text-nowrap">
                                    <a href="{{ route('doeca.edicao.view', $ed) }}" class="btn btn-sm btn-info text-white me-1" title="Visualizar" target="_blank" rel="noopener noreferrer">
                                        <i class="fas fa-eye"></i> Visualizar
                                    </a>
                                    <a href="{{ route('doeca.edicao.download', $ed) }}" class="btn btn-sm btn-secondary" title="Baixar PDF">
                                        <i class="fas fa-download"></i> Baixar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    @if($busca !== '')
                                        <i class="fas fa-file-circle-xmark fa-2x mb-3 d-block"></i>
                                        <p class="mb-0">Nenhum documento encontrado para sua pesquisa.</p>
                                    @else
                                        <p class="mb-0">Nenhuma edição cadastrada.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $edicoes->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            const $panel = $('#panelAdvanced');
            $('#btnAdvanced').on('click', function () {
                $panel.slideToggle(200);
                $(this).attr('aria-expanded', $panel.is(':visible'));
            });
            $('#btnResetAdvanced').on('click', function () {
                $('input[name="edicao"]').val('');
                $('input[name="data_inicio"]').val('');
                $('input[name="data_fim"]').val('');
            });

            const $input = $('#inputBusca');
            const $suggestions = $('#suggestions');
            const $btnClear = $('#btnClear');
            const urlAutocomplete = @json(route('doeca.autocomplete'));

            $input.on('input', function () {
                const query = $(this).val();
                if (query.length > 0) { $btnClear.show(); } else { $btnClear.hide(); }
                if (query.length < 2) { $suggestions.hide(); return; }
                $.getJSON(urlAutocomplete, { q: query }, function (data) {
                    if (data.length > 0) {
                        let html = '';
                        data.forEach(function (item) {
                            const esc = $('<div>').text(item).html();
                            html += '<div class="autocomplete-suggestion"><i class="fas fa-search"></i><span>' + esc + '</span></div>';
                        });
                        $suggestions.html(html).show();
                    } else {
                        $suggestions.hide();
                    }
                });
            });

            $input.on('focus', function () {
                if ($(this).val().length >= 2) { $(this).trigger('input'); }
            });

            $btnClear.on('click', function () {
                $input.val('').focus();
                $(this).hide();
                $suggestions.hide();
            });

            $(document).on('click', '.autocomplete-suggestion', function () {
                const value = $(this).find('span').last().text();
                $input.val(value);
                $suggestions.hide();
                $input.closest('form').submit();
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('.search-input-group').length) {
                    $suggestions.hide();
                }
            });
        });
    </script>
@endpush
