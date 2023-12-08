<?php
    include_once "saude_cabecalho.php";
?>
<article>
    <?php
        $menuop = isset($_GET['menuop'])?$_GET['menuop']:'home';
    
        switch($menuop){
            case "sau_ger_atend":
                include_once "sau_gerenciar_atend.php";
                break;

            case "sau_atend":
                include_once "sau_atendimento.php";
                break;

            case "sau_ger_assis":
                include_once "saude_gerenciar_assis.php";
                break;

            default:
            }
        ?>
</article>
        
<div style="text-align: center;">
    <a href="javascript:history.go(-1)" id="voltar" rel="prev">Voltar</a>
</div>