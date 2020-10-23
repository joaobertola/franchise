<?php

require "connect/sessao.php";

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

    function voltar_tela() {
        frm = document.relatorio;
        frm.action = 'painel.php?pagina1=area_restrita/d_equipamentos0.php';
        frm.submit();
    }

    function verifica_cliente(codigo) {
        if (codigo != '') {
            var req = new XMLHttpRequest();
            req.open('GET', 'carrega_cliente.php?codigo=' + codigo, false);
            req.send(null);
            if (req.status != 200) return '';
            var retorno = req.responseText;
            var array = retorno.split('][');
            $('#nome_cliente').text(array[0]);
        }
    }

    function ConfirmarRelatorio() {

        var escolha = $('input:radio[name=tp_rel]:checked').val();
        var id_franquia = $('#franqueado').val();
        var codigo = $('#cliente').val();
        var nserie = $('#numero_serie').val();
        var idfuncionario = $('#id_funcionario').val();
        var dataI = $('#dataI').val();
        var dataF = $('#dataF').val();

        frm = document.relatorio;
        frm.action = 'painel.php?pagina1=area_restrita/d_equipamentos_relatorio2.php';
        frm.submit();

    }

    function mostrar(id) {

        document.getElementById('escolha1').style.display = 'none';
        document.getElementById('escolha2').style.display = 'none';
        document.getElementById('escolha3').style.display = 'none';
        document.getElementById('escolha4').style.display = 'none';
        document.getElementById('escolha5').style.display = 'none';
        document.getElementById('escolha6').style.display = 'none';
        document.getElementById('escolha7').style.display = 'none';
        document.getElementById('escolha8').style.display = 'none';

        if (document.getElementById(id).style.display == 'none') {
            document.getElementById(id).style.display = '';
        }

        if (id == 'escolha1') document.relatorio.cliente.focus();
        if (id == 'escolha2') document.relatorio.data2I.focus();
        if (id == 'escolha3') document.relatorio.numero_serie.focus();
        if (id == 'escolha4') document.relatorio.equipamento.focus();
        if (id == 'escolha5') document.relatorio.equipamento.focus();
        if (id == 'escolha6') document.relatorio.equipamento.focus();
        if (id == 'escolha7') document.relatorio.equipamento.focus();
        if (id == 'escolha8') document.relatorio.equipamento.focus();

    }

    function dataFormatada(d) {
        var nomeMeses = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12']
        var data = new Date(d);
        var dia = data.getDate();
        var mes = data.getMonth();
        mes = nomeMeses[mes];
        var ano = data.getFullYear();
        return [dia, mes, ano].join('/');
    }


    function estorno(id_franquia) {
        var numSerie = $("#numero_serie").val();

        $.ajax({
            url: '../php/area_restrita/ajaxEstornoProd.php',
            data: {
                numSerie: numSerie
            },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
                if (json != false) {
                    $('.idConsignacaoSpan').text(json.id_franquia_equipamento);
                    $('.DtConsignacaoSpan').text(moment(json.data).format("DD/MM/YYYY"));
                    $('.ConsultorSpan').text(json.nome);
                    $('.DescProdSpan').text(json.codigo_barra + " - " + json.descricao);

                    $('.idConsignacao').removeAttr('style');
                    $('.DtConsignacao').removeAttr('style');
                    $('.Consultor').removeAttr('style');
                    $('.DescProd').removeAttr('style');
                    $('.estornar').removeAttr('style');
                    $('.motivo').removeAttr('style');
                    $('.tipoConsignacao').removeAttr('style');
                } else {
                    alert("Não existe o produto consignado.");
                }
            }
        });
    }

    function confirmarEstorno(numero_serie) {

        var id_franquia = <?php echo $id_franquia ?>;

        if (numero_serie == '') {
            alert('Número de série não pode ser vázio!');
            return false;
        }

        if ($('#iptMotivo').val().length < 5) {
            alert('O Motivo deve ter no mínimo 5 caracteres');
            return false;
        }

        $.ajax({
            url: '../php/BuscaProduto.php',
            data: {
                numero_serie: numero_serie,
                action: 'estornarConsignacao',
                id_franquia: id_franquia,
                motivo: $('#iptMotivo').val(),
                tipoEstorno: $('input[name="iptTipoEstorno"]:checked').val()
            },
            type: 'POST',
            dataType: 'json',
            success: function(data) {

                if (data == 1) {
                    alert('Consignação estornada com sucesso!');
                    location.reload();
                }

            }
        })
    }

    function verificarSenha() {

        var id_franquia = <?php echo $id_franquia ?>;
        if (id_franquia == 163) {
            confirmarEstorno($('#numero_serie').val());
        } else {

            $.ajax({
                url: '../php/BuscaProduto.php',
                data: {
                    action: 'retornaSenha'
                },
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    if (data == $('#iptSenha').val()) {
                        console.log('chegou');
                        confirmarEstorno($('#numero_serie').val());
                    } else {
                        alert('A Senha digitada está incorreta');
                    }
                }
            })
        }
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

$qry = mysql_query($sql, $con);

$sqlFuncao = "
                SELECT
                    id,
                    descricao
                FROM cs2.funcao
                WHERE ativo = '1'
                ORDER BY descricao
                ";
$resFuncao = mysql_query($sqlFuncao, $con);
?>
<form method="post" action="#" name='relatorio' id='relatorio'>
    <table width=70% border="0" align="center">
        <tr class="titulo">
            <td colspan="2">Relat&oacute;rios</td>
        </tr>
        <tr>
            <td class="campoesquerda" colspan="2">&nbsp;</td>
        </tr>

        <tr>
            <td class="campoesquerda" colspan="2" style="text-align: center">
                <input type="radio" name='tp_rel' id="tp_rel1" value="1" onClick="javascript: mostrar('escolha1');return true;">Cliente &nbsp;&nbsp;
                <input type="radio" name='tp_rel' id="tp_rel2" value="2" onClick="javascript: mostrar('escolha2');return true;">Franquia &nbsp;&nbsp;
                <input type="radio" name='tp_rel' id="tp_rel3" value="3" onClick="javascript: mostrar('escolha3');return true;">N&deg; S&eacute;rie &nbsp;&nbsp;
                <input type="radio" name='tp_rel' id="tp_rel4" value="4" onClick="javascript: mostrar('escolha4');return true;">Produtos &nbsp;&nbsp;
                <?php if ($id_franquia == 163) { ?>
                    <input type="radio" name='tp_rel' id="tp_rel5" value="5" onClick="javascript: mostrar('escolha5');return true;">Funcionários/Premiações &nbsp;&nbsp;
                    <input type="radio" name='tp_rel' id="tp_rel8" value="8" onClick="javascript: mostrar('escolha8');return true;">Pontuação Brincadeira &nbsp;&nbsp;
                <?php } ?>
                <input type="radio" name='tp_rel' id="tp_rel6" value="6" onClick="javascript: mostrar('escolha6');return true;">Check List Equipamentos/Produtos
                <input type="radio" name='tp_rel' id="tp_rel6" value="7" onClick="javascript: mostrar('escolha7');return true;">Relatório Estorno
            </td>
        </tr>
    </table>

    <table id="escolha1" style="display:none;" border="0" width="70%" cellpadding="0" align="center" />
    <tr height='30'>
        <td class="subtitulodireita">Cliente</td>
        <td class="subtitulopequeno">
            <input type="text" name="cliente" id="cliente" value="" maxlength="6" onblur="verifica_cliente(this.value)">
            <span id="nome_cliente"></span>
        </td>
    </tr>
    <tr height='30'>
        <td colspan="2" class="titulo" align="center">
            <input type="button" name="enviar" value="    Gerar Relatório    " onclick="ConfirmarRelatorio()" />
            <input type="button" name="voltar" value="    Voltar    " onclick="voltar_tela()" />
        </td>
    </tr>
    </table>

    <table id="escolha2" style="display:none;" border="0" width="70%" cellpadding="0" align="center" />
    <tr height='30'>
        <td class="subtitulodireita">Per&iacute;odo</td>
        <td class="subtitulopequeno">
            <input type="text" id="data2I" name="data2I" onKeyPress="return MM_formtCep(event,this,'##/##/####');">
            a
            <input type="text" id="data2F" name="data2F" onKeyPress="return MM_formtCep(event,this,'##/##/####');">
        </td>
    </tr>
    <tr height='30'>
        <td class="subtitulodireita">Franquia</td>
        <td class="subtitulopequeno">
            <select name="franqueado" id="franqueado" style="width:70%">
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
        <td class="subtitulodireita">Funcion&aacute;rio</td>
        <td height="22" colspan="3" class="subtitulopequeno">
            <select name='id_funcionario' id='id_funcionario' style='width:65%'>
                <option value="0">TODOS</option>
            </select>
        </td>
    </tr>
    <tr height='30'>
        <td colspan="2" class="titulo" align="center">
            <input type="button" name="enviar" value="    Gerar Relatório    " onclick="ConfirmarRelatorio()" />
            <input type="button" name="voltar" value="    Voltar    " onclick="voltar_tela()" />
        </td>
    </tr>
    </table>

    <table id="escolha3" style="display:none;" border="0" width="70%" cellpadding="0" align="center" />
    <tr height='30'>
        <td class="subtitulodireita">N&deg; de S&eacute;rie</td>
        <td class="subtitulopequeno">
            <input type="text" value="" name="numero_serie" id="numero_serie" />
        </td>
    </tr>
    <tr height='30'>
        <td colspan="2" class="titulo" align="center">
            <input type="button" name="enviar" value="    Gerar Relatório    " onclick="ConfirmarRelatorio()" />
            <input type="button" name="enviar" value="    Estornar    " onclick="estorno(<?php echo $id_franquia ?>)" />
            <input type="button" name="voltar" value="    Voltar    " onclick="voltar_tela()" />
        </td>
    </tr>
    <tr>
    <tr height='30' class="idConsignacao hide" style="display: none;">
        <td class="subtitulo" style="text-align: right !important; padding-right: 10px;">Consignação</td>
        <td colspan="" class="subtitulo" align="center" style="text-align: left !important">
            <span class="idConsignacaoSpan" style="font-weight: normal; padding-left: 10px;"></span>
        </td>
    </tr>
    <tr height='30' class="Consultor hide" style="display: none;">
        <td class="subtitulo" style="text-align: right !important; padding-right: 10px;">Consultor</td>
        <td colspan="" class="subtitulo" align="center" style="text-align: left !important">
            <span class="ConsultorSpan" style="font-weight: normal; padding-left: 10px;"></span>
        </td>
    </tr>
    <tr height='30' class="DtConsignacao hide" style="display: none;">
        <td class="subtitulo" style="text-align: right !important; padding-right: 10px;">Data Consignação</td>
        <td colspan="" class="subtitulo" align="center" style="text-align: left !important">
            <span class="DtConsignacaoSpan" style="font-weight: normal; padding-left: 10px;"></span>
        </td>
    </tr>
    <tr height='30' class="DescProd hide" style="display: none;">
        <td class="subtitulo" style="text-align: right !important; padding-right: 10px;">Descrição do Produto</td>
        <td colspan="" class="subtitulo" align="center" style="text-align: left !important">
            <span class="DescProdSpan" style="font-weight: normal; padding-left: 10px;"></span>
        </td>
    </tr>
    <tr height='30' class="tipoConsignacao hide" style="display: none;">
        <td class="subtitulo" style="text-align: right !important; padding-right: 10px;">Tipo</td>
        <td colspan="" class="subtitulo" align="center" style="text-align: left !important">
            <input type="radio" id="iptTipoEstorno" name="iptTipoEstorno" value="C" checked>Estornar Consignação
            &nbsp;&nbsp;
            <?php if ($id_franquia == 163) { ?>
                <input type="radio" id="iptTipoEstorno" name="iptTipoEstorno" value="V">Estornar Produto de Venda
            <?php } ?>
        </td>
    </tr>
    <tr height='30' class="motivo hide" style="display: none;">
        <td class="subtitulo" style="text-align: right !important; padding-right: 10px;">Motivo</td>
        <td colspan="" class="subtitulo" align="center" style="text-align: left !important">
            <textarea style="resize: none; width: 280px;" name="iptMotivo" id="iptMotivo" rows="2"></textarea>
        </td>
    </tr>
    <tr height='30' class="estornar hide" style="display: none;">
        <td class="subtitulo" style="text-align: right !important;"><?php echo $id_franquia == 163 ? '' : 'Senha:' ?></td>
        <td colspan="" class="subtitulo" style="text-align: left !important">
            <?php if ($id_franquia != 163) { ?>
                <input type="password" name="iptSenha" id="iptSenha" />
            <?php } ?>
            <input type="button" name="estornar" value="    Confirmar    " onclick="verificarSenha()" />
        </td>
    </tr>
    </table>

    <table id="escolha4" style="display:none;" border="0" width="70%" cellpadding="0" align="center" />
    <tr height='30'>
        <td class="subtitulodireita">Equipamento</td>
        <td class="subtitulopequeno">
            <?php
            echo "<select name='equipamento' id='equipamento' onblur='pesquisa_produto(this.value)' >";
            $sql = "SELECT codigo, codigo2, descricao FROM cs2.produto
                            WHERE exibir_tabela = 'S' ORDER BY descricao";
            $resposta = mysql_query($sql, $con);
            $txt_prod = "<option value='0'>TODOS</option>";
            while ($array = mysql_fetch_array($resposta)) {
                $codigo = $array["codigo"];
                $codigo2 = $array["codigo2"];
                $descricao = $array["descricao"];
                $txt_prod .= "<option value=\"$codigo\">$descricao - $codigo2</option>\n";
            }
            echo $txt_prod;
            echo "</select>";
            ?>
        </td>
    </tr>
    <tr height='30'>
        <td class="subtitulodireita">Per&iacute;odo</td>
        <td class="subtitulopequeno">
            <input type="text" id="data4I" name="data4I" onKeyPress="return MM_formtCep(event,this,'##/##/####');">
            a
            <input type="text" id="data4F" name="data4F" onKeyPress="return MM_formtCep(event,this,'##/##/####');">
        </td>
    </tr>
    <tr height='30'>
        <td colspan="2" class="titulo" align="center">
            <input type="button" name="enviar" value="    Gerar Relatório    " onclick="ConfirmarRelatorio()" />
            <input type="button" name="voltar" value="    Voltar    " onclick="voltar_tela()" />
        </td>
    </tr>
    </table>

    <table id="escolha6" style="display:none;" border="0" width="70%" cellpadding="0" align="center" />
    <tr height='30'>
        <td class="subtitulodireita">Franquia</td>
        <td class="subtitulopequeno">
            <select name="franqueado" id="franqueado" style="width:70%">
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
        <td class="subtitulodireita">Funcionário</td>
        <td height="22" colspan="3" class="subtitulopequeno">
            <select name='id_funcionario_check' id='id_funcionario_check' style='width:65%'>
                <option value="0">TODOS</option>
            </select>
        </td>
    </tr>
    <tr height='30'>
        <td class="subtitulodireita">Selecione o Equipamento/Produto</td>
        <td height="22" colspan="3" class="subtitulopequeno">
            <?php
            echo "<select name='codigo_barras_check' id='codigo_barras' onChange='pesquisa_produto(this.value)' >";
            $sql = "SELECT codigo, codigo2, descricao FROM cs2.produto
                                WHERE exibir_tabela = 'S' ORDER BY descricao";
            $resposta = mysql_query($sql, $con);
            $txt_prod = "<option value='0'>Selecione o Equipamento</option>";
            while ($array = mysql_fetch_array($resposta)) {
                $codigo = $array["codigo"];
                $codigo2 = $array["codigo2"];
                $descricao = $array["descricao"];
                $txt_prod .= "<option value=\"$codigo\">$descricao - $codigo2</option>\n";
            }
            echo $txt_prod;
            echo "</select>";
            ?>
        </td>
    </tr>
    <tr height='30'>
        <td colspan="2" class="titulo" align="center">
            <input type="button" name="enviar" value="    Gerar Relatório    " onclick="ConfirmarRelatorio()" />
            <input type="button" name="voltar" value="    Voltar    " onclick="voltar_tela()" />
        </td>
    </tr>


    </table>
    <table id="escolha5" style="display:none;" border="0" width="70%" cellpadding="0" align="center" />
    <tr height='30'>
        <td class="subtitulodireita">Per&iacute;odo</td>
        <td class="subtitulopequeno">
            <input type="text" id="data2I" name="data2I" onKeyPress="return MM_formtCep(event,this,'##/##/####');">
            a
            <input type="text" id="data2F" name="data2F" onKeyPress="return MM_formtCep(event,this,'##/##/####');">
            &nbsp;
            <select id="iptTipoRelatorio" name="iptTipoRelatorio">
                <option value="S">Semanal</option>
                <option value="M">Mensal</option>
                <option value="C">CONTÁBIL</option>
            </select>
        </td>
    </tr>
    <tr height='30'>
        <td class="subtitulodireita">Franquia</td>
        <td class="subtitulopequeno">
            <select name="franqueado" id="franqueado" style="width:70%">
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
        <td class="subtitulodireita">Função</td>
        <td height="22" colspan="3" class="subtitulopequeno">
            <select name="iptFuncao">
                <option value="0">TODOS</option>
                <?php while ($arrFuncao = mysql_fetch_array($resFuncao)) { ?>
                    <option value="<?php echo $arrFuncao['id'] ?>"><?php echo $arrFuncao['descricao'] ?></option>
                <?php } ?>
                </option>
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
            <input type="button" name="enviar" value="    Gerar Relatório    " onclick="ConfirmarRelatorio()" />
            <input type="button" name="voltar" value="    Voltar    " onclick="voltar_tela()" />
        </td>
    </tr>
    </table>
    <table id="escolha8" style="display:none;" border="0" width="70%" cellpadding="0" align="center">
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
        <!--
        <tr height='30'>
            <td class="subtitulodireita">Função</td>
            <td height="22" colspan="3" class="subtitulopequeno">
                <select name="iptFuncao">
                    <option value="9">Atendimento Comercial Externo</option>
                </select>
            </td>
        </tr>
    -->
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
                <input type="button" name="enviar" value="    Gerar Relatório    " onclick="ConfirmarRelatorio()" />
                <input type="button" name="voltar" value="    Voltar    " onclick="voltar_tela()" />
            </td>
        </tr>
    </table>

    <table id="escolha7" style="display:none;" border="0" width="70%" cellpadding="0" align="center" />
    <tr height='30'>
        <td colspan="7" class="titulo">Relatório de Estorno</td>
    </tr>
    <tr height='30'>
        <td class="subtitulo">Data Estorno</td>
        <td class="subtitulo">Número Série</td>
        <td class="subtitulo">Código Barra</td>
        <td class="subtitulo">Funcionário Consignação</td>
        <td class="subtitulo">Usuário Estorno</td>
        <td class="subtitulo">Motivo</td>
        <td class="subtitulo">Tipo</td>
    </tr>
    <?php while ($resultLog = mysql_fetch_array($qry)) { ?>
        <tr>
            <td class="corpoTabela"><?php echo $resultLog['data_hora'] ?></td>
            <td class="corpoTabela"><?php echo $resultLog['numero_serie'] ?></td>
            <td class="corpoTabela"><?php echo $resultLog['codigo_barra'] ?></td>
            <td class="corpoTabela"><?php echo $resultLog['funcionario_consignacao'] ?></td>
            <td class="corpoTabela"><?php echo $resultLog['fantasia'] ?></td>
            <td class="corpoTabela"><?php echo $resultLog['motivo'] ?></td>
            <td class="corpoTabela"><?php echo $resultLog['tipo_estorno'] ?></td>
        </tr>
    <?php } ?>
    </table>

</form>