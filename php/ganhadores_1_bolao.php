<?php
function dataMostra($p_paramento){
 	   $dia = substr($p_paramento, 8,2);   
	   $mes = substr($p_paramento, 5,2);   
	   $ano = substr($p_paramento, 0,4);   	   
	   $hora = substr($p_paramento, 11,8);  	   
	   $data_hora_view.=$dia;
	   $data_hora_view.="/";
	   $data_hora_view.=$mes;
	   $data_hora_view.="/";
	   $data_hora_view.=$ano;	   
	   $data_hora_view.=" - ";	   
	   $data_hora_view.=$hora;	   
	   return ($data_hora_view);
} 

require_once("connect/conexao_conecta.php");

$sql_valor = "SELECT
  c.brasil, j.adversario2, c.apostador, c.data_hora_lance, j.bandeira1, j.bandeira2, 
  j.situacao, f.fantasia, j.data_hora_jogo, j.adversario AS nome_adversario
FROM
  copa c INNER JOIN
  jogo j ON c.id_jogo = j.id INNER JOIN
  franquia f ON f.id = c.id_franquia
WHERE
  j.situacao = 'I'
 AND
c.id_jogo = '{$_REQUEST['id_jogo']}'
ORDER BY c.id";
$qry_valor  = mysql_query($sql_valor);
$total = mysql_num_rows($qry_valor);
$acumulado = $total * 10;
$acumulado_mostra =  number_format($total * 10,2,',','.'); 
$nome_adversario = mysql_result($qry_valor,0,'nome_adversario');
$nome_adversario2 = mysql_result($qry_valor,0,'adversario2');
$bandeira1 = mysql_result($qry_valor,0,'bandeira1');
$bandeira2 = mysql_result($qry_valor,0,'bandeira2');

//pega o resultado do jogo
$sql_resultado = "SELECT resultado1, resultado2 FROM jogo
				  WHERE situacao = 'I' AND id = '{$_REQUEST['id_jogo']}'";
$qry_resultado  = mysql_query($sql_resultado);
$resultado1 = mysql_result($qry_resultado,0,'resultado1');
$resultado2 = mysql_result($qry_resultado,0,'resultado2');

$sql = "SELECT
  c.brasil, c.adversario, c.apostador, c.data_hora_lance,
  j.situacao, f.fantasia, j.data_hora_jogo
FROM
  copa c INNER JOIN
  jogo j ON c.id_jogo = j.id INNER JOIN
  franquia f ON f.id = c.id_franquia
WHERE
  j.situacao = 'I'
 AND
 	c.brasil = '$resultado1' 
AND 
	c.adversario = '$resultado2' 
AND 
	c.id_jogo = '{$_REQUEST['id_jogo']}'
ORDER BY c.id";
$qry  = mysql_query($sql);
$qry_tmp  = mysql_query($sql);
$total_ganhadores = mysql_num_rows($qry_tmp);
$premio =  $acumulado / $total_ganhadores;
$premio =  number_format($premio,2,',','.'); 

$a_cor = array('#FFFFFF', '#F6F6F9');
$cont=1;
?>



<table border="0" width="95%" align="center" cellpadding="0" cellspacing="2" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">

<tr><td width="15%" align="center"><img src="../img/<?=$bandeira1?>" width="100px"></td>
  <td align="center">
  <font style="font-size:20px" color="#0000FF"><b>BOL�O<br>
  MILION�RIO<br>
  WEB CONTROL EMPRESAS<p>
  <?=$nome_adversario2?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$nome_adversario?>
  </b></font><p>.
  </td>

  <td width="15%" align="center"><img src="../img/<?=$bandeira2?>" width="100px"></td>

</tr>
</table>

<p>
<div align="center"><b>Rela��o de Ganhadores <?=$_REQUEST['id_jogo']?>� Bol�o</b></div>
<table border="0" width="95%" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">

<tr bgcolor="#FFCC99">
  <td width="7%" align="center"><img src="../img/<?=$bandeira1?>" width="25px"></td>
  <td width="2%" align="center">&nbsp;</td>
  <td width="7%" align="center"><img src="../img/<?=$bandeira2?>" width="25px"></td>
  <td width="44%" align="left"><b>Nome da Franquia</b></td>
  <td width="20%" align="left"><b>Apostador</b></td>
  <td width="20%" align="center"><b>Data do Lance</b></td>
</tr>

<?php 
while($rs = mysql_fetch_array($qry)){
$cont++;
?>
<tr bgcolor="<?=$a_cor[$cont % 2]?>">
  <td align="center"><?=$rs['brasil']?></td>
  <td align="center"><b>X</b></td>
  <td align="center"><?=$rs['adversario']?></td>
  <td align="left"><?=$rs['fantasia']?></td>
  <td align="left"><?=strtoupper($rs['apostador'])?>&nbsp;<?=$traira?></td>
  <td align="center"><?=dataMostra($rs['data_hora_lance'])?></td>
</tr>
<?php } ?>
</table>

<table border="0" width="95%" align="center" cellpadding="0" cellspacing="1">

<tr><td colspan="3" align="center"><font style="font-size:18px" color="#FF0000"><b>Total PR�MIO POR GANHADOR R$ <?=$premio?></b></font></td></tr>

<tr><td colspan="3" align="center"><font style="font-size:18px" color="#FF0000"><b>Total ACUMULADO R$ <?=$acumulado_mostra?></b></font></td></tr>

</table>