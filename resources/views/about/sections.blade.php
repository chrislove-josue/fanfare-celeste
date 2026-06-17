@extends('layouts.app')

@section('title', 'Sections - Fanfare Céleste')

@section('content')
<section class="py-8">
    <div class="container">
        <h1 class="text-3xl font-bold mb-4">Sections musicales</h1>

        @if(isset($sections) && is_array($sections) && count($sections))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($sections as $key => $s)
                    <div class="border rounded p-4">
                        <h2 class="text-xl font-semibold">{{ $s['name'] ?? $key }}</h2>
                        <p class="text-gray-700 mt-2">{{ $s['description'] ?? '' }}</p>
                        <p class="text-sm text-gray-600 mt-2">Chef : {{ $s['leader'] ?? '-' }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-700">Aucune section définie.</p>
        @endif
    </div>
</section>
@endsection

