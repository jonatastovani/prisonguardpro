<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes.php";

    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(10,12);
    $blnPermitido = verificaPermissao($permissoesNecessarias);

    if($blnPermitido==false){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. </li>");
        echo json_encode($retorno);
        exit();
    }
    
    //Se for pertencente a permissão de alterar Qualificativa do setor CIMIC ou Diretor da Inclusão então se libera mais campos para ser editado
    $permissoesNecessarias = array(9,12);
    $blnCIMIC = verificaPermissao($permissoesNecessarias);
    $permissoesNecessarias = array(3);
    $blnDirInclusao = verificaPermissao($permissoesNecessarias);
    
    $dataagora = date('Y-m-d H:i:s');
    $idpreso = $_POST['idpreso'];
    $nome = isset($_POST['nome'])?$_POST['nome']:0;
    $matricula = isset($_POST['matricula'])?$_POST['matricula']:0;
    $rg = isset($_POST['rg'])?$_POST['rg']:0;
    $cpf = isset($_POST['cpf'])?$_POST['cpf']:0;
    $outrodoc = isset($_POST['outrodoc'])?$_POST['outrodoc']:0;
    $pai = isset($_POST['pai'])?$_POST['pai']:'';
    $mae = isset($_POST['mae'])?$_POST['mae']:'';
    
    $nacionalidade = isset($_POST['nacionalidade'])?$_POST['nacionalidade']:0;
    if($nacionalidade!=1){
        $ufnasc = 0;
        $cidadenasc = 0;
    }else{
        $ufnasc = isset($_POST['ufnasc'])?$_POST['ufnasc']:0;
        $cidadenasc = isset($_POST['cidadenasc'])?$_POST['cidadenasc']:0;
    }

    $datanasc = isset($_POST['datanasc'])?$_POST['datanasc']:0;

    $cutis = isset($_POST['cutis'])?$_POST['cutis']:0;
    $tipocabelo = isset($_POST['tipocabelo'])?$_POST['tipocabelo']:0;
    $corcabelo = isset($_POST['corcabelo'])?$_POST['corcabelo']:0;
    $olhos = isset($_POST['olhos'])?$_POST['olhos']:0;
    $estatura = isset($_POST['estatura'])?$_POST['estatura']:0;
    $peso = isset($_POST['peso'])?$_POST['peso']:0;
    $profissao = isset($_POST['profissao'])?$_POST['profissao']:0;
    $escolaridade = isset($_POST['escolaridade'])?$_POST['escolaridade']:0;
    $estcivil = isset($_POST['estcivil'])?$_POST['estcivil']:0;
    $religiao = isset($_POST['religiao'])?$_POST['religiao']:0;
    $logradouro = isset($_POST['logradouro'])?$_POST['logradouro']:'';
    $numero = isset($_POST['numero'])?$_POST['numero']:'';
    $complemento = isset($_POST['complemento'])?$_POST['complemento']:'';
    $bairro = isset($_POST['bairro'])?$_POST['bairro']:'';
    $ufmorad = isset($_POST['ufmorad'])?$_POST['ufmorad']:0;
    $cidademorad = isset($_POST['cidademorad'])?$_POST['cidademorad']:0;
    $telefones = isset($_POST['telefones'])?$_POST['telefones']:0;
    $vulgos = isset($_POST['vulgos'])?$_POST['vulgos']:0;
    $sinais = isset($_POST['sinais'])?$_POST['sinais']:0;
    $artigos = isset($_POST['artigos'])?$_POST['artigos']:0;
    $anos = isset($_POST['anos'])?$_POST['anos']:0;
    $meses = isset($_POST['meses'])?$_POST['meses']:0;
    $dias = isset($_POST['dias'])?$_POST['dias']:0;
    $observacoes = isset($_POST['observacoes'])?$_POST['observacoes']:0;

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {

            //Update comum, sem permissão de CIMIC
            $sql = "UPDATE cadastros SET RG = :rg, CPF = :cpf, OUTRODOC = :outrodoc, CUTIS = :cutis, TIPOCABELO = :tipocabelo, CORCABELO = :corcabelo, OLHOS = :olhos, ESTATURA = :estatura, PESO = :peso, PROFISSAO = :profissao, INSTRUCAO = :escolaridade, ESTADOCIVIL = :estcivil, RELIGIAO = :religiao, ENDERECO = :logradouro, NUMERO = :numero, BAIRRO = :bairro, COMPLEMENTO = :complemento, IDCIDADEMORADIA = :cidademorad, IDESTADOMORADIA = :ufmorad, SINAIS = :sinais, OBSERVACOES = :observacoes, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE MATRICULA = :matricula";
            
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('rg', $rg, PDO::PARAM_STR);
            $stmt->bindParam('cpf', $cpf, PDO::PARAM_STR);
            $stmt->bindParam('outrodoc', $outrodoc, PDO::PARAM_STR);
            $stmt->bindParam('cutis', $cutis, PDO::PARAM_INT);
            $stmt->bindParam('tipocabelo', $tipocabelo, PDO::PARAM_INT);
            $stmt->bindParam('corcabelo', $corcabelo, PDO::PARAM_INT);
            $stmt->bindParam('olhos', $olhos, PDO::PARAM_INT);
            $stmt->bindParam('estatura', $estatura, PDO::PARAM_STR);
            $stmt->bindParam('peso', $peso, PDO::PARAM_INT);
            $stmt->bindParam('profissao', $profissao, PDO::PARAM_INT);
            $stmt->bindParam('escolaridade', $escolaridade, PDO::PARAM_INT);
            $stmt->bindParam('estcivil', $estcivil, PDO::PARAM_INT);
            $stmt->bindParam('religiao', $religiao, PDO::PARAM_INT);
            $stmt->bindParam('logradouro', $logradouro, PDO::PARAM_STR);
            $stmt->bindParam('numero', $numero, PDO::PARAM_STR);
            $stmt->bindParam('bairro', $bairro, PDO::PARAM_STR);
            $stmt->bindParam('complemento', $complemento, PDO::PARAM_STR);
            $stmt->bindParam('cidademorad', $cidademorad, PDO::PARAM_INT);
            $stmt->bindParam('ufmorad', $ufmorad, PDO::PARAM_INT);
            $stmt->bindParam('sinais', $sinais, PDO::PARAM_STR);
            $stmt->bindParam('observacoes', $observacoes, PDO::PARAM_STR);
            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
            $stmt->bindParam('matricula', $matricula, PDO::PARAM_INT);
            $stmt->execute();
            
            //Primeiro busca todos os artigos que já estão salvos para este preso
            $sql = "SELECT ID FROM cadastros_telefones WHERE IDPRESO = :idpreso AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL;"; 
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
            $stmt->execute();
            $listaTelefonesExcluir = [];
            while($artigosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($listaTelefonesExcluir,$artigosexistente['ID']);
            }

            if($telefones!=0){
                foreach($telefones as $telefone){
                    $idbanco = $telefone['idbanco'];
                    $nomecontato = $telefone['nomecontato'];
                    $numerocontato = $telefone['numerocontato'];

                    if($idbanco==0){
                        $sql = "INSERT INTO cadastros_telefones (IDPRESO, NOMECONTATO, NUMERO, IDCADASTRO, IPCADASTRO) VALUES (:idpreso, :nomecontato, :numerocontato, :idusuario, :ipcomputador);";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                        $stmt->bindParam('nomecontato', $nomecontato, PDO::PARAM_STR);
                        $stmt->bindParam('numerocontato', $numerocontato, PDO::PARAM_STR);
                        $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                        $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                        $stmt->execute();
                        $resultado = $stmt->rowCount(); 
                        
                        if($resultado==0){
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O Telefone não foi inserido. Tente novamente mais tarde ou contate o programador. </li>");
                            echo json_encode($retorno);
                            exit();
                        }
                    }
                    else{
                        //Exclui da listagem de artigos existentes pois este artigo não foi excluído.
                        $posicao = array_search($idbanco,$listaTelefonesExcluir);        
                        if($posicao!==false){
                            unset($listaTelefonesExcluir[$posicao]);
                        }
                    }
                }
            }
            //Exclui os telefones que foram exclusos da entrada do preso.
            if(count($listaTelefonesExcluir)){
                foreach($listaTelefonesExcluir as $idexcluir){
                    $sql = "UPDATE cadastros_telefones SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                    $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

            //Primeiro busca todos os artigos que já estão salvos para este preso
            $sql = "SELECT ID FROM cadastros_vulgos WHERE IDPRESO = :idpreso AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL;"; 
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
            $stmt->execute();
            $listaVulgosExcluir = [];
            while($artigosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($listaVulgosExcluir,$artigosexistente['ID']);
            }     
            
            if($vulgos!=0){
                foreach($vulgos as $vulgo){
                    $idbanco = $vulgo['idbanco'];
                    $vulgo = $vulgo['vulgo'];

                    if($idbanco==0){
                        $sql = "INSERT INTO cadastros_vulgos (IDPRESO, NOME, IDCADASTRO, IPCADASTRO) VALUES (:idpreso, :vulgo, :idusuario, :ipcomputador);";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                        $stmt->bindParam('vulgo', $vulgo, PDO::PARAM_STR);
                        $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                        $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                        $stmt->execute();
                        $resultado = $stmt->rowCount(); 
                        
                        if($resultado==0){
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O Vulgo não foi inserido. Tente novamente mais tarde ou contate o programador. </li>");
                            echo json_encode($retorno);
                            exit();
                        }
                    }else{
                        //Exclui da listagem de artigos existentes pois este artigo não foi excluído.
                        $posicao = array_search($idbanco,$listaVulgosExcluir);        
                        if($posicao!==false){
                            unset($listaVulgosExcluir[$posicao]);
                        }
                    }
                }
            }

            //Exclui os telefones que foram exclusos da entrada do preso.
            if(count($listaVulgosExcluir)){
                foreach($listaVulgosExcluir as $idexcluir){
                    $sql = "UPDATE cadastros_vulgos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                    $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

            if($blnCIMIC===true || $blnDirInclusao===true){
                $sql = "UPDATE cadastros SET NOME = :nome, PAI = :pai, MAE = :mae, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE MATRICULA = :matricula";             

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('nome', $nome, PDO::PARAM_STR);
                $stmt->bindParam('pai', $pai, PDO::PARAM_STR);
                $stmt->bindParam('mae', $mae, PDO::PARAM_STR);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('matricula', $matricula, PDO::PARAM_INT);
                $stmt->execute();
            }

            if($blnCIMIC===true){
                $sql = "UPDATE cadastros SET DATANASC = :datanasc, IDCIDADENASC = :cidadenasc, IDESTADONASC = :ufnasc, NACIONALIDADE = :nacionalidade, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE MATRICULA = :matricula";             

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('datanasc', $datanasc, PDO::PARAM_STR);
                $stmt->bindParam('cidadenasc', $cidadenasc, PDO::PARAM_INT);
                $stmt->bindParam('ufnasc', $ufnasc, PDO::PARAM_INT);
                $stmt->bindParam('nacionalidade', $nacionalidade, PDO::PARAM_INT);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('matricula', $matricula, PDO::PARAM_INT);
                $stmt->execute();

                $sql = "UPDATE cadastros_condenacao SET ANOS = :anos, MESES = :meses, DIAS = :dias, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE IDPRESO = :idpreso";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('anos', $anos, PDO::PARAM_INT);
                $stmt->bindParam('meses', $meses, PDO::PARAM_INT);
                $stmt->bindParam('dias', $dias, PDO::PARAM_INT);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                $stmt->execute();
                
                //Primeiro busca todos os artigos que já estão salvos para este preso
                $sql = "SELECT ID FROM entradas_artigos WHERE IDPRESO = :idpreso AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL;"; 
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                $stmt->execute();
                $listaArtigosExcluir = [];
                while($artigosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                    array_push($listaArtigosExcluir,$artigosexistente['ID']);
                }        
                
                if($artigos!=0){
                    foreach($artigos as $artigo){
                        $idbanco = $artigo['idbanco'];
                        $idartigo = $artigo['ARTIGO'];
                        $obsartigo = $artigo['obsartigo'];

                        if($idbanco==0){
                            $sql = "INSERT INTO entradas_artigos (IDPRESO, IDARTIGO, OBSERVACOES, DATACADASTRO, IDCADASTRO, IPCADASTRO) VALUES (:idpreso, :idartigo,  :obsartigo, :dataagora, :idusuario, :ipcomputador);";

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                            $stmt->bindParam('idartigo', $idartigo, PDO::PARAM_INT);
                            $stmt->bindParam('obsartigo', $obsartigo, PDO::PARAM_STR);
                            $stmt->bindParam('dataagora', $dataagora, PDO::PARAM_STR);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->execute();
                            $resultado = $stmt->rowCount(); 
                            
                            if($resultado==0){
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O Artigo não foi inserido. Tente novamente mais tarde ou contate o programador. </li>");
                                echo json_encode($retorno);
                                exit();
                            }
                        }
                        else{
                            $sql = "UPDATE entradas_artigos SET OBSERVACOES = :obsartigo, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idbanco";

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('obsartigo', $obsartigo, PDO::PARAM_STR);
                            $stmt->bindParam('idbanco', $idbanco, PDO::PARAM_INT);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->execute();
                            $resultado = $stmt->rowCount(); 
                            
                            //Exclui da listagem de artigos existentes pois este artigo não foi excluído.
                            $posicao = array_search($idbanco,$listaArtigosExcluir);        
                            if($posicao!==false){
                                unset($listaArtigosExcluir[$posicao]);
                            }
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
    
    //unset($GLOBALS['conexao']);

    $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados salvos com sucesso! </li>");
    echo json_encode($retorno);
