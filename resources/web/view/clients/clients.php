<?php
require_once "assets/functions/commonsFunctions.php";

array_shift($url);

if (!count($url)) {

	include_once "showClients.php";
} else if ($url[0] == 'home') {

	include_once "clientsHome.php";
} else if ($url[0] == 'new') {

	$url = [];
	include_once "registerClients.php";
} else {

	$commonFunctions = new CommonsFunctions();

	try {
		$consultResult = $commonFunctions->curlConsult(array('urlApi' => API_CLIENTS, 'id' => $url[0]));

		if ($consultResult->getStatusCode() === 200) {
			$result = $consultResult->getData();
			include_once "registerClients.php";
		} else {
			$redirect = isset($_POST['redirect-previous']) ? $_POST['redirect-previous'] : '/clients';
			$message =  isset($_POST['redirect-previous']) ? null : "Retornar à página de clientes";
			$commonFunctions->includePage404($redirect, $message);
		}
	} catch (Exception $e) {
		echo "Ocorreu um erro ao consultar a API: " . $e->getMessage();
	}
}
