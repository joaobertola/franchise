<?php

require "connect/conexao_conecta.php";

@$action = $_REQUEST['action'];
@$valor = $_REQUEST['valor_1'];
@$valor2 = $_REQUEST['valor_2'];

if ($action == 'franquia') {
    $sql = "SELECT id, fantasia from cs2.franquia
            WHERE id = {$valor}";
    $qry = mysql_query($sql, $con);
    //echo "<select name='franquia' id='franquia'>";
    $res = mysql_fetch_array($qry);
    echo '<input style="width:100%"type="text" disabled name="franquia" id="franquia" value="' . $res['fantasia'] . '" />';
    //echo "</select>";
} else if ($action == 'funcionario') {

    $sql = "SELECT id, nome from cs2.funcionario
            WHERE id_empregador IN ('1','2')
            ORDER BY nome";
    $qry = mysql_query($sql, $con);
    echo "<SELECT name='funcionario' id='funcionario'>";

    if ($valor2 == '1') {
        if (mysql_num_rows($qry) > 0) {
            while ($res = mysql_fetch_array($qry)) {
                $id = trim($res['id']);
                $nome = trim($res['nome']);
                if ($id == $valor)
                    echo "<option value='$id' selected >$nome</option>";
                else
                    echo "<option value='$id'>$nome</option>";
            }
        }
    }else {
        // OUTRAS FRANQUIAS
        echo "<option value='0'>FUNCIONARIO FRANQUIA</option>";
    }
    echo "</select>";
} else if ($action == 'equipamento') {

    $sql = "SELECT codigo, descricao from cs2.produto WHERE codigo = '$valor'";
    $qry = mysql_query($sql, $con);
    $res = mysql_fetch_array($qry);
    echo '<input style="width:100%"type="text" disabled name="franquia" id="equipamento" value="' . $res['descricao'] . '" />';
} else if ($action == 'pagamentos') {

    $sql = "SELECT 
                id, numero_parcela, qtd_parcelas, date_format(data_vencimento,'%d/%m/%Y') as vencimento, 
                valor_parcela, 
                valor_pagamento, date_format(data_pagamento,'%d/%m/%Y') as data_pagamento
            FROM cs2.cadastro_emprestimo_franquia 
            WHERE protocolo = '$valor' ";
    $qry = mysql_query($sql, $con);
    if (mysql_num_rows($qry) > 0) {
        $saida = '';
        while ($res = mysql_fetch_array($qry)) {
            $id_pagamento = trim($res['id']);
            $numero_parcela = trim($res['numero_parcela']);
            $qtd_parcelas = trim($res['qtd_parcelas']);
            $vencimento = trim($res['vencimento']);
            $valor_parcela = trim($res['valor_parcela']);
            $data_pagamento = trim($res['data_pagamento']);
            $valor_pagamento = trim($res['valor_pagamento']);

            $saida .= "<tr data-id-pagamento='{$id_pagamento}' style='cursor: pointer'>
                          <td>$vencimento</td>
                          <td>$valor_parcela</td>
                          <td>$data_pagamento</td>
                          <td>$valor_pagamento</td>
                          <td>
                             <a onclick='edit_parcela(\"$id_pagamento\",\"$vencimento\",\"$valor_parcela\")'><img src='../img/img_v.gif'></a>
                             &nbsp;&nbsp;
                             <a onclick='remove_parela(\"$id_pagamento\")'><img src='../img/delete.png'></a>
                             &nbsp;&nbsp; 
                             <a onclick='exibe_parcela(\"$id_pagamento\",\"$vencimento\",\"$valor_parcela\")'><img src='../img/clipboard.gif'></a>
                             &nbsp;&nbsp; 
                          </td>
                       </tr>";
        }
        echo $saida;
    }
}
?>





