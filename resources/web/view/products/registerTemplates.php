<?php
$str_id = isset($_POST['id']) ? 'value="' . $_POST['id'] . '"' : '';

if ($str_id == '') {

	if (count($url)) {

		$str_id = 'value="' . $url[0] . '"';
	}
}

$redirectPrevious = isset($_POST['redirect-previous']) ? $_POST['redirect-previous'] : '/products/templates';
?>

<div class="row mt-2">
	<div class="col-10 text-center">
		<h3 id="title">Modelo</h3>
	</div>
	<div class="col-2 text-end">
		<button class="btn btn-outline-info btn-sm" id="editTemplate" title="Editar este modelo"><i class="bi bi-pencil"></i></button>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<h5>Itens</h5>
	</div>
</div>

<div class="row flex-fill border rounded p-2 overflow-auto">
	<div class="col-12">
		<div id="containerItems" class="row flex-row flex-wrap">

		</div>
	</div>
</div>

<div class="row mt-2 mb-2">
	<div class="col-lg-3">
		<button id="btnAddItem" class="btn btn-primary me-2" title="Inserir um novo item">Inserir item</button>
		<button id="cancel" class="btn btn-danger" style="width: 90px;" title="Sair sem salvar dados do modelo">Sair</button>
	</div>
</div>

<input type="hidden" id="id" <?= $str_id ?>>
<input type="hidden" id="redirectPrevious" value="<?= $redirectPrevious ?>">

<?php include_once "view/popup/products/popupNewTemplate.php" ?>
<?php include_once "view/popup/products/popupNewItemTemplate.php" ?>
<script type="module" src="/assets/js/products/registerTemplates.js"></script>