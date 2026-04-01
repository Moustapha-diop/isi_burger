@extends('layouts.app')
@section('title', 'Connexion – ISI BURGER')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="text-center fw-bold mb-4">
                     <span style="color:var(--isi-yellow)">ISI BURGER</span>
                </h4>
                <h5 class="mb-3 text-center text-muted">Connexion</h5>

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-isi w-100">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
                    </button>
                </form>

                <hr>
                <p class="text-center text-muted small mb-0">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" class="text-danger">S'inscrire</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
