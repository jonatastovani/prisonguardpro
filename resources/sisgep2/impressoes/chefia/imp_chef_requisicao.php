<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    // $permissoesNecessarias = array(7);//array(3,4,5,6,7);
    // $redirecionamento = "../acesso_negado.php";
    // $blnPermitido = false;

    // $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "Requisicao-".date('YmdHis').".pdf";
    
    // echo var_dump($_GET);

    $idsmov = isset($_GET['idsmov'])?$_GET['idsmov']:0;
    $idstabela = isset($_GET['idstabela'])?$_GET['idstabela']:0;
    $tiporeq = isset($_GET['tiporeq'])?$_GET['tiporeq']:md5(1);
    
    $idsmov = explode(',',$idsmov);
    $idstabela = explode(',',$idstabela);

    // $where = '';
    // $explodepreso = explode(",",$idspreso);
    // $explodeperiodo = explode(",",$idsperiodo);

    $params = [];
    
    // $where = 'AND (';
    // for($i=0;$i<count($explodepreso);$i++){
    //     if($where!='AND ('){
    //         $where .= " OR ";
    //     }
    //     $where .= "(md5(EMA.IDPRESO) = ?";
    //     array_push($params,$explodepreso[$i]);
    //     $where .= " AND md5(EMA.IDPERIODOENTREGA) = ?)";
    //     array_push($params,$explodeperiodo[$i]);
    // }
    // $where .= ')';
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true && count(($idsmov))){

        //TABELA TEMPORÁRIA PARA TRATAR INSERIR E TRATAR OS DADOS A SEREM RETORNADOS
        $sql = "CREATE TEMPORARY TABLE consulta_ger_raio (
            ID INT auto_increment, PRIMARY KEY(ID),
            TABELA INT NOT NULL,
            IDMOVIMENTACAO INT NOT NULL,
            MATRICULA INT NOT NULL,
            NOME varchar(255) DEFAULT NULL,
            IDPRESO INT NOT NULL,
            RAIOATUAL varchar(5),
            CELAATUAL INT NOT NULL,
            RAIODESTINO varchar(10) DEFAULT NULL,
            CELADESTINO varchar(10) DEFAULT NULL,
            DATAMOV datetime DEFAULT NULL,
            OBSERVACOES mediumtext DEFAULT NULL,
            TIPO varchar(255) NOT NULL,
            IDSITUACAO INT NOT NULL,
            SITUACAO varchar(255) NOT NULL) DEFAULT CHAR SET UTF8;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute();
        
        $arr = [];

        $sql = "INSERT INTO consulta_ger_raio (TABELA, IDMOVIMENTACAO, MATRICULA, NOME, IDPRESO, RAIOATUAL, CELAATUAL, RAIODESTINO, CELADESTINO, DATAMOV, OBSERVACOES, TIPO, IDSITUACAO, SITUACAO) \r";
        $blnunion = false;

        for($i=0;$i<count($idsmov);$i++){
            if($blnunion){
                $sql .= "\r UNION \r";
            }

            //Mudança de Cela / Raio
            if($idstabela[$i]==md5(1)){
                $sql .= "SELECT 1 TABELA, CHEMC.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CHEMC.IDPRESO, RC2.NOME RAIOATUAL, CADMC.CELA CELAATUAL, RC.NOME RAIODESTINO, CHEMC.CELADESTINO, @dataBoletim DATAMOV, CHEMC.OBSERVACOES, CASE WHEN CADMC.RAIO = CHEMC.RAIODESTINO THEN 'Mudança de Cela' WHEN CADMC.RAIO <> CHEMC.RAIODESTINO AND CHEMC.RAIODESTINO IS NOT NULL THEN 'Mudança de Raio' ELSE 'Mudança Raio/Cela' END TIPO, CHEMC.IDSITUACAO, SIT.NOME SITUACAO
                FROM chefia_mudancacela CHEMC
                INNER JOIN entradas_presos EP ON EP.ID = CHEMC.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                LEFT JOIN tab_raioscelas RC ON RC.ID = CHEMC.RAIODESTINO
                INNER JOIN cadastros_mudancacela CADMC ON CADMC.ID = CHEMC.IDCELAATUAL
                INNER JOIN tab_raioscelas RC2 ON RC2.ID = CADMC.RAIO
                INNER JOIN tab_situacao SIT ON SIT.ID = CHEMC.IDSITUACAO
                WHERE md5(CHEMC.ID) = ? AND CHEMC.IDEXCLUSOREGISTRO IS NULL";

            }
            //Transferências
            elseif($idstabela[$i]==md5(2)){
                $sql .= "SELECT 2 TABELA, CT.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CT.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, COT.DATASAIDA DATAMOV, NULL OBSERVACOES, concat(MT.MOTIVOFINAL, ' ', UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE) TIPO, CT.IDSITUACAO, SIT.NOME SITUACAO
                FROM cimic_transferencias CT
                INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN cimic_ordens_transferencias COT ON COT.ID = CT.IDORDEMSAIDAMOV
                INNER JOIN tab_unidades UN ON UN.ID = COT.IDDESTINO
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                INNER JOIN tab_movimentacoestipo MT ON MT.ID = CT.IDTIPOMOV
                INNER JOIN tab_situacao SIT ON SIT.ID = CT.IDSITUACAO
                WHERE md5(CT.ID) = ? AND CT.IDEXCLUSOREGISTRO IS NULL";
            }
            elseif($idstabela[$i]==md5(3)){
                $sql .= "SELECT 3 TABELA, CA.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CA.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, COA.DATASAIDA DATAMOV, NULL OBSERVACOES, CLA.NOMEABREVIADO TIPO, CA.IDSITUACAO, SIT.NOME SITUACAO
                FROM cimic_apresentacoes CA
                INNER JOIN entradas_presos EP ON EP.ID = CA.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN cimic_ordens_apresentacoes COA ON COA.ID = CA.IDORDEMSAIDAMOV
                INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = COA.IDDESTINO
                INNER JOIN tab_situacao SIT ON SIT.ID = CA.IDSITUACAO
                WHERE md5(CA.ID) = ? AND CA.IDEXCLUSOREGISTRO IS NULL";
            }
            elseif($idstabela[$i]==md5(4)){
                $sql .= "SELECT 4 TABELA, CAIP.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CAIP.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, concat(CAI.DATASAIDA, ' ', CAIP.HORAAPRES) DATAMOV, NULL OBSERVACOES, CLA.NOMEABREVIADO TIPO, CAIP.IDSITUACAO, SIT.NOME SITUACAO
                FROM cimic_apresentacoes_internas_presos CAIP
                INNER JOIN entradas_presos EP ON EP.ID = CAIP.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN cimic_apresentacoes_internas CAI ON CAI.ID = CAIP.IDAPRES
                INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = CAI.IDDESTINO
                INNER JOIN tab_situacao SIT ON SIT.ID = CAIP.IDSITUACAO
                WHERE md5(CAIP.ID) = ? AND CAIP.IDEXCLUSOREGISTRO IS NULL";
            }
            elseif($idstabela[$i]==md5(5)){
                $sql .= "SELECT 5 TABELA, ENF.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, ENF.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, concat(REQ.DATAATEND, ' ', ENF.HORAATEND) DATAMOV, ENF.OBSERVACOES, CAT.NOME TIPO, ENF.IDSITUACAO, SIT.NOME SITUACAO 
                FROM enf_atendimentos ENF
                INNER JOIN entradas_presos EP ON EP.ID = ENF.IDPRESO
                INNER JOIN enf_atendimentos_requis REQ ON REQ.ID = ENF.IDREQ
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN chefia_atendimentostipo CAT ON CAT.ID = ENF.IDTIPOATEND
                INNER JOIN tab_situacao SIT ON SIT.ID = ENF.IDSITUACAO
                LEFT JOIN enf_atendimentos ENF2 ON ENF2.ID = ENF.ID
                WHERE md5(ENF.ID) = ? AND ENF.IDEXCLUSOREGISTRO IS NULL";
            }
            elseif($idstabela[$i]==md5(6)){
                $sql .= "SELECT 6 TABELA, CATD.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CATD.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, REQ.DATAATEND DATAMOV, NULL OBSERVACOES, concat(CAT.ABREVIACAO, CASE WHEN REQ.REQUISITANTE IS NOT NULL THEN concat(' ', REQ.REQUISITANTE) ELSE '' END) TIPO, CATD.IDSITUACAO, SIT.NOME SITUACAO
                FROM chefia_atendimentos CATD
                INNER JOIN chefia_atendimentos_requis REQ ON REQ.ID = CATD.IDREQ
                INNER JOIN entradas_presos EP ON EP.ID = CATD.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN chefia_atendimentostipo CAT ON CAT.ID = REQ.IDTIPOATEND
                INNER JOIN tab_situacao SIT ON SIT.ID = CATD.IDSITUACAO
                WHERE md5(CATD.ID) = ? AND CATD.IDEXCLUSOREGISTRO IS NULL";
            }
            elseif($idstabela[$i]==md5(7)){
                $sql .= "SELECT 7 TABELA, EXC.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, EXC.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, EXC.DATASAIDA DATAMOV, NULL OBSERVACOES, concat(MT.NOME, ' - ', MOV.NOME) TIPO, EXC.IDSITUACAO, SIT.NOME SITUACAO
                FROM cimic_exclusoes EXC
                INNER JOIN tab_movimentacoesmotivos MOV ON MOV.ID = EXC.IDMOTIVO
                INNER JOIN tab_movimentacoestipo MT ON MT.ID = EXC.IDTIPO
                INNER JOIN entradas_presos EP ON EP.ID = EXC.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN tab_situacao SIT ON SIT.ID = EXC.IDSITUACAO
                WHERE md5(EXC.ID) = ? AND EXC.IDEXCLUSOREGISTRO IS NULL";
            }

            array_push($params,$idsmov[$i]);

            $blnunion = true;
        }

        $sql .= " ORDER BY DATAMOV";

        //echo json_encode($sql); exit();

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);

        //Preenche todos raios e celas
        $sql = "UPDATE consulta_ger_raio SET RAIOATUAL = FUNCT_dados_raio_cela_preso(IDPRESO, DATAMOV, 2), CELAATUAL = FUNCT_dados_raio_cela_preso(IDPRESO, DATAMOV, 3) WHERE RAIOATUAL = '0'";
        
        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute();

        $sql = "SELECT CONS.*, date_format(CASE WHEN CONS.DATAMOV <> '0000-00-00 00:00:00' THEN CONS.DATAMOV ELSE CURRENT_DATE END,'%d de %M de %Y') DATAEXTENSA FROM consulta_ger_raio CONS";
        
        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetchAll();
        if(count($resultado)==0){
            echo "<h1>O procedimento solicitado não foi encontrado. Se o problema persistir contate o programador.</h1>";
            exit();
        }
        // echo "<p>".count($resultado)."</p>";
        // pre_r($resultado);

        if(count($resultado)){

            $contador = 0;
            for($i=0;$i<count($resultado);$i++){
                $nome=$resultado[$i]['NOME'];
                $matricula=$resultado[$i]['MATRICULA']!=null?midMatricula($resultado[$i]['MATRICULA'],3):"N/C";
                $raio=$resultado[$i]['RAIOATUAL'];
                $cela=$resultado[$i]['CELAATUAL'];
                $dataextensa=$resultado[$i]['DATAEXTENSA'];
                $tipo=$resultado[$i]['TIPO'];
                $horario=isset($resultado[$i]['DATAMOV'])?retornaDadosDataHora($resultado[$i]['DATAMOV'],6):"";
                
                if($tiporeq==md5(1)){
                    if($contador>0){ ?>
                        <hr style="border: none; border-bottom: dashed 1px #000000"> <?php
                    }
                    $contador++; ?>
                    
                    <table style="width: 100vw;"><table class="padding-margin-0" style="width: 100vw; text-align: center; font-size: 12pt; border-collapse: collapse; margin: 0; padding: 0; border: 1px solid black;">
                        <tr>
                            <td class="sem-borda align-lef" colspan="7">Nome: <b><?=$nome?></b></td>
                            <td class="sem-borda" colspan="3">Matrícula: <b><?=$matricula?></b></td>
                            <td class="sem-borda"><?=$contador?></td>
                        </tr>
                        <tr>
                            <td class="sem-borda align-lef" colspan="2">Módulo: <b><?=$raio?></b></td>
                            <td class="sem-borda align-lef" colspan="2">Cela: <b><?=$cela?></b></td>
                            <td class="sem-borda align-rig" colspan="7"><b><?=$dataextensa?></b></td>
                        </tr>
                        <tr>
                            <td class="sem-borda" colspan="11" style="height: 1em;"></td>
                        </tr>
                        <tr>
                            <td class="sem-borda"></td>
                            <td class="sem-borda" colspan="4"></td>
                            <!-- <td class="sem-borda" colspan="4" style="border-bottom: 2px solid black;"></td> -->
                            <td class="sem-borda align-rig" colspan="6"><b><?=$tipo?></b></td>
                        </tr>
                        <tr>
                            <td class="sem-borda"></td>
                            <td class="sem-borda" colspan="4"><b></b></td>
                            <!-- <td class="sem-borda" colspan="4"><b>D.S.N.S</b></td> -->
                            <td class="sem-borda" colspan="4"></td>
                            <td class="sem-borda" colspan="2"><b><?=$horario?></b></td>
                        </tr>
                    </table></table> <?php
                }else{ 
                    if($contador==0){ ?>
                        <table class="intercalado" style="width: 100vw; text-align: center; font-size: 10pt; border-collapse: collapse; margin: 0; padding: 0;"> 
                            <tr>
                                <th class="sem-borda"></th>
                                <th class="sem-borda">Matrícula</th>
                                <th class="sem-borda align-lef">Nome</th>
                                <th class="sem-borda">Tipo de Requisição</th>
                                <th class="sem-borda">Horário</th>
                                <th class="sem-borda">Raio/Cela</th>
                            </tr><?php
                    }
                    $contador++; ?>
                    
                    <tr class="intercalado">
                        <td class="sem-borda"><?=$contador?></td>
                        <td class="sem-borda nowrap"><?=$matricula?></td>
                        <td class="sem-borda align-lef"><?=$nome?></td>
                        <td class="sem-borda"><?=$tipo?></td>
                        <td class="sem-borda"><?=$horario?></td>
                        <td class="sem-borda"><?=$raio?>/<?=$cela?></td>
                    </tr> <?php

                    if($contador==count($resultado)){ ?>
                        </table> <?php
                    }
                }
                
            }
        }
    }else{
        echo $conexaoStatus;
        exit();
    }
