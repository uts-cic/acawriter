<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="row">
        <div class="offset-1 col-md-2"><img src="/images/uts_logo.png" alt="UTS"/></div>
        <div class="col-md-9"><br /><h1>Academic Writing Analytics</h1></div>
    </div>

        <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <!-- Collapsed Hamburger -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav">
                        &nbsp;
                </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav mx-auto">

                        <!-- Authentication Links -->

                        @if (Auth::guest())
                            <!-- <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li> -->
                            <!-- <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li> -->
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ url('/home')}}">My Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{url('example')}}">Example Texts</a></li>
                            <!-- <li class="nav-item"><a class="nav-link" href="/analyse">Analyse</a></li>
                            <li class="nav-item"><a class="nav-link" href="/assignment">Assignment</a></li> -->

                        @endif
                    </ul>
                <ul class="nav navbar-nav pull-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                    <!-- <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li> -->
                    <!-- <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li> -->
                    @else
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                    @endif
                </ul>

                </div>

        </nav>


        @yield('content')

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @include('layouts.footer')
</body>
</html>
