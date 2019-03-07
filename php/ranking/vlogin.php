<?php
require "../connect/sessao.php";
include "../ocorrencias/config.php";

if ($go=='ingressar') {
        $con=mysql_pconnect($db[host],$db[user],$db[senha]);
        mysql_select_db($db[nome]);
        $sql="SELECT * FROM $table_user WHERE usuario='$login'";
        $result=mysql_query($sql);
        $linhas=mysql_num_rows($result);
        for($x=0;$x<$linhas;$x++) {
                $id=mysql_result($result,$x,0);
                $login_bd=mysql_result($result,$x,1);
                $senha_bd=mysql_result($result,$x,2);
                if ($login==$login_bd AND $senha==$senha_bd) {
                        session_start("vlogin"); //Inicializa a sess�o
                        session_name();
                        session_destroy();
                        session_register("login","senha"); //Registra as vari�veis na sess�o
                        header("Location:$link[admin]"); //Redireciono para a p�gina principal
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

setcookie ("login");
setcookie ("senha");

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
AREA RESTRITA:
<FORM ACTION="<?php echo $PHP_SELF; ?>?go=ingressar" method="post">
Login: &nbsp;<INPUT TYPE="text" NAME="login" value=""><br>
Senha: 
<INPUT TYPE="password" NAME="senha" value=""><br><br>
<input type=submit value=" Ingressar ">
</FORM>
</center>
</body>
</html>