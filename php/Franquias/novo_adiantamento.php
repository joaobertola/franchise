<script language="javascript">
jQuery(function($){
   $("#data").mask("99/99/9999");     
});

(function($){
$(
	function(){
		$('input:text').setMask();
	}
);
})(jQuery);
</script>

<?php
require_once('../connect/sessao.php');
//
//session_start();
//
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

$id_func = $_GET['id_func'];

//apresenta s� o franqueado selecionado
$comando = "SELECT nome FROM funcionario WHERE id = '$id_func'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res); 
$nome	= $matriz['nome'];
//$res = mysql_close ($con);
?>
<body>
<form method="post" action="Franquias/novo_adiantamento_grava.php">

<table width=560 border="0" align="center">
  <tr class="titulo">
    <td colspan="2">LAN&Ccedil;AMENTO DE ADIANTAMENTO<br><font color="#FF0000"><?=$nome?></font>
      <input type="hidden" name="id_func" value="<?php echo $id_func; ?>"></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data</td>
    <td class="campoesquerda">
    	<input type="text" name="data" id="data" maxlength="10" size="15">
     </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Descri&ccedil;&atilde;o</td>
    <td class="campoesquerda"><input type="text" name="descricao" maxlength="50" size="70"></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Valor</td>
    <td class="campoesquerda"><input type="text" name="valor" onKeydown="FormataValor(this,20,event,2)" style="text-align:right" />    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><input type="submit" name="incluir" id="incluir" value="    Incluir    " />
        <input name="button" type="button" onClick="javascript: history.back();" value="      Voltar      " /></td>
  </tr>
</table>

<table width=560 border="0" align="center">
	<tr class="subtitulocentro">
		<td colspan="3">Hist&oacute;rico de Adiantamentos</td>
	</tr>
<?php
  	$sql = "SELECT date_format(data,'%d/%m/%Y') as data, valor, descricao FROM cs2.adiantamento_funcionario
			WHERE id_func = $id_func";
	$qry = mysql_query($sql,$con) or die("ERRO SQL: $sql");
	if ( mysql_num_rows($qry) > 0 ){
			echo "<tr class='titulo'>
		    	<td align='center' width='20%'>Data</td>
		        <td align='center' width='20%'>Valor</td>
	    	    <td width='60%'>Descri&ccedil;&atilde;o</td>
		    </tr>";
		$cont = 0;
		while ( $reg = mysql_fetch_array( $qry ) ){
			$cont++;
			$a = $cont % 2;
			
			if ( $a == 0)
				$color = "#FFFFFF";
			else
				$color = "#CCCCCC";
				
			$data      = $reg['data'];
			$valor     = number_format($reg['valor'],2,',', '.');
			$descricao = $reg['descricao'];
			echo "
			<tr style='background-color:$color'>
		    	<td align='center' style='font-size:12px'>$data</td>
		        <td align='center' style='font-size:12px'>$valor</td>
	    	    <td style='font-size:12px'>$descricao</td>
		    </tr>
			";		
		}
	}
	?>

    
</table>
</form>
</body>