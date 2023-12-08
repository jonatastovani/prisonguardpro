<?php
    session_start();
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    $tipo = $_POST['tipo'];
    $idlocal = isset($_POST['idlocal'])?$_POST['idlocal']:0;
    $iddestino = isset($_POST['iddestino'])?$_POST['iddestino']:0;
    $idtipo = isset($_POST['idtipo'])?$_POST['idtipo']:0;
    $idmotivo = isset($_POST['idmotivo'])?$_POST['idmotivo']:0;
    $iduf = isset($_POST['iduf'])?$_POST['iduf']:0;
    $idcidade = isset($_POST['idcidade'])?$_POST['idcidade']:0;
    $idartigo = isset($_POST['idartigo'])?$_POST['idartigo']:0;
    $identrada = isset($_POST['identrada'])?$_POST['identrada']:0;
    $idorigem = isset($_POST['idorigem'])?$_POST['idorigem']:0;
    $idprofissao = isset($_POST['idprofissao'])?$_POST['idprofissao']:0;
    $idraio = isset($_POST['idraio'])?$_POST['idraio']:0;
    $cela = isset($_POST['cela'])?$_POST['cela']:0;
    $idgrupo = isset($_POST['idgrupo'])?$_POST['idgrupo']:0;
    $idturno = isset($_POST['idturno'])?$_POST['idturno']:0;
    $idmodelo = isset($_POST['idmodelo'])?$_POST['idmodelo']:0;
    $idbuscar = isset($_POST['idbuscar'])?$_POST['idbuscar']:0;
    $idmedic = isset($_POST['idmedic'])?$_POST['idmedic']:0;
    $idgrau = isset($_POST['idgrau'])?$_POST['idgrau']:0;
    $tabela = isset($_POST['tabela'])?$_POST['tabela']:0;
    $idsituacao = isset($_POST['idsituacao'])?$_POST['idsituacao']:0;
    $dataconsulta = isset($_POST['dataconsulta'])?$_POST['dataconsulta']:'';
    $arridtipos = isset($_POST['arridtipos'])?$_POST['arridtipos']:0;
    //verificaativo == 1: insere texto no where para selecionar somente o registro que não foi excluído
    $verificaativo = isset($_POST['verificaativo'])?$_POST['verificaativo']:0;
    $verificapermissao = isset($_POST['verificapermissao'])?$_POST['verificapermissao']:0;

    $idusuario = $_SESSION['id_usuario'];
    $ipcomputador = UserInfo::get_ip();

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            //Tipo 1 = Busca origens do códigos GSA
            if($tipo==1){

                $sql = "SELECT ID VALOR, concat(CASE WHEN CODIGO IS NULL THEN '' ELSE concat(CODIGO, CASE WHEN DIGITO IS NULL THEN '' ELSE concat('-',DIGITO,' ') END) END, NOME) NOMEEXIBIR FROM codigo_gsa WHERE TIPO = 1 ORDER BY NOME,CODIGO,DIGITO;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma origem ou destino foi encontrada. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 2 = Busca locais de apresentação
            elseif($tipo==2){

                $sql = "SELECT ID VALOR, NOMEABREVIADO NOMEEXIBIR FROM cimic_locaisapresentacoes WHERE IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL ORDER BY NOMEABREVIADO;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Local de Apresentação! </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 3 = buscar dados dos locais de apresentações
            elseif($tipo==3 && $idlocal!=0){
                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM cimic_locaisapresentacoes WHERE ID = :idlocal;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idlocal',$idlocal, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Local para o ID informado! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 4 = Busca destinos da tab_unidades
            elseif($tipo==4){

                $sql = "SELECT UN.ID VALOR, ucase(concat(UN.CODIGO, ' - ', UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE)) NOMEEXIBIR FROM tab_unidades UN
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE WHERE UN.ID <> (SELECT IDUNIDADE FROM tab_dadosunidade WHERE ID = 1) ORDER BY UN.NOMEUNIDADE;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Destino! </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 5 = buscar dados dos destinos da tab_unidades
            elseif($tipo==5 && $iddestino!=0){
                //Monta o Select
                $sql = "SELECT UN.ID VALOR, ucase(concat(UNT.ABREVIACAO, ' ', UN.NOMEUNIDADE)) NOMEEXIBIR FROM tab_unidades UN
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE WHERE UN.ID = :iddestino;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('iddestino',$iddestino, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Destino para o ID informado! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 6 = Busca tipos de movimentações
            elseif($tipo==6){
                //Lista dos tipos de movimentações selecionados
                $selecionados = isset($_POST['selecionados'])?$_POST['selecionados']:0;
                if($selecionados!=0){
                    $where = "WHERE ID IN ($selecionados)";
                }
                $sql = "SELECT ID VALOR, CONCAT(SIGLA, ' - ', NOME) NOMEEXIBIR FROM tab_movimentacoestipo $where ORDER BY NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Tipo de Movimentação! </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 7 = buscar dados dos tipos de movimentações
            elseif($tipo==7 && $idtipo!=0){
                //Monta o Select
                $sql = "SELECT ID VALOR, CONCAT(SIGLA, ' - ', NOME) NOMEEXIBIR FROM tab_movimentacoestipo WHERE ID = :idtipo;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idtipo',$idtipo, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Tipo para o ID informado! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 8 = Busca motivos de movimentações
            elseif($tipo==8 && $idtipo!=0){
                
                $sql = "SELECT MM.ID VALOR, CONCAT(MM.SIGLA, ' - ', MM.NOME) NOMEEXIBIR
                FROM tab_movimentacoesfiltro MF
                INNER JOIN tab_movimentacoesmotivos MM ON MM.ID = MF.IDMOTIVO
                WHERE MF.IDTIPO = :idtipo ORDER BY MM.NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idtipo',$idtipo,PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Motivo para o Tipo informado! </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 9 = buscar dados dos motivos de movimentações
            elseif($tipo==9 && $idmotivo!=0){
                //Monta o Select
                $sql = "SELECT ID VALOR, CONCAT(SIGLA, ' - ', NOME) NOMEEXIBIR
                FROM tab_movimentacoesmotivos WHERE ID = :idmotivo;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idmotivo',$idmotivo, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Motivo para o ID informado! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 10 = Busca motivos de apresentações
            elseif($tipo==10){
                
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM cimic_motivosapresentacoes ORDER BY NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Motivo! </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 11 = buscar dados dos motivos de apresentações
            elseif($tipo==11 && $idmotivo!=0){
                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR
                FROM cimic_motivosapresentacoes WHERE ID = :idmotivo;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idmotivo',$idmotivo, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Motivo para o ID informado! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 12 = buscar as coordenadorias existentes
            elseif($tipo==12){
                //Monta o Select
                $sql = "SELECT ID VALOR, concat(SIGLA, ' - ', NOME) NOMEEXIBIR FROM tab_unidadescoordenadorias";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma Coordenadoria! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 13 = buscar as tipos de unidades existentes
            elseif($tipo==13){
                //Monta o Select
                $sql = "SELECT ID VALOR, concat(ABREVIACAO, ' - ', NOME) NOMEEXIBIR FROM tab_unidadestipos";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhuma Tipo de Unidade! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 14 = buscar as perfis existentes
            elseif($tipo==14){
                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM tab_unidadesperfil";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Não foi encontrado nenhum Perfil de Unidade! </li>");
                    echo json_encode($retorno);
                    exit();
                }

            }
            //Tipo 15 = buscar as cidades existentes
            elseif($tipo==15 && $iduf!=0){

                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM tab_cidades WHERE IDUF = :iduf";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('iduf',$iduf, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    /*foreach ($resultado as $dados) {
                        array_push($retorno,array('VALOR' => $dados['VALOR'], 'NOMEEXIBIR' => $dados['NOMEEXIBIR']));
                    }*/
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma cidade foi encontrada para a UF informada </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 16 = buscar dados das cidades
            elseif($tipo==16 && $idcidade!=0){

                //Monta o Select
                $sql = "SELECT * FROM tab_cidades WHERE ID = :idcidade";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idcidade',$idcidade, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma cidade foi encontrada para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 17 = buscar os artigos existentes
            elseif($tipo==17){

                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM tab_artigos WHERE IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL ORDER BY NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum artigo foi encontrada. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 18 = buscar dados dos artigos
            elseif($tipo==18 && $idartigo!=0){

                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM tab_artigos WHERE ID = :idartigo";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idartigo',$idartigo, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum artigo foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 19 = buscar números de entradas existentes
            elseif($tipo==19){

                //Monta o Select
                $sql = "SELECT E.ID VALOR, concat(date_format(E.DATAENTRADA,'%d/%m/%Y %H:%i'), ' (', GSA.NOME,')') NOMEEXIBIR FROM entradas E 
                INNER JOIN codigo_gsa GSA ON E.IDORIGEM = GSA.ID
                WHERE E.ID > 0 AND E.IDEXCLUSOREGISTRO IS NULL AND E.DATAEXCLUSOREGISTRO IS NULL ORDER BY E.DATAENTRADA DESC;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma entrada de preso foi encontrada </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 20 = buscar dados da entradas
            elseif($tipo==20 && $identrada!=0){

                //Monta o Select
                $sql = "SELECT E.ID, E.DATAENTRADA, GSA.NOME ORIGEM, E.IDORIGEM, count(EP.ID) QTDPRESOS FROM entradas_presos EP
                INNER JOIN entradas E ON E.ID = EP.IDENTRADA 
                INNER JOIN codigo_gsa GSA ON E.IDORIGEM = GSA.ID
                WHERE E.ID = :identrada AND EP.IDEXCLUSOREGISTRO IS NULL AND EP.DATAEXCLUSOREGISTRO IS NULL";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('identrada',$identrada, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma entrada de preso foi encontrada para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 21 = buscar dados da origem GSA
            elseif($tipo==21 && $idorigem!=0){

                //Monta o Select
                $sql = "SELECT ID VALOR, concat(CODIGO,'-',DIGITO,' (',NOME,')') NOMEEXIBIR FROM codigo_gsa WHERE ID = :idorigem AND TIPO = 1;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idorigem',$idorigem, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma origem foi encontrada para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 22 = buscar as profissões existentes
            elseif($tipo==22){

                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM tab_profissoes ORDER BY NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma Profissão foi encontrada. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 23 = buscar dados das profissões
            elseif($tipo==23 && $idprofissao!=0){

                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM tab_profissoes WHERE ID = :idprofissao";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idprofissao',$idprofissao, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma Profissão foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 24 = buscar raios existentes
            elseif($tipo==24){

                //Monta o Select
                $sql = "SELECT RC.ID VALOR, RC.NOME NOMEEXIBIR, RC.* FROM tab_raioscelas RC WHERE RC.LOCALOCULTO = FALSE";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum Raio foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 25 = buscar dados do raio
            elseif($tipo==25 && $idraio!=0){

                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR, QTD FROM tab_raioscelas WHERE ID = :idraio AND LOCALOCULTO = FALSE";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idraio',$idraio, PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum Raio foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 26 = buscar tipos de visualização (gerenciar raios e chefia)
            elseif($tipo==26){
                $where='';
                $params = [];

                if($verificapermissao==1){

                    $resultadocomputador = buscaVisualizacoesComputador($ipcomputador,2);
                    $resultadousuario = buscaVisualizacoesUsuario($idusuario,2);

                    $permissoes=[];
                    if(count($resultadocomputador) && count($resultadousuario)){
                        $permissoes = array_merge($resultadocomputador,$resultadousuario);
                    }elseif(count($resultadocomputador)){
                        $permissoes=$resultadocomputador;
                    }elseif(count($resultadousuario)){
                        $permissoes=$resultadousuario;
                    }
                    // $permissoes = $resultadocomputador;
                    // echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." QTD ".count($permissoes)." </li>"));exit();
                    
                    // $sqlwhere = "";
                    foreach($permissoes as $perm){
                        if($where==''){
                            $where = '?';
                            // $sqlwhere = "$perm";
                        }else{
                            $where .= ', ?';
                            // $sqlwhere .= ", $perm";
                        }
                        array_push($params,$perm);
                    }
                }
                // echo $sqlwhere;
                
                if($where!=''){
                    $where = "WHERE ID IN ($where)";
                }

                //Monta o Select
                $sql = "SELECT DISTINCT ID VALOR, NOME NOMEEXIBIR FROM tab_raioscelas_visualizacao $where ORDER BY ID";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma Visualização foi encontrada. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 27 = buscar tipos de atendimento (gerenciar raios e chefia)
            elseif($tipo==27 && $idgrupo!=0){
                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM chefia_atendimentostipo WHERE IDGRUPO IN (:idgrupo) ORDER BY NOME";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idgrupo',$idgrupo,PDO::PARAM_STR);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum Tipo de Atendimento foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 28 = buscar situações conforme o tipo informado
            elseif($tipo==28 && $idtipo!=0){
                
                $where='';
                //Lista dos tipos de situações selecionados dentro do grupo caso for informado
                $selecionados = isset($_POST['selecionados'])?$_POST['selecionados']:0;
                if($selecionados!=0){
                    $where = " AND SIT.ID IN ($selecionados)";
                }

                $naoselecionados = isset($_POST['naoselecionados'])?$_POST['naoselecionados']:0;
                if($naoselecionados!=0){
                    $where .= " AND SIT.ID NOT IN ($naoselecionados)";
                }

                //Monta o Select
                $sql = "SELECT SIT.ID VALOR, SIT.NOME NOMEEXIBIR
                FROM tab_situacaofiltro SF
                INNER JOIN tab_situacao SIT ON SIT.ID = SF.IDSITUACAO
                INNER JOIN tab_situacaotipo ST ON ST.ID = SF.IDTIPO
                WHERE ST.ID = :idtipo $where ORDER BY SIT.ID;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idtipo',$idtipo,PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum Tipo de Atendimento foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 29 = buscar sugestões de preenchimento conforme o tipo informado
            elseif($tipo==29 && $idtipo!=0){
                
                switch ($idtipo) {
                    case 1:
                        $tabela = 'chefia_atendimentosnomes';
                        $colunas = 'NULL VALOR, NOME NOMEEXIBIR';
                        $order = 'ORDER BY NOME';
                        break;
                    
                    case 2:
                        $tabela = 'enf_atendimentosnomes';
                        $colunas = 'NULL VALOR, NOME NOMEEXIBIR';
                        $order = 'ORDER BY NOME';
                        break;
                    
                    default:
                        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum Tipo de Sugestão foi informado. </li>");
                        echo json_encode($retorno);
                        exit();
                        break;
                }

                //Monta o Select
                $sql = "SELECT $colunas FROM $tabela WHERE IDEXCLUSOREGISTRO IS NULL $order;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                // if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                // }
                // else{
                //     $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum Tipo de Atendimento foi encontrado. </li>");
                //     echo json_encode($retorno);
                //     exit();
                // }
            }
            //Tipo 30 = buscar turnos existentes
            elseif($tipo==30){
                
                $where="";
                $params=[];
                $selecionados = isset($_POST['selecionados'])?$_POST['selecionados']:0;
                
                if($selecionados!=0){
                    if(!is_array(($selecionados))){
                        $selecionados = explode($selecionados,',');
                    }
                    if(is_array(($selecionados))){
                        foreach($selecionados as $idturno){
                            if($where==""){
                                $where = "?";
                            }else{
                                $where .= ",?";
                            }
                            array_push($params,$idturno);
                        }
                        $where = "WHERE ID IN ($where)";
                    }
                }
                
                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM tab_turnos $where;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum Turno foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 31 = buscar escalas existentes
            elseif($tipo==31){
                
                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM funcionarios_escalatipo;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum tipo de escala foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 32 = buscar postos de trabalho existentes
            elseif($tipo==32 && $idturno!=0 && $idmodelo!=0){
                $where='';
                $comassinatura = isset($_POST['comassinatura'])?$_POST['comassinatura']:0;

                if($idmodelo==2){
                    $where = "AND FEPOS.IDTROCA = 0";
                }
                if($comassinatura==1){
                    $where .= " AND FEPOS.COMASSINATURA = 1";
                }

                //Monta o Select
                $sql = "SELECT FEPOS.ID VALOR, FEPOS.NOME NOMEEXIBIR, FEPOS.* FROM funcionarios_escalapostos FEPOS WHERE IDTURNO = :idturno $where ORDER BY FEPOS.ORDEM;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idturno',$idturno,PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum posto de serviço foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 33 = buscar tipos de procedimentos existentes
            elseif($tipo==33){

                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM chefia_proced_tipos;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum tipo de procedimento foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 34 = buscar diretores
            elseif($tipo==34){
                $where='';
                $params=[];
                
                if($idturno==0 && $dataconsulta==''){
                    $sql = retornaQueryDadosBoletimVigente();
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute();

                    $sql = "SELECT date_format(@dataBoletim,'%Y-%m-%d') DATABOLETIM, @intIDTurno IDTURNO;";
                    $stmt = $GLOBALS['conexao']->prepare($sql);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();

                    $dataconsulta = $resultado[0]['DATABOLETIM'];
                    $idturno = $resultado[0]['IDTURNO'];
                }

                array_push($params,$idturno);

                //Lista dos tipos de diretores a serem selecionados
                $selecionados = isset($_POST['selecionados'])?$_POST['selecionados']:0;

                $in='';
                for($isel=0;$isel<count($selecionados);$isel++){
                    array_push($params,$selecionados[$isel]);
                    $in.=", ?";
                }

                array_push($params,$dataconsulta);
                array_push($params,$dataconsulta);
                array_push($params,$dataconsulta);
                
                $sql = "SELECT TUS.ID VALOR, concat(US.NOME, ' | ',PERM.NOME, CASE WHEN TUS.SUBSTITUTO = 1 THEN ' - Subst.' ELSE '' END, CASE WHEN TUS.TEMPORARIO = 1 THEN concat(' (Temporário até ', date_format(TUS.DATATERMINO, '%d/%m/%Y'), ')') ELSE '' END) NOMEEXIBIR, TUS.ID IDDIRETOR, TUS.TEMPORARIO, TUS.IDUSUARIO, TUS.IDPERMISSAO, TUS.SUBSTITUTO
                FROM tab_usuariospermissoes TUS
                INNER JOIN tab_usuarios US ON US.ID = TUS.IDUSUARIO
                INNER JOIN tab_permissoes PERM ON PERM.ID = TUS.IDPERMISSAO
                WHERE PERM.ID IN ((SELECT IDDIRETORCARCERAGEM FROM tab_turnos WHERE ID = ?) $in)  
                AND ((TUS.DATAINICIO <= ? AND TUS.DATATERMINO IS NULL) 
                OR (TUS.DATATERMINO >= ? AND TUS.DATAINICIO <= ?)) AND TUS.IDEXCLUSOREGISTRO IS NULL ORDER BY TUS.TEMPORARIO DESC, TUS.SUBSTITUTO;";
    
                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum diretor foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Tipo 35 = buscar tipos de contagens
            elseif($tipo==35){

                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR, DESCRICAO FROM chefia_contagenstipos ORDER BY ID;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum tipo de contagem foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar idusuário logado
            elseif($tipo==36){

                echo json_encode($_SESSION['id_usuario']);
                
            }
            //Buscar grau de parentesco
            elseif($tipo==37 && $idtipo>0){

                //Monta o Select
                $sql = "SELECT GP.ID VALOR, GP.NOME NOMEEXIBIR FROM tab_grauparentesco_filtro GPF
                INNER JOIN tab_grauparentesco GP ON GP.ID = GPF.IDGRAU
                WHERE GPF.IDTIPO = :idtipo ORDER BY GP.NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idtipo',$idtipo,PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum tipo de parentesco foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar id criptografado md5
            elseif($tipo==38 && $idtipo>0 && $idbuscar>0){

                $tabela = retornaNomeTabela($idtipo);
                if($tabela==0){
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> IDTIPO não reconhecido. </li>");
                    echo json_encode($retorno);
                    exit();
                }

                //Monta o Select
                $sql = "SELECT * FROM $tabela WHERE MD5(ID) = :idbuscar;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idbuscar',$idbuscar,PDO::PARAM_STR);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> ID não encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar os unidades de fornecimento existente
            elseif($tipo==39){

                //Monta o Select
                $sql = "SELECT ID VALOR, concat(SIGLA, ' - ', NOME) NOMEEXIBIR FROM tab_unidadesfornecimento ORDER BY NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma unidade de fornecimento foi encontrada. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar os medicamentos existente
            elseif($tipo==40){

                //Monta o Select
                $sql = "SELECT ENF.ID VALOR, concat(ENF.NOME, ' (', UNI.SIGLA, ')') NOMEEXIBIR, ENF.ID, ENF.NOME, ENF.QTD, ENF.QTDESTOQUE, ENF.MINIMOESTOQUE
                FROM enf_medicamentos ENF
                INNER JOIN tab_unidadesfornecimento UNI ON UNI.ID = ENF.IDUNIDADE
                WHERE IDEXCLUSOREGISTRO IS NULL ORDER BY ENF.NOME;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma medicamento foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar dados do medicamentos informado
            elseif($tipo==41 && $idmedic>0){

                $where = '';
                if($verificaativo){
                    $where='AND IDEXCLUSOREGISTRO IS NULL';
                }
                //Monta o Select
                $sql = "SELECT * FROM enf_medicamentos WHERE ID = :idmedic $where;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idmedic',$idmedic,PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma dado foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar dados grau de parentesco
            elseif($tipo==42 && $idgrau>0){

                //Monta o Select
                $sql = "SELECT * FROM tab_grauparentesco WHERE ID = :idgrau;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->bindParam('idgrau',$idgrau,PDO::PARAM_INT);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma dado foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar DESC da tabela
            elseif($tipo==43 && $tabela>0){

                $tabela = retornaNomeTabela($tabela);

                //Monta o Select
                $sql = "DESC $tabela;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma dado foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar dados situacao
            elseif($tipo==44 && $idsituacao>0){
                $tiposituacao = isset($_POST['tiposituacao'])?$_POST['tiposituacao']:0;

                $params = [$idsituacao];
                if($tiposituacao>0){
                    //Monta o Select
                    $sql = "SELECT SIT.* FROM tab_situacaofiltro SF
                        INNER JOIN tab_situacao SIT ON SIT.ID = SF.IDSITUACAO
                        INNER JOIN tab_situacaotipo ST ON ST.ID = SF.IDTIPO
                        WHERE SIT.ID = ? AND ST.ID = ? LIMIT 1;";
                        array_push($params,$tiposituacao);
                }else{
                    //Monta o Select
                    $sql = "SELECT * FROM tab_situacao WHERE ID = ?;";
                }

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhuma dado foi encontrado para o ID informado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar lista de nacionalidade
            elseif($tipo==45){

                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR FROM tab_nacionalidade;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Listagem de nacionalidade não encontrada. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar lista de estados
            elseif($tipo==46){

                //Monta o Select
                $sql = "SELECT ID VALOR, SIGLA NOMEEXIBIR FROM tab_estados;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Listagem de Estados não encontrada. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar lista de emissores ou expedidores de RG
            elseif($tipo==47){

                //Monta o Select
                $sql = "SELECT ID VALOR, SIGLA NOMEEXIBIR FROM tab_orgao_expedidor;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Listagem de Estados não encontrada. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            //Buscar se o raio e cela informados é cela de excessão;
            elseif($tipo==48 && $idraio>0 && $cela>0){
                if($dataconsulta==''){
                    $dataconsulta = date('Y-m-d H:i');
                };
                $where = '';
                $params=[$idraio,$cela,$dataconsulta,$dataconsulta,$dataconsulta];
                if(count($arridtipos)>0){
                    foreach($arridtipos as $idtipo){
                        if($where==''){
                            $where = '?';
                        }else{
                            $where .= ',?';

                        }
                        array_push($params,$idtipo);
                    }
                    $where = "AND RE.IDTIPO IN ($where)";
                }

                //Monta o Select
                $sql = "SELECT RE.IDRAIO, RC.NOME RAIO, RC.NOMECOMPLETO, RC.QTD, RE.CELA, RE.IDTIPO, RET.NOME TIPO, RE.DATAINICIO, RE.DATATERMINO
                FROM tab_raioscelasexcecoes RE
                INNER JOIN tab_raioscelas RC ON RC.ID = RE.IDRAIO
                INNER JOIN tab_raioscelasexcecoestipo RET ON RET.ID = RE.IDTIPO
                WHERE RE.IDRAIO = ? AND RE.CELA = ? AND ((RE.DATAINICIO <= ? AND RE.DATATERMINO IS NULL) OR (RE.DATAINICIO <= ? AND RE.DATATERMINO >= ?)) $where AND RE.IDEXCLUSOREGISTRO IS NULL;";


                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute($params);
                $retorno = $stmt->fetchAll();
                echo json_encode($retorno);
                
            }
            //Buscar períodos existentes (Manhã, Tarde e Noite)
            elseif($tipo==49){

                //Monta o Select
                $sql = "SELECT ID VALOR, NOME NOMEEXIBIR, PER.* FROM tab_periodos PER";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                $stmt->execute();
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
        
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum período foi encontrado. </li>");
                    echo json_encode($retorno);
                    exit();
                }
            }
            
        } catch (PDOException $e) {
            $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> Ocorreu um erro. Erro: ". $e->getMessage()." </li>");
            echo json_encode($retorno);
            exit();
        }
    }else{
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-erro'> $conexaoStatus </li>");
        echo json_encode($retorno);
        exit();
    }

    exit();

    echo json_encode(array('MENSAGEM' => "<li class = 'mensagem-erro'> Linha ".__LINE__." </li>"));exit();
