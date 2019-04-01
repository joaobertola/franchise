<?php
$sql_total_contratos = "SELECT qtd_contrato_mes FROM cs2.franquia WHERE id = '{$_SESSION['id']}'";
$qry_total_contratos = mysql_query($sql_total_contratos, $con);
$meta_mes_contrato = mysql_result($qry_total_contratos,0,'qtd_contrato_mes');

$ano_mes = date("Y-m");

$sql_contratos_mes = "select a.codloja, mid(b.logon,1,5) as logon, a.nomefantasia, a.vendedor,
			date_format(a.dt_cad, '%d/%m/%Y') AS data, c.fantasia from cadastro a
			inner join logon b on a.codloja = b.codloja
			inner join franquia c on a.id_franquia = c.id
			where a.dt_cad like '{$ano_mes}%'  and id_franquia='{$_SESSION['id']}' and a.sitcli = 0
			group by a.codloja order by logon";								  
$qry_contratos_mes = mysql_query($sql_contratos_mes, $con);
$total_contrato_mes_fechado = mysql_num_rows($qry_contratos_mes);
$total_falta = $meta_mes_contrato - $total_contrato_mes_fechado; 

$cnx_email = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
$sql_frame = "SELECT * FROM dbsites.tbl_escolhaframe WHERE esc_ativo = 'S' ORDER BY esc_data_ativacao DESC LIMIT 10 ";
$qry_frame = mysql_query($sql_frame, $cnx_email) or die ("Erro SQL ao listar os Frames : $sql_frame");

	$dia = date('%d');
	$_dia = date('d');
	if( ($_dia == 06) or ($_dia == 12) or ($_dia == 29) or ($_dia == 03) or ($_dia == 17) or ($_dia == 31) or 
	    ($_dia == 24) or ($_dia == 01) or ($_dia == 15) or ($_dia == 09) or ($_dia == 19) or ($_dia == 20) or 
		($_dia == 26) or ($_dia == 02) or ($_dia == 16) or ($_dia == 22) or ($_dia == 08) or ($_dia == 18) or 
		($_dia == 07) or ($_dia == 21) or ($_dia == 28) or ($_dia == 13) or ($_dia == 04) or ($_dia == 14) or
		($_dia == 27) or ($_dia == 05)){		
		$largura = '520';
		$altura  = '800';	
	}
	
	if( ($_dia == 10) or ($_dia == 23) or ($_dia == 30) ){
		$largura = '650';
		$altura  = '450';	
	} 
	
	if( ($_dia == 11) or ($_dia == 25) ){
		$largura = '650';
		$altura  = '525';	
	}
?>