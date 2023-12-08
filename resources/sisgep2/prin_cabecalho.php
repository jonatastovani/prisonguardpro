<!-- Cabeçalho principal exibido na página principal em todos os carregamentos de páginas -->

<?php
    require_once "configuracoes/conexao.php";

    $id_usuario = isset($_SESSION['id_usuario'])?$_SESSION['id_usuario']:0;
    $id_boletim = isset($_SESSION['id_boletim'])?$_SESSION['id_boletim']:0;
    $nome_usuario = "ERRO DE USUÁRIO";

    $conexaoStatus = conectarBD();
    if($conexaoStatus==true){
        //Obtem o nome do usuário para exibir no cabeçalho
        if($id_usuario!=0){
            $sql="SELECT NOME FROM tab_usuarios WHERE ID = :id_usuario";
            
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam(':id_usuario',$id_usuario,PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetchAll();
            if(count($resultado)==1){
                $nome_usuario = $resultado[0]['NOME'];
            }else{
                $nome_usuario = "ERRO DE USUÁRIO";
            }
        }
        $sql="SELECT * FROM chefia_boletim WHERE BOLETIMDODIA = True";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll();

        if(count($resultado)){
            $id_boletim = $resultado[0]['ID'];
            $_SESSION['id_boletim']=$resultado[0]['ID'];
        }
        else{
            $id_boletim = 0;
            $_SESSION['id_boletim']=0;
            header('Location: index.php');
        }

        //Busca dados da unidade
        $sql = "SELECT ucase(concat(UNT.NOME, ' de ', UN.NOMEUNIDADE)) NOMEUNIDADE, UN.NOMEATRIBUIDO, CID.NOME CIDADE FROM tab_dadosunidade DU
        INNER JOIN tab_unidades UN ON DU.IDUNIDADE = UN.ID
        INNER JOIN tab_cidades CID ON CID.ID = UN.IDCIDADE
        INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
        WHERE DU.ID = 1;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetchAll();
        $Nome_unidade = $resultado[0]['NOMEUNIDADE'];
        $Nome_atribuido = $resultado[0]['NOMEATRIBUIDO'];
        global $Cidade_unidade;
        $Cidade_unidade = $resultado[0]['CIDADE'];

        //Se houver boletim diário, então é apresentado na barra -->

        $sql = "SELECT BI.ID, BI.NUMERO, TU.NOME TURNO, date_format(DATABOLETIM, '%Y') ANOBOLETIM FROM chefia_boletim BI
        INNER JOIN tab_turnos TU ON BI.IDTURNO = TU.ID
        WHERE BI.BOLETIMDODIA = TRUE";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        
        if(count($resultado)){
            $num_boletim = ArrumaNumeroBoletim($resultado[0]['NUMERO'],$resultado[0]['ANOBOLETIM']);
            $num_boletim .= " - ".$resultado[0]['TURNO'];
        }
    }
?>

<header>
    <div class="principal">
        <div id="dados-unidade">

            <?php
                // Colocar o nome da unidade no efeito
                // $nomecss = '';
                // for($i=0;$i<strlen($Nome_unidade);$i++){
                //     $nomecss .= "" . substr($Nome_unidade,$i,1);
                // }
            ?>

            <h1 id="nomeunidade"><?=$Nome_unidade?></h1>
            <section>
                <ul id="ulnomeunidade"></ul>
            </section>
            <h2><?=$Nome_atribuido?></h2>
        </div>
        <nav class="cabecalho-principal">
            <div class="flex largura-restante">
                <a href="principal.php" rel="next" target="_self"><img src="imagens/home-32.png" class="img20"> Início</a>
                <a href="principal.php?menuop=seldoc" rel="next" target="_self"><img src="imagens/imprimir-32.png" class="img20"> Impressões</a> <?php
                if($_SESSION['id_usuario']==2){ ?>
                    <a href="principal.php?menuop=teste" rel="next" target="_self"><img src="imagens/teste.png" class="img20">Teste</a>
                    <a href="principal.php?menuop=testeajax" rel="next" target="_self"><img src="imagens/teste.png" class="img20">Teste Ajax</a> <?php
                }?>
            </div>
            <div class="flex align-rig" style="width: 300px;">
                <div style="width: 100%;">
                    <a href="#" style="display: inline-block;" rel="next" target="_self"><img src="imagens/usuario.png" alt="Usuário" class="img20"> <b><?=$nome_usuario?></b></a>
                    <a href="configuracoes/logout.php" style="display: inline-block;" rel="next" target="_self"><img src="imagens/sair-32.ico" class="img20"> Sair</a>
                </div>
            </div>
        </nav>
    </div>
    <div style="position: fixed; z-index: 1; top: 0;">
        <ul id="mensagem"></ul>
    </div>

    <!-- Se houver sessão iniciada, então é apresentado na barra -->
    <?php if($id_usuario!=0){ ?>
    <?php } ?>
</header>

<?php
    //Fecha a conexão
    unset($GLOBALS['conexao']);

