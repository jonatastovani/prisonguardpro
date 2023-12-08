<?php

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){

        $sql = "SELECT concat(UNT.NOME, ' de ', UN.NOMEUNIDADE) NOMEUNIDADE, UN.NOMEATRIBUIDO, concat(CID.NOME, '/', EST.SIGLA) CIDADE, UN.CODIGO,
        concat(UN.ENDERECO, ' - CEP:', UN.CEP) ENDERECO, UN.TELEFONES, UN.EMAILCIMIC, UNC.NOME COORDENADORIA
        FROM tab_dadosunidade DU
        INNER JOIN tab_unidades UN ON DU.IDUNIDADE = UN.ID
        INNER JOIN tab_unidadescoordenadorias UNC ON UNC.ID = UN.IDCOORDENADORIA
        INNER JOIN tab_cidades CID ON CID.ID = UN.IDCIDADE
        INNER JOIN tab_estados EST ON EST.ID = CID.IDUF
        INNER JOIN tab_unidadestipos UNT ON UNT.ID = UN.IDTIPOUNIDADE
        WHERE DU.ID = 1;";

        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetchAll();
        $Coordenadoria_unidade = $resultado[0]['COORDENADORIA'];
        $Nome_unidade = $resultado[0]['NOMEUNIDADE'];
        $Nome_atribuido = $resultado[0]['NOMEATRIBUIDO'];
        $Codigo_unidade = $resultado[0]['CODIGO'];
        $Cidade_unidade = $resultado[0]['CIDADE'];
        $Endereco_unidade = $resultado[0]['ENDERECO'];
        $Telefones_unidade = $resultado[0]['TELEFONES'];
        $EmailCimic_unidade = $resultado[0]['EMAILCIMIC'];

    }else{
        echo $conexaoStatus;
        exit();
    }
