<?php session_start();
//
// Sisgep ® - Versão 2.0 18/11/2004 SDR-Systems - DG
// Modulo >--->  Página Principal Segurança - Segurança
//
if (@$_SESSION["log_ok"] <> "ok") {
	@$_SESSION["url_chamada"] = @$_SERVER['PHP_SELF'];
	header("Location:/login.php");
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- SisGep ® - Versão 2.0 - SDR-Systems -->
<html>
<head>
<title>Sisgep - Sistema Gerencial Prisional</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
<link rel="stylesheet" href="../css/style.css" type="text/css">
</head>
<body>
<div align="center"><a href="../meio.php"><img src="../imagens_part/barra_pjbas.jpg" width="620" height="70" border="0"></a></div>
</p>
<p align="center" class="titulo_pagina">CONSULTAS</p>
<div align="center">
<table width="422" height="216" border="0" align="center">
  <tr>
    <td width="136" height="105"><div align="center">
      <p><a href="pesq_matric_mini.php"><img src="../imagens_comuns/userinfo.png" alt="" width="55" height="55"></a></p>
      <p class="texto">Pesquisa Sentenciado</p>
      </div></td>
    <td width="136"><div align="center">
      <p><a href="../list_pop.php"><img src="../imagens_comuns/contagem.png" width="55" height="55"></a></p>
      <p class="texto">Contagem</p>
    </div></td>
    <td width="136"><div align="center">
      <p><a href="../em_construcao.php"><img src="../imagens_comuns/atendimento.jpg" width="55" height="55"></a></p>
      <p class="texto">Atendimento</p>
    </div></td>
  </tr>
  <tr>
    <td height="105"><div align="center">
      <p><a href="tipo_listagem.php"><img src="../imagens_comuns/text-enriched.png" width="55" height="55"></a></p>
      <p class="texto">Listagens</p>
    </div></td>
    <td heigth ="135"><div align="center">
      <p><a href="http://10.14.5.40"><img src="../imagens_comuns/gepen.jpg" width="120" height="49"></a></p>
      <p class="texto">GEPEN</p>
    </div></td>
    <td height "135"><div align="center">
      <p><a href="http://10.200.206.10:81/sap"><img src="../imagens_comuns/sbprodesp.gif" width="75" height="55"></a></p>
      <p class="texto">Prodesp (GSA)</p>
      </div></td>
  </tr>
</table>
<input name="remet" type="hidden" value="/seguranca/inclusao/index.php" >
</div></p>
<p align="center"><a href="../meio.php"><img src="../imagens_comuns/voltar.jpg" width="85" height="16"></a></p>
</body>
</html>
