<?php session_start();
//
// Sisgep ® - Versão 2.0 05/01/2005 SDR-Systems - RD
// Modulo >--->  Pesquisa Atendimentos - Segurança
//
if (@$_SESSION["log_ok"] <> "ok") {
	@$_SESSION["url_chamada"] = @$_SERVER['PHP_SELF'];
	header("Location:/login.php");
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- Sisgep ® - Versão 2.0 - SDR-Systems -->
<html>
<head>
<title>Sisgep - Pesquisa de Sentenciados</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/style.css" type="text/css">
</head>
<body>
<p align="center"><a href="../meio.php" title="Retorna ao Login"><img src="../imagens_part/barra_pjbas.jpg" width="620" height="70" border="0"></a>
<form name="pesquisa" method="post" action="listagem.php" target="_blank">
<div align="center">
  <p class="titulo_pagina">Listagens de Sentenciados</p>
</div>
<div align="center">
  <table width="182" border="1" align="center" class="texto">
 <tr>
    <td width="23" bgcolor="#969AFD">
        <div align="left">
          <input name="opcao" type="radio" value="matricge" checked="checked">
        </div></td>
    <td width="143" bgcolor="#969AFD"><div align="left">Matricula - Geral </div></td>
  </tr>
  <tr>
    <td bgcolor="#969AFD">
      <input name="opcao" type="radio" value="nome">    </td>
    <td bgcolor="#969AFD"><div align="left">Nome -
           Geral
         <input name="remet" type="hidden" value="tipo_listagem.php">    
         </div></td>
  </tr>
  <tr>
    <td bgcolor="#969AFD"><input name="opcao" type="radio" value="pavilhao"></td>
    <td bgcolor="#969AFD"><div align="left">Pavilh&atilde;o - Geral</div></td>
  </tr>
  <tr>
    <td bgcolor="#969AFD"><input name="opcao" type="radio" value="pavilhao_opt"></td>
    <td bgcolor="#969AFD"><select name="pav_escolha" id="select">
      <option selected></option>
      <option value="1">RAIO1</option>
      <option value="2">RAIO2</option>
      <option value="3">RAIO3</option>
	  <option value="4">RAIO4</option>
	  <option value="5">RAIO5</option>
	  <option value="6">RAIO6</option>
      <option value="RCD">DISCIPLINAR</option>
      <option value="SEG">SEGURO</option>
      <option value="TRI">TRI</option>
      <option value="ENF">ENFERMARIA</option>
      <option value="INC">INCLUS&Atilde;O</option>
      <option value="APP">APP</option>
      <option value="TR">TR&Acirc;NSITO DA CASA</option>
    </select></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#969AFD"><div align="center"></div></td>
    </tr>
  <tr>
    <td colspan="2" bgcolor="#969AFD"><div align="center">
        <input name="imageField" type="image" src="../imagens_comuns/enviar.jpg" width="85" height="16" border="0" onClick="submit">
    </div></td>
    </tr>
  <tr>
    <td colspan="2" bgcolor="#969AFD"><div align="center"><span class="style4"><a href="index.php"><img src="../imagens_comuns/voltar.jpg" width="85" height="16" border="0"></a></span></div></td>
    </tr>
</table>
</form>
</body>
</html>
