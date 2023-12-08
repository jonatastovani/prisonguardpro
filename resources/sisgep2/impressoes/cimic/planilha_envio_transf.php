<?php

    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(9,48);
    $redirecionamento = "../acesso_negado.php";
    $blnPermitido = false;

    $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);

    $idordem = isset($_GET['idordem'])?$_GET['idordem']:0;
    $retorno = [];

    if(empty($idordem)){
        echo '<h1 style="margin-top: 100px;">Nenhum ID de Ordem foi informado</h1>';
        exit();
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $sql = "SELECT CT.ID IDMOVIMENTACAO, CT.IDPRESO, EP.MATRICULA, concat(COND.ANOS, ' a, ', COND.MESES, ' m, ', COND.DIAS, ' d') CONDENACAO, concat(MT.MOTIVOFINAL,' - ', UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE) TIPO, COT.DATASAIDA, date_format(COT.DATASAIDA, '%W, %d de %M de %Y') DATADOC,

        (SELECT group_concat(CASE WHEN EA.OBSERVACOES IS NULL THEN ART.NOME ELSE CONCAT(ART.NOME, ' (', EA.OBSERVACOES, ')') END SEPARATOR ', ')
        FROM entradas_artigos EA 
        INNER JOIN tab_artigos ART ON ART.ID = EA.IDARTIGO
        WHERE EA.IDPRESO = EP.ID) ARTIGOS, EP.SEGURO,
        
        (SELECT concat(UNT2.NOME, ' ', UN2.NOMEUNIDADE)
        FROM cimic_transferencias_intermed CTI2 
        INNER JOIN tab_unidades UN2 ON UN2.ID = CTI2.IDDESTINOINTERM
        INNER JOIN tab_unidadestipos UNT2 ON UNT2.ID = UN2.IDTIPOUNIDADE
        WHERE CTI2.IDMOVIMENTACAO = CT.ID AND CTI2.PRIMEIROLOCAL = TRUE
        AND CTI2.IDEXCLUSOREGISTRO IS NULL AND CTI2.DATAEXCLUSOREGISTRO IS NULL) UNIDADE,
        
        (SELECT CASE WHEN CTI2.DESTINOFINAL = TRUE THEN MT2.MOTIVOFINAL ELSE MT2.MOTIVOINTERMEDIARIO END
        FROM cimic_transferencias_intermed CTI2
        INNER JOIN cimic_transferencias CT2 ON CT2.ID = CTI2.IDMOVIMENTACAO
        INNER JOIN tab_movimentacoestipo MT2 ON MT2.ID = CT2.IDTIPOMOV
        INNER JOIN tab_movimentacoesmotivos MM2 ON MM2.ID = CT2.IDMOTIVOMOV
        WHERE CT2.ID = CT.ID AND CTI2.PRIMEIROLOCAL = TRUE
        AND CTI2.IDEXCLUSOREGISTRO IS NULL AND CTI2.DATAEXCLUSOREGISTRO IS NULL) TIPOTRANSITO
        
        FROM cimic_transferencias CT
        INNER JOIN cimic_ordens_transferencias COT ON COT.ID = CT.IDORDEMSAIDAMOV
        INNER JOIN cimic_transferencias_intermed CTI ON CTI.IDMOVIMENTACAO = CT.ID
        INNER JOIN tab_unidades UN ON UN.ID = CTI.IDDESTINOINTERM
        INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
        INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
        INNER JOIN cadastros_condenacao COND ON COND.IDPRESO = EP.ID
        INNER JOIN tab_movimentacoestipo MT ON MT.ID = CT.IDTIPOMOV
        INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = CT.IDMOTIVOMOV
        WHERE MD5(COT.ID) = :idordem AND CTI.DESTINOFINAL = TRUE
        AND CTI.IDEXCLUSOREGISTRO IS NULL AND CTI.DATAEXCLUSOREGISTRO IS NULL
        AND CT.IDEXCLUSOREGISTRO IS NULL AND CT.DATAEXCLUSOREGISTRO IS NULL
        ORDER BY UNIDADE, EP.MATRICULA";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->bindParam('idordem',$idordem,PDO::PARAM_STR);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll();
        if(count($resultado)){
            $nomearquivo = "EnvioPresos-$Codigo_unidade-".date('YmdHis').".pdf";
            $datahora = $resultado[0]['DATASAIDA'];
            $data = retornaDadosDataHora($datahora,2);
            $dataextensa = $resultado[0]['DATADOC'];

            $conttotal = count($resultado);
            if($conttotal==1){
                $conttotal .= ' preso';
            }else{
                $conttotal .= ' presos';
            } ?>

            <p class="padding-margin-0 align-cen" style="font-size: 9pt; font-weight: bolder;">(Envio de <?=$conttotal?>)</p>
            <h1 class="titulo padding-margin-0" style="color: rgb(172, 55, 55); font-weight: bolder;">Envio do <?=$Nome_unidade?></h1>
            <p class="padding-margin-0 align-rig" style="font-size: 9pt; font-weight: bolder;"><?=$dataextensa?></p> <?php

            $padding = 0;
            $dest = ''; ?>

            <table style="width: 100%;"> <?php
                for($i=0;$i<count($resultado);$i++){
                    $idpreso = $resultado[$i]['IDPRESO'];
                    $matric = $resultado[$i]['MATRICULA'];
                    $matricula = midMatricula($matric,3);
                    $destino = $resultado[$i]['UNIDADE'];
                    $nome = buscaDadosLogPorPeriodo($matric,'NOME',3,$data);
                    $pai = buscaDadosLogPorPeriodo($matric,'PAI',3,$data);
                    $mae = buscaDadosLogPorPeriodo($matric,'MAE',3,$data);
                    $artigos = $resultado[$i]['ARTIGOS'];
                    $condenacao = $resultado[$i]['CONDENACAO'];
                    $tipo = $resultado[$i]['TIPO'];
                    $tipotransito = $resultado[$i]['TIPOTRANSITO'];
                    $seguro = $resultado[$i]['SEGURO'];
                    
                    $dadoscela = buscaDadosRaioCelaPreso($idpreso,$datahora,2);
                    $trabalho = $dadoscela['ESPECIAL'];

                    if($dest!=$destino){
                        $contdest=0;
                        //Faz a contagem de presos para este destino
                        for($icont=$i;$icont<count($resultado);$icont++){
                            if($resultado[$icont]['UNIDADE']==$destino){
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
                                <td class="sem-borda" colspan="5" style="margin-top: <?=$padding?>;">Destino: <span style="font-size: 12pt; color: blue; font-weight: bolder;"><?=$destino?></span></td>
                                <td class="sem-borda" style="width: 100px; text-align: right; font-weight: bolder; color: red;"><?=$contdest?></td>
                            </tr>
                        </table> <?php
                        $dest = $destino;
                        $padding = '10px';
                    }?>
                    <tr>
                        <table style="font-size: 8pt; border-collapse: collapse; width: 100%;"> 
                            <tr style="border-top: 2px solid black; background-color: lightblue;">
                                <td class="align-cen sem-borda" style="width: 6%; font-size: 9pt; font-weight: bold;"><?=$matricula?></td>
                                <td class="sem-borda" colspan="2" style="font-size: 9pt; font-weight: bold; width: 30%;"><?=$nome?></td>
                                <td class="sem-borda" style="font-size: 7pt; width: 20%;">Pai: <?=$pai?></td>
                                <td class="sem-borda" rowspan="2" style="width: 20%;">Dest. Final: <span style="font-size: 9pt; font-weight: bold;"><?=$tipo?></span></td>
                                <td class="sem-borda" rowspan="2" style="width: 15%;">Motivo TR: <span style="font-size: 9pt; font-weight: bold;"><?=$tipotransito?></span></td>
                            </tr>
                            <tr style="border-bottom: 2px solid black; background-color: lightblue;"> <?php
                                if($seguro==1 || $trabalho==1){ ?>
                                    <td class="sem-borda align-cen destaque-atencao" style="font-size: 9pt;">SEGURO</td> <?php
                                }else{ ?>
                                    <td class="sem-borda"></td> <?php
                                }
                                ?>
                                <td class="sem-borda" style="font-size: 7pt; width: 30%;">Art.: <?=$artigos?></td>
                                <td class="sem-borda" style="font-size: 7pt; width: 9%">Cond.: <?=$condenacao?></td>
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
