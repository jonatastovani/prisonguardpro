
<div class="titulo-pagina">
    <h1 id="titulo">Central de Impressões</h1>
    <input type="hidden" name="tipoacao" id="tipoacao" value="<?=$tipoacao?>">
</div>

<?php
    switch($menuop){
        //Selecionar o setor que vai acessar
        case "seldoc":
            include_once "impressoes/selecionar_doc.php";
            break;

        case "imp_termo_abertura";
            include_once "impressoes/cimic/termo_abertura_selecionar.php";
            break;

        case "imp_entrada_presos";
            include_once "impressoes/inclusao/entrada_presos_selecionar.php";
            break;

        case "imp_digitais_presos";
            include_once "impressoes/inclusao/digitais_presos_selecionar.php";
            break;

        case "imp_termo_declaracao";
            include_once "impressoes/inclusao/termo_declaracao_selecionar.php";
            break;

        case "imp_kit_entregue";
            include_once "impressoes/inclusao/kit_entregue_selecionar.php";
            break;

        case "imp_carteirinha";
            include_once "impressoes/inclusao/carteirinha_selecionar.php";
            break;

        default:
        include_once "impressoes/selecionar_doc.php";
    }
?>

<div style="text-align: center; margin-top: 25px;">
    <a href="<?=$GLOBALS['urlVoltar']?>" id="voltar" rel="prev">Voltar</a>
</div>