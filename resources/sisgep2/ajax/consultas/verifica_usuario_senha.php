<?php
    session_start();
    header('Content-Type: application/json');
    
    //Conexão
    include_once "../../configuracoes/conexao.php";
    
    //Cria a variável erros para salvar os erros e exibir na tela
    $erros = [];

    //Recebe os dados POST
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    //Verifica se os dados de usuario e senha foram preenchidos
    if(empty($usuario) or empty($senha)){
        $erros = array('ERRO' => "<li class = 'mensagem-erro'> O campo usuario/senha precisa ser preenchido </li>");
        echo json_encode($erros);
    }else{
        //Obtem o status da conexão. Se retornar com o valor true então significa que a conexão foi efetuada com sucesso, do contrário se exibe o erro no else.
        $statusConexao = conectarBD();
        //$erros = array('ERRO' => "<li class = 'mensagem-erro'> Status $statusConexao </li>");
        //echo json_encode($erros);

        if($statusConexao===true){
            //Busca se o usuário existe
            $sql = "SELECT * FROM tab_usuarios WHERE USUARIO = :usuario";
            try {
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                
                //Se encontrar o usuário então segue para verificar a senha
                if(count($resultado)==1){
                    //Faz a criptografia da senha para MD5
                    $senha=md5($senha);
                    $sql = "SELECT * FROM tab_usuarios WHERE USUARIO = :usuario AND SENHA = :senha";
                    
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                    $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();

                    //Se encontrar os dados de usuário e senha então se retorna somente os dados para ser observado no console, caso queira, mas não há necessidade de retornar nada.
                    if(count($resultado)==1){
                        //$erros = array('ERRO' => "<li class = 'mensagem-erro'> Conexão estabelecida </li>");
                        //Preenche dados da sessão
                        $_SESSION['logado'] = true;
                        $_SESSION['id_usuario'] = $resultado[0]['ID'];
                        
                        //Fecha a conexão
                        //unset($GLOBALS['conexao']);

                        echo json_encode($resultado);

                    //Se a senha estiver errada então retorna o erro
                    }else{
                        $erros = array('ERRO' => "<li class = 'mensagem-erro'>Usuário ou senha incorretos</li>");
                        echo json_encode($erros);
                    }

                //Se não encontrar o usuário então retorna o erro
                }else{
                        $erros = array('ERRO' => "<li class = 'mensagem-erro'> Usuário inexistente </li>");
                        echo json_encode($erros);        
                }                
            } catch (PDOException $e) {
                $erros = array('ERRO' => "<li class = 'mensagem-erro'> Ocorreu um erro. Erro: ".$e->getMessage()."</li>");
                echo json_encode($erros);
            }
        }else{
            //Caso não houver conexão com o banco de dados então se exibe o erro encontrado na conexão
            $erros = array('ERRO' => "<li class = 'mensagem-erro'>$statusConexao</li>");
            echo json_encode($erros);
        }
    }
?>