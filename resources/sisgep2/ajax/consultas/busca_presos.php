<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    $tipo = $_POST['tipo'];
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    $matric = isset($_POST['matric'])?$_POST['matric']:0;
    $tipobusca = isset($_POST['tipobusca'])?$_POST['tipobusca']:0;
    $valor = isset($_POST['valor'])?$_POST['valor']:0;
    //tiporetorno 1 = Para Select, tiporetorno 2 = Array
    $tiporetorno = isset($_POST['tiporetorno'])?$_POST['tiporetorno']:1;
    $idselecionar = isset($_POST['idselecionar'])?$_POST['idselecionar']:0;
    //Data para buscar os dados no período informado. Caso não for setado valor, se busca a última informação.
    $datapesq = isset($_POST['datapesq'])?$_POST['datapesq']:0;
    $blnvisuchefia = isset($_POST['blnvisuchefia'])?$_POST['blnvisuchefia']:false;

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            //Busca dados do preso pelo ID informado
            if($tipo==1 && $idpreso!=0){               
                $sql = "SELECT * FROM entradas_presos EP WHERE EP.ID = :idpreso";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
                $stmt->execute();

                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);

                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    if($datapesq==0){
                        $data = date('Y-m-d');
                        $datahora = date('Y-m-d H:i:59');
                    }else{
                        $data = retornaDadosDataHora($datapesq,1);
                        $datahora = $datapesq;
                    }
                    $matric = $resultado[0]['MATRICULA'];
                    $vinculado = $resultado[0]['MATRICULAVINCULADA'];

                    $caminhofoto = $vinculado?baixarFotoServidor($idpreso,1,"../../"):"imagens/sem-foto.png";

                    $nome = $vinculado?buscaDadosLogPorPeriodo($matric,'NOME',3,$data):buscaDadosLogPorPeriodo($idpreso,'NOME',5,$data);

                    $mae = $vinculado?buscaDadosLogPorPeriodo($matric,'MAE',3,$data):buscaDadosLogPorPeriodo($idpreso,'MAE',5,$data);

                    $pai = $vinculado?buscaDadosLogPorPeriodo($matric,'PAI',3,$data):buscaDadosLogPorPeriodo($idpreso,'PAI',5,$data);

                    $dadoscela = buscaDadosRaioCelaPreso($idpreso,$datahora,1);
                    if($dadoscela!=false){
                        $raio = $dadoscela['RAIO'];
                        $cela = $dadoscela['CELA'];
                        $seguro = $dadoscela['ESPECIAL'];
                    }else{
                        $raio = 'N/C';
                        $cela = 'N/C';    
                        $seguro = 0;
                    }
                    //Se o preso estiver como seguro na cela ou no registro de ID do Preso, então retorna-se que o preso é do seguro;
                    if($resultado[0]['SEGURO']==1){
                        $seguro = 1;
                    }

                    $retorno = array(
                        'IDPRESO'=>$idpreso,
                        'NOME'=>$nome,
                        'MATRICULA'=>$matric,
                        'MAE'=>$mae,
                        'PAI'=>$pai,
                        'RAIO'=>$raio,
                        'CELA'=>$cela,
                        'RAIOCELA'=>"$raio/$cela",
                        'SEGURO'=>$seguro,
                        'FOTO'=>$caminhofoto
                    );

                    echo json_encode(array($retorno));
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum preso encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca listagem de presos
            elseif($tipo==2 && $tipobusca!=0 && $valor!=0){

                //Busca somente presos que estão na unidade
                if($tipobusca==1){
                    // $sql = "SELECT CD.IDPRESO, CD.IDPRESO VALOR, CD.MATRICULA, CONCAT(MID(CD.MATRICULA, 1, LENGTH(CD.MATRICULA)-1), '-', MID(CD.MATRICULA, LENGTH(CD.MATRICULA), 1), ' - ', CD.NOME) NOMEEXIBIR FROM cadastros CD WHERE IDPRESO <> 0 AND IDPRESO IS NOT NULL;";
                    $sql = "SELECT CD.IDPRESO, CD.MATRICULA, CD.IDPRESO VALOR, CD.NOME, NULL NOMEEXIBIR FROM cadastros CD WHERE IDPRESO <> 0 AND IDPRESO IS NOT NULL;";
                }
                //Busca somente presos que são do raio informado
                elseif($tipobusca==2){
                    $idvisualizacao = $_POST['idvisualizacao'];
                    $arrayVisualizacao = retornaRaiosDaVisualizacao($idvisualizacao,3,$blnvisuchefia);

                    // $sql = "SELECT DISTINCT CD.IDPRESO, CD.IDPRESO VALOR, CD.MATRICULA, CONCAT(MID(CD.MATRICULA, 1, LENGTH(CD.MATRICULA)-1), '-', MID(CD.MATRICULA, LENGTH(CD.MATRICULA), 1), ' - ', CD.NOME) NOMEEXIBIR
                    // FROM cadastros CD
                    // LEFT JOIN cadastros_mudancacela CADMC ON CD.IDPRESO = CADMC.IDPRESO
                    // WHERE CD.IDPRESO <> 0 AND CD.IDPRESO IS NOT NULL
                    // AND RAIOALTERADO IS NULL AND CADMC.RAIO IN ($arrayVisualizacao)
                    // ORDER BY CD.MATRICULA, CADMC.ID DESC;";

                    $sql = "SELECT DISTINCT EP.ID IDPRESO, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.MATRICULA ELSE EP.MATRICULA END MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, EP.ID VALOR, NULL NOMEEXIBIR
                    FROM entradas_presos EP
                    LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                    INNER JOIN cadastros_mudancacela CADMC ON CADMC.IDPRESO = EP.ID
                    WHERE CADMC.RAIO IN ($arrayVisualizacao) AND CADMC.RAIOALTERADO IS NULL AND EP.IDEXCLUSOREGISTRO IS NULL ORDER BY CD.MATRICULA, CADMC.ID DESC;";

                }
                //Busca todos os presos que já passaram na unidade
                elseif($tipobusca==3){
                    // $sql = "SELECT MATRICULA VALOR, NULL NOMEEXIBIR, MATRICULA, NOME FROM cadastros WHERE IDEXCLUSOREGISTRO IS NULL ORDER BY MATRICULA";
                    $sql = "SELECT DISTINCT CD.MATRICULA VALOR, NULL NOMEEXIBIR, CD.MATRICULA, CD.NOME FROM cadastros CD
                    INNER JOIN entradas_presos EP ON EP.MATRICULA = CD.MATRICULA WHERE EP.IDEXCLUSOREGISTRO IS NULL ORDER BY MATRICULA";
                }
                
                //Retorna como valor o ID do preso
                $coluna = "IDPRESO";
                if($valor==1){
                    $coluna = "IDPRESO";
                }
                //Retorna como valor a matrícula do preso
                elseif($valor==2){
                    $coluna = "MATRICULA";
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll();

                for($i=0;$i<count($resultado);$i++){
                    $matricula = '';
                    if($resultado[$i]['MATRICULA']!=NULL){
                        $matricula = midMatricula($resultado[$i]['MATRICULA'],3);
                        $matricula .= " - ";
                    }
                    $resultado[$i]['NOMEEXIBIR'] = $matricula . $resultado[$i]['NOME'];
                }

                $retorno = "<option value='0'>Selecione</option>";
                if($tiporetorno==1){
                    foreach($resultado as $dados){
                        $selected='';
                        if($idselecionar==$dados[$coluna]){
                            $selected = ' selected';
                        }
                        $retorno .= "<option value=".$dados[$coluna]."$selected>".$dados['NOMEEXIBIR']."</option>";
                    }
                }else{
                    $retorno = $resultado;
                }

                echo json_encode($retorno);
                exit();
            }
            //Consulta o id ou matricula selecionada para ver se existe
            elseif($tipo==3 && $valor!=0){

                $params = [];
                //Retorna como valor o ID do preso
                if($valor==1){
                    $coluna = "EP.ID";
                    array_push($params,$idpreso);
                }
                //Retorna como valor a matrícula do preso
                elseif($valor==2){
                    $coluna = "CD.MATRICULA";
                    array_push($params,$matric);
                }
                
                $sql = "SELECT EP.ID IDPRESO, CD.MATRICULA, CD.NOME, CD.MATRICULA VALOR FROM entradas_presos EP
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA WHERE $coluna = ? AND EP.IDEXCLUSOREGISTRO IS NULL ORDER BY EP.ID DESC LIMIT 1";
                
                // echo $sql;

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $resultado = $stmt->fetchAll();

                // var_dump($resultado);
                echo json_encode($resultado);
                exit();
            }
            //Consulta a matrícula para ver se existe alguma passagem.
            elseif($tipo==4 && $matric!=0){
                //Monta o Select
                $sql = "SELECT * FROM cadastros CD WHERE MATRICULA = :matric;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('matric',$matric, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);

                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('OK' => "<li class = 'mensagem-exito'> Nenhuma informação encontrada para a matrícula ".midMatricula($matric,3).", mas você pode inserir os dados para a inclusão deste novo preso! </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca os telefones salvos para o ID informado
            elseif($tipo==5 && $idpreso!=0){
                //Monta o Select
                $sql = "SELECT * FROM cadastros_telefones
                WHERE IDPRESO = :idpreso AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso',$idpreso, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);

                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
            }
            //Busca os vulgos salvos para o ID informado
            elseif($tipo==6 && $idpreso!=0){
                //Monta o Select
                $sql = "SELECT * FROM cadastros_vulgos
                WHERE IDPRESO = :idpreso AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso',$idpreso, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);

                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
            }
            //Busca listagem de presos (recebimento ou retorno de presos)
            elseif($tipo==7){
                // Traz uma lista personalizada com os presos que estão para retornar (TIPO 1), presos na unidade (TIPO 2) e presos que não foram inclusos ainda (TIPO 3). Os presos não inclusos são os presos que não possuem o LANCADOCIMIC = TRUE, mas é OBRIGATÓRIO possuir MATRICULA.

                $sql = "#DROP TABLE IF EXISTS consulta_retorno;
                CREATE TEMPORARY TABLE consulta_retorno (
                ID INT auto_increment, primary key(ID),
                IDPRESO INT NOT NULL UNIQUE,
                DATARETORNO DATE DEFAULT NULL
                ) DEFAULT CHAR SET UTF8 ENGINE = MEMORY;
                
                INSERT INTO consulta_retorno (IDPRESO, DATARETORNO) SELECT CT.IDPRESO, CT.DATARETORNO TIPO FROM cimic_transferencias CT
                INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
                INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                WHERE IDTIPOMOV = 6 AND CD.IDPRESO IS NOT NULL AND CT.IDEXCLUSOREGISTRO IS NULL;
                
                #DROP TABLE IF EXISTS consulta_na_unidade;
                CREATE TEMPORARY TABLE consulta_na_unidade (
                ID INT auto_increment, primary key(ID),
                IDPRESO INT NOT NULL UNIQUE
                ) DEFAULT CHAR SET UTF8 ENGINE = MEMORY;
                
                INSERT INTO consulta_na_unidade (IDPRESO) SELECT CD.IDPRESO FROM cadastros CD
                INNER JOIN entradas_presos EP ON EP.ID = CD.IDPRESO
                WHERE EP.ID NOT IN (SELECT IDPRESO FROM consulta_retorno);";
                
                $retorno=[];
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                
                $sql = "SELECT CR.IDPRESO, CD.MATRICULA, CD.NOME, date_format(CR.DATARETORNO, '%d/%m/%Y') DATARETORNO FROM consulta_retorno CR
                INNER JOIN entradas_presos EP ON EP.ID = CR.IDPRESO
                INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA ORDER BY NOME;";

                $presosretorno='';
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                if($tiporetorno==1){
                    foreach($resultado as $dados){
                        $dataretorno = '';
                        if($dados['DATARETORNO']!=null){
                            $dataretorno = " (Retorno ".$dados['DATARETORNO'].")";
                        }
                        $presosretorno .= "<option value=".$dados['IDPRESO'].">".midMatricula($dados['MATRICULA'],3)." - ".$dados['NOME']."$dataretorno</option>";
                    }
                }else{
                    $presosretorno = $resultado;
                }

                $sql = "SELECT CNU.IDPRESO, CD.MATRICULA, CD.NOME FROM consulta_na_unidade CNU
                INNER JOIN entradas_presos EP ON EP.ID = CNU.IDPRESO
                INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA ORDER BY NOME;";

                /*$presosunidade='';
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                if($tiporetorno==1){
                    foreach($resultado as $dados){
                        $presosunidade .= "<option value=".$dados['IDPRESO'].">".midMatricula($dados['MATRICULA'],3)." - ".$dados['NOME']."</option>";
                    }
                }else{
                    $presosunidade = $resultado;
                }*/

                $sql = "SELECT ID IDPRESO, MATRICULA, NOME FROM entradas_presos WHERE ID NOT IN (SELECT IDPRESO FROM consulta_retorno) AND ID NOT IN (SELECT IDPRESO FROM consulta_na_unidade) AND MATRICULA NOT IN (SELECT MATRICULA FROM cadastros WHERE IDPRESO IS NOT NULL) AND MATRICULA IS NOT NULL AND LANCADOCIMIC = FALSE AND IDEXCLUSOREGISTRO IS NULL ORDER BY NOME;";

                $presosprovisorios='';
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                if($tiporetorno==1){
                    foreach($resultado as $dados){
                        $presosprovisorios .= "<option value=".$dados['IDPRESO'].">".midMatricula($dados['MATRICULA'],3)." - ".$dados['NOME']."</option>";
                    }
                }else{
                    $presosprovisorios = $resultado;
                }

                // Monta o retorno
                if($tiporetorno==1){
                    $retorno = '';
                    if($presosprovisorios!=''){
                        $retorno = "<optgroup label='Presos Provisórios'>$presosprovisorios</optgroup>";
                    }
                    if($presosretorno!=''){
                        $retorno .= "<optgroup label='Presos em Trânsito Judicial'>$presosretorno</optgroup>";
                    }
                    // if($presosunidade!=''){
                    //     $retorno .= "<optgroup label='Presos na Unidade'>$presosunidade</optgroup>";
                    // }
                }else{
                    $retorno = [];
                    if(count($presosprovisorios)){
                        foreach($presosprovisorios as $dados){
                            array_push($retorno,$dados);
                        }
                    }
                    if(count($presosretorno)){
                        foreach($presosretorno as $dados){
                            array_push($retorno,$dados);
                        }
                    }
                    // if(count($presosunidade)){
                    //     foreach($presosunidade as $dados){
                    //         array_push($retorno,$dados);
                    //     }
                    // }
                }

                echo json_encode($retorno);
                exit();
            }
            //Busca presos sem cela atribuída
            elseif($tipo==8){
                $sql = "SELECT EP.*, GSA.NOME ORIGEM, E.DATAENTRADA FROM entradas_presos EP 
                INNER JOIN entradas E ON E.ID = EP.IDENTRADA
                INNER JOIN codigo_gsa GSA ON GSA.ID = E.IDORIGEM
                WHERE EP.ID NOT IN (SELECT IDPRESO FROM cadastros_mudancacela WHERE IDEXCLUSOREGISTRO IS NULL) AND EP.ID > 0 AND EP.IDEXCLUSOREGISTRO IS NULL AND E.IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                $retorno = $stmt->fetchAll();
            }
            //Busca todas inclusões do preso
            elseif($tipo==9){
                
                $params = [];
                if($idpreso!=0){
                    $coluna = "EP.ID";
                    $params = [$idpreso];
                }
                elseif($matric!=0){
                    $coluna = "EP.MATRICULA";
                    $params = [$matric];
                }else{
                    echo array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhuma IDPreso ou Matrícula foram informada! </li>");
                    exit();
                }
                
                $params = [$matric];

                $sql = "SELECT EP.ID IDPRESO, GSA.ID IDORIGEM, GSA.NOME ORIGEM, E.DATAENTRADA, TM.NOME TIPOMOV, MM.NOME MOTIVOMOV, EP.DATAPRISAO
                FROM entradas_presos EP
                INNER JOIN entradas E ON E.ID = EP.IDENTRADA
                INNER JOIN codigo_gsa GSA ON GSA.ID = E.IDORIGEM
                INNER JOIN tab_movimentacoestipo TM ON TM.ID = EP.IDTIPOMOV
                INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = EP.IDMOTIVOMOV
                WHERE $coluna = ? AND EP.IDEXCLUSOREGISTRO IS NULL AND E.IDEXCLUSOREGISTRO IS NULL
                ORDER BY E.DATAENTRADA DESC;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $retorno = $stmt->fetchAll();

                if(!count($retorno)){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma movimentação foi encontrada para a matricula informada! </li>");
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

    echo json_encode($retorno);
    exit();

    exit;
    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." </li>"));exit();
