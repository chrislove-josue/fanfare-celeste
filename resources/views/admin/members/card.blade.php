<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: DejaVu Sans, sans-serif; }

@page {
    margin: 0;
    size: 148mm 105mm landscape;
}

.badge {
    width: 148mm;
    border: 2px solid #c9a227;
    border-radius: 8px;
    overflow: hidden;
    page-break-inside: avoid;
}

.inner {
    width: 100%;
    border-collapse: collapse;
}

.col-stripe {
    width: 7px;
    background: #c9a227;
    padding: 0;
}

.col-left {
    width: 86px;
    background: #101f55;
    padding: 12px 7px;
    border-right: 1px solid rgba(201,162,39,0.25);
    vertical-align: top;
    text-align: center;
}

.col-right {
    padding: 11px 13px;
    vertical-align: top;
    background: #0d1b4b;
}

.fanfare-label {
    font-size: 7.5px;
    color: #c9a227;
    font-weight: bold;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    line-height: 1.5;
    margin-bottom: 10px;
}

.photo-circle {
    width: 68px;
    height: 68px;
    border-radius: 34px;
    border: 2.5px solid #c9a227;
    overflow: hidden;
    background-color: #1e2d6b;
    margin: 0 auto 8px;
}

.official-label {
    font-size: 6.5px;
    color: #c9a227;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    border: 1px solid #c9a227;
    border-radius: 3px;
    padding: 2px 5px;
    display: inline-block;
    margin-top: 8px;
}

.badge-title {
    font-size: 7px;
    color: #c9a227;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 3px;
}

.member-name {
    font-size: 18px;
    font-weight: bold;
    color: #ffffff;
    line-height: 1.1;
    margin-bottom: 2px;
}

.member-number {
    font-size: 9px;
    color: #8fa8d8;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

.status-pill {
    font-size: 7.5px;
    color: #6fe0a0;
    background: #1a4a2e;
    padding: 2px 9px;
    border-radius: 10px;
    letter-spacing: 0.8px;
    display: inline-block;
    margin-bottom: 9px;
}

.info-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 9px;
}

.info-table td {
    width: 50%;
    vertical-align: top;
    padding: 0 3px 4px 0;
}

.info-table td:nth-child(2) {
    padding-left: 3px;
    padding-right: 0;
}

.info-box {
    background: rgba(255,255,255,0.06);
    border: 0.5px solid rgba(255,255,255,0.1);
    border-radius: 5px;
    padding: 5px 7px;
}

.info-label {
    font-size: 6.5px;
    color: #8fa8d8;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    margin-bottom: 2px;
}

.info-value {
    font-size: 10px;
    color: #ffffff;
    font-weight: bold;
}

.bottom-table {
    width: 100%;
    border-collapse: collapse;
}

.phone {
    font-size: 9.5px;
    color: #c9a227;
    font-weight: bold;
}

.date {
    font-size: 6.5px;
    color: #4a5f8a;
    margin-top: 2px;
}

.qr-wrap {
    width: 54px;
    height: 54px;
    background: rgba(255,255,255,0.95);
    border-radius: 5px;
    padding: 2px;
}

.qr-wrap img {
    width: 50px;
    height: 50px;
}
</style>
</head>
<body>
<div class="badge">
<table class="inner">
<tr>

    <td class="col-stripe"></td>

    <td class="col-left">
        <div class="fanfare-label">FANFARE<br>CÉLESTE</div>

        @if($member->photo_url)
    <div class="photo-circle">
        <img src="{{ $member->photo_url }}"
             width="68"
             height="68"
             style="display:block;width:68px;height:68px;" />
    </div>
@else
    <div class="photo-circle">
        <table style="width:68px;height:68px;"><tr><td style="text-align:center;vertical-align:middle;">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#c9a227" stroke-width="1.5">
                <circle cx="12" cy="8" r="4"/>
                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
            </svg>
        </td></tr></table>
    </div>
@endif

        <div class="official-label">MEMBRE<br>OFFICIEL</div>
    </td>

    <td class="col-right">
        <div class="badge-title">CARTE DE MEMBRE</div>
        <div class="member-name">{{ $member->full_name }}</div>
        <div class="member-number">{{ $member->member_number }}</div>
        <div class="status-pill">&#9679; {{ ucfirst($member->status) }}</div>

        <table class="info-table">
            <tr>
                <td>
                    <div class="info-box">
                        <div class="info-label">Section</div>
                        <div class="info-value">{{ $member->section ?? '—' }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-box">
                        <div class="info-label">Instrument</div>
                        <div class="info-value">{{ $member->instrument ?? '—' }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-box">
                        <div class="info-label">Né(e) le</div>
                        <div class="info-value">
                            {{ $member->birth_date ? $member->birth_date->format('d/m/Y') : '—' }}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="info-box">
                        <div class="info-label">Membre depuis</div>
                        <div class="info-value">
                            {{ $member->entry_date ? $member->entry_date->format('Y') : '—' }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <table class="bottom-table">
            <tr>
                <td style="vertical-align:middle;">
                    <div class="phone">{{ $member->phone ?? '—' }}</div>
                    <div class="date">Générée le {{ now()->format('d/m/Y') }}</div>
                </td>
                <td style="text-align:right;vertical-align:middle;">
                    <div class="qr-wrap">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR" />
                    </div>
                </td>
            </tr>
        </table>
    </td>

</tr>
</table>
</div>
</body>
</html>