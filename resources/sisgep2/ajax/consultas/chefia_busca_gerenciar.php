<?php

    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    $tipo = $_POST['tipo'];
    $idvisualizacao = isset($_POST['idvisualizacao'])?$_POST['idvisualizacao']:0;
    $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    $idtipoatend = isset($_POST['idtipoatend'])?$_POST['idtipoatend']:0;
    $idsolicitacao = isset($_POST['idsolicitacao'])?$_POST['idsolicitacao']:0;
    $idraio = isset($_POST['idraio'])?$_POST['idraio']:0;
    $cela = isset($_POST['cela'])?$_POST['cela']:0;
    $blnvisuchefia = isset($_POST['blnvisuchefia'])?$_POST['blnvisuchefia']:false;
    $idtipoproced = isset($_POST['idtipoproced'])?$_POST['idtipoproced']:0;
    $idboletim = isset($_POST['idboletim'])?$_POST['idboletim']:0;
    $idtipodiretor = isset($_POST['idtipodiretor'])?$_POST['idtipodiretor']:0;
    $idtipocontagem = isset($_POST['idtipocontagem'])?$_POST['idtipocontagem']:0;
    $tabela = isset($_POST['tabela'])?$_POST['tabela']:0;
    $boletimvigente = isset($_POST['boletimvigente'])?$_POST['boletimvigente']:0;

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){ 
        try {
            //Busca tabela do Gerenciar raio e chefia
            if($tipo==1){
                $arrvisu=retornaRaiosDaVisualizacao($idvisualizacao,5,$blnvisuchefia);
                
                $arrayencerrado = [6,7,8,9,13,15,19]; //ids das situações de encerrado dos raios;
                if($blnvisuchefia==1){
                    $arrayencerrado = [6,7,8,9,13,15,19]; //ids das situações de encerrado da chefia;
                }

                $exibir = isset($_POST['exibir'])?$_POST['exibir']:1;

                $ordemreg = $_POST['ordem'];
            
                if($ordemreg==1){
                    $ordem = 'MATRICULA';
                }elseif($ordemreg==2){
                    $ordem = 'NOME';
                }elseif($ordemreg==3){
                    $ordem = 'DATAMOV';
                }else{
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Ordenação não informada corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }
            
                //PRIMEIRO FAZ OS SETS DAS VARIÁVEIS LOCAIS QUE VÃO SER USADAS
                $sql = retornaQueryDadosBoletimVigente();
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();

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
                    DATAMOV datetime NOT NULL,
                    OBSERVACOES mediumtext DEFAULT NULL,
                    TIPO varchar(255) NOT NULL,
                    IDSITUACAO INT NOT NULL,
                    SITUACAO varchar(255) NOT NULL,
                    IDMUDANCA INT DEFAULT NULL/*,
                    IDDESIG INT DEFAULT NULL*/) DEFAULT CHAR SET UTF8;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();

                // CASE WHEN CHEMC.IDSITUACAO = 13 THEN (SELECT DATACADASTRO FROM chefia_mudancacelasituacao WHERE IDREFERENCIA = CHEMC.ID ORDER BY ID DESC LIMIT 1) ELSE CHEMC.DATACADASTRO END
                $sql = "INSERT INTO consulta_ger_raio (TABELA, IDMOVIMENTACAO, MATRICULA, NOME, IDPRESO, RAIOATUAL, CELAATUAL, RAIODESTINO, CELADESTINO, DATAMOV, OBSERVACOES, TIPO, IDSITUACAO, SITUACAO)
                
                SELECT 1 TABELA, CHEMC.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CHEMC.IDPRESO, RC2.NOME RAIOATUAL, CADMC.CELA CELAATUAL, RC.NOME RAIODESTINO, CHEMC.CELADESTINO, CASE WHEN CHEMC.IDSITUACAO = 6 THEN (SELECT DATACADASTRO FROM chefia_mudancacelasituacao WHERE IDMUDANCA = CHEMC.ID ORDER BY ID DESC LIMIT 1) ELSE CHEMC.DATACADASTRO END DATAMOV, CHEMC.OBSERVACOES, CASE WHEN CADMC.RAIO = CHEMC.RAIODESTINO THEN 'Mudança de Cela' WHEN CADMC.RAIO <> CHEMC.RAIODESTINO AND CHEMC.RAIODESTINO IS NOT NULL THEN 'Mudança de Raio' ELSE 'Mudança Raio/Cela' END TIPO, CHEMC.IDSITUACAO, SIT.NOME SITUACAO
                FROM chefia_mudancacela CHEMC
                INNER JOIN entradas_presos EP ON EP.ID = CHEMC.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                LEFT JOIN tab_raioscelas RC ON RC.ID = CHEMC.RAIODESTINO
                INNER JOIN cadastros_mudancacela CADMC ON CADMC.ID = CHEMC.IDCELAATUAL
                INNER JOIN tab_raioscelas RC2 ON RC2.ID = CADMC.RAIO
                INNER JOIN tab_situacao SIT ON SIT.ID = CHEMC.IDSITUACAO
                WHERE CHEMC.IDBOLETIM = @intIDBoletim AND CHEMC.IDEXCLUSOREGISTRO IS NULL
                
                UNION ";

                $sql .= "SELECT 2 TABELA, CT.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CT.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, COT.DATASAIDA DATAMOV, NULL OBSERVACOES, concat(MT.MOTIVOFINAL, ' ', UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE) TIPO, CT.IDSITUACAO, SIT.NOME SITUACAO
                FROM cimic_transferencias CT
                INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN cimic_ordens_transferencias COT ON COT.ID = CT.IDORDEMSAIDAMOV
                INNER JOIN tab_unidades UN ON UN.ID = COT.IDDESTINO
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                INNER JOIN tab_movimentacoestipo MT ON MT.ID = CT.IDTIPOMOV
                INNER JOIN tab_situacao SIT ON SIT.ID = CT.IDSITUACAO
                WHERE CT.IDEXCLUSOREGISTRO IS NULL AND 
                COT.DATASAIDA >= @dataInicio AND COT.DATASAIDA <= @dataFim
                
                UNION ";

                $sql .= "SELECT 3 TABELA, CA.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CA.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, COA.DATASAIDA DATAMOV, NULL OBSERVACOES, CLA.NOMEABREVIADO TIPO, CA.IDSITUACAO, SIT.NOME SITUACAO
                FROM cimic_apresentacoes CA
                INNER JOIN entradas_presos EP ON EP.ID = CA.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN cimic_ordens_apresentacoes COA ON COA.ID = CA.IDORDEMSAIDAMOV
                INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = COA.IDDESTINO
                INNER JOIN tab_situacao SIT ON SIT.ID = CA.IDSITUACAO
                WHERE CA.IDEXCLUSOREGISTRO IS NULL AND 
                COA.DATASAIDA >= @dataInicio AND COA.DATASAIDA <= @dataFim
                
                UNION ";

                $sql .= "SELECT 4 TABELA, CAIP.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CAIP.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, concat(CAI.DATASAIDA, ' ', CAIP.HORAAPRES) DATAMOV, NULL OBSERVACOES, CLA.NOMEABREVIADO TIPO, CAIP.IDSITUACAO, SIT.NOME SITUACAO
                FROM cimic_apresentacoes_internas_presos CAIP
                INNER JOIN entradas_presos EP ON EP.ID = CAIP.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN cimic_apresentacoes_internas CAI ON CAI.ID = CAIP.IDAPRES
                INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = CAI.IDDESTINO
                INNER JOIN tab_situacao SIT ON SIT.ID = CAIP.IDSITUACAO
                WHERE CAIP.IDEXCLUSOREGISTRO IS NULL AND 
                concat(CAI.DATASAIDA, ' ', CAIP.HORAAPRES) >= @dataInicio AND concat(CAI.DATASAIDA, ' ', CAIP.HORAAPRES) <= @dataFim
                
                UNION ";

                $sql .= "SELECT 5 TABELA, ENF.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, ENF.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, concat(REQ.DATAATEND, ' ', ENF.HORAATEND) DATAMOV, ENF.OBSERVACOES, CAT.NOME TIPO, ENF.IDSITUACAO, SIT.NOME SITUACAO 
                FROM enf_atendimentos ENF
                INNER JOIN entradas_presos EP ON EP.ID = ENF.IDPRESO
                INNER JOIN enf_atendimentos_requis REQ ON REQ.ID = ENF.IDREQ
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN chefia_atendimentostipo CAT ON CAT.ID = ENF.IDTIPOATEND
                INNER JOIN tab_situacao SIT ON SIT.ID = ENF.IDSITUACAO
                LEFT JOIN enf_atendimentos ENF2 ON ENF2.ID = ENF.ID
                WHERE concat(REQ.DATAATEND, ' ', ENF.HORAATEND) IS NOT NULL AND ENF.IDEXCLUSOREGISTRO IS NULL AND 
                concat(REQ.DATAATEND, ' ', ENF.HORAATEND) >= @dataInicio AND concat(REQ.DATAATEND, ' ', ENF.HORAATEND) <= @dataFim
                
                UNION ";

                $sql .= "SELECT 6 TABELA, CATD.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, CATD.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, REQ.DATAATEND DATAMOV, NULL OBSERVACOES, concat(CAT.ABREVIACAO, CASE WHEN REQ.REQUISITANTE IS NOT NULL THEN concat(' ', REQ.REQUISITANTE) ELSE '' END) TIPO, CATD.IDSITUACAO, SIT.NOME SITUACAO
                FROM chefia_atendimentos CATD
                INNER JOIN chefia_atendimentos_requis REQ ON REQ.ID = CATD.IDREQ
                INNER JOIN entradas_presos EP ON EP.ID = CATD.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN chefia_atendimentostipo CAT ON CAT.ID = REQ.IDTIPOATEND
                INNER JOIN tab_situacao SIT ON SIT.ID = CATD.IDSITUACAO
                WHERE CATD.IDEXCLUSOREGISTRO IS NULL AND 
                REQ.DATAATEND >= @dataInicio AND REQ.DATAATEND <= @dataFim
                
                UNION ";

                $sql .= "SELECT 7 TABELA, EXC.ID IDMOVIMENTACAO, EP.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, EXC.IDPRESO, 0 RAIOATUAL, 0 CELAATUAL, NULL RAIODESTINO, NULL CELADESTINO, EXC.DATASAIDA DATAMOV, NULL OBSERVACOES, concat(MT.NOME, ' - ', MOV.NOME) TIPO, EXC.IDSITUACAO, SIT.NOME SITUACAO
                FROM cimic_exclusoes EXC
                INNER JOIN tab_movimentacoesmotivos MOV ON MOV.ID = EXC.IDMOTIVO
                INNER JOIN tab_movimentacoestipo MT ON MT.ID = EXC.IDTIPO
                INNER JOIN entradas_presos EP ON EP.ID = EXC.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN tab_situacao SIT ON SIT.ID = EXC.IDSITUACAO
                WHERE EXC.IDEXCLUSOREGISTRO IS NULL AND (EXC.IDBOLETIM IS NULL OR EXC.IDBOLETIM = @intIDBoletim)

                ORDER BY $ordem";

                // echo json_encode($sql); exit();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();

                //Preenche todos raios e celas
                $sql = "UPDATE consulta_ger_raio SET RAIOATUAL = FUNCT_dados_raio_cela_preso(IDPRESO, DATAMOV, 2), CELAATUAL = FUNCT_dados_raio_cela_preso(IDPRESO, DATAMOV, 3) WHERE RAIOATUAL = '0';
                
                UPDATE consulta_ger_raio SET IDMUDANCA = FUNCT_dados_raio_cela_preso(IDPRESO, DATAMOV, 4) WHERE (TABELA = 7 AND IDSITUACAO = 13) OR (TABELA = 1 AND IDSITUACAO = 6);";
                // echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." $ordem</li>"));exit();
                
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();

                $strexibir = "";
                $params = [];
                foreach($arrayencerrado as $id){
                    if($strexibir==""){
                        $strexibir = "?";
                    }else{
                        $strexibir .= ",?";
                    }
                    array_push($params,$id);
                }

                if($exibir==1){
                    $strexibir = "AND IDSITUACAO NOT IN ($strexibir)";
                }elseif($exibir==2){
                    $strexibir = "AND IDSITUACAO IN ($strexibir)";
                }else{
                    $strexibir = '';
                    $params = [];
                }

                $sql = "SELECT CONS.*, FUNCT_dados_cela_excecao (CONS.IDMUDANCA,1) DESIG FROM consulta_ger_raio CONS WHERE (RAIOATUAL IN ($arrvisu) OR RAIODESTINO IN ($arrvisu)) $strexibir";
                // $sql = "SELECT * FROM consulta_ger_raio";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                $retorno = [];
                // $retorno="";
                // $contador = 0;
                unset($GLOBALS['conexao']);

                //echo json_encode($resultado);exit();

                foreach($resultado as $dados){
                    $idpreso = $dados['IDPRESO'];
                    $datamov = $dados['DATAMOV'];
                    if($dados['RAIOATUAL']==0){
                        // $dadoscela = buscaDadosRaioCelaPreso($idpreso,$datamov);
                        // $dados['RAIOATUAL'] = $dadoscela['RAIO'];
                        // $dados['CELAATUAL'] = $dadoscela['CELA'];
                        $dados['RAIOATUAL'] = "Erro Raio";
                        $dados['CELAATUAL'] = "Erro Cela";
                    }
                    $raioatual = $dados['RAIOATUAL'];
                    $raiocelaatual = $dados['RAIOATUAL']."/".$dados['CELAATUAL'];
                    
                    $tabela = $dados['TABELA'];
                    $idmovimentacao = $dados['IDMOVIMENTACAO'];
                    $matr = $dados['MATRICULA'];
                    if($matr>0){
                        $matricula = midMatricula($matr,3);
                    }else{
                        $matricula = 'N/C';
                    }
                    $nome = $dados['NOME'];
                    if($nome==null){
                        $nome = buscaDadosLogPorPeriodo($matr,'NOME',3,retornaDadosDataHora($datamov,1));
                    }
                    if(strlen($datamov)>10){
                        $horario = retornaDadosDataHora($datamov,6);
                    }else{
                        $horario = '';
                    }
                    
                    $tipomov = $dados['TIPO'];
                    $situacao = $dados['SITUACAO'];
                    $idsituacao = $dados['IDSITUACAO'];
                    $idmudanca = $dados['IDMUDANCA'];
                    $iddesig = $dados['DESIG'];

                    if($idsituacao==5 || $idsituacao==12){
                        $coraprovado = true;
                    }else{
                        $coraprovado = false;
                    }

                    $raiodestino = null;
                    //Adicionar as cores, em caso de status aprovado, vai ficar intercalando com o verde até o preso sair para o atendimento ou concluir a mudança
                    switch ($tabela) {
                        case 1:
                            $cor = "cor-mudcela";
                            if($dados['RAIODESTINO']!=null){
                                $raiodestino = $dados['RAIODESTINO'];
                                $destino = $dados['RAIODESTINO']."/";
                                if($dados['CELADESTINO']!=null){
                                    $destino .= $dados['CELADESTINO'];
                                }else{
                                    $destino .= "?";
                                }
                            }else{
                                $destino = "?";
                            }
                            break;
                        
                        case 2:
                            $cor = "cor-transf";
                            $destino = "TRANSF";
                            break;
                        
                        case 3:
                            $cor = "cor-apres";
                            $destino = "TREXT";
                            break;
                        
                        case 4:
                            $cor = "cor-apresint";
                            $destino = "TELE";
                            break;
                        
                        case 5:
                            $cor = "cor-enf";
                            $destino = "ENF";
                            break;
                        
                        case 6:
                            $cor = "cor-atend";
                            $destino = "ATEND";
                            break;
                        
                        case 7:
                            $cor = "cor-excl";
                            $destino = "ALV";
                            break;
                        
                        default:
                            $destino = "";
                            break;
                    }
                    if((in_array($idsituacao,array(18)) && !$blnvisuchefia) || (in_array($idsituacao,array(6,13)))){
                            $cor = "cor-fundo-comum-tr";
                    }elseif(in_array($idsituacao,array(19))){
                        $cor = "cor-cancelado-agend";
                    }elseif(in_array($idsituacao,array(7,8,9,15))){
                        $cor = "cor-cancelado";
                    }

                    array_push($retorno,array('IDPRESO'=>$idpreso, 'DATAMOV'=>$datamov, 'RAIOCELAATUAL'=>$raiocelaatual, 'RAIOATUAL'=>$raioatual, 'TABELA'=>$tabela, 'IDMOVIMENTACAO'=>$idmovimentacao, 'MATRICULA'=>$matricula, 'MATR'=>$matr, 'NOME'=>$nome, 'HORARIO'=>$horario, 'DESTINO'=>$destino, 'RAIODESTINO'=>$raiodestino, 'TIPO'=>$tipomov, 'SITUACAO'=>$situacao, 'IDSITUACAO'=>$idsituacao, 'COR'=>$cor, 'APROVADO'=>$coraprovado, 'ORDEMREG'=>$ordemreg, 'IDMUDANCA'=>$idmudanca, 'DESIG'=>$iddesig));
                }
            }
            //Busca raios da visualização selecionada
            elseif($tipo==2){
                if($blnvisuchefia==false && $idvisualizacao==0){
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Tipo de retorno da consulta de raios da visualização não definido. </li>"));
                    exit();
                }
                //padrão de retorno é 4 pois retorna um array com todas informações dos raios da visualização
                $tiporetorno = isset($_POST['tiporetorno'])?$_POST['tiporetorno']:4;
                $resultado = retornaRaiosDaVisualizacao($idvisualizacao,$tiporetorno,$blnvisuchefia);
                $retorno = [];
                foreach($resultado as $raio){
                    array_push($retorno, array('VALOR' => $raio['ID'], 'NOMEEXIBIR' => $raio['NOME'], 'NOMECOMPLETO' => $raio['NOMECOMPLETO'], 'QTD' => $raio['QTD'], 'TOTAL' => $raio['TOTAL']));
                }
            }
            //Busca dados da mudança solicitada
            elseif($tipo==3 && $idmovimentacao!=0){
              
                $sql = "SELECT * FROM chefia_mudancacela WHERE ID = :idmovimentacao;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam(':idmovimentacao',$idmovimentacao,PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca dados do atendimento solicitado para enfermaria
            elseif($tipo==4 && $idsolicitacao!=0){
                
                $sql = "SELECT * FROM enf_atendimentos WHERE ID = :idsolicitacao;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idsolicitacao',$idsolicitacao,PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca histórico de atendimentos de enfermaria do preso informado
            elseif($tipo==5 && $idpreso!=0 && $idtipoatend!=0){
                
                $sql = "SELECT ENF.ID, ENF.DATASOLICITACAO, ENF.DESCPEDIDO, concat(REQ.DATAATEND, ' ',ENF.HORAATEND) DATAATEND, ENF.DESCATEND, CHEATD.NOME TIPOATEND, SIT.NOME SITUACAO, ENF.IDSITUACAO, NULL COR
                FROM enf_atendimentos ENF
                INNER JOIN chefia_atendimentostipo CHEATD ON CHEATD.ID = ENF.IDTIPOATEND
                LEFT JOIN enf_atendimentos_requis REQ ON REQ.ID = ENF.IDREQ
                INNER JOIN tab_situacao SIT ON SIT.ID = ENF.IDSITUACAO
                WHERE ENF.IDPRESO = :idpreso AND ENF.IDTIPOATEND = :idtipoatend AND ENF.IDEXCLUSOREGISTRO IS NULL ORDER BY ENF.DATASOLICITACAO DESC;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso',$idpreso,PDO::PARAM_INT);
                $stmt->bindParam('idtipoatend',$idtipoatend,PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetchAll();

                for($i=0;$i<count($resultado);$i++){
                    $cor = "cor-enf";
                    $idsituacao = $resultado[$i]['IDSITUACAO'];

                    if(in_array($idsituacao,array(6,13,18))){
                        $cor = "cor-fundo-comum-tr";
                    }elseif(in_array($idsituacao,array(19))){
                        $cor = "cor-cancelado-agend";
                    }elseif(in_array($idsituacao,array(8,9,15,16))){
                        $cor = "cor-cancelado";
                    }
                    $resultado[$i]['COR'] = $cor;
                }
                $retorno=$resultado;

            }
            //Busca solicitações de atendimentos da visualização informada
            elseif($tipo==6 && $idtipoatend!=0){
                
                if(($blnvisuchefia==false || $blnvisuchefia==0) && $idvisualizacao==0){
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Tipo de retorno da consulta de raios da visualização não definido. </li>"));
                    exit();
                }

                $idsraios = retornaRaiosDaVisualizacao($idvisualizacao,3,$blnvisuchefia);
                
                //PRIMEIRO FAZ OS SETS DAS VARIÁVEIS LOCAIS QUE VÃO SER USADAS
                $sql = retornaQueryDadosBoletimVigente();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                
                //TABELA TEMPORÁRIA PARA TRATAR INSERIR E TRATAR OS DADOS A SEREM RETORNADOS
                $sql =  "CREATE TEMPORARY TABLE consulta_atend (
                    ID INT auto_increment, primary key (ID),
                    TABELA INT NOT NULL,
                    IDSOLICITACAO INT NOT NULL,
                    IDPRESO INT NOT NULL,
                    MATRICULA INT default NULL,
                    NOME varchar(255),
                    DATASOLICITACAO DATETIME NOT NULL,
                    DESCPEDIDO varchar(255) NOT NULL,
                    DATAATEND DATETIME default NULL,
                    DESCATEND varchar(255) DEFAULT NULL,
                    IDTIPOATEND INT NOT NULL,
                    TIPOATEND varchar(255) DEFAULT NULL,
                    IDSITUACAO INT NOT NULL,
                    SITUACAO varchar (255) NOT NULL,
                    IDRAIO INT DEFAULT NULL,
                    RAIO VARCHAR(5) DEFAULT NULL,
                    CELA INT DEFAULT NULL) DEFAULT CHAR SET UTF8;";
                
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                
                #tabela enf_atendimento usar IDTABELA = 5
                $sql = "INSERT INTO consulta_atend (TABELA, IDSOLICITACAO, IDPRESO, MATRICULA, NOME, DATASOLICITACAO, DESCPEDIDO, DATAATEND, DESCATEND, IDTIPOATEND, TIPOATEND, IDSITUACAO, SITUACAO, IDRAIO, RAIO, CELA)
                SELECT 5 TABELA, ENF.ID, ENF.IDPRESO, EP.MATRICULA, CD.NOME, ENF.DATASOLICITACAO, ENF.DESCPEDIDO, ENF.HORAATEND, ENF.DESCATEND, ENF.IDTIPOATEND, CHEATD.NOME TIPOATEND, ENF.IDSITUACAO, SIT.NOME SITUACAO, FUNCT_dados_raio_cela_preso(ENF.IDPRESO,CURRENT_TIMESTAMP,1) IDRAIO, FUNCT_dados_raio_cela_preso(ENF.IDPRESO,CURRENT_TIMESTAMP,2) RAIO, FUNCT_dados_raio_cela_preso(ENF.IDPRESO,CURRENT_TIMESTAMP,3) CELA
                FROM enf_atendimentos ENF
                INNER JOIN entradas_presos EP ON EP.ID = ENF.IDPRESO
                INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN chefia_atendimentostipo CHEATD ON CHEATD.ID = ENF.IDTIPOATEND
                INNER JOIN tab_situacao SIT ON SIT.ID = ENF.IDSITUACAO
                WHERE ENF.DATASOLICITACAO >= @dataInicio AND ENF.DATASOLICITACAO <= @dataFim AND ENF.IDEXCLUSOREGISTRO IS NULL ORDER BY ENF.DATASOLICITACAO DESC;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                
                $sql = "SELECT *, NULL COR FROM consulta_atend WHERE IDTIPOATEND IN (:idtipoatend) AND IDRAIO IN ($idsraios);";
                
                
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idtipoatend',$idtipoatend,PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetchAll();
                
                for($i=0;$i<count($resultado);$i++){
                    $tabela = $resultado[$i]['TABELA'];
                    $idsituacao = $resultado[$i]['IDSITUACAO'];
                    switch ($tabela) {
                        case 5:
                            $cor = "cor-enf";
                            break;
                    }

                    if(in_array($idsituacao,array(6,13,18))){
                        $cor = "cor-fundo-comum-tr";
                    }elseif(in_array($idsituacao,array(19))){
                        $cor = "cor-cancelado-agend";
                    }elseif(in_array($idsituacao,array(8,9,15,16))){
                        $cor = "cor-cancelado";
                    }
                    $resultado[$i]['COR'] = $cor;
                }
                $retorno=$resultado;
            }
            //Busca quantidades de presos por cela do raio informado
            elseif($tipo==7 && $idraio!=0){
                
                $sql = "SELECT RC.ID IDRAIO, RC.NOME RAIO, RC.QTD QTDCELAS, CELA, COUNT(CELA) QTD
                FROM cadastros_mudancacela CADMC
                /*INNER JOIN cadastros CD ON CD.IDPRESO = CADMC.IDPRESO*/
                INNER JOIN tab_raioscelas RC ON RC.ID = CADMC.RAIO
                WHERE CADMC.RAIO = :idraio AND CADMC.RAIOALTERADO IS NULL GROUP BY CADMC.CELA ORDER BY CADMC.CELA;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idraio',$idraio,PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetchAll();
                
                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O Raio/Local informado não contém presos. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca presos por raio e cela para o book
            elseif($tipo==8 && $idraio!=0 && $cela!=0){
                
                $sql = "SELECT CADMC.ID IDMUD, CADMC.IDPRESO,
                CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME,
                CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.MATRICULA ELSE EP.MATRICULA END MATRICULA,
                CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.PAI ELSE EP.PAI END PAI,
                CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.MAE ELSE EP.MAE END MAE,
                RC.ID IDRAIO, RC.NOME RAIO, CADMC.CELA,
                COND.ANOS, COND.MESES, COND.DIAS, EP.IDTIPOMOV, MT.NOME TIPOMOV, GSA.NOME ORIGEM,
                E.DATAENTRADA, CADMC.DATACADASTRO,
                (SELECT GROUP_CONCAT(CV.NOME) FROM cadastros_vulgos CV 
                WHERE CV.IDPRESO = EP.ID AND CV.IDEXCLUSOREGISTRO IS NULL AND CV.DATAEXCLUSOREGISTRO IS NULL) VULGOS
                FROM cadastros_mudancacela CADMC
                INNER JOIN entradas_presos EP ON EP.ID = CADMC.IDPRESO
                INNER JOIN entradas E ON E.ID = EP.IDENTRADA
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN tab_raioscelas RC ON RC.ID = CADMC.RAIO
                INNER JOIN cadastros_condenacao COND ON COND.IDPRESO = EP.ID
                LEFT JOIN tab_movimentacoestipo MT ON MT.ID = EP.IDTIPOMOV
                LEFT JOIN codigo_gsa GSA ON GSA.ID = E.IDORIGEM
                WHERE CADMC.RAIO = ? AND CELA = ? AND CADMC.RAIOALTERADO IS NULL AND CADMC.IDEXCLUSOREGISTRO IS NULL ORDER BY CADMC.CELA, NOME;";

                $params = [$idraio,$cela];
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                
                if(count($resultado)){
                    $retorno = [];
                    foreach($resultado as $dados){
                        $caminhofoto = baixarFotoServidor($dados['IDPRESO'],1,'../../');
                        array_push($retorno,array(
                            'IDPRESO'=>$dados['IDPRESO'],
                            'NOME'=>$dados['NOME'],
                            'MATRICULA'=>$dados['MATRICULA'],
                            'PAI'=>$dados['PAI'],
                            'MAE'=>$dados['MAE'],
                            'RAIO'=>$dados['RAIO'],
                            'CELA'=>$dados['CELA'],
                            'DATACADASTRO'=>$dados['DATACADASTRO'],
                            'DATAENTRADA'=>$dados['DATAENTRADA'],
                            'ORIGEM'=>$dados['ORIGEM'],
                            'FOTO'=> $caminhofoto
                        ));
                    }
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> A Cela/Local informado não contém presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca dados dos atendimentos gerais da movimentação informada
            elseif($tipo==9){
                $idbanco = isset($_POST['idbanco'])?$_POST['idbanco']:0;
                $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;

                $idbusca = $idmovimentacao;
                
                $where = "CATD.IDREQ = :idbusca";
                if($idbanco!=0){
                    $where = "CATD.IDREQ = (SELECT IDREQ FROM chefia_atendimentos WHERE ID = :idbusca)";
                    $idbusca = $idbanco;
                }

                $sql = "SELECT REQ.ID IDMOVIMENTACAO, CATD.ID IDTEND, CATD.IDPRESO, REQ.DATAATEND, REQ.REQUISITANTE, CATD.IDSITUACAO, REQ.IDTIPOATEND
                FROM chefia_atendimentos CATD
                INNER JOIN chefia_atendimentos_requis REQ ON REQ.ID = CATD.IDREQ
                WHERE $where AND CATD.IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idbusca',$idbusca,PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca atendimentos gerais existentes no período informado
            elseif($tipo==10){
                
                $params = [];
                $datainicio = $_POST['datainicio'];
                array_push($params,$datainicio);
                $datafinal = $_POST['datafinal'];
                array_push($params,$datafinal);

                $where = "";
                if($idtipoatend!=0){
                    $where = "AND REQ.IDTIPOATEND = ?";
                    array_push($params,$idtipoatend);
                }

                $sql = "SELECT REQ.ID IDMOVIMENTACAO, REQ.DATAATEND, REQ.REQUISITANTE, CAT.NOME TIPOATEND,
                (SELECT COUNT(ID) FROM chefia_atendimentos WHERE IDREQ = REQ.ID) QUANTIDADE
                FROM chefia_atendimentos_requis REQ
                INNER JOIN chefia_atendimentostipo CAT ON CAT.ID = REQ.IDTIPOATEND
                WHERE REQ.DATAATEND >= ? AND REQ.DATAATEND <= ? $where AND REQ.IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para os dados informados. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca dados celas Bate Piso e Bate Grade
            elseif($tipo==11 && $idtipoproced>0){
                
                if($boletimvigente==1){
                    $sql = retornaQueryDadosBoletimVigente();
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute();
                
                    $sql = "SELECT @intIDBoletim IDBOLETIM";
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute();
                    
                    $resultado = $stmt->fetchAll();
                    $params=[$resultado[0]['IDBOLETIM']];
                }else{
                    $params=[$idboletim];
                }

                array_push($params,$idtipoproced);

                $sql = "SELECT CPBPG.ID, CPBPG.IDUSUARIO, CPBPG.IDRAIO, RC.NOME, RC.NOMECOMPLETO, CPBPG.CELA
                FROM chefia_proced_bate_piso_grade CPBPG
                INNER JOIN tab_raioscelas RC ON RC.ID = CPBPG.IDRAIO
                INNER JOIN tab_usuarios US ON US.ID = CPBPG.IDUSUARIO
                WHERE CPBPG.IDBOLETIM = ? AND CPBPG.IDPROCED = ? AND CPBPG.IDEXCLUSOREGISTRO IS NULL ORDER BY US.NOME,US.ID,RC.NOME,CPBPG.CELA;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para os dados informados. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca dados do boletim informativo do dia
            elseif($tipo==12){
                
                $sql = "SELECT BOL.*, TU.*, TU.NOME NOMETURNO FROM chefia_boletim BOL
                INNER JOIN tab_turnos TU ON TU.ID = BOL.IDTURNO
                WHERE BOL.BOLETIMDODIA = TRUE;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();        
                $resultado = $stmt->fetchAll();
   
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum dado de Boletim foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca dados do tipo de contagem informado
            elseif($tipo==13){
                $strtiposcontagem = isset($_POST['strtiposcontagem'])?$_POST['strtiposcontagem']:0;
                if($strtiposcontagem!=0){
                    $idtipocontagem = $strtiposcontagem;
                }else{
                    if($idtipocontagem==0){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Tipo de contagem não informado. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }
                $resultado = retornaDadosContagens($idtipocontagem,1);
                
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    $retorno = $resultado;
                    // echo json_encode($resultado);
                    // exit();
                }
                // else{
                    // $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> A contagem não foi encontrada. </li>");
                // }
                echo json_encode($retorno);
                exit();
            }
            //Busca se as todos os raios/celas já foram efetuadas do tipo de contagem informado
            elseif($tipo==14 && $idtipocontagem>0){
                
                $resultado = verificaLiberacaoContagens($idtipocontagem);
                echo json_encode($resultado);
                exit();
            }
            //Busca dados personalizados do boletim vigente (Ex: próximo turno, horário de início e término)
            elseif($tipo==15){
                $sql = retornaQueryDadosBoletimVigente();
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();        
                
                $sql = "SELECT @intIDBoletim IDBOLETIM, @blnDiurno PERIODODIURNO, @intIDTurno IDTURNO, @intIDDiretor IDDIRETOR, @intIDTurnoSeguinte IDTURNOSEGUINTE, @dataBoletimCurta DATABOLETIM, @dataInicio DATAINICIO, @dataFim DATAFIM, @nomeTurnoAtual NOMETURNO, @nomeTurnoSeguinte NOMETURNOSEGUINTE;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();        
                $resultado = $stmt->fetchAll();
   
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum dado de Boletim foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Busca horarios para serem alterados
            elseif($tipo==16 && $tabela>0 && $idmovimentacao>0){
                $coluna = '';

                switch($tabela){
                    case 6:
                        $sql = "SELECT DATAATEND DATAMOV FROM enf_atendimentos WHERE ID = :idmovimentacao";
                        break;
            
                    case 7:
                        $sql = "SELECT REQ.DATAATEND DATAMOV FROM chefia_atendimentos CAT
                        INNER JOIN chefia_atendimentos_requis REQ ON REQ.ID = CAT.IDREQ
                        WHERE CAT.ID = :idmovimentacao";
                        break;
            
                    case 8:
                        $sql = "SELECT DATASAIDA DATAMOV FROM cimic_exclusoes WHERE ID = :idmovimentacao";
                        break;
            
                    default:
                        exit();
                        break;
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idmovimentacao',$idmovimentacao,pdo::PARAM_INT);
                $stmt->execute();        
                $resultado = $stmt->fetchAll();
   
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum informação foi encontrada. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca dados da contagem informada
            elseif($tipo==17 && $idcontagem>0){
                
                $sql = "SELECT CC.ID IDCONTAGEM, CC.IDTIPO, CC.IDUSUARIO, US.NOME NOMEUSUARIO, CC.AUTENTICADO, CC.IDRAIO, RC.NOMECOMPLETO NOMERAIO, CC.QTD, CCT.NOME NOMECONTAGEM
                FROM chefia_contagens CC
                INNER JOIN tab_raioscelas RC ON RC.ID = CC.IDRAIO
                INNER JOIN chefia_contagenstipos CCT ON CCT.ID = CC.IDTIPO
                LEFT JOIN tab_usuarios US ON US.ID = CC.IDUSUARIO
                WHERE CC.ID = ? AND CC.IDEXCLUSOREGISTRO IS NULL;";

                $params=[$idcontagem];

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);  
                $resultado = $stmt->fetchAll();
   
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma informação foi encontrada para o IDCONTAGEM informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            

        } catch (PDOException $e) {
            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Ocorreu um erro. Erro: ". $e->getMessage()." </li>");
            echo json_encode($retorno);
            exit();
        }
    }else{
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> $conexaoStatus </li>");
        echo json_encode($retorno);
        exit();
    }

echo json_encode($retorno);
exit();

echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." </li>"));exit();
