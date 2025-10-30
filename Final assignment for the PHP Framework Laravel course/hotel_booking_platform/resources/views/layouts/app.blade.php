<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="{{ url('/') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (!request()->header('X-Postman-Interceptor-Id'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        .aspect-\[4\/3\] {
            aspect-ratio: 4 / 3;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .hotel-image {
            object-fit: cover;
            object-position: center;
        }

        .group:hover .group-hover\:scale-105 {
            transform: scale(1.05);
        }

        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
        }
    </style>
</head>

<body class="font-sans antialiased" x-data>
    <script> 
        document.addEventListener('DOMContentLoaded', function () {
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('opacity-0');
                        img.classList.add('opacity-100');
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        });
    </script>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow mt-20">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Flash Messages -->
        <div class="mt-20">
            <x-flash-messages />
        </div>

        <!-- Page Content -->
        <main class="pt-20">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Автоматическое определение часового пояса -->
    <script>
        // Определяем часовой пояс пользователя и сохраняем в cookie
        if (!document.cookie.includes('timezone=')) {
            const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            document.cookie = `timezone=${timezone}; path=/; max-age=31536000`; // 1 год

            // Отправляем часовой пояс на сервер для авторизованных пользователей
            @auth
                fetch('/api/update-timezone', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ timezone: timezone })
                }).catch(console.error);
            @endauth
        }
    </script>
</body>

</html>