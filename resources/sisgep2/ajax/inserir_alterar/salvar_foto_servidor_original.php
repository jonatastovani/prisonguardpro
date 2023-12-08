<?php
    //header('Content-Type: application/json');
    include_once "../../funcoes/funcoes_comuns.php";
    
    //O usuário FTP tem acesso somente a pasta Fotos que está na raiz do 242, se for salvar em outro lugar então se cria outro usuário FTP indicando a pasta que ele irá ter acesso.

    //$tipo 1 = Fotos de presos
    //$tipo 2 = Fotos de visitantes
    $fotos = $_POST['fotos'];
    $tipo = $_POST['tipo'];
    $matric = $_POST['matric'];
    $matricula = midMatricula($matric,3);
    $idpreso = $_POST['idpreso'];
        
    $nomepasta = 'Fotos_sentenciados';
    $pastaInclusao = 'Fotos_inclusao';
    switch($tipo){
        case 1:
            $nomepasta = 'Fotos_sentenciados';
            break;
        case 2:
            $nomepasta = 'Fotos_visitantes';
            break;
    }

    $servidor = "10.14.239.242";
    $usuario = "ftpuser";
    $senha = "senha";
    $conexaoFTP = ftp_connect($servidor);
    $login = ftp_login($conexaoFTP,$usuario,$senha);
    //Define como passivo, senão da erro de PORTA
    ftp_pasv($conexaoFTP, true);

    if($login==1){

        if($tipo==1){
            $caminholocal = "../../fotos/Fotos_inclusao/$matricula";
            
            //Verifica se já existe a pasta com o nome sendo a matrícula do preso, se não existir então se cria a pasta
            if(!file_exists($caminholocal)){
                mkdir($caminholocal);
            }
            //Cria uma pasta com o ID do preso
            $caminholocal .= "/IDPreso-$idpreso";
            if(!file_exists($caminholocal)){
                mkdir($caminholocal);
            }

            foreach($fotos as $foto){
                $nome = $foto['nome'];
                if($nome==1){
                    //Salva a foto com o ID do preso, para ser inserido na pasta de Fotos_Sentenciados
                    $imagem = $foto['imagem'];
                    list($type, $imagem) = explode(';', $imagem);
                    list($base, $imagem) = explode(',', $imagem);
                    $imagem = base64_decode($imagem);
                    file_put_contents("$caminholocal/$idpreso.jpg",$imagem);
                }

                $nome = "$matricula $nome";
                $imagem = $foto['imagem'];
                list($type, $imagem) = explode(';', $imagem);
                list($base, $imagem) = explode(',', $imagem);

                $imagem = base64_decode($imagem);

                file_put_contents("$caminholocal/$nome.jpg",$imagem);
            }
        }

        /* Copiar a foto frontal para a pasta Fotos_sentenciados do servidor remoto */
        if (!ftp_put($conexaoFTP, "$nomepasta/$idpreso.jpg", "$caminholocal/$idpreso.jpg",FTP_BINARY)) {
            // Se não for enviado, mostra essa mensagem
            $retorno = array('MENSAGEM'=>"<li class='mensagem-erro'> Erro ao enviar arquivos </li>");
            echo json_encode($retorno);
            exit();
        }

        /* Copiar todas as outras fotos para a pasta Fotos_inclusao do servidor remoto */
        $arquivos = clean_scandir($caminholocal);
        
        //Cria a pasta que serão salvas as fotos no servidor remoto
        $pastaInclusao .= "/$matricula";
        if(!ftp_nlist ($conexaoFTP,$pastaInclusao)){
            ftp_mkdir($conexaoFTP,$pastaInclusao);
        }

        $pastaInclusao .= "/IDPreso-$idpreso";
        if(!ftp_nlist ($conexaoFTP,$pastaInclusao)){
            ftp_mkdir($conexaoFTP,$pastaInclusao);
        }

        for($i=0;$i<count($arquivos);$i++){
            //Verifica se o nome do arquivo já existe no servidor remoto e exclui caso existir
            $nomedestino = "$pastaInclusao/".$arquivos[$i];
            if(ftp_nlist ($conexaoFTP,$nomedestino)){
                ftp_delete($conexaoFTP,$nomedestino);
            }
    
            /* Copiar o arquivos do servidor local para o servidor remoto */
            $salva = ftp_put($conexaoFTP, $nomedestino, "$caminholocal/".$arquivos[$i],FTP_BINARY);
            if (!$salva) {
                // Se não for enviado, mostra essa mensagem
                $retorno = array('MENSAGEM'=>"<li class='mensagem-erro'> Erro ao enviar arquivos </li>");
                echo json_encode($retorno);
                exit();
            }
        }
        $retorno = array('OK'=>"<li class='mensagem-exito'> Salvo com sucesso! $salva </li>");
        echo json_encode($retorno);
        ftp_close($conexaoFTP);
        exit();

    }else{
        $retorno = array('MENSAGEM'=>'<li class="mensagem-erro"> Insucesso no Login </li>');
        echo json_encode($retorno);
        exit();
    }
