<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    // $permissoesNecessarias = array(7);//array(3,4,5,6,7);
    // $redirecionamento = "../acesso_negado.php";
    // $blnPermitido = false;

    // $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "Procedimentos-".date('YmdHis').".pdf";
    
    // echo var_dump($_GET);

    $modelo = $_GET['modelo'];
    $idturno = isset($_GET['idturno'])?$_GET['idturno']:0;
    $idboletim = isset($_GET['idboletim'])?$_GET['idboletim']:0;
    $idtipoescala = isset($_GET['idtipoescala'])?$_GET['idtipoescala']:0;
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $sql=retornaQueryDadosBoletimVigente();
        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute();

        $whereboletim = "";
        $params=[];

        if($modelo==md5(1)){
            $nomeescala = "Escala de Serviço";
            
            for($i=0;$i<2;$i++){
                array_push($params,$idtipoescala);
                if($idboletim==0){
                    $whereboletim = "BOL.ID = @intIDBoletim";
                }else{
                    $whereboletim = "md5(BOL.ID) = ?";
                    array_push($params,$idboletim);
                }
            }

            $sql = "SELECT FEPF.IDPOSTO, FEPOS.NOME NOMEPOSTO, FEPF.IDUSUARIO, US.NOME NOMEUSUARIO, FEPF.OBSERVACOES, BOL.IDTURNO, BOL.IDDIRETOR,FEPOS.COMASSINATURA,FEPOS.ENUMERAR, FEPOS.ORDEM, TU.NOME NOMETURNO, BOL.DATABOLETIM, BOL.NUMERO, date_format(BOL.DATABOLETIM,'%d de %M de %Y') DATAEXTENSA, TROC.IDUSUARIO IDUSUARIOTROCA, US2.NOME NOMEUSUARIOTROCA, FET.NOME TIPOESCALA, ((SELECT count(*) FROM funcionarios_escalaplantao_func FEPF2 WHERE FEPF2.IDESCALA = FEPF.IDESCALA AND FEPF2.IDPOSTO = FEPF.IDPOSTO AND FEPF2.IDEXCLUSOREGISTRO IS NULL ) + 
            (SELECT count(*) FROM funcionarios_escalaplantao_troca TROC2 INNER JOIN funcionarios_escalaplantao_func FEPF2 ON FEPF2.ID = TROC2.IDFUNC WHERE FEPF2.IDESCALA = FEPF.IDESCALA AND TROC2.IDPOSTO = FEPF.IDPOSTO AND TROC2.IDEXCLUSOREGISTRO IS NULL)) LINHASPOSTO
            FROM funcionarios_escalaplantao_func FEPF
            INNER JOIN funcionarios_escalaplantao FEP ON FEP.ID = FEPF.IDESCALA
            INNER JOIN chefia_boletim BOL ON BOL.ID = FEP.IDBOLETIM
            INNER JOIN tab_turnos TU ON TU.ID = BOL.IDTURNO
            INNER JOIN tab_usuarios US ON US.ID = FEPF.IDUSUARIO
            INNER JOIN funcionarios_escalapostos FEPOS ON FEPOS.ID = FEPF.IDPOSTO            
            LEFT JOIN funcionarios_escalaplantao_troca TROC ON TROC.IDFUNC = FEPF.ID
            LEFT JOIN tab_usuarios US2 ON US2.ID = TROC.IDUSUARIO
            INNER JOIN funcionarios_escalatipo FET ON FET.ID = FEP.IDTIPO
            WHERE MD5(FEP.IDTIPO) = ? AND $whereboletim AND FEPF.IDEXCLUSOREGISTRO IS NULL AND FEP.IDEXCLUSOREGISTRO IS NULL AND TROC.IDEXCLUSOREGISTRO IS NULL

            UNION

            SELECT TROC.IDPOSTO, FEPOS.NOME NOMEPOSTO, TROC.IDUSUARIO, US.NOME NOMEUSUARIO, FEPF.OBSERVACOES, BOL.IDTURNO, BOL.IDDIRETOR, FEPOS.COMASSINATURA, FEPOS.ENUMERAR, FEPOS.ORDEM, TU.NOME NOMETURNO, BOL.DATABOLETIM, BOL.NUMERO, date_format(BOL.DATABOLETIM,'%d de %M de %Y') DATAEXTENSA, NULL IDUSUARIOTROCA, NULL NOMEUSUARIOTROCA, FET.NOME TIPOESCALA, ((SELECT count(*) FROM funcionarios_escalaplantao_func FEPF2 WHERE FEPF2.IDESCALA = FEPF.IDESCALA AND FEPF2.IDPOSTO = TROC.IDPOSTO AND FEPF2.IDEXCLUSOREGISTRO IS NULL) + (SELECT count(*) FROM funcionarios_escalaplantao_troca TROC2 INNER JOIN funcionarios_escalaplantao_func FEPF2 ON FEPF2.ID = TROC2.IDFUNC WHERE FEPF2.IDESCALA = FEPF.IDESCALA AND TROC2.IDPOSTO = TROC.IDPOSTO AND TROC2.IDEXCLUSOREGISTRO IS NULL)) LINHASPOSTO
            FROM funcionarios_escalaplantao_troca TROC
            INNER JOIN funcionarios_escalaplantao_func FEPF ON FEPF.ID = TROC.IDFUNC
            INNER JOIN funcionarios_escalaplantao FEP ON FEP.ID = FEPF.IDESCALA
            INNER JOIN chefia_boletim BOL ON BOL.ID = FEP.IDBOLETIM
            INNER JOIN tab_turnos TU ON TU.ID = BOL.IDTURNO
            INNER JOIN tab_usuarios US ON US.ID = TROC.IDUSUARIO
            INNER JOIN funcionarios_escalapostos FEPOS ON FEPOS.ID = TROC.IDPOSTO
            INNER JOIN funcionarios_escalatipo FET ON FET.ID = FEP.IDTIPO
            WHERE MD5(FEP.IDTIPO) = ? AND $whereboletim AND FEPF.IDEXCLUSOREGISTRO IS NULL AND FEP.IDEXCLUSOREGISTRO IS NULL AND TROC.IDEXCLUSOREGISTRO IS NULL ORDER BY ORDEM;";

        }else{
            $nomeescala = "Escala Mensal";
            $params=[$idtipoescala,$idturno];

            $sql = "SELECT FEM.IDPOSTO, FEPOS.NOME NOMEPOSTO, FEPOS.COMASSINATURA, FEPOS.ENUMERAR, FEPOS.ORDEM, US.ID IDUSUARIO, US.NOME NOMEUSUARIO, FEM.OBSERVACOES, FEM.IDTURNO, TU.NOME NOMETURNO, CURRENT_DATE DATAHOJE, date_format(CURRENT_DATE,'%M/%Y') DATAEXTENSA, FET.NOME TIPOESCALA, TU.IDDIRETORCARCERAGEM, (SELECT COUNT(*) FROM funcionarios_escalamensal WHERE IDTURNO = FEM.IDTURNO AND IDPOSTO = FEM.IDPOSTO AND IDTIPO = FEM.IDTIPO AND IDEXCLUSOREGISTRO IS NULL) LINHASPOSTO
            FROM tab_usuarios US
            LEFT JOIN funcionarios_escalamensal FEM ON FEM.IDUSUARIO = US.ID
            LEFT JOIN funcionarios_escalapostos FEPOS ON FEPOS.ID = FEM.IDPOSTO
            INNER JOIN tab_turnos TU ON TU.ID = FEM.IDTURNO
            INNER JOIN funcionarios_escalatipo FET ON FET.ID = FEM.IDTIPO
            WHERE MD5(US.IDESCALA) = ? AND MD5(US.IDTURNO) = ? AND US.STATUS = 1 ORDER BY ORDEM;";


        }
        // echo "<p>$sql</p>";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);

        $resultado = $stmt->fetchAll();
        if(count($resultado)==0){
            echo "<h1> A Escala solicitada não foi encontrada. Se o problema persistir contate o programador. </h1>";
            exit();
        }
        // echo "<p>".count($resultado)."</p>";
        
        if($modelo==md5(1)){
            $iddiretor = $resultado[0]['IDDIRETOR'];
        }else{
            $params = [$_GET['iddiretor']];

            $sql = "SELECT ID IDDIRETOR FROM tab_usuariospermissoes WHERE md5(ID) = ?;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);

            $resultadodiretor = $stmt->fetchAll();            
            $iddiretor = $resultadodiretor[0]['IDDIRETOR'];
        }

        //Busca dados do diretor para a assinatura
        $resultadodiretor = buscaDadosIDPermissao($iddiretor);

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
        
        if(count($resultado)){
            $tipoescala = $resultado[0]['TIPOESCALA'];
            $nometurno = $resultado[0]['NOMETURNO'];
            $nomeescala .= " - $nometurno";
            $dataextensa = $resultado[0]['DATAEXTENSA'];
            if($modelo==md5(1)){
                $numeracao = ArrumaNumeroBoletim($resultado[0]['NUMERO'],retornaDadosDataHora($resultado[0]['DATABOLETIM'],5));

                $textodadosboletim = "$tipoescala - $dataextensa - Boletim $numeracao";
            }else{
                $textodadosboletim = "$tipoescala - $dataextensa";

            }
            // echo "<p>$nomeproced</p>";
            $nomearquivo = "Procedimento-". removeTodoEspaco($nomeescala)."-".date('YmdHis').".pdf";

            ?>

            <h1 class="titulo" style="padding-bottom: 0px;"><?=$nomeescala?></h1>

            <p class="align-rig"><?=$textodadosboletim?></p>
            
            <table style="width: 100vw; text-align: center; font-size: 12pt; border-collapse: collapse; margin: 0;">
                <thead>
                    <tr>
                        <th class="align-cen" style="width: 26%;">Setor</th>
                        <th class="align-cen" style="width: 4%;"></th>
                        <th class="align-cen" style="width: 45%;">Nome</th>
                        <th class="align-cen" style="width: 25%;">Assinatura</th>
                    </tr>
                </thead>
            </table> <?php
                    
            // $resultado = array_merge($resultado,$resultado,$resultado,$resultado,$resultado,$resultado,$resultado);

            $contadorfunc = 0;
            $contadorposto = -1;

            for($i=0;$i<count($resultado);$i++){
                $funcionario=$resultado[$i]['NOMEUSUARIO'];
                // echo "<p>$funcionario</p>";
                
                $tamanholetra = "12pt";
                if($modelo==md5(1)){
                    if($resultado[$i]['NOMEUSUARIOTROCA']!=NULL){
                        $funcionario .= " X " . $resultado[$i]['NOMEUSUARIOTROCA'];
                        $tamanholetra = "10pt";
                    }
                    elseif($resultado[$i]['OBSERVACOES']!=NULL){
                        $funcionario .= " (" . $resultado[$i]['OBSERVACOES'] . ")";
                    }
                }else{
                    if($resultado[$i]['OBSERVACOES']!=NULL){
                        $funcionario .= " (" . $resultado[$i]['OBSERVACOES'] . ")";
                    }
                }

                if($i==0 || $i>$contadorposto){ 
                    $nomeposto=$resultado[$i]['NOMEPOSTO'];
                    $linhasposto=$resultado[$i]['LINHASPOSTO']; ?>
                    
                    <table style="width: 100%; border-collapse: collapse;"><table style="width: 100%; text-align: center; font-size: 12pt; border-collapse: collapse; line-height: 1.5em;"><tr>

                    <td class="align-lef" style="width: 26%;" rowspan="<?=$linhasposto?>"><?=$nomeposto?></td> <?php
                    $contadorposto += $linhasposto;
                }else{ ?>
                    <tr> <?php
                }
                
                if($resultado[$i]['ENUMERAR']==1){
                    $contadorfunc++;
                    $cont = $contadorfunc; 
                }else{
                    $cont = "*";
                }

                if($resultado[$i]['COMASSINATURA']==0){
                    $assinatura = "XXXXXXXXXXXXXX"; 
                }else{
                    $assinatura = "";
                } ?>

                <td style="width: 4%;"><?=$cont?></td>
                <!-- <td style="width: 45%; word-wrap: break-word;"></td> -->
                <td class="align-lef" style="width: 45%; font-size: <?=$tamanholetra?>;"><?=$funcionario?></td>
                <td style="width: 25%;"><?=$assinatura?></td>
                </tr> <?php
                
                if($i==$contadorposto){ ?>
                    </table> </table> <?php

                }
            } ?>
            
            <section class="assinatura-final">
                <p class="align-cen padding-margin-0"><?=$nomediretor?></p>
                <p class="align-cen padding-margin-0"><?=$nomepermissao?></p>
            </section>


            <?php
        }        

    }else{
        echo $conexaoStatus;
        exit();
    }
