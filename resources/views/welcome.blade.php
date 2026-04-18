<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Smart Energy Monitoring System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-black text-white min-h-screen">

    <!-- Login / Register -->
    @if (Route::has('login'))
        <div class="fixed top-0 right-0 p-8 z-50 flex gap-6">

            @auth
                <a href="{{ url('/dashboard') }}" 
                   class="text-xl font-bold bg-gray-800 px-5 py-2 rounded-lg hover:bg-gray-700 transition">
                   Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="text-xl font-bold bg-blue-600 px-5 py-2 rounded-lg hover:bg-blue-500 transition">
                   Log In
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" 
                       class="text-xl font-bold bg-green-600 px-5 py-2 rounded-lg hover:bg-green-500 transition">
                       Register
                    </a>
                @endif
            @endauth

        </div>
    @endif


    <!-- Main Content -->
    <div class="flex items-center justify-center min-h-screen">

        <div class="bg-gray-900 text-white shadow-xl rounded-lg p-10 text-center max-w-xl border border-gray-700">

            <h1 class="text-4xl font-bold mb-6">
                Smart Energy Monitoring System
            </h1>

            <p class="text-gray-300 mb-8 text-lg">
                Monitor electricity usage, analyze consumption patterns, and improve energy efficiency with our smart monitoring platform.
            </p>

            <!-- Project Image -->
            <div class="flex justify-center">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Smart Energy Monitoring"
                     class="w-96 rounded-lg shadow-lg">
            </div>

        </div>

    </div>

</body>
</html>