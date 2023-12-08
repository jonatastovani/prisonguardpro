<?php

//Verifica se o usuário tem a permissão de imprimir recibo de presos;
$permissoesNecessarias = array(7);//array(3,4,5,6,7);
$redirecionamento = "../acesso_negado.php";
$blnPermitido = false;

$blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
$nomearquivo = "ReciboDePresos-".date('YmdHis').".pdf";

    $identrada = isset($_GET['identrada'])?$_GET['identrada']:'';
    $retorno = [];

    if(empty($identrada)){
        echo 'Nenhum ID de Entrada foi informado';
        exit();
    }

    $entradas = explode(',', $identrada);
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        foreach($entradas as $entrada){
            $sql = "SELECT E.DATAENTRADA, GSA.NOME ORIGEM, EP.MATRICULA, EP.NOME, EP.RG, date_format(E.DATAENTRADA,'%d de %M de %Y') DATAFINALPAGINA
            FROM entradas_presos EP
            INNER JOIN entradas E ON E.ID = EP.IDENTRADA
            INNER JOIN codigo_gsa GSA ON GSA.ID = E.IDORIGEM
            WHERE MD5(IDENTRADA) = :entrada;";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('entrada',$entrada,PDO::PARAM_STR);
            $stmt->execute();

            $resultado = $stmt->fetchAll();
            if(count($resultado)){
                $texto = "<p class='indent'>Declaramos através deste, o recebimento do(s) sentenciado(s) abaixo relacionado(s), procedente(s) da(o) <strong>".$resultado[0]['ORIGEM']."</strong>, para aguardar(em) julgamento judicial.</p><br>";
                //var_dump($resultado);
                
                ?>
                <table>
                    <h1 class="titulo">RECIBO DE DETENTOS</h1>
                    <?php
                    echo $texto;?>
                    <div style="align-items: center;">
                        <table style="width: 100%; border: 1px solid black; text-align: center;">
                            <thead>
                                <tr style="background-color: lightgray;">
                                    <th style="width: 40px; border: 2px solid black;"></th>
                                    <th style="border: 2px solid black;">DETENTO</th>
                                    <th style="width: 120px; border: 2px solid black;">MATRÍCULA</th>
                                    <th style="width: 120px; border: 2px solid black;">RG</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $contador = 1;
                                    foreach($resultado as $preso){
                                        $matricula = $preso['MATRICULA']?midMatricula($preso['MATRICULA'],3):'';
                                        $nome = $preso['NOME'];
                                        $rg = $preso['RG']; ?>
                                        <tr>
                                            <td><?=$contador?></td>
                                            <td style="text-align: left;"><?=$nome?></td>
                                            <td><?=$matricula?></td>
                                            <td><?=$rg?></td>
                                        </tr> <?php
                                        $contador++;
                                    } ?>
                            </tbody>
                        </table>
                    </div>
                    <p class="align-rig" style="padding: 100px 0px;"><?= $GLOBALS['Cidade_unidade'].', '. $preso['DATAFINALPAGINA']?></p>
                    
                </table>
                <?php               
            }
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
