<?php
//require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if ($name==""){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

include "d_relfranqueados1.php";
?>
</div>
<div align="right">
</div>
<table width="100%" align="center">
  <form method="post" action="painel.php?pagina1=area_restrita/d_cadfranqueado.php">
  <tr align="right">
    <td><input type="submit" name="Submit" value="Incluir Franqueado"></td>
  </tr>
  </form>
  <tr align="right">
    <td><input name="button" type="button" onClick="javascript: history.back();" value="                         Voltar" /></td>
  </tr>
</table>