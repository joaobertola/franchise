<?php
require "connect/funcoes.php";
require "connect/sessao.php";
require "connect/sessao_r.php";
$altura = "23";

$idFuncionarioLanc = $_REQUEST['id'];

$sqlFuncoes = "SELECT
                    f.id,
                    d.descricao AS departamento,
                    f.descricao AS funcao,
                    f.ativo AS ativo,
                    IF(f.ativo = 1, 'Ativo', 'Inativo') AS ativo_label
                FROM cs2.funcao f
                INNER JOIN cs2.departamento d
                ON d.id = f.id_departamento
                WHERE f.ativo = 1
                ORDER BY funcao ASC;";
$resFuncoes = mysql_query($sqlFuncoes, $con);

$sqlLancamentos = "SELECT
                        id,
                        id_funcionario,
                        CONCAT('R$ ', REPLACE(REPLACE(REPLACE(FORMAT(valor,2),'.',':'),',','.'),':',',')) AS valor,
                        DATE_FORMAT(data_folha, '%d/%m/%Y') AS data_folha_label,
                        IF(tipo_lancamento = 'D', 'Débito', 'Crédito') AS tipo_lancamento,
                        DATE_FORMAT(data_lancamento, '%d/%m/%Y') AS data_lancamento,
                        descricao
                    FROM cs2.lancamento_funcionario
                    WHERE id_funcionario = '$idFuncionarioLanc'
                     ;
";
$resLanc = mysql_query($sqlLancamentos,$con);

function telefoneConverteFunc($p_telefone)
{
    if ($p_telefone == '') {
        return ('');
    } else {
        $a = substr($p_telefone, 0, 2);
        $b = substr($p_telefone, 2, 4);
        $c = substr($p_telefone, 6, 4);

        $telefone_mascarado = "(";
        $telefone_mascarado .= $a;
        $telefone_mascarado .= ")&nbsp;";
        $telefone_mascarado .= $b;
        $telefone_mascarado .= "-";
        $telefone_mascarado .= $c;
        return ($telefone_mascarado);
    }
}

function converteDataBancoViewFunc($p_data_banco)
{
    $data_view = '';
    $dia = substr($p_data_banco, 8, 2);
    $mes = substr($p_data_banco, 5, 2);
    $ano = substr($p_data_banco, 0, 4);
    $data_view .= $dia;
    $data_view .= "/";
    $data_view .= $mes;
    $data_view .= "/";
    $data_view .= $ano;
    return ($data_view);
}

//SELECIONA O BANCO
$sql_banco = "SELECT banco, nbanco FROM consulta.banco ORDER BY nbanco";
$qry_banco = mysql_query($sql_banco, $con);

//SELECIONA O FUNCIONARIO PARA SER ALTERADO
$sql_func = "SELECT * FROM cs2.funcionario WHERE id = '{$_REQUEST['id']}'";
$qry = mysql_query($sql_func, $con);

if (mysql_num_rows($qry) == 0) {
    echo "Funcionario nao encontrado";
    die;
}
$cpf = mysql_result($qry, 0, 'cpf');
$rg = mysql_result($qry, 0, 'rg');
$nome = mysql_result($qry, 0, 'nome');
$tp_conta = mysql_result($qry, 0, 'tp_conta');
$funcao = mysql_result($qry, 0, 'funcao');
$salario = mysql_result($qry, 0, 'salario');
$salario_bruto = mysql_result($qry, 0, 'salario_bruto');
$adiantamento = mysql_result($qry, 0, 'adiantamento');
//$nome_mae = mysql_result($qry,0,'nome_mae');
$banco = mysql_result($qry, 0, 'banco');
$nr_banco = mysql_result($qry, 0, 'nr_banco');
$agencia = mysql_result($qry, 0, 'agencia');
$conta = mysql_result($qry, 0, 'conta');
$cep = mysql_result($qry, 0, 'cep');
$endereco = mysql_result($qry, 0, 'endereco');
$numero = mysql_result($qry, 0, 'numero');
$complemento = mysql_result($qry, 0, 'complemento');
$bairro = mysql_result($qry, 0, 'bairro');
$cidade = mysql_result($qry, 0, 'cidade');
$fone1 = mascara_celular(mysql_result($qry, 0, 'fone1'));
$fone2 = mascara_celular(mysql_result($qry, 0, 'fone2'));
$data_admissao = converteDataBancoViewFunc(mysql_result($qry, 0, 'data_admissao'));
$data_demissao = converteDataBancoViewFunc(mysql_result($qry, 0, 'data_demissao'));
$ativo = mysql_result($qry, 0, 'ativo');
$horario = mysql_result($qry, 0, 'horario');
$vt = mysql_result($qry, 0, 'vt');
$vr = mysql_result($qry, 0, 'vr');
$obs = mysql_result($qry, 0, 'obs');
$senha = mysql_result($qry, 0, 'senha');
$id_franqueado = mysql_result($qry, 0, 'id_franqueado');
$id_empregador = mysql_result($qry, 0, 'id_empregador');
$comissao_afiliacao = mysql_result($qry, 0, 'comissao_afiliacao');
$comissao_equipamento = mysql_result($qry, 0, 'comissao_equipamento');
$veiculo = mysql_result($qry, 0, 'veiculo');
$placa = mysql_result($qry, 0, 'placa');
$cao = mysql_result($qry, 0, 'consultor_assistente');
$id_consultor_assistente = mysql_result($qry, 0, 'id_consultor_assistente');
$idFuncao = mysql_result($qry, 0, 'id_funcao');

?>
<link rel="stylesheet" href="../css/assets/css/bootstrap.min.css">
<!---->
<!-- Optional theme -->
<link rel="stylesheet" href="../css/assets/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="../css/assets/css/sweetalert.css">
<link rel="stylesheet" href="../css/assets/css/jquery-ui-1.9.2.custom.css">
<link rel="stylesheet" href="../css/assets/css/jquery-ui-1.9.2.custom.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../css/assets/js/bootstrap.min.js"></script>
<script src="../css/assets/js/sweetalert.min.js"></script>
<script src="../css/assets/js/jquery-ui-1.9.2.js"></script>
<script src="../css/assets/js/jquery.maskMoney.js"></script>
<style>
    .cursorpointer {
        cursor: pointer;
    }
</style>
<script type="text/javascript">

    jQuery.browser = {};
    (function () {
        jQuery.browser.msie = false;
        jQuery.browser.version = 0;
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            jQuery.browser.msie = true;
            jQuery.browser.version = RegExp.$1;
        }
    })();

    jQuery(function ($) {
        $("#id_data_admissao").mask("99/99/9999");
        $("#id_data_demissao").mask("99/99/9999");
        //$('input[name=iptPlaca]').mask('aaa-9999');
    });

    (function ($) {
        $(function () {
                $('input:text').setMask();
            }
        );
    })(jQuery);

    // função q preenche automáticamente o CEP
    function addEvent(obj, evt, func) {
        if (obj.attachEvent) {
            return obj.attachEvent(("on" + evt), func);
        } else if (obj.addEventListener) {
            obj.addEventListener(evt, func, true);
            return true;
        }
        return false;
    }

    function XMLHTTPRequest() {
        try {
            return new XMLHttpRequest(); // FF, Safari, Konqueror, Opera, ...
        } catch (ee) {
            try {
                return new ActiveXObject("Msxml2.XMLHTTP"); // activeX (IE5.5+/MSXML2+)
            } catch (e) {
                try {
                    return new ActiveXObject("Microsoft.XMLHTTP"); // activeX (IE5+/MSXML1)
                } catch (E) {
                    return false; // doesn't support
                }
            }
        }
    }

    function buscarEndereco() {
        var campos = {
            validcep: document.getElementById("validcep"),
            cep: document.getElementById("cep"),
            logradouro: document.getElementById("logradouro"),
            numero: document.getElementById("numero"),
            complemento: document.getElementById("complemento"),// IMPLEMENTADO NA VERSÃO 4.0
            bairro: document.getElementById("bairro"),
            localidade: document.getElementById("localidade"),
        };

        var ajax = XMLHTTPRequest();
        ajax.open("GET", ("../client.php?cep=" + campos.cep.value.replace(/[^\d]*/, "")), true);

        ajax.onreadystatechange = function () {
            if (ajax.readyState == 1) {
                campos.logradouro.disabled = true;
                campos.logradouro.value = "carregando...";
                campos.bairro.disabled = true;
                campos.localidade.disabled = true;
                campos.bairro.value = "carregando...";

                campos.numero.disabled = true;// IMPLEMENTADO NA VERSÃO 4.0
                campos.numero.value = "carregando...";

                campos.complemento.disabled = true;// IMPLEMENTADO NA VERSÃO 4.0
                campos.complemento.value = "carregando...";

                campos.localidade.value = "carregando...";
            } else if (ajax.readyState == 4) {
                if (ajax.responseText == false) {
                    campos.validcep.innerHTML = "Cep invalido !!!";
                    campos.logradouro.disabled = false;
                    campos.logradouro.value = "";
                    campos.numero.disabled = false;// IMPLEMENTADO NA VERSÃO 4.0
                    campos.numero.value = "";// IMPLEMENTADO NA VERSÃO 4.0
                    campos.complemento.disabled = false;// IMPLEMENTADO NA VERSÃO 4.0
                    campos.complemento.value = "";// IMPLEMENTADO NA VERSÃO 4.0
                    campos.bairro.disabled = false;
                    campos.localidade.disabled = false;
                    campos.bairro.value = "";
                    campos.localidade.value = "";
                } else {
                    campos.validcep.innerHTML = "";
                    var r = ajax.responseText, i, logradouro, complemento, numero, bairro, localidade;
                    logradouro = r.substring(0, (i = r.indexOf(':')));
                    campos.logradouro.disabled = false;
                    campos.logradouro.value = unescape(logradouro.replace(/\+/g, " "));
                    <!-- IMPLEMENTADO NA VERSÃO 4.0 -->
                    r = r.substring(++i);
                    complemento = r.substring(0, (i = r.indexOf(':')));
                    campos.complemento.disabled = false;
                    campos.complemento.value = unescape(complemento.replace(/\+/g, " "));

                    r = r.substring(++i);
                    bairro = r.substring(0, (i = r.indexOf(':')));
                    campos.bairro.disabled = false;
                    campos.bairro.value = unescape(bairro.replace(/\+/g, " "));
                    r = r.substring(++i);
                    localidade = r.substring(0, (i = r.indexOf(':')));
                    campos.localidade.disabled = false;
                    campos.localidade.value = unescape(localidade.replace(/\+/g, " "));

                    <!-- IMPLEMENTADO NA VERSÃO 4.0 -->
                    r = r.substring(++i);
                    numero = r.substring(0, (i = r.indexOf(':')));
                    campos.numero.disabled = false;
                    campos.numero.value = unescape(numero.replace(/\+/g, " "));

                }
            }
        };
        ajax.send(null);
    }

    window.addEvent(
        window,
        "load",
        function () {
            window.addEvent(document.getElementById("cep"), "blur", buscarEndereco);
        }
    );

    function MM_formtCep(e, src, mask) {
        if (window.event) {
            _TXT = e.keyCode;
        }
        else if (e.which) {
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
            }
            else {
                return true;
            }
        }
    }

    //a pesar que parece comentário, é melhor não mexer nisto, pq reconhece os browsers
    function getHTTPObject() {
        var xmlhttp;
        if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
            try {
                xmlhttp = new XMLHttpRequest();
            } catch (e) {
                xmlhttp = false;
            }
        }
        return xmlhttp;
    }
    var http = getHTTPObject();

    function trim(str) {
        return str.replace(/^\s+|\s+$/g, "");
    }//valida espaço em branco

    function validaFuncionario() {
        d = document.incclient;
        d.action = 'painel.php?pagina1=Franquias/funcionario_bd.php&acao=A';
        d.submit();
    }

    function retornaListagem() {
        form = document.incclient;
        form.action = 'painel.php?pagina1=Franquias/funcionario_listagem.php&lista_ativo=S';
        form.submit();
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
    
    function Limpa(item,data){
        if ( item == 'E'){
            $("#iptDataLancamento").val(data);
            $("#iptDescricao").val('ADIANTAMENTO SALARIAL');
            
        }else{
            $("#iptDataLancamento").val('');
            $("#iptDescricao").val('');
        }
        $("#iptValorLancamento").focus();
    }

    window.onload = function () {

        id('id_fone1').onkeypress = function () {
            xmascara(this, mtel);
        }
        id('id_fone2').onkeypress = function () {
            xmascara(this, mtel);
        }
        document.incclient.rg.focus();
    }

</script>

<form name="incclient" method="post" action="#">
    <input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
    <table border="0" align="center" width="700" cellpadding="0" cellspacing="1">
        <tr height="25">
            <td colspan="3" class="titulo">Altera&ccedil;&atilde;o / Visualiza&ccedil;&atilde;o de Funcion&aacute;rio
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Nome</td>
            <td colspan="2" class="subtitulopequeno"><input name="nome" value="<?= $nome ?>" type="text"
                                                            style="width:60%" maxlength="50"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="maiusculo(this); this.className='boxnormal'"/></font>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">RG</td>
            <td colspan="2" class="subtitulopequeno"><input name="rg" value="<?= $rg ?>" type="text" style="width:30%"
                                                            maxlength="20" onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></font></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td width="20%" class="subtitulodireita">CPF</td>
            <td width="80%" colspan="2" class="subtitulopequeno"><input name="cpf" value="<?= $cpf ?>" alt="cpf"
                                                                        type="text" style="width:30%"
                                                                        onFocus="this.className='boxover'"
                                                                        onBlur="this.className='boxnormal'"/></font>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">CEP</td>
            <td colspan="2" class="subtitulopequeno"><input type="text" name="cep" id="cep" value="<?= $cep ?>"
                                                            style="width:20%" onChange=""
                                                            onKeyPress="return MM_formtCep(event,this,'#####-###');"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'" maxlength="9"/> </font>
                <div id="validcep" style="color: #FF0000;"></div>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Endere&ccedil;o</td>
            <td colspan="3" class="subtitulopequeno"><input type="text" name="endereco" value="<?= $endereco ?>"
                                                            id="logradouro" style="width:60%" maxlength="60"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="maiusculo(this); this.className='boxnormal'"/></font>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">N&uacute;mero</td>
            <td class="subtitulopequeno" colspan="3"><input type="text" name="numero" value="<?= $numero ?>" id="numero"
                                                            style="width:20%" maxlength="10"/></font></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Complemento</td>
            <td colspan="3" class="subtitulopequeno"><input type="text" name="complemento" value="<?= $complemento ?>"
                                                            id="complemento" maxlength="60" style="width:60%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="maiusculo(this); this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Bairro</td>
            <td colspan="3" class="subtitulopequeno"><input type="text" name="bairro" value="<?= $bairro ?>" id="bairro"
                                                            maxlength="50" style="width:60%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="maiusculo(this); this.className='boxnormal'"/></font>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Cidade</td>
            <td colspan="3" class="subtitulopequeno"><input type="text" name="cidade" value="<?= $cidade ?>"
                                                            id="localidade" maxlength="50" style="width:60%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="maiusculo(this); this.className='boxnormal'"/></font>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Telefone 1</td>
            <td colspan="3" class="subtitulopequeno"><input name="fone1" value="<?= $fone1 ?>" type="text" id="id_fone1"
                                                            style="width:20%" onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></font></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Telefone 2</td>
            <td colspan="3" class="subtitulopequeno"><input name="fone2" value="<?= $fone2 ?>" type="text" id="id_fone2"
                                                            style="width:20%" onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></td>
        </tr>


        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Fun&ccedil;&atilde;o</td>
            <td colspan="2" class="subtitulopequeno">
                <select name="funcao" id="funcao">
                    <option value="0">Selecione</option>
                    <?php if ($resFuncoes) {
                        while ($arrFuncao = mysql_fetch_array($resFuncoes)) { ?>

                            <option
                                value="<?php echo $arrFuncao['id'] ?>" <?php echo $idFuncao == $arrFuncao['id'] ? 'selected' : '' ?>><?php echo $arrFuncao['funcao'] ?></option>

                        <?php }
                    } ?>
                </select>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Sal&aacute;rio Bruto</td>
            <td colspan="2" class="subtitulopequeno"><input name="salario" value="<?= $salario_bruto ?>" alt="decimal"
                                                            type="text" style="width:20%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></font></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">VT + VR</td>
            <td colspan="2" class="subtitulopequeno"><input name="vt" value="<?= $vt ?>" alt="decimal" type="text"
                                                            style="width:20%" onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></font></td>
        </tr>
        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Comissão Afiliação</td>
            <td class="subtitulopequeno" colspan="2">
                <input name="iptComissaoAfiliacao" alt="decimal" type="text" style="width:20%"
                       onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"
                       value="<?php echo $comissao_afiliacao ?>"/> %
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Adiantamento</td>
            <td colspan="2" class="subtitulopequeno"><input name="adiantamento" value="<?= $adiantamento ?>"
                                                            alt="decimal" type="text" style="width:20%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/>
                <!--
                            <a href="painel.php?pagina1=Franquias/novo_adiantamento.php&id_func=<? echo $_REQUEST['id']; ?>">
                            <b><font color="#FF6600"> <img src="../../img/drop-add.gif"/>Inserir Adiantamento</b></a>
                -->
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Comissão Equipamento/Produtos</td>
            <td class="subtitulopequeno" colspan="2">
                <input name="iptComissaoEquipamentos" id="iptComissaoEquipamentos"
                       alt="decimal" type="text" style="width:20%; text-align: right"  
                       onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"
                       maxlength="5"
                       value="<?php echo $comissao_equipamento ?>"/> %
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Veículo</td>
            <td class="subtitulopequeno" colspan="2">
                <select name="iptCarro" id="iptCarro">
                    <option value="">Selecione</option>
                    <option value="" <?php echo $veiculo == '' ? 'selected' : '' ?>></option>
                    <option value="Uno Mille" <?php echo $veiculo == 'Uno Mille' ? 'selected' : '' ?>>Uno Mille</option>
                    <option value="Palio Fire" <?php echo $veiculo == 'Palio Fire' ? 'selected' : '' ?>>Palio Fire</option>
                    <option value="Pegeout" <?php echo $veiculo == 'Pegeout' ? 'selected' : '' ?>>Pegeout</option>
                    <option value="Gol" <?php echo $veiculo == 'Gol' ? 'selected' : '' ?>>Gol</option>
                </select>
            </td>

        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Placa</td>
            <td class="subtitulopequeno" colspan="2">
                <input name="iptPlaca" id="iptPlaca"
                       type="text" style="width:20%"
                       onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"
                       value="<?php echo $placa ?>"/>
            </td>
        </tr>
        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Franquia</td>
            <td colspan="2" class="subtitulopequeno">
                <select name="id_franquia">
                    <?php

                    $sql = "SELECT id, fantasia FROM franquia WHERE id=1 or id=2";
                    $resposta = mysql_query($sql, $con);
                    while ($array = mysql_fetch_array($resposta)) {
                        $id_franquia = $array["id"];
                        $nome_franquia = $array["fantasia"];
                        if ($id_franqueado == $id_franquia) {
                            echo "<option value=\"$id_franquia\" selected>$id_franquia - $nome_franquia</option>\n";
                        } else {
                            echo "<option value=\"$id_franquia\"";
                            if ($id_franqueado == $matriz['id_franquia']) echo "selected";
                            echo ">$id_franquia - $nome_franquia</option>\n";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Consultor/Assistente</td>
            <td colspan="2" class="subtitulopequeno">
                <select name="consultor_assistente">
                    <option value="0"></option>
                    <?php
                    $selectA = '';
                    $selectC = '';
                    if ($cao == 'A') $selectA = 'selected';
                    if ($cao == 'C') $selectC = 'selected';
                    echo "<option value='A' $selectA>Agendador(a)</option>\n";
                    echo "<option value='C' $selectC>Consultor(a)</option>\n";
                    ?>
                </select>

                <select name="id_consultor_assistente">
                    <option value="0"></option>
                    <?php
                    $sql = "SELECT id, nome FROM cs2.consultores_assistente
                            WHERE id_franquia=1 AND tipo_cliente IN ('0','1') AND situacao < 2
                            ORDER BY nome";
                    $resposta = mysql_query($sql, $con);
                    while ($array = mysql_fetch_array($resposta)) {
                        $id = $array["id"];
                        $nome = $array["nome"];
                        if ($id == $id_consultor_assistente) {
                            echo "<option value='$id' selected>$id - $nome</option>\n";
                        } else {
                            echo "<option value='$id'>$id - $nome</option>\n";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>


        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Horario</td>
            <td colspan="2" class="subtitulopequeno"><input name="horario" value="<?= $horario ?>" maxlength="60"
                                                            type="text" style="width:60%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></font></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Nr. do Banco</td>
            <td colspan="2" class="subtitulopequeno"><input name="nr_banco" value="<?= $nr_banco ?>" maxlength="10"
                                                            type="text" style="width:60%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Banco</td>
            <td colspan="2" class="subtitulopequeno"><input name="banco" value="<?= $banco ?>" maxlength="50"
                                                            type="text" style="width:60%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Ag&ecirc;ncia</td>
            <td colspan="2" class="subtitulopequeno"><input name="agencia" value="<?= $agencia ?>" type="text"
                                                            style="width:60%" maxlength="6"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></font></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Tipo Conta</td>
            <td colspan="2" class="subtitulopequeno">
                Corrente<input type="radio" name="tp_conta"
                               value="C" <?php if ($tp_conta == "C") { ?> checked="checked" <?php } ?> ></font>
                &nbsp;&nbsp;&nbsp;
                Poupan&ccedil;a<input type="radio" name="tp_conta"
                                      value="P" <?php if ($tp_conta == "P") { ?> checked="checked" <?php } ?>></font>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Conta</td>
            <td colspan="2" class="subtitulopequeno"><input name="conta" value="<?= $conta ?>" type="text"
                                                            style="width:60%" maxlength="20"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="maiusculo(this); this.className='boxnormal'"/></font>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Empregador</td>
            <td colspan="2" class="subtitulopequeno">
                <select name="id_empregador">
                    <?php

                    $sql = "SELECT id, nome_empresa FROM cs2.empregador";
                    $resposta = mysql_query($sql, $con);
                    while ($array = mysql_fetch_array($resposta)) {
                        $id_emp = $array["id"];
                        $nome_empresa = $array["nome_empresa"];

                        if ($id_emp == $id_empregador) echo $select = "selected";
                        else $select = '';

                        echo "<option value='$id_emp' $select >$id_emp - $nome_empresa</option>";


                    }
                    ?>
                </select>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Data Admiss&atilde;o</td>
            <td colspan="2" class="subtitulopequeno"><input name="data_admissao" value="<?= $data_admissao ?>"
                                                            id="id_data_admissao" type="text" style="width:20%"
                                                            maxlength="50" onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></font></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Data Demiss&atilde;o</td>
            <td colspan="2" class="subtitulopequeno"><input name="data_demissao" value="<?= $data_demissao ?>"
                                                            id="id_data_demissao" type="text" style="width:20%"
                                                            maxlength="50" onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></font></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Ativo</td>
            <td colspan="2" class="subtitulopequeno">Sim<input type="radio" name="ativo"
                                                               value="S" <?php if ($ativo == 'S') { ?> checked="checked" <?php } ?> />&nbsp;&nbsp;&nbsp;N&atilde;o<input
                    type="radio" name="ativo"
                    value="N" <?php if ($ativo == 'N') { ?> checked="checked" <?php } ?> /></font></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Observa&ccedil;&atilde;o</td>
            <td colspan="2" class="subtitulopequeno"><textarea name="obs" rows="3" class="inputi99"
                                                               style="width:99%;"><?= $obs ?></textarea></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Senha Funcion&aacute;rio</td>
            <td colspan="2" class="subtitulopequeno"><input type="text" name="senha" class="inputi99"
                                                            value="<?= $senha ?>"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td width="20%" class="subtitulodireita">&nbsp;</td>
            <td width="40%" class="subtitulopequeno"></font> Preenchimento obrigat&oacute;rio</td>
            <td width="40%" class="subtitulopequeno">
                <a href="painel.php?pagina1=Franquias/extrato_pagamentos_funcionarios.php&id=<?= $_REQUEST['id'] ?>"
                   class="link_simples">Extrato de Pagamentos</font></td>
        </tr>

        <tr height="30px">
            <td>&nbsp;</td>
            <td colspan="2"><input name="Enviar" type="button" value="Salvar" onclick="validaFuncionario();"/>&nbsp;&nbsp;&nbsp;<input
                    name="Enviar" type="button" value="Retorna a Listagem" onclick="retornaListagem();"/></td>
        </tr>
    </table>


    <table border="0" align="center" width="700" cellpadding="0" cellspacing="1" style="margin-top: 30px;">
        <tr height="25">
            <td colspan="4" class="titulo">Adicionar Lançamentos
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Tipo Lançamento</td>
            <td colspan="3" class="subtitulopequeno">
                <input type="radio" name="iptTipoLancamento" id="iptTipoLancamentoC" value="C" onclick="Limpa(this.value,'')">Crédito&nbsp;&nbsp;&nbsp;
                <input type="radio" name="iptTipoLancamento" id="iptTipoLancamentoD" value="D" onclick="Limpa(this.value,'')">Débito
                <input type="radio" name="iptTipoLancamento" id="iptTipoLancamentoE" value="E" onclick="Limpa(this.value,'<?php echo date('d/m/Y');?>')">Recibo Adiantamento Salário
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Valor</td>
            <td colspan="3" class="subtitulopequeno">
                <input type="text" id="iptValorLancamento" name="iptValorLancamento">
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Data/Folha</td>
            <td colspan="3" class="subtitulopequeno">
                <input type="text" id="iptDataLancamento" name="iptDataLancamento" class="datepicker">
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Descrição</td>
            <td colspan="3" class="subtitulopequeno">
                <textarea id="iptDescricao" name="iptDescricao" rows="2" style="resize: none; width: 400px;"> </textarea>
            </td>
        </tr>
    </table>
    <div class="row text-center" style="margin-top: 30px;">
        <button id="btnSalvar" type="button">Salvar</button>
    </div>

    <table border="0" align="center" width="800" cellpadding="0" cellspacing="1" style="margin-top: 30px;">
        <thead>
        <tr height="25">
            <td colspan="6" class="titulo">Lançamentos Funcionário
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulopequeno text-center">Data Lançamento</td>
            <td class="subtitulopequeno text-center">Descricao</td>
            <td class="subtitulopequeno text-center">Tipo Lançamento</td>
            <td class="subtitulopequeno text-center">Valor</td>
            <td class="subtitulopequeno text-center">Data/Folha</td>
            <td class="subtitulopequeno text-center">Excluir</td>
        </tr>
        </thead>
        <tbody id="tbodyLancamentos">
        <?php if($resLanc){
            while($arrLancamentos = mysql_fetch_array($resLanc)) {
                ?>
                <tr data-id="<?php echo $arrLancamentos['id'] ?>">
                    <td class="corpoTabela text-center"><?php echo $arrLancamentos['data_lancamento']?></td>
                    <td class="corpoTabela text-center"><?php echo $arrLancamentos['descricao']?></td>
                    <td class="corpoTabela text-center"><?php echo $arrLancamentos['tipo_lancamento']?></td>
                    <td class="corpoTabela text-center"><?php echo $arrLancamentos['valor']?></td>
                    <td class="corpoTabela text-center"><?php echo $arrLancamentos['data_folha_label']?></td>
                    <td class="corpoTabela text-center"><span class="glyphicon glyphicon-remove btnRemoverLancamento cursorpointer" style="color: red;"></span></td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>

</form>
<input type="hidden" id="iptIdFuncionario" value="<?php echo $idFuncionarioLanc ?>">
<script>
    $(document).ready(function () {
        $('.datepicker').datepicker({
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'S&aacute;bado'],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'S&aacute;b', 'Dom'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior',
            dateFormat: 'dd/mm/yy'
        });

        $("input[name=iptValorLancamento]").maskMoney({
            prefix: 'R$ '
            , allowNegative: true
            , thousands: '.'
            , decimal: ','
            , affixesStay: false
        });


        $('#btnSalvar').on('click', function(){

            var tipo = $('input[name="iptTipoLancamento"]:checked').val();
            var valor  = $('#iptValorLancamento').val();
            var dataLancamento = $('#iptDataLancamento').val();
            var idFuncionario = $('#iptIdFuncionario').val();
            var descricao = $('#iptDescricao').val();

            if(valor == 0 || valor == ''){
                alert('Favor preencher o valor!');
                return false;
            }

            if(dataLancamento == '' || dataLancamento == '00/00/0000'){
                alert('Favor preencher a Data de Lançamento!');
                return false;
            }

            $.ajax({

                url: '../php/DepartamentoFuncoes.php',
                data: {
                    action: 'adicionarLancamento',
                    idFuncionario: idFuncionario,
                    tipo: tipo,
                    valor: valor,
                    dataLancamento: dataLancamento,
                    descricao: descricao
                },
                type: 'POST',
                dataType: 'json',
                success: function(data){
                    if(data.retorno == 1){
                        location.reload();
                    }else if(data.retorno == 2){
                        var link = '../php/painel.php?pagina1=Franquias/printReciboAdiantamentoSalarial.php&id='+data.id;
                        window.open(link);
                        location.reload();
                    }
                }

            });
        });

        $('.btnRemoverLancamento').on('click', function(){

            var id = $(this).parent().parent().data('id');
            var objLinha = $(this).parent().parent();

            $.ajax({
                url: '../php/DepartamentoFuncoes.php',
                data: {
                    action: 'removerLancamento',
                    id: id
                },
                type: 'POST',
                dataType: 'json',
                success: function(data){

                    if(data.retorno == 1){
                        objLinha.remove();
                    }

                }
            })

        });
    });
</script>