<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    $permissoesNecessarias = array(3,8);
    $redirecionamento = "../acesso_negado.php";
    $blnPermitido = false;

    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Este documento precisa ser revisado. Motivo: Tabela cadastros_movimentacoes excluída! </li>");
    echo json_encode($retorno);
    exit;

    $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "Carteirinhas-".date('YmdHis').".pdf";

    $identrada = isset($_GET['identrada'])?$_GET['identrada']:0;
    $idpreso = isset($_GET['idpreso'])?$_GET['idpreso']:0;
    $retorno = [];

    if($idpreso!=0){
        $idbusca = explode(',', $idpreso);
    }else{
        echo 'Nenhum ID de Entrada ou Preso foi informado';
        exit();
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){ 
            $params = [];
            $where = '';
            foreach($idbusca as $id){
                if(empty($where)){
                    $where = "MD5(EP.ID) = ?";
                }else{
                    $where .= " OR MD5(EP.ID) = ?";
                }
                array_push($params,$id);
            }
            //print_r(($params));exit();

                $sql = "SELECT EP.ID IDPRESO, CD.NOME, EP.MATRICULA, date_format(CM.DATAMOVIMENTACAO, '%d/%m/%Y') DATAENTRADA, GSA.NOME ORIGEM, 
                (SELECT GROUP_CONCAT(CASE EA.OBSERVACOES WHEN '' THEN ART.NOME ELSE concat(ART.NOME,'(',EA.OBSERVACOES,')') END) FROM entradas_artigos EA 
                INNER JOIN tab_artigos ART ON ART.ID = EA.IDARTIGO
                WHERE EA.IDPRESO = EP.ID AND EA.IDEXCLUSOREGISTRO IS NULL AND EA.DATAEXCLUSOREGISTRO IS NULL) ARTIGOS,
                (SELECT GROUP_CONCAT(CV.NOME) FROM cadastros_vulgos CV 
                WHERE CV.IDPRESO = EP.ID AND CV.IDEXCLUSOREGISTRO IS NULL AND CV.DATAEXCLUSOREGISTRO IS NULL) VULGOS
                FROM entradas_presos EP
                INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN cadastros_movimentacoes CM ON CM.IDPRESO = EP.ID
                INNER JOIN codigo_gsa GSA ON GSA.ID = CM.IDORIGEM
                WHERE $where";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                //$stmt->bindParam("id",$id,PDO::PARAM_STR);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                if(count($resultado)){ 
                    for($i=0;$i<count($resultado);$i++){
                        $foto = baixarFotoServidor($resultado[$i]['IDPRESO'],1,'../');?>
                        
                        <table style="margin-bottom: 15px">
                            <tr>
                                <td style="border: none;">
                                    <table style="font-size: 9pt; border-collapse: collapse; width: 355px; border: 1px solid black;">
                                        <tr style="text-align: center; font-size: 9pt;">
                                            <td colspan="2">
                                                <b><?=$Nome_unidade?></b><br>
                                                <?=$Nome_atribuido?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Nome: <b><?=$resultado[$i]['NOME']?></b><br>
                                                Matrícula: <span style="font-weight: bolder; font-size: 14pt;"><?=midMatricula($resultado[$i]['MATRICULA'],3)?></span><br>
                                                Inclusão: <b><?=$resultado[$i]['DATAENTRADA']?></b><br>
                                                Procedência: <b><?=$resultado[$i]['ORIGEM']?></b><br>
                                                Artigo: <b><?=$resultado[$i]['ARTIGOS']?></b><br>
                                                Vulgo: <b><?=$resultado[$i]['VULGOS']?></b><br>
                                                <b>Raio</b>:___ <b>Cela</b>:___
                                            </td>
                                            <td><img style="width: 120px;" src="../<?=$foto?>"></td>
                                        </tr>
                                        <tr style="text-align: right; font-size: 7pt;">
                                            <td colspan="2">ID Preso: <?=$resultado[$i]['IDPRESO']?></td>
                                        </tr>
                                    </table>
                                </td> <?php
                                
                                if(isset($resultado[++$i])){

                                    $foto = baixarFotoServidor($resultado[$i]['IDPRESO'],1,'../');?>

                                    <td style="border: none; padding-left: 10px;">
                                        <table style="font-size: 9pt; border-collapse: collapse; width: 355px; border: 1px solid black;">
                                            <tr style="text-align: center; font-size: 9pt;">
                                                <td colspan="2">
                                                    <b><?=$Nome_unidade?></b><br>
                                                    <?=$Nome_atribuido?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Nome: <b><?=$resultado[$i]['NOME']?></b><br>
                                                    Matrícula: <span style="font-weight: bolder; font-size: 14pt;"><?=midMatricula($resultado[$i]['MATRICULA'],3)?></span><br>
                                                    Inclusão: <b><?=$resultado[$i]['DATAENTRADA']?></b><br>
                                                    Procedência: <b><?=$resultado[$i]['ORIGEM']?></b><br>
                                                    Artigo: <b><?=$resultado[$i]['ARTIGOS']?></b><br>
                                                    Vulgo: <b><?=$resultado[$i]['VULGOS']?></b><br>
                                                    <b>Raio</b>:___ <b>Cela</b>:___
                                                </td>
                                                <td><img style="width: 120px;" src="../<?=$foto?>"></td>
                                            </tr>
                                            <tr style="text-align: right; font-size: 7pt;">
                                                <td colspan="2">ID Preso: <?=$resultado[$i]['IDPRESO']?></td>
                                            </tr>
                                        </table>
                                    </td> <?php
                                } ?>
                            </tr>
                        </table> <?php
                    }
                }
    }else{
        echo $conexaoStatus;
        exit();
    }
