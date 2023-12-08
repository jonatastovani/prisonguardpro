<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/funcoes.php';
    include_once '../../funcoes/userinfo.php';

    //Cria a variável de retorno para salvar os a mensagem a ser exibida na tela
    $retorno = [];
    $situacao = $_POST['situacao'];
    $tabela = $_POST['tabela'];
    $id = $_POST['id'];
    $blnvisuchefia = isset($_POST['blnvisuchefia'])?$_POST['blnvisuchefia']:0;

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();

    //Se for alguma alteração que exige permissão da chefia/penal
    if($blnvisuchefia==1){
        $resultado = retornaPermissaoPenal(1,1);

        //Verifica se o usuário tem a permissão necessária
        $permissoesNecessarias = array($resultado[0]['IDPERMISSAO']);
        $blnPermitido = verificaPermissao($permissoesNecessarias,"");
        $nometurno = $resultado[0]['NOME'];

        if($blnPermitido==false){
            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. É necessário ao menos a permissão de Penal do $nometurno para gerenciar a chefia. </li>");
            echo json_encode($retorno);
            exit();
        }
    }

    // Se estiver iniciado a contagem final de plantão então não se altera mais nenhum status que implica na alteração de cela
    if(in_array($tabela,array(1,2,3,4,8))){
        
        verificaBloqueioMovimentacao();

    }
    switch($tabela){
        case 1:
            $tabela = "entradas_presos";
            break;

        case 2:

            $tabela = "chefia_mudancacela";
            break;

        case 3:
            $tabela = "cimic_transferencias";
            break;

        case 4:
            $tabela = "cimic_apresentacoes";
            break;

        case 5:
            $tabela = "cimic_apresentacoes_internas_presos";
            break;

        case 6:
            $tabela = "enf_atendimentos";
            break;

        case 7:
            $tabela = "chefia_atendimentos";
            break;

        case 8:
            $tabela = "cimic_exclusoes";
            break;

        default:
            exit();
            break;
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        //Monta o Select
        $sql = "UPDATE $tabela SET IDSITUACAO = :situacao, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID IN (:id)";
    
        try {
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('situacao',$situacao,PDO::PARAM_INT);
            $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
            $stmt->bindParam('ipcomputador',$ipcomputador,PDO::PARAM_STR);
            $stmt->bindParam('id',$id,PDO::PARAM_STR);
            $stmt->execute();
    
            $resultado = $stmt->rowCount();
            //unset($GLOBALS['conexao']);
    
            if($resultado==1){
                $retorno = array('OK' => "<li class = 'mensagem-exito'> Status alterado com sucesso! </li>");
                echo json_encode($retorno);
                exit();
            }
            else{
                $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Alteração de Status não obteve êxito. </li>");
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