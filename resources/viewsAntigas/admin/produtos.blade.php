@extends('site.layout')
@section('title','Produtos')
    
@section('conteudo')
    <div class="fixed-action-btn">
        <a id="openModalProduto" class="btn-floating btn-large bg-gradient-green modal-trigger" href="#">
            <i class="bi bi-plus"></i>
        </a>
    </div>

    <!-- Modal Structure -->
    <div id="modalProduto" class="modal">
        <div class="modal-content" style="max-width: 500px; margin: 0 auto; ">
            <h4><i class="bi bi-card-gift"></i> Novo produto</h4>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="first_name" class="form-label">First Name</label>
                    <input placeholder="Placeholder" id="first_name" type="text" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input id="last_name" type="text" class="form-control">
                </div>

                <div class="col-12">
                    <label for="select" class="form-label">Materialize Select</label>
                    <select class="form-select">
                        <option value="" disabled selected>Choose your option</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </select>
                </div>

                <div class="col-12">
                    <a href="#!" class="btn btn-primary">Cadastrar</a><br>
                </div>
            </form>
        </div>
    </div>


    <div class="container">
        <div class="row crud">
            <div class="row titulo">
                <h1 class="col-12 col-md-6">Produtos</h1>
                <span class="col-12 col-md-6 chip text-end">234 produtos cadastrados</span>
            </div>

            <nav class="navbar navbar-dark bg-gradient-blue">
                <div class="container-fluid">
                    <form class="d-flex">
                        <div class="input-group">
                            <input placeholder="Pesquisar..." id="search" type="search" class="form-control" required>
                            <button class="btn btn-outline-light" type="button"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </nav>

            <div class="card z-depth-4 registros">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Categoria</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($produtos as $produto)
                            <tr>
                                <td><img src=" {{ $produto->imagem }} " alt="" width="70" class="img-thumbnail"></td>
                                <td>#{{ $produto->id }}</td>
                                <td>{{ $produto->nome }}</td>
                                <td>R$ {{ number_format($produto->preco,2,',','.') }}</td>
                                <td>{{ $produto->categoria->nome }}</td>
                                <td>
                                    <a class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                                    <a class="btn btn-danger"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 d-flex justify-content-center">
                    {{ $produtos->links('custom.pagination') }}
                </div>
            </div>
            
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Abrir modal ao clicar em um botão ou link (substitua '#seuBotao' pelo seletor real do seu botão)
            $('#openModalProduto').on('click', function () {
                $('#modalProduto').fadeIn();
            });

            // Fechar modal ao clicar no botão "Cadastrar"
            $('#modalProduto .btn-primary').on('click', function () {
                $('#modalProduto').fadeOut();
            });

            // Fechar modal ao clicar fora da área do modal
            $(document).mouseup(function (e) {
                var container = $('#modalProduto');
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.fadeOut();
                }
            });

                        
            // /* Modal */
            // $('#openModalProduto').click(function () {
            //     $('#modalProduto').show();
            // })

        });

    </script>
    
@endsection