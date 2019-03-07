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
<style>
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
        width:400px;
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
        width:400px;
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
    #modal-sms button, #modal-manual-sms button {
        position: relative;
        width: 250px;
        height: 25px;
        margin: 10px auto;
        background-color: #0a6aa1;
        color: white;
    }
    #close-modal-sms { text-align: right; padding-top: 8px; font-size: 16px; color: #555; }
    #close-modal-sms span {
        margin-right: 5px;
        padding: 3px 3px;
        cursor: pointer;
        font-size: 18px;
    }
</style>
<script language="javascript">

    // MODAL BOLETO SMS

    var smsId;
    var smsNumber;
    var smsVencimento;
    var smsValor;
    var codLoja_SMS;
    var nomeFantaia_SMS;

    // codLoja_SMS,nomeFantaia_SMS
    function modalSendBoleto(ref,celular_n_mask,vencimento,valor){
        smsId = ref;
        smsNumber = celular_n_mask;
        smsVencimento = vencimento;
        smsValor = valor;
        codLoja_SMS = document.getElementById('codLoja_SMS').value;
        nomeFantaia_SMS = document.getElementById('nomeFantaia_SMS').value;
        if(smsNumber == '0') {
            alert('Não é possível enviar o SMS, porque o número do celular é inválido!');
            return false;
        }
        var fundo_modal = document.getElementById('fundo-modal-sms');
        var modal = document.getElementById('modal-sms');
        if (fundo_modal.style.display == 'none') {
            fundo_modal.style.display = 'block';
            modal.style.display = 'block';
            $('#modalDigitarCodBarraSMS').click(function(){
                var modal_digitar = document.getElementById('modal-manual-sms');
                modal_digitar.style.display = 'block';
            });
        } else {
            fundo_modal.style.display = 'none';
            modal.style.display = 'none';
        }
        //alert(ref);
    }

    function digitarCodBarraSMS() {
        var fundo_modal = document.getElementById('fundo-modal-sms');
        var modal = document.getElementById('modal-sms');
        var modal_digitar = document.getElementById('modal-manual-sms');
        var txtSmsManual = document.getElementById('txtSmsManual').value;
        fundo_modal.style.display = 'none';
        modal.style.display = 'none';
        modal_digitar.style.display = 'none';

        $.ajax({
            url: "../../inform/boleto/boleto.php?numdoc="+smsId+"&barcode",
            type: "POST",
            success: function (data) {
                //alert(data);
                barcode = data;
                $.ajax({
                    url: "sms/enviaBoletoSMS.php",
                    type: "POST",
                    data: {
                        'action':'digitarCodBarraSMS',
                        'barcode':barcode,
                        'smsId':smsId,
                        'smsNumber':smsNumber,
                        'dt_vencimento':smsVencimento,
                        'valor':smsValor,
                        'codLoja_SMS':codLoja_SMS,
                        'nomeFantaia_SMS':nomeFantaia_SMS,
                        'txtSmsManual': txtSmsManual
                    },
                    success: function (data) {
                        console.log(data);
                        alert(data);
                    }
                });
            }
        });
    }

    function enviarCodBarraSMS() {
        //alert(smsId);
        $.ajax({
            url: "../../inform/boleto/boleto.php?numdoc="+smsId+"&barcode",
            type: "POST",
            success: function (data) {
                //alert(data);
                barcode = data;
                $.ajax({
                    url: "sms/enviaBoletoSMS.php",
                    type: "POST",
                    data: {
                        'action':'enviarCodBarraSMS',
                        'barcode':barcode,
                        'smsId':smsId,
                        'smsNumber':smsNumber,
                        'dt_vencimento':smsVencimento,
                        'valor':smsValor,
                        'codLoja_SMS':codLoja_SMS,
                        'nomeFantaia_SMS':nomeFantaia_SMS
                    },
                    success: function (data) {
                        console.log(data);
                        alert(data);
                    }
                });
            }
        });


    }
    function closeModalSms() {
        var fundo_modal = document.getElementById('fundo-modal-sms');
        var modal = document.getElementById('modal-sms');
        var modal_digitar = document.getElementById('modal-manual-sms');
        fundo_modal.style.display = 'none';
        modal.style.display = 'none';
        modal_digitar.style.display = 'none';
    }

    function alerta() {
        if (confirm("CONFIRMA O CANCELAMENTO DO RECEBIMENTO DE TÍTULO ?")) {
        } else {
            return false
        }
    }

    function alerta_desconto() {
        if (confirm("CONFIRMA LANÇAMENTO NESTA FATURA ?")) {
        } else {
            return false
        }
    }

    function afixar(form, idDiv, nboleto, cliente, sitcli) {

        if ( sitcli == 2 ){
            alert('Cliente CANCELADO !!! ');
            abort;
        }

        div = document.getElementById(idDiv);

        $('#botaoOK').removeAttr('onClick');
        $('#botaoOK').attr("onClick", "valida_user('" + nboleto + "','" + cliente + "')");

        if (div.style.display == 'none') {
            div.style.display = 'block';
        } else {
            div.style.display = 'none';
        }

    }

    function limpar(form, idDiv) {
        document.form.dt_regularizacao.value = null;
        document.form.senha_user.value = null;
        $('#nome_usuario').text('');
        div = document.getElementById(idDiv);
        if (div.style.display == 'none')
            div.style.display = 'block';
        else
            div.style.display = 'none';
    }

    function valida_user(nboleto, codloja) {
        frm = document.form;

        if (confirm("CONFIRMA LANÇAMENTO NESTA FATURA ?")) {
        } else {
            return false
        }

        var usuario = frm.senha_user.value;
        if (usuario == '') {
            alert('Favor informar a senha para autorizacao');
            exit;
        }
        var req = new XMLHttpRequest();
        req.open('GET', "connect/valida_user.php?usuario=" + usuario, false);
        req.send(null);
        if (req.status != 200)
            return '';
        var resposta = req.responseText;
        var array = resposta.split(';');
        var id = array[0] - 1;
        var nome = array[1];

        $('#nome_usuario').text(nome);

        if (nome) {

            frm = document.form;
            frm.action = 'painel.php?pagina1=clientes/a_fatura_desconto.php&numdoc=' + nboleto + '&codloja=' + codloja;
            frm.submit();

        } else {
            alert(' SENHA INVALIDA ');
        }
    }

    function Altera_Vencimento(d1, d2, d3) {
        $('#vencimento_original').val(d1);
        $('#valor_original').val(d2);
        $('#numdoc_titulo').val(d3);
        $('#Modal_Vencimento').modal('show');
        $('#vencimento_atual').focus();
    }

    function Altera_Vencimento_Confirma() {

        $.ajax({
            url: "clientes/a_titulo_atualiza.php?update",
            type: "POST",
            data: {
                'numdoc': $('#numdoc_titulo').val(),
                'data_vencimento': $('#vencimento_atual').val()
            },
            success: function (data) {
                if (data == 1) {
                    //location.reload();
                    console.log(data);
                } else {
                    console.log(data);
                }
            }
        });

    }

    $(document).ready(function () {
        $('.mask-data').mask('00/00/0000');
    });

</script>

<script language="javascript">

    function geraNotificacao(p_codloja, p_soma) {

        if (p_soma == '0') {
            alert('Atencao !  Nao existem debito para este cliente.');
        } else {
            popup = window.open('clientes/popup_notificacao_data.php?codloja=' + p_codloja + '&soma=' + p_soma, 'janela', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=' + 700 + ',height=' + 700 + ',left=' + 5 + ', top=' + 5 + ',screenX=' + 100 + ',screenY=' + 100 + '');
        }
    }

    function grava_cobradora(boleto, option) {

        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET", "clientes/atualiza_cobradora_titulos.php?cobradora=" + option + "&numdoc=" + boleto, true);
        xmlhttp.send();
    }
</script>

<?php
// pega o saldo do crediario/credupere

$saldo_crediario = '0,00';
//echo "<br>".date('H:m:s');

$sql_saldo = "SELECT saldo FROM cs2.contacorrente_recebafacil WHERE codloja='$codloja' order by id";
$qr2 = mysql_query($sql_saldo, $con) or die("\nErro ao gerar o extrato\n" . mysql_error() . "\n\n");
//echo "<br>".date('H:m:s');
while ($matriz = mysql_fetch_array($qr2)) {
    $saldo_crediario = number_format($matriz['saldo'], 2, ",", ".");
    $sdo_crediario = $matriz['saldo'];
}
//echo "<br>".date('H:m:s');
$sql_cliente = "select mid(a.logon,1,5) as logon, b.razaosoc, b.nomefantasia,
                    b.fone, b.fax, b.celular, b.email, b.sitcli
                from logon a
                inner join cadastro b on a.codloja=b.codloja
                where b.codloja = '$codloja'";
//echo "<br>".date('H:m:s');
$resulta = mysql_query($sql_cliente, $con);
//echo "<br>".date('H:m:s');
$linha = mysql_num_rows($resulta);
if ($linha > 0) {
    $matriz = mysql_fetch_array($resulta);
    $logon = $matriz['logon'];
    $razaosoc = $matriz['razaosoc'];
    $razaosoc = $matriz['nomefantasia'];
    $fone = mascara_celular($matriz['fone']);
    $fax = mascara_celular($matriz['fax']);
    $celular_n_mask = $matriz['celular'];
    $email = $matriz['email'];
    $sitcli = $matriz['sitcli'];
//  $operadora = ver_operadora($celular);
    $celular = mascara_celular($matriz['celular']);
}

// VERIFICA SE O CLIENTE TEM BOLETO DE MULTA E NAO FOI PAGO
//echo "<br>".date('H:m:s');
$command = "SELECT 
                numdoc AS boleto, date_format(vencimento,'%d/%m/%Y') AS venc, valor, 
                date_format(datapg,'%d/%m/%Y') AS dtpagamento, valorpg, origem_pgto, 
                vencimento, isento_juros, referencia, 
                date_format(vencimento_alterado,'%d/%m/%Y') AS venc_alterado_view, vencimento_alterado as venc_alterado
            FROM cs2.titulos 
            WHERE codloja = '$codloja' AND referencia = 'MULTA' AND valorpg is null";
$res = mysql_query($command, $con);

if (mysql_num_rows($res) == 0) {
    $param1 = '';
    $param2 = '';
} else {
    if ( mysql_result($res, 0, 'venc') != '' )
        $param1 = mysql_result($res, 0, 'venc_alterado_view');
    else
        $param1 = mysql_result($res, 0, 'venc');
    $param2 = mysql_result($res, 0, 'valor');
}

$texto_boleto_multa = '';

if (mysql_num_rows($res) > 0)
    $texto_boleto_multa = "Multa Contratual Pendente : Venc: $param1 - R$ $param2";


//pega da tabela titulos todas as ocorrencias para esse codloja
$command = "SELECT 
                numdoc AS boleto, date_format(vencimento,'%d/%m/%Y') AS venc, valor, 
                date_format(datapg,'%d/%m/%Y') AS dtpagamento, valorpg, origem_pgto, 
                vencimento, isento_juros, referencia, 
                date_format(vencimento_alterado,'%d/%m/%Y') AS venc_alterado_view,
                vencimento_alterado as venc_alterado
            FROM cs2.titulos 
            WHERE codloja = '$codloja'
            AND numboleto IS NOT NULL ORDER BY vencimento";
$res = mysql_query($command, $con);
$linhas = mysql_num_rows($res);
$linhas1 = $linhas + 3;

//comeca a tabela
?>
<input type="hidden" id="codLoja_SMS" value="<?=$codloja?>">
<input type="hidden" id="nomeFantaia_SMS" value="<?=$razaosoc?>">
<!-- MODAL SMS -->
<div id="fundo-modal-sms" onclick="closeModalSms()">&nbsp;</div>
<div id="modal-sms" style="display:none;">
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" id="close-modal-sms">
                    <span aria-hidden="true" onclick="closeModalSms()">&times;</span>
                </div>
                <div class="modal-body">
                    <button onclick="enviarCodBarraSMS()">Enviar Código de Barras por SMS</button>
                    <button id="modalDigitarCodBarraSMS">Digitar Código de Barras por SMS</button>
<!--                    <ul>-->
<!--                        <li><a onclick="enviarCodBarraSMS()" style="cursor: pointer;">Enviar Código de Barras por SMS</a></li>-->
<!--                        <li><a id="modalDigitarCodBarraSMS" style="cursor: pointer;">Digitar Código de Barras por SMS</a></li>-->
<!--                    </ul>-->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ENVIO SMS MANUAL -->
<div id="modal-manual-sms" style="display:none;">
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" id="close-modal-sms">
                    <span aria-hidden="true" onclick="closeModalSms()">&times;</span>
                </div>
                <div class="modal-body">
                    <textarea maxlength="55" name="txtSmsManual" id="txtSmsManual" cols="30" rows="10" placeholder="Digite aqui..."></textarea>
                    <br>
                    <button onclick="digitarCodBarraSMS()">Enviar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<form name='form' method='post' action='#' onsubmit='return false;'>
    <?php
    echo "
        <table align='center' width='85%' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
            <tr>
                <td colspan='15' class='titulo'>Faturas</td>
            </tr>
            <tr>
                <td colspan='4' class='subtitulodireita'>Codigo</td>
                <td colspan='11' class='subtitulopequeno'>$logon</td>
            </tr>
            <tr>
                <td colspan='4' class='subtitulodireita'>Razao Social</td>
                <td colspan='11' class='subtitulopequeno'>$razaosoc</td>
            </tr>
            <tr>
                <td colspan='4' class='subtitulodireita'>Telefones</td>
                <td colspan='11' class='subtitulopequeno'>$fone<br>$fax<br>$celular - $operadora</td>
            </tr>
            <tr>
                <td colspan='4' class='subtitulodireita'>Email</td>
                <td colspan='11' class='subtitulopequeno'>$email</td>
            </tr>
            <tr>
                <td rowspan='$linhas1' width='1' bgcolor='#999999'></td>
            </tr>
            <tr height='20' bgcolor='87b5ff'>
                <td align='center'  width='9%'>No. Boleto</td>
                <td align='center'  width='9%'>Venc. Original</td>
                <td align='center'  width='9%'>Venc. Atualizado</td>
                <td align='center'  width='9%'>Vr. Original</td>
                <td align='center'  width='9%'><font color='red'>Desconto<br>Abatimento</font></td>
                <td align='center'  width='9%'><font color='#0000e6'>Acrescimo</font></td>
                <td align='center'  width='9%'><b>Valor Atualizado</b></td>
                <td align='center'  width='9%'>Data Pagamento</td>
                <td align='center'  width='9%'>Valor Pago</td>
                <td align='center'  width='9%'>&nbsp;</td>
                <td align='center'  width='9%'>Origem</td>
                <td align='center'  width='9%'>&nbsp;</td>
                <td align='center'  width='9%'> ... </td>
                <td align='center'  width='10%'> ... </td>
                <td align='center'  width='10%'> Auxiliar de Cobran&ccedil;a </td>
            </tr>
            <tr>
                <td colspan='13' height='1' bgcolor='#666666'></td>
            </tr>";

    $celular_ddd = substr($celular_n_mask,0,2);
    $celular_digito_9 = substr($celular_n_mask,2,1);
    $celular_number = substr($celular_n_mask,3,8);

    $celular_valid = true;

    if($celular_digito_9 != 9){
        $celular_valid = false;
        $celular_n_mask = 0;
    }else if(strlen($celular_number) < 8){
        $celular_valid = false;
        $celular_n_mask = 0;
    }

    for ($a = 1; $a <= $linhas; $a++) {

        $matriz = mysql_fetch_array($res);
        $boleto = $matriz['boleto'];

        // Verificando se o titulo tem desconto
        $sql_desconto = "SELECT sum(desconto) AS desconto, sum(acrescimo) AS acrescimo  FROM cs2.titulos_movimentacao
                         WHERE numdoc = '$boleto'";
        $qry_desconto = mysql_query($sql_desconto, $con);
        $valor_desconto = mysql_result($qry_desconto, 0, 'desconto');
        $valor_acrescimo = mysql_result($qry_desconto, 0, 'acrescimo');

        $venc_alter      = $matriz['venc_alterado'];
        $venc_alterado_o = $matriz['venc_alterado'];
        $venc_alterado_o = substr($venc_alterado_o, 8, 2) . '/' . substr($venc_alterado_o, 5, 2) . '/' . substr($venc_alterado_o, 0, 4);

        $venc_alterado = $matriz['venc_alterado'];
        $venc_alterado_view = $matriz['venc_alterado_view'];
        $venc_original = $matriz['venc'];
        $venc = $matriz['venc'];

        $vencSMS = $venc;

        if ($venc_alterado != '')
            $venc = $venc_alterado_view;

        $valor_original = $matriz['valor'];
        $valor = $matriz['valor'] - $valor_desconto;
        $referencia = $matriz['referencia'];
        $dtpagamento = $matriz['dtpagamento'];

        # Ver cobradora
        $sql_cob = "SELECT id_cobradora FROM cs2.titulos_cobradora
                    WHERE numdoc = '$boleto'
                    ORDER BY id DESC 
                    Limit 1";
        $xres = mysql_query($sql_cob, $con);

        if (mysql_num_rows($xres) == 0) {
            $id_cobradora = '';
        } else {
            $id_cobradora = mysql_result($xres, 0, 'id_cobradora');
        }

        if ($_SESSION['id'] != 163) // SOMENTE PARA O WELLINGTON
            $ativo = " disabled='disabled' ";

        $sql_cob = "SELECT id as idcob, nome FROM cs2.funcionario WHERE ativo='S' AND id_funcao = 12";
        $cobradora = "<select id='cobradora' $ativo onchange='grava_cobradora($boleto,this.value)'>";
        $xres = mysql_query($sql_cob, $con);
        if (mysql_num_rows($xres) > 0) {
            $cobradora .= "<option value='0'>Selecione</option>";
            while ($xreg = mysql_fetch_array($xres)) {
                $id_cob = $xreg['idcob'];
                $nome = substr($xreg['nome'], 0, 20);
                if ($id_cob == $id_cobradora)
                    $cobradora .= "<option value='$id_cob' selected='selected'>$nome</option>";
                else
                    $cobradora .= "<option value='$id_cob'>$nome</option>";
            }
            $cobradora .= "<select>";
        } else
            $cobradora = '';

        if (( $referencia <> 'MULTA' ) or ( $referencia == 'MULTA' and $dtpagamento <> '' )) {

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
            $teste_multa = 0;
            if ($dif > 0) {

                $nvalor = str_replace(',', '.', $valor);
                $multa = ($nvalor+$valor_acrescimo) * 0.02 ; // 2%
                
                $encargos = ( ( $nvalor + $valor_acrescimo) * 0.0015 ) * $dif;
                $xencargos = number_format($encargos, 2, ',', '.');

                $encargos = number_format($encargos, 2);

                $_valor = ( $nvalor + $multa + $encargos + $valor_acrescimo );

                $multa = number_format($multa, 2, ',', '.');
                $valor_cobrado = number_format($_valor, 2, ',', '.');
                
            } else {
                
                $valor_cobrado = number_format($valor+$valor_acrescimo, 2, ',', '.');
                
            }
            
            /* ****************************************** */
            $img_altera_vencimento = '';
            $valor_original = number_format($valor_original, 2, ',', '.');
            $dtpagamento = $matriz['dtpagamento'];
            $valorpg = $matriz['valorpg'];
            $origem = $matriz['origem_pgto'];
            if (!$valorpg)
                $soma = $soma + $valor;

            /* Converte a Data para o formato Americano para fazer a comparação */
            $venc_originalAux        = str_replace('/', '-', $venc_original);
            $venc_originalComparacao = date('Y-m-d', strtotime($venc_originalAux));

            if ($venc_originalComparacao < $data_atual AND $dtpagamento == "") {
                echo "<tr height='22' bgcolor='#E5E5E5'>
                           <!-- <td align='center'><u><a href='../../inform/boleto/boleto-atraso.php?numdoc=$boleto'><font color='blue'>$boleto</font></a></u></td> -->
                                <td align='center'><u><a href='../../inform/boleto/boleto.php?numdoc=$boleto'><font color='blue'>$boleto</font></a></u></td>
                                


                            <td align='center'>$venc_original</td>";
            }else{
                echo "<tr height='22' bgcolor='#E5E5E5'>
                            <td align='center'><u><a href='../../inform/boleto/boleto.php?numdoc=$boleto'><font color='blue'>$boleto</font></a></u></td>
                            <td align='center'>$venc_original</td>";                
            }

            if ($venc_alterado != '')
                echo "<td align='center'>$venc</td>";
            else
                echo "<td align='center'>&nbsp;</td>";

            echo "  <td align='center'>$valor_original</td>";

            if ($valor_desconto > 0)
                echo "  <td align='center'><font color='red'>" . number_format($valor_desconto, 2, ',', '.') . "<font></td>";
            else
                echo "  <td align='center'>&nbsp;</td>";

            echo "  <td align='center'><font color='#0000e6'>$valor_acrescimo</font></td>";

            echo"        <td align='center'>";

            $btnSms = "<span onclick=\"modalSendBoleto({$boleto},'{$celular_n_mask}','{$vencSMS}','{$valor_original}')\" title='Enviar Boleto por SMS' style='border-radius:2px;background:rgba(1,124,194,0.8);padding:5px;color:#FFF;cursor:pointer;'><i class='fas fa-sms'></i></span>";

            if ($dtpagamento == "") {
                // NAO PAGO
                echo "<b>" . trim($valor_cobrado . ' ' . $txt_valorcobrado) . "</b>";
                $img_pg = '&nbsp;';
                if ($saldo_crediario > 0) {
                    if ($dif >= 10) {
                        if ($sdo_crediario >= $_valor) {
                            $img_cancel_pgto = "<a href=\"painel.php?pagina1=Franquias/b_baixartitulo_crediario.php&numdoc=$boleto&codloja=$codloja&valorcomjuros=$valor_cobrado&logon=$logon\" onMouseOver=\"window.status='Recebimento de Título usando o SALDO CREDOR DO CREDÁRIO/RECUPERE/BOLETO'; return true\" title='Quitar Titulo usando SALDO CREDÁRIO/RECUPERE/BOLETO'><IMG SRC=\"../img/compensacao.gif\" width=\"70\" height=\"16\" border=\"0\"></a>";
                        } else
                            $img_cancel_pgto = '&nbsp;';
                    } else
                        $img_cancel_pgto = '&nbsp;';
                }else {
                    $img_cancel_pgto = '&nbsp;';
                }
                if ($_SESSION['id'] == 163 or $_SESSION['id'] == 4 or $_SESSION['id'] == 1204) {
                    $img_desconto = "<a style='position:relative;top:4px;' onClick=\"afixar(form,'senhauser','$boleto','$codloja','$sitcli')\"><img src='../img/menos.gif' width='16' height='16' border='0' /></a>";
                }

            } else {
                
                $img_pg = "<img src='../../franquias/img/img_v.gif'> Pago";
                if ($_SESSION['id'] == 163 && ( $origem == 'FRANQUIA' or $origem == 'BANCO')) {
                    $img_cancel_pgto = "<a style='position:relative;top:4px;'  href=\"painel.php?pagina1=Franquias/b_cancelabaixa.php&numdoc=$boleto&codloja=$codloja\" onMouseOver=\"window.status='Cancela Recebimento de Título'; return true\" title='Clique para cancelar o recebimento do Titulo' onclick='return alerta()'><IMG SRC='../img/exclaim.gif' width='16' height='16' border='0'></a>";
                } else {
                    $img_cancel_pgto = '&nbsp;';
                }

                $btnSms = '';
                
            }

            echo "</td>
                        <td align='center'>$dtpagamento</td>
                        <td align='center'>$valorpg</td>
                        <td align='center'>$img_pg</td>
                        <td align='center'>$origem</td>
                        <td align='center'>$btnSms</td>
                        <td align='center'>$img_cancel_pgto</td>
                        <td align='center'>" . $img_altera_vencimento . $img_desconto . "</td>
                        <td align='center'>$cobradora</td>
                </tr>";
        }
    }
    $somax = number_format($soma, 2);

    echo "
        <tr height='20' class='subtitulodireita'>
            <td colspan='8' align='left'>$texto_boleto_multa</td>
            <td colspan='7'>Soma das Faturas Mensais não pagas: R$ $somax</td>
            <td></td>           
        </tr>
        <tr>
            <td colspan='15' height='1' bgcolor='#666666'></td>
        </tr>
        <tr>
            <td colspan='15' align='center' bgcolor='red'>
                <div id='senhauser' style='display: none;color:#FFF'>
                    Favor Informar a Senha :
                    <input type='password' name='senha_user' id='senha_user' />
                    <input type='button' value='[ OK ]' id='botaoOK' onclick='valida_user($boleto, $codloja)'  />
                </div>
            </td>
        </tr>           
    </table>";
    $res = mysql_close($con);
    echo "<br />";

    include('a_ver_faturas_antecipacao.php');

    $total_geral = $soma + $soma_antecipacao;
    $total_geral = number_format($total_geral, 2, ',', '.');

    echo "
    <table align='center' width='85%' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
        <tr height='40' class='subtitulodireita' >
            <td>Total Geral do D&eacute;bito</td>
            <td>R$ $total_geral</td>
        </tr>
        <tr>
            <td colspan='2'>&nbsp;</td>
        </tr>
            <tr height='40' class='titulo'>
            <td>SALDO CREDI&Aacute;RIO / RECUPERE</td>
            <td>R$ $saldo_crediario</td>
        </tr>
        <tr>
            <td colspan='2'>&nbsp;</td>
        </tr>
    </table>";
    ?>
    <table border="0" align="center">
        <tr>
            <td width="40%" align="left"><input type="button" onClick="geraNotificacao(<?= $codloja ?>, '<?= $soma ?>');" value="Gerar Comunicado de Débito" /></td>
            <td width="20%">&nbsp;</td>
            <td width="40%" align="right"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
        </tr>
    </table>

    <form>

        <script>

            $(document).keydown(function (e) {
                if ($('#senha_user').is(':focus') && e.keyCode == 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#botaoOK').click();
                }
            })

        </script>