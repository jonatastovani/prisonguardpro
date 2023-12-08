<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";

    $tipo = isset($_POST['tipo'])?$_POST['tipo']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    $iditem = isset($_POST['iditem'])?$_POST['iditem']:0;
    $idkit = isset($_POST['idkit'])?$_POST['idkit']:0;

    if($tipo==0){   
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum tipo foi informado. </li>");
        echo json_encode($retorno);
        exit();
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            //Busca todos os kits que já foram entregues para o preso no id informado
            if($tipo==1){
                $sql = "SELECT KE.ID VALOR, concat(date_format(KE.DATAENTREGA,'%d/%m/%Y'), ' (', KTE.NOME, ')') NOMEEXIBIR, KE.ID, KE.DATAENTREGA, KTE.NOME TIPOENTREGA
                FROM inc_kitentregue KE
                INNER JOIN entradas_presos EP ON KE.IDPRESO = EP.ID
                INNER JOIN inc_kittipoentrega KTE ON KTE.ID	= KE.IDTIPOENTREGA
                WHERE KE.IDPRESO = :idpreso AND KE.IDEXCLUSOREGISTRO IS NULL AND KE.DATAEXCLUSOREGISTRO IS NULL ORDER BY KE.DATAENTREGA DESC";

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
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum Kit Entregue foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca todos os ítens existentes e que não estão excluídos
            elseif($tipo==2){
                $sql = "SELECT ID VALOR, concat(NOME, CASE WHEN ITEMNOVO = 1 THEN ' (Novo)'  ELSE '' END) NOMEEXIBIR, NOME, QTD, PADRAOENTREGA FROM inc_kititens WHERE IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL ORDER BY NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    /*$retorno="<option value='0'>Selecione</option>";
                    foreach($resultado as $dados){
                        $retorno .= "<option value=".$dados['VALOR'].">".$dados['NOMEEXIBIR']."</option>";
                    }*/
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum item foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Varifica se o ítem existe e retorna os dados sobre o ítem
            elseif($tipo==3 && $iditem!=0){

                $sql = "SELECT ID VALOR, concat(NOME, CASE WHEN ITEMNOVO = 1 THEN ' (Novo)' ELSE '' END) NOMEEXIBIR, NOME, QTD, ITEMNOVO, PADRAOENTREGA, IDEXCLUSOREGISTRO EXCLUIDO, date_format(DATAEXCLUSOREGISTRO, '%d/%m/%Y %H:%i') DATAEXCLUIDO FROM inc_kititens WHERE ID = :iditem";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('iditem', $iditem, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum ítem encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca informações do kit
            elseif($tipo==4 && $idkit!=0){

                $sql = "SELECT KE.DATAENTREGA, KE.OBSERVACOES
                FROM inc_kitentregue KE
                WHERE KE.ID = :idkit";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idkit', $idkit, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum ítem encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca todos os ítens entregue no idkit informado
            elseif($tipo==5 && $idkit!=0){

                $sql = "SELECT KI.ID IDITEM, concat(KI.NOME, CASE WHEN ITEMNOVO = 1 THEN ' (Novo)' ELSE '' END) NOMEEXIBIR,
                KIE.ID IDITEMENTREGUE, KIE.QTD
                FROM inc_kitentregue KE 
                INNER JOIN inc_kititensentregue KIE ON KIE.IDKIT = KE.ID
                INNER JOIN inc_kititens KI ON KI.ID = KIE.IDITEM
                WHERE KE.ID = :idkit AND KIE.IDEXCLUSOREGISTRO IS NULL AND KIE.DATAEXCLUSOREGISTRO IS NULL ORDER BY KI.NOME";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idkit', $idkit, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum ítem encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca todos os ítens padrão de entrega
            elseif($tipo==6){
                $sql = "SELECT ID IDITEM, concat(NOME, ' (', CASE WHEN ITEMNOVO = 1 THEN 'Novo' WHEN ITEMNOVO = 0 THEN 'Usado' END, ')') NOMEEXIBIR , QTD FROM inc_kititens 
                WHERE PADRAOENTREGA = TRUE AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL ORDER BY NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum item foi encontrado. </li>");
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
        exit();
    }