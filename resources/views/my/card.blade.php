@extends('layouts.admin')

@section('title', 'Ma carte de membre')

@section('page-title', 'Ma carte de membre')
@section('page-subtitle', 'Carte d\'identité de la Fanfare Céleste')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('my.profile') }}">Mon profil</a></li>
    <li class="breadcrumb-item active">Ma carte</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <!-- Message informatif -->
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Votre carte de membre</strong> - Ce document vous identifie comme membre officiel de la Fanfare Céleste.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                <!-- Carte de membre -->
                <div class="member-card-wrapper">
                    <div class="member-card" style="
                        background: linear-gradient(135deg, #1a1a3e 0%, #283593 50%, #3946B2 100%);
                        border-radius: 16px;
                        padding: 30px;
                        color: #fff;
                        position: relative;
                        overflow: hidden;
                        box-shadow: 0 10px 40px rgba(57, 70, 178, 0.3);
                        min-height: 350px;
                    ">
                        <!-- Arrière-plan décoratif -->
                        <div style="position: absolute; top: -50%; right: -20%; width: 300px; height: 300px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
                        <div style="position: absolute; bottom: -40%; left: -10%; width: 200px; height: 200px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
                        
                        <!-- Entête -->
                        <div class="d-flex justify-content-between align-items-start mb-3" style="position: relative; z-index: 1;">
                            <div>
                                <div style="font-size: 12px; opacity: 0.7; letter-spacing: 2px; text-transform: uppercase;">Fanfare Céleste</div>
                                <div style="font-size: 10px; opacity: 0.5;">La Trompette de Dieu de l'Atlantique</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 10px; opacity: 0.6;">N° Membre</div>
                                <div style="font-size: 18px; font-weight: 700; letter-spacing: 1px;">{{ $member->member_number ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Contenu principal -->
                        <div class="d-flex align-items-center gap-4" style="position: relative; z-index: 1;">
                            <!-- Photo -->
                            <div style="flex-shrink: 0;">
                                @if($member && $member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" 
                                         alt="{{ $member->full_name }}" 
                                         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255,255,255,0.3);">
                                @else
                                    <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.1); border: 3px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-size: 40px; font-weight: 700;">
                                        {{ strtoupper(substr($member->first_name ?? 'U', 0, 1) . substr($member->last_name ?? 'M', 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Informations -->
                            <div style="flex: 1;">
                                <div style="font-size: 20px; font-weight: 700; margin-bottom: 2px;">
                                    {{ $member->full_name ?? 'Membre' }}
                                </div>
                                <div style="font-size: 13px; opacity: 0.8; margin-bottom: 8px;">
                                    {{ $member->section ?? 'Section non définie' }}
                                </div>
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px 15px; font-size: 12px; opacity: 0.8;">
                                    <div><span style="opacity: 0.5;">Instrument :</span> {{ $member->instrument ?? '-' }}</div>
                                    <div><span style="opacity: 0.5;">Niveau :</span> {{ $member->instrument_level ?? '-' }}</div>
                                    <div><span style="opacity: 0.5;">Entrée :</span> {{ $member->entry_date ? $member->entry_date->format('d/m/Y') : '-' }}</div>
                                    <div>
                                        <span style="opacity: 0.5;">Statut :</span>
                                        @if($member && $member->status === 'active')
                                            <span style="color: #40A81B;">✓ Actif</span>
                                        @elseif($member && $member->status === 'pending')
                                            <span style="color: #ffc107;">⏳ En attente</span>
                                        @else
                                            <span style="color: #dc3545;">✗ Inactif</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pied de carte -->
                        <div style="position: relative; z-index: 1; margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: center;">
                            <div style="font-size: 9px; opacity: 0.4;">
                                Valide jusqu'au {{ $member && $member->entry_date ? $member->entry_date->addYear()->format('d/m/Y') : date('d/m/Y', strtotime('+1 year')) }}
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="font-size: 9px; opacity: 0.4;">Membre depuis {{ $member && $member->entry_date ? $member->entry_date->format('d/m/Y') : '-' }}</div>
                                <!-- QR Code (si disponible) -->
                                @if(isset($qrCode))
                                    <div style="background: #fff; padding: 5px; border-radius: 4px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" style="width: 40px; height: 40px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-4 text-center">
                    <p class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Cette carte est uniquement consultable. Pour toute modification, veuillez contacter l'administration.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('my.profile') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Retour au profil
                        </a>
                    </div>
                </div>

                <!-- Information de sécurité -->
                <div class="alert alert-secondary mt-4 mb-0" style="font-size: 12px;">
                    <i class="fas fa-shield-alt me-2"></i>
                    <strong>Document sécurisé</strong> - Cette carte est un document officiel de la Fanfare Céleste. 
                    Elle ne peut être modifiée que par l'administration.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles d'impression -->
@push('styles')
<style>
    @media print {
        .sidebar, .topbar, .btn, .alert, .navbar, .footer, .breadcrumb {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .page-content {
            padding: 0 !important;
        }
        .member-card-wrapper {
            margin: 20px auto;
        }
        .member-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        body {
            background: #fff !important;
        }
    }
</style>
@endpush

@push('scripts')
@endpush
@endsection