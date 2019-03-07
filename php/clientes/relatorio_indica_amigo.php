<?php

require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";

$where = ($_SESSION['ss_tipo'] == 'b') ? ' WHERE cad.id_franquia = ' . $_SESSION['id'] : '';

$sql = "SELECT
          ia.id,
          ia.id_cadastro,
          ia.codigo_associado,
          UPPER(ia.nome_amigo) AS nome_amigo,
          ia.fone_amigo1,
          ia.fone_amigo2,
          ia.dt_creation,
          ia.dt_last_update,
          cad.id_franquia,
          ia.id_agendador,
          frq.fantasia AS nome_franquia,
          ia.fatura_bonificar,
          (SELECT
                cod_cliente_vr
        FROM base_web_control.indica_amigo_log ial
        WHERE id_indicacao = ia.id
        ORDER BY ial.id DESC LIMIT 1) AS cod_cliente_vr,
      (SELECT
                status_indicacao
            FROM base_web_control.indica_amigo_log ial
            WHERE id_indicacao = ia.id
            ORDER BY ial.id DESC LIMIT 1
          ) AS status_indicacao,
                
      ( SELECT
                if (ial.num_doc = 'DEPOSITO C/C', 'DEPOSITO C/C', date_format(b.vencimento,'%d/%m/%Y') )
            FROM base_web_control.indica_amigo_log ial
            LEFT OUTER JOIN cs2.titulos b on ial.num_doc = b.numdoc
            WHERE id_indicacao = ia.id
            ORDER BY ial.id DESC LIMIT 1
          ) AS num_doc,
                
          (SELECT IF ( c.pendencia_contratual = '0', 'REGULAR', 'PENDENTE' ) FROM base_web_control.webc_usuario AS u
                INNER JOIN cs2.cadastro c ON u.id_cadastro = c.codloja
            WHERE u.login =(SELECT
            cod_cliente_vr
        FROM base_web_control.indica_amigo_log ial
        WHERE id_indicacao = ia.id
        ORDER BY ial.id DESC LIMIT 1)
            LIMIT 1 ) AS pendencia_contratual

            FROM base_web_control.indica_amigo AS ia

        INNER JOIN cs2.cadastro AS cad ON cad.codloja = ia.id_cadastro
        INNER JOIN cs2.franquia AS frq ON frq.id = cad.id_franquia
        $where";

if (@$_POST['iptFranquia']) {
    $sql .= ' AND frq.id = ' . $_POST['iptFranquia'];
}
if (@$_POST['iptSearch']) {
    $sql .= ' AND (ia.codigo_associado LIKE "%' . $_POST['iptSearch'] . '%" OR ia.fone_amigo1 LIKE "%' . $_POST['iptSearch'] . '%" OR ia.id = "' . $_POST['iptSearch'] . '" OR ia.fone_amigo2 LIKE "%' . $_POST['iptSearch'] . '%" OR ia.nome_amigo LIKE "%' . $_POST['iptSearch'] . '%")';
}

$sql .= " ORDER BY ia.id DESC";


if ( @$_POST['iptFranquia'] == '0' or @$_POST['iptFranquia'] == '' ) 
    $sql .= ' LIMIT 100';


// echo "<pre>";
// echo $sql;
// echo "</pre>";die;

$qry = mysql_query($sql, $con) or die($sql);
$total = mysql_num_rows($qry);


$sqlFranquias = "SELECT
                    frq.id,
                    frq.fantasia
                FROM  base_web_control.indica_amigo ia
                " . $where . "
                INNER JOIN cs2.cadastro AS cad ON cad.codloja = ia.id_cadastro
                INNER JOIN cs2.franquia AS frq ON frq.id = cad.id_franquia
                GROUP BY frq.id
                ORDER BY frq.fantasia ASC";

if ($id_franquia == 4 || $id_franquia == 163) {

    $qryFranquias = mysql_query($sqlFranquias, $con);
    $totalFranquias = mysql_num_rows($qryFranquias);

    $html = '';
    while ($resFranquia = mysql_fetch_array($qryFranquias)) {
        $html .= '<option value="' . $resFranquia['id'] . '">' . $resFranquia['fantasia'] . '</option>';
    }
}
?>
<script>

</script>
<style>
    h1 {
        text-align: center
    }

    table {
        border-collapse: collapse;
        font-size: 12px;
        font-family: arial, sans-serif;
    }

    table.tblIndicaAmigo tr td {
        padding: 6px
    }

    @media print {
        .noprint {
            display: none
        }
    }

    table.tblIndicaAmigo tr:hover {
        background: #ddd;
        cursor: pointer;
    }
</style>
<div id="div2Print">
    <h1>Relat&oacute;rio de Indica&ccedil;&atilde;o de Amigo</h1>

    <div class="noprint">
        <div style="margin-left: 52px;">
            <form id="frmFiltro" method="post">
                <?php if ($id_franquia == 4 || $id_franquia == 163) { ?>
                    <label for="iptFranquia">Franquia</label>
                    <select id="iptFranquia" name="iptFranquia">
                        <option value="0">Todas</option>
                        <?php echo $html ?>
                    </select>
                <?php } ?>
                <label for="iptSearch">Status da Indicação</label>
                    <select id="iptStatus" name="iptStatus">
                        <option value="0" selected="selected">Todas</option>
                        <option value="VR">Venda Realizada</option>
                        <option value="SI">Sem Interesse</option>
                        <option value="RE">Reagendado</option>
                        <option value="SC">Sem Contato</option>
                        <option value="JC">Já era cliente</option>
                        <option value="AC">Auto Indicou</option>
                        <option value="RP">Repetido</option>
                        <option value="TE">Telefone(s) Errado(s).</option>
                        <option value="CC">Associado Cancelou.</option>
                        <option value="CA">Amigo Indicado Cancelou.</option>
                        <option value="CT">Contador (Não gera bonificação).</option>

                    </select>
                <label for="iptSearch">Busca Avançada</label>
                <input type="text" name="iptSearch" id="iptSearch">
                <button id="btnFiltrar" type="submit">Filtrar</button>
            </form>
        </div>
        <!--
        <div style="margin-left: 52px; margin-top: 20px;">
            <button name="btnImprimirTermica" id="btnImprimirTermica">Imprimir Selecionados (T&eacute;rmica)</button>
        </div>
        -->
        <div style="margin-left: 52px; margin-top: 20px;">
            <button name="btnImprimir" id="btnImprimir">Imprimir Selecionados</button>
        </div>

        <br>
        <br>
    </div>
    
    <form id="frmImprimirTermica" name="frmImprimirTermica" method="post"
          action="clientes/relatorio_indica_amigo_imprimir.php">
        <table class="tblIndicaAmigo" id="tblIndicaAmigo" border="1" width="95%" align="center" cellspacing="0"
               style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
            <thead>
                <tr bgcolor="#CFCFCF">
                    <th><input type="checkbox" value="<?php echo $res['id'] ?>" id="iptCheckTodos" name="iptCheckTodos">
                    </th>
                    <th>Id</th>
                    <th>Data Indica&ccedil;&atilde;o</th>
                    <th>Franquia</th>
                    <th>Associado</th>
                    <th>Amigo Indicado</th>
                    <th>Telefone</th>
                    <th>Data Finaliza&ccedil;&atilde;o</th>
                    <th>Status Indicação</th>
                    <th>Status Contrato</th>
                    <th>Fat. Boni.</th>
                    <th>Fat. &agrave; Boni.</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                $a_cor = array("#eee", "#FFFFFF");
                $cont = 0;

                while ($res = mysql_fetch_array($qry)) {

                    $iptStatus = @$_REQUEST['iptStatus'];
                    $ok = 0;
                    if ( $iptStatus == '0' ) $ok = '1';
                    elseif ( $res['status_indicacao'] == $iptStatus ) $ok = 1;
                    else $ok = 0;

                    if ( $ok == 1 ){
                        $cont++;
                        if ($res['id_agendador'] == 0) {
                            $status = '<span style="color:#f00;">Pendente</span>';
                        } else {
                            $status = '<span style="color:#6bb9f0;">Repassado ao Consultor</span>';
                        }

                        $reg = '';

                        if ($res['status_indicacao'] == 'VR') {
                            $reg = $res['pendencia_contratual'];
                            $status = "<span style='color:#12b51a;'>Venda Realizada</span>";
                        } else if ($res['status_indicacao'] == 'SI') {
                            $status = "<span style='color:#666;'>Sem Interesse</span>";
                        } else if ($res['status_indicacao'] == 'RE') {
                            $status = "<span style='color:#347ecb;'>Reagendado</span>";
                        } else if ($res['status_indicacao'] == 'SC') {
                            $status = "<span style='color:#ff890b;'>Sem Contato</span>";
                        } else if ($res['status_indicacao'] == 'JC') {
                            $status = "<span style='color:#ff890b;'>Já era cliente</span>";
                        } else if ($res['status_indicacao'] == 'AC') {
                            $status = "<span style='color:#ff890b;'>Auto Indicou</span>";
                        } else if ($res['status_indicacao'] == 'RP') {
                            $status = "<span style='color:#ff890b;'>Repetido</span>";
                        } else if ($res['status_indicacao'] == 'TE') {
                            $status = "<span style='color:#ff890b;'>Telefone(s) Errado(s)</span>";
                        } else if ($res['status_indicacao'] == 'CC') {
                            $status = "<span style='color:#ff890b;'>Associado Cancelou</span>";
                        } else if ($res['status_indicacao'] == 'CA') {
                            $status = "<span style='color:#ff890b;'>Amigo Indicado Cancelou</span>";
                        } else if ($res['status_indicacao'] == 'CT') {
                            $status = "<span style='color:#ff890b;'>Contador ( Não gera bonificação)</span>";
                        }
                        
                        ?>
                        <tr data-id="<?= $res['id'] ?>" bgcolor="<?= $a_cor[$cont % 2] ?>">
                            <td class="tdCheck"><input type="checkbox" value="<?php echo $res['id'] ?>" id="iptIdIndique" name="iptIdIndique[]"></td>
                            <td class="tdSel"><?= $res['id'] ?></td>
                            <td class="tdSel"
                                style="text-align:center"><?= date("d/m/Y", strtotime($res['dt_creation'])) ?></td>
                            <td class="tdSel"><?= $res['nome_franquia'] ?></td>
                            <td class="tdSel" style="text-align:center"><?= $res['codigo_associado'] ?></td>
                            <td class="tdSel"><?= $res['nome_amigo'] ?></td>
                            <td class="tdSel"><?php echo ($res['fone_amigo2']) ? $res['fone_amigo1'] . ' / ' . $res['fone_amigo2'] : $res['fone_amigo1']; ?></td>
                            <td class="tdSel"
                                style="text-align:center"><?= date("d/m/Y", strtotime($res['dt_last_update'])) ?></td>
                            <td class="tdSel" style="text-align:center"><?= $status ?></td>
                            <td class="tdSel" style="text-align:center"><?= $reg ?></td>
                            <td class="tdSel" style="text-align:center"><?= $res['num_doc'] ?></td>
                            <td class="tdSel" style="text-align:center"><?= $res['fatura_bonificar'] ?></td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </form>
</div>

<script>

    $('#iptCheckTodos').click(function (e) {

        console.log($('input[name="iptCheckTodos"]:checked').length > 0);

        if ($('input[name="iptCheckTodos"]:checked').length > 0) {
            $.each($('input[name="iptIdIndique[]"]'), function () {
                $(this).attr('checked', 'checked');
            })
        } else {
            $.each($('input[name="iptIdIndique[]"]'), function () {
                $(this).removeAttr('checked');
            })

        }
    });


    $('.tdSel').click(function () {
        var idIndicacao = $(this).parent().attr('data-id');
        window.location = 'painel.php?pagina1=clientes/relatorio_indica_amigo_altera.php&idIndicacao=' + idIndicacao;
    });

    $('#btnImprimir').click(function () {
        $.each($("input[name='iptIdIndique[]']:checked"), function(){ 
            var idIndicacao = $(this).val();
            window.location = 'painel.php?pagina1=clientes/relatorio_indica_amigo_altera.php&imprimir=sim&idIndicacao=' + idIndicacao;
        });
    });


    $('button[name=btnImprimirRel]').click(function () {
        function Popup(data, style) {
            var mywindow = window.open('', '#div2Print');
            mywindow.document.write('<html><head><title></title>');
            /* stylesheet*/
            mywindow.document.write('<style>' + style + '</style>');
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10

            setTimeout(function () {
                mywindow.print();
                mywindow.close();
            }, 100); //1 segundo

            return true;
        }

        var style = 'h1{text-align: center}\n\
                          table{\n\
                                border-collapse: collapse;\n\
                                font-size:13px;\n\
                                font-family: arial, sans-serif;\n\
                          }\n\
              table.tblIndicaAmigo tr td{padding:2px}\n\
                          @media print {\n\
                                  .noprint{display:none}\n\
                                }';

        Popup($("#div2Print").html(), style);
    });

    $('#btnImprimirTermica').click(function () {

        $('#frmImprimirTermica').submit();

    })
</script>