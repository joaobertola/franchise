<?php
/**
 * @file b_boletopdf.php
 * @brief
 * @author ARLLON DIAS
 * @date 13/10/2016
 * @version 1.0
 * */
require "connect/sessao.php";
require "connect/funcoes.php";

$go = $_POST['go'];
$codigo = $_POST['codigo'];
?>
<script language="javascript">

    function calculaDiferenca(dataInicial, dataFinal) {

        var date1 = new Date(dataInicial);
        var date2 = new Date(dataFinal);
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

    }

    function converte_data(data) {
        var ano = data.substr(6, 4);
        var mes = data.substr(3, 2);
        var dia = data.substr(0, 2);

        return ano + '/' + mes + '/' + dia;
    }

    function enviarEmailPDF() {

        var numDoc = document.getElementById('numdoc').value;
        var de = document.getElementById('de').value;
        var para = document.getElementById('para').value;
        var assunto = document.getElementById('assunto').value;
        var venc_atualizado = document.getElementById('vencimento_atualizado').value;
        var venc_atualizado_confirmado = document.getElementById('venc_atualizado_confirmado').value;

        // travar para nao gravar vencimento invalido
        var d = new Date();
        var dataHora = (d.toLocaleString());
        var data = dataHora.substr(0, 10);


        var dataAtual = parseInt(data.split("/")[2] + data.split("/")[1] + data.split("/")[0]);
        var vencimento = parseInt(venc_atualizado.split("/")[2] + venc_atualizado.split("/")[1] + venc_atualizado.split("/")[0]);

        var dataAtualComparar = new Date(dataAtual);
        var dataVencimentoComparar = new Date(vencimento);

        if (dataVencimentoComparar < dataAtualComparar) {
            alert('O Vencimento deve ser maior que a Data Atual!');
            return false;
        }

        window.location.href = '../../inform/boleto/boleto.php?numdoc=' + numDoc + '&link_externo=sim&de=' + de + '&para=' + para + '&assunto=' + assunto + '&venc_atualizado=' + venc_atualizado + '&venc_atualizado_confirmado=' + venc_atualizado_confirmado;

    }
    window.onload = function () {
        document.enviaEmail.vencimento_atualizado.focus();
    }
</script>

<?php
if (empty($go))
    $go = $_GET['go'];

if (empty($go)) {
    ?>
    <script language="javascript">

        function check(Form) {
            var retorno = true;
            if (document.AltCliente.codigo.value == "")
            {
                window.alert("Informe um Código de Cliente!");
                document.AltCliente.codigo.focus();
                return false;
            }
            if (document.AltCliente.codigo.value == 0)
            {
                window.alert("Informe um Código diferente de 0");
                document.AltCliente.codigo.focus();
                return false;
            }
            if (isNumeroString(document.AltCliente.codigo.value) != 1)
            {
                window.alert("Informe um Código numérico!");
                document.AltCliente.codigo.focus();
                return false;
            }
            document.AltCliente.submit();
            return (true);
        }
    </script>

    <form name="AltCliente" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
        <div>
            <table width=70% align="center">
                <tr>
                    <td colspan="2" align="center" class="titulo">EMISS&Atilde;O DE SEGUNDA VIA DE BOLETOS DE PAGAMENTO</td>
                </tr>
                <tr>
                    <td width="173" class="subtitulodireita">&nbsp;</td>
                    <td width="224" class="campoesquerda">&nbsp;</td>
                </tr>
                <tr>
                    <td class="subtitulodireita">C&oacute;digo do cliente</td>
                    <td class="campoesquerda">
                        <input type="text" name="codigo" id="codigo" size="10" maxlength="6" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" />
                        <input type="hidden" name="go" value="ingressar" /></td>
                </tr>
                <tr>
                    <td class="subtitulodireita">&nbsp;</td>
                    <td class="campoesquerda"><?php echo $nome_franquia; ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo">&nbsp;</td>
                </tr>
                <tr align="right">
                    <td colspan="2"><input type="submit" value=" Enviar Consulta" name="enviar" onClick="return check(this.form);" /></td>
                </tr>
            </table>
        </div>
    </form>
    <?php
} // fim go null

if ($go == 'ingressar') {
    if ($tipo == "b")
        $rfq = "and id_franquia='$id_franquia'";
    else
        $rfq = "";

    $sql = "select b.codloja, a.logon, b.id_franquia, b.razaosoc, b.fone, b.cidade, b.uf, b.email from cs2.logon a
            inner join cs2.cadastro b on a.codloja=b.codloja
            where mid(logon,1,5)='$codigo' $rfq";
    $resulta = mysql_query($sql, $con) or die("Erro ao selecionar o codigo");
    $linha = mysql_num_rows($resulta);
    if ($linha == 0) {
        print "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\"); javascript: history.back();</script>";
    } else {
        $matriz = mysql_fetch_array($resulta);
        $codloja = $matriz['codloja'];
        $razaosoc = $matriz['razaosoc'];
        $fone = $matriz['fone'];
        $cidade = $matriz['cidade'];
        $uf = $matriz['uf'];
        $email = $matriz['email'];
    }
    ?>
    <table width=70% align="center">
        <tr>
            <td colspan="2" align="center" class="titulo">EMISS&Atilde;O DE SEGUNDA VIA DE BOLETOS DE PAGAMENTO</td>
        </tr>
        <tr>
            <td width="173" class="subtitulodireita">&nbsp;</td>
            <td width="224" class="campoesquerda">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitulodireita">C&oacute;digo do cliente</td>
            <td class="subtitulopequeno"><?php echo $codigo; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Raz&atilde;o Social</td>
            <td class="subtitulopequeno"><?php echo $razaosoc; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Telefone</td>
            <td class="subtitulopequeno"><?php echo $fone; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Cidade</td>
            <td class="subtitulopequeno"><?php echo $cidade . " - " . $uf; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Boletos Mensalidades:</td>
            <?php
            $sql = "SELECT 
                    vencimento, 
                    valor, 
                    numdoc, 
                    referencia, 
                    date_format(vencimento,'%d/%m/%Y') as venc2,
                    date_format(vencimento_alterado,'%d/%m/%Y') as venc_alterado
		  FROM cs2.titulos
		  WHERE codloja='$codloja' AND datapg IS NULL
		  ORDER BY vencimento ASC";
            $qr = mysql_query($sql, $con) or die("\n erro no segundo\n" . mysql_error() . "\n\n");
            $nreg = mysql_num_rows($qr);
            if ($nreg == 0) {
                echo "<td class='campojustificado' style='padding-left:5px'>
				<b>Este cliente n&atilde;o registra boletos em aberto para este periodo</b>
			</td>";
            } else {
                $registro = '';
                for ($i = 0; $i < $nreg; $i++) {
                    $mes_ano = mysql_result($qr, $i, "vencimento");
                    $vencimento = mysql_result($qr, $i, "venc2");
                    $valor = mysql_result($qr, $i, "valor");
                    $valor = number_format($valor, 2, ',', '.');

                    $mes_ano = substr($mes_ano, 5, 2) . "/" . substr($mes_ano, 0, 4);
                    $ref = mysql_result($qr, $i, "referencia");
                    if ($ref <> 'MULTA') {
                        $boleto = $mes_ano;
                        $numdoc = mysql_result($qr, $i, "numdoc");
                        $registro .= "<a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc\">$vencimento</a>&nbsp;&nbsp;
		<a href=\"painel.php?pagina1=Franquias/b_boleto.php&go=email&numdoc=$numdoc&email=$email&vencimento=$vencimento\">e-mail <img src='../../../images/aaMsgNaoLida.gif' border='0' ></a>
		<br>";
                    }
                }
                echo "<td class=\"campoesquerda\" style=\"padding-left:5px\">
					<p>$registro</p>
			 </td>";
            } //fim else
            ?>
        </tr>

        <!--  BOLETO DE ANTECIPACAO   -->

        <tr>
            <td class="subtitulodireita">Boletos Antecipa&ccedil;&atilde;o:</td>
            <td class="campoesquerda"><br>
                <?php
                $command = "SELECT
				a.numboleto AS boleto, date_format(a.vencimento,'%d/%m/%Y') AS venc, a.valor,
				date_format(a.datapg,'%d/%m/%Y') AS dtpagamento, a.valorpg,
				a.vencimento, a.contrato, date_format(b.data_vencimento,'%d/%m/%Y') as venc_orig,
				b.valor_parcela as vr_orig
		FROM    cs2.titulos_antecipacao a
		inner join cs2.cadastro_emprestimo b ON a.contrato = b.protocolo AND a.id_antecipacao = b.id
		WHERE a.valorpg is NULL AND a.codloja = '$codloja'
		ORDER BY vencimento";

                $res = mysql_query($command, $con) or die("Erro: SQL : $command)");
                $linhas = mysql_num_rows($res);

                if ($linhas > 0) {

                    for ($a = 1; $a <= $linhas; $a++) {
                        $matriz = mysql_fetch_array($res);
                        $boleto = $matriz['boleto'];
                        $venc = $matriz['venc'];
                        $valor = $matriz['valor'];
                        $contrato = $matriz['contrato'];
                        $venc_orig = $matriz['venc_orig'];
                        $vr_orig = $matriz['vr_orig'];
                        $vr_orig = number_format($vr_orig, 2, ",", ".");

                        echo "
					<a href=\"https://www.webcontrolempresas.com.br/inform/boleto_antecipa/boleto.php?numdoc=$boleto\">Boleto $venc_orig</a>&nbsp;&nbsp;
		<a href=\"painel.php?pagina1=Franquias/b_boleto.php&go=email_antecipa&numdoc=$boleto&email=$email&vencimento=$vencimento\">e-mail <img src='../../../images/aaMsgNaoLida.gif' border='0' ></a>
		<br>";
                    }
                }
                ?>
                <br>
            </td>
        </tr>


        <!--  BOLETO DE MULTA CONTRATUAL   -->

        <tr>
            <td class="subtitulodireita">Boleto Multa Contratual:</td>
            <td class="campoesquerda"><p><?php
                    $sql = "	Select vencimento, valor, numdoc, referencia , date_format(vencimento,'%d/%m/%Y') as venc2
					FROM cs2.titulos
					where codloja='$codloja' and datapg is null and referencia = 'MULTA'
					order by vencimento asc";
                    $qr = mysql_query($sql, $con) or die("\n erro no segundo\n" . mysql_error() . "\n\n");
                    if (mysql_num_rows($qr) > 0) {
                        $vencimento = mysql_result($qr, 0, "venc2");
                        $mes_ano = mysql_result($qr, 0, "vencimento");
                        $mes_ano = substr($mes_ano, 5, 2) . "/" . substr($mes_ano, 0, 4);
                        $ref = mysql_result($qr, 0, "referencia");
                        $boleto1 = $mes_ano;
                        $numdoc1 = mysql_result($qr, 0, "numdoc");
                        $envelope1 = "e-mail <img src=\"../../../images/aaMsgNaoLida.gif\" border=\"0\" >";
                        echo "&nbsp;&nbsp;<a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc1\">Boleto $vencimento</a>&nbsp;&nbsp;
		<a href=\"painel.php?pagina1=Franquias/b_boleto.php&go=email_multa&numdoc=$numdoc1&email=$email&vencimento=$vencimento\">$envelope1</a>";
                    }
                    ?>
                </p>
            </td>
        </tr>


        <tr>
            <td colspan="2" class="titulo">&nbsp;</td>
        </tr>
        <tr align="right">
            <td colspan="2"><input type="button" onClick="javascript: history.back();" value="              Voltar" /></td>
        </tr>
    </table>
    <?php
}//fim go=ingressar

if ($go == 'email') {

    $numdoc = $_GET['numdoc'];
    $email = $_GET['email'];
    $vencimento = $_GET['vencimento'];
    $venc_alterado = $_GET['venc_alterado'];
    $date = date("d/m/Y", time());

    $sql = "SELECT 
            vencimento, 
            valor, 
            date_format(vencimento,'%d/%m/%Y') as venc2,
            date_format(vencimento_alterado,'%d/%m/%Y') as venc_alterado
          FROM cs2.titulos
          WHERE numdoc='$numdoc'";
    $qr = mysql_query($sql, $con) or die("\n erro no segundo\n" . mysql_error() . "\n\n");
    $nreg = mysql_num_rows($qr);
    for ($i = 0; $i < $nreg; $i++) {
        $mes_ano = mysql_result($qr, $i, "vencimento");
        $vencimento = mysql_result($qr, $i, "venc2");
        $venc_original = mysql_result($qr, $i, "venc2");
        $valor = mysql_result($qr, $i, "valor");
        $valor = number_format($valor, 2, ',', '.');
    }


    if ($venc_alterado != '') {
        $venc_mostra = $venc_alterado;
        $dif = diferenca_entre_datas($date, $venc_alterado, 'DD/MM/AAAA');
    } else {
        $venc_mostra = $vencimento;
        $dif = diferenca_entre_datas($date, $vencimento, 'DD/MM/AAAA');
    }

    if ($dif <= 0) {
        $disabled = 'disabled';
    }
    ?>
    <script type="text/javascript">
    <?php
    if (date('Y-m-d', strtotime(str_replace('/', '-', $_GET['vencimento']))) < date('Y-m-d') && !isset($_GET['venc_alterado'])) {
        ?>
            $(document).ready(function () {
                $('#btn-hideemail').css({'display': 'none'});
            });
        <?php
    }
    ?>
    </script>
    <form name="enviaEmail" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="numdoc" id="numdoc" value="<?= $numdoc ?>">
        <table width="70%" align="center">
            <tr>
                <td colspan="2" align="center" class="titulo">EMISS&Atilde;O DE SEGUNDA VIA DE BOLETOS DE PAGAMENTO</td>
            </tr>
            <tr>
                <td width="25%" class="subtitulodireita">&nbsp;</td>
                <td width="75%" class="campoesquerda">&nbsp;</td>
            </tr>
            <tr>
                <td width="25%" class="subtitulodireita">Numero Boleto</td>
                <td width="75%" class="campoesquerda"><?php echo $numdoc; ?></td>
            </tr>
            <tr>
                <td width="25%" class="subtitulodireita">Vencimento Original</td>
                <td width="75%" class="campoesquerda">
                    <input type="text" name="vencimento_original" id="vencimento_original" value="<?php echo $vencimento; ?>"  class="boxnormal" disabled />
                </td>
            </tr>
            <tr>
                <td width="25%" class="subtitulodireita">Valor Original</td>
                <td width="75%" class="campoesquerda">
                    <input type="text" name="valor_original" id="valor_original" value="<?php echo $valor; ?>"  class="boxnormal" disabled />
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">De:</td>
                <td class="subtitulopequeno">
                    <input type="text" name="de" id="de" size="35" value="financeiro@webcontrolempresas.com.br" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" />
                </td>
            </tr>
            <tr >
                <td class="subtitulodireita" style="background-color:#FFFF00">Informar a nova data para pagamento:</td>
                <td class="subtitulopequeno" style="background-color:#FFFF00">
                    <input type="hidden" id="venc_atualizado_confirmado"  name="venc_atualizado_confirmado" value=""/>
                    <input type="hidden" id="vencimento_atualizado"  name="venc_atualizado" value="<?php echo $venc_mostra; ?>"/>

                    <input type="text" id="vencimento_atualizado1" size="35" value="<?php echo $venc_mostra; ?>" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" disabled />
                    <?php if ($disabled == '') { ?>
                        <a onclick="opemCalendario()"><img width="20px" src="../img/calendar-icon_34471.png"/></a>
                        <div style="position:absolute;width:200px;min-height:150px;background:#FFF;display:none;" id="calendario-box"></div>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita" style="background-color:#FFFF00">Valor Atualizado:</td>
                <td class="subtitulopequeno" style="background-color:#FFFF00">
                    <input type="text" name="valor_atualizado" id="valor_atualizado" size="35" value="<?php echo $valor; ?>" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" disabled />
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita" style="background-color:#FFFF00">Para:</td>
                <td class="subtitulopequeno" style="background-color:#FFFF00">
                    <input type="text" name="para" id="para" size="35" value="<?php echo $email; ?>" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" />
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Assunto:</td>
                <td class="subtitulopequeno">
                    <input type="text" name="assunto" id="assunto" value="Segunda via do boleto WEB CONTROL EMPRESAS" size="50" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" />
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Mensagem:</td>
                <td class="subtitulopequeno">
                    <div align="justify">
                        Prezado Associado WEB CONTROL EMPRESAS,

                        <p align="justify">Segue o boleto em anexo para pagamento em QUALQUER BANCO, CASAS LOTERICAS, CORREIOS, TERMINAIS ELETRONICOS, e INTERNET. Após o pagamento sua baixa será efetuada em 24 horas, e seu acesso ao seu SITE e as PESQUISAS e SOLUÇÕES será AUTOMATICAMENTE LIBERADO. <p>

                        <p>Dpto. Financeiro WEB CONTROL EMPRESAS
                    </div>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">&nbsp;</td>
                <td class="campoesquerda"><input type="hidden" name="go" value="enviaemail" /></td>
            </tr>
            <tr>
                <td colspan="2" class="titulo">&nbsp;</td>
            </tr>
            <tr align="right"  id="btn-hideemail" >
                <td colspan="2"><input type="button" value=" Enviar Email" name="enviar" onclick="enviarEmailPDF()"/></td>
            </tr>
        </table>
    </form>

    <script>
        if (jQuery) {
            $ = jQuery;
        }

        function opemCalendario() {
            $.post('Franquias/b_boletopdf_calendar.php', function (refer) {
                $('#calendario-box').html(refer);
                $('#calendario-box').toggle();
            });
        }
        function closeCalendar() {
            $('#calendario-box').hide();
        }

        function actionCalendar(ref) {
            $.post('Franquias/b_boletopdf_calendar.php?' + ref, function (refer) {
                $('#calendario-box').html(refer);
                $('#calendario-box').show();
            });
        }

        function setValCalendar(valorExtrangeiro, valor) {
            $('#btn-hideemail').show();
            $('#vencimento_atualizado').val(valor);
            $('#vencimento_atualizado1').val(valor);
            $('#calendario-box').hide();
            $.ajax({
                url: "Franquias/b_boleto_calcula.php",
                type: "POST",
                data: {
                    'vencimento_original': $('#vencimento_original').val(),
                    'valor_original': $('#valor_original').val(),
                    'data_pagamento': $('#vencimento_atualizado').val()
                },
                success: function (data) {
                    console.log(data);
                    $('#valor_atualizado').val(data);
                    $('#venc_atualizado_confirmado').val($('#vencimento_atualizado').val());
                }
            });

        }

    </script>

    <?php
} //fim go=email

if ($go == 'email_antecipa') {

    $numdoc = $_GET['numdoc'];
    $email = $_GET['email'];
    $vencimento = $_GET['vencimento'];
    ?>
    <form name="enviaEmail" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="numdoc" value="<?= $numdoc ?>">
        <input type="hidden" name="tipo" value="ANTECIPA">
        <table width="70%" align="center">
            <tr>
                <td colspan="2" align="center" class="titulo">EMISS&Atilde;O DE SEGUNDA VIA DE BOLETOS DE ANTECIPAC&Ccedil;&Atilde;O</td>
            </tr>
            <tr>
                <td width="25%" class="subtitulodireita">&nbsp;</td>
                <td width="75%" class="campoesquerda">&nbsp;</td>
            </tr>
            <tr>
                <td class="subtitulodireita">De:</td>
                <td class="subtitulopequeno"><input type="text" name="de" id="de" size="35" value="financeiro@webcontrolempresas.com.br" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Para:</td>
                <td class="subtitulopequeno"><input type="text" name="para" id="para" size="35" value="<?php echo $email; ?>" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Assunto:</td>
                <td class="subtitulopequeno"><input type="text" name="assunto" id="assunto" value="Segunda via do boleto WEB CONTROL EMPRESAS" size="35" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Mensagem:</td>
                <td class="subtitulopequeno">
                    <div align="justify">
                        Prezado Associado WEB CONTROL EMPRESAS,
                        <p align="justify">Segue o BOLETO referente a PARCELA DE ANTECIPA&Ccedil;&Atilde;O DE CR&Eacute;DITO EM ATRASO que poder&aacute; ser efetuado o pagamento em QUALQUER BANCO, CASAS LOTERICAS, CORREIOS, TERMINAIS ELETRONICOS, e INTERNET.<p>
                        <p align="center" style="color:#F00">Clique e imprima o boleto - Vencimento : <a href="https://www.webcontrolempresas.com.br/inform/boleto_antecipa/boleto.php?numdoc=<?= $numdoc ?>&link_externo=sim"><?= $vencimento ?></a></p>

                        <p>Obs: Ap&oacute;s o pagamento das parcelas de Antecipa&ccedil;&atilde;o, o valor ser&aacute; disponibilizado para futuras antecipa&ccedil;&otilde;es de boletos emitidos aos seus consumidores.</p>

                        <p>Obrigado.</p>
                        <p>Dpto. Financeiro WEB CONTROL EMPRESAS</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">&nbsp;</td>
                <td class="campoesquerda"><input type="hidden" name="go" value="enviaemail" /></td>
            </tr>
            <tr>
                <td colspan="2" class="titulo">&nbsp;</td>
            </tr>
            <tr align="right">
                <td colspan="2"><input id="btn-hideemail" type="button" value=" Enviar Email" name="enviar" onclick="enviarEmailPDF()"/></td>
            </tr>
        </table>
    </form>
    <?php
} elseif ($go == 'email_multa') {

    $numdoc = $_GET['numdoc'];
    $email = $_GET['email'];
    $vencimento = $_GET['vencimento'];
    ?>
    <form name="enviaEmail" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="numdoc" value="<?= $numdoc ?>">
        <input type="hidden" name="tipo" value="MULTA">
        <table width="70%" align="center">
            <tr>
                <td colspan="2" align="center" class="titulo">EMISS&Atilde;O DE SEGUNDA VIA DE BOLETOS DE MULTA CONTRATUAL</td>
            </tr>
            <tr>
                <td width="25%" class="subtitulodireita">&nbsp;</td>
                <td width="75%" class="campoesquerda">&nbsp;</td>
            </tr>
            <tr>
                <td class="subtitulodireita">De:</td>
                <td class="subtitulopequeno"><input type="text" name="de" id="de" size="35" value="financeiro@webcontrolempresas.com.br" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Para:</td>
                <td class="subtitulopequeno"><input type="text" name="para" id="para" size="35" value="<?php echo $email; ?>" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Assunto:</td>
                <td class="subtitulopequeno"><input type="text" name="assunto" id="assunto" value="Segunda via do boleto WEB CONTROL EMPRESAS" size="35" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Mensagem:</td>
                <td class="subtitulopequeno">
                    <div align="justify">
                        Prezado Associado WEB CONTROL EMPRESAS,

                        <p align="justify">Segue o BOLETO referente a MULTA CONTRATUAL conforme cl&aacute;usula 13&deg; e 14&deg; do CONTRATO DE PRESTA&Ccedil;&Atilde;O DE SERVI&Ccedil;OS que poder&aacute; ser efetuado o pagamento QUALQUER BANCO, CASAS LOTERICAS, CORREIOS, TERMINAIS ELETRONICOS, e INTERNET.<p>
                        <p align="center" style="color:#F00">Clique e imprima o boleto - Vencimento : <a href="https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=<?= $numdoc ?>&link_externo=sim"><?= $vencimento ?></a></p>

                        <p>Obs: A ULTIMA FATURA a ser efetuada ser&aacute; com o VENCIMENTO subsequente ao m&ecirc;s do pagamento da Multa Contratual.<br>
                            Exemplo: Pagamento da multa 10/05/2014, o &uacute;ltimo boleto a ser pago ser&aacute; 30/06/2014 (referente aos servi&ccedil;os do m&ecirc;s 05/2014).</p>
                        <p>Antes de efetuar o pagamento do BOLETO DE MULTA CONTRATUAL, pense nas solu&ccedil;&otilde;es que sua empresa deixar&aacute; de usufruir por um valor MUITO BAIXO:</p>

                        <p><font color='blue'><b>1&deg; SOLU&Ccedil;&Atilde;O:</b></font> DENTRO DO SEU PROPRIO SITE sua empresa poderá cadastrar todos os seus clientes, seu estoque, suas despesas e contas pagas, relatórios de clientes e muito mais que você controla na sua empresa.<br>

                        <p><font color='blue'><b>2&deg; SOLU&Ccedil;&Atilde;O: </b></font>DENTRO DO SEU PROPRIO SITE sua empresa poderá localizar qualquer cliente ou pessoa no Brasil, mesmo que seja somente pelo nome.<br>

                        <p><font color='blue'><b>3&deg; SOLU&Ccedil;&Atilde;O: </b></font>DENTRO DO SEU PROPRIO SITE sua empresa poderá vender no crediário ou no boleto para qualquer cliente, e deixar de perder venda, pois muitos clientes não tem dinheiro, e nem cartão, e sua empresa poderá emitir os boletos em 1 minuto.<br>

                        <p><font color='blue'><b>4&deg; SOLU&Ccedil;&Atilde;O: </b></font>DENTRO DO SEU PROPRIO SITE sua empresa poderá parcelar a divida de todos os seus devedores, mandar cartinha de cobrança, parcelar as dividas e facilitar para o devedor, e recuperar de forma inteligente o que o Sr já perdeu ou está perdendo.<br>

                        <p><font color='blue'><b>5&deg; SOLU&Ccedil;&Atilde;O: </b></font>DENTRO DO SEU PROPRIO SITE sua empresa poderá consultar qualquer pessoa, se ela tem problemas com cheque, ou com protesto no cartório, ou com nome com RESTRIÇÃO NA PRAÇA.<br>

                        <p><font color='blue'><b>6&deg; SOLU&Ccedil;&Atilde;O: </b></font>DENTRO DO SEU PROPRIO SITE sua empresa poderá ter  e-mails, controle contábil (folha ponto, advertência de funcionários, promissórias, recibos, holerite, contratos diversos), também poderá bloquear seus devedores e também encaminhar todos para protesto em qualquer cartorio caso o cliente ficar te devendo.<br>

                        <p><font color='blue'><b>7&deg; SOLU&Ccedil;&Atilde;O:</b></font> DENTRO DO SEU PROPRIO SITE sua empresa poderá alterar fotos e textos em seu site em apenas 1 minuto, isso quer dizer que o Sr (a) pode trocar as fotos, pode mudar os textos, pode mudar as promoções a qualquer momento em seu Site, sem depender de programador nenhum !!! <br>

                        <p>Obrigado.


                        <p>Dpto. Financeiro WEB CONTROL EMPRESAS
                    </div>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">&nbsp;</td>
                <td class="campoesquerda"><input type="hidden" name="go" value="enviaemail" /></td>
            </tr>
            <tr>
                <td colspan="2" class="titulo">&nbsp;</td>
            </tr>
            <tr align="right">
                <td colspan="2"><input type="button" value=" Enviar Email" name="enviar" onclick="enviarEmailPDF()"/></td>
            </tr>
        </table>
    </form>
    <?php
}

if ($go == "enviaemail") {

    include("class.send.php");

    $tipo = $_REQUEST['tipo'];

    if ($tipo == "ANTECIPA")
        $link = 'boleto_antecipa';
    else
        $link = 'boleto';


    //mensagem padr�o
    $numdoc = $_REQUEST['numdoc'];
    $vencimento = $_REQUEST['vencimento'];

    if ($tipo == 'ANTECIPA') {
        $txt_msg_padrao_1 = "
Prezado Associado WEB CONTROL EMPRESAS,<br>
<br>
<br>
Segue o BOLETO referente a PARCELA DE ANTECIPA&Ccedil;&Atilde;O DE CR&Eacute;DITO EM ATRASO que poder&aacute; ser efetuado o pagamento em QUALQUER BANCO, CASAS LOTERICAS, CORREIOS, TERMINAIS ELETRONICOS, e INTERNET.<p>
<p align='center'><a href='https://www.webcontrolempresas.com.br/inform/boleto_antecipa/boleto.php?numdoc=$numdoc&link_externo=sim'>Clique e imprima o boleto - Vencimento : $vencimento</a></p>

    <p>Obs: Ap&oacute;s o pagamento das parcelas de Antecipa&ccedil;&atilde;o, o valor ser&aacute; disponibilizado para futuras antecipa&ccedil;&otilde;es de boletos emitidos aos seus consumidores.</p>


<br>
Obrigado. <br>
<br><br>
Dpto. Financeiro WEB CONTROL EMPRESAS";
    } elseif ($tipo == 'MULTA') {
        $txt_msg_padrao_1 = "

		Prezado Associado WEB CONTROL EMPRESAS,

		<p align='justify'>Segue o BOLETO referente a MULTA CONTRATUAL conforme cl&aacute;usula 13&deg; e 14&deg; do CONTRATO DE PRESTA&Ccedil;&Atilde;O DE SERVI&Ccedil;OS que poder&aacute; ser efetuado o pagamento QUALQUER BANCO, CASAS LOTERICAS, CORREIOS, TERMINAIS ELETRONICOS, e INTERNET.<p>
    <p align='center'><a href='https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc&link_externo=sim'>Clique e imprima o boleto - Vencimento : $vencimento</a></p>

    <p>Obs: A &Uacute;LTIMA FATURA a ser efetuada ser&aacute; com o VENCIMENTO subsequente ao m&ecirc;s do pagamento da Multa Contratual.<br>
    Exemplo: Pagamento da multa 10/05/2014, o &uacute;ltimo boleto a ser pago ser&aacute; 30/06/2014 (referente aos servi&ccedil;os do m&ecirc;s 05/2014).</p>
    <p>Antes de efetuar o pagamento do BOLETO DE MULTA CONTRATUAL, pense nas solu&ccedil;&otilde;es que sua empresa deixar&aacute; de usufruir por um valor MUITO BAIXO:</p>

    <p><font color='blue'><b>1&deg; SOLU&Ccedil;&Atilde;O:</b></font> DENTRO DO SEU PR&Oacute;PRIO SITE sua empresa poder&aacute; cadastrar todos os seus clientes, seu estoque, suas despesas e contas pagas, relat&oacute;rios de clientes e muito mais que voc&ecirc; controla na sua empresa.<br>

    <p><font color='blue'><b>2&deg; SOLU&Ccedil;&Atilde;O: </b></font>DENTRO DO SEU PR&Oacute;PRIO SITE sua empresa poder&aacute; localizar qualquer cliente ou pessoa no Brasil, mesmo que seja somente pelo nome.<br>

    <p><font color='blue'><b>3&deg; SOLU&Ccedil;&Atilde;O: </b></font>DENTRO DO SEU PR&Oacute;PRIO SITE sua empresa poder&aacute; vender no credi&aacute;rio ou no boleto para qualquer cliente, e deixar de perder venda, pois muitos clientes n&atilde;o tem dinheiro, e nem cart&atilde;o, e sua empresa poder&aacute; emitir os boletos em 1 minuto.<br>

    <p><font color='blue'><b>4&deg; SOLU&Ccedil;&Atilde;O: </b></font>DENTRO DO SEU PR&Oacute;PRIO SITE sua empresa poder&aacute; parcelar a d&iacute;vida de todos os seus devedores, mandar cartinha de cobran&ccedil;a, parcelar as d&iacute;vidas e facilitar para o devedor, e recuperar de forma inteligente o que o Sr j&aacute; perdeu ou est&aacute; perdendo.<br>

    <p><font color='blue'><b>5&deg; SOLU&Ccedil;&Atilde;O: </b></font>DENTRO DO SEU PR&Oacute;PRIO SITE sua empresa poder&aacute; consultar qualquer pessoa, se ela tem problemas com cheque, ou com protesto no cart&oacute;rio, ou com nome com RESTRI&Ccedil;&Atilde;O NA PRA&Ccedil;A.<br>

    <p><font color='blue'><b>6&deg; SOLU&Ccedil;&Atilde;O: </b></font>DENTRO DO SEU PR&Oacute;PRIO SITE sua empresa poder&aacute; ter  e-mails, controle cont&aacute;bil (folha ponto, advert&ecirc;ncia de funcion&aacute;rios, promiss&oacute;rias, recibos, holerite, contratos diversos), tamb&eacute;m poder&aacute; bloquear seus devedores e tamb&eacute;m encaminhar todos para protesto em qualquer cart&oacute;rio caso o cliente ficar te devendo.<br>

    <p><font color='blue'><b>7&deg; SOLU&Ccedil;&Atilde;O:</b></font> DENTRO DO SEU PR&Oacute;PRIO SITE sua empresa poder&aacute; alterar fotos e textos em seu site em apenas 1 minuto, isso quer dizer que o Sr (a) pode trocar as fotos, pode mudar os textos, pode mudar as promo&ccedil;&otilde;es a qualquer momento em seu Site, sem depender de programador nenhum !!! <br>

    <p>Obrigado.


    <p>Dpto. Financeiro WEB CONTROL EMPRESAS
";
    } else {
        $txt_msg_padrao_1 = "Prezado Associado WEB CONTROL EMPRESAS,<br>
	<br>Segue o boleto em anexo para pagamento em QUALQUER BANCO, CASAS LOT&Eacute;RICAS, CORREIOS, <br>
	TERMINAIS ELETR&Ocirc;NICOS, e INTERNET. Ap&oacute;s o pagamento sua baixa ser&aacute; efetuada em 24 horas, <br>
	e seu acesso ao seu SITE e as PESQUISAS e SOLU&Ccedil;&Otilde;ES ser&aacute; AUTOM&Aacute;TICAMENTE LIBERADO. <br>
    <br>
	<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vencimento : <a href='https://www.webcontrolempresas.com.br/inform/$link/boleto.php?numdoc=$numdoc&link_externo=sim'>Acessar Boleto $vencimento</a>
    <br>
    <br><br>
    Dpto. Financeiro WEB CONTROL EMPRESAS";
    }
    $assunto = $_POST['assunto'];

    $txt_tmp = "$txt_msg_padrao_1";
    $txt_tmp .= "                                  ";
    $txt_tmp .= "                                  ";
    $txt_tmp .= "                                  ";
    $txt_tmp .= "                                  ";

    $configuracao = "$txt_tmp";

    $to = $_POST['para'];
    $from = $_POST['de'];

    $assunto = utf8_encode(html_entity_decode($assunto));
    # Enviando Email
    $contato = new SendEmail;
    $contato->nomeEmail = "Departamento Financeiro"; // Nome do Responsavel que vai receber o E-Mail
    $contato->paraEmail = $to; // Email que vai receber a mensagem

    $contato->configHost = "10.2.2.7"; // Endereço do seu SMTP
    $contato->configPort = 25; // Porta usada pelo seu servidor. Padrão 25
    $contato->configUsuario = "financeiro@webcontrolempresas.com.br"; // Login do email que ira utilizar
    $contato->configSenha = "infsys321"; // Senha do email
    $contato->remetenteEmail = "financeiro@webcontrolempresas.com.br"; // E-mail que vai ser exibido no remetente da mensagem
    $contato->remetenteNome = "Web Control Empresas";    // Um nome para o remetente
    $contato->assuntoEmail = $assunto; // Assunto da mensagem
    $contato->conteudoEmail = $configuracao; // Conteudo da mensagem.
    $contato->confirmacao = 1; // Se for 1 exibi a mensagem de confirmação
    $contato->mensagem = ""; // Mensagem de Confirmacao
    $contato->erroMsg = "[ $codloja ] Uma mensagem de erro aqui"; // pode colocar uma mensagem de erro aqui!!
    $contato->confirmacaoErro = 1; // Se voce colocar 1 ele exibi o erro que ocorreu no erro se for 0 não exibi o erro uso geralmente para verifiar se ta pegando.
    $contato->enviar(); // envia a mensagem

    echo "<script>alert(\"E-mail enviado com sucesso!\");</script>";
    echo "<script language= 'JavaScript'>
location.href='painel.php?pagina1=Franquias/b_boleto.php'
</script>";

}//fim go=enviaemail
?>