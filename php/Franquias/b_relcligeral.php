<script>
	function popup(logon){
		window.open('painel.php?pagina1=clientes/a_cons_id.php&codigo='+logon);
	}
	
	function cancela(){
	 window.location.href="painel.php?pagina1=Franquias/b_relcli.php";
	}
</script>

<form name="frm">
<?php
require "connect/sessao.php";

function mascarTelefone($p_telefone){
   if ($p_telefone == '') {
      return ('');
   } else {
	   $a = substr($p_telefone, 0,2);
	   $b = substr($p_telefone, 2,4);
	   $c = substr($p_telefone, 6,4);

	   $telefone_mascarado  = "(";
	   $telefone_mascarado .= $a;
	   $telefone_mascarado .= ") ";
	   $telefone_mascarado .= $b;
	   $telefone_mascarado .= "-";
	   $telefone_mascarado .= $c;
	   return ($telefone_mascarado);
	}
}

$status = $_REQUEST['status'];
$idfranqueado = $_REQUEST['id_franquia'];
$cidade = $_REQUEST['cidade'];

if ($status == '1') $sts = " AND a.sitcli < '2'";
elseif ($status == '2') $sts = " AND a.sitcli = '2'";

if ($cidade != 'TODAS' ) $filtro_bairro = " AND a.cidade = '$cidade'";

$comando = "SELECT a.codloja, a.cidade, mid(b.logon,1,LOCATE('S',logon)-1) as logon, a.nomefantasia, a.fone,
					a.fax, a.celular, a.bairro, a.end, a.numero, a.complemento, a.uf,
					a.nome_consultoria,  date_format(a.data_consultoria, '%d/%m/%Y') AS data_consultoria
			FROM cadastro a
			inner join logon b on a.codloja=b.codloja
			where id_franquia='$idfranqueado' $sts $filtro_bairro
			group by a.codloja
			ORDER BY a.cidade, a.uf , a.bairro, a.end";
$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0")
	{
	echo "<table width='1100' border='0' cellpadding='0' cellspacing='0'>
			<tr height='20'>
				<td align='center' width='100%'>Nenhum cliente cadastrado!</td>
			</tr>
		  </table>";
	}
	else
	{
	echo "<table width='1100' border='0' cellpadding='0' cellspacing='0' class='bodyText' style='font-size:8px'>
	 		<tr>
				<td colspan='6' height='1' bgcolor='#999999'></td>
			</tr>
	 		<tr>
				<td rowspan='$linhas1' width='1' bgcolor='#999999'></td>
			</tr>
			<tr height='20' bgcolor='87b5ff'>
				<td width='50'>Código</td>
				<td width='200'>Nome de Fantasia</td>
				<td width='400'>Endereço</td>
				<td width='200'>Bairro</td>
				<td width='200'>Cidade/UF</td>
				<td width='150'>Consultoria</td>
				<td rowspan='$linhas1' width='1' bgcolor='#999999'></td>
			</tr>
			<tr>
				<td colspan='6' height='1' bgcolor='#666666'>
				</td>
			</tr>";
	  for ($a=0; $a<$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$id = $matriz['codloja'];
		$logon = strtoupper($matriz['logon']);
	  	$nome = strtoupper($matriz['nomefantasia']);
		$nome = substr($nome,0,25);

		$end = strtoupper($matriz['end']);
		$numero = $matriz['numero'];
		$complemento = strtoupper($matriz['complemento']);
		$bairro = strtoupper($matriz['bairro']);
		$cidade = strtoupper($matriz['cidade']);
		$cidade = substr($cidade,0,20);
		$uf = strtoupper($matriz['uf']);
		
		$data_consultoria = strtoupper($matriz['data_consultoria']);
		$nome_consultoria = strtoupper($matriz['nome_consultoria']);
		
		if ( $_SESSION['id'] == 163 ){
			if ( !empty($data_consultoria) )
				$link_consultoria = "<a href='painel.php?pagina1=Franquias/consultoria3.php&codloja=$id&status=$status&idfranqueado=$idfranqueado&cidade=$cidade&cliente=$nome'>
				<font color='#0000CC'>$data_consultoria - $nome_consultoria</font></a>";
			else
				$link_consultoria = "<a href='painel.php?pagina1=Franquias/consultoria.php&codloja=$id&status=$status&idfranqueado=$idfranqueado&cidade=$cidade'>
				<font color='#FF0000'>Consultoria pendente</font></a>";
		}else{
			$link_consultoria = '';
		}
		$endereco = '';
		$endereco .= $end;
		if ( strpos($endereco,$numero) == 0 )
			$endereco .= $numero;
			
		if ( !empty($complemento) )
			$endereco .= ' '.$complemento;

		$fone1 = mascarTelefone($matriz['fone']);
		$fone2 = mascarTelefone($matriz['fax']);
		$celular = mascarTelefone($matriz['celular']);

	  	echo "<tr height='24'";
		if (($a%2) == 0) {
			echo "bgcolor='#E5E5E5'>";
		} else {
			echo ">";
		}
		echo "	<td>
					<a href='#' onclick='popup($logon)'>$logon</a>
				</td>
	  	      	<td>$nome</td>
				<td>$endereco</td>
				<td>$bairro</td>
				<td>$cidade / $uf</td>
	  	      	<td>$link_consultoria</td>
	  	      	</tr>";
		}

		echo "<tr>
				<td colspan='6' align='right' height='1' bgcolor='#666666'>
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan='2' align='right'>TOTAL DE CLIENTES</td>
				<td align='center'><b>$a</b></td>
				<td colspan='2'></td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<div align="center"><input type="button" onClick="cancela()" value="       Voltar       " /></div>
</form>