<?php
	require_once("../smtp.class.php");

$to       = $_REQUEST['email'];	
$razaosoc = $_REQUEST['razaosoc'];  
$end      = $_REQUEST['end'];  
$bairro   = $_REQUEST['bairro'];  
$cidade   = $_REQUEST['cidade'];  
$uf       = $_REQUEST['uf'];  
$cep      = $_REQUEST['cep'];  
$nome_credor = $_REQUEST['nome_credor'];
$fone_credor = $_REQUEST['fone_credor'];
$data_notificacao = $_REQUEST['data_notificacao'];
$soma = $_REQUEST['soma'];

	$configuracao .= "COMUNICADO DE DEBITO \n\n";	 
	$configuracao .= "\n\nNome: $razaosoc";	 
	$configuracao .= "\nEndereco: $end";	 
	$configuracao .= "\nBairro: $bairro";	 
	$configuracao .= "\nCidade: $cidade";	 
	$configuracao .= "\nUF: $uf";	 
	$configuracao .= "\nCep: $cep";	 
	$configuracao .= "\n\nPrezado Sr.(a) $razaosoc";	 
	$configuracao .= "\n\nVimos lembrar-lhe sobre o vencimento(s) de sua(s) parcela(s),  correspondente ao seu contrato com nossa  empresa. Temos certeza que somente a falta  de tempo ou o natural esquecimento fez com que V.Sa. deixasse de saldar seu  d�bito na data do vencimento, cujo pagamento solicitamos seja providenciado  com urg�ncia.";	 
	$configuracao .= "\n\nA prop�sito, lembramos-lhe que nossas  facilidades e proposta para pagamento devem-se &agrave; confian�a depositada em V.Sa..";	 
	$configuracao .= "\n\nEste acordo lhe dar� melhores condi��es para pagamento sem comprometer seu or�amento.";	 
	$configuracao .= "\n\nEncaminhamos os BOLETOS em anexo para pagamento em qualquer BANCO, CASAS LOT�RICAS, CAIXAS ELETR�NICOS, INTERNET e CORREIOS.";	 
	$configuracao .= "\n\nQuaisquer d�vidas referentes aos Boletos em anexo, favor entrar em contato conosco para esclarecimentos que forem necess�rios.";	 
	$configuracao .= "\n\nReiteramos nossos protestos de elevada estima e considera��o.";
	$configuracao .= "\n\nCordialmente,";	 
	$configuracao .= "\n\nDepartamento Financeiro.";	
	
	$configuracao .= "\n\nDADOS DO DEBITO";	
	$configuracao .= "\nEmpresa Credora: $nome_credor";	 
	$configuracao .= "\nTelefone: $fone_credor";	 
	$configuracao .= "\nVencimento: $data_notificacao";	 
	$configuracao .= "\nValor: $soma";	 
	
	$assunto = 'COMUNICADO DE DEBITO WEB CONTROL EMPRESAS';
	
	$from = 'debito@webcontrolempresas.com.br';
	
	$host = "mail.webcontrolempresas.com.br"; //host do servidor SMTP
	$smtp = new Smtp($host);
	
	$smtp->user = "financeiro@webcontrolempresas.com.br"; //usuario do servidor SMTP
	$smtp->pass = "informbrasil"; // senha dousuario do servidor SMTP
	$smtp->debug = true; // ativar a autentica� SMTP
	
	if($smtp->Send($to, $from, $assunto, $configuracao )) $mensagem = "OK";
	else $mensagem = "ERR";

	if($mensagem=="OK"){?>
		<script language="javascript">
			alert("Enviado com sucesso !");
			window.close();
		</script>
	<?php } else { ?>
		<script language="javascript">
			alert("Erro ao Enviar !");
			window.close();
		</script>
	<?php	
	}
	?>
