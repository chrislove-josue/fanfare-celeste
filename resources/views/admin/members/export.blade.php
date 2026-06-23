<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des membres - Fanfare Céleste</title>
    <style>
        @import url()
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #3946B2;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #3946B2;
            font-size: 24px;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0 0;
            font-size: 14px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th {
            background: #3946B2;
            color: #fff;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 11px;
        }
        .table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .table tr:hover {
            background: #f0f2f8;
        }
        .badge-active {
            color: #28a745;
            font-weight: bold;
        }
        .badge-pending {
            color: #ffc107;
            font-weight: bold;
        }
        .badge-inactive {
            color: #dc3545;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #999;
        }
        .section-title {
            background: #f8f9fa;
            padding: 5px 10px;
            font-weight: bold;
            color: #3946B2;
            margin-top: 10px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(57, 70, 178, 0.05);
            font-weight: bold;
            pointer-events: none;
            z-index: 999;
        }
    </style>
</head>
<body>
    <div class="watermark">FANFARE CÉLESTE la trompette de dieu de l'atlantique</div>

    <div class="header">
        <h1>🎵 Fanfare Céleste</h1>
        <p>La Trompette de Dieu de l'Atlantique</p>
        <p style="font-size: 12px; color: #666; margin-top: 5px;">
            Liste des membres - Généré le {{ date('d/m/Y à H:i') }}
        </p>
    </div>

    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
        <div>
            <strong>Total membres :</strong> {{ $members->count() }}
        </div>

    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>N° Membre</th>
                <th>Nom complet</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Section</th>
                <th>Instrument</th>
                <th>Statut</th>
                <th>Date d'entrée</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $index => $member)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $member->member_number ?? '-' }}</td>
                <td><strong>{{ $member->first_name }} {{ $member->last_name }}</strong></td>
                <td>{{ $member->email ?? '-' }}</td>
                <td>{{ $member->phone ?? '-' }}</td>
                <td>{{ $member->section ?? '-' }}</td>
                <td>{{ $member->instrument ?? '-' }}</td>
                <td>
                    @if($member->status === 'active')
                        <span class="badge-active">Actif</span>
                    @elseif($member->status === 'pending')
                        <span class="badge-pending">En attente</span>
                    @else
                        <span class="badge-inactive">Inactif</span>
                    @endif
                </td>
                <td>{{ $member->entry_date ? $member->entry_date->format('d/m/Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 30px; color: #999;">
                    Aucun membre enregistré
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Statistiques par section -->
    @php
        $sections = $members->groupBy('section')->map->count();
    @endphp
    @if($sections->count() > 0)
    <div style="margin-top: 30px;">
        <div class="section-title">📊 Statistiques par section</div>
        <table style="width: 50%; border-collapse: collapse; margin-top: 10px;">
            @foreach($sections as $section => $count)
            <tr>
                <td style="padding: 5px 10px; border-bottom: 1px solid #eee;">{{ $section ?? 'Non défini' }}</td>
                <td style="padding: 5px 10px; border-bottom: 1px solid #eee; text-align: right;">
                    <strong>{{ $count }}</strong> membre{{ $count > 1 ? 's' : '' }}
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Statistiques par instrument -->
    @php
        $instruments = $members->groupBy('instrument')->map->count();
    @endphp
    @if($instruments->count() > 0)
    <div style="margin-top: 20px;">
        <div class="section-title">🎵 Statistiques par instrument</div>
        <table style="width: 50%; border-collapse: collapse; margin-top: 10px;">
            @foreach($instruments as $instrument => $count)
            <tr>
                <td style="padding: 5px 10px; border-bottom: 1px solid #eee;">{{ $instrument ?? 'Non défini' }}</td>
                <td style="padding: 5px 10px; border-bottom: 1px solid #eee; text-align: right;">
                    <strong>{{ $count }}</strong> membre{{ $count > 1 ? 's' : '' }}
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <div class="footer">
        <p>
            Document généré automatiquement - Fanfare Céleste &copy; {{ date('Y') }}
            <br>
            <span style="font-size: 9px; color: #999;">
                Ce document est confidentiel et destiné à un usage interne.
            </span>
        </p>
    </div>
</body>
</html>