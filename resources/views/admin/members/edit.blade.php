@extends('layouts.app')

@section('title', 'Modifier membre')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier le membre</h1>
        <div>
            <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="{{ route('admin.members.card', $member) }}" class="btn btn-primary">
                <i class="fas fa-id-card"></i> Carte
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.members.update', $member) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control">
                        @if($member->photo_url)
                            <img src="{{ $member->photo_url }}" class="rounded-circle mt-3" width="100" height="100" style="object-fit:cover;" alt="Photo membre">
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">N° Membre</label>
                        <input type="text" class="form-control" value="{{ $member->member_number }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $member->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $member->first_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nom</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $member->last_name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $member->whatsapp) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $member->email }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Section</label>
                        <select name="section" class="form-select">
                            <option value="">—</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}" {{ $member->section === $section ? 'selected' : '' }}>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Instrument</label>
                        <select name="instrument" class="form-select">
                            <option value="">—</option>
                            @foreach($instruments as $instrument)
                                <option value="{{ $instrument }}" {{ $member->instrument === $instrument ? 'selected' : '' }}>
                                    {{ $instrument }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Niveau instrument</label>
                        <select name="instrument_level" class="form-select">
                            <option value="">—</option>
                            @foreach($instrumentLevels as $level)
                                <option value="{{ $level }}" {{ $member->instrument_level === $level ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_',' ',$level)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Date d’entrée</label>
                        <input type="date" name="entry_date" class="form-control" value="{{ old('entry_date', $member->entry_date?->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Adresse</label>
                        <textarea name="address" class="form-control" rows="3" required>{{ old('address', $member->address) }}</textarea>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $member->birth_date?->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Lieu de naissance</label>
                        <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place', $member->birth_place) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact d’urgence (Nom)</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact d’urgence (Téléphone)</label>
                        <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Notes médicales</label>
                        <textarea name="medical_notes" class="form-control" rows="3">{{ old('medical_notes', $member->medical_notes) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Compétences</label>
                        <textarea name="skills" class="form-control" rows="3">{{ old('skills', $member->skills) }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Notes (Admin)</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $member->notes) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

