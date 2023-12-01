<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ config('sistema.sigla') }} @yield('title')</title>

        <link rel="stylesheet" href="{{ asset('bootstrap-5.3.2-dist/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('bootstrap-icons-1.11.1/bootstrap-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style-login.css') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

        <script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="{{ asset('bootstrap-5.3.2-dist/js/bootstrap.min.js') }}"></script>

    </head>
    <body>

        <div id="login" class="d-flex justify-content-center align-items-center" style="width: 100vw; height: 100vh;">

            <div class="container-teste">
                
                <div class="container">

                    <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <h2 class="mb-3 text-center">{{ config('sistema.nome') }}</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4 class="mb-3 text-center">Login</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="firstName">Nome</label>
                                    <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Digite seu nome"  autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="lastName">Sobrenome</label>
                                    <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Digite seu sobrenome"  autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="username">Usuário</label>
                                    <input type="text" class="form-control" name="email" id="username" placeholder="Digite seu nome de usuário"  autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password">Senha</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Digite sua senha" >
                                        <button class="btn btn-outline-secondary" type="button" id="show-password">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if ($mensagem = Session::get('erro'))
                            {{ $mensagem }}
                        @endif

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                {{ $error }} <br>
                            @endforeach
                        @endif

                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary w-50" id="send">Entrar</button>
                            </div>
                        </div>

                    </form>

                </div>

            </div>

        </div>

        <script src="{{ asset('js/login/login.js') }}"></script>
 
    </body>
</html>