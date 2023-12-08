<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes.php";

    $retorno=[];

    $tipo = $_POST['tipo'];
    $acao = isset($_POST['acao'])?$_POST['acao']:0;
    $confirmacao = isset($_POST['confirmacao'])?$_POST['confirmacao']:0;
    $idordem = isset($_POST['idordem'])?$_POST['idordem']:0;
    $idapres = isset($_POST['idapres'])?$_POST['idapres']:0;
    $datasaida = isset($_POST['datasaida'])?$_POST['datasaida']:'';
    $iddestinoordem = isset($_POST['iddestinoordem'])?$_POST['iddestinoordem']:0;
    $iddestinoapres = isset($_POST['iddestinoapres'])?$_POST['iddestinoapres']:0;
    $presos = isset($_POST['presos'])?$_POST['presos']:0;
    
    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    $dataAgora = date('Y-m-d H:i:s');

    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(9,37,38,39);
    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

    if($blnPermitido==false){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. </li>");
        echo json_encode($retorno);
        exit();
    }

    $conexaoStatus = conectarBD();    
    if($conexaoStatus===true){
        try {
            if(in_array($tipo,array(1,2))){
                $retorno = "";
                foreach($presos as $preso){
                    $idpreso = $preso['idpreso'];
                    $idmovimentacao = $preso['idmovimentacao'];
                    $horaapres = $preso['horaapres'];

                    //Verifica se o preso possui alguma transferência em aberto (sem estar realizado a saida e o retorno), se houver registro então emite um aviso, com informações da tranferência;
                    //Se data da apresentação estiver entre a data de saída e retorno, se emite o aviso. Se não possuir data de retorno, então se emite o aviso de todo jeito.
                    $consulta = consultarTransferenciasPreso($idpreso,0,"4,5,6");
                    //*****Usar confirmação acima de 1, pois o 1 é reservado apenas para avisos sem requerer OK ou Cancelar******;
                    if($consulta!=false && $confirmacao<1){
                        $mensagem = "";
                        $data = retornaDadosDataHora($datasaida,1)." ".$horaapres;
                        
                        // A verificação é realizada levando em consideração o horário também!
                        //Está verificando por dia e hora. Se precisar que verifique por dia então se altera o tipo
                        if($consulta['DATARETORNO']==null){
                            if(retornaDiferencaDeDataEHora($data,$consulta['DATASAIDA'],8)==1){
                                //Se a data da apresentação estiver entre as datas de Saída e retorno, então se emite o aviso. 
                                $mensagem = $consulta['MSGCONFIR'].$consulta['INFO'];
                            }
                        }else{
                            if(retornaDiferencaDeDataEHora($data,$consulta['DATASAIDA'],8)==1 && retornaDiferencaDeDataEHora($consulta['DATARETORNO'],$data,8)==1){
                                //Se a data da apresentação estiver entre as datas de Saída e retorno, então se emite o aviso. 
                                $mensagem = $consulta['MSGCONFIR'].$consulta['INFO'];
                            }
                        }
                        if($mensagem!=""){
                            if($retorno==""){
                                $retorno = $mensagem;
                            }else{
                                $retorno .= "\r********************************************************************\r\r$mensagem";
                            }
                        }
                    }
                }
                if($retorno!=""){
                    $retorno = array('MSGCONFIR'=>$retorno,'CONFIR'=>1);
                    echo json_encode($retorno);
                    exit();
                }
            }

            //Apresentações externas
            if($tipo==1){
                if($acao=='incluir'){
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(9,37);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");
    
                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para inserir Apresentações de Presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
    
                    $sql = "INSERT INTO cimic_ordens_apresentacoes (IDDESTINO, DATASAIDA,DATACADASTRO, IDCADASTRO, IPCADASTRO)
                    VALUES (:iddestinoordem, :datasaida, :dataAgora, :idusuario, :ipcomputador)";
            
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('iddestinoordem', $iddestinoordem, PDO::PARAM_INT);
                    $stmt->bindParam('datasaida', $datasaida, PDO::PARAM_STR);
                    $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                    $stmt->execute();
                    $resultado = $stmt->rowCount();
    
                    if($resultado==1){
                        $sql = "SELECT * FROM cimic_ordens_apresentacoes WHERE DATASAIDA = :datasaida AND DATACADASTRO = :dataAgora";
            
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('datasaida', $datasaida, PDO::PARAM_STR);
                        $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                        $stmt->execute();
                        $resultado = $stmt->fetchAll();    
                        
                        if(count($resultado)==1){
                            $idordem = $resultado[0]['ID'];
                        }else{
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível consultar a nova Ordem de Saída inserida. Tente novamente mais tarde ou contate o programador. </li>");
                            echo json_encode($retorno);
                            exit();
                        }
                    }else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> A Ordem de Saída não foi inserida. Tente novamente mais tarde ou contate o programador. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
    
                }else{
                    $permissoesNecessarias = array(9,38);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");
    
                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para alterar Apresentações de Presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
    
                    //Verifica se ja foi realizado a apresentação. Se houver, não será possível alterar a Ordem de Saída.
                    $sql = "SELECT CA.ID FROM cimic_apresentacoes CA
                    INNER JOIN cimic_ordens_apresentacoes COA ON COA.ID = CA.IDORDEMSAIDAMOV
                    WHERE COA.ID = :idordem AND CA.IDEXCLUSOREGISTRO IS NULL AND CA.DATAEXCLUSOREGISTRO IS NULL AND CA.REALIZADOSAIDA = TRUE;"; 
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idordem', $idordem, PDO::PARAM_INT);
                    $stmt->execute();
                    $resultado = $stmt->rowCount();
    
                    if($resultado==0){
                        $sql = "UPDATE cimic_ordens_apresentacoes SET IDDESTINO = :iddestinoordem, DATASAIDA = :datasaida, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idordem";
            
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('iddestinoordem', $iddestinoordem, PDO::PARAM_INT);
                        $stmt->bindParam('datasaida', $datasaida, PDO::PARAM_STR);
                        $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                        $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                        $stmt->bindParam('idordem', $idordem, PDO::PARAM_INT);
                        $stmt->execute();
                        $resultado = $stmt->rowCount();    
                    }
                }
    
                if($idordem!=0){
                    //Primeiro busca todos os presos que já estão salvos com essa Ordem de Saída
                    $sql = "SELECT ID FROM cimic_apresentacoes WHERE IDORDEMSAIDAMOV = :idordem AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL AND REALIZADOSAIDA = FALSE;"; 
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idordem', $idordem, PDO::PARAM_INT);
                    $stmt->execute();
    
                    $listaExcluir = [];
                    while($movimentacoesexistentes = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($listaExcluir,$movimentacoesexistentes['ID']);
                    }
    
                    foreach($presos as $preso){
            
                        $idpreso = $preso['idpreso'];
                        $idmovimentacao = isset($preso['idmovimentacao'])?$preso['idmovimentacao']:0;
                        $idmotivoapres = $preso['idmotivoapres'];
                        $horaapres = $preso['horaapres'];
    
                        if($idmovimentacao==0){
                            $sql = "INSERT INTO cimic_apresentacoes (IDPRESO, IDORDEMSAIDAMOV, HORAAPRES, IDMOTIVOAPRES, DATACADASTRO, IDCADASTRO, IPCADASTRO) VALUES (:idpreso, :idordem, :horaapres, :idmotivoapres, :dataAgora, :idusuario, :ipcomputador);";
    
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                            $stmt->bindParam('idordem', $idordem, PDO::PARAM_INT);
                            $stmt->bindParam('horaapres', $horaapres, PDO::PARAM_STR);
                            $stmt->bindParam('idmotivoapres', $idmotivoapres, PDO::PARAM_INT);
                            $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->execute();
                            $resultado = $stmt->rowCount(); 
                            
                            if($resultado==0){
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> A Apresentação não foi inserida. Tente novamente mais tarde ou contate o programador. </li>");
                                echo json_encode($retorno);
                                exit();
                            }
                        }
                        else{
                            $sql = "UPDATE cimic_apresentacoes SET HORAAPRES = :horaapres, IDMOTIVOAPRES = :idmotivoapres, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idmovimentacao";
    
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                            $stmt->bindParam('horaapres', $horaapres, PDO::PARAM_STR);
                            $stmt->bindParam('idmotivoapres', $idmotivoapres, PDO::PARAM_INT);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->execute();
                            $resultado = $stmt->rowCount(); 
                            
                            //Exclui da listagem de apresentacoes existentes pois este apresentacao não foi excluído.
                            $posicao = array_search($idmovimentacao,$listaExcluir);
                            if($posicao!==false){
                                unset($listaExcluir[$posicao]);
                            }
                        }
                    }
    
                    //Exclui os presos que foram exclusos da entrada.
                    if(count($listaExcluir)){
                        $permissoesNecessarias = array(9,39);
                        $blnPermitido = verificaPermissao($permissoesNecessarias,"");
        
                        if($blnPermitido==false){
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir Apresentações de Presos. </li>");
                            echo json_encode($retorno);
                            exit();
                        }
                            
                        foreach($listaExcluir as $idexcluir){
                            $sql = "UPDATE cimic_apresentacoes SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";
    
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
    
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível consultar a Ordem de Saída inserida. Tente novamente mais tarde ou contate o programador. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Apresentações internas
            elseif($tipo==2){
                //Atribuído true quando já existir apresentações internas para este local, então não se cria outra, somente junta as apresentações. Se for falso, será realizado a pesquisa para excluir os presos que não estão sendo alterados.
                $blnLocalExistente = false;

                if($acao=='incluir'){
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(9,37);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");
    
                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para inserir Apresentações de Presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                    
                    //Verifica se já existe uma apresentação para este local na data informada. Se houver é retornado este id para inserir os novos presos adicionados

                    $sql = "SELECT * FROM cimic_apresentacoes_internas WHERE IDDESTINO = :iddestinoapres AND DATASAIDA = :datasaida;";
            
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('iddestinoapres', $iddestinoapres, PDO::PARAM_INT);
                    $stmt->bindParam('datasaida', $datasaida, PDO::PARAM_STR);
                    $stmt->execute();

                    $resultado = $stmt->fetchAll();

                    if(count($resultado)){
                        $idapres = $resultado[0]['ID'];
                        $blnLocalExistente = true;
                    }else{
                        $sql = "INSERT INTO cimic_apresentacoes_internas (IDDESTINO, DATASAIDA,DATACADASTRO, IDCADASTRO, IPCADASTRO)
                        VALUES (:iddestinoapres, :datasaida, :dataAgora, :idusuario, :ipcomputador)";
                
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('iddestinoapres', $iddestinoapres, PDO::PARAM_INT);
                        $stmt->bindParam('datasaida', $datasaida, PDO::PARAM_STR);
                        $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                        $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                        $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                        $stmt->execute();
                        $resultado = $stmt->rowCount();
        
                        if($resultado==1){
                            $sql = "SELECT * FROM cimic_apresentacoes_internas WHERE DATASAIDA = :datasaida AND DATACADASTRO = :dataAgora";
                
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('datasaida', $datasaida, PDO::PARAM_STR);
                            $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                            $stmt->execute();
                            $resultado = $stmt->fetchAll();    
                            
                            if(count($resultado)==1){
                                $idapres = $resultado[0]['ID'];
                            }else{
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível consultar a nova Apresentação Interna. Tente novamente mais tarde ou contate o programador. </li>");
                                echo json_encode($retorno);
                                exit();
                            }
        
                        }else{
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> A Apresentação Interna não foi inserida. Tente novamente mais tarde ou contate o programador. </li>");
                            echo json_encode($retorno);
                            exit();
                        }
                    }
                }else{
                    $permissoesNecessarias = array(9,38);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");
    
                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para alterar Apresentações de Presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
    
                    //Verifica se ja foi realizado a apresentação. Se houver, não será possível alterar a Apresentacao Interna .
                    $sql = "SELECT CAIP.ID FROM cimic_apresentacoes_internas_presos CAIP
                    INNER JOIN cimic_apresentacoes_internas CAI ON CAI.ID = CAIP.IDAPRES
                    WHERE CAI.ID = :idapres AND CAIP.IDEXCLUSOREGISTRO IS NULL AND CAIP.DATAEXCLUSOREGISTRO IS NULL AND CAIP.REALIZADOSAIDA = TRUE;"; 
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idapres', $idapres, PDO::PARAM_INT);
                    $stmt->execute();
                    $resultado = $stmt->rowCount();
    
                    if($resultado==0){

                        $sql = "UPDATE cimic_apresentacoes_internas SET IDDESTINO = :iddestinoapres, DATASAIDA = :datasaida, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idapres";
            
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('iddestinoapres', $iddestinoapres, PDO::PARAM_INT);
                        $stmt->bindParam('datasaida', $datasaida, PDO::PARAM_STR);
                        $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                        $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                        $stmt->bindParam('idapres', $idapres, PDO::PARAM_INT);
                        $stmt->execute();
                        $resultado = $stmt->rowCount();    
                    }
                }
    
                //echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ". __LINE__." </li>"));exit();
                if($idapres!=0){
                    
                    $listaExcluir = [];
                    if($blnLocalExistente==false){
                        //Primeiro busca todos os presos que já estão salvos com essa Ordem de Saída
                        $sql = "SELECT ID FROM cimic_apresentacoes_internas_presos WHERE IDAPRES = :idapres AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL AND REALIZADOSAIDA = FALSE;"; 
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idapres', $idapres, PDO::PARAM_INT);
                        $stmt->execute();
        
                        while($movimentacoesexistentes = $stmt->fetch(PDO::FETCH_ASSOC)){
                            array_push($listaExcluir,$movimentacoesexistentes['ID']);
                        }
                    }
    
                    foreach($presos as $preso){
            
                        $idpreso = $preso['idpreso'];
                        $idmovimentacao = isset($preso['idmovimentacao'])?$preso['idmovimentacao']:0;
                        $idmotivoapres = $preso['idmotivoapres'];
                        $horaapres = $preso['horaapres'];
    
                        if($idmovimentacao==0){
                            $sql = "INSERT INTO cimic_apresentacoes_internas_presos (IDPRESO, IDAPRES, HORAAPRES, IDMOTIVOAPRES, DATACADASTRO, IDCADASTRO, IPCADASTRO) VALUES (:idpreso, :idapres, :horaapres, :idmotivoapres, :dataAgora, :idusuario, :ipcomputador);";
    
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                            $stmt->bindParam('idapres', $idapres, PDO::PARAM_INT);
                            $stmt->bindParam('horaapres', $horaapres, PDO::PARAM_STR);
                            $stmt->bindParam('idmotivoapres', $idmotivoapres, PDO::PARAM_INT);
                            $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->execute();
                            $resultado = $stmt->rowCount(); 
                            
                            if($resultado==0){
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> A Apresentação não foi inserida. Tente novamente mais tarde ou contate o programador. </li>");
                                echo json_encode($retorno);
                                exit();
                            }
                        }
                        else{
                            $sql = "UPDATE cimic_apresentacoes_internas_presos SET HORAAPRES = :horaapres, IDMOTIVOAPRES = :idmotivoapres, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idmovimentacao";
    
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                            $stmt->bindParam('horaapres', $horaapres, PDO::PARAM_STR);
                            $stmt->bindParam('idmotivoapres', $idmotivoapres, PDO::PARAM_INT);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->execute();
                            $resultado = $stmt->rowCount(); 
                            
                            //Exclui da listagem de apresentacoes existentes pois este apresentacao não foi excluído.
                            $posicao = array_search($idmovimentacao,$listaExcluir);
                            if($posicao!==false){
                                unset($listaExcluir[$posicao]);
                            }
                        }
                    }
    
                    //Exclui os presos que foram exclusos da entrada.
                    if(count($listaExcluir)){
                        $permissoesNecessarias = array(9,39);
                        $blnPermitido = verificaPermissao($permissoesNecessarias,"");
        
                        if($blnPermitido==false){
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir Apresentações de Presos. </li>");
                            echo json_encode($retorno);
                            exit();
                        }
                            
                        foreach($listaExcluir as $idexcluir){
                            $sql = "UPDATE cimic_apresentacoes_internas_presos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";
    
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
    
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível consultar a Apresentação Interna inserida. Tente novamente mais tarde ou contate o programador. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Exclui Apresentações externas
            elseif($tipo==3 && $idordem!=0){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(9,39);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir Apresentações de Presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $sql = "UPDATE cimic_ordens_apresentacoes SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idordem;";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('idordem', $idordem, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->rowCount();
                
                if($resultado>0){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Apresentação Externa excluída com sucesso! </li>");
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível excluir a Apresentação Externa! </li>");
                }
                echo json_encode($retorno);
                exit();    
            }
            //Exclui Apresentações internas
            elseif($tipo==4 && $idapres!=0){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(9,39);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir Transferências de Presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $sql = "UPDATE cimic_apresentacoes_internas SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idapres;";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('idapres', $idapres, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->rowCount();
                
                if($resultado>0){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Apresentação Interna excluída com sucesso! </li>");
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível excluir a Apresentação Interna! </li>");
                }
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

    $retorno = array('IDAPRES' => intval($idapres), 'IDORDEM' => intval($idordem));
    echo json_encode($retorno);
    exit();

    //echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha". __LINE__." </li>"));exit();

