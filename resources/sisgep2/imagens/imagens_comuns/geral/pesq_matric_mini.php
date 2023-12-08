<?php session_start();
//
// Sisgep ® - Versão 2.0 18/11/2004 SDR-Systems - DG
// Modulo >--->  Pesquisa Sentenciado - Segurança
//
if (@$_SESSION["log_ok"] <> "ok") {
	@$_SESSION["url_chamada"] = @$_SERVER['PHP_SELF'];
	header("Location:/login.php");
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- Sisgep ® - Versão 1.1 - SDR-Systems -->
<html>
<head>
<title>Sisgep - Pesquisa de Sentenciados</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/style.css" type="text/css">
</head>
<p align="center"><a href="index.php" title="Retorna ao Login"><img src="../imagens_part/barra_pjbas.jpg" width="620" height="70" border="0"></a>
<form name="pesquisa" method="post" action="list_matric.php">
<table width="427" border="0" align="center">
  <tr>
    <td width="421" bgcolor="#0000FF"><div align="center" class="titulo_tabela">Pesquisa Sentenciado</div></td>
  </tr>
</table>
  <table width="427" border="0" align="center" class="nome_campo">
    <tr>
      <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
      <td width="21"><input name="opcao" type="radio" value="matricula" checked></td>
      <td width="81"><div align="left">Matr&iacute;cula</div></td>
      <td width="20"><div align="left">
        <input name="opcao" type="radio" value="nome">
      </div></td>
      <td width="51"><div align="left">Nome</div></td>
      <td width="21"><input name="opcao" type="radio" value="execucao"></td>
      <td width="75"><div align="left">Execu&ccedil;&atilde;o</div></td>
      <td width="21"><input name="opcao" type="radio" value="pai"></td>
      <td width="33"><div align="left">Pai</div></td>
      <td width="21"><input name="opcao" type="radio" value="mae"></td>
      <td width="41"><div align="left">M&atilde;e</div></td>
    </tr>
    <tr>
      <td colspan="10"><input name="text_pesq" type="text" id="textfield5" size="70" maxlength="70"></td>
    </tr>
    <tr>
      <td colspan="10">
      <input name="remet" type="hidden" value="pesq_matric_mini.php">
      <input name="dest" type="hidden" value="mini_qualificativa.php"></td>
    </tr>
    <tr>
      <td colspan="10"><div align="center"><input name="imageField" type="image" src="../imagens_comuns/enviar.jpg" width="85" height="16" border="0" onClick="submit">
      </div></td>
    </tr>
  </table>
</form>  
<p align="center">
  <input name="imageField2" type="image" src="../imagens_comuns/voltar.jpg" width="85" height="16" border="0" onClick="document.location.href='index.php'">

</body>
</html>
