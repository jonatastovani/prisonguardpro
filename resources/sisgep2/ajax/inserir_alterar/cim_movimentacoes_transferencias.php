<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes.php";

    $retorno=[];

    $acao = isset($_POST['acao'])?$_POST['acao']:'';
    $confirmacao = isset($_POST['confirmacao'])?$_POST['confirmacao']:0;
    $idordem = isset($_POST['idordem'])?$_POST['idordem']:0;
    $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;
    $datasaida = isset($_POST['datasaida'])?$_POST['datasaida']:'';
    $iddestinoordem = isset($_POST['iddestinoordem'])?$_POST['iddestinoordem']:0;
    $presos = isset($_POST['presos'])?$_POST['presos']:'';
    $tipo = isset($_POST['tipo'])?$_POST['tipo']:'';

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    $dataAgora = date('Y-m-d H:i:s');
    
    //Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = array(9,43,44,45);
    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

    if($blnPermitido==false){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. </li>");
        echo json_encode($retorno);
        exit();
    }

    $conexaoStatus = conectarBD();    
    if($conexaoStatus===true){
        try {
            //Incluir/Alterar dados das transferências
            if($tipo==1){
                $retorno = "";
                foreach($presos as $preso){
                    $idpreso = $preso['idpreso'];
                    $idmovimentacao = $preso['idmovimentacao'];
                    $dataretorno = $preso['dataretorno'];
                    if($dataretorno==''){
                        $dataretorno=0;
                    }

                    // echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." Mov $idmovimentacao, saída $datasaida, retorno $dataretorno </li>"));exit();

                    //Verifica se o preso possui alguma apresentação em aberto (sem estar realizado a saida), se houver registro então emite um aviso, com informações da apresentação;
                    //Se data da apresentação estiver entre a data de saída e retorno, se emite o aviso. Se não possuir data de retorno, então se emite o aviso de todo jeito.
                    $consulta = consultarApresentacoesPreso($idpreso,$datasaida,$dataretorno);
                    //*****Usar confirmação acima de 1, pois o 1 é reservado apenas para avisos sem requerer OK ou Cancelar******;
                    if($consulta!=false && $confirmacao<1){
                        $mensagem = $consulta['MSGCONFIR'];
                        
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

                foreach($presos as $preso){
                    $idpreso = $preso['idpreso'];
                    $idmovimentacao = $preso['idmovimentacao'];

                    //Verifica se o preso possui alguma transferência em aberto (sem estar realizado a saida e o retorno), se houver registro então não se permite inserir uma nova transferência;
                    $consulta = consultarTransferenciasPreso($idpreso,$datasaida,"4,5,6");
                    //*****Usar confirmação acima de 1, pois o 1 é reservado apenas para avisos sem requerer OK ou Cancelar******;
                    if($consulta!=false && $confirmacao<3){
                        $mensagem = "";
                        if($consulta['IDORDEM']!=$idordem){
                            $mensagem = $consulta['MSGCONFIR'];
                            //Se for diferente do idordem que está sendo editado então não se pode inserir a transferência porque já há uma em aberto. Primeiramente deve se excluir a que está em aberto.
                            $mensagem .= "\rA transferência mencionada deve ser excluída para poder inserir esta nova trânsferência.".$consulta['INFO'];
                        }elseif($consulta['IDMOV']!=$idmovimentacao){
                            //Atualiza-se o número do idmovimentacao, pois provavelmente o usuário excluiu a transferência do preso e está inserindo-o novamente na mesma alteração de dados. Então se atribui o idmovimentação encontrado.
                            for($i=0;$i<count($presos);$i++){
                                if($presos[$i]['idpreso']==$idpreso){
                                    $presos[$i]['idmovimentacao'] = $consulta['IDMOV'];
                                }
                            }
                        }
                        
                        if($mensagem!=""){
                            if($retorno==""){
                                $retorno = $mensagem;
                            }else{
                                $retorno .= "\r\r$mensagem";
                            }
                        }
                    }
                }

                if($retorno!=""){
                    $retorno = array('MSGCONFIR'=>$retorno,'CONFIR'=>2);
                    echo json_encode($retorno);
                    exit();
                }

                if($acao=='incluir'){
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(9,43);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para incluir Transferências de Presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }

                    //Se for 'incluir' é porque é uma ordem nova, então se gera a ordem de saída primeiro
                    $sql = "INSERT INTO cimic_ordens_transferencias (IDDESTINO, DATASAIDA,DATACADASTRO, IDCADASTRO, IPCADASTRO)
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
                        $sql = "SELECT * FROM cimic_ordens_transferencias WHERE DATASAIDA = :datasaida AND DATACADASTRO = :dataAgora";
            
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

                }elseif($acao=='alterar'){
                    //Verifica se o usuário tem a permissão necessária
                    $permissoesNecessarias = array(9,44);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para alterar Transferências de Presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }

                    //Verifica se ja foi realizado a transferência. Se houver não será possível alterar a Ordem de Saída.
                    $sql = "SELECT CMT.ID FROM cimic_transferencias CMT
                    INNER JOIN cimic_ordens_transferencias CMO ON CMO.ID = CMT.IDORDEMSAIDAMOV
                    WHERE CMO.ID = :idordem AND CMT.IDEXCLUSOREGISTRO IS NULL AND CMT.DATAEXCLUSOREGISTRO IS NULL AND CMT.REALIZADOSAIDA = TRUE;"; 
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idordem', $idordem, PDO::PARAM_INT);
                    $stmt->execute();
                    $resultado = $stmt->rowCount();

                    if($resultado==0){
                        $sql = "UPDATE cimic_ordens_transferencias SET IDDESTINO = :iddestinoordem, DATASAIDA = :datasaida, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idordem";
            
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
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Ação não informada. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                if($idordem!=0){
                    //Primeiro busca todos os presos que já estão salvos com essa Ordem de Saída
                    $sql = "SELECT ID FROM cimic_transferencias WHERE IDORDEMSAIDAMOV = :idordem AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL AND REALIZADOSAIDA = FALSE;"; 
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
                        $idtipo = $preso['idtipo'];
                        $idmotivo = $preso['idmotivo'];
                        $dataretorno = $preso['dataretorno'];
                        $destinos = $preso['destinos'];
                        $apresentacoes = isset($preso['apresentacoes'])?$preso['apresentacoes']:0;

                        //Se idmovimentacao = 0 é porque está se inserindo a movimentação agora. Se for diferente é porque está alterando
                        if($idmovimentacao==0){
                            $sql = "INSERT INTO cimic_transferencias (IDPRESO, IDORDEMSAIDAMOV, IDTIPOMOV, IDMOTIVOMOV, DATARETORNO, DATACADASTRO, IDCADASTRO, IPCADASTRO) VALUES 
                            (:idpreso, :idordem, :idtipo, :idmotivo, :dataretorno, :dataAgora, :idusuario, :ipcomputador);";
                        }else{
                            //Verifica se a movimentacao pode ser alterada.
                            //Se caso já foi realizado a movimentação, então não se pode mais alterar as informações, a não ser a data de retorno.
                            $sql = "SELECT * FROM cimic_transferencias WHERE ID = :idmovimentacao AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL AND REALIZADOSAIDA = TRUE;"; 
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                            $stmt->execute();
                            $resultado = $stmt->rowCount();
                        
                            //Se encontrar o registro significa que já foi realizado a saida, então somente altera a data de retorno
                            if($resultado>0){
                                $sql = "UPDATE cimic_transferencias SET DATARETORNO = :dataretorno, DATAATUALIZACAO = :dataAgora, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idmovimentacao;";
                                
                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->bindParam('dataretorno', $dataretorno, PDO::PARAM_STR);
                                $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                                $stmt->execute();
                                
                                $posicao = array_search($idmovimentacao,$listaExcluir);        
                                if($posicao!==false){
                                    unset($listaExcluir[$posicao]);
                                }
                                continue;
                            }

                            $sql = "UPDATE cimic_transferencias SET IDTIPOMOV = :idtipo, IDMOTIVOMOV = :idmotivo, DATARETORNO = :dataretorno, DATAATUALIZACAO = :dataAgora, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idmovimentacao;";

                            //Exclui da listagem de presos existentes pois este preso está sendo alterado somente. No final se exclui todos os presos que não foram atualizados, pois na verdade eles foram excluídos.
                            $posicao = array_search($idmovimentacao,$listaExcluir);        
                            if($posicao!==false){
                                unset($listaExcluir[$posicao]);
                            }
                        }
                        
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        if($idmovimentacao==0){
                            $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                            $stmt->bindParam('idordem', $idordem, PDO::PARAM_INT);
                        }else{
                            $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                        }
                        $stmt->bindParam('idtipo', $idtipo, PDO::PARAM_INT);
                        $stmt->bindParam('idmotivo', $idmotivo, PDO::PARAM_INT);
                        $stmt->bindParam('dataretorno', $dataretorno, PDO::PARAM_STR);
                        $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                        $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                        $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                        $stmt->execute();
                        $resultado = $stmt->rowCount(); 
                        
                        //Somente busca o idmovimentacao caso estiver inseririndo cadastro novo. 
                        if($resultado==1 && $idmovimentacao==0){
                            $sql = "SELECT * FROM cimic_transferencias WHERE IDPRESO = :idpreso AND DATACADASTRO = :dataAgora AND IDORDEMSAIDAMOV = :idordem ORDER BY ID DESC";
                            
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_STR);
                            $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                            $stmt->bindParam('idordem', $idordem, PDO::PARAM_INT);
                            $stmt->execute();
                            $resultado = $stmt->fetchAll(); 

                            if(count($resultado)){
                                $idmovimentacao = $resultado[0]['ID'];
                            }else{
                                $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> A movimentação não foi encontrado para ser incluso o(s) Destino(s) Intermediário(s) da Rota e a(s) Apresentação(ões). Tente novamente mais tarde ou contate o programador. </li>");
                                echo json_encode($retorno);
                                exit();
                            }
                        }

                        if($idmovimentacao!=0 && $apresentacoes!=0){
                            //Primeiro busca todos as apresentacoes que já estão salvas para este preso
                            $sql = "SELECT ID FROM cimic_transferencias_apres WHERE IDMOVIMENTACAO = :idmovimentacao AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL;"; 
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                            $stmt->execute();

                            $listaApresentacoesExcluir = [];
                            while($apresentacoesexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                                array_push($listaApresentacoesExcluir,$apresentacoesexistente['ID']);
                            }

                            foreach($apresentacoes as $apresentacao){
                                $idbanco = $apresentacao['idbanco'];
                                $idlocalapres = $apresentacao['idlocalapres'];
                                $idmotivoapres = $apresentacao['idmotivoapres'];
                                $dataapres = $apresentacao['dataapres'];

                                if($idbanco==0){
                                    $sql = "INSERT INTO cimic_transferencias_apres (IDMOVIMENTACAO, IDDESTINOAPRES, DATAAPRES, IDMOTIVOAPRES, DATACADASTRO, IDCADASTRO, IPCADASTRO) VALUES (:idmovimentacao, :idlocalapres, :dataapres, :idmotivoapres, :dataAgora, :idusuario, :ipcomputador);";

                                    $stmt = $GLOBALS['conexao']->prepare($sql);
                                    $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                                    $stmt->bindParam('idlocalapres', $idlocalapres, PDO::PARAM_INT);
                                    $stmt->bindParam('dataapres', $dataapres, PDO::PARAM_STR);
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
                                    $sql = "UPDATE cimic_transferencias_apres SET DATAAPRES = :dataapres, IDMOTIVOAPRES = :idmotivoapres, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idbanco";
        
                                    $stmt = $GLOBALS['conexao']->prepare($sql);
                                    $stmt->bindParam('idbanco', $idbanco, PDO::PARAM_INT);
                                    $stmt->bindParam('dataapres', $dataapres, PDO::PARAM_STR);
                                    $stmt->bindParam('idmotivoapres', $idmotivoapres, PDO::PARAM_INT);
                                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                    $stmt->execute();
                                    $resultado = $stmt->rowCount(); 
                                    
                                    //Exclui da listagem de apresentacoes existentes pois este apresentacao não foi excluído.
                                    $posicao = array_search($idbanco,$listaApresentacoesExcluir);
                                    if($posicao!==false){
                                        unset($listaApresentacoesExcluir[$posicao]);
                                    }
                                }
                            }

                            //Exclui as apresentacoes que foram exclusas da movimentação.
                            if(count($listaApresentacoesExcluir)){
                                foreach($listaApresentacoesExcluir as $idexcluir){
                                    $sql = "UPDATE cimic_transferencias_apres SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";
            
                                    $stmt = $GLOBALS['conexao']->prepare($sql);
                                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                    $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                                    $stmt->execute();
                                }
                            }
            
                        }
            
                        if($idmovimentacao!=0 && $destinos!=0){
                            //Primeiro busca todos as destinos que já estão salvas para este preso
                            $sql = "SELECT ID FROM cimic_transferencias_intermed WHERE IDMOVIMENTACAO = :idmovimentacao AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL;"; 
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                            $stmt->execute();

                            $listaDestinosExcluir = [];
                            while($destinosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                                array_push($listaDestinosExcluir,$destinosexistente['ID']);
                            }

                            foreach($destinos as $destino){
                                $idbanco = $destino['idbanco'];
                                $iddestinointerm = $destino['iddestinointerm'];
                                $datainterm = $destino['datainterm'];
                                $comentario = $destino['comentario'];
                                $blnprimeirolocal = $destino['blnprimeirolocal'];
                                $blndestinofinal = $destino['blndestinofinal'];

                                if($blnprimeirolocal=='true'){
                                    $blnprimeirolocal=1;
                                }else{
                                    $blnprimeirolocal = 0;
                                }
                                if($blndestinofinal=='true'){
                                    $blndestinofinal=1;
                                }else{
                                    $blndestinofinal = 0;
                                }

                                if($idbanco==0){
                                    $sql = "INSERT INTO cimic_transferencias_intermed (IDMOVIMENTACAO, IDDESTINOINTERM, DATAINTERM, PRIMEIROLOCAL, DESTINOFINAL, COMENTARIO, DATACADASTRO, IDCADASTRO, IPCADASTRO) VALUES (:idmovimentacao, :iddestinointerm, :datainterm, :blnprimeirolocal, :blndestinofinal, :comentario, :dataAgora, :idusuario, :ipcomputador);";

                                    $stmt = $GLOBALS['conexao']->prepare($sql);
                                    $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                                    $stmt->bindParam('iddestinointerm', $iddestinointerm, PDO::PARAM_INT);
                                    $stmt->bindParam('datainterm', $datainterm, PDO::PARAM_STR);
                                    $stmt->bindParam('blnprimeirolocal', $blnprimeirolocal, PDO::PARAM_INT);
                                    $stmt->bindParam('blndestinofinal', $blndestinofinal, PDO::PARAM_INT);
                                    $stmt->bindParam('comentario', $comentario, PDO::PARAM_STR);
                                    $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                    $stmt->execute();
                                    $resultado = $stmt->rowCount(); 
                                    
                                    if($resultado==0){
                                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> O Destino não foi inserido. Tente novamente mais tarde ou contate o programador. </li>");
                                        echo json_encode($retorno);
                                        exit();
                                    }
                                }
                                else{
                                    $sql = "UPDATE cimic_transferencias_intermed SET DATAINTERM = :datainterm, PRIMEIROLOCAL = :blnprimeirolocal, DESTINOFINAL = :blndestinofinal, COMENTARIO = :comentario, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idbanco";
        
                                    $stmt = $GLOBALS['conexao']->prepare($sql);
                                    $stmt->bindParam('idbanco', $idbanco, PDO::PARAM_INT);
                                    $stmt->bindParam('datainterm', $datainterm, PDO::PARAM_STR);
                                    $stmt->bindParam('blnprimeirolocal', $blnprimeirolocal, PDO::PARAM_INT);
                                    $stmt->bindParam('blndestinofinal', $blndestinofinal, PDO::PARAM_INT);
                                    $stmt->bindParam('comentario', $comentario, PDO::PARAM_STR);
                                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                    $stmt->execute();
                                    $resultado = $stmt->rowCount(); 
                                    
                                    //Exclui da listagem de destinos existentes pois este destino não foi excluído.
                                    $posicao = array_search($idbanco,$listaDestinosExcluir);
                                    if($posicao!==false){
                                        unset($listaDestinosExcluir[$posicao]);
                                    }
                                }
                            }


                            //Exclui as destinos que foram exclusas da movimentação.
                            if(count($listaDestinosExcluir)){
                                foreach($listaDestinosExcluir as $idexcluir){
                                    $sql = "UPDATE cimic_transferencias_intermed SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";
            
                                    $stmt = $GLOBALS['conexao']->prepare($sql);
                                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                    $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                                    $stmt->execute();
                                }
                            }
                            //$retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> ERRO. </li>");echo json_encode($retorno);exit();
                        }
                    }

                    //Exclui os presos que foram exclusos da entrada.
                    if(count($listaExcluir)){
                        //Verifica se o usuário tem a permissão necessária
                        $permissoesNecessarias = array(9,45);
                        $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                        if($blnPermitido==false){
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir Transferências de Presos. </li>");
                            echo json_encode($retorno);
                            exit();
                        }

                        foreach($listaExcluir as $idexcluir){
                            $sql = "UPDATE cimic_transferencias SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";

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
            //Alterar dados do retorno da tabela cimic_transferencias
            elseif($tipo==2){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(9,44);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para alterar Transferências de Presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }
                
                $datamov = $_POST['datamov'];
                $idpreso = $_POST['idpreso'];
                $seguro = $_POST['seguro'];
                if($seguro=='true'){
                    $seguro=1;
                }else{
                    $seguro=0;
                }
                $idmovimentacao = $_POST['idmovimentacao'];

                $sql = "UPDATE cimic_transferencias SET DATARETORNO = :datamov, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idmovimentacao;";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('datamov', $datamov, PDO::PARAM_STR);
                $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->execute();

                $sql = "UPDATE entradas_presos SET SEGURO = :seguro, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idpreso;";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                $stmt->bindParam('seguro', $seguro, PDO::PARAM_INT);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->execute();

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados atualizados com sucesso! </li>");
                echo json_encode($retorno);
                exit();

            }
            //Alterar dados do recebimento da tabela cimic_recebimentos
            elseif($tipo==3){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(9,44);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para alterar Transferências de Presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $idpreso = $_POST['idpreso'];
                $datamov = $_POST['datamov'];
                $origem = $_POST['origem'];
                $tipomov = $_POST['tipomov'];
                $motivomov = $_POST['motivomov'];
                $seguro = $_POST['seguro'];
                if($seguro=='true'){
                    $seguro=1;
                }else{
                    $seguro=0;
                }
                $idmovimentacao = $_POST['idmovimentacao'];

                $sql = "UPDATE cimic_recebimentos SET DATARECEB = :datamov, IDPROCEDENCIA = :origem, IDTIPOMOV = :tipomov, IDMOTIVOMOV = :motivomov, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idmovimentacao;";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('datamov', $datamov, PDO::PARAM_STR);
                $stmt->bindParam('origem', $origem, PDO::PARAM_INT);
                $stmt->bindParam('tipomov', $tipomov, PDO::PARAM_INT);
                $stmt->bindParam('motivomov', $motivomov, PDO::PARAM_INT);
                $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->execute();

                $sql = "UPDATE entradas_presos SET SEGURO = :seguro, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idpreso;";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                $stmt->bindParam('seguro', $seguro, PDO::PARAM_INT);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->execute();

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados atualizados com sucesso! </li>");
                echo json_encode($retorno);
                exit();
            }
            //Insere retornos ou recebimento de presos pelo trânsito
            elseif($tipo==4){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(9,43,44);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para inserir ou alterar Transferências de Presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $idpreso = $_POST['idpreso'];
                $confirmacao = $_POST['confirmacao'];
                $tipomov = $_POST['tipomov'];
                $motivomov = $_POST['motivomov'];
                $origem = $_POST['origem'];
                $datamov = $_POST['datamov'];
                $idmovimentacao = $_POST['idmovimentacao'];
                $seguro = $_POST['seguro'];
                if($seguro=='true'){
                    $seguro=1;
                }else{
                    $seguro=0;
                }

                // Se a confirmação == 6 então não se alterará nenhuma das informações e retornará uma mensagem
                if(in_array($confirmacao,array(2,6))){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma informação foi alterada! </li>");
                    echo json_encode($retorno);
                    exit();
                }

                //Verificar se o preso tem retorno sem data ou um retorno definido.
                //Se houver retorno não é permitido inserir uma nova data de retorno, tem que esperar o preso voltar para ter uma nova saída e um novo retorno.

                //Verifica se o preso possui trânsito judicial em aberto (sem estar realizado a saida e o retorno), se houver registro então se manipula somente essa movimentação, inserindo ou alterando a data de retorno;
                $consulta = consultarTransferenciasPreso($idpreso,$datamov,'6',true);
                //*****Usar confirmação acima de 1, pois o 1 é reservado apenas para avisos sem requerer OK ou Cancelar******;
                if($consulta!=false && $confirmacao<3){
                    $mensagem = $consulta['MSGCONFIR'];
                    if($consulta['CONFIR']==3){
                        $mensagem .= "\rDeseja atualizar a data de retorno?".$consulta['INFO'];
                    }elseif($consulta['CONFIR']==4){
                        $mensagem .= "\rDeseja inserir esta data na movimentação existente?".$consulta['INFO'];
                    }else{
                        $mensagem .= $consulta['INFO'];
                    }
                    $retorno = array('MSGCONFIR'=>$mensagem,'CONFIR'=>$consulta['CONFIR'],'IDMOV'=>$consulta['IDMOV']);
                    echo json_encode($retorno);
                    exit();
                }

                //Verifica se o preso possui recebimento em aberto (sem estar realizado o recebimento), se houver registro então se manipula somente essa movimentação, inserindo ou alterando a data de retorno;
                $consulta = consultarRecebimentoPreso($idpreso,$datamov);
                //*****Usar confirmação acima de 1, pois o 1 é reservado apenas para avisos sem requerer OK ou Cancelar******;
                if($consulta!=false && $confirmacao<5){
                    $mensagem = $consulta['MSGCONFIR'];
                    if($consulta['CONFIR']==5){
                        $mensagem .= "\rDeseja atualizar os dados de recebimento?".$consulta['INFO'];
                    }else{
                        $mensagem .= $consulta['INFO'];
                    }
                    $retorno = array('MSGCONFIR'=>$mensagem,'CONFIR'=>$consulta['CONFIR'],'IDMOV'=>$consulta['IDMOV']);
                    echo json_encode($retorno);
                    exit();
                }

                if(in_array($confirmacao,array(3,4))){
                    $sql = "UPDATE cimic_transferencias SET DATARETORNO = :datamov, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idmovimentacao;";
            
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('datamov', $datamov, PDO::PARAM_STR);
                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                    $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                    $stmt->execute();

                }elseif($confirmacao==5){
                    $sql = "UPDATE cimic_recebimentos SET DATARECEB = :datamov, IDPROCEDENCIA = :origem, IDTIPOMOV = :tipomov, IDMOTIVOMOV = :motivomov, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idmovimentacao;";
            
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('datamov', $datamov, PDO::PARAM_STR);
                    $stmt->bindParam('origem', $origem, PDO::PARAM_INT);
                    $stmt->bindParam('tipomov', $tipomov, PDO::PARAM_INT);
                    $stmt->bindParam('motivomov', $motivomov, PDO::PARAM_INT);
                    $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                    $stmt->execute();
                }elseif($confirmacao!=6){
                    $sql = "INSERT INTO cimic_recebimentos (IDPRESO, DATARECEB, IDPROCEDENCIA, IDTIPOMOV, IDMOTIVOMOV, IDCADASTRO, IPCADASTRO) VALUES(:idpreso, :datamov, :origem, :tipomov, :motivomov, :idusuario, :ipcomputador);";
            
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                    $stmt->bindParam('datamov', $datamov, PDO::PARAM_STR);
                    $stmt->bindParam('origem', $origem, PDO::PARAM_INT);
                    $stmt->bindParam('tipomov', $tipomov, PDO::PARAM_INT);
                    $stmt->bindParam('motivomov', $motivomov, PDO::PARAM_INT);
                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                    $stmt->execute();
                }

                $sql = "UPDATE entradas_presos SET SEGURO = :seguro, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idpreso;";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                $stmt->bindParam('seguro', $seguro, PDO::PARAM_INT);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->execute();

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados inseridos / atualizados com sucesso! </li>");
                echo json_encode($retorno);
                exit();
            }
            //Exclui transferências
            elseif($tipo==5 && $idordem!=0){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(9,45);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir Transferências de Presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $sql = "UPDATE cimic_ordens_transferencias SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idordem;";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('idordem', $idordem, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->rowCount();
                
                if($resultado>0){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Ordem de Saída excluída com sucesso! </li>");
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível excluir a Ordem de Saída! </li>");
                }
                echo json_encode($retorno);
                exit();    
            }
            //Exclui recebimento da cimic_recebimentos
            elseif($tipo==6 && $idmovimentacao!=0){
                //Verifica se o usuário tem a permissão necessária
                $permissoesNecessarias = array(9,45);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para excluir Transferências de Presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $sql = "UPDATE cimic_recebimentos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idmovimentacao;";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->rowCount();
                
                if($resultado>0){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Recebimento de Preso excluído com sucesso! </li>");
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível excluir o Recebimento de Preso! </li>");
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
        exit();
    }
    
    //unset($GLOBALS['conexao']);

    $retorno = array('IDORDEM' => intval($idordem));
    echo json_encode($retorno);
    exit();

    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." </li>"));exit();
