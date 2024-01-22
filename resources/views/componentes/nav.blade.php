<div class="row">
    <nav id="navbar" class="navbar navbar-dark navbar-expand-lg bg-dark">
        <div class="container-fluid">
            <a id="systemName" class="navbar-brand" href="/home">{{ config('sistema.nome') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>

            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="navbarNavDropdown" aria-labelledby="navbarNavDropdownLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="navbarNavDropdownLabel">{{ config('sistema.nome') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav">
                        {{-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/home">
                                Home
                            </a>
                        </li> --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Inclusão
                            </a>
                            <ul class="dropdown-menu" data-bs-popper="static">
                                <li class="nav-item">
                                    <a class="dropdown-item item-dropdown" href="{{route('inclusao.entradasPresos')}}">
                                        Entrada de presos
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="dropdown-item item-dropdown" href="/employees/home">
                                        Funcionários
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item item-dropdown" href="/products/home">
                                        Produtos
                                    </a>
                                </li> --}}
                            </ul>
                        </li>
                        {{-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Vendas
                            </a>
                            <ul class="dropdown-menu" data-bs-popper="static">
                                <li class="nav-item">
                                    <a class="dropdown-item item-dropdown" href="/budgets">
                                        Orçamentos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item item-dropdown" href="/orders">
                                        Pedidos
                                    </a>
                                </li>
                            </ul>
                        </li> --}}

                    </ul>
                    <ul class="navbar-nav flex-fill justify-content-end">
                        <li class="nav-item dropdown">
                            @auth
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Olá {{ auth()->user()->nome }}!
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
            </div>
        </div>
    </nav>
</div>