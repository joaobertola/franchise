<?php
include("functions.php");
if($_GET["acao"]=="cadastra"){
 cadastrar($_POST["usuario"],$_POST["senha"],$_POST["lembrete"],$_POST["email"]);
}
else{
?>
<html>
<head>
<title>Cadastro</title>
</head>
<body>
<p align="center"><b><font size="2" face="Verdana">//\\ CADASTRO //\\ .</font></b></p>
<form method="POST" action="?acao=cadastra">
  <p align="center"><font size="2" face="Verdana">Usuário:<br>
  <input type="text" name="usuario" size="20"><br>
  Senha:<br>
  <input type="password" name="senha" size="20"> <br>
  Lembrete:<br>
  <input type="text" name="lembrete" size="20"> <br>
  Endere&ccedil;o de E-mail:<br>
  <input type="text" name="email" size="20"> <br>
  <input type="submit" value="Cadastrar"> </font></p>
</form>
<p align="center"><font size="1" face="Verdana">GBAllcheats Login <br>
Desenvolvido por <a href="http://www.gballcheats.1br.net">GBAllcheats</a> .<br>
</font></p>
</body>
</html>
<?php } ?>