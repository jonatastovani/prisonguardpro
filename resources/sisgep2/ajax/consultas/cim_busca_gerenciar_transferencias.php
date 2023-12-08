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
                    $ordem = 'DATAMOV';
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
                for($i=0;$i<3;$i++){
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
                                $where .= "(UCASE(CD.NOME) LIKE UCASE(?) OR EP.MATRICULA LIKE UCASE(?) OR UCASE(UNT.ABREVIACAO) LIKE UCASE(?) OR UCASE(UN.NOMEUNIDADE) LIKE UCASE(?) OR concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) LIKE ? OR concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) LIKE ? OR UCASE(MT.NOME) LIKE UCASE(?)) AND CTI.PRIMEIROLOCAL = TRUE";
                                //Quantidade de substiuições que vão ser inseridas
                                $repeticao = 7;
                            }elseif($i==1){
                                $where .= "(UCASE(CD.NOME) LIKE UCASE(?) OR UCASE(EP.NOME) LIKE UCASE(?) OR EP.MATRICULA LIKE ? OR UCASE(UNT.ABREVIACAO) LIKE UCASE(?) OR UCASE(UN.NOMEUNIDADE) LIKE UCASE(?)) AND CTI.PRIMEIROLOCAL = TRUE";
                                //Quantidade de substiuições que vão ser inseridas
                                $repeticao = 5;
                            }elseif($i==2){
                                $where .= "UCASE(CD.NOME) LIKE UCASE(?) OR UCASE(EP.NOME) LIKE UCASE(?) OR EP.MATRICULA LIKE ? OR UCASE(UNT.ABREVIACAO) LIKE UCASE(?) OR UCASE(UN.NOMEUNIDADE) LIKE UCASE(?) OR UCASE(MT.NOME) LIKE UCASE(?)";
                                //Quantidade de substiuições que vão ser inseridas
                                $repeticao = 6;
                            }

                            for($iparams=0;$iparams<$repeticao;$iparams++){
                                array_push($params,$textoinicio.$tex.$textofinal);
                            }
                        }
                        $where .= ") ";
                    }
                    array_push($arraywheres,$where);
                }

                $sql = "SELECT 1 TABELA, COT.ID IDORDEM, CT.ID IDMOVIMENTACAO, CT.IDPRESO, COT.DATASAIDA DATAMOV, CD.NOME, CD.MATRICULA, concat(UNT.ABREVIACAO , ' ', UN.NOMEUNIDADE) UNIDADE, concat(LPAD(TOF.NUMERO, 4, '0'), '/', TOF.ANO) OFICIO, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) ORDEM, CT.DATARETORNO, MT.NOME TIPO, EP.SEGURO
                FROM cimic_transferencias CT
                INNER JOIN cimic_ordens_transferencias COT ON COT.ID = CT.IDORDEMSAIDAMOV
                INNER JOIN cimic_transferencias_intermed CTI ON CTI.IDMOVIMENTACAO = CT.ID
                INNER JOIN tab_oficios TOF ON TOF.ID = COT.IDOFICIOESCOLTA
                INNER JOIN tab_ordemsaida TOS ON TOS.ID = COT.IDORDEM
                INNER JOIN tab_unidades UN ON UN.ID = CTI.IDDESTINOINTERM
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                INNER JOIN tab_movimentacoestipo MT ON MT.ID = CT.IDTIPOMOV
                INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
                INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                WHERE COT.DATASAIDA >= ? AND COT.DATASAIDA <= ?
                AND CTI.PRIMEIROLOCAL = TRUE
                AND COT.IDEXCLUSOREGISTRO IS NULL AND CTI.IDEXCLUSOREGISTRO IS NULL
                AND CT.IDEXCLUSOREGISTRO IS NULL AND (EP.IDEXCLUSOREGISTRO IS NULL OR EP.DATAEXCLUSOREGISTRO <= ?) " .$arraywheres[0]. " UNION ";

                $sql .= "SELECT 2 TABELA, CT.IDORDEMSAIDAMOV IDORDEM, CT.ID IDMOVIMENTACAO, CT.IDPRESO, CT.DATARETORNO DATAMOV, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, EP.MATRICULA, concat(UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE) UNIDADE, NULL OFICIO, NULL ORDEM, NULL DATARETORNO, (SELECT NOME FROM tab_movimentacoestipo WHERE ID = 16) TIPO, EP.SEGURO
                FROM cimic_transferencias CT
                INNER JOIN cimic_ordens_transferencias COT ON COT.ID = CT.IDORDEMSAIDAMOV
                INNER JOIN cimic_transferencias_intermed CTI ON CTI.IDMOVIMENTACAO = CT.ID
                INNER JOIN tab_unidades UN ON UN.ID = CTI.IDDESTINOINTERM
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                INNER JOIN entradas_presos EP ON EP.ID = CT.IDPRESO
                INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                WHERE CT.DATARETORNO >= ? AND CT.DATARETORNO <= ? AND CTI.PRIMEIROLOCAL = TRUE AND CT.IDTIPOMOV = 6 AND COT.IDEXCLUSOREGISTRO IS NULL AND CT.IDEXCLUSOREGISTRO IS NULL AND CTI.IDEXCLUSOREGISTRO IS NULL AND (EP.IDEXCLUSOREGISTRO IS NULL OR EP.DATAEXCLUSOREGISTRO <= ?) " .$arraywheres[1]. " UNION ";

                $sql .= "SELECT 3 TABELA, 0 IDORDEM, CR.ID IDMOVIMENTACAO, CR.IDPRESO, CR.DATARECEB DATAMOV, CASE WHEN EP.MATRICULAVINCULADA = TRUE THEN CD.NOME ELSE EP.NOME END NOME, EP.MATRICULA, concat(UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE) UNIDADE, NULL OFICIO, NULL ORDEM, NULL DATARETORNO, MT.NOME TIPO, EP.SEGURO
                FROM cimic_recebimentos CR
                INNER JOIN tab_unidades UN ON UN.ID = CR.IDPROCEDENCIA
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                INNER JOIN entradas_presos EP ON EP.ID = CR.IDPRESO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN tab_movimentacoestipo MT ON MT.ID = CR.IDTIPOMOV
                INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = CR.IDMOTIVOMOV
                WHERE CR.DATARECEB >= ? AND CR.DATARECEB <= ? AND CR.IDEXCLUSOREGISTRO IS NULL AND (EP.IDEXCLUSOREGISTRO IS NULL OR EP.DATAEXCLUSOREGISTRO <= ?) " .$arraywheres[2]. " ORDER BY $ordem;";

                //echo json_encode($sql); exit();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                $retorno="";
                $contador = 0;

                foreach($resultado as $dados){
                    $tabela = $dados['TABELA'];
                    $idordem = $dados['IDORDEM'];
                    $idmovimentacao = $dados['IDMOVIMENTACAO'];
                    $idpreso = $dados['IDPRESO'];
                    $datamov = $dados['DATAMOV'];
                    $nome = $dados['NOME'];
                    $matr = $dados['MATRICULA'];
                    if($matr>0){
                        $matricula = midMatricula($matr,3);
                    }else{
                        $matricula = '';
                    }
                    $unidade = $dados['UNIDADE'];
                    $oficio = $dados['OFICIO'];
                    $ordem = $dados['ORDEM'];
                    $dataretorno = $dados['DATARETORNO'];
                    $tipomov = $dados['TIPO'];
                    $seguro = $dados['SEGURO'];
                            
                    $dadoscela = buscaDadosRaioCelaPreso($idpreso,$datamov,2);
                    $trabalho = $dadoscela['ESPECIAL'];

                    if($datamov!==null){
                        $datamov = retornaDadosDataHora($datamov,2);
                    }
                    if($dataretorno!==null){
                        $dataretorno = retornaDadosDataHora($dataretorno,2);
                    }
                    if($seguro==1){
                        $nome .= " <span class='destaque-atencao'>(SEGURO)</span>";
                    }
                    if($trabalho==1){
                        $nome .= " <span class='destaque-atencao'>(TRAB)</span>";
                    }
                    if($tabela==2 || $tabela==3){
                        $cor = 'fundo-chegada-cim-transf';
                    }else{
                        $cor = 'fundo-saida-cim-transf';
                    }

                    $contador++;
                    $retorno .= "
                    <tr class='$cor'>
                        <td>
                            <input type='checkbox' id='check$contador' data-idmov='$idmovimentacao' data-tabela='$tabela'>
                        </td>
                        <td class='centralizado' style='min-width: 50px;'>";
                            if($tabela == 1){
                                $retorno .= "
                                <form action='principal.php?menuop=cim_movimentacoes_transf' method='post' target='_blank'>
                                    <input type='hidden' name='ordempost' value='$idordem'>
                                    <button type='submit' class='btnAcaoRegistro'><img src='imagens/seta-dir-vermelha.png' class='imgBtnAcao'></button>
                                </form>";

                            }elseif($tabela==2){
                                $retorno .= "
                                <button id='retorno$idmovimentacao' data-id='$idmovimentacao' class='btnAcaoRegistro alterarretorno'><img src='imagens/seta-esq-azul.png' class='imgBtnAcao'></button>";

                            }elseif($tabela==3){
                                $retorno .= "
                                <button id='chegada$idmovimentacao' data-id='$idmovimentacao' class='btnAcaoRegistro alterarrecebimento'><img src='imagens/seta-esq-verde.png' class='imgBtnAcao'></button>";
                            }

                            $retorno .= "
                        </td>

                        <td class='centralizado min-width-100'>$matricula</td>
                        <td style='min-width: 350px; max-width: 450px;'>$nome</td>
                        <td class='centralizado'>$datamov</td>
                        <td class='centralizado' style='min-width: 150px; max-width: 350px;'>$unidade</td>
                        <td class='centralizado'>$oficio</td>
                        <td class='centralizado'>$ordem</td>
                        <td class='centralizado' style='min-width: 250px; max-width: 350px;'>$tipomov</td>
                        <td class='centralizado'>$dataretorno</td>
                    </tr>";
                }
            }
            //Busca IDOrdem dos ids movimentacao de envio informados
            elseif($tipo==2){
                $idsmovenvio = $_POST['idsmovenvio'];

                $params = [];
                $sqlIN = '';
        
                foreach($idsmovenvio as $id){
                    array_push($params,$id);
                    if($sqlIN==''){
                        $sqlIN = '?';
                    }else{
                        $sqlIN .=', ?';
                    }
                }
        
                $sql = "SELECT DISTINCT(COT.ID) FROM cimic_transferencias CT
                INNER JOIN cimic_ordens_transferencias COT ON COT.ID = CT.IDORDEMSAIDAMOV
                WHERE CT.ID IN ($sqlIN);";
        
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                
                $resultado = $stmt->fetchAll();
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi possível obter ID Ordem das Movimentação de Envio! </li>");
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

echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." </li>"));exit();
