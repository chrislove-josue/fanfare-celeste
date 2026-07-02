<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vérification — {{ $member->full_name }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background: linear-gradient(135deg, #1a1a3e 0%, #283593 50%, #3946B2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .verify-card {
        background: #ffffff;
        border-radius: 20px;
        max-width: 420px;
        width: 100%;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .verify-header {
        background: linear-gradient(135deg, #1a1a3e 0%, #283593 50%, #3946B2 100%);
        padding: 28px 24px 24px;
        text-align: center;
        color: #fff;
    }

    .badge-check {
        width: 56px;
        height: 56px;
        margin: 0 auto 12px;
        background: rgba(64, 168, 27, 0.2);
        border: 2px solid #40A81B;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #40A81B;
    }

    .verify-title {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        opacity: 0.8;
    }

    .verify-org {
        font-size: 11px;
        opacity: 0.5;
        margin-top: 2px;
    }

    .member-photo {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        margin: -45px auto 0;
        border: 4px solid #fff;
        background: #eef2f8;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        z-index: 1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .member-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .member-photo .initials {
        font-size: 30px;
        font-weight: 700;
        color: #3946B2;
    }

    .member-body {
        padding: 55px 24px 28px;
        text-align: center;
    }

    .member-name {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 2px;
    }

    .member-number {
        font-size: 13px;
        color: #3946B2;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 14px;
    }

    .status-pill {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pill.active { background: #dcfce7; color: #16a34a; }
    .status-pill.inactive { background: #fee2e2; color: #dc2626; }
    .status-pill.pending { background: #fef3c7; color: #d97706; }

    .info-grid {
        text-align: left;
        border-top: 1px solid #eef0f5;
        padding-top: 18px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 13px;
        gap: 10px;
    }

    .info-row .info-label {
        color: #9ca3af;
        flex-shrink: 0;
    }

    .info-row .info-value {
        color: #1a1a2e;
        font-weight: 600;
        text-align: right;
    }

    .verify-footer {
        text-align: center;
        padding: 16px 24px 22px;
        font-size: 11px;
        color: #9ca3af;
        border-top: 1px solid #eef0f5;
    }
</style>
</head>
<body>

<div class="verify-card">
    <div class="verify-header">
        <div class="badge-check">✓</div>
        <div class="verify-title">Membre vérifié</div>
        <div class="verify-org">Fanfare Céleste — La Trompette de Dieu</div>
    </div>

    <div class="member-photo">
        @if($member->photo_url)
            <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}">
        @else
            <div class="initials">{{ $member->initials }}</div>
        @endif
    </div>

    <div class="member-body">
        <div class="member-name">{{ $member->full_name }}</div>
        <div class="member-number">{{ $member->member_number }}</div>

        <div class="status-pill {{ $member->status === 'active' ? 'active' : ($member->status === 'pending' ? 'pending' : 'inactive') }}">
            @if($member->status === 'active') ✓ Actif
            @elseif($member->status === 'pending') En attente
            @else Inactif
            @endif
        </div>

        <div class="info-grid">
            @if($currentCommitteePosition)
                <div class="info-row">
                    <span class="info-label">Poste</span>
                    <span class="info-value">{{ $currentCommitteePosition->position_label ?? $currentCommitteePosition->position }}</span>
                </div>
            @endif
            <div class="info-row">
                <span class="info-label">Section</span>
                <span class="info-value">{{ $member->section ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Instrument</span>
                <span class="info-value">{{ $member->instrument ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Téléphone</span>
                <span class="info-value">{{ $member->phone ?? '—' }}</span>
            </div>
            @if($member->whatsapp)
                <div class="info-row">
                    <span class="info-label">WhatsApp</span>
                    <span class="info-value">{{ $member->whatsapp }}</span>
                </div>
            @endif
            @if($paroisse)
                <div class="info-row">
                    <span class="info-label">Paroisse</span>
                    <span class="info-value">{{ $paroisse }}</span>
                </div>
            @endif
            @if($sousRegion)
                <div class="info-row">
                    <span class="info-label">Sous région</span>
                    <span class="info-value">{{ $sousRegion }}</span>
                </div>
            @endif
            <div class="info-row">
                <span class="info-label">Membre depuis</span>
                <span class="info-value">{{ $member->entry_date ? $member->entry_date->format('d/m/Y') : '—' }}</span>
            </div>
        </div>
    </div>

    <div class="verify-footer">
        Cette page confirme l'authenticité de la carte de membre.
    </div>
</div>

</body>
</html>