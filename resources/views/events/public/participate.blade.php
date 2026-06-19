@extends('layouts.app')

@section('title', 'Inscription - ' . ($event->title ?? 'Événement'))

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a href="{{ route('public.events.show', $event) }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <div>
                <span class="badge bg-primary"><i class="fas fa-user-plus me-1"></i> Inscription</span>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-transparent">
                        <h4 class="mb-0">{{ $event->title }}</h4>
                        <div class="text-muted mt-1">
                            {{ optional($event->start_date)->format('d/m/Y H:i') }}
                            @if($event->location)
                                • {{ $event->location }}
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="alert alert-info mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <img
                                                src="{{ $avatarUrl }}"
                                                alt="Avatar"
                                                class="rounded-circle"
                                                style="width:56px;height:56px;object-fit:cover;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Votre identité</div>
                                            <div class="text-muted">Nom, e-mail et quartier alimentent votre demande.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('public.events.participate', $event) }}">
                                @csrf

                                <div class="col-md-6">
                                    <label class="form-label">Nom</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $profile->last_name ?? '') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $profile->first_name ?? '') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $profile->email ?? '') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone ?? '') }}" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Quartier</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $profile->address ?? '') }}" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Message (optionnel)</label>
                                    <textarea name="message" class="form-control" rows="3" placeholder="Ex: Je souhaite participer...">{{ old('message') }}</textarea>
                                </div>

                                <div class="col-12 d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-1"></i> Envoyer ma demande
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="text-muted mt-3" style="font-size: 0.95rem;">
                    En envoyant, vous inscrivez votre participation à cet événement.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

