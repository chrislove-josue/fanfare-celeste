@extends('layouts.app')

@section('title', "Adhésion - Fanfare Céleste")

@section('content')
<section class="py-8">
    <div class="container">
        <div class="row g-4 align-items-stretch">
           

            <div class="">
                <div class="card shadow-sm" style="border-radius: 15px; border: 1px solid rgba(0,0,0,.05);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="h3 mb-0">Formulaire d’adhésion</h2>
                        </div>

                        @if(session('success'))
                            <div class="mb-4 rounded border border-green-300 bg-green-50 p-3 text-green-800">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-800">
                                <ul class="list-disc pl-5">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

<form method="POST" action="{{ route('membership.submit') }}" class="mt-2" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                {{-- 1) Identité (obligatoire côté contrôleur: name/email) --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Nom complet</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(26,35,126,.06); border-color: rgba(26,35,126,.15);">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="email">Email (optionnel si pas d’email, laissez vide)</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(26,35,126,.06); border-color: rgba(26,35,126,.15);">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="email@exemple.com">
                                    </div>
                                    <div class="form-text">Rempli si vous souhaitez recevoir la confirmation par email.</div>
                                </div>

                                {{-- 2) Contact --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="phone">Téléphone (optionnel)</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(25,135,84,.08); border-color: rgba(25,135,84,.2);">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone') }}" placeholder="Ex: +229 01 98 12 33 53">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="whatsapp">WhatsApp (optionnel)</label>
                                    <input id="whatsapp" name="whatsapp" type="text" class="form-control" value="{{ old('whatsapp') }}" placeholder="Ex: +229 01 98 12 33 53">
                                </div>

                                {{-- 3) Adresse & naissance --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="address">Adresse (optionnel)</label>
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

                                {{-- 4) Instrument / section --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="instrument">Instrument (optionnel)</label>
                                    <select id="instrument" name="instrument" class="form-select">
                                        <option value="">-- Choisir --</option>
                                        @foreach($instruments as $ins)
                                            <option value="{{ $ins }}" @selected(old('instrument') === $ins)>{{ $ins }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label" for="instrument_level">Niveau (optionnel)</label>
                                    <select id="instrument_level" name="instrument_level" class="form-select">
                                        <option value="">-- Choisir --</option>
                                        <option value="debutant" @selected(old('instrument_level') === 'debutant')>Débutant</option>
                                        <option value="intermediaire" @selected(old('instrument_level') === 'intermediaire')>Intermédiaire</option>
                                        <option value="avance" @selected(old('instrument_level') === 'avance')>Avancé</option>
                                        <option value="expert" @selected(old('instrument_level') === 'expert')>Expert</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label" for="section">Section</label>
                                    <select id="section" name="section" class="form-select">
                                        <option value="">-- Choisir --</option>
                                        @foreach($sections as $sec)
                                            <option value="{{ $sec }}" @selected(old('section') === $sec)>{{ $sec }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 5) Contacts d’urgence --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="emergency_contact_name">Contact d’urgence (nom)</label>
                                    <input id="emergency_contact_name" name="emergency_contact_name" type="text" class="form-control" value="{{ old('emergency_contact_name') }}" placeholder="Nom et prénom">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="emergency_contact_phone">Contact d’urgence (téléphone)</label>
                                    <input id="emergency_contact_phone" name="emergency_contact_phone" type="text" class="form-control" value="{{ old('emergency_contact_phone') }}" placeholder="Ex: +229 ...">
                                </div>

                                {{-- 6) Santé & compétences --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="medical_notes">Notes médicales (optionnel)</label>
                                    <textarea id="medical_notes" name="medical_notes" class="form-control" rows="3" style="resize: vertical;">{{ old('medical_notes') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="skills">Compétences / notes (optionnel)</label>
                                    <textarea id="skills" name="skills" class="form-control" rows="3" style="resize: vertical;">{{ old('skills') }}</textarea>
                                </div>

                                {{-- 7) Pièces jointes --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="photo">Photo (optionnel)</label>
                                    <input id="photo" name="photo" type="file" class="form-control" accept="image/*">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="id_document">Pièce d’identité (optionnel)</label>
                                    <input id="id_document" name="id_document" type="file" class="form-control" accept="image/*,.pdf">
                                </div>

                                {{-- 8) Message --}}
                                <div class="col-12">
                                    <label class="form-label" for="message">Message</label>
                                    <textarea id="message" name="message" class="form-control" rows="5" required style="resize: vertical;">{{ old('message') }}</textarea>
                                    <div class="form-text">Expliquez brièvement pourquoi vous souhaitez adhérer.</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-4">
                                <div class="text-muted small">
                                    <i class="fas fa-lock me-2"></i>
                                    Vos informations restent confidentielles.
                                </div>
                                <button type="submit" class="btn btn-primary px-4" style="border-radius: 12px;">
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

