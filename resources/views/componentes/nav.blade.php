<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a id="systemName" class="navbar-brand" href="#"> {{ config('sistema.nome') }} </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav d-flex flex-wrap justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Carrinho</a>
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
    </div>
</nav>