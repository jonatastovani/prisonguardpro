<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    $tipo = $_POST['tipo'];
    $matric = isset($_POST['matric'])?$_POST['matric']:0;
    $cpfvisitante = isset($_POST['cpfvisitante'])?$_POST['cpfvisitante']:0;
    
    //Cria a variável de retorno para salvar os a mensagem a ser exibida na tela
    $retorno = [];

    if($tipo==1 && $matric==0){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhuma matrícula informada </li>");
        echo json_encode($retorno);
        exit();
    }

    if($tipo==2 && $cpfvisitante==0){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhuma CPF informado </li>");
        echo json_encode($retorno);
        exit();
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        //Monta o Select
        $sql = "SELECT EP.ID, 
        (SELECT ID FROM entradas_presos EP2 WHERE EP2.MATRICULA = EP.MATRICULA AND EP2.ID <> EP.ID AND EP2.IDEXCLUSOREGISTRO IS NULL AND EP2.DATAEXCLUSOREGISTRO IS NULL ORDER BY EP2.ID DESC LIMIT 1) IDANTIGO
        FROM cadastros CD
        INNER JOIN entradas_presos EP ON EP.MATRICULA = CD.MATRICULA
        WHERE CD.MATRICULA = :matric ORDER BY EP.ID DESC LIMIT 1;";

        try {
            if($tipo==1){
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('matric',$matric, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);

                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    if($resultado[0]['ID']!=null){
                        //Baixa a foto do preso para o servidor local
                        $caminhoFoto = baixarFotoServidor($resultado[0]['ID'],1,"../../");
                        if($caminhoFoto=="imagens/sem-foto.png" && $resultado[0]['IDANTIGO']!=""){
                            $caminhoFoto = baixarFotoServidor($resultado[0]['IDANTIGO'],1,"../../");
                        }
                    }

                    $retorno = array('OK' => $caminhoFoto);
                    echo json_encode($retorno);
                    exit();
                }
                /*else{
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Nenhuma informação encontrada para a matrícula informada, mas você pode inserir os dados para a inclusão deste novo preso! </li>");
                    echo json_encode($retorno);
                    exit();
                }*/
            }
            elseif($tipo==2){
                $caminhoFoto = baixarFotoServidor($cpfvisitante,2,"../../");
                $retorno = array('OK' => $caminhoFoto);
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
        exit();
    }