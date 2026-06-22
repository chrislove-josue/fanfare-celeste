@extends('layouts.app')

@section('title', 'Merci - Fanfare Céleste')

@section('content')
<section class="py-8">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm" style="border-radius: 15px; border: 1px solid rgba(0,0,0,.05);">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="fas fa-check-circle" style="font-size: 3rem; color: #40A81B;"></i>
                            </div>

                            <h1 class="h3 mb-2">Merci pour votre adhésion !</h1>
                            <p class="text-muted mb-4">
                                Votre demande a bien été enregistrée. Nous vous recontactons dans les plus brefs délais.
                            </p>

                            <a href="{{ route('home') }}" class="btn btn-primary px-4" style="border-radius: 12px;">
                                <i class="fas fa-home me-2"></i>Retour à l’accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

