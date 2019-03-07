<?php
//require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

$id = $_GET['id'];

//apresenta s� o franqueado selecionado
$comando = "SELECT * FROM franquia WHERE id='$id'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res); 
$franqueado	= $matriz['id'];
$franquia	= $matriz['fantasia'];
$data = date('Y-m-d H:i:s');
$dia  = date('d/m/Y H:i');
$res = mysql_close ($con);
?>
<body>
<form method="post" action="area_restrita/d_ctaincluir_final.php">

<table width=560 border="0" align="center">
  <tr class="titulo">
    <td colspan="2">CONTA CORRENTE DA <?php echo $franquia; ?>
      <input type="hidden" name="franqueado" value="<?php echo $franqueado; ?>"></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data</td>
    <td class="campoesquerda"><?php echo $dia; ?>
    <input type="hidden" name="data" value="<?php echo $data; ?>"></td>
  </tr>
    <td class="subtitulodireita">Opera&ccedil;&atilde;o</td>
      <td class="campoesquerda">
        <input name="operacao" type="radio" value="0" checked>
      Cr&eacute;dito<br>
      <input name="operacao" type="radio" value="1">
      D&eacute;bito</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Discrimina&ccedil;&atilde;o</td>
    <td class="campoesquerda"><input type="text" name="discriminacao" maxlength="50" size="50"></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Valor</td>
    <td class="campoesquerda"><input type="text" name="valor" onKeydown="FormataValor(this,20,event,2)" style="text-align:right" />    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
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
</form>
</body>