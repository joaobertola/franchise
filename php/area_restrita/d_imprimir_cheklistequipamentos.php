<?php

/**
 * @file d_imprimir_cheklistequipamentos.php
 * @brief Arquivo responsável pela impressão do checklist na impressora térmica Bematech TH4200
 * @author ARLLON DIAS
 * @date 09/01/2017
 * @version 1.0
 * */
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$id_funcionario = $_REQUEST['id_funcionario'];
$codigo_barra = $_REQUEST['codigo_barra'];

$sql = "SELECT
            fe.id AS id_consignacao,
            f.id AS id_funcionario,
            f.nome AS nome_funcionario,
            f.funcao AS funcao_funcionario,
            f.veiculo AS veiculo_funcionario,
            f.placa AS placa_funcionario,
            DATE_FORMAT(fe.data, '%d/%m/%Y') AS data_consignacao,
            fed.codigo_barra,
            p.descricao,
            IF(fed.numero_serie IS NULL OR fed.numero_serie = '', fed.saldo, fed.numero_serie) AS numero_serie,
            f.rg AS rg_funcionario,
            f.cpf AS cpf_funcionario,
            fed.id AS id_consignacao_prod
        FROM cs2.franquia_equipamento fe
        LEFT JOIN cs2.franquia_equipamento_descricao fed
        ON fed.id_franquia_equipamento = fe.id
        LEFT JOIN cs2.funcionario f
        ON f.id = fe.id_funcionario
        LEFT JOIN base_web_control.produto p
        ON p.id_cadastro = 62735
        AND p.codigo_barra = fed.codigo_barra
        WHERE fe.consignacao = 'S'
        AND IF('$id_funcionario' = 0, 0=0, fe.id_funcionario = '$id_funcionario')
        AND IF('$codigo_barra' = 0, 0=0, fed.codigo_barra = '$codigo_barra')
        AND fed.codigo_barra != ''
        ORDER BY f.nome ASC, p.descricao ASC, fe.data DESC;
        ";
$qry = mysql_query($sql, $con);
//echo '<pre>';
//var_dump($sql);
//die;


$separator = '-------------------------------------------------------<br>';
$separatorCut = '----------------------------------------------------<img src="../clientes/tabelaimgs/1484001935_cut.png" height=12>';

$html = '<style>
@page { margin: 0.0in 0.0in 0.0in 0.0in;}
body {
    display: block;
    margin: 0px;
    margin-top: 25px;
    margin-right: 0.5px;
    margin-bottom: 3px;
    margin-left: 0.5px;
    font-weight: bold;
    }
</style>';

$i = 0;
$strNome = '';
$totalLinhas = mysql_num_rows($qry);

while ($arrRetorno = mysql_fetch_array($qry)) {

    if ($i != 0 && $strNome != $arrRetorno['nome_funcionario']) {
        $html .= '</table>';
        $html .= '<br><br><br><br>';
        $html .= 'Os Equipamentos/Produtos encontram-se na posse do Funcionário acima.<br><br><br>';
        $html .= '<br><br><br>';
        $html .= '________________________&nbsp;&nbsp;&nbsp;&nbsp;________________________<br>';
        $html .= 'Assinatura do Conferente&nbsp;&nbsp;&nbsp;&nbsp;Funcionário<br>';
        $html .= date('d/m/Y');
        $html .= '<br><br><br>';
        if ($id_funcionario == 0) {
            $html .= $separatorCut;
        }
    }

    $id_consignacao = $arrRetorno['id_consignacao_prod'];

//    echo '<pre>';
//    var_dump($id_consignacao);
//    die;

    $sqlLog = "SELECT
                     DATE_FORMAT(data_hora, '%d/%m/%Y') AS data_hora,
                     qtd,
                     if(tipo = 'C', 'Consignação', 'Venda') as tipo
               FROM cs2.franquia_equipamento_descricao_log
               WHERE id_franquia_equipamento = '$id_consignacao'";

    $resLog = mysql_query($sqlLog, $con);

    if ($i == 0 || $strNome != $arrRetorno['nome_funcionario']) {
        $html .= '<div id="docPrint" style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px !important; font-weight: bold !important; word-wrap: break-word !important;">';
        $html .= '<div align="center">CHECK LIST EQUIPAMENTOS/PRODUTOS</div><br>';
        $html .= $separator;
        $html .= $arrRetorno['id_funcionario'] . ' - ' . $arrRetorno['nome_funcionario'] . '<br>';
        $html .= 'FUNÇÃO - ' . $arrRetorno['funcao_funcionario'] . '<br>';
        $html .= 'VEICULO - ' . $arrRetorno['veiculo_funcionario'] . '<br>';
        $html .= 'PLACA  - ' . $arrRetorno['placa_funcionario'] . '<br>';
        $html .= $separator;
        $html .= '<table style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px !important; font-weight: bold !important; word-wrap: break-word !important;">';
        $html .= '<tr>';
        $html .= '<td>Data</td>';
        $html .= '<td>Equip/Prod</td>';
        $html .= '<td>Série / Saldo</td>';
        $html .= '<td>Sim</td>';
        $html .= '<td>Não</td>';
        $html .= '</tr>';
    }


    $html .= '<tr>';
    $html .= '<td>' . $arrRetorno['data_consignacao'] . '</td>';
    $html .= '<td>' . $arrRetorno['descricao'] . '</td>';
    $html .= '<td>' . $arrRetorno['numero_serie'] . '</td>';
    $html .= '<td><img src="../clientes/tabelaimgs/bola_nao.png" height=12></td>';
    $html .= '<td><img src="../clientes/tabelaimgs/bola_nao.png" height=12></td>';
    $html .= '</tr>';

//    if (mysql_num_rows($resLog) > 0) {
//
//        $html .= '<tr><td colspan="4">Lançamentos</td></tr>';
//        while ($arrLog = mysql_fetch_array($resLog)) {
//
//            $html .= '<tr>';
//            $html .= '<td>';
//            $html .= $arrLog['data_hora'];
//            $html .= '</td>';
//            $html .= '<td>';
//            $html .= $arrLog['tipo'];
//            $html .= '</td>';
//            $html .= '<td>';
//            $html .= $arrLog['qtd'];
//            $html .= '</td>';
//            $html .= '<td></td>';
//            $html .= '<td></td>';
//            $html .= '</tr>';
//
//
//        }
//    }


    $strNome = $arrRetorno['nome_funcionario'];
    $i++;
}
$html .= '</table>';
$html .= '<br><br><br><br>';
$html .= 'Os Equipamentos/Produtos encontram-se na posse do Funcionário acima.<br><br><br>';
$html .= '<br><br><br>';
$html .= '________________________&nbsp;&nbsp;&nbsp;&nbsp;________________________<br>';
$html .= 'Assinatura do Conferente&nbsp;&nbsp;&nbsp;&nbsp;Funcionário<br>';
$html .= date('d/m/Y');
$html .= '<br><br><br>';

$html .= '<script type="text/javascript">
    setTimeout(function(){
    this.print();
    window.history.go(-2);
    },1000);</script>';
echo $html;




