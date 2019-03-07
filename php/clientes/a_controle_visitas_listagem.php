<?php

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";


$protocolo = $_REQUEST['protocolo'];

if (  $id_franquia == 1 or $id_franquia == 163 or $id_franquia == 46 or $id_franquia == 59 or $id_franquia == 4	)
	$id_franquia = 247;
	
	
echo "<table width='100%' border='1' cellpadding='0' cellspacing='0' class='bodyText'>
	 	<tr>
			<td align='center' colspan='7' height='1' bgcolor='#CCCCCC'>RELAT&Oacute;RIO DE VISITAS AGENDADAS</td>
		</tr>
		<tr height='20' bgcolor='87b5ff' style='font-size:9px'>
			<td height='15' align='left'>Data Cadastro</td>
			<td height='15' align='left'>Data Agendamento</td>
			<td height='15' align='left'>Assistente</td>
			<td height='15' align='left'>Consultor</td>
			<td height='15' align='left'>Empresa</td>
			<td height='15' align='left'>Fones</td>
			<td height='15' align='left'>Respons&aacute;vel</td>
		</tr>
		<tr>
			<td colspan='7' height='1' bgcolor='#666666'></td>
		</tr>";

$registro = 0; 

$sql_pesquisa = "SELECT date_format(data_cadastro,'%d/%m/%Y') as data_cadastro, 
						date_format(data_agendamento,'%d/%m/%Y') as data_agendamento, 
						hora_agendamento, assitente_comercial,
						id_consultor, empresa, endereco, fone1, fone2, responsavel, id_assistente
				 FROM cs2.controle_comercial_visitas 
				 WHERE id = $protocolo and id_franquia = $id_franquia";


$qry_pesquisa = mysql_query($sql_pesquisa, $con) or die ("Erro MYSQL");
if ( mysql_num_rows($qry_pesquisa) > 0 ){
	while ( $reg = mysql_fetch_array($qry_pesquisa) ){
		$registro++;
		$data_cadastro = $reg['data_cadastro'];
		$data_agendamento = $reg['data_agendamento'];
		$hora_agendamento = $reg['hora_agendamento'];
		$assitente_comercial = $reg['assitente_comercial'];
		$id_assistente = $reg['id_assistente'];
		$id_consultor = $reg['id_consultor'];
		$empresa = $reg['empresa'];
		$endereco = $reg['endereco'];
		$fone1 = $reg['fone1'];
		$fone2 = $reg['fone2'];
		$responsavel = $reg['responsavel'];
    $id_assistente = $reg['id_assistente'];
    
    $sql = mysql_query("SELECT nome FROM cs2.consultores_assistente WHERE id = '$id_consultor'", $con);
    if (mysql_num_rows($sql) == 0) {
        $consultor = '';
    } else {
        $consultor = mysql_result($sql,0,'nome');
    }

    $sql_a = mysql_query("SELECT nome FROM cs2.consultores_assistente WHERE id = '$id_assistente'", $con);

    if (mysql_num_rows($sql_a) == 0) {
        $assistente = '';
    } else {
        $assistente = mysql_result($sql_a,0,'nome');
    }

		echo "<tr height='24'  style='font-size:9px' ";
		if (($registro%2) <> 0) {
			echo "bgcolor='#E5E5E5'>";
		} else {
			echo ">";
		}                 
		echo "<td align='left'>                
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_franquia=$id_franquia'>$data_cadastro</a>
				</td>
  			    <td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_franquia=$id_franquia'>$data_agendamento</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_franquia=$id_franquia'>$assistente</a>
				</td>
  			    <td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_franquia=$id_franquia'>$consultor</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_franquia=$id_franquia'>$empresa</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_franquia=$id_franquia'>$fone1 - $fone2</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_franquia=$id_franquia'>$responsavel</a>
				</td>";
		echo "</tr>";
	}
}                                                         
					    
echo "</table>";                               
?>