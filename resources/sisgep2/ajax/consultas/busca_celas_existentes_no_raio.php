<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";

    //recupera os dados de raio
    $raio = $_POST['raio'];

    //Cria a variável de retorno para salvar os a mensagem a ser exibida na tela
    $retorno = [];

    if(empty($raio)){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum raio foi informado </li>");
        echo json_encode($retorno);
        exit;
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        //Monta o Select
        $sql = "SELECT NOME, QTD FROM tab_raioscelas WHERE NOME = :raio";

        try {
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('raio',$raio, PDO::PARAM_STR);
            $stmt->execute();
    
            $resultado = $stmt->fetchAll();
            //unset($GLOBALS['conexao']);

            //Verifica se foi encontrado algum registro
            if(count($resultado)){
                echo json_encode($resultado);
                exit();
            }
            else{
                $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma quantidade de cela foi encontrado para o raio informado </li>");
                echo json_encode($retorno);
                exit;
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