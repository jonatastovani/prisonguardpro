<?php

    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    // include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes.php";

    $retorno=[];

    $tipo = $_POST['tipo'];
    $confirmacao = isset($_POST['confirmacao'])?$_POST['confirmacao']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    $arrvisitantes = isset($_POST['arrvisitantes'])?$_POST['arrvisitantes']:0;
    $idvisita = isset($_POST['idvisita'])?$_POST['idvisita']:0;
    $idvisitante = isset($_POST['idvisitante'])?$_POST['idvisitante']:0;
    $idvisitanteatual = isset($_POST['idvisitanteatual'])?$_POST['idvisitanteatual']:0;
    $idvisitantealterar = isset($_POST['idvisitantealterar'])?$_POST['idvisitantealterar']:0;
    $cpf = isset($_POST['cpf'])?$_POST['cpf']:'';
    $nome = isset($_POST['nome'])?$_POST['nome']:'';
    $nomesocial = isset($_POST['nomesocial'])?$_POST['nomesocial']:'';
    $rg = isset($_POST['rg'])?$_POST['rg']:'';
    $idemissorrg = isset($_POST['idemissorrg'])?$_POST['idemissorrg']:0;
    $idestadorg = isset($_POST['idestadorg'])?$_POST['idestadorg']:0;
    $pai = isset($_POST['pai'])?$_POST['pai']:'';
    $mae = isset($_POST['mae'])?$_POST['mae']:'';
    $observacoes = isset($_POST['observacoes'])?$_POST['observacoes']:'';
    $idufnasc = isset($_POST['idufnasc'])?$_POST['idufnasc']:0;
    $idnacionalidade = isset($_POST['idnacionalidade'])?$_POST['idnacionalidade']:0;
    $idcidadenasc = isset($_POST['idcidadenasc'])?$_POST['idcidadenasc']:0;
    $datanasc = isset($_POST['datanasc'])?$_POST['datanasc']:'';
    $emancipado = isset($_POST['emancipado'])?$_POST['emancipado']:0;
    $idresponsavel = isset($_POST['idresponsavel'])?$_POST['idresponsavel']:0;
    $idparentresp = isset($_POST['idparentresp'])?$_POST['idparentresp']:0;
    $logradouro = isset($_POST['logradouro'])?$_POST['logradouro']:'';
    $numero = isset($_POST['numero'])?$_POST['numero']:'';
    $complemento = isset($_POST['complemento'])?$_POST['complemento']:'';
    $bairro = isset($_POST['bairro'])?$_POST['bairro']:'';
    $idufmorad = isset($_POST['idufmorad'])?$_POST['idufmorad']:0;
    $idcidademorad = isset($_POST['idcidademorad'])?$_POST['idcidademorad']:0;
    $idparentesco = isset($_POST['idparentesco'])?$_POST['idparentesco']:0;
    $idsitvisitante = isset($_POST['idsitvisitante'])?$_POST['idsitvisitante']:0;
    $comvisitante = isset($_POST['comvisitante'])?$_POST['comvisitante']:'';
    $idsitvisita = isset($_POST['idsitvisita'])?$_POST['idsitvisita']:0;
    $comvisita = isset($_POST['comvisita'])?$_POST['comvisita']:'';
    $idtipomov = isset($_POST['idtipomov'])?$_POST['idtipomov']:0;
    $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;
    $arrcelas = isset($_POST['arrcelas'])?$_POST['arrcelas']:0;

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    $dataAgora = date('Y-m-d H:i:s');

    $conexaoStatus = conectarBD();    
    if($conexaoStatus===true){
        try {
            //Inserir visitantes na inclusão do preso
            if($tipo==1 && $idpreso>0){
                $params=[];

                if($arrvisitantes!=0){
                    foreach($arrvisitantes as $visita){
                        $idbanco = $visita['idbanco'];
                        $idvisitante = $visita['idvisitante'];
                        $nomevisitante = $visita['nomevisitante'];
                        $idparentesco = $visita['idparentesco'];

                        if($idbanco==0){
                            if($idvisitante==0){

                                $sql = "INSERT INTO rol_visitantes (NOME,IDCADASTRO,IPCADASTRO,DATACADASTRO) VALUES (?,?,?,?)";
                                $params=[$nomevisitante,$idusuario,$ipcomputador,$dataAgora];

                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->execute($params);
                                // $resultado = $stmt->rowCount();

                                $sql = "SELECT * FROM rol_visitantes WHERE NOME = ? AND IPCADASTRO = ? AND DATACADASTRO = ?;";

                                $params=[$nomevisitante,$ipcomputador,$dataAgora];

                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->execute($params);
                                $resultado = $stmt->fetchAll();

                                $idvisitante = $resultado[0]['ID'];
                            }
                            
                            $sql = "INSERT INTO rol_visitantes_presos (IDPRESO,IDVISITANTE,IDPARENTESCO,IDCADASTRO,IPCADASTRO) VALUES (?,?,?,?,?)";
                            $params=[$idpreso,$idvisitante,$idparentesco,$idusuario,$ipcomputador];

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);
                            // $resultado = $stmt->rowCount();
                        }
                    }
                }

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            //Alterar ID visitante para ID visitante existente
            elseif($tipo==2 && $idvisitanteatual>0 && $idvisitantealterar>0 && $idvisita>0){
                $sql = "UPDATE rol_visitantes SET IDEXCLUSOREGISTRO =?, IPEXCLUSOREGISTRO = ? WHERE ID = ?;";
                $params=[$idusuario,$ipcomputador,$idvisitanteatual];

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();

                if($resultado){
                    $sql = "UPDATE rol_visitantes_presos SET IDVISITANTE = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                    $params=[$idvisitantealterar,$idusuario,$ipcomputador,$idvisita];
    
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute($params);
                    $resultado = $stmt->rowCount();
    
                    if($resultado){
                        $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados alterados com sucesso!! </li>");
                        echo json_encode($retorno);
                        exit();
                    }else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> ID Visitante oficial não substituido pelo ID oficial. Por favor, contate o programador. </li>");
                        echo json_encode($retorno);
                        exit();    
                    }  
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> ID Visitante mais recente não foi excluído, não será possível substituir pelo ID oficial. </li>");
                    echo json_encode($retorno);
                    exit();    
                }
            }
            //Inserir CPF para visitante novo
            elseif($tipo==3 && $idvisitante>0 && $cpf!=''){
                $sql = "UPDATE rol_visitantes SET CPF = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                $params=[$cpf,$idusuario,$ipcomputador,$idvisitante];

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();

                if($resultado){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                    echo json_encode($retorno);
                    exit();    
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível inserir o CPF para o visitante informado.\nSe o problema persistir, comunique o programador. </li>");
                    echo json_encode($retorno);
                    exit();    
                }
            }
            //Alterando cadastro de visitante e visita
            elseif($tipo==4 && $idvisitante>0 && $idvisita>0){
                $sql = "UPDATE rol_visitantes SET NOME = ?, NOMESOCIAL = ?, RG = ?, IDEMISSORRG = ?, IDESTADORG = ?, PAI = ?, MAE = ?, OBSERVACOES = ?, IDNACIONALIDADE = ?, IDCIDADENASC = ?, DATANASC = ?, EMANCIPADO = ?, ENDERECO = ?, NUMERO = ?, BAIRRO = ?, COMPLEMENTO = ?, IDCIDADEMORADIA = ?, IDSITUACAO = ?, COMENTARIO = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                $params=[$nome,$nomesocial,$rg,$idemissorrg,$idestadorg,$pai,$mae,$observacoes,$idnacionalidade,$idcidadenasc,$datanasc,$emancipado,$logradouro,$numero,$bairro,$complemento,$idcidademorad,$idsitvisitante,$comvisitante,$idusuario,$ipcomputador,$idvisitante];

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                // $resultado = $stmt->rowCount();

                $sql = "UPDATE rol_visitantes_presos SET IDSITUACAO = ?, COMENTARIO = ?, IDPARENTESCO = ?, IDRESPONSAVEL = ?, IDPARENTRESP = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                $params=[$idsitvisita,$comvisita,$idparentesco,$idresponsavel,$idparentresp,$idusuario,$ipcomputador,$idvisita];

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                // $resultado = $stmt->rowCount();

                // if($resultado){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                    echo json_encode($retorno);
                    exit();    
                // }else{
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível inserir o CPF para o visitante informado.\nSe o problema persistir, comunique o programador. </li>");
                //     echo json_encode($retorno);
                //     exit();    
                // }
            }
             //Inserindo entrada ou saída de visitantes
            elseif($tipo==5 && ($idvisita>0 && $idtipomov==1 || $idmovimentacao>0 && $idtipomov==2)){

                if($idtipomov==1){

                    //Confere se a visita existe
                    $sql = "SELECT * FROM rol_visitantes_presos WHERE ID = ? AND IDEXCLUSOREGISTRO IS NULL;";
                    $params=[$idvisita];
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute($params);
                    $resultado = $stmt->fetchAll();

                    if(count($resultado)){
                        //Verifica se o preso e a cela que ele está pode receber visita
                        $idpreso = $resultado[0]['IDPRESO'];

                        $sql = retornaQueryDadosBoletimVigente();
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->execute();

                        $sql = "SELECT @intIDTurno IDTURNO, @dataBoletimCurta DATABOLETIM";
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->execute();
                        $resultado = $stmt->fetchAll();

                        $idturno = $resultado[0]['IDTURNO'];
                        $databoletim = $resultado[0]['DATABOLETIM'];

                        $sql = "SELECT RRC.IDRAIO, RRC.CELA, RRC.PERMITIDO, RRC.IDTURNO, EP.*
                        FROM entradas_presos EP
                        INNER JOIN cadastros_mudancacela CMUD ON CMUD.IDPRESO = EP.ID
                        INNER JOIN rol_raioscelas_visita RRC
                        WHERE EP.ID = ? AND CMUD.RAIOALTERADO IS NULL AND RRC.IDRAIO = CMUD.RAIO AND RRC.CELA = CMUD.CELA ORDER BY CMUD.ID DESC, RRC.ID DESC LIMIT 1;
                        ";
                        $params=[$idpreso];
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->execute($params);
                        $resultado = $stmt->fetchAll();

                        if(count($resultado)){
                            //Verifica se está permitido a visitação para o raio e cela do preso
                            if($resultado[0]['IDTURNO']!=null && $resultado[0]['IDTURNO']!=$idturno || $resultado[0]['PERMITIDO']==0){
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> A visitação não está permitida para o raio ou cela do preso. Verifique as configurações dos dias de visitas! </li>");
                                echo json_encode($retorno);
                                exit();
                            }

                            //Verifica-se se já foi incluso esta visita
                            $sql = "SELECT * FROM rol_movimentacoes WHERE IDVISITA = ? AND date_format(DATAENTRADA,'%Y-%m-%d') = CURRENT_DATE AND IDEXCLUSOREGISTRO IS NULL;";

                            $params=[$idvisita];

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);
                            $resultado = $stmt->fetchAll();
                            
                            if(count($resultado)){
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Este visitante já realizou entrada as " . retornaDadosDataHora($resultado[0]['DATAENTRADA'],12) . "!! </li>");
                                echo json_encode($retorno);
                                exit();
                            }
                            // echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ". __LINE__." ". count($resultado) ."</li>"));exit();
                            

                            //Verifica-se se o visitante tem um responsável. Se tiver, exige-se que o responsável seja inserido primeiro
                            $sql = "SELECT * FROM rol_visitantes_presos WHERE ID = ? AND IDRESPONSAVEL IS NOT NULL AND IDEXCLUSOREGISTRO IS NULL;";

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);
                            $resultado = $stmt->fetchAll();

                            if(count($resultado)){
                                $params=[$resultado[0]['IDRESPONSAVEL']];

                                $sql = "SELECT * FROM rol_movimentacoes WHERE IDVISITA = ? AND date_format(DATAENTRADA,'%Y-%m-%d') = CURRENT_DATE AND IDEXCLUSOREGISTRO IS NULL;";

                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->execute($params);
                                $resultado = $stmt->fetchAll();

                                if(count($resultado)==0){
                                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> O Responsável por este visitante precisa realizar a entrada primeiramente! </li>");
                                    echo json_encode($retorno);
                                    exit();
                                }
                            }

                            $sql = "INSERT INTO rol_movimentacoes (IDVISITA,IDCADASTRO,IPCADASTRO) VALUES(?,?,?);";

                            $params=[$idvisita,$idusuario,$ipcomputador];
                        }else{
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível conferir informações de raio e cela do preso para verificação de visitação. </li>");
                            echo json_encode($retorno);
                            exit();
                        }

                    }else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> O visitante informado não existe ou foi excluído. </li>");
                        echo json_encode($retorno);
                        exit();
                    }

                }elseif($idtipomov==2){

                    //Verifica-se se o visitante tem menores em sua responsabilidade. Se tiver, exige-se que os menores saiam primeiro
                    $sql = "SELECT * FROM rol_movimentacoes RM
                    INNER JOIN rol_visitantes_presos RVP ON RVP.ID = RM.IDVISITA
                    WHERE RVP.IDRESPONSAVEL = ? AND date_format(RM.DATAENTRADA,'%Y-%m-%d') = CURRENT_DATE AND RM.DATASAIDA IS NULL AND RM.IDEXCLUSOREGISTRO IS NULL;";
    
                    $params=[$idvisita];

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute($params);
                    $resultado = $stmt->fetchAll();

                    if(count($resultado)){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> O(s) menor(es) na responsabilidade deste visitante precisam sair primeiro! </li>");
                        echo json_encode($retorno);
                        exit();
                    }

                    $sql = "UPDATE rol_movimentacoes SET DATASAIDA = CURRENT_TIMESTAMP, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";

                    $params=[$idusuario,$ipcomputador,$idmovimentacao];
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                // $resultado = $stmt->rowCount();

                // if($resultado){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                    echo json_encode($retorno);
                    exit();    
                // }else{
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível inserir o CPF para o visitante informado.\nSe o problema persistir, comunique o programador. </li>");
                //     echo json_encode($retorno);
                //     exit();    
                // }
            }
            //Inserir CPF para visitante novo
            elseif($tipo==6 && $arrcelas!=0){

                $params=[];
                $sql="";

                foreach($arrcelas as $cela){
                    $idbanco = $cela['idbanco'];
                    $permitido = $cela['permitido'];
                    $idturno = $cela['idturno'];

                    $sql .= "UPDATE rol_raioscelas_visita SET PERMITIDO = ?, IDTURNO = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                    array_push($params,$permitido);
                    array_push($params,$idturno);
                    array_push($params,$idusuario);
                    array_push($params,$ipcomputador);
                    array_push($params,$idbanco);
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }

        } catch (PDOException $e) {
            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Ocorreu um erro. Erro: ". $e->getMessage()." </li>");
            echo json_encode($retorno);
            exit();
        }

    }else{
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> $conexaoStatus </li>");
        echo json_encode($retorno);
    }
    
    //unset($GLOBALS['conexao']);

    exit();

    //echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ". __LINE__." </li>"));exit();

