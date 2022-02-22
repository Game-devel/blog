<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title',"JGJ")</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <script src="https://www.wiris.net/demo/editor/editor" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.3/MathJax.js?config=TeX-MML-AM_CHTML" defer></script>
    <!-- Styles -->
    @include ('admin.styles.style')

</head>
<body class="uk-background-muted">
<header>
    <nav class="uk-navbar" uk-navbar="">
        <div class="uk-navbar-left">
            <ul class="uk-navbar-nav  uk-visible@m">
                <li>
                    <span id="trigger_menu">
                        <span class="uk-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="10" cy="3" r="2"></circle> <circle cx="10" cy="10" r="2"></circle>
                                <circle cx="10" cy="17" r="2"></circle>
                            </svg>

                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <rect
                                        x="2" y="2" width="3" height="3"></rect>
                                <rect x="8" y="2" width="3" height="3"></rect>
                                <rect x="14" y="2" width="3" height="3"></rect>
                                <rect x="2" y="8" width="3" height="3"></rect>
                                <rect x="8" y="8" width="3" height="3"></rect>
                                <rect x="14" y="8" width="3" height="3"></rect>
                                <rect x="2" y="14" width="3" height="3"></rect>
                                <rect x="8" y="14" width="3" height="3"></rect>
                                <rect x="14" y="14" width="3" height="3"></rect>
                            </svg>
                        </span>
                    </span>
                </li>
            </ul>
            <a uk-navbar-toggle-icon="" href="#offcanvas" uk-toggle=""
               class="uk-navbar-toggle uk-hidden@m">

            </a>
        </div>

        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li>
                    <span class="" aria-expanded="false">{{ Auth::guard()->user()->name }}</span>
                    <div class="uk-navbar-dropdown uk-navbar-dropdown-bottom-right">
                        <ul class="uk-nav uk-navbar-dropdown-nav">
                            {{-- <li class="uk-active">
                                <img src="{{\Illuminate\Support\Facades\Auth::guard()->user()->getAvatar('m')}}"
                                     alt="">
                            </li> --}}
                            <li class="uk-nav-header">{{Auth::guard()->user()->job}}</li>
                            <li><a href="#">Настройки</a></li>
                            <li class="uk-nav-divider"></li>
                            <li><a href="/admin/logout" onclick="window.localStorage.clear()">Выйти</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>

                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
@include('admin.layouts.aside')
<div id="app">
    <div class="uk-container uk-container-expand uk-container-custom">
        @yield('content')
    </div>
</div>
@include ('admin.scripts.script')

</body>
</html>
