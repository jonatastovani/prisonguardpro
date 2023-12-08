<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes_comuns.php";

    //Cria a variável de retorno para salvar os a mensagem a ser exibida na tela
    $retorno = [];

    //Obtem o tipo de pesquisa para poder assim realizar a consulta.
    //Tipo 1 = buscar ordens de saída existentes
    $tipo = $_POST['tipo'];
    $idordem = isset($_POST['idordem'])?$_POST['idordem']:0;
    $idapres = isset($_POST['idapres'])?$_POST['idapres']:0;

    $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;
    $idbanco = isset($_POST['idbanco'])?$_POST['idbanco']:0;
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        try {

            //Tipo 1 = buscar ordens de saída existentes da cimic_apresentacoes
            if($tipo==1){
                //Monta o Select
                $sql = "SELECT COA.ID VALOR, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO, ' - ', LOC.NOMEABREVIADO, ' (', date_format(COA.DATASAIDA, '%W-%d/%m/%Y %H:%i'), ')') NOMEEXIBIR FROM cimic_ordens_apresentacoes COA
                INNER JOIN tab_ordemsaida TOS ON TOS.ID = COA.IDORDEM
                INNER JOIN cimic_locaisapresentacoes LOC ON LOC.ID = COA.IDDESTINO
                WHERE COA.IDEXCLUSOREGISTRO IS NULL AND COA.DATAEXCLUSOREGISTRO IS NULL ORDER BY COA.DATASAIDA DESC;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                //$stmt->bindParam('identrada',$identrada, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma Ordem de Saída existente! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 2 = buscar apresentações existentes na ordem de saída da cimic_apresentacoes
            elseif($tipo==2 && $idordem!=0){
                //Monta o Select
                $sql = "SELECT COA.ID IDORDEM, COA.DATASAIDA, COA.IDDESTINO, CA.ID IDMOVIMENTACAO, CA.HORAAPRES, CA.IDMOTIVOAPRES, CA.IDPRESO, concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) OFICIO, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) ORDEM, CA.REALIZADOSAIDA FROM cimic_apresentacoes CA
                INNER JOIN cimic_ordens_apresentacoes COA ON COA.ID = CA.IDORDEMSAIDAMOV
                INNER JOIN tab_oficios TOF ON TOF.ID = COA.IDOFICIOESCOLTA
				INNER JOIN tab_ordemsaida TOS ON TOS.ID = COA.IDORDEM
                WHERE COA.ID = :idordem AND CA.IDEXCLUSOREGISTRO IS NULL AND CA.DATAEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idordem',$idordem, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma Movimentação para esta Ordem de Saída! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 3 = buscar apresentacoes existentes da cimic_apresentacoes_internas
            elseif($tipo==3){
                //Monta o Select
                $sql = "SELECT CAI.ID VALOR, concat(date_format(CAI.DATASAIDA, '%W %d/%m/%Y'), ' - ', LOC.NOMEABREVIADO) NOMEEXIBIR FROM cimic_apresentacoes_internas CAI
                INNER JOIN cimic_locaisapresentacoes LOC ON LOC.ID = CAI.IDDESTINO
                WHERE CAI.IDEXCLUSOREGISTRO IS NULL AND CAI.DATAEXCLUSOREGISTRO IS NULL ORDER BY CAI.DATASAIDA DESC;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                //$stmt->bindParam('identrada',$identrada, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma Apresentação Interna! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 4 = buscar apresentações de presos da cimic_apresentacoes_internas_presos
            elseif($tipo==4 && $idapres!=0){
                //Monta o Select
                $sql = "SELECT CAIP.IDAPRES, CAI.DATASAIDA, CAI.IDDESTINO, CAIP.ID IDMOVIMENTACAO, CAIP.HORAAPRES, CAIP.IDMOTIVOAPRES, CAIP.IDPRESO, concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) OFICIO, CAIP.REALIZADOSAIDA FROM cimic_apresentacoes_internas_presos CAIP
                INNER JOIN cimic_apresentacoes_internas CAI ON CAI.ID = CAIP.IDAPRES
                INNER JOIN tab_oficios TOF ON TOF.ID = CAIP.IDOFICIOAPRES
                WHERE CAI.ID = :idapres AND CAIP.IDEXCLUSOREGISTRO IS NULL AND CAIP.DATAEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idapres',$idapres, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma Movimentação para esta Apresentação Interna! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 5 = buscar dados das ordens de saída da cimic_apresentacoes
            elseif($tipo==5 && $idordem!=0){
                //Monta o Select
                $sql = "SELECT COA.ID VALOR, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO, ' - ', LOC.NOMEABREVIADO, ' (', date_format(COA.DATASAIDA, '%W-%d/%m/%Y %H:%i'), ')') NOMEEXIBIR FROM cimic_ordens_apresentacoes COA
                INNER JOIN tab_ordemsaida TOS ON TOS.ID = COA.IDORDEM
                INNER JOIN cimic_locaisapresentacoes LOC ON LOC.ID = COA.IDDESTINO
                WHERE COA.ID = :idordem;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idordem',$idordem, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma Movimentação para esta Apresentação Interna! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 6 = buscar dados das apresentacoes cimic_apresentacoes_internas
            elseif($tipo==6 && $idapres!=0){
                //Monta o Select
                $sql = "SELECT CAI.ID VALOR, concat(date_format(CAI.DATASAIDA, '%W %d/%m/%Y'), ' - ', LOC.NOMEABREVIADO) NOMEEXIBIR FROM cimic_apresentacoes_internas CAI
                INNER JOIN cimic_locaisapresentacoes LOC ON LOC.ID = CAI.IDDESTINO
                WHERE CAI.ID = :idapres;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idapres',$idapres, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma Movimentação para esta Apresentação Interna! </li>");
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