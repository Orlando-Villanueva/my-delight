<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - Delight</title>
    <style>
        /* Reset styles */
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
        }

        /* Base styles */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            min-width: 100%;
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .logo {
            color: #ffffff;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            text-decoration: none;
        }

        .tagline {
            color: #e0e7ff;
            font-size: 16px;
            margin: 8px 0 0 0;
        }

        /* Content */
        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 20px 0;
        }

        .message {
            font-size: 16px;
            line-height: 1.6;
            color: #4b5563;
            margin: 0 0 30px 0;
        }

        /* Button */
        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Security notice */
        .security-notice {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }

        .security-notice-title {
            font-weight: 600;
            color: #92400e;
            margin: 0 0 8px 0;
        }

        .security-notice-text {
            font-size: 14px;
            color: #b45309;
            margin: 0;
        }

        /* Footer */
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer-text {
            font-size: 14px;
            color: #6b7280;
            margin: 0 0 10px 0;
        }

        .footer-link {
            color: #667eea;
            text-decoration: none;
        }

        .footer-link:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            
            .header, .content, .footer {
                padding: 20px !important;
            }
            
            .greeting {
                font-size: 20px;
            }
            
            .button {
                padding: 14px 24px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1 class="logo">Delight</h1>
            <p class="tagline">Find delight in your daily Bible reading</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 class="greeting">Reset Your Password</h2>
            
            <p class="message">
                Hello! We received a request to reset your password for your Delight account. 
                Click the button below to create a new password and get back to building your Bible reading habit.
            </p>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="button">Reset My Password</a>
            </div>

            <p class="message">
                This password reset link will expire in {{ config('auth.passwords.users.expire', 60) }} minutes for your security.
            </p>

            <div class="security-notice">
                <p class="security-notice-title">🔒 Security Notice</p>
                <p class="security-notice-text">
                    If you didn't request this password reset, you can safely ignore this email. 
                    Your password will remain unchanged.
                </p>
            </div>

            <p class="message">
                If you're having trouble clicking the button, copy and paste the URL below into your web browser:
                <br><br>
                <a href="{{ $resetUrl }}" style="color: #667eea; word-break: break-all;">{{ $resetUrl }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                This email was sent from Delight, your Bible reading habit tracker.
            </p>
            <p class="footer-text">
                Questions? Contact us at 
                <a href="mailto:{{ config('mail.from.address') }}" class="footer-link">{{ config('mail.from.address') }}</a>
            </p>
        </div>
    </div>
</body>
</html>