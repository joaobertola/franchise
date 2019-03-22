<?php

$usuario = $_POST["usuario"];
$senha = $_POST["senha"];

$ip = getenv("REMOTE_ADDR");

session_start();
//elimina os dados da sess�o
unset($_SESSION['id']);
unset($_SESSION['name']);
unset($_SESSION['nome2']);
unset($_SESSION['login']);

session_destroy();

//include("destroy.php");

if (($usuario!="")||($senha!="")) {

	include "conexao_conecta.php";
	include "funcoes.php";
	
	# qtd de acesso independente se tiver correto o usuario ou senha
	# somente ser� permitido 15 tentativas	
	
	$comando="select * from franquia where usuario='$usuario' and senha='$senha'";
        
	$res = mysql_query($comando,$con);
	$linha = mysql_num_rows($res);
	$matriz = mysql_fetch_array($res);

	$res = mysql_close($con);

	if ($linha!=0) {
		$login = $matriz['usuario'];
		$tipo = $matriz['tipo'];
		$classificacao = $matriz['classificacao'];
		$fantasia = $matriz['fantasia'];
		$id = $matriz['id'];
		$id_master = $matriz['id_franquia_master'];
		$senha = $matriz['senha'];
		$acesso_remoto = $matriz['acesso_remoto'];
		$sitfrq = $matriz['sitfrq'];

		if ( $sitfrq > 0){ 
		
			$variavel_1 = 'Sua Franquia não atendeu as diretrizes comerciais acordadas na 2ª REUNI�O NACIONAL DE FRANQUIAS, infringindo a cláusula 23ª do Contrato de Franchising.';
		$variavel_2 = '1 - Qtd mínima de [2] ASSISTENTE COMERCIAL não contratada.';
		$variavel_3 = '2 - Qtd mínima de [2] CONSULTORES COMERCIAIS não contratados.';
		$variavel_4 = '3 - Qtd mínima de [20] VISITAS SEMANAIS AGENDADAS insuficiente.';
		$variavel_5 = '4 - Qtd mínima de [7] VENDAS SEMANAIS não realizadas.';
		$variavel_6 = 'Entre em contato URGENTE com a FRANQUEADORA !';
		
		echo '<script>alert("'.'\n'.$variavel_1.'\n\n'.$variavel_2.'\n'.$variavel_3.'\n'.$variavel_4.'\n'.$variavel_5.'\n\n'.$variavel_6.'");history.back();</script>';
			exit;		
		
		}
                // Bloqueando para acesso remoto.
		$xip = substr($ip,0,7);
		if ( $acesso_remoto == 'N' ){
                    if ( $xip <> '192.168' && $xip <> '10.2.2.' ){
			print"
			<script>
			alert(\"Usuario sem permissao para Acesso Remoto [$ip]!\");
			history.back();
			</script>
			";
			exit;
                    }
		}
		session_start();
		$_SESSION['ss_name'] = $login;
		$_SESSION['ss_tipo'] = $tipo;
		$_SESSION['ss_classificacao'] = $classificacao;
		$_SESSION['usuario'] = "$login";
		$_SESSION['fantasia'] = "$fantasia";
		$_SESSION['id'] = "$id";
		$_SESSION['id_master'] = "$id_master";
		$_SESSION['senha'] = "$senha";
		
		$qtd = seguranca3($ip, $usuario, 'S');

		if ( $qtd >= 15 )
			header("location:../../erro/erro.htm");
		else
			header("Location: ../painel.php");
	} else {
		$qtd = seguranca3($ip, $usuario, 'N');
		if ( $qtd >= 15 )
			header("location:../../erro/erro.htm");
		else {
			print"
			<script>
			<!-- alert(\"Acesso negado!\\nVerifique seus dados!\"); -->
			history.back();
			</script>
			";
			exit;
		}
	}
} else {
	header("Location: ../../erro/index.php");
}

?>
