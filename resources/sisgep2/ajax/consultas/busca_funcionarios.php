<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    $tipo = $_POST['tipo'];
    $idvisualizacao = isset($_POST['idvisualizacao'])?$_POST['idvisualizacao']:0;
    $idturno = isset($_POST['idturno'])?$_POST['idturno']:0;
    $idescala = isset($_POST['idescala'])?$_POST['idescala']:0;
    $idtipoescala = isset($_POST['idtipoescala'])?$_POST['idtipoescala']:0;
    $idfuncionario = isset($_POST['idfuncionario'])?$_POST['idfuncionario']:0;
    $usuario = isset($_POST['usuario'])?$_POST['usuario']:'';
    $senha = isset($_POST['senha'])?$_POST['senha']:'';

    $idusuario = $_SESSION['id_usuario'];

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){ 
        try {
            //Busca lista de funcionários
            if($tipo==1 && $idescala!=0){
                $idturno = isset($_POST['idturno'])?$_POST['idturno']:0;
                $status = isset($_POST['status'])?$_POST['status']:'X';
                $textobusca = isset($_POST['textobusca'])?$_POST['textobusca']:'';

                $params = [];
                $where = "";
                if($idturno!=0){
                    $where = " AND US.IDTURNO = ?";
                    array_push($params,$idturno);
                }
                if($status!="X"){
                    $where .= " AND US.STATUS = ?";
                    array_push($params,$status);
                }

                if($textobusca!=''){
                    $where .= " AND UCASE(US.NOME) LIKE CONCAT('%',UCASE(?),'%')";
                    array_push($params,$textobusca);
                }

                $sql = "SELECT US.ID IDUSUARIO, US.USUARIO, CASE US.STATUS WHEN TRUE THEN 'Ativa' ELSE 'Inativa' END STATUS, US.STATUS IDSTATUS, US.RSUSUARIO, US.NOME, US.APELIDO, US.RG, US.CPF, TU.NOME TURNO, US.IDTURNO, ET.NOME ESCALA, US.IDESCALA, CASE US.CONTABLOQUEADA WHEN TRUE THEN 'Bloqueada' ELSE 'Normal' END BLOQUEADA, US.CONTABLOQUEADA
                FROM tab_usuarios US
                INNER JOIN tab_turnos TU ON TU.ID = US.IDTURNO
                LEFT JOIN funcionarios_escalatipo ET ON ET.ID = US.IDESCALA
                WHERE US.ID > 2 $where ORDER BY TU.NOME, US.NOME, US.RSUSUARIO";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum registro foi encontrado para os dados informados. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca turnos existentes
            elseif($tipo==2){
                $blnescala = isset($_POST['blnescala'])?$_POST['blnescala']:0;
                $params=[];
                $where="";
                
                // $permissoes = buscaPermissoesUsuario($idusuario,2);
                // $retornosql = '';
                // foreach($permissoes as $permissao){
                //     if($where==''){
                //         $where = "?";
                //         $retornosql = $permissao;
                //     }else{
                //         $where .= ",?";
                //         $retornosql .= ",".$permissao;
                //     }
                //     array_push($params,$permissao);
                // }

                // $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> WHERE IDPERMISSAO IN ($retornosql) </li>");
                // echo json_encode($retorno);
                // exit();            
                
                // $retornosql = '';
                if($blnescala==1){
                    //Busca as permissões que o usuário tem
                    $permissoes = buscaPermissoesUsuario($idusuario,2);
                    $resultado = buscaPermissoesFilhas($permissoes,2,true);

                    foreach($resultado as $permissao){
                        if($where==''){
                            $where = "?";
                            // $retornosql = $permissao;
                        }else{
                            $where .= ",?";
                            // $retornosql .= ",".$permissao;
                        }
                        array_push($params,$permissao);
                    }

                    $where = "WHERE IDPERMISSAO IN ($where);";
                    // $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> WHERE IDPERMISSAO IN ($retornosql) </li>");
                    // echo json_encode($retorno);
                    // exit();            

                }

                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR, PERIODODIURNO FROM tab_turnos $where;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                
                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum turno foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca listagem de permissões que o usuário pode editar
            elseif($tipo==3){
                
                //Busca as permissões que o usuário tem
                $permissoes = buscaPermissoesUsuario($_SESSION['id_usuario'],2);

                $resultado = buscaPermissoesFilhas($permissoes,1);
                
                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Você não possui permissão para gerenciar permissões de nenhuma área. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca dados do funcionário informado
            elseif($tipo==4 && $idfuncionario!=0){
                
                $sql = "SELECT * FROM tab_usuarios WHERE ID = :idfuncionario;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idfuncionario',$idfuncionario,PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetchAll();
                
                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum funcionário foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca escala de plantão
            elseif($tipo==5 && $idtipoescala!=0){
                
                $sql = retornaQueryDadosBoletimVigente();
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                
                $sql = "SELECT FEPF.ID IDBANCO, FEPF.IDESCALA, FEPF.IDPOSTO, FEPOS.NOME NOMEPOSTO, FEPF.IDUSUARIO, US.NOME NOMEUSUARIO, FEPF.OBSERVACOES, BOL.IDTURNO, TROC.IDUSUARIO IDUSUARIOTROCA, TROC.IDPOSTO IDPOSTOTROCA
                FROM funcionarios_escalaplantao_func FEPF
                INNER JOIN funcionarios_escalaplantao FEP ON FEP.ID = FEPF.IDESCALA
                INNER JOIN chefia_boletim BOL ON BOL.ID = FEP.IDBOLETIM
                INNER JOIN tab_usuarios US ON US.ID = FEPF.IDUSUARIO
                INNER JOIN funcionarios_escalapostos FEPOS ON FEPOS.ID = FEPF.IDPOSTO
                LEFT JOIN funcionarios_escalaplantao_troca TROC ON TROC.IDFUNC = FEPF.ID                
                WHERE FEP.IDTIPO = :idtipoescala AND BOL.DATABOLETIM = date_format(@dataBoletim, '%Y-%m-%d') AND FEPF.IDEXCLUSOREGISTRO IS NULL AND FEP.IDEXCLUSOREGISTRO IS NULL AND TROC.IDEXCLUSOREGISTRO IS NULL ORDER BY ORDEM;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idtipoescala',$idtipoescala,PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetchAll();
                
                $retorno=$resultado;
            }
            //Busca escala mensal
            elseif($tipo==6 && $idtipoescala!=0 && $idturno!=0){
                                
                $sql = "SELECT FEM.ID IDBANCO, FEM.IDPOSTO, US.ID IDUSUARIO, US.NOME NOMEUSUARIO, FEM.OBSERVACOES
                FROM tab_usuarios US
                LEFT JOIN funcionarios_escalamensal FEM ON FEM.IDUSUARIO = US.ID
                LEFT JOIN funcionarios_escalapostos FEPOS ON FEPOS.ID = FEM.IDPOSTO
                WHERE US.IDESCALA = :idtipoescala AND US.IDTURNO = :idturno AND US.STATUS = 1 ORDER BY ORDEM;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idtipoescala',$idtipoescala,PDO::PARAM_INT);
                $stmt->bindParam('idturno',$idturno,PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetchAll();
                
                $retorno=$resultado;
            }
            //Verifica e compara turno vigente com o visualizado
            elseif($tipo==7 && $idturno!=0){
                
                $sql = retornaQueryDadosBoletimVigente();
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                
                $sql = "SELECT * FROM chefia_boletim CBOL
                INNER JOIN tab_turnos TUR ON TUR.ID = CBOL.IDTURNO WHERE CBOL.ID = @intIDBoletim;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll();

                if($idturno==$resultado[0]['IDTURNO']){
                    $retorno=array('RETORNO'=>1);
                }else{
                    $retorno=array('RETORNO'=>0);
                }
            }
            //Busca lista de funcionários ativos para select
            elseif($tipo==8){
                $where='';
                $turnonaoselecionados = isset($_POST['turnonaoselecionados'])?$_POST['turnonaoselecionados']:0;
                if($turnonaoselecionados!=0){
                    $where = "AND US.IDTURNO NOT IN ($turnonaoselecionados)";
                }

                $sql = "SELECT US.ID VALOR, concat(US.NOME, ' | ', TU.NOME) NOMEEXIBIR
                FROM tab_usuarios US
                INNER JOIN tab_turnos TU ON TU.ID = US.IDTURNO
                WHERE US.ID > 1 AND US.STATUS = 1 $where ORDER BY TU.ID, US.NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                
                $retorno=$resultado;
            }
            //Confirma dados da autenticação por usuário
            elseif($tipo==9 && $usuario!='' && $senha !=''){
                $sql = "SELECT * FROM tab_usuarios WHERE USUARIO = :usuario LIMIT 1;";
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('usuario',$usuario,PDO::PARAM_STR);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                
                if(count($resultado)){
                    $idusuario = $resultado[0]['ID'];
                    $senha = md5($senha);
                    
                    $sql = "SELECT * FROM tab_usuarios WHERE SENHA = :senha AND ID = :idusuario;";
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('senha',$senha,PDO::PARAM_STR);
                    $stmt->bindParam('idusuario',$idusuario,PDO::PARAM_INT);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();
                    
                    if(count($resultado)){
                        $retorno = array('IDUSUARIO' => $resultado[0]['ID']);
                    }else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Usuário ou senha incorretos. </li>");
                    }
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Usuário ou senha incorretos. </li>");
                }
            }
            //Busca permissões do funcionário informado
            elseif($tipo==10 && $idfuncionario!=0){
                $temporaria = isset($_POST['temporaria'])?$_POST['temporaria']:0;
                
                $params = [$idfuncionario,$temporaria];

                $where = '';
                if($temporaria==0){
                    $where = 'AND TUP.DATATERMINO IS NULL';
                }else{
                    $sql = retornaQueryDadosBoletimVigente();
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute();
                    
                    $where = 'AND (TUP.DATAINICIO <= @dataBoletimCurta AND TUP.DATATERMINO >= @dataBoletimCurta OR TUP.DATAINICIO >= @dataBoletimCurta)';
                }

                $sql = "SELECT TUP.*, TP.NOME NOMEPERMISSAO, TP.DESCRICAO DESCRICAOPERMISSAO, TP.DIRETOR FROM tab_usuariospermissoes TUP
                INNER JOIN tab_permissoes TP ON TP.ID = TUP.IDPERMISSAO
                WHERE TUP.IDUSUARIO = ? AND TUP.TEMPORARIO = ? $where AND TUP.IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                
                $retorno=$resultado;
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

echo json_encode($retorno);
exit();

echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." </li>"));exit();
