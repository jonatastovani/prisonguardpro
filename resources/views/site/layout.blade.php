<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('sistema.sigla') }} @yield('title')</title>

    <link rel="stylesheet" href="/bootstrap-5.3.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="/bootstrap-icons-1.11.1/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-popup.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/modal.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/style-login.css') }}">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('build/assets/app-e3b0c442.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="{{ asset('js/jquery/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery/jquery.mask-1.14.16.min.js') }}"></script>
    <script src="{{ asset('js/jquery/jquery.Jcrop.min.js') }}"></script>
    <script src="{{ asset('js/jquery/jquery-maskmoney-v3.0.2.min.js') }}"></script>
    <script src="{{ asset('js/jquery/notify.min.js') }}"></script>
    <script src="{{ asset('js/jquery/moments-2.29.4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="{{ asset('bootstrap-5.3.2-dist/js/bootstrap.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>        
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script type="module" src="{{asset('build/assets/app-72d66899.js')}}"></script>

</head>

<body>
    {{-- @vite('resources/js/app.js') --}}
    
    <div class="container-fluid">
        <div class="row vh-100">
            <div class="col-12 d-flex flex-column flex-nowrap mh-100">
                @component('componentes.nav')
                @endcomponent

                <div class="row flex-fill overflow-auto">
                    <div class="col-12 mx-auto d-flex flex-column flex-nowrap mh-100" style="max-width: 1200px;">
                        @yield('conteudo')
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('site.includesDefault')
    @include('comuns.modalMessage')
    <script type="module" src="{{ asset('js/script.js') }}"></script>

</body>

</html>
