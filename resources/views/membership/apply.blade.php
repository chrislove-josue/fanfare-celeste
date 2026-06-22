@extends('layouts.app')

@section('title', "Adhésion - Fanfare Céleste")

@section('content')
<section class="py-8">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5">
                <div class="card shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, rgba(26,35,126,.05), rgba(40,53,147,.02)); border: 1px solid rgba(26,35,126,.15);">
                    <div class="card-body p-4">
                        <h1 class="h3 mb-2">Adhérer à la Fanfare Céleste</h1>
                        <p class="text-muted mb-4">Remplissez le formulaire et nous vous recontactons rapidement.</p>

                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-whatsapp text-success" style="font-size: 1.6rem;"></i>
                                <div class="ms-3">
                                    <div class="fw-semibold">WhatsApp</div>
                                    <div class="text-muted">+229 01 98 12 33 53</div>
                                    <a class="btn btn-success btn-sm mt-2" href="https://wa.me/2290198123353" target="_blank" rel="noopener">
                                        <i class="fab fa-whatsapp me-2"></i>Nous écrire
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mb-0" style="border-radius: 12px;">
                            <i class="fas fa-info-circle me-2"></i>
                            Votre demande sera prise en compte.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm" style="border-radius: 15px; border: 1px solid rgba(0,0,0,.05);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="h5 mb-0">Formulaire d’adhésion</h2>
                            <span class="badge rounded-pill" style="background: linear-gradient(135deg, #1a237e, #283593); color: #fff;">Public</span>
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

                        <form method="POST" action="{{ route('membership.submit') }}" class="mt-2">
                            @csrf

                            <div class="row g-3">
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
                                    <label class="form-label" for="email">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(26,35,126,.06); border-color: rgba(26,35,126,.15);">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="phone">Téléphone (optionnel)</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: rgba(25,135,84,.08); border-color: rgba(25,135,84,.2);">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone') }}" placeholder="Ex: +229 01 98 12 33 53">
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="p-3 rounded w-100" style="background: linear-gradient(135deg, rgba(25,135,84,.10), rgba(25,135,84,.04)); border: 1px solid rgba(25,135,84,.2);">
                                        <div class="d-flex align-items-center">
                                            <i class="fab fa-whatsapp text-success" style="font-size: 1.2rem;"></i>
                                            <div class="ms-2">
                                                <div class="fw-semibold">WhatsApp de la Fanfare</div>
                                                <div class="text-muted">+229 01 98 12 33 53</div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <a class="btn btn-success btn-sm w-100" href="https://wa.me/22998123353" target="_blank" rel="noopener">
                                                <i class="fab fa-whatsapp me-2"></i>Contact rapide
                                            </a>
                                        </div>
                                    </div>
                                </div>

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

