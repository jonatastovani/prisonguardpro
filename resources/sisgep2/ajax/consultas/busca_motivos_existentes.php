<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";

    //recupera os dados de tipo
    $tipo = $_POST['tipo'];

    //Cria a variável de retorno para salvar os a mensagem a ser exibida na tela
    $retorno = [];

    if(empty($tipo)){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum tipo foi informado </li>");
        echo json_encode($retorno);
        exit;
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        //Monta o Select
        $sql = "SELECT MM.ID VALOR, CONCAT(MM.SIGLA, ' - ', MM.NOME) NOMEEXIBIR
        FROM tab_movimentacoesfiltro MF
        INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = MF.IDMOTIVO
        WHERE MF.IDTIPO = :tipo";

        try {
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('tipo',$tipo, PDO::PARAM_INT);
            $stmt->execute();
    
            $resultado = $stmt->fetchAll();
            //unset($GLOBALS['conexao']);

            //Verifica se foi encontrado algum registro
            if(count($resultado)){
                echo json_encode($resultado);
                exit();
            }
            else{
                $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum motivo foi encontrado para o tipo informado </li>");
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