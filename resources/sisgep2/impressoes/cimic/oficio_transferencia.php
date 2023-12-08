<?php

    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(9,47);
    $redirecionamento = "../acesso_negado.php";
    $blnPermitido = false;

    $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);

    $oficios = isset($_GET['oficios'])?$_GET['oficios']:0;
    $retorno = [];

    if(empty($oficios)){
        echo '<h1 style="margin-top: 100px;">Nenhum ID de Destino foi informado</h1>';
        exit();
    }

    $idoficios = explode(',', $oficios) ;
    if(count($idoficios)>1){
        $nomearquivo = "OficioTransferencia-".date('YmdHis').".pdf";
    }else{
        $nomearquivo = '';
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        $contador = 0;

        foreach($idoficios as $idoficio){
            $contador++;

            $sql = "SELECT CT.IDPRESO, EP.MATRICULA, concat(LPAD(TOF.NUMERO, 4, '0'), '/',TOF.ANO) OFICIO, concat(UNT.NOME, ' de ', UN.NOMEUNIDADE) UNIDADE, UN.DIRETOR, concat(CID.NOME,'/', EST.SIGLA) CIDADE, CASE WHEN CTI.DESTINOFINAL = TRUE THEN concat(MT.MOTIVOFINAL,' - ', MM.NOME) ELSE MT.MOTIVOINTERMEDIARIO END TIPO, CTI.COMENTARIO, CTI.DATAINTERM, date_format(CTI.DATAINTERM,'%W, %d de %M de %Y') DATADOC, UN.CODIGO, CMO.DATASAIDA
            FROM cimic_transferencias_intermed CTI
            INNER JOIN tab_unidades UN ON UN.ID = CTI.IDDESTINOINTERM
            INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
            INNER JOIN tab_cidades CID ON CID.ID = UN.IDCIDADE
            INNER JOIN tab_estados EST ON EST.ID = CID.IDUF
            INNER JOIN tab_oficios TOF ON TOF.ID = CTI.IDOFICIOINTERM
            INNER JOIN cimic_transferencias CT ON CT.ID = CTI.IDMOVIMENTACAO
            INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
            INNER JOIN tab_movimentacoestipo MT ON MT.ID = CT.IDTIPOMOV
            INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = CT.IDMOTIVOMOV
            INNER JOIN cimic_ordens_transferencias CMO ON CMO.ID = CT.IDORDEMSAIDAMOV
            WHERE MD5(TOF.ID) = :idoficio AND CTI.IDEXCLUSOREGISTRO IS NULL AND CTI.DATAEXCLUSOREGISTRO IS NULL;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('idoficio',$idoficio,PDO::PARAM_STR);
            $stmt->execute();
            
            $resultado = $stmt->fetchAll();

            if(count($resultado)){
                $oficio = $resultado[0]['OFICIO'];
                $dataextensa = $resultado[0]['DATADOC'];
                $datahora = $resultado[0]['DATASAIDA'];
                $data = retornaDadosDataHora($datahora,1);
                $unidade = $resultado[0]['UNIDADE'];
                $codigo = $resultado[0]['CODIGO'];
                $cidade = $resultado[0]['CIDADE']; 
                $diretordestino = $resultado[0]['DIRETOR']; 
                
                $dadosdiretor = buscaDadosDiretor(6,$data);
                $nomediretor = strtoupper($dadosdiretor['NOME']);
                $cargodiretor = $dadosdiretor['CARGO'];
                
                if(empty($nomearquivo)){
                    $nomearquivo = "OficioTransferencia-$codigo-".date('YmdHis').".pdf";
                }?>

                <table style="font-size: 12pt; border-collapse: collapse; width: 100%;">
                    <p class="padding-margin-0">Ofício nº <?=$oficio?></p>
                    <p class="align-rig"><b><?=$dataextensa?></b></p>
                    
                    <p class="align-cen padding-margin-0" style="font-size: 16pt; color: rgb(172, 55, 55); font-weight: bolder;"><?=$unidade?></p>

                    <p class="indent">Senhor Diretor</p>

                    <p class="indent align-jus" style="font-size: 12pt; padding-bottom: 20px;">Com este, apresentamos a Vossa Senhoria, devidamente escoltado e com os procedimentos de prache, o(s) detento(s) abaixo relacionado(s), com os motivos que seguem:</p> <?php
                    
                    for($i = 0;$i<count($resultado);$i++){
                        $idpreso = $resultado[$i]['IDPRESO'];
                        $nome = buscaDadosLogPorPeriodo($resultado[$i]['MATRICULA'],'NOME',3,$data);
                        $matricula = midMatricula($resultado[$i]['MATRICULA'],3);
                        $pai = buscaDadosLogPorPeriodo($resultado[$i]['MATRICULA'],'PAI',3,$data);
                        $mae = buscaDadosLogPorPeriodo($resultado[$i]['MATRICULA'],'MAE',3,$data);
                        $rg = buscaDadosLogPorPeriodo($resultado[$i]['MATRICULA'],'RG',3,$data);
                        $tipo = $resultado[$i]['TIPO'];
                        $comentario = $resultado[$i]['COMENTARIO'];
                        
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
                            <table style="font-size: 12pt; border-collapse: collapse; width: 100%; line-height: 1em;">
                                <tr>
                                    <td rowspan="5" style="width: 80px; padding: 0px 1px;"><img style="width: 100%;" src="../<?=$foto?>"></td>
                                    <td colspan="7" style="padding-left: 20px; border-bottom: none;">Nome: <span style="font-weight: bolder;"><?=$nome?></span></td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="padding-left: 20px; border: none;">Matrícula: <span style="font-weight: bolder;"><?=$matricula?></span></td>
                                    <td colspan="3" style="padding-left: 20px; font-size: 9pt; border-top: none; border-bottom: none; border-left: none;">Pai: <b><?=$pai?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4" rowspan="2" style="padding-left: 20px; font-size: 15pt; border: none;">SEGURO<b></b></td>
                                    <td colspan="3" style="padding-left: 20px; font-size: 9pt; border-top: none; border-bottom: none; border-left: none;">Mãe: <b><?=$mae?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="padding-left: 20px; font-size: 9pt; border-top: none; border-bottom: none; border-left: none;">RG: <b><?=$rg?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="7" style="padding-left: 20px; font-size: 10pt; border-top: none;"><b><?=$motivo?></b></td>
                                </tr>
                            </table><?php
                    } ?>
                    
                            <div style="position: absolute; bottom: 0px; width: 92%;">
                                <p class="align-cen">Atenciosamente,</p>
                                <div style="padding-top: 25px; line-height: 5px;">
                                    <p class="align-cen"><b><?=$nomediretor?></b></p>
                                    <p class="align-cen padding-margin-0"><?=$cargodiretor?></p>
                                </div>
                                <p class="padding-margin-0">A Vossa Senhoria</p>
                                <p class="padding-margin-0"><b>Dr. <?=$diretordestino?></b></p>
                                <p class="padding-margin-0">Diretor da(o) <?=$unidade?></p>
                                <p class="padding-margin-0"><b><?=$cidade?></b></p>
                            </div>
                        </td>
                    </tr>
                </table><?php
            }
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
