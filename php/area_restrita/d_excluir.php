<?php
// require_once('../connect/sessao.php');
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
?>
<form name="AltCliente" method="POST" action="painel.php?pagina1=area_restrita/d_excluir1.php">

<table width=50% align="center">
  <tr>
    <td colspan="2" align="center" class="titulo">INFORME O C&Oacute;DIGO DO CLIENTE </td>
  </tr>
  <tr>
    <td width="173" class="subtitulodireita">&nbsp;</td>
    <td width="224" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo do cliente</td>
    <td class="campoesquerda">
       <input type="text" name="codigo" id="codigo" size="10" maxlength="6" />    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr align="right">
    <td colspan="2"><input type="submit" value=" Excluir Cliente " name="enviar" /></td>
  </tr>
</table>
</form>
