<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes_comuns.php";

    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(9,49,50,51);
    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

    if($blnPermitido==false){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. </li>");
        echo json_encode($retorno);
        exit();
    }

    $tipo = isset($_POST['tipo'])?$_POST['tipo']:0;
    $id = isset($_POST['id'])?$_POST['id']:0;
    $dataagora = date('Y-m-d H:i:s');
    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    
    if($tipo == 0){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Tipo não informado. </li>");
        echo json_encode($retorno);
        exit();
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            if($tipo==1){
                $cidade = $_POST['cidade'];
                $perfil = $_POST['perfil'];
                $codigo = $_POST['codigo'];
                $nome = $_POST['nome'];
                $atribuido = $_POST['atribuido'];
                $tipounidade = $_POST['tipounidade'];
                $coord = $_POST['coord'];
                $diretor = $_POST['diretor'];
                $notes = $_POST['notes'];
                $cimic = $_POST['cimic'];
                $endereco = $_POST['endereco'];
                $cep = $_POST['cep'];
                $telefones = $_POST['telefones'];
                
                //Verifica se existe algum código de unidade igual o que está sendo incluso ou alterado
                $sql = "SELECT * FROM tab_unidades WHERE CODIGO = :codigo";

                if($id!=0){
                    $sql .= " AND ID <> :id";
                }
                //echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." </li>"));exit();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                if($id!=0){
                    $stmt->bindParam('id', $id, PDO::PARAM_INT);
                }
                $stmt->bindParam('codigo', $codigo, PDO::PARAM_STR);
                $stmt->execute();
                $resultado = $stmt->rowCount();

                if($resultado>0){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Já existe uma unidade cadastrada com o mesmo CÓDIGO informado. Solicite ao programador para reativar a unidade caso ela esteja excluída. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                if($id==0){
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(9,49);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para incluir Unidades Prisionais. </li>");
                        echo json_encode($retorno);
                        exit();
                    }

                    $sql = "INSERT INTO tab_unidades (IDCIDADE, IDPERFIL, CODIGO, NOMEUNIDADE, NOMEATRIBUIDO, IDTIPOUNIDADE, IDCOORDENADORIA, DIRETOR, EMAILNOTES, EMAILCIMIC, ENDERECO, CEP, TELEFONES, IDCADASTRO, IPCADASTRO) VALUES (:cidade, :perfil, :codigo, :nome, :atribuido, :tipounidade, :coord, :diretor, :notes, :cimic, :endereco, :cep, :telefones, :idusuario, :ipcomputador)";
                }else{
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(9,50);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para alterar dados das Unidades Prisionais. </li>");
                        echo json_encode($retorno);
                        exit();
                    }

                    $sql = "UPDATE tab_unidades SET IDCIDADE = :cidade, IDPERFIL = :perfil, CODIGO = :codigo, NOMEUNIDADE = :nome, NOMEATRIBUIDO = :atribuido, IDTIPOUNIDADE = :tipounidade, IDCOORDENADORIA = :coord, DIRETOR = :diretor, EMAILNOTES = :notes, EMAILCIMIC = :cimic, ENDERECO = :endereco, CEP = :cep, TELEFONES = :telefones, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :id";
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                if($id!=0){
                    $stmt->bindParam('id', $id, PDO::PARAM_INT);
                }
                $stmt->bindParam('cidade', $cidade, PDO::PARAM_INT);
                $stmt->bindParam('perfil', $perfil, PDO::PARAM_INT);
                $stmt->bindParam('codigo', $codigo, PDO::PARAM_STR);
                $stmt->bindParam('nome', $nome, PDO::PARAM_STR);
                $stmt->bindParam('atribuido', $atribuido, PDO::PARAM_STR);
                $stmt->bindParam('tipounidade', $tipounidade, PDO::PARAM_INT);
                $stmt->bindParam('coord', $coord, PDO::PARAM_INT);
                $stmt->bindParam('diretor', $diretor, PDO::PARAM_STR);
                $stmt->bindParam('notes', $notes, PDO::PARAM_STR);
                $stmt->bindParam('cimic', $cimic, PDO::PARAM_STR);
                $stmt->bindParam('endereco', $endereco, PDO::PARAM_STR);
                $stmt->bindParam('cep', $cep, PDO::PARAM_STR);
                $stmt->bindParam('telefones', $telefones, PDO::PARAM_STR);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
    
                $stmt->execute();

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Operação realizada com sucesso!! </li>");
                echo json_encode($retorno);
                exit();

            }
            //Excluir uma unidade
            elseif($tipo==2 && $id!=0){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(9,51);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir Unidades Prisionais. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $sql = "UPDATE tab_unidades SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :id";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('id', $id, PDO::PARAM_INT);    
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->execute();

                $resultado = $stmt->rowCount();
                if($resultado>0){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Unidade excluída com sucesso!! </li>");
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
    }
    
    //unset($GLOBALS['conexao']);

    $retorno = array('OK' => "Executado com sucesso");
    echo json_encode($retorno);
