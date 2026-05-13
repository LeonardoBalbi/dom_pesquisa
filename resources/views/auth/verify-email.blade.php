@extends('layouts.guest')

@section('content')
    <div class="mb-4 text-muted small">
        Obrigado por se inscrever! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você? Se você não recebeu o e-mail, teremos o prazer de lhe enviar outro.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4" role="alert">
            Um novo link de verificação foi enviado para o endereço de e-mail fornecido durante o registro.
        </div>
    @endif

    <div class="mt-4 d-flex align-items-center justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-dark px-4">
                Reenviar E-mail de Verificação
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none text-muted small">
                Sair
            </button>
        </form>
    </div>
@endsection
