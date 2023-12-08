<?php
    include_once "chefia_cabecalho.php";
?>
<article>
    <?php
        $menuop = isset($_GET['menuop'])?$_GET['menuop']:'home';
    
        switch($menuop){
            case 'chef_numeracaoboletim':
                include_once "chefia_numeracao_boletim.php";
                break;

            case 'chef_ger_raio':
                include_once "chefia_gerenciar_raio.php";
                break;
    
            case 'chef_ger_chefia':
                include_once "chefia_gerenciar.php";
                break;
    
            case 'chef_ger_atend':
                include_once "chefia_gerenciar_atend.php";
                break;
    
            case 'chef_funcionarios':
                include_once "setores/funcionarios/func_gerenciar.php";
                break;
                
            case 'chef_escala':
                $idtipoescala = 1; //Escala de Plantão Carceragem
                include_once "setores/funcionarios/func_escala.php";
                break;
                
            case "chef_batepisograde":
                include_once "chefia_batepisograde.php";
                break;

            default:
            }
        ?>
</article>
        
<div style="text-align: center;">
    <a href="javascript:history.go(-1)" id="voltar" rel="prev">Voltar</a>
</div>