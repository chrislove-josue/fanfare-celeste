@extends('layouts.admin')

@section('title', $member->full_name)

@section('page-title', $member->full_name)
@section('page-subtitle', 'Détails du membre')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Membres</a></li>
    <li class="breadcrumb-item active">{{ $member->full_name }}</li>
@endsection

@section('content')
<style>
    .document-preview {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        background: #f8fafc;
        height: 100%;
    }
    .document-preview:hover {
        border-color: #3946B2;
        background: #eef2ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .document-preview .icon {
        font-size: 3rem;
        margin-bottom: 0.5rem;
    }
    .document-preview .name {
        font-size: 0.85rem;
        font-weight: 500;
        color: #1a1a2e;
        word-break: break-all;
    }
    .document-preview .size {
        font-size: 0.75rem;
        color: #6b7280;
    }
    .document-preview-wrapper {
        position: relative;
    }
    .info-section {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .info-section .section-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f1f3f5;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-section .section-title i {
        color: #3946B2;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.4rem 0;
        border-bottom: 1px solid #f8fafc;
    }
    .info-row .label {
        color: #6b7280;
        font-size: 0.85rem;
    }
    .info-row .value {
        font-weight: 500;
        color: #1a1a2e;
        font-size: 0.9rem;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
    }
    .badge-status {
        padding: 4px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-status.active { background: #dcfce7; color: #16a34a; }
    .badge-status.pending { background: #fef3c7; color: #d97706; }
    .badge-status.inactive { background: #fee2e2; color: #dc2626; }
    .badge-status.suspended { background: #fef3c7; color: #d97706; }
    .badge-status.withdrawn { background: #e5e7eb; color: #6b7280; }
    .badge-status.deceased { background: #1f2937; color: #fff; }
    .badge-status.training { background: #dbeafe; color: #2563eb; }

    /* Styles pour le modal - identiques à index */
    #documentModal .modal-content {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    #documentModal .modal-header {
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
    }
    #documentModal .modal-body {
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        padding: 1.5rem;
    }
    #documentModal .modal-body img {
        max-height: 500px;
        max-width: 100%;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    #documentModal .modal-body iframe {
        width: 100%;
        height: 500px;
        border: none;
        border-radius: 8px;
    }
    #documentModal .modal-footer {
        background: #fff;
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        #documentModal .modal-body {
            min-height: 250px;
            padding: 1rem;
        }
        #documentModal .modal-body iframe {
            height: 300px;
        }
        #documentModal .modal-body img {
            max-height: 300px;
        }
    }
</style>

@php
    // Extraction de la paroisse et sous-région depuis l'adresse
    $paroisse = null;
    $sousRegion = null;
    if ($member->address) {
        if (preg_match('/Paroisse\s*:\s*(.*?)(?:\||$)/iu', $member->address, $m)) {
            $paroisse = trim($m[1]);
        }
        if (preg_match('/Sous[-\s]?r[ée]gion\s*:\s*(.*?)(?:\||$)/iu', $member->address, $m)) {
            $sousRegion = trim($m[1]);
        }
        // Si aucun label reconnu, on utilise le texte brut comme Paroisse
        if (!$paroisse && !$sousRegion) {
            $paroisse = trim($member->address);
        }
    }
@endphp

<div class="row g-4">
    <!-- Colonne de gauche -->
    <div class="col-lg-4">
        <!-- Photo et identité -->
        <div class="card shadow-sm">
            <div class="card-body text-center">
                @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" 
                         alt="{{ $member->full_name }}" 
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #3946B2; cursor: pointer;"
                         onclick="viewDocumentModal('{{ asset('storage/' . $member->photo) }}', '{{ $member->full_name }}', '{{ $member->member_number }}', '{{ basename($member->photo) }}')"
                         title="Cliquer pour agrandir">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 120px; height: 120px; font-size: 40px; font-weight: 700;">
                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                    </div>
                @endif
                
                <h5 class="mt-3 mb-1">{{ $member->full_name }}</h5>
                <p class="text-muted small">#{{ $member->member_number }}</p>
                
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <span class="badge-status {{ $member->status }}">
                        {{ $member->status === 'active' ? 'Actif' : ($member->status === 'pending' ? 'En attente' : ucfirst($member->status)) }}
                    </span>
                    @if($member->section)
                        <span class="badge bg-primary">{{ $member->section }}</span>
                    @endif
                    @if($member->instrument)
                        <span class="badge bg-info">{{ $member->instrument }}</span>
                    @endif
                </div>
                
                <hr>
                
                <div class="text-start">
                    <div class="info-row">
                        <span class="label">Email</span>
                        <span class="value">{{ $member->email ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Téléphone</span>
                        <span class="value">{{ $member->phone ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">WhatsApp</span>
                        <span class="value">{{ $member->whatsapp ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Paroisse</span>
                        <span class="value">{{ $paroisse ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Sous-région</span>
                        <span class="value">{{ $sousRegion ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Date de naissance</span>
                        <span class="value">{{ $member->birth_date ? $member->birth_date->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Âge</span>
                        <span class="value">{{ $member->age ?? '-' }} ans</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Lieu de naissance</span>
                        <span class="value">{{ $member->birth_place ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Date d'entrée</span>
                        <span class="value">{{ $member->entry_date ? $member->entry_date->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Membre depuis</span>
                        <span class="value">{{ $member->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Dernière modification</span>
                        <span class="value">{{ $member->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Modifier
                    </a>
                    <a href="{{ route('admin.members.card', $member) }}" target="_blank" class="btn btn-success">
                        <i class="fas fa-id-card me-2"></i> Carte de membre
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Colonne de droite -->
    <div class="col-lg-8">
        <!-- Informations musicales -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-music"></i> Informations musicales
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="label">Section</span>
                        <span class="value">{{ $member->section ?? '-' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="label">Rôle dans la section</span>
                        <span class="value">{{ $member->section_role ?? '-' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="label">Instrument</span>
                        <span class="value">{{ $member->instrument ?? '-' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="label">Pupitre</span>
                        <span class="value">{{ $member->instrument_level ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact d'urgence -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-phone-alt text-danger"></i> Contact d'urgence
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="label">Nom</span>
                        <span class="value">{{ $member->emergency_contact_name ?? '-' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="label">Téléphone</span>
                        <span class="value">{{ $member->emergency_contact_phone ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @if($member->medical_notes)
                <div class="mt-2">
                    <div class="info-row">
                        <span class="label">Notes médicales</span>
                        <span class="value" style="text-align: left; max-width: 100%;">{{ $member->medical_notes }}</span>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Compétences et notes -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-star text-warning"></i> Compétences & Notes
            </div>
            @if($member->skills)
                <div class="mb-3">
                    <span class="label d-block mb-2">Compétences</span>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach(explode(',', $member->skills) as $skill)
                            <span class="badge bg-light text-dark border">{{ trim($skill) }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if($member->notes)
                <div>
                    <span class="label d-block mb-2">Notes</span>
                    <p class="mb-0 p-2 bg-light rounded">{{ $member->notes }}</p>
                </div>
            @endif
        </div>
        
        <!-- Documents -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-file-alt text-primary"></i> Documents
            </div>
            <div class="row g-3">
                <!-- Pièce d'identité -->
                <div class="col-md-4">
                    <div class="document-preview-wrapper">
                        <div class="document-preview" onclick="viewDocumentModal(
                            '{{ $member->id_document ? asset('storage/' . $member->id_document) : '' }}',
                            '{{ $member->full_name }}',
                            '{{ $member->member_number }}',
                            '{{ $member->id_document ? basename($member->id_document) : '' }}'
                        )">
                            @if($member->id_document)
                                @php
                                    $extension = strtolower(pathinfo($member->id_document, PATHINFO_EXTENSION));
                                    $filePath = storage_path('app/public/' . $member->id_document);
                                    $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                    $fileSizeFormatted = $fileSize > 0 ? number_format($fileSize / 1024, 1) . ' KB' : 'N/A';
                                @endphp
                                <div class="icon">
                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <i class="fas fa-image text-primary"></i>
                                    @elseif($extension === 'pdf')
                                        <i class="fas fa-file-pdf text-danger"></i>
                                    @else
                                        <i class="fas fa-file-alt text-primary"></i>
                                    @endif
                                </div>
                                <div class="name">Pièce d'identité</div>
                                <div class="size">{{ $fileSizeFormatted }}</div>
                                <span class="badge bg-primary mt-2"><i class="fas fa-eye me-1"></i> Voir</span>
                            @else
                                <div class="icon"><i class="fas fa-file text-muted"></i></div>
                                <div class="name text-muted">Aucune pièce</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Lettre d'adhésion -->
                <div class="col-md-4">
                    <div class="document-preview-wrapper">
                        <div class="document-preview" onclick="viewDocumentModal(
                            '{{ $member->adhesion_letter ? asset('storage/' . $member->adhesion_letter) : '' }}',
                            '{{ $member->full_name }}',
                            '{{ $member->member_number }}',
                            '{{ $member->adhesion_letter ? basename($member->adhesion_letter) : '' }}'
                        )">
                            @if($member->adhesion_letter)
                                @php
                                    $extension = strtolower(pathinfo($member->adhesion_letter, PATHINFO_EXTENSION));
                                    $filePath = storage_path('app/public/' . $member->adhesion_letter);
                                    $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                    $fileSizeFormatted = $fileSize > 0 ? number_format($fileSize / 1024, 1) . ' KB' : 'N/A';
                                @endphp
                                <div class="icon">
                                    @if($extension === 'pdf')
                                        <i class="fas fa-file-pdf text-danger"></i>
                                    @elseif(in_array($extension, ['doc', 'docx']))
                                        <i class="fas fa-file-word text-primary"></i>
                                    @else
                                        <i class="fas fa-file-alt text-success"></i>
                                    @endif
                                </div>
                                <div class="name">Lettre d'adhésion</div>
                                <div class="size">{{ $fileSizeFormatted }}</div>
                                <span class="badge bg-success mt-2"><i class="fas fa-eye me-1"></i> Voir</span>
                            @else
                                <div class="icon"><i class="fas fa-file-signature text-muted"></i></div>
                                <div class="name text-muted">Aucune lettre</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Photo -->
                <div class="col-md-4">
                    <div class="document-preview-wrapper">
                        <div class="document-preview" onclick="viewDocumentModal(
                            '{{ $member->photo ? asset('storage/' . $member->photo) : '' }}',
                            '{{ $member->full_name }}',
                            '{{ $member->member_number }}',
                            '{{ $member->photo ? basename($member->photo) : '' }}'
                        )">
                            @if($member->photo)
                                <div class="icon"><i class="fas fa-camera text-info"></i></div>
                                <div class="name">Photo du membre</div>
                                <div class="size">Cliquez pour agrandir</div>
                                <span class="badge bg-info mt-2"><i class="fas fa-eye me-1"></i> Voir</span>
                            @else
                                <div class="icon"><i class="fas fa-user text-muted"></i></div>
                                <div class="name text-muted">Aucune photo</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL POUR LE DOCUMENT - IDENTIQUE À INDEX -->
<!-- ========================================== -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-id-card text-primary me-2"></i>
                    Document d'identité - <span id="documentModalMemberName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="documentModalBody">
                <div class="text-center" id="documentLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement du document...</p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex flex-wrap gap-3 me-auto">
                    <span class="text-muted small">
                        <i class="fas fa-user me-1"></i>
                        <span id="documentModalMemberInfo"></span>
                    </span>
                    <span class="text-muted small">
                        <i class="fas fa-hashtag me-1"></i>
                        <span id="documentModalMemberNumber"></span>
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <a href="#" id="documentDownloadLink" class="btn btn-primary btn-sm" download>
                        <i class="fas fa-download me-1"></i> Télécharger
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ==========================================
    // GESTION DU DOCUMENT DANS LE MODAL
    // Version identique à celle de index.blade.php
    // ==========================================

    function viewDocumentModal(documentPath, memberName, memberNumber, fileName) {
        // Mettre à jour les informations du modal
        document.getElementById('documentModalMemberName').textContent = memberName;
        document.getElementById('documentModalMemberInfo').textContent = memberName;
        document.getElementById('documentModalMemberNumber').textContent = memberNumber;
        document.getElementById('documentDownloadLink').href = documentPath;
        document.getElementById('documentDownloadLink').download = 'document_' + memberNumber + '.' + fileName.split('.').pop();
        
        // Afficher le loader
        const body = document.getElementById('documentModalBody');
        body.innerHTML = `
            <div class="text-center" id="documentLoading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement du document...</p>
            </div>
        `;
        
        // Ouvrir le modal
        const modal = new bootstrap.Modal(document.getElementById('documentModal'));
        modal.show();
        
        // Déterminer le type de fichier
        const extension = fileName.split('.').pop().toLowerCase();
        const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension);
        
        if (isImage) {
            // Charger l'image
            const img = new Image();
            img.onload = function() {
                body.innerHTML = `
                    <img src="${documentPath}" 
                         alt="Document d'identité" 
                         class="img-fluid rounded shadow-sm"
                         style="max-height: 500px; max-width: 100%; object-fit: contain;">
                `;
            };
            img.onerror = function() {
                body.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <p class="text-muted">Impossible de charger l'image</p>
                        <a href="${documentPath}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i> Ouvrir dans un nouvel onglet
                        </a>
                    </div>
                `;
            };
            img.src = documentPath;
        } else if (extension === 'pdf') {
            // Afficher le PDF dans un iframe
            body.innerHTML = `
                <iframe src="${documentPath}" 
                        style="width: 100%; height: 500px; border: none; border-radius: 8px;" 
                        frameborder="0"></iframe>
            `;
        } else {
            // Autres types de fichiers
            body.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-4x text-primary mb-3"></i>
                    <p class="text-muted">Type de fichier non prévisualisable</p>
                    <a href="${documentPath}" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i> Ouvrir le fichier
                    </a>
                </div>
            `;
        }
    }
</script>
@endpush
@endsection