@extends('site.layout')
@php
    $titulo = !empty($id) ? "Entrada $id" : 'Nova Entrada';
@endphp
@section('title', $titulo)

@section('conteudo')

    <div class="row">
        <div class="col-12 mt-2 text-center">
            <h3>{{ !empty($id) ? 'Alterar Entrada de Presos' : 'Nova Entrada de Presos' }}</h3>
        </div>
    </div>

    <div id="dadosEntradaEntradasPresos">
		<div class="row">
			<div class="col-md-7 mt-2" title="Selecione a Origem da Entrada">
				<label class="form-label" for="origem_idEntradasPresos">Origem</label>
				<select name="origem_id" id="origem_idEntradasPresos" class="form-select"></select>
			</div>
			<div class="col-md-3 col-sm-4 mt-2">
				<label class="form-label" for="data_entradaEntradasPresos">Data Entrada</label>
				<input type="date" name="data_entrada" id="data_entradaEntradasPresos" class="form-control"
					value="{{ now()->format('Y-m-d') }}">
			</div>
			<div class="col-md-2 col-sm-3 mt-2">
				<label class="form-label" for="hora_entradaEntradasPresos">Hora Entrada</label>
				<input type="time" name="hora_entrada" id="hora_entradaEntradasPresos" class="form-control"
					value="{{ now()->format('H:i') }}">
			</div>
		</div>
	</div>

    <div class="row">
        <div class="col-12">
            <h5>Presos</h5>
        </div>
    </div>

    <div class="row flex-fill border border-dark-subtle rounded p-2 overflow-auto">
        <div class="col-12 p-0">
            <div id="containerPresos" class="d-flex flex-row flex-wrap"></div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-sm-6">
			<div class="row">
				<div class="col-12 mt-2">
					<button type="button" id="btnInserirPreso" class="btn btn-primary w-md-25" title="Inserir um preso">
						Inserir Preso
					</button>
				</div>
			</div>
		</div>
        <div class="col-sm-6">
			<div class="row text-end">
				<div class="col-12 mt-2">
					<button type="submit" id="btnSalvar" class="btn btn-success me-2 w-25" title="Salvar alterações">
						Salvar
					</button>
					<a href="{{ !empty($redirecionamentoAnterior) ? $redirecionamentoAnterior : route('inclusao.entradasPresos') }}"
						class="btn btn-danger w-25 redirecionamentoAnterior" title="Sair da edição da Entrada de Presos" style="width: 100px;">
						Sair
					</a>
				</div>
			</div>
		</div>
    </div>

    <input type="hidden" id="id" value="{{ isset($id) ? $id : '' }}">

    <?php // include_once "view/popup/budgets/popupEditBudgets.php"
    ?>
    <?php // include_once "view/popup/orders/popupOrders.php"
    ?>
    <?php // include_once "view/popup/products/popupProducts.php"
    ?>
    <?php // include_once "view/popup/products/popupNewProduct.php"
    ?>

    <script type="module" src="{{ asset('js/setores/inclusao/cadastroEntradasPresos.js') }}"></script>

@endsection
