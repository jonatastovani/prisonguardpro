<?php
    header('Content-Type: application/json');
    include_once "../../configuracoes/conexao.php";
    include_once "../../funcoes/funcoes.php";

    $tipo = isset($_POST['tipo'])?$_POST['tipo']:0;
    $textobusca = isset($_POST['textobusca'])?$_POST['textobusca']:0;
    $idunidade = isset($_POST['idunidade'])?$_POST['idunidade']:0;

    if($tipo==0){   
        $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum tipo foi informado. </li>");
        echo json_encode($retorno);
        exit();
    }

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            //Busca todas cidades, filtrando pelo textobusca se houver
            if($tipo==1 || $tipo==2 && $idunidade!=0){
                $params=[];
                
                $where='';
                if($tipo==1){
                    if(strlen($textobusca)>0){
                        $palavas = retornaArrayPalavras($textobusca);
                        
                        $where = "AND (";
                        foreach($palavas as $tex){
                            if($where != "AND ("){
                                $where .= " OR ";                        
                            }

                            $where .= "(UCASE(UN.NOMEUNIDADE) LIKE UCASE(?) OR UCASE(UN.NOMEATRIBUIDO) LIKE UCASE(?) OR UCASE(UN.CODIGO) LIKE UCASE(?) OR UCASE(UN.DIRETOR) LIKE UCASE(?) OR UCASE(UN.EMAILNOTES) LIKE UCASE(?) OR UCASE(UN.EMAILCIMIC) LIKE UCASE(?) OR UCASE(UN.ENDERECO) LIKE UCASE(?) OR UCASE(UN.CEP) LIKE UCASE(?) OR UCASE(UN.TELEFONES) LIKE UCASE(?) OR UCASE(CID.NOME) LIKE UCASE(?) OR UCASE(UNP.NOME) LIKE UCASE(?) OR UCASE(UNT.NOME) LIKE UCASE(?) OR UCASE(UNT.ABREVIACAO) LIKE UCASE(?) OR UCASE(UNC.NOME) LIKE UCASE(?))";
                            //Quantidade de substiuições que vão ser inseridas
                            $repeticao = 14;

                            for($iparams=0;$iparams<$repeticao;$iparams++){
                                array_push($params,"%$tex%");
                            }
                        }
                        $where .= ") ";
                    }

                }elseif($tipo==2 && $idunidade!=0){
                    $where = "AND UN.ID = :idunidade";
                }else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> ID Unidade não foi informado. </li>");
                    echo json_encode($retorno);
                    exit();            
                }

                $sql = "SELECT UN.ID, UN.NOMEUNIDADE, UN.NOMEATRIBUIDO, UN.CODIGO, UN.DIRETOR, UN.EMAILNOTES, UN.EMAILCIMIC, UN.ENDERECO, UN.CEP, UN.TELEFONES, CID.ID IDCIDADE, CID.NOME CIDADE, UNP.NOME PERFIL, UNP.ID IDPERFIL, UNT.ABREVIACAO TIPO, UNT.ID IDTIPO, UNC.NOME COORD, UNC.ID IDCOORD
                FROM tab_unidades UN
                INNER JOIN tab_cidades CID ON CID.ID = UN.IDCIDADE
                INNER JOIN tab_unidadesperfil UNP ON UNP.ID = UN.IDPERFIL
                INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
                INNER JOIN tab_unidadescoordenadorias UNC ON UNC.ID = UN.IDCOORDENADORIA
                WHERE UN.IDEXCLUSOREGISTRO IS NULL AND UN.DATAEXCLUSOREGISTRO IS NULL $where
                ORDER BY NOMEUNIDADE
                ;";

                $stmt = $GLOBALS['conexao']->prepare($sql);
                if($tipo==1){
                    $stmt->execute($params);
                }elseif($tipo==2){
                    $stmt->bindParam('idunidade',$idunidade,PDO::PARAM_INT);
                    $stmt->execute();
                }
        
                $resultado = $stmt->fetchAll();
                //unset($GLOBALS['conexao']);
   
                //Verifica se foi encontrado algum registro
                if(count($resultado)){
                    /*$retorno='';
                    foreach($resultado as $dados){
                        $retorno .= "<option value=".$dados['VALOR'].">".$dados['NOMEEXIBIR']."</option>";
                    }*/

                    echo json_encode($resultado);
                    exit();
                }
                else{
                    $retorno = array('MENSAGEM' => "<li class = 'mensagem-aviso'> Nenhum Unidade foi encontrada. </li>");
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