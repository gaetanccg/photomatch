<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') - Trouve Ton Photographe</title>
    <style>
        /* Reset */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        /* Base styles */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 30px 40px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .email-header .tagline {
            color: rgba(255, 255, 255, 0.9);
            margin: 8px 0 0 0;
            font-size: 14px;
            font-weight: 400;
        }

        /* Content */
        .email-content {
            padding: 40px;
        }
        .email-content h2 {
            color: #111827;
            font-size: 22px;
            font-weight: 600;
            margin: 0 0 20px 0;
        }
        .email-content p {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 16px 0;
        }

        /* Info box */
        .info-box {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 8px 0;
            vertical-align: top;
        }
        .info-label {
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 120px;
        }
        .info-value {
            color: #111827;
            font-size: 15px;
            font-weight: 500;
        }

        /* Message box */
        .message-box {
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
            border-radius: 0 8px 8px 0;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .message-box p {
            color: #065f46;
            font-size: 15px;
            font-style: italic;
            margin: 0;
        }

        /* CTA Button */
        .cta-container {
            text-align: center;
            margin: 32px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.3);
        }
        .cta-button:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        /* Secondary button */
        .cta-button-secondary {
            background: #ffffff;
            color: #10b981 !important;
            border: 2px solid #10b981;
            box-shadow: none;
        }

        /* Alert styles */
        .alert-warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            border-radius: 0 8px 8px 0;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .alert-warning p {
            color: #92400e;
            margin: 0;
        }

        /* Footer */
        .email-footer {
            background-color: #f9fafb;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
            margin: 0 0 12px 0;
        }
        .email-footer a {
            color: #10b981;
            text-decoration: none;
        }
        .email-footer a:hover {
            text-decoration: underline;
        }
        .footer-links {
            margin-top: 16px;
        }
        .footer-links a {
            color: #9ca3af;
            font-size: 12px;
            margin: 0 8px;
        }

        /* Responsive */
        @media only screen and (max-width: 620px) {
            .email-container {
                width: 100% !important;
            }
            .email-header {
                padding: 24px 20px !important;
            }
            .email-content {
                padding: 24px 20px !important;
            }
            .email-footer {
                padding: 24px 20px !important;
            }
            .info-label {
                display: block;
                width: 100%;
                margin-bottom: 4px;
            }
            .info-value {
                display: block;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" class="email-container" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            <h1>Trouve Ton Photographe</h1>
                            <p class="tagline">La plateforme des photographes professionnels</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="email-content">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <p>Cet email a été envoyé par Trouve Ton Photographe.</p>
                            <p>Si vous avez des questions, contactez-nous à <a href="mailto:contact@trouvetonphotographe.fr">contact@trouvetonphotographe.fr</a></p>
                            <div class="footer-links">
                                <a href="{{ config('app.url') }}">Accueil</a>
                                <a href="{{ config('app.url') }}/legal/privacy">Confidentialité</a>
                                <a href="{{ config('app.url') }}/legal/terms">CGU</a>
                            </div>
                            <p style="margin-top: 20px; color: #9ca3af; font-size: 11px;">
                                &copy; {{ date('Y') }} Trouve Ton Photographe. Tous droits réservés.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
