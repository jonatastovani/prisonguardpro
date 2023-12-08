<?php

    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    // include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes.php";

    $retorno=[];

    $tipo = $_POST['tipo'];
    $confirmacao = isset($_POST['confirmacao'])?$_POST['confirmacao']:0;
    $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    $idmotivo = isset($_POST['idmotivo'])?$_POST['idmotivo']:0;
    $idtipo = isset($_POST['idtipo'])?$_POST['idtipo']:0;
    $idtipoatend = isset($_POST['idtipoatend'])?$_POST['idtipoatend']:0;
    $presos = isset($_POST['presos'])?$_POST['presos']:0;
    $requisitante = isset($_POST['requisitante'])?$_POST['requisitante']:'';
    $dataatend = isset($_POST['dataatend'])?$_POST['dataatend']:'';
    $idmedic = isset($_POST['idmedic'])?$_POST['idmedic']:0;
    $nome = isset($_POST['nome'])?$_POST['nome']:'';
    $idunidadefornec = isset($_POST['idunidadefornec'])?$_POST['idunidadefornec']:0;
    $qtdpadrao = isset($_POST['qtdpadrao'])?$_POST['qtdpadrao']:0;
    $qtdestoque = isset($_POST['qtdestoque'])?$_POST['qtdestoque']:0;
    $minestoque = isset($_POST['minestoque'])?$_POST['minestoque']:0;
    $idatend = isset($_POST['idatend'])?$_POST['idatend']:0;
    $descatend = isset($_POST['descatend'])?$_POST['descatend']:'';
    $observacoes = isset($_POST['observacoes'])?$_POST['observacoes']:'';
    $arrmedicamentos = isset($_POST['arrmedicamentos'])?$_POST['arrmedicamentos']:0;
    $idperiodo = isset($_POST['idperiodo'])?$_POST['idperiodo']:0;
    $idass = isset($_POST['idass'])?$_POST['idass']:0;
    $arrentregar = isset($_POST['arrentregar'])?$_POST['arrentregar']:0;

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    $dataAgora = date('Y-m-d H:i:s');

    $conexaoStatus = conectarBD();    
    if($conexaoStatus===true){
        try {
            //Inserir Atendimentos Enfermaria
            if($tipo==1){

                if($idmovimentacao==0){
                    //Verifica se o usuário tem a permissão necessária
                    /*$permissoesNecessarias = array(9,37);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para inserir Apresentações de Presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }*/

                    $sql = "INSERT INTO enf_atendimentos_requis (IDTIPOATEND, REQUISITANTE, DATAATEND, IDCADASTRO, IPCADASTRO, DATACADASTRO) VALUES (?, ?, ?, ?, ?, ?);";
                    
                    $params = [$idtipoatend,$requisitante,$dataatend,$idusuario,$ipcomputador,$dataAgora];

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute($params);
                    $resultado = $stmt->rowCount();

                    if($resultado>0){
                        $sql = "SELECT ID FROM enf_atendimentos_requis WHERE IDTIPOATEND = ? AND REQUISITANTE = ? AND DATACADASTRO = ?;";

                        $params = [$idtipoatend,$requisitante,$dataAgora];
                    
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->execute($params);
                        $resultado = $stmt->fetchAll();

                        $idmovimentacao = $resultado[0]['ID'];
                    }else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Erro ao inserir Requisitante. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }else{
                    $sql = "UPDATE enf_atendimentos_requis SET IDTIPOATEND = ?, REQUISITANTE = ?, DATAATEND = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                    
                    $params = [$idtipoatend,$requisitante,$dataatend,$idusuario,$ipcomputador,$idmovimentacao];

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute($params);
                    $resultado = $stmt->fetchAll();
                }

                //Primeiro busca todos os presos que estão inclusos no atendimento
                $sql = "SELECT ID FROM enf_atendimentos WHERE IDREQ = ? AND IDEXCLUSOREGISTRO IS NULL;"; 
                
                $params = [$idmovimentacao];
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $listaPresosExcluir = [];
                while($presosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                    array_push($listaPresosExcluir,$presosexistente['ID']);
                }
                
                if($presos!=0){
                    foreach($presos as $preso){
                        $idpreso = $preso['idpreso'];
                        $idbanco = $preso['idbanco'];
                        $horario = $preso['horario'];
                        $idsituacao = $preso['idsituacao'];

                        if($idbanco==0){
                            $sql = "INSERT INTO enf_atendimentos (IDREQ, IDTIPOATEND, IDPRESO, HORAATEND, IDSITUACAO, IDCADASTRO, IPCADASTRO) VALUES (?,?,?,?,?,?,?);";

                            $params = [$idmovimentacao,$idtipoatend,$idpreso,$horario,$idsituacao,$idusuario,$ipcomputador];

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);
                            $resultado = $stmt->rowCount();

                        }else{
                            $sql = "UPDATE enf_atendimentos SET IDREQ = ?, IDTIPOATEND = ?, HORAATEND = ?, IDSITUACAO = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                            
                            $params = [$idmovimentacao,$idtipoatend,$horario,$idsituacao,$idusuario,$ipcomputador,$idbanco];

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);
                            $resultado = $stmt->rowCount();

                            $posicao = array_search($idbanco,$listaPresosExcluir);        
                            if($posicao!==false){
                                unset($listaPresosExcluir[$posicao]);
                            }
                        }
                    }
                }
            
                //Exclui os artigos que foram exclusos da entrada do preso.
                if(count($listaPresosExcluir)){
                    foreach($listaPresosExcluir as $idexcluir){
                        $sql = "UPDATE enf_atendimentos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                        $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                        $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Atendimento enviado com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            // Inserir/Alterar medicamentos
            elseif($tipo==2){
                      
                //Verifica se o usuário tem a permissão necessária
                // $permissoesNecessarias = array($resultado[0]['IDPERMISSAO']);
                // $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                // $nometurno = $resultado[0]['NOME'];

                // if($blnPermitido==false){
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. É necessário ao menos a permissão de Penal do $nometurno para gerenciar a chefia. </li>");
                //     echo json_encode($retorno);
                //     exit();
                // }
                
                if($idmedic==0){
                    $params=[$nome,$idunidadefornec,$qtdpadrao,$qtdestoque,$minestoque,$idusuario,$ipcomputador];
                    
                    $sql = "INSERT INTO enf_medicamentos (NOME,IDUNIDADE,QTD,QTDESTOQUE,MINIMOESTOQUE,IDCADASTRO,IPCADASTRO) VALUES(?,?,?,?,?,?,?);";

                }else{
                    $params=[$nome,$idunidadefornec,$qtdpadrao,$qtdestoque,$minestoque,$idusuario,$ipcomputador,$idmedic];
                    
                    $sql = "UPDATE enf_medicamentos SET NOME = ?, IDUNIDADE = ?, QTD = ?, QTDESTOQUE = ?, MINIMOESTOQUE = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";

                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();
                
                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            // Excluir medicamentos
            elseif($tipo==3 && $idmedic>0){
                        
                //Verifica se o usuário tem a permissão necessária
                // $permissoesNecessarias = array($resultado[0]['IDPERMISSAO']);
                // $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                // $nometurno = $resultado[0]['NOME'];

                // if($blnPermitido==false){
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. É necessário ao menos a permissão de Penal do $nometurno para gerenciar a chefia. </li>");
                //     echo json_encode($retorno);
                //     exit();
                // }

                $params=[$idusuario,$ipcomputador,$idmedic];
                
                $sql = "UPDATE enf_medicamentos SET IDEXCLUSOREGISTRO = ?, IPEXCLUSOREGISTRO = ? WHERE ID = ?;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();
                
                $retorno = array('OK' => "<li class = 'mensagem-exito'> Medicamento excluído com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            // Alterar Atendimento
            elseif($tipo==4 && $idatend>0){
                      
                //Verifica se o usuário tem a permissão necessária
                // $permissoesNecessarias = array($resultado[0]['IDPERMISSAO']);
                // $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                // $nometurno = $resultado[0]['NOME'];

                // if($blnPermitido==false){
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. É necessário ao menos a permissão de Penal do $nometurno para gerenciar a chefia. </li>");
                //     echo json_encode($retorno);
                //     exit();
                // }
                
                $params=[$descatend,$observacoes,$idusuario,$ipcomputador,$idatend];
                
                $sql = "UPDATE enf_atendimentos SET DESCATEND = ?, OBSERVACOES = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                if($arrmedicamentos!=0){
                    //Primeiro busca todos os medicamentos que já estão salvos para este atendimento
                    $sql = "SELECT ID FROM enf_atendimentos_medic WHERE IDATEND = :idatend AND IDEXCLUSOREGISTRO IS NULL;"; 
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idatend', $idatend, PDO::PARAM_INT);
                    $stmt->execute();
                    $listaMedicamentosExcluir = [];
                    while($medicExistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($listaMedicamentosExcluir,$medicExistente['ID']);
                    }
                    
                    foreach($arrmedicamentos as $medic){
                        $idbanco = $medic['idbanco'];
                        $idmedic = $medic['idmedic'];
                        $qtdentregue = $medic['qtdentregue'];
                        $recommedic = $medic['recommedic'];

                        if($idbanco==0){
                            $params=[$idatend,$idmedic,$qtdentregue,$recommedic,$idusuario,$ipcomputador];
                            $sql = "INSERT INTO enf_atendimentos_medic (IDATEND,IDMEDICAMENTO,QTD,OBSERVACOES,IDCADASTRO,IPCADASTRO) VALUES (?,?,?,?,?,?);";
                            
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);

                        }
                        else{
                            $params=[$qtdentregue,$recommedic,$idusuario,$ipcomputador,$idbanco];
                            $sql = "UPDATE enf_atendimentos_medic SET QTD = ?,  OBSERVACOES = ?,IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);

                            //Exclui da listagem de artigos existentes pois este medic não foi excluído.
                            $posicao = array_search($idbanco,$listaMedicamentosExcluir);        
                            if($posicao!==false){
                                unset($listaMedicamentosExcluir[$posicao]);
                            }
                        }
                    }

                    //Exclui os artigos que foram exclusos da entrada do preso.
                    if(count($listaMedicamentosExcluir)){
                        foreach($listaMedicamentosExcluir as $idexcluir){
                            $sql = "UPDATE enf_atendimentos_medic SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";
    
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }
                
                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            // Entregar medicamento assistido
            elseif($tipo==5 && $arrentregar!=0){
                        
                //Verifica se o usuário tem a permissão necessária
                // $permissoesNecessarias = array($resultado[0]['IDPERMISSAO']);
                // $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                // $nometurno = $resultado[0]['NOME'];

                // if($blnPermitido==false){
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. É necessário ao menos a permissão de Penal do $nometurno para gerenciar a chefia. </li>");
                //     echo json_encode($retorno);
                //     exit();
                // }
                
                
                $wherepreso = "";
                if($idtipo==1){
                    $params=[$idusuario,$ipcomputador];

                    foreach($arrentregar as $info){
                        if($wherepreso==''){
                            $wherepreso = "?";
                        }else{
                            $wherepreso .= ",?";
                        }
                        array_push($params,$info['idass']);
                    }

                    $sql = "INSERT INTO enf_medic_assistido_entregue (IDASS, QTDENTREGUE, IDCADASTRO, IPCADASTRO) SELECT ID, QTDENTREGA, ?, ? FROM enf_medic_assistido WHERE ID IN ($wherepreso)
                        AND ID NOT IN (SELECT DISTINCT EMAE2.IDASS FROM enf_medic_assistido_entregue EMAE2
                            WHERE date_format(EMAE2.DATAENTREGUE,'%Y-%m-%d') = CURRENT_DATE AND EMAE2.IDEXCLUSOREGISTRO IS NULL)
                    AND (DATATERMINO IS NULL OR date_format(DATATERMINO,'%Y-%m-%d') >= CURRENT_DATE) AND IDEXCLUSOREGISTRO IS NULL;";
                }else{
                    $params=[$idusuario,$ipcomputador];

                    foreach($arrentregar as $info){
                        if($wherepreso==''){
                            $wherepreso = "(IDPRESO = ? AND IDPERIODOENTREGA = ?)";
                        }else{
                            $wherepreso .= " OR (IDPRESO = ? AND IDPERIODOENTREGA = ?)";
                        }
                        array_push($params,$info['idpreso']);
                        array_push($params,$info['idperiodo']);
                    }

                    $sql = "INSERT INTO enf_medic_assistido_entregue (IDASS, QTDENTREGUE, IDCADASTRO, IPCADASTRO) SELECT ID, QTDENTREGA, ?, ? FROM enf_medic_assistido WHERE ($wherepreso)
                        AND ID NOT IN (SELECT DISTINCT EMAE2.IDASS FROM enf_medic_assistido_entregue EMAE2
                            WHERE date_format(EMAE2.DATAENTREGUE,'%Y-%m-%d') = CURRENT_DATE AND EMAE2.IDEXCLUSOREGISTRO IS NULL)
                    AND (DATATERMINO IS NULL OR date_format(DATATERMINO,'%Y-%m-%d') >= CURRENT_DATE) AND IDEXCLUSOREGISTRO IS NULL;";
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();
                
                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            // Inserir/Alterar Medicamentos Assistidos
            elseif($tipo==6 && $idpreso>0){
                      
                //Verifica se o usuário tem a permissão necessária
                // $permissoesNecessarias = array($resultado[0]['IDPERMISSAO']);
                // $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                // $nometurno = $resultado[0]['NOME'];

                // if($blnPermitido==false){
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. É necessário ao menos a permissão de Penal do $nometurno para gerenciar a chefia. </li>");
                //     echo json_encode($retorno);
                //     exit();
                // }
                $arrperiodos = $_POST['arrperiodos'];
                $params = [$idpreso];
                //Primeiro busca todos os assistidos que já estão salvos para este preso
                $sql = "SELECT ID FROM enf_medic_assistido WHERE IDPRESO = ? AND IDEXCLUSOREGISTRO IS NULL;"; 
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->fetchAll();

                $listaMedAssExcluir = [];
                for($i=0;$i<count($resultado);$i++){
                    array_push($listaMedAssExcluir,$resultado[$i]['ID']);
                }

                foreach($arrperiodos as $per){
                    $idperiodo = $per['idperiodo'];
                    $medicass = isset($per['medicass'])?$per['medicass']:0;

                    if($medicass!=0){
                        foreach($medicass as $medic){
                            $idbanco = $medic['idbanco'];
                            $idmedic = $medic['idmedic'];
                            $qtd = $medic['qtd'];
                            $datainicio = $medic['datainicio'];
                            $datatermino = $medic['datatermino'];
                            $excluido = $medic['excluido'];

                            if($idbanco==0){
                                $params = [$idpreso,$idmedic,$qtd,$idperiodo,$datainicio,$datatermino,$idusuario,$ipcomputador];
                                $sql = "INSERT INTO enf_medic_assistido (IDPRESO,IDMEDICAMENTO,QTDENTREGA,IDPERIODOENTREGA,DATAINICIO,DATATERMINO,IDCADASTRO,IPCADASTRO) VALUES (?,?,?,?,?,?,?,?);";

                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->execute($params);
                                $resultado = $stmt->rowCount(); 
                                
                                if($resultado==0){
                                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Ocorreu um erro ao inserir Medicamento Assistido. Tente novamente mais tarde, se o problema persistir contate o programador. </li>");
                                    echo json_encode($retorno);
                                    exit();
                                }
                            }
                            else{
                                if($excluido==0){
                                    $params = [$qtd,$datainicio,$datatermino,$idusuario,$ipcomputador,$idbanco];
                                    $sql = "UPDATE enf_medic_assistido SET QTDENTREGA = ?, DATAINICIO = ?, DATATERMINO = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?";

                                    $stmt = $GLOBALS['conexao']->prepare($sql);
                                    $stmt->execute($params);
                                    $resultado = $stmt->rowCount(); 
                                }else{
                                    $params = [$idusuario,$ipcomputador,$idbanco];
                                    $sql = "UPDATE enf_medic_assistido SET IDEXCLUSOREGISTRO = ?, IPEXCLUSOREGISTRO = ? WHERE ID = ?";

                                    $stmt = $GLOBALS['conexao']->prepare($sql);
                                    $stmt->execute($params);
                                    $resultado = $stmt->rowCount(); 
                                }

                                //Exclui da listagem de assistidos existentes
                                $posicao = array_search($idbanco,$listaMedAssExcluir);        
                                if($posicao!==false){
                                    unset($listaMedAssExcluir[$posicao]);
                                }
                            }
                        }
                    }
                }

                //Exclui os assistidos que não estão na listagem de assistidos nesta edição.
                if(count($listaMedAssExcluir)){
                    foreach($listaMedAssExcluir as $idexcluir){
                        $params = [$idusuario,$ipcomputador,$idexcluir];
                        $sql = "UPDATE enf_medic_assistido SET IDEXCLUSOREGISTRO = ?, IPEXCLUSOREGISTRO = ? WHERE ID = ?";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->execute($params);
                        $resultado = $stmt->rowCount(); 
                    }
                }

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

    //echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ". __LINE__." </li>"));exit();

