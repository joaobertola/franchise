<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$selected = $_POST['selected'];
$codloja = $_POST['codloja'];
$limite = $_POST['limite'];
$tplib = $_POST['tplib'];
$tipo_cliente = $_POST['tipo_cliente'];

$logon = $_POST['logon'];
$ip = getenv("REMOTE_ADDR");
$selected_cnt   = count($selected);
if (empty($selected_cnt)) {
	echo "<script>alert(\"Você não marcou nenhuma opção!\");</script>";
	echo "<meta http-equiv=Refresh content=\"0; url= ../painel.php?pagina1=Franquias/b_liberaconsulta1.php&codigo=$codigo\";>";
}

switch($tplib) {
	case 0 : // Aumenta da QTD de consulta
		for ($i=0; $i<$selected_cnt; $i++) { 
			$b = $selected[$i];
			if ( $tipo_cliente == 'A' ){
				
				// Verifico se a consulta selecionada tem disponivel, se tiver nao permite alterar mais.
				// Obs.;  somente para o codigo 19120
				//              19120, 77120, 77117, 77121, 77113
				if ( $codloja == 764 or $codloja == 54835 or $codloja == 54832 or $codloja == 54836 or $codloja == 54828 or $codloja == 54828 ){
					$resultado = 0;
					for ($j=0; $j<$selected_cnt; $j++) { 
						$d = $selected[$j];
						$sql_ver = "SELECT qtd - consumo as resultado FROM cs2.cons_liberada_logon WHERE tpcons = '$d' AND codloja = '$codloja' AND CAST(MID(logon,1,6) AS UNSIGNED)= '$logon'";
						$qry_ver = mysql_query($sql_ver,$con) or die("Erro SQL $sql_ver");
						$resultado += mysql_result($qry_ver,0,'resultado');
					}
					if ( $resultado > 0 ){
						echo "<script>alert(\"Dentre as consultas selecionadas, existem consultas disponiveis!\");history.back();</script>";
						exit;
					}
				}
				$comando = "UPDATE cs2.cons_liberada_logon SET qtd=qtd+$limite WHERE tpcons = '$b' AND codloja = '$codloja' AND CAST(MID(logon,1,6) AS UNSIGNED)= '$logon'";
			}else{
			
				// Verifico se a consulta selecionada tem disponivel, se tiver nao permite alterar mais.
				// Obs.;  somente para o codigo 19120
				//              19120, 77120, 77117, 77121, 77113
				if ( $codloja == 764 or $codloja == 54835 or $codloja == 54832 or $codloja == 54836 or $codloja == 54828 or $codloja == 54828 ){
					$resultado = 0;
					for ($j=0; $j<$selected_cnt; $j++) { 
						$d = $selected[$j];
						$sql_ver = "SELECT qtd - consumo as resultado FROM cs2.cons_liberada WHERE tpcons = '$d' AND codloja = '$codloja'";
						$qry_ver = mysql_query($sql_ver,$con) or die("Erro SQL $sql_ver");
						$resultado += mysql_result($qry_ver,0,'resultado');
					}
					if ( $resultado > 0 ){
						echo "<script>alert(\"Dentre as consultas selecionadas, existem consultas disponiveis!\");history.back()</script>";
						exit;
					}
				}	
				$comando = "update cs2.cons_liberada set qtd=qtd+$limite where tpcons = '$b' and codloja = '$codloja'";
			}
			$res = mysql_query ($comando, $con);
			// Grava o Log
			$texto = "LIBERACAO DE CONSULTA: Usu�rio: ".$_SESSION['usuario']." IP: $ip Consulta: $b  Qtd: +($limite) Cliente: [$codloja]";
			$sql_grava = "INSERT INTO cs2.mensagem_cs2(data_cad, hora_cad, mensagem) VALUES( NOW(), NOW(), '$texto' )";
			mysql_query($sql_grava,$con);
		}
		break;
	case 1: // Diminui a QTD de consulta
		for ($i=0; $i<$selected_cnt; $i++) {
			$b = $selected[$i];
			
			if ( $tipo_cliente == 'A' ){
				
				mysql_query ("update cs2.cons_liberada_logon set qtd = qtd - $limite where tpcons = '$b' and codloja = '$codloja' and CAST(MID(logon,1,6) AS UNSIGNED)= '$logon'", $con);
				mysql_query ("update cs2.cons_liberada_logon set qtd=0 where tpcons = '$b' and codloja = '$codloja' and qtd<=0 and CAST(MID(logon,1,6) AS UNSIGNED)= '$logon'", $con);

			}else{
				mysql_query ("update cs2.cons_liberada set qtd = qtd - $limite where tpcons = '$b' and codloja = '$codloja'", $con);
				mysql_query ("update cs2.cons_liberada set qtd=0 where tpcons = '$b' and codloja = '$codloja' and qtd<=0", $con);
			}
			
			// Grava o Log
			$texto = "LIBERACAO DE CONSULTA: Usu�rio: ".$_SESSION['usuario']." IP: $ip Consulta: $b  Qtd: -($limite) Cliente: [$codloja] ";
			$sql_grava = "INSERT INTO cs2.mensagem_cs2(data_cad, hora_cad, mensagem) VALUES( NOW(), NOW(), '$texto' )";
			mysql_query($sql_grava,$con);
			
		}
		break;
} //fim switch
$res = mysql_close ($con);
echo "<meta http-equiv=\"refresh\" content=\"0; url= ../painel.php?pagina1=Franquias/b_consliberada.php&codloja=$codloja&logon=$logon\";>";
?>