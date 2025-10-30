<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('email.2fa_code.subject', ['app_name' => config('app.name')]) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .code-box {
            background: #f8f9fa;
            border: 2px solid #007bff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 5px;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🏨 {{ config('app.name') }}</h1>
        </div>

        <p>{{ __('email.2fa_code.greeting', ['name' => $user->full_name]) }}</p>

        <p>{{ __('email.2fa_code.intro') }}</p>

        <div class="code-box">
            <p>{{ __('email.2fa_code.your_code') }}</p>
            <h2 style="margin: 20px 0; color: #1a237e; font-size: 28px;">{{ $code }}</h2>



            <p>{{ __('email.2fa_code.expires') }}</p>
        </div>

        <div class="footer">
            <p>{{ __('email.2fa_code.ignore') }}</p>
            <p>{{ __('email.2fa_code.regards') }}<br>{{ __('email.2fa_code.team', ['app_name' => config('app.name')]) }}
            </p>
        </div>
    </div>
</body>

</html>