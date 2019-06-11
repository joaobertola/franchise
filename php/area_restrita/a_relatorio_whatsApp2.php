<?php

require "../connect/conexao_conecta.php";

$campanha = $_REQUEST['id_campanha'];

$sql = "SELECT (SELECT COUNT(*) FROM cs2.campanha_whatsApp_retorno
                WHERE id_campanha = $campanha) as total,
                    count(*) AS qtd,
                    CASE retorno
                        WHEN 1 THEN 'Ainda não verificado'
                        WHEN 2 THEN 'Aguardando envio da mensagem'
                        WHEN 3 THEN 'Enviando mensagem'
                        WHEN 4 THEN 'Mensagem Enviada'
                        WHEN 5 THEN 'Mensagem Entregue'
                        WHEN 6 THEN 'Número telefone Inválido'
                        WHEN 7 THEN 'Telefone Não possui WhatsApp'
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
