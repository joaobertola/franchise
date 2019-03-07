<?php

include "config.php";

$go = $_REQUEST['go'];

if ($go=='ingressar') {
	echo "<pre>";
	print_r($_SESSION);
		$login_ocorr = $_REQUEST['login_ocorr'];
		$senha_ocorr =  $_REQUEST['senha_ocorr'];
        $con=mysql_pconnect($db[host],$db[user],$db[senha]);
        mysql_select_db($db[nome]);
        $sql="SELECT id, usuario, senha_restrita FROM $table_user WHERE usuario='$login_ocorr' AND senha_restrita = '$senha_ocorr'";
        $result=mysql_query($sql) or die ("Erro MYSQL : $sql");
        $linhas=mysql_num_rows($result);

        for($x=0;$x<$linhas;$x++) {
                $id=mysql_result($result,$x,0);
                $login_bd=mysql_result($result,$x,1);
                $senha_bd=mysql_result($result,$x,2);

                if ($login_ocorr==$login_bd AND $senha_ocorr==$senha_bd) {
                        session_start("vlogin"); //Inicializa a sess�o
                        session_name();
                        session_destroy();
                        session_register("login_ocorr","senha_ocorr"); //Registra as vari�veis na sess�o
                        header("Location:admin.php"); //Redireciono para a p�gina principal
                        exit;
                }
else {
header ("Location: $PHP_SELF?go=error");
}
}
}
?>
<?php
if ($go=='error') {
print"
<script>
alert(\"Login ou senha incorreta!\");
window.location = 'vlogin.php';
</script>
";
exit;
}
if ($log == "out") {

setcookie ("login_ocorr");
setcookie ("senha_ocorr");

?>
<script>
alert("Deslogado!");
window.close();
</script>
<?php
}
?>
<html>
<body bgcolor="<?php echo "$cor[bgcolor]"; ?>">
<br><br>
<center>
<font face="Arial" size="2">AREA RESTRITA:</font>
  <FORM ACTION="<?php echo $PHP_SELF; ?>?go=ingressar" method="post">
<font face="Arial" size="2">Login: &nbsp;<INPUT TYPE="text" NAME="login_ocorr" value=""><br>
Senha:</font> <INPUT TYPE="password" NAME="senha_ocorr" value=""><br><br>
<input type=submit value="Ingressar">
</FORM>
</center>
</body>
</html>