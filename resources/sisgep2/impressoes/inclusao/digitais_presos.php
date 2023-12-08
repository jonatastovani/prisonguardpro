<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    $permissoesNecessarias = array(3,8);
    $redirecionamento = "../acesso_negado.php";
    $blnPermitido = false;

    $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "Digitais-".date('YmdHis').".pdf";

    $identrada = isset($_GET['identrada'])?$_GET['identrada']:0;
    $idpreso = isset($_GET['idpreso'])?$_GET['idpreso']:0;
    $retorno = [];
    $idbusca = [];
    $colunabusca = '';

    if($idpreso!=0){
        $idbusca = explode(',', $idpreso);
        $colunabusca = 'EP.ID';
    }elseif($identrada!=0){
        $idbusca = explode(',', $identrada);
        $colunabusca = 'E.ID';
    }else{
        echo 'Nenhum ID de Entrada ou Preso foi informado';
        exit();
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        foreach($idbusca as $id){
            $sql = "SELECT EP.ID IDPRESO, EP.NOME, EP.MATRICULA, EPERT.ID IDPERTENCE
            FROM entradas_presos EP
            INNER JOIN entradas E ON E.ID = EP.IDENTRADA
            INNER JOIN inc_pertences EPERT ON EP.ID = EPERT.IDPRESO
            WHERE MD5($colunabusca) = :id AND EPERT.IDTIPOPERTENCE = 1";

            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam("id",$id,PDO::PARAM_STR);
            $stmt->execute();

            $resultado = $stmt->fetchAll();
            if(count($resultado)){ ?>
                <div style="align-items: center;">
                    <?php
                        foreach($resultado as $preso){
                            $matricula = $preso['MATRICULA']?midMatricula($preso['MATRICULA'],3):'';
                            $nome = $preso['NOME']; ?>

                            <table style="width: 100%;">
                                <table style="width: 100%; border: 1px solid black; text-align: center; font-size: 10pt; margin: 0px 0px 15px;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: left;" colspan="6">Número Pertence: <?=$preso['IDPERTENCE']?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;" colspan="4">NOME: <?=$nome?></td>
                                            <td style="text-align: left;" colspan="2">MATRÍCULA: <?=$matricula?></td>
                                        </tr>
                                        <tr> <!-- Linha em branco-->
                                            <td style="height: 5px; border: none;" colspan="6"></td>
                                        </tr><?php

                                        for($i=0;$i<2;$i++){ ?>

                                            <tr>
                                                <td style="width: 20px;"></td>
                                                <td style="width: 100px;">POLEGAR</td>
                                                <td style="width: 100px;">INDICADOR</td>
                                                <td style="width: 100px;">MÉDIO</td>
                                                <td style="width: 100px;">ANELAR</td>
                                                <td style="width: 100px;">MÍNIMO</td>
                                            </tr>
                                            <tr>
                                                <td style=" height: 150px;"> <?php
                                                
                                                    if($i==0){ ?>
                                                        D<br>I<br>R<br>E<br>I<br>T<br>A <?php
                                                    }else{ ?>
                                                        E<br>S<br>Q<br>U<br>E<br>R<br>D<br>A <?php
                                                    }
                                                    ?>

                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr> <!-- Linha em branco-->
                                                <td style="height: 20px; border: none;" colspan="6"></td>
                                            </tr> <?php

                                        } ?>

                                        <tr>
                                            <td style="text-align: left; height: 30px;" colspan="6">ASSINATURA DO DETENTO:</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </table>
                        <?php }
                    ?>
                </div>
            <?php }
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
