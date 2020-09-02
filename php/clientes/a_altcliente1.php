<script type="text/javascript">
function alterarFranquia(){

    var id_franquia = $('select[name="franqueado"]').val();
    $.ajax({
            url: '../php/clientes/BuscaConsultorAgendador.php',
            data: {
                    id_franquia: id_franquia,
                    action: 'buscarConsultorAgendador'
            },
            type: 'POST',
            dataType: 'text',
            success: function(data){

                var arrResult = data.split(';');
                $('select[name="id_consultor"]').html('');
                $('select[name="id_agendador"]').html('');
                $('select[name="id_consultor"]').append(arrResult[0]);
                $('select[name="id_agendador"]').append(arrResult[1]);

            }
    });

}

//para filtrar banco, agencia e conta
// Formata o campo Agencia
function formataAgenciaConta(campo) {
    campo.value = filtraCampo(campo);
    vr = campo.value;
    tam = vr.length;
    if (tam <= 1)
        campo.value = vr;
    if (tam > 1)
        campo.value = vr.substr(0, tam - 1) + '-' + vr.substr(tam - 1, tam);
}
// Formata o campo valor



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
// Formata o campo valor
function formataNumerico(campo) {
    campo.value = filtraCampo(campo);
    vr = campo.value;
    tam = vr.length;
}
// limpa todos os caracteres especiais do campo solicitado
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
// Seta o ajuda do campo no campo
function setaTextoAjuda(txt) {
    if (document.getElementById('textoAjuda'))
        document.getElementById('textoAjuda').innerHTML = txt + ' ';
}
function getTeclaPressionada(evt) {
    if (typeof (evt) == 'undefined')
        evt = window.event;
    return(evt.keyCode ? evt.keyCode : (evt.which ? evt.which : evt.charCode));
}
// teclas 63230 a 63240 = safari
function isTeclaEspecial(key) {
    return key < 32 || (key >= 35 && key <= 36) || (key >= 37 && key <= 40) || key == 46 || (key >= 63230 && key <= 63240);
}
function isTeclaRelevante(key) {
    return (key == 8) || (key == 46) || (key == 88) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105);
}
function isCaracterRelevante(key) {
    return (key == 88) || (key == 120) || (key >= 48 && key <= 57);
}
function isCopiaCola(ctrlKey, key) {
    return ctrlKey && (key == 118 || key == 86 || key == 99 || key == 67);
}
function filtraTeclas(evt) {
    var key = getTeclaPressionada(evt);
    if (isTeclaEspecial(key) || isTeclaRelevante(key) || isCopiaCola(evt.ctrlKey, key))
        return true;
    StopEvent(evt);
    return false;
}
function filtraCaracteres(evt) {
    var key = getTeclaPressionada(evt);
    if (isTeclaEspecial(key) || isCaracterRelevante(key) || isCopiaCola(evt.ctrlKey, key))
        return true;
    StopEvent(evt);
    return false;
}
function StopEvent(evt) {
    if (document.all)
        evt.returnValue = false;
    else if (evt.preventDefault)
        evt.preventDefault();
}
function formataMascara(format, field) {
    var result = "";
    var maskIdx = format.length - 1;
    var error = false;
    var valor = field.value;
    var posFinal = false;
    if (field.setSelectionRange) {
        if (field.selectionStart == valor.length)
            posFinal = true;
    }
    valor = valor.replace(/[^0123456789Xx]/g, '');
    for (var valIdx = valor.length - 1; valIdx >= 0 && maskIdx >= 0; --maskIdx) {
        var chr = valor.charAt(valIdx);
        var chrMask = format.charAt(maskIdx);
        switch (chrMask) {
            case '#':
                if (!(/\d/.test(chr)))
                    error = true;
                result = chr + result;
                --valIdx;
                break;
            case '@':
                result = chr + result;
                --valIdx;
                break;
            default:
                result = chrMask + result;
        }
    }
    field.value = result;
    field.style.color = error ? 'red' : '';
    if (posFinal) {
        field.selectionStart = result.length;
        field.selectionEnd = result.length;
    }
    return result;
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

function confirma() {
    frm = document.form;
    var parte1 = frm.email.value.indexOf("@");
    //var parte2 = frm.email.value.indexOf(".");
    var parte3 = frm.email.value.length;
    if (!(parte1 >= 3 && parte3 >= 9)) {//parte2 >= 6 && 
        alert("O campo E-mail deve ser conter um endereco eletronico!");
        frm.email.focus();
        return false;
    }
    frm.action = 'clientes/update_altcliente.php';
    frm.submit();
}

function refresch() {
    frm = document.form;
    frm.action = 'painel.php?pagina1=clientes/a_altcliente1.php';
    frm.submit();
}

function validaCel(){
	if ($('#celular').val() != '' && $('#celular').val().length 	< 15 ){
		 alert("O campo celular deve ser preenchido corretamente!! ");
		 var atual = $('#celular').val();
		 $('#celular').val('');
		 $('#celular').focus();
		 return false;
	}
}

function Verifica_TipoCliente() {
    frm = document.form;
    if (frm.tipo_cliente.value == 'A') {
        alert('Vo�� est� mudando o Tipo do Cliente Para Administrador');
    }
}

/* M�scaras ER */
function xmascara(o, f) {
    v_obj = o
    v_fun = f
    setTimeout("xexecmascara()", 1)
}
function xexecmascara() {
    v_obj.value = v_fun(v_obj.value)
}
function mtel(v) {
    v = v.replace(/\D/g, "");             //Remove tudo o que n�o � d�gito
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); //Coloca par�nteses em volta dos dois primeiros d�gitos
    v = v.replace(/(\d)(\d{4})$/, "$1-$2");    //Coloca h�fen entre o quarto e o quinto d�gitos
    return v;
}
function id(el) {
    return document.getElementById(el);
}

window.onload = function () {

    id('celular').onkeypress = function () {
        xmascara(this, mtel);
    }
    id('fone_whatsapp').onkeypress = function () {
        xmascara(this, mtel);
    }
    id('telefone').onkeypress = function () {
        xmascara(this, mtel);
    }
    id('fax').onkeypress = function () {
        xmascara(this, mtel);
    }
    id('fone_res').onkeypress = function () {
        xmascara(this, mtel);
    }
    id('contador_telefone').onkeypress = function () {
        xmascara(this, mtel);
    }
    id('contador_celular').onkeypress = function () {
        xmascara(this, mtel);
    }

//    alterarFranquia();

// $( document ).ready(function() {
// 	$( "#celular" ).focusout(function() {
// 		if ($('#celular').val().length 	< 15 ){
// 			 alert("O campo celular deve ser preenchido corretamente!! ");
// 	         return false;
// 		}
// 	}
// });


}

</script>
<style type="text/css">
       h1 {font-size: 140%;}
       form {margin: 30px 50px 0;}
       form input, select {
       font-family: Arial;
       font-size: 8pt;
       }
       form input#numero, form input#uf, form input#cep {float: left; width: 75px;}
       address {clear: both; padding: 30px 0;}

</style>

<script src="https://www.webcontrolempresas.com.br/site/assets/js/jquery-1.12.js"></script>
<script src="https://webcontrolempresas.com.br/site/assets/js/mask.js"></script>
<script>
    $(document).ready(function () {
        $('#pct_pesquisa').mask("#0.00", {reverse: true});
    });
</script>
<?php
require "connect/sessao.php";
require "connect/funcoes.php";


if ( $id_franquia == 1 || $id_franquia == 4 || $id_franquia == 5 || $id_franquia == 163 || $id_franquia == 247 )
    $id_franquia_agendador = 1;

$codloja = $_REQUEST['codloja'];

$comando = "SELECT 
                a.renegociacao_tabela, a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, 
                a.bairro, a.end, a.cep, a.fone, a.fax, a.email, a.tx_mens, a.id_franquia, 
                date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit, a.ramo_atividade,
                a.obs, a.mensalidade_solucoes, a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, a.hora_cadastro, 
                a.cpfsocio2, a.emissao_financeiro, a.vendedor, mid(b.logon,1,5) as logon, 
                mid(b.logon,7,10) as senha, a.classe, a.banco_cliente, a.agencia_cliente,
                a.conta_cliente, a.cpfcnpj_doc, a.nome_doc, a.tpconta, a.complemento,
                a.inscricao_estadual, a.cnae_fiscal, a.inscricao_municipal, a.vr_max_limite_crediario,
                a.inscricao_estadual_tributario, a.numero, a.tipo_cliente, a.emitir_nfs,
                a.contador_nome, a.contador_telefone, a.contador_celular, a.contador_email1, a.contador_email2,
                a.contadorSN,
                a.agendador,
                a.id_agendador,
                a.id_consultor,
                a.whatsapp,
                a.modulo_loja_vitual,
                a.modulo_pesq_credito,
                a.modulo_receber_deved,
                a.modulo_aumentar_vendas
            FROM cs2.cadastro a
            INNER JOIN cs2.logon b    ON a.codloja = b.codloja
            INNER JOIN cs2.situacao d ON a.sitcli = d.codsit
            WHERE a.codloja='$codloja' LIMIT 1";
$res = mysql_query($comando, $con);
$matriz = mysql_fetch_array($res);
//tratamento para agencia e conta corrente
$agencia_cliente = $matriz['agencia_cliente'];
$agencia_cliente = strtoupper($agencia_cliente);
$celular = mascara_celular($matriz['celular']);
$fone = mascara_celular($matriz['fone']);
$fone_res = mascara_celular($matriz['fone_res']);
$fone_whatsapp = mascara_celular($matriz['whatsapp']);
$fax = mascara_celular($matriz['fax']);

if ($matriz['contador_telefone'])
    $contador_telefone = mascara_celular($matriz['contador_telefone']);

if ($matriz['contador_celular'])
    $contador_celular = mascara_celular($matriz['contador_celular']);

///////////////////////////////////////////////////////////////
$_comando = "SELECT 
                a.renegociacao_tabela, a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, 
                a.bairro, a.end, a.cep, a.fone, a.fax, a.email, a.tx_mens, a.id_franquia, 
                date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit, a.ramo_atividade, 
                a.obs, a.mensalidade_solucoes, a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, 
                a.cpfsocio2, a.emissao_financeiro, a.vendedor, mid(b.logon,1,5) as logon, 
                mid(b.logon,7,10) as senha, a.classe, a.banco_cliente, a.agencia_cliente, 
                a.conta_cliente, a.cpfcnpj_doc, a.nome_doc, a.tpconta,  a.complemento,
                a.inscricao_estadual, a.cnae_fiscal, a.inscricao_municipal, a.vr_max_limite_crediario, 
                a.inscricao_estadual_tributario, a.numero, a.emitir_nfs, a.contadorSN, a.agendador
            FROM cs2.cadastro a
            inner join cs2.logon b on a.codloja=b.codloja
            inner join cs2.situacao d on a.sitcli=d.codsit
            where a.codloja='$codloja' limit 1";
$_res = mysql_query($_comando, $con);
///////////////////////////////////////////////////////////////
//seleciona a franquia junior
$sql_jr = "SELECT id_franquia_jr FROM cs2.cadastro WHERE codloja = '$codloja'";
$rs_jr = mysql_query($sql_jr, $con);

if (empty($rs_jr)) {
    $id_franquia_jr = mysql_result($rs_jr, 0, 'id_franquia_jr');
} else {
    $id_franquia_jr = '';
}


if (strlen($agencia_cliente) > 4) {
    $agencia_cliente = substr($agencia_cliente, 0, 4) . '-' . substr($agencia_cliente, 4, 1);
} else {
    $agencia_cliente = substr($agencia_cliente, 0, 4);
}

$conta_cliente = 100000000000 + $matriz['conta_cliente'];
$conta_cliente = substr($conta_cliente, 1, 10) . '-' . substr($conta_cliente, 11, 1);

$renegociacao_tabela = $matriz['renegociacao_tabela'];
if ($renegociacao_tabela != "") {
    $dia = substr($renegociacao_tabela, 8, 10);
    $mes = substr($renegociacao_tabela, 5, 2);
    $ano = substr($renegociacao_tabela, 0, 4);
    $data_view.=$dia;
    $data_view.="/";
    $data_view.=$mes;
    $data_view.="/";
    $data_view.=$ano;

    if ($data_view == '00/00/0000') {
        $data_view = "";
    }
}
?>

<form method="post" action="#" name="form">
    <input type="hidden" name="codloja" value="<?php echo $codloja;?>">
    <table>
        <tr>
            <td colspan="4" class="titulo" align="center"><br />
                ALTERA&Ccedil;&Atilde;O DOS DADOS DE CLIENTES</td>
        </tr>
        <tr>
            <td width="224" class="subtitulodireita">ID</td>
            <td width="147" class="subtitulopequeno">
                <?php echo $matriz['codloja']; ?>
                <input name="codloja2" type="hidden" id="codloja" value="<?php echo $matriz['codloja']; ?>" />
            </td>
            <?php
            if ($tipo == "a") {
                echo "<td width='93' class='subtitulopequeno'>Tipo Cliente:</td>";
                echo "<td width='200' class='subtitulopequeno'>
                <select name='tipo_cliente' style='width:70%' onchange='Verifica_TipoCliente()'>
                <option value='A' " ;
                if ( $matriz['tipo_cliente'] == 'A' ) echo "selected";
                echo ">Administrador</option> 
                <option value='N' "; if ( $matriz['tipo_cliente'] == 'N' ) echo "selected";
                echo ">Normal</option>
                </select>
                </td>";
            }else{
                echo "<td width='93' class='subtitulopequeno'>&nbsp;</td>
                <td width='200' class='subtitulopequeno'>&nbsp;</td>";
            }
            ?>
        </tr>
        <tr>
            <td class="subtitulodireita">C&oacute;digo de Cliente </td>
            <td colspan="3" class="subtitulopequeno">
                <input name="codigo" type="hidden" value="<?=$matriz['logon']?>"  />
                <?=$matriz['logon']?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Raz&atilde;o Social</td>
            <td colspan="3" class="subtitulopequeno">
                <?php
                if ($tipo == "a" or $id_franquia == 4) {
                    echo "<input name=\"razao\" type=\"text\" id=\"razao\" value=\"".$matriz['razaosoc']."\" size=\"65\" maxlength=\"200\" onFocus=\"this.className='boxover'\" onBlur=\"maiusculo(this); this.className='boxnormal'\" />";
                } else {
                    echo "<input name=\"razao\" type=\"hidden\" value=\"".$matriz['razaosoc']."\"  />";
                    echo $matriz['razaosoc'];
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Nome Fantasia</td>
            <td colspan="3" class="subtitulopequeno">
                <?php
                if ($tipo == "a" or $id_franquia == 4) {
                    echo "<input name=\"nomef\" type=\"text\" id=\"razao\" value=\"".$matriz['nomefantasia']."\" size=\"65\" maxlength=\"200\" onFocus=\"this.className='boxover'\" onBlur=\"maiusculo(this); this.className='boxnormal'\" />";
                } else {
                    echo "<input name=\"nomef\" type=\"hidden\" value=\"".$matriz['nomefantasia']."\"  />";
                    echo $matriz['nomefantasia'];
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">CNPJ</td>
            <td colspan="3" class="subtitulopequeno">
                <?php
                if ($tipo == "a") {
                    echo "<input name=\"cnpj\" type=\"text\" id=\"razao\" onKeyPress=\"soNumero(); formatar('##.###.###/####-##', this)\" value=\"".$matriz['insc']."\" size=\"22\" maxlength=\"18\" onFocus=\"this.className='boxover'\" onBlur=\"this.className='boxnormal'\" />";
                } else {
                    echo "<input name=\"cnpj\" type=\"hidden\" value=\"".$matriz['insc']."\"  />";
                    echo $matriz['insc'];
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Inscri&ccedil;&atilde;o Estadual</td>
            <td colspan="3" class="subtitulopequeno">
                <input type="hidden" id="hr_cad" name="hr_cad" value="<?php echo $matriz['hora_cadastro'] ?>">
                <input name="inscricao_estadual" type="text" id="inscricao_estadual" value="<?php echo $matriz['inscricao_estadual']; ?>" size="22" maxlength="14" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />digite apenas n&uacute;meros</td>
        </tr>
        <tr>
            <td class="subtitulodireita">CNAE Fiscal</td>
            <td colspan="3" class="subtitulopequeno"><input name="cnae_fiscal" type="text" id="cnae_fiscal" value="<?php echo $matriz['cnae_fiscal']; ?>" size="22" maxlength="7" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />digite apenas n&uacute;meros</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Inscri&ccedil;&atilde;o Municipal</td>
            <td colspan="3" class="subtitulopequeno"><input name="inscricao_municipal" type="text" id="inscricao_municipal" value="<?php echo $matriz['inscricao_municipal']; ?>" size="22" maxlength="14" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />digite apenas n&uacute;meros</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Inscri&ccedil;&atilde;o Estadual (Subst. Tribut&aacute;ria)</td>
            <td colspan="3" class="subtitulopequeno"><input name="inscricao_estadual_tributario" type="text" id="inscricao_estadual_tributario" value="<?php echo $matriz['inscricao_estadual_tributario']; ?>" size="22" maxlength="14" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />digite apenas n&uacute;meros</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Endere&ccedil;o</td>
            <td colspan="3" class="subtitulopequeno"><input name="endereco" type="text" id="endereco" value="<?php echo $matriz['end']; ?>" size="65" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />(*)</td>
        </tr>
        <tr>
            <td class="subtitulodireita">N&uacute;mero</td>
            <td class="subtitulopequeno" colspan="4"><input name="numero_endereco" type="text" id="numero_endereco" value="<?php echo $matriz['numero']; ?>" size="40" maxlength="10" onFocus="this.className='boxover'" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Complemento</td>
            <td class="subtitulopequeno" colspan="4"><input name="complemento" id="complemento" type="text" value="<?= $matriz['complemento'] ?>" size="40" onFocus="this.className='boxover'" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Bairro</td>
            <td colspan="3" class="subtitulopequeno"><input name="bairro" type="text" id="bairro" value="<?php echo $matriz['bairro']; ?>" size="40" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">UF</td>
            <td colspan="3" class="subtitulopequeno">
                <select name="uf">
                    <option value="PR" <?php if ($matriz['uf'] == "PR") { echo "selected"; } ?> >PR</option>
                    <option value="SC" <?php if ($matriz['uf'] == "SC") { echo "selected"; } ?> >SC</option>
                    <option value="SP" <?php if ($matriz['uf'] == "SP") { echo "selected"; } ?> >SP</option>
                    <option value="RS" <?php if ($matriz['uf'] == "RS") { echo "selected"; } ?> >RS</option>
                    <option value="GO" <?php if ($matriz['uf'] == "GO") { echo "selected"; } ?> >GO</option>
                    <option value="MG" <?php if ($matriz['uf'] == "MG") { echo "selected"; } ?> >MG</option>
                    <option value="RJ" <?php if ($matriz['uf'] == "RJ") { echo "selected"; } ?> >RJ</option>
                    <option value="BA" <?php if ($matriz['uf'] == "BA") { echo "selected"; } ?> >BA</option>
                    <option value="MT" <?php if ($matriz['uf'] == "MT") { echo "selected"; } ?> >MT</option>
                    <option value="CE" <?php if ($matriz['uf'] == "CE") { echo "selected"; } ?> >CE</option>
                    <option value="AC" <?php if ($matriz['uf'] == "AC") { echo "selected"; } ?> >AC</option>
                    <option value="AL" <?php if ($matriz['uf'] == "AL") { echo "selected"; } ?> >AL</option>
                    <option value="AM" <?php if ($matriz['uf'] == "AM") { echo "selected"; } ?> >AM</option>
                    <option value="AP" <?php if ($matriz['uf'] == "AP") { echo "selected"; } ?> >AP</option>
                    <option value="DF" <?php if ($matriz['uf'] == "DF") { echo "selected"; } ?> >DF</option>
                    <option value="ES" <?php if ($matriz['uf'] == "ES") { echo "selected"; } ?> >ES</option>
                    <option value="MA" <?php if ($matriz['uf'] == "MA") { echo "selected"; } ?> >MA</option>
                    <option value="MS" <?php if ($matriz['uf'] == "MS") { echo "selected"; } ?> >MS</option>
                    <option value="PA" <?php if ($matriz['uf'] == "PA") { echo "selected"; } ?> >PA</option>
                    <option value="PB" <?php if ($matriz['uf'] == "PB") { echo "selected"; } ?> >PB</option>
                    <option value="PE" <?php if ($matriz['uf'] == "PE") { echo "selected"; } ?> >PE</option>
                    <option value="PI" <?php if ($matriz['uf'] == "PI") { echo "selected"; } ?> >PI</option>
                    <option value="RN" <?php if ($matriz['uf'] == "RN") { echo "selected"; } ?> >RN</option>
                    <option value="RO" <?php if ($matriz['uf'] == "RO") { echo "selected"; } ?> >RO</option>
                    <option value="RR" <?php if ($matriz['uf'] == "RR") { echo "selected"; } ?> >RR</option>
                    <option value="SE" <?php if ($matriz['uf'] == "SE") { echo "selected"; } ?> >SE</option>
                    <option value="TO" <?php if ($matriz['uf'] == "TO") { echo "selected"; } ?> >TO</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Cidade</td>
            <td colspan="3" class="subtitulopequeno"><input name="cidade" type="text" id="cidade" value="<?php echo $matriz['cidade']; ?>" size="30" maxlength="30" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">CEP</td>
            <td colspan="3" class="subtitulopequeno"><input name="cep" type="text" id="cep" onKeyPress="soNumero(); formatar('##.###-###', this)" value="<?php echo $matriz['cep']; ?>" size="12" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Telefone</td>
            <td colspan="3" class="subtitulopequeno"><input name="telefone" type="text" id="telefone" onKeyPress="soNumero()" value="<?php echo $fone; ?>" size="25" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Fax</td>
            <td colspan="3" class="subtitulopequeno"><input name="fax" type="text" id="fax" onKeyPress="soNumero()" value="<?php echo $fax; ?>" size="25" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Celular</td>
            <td colspan="3" class="subtitulopequeno"><input name="celular"  onBlur="validaCel()" type="text" id="celular" value="<?php echo $celular; ?>" size="25" maxlength="15" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Telefone Residencial</td>
            <td colspan="3" class="subtitulopequeno"><input name="fone_res" type="text" id="fone_res" onKeyPress="soNumero();" value="<?php echo $fone_res; ?>" size="25" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Telefone Whatsapp</td>
            <td colspan="3" class="subtitulopequeno"><input name="fone_whatsapp"  onBlur="validaCel()" type="text" id="fone_whatsapp" value="<?php echo $fone_whatsapp; ?>" size="25" maxlength="15" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">E-mail</td>
            <td colspan="3" class="subtitulopequeno"><input name="email" class="h2" type="text" id="email" value="<?php echo strtolower($matriz['email']); ?>" size="25" maxlength="200" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Propriet&aacute;rio 1 </td>
            <td colspan="3" class="subtitulopequeno">
                <table border="0" class="subtitulopequeno">
                    <tr>
                        <td class="campoesquerda">Nome</td>
                        <td><input name="nome_prop1" type="text" id="nome_prop1" value="<?php echo $matriz['socio1']; ?>" size="60" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
                    </tr>
                    <tr>
                        <td class="campoesquerda">CPF 1</td>
                        <td><input name="cpf1" type="text" id="cpf1" OnKeyPress="soNumero(); formatar('###.###.###-##', this)" value="<?php echo $matriz['cpfsocio1']; ?>" size="17" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Propriet&aacute;rio 2</td>
            <td colspan="3" class="subtitulopequeno">
                <table border="0" class="subtitulopequeno">
                    <tr>
                        <td class="campoesquerda">Nome</td>
                        <td><input name="nome_prop2" type="text" id="nome_prop2" value="<?php echo $matriz['socio2']; ?>" size="60" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
                    </tr>
                    <tr>
                        <td class="campoesquerda">CPF 2</td>
                        <td><input name="cpf2" type="text" id="cpf2" OnKeyPress="soNumero(); formatar('###.###.###-##', this)" value="<?php echo $matriz['cpfsocio2']; ?>" size="17" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- DADOS DO CONTADOR -->
        <tr>
            <td class="subtitulodireita">Nome Contador</td>
            <td colspan="3" class="subtitulopequeno">
                <input name="contador_nome" type="text" id="contador_nome" size="50" maxlength="100" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $matriz['contador_nome']; ?>" />
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Telefone Contador</td>
            <td colspan="3" class="subtitulopequeno">
                <input name="contador_telefone" type="text" id="contador_telefone" size="25" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $contador_telefone; ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Celular Contador</td>
            <td colspan="3" class="subtitulopequeno">
                <input name="contador_celular" type="text" id="contador_celular" size="25" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $contador_celular; ?>" />
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Email 1 Contador</td>
            <td colspan="3" class="subtitulopequeno">
                <input name="contador_email1" type="text" id="contador_email1" size="50" maxlength="100" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $matriz['contador_email1']; ?>" />
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Email 2 Contador</td>
            <td colspan="3" class="subtitulopequeno">
                <input name="contador_email2" type="text" id="contador_email2" size="50" maxlength="100" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $matriz['contador_email2']; ?>" />
            </td>
        </tr>

        <!-- FIM DADOS DO CONTADOR -->

        <tr>
            <td class="subtitulodireita">Segmento Empresarial</td>
            <td colspan="3" class="subtitulopequeno"><input name="ramo" type="text" id="ramo" size="25" maxlength="25" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $matriz['ramo_atividade']; ?>" /></td>
        </tr>
        <?php
        if ($tipo == "a") { ?>
            <tr>
                <td class="subtitulodireita">Data de afilia&ccedil;&atilde;o</td>
                <td colspan="" class="subtitulopequeno">
                    <input name="dt_cad" type="text" id="dt_cad" maxlength="10" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $matriz['data']; ?>" />
                </td>
                <td class="subtitulodireita">Hor&aacute;rio</td>
                <td colspan="" class="subtitulopequeno">
                    <input name="hr_cad" type="text" id="hr_cad" maxlength="5" onKeyPress="return MM_formtCep(event,this,'##:##');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $matriz['hora_cadastro']; ?>" />
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td class="subtitulodireita">Renegocia&ccedil;&atilde;o de Tabela</td>
            <td colspan="3" class="subtitulopequeno">
                <?php
                if ($_SESSION['ss_tipo'] == 'b') { ?>
                    <input type="text" name="renegociacao_tabela" id="renegociacao_tabela" value="<?= $data_view ?>" onChange="" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10" disabled="disabled" />
                    <input type="hidden" name="renegociacao_tabela" id="renegociacao_tabela" value="<?= $data_view ?>" maxlength="10" />
                <?php } else { ?>
                    <input type="text" name="renegociacao_tabela" id="renegociacao_tabela" value="<?= $data_view ?>" onChange="" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10" />
                <?php } ?>
            </td>
        </tr>
        <?php
        if ($tipo == "a") { ?>
            <tr>
                <td class="subtitulodireita">Pacote Pesquisas</td>
                <td colspan="2" class="subtitulopequeno"><input name="pct_pesquisa" type="text" id="pct_pesquisa" value="<?php echo $matriz['tx_mens']; ?>" size="25" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
                <td class="subtitulopequeno">
                    <input name="contadorSN" type="checkbox" id="contadorSN"
                        <?php
                        if ( $matriz['contadorSN'] == 'S' ) echo "checked='checked'";
                        ?>
                    />Contador Mensalidade ZERO</td>
            </tr>
            <tr>
                <td class="subtitulodireita">Licen&ccedil;as - Softwares de Solu&ccedil;&otilde;es</td>
                <td colspan="3" class="subtitulopequeno"><input name="pct_solucoes" type="text" id="pct_solucoes" value="<?php echo $matriz['mensalidade_solucoes']; ?>" size="25" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita"></td>
                <td colspan="3" class="subtitulopequeno">&nbsp;</td>
            </tr>
            <tr>
                <td class="subtitulodireita">Módulo Loja Virtual E-commerce</td>
                <td colspan="3" class="subtitulopequeno">
                    <select name="modulo_loja_vitual" style="width:40%">
                        <?php
                        $sel_mod_1 = '';
                        $sel_mod_2 = '';
                        $sel_mod_3 = '';
                        if ( $matriz['modulo_loja_vitual'] == NULL ) $sel_mod_1 = 'selected="selected"';
                        elseif ( $matriz['modulo_loja_vitual'] == '0.00') $sel_mod_2 = 'selected="selected"';
                        else $sel_mod_3 = 'selected="selected"';
                        ?>
                        <option value = '9' <?php echo $sel_mod_1; ?>>nenhum</option>
                        <option value = '0' <?php echo $sel_mod_2; ?> >0,00</option>
                        <option value = '1' <?php echo $sel_mod_3; ?>>
                            <?php
                            $sql_modulo = "SELECT valor FROM cs2.cadastro_modulos WHERE Id = 1";
                            $res_modulo = mysql_query($sql_modulo, $con);
                            if ( $matriz['modulo_loja_vitual']  == NULL or $matriz['modulo_loja_vitual']  == 0 )
                                echo mysql_result($res_modulo,0,'valor');
                            else
                                echo $matriz['modulo_loja_vitual'];
                            ?>                                
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Módulo Receber de Devedores</td>
                <td colspan="3" class="subtitulopequeno">
                    <select name="modulo_receber_deved" style="width:40%">
                        <?php
                        $sel_mod_1 = '';
                        $sel_mod_2 = '';
                        $sel_mod_3 = '';
                        if ( $matriz['modulo_receber_deved'] == NULL ) $sel_mod_1 = 'selected="selected"';
                        elseif ( $matriz['modulo_receber_deved'] == '0.00') $sel_mod_2 = 'selected="selected"';
                        else $sel_mod_3 = 'selected="selected"';
                        ?>
                        <option value = '9' <?php echo $sel_mod_1; ?>>nenhum</option>
                        <option value = '0' <?php echo $sel_mod_2; ?> >0,00</option>
                        <option value = '1' <?php echo $sel_mod_3; ?>>
                            <?php
                            $sql_modulo = "SELECT valor FROM cs2.cadastro_modulos WHERE Id = 3";
                            $res_modulo = mysql_query($sql_modulo, $con);
                            if ( $matriz['modulo_receber_deved']  == NULL or $matriz['modulo_receber_deved']  == 0 )
                                echo mysql_result($res_modulo,0,'valor');
                            else
                                echo $matriz['modulo_receber_deved'];
                            ?>                                
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Módulo Consulta de Crédito</td>
                <td colspan="3" class="subtitulopequeno">
                    <select name="modulo_pesq_credito" style="width:40%">
                        <?php
                        $sel_mod_1 = '';
                        $sel_mod_2 = '';
                        $sel_mod_3 = '';
                        if ( $matriz['modulo_pesq_credito'] == NULL ) $sel_mod_1 = 'selected="selected"';
                        elseif ( $matriz['modulo_pesq_credito'] == '0.00') $sel_mod_2 = 'selected="selected"';
                        else $sel_mod_3 = 'selected="selected"';
                        ?>
                        <option value = '9' <?php echo $sel_mod_1; ?>>nenhum</option>
                        <option value = '0' <?php echo $sel_mod_2; ?> >0,00</option>
                        <option value = '1' <?php echo $sel_mod_3; ?>>
                            <?php
                            $sql_modulo = "SELECT valor FROM cs2.cadastro_modulos WHERE Id = 2";
                            $res_modulo = mysql_query($sql_modulo, $con);
                            if ( $matriz['modulo_pesq_credito']  == NULL or $matriz['modulo_pesq_credito']  == 0 )
                                echo mysql_result($res_modulo,0,'valor');
                            else
                                echo $matriz['modulo_pesq_credito'];
                            ?>                                
                        </option>
                    </select>
                </td>
            </tr>

            <tr>
                <td class="subtitulodireita">Módulo Aumentar Clientes e Faturamento</td>
                <td colspan="3" class="subtitulopequeno">
                    <select name="modulo_aumentar_vendas" style="width:40%">
                        <?php
                        $sel_mod_1 = '';
                        $sel_mod_2 = '';
                        $sel_mod_3 = '';
                        if ( $matriz['modulo_aumentar_vendas'] == NULL ) $sel_mod_1 = 'selected="selected"';
                        elseif ( $matriz['modulo_aumentar_vendas'] == '0.00') $sel_mod_2 = 'selected="selected"';
                        else $sel_mod_3 = 'selected="selected"';
                        ?>
                        <option value = '9' <?php echo $sel_mod_1; ?>>nenhum</option>
                        <option value = '0' <?php echo $sel_mod_2; ?> >0,00</option>
                        <option value = '1' <?php echo $sel_mod_3; ?>>
                            <?php
                            $sql_modulo = "SELECT valor FROM cs2.cadastro_modulos WHERE Id = 4";
                            $res_modulo = mysql_query($sql_modulo, $con);
                            if ( $matriz['modulo_aumentar_vendas']  == NULL or $matriz['modulo_aumentar_vendas']  == 0 )
                                echo mysql_result($res_modulo,0,'valor');
                            else
                                echo $matriz['modulo_aumentar_vendas'];
                            ?>                                
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita"></td>
                <td colspan="3" class="subtitulopequeno">&nbsp;</td>
            </tr>
            <?php
        }
        ?>
        <?php if ($_SESSION['ss_tipo'] == "a") { ?>
            <tr>
                <td class="subtitulodireita">Franqueado</td>
                <td colspan="3" class="subtitulopequeno">
                    <?php
                    if ($_REQUEST['franqueado'] == "")
                        $franqueado = mysql_result($_res, 0, 'id_franquia');
                    else
                        $franqueado = $_REQUEST['franqueado'];
                    ?>
                    <select name="franqueado" style="width:70%" onchange="alterarFranquia()" >
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
                    </select>&nbsp;<input type="button" value="OK" onclick="refresch()">
                </td>
            </tr>
            <?php
        } else { ?>
            <tr>
                <td class="subtitulodireita">Franqueado</td>
                <td colspan="3" class="subtitulopequeno">
                    <?php
                    if ($_REQUEST['franqueado'] == "") {
                        if (empty($_res)) {
                            $franqueado = mysql_result($_res, 0, 'id_franquia');
                        } else {
                            $franqueado = '';
                        }

                    } else {
                        $franqueado = $_REQUEST['franqueado'];
                    }
                    ?>
                    <select name="franqueado" style="width:70%" disabled="disabled">
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
                <td class="subtitulodireita">Franqueado Junior</td>
                <td colspan="3" class="subtitulopequeno">
                    <select name="id_franquia_jr" style="width:70%" disabled="disabled">
                        <option value="0">&nbsp;</option>
                        <?php
                        $sql_jr = "SELECT id, id_franquia_master, razaosoc FROM cs2.franquia 
                               WHERE id_franquia_master = '$franqueado' AND classificacao = 'J'";
                        $resp_jr = mysql_query($sql_jr,$con);
                        if (mysql_num_rows($resp_jr) > 0) {
                            while ($row_jr = mysql_fetch_array($resp_jr)) {
                                $id_franquia_jr_row = $row_jr["id"];
                                $id_franquia_master = $row_jr["id_franquia_master"];
                                $razaosoc = $row_jr["razaosoc"];
                                if ($id_franquia_jr == $id_franquia_jr_row) {
                                    echo "<option value='$id_franquia_jr_row' selected>$id_franquia_jr_row - $razaosoc</option>";
                                } else {
                                    echo "<option value='$id_franquia_jr_row'>$id_franquia_jr_row - $razaosoc</option>";
                                }
                            }
                            echo "<option value='0'>Nenhum</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td class="subtitulodireita">Agendador</td>
            <td height="22" colspan="3" class="subtitulopequeno">
                <?php
                if ($_SESSION['ss_tipo'] == "a") {

                    $sql_sel = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '{$matriz['id_franquia']}'
                AND (tipo_cliente = '1' OR tipo_cliente = '2') AND situacao IN('0', '1') ORDER BY situacao, nome";
                    $qry = mysql_query($sql_sel, $con) or die("erro sql");
                    echo "<select name='id_agendador' id='id_agendador' style='width:65%'>";
                    ?>
                    <option value="">--Selecionar--</option>
                    <?php
                    while($rs = mysql_fetch_array($qry)) {
                        $id_agendador = $rs['id'];
                        if($rs['situacao'] == "0"){
                            $sit = "Ativo:";
                        }elseif($rs['situacao'] == "1"){
                            $sit = "Bloqueado";
                        }elseif($rs['situacao'] == "2"){
                            $sit = "Cancelado";
                        }
                        $nome = $rs['nome'].' - '.$sit;
                        if ($id_agendador == $matriz['id_agendador'] ) {
                            echo "<option value='$id_agendador' selected>{$nome}</option>";
                        } else {
                            echo "<option value='$id_agendador'>{$nome}</option>";
                        }
                    }?>
                    </select>
                    <?php
                } else {
                    echo $matriz['agendador']; ?>
                    <input name="agendador" type="hidden" id="agendador" value="<?php echo $matriz['agendador']; ?>" size="25" maxlength="20" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
                    <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Consultor</td>
            <td height="22" colspan="3" class="subtitulopequeno">
                <?php
                if ($_SESSION['ss_tipo'] == "a") {
                    $sql_sel = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '{$matriz['id_franquia']}'
                AND (tipo_cliente = '0' OR tipo_cliente = '2') AND situacao IN('0', '1') ORDER BY situacao, nome";
                    $qry = mysql_query($sql_sel, $con);
                    echo "<select name='id_consultor' id='id_consultor' style='width:65%'>";
                    ?>
                    <option value="">--Selecionar--</option>
                    <?php
                    while($rs = mysql_fetch_array($qry)) {
                        $id_consultor = $rs['id'];
                        if($rs['situacao'] == "0"){
                            $sit = "Ativo";
                        }elseif($rs['situacao'] == "1"){
                            $sit = "Bloqueado";
                        }elseif($rs['situacao'] == "2"){
                            $sit = "Cancelado";
                        }
                        $nome = $rs['nome'].' - '.$sit;
                        if ($id_consultor == $matriz['id_consultor'] ) {
                            echo "<option value='$id_consultor' selected>{$nome}</option>";
                        } else {
                            echo "<option value='$id_consultor'>{$nome}</option>";
                        }
                    }
                    ?>
                    </select>
                    <?php
                } else {
                    echo $matriz['vendedor']; ?>
                    <input name="vendedor" type="hidden" id="vendedor" value="<?php echo $matriz['vendedor']; ?>" size="25" maxlength="20" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
                    <?php
                } ?>
            </td>
        </tr>

        <?php
        if (($_SESSION['ss_tipo'] == "a") or ( $_SESSION["id"] == '4')) {
            ?>
            <tr>
                <td class="subtitulodireita">Emiss&atilde;o de Nota Fiscal de Servi&ccedil;o</td>
                <td colspan="3" valign="top" class="subtitulopequeno">
                    <select name="emitir_nfs">
                        <option value="" <?php if ($matriz['emitir_nfs'] == "") { echo "selected"; } ?> >Selecione</option>
                        <option value="S" <?php if ($matriz['emitir_nfs'] == "S") { echo "selected"; } ?> >SIM</option>
                        <option value="N" <?php if ($matriz['emitir_nfs'] == "N") { echo "selected"; } ?> >N&Atilde;O</option>
                    </select>
                </td>
            </tr>
            <?php
        }
        if( $_SESSION['id'] == '163' ){?>
            <tr>
                <td class="subtitulodireita">Valor M&aacute;ximo de Emissao do Cred/Rec/Bol</td>
                <td colspan="3" class="subtitulopequeno">
                    <input type="text" name="vr_max_limite_crediario" size="16" maxlength="12" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $matriz['vr_max_limite_crediario']; ?>" onKeydown="FormataValor(this,20,event,2)" style="text-align:right"></td>
            </tr>
            <?php
        } ?>
        <tr>
            <td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
            <td colspan="3" class="subtitulopequeno"><textarea name="obs" cols="50" rows="3"><?php echo $matriz['obs']; ?></textarea></td>
        </tr>

        <tr>
            <td class="subtitulodireita">Status</td>
            <td colspan="3" class="formulario"
                <?php
                if ($matriz['sitcli'] == "0") {
                    echo "bgcolor=\"#33CC66\"";
                } else {
                    echo "bgcolor=\"#FF0000\"";
                }
                ?>
            >
                <font color="#FFFFFF"><?php echo $matriz['descsit']; ?></font>
            </td>
        </tr>
        <?php
        $ssql = "select date_format(a.data_documento,'%d/%m/%Y') as documento, a.tipo_documento, b.motivo,
		date_format(a.ultima_fatura,'%d/%m/%Y') as ultima from pedidos_cancelamento a
		inner join motivo_cancel b on a.id_mot_cancelamento=b.id
		where codloja='$codloja'";
        $rs = mysql_query($ssql, $con);
        $line = mysql_num_rows($rs);
        if ($line != 0) {
            while ($fila = mysql_fetch_object($rs)) {
                echo "
                    <tr>
                      <td class='subtitulodireita'>Dados do Cancelamento </td>
                      <td class='subtitulopequeno'><table>
                        <tr>
                          <td class='subtitulodireita'>Data do Documento</td>
                          <td class='campoesquerda'>$fila->documento</td>
                        </tr>
                        <tr>
                          <td class='subtitulodireita'>Doc. de Cancelamento</td>
                          <td class='campoesquerda'>$fila->tipo_documento</td>
                        </tr>
                        <tr>
                          <td class='subtitulodireita'>Motivo do Cancelamento</td>
                          <td class='campoesquerda'>$fila->motivo</td>
                        </tr>
                        <tr>
                          <td class='subtitulodireita'>&Uacute;ltima Fatura</td>
                          <td class='campoesquerda'>$fila->ultima</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td class='subtitulodireita'>&nbsp;</td>
                      <td class='subtitulopequeno'><input type='checkbox' name='negativado' id='negativado' />
                        <label for='negativado'>Cliente Negativado (n&atilde;o sair na listagem de cobran&ccedil;a)</label></td>
                    </tr>";
            }
            mysql_free_result($rs);
        }
        ?>
        <tr>
            <td colspan="4" class="titulo">&nbsp;</td>
        </tr>

        <table width="234" align="center">
            <tr align="center">
                <td width="109">
                    <input name="alterar" type="button" value=" Modificar " onclick="confirma()" />
                </td>
                <td>
                    <input type="button" onClick="javascript: history.back();" value="       Voltar       " />
                </td>
            </tr>
        </table>
</form>
<?php
$res = mysql_close($con);
?>