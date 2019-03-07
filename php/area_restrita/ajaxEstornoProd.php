<?php

require "../connect/conexao_conecta.php";


$id = $_POST['numSerie'];

$sqlConsig = "SELECT 
			  fed.id_franquia_equipamento,
			  fe.data,
			  f.nome,
			  pd.descricao,
			  pd.codigo_barra
			  FROM cs2.franquia_equipamento fe
				INNER JOIN cs2.franquia_equipamento_descricao fed ON fe.id = fed.id_franquia_equipamento
				INNER JOIN cs2.funcionario f ON f.id = fe.id_funcionario
				INNER JOIN base_web_control.produto pd ON fed.codigo_barra = pd.codigo_barra
			  WHERE fed.numero_serie = '$id'
			  AND pd.id_cadastro = 62735
			  AND fe.consignacao = 'S';";

$resConsig = mysql_query($sqlConsig, $con);

$row = mysql_fetch_assoc($resConsig);

// header('Content-type: application/json');
echo json_encode($row);