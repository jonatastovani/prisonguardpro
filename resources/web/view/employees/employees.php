<?php
require_once "assets/functions/commonsFunctions.php";
require_once "assets/functions/popFunctions.php";

include_once "employeesHome.php";
array_shift($url);

$arrayPopups = ['departments', 'roles', 'employees'];
$arrayActions = ['POST', 'PUT', 'DELETE'];

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

				case 'departments':
					$consultResult = $commonFunctions->curlConsult(array('urlApi' => API_WORKFORCE_DEPARTMENTS, 'id' => $idSearch));
					break;

				case 'roles':
					$consultResult = $commonFunctions->curlConsult(array('urlApi' => API_WORKFORCE_ROLES, 'id' => $idSearch));
					break;

				case 'employees':
					$consultResult = $commonFunctions->curlConsult(array('urlApi' => API_WORKFORCE_EMPLOYEES, 'id' => $idSearch));
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