<?php
include("Includes/Connection_inc.php");

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case 'grafico_franquia_19':
			$selecao = str_replace('a.id_franquia', 'c.id_franquia', $_POST['selecao']);

			// Function to connect to the DB
			$link = connectToDB();
			$strSQL = " SELECT count(*) as x, 
			                CONCAT( 
			                    CASE month(a.data_hora) 
			                        WHEN 1 THEN 'Janeiro'
			                        WHEN 2 THEN 'Fevereiro'        
			                        WHEN 3 THEN 'Marco'        
			                        WHEN 4 THEN 'Abril'        
			                        WHEN 5 THEN 'Maio'        
			                        WHEN 6 THEN 'Junho'        
			                        WHEN 7 THEN 'Julho'
			                        WHEN 8 THEN 'Agosto'
			                        WHEN 9 THEN 'Setembro'
			                        WHEN 10 THEN 'Outubro'        
			                        WHEN 11 THEN 'Novembro'        
			                        WHEN 12 THEN 'Dezembro'
			                    end ,
			                    '/',
			                    year(a.data_hora)
			                ) as y 
			            FROM base_web_control.venda_notas_eletronicas a
			            INNER JOIN base_web_control.venda b ON a.id_venda = b.id
			            inner join cs2.cadastro c on b.id_cadastro =  c.codloja
			            WHERE $selecao 
			                a.data_hora between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
			                AND
			                    a.tipo_nota = 'NFC'
			                AND 
			                    a.status = 5
			            GROUP BY month(a.data_hora) , year(a.data_hora)
			            ORDER BY a.data_hora";
			$result = mysql_query($strSQL) or die($strSQL);

			$rows = array();
			while($r = mysql_fetch_assoc($result)) {
				$rows[] = $r;
			}

			echo json_encode($rows);
			mysql_close($link);
			break;
		
		default:
			# code...
			break;
	}
}