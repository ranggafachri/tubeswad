@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h1 class="h3">
                            <i class="bi bi-shield-lock"></i> Reset Password
                        </h1>
                        <p class="text-muted">Silakan masukkan password baru Anda</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Alamat Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                    placeholder="Masukkan email Anda">
                                
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password Baru') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                    name="password" required autocomplete="new-password"
                                    placeholder="Masukkan password baru">
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">{{ __('Konfirmasi Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <input id="password-confirm" type="password" class="form-control" 
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Konfirmasi password baru">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle me-2"></i>{{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 15px;
    }
    .btn-primary {
        padding: 0.8rem;
        border-radius: 8px;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    .form-control {
        border-left: none;
    }
    .form-control:focus {
        border-left: none;
        box-shadow: none;
    }
    .input-group-text i {
        color: #6c757d;
    }
</style>
@endsection
