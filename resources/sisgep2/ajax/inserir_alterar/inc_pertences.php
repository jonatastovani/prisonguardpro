<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once '../../funcoes/funcoes.php';
    
    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(31,32,33,34,35,36);
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
    $descartado = isset($_POST['descartado'])?$_POST['descartado']:0;
    $entrada = isset($_POST['entrada'])?$_POST['entrada']:0;
    $idpertence = isset($_POST['idpertence'])?$_POST['idpertence']:0;
    $tipopertence = isset($_POST['tipopertence'])?$_POST['tipopertence']:0;
    $nomeretirada = isset($_POST['nomeretirada'])?$_POST['nomeretirada']:'';
    $retirada = isset($_POST['retirada'])?$_POST['retirada']:0;
    $grau = isset($_POST['grau'])?$_POST['grau']:0;
    $observacoes = isset($_POST['observacoes'])?$_POST['observacoes']:'';
    $datadescartado = isset($_POST['datadescartado'])?$_POST['datadescartado']:date('Y-m-d h:i:s');

    if($descartado==='true'){
        $descartado=1;
    }else {
        $descartado=0;
        $datadescartado='';
    }
    
    if($nomeretirada==''){
        $grau=0;
        $retirada='';
    }

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    $dataagora = date('Y-m-d H:i:s');

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            //Insere o novo pertence a ser guardado
            if($tipo==1){
                if($tipopertence==1 || $tipopertence==2){
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(3,31);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para Incluir Pertences Guardados. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }else{
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(3,34);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para Incluir Sedex Retidos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }                    
                }

                $sql = "INSERT INTO inc_pertences (IDPRESO, IDTIPOPERTENCE, DATAENTRADA, OBSERVACOES, IDCADASTRO, IPCADASTRO) VALUES (:idpreso, :tipopertence, :entrada, :observacoes, :idusuario, :ipcomputador)";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso',$idpreso,PDO::PARAM_INT);
                $stmt->bindParam('tipopertence',$tipopertence,PDO::PARAM_INT);
                $stmt->bindParam('entrada',$entrada,PDO::PARAM_STR);
                $stmt->bindParam('observacoes',$observacoes,PDO::PARAM_STR);
                $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                $stmt->execute();
        
                $resultado = $stmt->rowCount();
                //unset($GLOBALS['conexao']);
        
                if($resultado==1){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Pertence inserido com sucesso! </li>");
                    echo json_encode($retorno);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> O Pertence não foi inserido. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Alterar o pertence
            elseif($tipo==2 && $idpertence!=0){
                if($tipopertence==1 || $tipopertence==2){
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(3,32);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para Alterar Pertences Guardados. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }else{
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(3,35);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para Alterar Sedex Retidos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }                    
                }

                if($descartado==1){
                    if($tipopertence!=1 && $entrada!=0){
                        $dataentrada = ', DATAENTRADA = :entrada';
                    }else {
                        $dataentrada = '';
                    }
                    $sql = "UPDATE inc_pertences SET DESCARTADO = TRUE, DATADESCARTADO = :datadescartado $dataentrada, OBSERVACOES = :observacoes, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idpertence";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('datadescartado',$datadescartado,PDO::PARAM_STR);
                    if($tipopertence!=1 && $entrada!=0){
                        $stmt->bindParam('entrada',$entrada,PDO::PARAM_STR);
                    }
                    $stmt->bindParam('observacoes',$observacoes,PDO::PARAM_STR);
                    $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                    $stmt->bindParam('idpertence',$idpertence,PDO::PARAM_INT);
                    //$stmt->bindParam('tipopertence',$tipopertence,PDO::PARAM_INT);
                    $stmt->execute();
            
                    $resultado = $stmt->rowCount();
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados salvos com sucesso! </li>");
                    echo json_encode($retorno);
                    exit();
                }
                else{

                    if($tipopertence!=1 && $entrada!=0){
                        $dataentrada = ', DATAENTRADA = :entrada';
                    }else {
                        $dataentrada = '';
                    }
                    $sql = "UPDATE inc_pertences SET DESCARTADO = FALSE $dataentrada, OBSERVACOES = :observacoes, NOMERETIRADA = :nomeretirada, IDGRAUPARENTESCO = :grau, DATARETIRADA = :retirada, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idpertence";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    if($tipopertence!=1 && $entrada!=0){
                        $stmt->bindParam('entrada',$entrada,PDO::PARAM_STR);
                    }
                    $stmt->bindParam('observacoes',$observacoes,PDO::PARAM_STR);
                    $stmt->bindParam('nomeretirada',$nomeretirada,PDO::PARAM_STR);
                    $stmt->bindParam('grau',$grau,PDO::PARAM_INT);
                    $stmt->bindParam('retirada',$retirada,PDO::PARAM_STR);
                    $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                    $stmt->bindParam('idpertence',$idpertence,PDO::PARAM_INT);
                    //$stmt->bindParam('tipopertence',$tipopertence,PDO::PARAM_INT);
                    $stmt->execute();

                    $resultado = $stmt->rowCount();
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados salvos com sucesso! </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Excluir pertence
            elseif($tipo==3 && $idpertence!=0){
                if($tipopertence==1 || $tipopertence==2){
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(33);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para Excluir Pertences Guardados. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }else{
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(36);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para Excluir Sedex Retidos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }                    
                }

                //Exclui o kit
                $sql = "UPDATE inc_pertences SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idpertence";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
                $stmt->bindParam('idpertence',$idpertence,PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->rowCount();
                //unset($GLOBALS['conexao']);
        
                if($resultado==1){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Pertence excluído com sucesso! </li>");
                    echo json_encode($retorno);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível excluir o Pertence. </li>");
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
