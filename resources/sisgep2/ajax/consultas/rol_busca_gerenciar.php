<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    $tipo = $_POST['tipo'];
    $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    $visitanteantigo = isset($_POST['visitanteantigo'])?$_POST['visitanteantigo']:0;
    $idvisita = isset($_POST['idvisita'])?$_POST['idvisita']:0;
    $idvisitante = isset($_POST['idvisitante'])?$_POST['idvisitante']:0;
    $cpf = isset($_POST['cpf'])?$_POST['cpf']:'';
    $idtipomov = isset($_POST['idtipomov'])?$_POST['idtipomov']:0;
    $responsavel = isset($_POST['responsavel'])?$_POST['responsavel']:0;
    $idsituacao = isset($_POST['idsituacao'])?$_POST['idsituacao']:0;
    $dataconsulta = isset($_POST['dataconsulta'])?$_POST['dataconsulta']:'';

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){ 
        try {
            //Busca tabela do Gerenciar Visitantes
            if($tipo==1){
                // $datainicio = $_POST['datainicio'];
                // $datafinal = $_POST['datafinal'];
                $ordem = $_POST['ordem'];
                $buscatexto = $_POST['buscatexto'];
                $texto = $_POST['texto'];
                $textobusca = $_POST['textobusca'];
                $situacao = $_POST['situacao'];

                if($situacao==0){
                    $situacao = "";
                }elseif($situacao==1){
                    $situacao = "AND RVP.IDSITUACAO = 23 AND RV.IDSITUACAO = 25";
                }elseif($situacao==2){
                    $situacao = "AND (RVP.IDSITUACAO <> 23 OR RV.IDSITUACAO <> 25)";
                }else{
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Situação não informada corretamente. </li>"));
                    exit();
                }

                if($ordem==1){
                    $ordem = 'EP.MATRICULA';
                }elseif($ordem==2){
                    $ordem = 'EP.NOME';
                }elseif($ordem==3){
                    $ordem = 'RV.NOME';
                }else{
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Ordenação não informada corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }
            
                if($texto!=1 && $texto!=2){
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Opção de busca do texto não informado corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }
            
                if($buscatexto==1){
                    $textoinicio = '%';
                    $textofinal = '%';
                }elseif($buscatexto==2){
                    $textoinicio = '';
                    $textofinal = '';
                }elseif($buscatexto==3){
                    $textoinicio = '';
                    $textofinal = '%';
                }elseif($buscatexto==4){
                    $textoinicio = '%';
                    $textofinal = '';
                }else{
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Método de busca do texto não informado corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }

                $whereentradasaida = '';
                if($idtipomov==1){
                    $whereentradasaida = " AND RVP.ID NOT IN (SELECT IDVISITA FROM rol_movimentacoes WHERE date_format(DATAENTRADA,'%Y-%m-%d') = CURRENT_DATE AND DATAEXCLUSOREGISTRO IS NULL)";
                }elseif($idtipomov==2){
                    $whereentradasaida = " AND RVP.ID IN (SELECT IDVISITA FROM rol_movimentacoes WHERE date_format(DATAENTRADA,'%Y-%m-%d') = CURRENT_DATE AND DATASAIDA IS NULL AND DATAEXCLUSOREGISTRO IS NULL)";
                }

                $params=[];
                $arraywheres = [];
                
                //Adiciona na ordem os parâmetros
                for($i=0;$i<1;$i++){
                    // array_push($params,"$datainicio 00:00:00");
                    // array_push($params,"$datafinal 23:59:59");
                    // array_push($params,"$datafinal 23:59:59"); //Para a data do EP.EXCLUSOREGISTRO
                    
                    $where='';
                    if(strlen($textobusca)>0){
                        if($texto==1){
                            $palavas = retornaArrayPalavras($textobusca);
                        }else{
                            $palavas = array($textobusca);
                        }
                        
                        $where = "AND (";
                        foreach($palavas as $tex){
                            if($where != "AND ("){
                                $where .= " OR ";                        
                            }
                            
                            if($i==0){
                                $where .= "(UCASE(CAD.NOME) LIKE UCASE(?) OR REPLACE(REPLACE(EP.MATRICULA,'.',''),'-','') LIKE UCASE(REPLACE(REPLACE(?,'.',''),'-','')) OR UCASE(RV.NOME) LIKE UCASE(?) OR UCASE(REPLACE(REPLACE(RV.CPF,'.',''),'-','')) LIKE UCASE(REPLACE(REPLACE(?,'.',''),'-','')) OR UCASE(REPLACE(REPLACE(RV.RG,'.',''),'-','')) LIKE UCASE(REPLACE(REPLACE(?,'.',''),'-','')) OR UCASE(RV.NOMESOCIAL) LIKE UCASE(?) OR UCASE(GP.NOME) LIKE UCASE(?) OR UCASE(SIT.NOME) LIKE UCASE(?) OR UCASE(RVP.COMENTARIO) LIKE UCASE(?) OR UCASE(SIT1.NOME) LIKE UCASE(?) OR UCASE(RV.COMENTARIO) LIKE UCASE(?))";
                                //Quantidade de substiuições que vão ser inseridas
                                $repeticao = 11;
                            }

                            for($iparams=0;$iparams<$repeticao;$iparams++){
                                array_push($params,$textoinicio.$tex.$textofinal);
                            }
                        }
                        $where .= ") ";
                    }
                    array_push($arraywheres,$where);
                }

                $sql = "SELECT RVP.ID, RVP.IDPRESO, EP.MATRICULA, CAD.NOME, CAD.IDPRESO IDPRESOATUAL, RVP.IDVISITANTE, RV.NOME NOMEVISITANTE, RV.CPF, RV.RG, RVP.IDPARENTESCO, GP.NOME PARENTESCO, RVP.DATACADASTRO, CASE WHEN RVP.IDSITUACAO = 23 AND RV.IDSITUACAO = 25 THEN (SELECT DATACADASTRO FROM rol_visitantes_presossituacao WHERE IDREFERENCIA = RVP.ID ORDER BY ID DESC LIMIT 1) ELSE NULL END DATAAPROVADO, RVP.IDSITUACAO, CASE WHEN RV.IDSITUACAO IN (25,31) THEN SIT.NOME ELSE  SIT1.NOME END SITUACAO, RVP.COMENTARIO, RV.IDSITUACAO IDSITUACAOVISI, SIT1.NOME SITUACAOVISI, RV.COMENTARIO COMENTARIOVISI, FUNCT_dados_raio_cela_preso(EP.ID, CURRENT_TIMESTAMP, 1) IDRAIO, FUNCT_dados_raio_cela_preso(EP.ID, CURRENT_TIMESTAMP, 2) RAIO, FUNCT_dados_raio_cela_preso(EP.ID, CURRENT_TIMESTAMP, 3) CELA, EP.SEGURO, CASE WHEN RM.ID IS NOT NULL THEN RM.ID ELSE 0 END IDMOV, NULL COR
                FROM rol_visitantes_presos RVP
                INNER JOIN rol_visitantes RV ON RV.ID = RVP.IDVISITANTE
                INNER JOIN entradas_presos EP ON EP.ID = RVP.IDPRESO
                INNER JOIN cadastros CAD ON CAD.MATRICULA = EP.MATRICULA
                INNER JOIN tab_grauparentesco GP ON GP.ID = RVP.IDPARENTESCO
                INNER JOIN tab_situacao SIT ON SIT.ID = RVP.IDSITUACAO
                INNER JOIN tab_situacao SIT1 ON SIT1.ID = RV.IDSITUACAO
                LEFT JOIN rol_movimentacoes RM ON RM.IDVISITA = RVP.ID
                WHERE RVP.IDEXCLUSOREGISTRO IS NULL AND RV.IDEXCLUSOREGISTRO IS NULL AND EP.IDEXCLUSOREGISTRO IS NULL AND CAD.IDPRESO = EP.ID $whereentradasaida " .$arraywheres[0]. " $situacao
                ORDER BY $ordem;";

                //echo json_encode($sql); exit();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                $retorno="";
                $contador = 0;

                // echo json_encode($resultado);
                // exit();            
                
                for($i=0;$i<count($resultado);$i++){
                    
                    $idpreso = $resultado[$i]['IDPRESO'];
                                       
                    $idsituacao = $resultado[$i]['IDSITUACAO'];
                    $idsituacaovisi = $resultado[$i]['IDSITUACAOVISI'];

                    $seguro = $resultado[$i]['SEGURO'];
                    $dadoscela = buscaDadosRaioCelaPreso($idpreso,date('Y-m-d H:i:s'),2);
                    if($dadoscela!=false){
                        $trabalho = $dadoscela['ESPECIAL'];
                    }else{
                        $trabalho = 0;
                    }

                    if($seguro==1){
                        $resultado[$i]['NOME'] .= " <span class='destaque-atencao'>(SEGURO)</span>";
                    }
                    if($trabalho==1){
                        $resultado[$i]['NOME'] .= " <span class='destaque-atencao'>(TRAB)</span>";
                    }

                    if(in_array($idsituacao,array(23)) && in_array($idsituacaovisi,array(25))){
                        $cor = "cor-aprovado";
                    }elseif(in_array($idsituacao,array(24)) || in_array($idsituacaovisi,array(26))){
                        $cor = "cor-bloqueado";
                    }elseif(in_array($idsituacaovisi,array(27))){
                        $cor = "cor-suspenso";
                    }elseif(in_array($idsituacao,array(28,29,30))){
                        $cor = "cor-excluido";
                    }else{
                        $cor = "cor-fundo-comum-tr";
                    }

                    $resultado[$i]['COR'] = $cor;
                }

                echo json_encode($resultado);
                exit();            
            }
            //Busca visitantes do preso
            elseif($tipo==2 && $idpreso>0){
                $params=[$idpreso];
                if($visitanteantigo==1){
                    $where = "IN (SELECT EP2.ID FROM entradas_presos EP2 WHERE EP2.MATRICULA = (SELECT EP1.MATRICULA FROM entradas_presos EP1 WHERE EP1.ID = ?)) AND RVP.IDVISITANTE NOT IN (SELECT IDVISITANTE FROM rol_visitantes_presos WHERE IDPRESO = ? AND IDEXCLUSOREGISTRO IS NULL)";
                    array_push($params,$idpreso);
                }else{
                    $where = "= ?";
                }

                $sql = "SELECT RVP.ID, RVP.IDVISITANTE, RV.NOME, RVP.IDPARENTESCO, GP.NOME PARENTESCO, RVP.IDSITUACAO, SIT.NOME SITUACAO, RVP.DATACADASTRO, RV.IDSITUACAO IDSITUACAOVISI, SIT1.NOME SITUACAOVISI, NULL COR
                FROM rol_visitantes_presos RVP
                INNER JOIN rol_visitantes RV ON RV.ID = RVP.IDVISITANTE
                INNER JOIN tab_situacao SIT1 ON SIT1.ID = RV.IDSITUACAO
                INNER JOIN tab_grauparentesco GP ON GP.ID = RVP.IDPARENTESCO
                INNER JOIN tab_situacao SIT ON SIT.ID = RVP.IDSITUACAO
                WHERE RVP.IDPRESO $where AND RVP.IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->fetchAll();

                for($i=0;$i<count($resultado);$i++){
                    $idsituacao = $resultado[$i]['IDSITUACAO'];
                    $idsituacaovisi = $resultado[$i]['IDSITUACAOVISI'];
                    
                    if(in_array($idsituacao,array(23)) && in_array($idsituacaovisi,array(25))){
                        $cor = "cor-aprovado";
                    }elseif(in_array($idsituacao,array(24)) || in_array($idsituacaovisi,array(26))){
                        $cor = "cor-bloqueado";
                    }elseif(in_array($idsituacaovisi,array(27))){
                        $cor = "cor-suspenso";
                    }elseif(in_array($idsituacao,array(28,29,30))){
                        $cor = "cor-excluido";
                    }else{
                        $cor = "cor-fundo-comum-tr";
                    }
                    
                    $resultado[$i]['COR']=$cor;
                }
                $retorno=$resultado;
            }
            //Busca dados da visita inserida (Dados da visita e parentesco) com opção de busca dos dependentes desta visita
            elseif($tipo==3 && $idvisita>0){
                $buscadependentes = isset($_POST['buscadependentes'])?$_POST['buscadependentes']:0;
                
                $params=[$idvisita];
                $union="";

                if($buscadependentes==1){
                    $union = "\rUNION \r"."SELECT RVP.*, GP.NOME PARENTESCO, SIT.NOME SITUACAO, (SELECT DATACADASTRO FROM rol_visitantes_presossituacao WHERE IDREFERENCIA = RVP.ID ORDER BY ID DESC LIMIT 1) DATASITUACAO FROM rol_visitantes_presos RVP
                    INNER JOIN tab_grauparentesco GP ON GP.ID = RVP.IDPARENTESCO
                    INNER JOIN tab_situacao SIT ON SIT.ID = RVP.IDSITUACAO
                    WHERE RVP.IDRESPONSAVEL = ?";
                    array_push($params,$idvisita);
                }

                $sql = "SELECT RVP.*, GP.NOME PARENTESCO, SIT.NOME SITUACAO, (SELECT DATACADASTRO FROM rol_visitantes_presossituacao WHERE IDREFERENCIA = RVP.ID ORDER BY ID DESC LIMIT 1) DATASITUACAO FROM rol_visitantes_presos RVP
                INNER JOIN tab_grauparentesco GP ON GP.ID = RVP.IDPARENTESCO
                INNER JOIN tab_situacao SIT ON SIT.ID = RVP.IDSITUACAO
                WHERE RVP.ID = ? $union;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID Visita informado. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca dados do visitante
            elseif($tipo==4 && $idvisitante>0){
                $params=[$idvisitante];

                $sql = "SELECT RV.*, CID.IDUF UFNASC, CID2.IDUF UFMORADIA, SIT.NOME SITUACAO, (SELECT DATACADASTRO FROM rol_visitantessituacao WHERE IDREFERENCIA = RV.ID ORDER BY ID DESC LIMIT 1) DATASITUACAO FROM rol_visitantes RV
                LEFT JOIN tab_cidades CID ON CID.ID = RV.IDCIDADENASC
                LEFT JOIN tab_cidades CID2 ON CID2.ID = RV.IDCIDADEMORADIA
                INNER JOIN tab_situacao SIT ON SIT.ID = RV.IDSITUACAO
                WHERE RV.ID = ?;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID Visitante informado. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca visitantes pelo CPF
            elseif($tipo==5 && $cpf!=''){
                $params=[$cpf];

                $sql = "SELECT * FROM rol_visitantes WHERE CPF = ?;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->fetchAll();

                $retorno=$resultado;
                // if(count($resultado)){
                //     $retorno=$resultado;
                // }else{
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID Visitante informado. </li>");
                //     echo json_encode($retorno);
                //     exit();            
                // }
            }
            //Busca visitantes ativos do preso informado e também faz verificação de id pelo idvisita informado
            elseif($tipo==6 && $idvisita>0){
                
                $params=[$idvisita];
                $where = "";
                // echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." $idvisitante</li>"));exit();

                if($responsavel==1){
                    $where = "AND RVP.IDVISITANTE <> ? AND (TIMESTAMPDIFF(YEAR,RV.DATANASC,CURRENT_DATE) >= 18 OR TIMESTAMPDIFF(YEAR,RV.DATANASC,CURRENT_DATE) >= 16 AND RV.EMANCIPADO = TRUE) AND RVP.IDRESPONSAVEL IS NULL AND RVP.IDSITUACAO = 23 AND RV.IDSITUACAO = 25";
                    array_push($params,$idvisitante);
                }

                // if($idvisita>0){
                //     $where .= " AND RVP.ID = ?";
                //     array_push($params,$idvisita);
                // }

                $sql = "SELECT RVP.ID VALOR, concat(CASE WHEN RV.NOMESOCIAL IS NOT NULL THEN RV.NOMESOCIAL ELSE RV.NOME END, ' - (CPF: ', RV.CPF, ')') NOMEEXIBIR FROM rol_visitantes_presos RVP
                INNER JOIN rol_visitantes RV ON RV.ID = RVP.IDVISITANTE
                WHERE RVP.IDPRESO = (SELECT IDPRESO FROM rol_visitantes_presos WHERE ID = ?) $where AND RVP.IDEXCLUSOREGISTRO IS NULL AND RV.IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->fetchAll();

                $retorno=$resultado;
                // if(count($resultado)){
                //     $retorno=$resultado;
                // }else{
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID Visitante informado. </li>");
                //     echo json_encode($retorno);
                //     exit();            
                // }
            }
            //Busca idmovimentação da visita na data informada
            elseif($tipo==7 && $idvisita>0 && $dataconsulta!=''){
                
                $params=[$idvisita,$dataconsulta];
                // echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." $idvisitante</li>"));exit();

                $sql = "SELECT * FROM rol_movimentacoes RM
                WHERE RM.IDVISITA = ? AND date_format(RM.DATAENTRADA, '%Y-%m-%d') = ? AND RM.IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $retorno = $stmt->fetchAll();
            }
            //Busca raios e celas dos dias de visitas
            elseif($tipo==8){
                
                $sql = "SELECT RRC.ID, RC.ID IDRAIO, RC.NOME NOMERAIO, RC.NOMECOMPLETO, RRC.CELA, RRC.IDTURNO, RRC.PERMITIDO
                FROM rol_raioscelas_visita RRC
                INNER JOIN tab_raioscelas RC ON RC.ID = RRC.IDRAIO
                ORDER BY RC.ID, RRC.CELA;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                $retorno = $stmt->fetchAll();

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
