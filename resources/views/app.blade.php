<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-name" content="{{ config('app.name') }}">
    <meta name="description" content="{{ config('app.description', 'GrowithBI - Program magang Bank Indonesia Provinsi Lampung untuk mahasiswa dan fresh graduate yang tertarik pada ekonomi, keuangan, dan transformasi digital.') }}">

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
    <link rel="icon" type="image/webp" href="{{ asset('logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.webp') }}">

    <!-- Preload LCP images so the browser fetches them at highest priority based on route -->
    @if(request()->routeIs('login', 'register', 'password.*'))
        <link rel="preload" as="image" href="{{ asset('mascot_small.webp') }}" fetchpriority="high">
    @elseif(request()->routeIs('home') || request()->is('/'))
        <link rel="preload" as="image" href="{{ asset('hero.webp') }}" fetchpriority="high">
    @endif

    <!-- Early connection to font CDN -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">

    <!-- Fonts — non-blocking: preload then swap to stylesheet via onload -->
    <link rel="preload"
          href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    </noscript>

    <!--
        Inline critical CSS — paints the above-the-fold shell (body bg, navbar
        background, hero layout) before the async main stylesheet arrives.
        Keep this in sync with the navbar + hero visual styles.
    -->
    <style>
        /* Reset & base */
        *,::before,::after{box-sizing:border-box}
        html{line-height:1.5;-webkit-text-size-adjust:100%;scroll-behavior:smooth}
        body{margin:0;font-family:ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;background-color:#f8fafc;color:#0f172a}

        /* Prevent layout shift from the Inertia mount div */
        #app{min-height:100vh}


    </style>

    <!-- Vite assets: JS is module (non-blocking by spec); CSS is deferred below -->
    @routes
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>