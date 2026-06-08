<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-name" content="{{ config('app.name') }}">

    @if(config('app.env') === 'production')
    <meta http-equiv="Content-Security-Policy"
          content="
            default-src 'self';
            script-src 'self' 'unsafe-inline' 'unsafe-eval';
            style-src 'self' 'unsafe-inline' https://fonts.bunny.net;
            font-src 'self' https://fonts.bunny.net;
            img-src 'self' data: blob:;
            connect-src 'self' ws: wss:;
          ">
    @endif

    <title inertia>{{ config('app.name', 'GrowithBI') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('logo_web.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo_web.webp') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="dns-prefetch" href="https://fonts.bunny.net">

    <link rel="preload"
          href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
          as="style">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
          rel="stylesheet">

    <!-- Hero Image Preload (sesuaikan path jika berbeda) -->
    <link rel="preload"
          as="image"
          href="{{ asset('hero.webp') }}"
          fetchpriority="high">

    <!-- Laravel -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>