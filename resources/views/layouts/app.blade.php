<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('ChatWelfare | Chatbot for finding schemes') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon_.PNG') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<style>

</style>

@php
    use Illuminate\Support\Facades\Request;
    use Stichoza\GoogleTranslate\GoogleTranslate;
    use Illuminate\Support\Facades\Session;

    $translator = new GoogleTranslate();
    $tamil = $translator->setTarget("ta")->translate("Tamil");
    $telugu = $translator->setTarget("te")->translate("Telugu");
    $malayalam = $translator->setTarget("ml")->translate("Malayalam");

    $lang = Session::get("lang");
    $translator->setTarget($lang);
    $path = Request::getPathInfo();

    $introMessage = "Hi! This is ChatWelfare, an chatbot for finding government schemes specifically for PwD's.";
    $introMessage .= "Please provide the following details:";
    $introMessage .= "<Your Disability> scheme (or) Enter \"list\" to display all available schemes.";

    $introMessage = $translator->translate($introMessage);
@endphp

<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light shadow-lg rounded-bottom-pill border border-dark"
         style="background: #1F2544; color: #fff">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                {{ $translator->translate(__('Home Page')) }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <li>
                        @auth
                            <a href="{{ route('schemes.index') }}" class="nav-link text-white">
                                {{ $translator->translate( __('Schemes')) }}
                            </a>
                        @endauth
                    </li>

                    @if($path === '/')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#"
                               role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                               v-pre>
                                {{ $translator->translate( __('Language')) }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('translate', "en") }}">{{ __("English") }}</a>
                                <a class="dropdown-item" href="{{ route('translate', "ta") }}">{{ $tamil }}</a>
                                <a class="dropdown-item" href="{{ route('translate', "te") }}">{{ $telugu }}</a>
                                <a class="dropdown-item" href="{{ route('translate', "ml") }}">{{ $malayalam }}</a>
                            </div>
                        </li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link text-white"
                                   href="{{ route('login') }}">{{ $translator->translate(__('Admin Login')) }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#"
                               role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                               v-pre>
                                {{ $translator->translate(Auth::user()->name) }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ $translator->translate(__('Logout')) }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="pt-4">
        @yield('content')
    </main>
</div>
</body>

@if($path === "/")
    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();

        var botmanWidget = {
            title: 'ChatWelfare',
            introMessage: "{{ $introMessage }}",
            desktopHeight: 1000,
            desktopWidth: 500,
            aboutText: '',
            aboutLink: '',
            headerTextColor: '#fff',
            timeFormat: 'hh:MM',
            placeholderText: 'Ask ChatWelfare ...',
            bubbleBackground: '#fff',
            bubbleAvatarUrl: "{{ asset('assets/img/bot_icon.jpg') }}",
        };

        recognition.onstart = function () {
            botmanChatWidget.open();
            Swal.fire({
                position: "center-left",
                title: "Speak now...",
                imageUrl: "{{ asset('assets/img/microphone.png') }}",
                imageHeight: 100,
                imageWidth: 100,
                imageAlt: "mic",
                showConfirmButton: false,
                timer: 3500
            });
        };

        recognition.onerror = function (event) {
            botmanChatWidget.sayAsBot("{{ $translator->translate("I'm unable to hear your speech!") }}");
        };

        recognition.onresult = function (event) {
            const speechResult = event.results[0][0].transcript;
            console.log('Recognized speech:', speechResult);
            botmanChatWidget.say(speechResult);
        };

        document.getElementById('voiceInputBtn').addEventListener('click', function () {
            recognition.start();
        });
    </script>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

</html>
