<?php

function geraTimestamp($data) {
        $partes = explode('/', $data);
        return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}

require "connect/sessao.php";
require "connect/funcoes.php";
require "data.php";

$id_titulo = $_REQUEST['id_titulo'];

$sql = "SELECT 
			a.numboleto, a.codloja, a.emissao, a.vencimento, a.valor,
			b.end, b.numero, b.complemento, b.bairro, b.cidade, b.uf, b.cep, b.email, b.insc, b.razaosoc, MID(c.logon,1,LOCATE('S', c.logon) - 1) as logon
		FROM cs2.titulos_antecipacao a
		INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
		INNER JOIN cs2.logon c ON a.codloja = c.codloja
		WHERE id_antecipacao = $id_titulo";
$qry = mysql_query($sql,$con);

if ( mysql_num_rows($qry) == 0 ){
	echo "Erro ao Gerar Boleto - Boleto inexistente";
	exit;
}

$codloja     = mysql_result($qry,0,'codloja');
$emissao     = mysql_result($qry,0,'emissao');
$emissao     = data_mysql_i($emissao);
$vencimento  = mysql_result($qry,0,'vencimento');
$vencimentom = data_mysql_i($vencimento);
$valor       = mysql_result($qry,0,'valor');
$valor_mostra= 'R$ '.number_format($valor,2,',','.');
$end         = mysql_result($qry,0,'end');
$numero      = mysql_result($qry,0,'numero');
$complemento = mysql_result($qry,0,'complemento');
$bairro      = mysql_result($qry,0,'bairro');
$cidade      = mysql_result($qry,0,'cidade');
$uf          = mysql_result($qry,0,'uf');
$cep         = mysql_result($qry,0,'cep');
$logon       = mysql_result($qry,0,'logon');

$endereco    = $end;
if (  $numero != '' ) $endereco .= ', '.$numero;
if (  $complemento != '' ) $endereco .= ' '.$complemento;
if (  $bairro != '' ) $endereco .= ' - '.$bairro;
if (  $cep != '' ) $endereco .= '<br>'.$cep;
if (  $cidade != '' ) $endereco .= ' - '.$cidade;
if (  $ud != '' ) $endereco .= ' - '.$uf;

$email       = mysql_result($qry,0,'email');
$razaosoc    =  mysql_result($qry,0,'razaosoc');
$cpfcnpj     =  mysql_result($qry,0,'insc');
$numboleto   = mysql_result($qry,0,'numboleto');

if ( $numboleto == '' ){
	
	$sql ="select contador_recebafacil + 1 as contador_recebafacil  from cs2.controle_boletos limit 1";
	$qr = mysql_query($sql,$con) or die ("Erro ao selecionar o contador de boletos");
	$numboleto = mysql_result($qr,0,'contador_recebafacil');
	$sql ="update cs2.controle_boletos set contador_recebafacil = contador_recebafacil + 1";
	$qr = mysql_query($sql,$con) or die ("Erro ao atualizar contador de boletos");
	
	$sql =" UPDATE cs2.titulos_antecipacao 
				SET numboleto = $numboleto
			WHERE id_antecipacao = $id_titulo";
	$qr = mysql_query($sql,$con) or die ("Erro ao atualizar titulos Antecipacao");
}

$numboleto  = $numboleto * 1;
$numboleto  = str_pad($numboleto,8,0,STR_PAD_LEFT);
$carteira   = '175';
$banco      = '341';
$agencia    = '8616';
$conta      = '21201'; // ITAU - ISPCN
$moeda      = '9';
$DAC1       = $agencia.$conta.$carteira.$numboleto;
$DAC1       = dig10bb($DAC1);
$DAC2       = $agencia.$conta;
$DAC2       = dig10bb($DAC2);

$valor      = str_pad(str_replace('.','',$valor),10,0,STR_PAD_LEFT);
$valor      = str_pad(str_replace(',','',$valor),10,0,STR_PAD_LEFT);

$nosso      = "$carteira/$numboleto-$DAC1";
$vencimento = str_replace('-','',$vencimento);
$fator      = fvenc(str_replace('-','',$vencimento),19971007);

$digito = $banco.$moeda.$fator.$valor.$carteira.$numboleto.$DAC1.$agencia.$conta.$DAC2.'000';
			
$tamanho = strlen($digito);
$dv = dig11bar($digito,$tamanho);
			
$codbar = $banco.$moeda.$dv.$fator.$valor.$carteira.$numboleto.$DAC1.$agencia.$conta.$DAC2.'000';

$codigo = BarCode($codbar); // imagem do codigo de barras

$p1 = substr($codbar,0,4);
$p2 = substr($codbar,19,5);
$p3 = dig10bb($p1.$p2);
$p4 = $p1.$p2.$p3;
$p5 = substr($p4,0,5);
$p6 = substr($p4,5,5);
$Campo1 = $p5.'.'.$p6;

$p1 = substr($codbar,24,10);
$p2 = dig10bb($p1);
$p3 = $p1.$p2;
$p4 = substr($p3,0,5);
$p5 = substr($p3,5,6);
$Campo2 = $p4.'.'.$p5;

$p1 = substr($codbar,34,10);
$p2 = dig10bb($p1);
$p3 = $p1.$p2;
$p4 = substr($p3,0,5);
$p5 = substr($p3,5,6);
$Campo3 = $p4.'.'.$p5;

$Campo4 = substr($codbar,4,1);

$Campo5 = substr($codbar,5,14);

$numerosup = $Campo1." ".$Campo2." ".$Campo3." ".$Campo4." ".$Campo5;

$nvalor = str_replace(',','.',$valor);

$encargosdia = ($nvalor * 0.0031 );

$xencargosdia = number_format ($encargosdia, 2, ',', '.');

?>
<html>
<head>
<title>Web Control Empresas - Boleto Banc&aacute;rio - ANTECIPA&Ccedil;&Atilde;O DE CR&Eacute;DITO EM ATRASO</title>
<link href="../../../inform/boleto/boleto.css" rel="stylesheet" type="text/css">
<style type="text/css" media="print">
.noprint {display:none;
}
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" topmargin="0"  background="../../images/fundo_10_03_2010.jpg"> 
<table width="800" border="0" cellspacing="5" cellpadding="0" align="center">
		<tr valign="bottom" class="noprint">
			<td width="85%" align="left">
            	<a href="#" onClick="JavaScript:self.print()">
                <img src="https://www.webcontrolempresas.com.br/images/print.gif" alt="Imprimir" border="0">
                <br>
                <span class="bodyText">Imprimir</span></a>
			</td>
		</tr>
	</table>
	<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr> 
			<td width="30%" height="40" align="left"><img src="https://www.webcontrolempresas.com.br/inform/boleto/imgs/web_control_azul.png" height="50"></td>
			<td width="70%" class="formulario" align="left"><i><font size="+1"> Tecnologia, Automação, Consultas e Sites</font></i></td>
		</tr>
		<tr>
			<td colspan="2" height="30" class="formulario" style="text-align:center">BOLETO REFERENTE A PARCELA(S) DE ANTECIPACAO  EM ATRASO</td>
		</tr>
		<tr>
			<td colspan="2" align="left" class="bodyText">C&oacute;digo: <?=$logon?></td>
		</tr>
	</table> 
	<table width="800" cellspacing="0" align="center" border="0" cellpadding="1" style="border-collapse: collapse">
		<tr  bgcolor="#D3D3D3" align="center">
			<td width="20%" height="25" class="linha_topo_1">Parcela</td>
			<td width="20%" class="linha_topo_3" align="center">Vencimento</td>
			<td width="20%" class="linha_topo_2" align="center">Dias em Atraso</td>
            <td width="20%" class="linha_topo_2" align="center">Valor Principal</td>
			<td width="20%" class="linha_topo_2" align="center">Valor Corrigido</td>
		</tr>
        <?php
        # Verificando o contrato em atraso.
		$sql = "SELECT 
					protocolo, qtd_parcelas, numero_parcela, 
					date_format(data_vencimento,'%d/%m/%Y') as vencimento, 
					vr_emprestimo_solicitado, valor_parcela
				FROM cs2.cadastro_emprestimo
				WHERE 
						data_vencimento <= NOW() 
					AND 
						valor_pagamento IS NULL
					AND 
						id = $id_titulo";
		$qry = mysql_query($sql,$con);
		while ( $reg = mysql_fetch_array($qry) ){
			$valor         = $reg['valor_parcela'];
			$contrato      = $reg['protocolo'];
			$vr_emprestimo = $reg['vr_emprestimo_solicitado'];
			$vr_emprestimo = number_format($vr_emprestimo,2,',','.');
			$parcela       = $reg['numero_parcela'].'/'.$reg['qtd_parcelas'];
			$data_hoje     = $vencimentom;
			$vencimento    = $reg['vencimento'];
			$time_inicial  = geraTimestamp($vencimento);
			$time_final    = geraTimestamp($data_hoje);
			$diferenca     = $time_final - $time_inicial; // segundos
			$dif_dias      = (int)floor( $diferenca / (60 * 60 * 24)); // dias
			$multa         = $valor * 0.02;
			$juros_dias    = 5 / 30;
			$vrjuros       = $valor * ( ( $dif_dias * $juros_dias ) / 100 );
			$vr_parc_atual = $valor + $multa + $vrjuros;
			$total_cliente += $vr_parc_atual;
			$vr_parc_atual = number_format($vr_parc_atual,2, ',','.');
			$valor_parcela = number_format($valor,2, ',','.');
			
			echo "
			
				<tr>
					<td class='linha2' align='center'>$parcela</td>
					<td class='linha2' align='center'>$vencimento</td>
					<td class='linha2' align='center'>$dif_dias</td>
					<td class='linha2' align='center'>R$ $valor_parcela</td>
					<td class='linha2' align='center'>R$ $vr_parc_atual</td>
				</tr>			
			";

		}
		
		?>
        

		<tr class="formulario" align="right" bgcolor="#D3D3D3">
			<td height="21" colspan="4" align="center" class="linha_rodape_1">Protocolo: <?php echo "$contrato - Valor Antecipado: $vr_emprestimo";?></td>
			<td class="linha_rodape_2" align="center"><?=$valor_mostra?></td>
		</tr>
	</table>
    
	<table border="0" width="800" align="center" cellpadding="0" cellspacing="3">
		<tr align="center">
			<td>&nbsp;</td>
		</tr>
</table>
	<table border="0" cellpadding="0" cellspacing="0" width="800" align="center" bgcolor="#FFFFFF">
	  <tbody> 
			<tr>
				<td> 
					<table border="1" width="800">
                    	<tr>
							<td class="numBanco"><img src="https://www.webcontrolempresas.com.br/images/itau.jpg"> | 341-7 |</td>
							<td class="ipte">Recibo do Sacado</td> 
						</tr>
					</table>
                    
					<table cellpadding="0" cellspacing="0" width="100%" class="tabela"> 
						<tr class="cellTitle">
							<td width="21%" class="bottonLineR">Vencimento<br>
                            	<div align="center" class="cellBody" style="font-weight:bold"><?=$vencimentom?></div></td>
							<td width="23%" class="bottonLineR">Ag./Conta do Cedente<br>
								<span class="cellBody">8616 / 21.199-7</span></td>
							<td width="6%" class="bottonLineR">Esp&eacute;cie<br>
								<span class="cellBody"> R$</span></td>
							<td width="25%" class="bottonLineR">Nosso Numero<br>
								<span class="cellBody"><?=$numboleto?></span></td>
							<td width="25%" class="bottonLine">Numero do Documento<br>
								<span class="cellBody"><?=$contrato?></span></td>
						</tr>
						<tr class="cellTitle">
							<td class="bottonLineR">(=) Valor do Documento<br> 
								<div align="center"><span class="cellBodyD"><font size="1" face="tahoma"><?=$valor_mostra?></font></span></div></td>
							<td class="bottonLineR">(+) Multa<br> 
								<div align="center"><span class="cellBodyD"><font size="1" face="tahoma">&nbsp;</font></span></div></td>
							<td colspan="2" class="bottonLineR">(+) Outros Acrescimos<br> 
								<div align="center"><span class="cellBodyD"><font size="1" face="tahoma">&nbsp;</font></span></div></td>
							<td class="bottonLine">(=) Valor Cobrado<br>
								<div align="center"><span class="cellBodyD" style="font-size:12px">&nbsp;</span></div></td>
						</tr>
						<tr class="cellTitle"> 
							<td colspan="5">Sacado <span class="cellBody">&nbsp;&nbsp;&nbsp;<?=$razaosoc?></span></td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" align="center" width="800"> 
		<tbody> 
			<tr class="bodyText">
				<td colspan="3" align="center">Autenticacao Mecanica
					<hr class="divisor" noshade="noshade" size="1">
				</td> 
			</tr> 
		</tbody> 
	</table>
         
<table border="0" cellpadding="0" cellspacing="0" align="center" width="800"> 
		<tr>               
			<td class="numBanco"><img src="https://www.webcontrolempresas.com.br/images/itau.jpg"> | 341-7 | </td>
			<td class="ipte" width="*"><font size="3" face="tahoma"><?=$numerosup?></font></td> 
		</tr>
	</table> 
        
<table class="tabela" cellpadding="0" cellspacing="0" align="center" width="800"> 
		<tbody>
			<tr class="cellTitle"> 
				<td colspan="5" width="500" class="bottonLineR">Local de Pagamento<br>
					<span class="cellBody">&nbsp;&nbsp;BANCOS / INTERNET / CASAS LOT&Eacute;RICAS / CORREIOS E TERMINAIS</span></td>
				<td width="170" class="bottonLine">Vencimento<br> 
					<div align="right"><span class="cellBodyD"><font style="font-size: 12px" face="tahoma"><strong><?=$vencimentom?></strong></font></span></div></td> 
			</tr> 
			<tr class="cellTitle"> 
				<td colspan="5" width="500" class="bottonLineR">Cedente<br>
					<span class="cellBody">WEB CONTROL EMPRESAS - SITES, SOLU&Ccedil;&Otilde;ES E PESQUISAS</span></td>
				<td width="170" class="bottonLine">Agencia/Conta do Cedente<br> 
					<div align="right"><span class="cellBody"><font size="1" face="tahoma">8616 / 21.199-4</font></span></div></td>
			</tr>
			<tr class="cellTitle">
				<td width="85" class="bottonLineR">Data de Emissao<br>
					<span class="cellBody"><font size="1" face="tahoma"><?=$emissao?></font></span></td>
				<td width="115" class="bottonLineR">Numero do Documento<br>
					<span class="cellBody"><font size="1" face="tahoma"><?=$contrato?></font></span></td>
				<td width="110" class="bottonLineR">Especie Doc<br>
					<span class="cellBody"><br></span></td>
				<td width="70" class="bottonLineR">Aceite<br>
					<span class="cellBody">N</span></td>
				<td width="120" class="bottonLineR">Data do Processamento<br>
					<span class="cellBody"><font size="1" face="tahoma"><?=$emissao?></font></span></td> 
				<td width="170" class="bottonLine">Nosso Numero<br>
					<div align="right"><span class="cellBody"><font size="1" face="tahoma"><?=$numboleto?></font></span></div></td>
			</tr>
			<tr class="cellTitle">
				<td width="85" height="31" class="bottonLineR">Uso do Banco<br>
					<span class="cellBody"><br></span> </td>
				<td width="115" class="bottonLineR">Carteira<br>
				  <font size="1" face="tahoma"> 175</font></td>
				<td width="110" class="bottonLineR">Especie<br>
					<span class="cellBody">R$<br></span> </td>
				<td width="70" class="bottonLineR">Quantidade<br>
					<span class="cellBody"><br></span> </td>
				<td width="110" class="bottonLineR">Valor<br>
					<span class="cellBody"><br></span> </td>
				<td width="170" class="bottonLine" >(=) Valor do Documento<br>
					<div align="right"><span class="cellBodyD"><font style="font-size: 12px" face="tahoma"><?=$valor_mostra?></font></span></div></td>
			</tr>
            <tr class="cellTitle"> 
				<td colspan="5" rowspan="5" class="bottonLineR"><p>Instrucoes<br>
			      <br>
			      <span class="cellBody"><BR>
			      * ESTE T&Iacute;TULO SER&Aacute; PROTESTADO APÓS O VENCIMENTO.
			      <br>
			      <br>
			      C&oacute;digo : <?=$logon?>
			      </span></p>
				</td>
				<td width="170" class="bottonLine">(-) Desconto/Abatimento<br>
					<div align="right"><span class="cellBody" style="font-size:20px">&nbsp;</span></div></td>
			</tr>
			<tr class="cellTitle">
				<td width="170" class="bottonLine">(-) Outras Deducoes<br>
					<div align="right"><span class="cellBody" style="font-size:20px">&nbsp;</span></div></td>
			</tr>
			<tr class="cellTitle">
				<td width="170" class="bottonLine">(+) Multa<br>
					<div align="right"><span class="cellBodyD"><font size="1" face="tahoma" style="font-size:20px">&nbsp;</font></span></div></td>
			</tr>
			<tr class="cellTitle">
				<td width="170" class="bottonLine">(+) Outros Acrescimos<br> 
<div align="right"><span class="cellBodyD" style="font-size:20px">&nbsp;</span></div></td> 
			</tr> 
			<tr class="cellTitle"> 
				<td width="170" class="bottonLine">
					<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
						<tr class="cellTitle">
							<td width="60%">(=) Valor Cobrado</td>
							<td width="40%" align="right"><span class="cellBodyD" style="font-size:20px">&nbsp;</span></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="7" width="100%">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>
							<tr style="font-size:13px">
								<td class="cellTitle" width="100">Sacado</td>
								<td colspan="2">
									<?=$razaosoc?> - CPF/CNPJ: <?=$cpfcnpj?><br>
									<?=$endereco?>
								</td>
							</tr>
							<tr class="cellBody">
								<td></td>
								<td colspan="2"></td>
							</tr>
							<tr class="cellBody">
								<td></td>
								<td colspan="2"></td>
							</tr>
							<tr class="cellBody" colspan="3">
								<td></td>
							</tr>
							<tr class="cellTitleB">
								<td></td>
								<td class="cellBody"></td>
								<td width="200">C&oacute;digo de Baixa</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
<table border="0" cellpadding="0" cellspacing="0" align="center" width="800">
		<tbody>
			<tr>
				<td class="cellTitle"><div align="right">Autenti&ccedil;&atilde;o Mec&acirc;nica - Ficha de Compensa&ccedil;&atilde;o</div></td>
			</tr>
		</tbody>
	</table>
<table border="0" align="center" width="800"> 
		<tbody> 
			<tr> 
				<td width="15"></td> 
				<td class="cellBody"><font size="1" face="tahoma"><?=$codigo?></font><br></td> 
			</tr> 
		</tbody> 
	</table>
</body>
</html>