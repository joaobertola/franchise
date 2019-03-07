<?php

$id = $_SESSION['id'];

if (($id == "59") || ($id == "46") || ($id == "163"))
	$tipo = $tipo;
else
	$bloqueio_geral = " disabled='disabled' ";

?>

<script type="text/javascript" src="../../../inform/js/prototype.js"></script>
<script >
function combo(valor) {
	self.location = "<?php $_SERVER['PHP_SELF']; ?>painel.php?pagina1=clientes/a_controle_visitas0.php&wantedweek="+valor;
}

function teste(compo_alterar,valor,linha,dia_semana,numero_semana){
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","clientes/grava_oficina.php?compo_alterar="+compo_alterar+"&numero_semana="+numero_semana+"&dia_semana="+dia_semana+"&linha="+linha+"&valor="+valor,true);
	xmlhttp.send();

}


</script>
<thead>
<tr>
	<th colspan="3"><h3 class="col100 text-center">OFICINA DE TREINAMENTO VIRTUAL</h3><h4 class="col100 text-center">Diretor Comercial: Ananias Teixeira</h4></th>
</tr>
</thead>
<tbody>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td colspan="3" style="text-align:center; font-size:14px">
    	Ligue agora e agende o seu hor&aacute;rio com o Departamento de Franquias
	</td>
</tr>
<tr>
	<td colspan="3" class="text-center">
    	<br>Temas:<br><br>OFICINA DE LOCALIZA MAX<br>
OFICINA DE CREDI&Aacute;RIO / BOLETO <br>
OFICINA DE PESQUISA DE CR&Eacute;DITO<br>
OFICINA DE RECUPERE SYSTEM<br>
OFICINA DE WEB-CONTROL<br>
OFICINA DE LOCALIZA MAX NOVOS CLIENTES<br>
OFICINA DE VIRTUAL FLEX<br>
OFICINA DE CONT&Aacute;BIL SOLUTION<br>
OFICINA DE OBJE&Ccedil;&Otilde;ES DE CLIENTES<br>
OFICINA DE TAXA DE IMPLANTA&Ccedil;&Atilde;O<br>
OFICINA DE ABORDAGEM A CLIENTES<br>
OFICINA DE FECHAMENTO DA VENDA<br>
OFICINA COM O FRANQUEADO<br><br>
	</td>
</tr>

<?php

function diasemana($data) {
	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);

	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

	switch($diasemana) {
		case"0": $diasemana = "SEGUNDA"; break;
		case"1": $diasemana = "TER&Ccedil;A";   break;
		case"2": $diasemana = "QUARTA";  break;
		case"3": $diasemana = "QUINTA";  break;
		case"4": $diasemana = "SEXTA";   break;
	}

	return "$diasemana";
}

$week = date('W');

$wantedweek = $_GET['wantedweek'];

if (empty($wantedweek)) $sel = $week;
else $sel = $wantedweek;

$ano_esc = $_REQUEST['ano_esc'];
if(empty($ano_esc)){
	$ano_esc = date('Y');
}else{
	$ano_esc = $_REQUEST['ano_esc'];
}

echo"
	<tr>
		<td>Semana</td>
		<td colspan='2'>
			<select name='wantedweek' class='boxnormal' onchange='combo(this.value)'>";
				for ($i = 1; $i <= 52; $i++) {
				echo "<option value=\"$i\"";
				if ($sel == $i) echo " selected";
				echo ">$i&ordm; Semana </option>";
			}
			echo "</select>
		</td>
	</tr>";

echo "<tr>";

if (empty($wantedweek)) $wantedweek = $week;
$week_diff = $wantedweek - $week;
$ts_week = strtotime("$week_diff week");
$day_of_week = date('w', $ts_week);
$fim = 5;
$inicio = 1;


$sql = "SELECT count(*) as qtd FROM cs2.oficina_treinamento
		WHERE numero_semana = '$wantedweek'";
$qry = mysql_query($sql,$con) or die ("$sql");
$qtd = mysql_result($qry,0,'qtd');
if ( $qtd == '' ) $qtd = 0;

if ( $qtd == 0 ){
	$sql = "INSERT INTO cs2.oficina_treinamento(numero_semana)
			VALUES ('$wantedweek')";
	$qry = mysql_query($sql,$con) or die ("$sql");
}

$sql = "SELECT 
			hora_seg_1,  tema_seg_1,  idfranquia_seg_1,
			hora_seg_2,  tema_seg_2,  idfranquia_seg_2,
			hora_seg_3,  tema_seg_3,  idfranquia_seg_3,
			hora_seg_4,  tema_seg_4,  idfranquia_seg_4,
			
			hora_ter_1,  tema_ter_1,  idfranquia_ter_1,
			hora_ter_2,  tema_ter_2,  idfranquia_ter_2,
			hora_ter_3,  tema_ter_3,  idfranquia_ter_3,
			hora_ter_4,  tema_ter_4,  idfranquia_ter_4,
			
			hora_qua_1,  tema_qua_1,  idfranquia_qua_1,
			hora_qua_2,  tema_qua_2,  idfranquia_qua_2,
			hora_qua_3,  tema_qua_3,  idfranquia_qua_3,
			hora_qua_4,  tema_qua_4,  idfranquia_qua_4,
			
			hora_qui_1,  tema_qui_1,  idfranquia_qui_1,
			hora_qui_2,  tema_qui_2,  idfranquia_qui_2,
			hora_qui_3,  tema_qui_3,  idfranquia_qui_3,
			hora_qui_4,  tema_qui_4,  idfranquia_qui_4,

			hora_sex_1,  tema_sex_1,  idfranquia_sex_1,
			hora_sex_2,  tema_sex_2,  idfranquia_sex_2,
			hora_sex_3,  tema_sex_3,  idfranquia_sex_3,
			hora_sex_4,  tema_sex_4,  idfranquia_sex_4

		FROM cs2.oficina_treinamento
		WHERE numero_semana = '$wantedweek'";
$qry = mysql_query($sql,$con) or die ("$sql");
if ( mysql_num_rows($qry) > 0 ){
	while( $reg = mysql_fetch_array($qry) ){
		$hora_seg_1 = $reg['hora_seg_1']; $tema_seg_1 = $reg['tema_seg_1']; $idfranquia_seg_1 = $reg['idfranquia_seg_1'];
		$hora_seg_2 = $reg['hora_seg_2']; $tema_seg_2 = $reg['tema_seg_2']; $idfranquia_seg_2 = $reg['idfranquia_seg_2'];
		$hora_seg_3 = $reg['hora_seg_3']; $tema_seg_3 = $reg['tema_seg_3']; $idfranquia_seg_3 = $reg['idfranquia_seg_3'];
		$hora_seg_4 = $reg['hora_seg_4']; $tema_seg_4 = $reg['tema_seg_4']; $idfranquia_seg_4 = $reg['idfranquia_seg_4'];
		
		$hora_ter_1 = $reg['hora_ter_1']; $tema_ter_1 = $reg['tema_ter_1']; $idfranquia_ter_1 = $reg['idfranquia_ter_1'];
		$hora_ter_2 = $reg['hora_ter_2']; $tema_ter_2 = $reg['tema_ter_2']; $idfranquia_ter_2 = $reg['idfranquia_ter_2'];
		$hora_ter_3 = $reg['hora_ter_3']; $tema_ter_3 = $reg['tema_ter_3']; $idfranquia_ter_3 = $reg['idfranquia_ter_3'];
		$hora_ter_4 = $reg['hora_ter_4']; $tema_ter_4 = $reg['tema_ter_4']; $idfranquia_ter_4 = $reg['idfranquia_ter_4'];
		
		$hora_qua_1 = $reg['hora_qua_1']; $tema_qua_1 = $reg['tema_qua_1']; $idfranquia_qua_1 = $reg['idfranquia_qua_1'];
		$hora_qua_2 = $reg['hora_qua_2']; $tema_qua_2 = $reg['tema_qua_2']; $idfranquia_qua_2 = $reg['idfranquia_qua_2'];
		$hora_qua_3 = $reg['hora_qua_3']; $tema_qua_3 = $reg['tema_qua_3']; $idfranquia_qua_3 = $reg['idfranquia_qua_3'];
		$hora_qua_4 = $reg['hora_qua_4']; $tema_qua_4 = $reg['tema_qua_4']; $idfranquia_qua_4 = $reg['idfranquia_qua_4'];
		
		$hora_qui_1 = $reg['hora_qui_1']; $tema_qui_1 = $reg['tema_qui_1']; $idfranquia_qui_1 = $reg['idfranquia_qui_1'];
		$hora_qui_2 = $reg['hora_qui_2']; $tema_qui_2 = $reg['tema_qui_2']; $idfranquia_qui_2 = $reg['idfranquia_qui_2'];
		$hora_qui_3 = $reg['hora_qui_3']; $tema_qui_3 = $reg['tema_qui_3']; $idfranquia_qui_3 = $reg['idfranquia_qui_3'];
		$hora_qui_4 = $reg['hora_qui_4']; $tema_qui_4 = $reg['tema_qui_4']; $idfranquia_qui_4 = $reg['idfranquia_qui_4'];

		$hora_sex_1 = $reg['hora_sex_1']; $tema_sex_1 = $reg['tema_sex_1']; $idfranquia_sex_1 = $reg['idfranquia_sex_1'];
		$hora_sex_2 = $reg['hora_sex_2']; $tema_sex_2 = $reg['tema_sex_2']; $idfranquia_sex_2 = $reg['idfranquia_sex_2'];
		$hora_sex_3 = $reg['hora_sex_3']; $tema_sex_3 = $reg['tema_sex_3']; $idfranquia_sex_3 = $reg['idfranquia_sex_3'];
		$hora_sex_4 = $reg['hora_sex_4']; $tema_sex_4 = $reg['tema_sex_4']; $idfranquia_sex_4 = $reg['idfranquia_sex_4'];
	}
}

for ($i = $inicio; $i <= $fim; $i++) {
	
	// TimeStamp contendo os dias da semana de domingo a sabado
	$ts = strtotime( ( $i - $day_of_week )." days", $ts_week );
	$primeiro = date($ano_esc."-m-d",$ts);
	$_ano_esc = $ano_esc - 1;
	$dia_semana = diasemana(date($_ano_esc.'/m/d',$ts));
	$inisemana = substr($dia_semana,0,3);

	// $hora_1.. = primeiro horario do dia
	// $hora_2.. = segundo horario do dia
	// $hora_3.. = terceiro horario do dia
	// $hora_4.. = quarto horario do dia
	
	$hora_11 = '';	$hora_12 = '';	$hora_13 = '';	$hora_14 = '';	$hora_15 = '';
	$hora_16 = '';	$hora_17 = '';	$hora_18 = '';	$hora_19 = '';
	
	$hora_21 = '';	$hora_22 = '';	$hora_23 = '';	$hora_24 = '';	$hora_25 = '';
	$hora_26 = '';	$hora_27 = '';	$hora_28 = '';	$hora_29 = '';

	$hora_31 = '';	$hora_32 = '';	$hora_33 = '';	$hora_34 = '';	$hora_35 = '';
	$hora_36 = '';	$hora_37 = '';	$hora_38 = '';	$hora_39 = '';

	$hora_41 = '';	$hora_42 = '';	$hora_43 = '';	$hora_44 = '';	$hora_45 = '';
	$hora_46 = '';	$hora_47 = '';	$hora_48 = '';	$hora_49 = '';

	$bloqueio_dia = '';
	
	// Primeiro tema do dia
	$tema_100 = ''; $tema_101 = ''; $tema_102 = ''; $tema_103 = ''; $tema_104 = '';
	$tema_105 = ''; $tema_106 = ''; $tema_107 = ''; $tema_108 = ''; $tema_109 = '';
	$tema_110 = ''; $tema_111 = ''; $tema_112 = ''; $tema_113 = ''; $tema_114 = '';
	// Segundo tema do dia
	$tema_200 = ''; $tema_201 = ''; $tema_202 = ''; $tema_203 = ''; $tema_204 = '';
	$tema_205 = ''; $tema_206 = ''; $tema_207 = ''; $tema_208 = ''; $tema_209 = '';
	$tema_210 = ''; $tema_211 = ''; $tema_212 = ''; $tema_213 = ''; $tema_214 = '';
	// Terceiro tema do dia
	$tema_300 = ''; $tema_301 = ''; $tema_302 = ''; $tema_303 = ''; $tema_304 = '';
	$tema_305 = ''; $tema_306 = ''; $tema_307 = ''; $tema_308 = ''; $tema_309 = '';
	$tema_310 = ''; $tema_311 = ''; $tema_312 = ''; $tema_313 = ''; $tema_314 = '';
	// Quarto tema do dia
	$tema_400 = ''; $tema_401 = ''; $tema_402 = ''; $tema_403 = ''; $tema_404 = '';
	$tema_405 = ''; $tema_406 = ''; $tema_407 = ''; $tema_408 = ''; $tema_409 = '';
	$tema_410 = ''; $tema_411 = ''; $tema_412 = ''; $tema_413 = ''; $tema_414 = '';

	$_franqueado1 = '';
	$_franqueado2 = '';
	$_franqueado3 = '';
	$_franqueado4 = '';

	if ( $inisemana == 'SEG' ){
		switch($hora_seg_1) {
			case "00": $hora_10 = "selected='selected'"; break;
			case "01": $hora_11 = "selected='selected'"; $bloqueio_dia = " disabled='disabled' "; break;
			case "02": $hora_12 = "selected='selected'"; break;
			case "03": $hora_13 = "selected='selected'"; break;
			case "04": $hora_14 = "selected='selected'"; break;
			case "05": $hora_15 = "selected='selected'"; break;
			case "06": $hora_16 = "selected='selected'"; break;
			case "07": $hora_17 = "selected='selected'"; break;
			case "08": $hora_18 = "selected='selected'"; break;
			case "09": $hora_19 = "selected='selected'"; break;
		}
		switch($hora_seg_2) {
			case "01": $hora_21 = "selected='selected'"; break;
			case "02": $hora_22 = "selected='selected'"; break;
			case "03": $hora_23 = "selected='selected'"; break;
			case "04": $hora_24 = "selected='selected'"; break;
			case "05": $hora_25 = "selected='selected'"; break;
			case "06": $hora_26 = "selected='selected'"; break;
			case "07": $hora_27 = "selected='selected'"; break;
			case "08": $hora_28 = "selected='selected'"; break;
			case "09": $hora_29 = "selected='selected'"; break;
		}
		switch($hora_seg_3) {
			case "01": $hora_31 = "selected='selected'"; break;
			case "02": $hora_32 = "selected='selected'"; break;
			case "03": $hora_33 = "selected='selected'"; break;
			case "04": $hora_34 = "selected='selected'"; break;
			case "05": $hora_35 = "selected='selected'"; break;
			case "06": $hora_36 = "selected='selected'"; break;
			case "07": $hora_37 = "selected='selected'"; break;
			case "08": $hora_38 = "selected='selected'"; break;
			case "09": $hora_39 = "selected='selected'"; break;
		}
		switch($hora_seg_4) {
			case "01": $hora_41 = "selected='selected'"; break;
			case "02": $hora_42 = "selected='selected'"; break;
			case "03": $hora_43 = "selected='selected'"; break;
			case "04": $hora_44 = "selected='selected'"; break;
			case "05": $hora_45 = "selected='selected'"; break;
			case "06": $hora_46 = "selected='selected'"; break;
			case "07": $hora_47 = "selected='selected'"; break;
			case "08": $hora_48 = "selected='selected'"; break;
			case "09": $hora_49 = "selected='selected'"; break;
		}
		# TEMA
		switch($tema_seg_1) {
			case "OF0":  $tema_100 = "selected='selected'"; break;
			case "OF1":  $tema_101 = "selected='selected'"; break;
			case "OF2":  $tema_102 = "selected='selected'"; break;
			case "OF3":  $tema_103 = "selected='selected'"; break;
			case "OF4":  $tema_104 = "selected='selected'"; break;
			case "OF5":  $tema_105 = "selected='selected'"; break;
			case "OF6":  $tema_106 = "selected='selected'"; break;
			case "OF7":  $tema_107 = "selected='selected'"; break;
			case "OF8":  $tema_108 = "selected='selected'"; break;
			case "OF9":  $tema_109 = "selected='selected'"; break;
			case "OF10": $tema_110 = "selected='selected'"; break;
			case "OF11": $tema_111 = "selected='selected'"; break;
			case "OF12": $tema_112 = "selected='selected'"; break;
			case "OF13": $tema_113 = "selected='selected'"; break;
			case "OF14": $tema_114 = "selected='selected'"; break;
		}
		switch($tema_seg_2) {
			case "OF1":  $tema_201 = "selected='selected'"; break;
			case "OF2":  $tema_202 = "selected='selected'"; break;
			case "OF3":  $tema_203 = "selected='selected'"; break;
			case "OF4":  $tema_204 = "selected='selected'"; break;
			case "OF5":  $tema_205 = "selected='selected'"; break;
			case "OF6":  $tema_206 = "selected='selected'"; break;
			case "OF7":  $tema_207 = "selected='selected'"; break;
			case "OF8":  $tema_208 = "selected='selected'"; break;
			case "OF9":  $tema_209 = "selected='selected'"; break;
			case "OF10": $tema_210 = "selected='selected'"; break;
			case "OF11": $tema_211 = "selected='selected'"; break;
			case "OF12": $tema_212 = "selected='selected'"; break;
			case "OF13": $tema_213 = "selected='selected'"; break;
			case "OF14": $tema_214 = "selected='selected'"; break;
		}
		switch($tema_seg_3) {
			case "OF1":  $tema_301 = "selected='selected'"; break;
			case "OF2":  $tema_302 = "selected='selected'"; break;
			case "OF3":  $tema_303 = "selected='selected'"; break;
			case "OF4":  $tema_304 = "selected='selected'"; break;
			case "OF5":  $tema_305 = "selected='selected'"; break;
			case "OF6":  $tema_306 = "selected='selected'"; break;
			case "OF7":  $tema_307 = "selected='selected'"; break;
			case "OF8":  $tema_308 = "selected='selected'"; break;
			case "OF9":  $tema_309 = "selected='selected'"; break;
			case "OF10": $tema_310 = "selected='selected'"; break;
			case "OF11": $tema_311 = "selected='selected'"; break;
			case "OF12": $tema_312 = "selected='selected'"; break;
			case "OF13": $tema_313 = "selected='selected'"; break;
			case "OF14": $tema_314 = "selected='selected'"; break;
		}
		switch($tema_seg_4) {
			case "OF1":  $tema_401 = "selected='selected'"; break;
			case "OF2":  $tema_402 = "selected='selected'"; break;
			case "OF3":  $tema_403 = "selected='selected'"; break;
			case "OF4":  $tema_404 = "selected='selected'"; break;
			case "OF5":  $tema_405 = "selected='selected'"; break;
			case "OF6":  $tema_406 = "selected='selected'"; break;
			case "OF7":  $tema_407 = "selected='selected'"; break;
			case "OF8":  $tema_408 = "selected='selected'"; break;
			case "OF9":  $tema_409 = "selected='selected'"; break;
			case "OF10": $tema_410 = "selected='selected'"; break;
			case "OF11": $tema_411 = "selected='selected'"; break;
			case "OF12": $tema_412 = "selected='selected'"; break;
			case "OF13": $tema_413 = "selected='selected'"; break;
			case "OF14": $tema_414 = "selected='selected'"; break;
		}
		# FRANQUIA
		if($idfranquia_seg_1) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_seg_1 == $j) $_franqueado1 = $j;
		if($idfranquia_seg_2) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_seg_2 == $j) $_franqueado2 = $j;
		if($idfranquia_seg_3) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_seg_3 == $j) $_franqueado3 = $j;
		if($idfranquia_seg_4) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_seg_4 == $j) $_franqueado4 = $j;

	}elseif ( $inisemana == 'TER' ){
		switch($hora_ter_1) {
			case "01": $hora_11 = "selected='selected'"; break;
			case "02": $hora_12 = "selected='selected'"; break;
			case "03": $hora_13 = "selected='selected'"; break;
			case "04": $hora_14 = "selected='selected'"; break;
			case "05": $hora_15 = "selected='selected'"; break;
			case "06": $hora_16 = "selected='selected'"; break;
			case "07": $hora_17 = "selected='selected'"; break;
			case "08": $hora_18 = "selected='selected'"; break;
			case "09": $hora_19 = "selected='selected'"; break;
		}
		switch($hora_ter_2) {
			case "01": $hora_21 = "selected='selected'"; break;
			case "02": $hora_22 = "selected='selected'"; break;
			case "03": $hora_23 = "selected='selected'"; break;
			case "04": $hora_24 = "selected='selected'"; break;
			case "05": $hora_25 = "selected='selected'"; break;
			case "06": $hora_26 = "selected='selected'"; break;
			case "07": $hora_27 = "selected='selected'"; break;
			case "08": $hora_28 = "selected='selected'"; break;
			case "09": $hora_29 = "selected='selected'"; break;
		}
		switch($hora_ter_3) {
			case "01": $hora_31 = "selected='selected'"; break;
			case "02": $hora_32 = "selected='selected'"; break;
			case "03": $hora_33 = "selected='selected'"; break;
			case "04": $hora_34 = "selected='selected'"; break;
			case "05": $hora_35 = "selected='selected'"; break;
			case "06": $hora_36 = "selected='selected'"; break;
			case "07": $hora_37 = "selected='selected'"; break;
			case "08": $hora_38 = "selected='selected'"; break;
			case "09": $hora_39 = "selected='selected'"; break;
		}
		switch($hora_ter_4) {
			case "01": $hora_41 = "selected='selected'"; break;
			case "02": $hora_42 = "selected='selected'"; break;
			case "03": $hora_43 = "selected='selected'"; break;
			case "04": $hora_44 = "selected='selected'"; break;
			case "05": $hora_45 = "selected='selected'"; break;
			case "06": $hora_46 = "selected='selected'"; break;
			case "07": $hora_47 = "selected='selected'"; break;
			case "08": $hora_48 = "selected='selected'"; break;
			case "09": $hora_49 = "selected='selected'"; break;
		}
		# TEMA
		switch($tema_ter_1) {
			case "OF0":  $tema_100 = "selected='selected'"; break;
			case "OF1":  $tema_101 = "selected='selected'"; break;
			case "OF2":  $tema_102 = "selected='selected'"; break;
			case "OF3":  $tema_103 = "selected='selected'"; break;
			case "OF4":  $tema_104 = "selected='selected'"; break;
			case "OF5":  $tema_105 = "selected='selected'"; break;
			case "OF6":  $tema_106 = "selected='selected'"; break;
			case "OF7":  $tema_107 = "selected='selected'"; break;
			case "OF8":  $tema_108 = "selected='selected'"; break;
			case "OF9":  $tema_109 = "selected='selected'"; break;
			case "OF10": $tema_110 = "selected='selected'"; break;
			case "OF11": $tema_111 = "selected='selected'"; break;
			case "OF12": $tema_112 = "selected='selected'"; break;
			case "OF13": $tema_113 = "selected='selected'"; break;
			case "OF14": $tema_114 = "selected='selected'"; break;
		}
		switch($tema_ter_2) {
			case "OF1":  $tema_201 = "selected='selected'"; break;
			case "OF2":  $tema_202 = "selected='selected'"; break;
			case "OF3":  $tema_203 = "selected='selected'"; break;
			case "OF4":  $tema_204 = "selected='selected'"; break;
			case "OF5":  $tema_205 = "selected='selected'"; break;
			case "OF6":  $tema_206 = "selected='selected'"; break;
			case "OF7":  $tema_207 = "selected='selected'"; break;
			case "OF8":  $tema_208 = "selected='selected'"; break;
			case "OF9":  $tema_209 = "selected='selected'"; break;
			case "OF10": $tema_210 = "selected='selected'"; break;
			case "OF11": $tema_211 = "selected='selected'"; break;
			case "OF12": $tema_212 = "selected='selected'"; break;
			case "OF13": $tema_213 = "selected='selected'"; break;
			case "OF14": $tema_214 = "selected='selected'"; break;
		}
		switch($tema_ter_3) {
			case "OF1":  $tema_301 = "selected='selected'"; break;
			case "OF2":  $tema_302 = "selected='selected'"; break;
			case "OF3":  $tema_303 = "selected='selected'"; break;
			case "OF4":  $tema_304 = "selected='selected'"; break;
			case "OF5":  $tema_305 = "selected='selected'"; break;
			case "OF6":  $tema_306 = "selected='selected'"; break;
			case "OF7":  $tema_307 = "selected='selected'"; break;
			case "OF8":  $tema_308 = "selected='selected'"; break;
			case "OF9":  $tema_309 = "selected='selected'"; break;
			case "OF10": $tema_310 = "selected='selected'"; break;
			case "OF11": $tema_311 = "selected='selected'"; break;
			case "OF12": $tema_312 = "selected='selected'"; break;
			case "OF13": $tema_313 = "selected='selected'"; break;
			case "OF14": $tema_314 = "selected='selected'"; break;
		}
		switch($tema_ter_4) {
			case "OF1":  $tema_401 = "selected='selected'"; break;
			case "OF2":  $tema_402 = "selected='selected'"; break;
			case "OF3":  $tema_403 = "selected='selected'"; break;
			case "OF4":  $tema_404 = "selected='selected'"; break;
			case "OF5":  $tema_405 = "selected='selected'"; break;
			case "OF6":  $tema_406 = "selected='selected'"; break;
			case "OF7":  $tema_407 = "selected='selected'"; break;
			case "OF8":  $tema_408 = "selected='selected'"; break;
			case "OF9":  $tema_409 = "selected='selected'"; break;
			case "OF10": $tema_410 = "selected='selected'"; break;
			case "OF11": $tema_411 = "selected='selected'"; break;
			case "OF12": $tema_412 = "selected='selected'"; break;
			case "OF13": $tema_413 = "selected='selected'"; break;
			case "OF14": $tema_414 = "selected='selected'"; break;
		}
		# FRANQUIA

		if($idfranquia_ter_1) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_ter_1 == $j) $_franqueado1 = $j;
		if($idfranquia_ter_2) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_ter_2 == $j) $_franqueado2 = $j;
		if($idfranquia_ter_3) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_ter_3 == $j) $_franqueado3 = $j;
		if($idfranquia_ter_4) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_ter_4 == $j) $_franqueado4 = $j;
		
	}elseif ( $inisemana == 'QUA' ){
		switch($hora_qua_1) {
			case "01": $hora_11 = "selected='selected'"; break;
			case "02": $hora_12 = "selected='selected'"; break;
			case "03": $hora_13 = "selected='selected'"; break;
			case "04": $hora_14 = "selected='selected'"; break;
			case "05": $hora_15 = "selected='selected'"; break;
			case "06": $hora_16 = "selected='selected'"; break;
			case "07": $hora_17 = "selected='selected'"; break;
			case "08": $hora_18 = "selected='selected'"; break;
			case "09": $hora_19 = "selected='selected'"; break;
		}
		switch($hora_qua_2) {
			case "01": $hora_21 = "selected='selected'"; break;
			case "02": $hora_22 = "selected='selected'"; break;
			case "03": $hora_23 = "selected='selected'"; break;
			case "04": $hora_24 = "selected='selected'"; break;
			case "05": $hora_25 = "selected='selected'"; break;
			case "06": $hora_26 = "selected='selected'"; break;
			case "07": $hora_27 = "selected='selected'"; break;
			case "08": $hora_28 = "selected='selected'"; break;
			case "09": $hora_29 = "selected='selected'"; break;
		}
		switch($hora_qua_3) {
			case "01": $hora_31 = "selected='selected'"; break;
			case "02": $hora_32 = "selected='selected'"; break;
			case "03": $hora_33 = "selected='selected'"; break;
			case "04": $hora_34 = "selected='selected'"; break;
			case "05": $hora_35 = "selected='selected'"; break;
			case "06": $hora_36 = "selected='selected'"; break;
			case "07": $hora_37 = "selected='selected'"; break;
			case "08": $hora_38 = "selected='selected'"; break;
			case "09": $hora_39 = "selected='selected'"; break;
		}
		switch($hora_qua_4) {
			case "01": $hora_41 = "selected='selected'"; break;
			case "02": $hora_42 = "selected='selected'"; break;
			case "03": $hora_43 = "selected='selected'"; break;
			case "04": $hora_44 = "selected='selected'"; break;
			case "05": $hora_45 = "selected='selected'"; break;
			case "06": $hora_46 = "selected='selected'"; break;
			case "07": $hora_47 = "selected='selected'"; break;
			case "08": $hora_48 = "selected='selected'"; break;
			case "09": $hora_49 = "selected='selected'"; break;
		}
		# TEMA
		switch($tema_qua_1) {
			case "OF0":  $tema_100 = "selected='selected'"; break;
			case "OF1":  $tema_101 = "selected='selected'"; break;
			case "OF2":  $tema_102 = "selected='selected'"; break;
			case "OF3":  $tema_103 = "selected='selected'"; break;
			case "OF4":  $tema_104 = "selected='selected'"; break;
			case "OF5":  $tema_105 = "selected='selected'"; break;
			case "OF6":  $tema_106 = "selected='selected'"; break;
			case "OF7":  $tema_107 = "selected='selected'"; break;
			case "OF8":  $tema_108 = "selected='selected'"; break;
			case "OF9":  $tema_109 = "selected='selected'"; break;
			case "OF10": $tema_110 = "selected='selected'"; break;
			case "OF11": $tema_111 = "selected='selected'"; break;
			case "OF12": $tema_112 = "selected='selected'"; break;
			case "OF13": $tema_113 = "selected='selected'"; break;
			case "OF14": $tema_114 = "selected='selected'"; break;
		}
		switch($tema_qua_2) {
			case "OF1":  $tema_201 = "selected='selected'"; break;
			case "OF2":  $tema_202 = "selected='selected'"; break;
			case "OF3":  $tema_203 = "selected='selected'"; break;
			case "OF4":  $tema_204 = "selected='selected'"; break;
			case "OF5":  $tema_205 = "selected='selected'"; break;
			case "OF6":  $tema_206 = "selected='selected'"; break;
			case "OF7":  $tema_207 = "selected='selected'"; break;
			case "OF8":  $tema_208 = "selected='selected'"; break;
			case "OF9":  $tema_209 = "selected='selected'"; break;
			case "OF10": $tema_210 = "selected='selected'"; break;
			case "OF11": $tema_211 = "selected='selected'"; break;
			case "OF12": $tema_212 = "selected='selected'"; break;
			case "OF13": $tema_213 = "selected='selected'"; break;
			case "OF14": $tema_214 = "selected='selected'"; break;
		}
		switch($tema_qua_3) {
			case "OF1":  $tema_301 = "selected='selected'"; break;
			case "OF2":  $tema_302 = "selected='selected'"; break;
			case "OF3":  $tema_303 = "selected='selected'"; break;
			case "OF4":  $tema_304 = "selected='selected'"; break;
			case "OF5":  $tema_305 = "selected='selected'"; break;
			case "OF6":  $tema_306 = "selected='selected'"; break;
			case "OF7":  $tema_307 = "selected='selected'"; break;
			case "OF8":  $tema_308 = "selected='selected'"; break;
			case "OF9":  $tema_309 = "selected='selected'"; break;
			case "OF10": $tema_310 = "selected='selected'"; break;
			case "OF11": $tema_311 = "selected='selected'"; break;
			case "OF12": $tema_312 = "selected='selected'"; break;
			case "OF13": $tema_313 = "selected='selected'"; break;
			case "OF14": $tema_314 = "selected='selected'"; break;
		}
		switch($tema_qua_4) {
			case "OF1":  $tema_401 = "selected='selected'"; break;
			case "OF2":  $tema_402 = "selected='selected'"; break;
			case "OF3":  $tema_403 = "selected='selected'"; break;
			case "OF4":  $tema_404 = "selected='selected'"; break;
			case "OF5":  $tema_405 = "selected='selected'"; break;
			case "OF6":  $tema_406 = "selected='selected'"; break;
			case "OF7":  $tema_407 = "selected='selected'"; break;
			case "OF8":  $tema_408 = "selected='selected'"; break;
			case "OF9":  $tema_409 = "selected='selected'"; break;
			case "OF10": $tema_410 = "selected='selected'"; break;
			case "OF11": $tema_411 = "selected='selected'"; break;
			case "OF12": $tema_412 = "selected='selected'"; break;
			case "OF13": $tema_413 = "selected='selected'"; break;
			case "OF14": $tema_414 = "selected='selected'"; break;
		}
		# FRANQUIA
		if($idfranquia_qua_1) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_qua_1 == $j) $_franqueado1 = $j;
		if($idfranquia_qua_2) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_qua_2 == $j) $_franqueado2 = $j;
		if($idfranquia_qua_3) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_qua_3 == $j) $_franqueado3 = $j;
		if($idfranquia_qua_4) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_qua_4 == $j) $_franqueado4 = $j;

	}elseif ( $inisemana == 'QUI' ){
		switch($hora_qui_1) {
			case "01": $hora_11 = "selected='selected'"; break;
			case "02": $hora_12 = "selected='selected'"; break;
			case "03": $hora_13 = "selected='selected'"; break;
			case "04": $hora_14 = "selected='selected'"; break;
			case "05": $hora_15 = "selected='selected'"; break;
			case "06": $hora_16 = "selected='selected'"; break;
			case "07": $hora_17 = "selected='selected'"; break;
			case "08": $hora_18 = "selected='selected'"; break;
			case "09": $hora_19 = "selected='selected'"; break;
		}
		switch($hora_qui_2) {
			case "01": $hora_21 = "selected='selected'"; break;
			case "02": $hora_22 = "selected='selected'"; break;
			case "03": $hora_23 = "selected='selected'"; break;
			case "04": $hora_24 = "selected='selected'"; break;
			case "05": $hora_25 = "selected='selected'"; break;
			case "06": $hora_26 = "selected='selected'"; break;
			case "07": $hora_27 = "selected='selected'"; break;
			case "08": $hora_28 = "selected='selected'"; break;
			case "09": $hora_29 = "selected='selected'"; break;
		}
		switch($hora_qui_3) {
			case "01": $hora_31 = "selected='selected'"; break;
			case "02": $hora_32 = "selected='selected'"; break;
			case "03": $hora_33 = "selected='selected'"; break;
			case "04": $hora_34 = "selected='selected'"; break;
			case "05": $hora_35 = "selected='selected'"; break;
			case "06": $hora_36 = "selected='selected'"; break;
			case "07": $hora_37 = "selected='selected'"; break;
			case "08": $hora_38 = "selected='selected'"; break;
			case "09": $hora_39 = "selected='selected'"; break;
		}
		switch($hora_qui_4) {
			case "01": $hora_41 = "selected='selected'"; break;
			case "02": $hora_42 = "selected='selected'"; break;
			case "03": $hora_43 = "selected='selected'"; break;
			case "04": $hora_44 = "selected='selected'"; break;
			case "05": $hora_45 = "selected='selected'"; break;
			case "06": $hora_46 = "selected='selected'"; break;
			case "07": $hora_47 = "selected='selected'"; break;
			case "08": $hora_48 = "selected='selected'"; break;
			case "09": $hora_49 = "selected='selected'"; break;
		}
		# TEMA
		switch($tema_qui_1) {
			case "OF0":  $tema_100 = "selected='selected'"; break;
			case "OF1":  $tema_101 = "selected='selected'"; break;
			case "OF2":  $tema_102 = "selected='selected'"; break;
			case "OF3":  $tema_103 = "selected='selected'"; break;
			case "OF4":  $tema_104 = "selected='selected'"; break;
			case "OF5":  $tema_105 = "selected='selected'"; break;
			case "OF6":  $tema_106 = "selected='selected'"; break;
			case "OF7":  $tema_107 = "selected='selected'"; break;
			case "OF8":  $tema_108 = "selected='selected'"; break;
			case "OF9":  $tema_109 = "selected='selected'"; break;
			case "OF10": $tema_110 = "selected='selected'"; break;
			case "OF11": $tema_111 = "selected='selected'"; break;
			case "OF12": $tema_112 = "selected='selected'"; break;
			case "OF13": $tema_113 = "selected='selected'"; break;
			case "OF14": $tema_114 = "selected='selected'"; break;
		}
		switch($tema_qui_2) {
			case "OF1":  $tema_201 = "selected='selected'"; break;
			case "OF2":  $tema_202 = "selected='selected'"; break;
			case "OF3":  $tema_203 = "selected='selected'"; break;
			case "OF4":  $tema_204 = "selected='selected'"; break;
			case "OF5":  $tema_205 = "selected='selected'"; break;
			case "OF6":  $tema_206 = "selected='selected'"; break;
			case "OF7":  $tema_207 = "selected='selected'"; break;
			case "OF8":  $tema_208 = "selected='selected'"; break;
			case "OF9":  $tema_209 = "selected='selected'"; break;
			case "OF10": $tema_210 = "selected='selected'"; break;
			case "OF11": $tema_211 = "selected='selected'"; break;
			case "OF12": $tema_212 = "selected='selected'"; break;
			case "OF13": $tema_213 = "selected='selected'"; break;
			case "OF14": $tema_214 = "selected='selected'"; break;
		}
		switch($tema_qui_3) {
			case "OF1":  $tema_301 = "selected='selected'"; break;
			case "OF2":  $tema_302 = "selected='selected'"; break;
			case "OF3":  $tema_303 = "selected='selected'"; break;
			case "OF4":  $tema_304 = "selected='selected'"; break;
			case "OF5":  $tema_305 = "selected='selected'"; break;
			case "OF6":  $tema_306 = "selected='selected'"; break;
			case "OF7":  $tema_307 = "selected='selected'"; break;
			case "OF8":  $tema_308 = "selected='selected'"; break;
			case "OF9":  $tema_309 = "selected='selected'"; break;
			case "OF10": $tema_310 = "selected='selected'"; break;
			case "OF11": $tema_311 = "selected='selected'"; break;
			case "OF12": $tema_312 = "selected='selected'"; break;
			case "OF13": $tema_313 = "selected='selected'"; break;
			case "OF14": $tema_314 = "selected='selected'"; break;
		}
		switch($tema_qui_4) {
			case "OF1":  $tema_401 = "selected='selected'"; break;
			case "OF2":  $tema_402 = "selected='selected'"; break;
			case "OF3":  $tema_403 = "selected='selected'"; break;
			case "OF4":  $tema_404 = "selected='selected'"; break;
			case "OF5":  $tema_405 = "selected='selected'"; break;
			case "OF6":  $tema_406 = "selected='selected'"; break;
			case "OF7":  $tema_407 = "selected='selected'"; break;
			case "OF8":  $tema_408 = "selected='selected'"; break;
			case "OF9":  $tema_409 = "selected='selected'"; break;
			case "OF10": $tema_410 = "selected='selected'"; break;
			case "OF11": $tema_411 = "selected='selected'"; break;
			case "OF12": $tema_412 = "selected='selected'"; break;
			case "OF13": $tema_413 = "selected='selected'"; break;
			case "OF14": $tema_414 = "selected='selected'"; break;
		}
		# FRANQUIA
		if($idfranquia_qui_1) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_qui_1 == $j) $_franqueado1 = $j;
		if($idfranquia_qui_2) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_qui_2 == $j) $_franqueado2 = $j;
		if($idfranquia_qui_3) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_qui_3 == $j) $_franqueado3 = $j;
		if($idfranquia_qui_4) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_qui_4 == $j) $_franqueado4 = $j;

	}elseif ( $inisemana == 'SEX' ){
		switch($hora_sex_1) {
			case "01": $hora_11 = "selected='selected'"; break;
			case "02": $hora_12 = "selected='selected'"; break;
			case "03": $hora_13 = "selected='selected'"; break;
			case "04": $hora_14 = "selected='selected'"; break;
			case "05": $hora_15 = "selected='selected'"; break;
			case "06": $hora_16 = "selected='selected'"; break;
			case "07": $hora_17 = "selected='selected'"; break;
			case "08": $hora_18 = "selected='selected'"; break;
			case "09": $hora_19 = "selected='selected'"; break;
		}
		switch($hora_sex_2) {
			case "01": $hora_21 = "selected='selected'"; break;
			case "02": $hora_22 = "selected='selected'"; break;
			case "03": $hora_23 = "selected='selected'"; break;
			case "04": $hora_24 = "selected='selected'"; break;
			case "05": $hora_25 = "selected='selected'"; break;
			case "06": $hora_26 = "selected='selected'"; break;
			case "07": $hora_27 = "selected='selected'"; break;
			case "08": $hora_28 = "selected='selected'"; break;
			case "09": $hora_29 = "selected='selected'"; break;
		}
		switch($hora_sex_3) {
			case "01": $hora_31 = "selected='selected'"; break;
			case "02": $hora_32 = "selected='selected'"; break;
			case "03": $hora_33 = "selected='selected'"; break;
			case "04": $hora_34 = "selected='selected'"; break;
			case "05": $hora_35 = "selected='selected'"; break;
			case "06": $hora_36 = "selected='selected'"; break;
			case "07": $hora_37 = "selected='selected'"; break;
			case "08": $hora_38 = "selected='selected'"; break;
			case "09": $hora_39 = "selected='selected'"; break;
		}
		switch($hora_sex_4) {
			case "01": $hora_41 = "selected='selected'"; break;
			case "02": $hora_42 = "selected='selected'"; break;
			case "03": $hora_43 = "selected='selected'"; break;
			case "04": $hora_44 = "selected='selected'"; break;
			case "05": $hora_45 = "selected='selected'"; break;
			case "06": $hora_46 = "selected='selected'"; break;
			case "07": $hora_47 = "selected='selected'"; break;
			case "08": $hora_48 = "selected='selected'"; break;
			case "09": $hora_49 = "selected='selected'"; break;
		}
		# TEMA
		switch($tema_sex_1) {
			case "OF0":  $tema_100 = "selected='selected'"; break;
			case "OF1":  $tema_101 = "selected='selected'"; break;
			case "OF2":  $tema_102 = "selected='selected'"; break;
			case "OF3":  $tema_103 = "selected='selected'"; break;
			case "OF4":  $tema_104 = "selected='selected'"; break;
			case "OF5":  $tema_105 = "selected='selected'"; break;
			case "OF6":  $tema_106 = "selected='selected'"; break;
			case "OF7":  $tema_107 = "selected='selected'"; break;
			case "OF8":  $tema_108 = "selected='selected'"; break;
			case "OF9":  $tema_109 = "selected='selected'"; break;
			case "OF10": $tema_110 = "selected='selected'"; break;
			case "OF11": $tema_111 = "selected='selected'"; break;
			case "OF12": $tema_112 = "selected='selected'"; break;
			case "OF13": $tema_113 = "selected='selected'"; break;
			case "OF14": $tema_114 = "selected='selected'"; break;
		}
		switch($tema_sex_2) {
			case "OF1":  $tema_201 = "selected='selected'"; break;
			case "OF2":  $tema_202 = "selected='selected'"; break;
			case "OF3":  $tema_203 = "selected='selected'"; break;
			case "OF4":  $tema_204 = "selected='selected'"; break;
			case "OF5":  $tema_205 = "selected='selected'"; break;
			case "OF6":  $tema_206 = "selected='selected'"; break;
			case "OF7":  $tema_207 = "selected='selected'"; break;
			case "OF8":  $tema_208 = "selected='selected'"; break;
			case "OF9":  $tema_209 = "selected='selected'"; break;
			case "OF10": $tema_210 = "selected='selected'"; break;
			case "OF11": $tema_211 = "selected='selected'"; break;
			case "OF12": $tema_212 = "selected='selected'"; break;
			case "OF13": $tema_213 = "selected='selected'"; break;
			case "OF14": $tema_214 = "selected='selected'"; break;
		}
		switch($tema_sex_3) {
			case "OF1":  $tema_301 = "selected='selected'"; break;
			case "OF2":  $tema_302 = "selected='selected'"; break;
			case "OF3":  $tema_303 = "selected='selected'"; break;
			case "OF4":  $tema_304 = "selected='selected'"; break;
			case "OF5":  $tema_305 = "selected='selected'"; break;
			case "OF6":  $tema_306 = "selected='selected'"; break;
			case "OF7":  $tema_307 = "selected='selected'"; break;
			case "OF8":  $tema_308 = "selected='selected'"; break;
			case "OF9":  $tema_309 = "selected='selected'"; break;
			case "OF10": $tema_310 = "selected='selected'"; break;
			case "OF11": $tema_311 = "selected='selected'"; break;
			case "OF12": $tema_312 = "selected='selected'"; break;
			case "OF13": $tema_313 = "selected='selected'"; break;
			case "OF14": $tema_314 = "selected='selected'"; break;
		}
		switch($tema_sex_4) {
			case "OF1":  $tema_401 = "selected='selected'"; break;
			case "OF2":  $tema_402 = "selected='selected'"; break;
			case "OF3":  $tema_403 = "selected='selected'"; break;
			case "OF4":  $tema_404 = "selected='selected'"; break;
			case "OF5":  $tema_405 = "selected='selected'"; break;
			case "OF6":  $tema_406 = "selected='selected'"; break;
			case "OF7":  $tema_407 = "selected='selected'"; break;
			case "OF8":  $tema_408 = "selected='selected'"; break;
			case "OF9":  $tema_409 = "selected='selected'"; break;
			case "OF10": $tema_410 = "selected='selected'"; break;
			case "OF11": $tema_411 = "selected='selected'"; break;
			case "OF12": $tema_412 = "selected='selected'"; break;
			case "OF13": $tema_413 = "selected='selected'"; break;
			case "OF14": $tema_414 = "selected='selected'"; break;
		}
		# FRANQUIA
		
		if($idfranquia_sex_1) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_sex_1 == $j) $_franqueado1 = $j;
		if($idfranquia_sex_2) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_sex_2 == $j) $_franqueado2 = $j;
		if($idfranquia_sex_3) 
			for ( $j = 1 ; $j <= 5000 ; $j++ )	
		if ( $idfranquia_sex_3 == $j) $_franqueado3 = $j;
		if($idfranquia_sex_4) for ( $j = 1 ; $j <= 5000 ; $j++ )	if ( $idfranquia_sex_4 == $j) $_franqueado4 = $j;

	}
	

	echo "<td style=\"align='center'\">".$dia_semana.'<br>'.date("d/m/".$_ano_esc, $ts);
				
	echo "<td  colspan='3'>
			<table class='table table-striped table-responsive col30'>
				<tr>
					<td>
						<select name='horasemana' $bloqueio_dia $bloqueio_geral onchange=\"teste('hora',this.value,'1','$inisemana','$wantedweek')\">
							<option value='00' $hora_10 >Selecione o horario</option>
							<option value='01' $hora_11 >08:00 as 09:00</option>
							<option value='02' $hora_12 >09:00 as 10:00</option>
							<option value='03' $hora_13 >10:00 as 11:00</option>
							<option value='04' $hora_14 >11:00 as 12:00</option>
							<option value='05' $hora_15 >14:00 as 15:00</option>
							<option value='06' $hora_16 >15:00 as 16:00</option>
							<option value='07' $hora_17 >16:00 as 17:00</option>
							<option value='08' $hora_18 >17:00 as 18:00</option>
							<option value='09' $hora_19 >18:00 as 19:00</option>
						</select>
					</td>
					<td>
						<select name='oficina' $bloqueio_dia $bloqueio_geral onchange=\"teste('tema',this.value,'1','$inisemana','$wantedweek')\">
							<option value='0'>Escolha do Tema</option>
							<option value='OF0' $tema_100 >PRETENDENTES A FRANQUEADOS</option>
							<option value='OF1' $tema_101 >OFICINA DE LOCALIZA MAX</option>
							<option value='OF2' $tema_102 >OFICINA DE CREDIARIO / BOLETO </option>
							<option value='OF3' $tema_103 >OFICINA DE PESQUISA DE CREDITO</option>
							<option value='OF4' $tema_104 >OFICINA DE RECUPERE SYSTEM</option>
							<option value='OF5' $tema_105 >OFICINA DE WEB-CONTROL</option>
							<option value='OF6' $tema_106 >OFICINA DE LOCALIZA MAX NOVOS CLIENTES</option>
							<option value='OF7' $tema_107 >OFICINA DE VIRTUAL FLEX</option>
							<option value='OF8' $tema_108 >OFICINA DE CONTABIL SOLUTION</option>
							<option value='OF9' $tema_109 >OFICINA DE OBJECOES DE CLIENTES</option>
							<option value='OF10' $tema_110 >OFICINA DE TAXA DE IMPLANTACAO</option>
							<option value='OF11' $tema_111 >OFICINA DE ABORDAGEM A CLIENTES</option>
							<option value='OF12' $tema_112 >OFICINA DE FECHAMENTO DA VENDA</option>
							<option value='OF13' $tema_113 >REUNIAO COM GERENTES COMERCIAIS</option>
							<option value='OF14' $tema_114 >OFICINA COM FRANQUEADO</option>
						</select>
					</td>
					<td>";

						echo "<select name='idfranquia' $bloqueio_dia $bloqueio_geral onchange=\"teste('idfranquia',this.value,'1','$inisemana','$wantedweek')\">";
						echo "<option value='0'>Selecione a Franquia</option>";
						$sql = "SELECT id, fantasia FROM franquia 
									WHERE sitfrq = 0 AND ( classificacao IN ('M','X')  )
									ORDER BY id";
						$resposta = mysql_query($sql, $con);
						while ($array = mysql_fetch_array($resposta)) {
							$franquia   = $array["id"];
							$nome_franquia = $franquia.' - '.$array["fantasia"];
							
							if ($franquia == $_franqueado1) {
								echo "<option value='$franquia' selected='selected'>$nome_franquia</option>\n"; }
							else {
								echo "<option value='$franquia' >$nome_franquia</option>\n"; }
						}
						echo "</select>
					</td>
				</tr>
				<tr>
					<td>
						<select name='horasemana' $bloqueio_geral onchange=\"teste('hora',this.value,'2','$inisemana','$wantedweek')\">
							<option value='00' $hora_20 >Selecione o horario</option>
							<option value='01' $hora_21 >08:00 as 09:00</option>
							<option value='02' $hora_22 >09:00 as 10:00</option>
							<option value='03' $hora_23 >10:00 as 11:00</option>
							<option value='04' $hora_24 >11:00 as 12:00</option>
							<option value='05' $hora_25 >14:00 as 15:00</option>
							<option value='06' $hora_26 >15:00 as 16:00</option>
							<option value='07' $hora_27 >16:00 as 17:00</option>
							<option value='08' $hora_28 >17:00 as 18:00</option>
							<option value='09' $hora_29 >18:00 as 19:00</option>
						</select>
					</td>
					<td>
						<select name='oficina' $bloqueio_geral onchange=\"teste('tema',this.value,'2','$inisemana','$wantedweek')\">
							<option value='0'>Escolha do Tema</option>
							<option value='OF1'  $tema_201 >OFICINA DE LOCALIZA MAX</option>
							<option value='OF2'  $tema_202 >OFICINA DE CREDIARIO / BOLETO </option>
							<option value='OF3'  $tema_203 >OFICINA DE PESQUISA DE CREDITO</option>
							<option value='OF4'  $tema_204 >OFICINA DE RECUPERE SYSTEM</option>
							<option value='OF5'  $tema_205 >OFICINA DE WEB-CONTROL</option>
							<option value='OF6'  $tema_206 >OFICINA DE LOCALIZA MAX NOVOS CLIENTES</option>
							<option value='OF7'  $tema_207 >OFICINA DE VIRTUAL FLEX</option>
							<option value='OF8'  $tema_208 >OFICINA DE CONTABIL SOLUTION</option>
							<option value='OF9'  $tema_209 >OFICINA DE OBJECOES DE CLIENTES</option>
							<option value='OF10' $tema_210 >OFICINA DE TAXA DE IMPLANTACAO</option>
							<option value='OF11' $tema_211 >OFICINA DE ABORDAGEM A CLIENTES</option>
							<option value='OF12' $tema_212 >OFICINA DE FECHAMENTO DA VENDA</option>
							<option value='OF13' $tema_213 >REUNIAO COM GERENTES COMERCIAIS</option>
							<option value='OF14' $tema_214 >OFICINA COM FRANQUEADO</option>
						</select>
					</td>
					<td>";
						echo "<select name='idfranquia' $bloqueio_geral onchange=\"teste('idfranquia',this.value,'2','$inisemana','$wantedweek')\">";
						echo "<option value='0'>Selecione a Franquia</option>";
						$sql = "SELECT id, fantasia FROM franquia 
								WHERE sitfrq = 0 AND ( classificacao IN ('M','X')  )
								ORDER BY id";
						$resposta = mysql_query($sql, $con);
						while ($array = mysql_fetch_array($resposta)) {
							$franquia   = $array["id"];
							$nome_franquia = $franquia.' - '.$array["fantasia"];
							if ($franquia == $_franqueado2) {
								echo "<option value='$franquia' selected='selected'>$nome_franquia</option>\n";
							}else{
								echo "<option value='$franquia' >$nome_franquia</option>\n";
							}

						}
						echo "</select>
					</td>
				</tr>
				<tr>
					<td>
						<select name='horasemana' $bloqueio_geral onchange=\"teste('hora',this.value,'3','$inisemana','$wantedweek')\">
							<option value='00' $hora_30 >Selecione o horario</option>
							<option value='01' $hora_31 >08:00 as 09:00</option>
							<option value='02' $hora_32 >09:00 as 10:00</option>
							<option value='03' $hora_33 >10:00 as 11:00</option>
							<option value='04' $hora_34 >11:00 as 12:00</option>
							<option value='05' $hora_35 >14:00 as 15:00</option>
							<option value='06' $hora_36 >15:00 as 16:00</option>
							<option value='07' $hora_37 >16:00 as 17:00</option>
							<option value='08' $hora_38 >17:00 as 18:00</option>
							<option value='09' $hora_39 >18:00 as 19:00</option>
						</select>
					</td>
					<td>
						<select name='oficina' $bloqueio_geral onchange=\"teste('tema',this.value,'3','$inisemana','$wantedweek')\">
							<option value='0'>Escolha do Tema</option>
							<option value='OF1'  $tema_301 >OFICINA DE LOCALIZA MAX</option>
							<option value='OF2'  $tema_302 >OFICINA DE CREDIARIO / BOLETO </option>
							<option value='OF3'  $tema_303 >OFICINA DE PESQUISA DE CREDITO</option>
							<option value='OF4'  $tema_304 >OFICINA DE RECUPERE SYSTEM</option>
							<option value='OF5'  $tema_305 >OFICINA DE WEB-CONTROL</option>
							<option value='OF6'  $tema_306 >OFICINA DE LOCALIZA MAX NOVOS CLIENTES</option>
							<option value='OF7'  $tema_307 >OFICINA DE VIRTUAL FLEX</option>
							<option value='OF8'  $tema_308 >OFICINA DE CONTABIL SOLUTION</option>
							<option value='OF9'  $tema_309 >OFICINA DE OBJECOES DE CLIENTES</option>
							<option value='OF10' $tema_310 >OFICINA DE TAXA DE IMPLANTACAO</option>
							<option value='OF11' $tema_311 >OFICINA DE ABORDAGEM A CLIENTES</option>
							<option value='OF12' $tema_312 >OFICINA DE FECHAMENTO DA VENDA</option>
							<option value='OF13' $tema_313 >REUNIAO COM GERENTES COMERCIAIS</option>
							<option value='OF14' $tema_314 >OFICINA COM FRANQUEADO</option>
						</select>
					</td>
					<td>";
						echo "<select name='idfranquia' $bloqueio_geral onchange=\"teste('idfranquia',this.value,'3','$inisemana','$wantedweek')\">";
						echo "<option value='0'>Selecione a Franquia</option>";
						$sql = "SELECT id, fantasia FROM franquia 
								WHERE sitfrq = 0 AND ( classificacao IN ('M','X')  )
								ORDER BY id";
						$resposta = mysql_query($sql, $con);
						while ($array = mysql_fetch_array($resposta)) {
							$franquia   = $array["id"];
							$nome_franquia = $franquia.' - '.$array["fantasia"];
							if ($franquia == $_franqueado3) {
								echo "<option value='$franquia' selected='selected'>$nome_franquia</option>\n";
							}else{
								echo "<option value='$franquia' >$nome_franquia</option>\n";
							}

						}
						echo "</select>
					</td>
				</tr>
				<tr>
					<td>
						<select name='horasemana' $bloqueio_geral onchange=\"teste('hora',this.value,'4','$inisemana','$wantedweek')\">
							<option value='00' $hora_40 >Selecione o horario</option>
							<option value='01' $hora_41 >08:00 as 09:00</option>
							<option value='02' $hora_42 >09:00 as 10:00</option>
							<option value='03' $hora_43 >10:00 as 11:00</option>
							<option value='04' $hora_44 >11:00 as 12:00</option>
							<option value='05' $hora_45 >14:00 as 15:00</option>
							<option value='06' $hora_46 >15:00 as 16:00</option>
							<option value='07' $hora_47 >16:00 as 17:00</option>
							<option value='08' $hora_48 >17:00 as 18:00</option>
							<option value='09' $hora_49 >18:00 as 19:00</option>
						</select>
					</td>
					<td>
						<select name='oficina' $bloqueio_geral onchange=\"teste('tema',this.value,'4','$inisemana','$wantedweek')\">
							<option value='0'>Escolha do Tema</option>
							<option value='OF1'  $tema_401 >OFICINA DE LOCALIZA MAX</option>
							<option value='OF2'  $tema_402 >OFICINA DE CREDIARIO / BOLETO </option>
							<option value='OF3'  $tema_403 >OFICINA DE PESQUISA DE CREDITO</option>
							<option value='OF4'  $tema_404 >OFICINA DE RECUPERE SYSTEM</option>
							<option value='OF5'  $tema_405 >OFICINA DE WEB-CONTROL</option>
							<option value='OF6'  $tema_406 >OFICINA DE LOCALIZA MAX NOVOS CLIENTES</option>
							<option value='OF7'  $tema_407 >OFICINA DE VIRTUAL FLEX</option>
							<option value='OF8'  $tema_408 >OFICINA DE CONTABIL SOLUTION</option>
							<option value='OF9'  $tema_409 >OFICINA DE OBJECOES DE CLIENTES</option>
							<option value='OF10' $tema_410 >OFICINA DE TAXA DE IMPLANTACAO</option>
							<option value='OF11' $tema_411 >OFICINA DE ABORDAGEM A CLIENTES</option>
							<option value='OF12' $tema_412 >OFICINA DE FECHAMENTO DA VENDA</option>
							<option value='OF13' $tema_413 >REUNIAO COM GERENTES COMERCIAIS</option>
							<option value='OF14' $tema_414 >OFICINA COM FRANQUEADO</option>
						</select>
					</td>
					<td>";
						echo "<select name='idfranquia' $bloqueio_geral onchange=\"teste('idfranquia',this.value,'4','$inisemana','$wantedweek')\">";
						echo "<option value='0'>Selecione a Franquia</option>";
						$sql = "SELECT id, fantasia FROM franquia 
								WHERE sitfrq = 0 AND ( classificacao IN ('M','X')  )
								ORDER BY id";
						$resposta = mysql_query($sql, $con);
						while ($array = mysql_fetch_array($resposta)) {
							$franquia   = $array["id"];
							$nome_franquia = $franquia.' - '.$array["fantasia"];
							if ($franquia == $_franqueado4) {
								echo "<option value='$franquia' selected='selected'>$nome_franquia</option>\n";
							}else{
								echo "<option value='$franquia' >$nome_franquia</option>\n";
							}
						}
						echo "</select>
					</td>
				</tr>
			</table>
	</td>
</tr>
</tbody>";
}
?>