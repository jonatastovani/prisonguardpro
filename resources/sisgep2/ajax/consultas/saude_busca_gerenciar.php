<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    $tipo = $_POST['tipo'];
    $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;
    $idbanco = isset($_POST['idbanco'])?$_POST['idbanco']:0;
    $idatend = isset($_POST['idatend'])?$_POST['idatend']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){ 
        try {
            //Busca tabela do Gerenciar Atendimentos
            if($tipo==1){
                $datainicio = $_POST['datainicio'];
                $datafinal = $_POST['datafinal'];
                $ordem = $_POST['ordem'];
                $buscatexto = $_POST['buscatexto'];
                $texto = $_POST['texto'];
                $textobusca = $_POST['textobusca'];
                $tipodata = $_POST['tipodata'];

                if($tipodata==1){
                    $wheredatas = "concat(REQ.DATAATEND, ' ', ATD.HORAATEND) >= ? AND concat(REQ.DATAATEND, ' ', ATD.HORAATEND) <= ?";
                }elseif($tipodata==2){
                    $wheredatas = "ATD.DATASOLICITACAO >= ? AND ATD.DATASOLICITACAO <= ?";
                }else{
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Tipo de intervalo de datas incorreto! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }

                if($ordem==1){
                    $ordem = 'MATRICULA';
                }elseif($ordem==2){
                    $ordem = 'NOME';
                }elseif($ordem==3){
                    $ordem = 'REQ.DATAATEND, ATD.HORAATEND';
                }elseif($ordem==4){
                    $ordem = 'DATASOLICITACAO';
                }else{
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Ordenação não informada corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }
            
                if($texto!=1 && $texto!=2){
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Opção de busca do texto não informado corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }
            
                if($buscatexto==1){
                    $textoinicio = '%';
                    $textofinal = '%';
                }elseif($buscatexto==2){
                    $textoinicio = '';
                    $textofinal = '';
                }elseif($buscatexto==3){
                    $textoinicio = '';
                    $textofinal = '%';
                }elseif($buscatexto==4){
                    $textoinicio = '%';
                    $textofinal = '';
                }else{
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Método de busca do texto não informado corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }

                $params=[];
                $arraywheres = [];
                
                //Adiciona na ordem os parâmetros
                for($i=0;$i<1;$i++){
                    array_push($params,"$datainicio 00:00:00");
                    array_push($params,"$datafinal 23:59:59");
                    // array_push($params,"$datafinal 23:59:59"); //Para a data do EP.EXCLUSOREGISTRO
                    
                    $where='';
                    if(strlen($textobusca)>0){
                        if($texto==1){
                            $palavas = retornaArrayPalavras($textobusca);
                        }else{
                            $palavas = array($textobusca);
                        }
                        
                        $where = "AND (";
                        foreach($palavas as $tex){
                            if($where != "AND ("){
                                $where .= " OR ";                        
                            }
                            
                            if($i==0){
                                $where .= "(UCASE(CAD.NOME) LIKE UCASE(?) OR EP.MATRICULA LIKE UCASE(?) OR UCASE(ATT.NOME) LIKE UCASE(?) OR ATD.HORAATEND LIKE (?))";
                                //Quantidade de substiuições que vão ser inseridas
                                $repeticao = 4;
                            }

                            for($iparams=0;$iparams<$repeticao;$iparams++){
                                array_push($params,$textoinicio.$tex.$textofinal);
                            }
                        }
                        $where .= ") ";
                    }
                    array_push($arraywheres,$where);
                }

                $sql = "SELECT ATD.ID IDATEND, ATD.IDPRESO, CAD.MATRICULA, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CAD.NOME ELSE EP.NOME END NOME, concat(REQ.DATAATEND, ' ', ATD.HORAATEND) DATAATEND, FUNCT_dados_raio_cela_preso(ATD.IDPRESO, CASE WHEN concat(REQ.DATAATEND, ' ', ATD.HORAATEND) IS NOT NULL THEN concat(REQ.DATAATEND, ' ', ATD.HORAATEND) ELSE ATD.DATASOLICITACAO END, 1) IDRAIO, FUNCT_dados_raio_cela_preso(ATD.IDPRESO, CASE WHEN concat(REQ.DATAATEND, ' ', ATD.HORAATEND) IS NOT NULL THEN concat(REQ.DATAATEND, ' ', ATD.HORAATEND) ELSE ATD.DATASOLICITACAO END, 2) RAIO, FUNCT_dados_raio_cela_preso(ATD.IDPRESO, CASE WHEN concat(REQ.DATAATEND, ' ', ATD.HORAATEND) IS NOT NULL THEN concat(REQ.DATAATEND, ' ', ATD.HORAATEND) ELSE ATD.DATASOLICITACAO END, 3) CELA, ATD.DATASOLICITACAO, ATD.IDTIPOATEND, ATT.NOME TIPO, ATD.IDSITUACAO, SIT.NOME SITUACAO, EP.SEGURO, ATD.DESCPEDIDO, NULL COR
                FROM enf_atendimentos ATD
                INNER JOIN entradas_presos EP ON EP.ID = ATD.IDPRESO
                LEFT JOIN cadastros CAD ON CAD.MATRICULA = EP.MATRICULA
                LEFT JOIN enf_atendimentos_requis REQ ON REQ.ID = ATD.IDREQ
                INNER JOIN chefia_atendimentostipo ATT ON ATT.ID = ATD.IDTIPOATEND
                INNER JOIN tab_situacao SIT ON SIT.ID = ATD.IDSITUACAO
                WHERE $wheredatas AND ATD.IDEXCLUSOREGISTRO IS NULL AND EP.IDEXCLUSOREGISTRO IS NULL " .$arraywheres[0]. "
                ORDER BY $ordem;";

                //echo json_encode($sql); exit();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                $retorno="";
                $contador = 0;

                for($i=0;$i<count($resultado);$i++){
                    
                    $idpreso = $resultado[$i]['IDPRESO'];
                    $dataatend = $resultado[$i]['DATAATEND'];
                    $datasolic = $resultado[$i]['DATASOLICITACAO'];
                    if($dataatend!=null){
                        $datamov = $dataatend;
                    }else{
                        $datamov = $datasolic;
                    }
                    
                    $idsituacao = $resultado[$i]['IDSITUACAO'];

                    $seguro = $resultado[$i]['SEGURO'];
                    $dadoscela = buscaDadosRaioCelaPreso($idpreso,$datamov,2);
                    $trabalho = $dadoscela['ESPECIAL'];

                    if($seguro==1){
                        $resultado[$i]['NOME'] .= " <span class='destaque-atencao'>(SEGURO)</span>";
                    }
                    if($trabalho==1){
                        $resultado[$i]['NOME'] .= " <span class='destaque-atencao'>(TRAB)</span>";
                    }

                    $cor = 'cor-enf';
                    if(in_array($idsituacao,array(6,13,18))){
                        $cor = "cor-fundo-comum-tr";
                    }elseif(in_array($idsituacao,array(19))){
                        $cor = "cor-cancelado-agend";
                    }elseif(in_array($idsituacao,array(7,8,9,15))){
                        $cor = "cor-cancelado";
                    }
                    $resultado[$i]['COR'] = $cor;
                }

                echo json_encode($resultado);
                exit();            
            }
            //Busca dados dos atendimentos da enfermaria da movimentação informada
            elseif($tipo==2){
                $idbanco = isset($_POST['idbanco'])?$_POST['idbanco']:0;
                $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;

                if($idbanco!=0){
                    $sql = "SELECT REQ.ID IDMOVIMENTACAO, ATD.ID IDTEND, ATD.IDPRESO, REQ.DATAATEND, ATD.HORAATEND, REQ.REQUISITANTE, ATD.IDSITUACAO, ATD.IDTIPOATEND
                    FROM enf_atendimentos ATD
                    LEFT JOIN enf_atendimentos_requis REQ ON REQ.ID = ATD.IDREQ
                    WHERE ATD.ID = :idbanco AND ATD.IDEXCLUSOREGISTRO IS NULL;";

                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->bindParam('idbanco',$idbanco,PDO::PARAM_INT);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();

                    if(count($resultado)){
                        $idmovimentacao=isset($resultado[0]['IDMOVIMENTACAO'])?$resultado[0]['IDMOVIMENTACAO']:0;
                        
                        if($idmovimentacao>0){

                            $sql = "SELECT REQ.ID IDMOVIMENTACAO, ATD.ID IDTEND, ATD.IDPRESO, REQ.DATAATEND, ATD.HORAATEND, REQ.REQUISITANTE, ATD.IDSITUACAO, REQ.IDTIPOATEND
                            FROM enf_atendimentos ATD
                            INNER JOIN enf_atendimentos_requis REQ ON REQ.ID = ATD.IDREQ
                            WHERE ATD.IDREQ = :idmovimentacao AND ATD.IDEXCLUSOREGISTRO IS NULL;";
            
                            $stmt = $GLOBALS['conexao']->prepare($sql);
                            $stmt->bindParam('idmovimentacao',$idmovimentacao,PDO::PARAM_INT);
                            $stmt->execute();
            
                            $resultado = $stmt->fetchAll();
                            $retorno=$resultado;
                        }
                        $retorno=$resultado;
                    }else{
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID informado. </li>");
                        echo json_encode($retorno);
                        exit();            
                    }
                }
            }
            //Busca dados do atendimento informado
            elseif($tipo==3 && $idatend>0){

                $sql = "SELECT ATD.ID, ATD.IDPRESO, ATD.DESCPEDIDO, ATD.DESCATEND, ATD.OBSERVACOES, concat(REQ.DATAATEND, ' ', ATD.HORAATEND) DATAATEND, ATD.DATASOLICITACAO, REQ.REQUISITANTE
                FROM enf_atendimentos ATD
                INNER JOIN enf_atendimentos_requis REQ ON REQ.ID = ATD.IDREQ
                WHERE ATD.ID = :idatend;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idatend',$idatend,PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetchAll();

                if(count($resultado)){
                    $retorno=$resultado;
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();            
                }
            }
            //Busca medicamentos do atendimento informado
            elseif($tipo==4 && $idatend>0){

                $sql = "SELECT * FROM enf_atendimentos_medic WHERE IDATEND = :idatend AND IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idatend',$idatend,PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetchAll();

                // if(count($resultado)){
                    $retorno=$resultado;
                // }else{
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID informado. </li>");
                //     echo json_encode($retorno);
                //     exit();            
                // }
            }
            //Busca tabela do Gerenciar Assistidos
            elseif($tipo==5){
                $datainicio = isset($_POST['datainicio'])?$_POST['datainicio']:'';
                $datafinal = isset($_POST['datafinal'])?$_POST['datafinal']:'';
                $ordem = isset($_POST['ordem'])?$_POST['ordem']:1;
                $buscatexto = isset($_POST['buscatexto'])?$_POST['buscatexto']:1;
                $opcaobusca = isset($_POST['opcaobusca'])?$_POST['opcaobusca']:1;
                $textobusca = isset($_POST['textobusca'])?$_POST['textobusca']:'';
                $periodo = isset($_POST['periodo'])?$_POST['periodo']:0;
                $situacao = isset($_POST['situacao'])?$_POST['situacao']:0;

                if($ordem==1){
                    $ordem = 'MATRICULA';
                }elseif($ordem==2){
                    $ordem = 'NOME';
                }elseif($ordem==3){
                    $ordem = 'RAIO';
                }else{
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Ordenação não informada corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }
                
                if($periodo==1){
                    $periodoentregue = 'AND EMAE.IDPERIODOENTREGUE = 1';
                    $periodoentrega = 'AND EMA.IDPERIODOENTREGA = 1';
                }elseif($periodo==2){
                    $periodoentregue = 'AND EMAE.IDPERIODOENTREGUE = 2';
                    $periodoentrega = 'AND EMA.IDPERIODOENTREGA = 2';
                }elseif($periodo==3){
                    $periodoentregue = 'AND EMAE.IDPERIODOENTREGUE = 3';
                    $periodoentrega = 'AND EMA.IDPERIODOENTREGA = 3';
                }elseif($periodo==0){
                    $periodoentregue = '';
                    $periodoentrega = '';
                }else{
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Período não informado corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }
            
                if($opcaobusca!=1 && $opcaobusca!=2){
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Opção de busca do texto não informado corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }
            
                if($buscatexto==1){
                    $textoinicio = '%';
                    $textofinal = '%';
                }elseif($buscatexto==2){
                    $textoinicio = '';
                    $textofinal = '';
                }elseif($buscatexto==3){
                    $textoinicio = '';
                    $textofinal = '%';
                }elseif($buscatexto==4){
                    $textoinicio = '%';
                    $textofinal = '';
                }else{
                    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Método de busca do texto não informado corretamente! Se o problema persistir, consulte o programador. </li>"));
                    exit();
                }

                $params=[];
                $arraywheres = [];
                //Caso esteja visualizando um dia e período específico para um preso
                $wherepreso = '';

                //Adiciona na ordem os parâmetros
                for($i=0;$i<2;$i++){
                    
                    if($i==0){
                        if(in_array($situacao,array(0,2))){
                            array_push($params,"$datainicio 00:00:00");
                            array_push($params,"$datafinal 23:59:59");
                        }else{
                            array_push($arraywheres,'');
                            continue;
                        }
                    }elseif($i==1){
                        if(in_array($situacao,array(0,1))){
                            array_push($params,$datafinal);
                            array_push($params,$datafinal);
                            array_push($params,$datafinal);
                            array_push($params,$datafinal);
                            array_push($params,$datafinal);
                            array_push($params,$datafinal);
                            array_push($params,$datafinal);
                        }else{
                            continue;
                        }
                    }
                    
                    $where='';
                    if(strlen($textobusca)>0){
                        if($opcaobusca==1){
                            $palavas = retornaArrayPalavras($textobusca);
                        }else{
                            $palavas = array($textobusca);
                        }
                        
                        $where = "AND (";
                        foreach($palavas as $tex){
                            if($where != "AND ("){
                                $where .= " OR ";                        
                            }
                            
                            if(in_array($i,array(0,1))){
                                $where .= "(UCASE(CAD.NOME) LIKE UCASE(?) OR EP.MATRICULA LIKE UCASE(?) OR UCASE(MED.NOME) LIKE UCASE(?) OR UCASE(PER.NOME) LIKE UCASE(?))";
                                //Quantidade de substiuições que vão ser inseridas
                                $repeticao = 4;
                            }

                            for($iparams=0;$iparams<$repeticao;$iparams++){
                                array_push($params,$textoinicio.$tex.$textofinal);
                            }
                        }
                        $where .= ") ";
                    }
                    array_push($arraywheres,$where);
                    //Caso esteja visualizando um dia e período específico para um preso
                    if($idpreso>0){
                        $wherepreso = 'AND EMA.IDPRESO = ?';
                        array_push($params,$idpreso);
                    }
                }

                $sql = '';
                if(in_array($situacao,array(0,2))){
                    $sql = "SELECT EMA.ID IDASS, EMA.IDMEDICAMENTO, MED.NOME NOMEMEDICAMENTO, EMAE.QTDENTREGUE QTDENTREGA, FORN.SIGLA UNIDADEFORN, EMA.IDPRESO, CAD.MATRICULA, CAD.NOME, date_format(EMAE.DATAENTREGUE,'%Y-%m-%d') DATAENTREGUE, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, EMAE.DATAENTREGUE, 1) IDRAIO, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, EMAE.DATAENTREGUE, 2) RAIO, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, EMAE.DATAENTREGUE, 3) CELA, EMAE.IDPERIODOENTREGUE IDPERIODO, PER.NOME PERIODO, 
                        (SELECT COUNT(EMAE1.ID) FROM enf_medic_assistido_entregue EMAE1
                        INNER JOIN enf_medic_assistido EMA1 ON EMA1.ID = EMAE1.IDASS
                        WHERE EMAE1.IDPERIODOENTREGUE = EMAE.IDPERIODOENTREGUE AND EMA1.IDPRESO = EMA.IDPRESO AND date_format(EMAE1.DATAENTREGUE,'%Y-%m-%d') = date_format(EMAE.DATAENTREGUE,'%Y-%m-%d') AND EMAE1.IDEXCLUSOREGISTRO IS NULL) QTDMED,
                    EMA.DATAINICIO, EMA.DATATERMINO, NULL COR, 2 ORDEM
                    FROM enf_medic_assistido_entregue EMAE
                    INNER JOIN enf_medic_assistido EMA ON EMA.ID = EMAE.IDASS
                    INNER JOIN enf_medicamentos MED ON MED.ID = EMA.IDMEDICAMENTO
                    INNER JOIN tab_unidadesfornecimento FORN ON FORN.ID = MED.IDUNIDADE
                    INNER JOIN entradas_presos EP ON EP.ID = EMA.IDPRESO
                    INNER JOIN cadastros CAD ON CAD.MATRICULA = EP.MATRICULA
                    INNER JOIN tab_periodos PER ON PER.ID = EMAE.IDPERIODOENTREGUE
                    WHERE EMAE.DATAENTREGUE >= ? AND EMAE.DATAENTREGUE <= ? AND EMAE.IDEXCLUSOREGISTRO IS NULL
                    ". $arraywheres[0] . " $periodoentregue $wherepreso ";
                }

                if($sql!='' && $situacao==0){
                    $sql .= ' UNION ';
                }

                if(in_array($situacao,array(0,1))){
                    $sql .= "SELECT EMA.ID IDASS, EMA.IDMEDICAMENTO, MED.NOME NOMEMEDICAMENTO, EMA.QTDENTREGA, FORN.SIGLA UNIDADEFORN, EMA.IDPRESO, CAD.MATRICULA, CAD.NOME, NULL DATAENTREGUE, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, CURRENT_TIMESTAMP, 1) IDRAIO, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, CURRENT_TIMESTAMP, 2) RAIO, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, CURRENT_TIMESTAMP, 3) CELA, EMA.IDPERIODOENTREGA IDPERIODO, PER.NOME PERIODO, 
                        (SELECT COUNT(ID) FROM enf_medic_assistido WHERE
                            ID NOT IN (SELECT DISTINCT EMAE2.IDASS FROM enf_medic_assistido_entregue EMAE2
                            WHERE date_format(EMAE2.DATAENTREGUE,'%Y-%m-%d') = CASE WHEN ? > CURRENT_DATE THEN CURRENT_DATE ELSE ? END AND EMAE2.IDEXCLUSOREGISTRO IS NULL)
                        AND IDPERIODOENTREGA = EMA.IDPERIODOENTREGA AND IDPRESO = EMA.IDPRESO AND (DATATERMINO >= ? OR DATATERMINO IS NULL) AND IDEXCLUSOREGISTRO IS NULL) QTDMED,
                    EMA.DATAINICIO, EMA.DATATERMINO, NULL COR, 1 ORDEM
                    FROM enf_medic_assistido EMA
                    INNER JOIN enf_medicamentos MED ON MED.ID = EMA.IDMEDICAMENTO
                    INNER JOIN tab_unidadesfornecimento FORN ON FORN.ID = MED.IDUNIDADE
                    INNER JOIN entradas_presos EP ON EP.ID = EMA.IDPRESO
                    INNER JOIN cadastros CAD ON CAD.MATRICULA = EP.MATRICULA
                    INNER JOIN tab_periodos PER ON PER.ID = EMA.IDPERIODOENTREGA
                    WHERE date_format(EMA.DATAINICIO,'%Y-%m-%d') <= ? AND (date_format(EMA.DATATERMINO,'%Y-%m-%d') >= ? OR EMA.DATATERMINO IS NULL) AND 
                        EMA.ID NOT IN (SELECT DISTINCT EMAE.IDASS
                        FROM enf_medic_assistido_entregue EMAE
                        WHERE date_format(EMAE.DATAENTREGUE,'%Y-%m-%d') = CASE WHEN ? > CURRENT_DATE THEN CURRENT_DATE ELSE ? END AND EMAE.IDEXCLUSOREGISTRO IS NULL)
                    AND EMA.IDEXCLUSOREGISTRO IS NULL AND EP.IDEXCLUSOREGISTRO IS NULL
                    ". $arraywheres[1] . " $periodoentrega $wherepreso";
                }

                $sql .= " ORDER BY ORDEM, DATAENTREGUE DESC, $ordem, IDPERIODO, RAIO, CELA;";

                // echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> $sql </li>"));exit();
                // echo json_encode($sql); exit();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                $retorno="";

                // echo json_encode($resultado);
                // exit();            
                
                for($i=0;$i<count($resultado);$i++){
                    
                    $dataconsulta = date('Y-m-d H:i:s');
                    if($resultado[$i]['DATAENTREGUE']!=null){
                        $dataconsulta = $resultado[$i]['DATAENTREGUE'];
                    }
                    $idpreso = $resultado[$i]['IDPRESO'];                   
                    // $idsituacao = $resultado[$i]['IDSITUACAO'];

                    // $seguro = 0;
                    $seguro = buscaCelaExcessao(1,$dataconsulta,$resultado[$i]['IDRAIO'],$resultado[$i]['CELA']);
                    // $seguro = $dadoscela['ESPECIAL'];
                    // $trabalho = 0;
                    $trabalho = buscaCelaExcessao(2,$dataconsulta,$resultado[$i]['IDRAIO'],$resultado[$i]['CELA']);
                    // $trabalho = $dadoscela['ESPECIAL'];

                    if($seguro==1){
                        $resultado[$i]['NOME'] .= " <span class='destaque-atencao'>(SEGURO)</span>";
                    }
                    if($trabalho==1){
                        $resultado[$i]['NOME'] .= " <span class='destaque-atencao'>(TRAB)</span>";
                    }

                    $cor = 'cor-pendente';
                    if($resultado[$i]['DATAENTREGUE']!=null){
                        $cor = "cor-entregue";
                    }
                    $resultado[$i]['COR'] = $cor;
                }

                echo json_encode($resultado);
                exit();            
            }
            //Busca medicamentos assistidos do preso informado
            elseif($tipo==6 && $idpreso>0){

                $sql = "SELECT EMA.*, MED.NOME NOMEMEDICAMENTO
                FROM enf_medic_assistido EMA
                INNER JOIN enf_medicamentos MED ON MED.ID = EMA.IDMEDICAMENTO
                WHERE EMA.IDPRESO = :idpreso AND (date_format(EMA.DATATERMINO,'%Y-%m-%d') >= CURRENT_DATE OR EMA.DATATERMINO IS NULL) AND EMA.IDEXCLUSOREGISTRO IS NULL AND MED.IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idpreso',$idpreso,PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetchAll();

                // if(count($resultado)){
                    $retorno=$resultado;
                // }else{
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Nenhum registro foi encontrado para o ID informado. </li>");
                //     echo json_encode($retorno);
                //     exit();            
                // }
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
