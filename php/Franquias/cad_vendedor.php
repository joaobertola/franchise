<?
require "../connect/sessao.php";
require "../connect/conexao_conecta.php"; 

$comando = "insert into vendedores (vendedor, cpf, data_nasc, tipo_consultor, e-mail, franqueado) values ('$nomec','$cpf','$nasce','$cbotipo','$email','$franqueado')";
$res = mysql_query ($comando, $con);
$res = mysql_close ($con);

$pagina1 = "Franquias/most_vendedor.php";
echo "<script>alert(\"Consultor cadastrado com sucesso!\");</script>";
echo "<meta http-equiv=\"refresh\" content=\"0; url= ../painel.php?pagina1=$pagina1\";>";
?>