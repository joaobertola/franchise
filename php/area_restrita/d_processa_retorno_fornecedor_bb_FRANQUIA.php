<?php

include("../../../validar2.php");

global $conexao,$arquivo;
conecex();

$erro = '';
$QUITADO = '';
$RECEBA_F = '';
$cont_nao = 0;
$cont_liq = 0;
$tarifa_doc = 10.00;

function espaco($espa,$quant){
	$aux=$espa;
	$tamanho=strlen($espa);
	$zeros="";
	for($i=1;$i<=$quant-$tamanho;$i++){
		$zeros = "&nbsp;".$zeros;
	}
	$aux ="$aux$zeros";
	return $aux;
}

	$erro = '';
	$linha  = file($arquivo);
	$total  = count($linha); // Conta as linhas

	for($i=0;$i<$total;$i++){
		# Cabecalho do Arquivo
		$lin = $linha[$i];
		
		if ( $i == 0 ){
			$pagamento = substr($lin,147,4).'-'.substr($lin,145,2).'-'.substr($lin,143,2);
			$sql = "select count(*) qtd from consulta.Controle_ccf where Tipo_Arq='REMPGFRQ' and Arquivo = '$pagamento'";
			$qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
			$qtd = mysql_result($qr_sdo,0,'qtd');
			if ( empty($qtd) ) $qtd = '0';
			if ( $qtd > 0 ){
				echo "ATENCAO !!! Arquivo de RETORNO ( FORNECEDOR - FRANQUIA ) ja foi processado";
				exit;
			}
		}else{
			# Trailler do Arquivo
			if ( substr($lin,13,1) == 'A' ){
				$data_processamento = substr($lin,158,4).'-'.substr($lin,156,2).'-'.substr($lin,154,2);
				$bco = substr($lin,20,3);
				$age     = substr($lin,24,4).'-'.substr($lin,28,1);
				$cta     = substr($lin,29,12).'-'.substr($lin,41,1);
				
				$descricao = "REPASSE P/ FRANQUEADO $data_processamento ($bco/$age/$cta)";
				if ( $bco == '001' ) $comprova = 'DEPOSITO COM SUCESSO (REALIZADO REMESSA PELO BANCO DO BRASIL S/A)';
				else $comprova = 'DOC DEPOSITADO COM SUCESSO PELO BANCO DO BRASIL S/A';
				
				$comprova = 'DOC/TED DEPOSITADO COM SUCESSO PELO BANCO TRANSMISSOR';

				$nome    = substr($lin,43,30);
				$vlr     = substr($lin,162,13).'.'.substr($lin,175,2);
				$vlr     = trim($vlr);				
				$vlr     = $vlr * 1;
				$idfrq   = substr($lin,182,10);
				$idfrq   = trim($idfrq);
				$cmd     = substr($lin,230,2);
				$cmd     = trim($cmd);
				$linhaA = $lin;
			}
			if ( substr($lin,13,1) == 'B' ){
				
				if ( $cmd == '00' ){
					$sql_insert = "INSERT INTO cs2.contacorrente( franqueado, data, operacao, discriminacao, valor ) 
					               VALUES('$idfrq',now(),'1','$descricao','$vlr')";
					$qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");
					$sql_insert = "INSERT INTO cs2.contacorrente( franqueado, data, operacao, discriminacao, valor )
						VALUES('$idfrq',now(),'1','DOC - TRANSFERENCIA ENTRE BANCOS', '$tarifa_doc')";
					$qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");
				}else{
					// tratamento dos ERROS OCORRIDOS
					$erro .= colocaespacosdir($codloja,7).' - '.$linhaA."<br>";
				}
			}
		}
	} # for
	# Terminou processamento registrar movimento
	$sql_insert = "INSERT INTO consulta.Controle_ccf(Tipo_Arq,Arquivo,Data_Remessa,Hora_Inicio)
	        VALUES('REMPGFRQ','$data_processamento',now(),now())";
	$qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");
	
	if ( !empty($erro) ){
		$saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
		$saida .= "Web Control Empresas".espaco('','8')."Retorno Fornecedor (FRANQUIA)".espaco('','10')."BANCO BRASIL".espaco('','10')."Data Processamento: $data_processamento<br><br>";
		$saida  .= "ERROS LISTADOS ================================================================================<br><br>";
		$saida .= $erro;
		$saida .= "</div>";
		echo $saida;
	}else{
		$saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
		$saida .= "Web Control Empresas".espaco('','8')."Retorno Fornecedor (FRANQUIA)".espaco('','10')."BANCO BRASIL".espaco('','10')."Data Processamento: $data_processamento<br><br>";
		$saida  .= "TODOS OS REGISTROS FORAM PROCESSADOS, POREM NENHUM ERRO DE RETORNO FOI ENCONTRADO<br>";		
		$saida .= "</div>";
		echo $saida;
	}
		
	# enviando email dos erros
	include("class.phpmailer.php");
	try {
		$mail = new PHPMailer();
		$mail->IsSendmail(); // telling the class to use SendMail transport
		$mail->IsSMTP(); //ENVIAR VIA SMTP
		$mail->Host = "10.2.2.7"; //SERVIDOR DE SMTP 
		$mail->SMTPAuth = true; //ATIVA O SMTP AUTENTICADO
		$mail->Username = "cpd@webcontrolempresas.com.br"; //EMAIL PARA SMTP AUTENTICADO
		$mail->Password = "#9%kxP*-11"; //SENHA DO EMAIL PARA SMTP AUTENTICADO
		$mail->From = "cpd@webcontrolempresas.com.br"; //E-MAIL DO REMETENTE 
		$mail->FromName = "CPD - Web Control Empresas"; //NOME DO REMETENTE
		$mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - Web Control Empresas"); //E-MAIL DO DESINATARIO, NOME DO DESINATARIO 
		$mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
		$mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
		$mail->Subject = "Retorno Fornecedor ( FRANQUIA )"; //ASSUNTO DA MENSAGEM
		$mail->Body = $saida; //MENSAGEM NO FORMATO HTML
		$mail->Send();
		echo "ATENCAO >>  Este relatorio foi enviado para o EMAIL : administrativo@webcontrolempresas.com.br</p>\n";
	} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
	}	
?>