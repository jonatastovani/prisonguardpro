<?php session_start();
//
// Sisgep ® - Versão 2.0 18/11/2004 SDR-Systems - DG
// Modulo >--->  Página Principal Segurança - Segurança
//
if (@$_SESSION["log_ok"] <> "ok") {
	@$_SESSION["url_chamada"] = @$_SERVER['PHP_SELF'];
	header("Location:/meio.php");
	}
	else
	if ((@$_SESSION["user_area"] <> "seg" and @$_SESSION["user_area"] <> "cimic" and @$_SESSION["user_area"] <> "all") or  (@$_SESSION["user_nivel"] < 3)) {
	@$_SESSION["url_chamada"] = @$_SERVER['PHP_SELF'];
	header("Location:/acesso_negado.php");
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
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
</head>
<body>
<div align="center"><a href="../../meio.php"><img src="../imagens_part/barra_pjbas.jpg" width="620" height="70" border="0"></a></div>
</p>
<p align="center" class="titulo_pagina">C A D A S T R O</p>
<div align="center">
<table width="562" height="323" border="0" align="center">
  <tr>
    <td width="136" height="105"><div align="center">
      <p><a href="pesq_matric.php"><img src="../imagens_comuns/userinfo.png" alt="" width="55" height="55"></a></p>
      <p class="texto">Pesquisar Qualificativa</p>
    </div></td>
    <td width="136"><div align="center">
      <p><a href="agenda.php"><img src="../imagens_comuns/kwrite.png" width="55" height="55"></a></p>
      <p class="texto">Agenda de<br> Escoltas</p>
    </div></td>
    <td width="136"><div align="center">
        <p><a href="movimentacao.php"><img src="../imagens_comuns/truck.png" width="55" height="55"></a></p>
        <p class="texto">Movimenta&ccedil;&otilde;es<br> de presos</p>
    </div></td>
    <td width="136"><div align="center">
      <p><a href="mem_of.php"><img src="../imagens_comuns/oficio2.jpg" width="55" height="55"></a></p>
      <p class="texto">Ofícios e Ordens de Saída</p>
    </div></td>
  </tr>
  <tr>
    <td height="105"><div align="center">
      <p><a href="../cadastro/list_pop_cad.php"><img src="../imagens_comuns/log.png" alt="" width="55" height="55"></a></p>
      <p class="texto">Contagem da Unidade</p>
    </div></td>
    <td heigth ="135"><div align="center">
      <p><a href="tipo_listagem.php"><img src="../imagens_comuns/text-enriched.png" width="55" height="55"></a></p>
      <p class="texto">Listagens</p>
    </div></td>
    <td "135"><div align="center" class="texto">
        <p><a href="tipo_listagem_mov.php"><img src="../imagens_comuns/window_list.png" width="55" height="55"></a></p>
        <p class="texto">Relat&oacute;rio de Movimenta&ccedil;&otilde;es</p>
    </div></td>
    <td height "135"><div align="center" class="texto">
      <p><a href="tipo_listagem_mov.php"><img src="../imagens_comuns/window_list.png" width="55" height="55"></a></p>
      <p class="texto">Relat&oacute;rio de Movimenta&ccedil;&otilde;es</p>
    </div></td>
  </tr>
  <tr>
    <td height="105"><div align="center">
      <p><a href="cad_qualifi_bas.php"><img src="../imagens_comuns/folder_home.png" width="55" height="55"></a></p>
      <p class="texto">Incluir 
        Sentenciados (Agenda)</p>
      </div></td>
    <td heigth ="135"><div align="center">
      <p><a href="pesq_localidade.php"><img src="../imagens_comuns/icone_mapa.jpg" width="55" height="55"></a></p>
      <p class="texto">Pesquisar Localidade</p>
    </div></td>
    <td "135"><div align="center">
        <p><a href="../base.php"><img src="../imagens_comuns/kpresenter.png" width="64" height="64"></a></p>
        <p class="texto">Certifica Matricula</p>
    </div></td>
    <td height "135"><div align="center">
      <p><a href="../base.php"><img src="../imagens_comuns/kpresenter.png" width="64" height="64"></a></p>
      <p class="texto">Certifica Matricula</p>
    </div></td>
  </tr>
</table>
<input name="remet" type="hidden" value="/seguranca/inclusao/index.php" >
</div></p>
<table width="422" align="center">
<tr>
  <td>&nbsp;</td>
  <td><div align="center" class="style1">P&aacute;gina 1 </div></td>
  <td>&nbsp;</td>
</tr>
<tr>
<td><p align="left"><img src="../imagens_comuns/seta.png" width="59" height="57"></a></p></td>
<td><p align="center"><a href="../seguranca/index.php"><img src="../imagens_comuns/home.jpg" width="120" height="55" align="absmiddle"></a></td>
<td><p align="right"><a href="/cadastro/index2.php"><img src="../imagens_comuns/seta.png" width="59" height="57"></a></p></td>
</tr>
</table>
</body>
</html>
