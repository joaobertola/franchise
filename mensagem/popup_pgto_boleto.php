<link rel="stylesheet" href="../../web_control/css/popup.css" type="text/css">
<style type="text/css" media="print">
.noprint {
	display:none;
}
</style>

<script language="javascript">
function popup(p_num_doc) {
	window.close();
	window.open('http://www.informsystem.com.br/inform/boleto/boleto.php?numdoc='+p_num_doc, 'orcamento', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+780+',height='+550+',left='+0+', top='+0+',screenX='+0+',screenY='+0+'');  
}
</script>
<table border="0" width="450px" align="center" cellpadding="0" cellspacing="2" style="border: 1px solid #F5F5F5; background-color:#FFFFFF">

<tr>
  <td height="30" colspan="2" bgcolor="#FF9900" class="topo" align="center"><b>PAGAMENTO DE BOLETOS</b></td></tr>
<tr><td colspan="2" bgcolor="#FFFFFF">&nbsp;</td></tr>	
<tr>
	<td colspan="2" class="coluna_1">
    <div align="justify">
    <p>Em virtude da greve dos bancos em algumas cidades, comunicamos em caracter de URGÊNCIA que nossos BOLETOS poder&atilde;o ser pagos nos seguintes estabelecimentos: </p>
    
    <p><font color="#0000FF"><b>1 - TERMINAIS ELETR&Ocirc;NICOS (AUTO-ATENDIMENTO)</b></font><br />
      <font color="#0000FF"><b>2 - CORREIOS</b></font><br />
      <font color="#0000FF"><b>3 - LOT&Eacute;RICAS</b></font><br />
      <font color="#0000FF"><b>4 - INTERNET</b></font></p>
      <font color="#FF0000">
    <div align="center">
    Imprima os <b>BOLETOS PENDENTES<b> abaixo e<br>
    pague em qualquer estabelecimento.
    </div>
    </font>    
    <p>
    <?
		$codloja = $_GET['codloja'];
		
		$usermy="csinform";
		$passwordmy="inform4416#scf";
		$nomedb="consulta";
		$conexao=@mysql_pconnect("10.2.2.3",$usermy,$passwordmy)or die ("Prezado Cliente, <br><br> Estamos em manutenção em Nosso Banco de Dados, dentro de instantes a conexão será estabelecida novamente. <br><br>Atenciosamente, <br><br>Departamento de TI.");
		$bd=mysql_select_db($nomedb,$conexao) or die("Nao foi posivel selecionar o banco de dados contate o administrador erro 30");
		
		$sql="	SELECT vencimento, valor, numdoc 
				FROM cs2.titulos 
				WHERE codloja=$codloja AND datapg IS NULL
				ORDER BY vencimento ASC";		
		$qr = mysql_query($sql,$conexao) or die ("Erro SQL:  $sql");
		$nreg = mysql_num_rows($qr);
		
		for($i=0;$i<$nreg;$i++){
			$numdoc =  mysql_result($qr,$i,"numdoc");
			$mes_ano =  mysql_result($qr,$i,"vencimento");
			$mes_ano =  substr($mes_ano,8,2)."/".substr($mes_ano,5,2)."/".substr($mes_ano,0,4);
			//if ($i == 0){
				echo "<table border='0' align='center'><tr><td>";
				echo "<font color='#0000FF'>Vencimento: </font><a href='#' onClick='popup($numdoc)'>$mes_ano</a></font>&nbsp;<font style='font-size:12px'>(Clique e Imprima)</font><br>";
				echo "</td></tr></table>";
				//echo "<a href='http://www.informsystem.com.br/inform/boleto/boleto.php?&numdoc=$numdoc'>$mes_ano</a> ";
			//}
		}
	?>
    </p>
    </div>
    
    </td>
  </tr>
  
  <tr><td colspan="2">&nbsp;</td></tr>
    
  <tr>
	<td colspan="2" class="coluna_1">	
    <p>WEBCONTROL EMPRESAS<br />
    Departamento Financeiro	  
    </td></tr>   
</table>

<p>
<table border="0" width="450px" align="center" cellpadding="0" cellspacing="3">
<tr class="noprint">
    <td align="left"><a href="#" class="link" onClick="window.close()">Fechar</a></td>
</tr> 
</table>

