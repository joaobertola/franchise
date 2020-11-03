<form method="post" action="painel.php?pagina1=Franquias/b_baixafatura.php">
<div align="center"><input type="submit" value="       Voltar       " /></div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
function PrintDiv(div)
{
	var divContents = $('#'+div).html();
	var printWindow = window.open('', '', 'height=768,width=1024');
	printWindow.document.write('<html><head><title>Faturas</title>');
	printWindow.document.write('</head><body>');
	printWindow.document.write(divContents);
	printWindow.document.write('</body></html>');
	printWindow.document.close();
	printWindow.print();
}
</script>
</form>
<?php
require "connect/sessao.php";


$situacao 	= $_REQUEST['situacao'];
$periodo 	= $_REQUEST['periodo'];
$vencimento1 = $_REQUEST['vencimento1'];
$vencimento2 = $_REQUEST['vencimento2'];
$franqueado = $_REQUEST['franqueado'];
$cobranca 	= $_REQUEST['cobranca'];
$ordem 		= $_REQUEST['ordem'];
$lpp 		= $_REQUEST['lpp'];
$excel 		= $_REQUEST['excel'];
$pagina 	= $_REQUEST['pagina'];
$codigo1 	= $_REQUEST['codigo1'];
$flag_multa = $_REQUEST['flag_multa'];

$atz_tabela = $_REQUEST['at_tabela'];

//fun��o para tratar os resultados com 2 decimais
function FloatFormat($Value, $Precision) { 
    $decimals = log10(abs($Value)); 
    $decimals = - (intval(min($decimals, 0)) - $Precision); 
	if ($decimals = 2 ) $format = "%." . $decimals . "f";
    return sprintf($format, $Value); 
}
//alterna entre formato mysql e formato data anbt
$venc1 = implode(preg_match("~\/~", $vencimento1) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $vencimento1) == 0 ? "-" : "/", $vencimento1)));
$venc2 = implode(preg_match("~\/~", $vencimento2) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $vencimento2) == 0 ? "-" : "/", $vencimento2)));

//aqui come�a a programa��o em si
if (isset($periodo)) 
	$intervalo = "and a.vencimento between '$venc1' and '$venc2'";
else $intervalo = "";
if (!$venc1 || !venc2) $intervalo = "";
if ($cobranca == true) $cobr = "and c.sit_cobranca<>1";
else $cobr = "";

if (($tipo == "a") || ($tipo == "c")){

	if ( $franqueado != 'todos' )
		if ( $id_franquia == 163 ){
			if ( $franqueado == 1 )
				$frq = "and ( c.id_franquia='1' or f.classificacao = 'X' )";
			else
				$frq = "and c.id_franquia = '$franqueado'";
		}else
			$frq = "and c.id_franquia = '$franqueado'";
		
	else{
		if ( $franqueado != 'todos' ){

			if ( $id_franquia == 163 ){
				if ( $franqueado == 1 )
					$frq = "and ( c.id_franquia='1' or f.classificacao = 'X' )";
				else
					$frq = "and c.id_franquia = '$franqueado'";
			}
		}
	}
	
}else{
	
	$frq = "and c.id_franquia='$id_franquia'";
}

$multa_nao = '';
if ($flag_multa == 'on')
$multa_nao = " a.referencia <> 'MULTA' and ";

if ( $tipo == 'a' )
	if (! empty($codigo1))
		 $frq .= " and d.logon like '$codigo1%' ";

$command = "select a.numdoc, MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, c.fone, c.celular, c.nomefantasia, a.valor,
				   DATE_FORMAT(a.vencimento,'%d/%m/%Y') as vencimento, a.valorpg, a.codloja, 
				   a.referencia, a.datapg as datapg2,DATE_FORMAT(a.datapg,'%d/%m/%Y') as datapg, 
				   a.numboleto, a.origem_pgto 
			FROM titulos a
			inner join logon b on a.codloja = b.codloja
			inner join cadastro c on a.codloja = c.codloja
			inner join cs2.logon d on a.codloja = d.codloja
			inner join franquia f on c.id_franquia = f.id
			where $multa_nao a.debito is not null $situacao $intervalo $frq $cobr
			group by numdoc
			order by $ordem, a.vencimento";

$res = mysql_query($command,$con);
$linhas = mysql_num_rows($res);

$sql_cobradoras = "SELECT id, nome, funcao FROM cs2.funcionario
					WHERE funcao = 'AUXILIAR DE COBRAN�AS' AND ativo = 'S' AND id <> 90";
$qry_cobradoras = mysql_query($sql_cobradoras,$con);
$qtd_cobradoras = mysql_num_rows($qry_cobradoras);
$x = 0;
$cobradoras = array();
while ($m = mysql_fetch_array($qry_cobradoras))
{
	$x += 1;
	$nome = $m['nome'];
	$id_cobradora = $m['id'];
	$cobradoras[$x] = $nome.';'.$id_cobradora;
	
}

$registro = $linhas / $qtd_cobradoras;
$registro = round($registro);
$faixa_i = 1;
$faixa_f = $registro;

if ( $atz_tabela == 'SIM' ){

	for ( $i = 1 ; $i <= $qtd_cobradoras ; $i++ ){
		
		$nom_cobradora = $cobradoras[$i];
		$array_cobradora = explode(';',$nom_cobradora);
		$nome_cobradora = $array_cobradora[0];
		$id_cobradora   = $array_cobradora[1];
		
		$inicio = $faixa_i;
		$final  = $faixa_f;
		$sql = $command." limit $inicio,$registro ";
		
		//echo "[$sql]";
		
		$res = mysql_query($sql,$con);
		$class_cob = str_replace(' ','_',$nome_cobradora);
		$nome_cobradora = "<a href='#' onclick=\"PrintDiv('$class_cob')\">$nome_cobradora<a>";
		
		echo"
		<div id='$class_cob'>
		<table align='center' border='0' cellpadding='0' cellspacing='0' class='bodyText' width='85%'>
			<tr>
				<td colspan='4' class='titulo' style='font-size:9px' align='center'>Auxiliar de Cobran&ccedil;a: [ $nome_cobradora ]</td>
			</tr>
			<tr class='subtitulodireita'>
				<td align='center' style='font-size:9px'>Cliente</td>
				<td align='center' style='font-size:9px'>Vencimento</td>
				<td align='center' style='font-size:9px'>Valor (R$)</td>
			</tr>
			<tr>
				<td colspan='4' height='1' bgcolor='#666666'>
				</td>
			</tr>";
			
			while ($matriz = mysql_fetch_array($res))
			{
				$a = $a + 1;
				$numdoc = $matriz['numdoc'];

				$sql = "SELECT * FROM cs2.titulos_cobradora WHERE numdoc = '$numdoc' order by id";
				$qr = mysql_query($sql,$con) or die ("\n 01: Erro ao pesquisar [atualiza_cobradora_titulos]\n".mysql_error()."\n\n");
				if ( mysql_num_rows($qr) > 0 ){
				$id = mysql_result($qr,0,'id');
				$sql_u = "UPDATE cs2.titulos_cobradora
							SET
								id_cobradora = '$id_cobradora',
								data_gravacao = NOW(),
								hora_gravacao = NOW()
						  WHERE id = '$id'";
				$qr2 = mysql_query($sql_u,$con) or die ("\n 02: Erro ao pesquisar [atualiza_cobradora_titulos]\n".mysql_error()."\n\n");		  

			}else{

				$sql_u = "INSERT INTO cs2.titulos_cobradora
							(numdoc, id_cobradora , data_gravacao, hora_gravacao )
						  VALUES
						  	('$numdoc' , '$id_cobradora', NOW() , NOW() )";
				$qr2 = mysql_query($sql_u,$con) or die ("\n 02: Erro: $sql_u]\n".mysql_error()."\n\n");		  
			}
			$logon = $matriz['logon'];
			$nomefantasia = $matriz['nomefantasia'];
			$valor = $matriz['valor'];
			$vencimento = $matriz['vencimento'];
			$referencia = $matriz['referencia'];
			if ( $referencia == 'MULTA' ) $ref = '(multa)';
			else $ref = '';
			
			$registros[$i][$numdoc] = "$logon;$nomefantasia;$vencimento,$valor";
			
			echo "<tr class='subtitulodireita_2' height='20'";
			if (($a%2) == 0) {
				echo "bgcolor='#E5E5E5'>";
			} else {
				echo ">";
			}
			echo "
					<td style='font-size:9px'>$logon - $nomefantasia</td>
					<td style='font-size:9px' align=\"center\">$vencimento</td>";
			?>		
					<td style='font-size:9px' align="center"><?=number_format($valor,2,',','.').' '.$ref;?></td>
			<?php
			echo "</tr>";
			
		}
		echo "</table>
		</div>
		<br><br>";
		$faixa_i = $final + 1;
		$faixa_f = $final + $registro;
	}
}else{
		
	if ($linhas == "0")
	{
		echo "<table width=100% border='0' cellpadding='0' cellspacing='0'>
			<tr height=\"20\" class=\"titulo\">
			<td align=\"center\" width=\"100%\">Nenhum cliente cadastrado neste periodo!</td></tr></table>";
	} else {
		//come�a a tabela
		echo "<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\" width='85%'>
			<tr>
				<td colspan=\"12\" class=\"titulo\">Faturas</td>
			</tr>
			<tr>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr class=\"subtitulodireita\">";
			echo "	
				<td align=\"center\">C&oacute;digo</td>
				<td align=\"center\">Nome Fantasia</td>
				
				<td align=\"center\">Vencimento</td>
				<td align=\"center\">Valor (R$)</td>
				
				<td align=\"center\">Val. Pg. (R$)</td>
				<td align=\"center\">Data Pg.</td>";
				echo" <td align=\"center\">Origem Pgto.</td>
				<td align=\"center\">Quitar</td>
				<td align=\"center\">Excluir</td>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr>
				<td colspan=\"12\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>";
		while ($matriz = mysql_fetch_array($res))
		{
			$a = $a + 1;
			$numdoc = $matriz['numdoc'];
			$codloja = $matriz['codloja'];
			$logon = $matriz['logon'];
			$fone = $matriz['fone'];
			$celular = $matriz['celular'];
			$nomefantasia = $matriz['nomefantasia'];
			$valor = $matriz['valor'];
			$vencimento = $matriz['vencimento'];
			$valorpg = $matriz['valorpg'];
			$datapg = $matriz['datapg'];
			$numboleto = $matriz['numboleto'];
			$origem_pgto = $matriz['origem_pgto'];
				
			$referencia = $matriz['referencia'];
			$dtpagamento = $matriz['datapg2'];
		
			if ( $referencia == 'MULTA' ) $ref = '(multa)';
			else $ref = '';
		
			if (!$valorpg) {
				$soma = $soma + $valor;
				$soma2 = $soma2 + 0;
			} else {
				$soma = $soma + 0;
				$soma2 = $soma2 + $valorpg;
			}
			$limite = 40;
			$string2 = $nomefantasia;
			$sizeName2 = strlen($string2);		
			echo "<tr class='subtitulodireita_2' height=\"30\"";
			if (($a%2) == 0) {
				echo "bgcolor=\"#E5E5E5\">";
			} else {
				echo ">";
			}
			echo "
					<td align=\"center\">$logon</td>
					<td>";
					for($num2=0;$num2<$limite;$num2++) {
						print($string2[$num2]);
					}
					if($sizeName2>$limite){echo"...";}
			echo "</td>
					<td align=\"center\">$vencimento</td>";
			?>		
					<td align="center"><?=number_format($valor,2,',','.').' '.$ref;?></td>
			<?php
			echo "<td align=\"center\">$valorpg</td>
					<td align=\"center\">$datapg</td>";
			echo "<td align=\"center\">$origem_pgto</td>";
			if ($datapg != "") {
					echo "<td></td>";
			} else {
				if( $_SESSION["id"] == '163' ){
					echo "<td align=\"center\">
						<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo\" onMouseOver=\"window.status='Quitar Titulo'; return true\">
							<IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"10\" border=\"0\">
							</a>
						  </td>";
	echo "<td align=\"center\">
						<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc&codloja=$codloja\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\">
							<IMG SRC=\"../img/exc.gif\" width=\"16\" height=\"10\" border=\"0\">
							</a>
						  </td>";
				}
				echo "<td></td>";
			} //fim else
			echo "</tr>";
		}
	}
	// VERIFICA SE O CLIENTE TEM BOLETO DE MULTA E NAO FOI PAGO
	$command = "SELECT 
					numdoc AS boleto, date_format(vencimento,'%d/%m/%Y') AS venc, valor, 
					date_format(datapg,'%d/%m/%Y') AS dtpagamento, valorpg, origem_pgto, 
					vencimento, isento_juros, referencia
				FROM    titulos 
				WHERE codloja = '$codloja' AND referencia = 'MULTA' AND valorpg is null";
	$res = mysql_query($command,$con);
	$param1 = mysql_result($res,0,'venc');
	$param2 = mysql_result($res,0,'valor');
	$texto_boleto_multa = '';
	if ( mysql_num_rows ($res) > 0 )
		$texto_boleto_multa = "Multa Contratual Pendente : Venc: $param1 - R$ $param2";

	$soma = FloatFormat($soma,2);
	$soma = number_format($soma, 2, ',', '.');
  	 echo "
		<tr height=\"20\" class=\"subtitulodireita\">
			<td colspan='3'><font color=red><b>$texto_boleto_multa</b></font></td>
			<td colspan=\"2\">Total de Clientes: $a</td>
			<td colspan=\"8\">";
	if ($situacao == "and a.datapg is null") echo "Soma das Faturas n�o quitadas: R$ $soma";
	else if ($situacao == "and a.datapg is not null ") echo "Soma das Faturas quitadas: R$ $soma2";
	else echo "Soma das Faturas n�o quitadas: R$ $soma<br>
				Soma das Faturas quitadas: R$ $soma2";
	echo "</table>";
}
?>
<center>
</center>
<form method="post" action="painel.php?pagina1=Franquias/b_baixafatura.php">
<div align="center"><input type="submit" value="       Voltar       " /></div>
</form>

<?php if($_REQUEST['ok'] == 1) {?>
	<script>alert("Titulo atualizado com sucesso!");</script>
<?php } ?>
