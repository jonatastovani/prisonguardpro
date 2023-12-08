<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes.php";

    $retorno=[];

    $ACAO = $_POST['ACAO'];
    $identrada = isset($_POST['IDENTRADA'])?$_POST['IDENTRADA']:0;
    $dataentrada = isset($_POST['DATAENTRADA'])?$_POST['DATAENTRADA']:0;
    $idorigem = isset($_POST['IDORIGEM'])?$_POST['IDORIGEM']:0;
    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    $PRESOS = $_POST['PRESOS'];

    $dataAgora = date('Y-m-d H:i:s');

    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(4,5,16,17);
    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

    if($blnPermitido==false){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. </li>");
        echo json_encode($retorno);
        exit();
    }

    $conexaoStatus = conectarBD();    
    if($conexaoStatus===true){
        try {

            if($ACAO=='nova'){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(4,16);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para inserir entrada de presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                //Se for 'nova' é porque é uma entrada nova, então se cria a entrada primeiro
                $sql = "INSERT INTO entradas (IDORIGEM, DATAENTRADA, DATACADASTRO, IDCADASTRO, IPCADASTRO) VALUES (:idorigem, :dataentrada, :dataAgora, :idusuario, :ipcomputador)";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idorigem', $idorigem, PDO::PARAM_INT);
                $stmt->bindParam('dataentrada', $dataentrada, PDO::PARAM_STR);
                $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->execute();
                $resultado = $stmt->rowCount();

                if($resultado==1){
                    $sql = "SELECT * FROM entradas WHERE DATAENTRADA = :dataentrada AND DATACADASTRO = :dataAgora";
        
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('dataentrada', $dataentrada, PDO::PARAM_STR);
                    $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();    
                    
                    if(count($resultado)==1){
                        $identrada = $resultado[0]['ID'];
                    }else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível consultar a entrada inserida. Tente novamente mais tarde ou contate o programador. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> A entrada não foi inserida. Tente novamente mais tarde ou contate o programador. </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }else{
                //Se for 'alterar' é porque já existe a entrada, então somente se altera as informações dos presos

                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(5,17);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para alterar entrada de presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                //Verifica se existem presos já lançados pelo cimic. Se houver não será possível alterar a entrada. A não ser que a pessoa possua permissão específica para isso.
                $sql = "SELECT * FROM entradas_presos WHERE IDENTRADA = :identrada AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL AND LANCADOCIMIC = TRUE;"; 
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('identrada', $identrada, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->rowCount();

                if($resultado>0){
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(9,17);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para alterar entrada de presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }

                $sql = "UPDATE entradas SET IDORIGEM = :idorigem, DATAENTRADA = :dataentrada, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :identrada";
    
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idorigem', $idorigem, PDO::PARAM_INT);
                $stmt->bindParam('dataentrada', $dataentrada, PDO::PARAM_STR);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('identrada', $identrada, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->rowCount();    
            }

            if($identrada!=0){
                //Primeiro busca todos os presos que já estão salvos com essa entrada
                $sql = "SELECT ID FROM entradas_presos WHERE IDENTRADA = :identrada AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL AND LANCADOCIMIC = FALSE;"; 
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('identrada', $identrada, PDO::PARAM_INT);
                $stmt->execute();
                $listaExcluir = [];
                while($presosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                    array_push($listaExcluir,$presosexistente['ID']);
                }

                foreach($PRESOS as $preso){
        
                    $nome = $preso['NOME'];
                    $pai = $preso['pai'];
                    $mae = $preso['mae'];
                    $provisorio = isset($preso['provisorio'])?$preso['provisorio']:0;
                    if($provisorio=='true'){
                        $provisorio = 1;
                    }else{
                        $provisorio = 0;
                    }
                    $matricula = $preso['MATRICULA'];
                    $idpreso = $preso['IDPRESO'];
                    $rg = $preso['RG'];
                    $informacoes = $preso['INFORMACOES'];
                    $observacoes = $preso['OBSERVACOES'];
                    $matriculaVinculada = $preso['MATRICULAVINCULADA'];
                    $artigos = isset($preso['ARTIGOS'])?$preso['ARTIGOS']:0;
        
                    //Se for 0 é porque está se inserindo o preso agora. Se for diferente é porque está alterando
                    if($idpreso==0){
                        $sql = "INSERT INTO entradas_presos (IDENTRADA, MATRICULA, MATRICULAVINCULADA, NOME, PAI, MAE, PROVISORIO, RG, INFORMACOES, OBSERVACOES, DATACADASTRO, IDCADASTRO, IPCADASTRO) VALUES (:identrada, :matricula, :matriculaVinculada, :nome, :pai, :mae, :provisorio, :rg, :informacoes, :observacoes, :dataAgora, :idusuario, :ipcomputador);"; 
                    }else{
                        //Verifica se o preso pode ser alterado.
                        //Se caso já foi lançado pelo cimic, somente o CIMIC pode alterar
                        /*$sql = "SELECT * FROM entradas_presos WHERE ID = :idpreso AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL AND LANCADOCIMIC = TRUE;"; 
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                        $stmt->execute();
                        $resultado = $stmt->rowCount();
        
                        //Se não encontrar o registro significa que já foi lançado pelo cimic, então não se altera mais este cadastro
                        if($resultado>0){
                            //Verifica se o usuário tem a permissão necessária
                            $permissoesNecessarias = array(9,17);
                            $blnPermitido = verificaPermissao($permissoesNecessarias);
                            if($blnPermitido!==true){
                                continue;
                            }
                        }*/

                        $sql = "UPDATE entradas_presos SET MATRICULA = :matricula, MATRICULAVINCULADA = :matriculaVinculada, NOME = :nome, PAI = :pai, MAE = :mae, PROVISORIO = :provisorio, RG = :rg, INFORMACOES = :informacoes, OBSERVACOES = :observacoes, DATAATUALIZACAO = :dataAgora, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idpreso;";

                        //Exclui da listagem de presos existentes pois este preso está sendo alterado somente. No final se exclui todos os presos que não foram atualizados, pois na verdade eles foram excluídos.
                        $posicao = array_search($idpreso,$listaExcluir);        
                        if($posicao!==false){
                            unset($listaExcluir[$posicao]);
                        }
                    }
                    
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    if($idpreso==0){
                        $stmt->bindParam('identrada', $identrada, PDO::PARAM_INT);
                    }else{
                        $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                    }
                    $stmt->bindParam('matricula', $matricula, PDO::PARAM_INT);
                    $stmt->bindParam('matriculaVinculada', $matriculaVinculada, PDO::PARAM_BOOL);
                    $stmt->bindParam('nome', $nome, PDO::PARAM_STR);
                    $stmt->bindParam('pai', $pai, PDO::PARAM_STR);
                    $stmt->bindParam('mae', $mae, PDO::PARAM_STR);
                    $stmt->bindParam('provisorio', $provisorio, PDO::PARAM_STR);
                    $stmt->bindParam('rg', $rg, PDO::PARAM_STR);
                    $stmt->bindParam('informacoes', $informacoes, PDO::PARAM_STR);
                    $stmt->bindParam('observacoes', $observacoes, PDO::PARAM_STR);
                    $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                    $stmt->execute();
                    $resultado = $stmt->rowCount(); 

                    //Somente busca o idpreso caso estiver inseririndo cadastro novo. 
                    if($resultado==1 && $idpreso==0){
                        $sql = "SELECT * FROM entradas_presos WHERE NOME = :nome AND DATACADASTRO = :dataAgora AND IDENTRADA = :identrada ORDER BY ID DESC";
                        
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('nome', $nome, PDO::PARAM_STR);
                        $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                        $stmt->bindParam('identrada', $identrada, PDO::PARAM_INT);
                        $stmt->execute();
                        $resultado = $stmt->fetchAll(); 

                        if(count($resultado)){
                            $idpreso = $resultado[0]['ID'];
                        }else{
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O preso não foi encontrado para ser incluso os artigos. Tente novamente mais tarde ou contate o programador. </li>");
                            echo json_encode($retorno);
                            exit();
                        }
                    }

                    if($idpreso!=0 && $artigos!=0){
                        //Primeiro busca todos os artigos que já estão salvos para este preso
                        $sql = "SELECT ID FROM entradas_artigos WHERE IDPRESO = :idpreso AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL;"; 
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                        $stmt->execute();
                        $listaArtigosExcluir = [];
                        while($artigosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                            array_push($listaArtigosExcluir,$artigosexistente['ID']);
                        }

                        foreach($artigos as $artigo){
                            $idbanco = $artigo['idbanco'];
                            $idartigo = $artigo['ARTIGO'];
                            $obsartigo = $artigo['obsartigo'];

                            if($idbanco==0){
                                $sql = "INSERT INTO entradas_artigos (IDPRESO, IDARTIGO, OBSERVACOES, DATACADASTRO, IDCADASTRO, IPCADASTRO) VALUES (:idpreso, :idartigo, :obsartigo, :dataAgora, :idusuario, :ipcomputador);";

                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                                $stmt->bindParam('idartigo', $idartigo, PDO::PARAM_INT);
                                $stmt->bindParam('obsartigo', $obsartigo, PDO::PARAM_STR);
                                $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                $stmt->execute();
                                $resultado = $stmt->rowCount(); 
                                
                                if($resultado==0){
                                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O Artigo não foi inserido. Tente novamente mais tarde ou contate o programador. </li>");
                                    echo json_encode($retorno);
                                    exit();
                                }
                            }
                            else{
                                $sql = "UPDATE entradas_artigos SET OBSERVACOES = :obsartigo, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idbanco";
    
                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->bindParam('idbanco', $idbanco, PDO::PARAM_INT);
                                $stmt->bindParam('obsartigo', $obsartigo, PDO::PARAM_STR);
                                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                $stmt->execute();
                                $resultado = $stmt->rowCount(); 
                                
                                /*if($resultado==0){
                                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O Artigo não foi alterado. Tente novamente mais tarde ou contate o programador. </li>");
                                    echo json_encode($retorno);
                                    exit();
                                }*/

                                //Exclui da listagem de artigos existentes pois este artigo não foi excluído.
                                $posicao = array_search($idbanco,$listaArtigosExcluir);        
                                if($posicao!==false){
                                    unset($listaArtigosExcluir[$posicao]);
                                }
                            }
                        }

                        //Exclui os artigos que foram exclusos da entrada do preso.
                        if(count($listaArtigosExcluir)){
                            foreach($listaArtigosExcluir as $idexcluir){
                                $sql = "UPDATE entradas_artigos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";
        
                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                                $stmt->execute();
                            }
                        }
        
                    }
                }

                //Exclui os presos que foram exclusos da entrada.
                if(count($listaExcluir)){
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(3,6);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                    if($blnPermitido==false){
                        $retorno = array('OK' => "<li class = 'mensagem-aviso'> Você não possui a permissão necessária para excluir presos da entrada. Caso tenha adicionado mais presos a esta entrada, as informações foram inclusão com sucesso! </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                    
                    foreach($listaExcluir as $idexcluir){
                        $sql = "UPDATE entradas_presos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                        $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                        $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }

            }else{
                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível consultar a entrada inserida. Tente novamente mais tarde ou contate o programador. </li>");
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

    $retorno = array('IDENTRADA' => intval($identrada));
    echo json_encode($retorno);
