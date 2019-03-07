<?

$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
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

$assitente = $_REQUEST['assitente'];
$dt_agenda = $_REQUEST['data_agendamento'];
$id_franquia = $_REQUEST['id_franquia'];
if ( $id_franquia == 163 or $id_franquia == 46 or $id_franquia == 59 )
	$id_franquia = 1;
	                                 
$dt_agenda = substr($dt_agenda,6,4).'-'.substr($dt_agenda,3,2).'-'.substr($dt_agenda,0,2);

$data = date("Y-m-d");
 
$sql   = "	SELECT * FROM cs2.controle_comercial_visitas 
			WHERE assitente_comercial = '$assitente' AND data_agendamento = '$data'
			AND id_franquia = '$id_franquia'";
$res   = mysql_query($sql,$con);

if ( mysql_num_rows($res) > 0 ){
	while ( $reg = mysql_fetch_array($res) ){
		$data_cadastro = $reg['data_cadastro'];
		$hora_agendamento = substr($reg['hora_agendamento'],0,5);
		$empresa = $reg['empresa'];
		$fone = $reg['fone1'].' - '.$reg['fone2'];
	echo "$hora_agendamento -> $empresa / $fone<BR>";
	}
	
	
}else{
	echo " NENHUM COMPROMISSO AGENDADO";
}




?>
