<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; }

body {
    font-family: "DejaVu Sans", sans-serif;
    color: #1f2430;
}

/* =========================================================
   DomPDF ignore box-sizing : width/height s'appliquent à la
   boîte de contenu. 84.12 + 2 x 3.44 (bordure) + 8 (padding)
   = 99 mm de large, 128.12 + 2 x 3.44 + 7 = 142 mm de haut.
   ========================================================= */
.card {
    width: 84.12mm;
    height: 127.8mm;
    margin: 3mm auto;
    border: 13px solid #f5319a;
    background: #ffffff;
    padding: 3.5mm 4mm 3.5mm;
    overflow: hidden;
    page-break-inside: avoid;
}

/* ===== EN-TÊTE : LOGOS ===== */
.logos {
    width: 100%;
    border-collapse: collapse;
    height: 20mm;
}

.logos td {
    width: 33.33%;
    height: 20mm;
    text-align: center;
    vertical-align: middle;
    padding: 0;
}

.logo-img {
    max-width: 27mm;
    max-height: 20mm;
}

.logo-placeholder {
    width: 22mm;
    height: 15mm;
    margin: 0 auto;
    border: 1px dashed #d8d8e2;
    color: #b9b9c7;
    font-size: 5.5px;
    line-height: 15mm;
    text-align: center;
}

.logo-solo {
    height: 20mm;
    text-align: center;
}

.logo-solo img {
    height: 20mm;
}

/* ===== TITRES ===== */
.title {
    text-align: center;
    color: #f5319a;
    font-weight: bold;
    font-size: 18pt;
    line-height: 1.05;
    letter-spacing: 0.3px;
}

.subtitle {
    text-align: center;
    color: #4ea832;
    font-weight: bold;
    font-family: "DejaVu Serif", serif;
    font-size: 13pt;
    line-height: 1.3;
}

.country {
    text-align: center;
    color: #6b7280;
    font-size: 6.5pt;
    letter-spacing: 0.7px;
    margin-top: 0.7mm;
}

/* ===== MATRICULE / TELEPHONE ===== */
.idrow {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2.5mm;
}

.idrow td {
    vertical-align: bottom;
}

.id-left {
    width: 45%;
}

.id-right {
    width: 55%;
    text-align: right;
}

.id-label {
    font-size: 8pt;
    font-weight: bold;
    color: #1f2430;
}

.id-value {
    font-size: 12pt;
    font-weight: bold;
    color: #e30613;
    line-height: 1.15;
}

.id-tel-label {
    font-size: 7pt;
    font-weight: bold;
    color: #4ea832;
    line-height: 1.15;
    margin-bottom: 0.5mm;
}

.id-tel {
    font-size: 11pt;
    font-weight: bold;
    color: #4ea832;
    line-height: 1.15;
}

.id-tel .prefix {
    font-weight: normal;
}

/* ===== INFOS + PHOTO ===== */
.main {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2mm;
    height: 44mm;
}

.main td {
    vertical-align: top;
}

.info {
    width: 52mm;
    padding-right: 2mm;
}

.photo-cell {
    width: 30mm;
    text-align: right;
}

.line {
    font-size: 9px;
    line-height: 1.35;
    margin-bottom: 1.7mm;
}

.line .key {
    font-weight: bold;
    text-decoration: underline;
}

.photo {
    width: 29mm;
    height: 39mm;
    border: 2px solid #00aadd;
    background: #b6d4ed;
    overflow: hidden;
    text-align: center;
}

.photo img {
    width: 29mm;
    height: 39mm;
    display: block;
}

.photo-placeholder {
    color: #6f93b8;
    font-size: 8px;
    line-height: 39mm;
}

/* ===== PIED : QR CODE + SIGNATURE ===== */
.footer {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2mm;
}

.footer td {
    vertical-align: bottom;
}

.qr-cell {
    width: 45%;
}

.qr {
    width: 20mm;
    height: 20mm;
}

.qr img {
    width: 20mm;
    height: 20mm;
}

.sign-cell {
    width: 55%;
    text-align: center;
    vertical-align: bottom;
}

.le-maitre {
    font-size: 8pt;
    font-weight: bold;
    color: #1f2430;
    margin-bottom: 0.8mm;
}

.stamp-img {
    height: 13mm;
    margin-bottom: 0.8mm;
}

.stamp-placeholder {
    width: 13mm;
    height: 13mm;
    margin: 0 auto 0.8mm;
    border: 1px dashed #e30613;
    border-radius: 50%;
    color: #e30613;
    font-size: 5.5px;
    text-align: center;
    line-height: 13mm;
}

.signature-name {
    font-size: 10pt;
    font-weight: bold;
    color: #1f2430;
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

    // Logos de la carte (facultatifs). Si aucun n'est fourni, on retombe
    // sur le logo officiel du site.
    $cardDir       = public_path('images/card');
    $logoFanfare   = file_exists($cardDir . '/logo-fanfare.png') ? $cardDir . '/logo-fanfare.png' : null;
    $logoEglise    = file_exists($cardDir . '/logo-eglise.png') ? $cardDir . '/logo-eglise.png' : null;
    $logoTrompette = file_exists($cardDir . '/trompette.png') ? $cardDir . '/trompette.png' : null;
    $hasCardLogos  = $logoFanfare || $logoEglise || $logoTrompette;

    $siteLogo = public_path('storage/logo/logo.jpg');
    $stamp    = file_exists($cardDir . '/tampon.png') ? $cardDir . '/tampon.png' : null;
@endphp

<div class="card">

    {{-- ===== LOGOS ===== --}}
    @if($hasCardLogos)
        <table class="logos">
            <tr>
                <td>
                    @if($logoFanfare)
                        <img src="{{ $logoFanfare }}" class="logo-img" alt="">
                    @else
                        <div class="logo-placeholder">LOGO<br>FANFARE</div>
                    @endif
                </td>
                <td>
                    @if($logoEglise)
                        <img src="{{ $logoEglise }}" class="logo-img" alt="">
                    @else
                        <div class="logo-placeholder">LOGO<br>ÉGLISE</div>
                    @endif
                </td>
                <td>
                    @if($logoTrompette)
                        <img src="{{ $logoTrompette }}" class="logo-img" alt="">
                    @else
                        <div class="logo-placeholder">TROMPETTE</div>
                    @endif
                </td>
            </tr>
        </table>
    @elseif(file_exists($siteLogo))
        <div class="logo-solo">
            <img src="{{ $siteLogo }}" alt="">
        </div>
    @else
        <table class="logos"><tr><td>&nbsp;</td></tr></table>
    @endif

    {{-- ===== TITRES ===== --}}
    <div class="title">FANFARE CÉLESTE</div>
    <div class="subtitle">LA TROMPETTE DE DIEU</div>
    <div class="country">de l'atlantique</div>

    {{-- ===== MATRICULE / TÉLÉPHONE ===== --}}
    <table class="idrow">
        <tr>
            <td class="id-left">
                <div class="id-label">Matricule</div>
                <div class="id-value">{{ $matriculeNumber }}</div>
            </td>
            <td class="id-right">
                <div class="id-tel-label">Tél. du club fanfare</div>
                <div class="id-tel">+229 01 96 31 07 75</div>
            </td>
        </tr>
    </table>

    {{-- ===== INFOS + PHOTO ===== --}}
    <table class="main">
        <tr>
            <td class="info">
                <div class="line"><span class="key">Nom</span>: {{ strtoupper($member->last_name) }}</div>
                <div class="line"><span class="key">Prénoms</span>: {{ $member->first_name }}</div>
                <div class="line"><span class="key">Né le</span>: {{ $member->birth_date ? $member->birth_date->format('d-m-Y') : '—' }}{{ $member->birth_place ? ' à ' . $member->birth_place : '' }}</div>
                <div class="line"><span class="key">Paroisse</span>: {{ $paroisse }}</div>
                <div class="line"><span class="key">Sous Région</span>: {{ $sousRegion }}</div>
                <div class="line"><span class="key">Instrument</span>: {{ $member->instrument ?? '—' }}</div>
                <div class="line"><span class="key">Contact</span>: {{ $member->phone ?? '—' }}</div>
            </td>
            <td class="photo-cell">
                <div class="photo">
                    @if($member->photo_url)
                        <img src="{{ $member->photo_url }}" alt="">
                    @else
                        <div class="photo-placeholder">PHOTO</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== QR CODE + SIGNATURE ===== --}}
    <table class="footer">
        <tr>
            <td class="qr-cell">
                <div class="qr">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR">
                </div>
            </td>
            <td class="sign-cell">
                <div class="le-maitre">Le maître</div>
                @if($stamp)
                    <img src="{{ $stamp }}" class="stamp-img" alt="">
                @else
                    <div class="stamp-placeholder">TAMPON</div>
                @endif
                <div class="signature-name">Marius ADOLIWOSSI</div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
