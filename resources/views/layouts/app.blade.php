<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AcaWriter | University of Technology Sydney</title>

    <link href="https://unpkg.com/nprogress@0.2.0/nprogress.css" rel="stylesheet">
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}{{ env('CACHE_BUST', '') ? '?' . env('CACHE_BUST', '') : '' }}" rel="stylesheet">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-77076723-6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        @if (Auth::check())
            var ROLE ='{{ Auth::user()->getRole() }}';
            gtag('set', {'user_id': '{{ Auth::id() }}'});
        @endif

        gtag('config', 'UA-77076723-6', {
            'custom_map': {'dimension1': 'role'}
        });
    </script>
</head>
<body data-ga-category="Body">

    @include('layouts.header')

    @yield('content')

    @include('layouts.footer')

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.slim.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.4.4/polyfill.min.js"></script>
    <script src="{{ asset('js/app.js') }}{{ env('CACHE_BUST', '') ? '?' . env('CACHE_BUST', '') : '' }}"></script>
</body>
</html>
