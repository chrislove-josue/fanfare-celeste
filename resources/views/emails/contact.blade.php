<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.8;
            font-size: 14px;
        }
        .content {
            padding: 30px 25px;
        }
        .content h2 {
            color: #1a237e;
            margin-top: 0;
            font-size: 20px;
        }
        .message-box {
            background: #f8f9fa;
            border-left: 4px solid #3946B2;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .message-box p {
            margin: 0;
            line-height: 1.6;
        }
        .info-item {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            width: 100px;
            color: #555;
        }
        .info-value {
            flex: 1;
            color: #333;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 25px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
        }
        .badge {
            display: inline-block;
            background: #3946B2;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            color: #3946B2;
            text-decoration: none;
            margin: 0 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎵 Fanfare Céleste</h1>
            <p>La Trompette de Dieu de l'Atlantique</p>
            <span class="badge">Nouveau message</span>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>📩 Nouveau message de contact</h2>
            <p style="color: #666; margin-bottom: 20px;">
                Vous avez reçu un nouveau message depuis le formulaire de contact du site.
            </p>

            <!-- Informations de l'expéditeur -->
            <div style="background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                <h4 style="color: #1a237e; margin: 0 0 10px;">👤 Informations de l'expéditeur</h4>
                <div class="info-item">
                    <span class="info-label">Nom :</span>
                    <span class="info-value">{{ $name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email :</span>
                    <span class="info-value">
                        <a href="mailto:{{ $email }}" style="color: #3946B2;">{{ $email }}</a>
                    </span>
                </div>
                @if($phone)
                <div class="info-item">
                    <span class="info-label">Téléphone :</span>
                    <span class="info-value">
                        <a href="tel:{{ $phone }}" style="color: #3946B2;">{{ $phone }}</a>
                    </span>
                </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Sujet :</span>
                    <span class="info-value" style="font-weight: bold;">{{ $subject }}</span>
                </div>
            </div>

            <!-- Message -->
            <div style="margin-bottom: 20px;">
                <h4 style="color: #1a237e; margin: 0 0 10px;">📝 Message :</h4>
                <div class="message-box">
                    <p>{{ $message }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div style="background: #e8f5e9; border-radius: 8px; padding: 15px; text-align: center;">
                <p style="margin: 0; color: #2e7d32;">
                    <strong>🔔 Action recommandée :</strong>
                </p>
                <div style="margin-top: 10px;">
                    <a href="mailto:{{ $email }}?subject=RE: {{ $subject }}" 
                       style="display: inline-block; background: #3946B2; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;">
                        <i class="fas fa-reply"></i> Répondre
                    </a>
                    <a href="tel:{{ $phone }}" 
                       style="display: inline-block; background: #40A81B; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;">
                        <i class="fas fa-phone"></i> Appeler
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0; font-weight: bold; color: #1a237e;">Fanfare Céleste</p>
            <p style="margin: 5px 0;">Cotonou, Bénin</p>
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Instagram</a> |
                <a href="#">YouTube</a>
            </div>
            <p style="margin: 10px 0 0;">
                Cet email a été envoyé automatiquement depuis le site de la Fanfare Céleste.
            </p>
        </div>
    </div>
</body>
</html>