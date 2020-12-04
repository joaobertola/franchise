<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="https://www.webcontrolempresas.com.br/franquias/css/assets/js/bootstrap.min.js"></script>
<script src="https://www.webcontrolempresas.com.br/franquias/css/assets/js/mask.js"></script>
<script>
    $(document).ready(function() {
        $('#myModal2').on('show.bs.modal', function() {
            console.log('aqui');
            setTimeout(function() {
                $('.focus_input').focus();
            }, 1000);
        });
    });
    var post_dataI = '<?= $_REQUEST['dataI'] ?>';
    var post_dataF = '<?= $_REQUEST['dataF'] ?>';
    var post_equipamento = '<?= $_REQUEST['equipamento'] ?>';
    var post_numero_serie = '<?= $_REQUEST['numero_serie'] ?>';
    var post_franqueado = '<?= $_REQUEST['franqueado'] ?>';
    var post_id_funcionario = '<?= $_REQUEST['id_funcionario'] ?>';

    var idSaveDataPagamento;

    function MM_formtCep(e, src, mask) {
        if (window.event) {
            _TXT = e.keyCode;
        } else if (e.which) {
            _TXT = e.which;
        }
        if (_TXT > 47 && _TXT < 58) {
            var i = src.value.length;
            var saida = mask.substring(0, 1);
            var texto = mask.substring(i)
            if (texto.substring(0, 1) != saida) {
                src.value += texto.substring(0, 1);
            }
            return true;
        } else {
            if (_TXT != 8) {
                return false;
            } else {
                return true;
            }
        }
    }

    function voltar() {
        frm = document.tela_voltar;
        frm.action = 'painel.php?pagina1=area_restrita/d_equipamentos_relatorio.php';
        frm.submit();
    }

    function baixa_parcela(venc, vlr, idp) {
        $('#vencimento_parcela').val(venc);
        $('#valor_parcela').val(vlr);
        $('#id_pagamento').val(idp);
        $('#myModal2').modal('show');
        $('.focus_input').focus();
    }

    function VoltaCheque(venc, vlr, idp, dtconf) {
        $('#vencimento_parcela2').val(venc);
        $('#valor_parcela2').val(vlr);
        $('#id_pagamento2').val(idp);
        $('#data_confirmacao2').val(dtconf);
        $('#myModal3').modal('show');
    }

    function saveDataPagamento() {

        $.ajax({
            url: "area_restrita/d_equipamento_relatorio2_save_recebimento.php?update",
            type: "POST",
            data: {
                'id_pagamento': $('#id_pagamento').val(),
                'data_confirmacao': $('#data_confirmacao').val(),
                'cmd': 'ins'
            },
            success: function(data) {
                if (data == 1) {
                    location.reload();
                } else {
                    console.log(data);
                }
            }
        });
    }

    function confirmaDevolucao() {

        $.ajax({
            url: "area_restrita/d_equipamento_relatorio2_save_devolucao_cheque.php?update",
            type: "POST",
            data: {
                'id_pagamento': $('#id_pagamento2').val(),
                'cmd_devol': 'S',
            },
            success: function(data) {
                if (data == 1) {
                    location.reload();
                } else {
                    console.log(data);
                }
            }
        });
    }

    function DelDataPagamento(idp) {

        $.ajax({
            url: "area_restrita/d_equipamento_relatorio2_save_recebimento.php?update",
            type: "POST",
            data: {
                'id_pagamento': idp,
                'cmd': 'del'
            },
            success: function(data) {
                if (data == 1) {
                    location.reload();
                } else {
                    console.log(data);
                }
            }
        });
    }

    function DelChequeDevolvido(idp) {

        $.ajax({
            url: "area_restrita/d_equipamento_relatorio2_save_devolucao_cheque.php?update",
            type: "POST",
            data: {
                'id_pagamento': idp,
                'cmd_devol': 'N'
            },
            success: function(data) {
                if (data == 1) {
                    location.reload();
                } else {
                    console.log(data);
                }
            }
        });
    }
</script>
<link rel="stylesheet" href="https://www.webcontrolempresas.com.br/franquias/css/assets/css/font-awesome.min.css">
<link rel="stylesheet" href="https://www.webcontrolempresas.com.br/franquias/css/assets/css/bootstrap.min.css">

<style>
    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 4px;
    }

    .cropit-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-top: 7px;
        width: 294px;
        height: 212px;
    }

    .cropit-preview-image-container {
        cursor: move;
    }

    .image-size-label {
        margin-top: 10px;
    }

    input,
    .export {
        display: block;
    }

    button {
        margin-top: 10px;
    }

    .splash .controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom,
    .demos .demo-wrapper .controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        height: 5px;
        background: #eee;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        outline: none;
    }
</style>
<?php

function dados($tabela, $dados, $con)
{

    if ($tabela == 'franquia') {
        $sql = "SELECT fantasia AS dados FROM cs2.franquia WHERE id = '$dados'";
    } else if ($tabela == 'funcionario') {
        $sql = "SELECT nome AS dados FROM cs2.funcionario WHERE id = '$dados'";
    }
    $qry = mysql_query($sql, $con) or die("ERRO SQL: $sql");
    return mysql_result($qry, 0, 'dados');
}

$tp_rel = $_REQUEST['tp_rel'];
$cliente = $_REQUEST['cliente'];
$data2I = $_REQUEST['data2I'];
$data2I = substr($data2I, 6, 4) . '-' . substr($data2I, 3, 2) . '-' . substr($data2I, 0, 2);

$data2F = $_REQUEST['data2F'];
$data2F = substr($data2F, 6, 4) . '-' . substr($data2F, 3, 2) . '-' . substr($data2F, 0, 2);

$franqueado = $_REQUEST['franqueado'];
$id_funcionario = $_REQUEST['id_funcionario'];
$numero_serie = $_REQUEST['numero_serie'];
$equipamento = $_REQUEST['equipamento'];
$data4I = $_REQUEST['data4I'];
$data4F = $_REQUEST['data4F'];
$ativo = $_REQUEST['iptAtivo'];

switch ($tp_rel) {

    case '1':

        $sql = "SELECT a.nomefantasia, a.codloja FROM cs2.cadastro a
                INNER JOIN cs2.logon b ON a.codloja = b.codloja 
                WHERE CAST(MID(b.logon,1,6) AS UNSIGNED) = " . $_REQUEST['cliente'];
        $qry = mysql_query($sql, $con);
        $cliente = mysql_result($qry, 0, 'nomefantasia');
        $codigo = mysql_result($qry, 0, 'codloja');

        echo "<table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                 <tr style='background-color: #a1c2fa; text-align: center'>
                    <td colspan='8'>{$_REQUEST['cliente']} - $cliente</td>
                 </tr>
              </table>";

        $sql_lista = "SELECT id FROM cs2.cadastro_equipamento
                      WHERE codloja = '$codigo' and venda_finalizada = 'S'
                      ORDER by id";
        $resx = mysql_query($sql_lista, $con);

        while ($regx = mysql_fetch_array($resx)) {

            $id = $regx['id'];

            $sql_dados = "SELECT a.qtd, a.numero_serie, b.descricao, a.valor_unitario, c.vr_desconto,
                                DATE_FORMAT(c.data_compra,'%d/%m/%Y') as data, c.id, a.codigo_barra, ca.nome
                          FROM cs2.cadastro_equipamento_descricao a 
                          INNER JOIN base_web_control.produto b  ON b.id_cadastro = 62735 AND ( a.codigo_barra = b.codigo_barra OR a.codigo_barra = b.identificacao_interna)
                          INNER JOIN cs2.cadastro_equipamento c  ON c.id = a.id_cadastro_equipamento
                          LEFT JOIN cs2.funcionario ca           ON ca.id = c.id_consultor
                          WHERE c.id = $id
                          ORDER BY c.id";
            $res = mysql_query($sql_dados, $con);
?>

            <div class="modal fade" id="myModal2" tabindex="1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Recebimento de Parcela</h4>
                        </div>
                        <div class="container"></div>
                        <div class="modal-body">
                            <table class="table">
                                <tr>
                                    <td style="border-top:0;">Data vencimento:</td>
                                    <td style="border-top:0;">
                                        <input type="hidden" class="mask-data" name="id_pagamento" id='id_pagamento' value=''>
                                        <input type="text" class="mask-data" name="vencimento_parcela" id='vencimento_parcela' value='' disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:0;">Data Valor:</td>
                                    <td style="border-top:0;">
                                        <input type="text" class="mask-dinheiro" name="valor_parcela" id='valor_parcela' value='' disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:0;">Data de Confirmação:</td>
                                    <td style="border-top:0;" id="focus_i">
                                        <input type="text" name="data_confirmacao" class="focus_input" id="data_confirmacao" value="<?php echo date('d/m/Y'); ?>" onKeyPress="return MM_formtCep(event,this,'##/##/####');">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">

                            <button type="submit" onclick="saveDataPagamento()" class="btn btn-primary">Gravar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="myModal3" tabindex="1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Devolução de Cheque</h4>
                        </div>
                        <div class="container"></div>
                        <div class="modal-body">
                            <table class="table">
                                <tr>
                                    <td style="border-top:0;">Data vencimento:</td>
                                    <td style="border-top:0;">
                                        <input type="hidden" class="mask-data" name="id_pagamento2" id='id_pagamento2' value=''>
                                        <input type="text" class="mask-data" name="vencimento_parcela2" id='vencimento_parcela2' value='' disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:0;">Data Valor:</td>
                                    <td style="border-top:0;">
                                        <input type="text" class="mask-dinheiro" name="valor_parcela2" id='valor_parcela2' value='' disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:0;">Data de Confirmação:</td>
                                    <td style="border-top:0;">
                                        <input type="text" name="data_confirmacao2" id='data_confirmacao2' value='' disabled>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">

                            <button type="submit" onclick="confirmaDevolucao()" class="btn btn-primary">Confirmar devolução de Cheque</button>
                        </div>
                    </div>
                </div>
            </div>

            <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                <tr class="titulo">
                    <td colspan="8">Equipamentos Vendidos</td>
                </tr>
                <tr>
                    <td colspan='8' height='1' bgcolor='#999999'></td>
                </tr>
                <tr height='20' class='titulo'>
                    <td width='7%'>Data</td>
                    <td width='5%'>Qtde</td>
                    <td width='10%'>Cod. Barra</td>
                    <td width='30%'>Descri&ccedil;&atilde;o</td>
                    <td width='10%'>S&eacute;rie</td>
                    <td width='10%'>Vr. Unit&aacute;rio</td>
                    <?php if ($id_franquia == 163) { ?>
                        <td width='10%'>Consultor</td>
                    <?php } ?>
                </tr>
                <?php

                $saida = '';
                $soma_total = 0;
                $a = 0;
                $vr_recebido = 0;
                while ($reg = mysql_fetch_array($res)) {
                    $vr_recebido = 0;

                    $a++;

                    $id_venda = $reg['id'];
                    $cod_barra = $reg['codigo_barra'];
                    $qtd = $reg['qtd'];
                    $data = $reg['data'];
                    $descricao = $reg['descricao'];
                    $serie = $reg['numero_serie'];
                    $vr_unit = $reg['valor_unitario'];
                    $total = $qtd * $vr_unit;
                    $soma_total += $total;
                    $vr_unit = number_format($vr_unit, 2, ',', '.');

                    $vr_desconto = number_format($reg['vr_desconto'], 2, ',', '.');


                    echo "<tr height='22'";
                    if (($a % 2) == 0) {
                        echo "bgcolor='#E5E5E5'>";
                    } else {
                        echo ">";
                    }

                    $i++;
                    echo "    <td align='center'>$data</td>";
                    echo "    <td align='center'>$qtd</td>";
                    echo "    <td align='center'>$cod_barra</td>";
                    echo "    <td align='center'>$descricao</td>";
                    echo "    <td align='center'>$serie</td>";
                    echo "    <td align='center'>R$ $vr_unit</td>";

                    if ($id_franquia == 163) {
                        echo "<td align='center'>" . $reg['nome'] . "</td>";
                    }
                    echo "</tr>";
                }
                $vr_desconto = $soma_total - $vr_recebido;
                $vr_desconto2 = number_format($vr_desconto, 2, ',', '.');

                echo "<tr>";
                echo "    <td align='right' colspan='7'><b><font color='#0000FF'>TOTAL PAGO: R$ $vr_desconto2</b></font></td>";
                echo "</tr>";

                $total = number_format($soma_total - $vr_desconto, 2, ',', '.');

                $sql_pgto = "SELECT
                                id, valor, DATE_FORMAT(vencimento,'%d/%m/%Y') as venc,
                                CASE id_formapgto 
                                    WHEN '1' THEN 'DINHEIRO'
                                    WHEN '2' THEN 'CHEQUE'
                                    WHEN '3' THEN 'CARTAO DE CREDITO'
                                    WHEN '4' THEN 'BOLETO'
                                    WHEN '5' THEN 'CARTAO DE DEBITO'
                                    WHEN '6' THEN 'ANTECIPACAO'
                                    WHEN '7' THEN 'TRANSFERENCIA'
                                END as pgto,
                                devol_doc,
                                DATE_FORMAT(dt_conf_recebimento,'%d/%m/%Y') as dt_conf_recebimento
                             FROM cs2.cadastro_equipamento_pagamento WHERE id_venda = $id_venda";
                $res_pgto = mysql_query($sql_pgto, $con);
                $forma_pgto = '';
                $soma_produto = 0;
                $vr_recebido = 0;

                $html_recebido = "
                <tr>
                    <td colspan='7'>
                        <table width='70%' align='center' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                            <tr style='background-color: #a1c2fa; text-align: center'>
                                <td colspan='7'>Lan&ccedil;amentos Realizados</td>
                            </tr>
                            <tr class='titulo'>
                                <td>Vencimento</td>
                                <td>Valor</td>
                                <td>Meio Pagamento</td>
                                <td>Conf. Financeiro</td>
                                <td>Doc. Devolvido</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                           ";
                while ($reg_pg = mysql_fetch_array($res_pgto)) {

                    $j++;

                    $id = $reg_pg['id'];
                    $pgto = $reg_pg['pgto'];
                    $venc = $reg_pg['venc'];
                    $devol_doc = $reg_pg['devol_doc'];
                    $dt_conf_recebimento = $reg_pg['dt_conf_recebimento'];
                    $vlr = number_format($reg_pg['valor'], 2, ',', '.');
                    $vr_recebido += $reg_pg['valor'];
                    $forma_pgto .= $pgto . ' - ' . $venc . ' - R$ ' . $vlr . '<br>';

                    $vlr = number_format($reg_pg['valor'], 2, ',', '.');
                    if ($vrpg > 0)
                        $vrpg = number_format($reg_pg['valor_pagamento'], 2, ',', '.');

                    if (($j % 2) == 0) {
                        $html_recebido .= "<tr align='center' bgcolor='#E5E5E5'>";
                    } else {
                        $html_recebido .= "<tr align='center'>";
                    }

                    $img_volta = '';
                    $img_conf = '';
                    $devol_doc_msg = '';

                    if ($_SESSION['id'] == 163) {

                        if ($dt_conf_recebimento == '')
                            $img_conf = "<a href='#' onclick=\"baixa_parcela('$venc','$vlr','$id')\" title='Confirmar o recebimento da venda' onclick='return alerta()'><IMG SRC='../img/drop-add.gif' width='16' height='16' border='0'></a>";
                        else
                            $img_conf = "<a href='#' onclick=\"DelDataPagamento('$id')\" title='Confirmar o recebimento da venda'><IMG SRC='../img/minus.png' width='16' height='16' border='0'></a>";

                        if ($dt_conf_recebimento != '')
                            $img_volta = "<a href='#' onclick=\"VoltaCheque('$venc','$vlr','$id','$dt_conf_recebimento')\" title='Confirmar devolucao de cheque'><IMG SRC='../img/cheque.jpg' width='16' height='16' border='0'></a>";

                        if ($devol_doc == 'S') {
                            $devol_doc_msg = 'CHEQUE DEVOLVIDO';
                            $img_volta = "<a href='#' onclick=\"DelChequeDevolvido('$id')\" title='Cancelar cheque devolvido'><IMG SRC='../img/delete.png' width='16' height='16' border='0'></a>";
                        }
                    } else {

                        if ($dt_conf_recebimento == '')
                            $img_conf = "<IMG SRC='../img/drop-add.gif' width='16' height='16' border='0'>";
                        else
                            $img_conf = "<IMG SRC='../img/minus.png' width='16' height='16' border='0'>";

                        if ($dt_conf_recebimento != '')
                            $img_volta = "<IMG SRC='../img/cheque.jpg' width='16' height='16' border='0'>";

                        if ($devol_doc == 'S') {
                            $devol_doc_msg = 'CHEQUE DEVOLVIDO';
                            $img_volta = "<IMG SRC='../img/delete.png' width='16' height='16' border='0'>";
                        }
                    }
                    $html_recebido .= " <td>
                                                <input type='hidden' name='id_principal' id = 'id_principal' value='$id' />
                                                $venc
                                            </td>
                                            <td>$vlr</td>
                                            <td>$pgto</td>
                                            <td>$dt_conf_recebimento</td>
                                            <td>$devol_doc_msg</td>
                                            <td>$img_conf</td>
                                            <td>$img_volta</td>
                                        </tr>";
                }

                $html_recebido .= "
                      </table>
                    </td>
                </tr>";

                echo $html_recebido;
                ?>
            </table>
            <br>
            <hr><br>
        <?php
        }
        break;

    case '2':

        $sql = "SELECT 
            'franquia' as solicitante, a.id, a.data as dt_ordem, date_format(a.data,'%d/%m/%Y') as data, 
            d.fantasia, e.nome, c.descricao AS descricao_produto,
            b.numero_serie, b.valor_unitario, b.codigo_barra, a.id_franquia, a.id_funcionario
        FROM cs2.franquia_equipamento a
        INNER JOIN franquia_equipamento_descricao b ON a.id = b.id_franquia_equipamento
        INNER JOIN base_web_control.produto c ON c.id_cadastro = 62735 AND ( c.codigo_barra = b.codigo_barra OR b.codigo_barra = c.identificacao_interna)
        INNER JOIN cs2.franquia d ON a.id_franquia = d.id
        LEFT JOIN cs2.funcionario e ON a.id_funcionario = e.id
        WHERE a.consignacao='S'";

        if ($_REQUEST['data2I'] != '' && $_REQUEST['data2F'] != '')
            $sql .= " AND data BETWEEN '$data2I' AND '$data2F'";

        if ($_REQUEST['franqueado'] != '0') {
            $sql .= " AND a.id_franquia = '{$_REQUEST['franqueado']}'";
            $dados_franqueado = dados('franquia', $_REQUEST['franqueado'], $con);
        } else
            $dados_franqueado = '';

        if ($_REQUEST['id_funcionario'] != '0') {
            $sql .= " AND a.id_funcionario = '{$_REQUEST['id_funcionario']}'";
            $dados_funcionario = dados('funcionario', $_REQUEST['id_funcionario'], $con);
        } else
            $dados_funcionario = '';

        //echo "<pre>".$sql;

        $qry = mysql_query($sql, $con);

        ?>
        <div>
            <form id="frmImprimirTermica" name="frmImprimirTermica" method="post" action="clientes/relatorio_indica_amigo_imprimir.php">
                <table class="tblIndicaAmigo" id="tblIndicaAmigo" border="0" width="95%" align="center" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF; font-family: Verdana;font-size: 10px">
                    <tr class="titulo">
                        <td colspan="2">Relat&oacute;rios</td>
                    </tr>
                    <tr>
                    <tr>
                        <td class="subtitulodireita" width='200px'>Período</td>
                        <td class="subtitulopequeno">
                            <?php echo $_REQUEST['data2I'] . ' à ' . $_REQUEST['data2F']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="subtitulodireita">Franquia:</td>
                        <td class="subtitulopequeno">
                            <?php echo $dados_franqueado; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="subtitulodireita">Funcionário:</td>
                        <td class="subtitulopequeno">
                            <?php echo $dados_funcionario; ?>
                        </td>
                    </tr>
                    </tr>
                </table>

                <table class="tblIndicaAmigo" id="tblIndicaAmigo" border="1" width="95%" align="center" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF; font-family: Verdana;font-size: 10px">
                    <thead>
                        <tr bgcolor="#CFCFCF">
                            <th>Data Pedido</th>
                            <th>Franquia/Cliente</th>
                            <th>Funcionário</th>
                            <th>Equipamento</th>
                            <th>Nº. Série</th>
                            <th>Valor</th>
                            <th>
                                <table>
                                    <tr>
                                        <th width='10%' bgcolor="#F0F8FF">Parcela</th>
                                        <th width='20%' bgcolor="#FAF0E6">Vencimento</th>
                                        <th width='15%' bgcolor="#F0F8FF">Valor</th>
                                        <th width='20%' bgcolor="#FAF0E6">Dt. Pgto</th>
                                        <th width='15%' bgcolor="#F0F8FF">Valor Pago</th>
                                    </tr>
                                </table>
                            </th>
                            <th>Dt Pgto Premiação</th>
                            <th>Vlr Premiação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $a_cor = array("#eee", "#FFFFFF");
                        $cont = 0;

                        while ($res = mysql_fetch_array($qry)) {

                            $cont++;
                        ?>
                            <tr data-identificacao="<?= $res['id'] ?>" data-data="<?= $res['data'] ?>" data-id_franquia="<?= $res['id_franquia'] ?>" data-funcionario="<?= $res['id_funcionario'] ?>" data-equipamento="<?= $res['codigo_barra'] ?>" data-id_franquia="<?= $res['id'] ?>" data-numero_serie="<?= $res['numero_serie'] ?>" data-valor_unitario="<?= number_format($res['valor_unitario'], 2, ',', '.') ?>">

                                <td class="mostrar"><?= $res['data'] ?></td>
                                <td class="mostrar"><?= $res['fantasia'] ?></td>
                                <td class="mostrar"><?= $res['nome'] ?></td>
                                <td class="mostrar"><?= $res['descricao_produto'] ?></td>
                                <td class="mostrar"><?= $res['numero_serie'] ?></td>
                                <td class="mostrar"><?= number_format($res['valor_unitario'], 2, ',', '.') ?></td>
                                <td>
                                    <table>
                                        <?php
                                        if ($res['solicitante'] == 'franquia') {
                                            $sql_parcela = "SELECT
                                                            id, numero_parcela, qtd_parcelas, date_format(data_vencimento,'%d/%m/%Y') as vencimento, valor_parcela, 
                                                            valor_pagamento, date_format(data_pagamento,'%d/%m/%Y') as data_pagamento
                                                        FROM cs2.cadastro_emprestimo_franquia 
                                                        WHERE protocolo = '{$res['id']}'";
                                            $qry_parcela = mysql_query($sql_parcela, $con);
                                            $qtd_reg = mysql_num_fields($qry_parcela);
                                            $reg = 0;
                                            $link_img = '';
                                            while ($res2 = mysql_fetch_array($qry_parcela)) {
                                                $reg++;
                                                echo "<tr data-id='{$res2['id']}'>
                                                    <td width='10%' bgcolor='#F0F8FF'>{$res2['numero_parcela']}</td>
                                                    <td width='20%' bgcolor='#FAF0E6'>{$res2['vencimento']}</td>
                                                    <td width='15%' bgcolor='#F0F8FF'>{$res2['valor_parcela']}</td>
                                                    <td width='20%' bgcolor='#FAF0E6'>{$res2['data_pagamento']}</td>
                                                    <td width='15%' bgcolor='#F0F8FF'>{$res2['valor_pagamento']}</td>
                                                  </tr>";
                                            }
                                        } else {

                                            $sql_parcela = "SELECT
                                                            id, id_venda, date_format(vencimento,'%d/%m/%Y') as vencimento, valor, id_formapgto
                                                        FROM cs2.cadastro_equipamento_pagamento
                                                        WHERE id_venda = '{$res['id']}'"
                                                . " ORDER BY vencimento";
                                            $qry_parcela = mysql_query($sql_parcela, $con);
                                            $qtd_reg = mysql_num_fields($qry_parcela);
                                            $reg = 0;
                                            $j = 0;
                                            $link_img = '';
                                            while ($res2 = mysql_fetch_array($qry_parcela)) {
                                                $reg++;
                                                $j++;
                                                echo "<tr data-id='{$res2['id']}'>
                                                    <td width='10%' bgcolor='#F0F8FF'>$j</td>
                                                    <td width='20%' bgcolor='#FAF0E6'>{$res2['vencimento']}</td>
                                                    <td width='15%' bgcolor='#F0F8FF'>{$res2['valor']}</td>
                                                    <td width='20%' bgcolor='#FAF0E6'></td>
                                                    <td width='15%' bgcolor='#F0F8FF'></td>
                                                  </tr>";
                                            }
                                        }
                                        ?>
                                    </table>
                                </td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        </div>
        <?php
        break;

    case '3':

        // Localizando o equipamento pelo numero de serie, porem tenho que fazer a pesquisa
        // em 2 tabelas:  cadastro_equipamento(cliente) e franquia_equipamento(franquia)

        $sqly = "SELECT
            'franquia' as solicitante, a.id, a.data as dt_ordem, date_format(a.data,'%d/%m/%Y') as data, 
            d.fantasia, e.nome, d.id AS idFranquia_Codloja
        FROM cs2.franquia_equipamento a
        INNER JOIN cs2.franquia_equipamento_descricao b ON a.id = b.id_franquia_equipamento
        INNER JOIN cs2.franquia d ON a.id_franquia = d.id
        LEFT JOIN cs2.funcionario e ON a.id_funcionario = e.id
        WHERE b.numero_serie = '{$_REQUEST['numero_serie']}'
            AND a.consignacao = 'S'
        UNION
        SELECT 
                'cliente' as solicitante, a.id, a.data_compra as dt_ordem, 
                date_format(a.data_compra,'%d/%m/%Y') as data, d.nomefantasia AS fantasia, 
                e.nome, CAST(MID(l.logon,1,6) AS UNSIGNED) as idFranquia_Codloja
        FROM cs2.cadastro_equipamento a
        INNER JOIN cs2.cadastro_equipamento_descricao b ON a.id = b.id_cadastro_equipamento
        INNER JOIN cs2.cadastro   d ON a.codloja = d.codloja
        LEFT JOIN cs2.funcionario e ON a.id_consultor = e.id
        LEFT OUTER JOIN cs2.logon l ON a.codloja = l.codloja
        WHERE b.numero_serie = '{$_REQUEST['numero_serie']}'";
        $resy = mysql_query($sqly, $con);
        while ($regy = mysql_fetch_array($resy)) {

            $solicitante = $regy['solicitante'];
            $id = $regy['id'];
            $data = $regy['data'];
            $nome = $regy['fantasia'];
            $nomeFuncionario = $regy['nome']; // Nome do Funcionario
            $idFranquia_Codloja = $regy['idFranquia_Codloja'];

            if ($solicitante == 'cliente') {

                echo "
                <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                    <tr style='background-color: #a1c2fa; text-align: center'>
                        <td colspan='8'>$idFranquia_Codloja - $nome</td>
                    </tr>
                </table>";

                $sql_dados = "SELECT a.qtd, a.numero_serie, b.descricao, a.valor_unitario, c.vr_desconto,
                                    DATE_FORMAT(c.data_compra,'%d/%m/%Y') as data, c.id, a.codigo_barra, ca.nome
                              FROM cs2.cadastro_equipamento_descricao a 
                              INNER JOIN base_web_control.produto b  ON b.id_cadastro = 62735 AND ( a.codigo_barra = b.codigo_barra OR a.codigo_barra = b.identificacao_interna)
                              INNER JOIN cs2.cadastro_equipamento c  ON c.id = a.id_cadastro_equipamento
                              LEFT JOIN cs2.funcionario ca           ON ca.id = c.id_consultor
                              WHERE c.id = $id
                              ORDER BY c.id";
                $res = mysql_query($sql_dados, $con);
        ?>
                <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                    <tr class="titulo">
                        <td colspan="8"><?php echo $idFranquia_Codloja . ' - ' . $nome . ' - ' . $nomeFuncionario ?></td>
                    </tr>
                    <tr>
                        <td colspan='8' height='1' bgcolor='#999999'></td>
                    </tr>
                    <tr height='20' class='titulo'>
                        <td width='7%'>Data</td>
                        <td width='5%'>Qtde</td>
                        <td width='10%'>Cod. Barra</td>
                        <td width='30%'>Descri&ccedil;&atilde;o</td>
                        <td width='10%'>S&eacute;rie</td>
                        <td width='10%'>Vr. Unit&aacute;rio</td>
                        <?php if ($id_franquia == 1) { ?>
                            <td width='10%'>Consultor</td>
                            <td width='10%'>Alterar Consultor</td>
                        <?php } ?>
                    </tr>
                    <?php
                    $saida = '';
                    $soma_total = 0;
                    $a = 0;
                    while ($reg = mysql_fetch_array($res)) {

                        $a++;

                        $id_venda = $reg['id'];
                        $cod_barra = $reg['codigo_barra'];
                        $qtd = $reg['qtd'];
                        if ($qtd == 0) {
                            $qtd = 1;
                        }
                        $data = $reg['data'];
                        $descricao = $reg['descricao'];
                        $serie = $reg['numero_serie'];
                        $vr_unit = $reg['valor_unitario'];
                        $total = $qtd * $vr_unit;
                        $soma_total += $total;
                        $vr_unit = number_format($vr_unit, 2, ',', '.');

                        $vr_desconto = number_format($reg['vr_desconto'], 2, ',', '.');


                        $saida .= "<tr height='22'";
                        if (($a % 2) == 0) {
                            $saida .= "bgcolor='#E5E5E5'>";
                        } else {
                            $saida .= ">";
                        }
                        $sql_pgto = "SELECT
                                        valor, DATE_FORMAT(vencimento,'%d/%m/%Y') as venc,
                                        vencimento,
                                        CASE id_formapgto 
                                            WHEN '1' THEN 'DINHEIRO'
                                            WHEN '2' THEN 'CHEQUE'
                                            WHEN '3' THEN 'CARTAO DE CREDITO'
                                            WHEN '4' THEN 'BOLETO'
                                            WHEN '5' THEN 'CARTAO DE DEBITO'
                                            WHEN '6' THEN 'ANTECIPACAO'
                                        END as pgto
                                     FROM cs2.cadastro_equipamento_pagamento 
                                     WHERE id_venda = $id_venda
                                     ORDER BY vencimento";
                        $res_pgto = mysql_query($sql_pgto, $con);
                        $forma_pgto = '';
                        $soma_produto = 0;
                        $vr_recebido = 0;
                        while ($reg_pg = mysql_fetch_array($res_pgto)) {
                            $pgto = $reg_pg['pgto'];
                            $venc = $reg_pg['venc'];
                            $vlr = number_format($reg_pg['valor'], 2, ',', '.');
                            $vr_recebido += $reg_pg['valor'];
                            $forma_pgto .= $pgto . ' - ' . $venc . ' - R$ ' . $vlr . '<br>';
                        }
                        $i++;
                        $saida .= "    <td align='center'>$data</td>";
                        $saida .= "    <td align='center'>$qtd</td>";
                        $saida .= "    <td align='center'>$cod_barra</td>";
                        $saida .= "    <td align='center'>$descricao</td>";
                        $saida .= "    <td align='center'>$serie</td>";
                        $saida .= "    <td align='center'>R$ $vr_unit</td>";

                        if ($id_franquia == 1) {
                            $saida .= "<td align='center'>" . $reg['nome'] . "</td>";
                            $saida .= "<td align='center'><a onclick='alterarVendedor(\"" . $reg['id'] . "\")'>Alterar Consultor</a></td>";
                        }

                        $saida .= "</tr>";
                    }


                    $vr_desconto = $soma_total - $vr_recebido;

                    $vr_desconto2 = number_format($vr_desconto, 2, ',', '.');

                    $total = number_format($soma_total - $vr_desconto, 2, ',', '.');

                    $saida .= "<tr>";
                    $saida .= "    <td align='right' colspan='7'><br><b>Valor Desconto: R$ $vr_desconto2</b></td>";
                    $saida .= "</tr>";
                    $saida .= "<tr>";
                    $saida .= "    <td align='right' colspan='7'><b><font color='#0000FF'>TOTAL PAGO: R$ $total</b></font></td>";
                    $saida .= "</tr>";

                    $saida .= "<tr>";
                    $saida .= "    <td align='right' colspan='7'><br><b>FORMA DE PAGAMENTO: $forma_pgto</b></td>";
                    $saida .= "</tr>";

                    echo $saida;
                    echo "</table>";
                } else {

                    echo "
                    <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                        <tr style='background-color: #a1c2fa; text-align: center'>
                            <td colspan='8'>$idFranquia_Codloja - $nome - $nomeFuncionario</td>
                        </tr>
                    </table>";

                    $sql_dados = "SELECT
                            a.qtd, a.numero_serie, b.descricao, a.valor_unitario,
                            DATE_FORMAT(c.data,'%d/%m/%Y') as data, a.codigo_barra, ca.nome
                          FROM cs2.franquia_equipamento_descricao a
                          INNER JOIN base_web_control.produto b ON b.id_cadastro = 62735 and ( a.codigo_barra = b.codigo_barra OR a.codigo_barra = b.identificacao_interna)
                          INNER JOIN cs2.franquia_equipamento c  ON c.id = a.id_franquia_equipamento
                          LEFT JOIN cs2.funcionario ca           ON ca.id = c.id_consultor
                          WHERE a.id_franquia_equipamento = $id";
                    $res = mysql_query($sql_dados, $con);
                    ?>
                    <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                        <tr class="titulo">
                            <td colspan="8">Equipamentos em Consignação</td>
                        </tr>
                        <tr>
                            <td colspan='8' height='1' bgcolor='#999999'></td>
                        </tr>
                        <tr height='20' class='titulo'>
                            <td width='7%'>Data</td>
                            <td width='5%'>Qtde</td>
                            <td width='10%'>Cod. Barra</td>
                            <td width='30%'>Descri&ccedil;&atilde;o</td>
                            <td width='10%'>S&eacute;rie</td>
                            <td width='10%'>Vr. Unit&aacute;rio</td>
                            <?php if ($id_franquia == 1) { ?>
                                <td width='10%'>Consultor</td>
                                <td width='10%'>Alterar Consultor</td>
                            <?php } ?>
                        </tr>
                <?php
                    $saida = '';
                    $soma_total = 0;
                    $a = 0;
                    while ($reg = mysql_fetch_array($res)) {

                        $a++;

                        $id_venda = $reg['id'];
                        $cod_barra = $reg['codigo_barra'];
                        $qtd = $reg['qtd'];
                        $data = $reg['data'];
                        $descricao = $reg['descricao'];
                        $serie = $reg['numero_serie'];
                        $vr_unit = $reg['valor_unitario'];
                        $total = $qtd * $vr_unit;
                        $soma_total += $total;
                        $vr_unit = number_format($vr_unit, 2, ',', '.');
                        $vr_desconto = number_format($reg['vr_desconto'], 2, ',', '.');

                        $saida .= "<tr height='22'";
                        if (($a % 2) == 0) {
                            $saida .= "bgcolor='#E5E5E5'>";
                        } else {
                            $saida .= ">";
                        }

                        $i++;
                        $saida .= "    <td align='center'>$data</td>";
                        $saida .= "    <td align='center'>$qtd</td>";
                        $saida .= "    <td align='center'>$cod_barra</td>";
                        $saida .= "    <td align='center'>$descricao</td>";
                        $saida .= "    <td align='center'>$serie</td>";
                        $saida .= "    <td align='center'>R$ $vr_unit</td>";

                        if ($id_franquia == 1) {
                            $saida .= "<td align='center'>" . $reg['nome'] . "</td>";
                            $saida .= "<td align='center'><a onclick='alterarVendedor(\"" . $reg['id'] . "\")'>Alterar Consultor</a></td>";
                        }

                        $saida .= "</tr>";
                    }
                    $total = number_format($soma_total - $vr_desconto, 2, ',', '.');
                    $saida .= "<tr>";
                    $saida .= "    <td align='right' colspan='7'>&nbsp;</td>";
                    $saida .= "</tr>";

                    echo $saida;

                    $sql_pgto = "SELECT DATE_FORMAT(data_vencimento,'%d/%m/%Y') as data_venc, valor_parcela,
                                    data_vencimento,
                                    DATE_FORMAT(data_pagamento,'%d/%m/%Y') as data_pagamento, valor_pagamento
                             FROM cs2.cadastro_emprestimo_franquia
                             WHERE protocolo = '$id' ORDER BY data_vencimento";
                    $qry_pgto = mysql_query($sql_pgto, $con);
                    $forma_pgto = '';

                    $html_forma = "
                        <tr>
                            <td colspan='7'>
                                <table width='50%' align='center' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                                    <tr style='background-color: #a1c2fa; text-align: center'>
                                        <td colspan='4'>Lan&ccedil;amentos Realizados</td>
                                    </tr>
                                    <tr class='titulo'>
                                        <td>Vencimento</td>
                                        <td>Valor</td>
                                        <td>Data Pagamento</td>
                                        <td>Valor Pagamento</td>
                                    </tr>";
                    $j = 0;
                    while ($reg_pg = mysql_fetch_array($qry_pgto)) {
                        $j++;
                        $pgto = 'DEBITO Extrato';
                        $venc = $reg_pg['data_venc'];
                        $dtpg = $reg_pg['data_pagamento'];
                        $vrpg = $reg_pg['valor_pagamento'];

                        $vlr = number_format($reg_pg['valor_parcela'], 2, ',', '.');
                        if ($vrpg > 0)
                            $vrpg = number_format($reg_pg['valor_pagamento'], 2, ',', '.');

                        if (($j % 2) == 0) {
                            $html_forma .= "<tr align='center' bgcolor='#E5E5E5'>";
                        } else {
                            $html_forma .= "<tr align='center'>";
                        }
                        $html_forma .= " <td>$venc</td>
                                                         <td>$vlr</td>
                                                         <td>$dtpg</td>
                                                         <td>$vrpg</td>
                                                      </tr>";
                    }
                    $html_forma .= "
                                </table>
                              </td>
                           </tr>
                        </table>";
                    echo $html_forma;
                }
            }
            break;

        case '4':

            $equipamento = $_REQUEST['equipamento'];

            $data4I = $_REQUEST['data4I'];

            if ($data4I != '') {
                $data4I = substr($data4I, 6, 4) . '-' . substr($data4I, 3, 2) . '-' . substr($data4I, 0, 2);
                $data4F = $_REQUEST['data4F'];
                $data4F = substr($data4F, 6, 4) . '-' . substr($data4F, 3, 2) . '-' . substr($data4F, 0, 2);

                $datac = "and c.data_compra BETWEEN '$data4I' AND '$data4F'";
                $dataf = "and c.data BETWEEN '$data4I' AND '$data4F'";
            } else {
                $datac = '';
                $dataf = '';
            }

            $sql = "SELECT
                    'cliente' as origem, DATE_FORMAT(c.data_compra,'%d/%m/%Y') as data, c.codloja, 
                    b.descricao, a.numero_serie, ca.nome,
                    ( SELECT CAST(MID(logon,1,6) AS UNSIGNED) FROM cs2.logon WHERE codloja = c.codloja LIMIT 1) AS logon,
                    ( SELECT nomefantasia FROM cs2.cadastro WHERE codloja = c.codloja LIMIT 1) AS nomefantasia
                FROM cs2.cadastro_equipamento_descricao a 
                INNER JOIN base_web_control.produto b  ON b.id_cadastro = 62735 AND ( a.codigo_barra = b.codigo_barra OR a.codigo_barra = b.identificacao_interna)
                INNER JOIN cs2.cadastro_equipamento c  ON c.id = a.id_cadastro_equipamento
                LEFT JOIN cs2.funcionario ca           ON ca.id = c.id_consultor
                WHERE a.codigo_barra = '$equipamento' $datac
                    AND a.consignacao = 'S'
                
                UNION
                
                SELECT 
                    'franquia' as origem, DATE_FORMAT(c.data,'%d/%m/%Y') as data, 'ID',
                    b.descricao, a.numero_serie, ca.nome, 'LOGON',
                    ( SELECT fantasia FROM cs2.franquia WHERE id = c.id_franquia LIMIT 1) AS nomefantasia
                FROM cs2.franquia_equipamento_descricao a 
                INNER JOIN base_web_control.produto b  ON b.id_cadastro = 62735 AND ( a.codigo_barra = b.codigo_barra OR a.codigo_barra = b.identificacao_interna)
                INNER JOIN cs2.franquia_equipamento c  ON c.id = a.id_franquia_equipamento
                LEFT JOIN cs2.funcionario ca           ON ca.id = c.id_funcionario
                WHERE a.codigo_barra = '$equipamento' $dataf
                ORDER BY data";

            //        echo "<pre>";
            //        print_r($sql);

            $qry = mysql_query($sql, $con);

                ?>

                <table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                    <tr class="titulo">
                        <td colspan="8">Equipamentos Vendidos por periodo</td>
                    </tr>
                    <tr>
                        <td colspan='6' height='1' bgcolor='#999999'></td>
                    </tr>
                    <tr height='20' class='titulo'>
                        <td width='7%'>Data</td>
                        <td width='5%'>Codigo</td>
                        <td width='10%'>Empresa</td>
                        <td width='30%'>Descri&ccedil;&atilde;o</td>
                        <td width='10%'>N&deg;. S&eacute;rie</td>
                        <td width='10%'>Funcion&aacute;rio</td>
                    </tr>
                    <?php
                    $a = 0;
                    $saida = '';
                    while ($reg = mysql_fetch_array($qry)) {
                        $a++;
                        $saida .= "<tr height='22'";
                        if (($a % 2) == 0) {
                            $saida .= "bgcolor='#E5E5E5'>";
                        } else {
                            $saida .= ">";
                        }
                        $saida .= "    <td>{$reg['data']}</td>";
                        $saida .= "    <td align='center'>{$reg['logon']}</td>";
                        $saida .= "    <td>{$reg['nomefantasia']}</td>";
                        $saida .= "    <td>{$reg['descricao']}</td>";
                        $saida .= "    <td align='center'>{$reg['numero_serie']}</td>";
                        $saida .= "    <td align='center'>{$reg['nome']}</td>";
                        $saida .= "</tr>";
                    }
                    $saida .= "<tr class='titulo' height='1'><td colspan='6'></td></tr>";
                    $saida .= "<tr><td colspan='6'>Listados $a registros.</td></tr>";
                    echo $saida;
                    echo "</table>";

                    break;

                case '5':


                    if ($_POST['iptFuncao'] == 0 || empty($_POST['iptFuncao'])) { ?>

                        <script>
                            alert('Favor selecionar uma função!');
                            location.href = '../php/painel.php?pagina1=area_restrita/d_equipamentos_relatorio.php';
                        </script>

                    <?php exit;
                    }

                    $iptFuncao = $_POST['iptFuncao'];
                    $iptTipoRelatorio = $_POST['iptTipoRelatorio'];

                    switch ($_POST['iptFuncao']) {
                        case '9': // Atendimento Comercial Externo

                            if ($_POST['iptTipoRelatorio'] == 'S') {
                                include('rel_comissao_func.php');
                            } else if ($_POST['iptTipoRelatorio'] == 'M') {
                                include('rel_comissao_func_mensal.php');
                            } else {
                                include('rel_comissao_func_contabil.php');
                            }
                            break;

                        case '19': // Atendimento Administrativo Externo
                            include('rel_comissao_atend_externo.php');

                            break;

                        case '10': // Auxiliar Comercial
                            include('rel_comissao_aux_comercial.php');
                            break;

                        case '13':
                        case '17':
                        case '18':
                        case '28':  // incluido Auxiliar de Conferencia - Pedido Danillo
                            include('rel_comissao_13_17_18.php');
                            break;

                        case '24': // Assistente de Automação

                            include('rel_assistente_automacao.php');
                            break;

                        default:

                            include('rel_cred_deb_func.php');
                            break;
                    }

                    break;

                case '6':

                    //                    echo '<pre>';
                    //                    var_dump($_REQUEST);
                    //                    die;
                    ?>
                    <script>
                        location.href = 'area_restrita/<?php echo 'd_imprimir_cheklistequipamentos.php?id_funcionario=' . $_REQUEST['id_funcionario_check'] . '&codigo_barra=' . $_REQUEST['codigo_barras_check']; ?>'
                    </script>
            <?php
                    break;
                case '8':
                    include('rel_brincadeira.php');

                    break;
            } ?>
            <form method="post" action="#" name='tela_voltar' id='tela_voltar' class="noprint">
                <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                    <tr align="center">
                        <td>
                            <br>
                            <input type="button" value=" VOLTAR " onclick="voltar()">
                        </td>
                    </tr>
                </table>
            </form>


            <script>
                $(document).ready(function() {
                    $('#btnImprimir').click(function() {
                        function Popup(data, style) {
                            var mywindow = window.open('', '.imprimir');
                            mywindow.document.write('<html><head><title></title>');
                            //stylesheet
                            mywindow.document.write('<style>' + style + '</style>');

                            mywindow.document.write('</head><body >');
                            mywindow.document.write(data);
                            mywindow.document.write('</body></html>');

                            mywindow.document.close(); // necessary for IE >= 10
                            mywindow.focus(); // necessary for IE >= 10

                            setTimeout(function() {
                                mywindow.print();
                                mywindow.close();
                            }, 100); //1 segundo

                            return true;
                        }

                        $('.no-print').remove();

                        //estilo da impressao
                        var style = '\n\
                @media print {\n\
                    .page-break{ \n\
                    page-break-after: always;\n\
                    }\n\
                    div {\n\
                    float: none !important;\n\
                    }\n\
                    }\n\
        .titulo\n\
        {\n\
            FONT-WEIGHT: bold;\n\
            FONT-SIZE: 8pt;\n\
            COLOR: black;\n\
            FONT-FAMILY: Arial;\n\
            HEIGHT: 20px;\n\
            BACKGROUND-COLOR: #CCCCCC;\n\
            TEXT-ALIGN: center\n\
        }\n\
        .titulo_e_nois\n\
        {\n\
            FONT-WEIGHT: bold;\n\
            FONT-SIZE: 8pt;\n\
            COLOR: black;\n\
            FONT-FAMILY: Arial;\n\
            HEIGHT: 20px;\n\
            BACKGROUND-COLOR: #6CF;\n\
            TEXT-ALIGN: center\n\
        }\n\
        .subtitulo\n\
        {\n\
            FONT-WEIGHT: bold;\n\
            FONT-SIZE: 8pt;\n\
            COLOR: black;\n\
            FONT-FAMILY: Arial;\n\
            BACKGROUND-COLOR: lightsteelblue;\n\
            TEXT-ALIGN: center\n\
        }\n\
        .subtitulopequeno\n\
        {\n\
            FONT-SIZE: 8pt;\n\
            COLOR: black;\n\
            FONT-FAMILY: Arial;\n\
            BACKGROUND-COLOR: lightsteelblue;\n\
            TEXT-ALIGN: left;\n\
            padding-left:5px;\n\
        }\n\
        .corpoTabela\n\
        {\n\
            FONT-SIZE: 8pt;\n\
            COLOR: black;\n\
            FONT-FAMILY: Arial;\n\
            padding-left:5px;\n\
            \n\
        }\n\
        .subtitulopequeno1\n\
        {\n\
            FONT-WEIGHT: bold;\n\
            FONT-SIZE: 8pt;\n\
            COLOR: black;\n\
            FONT-FAMILY: Arial;\n\
            BACKGROUND-COLOR: lightsteelblue;\n\
            TEXT-ALIGN: center\n\
        }\n\
        ';

                        Popup($(".imprimir").html(), style);
                    });
                })
            </script>