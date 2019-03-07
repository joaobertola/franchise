<?php

//echo "<pre>";
$envia = $_REQUEST['envia'];
$id_pedido = $_REQUEST['id_pedido'];
$id_pedido = str_pad($id_pedido,4,0,STR_PAD_LEFT);

$sql = "SELECT
			a.banco, a.agencia, a.tpconta, a.conta, a.dv, a.doc, a.nome, b.fantasia, a.nomebanco
		FROM cs2.solicitacao_valores a
		INNER JOIN cs2.franquia b ON a.id_franquia = b.id
		WHERE a.id = '$id_pedido' ";
$qry = mysql_query($sql, $con) or die("Erro SQL: $sql");

$nome_franquia = @mysql_result($qry,0,'fantasia');
$banco         = @mysql_result($qry,0,'banco');
$nomebanco     = @mysql_result($qry,0,'nomebanco');

$agencia       = @mysql_result($qry,0,'agencia');
$tpconta       = @mysql_result($qry,0,'tpconta');
$conta         = @mysql_result($qry,0,'conta');
$dv            = @mysql_result($qry,0,'dv');
$doc           = @mysql_result($qry,0,'doc');
$nome          = @mysql_result($qry,0,'nome');

$html = "
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
</head>
<script language='javascript'>
function voltar(){
 	d = document.form1;
    d.action = 'painel.php?pagina1=area_restrita/a_solicitacao_valores.php';
	d.submit();
} 
</script>
<form name='form1' method='post' action='#' >
<table border='0' align='center' width='600' cellpadding='0' cellspacing='0' class='bodyText'>
	<tr>
		<td colspan='2' class='titulo' style=\"text-align:'center'\"><br>REQUISI&Ccedil;&Atilde;O DE VALORES</td>
	</tr>
	<tr>
		<td colspan='2' class='titulo' style=\"font-size:18px;text-align:'center'\"><br><b>N&deg; da Requisi&ccedil;&atilde;o: $id_pedido</b></td>
	</tr>
	<tr>
		<td width='200' class='subtitulodireita'>&nbsp;</td>
		<td class='subtitulopequeno'>&nbsp;</td>
	</tr>

	<tr>
		<td class='subtitulodireita'>Franquia</td>
		<td colspan='2' class='subtitulopequeno'>
			$nome_franquia
		</td>
	</tr>
	<tr>
		<td colspan='2' height='15' bgcolor='#CCCCCC'></td>
	</tr>
	<tr>
		<td colspan='2'>
			<table width='100%' cellpadding='4' cellspacing='0' border='1'>
				<thead>
					<tr>
						<td align='center'>Data</td>
						<td align='center'>Descri&ccedil;&atilde;o</td>
						<td align='center'>Valor</td>
					</tr>
				</thead>";

                // Buscando ITENS
				$sql = "SELECT 
							date_format(data,'%d/%m/%Y') as data ,descricao,valor 
						FROM cs2.solicitacao_valores_item
						WHERE id_sol = $id_pedido
						ORDER BY id";
				$qry = mysql_query($sql, $con) or die("Erro SQL: $sql");
				$total = 0;
				while ( $reg = mysql_fetch_array($qry) ){
					$data = $reg['data'];
					$descricao = $reg['descricao'];
					$valor = number_format($reg['valor'],2,',','.');
					
					$total += $reg['valor'];
					
					$html .=  "
						<tr>
							<td align='center'>
								$data
							</td>
							<td align='left' style='font-size:8px'>
								$descricao
							</td>
							<td align='center'>
		                      	$valor
							</td>
						</tr>";
                }
				$total = number_format($total,2,',','.');

				$html .= "
                <tr>
                	<td colspan='2' align='right'>Total da Nota:</td>
                    <td colspan='2' align='center'>$total</td>
                </tr>
			</table>
		</td>
	</tr>
</table>
<table border='0' align='center' width='600' cellpadding='0' cellspacing='0' class='bodyText'>
	<tr>
		<td colspan='2' class='titulo'><br>Dados Banc&aacute;rios</td>
	</tr>
	<tr>
		<td class='subtitulopequeno'>&nbsp;</td>
		<td class='subtitulopequeno'>&nbsp;</td>
	</tr>
	<tr>
		<td class='subtitulodireita'>Banco : </td>
		<td class='subtitulopequeno'>
			$banco - $nomebanco
		</td>
	</tr>
	
	<tr>
		<td class='subtitulodireita'>Ag&ecirc;ncia : </td>
		<td class='subtitulopequeno'>
			$agencia
		</td>
	</tr>
	<tr>
		<td class='subtitulodireita'>Tipo de Conta :</td>
		<td class='subtitulopequeno'>
			$tpconta
		</td>
	</tr>
	<tr>
		<td class='subtitulodireita'>N&deg; da Conta / DV :</td>
		<td class='subtitulopequeno'>
			$conta - $dv
		</td>
	</tr>
	<tr>
		<td class='subtitulodireita'>CPF/CNPJ : </td>
		<td class='subtitulopequeno'>
			$doc
		</td>
	</tr>
	<tr>
		<td class='subtitulodireita'>Nome : </td>
		<td class='subtitulopequeno'>
			$nome
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan='2'>
			<table width='100%'>
				<tr bgcolor='#CCCCCC'>
					<td><p>OBSERVA&Ccedil;&Atilde;O:</p>
						<p>Ap&oacute;s imprimir esta requisi&ccedil;&atilde;o, dever&aacute; ser anexado as NOTAS, RECIBOS e BOLETOS originais dos respectivos pagamentos e enviar com URG&Ecirc;NCIA ao Departamento Financeiro, SOB PENA DE SUSPENS&Atilde;O DE FUTUROS DEP&Oacute;SITOS</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan='2'>&nbsp;</td>
	</tr>
    <tr>
    	<td colspan='2' class='noprint' align='center'>
        	<input type='button' value='   Imprimir Solicita&ccedil;&atilde;o   ' onclick='window.print()' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type='button' value='     Voltar     ' onclick='voltar()' />
        </td>
    </tr>
	<tr>
		<td colspan='2'>&nbsp;</td>
	</tr>
</table>
</form>";

$html .= "<table border='0' align='center' width='600' cellpadding='0' cellspacing='0' class='bodyText'>
		<tr>
			<td>Arquivos em Anexos:<br>";
// Verificando se tem arquivos.
$sql = "SELECT 
			nome_arquivo
		FROM cs2.solicitacao_valores_arq
		WHERE id_sol = $id_pedido";
$qry = mysql_query($sql, $con) or die("Erro SQL: $sql");
while ( $reg = mysql_fetch_array($qry) ){
	$nome_arquivo = $reg['nome_arquivo'];
	$html .= "<a href='https://www.webcontrolempresas.com.br/franquias/php/area_restrita/upload/arquivo_solicitacao/$nome_arquivo'>$nome_arquivo</a><br>";
}
$html .= "</td>
		</tr>
		
		<tr'>
			<td align='center'><p>&nbsp;</p>
			
			________________________________________________</td>
		</tr>
		<tr>
			<td align='center'>Assinatura do Solicitante</td>
		</tr>		
		";

echo $html;

if ( $envia != 'N' ){
	include("class.phpmailer.php");
	try {
		$mail = new PHPMailer();
		$mail->CharSet = "utf-8";
		$mail->IsSendmail(); // telling the class to use SendMail transport
		$mail->IsSMTP(); //ENVIAR VIA SMTP
		$mail->Host = "10.2.2.7"; //SERVIDOR DE SMTP 
		$mail->SMTPAuth = true; //ATIVA O SMTP AUTENTICADO
		$mail->Username = "cpd@webcontrolempresas.com.br"; //EMAIL PARA SMTP AUTENTICADO
		$mail->Password = "#9%kxP*-11"; //SENHA DO EMAIL PARA SMTP AUTENTICADO
		$mail->From = "lucianomancini@hotmail.com"; //E-MAIL DO REMETENTE 
		$mail->FromName = "CPD - Web Control Empresas"; //NOME DO REMETENTE
		$mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - Web Control Empresas"); //E-MAIL DO DESINATÁRIO, NOME DO DESINATÁRIO
		$mail->AddCC("danillo@webcontrolempresas.com.br","Danillo - Web Control Empresas");
		
		$mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
		$mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
		$mail->Subject = "Solicitacao de Valores - Expansao:  $nome_franquia"; //ASSUNTO DA MENSAGEM
		$mail->Body = $html; //MENSAGEM NO FORMATO HTML
		$mail->Send();
		echo "<table border='0' align='center' width='600' cellpadding='0' cellspacing='0' class='bodyText'>
		<tr>
			<td><p>
				ATEN&Ccedil;&Atilde;O >>  Este relatorio foi enviado para o EMAIL : administrativo@webcontrolempresas.com.br
				</p>
			</td>
		</tr>
		</table>";
	} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
	}
}

if ( $envia == 'N' ){
	echo "<script>window.print()</script>";
}

?>