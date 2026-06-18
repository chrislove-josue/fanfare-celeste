@extends('layouts.app')

@section('title', 'Bureau exécutif - Fanfare Céleste')

@section('content')
<section class="py-8">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="text-2xl font-bold">Bureau exécutif</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(isset($committeeMembers) && $committeeMembers->count())
            <div class="row g-3">
                @foreach($committeeMembers as $cm)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title mb-1">
                                    {{ $cm->member->full_name ?? $cm->member->name ?? 'Membre' }}
                                </h5>
                                <div class="text-muted mb-3">
                                    {{ $positions[$cm->position] ?? ($cm->position ?? 'Rôle') }}
                                </div>

                                <div class="small text-muted mb-3">
                                    @if(!empty($cm->start_date))
                                        <div><b>Début :</b> {{ \Illuminate\Support\Carbon::parse($cm->start_date)->format('d/m/Y') }}</div>
                                    @endif
                                    @if(!empty($cm->end_date) && $cm->end_date !== '0000-00-00')
                                        <div><b>Fin :</b> {{ \Illuminate\Support\Carbon::parse($cm->end_date)->format('d/m/Y') }}</div>
                                    @endif
                                </div>

                                <div class="mt-2 d-flex gap-2 flex-wrap">
                                    <a href="{{ route('admin.committee.show', $cm->id) }}" class="btn btn-outline-primary btn-sm">Détails</a>
                                    <a href="{{ route('admin.committee.edit', $cm->id) }}" class="btn btn-outline-secondary btn-sm">Modifier</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $committeeMembers->links() }}
            </div>
        @else
            <p class="text-muted mb-0">Aucun membre du bureau exécutif trouvé.</p>
        @endif

        @if(isset($currentCommittee) && $currentCommittee && $currentCommittee->count())
            <hr class="my-5">
            <h2 class="h5 mb-3">Bureau actuel</h2>
            <div class="row g-3">
                @foreach($currentCommittee as $cm)
                    <div class="col-12 col-md-6">
                        <div class="border rounded p-3">
                            <div class="fw-semibold">
                                {{ $cm->member->full_name ?? $cm->member->name ?? 'Membre' }}
                            </div>
                            <div class="text-muted">{{ $positions[$cm->position] ?? ($cm->position ?? 'Rôle') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
@endsection

