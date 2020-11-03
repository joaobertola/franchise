<?php

	# Alteracoes no arquivo
	# 05/05/2010 alterado para cobrar a taxa de 2% para o Recupere
	
	include("../../../validar2.php");

	global $conexao,$arquivo, $cont_nao, $NAO_ENC, $RECEBA_F , $cmd;
	conecex();


function quita_titulo($i_titulo, $i_valor_titulo, $i_total_recebido, $i_juros_titulo, $pagamento){
	
	global $conexao, $cont_nao, $NAO_ENC, $RECEBA_F, $cmd;
	
	$sql = "SELECT	a.codloja,a.valor,a.numdoc, date_format(a.vencimento,'%d/%m/%Y') as vencimento,
                        b.nomefantasia,b.cidade,
                        a.cpfcnpj_devedor, c.Nom_Nome, b.banco_cliente, b.agencia_cliente,
                        b.conta_cliente, b.cpfcnpj_doc, b.nome_doc, a.tp_titulo, a.datapg,
                        a.emissao
                FROM cs2.titulos_recebafacil a
                INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                LEFT OUTER JOIN base_inform.Nome_Brasil c ON a.cpfcnpj_devedor = c.Nom_CPF
                INNER JOIN cs2.logon d ON b.codloja = d.codloja
                WHERE a.numboleto='$i_titulo'
                GROUP BY a.numboleto";
	$qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
	$qtd  = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		$dados = mysql_fetch_array($qr_sql);
		$i_titulo = $dados['numdoc'];
		$tp_tit = $dados['tp_titulo'];
		$Valor_Bol = $dados['valor'];
		$vencimento = $dados['vencimento'];
		$codloja = $dados['codloja'];
		$logon = $dados['logon'];
		$nomefantasia = $dados['nomefantasia'];
		$emissao = $dados['emissao'];
		
		# Verificando se o titulo foi pago por compensa��o
		# 00 - Pago e o dinheiro foi liberado
		# Diferente de 00 - N�o baixar o pagamento e gravar em uma tabela para libera��o do Administrativo
		if ( $cmd == '00'){
			# Atualizando tabela titulo_recebafacil
			$sql_update = "	UPDATE cs2.titulos_recebafacil 
							SET datapg = '$pagamento', valorpg='$i_total_recebido',juros='$i_juros_titulo' 
							WHERE numboleto='$i_titulo'";
			$qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
			# Verificando se o titulo j� est� na tabela Conta Corrente Receba F�cil
			$sql_cta = "SELECT count(*) qtd FROM cs2.contacorrente_recebafacil WHERE numboleto = '$i_titulo'";
			$qr_cta = mysql_query($sql_cta,$conexao) or die ("ERRO: $sql_cta");
			$qtd = mysql_result($qr_cta,0,'qtd');
			if ( empty($qtd) ) $qtd = '0';
			if ( $qtd == 0 ){
				$Text = ' Titulo Receb. Bco : '.$dados['cpfcnpj_devedor'].' '.$dados['Nom_Nome'];
				$Text = str_replace(chr(39),'',$Text);
				$Text = str_replace(chr(47),'',$Text);
				
				// verifico o saldo do cliente
				$sql_sdo = "SELECT saldo FROM cs2.contacorrente_recebafacil
							WHERE codloja='$codloja' order by id desc limit 1";
				$qr_sdo = mysql_query($sql_sdo,$conexao) or die ("ERRO: $sql_sdo");
				$saldo = mysql_result($qr_sdo,0,'saldo');
				if ( empty($saldo) ) $saldo = '0';
				$tx_adm = 0;
				$tx_adm = ( $i_valor_titulo * 0.025 );
				$saldo += $i_valor_titulo;
                                
				$time_inicial = strtotime('2017-01-01');
                                $time_final = strtotime($emissao);
                                // Calcula a diferença de segundos entre as duas datas:
                                $diferenca = $time_final - $time_inicial; // 19522800 segundos
                                // Calcula a diferença de dias
                                $diferenca_dias = (int) floor($diferenca / (60 * 60 * 24));
                                // Após 01/01/2017
                                if ( $diferenca >= 0 ) $tarifa = 2.25;
                                else $tarifa = 4.95;
            
				$saldo = ($saldo - ( $tarifa + $tx_adm) );
				$sql_ins = "INSERT INTO cs2.contacorrente_recebafacil(
								numboleto,codloja,data,discriminacao,venc_titulo,
								valor_titulo,valor,saldo,datapgto,tx_adm, tarifa_bancaria)
							SELECT numboleto,a.codloja, now(),'$Text', a.vencimento, a.valor,
								'$i_valor_titulo',' $saldo','$pagamento','$tx_adm', $tarifa
							FROM cs2.titulos_recebafacil a
							INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
							WHERE a.numboleto='$i_titulo'";
				$qr_ins = mysql_query($sql_ins,$conexao) or die ("ERRO: $sql_ins");
			}
			$RECEBA_F .= '<tr><td>'.substr($logon.' - '.$nomefantasia,0,35).'</td>';
			$RECEBA_F .= '<td>'.$dados['cpfcnpj_devedor'].'</td>';
			$RECEBA_F .= '<td>'.substr($dados['Nom_Nome'],0,35).'</td>';
			$RECEBA_F .= '<td width="120">'.$i_titulo.'</td>';
			$RECEBA_F .= '<td width="120">'.$vencimento.'</td>';
			$RECEBA_F .= '<td width="120" align="right">'.number_format($dados['valor'],2,',','.').'</td>';
			$RECEBA_F .= '<td width="120" align="right">'.number_format($i_valor_titulo,2,',','.').'</td></tr>';
		
		}else{
			# Titulo pago com Cheque, enviar para a tabela para libera��o do Wellington
			$comp_sql = "INSERT INTO cs2.titulos_recebafacil_a_liberar 
						 SELECT * FROM cs2.titulos_recebafacil where numboleto='$i_titulo'";
			$qr_comp = mysql_query($comp_sql,$conexao) or die ("ERRO: $comp_sql");
			$comp_sql = "UPDATE cs2.titulos_recebafacil_a_liberar 
							SET datapg='$pagamento', valorpg='$i_valor_titulo', juros='$i_juros_titulo'
						WHERE numboleto='$i_titulo'";
			$qr_comp = mysql_query($comp_sql,$conexao) or die ("ERRO: $comp_sql");
		}
	}else{
		$cont_nao++;
		$NAO_ENC .= espaco($i_numdoc,20).espaco('',3).espaco($i_titulo,20).' '.espaco($vencimento,10).espaco('',4).$i_valor_titulo."<br>";
	}

}


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
				$cmd              = trim(substr($lin,213,10));
			}
			if ( substr($lin,13,1) == 'U' ){
				$i_juros_titulo   = trim(substr($lin,17,15)/100);
				$i_juros_titulo2  = trim(substr($lin,122,15)/100);
				$i_total_recebido = $i_valor_titulo + ( $i_juros_titulo + $i_juros_titulo2 );
				$i_valor_titulo   = $i_total_recebido;
				
				# PESQUISA NA TABELA   TITULOS_RECEBAFACIL
				$sql = "SELECT	a.codloja,a.valor,a.numdoc,a.vencimento,b.nomefantasia,b.cidade,
								a.cpfcnpj_devedor, c.Nom_Nome, b.banco_cliente, b.agencia_cliente,
								b.conta_cliente, b.cpfcnpj_doc, b.nome_doc, a.tp_titulo, a.datapg,
								MID(d.logon,1,LOCATE('S', d.logon) - 1) AS logon, a.emissao
						FROM cs2.titulos_recebafacil a
						INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
						LEFT OUTER JOIN base_inform.Nome_Brasil c ON a.cpfcnpj_devedor = c.Nom_CPF
						INNER JOIN cs2.logon d ON b.codloja = d.codloja
						WHERE a.numboleto='$i_titulo'
						GROUP BY a.numboleto";
				$qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
				$qtd  = mysql_num_rows($qr_sql);
				if ( $qtd > 0 ){
					$dados = mysql_fetch_array($qr_sql);
					$tp_tit = $dados['tp_titulo'];
					$Valor_Bol = $dados['valor'];
					$vencimento = $dados['vencimento'];
					$codloja = $dados['codloja'];
					$logon = $dados['logon'];
					$nomefantasia = $dados['nomefantasia'];
					$emissao = $dados['emissao'];

					# Verificando se o titulo foi pago por compensa��o
					# 00 - Pago e o dinheiro foi liberado
					# Diferente de 00 - N�o baixar o pagamento e gravar em uma tabela para libera��o do Administrativo
					
					if ( $cmd == '00' or $cmd == '' ){
						# Atualizando tabela titulo_recebafacil
						$sql_update = "	UPDATE cs2.titulos_recebafacil 
										SET datapg = '$pagamento', valorpg='$i_total_recebido',juros='$i_juros_titulo' 
										WHERE numboleto='$i_titulo'";
						$qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
						# Verificando se o titulo j� est� na tabela Conta Corrente Receba F�cil
						$sql_cta = "SELECT count(*) qtd FROM cs2.contacorrente_recebafacil WHERE numboleto = '$i_titulo'";
						$qr_cta = mysql_query($sql_cta,$conexao) or die ("ERRO: $sql_cta");
						$qtd = mysql_result($qr_cta,0,'qtd');
						if ( empty($qtd) ) $qtd = '0';
						if ( $qtd == 0 ){
							$Text = ' Titulo Receb. Bco : '.$dados['cpfcnpj_devedor'].' '.$dados['Nom_Nome'];
							$Text = str_replace(chr(39),'',$Text);
							$Text = str_replace(chr(47),'',$Text);
							
							// verifico o saldo do cliente
							$sql_sdo = "SELECT saldo FROM cs2.contacorrente_recebafacil
										WHERE codloja='$codloja' order by id desc limit 1";
							$qr_sdo = mysql_query($sql_sdo,$conexao) or die ("ERRO: $sql_sdo");
							$saldo = mysql_result($qr_sdo,0,'saldo');
							if ( empty($saldo) ) $saldo = '0';
							$tx_adm = 0;
							$tx_adm = ( $i_valor_titulo * 0.025 );
							$saldo += $i_valor_titulo;
                                                        
                                                        $time_inicial = strtotime('2017-01-01');
                                                        $time_final = strtotime($emissao);
                                                        // Calcula a diferença de segundos entre as duas datas:
                                                        $diferenca = $time_final - $time_inicial; // 19522800 segundos
                                                        // Calcula a diferença de dias
                                                        $diferenca_dias = (int) floor($diferenca / (60 * 60 * 24));
                                                        // Após 01/01/2017
                                                        if ( $diferenca >= 0 ) $tarifa = 2.25;
                                                        else $tarifa = 4.95;

							$saldo = ($saldo - ( $tarifa + $tx_adm) );
                                                        
							$sql_ins = "INSERT INTO cs2.contacorrente_recebafacil(
											numboleto,codloja,data,discriminacao,venc_titulo,
											valor_titulo,valor,saldo,datapgto,tx_adm, tarifa_bancaria)
										SELECT numboleto, a.codloja , now() , '$Text' , a.vencimento, a.valor,
											'$i_valor_titulo' , ' $saldo' , '$pagamento', '$tx_adm', '$tarifa'
										FROM cs2.titulos_recebafacil a
										INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
										WHERE a.numboleto='$i_titulo'";
							$qr_ins = mysql_query($sql_ins,$conexao) or die ("ERRO: $sql_ins");
						}
						$RECEBA_F .= '<tr><td>'.substr($logon.' - '.$nomefantasia,0,35).'</td>';
						$RECEBA_F .= '<td>'.$dados['cpfcnpj_devedor'].'</td>';
						$RECEBA_F .= '<td>'.substr($dados['Nom_Nome'],0,35).'</td>';
						$RECEBA_F .= '<td width="120">'.$i_titulo.'</td>';
						$RECEBA_F .= '<td width="120">'.$vencimento.'</td>';
						$RECEBA_F .= '<td width="120" align="right">'.number_format($dados['valor'],2,',','.').'</td>';
						$RECEBA_F .= '<td width="120" align="right">'.number_format($i_valor_titulo,2,',','.').'</td></tr>';
					}else{
						# Titulo pago com Cheque, enviar para a tabela para libera��o do Wellington
						$comp_sql = "INSERT INTO cs2.titulos_recebafacil_a_liberar 
									 SELECT * FROM cs2.titulos_recebafacil where numboleto='$i_titulo'";
						$qr_comp = mysql_query($comp_sql,$conexao) or die ("ERRO: $comp_sql");
						$comp_sql = "UPDATE cs2.titulos_recebafacil_a_liberar 
										SET datapg='$pagamento', valorpg='$i_valor_titulo', juros='$i_juros_titulo'
									WHERE numboleto='$i_titulo'";
						$qr_comp = mysql_query($comp_sql,$conexao) or die ("ERRO: $comp_sql");
					}
				}else{
					# Titulo nao encontrado na tabela [ TITULOS_RECEBAFACIL ]
					// Verificando na tabela [ TITULOS_RECEBAFACIL_EXCUIDOS] se tiver, nover o boleto para [TITULO_RECEBAFACIL] e efetuar a baixa
					
					$sql_tit_exc = "SELECT count(*) as qtd  FROM cs2.titulos_recebafacil_excluidos WHERE numboleto='$i_titulo'";
					$qry_tit_exc = mysql_query( $sql_tit_exc, $conexao ) or die("ERRO : $sql_tit_exc");
					$qtd_tit_exc = mysql_result($qry_tit_exc,0,'qtd');
					if ( $qtd_tit_exc > 0 ){
						$sql_ajuda = "INSERT INTO cs2.titulos_recebafacil
									  SELECT * FROM cs2.titulos_recebafacil_excluidos
									  WHERE numboleto='$i_titulo'";
						$qry_ajuda = mysql_query($sql_ajuda,$conexao) or die("ERRO : $sql_ajuda");
						
						$sql_ajuda = "DELETE FROM cs2.titulos_recebafacil_excluidos
									  WHERE numboleto='$i_titulo'";
						$qry_ajuda = mysql_query($sql_ajuda,$conexao) or die("ERRO : $sql_ajuda");
						
						quita_titulo($i_titulo, $i_valor_titulo, $i_total_recebido, $i_juros_titulo, $pagamento);
						
					}else{
					
						$cont_nao++;
						$NAO_ENC .= espaco($i_numdoc,20).espaco('',3).espaco($i_titulo,20).' '.espaco($vencimento,10).espaco('',4).$i_valor_titulo."<br>";
						
					}
				}
			} // comando U
		} // segunda linha em diante
	} // For
	$saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
	$saida .= "Inform System".espaco('','15')."Cobranca Bancaria".espaco('','10')."B. BRASIL".espaco('','9')."Data Pgto: $pagamento<br><br>";
	
	if ( !empty($fraude) ){
		echo "FRAUDE NO PAGAMENTO===========================================================================<br><br>";
		echo $fraude;
	}
	
	if ( $cont_nao > 0 ){
		$saida  .= "TITULOS NAO ENCONTRADO =======================================================================<br><br>";
		$saida  .= espaco('No. Documento',20).espaco('',3).espaco('No. Boleto',20).' '.espaco('Vencimento',10).espaco('',4)."Vlr Titulo<br><br>";
		$saida  .= "$NAO_ENC<br>";
		$saida  .= "==============================================================================================<br>";
		$saida  .= "Listado(s) ".colocazeros($cont_nao,4)." Titulo(s)<br><br>";
	}
	$saida .= "TITULOS QUITADOS =============================================================================<br><br>";
	$saida .= espaco('No. Docum.',17).espaco('No. Boleto',11).' Vencimento '.espaco('Vr Titulo',13)." Nome/Cidade <br><br>";
	$saida .= "$QUITADO";
	$saida .= "==============================================================================================<br>";
	$saida .= "Listado(s) ".colocazeros($cont_liq,4)." Titulo(s)     Totalizando : ".number_format($valor_total_quitado,2,',', '.')."<br><br>";
	if ( strlen($RECEBA_F) > 0 ){
		$saida  .= "<br><br>";
		$saida  .= "TITULOS RECEBA-F�CIL =========================================================================<br><br>";
		$saida  .= "
			<table>
				$RECEBA_F
			</table>";
	}	
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
		$mail->Username = "cpd@informsystem.com.br"; //EMAIL PARA SMTP AUTENTICADO
		$mail->Password = "#9%kxP*-11"; //SENHA DO EMAIL PARA SMTP AUTENTICADO
		$mail->From = "cpd@informsystem.com.br"; //E-MAIL DO REMETENTE 
		$mail->FromName = "CPD - InformSystem"; //NOME DO REMETENTE
		$mail->AddAddress("administrativo@informsystem.com.br","Administrativo - InformSystem"); //E-MAIL DO DESINAT�RIO, NOME DO DESINAT�RIO 
		$mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
		$mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
		$mail->Subject = "Retorno Cobranca BBRASIL"; //ASSUNTO DA MENSAGEM
		$mail->Body = $saida; //MENSAGEM NO FORMATO HTML
		$mail->Send();
		echo "ATEN��O >>  Este relatorio foi enviado para o EMAIL : administrativo@informsystem.com.br</p>\n";
	} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
	}

?>