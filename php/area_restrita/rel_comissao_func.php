<?php
/**
 * @file rel_comissao_func.php
 * @brief Arquivo Responsável pelo relatório de comissão
 * @author ARLLON DIAS
 * @date 06/01/2017
 * @version 1.0
 **/
require_once('auxiliarTabelaComissao.php');


if (empty($_POST['data2I']) || empty($_POST['data2F'])) { ?>

    <script>
        alert('Periodo Inicial e Final Obrigatório');
        location.href = '../php/painel.php?pagina1=area_restrita/d_equipamentos_relatorio.php';
    </script>

<?php } elseif (empty($_POST['franqueado']) || $_POST['franqueado'] == 0) { ?>

    <script>
        alert('Você deve selecionar uma franquia!');
        location.href = '../php/painel.php?pagina1=area_restrita/d_equipamentos_relatorio.php';
    </script>

<?php }

$id_funcionario = $_POST['id_funcionario'];
$id_franquia = $_POST['franqueado'];
$strDataInicio = $_POST['data2I'];
$strDataFim = $_POST['data2F'];
$id_funcao = $_POST['iptFuncao'];
$ativo = $_POST['iptAtivo'];

$strDataInicio = substr($strDataInicio, 6, 4) . '-' . substr($strDataInicio, 3, 2) . '-' . substr($strDataInicio, 0, 2);
$strDataFim = substr($strDataFim, 6, 4) . '-' . substr($strDataFim, 3, 2) . '-' . substr($strDataFim, 0, 2);

$qryFuncionario = "SELECT
                        f.id,
                        f.nome AS nome,
                        fun.descricao
                   FROM cs2.funcionario f
                   INNER JOIN cs2.funcao fun
                   ON fun.id = f.id_funcao
                   WHERE IF('$id_funcionario' = '0',0=0,f.id = '$id_funcionario')
                   AND id_funcao = '$id_funcao'
                   AND f.ativo = '$ativo'
                   ORDER BY f.nome ASC";
$rstFuncionario = mysql_query($qryFuncionario, $con);
?>
<button type="button" id="btnImprimir" class="btnImprimir pull-right" style="margin-top: 15px;">Imprimir Relatório
</button>
<div class="imprimir">
    <?php
    while ($arrFuncionario = mysql_fetch_array($rstFuncionario)) {
        geraHtml($arrFuncionario['id'], $strDataInicio, $strDataFim, $ativo, $con, $arrFuncionario['nome'], $arrFuncionario['funcao']);
    }
    ?>
    <div class="page-break exibirTotal hidden">

    </div>
</div>
<script>

    function number_format(number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    $(document).ready(function () {

        // FUNCAO PARA MARCAR OU DESMARCAR COMO PAGO A COMISSAO DAS AFILIAÇÕES
        $(document).on('click', '.iptPagoComissao', function () {

            var marcarPago = $(this).is(':checked');
            var idCadastro = $(this).data('id_cadastro');
            var valorComissao = $(this).data('valor_comissao');
            console.log(marcarPago + ' ' + idCadastro);

            $.ajax({

                url: '../php/ComissaoApoio.php',
                data: {
                    idCadastro: idCadastro,
                    marcarPago: marcarPago,
                    valorComissao: valorComissao,
                    action: 'comissaoAfiliacao'
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {

                }

            })

        });

        // FUNCAO PARA MARCAR OU DESMARCAR COMO PAGO A COMISSÃO DOS EQUIPAMENTOS
        $(document).on('click', '.iptPagoEquipamento', function () {

            var marcarPago = $(this).is(':checked');
            var idEquipamento = $(this).data('id_equipamento');
            var valorComissao = $(this).data('valor_comissao');

            $.ajax({

                url: '../php/ComissaoApoio.php',
                data: {
                    idEquipamento: idEquipamento,
                    marcarPago: marcarPago,
                    valorComissao: valorComissao,
                    action: 'comissaoEquipamento'
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {

                }

            })

        });


    });

    $(window).load(function () {

        var valorTotal = 0;
        $('.totalAPagar').each(function () {
            console.log($(this).html());
            $('.exibirTotal').append($(this).html() + '<br>');
        });
        var valorTotal = 0;
        $('.iptValorTotalSoma').each(function () {
            valorTotal = valorTotal + parseFloat($(this).val());
        });
        $('.exibirTotal').append('<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top: 5px;"><tr><td class="corpoTabela" align="">Sub Total</td><td class="corpoTabela totalFolha" align="right">' + number_format(valorTotal, 2, ',', '.') + '</td></tr></table>');

    });

    $(document).on('click', '.iptPagoBonus20', function(){

        var marcarPago = $(this).is(':checked');
        var idFuncionario = $(this).parent().parent().data('id_funcionario');
        var dataReferencia = $(this).parent().parent().data('data_referencia');

        $.ajax({

            url: '../php/ComissaoApoio.php',
            data: {
                idFuncionario: idFuncionario,
                marcarPago: marcarPago,
                action: 'comissaoBonus',
                tipo: '20',
                dataReferencia: dataReferencia
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {

            }

        });
    })

</script>