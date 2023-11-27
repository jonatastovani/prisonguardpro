<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGP @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.2-dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap-icons-1.11.1/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-login.css') }}">
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
    @component('componentes.nav')
    @endcomponent

    @yield('conteudo')

</body>
</html>