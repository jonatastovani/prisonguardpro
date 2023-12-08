<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a id="systemName" class="navbar-brand" href="#"> {{ config('sistema.nome') }} </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav d-flex flex-wrap justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('site.index') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Inclusão
                    </a>
                    <ul class="dropdown-menu" data-bs-popper="static">
                        <li class="nav-item dropend">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Entradas
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Inserir / Alterar</a></li>
                                <li><a class="dropdown-item" href="#">Gerenciar entradas</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropend">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Pertences
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" title="Gereciar Pertences retidos">Gerenciar Pertences</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropend">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Sedex
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" title="Gereciar Sedexs retidos">Gerenciar Sedex</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        
        

        <ul class="navbar-nav d-flex flex-wrap text-end">
            <li class="nav-item dropdown">
                @auth
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Olá {{ auth()->user()->firstName }}!
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        
                        {{-- <li><a class="dropdown-item" href="{{ route('admin.dashboard') }} "> Dashboard </a></li> --}}
                        <li><a class="dropdown-item" href="{{ route('login.logout') }} "> Sair </a></li>

                    </ul>
                @else
                    <a class="nav-link" href="{{route('login.login')}}" role="button">
                        Login
                    </a>
                @endauth
            </li>
        </ul>

    </div>
</nav>