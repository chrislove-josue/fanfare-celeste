@extends('layouts.admin')

@section('title', 'Document - ' . $member->full_name)
@section('page-title', 'Document d\'identité')
@section('page-subtitle', $member->full_name . ' - ' . $member->member_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Membres</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.members.show', $member) }}">{{ $member->full_name }}</a></li>
    <li class="breadcrumb-item active">Document</li>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-id-card text-primary me-2"></i>
                Document d'identité
            </h5>
            <div>
                <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
                @if(isset($imageSrc))
                <a href="{{ $imageSrc }}" download="document_{{ $member->member_number }}.jpg" class="btn btn-primary btn-sm">
                    <i class="fas fa-download me-1"></i> Télécharger
                </a>
                @endif
            </div>
        </div>
        <div class="card-body text-center py-4">
            @if(isset($imageSrc))
                <img src="{{ $imageSrc }}" 
                     alt="Document de {{ $member->full_name }}" 
                     class="img-fluid rounded shadow-sm"
                     style="max-height: 600px; max-width: 100%; object-fit: contain;">
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                    <p class="text-muted">Le document est au format PDF. Cliquez sur "Télécharger" pour le visualiser.</p>
                    <a href="{{ asset('storage/' . $member->id_document) }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-eye me-1"></i> Ouvrir le PDF
                    </a>
                </div>
            @endif
        </div>
        <div class="card-footer bg-light py-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <small class="text-muted d-block">Membre</small>
                    <span class="fw-semibold">{{ $member->full_name }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Numéro de membre</small>
                    <span class="fw-semibold">{{ $member->member_number }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Email</small>
                    <span class="fw-semibold">{{ $member->email ?? '-' }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Document</small>
                    <span class="fw-semibold text-truncate" style="max-width: 150px; display: inline-block;">
                        {{ basename($member->id_document) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection