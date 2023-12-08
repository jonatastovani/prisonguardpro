<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once '../../funcoes/funcoes.php';
    
    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(3,24,25,26,27);
    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

    if($blnPermitido==false){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. </li>");
        echo json_encode($retorno);
        exit();
    }

    //Cria a variável de retorno para salvar os a mensagem a ser exibida na tela
    $retorno = [];
    $tipo = $_POST['tipo'];
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    $kitentregue = isset($_POST['kitentregue'])?$_POST['kitentregue']:0;
    $idkit = isset($_POST['idkit'])?$_POST['idkit']:0;
    $iditem = isset($_POST['iditem'])?$_POST['iditem']:0;
    $nome = isset($_POST['nome'])?$_POST['nome']:'';
    $padrao = isset($_POST['padrao'])?$_POST['padrao']:0;
    $qtd = isset($_POST['qtd'])?$_POST['qtd']:0;
    $itemnovo = isset($_POST['itemnovo'])?$_POST['itemnovo']:0;
 
    if($padrao==='true'){
        $padrao=1;
    }else{
        $padrao=0;
        $qtd=0;
    }
    
    if($itemnovo==='true'){
        $itemnovo=1;
    }else{
        $itemnovo=0;
    }
    
    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    $dataagora = date('Y-m-d H:i:s');

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            //Insere o kit padrão de entrega
            if($tipo==1){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(3,24);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para entregar Kit de Ítens. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $sql = "INSERT INTO inc_kitentregue (IDPRESO, IDTIPOENTREGA, IDCADASTRO, IPCADASTRO) VALUES (:idpreso, 1, :idusuario, :ipcomputador)";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso',$idpreso,PDO::PARAM_INT);
                $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                $stmt->execute();
        
                $resultado = $stmt->rowCount();
                //unset($GLOBALS['conexao']);
        
                if($resultado==1){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Kit Padrão inserido com sucesso! </li>");
                    echo json_encode($retorno);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Kit Padrão não foi inserido. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Incluir ou alterar o kit do preso
            elseif($tipo==2 && $kitentregue!=0){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(3,24);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para entregar Kit de Ítens. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $acao = $kitentregue['acao'];
                $idpreso = $kitentregue['idpreso'];
                $idkit = $kitentregue['idkit'];
                $observacoes = $kitentregue['observacoes'];
                $dataentrega = $kitentregue['dataentrega'];
                $itens = $kitentregue['itens'];

                if($acao=='incluir'){
                    //Insere o kit montado
                    $sql = "INSERT INTO inc_kitentregue (IDPRESO, DATAENTREGA, OBSERVACOES, IDCADASTRO, IPCADASTRO, DATACADASTRO) VALUES (:idpreso, :dataentrega, :observacoes, :idusuario, :ipcomputador, :dataagora)";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idpreso',$idpreso,PDO::PARAM_INT);
                    $stmt->bindParam('dataentrega',$dataentrega,PDO::PARAM_STR);
                    $stmt->bindParam('observacoes',$observacoes,PDO::PARAM_STR);
                    $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                    $stmt->bindParam('dataagora',$dataagora,PDO::PARAM_STR);
                    $stmt->execute();
            
                    $resultado = $stmt->rowCount();
                    if($resultado==1){
                        $sql = "SELECT max(ID) ID FROM inc_kitentregue WHERE IDPRESO = :idpreso AND DATACADASTRO = :dataagora";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idpreso',$idpreso,PDO::PARAM_INT);
                        $stmt->bindParam('dataagora',$dataagora,PDO::PARAM_STR);
                        $stmt->execute();
                
                        $resultado = $stmt->fetchAll();
                        if(count($resultado)){
                            $idkit=$resultado[0]['ID'];
                        }else{
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado o ID do novo Kit Entregue. </li>");
                            echo json_encode($retorno);
                            exit();
                        }
                    }
                    else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> O novo Kit não foi inserido. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }
                else{
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(3,25);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para alterar o Kit Entregue. </li>");
                        echo json_encode($retorno);
                        exit();
                    }

                    //Insere o kit montado
                    $sql = "UPDATE inc_kitentregue SET DATAENTREGA = :dataentrega, OBSERVACOES = :observacoes, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idkit";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('dataentrega',$dataentrega,PDO::PARAM_STR);
                    $stmt->bindParam('observacoes',$observacoes,PDO::PARAM_STR);
                    $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                    $stmt->bindParam('idkit',$idkit,PDO::PARAM_INT);
                    $stmt->execute();
                }

                if($idkit!=0){
                    //Primeiro busca todos os itens que já estão salvos para este kit
                    $sql = "SELECT ID FROM inc_kititensentregue WHERE IDKIT = :idkit AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL;"; 
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idkit', $idkit, PDO::PARAM_INT);
                    $stmt->execute();
                    $listaItensExcluir = [];
                    while($itensexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($listaItensExcluir,$itensexistente['ID']);
                    }

                    foreach($itens as $item){
                        $iditem = $item['iditem'];
                        $idbanco = $item['idbanco'];
                        $quantidade = $item['quantidade'];
    
                        if($idbanco==0){
                            $sql = "INSERT INTO inc_kititensentregue (IDKIT, IDITEM, QTD, IDCADASTRO, IPCADASTRO) VALUES (:idkit, :iditem, :quantidade, :idusuario, :ipcomputador);";

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idkit', $idkit, PDO::PARAM_INT);
                            $stmt->bindParam('iditem', $iditem, PDO::PARAM_INT);
                            $stmt->bindParam('quantidade', $quantidade, PDO::PARAM_INT);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->execute();
                            $resultado = $stmt->rowCount(); 
                            
                            if($resultado==0){
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O Ítem não foi inserido. Tente novamente mais tarde ou contate o programador. </li>");
                                echo json_encode($retorno);
                                exit();
                            }
                        }
                        else{
                            $sql = "UPDATE inc_kititensentregue SET QTD = :quantidade, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idbanco";
    
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('quantidade', $quantidade, PDO::PARAM_STR);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->bindParam('idbanco', $idbanco, PDO::PARAM_INT);
                            $stmt->execute();
                            $resultado = $stmt->rowCount(); 
                            
                            //Exclui da listagem de artigos existentes pois este artigo não foi excluído.
                            $posicao = array_search($idbanco,$listaItensExcluir);        
                            if($posicao!==false){
                                unset($listaItensExcluir[$posicao]);
                            }
                        }
                    }

                    //Exclui os itens que foram exclusos do kit.
                    if(count($listaItensExcluir)){
                        foreach($listaItensExcluir as $idexcluir){
                            //Verifica se o usuário tem a permissão necessária
                            $permissoesNecessarias = array(3,26);
                            $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                            if($blnPermitido==false){
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir ítens do Kit Entregue. </li>");
                                echo json_encode($retorno);
                                exit();
                            }

                            $sql = "UPDATE inc_kititensentregue SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";
    
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }
            }
            //Excluir kit
            elseif($tipo==3 && $idkit!=0){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(3,27);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir o Kit Entregue. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                //Exclui o kit
                $sql = "UPDATE inc_kitentregue SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idkit";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                $stmt->bindParam('idkit',$idkit,PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->rowCount();
                //unset($GLOBALS['conexao']);
        
                if($resultado==1){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Kit excluído com sucesso! </li>");
                    echo json_encode($retorno);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível excluir o Kit. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Incluir Ítens Pertence
            elseif($tipo==4){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(3,28);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para incluir Ítens de Pertence. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                //Insere um ítem
                $sql = "INSERT INTO inc_kititens (NOME, ITEMNOVO, PADRAOENTREGA, QTD, IDCADASTRO, IPCADASTRO) VALUES(:nome, :itemnovo, :padrao, :qtd, :idusuario, :ipcomputador);";
                
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('nome',$nome,PDO::PARAM_STR);
                $stmt->bindParam('itemnovo',$itemnovo,PDO::PARAM_INT);
                $stmt->bindParam('padrao',$padrao,PDO::PARAM_INT);
                $stmt->bindParam('qtd',$qtd,PDO::PARAM_INT);
                $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                $stmt->execute();
        
                $resultado = $stmt->rowCount();
                //unset($GLOBALS['conexao']);
        
                if($resultado==1){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Kit excluído com sucesso! </li>");
                    echo json_encode($retorno);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível adicionar o novo ítem. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Altera Ítens do Pertence
            elseif($tipo==5 && $iditem!=0){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(3,29);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para alterar Ítens do Pertence. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                //Altera o Kit
                $sql = "UPDATE inc_kititens SET NOME = :nome, ITEMNOVO = :itemnovo, PADRAOENTREGA = :padrao, QTD = :qtd, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :iditem";

                //$retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> $nome, $itemnovo, $padrao, $qtd, $iditem. </li>");echo json_encode($retorno);exit();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('nome',$nome,PDO::PARAM_STR);
                $stmt->bindParam('itemnovo',$itemnovo,PDO::PARAM_INT);
                $stmt->bindParam('padrao',$padrao,PDO::PARAM_INT);
                $stmt->bindParam('qtd',$qtd,PDO::PARAM_INT);
                $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                $stmt->bindParam('iditem',$iditem,PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->rowCount();
                //unset($GLOBALS['conexao']);
        
                if($resultado==1){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Ítem alterado com sucesso! </li>");
                    echo json_encode($retorno);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> O ítem não sofreu alterações. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Excluir Ítens do Pertence
            elseif($tipo==6 && $iditem!=0){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(3,30);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir Ítens do Pertence. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $sql = "UPDATE inc_kititens SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :iditem";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                $stmt->bindParam('iditem',$iditem,PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->rowCount();
                //unset($GLOBALS['conexao']);
        
                if($resultado==1){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Ítem excluído com sucesso! </li>");
                    echo json_encode($retorno);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível excluir o Ítem. </li>");
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

$retorno = array('OK' => $idkit);
echo json_encode($retorno);
exit();
