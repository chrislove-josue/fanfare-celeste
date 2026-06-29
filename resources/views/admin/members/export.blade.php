<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des membres - Fanfare Céleste</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            color: #1a1a2e;
            background: #ffffff;
            position: relative;
            min-height: 100vh;
        }
        
        /* ============================================
           WATERMARK - EN MAJUSCULES
        ============================================ */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 70px;
            font-weight: 900;
            color: rgba(57, 70, 178, 0.04);
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
            padding-bottom: 15px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .header-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .header-logo img {
            height: 150px;
            width: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .header-text {
            text-align: left;
        }
        
        .header-text h1 {
            color: #3946B2;
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .header-text .subtitle {
            color: #4a5568;
            font-size: 14px;
            font-weight: 500;
            margin: 2px 0 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .header-text .slogan {
            color: #6b7280;
            font-size: 11px;
            font-weight: 400;
            margin: 2px 0 0;
            font-style: italic;
        }
        
        .header-meta {
            margin-top: 8px;
            font-size: 12px;
            color: #6b7280;
        }
        
        .header-meta span {
            background: #f0f2f8;
            padding: 3px 12px;
            border-radius: 20px;
            margin: 0 3px;
        }

        /* ============================================
           STATS
        ============================================ */
        .stats-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
            padding: 10px 15px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #eef2f6;
            position: relative;
            z-index: 1;
        }
        
        .stats-bar .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .stats-bar .stat-item .label {
            color: #6b7280;
            font-weight: 500;
        }
        
        .stats-bar .stat-item .value {
            color: #1a1a2e;
            font-weight: 700;
            font-size: 14px;
        }
        
        .stats-bar .stat-divider {
            width: 1px;
            height: 25px;
            background: #e5e7eb;
        }
        
        .badge-count {
            background: #3946B2;
            color: #fff;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }

        /* ============================================
           TABLE
        ============================================ */
        .table-wrapper {
            position: relative;
            z-index: 1;
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .table th {
            background: #3946B2;
            color: #ffffff;
            padding: 10px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        
        .table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f0f2f8;
            font-size: 11px;
            color: #1a1a2e;
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
            font-size: 10px;
        }
        
        .table .member-name {
            font-weight: 600;
        }
        
        /* Badges de statut */
        .badge-status {
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-block;
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

        /* ============================================
           STATISTIQUES SECTIONS / INSTRUMENTS
        ============================================ */
        .stats-sections {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 25px;
            position: relative;
            z-index: 1;
        }
        
        .stats-sections .stats-block {
            flex: 1;
            min-width: 250px;
            background: #f8fafc;
            border-radius: 10px;
            padding: 15px 20px;
            border: 1px solid #eef2f6;
        }
        
        .stats-sections .stats-block .block-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #3946B2;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .stats-sections .stats-block .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 11px;
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
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
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
            font-size: 12px;
            color: green;
            margin-top: 3px;
        }

        /* ============================================
           PRINT STYLES
        ============================================ */
        @media print {
            body {
                padding: 10px;
            }
            
            .watermark {
                font-size: 60px;
                color: rgba(57, 70, 178, 0.03);
            }
            
            .header {
                border-bottom-width: 2px;
            }
            
            .header-logo img {
                height: 55px;
            }
            
            .header-text h1 {
                font-size: 22px;
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
            .badge-training {
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
        }
    </style>
</head>
<body>

    <!-- ========================================== -->
    <!-- WATERMARK EN MAJUSCULES                     -->
    <!-- ========================================== -->
    <div class="watermark">
        FANFARE CÉLESTE<br>
        <span class="watermark">LA TROMPETTE DE DIEU DE L'ATLANTIQUE</span>
    </div>

    <!-- ========================================== -->
    <!-- HEADER                                     -->
    <!-- ========================================== -->
    <div class="header">
        <div class="header-content">
            <div class="header-logo">
                <img src="{{ public_path('storage/logo/logo-blanc.jpeg') }}" alt="Fanfare Céleste">
                <div class="header-text">
                    <h1>Fanfare Céleste</h1>
                    <div class="subtitle">La Trompette de Dieu de l'Atlantique</div>
                    <div class="slogan">« Servir Dieu par la musique »</div>
                </div>
            </div>
        </div>
        <div class="header-meta">
            <span>📋 Liste des membres</span>
            <span>📅 Généré le {{ date('d/m/Y à H:i') }}</span>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- STATS BAR                                  -->
    <!-- ========================================== -->
    <div class="stats-bar">
        <div class="stat-item">
            <span class="label">👥 Total membres</span>
            <span class="value">{{ $members->count() }}</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <span class="label">✅ Actifs</span>
            <span class="value">{{ $members->where('status', 'active')->count() }}</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <span class="label">⏳ En attente</span>
            <span class="value">{{ $members->where('status', 'pending')->count() }}</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <span class="label">📊 Sections</span>
            <span class="value">{{ $members->groupBy('section')->count() }}</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <span class="label">🎵 Instruments</span>
            <span class="value">{{ $members->groupBy('instrument')->count() }}</span>
        </div>
        <div>
            <span class="badge-count">{{ $members->count() }} MEMBRES</span>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TABLEAU DES MEMBRES                        -->
    <!-- ========================================== -->
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th style="width: 80px;">N° Membre</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Section</th>
                    <th>Instrument</th>
                    <th style="width: 80px;">Statut</th>
                    <th style="width: 90px;">Date d'entrée</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $index => $member)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="member-number">{{ $member->member_number ?? '-' }}</td>
                    <td class="member-name">{{ $member->first_name }} {{ $member->last_name }}</td>
                    <td>{{ $member->email ?? '-' }}</td>
                    <td>{{ $member->phone ?? '-' }}</td>
                    <td>{{ $member->section ?? '-' }}</td>
                    <td>{{ $member->instrument ?? '-' }}</td>
                    <td>
                        @if($member->status === 'active')
                            <span class="badge-status badge-active">Actif</span>
                        @elseif($member->status === 'pending')
                            <span class="badge-status badge-pending">En attente</span>
                        @elseif($member->status === 'suspended')
                            <span class="badge-status badge-suspended">Suspendu</span>
                        @elseif($member->status === 'withdrawn')
                            <span class="badge-status badge-withdrawn">Retiré</span>
                        @elseif($member->status === 'deceased')
                            <span class="badge-status badge-deceased">Décédé</span>
                        @elseif($member->status === 'training')
                            <span class="badge-status badge-training">En formation</span>
                        @else
                            <span class="badge-status badge-inactive">Inactif</span>
                        @endif
                    </td>
                    <td>{{ $member->entry_date ? $member->entry_date->format('d/m/Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: #9ca3af;">
                        <div style="font-size: 40px; margin-bottom: 10px;">📭</div>
                        Aucun membre enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- STATISTIQUES PAR SECTION ET INSTRUMENT      -->
    <!-- ========================================== -->
    @php
        $sections = $members->groupBy('section')->map->count()->sortDesc();
        $instruments = $members->groupBy('instrument')->map->count()->sortDesc();
    @endphp
    
    @if($sections->count() > 0 || $instruments->count() > 0)
    <div class="stats-sections">
        @if($sections->count() > 0)
        <div class="stats-block">
            <div class="block-title">📊 Par section</div>
            @foreach($sections as $section => $count)
            <div class="stat-row">
                <span class="stat-name">{{ $section ?? 'Non défini' }}</span>
                <span class="stat-count">{{ $count }} <span style="font-weight:400;color:#9ca3af;font-size:10px;">membre{{ $count > 1 ? 's' : '' }}</span></span>
            </div>
            @endforeach
        </div>
        @endif
        
        @if($instruments->count() > 0)
        <div class="stats-block">
            <div class="block-title">🎵 Par instrument</div>
            @foreach($instruments as $instrument => $count)
            <div class="stat-row">
                <span class="stat-name">{{ $instrument ?? 'Non défini' }}</span>
                <span class="stat-count">{{ $count }} <span style="font-weight:400;color:#9ca3af;font-size:10px;">membre{{ $count > 1 ? 's' : '' }}</span></span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    <!-- ========================================== -->
    <!-- FOOTER                                     -->
    <!-- ========================================== -->
    <div class="footer">
        <p>
            <span class="footer-brand">Fanfare Céleste</span> &copy; 2020 - {{ date('Y') }}
            <br>
            <span class="footer-note">
                🔒 Ce document est confidentiel et destiné à un usage interne.
            </span>
            <br>
            <span class="footer-note">
                La Trompette de Dieu de l'Atlantique - Tous droits réservés
            </span>
        </p>
    </div>

</body>
</html>