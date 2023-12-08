<?php
include_once "cimic_cabecalho.php";
?>
<article>
    <?php
        $menuop = isset($_GET['menuop'])?$_GET['menuop']:'home';
    
        switch($menuop){
            case 'cim_presos_pendentes':
                include_once "cim_presos_pendentes.php";
                break;
    
            case 'cim_incluir_presos':
                include_once "cim_incluir_presos.php";
                break;
    
            case 'cim_alt_qualificativa_preso':
                include_once "setores/inclusao/inc_alterar_qualificativa.php";
                break;
    
            case 'cim_movimentacoes_transf':
                include_once "cim_movimentacoes_transferencias.php";
                break;
    
            case 'cim_movimentacoes_apres':
                include_once "cim_movimentacoes_apresentacoes.php";
                break;
    
            case 'cim_gerenciar_transf':
                include_once "cim_gerenciar_transferencias.php";
                break;

            case 'cim_gerenciar_apres':
                include_once "cim_gerenciar_apresentacoes.php";
                break;
    
            case 'cim_movimentacoes_apres_int':
                include_once "cim_movimentacoes_apres_interna.php";
                break;
    
            case 'cim_ger_excl':
                include_once "cim_gerenciar_exclusoes.php";
                break;
    
            default:
        }
    ?>
</article>
        
<div style="text-align: center;">
    <a href="javascript:history.go(-1)" id="voltar" rel="prev">Voltar</a>
</div>