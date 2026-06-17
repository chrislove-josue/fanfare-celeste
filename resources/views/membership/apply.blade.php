@extends('layouts.app')

@section('title', "Adhésion - Fanfare Céleste")

@section('content')
<section class="py-8">
    <div class="container max-w-2xl">
        <h1 class="text-3xl font-bold mb-4">Adhérer à la Fanfare Céleste</h1>

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

        <form method="POST" action="{{ route('membership.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1" for="name">Nom complet</label>
                <input id="name" name="name" type="text" class="w-full border rounded p-2" value="{{ old('name') }}" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="email">Email</label>
                <input id="email" name="email" type="email" class="w-full border rounded p-2" value="{{ old('email') }}" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="phone">Téléphone (optionnel)</label>
                <input id="phone" name="phone" type="text" class="w-full border rounded p-2" value="{{ old('phone') }}">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="message">Message</label>
                <textarea id="message" name="message" class="w-full border rounded p-2" rows="5" required>{{ old('message') }}</textarea>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Envoyer</button>
            </div>
        </form>
    </div>
</section>
@endsection

