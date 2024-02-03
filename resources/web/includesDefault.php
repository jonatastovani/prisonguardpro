<script>
	const systemDisplayName = "<?=SYSTEM_DISPLAY_NAME?>";
	const urlDomain = "<?=URL_DOMAIN?>";
	const urlApiZipCode = "<?=URL_DOMAIN.URL_ZIPCODE?>";
	const urlApiClients = "<?=URL_DOMAIN.API_CLIENTS?>";
	const urlApiWorkEmployees = "<?=URL_DOMAIN.API_WORKFORCE_EMPLOYEES?>";
	const urlApiWorkDepartments = "<?=URL_DOMAIN.API_WORKFORCE_DEPARTMENTS?>";
	const urlApiWorkRoles = "<?=URL_DOMAIN.API_WORKFORCE_ROLES?>";
	const urlApiProdItems = "<?=URL_DOMAIN.API_PRODUCTS_ITEMS?>";
	const urlApiOrders = "<?=URL_DOMAIN.API_ORDERS?>";
	const urlApiBudgets = "<?=URL_DOMAIN.API_BUDGETS?>";
	const urlApiProducts = "<?=URL_DOMAIN.API_PRODUCTS?>";
	const urlApiProdTemplates = "<?=URL_DOMAIN.API_PRODUCTS_TEMPLATES?>";

	const globalDebug = <?= DEBUG_MODE ? 'true' : 'false' ?>;
	const globalDebugStack = <?= DEBUG_MODE_STACK ? 'true' : 'false' ?>;

</script>

<?php 

$arrFieldsNotify = ['arrNotifyMessage'];
$arrNotify = [];

foreach ($_POST as $key => $value) {
    if (in_array($key, $arrFieldsNotify)) {
        $arrNotify[$key] = $value;
    }
}

if (count($arrNotify)) {
	?>
	<script>

		$(document).ready(function() {
			const arrNotifyMessage = <?= $arrNotify['arrNotifyMessage'] ?>;

			arrNotifyMessage.forEach(notifyMessage => {

				$.notify(notifyMessage.message, notifyMessage.type ? notifyMessage.type : 'info');
				
			});

		});

	</script>
	<?php
}
?>

<?php include_once "view/commons/modalMessage.php"; ?>
<script type="module" src="/assets/js/site/script.js"></script>
