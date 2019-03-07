<?php

$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
if (!$con) {
    echo 'Erro na conexao com o Servidor<br>';
    echo mysql_error();
    exit;
} else {
    $database = mysql_select_db("cs2", $con);
    if (!$database) {
        echo 'Erro na conexão com o Banco de dados<br>';
        echo mysql_error();
    }
}

$id_consultor = $_REQUEST['id_consultor'];
$dt_agenda = $_REQUEST['data_agendamento'];
$id_franquia = $_REQUEST['id_franquia'];
if ($id_franquia == 163 or $id_franquia == 46 or $id_franquia == 59)
    $id_franquia = 1;

$dt_agenda = substr($dt_agenda, 6, 4) . '-' . substr($dt_agenda, 3, 2) . '-' . substr($dt_agenda, 0, 2);

$sql = "SELECT cvh.*, cv.empresa as empresa, cv.responsavel as responsavel, cs.nome as status FROM cs2.controle_comercial_visitas_historico as cvh 
        INNER JOIN cs2.controle_comercial_visitas as cv ON cv.id = cvh.id_visita
        INNER JOIN cs2.controle_comercial_visitas_status as cs ON cs.id = cvh.status
	WHERE cvh.id_consultor = '$id_consultor' AND cvh.data_visita = '$dt_agenda'
	ORDER BY cvh.hora
	";
$res = mysql_query($sql, $con);

$saida = "<table border=1>";
if (mysql_num_rows($res) > 0) {
    while ($reg = mysql_fetch_array($res)) {
        $i = $reg['id'];

        $id = "<a href=#
			onClick=\"window.open('../php/painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$i&b_rel_franquia=$id_franquia', '_blank')\"
			>$i</a>
			";



        $saida .= "<tr>
			<td><b>" . $reg['hora'] . "</b></td>
			<td>" . $id . "</td>
			<td>" . $reg['id'] . "</td>
			<td>" . $reg['empresa'] . "</td>
			<td>" . $reg['fone1'] . "</td>
			<td>" . $reg['responsavel'] . "</td>
			<td>" . $reg['status'] . "</td>
			</tr>";
    }
} else {
    echo " NENHUM COMPROMISSO AGENDADO";
}

$saida .= "</table>";
echo $saida;
?>
