<?php session_start();
//
// Sisgep ® - Versão 2.0 04/11/2004 SDR-Systems - RD
// Modulo >--->  Lista  Sentenciados - Segurança
//
if (@$_SESSION["log_ok"] <> "ok") {
	@$_SESSION["url_chamada"] = @$_SERVER['PHP_SELF'];
	header("Location:/login.php");
	}
	
?>
<?php 
$dest = $_POST["dest"];
$remet = $_POST["remet"];
$parametro = $_POST["parametro"];
$operacao = $_POST["operacao"];
$opcao = $_POST["opcao"];
$valor = strtoupper($_POST["text_pesq"]); 
include("../includes/conecta.php"); 
if ($valor == "") { $valor = "*"; } 
if ($opcao == "matricula")	{ $sql = "select Matric_Cad, Nome_Cad, Mae_Cad from cadastros where Matric_Cad like '" . $valor . "%' order by Matric_Cad"; $campo = "Nome da Mãe";}
if ($opcao == "nome")			{ $sql = "select Matric_Cad, Nome_Cad, Mae_Cad from cadastros where Nome_Cad like '" . $valor . "%' order by Nome_Cad"; $campo = "Nome da Mãe";}
if ($opcao == "execucao")		{ $sql = "select Matric_Cad, Nome_Cad, Exec_Cad from cadastros where Exec_Cad like '" . $valor . "%' order by Exec_Cad"; $campo = "Número da Execução";}
if ($opcao == "pai")			{ $sql = "select Matric_Cad, Nome_Cad, Pai_Cad from cadastros where Pai_Cad like '" . $valor . "%' order by Nome_Cad"; $campo = "Nome do Pai";}
if ($opcao == "mae")			{ $sql = "select Matric_Cad, Nome_Cad, Mae_Cad from cadastros where Mae_Cad like '" . $valor . "%' order by Nome_Cad"; $campo = "Nome da Mãe";}

		$rs = mysql_query($sql) or die(mysql_error());
		$n_row = mysql_num_rows($rs);
				
		/* if ( $n_row == 1 and $parametro == "direto") {
			$row = mysql_fetch_array($rs);
			$matric_direto = $row[0];
			$nome = $row[1];
			header("Location: registra_saida_pv.php?matric=$matric_direto&operacao=$operacao"); 			
 		}; 
 */?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- Sisgep ® - Versão 2.0 - SDR-Systems -->
<html>
<head>
<title>Sisgep - Resultado da Pesquisa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/style.css" type="text/css">
</head>
<body>
<p align="center"><img src="../imagens_part/barra_pjbas.jpg" width="620" height="70" border="0">
<p align="center" class="titulo_pagina">Pesquisa de Sentenciados</p>
<p align="center"><a href="<?php echo $remet ; ?>"><img src="../imagens_comuns/voltar.jpg" width="85" height="16" border="0"></a></p>
<div align="center" class="texto"><?php echo " Encontrado(s) " . $n_row . " registro(s).";?></div>
<p></p>
<table width="697" border="1" align="center">
  <tr bgcolor="#0000FF">
    <td width="79" class="titulo_tabela"><div align="center">Matr&iacute;cula</div></td>
    <td width="314" class="titulo_tabela"><div align="center">Nome do Sentenciado </div></td>
    <td width="282" class="titulo_tabela"><div align="center"><?php echo $campo; ?></div></td>
	<?php 
		
			while($row = mysql_fetch_array($rs))
			{
         	$Matric = substr($row[0],0,6) . "-" . substr($row[0],6,1);
		?>
 <tr>
    <td class="nome_campo"><a href="<?php echo $dest ; ?>?matric=<?php echo $row[0];?>&nome=<?php echo $row[1];?>&operacao=<?php echo $operacao;?>">
    <div align="center"><?php echo $Matric; ?></div></a></td>
    <td class="nome_campo"><a href="<?php echo $dest ; ?>?matric=<?php echo $row[0];?>&nome=<?php echo $row[1];?>&operacao=<?php echo $operacao;?>"><?php echo $row[1]; ?></a></td>
    <td class="nome_campo"><a href="<?php echo $dest ; ?>?matric=<?php echo $row[0];?>&nome=<?php echo $row[1];?>&operacao=<?php echo $operacao;?>"><?php echo $row[2]; ?></a></td>
 </tr>
	<?  
	} ?>
</table>
<div align="center"><br>
  <a href="<?php echo $remet ; ?>?operacao=<? echo $operacao; ?>"><img src="../imagens_comuns/voltar.jpg" width="85" height="16" border="0"></a></div>
<p></p>
<div align="center" class="aviso"><?php if ($n_row == '0') { echo "Nenhuma ocorrência para esta pesquisa.";?></div>
  <div align="center">
</div>
    <?php
}
?>
  </div>
  <p>&nbsp;</p>
</body>
</html>