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
    $sql = "SELECT a.nomefantasia, mid(logon,1,5) as codigo FROM cs2.cadastro a
            INNER JOIN cs2.logon b ON a.codloja = b.codloja WHERE a.codloja = " . $_REQUEST['codigo'];
    $qry = mysql_query($sql, $con);
    $cliente = mysql_result($qry, 0, 'nomefantasia');
    $codigo = mysql_result($qry, 0, 'codigo');

    $resx = mysql_query($sql_lista, $con);
    if (mysql_num_rows($resx) > 0) {
        ?>
        <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
            <tr style='background-color: #a1c2fa; text-align: center'>
                <td colspan="8"><?php echo $codigo . ' - ' . $cliente; ?></td>
            </tr>
        </table>
        <br>
        <?php
        while ($regx = mysql_fetch_array($resx)) {

            $id = $regx['id'];
            
            // Verificando se existe ID de pagamento, caso não exista o sistema nao ira mostrar o Pedido
            
            $sql_dados = "SELECT count(*) AS qtd FROM cs2.cadastro_equipamento_pagamento WHERE id_venda = $id";
            $res = mysql_query($sql_dados, $con);
            $qtd_pgto = mysql_result($res,0,'qtd');
            
            if ( $qtd_pgto > 0 ){
            
                $sql_dados = "SELECT a.qtd, a.numero_serie, b.descricao, a.valor_unitario, c.vr_desconto,
                                        DATE_FORMAT(c.data_compra,'%d/%m/%Y') as data, c.id, a.codigo_barra, ca.nome
                                  FROM cs2.cadastro_equipamento_descricao a 
                                  INNER JOIN base_web_control.produto b  ON b.id_cadastro = 62735 AND a.codigo_barra = b.codigo_barra 
                                  INNER JOIN cs2.cadastro_equipamento c  ON c.id = a.id_cadastro_equipamento
                                  LEFT JOIN cs2.funcionario ca           ON ca.id = c.id_consultor
                                  WHERE c.id = $id and c.venda_finalizada = 'S'
                                  ORDER BY c.id";

                $res = mysql_query($sql_dados, $con);
                ?>

                <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                    <tr class="titulo">
                        <td colspan="8">Equipamentos Adquiridos - Venda: <?php echo $id;?></td>
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
                        $sql_pgto = "SELECT 
                                    valor, DATE_FORMAT(vencimento,'%d/%m/%Y') as venc,
                                    CASE id_formapgto 
                                        WHEN '1' THEN 'DINHEIRO'
                                        WHEN '2' THEN 'CHEQUE'
                                        WHEN '3' THEN 'CARTAO DE CREDITO'
                                        WHEN '4' THEN 'BOLETO'
                                        WHEN '5' THEN 'CARTAO DE DEBITO'
                                        WHEN '6' THEN 'ANTECIPACAO'
                                        WHEN '7' THEN 'TRANSFERENCIA'
                                    END as pgto
                                 FROM cs2.cadastro_equipamento_pagamento WHERE id_venda = $id_venda ORDER BY vencimento ASC";
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



                    $total = number_format($soma_total - $vr_desconto, 2, ',', '.');

                    if ($vr_desconto < 0) {
                        $vr_desconto = 0;
                    }

                    $vr_desconto2 = number_format($vr_desconto, 2, ',', '.');

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
                    ?>
                </table>
                <?php
            }
        }
        ?>
        <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
            <tr align='center'>
                <td  colspan='8'><input type="button" value="   VOLTAR   " onclick="voltar()"></td>
            </tr>
        </table>   
        <?php
}
else{
    echo "<script>alert('NENHUM EQUIPAMENTO LANÇADO PARA ESTE CLIENTE.');history.back()</script>";
    die;
}?>
</form>
<?php if ($id_franquia == 1) { ?>

    <form id="frmAlterarVendedor" name="frmAlterarVendedor" method="post" action="clientes/a_alterar_vendedor.php">
        <input id="id" name="id" type="hidden" />
        <input id="codigo" name="codigo" type="hidden" value="<?php echo $codigo ?>"/>
        <input id="acao" name="acao" type="hidden" />
        <input id="id_franquia" name="id_franquia" type="hidden" value="<?php echo $id_franquia ?>"/>
    </form>

<?php } ?>