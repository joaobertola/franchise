<?php
require "connect/sessao.php";

$codloja  = $_GET['codloja'];
$razaosoc = $_GET['razaosoc'];
$logon 	  = $_GET['logon'];

$resulta = mysql_query("select a.codloja, mid(c.logon,1,5) as logon, b.razaosoc, b.nomefantasia,
						DATE_FORMAT(a.dt_chegada_procuracao,'%d/%m/%Y') as chegada, a.autorizado_serasa, 
						date_format(a.dt_resposta_serasa,'%d/%m/%Y') as resposta from movimento_serasa a
						inner join cadastro b on a.codloja=b.codloja
						inner join logon c on a.codloja=c.codloja
						WHERE a.codloja=$codloja limit 1", $con);
$linha = mysql_num_rows($resulta);
if ($linha == 0)
{
	echo "<script>alert(\"Este cliente ainda não enviou o Termo de Negativação Equifax !\"); javascript: history.back();</script>";
}
else 
{
$matriz = mysql_fetch_array($resulta); 
?>
<script language="javascript">
function printEquifax(){
  cupom  = open('clientes/termo_captacao_equifax.php?codloja=<?=$codloja?>', 'historico', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+850+',height='+500+',left='+0+', top='+0+',screenX='+0+',screenY='+0+'');
}
</script>
<table border="0" align="center" width="643">
  <tr>
    <td colspan="2" class="titulo" align="center">PROCURA&Ccedil;&Atilde;O EQUIFAX</td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" class="subtitulodireita">ID</td>
    <td width="50%" class="subtitulopequeno">
    <?php echo $matriz['codloja']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo de Cliente </td>
    <td class="subtitulopequeno">
      <?php echo $matriz['logon']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nome Fantasia</td>
    <td class="subtitulopequeno"><?php echo $matriz['nomefantasia']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data de Chegada da Procura&ccedil;&atilde;o </td>
    <td class="subtitulopequeno"><?php echo $matriz['chegada']; ?></td>
  </tr>
  <!--<tr>
    <td class="subtitulodireita">Enviado SERASA</td>
    <td class="formulario" <?php /*if ($matriz['autorizado_serasa'] == "AUTORIZADO") {
		echo "bgcolor=\"#33CC66\"";}
		else {
		echo "bgcolor=\"#FF0000\"";} */
		?>><font color="#FFFFFF"><?php //echo $matriz['autorizado_serasa']; ?></font></td>
  </tr>-->
  <!--tr>
    <td class="subtitulodireita">Data da Envio para EQUIFAX </td>
    <td class="subtitulopequeno">< ?php echo $matriz['resposta']; ?></td>
  </tr -->
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>

  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    	<input type="button" onClick="javascript: history.back();" value="       Voltar       " />
        &nbsp; &nbsp; &nbsp;
        <input type="button" onClick="printEquifax();" value="       Gerar Captação Equifax       " />
   </td>
  </tr>
</table>
<?php
  $res = mysql_close ($con);
}  
?>