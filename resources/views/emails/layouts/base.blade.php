<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Delight - Find delight in your daily Bible reading')</title>
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
            background-color: #f9fafb;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Header */
        .header {
            background: #3366CC;
            padding: 32px 24px;
            text-align: center;
        }

        .logo {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-decoration: none;
            letter-spacing: -0.025em;
        }

        .tagline {
            color: #dbeafe;
            font-size: 14px;
            margin: 8px 0 0 0;
            font-weight: 400;
        }

        /* Content */
        .content {
            padding: 32px 24px;
        }

        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 16px 0;
            letter-spacing: -0.025em;
        }

        .message {
            font-size: 16px;
            line-height: 1.625;
            color: #4b5563;
            margin: 0 0 24px 0;
        }

        /* Button */
        .button-container {
            text-align: center;
            margin: 32px 0;
        }

        .button {
            display: inline-block;
            background: #3366CC;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            border: 1px solid #3366CC;
        }

        .button:hover {
            background: #2c5aa0;
            border-color: #2c5aa0;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(51, 102, 204, 0.25);
        }

        /* Notice boxes */
        .notice {
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
            border-left: 4px solid;
        }

        .notice-security {
            background-color: #fffbeb;
            border-left-color: #f59e0b;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
        }

        .notice-info {
            background-color: #eff6ff;
            border-left-color: #3366CC;
            border: 1px solid #bfdbfe;
            border-left: 4px solid #3366CC;
        }

        .notice-success {
            background-color: #f0fdf4;
            border-left-color: #66CC99;
            border: 1px solid #bbf7d0;
            border-left: 4px solid #66CC99;
        }

        .notice-title {
            font-weight: 600;
            margin: 0 0 8px 0;
            font-size: 14px;
        }

        .notice-security .notice-title {
            color: #92400e;
        }

        .notice-info .notice-title {
            color: #1e40af;
        }

        .notice-success .notice-title {
            color: #166534;
        }

        .notice-text {
            font-size: 14px;
            margin: 0;
            line-height: 1.5;
        }

        .notice-security .notice-text {
            color: #a16207;
        }

        .notice-info .notice-text {
            color: #1d4ed8;
        }

        .notice-success .notice-text {
            color: #15803d;
        }

        /* Footer */
        .footer {
            background-color: #f9fafb;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer-text {
            font-size: 13px;
            color: #6b7280;
            margin: 0 0 8px 0;
            line-height: 1.5;
        }

        .footer-link {
            color: #3366CC;
            text-decoration: none;
        }

        .footer-link:hover {
            color: #2c5aa0;
            text-decoration: underline;
        }

        /* Utility classes */
        .text-center {
            text-align: center;
        }

        .text-small {
            font-size: 13px;
            line-height: 1.5;
        }

        .text-muted {
            color: #6b7280;
        }

        .text-link {
            color: #3366CC;
            text-decoration: none;
        }

        .text-link:hover {
            color: #2c5aa0;
            text-decoration: underline;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mb-8 {
            margin-bottom: 8px;
        }

        .mb-16 {
            margin-bottom: 16px;
        }

        .mb-24 {
            margin-bottom: 24px;
        }

        .mb-32 {
            margin-bottom: 32px;
        }

        /* Card-like content sections */
        .content-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 16px 0;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 24px 0;
            border: none;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }
            
            .header {
                padding: 24px 16px !important;
            }
            
            .content {
                padding: 24px 16px !important;
            }
            
            .footer {
                padding: 20px 16px !important;
            }
            
            .greeting {
                font-size: 20px;
            }
            
            .button {
                padding: 12px 20px;
                font-size: 14px;
                width: auto;
                display: inline-block;
            }
            
            .notice {
                padding: 12px;
                margin: 16px 0;
            }
        }
    </style>
    @stack('styles')
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
            @yield('content')
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
            @yield('footer-extra')
        </div>
    </div>
</body>
</html>