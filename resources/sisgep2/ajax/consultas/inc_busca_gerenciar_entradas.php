<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    //recupera os dados de tipo
    $datainicio = $_POST['datainicio'];
    $datafinal = $_POST['datafinal'];
    $situacao = $_POST['situacao'];

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){ 
        try {
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
            if($situacao>0){
                array_push($params,$situacao);
                $situacao = "AND EP.IDSITUACAO = ?";
            }else{
                $situacao = '';
            }

            $sql = "SELECT EP.ID, EP.IDENTRADA, EP.MATRICULA, EP.NOME, EP.RG,
            ST.NOME SITUACAO, ST.ID IDSITUACAO, CD.IDPRESO, E.DATAENTRADA, EP.LANCADOCIMIC, (SELECT KE.ID FROM inc_kitentregue KE WHERE KE.IDPRESO = EP.ID AND KE.IDEXCLUSOREGISTRO  IS NULL AND KE.DATAEXCLUSOREGISTRO IS NULL LIMIT 1) IDKITENTREGUE
            FROM entradas_presos EP
            INNER JOIN tab_situacao ST ON ST.ID = EP.IDSITUACAO
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN entradas E ON EP.IDENTRADA = E.ID
            WHERE E.DATAENTRADA >= ? AND E.DATAENTRADA <= ? AND EP.PROVISORIO = FALSE AND EP.IDEXCLUSOREGISTRO IS NULL AND EP.DATAEXCLUSOREGISTRO IS NULL $situacao ORDER BY EP.ID DESC";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetchAll();
            $retorno="";

            foreach($resultado as $dados){ 
                $id = $dados['ID'];
                $identrada = $dados['IDENTRADA'];
                $matric = $dados['MATRICULA'];
                if($matric>0){
                    $matricula = midMatricula($matric,3);
                }else{
                    $matricula = '';
                }
                $nome = $dados['NOME'];
                $rg = $dados['RG'];
                $situacao = $dados['SITUACAO'];
                $idsituacao = $dados['IDSITUACAO'];
                $idpreso = $dados['IDPRESO'];
                $lancado = $dados['LANCADOCIMIC'];
                $dataentrada = retornaDadosDataHora($dados['DATAENTRADA'],2);
                $kitentregue = $dados['IDKITENTREGUE'];
                
                $retorno .= "
                <tr class='cor-fundo-comum-tr'>
                    <td><input type='checkbox' id='$id' data-identrada='$identrada'></td>
                    <td class='centralizado min-width-100'>$matricula</td>
                    <td>$nome</td>
                    <td class='centralizado min-width-100'>$rg</td>
                    <td class='centralizado min-width-100'>$dataentrada</td>
                    <td class='centralizado min-width-100'>$situacao</td>

                    <td class='acaoContainer-flex'>";
                        if($idpreso == $id && $lancado==true){
                            $retorno .= "
                            <form action='principal.php?menuop=inc_alt_qualificativa_preso' method='post' target='_blank'>
                                <input type='hidden' name='matric' value='$matric'>
                                <input type='hidden' name='idpreso' value='$idpreso'>
                                <input type='hidden' name='redirecionamento' id='redirecionamento' value='menuop=inc_gerenciar_presos&back=2'>
                                <button type='submit' class='btnAcaoRegistro'><img src='imagens/cadastro-preso-alterar.png' class='imgBtnAcao'></button>
                            </form>";
                            
                            if($kitentregue>0){
                                $retorno .= "
                                <button id='altkit$id' class='btnAcaoRegistro alterarkit'><img src='imagens/kit-entregue.png' class='imgBtnAcao'></button>";
                            }else{
                                $retorno .= "
                                <button id='novokit$id' class='btnAcaoRegistro novokit'><img src='imagens/adicionar-kit.png' class='imgBtnAcao'></button>";
                            }
    
                        }
                        if($idsituacao == 1){
                            $retorno .= "
                                <button class='btnAcaoRegistro encerrado' id='encerrado$id'><img src='imagens/checked.png' class='imgBtnAcao'></button>";
                        }elseif($idsituacao == 2){
                            $retorno .= "
                                <button class='btnAcaoRegistro pendente' id='pendente$id'><img src='imagens/checked-false.png' class='imgBtnAcao'></button>";
                        }
                    $retorno .= "
                    </td>
                </tr>";
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
