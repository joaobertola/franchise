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
<form method="POST" action="logar.php?pagina=<?=$pagina?>">
  <p align="center"><font size="2" face="Verdana">Usuário:<br>
    <input type="text" name="usuario" size="20">
    <br>
    Senha:<br>
    <input type="password" name="senha" size="20">
    </font><font size="2" face="Verdana"><br>
    <input type="submit" value="Login" name="entrar">
    </font></p>
  </form>
<p align="center"><font size="1" face="Verdana"><br>
  </font></p>
</body>
</html>
