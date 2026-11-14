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
    $qrCode   = $member->memberCardQrCode;

    // Dimensions de la photo pour éviter la déformation (contain dans 29x39mm)
    $photoW = 29; $photoH = 39; // mm (cadre)
    $imgW = $photoW; $imgH = $photoH; $imgMt = 0; $imgMl = 0;
    if ($member->photo_url && preg_match('#^data:image/[^;]+;base64,(.+)$#', $member->photo_url, $pm)) {
        $bin = base64_decode($pm[1]);
        $info = @getimagesizefromstring($bin);
        if ($info && $info[0] > 0 && $info[1] > 0) {
            $scale = min($photoW / $info[0], $photoH / $info[1]);
            $imgW = $info[0] * $scale;
            $imgH = $info[1] * $scale;
            $imgMt = ($photoH - $imgH) / 2;
            $imgMl = ($photoW - $imgW) / 2;
        }
    }
@endphp

<div class="card" @if(!empty($lastCard)) style="page-break-after: auto;" @endif>

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
    <div class="country">DE L'ATLANTIQUE</div>

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
                        <img src="{{ $member->photo_url }}" style="width: {{ $imgW }}mm; height: {{ $imgH }}mm; margin-top: {{ $imgMt }}mm; margin-left: {{ $imgMl }}mm;" alt="">
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
