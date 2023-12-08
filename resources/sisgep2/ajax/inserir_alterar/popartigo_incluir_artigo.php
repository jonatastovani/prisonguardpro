<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes_comuns.php";

    $acao = $_POST['acao'];
    $valor = isset($_POST['valor'])?$_POST['valor']:0;
    $id = isset($_POST['id'])?$_POST['id']:0;
    $dataagora = date('Y-m-d H:i:s');
    
    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    
    if($valor == 0){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum valor foi inserido. </li>");
        echo json_encode($retorno);
        exit();
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {

            //Verifica se existe um artigo ativo com o mesmo nome
            $sql = "SELECT * FROM tab_artigos WHERE NOME = :valor AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL";

            if($acao!='incluir'){
                $sql .= " AND ID <> :id";
            }

            $stmt = $GLOBALS['conexao']->prepare($sql);
            if($acao!='incluir'){
                $stmt->bindParam('id', $id, PDO::PARAM_INT);
            }
            $stmt->bindParam('valor', $valor, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->rowCount();

            if($resultado>0){
                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Já existe um artigo ativo com o mesmo nome informado. </li>");
                echo json_encode($retorno);
                exit();
            }

            if($acao=='incluir'){
                $sql = "INSERT INTO tab_artigos (NOME, IDCADASTRO, IPCADASTRO) VALUES (:valor, :idusuario, :ipcomputador)";
            }else{
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(9,19);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. </li>");
                    echo json_encode($retorno);
                    exit();
                }
                if($id == 0){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum ID foi informado para alteração. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            
                $sql = "UPDATE tab_artigos SET NOME = :valor, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :id";             
            }

            $stmt = $GLOBALS['conexao']->prepare($sql);
            if($acao!='incluir'){
                $stmt->bindParam('id', $id, PDO::PARAM_INT);
            }
            $stmt->bindParam('valor', $valor, PDO::PARAM_STR);
            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);

            $stmt->execute();
            /*$resultado = $stmt->rowCount();
            
            if($resultado!=1){
                if($blnMatriculaVinculada==0){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O cadastro de preso não foi inserido. Tente novamente mais tarde ou contate o programador. </li>");
                    echo json_encode($retorno);
                    exit();
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O cadastro de preso não foi alterado. Tente novamente mais tarde ou contate o programador. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }*/

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

    $retorno = array('OK' => "Executado com sucesso");
    echo json_encode($retorno);
