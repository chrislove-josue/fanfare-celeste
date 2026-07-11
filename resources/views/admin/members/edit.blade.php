@extends('layouts.admin')

@section('title', 'Modifier le membre - ' . $member->full_name)

@section('page-title', 'Modifier le membre')
@section('page-subtitle', $member->full_name . ' - ' . $member->member_number)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Membres</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.members.show', $member) }}">{{ $member->full_name }}</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<style>
    .form-section {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
    }
    
    .form-section .section-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f1f3f5;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-section .section-title i {
        color: #3946B2;
    }
    
    .form-label {
        font-weight: 500;
        font-size: 0.85rem;
        color: #374151;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3946B2;
        box-shadow: 0 0 0 3px rgba(57, 70, 178, 0.1);
    }
    
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }
    
    .invalid-feedback {
        font-size: 0.8rem;
        color: #dc2626;
    }
    
    .photo-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }
    
    .photo-preview-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #6b7280;
        border: 4px solid #fff;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }
    
    .document-preview-container {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px dashed #e5e7eb;
        text-align: center;
        transition: all 0.3s ease;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .document-preview-container.has-document {
        border: 2px solid #22c55e;
        background: #f0fdf4;
    }
    
    .document-preview-container .document-icon {
        font-size: 4rem;
        margin-bottom: 0.5rem;
    }
    
    .document-preview-container .document-name {
        font-size: 0.9rem;
        font-weight: 500;
        color: #1a1a2e;
        word-break: break-all;
    }
    
    .document-preview-container .document-size {
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .document-preview {
        max-width: 100%;
        max-height: 250px;
        object-fit: contain;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    
    .document-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .document-actions .btn {
        font-size: 0.8rem;
        padding: 0.4rem 1rem;
        border-radius: 8px;
    }
    
    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafafa;
        width: 100%;
    }
    
    .upload-area:hover {
        border-color: #3946B2;
        background: #f8fafc;
    }
    
    .upload-area.dragover {
        border-color: #3946B2;
        background: #eef2ff;
    }
    
    .upload-area .upload-icon {
        font-size: 3rem;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }
    
    .upload-area .upload-text {
        font-size: 0.95rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .upload-area .upload-hint {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 0.25rem;
    }
    
    .btn-action {
        padding: 0.6rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }
    
    .badge-status {
        padding: 4px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .badge-status.active {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .badge-status.pending {
        background: #fef3c7;
        color: #d97706;
    }
    
    .badge-status.inactive {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .badge-status.suspended {
        background: #fef3c7;
        color: #d97706;
    }
    
    .badge-status.withdrawn {
        background: #e5e7eb;
        color: #6b7280;
    }
    
    .badge-status.deceased {
        background: #1f2937;
        color: #fff;
    }
    
    .badge-status.training {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .roles-select-container {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.5rem;
        background: #fafafa;
    }
    
    .roles-select-container::-webkit-scrollbar {
        width: 5px;
    }
    
    .roles-select-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .roles-select-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .role-checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 8px;
        transition: background 0.2s ease;
        cursor: pointer;
    }
    
    .role-checkbox-item:hover {
        background: #eef2ff;
    }
    
    .role-checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #3946B2;
        cursor: pointer;
        flex-shrink: 0;
    }
    
    .role-checkbox-item .role-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #1a1a2e;
        flex: 1;
    }
    
    .role-checkbox-item .role-badge {
        font-size: 0.6rem;
        padding: 2px 8px;
        border-radius: 10px;
        background: #e5e7eb;
        color: #6b7280;
        font-weight: 500;
    }
    
    .role-checkbox-item .role-badge.admin {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .role-checkbox-item .role-badge.manager {
        background: #fef3c7;
        color: #d97706;
    }
    
    .role-checkbox-item .role-badge.member {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .role-checkbox-item .role-badge.finance {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .role-checkbox-item .role-badge.super-admin {
        background: #fecaca;
        color: #b91c1c;
    }
    
    .file-info {
        background: #f1f5f9;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.5rem;
    }
    
    .file-info .file-name {
        font-size: 0.9rem;
        font-weight: 500;
        color: #1a1a2e;
    }
    
    .file-info .file-size {
        font-size: 0.8rem;
        color: #6b7280;
    }

    /* Styles pour le modal du document */
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
</style>

<div class="container-fluid py-4">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h5 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Veuillez corriger les erreurs suivantes :
            </h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.members.update', $member) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- SECTION 1 : INFORMATIONS PERSONNELLES -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-user-circle"></i>
                Informations personnelles
            </div>
            <div class="row g-3">
                <div class="col-md-3 text-center">
                    <label class="form-label">Photo</label>
                    <div class="mb-2">
                        @if($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" class="photo-preview" alt="{{ $member->full_name }}">
                        @else
                            <div class="photo-preview-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                    <small class="text-muted">Format : JPG, PNG | Taille max : 2MB</small>
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">N° Membre</label>
                    <input type="text" class="form-control bg-light" value="{{ $member->member_number }}" disabled readonly>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Statut *</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $member->status) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Date d'entrée</label>
                    <input type="date" name="entry_date" class="form-control @error('entry_date') is-invalid @enderror" 
                           value="{{ old('entry_date', optional($member->entry_date)->format('Y-m-d')) }}">
                    @error('entry_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Prénom *</label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                           value="{{ old('first_name', $member->first_name) }}" required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                           value="{{ old('last_name', $member->last_name) }}" required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $member->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Téléphone *</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ old('phone', $member->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" 
                           value="{{ old('whatsapp', $member->whatsapp) }}">
                    @error('whatsapp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Adresse</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $member->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 2 : MUSIQUE ET SECTION -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-music"></i>
                Informations musicales
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Section</label>
                    <select name="section" class="form-select @error('section') is-invalid @enderror">
                        <option value="">-- Sélectionner --</option>
                        @foreach($sections as $key => $label)
                            <option value="{{ $key }}" {{ old('section', $member->section) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('section')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rôle dans la section</label>
                    <input type="text" name="section_role" class="form-control @error('section_role') is-invalid @enderror" 
                           value="{{ old('section_role', $member->section_role) }}" placeholder="Responsable, Membre...">
                    @error('section_role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Instrument</label>
                    <select name="instrument" class="form-select @error('instrument') is-invalid @enderror">
                        <option value="">-- Sélectionner --</option>
                        @foreach($instruments as $key => $label)
                            <option value="{{ $key }}" {{ old('instrument', $member->instrument) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('instrument')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Pupitre</label>
                    <select name="instrument_level" class="form-select @error('instrument_level') is-invalid @enderror">
                        <option value="">-- Sélectionner --</option>
                        @foreach(App\Models\Member::getInstrumentLevels() as $val => $label)
                            <option value="{{ $val }}" {{ old('instrument_level', $member->instrument_level) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('instrument_level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                           value="{{ old('birth_date', optional($member->birth_date)->format('Y-m-d')) }}">
                    @error('birth_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Lieu de naissance</label>
                    <input type="text" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror" 
                           value="{{ old('birth_place', $member->birth_place) }}">
                    @error('birth_place')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 3 : DOCUMENTS -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-folder-open"></i>
                Documents
            </div>
            <div class="row g-3">
                <!-- Document d'identité -->
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-id-card text-primary me-1"></i>
                        Pièce d'identité
                    </label>
                    @if($member->id_document)
                        @php
                            $filePath = storage_path('app/public/' . $member->id_document);
                            $fileExists = file_exists($filePath);
                            $fileSize = $fileExists ? filesize($filePath) : 0;
                            $fileSizeFormatted = $fileSize > 0 ? number_format($fileSize / 1024, 1) . ' KB' : 'N/A';
                            $extension = pathinfo($member->id_document, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $documentPath = asset('storage/' . $member->id_document);
                            $fileName = basename($member->id_document);
                        @endphp
                        
                        <div class="document-preview-container has-document mb-2">
                            @if($isImage && $fileExists)
                                <img src="{{ $documentPath }}" alt="Document" class="document-preview">
                            @else
                                <div class="document-icon">
                                    <i class="fas fa-file-{{ $extension === 'pdf' ? 'pdf' : 'alt' }} text-{{ $extension === 'pdf' ? 'danger' : 'primary' }}"></i>
                                </div>
                            @endif
                            <div class="document-name">{{ $fileName }}</div>
                            <div class="document-size">{{ $fileSizeFormatted }}</div>
                            <div class="document-actions">
                                <button type="button" class="btn btn-info btn-sm" onclick="viewDocumentModal('{{ $documentPath }}', '{{ $member->full_name }}', '{{ $member->member_number }}', '{{ $fileName }}')">
                                    <i class="fas fa-eye me-1"></i> Voir
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="remove_document" id="removeDocument" value="1">
                            <label class="form-check-label text-danger" for="removeDocument">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </label>
                        </div>
                    @endif
                    
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <div class="upload-text">{{ $member->id_document ? 'Remplacer' : 'Ajouter' }}</div>
                        <div class="upload-hint">JPG, PNG, PDF, WebP | 5MB max</div>
                    </div>
                    <input type="file" name="id_document" id="id_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.webp" style="display: none;">
                    
                    <div id="selectedFileInfo" style="display: none;" class="mt-2">
                        <div class="file-info">
                            <span>
                                <i class="fas fa-file me-2 text-primary"></i>
                                <span class="file-name" id="selectedFileName"></span>
                            </span>
                            <span>
                                <span class="file-size" id="selectedFileSize"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearSelectedFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Lettre d'adhésion -->
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-file-signature text-success me-1"></i>
                        Lettre d'adhésion
                    </label>
                    @if($member->adhesion_letter)
                        @php
                            $letterPath = storage_path('app/public/' . $member->adhesion_letter);
                            $letterExists = file_exists($letterPath);
                            $letterSize = $letterExists ? filesize($letterPath) : 0;
                            $letterSizeFormatted = $letterSize > 0 ? number_format($letterSize / 1024, 1) . ' KB' : 'N/A';
                            $letterExtension = pathinfo($member->adhesion_letter, PATHINFO_EXTENSION);
                            $letterPathUrl = asset('storage/' . $member->adhesion_letter);
                            $letterName = basename($member->adhesion_letter);
                        @endphp
                        
                        <div class="document-preview-container has-document mb-2">
                            <div class="document-icon">
                                @if(strtolower($letterExtension) === 'pdf')
                                    <i class="fas fa-file-pdf text-danger"></i>
                                @elseif(in_array(strtolower($letterExtension), ['doc', 'docx']))
                                    <i class="fas fa-file-word text-primary"></i>
                                @else
                                    <i class="fas fa-file-alt text-success"></i>
                                @endif
                            </div>
                            <div class="document-name">{{ $letterName }}</div>
                            <div class="document-size">{{ $letterSizeFormatted }}</div>
                            <div class="document-actions">
                                <button type="button" class="btn btn-info btn-sm" onclick="viewDocumentModal('{{ $letterPathUrl }}', '{{ $member->full_name }}', '{{ $member->member_number }}', '{{ $letterName }}')">
                                    <i class="fas fa-eye me-1"></i> Voir
                                </button>
                                <a href="{{ $letterPathUrl }}" download class="btn btn-primary btn-sm">
                                    <i class="fas fa-download me-1"></i> Télécharger
                                </a>
                            </div>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="remove_adhesion_letter" id="removeAdhesionLetter" value="1">
                            <label class="form-check-label text-danger" for="removeAdhesionLetter">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </label>
                        </div>
                    @endif
                    
                    <div class="upload-area" id="uploadAdhesionArea">
                        <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <div class="upload-text">{{ $member->adhesion_letter ? 'Remplacer la lettre' : 'Ajouter la lettre d\'adhésion' }}</div>
                        <div class="upload-hint">PDF, DOC, DOCX, TXT, RTF | 5MB max</div>
                    </div>
                    <input type="file" name="adhesion_letter" id="adhesion_letter" class="form-control" accept=".pdf,.doc,.docx,.txt,.rtf" style="display: none;">
                    
                    <div id="selectedAdhesionFileInfo" style="display: none;" class="mt-2">
                        <div class="file-info">
                            <span>
                                <i class="fas fa-file me-2 text-primary"></i>
                                <span class="file-name" id="selectedAdhesionFileName"></span>
                            </span>
                            <span>
                                <span class="file-size" id="selectedAdhesionFileSize"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearSelectedAdhesionFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 4 : URGENCE ET NOTES -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-ambulance"></i>
                Contact d'urgence et notes
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Contact d'urgence (Nom)</label>
                    <input type="text" name="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                           value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}">
                    @error('emergency_contact_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contact d'urgence (Téléphone)</label>
                    <input type="text" name="emergency_contact_phone" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                           value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}">
                    @error('emergency_contact_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Notes médicales</label>
                    <textarea name="medical_notes" class="form-control @error('medical_notes') is-invalid @enderror" rows="3">{{ old('medical_notes', $member->medical_notes) }}</textarea>
                    @error('medical_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Compétences</label>
                    <input type="text" name="skills" class="form-control @error('skills') is-invalid @enderror" 
                           value="{{ old('skills', $member->skills) }}" placeholder="Séparées par des virgules">
                    @error('skills')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        <!-- SECTION 5 : COMPTE UTILISATEUR ET RÔLES -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-user"></i>
                Compte utilisateur et rôles
            </div>
            
            @if($member->user)
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-info-circle me-1 text-primary"></i>
                                Informations du compte
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i> 
                                    {{ $member->user->email }}
                                </span>
                                <span class="text-muted small">
                                    (ID: #{{ $member->user->id }})
                                </span>
                            </div>
                            <div class="mt-2 d-flex gap-2">
                                <button type="button" class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $member->id }}">
                                    <i class="fas fa-key me-1"></i> Réinitialiser
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-shield-alt me-1 text-primary"></i>
                                Rôles
                            </h6>
                            <div class="roles-select-container">
                                @foreach($availableRoles as $role)
                                    <label class="role-checkbox-item">
                                        <input type="checkbox" 
                                               name="roles[]" 
                                               value="{{ $role->name }}"
                                               @if(in_array($role->name, $userRoles)) checked @endif>
                                        <span class="role-label">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</span>
                                        <span class="role-badge {{ $role->name }}">
                                            {{ $role->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Sélectionnez un ou plusieurs rôles pour ce membre
                            </small>
                        </div>
                    </div>
                </div>
            @else
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="badge bg-secondary">
                        <i class="fas fa-times-circle me-1"></i> Aucun compte utilisateur
                    </span>
                    <button type="button" class="btn btn-success btn-sm" 
                            data-bs-toggle="modal" data-bs-target="#createUserModal{{ $member->id }}">
                        <i class="fas fa-user-plus me-1"></i> Créer un compte
                    </button>
                </div>
            @endif
        </div>

        <!-- INFORMATIONS SYSTÈME -->
        <div class="row g-3 mb-4">
            <div class="col-md-12">
                <div class="bg-light rounded-3 p-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="fw-semibold small text-muted">Créé le</label>
                            <p class="mb-0">{{ $member->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-semibold small text-muted">Dernière modification</label>
                            <p class="mb-0">{{ $member->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-semibold small text-muted">ID Membre</label>
                            <p class="mb-0">#{{ $member->id }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-semibold small text-muted">N° Membre</label>
                            <p class="mb-0">{{ $member->member_number }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOUTONS D'ACTION -->
        <div class="d-flex justify-content-end gap-3">
            <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary btn-action">
                <i class="fas fa-times me-2"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary btn-action">
                <i class="fas fa-save me-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>

<!-- MODAL : CRÉER UN COMPTE -->
<div class="modal fade" id="createUserModal{{ $member->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.members.create-user', $member) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2 text-success"></i>
                        Créer un compte pour {{ $member->full_name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Vous allez créer un compte utilisateur pour <strong>{{ $member->full_name }}</strong>.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email de connexion</label>
                        <input type="email" class="form-control" value="{{ $member->email }}" readonly>
                        <small class="text-muted">L'email est utilisé comme identifiant</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mot de passe *</label>
                        <div class="input-group">
                            <input type="text" name="password" id="editPassword{{ $member->id }}" 
                                   class="form-control" value="{{ Str::random(10) }}" required>
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="generateEditPassword({{ $member->id }})">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="toggleEditPasswordVisibility({{ $member->id }})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Le membre utilisera ce mot de passe pour se connecter</small>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Informations à transmettre au membre :</strong>
                        <ul class="mb-0 mt-2">
                            <li>🔗 Lien de connexion : <strong>{{ url('/login') }}</strong></li>
                            <li>📧 Email : <strong>{{ $member->email }}</strong></li>
                            <li>🔑 Mot de passe : <strong id="editPasswordDisplay{{ $member->id }}">••••••••</strong></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i> Créer le compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL : RÉINITIALISER LE MOT DE PASSE -->
<div class="modal fade" id="resetPasswordModal{{ $member->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.members.reset-password', $member) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key me-2 text-warning"></i>
                        Réinitialiser le mot de passe de {{ $member->full_name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Vous allez réinitialiser le mot de passe de <strong>{{ $member->full_name }}</strong>.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nouveau mot de passe *</label>
                        <div class="input-group">
                            <input type="text" name="password" id="editResetPassword{{ $member->id }}" 
                                   class="form-control" value="{{ Str::random(10) }}" required>
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="generateEditResetPassword({{ $member->id }})">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="toggleEditResetPasswordVisibility({{ $member->id }})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Le membre utilisera ce nouveau mot de passe</small>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Nouveau mot de passe :</strong>
                        <span id="editResetPasswordDisplay{{ $member->id }}">••••••••</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i> Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL POUR LE DOCUMENT                     -->
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

    // ==========================================
    // GESTION DU DOCUMENT D'IDENTITÉ (UPLOAD)
    // ==========================================

    function initUploadArea() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('id_document');
        
        if (uploadArea && fileInput) {
            uploadArea.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.click();
            });
            
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });
            
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                
                if (e.dataTransfer.files.length > 0) {
                    const file = e.dataTransfer.files[0];
                    if (validateFile(file)) {
                        fileInput.files = e.dataTransfer.files;
                        handleFileSelect(file);
                    }
                }
            });
            
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    if (validateFile(file)) {
                        handleFileSelect(file);
                    } else {
                        this.value = '';
                    }
                }
            });
        }
    }

    function validateFile(file) {
        const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
        const maxSize = 5 * 1024 * 1024;
        
        if (!validTypes.includes(file.type)) {
            alert('❌ Format de fichier non supporté. Utilisez JPG, PNG, WebP ou PDF.');
            return false;
        }
        
        if (file.size > maxSize) {
            alert('❌ Le fichier est trop volumineux. Taille max : 5MB.');
            return false;
        }
        
        return true;
    }

    function handleFileSelect(file) {
        const fileInfo = document.getElementById('selectedFileInfo');
        const fileName = document.getElementById('selectedFileName');
        const fileSize = document.getElementById('selectedFileSize');
        
        fileInfo.style.display = 'block';
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
        
        const uploadArea = document.getElementById('uploadArea');
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                uploadArea.innerHTML = `
                    <img src="${e.target.result}" alt="Aperçu" style="max-height: 150px; max-width: 100%; border-radius: 8px;">
                    <div class="upload-text mt-2">${file.name}</div>
                    <div class="upload-hint">${(file.size / 1024).toFixed(1)} KB</div>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            uploadArea.innerHTML = `
                <div class="upload-icon">
                    <i class="fas fa-file-pdf text-danger"></i>
                </div>
                <div class="upload-text">${file.name}</div>
                <div class="upload-hint">${(file.size / 1024).toFixed(1)} KB</div>
            `;
        }
    }

    function clearSelectedFile() {
        const fileInput = document.getElementById('id_document');
        const fileInfo = document.getElementById('selectedFileInfo');
        
        if (fileInput) {
            fileInput.value = '';
        }
        fileInfo.style.display = 'none';
        
        const uploadArea = document.getElementById('uploadArea');
        const hasDocument = {{ $member->id_document ? 'true' : 'false' }};
        const text = hasDocument ? 'Cliquez pour remplacer le document' : 'Cliquez ou glissez-déposez pour ajouter un document';
        
        uploadArea.innerHTML = `
            <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="upload-text">${text}</div>
            <div class="upload-hint">Formats acceptés : JPG, PNG, PDF, WebP | Taille max : 5MB</div>
        `;
    }

    // ==========================================
    // GESTION DE LA LETTRE D'ADHÉSION (UPLOAD)
    // ==========================================

    function initAdhesionUploadArea() {
        const uploadArea = document.getElementById('uploadAdhesionArea');
        const fileInput = document.getElementById('adhesion_letter');
        
        if (uploadArea && fileInput) {
            uploadArea.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.click();
            });
            
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });
            
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                
                if (e.dataTransfer.files.length > 0) {
                    const file = e.dataTransfer.files[0];
                    if (validateAdhesionFile(file)) {
                        fileInput.files = e.dataTransfer.files;
                        handleAdhesionFileSelect(file);
                    }
                }
            });
            
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    if (validateAdhesionFile(file)) {
                        handleAdhesionFileSelect(file);
                    } else {
                        this.value = '';
                    }
                }
            });
        }
    }

    function validateAdhesionFile(file) {
        const maxSize = 5 * 1024 * 1024;
        const validExtensions = ['pdf', 'doc', 'docx', 'txt', 'rtf'];
        const extension = file.name.split('.').pop().toLowerCase();
        
        if (!validExtensions.includes(extension)) {
            alert('❌ Format de fichier non supporté. Utilisez PDF, DOC, DOCX, TXT ou RTF.');
            return false;
        }
        
        if (file.size > maxSize) {
            alert('❌ Le fichier est trop volumineux. Taille max : 5MB.');
            return false;
        }
        
        return true;
    }

    function handleAdhesionFileSelect(file) {
        const fileInfo = document.getElementById('selectedAdhesionFileInfo');
        const fileName = document.getElementById('selectedAdhesionFileName');
        const fileSize = document.getElementById('selectedAdhesionFileSize');
        
        if (fileInfo) {
            fileInfo.style.display = 'block';
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
        }
        
        const uploadArea = document.getElementById('uploadAdhesionArea');
        if (uploadArea) {
            const extension = file.name.split('.').pop().toLowerCase();
            let icon = 'fa-file-alt text-success';
            if (extension === 'pdf') {
                icon = 'fa-file-pdf text-danger';
            } else if (['doc', 'docx'].includes(extension)) {
                icon = 'fa-file-word text-primary';
            }
            
            uploadArea.innerHTML = `
                <div class="upload-icon">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="upload-text">${file.name}</div>
                <div class="upload-hint">${(file.size / 1024).toFixed(1)} KB</div>
            `;
        }
    }

    function clearSelectedAdhesionFile() {
        const fileInput = document.getElementById('adhesion_letter');
        const fileInfo = document.getElementById('selectedAdhesionFileInfo');
        
        if (fileInput) {
            fileInput.value = '';
        }
        if (fileInfo) {
            fileInfo.style.display = 'none';
        }
        
        const uploadArea = document.getElementById('uploadAdhesionArea');
        if (uploadArea) {
            const hasDocument = {{ $member->adhesion_letter ? 'true' : 'false' }};
            const text = hasDocument ? 'Cliquez pour remplacer la lettre' : 'Cliquez ou glissez-déposez pour ajouter la lettre d\'adhésion';
            
            uploadArea.innerHTML = `
                <div class="upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div class="upload-text">${text}</div>
                <div class="upload-hint">PDF, DOC, DOCX, TXT, RTF | 5MB max</div>
            `;
        }
    }

    // ==========================================
    // GESTION DES MOTS DE PASSE
    // ==========================================
    
    function generateEditPassword(memberId) {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        let password = '';
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('editPassword' + memberId).value = password;
        document.getElementById('editPasswordDisplay' + memberId).textContent = password;
    }

    function generateEditResetPassword(memberId) {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        let password = '';
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('editResetPassword' + memberId).value = password;
        document.getElementById('editResetPasswordDisplay' + memberId).textContent = password;
    }

    function toggleEditPasswordVisibility(memberId) {
        const input = document.getElementById('editPassword' + memberId);
        const display = document.getElementById('editPasswordDisplay' + memberId);
        if (input.type === 'password') {
            input.type = 'text';
            display.textContent = input.value;
        } else {
            input.type = 'password';
            display.textContent = '••••••••';
        }
    }

    function toggleEditResetPasswordVisibility(memberId) {
        const input = document.getElementById('editResetPassword' + memberId);
        const display = document.getElementById('editResetPasswordDisplay' + memberId);
        if (input.type === 'password') {
            input.type = 'text';
            display.textContent = input.value;
        } else {
            input.type = 'password';
            display.textContent = '••••••••';
        }
    }

    // ==========================================
    // INITIALISATION
    // ==========================================
    
    document.addEventListener('DOMContentLoaded', function() {
        initUploadArea();
        initAdhesionUploadArea();
        
        document.querySelectorAll('[id^="createUserModal"]').forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                const id = this.id.replace('createUserModal', '');
                const passwordField = document.getElementById('editPassword' + id);
                if (passwordField) {
                    document.getElementById('editPasswordDisplay' + id).textContent = passwordField.value;
                }
            });
        });

        document.querySelectorAll('[id^="resetPasswordModal"]').forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                const id = this.id.replace('resetPasswordModal', '');
                const passwordField = document.getElementById('editResetPassword' + id);
                if (passwordField) {
                    document.getElementById('editResetPasswordDisplay' + id).textContent = passwordField.value;
                }
            });
        });
    });
</script>
@endpush
@endsection