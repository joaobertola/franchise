<?php
 	include("../connect/conexao_conecta.php") ;
  	include("class.send.php");

	$assunto = $_REQUEST['assunto'];

	$id_pretendentes = $_REQUEST['id_pretendentes'];
	$id_pretendentes = str_replace("-","','",$id_pretendentes);
	$id_pretendentes = "'".$id_pretendentes."'";
	
	//seleciona a mensagem para enviar o e-mail
	$sql_msg = "SELECT texto_email, descricao FROM cs2.pretendentes_status WHERE id = '{$_REQUEST['id_status']}'";
	$qry_msg = mysql_query($sql_msg, $con);
	$texto_email = mysql_result($qry_msg,0,'texto_email');	

	//seleciona os pretendentes para enviar o e-mail
	$sql_email = "SELECT id, nome, LCASE(email)AS email FROM cs2.pretendentes WHERE id IN ($id_pretendentes)";
	$qry_email = mysql_query($sql_email, $con);
	while($row_email = mysql_fetch_array($qry_email)){
		$email_cliente  = $row_email['email'];		
		$nome  		    = $row_email['nome'];		
		$id  		    = $row_email['id'];		
		$proposta_email = str_replace('{nome_candidato}', $nome, $texto_email);
		
		$contato = new SendEmail;
		$contato -> nomeEmail      = $nome; // Nome do Responsavel que vai receber o E-Mail		
		$contato -> paraEmail      = $email_cliente; // Email que vai receber a mensagem
		$contato -> configHost     = "10.2.2.7"; // Endereço do seu SMTP
		$contato -> configPort     = 25; // Porta usada pelo seu servidor. Padrão 25
		$contato -> configUsuario  = "teixeira@webcontrolempresas.com.br"; // Login do email que ira utilizar
		$contato -> configSenha    = "julia@dede"; // Senha do email
		$contato -> remetenteEmail = "teixeira@webcontrolempresas.com.br"; // E-mail que vai ser exibido no remetente da mensagem
		$contato -> remetenteNome  = "Diretor de Franquia";	// Um nome para o remetente
		$contato -> assuntoEmail   = $assunto; // Assunto da mensagem
		$contato -> conteudoEmail  = $proposta_email;// Conteudo da mensagem se voce quer enviar a mensagem em HTML so colocar o body ai dentro e montar seu style que ele aceita normal.
		$contato -> confirmacao = 1; // Se for 1 exibi a mensagem de confirmação
		//$contato -> mensagem = "Sua mensagem de confirmacao !!!\n"; // Mensagem de Confirmação		
		$contato -> erroMsg = "Uma mensagem de erro aqui";// pode colocar uma mensagem de erro aqui!!
		$contato -> confirmacaoErro = 1; // Se voce colocar 1 ele exibi o erro que ocorreu no erro se for 0 não exibi o erro uso geralmente para verificar se ta pegando.	
		try{
			$contato -> enviar(); // envia a mensagem
		}catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		
		$sql5 = "INSERT INTO ocorr_pretendentes (pretendente, msg, data) VALUES ('$id', '$assunto', now())";
		$qr = mysql_query($sql5, $con) or die ("erro ao incluir o comentario".mysql_error());
	}	
?>

<script type="text/javascript">
	alert('E-mail enviado com sucesso ! ');
	window.location.href="../painel.php?pagina1=area_restrita/pretendentes_form_listar.php&doc=<?=$_REQUEST['doc']?>&go=<?=$_REQUEST['go']?>&nome=<?=$_REQUEST['nome']?>&af=<?=$_REQUEST['af']?>&at=<?=$_REQUEST['at']?>&data1=<?=$_REQUEST['data1']?>&data2=<?=$_REQUEST['data2']?>&data_envio1=<?=$_REQUEST['data_envio1']?>&data_envio2=<?=$_REQUEST['data_envio2']?>&id_status=<?=$_REQUEST['id_status']?>";
</script>