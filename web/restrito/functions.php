<?php

session_start("login");

function cadastrar($usuario,$senha,$lembrete,$email){
 include("users.php");
 if(($usuario=="") OR ($senha=="") OR ($lembrete=="") OR ($email=="")){
 echo "<font face=verdana size=1>";
 echo "Preenchimento de todos os campos obrigat�rio.";
 echo "<br>";
 echo "<a href=javascript:history.back(1)>";
 echo "Voltar";
 echo "</a></font>";
 }
 else{
 if($Senha_u[$usuario]){
 echo "<font face=verdana size=1>";
 echo "Usu�rio ja cadastrado. Escolha outro nome.";
 echo "<br>";
 echo "<a href=javascript:history.back(1)>";
 echo "Voltar";
 echo "</a></font>";
 }
 else{
 $varsenha = "Senha_u[";
 $varemail = "Email_u[";
 $varpalavra = "Palavra_u[";
 $fp=fopen("users.php","a+");
 fputs($fp,"
<?php
//Configura��es do usu�rio: $usuario
$$varsenha$usuario] = \"$senha\";
$$varemail$usuario] = \"$email\";
$$varpalavra$usuario] = \"$lembrete\";
?> ");
 fclose($fp);
 echo "<font face=verdana size=1>";
 echo "Voc� foi cadastrado com sucesso!";
 echo "<br>";
 echo "</a></font>"; 
 }
 }
}
function proteger(){
 session_destroy();
 
 $pagina = $_SERVER["PHP_SELF"];
 if(($_SESSION["user"]!="") OR ($_SESSION["pass"]!="")){}
 else{
 echo "<script>location.href='login.php?act=frm&pagina=$pagina'</script>";
 }
}
function valida_login($usuario,$senha,$pagina){
 include("users.php");
 if(!isset($Senha_u[$usuario])||($Senha_u[$usuario]=='')){
 echo "<font face=verdana size=1>";
 echo "Usu�rio n�o cadastrado";
 echo "<br>";
 echo "<a href=javascript:history.back(1)>";
 echo "Voltar";
 echo "</a></font>";
 }
 elseif($Senha_u[$usuario]==$senha){
 $_SESSION["user"] = $usuario;
 $_SESSION["pass"] = $senha;
 echo "<script>location.href='$pagina'</script>";
 }
 else{
 echo "<font face=verdana size=1>";
 echo "Senha incorreta";
 echo "<br>";
 echo "<a href=javascript:history.back(1)>";
 echo "Voltar";
 echo "</a></font>";
 }
}
function email($usuario){
 include("users.php"); 
 if(!$Senha_u[$usuario]){
 echo "<font face=verdana size=1>";
 echo "Usu�rio inexistente";
 echo "<br>";
 echo "<a href=javascript:history.back(1)>";
 echo "Voltar";
 echo "</a></font>";
 }
 else{
 mail($Email_u[$usuario],"A Senha!","A sua senha em nosso banco de dados �: $Senha_u[$usuario]!","");
 echo "<font face=verdana size=1>";
 echo "Por favor, verifique seu email.";
 echo "<br>";
 echo "<a href=javascript:history.back(1)>";
 echo "Voltar";
 echo "</a></font>"; 
 }
}
function mostrar_palavra($usuario){
 include("users.php"); 
 if(!$Senha_u[$usuario]){
 echo "<font face=verdana size=1>";
 echo "Usu�rio n�o cadastrado";
 echo "<br>";
 echo "<a href=javascript:history.back(1)>";
 echo "Voltar";
 echo "</a></font>";
 }
 else{
 echo "<font face=verdana size=1>";
 echo "Lembrete de senha: <b>$Palavra_u[$usuario]</b>";
 echo "<br>";
 echo "<a href=?acao=email&usuario=$usuario>";
 echo "N�o lembra ainda?...";
 echo "<br>";
 echo "<a href=javascript:history.back(1)>";
 echo "Voltar";
 echo "</a></font>";
 }
}

?>