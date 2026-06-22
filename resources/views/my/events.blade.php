@extends('layouts.adminApp')
@section('page-title')
    <h2 class="m-0 fs-5 fw-bold">Mes participations</h2>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-3 p-3 p-md-4 space-y-6">
                <div class="mb-4">
                    <div class="text-gray-600">Membre: <b>{{ $member->full_name }}</b> ({{ $member->member_number }})</div>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead class="text-left">
                            <tr class="border-b">
                                <th class="py-2 pr-4">Événement</th>
                                <th class="py-2 pr-4">Type</th>
                                <th class="py-2 pr-4">Début</th>
                                <th class="py-2 pr-4">Fin</th>
                                <th class="py-2 pr-4">Lieu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $p)
                                <tr class="border-b">
                                    <td class="py-2 pr-4">{{ $p->event->title ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $p->event->type ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ optional($p->event->start_date)->format('Y-m-d') ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ optional($p->event->end_date)->format('Y-m-d') ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $p->event->location ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-gray-500">Aucune participation.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $events->links() }}</div>

                <div class="mt-4">
                    <a class="btn btn-secondary" href="{{ route('my.profile') }}">Retour profil</a>
                </div>
            </div>
        </div>
    </div>
@endsection


