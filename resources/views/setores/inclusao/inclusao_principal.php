<?php 
    include "inclusao_cabecalho.php";
?>

<article>
    <?php
        $menuop = isset($_GET['menuop'])?$_GET['menuop']:'home';
    
        switch($menuop){
            case 'inc_incluiralterar_presos':
                include_once "incluir_alterar_entrada_presos.php";
                break;

            case 'inc_gerenciar_presos':
                include_once "inc_gerenciar_entrada_presos.php";
                break;
    
            case 'inc_alt_qualificativa_preso':
                include_once "inc_alterar_qualificativa.php";
                break;
    
            case 'inc_foto_preso':
                include_once "inc_fotos_preso.php";
                break;

            case 'inc_gerenciar_pertences':
                include_once "inc_gerenciar_pertences_sedex.php";
                break;

            case 'inc_gerenciar_sedex':
                include_once "inc_gerenciar_pertences_sedex.php";
                break;


            default:
        }
    ?>
</article>
        
<div style="text-align: center;">
    <a href="javascript:history.go(-1)" id="voltar" rel="prev">Voltar</a>
</div>