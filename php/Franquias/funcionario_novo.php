<?php
require "connect/sessao.php";
require "connect/sessao_r.php";
$altura = "23";

//SELECIONA O BANCO
$sql_banco = "SELECT banco, nbanco FROM consulta.banco ORDER BY nbanco";
$qry_banco = mysql_query($sql_banco, $con);

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
?>
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
     #   $('input[name=iptPlaca]').mask('aaa-9a99');
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
        d.action = 'painel.php?pagina1=Franquias/funcionario_bd.php&acao=G';
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

    window.onload = function () {

        id('id_fone1').onkeypress = function () {
            xmascara(this, mtel);
        }
        id('id_fone2').onkeypress = function () {
            xmascara(this, mtel);
        }
        document.incclient.nome.focus();
    }

</script>

<form name="incclient" method="post" action="#">
    <table border="0" align="center" width="700" cellpadding="0" cellspacing="1">
        <tr height="25">
            <td colspan="2" class="titulo">Inclus&atilde;o de Funcion&aacute;rio</td>
        </tr>
        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Nome</td>
            <td class="subtitulopequeno"><input name="nome" type="text" style="width:60%" maxlength="50"
                                                onFocus="this.className='boxover'"
                                                onBlur="maiusculo(this); this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">RG</td>
            <td class="subtitulopequeno"><input name="rg" type="text" style="width:30%" maxlength="20"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td width="20%" class="subtitulodireita">CPF</td>
            <td width="80%" class="subtitulopequeno"><input name="cpf" alt="cpf" type="text" style="width:30%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">CEP</td>
            <td class="subtitulopequeno"><input type="text" name="cep" id="cep" style="width:20%" onChange=""
                                                onKeyPress="return MM_formtCep(event,this,'#####-###');"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"
                                                maxlength="9"/><font color="#FF0000">(*) </font>

                <div id="validcep" style="color: #FF0000;"></div>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Endere&ccedil;o</td>
            <td colspan="2" class="subtitulopequeno"><input type="text" name="endereco" id="logradouro"
                                                            style="width:60%" maxlength="60"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="maiusculo(this); this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">N&uacute;mero</td>
            <td class="subtitulopequeno" colspan="2"><input type="text" name="numero" id="numero" style="width:20%"
                                                            maxlength="10"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Complemento</td>
            <td colspan="2" class="subtitulopequeno"><input type="text" name="complemento" id="complemento"
                                                            maxlength="60" style="width:60%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="maiusculo(this); this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Bairro</td>
            <td colspan="2" class="subtitulopequeno"><input type="text" name="bairro" id="bairro" maxlength="50"
                                                            style="width:60%" onFocus="this.className='boxover'"
                                                            onBlur="maiusculo(this); this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Cidade</td>
            <td colspan="2" class="subtitulopequeno"><input type="text" name="cidade" id="localidade" maxlength="50"
                                                            style="width:60%" onFocus="this.className='boxover'"
                                                            onBlur="maiusculo(this); this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Telefone 1</td>
            <td colspan="2" class="subtitulopequeno"><input name="fone1" type="text" id="id_fone1" style="width:20%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Telefone 2</td>
            <td colspan="2" class="subtitulopequeno"><input name="fone2" type="text" id="id_fone2" style="width:20%"
                                                            onFocus="this.className='boxover'"
                                                            onBlur="this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Fun&ccedil;&atilde;o</td>
            <td class="subtitulopequeno">
                <select name="funcao" id="funcao">
                    <option value="0">Selecione</option>
                    <?php if($resFuncoes){
                        while($arrFuncao = mysql_fetch_array($resFuncoes)){ ?>

                            <option value="<?php echo $arrFuncao['id']?>"><?php echo $arrFuncao['funcao']?></option>

                        <?php }
                    }?>
                </select>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Salario Liquido</td>
            <td class="subtitulopequeno"><input name="salario" alt="decimal" type="text" style="width:20%"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">VT + VR</td>
            <td class="subtitulopequeno"><input name="vt" alt="decimal" type="text" style="width:20%"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Comissão Afiliação</td>
            <td class="subtitulopequeno"><input name="iptComissaoAfiliacao" alt="decimal" type="text" style="width:20%"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Comissão Equipamento/Produtos</td>
            <td class="subtitulopequeno"><input name="iptComissaoEquipamentos" id="iptComissaoEquipamentos"
                                                type="text" style="width:20%" alt="decimal"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"
                                                maxlength="3"/> %
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Veículo</td>
            <td class="subtitulopequeno">
                <select name="iptCarro" id="iptCarro">
                    <option></option>
                    <option>Uno Mille</option>
                    <option>Palio Fire</option>
                    <option>Pegeout</option>
                    <option>GOL</option>
                </select>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Placa</td>
            <td class="subtitulopequeno">
                <input name="iptPlaca" id="iptPlaca"
                    type="text" style="width:20%"
                    onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"
                    maxlength="3"/>
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
                        echo "<option value'$id_franquia'>$id_franquia - $nome_franquia</option>\n";
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
                    echo "<option value=''></option>\n";
                    echo "<option value='A'>Agendador(a)</option>\n";
                    echo "<option value='C'>Consultor(a)</option>\n";
                    ?>
                </select>

                <select name="id_consultor_assistente">
                    <option value="0"></option>
                    <?php
                    $sql = "SELECT id, nome FROM cs2.consultores_assistente
                            WHERE id_franquia=1 AND tipo_cliente IN ('0','1') AND situacao < 2
                            ORDER BY nome";
                    $resposta = mysql_query($sql, $con);
                    echo "<option value=''></option>\n";
                    while ($array = mysql_fetch_array($resposta)) {
                        $id = $array["id"];
                        $nome = $array["nome"];
                        echo "<option value='$id'>$id - $nome</option>\n";
                    }
                    ?>
                </select>
            </td>
        </tr>
        
        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Adiantamento</td>
            <td class="subtitulopequeno"><input name="adiantamento" alt="decimal" type="text" style="width:20%"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Horario</td>
            <td class="subtitulopequeno"><input name="horario" maxlength="60" type="text" style="width:60%"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Nr. do Banco</td>
            <td class="subtitulopequeno"><input name="nr_banco" maxlength="10" type="text" style="width:60%"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Banco</td>
            <td class="subtitulopequeno"><input name="banco" maxlength="50" type="text" style="width:60%"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Ag&ecirc;ncia</td>
            <td class="subtitulopequeno"><input name="agencia" type="text" style="width:60%" maxlength="6"
                                                onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Tipo Conta</td>
            <td class="subtitulopequeno">
                Corrente<input type="radio" name="tp_conta" value="C">
                &nbsp;&nbsp;&nbsp;
                Poupan&ccedil;a<input type="radio" name="tp_conta" value="P">
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Conta</td>
            <td class="subtitulopequeno"><input name="conta" type="text" style="width:60%" maxlength="20"
                                                onFocus="this.className='boxover'"
                                                onBlur="maiusculo(this); this.className='boxnormal'"/></td>
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
                        echo "<option value='$id_emp'>$id_emp - $nome_empresa</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        
        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Data Admiss&atilde;o</td>
            <td class="subtitulopequeno"><input name="data_admissao" id="id_data_admissao" type="text" style="width:20%"
                                                maxlength="50" onFocus="this.className='boxover'"
                                                onBlur="this.className='boxnormal'"/></td>
        </tr>

        <tr height="<?= $altura ?>">
            <td class="subtitulodireita">Observa&ccedil;&atilde;o</td>
            <td class="subtitulopequeno"><textarea name="obs" rows="3" class="inputi99" style="width:99%;"></textarea>
            </td>
        </tr>

        <tr height="<?= $altura ?>">
            <td width="20%" class="subtitulodireita">&nbsp;</td>
            <td width="80%" class="subtitulopequeno"> Preenchimento obrigat&oacute;rio</td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td><input name="Enviar" type="button" value="Confirma"
                       onclick="validaFuncionario();"/>&nbsp;&nbsp;&nbsp;<input name="Enviar" type="button"
                                                                                value="Retorna a Listagem"
                                                                                onclick="retornaListagem();"/></td>
        </tr>
    </table>
</form>
