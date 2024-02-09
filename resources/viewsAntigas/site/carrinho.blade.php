@extends('site.layout')
@section('title','Carrinho')
    
@section('conteudo')

    @if ($mensagem = Session::get('notifyMessage'))
        <?php
            $_POST['arrNotifyMessage'] = $mensagem;
        ?>
    @endif

    @if (!$itens->count())

        <div class="row">
            <div class="col-12">
                <div class="card mt-4 bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Seu carrinho está vazio!</h5>
                        {{-- <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6> --}}
                        <p class="card-text">Aproveite nossas promoções!</p>
                        <a href="{{ route('site.index') }}" class="btn btn-outline-success btn-lg">Ir para as compras</a>
                    </div>
                </div>
            </div>
        </div>

    @else
            
        <div class="row">
            <div class="col-12">
                <h5>Seu carrinho possui {{ $itens->count() }} produtos.</h5>
            </div>
        </div>
        <div class="row flex-row flex-wrap justify-content-around p-0 m-0">

            <div class="table-responsive">
                <table class="table table-striped table-hover">
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
                                <form action=" {{ route('site.atualizaCarrinho') }} " method="POST" enctype="multipart/form-data" class="me-2">
                                    <td>
                                        <input type="number" name="quantity" min="1" value="{{ $item->quantity }}" style="width: 50px; text-align: center;">
                                    </td>
                                    <td>
                                        <div class="d-flex">
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

        <div class="row">
            <div class="col-12">
                <div class="card mt-4 bg-warning text-white mb-4 text-end" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Valor Total: R$ {{ number_format(\Cart::getTotal(), 2,',','.') }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Pague em até 12x sem juros no cartão de crédito</h6>
                        {{-- <p class="card-text">Aproveite nossas promoções!</p> --}}
                        {{-- <a href="#" class="btn btn-outline-success btn-lg">Ir para as compras</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                <a href="{{ route('site.index') }}" class="btn btn-primary me-2">Continuar comprando <i class="bi bi-arrow-return-left"></i></a>
                <a href="{{ route('site.limpaCarrinho') }}" class="btn btn-warning me-2">Limpar carrinho <i class="bi bi-x"></i></a>
                <button type="button" class="btn btn-success">Finalizar pedido <i class="bi bi-check-lg"></i></button>
            </div>
        </div>
        
    @endif

    
@endsection