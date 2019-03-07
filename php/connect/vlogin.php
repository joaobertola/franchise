<?php

if ($tipo == "a") {

	header("Location: ../area_restrita.php");

}else{
	
	echo "<script>alert(\"Usuário inválido!\\nVocê não possui direito para acessar aqui!\");</script>";
	echo "<meta http-equiv=\"refresh\" content=\"0; url= ../corpo.php\";>";
	
}
?>