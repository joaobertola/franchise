<?php

require "connect/conexao_conecta.php";

$id_franquia = $_REQUEST['franqueado'];
$qtd_parcelas = $_REQUEST['qtd_parcela'];
$numero_serie = $_REQUEST['numero_serie'];
$id_funcionario = $_REQUEST['id_funcionario'];
$codigo_barras = $_REQUEST['iptCodigoBarra'];
$valor_produto = $_REQUEST['valor_produto2'];
//$data_venda = $_REQUEST['data_venda'];
$qtd = $_REQUEST['qtd'];

$validarNumeroSerie = false;
if ($qtd == '') {
    $qtd = 1;
    $validarNumeroSerie = true;
}


$data_venda = substr($data_venda, 6, 4) . '-' . substr($data_venda, 3, 2) . '-' . substr($data_venda, 0, 2);
//echo '<pre>';
//var_dump($_REQUEST);
//die;

if ($id_franquia == 1)
    $valor_parcela = $valor_produto;
else
    $valor_parcela = $valor_produto / $qtd_parcelas;

// PARA CONSIGNACAO
$consignacao = 'N';
if ($qtd_parcelas == 99) {
    $qtd_parcelas = 1;
    $vencimentos[0] = $_REQUEST['data_venda'];
    $consignacao = 'S';
} else
    $vencimentos = $_REQUEST['vencimento'];
$numero_serie = strtoupper($numero_serie);

try {
    $qtdConsig = 0;

    if ($consignacao == 'S' && $validarNumeroSerie == true) {

        $sqlConsig = "SELECT
                        COUNT(*) AS qtd,
                        f.nome,
                        fed.numero_serie,
                        fed.codigo_barra,
                        fe.consignacao
                    FROM cs2.franquia_equipamento fe
                    INNER JOIN cs2.franquia_equipamento_descricao fed
                    ON fe.id = fed.id_franquia_equipamento
                    INNER JOIN cs2.funcionario f
                    ON f.id = fe.id_funcionario
                    WHERE fed.numero_serie = '$numero_serie'
                    AND fe.consignacao = 'S'
            ";
        $resConsig = mysql_query($sqlConsig, $con);

        $qtdConsig = mysql_result($resConsig, 0, 'qtd');
        $nome = mysql_result($resConsig, 0, 'nome');


//            echo $qtdConsig;
//            die;

    }

    if ($qtdConsig > 0) {

        echo "<script language='javascript'>
            alert('Já Existe uma consignação com esse Nº de Série para: " . $nome . "');
            window.location.href = '../php/painel.php?pagina1=area_restrita/d_equipamentos.php';
        </script>";

    } else {

        if ($numero_serie == '' || empty($numero_serie)) {
            $sql = "SELECT fed.id, fe.id AS id_venda FROM cs2.franquia_equipamento_descricao fed INNER JOIN cs2.franquia_equipamento fe ON fe.id = fed.id_franquia_equipamento WHERE  id_funcionario = '$id_funcionario' AND codigo_barra = '$codigo_barras' AND fe.consignacao = 'S'";
            $resAux = mysql_query($sql, $con);

            @$id = mysql_result($resAux, 0, 'id');
            @$id_venda = mysql_result($resAux, 0, 'id_venda');

            if ($id) {
                $sqlAux2 = "UPDATE cs2.franquia_equipamento_descricao SET qtd = qtd + $qtd WHERE id = '$id'";
                $resAux2 = mysql_query($sqlAux2, $con);

                $sqlAux3 = "UPDATE cs2.franquia_equipamento_descricao SET saldo = saldo + $qtd WHERE id = '$id'";
                $resAux3 = mysql_query($sqlAux3, $con);

                $sqlAux4 = "INSERT INTO cs2.franquia_equipamento_descricao_log(id_franquia_equipamento, qtd, data_hora)
                VALUES('$id', '$qtd', NOW())";
                $resAux4 = mysql_query($sqlAux4, $con);

            } else {
                $sql = "INSERT INTO cs2.franquia_equipamento(id_franquia, data, id_funcionario, consignacao )
                   VALUES('$id_franquia', NOW(), '$id_funcionario', '$consignacao')";
                $res = mysql_query($sql, $con);
                $id_venda = mysql_insert_id($con);

                // Gravando o item comprado
                $sql = "INSERT INTO cs2.franquia_equipamento_descricao
                        ( id_franquia_equipamento, qtd, codigo_barra, numero_serie, valor_unitario, saldo )
                    VALUES
                        ( '$id_venda' , '$qtd' , '$codigo_barras' , '$numero_serie', '$valor_produto', '$qtd')";

            }
            
        } else {

            $sql = "INSERT INTO cs2.franquia_equipamento(id_franquia, data, id_funcionario, consignacao )
                   VALUES('$id_franquia', NOW(), '$id_funcionario', '$consignacao')";
            $res = mysql_query($sql, $con);
            $id_venda = mysql_insert_id($con);

            // Gravando o item comprado
            $sql = "INSERT INTO cs2.franquia_equipamento_descricao
                        ( id_franquia_equipamento, qtd, codigo_barra, numero_serie, valor_unitario, saldo )
                    VALUES
                        ( '$id_venda' , '$qtd' , '$codigo_barras' , '$numero_serie', '$valor_produto', '$qtd')";
        }
        $res = mysql_query($sql, $con) or die("ERRO 001 : $sql");
        
        // Buscando	o que comprou
        $sql = "SELECT b.qtd, c.descricao, b.valor_unitario, b.numero_serie, b.qtd FROM cs2.franquia_equipamento a
                    INNER JOIN cs2.franquia_equipamento_descricao b ON a.id = b.id_franquia_equipamento
                    INNER JOIN base_web_control.produto c ON b.codigo_barra = c.codigo_barra and c.id_cadastro=62735
                    WHERE a.id = $id_venda";
        
        //echo "$sql";die;
        
        $res = mysql_query($sql, $con);
        $descricao_deposito = 'Aquisi&ccedil;&atilde;o de Equipamentos:<br>';
        while ($reg = mysql_fetch_array($res)) {
            $qtd = $reg['qtd'];
            $desc = $reg['descricao'];
            $numero_serie = $reg['numero_serie'];
            $vlr = $reg['valor_unitario'];
            $vlr = number_format($vlr, 2, ',', '.');
            $descricao_deposito .= "$qtd - $desc - Valor Unit&aacute;rio: R$ $vlr <br>N&uacute;mero S&eacute;rie: $numero_serie<br>";
        }
        $j = 0;

        for ($i = 0; $i < $qtd_parcelas; $i++) {

            $j++;
            // Gravando os itens comprados
            $nparc = str_pad($j, 3, 0, STR_PAD_LEFT);
            $vencimento = $vencimentos[$i];
            $vencimento = substr($vencimento, 6, 4) . "-" . substr($vencimento, 3, 2) . "-" . substr($vencimento, 0, 2);

            if ($id_franquia != 1) {
                $sql = "
                            INSERT INTO cs2.cadastro_emprestimo_franquia
                                    (
                                            id_franquia, data_solicitacao, hora_solicitacao, qtd_parcelas, numero_parcela,
                                            data_vencimento, vr_emprestimo_solicitado, valor_parcela, protocolo,
                                            depositado_cta_cliente, descricao_deposito, data_deposito
                                    )
                            VALUES
                                    (
                                            '$id_franquia' , NOW() , NOW() , '$qtd_parcelas', '$nparc',
                                            '$vencimento', $valor_produto, $valor_parcela, '$id_venda',
                                            'S', '$descricao_deposito', NOW()
                                    )
                    ";
                $res = mysql_query($sql, $con);
            } else {
                // se for franquia curitiba nao grava a venda
            }
        }

        echo "<script>alert('Registro gravado com sucesso.');</script>";
        echo "<script>window.open('area_restrita/d_imprimir_consignacao.php?id=" . $id_venda . "')</script>";

    }
} catch (SoapFault $e) {
    //return $e->getMessage();
    echo "<script>alert('Houve um erro ao gravar os recebimentos. Favor verificar no Extrato do Franqueado')</script>";
}

?>
<script language="javascript">
    window.location.href = "../php/painel.php?pagina1=area_restrita/d_equipamentos.php";
</script>