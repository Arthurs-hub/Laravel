<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('email.welcome.title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            background-color: #f8fafc;
            color: #3d4852;
            line-height: 1.5;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e8e5ef;
            border-radius: 2px;
        }
        h2 {
            color: #2d3748;
            font-size: 1.5rem;
        }
        p, li {
            margin-bottom: 1rem;
        }
        ul {
            padding-left: 20px;
            list-style: none;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.875rem;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>{{ __('email.welcome.greeting', ['name' => $user->full_name]) }}</h2>
        <p>{{ __('email.welcome.intro') }}</p>

        <p>{{ __('email.welcome.you_can_now') }}</p>
        <ul>
            <li>{{ __('email.welcome.feature1') }}</li>
            <li>{{ __('email.welcome.feature2') }}</li>
            <li>{{ __('email.welcome.feature3') }}</li>
            <li>{{ __('email.welcome.feature4') }}</li>
        </ul>

        <p>{{ __('email.welcome.start_browsing') }}</p>

        <div class="footer">
            <p>{{ __('email.welcome.support') }}</p>
            <p>{{ __('email.welcome.regards') }}<br>{{ __('email.welcome.team', ['app_name' => config('app.name')]) }}</p>
        </div>
    </div>
</body>
</html>
