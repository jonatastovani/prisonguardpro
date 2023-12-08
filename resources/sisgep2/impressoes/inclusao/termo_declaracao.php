<?php

//Verifica se o usuário tem a permissão necessária
$permissoesNecessarias = array(9,23);
$redirecionamento = "../acesso_negado.php";
$blnPermitido = false;

$blnPermitido = verificaPermissao($permissoesNecessarias);
//$nomearquivo = "TermoAbertura-".date('YmdHis').".pdf";

    $idpreso = isset($_GET['idpreso'])?$_GET['idpreso']:0;
    $retorno = [];

    if(empty($idpreso)){
        echo 'Nenhum ID de Preso foi informado';
        exit();
    }

    $idinclusao = explode(',', $idpreso) ;
    // var_dump($idinclusao);

    if(count($idinclusao)>1){
        $nomearquivo = "TermoDeclaração-".count($idinclusao)."Presos-".date('YmdHis').".pdf";
    }else{
        $nomearquivo = '';
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        foreach($idinclusao as $movimentacao){
            $sql = "SELECT EP.MATRICULA, CASE WHEN CD.NOME IS NOT NULL THEN CD.NOME ELSE EP.NOME END NOME, CASE WHEN CD.PAI IS NOT NULL THEN CD.PAI ELSE EP.PAI END PAI, CASE WHEN CD.MAE IS NOT NULL THEN CD.MAE ELSE EP.MAE END MAE, CD.DATANASC, E.DATAENTRADA, EST.SIGLA UF, CID.NOME CIDADENASC, NAC.ID IDNACIONALIDADE, NAC.NOME NACIONALIDADE, GSA.NOME ORIGEM, date_format(E.DATAENTRADA,'%W, %d de %M de %Y') DATAFINALPAGINA
            FROM entradas_presos EP
            INNER JOIN entradas E ON E.ID = EP.IDENTRADA
            INNER JOIN codigo_gsa GSA ON GSA.ID = E.IDORIGEM
            LEFT JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            LEFT JOIN tab_cidades CID ON CID.ID = CD.IDCIDADENASC
            LEFT JOIN tab_estados EST ON EST.ID = CID.IDUF
            LEFT JOIN tab_nacionalidade NAC ON NAC.ID = CD.NACIONALIDADE
            WHERE MD5(EP.ID) = :idpreso;";
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('idpreso',$movimentacao,PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetchAll();

            if(count($resultado)){
                if(empty($nomearquivo)){
                    $nomearquivo = "TermoDeclaração-".$resultado[0]['NOME']."-".date('YmdHis').".pdf";
                } ?>
                <table>
                    <h1 class="titulo">TERMO DE DECLARAÇÃO</h1> <?php
                    $nome = $resultado[0]['NOME'];
                    $matricula = midMatricula($resultado[0]['MATRICULA'],3);
                    $pai = isset($resultado[0]['PAI'])?$resultado[0]['PAI']:'';
                    $mae = isset($resultado[0]['MAE'])?$resultado[0]['MAE']:'';

                    $filiacao = '';
                    if($mae!=''){
                        $filiacao = "<b>".$mae."</b>";
                    }
                    if($pai!=''){
                        if($filiacao!=''){
                            $filiacao .= ' e ';
                        }
                        $filiacao .= "<b>".$pai."</b>";
                    }
                    if($filiacao==''){
                        $filiacao = "<b> N/C </b>";
                    }

                    $dataincl = retornaDadosDataHora($resultado[0]['DATAENTRADA'],2);
                    $datanasc = isset($resultado[0]['DATANASC'])?retornaDadosDataHora($resultado[0]['DATANASC'],2):"N/C";
                    $origem = $resultado[0]['ORIGEM'];
                    $idnacionalidade = $resultado[0]['IDNACIONALIDADE'];
                    $nacionalidade = $resultado[0]['NACIONALIDADE'];
                    $ufnasc = $resultado[0]['UF'];
                    $cidadenasc = $resultado[0]['CIDADENASC'];
                    
                    if($idnacionalidade==1){
                        $naturalidade = $cidadenasc.'-'.$ufnasc;
                    }elseif($idnacionalidade==2){
                        $naturalidade = "Estrangeiro(a)";
                    }else{
                        $naturalidade = "N/C";
                    }
                    ?>
                    
                    <div style="display: flex; padding: 10px 0px;">
                        <div style="display: inline-block;">
                            Matrícula: <b><?=$matricula?></b>
                        </div>
                        <div style="display: inline-block; margin-left: 15px;">
                            Nome: <b><?=$nome?></b>
                        </div>

                    </div>
                    
                    <div style="padding: 10px 0px;">
                        <span>Filiação: <?=$filiacao?></span>
                    </div>

                    <div style="display: flex; padding: 10px 0px;">
                        <div style="display: inline-block;">
                            Incluído em: <b><?=$dataincl?></b>
                        </div>
                        <div style="display: inline-block; margin-left: 15px;">
                            Data de Nascimento: <b><?=$datanasc?></b>
                        </div>
                    </div>

                    <div style="display: flex; padding: 10px 0px;">
                        <div style="display: inline-block;">
                            Procedência: <b><?=$origem?></b>
                        </div>
                        <div style="display: inline-block; margin-left: 15px;">
                            Naturalidade: <b><?=$naturalidade?></b>
                        </div>
                    </div>

                    <div style="padding-top: 40px;">
                        <p class="indent align-jus">Declaro para os devidos fins, que se fizerem necessários que estou sendo incluído nesta Unidade Prisional e que não sofri nenhum tipo de agressão física ou moral e estou ciente que tenho o prazo de 20 (vinte) dias a contar desta data, bem como meus familiares para RETIRAR os pertences que ficaram na INCLUSÃO, sendo que após este prazo fica AUTORIZADA a doação dos mesmos.</p>
                    </div>
                    
                    <div class="align-cen" style="padding: 100px 0px;">
                        <div style="width: 75%; padding-left: 12,5% ;">
                            <hr>
                        </div>
                        <?=$nome?> - Matrícula: <?=$matricula?>
                    </div>
                </table> <?php
            } 
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
