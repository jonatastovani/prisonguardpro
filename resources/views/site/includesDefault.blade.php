
<script>
    const baseUrl = 'http://172.14.239.101';
    const urlApi = `${baseUrl}/api`;
	const urlVersion = "{{ config('sistema.versionApi') }}";
	const urlApiVersion = urlApi + urlVersion;
	const urlRefArtigos = `${urlApiVersion}/ref/artigos`;
	const urlLogin = urlApi + "/auth";
	console.log(urlLogin);
</script>

<?php 

$arrFieldsNotify = ['arrNotifyMessage'];
$arrNotify = [];

foreach ($_POST as $key => $value) {
    if (in_array($key, $arrFieldsNotify)) {
        $arrNotify[$key] = $value;
    }
}

if (count($arrNotify)) { ?>
	<script>

		$(document).ready(function() {
			const arrNotifyMessage = <?= $arrNotify['arrNotifyMessage'] ?>;
			
			arrNotifyMessage.forEach(notifyMessage => {

				$.notify(notifyMessage.message, notifyMessage.type ? notifyMessage.type : 'info');
				
			});

		});

	</script> <?php
}