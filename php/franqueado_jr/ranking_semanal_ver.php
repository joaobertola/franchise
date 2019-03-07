<script src="https://www.webcontrolempresas.com.br/Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<?
require "connect/sessao.php";

function mod($nr1,$nr2){
	$val1 = floor($nr1/$nr2);
	$resto = $nr1 -($val1*$nr2);
	return $val1.'-'.$resto;
}

$primeiro = $_POST['primeiro'];
$ultimo = $_POST['ultimo'];
$wanted_week = $_POST['wanted__week'];

//conta quantas vendas foram realizadas no periodo da franquia MASTER
$query = "select count(*) from cadastro where dt_cad between '$primeiro' and '$ultimo' and id_franquia='$id_franquia_master'";
$query = mysql_query($query,$con);
$query = mysql_fetch_array($query);
$total = $query[0];
//caso n�o tiver vendas aparece um alerta e volta � pagina anterior
if (!$total) {
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr height=\"20\">
			<td align=\"center\" width=\"100%\" class=\"titulo\">Sem vendas registradas neste periodo</td></tr></table>";
} else {

//faz o ranking de venda de acordo ao numero de vendas do periodo
$command = "select count(*) qtd, a.id_franquia, b.fantasia, b.foto, b.estrela, a.dt_cad, a.id_franquia_jr from cadastro a
			inner join franquia b on a.id_franquia=b.id
			where a.dt_cad between '$primeiro' and '$ultimo' and a.id_franquia='$id_franquia_master'
			group by a.id_franquia order by qtd desc, a.dt_cad";

$res = mysql_query($command,$con);
$linhas = mysql_num_rows($res);
$linhas1 = $linhas+3;

//come�a a tabela
	echo "<div class=\"titulo\">RANKING SEMANAL DE VENDAS ( Franqueado JUNIOR )</div><br>";
?>
        <fieldset class="normal" style=" background:url(http://i.s8.com.br/images/i/bg_content.jpg); border-width:6px">
        <legend style="color:#FF6600; font-weight:bold; background-color:#FFFFFF">Comunicado para toda a rede de franquias</legend>
        <blockquote>
        <p>Cada <img src="../img/estrela.gif" alt="estrela" width="30" height="32" /> representa 1 (uma) vit&oacute;ria entre os 10 primeiros colocados<br />
                	Cada <img src="../img/diamante.gif" alt="diamante" width="40" height="30" />representa 5 (cinco) vit&oacute;rias entre os 10 primeiros colocados</p>
                	<p>Cada vit&oacute;ria representa <strong>compet&ecirc;ncia</strong>, <strong>garra</strong>, muito <strong>trabalho</strong>, montagem de <strong>equipe</strong> de vendas e vontade de <strong>mudar</strong> de vida, vontade de <strong>conquisar</strong> valores e patrim&ocirc;nio.</p>
                	<p>E voc&ecirc;? Est&aacute; esperando o que para entrar nesta festa? Mude sua vida! Ela passa r&aacute;pido, <font color="#FF6600"><b>conquiste seu peda&ccedil;o de sucesso!</b></font></p>
                	<p>Fa&ccedil;a como os campe&otilde;es abaixo! Viva o trabalho! Viva os resultados!</p>
		</blockquote>
        </fieldset>
        <br />
		<table align="center" width="650" border="0" cellpadding="0" cellspacing="0" class="quente">
	 		<tr>
			  <td colspan="7" height="1" bgcolor="#999999"></td>
			</tr>
<tr height="20" class="titulo">
  <td align="center">Posi&ccedil;&atilde;o</td>
				<td align="center">Franqueado JUNIOR</td>
				<td align="center">Quantidade</td>
				<td></td>
</tr>
			<tr>
			  <td colspan="5" height="1" bgcolor="#666666">
			  </td>
			</tr>
<?
	$b = 1;
	for ($a=1; $a<=$linhas; $a++) {
	  	//caso for igual a 0
	  	$matriz = mysql_fetch_array($res);
		if ($matriz['id_franquia'] != 0) 
			$franqueado = $matriz['id_franquia'];
		else
			$franqueado = -1;
		//pega quantidade, nome de fantasia e foto
		$id = $matriz['id_franquia'];
		$qtd = $matriz['qtd'];
		# $nome_franquia	= $matriz['fantasia'];
		$foto	= $matriz['foto'];
		$estrela = $matriz['estrela'];
		
		$id_franquia_jr = $matriz['id_franquia_jr'];
		$query = "select fantasia from franquia where id = $id_franquia";
		$query = mysql_query($query,$con);
		$query = mysql_fetch_array($query);
		$nome_franquia	= $query[0];
		
		//
		echo "<tr ";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
		if (($b == 1) && ($id <> 1)) {
?>
<td>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','1 lugar','src','../img/1lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/1lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="1 lugar">
    <param name="movie" value="../img/1lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/1lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object>
</noscript>
</td>
<?
		$b = $b +1;
		} elseif (($b == 2) && ($id <>1)) {
?>
<td>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','2 lugar','src','../img/2lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/2lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="2 lugar">
  <param name="movie" value="../img/2lugar.swf" />
  <param name="quality" value="high" />
  <embed src="../img/2lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
</object>
</noscript>
</td>
<?
		$b = $b +1;
		} elseif (($b == 3) && ($id <> 1)) {
?>
<td>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','3 lugar','src','../img/3lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/3lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="3 lugar">
  <param name="movie" value="../img/3lugar.swf" />
  <param name="quality" value="high" />
  <embed src="../img/3lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
</object>
</noscript>
</td>
<?
		$b = $b +1;
        } elseif (($b == 4) && ($id <> 1)) {
?>
<td>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','4 lugar','src','../img/4lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/4lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="4 lugar">
  <param name="movie" value="../img/4lugar.swf" />
  <param name="quality" value="high" />
  <embed src="../img/4lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
</object>
</noscript>
</td>
<?
		$b = $b +1;
		} elseif (($b == 5) && ($id <> 1)) {
?>
<td>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','5 lugar','src','../img/5lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/5lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="5 lugar">
  <param name="movie" value="../img/5lugar.swf" />
  <param name="quality" value="high" />
  <embed src="../img/5lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
</object>
</noscript>
</td>
<?
		$b = $b +1;
		} elseif (($b == 6) && ($id <> 1)){
?>
<td>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','6 lugar','src','../img/6lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/6lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="6 lugar">
  <param name="movie" value="../img/6lugar.swf" />
  <param name="quality" value="high" />
  <embed src="../img/6lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
</object>
</noscript>
</td>
<?
		$b = $b +1;
		} elseif (($b == 7) && ($id <> 1)) {
?>
<td>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','7 lugar','src','../img/7lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/7lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="7 lugar">
  <param name="movie" value="../img/7lugar.swf" />
  <param name="quality" value="high" />
  <embed src="../img/7lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
</object>
</noscript>
</td>
<?
		$b = $b +1;
		} elseif (($b == 8) && ($id <> 1)) {
?>
<td>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','8 lugar','src','../img/8lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/8lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="8 lugar">
  <param name="movie" value="../img/8lugar.swf" />
  <param name="quality" value="high" />
  <embed src="../img/8lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
</object></noscript></td>
<?
		$b = $b +1;
		} elseif (($b == 9) && ($id <> 1)) {
?>
<td>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','9 lugar','src','../img/9lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/9lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="9 lugar">
    <param name="movie" value="../img/9lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/9lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object></noscript></td>
<?
		$b = $b +1;
		} elseif (($b == 10) && ($id <> 1)) {
?>
<td>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','10 lugar','src','../img/10lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/10lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="10 lugar">
    <param name="movie" value="../img/10lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/10lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object></noscript></td>
<?
		$b = $b +1;
		} else { echo "<td align=\"center\">$a &ordm;</td>";}
		echo "	  <td align=\"center\">";
		if (!empty($estrela)) {
			$resto = mod($estrela,5);

			$array = explode('-',$resto);
			
			$diamante = $array[0];
			$star = $array[1];
			
			for($i=0;$i<$diamante;$i++) {
				echo "<img src=\"../img/diamante.gif\">";
			}
			for($j=1;$j<=$star;$j++){
					echo "<img src=\"../img/estrela.gif\">";
			}

			echo "<br>";
		}
		# link para mostrar a foto, desde que esteja cadastrado no banco de dados
		
		# <td align=\"center\"><img src='ranking/d_gera.php?id=".$id."'></td>
		
		echo "$nome_franquia</td>
			  <td align=\"center\"><font color=\"#006666\">&nbsp;$qtd</font></font></td>
		 	  
			  <td></td>";
	  	 echo "</tr>";
	} //fim do for
		echo "<tr>
				<td colspan=\"4\" align=\"right\" class=\"titulo\">
					<b>Total de vendas do periodo entre $primeiro e $ultimo: <font color=\"#ff6600\">$total</font></b>
				</td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<script src="../../../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>

<p></p>
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>