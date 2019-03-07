<?php
require "../connect/sessao.php";
include "../ocorrencias/config.php";
include "login.php";

mysql_connect($db[host], $db[user], $db[senha]) or die (mysql_error());
mysql_select_db($db[nome]) or die (mysql_error());

if (empty($act)) {
?>
<html>

<head>
<title>Administra��o</title>
<link href="../../css/tabela.css" rel="stylesheet" type="text/css" >
</head>

<body class="admin" bgcolor="<?php echo "$cor[bgcolor]"; ?>">
<table>
  <tr class="titulo">
    <td> Centro de Administra&ccedil;&atilde;o dos Recados </td>
  </tr>
  <tr class="subtituloesquerda">
    <td><a href="?act=edt&edt=mensagem">Editar Recados </a></td>
  </tr>
  <tr class="subtituloesquerda">
    <td><a href="?act=del&del=mensagem">Remover Recados </a></td>
  </tr>
</table>
<form method="post" action="vlogin.php?log=out" >
<input type="submit" name="deslogar" value=" Deslogar " />
</form>
</body>

</html>
<?php
} // end if $act add
if ($act == "edt") {
if ($edt == "mensagem") {
?>
<html>

<head>
<title>Administra��o - Editar Recados</title>
<link href="style.css" type="text/css" rel="stylesheet">
</head>

<body class="admin" bgcolor="<?php echo "$cor[bgcolor]"; ?>">
<p><font face="Verdana" size="3"><b>Editar Recados</b></font></p>
<?php
$sql = mysql_query("SELECT * FROM mural order by id desc");
echo "
<center>
<table border=\"1\" bordercolor=\"#000000\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td><font face=\"Verdana\" size=\"2\"><b>Mensagem:</b></font></td>
<td><font face=\"Verdana\" size=\"2\"><b>Editar:</b></font></td>
</tr>
";
while ($l = mysql_fetch_array($sql)) {
echo "<tr><td><font face=\"Verdana\" size=\"1\">".$l[msg]."</font></td><td><font face=\"Verdana\" size=\"1\"><a href=\"?act=edt&edt=mensagem2&id=".$l[id]."\">Editar</font></td></tr>";
}
echo "</center></table>";
?>
<br><br><form action="admin.php">
<input type="submit" name="voltar1" value="Voltar" />
</form>
</body>
</html>
<?php

} // end if edt mensagem

if ($edt == "mensagem2") {
?>
<html>

<head>
<title>Administra��o - Editar Recados</title>
<link href="style.css" type="text/css" rel="stylesheet">
</head>

<body class="admin" bgcolor="<?php echo "$cor[bgcolor]"; ?>">
<p><font face="Verdana" size="3"><b>Editar Recados</b></font></p>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%">
      <p align="center"><font face="Verdana" size="1">Reescreva a regra:</font></td>
  </tr>
  <tr>
    <td width="100%">
      <form action="admin.php">
        <p align="center"><font face="Verdana" size="1">
		<?php $sql = mysql_query("SELECT * FROM mural where id like '$id'"); $l = mysql_fetch_array($sql); ?>
		<textarea rows="4" name="msg" cols="30">
		<?php echo $l[msg] ?></textarea><br>
        <input type="hidden" name="act" value="edt">
		<input type="hidden" name="id" value="<?php echo $id ?>">
		<input type="hidden" name="edt" value="mensagem2">
		<input type="hidden" name="step" value="2">
		<input type="submit" value="Editar">
		<input type="reset" value="Redefinir">
		</font></p>
      </form>
    </td>
  </tr>
</table>
<form action="admin.php">
<input type="submit" name="voltar2" value="Voltar" />
</form></body>

</html>
<?php
if ($step == "2") {
if (empty($msg)) {
die ("O campo \"Mensagem\" deve ser preenchido.");
} else {
mysql_query("UPDATE mural set msg = '$msg' where id like '$id'") or die(mysql_error());
echo "
<script>
alert(\"Mensagem editada com sucesso!\");
window.redirect(\"admin.php\");
</script>";
} // end else for step 2 edt mensagem

} // end if step 2 edt mensagem

} // end if edt mensagem2

} // end if $act edt
if ($del == "mensagem") {
?>
<html>

<head>
<title>Administra��o - Remover Recados</title>
<link href="style.css" type="text/css" rel="stylesheet">
</head>

<body class="admin" bgcolor="<?php echo "$cor[bgcolor]"; ?>">
<p><font face="Verdana" size="1"><b>Remover Recados</b></font></p>
<?php
$sql = mysql_query("SELECT * FROM mural order by id desc");
echo "
<center>
<form action=\"./Admin.php\" method=\"post\" onsubmit=\"document.all['BotaoSubmit'].disabled = true; \">
<table border=\"1\" bordercolor=\"#000000\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td width=\"77%\"><font face=\"Verdana\" size=\"1\"><b>Recados:</b></font></td>
<td width=\"23%\"><font face=\"Verdana\" size=\"1\"><b>Remover:</b></font></td>
</tr>
";
if (mysql_num_rows($sql) > "0") {
while ($l = mysql_fetch_array($sql)) {
echo "<tr><td><font face=\"Verdana\" size=\"1\">".$l[msg]."</font></td><td><input type=\"radio\" name=\"id\" value=\"".$l[id]."\"><font face=\"Verdana\" size=\"1\">ID: ".$l[id]."</font></td></tr>";
} 
} else {
echo "<tr><td colspan=\"2\"><font face=\"Verdana\" size=\"1\"><b>N�o h� Recados registrados.</b></font></td></tr>";
}
echo "</center></table>";
?>
<input type="hidden" name="act" value="del">
<input type="hidden" name="del" value="mensagem">
<input type="hidden" name="step" value="2">
<br>
<center>
<input id="BotaoSubmit" type="submit" value="Remover">
<input type="reset" value="Redefinir">
</center>
</form>
<input type="button" onClick="javascript: history.back();" value="Voltar" />
</body>
</html>
<?php
if ($step == "2") {
if (empty($id)) {
?>
<script>
alert("Nenhuma mensagem foi removida.");
window.location = 'admin.php';
</script>
<?php
} else {
mysql_query("DELETE FROM mural where id like '$id'") or die(mysql_error());
echo "
<script>
alert(\"Mensagem removida com sucesso!\");
window.location = 'admin.php';
</script>";
} // end else for step 2 del mensagem

} // end if step 2 del mensagem

} // end if $act del mensagem

?>