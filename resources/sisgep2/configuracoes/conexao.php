<?php

function conectarBD(){
    $servidor = '10.14.239.102';
    $usuario = 'aplicacaosisgep2';
    $senha = 'jon123';
    $bancodedados = 'sistemaphp';

    try {
        $GLOBALS['conexao'] = new PDO("mysql:host=$servidor;dbname=$bancodedados", $usuario, $senha,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $GLOBALS['conexao']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SET lc_time_names = 'pt_BR';";
        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute();

        return true;  
    } catch (PDOException $e) {
        return 'Ocorreu um erro ao conectar ao Banco de Dados. Erro: ' . $e->getMessage();
    }
}

/*if(Conectar()==true){
    echo 'Conectado <br>';
    $id = 1;
    try {
        $stmt = $GLOBALS['conexao']->prepare('SELECT E.ID, E.DATAENTRADA, COD.NOME FROM entradas E INNER  JOIN codigo_gsa COD ON E.IDORIGEM = COD.ID WHERE E.ID = :id');
        $stmt->execute(array('id' => $id));

        while($row = $stmt->fetch()) {
            print_r($row);
        }
    } catch (PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
}

$conexao = mysqli_connect($servidor, $usuario, $senha, $bancodedados);

//Realiza a conexão com o Banco de Dados
if(mysqli_connect_error()){
    echo "Falha na conexão: " . mysqli_connect_error();
}

//Realiza o SET do UTF-8
if (!mysqli_set_charset($conexao, 'utf8')) {
    printf('Error ao usar utf8: %s', mysqli_error($conexao));
    exit;
}*/