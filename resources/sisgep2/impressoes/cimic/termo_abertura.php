<?php

//Verifica se o usuário tem a permissão necessária
$permissoesNecessarias = array(21);
$redirecionamento = "../acesso_negado.php";
$blnPermitido = false;

$blnPermitido = verificaPermissao($permissoesNecessarias,$redirecionamento);
//$nomearquivo = "TermoAbertura-".date('YmdHis').".pdf";

    /*$retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Este documento precisa ser revisado. Motivo: Tabela cadastros_movimentacoes excluída! </li>");
    echo json_encode($retorno);
    exit;
*/

    $idpreso = isset($_GET['idpreso'])?$_GET['idpreso']:0;
    $retorno = [];

    if(empty($idpreso)){
        echo 'Nenhum ID de Movimentação foi informado';
        exit();
    }

    $idinclusao = explode(',', $idpreso) ;
    if(count($idinclusao)>1){
        $nomearquivo = "TermoAbertura-".count($idinclusao)."Presos-".date('YmdHis').".pdf";
    }else{
        $nomearquivo = '';
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        foreach($idinclusao as $id){
            
            $sql = "SELECT E.DATAENTRADA, date_format(E.DATAENTRADA,'%Y-%m-%d') DATAMOV, GSA.NOME ORIGEM, EP.MATRICULA, EP.NOME, EP.RG, date_format(E.DATAENTRADA,'%d de %M de %Y') DATAFINALPAGINA
            FROM entradas_presos EP
            INNER JOIN cadastros CD ON CD.MATRICULA = EP.MATRICULA
            INNER JOIN entradas E ON E.ID = EP.IDENTRADA
            INNER JOIN codigo_gsa GSA ON GSA.ID = E.IDORIGEM
            WHERE MD5(EP.ID) = :idpreso;";
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('idpreso',$id,PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            
            if(count($resultado)){
                $nome = $resultado[0]['NOME'];
                $matricula=$resultado[0]['MATRICULA']!=null?midMatricula($resultado[0]['MATRICULA'],3):"N/C";
                $origem = $resultado[0]['ORIGEM'];
                $dataextensa = $resultado[0]['DATAFINALPAGINA']!=NULL?$resultado[0]['DATAFINALPAGINA']:'MIGRAÇÃO DE SISTEMA';

                $data = $resultado[0]['DATAMOV']!='0000-00-00'?$resultado[0]['DATAENTRADA']:'2022-01-01';

                if(empty($nomearquivo)){
                    $nomearquivo = "TermoAbertura-$nome-".date('YmdHis').".pdf";
                }

                $resultadodiretor = buscaDadosDiretor(9,$data);
                foreach($resultadodiretor as $dadosdiretor){

                    // $nomediretor = strtoupper($dadosdiretor['NOME']);
                    $nomediretor = $dadosdiretor['NOME'];
                    $subtituto = $dadosdiretor['SUBSTITUTO']==1?' - Subst.':'';
                    $cargodiretor = $dadosdiretor['CARGO'] . $subtituto; ?>

                    <table>
                        <h1 class="titulo">PRONTUÁRIO PROCESSUAL</h1>
                        <h1 class="titulo">TERMO DE ABERTURA</h1>
        
                        <p class='indent' style='font-size: 25px;'>De conformidade com a Portaria COESPE nº 190/01 DECLARO ABERTO, o prontuário do sentenciado <b><?=$nome?></b>, matrícula <b><?=$matricula?></b>, por motivo de inclusão neste Estabelecimento Penal, procedente do(a) <b><?=$origem?></b>.</p><br>
        
                        <p style="text-align: right; padding: 100px 0px;"><?=$Cidade_unidade?>, <?=$dataextensa?></p>
                        <div style="line-height: 0.5em;">
                            <p class="align-cen"><b><?=$nomediretor?></b></p>
                            <p class="align-cen"><?=$cargodiretor?></p>
                        </div>
                        
                    </table> <?php
                }
            }
        }

    }else{
        echo $conexaoStatus;
        exit();
    }
