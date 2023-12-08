<?php
    $numeracao = "";
    if(in_array($opcaocabecalho,array(true,md5(3),md5(8),md5(10),md5(11)),true)){
        $numeracao = " Página <span class='page'></span>";
    }
//<hr style="padding: 0px; margin: 0px; margin-bottom: 1px;">
//<hr style="padding: 0px; margin: 0px;">
?>
<div>
    <p style="position: absolute; bottom: 35px; left: 15px;">Documento gerado em <?php echo date('d/m/Y H:i:s') ?></p>
    
    <p class="padding-margin-0">Elaborado em SISGEP 2.0</p>
    <p class="padding-margin-0"><?=$Endereco_unidade?> - <?=$Cidade_unidade?></p>
    <p class="padding-margin-0"> Tel.:<?=$Telefones_unidade?></p>
    <p class="padding-margin-0"><?=$EmailCimic_unidade?></p>
    
    <p style="position: absolute; bottom: 5px; right: 15px;"><?=$numeracao?></p>

</div>
