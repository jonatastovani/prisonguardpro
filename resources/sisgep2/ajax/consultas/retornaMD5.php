<?php
    header('Content-Type: application/json');

    $valor = $_POST['valor'];

    //Cria a variável de retorno para salvar os a mensagem a ser exibida na tela
    $retorno = [];
    $codigo = '';

    if(!empty($valor)){
        $valor = md5($valor);
        $retorno = array('OK' => "$valor");
        echo json_encode($retorno);
        exit();
    }else{
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma informação foi inserida para ser codificada </li>");
        echo json_encode($retorno);
        exit();
    }