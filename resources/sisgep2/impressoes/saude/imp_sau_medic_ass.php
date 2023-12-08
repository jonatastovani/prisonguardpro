<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    // $permissoesNecessarias = array(7);//array(3,4,5,6,7);
    // $redirecionamento = "../acesso_negado.php";
    // $blnPermitido = false;

    // $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "PresosEntregaAssistida-".date('YmdHis').".pdf";

    // echo var_dump($_GET);

    // $idspreso = isset($_GET['idspreso'])?$_GET['idspreso']:0;
    // $idsperiodo = isset($_GET['idsperiodo'])?$_GET['idsperiodo']:0;

    // $where = '';
    // $explodepreso = explode(",",$idspreso);
    // $explodeperiodo = explode(",",$idsperiodo);

    $params = [];
    
    // $where = 'AND (';
    // for($i=0;$i<count($explodepreso);$i++){
    //     if($where!='AND ('){
    //         $where .= " OR ";
    //     }
    //     $where .= "(md5(EMA.IDPRESO) = ?";
    //     array_push($params,$explodepreso[$i]);
    //     $where .= " AND md5(EMA.IDPERIODOENTREGA) = ?)";
    //     array_push($params,$explodeperiodo[$i]);
    // }
    // $where .= ')';
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $sql = "SELECT EMA.ID IDASS, EMA.IDMEDICAMENTO, MED.NOME NOMEMEDICAMENTO, EMA.QTDENTREGA, FORN.SIGLA UNIDADEFORN, EMA.IDPRESO, CAD.MATRICULA, CAD.NOME, NULL DATAENTREGUE, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, CURRENT_TIMESTAMP, 1) IDRAIO, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, CURRENT_TIMESTAMP, 2) RAIO, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, CURRENT_TIMESTAMP, 3) CELA, EMA.IDPERIODOENTREGA IDPERIODO, PER.NOME PERIODO, EMA.DATAINICIO, EMA.DATATERMINO
        FROM enf_medic_assistido EMA
        INNER JOIN enf_medicamentos MED ON MED.ID = EMA.IDMEDICAMENTO
        INNER JOIN tab_unidadesfornecimento FORN ON FORN.ID = MED.IDUNIDADE
        INNER JOIN entradas_presos EP ON EP.ID = EMA.IDPRESO
        INNER JOIN cadastros CAD ON CAD.MATRICULA = EP.MATRICULA
        INNER JOIN tab_periodos PER ON PER.ID = EMA.IDPERIODOENTREGA
        WHERE (date_format(EMA.DATATERMINO,'%Y-%m-%d') >= CURRENT_DATE OR EMA.DATATERMINO IS NULL) AND EMA.IDEXCLUSOREGISTRO IS NULL AND EP.IDEXCLUSOREGISTRO IS NULL
        ORDER BY NOME,IDPRESO,IDPERIODO,MED.NOME;";

        // echo "<p>$sql</p>";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);

        $resultado = $stmt->fetchAll();
        // echo "<p>".count($resultado)."</p>";
        
        if(count($resultado)){?>

                <h1 class="titulo" style="padding-bottom: 0px;">Relação de Presos com Entrega Assistida de Medicamento</h1> <?php
                
            for($i=0;$i<count($resultado);$i++){
                $idpreso=$resultado[$i]['IDPRESO'];                    
                $matricula = midMatricula($resultado[$i]['MATRICULA'],3);
                $nome = $resultado[$i]['NOME'];
                $raiocela = $resultado[$i]['RAIO']."/".$resultado[$i]['CELA'];
                ?>

                <table style="width: 100vw;">
                    <table style="width: 100%; text-align: center; font-size: 11pt; margin-bottom: 15px;">
                        <thead>
                            <tr style="background-color: lightgray;">
                                <th class="align-lef" style="border: 2px solid black; padding-left: 10px;" colspan="5"><span style="font-weight: normal;">Matrícula:</span> <?=$matricula?> | <span style="font-weight: normal;">Nome:</span> <?=$nome?> |  <span style="font-weight: normal;">R/C:</span> <?=$raiocela?></th>
                            </tr>
                            <tr style="background-color: lightgray;">
                                <th style="border: 2px solid black;">Medicamento</th>
                                <th style="border: 2px solid black;">Qtd</th>
                                <th style="border: 2px solid black;">Período</th>
                                <th style="border: 2px solid black;">Início</th>
                                <th style="border: 2px solid black;">Término</th>
                            </tr>
                        </thead>
                        <tbody><?php
                            for($i=$i;$i<count($resultado);$i++){
                                if($idpreso==$resultado[$i]['IDPRESO']){
                                    $nomemedic = $resultado[$i]['NOMEMEDICAMENTO'];
                                    $qtd = $resultado[$i]['QTDENTREGA'];
                                    $periodo = $resultado[$i]['PERIODO'];
                                    $inicio = retornaDadosDataHora($resultado[$i]['DATAINICIO'],2);
                                    $termino = '';
                                    if($resultado[$i]['DATATERMINO']!=null){
                                        $termino = retornaDadosDataHora($resultado[$i]['DATATERMINO'],2);
                                    }
                                    ?>
                                    <tr>
                                        <td class="align-lef"><?=$nomemedic?></td>
                                        <td class="align-cen"><?=$qtd?></td>
                                        <td class="align-cen"><?=$periodo?></td>
                                        <td class="align-cen nowrap"><?=$inicio?></td>
                                        <td class="align-cen nowrap"><?=$termino?></td>
                                    </tr><?php
                                    $ultimo = $i;
                                }
                            }
                            $i = $ultimo;?>
                        </tbody>
                    </table>
                </table><?php
                
            }
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
