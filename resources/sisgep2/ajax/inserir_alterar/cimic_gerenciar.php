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

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    $dataAgora = date('Y-m-d H:i:s');

    $conexaoStatus = conectarBD();    
    if($conexaoStatus===true){
        try {
            //Inserir Exclusões
            if($tipo==1){
                $params=[];

                if($idmovimentacao==0){
                    $sql = "INSERT INTO cimic_exclusoes (IDPRESO, DATASAIDA,IDTIPO,IDMOTIVO,IDCADASTRO,IPCADASTRO) VALUES (?,CURRENT_DATE,?,?,?,?)";
                    $params=[$idpreso,$idtipo,$idmotivo,$idusuario,$ipcomputador];
                }else{
                    $sql = "UPDATE cimic_exclusoes SET IDTIPO = ?, IDMOTIVO = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";             
                    $params=[$idtipo,$idmotivo,$idusuario,$ipcomputador,$idmovimentacao];
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

    //echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ". __LINE__." </li>"));exit();

