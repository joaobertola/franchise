<?php

if ( $id_franquia <= 0 ){
	echo "Tente outra vez.";
	exit;
}

function data_mysql($data){
	// converte data no formato DD/MM/AAAA  para AAAA-MM-DD
	$data = substr($data,6,4)."-".substr($data,3,2) . "-" . substr($data,0,2);
	return $data;
}

function extenso($valor=0, $maiusculas=false) { 
	$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão"); 
	$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões"); 
	
	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"); 
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove"); 
	$u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove"); 
	
	$z=0; 
	
	$valor = number_format($valor, 2, ".", "."); 
	$inteiro = explode(".", $valor); 
	for($i=0;$i<count($inteiro);$i++) 
	for($ii=strlen($inteiro[$i]);$ii<3;$ii++) 
	$inteiro[$i] = "0".$inteiro[$i]; 
	
	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2); 
	for ($i=0;$i<count($inteiro);$i++) { 
		$valor = $inteiro[$i]; 
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]]; 
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]]; 
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : ""; 
		
		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru; 
		$t = count($inteiro)-1-$i; 
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : ""; 
		if ($valor == "000")$z++; elseif ($z > 0) $z--; 
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r; 
	} 
	
	if(!$maiusculas){ 
		return($rt ? $rt : "zero"); 
	} else { 
		return (ucwords($rt) ? ucwords($rt) : "Zero"); 
	} 
}

function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';
	$retorno = '';
	$caracteres = '';

	$caracteres .= $lmin;
	if ($maiusculas) $caracteres .= $lmai;
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;

	$len = strlen($caracteres);
	for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand-1];
	}
	return $retorno;
}

function CalculaPrestacao2($PVista,$Entrada,$qtd_parcela,$tx_mensal)
{
	$VFin = $PVista-$Entrada;
	$tx_mensal = $tx_mensal/100.00;
	$R = $VFin * $tx_mensal * pow( (1+$tx_mensal),$qtd_parcela)/(pow((1+$tx_mensal),$qtd_parcela)-1);
	return $R;
}

$hoje = date('Y-m-d');
$data = date('d/m/Y');

$sql_lim_disp = "SELECT a.limite_credito, a.banco, a.agencia, a.conta,
						a.tpconta, a.cpftitular, a.titular, b.nbanco, a.fantasia 
				 FROM cs2.franquia a
				 INNER JOIN consulta.banco b ON a.banco = b.banco
				 WHERE a.id = $id_franquia";
$qry_lim = mysql_query($sql_lim_disp,$con) or die("Erro SQL: $sql_lim_disp");
$limite_credito  = mysql_result($qry_lim,0,'limite_credito');
$banco_cliente   = mysql_result($qry_lim,0,'banco');
$agencia_cliente = mysql_result($qry_lim,0,'agencia');
$conta_cliente   = mysql_result($qry_lim,0,'conta');
$nbanco          = mysql_result($qry_lim,0,'nbanco');
$fantasia        = mysql_result($qry_lim,0,'fantasia');
$tpconta         = mysql_result($qry_lim,0,'tpconta');
$tpconta         = ($tpconta    == '1' ? 'CONTA CORRENTE' : 'CONTA POUPAN&Ccedil;A');
$cpfcnpj_doc     = mysql_result($qry_lim,0,'cpftitular');
$nome_doc        = mysql_result($qry_lim,0,'titular');
$limite_credito *= 1;

$valor = $_REQUEST['vlr'];
$valor_solicitado = str_replace('.','',$valor);
$valor_solicitado = str_replace(',','.',$valor_solicitado);

$sql = "SELECT sum(valor_parcela) AS valor 
		FROM cs2.cadastro_emprestimo_franquia 
		WHERE id_franquia = $id_franquia AND valor_pagamento IS NULL";
$qry = mysql_query($sql,$con) or die("Erro SQL: $sql");
$valor_parcela_avencer = mysql_result($qry,0,'valor');
$valor_parcela_avencer *= 1; 
$valor_solicitado *= 1;
$valor = $valor_solicitado;
$limite_disponivel = $limite_credito - $valor_parcela_avencer;

if ( $limite_disponivel < 0 ) $limite_disponivel = 0;

if ( $valor_solicitado > 0 ){
	if ( $limite_disponivel < $valor_solicitado ){
		$limite_disponivel = number_format($limite_disponivel,2,',','.');	
		$valor_solicitado  = number_format($valor_solicitado,2,',','.');
		echo "<hr><br><div align='center'>DESCULPE !!!   O Limite de Cr&eacute;dito para Antecipa&ccedil;&atilde;o de Boletos dispon&iacute;vel é menor que o valor solicitado.<br><br>Limite Dispon&iacute;vel: R$ $limite_disponivel <br><br>Valor solicitado: R$ $valor_solicitado<br><br>
		<input type='button' value='VOLTAR' onClick='history.go(-1)'>
		</div><hr>";
		exit;
	}
}

$valor_extenso = extenso($valor_solicitado, true);
$valor_extenso = strtoupper($valor_extenso);

$qtd_parcela_solicitada = trim($_REQUEST['qtd_parcela']);

//$qtd_parc = str_pad($qtd_parcela_solicitada,3,'0',STR_PARD_LEFT);

//echo "[$qtd_parcela_solicitada] - [$qtd_parc]";
//die;

$tabela = "<table border='1' width='100%'>";

$vr_solicitado = number_format($valor_solicitado,2,',','.');

$nparcela = CalculaPrestacao2($valor_solicitado,0,$qtd_parcela_solicitada,'5');
$vencimento = date('d/m/Y');
for ( $i = 1 ; $i<= $qtd_parcela_solicitada ; $i++ ){
	$vencimento = SomarData($vencimento, '0', '1', '0');
	$valor_parcela = number_format($nparcela, 2, ',', '.');
	$tabela .= "<tr>
   		   	        <td>$i&ordm; Vencimento</td>
   	           	    <td>$vencimento</td>
       	           	<td>R$ $valor_parcela</td>
                </tr>";
} 
$tabela .= "</table>";

if ( $valor_parcela <> $_REQUEST['vr_parcela'] ){
	echo "Houve diverg&ecirc;ncia no c&aacute;lculo das parcelas entre sistemas. Contate a WEB CONTROL EMPRESAS ou sua franquia de relacionamento. <br>Calculo 1 :[$valor_parcela]  Calculo 2: [".$_REQUEST['vr_parcela']."]";
	exit;
}

$protocolo = geraSenha(10,true,true,false);
$protocolo = strtoupper($protocolo);

$vencimento = date('d/m/Y');

for ( $i = 1 ; $i<= $qtd_parcela_solicitada ; $i++ ){
	$vencimento = SomarData($vencimento, '0', '1', '0');
	$nvencimento = data_mysql($vencimento);
	$array_vencimento[$i] = $vencimento;
	$sql_insert = "INSERT INTO 
						cs2.cadastro_emprestimo_franquia(
							id_franquia,
							data_solicitacao,
							hora_solicitacao,
							qtd_parcelas,
							numero_parcela,
							data_vencimento,
							valor_parcela,
							limite_no_dia,
							vr_emprestimo_solicitado,
							protocolo)
				   VALUES(  $id_franquia,
				   			NOW(),
							NOW(),
							$qtd_parcela_solicitada,
							$i,
							'$nvencimento',
							$nparcela,
							$limite_disponivel,
							'$valor_solicitado',
							'$protocolo'
							)";
	$qry_insert = mysql_query($sql_insert,$con) or die("Erro : $sql_insert");
}

$venc_ini = $array_vencimento[1];
$venc_fim = $array_vencimento[$qtd_parcela_solicitada*1];

$saida  = "<table width=\"600\" border=\"1\" cellpadding=\"1\" cellspacing=\"0\" align=\"center\" bordercolor=\"#00CC99\">
	<tr>
	   <td>
			<p><div align=\"center\">
				Solicita&ccedil;&atilde;o de Antecipa&ccedil;&atilde;o de Cr&eacute;ditos de Repasse realizada com SUCESSO !
				<br><br>
				Protocolo da Solicita&ccedil;&atilde;o: <font color=\"#FF0000\"><b>$protocolo</b></font>
				<br><br>
				No prazo m&aacute;ximo de 24 horas o cr&eacute;dito solicitado estar&aacute; dispon&iacute;vel na conta abaixo:</div></p>
		</td>
	</tr>
	<tr>
		<td>
			<table width=\"600\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" align=\"center\">
				<tr>
					<td colspan=2 align=\"center\">Conta cadastrada para recebimento da  Antecipa&ccedil;&atilde;o:</td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td width=\"200\">Banco</td>
					<td>$banco_cliente - $nbanco</td>
				</tr>
				<tr>
					<td>Ag&ecirc;ncia</td>
					<td>$agencia_cliente</td>
				</tr>
				<tr>
					<td>Tipo Conta</td>
					<td>$tpconta</td>
				</tr>
				<tr>
					<td>N&deg; Conta</td>
					<td>$conta_cliente</td>
				</tr>
				<tr>
					<td>CPF/CNPJ do Titular</td>
					<td>$cpfcnpj_doc</td>
				</tr>
				<tr>
					<td>Titular</td>
					<td>$nome_doc</td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align=\"center\">Valor da Antecipa&ccedil;&atilde;o: R$ $vr_solicitado<br>( $valor_extenso )</td>
	</tr>
	<tr>
		<td>$tabela</td>
	</tr>
";
if ( $franqueado == 'S' ){
	$saida  .= "
	<tr>
		<td align=\"center\" style=\"font-size:15px;color:#F00\"><b>ESTA É UMA SIMULA&Ccedil;&Atilde;O PARA TREINAMENTO DE CLIENTES.</b></td>
	</tr>";
}
$saida  .= "
</table>";

$retorno = $saida;

$saida  .= "
<div align=\"center\" class=\"noprint\"><input type=\"button\" value=\" Imprimir \" onclick=\"window.print();\" /></div>
";

$retorno_email_administrativo = "<div>Solicita&ccedil;&atilde;o de Antecipa&ccedil;&atilde;o de Franquia<br><br>Franquia: $id_franquia - $fantasia</div><br><br>".$retorno;

echo "$saida";


# Enviando Email para o cliente que solicitou a antecipação e para a Departamento Financeiro.

include("class.send.php");

$assunto = "Solicitacao de Antecipacao - Franquia";

$contato = new SendEmail;
$contato -> nomeEmail      = "Solicitacao de Antecipacao - Franquia"; // Nome do Responsavel que vai receber o E-Mail		
$contato -> paraEmail      = 'administrativo@webcontrolempresas.com.br' ; // Email que vai receber a mensagem
$contato -> configHost     = "10.2.2.7"; // Endereço do seu SMTP
$contato -> configPort     = 25; // Porta usada pelo seu servidor. Padrão 25
$contato -> configUsuario  = "administrativo@webcontrolempresas.com.br"; // Login do email que ira utilizar
$contato -> configSenha    = "informbrasil"; // Senha do email
$contato -> remetenteEmail = "administrativo@webcontrolempresas.com.br"; // E-mail que vai ser exibido no remetente da mensagem
$contato -> remetenteNome  = "Administrativo";	// Um nome para o remetente
$contato -> assuntoEmail   = $assunto; // Assunto da mensagem
$contato -> conteudoEmail  = $retorno_email_administrativo;// Conteudo da msg se voce quer enviar a mensagem em HTML.
$contato -> confirmacao    = 1; // Se for 1 exibi a mensagem de confirmação
$contato -> mensagem       = ""; // Mensagem de Confirmacao
$contato -> erroMsg        = "Uma mensagem de erro aqui";// pode colocar uma mensagem de erro aqui!!
$contato -> confirmacaoErro = 1; // Se voce colocar 1 ele exibi o erro que ocorreu no erro se for 0 não exibi
$contato -> enviar(); // envia a mensagem

/*
	include("smtp.class.php");

	$host = "10.2.2.7"; // host do servidor SMTP 
	$assunto = "Solicitação de Antecipação - Franquia";
	$smtp = new Smtp($host);
	$smtp->user = "administrativo@webcontrolempresas.com.br"; // usuario do servidor SMTP
	$smtp->pass = "informbrasil"; // senha dousuario do servidor SMTP
	$smtp->debug = true; // ativar a autenticaç SMTP
	$from = "administrativo@webcontrolempresas.com.br";
		
	if($smtp->Send('administrativo@webcontrolempresas.com.br', $from, $assunto, $retorno_email_administrativo))
		$mensagem = "OK";
	else $mensagem = "ERR";

	if ( $mensagem == 'OK' ){
		if ( ! empty( $email_cliente ) ){
			$smtp2 = new Smtp($host); // host do servidor SMTP
			$smtp2->user = "administrativo@webcontrolempresas.com.br"; // usuario do servidor SMTP
			$smtp2->pass = "informbrasil"; // senha dousuario do servidor SMTP
			$smtp2->debug = true; // ativar a autenticaç SMTP
			$from = "administrativo@webcontrolempresas.com.br";
			if($smtp2->Send($email_cliente, $from, $assunto, $retorno_email_administrativo )) $mensagem = "OK";
			else $mensagem = "ERR";
		}
	}
	*/
?>