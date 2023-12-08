<?php

    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(9,41,52);
    $redirecionamento = "../acesso_negado.php";
    $blnPermitido = false;

    $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    
    $ordens = isset($_GET['ordens'])?$_GET['ordens']:0;
    $query = isset($_GET['query'])?$_GET['query']:0;
    $retorno = [];

    if(empty($ordens)){
        echo '<h1 style="margin-top: 100px;">Nenhum ID de Ofício Escolta foi informado</h1>';
        exit();
    }

    $idordens = explode(',', $ordens) ;
    if(count($idordens)>1){
        $nomearquivo = "OficioEscolta-".count($idordens)."Presos-".date('YmdHis').".pdf";
    }else{
        $nomearquivo = '';
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        foreach($idordens as $idordem){
            if($query==md5(1)){
                $sql = "SELECT COT.ID IDORDEM, COT.DATASAIDA, CT.IDPRESO, EP.MATRICULA, concat(LPAD(TOF.NUMERO, 4, '0'), '/',TOF.ANO) OFICIO, concat(UNT.NOME, ' de ', UN.NOMEUNIDADE) UNIDADE, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) ORDEM, concat(MT.MOTIVOFINAL,' - ', MM.NOME) TIPO, (SELECT CTI.COMENTARIO FROM cimic_transferencias_intermed CTI WHERE CTI.IDMOVIMENTACAO = CT.ID AND CTI.DESTINOFINAL = TRUE AND CTI.IDEXCLUSOREGISTRO IS NULL AND CTI.DATAEXCLUSOREGISTRO IS NULL LIMIT 1) COMENTARIO, concat(COND.ANOS, ' a, ', COND.MESES, ' m, ', COND.DIAS, ' d') CONDENACAO, date_format(COT.DATACADASTRO,'%W, %d de %M de %Y') DATADOC, EP.SEGURO,
                (SELECT group_concat(CASE WHEN EA.OBSERVACOES IS NULL THEN ART.NOME ELSE CONCAT(ART.NOME, ' (', EA.OBSERVACOES, ')') END SEPARATOR ', ')
                FROM entradas_artigos EA 
                INNER JOIN tab_artigos ART ON ART.ID = EA.IDARTIGO
                WHERE EA.IDPRESO = EP.ID) ARTIGOS
                FROM cimic_transferencias CT
                INNER JOIN cimic_ordens_transferencias COT ON COT.ID = CT.IDORDEMSAIDAMOV
                INNER JOIN tab_oficios TOF ON TOF.ID = COT.IDOFICIOESCOLTA
                INNER JOIN tab_ordemsaida TOS ON TOS.ID = COT.IDORDEM
                INNER JOIN tab_unidades UN ON UN.ID = COT.IDDESTINO
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                INNER JOIN tab_cidades CID ON CID.ID = UN.IDCIDADE
                INNER JOIN tab_estados EST ON EST.ID = CID.IDUF
                INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
                INNER JOIN cadastros_condenacao COND ON COND.IDPRESO = EP.ID
                INNER JOIN tab_movimentacoestipo MT ON MT.ID = CT.IDTIPOMOV
                INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = CT.IDMOTIVOMOV
                WHERE MD5(COT.ID) = :idordem AND CT.IDEXCLUSOREGISTRO IS NULL AND CT.DATAEXCLUSOREGISTRO IS NULL;";
    
            }elseif($query==md5(2)){
                $sql = "SELECT COA.ID IDORDEM, COA.DATASAIDA, CA.IDPRESO, EP.MATRICULA, concat(LPAD(TOF.NUMERO, 4, '0'), '/',TOF.ANO) OFICIO, CLA.NOME UNIDADE, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) ORDEM, MA.NOME TIPO, (SELECT CTI.COMENTARIO FROM cimic_transferencias_intermed CTI WHERE CTI.IDMOVIMENTACAO = CA.ID AND CTI.DESTINOFINAL = TRUE AND CTI.IDEXCLUSOREGISTRO IS NULL AND CTI.DATAEXCLUSOREGISTRO IS NULL LIMIT 1) COMENTARIO, concat(COND.ANOS, ' a, ', COND.MESES, ' m, ', COND.DIAS, ' d') CONDENACAO, date_format(COA.DATACADASTRO,'%W, %d de %M de %Y') DATADOC, CA.HORAAPRES, EP.SEGURO,
                (SELECT group_concat(CASE WHEN EA.OBSERVACOES IS NULL THEN ART.NOME ELSE CONCAT(ART.NOME, ' (', EA.OBSERVACOES, ')') END SEPARATOR ', ')
                FROM entradas_artigos EA 
                INNER JOIN tab_artigos ART ON ART.ID = EA.IDARTIGO
                WHERE EA.IDPRESO = EP.ID) ARTIGOS
                FROM cimic_apresentacoes CA
                INNER JOIN cimic_ordens_apresentacoes COA ON COA.ID = CA.IDORDEMSAIDAMOV
                INNER JOIN tab_oficios TOF ON TOF.ID = COA.IDOFICIOESCOLTA
                INNER JOIN tab_ordemsaida TOS ON TOS.ID = COA.IDORDEM
                INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = COA.IDDESTINO
                INNER JOIN tab_cidades CID ON CID.ID = CLA.IDCIDADE
                INNER JOIN tab_estados EST ON EST.ID = CID.IDUF
                INNER JOIN entradas_presos EP ON EP.ID = CA.IDPRESO
                INNER JOIN cadastros_condenacao COND ON COND.IDPRESO = EP.ID
                INNER JOIN cimic_motivosapresentacoes MA ON MA.ID = CA.IDMOTIVOAPRES
                WHERE MD5(COA.ID) = :idordem AND CA.IDEXCLUSOREGISTRO IS NULL AND CA.DATAEXCLUSOREGISTRO IS NULL;";
            }
            
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('idordem',$idordem,PDO::PARAM_STR);
            $stmt->execute();
            
            $resultado = $stmt->fetchAll();

            if(count($resultado)){
                $oficio = $resultado[0]['OFICIO'];
                $ordem = $resultado[0]['ORDEM'];
                $dataextensa = $resultado[0]['DATADOC'];
                $datahora = $resultado[0]['DATASAIDA'];
                $data = retornaDadosDataHora($datahora,1);
                $datasaida = retornaDadosDataHora($datahora,2);
                $horasaida = retornaDadosDataHora($datahora,8);
                $minutosaida = retornaDadosDataHora($datahora,9);
                if($minutosaida==0){
                    $minutosaida='';
                }
                $unidade = $resultado[0]['UNIDADE'];
                
                $dadosdiretor = buscaDadosDiretor(6,$data);
                $nomediretor = strtoupper($dadosdiretor['NOME']);
                $cargodiretor = $dadosdiretor['CARGO'];
                
                if(empty($nomearquivo)){
                    $nomearquivo = "OficioEscolta".date('YmdHis').".pdf";
                } ?>

                <table style="font-size: 12pt; border-collapse: collapse; width: 100%;">
                    <p class="padding-margin-0">Ofício nº <?=$oficio?></p>
                    <p class="align-rig"><b><?=$dataextensa?></b></p>

                    <p class="indent">Senhor Comandante</p>

                    <p class="indent align-jus" style="font-size: 12pt;">Solicito de Vossa Senhoria, escolta militar para o dia <b><?=$datasaida?></b>, a fim de conduzir o(s) detento(s) abaixo relacionado(s) e descriminados às <b><?=$horasaida?>h<?=$minutosaida?></b>.</p>
                    
                    <p class="align-cen padding-margin-0" style="font-size: 16pt; color: rgb(172, 55, 55); font-weight: bolder;"><?=$unidade?></p>

                     <?php
                    
                        for($i = 0;$i<count($resultado);$i++){
                            $idpreso = $resultado[$i]['IDPRESO'];
                            $nome = buscaDadosLogPorPeriodo($resultado[$i]['MATRICULA'],'NOME',3,$data);
                            $matricula = midMatricula($resultado[$i]['MATRICULA'],3);
                            $pai = buscaDadosLogPorPeriodo($resultado[$i]['MATRICULA'],'PAI',3,$data);
                            $mae = buscaDadosLogPorPeriodo($resultado[$i]['MATRICULA'],'MAE',3,$data);
                            $rg = buscaDadosLogPorPeriodo($resultado[$i]['MATRICULA'],'RG',3,$data);
                            $tipo = $resultado[$i]['TIPO'];
                            $comentario = $resultado[$i]['COMENTARIO'];
                            $artigos = $resultado[$i]['ARTIGOS'];
                            $condenacao = $resultado[$i]['CONDENACAO'];
                            $seguro = $resultado[$i]['SEGURO'];

                            $dadoscela = buscaDadosRaioCelaPreso($idpreso,$datahora,2);
                            $trabalho = $dadoscela['ESPECIAL'];
    
                            if($query==md5(2)){
                                $horaapres = retornaDadosDataHora($data ." ". $resultado[$i]['HORAAPRES'],8);
                                $minutoapres = retornaDadosDataHora($data ." ". $resultado[$i]['HORAAPRES'],9);
                                if($minutoapres==0){
                                    $minutoapres='';
                                }
                            }

                            $motivo = $tipo;
                            if(!empty($comentario)){
                                $motivo = "$tipo - $comentario";
                            }else{
                                $motivo = $tipo;
                            }
                            //baixar foto preso
                            $foto = baixarFotoServidor($idpreso,1,"../"); 
                            
                            if($i!=1){
                                echo "</td></tr>";
                            } ?>

                        <tr>
                            <td style="border: none;">
                                <table style="font-size: 12pt; border-collapse: collapse; width: 100%; line-height: 1em; border: 2px solid black;">
                                    <tr>
                                        <td rowspan="5" style="width: 13%; padding: 0px 1px;"><img style="width: 100%;" src="../<?=$foto?>"></td>
                                        <td class="sem-borda" colspan="7" style="padding-left: 20px;">Nome: <span style="font-weight: bolder;"><?=$nome?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="sem-borda" colspan="3" style="padding-left: 20px;">Matrícula: <span style="font-weight: bolder;"><?=$matricula?></span></td>
                                        <td class="sem-borda" colspan="4" style="padding-left: 20px; font-size: 9pt;">Pai: <b><?=$pai?></b></td>
                                    </tr>
                                    <tr> <?php
                                        if($seguro==1 || $trabalho==1){ ?>
                                            <td class="sem-borda matricula-destaque align-cen" colspan="3" style="padding-left: 20px; width: 30%;">SEGURO<b></b></td> <?php
                                        }else{ ?>
                                            <td class="sem-borda" style="" colspan="3"></td> <?php
                                        }
                                        ?>
                                        <td class="sem-borda" colspan="4" style="padding-left: 20px; font-size: 9pt;">Mãe: <b><?=$mae?></b></td>
                                    </tr>
                                    <tr>
                                        <td class="sem-borda" colspan="3"></b></b></td>
                                        <td class="sem-borda" colspan="3" style="padding-left: 20px; font-size: 9pt; width: 50%;">Artigo(s): <b><?=$artigos?></b></td>
                                        <td class="sem-borda" style="padding-left: 20px; font-size: 9pt; width: 20%;">Cond: <b><?=$condenacao?></b></td>
                                    </tr>
                                    <tr> <?php
                                    
                                    if($query==MD5(1)){ ?>
                                        <td class="sem-borda" colspan="7" style="padding-left: 20px; font-size: 10pt;"><b><?=$motivo?></b></td><?php
                                    }elseif($query==MD5(2)){ ?>
                                        <td class="sem-borda" style="padding-left: 20px; font-size: 10pt;">Horário: <b><?=$horaapres?>h<?=$minutoapres?></b></td>

                                        <td class="sem-borda" colspan="6" style="padding-left: 20px; font-size: 10pt;"><b><?=$motivo?></b></td><?php
                                    }?>
                                    </tr>
                                </table><?php
                        } ?>
                        
                                <div style="position: absolute; bottom: 0px; width: 92%;">
                                    <p class="align-cen">Atenciosamente,</p>
                                    <div style="padding-top: 25px; line-height: 5px;">
                                        <p class="align-cen"><b><?=$nomediretor?></b></p>
                                        <p class="align-cen padding-margin-0"><?=$cargodiretor?></p>
                                    </div>
                                    <p>A Vossa Senhoria, Senhor Comandante do 19º BPMI</p>
                                </div>

                            </td>
                        </tr>
                    </table>

                <?php
            }
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
