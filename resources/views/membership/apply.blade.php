@extends('layouts.app')

@section('title', "Adhésion - Fanfare Céleste")

@section('content')
<section class="py-8">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="">
                <div class="shadow-sm card" style="border-radius: 15px; border: 1px solid rgba(0,0,0,.05);">
                    <div class="p-4 card-body">
                        <div class="mb-4 d-flex align-items-center justify-content-between">
                            <h2 class="mb-0 h3">Formulaire d'adhésion</h2>
                        </div>

                        @if(session('success'))
                            <div class="p-3 mb-4 text-green-800 border border-green-300 rounded bg-green-50">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="p-3 mb-4 text-red-800 border border-red-300 rounded bg-red-50">
                                <ul class="pl-5 list-disc">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('membership.submit') }}" class="mt-2" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                {{-- 1) Identité --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Nom complet *</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(26,35,126,.06); border-color: rgba(26,35,126,.15);">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="email">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(26,35,126,.06); border-color: rgba(26,35,126,.15);">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="email@exemple.com">
                                    </div>
                                    <div class="form-text">Laissez vide pour générer automatiquement : prenom.nom@example.com</div>
                                </div>

                                {{-- 2) Contact --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="phone">Téléphone</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(25,135,84,.08); border-color: rgba(25,135,84,.2);">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone') }}" placeholder="Ex: +229 01 98 12 33 53">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="whatsapp">WhatsApp</label>
                                    <input id="whatsapp" name="whatsapp" type="text" class="form-control" value="{{ old('whatsapp') }}" placeholder="Ex: +229 01 98 12 33 53">
                                </div>

                                {{-- 3) Adresse & naissance --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="address">Adresse</label>
                                    <input id="address" name="address" type="text" class="form-control" value="{{ old('address') }}" placeholder="Quartier / Ville">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label" for="birth_date">Date de naissance</label>
                                    <input id="birth_date" name="birth_date" type="date" class="form-control" value="{{ old('birth_date') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label" for="birth_place">Lieu de naissance</label>
                                    <input id="birth_place" name="birth_place" type="text" class="form-control" value="{{ old('birth_place') }}" placeholder="Ville / Pays">
                                </div>

                                {{-- 4) Instrument / Section / Pupitre --}}
                                <div class="col-md-4">
                                    <label class="form-label" for="section">Section</label>
                                    <select id="section" name="section" class="form-select">
                                        <option value="">-- Choisir --</option>
                                        @foreach($sections as $key => $label)
                                            <option value="{{ $key }}" @selected(old('section') === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="instrument">Instrument</label>
                                    <select id="instrument" name="instrument" class="form-select">
                                        <option value="">-- Choisir --</option>
                                        @foreach($instruments as $key => $label)
                                            <option value="{{ $key }}" @selected(old('instrument') === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="instrument_level">Pupitre</label>
                                    <select id="instrument_level" name="instrument_level" class="form-select">
                                        <option value="">-- Choisir --</option>
                                        @foreach($pupitres as $key => $label)
                                            <option value="{{ $key }}" @selected(old('instrument_level') === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 5) Contacts d'urgence --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="emergency_contact_name">Contact d'urgence (nom)</label>
                                    <input id="emergency_contact_name" name="emergency_contact_name" type="text" class="form-control" value="{{ old('emergency_contact_name') }}" placeholder="Nom et prénom">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="emergency_contact_phone">Contact d'urgence (téléphone)</label>
                                    <input id="emergency_contact_phone" name="emergency_contact_phone" type="text" class="form-control" value="{{ old('emergency_contact_phone') }}" placeholder="Ex: +229 ...">
                                </div>

                                {{-- 6) Santé & compétences --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="medical_notes">Notes médicales</label>
                                    <textarea id="medical_notes" name="medical_notes" class="form-control" rows="3" style="resize: vertical;">{{ old('medical_notes') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="skills">Compétences / notes</label>
                                    <textarea id="skills" name="skills" class="form-control" rows="3" style="resize: vertical;">{{ old('skills') }}</textarea>
                                </div>

                                {{-- 7) Pièces jointes --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="photo">Photo</label>
                                    <input id="photo" name="photo" type="file" class="form-control" accept="image/*">
                                    <div class="form-text">Format : JPG, PNG, WebP | Taille max : 2MB</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="id_document">Pièce d'identité</label>
                                    <input id="id_document" name="id_document" type="file" class="form-control" accept="image/*,.pdf">
                                    <div class="form-text">Format : JPG, PNG, PDF | Taille max : 5MB</div>
                                </div>

                            </div>

                            <div class="mt-4 d-flex align-items-center justify-content-between">
                                <div class="text-muted small">
                                    <i class="fas fa-lock me-2"></i>
                                    Vos informations restent confidentielles.
                                </div>
                                <button type="submit" class="px-4 btn btn-primary" style="border-radius: 12px;">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection