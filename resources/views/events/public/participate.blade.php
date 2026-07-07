@extends('layouts.app')

@section('title', 'S\'inscrire à l\'événement - ' . $event->title)

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm" style="border-radius: 15px; overflow: hidden;">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Inscription à l'événement
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Informations de l'événement -->
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt fa-2x me-3"></i>
                                <div>
                                    <h5 class="mb-1">{{ $event->title }}</h5>
                                    <p class="mb-0 text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ optional($event->start_date)->format('d/m/Y à H:i') }}
                                        @if($event->end_date)
                                            <span class="mx-2">→</span>
                                            {{ optional($event->end_date)->format('d/m/Y à H:i') }}
                                        @endif
                                    </p>
                                    @if($event->location)
                                        <p class="mb-0 text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $event->location }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire -->
                        <form action="{{ route('public.events.participate.submit', $event) }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- Prénom -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user me-1 text-primary"></i>
                                        Prénom *
                                    </label>
                                    <input type="text" 
                                           name="first_name" 
                                           class="form-control @error('first_name') is-invalid @enderror" 
                                           value="{{ old('first_name', $prefilledData['first_name'] ?? '') }}"
                                           placeholder="Votre prénom"
                                           required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nom -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user me-1 text-primary"></i>
                                        Nom *
                                    </label>
                                    <input type="text" 
                                           name="last_name" 
                                           class="form-control @error('last_name') is-invalid @enderror" 
                                           value="{{ old('last_name', $prefilledData['last_name'] ?? '') }}"
                                           placeholder="Votre nom"
                                           required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Téléphone -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-phone me-1 text-primary"></i>
                                        Téléphone *
                                    </label>
                                    <input type="tel" 
                                           name="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $prefilledData['phone'] ?? '') }}"
                                           placeholder="+229 97 00 00 00"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- WhatsApp (optionnel) -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fab fa-whatsapp me-1 text-success"></i>
                                        WhatsApp
                                    </label>
                                    <input type="tel" 
                                           name="whatsapp" 
                                           class="form-control @error('whatsapp') is-invalid @enderror" 
                                           value="{{ old('whatsapp', $prefilledData['whatsapp'] ?? '') }}"
                                           placeholder="+229 97 00 00 00">
                                    @error('whatsapp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Optionnel</small>
                                </div>

                                <!-- Message d'information si connecté -->
                                @auth
                                    <div class="col-12">
                                        <div class="alert alert-success mb-0">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Vous êtes connecté(e) !</strong>
                                            Vos informations ont été pré-remplies.
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Vous n'êtes pas connecté(e).</strong>
                                            
                                        </div>
                                    </div>
                                @endauth

                                <!-- Boutons -->
                                <div class="col-12 mt-3">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Confirmer mon inscription
                                        </button>
                                        <a href="{{ route('public.events.show', $event) }}" class="btn btn-outline-secondary btn-lg">
                                            <i class="fas fa-times me-2"></i>
                                            Annuler
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Retour -->
                <div class="mt-4 text-center">
                    <a href="{{ route('public.events.show', $event) }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour à l'événement
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    .card-header {
        border-radius: 0;
    }
    .form-control:focus {
        border-color: #3946B2;
        box-shadow: 0 0 0 0.2rem rgba(57, 70, 178, 0.15);
    }
    .btn-primary {
        background: linear-gradient(135deg, #3946B2, #283593);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(26,35,126,0.3);
    }
    .alert {
        border-radius: 10px;
    }
</style>
@endpush