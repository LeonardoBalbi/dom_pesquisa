@extends('layouts.public')

@php
    use App\Models\Edicao;
@endphp

@section('title', 'Edição ' . $edicao->numero_edicao)

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{ route('doeca.inicio', array_filter(['busca' => $filtro])) }}" class="text-decoration-none text-success">Início</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edição {{ $edicao->numero_edicao }}</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4">
                        <div>
                            <h2 class="fw-bold mb-1">{!! Edicao::htmlComDestaqueBusca('Edição '.$edicao->numero_edicao, $filtro) !!}</h2>
                            <p class="text-muted mb-0">Publicada em {{ $edicao->data_publicacao?->format('d/m/Y') }}</p>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <span class="badge bg-light text-dark border p-2">
                                {{ $edicao->visualizacoes }} visualizações
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase text-muted small">Palavras-chave</h6>
                        <p class="mb-0">{!! Edicao::htmlComDestaqueBusca($edicao->palavras_chave ?: 'Nenhuma palavra-chave cadastrada.', $filtro) !!}</p>
                    </div>

                    <div class="d-grid gap-2 d-md-flex mt-5">
                        <a href="{{ route('doeca.edicao.view', $edicao->id) }}" target="_blank" class="btn btn-success btn-lg px-5 fw-bold text-white">
                            Visualizar PDF
                        </a>
                        <a href="{{ route('doeca.edicao.download', $edicao->id) }}" class="btn btn-primary btn-lg px-5 fw-bold text-white">
                            Baixar PDF
                        </a>
                        <a href="{{ route('doeca.inicio', array_filter(['busca' => $filtro])) }}" class="btn btn-outline-secondary btn-lg px-4">
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
