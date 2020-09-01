<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="wrapper" id="app">
        <nav id="nav" style="">
            <div class="navbar">
                <a class="navbar-brand" href="/home">Cardo</a>
            </div>
            <ul class="col">
                <a href="/course"><li>Kurse</li></a>
                <a href="/client"><li>Kunden</li></a>
                <a href="/supplier"><li>Lieferanten</li></a>
                <a href="/item"><li>Produkte</li></a>
                <a href="/staff"><li>Personal</li></a>
                <a href="" data-toggle="collapse" data-target="#nav-buchhaltung"><li class="d-flex align-items-center justify-content-between line-height-base">Buchhaltung<i class="fas fa-caret-right"></i></li></a>
                <ul id="nav-buchhaltung" class="collapse">
                    <a href="/bookkeeping/revenue"><li>Einnahmen</li></a>
                    <a href="/bookkeeping/expense"><li>Ausgaben</li></a>
                    <a href="/bookkeeping/invoice"><li>Rechnungen</li></a>
                </ul>
            </ul>
            <div class="px-3 text-white text-center"><p><a href="" target="_blank">Link</a></p></div>
            <div class="bg-secondary text-white p-2 d-flex justify-content-around">
                <a class="text-white" href="/impressum">Impressum</a>
            </div>
        </nav>

        <div id="content-container">

            <nav class="navbar navbar-expand navbar-light bg-light sticky-top shadow-sm">
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <span class="navbar-text" id="menu-toggle">
                        <i class="fas fa-bars pointer"></i>
                    </span>
                    <form class="form-inline col my-2 my-lg-0">
                        <!-- <input class="form-control mr-sm-2 col" type="search" placeholder="Search" aria-label="Search"> -->
                        <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button> -->
                    </form>
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-xs-none d-sm-none d-md-inline d-lg-inline d-xl-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/user/settings">Einstellungen</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div id="content" class="container-fluid mt-3" style="height: 100vh;">
                @yield('content')
            </div>


        </div>

        <flash-message :initial-message="{{ session()->has('status') ? json_encode(session('status')) : 'null' }}"></flash-message>

    </div>
</body>
</html>
