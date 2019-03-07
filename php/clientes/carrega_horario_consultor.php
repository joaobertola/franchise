<?php

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

$id_consultor = $_REQUEST['id_consultor'];
$dt_agenda = $_REQUEST['data_agendamento'];
$id_franquia = $_REQUEST['id_franquia'];
if ( $id_franquia == 163 or $id_franquia == 46 or $id_franquia == 59 )
	$id_franquia = 1;

	$dt_agenda = substr($dt_agenda,6,4).'-'.substr($dt_agenda,3,2).'-'.substr($dt_agenda,0,2);

	$sql = "SELECT * FROM cs2.controle_comercial_visitas
	WHERE id_consultor = '$id_consultor' AND data_agendamento = '$dt_agenda'
	ORDER BY hora_agendamento
	";
	$res   = mysql_query($sql,$con);

	$saida = "<table border=1>";
	if ( mysql_num_rows($res) > 0 ){
		while ( $reg = mysql_fetch_array($res) ){
			$i = $reg['id'];

			$id = "<a href=#
			onClick=\"window.open('../php/painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$i&b_rel_franquia=$id_franquia', '_blank')\"
			>$i</a>
			";
			$data_cadastro = $reg['data_cadastro'];
			$hora_agendamento = $reg['hora_agendamento'];
			$empresa = $reg['empresa'];
			$responsavel = $reg['responsavel'];
			$responsavel = substr($responsavel,0,15);
			$fone1 = $reg['fone1'];
			$fone2 = $reg['fone2'];
			$saida.= "<tr>
			<td><b>$hora_agendamento</b></td>
			<td>$id</td>
			<td>$empresa</td>
			<td>$fone1</td>
			<td>$fone2</td>
			<td>$responsavel</td>
			</tr>";
		}


	}else{
		echo " NENHUM COMPROMISSO AGENDADO";
	}

	$saida .= "</table>";
	echo $saida;


	?>
