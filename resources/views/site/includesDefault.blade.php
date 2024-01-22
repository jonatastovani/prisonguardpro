
<script>
    const baseUrl = "{{ env('HOST', 'http://prisonguardpro.test') }}";
    const globalDebug = {{ config('sistema.globalDebug') ? 'true' : 'false' }};
    const globalDebugStack = {{ config('sistema.globalDebugStack') ? 'true' : 'false' }};

    const urlApi = `${baseUrl}/api`;
    const urlVersion = "{{ config('sistema.versionApi') }}";
    const urlApiVersion = `${urlApi}${urlVersion}`;
    const urlLogin = `${urlApi}/auth`;

    const urlRefArtigos = `${urlApiVersion}/ref/artigos`;
    const urlRefStatus = `${urlApiVersion}/ref/status`;

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