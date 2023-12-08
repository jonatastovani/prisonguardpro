<?php

    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(9,48);
    $redirecionamento = "../acesso_negado.php";
    $blnPermitido = false;

    $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);

    $datareceb = isset($_GET['datareceb'])?$_GET['datareceb']:0;
    $retorno = [];

    if(empty($datareceb)){
        echo '<h1 style="margin-top: 100px;">Nenhuma Data de Recebimento ou Retorno foi informada.</h1>';
        exit();
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $sql = "SELECT CT.IDPRESO, 'verifica' NOME, EP.MATRICULA, concat(UNT.NOME, ' ', UN.NOMEUNIDADE) UNIDADE, date_format(CT.DATARETORNO, '%W, %d de %M de %Y') DATADOC, (SELECT NOME FROM tab_movimentacoestipo WHERE ID = 16) TIPOTRANSITO, EP.SEGURO, MM.NOME MOTIVO, CT.DATARETORNO DATARECEB,

        (SELECT group_concat(CASE WHEN EA.OBSERVACOES IS NULL THEN ART.NOME ELSE CONCAT(ART.NOME, ' (', EA.OBSERVACOES, ')') END SEPARATOR ', ')
        FROM entradas_artigos EA 
        INNER JOIN tab_artigos ART ON ART.ID = EA.IDARTIGO
        WHERE EA.IDPRESO = EP.ID) ARTIGOS
        
        FROM cimic_transferencias CT
        INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
        INNER JOIN cimic_transferencias_intermed CTI ON CTI.IDMOVIMENTACAO = CT.ID
        INNER JOIN tab_unidades UN ON UN.ID = CTI.IDDESTINOINTERM
        INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
        INNER JOIN tab_movimentacoestipo MT ON MT.ID = CT.IDTIPOMOV
        INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = CT.IDMOTIVOMOV
        WHERE MD5(DATARETORNO) = :dataret
        AND CT.IDEXCLUSOREGISTRO IS NULL AND CT.DATAEXCLUSOREGISTRO IS NULL
        AND CTI.PRIMEIROLOCAL = TRUE AND CTI.IDEXCLUSOREGISTRO IS NULL AND CTI.DATAEXCLUSOREGISTRO IS NULL
        
        UNION
        
        SELECT CR.IDPRESO, EP.NOME, EP.MATRICULA, concat(UNT.NOME, ' ', UN.NOMEUNIDADE) UNIDADE, date_format(CR.DATARECEB, '%W, %d de %M de %Y') DATADOC, MT.MOTIVOFINAL TIPOTRANSITO, EP.SEGURO, MM.NOME MOTIVO, CR.DATARECEB,
        
        (SELECT group_concat(CASE WHEN EA.OBSERVACOES IS NULL THEN ART.NOME ELSE CONCAT(ART.NOME, ' (', EA.OBSERVACOES, ')') END SEPARATOR ', ')
        FROM entradas_artigos EA 
        INNER JOIN tab_artigos ART ON ART.ID = EA.IDARTIGO
        WHERE EA.IDPRESO = EP.ID) ARTIGOS
        
        FROM cimic_recebimentos CR
        INNER JOIN entradas_presos EP ON EP.ID = CR.IDPRESO
        INNER JOIN tab_unidades UN ON UN.ID = CR.IDPROCEDENCIA
        INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
        INNER JOIN tab_movimentacoestipo MT ON MT.ID = CR.IDTIPOMOV
        INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = CR.IDMOTIVOMOV
        WHERE MD5(DATARECEB) = :datareceb
        
        ORDER BY UNIDADE, MATRICULA;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->bindParam('dataret',$datareceb,PDO::PARAM_STR);
        $stmt->bindParam('datareceb',$datareceb,PDO::PARAM_STR);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll();
        if(count($resultado)){
            $nomearquivo = "RecebimentoPresos-$Codigo_unidade-".date('YmdHis').".pdf";
            $datahora = $resultado[0]['DATARECEB'];
            $data = retornaDadosDataHora($datahora,2);
            $dataextensa = $resultado[0]['DATADOC'];

            $conttotal = count($resultado);
            if($conttotal==1){
                $conttotal .= ' preso';
            }else{
                $conttotal .= ' presos';
            } ?>

            <p class="padding-margin-0 align-cen" style="font-size: 9pt; font-weight: bolder;">(Recebimento de <?=$conttotal?>)</p>
            <h1 class="titulo padding-margin-0" style="color: rgb(172, 55, 55); font-weight: bolder;">Recebimento do <?=$Nome_unidade?></h1>
            <p class="padding-margin-0 align-rig" style="font-size: 9pt; font-weight: bolder;"><?=$dataextensa?></p> <?php

            $padding = 0;
            $orig = ''; ?>

            <table style="width: 100%;"> <?php
                for($i=0;$i<count($resultado);$i++){
                    $idpreso = $resultado[$i]['IDPRESO'];
                    $matric = $resultado[$i]['MATRICULA'];
                    $matricula = midMatricula($matric,3);
                    $origem = $resultado[$i]['UNIDADE'];
                    $nome = buscaDadosLogPorPeriodo($matric,'NOME',3,$data);
                    $pai = buscaDadosLogPorPeriodo($matric,'PAI',3,$data);
                    $mae = buscaDadosLogPorPeriodo($matric,'MAE',3,$data);
                    if($nome==''){
                        $nome = buscaDadosLogPorPeriodo($idpreso,'NOME',5,$data);
                    }
                    if($pai==''){
                        $pai = buscaDadosLogPorPeriodo($idpreso,'PAI',5,$data);
                    }
                    if($mae==''){
                        $mae = buscaDadosLogPorPeriodo($idpreso,'MAE',5,$data);
                    }
                    $artigos = $resultado[$i]['ARTIGOS'];
                    $motivo = $resultado[$i]['MOTIVO'];
                    $tipotransito = $resultado[$i]['TIPOTRANSITO'];
                    
                    $seguro = $resultado[$i]['SEGURO'];

                    if($orig!=$origem){
                        $contdest=0;
                        //Faz a contagem de presos para esta origem
                        for($icont=$i;$icont<count($resultado);$icont++){
                            if($resultado[$icont]['UNIDADE']==$origem){
                                $contdest++;
                            }else{
                                break;
                            }
                        }
                        if($contdest==1){
                            $contdest .= ' preso';
                        }else{
                            $contdest .= ' presos';
                        }
                        ?>
                        <table style="font-size: 8pt; border-collapse: collapse; width: 100%;">
                            <tr>
                                <td class="sem-borda" colspan="5" style="margin-top: <?=$padding?>;">Destino: <span style="font-size: 12pt; color: blue; font-weight: bolder;"><?=$origem?></span></td>
                                <td class="sem-borda" style="width: 100px; text-align: right; font-weight: bolder; color: red;"><?=$contdest?></td>
                            </tr>
                        </table> <?php
                        $orig = $origem;
                        $padding = '10px';
                    }?>
                    <tr>
                        <table style="font-size: 8pt; border-collapse: collapse; width: 100%;"> 
                            <tr style="border-top: 2px solid black; background-color: lightgoldenrodyellow;">
                                <td class="align-cen sem-borda" style="width: 6%; font-size: 9pt; font-weight: bold;"><?=$matricula?></td>
                                <td class="sem-borda" colspan="2" style="font-size: 9pt; font-weight: bold; width: 30%;"><?=$nome?></td>
                                <td class="sem-borda" style="font-size: 7pt; width: 20%;">Pai: <?=$pai?></td>
                                <td class="sem-borda" rowspan="2" style="width: 20%;">Motivo: <span style="font-size: 9pt; font-weight: bold;"><?=$motivo?></span></td>
                                <td class="sem-borda" rowspan="2" style="width: 15%;">Motivo TR: <span style="font-size: 9pt; font-weight: bold;"><?=$tipotransito?></span></td>
                            </tr>
                            <tr style="border-bottom: 2px solid black; background-color: lightgoldenrodyellow;"> <?php
                                if($seguro==1){ ?>
                                    <td class="sem-borda align-cen destaque-atencao" style="font-size: 9pt;">SEGURO</td> <?php
                                }else{ ?>
                                    <td class="sem-borda"></td> <?php
                                }
                                ?>
                                <td class="sem-borda" colspan="2" style="font-size: 7pt; width: 30%;">Art.: <?=$artigos?></td>
                                <td class="sem-borda" style="font-size: 7pt; width: 20%;">Mãe: <?=$mae?></td>
                            </tr>
                        </table>
                    </tr> <?php
                }?>
            </table> <?php
        }
    }else{
        echo $conexaoStatus;
        exit();
    }
