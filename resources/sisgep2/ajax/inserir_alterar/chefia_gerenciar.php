<?php

use LDAP\Result;

    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    // include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes.php";

    $retorno=[];

    $tipo = $_POST['tipo'];
    $confirmacao = isset($_POST['confirmacao'])?$_POST['confirmacao']:0;
    $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    $idraio = isset($_POST['idraio'])?$_POST['idraio']:0;
    $cela = isset($_POST['cela'])?$_POST['cela']:0;
    $idsituacao = isset($_POST['idsituacao'])?$_POST['idsituacao']:4;
    $observacoes = isset($_POST['observacoes'])?$_POST['observacoes']:'';
    $idtipoatend = isset($_POST['idtipoatend'])?$_POST['idtipoatend']:0;
    $descpedido = isset($_POST['descpedido'])?$_POST['descpedido']:'';
    $presos = isset($_POST['presos'])?$_POST['presos']:0;
    $requisitante = isset($_POST['requisitante'])?$_POST['requisitante']:'';
    $dataatend = isset($_POST['dataatend'])?$_POST['dataatend']:'';
    $blnvisuchefia = isset($_POST['blnvisuchefia'])?$_POST['blnvisuchefia']:false;
    $idtipoproced = isset($_POST['idtipoproced'])?$_POST['idtipoproced']:0;
    $idtipocontagem = isset($_POST['idtipocontagem'])?$_POST['idtipocontagem']:0;
    $idcontagem = isset($_POST['idcontagem'])?$_POST['idcontagem']:0;
    $idfuncionario = isset($_POST['idfuncionario'])?$_POST['idfuncionario']:0;
    $iddiretor = isset($_POST['iddiretor'])?$_POST['iddiretor']:0;
    $tabela = isset($_POST['tabela'])?$_POST['tabela']:0;
    $horario = isset($_POST['horario'])?$_POST['horario']:0;
    $arrpresos = isset($_POST['arrpresos'])?$_POST['arrpresos']:0;

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    $dataAgora = date('Y-m-d H:i:s');

    $conexaoStatus = conectarBD();    
    if($conexaoStatus===true){
        try {
            //Inserir Mudança de Raio ou Cela
            if($tipo==1){

                verificaBloqueioMovimentacao();

                //Verifica se a cela destino não é a mesma que o preso já está
                $sql = "SELECT FUNCT_dados_raio_cela_preso(?, CURRENT_TIMESTAMP, 1) IDRAIOATUAL, FUNCT_dados_raio_cela_preso(?, CURRENT_TIMESTAMP, 3) CELAATUAL";
                $params = [$idpreso,$idpreso];

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    if($resultado[0]['IDRAIOATUAL']==$idraio && $resultado[0]['CELAATUAL']==$cela){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> O preso já se encontra na cela destino informada. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }
                
                if($idmovimentacao==0){

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

                    // PRIMEIRO VERIFICA-SE SE JÁ NÃO EXISTE UM PEDIDO DE MUDANÇA INSERIDO, SE HOUVER ENTÃO EXIBE UMA MENSAGEM CANCELANDO A INSERÇÃO;
                    $sql = "SELECT CMUD.*, CMUD.ID IDMOV, CD.NOME, CD.MATRICULA 
                    FROM chefia_mudancacela CMUD
                    INNER JOIN cadastros CD ON CD.IDPRESO = CMUD.IDPRESO
                    WHERE CMUD.IDPRESO = :idpreso AND CMUD.IDSITUACAO <> 6 AND 
                    CMUD.IDBOLETIM = (SELECT ID FROM chefia_boletim WHERE BOLETIMDODIA = TRUE);";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();
                    
                    if(count($resultado)){
                        $arraycancelados = [7,8,9];
                        $retorno = "O preso ".$resultado[0]['NOME']." possui uma mudança de cela em andamento, portanto não será possível inserir outra até que esta finalize.";

                        if($blnvisuchefia!=false){
                            $retorno .= "\rClique em OK para dar seguimento à mudança em andamento.";
                            $confirm = 2;
                        }else{
                            if(in_array($resultado[0]['IDSITUACAO'],$arraycancelados)){
                                $retorno .= "\rSolicite a chefia que altere a situação da mudança.";
                            }
                            $confirm = 1;
                        }

                        $retorno = array('MSGCONFIR'=>$retorno,'CONFIR'=>$confirm, 'IDMOV'=>$resultado[0]['IDMOV']);
                        echo json_encode($retorno);
                        exit();
                    }

                    $sql = "INSERT INTO chefia_mudancacela (IDPRESO, RAIODESTINO, CELADESTINO, IDSITUACAO, OBSERVACOES, IDCADASTRO, IPCADASTRO)
                    VALUES (?,?,?,?,?,?,?)";

                    $params = [$idpreso,$idraio,$cela,$idsituacao,$observacoes,$idusuario,$ipcomputador];

                }else{
                    
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
                    
                    $sql = "UPDATE chefia_mudancacela SET RAIODESTINO = ?, CELADESTINO = ?, IDSITUACAO = ?, OBSERVACOES = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                    $params = [$idraio,$cela,$idsituacao,$observacoes,$idusuario,$ipcomputador,$idmovimentacao];
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Solicitação de Mudança de Cela enviada com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            //Solicitação de Atendimento Enfermaria
            elseif($tipo==2){
                                
                if($idmovimentacao==0){
                    //Verifica se o usuário tem a permissão necessária
                    /*$permissoesNecessarias = array(9,37);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para inserir Apresentações de Presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }*/

                    $sql = "INSERT INTO enf_atendimentos (IDTIPOATEND, IDPRESO, DESCPEDIDO, IDCADASTRO, IPCADASTRO) VALUES (:idtipoatend, :idpreso, :descpedido, :idusuario, :ipcomputador)";
                }else{
                    $sql = "UPDATE enf_atendimentos SET IDTIPOATEND = :idtipoatend, DESCPEDIDO = :descpedido, IDSITUACAO = DEFAULT, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idmovimentacao;";             
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                if($idmovimentacao==0){
                    $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                }else{
                    $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                }
                $stmt->bindParam('idtipoatend', $idtipoatend, PDO::PARAM_INT);
                $stmt->bindParam('descpedido', $descpedido, PDO::PARAM_STR);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->execute();
                $resultado = $stmt->rowCount();

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Solicitação de Atendimento enviada com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            //Inserir Atendimentos Gerais
            elseif($tipo==3){

                if($idmovimentacao==0){
                    //Verifica se o usuário tem a permissão necessária
                    /*$permissoesNecessarias = array(9,37);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para inserir Apresentações de Presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }*/

                    $sql = "INSERT INTO chefia_atendimentos_requis (IDTIPOATEND, REQUISITANTE, DATAATEND, IDCADASTRO, IPCADASTRO, DATACADASTRO) VALUES (:idtipoatend, :requisitante, :dataatend, :idusuario, :ipcomputador, :dataAgora);";
                    
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idtipoatend', $idtipoatend, PDO::PARAM_INT);
                    $stmt->bindParam('requisitante', $requisitante, PDO::PARAM_STR);
                    $stmt->bindParam('dataatend', $dataatend, PDO::PARAM_STR);
                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                    $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                    $stmt->execute();
                    $resultado = $stmt->rowCount();
    
                    if($resultado>0){
                        $sql = "SELECT ID FROM chefia_atendimentos_requis WHERE IDTIPOATEND = :idtipoatend AND REQUISITANTE = :requisitante AND DATACADASTRO = :dataAgora;";
                    
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idtipoatend', $idtipoatend, PDO::PARAM_INT);
                        $stmt->bindParam('requisitante', $requisitante, PDO::PARAM_STR);
                        $stmt->bindParam('dataAgora', $dataAgora, PDO::PARAM_STR);
                        $stmt->execute();
                        $resultado = $stmt->fetchAll();

                        $idmovimentacao = $resultado[0]['ID'];
                    }else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Erro ao inserir Requisitante. </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }else{
                    $sql = "UPDATE chefia_atendimentos_requis SET IDTIPOATEND = :idtipoatend, REQUISITANTE = :requisitante, DATAATEND = :dataatend, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idmovimentacao;;";
                    
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idtipoatend', $idtipoatend, PDO::PARAM_INT);
                    $stmt->bindParam('requisitante', $requisitante, PDO::PARAM_STR);
                    $stmt->bindParam('dataatend', $dataatend, PDO::PARAM_STR);
                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                    $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();
                }

                //Primeiro busca todos os presos que estão inclusos no atendimento
                $sql = "SELECT ID FROM chefia_atendimentos WHERE IDREQ = :idmovimentacao AND IDEXCLUSOREGISTRO IS NULL;"; 
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                $stmt->execute();

                $listaPresosExcluir = [];
                while($presosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                    array_push($listaPresosExcluir,$presosexistente['ID']);
                }
                
                if($presos!=0){
                    foreach($presos as $preso){
                        $idpreso = $preso['idpreso'];
                        $idbanco = $preso['idbanco'];
                        $idsituacao = $preso['idsituacao'];

                        if($idbanco==0){
                            $sql = "INSERT INTO chefia_atendimentos (IDREQ, IDPRESO, IDSITUACAO, IDCADASTRO, IPCADASTRO) VALUES (:idmovimentacao, :idpreso, :idsituacao, :idusuario, :ipcomputador);";

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idmovimentacao', $idmovimentacao, PDO::PARAM_INT);
                            $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                            $stmt->bindParam('idsituacao', $idsituacao, PDO::PARAM_INT);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->execute();
                            $resultado = $stmt->rowCount();

                        }else{
                            $sql = "UPDATE chefia_atendimentos SET IDSITUACAO = :idsituacao, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idbanco;";
                            
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idsituacao', $idsituacao, PDO::PARAM_INT);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->bindParam('idbanco', $idbanco, PDO::PARAM_INT);
                            $stmt->execute();
                            $resultado = $stmt->rowCount();

                            $posicao = array_search($idbanco,$listaPresosExcluir);        
                            if($posicao!==false){
                                unset($listaPresosExcluir[$posicao]);
                            }
                        }
                    }
                }
            
                //Exclui os artigos que foram exclusos da entrada do preso.
                if(count($listaPresosExcluir)){
                    foreach($listaPresosExcluir as $idexcluir){
                        $sql = "UPDATE chefia_atendimentos SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                        $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                        $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Atendimento enviado com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            //Inserir Bate Piso / Bate Grade
            elseif($tipo==4 && $idtipoproced>0){
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
                
                $arrfuncionarios = isset($_POST['arrfuncionarios'])?$_POST['arrfuncionarios']:0;

                //Insere os dados do boletim
                $sql = retornaQueryDadosBoletimVigente(); 
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();

                //Primeiro busca todos os funcionários já adicionados no procedimento
                $sql = "SELECT ID FROM chefia_proced_bate_piso_grade WHERE IDBOLETIM = @intIDBoletim AND IDPROCED = :idtipoproced AND IDEXCLUSOREGISTRO IS NULL;"; 
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idtipoproced', $idtipoproced, PDO::PARAM_INT);
                $stmt->execute();

                $listaExcluir = [];
                while($funcionarioexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                    array_push($listaExcluir,$funcionarioexistente['ID']);
                }
                
                if($arrfuncionarios!=0){
                    foreach($arrfuncionarios as $funcionario){
                        $idfuncionario = $funcionario['idfuncionario'];
                        $celas = $funcionario['celas'];

                        foreach($celas as $raios){
                            $idraio = $raios['idraio'];
                            $celasselecionadas = $raios['celas'];
                            
                            foreach($celasselecionadas as $id){
                                $cela = $id['cela'];
                                $idbanco = $id['idbanco'];
                                $params=[];
                                $sql='';

                                if($idbanco==0){
                                    if($sql==''){
                                        $sql = "INSERT INTO chefia_proced_bate_piso_grade (IDPROCED,IDUSUARIO,IDRAIO,CELA,IDCADASTRO,IPCADASTRO) VALUES (?,?,?,?,?,?)";
                                    }else{
                                        $sql .= ",(?,?,?,?,?,?)";
                                    }

                                    array_push($params,$idtipoproced);
                                    array_push($params,$idfuncionario);
                                    array_push($params,$idraio);
                                    array_push($params,$cela);
                                    array_push($params,$idusuario);
                                    array_push($params,$ipcomputador);

                                    $stmt = $GLOBALS['conexao']->prepare($sql);
                                    $stmt->execute($params);

                                }else{
                                    $posicao = array_search($idbanco,$listaExcluir);        
                                    if($posicao!==false){
                                        unset($listaExcluir[$posicao]);
                                    }
                                }
                            }
                        }
                    }
                }
            
                //Exclui os artigos que foram exclusos da entrada do preso.
                if(count($listaExcluir)){
                    foreach($listaExcluir as $idexcluir){
                        $sql = "UPDATE chefia_proced_bate_piso_grade SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                        $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                        $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados inseridos com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            // Inserir contagens
            elseif($tipo==5 && $idtipocontagem>0){
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

                //Verifica se a contagem já foi iniciada
                $resultado = retornaDadosContagens($idtipocontagem,1);
                
                //Verifica se foi encontrado algum registro
                if($resultado){
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-aviso'> A contagem já foi inciada. </li>"));
                    exit();
                }

                if($idtipocontagem==1){

                    $sql = retornaQueryDadosBoletimVigente();
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute();
                        
                    $sql = "SELECT @intIDDiretor IDDIRETOR;";
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();
    
                    if($resultado[0]['IDDIRETOR']==NULL || $resultado[0]['IDDIRETOR']==''){
                        echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado Diretor de Plantão. Não será possível iniciar a contagem de Final de Plantão sem um Diretor Responsável!</li>"));
                        exit();
                    }
                }

                $params=[$idtipocontagem,$idusuario,$ipcomputador];

                $sql = "INSERT INTO chefia_contagens (IDTIPO,IDRAIO,QTD,IDCADASTRO,IPCADASTRO)
                SELECT ?, RC.ID ID, count(RAIO) QTD, ?, ?
                FROM tab_raioscelas RC
                LEFT JOIN cadastros_mudancacela CMUD ON CMUD.RAIO = RC.ID
                WHERE RC.QTD > 0 AND CMUD.RAIOALTERADO IS NULL GROUP BY RC.ID ORDER BY ID;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();
                
                $retorno = array('OK' => "<li class = 'mensagem-exito'> Contagem iniciada com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            //Altera quem fez a contagem
            elseif($tipo==6 && $idcontagem>0){
                
                $params=[$idfuncionario];
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

                    array_push($params,0);
                    array_push($params,$idusuario);
                }else{
                    array_push($params,1);
                    array_push($params,$idfuncionario);
                }
                array_push($params,$ipcomputador);
                array_push($params,$idcontagem);

                
                //Buscar as informações do boletim para fazer uma alteração mais segura do usuário que está fazendo a contagem
                $sql = retornaQueryDadosBoletimVigente();
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();

                $sql = "UPDATE chefia_contagens SET IDUSUARIO = ?, AUTENTICADO = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ? AND IDBOLETIM = @intIDBoletim;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();
                
                $retorno = array('OK' => "<li class = 'mensagem-exito'> Contagem atualizada com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            // Excluir contagens
            elseif($tipo==7 && $idtipocontagem>0){
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

                $resultado = verificaLiberacaoContagens($idtipocontagem);

                if($resultado[0]['CONTAGEMEXISTE']==0){
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-aviso'> A contagem não existe para ser excluída. </li>"));
                    exit();
                }

                //Buscar as informações do boletim
                $sql = retornaQueryDadosBoletimVigente();
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();

                $params=[$idusuario,$ipcomputador,$idtipocontagem];
                
                $sql = "UPDATE chefia_contagens SET IDEXCLUSOREGISTRO = ?, IPEXCLUSOREGISTRO = ? WHERE IDBOLETIM = @intIDBoletim AND IDTIPO = ? AND IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();
                
                $retorno = array('OK' => "<li class = 'mensagem-exito'> Contagem excluída com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            // Inicia novo boletim
            elseif($tipo==8){
                $resultado = verificaLiberacaoContagens(1);
                //Verifica se todas contagens da passagem de plantão estão realizadas

                if($resultado[0]['CONTAGEMEXISTE']==0){
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-aviso'> A contagem de passagem de plantão não existe. </li>"));
                    exit();

                }else{
                    
                    if($resultado[0]['CONTAGEMLIBERADA']==0){
                        echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-aviso'> A contagem ainda não foi concluída, por favor, encerre as contagens e tente novamente. </li>"));
                        exit();
                    }else{
                        
                        $sql = retornaQueryDadosBoletimVigente();
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->execute();
                            
                        $sql = "SELECT @intIDDiretor IDDIRETOR;";
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->execute();
                        $resultado = $stmt->fetchAll();
        
                        if($resultado[0]['IDDIRETOR']==NULL || $resultado[0]['IDDIRETOR']==''){
                            echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado Diretor de Plantão. Não será possível iniciar um novo Boletim Informativo antes ser designado um Diretor Responsável para o Boletim em andamento!</li>"));
                            exit();
                        }

                        $resultado = retornaPermissaoPenal(2,1);

                        //Verifica se o usuário tem a permissão necessária
                        $permissoesNecessarias = array($resultado[0]['IDPERMISSAO']);
                        $blnPermitido = verificaPermissao($permissoesNecessarias,"");
                        $nometurno = $resultado[0]['NOME'];

                        if($blnPermitido==false){
                            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. É necessário a permissão de Penal do $nometurno para iniciar o novo Boletim. </li>");
                            echo json_encode($retorno);
                            exit();
                        }

                        $params=[$idusuario,$ipcomputador];

                        $sql = "CALL PROCED_gera_numero_boletim(?,?);";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->execute($params);
                        
                        $sql = "SELECT ID, concat(LPAD(NUMERO,3,0),'/',date_format(DATABOLETIM,'%Y')) NUMERACAO, NUMERO, DATABOLETIM FROM chefia_boletim WHERE BOLETIMDODIA = TRUE;";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->execute();
                        $resultado = $stmt->fetchAll();
                        
                        $idboletim = $resultado[0]['ID'];
                        $numeracao = $resultado[0]['NUMERACAO'];
                        $databoletim = retornaDadosDataHora($resultado[0]['DATABOLETIM'],2);
                        $retorno = array('OK' => "<li class = 'mensagem-exito'> Boletim $numeracao iniciado com sucesso. Bom trabalho à todos!</li>");
                        echo json_encode($retorno);
                        exit();

                    }
                }

                // //Buscar as informações do boletim
                // $sql = retornaQueryDadosBoletimVigente();
                // $stmt = $GLOBALS['conexao']->prepare($sql);
                // $stmt->execute();

                // $params=[$idusuario,$ipcomputador,$idtipocontagem];
                
                // $sql = "UPDATE chefia_contagens SET IDEXCLUSOREGISTRO = ?, IPEXCLUSOREGISTRO = ? WHERE IDBOLETIM = @intIDBoletim AND IDTIPO = ? AND IDEXCLUSOREGISTRO IS NULL;";

                // $stmt = $GLOBALS['conexao']->prepare($sql);
                // $stmt->execute($params);
                // $resultado = $stmt->rowCount();
                
                // $retorno = array('OK' => "<li class = 'mensagem-exito'> Contagem excluída com sucesso!! </li>");
                // echo json_encode($retorno);
                // exit();
            }
            // Salva dados boletim
            elseif($tipo==9){
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

                $params=[$iddiretor,$idusuario,$ipcomputador];
                    
                $sql = "UPDATE chefia_boletim SET IDDIRETOR = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE BOLETIMDODIA = TRUE;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();
                
                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                echo json_encode($retorno);
                exit();

            }
            // Salva alteração de horário
            elseif($tipo==10 && $tabela>0 && $idmovimentacao>0){
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

                switch($tabela){
                    case 6:
                        $sql = "UPDATE enf_atendimentos SET DATAATEND = concat(date_format(DATAATEND, '%Y-%m-%d '), ?), IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?";
                        break;
            
                    case 7:
                        $sql = "UPDATE enf_atendimentos SET DATAATEND = concat(date_format(DATAATEND, '%Y-%m-%d '), ?), IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = (SELECT IDREQ FROM chefia_atendimentos WHERE ID = ?";
                        break;
            
                    case 8:
                        $sql = "UPDATE cimic_exclusoes SET DATASAIDA = concat(date_format(DATASAIDA, '%Y-%m-%d'),' ', ?), IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?";
                        break;
            
                    default:
                        exit();
                        break;
                }

                $params=[$horario,$idusuario,$ipcomputador,$idmovimentacao];
                 
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->rowCount();
                
                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                echo json_encode($retorno);
                exit();

            }
            // Inserir cela para preso sem cela
            elseif($tipo==11 && $arrpresos!=0){
                // echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ". __LINE__." </li>"));exit();

                verificaBloqueioMovimentacao();

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

                foreach($arrpresos as $preso){
                    $idpreso = $preso['idpreso'];
                    $idraio = $preso['idraio'];
                    $cela = $preso['cela'];

                    //Somente altera se o idraio for diferente de 0
                    if($idraio>0){
                        //Insere por padrão na inclusão, se caso for outra cela o procedimento é feito como mudança de cela com o status de realizada
                        $sql = "INSERT INTO cadastros_mudancacela (IDPRESO,RAIO,CELA,IDCADASTRO, IPCADASTRO) VALUES(?,?,?,?,?);";
                        $params=[$idpreso,7,1,$idusuario,$ipcomputador];
                        
                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->execute($params);
                        // $resultado = $stmt->rowCount();
                        
                        //Caso não for a inclusão
                        if($idraio!=7){
                            $sql = "INSERT INTO chefia_mudancacela (IDPRESO, RAIODESTINO, CELADESTINO, IDSITUACAO, OBSERVACOES, IDCADASTRO, IPCADASTRO)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
                            $params=[$idpreso,$idraio,$cela,6,'Alterado de cela automaticamente no momento de inclusão',$idusuario,$ipcomputador];
                            
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);
                        }
                    }
                }

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
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

    //echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ". __LINE__." </li>"));exit();

