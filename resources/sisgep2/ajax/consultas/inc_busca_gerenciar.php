<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes_comuns.php";

    //Cria a variável de retorno para salvar os a mensagem a ser exibida na tela
    $retorno = [];

    //Obtem o tipo de pesquisa para poder assim realizar a consulta.
    //Tipo 1 = buscar dados da entrada
    //Tipo 2 = buscar dados dos presos
    //Tipo 3 = buscar dados dos artigos do preso
    //Tipo 4 = buscar quantidade de presos que existem na entrada
    $tipo = $_POST['tipo'];

    $identrada = isset($_POST['identrada'])?$_POST['identrada']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        try {

            //Tipo 1 = buscar dados da entrada
            if($tipo==1){
                //Monta o Select
                $sql = "SELECT * FROM entradas WHERE ID = :identrada";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('identrada',$identrada, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma entrada com o Número de Entrada informado </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 2 = buscar dados dos presos
            elseif($tipo==2){
                //Monta o Select
                $sql = "SELECT * FROM entradas_presos WHERE IDENTRADA = :identrada AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('identrada',$identrada, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum preso para o Número de Entrada informado </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 3 = buscar dados dos artigos do preso
            elseif($tipo==3){
               
                //Monta o Select
                $sql = "SELECT EA.ID, EA.IDARTIGO, EA.OBSERVACOES FROM entradas_artigos EA
                INNER JOIN tab_artigos TA ON EA.IDARTIGO = TA.ID
                WHERE EA.IDPRESO = :idpreso AND EA.IDEXCLUSOREGISTRO IS NULL AND EA.DATAEXCLUSOREGISTRO IS NULL ORDER BY TA.NOME";
                
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
            //Tipo 4 = buscar quantidade de presos que existem na entrada
            elseif($tipo==4){
                //Monta o Select
                $sql = "SELECT COUNT(ID) QTD FROM entradas_presos WHERE IDENTRADA = :identrada AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('identrada',$identrada, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
            }
            //Buscar quantidade de presos que existem na entrada
            elseif($tipo==5){
                $datainicio = $_POST['datainicio'];
                $datafinal = $_POST['datafinal'];
                $textobusca = $_POST['textobusca'];
                $ordem = isset($_POST['ordem'])?$_POST['ordem']:1;
                $texto = isset($_POST['texto'])?$_POST['texto']:1;
                $buscatexto = isset($_POST['buscatexto'])?$_POST['buscatexto']:1;
                $situacao = isset($_POST['situacao'])?$_POST['situacao']:0;

                if($ordem==1){
                    $ordem = 'MATRICULA';
                }elseif($ordem==2){
                    $ordem = 'NOME';
                }elseif($ordem==3){
                    $ordem = 'E.DATAENTRADA';
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
                                $where .= "(UCASE(CAD.NOME) LIKE UCASE(?) OR UCASE(EP.NOME) LIKE UCASE(?) OR EP.MATRICULA LIKE UCASE(?) OR UCASE(ATT.NOME) LIKE UCASE(?) OR ATD.HORAATEND LIKE (?))";
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
                if($situacao>0){
                    array_push($params,$situacao);
                    $situacao = "AND EP.IDSITUACAO = ?";
                }else{
                    $situacao = '';
                }

                $sql = "SELECT EP.ID, EP.IDENTRADA, EP.MATRICULA, EP.NOME, EP.RG, GSA.NOME ORIGEM,
                ST.NOME SITUACAO, ST.ID IDSITUACAO, CD.IDPRESO, E.DATAENTRADA, EP.LANCADOCIMIC, (SELECT KE.ID FROM inc_kitentregue KE WHERE KE.IDPRESO = EP.ID AND KE.IDEXCLUSOREGISTRO  IS NULL LIMIT 1) IDKITENTREGUE, EP.SEGURO, CASE WHEN EP.IDSITUACAO = 2 THEN 'cor-fundo-comum-tr' ELSE 'cor-pendente' END COR
                FROM entradas_presos EP
                INNER JOIN tab_situacao ST ON ST.ID = EP.IDSITUACAO
                LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
                INNER JOIN entradas E ON EP.IDENTRADA = E.ID
                INNER JOIN codigo_gsa GSA ON GSA.ID = E.IDORIGEM
                WHERE E.DATAENTRADA >= ? AND E.DATAENTRADA <= ? AND EP.PROVISORIO = FALSE AND EP.IDEXCLUSOREGISTRO IS NULL AND E.IDEXCLUSOREGISTRO IS NULL " .$arraywheres[0]. " $situacao ORDER BY $ordem";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $retorno = $stmt->fetchAll();

                // foreach($resultado as $dados){ 
                //     $id = $dados['ID'];
                //     $identrada = $dados['IDENTRADA'];
                //     $matric = $dados['MATRICULA'];
                //     if($matric>0){
                //         $matricula = midMatricula($matric,3);
                //     }else{
                //         $matricula = '';
                //     }
                //     $nome = $dados['NOME'];
                //     $rg = $dados['RG'];
                //     $situacao = $dados['SITUACAO'];
                //     $idsituacao = $dados['IDSITUACAO'];
                //     $idpreso = $dados['IDPRESO'];
                //     $lancado = $dados['LANCADOCIMIC'];
                //     $dataentrada = retornaDadosDataHora($dados['DATAENTRADA'],2);
                //     $kitentregue = $dados['IDKITENTREGUE'];
                    
                //     $retorno .= "
                //     <tr class='cor-fundo-comum-tr'>
                //         <td><input type='checkbox' id='$id' data-identrada='$identrada'></td>
                //         <td class='centralizado min-width-100'>$matricula</td>
                //         <td>$nome</td>
                //         <td class='centralizado min-width-100'>$rg</td>
                //         <td class='centralizado min-width-100'>$dataentrada</td>
                //         <td class='centralizado min-width-100'>$situacao</td>

                //         <td class='acaoContainer-flex'>";
                //             if($idpreso == $id && $lancado==true){
                //                 $retorno .= "
                //                 <form action='principal.php?menuop=inc_alt_qualificativa_preso' method='post' target='_blank'>
                //                     <input type='hidden' name='matric' value='$matric'>
                //                     <input type='hidden' name='idpreso' value='$idpreso'>
                //                     <input type='hidden' name='redirecionamento' id='redirecionamento' value='menuop=inc_gerenciar_presos&back=2'>
                //                     <button type='submit' class='btnAcaoRegistro'><img src='imagens/cadastro-preso-alterar.png' class='imgBtnAcao'></button>
                //                 </form>";
                                
                //                 if($kitentregue>0){
                //                     $retorno .= "
                //                     <button id='altkit$id' class='btnAcaoRegistro alterarkit'><img src='imagens/kit-entregue.png' class='imgBtnAcao'></button>";
                //                 }else{
                //                     $retorno .= "
                //                     <button id='novokit$id' class='btnAcaoRegistro novokit'><img src='imagens/adicionar-kit.png' class='imgBtnAcao'></button>";
                //                 }
        
                //             }
                //             if($idsituacao == 1){
                //                 $retorno .= "
                //                     <button class='btnAcaoRegistro encerrado' id='encerrado$id'><img src='imagens/checked.png' class='imgBtnAcao'></button>";
                //             }elseif($idsituacao == 2){
                //                 $retorno .= "
                //                     <button class='btnAcaoRegistro pendente' id='pendente$id'><img src='imagens/checked-false.png' class='imgBtnAcao'></button>";
                //             }
                //         $retorno .= "
                //         </td>
                //     </tr>";
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
