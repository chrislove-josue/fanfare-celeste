<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: DejaVu Sans, sans-serif; }

.card {
    width: 99mm;
    height: 142mm;
    margin: 3mm auto 3mm;
    border: 5px solid #E6007E;
    background: #ffffff;
    padding: 4mm 4mm 2.5mm;
    overflow: hidden;
    page-break-inside: avoid;
}

/* ===== EN-TÊTE : LOGOS ===== */
.logos-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 2mm;
}

.logos-table td {
    text-align: center;
    vertical-align: middle;
    width: 33.33%;
}

.logo-box {
    width: 22mm;
    height: 18mm;
    margin: 0 auto;
    display: block;
}

.logo-placeholder {
    width: 20mm;
    height: 16mm;
    margin: 0 auto;
    border: 1px dashed #cccccc;
    color: #cccccc;
    font-size: 6px;
    text-align: center;
    line-height: 16mm;
}

/* ===== TITRES ===== */
.title-fanfare {
    text-align: center;
    color: #E6007E;
    font-weight: bold;
    font-size: 19px;
    letter-spacing: 0.5px;
    margin-bottom: 0.5mm;
}

.subtitle-trompette {
    text-align: center;
    color: #2E9E3F;
    font-weight: bold;
    font-size: 14px;
    font-family: "DejaVu Serif", serif;
    margin-bottom: 0.5mm;
}

.country {
    text-align: center;
    color: #111111;
    font-size: 9px;
    font-family: "DejaVu Serif", serif;
    letter-spacing: 0.5px;
    margin-bottom: 1.5mm;
}

/* ===== MATRICULE / TEL ===== */
.top-info-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.5mm;
}

.top-info-table td {
    vertical-align: middle;
}

.matricule-label {
    font-weight: bold;
    color: #111111;
    font-size: 10px;
}

.matricule-value {
    color: #E30613;
    font-weight: bold;
    font-size: 14px;
    white-space: nowrap;
}

.tel-value {
    color: #0072BC;
    font-weight: bold;
    font-size: 9.5px;
    text-align: right;
    white-space: nowrap;
    padding-right: 1mm;
}

/* ===== BLOC INFOS + PHOTO ===== */
.main-table {
    width: 100%;
    border-collapse: collapse;
}

.info-col {
    vertical-align: top;
    width: 50%;
    padding-right: 2mm;
}

.photo-col {
    vertical-align: top;
    width: 50%;
    text-align: right;
}

.info-line {
    font-size: 9px;
    color: #111111;
    margin-bottom: 1.6mm;
    line-height: 1.25;
}

.info-line .label {
    font-weight: bold;
    text-decoration: underline;
}

.info-line .value {
    font-weight: normal;
}

.photo-frame {
    width: 30mm;
    height: 30mm;
    border: 2px solid #29ABE2;
    background-color: #eef2f8;
    overflow: hidden;
    margin-left: auto;
    text-align: center;
    vertical-align: middle;
}

.photo-frame img {
    width: 30mm;
    height: 30mm;
    display: block;
}

.photo-placeholder-text {
    color: #a9b8cf;
    font-size: 8px;
    line-height: 30mm;
}

/* ===== PIED : QR CODE + SIGNATURE ===== */
.footer-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1.5mm;
}

.footer-table td {
    vertical-align: bottom;
}

.qr-col {
    width: 40%;
}

.qr-wrap {
    width: 20mm;
    height: 20mm;
}

.qr-wrap img {
    width: 20mm;
    height: 20mm;
}

.signature-col {
    width: 60%;
    text-align: center;
}

.stamp-placeholder {
    width: 16mm;
    height: 16mm;
    border: 1px dashed #E30613;
    border-radius: 50%;
    color: #E30613;
    font-size: 6px;
    text-align: center;
    line-height: 16mm;
    margin: 0 auto 0.5mm;
}

.stamp-img {
    width: 16mm;
    height: 16mm;
    margin: 0 auto 0.5mm;
    display: block;
}

.le-maitre-label {
    font-size: 9px;
    font-weight: bold;
    color: #111111;
    margin-bottom: 0.5mm;
}

.signature-name {
    font-size: 11px;
    font-weight: bold;
    color: #111111;
    font-family: "DejaVu Serif", serif;
}
</style>
</head>
<body>

@php
    // Découpage du champ "address" au format réel : "Paroisse: X | Sous-région: Y"
    $paroisse = '—';
    $sousRegion = '—';

    if ($member->address) {
        if (preg_match('/Paroisse\s*:\s*(.*?)(?:\||$)/iu', $member->address, $m)) {
            $paroisse = trim($m[1]);
        }
        if (preg_match('/Sous[-\s]?r[ée]gion\s*:\s*(.*?)(?:\||$)/iu', $member->address, $m)) {
            $sousRegion = trim($m[1]);
        }
        // Si aucun label reconnu, on utilise le texte brut comme Paroisse
        if ($paroisse === '—' && $sousRegion === '—') {
            $paroisse = trim($member->address);
        }
    }

    // Matricule complet (ex: FCTDA-004)
    $matriculeNumber = $member->member_number ?? '—';
@endphp

<div class="card">

    {{-- ===== LOGOS ===== --}}
    <table class="logos-table">
        <tr>
            <td>
                {{-- Logo Fanfare Céleste --}}
                @if(file_exists(public_path('images/card/logo-fanfare.png')))
                    <img src="{{ public_path('images/card/logo-fanfare.png') }}" class="logo-box">
                @else
                    <div class="logo-placeholder">LOGO<br>FANFARE</div>
                @endif
            </td>
            <td>
                {{-- Logo Église Céleste (arc-en-ciel) --}}
                @if(file_exists(public_path('images/card/logo-eglise.png')))
                    <img src="{{ public_path('images/card/logo-eglise.png') }}" class="logo-box">
                @else
                    <div class="logo-placeholder">LOGO<br>ÉGLISE</div>
                @endif
            </td>
            <td>
                {{-- Trompette --}}
                @if(file_exists(public_path('images/card/trompette.png')))
                    <img src="{{ public_path('images/card/trompette.png') }}" class="logo-box">
                @else
                    <div class="logo-placeholder">TROMPETTE</div>
                @endif
            </td>
        </tr>
    </table>

    {{-- ===== TITRES ===== --}}
    <div class="title-fanfare">FANFARE CÉLESTE</div>
    <div class="subtitle-trompette">LA TROMPETTE DE DIEU</div>
    <div class="country">RÉPUBLIQUE DU BÉNIN</div>

    {{-- ===== MATRICULE / TEL ===== --}}
    <table class="top-info-table">
        <tr>
            <td style="width:50%;">
                <div class="matricule-label">Matricule :</div>
                <div class="matricule-value">{{ $matriculeNumber }}</div>
            </td>
            <td style="width:50%;" class="tel-value">
                TEL: 61 01 26 49
            </td>
        </tr>
    </table>

    {{-- ===== INFOS + PHOTO ===== --}}
    <table class="main-table">
        <tr>
            <td class="info-col">
                <div class="info-line"><span class="label">Nom</span>: <span class="value">{{ strtoupper($member->last_name) }}</span></div>
                <div class="info-line"><span class="label">Prénoms</span>: <span class="value">{{ $member->first_name }}</span></div>
                <div class="info-line"><span class="label">Né le</span>: <span class="value">{{ $member->birth_date ? $member->birth_date->format('d-m-Y') : '—' }}{{ $member->birth_place ? ' à ' . $member->birth_place : '' }}</span></div>
                <div class="info-line"><span class="label">Paroisse</span>: <span class="value">{{ $paroisse }}</span></div>
                <div class="info-line"><span class="label">Sous Région</span>: <span class="value">{{ $sousRegion }}</span></div>
                <div class="info-line"><span class="label">Instrument</span>: <span class="value">{{ $member->instrument ?? '—' }}</span></div>
                <div class="info-line"><span class="label">Contact</span>: <span class="value">{{ $member->phone ?? '—' }}</span></div>
            </td>
            <td class="photo-col">
                <div class="photo-frame">
                    @if($member->photo_url)
                        <img src="{{ $member->photo_url }}">
                    @else
                        <div class="photo-placeholder-text">PHOTO</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== QR CODE + SIGNATURE ===== --}}
    <table class="footer-table">
        <tr>
            <td class="qr-col">
                <div class="qr-wrap">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR">
                </div>
            </td>
            <td class="signature-col">
                @if(file_exists(public_path('images/card/tampon.png')))
                    <img src="{{ public_path('images/card/tampon.png') }}" class="stamp-img">
                @else
                    <div class="stamp-placeholder">TAMPON</div>
                @endif
                <div class="le-maitre-label">Le maître</div>
                <div class="signature-name">Marius ADOLIWOSSI</div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>