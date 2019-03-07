<style type="text/css">
    a{cursor:pointer;}
    h1 {
        font-size: 140%;
    }

    form {
        margin: 30px 50px 0;
    }

    form input, select {
        font-family: Arial;
        font-size: 8pt;
    }

    form input#numero, form input#uf, form input#cep {
        float: left;
        width: 75px;
    }

    address {
        clear: both;
        padding: 30px 0;
    }
</style>

<?php
require "connect/sessao.php";
require "connect/sessao_r.php";

$id_franquia = $_REQUEST ['b_rel_franquia'];
$idFranquiaAux = $_SESSION ['id'];

if ($id_franquia == 4 || $id_franquia == 5 || $id_franquia == 163 || $id_franquia == 247)
    $id_franquia = 1;

$protocolo = $_REQUEST ['protocolo'];
$origem = $_REQUEST ['origem'];

// echo "<pre>";
// print_r( $_REQUEST );

$sql_pesquisa = "SELECT
                        date_format(data_cadastro,'%d/%m/%Y') as data_cadastro,
                        date_format(data_agendamento,'%d/%m/%Y') as data_agendamento,
                        hora_agendamento, assitente_comercial,
                        id_consultor, empresa, endereco, bairro, cidade, ponto_referencia, fone1, fone2, responsavel,
                        status_venda, codigo_cliente, ocorrencia,
                        date_format(data_venda,'%d/%m/%Y') as data_venda, id_assistente,
                        resultado_visitou, resultado_demonstrou, resultado_levousuper,
                        resultado_ligougerente,filhos_visitou,observacao,
                        triplicar_vendas, cad_cliente, prod_estoque, boletos, nota_fiscal, site, frente_caixa,
                        resultado_cartaovisita,cep,numero,uf, qtd_cartoes, agendar_futuro, email, enviar_sms
                     FROM cs2.controle_comercial_visitas
                     WHERE id = $protocolo";

// echo $sql_pesquisa;exit;

$qry_pesquisa = mysql_query($sql_pesquisa, $con) or die("Erro MYSQL");
if (mysql_num_rows($qry_pesquisa) > 0) {
    while ($reg = mysql_fetch_array($qry_pesquisa)) {
        $registro ++;
        $data_cadastro = $reg ['data_cadastro'];
        $data_agendamento = $reg ['data_agendamento'];
        $hora_agendamento = substr($reg ['hora_agendamento'], 0, 5);
        $assitente_comercial = $reg ['assitente_comercial'];
        $id_consultor = $reg ['id_consultor'];
        $empresa = $reg ['empresa'];
        $endereco = $reg ['endereco'];
        $bairro = $reg ['bairro'];
        $cidade = $reg ['cidade'];
        $ponto_referencia = $reg ['ponto_referencia'];
        $fone1 = $reg ['fone1'];
        $fone2 = $reg ['fone2'];
        $responsavel = $reg ['responsavel'];
        $status_venda = $reg ['status_venda'];
        $data_venda = $reg ['data_venda'];
        $codigo_cliente = $reg ['codigo_cliente'];
        $id_assistente = $reg ['id_assistente'];
        $ocorrencia = $reg ['ocorrencia'];
        $observacao = $reg ['observacao'];
        $resultado_visitou = $reg ['resultado_visitou'] == 1 ? 'checked="checked"' : '';
        $cep = $reg ['cep'];
        $numero = $reg ['numero'];
        $uf = $reg ['uf'];
        $qtdCartoes = $reg ['qtd_cartoes'];
        $agendarFuturo = $reg ['agendar_futuro'];
        $email = $reg ['email'];
        $sms = $reg ['enviar_sms'];

        $resultado_demonstra = $reg ['resultado_demonstrou'];
        $resultado_demonstrou = $reg ['resultado_demonstrou'] == 1 ? 'checked="checked"' : '';
        $resultado_levousuper = $reg ['resultado_levousuper'] == 1 ? 'checked="checked"' : '';
        $resultado_ligougerente = $reg ['resultado_ligougerente'] == 1 ? 'checked="checked"' : '';
        $resultado_cartaovisita = $reg ['resultado_cartaovisita'] == 1 ? 'checked="checked"' : '';

        $array_filho = explode(';', $reg ['filhos_visitou']);
        $filho_cadcli = $array_filho [0] == 1 ? 'checked="checked"' : '';
        $filho_cadpro = $array_filho [1] == 1 ? 'checked="checked"' : '';
        $filho_frentecx = $array_filho [2] == 1 ? 'checked="checked"' : '';
        $filho_emis_bol = $array_filho [3] == 1 ? 'checked="checked"' : '';
        $filho_conscred = $array_filho [4] == 1 ? 'checked="checked"' : '';
        $filho_parc_deb = $array_filho [5] == 1 ? 'checked="checked"' : '';
        $filho_negativa = $array_filho [6] == 1 ? 'checked="checked"' : '';
        $filho_listamark = $array_filho [7] == 1 ? 'checked="checked"' : '';

        $triplicar_vendas = $reg ['triplicar_vendas'];
        $cad_cliente = $reg ['cad_cliente'];
        $prod_estoque = $reg ['prod_estoque'];
        $boletos = $reg ['boletos'];
        $nota_fiscal = $reg ['nota_fiscal'];
        $site = $reg ['site'];
        $frente_caixa = $reg ['frente_caixa'];
    }
}
?>

</style>


<script type="text/javascript" src="../../../inform/js/prototype.js"></script>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../js/jquery.meio.mask.js"></script>

<script language="javascript">

    function validaModal(ref) {
        var valor = $('#acesso-modal').val();
        $('#opem-editar').load('clientes/a_controle_visitas_altera_new_status.php?id=' + ref + '&acesso=' + valor);
    }

    function setAssintete() {
        var e = document.getElementById("assinantes_list");
        $('#assitente').val(e.options[e.selectedIndex].text);
    }

    function closeModal() {
        $('#opem-editar').hide();
    }

    function atualizaStatus(ref) {
        $('#opem-editar').show();
        $('#opem-editar').load('clientes/a_controle_visitas_altera_new_status.php?id=' + ref);
    }

    function apontarAgendamento() {
        $('#data_agenda').attr('style', 'border: 5px solid #f00');
        $('#hora').attr('style', 'border: 5px solid #f00');
        setTimeout(function () {
            $('#data_agenda').removeAttr('style');
            $('#hora').removeAttr('style');
        }, 3000);
    }

    function apontarContato() {
        $('#fone1').attr('style', 'border: 5px solid #f00');
        $('#fone2').attr('style', 'border: 5px solid #f00');
        setTimeout(function () {
            $('#fone1').removeAttr('style');
            $('#fone2').removeAttr('style');
        }, 3000);
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

    function inicia_cadastro(dados, dados2) {

        if (dados == 'S') {
            document.getElementById('div_data').style.display = "block";
            document.getElementById('div_codigo').style.display = "block";
            document.getElementById('div_ocorrencia').style.display = "none";
        } else if (dados == 'N') {
            document.getElementById('div_data').style.display = "block";
            document.getElementById('div_codigo').style.display = "none";
            document.getElementById('div_ocorrencia').style.display = "block";
        } else {
            document.getElementById('div_data').style.display = "none";
            document.getElementById('div_codigo').style.display = "none";
            document.getElementById('div_ocorrencia').style.display = "none";
        }

        if (dados2 == '1') {
            document.getElementById('div_demonstrou').style.display = "block";
        }
    }

    function Mascara_Hora(hora) {
        var hora01 = '';
        hora01 = hora01 + hora;
        if (hora01.length == 2) {
            hora01 = hora01 + ':';
            document.forms[0].hora.value = hora01;
        }
        if (hora01.length == 5) {
            Verifica_Hora();
        }
    }

    function Verifica_Hora() {
        hrs = (document.forms[0].hora.value.substring(0, 2));
        min = (document.forms[0].hora.value.substring(3, 5));
        estado = "";
        if ((hrs < 00) || (hrs > 23) || (min < 00) || (min > 59)) {
            estado = "errada";
        }
        if (document.forms[0].hora.value == "") {
            estado = "errada";
        }
        if (estado == "errada") {
            alert("Hora invÃ¡lida!");
            document.forms[0].hora.focus();
        }
    }

    function mostrar(obj) {

        switch (obj.id) {
            case 'divum':
                $("#data_texto").text('Data da Venda');
                document.getElementById('div_data').style.display = "block";
                document.getElementById('div_codigo').style.display = "block";
                document.getElementById('div_ocorrencia').style.display = "none";
                break;
            case 'divdois':
                $("#data_texto").text('Data da Desinteresse');
                document.getElementById('div_data').style.display = "block";
                document.getElementById('div_codigo').style.display = "none";
                document.getElementById('div_ocorrencia').style.display = "block";
                break;
            case 'divtres':
                document.getElementById('div_data').style.display = "none";
                document.getElementById('div_codigo').style.display = "none";
                document.getElementById('div_ocorrencia').style.display = "none";
                break;
        }
    }

    function mostrar_filhos(obj) {
        switch (obj.id) {
            case 'resultado_demonstrou':

                if (document.getElementById('resultado_demonstrou').checked)
                    document.getElementById('div_demonstrou').style.display = "block";
                else
                    document.getElementById('div_demonstrou').style.display = "none";
                break;

            default:
                document.getElementById('div_demonstrou').style.display = "none";
                break;
        }
    }


    function maiusculo(obj)
    {
        obj.value = obj.value.toUpperCase();
    }

    function xtrim(str) {
        return str.replace(/^\s+|\s+$/g, "");
    }

    function valida_dados2() {

        d = document.form;
        if (d.data_agenda.value == "") {
            alert("O campo Data do Agendamento deve ser preenchido!");
            d.data_agenda.focus();
            return false;
        } else if (d.id_consultor.value == "") {
            alert("O campo Consultor Comercial deve ser preenchido!");
            d.id_consultor.focus();
            return false;
        } else if (d.hora.value == "") {
            alert("O campo " + d.hora.name + " deve ser preenchido!");
            d.hora.focus();
            return false;
        } else if (d.empresa.value == "") {
            alert("O campo " + d.empresa.name + " deve ser preenchido!");
            d.empresa.focus();
            return false;
        } else if (d.endereco.value == "") {
            alert("O campo " + d.endereco.name + " deve ser preenchido!");
            d.endereco.focus();
            return false;
        } else if (d.fone1.value == "") {
            alert("O campo " + d.fone1.name + " deve ser preenchido!");
            d.fone1.focus();
            return false;
        } else if (d.responsavel.value == "") {
            alert("O campo " + d.responsavel.name + " deve ser preenchido!");
            d.responsavel.focus();
            return false;
        }
        grava_Registro2();
    }

    function grava_Registro2() {
        d = document.form;
<?php
if ($origem == '1')
    echo "d.action = 'painel.php?pagina1=clientes/a_controle_visitas_grava2.php';";
else
    echo "d.action = 'painel.php?pagina1=clientes/a_controle_visitas_grava_new.php';";
?>
        d.submit();
    }

    window.onload = function () {
        inicia_cadastro('<?= $status_venda ?>', '<?= $resultado_demonstra ?>');
    }
</script>

<div id="opem-editar" style="display:none;background:#f1f1f1;padding:2%;top:20%;width:60%;height: auto;position:fixed;">

</div>


<form name="form" method="post" action="#">
    <input type="hidden" name="b_rel_assistente"
           value="<?= $_REQUEST['b_rel_assistente'] ?>"> <input type="hidden"
           name="b_rel_consultor" value="<?= $_REQUEST['b_rel_consultor'] ?>"> <input
           type="hidden" name="b_rel_datai" value="<?= $_REQUEST['b_rel_datai'] ?>">
    <input type="hidden" name="b_rel_dataf"
           value="<?= $_REQUEST['b_rel_dataf'] ?>"> <input type="hidden"
           name="b_rel_franquia" value="<?= $_REQUEST['b_rel_franquia'] ?>">
    <table border="0" width="850px" align="center" cellpadding="0"
           cellspacing="2"
           style="border: 1px solid #D1D7DC; background-color: #FFFFFF">
        <tr>
            <td colspan="2" class="titulo"><br>SISTEMA&nbsp;&nbsp;DE&nbsp;&nbsp;CONTROLE&nbsp;&nbsp;COMERCIAL</td>
        </tr>
        <tr>
            <td width="30%" class="subtitulodireita">&nbsp;</td>
            <td width="70%" class="subtitulopequeno">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitulodireita">N&uacute;mero da Visita</td>
            <td class="subtitulopequeno"><?= $protocolo ?>
                <input type="hidden" id="protocolo" name="protocolo"
                       value="<?= $protocolo ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Data do Cadastro</td>
            <td class="subtitulopequeno"><?= $data_cadastro ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Cliente possui algo para:</td>
            <td class="subtitulopequeno">
                <table border=1>
                    <tr>
                        <td width="200">Triplicar suas Vendas</td>
                        <td><input type="radio" name="triplicar_venda"
                                   id="triplicar_venda" value="1"
                                   <?php if ($triplicar_vendas == '1') echo "checked"; ?>>Sim <input
                                   type="radio" name="triplicar_venda" id="triplicar_venda"
                                   value="2" <?php if ($triplicar_vendas == '2') echo "checked"; ?>>Nao
                        </td>
                    </tr>
                    <tr>
                        <td width="200">Cadastros de Clientes</td>
                        <td><input type="radio" name="cad_cliente" value="1"
                                   <?php if ($cad_cliente == '1') echo "checked"; ?>>Sim <input
                                   type="radio" name="cad_cliente" value="2"
                                   <?php if ($cad_cliente == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                    <tr>
                        <td width="200">Cadastro de Produto e Estoque</td>
                        <td><input type="radio" name="prod_estoque" value="1"
                                   <?php if ($prod_estoque == '1') echo "checked"; ?>>Sim <input
                                   type="radio" name="prod_estoque" value="2"
                                   <?php if ($prod_estoque == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                    <tr>
                        <td width="200">Emissão de Boletos ou Carnê Crediário</td>
                        <td><input type="radio" name="boletos" value="1"
                                   <?php if ($boletos == '1') echo "checked"; ?>>Sim <input
                                   type="radio" name="boletos" value="2"
                                   <?php if ($boletos == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                    <tr>
                        <td width="200">Frente de Caixa</td>
                        <td><input type="radio" name="frente_caixa" value="1"
                                   <?php if ($frente_caixa == '1') echo "checked"; ?>>Sim <input
                                   type="radio" name="frente_caixa" value="2"
                                   <?php if ($frente_caixa == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                    <tr>
                        <td width="200">Emissão de Nota Fiscal</td>
                        <td><input type="radio" name="nota_fiscal" value="1"
                                   <?php if ($nota_fiscal == '1') echo "checked"; ?>>Sim <input
                                   type="radio" name="nota_fiscal" value="2"
                                   <?php if ($nota_fiscal == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                    <tr>
                        <td width="200">Site na Internet</td>
                        <td><input type="radio" name="site" value="1"
                                   <?php if ($site == '1') echo "checked"; ?>>Sim <input
                                   type="radio" name="site" value="2"
                                   <?php if ($site == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Fone 1 (Fixo)</td>
            <td class="subtitulopequeno"><input name="fone1" type="text"
                                                id="fone1" size="22" maxlength="13"
                                                onFocus="this.className = 'boxover'"
                                                onKeyPress="soNumero(); formatar('##-####-####', this)"
                                                value="<?= $fone1 ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Fone 2 (Celular)</td>
            <td class="subtitulopequeno"><input type="text" name="fone2"
                                                id="fone2" size="22" maxlength="13"
                                                onFocus="this.className = 'boxover'"
                                                onKeyPress="soNumero();
                                                        formatar('##-####-####', this)"
                                                value="<?= $fone2 ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Respons&aacute;vel</td>
            <td class="subtitulopequeno"><input type="text" name="responsavel"
                                                id="responsavel" size="75" maxlength="200"
                                                onFocus="this.className = 'boxover'"
                                                onBlur="maiusculo(this); this.className = 'boxnormal'"
                                                value="<?= $responsavel ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Email</td>
            <td class="subtitulopequeno"><input type="text" name="email"
                                                id="email" onFocus="this.className = 'boxover'" value="<?= $email ?>" />
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Empresa</td>
            <td class="subtitulopequeno"><input name="empresa" type="text"
                                                id="empresa" size="75" maxlength="60"
                                                onFocus="this.className = 'boxover'"
                                                onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'"
                                                value="<?= $empresa ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">CEP</td>
            <td class="subtitulopequeno"><input name="cep_agendamento"
                                                type="text" value="<?php echo $cep ?>" id="cep_agendamento"
                                                maxlength="9" onchange="verificaEndereco()"
                                                onKeyPress="return MM_formtCep(event, this, '#####-###');" /></td>
        </tr>
        </tr>
        <tr>
            <td class="subtitulodireita">Endere&ccedil;o</td>
            <td class="subtitulopequeno"><input name="endereco" type="text"
                                                id="endereco" size="75" maxlength="100"
                                                onFocus="this.className = 'boxover'"
                                                onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'"
                                                value="<?= $endereco ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">N&ordm;</td>
            <td class="subtitulopequeno"><input name="numero" type="text"
                                                id="numero" size="75" value="<?php echo $numero ?>" maxlength="10"
                                                onFocus="this.className = 'boxover'"
                                                onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'" /></td>
        </tr>
        </tr>
        <tr>
            <td class="subtitulodireita">Bairro</td>
            <td class="subtitulopequeno"><input name="bairro" type="text"
                                                id="bairro" size="75" maxlength="60"
                                                onFocus="this.className = 'boxover'"
                                                onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'"
                                                value="<?= $bairro ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Cidade</td>
            <td class="subtitulopequeno"><input name="cidade" type="text"
                                                id="cidade" size="75" maxlength="50"
                                                onFocus="this.className = 'boxover'"
                                                onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'"
                                                value="<?= $cidade ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">UF</td>
            <td colspan="2" class="subtitulopequeno"><select name="uf" id="uf">
                    <option value="">-- selecione --</option>
                    <option value="AC" <?php echo $uf == 'AC' ? 'selected' : '' ?>>Acre</option>
                    <option value="AL" <?php echo $uf == 'AL' ? 'selected' : '' ?>>Alagoas</option>
                    <option value="AP" <?php echo $uf == 'AP' ? 'selected' : '' ?>>Amap&aacute;</option>
                    <option value="AM" <?php echo $uf == 'AM' ? 'selected' : '' ?>>Amazonas</option>
                    <option value="BA" <?php echo $uf == 'BA' ? 'selected' : '' ?>>Bahia</option>
                    <option value="CE" <?php echo $uf == 'CE' ? 'selected' : '' ?>>Cear&aacute;</option>
                    <option value="DF" <?php echo $uf == 'DF' ? 'selected' : '' ?>>Distrito
                        Federal</option>
                    <option value="ES" <?php echo $uf == 'ES' ? 'selected' : '' ?>>Esp&iacute;rito
                        Santo</option>
                    <option value="GO" <?php echo $uf == 'GO' ? 'selected' : '' ?>>Goi&aacute;s</option>
                    <option value="MA" <?php echo $uf == 'MA' ? 'selected' : '' ?>>Maranh&atilde;o</option>
                    <option value="MT" <?php echo $uf == 'MT' ? 'selected' : '' ?>>Mato
                        Grosso</option>
                    <option value="MS" <?php echo $uf == 'MS' ? 'selected' : '' ?>>Mato
                        Grosso do Sul</option>
                    <option value="MG" <?php echo $uf == 'MG' ? 'selected' : '' ?>>Minas
                        Gerais</option>
                    <option value="PA" <?php echo $uf == 'PA' ? 'selected' : '' ?>>Par&aacute;</option>
                    <option value="PB" <?php echo $uf == 'PB' ? 'selected' : '' ?>>Para&iacute;ba</option>
                    <option value="PR" <?php echo $uf == 'PR' ? 'selected' : '' ?>>Paran&aacute;</option>
                    <option value="PE" <?php echo $uf == 'PE' ? 'selected' : '' ?>>Pernambuco</option>
                    <option value="PI" <?php echo $uf == 'PI' ? 'selected' : '' ?>>Piau&iacute;</option>
                    <option value="RJ" <?php echo $uf == 'RJ' ? 'selected' : '' ?>>Rio
                        de Janeiro</option>
                    <option value="RN" <?php echo $uf == 'RN' ? 'selected' : '' ?>>Rio
                        Grande do Norte</option>
                    <option value="RS" <?php echo $uf == 'RS' ? 'selected' : '' ?>>Rio
                        Grande do Sul</option>
                    <option value="RO" <?php echo $uf == 'RO' ? 'selected' : '' ?>>Rond&ocirc;nia</option>
                    <option value="RR" <?php echo $uf == 'RR' ? 'selected' : '' ?>>Roraima</option>
                    <option value="SC" <?php echo $uf == 'SC' ? 'selected' : '' ?>>Santa
                        Catarina</option>
                    <option value="SP" <?php echo $uf == 'SP' ? 'selected' : '' ?>>S&atilde;o
                        Paulo</option>
                    <option value="SE" <?php echo $uf == 'SE' ? 'selected' : '' ?>>Sergipe</option>
                    <option value="TO" <?php echo $uf == 'TO' ? 'selected' : '' ?>>Tocantins</option>
                </select> <font color="#FF0000">(*)</font></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Ponto de Referencia</td>
            <td class="subtitulopequeno"><input name="ponto_referencia"
                                                type="text" id="ponto_referencia" size="75" maxlength="100"
                                                onFocus="this.className = 'boxover'"
                                                onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'"
                                                value="<?= $ponto_referencia ?>" /></td>
        </tr>


        <tr>
            <td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
            <td class="subtitulopequeno"><textarea
                    style="resize: none; width: 68%" name="observacao" id="observacao"
                    rows="5" onFocus="this.className = 'boxover'"
                    onBlur="maiusculo(this);
                            this.className = 'boxnormal'"><?= $observacao ?>    
                </textarea></td>
        </tr>
        <tr>
            <td class="subtitulodireita"></td>
            <td class="subtitulopequeno"><input type="checkbox"
                                                name="agendarFuturo" id="agendarFuturo"
                                                <?php echo $agendarFuturo == 'S' ? 'checked' : '' ?>>Agendar em outra
                oportunidade</td>
        </tr>


        <tr>
            <td class="subtitulodireita">Assistente Comercial</td>
            <td class="subtitulopequeno">
                <?php
                $sql_sel = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia' AND situacao IN('0','1') AND tipo_cliente = 1 ORDER BY nome";
                $qry = mysql_query($sql_sel, $con);
                echo "<select onclange='setAssintete()' id='assinantes_list' name='id_assistente' style='width:42%'>";
                $nA = '';
                while ($rs = mysql_fetch_array($qry)) {
                    if ($rs ['situacao'] == "0") {
                        $sit = "Ativo";
                    } elseif ($rs ['situacao'] == "1") {
                        $sit = "Bloqueado";
                    }
                    if ($id_assistente == $rs ['id']) {
                        $nA = $rs['nome'];
                        ?>
                <option value="<?= $rs['id'] ?>" selected><?= $rs['nome'] ?> - <?= $sit ?></option>
            <?php } else { ?>
                <option
                    value="<?= $rs['id'] ?>"><?= $rs['nome'] ?> - <?= $sit ?></option>
                    <?php
                }
            }
            echo "</select>";

            echo '<input type="hidden" name="assitente" id="assitente" value="' . $nA . '" />';
            ?>
        </td>
        </tr>

        <input type="hidden" name="data_agenda" value="<?= $data_agendamento ?>" />
        <input type="hidden" name="id_consultor" value="<?= $id_consultor ?>" /> 
        <input type="hidden" name="hora" id="hora" value="<?= $hora_agendamento ?>" />
        <input type="hidden" name="status" value="<?= $status_venda ?>"/>
        <input type="hidden" name="sms" value="<?= $sms ?>"/>

        <tr>
            <td colspan="2" class="subtitulopequeno">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="titulo">RESULTADO DO AGENDAMENTO</td>
        </tr>
        <tr>
            <td colspan="2" class="subtitulopequeno">&nbsp;</td>
        </tr> 
        <?php
        $sqlHistorico = " SELECT ch.*,cs.nome as status, ca.nome as consultor "
                . " FROM cs2.controle_comercial_visitas_historico ch "
                . " INNER JOIN cs2.controle_comercial_visitas_status cs ON cs.id = ch.status "
                . " INNER JOIN cs2.consultores_assistente ca ON ca.id = ch.id_consultor "
                . " WHERE ch.id_visita = {$protocolo} ORDER BY ch.data_visita DESC";
        $historico = mysql_query($sqlHistorico, $con) or die("Erro MYSQL");
        if ($historico) {
            echo ''
            . '<tr>'
            . '     <td colspan="2">'
            . '         <table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 1px solid #D1D7DC; ">'
            . '             <thead>'
            . '                 <tr>'
            . '                     <td class="subtitulodireita">#</td>'
            . '                     <td class="subtitulodireita">Data VIsita</td>'
            . '                     <td class="subtitulodireita">Hora</td>'
            . '                     <td class="subtitulodireita">Consultor</td>'
            . '                     <td class="subtitulodireita">Status</td>'
            . '                 </tr>'
            . '             </thead>'
            . '             <tbody>';
            while ($dados = mysql_fetch_array($historico)) {
                echo '              <tr>'
                . '                     <td class="subtitulopequeno">' . $dados['id'] . '</td>'
                . '                     <td class="subtitulopequeno">' . date('d/m/Y', strtotime($dados['data_visita'])) . '</td>'
                . '                     <td class="subtitulopequeno">' . str_replace(':00:0', '', $dados['hora']) . '</td>'
                . '                     <td class="subtitulopequeno">' . $dados['consultor'] . '</td>'
                . '                     <td class="subtitulopequeno"><a onclick="atualizaStatus(' . $dados['id'] . ')">' . $dados['status'] . '</a></td>'
                . '                 </tr>';

                $sqlA = ""
                        . " SELECT chs.* "
                        . " FROM cs2.controle_comercial_visitas_historico_status chs  "
                        . " WHERE chs.id_visita = " . $dados['id'] . " AND chs.id_agendamento = " . $dados['id_visita'] . ' ORDER BY id DESC';
                $qryPesquisaA = mysql_query($sqlA, $con) or die("Erro MYSQL");
                while ($dadosA = mysql_fetch_array($qryPesquisaA)) {
                    $justificativa = ' -- ';
                    if ($dadosA['id_justificativa'] > 0) {
                        $QueryJustificativa = mysql_query('SELECT nome FROM cs2.controle_comercial_visitas_justificativa WHERE id = ' . $dadosA['id_justificativa'], $con) or die("Erro MYSQL");
                        $Jh = mysql_fetch_array($QueryJustificativa);
                        $justificativa = $Jh['nome'];
                    }
                    $status = ' status ';
                    if ($dadosA['id_status'] > 0) {
                        $QueryStatus = mysql_query('SELECT nome FROM cs2.controle_comercial_visitas_status WHERE id = ' . $dadosA['id_status'], $con) or die("Erro MYSQL");
                        $St = mysql_fetch_array($QueryStatus);
                        $status = $St['nome'];
                    }
                    echo '              <tr>'
                    . '                     <td class="subtitulopequeno">Atualizado</td>'
                    . '                     <td class="subtitulopequeno">Data alteração: ' . date('d/m/Y', strtotime($dadosA['created_at'])) . '</td>'
                    . '                     <td class="subtitulopequeno">' . date('H:i:s', strtotime($dadosA['created_at'])) . '</td>'
                    . '                     <td class="subtitulopequeno">' . $justificativa . '</td>'
                    . '                     <td class="subtitulopequeno">' . $status . '</td>'
                    . '                 </tr>';
                }

                echo '<tr><td colspan="5"><br/></td></tr>';
            }
            echo '             </tbody>'
            . '         </table>'
            . '     </td>'
            . '</tr>';
        }
        ?>
        <tr>
            <td colspan="2" class="subtitulopequeno"><br/><br/></td>
        </tr>

        <tr>
            <td colspan="2">
                <div id="div_data" style="display: none">
                    <table border="0" width="850px" align="center" cellpadding="0"
                           cellspacing="2"
                           style="border: 1px solid #D1D7DC; background-color: #FFFFFF">
                        <tr>
                            <td width="30%" class="subtitulodireita"><span id='data_texto'></span>
                            </td>
                            <td width="70%" class="subtitulopequeno"><input type="text"
                                                                            name="data_venda" id="data_venda" value="<?= $data_venda ?>"
                                                                            onFocus="this.className = 'boxover'"
                                                                            onKeyPress="return MM_formtCep(event, this, '##/##/####');"
                                                                            onBlur="this.className = 'boxnormal'" maxlength="10" /></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="div_codigo" style="display: none">
                    <table border="0" width="850px" align="center" cellpadding="0"
                           cellspacing="2"
                           style="border: 1px solid #D1D7DC; background-color: #FFFFFF">
                        <tr>
                            <td class="subtitulodireita" width="30%">C&oacute;digo do Cliente</td>
                            <td class="subtitulopequeno" width="70%"><input type="text"
                                                                            name="codigo_cliente" id="codigo_cliente"
                                                                            value="<?= $codigo_cliente ?>" onFocus="this.className = 'boxover'"
                                                                            onKeyPress="soNumero();" onBlur="this.className = 'boxnormal'" />
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="div_ocorrencia" style="display: none">
                    <table border="0" width="850px" align="center" cellpadding="0"
                           cellspacing="2"
                           style="border: 1px solid #D1D7DC; background-color: #FFFFFF">
                        <tr>
                            <td class="subtitulodireita" width="30%">Motivo do N&Atilde;O
                                INTERESSE</td>
                            <td class="subtitulopequeno" width="70%" style="text-align: left">
                                <textarea name="ocorrencia" wrap=physical style="width: 99%"
                                          rows="3"
                                          onKeyDown="textCounter(this.form.ocorrencia, this.form.remLen, 160);"
                                          onKeyUp="textCounter(this.form.ocorrencia, this.form.remLen, 160);"><?= $ocorrencia ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>

        <?php
        $display = '"';
//	echo 'ID FRANQUIA: ' . $id_franquia;
        if ($idFranquiaAux != 163 && $idFranquiaAux != 4 && $idFranquiaAux != 1) {
            $display = 'style="display: none"';
        }
        ?>

        <tr <?php echo $display; ?>>
            <td colspan="2" class="titulo">RESULTADO DA CONFER&Ecirc;NCIA</td>
        </tr>
        <tr <?php echo $display; ?>>
            <td colspan="2" class="subtitulopequeno">&nbsp;</td>
        </tr>
        <tr <?php echo $display; ?>>
            <td colspan="2" class="subtitulopequeno">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
                    type="checkbox" name="resultado_visitou" id="resultado_visitou"
                    <?php echo $resultado_visitou; ?>> Visitou no Hor&aacute;rio
                Agendado ?<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
                    type="checkbox" name="resultado_demonstrou"
                    id="resultado_demonstrou" onclick="mostrar_filhos(this)"
                    <?php echo $resultado_demonstrou; ?>> Demonstrou no Computador todas
                as Solu&ccedil;&otilde;es ?<br>
                <div id="div_demonstrou" style="display: none">
                    <div style='margin-left: 50px;'>
                        <input type="checkbox" name="filho_cadcli"
                               <?php echo $filho_cadcli; ?>> Cadastro de Clientes
                    </div>
                    <div style='margin-left: 50px;'>
                        <input type="checkbox" name="filho_cadpro"
                               <?php echo $filho_cadpro; ?>> Cadastro de Produtos/Estoque<br>
                    </div>
                    <div style='margin-left: 50px;'>
                        <input type="checkbox" name="filho_frentecx"
                               <?php echo $filho_frentecx; ?>> Frente de Caixa<br>
                    </div>
                    <div style='margin-left: 50px;'>
                        <input type="checkbox" name="filho_emis_bol"
                               <?php echo $filho_emis_bol; ?>> Emissão de Boletos<br>
                    </div>
                    <div style='margin-left: 50px;'>
                        <input type="checkbox" name="filho_conscred"
                               <?php echo $filho_conscred; ?>> Consulta de Crédito<br>
                    </div>
                    <div style='margin-left: 50px;'>
                        <input type="checkbox" name="filho_parc_deb"
                               <?php echo $filho_parc_deb; ?>> Parcelamento de Débito para
                        devedores<br>
                    </div>
                    <div style='margin-left: 50px;'>
                        <input type="checkbox" name="filho_negativa"
                               <?php echo $filho_negativa; ?>> Negativação de Devedores<br>
                    </div>
                    <div style='margin-left: 50px;'>
                        <input type="checkbox" name="filho_listamark"
                               <?php echo $filho_listamark; ?>> Lista Marketing - Clientes da
                        Rua ou Bairro
                    </div>
                </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
                    type="checkbox" name="resultado_levousuper"
                    id="resultado_levousuper" <?php echo $resultado_levousuper; ?>>
                Levou a Super Pasta Preta - Equipamentos ?<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
                    type="checkbox" name="resultado_ligougerente"
                    id="resultado_ligougerente" <?php echo $resultado_ligougerente; ?>>
                Ligou para o Gerente ? <br> <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
                    type="checkbox" name="resultado_cartaovisita"
                    id="resultado_cartaovisita" <?php echo $resultado_cartaovisita; ?>><font
                    color="red"> Cartões de Visita dos Vizinhos</font> <input
                    type="number" name="iptQtdCartoes" id="iptQtdCartoes"
                    value="<?php echo $qtdCartoes ?>"><br> <br>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input name="Gravar" type="button"
                                                  value="   Gravar  " onClick="valida_dados2();" /> <input
                                                  type='button' value='       Voltar        ' style='cursor: pointer'
                                                  onClick="document.location = 'painel.php?pagina1=clientes/a_controle_visitas2_new.php'" />
                <input type='button' value='       Fechar        '
                       style='cursor: pointer' onClick="window.close()" /></td>
        </tr>
    </table>
</form>