<?php

//Retorna uma string com as ARTIGOS existentes para ser inserido no select
function inserirArtigos(){
    include_once "configuracoes/conexao.php";

    $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM tab_artigos WHERE IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL ORDER BY NOME;";
    $retorno = "";
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        
        $retorno = "<option value=0>Selecione</option>";
        foreach($resultado as $dados){
            $retorno .= "<option value=".$dados['VALOR'].">".$dados['NOMEEXIBIR']."</option>";
        }
    }else{
        $retorno = "<option value='0'>".$conexaoStatus."</option>";
    }
    return $retorno;
}

//Verifica transferência de trânsito judicial
/*idpreso = ID do preso
datamov = Data da movimentação para comparar em caso de estar inserindo o cadastro (0 para somente consulta se existe saída do preso)
intipos = string de  IDs dos tipos a serem consultados (separados por virgula)
blnretorno = true para se caso a consulta realizada é para verificar a data de retorno*/
function consultarTransferenciasPreso($idpreso, $datamov, $intipos, $blnretorno=false){
    $retorno = false;
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            if($blnretorno==true){
                $strrealizado = "REALIZADORETORNO";
                $destinoorigem = "Origem";
            }else{
                $strrealizado = "REALIZADOSAIDA";
                $destinoorigem = "Destino";
            }
            $sql = "SELECT CT.IDPRESO, CT.ID IDMOV, CD.NOME, UN.CODIGO, concat(UNT.NOME, ' de ', UN.NOMEUNIDADE) ORIGEM, MM.NOME MOTIVOMOV, MT.NOME TIPOMOV, CT.DATARETORNO, COT.ID IDORDEM,
            COT.DATASAIDA, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) ORDEM
            FROM cimic_transferencias CT
            INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
            INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN cimic_transferencias_intermed CTI ON CTI.IDMOVIMENTACAO = CT.ID
            INNER JOIN tab_unidades UN ON UN.ID = CTI.IDDESTINOINTERM
            INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
            INNER JOIN tab_movimentacoestipo MT ON MT.ID = CT.IDTIPOMOV
            INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = CT.IDMOTIVOMOV
            INNER JOIN cimic_ordens_transferencias COT ON COT.ID = CT.IDORDEMSAIDAMOV
            INNER JOIN tab_ordemsaida TOS ON TOS.ID = COT.IDORDEM         
            WHERE CD.IDPRESO = :idpreso
            AND CT.IDTIPOMOV IN ($intipos) AND CT.$strrealizado = FALSE
            AND CTI.PRIMEIROLOCAL = TRUE AND CTI.IDEXCLUSOREGISTRO IS NULL
            AND CT.IDEXCLUSOREGISTRO IS NULL AND COT.IDEXCLUSOREGISTRO IS NULL;";
            
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
    
            if(count($resultado)){
                $nome = $resultado[0]['NOME'];
                $tipomov = $resultado[0]['TIPOMOV'];
                $motivo = $resultado[0]['MOTIVOMOV'];
                $idmovimentacao = $resultado[0]['IDMOV'];
                $origem = $resultado[0]['ORIGEM'];
                $datasaida = $resultado[0]['DATASAIDA'];
                $dataretorno = $resultado[0]['DATARETORNO'];
                $ordem = $resultado[0]['ORDEM'];
                $idordem = $resultado[0]['IDORDEM'];
                if($dataretorno!=null){
                    $strdataretorno = retornaDadosDataHora($dataretorno,2);
                }else{
                    $strdataretorno = 'Não definida';
                }
                $info = "\r\rDados da Transferência:\rUnidade $destinoorigem: $origem;\rOrdem Saída: $ordem;\rData de Saída: ".retornaDadosDataHora($datasaida,12).";\rData prevista de retorno: $strdataretorno;\rTipo de movimentação: $tipomov;\rMotivo da movimentação: $motivo;";
                $obs = "\r\rObs.: Não é possível inserir outra data de retorno ou recebimento sem antes o preso retornar para a unidade.";

                if($blnretorno==true){
                    if($dataretorno!=null && $datamov!=$dataretorno){
                        // Existe a data de retorno, mas é diferente da que está se inserindo.
                        $retorno = array('MSGCONFIR' => "O preso $nome possui uma $tipomov com retorno para ".retornaDadosDataHora($dataretorno,2).".", 'CONFIR' => 3, 'IDMOV'=>$idmovimentacao, 'INFO'=>$info.$obs, 'DATASAIDA' => $datasaida, 'DATARETORNO' => $dataretorno);
                    }elseif($dataretorno!=null){
                        // Existe a data de retorno, e é igual a que está se inserindo.
                        $retorno = array('MSGCONFIR' => "O preso $nome já está previsto para retornar na data inserida.", 'CONFIR' => 2, 'IDMOV'=>$idmovimentacao, 'INFO'=>$info.$obs, 'DATASAIDA' => $datasaida, 'DATARETORNO' => $dataretorno);
                    }else{
                        // Não existe a data de retorno, então se for inserir um retorno será inserido nesta movimentação em aberto.
                        $retorno = array('MSGCONFIR' => "O preso $nome possui uma $tipomov sem data para retorno.", 'CONFIR' => 4, 'IDMOV'=>$idmovimentacao, 'INFO'=>$info.$obs, 'DATASAIDA' => $datasaida, 'DATARETORNO' => $dataretorno);
                    }
                }else{
                    if($datamov==0){
                        //Se for 0 é porque é somente uma consulta se existe saída do preso
                        $retorno = array('MSGCONFIR' => "O preso $nome possui uma $tipomov com saída para ".retornaDadosDataHora($datasaida,2).".", 'CONFIR' => 1, 'IDMOV'=>$idmovimentacao, 'INFO'=>$info, 'DATASAIDA' => $datasaida, 'DATARETORNO' => $dataretorno, 'IDORDEM' => $idordem);
                    }
                    elseif($datamov!=$datasaida){
                        // Existe a saída, mas é diferente da que está se inserindo.
                        $retorno = array('MSGCONFIR' => "O preso $nome possui uma $tipomov com saída para ".retornaDadosDataHora($datasaida,2).".", 'CONFIR' => 3, 'IDMOV'=>$idmovimentacao, 'INFO'=>$info.$obs, 'DATASAIDA' => $datasaida, 'DATARETORNO' => $dataretorno, 'IDORDEM' => $idordem);
                    }else{
                        // Existe a data de retorno, e é igual a que está se inserindo.
                        $retorno = array('MSGCONFIR' => "O preso $nome já está previsto para retornar na data inserida.", 'CONFIR' => 2, 'IDMOV'=>$idmovimentacao, 'INFO'=>$info.$obs, 'DATASAIDA' => $datasaida, 'DATARETORNO' => $dataretorno, 'IDORDEM' => $idordem);
                    }
                }
            }

            return $retorno;
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
}

//Verifica recebimento existente do preso
function consultarRecebimentoPreso($idpreso, $datamov){
    $retorno = false;
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            $sql = "SELECT CR.IDPRESO, CR.ID IDMOV, EP.NOME, EP.MATRICULA, UN.CODIGO, concat(UNT.NOME, ' de ', UN.NOMEUNIDADE) ORIGEM, MM.NOME MOTIVOMOV, CR.DATARECEB, MT.NOME TIPOMOV
            FROM cimic_recebimentos CR
            INNER JOIN entradas_presos EP ON EP.ID = CR.IDPRESO
            INNER JOIN tab_unidades UN ON UN.ID = CR.IDPROCEDENCIA
            INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
            INNER JOIN tab_movimentacoestipo MT ON MT.ID = CR.IDTIPOMOV
            INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = CR.IDMOTIVOMOV
            WHERE CR.IDPRESO = :idpreso AND CR.REALIZADO = FALSE;";
            
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
    
            if(count($resultado)){
                $nome = $resultado[0]['NOME'];
                $motivo = $resultado[0]['MOTIVOMOV'];
                $tipomov = $resultado[0]['TIPOMOV'];
                $idmovimentacao = $resultado[0]['IDMOV'];
                $origem = $resultado[0]['ORIGEM'];
                $datareceb = $resultado[0]['DATARECEB'];
                $strdatareceb = retornaDadosDataHora($datareceb,2);

                $info = "\r\rDados do Recebimento:\rUnidade Origem: $origem;\rData prevista de recebimento: $strdatareceb;\rTipo de movimentação: $tipomov;\rMotivo da movimentação: $motivo;";

                if($datareceb!=null && $datamov!=$datareceb){
                    // Existe a data de recebimento, mas é diferente da que está se inserindo.
                    $retorno = array('MSGCONFIR' => "O preso $nome possui um(a) $tipomov, motivo: $motivo com recebimento para $strdatareceb.", 'CONFIR' => 5, 'IDMOV'=>$idmovimentacao, 'INFO'=>$info);
                }elseif($datareceb!=null){
                    // Existe a data de recebimento e é igual a que está se inserindo.
                    $retorno = array('MSGCONFIR' => "O preso $nome já está previsto para ser recebido na data inserida.", 'CONFIR' => 6, 'IDMOV'=>$idmovimentacao, 'INFO'=>$info);
                }
            }

            return $retorno;
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
}

//Verifica apresentações locais agendadas
function consultarApresentacoesPreso($idpreso, $datahorainicio, $datahoratermino=0){
    $retorno = "";
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            $params=[];
            for($i=0;$i<2;$i++){
                array_push($params,$idpreso);
                array_push($params,$datahorainicio);
                if($datahoratermino!=0){
                    array_push($params,$datahorainicio);
                    if($i==0){
                        $strterminoexterna = "AND concat(date_format(COA.DATASAIDA, '%Y-%m-%d'), ' ', CA.HORAAPRES) <= ?";
                    }else{
                        $strterminointerna = "AND concat(date_format(CAI.DATASAIDA, '%Y-%m-%d'), ' ', CAIP.HORAAPRES) <= ?";
                    }
                }else{
                    $strterminoexterna = '';
                    $strterminointerna = '';
                }
            }


            $sql = "SELECT CA.IDPRESO, CD.NOME, CD.MATRICULA, CLA.NOME DESTINO, concat(date_format(COA.DATASAIDA, '%Y-%m-%d'), ' ', CA.HORAAPRES) DATAAPRES, COA.DATASAIDA, concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) OFICIO, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) ORDEM, CA.PROCESSO, 'Externa' LOCALAPRES
            FROM cimic_apresentacoes CA
            INNER JOIN entradas_presos EP ON EP.ID = CA.IDPRESO
            INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN cimic_ordens_apresentacoes COA ON COA.ID = CA.IDORDEMSAIDAMOV
            INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = COA.IDDESTINO
            INNER JOIN tab_oficios TOF ON TOF.ID = CA.IDOFICIOAPRES
            INNER JOIN tab_ordemsaida TOS ON TOS.ID = COA.IDORDEM
            WHERE CA.IDPRESO = ? AND concat(date_format(COA.DATASAIDA, '%Y-%m-%d'), ' ', CA.HORAAPRES) >= ? $strterminoexterna
            AND CA.REALIZADOSAIDA = FALSE AND COA.IDEXCLUSOREGISTRO IS NULL AND CA.IDEXCLUSOREGISTRO IS NULL
            
            UNION

            SELECT CAIP.IDPRESO, CD.NOME, CD.MATRICULA, CLA.NOME DESTINO, concat(date_format(CAI.DATASAIDA, '%Y-%m-%d'), ' ', CAIP.HORAAPRES) DATAAPRES, concat(date_format(CAI.DATASAIDA, '%Y-%m-%d'), ' ', CAIP.HORAAPRES) DATASAIDA, concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) OFICIO, 'N/C' ORDEM, CAIP.PROCESSO, 'Interna' LOCALAPRES
            FROM cimic_apresentacoes_internas_presos CAIP
            INNER JOIN entradas_presos EP ON EP.ID = CAIP.IDPRESO
            INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN cimic_apresentacoes_internas CAI ON CAI.ID = CAIP.IDAPRES
            INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = CAI.IDDESTINO
            INNER JOIN tab_oficios TOF ON TOF.ID = CAIP.IDOFICIOAPRES
            WHERE CAIP.IDPRESO = ? AND concat(date_format(CAI.DATASAIDA, '%Y-%m-%d'), ' ', CAIP.HORAAPRES) >= ? $strterminointerna
            AND CAIP.REALIZADOSAIDA = FALSE AND CAI.IDEXCLUSOREGISTRO IS NULL AND CAIP.IDEXCLUSOREGISTRO IS NULL;";
            
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();
    
            $qtd = count($resultado);
            if($qtd){

                $nome = $resultado[0]['NOME'];
                if($qtd==1){
                    $retorno = "O preso $nome possui uma apresentação:";
                }else{
                    $retorno = "O preso $nome possui $qtd apresentações:";
                }

                foreach($resultado as $dados){
                    $ordem = $dados['ORDEM'];
                    $internaexterna = $dados['LOCALAPRES'];
                    $datasaida = retornaDadosDataHora($dados['DATASAIDA'],12);
                    $destino = $dados['DESTINO'];
                    $oficio = $dados['OFICIO'];
                    $horaapres = retornaDadosDataHora($dados['DATAAPRES'],8);
                    $minutoapres = retornaDadosDataHora($dados['DATAAPRES'],9);
                    if($minutoapres==0){
                        $minutoapres='';
                    }
                    $processo = $dados['PROCESSO'];
                    if($processo!=null){
                        $processo="\rProcesso: $processo;";
                    }
                    $info = "\r\rDados da Apresentação:\rTipo de Apresentação: $internaexterna;\rDestino: $destino;\rOrdem Saída: $ordem;\rData de Saída: $datasaida;\rHorário da Apresentação: ".$horaapres."h$minutoapres;\rOfício de Apresentação: $oficio;$processo";

                    $retorno .= $info;
                }

                $retorno = array('MSGCONFIR' => $retorno, 'CONFIR' => 1);
                return $retorno;
            }

            return false;
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
}


// echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." </li>"));exit();
