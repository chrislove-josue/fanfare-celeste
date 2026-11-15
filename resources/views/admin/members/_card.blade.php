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

    // Logos de la carte. Les fichiers sont dans public/storage/logo/.
    // Gauche (FCDTA) : logo-ecc.png -> ecc.png -> logo-blanc.jpeg -> logo.jpg
    // Milieu (Église) : logo-eglise.png
    // Droite (Instrument) : trompette.png
    $logoDir = public_path('storage/logo');
    $logoFanfare = null;
    foreach (['logo-ecc.png', 'ecc.png', 'logo-blanc.jpeg', 'logo.jpg'] as $candidate) {
        if (file_exists($logoDir . '/' . $candidate)) {
            $logoFanfare = $logoDir . '/' . $candidate;
            break;
        }
    }
    $logoEglise    = file_exists($logoDir . '/logo-eglise.png') ? $logoDir . '/logo-eglise.png' : null;
    $logoTrompette = file_exists($logoDir . '/trompette.png') ? $logoDir . '/trompette.png' : null;

    // Helper : charge une image, la redimensionne (max 200px) et la renvoie en data URI base64.
    // Cela rend la carte autonome (pas de dépendance au système de fichiers / symlink en prod).
    $toDataUri = function (?string $path, int $maxDim = 200): ?string {
        if (!$path || !file_exists($path)) return null;
        $mime = @mime_content_type($path);
        $src = match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => @imagecreatefromwebp($path),
            default => null,
        };
        if (!$src) {
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }
        $sw = imagesx($src); $sh = imagesy($src);
        $scale = min($maxDim / $sw, $maxDim / $sh, 1);
        $nw = max(1, (int) round($sw * $scale));
        $nh = max(1, (int) round($sh * $scale));
        $dst = imagecreatetruecolor($nw, $nh);
        if (in_array($mime, ['image/png', 'image/webp'], true)) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $transparent);
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $sw, $sh);
        ob_start();
        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagepng($dst, null);
            $outMime = 'image/png';
        } else {
            imagejpeg($dst, null, 85);
            $outMime = 'image/jpeg';
        }
        $data = ob_get_clean();
        imagedestroy($src);
        imagedestroy($dst);
        return 'data:' . $outMime . ';base64,' . base64_encode($data);
    };

    $logoFanfareUri = $toDataUri($logoFanfare);
    $logoEgliseUri = $toDataUri($logoEglise);
    $logoTrompetteUri = $toDataUri($logoTrompette);
    $stamp    = file_exists($logoDir . '/tampon.png') ? $logoDir . '/tampon.png' : null;
    $stampUri = $toDataUri($stamp, 120);

    // Dimensions de la photo : le cadre s'adapte au ratio de la photo (pas de déformation, pas de vide)
    $maxFrameW = 29; $maxFrameH = 39; // mm (cadre max)
    $frameW = $maxFrameW; $frameH = $maxFrameH;
    $imgW = $frameW; $imgH = $frameH;
    if ($member->photo_url && preg_match('#^data:image/[^;]+;base64,(.+)$#', $member->photo_url, $pm)) {
        $bin = base64_decode($pm[1]);
        $info = @getimagesizefromstring($bin);
        if ($info && $info[0] > 0 && $info[1] > 0) {
            $ratio = $info[1] / $info[0]; // hauteur / largeur
            // Largeur max 29mm, hauteur max 39mm, ratio conservé
            $frameW = $maxFrameW;
            $frameH = $frameW * $ratio;
            if ($frameH > $maxFrameH) {
                $frameH = $maxFrameH;
                $frameW = $frameH / $ratio;
            }
            $imgW = $frameW;
            $imgH = $frameH;
        }
    }
@endphp

<div class="card" @if(!empty($lastCard)) style="page-break-after: auto;" @endif>

    {{-- ===== LOGOS ===== --}}
    <table class="logos">
        <tr>
            <td>
                @if($logoFanfareUri)
                    <img src="{{ $logoFanfareUri }}" class="logo-img" alt="">
                @else
                    <div class="logo-placeholder">LOGO<br>FCDTA</div>
                @endif
            </td>
            <td>
                @if($logoEgliseUri)
                    <img src="{{ $logoEgliseUri }}" class="logo-img" alt="">
                @else
                    <div class="logo-placeholder">LOGO<br>ÉGLISE</div>
                @endif
            </td>
            <td>
                @if($logoTrompetteUri)
                    <img src="{{ $logoTrompetteUri }}" class="logo-img" alt="">
                @else
                    <div class="logo-placeholder">INSTRUMENT</div>
                @endif
            </td>
        </tr>
    </table>

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
                <div class="photo" style="width: {{ $frameW }}mm; height: {{ $frameH }}mm;">
                    @if($member->photo_url)
                        <img src="{{ $member->photo_url }}" style="width: {{ $imgW }}mm; height: {{ $imgH }}mm;" alt="">
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
                @if($stampUri)
                    <img src="{{ $stampUri }}" class="stamp-img" alt="">
                @else
                    <div class="stamp-placeholder">TAMPON</div>
                @endif
                <div class="signature-name">Marius ADOLIWOSSI</div>
            </td>
        </tr>
    </table>

</div>
