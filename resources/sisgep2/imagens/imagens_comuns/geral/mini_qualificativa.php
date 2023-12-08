<?php session_start();
@$_SESSION["url_chamada"] = @$_SERVER['PHP_SELF'];
//
// Sisgep ® - Versão 2.0 30/03/2005 SDR-Systems - dg
// Modulo >--->  Mini Ficha Qualificativa - gaiola
//
if (@$_SESSION["log_ok"] <> "ok") {
	@$_SESSION["url_chamada"] = @$_SERVER['PHP_SELF'];
	header("Location:/login.php");
	}

?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Sisgep - Ficha Qualificativa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/style.css" type="text/css">
</head>
<body>
<?php //include("../header.php"); ?>
<?php include("../includes/conecta.php"); ?>
<?php $matric = $_GET["matric"]; ?>
<?php
		//echo $matric;
		$sql = "select * from cadastros where Matric_Cad = '" . $matric . "'";
		$rs = mysql_query($sql) or die(mysql_error());
		$n_row = mysql_num_rows($rs);
		$row = mysql_fetch_array($rs);
		$Matric = substr($matric,0,6) . "-" . substr($matric,6,1);
		$caminho = "../../fotos_sentenciados/";
		$foto = $caminho . $Matric . ".jpg";
$nascimento = substr($row[7],8,2) . "/" . substr($row[7],5,2) . "/" . substr($row[7],0,4);

if ($row[29] == "C") {
$status_casa = "NA UNIDADE";
$style = "status_casa";
};
if ($row[29] == "T") {
$status_casa = "EM TRÂNSITO";
$style = "status_transito";
};
if ($row[29] == "X") {
$status_casa = "EXCLUÍDO";
$style = "status_excluido";
};
//seleciona movimentações
$sql_mov = "select * from mov_sent where Matric_Mov = '" . $matric . "' order by Cod_id desc" ;
$rs_mov = mysql_query($sql_mov) or die(mysql_error());

//seleciona cela e pavilhão
$sql_cela = "select * from celas where Matric_Cel = '" . $matric . "' and Dl_Cel = '+'" ;
$rs_cela = mysql_query($sql_cela) or die(mysql_error());
$row_cela = mysql_fetch_array($rs_cela);
$dt_in_cela = substr($row_cela[5],8,2) . "/" . substr($row_cela[5],5,2) . "/" . substr($row_cela[5],0,4);

//seleciona tabela artigo simples
$sql_artsimp = "select * from artigos_simples where Matric_Art = $matric";
$rs_artsimp = mysql_query($sql_artsimp) or die (mysql_error());
$row_art = mysql_fetch_array($rs_artsimp);

?>
<div align="center"><img src="../imagens_part/barra_pjbas.jpg" width="620" height="70"></div>
<p></p>
<p></p>
<table width="600" border="0" align="center" class="titulo_tabela">
  <tr>
    <td width="590" height="34" bgcolor="#0000FF"><div align="center">Ficha Qualificativa</div></td>
  </tr>
</table>
<p></p>
<p></p>
<table width="600" border="0" align="center" class="nome_campo">
  <tr>
    <td width="85" bgcolor="#d1d2fe">Matr&iacute;cula</td>
    <td colspan="4" bgcolor="#d1d2fe">Nome</td>
    <td width="127" rowspan="8"><div align="center"><a href="../foto.php?caminho=../fotos_sentenciados/&matric=<?php echo $matric ;?>&nome=<?php echo $row[2];?>"><img src="<?php echo $foto; ?>" width="125" height="171" border="0" align="baseline"></a><a href="../foto.php?caminho=../fotos_sentenciados/&matric=<?php echo $matric ;?>&nome=<?php echo $row[2];?>"></a></div></td>
  </tr>
  <tr class="texto_campo">
    <td height="20" bgcolor="#e5e5fe"><div align="center"><?php echo $Matric; ?></div></td>
    <td colspan="4" bgcolor="#e5e5fe"><div align="left"><?php echo $row[2]; ?></div></td>
  </tr>
  <tr>
    <td height="17" bgcolor="#d1d2fe"><div align="center">Regime</div></td>
    <td width="70" bgcolor="#d1d2fe"><div align="center">Execu&ccedil;&atilde;o</div></td>
    <td width="68" bgcolor="#d1d2fe"><div align="center">Pavilh&atilde;o</div></td>
    <td width="69" bgcolor="#d1d2fe"><div align="center">Cela</div></td>
    <td width="155" bgcolor="#d1d2fe"><div align="center">Entrada na Cela</div></td>
  </tr>
  <tr class="texto_campo">
    <td height="20" bgcolor="#e5e5fe"><div align="center"><?php echo $row[1]; ?></div></td>
    <td bgcolor="#e5e5fe"><div align="center"><?php echo $row[3]; ?></div></td>
    <td bgcolor="#e5e5fe"><div align="center"><?php echo $row_cela[3]; ?></div></td>
    <td bgcolor="#e5e5fe"><div align="center"><?php echo $row_cela[2]; ?></div></td>
    <td bgcolor="#e5e5fe"><div align="center"><?php echo $dt_in_cela; ?></div></td>
  </tr>
  <tr>
    <td height="12" colspan="2" bgcolor="#d1d2fe"><div align="center">Data de Nascimento</div></td>
    <td height="12" colspan="2" bgcolor="#d1d2fe"><div align="center">RG</div></td>
    <td height="12" bgcolor="#d1d2fe"><div align="center">Status</div></td>
  </tr>
  <tr class="texto_campo">
    <td height="22" colspan="2" bgcolor="#e5e5fe"><div align="center"><?php echo $nascimento; ?></div></td>
    <td height="22" colspan="2" bgcolor="#e5e5fe"><div align="center"><?php echo $row[4]; ?></div></td>
    <td height="22" bgcolor="#e5e5fe"><div align="center"><span class="<?php echo $style;  ?>"><?php echo $status_casa; ?></span></div></td>
  </tr>
  <tr>
    <td height="18" colspan="3" bgcolor="#d1d2fe"><div align="center">Artigos</div></td>
    <td height="18" colspan="2" bgcolor="#d1d2fe"><div align="center">Pena</div></td>
  </tr>
  <tr class="texto_campo">
    <td height="20" colspan="3" bgcolor="#e5e5fe"><div align="center"><? echo $row_art[2];?></div></td>
    <td height="20" colspan="2" bgcolor="#e5e5fe"><div align="center"><? echo $row_art[5];?> ANOS, <? echo $row_art[6]; ?> MESES, <? echo $row_art[7]; ?> DIAS </div></td>
  </tr>
  <tr bgcolor="#d1d2fe">
    <td><div align="left">Naturalidade: </div></td>
    <td colspan="5" bgcolor="#e5e5fe"><div align="left" class="texto_campo"><?php echo "$row[5] - $row[6]"; ?></div></td>
  </tr>
  <tr bgcolor="#d1d2fe">
    <td><div align="center">M&atilde;e</div></td>
    <td colspan="5" bgcolor="#e5e5fe" class="texto_campo"><?php echo $row[12]; ?></td>
  </tr>
  <tr bgcolor="#d1d2fe">
    <td><div align="center">Pai</div></td>
    <td colspan="5" bgcolor="#e5e5fe" class="texto_campo"><?php echo $row[11]; ?></td>
  </tr>
</table>

<table width="600" border="0" align="center">
  <tr bgcolor="#0000FF" class="titulo_tabela">
  <td class="titulo_tabela"><div align="center">Movimenta&ccedil;&atilde;o</div></td>
  </tr>
</table>

<table width="600" border="0" align="center">
  <tr bgcolor="#d1d2fe">
    <td width="174" class="nome_campo"><div align="center">Tipo</div></td>
    <td width="102" class="nome_campo"><div align="center">Data</div></td>
    <td width="310" class="nome_campo"><div align="center">Proced&ecirc;ncia / Destino</div></td>
  </tr>
<?php
			$row_mov = mysql_fetch_array($rs_mov);
//			{
$data_in = substr($row_mov[3],8,2) . "/" . substr($row_mov[3],5,2) . "/" . substr($row_mov[3],0,4);
$data_out = substr($row_mov[4],8,2) . "/" . substr($row_mov[4],5,2) . "/" . substr($row_mov[4],0,4);

//seleciona tabela tipo movimentacoes
$sql_tp_mov = "select * from tab_movimentacoes where Cod_Refer='$row_mov[2]'" ;
$rs_tp_mov = mysql_query($sql_tp_mov) or die(mysql_error());
$row_tp_mov = mysql_fetch_array($rs_tp_mov);
$tipo = $row_tp_mov[2];
?>
  <tr bgcolor="#e5e5fe">
    <td class="texto_campo"> <div align="center"><?php echo $tipo; ?></div></td>
    <td class="texto_campo"><div align="center">
        <?php if ($row_mov[3] <> "") echo $data_in; ?>
        <?php if ($row_mov[4] <> "") echo $data_out; ?>
        </div></td>
    <td class="texto_campo"><div align="center"><?php echo $row_mov[5]; ?></div></td>
  </tr>
<?php // };?>
</table><br>

 
<p align="center">
  <input name="imageField322" type="image" src="../imagens_comuns/voltar.jpg" width="85" height="16" border="0" onClick="document.location.href='pesq_matric_mini.php';">
</span></p>
</body>
</html>