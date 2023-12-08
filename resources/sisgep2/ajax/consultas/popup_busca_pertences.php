<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";

    $tipo = isset($_POST['tipo'])?$_POST['tipo']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    $idpertence = isset($_POST['idpertence'])?$_POST['idpertence']:0;
    $tipopertence = isset($_POST['tipopertence'])?$_POST['tipopertence']:0;
    
    if($tipo==0){   
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum tipo foi informado. </li>");
        echo json_encode($retorno);
        exit();
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            //Busca todos os pertences que já foram guardados para o preso no id informado
            if($tipo==1){
                $sql = "SELECT concat(date_format(IP.DATAENTRADA,'%d/%m/%Y'), ' (', TP.NOME, ')') NOMEEXIBIR, IP.ID VALOR
                FROM inc_pertences IP
                INNER JOIN entradas_presos EP ON EP.ID = IP.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN inc_pertencestipopertence TP ON TP.ID = IP.IDTIPOPERTENCE
                WHERE IP.IDPRESO = :idpreso AND IP.IDEXCLUSOREGISTRO IS NULL AND IP.DATAEXCLUSOREGISTRO IS NULL
                ORDER BY IP.DATAENTRADA DESC";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
   
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    /*$retorno='';
                    foreach($resultado as $dados){
                        $retorno .= "<option value=".$dados['VALOR'].">".$dados['NOMEEXIBIR']."</option>";
                    }*/

                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum Pertence foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca informações do pertence
            elseif($tipo==2 && $idpertence!=0){

                $sql = "SELECT IP.ID, date_format(IP.DATAENTRADA,'%Y-%m-%d') DATAENTRADA, date_format(IP.DATARETIRADA,'%Y-%m-%d') DATARETIRADA, IP.NOMERETIRADA, IP.OBSERVACOES, TP.NOME TIPO, TP.ID IDTIPO, IP.IDGRAUPARENTESCO, IP.DESCARTADO, date_format(IP.DATADESCARTADO,'%Y-%m-%d') DATADESCARTADO
                FROM inc_pertences IP
                INNER JOIN inc_pertencestipopertence TP ON TP.ID = IP.IDTIPOPERTENCE
                WHERE IP.ID = :idpertence";

                //echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-aviso'> Teste. </li>"));exit();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpertence', $idpertence, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum dado encontrado para o pertence selecionado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca graus de parentesco
            elseif($tipo==3){

                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM tab_grauparentesco ORDER BY NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                $retorno = "<option value=0>Selecione</option>";
                foreach($resultado as $dados){
                    if($dados['VALOR']==$idSelecionar){
                        $retorno .= "<option value=".$dados['VALOR']." selected>".$dados['NOMEEXIBIR']."</option>";
                    }else{
                        $retorno .= "<option value=".$dados['VALOR'].">".$dados['NOMEEXIBIR']."</option>";
                    }
                }
                echo json_encode($retorno);
                exit();
                //unset($GLOBALS['conexao']);
            }
            //Busca tipos de pertences
            elseif($tipo==4 && $tipopertence!=0){

                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM inc_pertencestipopertence WHERE ID = :tipopertence;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('tipopertence', $tipopertence, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                echo json_encode($resultado);
                exit();
                //unset($GLOBALS['conexao']);
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