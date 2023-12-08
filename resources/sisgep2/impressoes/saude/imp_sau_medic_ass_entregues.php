<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    // $permissoesNecessarias = array(7);//array(3,4,5,6,7);
    // $redirecionamento = "../acesso_negado.php";
    // $blnPermitido = false;

    // $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "MedicamentosAssistidosEntregues-".date('YmdHis').".pdf";

    // echo var_dump($_GET);

    // $strdatainicio = $_GET['strdatainicio'];
    // $strdatafinal = $_GET['strdatafinal'];
    $datainicio = $_GET['datainicio'];
    $datafinal = $_GET['datafinal'];
    $idspreso = isset($_GET['idspreso'])?$_GET['idspreso']:0;
    $idsperiodo = isset($_GET['idsperiodo'])?$_GET['idsperiodo']:0;

    $where = '';
    $explodepreso = explode(",",$idspreso);
    $explodeperiodo = explode(",",$idsperiodo);

    $params = [$datainicio,$datafinal];
    
    // if(count($explodeperiodo)==count($explodepreso) && count($explodepreso)>0 && ($idspreso!=0 || $idsperiodo!=0)){
        $where = 'AND (';
        for($i=0;$i<count($explodepreso);$i++){
            if($where!='AND ('){
                $where .= " OR ";
            }
            $where .= "(md5(EMA.IDPRESO) = ?";
            array_push($params,$explodepreso[$i]);
            $where .= " AND md5(EMAE.IDPERIODOENTREGUE) = ?)";
            array_push($params,$explodeperiodo[$i]);
        }
        $where .= ')';
        // echo "<p>$where</p>";
    // }
    
    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $sql = "SELECT EMAE.ID, EMA.ID IDASS, EMA.IDMEDICAMENTO, MED.NOME NOMEMEDICAMENTO, EMAE.QTDENTREGUE, FORN.SIGLA UNIDADEFORN, EMA.IDPRESO, CAD.MATRICULA, CAD.NOME, EMAE.DATAENTREGUE, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, EMAE.DATAENTREGUE, 1) IDRAIO, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, EMAE.DATAENTREGUE, 2) RAIO, FUNCT_dados_raio_cela_preso(EMA.IDPRESO, EMAE.DATAENTREGUE, 3) CELA, EMAE.IDPERIODOENTREGUE IDPERIODO, PER.NOME PERIODO,
        date_format(EMA.DATAINICIO,'%Y-%m-%d') DATAINICIO, date_format(EMA.DATATERMINO,'%Y-%m-%d') DATATERMINO, NULL COR, EMAE.IDCADASTRO, US.USUARIO, EMAE.DATACADASTRO, 
            (SELECT COUNT(EMAE1.ID) FROM enf_medic_assistido_entregue EMAE1
            INNER JOIN enf_medic_assistido EMA1 ON EMA1.ID = EMAE1.IDASS
            WHERE EMAE1.IDPERIODOENTREGUE = EMAE.IDPERIODOENTREGUE AND EMA1.IDPRESO = EMA.IDPRESO AND date_format(EMAE1.DATAENTREGUE,'%Y-%m-%d') = date_format(EMAE.DATAENTREGUE,'%Y-%m-%d') AND EMAE1.IDEXCLUSOREGISTRO IS NULL) QTDMED,
        date_format(CURRENT_DATE,'%d de %M de %Y') DATAFINALPAGINA
        FROM enf_medic_assistido_entregue EMAE
        INNER JOIN enf_medic_assistido EMA ON EMA.ID = EMAE.IDASS
        INNER JOIN enf_medicamentos MED ON MED.ID = EMA.IDMEDICAMENTO
        INNER JOIN tab_unidadesfornecimento FORN ON FORN.ID = MED.IDUNIDADE
        INNER JOIN entradas_presos EP ON EP.ID = EMA.IDPRESO
        INNER JOIN cadastros CAD ON CAD.MATRICULA = EP.MATRICULA
        INNER JOIN tab_periodos PER ON PER.ID = EMAE.IDPERIODOENTREGUE
        INNER JOIN tab_usuarios US ON US.ID = EMAE.IDCADASTRO
        WHERE date_format(EMAE.DATAENTREGUE,'%Y-%m-%d') >= ? AND date_format(EMAE.DATAENTREGUE,'%Y-%m-%d') <= ? AND EMAE.IDEXCLUSOREGISTRO IS NULL $where
        
        ORDER BY DATAENTREGUE DESC, NOME, IDPERIODO, RAIO, CELA;";

        // echo "<p>$sql</p>";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);

        $resultado = $stmt->fetchAll();
        // echo "<p>".count($resultado)."</p>";
        
        if(count($resultado)){
            $datainicioconv = retornaDadosDataHora($datainicio,2);
            $dataterminoconv = retornaDadosDataHora($datafinal,2);?>

                <h1 class="titulo" style="padding-bottom: 0px;">Relação Medicamentos Entregues</h1>
                
                <p class="align-rig" style="padding-top: 0; font-size: 10pt;">Consulta de <?=$datainicioconv?> a <?=$dataterminoconv?></p>

                <div style="align-items: center;">
                    <table style="width: 100%; text-align: center; font-size: 11pt;">
                        <thead>
                            <tr style="background-color: lightgray;">
                                <th style="border: 2px solid black;">Matrícula</th>
                                <th style="border: 2px solid black;">Nome do preso</th>
                                <th style="border: 2px solid black;">R/C</th>
                                <th style="border: 2px solid black;">Nome Medicamento</th>
                                <th style="border: 2px solid black;">Qtd</th>
                                <th style="border: 2px solid black;">Data Entregue</th>
                                <th style="border: 2px solid black;">Período</th>
                                <th style="border: 2px solid black;">Usuário</th>
                                <th style="border: 2px solid black;">Lançamento</th>
                            </tr>
                        </thead>
                        <tbody><?php
                            foreach($resultado as $medicent){
                                $matricula = midMatricula($medicent['MATRICULA'],3);
                                $nome = $medicent['NOME'];
                                $raiocela = $medicent['RAIO']."/".$medicent['CELA'];
                                $nomemedic = $medicent['NOMEMEDICAMENTO'];
                                $qtd = $medicent['QTDENTREGUE'];
                                $dataentregue = retornaDadosDataHora($medicent['DATAENTREGUE'],2);
                                $periodo = $medicent['PERIODO'];
                                $inicio = $medicent['DATAINICIO'];
                                $termino = $medicent['DATATERMINO'];
                                $usuario = $medicent['USUARIO'];
                                $datacadastro = retornaDadosDataHora($medicent['DATACADASTRO'],12);

                                ?>
                                <tr>
                                    <td class="nowrap"><?=$matricula?></td>
                                    <td class="align-lef"><?=$nome?></td>
                                    <td class="align-cen"><?=$raiocela?></td>
                                    <td class="align-lef"><?=$nomemedic?></td>
                                    <td class="align-cen"><?=$qtd?></td>
                                    <td class="align-cen nowrap"><?=$dataentregue?></td>
                                    <td class="align-cen"><?=$periodo?></td>
                                    <td class="align-cen"><?=$usuario?></td>
                                    <td class="align-cen"><?=$datacadastro?></td>
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
