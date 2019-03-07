<?
$bloquear = $_REQUEST['bloquear'];

echo "xxxx";
exit;

echo "
<table border='0' width='65%' align='center' cellpadding='0' cellspacing='1' style='border: 1px solid #D1D7DC; background-color:#FFFFFF'>
	<tr>
		<td align='center' height='40'>RELATORIO DE BLOQUEIO DE FRANQUIAS<br><hr></td>
	</tr>
	";
global $con;

//CONECTA AO MYSQL 
$con = @mysql_connect("10.2.2.3", "csinform", "inform4416#scf");
if (!$con) {
	echo 'Erro na conexao com o Servidor<br>';
	echo mysql_error();
	exit;
} else {
	$database = mysql_select_db("cs2",$con);
	if (!$database) {
		echo 'Erro na conexão com o Banco de dados<br>';
		echo mysql_error();
	}
}


function grava_vendas($id_franquia,$data_cadastro,$hora_cadastro){
	global $con;
	$sql = "select count(*) qtd from cs2.temp_vendas where id_franquia = $id_franquia";
	$qr_sql = mysql_query($sql,$con) or die ("ERRO: $sql");
	$qtd = mysql_result($qr_sql,0,'qtd');
	if ( empty($qtd) ) $qtd = '0';
	if ( $qtd == 0 ) $sql_insert = "INSERT INTO cs2.temp_vendas VALUES( 1 , '$data_cadastro $hora_cadastro' , $id_franquia )";
	else $sql_insert = "UPDATE cs2.temp_vendas SET qtd = qtd + 1 WHERE id_franquia = $id_franquia";
	$qr_sqlinsert = mysql_query($sql_insert,$con) or die ("ERRO: $sql_insert");
}


$primeiro  = '2013-02-08';
$ultimo    = '2013-02-15';

// Limpa a tabela temporária
$command = "Delete from cs2.temp_vendas";
$res = mysql_query($command,$con);

//faz o ranking de venda de acordo ao numero de vendas do periodo

$command = "SELECT id_franquia,dt_cad, hora_cadastro 
			FROM cadastro 
			WHERE dt_cad between '$primeiro' AND '$ultimo'
			AND MID(upper(razaosoc),1,9 ) <> 'FRANQUEAD' 
			ORDER BY id_franquia,dt_cad,hora_cadastro";			
$res = mysql_query($command,$con);

$reg = 0;
while($registro = mysql_fetch_array($res)){
	$id_franquia = $registro['id_franquia'];
	$data_cadastro = $registro['dt_cad'];
	$hora_cadastro = $registro['hora_cadastro'];
	$reg++;
			
	# primeiro dia, verificando se o contrato foi cadastrado após as 18:00:00
	if ( $data_cadastro == $primeiro ){
		if ( strtotime($hora_cadastro) > strtotime("18:00:00") )
			grava_vendas($id_franquia,$data_cadastro,$hora_cadastro);
			//echo "FRANQUEADO FECHOU NO PRIMEIRO DIA APÓS AS 18:00:00 <br>";
	}else if ( $data_cadastro == $ultimo ){
		
		# ultimo dia, verificando se o contrato foi cadastrado até as 18:00:00
		if ( strtotime($hora_cadastro) <= strtotime("18:00:00") )
			//echo "FRANQUEADO FECHOU NO ULTIMO DIA ATÉ AS 18:00:00 <br>";
			grava_vendas($id_franquia,$data_cadastro,$hora_cadastro);
	}else{
			grava_vendas($id_franquia,$data_cadastro,$hora_cadastro);		
	}
}

$comando = "SELECT a.id, b.qtd, a.fantasia 
			FROM franquia a
			LEFT outer join temp_vendas b ON a.id = b.id_franquia
			WHERE mid(a.fantasia,1,8) = 'FRANQUIA'
			ORDER BY a.fantasia";
$res = mysql_query($comando,$con);
if ( mysql_num_rows($res) > 0 ){
	while ( $reg = mysql_fetch_array($res) ){
		$id_franquia = $reg['id'];
		$fantasia = $reg['fantasia'];
		$qtd = $reg['qtd'];
		if ( empty($qtd) ) $qtd = 0;

		$qtd_ass = 0;
		$qtd_ven = 0;
		
		# Verificando se o franqueado tem até 2 vendedores cadastrados.
		
		$sql_vend = "SELECT count(*) as qtd FROM cs2.consultores_assistente
					 WHERE id_franquia = '$id_franquia' AND situacao = 'A' AND tipo_cliente = 0";
		$qry_ven = mysql_query($sql_vend,$con);
		$qtd_ven = mysql_result( $qry_ven , 0 , 'qtd' );
		
		# Verificando se o franqueado tem até 2 vendedores cadastrados.
		
		$sql_ass  = "SELECT count(*) as qtd FROM cs2.consultores_assistente
					 WHERE id_franquia = '$id_franquia' AND situacao = 'A' AND tipo_cliente = 1";
		$qry_ass = mysql_query($sql_ass,$con);
		$qtd_ass = mysql_result( $qry_ass , 0 , 'qtd' );
		
		$bloqueio = 'N';

		if ( $id_franquia <> 5 && $id_franquia <> 1205 && $id_franquia <> 1204 && $id_franquia <> 247 && $id_franquia <> 4 ){
			echo "<tr><td>";
			echo "Franquia : $id_franquia <b>$fantasia</b><br>";
			echo "Qtd de ASSISTENTE COMERCIAL [ <b>$qtd_ass</b> ]<br>";
			echo "Qtd de CONSULTORES COMERCIAL [ <b>$qtd_ven</b> ]<br>";
			echo "Qtd de CONTRATOS FECHADOS NA SEMANA [ <b>$qtd</b> ]<br>";
				
			if ( $qtd_ven < 2 ){
				echo "<font color='#FF0000'>BLOQUEADO POR LIMITE MINIMO DE CONSULTORES</font><br>";
				$bloqueio = 'S';
			}
			if ( $qtd_ass < 1 ){
				echo "<font color='#FF0000'>BLOQUEADO POR LIMITE MINIMO DE  ASSISTENTE COMERCIAL</font><br>";
				$bloqueio = 'S';
			}
			
			if ( ( $bloqueio == 'S'  && $qtd >= 5)  ){
					echo "<font color='#0000FF'>Escapou... FECHOU mais de 5 CONTRATOS</font><br>";
					$bloqueio = 'N';
			}else{
				echo "<font color='#FF0000'>BLOQUEADO : FECHOU MENOS DE 5 CONTRATOS</font><br>";
				$bloqueio = 'S';
			}
			echo "<hr>";
			echo "</td></tr>";

		}
		if ( $bloqueio == 'S'){
			$sql_update = "UPDATE cs2.franquia SET sitfrq = 1 WHERE id = $id_franquia";
		}else{
			$sql_update = "UPDATE cs2.franquia SET sitfrq = 0 WHERE id = $id_franquia";
		}
		
		if ( $bloquear == 'SIM' )
			$res_update = mysql_query($sql_update,$con);

	}
}

echo "</table>";

?>