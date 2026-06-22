@extends('layouts.adminApp')

@section('content')
<div class="bg-white shadow-sm rounded-3 p-3 p-md-4 space-y-6">

    <div class="flex items-start gap-4">



                    @if($member->photo_url)
                        <img src="{{ $member->photo_url }}" alt="Photo" class="w-20 h-20 rounded-full object-cover border" />
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-100 border flex items-center justify-center text-gray-500">
                            {{ $member->initials }}
                        </div>
                    @endif

                    <div>
                        <div class="text-2xl font-bold">{{ $member->full_name }}</div>
                        <div class="text-gray-600">{{ $member->member_number }}</div>
                        <div class="mt-2 text-gray-700">
                            <div><b>Section:</b> {{ $member->section ?? '-' }}</div>
                            <div><b>Instrument:</b> {{ $member->instrument ?? '-' }}</div>
                            <div><b>Niveau:</b> {{ $member->instrument_level ?? '-' }}</div>
                            <div><b>Statut:</b> {{ $member->status ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <div class="font-semibold">Coordonnées</div>
                        <div class="text-gray-700"><b>Email:</b> {{ $member->email }}</div>
                        <div class="text-gray-700"><b>Téléphone:</b> {{ $member->phone ?? '-' }}</div>
                        <div class="text-gray-700"><b>WhatsApp:</b> {{ $member->whatsapp ?? '-' }}</div>
                        <div class="text-gray-700"><b>Adresse:</b> {{ $member->address ?? '-' }}</div>
                    </div>

                    <div class="space-y-2">
                        <div class="font-semibold">Informations</div>
                        <div class="text-gray-700"><b>Date de naissance:</b> {{ optional($member->birth_date)->format('Y-m-d') ?? '-' }}</div>
                        <div class="text-gray-700"><b>Date d’entrée:</b> {{ optional($member->entry_date)->format('Y-m-d') ?? '-' }}</div>
                        <div class="text-gray-700"><b>Contact urgence:</b> {{ $member->emergency_contact_name ?? '-' }}</div>
                        <div class="text-gray-700"><b>Tél urgence:</b> {{ $member->emergency_contact_phone ?? '-' }}</div>
                    </div>
                </div>

                <div>
                    <div class="font-semibold mb-2">Bureau (si applicable)</div>
                    @if($currentCommitteePosition)
                        <div class="text-gray-700">
                            <b>Poste:</b> {{ $currentCommitteePosition->position ?? '-' }}
                        </div>
                        <div class="text-gray-700">
                            <b>Mandat:</b> {{ $currentCommitteePosition->start_date ? $currentCommitteePosition->start_date->format('Y-m-d') : '-' }}
                            - {{ $currentCommitteePosition->end_date ? $currentCommitteePosition->end_date->format('Y-m-d') : 'en cours' }}
                        </div>
                    @else
                        <div class="text-gray-500">Aucune position actuelle au bureau.</div>
                    @endif
                </div>

                <div>
                    <a class="btn btn-primary" href="{{ route('my.profile') }}" style="display:none"></a>
            <div class="flex flex-wrap gap-3">
                <a class="btn btn-primary text-white" href="{{ route('my.contributions') }}">Mes cotisations</a>
                <a class="btn btn-primary text-white" href="{{ route('my.transactions') }}">Mes transactions</a>
                <a class="btn btn-primary text-white" href="{{ route('my.events') }}">Mes participations</a>
            </div>
        </div>
    </div>
</div>
@endsection



