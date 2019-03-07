<?
session_start("vlogin");
if(!(session_is_registered("login") AND session_is_registered("senha"))) {
                echo "<b>Área restrita</b>.<br>Você não tem permissão para acessá-la.";
                exit;
}
$login=$HTTP_SESSION_VARS[login];
$senha=$HTTP_SESSION_VARS[senha];
?>
