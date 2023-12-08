<?php

    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(9,42);
    $redirecionamento = "../acesso_negado.php";
    $blnPermitido = false;

    $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);

    $apresentacoes = isset($_GET['apresentacoes'])?$_GET['apresentacoes']:0;
    $query = isset($_GET['query'])?$_GET['query']:0;
    $retorno = [];

    if(empty($apresentacoes)){
        echo '<h1 style="margin-top: 100px;">Nenhum ID de Apresentação foi informado</h1>';
        exit();
    }

    $idapresentacoes = explode(',', $apresentacoes) ;
    if(count($idapresentacoes)>1){
        $nomearquivo = "OficioApresentacao-".count($idapresentacoes)."Presos-".date('YmdHis').".pdf";
    }else{
        $nomearquivo = '';
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        foreach($idapresentacoes as $apres){ ?>
            <table>
                <?php
                if($query==md5(1)){
                    $sql = "SELECT CMT.IDPRESO, EP.MATRICULA, CLAR.NOME ENTIDADE, CLAR.FORMALIDADE, CLAR.FORMALIDADEABREV, CLAR.DEDICADO, CLAR.ENVIADO, CLA.NOME NOMELOCAL, concat(CID.NOME, '/', EST.SIGLA) CIDADE, CTA.DATAAPRES, CMAP.NOME MOTIVOAPRES, concat(LPAD(TOF.NUMERO, 4, '0'),'/',TOF.ANO) OFICIO,
                    date_format(CTA.DATAAPRES,'%W, %d de %M de %Y') DATADOC
                    FROM cimic_transferencias_apres CTA
                    INNER JOIN cimic_transferencias CMT ON CMT.ID = CTA.IDMOVIMENTACAO
                    INNER JOIN tab_oficios TOF ON TOF.ID = CTA.IDOFICIOAPRES
                    INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = CTA.IDDESTINOAPRES
                    INNER JOIN cimic_locaisapresentacoesresponsavel CLAR ON CLAR.ID = CLA.IDTIPORESP
                    INNER JOIN tab_cidades CID ON CID.ID = CLA.IDCIDADE
                    INNER JOIN tab_estados EST ON EST.ID = CID.IDUF
                    INNER JOIN cimic_motivosapresentacoes CMAP ON CMAP.ID = CTA.IDMOTIVOAPRES
                    INNER JOIN entradas_presos EP ON EP.ID = CMT.IDPRESO
                    WHERE MD5(CTA.ID) = :apres;";

                }elseif($query==md5(2)){
                    $sql = "SELECT CA.IDPRESO, EP.MATRICULA, CLAR.NOME ENTIDADE, CLAR.FORMALIDADE, CLAR.FORMALIDADEABREV, CLAR.DEDICADO, CLAR.ENVIADO, CLA.NOME NOMELOCAL, concat(CID.NOME, '/', EST.SIGLA) CIDADE, concat(date_format(COA.DATASAIDA, '%Y-%m-%d '), CA.HORAAPRES) DATAAPRES, CMAP.NOME MOTIVOAPRES, concat(LPAD(TOF.NUMERO, 4, '0'),'/',TOF.ANO) OFICIO,
                    date_format(COA.DATASAIDA,'%W, %d de %M de %Y') DATADOC
                    FROM cimic_apresentacoes CA
                    INNER JOIN cimic_ordens_apresentacoes COA ON COA.ID = CA.IDORDEMSAIDAMOV
                    INNER JOIN tab_oficios TOF ON TOF.ID = CA.IDOFICIOAPRES
                    INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = COA.IDDESTINO
                    INNER JOIN cimic_locaisapresentacoesresponsavel CLAR ON CLAR.ID = CLA.IDTIPORESP
                    INNER JOIN tab_cidades CID ON CID.ID = CLA.IDCIDADE
                    INNER JOIN tab_estados EST ON EST.ID = CID.IDUF
                    INNER JOIN cimic_motivosapresentacoes CMAP ON CMAP.ID = CA.IDMOTIVOAPRES
                    INNER JOIN entradas_presos EP ON EP.ID = CA.IDPRESO
                    WHERE MD5(CA.ID) = :apres;";
                
                }elseif($query==md5(3)){
                    $sql = "SELECT CAIP.IDPRESO, EP.MATRICULA, CLAR.NOME ENTIDADE, CLAR.FORMALIDADE, CLAR.FORMALIDADEABREV, CLAR.DEDICADO, CLAR.ENVIADO, CLA.NOME NOMELOCAL, concat(CID.NOME, '/', EST.SIGLA) CIDADE, concat(date_format(CAI.DATASAIDA, '%Y-%m-%d '), CAIP.HORAAPRES) DATAAPRES, CMAP.NOME MOTIVOAPRES, concat(LPAD(TOF.NUMERO, 4, '0'),'/',TOF.ANO) OFICIO, date_format(CAI.DATASAIDA,'%W, %d de %M de %Y') DATADOC
                    FROM cimic_apresentacoes_internas_presos CAIP
                    INNER JOIN cimic_apresentacoes_internas CAI ON CAI.ID = CAIP.IDAPRES
                    INNER JOIN tab_oficios TOF ON TOF.ID = CAIP.IDOFICIOAPRES
                    INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = CAI.IDDESTINO
                    INNER JOIN cimic_locaisapresentacoesresponsavel CLAR ON CLAR.ID = CLA.IDTIPORESP
                    INNER JOIN tab_cidades CID ON CID.ID = CLA.IDCIDADE
                    INNER JOIN tab_estados EST ON EST.ID = CID.IDUF
                    INNER JOIN cimic_motivosapresentacoes CMAP ON CMAP.ID = CAIP.IDMOTIVOAPRES
                    INNER JOIN entradas_presos EP ON EP.ID = CAIP.IDPRESO
                    WHERE MD5(CAIP.ID) = :apres;";
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('apres',$apres,PDO::PARAM_STR);
                $stmt->execute();
                
                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    
                    $oficio = $resultado[0]['OFICIO'];
                    $dataextensa = $resultado[0]['DATADOC'];
                    $data = retornaDadosDataHora($resultado[0]['DATAAPRES'],1);
                    $horaapres = retornaDadosDataHora($resultado[0]['DATAAPRES'],8);
                    $minutoapres = retornaDadosDataHora($resultado[0]['DATAAPRES'],9);
                    if($minutoapres==0){
                        $minutoapres='';
                    }
                    $idpreso = $resultado[0]['IDPRESO'];
                    $matricula = midMatricula($resultado[0]['MATRICULA'],3);
                    $matric = $resultado[0]['MATRICULA'];
                    $nome = buscaDadosLogPorPeriodo($matric,'NOME',3,$data);
                    if($nome == ''){
                        $nome = buscaDadosLogPorPeriodo($idpreso,'NOME',5,$data);
                    };
                    $mae = buscaDadosLogPorPeriodo($matric,'MAE',3,$data);
                    if($mae == ''){
                        $mae = buscaDadosLogPorPeriodo($idpreso,'MAE',5,$data);
                    };
                    $pai = buscaDadosLogPorPeriodo($matric,'PAI',3,$data);
                    if($pai == ''){
                        $pai = buscaDadosLogPorPeriodo($idpreso,'PAI',5,$data);
                    };
                    $rg = buscaDadosLogPorPeriodo($matric,'RG',3,$data);
                    if($rg == ''){
                        $rg = buscaDadosLogPorPeriodo($idpreso,'RG',5,$data);
                    };
                    $local = $resultado[0]['NOMELOCAL'];
                    $motivoapres = $resultado[0]['MOTIVOAPRES'];
                    $cidade = $resultado[0]['CIDADE']; 
                    $entidade = $resultado[0]['ENTIDADE']; 
                    $formalidade = $resultado[0]['FORMALIDADE']; 
                    $formalidadeabrev = $resultado[0]['FORMALIDADEABREV']; 
                    $dedicado = $resultado[0]['DEDICADO']; 
                    $enviado = $resultado[0]['ENVIADO'];

                    //baixar foto preso
                    $foto = baixarFotoServidor($idpreso,1,"../");

                    $dadosdiretor = buscaDadosDiretor(6,$data);
                    $nomediretor = strtoupper($dadosdiretor['NOME']);
                    $cargodiretor = $dadosdiretor['CARGO'];
                    
                    if(empty($nomearquivo)){
                        $nomearquivo = "OficioApresentacao-$nome-".date('YmdHis').".pdf";
                    }
                    
                    ?>
                    
                    <p>Ofício nº <?=$oficio?></p>
                    <p class="align-rig"><b><?=$dataextensa?></b></p> <?php
                    
                    if($query==md5(1) || $query==md5(2)){ ?>
                        <p class="indent" style="margin-top: 50px;"><?=$dedicado?></p>
                        <p class="indent" style="font-size: 14pt; margin-top: 50px;">Com este, apresentamos a <?=$formalidade?>, devidamente escoltado e com os procedimentos de prache o detento abaixo relacionado, às <b><?=$horaapres?>h<?=$minutoapres?></b>, <b><?=$motivoapres?></b>.</p> <?php
                    
                    }elseif($query==md5(3)){ ?>
                        <p class="indent" style="margin-top: 50px;">Caro Servidor</p>
                        <p class="indent" style="font-size: 14pt; margin-top: 50px;">Com este, solicitamos a Vossa Senhoria, que encaminhe à sala de Teleaudiências, com os procedimentos de prache o detento abaixo relacionado, às <b><?=$horaapres?>h<?=$minutoapres?></b>, <b><?=$motivoapres?></b>.</p> <?php
                    }?>
                    
                    <table style="font-size: 12pt; margin-top: 50px;">
                        <tr>
                            <td rowspan="6" style="border: none;"><img style="max-width: 130px;" src="../<?=$foto?>"></td>
                            <td style="border: none; padding-left: 20px;">Nome: <span style="font-weight: bolder; font-size: 15pt;"><?=$nome?></span></td>
                        </tr>
                        <tr>
                            <td style="border: none; padding-left: 20px;">Matrícula: <span style="font-weight: bolder; font-size: 15pt;"><?=$matricula?></span></td>
                        </tr>
                        <tr>
                            <td style="border: none; padding-left: 20px; font-size: 10pt;">Pai: <b><?=$pai?></b></td>
                        </tr>
                        <tr>
                            <td style="border: none; padding-left: 20px; font-size: 10pt;">Mãe: <b><?=$mae?></b></td>
                        </tr>
                        <tr>
                            <td style="border: none; padding-left: 20px; font-size: 10pt;">RG: <b><?=$rg?></b></td>
                        </tr>
                    </table>

                    <div style="position: absolute; bottom: 0px; width: 92%;">
                        <p class="align-cen">Respeitosamente,</p>
                        <div style="padding-top: 25px; line-height: 5px;">
                            <p class="align-cen"><b><?=$nomediretor?></b></p>
                            <p class="align-cen"><?=$cargodiretor?></p>
                        </div> <?php
                    
                        if($query==md5(1) || $query==md5(2)){ ?>
                        <div class="align-rig" style="display: flex; justify-content: flex-end;">
                            <div style="width: 175px; display: inline-block; line-height: 1em; font-size: 9pt;">
                                <p class="align-cen" style="padding: 0; margin: 0;"><b>CERTIDÃO</b></p>
                                <p class="indent align-jus" style="padding: 0; margin: 0;">Certifico que nesta data detento o acima qualificado devidamente apresentado neste Juízo, referente aos autos supra mencionado.</p>
                                <p style="margin-bottom: 25px;">___/________/20___</p>
                                <hr style="padding: 0px 10px 0px;">
                                <p class="align-cen" style="padding: 0; margin: 0;">Escrivão / Escrevente</p>
                            </div>
                        </div><?php
                        }
                    
                        if($query==md5(1) || $query==md5(2)){ ?>
                            <p style="padding: 0; margin: 0; margin-top: 10px;"><?=$enviado?></p>
                            <p style="padding: 0; margin: 0;"><?=$dedicado?> da(o) <?=$local?></p>
                            <p style="padding: 0; margin: 0;"><b><?=$cidade?></b></p> <?php
                        
                        }elseif($query==md5(3)){ ?>
                            <p style="padding: 0; margin: 0; margin-top: 10px;">À Vossa Senhoria</p>
                            <p style="padding: 0; margin: 0;">Policial Penal do Setor de Chefia/Penal</p>
                            <p style="padding: 0; margin: 0;"><b><?=$Cidade_unidade?></b></p> <?php
                        }?>
                    </div> <?php
                } ?>
            </table> <?php
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
