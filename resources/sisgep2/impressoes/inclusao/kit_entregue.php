<?php

//Verifica se o usuário tem a permissão necessária
$permissoesNecessarias = array(9,21);
$redirecionamento = "../acesso_negado.php";
$blnPermitido = false;

$blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
//$nomearquivo = "TermoAbertura-".date('YmdHis').".pdf";

    $idkitentregue = isset($_GET['idkitentregue'])?$_GET['idkitentregue']:0;
    $retorno = [];

    if(empty($idkitentregue)){
        echo 'Nenhum ID de Movimentação foi informado';
        exit();
    }

    $idkit = explode(',', $idkitentregue) ;
    if(count($idkit)>1){
        $nomearquivo = "KitEntregue-".count($idkit)."Presos-".date('YmdHis').".pdf";
    }else{
        $nomearquivo = '';
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        foreach($idkit as $kit){
            $sql = "SELECT CD.NOME, CD.MATRICULA, KI.ID IDITEM, concat(KI.NOME, ' (', CASE WHEN ITEMNOVO = 1 THEN 'Novo' WHEN ITEMNOVO = 0 THEN 'Usado' END, ')') NOMEEXIBIR,
            KIE.ID IDITEMENTREGUE, KIE.QTD, date_format(KE.DATAENTREGA,'%d de %M de %Y') DATAFINALPAGINA
            FROM inc_kitentregue KE 
            INNER JOIN entradas_presos EP ON EP.ID = KE.IDPRESO
            INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN inc_kititensentregue KIE ON KIE.IDKIT = KE.ID
            INNER JOIN inc_kititens KI ON KI.ID = KIE.IDITEM
            WHERE MD5(KE.ID) = :idkit AND KIE.IDEXCLUSOREGISTRO IS NULL AND KIE.DATAEXCLUSOREGISTRO IS NULL ORDER BY KI.NOME";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('idkit', $kit, PDO::PARAM_STR);
            $stmt->execute();

            $resultado = $stmt->fetchAll();
            if(count($resultado)){
                if(empty($nomearquivo)){
                    $nomearquivo = "KitEntregue-".$resultado[0]['NOME']."-".date('YmdHis').".pdf";
                } ?>
                <table>
                    <h1 class="titulo">RECIBO DE KIT DE PERTENCES</h1> 
                    
                    <span style="margin-bottom: 20px;">Nome: <b><?=$resultado[0]['NOME']?></b></span><br>
                    <span>Matrícula: <b><?=midMatricula($resultado[0]['MATRICULA'],3)?></b></span>
                    <p class="indent">Declaro que nesta data recebi o(s) pertence(s) abaixo descito(s):</p>

                    <ol> <?php
                        foreach($resultado as $item){ ?>
                            <li><?=$item['NOMEEXIBIR']?> = <?=$item['QTD']?></li> <?php
                        } ?>
                    </ol>

                    <p style="margin: 20px 0px;">Estou ciente que danos ao patrimônio acarretara em sanções disciplinares prevista em lei.</p>
                    <p class="align-rig" style="padding: 50px 0px;"><?= $Cidade_unidade.', '. $resultado[0]['DATAFINALPAGINA']?></p>

                    <div class="align-cen">
                        <div class="align-cen" style="width: 50%; display: inline-block;">
                            <hr>
                            <b><?=$resultado[0]['NOME']?></b>
                        </div>
                    </div>

                </table> <?php
            }
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
