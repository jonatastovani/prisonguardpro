<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    // $permissoesNecessarias = array(7);//array(3,4,5,6,7);
    // $redirecionamento = "../acesso_negado.php";
    // $blnPermitido = false;

    // $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "DesignacaoDesligamento-".date('YmdHis').".pdf";
    
    // echo var_dump($_GET);

    $idsmudanca = isset($_GET['idsmudanca'])?$_GET['idsmudanca']:0;

    $idsmudanca = explode(',',$idsmudanca);
    
    // pre_r($idsmudanca); // exit();

    $params = [];
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $results = [];
        $params = [];

        $where = "";
        foreach($idsmudanca as $id){
            if($where==""){
                $where = "?";
            }else{
                $where .= ",?";
            }
            array_push($params,$id);
        }

        // Busca a última cela que o preso está, retornando consulta para o início da cela somente ou início e término caso o preso for removido da unidade
        $sql = "SELECT 1 TABELA, CMUD.*, CD.NOME, CD.MATRICULA, RC.NOME NOMERAIO, TU.NOME NOMETURNO, BOL.DATABOLETIM, BOL.NUMERO, date_format(BOL.DATABOLETIM,'%d de %M de %Y') DATAEXTENSA, BOL.IDDIRETOR, RCET.DESCRICAOPOSTO
        FROM cadastros_mudancacela CMUD
        INNER JOIN entradas_presos EP ON EP.ID = CMUD.IDPRESO
        INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
        INNER JOIN chefia_boletim BOL ON BOL.ID = CASE WHEN CMUD.IDBOLETIMSAIDA IS NOT NULL THEN CMUD.IDBOLETIMSAIDA ELSE CMUD.IDBOLETIMENTRADA END
        INNER JOIN tab_turnos TU ON TU.ID = BOL.IDTURNO
        INNER JOIN tab_raioscelas RC ON RC.ID = CMUD.RAIO
        INNER JOIN tab_raioscelasexcecoes RCE ON RCE.IDRAIO = RC.ID
        INNER JOIN tab_raioscelasexcecoestipo RCET ON RCET.ID = RCE.IDTIPO
        WHERE  MD5(CMUD.ID) IN ($where) AND 
        RCE.IDRAIO = CMUD.RAIO AND RCE.CELA = CMUD.CELA AND RCE.DATAINICIO <= CMUD.DATACADASTRO AND (RCE.DATATERMINO >= CMUD.DATACADASTRO OR RCE.DATATERMINO IS NULL);";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);
        $resultado = $stmt->fetchAll();

        // echo "<p>1º Consulta ".count($resultado)."</p>";

        if(count($resultado)){
            $results = $resultado;
        }

        // Busca se a cela anterior ele estava em uma cela de remissão, para então poder fazer o papel de encerramento daquela contagem de remissão. Se ele for para outra cela de remissão então irá se retornar alguma informação na primeira consulta
        foreach($idsmudanca as $id){
            $sql = "SELECT 2 TABELA, CMUD.*, CD.NOME, CD.MATRICULA, RC.NOME NOMERAIO, TU.NOME NOMETURNO, BOL.DATABOLETIM, BOL.NUMERO, date_format(BOL.DATABOLETIM,'%d de %M de %Y') DATAEXTENSA, BOL.IDDIRETOR, RCET.DESCRICAOPOSTO
            FROM cadastros_mudancacela CMUD
            INNER JOIN entradas_presos EP ON EP.ID = CMUD.IDPRESO
            INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN chefia_boletim BOL ON BOL.ID = CASE WHEN CMUD.IDBOLETIMSAIDA IS NOT NULL THEN CMUD.IDBOLETIMSAIDA ELSE CMUD.IDBOLETIMENTRADA END
            INNER JOIN tab_turnos TU ON TU.ID = BOL.IDTURNO
            INNER JOIN tab_raioscelas RC ON RC.ID = CMUD.RAIO
            INNER JOIN tab_raioscelasexcecoes RCE ON RCE.IDRAIO = CMUD.RAIO
            INNER JOIN tab_raioscelasexcecoestipo RCET ON RCET.ID = RCE.IDTIPO
            WHERE MD5(CMUD.ID) < ? AND CMUD.IDPRESO = (SELECT CMUD2.IDPRESO FROM cadastros_mudancacela CMUD2 WHERE MD5(CMUD2.ID) = ?) AND RCE.IDRAIO = CMUD.RAIO AND RCE.CELA = CMUD.CELA AND RCE.DATAINICIO <= CMUD.DATACADASTRO AND (RCE.DATATERMINO >= CMUD.DATACADASTRO OR RCE.DATATERMINO IS NULL)  AND RCET.CELAREMISSAO = 1 AND RCE.IDEXCLUSOREGISTRO IS NULL LIMIT 1;";
            $params = [$id,$id];

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();

            // echo "<p>2º Consulta ".count($resultado)."</p>";
            if(count($resultado)){
                if(count($results)){
                    $results = array_merge($results,$resultado);
                }else{
                    $results = $resultado;
                }
            }
        }
/*
        foreach($idsmudanca as $id){
            $sql = "SELECT CMUD.* FROM cadastros_mudancacela CMUD
            INNER JOIN tab_raioscelasexcecoes RCE ON RCE.IDRAIO = CMUD.RAIOALTERADO
            INNER JOIN tab_raioscelasexcecoestipo RCET ON RCET.ID = RCE.IDTIPO
            WHERE MD5(CMUD.ID) < ? AND CMUD.IDPRESO = (SELECT CMUD2.IDPRESO FROM cadastros_mudancacela CMUD2 WHERE MD5(CMUD2.ID) = ?) AND RCE.IDRAIO = CMUD.RAIOALTERADO AND RCE.CELA = CMUD.CELAALTERADO AND RCE.DATAINICIO <= CMUD.DATAATUALIZACAO AND (RCE.DATATERMINO >= CMUD.DATAATUALIZACAO OR RCE.DATATERMINO IS NULL)  AND RCET.CELAREMISSAO = 1 AND RCE.IDEXCLUSOREGISTRO IS NULL LIMIT 1;";
            $params = [$id,$id];

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();

            echo "<p>3º Consulta ".count($resultado)."</p>";
            if(count($resultado)){
                if(count($results)){
                    $results = array_merge($results,$resultado);
                }else{
                    $results = $resultado;
                }
            }
        }
*/

        if(count($results)==0){
            echo "<h1>A consulta não obteve resultados. Se o problema persistir contate o programador.</h1>";
            exit();
        }
        // echo "<p>Todas consultas ".count($results)."</p>";
        // pre_r($results);exit();

        //Busca dados do diretor para a assinatura
        $params=[$results[0]['IDDIRETOR']];

        $sql = "SELECT PERM.NOME NOMEPERMISSAO, PERM.NOMECOMPLETO NOMECOMPLETOPERMISSAO, US.NOME NOMEDIRETOR, USPERM.SUBSTITUTO
        FROM tab_usuariospermissoes USPERM
        INNER JOIN tab_usuarios US ON US.ID = USPERM.IDUSUARIO
        INNER JOIN tab_permissoes PERM ON PERM.ID = USPERM.IDPERMISSAO
        WHERE USPERM.ID = ?;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);
        $resultadodiretor = $stmt->fetchAll();

        if(count($resultadodiretor)==0){
            echo "<h1>Diretor não encontrado. Não será possível gerar o documento sem essa informação.</h1>";
            exit();
        }

        $nomepermissao = $resultadodiretor[0]['NOMECOMPLETOPERMISSAO'];
        $nomediretor = $resultadodiretor[0]['NOMEDIRETOR'];
        $substituto = $resultadodiretor[0]['SUBSTITUTO'];
        if($substituto==1){
            $nomepermissao .= " - Subst.";
        }

        if(count($results)){?>

            <table style="width: 100%;"> <?php
                
                $larguramax = '';
                if(count($results)==1){
                    // Quando é somente um registro tem que por isso senão ocupa a página toda
                    $larguramax = "max-width: 49%;";
                }

                for($i=0;$i<count($results);$i++){
                    $nome=$results[$i]['NOME'];
                    $matricula=$results[$i]['MATRICULA']!=null?midMatricula($results[$i]['MATRICULA'],3):"N/C";
                    $raio=$results[$i]['NOMERAIO'];
                    $cela=$results[$i]['CELA'];
                    $raiocela = "$raio/$cela";
                    $dataentrada=$results[$i]['DATACADASTRO'];
                    $descricao=$results[$i]['DESCRICAOPOSTO'];
                    $dataentradanormal=retornaDadosDataHora($dataentrada,2);
                    $datatermino=$results[$i]['DATAATUALIZACAO']!=null?$results[$i]['DATAATUALIZACAO']:'';
                    $dataterminonormal=$datatermino!=''?retornaDadosDataHora($datatermino,2):'';
                    $dias=$datatermino!=''?retornaDiferencaDeDataEHora($datatermino,$dataentrada,7):'';
                    $dataextensa=$results[$i]['DATAEXTENSA'];
                    $tabela=$results[$i]['TABELA'];
                    $nometurno = $results[0]['NOMETURNO'];
                    $numeracao = ArrumaNumeroBoletim($results[0]['NUMERO'],retornaDadosDataHora($results[0]['DATABOLETIM'],5));
                    
                    $border = "border-left: 2px dashed black;";
                    if($i % 2 == 0){
                        $border = "border-right: 2px dashed black;"; ?>
                        <tr style=" vertical-align: text-top;"> <?php
                    } ?>

                        <td style="height: 95%; padding: 15px; border: none; <?=$border?> width: 49%;">

                            <div style="<?=$larguramax?>">
                                <div class="cabecalho">
                                    <h2 class="secretaria" style="font-size: 9; padding-top: 20px;">SECRETARIA DA ADMINISTRAÇÃO PENITENCIÁRIA</h2>
                                    <h2 class="coordenadoria" style="font-size: 7;"><?=$Coordenadoria_unidade?></h2>
                                    <h1 class="cdp" style="margin-top: 35px; font-size: 13;"><?=mb_strtoupper($Nome_unidade)?></h1>
                                    <h1 class="cdp" style="margin-top: 15px; font-size: 12;"><?=$Nome_atribuido?></h1>
                                    <div class="logosap" style="right: 5px;">
                                        <div><img src="../imagens/logo-sap.png" alt="" style="width: 75%; padding-left: 15px;"></div>
                                    </div>
                                </div> <?php
                                if($datatermino==''){ ?>
                                    <h3 class="titulo">COMUNICADO DE DESIGINAÇÃO</h3>
                                    <p  style="text-align: justify; line-height: 1.8em;">Solicito a Vossa Senhoria, a DESIGINAÇÃO para serviço do detento: <b><?=$nome?></b>, Matrícula: <b><?=$matricula?></b>, que prestará serviço no posto de <b><?=$descricao?></b> do Módulo: <b><?=$raiocela?></b>. Conforme BOLETIM DIÁRIO Nº <b><?=$numeracao?></b>.<?php
                                }else{ ?>
                                    <h3 class="titulo">COMUNICADO DE DESLIGAMENTO</h3>
                                    <p style="text-align: justify; line-height: 1.8em;">Solicito a Vossa Senhoria, o DESLIGAMENTO de serviço do detento: <b><?=$nome?></b>, Matrícula: <b><?=$matricula?></b>, pelo motivo abaixo relatado, sendo que o mesmo encontrava prestando serviço no posto de <b><?=$descricao?></b> no Módulo: <b><?=$raiocela?></b>. Conforme BOLETIM DIÁRIO Nº <b><?=$numeracao?></b>.</p>
                                
                                    <p>Motivo:</p>
                                    <p>O preso apresentou <b><?=$dias?></b> dia(s) de trabalho. Desde <b><?=$dataentradanormal?></b> a <b><?=$dataterminonormal?></b></p><?php
                                } ?>
                                <p class="align-rig"><?=$nometurno?> - <?=$dataextensa?></p>
                                <hr style="width: 50%; text-align: center; margin-top: 50px;">
                                <p style="margin: 0; text-align: center;">Responsável do Raio</p>
                                <div style="width: 100%; padding-top: 50px;">
                                    <p class="align-cen padding-margin-0"><?=$nomediretor?></p>
                                    <p class="align-cen padding-margin-0"><?=$nomepermissao?></p>
                                </div>
                            </div>
                        </td>

                    <?php

                    if(($i % 2 > 0) || ($i+1)==count($results)){ ?>
                        </tr> <?php
                    }

                } ?>
            </table> <?php
        }
    }else{
        echo $conexaoStatus;
        exit();
    }
