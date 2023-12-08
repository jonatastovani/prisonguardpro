<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    // $permissoesNecessarias = array(7);//array(3,4,5,6,7);
    // $redirecionamento = "../acesso_negado.php";
    // $blnPermitido = false;

    // $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "Procedimentos-".date('YmdHis').".pdf";
    
    // echo var_dump($_GET);

    $boletimvigente = isset($_GET['boletimvigente'])?$_GET['boletimvigente']:0;
    $idboletim = isset($_GET['idboletim'])?$_GET['idboletim']:0;
    $idtipoproced = $_GET['idtipoproced'];

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

        $whereboletim = "";
        $params=[];

        //Se for o boletim vigente então é feito a busca do IDBOLETIM atual
        if($boletimvigente==md5(1)){
            $sql=retornaQueryDadosBoletimVigente();
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->execute();

            $whereboletim = "CPBPG.IDBOLETIM = @intIDBoletim";
        }else{
            $whereboletim = "md5(CPBPG.IDBOLETIM) = ?";
            $params=[$idboletim];
        }

        array_push($params,$idtipoproced);

        $sql = "SELECT DISTINCT CPBPG.IDUSUARIO, US.NOME NOMEUSUARIO, CPBPG.IDRAIO, RC.NOME NOMERAIO, RC.NOMECOMPLETO NOMERAIOCOMPLETO, (SELECT group_concat(CPBPG2.CELA SEPARATOR ', ') FROM chefia_proced_bate_piso_grade CPBPG2 WHERE CPBPG2.IDUSUARIO = CPBPG.IDUSUARIO AND CPBPG2.IDEXCLUSOREGISTRO IS NULL AND CPBPG2.IDBOLETIM = CPBPG.IDBOLETIM AND CPBPG2.IDRAIO = CPBPG.IDRAIO) CELA, CPT.NOME NOMEPROCED, TU.NOME NOMETURNO, BOL.DATABOLETIM, BOL.NUMERO, date_format(BOL.DATABOLETIM,'%d de %M de %Y') DATAEXTENSA, BOL.IDDIRETOR, (SELECT COUNT(DISTINCT CPBPG2.IDRAIO) FROM chefia_proced_bate_piso_grade CPBPG2 WHERE CPBPG2.IDUSUARIO = CPBPG.IDUSUARIO AND CPBPG2.IDBOLETIM = CPBPG.IDBOLETIM AND CPBPG2.IDEXCLUSOREGISTRO IS NULL) LINHASNOME
        FROM chefia_proced_bate_piso_grade CPBPG
        INNER JOIN tab_raioscelas RC ON RC.ID = CPBPG.IDRAIO
        INNER JOIN tab_usuarios US ON US.ID = CPBPG.IDUSUARIO
        INNER JOIN chefia_proced_tipos CPT ON CPT.ID = CPBPG.IDPROCED
        INNER JOIN chefia_boletim BOL ON BOL.ID = CPBPG.IDBOLETIM
        INNER JOIN tab_turnos TU ON TU.ID = BOL.IDTURNO
        WHERE $whereboletim AND md5(CPBPG.IDPROCED) = ? AND CPBPG.IDEXCLUSOREGISTRO IS NULL
        ORDER BY NOMEUSUARIO,IDUSUARIO,NOMERAIO;";

        // echo "<p>$sql</p>";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);

        $resultado = $stmt->fetchAll();
        if(count($resultado)==0){
            echo "<h1>O procedimento solicitado não foi encontrado. Se o problema persistir contate o programador.</h1>";
            exit();
        }
        // echo "<p>".count($resultado)."</p>";
        
        //Busca dados do diretor para a assinatura
        $resultadodiretor = buscaDadosIDPermissao($resultado[0]['IDDIRETOR']);

        if(count($resultadodiretor)==0){
            echo "<h1>Diretor não encontrado. Não será possível gerar o documento sem essa informação.</h1>";
            exit();
        }

        $nomepermissao = $resultadodiretor[0]['NOMECOMPLETOPERMISSAO'];
        $nomediretor = $resultadodiretor[0]['NOMEDIRETOR'];
        $substituto = $resultadodiretor[0]['SUBSTITUTO'];
        if($substituto==1){
            $nomepermissao .= " - Subst.";
        }
        
        if(count($resultado)){
            $nomeproced = $resultado[0]['NOMEPROCED'];
            $nomearquivo = "Procedimento-". removeTodoEspaco($nomeproced)."-".date('YmdHis').".pdf";
            $nometurno = $resultado[0]['NOMETURNO'];
            $dataextensa = $resultado[0]['DATAEXTENSA'];
            $numeracao = ArrumaNumeroBoletim($resultado[0]['NUMERO'],retornaDadosDataHora($resultado[0]['DATABOLETIM'],5));
            // echo "<p>$nomeproced</p>";

            ?>

            <h1 class="titulo" style="padding-bottom: 0px;"><?=$nomeproced?></h1>

            <p class="align-rig"><?=$nometurno?> - <?=$dataextensa?> - Boletim <?=$numeracao?></p>
            
            <p style="text-indent: 2em; font-weight: bolder;">Senhor Diretor de Segurança e Disciplina;</p>
            <p style="text-align: justify;">Informo que foi realizado procedimento de "<b><?=$nomeproced?></b>" conforme discriminado na relação abaixo:</p> 
            
            <table style="width: 100vw; text-align: center; font-size: 12pt; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="width: 4%;"></th>
                        <th class="align-cen" style="width: 55%;">Funcionário</th>
                        <th class="align-cen" style="width: 8%;">Raio</th>
                        <th class="align-cen" style="width: 8%;">Celas</th>
                        <th class="align-cen" style="width: 25%;">Assinatura</th>
                    </tr>
                </thead>
            </table> <?php
                    
            // $resultado = array_merge($resultado,$resultado,$resultado,$resultado,$resultado,$resultado,$resultado);

            $contadorfunc = 0;
            $contadornome = -1;

            for($i=0;$i<count($resultado);$i++){
                $raio=$resultado[$i]['NOMERAIO'];
                $cela=$resultado[$i]['CELA'];

                if($i==0 || $i>$contadornome){ 
                    $contadorfunc++;
                    $funcionario=$resultado[$i]['NOMEUSUARIO'];
                    $linhasnome=$resultado[$i]['LINHASNOME']; ?>
                    
                    <table style="width: 100vw; border-collapse: collapse;"><table style="width: 100vw; text-align: center; font-size: 12pt; border-collapse: collapse;"><tr>

                    <td style="width: 4%;" rowspan="<?=$linhasnome?>"><?=$contadorfunc?></td>
                    <td style="width: 55%;" rowspan="<?=$linhasnome?>"><?=$funcionario?></td> <?php
                }else{ ?>
                    <tr> <?php
                } ?>
                
                <td style="width: 8%;"><?=$raio?></td>
                <td style="width: 8%; word-wrap: break-word;"><?=$cela?></td> <?php
                
                if($i==0 || $i>$contadornome){ ?>
                    <td rowspan="<?=$linhasnome?>" style="width: 25%;"></td> <?php

                    $contadornome += $linhasnome;

                }
                if($i==$contadornome){ ?>
                    </tr> </table> </table> <?php

                }else{ ?>

                    </tr> <?php
                }
            } ?>
            
            <section class="assinatura-final">
                <p class="align-cen padding-margin-0"><?=$nomediretor?></p>
                <p class="align-cen padding-margin-0"><?=$nomepermissao?></p>
            </section>


            <?php
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
