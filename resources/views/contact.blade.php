@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Section principale avec bouton WhatsApp -->
        <div class="col-lg-6 mx-auto">
            <div class="text-center">
                <h1 class="display-4 fw-bold mb-4">Contactez-nous</h1>
                <p class="lead mb-5">Nous sommes à votre disposition pour toute information ou demande.</p>
                
                <!-- Message de succès si nécessaire -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Carte WhatsApp -->
                <div class="card shadow-lg border-0 mb-5" style="background: linear-gradient(135deg, #25D366, #128C7E); color: white; border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="fab fa-whatsapp" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Discutons sur WhatsApp</h2>
                        <p class="mb-4" style="font-size: 1.1rem; opacity: 0.9;">
                            Cliquez sur le bouton ci-dessous pour nous contacter directement via WhatsApp.<br>
                            Nous vous répondrons dans les plus brefs délais.
                        </p>
                        <a href="https://wa.me/22998123353" target="_blank" class="btn btn-light btn-lg px-5 py-3" style="border-radius: 50px; font-weight: 600;">
                            <i class="fab fa-whatsapp me-2" style="color: #25D366; font-size: 1.3rem;"></i>
                            Nous contacter sur WhatsApp
                        </a>
                        <div class="mt-4">
                            <small style="opacity: 0.7;">
                                <i class="fas fa-clock me-1"></i> Réponse rapide garantie
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Informations supplémentaires -->
                <div class="row g-3 mt-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-phone text-primary" style="font-size: 2rem;"></i>
                                <h6 class="fw-bold mt-2">Téléphone</h6>
                                <p class="text-muted mb-0">+229 97 00 00 00</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope text-primary" style="font-size: 2rem;"></i>
                                <h6 class="fw-bold mt-2">Email</h6>
                                <p class="text-muted mb-0">contact@fanfareceleste.org</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-map-marker-alt text-primary" style="font-size: 2rem;"></i>
                                <h6 class="fw-bold mt-2">Adresse</h6>
                                <p class="text-muted mb-0">Cotonou, Bénin</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Horaires -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-clock text-primary me-2"></i>
                            Nos horaires
                        </h5>
                        <div class="row">
                            <div class="col-sm-4">
                                <small class="text-muted">Répétitions</small>
                                <p class="fw-semibold mb-0">Mer & Sam<br>16h-19h</p>
                            </div>
                            <div class="col-sm-4">
                                <small class="text-muted">Bureau</small>
                                <p class="fw-semibold mb-0">Lun - Ven<br>9h-17h</p>
                            </div>
                            <div class="col-sm-4">
                                <small class="text-muted">Permanence</small>
                                <p class="fw-semibold mb-0">Samedi<br>10h-13h</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Réseaux sociaux -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-share-alt text-primary me-2"></i>
                            Suivez-nous
                        </h5>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="https://wa.me/22998123353" target="_blank" class="btn btn-outline-success">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
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
    .btn-light:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
</style>
@endpush

@push('scripts')
@if(env('GOOGLE_MAPS_API_KEY'))
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
@endif
<script>
    function initMap() {
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
        setTimeout(function() {
            infoWindow.open(map, marker);
        }, 1000);
    }
</script>
@endpush
@endsection