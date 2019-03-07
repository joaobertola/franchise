<?php
require "connect/sessao_r.php";

$sql_data = "SELECT 
date_format(data_hora_jogo,'%Y%m%d') as data_hora_jogo,
date_format(data_hora_jogo,'%d/%m/%Y') as data_hora_jogo_mostra
FROM cs2.jogo WHERE situacao = 'A'";
$res_data = mysql_query ($sql_data, $con);		
$data_final  = mysql_result($res_data,0,'data_hora_jogo');
$data_hora_jogo_mostra  = mysql_result($res_data,0,'data_hora_jogo_mostra');

$data_atual  = date("Ymd"); //2010 - 06 - 20

//A Data Informada é menor que a Data Atual por Gentileza Informe Outra

/*echo "data atual = > ".$data_atual;
echo "<br>data_final => ".$data_final;

$data_atual = '20100625';

echo "<p>data atual => ".$data_atual;
echo "<br>data_final => ".$data_final;

*/
if ($data_atual >= $data_final)
{
	echo "<div align='center'><h1><font color='red'>Apostas Encerradas ! </font></h1></div>";
	exit;	 
}


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

//seleciona o jogo ativo
$sql_jogo = "SELECT * FROM cs2.jogo WHERE situacao = 'A'";
$res = mysql_query ($sql_jogo, $con);		
$adversario      = mysql_result($res,0,'adversario');
$id_jogo        = mysql_result($res,0,'id');
$data_hora_jogo = dataMostra(mysql_result($res,0,'data_hora_jogo'));

//seleciona as jogadas
$sql_jogadas = "
SELECT
  c.id, c.brasil, c.adversario, c.apostador, c.data_hora_lance,
  j.situacao, c.id_franquia, j.data_hora_jogo
FROM
  copa c 
INNER JOIN jogo j ON c.id_jogo = j.id 
INNER JOIN franquia f ON f.id = c.id_franquia
WHERE
  j.situacao    = 'A' 
AND
  c.id_franquia = '{$_SESSION['id']}'
ORDER BY c.id ASC";
$res_jogadas = mysql_query ($sql_jogadas, $con);	
$qry_tmp  = mysql_query($sql_jogadas, $con);
$total_jogadas = mysql_num_rows($res_jogadas);
$acumulado =  number_format($total_jogadas * 10,2,',','.'); 

$data_hora_jogo = dataMostra(mysql_result($qry_tmp,0,'data_hora_jogo'));
?>
<style type"text/css">
h1 {font-size: 140%;}
form {margin: 30px 50px 0;}
form input, select {
	font-family: Arial;
	font-size: 8pt;
}
form input#numero, form input#uf, form input#cep {float: left; width: 75px;}
address {clear: both; padding: 30px 0;}
</style>

<script language="javascript">
function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espaço em branco

function valida(){
	frm = document.form;	
	if(trim(frm.apostador.value) == ""){
		alert("Falta informar o Apostador !");
		frm.apostador.focus();
		return false;
	}
	confirmation();
}

function confirmation() {
	var answer = confirm("Confirma a Aposta ?")
	if (answer){
		confirma();
	}
}

function confirma(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=Franquias/bolao_copa_do_mundo_bd.php';
	frm.submit();
 } 

</script>
<form name="form" method="post" action="#">
<input type="hidden" name="id_jogo" value="<?=$id_jogo?>">

<table border="0" width="600" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">

  <tr>
    <td colspan="2" class="titulo" align="center"><font color="#FF0000">As apostas só poderão ser realizadas até às 23:59 Hs do dia anterior ao jogo. </b></td>
  </tr>

  <tr>
    <td colspan="2" class="titulo" align="center">Bolão Jogo dia <?=$data_hora_jogo_mostra?> da Copa do Mundo</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita" width="30%"><b>Nome do Apostador</b></td>
    <td class="subtitulopequeno" width="70%"><input name="apostador" type="text" style="width:99%" maxlength="40" onfocus="this.className='boxover'" onblur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  
  <tr><td colspan="2">&nbsp;</td></tr>
  
</table>

<table border="0" width="600" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
  <tr align="center">
    <td class="titulo" width="42%">Espanha</td>
    <td class="titulo" width="16%"><b>X</b></td>
    <td class="titulo" width="42%"><?=$adversario?></td>
  </tr>
  
  <tr align="center" bgcolor="#F6F6F9">
    <td>
    <select name="brasil" style="width:30%">
    <?php for($b=0; $b<=10; $b++){?>
    	<option value="<?=$b?>"><?=$b?></option>
    <?php } ?>    
    </select>
    </td>
    <td><b>X</b></td>
    <td>
    <select name="adversario" style="width:30%">
    <?php for($a=0; $a<=10; $a++){?>
    	<option value="<?=$a?>"><?=$a?></option>
    <?php } ?>    
    </select>
    </td>
  </tr>
  <tr>
  <td colspan="3" align="center"><input type="button" value="Confirma a Jogada" onclick="valida()"></td>
  </tr>
 
</table>
</form>

<?php if($total_jogadas > 0){?>
<p>
<table border="0" width="600" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
<tr bgcolor="#FFCC99">
  <td width="15%" align="center"><b>Nº da Aposta</b></td>
  <td width="9%" align="center"><img src="../img/espanha.jpg" width="25px"></td>
  <td width="2%" align="center">&nbsp;</td>
  <td width="9%" align="center"><img src="../img/4.jpg" width="25px"></td>
  <td width="40%" align="left"><b>Apostador</b></td>
  <td width="25%" align="center"><b>Data do Lance</b></td>
</tr>

<?php 
$a_cor = array('#FFFFFF', '#F6F6F9');
$cont=1;
while($rs = mysql_fetch_array($res_jogadas)){
$cont++;
if($rs['adversario'] > $rs['brasil']){
	$traira = " :-( = Traíra = )-:";
?>
<tr bgcolor="#FF6347">
<?
}else{
	$traira = "";
?>
<tr bgcolor="<?=$a_cor[$cont % 2]?>">
<?php
}
?>
  <td align="center">000000<?=$rs['id']?></td>
  <td align="center"><?=$rs['brasil']?></td>
  <td align="center"><b>X</b></td>
  <td align="center"><?=$rs['adversario']?></td>
  <td align="left"><?=$rs['apostador']?>&nbsp;<?=$traira?></td>
  <td align="center"><?=dataMostra($rs['data_hora_lance'])?></td>
</tr>
<?php } ?>
</table>

<table border="0" width="600" align="center" cellpadding="0" cellspacing="1">

<tr><td colspan="6" align="center"><font style="font-size:18px" color="#FF0000"><b>Total APOSTADO R$ <?=$acumulado?></b></font></td></tr>
</table>

<?php } ?>