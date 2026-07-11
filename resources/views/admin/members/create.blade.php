@extends('layouts.admin')

@section('title', 'Nouveau membre')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Ajouter un membre</h1>
        <div>
            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Retour
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
            <form method="POST" action="{{ route('admin.members.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Document d'identité (optionnel)</label>
                        <input type="file" name="id_document" class="form-control" accept="image/*,.pdf">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Lettre d'adhésion (optionnel)</label>
                        <input type="file" name="adhesion_letter" class="form-control" accept=".pdf,.doc,.docx,.txt,.rtf">
                        <div class="form-text">Formats : PDF, DOC, DOCX, TXT, RTF | Taille max : 5MB</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select" disabled>
                            <option value="pending" selected>Pending</option>
                        </select>
                        <div class="form-text">Le statut initial est automatiquement réglé à Pending.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nom</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="laissez vide pour générer automatiquement">
                        <div class="form-text">Laissez vide pour générer automatiquement : prenom.nom@example.com</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Adresse</label>
                        <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Lieu de naissance</label>
                        <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date d'entrée</label>
                        <input type="date" name="entry_date" class="form-control" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Section</label>
                        <select name="section" class="form-select">
                            <option value="">—</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}" {{ old('section') === $section ? 'selected' : '' }}>{{ $section }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Instrument</label>
                        <select name="instrument" class="form-select">
                            <option value="">—</option>
                            @foreach($instruments as $instrument)
                                <option value="{{ $instrument }}" {{ old('instrument') === $instrument ? 'selected' : '' }}>{{ $instrument }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pupitre</label>
                        <select name="instrument_level" class="form-select">
                            <option value="">—</option>
                            @foreach($instrumentLevels as $val => $label)
                                <option value="{{ $val }}" {{ old('instrument_level') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Rôle section (optionnel)</label>
                        <input type="text" name="section_role" class="form-control" value="{{ old('section_role') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact d'urgence (nom)</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact d'urgence (téléphone)</label>
                        <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Notes médicales</label>
                        <textarea name="medical_notes" class="form-control" rows="3">{{ old('medical_notes') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Compétences / notes</label>
                        <textarea name="skills" class="form-control" rows="3">{{ old('skills') }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Notes (Admin)</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
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