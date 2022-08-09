<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-16x16.png') }}" sizes="16x16">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- App Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div id="app">
        <div class="bg-white py-3">
            <div class="container">
                <img src="{{ asset('img/mmi_logo_outline_retina.png') }}" style="width: 280px;">
            </div>
        </div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if (Request::is('admin*'))
                                <li class="nav-item"><router-link class="nav-link" to="/admin">{{ __('Dashboard') }}</router-link></li>
                                <li class="nav-item"><router-link class="nav-link" to="/admin/participants">{{ __('Participants') }}</router-link></li>
                                <li class="nav-item"><router-link class="nav-link" to="/admin/studies">{{ __('Studies') }}</router-link></li>
                                <li class="nav-item"><router-link class="nav-link" to="/admin/mailings">{{ __('Mailings') }}</router-link></li>
                            @else
                                <a class="nav-link" href="{{ url('/admin') }}">{{ __('Dashboard') }}</a>
                                <a class="nav-link" href="{{ url('/admin/participants') }}">{{ __('Participants') }}</a>
                                <a class="nav-link" href="{{ url('/admin/studies') }}">{{ __('Studies') }}</a>
                                <a class="nav-link" href="{{ url('/admin/mailings') }}">{{ __('Mailings') }}</a>
                            @endif
                        @endauth
                    </ul>
                </div>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('admin.login'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.login') }}">{{ __('Login') }}</a></li>
                        @endif
                        @if (Route::has('admin.register'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.register') }}">{{ __('Register') }}</a></li>
                        @endif
                    @else
                        @can ('read', \App\Models\User::class)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('Admin') }}</a>
                                <div class="dropdown-menu">
                                    @if (Request::is('admin*'))
                                        <router-link class="dropdown-item" to="/admin/users">{{ __('Users') }}</router-link>
                                        <div class="dropdown-divider"></div>
                                        <router-link class="dropdown-item" to="/admin/settings">{{ __('Settings') }}</router-link>
                                        <div class="dropdown-divider"></div>
                                        <router-link class="dropdown-item" to="/admin/logs">{{ __('Logs') }}</router-link>
                                    @else
                                        <a class="dropdown-item" href="{{ url('/admin/users') }}">{{ __('Users') }}</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ url('/admin/settings') }}">{{ __('Settings') }}</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ url('/admin/logs') }}">{{ __('Logs') }}</a>
                                    @endif
                                </div>
                            </li>
                        @endcan
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</a>
                            <div class="dropdown-menu">
                                @if (Request::is('admin*'))
                                    <router-link class="dropdown-item" to="/admin/profile">{{ __('Profile') }}</router-link>
                                @else
                                    <a class="dropdown-item" href="{{ url('/admin/profile') }}">{{ __('Profile') }}</a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <main class="py-4">
            <b-overlay v-bind:show="$app.overlay" rounded="sm">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12 bg-white p-3 rounded">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </b-overlay>
        </main>
    </div>

    <!-- App Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
