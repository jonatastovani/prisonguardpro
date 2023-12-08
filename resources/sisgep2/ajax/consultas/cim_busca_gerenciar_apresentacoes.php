<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    $tipo = $_POST['tipo'];

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){ 
        try {
            //Busca tabela do Gerenciar Transferência
            if($tipo==1){
                $datainicio = $_POST['datainicio'];
                $datafinal = $_POST['datafinal'];
                $ordem = $_POST['ordem'];
                $buscatexto = $_POST['buscatexto'];
                $texto = $_POST['texto'];
                $textobusca = $_POST['textobusca'];
            
                if($ordem==1){
                    $ordem = 'MATRICULA';
                }elseif($ordem==2){
                    $ordem = 'NOME';
                }elseif($ordem==3){
                    $ordem = 'DATAAPRES';
                }elseif($ordem==4){
                    $ordem = 'LOCALAPRES';
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
                for($i=0;$i<2;$i++){
                    array_push($params,"$datainicio 00:00:00");
                    array_push($params,"$datafinal 23:59:59");
                    array_push($params,"$datafinal 23:59:59"); //Para a data do EP.EXCLUSOREGISTRO
                    
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
                                $where .= "(UCASE(CD.NOME) LIKE UCASE(?) OR UCASE(EP.NOME) LIKE UCASE(?) OR EP.MATRICULA LIKE ? OR UCASE(CLA.NOME) LIKE UCASE(?) OR concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) LIKE ? OR concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) LIKE ? OR UCASE(CA.PROCESSO) LIKE UCASE(?) OR UCASE('Externa') LIKE UCASE(?))";
                                //Quantidade de substiuições que vão ser inseridas
                                $repeticao = 8;
                            }elseif($i==1){
                                $where .= "(UCASE(CD.NOME) LIKE UCASE(?) OR UCASE(EP.NOME) LIKE UCASE(?) OR EP.MATRICULA LIKE ? OR UCASE(CLA.NOME) LIKE UCASE(?) OR concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) LIKE ? OR UCASE(CAIP.PROCESSO) LIKE UCASE(?) OR UCASE('Interna') LIKE UCASE(?))";
                                //Quantidade de substiuições que vão ser inseridas
                                $repeticao = 7;
                            }

                            for($iparams=0;$iparams<$repeticao;$iparams++){
                                array_push($params,$textoinicio.$tex.$textofinal);
                            }
                        }
                        $where .= ") ";
                    }
                    array_push($arraywheres,$where);
                }

                $sql = "SELECT 1 TABELA, COA.ID IDORDEM, CA.ID IDAPRES, CA.IDPRESO, CD.NOME, CD.MATRICULA, CLA.NOME DESTINO, concat(date_format(COA.DATASAIDA, '%Y-%m-%d'), ' ', CA.HORAAPRES) DATAAPRES, COA.DATASAIDA, concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) OFICIO, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) ORDEM, CA.PROCESSO, EP.SEGURO, 'Externa' LOCALAPRES
                FROM cimic_apresentacoes CA
                INNER JOIN entradas_presos EP ON EP.ID = CA.IDPRESO
                INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN cimic_ordens_apresentacoes COA ON COA.ID = CA.IDORDEMSAIDAMOV
                INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = COA.IDDESTINO
                INNER JOIN tab_oficios TOF ON TOF.ID = CA.IDOFICIOAPRES
                INNER JOIN tab_ordemsaida TOS ON TOS.ID = COA.IDORDEM
                WHERE concat(date_format(COA.DATASAIDA, '%Y-%m-%d'), ' ', CA.HORAAPRES) >= ? AND concat(date_format(COA.DATASAIDA, '%Y-%m-%d'), ' ', CA.HORAAPRES) <= ? AND COA.IDEXCLUSOREGISTRO IS NULL AND CA.IDEXCLUSOREGISTRO IS NULL AND (EP.IDEXCLUSOREGISTRO IS NULL OR EP.DATAEXCLUSOREGISTRO <= ?) " .$arraywheres[0]. " UNION ";

                $sql .="SELECT 2 TABELA, CAI.ID IDORDEM, CAIP.ID IDAPRES, CAIP.IDPRESO, CD.NOME, CD.MATRICULA, CLA.NOME DESTINO, concat(date_format(CAI.DATASAIDA, '%Y-%m-%d'), ' ', CAIP.HORAAPRES) DATAAPRES, concat(date_format(CAI.DATASAIDA, '%Y-%m-%d'), ' ', CAIP.HORAAPRES) DATASAIDA, concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) OFICIO, 'N/C' ORDEM, CAIP.PROCESSO, EP.SEGURO, 'Interna' LOCALAPRES
                FROM cimic_apresentacoes_internas_presos CAIP
                INNER JOIN entradas_presos EP ON EP.ID = CAIP.IDPRESO
                INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN cimic_apresentacoes_internas CAI ON CAI.ID = CAIP.IDAPRES
                INNER JOIN cimic_locaisapresentacoes CLA ON CLA.ID = CAI.IDDESTINO
                INNER JOIN tab_oficios TOF ON TOF.ID = CAIP.IDOFICIOAPRES
                WHERE concat(date_format(CAI.DATASAIDA, '%Y-%m-%d'), ' ', CAIP.HORAAPRES) >= ? AND concat(date_format(CAI.DATASAIDA, '%Y-%m-%d'), ' ', CAIP.HORAAPRES) <= ? AND CAI.IDEXCLUSOREGISTRO IS NULL AND CAIP.IDEXCLUSOREGISTRO IS NULL AND (EP.IDEXCLUSOREGISTRO IS NULL OR EP.DATAEXCLUSOREGISTRO <= ?) " .$arraywheres[1]. " ORDER BY $ordem;";

                //echo json_encode($sql); exit();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                $retorno="";
                $contador = 0;

                foreach($resultado as $dados){
                    $tabela = $dados['TABELA'];
                    $idordem = $dados['IDORDEM'];
                    $idapres = $dados['IDAPRES'];
                    $idpreso = $dados['IDPRESO'];
                    $dataapres = $dados['DATAAPRES'];
                    $nome = $dados['NOME'];
                    $matr = $dados['MATRICULA'];
                    if($matr>0){
                        $matricula = midMatricula($matr,3);
                    }else{
                        $matricula = '';
                    }
                    $destino = $dados['DESTINO'];
                    $oficio = $dados['OFICIO'];
                    $ordem = $dados['ORDEM'];
                    $processo = $dados['PROCESSO'];
                    $localapres = $dados['LOCALAPRES'];
                    $seguro = $dados['SEGURO'];
                            
                    $dadoscela = buscaDadosRaioCelaPreso($idpreso,$dataapres,2);
                    $trabalho = $dadoscela['ESPECIAL'];

                    if($dataapres!==null){
                        $dataapres = retornaDadosDataHora($dataapres,2);
                    }
                    if($seguro==1){
                        $nome .= " <span class='destaque-atencao'>(SEGURO)</span>";
                    }
                    if($trabalho==1){
                        $nome .= " <span class='destaque-atencao'>(TRAB)</span>";
                    }

                    $contador++;
                    $retorno .= "
                    <tr class='cor-fundo-comum-tr'>
                        <td>
                            <input type='checkbox' id='check$contador' data-idapres='$idapres' data-tabela='$tabela'>
                        </td>
                        <td class='centralizado' style='min-width: 50px;'>";
                            if($tabela == 1){
                                $retorno .= "
                                <form action='principal.php?menuop=cim_movimentacoes_apres' method='post' target='_blank'>
                                    <input type='hidden' name='ordempost' value='$idordem'>
                                    <button type='submit' class='btnAcaoRegistro'><img src='imagens/algemas.png' class='imgBtnAcao'></button>
                                </form>";

                            }elseif($tabela==2){
                                $retorno .= "
                                <form action='principal.php?menuop=cim_movimentacoes_apres_int' method='post' target='_blank'>
                                    <input type='hidden' name='ordempost' value='$idordem'>
                                    <button type='submit' class='btnAcaoRegistro'><img src='imagens/teleaudiencia.png' class='imgBtnAcao'></button>
                                </form>";
                            }
                            $retorno .= "
                        </td>

                        <td class='centralizado min-width-100'>$matricula</td>
                        <td style='min-width: 350px; max-width: 450px;'>$nome</td>
                        <td class='centralizado'>$dataapres</td>
                        <td class='centralizado' style='min-width: 350px; max-width: 450px;'>$destino</td>
                        <td class='centralizado'>$oficio</td>
                        <td class='centralizado'>$ordem</td>
                        <td class='centralizado'>$localapres</td>
                        <td class='centralizado'>$processo</td>
                    </tr>";
                }
            }
            //Busca IDOrdem dos ids apresentações externas
            elseif($tipo==2){
                $idsapresext = $_POST['idsapresext'];

                $params = [];
                $sqlIN = '';
        
                foreach($idsapresext as $id){
                    array_push($params,$id);
                    if($sqlIN==''){
                        $sqlIN = '?';
                    }else{
                        $sqlIN .=', ?';
                    }
                }
        
                $sql = "SELECT DISTINCT(COA.ID) FROM cimic_apresentacoes CA
                INNER JOIN cimic_ordens_apresentacoes COA ON COA.ID = CA.IDORDEMSAIDAMOV
                WHERE CA.ID IN ($sqlIN);";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                
                $resultado = $stmt->fetchAll();
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível obter ID Ordem das Apresentações Externas! </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Busca datas dos ids de retorno e ids de recebimento informados
            elseif($tipo==3){
                $idsmovretorno = isset($_POST['idsmovretorno'])?$_POST['idsmovretorno']:0;
                $idsmovreceb = isset($_POST['idsmovreceb'])?$_POST['idsmovreceb']:0;

                if($idsmovreceb==0 && $idsmovretorno==0){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma movimentação de recebimento ou retorno foi selecionada. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                $params = [];
                $union = '';
                $sql = '';
                if($idsmovretorno!=0){
                    $sqlINret = '';
                    foreach($idsmovretorno as $id){
                        array_push($params,$id);
                        if($sqlINret==''){
                            $sqlINret = '?';
                        }else{
                            $sqlINret .=', ?';
                        }
                    }

                    $sql = "SELECT DISTINCT(DATARETORNO) DATARECEB FROM cimic_transferencias CT
                    WHERE CT.ID IN ($sqlINret) AND DATARETORNO IS NOT NULL";
                    $union = ' UNION ';
                }

                if($idsmovreceb!=0){
                    $sqlINreceb = '';
                    foreach($idsmovreceb as $id){
                        array_push($params,$id);
                        if($sqlINreceb==''){
                            $sqlINreceb = '?';
                        }else{
                            $sqlINreceb .=', ?';
                        }
                    }
                    $sql .= $union;
                    $sql .= "SELECT DISTINCT(DATARECEB) FROM cimic_recebimentos CR
                    WHERE CR.ID IN ($sqlINreceb) AND DATARECEB IS NOT NULL;";
                }
        
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                
                $resultado = $stmt->fetchAll();
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível obter ID Ordem das Movimentação de Retorno e Recebimento! </li>");
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

echo json_encode($retorno);
exit();
