<?php

    //Verifica se o usuário tem a permissão de imprimir recibo de presos;
    // $permissoesNecessarias = array(7);//array(3,4,5,6,7);
    // $redirecionamento = "../acesso_negado.php";
    // $blnPermitido = false;

    // $blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
    $nomearquivo = "Contagem-".date('YmdHis').".pdf";
    
    // echo var_dump($_GET);

    $boletimvigente = isset($_GET['boletimvigente'])?$_GET['boletimvigente']:0;
    $idboletim = isset($_GET['idboletim'])?$_GET['idboletim']:0;
    $idtipocontagem = $_GET['idtipocontagem'];

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

            $whereboletim = "CC.IDBOLETIM = @intIDBoletim";
        }else{
            $whereboletim = "md5(CC.IDBOLETIM) = ?";
            $params=[$idboletim];
        }

        array_push($params,$idtipocontagem);

        $sql = "SELECT CC.ID IDCONTAGEM, CC.IDTIPO, CC.IDUSUARIO, US.NOME NOMEUSUARIO, CC.AUTENTICADO, CC.IDRAIO, RC.NOMECOMPLETO NOMERAIO, CC.QTD, CCT.NOME NOMECONTAGEM, TU.NOME NOMETURNO, BOL.DATABOLETIM, BOL.NUMERO, date_format(BOL.DATABOLETIM,'%d de %M de %Y') DATAEXTENSA, BOL.IDDIRETOR, CC.DATACADASTRO
        FROM chefia_contagens CC
        INNER JOIN tab_raioscelas RC ON RC.ID = CC.IDRAIO
        INNER JOIN chefia_contagenstipos CCT ON CCT.ID = CC.IDTIPO
        LEFT JOIN tab_usuarios US ON US.ID = CC.IDUSUARIO
        INNER JOIN chefia_boletim BOL ON BOL.ID = CC.IDBOLETIM
        INNER JOIN tab_turnos TU ON TU.ID = BOL.IDTURNO
        WHERE $whereboletim AND md5(CC.IDTIPO) = ? AND CC.IDEXCLUSOREGISTRO IS NULL;";

        // echo "<p>$sql</p>";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);

        $resultado = $stmt->fetchAll();
        if(count($resultado)==0){
            echo "<h1>A contagem solicitada não foi encontrada. Se o problema persistir contate o programador.</h1>";
            exit();
        }
        // echo "<p>".count($resultado)."</p>";
        
        //Busca dados dos presos em apresentação fora da unidade (Fórum, Hospital ou tudo que precise de um funcionário da unidade)
        $params=[$resultado[0]['DATACADASTRO'],$resultado[0]['DATACADASTRO']];

        $sql = "SELECT * FROM cimic_apresentacoes
        WHERE DATAHORASAIDA <= ?
        AND (DATAHORARETORNO IS NULL OR DATAHORARETORNO >= ?) AND IDEXCLUSOREGISTRO IS NULL;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);
        $resultadoapres = $stmt->fetchAll();

        $qtdapresentacao = count($resultadoapres);
        
        //Busca dados do diretor para a assinatura
        $params=[$resultado[0]['IDDIRETOR']];

        $sql = "SELECT PERM.NOME NOMEPERMISSAO, PERM.NOMECOMPLETO NOMECOMPLETOPERMISSAO, US.NOME NOMEDIRETOR, USPERM.SUBSTITUTO
        FROM tab_usuariospermissoes USPERM
        INNER JOIN tab_usuarios US ON US.ID = USPERM.IDUSUARIO
        INNER JOIN tab_permissoes PERM ON PERM.ID = USPERM.IDPERMISSAO
        WHERE USPERM.ID = ?;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute($params);
        $resultadodiretor = $stmt->fetchAll();

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
            $blnemaberto = false; //Variável que se constar verdadeiro não irá gerar o compo de assinatura do diretor, pois há dados faltando
            $nomecontagem = $resultado[0]['NOMECONTAGEM'];
            $nometurno = $resultado[0]['NOMETURNO'];
            $dataextensa = $resultado[0]['DATAEXTENSA'];
            $numeracao = ArrumaNumeroBoletim($resultado[0]['NUMERO'],retornaDadosDataHora($resultado[0]['DATABOLETIM'],5));
            $somatotal = $qtdapresentacao;
            // echo "<p>$numeracao</p>";

            ?>

            <h1 class="titulo" style="padding-bottom: 0px;"><?=$nomecontagem?></h1>

            <p class="align-rig"><?=$nometurno?> - <?=$dataextensa?> - Boletim <?=$numeracao?></p> <?php
                
            for($i=0;$i<count($resultado);$i++){?>

                <table style="width: 100vw; text-align: center; font-size: 12pt; margin-bottom: 15px; border-spacing:0px 15px;">
                    <thead>
                        <tr>
                            <th class="align-lef" style="border: none;">Raio/Cela</th>
                            <th style="border: none;">Funcionário</th>
                            <th style="border: none;">Assinatura</th>
                            <th class="align-rig" style="border: none;">Qtd</th>
                        </tr>
                    </thead>
                    <tbody><?php
                        for($i=$i;$i<count($resultado);$i++){
                            $nomeraio=$resultado[$i]['NOMERAIO'];
                            $funcionario=$resultado[$i]['NOMEUSUARIO'];

                            if($funcionario==''){
                                $funcionario='*****';
                            }
                            $qtd=$resultado[$i]['QTD'];
                            $somatotal += $qtd;
                            
                            if($qtd>0 && $funcionario=='*****'){
                                $funcionario='<span class="destaque-atencao">NÃO CONFIRMADO CONTAGEM</span>';
                                $blnemaberto = true;
                            }?>

                            <tr>
                                <td class="align-lef" style="border: none; border-bottom: 1px dashed black; width: 20%;"><?=$nomeraio?></td>
                                <td class="align-cen" style="border: none; border-bottom: 1px dashed black; width: 35%;"><?=$funcionario?></td>
                                <td style="border: none; border-bottom: 1px dashed black;"></td>
                                <td class="align-rig" style="border: none; border-bottom: 1px dashed black; width: 6%;"><?=$qtd?></td>
                            </tr><?php
                        }?>
                    </tbody>
                </table>

                <div class="align-rig">
                    <p style="display: inline-flex; font-size: 14;">Presos em apresentação: <?=$qtdapresentacao?></p><br>
                    <h1 style="display: inline-flex; font-size: 18;">Total Geral: <?=$somatotal?></h1>
                </div> <?php
                
                if($blnemaberto){ ?>
                    <p>****** Há um ou mais locais que a contagem não foi realizada. Não será gerado o campo de assinatura do diretor responsavel. ******</p>
                    <?php
                }else{ ?>
                    <div class="assinatura" style="position: absolute; bottom: 10px; width: 100%;">
                        <p class="align-cen padding-margin-0"><?=$nomediretor?></p>
                        <p class="align-cen padding-margin-0"><?=$nomepermissao?></p>
                    </div> <?php
                }
            }
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
