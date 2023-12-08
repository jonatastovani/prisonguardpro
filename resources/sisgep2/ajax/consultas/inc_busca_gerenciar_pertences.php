<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    //recupera os dados de tipo
    $datainicio = $_POST['datainicio'];
    $datafinal = $_POST['datafinal'];
    $situacao = $_POST['situacao'];
    $textobusca = $_POST['textobusca'];
    $filtrotexto = $_POST['filtrotexto'];
    $tipo = $_POST['tipo'];

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){ 
        try {
            $params=[];
            $where = '';
            //Adiciona na ordem os parâmetros
            for($i=0;$i<2;$i++){
                array_push($params,"$datainicio 00:00:00");
                array_push($params,"$datafinal 23:59:59");
            }

            if(strlen($textobusca)>0){
                if($filtrotexto==1){
                    $palavas = retornaArrayPalavras($textobusca);
                }else{
                    $palavas = array($textobusca);
                }
                
                $where = "AND (";
                foreach($palavas as $tex){
                    if($where != "AND ("){
                        $where .= " OR ";                        
                    }
                    $where .= "IP.IDPRESO like ? OR IP.NOMERETIRADA like ? OR IGP.NOME like ? OR IP.OBSERVACOES like ? OR EP.MATRICULA like ? OR EP.NOME like ? OR EP.RG like ? OR EP.INFORMACOES like ? OR GSA.ID like ? OR GSA.NOME like ? OR CD.NOME like ? OR CID1.NOME like ? OR CID2.NOME like ? OR EST1.NOME like ? OR EST2.NOME like ? OR NC.NOME like ? OR CD.RG like ? OR CD.CPF like ? OR CD.OUTRODOC like ? OR CD.PAI like ? OR CD.MAE like ? OR CD.OBSERVACOES like ?";
                    //Quantidade de substiuições que vão ser inseridas
                    for($i=0;$i<22;$i++){
                        array_push($params,"%$tex%");
                    }
                }
                $where .= ")";
            }

            if($tipo==2 || $tipo==1){
                $tipo = "1,2";
            }

            if($situacao==1){
                //array_push($params,$situacao);
                $situacao = "AND IP.DESCARTADO = FALSE AND IP.IDGRAUPARENTESCO IS NULL";
            }elseif($situacao==2){
                //array_push($params,$situacao);
                $situacao = "AND IP.IDGRAUPARENTESCO IS NOT NULL";
            }elseif($situacao==3){
                //array_push($params,$situacao);
                $situacao = "AND IP.DESCARTADO = TRUE";
            }
            else{
                $situacao = '';
            }

            $sql = "SELECT IP.ID, EP.ID IDPRESO, EP.MATRICULA, IP.NOMERETIRADA, IGP.NOME GRAUPARENTESCO, IP.DATAENTRADA, IP.DATARETIRADA, IP.OBSERVACOES, IP.DESCARTADO, IP.DATADESCARTADO,
            CASE EP.LANCADOCIMIC WHEN FALSE THEN EP.NOME ELSE CD.NOME END NOME, FUNCT_dados_raio_cela_preso(EP.ID, CURRENT_TIMESTAMP, 2) RAIO, FUNCT_dados_raio_cela_preso(EP.ID, CURRENT_TIMESTAMP, 3) CELA
            FROM inc_pertences IP
            INNER JOIN entradas_presos EP ON IP.IDPRESO = EP.ID
            INNER JOIN entradas E ON E.ID = EP.IDENTRADA
            INNER JOIN codigo_gsa GSA ON GSA.ID = E.IDORIGEM
            LEFT JOIN tab_grauparentesco IGP ON IGP.ID = IP.IDGRAUPARENTESCO
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            LEFT JOIN tab_cidades CID1 ON CID1.ID = CD.IDCIDADENASC
            LEFT JOIN tab_cidades CID2 ON CID2.ID = CD.IDCIDADEMORADIA
            LEFT JOIN tab_estados EST1 ON EST1.ID = CD.IDESTADONASC
            LEFT JOIN tab_estados EST2 ON EST2.ID = CD.IDESTADONASC
            LEFT JOIN tab_nacionalidade NC ON NC.ID = CD.NACIONALIDADE
            WHERE IP.IDTIPOPERTENCE IN ($tipo) AND (IP.DATAENTRADA >= ? AND IP.DATAENTRADA <= ? OR IP.DATARETIRADA >= ? AND IP.DATARETIRADA <= ?) $where $situacao AND IP.IDEXCLUSOREGISTRO IS NULL AND IP.DATAEXCLUSOREGISTRO IS NULL ORDER BY IP.ID DESC;";

            //echo json_encode($sql); exit();

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute($params);

            $resultado = $stmt->fetchAll();
            $retorno="";

            foreach($resultado as $dados){
                $id = $dados['ID'];
                $idpreso = $dados['IDPRESO'];
                $matr = $dados['MATRICULA'];
                if($matr>0){
                    $matricula = midMatricula($matr,3);
                }else{
                    $matricula = '';
                }
                $nome = $dados['NOME'];
                $nomeretirada = $dados['NOMERETIRADA'];
                $grau = $dados['GRAUPARENTESCO'];
                $dataentrada = $dados['DATAENTRADA'];
                $dataretirada = $dados['DATARETIRADA'];
                $observacoes = $dados['OBSERVACOES'];
                $descartado = $dados['DESCARTADO'];
                $datadescartado = $dados['DATADESCARTADO'];
                $raio = $dados['RAIO'];
                $cela = $dados['CELA'];
                $raiocela = $dados['RAIO']!=null?$dados['RAIO']:"N/C";
                $raiocela .= ($dados['CELA']!=null && $dados['CELA']>0)?"/". $dados['CELA']:"";

                $dataentrada = retornaDadosDataHora($dados['DATAENTRADA'],2);
                if($dataretirada!==null){
                    $dataretirada = retornaDadosDataHora($dataretirada,2);
                }
                
                $retorno .= "
                <tr class='cor-fundo-comum-tr'>
                    <td><input type='checkbox' id='$id' data-preso='$idpreso' value='$id'></td>
                    <td class='centralizado' style='min-width: 50px;'>
                        <button id='altpertence$id' class='btnAcaoRegistro alterarpertence' title='Abrir pertence'><img src='imagens/pertences-preso.png' class='imgBtnAcao'></button>";

                        if($descartado == 1){
                            $retorno .= "
                                <button class='btnAcaoRegistro descartado' title='Desfazer descarte' id='refazer$id'><img src='imagens/refazer.png' class='imgBtnAcao'></button>";
                        }elseif($descartado == 0 && $grau == null){
                            $retorno .= "
                                <button class='btnAcaoRegistro pendente' title='Descartar pertence' id='descartar$id'><img src='imagens/lixeira.png' class='imgBtnAcao'></button>";
                        }
                        $retorno .= "
                    </td>

                    <td class='centralizado'>$id</td>
                    <td class='centralizado dataentrada' id='dataentrada$id'>$dataentrada</td>
                    <td style='min-width: 350px; max-width: 450px;'>$nome</td>
                    <td class='centralizado min-width-100'>$matricula</td>
                    <td class='centralizado'>$raiocela</td>
                    <td style='min-width: 250px; max-width: 450px;'>$nomeretirada</td>
                    <td class='centralizado min-width-100'>$grau</td>
                    <td class='centralizado'>$dataretirada</td>
                    <td class='overflow-y min-width-200 max-width-400'>$observacoes</td>
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
