<div class="row">
	<div class="col-12 mt-2">
		<h3 class="text-center">Modelos</h3>
	</div>
</div>
<div class="row flex-fill overflow-auto">
	<div class="table-responsive mt-2">
		<table id="table-templates" class="table table-hover">
			<thead>
				<tr class="text-center">
					<th>Nome</th>
					<th>Qtd. Itens</th>
					<th>Ação</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<div class="row mt-2 mb-2">
	<div class="col-12">
		<button id="btnNewTemplate" class="btn btn-primary" title="Inserir novo modelo">Novo modelo</button>
	</div>
</div>

<?php include_once "view/popup/products/popupNewTemplate.php" ?>
<script type="module" src="/assets/js/products/showTemplates.js"></script>