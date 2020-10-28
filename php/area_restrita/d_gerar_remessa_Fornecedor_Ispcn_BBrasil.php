<?php

	# Author:  Luciano Mancini
	# M�dulo:  Remessa Fornecedor
	# Finalidade: Gerar o arquivo de cr�dito a serem enviados ao Banco do Brasil para os cliente CREDI�RIO/RECUPERE
	
	include("../../validar2.php");

	global $conexao,$arquivo;
	conecex();


	function ver_autorizacao($codloja,$cpfcnpj_doc,$banco,$agencia,$conta){
		
		global $conexao;
		
		$sql_ver = "SELECT count(*) qtd FROM cs2.autorizacao_conta
					WHERE cod_loja = '$codloja' 
							AND cpf_cnpj = '$cpfcnpj_doc'
							AND banco = '$banco' 
							AND agencia = '$agencia' 
							AND conta = '$conta'
							AND status = 'A'";
		$qry_ver = mysql_query($sql_ver,$conexao);
		$qtd = mysql_result($qry_ver,0, 'qtd');
		if ( $qtd == 0 ) 
			return 'NEG';
		else
			return 'OK';
		
	}
	
	$sql_registro = "select subdate(now(), interval 60 day) data;";
	$qry_registro = mysql_query($sql_registro,$conexao);
	$data_limite = mysql_result($qry_registro,0,'data');
	$data_limite = substr($data_limite,0,10);
	
	$registros = '';

	$linha_ted = "<p></p>
					<table width='1000' align='center'>
					<tr bgcolor='#CCCCCC' style='font-size:12px' >
						<td colspan='8'>ID</td>
						<td>Cliente</td>
						<td>Titular</td>
						<td width='20'>Banco</td>
						<td width='50'>Agencia</td>
						<td width='100'>Conta</td>
						<td width='100'>Valor</td>
						<td width='100'>Data Afilia&ccedil;&atilde;o</td>
					</tr>";
					
	$blq_repasse_inadimplencia = '';
	$NumeroArquivo = 1;
	$NumeroLote = 0;
	$totccbb = 0;
	$totcpbb = 0;
	$totccou = 0;
	$totcpou = 0;
	// GERAR REGISTRO HEADER DO ARQUIVO
	$Registro  = colocazeros('001',3);                // 001 a 003 - C�digo do banco
	$Registro .= '0000';                              // 004 a 007 - Lote de servi�o
	$Registro .= '0';                                 // 008 a 008 - Tipo de registro - Registro header de arquivo
	$Registro .= colocaespacos('',9);                 // 009 a 017 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= '2';                                 // 018 a 018 - Tipo de inscri��o do cedente
	$Registro .= '12244595000100';                    // 019 a 032 - N�mero de inscri��o do cedente
	$Registro .= '0009750160126       ';              // 033 a 052 - C�digo do conv�nio no banco
	$Registro .= '03051';                             // 053 a 057 - C�digo da ag�ncia do cedente
	$Registro .= '1';                                 // 058 a 058 - D�gito da ag�ncia do cedente
	$Registro .= colocazeros('23096',12);             // 059 a 070 - N�mero da conta do cedente
	$Registro .= '0';                                 // 071 a 071 - D�gito da conta do cedente
	$Registro .= ' ';                                 // 072 a 072 - D�gito verificador da ag�ncia / conta
	$Registro .= colocaespacos('ISPCN ADMINISTRACAO DE COBRANC',30);  // 073 a 102 - Nome do cedente
	$Registro .= colocaespacosdir('BANCO DO BRASIL',30);  // 103 a 132 - Nome do banco
	$Registro .= colocaespacos('',10);                    // 133 a 142 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= '1';                                     // 143 a 143 - C�digo de Remessa (1) / Retorno (2)
	$data = date('dmY');
	$Registro .= $data;                                   // 144 a 151 - Data do de gera��o do arquivo
	$hora = date("His");
	$Registro .= substr($hora,0,6);                       // 152 a 157 - Hora de gera��o do arquivo
	$Registro .= colocazeros($NumeroArquivo,6);           // 158 a 163 - N�mero seq�encial do arquivo
	$Registro .= '030';                                   // 164 a 166 - N�mero da vers�o do layout do arquivo
	$Registro .= '00000';                                 // 167 a 171 - Densidade de grava��o do arquivo (BPI)
	$Registro .= colocaespacos('',20);                    // 172 a 191 - Uso reservado do banco
	$Registro .= colocaespacos('',20);                    // 192 a 211 - Uso reservado da empresa
	$Registro .= colocaespacos('',11);                    // 212 a 222 - 11 brancos
	$Registro .= 'CSP';                                   // 223 a 225 - 'CSP'
	$Registro .= '000';                                   // 226 a 228 - Uso exclusivo de Vans
	$Registro .= colocaespacos('',2);                     // 229 a 230 - Tipo de servico
	$Registro .= colocaespacos('',10);                    // 231 a 240 - titulo em carteira de cobranca
	$totlinha++;
	$registros .= "$Registro\n";
	
	# CORRENTISTA BANCO DO BRASIL - CONTA CORRENTE
	# REGISTRO HEADER DO LOTE 0001
	$NumeroLote++;
	$Registro = '001';                                // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);           // 004 a 007 - N�mero do lote de servi�o
	$Registro .= '1';                                  // 008 a 008 - Tipo do registro - Registro header de lote
	$Registro .= 'C';                                  // 009 a 009 - Tipo de opera��o: C - CREDITO
	$Registro .= '20';                                 // 010 a 011 - Tipo de servi�o: 20 (Pagamento Fornecedor)
	$Registro .= '01';                                 // 012 a 013 - Forma de lan�amento: 01 (Credito em Conta Corrente)
	$Registro .= '020';                                // 014 a 016 - N�mero da vers�o do layout do lote
	$Registro .= ' ';                                  // 017 a 017 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= '2';                                  // 018 a 018 - Tipo de inscri��o do cedente
	$Registro .= colocazeros('12244595000100',14);     // 019 a 032 - N�mero de inscri��o do cedente
	$Registro .= '0009750160126       ';               // 033 a 052 - C�digo do conv�nio no banco
	$Registro .= '03051';                              // 053 a 057 - C�digo da ag�ncia do cedente
	$Registro .= '1';                                  // 058 a 058 - D�gito da ag�ncia do cedente
	$Registro .= '000000023096';                       // 059 a 700 - N�mero da conta do cedente
	$Registro .= '0';                                  // 071 a 071 - D�gito da conta do cedente
	$Registro .= ' ';                                                   // 072 a 073 - D�gito verificador da ag�ncia / conta
	$Registro .= colocaespacosdir('ISPCN ADMINISTRACAO DE COBRANC',30); // 073 a 102 - Nome do cedente
	$Registro .= colocaespacos('',40);                                  // 103 a 142 - Mensagem 1 para todos os boletos do lote
	$Registro .= colocaespacosdir('AV. MARECHAL FLORIANO PEIXOTO',30);  // 143 a 172 - Nome da rua, av. p�a, etc
	$Registro .= '00306';                                                // 173 a 177 - N�mero do local
	$Registro .= colocaespacosdir('SL 11',15);                          // 178 a 192 - Complemento
	$Registro .= colocaespacosdir('CURITIBA',20);                       // 193 a 212 - Cidade
	$Registro .= '80010';                                               // 213 a 217 - CEP
	$Registro .= '130';                                                 // 218 a 220 - Complemento do CEP
	$Registro .= 'PR';                                                  // 221 a 222 - Sigla do Estado
	$Registro .= colocaespacos('',8);                                   // 223 a 230 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= colocaespacos('',10);                                  // 231 a 240 - C�digo de Ocorr�ncias p/ retorno
	$totlinha++;
	$registros .= "$Registro\n";
	# Selecionando do Clientes	- CONTA CORRENTE
    $sql = "SELECT  DISTINCT(a.codloja) codloja, mid(b.razaosoc,1,30) razaosoc, b.banco_cliente, b.agencia_cliente, 
					b.conta_cliente, cpfcnpj_doc, upper(mid(b.nome_doc,1,30)) nome_doc, 
					'8' as vr_repasse, b.tpconta,
					date_format(b.dt_cad,'%d/%m/%Y') as dt_cad
            FROM cs2.contacorrente_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja 
            WHERE b.banco_cliente = 1 
				  AND b.tpconta = 1 
				  AND b.sitcli = 0 
				  AND ( ( b.pendencia_contratual = 0) or ( b.pendencia_contratual = 1 and b.dt_cad >= '$data_limite') )
			ORDER BY b.nome_doc";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		while($registro = mysql_fetch_array($qr_sql)){
			$codloja = $registro['codloja'];
			$razaosoc = $registro['razaosoc'];
			$banco_cliente = $registro['banco_cliente'];
			$agencia_cliente = $registro['agencia_cliente'];
			$conta_cliente = $registro['conta_cliente'];
			$cpfcnpj_doc = $registro['cpfcnpj_doc'];
			$nome_doc = $registro['nome_doc'];
			$vr_repasse = $registro['vr_repasse'];
			$tpconta = $registro['tpconta'];
			$dt_cad = $registro['dt_cad'];
			
			$id   = str_pad($codloja,6,' ',STR_PAD_RIGHT);
			$emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
			$tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
			$bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
			$age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
			$cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);

			
			# verificando o logon do cliente
			$sql2 = 'SELECT CAST(MID(logon,1,6) AS UNSIGNED) logon FROM cs2.logon WHERE codloja='.$codloja.' LIMIT 1';
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$array_sql2 = mysql_fetch_array($xqr_sql);
			$logon = $array_sql2["logon"];
			# verificando o saldo na conta corrente do cliente
			$sql2 = 'SELECT saldo FROM cs2.contacorrente_recebafacil WHERE codloja='.$codloja.' ORDER BY id DESC LIMIT 1';
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$array_sql2=mysql_fetch_array($xqr_sql);
			$saldo = $array_sql2["saldo"];
			if ( empty($saldo) ) $saldo = 0;
			$saldo -= $vr_repasse;
			$vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
			$vlr  = number_format($vlr,2,',', '.');
			
			$valor_ant = verifica_emprestimo($codloja);
			# j� que est� inadimplente ZERO o saldo para n�o enviar
			if ( $valor_ant > 0 ){
				$linha_sup_3000_rec .= "<tr style='font-size:12px'>
											<td>$id</td>
											<td>$emp</td>
											<td>$tit</td>
											<td>$bco</td>
											<td>$age</td>
											<td>$cta</td>
											<td>$vlr</td>
											<td>$dt_cad</td>
											<td>Atraso Antecipacao</td>
										</tr>";
				$autoriza = 'N';
				$saldo = 0;
			}
			
			if ( $saldo > 0 ){
				# verificando se o cliente possui titulo em aberto, se tiver n�o gera repasse para ele.
				$xdata = date('Y-m-d');
				# verificando se o cliente possui titulo em aberto, se tiver n�o gera repasse para ele.
				$sql2 = "SELECT count(*) qtd FROM cs2.titulos
						 WHERE codloja='$codloja' and datapg is null and vencimento < '$xdata'";
				$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
				$array_sql2=mysql_fetch_array($xqr_sql);
				$xqtd = $array_sql2["qtd"];
				if ( $xqtd > 0 ){
					# Cliente INADIMPLENTE com as mensalidades
					$blq_repasse_inadimplencia .= colocaespacos($logon,5).' '.colocaespacosdir($razaosoc,30).'   '.
	                              colocaespacos($tpconta,15).' '.
	                              colocazeros($banco_cliente,3).' '.
	                              colocaespacos($agencia_cliente,6).'   '.
	                              colocaespacos($conta_cliente,12).' '.
	                              colocaespacos($cpfcnpj_doc,14).'  '.
	                              colocaespacosdir($nome_doc,30).'   '.
	                              colocaespacos($vr_repasse,10).'  '.
	                              colocaespacos($saldo,12)." I \n";
	                $totinadimplente += $saldo;
				}else{
					
# Cliente em dia com suas mensalidades
						
						$id   = str_pad($codloja,6,' ',STR_PAD_RIGHT);
						$emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
						$tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
						$bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
						$age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
						$cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);
						$vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
						$vlr  = number_format($vlr,2,',', '.');
						
						if ( $saldo > 2000 ){
						
							if ( ($cpfcnpj_doc != $insc) && ($cpfcnpj_doc != $cpfsocio1) && ($cpfcnpj_doc != $cpfsocio2) ){

								$retorno = ver_autorizacao($codloja,$cpfcnpj_doc,$banco_cliente,$agencia_cliente,$conta_cliente);
								
								if ( $retorno == "NEG" ){
									$linha_sup_3000_rec .= "<tr style='font-size:12px'>
																<td>$id</td>
																<td>$emp</td>
																<td>$tit</td>
																<td>$bco</td>
																<td>$age</td>
																<td>$cta</td>
																<td>$vlr</td>
																<td>$dt_cad</td>
															</tr>";
									$autoriza = 'N';
								}else{
									// autorizado, a conta est� cadastrada para receber
									$autoriza = 'S';
								}
							}
						}
						
						if ( $saldo >= 5000 ){

							if ( $autoriza == 'S' ){
								$linha_ted .= "<tr style='font-size:12px'>
									<td>$id</td>
									<td>$emp</td>
									<td>$tit</td>
									<td>$bco</td>
									<td>$age</td>
									<td>$cta</td>
									<td>$vlr</td>
									<td>$dt_cad</td>
									</tr>";
							}
						}
						
						if ( $autoriza == 'S' ){

							$totlinha++;
							$NumeroRegistro++;
							$CODLOJA = '99999'.$codloja;
							// REGISTRO DETALHES DO LOTE
							$Registro = '001';                          // 001 a 003 - C�digo do banco
							$Registro .= colocazeros($NumeroLote,4);    // 004 a 007 - N�mero do lote
							$Registro .= '3';                           // 008 q 008 - Tipo do registro: Registro detalhe do lote
							$Registro .= colocazeros($NumeroRegistro,5); // 009 a 013 - N�mero seq�encial do registro no 
																// lote - Cada t�tulo tem 2 registros (P e Q)
							$Registro .= 'A';          // 014 a 014 - C�digo do segmento do registro detalhe
							$Registro .= '0';          // 015 a 015 - Tipo de movimento  0-Inclusao
							$Registro .= '00';         // 016 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
							$Registro .= '000';        // 018 a 020 - C�digo da camara centralizadora  
											  //       700 - DOC
											  //       018 - TED   
											  //       Quando BB Informar "000"
							$Registro .= colocazeros($banco_cliente,3);          // 021 a 023 - C�digo do Banco do Favorecido
							$Registro .= colocazeros(substr($agencia_cliente,0,strlen($agencia_cliente)-1),5);  // 024 a 028 - C�digo da Agencia do Favorecido
							$Registro .= colocazeros(substr($agencia_cliente,strlen($agencia_cliente)-1,1),1);  // 029 a 029 - Digito Verificado da Agencia do Favorecido
							$Registro .= colocazeros(substr($conta_cliente,0,strlen($conta_cliente)-1),12);   // 030 a 041 - C�digo da Conta Corrente do Favorecido
							$Registro .= colocazeros(substr($conta_cliente,strlen($conta_cliente)-1,1),1);    // 042 a 042 - Digito da Conta Corrente do Favorecido
							$Registro .= ' ';                              // 043 a 043 - Digito verificador da AG/CONTA
							$Registro .= colocaespacosdir($nome_doc,30);   // 044 a 073 - Nome do Favorecido

							$Registro .= colocaespacos('',20);             // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA -  DOC,NF,NP,ETC
					
						# data atual mais 1 dia
						$data = gmdate("dmY", time()+(3600*27));
						$Registro .= $data;                           // 094 a 101 - Data para lan�amento do Debito ou Credito
						$Registro .= 'BRL';                           // 102 a 104 - Tipo de Moeda
						$Registro .= colocazeros('',15);              // 105 a 119 - QTD de moeda
						$valor = $saldo * 100;
						$valor = str_replace(',','',$valor);
						$valor = str_replace('.','',$valor);
						$Registro .= colocazeros($valor,15);          // 120 a 134 - Valor para DEBITAR ou CREDITAR
						$Registro .= colocaespacos('',20);            // 135 a 154 - Num DOC ATRIBUIDO P/ BANCO
						$Registro .= '00000000';                      // 155 a 162 - Data real da efetiva��o do lan�amento
						$Registro .= '000000000000000';               // 163 a 177 - Valor real da efetiva��o do lan�amento
						$totccbb  += $saldo;
						$Registro .= colocaespacosdir($CODLOJA,40);    // codloja do associado  // 178 a 217 - Outras informa��es
						$Registro .= colocaespacos('',12);             // 218 a 229 - USO EXCLUSIVO FEBRABAN/CNAB
						$Registro .= '0';                              // 230 a 230 - Aviso ao favorecido  0-nao  1-sim
						$Registro .= colocaespacos('',10);             // 231 a 240 - CODIGOS DAS OCORRENCIAS P/ RETORNO
						$registros .= "$Registro\n";
					}
				}
			}
		}
	}
	# REGISTRO TRAILLER DO LOTE 0001
	$Registro = '';
	$Registro .= '001';                                    // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
	$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);                      // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
	$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
	$valor = $totccbb * 100;
	$total_geral += $totccbb;
	$valor = str_replace(',','',$valor);
	$valor = str_replace('.','',$valor);
	
	
	$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores DEB/CRED
	$Registro .= '000000000000000000';                     // 042 a 059 - Somat�ria de quant de moedas
	$Registro .= colocaespacos('',171);                    // 060 a 230 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
	$totlinha++;
	$registros .= "$Registro\n";
	
	# FIM DO PROCESSAMENTO DA CONTA CORRENTE DO BANCO DO BRASIL
	
	# CORRENTISTA BANCO DO BRASIL - CONTA POUPANCA
	# REGISTRO HEADER DO LOTE 0002
	$NumeroLote++;
	$Registro = '001';                                 // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);           // 004 a 007 - N�mero do lote de servi�o
	$Registro .= '1';                                  // 008 a 008 - Tipo do registro - Registro header de lote
	$Registro .= 'C';                                  // 009 a 009 - Tipo de opera��o: C - CREDITO
	$Registro .= '98';                                 // 010 a 011 - Tipo de servi�o: 98 (Pagamento Diversos)
	$Registro .= '05';                                 // 012 a 013 - Forma de lan�amento: 05 (Credito em Conta Poupan�a)
	$Registro .= '020';                                // 014 a 016 - N�mero da vers�o do layout do lote
	$Registro .= ' ';                                  // 017 a 017 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= '2';                                  // 018 a 018 - Tipo de inscri��o do cedente
	$Registro .= colocazeros('12244595000100',14);     // 019 a 032 - N�mero de inscri��o do cedente
	$Registro .= '0009750160126       ';               // 033 a 052 - C�digo do conv�nio no banco
	$Registro .= '03051';                              // 053 a 057 - C�digo da ag�ncia do cedente
	$Registro .= '1';                                  // 058 a 058 - D�gito da ag�ncia do cedente
	$Registro .= '000000023096';                       // 059 a 700 - N�mero da conta do cedente
	$Registro .= '0';                                  // 071 a 071 - D�gito da conta do cedente
	$Registro .= ' ';                                                   // 072 a 073 - D�gito verificador da ag�ncia / conta
	$Registro .= colocaespacosdir('ISPCN ADMINISTRACAO DE COBRANC',30); // 073 a 102 - Nome do cedente
	$Registro .= colocaespacos('',40);                                  // 103 a 142 - Mensagem 1 para todos os boletos do lote
	$Registro .= colocaespacosdir('AV. MARECHAL FLORIANO PEIXOTO',30);  // 143 a 172 - Nome da rua, av. p�a, etc
	$Registro .= '00306';                                                // 173 a 177 - N�mero do local
	$Registro .= colocaespacosdir('SL 11',15);                          // 178 a 192 - Complemento
	$Registro .= colocaespacosdir('CURITIBA',20);                       // 193 a 212 - Cidade
	$Registro .= '80010';                                               // 213 a 217 - CEP
	$Registro .= '130';                                                 // 218 a 220 - Complemento do CEP
	$Registro .= 'PR';                                                  // 221 a 222 - Sigla do Estado
	$Registro .= colocaespacos('',8);                                   // 223 a 230 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= colocaespacos('',10);                                  // 231 a 240 - C�digo de Ocorr�ncias p/ retorno
	$totlinha++;
	$registros .= "$Registro\n";
	$NumeroRegistro = 0;
	
	# Selecionando do Clientes com CONTA POUPAN�A	
    $sql = "SELECT  DISTINCT(a.codloja) codloja, mid(b.razaosoc,1,30) razaosoc, b.banco_cliente, b.agencia_cliente, 
					b.conta_cliente, cpfcnpj_doc, upper(mid(b.nome_doc,1,30)) nome_doc, 
					'8' as vr_repasse, b.tpconta,
					date_format(b.dt_cad,'%d/%m/%Y') as dt_cad 
            FROM cs2.contacorrente_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja 
            WHERE b.banco_cliente = 1 
				  AND b.tpconta = 2 
				  AND b.sitcli = 0 
				  AND ( ( b.pendencia_contratual = 0) or ( b.pendencia_contratual = 1 and b.dt_cad >= '$data_limite') )";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		while($registro = mysql_fetch_array($qr_sql)){
			$codloja = $registro['codloja'];
			$razaosoc = $registro['razaosoc'];
			$banco_cliente = $registro['banco_cliente'];
			$agencia_cliente = $registro['agencia_cliente'];
			$conta_cliente = $registro['conta_cliente'];
			$cpfcnpj_doc = $registro['cpfcnpj_doc'];
			$nome_doc = $registro['nome_doc'];
			$vr_repasse = $registro['vr_repasse'];
			$tpconta = $registro['tpconta'];
			$dt_cad = $registro['dt_cad'];
			# verificando o logon do cliente
			$sql2 = 'SELECT CAST(MID(logon,1,6) AS UNSIGNED) logon FROM cs2.logon WHERE codloja='.$codloja.' LIMIT 1';
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$array_sql2 = mysql_fetch_array($xqr_sql);
			$logon = $array_sql2["logon"];

			# verificando o saldo na conta corrente do cliente
			$sql2 = 'SELECT saldo FROM cs2.contacorrente_recebafacil WHERE codloja='.$codloja.' ORDER BY id DESC LIMIT 1';
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$array_sql2=mysql_fetch_array($xqr_sql);
			$saldo = $array_sql2["saldo"];
			if ( empty($saldo) ) $saldo = 0;
			$saldo -= $vr_repasse;
			
			$valor_ant = verifica_emprestimo($codloja);
			# j� que est� inadimplente ZERO o saldo para n�o enviar
			if ( $valor_ant > 0 ) $saldo = 0;
			
			if ( $saldo > 0 ){
				# verificando se o cliente possui titulo em aberto, se tiver n�o gera repasse para ele.
				$xdata = date('Y-m-d');
				# verificando se o cliente possui titulo em aberto, se tiver n�o gera repasse para ele.
				$sql2 = "SELECT count(*) qtd FROM cs2.titulos
						 WHERE codloja='$codloja' and datapg is null and vencimento < '$xdata'";
				$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
				$array_sql2=mysql_fetch_array($xqr_sql);
				$xqtd = $array_sql2["qtd"];
				if ( $xqtd > 0 ){
					# Cliente INADIMPLENTE com as mensalidades
					$blq_repasse_inadimplencia .= colocaespacos($logon,5).' '.colocaespacosdir($razaosoc,30).'   '.
	                              colocaespacos($tpconta,15).' '.
	                              colocazeros($banco_cliente,3).' '.
	                              colocaespacos($agencia_cliente,6).'   '.
	                              colocaespacos($conta_cliente,12).' '.
	                              colocaespacos($cpfcnpj_doc,14).'  '.
	                              colocaespacosdir($nome_doc,30).'   '.
	                              colocaespacos($vr_repasse,10).'  '.
	                              colocaespacos($saldo,12)." I \n";
	                $totinadimplente += $saldo;
				}else{
					# Cliente em dia com suas mensalidades
						
					$id   = str_pad($codloja,6,' ',STR_PAD_RIGHT);
					$emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
					$tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
					$bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
					$age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
					$cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);
					$vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
					$vlr  = number_format($vlr,2,',', '.');
					
					if ( $saldo > 2000 ){
						
						if ( ($cpfcnpj_doc != $insc) && ($cpfcnpj_doc != $cpfsocio1) && ($cpfcnpj_doc != $cpfsocio2) ){

							$retorno = ver_autorizacao($codloja,$cpfcnpj_doc,$banco_cliente,$agencia_cliente,$conta_cliente);
								
							if ( $retorno == "NEG" ){
								$linha_sup_3000_rec .= "<tr style='font-size:12px'>
															<td>$id</td>
															<td>$emp</td>
															<td>$tit</td>
															<td>$bco</td>
															<td>$age</td>
															<td>$cta</td>
															<td>$vlr</td>
															<td>$dt_cad</td>
														</tr>";
								$autoriza = 'N';
							}else{
								// autorizado, a conta est� cadastrada para receber
								$autoriza = 'S';
							}
						}
					}
						
					if ( $saldo >= 5000 ){

						if ( $autoriza == 'S' ){
							$linha_ted .= "<tr style='font-size:12px'>
								<td>$id</td>
								<td>$emp</td>
								<td>$tit</td>
								<td>$bco</td>
								<td>$age</td>
								<td>$cta</td>
								<td>$vlr</td>
								<td>$dt_cad</td>
								</tr>";
						}
					}
					
					if ( $autoriza == 'S' ){
						# Cliente em dia com suas mensalidades
						$totlinha++;
						$NumeroRegistro++;
						$CODLOJA = '99999'.$codloja;
						// REGISTRO DETALHES DO LOTE
						$Registro = '001';                          // 001 a 003 - C�digo do banco
						$Registro .= colocazeros($NumeroLote,4);    // 004 a 007 - N�mero do lote
						$Registro .= '3';                           // 008 q 008 - Tipo do registro: Registro detalhe do lote
						$Registro .= colocazeros($NumeroRegistro,5); // 009 a 013 - N�mero seq�encial do registro no 
																	// lote - Cada t�tulo tem 2 registros (P e Q)
						$Registro .= 'A';          // 014 a 014 - C�digo do segmento do registro detalhe
						$Registro .= '0';          // 015 a 015 - Tipo de movimento  0-Inclusao
						$Registro .= '00';         // 016 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
						$Registro .= '000';        // 018 a 020 - C�digo da camara centralizadora  
												  //       700 - DOC
												  //       018 - TED   
												  //       Quando BB Informar "000"
						$Registro .= colocazeros($banco_cliente,3);          // 021 a 023 - C�digo do Banco do Favorecido
						$Registro .= colocazeros(substr($agencia_cliente,0,strlen($agencia_cliente)-1),5);  // 024 a 028 - C�digo da Agencia do Favorecido
						$Registro .= colocazeros(substr($agencia_cliente,strlen($agencia_cliente)-1,1),1);  // 029 a 029 - Digito Verificado da Agencia do Favorecido
						$Registro .= colocazeros(substr($conta_cliente,0,strlen($conta_cliente)-1),12);   // 030 a 041 - C�digo da Conta Corrente do Favorecido
						$Registro .= colocazeros(substr($conta_cliente,strlen($conta_cliente)-1,1),1);    // 042 a 042 - Digito da Conta Corrente do Favorecido
						$Registro .= ' ';                              // 043 a 043 - Digito verificador da AG/CONTA
						$Registro .= colocaespacosdir($nome_doc,30);   // 044 a 073 - Nome do Favorecido
	
						$Registro .= colocaespacos('',20);             // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA -  DOC,NF,NP,ETC
						
						# data atual mais 1 dia
						$data = gmdate("dmY", time()+(3600*27));
						$Registro .= $data;                           // 094 a 101 - Data para lan�amento do Debito ou Credito
						$Registro .= 'BRL';                           // 102 a 104 - Tipo de Moeda
						$Registro .= colocazeros('',15);              // 105 a 119 - QTD de moeda
						$valor = $saldo * 100;
						$valor = str_replace(',','',$valor);
						$valor = str_replace('.','',$valor);
						$Registro .= colocazeros($valor,15);          // 120 a 134 - Valor para DEBITAR ou CREDITAR
						$Registro .= colocaespacos('',20);            // 135 a 154 - Num DOC ATRIBUIDO P/ BANCO
						$Registro .= '00000000';                      // 155 a 162 - Data real da efetiva��o do lan�amento
						$Registro .= '000000000000000';               // 163 a 177 - Valor real da efetiva��o do lan�amento
						$totcpbb  += $saldo;
						$Registro .= colocaespacosdir($CODLOJA,40);    // codloja do associado  // 178 a 217 - Outras informa��es
						$Registro .= colocaespacos('',12);             // 218 a 229 - USO EXCLUSIVO FEBRABAN/CNAB
						$Registro .= '0';                              // 230 a 230 - Aviso ao favorecido  0-nao  1-sim
						$Registro .= colocaespacos('',10);             // 231 a 240 - CODIGOS DAS OCORRENCIAS P/ RETORNO
						$registros .= "$Registro\n";
					}
				}
			}
		}
	}
	# REGISTRO TRAILLER DO LOTE 0002
	$Registro = '';
	$Registro .= '001';                                    // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
	$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);                      // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
	$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
	$valor = $totcpbb * 100;
	$total_geral += $totcpbb;
	$valor = str_replace(',','',$valor);
	$valor = str_replace('.','',$valor);
					
	$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores DEB/CRED
	$Registro .= '000000000000000000';                     // 042 a 059 - Somat�ria de quant de moedas
	$Registro .= colocaespacos('',171);                    // 060 a 230 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
	$totlinha++;
	$registros .= "$Registro\n";

	$NumeroRegistro = 0;
	
	/*
	
	// REGISTRO HEADER DO LOTE 0003
	// CORRENTISTA OUTROS BANCOS - CONTA CORRENTE (DOC/TED)
	$NumeroLote++;
	$Registro = '001';                                 // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);           // 004 a 007 - N�mero do lote de servi�o
	$Registro .= '1';                                  // 008 a 008 - Tipo do registro - Registro header de lote
	$Registro .= 'C';                                  // 009 a 009 - Tipo de opera��o: C - CREDITO
	$Registro .= '20';                                 // 010 a 011 - Tipo de servi�o: 20 (Pagamento Fornecedor)
	$Registro .= '03';                                 // 012 a 013 - Forma de lan�amento: 03 (Credito em Conta Corrente)
	$Registro .= '020';                                // 014 a 016 - N�mero da vers�o do layout do lote
	$Registro .= ' ';                                  // 017 a 017 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= '2';                                  // 018 a 018 - Tipo de inscri��o do cedente
	$Registro .= colocazeros('12244595000100',14);     // 019 a 032 - N�mero de inscri��o do cedente
	$Registro .= '0009750160126       ';               // 033 a 052 - C�digo do conv�nio no banco
	$Registro .= '03051';                              // 053 a 057 - C�digo da ag�ncia do cedente
	$Registro .= '1';                                  // 058 a 058 - D�gito da ag�ncia do cedente
	$Registro .= '000000023096';                       // 059 a 700 - N�mero da conta do cedente
	$Registro .= '0';                                  // 071 a 071 - D�gito da conta do cedente
	$Registro .= ' ';                                                   // 072 a 073 - D�gito verificador da ag�ncia / conta
	$Registro .= colocaespacosdir('ISPCN ADMINISTRACAO DE COBRANC',30); // 073 a 102 - Nome do cedente
	$Registro .= colocaespacos('',40);                                  // 103 a 142 - Mensagem 1 para todos os boletos do lote
	$Registro .= colocaespacosdir('AV. MARECHAL FLORIANO PEIXOTO',30);  // 143 a 172 - Nome da rua, av. p�a, etc
	$Registro .= '00306';                                                // 173 a 177 - N�mero do local
	$Registro .= colocaespacosdir('SL 11',15);                          // 178 a 192 - Complemento
	$Registro .= colocaespacosdir('CURITIBA',20);                       // 193 a 212 - Cidade
	$Registro .= '80010';                                               // 213 a 217 - CEP
	$Registro .= '130';                                                 // 218 a 220 - Complemento do CEP
	$Registro .= 'PR';                                                  // 221 a 222 - Sigla do Estado
	$Registro .= colocaespacos('',8);                                   // 223 a 230 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= colocaespacos('',10);                                  // 231 a 240 - C�digo de Ocorr�ncias p/ retorno
	$totlinha++;
	$registros .= "$Registro\n";

	$sql = "SELECT  DISTINCT(a.codloja) codloja, mid(b.razaosoc,1,30) razaosoc, b.banco_cliente, b.agencia_cliente, 
					b.conta_cliente, cpfcnpj_doc, upper(mid(b.nome_doc,1,30)) nome_doc, 
					'8' as vr_repasse, b.tpconta,
					date_format(b.dt_cad,'%d/%m/%Y') as dt_cad 
            FROM cs2.contacorrente_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja 
            WHERE b.banco_cliente <> 1 
				  AND b.tpconta = 1 
				  AND b.sitcli = 0 
				  AND ( ( b.pendencia_contratual = 0) or ( b.pendencia_contratual = 1 and b.dt_cad >= '$data_limite') )
			order by b.banco_cliente";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		while($registro = mysql_fetch_array($qr_sql)){
			$codloja = $registro['codloja'];
			$razaosoc = $registro['razaosoc'];
			$banco_cliente = $registro['banco_cliente'];
			$agencia_cliente = $registro['agencia_cliente'];
			$conta_cliente = $registro['conta_cliente'];
			$cpfcnpj_doc = $registro['cpfcnpj_doc'];
			$nome_doc = $registro['nome_doc'];
			$vr_repasse = $registro['vr_repasse'];
			$tpconta = $registro['tpconta'];
			$dt_cad = $registro['dt_cad'];	
			# verificando o logon do cliente
			$sql2 = 'SELECT CAST(MID(logon,1,6) AS UNSIGNED) logon FROM cs2.logon WHERE codloja='.$codloja.' LIMIT 1';
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$array_sql2 = mysql_fetch_array($xqr_sql);
			$logon = $array_sql2["logon"];
			# verificando o saldo na conta corrente do cliente
			$sql2 = 'SELECT saldo FROM cs2.contacorrente_recebafacil WHERE codloja='.$codloja.' ORDER BY id DESC LIMIT 1';
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$array_sql2=mysql_fetch_array($xqr_sql);
			$saldo = $array_sql2["saldo"];
			if ( empty($saldo) ) $saldo = 0;
			$saldo -= $vr_repasse;
			
			$valor_ant = verifica_emprestimo($codloja);
			# j� que est� inadimplente ZERO o saldo para n�o enviar
			if ( $valor_ant > 0 ) $saldo = 0;
			
			if ( $saldo > 0 ){
				# verificando se o cliente possui titulo em aberto, se tiver n�o gera repasse para ele.
				$sql2 = 'SELECT count(*) qtd FROM cs2.titulos
						 WHERE codloja='.$codloja.' and datapg is null and vencimento < now()';
				$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
				$array_sql2=mysql_fetch_array($xqr_sql);
				$xqtd = $array_sql2["qtd"];
				if ( $xqtd > 0 ){
					# Cliente INADIMPLENTE com as mensalidades
					$blq_repasse_inadimplencia .= colocaespacos($logon,5).' '.colocaespacosdir($razaosoc,30).'   '.
	                              colocaespacos($tpconta,15).' '.
	                              colocazeros($banco_cliente,3).' '.
	                              colocaespacos($agencia_cliente,6).'   '.
	                              colocaespacos($conta_cliente,12).' '.
	                              colocaespacos($cpfcnpj_doc,14).'  '.
	                              colocaespacosdir($nome_doc,30).'   '.
	                              colocaespacos($vr_repasse,10).'  '.
	                              colocaespacos($saldo,12)." I \n";
	                $totinadimplente += $saldo;
				}else{
					# Cliente em dia com suas mensalidades
						
					$id   = str_pad($codloja,6,' ',STR_PAD_RIGHT);
					$emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
					$tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
					$bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
					$age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
					$cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);
					$vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
					$vlr  = number_format($vlr,2,',', '.');
					
					if ( $saldo > 2000 ){
					
						if ( ($cpfcnpj_doc != $insc) && ($cpfcnpj_doc != $cpfsocio1) && ($cpfcnpj_doc != $cpfsocio2) ){

							$retorno = ver_autorizacao($codloja,$cpfcnpj_doc,$banco_cliente,$agencia_cliente,$conta_cliente);
							
							if ( $retorno == "NEG" ){
								$linha_sup_3000_rec .= "<tr style='font-size:12px'>
															<td>$id</td>
															<td>$emp</td>
															<td>$tit</td>
															<td>$bco</td>
															<td>$age</td>
															<td>$cta</td>
															<td>$vlr</td>
															<td>$dt_cad</td>
														</tr>";
								$autoriza = 'N';
							}else{
								// autorizado, a conta est� cadastrada para receber
								$autoriza = 'S';
							}
						}
					}
					
					if ( $saldo >= 5000 ){

						if ( $autoriza == 'S' ){
							$linha_ted .= "<tr style='font-size:12px'>
								<td>$id</td>
								<td>$emp</td>
								<td>$tit</td>
								<td>$bco</td>
								<td>$age</td>
								<td>$cta</td>
								<td>$vlr</td>
								<td>$dt_cad</td>
								</tr>";
						}
					}
					
					if ( $autoriza == 'S' ){

						# Cliente em dia com suas mensalidades
						$totlinha++;
						$NumeroRegistro++;
						$CODLOJA = '99999'.$codloja;
						// REGISTRO DETALHES DO LOTE
						// SEGMENTO A
						$Registro = '001';                          // 001 a 003 - C�digo do banco
						$Registro .= colocazeros($NumeroLote,4);    // 004 a 007 - N�mero do lote
						$Registro .= '3';                           // 008 q 008 - Tipo do registro: Registro detalhe do lote
						$Registro .= colocazeros($NumeroRegistro,5); // 009 a 013 - N�mero seq�encial do registro no 
																	// lote - Cada t�tulo tem 2 registros (P e Q)
						$Registro .= 'A';          // 014 a 014 - C�digo do segmento do registro detalhe
						$Registro .= '0';          // 015 a 015 - Tipo de movimento  0-Inclusao
						$Registro .= '00';         // 016 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
						$Registro .= '700';        // 018 a 020 - C�digo da camara centralizadora  
												  //       700 - DOC
												  //       018 - TED   
												  //       Quando BB Informar "000"
						$Registro .= colocazeros($banco_cliente,3);          // 021 a 023 - C�digo do Banco do Favorecido
	
						if ( strlen($agencia_cliente) <= 4 ) $Registro .= colocazeros($agencia_cliente,5);
						else $Registro .= colocazeros(substr($agencia_cliente,0,strlen($agencia_cliente)-1),5);    // 024 a 028 - C�d Agen do Favorecido
						$Registro .= ' ';
						
						$Registro .= colocazeros(substr($conta_cliente,0,strlen($conta_cliente)-1),12);   // 030 a 041 - C�digo da Conta Corrente do Favorecido
						$Registro .= colocazeros(substr($conta_cliente,strlen($conta_cliente)-1,1),1);    // 042 a 042 - Digito da Conta Corrente do Favorecido
						$Registro .= ' ';                              // 043 a 043 - Digito verificador da AG/CONTA
						$Registro .= colocaespacosdir($nome_doc,30);   // 044 a 073 - Nome do Favorecido
	
						$Registro .= colocaespacos('',20);             // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA -  DOC,NF,NP,ETC
						
						# data atual mais 1 dia
						$data = gmdate("dmY", time()+(3600*27));
						$Registro .= $data;                           // 094 a 101 - Data para lan�amento do Debito ou Credito
						$Registro .= 'BRL';                           // 102 a 104 - Tipo de Moeda
						$Registro .= colocazeros('',15);              // 105 a 119 - QTD de moeda
						$valor = $saldo * 100;
						$valor = str_replace(',','',$valor);
						$valor = str_replace('.','',$valor);
						$Registro .= colocazeros($valor,15);          // 120 a 134 - Valor para DEBITAR ou CREDITAR
						$Registro .= colocaespacos('',20);            // 135 a 154 - Num DOC ATRIBUIDO P/ BANCO
						$Registro .= '00000000';                      // 155 a 162 - Data real da efetiva��o do lan�amento
						$Registro .= '000000000000000';               // 163 a 177 - Valor real da efetiva��o do lan�amento
						$totccou  += $saldo;
						$Registro .= colocaespacosdir($CODLOJA,40);    // codloja do associado  // 178 a 217 - Outras informa��es
						$Registro .= colocaespacos('',12);             // 218 a 229 - USO EXCLUSIVO FEBRABAN/CNAB
						$Registro .= '0';                              // 230 a 230 - Aviso ao favorecido  0-nao  1-sim
						$Registro .= colocaespacos('',10);             // 231 a 240 - CODIGOS DAS OCORRENCIAS P/ RETORNO
						$registros .= "$Registro\n";
						// SEGMENTO B
						$totlinha++;
						$NumeroRegistro++;
						$Registro = '001';                            // 001 a 003 - C�digo do banco
						$Registro .= colocazeros($NumeroLote,4);       // 004 a 007 - N�mero do lote
						$Registro .= '3';                              // 008 a 008 - Registro detalhe do lote
						$Registro .= colocazeros($NumeroRegistro,5);   // 009 a 013 - N�mero seq�encial do registro no lote - Cada t�tulo tem 2 registros (P e Q)
						$Registro .= 'B';                              // 014 a 014 - C�digo do segmento do registro detalhe
						$Registro .= '   ';                            // 015 a 017 - BRANCOS
						$tam = strlen($cpfcnpj_doc);
						if ($tam==11) $Registro .= '1';                // 018 a 018 - 1 CPF 2 CGC
						else $Registro .= '2';                         // 018 a 018 - 1 CPF 2 CGC
						$Registro .= colocazeros($cpfcnpj_doc,14);     // 019 a 032 - Numero de Inscricao do Favorecido
						$Registro .= colocaespacos('',30);             // 033 a 020 - Endere�o do Favorecido
						$Registro .= colocazeros('',5);                // 063 a 067 - Numero do Endere�o
						$Registro .= colocaespacos('',15);             // 068 a 082 - Complemento Endere�o
						$Registro .= colocaespacos('',15);             // 083 a 097 - Bairro do Endere�o
						$Registro .= colocaespacos('',20);             // 098 a 117 - Nome da Cidade
						$Registro .= colocazeros('',5);                // 118 a 122 - CEP
						$Registro .= colocaespacos('',3);              // 123 a 125 - Complemento CEP
						$Registro .= colocaespacos('',2);              // 126 a 127 - UF
						$Registro .= '00000000';                       // 128 a 135 - Data do Vencimento
						$Registro .= colocazeros('',15);               // 136 a 150 - Valor do Documento
						$Registro .= colocazeros('',15);               // 151 a 165 - Valor do Abatimento
						$Registro .= colocazeros('',15);               // 166 a 180 - Valor do Desconto
						$Registro .= colocazeros('',15);               // 181 a 195 - Valor da Mora
						$Registro .= colocazeros('',15);               // 196 a 210 - Valor da Multa
						$Registro .= colocaespacos('',15);             // 211 a 225 - Codigo do Favorecido
						$Registro .= colocaespacos('',15);             // 226 a 240 - USO EXCLUSIVO FEBRABAN
						$registros .= "$Registro\n";
					}
				}
			}
		}
	}
	# REGISTRO TRAILLER DO LOTE 0003
	$Registro = '';
	$Registro .= '001';                                    // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
	$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);                      // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
	$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
	$valor = $totccou * 100;
	$total_geral += $totccou;
	$valor = str_replace(',','',$valor);
	$valor = str_replace('.','',$valor);
	$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores DEB/CRED
	$Registro .= '000000000000000000';                     // 042 a 059 - Somat�ria de quant de moedas
	$Registro .= colocaespacos('',171);                    // 060 a 230 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
	$totlinha++;
	$registros .= "$Registro\n";
	$NumeroLote++;
	// REGISTRO HEADER DO LOTE  0004
	// CORRENTISTA DE OUTROS BANCOS - CONTA POUPAN�A
	$Registro = '001';                                 // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);           // 004 a 007 - N�mero do lote de servi�o
	$Registro .= '1';                                  // 008 a 008 - Tipo do registro - Registro header de lote
	$Registro .= 'C';                                  // 009 a 009 - Tipo de opera��o: C - CREDITO
	$Registro .= '20';                                 // 010 a 011 - Tipo de servi�o: 20 (Pagamento Fornecedor)
	$Registro .= '03';                                 // 012 a 013 - Forma de lan�amento: 03 (DOC/TED)
	$Registro .= '020';                                // 014 a 016 - N�mero da vers�o do layout do lote
	$Registro .= ' ';                                  // 017 a 017 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= '2';                                  // 018 a 018 - Tipo de inscri��o do cedente
	$Registro .= colocazeros('12244595000100',14);     // 019 a 032 - N�mero de inscri��o do cedente
	$Registro .= '0009750160126       ';               // 033 a 052 - C�digo do conv�nio no banco
	$Registro .= '03051';                              // 053 a 057 - C�digo da ag�ncia do cedente
	$Registro .= '1';                                  // 058 a 058 - D�gito da ag�ncia do cedente
	$Registro .= '000000023096';                       // 059 a 700 - N�mero da conta do cedente
	$Registro .= '0';                                  // 071 a 071 - D�gito da conta do cedente
	$Registro .= ' ';                                                   // 072 a 073 - D�gito verificador da ag�ncia / conta
	$Registro .= colocaespacosdir('ISPCN ADMINISTRACAO DE COBRANC',30); // 073 a 102 - Nome do cedente
	$Registro .= colocaespacos('',40);                                  // 103 a 142 - Mensagem 1 para todos os boletos do lote
	$Registro .= colocaespacosdir('AV. MARECHAL FLORIANO PEIXOTO',30);  // 143 a 172 - Nome da rua, av. p�a, etc
	$Registro .= '00306';                                                // 173 a 177 - N�mero do local
	$Registro .= colocaespacosdir('SL 11',15);                          // 178 a 192 - Complemento
	$Registro .= colocaespacosdir('CURITIBA',20);                       // 193 a 212 - Cidade
	$Registro .= '80010';                                               // 213 a 217 - CEP
	$Registro .= '130';                                                 // 218 a 220 - Complemento do CEP
	$Registro .= 'PR';                                                  // 221 a 222 - Sigla do Estado
	$Registro .= colocaespacos('',8);                                   // 223 a 230 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= colocaespacos('',10);                                  // 231 a 240 - C�digo de Ocorr�ncias p/ retorno
	$totlinha++;
	$registros .= "$Registro\n";
	$NumeroRegistro = 0;
    $sql = "SELECT  DISTINCT(a.codloja) codloja, mid(b.razaosoc,1,30) razaosoc, b.banco_cliente, b.agencia_cliente, 
					b.conta_cliente, cpfcnpj_doc, upper(mid(b.nome_doc,1,30)) nome_doc, 
					'8' as vr_repasse, b.tpconta,
					date_format(b.dt_cad,'%d/%m/%Y') as dt_cad 
            FROM cs2.contacorrente_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja 
            WHERE b.banco_cliente <> 1 
				  AND b.tpconta = 2 
				  AND b.sitcli = 0 
				  AND ( ( b.pendencia_contratual = 0) or ( b.pendencia_contratual = 1 and b.dt_cad >= '$data_limite') )
			ORDER BY b.banco_cliente";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		while($registro = mysql_fetch_array($qr_sql)){
			$codloja = $registro['codloja'];
			$razaosoc = $registro['razaosoc'];
			$banco_cliente = $registro['banco_cliente'];
			$agencia_cliente = $registro['agencia_cliente'];
			$conta_cliente = $registro['conta_cliente'];
			$cpfcnpj_doc = $registro['cpfcnpj_doc'];
			$nome_doc = $registro['nome_doc'];
			$vr_repasse = $registro['vr_repasse'];
			$tpconta = $registro['tpconta'];
			$dt_cad = $registro['dt_cad'];	
			# verificando o logon do cliente
			$sql2 = 'SELECT CAST(MID(logon,1,6) AS UNSIGNED) logon FROM cs2.logon WHERE codloja='.$codloja.' LIMIT 1';
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$array_sql2 = mysql_fetch_array($xqr_sql);
			$logon = $array_sql2["logon"];
			# verificando o saldo na conta corrente do cliente
			$sql2 = 'SELECT saldo FROM cs2.contacorrente_recebafacil WHERE codloja='.$codloja.' ORDER BY id DESC LIMIT 1';
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$array_sql2=mysql_fetch_array($xqr_sql);
			$saldo = $array_sql2["saldo"];
			if ( empty($saldo) ) $saldo = 0;
			$saldo -= $vr_repasse;
			
			$valor_ant = verifica_emprestimo($codloja);
			# j� que est� inadimplente ZERO o saldo para n�o enviar
			if ( $valor_ant > 0 ) $saldo = 0;
			
			if ( $saldo > 0 ){
				# verificando se o cliente possui titulo em aberto, se tiver n�o gera repasse para ele.
				$xdata = date('Y-m-d');
				# verificando se o cliente possui titulo em aberto, se tiver n�o gera repasse para ele.
				$sql2 = "SELECT count(*) qtd FROM cs2.titulos
						 WHERE codloja='$codloja' and datapg is null and vencimento < '$xdata'";
				$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
				$array_sql2=mysql_fetch_array($xqr_sql);
				$xqtd = $array_sql2["qtd"];
				if ( $xqtd > 0 ){
					# Cliente INADIMPLENTE com as mensalidades
					$blq_repasse_inadimplencia .= colocaespacos($logon,5).' '.colocaespacosdir($razaosoc,30).'   '.
	                              colocaespacos($tpconta,15).' '.
	                              colocazeros($banco_cliente,3).' '.
	                              colocaespacos($agencia_cliente,6).'   '.
	                              colocaespacos($conta_cliente,12).' '.
	                              colocaespacos($cpfcnpj_doc,14).'  '.
	                              colocaespacosdir($nome_doc,30).'   '.
	                              colocaespacos($vr_repasse,10).'  '.
	                              colocaespacos($saldo,12)." I \n";
	                $totinadimplente += $saldo;
				}else{
					# Cliente em dia com suas mensalidades
						
					$id   = str_pad($codloja,6,' ',STR_PAD_RIGHT);
					$emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
					$tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
					$bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
					$age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
					$cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);
					$vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
					$vlr  = number_format($vlr,2,',', '.');
					
					if ( $saldo > 2000 ){
					
						if ( ($cpfcnpj_doc != $insc) && ($cpfcnpj_doc != $cpfsocio1) && ($cpfcnpj_doc != $cpfsocio2) ){

							$retorno = ver_autorizacao($codloja,$cpfcnpj_doc,$banco_cliente,$agencia_cliente,$conta_cliente);
							
							if ( $retorno == "NEG" ){
								$linha_sup_3000_rec .= "<tr style='font-size:12px'>
															<td>$id</td>
															<td>$emp</td>
															<td>$tit</td>
															<td>$bco</td>
															<td>$age</td>
															<td>$cta</td>
															<td>$vlr</td>
															<td>$dt_cad</td>
														</tr>";
								$autoriza = 'N';
							}else{
								// autorizado, a conta est� cadastrada para receber
								$autoriza = 'S';
							}
						}
					}
					
					if ( $saldo >= 5000 ){

						if ( $autoriza == 'S' ){
							$linha_ted .= "<tr style='font-size:12px'>
								<td>$id</td>
								<td>$emp</td>
								<td>$tit</td>
								<td>$bco</td>
								<td>$age</td>
								<td>$cta</td>
								<td>$vlr</td>
								<td>$dt_cad</td>
								</tr>";
						}
					}
					
					if ( $autoriza == 'S' ){
						$totlinha++;
						$NumeroRegistro++;
						$CODLOJA = '99999'.$codloja;
						// REGISTRO DETALHES DO LOTE
						// SEGMENTO A
						$Registro = '001';                           // 001 a 003 - C�digo do banco
						$Registro .= colocazeros($NumeroLote,4);     // 004 a 007 - N�mero do lote
						$Registro .= '3';                            // 008 q 008 - Tipo do registro: Registro detalhe do lote
						$Registro .= colocazeros($NumeroRegistro,5); // 009 a 013 - N�mero seq�encial do registro no 
																	 // lote - Cada t�tulo tem 2 registros (P e Q)
						$Registro .= 'A';          // 014 a 014 - C�digo do segmento do registro detalhe
						$Registro .= '0';          // 015 a 015 - Tipo de movimento  0-Inclusao
						$Registro .= '00';         // 016 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
						$Registro .= '700';        // 018 a 020 - C�digo da camara centralizadora  
												   //       700 - DOC
												   //       018 - TED   
												   //       Quando BB Informar "000"
						$Registro .= colocazeros($banco_cliente,3);          // 021 a 023 - C�digo do Banco do Favorecido
						
					   if ( strlen($agencia_cliente) <= 4 ) $Registro .= colocazeros($agencia_cliente,5);
					   else $Registro .= colocazeros(substr($agencia_cliente,0,strlen($agencia_cliente)-1),5);    // 024 a 028 - C�d Agen do Favorecido
	
						$Registro .= ' ';
						
						$Registro .= colocazeros(substr($conta_cliente,0,strlen($conta_cliente)-1),12);   // 030 a 041 - C�digo da Conta Corrente do Favorecido
						$Registro .= colocazeros(substr($conta_cliente,strlen($conta_cliente)-1,1),1);    // 042 a 042 - Digito da Conta Corrente do Favorecido
						$Registro .= ' ';                              // 043 a 043 - Digito verificador da AG/CONTA
						$Registro .= colocaespacosdir($nome_doc,30);   // 044 a 073 - Nome do Favorecido
	
						$Registro .= colocaespacos('',20);             // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA -  DOC,NF,NP,ETC
						
						# data atual mais 1 dia
						$data = gmdate("dmY", time()+(3600*27));
						$Registro .= $data;                           // 094 a 101 - Data para lan�amento do Debito ou Credito
						$Registro .= 'BRL';                           // 102 a 104 - Tipo de Moeda
						$Registro .= colocazeros('',15);              // 105 a 119 - QTD de moeda
						$valor = $saldo * 100;
						$valor = str_replace(',','',$valor);
						$valor = str_replace('.','',$valor);
						$Registro .= colocazeros($valor,15);          // 120 a 134 - Valor para DEBITAR ou CREDITAR
						$Registro .= colocaespacos('',20);            // 135 a 154 - Num DOC ATRIBUIDO P/ BANCO
						$Registro .= '00000000';                      // 155 a 162 - Data real da efetiva��o do lan�amento
						$Registro .= '000000000000000';               // 163 a 177 - Valor real da efetiva��o do lan�amento
						$totcpou  += $saldo;
						$Registro .= colocaespacosdir($CODLOJA,40);    // codloja do associado  // 178 a 217 - Outras informa��es
						$Registro .= colocaespacos('',12);             // 218 a 229 - USO EXCLUSIVO FEBRABAN/CNAB
						$Registro .= '0';                              // 230 a 230 - Aviso ao favorecido  0-nao  1-sim
						$Registro .= colocaespacos('',10);             // 231 a 240 - CODIGOS DAS OCORRENCIAS P/ RETORNO
						$registros .= "$Registro\n";
						// SEGMENTO B
						$totlinha++;
						$NumeroRegistro++;
						$Registro = '001';                            // 001 a 003 - C�digo do banco
						$Registro .= colocazeros($NumeroLote,4);       // 004 a 007 - N�mero do lote
						$Registro .= '3';                              // 008 a 008 - Registro detalhe do lote
						$Registro .= colocazeros($NumeroRegistro,5);   // 009 a 013 - N�mero seq�encial do registro no lote - Cada t�tulo tem 2 registros (P e Q)
						$Registro .= 'B';                              // 014 a 014 - C�digo do segmento do registro detalhe
						$Registro .= '   ';                            // 015 a 017 - BRANCOS
						$tam = strlen($cpfcnpj_doc);
						if ($tam==11) $Registro .= '1'; // 018 a 018 - 1 CPF 2 CGC
						else $Registro .= '2';                         // 018 a 018 - 1 CPF 2 CGC
						$Registro .= colocazeros($cpfcnpj_doc,14);     // 019 a 032 - Numero de Inscricao do Favorecido
						$Registro .= colocaespacos('',30);             // 033 a 020 - Endere�o do Favorecido
						$Registro .= colocazeros('',5);                // 063 a 067 - Numero do Endere�o
						$Registro .= colocaespacos('',15);             // 068 a 082 - Complemento Endere�o
						$Registro .= colocaespacos('',15);             // 083 a 097 - Bairro do Endere�o
						$Registro .= colocaespacos('',20);             // 098 a 117 - Nome da Cidade
						$Registro .= colocazeros('',5);                // 118 a 122 - CEP
						$Registro .= colocaespacos('',3);              // 123 a 125 - Complemento CEP
						$Registro .= colocaespacos('',2);              // 126 a 127 - UF
						$Registro .= '00000000';                       // 128 a 135 - Data do Vencimento
						$Registro .= colocazeros('',15);               // 136 a 150 - Valor do Documento
						$Registro .= colocazeros('',15);               // 151 a 165 - Valor do Abatimento
						$Registro .= colocazeros('',15);               // 166 a 180 - Valor do Desconto
						$Registro .= colocazeros('',15);               // 181 a 195 - Valor da Mora
						$Registro .= colocazeros('',15);               // 196 a 210 - Valor da Multa
						$Registro .= colocaespacos('',15);             // 211 a 225 - Codigo do Favorecido
						$Registro .= colocaespacos('',15);             // 226 a 240 - USO EXCLUSIVO FEBRABAN
						$registros .= "$Registro\n";
					}
				}
			}
		}
	}
	# REGISTRO TRAILLER DO LOTE 0004
	$totlinha++;
	$Registro = '';
	$Registro .= '001';                               // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - N�mero do lote
	$Registro .= '5';                                 // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);                 // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
	$Registro .= colocazeros($NumeroRegistro+2,6);    // 018 a 023 - Qtd de registro de lote
	$valor = $totcpou * 100;
	$total_geral += $totcpou;
	$valor = str_replace(',','',$valor);
	$valor = str_replace('.','',$valor);
	$Registro .= colocazeros($valor,18);              // 024 a 041 - Somat�ria dos valores DEB/CRED
	$Registro .= '000000000000000000';                // 042 a 059 - Somat�ria de quant de moedas
	$Registro .= colocaespacos('',171);               // 060 a 230 - Uso exclusivo FEBRABAN/CNAB
	$Registro .= colocaespacos('',10);                // 231 a 240 - C�digo das ocorr�ncias para retorno

	$registros .= "$Registro\n";

	
	$totlinha++;

	*/
		
	// TRAILER DO ARQUIVO
	$Registro = '';
	$Registro .= '001';                        // 001 a 003 - C�digo do banco
	$Registro .= '9999';                       // 004 a 007 - N�mero do lote
	$Registro .= '9';                          // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);          // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
	$Registro .= colocazeros($NumeroLote,6);   // 018 a 023 - Qtd de lotes no arquivo
	$Registro .= colocazeros($totlinha,6);     // 018 a 023 - quantidade de registros do arquivo
	$Registro .= '000000';                     // 024 a 029 - qtd de contas p/ conc. LOTES
	$Registro .= colocaespacos('',205);        // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
	$registros .= "$Registro\n";
	//Aqui voc� coloca o nome do arquivo que ser� gravado
	try{
		$arquivo = "../../../download/Repasse".date("dmY");
		$arq = "Repasse".date("dmY");
		$abrir = fopen($arquivo, "w");
		$escreve = fputs($abrir, $registros);
		fclose($arquivo);
	} catch (Exception $e) {
    	echo 'Erro ao gravar o arquivo: ',  $e->getMessage(), "\n";
	}

	$tamanho = filesize($arquivo);
	header("Content-type: Application/unknown");
	header("Content-length: $tamanho");
	header("Content-Disposition: attachment; filename=$arquivo");
	header("Content-Description: PHP Generated Data");

	
	$total_geral = number_format($total_geral,2);
	
	echo "
		  Arquivo Gerado com sucesso\r\n 
		  Arquivo:	<a href=https://www.webcontrolempresas.com.br/download/".$arquivo.">".$arq."</a><br> Total Geral : R$ $total_geral";
		  

	# Mostrando os TED
	
	$linha_ted .= "</table>";	
	echo $linha_ted;
	
	exit;

	$tamanho = filesize($arquivo);
	header("Content-type: Application/unknown");
	header("Content-length: $tamanho");
	header("Content-Disposition: attachment; filename=$arquivo");
	header("Content-Description: PHP Generated Data");
	readfile($arquivo);

?>
