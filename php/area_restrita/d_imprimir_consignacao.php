<?php
/**
 * @file d_imprimir_consignacao.php
 * @brief Arquivo responsável pela impressão de consignação
 * @author ARLLON DIAS
 * @date 09/01/2017
 * @version 1.0
 **/

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$id = $_REQUEST['id'];
$numeroSerie = $_REQUEST['iptNumeroSerie'];

if($numeroSerie != ''){
    $sql = "SELECT
            fe.id AS id_consignacao,
            f.id AS id_funcionario,
            IF(f.nome = '' OR f.nome IS NULL, frq.fantasia, f.nome) AS nome_funcionario,
            f.funcao AS funcao_funcionario,
            f.veiculo AS veiculo_funcionario,
            f.placa AS placa_funcionario,
            DATE_FORMAT(fe.data, '%d/%m/%Y') AS data_consignacao,
            fed.codigo_barra,
            p.descricao,
            IF(fed.numero_serie = '' || numero_serie IS NULL, fed.saldo, fed.numero_serie) AS numero_serie,
            f.rg AS rg_funcionario,
            f.cpf AS cpf_funcionario
        FROM cs2.franquia_equipamento fe
        LEFT JOIN cs2.franquia_equipamento_descricao fed
        ON fed.id_franquia_equipamento = fe.id
        LEFT JOIN cs2.funcionario f
        ON f.id = fe.id_funcionario
        LEFT JOIN base_web_control.produto p
        ON p.id_cadastro = 62735
        AND p.codigo_barra = fed.codigo_barra
        INNER JOIN cs2.franquia frq
        ON frq.id = fe.id_franquia
        WHERE fed.numero_serie = '$numeroSerie'";
}else{
    $sql = "SELECT
            fe.id AS id_consignacao,
            f.id AS id_funcionario,
            IF(f.nome = '' OR f.nome IS NULL, frq.fantasia, f.nome) AS nome_funcionario,
            f.funcao AS funcao_funcionario,
            f.veiculo AS veiculo_funcionario,
            f.placa AS placa_funcionario,
            DATE_FORMAT(fe.data, '%d/%m/%Y') AS data_consignacao,
            fed.codigo_barra,
            p.descricao,
            IF(fed.numero_serie = '' || numero_serie IS NULL, fed.saldo, fed.numero_serie) AS numero_serie,
            f.rg AS rg_funcionario,
            f.cpf AS cpf_funcionario
        FROM cs2.franquia_equipamento fe
        LEFT JOIN cs2.franquia_equipamento_descricao fed
        ON fed.id_franquia_equipamento = fe.id
        LEFT JOIN cs2.funcionario f
        ON f.id = fe.id_funcionario
        LEFT JOIN base_web_control.produto p
        ON p.id_cadastro = 62735
        INNER JOIN cs2.franquia frq
        ON frq.id = fe.id_franquia
        AND p.codigo_barra = fed.codigo_barra
        WHERE fe.id = '$id'";
}
//echo '<pre>';
//echo $sql;
$qry = mysql_query($sql, $con);
$aConsignacao = mysql_fetch_assoc($qry);
//echo '<pre>';
//var_dump($aConsignacao);
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
$html .= '<div id="docPrint" style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px !important; font-weight: bold !important; word-wrap: break-word !important;">';
$html .= '<div align="center"><img src="https://webcontrolempresas.com.br/webcontrol/classes/barcode/codigo_barra.php?c='.$aConsignacao['id_consignacao'].'" width="80" height="30"></div><br>';
$html .= '<div align="center">CONSIGNAÇÃO DE EQUIPAMENTOS/PRODUTOS</div><br>';
$html .= $separator;
$html .= 'ID CONSIGNAÇÃO: '.$aConsignacao['id_consignacao'].'<br>';
$html .= $aConsignacao['id_funcionario'].' - '.$aConsignacao['nome_funcionario'].'<br>';
$html .= 'FUNÇÃO - '.$aConsignacao['funcao_funcionario'] .'<br>';
$html .= 'VEÍCULO - '.$aConsignacao['veiculo_funcionario'] .'<br>';
$html .= 'PLACA - '.$aConsignacao['placa_funcionario'] .'<br>';
$html .= $separator;
$html .= '<table style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px !important; font-weight: bold !important; word-wrap: break-word !important;">';
$html .= '<tr>';
$html .= '<td>Data/Hora Saída</td>';
$html .= '<td>Equip/Prod.</td>';
$html .= '<td>Série / Saldo</td>';
$html .= '<td>Sim</td>';
$html .= '<td>Não</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td>'.$aConsignacao['data_consignacao'].'</td>';
$html .= '<td>'.$aConsignacao['descricao'].'</td>';
$html .= '<td>'.strtoupper($aConsignacao['numero_serie']).'</td>';
$html .= '<td><img src="../clientes/tabelaimgs/bola_nao.png" height=12></td>';
$html .= '<td><img src="../clientes/tabelaimgs/bola_nao.png" height=12></td>';
$html .= '</tr>';
$html .= '</table>';
$html .= $separator;
$html .= '<br>OBS:__________________________________________________<br><br><br>';
$html .= $separator;
$html .= '<div align="center">TERMO DE RESPONSABILIDADE</div><br>';
$html .= $separator;
$html .= '1 - Assumo a inteira responsabilidade civil e criminal pelo Equipamento acima descriminado, fornecido pela empresa WEB CONTROL EMPRESAS. Caso não houver a devolução,
fica autorizado desde já a tomada das providências cabíveis e também as tomadas judiciais.<br>';
$html .= $separator;
$html .= '2 - Os equipamentos serão de “total responsabilidade” do consultor em caso de furto, roubo, extravio ou mal conservação, devendo ser ressarcidos os prejuízos
ocorridos no período de seu domínio.<br>';
$html .= $separator;
$html .= '3 - O Consultor deverá apresentar o equipamento semanalmente (7 em 7 dias) na sede da empresa, o qual passara por vistoria do seu estado de conservação.
Caso não compareça neste prazo os equipamentos serão recolhidos imediatamente.<br>';
$html .= $separator;
$html .= '4 - Caso haja a venda dos Equipamentos de demonstração, o consultor deverá apresentar imediatamente o Pedido de Equipamentos e Suprimentos devidamente preenchidos e
anexados com os devidos valores. Após realizado este procedimento, o equipamento vendido será substituído por outro imediatamente para realizar as demonstrações aos clientes.<br>';
$html .= $separator;
$html .= '<br><br><br>';
$html .= '_______________________________<br>';
$html .= $aConsignacao['nome_funcionario']. '<br>';
$html .= 'RG: ' . $aConsignacao['rg_funcionario']. '| CPF: '. $aConsignacao['cpf_funcionario']. '<br>';
$html .= $separatorCut .'<br>';
$html .= '<div align="center"><img src="https://webcontrolempresas.com.br/webcontrol/classes/barcode/codigo_barra.php?c='.$aConsignacao['id_consignacao'].'" width="80" height="30"></div><br>';
$html .= '<div align="center">CONSIGNAÇÃO DE EQUIPAMENTOS/PRODUTOS</div><br>';
$html .= $separator;
$html .= 'ID CONSIGNAÇÃO: '.$aConsignacao['id_consignacao'].'<br>';
$html .= $aConsignacao['id_funcionario'].' - '.$aConsignacao['nome_funcionario'].'<br>';
$html .= 'FUNÇÃO - '.$aConsignacao['funcao_funcionario'] .'<br>';
$html .= 'VEÍCULO - '.$aConsignacao['veiculo_funcionario'] .'<br>';
$html .= 'PLACA - '.$aConsignacao['placa_funcionario'] .'<br>';
$html .= $separator;
$html .= '<table style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px !important; font-weight: bold !important; word-wrap: break-word !important;">';
$html .= '<tr>';
$html .= '<td>Data/Hora Saída</td>';
$html .= '<td>Equip/Prod.</td>';
$html .= '<td>Série / Saldo</td>';
$html .= '<td>Sim</td>';
$html .= '<td>Não</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td>'.$aConsignacao['data_consignacao'].'</td>';
$html .= '<td>'.$aConsignacao['descricao'].'</td>';
$html .= '<td>'.strtoupper($aConsignacao['numero_serie']).'</td>';
$html .= '<td><img src="../clientes/tabelaimgs/bola_nao.png" height=12></td>';
$html .= '<td><img src="../clientes/tabelaimgs/bola_nao.png" height=12></td>';
$html .= '</tr>';
$html .= '</table>';
$html .= $separator;
$html .= '<br>OBS:__________________________________________________<br><br><br>';
$html .= $separator;
$html .= '<div align="center">TERMO DE RESPONSABILIDADE</div><br>';
$html .= $separator;
$html .= '1 - Assumo a inteira responsabilidade civil e criminal pelo Equipamento acima descriminado, fornecido pela empresa WEB CONTROL EMPRESAS. Caso não houver a devolução,
fica autorizado desde já a tomada das providências cabíveis e também as tomadas judiciais.<br>';
$html .= $separator;
$html .= '2 - Os equipamentos serão de “total responsabilidade” do consultor em caso de furto, roubo, extravio ou mal conservação, devendo ser ressarcidos os prejuízos
ocorridos no período de seu domínio.<br>';
$html .= $separator;
$html .= '3 - O Consultor deverá apresentar o equipamento semanalmente (7 em 7 dias) na sede da empresa, o qual passara por vistoria do seu estado de conservação.
Caso não compareça neste prazo os equipamentos serão recolhidos imediatamente.<br>';
$html .= $separator;
$html .= '4 - Caso haja a venda dos Equipamentos de demonstração, o consultor deverá apresentar imediatamente o Pedido de Equipamentos e Suprimentos devidamente preenchidos e
anexados com os devidos valores. Após realizado este procedimento, o equipamento vendido será substituído por outro imediatamente para realizar as demonstrações aos clientes.<br>';
$html .= $separator;
$html .= '<br><br><br>';
$html .= '_______________________________<br>';
$html .= $aConsignacao['nome_funcionario']. '<br>';
$html .= 'RG: ' . $aConsignacao['rg_funcionario']. '| CPF: '. $aConsignacao['cpf_funcionario'];
$html .= '</div>';


$html.= '<script type="text/javascript">
    setTimeout(function(){
    this.print();
    window.history.go(-2);
    },1000);</script>';
echo $html;