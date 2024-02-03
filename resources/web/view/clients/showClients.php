<div class="row">
	<div class="col-12 mt-2">
		<h3 class="text-center">Clientes</h3>
	</div>
</div>

<div class="row">
	<div id="dataSearch" class="col-lg-12 col-sm-11 dataSearch">

		<div class="row">

			<div class="col-lg-3 col-sm-6 mt-2">
				<div class="input-group">
					<div class="input-group-text"><label for="nameSearchClients">Nome</label></div>
					<input type="text" class="form-control inputActionSearch" name="name" id="nameSearchClients">
				</div>
			</div>

			<div class="col-lg-3 col-sm-6 mt-2">
				<div class="row align-items-center h-100">
					<div class="col-6">
						<div class="form-check" title="Forma de ordenação em ordem ascendente, ou seja, do menor para o maior">
							<input type="radio" class="form-check-input inputActionSearch" id="rbAscSearchClients" name="method" value="asc" checked>
							<label class="form-check-label" for="rbAscSearchClients">Ascendente</label>
						</div>
					</div>
					<div class="col-6">
						<div class="form-check" title="Forma de ordenação em ordem descendente, ou seja, ou do maior para o menor">
							<input type="radio" class="form-check-input inputActionSearch" id="rbDescSearchClients" name="method" value="desc">
							<label class="form-check-label" for="rbDescSearchClients">Descendente</label>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-sm-6 mt-2">
				<div class="row align-items-center h-100">
					<div class="col-6">
						<div class="form-check">
							<input type="radio" class="form-check-input inputActionSearch" id="rbCpfSearchClients" name="document" value="cpf" checked>
							<label class="form-check-label" for="rbCpfSearchClients">CPF</label>
						</div>
					</div>
					<div class="col-6">
						<div class="form-check">
							<input type="radio" class="form-check-input inputActionSearch" id="rbCnpjSearchClients" name="document" value="cnpj">
							<label class="form-check-label" for="rbCnpjSearchClients">CNPJ</label>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6 mt-2">
				<div class="input-group group-cpfSearchClients">
					<div class="input-group-text"><label for="cpfSearchClients">CPF</label></div>
					<input type="text" name="cpf" class="form-control inputActionSearch" id="cpfSearchClients">
				</div>
				<div class="input-group group-cnpjSearchClients" hidden>
					<div class="input-group-text"><label for="cnpjSearchClients">CNPJ</label></div>
					<input type="text" name="cnpj" class="form-control inputActionSearch" id="cnpjSearchClients" disabled>
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-sm-6 mt-2">
				<div class="row align-items-center h-100">
					<div class="col-6" title="Data de Cadastro">
						<div class="form-check">
							<input type="radio" class="form-check-input inputActionSearch" id="rbCreatedSearchClients" name="dateSearch" value="created" checked>
							<label class="form-check-label" for="rbCreatedSearchClients">Cadastro</label>
						</div>
					</div>
					<div class="col-6" title="Data de Atualização">
						<div class="form-check">
							<input type="radio" class="form-check-input inputActionSearch" id="rbUpdatedSearchClients" name="dateSearch" value="updated">
							<label class="form-check-label" for="rbUpdatedSearchClients">Atualização</label>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6 mt-2 group-createdSearchClients" title="Filtro para busca informando o intervalo de datas que o cliente foi cadastrado">
				<div class="row">
					<div class="col-lg-7 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="createdAfterSearchClients">Cadastrado de:</label>
							<input type="date" class="form-control inputActionSearch" id="createdAfterSearchClients" name="createdAfterSearchClients">
						</div>
					</div>
					<div class="col-lg-5 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="createdBeforeSearchClients">até:</label>
							<input type="date" class="form-control inputActionSearch" id="createdBeforeSearchClients" name="createdBeforeSearchClients">
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6 mt-2 group-updatedSearchClients" title="Filtro para busca informando o intervalo de datas que o orçamento foi atualizado pela última vez" hidden>
				<div class="row">
					<div class="col-lg-7 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="updatedAfterSearchClients">Atualizado de:</label>
							<input type="date" class="form-control inputActionSearch" id="updatedAfterSearchClients" name="updatedAfterSearchClients" disabled>
						</div>
					</div>
					<div class="col-lg-5 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="updatedBeforeSearchClients">até:</label>
							<input type="date" class="form-control inputActionSearch" id="updatedBeforeSearchClients" name="updatedBeforeSearchClients" disabled>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<div class="col-auto flex-fill text-end">
		<button id="toggleDataSearchButton" class="btn btn-outline-secondary btn-mini d-lg-none toggleDataSearchButton">
			<i class="bi bi-view-list"></i>
		</button>
	</div>
</div>

<div class="row flex-fill overflow-auto">
	<div class="table-responsive mt-2">
		<table id="table-clients" class="table table-hover">
			<thead>
				<tr class="text-center">
					<th>Nome</th>
					<th>Telefone</th>
					<th>CPF</th>
					<th>CNPJ</th>
					<th>Cidade</th>
					<th>Ação</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<div class="row mt-2 mb-2">
	<div class="col-2">
		<form action="clients/new" method="post">
			<input id="new" class="btn btn-primary" type="submit" value="Novo cliente" title="Inserir novo cliente">
		</form>
	</div>
</div>

<script type="module" src="/assets/js/clients/showClients.js"></script>