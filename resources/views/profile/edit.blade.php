@extends('layouts.app')

@section('header', 'Perfil')

@section('content')
    <div class="row g-4">
        <!-- Update Profile Information -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-1">Informações do Perfil</h5>
                    <p class="text-muted small mb-4">Atualize as informações de perfil e endereço de e-mail da sua conta.</p>

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3 mt-4">
                            <button type="submit" class="btn btn-dark px-4">Salvar</button>
                            @if (session('status') === 'profile-updated')
                                <span class="text-success small">Salvo com sucesso!</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-1">Atualizar Senha</h5>
                    <p class="text-muted small mb-4">Certifique-se de que sua conta esteja usando uma senha longa e aleatória para permanecer segura.</p>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Senha Atual</label>
                            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha</label>
                            <input id="password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3 mt-4">
                            <button type="submit" class="btn btn-dark px-4">Salvar</button>
                            @if (session('status') === 'password-updated')
                                <span class="text-success small">Senha atualizada!</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="col-md-8 mb-5">
            <div class="card shadow-sm border-0 rounded-3 border-start border-danger border-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold text-danger mb-1">Excluir Conta</h5>
                    <p class="text-muted small mb-4">Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente removidos.</p>

                    <button type="button" class="btn btn-danger px-4" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                        Excluir Conta
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
                                @csrf
                                @method('delete')
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold" id="confirmUserDeletionModalLabel">Você tem certeza que deseja excluir sua conta?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body border-0 py-0">
                                    <p class="text-muted small">Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente removidos. Por favor, insira sua senha para confirmar que deseja excluir permanentemente sua conta.</p>
                                    <div class="mt-3">
                                        <label for="delete_password" class="visually-hidden">Senha</label>
                                        <input id="delete_password" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Senha">
                                        @error('password', 'userDeletion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger">Excluir Conta</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
