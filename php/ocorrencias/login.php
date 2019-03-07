<?
session_start("vlogin");

print_r( $_SESSION );

if(!(session_is_registered("login_ocorr") AND session_is_registered("senha_ocorr"))) {
                echo "<b>Área restrita</b>.<br>Você não tem permissão para acessá-la.";
                exit;
}
$login=$HTTP_SESSION_VARS[login];
$senha=$HTTP_SESSION_VARS[senha];
?>
