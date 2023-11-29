@extends('site.layout')
@section('title','Carrinho')
    
@section('conteudo')

    @if ($mensagem = Session::get('notifyMessage'))
        <?php
            $_POST['arrNotifyMessage'] = $mensagem;
        ?>
    @endif

    <div class="row">
        <div class="col-12">
            <h5>Seu carrinho possui {{ $itens->count() }} produtos.</h5>
        </div>
    </div>
    <div class="row flex-row flex-wrap justify-content-around p-0 m-0">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Quant.</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itens as $item)
                        <tr>
                            <td><img src=" {{ $item->attributes->image }} " alt="" width="70" class="img-thumbnail"></td>
                            <td> {{ $item->name }} </td>
                            <td> R$ {{ number_format($item->price,2,',','.') }} </td>
                            <td>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" style="width: 50px; text-align: center;">
                            </td>
                            <td>
                                <div class="d-flex">
                                    <form action=" {{ route('site.atualizaCarrinho') }} " method="POST" enctype="multipart/form-data" class="me-2">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button class="btn btn-outline-success btn-sm" title="Atualizar produto"><i class="bi bi-arrow-clockwise"></i></button>
                                    </form>
                                    
                                    <form action=" {{ route('site.removeCarrinho') }} " method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button class="btn btn-outline-danger btn-sm" title="Excluir produto"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    

    </div>

    <div class="row justify-content-center">
        <div class="col-12 d-flex justify-content-center">
            <button type="button" class="btn btn-primary me-2">Continuar comprando <i class="bi bi-arrow-return-left"></i></button>
            <button type="button" class="btn btn-warning me-2">Limpar carrinho <i class="bi bi-x"></i></button>
            <button type="button" class="btn btn-success">Finalizar pedido <i class="bi bi-check-lg"></i></button>
        </div>
    </div>
    
    
@endsection