<?php

require "connect/sessao.php";

$hoje = date('d/m/Y');

?>

    <script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
    <script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>
    <script>
        $(document).ready(function(){

            $('#iptCodigoBarra').on('blur', function(){

                var codigo_barra = $(this).val();


                $.ajax({
                    url: '../php/BuscaProduto.php',
                    data:{
                        action: 'buscaProdutoCodigoBarra',
                        codigo_barra: codigo_barra
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(data){
                        console.log(data);

                        if(data.result == 1){
                            $('#descricaoProduto').html(data.descricao);
                            $('#valor_produto').val(number_format(data.custo_medio_venda,2,'.',''));
                            $('#valor_produto2').val(data.custo_medio_venda);
                            var temParcelas = false;
                            $.each($('.qtd_parcela > option'), function(){

                                if($(this).val() == 6){
                                    temParcelas = true;
                                }

                            });
                            if(temParcelas == true){
                                $('.qtd_parcela').val(6);
                                $('.qtd_parcela').trigger('change');
                            }
                            $('#numero_serie').focus();

                            console.log(data.controle_qtd);
                            if(data.controle_qtd == 'S'){
                                $('.numero_serie').attr('style', 'display: none;');
                                $('.quantidade').removeAttr('style');
                            }else{
                                $('.quantidade').attr('style', 'display: none;');
                                $('.numero_serie').removeAttr('style');
                            }
                        }
                    }
                })

            })

        });



        window.onload = function(){
            document.listacompra.data_venda.focus(); 
        };

        $('#data_venda').mask('99/99/9999');

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
        
        function voltar_tela(){
            frm = document.listacompra;
            frm.action = 'painel.php?pagina1=area_restrita/d_equipamentos0.php';
            frm.submit();
        }

        function reimprimir_consignacao(){
           document.getElementById('reimpressao').removeAttribute('style');
        }
    
        function alterarFranquia(){

            var id_franquia = $('select[name="franqueado"]').val();
            $.ajax({
                    url: '../php/clientes/BuscaConsultorAgendador.php',
                    data: {
                            id_franquia: id_franquia,
                            action: 'buscarFuncionario'
                    },
                    type: 'POST',
                    dataType: 'text',
                    success: function(data){

                        var arrResult = data.split(';');
                        $('select[name="id_funcionario"]').html('');
                        $('select[name="id_funcionario"]').append(arrResult[0]);
                    }
            });
            // selecionando qtd parcelas de acordo com a franquia
            $.ajax({
                    url: '../php/clientes/BuscaConsultorAgendador.php',
                    data: {
                            id_franquia: id_franquia,
                            action: 'buscarParcelas'
                    },
                    type: 'POST',
                    dataType: 'text',
                    success: function(data){
                        
                        $('#parcelas').html(data);
                    }
            });

        }

        function number_format (number, decimals, dec_point, thousands_sep) {
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

        function pesquisa_produto(codigo){

            if ( codigo != '' ){
                var req = new XMLHttpRequest();
                req.open('GET', 'carrega_produto.php?codigo='+codigo , false);
                req.send(null);
                if (req.status != 200) return '';
                var retorno = req.responseText;

                if ( retorno.trim() == '' ){
                    alert('PRODUTO NÃO CADASTRADO');
                    $('#codigo_barras').focus();
                }else{
                    var array      = retorno.split('][');
                    $('#valor_produto').val(array[1]);
                    $('#valor_produto2').val(array[1]);
                    $('#qtd_parcela').focus();
                }
            }
        }

        function grava_produto(){

            frm = document.listacompra;
            frm.action = 'painel.php?pagina1=salva_venda2.php';
            frm.submit();
            
        }

        function mostra_parcelas(qtd_parc){
            
            var valor_total  = $('#valor_produto').val();
            if ( valor_total == '' )
                valor_total = 0;

            var html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bodyText" >';
            var j = 0;
            $('#parcelas_mostra').html('');
            var hoje = $('#data_venda').val();
            var dia = hoje.substr(0,2);
            var mes = hoje.substr(3,2);
            var ano = hoje.substr(6,4);
            var d = dia;
            var valor_parcela = valor_total / qtd_parc;
            var j = 0;

            for (var i = 0; i < qtd_parc; i++) {

                j++;

                var num = i + 1; // número da parcela

                mes++; // adiantamos o mês
                // para os meses que possui 30 dias e não 31
                if (mes == 4 || mes == 6 || mes == 9 || mes == 11) {
                    if (dia == 31)
                        dia = 30; // retroagimos para o dia 30
                } else {
                    dia = d; // caso contrário recuperamos a informação do dia
                }
                if (mes == 2) { // se o mês é fevereiro
                    if (dia == 31 || dia == 30) { // e os dias são 30 ou 31
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
                if(mes < 10){
                    mes = '0'+mes;
                }

                var vencimento = dia + '/' + mes + '/' + ano;

                html += '<tr>\n\
                                <td>\n\
                                        '+j	+'&deg; - \n\
                                </td>\n\
                                <td>\n\
                                        Valor\n\
                                </td>\n\
                                <td>\n\
                                        R$ '+number_format(valor_parcela,2,',','.')+'\n\							</td>\n\
                                <td>\n\
                                        Vencimento\n\
                                </td>\n\
                                <td>\n\
                                        <input type="text" id="vencimento'+j+'" name=vencimento[] alt="decimal" value="'+vencimento+'" >\n\
                                </td>\n\
                        </tr>';

            }
            html += '</table>';

            $('#parcelas_mostra').html(html);
            $('#numero_serie').focus();
        }

        function ConfirmarDebito(Venda){

            frm = document.listacompra;
            frm.action = 'painel.php?pagina1=salva_venda.php';
            frm.submit();

        }
		
    </script>
    <form method="post" action="#" name='listacompra' id='listacompra'>
        <table width=70% border="0" align="center">
            <tr class="titulo">
                <td colspan="2">Controle de Sa&iacute;da de Produtos/Equipamentos</td>
            </tr>
            <tr>	
                <td class="campoesquerda" colspan="2">&nbsp;</td>
            </tr>
            <tr>	
                <td class="subtitulodireita">Data</td>
                <td class="subtitulopequeno">
                    <input type="text" id="data_venda" name="data_venda" onKeyPress="return MM_formtCep(event,this,'##/##/####');" value="<?php echo date('d/m/Y');?>" ></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Franquia:</td>
                <td class="subtitulopequeno">
                    <select name="franqueado" id="franqueado" style="width:70%" onchange="alterarFranquia()" >
                        <option value='0'>Selecione a Franquia</option>
                        <?php
                        $sql = "SELECT id, fantasia FROM franquia WHERE sitfrq=0 AND classificacao != 'J' ORDER BY id";
                        $resposta = mysql_query($sql,$con);
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
            <tr>
                <td class="subtitulodireita">Funcion&aacute;rio:</td>
                <td height="22" colspan="3" class="subtitulopequeno">
                    <?php
                    $sql_sel = "SELECT id, nome FROM cs2.funcionario where id_empregador IN (1,2) and ativo = 'S'";
                    $qry = mysql_query($sql_sel, $con);
                    echo "<select name='id_funcionario' id='id_funcionario' style='width:65%'>";
                    ?>
                    <option value="0">Selecione o Funcionario</option>
                    <?php
                    while($rs = mysql_fetch_array($qry)) {
                        $id_funcionario = $rs['id'];
                        $nome           = $rs['nome'];
                        if ($id_funcionario == $matriz['id_funcionario'] ) {
                            echo "<option value='$id_funcionario' selected>{$nome}</option>";
                        } else {
                            echo "<option value='$id_funcionario'>{$nome}</option>";
                        }
                    }
                    echo "</select>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Equipamento:</td>
                <td class="subtitulopequeno">
<!--                    --><?php
//                        echo "<select name='codigo_barras' id='codigo_barras' onChange='pesquisa_produto(this.value)' >";
//                        $sql = "SELECT codigo, codigo2, descricao FROM cs2.produto
//                                WHERE exibir_tabela = 'S' ORDER BY descricao";
//                        $resposta = mysql_query($sql,$con);
//                        $txt_prod = "<option value='0'>Selecione o Equipamento</option>";
//                        while ($array = mysql_fetch_array($resposta))
//                        {
//                                $codigo    = $array["codigo"];
//                                $codigo2   = $array["codigo2"];
//                                $descricao = $array["descricao"];
//                                $txt_prod .="<option value=\"$codigo\">$descricao - $codigo2</option>\n";
//                        }
//                        echo $txt_prod;
//                        echo "</select>";
//                    ?>
                    <input type="text" id="iptCodigoBarra" name="iptCodigoBarra" placeholder="Digite o código de barra" width="">
                    <span id="descricaoProduto"></span>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Valor:</td>
                <td class="subtitulopequeno">
                    <input type="hidden" id="valor_produto2" name="valor_produto2">
                    <input type="text" id="valor_produto" name="valor_produto" readonly="readonly" disabled>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Qtd Parcelas:</td>
                <td id="parcelas" class="subtitulopequeno"></td>
            </tr>
            <tr>
                <td class="subtitulodireita">&nbsp;</td>
                <td class="subtitulopequeno" ><span id="parcelas_mostra"></span></td>
            </tr>
            <tr class="numero_serie" >
                <td class="subtitulodireita">N&deg; de S&eacute;rie:</td>
                <td class="subtitulopequeno">
                    <input type="text" value="" name="numero_serie" id="numero_serie" style="text-transform: uppercase"/>
                </td>
            </tr>
            <tr class="quantidade" style="display: none;">
                <td class="subtitulodireita">Quantidade</td>
                <td class="subtitulopequeno">
                    <input type="text" value="" name="qtd" id="qtd" style="text-transform: uppercase"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="titulo" align="center">
                    <input type="button" name="enviar" value="    Confirmar    " onclick="grava_produto()" />
                    <input type="button" name="voltar" value="    Voltar   " onclick="voltar_tela()" />
                    <input type="button" name="reimprimir" value="    Reimprimir   " onclick="reimprimir_consignacao()" />

                </td>
            </tr>
        </table>
    </form>
    <div id="reimpressao" style="display: none;">
        <form method="post" action="area_restrita/d_imprimir_consignacao.php" name='reimprimir' id='reimprimir'>
            <table width=70% border="0" align="center">
                <tr>
                    <td class="titulo" colspan="2">Reimpressão de Consignação</td>
                </tr>
                <tr>
                    <td class="campoesquerda" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="subtitulo" style="text-align: right !important;">
                        Nº de Série:
                    </td>
                    <td class="subtitulo" style="text-align: left !important;">
                        <input type="text" name="iptNumeroSerie">
                    </td>
                </tr>
                <tr>
                    <td class="subtitulo" colspan="2">
                        <button type="submit" name="btnImprimir">Imprimir</button>
                    </td>
                </tr>
            </table>
    </div>


<script>
    $(document).ready(function(){

        $('#iptCodigoBarra').on('change', function(){



        });

    })
</script>