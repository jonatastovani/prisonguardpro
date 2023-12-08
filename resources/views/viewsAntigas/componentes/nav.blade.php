<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a id="systemName" class="navbar-brand" href="#"> {{ config('sistema.nome') }} </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav d-flex flex-wrap justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href=" {{ route('site.index') }} ">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href=" {{ route('site.carrinho') }} ">
                        Carrinho
                        <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger">
                            {{ \Cart::getContent()->count() }}
                            <span class="visually-hidden"> {{ \Cart::getContent()->count() }} Produtos adicionados no seu carrinho</span>
                        </span>
                        {{-- <span class="badge text-bg-secondary"> {{ \Cart::getContent()->count() }} </span>--}}
                    </a> 
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Categorias
                    </a>
                    <ul class="dropdown-menu">

                        @foreach ($categoriasMenu as $categoriaM)
                            <li><a class="dropdown-item" href="{{ route('site.categoria', $categoriaM->id) }} "> {{ $categoriaM->nome }} </a></li>
                        @endforeach

                    </ul>
                </li>
            </ul>
        </div>

        <ul class="navbar-nav d-flex flex-wrap text-end">
            {{-- <li class="nav-item">
                <a id="fullscreen" class="nav-link active" href="#" title="Clique para aplicar ou remover a tela cheia"><i class="bi bi-arrows-fullscreen"></i></a>
            </li> --}}
            <li class="nav-item dropdown">
                @auth
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Olá {{ auth()->user()->firstName }}!
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }} "> Dashboard </a></li>
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