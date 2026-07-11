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
                            <span class="badge bg-primary">Fanfare Céleste</span>
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

                        {{-- ALERTE CHAMPS OBLIGATOIRES --}}
                        <div class="p-3 mb-4 text-blue-800 border border-blue-300 rounded bg-blue-50" style="border-left: 4px solid #2563eb;">
                            <i class="fas fa-info-circle me-2"></i>
                            <span class="fw-semibold">Les champs marqués d'un <span class="text-danger"> * </span> sont obligatoires.</span>
                        </div>

                        {{-- CONDITIONS D'ADHÉSION --}}
                        <div class="p-4 mb-4 border rounded bg-light" style="border-left: 4px solid #3946B2;">
                            <h5 class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                Conditions d'adhésion
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start mb-2">
                                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                        <span>Être membre de l'<strong>Église du Christianisme Céleste</strong></span>
                                    </div>
                                    <div class="d-flex align-items-start mb-2">
                                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                        <span>Être <strong>choriste</strong> et avoir des compétences musicales</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start mb-2">
                                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                        <span> <strong>Être dispoible</strong>pour passer un <strong>test pratique</strong> après soumission</span>
                                    </div>
                                    <div class="d-flex align-items-start mb-2">
                                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                        <span>Validation définitive après le <strong>test musical</strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Une fois votre formulaire soumis, vous serez contacté pour un test pratique. La validation finale dépendra des résultats de ce test.
                            </div>
                        </div>

                        <form method="POST" action="{{ route('membership.submit') }}" class="mt-2" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                {{-- 1) Identité --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="last_name">Nom <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(26,35,126,.06); border-color: rgba(26,35,126,.15);">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input id="last_name" name="last_name" type="text" class="form-control" value="{{ old('last_name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="first_name">Prénom <span class="text-danger"> * </span></label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(26,35,126,.06); border-color: rgba(26,35,126,.15);">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input id="first_name" name="first_name" type="text" class="form-control" value="{{ old('first_name') }}" required>
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
                                    <label class="form-label" for="phone">Téléphone <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(25,135,84,.08); border-color: rgba(25,135,84,.2);">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone') }}" placeholder="Ex: +229 01 98 12 33 53" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="whatsapp">WhatsApp</label>
                                    <input id="whatsapp" name="whatsapp" type="text" class="form-control" value="{{ old('whatsapp') }}" placeholder="Ex: +229 01 98 12 33 53">
                                </div>

                                {{-- Paroisse et Sous-Région --}}
                                <div class="col-md-12">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label">Paroisse <span class="text-danger">*</span></label>
                                            <input type="text" name="paroisse" class="form-control" value="{{ old('paroisse') }}" placeholder="Paroisse" required>
                                            <div class="form-text">
                                                <span class="text-muted">Exemple : <strong>St Michel Sohoun Centre</strong></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Sous-Région <span class="text-danger">*</span></label>
                                            <input type="text" name="sous_region" class="form-control" value="{{ old('sous_region') }}" placeholder="Sous-région" required>
                                            <div class="form-text">
                                                <span class="text-muted">Exemple : <strong>Allada C/Sékou</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 4) Naissance --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="birth_date">Date de naissance  <span class="text-danger">*</span></label>
                                    <input id="birth_date" name="birth_date" type="date" required class="form-control" value="{{ old('birth_date') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="birth_place">Lieu de naissance</label>
                                    <input id="birth_place" name="birth_place" type="text" class="form-control" value="{{ old('birth_place') }}" placeholder="Ville / Pays">
                                </div>

                                {{-- 5) Instrument / Section / Pupitre --}}
                                <div class="col-md-4">
                                    <label class="form-label" for="section">Section <span class="text-danger">*</span></label>
                                    <select id="section" name="section" class="form-select" required>
                                        <option value="">-- Choisir --</option>
                                        @foreach($sections as $key => $label)
                                            <option value="{{ $key }}" @selected(old('section') === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="instrument">Instrument <span class="text-danger">*</span></label>
                                    <select id="instrument" name="instrument" class="form-select" required>
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

                                {{-- 6) Compétences musicales --}}
                                <div class="col-md-12">
                                    <label class="form-label" for="skills">Compétences musicales <span class="text-danger">*</span></label>
                                    <textarea id="skills" name="skills" class="form-control" rows="3" style="resize: vertical;" placeholder="Décrivez vos compétences musicales (chant, instrument, solfège, expérience...)" required>{{ old('skills') }}</textarea>
                                    <div class="form-text">Détaillez votre expérience musicale, vos atouts, formations suivies...</div>
                                </div>

                                {{-- 7) Contacts d'urgence --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="emergency_contact_name">Contact d'urgence (nom)</label>
                                    <input id="emergency_contact_name" name="emergency_contact_name" type="text" class="form-control" value="{{ old('emergency_contact_name') }}" placeholder="Nom et prénom">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="emergency_contact_phone">Contact d'urgence (téléphone)</label>
                                    <input id="emergency_contact_phone" name="emergency_contact_phone" type="text" class="form-control" value="{{ old('emergency_contact_phone') }}" placeholder="Ex: +229 ...">
                                </div>

                                {{-- 8) Santé --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="medical_notes">Notes médicales (optionnel)</label>
                                    <textarea id="medical_notes" name="medical_notes" class="form-control" rows="3" style="resize: vertical;">{{ old('medical_notes') }}</textarea>
                                    <div class="form-text">Informations médicales importantes (allergies, problèmes de santé...)</div>
                                </div>

                                {{-- 9) Pièces jointes --}}
                                <div class="col-md-4">
                                    <label class="form-label" for="photo">Photo</label>
                                    <input id="photo" name="photo" type="file" class="form-control" accept="image/*">
                                    <div class="form-text">Format : JPG, PNG, WebP | 2MB max</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="id_document">Pièce d'identité</label>
                                    <input id="id_document" name="id_document" type="file" class="form-control" accept="image/*,.pdf">
                                    <div class="form-text">Format : JPG, PNG, PDF | 5MB max</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="adhesion_letter">
                                        <i class="fas fa-file-signature text-success me-1"></i>
                                        Lettre d'adhésion
                                    </label>
                                    <input id="adhesion_letter" name="adhesion_letter" type="file" class="form-control" accept=".pdf,.doc,.docx,.txt,.rtf">
                                    <div class="form-text">PDF, DOC, DOCX, TXT, RTF | 5MB max</div>
                                </div>

                                {{-- 10) Engagement et confirmation --}}
                                <div class="col-12">
                                    <div class="p-3 border rounded bg-light">
                                        {{-- Conditions d'adhésion --}}
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="engagement" name="engagement" value="1" required>
                                            <label class="form-check-label" for="engagement">
                                                <strong>J'accepte les conditions d'adhésion <span class="text-danger">*</span></strong>
                                            </label>
                                        </div>
                                        <div class="ms-4 mt-2 text-muted small mb-3">
                                            <i class="fas fa-info-circle me-1"></i>
                                            En cochant cette case, je certifie être membre de l'Église du Christianisme Céleste, être choriste ou avoir des compétences musicales, et j'accepte de passer un test pratique après soumission.
                                        </div>

                                        {{-- Confirmation de l'exactitude des informations --}}
                                        <div class="form-check mt-3 pt-3 border-top">
                                            <input class="form-check-input" type="checkbox" id="confirmation" name="confirmation" value="1" required>
                                            <label class="form-check-label" for="confirmation">
                                                <strong>Je confirme l'exactitude des informations renseignées <span class="text-danger">*</span></strong>
                                            </label>
                                        </div>
                                        <div class="ms-4 mt-2 text-muted small">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Je certifie sur l'honneur que toutes les informations fournies dans ce formulaire sont exactes et conformes à la réalité.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex align-items-center justify-content-between">
                                <div class="text-muted small">
                                    <i class="fas fa-lock me-2"></i>
                                    Vos informations restent confidentielles.
                                </div>
                                <button type="submit" class="px-4 btn btn-primary" style="border-radius: 12px;">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer ma demande
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