@extends('layouts.app')

@section('title', 'Bureau exécutif - Fanfare Céleste')

@section('content')
<section class="py-8">
    <div class="container">
        <h1 class="text-3xl font-bold mb-4">Bureau exécutif</h1>

        @if(isset($committeeMembers) && $committeeMembers->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($committeeMembers as $cm)
                    <div class="border rounded p-4">
                        <div class="font-semibold">{{ $cm->member->full_name ?? $cm->member->name ?? 'Membre' }}</div>
                        <div class="text-sm text-gray-600">
                            {{ $positions[$cm->position] ?? 'Rôle' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-700">Aucun membre du bureau exécutif trouvé.</p>
        @endif
    </div>
</section>
@endsection

