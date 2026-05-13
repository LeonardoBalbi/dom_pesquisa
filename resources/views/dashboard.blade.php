@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <h5 class="card-title fw-bold">Bem-vindo!</h5>
            <p class="card-text text-muted">Você está logado no sistema.</p>
            
            <div class="row mt-4 g-3">
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded border">
                        <h6 class="text-uppercase small fw-bold text-muted mb-1">Última Visita</h6>
                        <p class="mb-0 fw-bold">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded border">
                        <h6 class="text-uppercase small fw-bold text-muted mb-1">Usuário</h6>
                        <p class="mb-0 fw-bold">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
