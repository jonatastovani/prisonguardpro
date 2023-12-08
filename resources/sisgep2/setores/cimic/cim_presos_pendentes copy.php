<?php
    include_once "configuracoes/conexao.php";
?>
<div class="titulo-pagina">
    <h1 id="titulo">Presos Pendentes</h1>
</div>

<div id="container-flex">
    <?php
        $conexaoStatus = conectarBD();
        if($conexaoStatus===true){
            try {
                $contador = 1;

                $sql="SELECT EP.ID, EP.NOME, EP.MATRICULA, EP.MATRICULAVINCULADA, EP.RG, 
                E.DATAENTRADA, 
                GSA.NOME ORIGEM,
                CD.NOME NOMEANTERIOR
                FROM entradas_presos EP
                INNER JOIN entradas E ON EP.IDENTRADA = E.ID
                INNER JOIN codigo_gsa GSA ON E.IDORIGEM = GSA.ID
                LEFT JOIN cadastros CD ON EP.MATRICULA = CD.MATRICULA
                WHERE LANCADOCIMIC = FALSE AND EP.ID > 0 AND EP.IDEXCLUSOREGISTRO IS NULL AND E.IDEXCLUSOREGISTRO IS NULL;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                if(count($resultado)){
                    foreach($resultado as $preso){ 
                        $blnPresoVinculado = false; //Caso o preso já houver passado, então se mostra que este preso está vinculado a um cadastro anterior. Pode se usar a coluna MATRICULAVINCULADA, mas dessa maneira somente se confirma mais uma vez se está vinculado ou não
                        if(!empty($preso['NOMEANTERIOR'])){
                            $blnPresoVinculado = true;
                        }
                        $id = $preso['ID'];
                        $nome = $preso['NOME'];
                        $nomevinculado = $preso['NOMEANTERIOR'];
                        $exibirnomevinculado = false;
                        if($nome != $nomevinculado && $nomevinculado!=null){
                            $exibirnomevinculado = true;
                        }
                        $matricula = $preso['MATRICULA'];
                        $matriculavinculada = 0;
                        if($preso['MATRICULAVINCULADA']){
                            $matriculavinculada = $matricula;
                        }
                        $matriculadigito = midMatricula($matricula,3);
                        $digito = midMatricula($matricula,2);
                        $matricula = midMatricula($matricula,1);
                        $rg = $preso['RG'];
                        $dataentrada = $preso['DATAENTRADA'];
                        $origem = $preso['ORIGEM'];

                        ?>
                        
                        <form id="form<?=$contador?>" class=" item-flex form-preso-pendente" action="principal.php?menuop=cim_incluir_presos" method="post">
                        <div style="display: flex;">
                            <div class="div-metade-aling-esquerda">
                                <h2 class="titulo-grupo">Preso <?=$contador?></span></h2>
                            </div>
                            <div class="div-metade-aling-direita">
                                <span <?php if($matriculavinculada==0){echo "hidden";}?> >Mat. Vinculada</span>
                            </div>
                        </div>  
                            Nome: <?=$nome?><br>
                            <input type="hidden" name="idpresobancodados" id="idpresobancodados" value="<?=$id?>">
                            <input type="hidden" name="tipoacao" id="tipoacao<?=$contador?>" value="incluir">
                            <?php if($exibirnomevinculado==true){ ?>
                                    Nome vinculado: <?=$nomevinculado?><br>
                                <?php } ?>
                            Matrícula: <?=$matriculadigito?><br>
                            <?php if(!empty($rg)){ ?>
                                <span style="padding-left: 15px;">RG:</span> <?=$rg?><br>
                            <?php } ?>
                            Origem: <?=$origem?>
                            <div style="text-align: right;">
                                <button type="submit" name="incluir">Incluir</button>
                                <button type="excluir" id="excluir">Excluir</button>
                            </div>
                        </form>

                        <?php $contador++;
                    }
                }else{
                    echo "<h1>Não existem presos pendentes</h1>";
                }

            } catch (PDOException $e) {
                echo json_encode("Ocorreu um erro. Erro: ". $e->getMessage());
            }
        }else{
            echo "Ocorreu um erro. Erro: $conexaoStatus";
        }
    ?>
</div>