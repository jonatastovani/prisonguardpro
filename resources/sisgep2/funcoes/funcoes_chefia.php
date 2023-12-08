<?php


$id_boletim = isset($_SESSION['id_boletim'])?$_SESSION['id_boletim']:0;

// Verifica o plantão que está na data informada.
// <param name="dateDataPlantao">Data a ser verificada.</param>
// <param name="blnPeriodoDiurno"><see langword="True"/> para Plantão Diurno.</param>
// <returns>Retorna o nome do Plantão. Ex: 'TURNO I', 'TURNO II', 'TURNO III', 'TURNO IV'</returns>
function VerificaTurnoDoDia($dateDataPlantao, $blnPeriodoDiurno){
    $dblResultado = 0;
    $datetime1 = new DateTime($dateDataPlantao);
    $datetime2 = new DateTime('2021-12-31');
    $interval = date_diff($datetime1,$datetime2);

    $dblResultado = ($interval->days / 2);

    if ($dblResultado == intval($dblResultado)){
        if ($blnPeriodoDiurno){
            return "Turno I";
        }else {
            return "Turno II";
        }
    }
    else{
        if ($blnPeriodoDiurno) {
            return "Turno III";
        }else{
                return "Turno IV";
        }
    }
}

// Função para retornar o número do boletim diário.
// <param name="dateData">Data do boletim.</param>
// <returns>Retorna o número do boletim diário.</returns>
function numeroBoletim($dateData,$intAno){
    $dateInicioAno = new DateTime($intAno."-12-31");
    $intNumero = date_diff($dateInicioAno,$dateData);
    return $intNumero->days;
}

// Função para retornar um texto da Numeração do Boletim.
// <param name="intNumero">Número do Boletim Informativo.</param>
// <param name="intAno">Ano do Boletim Informativo.</param>
function ArrumaNumeroBoletim($intNumero, $intAno){
    $strNumero = $intNumero;

    for($intA = 1; $intA <= (3 - strlen($strNumero)); $intA++){
        $strNumero = "0$strNumero";
    }
    $strNumero = "$strNumero/$intAno";
    return $strNumero;
}

// Verifica se existem Boletins do Dia, referente a cada horário que o boletim está aberto.
function MontaSQLBuscarBoletimHoje(){
    if(date('H')<=6){
        $sql="SELECT BI.ID, NUMERO, TURNO, date_format(DATABOLETIM, '%Y') ANOBOLETIM FROM chefia_boletim BI 
        INNER JOIN chefia_turnos TU ON BI.TURNO = TU.NOME 
        WHERE BI.ID <> ".$GLOBALS['id_boletim']." AND (BI.DATABOLETIM = CURRENT_DATE AND TU.PERIODODIURNO = TRUE OR 
        DATABOLETIM = ADDDATE(CURRENT_DATE, INTERVAL -1 DAY) AND TU.PERIODODIURNO = FALSE)";
    }
    elseif(date('H')>6 && date('H')<=18){
        $sql="SELECT BI.ID, NUMERO, TURNO, date_format(DATABOLETIM, '%Y') ANOBOLETIM FROM chefia_boletim BI 
        INNER JOIN chefia_turnos TU ON BI.TURNO = TU.NOME 
        WHERE BI.DATABOLETIM = CURRENT_DATE AND 
        BI.ID <> ".$GLOBALS['id_boletim'];
    }
    elseif(date('H')>18 && date('H')<=23){
        $sql="SELECT BI.ID, NUMERO, TURNO, date_format(DATABOLETIM, '%Y') ANOBOLETIM FROM chefia_boletim BI 
        INNER JOIN chefia_turnos TU ON BI.TURNO = TU.NOME  
        WHERE BI.DATABOLETIM = CURRENT_DATE AND TU.PERIODODIURNO = FALSE AND
        BI.ID <> ".$GLOBALS['id_boletim'];
    }

    //Retorna uma lista para ser analisada e inserida no select
    return $sql; 
}

//Busca o array de todos os raios que são exibidos na visualização selecionada;
// tiporetorno 1 = Retorna array de nomes dos raios
// tiporetorno 2 = Retorna array de id dos raios
// tiporetorno 3 = Retorna string de id dos raios
// tiporetorno 4 = Consulta de todos os dados
// tiporetorno 5 = Retorna string de nomes dos raios
// blnvisuchefia = True para quando a visualização for feita pela chefia, então se retorna todos os tipos de visualização se o idvisualizacao tem valor 0
// $blntexto 1 = Adiciona aspas antes e depois do valor que está sendo concatenado para ser retornado uma string string
function retornaRaiosDaVisualizacao($idvisualizacao, $tiporetorno,$blnvisuchefia){
    $retorno = "";
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            $blntexto=0;
            if(in_array($tiporetorno,array(1,5))){
                $coluna = "NOME";
                $blntexto=1;
            }elseif(in_array($tiporetorno,array(2,3,4))){
                $coluna = "ID";
            }else{
                echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Tipo de retorno da consulta de raios da visualização não definido. </li>"));
                exit();
            }
            $where = '';
            $params = [];

            if($blnvisuchefia==false || $idvisualizacao!=0){
                $where = 'WHERE IDVISU = ?';
                array_push($params, $idvisualizacao);
            }

            $sql = "SELECT DISTINCT RC.ID, RC.NOME, RC.QTD, RC.NOMECOMPLETO, 
            (SELECT COUNT(CELA)
            FROM cadastros_mudancacela CADMC
            WHERE CADMC.RAIO = RC.ID AND CADMC.RAIOALTERADO IS NULL) TOTAL
            FROM tab_raioscelas_visu_filtros RCF
            INNER JOIN tab_raioscelas RC ON RC.ID = RCF.IDRAIO
            $where;";
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);

            $resultado = $stmt->fetchAll();

            $retorno=[];
            if($tiporetorno==4){
                $retorno = $resultado;
            }else{
                for($i=0;$i<count($resultado);$i++){
                    if($tiporetorno==1 || $tiporetorno==2){
                        array_push($retorno,$resultado[$i][$coluna]);
                    }elseif(in_array($tiporetorno,array(3,5))){
                        if($retorno==[]){
                            if($blntexto){
                                $retorno = "'" . $resultado[$i][$coluna] . "'";
                            }else{
                                $retorno = $resultado[$i][$coluna];
                            }
                        }else{
                            if($blntexto){
                                $retorno .= ", '" . $resultado[$i][$coluna] . "'";
                            }else{
                                $retorno .= ", " . $resultado[$i][$coluna];
                            }
                        }
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

//Busca as visualizações concedidas ao usuario conforme as permissões atribuídas;
// $ipcomputador = ID do usuário que está se buscando as visualizações
// $tiporetorno 1 = retorna o array completo da consulta
// $tiporetorno 2 = retorna somente os IDVISU em um array
function buscaVisualizacoesUsuario($idusuario, $tiporetorno){   

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $resultadousuario = buscaPermissoesUsuario($idusuario,2);
        $resultadoposto = buscaPermissoesPostoTrabalho($idusuario,2);

        $permissoes=[];
        if(count($resultadousuario) && count($resultadoposto)){
            $permissoes = array_merge($resultadousuario,$resultadoposto);
        }elseif(count($resultadoposto)){
            $permissoes = $resultadoposto;
        }elseif(count($resultadousuario)){
            $permissoes = $resultadousuario;
        }

        if(count($permissoes)){
            $permissoes = buscaPermissoesFilhas($permissoes,2,true);
        }

        $retorno = [];

        if(count($permissoes)){
            $params = [];
            $where = '';
            foreach($permissoes as $perm){
                if($where==''){
                    $where = '?';
                }else{
                    $where .= ', ?';
                }
                array_push($params,$perm);
            }
    
            $sql = "SELECT TRVP.* FROM tab_raioscelas_visu_perm TRVP
            WHERE TRVP.IDPERMISSAO IN($where);";
        
            $stmt = $GLOBALS['conexao']->prepare($sql);
            // $stmt->bindParam('idusuario', $idusuario,PDO::PARAM_INT);
            $stmt->execute($params);
            //unset($GLOBALS['conexao']);
            
            $resultado = $stmt->fetchAll();
            if($tiporetorno==1){
                $retorno = $resultado;
            }elseif($tiporetorno==2){
                foreach($resultado as $permissao){
                    array_push($retorno,$permissao['IDVISU']);
                }
            }
        }
        
        return $retorno;
    }else{
        return $conexaoStatus;
    }
}

//Busca as visualizações concedidas ao computador nos setores que estiver cadastrado o IP;
// $idusuario = IP do computador que está se buscando as visualizações
// $tiporetorno 1 = retorna o array completo da consulta
// $tiporetorno 2 = retorna somente os IDVISU em um array
function buscaVisualizacoesComputador($ipcomputador, $tiporetorno){

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $permissoes = buscaPermissoesComputador($ipcomputador,2);
        if(count($permissoes)){
            $permissoes = buscaPermissoesFilhas($permissoes,2,true);
        }

        $retorno = [];

        if(count($permissoes)){
            $params = [];
            $where = '';
            foreach($permissoes as $perm){
                if($where==''){
                    $where = '?';
                }else{
                    $where .= ', ?';
                }
                array_push($params,$perm);
            }
            
            $sql = "SELECT TRVP.* FROM tab_raioscelas_visu_perm TRVP
            WHERE TRVP.IDPERMISSAO IN($where);";
        
            $stmt = $GLOBALS['conexao']->prepare($sql);
            // $stmt->bindParam('idusuario', $idusuario,PDO::PARAM_INT);
            $stmt->execute($params);
            //unset($GLOBALS['conexao']);
            
            $resultado = $stmt->fetchAll();
            if($tiporetorno==1){
                $retorno = $resultado;
            }elseif($tiporetorno==2){
                foreach($resultado as $permissao){
                    array_push($retorno,$permissao['IDVISU']);
                }
            }
        }
        return $retorno;
    }else{
        return $conexaoStatus;
    }
}

//Busca as visualizações concedidas ao usuario conforme as permissões atribuídas ao posto que está no boletim vigente;
// $ipcomputador = ID do usuário que está se buscando as visualizações
// $tiporetorno 1 = retorna o array completo da consulta
// $tiporetorno 2 = retorna somente os IDVISU em um array
function buscaVisualizacoesPostoTrabalho($idusuario, $tiporetorno){   

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $permissoes = buscaPermissoesPostoTrabalho($idusuario,2);
        if(count($permissoes)){
            $permissoes = buscaPermissoesFilhas($permissoes,2,true);
        }

        $retorno = [];

        if(count($permissoes)){
            $params = [];
            $where = '';
            foreach($permissoes as $perm){
                if($where==''){
                    $where = '?';
                }else{
                    $where .= ', ?';
                }
                array_push($params,$perm);
            }
            
            $sql = "SELECT TRVP.* FROM tab_raioscelas_visu_perm TRVP
            WHERE TRVP.IDPERMISSAO IN($where);";
        
            $stmt = $GLOBALS['conexao']->prepare($sql);
            // $stmt->bindParam('idusuario', $idusuario,PDO::PARAM_INT);
            $stmt->execute($params);
            //unset($GLOBALS['conexao']);
            
            $resultado = $stmt->fetchAll();
            if($tiporetorno==1){
                $retorno = $resultado;
            }elseif($tiporetorno==2){
                foreach($resultado as $permissao){
                    array_push($retorno,$permissao['IDVISU']);
                }
            }
        }
        return $retorno;
    }else{
        return $conexaoStatus;
    }
}

//Retorna array de contagens do fim do plantão conforme a busca informada;
// tiporetorno 1 = Retorna array todas as contagens
// tiporetorno 2 = Retorna array somente das contagens que tem população e ainda não foi inserido quem contou
function retornaDadosContagens($idtipocontagem,$tiporetorno){
    $retorno = [];
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            $where='';

            if($tiporetorno==2){
                $where = "AND CC.QTD > 0 AND CC.IDUSUARIO IS NULL";
            }

            $sql = retornaQueryDadosBoletimVigente();
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute();
            
            $sql = "SELECT CC.ID IDCONTAGEM, CC.IDTIPO, CC.IDUSUARIO, US.NOME NOMEUSUARIO, CC.AUTENTICADO, CC.IDRAIO, RC.NOMECOMPLETO NOMERAIO, CC.QTD, CCT.NOME NOMECONTAGEM
            FROM chefia_contagens CC
            INNER JOIN tab_raioscelas RC ON RC.ID = CC.IDRAIO
            INNER JOIN chefia_contagenstipos CCT ON CCT.ID = CC.IDTIPO
            LEFT JOIN tab_usuarios US ON US.ID = CC.IDUSUARIO
            WHERE CC.IDBOLETIM = @intIDBoletim AND CC.IDTIPO IN ($idtipocontagem) AND CC.IDEXCLUSOREGISTRO IS NULL $where;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll();

            //Verifica se foi encontrado algum registro
            if(count($resultado)){
                $retorno = $resultado;
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

//Retorna uma verificação se a contagem existe, se existir retorna se está liberado ou não o início do novo boletim. Após a contagem iniciada, não será possível executar ações que alterem a cela do preso;
function verificaLiberacaoContagens($idtipocontagem){
    $retorno = false;
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            $resultado = retornaDadosContagens($idtipocontagem,1);

            //Verifica se foi encontrado algum registro
            if(count($resultado)){

                //Monta o retorno dos que faltam inserir quem contou;
                $resultado = retornaDadosContagens($idtipocontagem,2);
                
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    $retorno = [];
                    foreach($resultado as $contagem){
                        array_push($retorno,array('IDCONTAGEM' => $contagem['IDCONTAGEM'],'IDTIPO' => $contagem['IDTIPO'],'IDRAIO' => $contagem['IDRAIO'],'NOMERAIO' => $contagem['NOMERAIO'],'QTD' => $contagem['QTD'],'CONTAGEMEXISTE' => 1,'CONTAGEMLIBERADA' => 0));
                    }
                }else{
                    $retorno = array(array('CONTAGEMEXISTE' => 1,'CONTAGEMLIBERADA' => 1));
                }
            }else{
                $retorno = array(array('CONTAGEMEXISTE' => 0));
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

//Retorna uma query gerando variáveis para a conexão MySQL aberta, setando os valores do boletim do dia para facilitar nas consultas do boletim que está aberto.
function retornaQueryDadosBoletimVigente(){
    $retorno = "SET @intIDBoletim = (SELECT ID FROM chefia_boletim WHERE BOLETIMDODIA = TRUE);

    SET @blnDiurno = (SELECT TUR.PERIODODIURNO FROM chefia_boletim CBOL
    INNER JOIN tab_turnos TUR ON TUR.ID = CBOL.IDTURNO WHERE CBOL.ID = @intIDBoletim);
    
    SET @intIDTurno = (SELECT CBOL.IDTURNO FROM chefia_boletim CBOL WHERE ID = @intIDBoletim);
    
    SET @intIDDiretor = (SELECT IDDIRETOR FROM chefia_boletim CBOL WHERE CBOL.ID = @intIDBoletim);

    SET @intIDTurnoSeguinte = (SELECT TU.IDTURNOSEGUINTE FROM chefia_boletim CBOL INNER JOIN tab_turnos TU ON TU.ID = CBOL.IDTURNO WHERE CBOL.ID = @intIDBoletim);

    SET @horaPlantao = (SELECT HORAPLANTAOASP FROM tab_dadosunidade WHERE ID = 1);
    SET @dataBoletimCurta = (SELECT DATABOLETIM FROM chefia_boletim WHERE ID = @intIDBoletim);
    SET @dataBoletim = concat((SELECT DATABOLETIM FROM chefia_boletim WHERE ID = @intIDBoletim), ' ', @horaPlantao);
    
    SET @dataInicio = CASE @blnDiurno WHEN 1 THEN @dataBoletim ELSE date_add(@dataBoletim, INTERVAL 12 HOUR) END;
    SET @dataFim = CASE @blnDiurno WHEN 1 THEN date_add(@dataBoletim, INTERVAL 12 HOUR) ELSE date_add(@dataBoletim, INTERVAL 24 HOUR) END;
    
    SET @nomeTurnoAtual = (SELECT NOME FROM tab_turnos WHERE ID = @intIDTurno);
    SET @nomeTurnoSeguinte = (SELECT NOME FROM tab_turnos WHERE ID = @intIDTurnoSeguinte);";

    return $retorno;
}

//Retorna o ID da permissão que é necessário para acessar ou realizar algo no penal que exija autenticação
// $tipo 1 = Retorna ID Permissão Penal do turno atual
// $tipo 2 = Retorna ID Permissão Penal do turno seguinte
// $tipo 3 = Retorna ID Permissão Penal de todos os turnos
// $tiporetorno 1 = Retorna lista com todos dados da consulta
// $tiporetorno 2 = Retorna array das permissões somente
function retornaPermissaoPenal($tipo,$tiporetorno){
    $retorno = false;
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            $sql = retornaQueryDadosBoletimVigente();
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute();
            
            //Busca permissão do penal do turno atual
            if($tipo==1){
                $sql = "SELECT * FROM tab_turnos WHERE ID = @intIDTurno;";
            //Busca permissão do penal do turno seguinte
            }elseif($tipo==2){
                $sql = "SELECT * FROM tab_turnos WHERE ID = @intIDTurnoSeguinte;";
            //Busca permissão do penal de todos os turnos
            }elseif($tipo==3){
                $sql = "SELECT * FROM tab_turnos WHERE IDPERMISSAO IS NOT NULL;";
            }

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll();

            $retorno=[];
            if($tiporetorno==1){
                $retorno = $resultado;
            }elseif($tiporetorno==2){
                foreach($resultado as $perm){
                    array_push($retorno,$perm['IDPERMISSAO']);
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
// echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." </li>"));exit();

function verificaBloqueioMovimentacao(){
    $blnPermitido = verificaLiberacaoContagens(1);
    if($blnPermitido[0]['CONTAGEMEXISTE']==1){
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não será possível realizar movimentações que implicam na alteração de cela do preso quando é iniciado a contagem de Troca de Plantão. </li>");
        echo json_encode($retorno);
        exit();
    }
}

//Retorna busca das celas de exceção que eram exceções na data informada
// $tipo 1 = Tipo exceção seguro
// $tipo 2 = Tipo exceção trabalho
// $tipo 4 = Tipo exceção faxina
// $tiporetorno 1 = Retorna lista com todos dados da consulta
function retornaRaiosCelasExcecaoPorData($arr){

    $tiporetorno = isset($arr['TIPORETORNO'])?$arr['TIPORETORNO']:1;
    $params = [];
    $strtipo = '';
    foreach($arr['TIPO'] as $idtipo){
        if($strtipo==''){
            $strtipo = '?';
        }else{
            $strtipo .= ', ?';
        }
        array_push($params,$idtipo);
        // echo "<p>$idtipo</p>";
        // echo "<p>$strtipo</p>";
    }


    array_push($params,retornaDadosDataHora($arr['DATACONSULTA'],13));
    array_push($params,retornaDadosDataHora($arr['DATACONSULTA'],13));

    // pre_r($params);

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {

            $sql = "SELECT * FROM tab_raioscelasexcecoes WHERE IDTIPO IN ($strtipo) AND DATAINICIO <= ? AND (DATATERMINO >= ? OR DATATERMINO IS NULL) AND IDEXCLUSOREGISTRO IS NULL;";
            // echo "<p>$sql</p>";
            
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();

            $retorno=[];
            if($tiporetorno==1){
                $retorno = $resultado;
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
