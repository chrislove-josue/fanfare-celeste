@extends('layouts.adminApp')
@section('page-title')
    <h2 class="m-0 fs-5 fw-bold">Mes transactions</h2>
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
                                <th class="py-2 pr-4">Référence</th>
                                <th class="py-2 pr-4">Type</th>
                                <th class="py-2 pr-4">Catégorie</th>
                                <th class="py-2 pr-4">Montant</th>
                                <th class="py-2 pr-4">Statut</th>
                                <th class="py-2 pr-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $t)
                                <tr class="border-b">
                                    <td class="py-2 pr-4">{{ $t->reference ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $t->type }}</td>
                                    <td class="py-2 pr-4">{{ $t->category ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ number_format((float) $t->amount, 0, ',', ' ') }}</td>
                                    <td class="py-2 pr-4">{{ $t->status ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ optional($t->transaction_date)->format('Y-m-d') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-gray-500">Aucune transaction.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $transactions->links() }}</div>

                <div class="mt-4">
                    <a class="btn btn-secondary" href="{{ route('my.profile') }}">Retour profil</a>
                </div>
            </div>
        </div>
    </div>
@endsection


