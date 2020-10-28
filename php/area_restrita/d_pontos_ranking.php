<?php

// require "connect/sessao.php";

?>
<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.6.0/moment-with-langs.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {

        $('select[name="franqueado"]').on('change', function() {
            var id_franquia = $(this).val();
            $.ajax({
                url: '../php/clientes/BuscaConsultorAgendador.php',
                data: {
                    id_franquia: id_franquia,
                    action: 'buscarFuncionario'
                },
                type: 'POST',
                dataType: 'text',
                success: function(data) {
                    var arrResult = data.split(';');
                    $('select[name="id_funcionario"]').html('');
                    $('select[name="id_funcionario"]').append('<OPTION value="0">TODOS</OPTION>' + arrResult[0]);
                    $('select[name="id_funcionario_check"]').html('');
                    $('select[name="id_funcionario_check"]').append('<OPTION value="0">TODOS</OPTION>' + arrResult[2]);
                }
            });
        });

        $('select[name="iptFuncao"]').on('change', function() {

            var idFuncao = $(this).val();
            var ativo = $('select[name="iptAtivo"] option:checked').val();

            $.ajax({
                url: '../php/clientes/BuscaConsultorAgendador.php',
                data: {
                    idFuncao: idFuncao,
                    action: 'buscarFuncionarioFuncao',
                    ativo: ativo
                },
                type: 'POST',
                dataType: 'json',
                success: function(data) {

                    $('select[name="id_funcionario"]').html('');
                    $('select[name="id_funcionario"]').append('<OPTION value="0">TODOS</OPTION>' + data);

                }
            })

        });

        $('select[name="iptAtivo"]').on('change', function() {

            var ativo = $(this).val();
            var idFuncao = $('select[name="iptFuncao"] option:checked').val();

            $.ajax({
                url: '../php/clientes/BuscaConsultorAgendador.php',
                data: {
                    idFuncao: idFuncao,
                    action: 'buscarFuncionarioFuncao',
                    ativo: ativo
                },
                type: 'POST',
                dataType: 'json',
                success: function(data) {

                    $('select[name="id_funcionario"]').html('');
                    $('select[name="id_funcionario"]').append('<OPTION value="0">TODOS</OPTION>' + data);

                }
            })

        });

        $('select[name="id_funcionario"]').on('change', function() {

            var id_funcionario = $(this).val();

            $.ajax({
                url: '../php/clientes/BuscaConsultorAgendador.php',
                data: {
                    id_funcionario: id_funcionario,
                    action: 'buscarFuncao'
                },
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    $('#funcaoFuncionario').html(data.funcao);
                }
            })

        });

        $("#tp_rel1").trigger("click");

    });

    function voltar_tela() {
        frm = document.relatorio;
        frm.action = 'painel.php?pagina1=area_restrita/d_pontos_ranking.php';
        frm.submit();
    }
</script>
<?php

$sql = "SELECT
        lc.id,
                DATE_FORMAT(lc.data_hora, '%d/%m/%Y') AS data_hora,
                lc.numero_serie,
                lc.codigo_barra,
                lc.motivo,
                f.nome AS funcionario_consignacao,
                if(lc.id_franquia = 4, 'Luciana', 'Wellington') AS fantasia,
                'Consignação' AS tipo_estorno
            FROM cs2.log_estorno_consignacao lc
            LEFT JOIN cs2.funcionario f
            ON f.id = lc.id_funcionario
            INNER JOIN cs2.franquia fr
            ON fr.id = lc.id_franquia

            UNION ALL
            SELECT
        ev.id,
                DATE_FORMAT(ev.data_hora, '%d/%m/%Y') AS data_hora,
                ev.numero_serie,
                ev.codigo_barra,
                ev.motivo,
                f.nome AS funcionario_consignacao,
                'Wellington' AS fantasia,
                'Venda' AS tipo_estorno
            FROM cs2.log_estorno_venda ev
            LEFT JOIN cs2.funcionario f
            ON f.id = ev.id_funcionario
            INNER JOIN cs2.franquia fr
            ON fr.id = ev.id_franquia


            ORDER BY id DESC
            LIMIT 100

            ;";
?>
<form method="post" action="painel.php?pagina1=area_restrita/rel_brincadeira.php" name='relatorio' id='relatorio'>
    <table id="escolha8" style="" border="0" width="70%" cellpadding="0" align="center">
        <tr class="titulo">
            <td colspan="2">Pontuação Ranking</td>
        </tr>
        <tr height='30'>
            <td class="subtitulodireita">Per&iacute;odo</td>
            <td class="subtitulopequeno">
                <input type="text" id="dataInicial" name="dataInicial" onKeyPress="return MM_formtCep(event,this,'##/##/####');">
                a
                <input type="text" id="dataFinal" name="dataFinal" onKeyPress="return MM_formtCep(event,this,'##/##/####');">
                &nbsp;
            </td>
        </tr>
        <tr height='30'>
            <td class="subtitulodireita">Franquia</td>
            <td class="subtitulopequeno">
                <select name="franquiaPontuacao" id="franquiaPontuacao" style="width:70%">
                    <option value='0'>TODAS</option>
                    <?php
                    $sql = "SELECT id, fantasia FROM franquia WHERE sitfrq=0 AND classificacao != 'J' ORDER BY id";
                    $resposta = mysql_query($sql, $con);
                    while ($array = mysql_fetch_array($resposta)) {
                        $id_franqueado = $array["id"];
                        $nome_franquia = $array["fantasia"];
                        if ($id_franqueado == $_REQUEST['franqueado']) {
                            echo "<option value=\"$id_franqueado\" selected>$id_franqueado - $nome_franquia</option>\n";
                        } else {
                            echo "<option value=\"$id_franqueado\"";
                            if ($id_franqueado == $matriz['id_franquia'])
                                echo "selected";
                            echo ">$id_franqueado - $nome_franquia</option>\n";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr height='30'>
            <td class="subtitulodireita">Ativo</td>
            <td height="22" colspan="3" class="subtitulopequeno">
                <select name="iptAtivo">
                    <option value="S">Sim</option>
                    <option value="N">Não</option>
                </select>
            </td>
        </tr>
        <tr height='30'>
            <td class="subtitulodireita">Funcionário</td>
            <td height="22" colspan="3" class="subtitulopequeno">
                <select name='id_funcionario' id='id_funcionario' style='width:65%'>
                    <option value="0">TODOS</option>
                </select>
            </td>
        </tr>
        <tr height='30'>
            <td colspan="2" class="titulo" align="center">
                <input type="submit" name="enviar" value="    Gerar Relatório    " />
                <input type="button" name="voltar" value="    Voltar    " onclick="voltar_tela()" />
            </td>
        </tr>
    </table>
</form>