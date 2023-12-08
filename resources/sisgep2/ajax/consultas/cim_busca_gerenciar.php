<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    $tipo = $_POST['tipo'];
    $idmovimentacao = isset($_POST['idmovimentacao'])?$_POST['idmovimentacao']:0;

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){ 
        try {
            //Busca tabela do Gerenciar Exclusões
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
                                $where .= "(UCASE(CD.NOME) LIKE UCASE(?) OR EP.MATRICULA LIKE UCASE(?) OR UCASE(MM.NOME) LIKE UCASE(?) OR UCASE(MT.NOME) LIKE UCASE(?) OR concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOF.ANO) LIKE ?)";
                                //Quantidade de substiuições que vão ser inseridas
                                $repeticao = 5;
                            }

                            for($iparams=0;$iparams<$repeticao;$iparams++){
                                array_push($params,$textoinicio.$tex.$textofinal);
                            }
                        }
                        $where .= ") ";
                    }
                    array_push($arraywheres,$where);
                }

                $sql = "SELECT EXC.ID IDMOV, EXC.IDPRESO, CAD.MATRICULA, CAD.NOME, concat(LPAD(TOS.NUMERO, 4, '0'), '/', TOS.ANO) ORDEM, EXC.IDORDEM, EXC.DATASAIDA DATAMOV, EXC.IDTIPO, MT.NOME TIPO, EXC.IDMOTIVO, MM.NOME MOTIVO, EXC.IDSITUACAO, SIT.NOME SITUACAO, EP.SEGURO
                FROM cimic_exclusoes EXC
                INNER JOIN entradas_presos EP ON EP.ID = EXC.IDPRESO
                INNER JOIN cadastros CAD ON CAD.MATRICULA = EP.MATRICULA
                INNER JOIN tab_ordemsaida TOS ON TOS.ID = EXC.IDORDEM
                INNER JOIN tab_movimentacoestipo MT ON MT.ID = EXC.IDTIPO
                INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = EXC.IDMOTIVO
                INNER JOIN tab_situacao SIT ON SIT.ID = EXC.IDSITUACAO
                WHERE EXC.DATASAIDA >= ? AND EXC.DATASAIDA <= ? AND EP.IDEXCLUSOREGISTRO IS NULL " .$arraywheres[0]. "
                ORDER BY $ordem;";

                //echo json_encode($sql); exit();

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);

                $resultado = $stmt->fetchAll();
                $retorno="";
                $contador = 0;

                foreach($resultado as $dados){
                    $idordem = $dados['IDORDEM'];
                    $idmovimentacao = $dados['IDMOV'];
                    $idpreso = $dados['IDPRESO'];
                    $datamov = $dados['DATAMOV'];
                    $nome = $dados['NOME'];
                    $matr = $dados['MATRICULA'];
                    if($matr>0){
                        $matricula = midMatricula($matr,3);
                    }else{
                        $matricula = '';
                    }
                    $motivo = $dados['MOTIVO'];
                    $ordem = $dados['ORDEM'];
                    $tipomov = $dados['TIPO'];
                    $seguro = $dados['SEGURO'];
                    $idsituacao = $dados['IDSITUACAO'];
                    $situacao = $dados['SITUACAO'];

                    $dadoscela = buscaDadosRaioCelaPreso($idpreso,$datamov,2);
                    $trabalho = $dadoscela['ESPECIAL'];

                    if($datamov!==null){
                        $datamov = retornaDadosDataHora($datamov,2);
                    }
                    if($seguro==1){
                        $nome .= " <span class='destaque-atencao'>(SEGURO)</span>";
                    }
                    if($trabalho==1){
                        $nome .= " <span class='destaque-atencao'>(TRAB)</span>";
                    }

                    $cor = 'cor-excl';
                    if(in_array($idsituacao,array(6,13,18))){
                        $cor = "cor-fundo-comum-tr";
                    }elseif(in_array($idsituacao,array(19))){
                        $cor = "cor-cancelado-agend";
                    }elseif(in_array($idsituacao,array(7,8,9,15))){
                        $cor = "cor-cancelado";
                    }

                    $contador++;
                    $retorno .= "
                    <tr id='exclusao$contador' class='$cor'>
                        <td>
                            <input type='checkbox' id='check$contador' data-idmov='$idmovimentacao' data-idsituacao='$idsituacao'>
                        </td>
                        <td class='tdbotoes centralizado' style='min-width: 50px;'></td>

                        <td class='centralizado min-width-100'>$matricula</td>
                        <td style='min-width: 350px; max-width: 450px;'>$nome</td>
                        <td class='centralizado'>$ordem</td>
                        <td class='centralizado'>$datamov</td>
                        <td class='centralizado' style='min-width: 150px; max-width: 350px;'>$motivo</td>
                        <td class='centralizado' style='min-width: 250px; max-width: 350px;'>$tipomov</td>
                        <td class='centralizado'>$situacao</td>
                    </tr>";
                }

                echo json_encode($retorno);
                exit();            
            }
            //Busca dados da exclusão informada
            elseif($tipo==2 && $idmovimentacao!=0){
              
                $sql = "SELECT * FROM cimic_exclusoes WHERE ID = :idmovimentacao;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam(':idmovimentacao',$idmovimentacao,PDO::PARAM_INT);
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
            //Busca presos pendentes
            elseif($tipo==3){
              
                $sql = "SELECT EP.ID, EP.NOME, EP.MATRICULA, EP.MATRICULAVINCULADA, EP.RG, 
                E.DATAENTRADA, GSA.NOME ORIGEM, E.ID IDENTRADA
                FROM entradas_presos EP
                INNER JOIN entradas E ON EP.IDENTRADA = E.ID
                INNER JOIN codigo_gsa GSA ON E.IDORIGEM = GSA.ID
                LEFT JOIN cadastros CD ON EP.MATRICULA = CD.MATRICULA
                WHERE LANCADOCIMIC = FALSE AND EP.ID > 0 AND EP.IDEXCLUSOREGISTRO IS NULL AND E.IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();

                $resultado = $stmt->fetchAll();
                echo json_encode($resultado);
                exit();
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
