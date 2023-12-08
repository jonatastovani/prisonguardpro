<?php session_start();
//
// Sisgep ® - Versão 2.0 11/01/2005 SDR-Systems - DG
// Modulo >--->  Lista  Sentenciados - Segurança
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
<title>Sisgep - Resultado da Pesquisa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/style.css" type="text/css">
</head>
<body>
<?php 
//$dest = $_POST["dest"];
$remet = $_POST["remet"];
$opcao = $_POST["opcao"]; 
$valor = $_POST["pav_escolha"]; 
$regime = $_POST["regime"]; 

if ($valor == "TR") { $opcao = "tr_da_casa"; } 


if ($valor == "") { $valor = "*"; } 
if ($opcao == "matricula")		{ $sql = "select Matric_Cad, Nome_Cad, RgNum_Cad, Exec_Cad from cadastros where Dl = '+' and Regime_Cad = 'FE' order by Matric_Cad";}
if ($opcao == "matricsa")		{ $sql = "select Matric_Cad, Nome_Cad, RgNum_Cad, Exec_Cad from cadastros where Dl = '+' and Regime_Cad = 'SA' order by Matric_Cad";}
if ($opcao == "matricge")		{ $sql = "select Matric_Cad, Nome_Cad, RgNum_Cad, Exec_Cad, Regime_Cad from cadastros where Dl = '+' order by Matric_Cad";}
if ($opcao == "nome")			{ $sql = "select Matric_Cad, Nome_Cad, RgNum_Cad, Exec_Cad from cadastros where Dl = '+' order by Nome_Cad";}
if ($opcao == "nomefe")			{ $sql = "select Matric_Cad, Nome_Cad, RgNum_Cad, Exec_Cad from cadastros where Dl = '+' and Regime_Cad = 'FE' order by Nome_Cad";}
if ($opcao == "nomesa")			{ $sql = "select Matric_Cad, Nome_Cad, RgNum_Cad, Exec_Cad from cadastros where Dl = '+' and Regime_Cad = 'SA' order by Nome_Cad";}
if ($opcao == "pavilhao")		{ $sql = "select Matric_Cel, Cela_Cel, Pav_Cel, Cama_Cel from celas where Dl_Cel = '+' order by Pav_Cel, Matric_Cel";}
if ($opcao == "pavilhao_opt")	{ $sql = "select Matric_Cel, Cela_Cel, Pav_Cel, Cama_Cel from celas where Pav_Cel= '$valor' and Dl_Cel = '+' order by Cela_Cel, Matric_Cel";}
if ($opcao == "tr_da_casa")		{ $sql = "select Matric_Cad, Nome_Cad from cadastros where Status_Cad = 'T' and Dl = '+' order by Matric_Cad";}

//$sql = "select Matric_Cad, Nome_Cad, Mae_Cad from cadastros where Dl = '+' and Regime_Cad = 'SA' order by Nome_Cad";
// Conexão com o banco de dados
include("../includes/conecta.php"); 
$rs = mysql_query($sql) or die(mysql_error());
$n_row = mysql_num_rows($rs);

?>
<p align="center" class="titulo_pagina">Listagem de Sentenciados</p>
<div align="center">
  <p class="texto"><?php echo " Encontrado(s) " . $n_row . " registro(s).";?></p>
</div>
<?php if ($opcao == "matricula" or $opcao == "matricsa" or $opcao == "matricge" or $opcao == "nome" or $opcao == "nomefe" or $opcao == "nomesa" or $opcao == "tr_da_casa"){ ?>
<table width="662" border="0" align="center" class="texto">
  <tr>
    <td width="118"><div align="center">Matr&iacute;cula</div></td>
    <td width="242"><div align="left">Nome do Sentenciado</div></td>
    <td width="64"><div align="center">Rg</div></td>
    <td width="64"><div align="center">Execu&ccedil;&atilde;o</div></td>
    <td width="57"><div align="center">Pavilh&atilde;o</div></td>
    <td width="35"><div align="center">Cela</div></td>
    <td width="52"><div align="center">Regime</div></td>
	<?php
			while($row = mysql_fetch_array($rs))
			{
			$sqlc = "select Cela_Cel, Pav_Cel, Cama_Cel from celas where Matric_Cel = '$row[0]' and Dl_Cel = '+'";
			$rsc = mysql_query($sqlc) or die(mysql_error());
			$rowc = mysql_fetch_array($rsc);
			
		?>
 <tr>
    <td><div align="center" class="style1"><?php echo substr($row[0],0,3) . "." . substr($row[0],3,3) . "-" . substr($row[0],6,1); ?></div></td>
    <td><div align="left"><?php echo $row[1]; ?></div></td>
    <td><div align="right"><?php echo $row[2]; ?></div></td>
    <td><div align="center"><?php echo $row[3]; ?></div></td>
    <td><div align="center"><?php echo $rowc[1]; ?></div></td>
    <td><div align="center"><?php echo $rowc[0]; ?></div></td>
    <td><div align="center"><?php echo $row[4]; ?></div></td>
 </tr>
    <?php } ?>
</table>
    <?php
}
?>
<?php if ($opcao == "pavilhao" or $opcao == "pavilhao_opt"){ ?>
    
	<table width="660" border="0" align="center" class="texto">
      <tr bgcolor="#0000FF">
        <td width="116" height="17" bgcolor="#FFFFFF"><div align="center">Matr&iacute;cula</div></td>
        <td width="357" bgcolor="#FFFFFF"><div align="left">Nome do Sentenciado</div></td>
        <td width="88" bgcolor="#FFFFFF"><div align="center">Pavilh&atilde;o</div></td>
        <td width="81" bgcolor="#FFFFFF"><div align="center">Cela</div></td>
        <?php
			while($row = mysql_fetch_array($rs))
			{
			$sqlc = "select Matric_Cad, Nome_Cad from cadastros where Matric_Cad = '$row[0]'";
			$rsc = mysql_query($sqlc) or die(mysql_error());
			$rowc = mysql_fetch_array($rsc);
			
		?>
      <tr>
        <td><div align="center"><?php echo substr($row[0],0,3) . "." . substr($row[0],3,3) . "-" . substr($row[0],6,1); ?></div></td>
        <td><div align="left"><?php echo $rowc[1]; ?></div></td>
        <td><div align="center" class="style1"><?php echo $row[2]; ?></div></td>
        <td><div align="center" class="style1"><?php echo $row[1]; ?></div></td>
      </tr>
<?php      } ?>
	  <?php } ?>
    </table>
</body>
</html>