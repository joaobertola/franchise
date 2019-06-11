<?php

require "../connect/conexao_conecta.php";

$campanha = $_REQUEST['id_campanha'];

$sql = "SELECT (SELECT COUNT(*) FROM cs2.campanha_whatsApp_retorno
                WHERE id_campanha = $campanha) as total,
                    count(*) AS qtd,
                    CASE retorno
                        WHEN 1 THEN 'Ainda não verificado'
                        WHEN 2 THEN 'Aguardando envio das mensagens'
                        WHEN 3 THEN 'Enviando mensagens'
                        WHEN 4 THEN 'Mensagens Enviadas'
                        WHEN 5 THEN 'Mensagens Entregues e NÃO LIDAS'
                        WHEN 6 THEN 'Número Telefone Inválido'
                        WHEN 7 THEN 'Telefone Não possui WhatsApp'
                        WHEN 8 THEN 'Mensagens Entregues e LIDAS'
                    END
                    AS retorno
                    FROM cs2.campanha_whatsApp_retorno
        WHERE id_campanha = $campanha
        GROUP BY retorno";
$qry = mysql_query( $sql, $con) or die('Erro comando SQL :'.$sql);
$html = '<table style="width:100%">
  <tr>
    <th>Quantidade</th>
    <th>Retorno</th> 
    <th>%</th> 
  </tr>';
while ($row = mysql_fetch_array($qry)) {
    $html .= "  <tr>
                <td>".$row['qtd']."</td>
                <td>".$row['retorno']."</td>
                <td>". number_format(($row['qtd'] / $row['total'] * 100 ),2)."</td>
                </tr>
            ";
//    print_r($row);
}

$html .= '</table>';
//echo $html;
//die();
echo json_encode($html);
