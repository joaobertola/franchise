<?php

	# Author:  Luciano Mancini
	# Modulo:  Remessa Franquia - Banco Itau
	# Finalidade: Gerar o arquivo de credito a serem enviados ao Banco ITAU para os franqueados

	include("../../../validar2.php");

	global $conexao,$arquivo;
	conecex();

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
					</tr>
				 ";
	
	$blq_repasse_inadimplencia = '';
	$NumeroArquivo = 1;
	$NumeroLote = 1;
	$totlinha = 0;
	
	$tot_pagar = 0;
	$NumeroRegistro = 0;
	
        $total_conta_corrente = 0;	
	$total_conta_poupanca = 0;
	$total_doc            = 0;
	$total_ted            = 0;
	
	// HEADER DO ARQUIVO
	$Registro  = colocazeros('341',3);                // 001 a 003 - Codigo do banco
	$Registro .= '0000';                              // 004 a 007 - Lote de servico
	$Registro .= '0';                                 // 008 a 008 - Tipo de registro - Registro header de arquivo
	$Registro .= colocaespacos('',6);                 // 009 a 014 - BRANCOS
	$Registro .= '080';                               // 015 a 017 - Layout do Arquivo
	$Registro .= '2';                                 // 018 a 018 - Tipo de inscricao
	$Registro .= '08745918000171';                    // 019 a 032 - Numero de inscricao do cedente
	$Registro .=  colocaespacos('',20);               // 033 a 052 - BRANCOS
	$Registro .= '03833';                             // 053 a 057 - Codigo da agencia do cedente
	$Registro .= ' ';                                 // 058 a 058 - BRANCOS
	$Registro .= colocazeros('63310',12);             // 059 a 070 - Numero da conta do cedente
	$Registro .= ' ';                                 // 071 a 071 - BRANCOS
	$Registro .= '4';                                 // 072 a 072 - Digito verificador da agencia / conta
	$Registro .= colocaespacosdir('WC SIST EQUIP INFORMATICA',30);  // 073 a 102 - Nome do cedente
	$Registro .= colocaespacosdir('BANCO ITAU',30);       // 103 a 132 - Nome do banco
	$Registro .= colocaespacos('',10);                    // 133 a 142 - BRANCOS
	$Registro .= '1';                                     // 143 a 143 - Codigo de Remessa (1) / Retorno (2)
	$data = date('dmY');
	$Registro .= $data;                                   // 144 a 151 - Data do de geracao do arquivo
	$hora = date("His");
	$Registro .= substr($hora,0,6);                       // 152 a 157 - Hora de geracao do arquivo
	$Registro .= colocazeros('',9);                       // 158 a 163 - ZEROS
	$Registro .= '00000';                                 // 167 a 171 - Unidade de Densidade
	$Registro .= colocaespacos('',69);                    // 172 a 240 - BRANCOS
	$totlinha++;
	$registros .= "$Registro\n";
	
	# INICIO - CONTA CORRENTE BANCO ITAU
	
	// HEADER DO LOTE
	$Registro  = '341';                               // 001 a 003 - C�digo do Banco
	$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
	$Registro .= '1';                                 // 008 a 008 - Tipo de Registro
	$Registro .= 'C';                                 // 009 a 009 - Tipo de Operacao
	$tp_pagto = '20';
	$Registro .= $tp_pagto;                           // 010 a 011 - Tipo de Pagamento | 20-Fornecedores | 30-Salarios

	# Forma de Pagamento
	# 01 - Credito em Conta Corrente no ITAU
	# 03 - DOC C
	# 05 - Credito em Conta Poupanca no ITAU
	# 06 - Credito em Conta Corrente no ITAU de mesma titularidade
	# 07 - DOC D
	# 41 - TED Outro titular
	# 43 - TED Mesmo titular
	# 60 - Cartao Salario

	$forma_pgto = '01';
	
	$Registro .= colocazeros($forma_pgto,2);          // 012 a 013 - Forma de Pagamento
	$Registro .= '040';                               // 014 a 016 - Layout do Lote
	$Registro .= ' ';                                 // 017 a 017 - Em branco
	$Registro .= '2';                                 // 018 a 018 - Empresa Tipo Inscricao - Debitada
	$Registro .= '08745918000171';                    // 019 a 032 - Empresa Numero Inscricao - Debitada
	
	$Registro .= $tp_pagto.$forma_pgto;               // 033 a 036 - Identificacao do lancamento
	$Registro .= colocaespacos('',16);                // 037 a 052 - Em branco
	
	$Registro .= '03833';                             // 053 a 057 - Agencia debitada
	$Registro .= ' ';                                 // 058 a 058 - Em branco
	$Registro .= colocazeros('63310',12);             // 059 a 070 - Conta debitada
	$Registro .= ' ';                                 // 071 a 071 - Em branco
	$Registro .= '4';                                 // 072 a 072 - DAC - Digito verificado da conta	
	$Registro .= colocaespacos('WC SIST EQUIP INFORMATICA',30);   // 073 a 102 - Nome da empresa debitada
	$Registro .= colocaespacos('',30);                // 103 a 132 - Finalidade do lote
	$Registro .= colocaespacos('',10);                // 133 a 142 - Hist�rico de conta corrente
	$Registro .= colocaespacosdir('AV. CANDIDO DE ABREU',30);            // 143 a 172 - Endereco da empresa
	$Registro .= colocaespacosdir('70',5);              // 173 a 177 - Numero
	$Registro .= colocaespacosdir('CONJ 404',15);         // 178 a 192 - Complemento
	$Registro .= colocaespacosdir('CURITIBA',20);        // 193 a 212 - Cidade
	$Registro .= colocaespacosdir('80530000',8);         // 213 a 220 - CEP
	$Registro .= colocaespacos('PR',2);               // 221 a 222 - UF
	$Registro .= colocaespacos('',8);                 // 223 a 230 - Em branco
	$Registro .= colocazeros('',10);                  // 231 a 240 - Ocorrencias
	$totlinha++;
	$registros .= "$Registro\n";	
	# Fim do header
	
	# Selecionando Franqueado - CONTA CORRENTE BANCO ITAU
        $sql = "SELECT id, MID(razaosoc,1,30) razaosoc, banco, agencia, tpconta, conta, titular, cpftitular 
                FROM cs2.franquia
                WHERE banco = '341' AND tpconta = '1' AND banco > 0 and situacao_repasse = 0
                ORDER BY id";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		while($registro = mysql_fetch_array($qr_sql)){
			$id = $registro['id'];
			$razaosoc = substr($registro['razaosoc'],0,30);
			$banco_cliente = $registro['banco'];
			$agencia_cliente = $registro['agencia'];
			$conta_cliente = $registro['conta'];
			$cpfcnpj_doc = trim($registro['cpftitular']);
			$nome_doc = substr($registro['titular'],0,30);
			
			$saldo = 0;

			# verificando o saldo na conta corrente do franqueado
			$sql2 = "select valor,operacao from cs2.contacorrente where franqueado = '$id' order by id asc";
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$xqtd = mysql_num_rows($xqr_sql);
			if ( $xqtd > 0 ){
				while($xregistro = mysql_fetch_array($xqr_sql)){
					$valor = $xregistro['valor'];
					$operacao = $xregistro['operacao'];
					if ( $operacao == '0' ) $saldo += $valor;
					else $saldo -= $valor;
				}
			}			
			
			$xsql = "SELECT 
						SUM(valor) as valorpg 
			 		 FROM cs2.contacorrente 
					 WHERE franqueado='$id' AND venc_titulo >= NOW()";

			$xqr_sql = mysql_query($xsql,$conexao) or die("Erro no SQL: $sql");
			$array_sql=mysql_fetch_array($xqr_sql);
			$valorpg = $array_sql["valorpg"];
			if ( empty($valorpg) ) $valorpg = 0;
			$saldo -= $valorpg;
			$saldo -= 10; // Debito do Valor do DOC

			$saldo = number_format($saldo,2,'.','');
			
			if ( $saldo > 0 ){
				
				echo " C/C --> Franquia: $id - Banco: $banco_cliente - SALDO: $saldo<br>";
				
				$emp  = str_pad(substr($razaosoc,0,30),30,' ',STR_PAD_RIGHT);
				$tit  = str_pad(substr($nome_doc,0,30),30,' ',STR_PAD_RIGHT);
				$bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
				$age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
				$cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);
				$vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
				$vlr  = number_format($vlr,2,',', '.');

				# Cliente em dia com suas mensalidades
				$NumeroRegistro++;
				$CODLOJA = '99999'.$id;
				// REGISTRO DETALHES DO LOTE
				$Registro = '341';                                         // 001 a 003 - C�digo do banco
				$Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
				$Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
				$Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
				$Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
				$Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
				$Registro .= '000';                                        // 018 a 020 - ZEROS
				$Registro .= str_pad($banco_cliente, 3, 0, STR_PAD_LEFT);  // 021 a 023 - C�digo do Banco do Favorecido
						
				$agencia   = colocazeros(substr($agencia_cliente,0,4),4);
				$conta     = substr($conta_cliente,0,strlen($conta_cliente)-1);
				$conta     = $conta * 1;
				$conta     = str_pad($conta, 6, 0, STR_PAD_LEFT);
				$dac_cta   = substr($conta_cliente,strlen($conta_cliente)-1,1);
				$dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);
						
				$agencia_conta = '0'.$agencia.' 000000'.$conta.' '.$dac_cta;
						
				$Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
				$Registro .= str_pad($nome_doc, 30, ' ', STR_PAD_RIGHT);   // 044 a 073 - Nome do Favorecido
				$Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
				$data = date('dmY');
				$Registro .= $data;                                        // 094 a 101 - Data de Pagamento
				$Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
				$Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS
				$valor = $saldo * 100;
				$valor = str_replace(',','',$valor);
				$valor = str_replace('.','',$valor);
				$Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
				$Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
				$Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
				$Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento
				$Registro .= str_pad($CODLOJA, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
				$Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento
						
				$Registro .= str_pad($cpfcnpj_doc, 14, 0, STR_PAD_LEFT);   // 204 a 217 - CPF/CNPJ do Favorecido
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);             // 218 a 219 - Finalidade do DOC
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 220 a 224 - Finalidade do TED
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 225 a 229 - BRANCOS
				$Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);             // 230 a 230 - Aviso ao Favorecido
				$Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);            // 231 a 240 - Ocorrencia de retorno
				$total_conta_corrente  += $saldo;
				$totlinha++;
				$registros .= "$Registro\n";
						
			}
		}
	}
	
	# TRAILLER - CONTA CORRENTE ITAU
	$Registro  = '341';                                    // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
	$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);                      // 009 a 017 - BRANCO
	$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
	$valor = $total_conta_corrente * 100;
	$valor = str_replace(',','',$valor);
	$valor = str_replace('.','',$valor);
	$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores
	$Registro .= '000000000000000000';                     // 042 a 059 - ZEROS
	$Registro .= colocaespacos('',171);                    // 060 a 230 - BRANCOS
	$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
	$totlinha++;
	$registros .= "$Registro\n";
	
	# FIM - CONTA CORRENTE BANCO ITAU
	
	#########################################################################################################
	
	# INICIO - CONTA POUPANCA BANCO ITAU
	$NumeroLote++;
	$NumeroRegistro = 0;	
	// HEADER
	$Registro  = '341';                               // 001 a 003 - C�digo do Banco
	$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
	$Registro .= '1';                                 // 008 a 008 - Tipo de Registro
	$Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
	$tp_pagto = '20';
	$Registro .= $tp_pagto;                           // 010 a 011 - Tipo de Pagamento | 20-Fornecedores | 30-Salarios
	
	# Forma de Pagamento
	# 01 - Credito em Conta Corrente no ITAU
	# 03 - DOC C
	# 05 - Credito em Conta Poupan�a no ITAU
	# 06 - Credito em Conta Corrente no ITAU de mesma titularidade
	# 07 - DOC D
	# 41 - TED Outro titular
	# 43 - TED Mesmo titular
	# 60 - Cartao Salario

	$forma_pgto = '05';
	
	$Registro .= colocazeros($forma_pgto,2);          // 012 a 013 - Forma de Pagamento
	$Registro .= '040';                               // 014 a 016 - Layout do Lote
	$Registro .= ' ';                                 // 017 a 017 - Em branco
	$Registro .= '2';                                 // 018 a 018 - Empresa Tipo Inscri��o - Debitada
	$Registro .= '08745918000171';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada
	
	$Registro .= '20'.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
	$Registro .= colocaespacos('',16);                // 037 a 052 - Em branco
	
	$Registro .= '03833';                             // 053 a 057 - Agencia debitada
	$Registro .= ' ';                                 // 058 a 058 - Em branco
	$Registro .= colocazeros('63310',12);             // 059 a 070 - Conta debitada
	$Registro .= ' ';                                 // 071 a 071 - Em branco
	$Registro .= '4';                                 // 072 a 072 - DAC - Digito verificado da conta	
	$Registro .= colocaespacos('WC SIST EQUIP INFORMATICA',30);   // 073 a 102 - Nome da empresa debitada
	$Registro .= colocaespacos('',30);                // 103 a 132 - Finalidade do lote
	$Registro .= colocaespacos('',10);                // 133 a 142 - Hist�rico de conta corrente
	$Registro .= colocaespacosdir('AV. CANDIDO DE ABREU',30);            // 143 a 172 - Endereco da empresa
	$Registro .= colocaespacosdir('70',5);              // 173 a 177 - Numero
	$Registro .= colocaespacosdir('CONJ 404',15);         // 178 a 192 - Complemento
	$Registro .= colocaespacosdir('CURITIBA',20);        // 193 a 212 - Cidade
	$Registro .= colocaespacosdir('80530000',8);         // 213 a 220 - CEP
	$Registro .= colocaespacos('PR',2);               // 221 a 222 - UF
	$Registro .= colocaespacos('',8);                 // 223 a 230 - Em branco
	$Registro .= colocazeros('',10);                  // 231 a 240 - Ocorrencias
	$totlinha++;
	$registros .= "$Registro\n";	
	# Fim do header
	
	# Selecionando dos Franqueados	- CONTA CORRENTE BANCO ITAU
        $sql = "SELECT id, MID(razaosoc,1,30) razaosoc, banco, agencia, tpconta, conta, titular, cpftitular 
			FROM cs2.franquia
			WHERE banco = '341' AND tpconta = '2' AND banco > 0 and situacao_repasse = 0
			ORDER BY id";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		while($registro = mysql_fetch_array($qr_sql)){
			$id = $registro['id'];
			$razaosoc = substr($registro['razaosoc'],0,30);
			$banco_cliente = $registro['banco'];
			$agencia_cliente = $registro['agencia'];
			$conta_cliente = $registro['conta'];
			$cpfcnpj_doc = trim($registro['cpftitular']);
			$nome_doc = substr($registro['titular'],0,30);
			
			$saldo = 0;

			# verificando o saldo na conta corrente do franqueado
			$sql2 = "select valor,operacao from cs2.contacorrente where franqueado = '$id' order by id asc";
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$xqtd = mysql_num_rows($xqr_sql);
			if ( $xqtd > 0 ){
				while($xregistro = mysql_fetch_array($xqr_sql)){
					$valor = $xregistro['valor'];
					$operacao = $xregistro['operacao'];
					if ( $operacao == '0' ) $saldo += $valor;
					else $saldo -= $valor;
				}
			}			
			
			$xsql = "SELECT 
						SUM(valor) as valorpg 
			 		 FROM cs2.contacorrente 
					 WHERE franqueado='$id' AND venc_titulo >= NOW()";
			$xqr_sql = mysql_query($xsql,$conexao) or die("Erro no SQL: $sql");
			$array_sql=mysql_fetch_array($xqr_sql);
			$valorpg = $array_sql["valorpg"];
			if ( empty($valorpg) ) $valorpg = 0;
			$saldo -= $valorpg;
			$saldo -= 10; // Debito do Valor do DOC
			
			$saldo = number_format($saldo,2,'.','');
			
			if ( $saldo > 0 ){

				echo " C/P --> Franquia: $id - Banco: $banco_cliente - SALDO: $saldo<br>";
				
				$emp  = str_pad(substr($razaosoc,0,30),30,' ',STR_PAD_RIGHT);
				$tit  = str_pad(substr($nome_doc,0,30),30,' ',STR_PAD_RIGHT);
				$bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
				$age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
				$cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);
				$vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
				$vlr  = number_format($vlr,2,',', '.');

				# Cliente em dia com suas mensalidades
				$NumeroRegistro++;
				$CODLOJA = '99999'.$id;
				// REGISTRO DETALHES DO LOTE
				$Registro = '341';                                         // 001 a 003 - C�digo do banco
				$Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
				$Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
				$Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
				$Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
				$Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
				$Registro .= '000';                                        // 018 a 020 - ZEROS
				$Registro .= str_pad($banco_cliente, 3, 0, STR_PAD_LEFT);  // 021 a 023 - C�digo do Banco do Favorecido
				
				$agencia   = colocazeros(substr($agencia_cliente,0,4),4);
				$conta     = substr($conta_cliente,0,strlen($conta_cliente)-1);
				$conta     = $conta * 1;
				$conta     = str_pad($conta, 6, 0, STR_PAD_LEFT);
				$dac_cta   = substr($conta_cliente,strlen($conta_cliente)-1,1);
				$dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);

				$conta    =  str_pad($conta, 12, 0, STR_PAD_LEFT);
									  
				$agencia_conta = '0'.$agencia.' '.$conta.' '.$dac_cta;
						
				$Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
				$Registro .= str_pad($nome_doc, 30, ' ', STR_PAD_RIGHT);   // 044 a 073 - Nome do Favorecido
				$Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
				$data = date('dmY');
				$Registro .= $data;                                        // 094 a 101 - Data de Pagamento
				$Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
				$Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS

				$valor = $saldo * 100;
				$valor = str_replace(',','',$valor);
				$valor = str_replace('.','',$valor);
				$Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
				$Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
				$Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
				$Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento

				$Registro .= str_pad($CODLOJA, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
				$Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento
						
				$Registro .= str_pad($cpfcnpj_doc, 14, 0, STR_PAD_LEFT);   // 204 a 217 - CPF/CNPJ do Favorecido
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);             // 218 a 219 - Finalidade do DOC
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 220 a 224 - Finalidade do TED
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 225 a 229 - BRANCOS
				$Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);             // 230 a 230 - Aviso ao Favorecido
				$Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);            // 231 a 240 - Ocorrencia de retorno

				$total_conta_poupanca  += $saldo;
				$totlinha++;
				$registros .= "$Registro\n";
						
			}
		}
	}
	
	# TRAILLER DO PRIMEIRO LOTE   -->>   CONTA POUPANCA ITAU
	
	$Registro  = '341';                                    // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
	$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);                      // 009 a 017 - BRANCO
	$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
	$valor = $total_conta_poupanca * 100;
	$valor = str_replace(',','',$valor);
	$valor = str_replace('.','',$valor);
	$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores
	$Registro .= '000000000000000000';                     // 042 a 059 - ZEROS
	$Registro .= colocaespacos('',171);                    // 060 a 230 - BRANCOS
	$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
	$totlinha++;
	$registros .= "$Registro\n";
	
	# FIM - CONTA POUPANCA BANCO ITAU

	#########################################################################################################

	# INICIO - TED - OUTROS BANCOS
	$NumeroLote++;
	$NumeroRegistro = 0;	
	// HEADER
	$Registro  = '341';                               // 001 a 003 - C�digo do Banco
	$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
	$Registro .= '1';                                 // 008 a 008 - Tipo de Registro
	$Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
	$tp_pagto = '20';
	$Registro .= $tp_pagto;                           // 010 a 011 - Tipo de Pagamento | 20-Fornecedores | 30-Salarios

	# Forma de Pagamento
	# 01 - Credito em Conta Corrente no ITAU
	# 03 - DOC C
	# 05 - Credito em Conta Poupan�a no ITAU
	# 06 - Credito em Conta Corrente no ITAU de mesma titularidade
	# 07 - DOC D
	# 41 - TED Outro titular
	# 43 - TED Mesmo titular
	# 60 - Cartao Salario

	$forma_pgto = '41';
	
	$Registro .= colocazeros($forma_pgto,2);          // 012 a 013 - Forma de Pagamento
	$Registro .= '040';                               // 014 a 016 - Layout do Lote
	$Registro .= ' ';                                 // 017 a 017 - Em branco
	$Registro .= '2';                                 // 018 a 018 - Empresa Tipo Inscri��o - Debitada
	$Registro .= '08745918000171';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada
	
	$Registro .= $tp_pagto.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
	$Registro .= colocaespacos('',16);                // 037 a 052 - Em branco
	
	$Registro .= '03833';                             // 053 a 057 - Agencia debitada
	$Registro .= ' ';                                 // 058 a 058 - Em branco
	$Registro .= colocazeros('63310',12);             // 059 a 070 - Conta debitada
	$Registro .= ' ';                                 // 071 a 071 - Em branco
	$Registro .= '4';                                 // 072 a 072 - DAC - Digito verificado da conta	
	$Registro .= colocaespacos('WC SIST EQUIP INFORMATICA',30);   // 073 a 102 - Nome da empresa debitada
	$Registro .= colocaespacos('',30);                // 103 a 132 - Finalidade do lote
	$Registro .= colocaespacos('',10);                // 133 a 142 - Hist�rico de conta corrente
	$Registro .= colocaespacosdir('AV. CANDIDO DE ABREU',30);            // 143 a 172 - Endereco da empresa
	$Registro .= colocaespacosdir('70',5);              // 173 a 177 - Numero
	$Registro .= colocaespacosdir('CONJ 404',15);         // 178 a 192 - Complemento
	$Registro .= colocaespacosdir('CURITIBA',20);        // 193 a 212 - Cidade
	$Registro .= colocaespacosdir('80530000',8);         // 213 a 220 - CEP
	$Registro .= colocaespacos('PR',2);               // 221 a 222 - UF
	$Registro .= colocaespacos('',8);                 // 223 a 230 - Em branco
	$Registro .= colocazeros('',10);                  // 231 a 240 - Ocorrencias
	$totlinha++;
	$registros .= "$Registro\n";	
	# Fim do header
	
	# Selecionando do FRANQUEADOS	- TED
        $sql = "SELECT id, MID(razaosoc,1,30) razaosoc, banco, agencia, tpconta, conta, titular, cpftitular 
                FROM cs2.franquia
                WHERE banco <> '341' AND id > 1 AND banco > 0 and situacao_repasse = 0
                ORDER BY id";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		while($registro = mysql_fetch_array($qr_sql)){
			$id = $registro['id'];
			$razaosoc = substr($registro['razaosoc'],0,30);
			$banco_cliente = $registro['banco'];
			$agencia_cliente = $registro['agencia'];
			$conta_cliente = $registro['conta'];
			$cpfcnpj_doc = trim($registro['cpftitular']);
			$nome_doc = substr($registro['titular'],0,30);

			$saldo = 0;

			# verificando o saldo na conta corrente do franqueado
			$sql2 = "select valor,operacao from cs2.contacorrente where franqueado = '$id' order by id asc";
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$xqtd = mysql_num_rows($xqr_sql);
			if ( $xqtd > 0 ){
				while($xregistro = mysql_fetch_array($xqr_sql)){
					$valor = $xregistro['valor'];
					$operacao = $xregistro['operacao'];
					if ( $operacao == '0' ) $saldo += $valor;
					else $saldo -= $valor;
				}
			}			
			
			$xsql = "SELECT 
						SUM(valor) as valorpg 
			 		 FROM cs2.contacorrente 
					 WHERE franqueado='$id' AND venc_titulo >= NOW()";
			$xqr_sql = mysql_query($xsql,$conexao) or die("Erro no SQL: $sql");
			$array_sql=mysql_fetch_array($xqr_sql);
			$valorpg = $array_sql["valorpg"];
			if ( empty($valorpg) ) $valorpg = 0;
			$saldo -= $valorpg;
			$saldo -= 10;  // Debito do Valor do DOC
			$saldo = number_format($saldo,2,'.','');
			
			if ( $saldo > 0 && $saldo >= 5000 ){
				
				echo " TED --> Franquia: $id - Banco: $banco_cliente - SALDO: $saldo<br>";
				
				$emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
				$tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
				$bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
				$age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
				$cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);
				$vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
				$vlr  = number_format($vlr,2,',', '.');
				
				# Cliente em dia com suas mensalidades
				$NumeroRegistro++;
				$CODLOJA = '99999'.$id;
				// REGISTRO DETALHES DO LOTE
				$Registro = '341';                                         // 001 a 003 - C�digo do banco
				$Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
				$Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
				$Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
				$Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
				$Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
				$Registro .= '000';                                        // 018 a 020 - ZEROS
				$Registro .= str_pad($banco_cliente, 3, 0, STR_PAD_LEFT);  // 021 a 023 - C�digo do Banco do Favorecido
					
				$agencia   = colocazeros(substr($agencia_cliente,0,4),5);
				$conta     = substr($conta_cliente,0,strlen($conta_cliente)-1);
				$conta     = $conta * 1;
				$conta     = str_pad($conta, 12, 0, STR_PAD_LEFT);
				$dac_cta   = substr($conta_cliente,strlen($conta_cliente)-1,1);
				$dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);
						
				$agencia_conta = $agencia.' '.$conta.' '.$dac_cta;
						
				$Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
				$Registro .= str_pad($nome_doc, 30, ' ', STR_PAD_RIGHT);   // 044 a 073 - Nome do Favorecido
				$Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
				$data = date('dmY');
				$Registro .= $data;                                        // 094 a 101 - Data de Pagamento
				$Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
				$Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS

				$valor = $saldo * 100;
				$valor = str_replace(',','',$valor);
				$valor = str_replace('.','',$valor);
				$Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
				$Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
				$Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
				$Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento

				$Registro .= str_pad($CODLOJA, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
				$Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento
					
				$Registro .= str_pad($cpfcnpj_doc, 14, 0, STR_PAD_LEFT);   // 204 a 217 - CPF/CNPJ do Favorecido
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);             // 218 a 219 - Finalidade do DOC
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 220 a 224 - Finalidade do TED
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 225 a 229 - BRANCOS
				$Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);             // 230 a 230 - Aviso ao Favorecido
				$Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);            // 231 a 240 - Ocorrencia de retorno

				$total_ted  += $saldo;
				$totlinha++;
				$registros .= "$Registro\n";
			}
		}
	}
	
	# TRAILLER - TED - OUTROS BANCOS
	
	$Registro  = '341';                                    // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
	$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);                      // 009 a 017 - BRANCO
	$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
	$valor = $total_ted * 100;
	$valor = str_replace(',','',$valor);
	$valor = str_replace('.','',$valor);
	$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores
	$Registro .= '000000000000000000';                     // 042 a 059 - ZEROS
	$Registro .= colocaespacos('',171);                    // 060 a 230 - BRANCOS
	$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
	$totlinha++;
	$registros .= "$Registro\n";
	
	# FIM - TED - OUTROS BANCOS

	#########################################################################################################
	
	# INICIO - DOC - OUTROS BANCOS
	$NumeroLote++;
	$NumeroRegistro = 0;	
	// HEADER
	$Registro  = '341';                               // 001 a 003 - C�digo do Banco
	$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
	$Registro .= '1';                                 // 008 a 008 - Tipo de Registro
	$Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
	$tp_pagto = '20';
	$Registro .= $tp_pagto;                           // 010 a 011 - Tipo de Pagamento | 20-Fornecedores | 30-Salarios

	
	# Forma de Pagamento
	# 01 - Credito em Conta Corrente no ITAU
	# 03 - DOC C
	# 05 - Credito em Conta Poupan�a no ITAU
	# 06 - Credito em Conta Corrente no ITAU de mesma titularidade
	# 07 - DOC D
	# 41 - TED Outro titular
	# 43 - TED Mesmo titular
	# 60 - Cartao Salario

	$forma_pgto = '03';
	
	$Registro .= colocazeros($forma_pgto,2);          // 012 a 013 - Forma de Pagamento
	$Registro .= '040';                               // 014 a 016 - Layout do Lote
	$Registro .= ' ';                                 // 017 a 017 - Em branco
	$Registro .= '2';                                 // 018 a 018 - Empresa Tipo Inscri��o - Debitada
	$Registro .= '08745918000171';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada
	
	$Registro .= $tp_pagto.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
	$Registro .= colocaespacos('',16);                // 037 a 052 - Em branco
	
	$Registro .= '03833';                             // 053 a 057 - Agencia debitada
	$Registro .= ' ';                                 // 058 a 058 - Em branco
	$Registro .= colocazeros('63310',12);             // 059 a 070 - Conta debitada
	$Registro .= ' ';                                 // 071 a 071 - Em branco
	$Registro .= '4';                                 // 072 a 072 - DAC - Digito verificado da conta	
	$Registro .= colocaespacos('WC SIST EQUIP INFORMATICA',30);   // 073 a 102 - Nome da empresa debitada
	$Registro .= colocaespacos('',30);                // 103 a 132 - Finalidade do lote
	$Registro .= colocaespacos('',10);                // 133 a 142 - Hist�rico de conta corrente
	$Registro .= colocaespacosdir('AV. CANDIDO DE ABREU',30);            // 143 a 172 - Endereco da empresa
	$Registro .= colocaespacosdir('70',5);              // 173 a 177 - Numero
	$Registro .= colocaespacosdir('CONJ 404',15);         // 178 a 192 - Complemento
	$Registro .= colocaespacosdir('CURITIBA',20);        // 193 a 212 - Cidade
	$Registro .= colocaespacosdir('80530000',8);         // 213 a 220 - CEP
	$Registro .= colocaespacos('PR',2);               // 221 a 222 - UF
	$Registro .= colocaespacos('',8);                 // 223 a 230 - Em branco
	$Registro .= colocazeros('',10);                  // 231 a 240 - Ocorrencias
	$totlinha++;
	$registros .= "$Registro\n";
	# Fim do header
	
	# Selecionando do Clientes	- CONTA CORRENTE OUTROS BANCOS
    $sql = "SELECT id, MID(razaosoc,1,30) razaosoc, banco, agencia, tpconta, conta, mid(titular,1,30) titular, cpftitular, classificacao  
			FROM cs2.franquia
			WHERE banco <> '341' AND id > 1 AND banco > 0 and situacao_repasse = 0
			ORDER BY id";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		while($registro = mysql_fetch_array($qr_sql)){
			$id = $registro['id'];
			$razaosoc = $registro['razaosoc'];
			$banco_cliente = $registro['banco'];
			$agencia_cliente = $registro['agencia'];
			$conta_cliente = $registro['conta'];
			$cpfcnpj_doc = trim($registro['cpftitular']);
			$nome_doc = $registro['titular'];
			$classificacao = trim($registro['classificacao']);
			
			$saldo = 0;
			
			# verificando o saldo na conta corrente do franqueado
			$sql2 = "select valor,operacao from cs2.contacorrente where franqueado = '$id' order by id asc";
			$xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
			$xqtd = mysql_num_rows($xqr_sql);
			if ( $xqtd > 0 ){
				while($xregistro = mysql_fetch_array($xqr_sql)){
					$valor = $xregistro['valor'];
					$operacao = $xregistro['operacao'];
					if ( $operacao == '0' ) $saldo += $valor;
					else $saldo -= $valor;
				}
			}
			
			$xsql = "SELECT 
						SUM(valor) as valorpg 
			 		 FROM cs2.contacorrente 
					 WHERE franqueado='$id' AND venc_titulo >= NOW()";
					 
			$xqr_sql = mysql_query($xsql,$conexao) or die("Erro zz no SQL: $xsql");
			$array_sql=mysql_fetch_array($xqr_sql);
			$valorpg = $array_sql["valorpg"];
			
			if ( empty($valorpg) ) $valorpg = 0;
			$saldo -= $valorpg;
			$saldo -= 10; // Debito do Valor do DOC
			$saldo = number_format($saldo,2,'.','');


			if ( $saldo > 0 && $saldo < 5000 ){

				echo " DOC --> Franquia: $id - Banco: $banco_cliente - SALDO: $saldo<br>";
				
				$emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
				$tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
				$bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
				$age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
				$cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);
				$vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
				$vlr  = number_format($vlr,2,',', '.');

				$NumeroRegistro++;
				$CODLOJA = '99999'.$id;
				// REGISTRO DETALHES DO LOTE
				$Registro = '341';                                         // 001 a 003 - C�digo do banco
				$Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
				$Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
				$Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
				$Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
				$Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
				$Registro .= '000';                                        // 018 a 020 - ZEROS
				$Registro .= str_pad($banco_cliente, 3, 0, STR_PAD_LEFT);  // 021 a 023 - C�digo do Banco do Favorecido
				
				$agencia   = colocazeros(substr($agencia_cliente,0,4),5);
				$conta     = substr($conta_cliente,0,strlen($conta_cliente)-1);
				$conta     = $conta * 1;
				$conta     = str_pad($conta, 12, 0, STR_PAD_LEFT);
				$dac_cta   = substr($conta_cliente,strlen($conta_cliente)-1,1);
				$dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);
						
				$agencia_conta = $agencia.' '.$conta.' '.$dac_cta;
					
				$Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
				$Registro .= str_pad($nome_doc, 30, ' ', STR_PAD_RIGHT);   // 044 a 073 - Nome do Favorecido
				$Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
				$data = date('dmY');
				$Registro .= $data;                                        // 094 a 101 - Data de Pagamento
				$Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
				$Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS

				$valor = $saldo * 100;
				$valor = str_replace(',','',$valor);
				$valor = str_replace('.','',$valor);
				$Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
				$Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
				$Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
				$Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento
				$Registro .= str_pad($CODLOJA, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
				$Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento
						
				$Registro .= str_pad($cpfcnpj_doc, 14, 0, STR_PAD_LEFT);   // 204 a 217 - CPF/CNPJ do Favorecido
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);             // 218 a 219 - Finalidade do DOC
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 220 a 224 - Finalidade do TED
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 225 a 229 - BRANCOS
				$Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);             // 230 a 230 - Aviso ao Favorecido
				$Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);            // 231 a 240 - Ocorrencia de retorno

				$total_doc  += $saldo;
				$totlinha++;
				$registros .= "$Registro\n";
			}
		}
	}
	
	
	# TRAILLER - DOC - OUTROS BANCOS
	
	$Registro  = '341';                                    // 001 a 003 - C�digo do banco
	$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
	$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);                      // 009 a 017 - BRANCO
	$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
	$valor = $total_doc * 100;
	$valor = str_replace(',','',$valor);
	$valor = str_replace('.','',$valor);
	$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores
	$Registro .= '000000000000000000';                     // 042 a 059 - ZEROS
	$Registro .= colocaespacos('',171);                    // 060 a 230 - BRANCOS
	$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
	$totlinha++;
	$registros .= "$Registro\n";
	
	# FIM - DOC - OUTROS BANCOS

	// TRAILER DO ARQUIVO
	$totlinha++;
	$Registro  = '341';                        // 001 a 003 - C�digo do banco
	$Registro .= '9999';                       // 004 a 007 - N�mero do lote
	$Registro .= '9';                          // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);          // 009 a 017 - BRANCOS
	$Registro .= colocazeros($NumeroLote,6);   // 018 a 023 - Qtd de lotes no arquivo
	$Registro .= colocazeros($totlinha,6);     // 024 a 029 - quantidade de registros do arquivo
	$Registro .= colocaespacos('',211);        // 030 a 240 - USO EXCLUSIVO FEBRABAN/CNAB
	$registros .= "$Registro\n";

	$tot_pagar = $total_conta_corrente + $total_conta_poupanca + $total_doc + $total_ted;
	
	$total_geral = number_format($tot_pagar,2);

	try{
		$arquivo = "../../../download/FQ".date("dmy");
		$arq = "FQ".date("dmy");
		$abrir = fopen($arquivo, "w");
		$escreve = fputs($abrir, $registros);
		fclose($arquivo);
	} catch (Exception $e) {
    	echo 'Erro ao gravar o arquivo: ',  $e->getMessage(), "\n";
	}

	
	echo "<div align='center'>
		  <p>Arquivo Gerado com sucesso</p> 
		  <p><a href=https://www.webcontrolempresas.com.br/download/".$arq.">".$arq."</a><p>
		  <p>Total Geral : R$ $total_geral</p>
		  </div>";

	
	
?>