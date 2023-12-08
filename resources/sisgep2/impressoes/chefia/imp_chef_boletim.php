<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    // $permissoesNecessarias = array(7);//array(3,4,5,6,7);
    // $redirecionamento = "../acesso_negado.php";
    // $blnPermitido = false;

    // $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "BoletimInformativo-".date('YmdHis').".pdf";
    
    // echo var_dump($_GET);

    $boletimvigente = isset($_GET['boletimvigente'])?$_GET['boletimvigente']:0;
    $idboletim = isset($_GET['idboletim'])?$_GET['idboletim']:0;
    
    // Caso houver dados que necessitam de alteração ou correção, não irá iniciar a geração do boletim, mas informará todas as pendências de uma só vez
    $blnnaocompilar = false;

    // pre_r($idsmudanca); // exit();

    $params = [];
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $resultado = [];
        $params = [];

        $where = "BOL.BOLETIMDODIA = TRUE ORDER BY BOL.ID DESC LIMIT 1";
        if($boletimvigente==0){
            $where = "md5(BOL.ID) = ?";
            array_push($params,$idboletim);
        }

        $sql = "SELECT BOL.*, BOL.ID IDBOLETIM, TU.NOME NOMETURNO, BOL.DATABOLETIM, BOL.NUMERO, date_format(BOL.DATABOLETIM,'%d de %M de %Y') DATAEXTENSA, BOL.IDDIRETOR
        FROM chefia_boletim BOL
        INNER JOIN tab_turnos TU ON TU.ID = BOL.IDTURNO
        WHERE $where;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);
        $resultado = $stmt->fetchAll();

        // echo "<p>1º Consulta ".count($resultado)."</p>";
        // pre_r($resultado); exit();

        if(count($resultado)==0){
            echo "<h1>A consulta de dados do Boletim do dia solicitado não obteve resultados. Se o problema persistir contate o programador.</h1>";
            exit();
        }

        $idboletim = $resultado[0]['IDBOLETIM'];
        $dataextensa=$resultado[0]['DATAEXTENSA'];
        $nometurno = $resultado[0]['NOMETURNO'];
        $numeracao = ArrumaNumeroBoletim($resultado[0]['NUMERO'],retornaDadosDataHora($resultado[0]['DATABOLETIM'],5));

        // echo "<p>Todas consultas ".count($resultado)."</p>";
        // pre_r($resultado);exit();

        $nomearquivo = "B.I. ".str_replace("/","-",$numeracao)." $nometurno.pdf";

        //Busca dados do diretor para a assinatura
        $params=[$resultado[0]['IDDIRETOR']];

        $sql = "SELECT PERM.NOME NOMEPERMISSAO, PERM.NOMECOMPLETO NOMECOMPLETOPERMISSAO, US.NOME NOMEDIRETOR, USPERM.SUBSTITUTO
        FROM tab_usuariospermissoes USPERM
        INNER JOIN tab_usuarios US ON US.ID = USPERM.IDUSUARIO
        INNER JOIN tab_permissoes PERM ON PERM.ID = USPERM.IDPERMISSAO
        WHERE USPERM.ID = ?;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);
        $resultadodiretor = $stmt->fetchAll();

        if(count($resultadodiretor)==0){
            echo '<li><p style="background-color: lightcoral;">Diretor não encontrado. Não será possível gerar o documento sem essa informação.</p></li>';
            $blnnaocompilar = true;
            // exit();
        }else{
            $nomepermissao = $resultadodiretor[0]['NOMECOMPLETOPERMISSAO'];
            $nomediretor = $resultadodiretor[0]['NOMEDIRETOR'];
            $substituto = $resultadodiretor[0]['SUBSTITUTO'];
            if($substituto==1){
                $nomepermissao .= " - Subst.";
            }
                        
        }

        //Busca se existe mudanças de cela pendentes. Se houver então é exibido a mensagem para excluir ou concluir a mudança
        $params=[$idboletim];

        $sql = "SELECT * FROM chefia_mudancacela WHERE IDBOLETIM = ? AND IDSITUACAO NOT IN (6,7,8,9) AND IDEXCLUSOREGISTRO IS NULL;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);
        $resultado = $stmt->fetchAll();

        if(count($resultado)){
            echo '<li><p>Existe Mudança(s) de Raio/Cela em aberto. Não será possível seguir com a compilação do Boletim Informativo. </p></li>';
            $blnnaocompilar = true;
            // exit();
        }

        // Busca os contagem de final de plantão
        $params=[$idboletim];
        
        $sql = "SELECT CC.ID IDCONTAGEM, CC.IDTIPO, CC.IDUSUARIO, US.NOME NOMEUSUARIO, CC.AUTENTICADO, CC.IDRAIO, RC.NOMECOMPLETO NOMERAIO, CC.QTD, CCT.NOME NOMECONTAGEM
        FROM chefia_contagens CC
        INNER JOIN tab_raioscelas RC ON RC.ID = CC.IDRAIO
        INNER JOIN chefia_contagenstipos CCT ON CCT.ID = CC.IDTIPO
        LEFT JOIN tab_usuarios US ON US.ID = CC.IDUSUARIO
        WHERE CC.IDBOLETIM = ? AND CC.IDTIPO = 1 AND CC.IDEXCLUSOREGISTRO IS NULL;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);
        $resultadocontagem = $stmt->fetchAll();       
        
        if(!count($resultadocontagem)){
            echo '<li><p style="background-color: lightcoral;">******** A contagem de Final de Plantão não foi encontrada. ********</p></li>';
             //Pode compilar, mas exibirá a mensagem no começo e no final do documento
             // $blnnaocompilar = true;
            // exit();
        }

        //Se for falso o não compilar, então gera-se o boletim
        if(!$blnnaocompilar){ ?>

            <h1 style="font-size: 18pt; text-align: center;"><b><?=$nometurno?></b></h1>
    
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="text-align: left; border: none;">Boletim Diário nº <?=$numeracao?></td>
                    <td style="text-align: right; border: none;"><?=$Cidade_unidade?>, <?=$dataextensa?></td>
                </tr>
            </table> <?php
            
            //Buscar presos que fora inclusos na unidade
            $params=[$idboletim];

            $sql = "SELECT EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.RG ELSE EP.RG END RG, GSA.NOME ORIGEM, RC.NOME RAIO, MUD.CELA
            FROM cadastros_mudancacela MUD
            INNER JOIN entradas_presos EP ON EP.ID = MUD.IDPRESO
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN entradas E ON E.ID = EP.IDENTRADA
            INNER JOIN tab_raioscelas RC ON RC.ID = MUD.RAIO
            INNER JOIN codigo_gsa GSA ON GSA.ID = E.IDORIGEM
            WHERE MUD.IDBOLETIMENTRADA = ? AND MUD.IDEXCLUSOREGISTRO IS NULL
            AND (SELECT count(MUD2.ID) FROM cadastros_mudancacela MUD2 WHERE MUD2.IDPRESO = MUD.IDPRESO AND MUD2.ID < MUD.ID AND MUD2.IDEXCLUSOREGISTRO IS NULL) = 0 ORDER BY NOME;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();

            if(count($resultado)){
                $plural = count($resultado)>1?"Inclusões na Unidade Prisional":"Inclusão na Unidade Prisional"; ?>
                <table style="text-align: center; width: 100%;">
                    <h4 style="margin-bottom: 0;"><b><?=$plural?></b></h4>

                    <table style="font-size: 10pt; width: 100%; border-collapse: collapse;">
                        <tr>
                            <th class="align-lef sem-borda">Nome</th>
                            <th class="sem-borda">Matrícula</th>
                            <th class="sem-borda">RG</th>
                            <th class="sem-borda">R/C</th>
                            <th class="align-rig sem-borda">Origem</th>
                        </tr> <?php

                        foreach($resultado as $linha){
                            $nome = $linha['NOME'];
                            $matricula=$linha['MATRICULA']!=null?midMatricula($linha['MATRICULA'],3):"N/C";
                            $rg=$linha['RG']!=null?$linha['RG']:"N/C";
                            $raiocela = $linha['RAIO']!=null?$linha['RAIO']:"N/C";
                            $raiocela .= ($linha['CELA']!=null && $linha['CELA']>0)?"/". $linha['CELA']:"";
                            $origem = $linha['ORIGEM']; ?>

                            <tr>
                                <td class="sem-borda"><?=$nome?></td>
                                <td class="align-cen nowrap sem-borda"><?=$matricula?></td>
                                <td class="align-cen nowrap sem-borda"><?=$rg?></td>
                                <td class="align-cen nowrap sem-borda"><?=$raiocela?></td>
                                <td class="align-rig sem-borda"><?=$origem?></td>
                            </tr>
                            
                            <?php
                        }
                        ?>
                    </table>
                </table>
                <?php
            }

            // Busca alvarás
            $params=[$idboletim];

            $sql = "SELECT CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CD.MATRICULA, FUNCT_dados_raio_cela_preso(EP.ID, MUD.DATAATUALIZACAO, 2) RAIO, FUNCT_dados_raio_cela_preso(EP.ID, MUD.DATAATUALIZACAO, 3) CELA, concat(MT.NOME, ' - ', MOT.NOME) MOTIVO
            FROM cimic_exclusoes EXC
            INNER JOIN entradas_presos EP ON EP.ID = EXC.IDPRESO
            INNER JOIN cadastros_mudancacela MUD ON MUD.ID = (SELECT ID FROM cadastros_mudancacela WHERE IDPRESO = EXC.IDPRESO AND IDEXCLUSOREGISTRO IS NULL ORDER BY ID DESC LIMIT 1)
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN tab_movimentacoestipo MT ON MT.ID = EXC.IDTIPO
            INNER JOIN tab_movimentacoesmotivos MOT ON MOT.ID = EXC.IDMOTIVO
            WHERE EXC.IDBOLETIM = ? AND EXC.IDEXCLUSOREGISTRO IS NULL ORDER BY CD.NOME;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();
            
            if(count($resultado)){
                $plural = count($resultado)>1?"Exclusões":"Exclusão"; ?>
                <table style="text-align: center; width: 100%;">
                    <h4 style="margin-bottom: 0;"><b><?=$plural?></b></h4>

                    <table style="font-size: 10pt; width: 100%; border-collapse: collapse;">
                        <tr>
                            <th class="align-lef sem-borda">Nome</th>
                            <th class="sem-borda">Matrícula</th>
                            <th class="sem-borda">R/C</th>
                            <th class="align-rig sem-borda">Motivo</th>
                        </tr> <?php

                        foreach($resultado as $linha){
                            $nome = $linha['NOME'];
                            $matricula=$linha['MATRICULA']!=null?midMatricula($linha['MATRICULA'],3):"N/C";
                            $raiocela = $linha['RAIO']!=null?$linha['RAIO']:"N/C";
                            $raiocela .= ($linha['CELA']!=null && $linha['CELA']>0)?"/". $linha['CELA']:"";
                            $motivo = $linha['MOTIVO']; ?>
                            <tr>
                                <td class="sem-borda"><?=$nome?></td>
                                <td class="align-cen nowrap sem-borda"><?=$matricula?></td>
                                <td class="align-cen nowrap sem-borda"><?=$raiocela?></td>
                                <td class="align-rig sem-borda"><?=$motivo?></td>
                            </tr>
                            
                            <?php
                        }
                        ?>
                    </table>
                </table>
                <?php
            }

            // Busca transferências
            $params=[$idboletim];

            $sql = "SELECT CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CD.MATRICULA, FUNCT_dados_raio_cela_preso(EP.ID, CT.DATAHORASAIDA, 2) RAIO, FUNCT_dados_raio_cela_preso(EP.ID, CT.DATAHORASAIDA, 3) CELA, COT.DATASAIDA, concat(UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE) DESTINO, MOT.NOME MOTIVO
            FROM cimic_transferencias CT
            INNER JOIN cimic_ordens_transferencias COT ON COT.ID = CT.IDORDEMSAIDAMOV
            INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN tab_movimentacoestipo MT ON MT.ID = CT.IDTIPOMOV
            INNER JOIN tab_movimentacoesmotivos MOT ON MOT.ID = CT.IDMOTIVOMOV
            INNER JOIN cimic_transferencias_intermed CTI ON CTI.IDMOVIMENTACAO = CT.ID
            INNER JOIN tab_unidades UN ON UN.ID = CTI.IDDESTINOINTERM
            INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
            WHERE CT.IDBOLETIMSAIDA = ? AND CT.IDEXCLUSOREGISTRO IS NULL AND CTI.DESTINOFINAL = TRUE AND CTI.IDEXCLUSOREGISTRO IS NULL ORDER BY CD.NOME;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();
            
            if(count($resultado)){
                
                $plural = count($resultado)>1?"Transferências":"Transferência"; ?>
                
                <table style="text-align: center; width: 100%;">
                    <h4 style="margin-bottom: 0;"><b><?=$plural?></b></h4>

                    <table style="font-size: 10pt; width: 100%; border-collapse: collapse;">
                        <tr>
                            <th class="align-lef sem-borda">Nome</th>
                            <th class="sem-borda">Matrícula</th>
                            <th class="sem-borda">R/C</th>
                            <th class="sem-borda">Horário</th>
                            <th class="sem-borda">Destino</th>
                            <th class="align-rig sem-borda">Motivo</th>
                        </tr> <?php

                        foreach($resultado as $linha){
                            $nome = $linha['NOME'];
                            $matricula=$linha['MATRICULA']!=null?midMatricula($linha['MATRICULA'],3):"N/C";
                            $raiocela = $linha['RAIO']!=null?$linha['RAIO']:"N/C";
                            $raiocela .= ($linha['CELA']!=null && $linha['CELA']>0)?"/". $linha['CELA']:"";
                            $horario = retornaDadosDataHora($linha['DATASAIDA'],6); 
                            $destino = $linha['DESTINO']; 
                            $motivo = $linha['MOTIVO']; ?>
                            <tr>
                                <td class="align-lef sem-borda"><?=$nome?></td>
                                <td class="align-cen nowrap sem-borda"><?=$matricula?></td>
                                <td class="align-cen nowrap sem-borda"><?=$raiocela?></td>
                                <td class="align-cen sem-borda"><?=$horario?></td>
                                <td class="align-cen sem-borda"><?=$destino?></td>
                                <td class="align-rig sem-borda"><?=$motivo?></td>
                            </tr>
                            
                            <?php
                        }
                        ?>
                    </table>
                </table>
                <?php
            }

            // Busca as apresentações externas
            $params=[$idboletim];

            $sql = "SELECT CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CD.MATRICULA, FUNCT_dados_raio_cela_preso(EP.ID, CA.DATAHORASAIDA, 2) RAIO, FUNCT_dados_raio_cela_preso(EP.ID, CA.DATAHORASAIDA, 3) CELA, COA.DATASAIDA, CLA.NOMEABREVIADO DESTINO
            FROM cimic_apresentacoes CA
            INNER JOIN entradas_presos EP ON EP.ID = CA.IDPRESO
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN cimic_ordens_apresentacoes COA ON COA.ID = CA.IDORDEMSAIDAMOV
            INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = COA.IDDESTINO
            WHERE CA.IDBOLETIMSAIDA = ? AND CA.IDEXCLUSOREGISTRO IS NULL AND COA.IDEXCLUSOREGISTRO IS NULL ORDER BY CD.NOME;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();
            
            if(count($resultado)){
                
                $plural = count($resultado)>1?"Apresentações Externas":"Apresentação Externa"; ?>
                
                <table style="text-align: center; width: 100%;">
                    <h4 style="margin-bottom: 0;"><b><?=$plural?></b></h4>

                    <table style="font-size: 10pt; width: 100%; border-collapse: collapse;">
                        <tr>
                            <th class="align-lef sem-borda">Nome</th>
                            <th class="sem-borda">Matrícula</th>
                            <th class="sem-borda">R/C</th>
                            <th class="sem-borda">Horário</th>
                            <th class="align-rig sem-borda">Destino</th>
                        </tr> <?php

                        foreach($resultado as $linha){
                            $nome = $linha['NOME'];
                            $matricula=$linha['MATRICULA']!=null?midMatricula($linha['MATRICULA'],3):"N/C";
                            $raiocela = $linha['RAIO']!=null?$linha['RAIO']:"N/C";
                            $raiocela .= ($linha['CELA']!=null && $linha['CELA']>0)?"/". $linha['CELA']:"";
                            $horario = retornaDadosDataHora($linha['DATASAIDA'],6); 
                            $destino = $linha['DESTINO'];?>

                            <tr>
                                <td class="align-lef sem-borda"><?=$nome?></td>
                                <td class="align-cen nowrap sem-borda"><?=$matricula?></td>
                                <td class="align-cen nowrap sem-borda"><?=$raiocela?></td>
                                <td class="align-cen sem-borda"><?=$horario?></td>
                                <td class="align-rig sem-borda"><?=$destino?></td>
                            </tr>
                            
                            <?php
                        } ?>
                    </table>
                </table>
                <?php
            }
            
            // Busca as apresentações internas
            $params=[$idboletim];

            $sql = "SELECT CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CD.MATRICULA, FUNCT_dados_raio_cela_preso(EP.ID, concat(CAI.DATASAIDA, ' ', CAIP.HORAAPRES), 2) RAIO, FUNCT_dados_raio_cela_preso(EP.ID, concat(CAI.DATASAIDA, ' ', CAIP.HORAAPRES), 3) CELA, concat(CAI.DATASAIDA, ' ', CAIP.HORAAPRES) DATASAIDA, CLA.NOMEABREVIADO DESTINO
            FROM cimic_apresentacoes_internas_presos CAIP
            INNER JOIN entradas_presos EP ON EP.ID = CAIP.IDPRESO
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN cimic_apresentacoes_internas CAI ON CAI.ID = CAIP.IDAPRES
            INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = CAI.IDDESTINO
            WHERE CAIP.IDBOLETIM = ? AND CAIP.IDEXCLUSOREGISTRO IS NULL AND CAI.IDEXCLUSOREGISTRO IS NULL ORDER BY CD.NOME;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();
            
            if(count($resultado)){
                
                $plural = count($resultado)>1?"Apresentações Internas / Teleaudiências":"Apresentação Interna / Teleaudiência"; ?>
                
                <table style="text-align: center; width: 100%;">
                    <h4 style="margin-bottom: 0;"><b><?=$plural?></b></h4>

                    <table style="font-size: 10pt; width: 100%; border-collapse: collapse;">
                        <tr>
                            <th class="align-lef sem-borda">Nome</th>
                            <th class="sem-borda">Matrícula</th>
                            <th class="sem-borda">R/C</th>
                            <th class="sem-borda">Horário</th>
                            <th class="align-rig sem-borda">Local</th>
                        </tr> <?php

                        foreach($resultado as $linha){
                            $nome = $linha['NOME'];
                            $matricula=$linha['MATRICULA']!=null?midMatricula($linha['MATRICULA'],3):"N/C";
                            $raiocela = $linha['RAIO']!=null?$linha['RAIO']:"N/C";
                            $raiocela .= ($linha['CELA']!=null && $linha['CELA']>0)?"/". $linha['CELA']:"";
                            $horario = retornaDadosDataHora($linha['DATASAIDA'],6); 
                            $destino = $linha['DESTINO'];?>

                            <tr>
                                <td class="align-lef sem-borda"><?=$nome?></td>
                                <td class="align-cen nowrap sem-borda"><?=$matricula?></td>
                                <td class="align-cen nowrap sem-borda"><?=$raiocela?></td>
                                <td class="align-cen sem-borda"><?=$horario?></td>
                                <td class="align-rig sem-borda"><?=$destino?></td>
                            </tr>
                            
                            <?php
                        } ?>
                    </table>
                </table>
                <?php
            }

            // Busca os atendimentos de enfermaria
            $params=[$idboletim];

            $sql = "SELECT CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CD.MATRICULA, FUNCT_dados_raio_cela_preso(EP.ID, concat(REQ.DATAATEND, ' ', ENF.HORAATEND), 2) RAIO, FUNCT_dados_raio_cela_preso(EP.ID, concat(REQ.DATAATEND, ' ', ENF.HORAATEND), 3) CELA, concat(REQ.DATAATEND, ' ', ENF.HORAATEND) DATAATEND, CAT.NOME TIPO
            FROM enf_atendimentos ENF
            INNER JOIN entradas_presos EP ON EP.ID = ENF.IDPRESO
            INNER JOIN enf_atendimentos_requis REQ ON REQ.ID = ENF.IDREQ
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN chefia_atendimentostipo CAT ON CAT.ID = ENF.IDTIPOATEND
            WHERE ENF.IDBOLETIM = ? AND ENF.IDEXCLUSOREGISTRO IS NULL AND REQ.IDEXCLUSOREGISTRO IS NULL ORDER BY CAT.NOME, CD.NOME;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();
            
            if(count($resultado)){
                
                $plural = count($resultado)>1?"Atendimentos Saúde":"Atendimento Saúde"; ?>
                
                <table style="text-align: center; width: 100%;">
                    <h4 style="margin-bottom: 0;"><b><?=$plural?></b></h4>

                    <table style="font-size: 10pt; width: 100%; border-collapse: collapse;">
                        <tr>
                            <th class="align-lef sem-borda">Nome</th>
                            <th class="sem-borda">Matrícula</th>
                            <th class="sem-borda">R/C</th>
                            <th class="sem-borda">Horário</th>
                            <th class="align-rig sem-borda">Tipo</th>
                        </tr> <?php

                        foreach($resultado as $linha){
                            $nome = $linha['NOME'];
                            $matricula=$linha['MATRICULA']!=null?midMatricula($linha['MATRICULA'],3):"N/C";
                            $raiocela = $linha['RAIO']!=null?$linha['RAIO']:"N/C";
                            $raiocela .= ($linha['CELA']!=null && $linha['CELA']>0)?"/". $linha['CELA']:"";
                            $horario = retornaDadosDataHora($linha['DATAATEND'],6); 
                            $tipoatend = $linha['TIPO'];?>

                            <tr>
                                <td class="align-lef sem-borda"><?=$nome?></td>
                                <td class="align-cen nowrap sem-borda"><?=$matricula?></td>
                                <td class="align-cen nowrap sem-borda"><?=$raiocela?></td>
                                <td class="align-cen sem-borda"><?=$horario?></td>
                                <td class="align-rig sem-borda"><?=$tipoatend?></td>
                            </tr>
                            
                            <?php
                        } ?>
                    </table>
                </table>
                <?php
            }

            // Busca os demais atendimentos
            $params=[$idboletim];

            $sql = "SELECT CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CD.MATRICULA, FUNCT_dados_raio_cela_preso(EP.ID, REQ.DATAATEND, 2) RAIO, FUNCT_dados_raio_cela_preso(EP.ID, REQ.DATAATEND, 3) CELA, REQ.DATAATEND DATAATEND, concat(CAT.ABREVIACAO, CASE WHEN REQ.REQUISITANTE IS NOT NULL THEN concat(' ', REQ.REQUISITANTE) ELSE '' END) TIPO
            FROM chefia_atendimentos CATD
            INNER JOIN chefia_atendimentos_requis REQ ON REQ.ID = CATD.IDREQ
            INNER JOIN entradas_presos EP ON EP.ID = CATD.IDPRESO
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN chefia_atendimentostipo CAT ON CAT.ID = REQ.IDTIPOATEND
            WHERE CATD.IDBOLETIM = ? AND CATD.IDEXCLUSOREGISTRO IS NULL AND REQ.IDEXCLUSOREGISTRO IS NULL ORDER BY CAT.NOME, CD.NOME;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();
            
            if(count($resultado)){
                
                $plural = count($resultado)>1?"Atendimentos Diversos":"Atendimento Diverso"; ?>
                
                <table style="text-align: center; width: 100%;">
                    <h4 style="margin-bottom: 0;"><b><?=$plural?></b></h4>

                    <table style="font-size: 10pt; width: 100%; border-collapse: collapse;">
                        <tr>
                            <th class="align-lef sem-borda">Nome</th>
                            <th class="sem-borda">Matrícula</th>
                            <th class="sem-borda">R/C</th>
                            <th class="sem-borda">Horário</th>
                            <th class="align-rig sem-borda">Tipo</th>
                        </tr> <?php

                        foreach($resultado as $linha){
                            $nome = $linha['NOME'];
                            $matricula=$linha['MATRICULA']!=null?midMatricula($linha['MATRICULA'],3):"N/C";
                            $raiocela = $linha['RAIO']!=null?$linha['RAIO']:"N/C";
                            $raiocela .= ($linha['CELA']!=null && $linha['CELA']>0)?"/". $linha['CELA']:"";
                            $horario = retornaDadosDataHora($linha['DATAATEND'],6); 
                            $tipoatend = $linha['TIPO'];?>

                            <tr>
                                <td class="align-lef sem-borda"><?=$nome?></td>
                                <td class="align-cen nowrap sem-borda"><?=$matricula?></td>
                                <td class="align-cen nowrap sem-borda"><?=$raiocela?></td>
                                <td class="align-cen sem-borda"><?=$horario?></td>
                                <td class="align-rig sem-borda"><?=$tipoatend?></td>
                            </tr>
                            
                            <?php
                        } ?>
                    </table>
                </table>
                <?php
            }

            // Cria a tabela temporária para as mudanças de cela e designação de trabalho
            $sqltemporaria = "CREATE TEMPORARY TABLE consulta_ger_raio (
                ID INT auto_increment, PRIMARY KEY(ID),
                TABELA INT NOT NULL,
                MATRICULA INT NOT NULL,
                NOME varchar(255) DEFAULT NULL,
                IDPRESO INT NOT NULL,
                RAIOATUAL varchar(5),
                CELAATUAL INT NOT NULL,
                RAIODESTINO varchar(10) DEFAULT NULL,
                CELADESTINO varchar(10) DEFAULT NULL,
                DATAMOV datetime NOT NULL,
                IDSITUACAO INT NOT NULL,
                IDMUDANCA INT DEFAULT NULL) DEFAULT CHAR SET UTF8;";

            $stmt = $GLOBALS['conexao']->prepare($sqltemporaria);
            $stmt->execute();

            //Abre nova conexão para zerar a tabela temporária
            $conexaoStatus = conectarBD();
            $stmt = $GLOBALS['conexao']->prepare($sqltemporaria);
            $stmt->execute();

            $params=[$idboletim,$idboletim];

            // Busca mudanças de cela
            $sql = "INSERT INTO consulta_ger_raio (TABELA, MATRICULA, NOME, IDPRESO, RAIOATUAL, CELAATUAL, RAIODESTINO, CELADESTINO, DATAMOV, IDSITUACAO)
                
            SELECT 1 TABELA, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CHEMC.IDPRESO, RC2.NOME RAIOATUAL, CADMC.CELA CELAATUAL, RC.NOME RAIODESTINO, CHEMC.CELADESTINO, CASE WHEN CHEMC.IDSITUACAO = 6 THEN (SELECT DATACADASTRO FROM chefia_mudancacelasituacao WHERE IDMUDANCA = CHEMC.ID ORDER BY ID DESC LIMIT 1) ELSE CHEMC.DATACADASTRO END DATAMOV, CHEMC.IDSITUACAO
            FROM chefia_mudancacela CHEMC
            INNER JOIN entradas_presos EP ON EP.ID = CHEMC.IDPRESO
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            LEFT JOIN tab_raioscelas RC ON RC.ID = CHEMC.RAIODESTINO
            INNER JOIN cadastros_mudancacela CADMC ON CADMC.ID = CHEMC.IDCELAATUAL
            INNER JOIN tab_raioscelas RC2 ON RC2.ID = CADMC.RAIO
            WHERE CHEMC.IDBOLETIM = ? AND CHEMC.IDEXCLUSOREGISTRO IS NULL AND CHEMC.IDSITUACAO = 6
            
            UNION

            SELECT 7 TABELA, CD.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, EXC.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, 'ALV' RAIODESTINO, NULL CELADESTINO, EXC.DATASAIDA DATAMOV, EXC.IDSITUACAO
            FROM cimic_exclusoes EXC
            INNER JOIN entradas_presos EP ON EP.ID = EXC.IDPRESO
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            WHERE EXC.IDBOLETIM = ? AND EXC.IDEXCLUSOREGISTRO IS NULL;
            ";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);

            //Preenche todos raios e celas
            $sql = "UPDATE consulta_ger_raio SET RAIOATUAL = FUNCT_dados_raio_cela_preso(IDPRESO, DATAMOV, 2), CELAATUAL = FUNCT_dados_raio_cela_preso(IDPRESO, DATAMOV, 3) WHERE RAIOATUAL = '0';

            UPDATE consulta_ger_raio SET IDMUDANCA = FUNCT_dados_raio_cela_preso(IDPRESO, DATAMOV, 4)";
            
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute();
            
            $sql = "SELECT CONS.*, FUNCT_dados_cela_excecao (CONS.IDMUDANCA,3) DESIG, FUNCT_dados_cela_excecao (CONS.IDMUDANCA,4) DESLIG FROM consulta_ger_raio CONS  ORDER BY NOME";
            // $sql = "SELECT * FROM consulta_ger_raio";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll();

            // pre_r($resultado); exit();

            $arrdesligamento = [];
            $arrdesignacao = [];
            $arrmudancas = [];
            
            foreach($resultado as $mudanca){
                if($mudanca['DESIG']>0 && $mudanca['TABELA']==1){
                    array_push($arrdesignacao,$mudanca);
                }
                if($mudanca['DESLIG']>0 && $mudanca['TABELA']==7){
                    array_push($arrdesligamento,$mudanca);
                }
                if($mudanca['TABELA']==1){
                    array_push($arrmudancas,$mudanca);
                }
            }

            $colspan = 1;
            
            if(count($arrdesligamento) || count($arrdesignacao) || count($arrmudancas)){                
                for($repete=0;$repete<3;$repete++){
                    $blncabecalho = false;

                    // 1º vez coloca os desligamentos
                    if($repete==0){
                        $arr = $arrdesligamento;
                        $plural = count($arr)>1?"Desligamentos de Trabalho":"Desligamento de Trabalho";
                        $colspan = 2;
                    }
                    // 2º vez coloca as designações
                    elseif($repete==1){
                        $arr = $arrdesignacao;
                        $plural = count($arr)>1?"Deginações para Trabalho":"Designação para Trabalho";
                        $colspan = 2;
                    }
                    // 3º vez coloca todas mudanças de celas
                    elseif($repete==2){
                        $arr = $arrmudancas;
                        $plural = count($arr)>1?"Mudanças de Celas":"Mudança de Cela";
                        $colspan = 1;
                    } 

                    foreach($arr as $linha){
                        
                        if(!$blncabecalho){ ?>

                            <table style="text-align: center; width: 100%;">
                                <h4 style="margin-bottom: 0;"><b><?=$plural?></b></h4> 
                                <table style="font-size: 10pt; width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <th class="align-lef sem-borda">Nome</th>
                                        <th class="sem-borda">Matrícula</th> <?php
                                        
                                        if(in_array($repete,array(0,2))){ ?>
                                            <th class="sem-borda" colspan="<?=$colspan?>">Origem</th> <?php
                                        }
                                        if(in_array($repete,array(1,2))){ ?>
                                            <th class="sem-borda" colspan="<?=$colspan?>">Destino</th> <?php
                                        } ?>
                                        
                                    </tr> <?php

                            $blncabecalho = true;
                        } 

                        $nome = $linha['NOME'];
                        $matricula=$linha['MATRICULA']!=null?midMatricula($linha['MATRICULA'],3):"N/C";
                        $raiocela = $linha['RAIOATUAL']!=null?$linha['RAIOATUAL']:"N/C";
                        $raiocela .= ($linha['CELAATUAL']!=null && $linha['CELAATUAL']>0)?"/". $linha['CELAATUAL']:"";
                        $raioceladestino = $linha['RAIODESTINO'];
                        $raioceladestino .= ($linha['CELADESTINO']!=null && $linha['CELADESTINO']>0)?"/". $linha['CELADESTINO']:""; ?>

                        <tr>
                            <td class="align-lef sem-borda"><?=$nome?></td>
                            <td class="align-cen nowrap sem-borda"><?=$matricula?></td> <?php
                                        
                            if(in_array($repete,array(0,2))){ ?>
                                <td class="align-cen nowrap sem-borda" colspan="<?=$colspan?>"><?=$raiocela?></td> <?php
                            }
                            if(in_array($repete,array(1,2))){ ?>
                                <td class="align-cen nowrap sem-borda" colspan="<?=$colspan?>"><?=$raioceladestino?></td> <?php
                            } ?>

                        </tr> <?php

                        if($arr[count($arr)-1] == $linha){?>
                            </table></table> <?php
                        }
                    }
                }
            }
            
            if(count($resultadocontagem)){
                $somatotal = 0; ?>
                
                <table style="text-align: center; width: 100%;">
                    <h4 style="margin-bottom: 0;"><b>População Carcerária</b></h4>

                    <table style="font-size: 12pt; width: 100%; border-collapse: collapse;"> <?php

                        foreach($resultadocontagem as $linha){
                            $nomeraio = $linha['NOMERAIO'];
                            $qtd = $linha['QTD'];
                            $somatotal += $qtd; ?>

                            <tr>
                                <td style="border: none; border-bottom: 1px solid black; text-align: left; padding: 0px 15px;"><?=$nomeraio?></td>
                                <td style="border: none; border-bottom: 1px solid black; text-align: right; padding: 0px 15px;"><?=$qtd?></td>
                            </tr>
                            
                            <?php
                        } ?>

                        <tr style="font-size: 14pt; background-color: lightgray;">
                            <td style="border: none; border-bottom: 1px solid black; text-align: left; padding: 0px 15px;">População Total</td>
                            <td style="border: none; border-bottom: 1px solid black; text-align: right; padding: 0px 15px;"><?=$somatotal?></td>
                        </tr>

                    </table>

                    <!-- Assinatur do diretor (Deixar dentro da table para não ser imprimido somente o nome do diretor caso fique somente esse campo para fora da folha) -->
                    <div class="assinatura" style="position: absolute; bottom: 10px; width: 100%;">
                        <p class="align-cen padding-margin-0"><?=$nomediretor?></p>
                        <p class="align-cen padding-margin-0"><?=$nomepermissao?></p>
                    </div>

                </table>


                <?php
            }else{
                echo '<li><p style="background-color: lightcoral;">******** A contagem de Final de Plantão não foi encontrada. ********</p></li>';
                //Pode compilar, mas exibirá a mensagem no começo e no final do documento
                 // $blnnaocompilar = true;
                // exit();
            }
    

        }else{?>

            <h2 style="color: rgb(200, 51, 25);">Regularize as pendências acima e atualize a página novamente para gerar o Boletim Informativo.</h2>    
            <?php
        }
        
    }else{
        echo $conexaoStatus;
        exit();
    }
