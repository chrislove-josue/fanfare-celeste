@extends('layouts.admin')

@section('title', 'Paramètres du site')

@section('page-title', 'Paramètres')
@section('page-subtitle', 'Configuration du site')
@section('breadcrumb')
    <li class="breadcrumb-item active">Paramètres</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Informations générales -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2 text-primary"></i>
                    Informations générales
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="site_name" class="form-label">Nom du site *</label>
                            <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                                   id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
                            @error('site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_email" class="form-label">Email de contact *</label>
                            <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                   id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" required>
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="site_description" class="form-label">Description du site</label>
                            <textarea class="form-control @error('site_description') is-invalid @enderror" 
                                      id="site_description" name="site_description" rows="2">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                            @error('site_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_phone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                   id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                            @error('contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="whatsapp_number" class="form-label">Numéro WhatsApp</label>
                            <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" 
                                   id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}">
                            @error('whatsapp_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="contact_address" class="form-label">Adresse</label>
                            <input type="text" class="form-control @error('contact_address') is-invalid @enderror" 
                                   id="contact_address" name="contact_address" value="{{ old('contact_address', $settings['contact_address'] ?? '') }}">
                            @error('contact_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Réseaux sociaux</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="facebook_url" class="form-label">
                                <i class="fab fa-facebook text-primary me-1"></i> Facebook
                            </label>
                            <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" 
                                   id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" placeholder="https://facebook.com/...">
                            @error('facebook_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="instagram_url" class="form-label">
                                <i class="fab fa-instagram text-danger me-1"></i> Instagram
                            </label>
                            <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" 
                                   id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" placeholder="https://instagram.com/...">
                            @error('instagram_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="youtube_url" class="form-label">
                                <i class="fab fa-youtube text-danger me-1"></i> YouTube
                            </label>
                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                   id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" placeholder="https://youtube.com/...">
                            @error('youtube_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tiktok_url" class="form-label">
                                <i class="fab fa-tiktok me-1"></i> TikTok
                            </label>
                            <input type="url" class="form-control @error('tiktok_url') is-invalid @enderror" 
                                   id="tiktok_url" name="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}" placeholder="https://tiktok.com/...">
                            @error('tiktok_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Configuration</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="membership_fee" class="form-label">Cotisation annuelle (FCFA)</label>
                            <input type="number" class="form-control @error('membership_fee') is-invalid @enderror" 
                                   id="membership_fee" name="membership_fee" value="{{ old('membership_fee', $settings['membership_fee'] ?? 5000) }}">
                            @error('membership_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input type="hidden" name="maintenance_mode" value="0">
                                <input type="checkbox" class="form-check-input" id="maintenance_mode" 
                                       name="maintenance_mode" value="1" 
                                       {{ (old('maintenance_mode', $settings['maintenance_mode'] ?? false) ? 'checked' : '') }}>
                                <label class="form-check-label" for="maintenance_mode">
                                    Mode maintenance
                                </label>
                                <div class="text-muted small">Activez pour mettre le site en maintenance</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Enregistrer les paramètres
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar - Actions rapides -->
    <div class="col-lg-4">
        <!-- Cache -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-database me-2 text-warning"></i>
                    Cache
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">Vider le cache de l'application pour appliquer les modifications.</p>
                <form action="{{ route('admin.settings.cache.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-trash-alt me-2"></i> Vider le cache
                    </button>
                </form>
            </div>
        </div>

        <!-- Informations système -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-server me-2 text-info"></i>
                    Système
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Version Laravel</span>
                    <span class="fw-semibold">{{ app()->version() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">PHP Version</span>
                    <span class="fw-semibold">{{ phpversion() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Environnement</span>
                    <span class="fw-semibold">{{ app()->environment() }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Debug Mode</span>
                    <span class="badge bg-{{ config('app.debug') ? 'danger' : 'success' }}">
                        {{ config('app.debug') ? 'Activé' : 'Désactivé' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Confirmation avant de vider le cache
    document.querySelector('form[action*="cache.clear"]')?.addEventListener('submit', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir vider le cache de l\'application ?')) {
            e.preventDefault();
        }
    });
</script>
@endpush