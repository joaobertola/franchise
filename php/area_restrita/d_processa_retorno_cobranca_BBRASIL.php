<?php

     # Autor: Luciano Mancini
	 # Data: 21/10/2010
     # Processa Retorno de Cobran�a - Mensalidade Banco do Brasil
	 
	 # Alteracoes: Anotar abaixo
	 #
	 #

	include("../../../validar2.php");

	global $conexao,$arquivo;
	conecex();

	$erro = '';
	$QUITADO = '';
	$fraude = '';
	$RECEBA_F = '';
	$cont_nao = 0;
	$cont_liq = 0;
	
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
	
	$linha  = file($arquivo);
	$total  = count($linha); //Conta as linhas
	for($i=0;$i<$total;$i++){
		# Cabe�alho do Arquivo
		$lin = $linha[$i];
		if ( $i == 0 ){
			$pagamento = substr($lin,147,4).'-'.substr($lin,145,2).'-'.substr($lin,143,2);
		}else{
			# Trailler do Arquivo
			if ( substr($lin,13,1) == 'T' ){
				$cod_movimento    = trim(substr($lin,15,2));
				# numero original
				$i_titulo         = trim(substr($lin,37,17));
				$i_numdoc         = trim(substr($lin,58,15));
				$i_valor_titulo   = trim(substr($lin,81,15)/100);
				$cmd              = trim(substr($lin,213,2));
			}
			if ( substr($lin,13,1) == 'U' ){
				$i_juros_titulo     = trim(substr($lin,17,15)/100);
				$i_total_recebido = $i_valor_titulo + $i_juros_titulo;
				$i_valor_titulo   = $i_total_recebido;

				# PESQUISA NA TABELA   TITULOS
				$sql = "SELECT a.codloja,a.valor,a.numdoc,a.vencimento,b.razaosoc,b.cidade,b.nomefantasia
						FROM cs2.titulos a
						INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
						WHERE a.numboleto = '$i_titulo' or a.numboleto2 = '$i_titulo'";
				$qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
				$qtd  = mysql_num_rows($qr_sql);
				if ( $qtd > 0 ){
					$reg = mysql_fetch_array($qr_sql);
					$Valor_Bol = $reg['valor'];
					$codloja   = $reg['codloja'];
					$Num_Doc    = $reg['numdoc'];
					$Vencimento = $reg['vencimento'];
					$outros     = substr($reg['razaosoc'],0,25).'/'.substr($reg['cidade'],0,15);
					$nomefantasia   = substr($reg['nomefantasia'],0,25);

					# Buscando o logon do cliente
					$sql_logon = "	SELECT MID(logon,1,LOCATE('S', logon) - 1) as logon from cs2.logon
									WHERE codloja = $codloja ";
					$qr_logon = mysql_query($sql_logon,$conexao) or die ("ERRO: $sql");
					$qtd_logon  = mysql_num_rows($qr_logon);
					if ( $qtd_logon > 0 ){
						$reg_logon = mysql_fetch_array($qr_logon);
						$logon = $reg_logon['logon'];
					}
					if ( $Valor_Bol > $i_total_recebido ){
						$erro .="============================================================================================<br>";
						$erro .="Corrup��o de registro !!! Verifique: <br>";
						$erro .="                            Titulo : $i_titulo        Documento: $Num_Doc<br>";
						$erro .="                            Cliente: ID = $codloja -  $logon<br>";
						$erro .="                         Vencimento: $Vencimento<br>";
						$erro .="                    Valor do Titulo: $Valor_Bol  Valor Pago: $i_total_recebido<br>";
						$erro .="TITULO BAIXADO COM O VALOR DE      : $i_total_recebido<br>";
						$erro .="============================================================================================<br>";
						$cont_liq++;
						Quita_fatura($i_titulo,$codloja,$i_juros_titulo,$pagamento,$i_valor_titulo,$i_juros_titulo,$nomefantasia);
						verifica_titulos($codloja);
						$QUITADO .= espaco($Num_Doc,16).espaco('',2).$i_titulo.' '.$Vencimento.' '.espaco($Valor_Bol,10).' '.$outros.'<br>';
						$valor_total_quitado += $Valor_Bol;
					}else{
						$cont_liq++;
						Quita_fatura($i_titulo,$codloja,$i_juros_titulo,$pagamento,$i_valor_titulo,$i_juros_titulo,$nomefantasia);
						verifica_titulos($codloja);
						$QUITADO .= espaco($Num_Doc,15).espaco('',2).$i_titulo.' '.$Vencimento.' '.espaco($Valor_Bol,10).' '.$outros.'<br>';
			            $valor_total_quitado += $Valor_Bol;
					}
              }else{
                    # TITULO NAO ENCONTRADO NA TABELA TITULOS
					$cont_nao++;
					$NAO_ENC .= espaco($i_numdoc,12).espaco('',6).espaco($i_titulo,20).' '.espaco($i_vencimento_titulo,10).espaco('',4).$i_valor_titulo;
              }
			} // comando U
		} // segunda linha em diante
	} // For
	
	# Final de Arquivo
	
	$saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
	$saida .= "Inform System".espaco('','15')."Cobran�a Banc�ria".espaco('','10')."BANCO BRASIL".espaco('','10')."Data Pgto: $pagamento<br><br>";
	
	if ( ! empty($erro) ){
		$saida  .= "TITULOS -- CORROMPIDOS =======================================================================<br><br>";
		$saida .= $erro;
	}

	if ( $cont_nao > 0 ){
		$saida  .= "TITULOS NAO ENCONTRADO =======================================================================<br><br>";
		$saida  .= "Cliente    N� Documento     N� Boleto         Vencimento   Vlr Titulo<br><br>";
		$saida  .= "$NAO_ENC<br>";
		$saida  .= "==============================================================================================<br><br>";
		$saida  .= "Listado(s) ".colocazeros($cont_nao,4)." Titulo(s)<br><br>";
	}
	$saida .= "TITULOS QUITADOS =============================================================================<br><br>";
	$saida .= espaco('N� Docum.',17).espaco('N� Boleto',11).' Vencimento '.espaco('Vr T�tulo',13)." Nome/Cidade <br><br>";
	$saida .= "$QUITADO";
	$saida .= "==============================================================================================<br>";
	$saida .= "Listado(s) ".colocazeros($cont_liq,4)." Titulo(s)     Totalizando : ".number_format($valor_total_quitado,2,',', '.')."<br><br>";

	$saida .= "</div>";
	
	echo $saida;

	# Enviando EMAIL para o ADMINISTRATIVO
	
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
		$mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - Web Control Empresas"); //E-MAIL DO DESINAT�RIO, NOME DO DESINAT�RIO
		$mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
		$mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
		$mail->Subject = "Retorno Cobranca TITULO MENSALIDADE BANCO DO BRASIL"; //ASSUNTO DA MENSAGEM
		$mail->Body = $saida; //MENSAGEM NO FORMATO HTML
		$mail->Send();
		echo "ATEN��O >>  Este relatorio foi enviado para o EMAIL : administrativo@webcontrolempresas.com.br</p>\n";
	} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
	}

	
?>
