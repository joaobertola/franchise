<?php
?>
<html>
<head>
<title>Login</title>
</head>
<body>
<p align="center"><b><font size="2" face="Verdana"><br>
  <br>
  Esta p&aacute;gina esta protegida, informe seu login. </font></b></p>
<form method="POST" action="logar.php">
  <p align="center"><font size="2" face="Verdana">Usuário:<br>
    <input name="usuario" type="text" value="inform" size="10">
    <br>
    Senha:<br>
    <input type="password" name="senha" size="10">
    </font><font size="2" face="Verdana"><br>
    <input type="submit" value="Login" name="entrar">
    </font></p>
	<input type="hidden" name="pagina" value="<?= $pagina; ?>" >
  </form>
<p align="center"><font size="1" face="Verdana"><br>
  </font></p>
</body>
</html>
