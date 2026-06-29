{{-- resources/views/auth/login.blade.php --}}

@extends('layouts.app')

@section('title', 'Connexion - Fanfare Céleste')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .login-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1.5rem;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    }

    .login-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 2.5rem 2rem;
        max-width: 420px;
        width: 100%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .login-header {
        text-align: center;
    }

    .login-header .logo {
        height: 150px;  /* ✅ Agrandi de 80px à 120px */
        width: auto;
    }

    .login-header h1 {
        color: #1a1a2e;
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .login-header p {
        color: #6b7280;
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
    }

    .form-group input[type="email"],
    .form-group input[type="password"] {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: #f9fafb;
        color: #1a1a2e;
    }

    .form-group input:focus {
        outline: none;
        border-color: #3946B2;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(57, 70, 178, 0.1);
    }

    .form-group input.error {
        border-color: #dc2626;
    }

    .form-group .error-message {
        color: #dc2626;
        font-size: 0.8rem;
        margin-top: 0.3rem;
    }

    .form-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .form-options label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #6b7280;
        cursor: pointer;
    }

    .form-options label input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #3946B2;
        border-radius: 4px;
        cursor: pointer;
    }

    /* ✅ Suppression du lien "Mot de passe oublié" */
    .form-options .forgot-password {
        display: none;
    }

    .btn-login {
        width: 100%;
        padding: 0.75rem;
        background: linear-gradient(135deg, #3946B2, #6366f1);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(57, 70, 178, 0.4);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .login-footer {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f3f5;
    }

    .login-footer p {
        color: #6b7280;
        font-size: 0.85rem;
    }

    .login-footer a {
        color: #3946B2;
        text-decoration: none;
        font-weight: 600;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }

    .alert {
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-size: 0.85rem;
        margin-bottom: 1.25rem;
    }

    .alert-success {
        background: #dcfce7;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .alert-error {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .login-container {
            padding: 1.5rem 1.25rem;
        }
        .login-header .logo {
            height: 90px;  /* ✅ Version mobile */
        }
        .login-header h1 {
            font-size: 1.25rem;
        }
    }
</style>

<div class="login-wrapper">
    <div class="login-container">
        <!-- En-tête avec logo agrandi -->
        <div class="login-header">
            <img src="{{ asset('storage/logo/logo-blanc.jpeg') }}" 
                 alt="Fanfare Céleste" 
                 class="logo">
            <h1>Bienvenue</h1>
            <p>Connectez-vous à votre espace</p>
        </div>

        <!-- Message de statut -->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <!-- Formulaire de connexion -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       required autofocus autocomplete="username"
                       class="{{ $errors->has('email') ? 'error' : '' }}">
                @if ($errors->has('email'))
                    <div class="error-message">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" 
                       required autocomplete="current-password"
                       class="{{ $errors->has('password') ? 'error' : '' }}">
                @if ($errors->has('password'))
                    <div class="error-message">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="form-options">
                <label>
                    <input type="checkbox" name="remember">
                    Se souvenir de moi
                </label>
                {{-- Lien "Mot de passe oublié" supprimé --}}
            </div>

            <button type="submit" class="btn-login">
                Se connecter
            </button>
        </form>

        <!-- Footer -->
        <div class="login-footer">
            <p>
                &copy; {{ date('Y') }} 
                <a href="{{ route('home') }}">Fanfare Céleste</a>
                — Tous droits réservés
            </p>
        </div>
    </div>
</div>
@endsection