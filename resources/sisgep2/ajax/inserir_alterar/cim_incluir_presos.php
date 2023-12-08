<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes.php";

    $retorno=[];
    
    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(9,11);
    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

    if($blnPermitido==false){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. </li>");
        echo json_encode($retorno);
        exit();
    }

    $acao = $_POST['acao'];
    $dataagora = date('Y-m-d H:i:s');
    $idmovimentacao = 0; //ID retorno para imprimir termo de abertura
    $idpresobancodados = isset($_POST['idpresobancodados'])?$_POST['idpresobancodados']:0;
    $nome = isset($_POST['nome'])?$_POST['nome']:0;
    $matricula = isset($_POST['matricula'])?$_POST['matricula']:0;
    $blnMatriculaVinculada = isset($_POST['blnMatriculaVinculada'])?$_POST['blnMatriculaVinculada']:0;
    $rg = isset($_POST['rg'])?$_POST['rg']:0;
    $cpf = isset($_POST['cpf'])?$_POST['cpf']:'';
    $outrodoc = isset($_POST['outrodoc'])?$_POST['outrodoc']:0;
    $pai = isset($_POST['pai'])?$_POST['pai']:0;
    $mae = isset($_POST['mae'])?$_POST['mae']:0;
    
    $nacionalidade = isset($_POST['nacionalidade'])?$_POST['nacionalidade']:0;
    if($nacionalidade!=1){
        $ufnascimento = 0;
        $cidadenascimento = 0;
    }else{
        $ufnascimento = isset($_POST['ufnascimento'])?$_POST['ufnascimento']:0;
        $cidadenascimento = isset($_POST['cidadenascimento'])?$_POST['cidadenascimento']:0;
    }

    $datanascimento = isset($_POST['datanascimento'])?$_POST['datanascimento']:0;
    $dataentrada = isset($_POST['dataentrada'])?$_POST['dataentrada']:0;
    $dataprisao = isset($_POST['dataprisao'])?$_POST['dataprisao']:0;
    $regime = isset($_POST['regime'])?$_POST['regime']:0;
    $provisorio = isset($_POST['provisorio'])?$_POST['provisorio']:0;
    $reincidente = isset($_POST['reincidente'])?$_POST['reincidente']:0;
    $tipomovimentacao = isset($_POST['tipomovimentacao'])?$_POST['tipomovimentacao']:0;
    $motivo = isset($_POST['motivo'])?$_POST['motivo']:0;
    $observacoes = isset($_POST['observacoes'])?$_POST['observacoes']:0;
    $artigos = isset($_POST['artigos'])?$_POST['artigos']:0;

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {

            if($blnMatriculaVinculada==0 && $acao=='incluir'){
                $sql = "INSERT INTO cadastros (MATRICULA, IDPRESO, NOME, DATANASC, IDCIDADENASC, IDESTADONASC, NACIONALIDADE, REGIME, PROVISORIO, REINCIDENTE, RG, CPF, OUTRODOC, PAI, MAE, IDCADASTRO, IPCADASTRO) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $params = [$matricula,$idpresobancodados,$nome,$datanascimento,$cidadenascimento,$ufnascimento,$nacionalidade,$regime,$provisorio,$reincidente,$rg,$cpf,$outrodoc,$pai,$mae,$idusuario,$ipcomputador];
            }else{
                $sql = "UPDATE cadastros SET IDPRESO = ?, NOME = ?, DATANASC = ?, IDCIDADENASC = ?, IDESTADONASC = ?, NACIONALIDADE = ?, REGIME = ?, PROVISORIO = ?, REINCIDENTE = ?, RG = ?, CPF = ?, OUTRODOC = ?, PAI = ?, MAE = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE MATRICULA = ?;";
                $params = [$idpresobancodados,$nome,$datanascimento,$cidadenascimento,$ufnascimento,$nacionalidade,$regime,$provisorio,$reincidente,$rg,$cpf,$outrodoc,$pai,$mae,$idusuario,$ipcomputador,$matricula];
            }

            $stmt = $GLOBALS['conexao']->prepare($sql);
            
            $stmt->execute($params);
            $resultado = $stmt->rowCount();
            
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
            }

            if($acao=='incluir'){
                
                //Depois de inserir a movimentação é alterado o status LANCADOCIMIC para bloquear atualizações de quem não possuir a permissão de alterar preso lançado.
                $sql = "UPDATE entradas_presos SET MATRICULA = ?, MATRICULAVINCULADA = TRUE, LANCADOCIMIC = TRUE, IDTIPOMOV = ?, IDMOTIVOMOV = ?, DATAPRISAO = ?, OBSERVACOES = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?";
                
                $params = [$matricula,$tipomovimentacao,$motivo,$dataprisao,$observacoes,$idusuario,$ipcomputador,$idpresobancodados];
                $stmt = $GLOBALS['conexao']->prepare($sql);

                $stmt->execute($params);
                $resultado = $stmt->rowCount();
                
                if($artigos!=0){
                    //Primeiro busca todos os artigos que já estão salvos para este preso
                    $sql = "SELECT ID FROM entradas_artigos WHERE IDPRESO = :idpreso AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL;"; 
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idpreso', $idpresobancodados, PDO::PARAM_INT);
                    $stmt->execute();
                    $listaArtigosExcluir = [];
                    while($artigosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($listaArtigosExcluir,$artigosexistente['ID']);
                    }
                    
                    foreach($artigos as $artigo){
                        $idbanco = $artigo['idbanco'];
                        $idartigo = $artigo['ARTIGO'];
                        $obsartigo = $artigo['obsartigo'];

                        if($idbanco==0){
                            $sql = "INSERT INTO entradas_artigos (IDPRESO, IDARTIGO, OBSERVACOES, DATACADASTRO, IDCADASTRO, IPCADASTRO) VALUES (?,?,?,?,?,?);";
                            
                            $params = [$idpresobancodados,$idartigo,$obsartigo,$dataagora,$idusuario,$ipcomputador];

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);
                            $resultado = $stmt->rowCount(); 
                            
                            if($resultado==0){
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O Artigo não foi inserido. Tente novamente mais tarde ou contate o programador. </li>");
                                echo json_encode($retorno);
                                exit();
                            }
                        }
                        else{
                            $sql = "UPDATE entradas_artigos SET OBSERVACOES = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?";
                            $params = [$obsartigo,$idusuario,$ipcomputador,$idbanco];

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);
                            $resultado = $stmt->rowCount(); 
                            
                            /*if($resultado==0){
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O Artigo não foi alterado. Tente novamente mais tarde ou contate o programador. </li>");
                                echo json_encode($retorno);
                                exit();
                            }*/

                            //Exclui da listagem de artigos existentes pois este artigo não foi excluído.
                            $posicao = array_search($idbanco,$listaArtigosExcluir);        
                            if($posicao!==false){
                                unset($listaArtigosExcluir[$posicao]);
                            }
                        }
                    }

                    //Exclui os artigos que foram exclusos da entrada do preso.
                    if(count($listaArtigosExcluir)){
                        foreach($listaArtigosExcluir as $idexcluir){
                            $sql = "UPDATE entradas_artigos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";
    
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }

                //Retorna o ID de movimentação para ser impresso
                $retorno = array('OK' => $idpresobancodados);
                echo json_encode($retorno);
                exit();

            }else{
                //Depois de alterar o cadastro do preso é alterado a movimentação de inclusão
                $sql = "UPDATE entradas_presos SET IDTIPOMOV = ?, IDMOTIVOMOV = ?, DATAPRISAO = ?, OBSERVACOES = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?";
                $params = [$tipomovimentacao,$motivo,$dataprisao,$observacoes,$idusuario,$ipcomputador,$idpresobancodados];

                $stmt = $GLOBALS['conexao']->prepare($sql);                
                $stmt->execute($params);
                $resultado = $stmt->rowCount();

                if($resultado!=1){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Dados da movimentação não foram alteradas. Tente novamente mais tarde ou contate o programador. </li>");
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
