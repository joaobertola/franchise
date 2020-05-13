<?php
// require "connect/sessao.php";

$hoje = date('d/m/Y');

$acao = $_REQUEST['acao'];
?>
<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>
<script>

    $(document).ready(function () {

        $('#id_consignacao').focus();
        $('#id_consignacao').on('change', function () {

            $.ajax({
                url: '../php/BuscaProduto.php',
                data: {
                    action: 'buscaVendaConsignacao',
                    id: $(this).val()
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    if (data.result == 1) {
                        $('#codigo_barras').val(data.codigo_barra);
                        $('#numero_serie').val(data.numero_serie);
                        $('#codigo_barras').trigger('blur');
                    } else {
                        alert('Atenção, Nenhuma consignação cadastrada!');
                        $('#codigo_barras').val('');
                        $('#numero_serie').val('');
                        $('#id_consignacao').focus();
                        $('#codigo_barras').trigger('blur');
                        $('#nome_produto').text('');
                        $('#valor_produto').val('');
                    }
                }
            })

        });
    });

    function alterarVendedor(id) {

        var form = document.forms.frmAlterarVendedor;
        form.id.value = id;
        form.submit();

    }

    function verifica_cliente(codigo) {
        if (codigo != '') {
            var req = new XMLHttpRequest();
            var idfranquia;
            req.open('GET', 'carrega_cliente.php?codigo=' + codigo, false);
            req.send(null);
            if (req.status != 200)
                return '';
            var retorno = req.responseText;
            var array = retorno.split('][');
            $('#nome_cliente').text(array[0] + ' / ' + array[2]);
            idfranquia = array[1].trim();
            $('#id_franquia').val(array[1].trim());
            $('#codloja').val(array[3].trim());

            $.ajax({
                url: '../php/clientes/BuscaConsultorAgendador.php',
                data: {
                    id_franquia: idfranquia,
                    action: 'buscarFuncionario'
                },
                type: 'POST',
                dataType: 'text',
                success: function (data) {
                    var arrResult = data.split(';');
                    $('select[name="id_consultores"]').html('');
                    $('select[name="id_consultores"]').append('<OPTION value="0">TODOS</OPTION>' + arrResult[0]);
                }
            });

            $.ajax({
                url: '../php/clientes/BuscaConsultorAgendador.php',
                data: {
                    id_franquia: idfranquia,
                    action: 'buscarConsultorAgendador',
                    ativo: 'S'
                },
                type: 'POST',
                dataType: 'text',
                success: function (data) {
                    var arrResult = data.split(';');
                    $('select[name="id_agendadores"]').html('');
                    $('select[name="id_agendadores"]').append('<OPTION value="0">TODOS</OPTION>' + arrResult[0]);
                }
            });
        }
    }

    function voltar_tela() {
        frm = document.listacompra;
        frm.action = 'painel.php?pagina1=area_restrita/d_equipamentos0.php';
        frm.submit();
    }

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

    function mascara(o, f) {
        v_obj = o
        v_fun = f
        setTimeout("execmascara()", 1)
    }

    function execmascara() {
        v_obj.value = v_fun(v_obj.value)
    }
    // formato mascara data
    function data(v) {
        v = v.replace(/\D/g, "")                    //Remove tudo o que não é dígito
        v = v.replace(/(\d{2})(\d)/, "$1/$2")
        v = v.replace(/(\d{2})(\d)/, "$1/$2")

        return v
    }

    window.onload = function () {
        document.listacompra.cliente.focus();
    }

    function maskIt(w, e, m, r, a) {
        // Cancela se o evento for Backspace
        if (!e)
            var e = window.event
        if (e.keyCode)
            code = e.keyCode;
        else if (e.which)
            code = e.which;
        // Variáveis da função
        var txt = (!r) ? w.value.replace(/[^\d]+/gi, '') : w.value.replace(/[^\d]+/gi, '').reverse();
        var mask = (!r) ? m : m.reverse();
        var pre = (a) ? a.pre : "";
        var pos = (a) ? a.pos : "";
        var ret = "";
        if (code == 9 || code == 8 || txt.length == mask.replace(/[^#]+/g, '').length)
            return false;
        // Loop na máscara para aplicar os caracteres
        for (var x = 0, y = 0, z = mask.length; x < z && y < txt.length; ) {
            if (mask.charAt(x) != '#') {
                ret += mask.charAt(x);
                x++;
            } else {
                ret += txt.charAt(y);
                y++;
                x++;
            }
        }
        // Retorno da função
        ret = (!r) ? ret : ret.reverse()
        w.value = pre + ret + pos;
    }

    function number_format(number, decimals, dec_point, thousands_sep) {

        number = (number + '')
                .replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + (Math.round(n * k) / k)
                            .toFixed(prec);
                };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
                .split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '')
                .length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1)
                    .join('0');
        }
        return s.join(dec);
    }

    function filtraCampo(campo) {
        var s = "";
        var cp = "";
        vr = campo.value;
        tam = vr.length;
        for (i = 0; i < tam; i++) {
            if (vr.substring(i, i + 1) != "/" && vr.substring(i, i + 1) != "-" && vr.substring(i, i + 1) != "." && vr.substring(i, i + 1) != ",") {
                s = s + vr.substring(i, i + 1);
            }
        }
        campo.value = s;
        return cp = campo.value
    }

    function formataValor(campo) {
        campo.value = filtraCampo(campo);
        vr = campo.value;
        tam = vr.length;
        if (tam <= 2) {
            campo.value = vr;
        }
        if ((tam > 2) && (tam <= 5)) {
            campo.value = vr.substr(0, tam - 2) + ',' + vr.substr(tam - 2, tam);
        }
        if ((tam >= 6) && (tam <= 8)) {
            campo.value = vr.substr(0, tam - 5) + '.' + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
        }
        if ((tam >= 9) && (tam <= 11)) {
            campo.value = vr.substr(0, tam - 8) + '.' + vr.substr(tam - 8, 3) + '.' + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
        }
        if ((tam >= 12) && (tam <= 14)) {
            campo.value = vr.substr(0, tam - 11) + '.' + vr.substr(tam - 11, 3) + '.' + vr.substr(tam - 8, 3) + '.' + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
        }
        if ((tam >= 15) && (tam <= 18)) {
            campo.value = vr.substr(0, tam - 14) + '.' + vr.substr(tam - 14, 3) + '.' + vr.substr(tam - 11, 3) + '.' + vr.substr(tam - 8, 3) + '.' + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
        }
    }

    function pesquisa_produto(codigo) {
        if (codigo != '') {
            var req = new XMLHttpRequest();
            req.open('GET', 'carrega_produto.php?codigo=' + codigo, false);
            req.send(null);
            if (req.status != 200)
                return '';
            var retorno = req.responseText;

            if (retorno.trim() == '') {
                alert('PRODUTO NÃO CADASTRADO');
                $('#codigo_barras').focus();
            } else {
                var array = retorno.split('][');
                $('#nome_produto').text(array[0]);
                $('#valor_produto').val(number_format(array[1], 2, ',', '.'));
                $('#codigo_barras').val(array[2]);
                $('#valor_comissao').text(array[3]);
                console.log(array);
                if (array[4] == 'S') {
                    // venda por quantidade
                    $('.numero_serie').attr('style', 'display: none;');
                    //$('#qtd').val('');
                    $('.quantidade').removeAttr('style');
                } else {
                    // venda unitaria
                    $('.quantidade').attr('style', 'display: none;');
                    //$('#numero_serie').val('');
                    $('#qtd').val(1);
                    $('.numero_serie').removeAttr('style');
                }
                $('#id_produto_ref').val(array[5]);
            }
        }
    }

    function grava_produto() {

        var codloja = $('#codloja').val();
        var id_produto  = $('#id_produto_ref').val();
        var codigo = $('#codigo_barras').val();
        var nserie = $('#numero_serie').val();
        var idvenda = $('#iptIdVenda').val();
        var valor = $('#valor_produto').val();
        var id_agendador = $('#id_agendadores').val();
        var id_consultor = $('#id_consultores').val();
        var data_venda = $('#data_venda').val();
        var qtd = $('#qtd').val();

        if (nserie != '') {
            qtd = 1;
        }

        $.ajax({
            url: "salva_produto_cliente.php", //teste somente para retornar success
            data: {
                codloja: codloja,
                codigo_barra: codigo,
                numero_serie: nserie,
                id_produto: id_produto,
                id_venda: idvenda,
                valor: valor,
                id_agendador: id_agendador,
                id_consultor: id_consultor,
                data_venda: data_venda,
                qtd: qtd
            },
            type: "POST",
            async: false,
            success: function (dataResult) {

                var retorno2 = dataResult;
                var array = retorno2.split('][');
                var idvenda2 = (array[0]).trim();
                var tabela = array[1];

                if (idvenda2 == 0) {
                    alert('Já existe uma venda para este número de série para: ' + tabela);
                    return false;
                } else {

                    $(".divNumeroPedido").text(idvenda2);
                    $("input[name=iptIdVenda]").val(idvenda2);
                    $('#tabela_compra').html(tabela);
                    $('#codigo_barras').val('');
                    $('#numero_serie').val('');
                    $('#codigo_barras').focus();
                }

            }
        });

        if ($('#id_consignacao').val() != '') {
            $.ajax({
                url: '../php/BuscaProduto.php',
                data: {
                    action: 'excluiConsignacao',
                    id: $('#id_consignacao').val()
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {

                }
            })
        }

    }

    function ConfirmarRecebimento(Venda) {

        // TODO: AQUI
        var valorVenda = $('.vlrTotalOriginal').val();
        $('input[name="vlr_recebido"]').val(valorVenda);
        $('#iptSaldo').val(valorVenda);
        if (Venda) {
            document.getElementById("pagamentos").style.display = "block";
            frm = document.listacompra;
            frm.forma_pgto.focus();
        }
    }


    function seleciona_pgto() {
        frm = document.listacompra;
        frm.vencimento.focus();
    }

    function FinalizarPedido() {
        var idvenda = $('#iptIdVenda').val();
        var valor_desconto = $('#valor_desconto').val();
        var data_compra = $('#data_compra').val();
        var codloja = $('#codloja').val();

        $.ajax({
            url: "finalizar_venda_cliente.php", //teste somente para retornar success
            data: {
                iptIdVenda: idvenda,
                valor_desconto: valor_desconto,
                data_compra: data_compra,
                codloja: codloja
            },
            type: "POST",
            async: false,
            success: function (dataResult) {
                console.log(dataResult);
                if (dataResult == '1') {
                    alert('Registro Gravado com Sucesso');
                    window.open("../php/painel.php?pagina1=clientes/a_equipamentos.php&codigo=" + codloja);
                    window.location.href = "../php/painel.php?pagina1=area_restrita/d_equipamentos_venda.php";
                }
            }
        });
    }
</script>

<form method="post" action="#" name='listacompra' id='listacompra'>
    
    <input type="hidden" name="id_produto_ref" id="id_produto_ref" value=""/>
    
    <table width=70% border="0" align="center">
        <thead>
            <tr class="titulo">
                <td colspan="2">Venda de Equipamentos/Produtos para Clientes</td>
                <input type="hidden" name="id_franquia" id="id_franquia" value="">
                <input type="hidden" name="codloja" id="codloja" value="">
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="subtitulodireita" colspan="2">
                    PEDIDO Nº.: <span class="divNumeroPedido"></span>
                    <input type="hidden" name="iptIdVenda" id="iptIdVenda" value="">
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita" width='20%'>ID Consignação</td>
                <td class="subtitulopequeno">
                    <input type="text" name="id_consignacao" id="id_consignacao" value="" maxlength="6"">
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita" width='20%'>Cliente</td>
                <td class="subtitulopequeno">
                    <input type="text" name="cliente" id="cliente" value="" maxlength="6"
                           onblur="verifica_cliente(this.value)">
                    <span id="nome_cliente"></span>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Código de Barras:</td>
                <td class="subtitulopequeno">
                    <input type="hidden" name="codloja" id="codloja" value="<?php echo $_REQUEST['codigo']; ?>"/>
                    <input type="hidden" name="id_venda" id="id_venda_registro" value=""/>
                    <input type="text" value="" name="codigo_barras" id="codigo_barras"
                           onblur="pesquisa_produto(this.value)"/>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Descri&ccedil;&atilde;o:</td>
                <td class="subtitulopequeno">
                    <span id="nome_produto"></span>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Valor Produto:</td>
                <td class="subtitulopequeno">
                    <input type="text" name="valor_produto" onKeydown="FormataValor(this, 20, event, 2)" id="valor_produto"
                           size="16" maxlength="15"/>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Comiss&atilde;o:</td>
                <td class="subtitulopequeno">
                    <span id="valor_comissao"></span>
                </td>
            </tr>
            <tr class="numero_serie">
                <td class="subtitulodireita">Nº. de Série:</td>
                <td class="subtitulopequeno">
                    <input type="text" value="" name="numero_serie" id="numero_serie"/>
                </td>
            </tr>
            <tr class="quantidade" style="display: none;">
                <td class="subtitulodireita">Quantidade</td>
                <td class="subtitulopequeno">
                    <input type="text" value="" name="qtd" id="qtd"/>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Agendador(a):</td>
                <td class="subtitulopequeno">
                    <select name="id_agendadores" id="id_agendadores">
                        <option value="0">Selecione</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Consultor(a):</td>
                <td class="subtitulopequeno">
                    <select name="id_consultores" id="id_consultores">
                        <option value="0">Selecione</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Data Venda:</td>
                <td class="subtitulopequeno">
                    <input name="data_venda" type="text" id="data_venda" size="15" maxlength="10" class="datepicker"
                           onFocus="this.className = 'boxover'" onKeyPress="return MM_formtCep(event, this, '##/##/####');"
                           onBlur="this.className = 'boxnormal'"/>
                </td>
            </tr>
        </tbody>
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="button" name="enviar" value="    Gravar Produto    " onclick="grava_produto()" class=""/>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" name="voltar" value="    Voltar    " onclick="voltar_tela()" class=""/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span id="tabela_compra"></span>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <input type="button" id="pgto" name="pgto" value="  Confirmar Recebimento  "
                       onclick="ConfirmarRecebimento('1')"/>
            </td>
        </tr>
        <tr align="center">
            <td colspan="2" align="center">
                <div id="pagamentos" style="display:none">
                    <table border="0" width="70%" align="center" id="tblPagamentos">
                        <tr>
                            <td width="30%" bgcolor="#E8E8E8" align="left">Forma de Pagamento</td>
                            <td width="30%" bgcolor="#E8E8E8" align="left">Vencimento</td>
                            <td width="30%" bgcolor="#E8E8E8" align="left">Valor</td>
                            <td width="10%" bgcolor="#E8E8E8" align="left">&nbsp;</td>
                            <td width="10%" bgcolor="#E8E8E8" align="left">&nbsp;</td>
                        </tr>
                        <tr class="formPagto">
                            <td width="45%">
                                <select name="forma_pgto[]" id="forma_pgto" onchange="seleciona_pgto()" class="forma_pgto">
                                    <option value="0">... Selecione ...</option>
                                    <option value="1">DINHEIRO</option>
                                    <option value="2">CHEQUE</option>
                                    <option value="3">CART&Atilde;O CR&Eacute;DITO</option>
                                    <option value="5">CART&Atilde;O D&Eacute;BITO</option>
                                    <option value="4">BOLETO</option>
                                    <option value="6">ANTECIPA&Ccedil;&Atilde;O</option>
                                    <option value="7">TRANSFER&Ecirc;NCIA</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="vencimento[]" id="vencimento" class="vencimento"
                                       value="" onKeyPress="mascara(this, data)"/>
                            </td>
                            <td>
                                <input type="text" name="vlr_recebido[]" id="vlr_recebido" value="" class="vlr_recebido"
                                       onKeydown="FormataValor(this, 20, event, 2)" style="text-align:right"/>
                            </td>
                            <td>
                                <input type="button" name="OK" value="[OK]" id="btnReceberAgora"
                                       class="btnReceberAgora"/>
                            </td>

                        </tr>

                    </table>
                    <table>
                        <tr>
                            <td colspan="4" align="right"><button type="button" id="btnSalvarTodos">[ OK P/ TODOS ]</button></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr align="center">
            <td colspan="2" align="center">
                <span id="pgtos"></span>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr align="center">
            <td colspan="2" align="center">
                <div id="finalizar" style="display:none">
                    <table border="0" width="50%" align="center">
                        <tr>
                            <td>Data Compra</td>
                        </tr>
                        <tr>
                            <td><input type="text" name="data_compra" id="data_compra"
                                       value="<?php echo date('d/m/Y'); ?>" onKeyPress="mascara(this, data)"/></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="button" value=" Finalizar Pedido "
                                                   onclick="FinalizarPedido()"/></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</form>
<input type="hidden" id="iptSaldo" name="iptSaldo">
<input type="hidden" id="iptVencimento" name="iptVencimento">
<script>
    $(document).on('change', '.forma_pgto', function () {

        /**
         * 1 - DINHEIRO
         * 2 - CHEQUE
         * 3 - CARTÃO DE CRÉDITO
         * 4 - CARTÃO DE DÉBITO
         * 5 - BOLETO
         * 6 - ANTECIPAÇÃO
         *
         */
        var idFormaPagamento = $(this).val();
        var valor = $('#totalGeral_').html();
        var hoje = $('#data_venda').val();
        valor = valor.replace('Total Geral R$ ','');
        valor = valor.replace('.','');
        valor = valor.replace(',','.');

        switch (idFormaPagamento) {

            case '1':

                $('.formPagto').remove();
                var PrimeiroVencimento = $('#iptVencimento').val();
                if (PrimeiroVencimento != 0) {
                    PrimeiroVencimento = PrimeiroVencimento.split('/');
                    var dia = parseInt(PrimeiroVencimento[0]);
                    var mes = parseInt(PrimeiroVencimento[1]) - 1;
                    var ano = parseInt(PrimeiroVencimento[2]);
                } else {
                    var PrimeiroV = hoje.split('/');
                    var dia = parseInt(PrimeiroV[0]);
                    if ( dia < 10 ) dia = '0'+dia;
                    var mes = parseInt(PrimeiroV[1]) - 1;
                    var ano = parseInt(PrimeiroV[2]);
                }

                mes = (mes + 1);
                if (mes < 10) {
                    mes = '0' + mes;
                }

                var d = dia; // grava o dia em outra variável para poder recuperar depois
                var data_ini = dia + '/' + mes + '/' + ano; // data para o boleto de pagamento à vista (recupere system nível 3)

                var html = '<tr class="formPagto"><td width="45%"><select name="forma_pgto[]" id="forma_pgto" onchange="seleciona_pgto()" class="forma_pgto"><option value="0">... Selecione ...</option><option value="1" selected>DINHEIRO</option><option value="2">CHEQUE</option><option value="3">CART&Atilde;O CR&Eacute;DITO</option><option value="5">CART&Atilde;O D&Eacute;BITO</option><option value="4">BOLETO</option><option value="6">ANTECIPA&Ccedil;&Atilde;O</option></select></td><td><input type="text" name="vencimento[]" class="vencimento" id="vencimento"value="' + data_ini + '" onKeyPress="mascara(this,data)"/></td><td><input type="text" name="vlr_recebido[]" id="vlr_recebido" class="vlr_recebido" value="' + number_format(valor, 2, ',', '.') + '" onKeydown="FormataValor(this,20,event,2)" style="text-align:right"/></td><td><input type="button" name="OK" value="[OK]" class="btnReceberAgora"/></td></tr>'
                $('#tblPagamentos').append(html);

                break;

            case '2':
                
                $('.formPagto').remove();
                var html = '';
                var PrimeiroVencimento = $(this).parent().parent().find('.vencimento').val();
                var valor = valor / 6;

                if (PrimeiroVencimento != 0) {
                    PrimeiroVencimento = PrimeiroVencimento.split('/');
                    var dia = parseInt(PrimeiroVencimento[0]);
                    var mes = parseInt(PrimeiroVencimento[1]) - 1;
                    var ano = parseInt(PrimeiroVencimento[2]);
                } else {
                    var PrimeiroV = hoje.split('/');
                    var dia = parseInt(PrimeiroV[0]);
                    var mes = parseInt(PrimeiroV[1]) - 1;
                    var ano = parseInt(PrimeiroV[2]);
                }


                var d = dia; // grava o dia em outra variável para poder recuperar depois
                var data_ini = dia + '/' + (mes + 2) + '/' + ano; // data para o boleto de pagamento à vista (recupere system nível 3)
                var p;

                $(this).parent().parent().find('.vencimento').val(data_ini);
                $(this).parent().parent().find('.vlr_recebido').val(number_format(valor, 2, ',', '.'));
                mes = mes + 1;
                for (var i = 0; i <= 10; i++) {

                    mes++; // adiantamos o mês

                    // para os meses que possui 30 dias e não 31
                    if (mes == 4 || mes == 6 || mes == 9 || mes == 11) {
                        if (dia == 31)
                            dia = 30; // retroagimos para o dia 30
                    } else {
                        dia = d; // caso contrário recuperamos a informação do dia
                    }

                    if (mes == 2) { // se o mês é fevereiro
                        if (dia == 31 || dia == 30 || dia == 29) { // e os dias são 30 ou 31
                            // muda o dia para o
                            // último dia do mês considerando ano bisexto
                            dia = (new Date(ano, 1, 29).getMonth() == 1) ? 29 : 28;
                        }
                    }

                    // se o mês passou de dezembro, viramos o ano
                    if (mes > 12) {
                        mes = 1;
                        ano++;
                    }

                    //Add p zero
                    if (mes < 10) mes = '0' + mes;
                    var diaa;
                    if ( dia < 10 ) diaa = '0' + dia;
                    else diaa = dia;
                    var data_vencimento = diaa + '/' + mes + '/' + ano;

                    html += '<tr class="formPagto"><td width="45%"><select name="forma_pgto[]" id="forma_pgto" onchange="seleciona_pgto()" class="forma_pgto"><option value="0">... Selecione ...</option><option value="1">DINHEIRO</option><option value="2" selected>CHEQUE</option><option value="3">CART&Atilde;O CR&Eacute;DITO</option><option value="5">CART&Atilde;O D&Eacute;BITO</option><option value="4">BOLETO</option><option value="6">ANTECIPA&Ccedil;&Atilde;O</option></select></td><td><input type="text" name="vencimento[]" class="vencimento" id="vencimento"value="' + data_vencimento + '" onKeyPress="mascara(this,data)"/></td><td><input type="text" name="vlr_recebido[]" id="vlr_recebido" class="vlr_recebido" value="' + number_format(valor, 2, ',', '.') + '" onKeydown="FormataValor(this,20,event,2)" style="text-align:right"/></td><td><input type="button" name="OK" value="[OK]" class="btnReceberAgora"/></td><td><button type="button" class="btnRemover">[REMOVER]</button></td></tr>'

                }

                $('#tblPagamentos').append(html);

                break;

            case '3':
                $('.formPagto').remove();
                var html = '';
                var PrimeiroVencimento = $(this).parent().parent().find('.vencimento').val();
                var valor = valor / 6;

                if (PrimeiroVencimento != 0) {
                    PrimeiroVencimento = PrimeiroVencimento.split('/');
                    var dia = parseInt(PrimeiroVencimento[0]);
                    var mes = parseInt(PrimeiroVencimento[1]) - 1;
                    var ano = parseInt(PrimeiroVencimento[2]);
                } else {
                    var PrimeiroV = hoje.split('/');
                    var dia = parseInt(PrimeiroV[0]);
                    var mes = parseInt(PrimeiroV[1]) - 1;
                    var ano = parseInt(PrimeiroV[2]);                    
                }

                var d = dia; // grava o dia em outra variável para poder recuperar depois
                var d = dia; // grava o dia em outra variável para poder recuperar depois
                var data_ini = dia + '/' + (mes + 2) + '/' + ano; // data para o boleto de pagamento à vista (recupere system nível 3)
                var p;

                $(this).parent().parent().find('.vencimento').val(data_ini);
                $(this).parent().parent().find('.vlr_recebido').val(number_format(valor, 2, ',', '.'));
                mes = mes + 1;
                for (var i = 0; i <= 5; i++) {

                    mes++; // adiantamos o mês

                    // para os meses que possui 30 dias e não 31
                    if (mes == 4 || mes == 6 || mes == 9 || mes == 11) {
                        if (dia == 31)
                            dia = 30; // retroagimos para o dia 30
                    } else {
                        dia = d; // caso contrário recuperamos a informação do dia
                    }

                    if (mes == 2) { // se o mês é fevereiro
                        if (dia == 31 || dia == 30 || dia == 29) { // e os dias são 30 ou 31
                            // muda o dia para o último dia do mês considerando ano bisexto
                            dia = (new Date(ano, 1, 29).getMonth() == 1) ? 29 : 28;
                        }
                    }

                    // se o mês passou de dezembro, viramos o ano
                    if (mes > 12) {
                        mes = 1;
                        ano++;
                    }

                    //Add p zero
                    if (mes < 10) mes = '0' + mes;
                    var diaa;
                    if ( dia < 10 ) diaa = '0' + dia;
                    else diaa = dia;
                    var data_vencimento = diaa + '/' + mes + '/' + ano;

                    html += '<tr class="formPagto"><td width="45%"><select name="forma_pgto[]" id="forma_pgto" onchange="seleciona_pgto()" class="forma_pgto"><option value="0">... Selecione ...</option><option value="1">DINHEIRO</option><option value="2" >CHEQUE</option><option value="3" selected>CART&Atilde;O CR&Eacute;DITO</option><option value="5">CART&Atilde;O D&Eacute;BITO</option><option value="4">BOLETO</option><option value="6">ANTECIPA&Ccedil;&Atilde;O</option></select></td><td><input type="text" name="vencimento[]" class="vencimento" id="vencimento"value="' + data_vencimento + '" onKeyPress="mascara(this,data)"/></td><td><input type="text" name="vlr_recebido[]" id="vlr_recebido" class="vlr_recebido" value="' + number_format(valor, 2, ',', '.') + '" onKeydown="FormataValor(this,20,event,2)" style="text-align:right"/></td><td><input type="button" name="OK" value="[OK]" class="btnReceberAgora"/></td><td><button type="button" class="btnRemover">[REMOVER]</button></td></tr>'


                }

                $('#tblPagamentos').append(html);

                break;

            case '4':

                $('.formPagto').remove();
                var PrimeiroVencimento = $('#iptVencimento').val();
                if (PrimeiroVencimento != 0) {
                    PrimeiroVencimento = PrimeiroVencimento.split('/');
                    var dia = parseInt(PrimeiroVencimento[0]);
                    var mes = parseInt(PrimeiroVencimento[1]) - 1;
                    var ano = parseInt(PrimeiroVencimento[2]);
                } else {
                    var PrimeiroV = hoje.split('/');
                    var dia = parseInt(PrimeiroV[0]);
                    var mes = parseInt(PrimeiroV[1]) - 1;
                    var ano = parseInt(PrimeiroV[2]);
                }

                mes = (mes + 1);
                if (mes < 10) mes = '0' + mes;
                if ( dia < 10 ) dia = '0'+dia;

                var d = dia; // grava o dia em outra variável para poder recuperar depois
                var data_ini = dia + '/' + mes + '/' + ano; // data para o boleto de pagamento à vista (recupere system nível 3)

                var html = '<tr class="formPagto"><td width="45%"><select name="forma_pgto[]" id="forma_pgto" onchange="seleciona_pgto()" class="forma_pgto"><option value="0">... Selecione ...</option><option value="1">DINHEIRO</option><option value="2">CHEQUE</option><option value="3">CART&Atilde;O CR&Eacute;DITO</option><option value="5">CART&Atilde;O D&Eacute;BITO</option><option value="4" selected>BOLETO</option><option value="6">ANTECIPA&Ccedil;&Atilde;O</option></select></td><td><input type="text" name="vencimento[]" class="vencimento" id="vencimento"value="' + data_ini + '" onKeyPress="mascara(this,data)"/></td><td><input type="text" name="vlr_recebido[]" id="vlr_recebido" class="vlr_recebido" value="' + number_format(valor, 2, ',', '.') + '" onKeydown="FormataValor(this,20,event,2)" style="text-align:right"/></td><td><input type="button" name="OK" value="[OK]" class="btnReceberAgora"/></td></tr>'
                $('#tblPagamentos').append(html);

                break;

            case '5':

                $('.formPagto').remove();
                var PrimeiroVencimento = $('#iptVencimento').val();
                if (PrimeiroVencimento != 0) {
                    PrimeiroVencimento = PrimeiroVencimento.split('/');
                    var dia = parseInt(PrimeiroVencimento[0]);
                    var mes = parseInt(PrimeiroVencimento[1]) - 1;
                    var ano = parseInt(PrimeiroVencimento[2]);
                } else {
                    var PrimeiroV = hoje.split('/');
                    var dia = parseInt(PrimeiroV[0]);
                    var mes = parseInt(PrimeiroV[1]) - 1;
                    var ano = parseInt(PrimeiroV[2]);
                }

                mes = (mes + 1);
                if (mes < 10) mes = '0' + mes;
                if ( dia < 10 ) dia = '0'+dia;
                var d = dia; // grava o dia em outra variável para poder recuperar depois
                var data_ini = dia + '/' + mes + '/' + ano; // data para o boleto de pagamento à vista (recupere system nível 3)

                var html = '<tr class="formPagto"><td width="45%"><select name="forma_pgto[]" id="forma_pgto" onchange="seleciona_pgto()" class="forma_pgto"><option value="0">... Selecione ...</option><option value="1" >DINHEIRO</option><option value="2">CHEQUE</option><option value="3">CART&Atilde;O CR&Eacute;DITO</option><option value="5" selected>CART&Atilde;O D&Eacute;BITO</option><option value="4" >BOLETO</option><option value="6">ANTECIPA&Ccedil;&Atilde;O</option></select></td><td><input type="text" name="vencimento[]" class="vencimento" id="vencimento"value="' + data_ini + '" onKeyPress="mascara(this,data)"/></td><td><input type="text" name="vlr_recebido[]" id="vlr_recebido" class="vlr_recebido" value="' + number_format(valor, 2, ',', '.') + '" onKeydown="FormataValor(this,20,event,2)" style="text-align:right"/></td><td><input type="button" name="OK" value="[OK]" class="btnReceberAgora"/></td></tr>'
                $('#tblPagamentos').append(html);

                break;

            case '6':

                $('.formPagto').remove();
                var PrimeiroVencimento = $('#iptVencimento').val();
                if (PrimeiroVencimento != 0) {
                    PrimeiroVencimento = PrimeiroVencimento.split('/');
                    var dia = parseInt(PrimeiroVencimento[0]);
                    var mes = parseInt(PrimeiroVencimento[1]) - 1;
                    var ano = parseInt(PrimeiroVencimento[2]);
                } else {
                    var PrimeiroV = hoje.split('/');
                    var dia = parseInt(PrimeiroV[0]);
                    var mes = parseInt(PrimeiroV[1]) - 1;
                    var ano = parseInt(PrimeiroV[2]);
                }

                mes = (mes + 1);
                if (mes < 10) mes = '0' + mes;
                if ( dia < 10 ) dia = '0'+dia;

                var d = dia; // grava o dia em outra variável para poder recuperar depois
                var data_ini = dia + '/' + mes + '/' + ano; // data para o boleto de pagamento à vista (recupere system nível 3)

                var html = '<tr class="formPagto"><td width="45%"><select name="forma_pgto[]" id="forma_pgto" onchange="seleciona_pgto()" class="forma_pgto"><option value="0">... Selecione ...</option><option value="1" >DINHEIRO</option><option value="2">CHEQUE</option><option value="3">CART&Atilde;O CR&Eacute;DITO</option><option value="5">CART&Atilde;O D&Eacute;BITO</option><option value="4">BOLETO</option><option value="6" selected>ANTECIPA&Ccedil;&Atilde;O</option></select></td><td><input type="text" name="vencimento[]" class="vencimento" id="vencimento"value="' + data_ini + '" onKeyPress="mascara(this,data)"/></td><td><input type="text" name="vlr_recebido[]" id="vlr_recebido" class="vlr_recebido" value="' + number_format(valor, 2, ',', '.') + '" onKeydown="FormataValor(this,20,event,2)" style="text-align:right"/></td><td><input type="button" name="OK" value="[OK]" class="btnReceberAgora"/></td></tr>'
                $('#tblPagamentos').append(html);

                break;

        }
    });


    $(document).on('click', '.btnReceberAgora', function () {

        var codloja = $('#codloja').val();
        var idvenda = $('#iptIdVenda').val();
        var formapgto = $(this).parent().parent().find('.forma_pgto').val();
        var vencimento = $(this).parent().parent().find('.vencimento').val();
        var valor = $(this).parent().parent().find('.vlr_recebido').val();

        $.ajax({
            url: "salva_venda_cliente.php", //teste somente para retornar success
            data: {
                iptIdVenda: idvenda,
                codloja: codloja,
                forma_pgto: formapgto,
                vencimento: vencimento,
                valor: valor
            },
            type: "POST",
            async: false,
            success: function (dataResult) {
                var valorRecebido = $('#vlr_recebido').val().replace('.', '').replace(',', '.');
                var saldo = $('#iptSaldo').val().replace('.', '').replace(',', '.');
                var iptRecebido = number_format(saldo - valorRecebido, 2);
                $('#pgtos').html(dataResult);
                document.getElementById("finalizar").style.display = "block";
                $('#vlr_recebido').val(iptRecebido);
                $('#iptSaldo').val(iptRecebido);
                $('#vencimento').val('');
                $('#forma_pgto').focus();
            }
        });


    });

    $(document).on('click', '.btnRemover', function () {
        $(this).parent().parent().remove();
        var totalParcelas = $('.forma_pgto').length;
        var valor = parseFloat($('#iptSaldo').val().replace('.', '').replace(',', '.')) / totalParcelas
        valor = number_format(valor, 2);
        valor = valor.replace('.', ',');
        $.each($('.vlr_recebido'), function () {
            $(this).val(valor);
        });
    });

    $(document).ready(function () {
        $('#btnSalvarTodos').on('click', function () {
            $.each($('.btnReceberAgora'), function () {
                $(this).trigger('click');
            });
        });
    })

</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function () {
        $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
    });
</script>