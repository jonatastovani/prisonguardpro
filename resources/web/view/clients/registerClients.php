<?php
$str_id = isset($_POST['id']) ? 'value="' . $_POST['id'] . '"' : '';

if ($str_id == '') {

	if (count($url)) {

		$str_id = 'value="' . $url[0] . '"';
	}
}

$redirectPrevious = isset($_POST['redirect-previous']) ? $_POST['redirect-previous'] : '/clients';
?>

<div class="row">
	<div class="col-12 text-center mt-2">
		<h3 id="title">Clientes</h3>
	</div>
</div>

<form name="form1" id="form1" method="post">
	<div class="row">
		<div class="col-md-6 mt-2">
			<label for="name" class="form-label">Nome:</label>
			<input type="text" class="form-control" id="name" required autofocus>
		</div>

		<div class="col-md-3 col-sm-4 mt-2">
			<label for="cpf" class="form-label">Documento:</label>
			<div class="row">
				<div class="col-6">
					<div class="form-check form-check-inline">
						<input type="radio" class="form-check-input" id="rbcpf" name="documento" checked>
						<label class="form-check-label" for="rbcpf">CPF</label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-check form-check-inline">
						<input type="radio" class="form-check-input" id="rbcnpj" name="documento">
						<label class="form-check-label" for="rbcnpj">CNPJ</label>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-4 mt-2">
			<div>
				<label for="cpf" class="form-label">CPF:</label>
				<input type="text" class="form-control" id="cpf">
			</div>
			<div hidden>
				<label for="cnpj" class="form-label">CNPJ:</label>
				<input type="text" class="form-control" id="cnpj" disabled>
			</div>
		</div>

		<div class="col-md-2 col-sm-4 mt-2">
			<label for="zipcode" class="form-label">CEP:</label>
			<input type="text" class="form-control" id="zipcode">
		</div>
		<div class="col-md-8 col-sm-6 mt-2">
			<label for="street" class="form-label">Logradouro:</label>
			<input type="text" class="form-control" id="street" maxlength="255">
		</div>
		<div class="col-sm-2 mt-2">
			<label for="street_number" class="form-label">Número:</label>
			<input type="text" class="form-control" id="street_number">
		</div>

		<div class="col-sm-4 mt-2">
			<label for="neighbourhood" class="form-label">Bairro:</label>
			<input type="text" class="form-control" id="neighbourhood">
		</div>
		<div class="col-sm-4 mt-2">
			<label for="complement" class="form-label">Bloco / Apartamento / Portão:</label>
			<input type="text" class="form-control" id="complement">
		</div>
		<div class="col-sm-4 mt-2">
			<label for="reference" class="form-label">Referência / Região:</label>
			<input type="text" class="form-control" id="reference">
		</div>

		<div class="col-sm-4 mt-2">
			<label for="city" class="form-label">Cidade:</label>
			<input type="text" class="form-control" id="city">
		</div>

		<div class="col-sm-4 mt-2">
			<label for="state" class="form-label">Estado:</label>
			<input type="text" class="form-control" id="state">
		</div>

		<div class="col-sm-5 mt-2">
			<label for="email" class="form-label">Email:</label>
			<input type="email" class="form-control" id="email" required>
		</div>
		<div class="col-sm-3 mt-2">
			<label for="tel" class="form-label">Telefone:</label>
			<input type="text" class="form-control clstelefone" id="tel">
		</div>
	</div>

	<div class="row mb-2">
		<div class="col-12 mt-2">
			<button id="save" class="btn btn-primary me-2" type="submit">
				<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
				<span>Salvar</span>
			</button>
			<input id="cancel" class="btn btn-danger" type="button" value="Cancelar" title="Cancelar ação">
		</div>
	</div>

	<input type="hidden" id="id" <?= $str_id ?>>
	<input type="hidden" id="redirectPrevious" value="<?= $redirectPrevious ?>">
</form>

<script type="module" src="/assets/js/clients/registerClients.js"></script>