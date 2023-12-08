<?php
//Sessão
session_start();

//Busca as funções
include_once "configuracoes/conexao.php";
include_once "funcoes/funcoes.php";

if(!$_SESSION['logado']){
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema RS</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/setores-style.css">
    <link rel="stylesheet" href="css/tamanhos.css">
    <link rel="stylesheet" href="css/jquery.Jcrop.min.css">
    <!-- <link rel="stylesheet" href="css/tab-style.css"> -->

    <!-- Estilos dos popups -->
    <link rel="stylesheet" href="css/popup-style.css">

    <?php
        //Já foi importado o moment js
        //Importanto o Moment para trabalho com datas
        //<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    ?>
</head>
<body>
    <!-- Scripts de javascript -->
    <script src="js/jQuery/jquery-3.6.0.min.js.js"></script>
    <script src="js/jQuery/jquery.mask.min.js"></script>
    <script src="js/jQuery/jquery.Jcrop.min.js"></script>
    <script src="js/jQuery/moment-2.29.1.min.js"></script>
    <script src="js/funcoes_comuns.js"></script>
    <script src="js/funcoes_chefia.js"></script>
    <script src="js/funcoes_inclusao.js"></script>
    <script src="js/funcoes_cimic.js"></script>
    <script src="js/funcoes_saude.js"></script>
    <script src="js/funcoes_rol.js"></script>
    <script src="js/funcoes_funcionarios.js"></script>
    <script src="js/script.js"></script>

    <?php
    
    //Cabeçalho principal
    include_once "prin_cabecalho.php";

    $menuop = isset($_GET['menuop'])?$_GET['menuop']:"selsetores";
    /*
    URL AMIGAVEL
    $getUrl = strip_tags(trim(filter_input(INPUT_GET,'url',FILTER_DEFAULT)));
    $setUrl = empty($getUrl)?'principal':$getUrl;

    $url = explode('/', $setUrl);
    $url[1] = empty($url[1])?'sel':$url[1];*/

    ?>

    <main>
    <?php
        switch($menuop){
            //Selecionar o setor que vai acessar
            case "selsetores":
                include_once "selecionar_setores.php";
                break;

            case "cimic":
            case "cim_incluir_presos";
            case "cim_presos_pendentes";
            case "cim_alt_qualificativa_preso";
            case 'cim_movimentacoes_transf':
            case 'cim_movimentacoes_apres':
            case 'cim_gerenciar_transf':
            case 'cim_gerenciar_apres':
            case 'cim_movimentacoes_apres_int':
            case 'cim_ger_excl':
                include_once "setores/cimic/cimic_principal.php";
                break;

            case "chefia":
            case "chef_numeracaoboletim":
            case "chef_ger_raio":
            case "chef_ger_chefia":
            case "chef_ger_atend":
            case "chef_funcionarios":
            case "chef_escala":
            case "chef_batepisograde":
                include_once "setores/chefia/chefia_principal.php";
                break;

            case "inclusao":
            case "inc_incluiralterar_presos";
            case "inc_gerenciar_presos";
            case "inc_alt_qualificativa_preso";
            case "inc_foto_preso";
            case "inc_gerenciar_pertences";
            case "inc_gerenciar_sedex";
                include_once "setores/inclusao/inclusao_principal.php";
                break;

            case "saude":
            case "sau_ger_atend":
            case "sau_atend":
            case "sau_ger_assis":
                include_once "setores/saude/saude_principal.php";
                break;

            case "rol":
            case "rol_ger":
            case "rol_alt_vis":
            case "rol_ent_sai":
                include_once "setores/rol/rol_principal.php";
                break;

            case "seldoc":
            case "imp_termo_abertura";
            case "imp_entrada_presos";
            case "imp_digitais_presos";
            case "imp_termo_declaracao";
            case "imp_kit_entregue";
            case "imp_carteirinha";
                include_once "impressoes/imprimir_doc_principal.php";
                break;

            case "acesso_negado":
                include_once "acesso_negado.php";
                break;

            case "teste":
                include_once "teste.php";
                break;

            case "testeajax":
                include_once "testeajax.php";
                break;

            default:
                include_once "selecionar_setores.php";
        }
    ?>
    </main>
    <script src="js/script_final.js"></script>
    <script src="js/principal.js"></script>
</body>
</html>