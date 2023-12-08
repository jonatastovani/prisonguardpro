<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes.php";
    
    //Cria a variável de retorno para salvar os a mensagem a ser exibida na tela
    $retorno = [];

    //Obtem o tipo de pesquisa para poder assim realizar a consulta.
    //Tipo 1 = exclui dados da entrada
    //Tipo 2 = exclui dados dos presos
    //Tipo 3 = exclui dados dos artigos do preso
    //Tipo 4 = exclui entrada e a entrada de preso, pois o usuário escolheu por excluir o preso e este preso é o último da entrada.
    $tipo = $_POST['tipo'];

    $identrada = isset($_POST['identrada'])?$_POST['identrada']:0;
    $idpresobancodados = isset($_POST['idpresobancodados'])?$_POST['idpresobancodados']:0;
    $idbancoartigo = isset($_POST['idbancoartigo'])?$_POST['idbancoartigo']:0;
    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        try {
            //Verifica se existem presos já lançados pelo cimic. Se houver não será possível excluir a entrada. A não ser que a pessoa possua permissão específica para isso (ainda não criado essa permissão em 22/04/2022).
            $sql = "SELECT * FROM entradas_presos WHERE IDENTRADA = :identrada AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL AND LANCADOCIMIC = TRUE;"; 
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('identrada', $identrada, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->rowCount();

            if($resultado>=1){
                $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível excluir a entrada de presos, pois algum preso desta entrada já foi incluso pelo CIMIC. </li>");
                echo json_encode($retorno);
                exit();
            }
            
            //Tipo 1 = exclui entrada
            if($tipo==1 || $tipo==4){
                ///Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(3,22);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir entrada de presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }
                
                //Monta o Select
                $sql = "UPDATE entradas SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :identrada";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario',$idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador',$ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('identrada',$identrada, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->rowCount();
    
                //Verifica se foi alterado algum registro
                if($resultado==1){
                    //Se for tipo 1 então se encerra o código aqui, se não for vai excluir o preso e encerrar o código lá.
                    if($tipo==1){
                        //Não fechar a conexão caso for tipo 4
                        //unset($GLOBALS['conexao']);
                        $retorno = array('OK' => "<li class = 'mensagem-exito'> Entrada de preso excluída com sucesso! </li>");
                        echo json_encode($retorno);
                        exit();    
                    }
    
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível excluir o registro. Verifique suas permissões! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }

            //Tipo 2 = exclui dados dos presos
            if($tipo==2 || $tipo==4){
    
                //Monta o Select
                $sql = "UPDATE entradas_presos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idpresobancodados";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario',$idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador',$ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('idpresobancodados',$idpresobancodados, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->rowCount();
                //unset($GLOBALS['conexao']);
               
                //Verifica se foi alterado algum registro
                if($resultado==1){
                    if($tipo==2){
                        $retorno = array('OK' => "<li class = 'mensagem-exito'> Preso excluído com sucesso! </li>");
                    }elseif($tipo==4){
                        $retorno = array('OK' => "<li class = 'mensagem-exito'> Preso e Entrada de Preso excluídos com sucesso! </li>");
                    }
                    echo json_encode($retorno);
                    exit();
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível excluir o registro. Verifique suas permissões! </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 3 = exclui dados dos artigos do preso
            if($tipo==3){
                //Monta o Select
                $sql = "UPDATE entradas_artigos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idbancoartigo";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario',$idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador',$ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('idbancoartigo',$idbancoartigo, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->rowCount();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi alterado algum registro
                if($resultado==1){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Artigo excluído com sucesso! </li>");
                    echo json_encode($retorno);
                    exit();
                }else{
                    $retorno = array('OK' => "<li class = 'mensagem-aviso'> Não foi possível excluir o registro. Verifique suas permissões! </li>");
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