<?php  
    /*require_once "configuracoes/conexao.php";

    //Obtem o status da conexão. Se retornar com o valor true então significa que a conexão foi efetuada com sucesso, do contrário se exibe o erro no else.
    $statusConexao = conectarBD();

    if($statusConexao==true){

        $sql = "SELECT * FROM tab_setores WHERE SETORATIVO = TRUE ORDER BY NOME";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        //$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetchAll();

        if(count($resultado)){
            ?><div class="container-setores"><?php 
                foreach ($resultado as $setor) {
                    ?><a href="principal.php?menuop=<?=$setor['CAMINHOPASTA']?>">
                        <div class="setor">
                            <h1><?=$setor['NOME']?></h1>
                        </div>
                    </a><?php
                }
            ?></div><?php
        }
    }else{
        //Caso não houver conexão com o banco de dados então se exibe o erro encontrado na conexão
        echo $statusConexao;
    } */?>

    <div class="container-setores">
        <a href="principal.php?menuop=cimic">
            <div class="setor">
                <h1>CIMIC</h1>
            </div>
        </a>
        <a href="principal.php?menuop=chefia">
            <div class="setor">
                <h1>Chefia</h1>
            </div>
        </a>
        <a href="principal.php?menuop=inclusao">
            <div class="setor">
                <h1>Inclusão</h1>
            </div>
        </a>
        <a href="principal.php?menuop=rol">
            <div class="setor">
                <h1>Rol</h1>
            </div>
        </a>
        <a href="principal.php?menuop=saude">
            <div class="setor">
                <h1>Saúde</h1>
            </div>
        </a>
    </div>


