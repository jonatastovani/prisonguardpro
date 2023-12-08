<?php
    include_once "rol_cabecalho.php";
?>
<article>
    <?php
        $menuop = isset($_GET['menuop'])?$_GET['menuop']:'home';
    
        switch($menuop){
            case "rol_ger":
                include_once "rol_gerenciar.php";
                break;

            case "rol_alt_vis":
                include_once "rol_alt_visitante.php";
                break;

            case "rol_ent_sai":
                include_once "rol_entradasaida.php";
                break;

            default:
            }
        ?>
</article>
        
<div style="text-align: center;">
    <a href="javascript:history.go(-1)" id="voltar" rel="prev">Voltar</a>
</div>