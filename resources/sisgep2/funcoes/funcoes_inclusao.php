<?php

//Retorna uma string com as Entradas existentes para ser inserido no select
function inserirKitEntregue($idpreso){
    include_once "configuracoes/conexao.php";

    //Busca todos os kits que já foram entregues para o preso no id informado
    $sql = "SELECT KE.ID VALOR, concat(date_format(KE.DATAENTREGA,'%d/%m/%Y'), ' (', KTE.NOME, ')') NOMEEXIBIR
    FROM inc_kitentregue KE
    INNER JOIN entradas_presos EP ON KE.IDPRESO = EP.ID
    INNER JOIN inc_kittipoentrega KTE ON KTE.ID	= KE.IDTIPOENTREGA
    WHERE KE.IDPRESO = :idpreso AND KE.IDEXCLUSOREGISTRO IS NULL AND KE.DATAEXCLUSOREGISTRO IS NULL ORDER BY KE.DATAENTREGA DESC";
    $retorno = "";
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->bindParam('idpreso', $idpreso, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetchAll();
        //Verifica se foi encontrado algum registro
        if(count($resultado)){
            foreach($resultado as $dados){
                $retorno .= "<option value=".$dados['VALOR'].">".$dados['NOMEEXIBIR']."</option>";
            }
        }
        else{
            $retorno = "<option value=".$dados['VALOR'].">Nenhum Kit Entregue foi encontrado</option>";
        }
    }else{
        $retorno = "<option value='0'>".$conexaoStatus."</option>";
    }
    return $retorno;
    //unset($GLOBALS['conexao']);
}


