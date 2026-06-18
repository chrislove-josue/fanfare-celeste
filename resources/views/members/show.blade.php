@extends('layouts.app')

@section('title', $member->full_name)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Fiche membre</h1>
        <div>
            <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.members.card', $member) }}" class="btn btn-primary">
                <i class="fas fa-id-card"></i> Carte membre
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Photo et infos principales -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="{{ $member->photo_url }}" alt="Photo" class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                    <h3>{{ $member->full_name }}</h3>
                    <p class="text-muted">N° {{ $member->member_number }}</p>
                    
                    @if($committeePosition)
                        <span class="badge bg-primary mb-3">
                            <i class="fas fa-user-tie"></i> {{ \App\Models\CommitteeMember::getPositions()[$committeePosition->position] ?? $committeePosition->position }}
                        </span>
                    @endif
                    
                    <hr>
                    
                    <div class="text-start">
                        <p><i class="fas fa-envelope me-2"></i> {{ $member->email }}</p>
                        <p><i class="fas fa-phone me-2"></i> {{ $member->phone }}</p>
                        <p><i class="fab fa-whatsapp me-2"></i> {{ $member->whatsapp ?? '-' }}</p>
                        <p><i class="fas fa-map-marker-alt me-2"></i> {{ $member->address }}</p>
                        <p><i class="fas fa-calendar-alt me-2"></i> Né le {{ $member->birth_date->format('d/m/Y') }} ({{ $member->age }} ans)</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations détaillées -->
        <div class="col-md-8">
            <!-- Statut -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Statut</h5>
                </div>
                <div class="card-body">
                    @php
                        $statusColors = [
                            'active' => 'success',
                            'pending' => 'warning',
                            'suspended' => 'danger',
                            'withdrawn' => 'secondary',
                            'deceased' => 'dark',
                            'training' => 'info'
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$member->status] }} fs-6 p-2">
                        {{ ucfirst($member->status) }}
                    </span>
                    <span class="ms-3">
                        <i class="fas fa-calendar me-1"></i> Membre depuis le {{ $member->entry_date->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            
            <!-- Informations musicales -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-music"></i> Informations musicales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Instrument :</strong> {{ $member->instrument ?? '-' }}</p>
                            <p><strong>Niveau :</strong> 
                                @if($member->instrument_level)
                                    @switch($member->instrument_level)
                                        @case('debutant') <span class="badge bg-info">Débutant</span> @break
                                        @case('intermediaire') <span class="badge bg-primary">Intermédiaire</span> @break
                                        @case('avance') <span class="badge bg-success">Avancé</span> @break
                                        @case('expert') <span class="badge bg-warning">Expert</span> @break
                                    @endswitch
                                @else - @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Section :</strong> {{ $member->section ?? '-' }}</p>
                            <p><strong>Rôle dans la section :</strong> {{ $member->section_role ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact urgence -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-ambulance"></i> Contact d'urgence</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nom :</strong> {{ $member->emergency_contact_name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Téléphone :</strong> {{ $member->emergency_contact_phone ?? '-' }}</p>
                        </div>
                    </div>
                    @if($member->medical_notes)
                        <p><strong>Notes médicales :</strong> {{ $member->medical_notes }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Cotisations récentes -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-coins"></i> Dernières cotisations</h5>
                </div>
                <div class="card-body">
                    @if($contributions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Montant</th>
                                        <th>Reçu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contributions as $contribution)
                                    <tr>
                                        <td>{{ $contribution->payment_date->format('d/m/Y') }}</td>
                                        <td>{{ ucfirst($contribution->type) }}</td>
                                        <td>{{ number_format($contribution->amount, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ $contribution->receipt_number }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucune cotisation enregistrée</p>
                    @endif
                </div>
            </div>
            
            <!-- Événements récents -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Participation aux événements</h5>
                </div>
                <div class="card-body">
                    @if($events->count() > 0)
                        <div class="list-group">
                            @foreach($events as $participation)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $participation->event->title }}</strong><br>
                                            <small>{{ $participation->event->start_date->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <span class="badge bg-{{ $participation->attendance_status == 'present' ? 'success' : ($participation->attendance_status == 'absent' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($participation->attendance_status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucune participation enregistrée</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection