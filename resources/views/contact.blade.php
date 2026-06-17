@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="mb-4">Contactez-nous</h1>
            <p class="lead mb-4">Nous sommes à votre disposition pour toute information ou demande.</p>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form action="{{ route('contact.send') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nom complet *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Sujet *</label>
                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" required>
                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Message *</label>
                    <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" required></textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane"></i> Envoyer le message
                </button>
            </form>
        </div>
        
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Nos coordonnées</h4>
                    <hr>
                    <p><i class="fas fa-map-marker-alt text-primary me-2"></i> Cotonou, Bénin</p>
                    <p><i class="fas fa-phone text-primary me-2"></i> +229 97 00 00 00</p>
                    <p><i class="fab fa-whatsapp text-primary me-2"></i> +229 97 00 00 00</p>
                    <p><i class="fas fa-envelope text-primary me-2"></i> contact@fanfareceleste.org</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h4>Nos horaires</h4>
                    <hr>
                    <p><strong>Répétitions :</strong> Mercredi et Samedi de 16h à 19h</p>
                    <p><strong>Bureau :</strong> Lundi - Vendredi de 9h à 17h</p>
                    <p><strong>Permanence :</strong> Samedi de 10h à 13h</p>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-body">
                    <h4>Suivez-nous</h4>
                    <hr>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Facebook</a>
                        <a href="#" class="btn btn-outline-danger"><i class="fab fa-instagram"></i> Instagram</a>
                        <a href="#" class="btn btn-outline-danger"><i class="fab fa-youtube"></i> YouTube</a>
                        <a href="#" class="btn btn-outline-success"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Google Maps -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Notre emplacement</h4>
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
<script>
    function initMap() {
        const location = { lat: 6.3702928, lng: 2.3912362 };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: location,
        });
        const marker = new google.maps.Marker({
            position: location,
            map: map,
            title: "Fanfare Céleste"
        });
    }
</script>
@endpush