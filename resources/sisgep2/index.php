<?php
    //Sessão
    session_start();
    //$_SESSION['pasta-origem'] = __DIR__;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SISGEP - Sistema de Gerenciamento Prisional</title>
  <link rel="stylesheet" href="css/style-login.css" type="text/css">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  
</head>

<body onload="form1.usuario.focus()">
<div class="container">
      
  <div class="box">

    <div class="formulario">
      <!-- /Exibe os erros caso haja algum erro -->
      <span id="erros"></span>
      <!-- Por padrão já abre a mesma página, mas é bom colocar o PHP_SELF -->
      <form id="form1" name="form1">
        <label for="usuario">Usuário: </label>
        <input type="text" id="usuario" name="usuario" class="caixa-texto" autocomplete="FALSE"><br>
        <label for="senha">Senha: </label>
        <input type="password" id="senha" name="senha" class="caixa-texto"><br>
        <div class="alinhamento-direita"><input type="submit" form="form1" class="btn-sub"></div>
      </form>
    </div>
  </div>
</div>
  <!-- Scripts do index -->
  <script src="js/jQuery/jquery-3.6.0.min.js.js"></script>
  <script src="js/index/index.js"></script>

</body>
</html>

