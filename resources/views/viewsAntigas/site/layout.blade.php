<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ config('sistema.sigla') }} @yield('title')</title>

        <link rel="stylesheet" href="{{ asset('bootstrap-5.3.2-dist/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('bootstrap-icons-1.11.1/bootstrap-icons.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style-popup.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style-login.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style-dashboard.css') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

        <script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>
        <script src="{{ asset('js/jquery.mask-1.14.16.min.js') }}"></script>
        <script src="{{ asset('js/jquery.Jcrop.min.js') }}"></script>
        <script src="{{ asset('js/jquery-maskmoney-v3.0.2.min.js') }}"></script>
        <script src="{{ asset('js/notify.min.js') }}"></script>
        <script type="module" src="{{ asset('js/script.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="{{ asset('bootstrap-5.3.2-dist/js/bootstrap.min.js') }}"></script>

    </head>
    <body>

        <div class="container-fluid">
            <div class="row d-flex flex-column flex-nowrap vh-100">
                <div class="row m-0 p-0">
                    @component('componentes.nav')
                    @endcomponent
                </div>
            
                <div class="row flex-fill overflow-auto mx-auto">
                    <main class="container-fluid">
                        <div class="row justify-content-center p-0 m-0">
                            <div class="col p-0 m-0" style="max-width: 1000px;">
                                
                                @yield('conteudo')
                    
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>    

        @include('site.includesDefault')        
        @include('common.modalMessage')
        <script type="module" src="{{ asset('js/script.js') }}"></script>

    </body>
</html>