<?php
require "connect/sessao.php";

$hoje = date('d/m/Y');

$acao = $_REQUEST['acao'];
$codigo = $_REQUEST['codigo'];

$sql_lista = "SELECT id FROM cs2.cadastro_equipamento
              WHERE codloja = '$codigo'
              ORDER by id";
if ($id_franquia == 4 || $id_franquia = 247 || $id_franquia == 163) {
    $id_franquia = 1;
}
?>
<script type="text/javascript" src="..../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../../js/jquery.meio.mask.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../js/jquery.meio.mask.js"></script>

<script>
    (function ($) {
        $(
                function () {
                    $('input:text').setMask();
                }
        );
    })(jQuery);
    function alterarVendedor(id) {

        var form = document.forms.frmAlterarVendedor;
        form.id.value = id;

        form.submit();

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
        document.listacompra.codigo_barras.focus();
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

    function FormataValor(campo, tammax, teclapres) {
        var tecla = teclapres.keyCode;
        vr = event.srcElement.value;
        vr = vr.replace("/", "");
        vr = vr.replace("/", "");
        vr = vr.replace(",", "");
        vr = vr.replace(".", "");
        vr = vr.replace(".", "");
        vr = vr.replace(".", "");
        vr = vr.replace(".", "");
        if (vr.length > 0) {
            vr = parseInt(vr, 10);
        }
        vr = '' + vr
        tam = vr.length;
        if (tam < tammax && tecla != 8) {
            tam = vr.length + 1;
        }
        if (tecla == 8) {
            tam = tam - 1;
        }
        if (tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105) {
            if (tam <= 2) {
                if (tam == 1) {
                    event.srcElement.value = "0,0" + vr;
                } else if (tam == 2) {
                    event.srcElement.value = "0," + vr;
                } else {
                    event.srcElement.value = "";
                }
            }
            if ((tam > 2) && (tam <= 5)) {
                event.srcElement.value = vr.substr(0, tam - 2) + ',' + vr.substr(tam - 2, tam);
            }
            if ((tam >= 6) && (tam <= 8)) {
                event.srcElement.value = vr.substr(0, tam - 5) + '.' + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
            }
            if ((tam >= 9) && (tam <= 11)) {
                event.srcElement.value = vr.substr(0, tam - 8) + '.' + vr.substr(tam - 8, 3) + '.' + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
            }
            if ((tam >= 12) && (tam <= 14)) {
                event.srcElement.value = vr.substr(0, tam - 11) + '.' + vr.substr(tam - 11, 3) + '.' + vr.substr(tam - 8, 3) + '.' + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
            }
            if ((tam >= 15) && (tam <= 17)) {
                event.srcElement.value = vr.substr(0, tam - 14) + '.' + vr.substr(tam - 14, 3) + '.' + vr.substr(tam - 11, 3) + '.' + vr.substr(tam - 8, 3) + '.' + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
            }
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
            }
        }
    }

    function grava_produto() {

        var codloja = $('#codloja').val();
        var codigo = $('#codigo_barras').val();
        var nserie = $('#numero_serie').val();
        var idvenda = $('#iptIdVenda').val();
        var valor = $('#valor_produto').val();
        var id_agendador = $('#id_agendador').val();
        var id_consultor = $('#id_consultor').val();
        var data_venda = $('#data_venda').val();

        $.ajax({
            url: "salva_produto_cliente.php", //teste somente para retornar success
            data: {
                codloja: codloja,
                codigo_barra: codigo,
                numero_serie: nserie,
                id_venda: idvenda,
                valor: valor,
                id_agendador: id_agendador,
                id_consultor: id_consultor,
                data_venda: data_venda
            },
            type: "POST",
            async: false,
            success:
                    function (dataResult) {

                        var retorno2 = dataResult;
                        var array = retorno2.split('][');
                        var idvenda2 = (array[0]).trim();
                        var tabela = array[1];

                        $(".divNumeroPedido").text(idvenda2);
                        $("input[name=iptIdVenda]").val(idvenda2);
                        $('#tabela_compra').html(tabela);
                        $('#codigo_barras').val('');
                        $('#numero_serie').val('');
                        $('#codigo_barras').focus();

                    }
        });

    }

    function ConfirmarRecebimento(Venda) {
        if (Venda) {

            document.getElementById("pagamentos").style.display = "block";
            frm = document.listacompra;
            frm.forma_pgto.focus();
        }
    }

    function ReceberAgora(Venda) {

        var codloja = $('#codloja').val();
        var idvenda = $('#iptIdVenda').val();
        var formapgto = $('#forma_pgto').val();
        var vencimento = $('#vencimento').val();
        var valor = $('#vlr_recebido').val();

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
            success:
                    function (dataResult) {

                        $('#pgtos').html(dataResult);
                        document.getElementById("finalizar").style.display = "block";

                    }
        });

    }

    function voltar_tela() {
        frm = document.listacompra;
        frm.action = 'painel.php?pagina1=clientes/a_cons_id.php&id=' +<?php echo $_REQUEST['codigo'] ?>;
        frm.submit();
    }

    function voltar() {
        frm = document.listacompra;
        frm.action = 'painel.php?pagina1=area_restrita/d_equipamentos_venda.php';
        frm.submit();

    }

    function seleciona_pgto() {
        frm = document.listacompra;
        frm.vencimento.focus();
    }

    function FinalizarPerdido() {
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
            success:
                    function (dataResult) {

                        if (dataResult == '1') {

                            alert('Registro Gravado com Sucesso');
                            window.location.href = "https://www.webcontrolempresas.com.br/franquias/php/painel.php?pagina1=clientes/a_equipamentos.php&codigo=" + codloja;
                        }

                    }
        });
    }
</script>
<form method="post" action="#" name='listacompra' id='listacompra'>
    <?php

    $sql = "SELECT a.nomefantasia, MID(logon,1,LOCATE('S', logon) - 1) as codigo, a.codloja FROM cs2.cadastro a
            INNER JOIN cs2.logon b ON a.codloja = b.codloja WHERE a.codloja = " . $_REQUEST['codigo'];
    $qry = mysql_query($sql, $con);
    $cliente = mysql_result($qry, 0, 'nomefantasia');
    $codigo = mysql_result($qry, 0, 'codigo');
    $id_cadastro = mysql_result($qry, 0, 'codloja');

    $resx = mysql_query($sql_lista, $con);
    if (mysql_num_rows($resx) > 0) {
        ?>
        <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
            <tr style='background-color: #a1c2fa; text-align: center'>
                <td colspan="3"><?php echo $codigo . ' - ' . $cliente; ?></td>
            </tr>
        </table>
        <br>

        <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
            <tr class="titulo">
                <td>Descrição</td>
                <td>Qtd Utilizado Cliente</td>
                <td>Qtd Utilizado Nacional</td>
            </tr>

            <?php

            // Qtd de produtos cadastrados

            $sql01 = "SELECT COUNT(id) as qtd FROM base_web_control.produto WHERE id_cadastro = '$id_cadastro'";
            $res = mysql_query($sql01, $con);
            $qtd_produto = mysql_result($res,0,'qtd');

            echo "<tr>
                     <td>Produtos Cadastrados</td>
                     <td>$qtd_produto</td>
                     <td>$qtd_produto_nacional</td>
                  </tr>";

            // Qtd de clientes cadastrados

            $sql02 = "SELECT COUNT(id) as qtd FROM base_web_control.cliente WHERE id_cadastro = '$id_cadastro'";
            $res = mysql_query($sql02, $con);
            $qtd_cliente = mysql_result($res,0,'qtd');

            echo "<tr>
                     <td>Clientes Cadastrados</td>
                     <td>$qtd_cliente</td>
                     <td>$qtd_cliente_nacional</td>
                  </tr>";

            // Qtd de vendas realizadas

            $sql03 = "SELECT COUNT(id) as qtd FROM base_web_control.venda 
                      WHERE id_cadastro = '$id_cadastro' and situacao != 'A' AND situacao != 'E'";
            $res = mysql_query($sql03, $con);
            $qtd_vendas_realizadas = mysql_result($res,0,'qtd');

            echo "<tr>
                     <td>Vendas Realizadas</td>
                     <td>$qtd_vendas_realizadas</td>
                     <td>qtd_vendas_realizadas_nacional</td>
                  </tr>";

            // Qtd de NOTAS FISCAIS realizadas

            $sql04 = "SELECT COUNT(*) as qtd, ve.tipo_nota FROM base_web_control.venda_notas_eletronicas ve
                      INNER JOIN base_web_control.venda v ON v.id = ve.id_venda
                      WHERE v.id_cadastro = '$id_cadastro' and status = '5' AND ambiente_nf='1'
                      GROUP BY ve.tipo_nota";
            $res = mysql_query($sql04, $con);


            while ($regx = mysql_fetch_array($res)) {

                $qtd_notas = $regx['qtd'];
                $tipo_nota = $regx['tipo_nota'];
                
                if ( $tipo_nota == 'NFC' ) $descricao = 'Qtd de NFCe emitidas';
                else if ( $tipo_nota == 'NFE' ) $descricao = 'Qtd de NFe emitidas';
                else if ( $tipo_nota == 'CFE' ) $descricao = 'Qtd de CFe emitidas';

                echo "<tr>
                         <td>descricao</td>
                         <td>$$qtd_notas</td>
                         <td>$qtd_notas_nacional</td>
                      </tr>";
            }

            ?>
        </table>
<?php } ?>
</form>