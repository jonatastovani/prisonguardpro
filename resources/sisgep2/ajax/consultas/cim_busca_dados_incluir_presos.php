<?php
/*Formulário para incluir presos.
Todos presos que chegarem são inclusos aqui.*/
header('Content-Type: application/json');

include_once '../../configuracoes/conexao.php';
include_once '../../funcoes/funcoes_comuns.php';

//Cria a variável de retorno para salvar os a mensagem a ser exibida na tela
$retorno = [];
$idpreso = $_POST['idpreso'];

if(empty($idpreso)){
    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma IDPRESO foi informado </li>");
    echo json_encode($retorno);
    exit;
}

$conexaoStatus = conectarBD();
if($conexaoStatus===true){
    try {

        $sql="SELECT EP.ID, EP.NOME, EP.MATRICULA, EP.MATRICULAVINCULADA, EP.RG,
        E.ID IDENTRADA, E.DATAENTRADA, date_format(E.DATAENTRADA, '%d/%m/%Y %H:%i') DATAHORAENTRADA, EP.PAI, EP.MAE,
        GSA.NOME ORIGEM, GSA.ID IDORIGEM,
        CD.NOME NOMEVINCULADO, CD.DATANASC, CD.IDCIDADENASC, CD.IDESTADONASC, CD.RG RGVINCULADO, CD.CPF, CD.OUTRODOC, CD.OBSERVACOES,
        (SELECT EP2.ID FROM entradas_presos EP2 WHERE EP2.MATRICULA = EP.MATRICULA AND EP2.ID <> EP.ID AND EP2.IDEXCLUSOREGISTRO IS NULL AND EP2.DATAEXCLUSOREGISTRO IS NULL ORDER BY EP2.ID DESC LIMIT 1) IDANTIGO
        FROM entradas_presos EP
        INNER JOIN entradas E ON EP.IDENTRADA = E.ID
        INNER JOIN codigo_gsa GSA ON E.IDORIGEM = GSA.ID
        LEFT JOIN cadastros CD ON EP.MATRICULA = CD.MATRICULA
        WHERE EP.ID = :idpreso;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->bindParam('idpreso',$idpreso,PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetchAll();
        //unset($GLOBALS['conexao']);

        if(count($resultado)){
            $matricula = $resultado[0]['MATRICULA'];

            if(!empty($matricula)){
                $matriculadigito = midMatricula($matricula,3);
                //Baixa a foto do preso para o servidor local
                baixarFotoServidor($matriculadigito,1,"../../");
            }
            echo json_encode($resultado);
            exit;
        }
        else{
            $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma dado foi encontrada para o IDPRESO informado </li>");
            echo json_encode($retorno);
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