<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";
 
 //Senha  Pesquisas e Soluções 
if($_REQUEST['op'] == 1) 
	{ 
	if (getenv("REQUEST_METHOD") == "POST") {
		
		
		$codigo = $_POST['codigo'];
		$senha  = $_POST['password'];
		$logon  = $_POST['logon'];
		$log    = $logon.'S'.$senha;
		{
			
			// Buscando a senha antiga
			$sql = "SELECT mid(logon,7,6) as senha_antiga FROM cs2.logon WHERE codloja='$codigo'";
			$qry = mysql_query($sql,$con);
			$senha_antiga =  mysql_result($qry,0,'senha_antiga');
			
			$query = "UPDATE logon SET logon='$log' WHERE codloja='$codigo'";
			mysql_query($query,$con);
			
			// Verifica se o usuario e senha antigo pertence a um usuario master
			// se for, alterar no virtualflex
			
			$sql_buscaMaster = "SELECT login_master FROM base_web_control.webc_usuario
						WHERE id_cadastro = '$codigo'
						AND login       = '$logon'
						AND senha       = '$senha_antiga'";
			$qry_buscaMaster = mysql_query($sql_buscaMaster,$con);
			$login_master =  mysql_result($qry_buscaMaster,0,'login_master');
			
			//se for usuario master envia requisição cURL-less para vfx
			if($login_master == 'S'){
				
				$url = 'http://virtualflex.com.br/ext_req/external_requests.php';
				$data = array('action' => 'alteraSenhaVfx', 'userlogin' => $logon, 'novaSenha' => $senha);
	
				$options = array(
				    'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data),
				    ),
				);
				$context  = stream_context_create($options);
	
				$result = file_get_contents($url, false, $context);
				//echo $result;
				//exit;
			}
			
			// altera senha do webcontrol
			
			$sql_update_wecontrolempresas = "
				UPDATE base_web_control.webc_usuario 
					SET senha   = '$senha'
				WHERE 
					id_cadastro = '$codigo'
				AND
					login       = '$logon'
				AND
					senha       = '$senha_antiga'
				";
			mysql_query($sql_update_wecontrolempresas,$con) or die("Erro: $sql_update_wecontrolempresas");
			

			
			$pagina1 = "clientes/a_altsenha.php";
			echo "<script>alert(\"Senha cadastrado com sucesso!\");</script>";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=$pagina1 \";>";
		}
	}
}

//senha de baixa de titulos
if($_REQUEST['op'] == 2) 
	{ 
	if (getenv("REQUEST_METHOD") == "POST") {
		$codigo 			= $_POST['codigo'];
		$senha_baixatitulo = $_POST['senha_baixatitulo'];
		$nsql = "SELECT senha FROM cs2.usuarios_crediario_recupere 
				 WHERE codloja = '$codigo' AND usuario = 'adm'";
		$nquery = mysql_query($nsql,$con);
		$nline = mysql_num_rows($nquery);
		if ($nline == 0) {
			$sql_insert = "	INSERT INTO 
								cs2.usuarios_crediario_recupere(codloja,usuario,senha,nivel)
							VALUES($codigo,'adm','$senha_baixatitulo','A')";
		}else{
			$sql_insert = "	UPDATE cs2.usuarios_crediario_recupere 
								SET senha = '$senha_baixatitulo' where codloja = '$codigo' and usuario = 'adm'";
		}
		$xquery = mysql_query($sql_insert,$con);
		/*
			$query = "UPDATE cadastro SET senha_baixatitulo='$senha_baixatitulo' WHERE codloja='$codigo'";
			mysql_query($query,$con);	
		*/
		$pagina1 = "clientes/a_altsenha.php";
		echo "<script>alert(\"Senha cadastrado com sucesso!\");</script>";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=$pagina1 \";>";		
	}
}

?>

