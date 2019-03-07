<?

global $con;

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


$bloquear = $_REQUEST['bloquear'];
$periodo = '';
$week = date('W');

$wanted_week = $_GET['wanted_week'];

if (empty($wanted_week)) $sel = $week;
else $sel = $wanted_week;

$ano_esc = $_REQUEST['ano_esc'];
if(empty($ano_esc)){
	$ano_esc = date('Y');
}else{
	$ano_esc = $_REQUEST['ano_esc'];
}

$abre_form = $_REQUEST['abre_form'];
if(empty($abre_form)){
	$abre_form = "1";
}else{
	$abre_form = $_REQUEST['abre_form'];
}

		if (empty($wanted_week)) $wanted_week = $week;
	
		$week_diff = $wanted_week - $week;

		$ts_week = strtotime("$week_diff week");

		$day_of_week = date('w', $ts_week);
		
		//VERIFICA SE O ANO ÉBISSEXTO
		if ((($ano_esc % 4) == 0 and ($ano_esc % 100)!=0) or ($ano_esc % 400)==0){
			$bissexto = TRUE;
		}else{
			$bissexto = FALSE;
		}

		if(($_REQUEST['wanted_week'] == 1) and ($bissexto == TRUE) ){
			$fim = 5;
			$inicio = -2;
		}elseif($bissexto == TRUE){
			$fim = 5;
			$inicio = -2;
		}else{
			$fim = 5;
			$inicio = -2;
		}
			for ($i = $inicio; $i <= $fim; $i++) {
			// TimeStamp contendo os dias da semana de domingo a sabado
			$ts = strtotime( ( $i - $day_of_week )." days", $ts_week );
	
			//if ($i == -2) {
			if ($i == $inicio) {
			

				$primeiro = date($ano_esc."-m-d",$ts);
				if($sel == "01"){
					$_ano_esc = $ano_esc - 1;
					$periodo .= date("d/m/".$_ano_esc, $ts) . " ap&oacute;s 18:00:00 <br>";
				}else{
					$periodo .= date("d/m/".$ano_esc, $ts) . " ap&oacute;s 18:00:00 <br>";
				}	
			//}elseif ($i == 5) {			
			  }elseif ($i == $fim) {			
				$ultimo = date($ano_esc."-m-d",$ts);
				$periodo .= date("d/m/".$ano_esc, $ts) . " at&eacute; as 18:00:00 <br>";
			}							
		}
		
		if($sel == "01"){
			$tmp = substr($primeiro, 0,4);
			$tmp = $tmp - 1;
			$dt_tmp = substr($primeiro, 4,6);
			$primeiro = $tmp;
			$primeiro .= $dt_tmp;
		}
		
$datai     = substr($primeiro,8,2).'/'.substr($primeiro,5,2).'/'.substr($primeiro,0,4);
$dataf     = substr($ultimo,8,2).'/'.substr($ultimo,5,2).'/'.substr($ultimo,0,4);



$html = "
<table border='0' width='65%' align='center' cellpadding='0' cellspacing='1' style='border: 1px solid #D1D7DC; background-color:#FFFFFF'>
	<tr>
		<td align='center' height='40'>RELATORIO DE BLOQUEIO DE FRANQUIAS<br></td>
	</tr>
	<tr>
		<td align='center' height='40'>Per&iacute;odo: <br>$periodo<br><hr></td>
	</tr>
";

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

function grava_agendamentos($id_franquia,$data_cadastro,$hora_cadastro){
	global $con;
	$sql = "select count(*) qtd from cs2.temp_agendamento where id_franquia = $id_franquia";
	$qr_sql = mysql_query($sql,$con) or die ("ERRO: $sql");
	$qtd = mysql_result($qr_sql,0,'qtd');
	if ( empty($qtd) ) $qtd = '0';
	if ( $qtd == 0 ) $sql_insert = "INSERT INTO cs2.temp_agendamento VALUES( 1 , '$data_cadastro $hora_cadastro' , $id_franquia )";
	else $sql_insert = "UPDATE cs2.temp_agendamento SET qtd = qtd + 1 WHERE id_franquia = $id_franquia";
	$qr_sqlinsert = mysql_query($sql_insert,$con) or die ("ERRO: $sql_insert");
}


// Limpa a tabela temporária
$command = "Delete from cs2.temp_vendas";
$res = mysql_query($command,$con) or die($command);

$command = "Delete from cs2.temp_agendamento";
$res = mysql_query($command,$con) or die($command);

//faz o ranking de venda de acordo ao numero de vendas do periodo

$command = "SELECT id_franquia,dt_cad, hora_cadastro
			FROM cadastro
			WHERE dt_cad between '$primeiro' AND '$ultimo'
			AND MID(upper(razaosoc),1,9 ) <> 'FRANQUEAD'
			ORDER BY id_franquia,dt_cad,hora_cadastro";
$res = mysql_query($command) or die ($command);

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
	}else if ( $data_cadastro == $ultimo ){
		# ultimo dia, verificando se o contrato foi cadastrado até as 18:00:00
		if ( strtotime($hora_cadastro) <= strtotime("18:00:00") )
			grava_vendas($id_franquia,$data_cadastro,$hora_cadastro);
	}else{
			grava_vendas($id_franquia,$data_cadastro,$hora_cadastro);
	}
}

$command = "SELECT id_franquia,data_cadastro, hora_cadastro 
			FROM controle_comercial_visitas";
$res = mysql_query($command) or die ($command);
while($registro = mysql_fetch_array($res)){
	$id_franquia = $registro['id_franquia'];
	$data_cadastro = $registro['data_cadastro'];
	$hora_cadastro = $registro['hora_cadastro'];
	# primeiro dia, verificando se o contrato foi cadastrado após as 18:00:00
	if ( $data_cadastro == $primeiro ){
		if ( strtotime($hora_cadastro) > strtotime("18:00:00") )
			grava_agendamentos($id_franquia,$data_cadastro,$hora_cadastro);
	}else if ( $data_cadastro == $ultimo ){
		if ( strtotime($hora_cadastro) <= strtotime("18:00:00") )
			grava_agendamentos($id_franquia,$data_cadastro,$hora_cadastro);
	}else{
			grava_agendamentos($id_franquia,$data_cadastro,$hora_cadastro);
	}
}

$comando = "SELECT a.id, b.qtd, a.fantasia, classificacao 
			FROM franquia a
			LEFT outer join temp_vendas b ON a.id = b.id_franquia
			WHERE mid(a.fantasia,1,8) = 'FRANQUIA' or  mid(a.fantasia,1,5) = 'MICRO'
			ORDER BY a.fantasia";
$res = mysql_query($comando);
if ( mysql_num_rows($res) > 0 ){
	while ( $reg = mysql_fetch_array($res) ){
		$id_franquia = $reg['id'];
		$fantasia = $reg['fantasia'];
		$classificacao = $reg['classificacao'];
		
		if ( $classificacao == 'X' ) $limite_escapou = 5;
		else $limite_escapou = 7;
		
		
		$qtd = $reg['qtd'];
		if ( empty($qtd) ) $qtd = 0;

		$qtd_ass = 0;
		$qtd_ven = 0;
		
		# Verificando se o franqueado tem até 2 vendedores cadastrados.
		
		$sql_vend = "SELECT count(*) as qtd FROM cs2.consultores_assistente
					 WHERE id_franquia = '$id_franquia' AND situacao = 'A' AND tipo_cliente = 0";
		$qry_ven = mysql_query($sql_vend);
		$qtd_ven = mysql_result( $qry_ven , 0 , 'qtd' );
		
		# Verificando se o franqueado tem até 2 vendedores cadastrados.
		
		$sql_ass  = "SELECT count(*) as qtd FROM cs2.consultores_assistente
					 WHERE id_franquia = '$id_franquia' AND situacao = 'A' AND tipo_cliente = 1";
		$qry_ass = mysql_query($sql_ass);
		$qtd_ass = mysql_result( $qry_ass , 0 , 'qtd' );

		# Verificando a qunatidade de agendamentos SEMANAIS.
		$sql_age  = "SELECT qtd FROM cs2.temp_agendamento
					 WHERE id_franquia = '$id_franquia'";
		$qry_age = mysql_query($sql_age);
		$qtd_age = mysql_result( $qry_age , 0 , 'qtd' );
		if ( empty($qtd_age) )
			$qtd_age = 0;
		
		$bloqueio = 'N';

		if ( $id_franquia <> 5 && $id_franquia <> 1205 && $id_franquia <> 1204 && $id_franquia <> 247 && $id_franquia <> 4 ){
			$html .= "<tr><td>";
			$html .= "Franquia : $id_franquia <b>$fantasia</b><br>";
			$html .= "Qtd de ASSISTENTE COMERCIAL [ <b>$qtd_ass</b> ]<br>";
			$html .= "Qtd de CONSULTORES COMERCIAL [ <b>$qtd_ven</b> ]<br>";
			$html .= "Qtd de AGENDAMENTOS SEMANAL [ <b>$qtd_age</b> ]<br>";
			$html .= "Qtd de CONTRATOS FECHADOS NA SEMANA [ <b>$qtd</b> ]<br>";
			
			if ( $qtd_ven < 2 ){
				$html .= "<font color='#FF0000'>BLOQUEADO POR LIMITE MINIMO DE CONSULTORES</font><br>";
				$bloqueio = 'S';
			}
			if ( $qtd_ass < 2 ){
				$html .= "<font color='#FF0000'>BLOQUEADO POR LIMITE MINIMO DE  ASSISTENTE COMERCIAL</font><br>";
				$bloqueio = 'S';
			}
			if ( $qtd_age < 20 ){
				$html .= "<font color='#FF0000'>BLOQUEADO POR LIMITE MINIMO DE AGENDAMENTO</font><br>";
				$bloqueio = 'S';
			}
			
			if ( ( $bloqueio == 'S'  && $qtd >= $limite_escapou)  ){
					$html .= "<font color='#0000FF'>Escapou... FECHOU mais de $limite_escapou CONTRATOS</font><br>";
					$bloqueio = 'N';
			}elseif ( ( $bloqueio == 'N'  && $qtd >= $limite_escapou )  ){
				$html .= "<font color='#FF0000'>PARABENS, SUA FRANQUIA CUMPRIU A META.</font><br>";
				$bloqueio = 'N';
			}else{
				$html .= "<font color='#FF0000'>BLOQUEADO : FECHOU MENOS DE $limite_escapou CONTRATOS</font><br>";
				$bloqueio = 'S';

			}
			$html .= "<hr>";
			$html .= "</td></tr>";

		}
		if ( $bloqueio == 'S'){
			$sql_update = "UPDATE cs2.franquia SET sitfrq = 1 WHERE id = $id_franquia\n";
		}else{
			$sql_update = "UPDATE cs2.franquia SET sitfrq = 0 WHERE id = $id_franquia\n";
		}
		$res_update = mysql_query($sql_update);
	}
}
$html .= "</table>";

$data_hora = date("d/m/Y h:m:s");

echo "Procedimento: BLOQUEIO DE FRANQUIAS - Finalizado as $data_hora Periodo: $datai a $dataf";

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
	$mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - Web Control Empresas"); //E-MAIL DO DESINATÁRIO, NOME DO DESINATÁRIO
	$mail->AddCC("luciano@webcontrolempresas.com.br","Luciano Mancini");
	//$mail->AddCC("teixeira@webcontrolempresas.com.br","Ananias Teixeira");
	$mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
	$mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
	$mail->Subject = "Bloqueio Automatico de Franquias"; //ASSUNTO DA MENSAGEM
	$mail->Body = $html; //MENSAGEM NO FORMATO HTML
	$mail->Send();
} catch (phpmailerException $e) {
	echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
	echo $e->getMessage(); //Boring error messages from anything else!
}



?>
