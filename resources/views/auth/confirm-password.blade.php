@extends('layouts.guest')

@section('content')
    <div class="mb-4 text-muted small">
        Esta é uma área segura do aplicativo. Por favor, confirme sua senha antes de continuar.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-dark px-4">
                Confirmar
            </button>
        </div>
    </form>
@endsection
