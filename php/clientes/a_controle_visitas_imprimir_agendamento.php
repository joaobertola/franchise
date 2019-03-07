<?php

/**
 * Created by PhpStorm.
 * User: Siméia
 * Date: 05/08/2016
 * Time: 09:37
 */
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";
 

function datetoUS($dataBrasil) {
    $d = explode('/', $dataBrasil);
    $d[2] = empty($d[2]) ? '' : $d[2];
    $d[1] = empty($d[1]) ? '' : $d[1];
    $d[0] = empty($d[0]) ? '' : $d[0];
    $dtOK = $d[2] . '-' . $d[1] . '-' . $d[0];
    return $dtOK;
}

$idConsultor = isset($_POST['iptIdConsultor']) ? $_POST['iptIdConsultor'] : '';
$dataAgendamento = datetoUS(isset($_POST['iptDataAgendamento']) ? $_POST['iptDataAgendamento'] : '');
$separator = '--------------------------------------------------<br>';

$blnMostrarTudo = false;
$mostrar1Via = false;
$mostrar2Via = false;
$mostrar3Via = false;
if (isset($_POST['iptMostrarTudo'])) {
    $blnMostrarTudo = true;
} else {
    if ($_POST['iptViaUm'] == 'on') {
        $mostrar1Via = true;
    }
    if ($_POST['iptViaDois'] == 'on') {
        $mostrar2Via = true;
    }
    if ($_POST['iptViaTres'] == 'on') {
        $mostrar3Via = true;
    }
}

$sql = "SELECT
                cv.id,
                CONCAT(DATE_FORMAT(cv.data_agendamento, '%d/%m/%Y'), ' ', DATE_FORMAT(cv.hora_agendamento,'%H:%i')) AS data_hora_agendamento,
                cv.assitente_comercial,
                cv.empresa,
                cv.endereco,
                cv.bairro,
                cv.cidade,
                cv.ponto_referencia,
                cv.fone1,
                cv.fone2,
                cv.responsavel,
                c.nome,
                cv.observacao,
                a.nome AS assistente_comercial,
                cv.triplicar_vendas, 
                cv.cad_cliente, 
                cv.prod_estoque, 
                cv.boletos, 
                cv.nota_fiscal, 
                cv.site,
                cv.frente_caixa,
                cv.status_venda,
                cv.numero,
                cv.uf
            FROM cs2.controle_comercial_visitas cv
            INNER JOIN cs2.consultores_assistente c
            ON c.id = cv.id_consultor
            INNER JOIN cs2.consultores_assistente a
            ON a.id = cv.id_assistente
          WHERE cv.id_consultor = '" . $idConsultor . "'
          AND cv.data_agendamento = '" . $dataAgendamento . "'";


if (isset($_POST['iptNumeroAtendimento'])) {
    $sql = "SELECT
                cv.id,
                CONCAT(DATE_FORMAT(cv.data_agendamento, '%d/%m/%Y'), ' ', DATE_FORMAT(cv.hora_agendamento,'%H:%i')) AS data_hora_agendamento,
                cv.assitente_comercial,
                cv.empresa,
                cv.endereco,
                cv.bairro,
                cv.cidade,
                cv.ponto_referencia,
                cv.fone1,
                cv.fone2,
                cv.responsavel,
                c.nome,
                cv.observacao,
                a.nome AS assistente_comercial,
                cv.triplicar_vendas,
                cv.cad_cliente,
                cv.prod_estoque,
                cv.boletos,
                cv.nota_fiscal,
                cv.site,
                cv.frente_caixa,
                cv.status_venda,
                cv.numero,
                cv.uf
            FROM cs2.controle_comercial_visitas cv
            INNER JOIN cs2.consultores_assistente c
            ON c.id = cv.id_consultor
            INNER JOIN cs2.consultores_assistente a
            ON a.id = cv.id_assistente
          WHERE cv.id = '" . $_POST['iptNumeroAtendimento'] . "'";
}


$qry = mysql_query($sql, $con);
      
$html = '<style>
@page { margin: 0.0in 0.0in 0.0in 0.0in;}
body {
    display: block;
    margin: 0px;
    margin-top: 25px;
    margin-right: 0.5px;
    margin-bottom: 3px;
    margin-left: 0.5px;
    }
</style>';
$html .= '<div id="docPrint" style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px; font-weight: bold; word-wrap: break-word;">';
while ($arrItemResult = mysql_fetch_array($qry)) {

    $bola_nao = '<img src="tabelaimgs/bola_nao.png" height=12>';

    $triplicar_vendas = $arrItemResult['triplicar_vendas'];
    $status_venda = $arrItemResult['status_venda'];

    if ($status_venda == 'R')
        $Msg = '(REAGENDAMENTO) ';
    else
        $Msg = '';

    if ($triplicar_vendas == '1') {
        $t_sim = '<img src="tabelaimgs/bola_sim.png" height=12>';
        $t_nao = '<img src="tabelaimgs/bola_nao.png" height=12>';
    } else {
        $t_sim = '<img src="tabelaimgs/bola_nao.png" height=12>';
        $t_nao = '<img src="tabelaimgs/bola_sim.png" height=12>';
    }

    $cad_cliente = $arrItemResult['cad_cliente'];
    if ($cad_cliente == '1') {
        $c_sim = '<img src="tabelaimgs/bola_sim.png" height=12>';
        $c_nao = '<img src="tabelaimgs/bola_nao.png" height=12>';
    } else {
        $c_sim = '<img src="tabelaimgs/bola_nao.png" height=12>';
        $c_nao = '<img src="tabelaimgs/bola_sim.png" height=12>';
    }

    $prod_estoque = $arrItemResult['prod_estoque'];
    if ($prod_estoque == '1') {
        $p_sim = '<img src="tabelaimgs/bola_sim.png" height=12>';
        $p_nao = '<img src="tabelaimgs/bola_nao.png" height=12>';
    } else {
        $p_sim = '<img src="tabelaimgs/bola_nao.png" height=12>';
        $p_nao = '<img src="tabelaimgs/bola_sim.png" height=12>';
    }

    $boletos = $arrItemResult['boletos'];
    if ($boletos == '1') {
        $b_sim = '<img src="tabelaimgs/bola_sim.png" height=12>';
        $b_nao = '<img src="tabelaimgs/bola_nao.png" height=12>';
    } else {
        $b_sim = '<img src="tabelaimgs/bola_nao.png" height=12>';
        $b_nao = '<img src="tabelaimgs/bola_sim.png" height=12>';
    }

    $frente_caixa = $arrItemResult['frente_caixa'];
    if ($frente_caixa == '1') {
        $f_sim = '<img src="tabelaimgs/bola_sim.png" height=12>';
        $f_nao = '<img src="tabelaimgs/bola_nao.png" height=12>';
    } else {
        $f_sim = '<img src="tabelaimgs/bola_nao.png" height=12>';
        $f_nao = '<img src="tabelaimgs/bola_sim.png" height=12>';
    }

    $nota_fiscal = $arrItemResult['nota_fiscal'];
    if ($nota_fiscal == '1') {
        $n_sim = '<img src="tabelaimgs/bola_sim.png" height=12>';
        $n_nao = '<img src="tabelaimgs/bola_nao.png" height=12>';
    } else {
        $n_sim = '<img src="tabelaimgs/bola_nao.png" height=12>';
        $n_nao = '<img src="tabelaimgs/bola_sim.png" height=12>';
    }

    $site = $arrItemResult['site'];
    if ($site == '1') {
        $s_sim = '<img src="tabelaimgs/bola_sim.png" height=12>';
        $s_nao = '<img src="tabelaimgs/bola_nao.png" height=12>';
    } else {
        $s_sim = '<img src="tabelaimgs/bola_nao.png" height=12>';
        $s_nao = '<img src="tabelaimgs/bola_sim.png" height=12>';
    }

    if ($mostrar1Via || $blnMostrarTudo) {
        $html .= '<div align="center">1&ordf; VIA - ' . $Msg . 'Atendimento Comercial</div><br>';
        $html .= $arrItemResult['id'] . ' - ' . $arrItemResult['nome'] . '<br>';
        $html .= 'Agendador(a):' . $arrItemResult['assistente_comercial'] . '<br>';
        $html .= 'Data Agendamento: ' . $arrItemResult['data_hora_agendamento'] . ' <br>';
        $html .= 'Empresa: ' . $arrItemResult['empresa'] . ' <br>';
        $html .= trim($arrItemResult['endereco']);
        if (trim($arrItemResult['numero']))
            $html .= ', ' . trim($arrItemResult['numero']);

        if (trim($arrItemResult['bairro']))
            $html .= ', ' . trim($arrItemResult['bairro']);

        if (trim($arrItemResult['cidade']))
            $html .= ', ' . trim($arrItemResult['cidade']);

        if (trim($arrItemResult['ponto_referencia']))
            $html .= ', (' . trim($arrItemResult['ponto_referencia']) . ')';

        $html .= '<br>';
        $html .= $separator;
        $html .= 'Telefone1 : ' . $arrItemResult['fone1'] . '<br>';
        $html .= 'Telefone2 : ' . $arrItemResult['fone2'] . '<br>';
        $html .= 'Respons&aacute;vel: ' . $arrItemResult['responsavel'] . '<br>';
        $html .= $separator;
        $html .= ' 
        <div>
            <table style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px; font-weight: bold; word-wrap: break-word;">
                <tr>
                    <td>
                        Possui Algo Para:
                    </td>
                </tr>
                <tr>
                    <td width=160>Triplicar suas Vendas?</td>
                    <td>' . $t_sim . ' SIM &nbsp; ' . $t_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td width="160">Cadastro de Produto e Estoque?</td>
                    <td>' . $p_sim . ' SIM &nbsp; ' . $p_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td width="160">Emissão de Boletos ou Carnê Crediário?</td>
                    <td>' . $b_sim . ' SIM &nbsp; ' . $b_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td width="160">Frente de Caixa ?</td>
                    <td>' . $f_sim . ' SIM &nbsp; ' . $f_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td width="160">Emissão de Nota Fiscal?</td>
                    <td>' . $n_sim . ' SIM &nbsp; ' . $n_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td width="160">Site na Internet?</td>
                    <td>' . $s_sim . ' SIM &nbsp; ' . $s_nao . ' N&Atilde;O</td>
                </tr>
            </table>
        </div>';

        $html .= $separator;
        if ($arrItemResult['observacao'] != '' && $arrItemResult['observacao'] != null) {
            $html .= '<div style="word-wrap: break-word;">Observa&ccedil;&otilde;es:<br>' . $arrItemResult['observacao'] . '<br><br></div>';
        } else {
            $html .= '<div style="word-wrap: break-word;">Observa&ccedil;&otilde;es:<br><br><br><hr><br><br><hr><br><br>';
        }
        $html .= 'x ' . $separator . '<br>';
    }
    if ($mostrar2Via || $blnMostrarTudo) {
        $html .= '<div align="center">2&ordf; VIA - ' . $Msg . 'Supervisor Comercial</div><br>';
        $html .= $arrItemResult['id'] . ' - ' . $arrItemResult['nome'] . '<br>';
        $html .= 'Agendador(a):' . $arrItemResult['assistente_comercial'] . '<br>';
        $html .= 'Data Agendamento: ' . $arrItemResult['data_hora_agendamento'] . ' <br>';
        $html .= 'Empresa: ' . $arrItemResult['empresa'] . ' <br>';
        $html .= trim($arrItemResult['endereco']);
        if (trim($arrItemResult['numero']))
            $html .= ', ' . trim($arrItemResult['numero']);

        if (trim($arrItemResult['bairro']))
            $html .= ', ' . trim($arrItemResult['bairro']);

        if (trim($arrItemResult['cidade']))
            $html .= ', ' . trim($arrItemResult['cidade']);

        if (trim($arrItemResult['ponto_referencia']))
            $html .= ', (' . trim($arrItemResult['ponto_referencia']) . ')';

        $html .= '<br>';
        $html .= $separator;
        $html .= 'Respons&aacute;vel:' . $arrItemResult['responsavel'] . '<br>';
        $html .= $separator;
        $html .= ' 
        <div>
            <table style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px; font-weight: bold; word-wrap: break-word;">
                <tr>
                    <td>
                        Possui Algo Para:
                    </td>
                </tr>
                <tr>
                    <td width=160>Triplicar suas Vendas?</td>
                    <td>' . $t_sim . ' SIM &nbsp; ' . $t_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td width=160>Cadastro de Produto e Estoque?</td>
                    <td>' . $p_sim . ' SIM &nbsp; ' . $p_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td width=160>Emissão de Boletos ou Carnê Crediário?</td>
                    <td>' . $b_sim . ' SIM &nbsp; ' . $b_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td width=160>Frente de Caixa ?</td>
                    <td>' . $f_sim . ' SIM &nbsp; ' . $f_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td width=160>Emissão de Nota Fiscal?</td>
                    <td>' . $n_sim . ' SIM &nbsp; ' . $n_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td width=160>Site na Internet?</td>
                    <td>' . $s_sim . ' SIM &nbsp; ' . $s_nao . ' N&Atilde;O</td>
                </tr>
            </table>
        </div>';
        $html .= $separator;
        if ($arrItemResult['observacao'] != '' && $arrItemResult['observacao'] != null) {
            $html .= '<div style="word-wrap: break-word;">Observa&ccedil;&otilde;es:<br>' . $arrItemResult['observacao'] . '<br><br></div>';
        } else {
            $html .= '<div style="word-wrap: break-word;">Observa&ccedil;&otilde;es:<br><br><br><hr><br><br><hr><br><br>';
        }
        $html .= 'x ' . $separator . '<br>';
    }
    if ($mostrar3Via || $blnMostrarTudo) {
        $html .= '<div align="center">3&ordf; VIA - ' . $Msg . 'CHECKLIST Diretoria</div><br>';
        $html .= $arrItemResult['id'] . ' - ' . $arrItemResult['nome'] . '<br>';
        $html .= 'Agendador(a):' . $arrItemResult['assistente_comercial'] . '<br>';
        $html .= 'Data Agendamento: ' . $arrItemResult['data_hora_agendamento'] . ' <br>';
        $html .= 'Empresa: ' . $arrItemResult['empresa'] . ' <br>';
        $html .= trim($arrItemResult['endereco']);
        if (trim($arrItemResult['numero']))
            $html .= ', ' . trim($arrItemResult['numero']);

        if (trim($arrItemResult['bairro']))
            $html .= ', ' . trim($arrItemResult['bairro']);

        if (trim($arrItemResult['cidade']))
            $html .= ', ' . trim($arrItemResult['cidade']);

        if (trim($arrItemResult['ponto_referencia']))
            $html .= ', (' . trim($arrItemResult['ponto_referencia']) . ')';

        $html .= '<br>';
        $html .= $separator;
        $html .= 'Telefone1 : ' . $arrItemResult['fone1'] . '<br>';
        $html .= 'Telefone2 : ' . $arrItemResult['fone2'] . '<br>';
        $html .= 'Respons&aacute;vel:' . $arrItemResult['responsavel'] . '<br>';
        $html .= $separator;
        $html .= '<div>
            <table style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px; font-weight: bold; word-wrap: break-word;">
                <tr>
                    <td>Visita no hor&aacute;rio?</td>
                    <td>' . $bola_nao . ' SIM &nbsp; ' . $bola_nao . ' N&Atilde;O</td>
                </tr>
            </table>';
        $html .= $separator;
        $html .= 'Demonstra&ccedil;&atilde;o no SEU COMPUTADOR:<br>';
        $html .= $bola_nao . ' Cadastro de Clientes<br>';
        $html .= $bola_nao . ' Cadastro de Produtos/Estoque<br>';
        $html .= $bola_nao . ' Frente de Caixa<br>';
        $html .= $bola_nao . ' Emiss&atilde;o de boletos<br>';
        $html .= $bola_nao . ' Consultas de cr&eacute;dito<br>';
        $html .= $bola_nao . ' Parcelamento de d&eacute;bitos para devedores<br>';
        $html .= $bola_nao . ' Negativa&ccedil;&atilde;o devedores<br>';
        $html .= $bola_nao . ' Lista para Marketing<br>';
        $html .= $separator;

        $html .= '<div>
            <table style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px; font-weight: bold; word-wrap: break-word;">
             <tr>
                    <td>Paralelo entre Sistemas?</td>
                    <td>' . $bola_nao . ' SIM &nbsp; ' . $bola_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td>SUPER PASTA com equipamentos?</td>
                    <td>' . $bola_nao . ' SIM &nbsp; ' . $bola_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td>Consultor ligou para SUPERVISOR?</td>
                    <td>' . $bola_nao . ' SIM &nbsp; ' . $bola_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td>Cart&otilde;es de Visita</td>
                    <td>' . $bola_nao . ' SIM &nbsp; ' . $bola_nao . ' N&Atilde;O</td>
                </tr>
                <tr>
                    <td>Entregou Mouse Pad de Brinde?</td>
                    <td>' . $bola_nao . ' SIM &nbsp; ' . $bola_nao . ' N&Atilde;O</td>
                </tr>
            </table>';

        $html .= $separator;
        $html .= '<div style="word-wrap: break-word;">Observa&ccedil;&otilde;es Gerais:<br><br><br><hr><br><br><hr><br><br>';
        $html .= $separator;
        $html .= 'Observa&ccedil;&otilde;es Administrativas:<br><br>';
        $html .= 'Procedimentos Realizados:<br><br>';
        $html .= '(&nbsp;&nbsp;)SIM &nbsp;&nbsp; (&nbsp;&nbsp;)N&Atilde;O<br>';
        $html .= $separator . '<br>';
        $html .= 'Advert&ecirc;ncias Gerenciais:<br><br>';
        $html .= '(&nbsp;&nbsp;)SIM &nbsp;&nbsp; (&nbsp;&nbsp;)N&Atilde;O<br>';
        $html .= '<br><br>x ' . $separator . '<br><br>';
    }
}
$html .= '</div>';

$html .= '<script type="text/javascript">
    setTimeout(function(){
    this.print();
    window.history.go(-1);
    },1500);</script>';
echo $html;
?>