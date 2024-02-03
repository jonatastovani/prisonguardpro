<div class="row">
	<div class="col-12">
		<h3 class="text-center">Pedidos</h3>
	</div>
</div>

<div class="row">
	<div id="dataSearch" class="col-lg-12 col-sm-11 dataSearch">

		<div class="row">

			<div class="col-lg-3 col-sm-6 mt-2 order-sm-1">
				<div class="row h-100 align-items-center">
					<div class="col-6" title="Data de cadastro">
						<div class="form-check">
							<input type="radio" class="form-check-input inputActionOrders" id="rbCreatedOrders" name="dateSearch" value="created" checked>
							<label class="form-check-label" for="rbCreatedOrders">Cadastro</label>
						</div>
					</div>
					<div class="col-6" title="Data de atualização">
						<div class="form-check">
							<input type="radio" class="form-check-input inputActionOrders" id="rbUpdatedOrders" name="dateSearch" value="updated">
							<label class="form-check-label" for="rbUpdatedOrders">Atualização</label>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6 mt-2 order-lg-2 order-sm-3 group-createdOrders" title="Filtro para busca informando o intervalo de datas que o pedido foi cadastrado">
				<div class="row">
					<div class="col-lg-7 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="createdAfterOrders">Cadastrado de:</label>
							<input type="date" class="form-control inputActionOrders" id="createdAfterOrders" name="createdAfterOrders">
						</div>
					</div>
					<div class="col-lg-5 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="createdBeforeOrders">até:</label>
							<input type="date" class="form-control inputActionOrders" id="createdBeforeOrders" name="createdBeforeOrders">
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6 mt-2 order-lg-2 order-sm-3 group-updatedOrders" title="Filtro para busca informando o intervalo de datas que o pedido foi atualizado pela última vez" hidden>
				<div class="row">
					<div class="col-lg-7 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="updatedAfterOrders">Atualizado de:</label>
							<input type="date" class="form-control inputActionOrders" id="updatedAfterOrders" name="updatedAfterOrders" disabled>
						</div>
					</div>
					<div class="col-lg-5 col-sm-6">
						<div class="input-group">
							<label class="input-group-text" for="updatedBeforeOrders">até:</label>
							<input type="date" class="form-control inputActionOrders" id="updatedBeforeOrders" name="updatedBeforeOrders" disabled>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-sm-6 mt-2 order-sm-2">
				<div class="row h-100 align-items-center">
					<div class="col-6">
						<div class="form-check" title="Forma de ordenação em ordem ascendente, ou seja, do menor para o maior">
							<input type="radio" class="form-check-input inputActionOrders" id="rbAscOrders" name="method" value="asc" checked>
							<label class="form-check-label" for="rbAscOrders">Ascendente</label>
						</div>
					</div>
					<div class="col-6">
						<div class="form-check" title="Forma de ordenação em ordem descendente, ou seja, ou do maior para o menor">
							<input type="radio" class="form-check-input inputActionOrders" id="rbDescOrders" name="method" value="desc">
							<label class="form-check-label" for="rbDescOrders">Descendente</label>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="row">

			<div class="col-md-4 col-sm-6 mt-2">
				<div class="input-group">
					<div class="input-group-text"><label for="statusOrders">Status</label></div>
					<select name="status" id="statusOrders" class="form-select inputActionOrders"></select>
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
		<table id="table-orders" class="table table-hover">
			<thead>
				<tr class="text-center">
					<th>ID Pedido</th>
					<th>Status</th>
					<th>Cliente</th>
					<th>Orçamento</th>
					<th>Preço</th>
					<th>Telefone</th>
					<th>Ação</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<?php include_once "view/popup/orders/popupOrders.php"; ?>
<script type="module" src="/assets/js/orders/showOrders.js"></script>