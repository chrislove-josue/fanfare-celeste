<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste complète des membres - Fanfare Céleste</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            padding: 12px;
            color: #1a1a2e;
            background: #ffffff;
            position: relative;
            min-height: 100vh;
        }
        
        /* ============================================
           WATERMARK
        ============================================ */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 55px;
            font-weight: 900;
            color: rgba(57, 70, 178, 0.025);
            letter-spacing: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
            width: 100%;
            text-align: center;
            font-family: 'Inter', Arial, sans-serif;
        }
        
        /* ============================================
           HEADER
        ============================================ */
        .header {
            text-align: center;
            border-bottom: 3px solid #3946B2;
            padding-bottom: 10px;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .header-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .header-logo img {
            height: 60px;
            width: auto;
            border-radius: 8px;
        }
        
        .header-text {
            text-align: left;
        }
        
        .header-text h1 {
            color: #3946B2;
            font-size: 20px;
            font-weight: 800;
            margin: 0;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .header-text .subtitle {
            color: #4a5568;
            font-size: 11px;
            font-weight: 500;
            margin: 2px 0 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .header-text .slogan {
            color: #6b7280;
            font-size: 9px;
            font-weight: 400;
            margin: 2px 0 0;
            font-style: italic;
        }
        
        .header-meta {
            margin-top: 5px;
            font-size: 9px;
            color: #6b7280;
        }
        
        .header-meta span {
            background: #f0f2f8;
            padding: 2px 10px;
            border-radius: 20px;
            margin: 0 3px;
        }

        /* ============================================
           STATS BAR
        ============================================ */
        .stats-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 8px 0;
            padding: 6px 12px;
            background: #f8fafc;
            border-radius: 6px;
            border: 1px solid #eef2f6;
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
            gap: 4px;
        }
        
        .stats-bar .stat-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .stats-bar .stat-item .label {
            color: #6b7280;
            font-weight: 500;
            font-size: 8px;
        }
        
        .stats-bar .stat-item .value {
            color: #1a1a2e;
            font-weight: 700;
            font-size: 10px;
        }
        
        .stats-bar .stat-divider {
            width: 1px;
            height: 18px;
            background: #e5e7eb;
        }
        
        .badge-count {
            background: #3946B2;
            color: #fff;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: 600;
        }

        /* ============================================
           TABLE
        ============================================ */
        .table-wrapper {
            position: relative;
            z-index: 1;
            overflow-x: auto;
            margin-top: 4px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5px;
        }
        
        .table th {
            background: #3946B2;
            color: #ffffff;
            padding: 4px 4px;
            text-align: left;
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border: 1px solid #2a37a0;
        }
        
        .table td {
            padding: 3px 4px;
            border: 1px solid #e5e7eb;
            font-size: 7.5px;
            color: #1a1a2e;
            vertical-align: middle;
        }
        
        .table tbody tr:nth-child(even) {
            background: #fafbfd;
        }
        
        .table tbody tr:hover {
            background: #f0f2f8;
        }
        
        .table .member-number {
            font-weight: 700;
            color: #3946B2;
            font-size: 7.5px;
        }
        
        .table .member-name {
            font-weight: 600;
            font-size: 8px;
        }
        
        /* Photo dans le tableau */
        .member-photo {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
        }
        
        .member-photo-placeholder {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #f0f2f8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #9ca3af;
            border: 2px solid #e5e7eb;
            margin: 0 auto;
        }
        
        /* Badges de statut */
        .badge-status {
            padding: 1px 6px;
            border-radius: 20px;
            font-size: 6.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-block;
            white-space: nowrap;
        }
        
        .badge-active {
            background: #dcfce7;
            color: #16a34a;
        }
        
        .badge-pending {
            background: #fef3c7;
            color: #d97706;
        }
        
        .badge-inactive {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .badge-suspended {
            background: #fef3c7;
            color: #d97706;
        }
        
        .badge-withdrawn {
            background: #e5e7eb;
            color: #6b7280;
        }
        
        .badge-deceased {
            background: #1f2937;
            color: #ffffff;
        }
        
        .badge-training {
            background: #dbeafe;
            color: #2563eb;
        }
        
        /* Niveaux d'instrument */
        .level-badge {
            padding: 1px 5px;
            border-radius: 10px;
            font-size: 6.5px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .level-Soprano {
            background: #fef3c7;
            color: #d97706;
        }
        
        .level-Alto {
            background: #dbeafe;
            color: #2563eb;
        }
        
        .level-Ténor {
            background: #dcfce7;
            color: #16a34a;
        }
        
        .level-Basse {
            background: #f3e8ff;
            color: #7c3aed;
        }

        /* ============================================
           STATISTIQUES DÉTAILLÉES
        ============================================ */
        .stats-sections {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
            position: relative;
            z-index: 1;
        }
        
        .stats-sections .stats-block {
            flex: 1;
            min-width: 170px;
            background: #f8fafc;
            border-radius: 6px;
            padding: 8px 12px;
            border: 1px solid #eef2f6;
        }
        
        .stats-sections .stats-block .block-title {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: #3946B2;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            padding-bottom: 4px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .stats-sections .stats-block .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 8px;
            border-bottom: 1px dashed #f0f2f8;
        }
        
        .stats-sections .stats-block .stat-row:last-child {
            border-bottom: none;
        }
        
        .stats-sections .stats-block .stat-row .stat-name {
            color: #4a5568;
        }
        
        .stats-sections .stats-block .stat-row .stat-count {
            font-weight: 700;
            color: #1a1a2e;
        }

        /* ============================================
           FOOTER
        ============================================ */
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 7px;
            color: #9ca3af;
            position: relative;
            z-index: 1;
        }
        
        .footer .footer-brand {
            font-weight: 700;
            color: #3946B2;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .footer .footer-note {
            font-size: 7px;
            color: green;
            margin-top: 2px;
        }

        /* ============================================
           PRINT STYLES
        ============================================ */
        @media print {
            body {
                padding: 8px;
                font-size: 7px;
            }
            
            .watermark {
                font-size: 40px;
                color: rgba(57, 70, 178, 0.02);
            }
            
            .header {
                border-bottom-width: 2px;
            }
            
            .header-logo img {
                height: 50px;
            }
            
            .header-text h1 {
                font-size: 16px;
            }
            
            .table th {
                background: #3946B2 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .badge-active,
            .badge-pending,
            .badge-inactive,
            .badge-suspended,
            .badge-withdrawn,
            .badge-deceased,
            .badge-training,
            .level-Soprano,
            .level-Alto,
            .level-Ténor,
            .level-Basse {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .stats-bar {
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .stats-sections .stats-block {
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .badge-count {
                background: #3946B2 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .member-photo,
            .member-photo-placeholder {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .table td {
                padding: 2px 3px;
            }
            
            .stats-sections .stats-block .stat-row {
                padding: 1px 0;
            }
            
            .footer {
                margin-top: 10px;
                padding-top: 5px;
            }
        }
    </style>
</head>
<body>

    <!-- ========================================== -->
    <!-- WATERMARK                                  -->
    <!-- ========================================== -->
    <div class="watermark">
        FANFARE CÉLESTE<br>
        <span style="font-size:28px;">LA TROMPETTE DE DIEU DE L'ATLANTIQUE</span>
    </div>

    <!-- ========================================== -->
    <!-- HEADER                                     -->
    <!-- ========================================== -->
    <div class="header">
        <div class="header-content">
            <div class="header-logo">
                @php
                    $logoPath = public_path('storage/logo/logo-blanc.jpeg');
                    $logoBase64 = null;
                    if (file_exists($logoPath)) {
                        $logoData = file_get_contents($logoPath);
                        $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($logoData);
                    }
                @endphp
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Fanfare Céleste" style="height:60px;width:auto;border-radius:8px;">
                @else
                    <div style="height:60px;width:60px;background:#3946B2;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;font-size:10px;">FC</div>
                @endif
                <div class="header-text">
                    <h1>Fanfare Céleste</h1>
                    <div class="subtitle">La Trompette de Dieu de l'Atlantique</div>
                    <div class="slogan">« Servir Dieu par la musique »</div>
                </div>
            </div>
        </div>
        <div class="header-meta">
            <span>📋 Rapport complet des membres</span>
            <span>📅 Généré le {{ date('d/m/Y à H:i') }}</span>
            <span>🆕 Version détaillée</span>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- STATS BAR                                  -->
    <!-- ========================================== -->
    <div class="stats-bar">
        <div class="stat-item">
            <span class="label">👥 Total</span>
            <span class="value">{{ $stats['total'] }}</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <span class="label">✅ Actifs</span>
            <span class="value">{{ $stats['active'] }}</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <span class="label">⏳ Attente</span>
            <span class="value">{{ $stats['pending'] }}</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <span class="label">⛔ Suspendus</span>
            <span class="value">{{ $stats['suspended'] }}</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <span class="label">📚 Formation</span>
            <span class="value">{{ $stats['training'] }}</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <span class="label">📊 Sections</span>
            <span class="value">{{ $stats['sections']->count() }}</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <span class="label">🏛️ Paroisses</span>
            <span class="value">{{ count($stats['paroisses']) }}</span>
        </div>
        <div>
            <span class="badge-count">{{ $stats['total'] }} MEMBRES</span>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TABLEAU COMPLET DES MEMBRES                -->
    <!-- ========================================== -->
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:18px;">#</th>
                    <th style="width:30px;">Photo</th>
                    <th style="width:55px;">N° Membre</th>
                    <th style="width:70px;">Nom complet</th>
                    <th style="width:65px;">Email</th>
                    <th style="width:45px;">Téléphone</th>
                    <th style="width:40px;">WhatsApp</th>
                    <th style="width:55px;">Paroisse</th>
                    <th style="width:45px;">Sous-région</th>
                    <th style="width:40px;">Section</th>
                    <th style="width:45px;">Instrument</th>
                    <th style="width:35px;">Niveau</th>
                    <th style="width:40px;">Rôle</th>
                    <th style="width:32px;">Statut</th>
                    <th style="width:35px;">Entrée</th>
                    <th style="width:35px;">Naissance</th>
                    <th style="width:45px;">Contact urgence</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $index => $member)
                @php
                    // Extraire Paroisse et Sous-région du champ address
                    $paroisse = '-';
                    $sousRegion = '-';
                    if ($member->address) {
                        if (preg_match('/Paroisse\s*:\s*(.*?)(?:\||$)/iu', $member->address, $m)) {
                            $paroisse = trim($m[1]) ?: '-';
                        }
                        if (preg_match('/Sous[-\s]?r[ée]gion\s*:\s*(.*?)(?:\||$)/iu', $member->address, $m)) {
                            $sousRegion = trim($m[1]) ?: '-';
                        }
                        if ($paroisse === '-' && $sousRegion === '-') {
                            $paroisse = trim($member->address) ?: '-';
                        }
                    }
                    
                    // Convertir la photo en base64
                    $photoBase64 = null;
                    if ($member->photo) {
                        $photoPath = storage_path('app/public/' . $member->photo);
                        if (file_exists($photoPath)) {
                            $photoData = file_get_contents($photoPath);
                            $photoType = pathinfo($photoPath, PATHINFO_EXTENSION);
                            $photoBase64 = 'data:image/' . $photoType . ';base64,' . base64_encode($photoData);
                        }
                    }
                @endphp
                <tr>
                    <td style="text-align:center;font-weight:600;">{{ $index + 1 }}</td>
                    <td style="text-align:center;">
                        @if($photoBase64)
                            <img src="{{ $photoBase64 }}" 
                                 alt="Photo" 
                                 style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:2px solid #e5e7eb;">
                        @else
                            <div style="width:28px;height:28px;border-radius:50%;background:#f0f2f8;display:flex;align-items:center;justify-content:center;font-size:12px;color:#9ca3af;border:2px solid #e5e7eb;margin:0 auto;">
                                👤
                            </div>
                        @endif
                    </td>
                    <td class="member-number">{{ $member->member_number ?? '-' }}</td>
                    <td class="member-name">{{ $member->first_name }} {{ $member->last_name }}</td>
                    <td style="font-size:6.5px;">{{ $member->email ?? '-' }}</td>
                    <td>{{ $member->phone ?? '-' }}</td>
                    <td>{{ $member->whatsapp ?? '-' }}</td>
                    <td style="font-weight:600;color:#3946B2;font-size:7px;">{{ $paroisse }}</td>
                    <td style="font-size:7px;">{{ $sousRegion }}</td>
                    <td>
                        @if($member->section)
                            <span style="font-weight:600;color:#3946B2;font-size:7px;">{{ $member->section }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $member->instrument ?? '-' }}</td>
                    <td>
                        @if($member->instrument_level)
                            <span class="level-badge level-{{ $member->instrument_level }}">
                                {{ $member->instrument_level }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td style="font-size:7px;">{{ $member->section_role ?? '-' }}</td>
                    <td>
                        @if($member->status === 'active')
                            <span class="badge-status badge-active">Actif</span>
                        @elseif($member->status === 'pending')
                            <span class="badge-status badge-pending">Attente</span>
                        @elseif($member->status === 'suspended')
                            <span class="badge-status badge-suspended">Suspendu</span>
                        @elseif($member->status === 'withdrawn')
                            <span class="badge-status badge-withdrawn">Retiré</span>
                        @elseif($member->status === 'deceased')
                            <span class="badge-status badge-deceased">Décédé</span>
                        @elseif($member->status === 'training')
                            <span class="badge-status badge-training">Formation</span>
                        @else
                            <span class="badge-status badge-inactive">Inactif</span>
                        @endif
                    </td>
                    <td>{{ $member->entry_date ? $member->entry_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $member->birth_date ? $member->birth_date->format('d/m/Y') : '-' }}</td>
                    <td style="font-size:6.5px;">
                        @if($member->emergency_contact_name || $member->emergency_contact_phone)
                            {{ $member->emergency_contact_name ?? '' }}
                            @if($member->emergency_contact_phone)
                                ({{ $member->emergency_contact_phone }})
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="17" style="text-align:center;padding:25px;color:#9ca3af;">
                        <div style="font-size:25px;margin-bottom:6px;">📭</div>
                        Aucun membre enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- STATISTIQUES DÉTAILLÉES                    -->
    <!-- ========================================== -->
    <div class="stats-sections">
        @if($stats['sections']->count() > 0)
        <div class="stats-block">
            <div class="block-title">📊 Par section</div>
            @foreach($stats['sections'] as $section => $count)
            <div class="stat-row">
                <span class="stat-name">{{ $section ?? 'Non défini' }}</span>
                <span class="stat-count">{{ $count }} <span style="font-weight:400;color:#9ca3af;font-size:7px;">({{ round(($count / $stats['total']) * 100, 1) }}%)</span></span>
            </div>
            @endforeach
        </div>
        @endif
        
        @if($stats['instruments']->count() > 0)
        <div class="stats-block">
            <div class="block-title">🎵 Par instrument</div>
            @foreach($stats['instruments'] as $instrument => $count)
            <div class="stat-row">
                <span class="stat-name">{{ $instrument ?? 'Non défini' }}</span>
                <span class="stat-count">{{ $count }} <span style="font-weight:400;color:#9ca3af;font-size:7px;">({{ round(($count / $stats['total']) * 100, 1) }}%)</span></span>
            </div>
            @endforeach
        </div>
        @endif
        
        @if(count($stats['paroisses']) > 0)
        <div class="stats-block">
            <div class="block-title">🏛️ Par paroisse</div>
            @foreach($stats['paroisses'] as $paroisse => $count)
            <div class="stat-row">
                <span class="stat-name">{{ $paroisse }}</span>
                <span class="stat-count">{{ $count }} <span style="font-weight:400;color:#9ca3af;font-size:7px;">({{ round(($count / $stats['total']) * 100, 1) }}%)</span></span>
            </div>
            @endforeach
        </div>
        @endif
        
        @if(count($stats['sous_regions']) > 0)
        <div class="stats-block">
            <div class="block-title">📍 Par sous-région</div>
            @foreach($stats['sous_regions'] as $sousRegion => $count)
            <div class="stat-row">
                <span class="stat-name">{{ $sousRegion }}</span>
                <span class="stat-count">{{ $count }} <span style="font-weight:400;color:#9ca3af;font-size:7px;">({{ round(($count / $stats['total']) * 100, 1) }}%)</span></span>
            </div>
            @endforeach
        </div>
        @endif
        
        <div class="stats-block">
            <div class="block-title">📌 Statut des membres</div>
            <div class="stat-row">
                <span class="stat-name">Actifs</span>
                <span class="stat-count">{{ $stats['active'] }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-name">En attente</span>
                <span class="stat-count">{{ $stats['pending'] }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-name">Suspendus</span>
                <span class="stat-count">{{ $stats['suspended'] }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-name">En formation</span>
                <span class="stat-count">{{ $stats['training'] }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-name">Inactifs</span>
                <span class="stat-count">{{ $stats['inactive'] }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-name">Retirés</span>
                <span class="stat-count">{{ $stats['withdrawn'] }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-name">Décédés</span>
                <span class="stat-count">{{ $stats['deceased'] }}</span>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- FOOTER                                     -->
    <!-- ========================================== -->
    <div class="footer">
        <p>
            <span class="footer-brand">Fanfare Céleste</span> &copy; 2020 - {{ date('Y') }}
            <br>
            <span class="footer-note">
                🔒 Ce document est confidentiel et destiné à un usage interne de la Fanfare Céleste.
            </span>
            <br>
            <span class="footer-note">
                📋 Rapport complet généré le {{ date('d/m/Y à H:i:s') }} - {{ $stats['total'] }} membres listés
            </span>
            <br>
            <span style="font-size:6px;color:#9ca3af;">
                La Trompette de Dieu de l'Atlantique - Tous droits réservés
            </span>
        </p>
    </div>

</body>
</html>