<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";
    
    $tipo = $_POST['tipo'];

    //Verifica se o usuário tem a permissão para executar ou mostrar algo a mais na tela via ajax
    if($tipo==1){
        // $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> $permissoes </li>");
        // echo json_encode($retorno);
        // exit();
        $permissoes = explode(',',$_POST['permissoes']);

        $retorno = verificaPermissao($permissoes);
        $retorno = array('PERMISSAO' => $retorno);
        echo json_encode($retorno);
        exit();
    }
