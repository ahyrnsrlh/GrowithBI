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
    <link rel="icon" type="image/webp" href="{{ asset('logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.webp') }}">

    <!-- Preload LCP hero image so the browser fetches it at highest priority -->
    <link rel="preload" as="image" href="{{ asset('hero.webp') }}" fetchpriority="high">

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

        /* Navbar shell — matches FloatingNavbar gradient so nav is visible immediately */
        body::before{
            content:'';
            position:fixed;top:0;left:0;right:0;
            height:72px;
            background:linear-gradient(to right,#1e40af,#1d4ed8,#3730a3);
            z-index:40;
        }

        /* Hero section background so the page isn't blank white during JS parse */
        body::after{
            content:'';
            position:fixed;top:0;left:0;right:0;bottom:0;
            background:linear-gradient(135deg,#f8fafc 0%,#eff6ff 50%,#eef2ff 100%);
            z-index:-1;
        }
    </style>

    <!-- Vite assets: JS is module (non-blocking by spec); CSS is deferred below -->
    @routes
    @vite('resources/js/app.js')
    @inertiaHead

    <!--
        Defer the Vite-compiled app.css so it is NOT render-blocking.
        Pattern: preload as="style" + onload swap (same as the font trick above).
        The manifest key matches the Vite output for resources/css/app.css.
    -->
    @php
        $manifest = null;
        $manifestPath = public_path('build/.vite/manifest.json');
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
    @endphp
    @if($cssFile)
    <link rel="preload"
          href="{{ asset('build/' . $cssFile) }}"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    </noscript>
    @endif
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>