<?php
$str_id = isset($_POST['id']) ? 'value="' . $_POST['id'] . '"' : '';

if ($str_id == '') {

	if (count($url)) {

		$str_id = 'value="' . $url[0] . '"';
	}
}

$redirectPrevious = isset($_POST['redirect-previous']) ? $_POST['redirect-previous'] : '/budgets';
?>

<div class="row">
	<div class="col-10 mt-2 text-center">
		<h3 id="title">Orçamento</h3>
	</div>
	<div class="col-2 mt-2 text-end">
		<button class="btn btn-outline-info btn-sm" id="editBudget" title="Editar este orçamento"><i class="bi bi-pencil"></i></button>
	</div>
</div>

<div class="row">
	<div class="col-12">

		<div class="row">
			<div class="d-flex">
				<h5 id="nameClient"></h5>
				<span id="btnOpenClient"></span>
			</div>
		</div>

		<div class="row">

			<div id="dataClient" class="col-lg-12 col-md-11 col-sm-10">

				<div class="row">

					<div class="col-md-4 col-sm-6">
						<p class="m-0">Telefone: <b><span id="tel"></span></b></p>
						<p class="m-0"><span id="typeDoc"></span>: <b><span id="doc"></span></b></p>
					</div>
					<div class="col-md-4 col-sm-6">
						<p class="m-0">Gerado em: <b><span id="created_at"></span></b></p>
						<p class="m-0">Alterado em: <b><span id="updated_at"></span></b></p>
					</div>
					<div class="col-sm-4 d-flex align-items-center">
						<p class="m-0">Pedido: <b><span id="order_id"></span></b></p>
						<span id="edit_order" class="ps-1 fs-6"></span>
					</div>

				</div>
			</div>
			<div class="col-md-1 col-sm-2 flex-fill text-end">
				<button id="toggleDataClientButton" class="btn btn-outline-secondary btn-mini d-lg-none">
					<i class="bi bi-view-list"></i>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<h5>Produtos</h5>
	</div>
</div>

<div class="row flex-fill border border-dark-subtle rounded p-2 overflow-auto">
	<div class="col-12 p-0">
		<div id="containerProducts" class="d-flex flex-row flex-wrap">

		</div>
	</div>
</div>

<div class="row mb-2">
	<div class="col-md-7 d-flex justify-content-end mt-2 order-md-2">
		<div class="row">
			<div class="col-sm-6">
				<div class="input-group me-2" title="Preço de custo deste orçamento">
					<label class="input-group-text">Custo R$</label>
					<input type="password" class="form-control" id="cost_priceBudget" disabled>
					<button class="btn btn-outline-secondary" type="button" id="show_cost_price" title="Exibir preço de custo"><i class="bi bi-eye-fill"></i></button>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="input-group me-2" title="Preço final deste orçamento">
					<label class="input-group-text">Preço final R$</label>
					<input type="text" class="form-control" id="priceBudget" disabled>
				</div>
			</div>
		</div>

	</div>
	<div class="col-md-5 mt-2">
		<button id="btnNewProduct" class="btn btn-primary me-2 w-sm-50" title="Inserir um novo produto">Novo produto</button>
		<button id="cancel" class="btn btn-danger w-sm-50" title="Sair do orçamento" style="width: 100px;">Sair</button>
	</div>
</div>

<input type="hidden" id="id" <?= $str_id ?>>
<input type="hidden" id="redirectPrevious" value="<?= $redirectPrevious ?>">

<?php include_once "view/popup/budgets/popupEditBudgets.php" ?>
<?php include_once "view/popup/orders/popupOrders.php" ?>
<?php include_once "view/popup/products/popupProducts.php" ?>
<?php include_once "view/popup/products/popupNewProduct.php" ?>
<script type="module" src="/assets/js/budgets/registerBudgets.js"></script>