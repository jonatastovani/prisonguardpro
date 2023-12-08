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
    $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;
    $idbanco = isset($_POST['idbanco'])?$_POST['idbanco']:0;

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        try {

            //Tipo 1 = buscar ordens de saída existentes
            if($tipo==1){
                //Monta o Select
                $sql = "SELECT COT.ID VALOR, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO, ' - ', UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE, ' (', date_format(COT.DATASAIDA, '%W-%d/%m/%Y %H:%i'), ')') NOMEEXIBIR FROM cimic_ordens_transferencias COT
                INNER JOIN tab_ordemsaida TOS ON TOS.ID = COT.IDORDEM
                INNER JOIN tab_unidades UN ON UN.ID = COT.IDDESTINO
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                WHERE COT.IDEXCLUSOREGISTRO IS NULL AND COT.DATAEXCLUSOREGISTRO IS NULL ORDER BY COT.DATASAIDA DESC;";

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
            //Tipo 2 = buscar movimentações existentes na ordem de saída
            elseif($tipo==2 && $idordem!=0){
                //Monta o Select
                $sql = "SELECT COT.ID IDORDEM, COT.DATASAIDA, COT.IDDESTINO, CT.ID IDMOVIMENTACAO, CT.IDPRESO, CT.IDTIPOMOV, CT.IDMOTIVOMOV, concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) OFICIO, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) ORDEM, CT.DATARETORNO, CT.REALIZADOSAIDA FROM cimic_transferencias CT
                INNER JOIN cimic_ordens_transferencias COT ON COT.ID = CT.IDORDEMSAIDAMOV
                INNER JOIN tab_oficios TOF ON TOF.ID = COT.IDOFICIOESCOLTA
				INNER JOIN tab_ordemsaida TOS ON TOS.ID = COT.IDORDEM
                WHERE COT.ID = :idordem AND CT.IDEXCLUSOREGISTRO IS NULL AND CT.DATAEXCLUSOREGISTRO IS NULL;";

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
            //Tipo 3 = buscar apresentações existentes na movimentação
            elseif($tipo==3 && $idmovimentacao!=0){
                //Monta o Select
                $sql = "SELECT CMTA.ID IDAPRES, CMTA.IDMOVIMENTACAO FROM cimic_transferencias_apres CMTA
                INNER JOIN cimic_transferencias CT ON CT.ID = CMTA.IDMOVIMENTACAO
                WHERE CT.ID = :idmovimentacao AND CMTA.IDEXCLUSOREGISTRO IS NULL AND CMTA.DATAEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idmovimentacao',$idmovimentacao, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                /*else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma Ordem de Saída existente! </li>");
                    echo json_encode($retorno);
                    exit();
                }*/

            }
            //Tipo 4 = buscar dados da apresentação
            elseif($tipo==4 && $idbanco!=0){
                //Monta o Select
                $sql = "SELECT CMTA.ID IDBANCO, CMTA.IDDESTINOAPRES IDLOCAL, CLA.NOME NOMELOCAL, CMTA.DATAAPRES, CMTA.IDMOTIVOAPRES, concat(LPAD(TOF.NUMERO, 4, '0'),'/',TOF.ANO) OFICIO, CMTA.IDOFICIOAPRES IDOFICIO
                FROM cimic_transferencias_apres CMTA
                INNER JOIN cimic_transferencias CT ON CT.ID = CMTA.IDMOVIMENTACAO
                INNER JOIN tab_oficios TOF ON TOF.ID = CMTA.IDOFICIOAPRES
                INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = CMTA.IDDESTINOAPRES
                WHERE CMTA.ID = :idbanco;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idbanco',$idbanco, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível obter dados da Apresentação ID $idbanco. </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 5 = buscar destinos existentes na movimentação
            elseif($tipo==5 && $idmovimentacao!=0){
                //Monta o Select
                $sql = "SELECT CTI.ID IDDEST, CTI.IDMOVIMENTACAO FROM cimic_transferencias_intermed CTI
                INNER JOIN cimic_transferencias CT ON CT.ID = CTI.IDMOVIMENTACAO
                WHERE CT.ID = :idmovimentacao AND CTI.IDEXCLUSOREGISTRO IS NULL AND CTI.DATAEXCLUSOREGISTRO IS NULL ORDER BY CTI.PRIMEIROLOCAL DESC, CTI.DESTINOFINAL ASC;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idmovimentacao',$idmovimentacao, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                /*else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma Ordem de Saída existente! </li>");
                    echo json_encode($retorno);
                    exit();
                }*/

            }
            //Tipo 6 = buscar dados do destino
            elseif($tipo==6 && $idbanco!=0){
                //Monta o Select
                $sql = "SELECT CTI.ID IDBANCO, CTI.IDDESTINOINTERM IDLOCAL, concat(UNT.ABREVIACAO, ' ',UN.NOMEUNIDADE) NOMEUNIDADE, CTI.DATAINTERM, concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) OFICIO, CASE CTI.PRIMEIROLOCAL WHEN 1 THEN 'checked' ELSE '' END PRIMEIROLOCAL, CASE CTI.DESTINOFINAL WHEN 1 THEN 'checked' ELSE '' END DESTINOFINAL, CTI.COMENTARIO, CTI.IDOFICIOINTERM IDOFICIO 
                FROM cimic_transferencias_intermed CTI
                INNER JOIN cimic_transferencias CT ON CT.ID = CTI.IDMOVIMENTACAO
                INNER JOIN tab_unidades UN ON UN.ID = CTI.IDDESTINOINTERM
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                INNER JOIN tab_oficios TOF ON TOF.ID = CTI.IDOFICIOINTERM
                WHERE CTI.ID = :idbanco;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idbanco',$idbanco, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível obter dados do Destino Intermediário ID $idbanco! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 7 = buscar dados da transferencia de retorno da cimic_transferencias
            elseif($tipo==7 && $idmovimentacao!=0){
                //Monta o Select
                $sql = "SELECT CT.ID, CT.IDPRESO,  16 IDTIPOMOV, 5 IDMOTIVOMOV, DATARETORNO DATARECEB, CTI.IDDESTINOINTERM IDPROCEDENCIA, concat(UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE) UNIDADE
                FROM cimic_transferencias CT
                INNER JOIN cimic_transferencias_intermed CTI ON CTI.IDMOVIMENTACAO = CT.ID
                INNER JOIN tab_unidades UN ON UN.ID = CTI.IDDESTINOINTERM
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                WHERE CT.ID = :idmovimentacao AND CTI.IDEXCLUSOREGISTRO IS NULL AND CTI.DATAEXCLUSOREGISTRO IS NULL AND CTI.PRIMEIROLOCAL = TRUE;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idmovimentacao',$idmovimentacao, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível obter dados da Movimentação de Recebimento $idmovimentacao! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 8 = buscar dados da transferencia cimic_recebimentos
            elseif($tipo==8 && $idmovimentacao!=0){
                //Monta o Select
                $sql = "SELECT CR.ID, CR.IDPRESO, CR.DATARECEB, EP.SEGURO, CR.IDPROCEDENCIA, concat(UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE) UNIDADE, CR.IDTIPOMOV, CR.IDMOTIVOMOV
                FROM cimic_recebimentos CR
                INNER JOIN entradas_presos EP ON EP.ID = CR.IDPRESO
                INNER JOIN tab_unidades UN ON UN.ID = CR.IDPROCEDENCIA
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                WHERE CR.ID = :idmovimentacao;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idmovimentacao',$idmovimentacao, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível obter dados da Movimentação de Recebimento $idmovimentacao! </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 8 = buscar dados da transferencia cimic_recebimentos
            elseif($tipo==9 && $idordem!=0){
                //Monta o Select
                $sql = "SELECT COT.ID VALOR, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO, ' - ', UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE, ' (', date_format(COT.DATASAIDA, '%W-%d/%m/%Y %H:%i'), ')') NOMEEXIBIR FROM cimic_ordens_transferencias COT
                INNER JOIN tab_ordemsaida TOS ON TOS.ID = COT.IDORDEM
                INNER JOIN tab_unidades UN ON UN.ID = COT.IDDESTINO
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                WHERE COT.ID = :idordem;";

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
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível obter dados da Movimentação de Recebimento $idmovimentacao! </li>");
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