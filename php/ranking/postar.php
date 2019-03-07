<?php
require "connect/sessao.php";
include "ocorrencias/config.php";
?>
<html>
<head>
<title>$titulo</title>
</head>
<body>
<form name="mural" action="ranking/inserir.php" method="post">
<table width="90%" border="0" align="center">
<tr>
  <td colspan="2" class="titulo">..:: Mandar uma mensagem ::..</td>
</tr>
<tr>
  <td colspan="2" class="menu">(*) preenchimento obrigatório</td>
  </tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class="subtitulodireita">De:</td>
  <td class="subtitulopequeno"><input name="de" type="text" size="40" maxlength="30">
    (*)</td>
</tr>
<tr>
  <td class="subtitulodireita">E-mail da franquia:</td>
  <td class="subtitulopequeno"><input name="email" type="text" size="40" maxlength="40"></td>
</tr>
<tr>
  <td class="subtitulodireita">End. messenger :</td>
  <td class="subtitulopequeno"><input name="msn" type="text" size="40" maxlength="30" ></td>
</tr>
<tr>
  <td class="subtitulodireita">Para:</td>
  <td class="subtitulopequeno"><input name="para" type="text" size="40" maxlength="30">
  (*)</td>
</tr>


<tr>
  <td rowspan="2" valign="center" class="subtitulodireita">Descri&ccedil;&atilde;o:</td>
  <td class="subtitulopequeno"><?
  if ($smyles == 1){
echo "<script language=\"JavaScript1.2\" src=\"../js/ubbc.js\" type=\"text/javascript\"></script>
<font face=\"Verdana\" color=\"#000000\" size=\"1\"><b>Smyles :</b></font>
<script language=\"JavaScript1.2\" type=\"text/javascript\">
<!--
if((navigator.appName == \"Netscape\" && navigator.appVersion.charAt(0) >= 4) || (navigator.appName == \"Microsoft Internet Explorer\" && navigator.appVersion.charAt(0) >= 4) || (navigator.appName == \"Opera\" && navigator.appVersion.charAt(0) >= 4)) {
document.write(\"<a href=javascript:smiley()><img src='../img/smiley.gif' align='bottom' alt='$texto_14' border='0'></a> \");
document.write(\"<a href=javascript:wink()><img src='../img/wink.gif' align='bottom' alt='$texto_15' border='0'></a> \");
document.write(\"<a href=javascript:cheesy()><img src='../img/cheesy.gif' align='bottom' alt='$texto_16' border='0'></a> \");
document.write(\"<a href=javascript:grin()><img src='../img/grin.gif' align='bottom' alt='$texto_17' border='0'></a> \");
document.write(\"<a href=javascript:angry()><img src='../img/angry.gif' align='bottom' alt='$texto_18' border='0'></a> \");
document.write(\"<a href=javascript:sad()><img src='../img/sad.gif' align='bottom' alt='$texto_19' border='0'></a> \");
document.write(\"<a href=javascript:shocked()><img src='../img/shocked.gif' align='bottom' alt='$texto_20' border='0'></a> \");
document.write(\"<a href=javascript:cool()><img src='../img/cool.gif' align='bottom' alt='$texto_21' border='0'></a> \");
document.write(\"<a href=javascript:huh()><img src='../img/huh.gif' align='bottom' alt='$texto_22' border='0'></a> \");
document.write(\"<a href=javascript:rolleyes()><img src='../img/rolleyes.gif' align='bottom' alt='$texto_23' border='0'></a> \");
document.write(\"<a href=javascript:tongue()><img src='../img/tongue.gif' align='bottom' alt='$texto_24' border='0'></a> \");
document.write(\"<a href=javascript:embarassed()><img src='../img/embarassed.gif' align='bottom' alt='$texto_25' border='0'></a> \");
document.write(\"<a href=javascript:lipsrsealed()><img src='../img/lipsrsealed.gif' align='bottom' alt='$texto_26' border='0'></a> \");
document.write(\"<a href=javascript:undecided()><img src='../img/undecided.gif' align='bottom' alt='$texto_27' border='0'></a> \");
document.write(\"<a href=javascript:kiss()><img src='../img/kiss.gif' align='bottom' alt='$texto_28' border='0'></a> \");
document.write(\"<a href=javascript:cry()><img src='../img/cry.gif' align='bottom' alt='$texto_29' border='0'></a> \");
document.write(\"<a href=javascript:coracao()><img src='../img/coracao.gif' width='15' height='15' align='bottom' alt='Cora&ccedil;&atilde;o' border='0'></a> \");
}
else {
document.write(\"<font size=1>Houve algum erro ao encontrar os Smyles.</font>\");
}
//-->
</script>
<noscript>
<font size=\"1\">Houve algum erro ao encontrar os Smyles.</font>
</noscript>";
}
else {
echo "&nbsp;";
}
  if ($smyles == 1){
echo "<a href=\"#\" onClick=\"window.open('../img/smilies.php','','width=535,height=500')\"><p align=\"center\"><font face=verdana size=1 color=red>[ mais smyles ]</font></p></a>";
} else {
echo "&nbsp;";
}
?></td>
</tr>
<tr>
  <td class="subtitulopequeno">
 <textarea name="msg" cols="50" rows="8" wrap="VIRTUAL" style="font-family: Verdana; font-size: 10 pt"></textarea>
 (*) </td>
</tr>
<tr>
  <td colspan="2"><br>
    <p align="center">
  <input type="submit" name="Submit" value="  Enviar  ">
  <input type="reset" name="reset" value="  Apagar  ">
    </p>    <br></td>
</tr>
</table>
</form>
</body>
</html>