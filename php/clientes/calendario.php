<?php

ob_start();
session_start();

/* <td style="cursor:pointer;" onclick="setValCalendar(\'' . $ano . '-' . $mes . '-' . $a . '\',\'' . $a . '/' . $mes . '/' . $ano . '\')">' . $a . '</td>';
  if (date('w', strtotime($ano . '-' . $mes . '-' . $i)) == 6) {
  echo '</tr><tr>
 */
if (!$_SESSION['BOLETOPDF_CALENDAR']['DIA']) {
    $_SESSION['BOLETOPDF_CALENDAR']['DIA'] = date('d');
}

if (!$_SESSION['BOLETOPDF_CALENDAR']['MES']) {
    $_SESSION['BOLETOPDF_CALENDAR']['MES'] = date('m');
}

if (!$_SESSION['BOLETOPDF_CALENDAR']['ANO']) {
    $_SESSION['BOLETOPDF_CALENDAR']['ANO'] = date('Y');
}

$dia = $_SESSION['BOLETOPDF_CALENDAR']['DIA'];
$mes = $_SESSION['BOLETOPDF_CALENDAR']['MES'];
$ano = $_SESSION['BOLETOPDF_CALENDAR']['ANO'];

if (isset($_REQUEST['right'])) {
    $newdata = date('Y-m-d', strtotime('+1 month ' . $ano . '-' . $mes . '-' . $dia));
    $newdata = explode('-', $newdata);
    $dia = date('d');
    $mes = $newdata[1];
    $ano = $newdata[0];
    $_SESSION['BOLETOPDF_CALENDAR']['MES'] = $newdata[1];
    $_SESSION['BOLETOPDF_CALENDAR']['ANO'] = $newdata[0];
}
if (isset($_REQUEST['left'])) {
    $newdata = date('Y-m-d', strtotime('-1 month ' . $ano . '-' . $mes . '-' . $dia));
    $newdata = explode('-', $newdata);
    $dia = date('d');
    $mes = $newdata[1];
    $ano = $newdata[0];
    $_SESSION['BOLETOPDF_CALENDAR']['MES'] = $newdata[1];
    $_SESSION['BOLETOPDF_CALENDAR']['ANO'] = $newdata[0];
}


$meses = array(
    1 => 'Janeiro',
    'Fevereiro',
    'Março',
    'Abril',
    'Maio',
    'Junho',
    'Julho',
    'Agosto',
    'Setembro',
    'Outubro',
    'Novembro',
    'Dezembro'
);

$ultimoDia = date('t', strtotime($ano . '-' . $mes . '-' . $dia));
$initSemana = date('w', strtotime($ano . '-' . $mes . '-01'));

echo '<table class="table" width="100%" celspan="2">
		<thead>
			<tr>
				<th style="cursor:pointer;" onclick="actionCalendar(\'left\')"><</th>
				<th colspan="5" >' . $meses[(($mes < 10) ? str_replace('0', '', $mes) : $mes)] . ' / ' . $ano . '</th>
				<th style="cursor:pointer;" onclick="actionCalendar(\'right\')">></th>
			</tr>
			<tr>
				<th>D</th>
				<th>S</th><th>T</th><th>Q</th><th>Q</th><th>S</th><th>S</th></tr></thead><tbody><tr>';
for ($i = 0; $i < $initSemana; $i++) {
    echo '<td></td>';
}
for ($i = 1; $i <= $ultimoDia; $i++) {
    $a = $i;
    if ($i < 10) {
        $a = '0' . $a;
    }
    echo '<td style="cursor:pointer;" onclick="setValCalendar(\'' . $ano . '-' . $mes . '-' . $a . '\',\'' . $a . '/' . $mes . '/' . $ano . '\')">' . $a . '</td>';
    if (date('w', strtotime($ano . '-' . $mes . '-' . $i)) == 6) {
        echo '</tr><tr>';
    }
}
echo '</tr></tbody></table>';
