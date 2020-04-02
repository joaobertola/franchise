<?php
require "connect/sessao.php";
require "connect/funcoes.php";

$codloja    = $_REQUEST['codloja'];
$data_atual = date("Y-m-d");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="<?= 'http://' . $_SERVER["SERVER_NAME"] ?>/franquias/css/assets/js/bootstrap.min.js"></script>
<script src="<?= 'http://' . $_SERVER["SERVER_NAME"] ?>/franquias/css/assets/js/mask.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="../css/assets/css/sweetalert.css">
<style>
    body{
        padding:0;
        margin:0;
    }
    #escolha{
        margin:0;
        width: 100%;
    }
    #fundo-modal-sms {
        width: 100%;
        height: 100%;
        position: fixed;
        background-color: #000;
        opacity: 0.5;
        top: 0;
        left: 0;
        z-index: 8888;
        display: none;
    }

    #modal-manual-sms {
        position: fixed;
        width: 400px;
        height: auto;
        background-color: white;
        z-index: 9999;
        top: 30%;
        left: 35%;
        text-align: center;
        border-radius: 7px;
        box-shadow: 5px 5px 5px black;
        display: none;
        padding-bottom: 10px;
    }

    #modal-manual-sms textarea {
        position: relative;
        margin: 5px auto;
        width: 300px;
        height: 150px;
        top: 0;
        left: 0;
    }

    #modal-sms {
        position: fixed;
        width: 400px;
        height: 150px;
        background-color: white;
        z-index: 9999;
        top: 30%;
        left: 35%;
        text-align: center;
        border-radius: 7px;
        box-shadow: 5px 5px 5px black;
        display: none;
    }

    #modal-sms button,
    #modal-manual-sms button {
        position: relative;
        width: 250px;
        height: 25px;
        margin: 10px auto;
        background-color: #0a6aa1;
        color: white;
    }

    #close-modal-sms {
        text-align: right;
        padding-top: 8px;
        font-size: 16px;
        color: #555;
    }

    #close-modal-sms span {
        margin-right: 5px;
        padding: 3px 3px;
        cursor: pointer;
        font-size: 18px;
    }
    .parcelas_new{
        background-color: #e5e5e5;
    }
    .parcelas_new td{
        padding-left: 8px;
        font-size: 12px;
        max-width: 15vw;
    }
    #escolha tbody td.subtitulodireita{
        min-width: 15.1vw;
        max-width: 15.1vw;
        width: 15.1vw;
    }
    #iptParcelas{
        width: 12.8vw;
    }
    tr#botao_confirmar td {
    /* margin-top: 34vh; */
    padding-top: 3%;
    }
</style>
<script src="../css/assets/js/sweetalert.min.js"></script>
<script language="javascript">
    // MODAL BOLETO SMS

    var smsId;
    var smsNumber;
    var smsVencimento;
    var smsValor;
    var codLoja_SMS;
    var nomeFantaia_SMS;

    function Confirmar_Parcelamento() {

        var numdoc_array = [];
        // var form = this;

        var numdoc = $('input:checkbox:checked').map(function(){
                numdoc_array.push($(this).data('numdoc'));
                return this.value;
            }).get();

        $.ajax({
            url: "clientes/a_ver_faturas_acordo_grava.php",
            type: "POST",
            data: {
                'valor': $('#evento_value').val(),
                'qtd_parcelas': $('#iptParcelas').val(),
                'codigo': $('#iptCodigo').val(),
                'numdoc' : numdoc_array                
            },
            success: function(data) {
                
                var array = data.split(';');
                var stat = array[0];
                var link = array[1];

                if (stat == '900') {
                    swal({
                           title: 'Registro gravado com sucesso!',
                           icon: 'success',
                           buttons: ["Ok", true]
                    }, (response)=>{
                        if(response){
                            window.location.reload();
                        }
                    });

                }else{
                    Swal.fire(
                       {
                        icon : 'error',
                        text : 'Erro ao gravar o registro'
                       }
                    );
                }
            }
        });
    }

    $(document).ready(function() {
        // $('.mask-data').mask('00/00/0000');

        var total = 0;
        //Chama a função com click em qualquer checkbox
        $(':checkbox').click(function() {

            //Atribui o valor do input p/ variável 'valor'
            var valor = parseFloat($(this).val());
            //Se o checkbox for marcado ele soma se não subtrai
            if ($(this).is(":checked")) {
                total += valor;
            } else {
                total -= valor;    
            }

            if (total > 0) {
                if (document.getElementById('escolha').style.display == 'none') {
                    document.getElementById('escolha').style.display = '';
                }
            } else {
                if (document.getElementById('escolha').style.display == '') {
                    document.getElementById('escolha').style.display = 'none';
                }
            }

            //Atribui o valor ao input
            $("#evento_value").val(total);
        });

       
        $('#iptParcelas').change(function() {
            var value = parseInt($(this).val());
            if (value > 0) {
                let html='';
                let data;
                var total_valor_parcela =$('#evento_value').val() / value;
                total_valor_parcela = total_valor_parcela.toString().split('.');
                total_valor_parcela[1] = total_valor_parcela[1].substr(0,2)
                total_valor_parcela = total_valor_parcela.join(',');
                $('.data_vencimento').each(function(index, el){
                    data = $(el).text();
                });
                if($('#escolha tbody').find('.parcelas_new').length){
                        $('#escolha tbody .parcelas_new').remove();
                    }
                let pai = document.getElementById("botao_confirmar").parentNode
                for (let index = 1; index <= value; index++) {
                    data = data.split('/');
                    data[1] = (parseInt(data[1]) + 1).toString();
                    data[1] = data[1].length == 1 ? "0"+data[1] : data[1];
                    data = data.join('/');

                    var tr = document.createElement("tr");
                    tr.classList.add("parcelas_new");


                    var td_data = document.createElement("td");
                    td_data.setAttribute("align","left");
                    td_data.setAttribute("colspan","3");
                    var b_data = document.createElement("b");
                    var b_text_data = document.createTextNode("Próximo(s) Vencimento(s): ");
                    var td_text_data = document.createTextNode(data);
                    b_data.appendChild(b_text_data);
                    td_data.appendChild(b_data);
                    td_data.appendChild(td_text_data);


                    var td_valor = document.createElement("td");
                    td_valor.setAttribute("align","left");
                    td_valor.setAttribute("colspan","6");;
                    var b_valor = document.createElement("b");
                    var b_text_valor = document.createTextNode("Valor da Parcela: ");
                    var td_text_valor = document.createTextNode(total_valor_parcela);
                    b_valor.appendChild(b_text_valor);
                    td_valor.appendChild(b_valor)
                    td_valor.appendChild(td_text_valor);


                    tr.appendChild(td_data);
                    tr.appendChild(td_valor);


                    pai.insertBefore(tr, document.getElementById("botao_confirmar"));
                    
                }
                remove_when_uncheked()
                // reciever_parcelar
            } else {
                $('#escolha tbody .parcelas_new').remove();
                remove_when_uncheked()
            }
        });

        function remove_when_uncheked(){
            $(':checkbox').click(function() {
                //Atribui o valor do input p/ variável 'valor'
                var valor = parseFloat($(this).val());
                //Se o checkbox for marcado ele soma se não subtrai
                if (!$(this).is(":checked")) {
                    if($('#escolha tbody').find('.parcelas_new').length){
                        $('#escolha tbody .parcelas_new').remove();
                        $('#iptParcelas option:eq(0)').prop('selected', true);
                    }
                }
            });
        }

    });
</script>

<?php

$sql_cliente = "select mid(a.logon,1,5) as logon, b.razaosoc, b.nomefantasia,
                    b.fone, b.fax, b.celular, b.email, b.sitcli
                from logon a
                inner join cadastro b on a.codloja=b.codloja
                where b.codloja = '$codloja'";
$resulta = mysql_query($sql_cliente, $con);
$linha = mysql_num_rows($resulta);
if ($linha > 0) {
    $matriz         = mysql_fetch_array($resulta);
    $logon          = $matriz['logon'];
    $razaosoc       = $matriz['razaosoc'];
    $razaosoc       = $matriz['nomefantasia'];
    $fone           = mascara_celular($matriz['fone']);
    $fax            = mascara_celular($matriz['fax']);
    $celular_n_mask = $matriz['celular'];
    $email          = $matriz['email'];
    $sitcli         = $matriz['sitcli'];
    $celular        = mascara_celular($matriz['celular']);
}

//pega da tabela titulos todas as ocorrencias para esse codloja
$command = "SELECT 
                 numdoc AS boleto, date_format(a.vencimento,'%d/%m/%Y') AS venc, a.valor, 
                 date_format(a.datapg,'%d/%m/%Y') AS dtpagamento, a.valorpg, a.referencia, 
                 a.vencimento, a.isento_juros, a.referencia, 
                 date_format(a.vencimento_alterado,'%d/%m/%Y') AS venc_alterado_view,
                 a.vencimento_alterado as venc_alterado,
                 (SELECT numdoc_destino FROM cs2.titulos_acordo WHERE texto_numdoc_origem like concat('%',a.numdoc,'%') LIMIT 1 ) as numdoc_destino
            FROM cs2.titulos a
            WHERE a.codloja = '$codloja'
              AND a.numboleto IS NOT NULL 
              AND a.datapg is NULL 
            ORDER BY a.vencimento";
$res = mysql_query($command, $con);
$linhas = mysql_num_rows($res);

if ( $linhas == 0 ){
    echo "<script>swal('NENHUM Título em Aberto !')</script>";
}


//comeca a tabela
?>
<form name='form' method='post' action='#' onsubmit='return false;'>
    <?php
    echo "
        <table align='center' width='85%' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
            <tr>
                <input type='hidden' id='iptCodigo' value='$codloja'>
                <td colspan='9' class='titulo'>FATURAS EM ATRASO OU A VENCER</td>
            </tr>
            <tr>
                <td colspan='3' class='subtitulodireita'>Codigo</td>
                <td colspan='6' class='subtitulopequeno'>$logon</td>
            </tr>
            <tr>
                <td colspan='3' class='subtitulodireita'>Razao Social</td>
                <td colspan='6' class='subtitulopequeno'>$razaosoc</td>
            </tr>
            <tr height='20' bgcolor='87b5ff'>
                <td align='center' width='5%'></td>
                <td align='center' width='9%'>No. Boleto</td>
                <td align='center' width='9%'>Venc. Original</td>
                <td align='center' width='9%'>Venc. Atualizado</td>
                <td align='center' width='9%'>Vr. Original</td>
                <td align='center' width='9%'><font color='red'>Desconto<br>Abatimento</font></td>
                <td align='center' width='9%'><font color='#0000e6'>Acrescimo</font></td>
                <td align='center' width='9%'><b>Valor Atualizado</b></td>
                <td align='center' width='9%'>Origem</td>
            </tr>
";

    $celular_ddd = substr($celular_n_mask, 0, 2);
    $celular_digito_9 = substr($celular_n_mask, 2, 1);
    $celular_number = substr($celular_n_mask, 3, 8);

    $celular_valid = true;

    if ($celular_digito_9 != 9) {
        $celular_valid = false;
        $celular_n_mask = 0;
    } else if (strlen($celular_number) < 8) {
        $celular_valid = false;
        $celular_n_mask = 0;
    }

    for ($a = 1; $a <= $linhas; $a++) {

        $matriz = mysql_fetch_array($res);
        $boleto = $matriz['boleto'];
        $numdoc_destino = $matriz['numdoc_destino'];

        // Verificando se o titulo tem desconto
        $sql_desconto = "SELECT sum(desconto) AS desconto, sum(acrescimo) AS acrescimo  FROM cs2.titulos_movimentacao
                         WHERE numdoc = '$boleto'";
        $qry_desconto    = mysql_query($sql_desconto, $con);
        $valor_desconto  = mysql_result($qry_desconto, 0, 'desconto');
        $valor_acrescimo = mysql_result($qry_desconto, 0, 'acrescimo');
        $venc_alter      = $matriz['venc_alterado'];
        $venc_alterado_o = $matriz['venc_alterado'];
        $venc_alterado_o = substr($venc_alterado_o, 8, 2) . '/' . substr($venc_alterado_o, 5, 2) . '/' . substr($venc_alterado_o, 0, 4);

        $venc_alterado      = $matriz['venc_alterado'];
        $venc_alterado_view = $matriz['venc_alterado_view'];
        $venc_original      = $matriz['venc'];
        $venc               = $matriz['venc'];

        $vencSMS = $venc;

        if ($venc_alterado != '')
            $venc = $venc_alterado_view;

        $valor_original = $matriz['valor'];
        $valor          = $matriz['valor'] - $valor_desconto;
        $referencia     = $matriz['referencia'];
        $dtpagamento    = $matriz['dtpagamento'];

        if ($_SESSION['id'] != 163) // SOMENTE PARA O WELLINGTON
            $ativo = " disabled='disabled' ";

        if (($referencia <> 'MULTA') or ($referencia == 'MULTA' and $dtpagamento <> '')) {

            /* condicao para mostra o pagamento com juros */
            $date = date("d/m/Y", time());

            $vencimento = $matriz['vencimento'];

            $vencimentof = substr($vencimento, 8, 2) . "/" . substr($vencimento, 5, 2) . "/" . substr($vencimento, 0, 4);

            if ($venc_alter != '') {
                $dif = diferenca_entre_datas($venc_alterado_o, $vencimentof, 'DD/MM/AAAA');
            } else {
                $dif = diferenca_entre_datas($date, $vencimentof, 'DD/MM/AAAA');
            }

            $txt_valorcobrado = '';

            if ($dif > 0) {

                $nvalor = str_replace(',', '.', $valor);
                $multa = ($nvalor + $valor_acrescimo) * 0.02; // 2%

                $encargos = (($nvalor + $valor_acrescimo) * 0.0015) * $dif;
                $xencargos = number_format($encargos, 2, ',', '.');

                $encargos = number_format($encargos, 2);

                $_valor = ($nvalor + $multa + $encargos + $valor_acrescimo);

                $multa = number_format($multa, 2, ',', '.');
                $valor_cobrado = number_format($_valor, 2, ',', '.');

            } else {

                $valor_cobrado = number_format($valor + $valor_acrescimo, 2, ',', '.');
            }

            $valor_original = number_format($valor_original, 2, ',', '.');
            $dtpagamento = $matriz['dtpagamento'];
            $valorpg = $matriz['valorpg'];
            $origem = $matriz['referencia'];
            if ($origem == 'BOL')
                $origem = 'Mensalidade';

            if (!$valorpg)
                $soma = $soma + $valor;

            /* Converte a Data para o formato Americano para fazer a comparação */
            $venc_originalAux        = str_replace('/', '-', $venc_original);

            // Col 1
            echo "<tr height='22' bgcolor='#E5E5E5'>";

            if ( $numdoc_destino != '' )
                echo "<td>&nbsp</td>";
            else
                echo "<td><input name='selecao[]' type='checkbox' value='$valor' data-numdoc='$boleto'/></td>";

            echo "<td align='center'><u><a href='../../inform/boleto/boleto.php?numdoc=$boleto'><font color='blue'>$boleto</font></a></u></td>
                     <td align='center' class='data_vencimento'>$venc_original</td>";

            // Col 2
            if ($venc_alterado != '')
                echo "<td align='center'>$venc</td>";
            else
                echo "<td align='center'>&nbsp;</td>";

            // Col 3            
            echo "  <td align='center'>$valor_original</td>";

            // Col 4
            if ($valor_desconto > 0)
                echo "  <td align='center'><font color='red'>" . number_format($valor_desconto, 2, ',', '.') . "<font></td>";
            else
                echo "  <td align='center'>&nbsp;</td>";

            // Col 5
            echo "  <td align='center'><font color='#0000e6'>$valor_acrescimo</font></td>";

            // Col 6
            echo "   <td align='center'>$valor_cobrado</td>";

            // Col 7
            echo "<td align='center'>$origem</td>
                </tr>";
        }
    }
    $somax = number_format($soma,2,',', '.');

    echo "<tr height='20' class='subtitulodireita'>
            <td colspan='9'>Soma das Faturas Mensais não pagas: R$ $somax</td>
          </tr>
    </table>";

    ?>
    <table align='center' width='85%' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
        <tr height='20' class='subtitulodireita'>
            <td colspan='3' class='subtitulodireita'>Soma Total do(s) registro(s) selecionado(s)</td>
            <td colspan='6' class='subtitulopequeno'><input type='text' disabled id='evento_value'></td>
        </tr>
    </table>
    <table align='center' width='85%' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
        <tr>
            <td colspan="9">
                <table id="escolha" align='center' width='85%' border='0' cellpadding='0' cellspacing='1' class='bodyText' style="display:none;">
                    <tr>
                        <td colspan='3' class='subtitulodireita'>Qtd de Parcelas</td>
                        <td colspan='6' class='subtitulopequeno'>
                            <select id="iptParcelas" name="iptParcelas">
                                <option value="0">Selecione...</option>
                                <option value="1">Receber na próxima fatura</option>
                                <option value="2">Parcelar em 2 vezes</option>
                                <option value="3">Parcelar em 3 vezes</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="botao_confirmar">
                        <td colspan="2"><input type='button' value='Confirme o parcelamento' onclick='Confirmar_Parcelamento()'></td>
                    </tr>
                    </div>
                </table>
            </td>
            <!-- <span id="reciever_parcelar"></span> -->
        </tr>
    </table>
</form>
<br>

<?php

$command = "SELECT 
               DATE_FORMAT(data,'%d/%m/%Y') AS data, parcela, DATE_FORMAT(vencimento,'%d/%m/%Y') AS vencimento,valor,texto_numdoc_origem
            FROM cs2.titulos_acordo 
            WHERE codloja = '$codloja'
            ORDER BY id";
$res = mysql_query($command, $con) or die ("Erro SQL : $command");
$linhas = mysql_num_rows($res);
if ( $linhas > 0 ){
    echo "<form name='form' method='post' action='#' onsubmit='return false;'>
            <table align='center' width='85%' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
                <tr>
                    <td colspan='9' class='titulo'>ACORDOS REALIZADOS</td>
                </tr>
                <tr height='20' bgcolor='87b5ff'>
                    <td align='center' width='9%'>Data Acordo</td>
                    <td align='center' width='9%'>Referência</td>
                    <td align='center' width='9%'>Parcela</td>
                    <td align='center' width='9%'>vencimento</td>
                    <td align='center' width='9%'>Valor</td>
                    <td align='center' width='9%'>Pago</td>
                </tr>
         ";
    while ( $reg = mysql_fetch_array($res) ){
        echo "<tr height='20' bgcolor='87b5ff'>
                 <td align='center' width='9%'>".$reg['data']."</td>
                 <td align='center' width='9%'>".$reg['texto_numdoc_origem']."</td>
                 <td align='center' width='9%'>".$reg['parcela']."</td>
                 <td align='center' width='9%'>".$reg['vencimento']."</td>
                 <td align='center' width='9%'>".number_format($reg['valor'],2,',','.')."</td>
                 <td align='center' width='9%'>".$reg['data_pagamento']."</td>
               </tr>";
    }
}
$res = mysql_close($con);
?>