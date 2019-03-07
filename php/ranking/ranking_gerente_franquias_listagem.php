<?php
require "connect/sessao.php";

$mes = $_REQUEST['mes'];
$ano = $_REQUEST['ano'];

$sql = "SELECT
		  COUNT(f.id_gerente) AS total, f.id_gerente, g.nome
		FROM
		  franquia f 
		INNER JOIN cadastro c ON c.id_franquia = f.id 
		INNER JOIN gerente g ON f.id_gerente = g.id		  
		AND c.dt_cad LIKE '$ano-$mes%'
		GROUP BY
		  f.id_gerente, g.nome
		ORDER BY total DESC";
$qry = mysql_query($sql,$con);		
?>

<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" class="quente">
<tr>
    <td colspan="4" class="titulo">RANKING GERENTE DE FRANQUIAS</td>
</tr>

<tr>
	<td colspan="4" bgcolor="#999999"></td>
</tr>

<tr>
	<td bgcolor="#999999"></td>
</tr>

<tr height="20"  bgcolor="#87b5ff" align="center">
    <td width="20%"><b>Posi&ccedil;&atilde;o</b></td>
    <td width="30%"><b>Gerente</b></td>
    <td width="20%"><b>Quantidade</b></td>
    <td width="30%"><b>Foto</b></td>
</tr>

<tr>
	<td colspan="4" bgcolor="#666666"></td>
</tr>
<?php 
$i=0;
while($rs = mysql_fetch_array($qry)) { 
$i++;
if($i%2 == 0)$cor = "#E5E5E5";	
else $cor = "#FFFFFF";	
?>
	
<tr height="30" align="center" bgcolor="<?=$cor?>">
    <td>
    	<?php if($i == "1"){?><img src="../img/ouro.jpg"><?php } ?>
        <?php if($i == "2"){?><img src="../img/prata.jpg"><?php } ?>
        <?php if($i == "3"){?><img src="../img/bronze.jpg"><?php } ?>
        <?php if($i == "4"){ echo $i."&ordm;"; } ?>
        <?php if($i == "5"){ echo $i."&ordm;"; } ?>
    </td>
    <td><font color="#006666" size="3"><b><?=$rs['nome']?></b></font></td>
    <td><?=$rs['total']?></td>
    <td>&nbsp;</td>
</tr>
	
<?php } ?>
</table>	
<?php $res = mysql_close ($con); ?>
<p>
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
