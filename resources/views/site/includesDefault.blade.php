
<script>
    const baseUrl = "{{ env('HOST', 'http://prisonguardpro.test') }}";
    const globalDebug = {{ config('sistema.globalDebug') ? 'true' : 'false' }};
    const globalDebugStack = {{ config('sistema.globalDebugStack') ? 'true' : 'false' }};

    const urlApi = `${baseUrl}/api`;
    const urlVersion = "{{ config('sistema.versionApi') }}";
    const urlApiVersion = `${urlApi}${urlVersion}`;
    const urlLogin = `${urlApi}/auth`;

    const urlIncEntrada = `${urlApiVersion}/inclusao/entradas`;
    const urlIncEntradaPreso = `${urlApiVersion}/inclusao/entradas/presos`;

    const urlPresoConvivio = `${urlApiVersion}/ref/presos/convivios`;

    const urlRefArtigos = `${urlApiVersion}/ref/artigos`;
    const urlRefCabeloTipo = `${urlApiVersion}/ref/cabelotipos`;
    const urlRefCabeloCor = `${urlApiVersion}/ref/cabelocores`;
    const urlRefCidades = `${urlApiVersion}/ref/cidades`;
    const urlRefCrenca = `${urlApiVersion}/ref/crencas`;
    const urlRefCutis = `${urlApiVersion}/ref/cutis`;
    const urlRefEscolaridade = `${urlApiVersion}/ref/escolaridades`;
    const urlRefEstadoCivil = `${urlApiVersion}/ref/estadocivil`;
    const urlRefGenero = `${urlApiVersion}/ref/generos`;
    const urlRefIncOrigem = `${urlApiVersion}/ref/inclusao/origem`;
    const urlRefOlhoTipo = `${urlApiVersion}/ref/olhotipos`;
    const urlRefOlhoCor = `${urlApiVersion}/ref/olhocores`;
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