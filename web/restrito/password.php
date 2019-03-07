<?php
include("funcoes.php");
if($_GET["acao"] == "lembrar"){
 mostrar_palavra($_POST["usuario"]);
}
elseif($_GET["acao"] == "email"){
 email($_GET["usuario"]);
}
else{
?>
<html>
<head>
<title>Esqueci a senha</title>
</head>
<body>
<p align="center"><b><font size="2" face="Verdana">// Recovery Password \\ </font></b></p>
<form method="POST" action="?acao=lembrar">
  <p align="center"><font size="2" face="Verdana">Usu&aacute;rio:<br>
  <input type="text" name="usuario" size="40">
   <input name="submit" type="submit" value="Continuar&gt;&gt;">
  </font></p>
</form>
<p align="center"><font size="1" face="Verdana">GBAllcehats Login <br>
Desenvolvido por <a href="http://www.gballcheats.1br.net">GBAllcheats</a> .</font><font size="1" face="Verdana"><br>
</font></p>
</body>
</html>
<?php
}
?>