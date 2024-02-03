<div class="row">
	<div class="col-12 mt-2">
		<h3 class="text-center">Orçamentos</h3>
	</div>
</div>

<div class="row">
	<div id="dataSearch" class="col-lg-12 col-sm-11 dataSearch">

		<div class="row">
			<div class="col-lg-3 col-sm-6 mt-2 order-sm-1">
				<div class="row align-items-center h-100">
					<div class="col-6" title="Data de Cadastro">
						<div class="form-check">
							<input type="radio" class="form-check-input inputActionBudgetsSearch" id="rbCreatedBudgets" name="dateSearchClientsBudgets" value="created" checked>
							<label class="form-check-label" for="rbCreatedBudgets">Cadastro</label>
						</div>
					</div>
					<div class="col-6" title="Data de Atualização">
						<div class="form-check">
							<input type="radio" class="form-check-input inputActionBudgetsSearch" id="rbUpdatedBudgets" name="dateSearchClientsBudgets" value="updated">
							<label class="form-check-label" for="rbUpdatedBudgets">Atualização</label>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 mt-2 group-createdBudgets order-lg-2 order-sm-3" title="Filtro para busca informando o intervalo de datas que o cliente foi cadastrado">
				<div class="row">
					<div class="col-lg-7 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="createdAfterBudgets">Cadastrado de:</label>
							<input type="date" class="form-control inputActionBudgetsSearch" id="createdAfterBudgets" name="createdAfterBudgets">
						</div>
					</div>
					<div class="col-lg-5 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="createdBeforeBudgets">até:</label>
							<input type="date" class="form-control inputActionBudgetsSearch" id="createdBeforeBudgets" name="createdBeforeBudgets">
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 mt-2 group-updatedBudgets order-lg-2 order-sm-3" title="Filtro para busca informando o intervalo de datas que o orçamento foi atualizado pela última vez" hidden>
				<div class="row">
					<div class="col-lg-7 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="updatedAfterBudgets">Atualizado de:</label>
							<input type="date" class="form-control inputActionBudgetsSearch" id="updatedAfterBudgets" name="updatedAfterBudgets" disabled>
						</div>
					</div>
					<div class="col-lg-5 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="updatedBeforeBudgets">até:</label>
							<input type="date" class="form-control inputActionBudgetsSearch" id="updatedBeforeBudgets" name="updatedBeforeBudgets" disabled>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6 mt-2 order-sm-2">
				<div class="row align-items-center h-100">
					<div class="col-6">
						<div class="form-check" title="Forma de ordenação em ordem ascendente, ou seja, ou do menor para o maior">
							<input type="radio" class="form-check-input inputActionBudgetsSearch" id="rbAscBudgets" name="methodBudgets" value="asc" checked>
							<label class="form-check-label" for="rbAscBudgets">Ascendente</label>
						</div>
					</div>
					<div class="col-6 mt-2">
						<div class="form-check" title="Forma de ordenação em ordem descendente, ou seja, ou do maior para o menor">
							<input type="radio" class="form-check-input inputActionBudgetsSearch" id="rbDescBudgets" name="methodBudgets" value="desc">
							<label class="form-check-label" for="rbDescBudgets">Descendente</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8 col-md-6 mt-2" title="Filtro para busca do orçamento selecionando o cliente">
				<div class="input-group">
					<div class="input-group-text">
						<label for="btnSearchClientesBudgets">Cliente</label>
					</div>
					<button id="btnSearchClientesBudgets" class="btn btn-outline-info" title="Clique para busca avançada de cliente"><i class="bi bi-search"></i></button>
					<select name="client_id" id="client_idBudgets" class="form-select inputActionBudgetsSearch"></select>
				</div>
			</div>
			<div class="col-lg-4 col-sm-6 mt-2" title="Filtro para busca do orçamento informando o ID do Orçamento">
				<div class="input-group">
					<div class="input-group-text">
						<label for="budget_id">Orçamento</label>
					</div>
					<input id="budget_id" type="search" class="form-control inputActionBudgetsSearch" list="listBudgets">
				</div>
				<datalist id="listBudgets"></datalist>
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
		<table id="table-budgets" class="table table-hover">
			<thead>
				<tr class="text-center">
					<th>ID</th>
					<th>Nome</th>
					<th>Telefone</th>
					<th>Preço Final</th>
					<th>Data do orçamento</th>
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
		<button id="btnNewBudget" class="btn btn-primary" title="Inserir novo orçamento">Novo orçamento</button>
	</div>
</div>

<?php include_once "view/popup/budgets/popupNewBudgets.php"; ?>
<script type="module" src="/assets/js/budgets/showBudgets.js"></script>