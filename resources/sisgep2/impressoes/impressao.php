<?php
    //Para poder deixar em maiúscula as letras (sem isso a acentuação não fica maiúscula)
    //$encoding = mb_internal_encoding(); // ou UTF-8, ISO-8859-1...
    //echo mb_strtoupper("virá", $encoding); // retorna VIRÁ

    ob_start();
    session_start();
    $nomearquivo = "documento".date('YmdHis').".pdf";
    
    //Opção 1 = Sem cabeçalho, sem rodapé, orientação retrato
    //Opção 2 = Cabeçalho, rodapé sem numeração, orientação retrato
    //Opção 3 = Somente Rodapé, orientação retrato
    //Opção 4 = Somente Cabeçalho, orientação retrato
    //Opção 5 = Sem cabeçalho, sem rodapé, Borda estreita para carteirinhas, orientação retrato
    //Opção 6 = Sem cabeçalho, sem rodapé, orientação paisagem
    //Opção 7 = Cabeçalho, rodapé sem numeração, orientação paisagem
    //Opção 8 = Somente Rodapé, orientação paisagem
    //Opção 9 = Somente Cabeçalho, orientação paisagem
    //Opção 10 = Cabeçalho, rodapé, numeração, Bordas laterais estreitas, orientação paisagem
    //Opção 11 = Cabeçalho, rodapé, numeração, com espaço para assinatura no final de todas as folhas, orientação retrato
    //Opção 12 = Sem cabeçalho, sem rodapé, Borda estreita, orientação paisagem
    //Opção não informada = Insere cabeçalho e rodapé com numeração, orientação retrato
    $opcaocabecalho = isset($_GET['opcaocabecalho'])?$_GET['opcaocabecalho']:true;
    // $opcaocabecalho = md5(12);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento SISGEP 2.0</title>
    <link rel="shortcut icon" href="impressao.ico" type="image/x-icon">
    <style>
    /**{
        padding: 0px;
        margin: 0px;
    }*/

    <?php
        if(in_array($opcaocabecalho,array(true,md5(2),md5(4),md5(7),md5(9)),true)){ ?>
            @page{
                margin: 100px 20px 75px;
            }
        <?php }
        elseif(in_array($opcaocabecalho,array(md5(3),md5(8)),true)){ ?>
            @page{
                margin: 10px 20px 75px;
            }
        <?php }
        elseif(in_array($opcaocabecalho,array(md5(1),md5(6)),true)){ ?>
            @page{
                margin: 20px 10px 0px;
            }
        <?php }
        elseif(in_array($opcaocabecalho,array(md5(5)),true)){ ?>
            @page{
                margin: 10px -5px 0px;
            }
        <?php }
        elseif(in_array($opcaocabecalho,array(md5(10)),true)){ ?>
            @page{
                margin: 80px 0px 75px;
            }
        <?php }
        elseif(in_array($opcaocabecalho,array(md5(11)),true)){ ?>
            @page{
                margin: 100px 20px 135px;
            }
            <?php }
        elseif(in_array($opcaocabecalho,array(md5(12)),true)){ ?>
            @page{
                margin: 20px -5px;
            }
        <?php }

        //Opções do modo retrato
        $retrato = array(true,md5(1),md5(2),md5(3),md5(4),md5(5),md5(11));
        //verifica se é retrato ou paisagem
        if(in_array($opcaocabecalho,$retrato,true)){
            $papelorientacao = 'portrait';
        }else{
            $papelorientacao = 'landscape';
        }
            // $papelorientacao = 'portrait';
        
    ?>

    header{
        position: fixed;
        left: 0px; 
        top: -70px;
        right: 0px;
    }

    <?php if(in_array($opcaocabecalho,array(md5(11)),true)){ ?>
        footer{
            position: fixed;
            width: 100%;
            bottom:-120px;
            height: 60px;
            left: 0px;
            right: 0px;
        }<?php
    }else{ ?>
        footer{
            position: fixed;
            width: 100%;
            bottom:-60px;
            height: 60px;
            left: 0px;
            right: 0px;
        }<?php
    }?>
   
    footer .page::after {
        content: counter(page, my-sec-counter);
    }

    footer p {
        font-size: 8pt;
        text-align: center;
    }
    
    .assinatura{
        position: fixed;
        width: 100%;
        bottom:-75px;
        height: 60px;
    }
    
    .assinatura-final{
        position: fixed;
        width: 100%;
        bottom:10px;
    }
    
    body {
        margin: auto;
        background-color: white;
        /*max-width: 800px;*/
        font-family: Arial, Helvetica, sans-serif;
        color:  black;
        font-size: 12pt;
        padding: 0px 30px 5px;
    }

    .cabecalho{
        position: relative;
        text-align: center;
        text-align: center;
        line-height: 5px;
    }

    .logosap {
        position: absolute;
        top: 0;
        right: 3%;
        width: 100px;
    }

    .logosap img {
        width: 100%;
    }

    .coordenadoria, .secretaria {
        color: rgb(126, 124, 124);
        font-size: 8pt;
    }

    .coordenadoria {
        font-style: italic;
    }

    .cdp {
        font-size: 12pt;
    }

    .titulo {
        font-size: 16pt;
        padding-bottom: 40px;
        text-align: center;
        justify-items: center;
    }

    .indent {
        text-indent: 50px;
    }

    .align-cen {
        text-align: center;
    }

    .align-rig {
        text-align: right;
    }

    .align-lef {
        text-align: left;
    }

    .align-jus {
        text-align: justify;
    }

    .padding-margin-0{
        padding: 0px;
        margin: 0px;
    }
    td, th {
        border-collapse: collapse;
        border: 1px solid black;
    }

    .com-borda{
        border: 1px solid black;
    }

    .sem-borda{
        border: none;
    }

    .intercalado tr:nth-child(even) {
        background:lightgray;
    }

    .destaque-atencao{
        font-weight: bold;
        color: lightyellow;
        background-color: red;
    }

    .nowrap {
        white-space: nowrap;
    }

    </style>
</head>
<body>
    <header>
        <?php
            include_once "../configuracoes/conexao.php";
            include_once "../funcoes/funcoes.php";
            include_once "consulta_unidade_impressao.php";
            if(in_array($opcaocabecalho,array(true,md5(2),md5(4),md5(7),md5(9),md5(10),md5(11)),true)){
                include_once "cabecalho_impressao.php";
            }
        ?>
    </header>
    
    <footer>
        <?php
            if(in_array($opcaocabecalho,array(true,md5(2),md5(3),md5(7),md5(8),md5(10),md5(11)),true)){
                include_once "rodape_impressao.php";
            }
        ?>
    </footer>

    <main>
        <article> <?php
            switch($_GET['documento']){
                case md5('entrada_presos'):
                    include_once "inclusao/entrada_presos.php";
                    break;
                case md5('digitais_presos'):
                    include_once "inclusao/digitais_presos.php";
                    break;
                case md5('termo_abertura'):
                    include_once "cimic/termo_abertura.php";
                    break;
                case md5('termo_declaracao'):
                    include_once "inclusao/termo_declaracao.php";
                    break;
                case md5('kit_entregue'):
                    include_once "inclusao/kit_entregue.php";
                    break;
                case md5('carteirinha'):
                    include_once "inclusao/carteirinha.php";
                    break;
                case md5('oficio_apresentacao'):
                    include_once "cimic/oficio_apresentacao.php";
                    break;
                case md5('oficio_transferencia'):
                    include_once "cimic/oficio_transferencia.php";
                    break;
                case md5('oficio_escolta'):
                    include_once "cimic/oficio_escolta.php";
                    break;
                case md5('ordem_saida_presos'):
                    include_once "cimic/ordem_saida_presos.php";
                    break;
                case md5('planilha_envio_transf'):
                    include_once "cimic/planilha_envio_transf.php";
                    break;
                case md5('planilha_recebimento_transf'):
                    include_once "cimic/planilha_recebimento_transf.php";
                    break;
                case md5('imp_sau_medic_ass_entregues'):
                    include_once "saude/imp_sau_medic_ass_entregues.php";
                    break;
                case md5('imp_sau_medic_ass_entregar'):
                    include_once "saude/imp_sau_medic_ass_entregar.php";
                    break;
                case md5('imp_sau_medic_ass'):
                    include_once "saude/imp_sau_medic_ass.php";
                    break;
                case md5('imp_chef_cont'):
                    include_once "chefia/imp_chef_cont.php";
                    break;
                case md5('imp_chef_proced'):
                    include_once "chefia/imp_chef_proced.php";
                    break;
                case md5('imp_escala'):
                    include_once "chefia/imp_escala.php";
                    break;
                case md5('imp_chef_requisicao'):
                    include_once "chefia/imp_chef_requisicao.php";
                    break;
                case md5('imp_chef_designacao'):
                    include_once "chefia/imp_chef_designacao.php";
                    break;
                case md5('imp_chef_boletim'):
                    include_once "chefia/imp_chef_boletim.php";
                    break;
                default:
                    header('Location: ../principal.php');
            } ?>
        </article>
    </main>

</body>
</html>
<?php

//unset($GLOBALS['conexao']);

$html = ob_get_clean(); //Limpar o buffer
//$html = utf8_encode($html);

// echo $html;exit();
use Dompdf\Dompdf;
use Dompdf\Options;

require_once "../dompdf/autoload.inc.php";

$options = new Options();
$options->set(array('isRemoteEnabled'=>TRUE));
$dompdf = new DOMPDF($options);

$dompdf->loadHtml($html); //Escreve o html
$dompdf->setPaper('A4', $papelorientacao);
$dompdf->render();
$dompdf->stream($nomearquivo,array("Attachment"=>false));

exit();