<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    // $permissoesNecessarias = array(7);//array(3,4,5,6,7);
    // $redirecionamento = "../acesso_negado.php";
    // $blnPermitido = false;

    // $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "MedicamentosAssistidosEntregar-".date('YmdHis').".pdf";

    // echo var_dump($_GET);

    $idspreso = isset($_GET['idspreso'])?$_GET['idspreso']:0;
    $idsperiodo = isset($_GET['idsperiodo'])?$_GET['idsperiodo']:0;

    $where = '';
    $explodepreso = explode(",",$idspreso);
    $explodeperiodo = explode(",",$idsperiodo);

    // $params = [$datainicio,$datafinal];
    $params = [];
    
    // if(count($explodeperiodo)==count($explodepreso) && count($explodepreso)>0 && ($idspreso!=0 || $idsperiodo!=0)){
        $where = 'AND (';
        for($i=0;$i<count($explodepreso);$i++){
            if($where!='AND ('){
                $where .= " OR ";
            }
            $where .= "(md5(EMA.IDPRESO) = ?";
            array_push($params,$explodepreso[$i]);
            $where .= " AND md5(EMA.IDPERIODOENTREGA) = ?)";
            array_push($params,$explodeperiodo[$i]);
        }
        $where .= ')';
        // echo "<p>$where</p>";
    // }
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $sql = "SELECT EMA.ID IDASS, EMA.IDMEDICAMENTO, MED.NOME NOMEMEDICAMENTO, EMA.QTDENTREGA, FORN.SIGLA UNIDADEFORN, EMA.IDPRESO, CAD.MATRICULA, CAD.NOME, NULL DATAENTREGUE, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, CURRENT_TIMESTAMP, 1) IDRAIO, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, CURRENT_TIMESTAMP, 2) RAIO, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, CURRENT_TIMESTAMP, 3) CELA, EMA.IDPERIODOENTREGA IDPERIODO, PER.NOME PERIODO, EMA.DATAINICIO, EMA.DATATERMINO, NULL COR, 1 ORDEM
        FROM enf_medic_assistido EMA
        INNER JOIN enf_medicamentos MED ON MED.ID = EMA.IDMEDICAMENTO
        INNER JOIN tab_unidadesfornecimento FORN ON FORN.ID = MED.IDUNIDADE
        INNER JOIN entradas_presos EP ON EP.ID = EMA.IDPRESO
        INNER JOIN cadastros CAD ON CAD.MATRICULA = EP.MATRICULA
        INNER JOIN tab_periodos PER ON PER.ID = EMA.IDPERIODOENTREGA
        WHERE date_format(EMA.DATAINICIO,'%Y-%m-%d') <= CURRENT_DATE AND (EMA.DATATERMINO = CURRENT_DATE OR EMA.DATATERMINO IS NULL) AND 
            EMA.ID NOT IN (SELECT DISTINCT EMAE.IDASS
            FROM enf_medic_assistido_entregue EMAE
            WHERE date_format(EMAE.DATAENTREGUE,'%Y-%m-%d') = CURRENT_DATE AND EMAE.IDEXCLUSOREGISTRO IS NULL)
        AND EMA.IDEXCLUSOREGISTRO IS NULL AND EP.IDEXCLUSOREGISTRO IS NULL $where
        
        ORDER BY DATAENTREGUE DESC, NOME, IDPERIODO, RAIO, CELA;";

        // echo "<p>$sql</p>";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);

        $resultado = $stmt->fetchAll();
        // echo "<p>".count($resultado)."</p>";
        
        if(count($resultado)){?>

                <h1 class="titulo" style="padding-bottom: 0px;">Relação Medicamentos a Entregar</h1>
                
                <div style="align-items: center;">
                    <table style="width: 100%; text-align: center; font-size: 11pt;">
                        <thead>
                            <tr style="background-color: lightgray;">
                                <th style="border: 2px solid black;">Matrícula</th>
                                <th style="border: 2px solid black;">Nome do preso</th>
                                <th style="border: 2px solid black;">R/C</th>
                                <th style="border: 2px solid black;">Nome Medicamento</th>
                                <th style="border: 2px solid black;">Qtd</th>
                                <th style="border: 2px solid black;">Data Entregar</th>
                                <th style="border: 2px solid black;">Período</th>
                            </tr>
                        </thead>
                        <tbody><?php
                            foreach($resultado as $medicent){
                                $matricula = midMatricula($medicent['MATRICULA'],3);
                                $nome = $medicent['NOME'];
                                $raiocela = $medicent['RAIO']."/".$medicent['CELA'];
                                $nomemedic = $medicent['NOMEMEDICAMENTO'];
                                $qtd = $medicent['QTDENTREGA'];
                                $dataentregue = retornaDadosDataHora($medicent['DATAENTREGUE'],2);
                                $periodo = $medicent['PERIODO'];
                                $inicio = $medicent['DATAINICIO'];
                                $termino = $medicent['DATATERMINO'];

                                ?>
                                <tr>
                                    <td class="nowrap"><?=$matricula?></td>
                                    <td class="align-lef"><?=$nome?></td>
                                    <td class="align-cen"><?=$raiocela?></td>
                                    <td class="align-lef"><?=$nomemedic?></td>
                                    <td class="align-cen"><?=$qtd?></td>
                                    <td class="align-cen nowrap"><?=$dataentregue?></td>
                                    <td class="align-cen"><?=$periodo?></td>
                                </tr>
                                <?php
                            }

                        ?></tbody>
                    </table>

                </div>
                <!-- <p class="align-rig" style="padding: 100px 0px;"><?php //$GLOBALS['Cidade_unidade'].', '. $medicent['DATAFINALPAGINA']?></p> -->
                <!-- <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Necessitatibus eligendi ipsa in amet voluptas ipsum ad, odio exercitationem maxime temporibus rem voluptate vero illo officia? Sed mollitia optio similique accusamus.</p> -->
            <?php               
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
