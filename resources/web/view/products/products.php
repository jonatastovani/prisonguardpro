<?php
require_once "assets/functions/commonsFunctions.php";
require_once "assets/functions/popFunctions.php";

array_shift($url);

$arrayPopups = ['items'];
$arrayActions = ['POST', 'PUT', 'DELETE'];

if (!count($url) || $url[0] == 'home' || in_array($url[0], $arrayPopups)) {

	include_once "productsHome.php";

	if (count($url) && in_array($url[0], $arrayPopups)) {

		$popForm = new popFunctions();

		$popName = $url[0];
		array_shift($url);

		if (count($url) && $url[0] != '' && !in_array($url[0], $arrayActions)) {

			$idSearch = $url[0];
			array_shift($url);
			$popAction = (count($url) && $url[0] != '' && in_array($url[0], $arrayActions)) ? $url[0] : '';

			$commonFunctions = new CommonsFunctions();

			try {
				$consultResult = [];

				switch ($popName) {

					case 'items':
						$consultResult = $commonFunctions->curlConsult(array('urlApi' => API_PRODUCTS_ITEMS, 'id' => $idSearch));
						break;
				}

				if ($consultResult->getStatusCode() === 200) {
					$popId = $consultResult->getData()['id'];
					echo $popForm->insertHiddenPopData(array('popName' => $popName, 'popId' => $popId, 'popAction' => $popAction));
				} else {
					echo $popForm->insertHiddenPopData(array('popName' => $popName, 'popAction' => $popAction));
				}
			} catch (Exception $e) {
				echo "Ocorreu um erro ao consultar a API: " . $e->getMessage();
			}
		} else {
			echo $popForm->insertHiddenPopData(array('popName' => $popName));
		}
	}
} else if ($url[0] == 'templates') {

	array_shift($url);

	if (!count($url)) {

		include_once "showTemplates.php";
	} else {

		$commonFunctions = new CommonsFunctions();

		try {
			$consultResult = $commonFunctions->curlConsult(array('urlApi' => API_PRODUCTS_TEMPLATES, 'id' => $url[0]));

			if ($consultResult->getStatusCode() === 200) {
				$result = $consultResult->getData();
				include_once "registerTemplates.php";
			} else {
				$redirect = isset($_POST['redirect-previous']) ? $_POST['redirect-previous'] : '/products/templates';
				$message =  isset($_POST['redirect-previous']) ? null : "Retornar à página de modelos";
				$commonFunctions->includePage404($redirect, $message);
			}
		} catch (Exception $e) {
			echo "Ocorreu um erro ao consultar a API: " . $e->getMessage();
		}
	}
} else {

	include_once "productsHome.php";

}
