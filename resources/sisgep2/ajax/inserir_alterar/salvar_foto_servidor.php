<?php
    //header('Content-Type: application/json');
    include_once "../../funcoes/funcoes_comuns.php";
    
    //O usuário FTP tem acesso somente a pasta Fotos que está na raiz do 242, se for salvar em outro lugar então se cria outro usuário FTP indicando a pasta que ele irá ter acesso.

    //$tipo 1 = Fotos de presos
    //$tipo 2 = Fotos de visitantes
    $tipo = $_POST['tipo'];
    // $fotos = [array('nome'=>1,'imagem'=>$_POST['imagem'])];

    $fotos = $_POST['fotos'];

    $matric = isset($_POST['matric'])?$_POST['matric']:0;
    $idpreso = isset($_POST['idpreso'])?$_POST['idpreso']:0;
    $cpfvisitante = isset($_POST['cpfvisitante'])?$_POST['cpfvisitante']:0;

    if($tipo==0){
        $retorno = array('MENSAGEM'=>"<li class='mensagem-erro'> Tipo não informado! </li>");
        echo json_encode($retorno);
        exit();
    }

    if($tipo==1){
        if($matric==0 && $idpreso==0){
            $retorno = array('MENSAGEM'=>"<li class='mensagem-erro'> Matrícula e ID Preso não informados! </li>");
            echo json_encode($retorno);
            exit();
        }elseif($matric==0){
            $retorno = array('MENSAGEM'=>"<li class='mensagem-erro'> Matrícula não informada! </li>");
            echo json_encode($retorno);
            exit();
        }elseif($idpreso==0){
            $retorno = array('MENSAGEM'=>"<li class='mensagem-erro'> ID Preso não informado! </li>");
            echo json_encode($retorno);
            exit();
        }else{
            $matricula = midMatricula($matric,3);
        }        
    }
    
    if($tipo==2 && $cpfvisitante==0){
        $retorno = array('MENSAGEM'=>"<li class='mensagem-erro'> CPF visitante não informado! </li>");
        echo json_encode($retorno);
        exit();
    }

    $nomepasta = '';
    $pastaInclusao = 'Fotos_inclusao';
    $nomefotofrontal = '';
    switch($tipo){
        case 1:
            $nomepasta = 'Fotos_sentenciados';
            $nomefotofrontal = $idpreso;
            break;

        case 2:
            $nomepasta = 'Fotos_visitantes';
            $nomefotofrontal = $cpfvisitante;
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
            //$caminholocal é o caminho no servidor local
            //Se cria a pasta da matrícula do preso, nela terá cada pasta de toda passagem do preso na unidade, organizada pelo ID do preso
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

        }else{
            //$caminholocal é o caminho no servidor local
            $caminholocal = "../../fotos/Fotos_visitantes";
        }

        if(in_array($tipo,array(1,2))){
            foreach($fotos as $foto){
                $nome = $foto['nome'];
                if($nome==1){
                    //Salva a foto com o ID do preso, para ser inserido na pasta de Fotos_Sentenciados
                    $imagem = $foto['imagem'];
                    list($type, $imagem) = explode(';', $imagem);
                    list($base, $imagem) = explode(',', $imagem);
                    $imagem = base64_decode($imagem);
                    file_put_contents("$caminholocal/$nomefotofrontal.jpg",$imagem);
                }

                // Somente o tipo 1 tem que salvar mais que uma foto..
                if($tipo==1){
                    $nome = "$matricula $nome";
                    $imagem = $foto['imagem'];
                    list($type, $imagem) = explode(';', $imagem);
                    list($base, $imagem) = explode(',', $imagem);

                    $imagem = base64_decode($imagem);

                    file_put_contents("$caminholocal/$nome.jpg",$imagem);
                }
            }
        }

        // Copiar a foto frontal para a pasta Fotos_sentenciados ou Fotos_visitantes do servidor remoto TRUENASS
        if (!ftp_put($conexaoFTP, "$nomepasta/$nomefotofrontal.jpg", "$caminholocal/$nomefotofrontal.jpg",FTP_BINARY)) {
            // Se não for enviado, mostra essa mensagem
            $retorno = array('MENSAGEM'=>"<li class='mensagem-erro'> Erro ao enviar foto Frontal para o servidor remoto. </li>");
            echo json_encode($retorno);
            exit();
        }

        //Somente tipo 1 que tem mais fotos para serem salvas
        if($tipo==1){
            // Copiar todas as outras fotos para a pasta Fotos_inclusao do servidor remoto
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
        }
        
        $retorno = array('OK'=>"<li class='mensagem-exito'> Salvo com sucesso! </li>");
        echo json_encode($retorno);
        ftp_close($conexaoFTP);
        exit();

    }else{
        $retorno = array('MENSAGEM'=>'<li class="mensagem-erro"> Insucesso no Login </li>');
        echo json_encode($retorno);
        exit();
    }
