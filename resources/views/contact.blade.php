@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Formulaire de contact -->
        <div class="col-lg-6">
            <div class="contact-form">
                <h1 class="display-5 fw-bold mb-3">Contactez-nous</h1>
                <p class="lead mb-4">Nous sommes à votre disposition pour toute information ou demande.</p>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="Votre nom complet" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="votre@email.com" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Téléphone</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" placeholder="+229 97 00 00 00">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                               value="{{ old('subject') }}" placeholder="Sujet de votre message" required>
                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                        <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" 
                                  placeholder="Écrivez votre message ici..." required>{{ old('message') }}</textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-paper-plane me-2"></i> Envoyer le message
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Informations de contact -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Nos coordonnées
                    </h4>
                    <hr>
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-map-marker-alt text-primary me-3 mt-1" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>Adresse</strong>
                            <p class="mb-0 text-muted">Cotonou, Bénin</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-phone text-primary me-3 mt-1" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>Téléphone</strong>
                            <p class="mb-0 text-muted">+229 97 00 00 00</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="fab fa-whatsapp text-success me-3 mt-1" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>WhatsApp</strong>
                            <p class="mb-0 text-muted">+229 97 00 00 00</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="fas fa-envelope text-primary me-3 mt-1" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>Email</strong>
                            <p class="mb-0 text-muted">contact@fanfareceleste.org</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">
                        <i class="fas fa-clock text-primary me-2"></i>
                        Nos horaires
                    </h4>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">🎵 Répétitions</span>
                        <span class="fw-semibold">Mercredi & Samedi 16h-19h</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">🏢 Bureau</span>
                        <span class="fw-semibold">Lun - Ven 9h-17h</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">📋 Permanence</span>
                        <span class="fw-semibold">Samedi 10h-13h</span>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">
                        <i class="fas fa-share-alt text-primary me-2"></i>
                        Suivez-nous
                    </h4>
                    <hr>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f me-2"></i> Facebook
                        </a>
                        <a href="#" class="btn btn-outline-danger">
                            <i class="fab fa-instagram me-2"></i> Instagram
                        </a>
                        <a href="#" class="btn btn-outline-danger">
                            <i class="fab fa-youtube me-2"></i> YouTube
                        </a>
                        <a href="#" class="btn btn-outline-success">
                            <i class="fab fa-whatsapp me-2"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Google Maps -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">
                        <i class="fas fa-map-marked-alt text-primary me-2"></i>
                        Notre emplacement
                    </h4>
                    <hr>
                    <div id="map" style="height: 400px; border-radius: 8px; overflow: hidden;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .contact-form .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .contact-form .form-control:focus {
        border-color: #3946B2;
        box-shadow: 0 0 0 0.2rem rgba(57, 70, 178, 0.15);
    }
    .contact-form .form-label {
        font-weight: 600;
        color: #333;
    }
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
    }
    .btn-outline-primary:hover,
    .btn-outline-danger:hover,
    .btn-outline-success:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
@if(env('GOOGLE_MAPS_API_KEY'))
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
@endif
<script>
    function initMap() {
        // Coordonnées de la Fanfare Céleste à Cotonou
        const location = { lat: 6.3702928, lng: 2.3912362 };
        
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });
        
        const marker = new google.maps.Marker({
            position: location,
            map: map,
            title: "Fanfare Céleste",
            animation: google.maps.Animation.DROP,
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                scaledSize: new google.maps.Size(40, 40)
            }
        });
        
        // Info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px; text-align: center;">
                    <h6 style="margin: 0; color: #1a237e; font-weight: bold;">🎵 Fanfare Céleste</h6>
                    <p style="margin: 5px 0 0; color: #666; font-size: 12px;">
                        La Trompette de Dieu de l'Atlantique
                    </p>
                    <p style="margin: 5px 0 0; color: #3946B2; font-size: 12px;">
                        <i class="fas fa-map-marker-alt"></i> Cotonou, Bénin
                    </p>
                </div>
            `
        });
        
        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });
        
        // Ouvrir automatiquement l'info window
        setTimeout(function() {
            infoWindow.open(map, marker);
        }, 1000);
    }
</script>
@endpush