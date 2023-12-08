<?php

use LDAP\Result;

    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once '../../funcoes/userinfo.php';
    include_once "../../funcoes/funcoes.php";

    $retorno=[];

    $tipo = $_POST['tipo'];
    $confirmacao = isset($_POST['confirmacao'])?$_POST['confirmacao']:0;
    $idfuncionario = isset($_POST['idfuncionario'])?$_POST['idfuncionario']:0;
    $nome = isset($_POST['nome'])?$_POST['nome']:0;
    $usuario = isset($_POST['usuario'])?$_POST['usuario']:0;
    $rs = isset($_POST['rs'])?$_POST['rs']:0;
    $cpf = isset($_POST['cpf'])?$_POST['cpf']:0;
    $rg = isset($_POST['rg'])?$_POST['rg']:0;
    $idturno = isset($_POST['idturno'])?$_POST['idturno']:0;
    $idstatus = isset($_POST['idstatus'])?$_POST['idstatus']:0;
    $idsituacao = isset($_POST['idsituacao'])?$_POST['idsituacao']:0;
    $idescala = isset($_POST['idescala'])?$_POST['idescala']:0;
    $arrpermissoes = isset($_POST['arrpermissoes'])?$_POST['arrpermissoes']:0;
    $idtipoescala = isset($_POST['idtipoescala'])?$_POST['idtipoescala']:0;
    $idmodelo = isset($_POST['idmodelo'])?$_POST['idmodelo']:0;
    $substituto = isset($_POST['substituto'])?$_POST['substituto']:0;
    $idboletim = isset($_POST['idboletim'])?$_POST['idboletim']:0;
    $temporaria = isset($_POST['temporaria'])?$_POST['temporaria']:0;
    $datainicio = isset($_POST['datainicio'])?$_POST['datainicio']:0;
    $datatermino = isset($_POST['datatermino'])?$_POST['datatermino']:0;
    $idbanco = isset($_POST['idbanco'])?$_POST['idbanco']:0;

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();
    $dataAgora = date('Y-m-d H:i:s');

    // Verifica se o usuário tem a permissão necessária
    $permissoesNecessarias = buscaIDsSetorPai();
    //Adicionar as permissões do Penal
    // $resultado = retornaPermissaoPenal(3,2);
    // foreach($resultado as $perm){
    //     array_push($permissoesNecessarias,$perm); //
    // }
    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

    if($blnPermitido==false){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para executar essa ação. </li>");
        echo json_encode($retorno);
        exit();
    }

    $conexaoStatus = conectarBD();    
    if($conexaoStatus===true){
        try {
            //Inserir/Alterar Funcionários
            if($tipo==1){
                if($idfuncionario==0){
                    //Verifica se o usuário tem a permissão necessária
                    /*$permissoesNecessarias = array(9,37);
                    $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                    if($blnPermitido==false){
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para inserir Apresentações de Presos. </li>");
                        echo json_encode($retorno);
                        exit();
                    }*/

                    // PRIMEIRO VERIFICA-SE SE JÁ NÃO EXISTE UM FUNCIONÁRIO COM ALGUMA DAS INFORMAÇÕES IDÊNTICAS;
                    $sql = "SELECT * FROM tab_usuarios WHERE USUARIO = :usuario OR RSUSUARIO = :rs OR CPF = :cpf;";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('usuario', $usuario, PDO::PARAM_STR);
                    $stmt->bindParam('rs', $rs, PDO::PARAM_INT);
                    $stmt->bindParam('cpf', $cpf, PDO::PARAM_STR);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();

                    if(count($resultado)){
                        $retorno = '';
                        foreach($resultado as $dados){
                            if($dados['USUARIO']==$usuario){
                                $retorno .="\r Usuário $usuario coincide com usuario do funcionário ".$dados['NOME'];
                            }
                            if($dados['RSUSUARIO']==$rs){
                                $retorno .="\r RS Servidor $rs coincide com usuario do funcionário ".$dados['NOME'];
                            }
                            if($dados['CPF']==$cpf){
                                $retorno .="\r CPF $cpf coincide com CPF do funcionário ".$dados['NOME'];
                            }
                        }
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não é possível inserir este funcionário pois algum dos dados coincidem com dados de um funcionário já existente!!\r$retorno</li>");
                        echo json_encode($retorno);
                        exit();
                    }

                    $sql = "INSERT INTO tab_usuarios (USUARIO, RSUSUARIO, NOME, CPF, IDTURNO, IDESCALA, CONTABLOQUEADA, STATUS, IDCADASTRO, IPCADASTRO) VALUES (:usuario, :rs, :nome, :cpf, :idturno, :idescala, :idsituacao, :idstatus, :idusuario, :ipcomputador)";
            
                }else{
                    $sql = "UPDATE tab_usuarios SET USUARIO = :usuario, RSUSUARIO = :rs, NOME = :nome, CPF = :cpf, IDTURNO = :idturno, IDESCALA = :idescala, CONTABLOQUEADA = :idsituacao, STATUS = :idstatus, IDATUALIZACAO = :idusuario, IPATUALIZACAO = :ipcomputador WHERE ID = :idfuncionario;";
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                if($idfuncionario!=0){
                    $stmt->bindParam('idfuncionario', $idfuncionario, PDO::PARAM_INT);
                }
                $stmt->bindParam('usuario', $usuario, PDO::PARAM_STR);
                $stmt->bindParam('rs', $rs, PDO::PARAM_INT);
                $stmt->bindParam('nome', $nome, PDO::PARAM_STR);
                $stmt->bindParam('cpf', $cpf, PDO::PARAM_STR);
                $stmt->bindParam('idturno', $idturno, PDO::PARAM_INT);
                $stmt->bindParam('idescala', $idescala, PDO::PARAM_INT);
                $stmt->bindParam('idsituacao', $idsituacao, PDO::PARAM_INT);
                $stmt->bindParam('idstatus', $idstatus, PDO::PARAM_INT);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->execute();
                $resultado = $stmt->rowCount();

                if($idfuncionario==0){
                    $sql = "SELECT * FROM tab_usuarios WHERE CPF = :cpf;";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('cpf', $cpf, PDO::PARAM_STR);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();

                    if(count($resultado)){
                        $idfuncionario = $resultado[0]['ID'];
                    }else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> ID Funcionário não encontrado!! </li>");
                        echo json_encode($retorno);
                        exit();
                    }
                }

                if($arrpermissoes!=0){
                    $params = [];
                    $where = '';
                    
                    $sql = '';

                    //Se não for temporária então já se coloca 0 nas datas por segurança
                    if($temporaria==0){
                        $datainicio = 0;
                        $datatermino = 0;
                    }

                    foreach($arrpermissoes as $permissoes){
                        $sql .= "CALL PROCED_verifica_permissoes(?,?,?,?,?,?,?,?,?,?);";
                        array_push($params,$idfuncionario);
                        array_push($params,$permissoes['idpermissao']);
                        array_push($params,$permissoes['valor']);
                        array_push($params,$permissoes['substituto']);
                        array_push($params,$idboletim);
                        array_push($params,$temporaria);
                        array_push($params,$datainicio);
                        array_push($params,$datatermino);
                        array_push($params,$idusuario);
                        array_push($params,$ipcomputador);
                    }

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute($params);
                }

                $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                echo json_encode($retorno);
                exit();
            }
            //Inserir/Alterar Escalas
            elseif($tipo==2 && $idmodelo!=0){
                $arrfuncionarios = isset($_POST['arrfuncionarios'])?$_POST['arrfuncionarios']:0;

                //Verifica se o usuário tem a permissão necessária
                /*$permissoesNecessarias = array(9,37);
                $blnPermitido = verificaPermissao($permissoesNecessarias,"");

                if($blnPermitido==false){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui a permissão necessária para inserir Apresentações de Presos. </li>");
                    echo json_encode($retorno);
                    exit();
                }*/
                
                $sql = retornaQueryDadosBoletimVigente();
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                
                //Se for modelo 1(Escala de plantão)
                if($idmodelo==1){
                    // PRIMEIRO VERIFICA-SE SE JÁ EXISTE UMA ESCALA DO PLANTÃO INICIADA E ATRIBUI ESTE VALOR A VARIÁVEL PARA SAIR DO LOOP;
                    while($idescala==0){
                        $sql = "SELECT * FROM funcionarios_escalaplantao WHERE IDBOLETIM = @intIDBoletim AND IDTIPO = :idtipoescala AND IDEXCLUSOREGISTRO IS NULL;";

                        $stmt = $GLOBALS['conexao']->prepare($sql);
                        $stmt->bindParam('idtipoescala', $idtipoescala, PDO::PARAM_INT);
                        $stmt->execute();
                        $resultado = $stmt->fetchAll();
        
                        if(count($resultado)){
                            $idescala = $resultado[0]['ID'];
                        }else{
                            $sql = "INSERT INTO funcionarios_escalaplantao (IDTIPO,IDCADASTRO,IPCADASTRO) VALUES (:idtipoescala, :idusuario, :ipcomputador)";
        
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idtipoescala', $idtipoescala, PDO::PARAM_INT);
                            $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                            $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                            $stmt->execute();
                        }    
                    }
                        
                    //Primeiro busca todos os funcionários que já estão salvos nesta escala
                    $sql = "SELECT * FROM funcionarios_escalaplantao_func WHERE IDESCALA = :idescala AND IDEXCLUSOREGISTRO IS NULL;"; 
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idescala', $idescala, PDO::PARAM_INT);
                    $stmt->execute();
                    $listaExcluir = [];
                    while($funcionariosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($listaExcluir,$funcionariosexistente['ID']);
                    }
                    
                    if($arrfuncionarios!=0){
                        $contador = 0;
                        $retorno='';
                        foreach($arrfuncionarios as $funcionario){
                            $params=[];
                            $idbanco = $funcionario['idbanco'];
                            $idfuncionario = $funcionario['idfuncionario'];
                            $idposto = $funcionario['idposto'];
                            $observacoes = $funcionario['observacoes'];
                            $troca = $funcionario['troca'];

                            //Verifica se o funcionário já existe na escala, se existe então somente altera, ao invés de excluir e adicionar um novo, caso o funcionário foi excluído e adicionado novamente na mesma alteração de escala
                            $posicao = array_search($idbanco,$listaExcluir);        
                            if($posicao!==false){
                                unset($listaExcluir[$posicao]);
                            }

                            if($idbanco==0){
                                $sql = "INSERT INTO funcionarios_escalaplantao_func (IDESCALA, IDPOSTO, IDUSUARIO, OBSERVACOES, IDCADASTRO, IPCADASTRO) VALUES (?,?,?,?,?,?);";
                                array_push($params,$idescala);
                                array_push($params,$idposto);
                                array_push($params,$idfuncionario);
                                array_push($params,$observacoes);
                                array_push($params,$idusuario);
                                array_push($params,$ipcomputador);
                            }else{
                                $sql = "UPDATE funcionarios_escalaplantao_func SET IDPOSTO = ?, OBSERVACOES = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                                array_push($params,$idposto);
                                array_push($params,$observacoes);
                                array_push($params,$idusuario);
                                array_push($params,$ipcomputador);
                                array_push($params,$idbanco);
                            }

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);

                            if($troca!=0){
                                if($idbanco==0){
                                    $params=[];
                                    //Busca o ID para inserir a troca
                                    $sql = "SELECT * FROM funcionarios_escalaplantao_func WHERE IDESCALA = ? AND IDUSUARIO = ? ORDER BY ID DESC LIMIT 1;";

                                    array_push($params,$idescala);
                                    array_push($params,$idfuncionario);

                                    $stmt = $GLOBALS['conexao']->prepare($sql);
                                    $stmt->execute($params);
                                    $resultado = $stmt->fetchAll();
                                    
                                    $idbanco = $resultado[0]['ID'];
                                }

                                $idfuncionario = $troca['idfuncionario'];
                                $idposto = $troca['idposto'];
                                
                                //Insere a troca
                                $params=[];
                                $sql = "CALL PROCED_verifica_funcionario_troca(?,?,?,?,?);";
                                array_push($params,$idfuncionario);
                                array_push($params,$idposto);
                                array_push($params,$idbanco);
                                array_push($params,$idusuario);
                                array_push($params,$ipcomputador);

                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->execute($params);
                            }
                        }

                        //Exclui os funcionários que foram excluídos da escala.
                        if(count($listaExcluir)){                       
                            foreach($listaExcluir as $idexcluir){
                                $sql = "UPDATE funcionarios_escalaplantao_func SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";

                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                                $stmt->execute();
                            }
                        }
                    }

                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                    echo json_encode($retorno);
                    exit();
                }
                //Se for modelo 2(Escala de padrão ou mensal)
                elseif($idmodelo==2){  
                    $params=[$idturno,$idtipoescala];

                    //Primeiro busca todos os funcionários que já estão salvos nesta escala
                    $sql = "SELECT * FROM funcionarios_escalamensal WHERE IDTURNO = ? AND IDTIPO = ? AND IDEXCLUSOREGISTRO IS NULL;"; 
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    // $stmt->bindParam('idturno', $idturno, PDO::PARAM_INT);
                    // $stmt->bindParam('idtipoescala', $idtipoescala, PDO::PARAM_INT);
                    $stmt->execute($params);
                    $listaExcluir = [];
                    while($funcionariosexistente = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($listaExcluir,$funcionariosexistente['ID']);
                    }
                    
                    if($arrfuncionarios!=0){
                        $contador = 0;
                        $retorno='';
                        foreach($arrfuncionarios as $funcionario){
                            $params=[];
                            $idbanco = $funcionario['idbanco'];
                            $idfuncionario = $funcionario['idfuncionario'];
                            $idposto = $funcionario['idposto'];
                            $observacoes = $funcionario['observacoes'];

                            //Verifica se o funcionário já existe na escala, se existe então somente altera, ao invés de excluir e adicionar um novo, caso o funcionário foi excluído e adicionado novamente na mesma alteração de escala
                            $posicao = array_search($idbanco,$listaExcluir);        
                            if($posicao!==false){
                                unset($listaExcluir[$posicao]);
                            }

                            if($idbanco==0){
                                $params = [$idturno,$idtipoescala,$idposto,$idfuncionario,$observacoes,$idusuario,$ipcomputador];

                                $sql = "INSERT INTO funcionarios_escalamensal (IDTURNO, IDTIPO, IDPOSTO, IDUSUARIO, OBSERVACOES, IDCADASTRO, IPCADASTRO) VALUES (?,?,?,?,?,?);";

                            }else{
                                $params = [$idposto,$observacoes,$idusuario,$ipcomputador,$idbanco];

                                $sql = "UPDATE funcionarios_escalamensal SET IDPOSTO = ?, OBSERVACOES = ?, IDATUALIZACAO = ?, IPATUALIZACAO = ? WHERE ID = ?;";
                            }

                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->execute($params);
                        }

                        //Exclui os funcionários que foram excluídos da escala.
                        if(count($listaExcluir)){                       
                            foreach($listaExcluir as $idexcluir){
                                $sql = "UPDATE funcionarios_escalamensal SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idexcluir;";

                                $stmt = $GLOBALS['conexao']->prepare($sql);
                                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                                $stmt->bindParam("idexcluir", $idexcluir, PDO::PARAM_INT);
                                $stmt->execute();
                            }
                        }
                    }

                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Excluir escala de plantão
            elseif($tipo==3){
                
                $sql = retornaQueryDadosBoletimVigente();
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();

                $sql = "UPDATE funcionarios_escalaplantao SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE IDBOLETIM = @intIDBoletim AND IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                $stmt->execute();
                $resultado = $stmt->rowCount();

                if($resultado){
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Dados enviados com sucesso!! </li>");
                    echo json_encode($retorno);
                    exit();
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível excluir a escala informada! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Excluir permissao temporária
            elseif($tipo==4 && $idbanco>0){

                // Busca o id permissão para após verificar se o usuário que está editando tem permissão para excluir a permissão temporária

                $sql = "SELECT IDPERMISSAO FROM tab_usuariospermissoes WHERE ID = :idbanco;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idbanco', $idbanco, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                $idpermissao = $resultado[0]['IDPERMISSAO'];
                
                //Busca as permissões que o usuário tem
                $permissoes = buscaPermissoesUsuario($_SESSION['id_usuario'],2);
                $resultado = buscaPermissoesFilhas($permissoes,2);
                
                if(array_search($idpermissao,$resultado)){
                    $sql = "UPDATE tab_usuariospermissoes SET IDEXCLUSOREGISTRO = :idusuario, IPEXCLUSOREGISTRO = :ipcomputador WHERE ID = :idbanco;";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idusuario', $idusuario, PDO::PARAM_INT);
                    $stmt->bindParam('ipcomputador', $ipcomputador, PDO::PARAM_STR);
                    $stmt->bindParam('idbanco', $idbanco, PDO::PARAM_INT);
                    $stmt->execute();
                    $resultado = $stmt->rowCount();
    
                    if($resultado){
                        $retorno = array('OK' => "<li class = 'mensagem-exito'> Permissão temporária excluída com sucesso!! </li>");
                    }else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível excluir esta permissão temporária! </li>");
                    }
    
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Não foi possível excluir esta permissão temporária. Você não tem permissão necessária para editar esta permissão! </li>");
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

    //echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ". __LINE__." </li>"));exit();

