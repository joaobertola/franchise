<?php
require "../connect/conexao_conecta.php"; 

//recebe os dados do formulario
$nome = $_POST['nome'];
$frq = $_POST['frq'];
$assunto = $_POST['assunto'];
$recado = $_POST['recado'];
//pega data e hora
$data = date("Y-m-d H:i:s");

if ($frq == 'todos') {
	$sql = "select id from franquia where sitfrq<>1";
	$ql = mysql_query($sql, $con);
	$linha = mysql_num_rows ($ql);
	while($registro = mysql_fetch_array($ql)){
		$frq = $registro['id'];
		$query ="INSERT INTO correio_franquia (nome, franquia, assunto, recado, data) VALUES ('$nome', '$frq', '$assunto', '$recado', '$data')";
		mysql_query($query,$con);
	}
}else{
	$query ="INSERT INTO correio_franquia (nome, franquia, assunto, recado, data) VALUES ('$nome', '$frq', '$assunto', '$recado', '$data')";
	mysql_query($query,$con);
}
echo "<script>alert(\"Email enviado com sucesso!\");</script>";
mysql_close($con);
echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/d_email.php&mail=area_restrita/d_lista_email.php \";>";
?>

