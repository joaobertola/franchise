<?php

require "connect/sessao.php";
require "connect/conexao_conecta.php";

if ( $_SESSION['ss_tipo'] == 'b' )
	$id_franquia = $_SESSION['id'];
else
	$id_franquia = 1;
	
if ( $id_franquia == 1 ){
	$compl = "or b.classificacao = 'X'";
}

$sql_lista ="SELECT a.codloja, count(*) qtd, a.celular, a.sitcli, a.sit_cobranca FROM cs2.cadastro a
			 INNER JOIN cs2.franquia b ON a.id_franquia = b.id
			 INNER JOIN cs2.titulos c ON a.codloja = c.codloja
			 WHERE ( a.id_franquia = $id_franquia $compl )
					AND c.datapg is NULL
					AND mid(a.celular,3,1) = '9'
					AND a.celular <> '' 
					AND c.vencimento < NOW() 
					AND a.sit_cobranca = 0 
			 GROUP BY a.codloja";
$qry_lista = mysql_query($sql_lista,$con) or die($sql_lista);
$reg1 = 0;$reg2 = 0;$regm = 0; $reg_blq = 0;
if ( mysql_num_rows($qry_lista) > 0 ){
	while ( $registro = mysql_fetch_array($qry_lista) ){
		$qtd = $registro['qtd'];
		$celular = $registro['celular']*1;
		$celular = str_pad($celular,11,0,STR_PAD_LEFT);
		$celular = preg_replace("/[^0-9]/", "", $celular); 
		
		$codloja = $registro['codloja'];
		$sitcli = $registro['sitcli'];
		$sit_cobranca = $registro['sit_cobranca'];
		
		if ( $sitcli < 2){		
		
			$br = "";
			if ( $qtd == 1 ){
				$reg1++;
				if ( $reg1 == 10 ){
					$br = "<br>";
					$reg1 = 0;
				}
				$qtd1 .= $celular.";$br";
			}elseif ( $qtd == 2 ){
				$reg2++;
				if ( $reg2 == 10 ){
					$br = "<br>";
					$reg2 = 0;
				}
				$qtd2 .= $celular.";$br";
			}else{
				$regm++;
				if ( $regm == 10 ){
					$br = "<br>";
					$regm = 0;
				}
				$qtd_mais .= $celular.";$br";
			}
			
		}else{
			# CLIENTE CANCELADO
			$reg_blq++;
			$br = '';
			if ( $reg_blq == 10 ){
				$br = "<br>";
				$reg_blq = 0;
			}
			$qtd_blq .= $celular.";$br";
		}
	}
	echo "
		<table border = 0  >
			<tr height='20'>
			<td  class='titulo'><font style='font-size:18px'>Rela&ccedil;&atilde;o de Clientes<br>Cobran&ccedil;a SMS</font></td>
			</tr>
			<tr height='1'>
				<td height='1'>&nbsp;</td>
			</tr>
			<tr bgcolor='87b5ff'>
				<td align='center' >Celular(es) de clientes ATIVOS ou BLOQUEADOS com 1 fatura pendente</td>
			</tr>
			<tr bgcolor='D1D7DC'>
			<td class='tabela'>$qtd1</td>
			</tr>
			
			<tr height='1'>
				<td height='1'>&nbsp;</td>
			</tr>
			
			<tr bgcolor='87b5ff'>
				<td align='center' >Celular(es) de clientes ATIVOS ou BLOQUEADOS com 2 faturas pendentes</td>
			</tr>
			<tr bgcolor='D1D7DC'>
			<td class='tabela'>$qtd2</td>
			</tr>
			
			<tr height='1'>
				<td height='1'>&nbsp;</td>
			</tr>
			
			<tr bgcolor='87b5ff'>
				<td align='center' >Celular(es) de clientes ATIVOS ou BLOQUEADOS com 3 ou mais faturas pendentes</td>
			</tr>
			<tr bgcolor='D1D7DC'>
			<td class='tabela'>$qtd_mais</td>
			</tr>
			
			<tr height='1'>
				<td height='1'>&nbsp;</td>
			</tr>
			
			<tr bgcolor='87b5ff'>
				<td align='center' >Celular(es) de clientes CANCELADOS e com faturas pendentes</td>
			</tr>
			<tr bgcolor='D1D7DC'>
			<td class='tabela'>$qtd_blq</td>
			</tr>
			</table>";
	
}

?>