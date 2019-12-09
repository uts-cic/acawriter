<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AcaWriter | University of Technology Sydney</title>

    <link href="https://unpkg.com/nprogress@0.2.0/nprogress.css" rel="stylesheet" />
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

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4" data-ga-category="Navbar">
        <!-- Branding Image -->
        <a class="navbar-brand" href="{{ url('/home') }}" data-ga-label="AcaWriter">
            <svg width="70" height="31" viewBox="0 0 70 31" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title>UTS</title><defs><path id="a" d="M.01.118v23.556h69.97V.118H.01z"></path></defs><g fill="none" fill-rule="evenodd"><path d="M.01.118v23.556h69.97V.118H.01z"></path><path class="logo__fill" fill="#FFFFFF" d="M40.667 10.463h4.305v16.312h4.398V10.463h4.305V6.554H40.667v3.909"></path><g transform="translate(0 6.128)"><mask id="b" fill="#fff"><use xlink:href="#a"></use></mask><path class="logo__fill" d="M32.389 14.4c0 1.63-.954 2.646-2.46 2.646-1.538 0-2.46-1.016-2.46-2.647V.426H23.07v14.035c0 4 2.767 6.494 6.858 6.494 4.09 0 6.857-2.493 6.857-6.494V.426H32.39V14.4m32.67-5.756l-2.522-.954c-1.384-.524-1.63-1.416-1.63-2.001 0-1.108.8-1.939 2.214-1.939 1.384 0 2.306.923 2.306 2.37v.554h4.121v-.462c0-3.755-2.583-6.094-6.427-6.094-4.029 0-6.366 2.77-6.366 5.848 0 2.955 1.538 4.71 4.46 5.817l2.828 1.077c1.261.493 1.753 1.17 1.753 2.186 0 1.415-.922 2.246-2.583 2.246-1.66 0-2.675-.77-2.675-2.4v-.739h-4.152v.708c0 3.755 2.89 6.094 6.827 6.094 4.305 0 6.766-2.616 6.766-6.34 0-3.725-2.4-5.017-4.92-5.971M3.821 8.76l-1.91-1.912L0 8.76l1.91 1.912L3.822 8.76m4.277.456l1.455 1.456 1.91-1.912-1.946-1.948a2.006 2.006 0 0 0-2.838 0L0 13.497l1.91 1.912L8.1 9.216m-.456 8.106l-1.911-1.913-1.455 1.457-1.455-1.457L.91 17.322l1.947 1.949a2.005 2.005 0 0 0 2.838 0l1.948-1.95m.456 3.369l-1.455-1.456-1.911 1.913 1.947 1.948a2.006 2.006 0 0 0 2.838 0l1.947-1.948-1.91-1.913-1.456 1.456" fill="#FFFFFF" mask="url(#b)"></path></g><path class="logo__fill" d="M11.92 22.994l-1.455-1.457-1.91 1.912 1.947 1.95a2.005 2.005 0 0 0 2.838 0l1.946-1.949-1.91-1.913-1.456 1.457m.456-3.37l1.912 1.913 1.91-1.912-1.911-1.913-1.91 1.912m-4.278-.454l-1.455-1.457-1.91 1.912 1.946 1.948a2.006 2.006 0 0 0 2.838 0l6.68-6.685-1.911-1.913-6.188 6.194m2.548-16.517L8.1.1 5.551 2.65 8.1 5.202l2.548-2.55" fill="#FFFFFF"></path><path class="logo__fill" d="M4.92 8.457h1.827v3.62a2.659 2.659 0 0 1 2.703 0v-3.62h1.828l1.197 1.198 2.548-2.55-2.548-2.55-1.197 1.198H4.92L3.723 4.555l-2.548 2.55 2.548 2.55L4.92 8.457" fill="#FFFFFF"></path></g></svg>
            AcaWriter
        </a>

        @if (Auth::check())
            <!-- Collapsed Hamburger -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation" data-ga-label="Mobile menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav iconic">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}" data-ga-label="Home"><i class="fa fa-home"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="/page/about"data-ga-label="About"><i class="fa fa-info-circle"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="/page/contact" data-ga-label="Contact"><i class="fa fa-envelope"></i></a></li>
                </ul>


                <ul class="nav navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/home')}}" data-ga-label="My documents">My documents</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{url('example')}}" data-ga-label="Examples">Examples</a></li>

                    @if (Auth::user()->hasRole('staff'))
                        <li class="nav-item"><a class="nav-link" href="{{ url('/assignment')}}" data-ga-label="Manage assignments">Manage assignments</a></li>
                    @endif

                    @if (Auth::user()->hasRole('admin'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Admin
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ url('/admin/users')}}">Manage users</a>
                                <a class="dropdown-item" href="/admin/reports">Reports</a>
                            </div>
                        </li>
                    @endif
                </ul>

                <ul class="nav navbar-nav pull-right">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="user-menu" data-toggle="dropdown" role="button" aria-expanded="false" data-ga-label="User">
                            {{ Auth::user()->name }} <span class="fa fa-caret"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="user-menu">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                data-ga-label="Logout"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        @endif
    </nav>

    @yield('content')

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.slim.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.4.4/polyfill.min.js"></script>

    <script src="{{ asset('js/app.js') }}{{ env('CACHE_BUST', '') ? '?' . env('CACHE_BUST', '') : '' }}"></script>

    @include('layouts.footer')
</body>
</html>
