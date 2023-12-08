<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes_comuns.php";


    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(9,20);
    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

    if($blnPermitido==false){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. </li>");
        echo json_encode($retorno);
        exit();
    }
    
    $id = isset($_POST['id'])?$_POST['id']:0;
    $dataagora = date('Y-m-d H:i:s');
    
    if($id == 0){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum ID foi informado para alteração. </li>");
        echo json_encode($retorno);
        exit();
    }

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {

            $sql = "UPDATE tab_artigos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :id";             

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->rowCount();

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
