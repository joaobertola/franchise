<?php
error_reporting(0);
?>
<style type="text/css">
    h1 {
        font-size: 140%;
    }

    form {
        margin: 30px 50px 0;
    }

    form input,
    select {
        font-family: Arial;
        font-size: 8pt;
    }

    form input#numero,
    form input#uf,
    form input#cep {
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

$id_franquia = $_REQUEST['b_rel_franquia'];
$idFranquiaAux = $_SESSION['id'];

if ($id_franquia == 4 || $id_franquia == 5 || $id_franquia == 163 || $id_franquia == 247)
    $id_franquia = 1;

$protocolo = $_REQUEST['protocolo'];
$origem = $_REQUEST['origem'];

$sql = "SELECT id, nome FROM cs2.concorrente ORDER BY nome";
$query = mysql_query($sql, $con) or die(mysql_error());
$result = array();


while ($row = mysql_fetch_array($query)) {
    $result[] = $row;
}

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
                        resultado_cartaovisita,cep,numero,uf, qtd_cartoes, agendar_futuro, email, enviar_sms, paralelo_sistemas, resultado_mousepad, ct.id as id_concorrente, ct.nome as nome_concorrente,
                        cm.vizinhos
                     FROM cs2.controle_comercial_visitas cm
                     LEFT JOIN cs2.concorrente ct ON ct.id = cm.id_concorrente 
                     WHERE cm.id = $protocolo";

// echo $sql_pesquisa;exit;

$qry_pesquisa = mysql_query($sql_pesquisa, $con) or die("Erro MYSQL");
if (mysql_num_rows($qry_pesquisa) > 0) {
    while ($reg = mysql_fetch_array($qry_pesquisa)) {
        $registro++;
        $data_cadastro = $reg['data_cadastro'];
        $data_agendamento = $reg['data_agendamento'];
        $hora_agendamento = substr($reg['hora_agendamento'], 0, 5);
        $assitente_comercial = $reg['assitente_comercial'];
        $id_consultor = $reg['id_consultor'];
        $empresa = $reg['empresa'];
        $endereco = $reg['endereco'];
        $bairro = $reg['bairro'];
        $cidade = $reg['cidade'];
        $ponto_referencia = $reg['ponto_referencia'];
        $fone1 = $reg['fone1'];
        $fone2 = $reg['fone2'];
        $responsavel = $reg['responsavel'];
        $status_venda = $reg['status_venda'];
        $data_venda = $reg['data_venda'];
        $codigo_cliente = $reg['codigo_cliente'];
        $id_assistente = $reg['id_assistente'];
        $ocorrencia = $reg['ocorrencia'];
        $observacao = $reg['observacao'];
        $resultado_visitou = $reg['resultado_visitou'] == 1 ? 'checked="checked"' : '';
        $cep = $reg['cep'];
        $numero = $reg['numero'];
        $uf = $reg['uf'];
        $qtdCartoes = $reg['qtd_cartoes'];
        $agendarFuturo = $reg['agendar_futuro'];
        $email = $reg['email'];
        $sms = $reg['enviar_sms'];
        $vizinhos = $reg['vizinhos'];

        $resultado_demonstra = $reg['resultado_demonstrou'];
        $resultado_demonstrou = $reg['resultado_demonstrou'] == 1 ? 'checked="checked"' : '';
        $resultado_levousuper = $reg['resultado_levousuper'] == 1 ? 'checked="checked"' : '';
        $resultado_ligougerente = $reg['resultado_ligougerente'] == 1 ? 'checked="checked"' : '';
        $resultado_cartaovisita = $reg['resultado_cartaovisita'] == 1 ? 'checked="checked"' : '';
        $resultado_paralelo = $reg['paralelo_sistemas'] == 1 ? 'checked="checked"' : '';
        $resultado_mousepad = $reg['resultado_mousepad'] == 1 ? 'checked="checked"' : '';
        $id_concorrente     = $reg['id_concorrente'];
        $nome_concorrente    = $reg['nome_concorrente'];

        $array_filho = explode(';', $reg['filhos_visitou']);
        $filho_cadcli = $array_filho[0] == 1 ? 'checked="checked"' : '';
        $filho_cadpro = $array_filho[1] == 1 ? 'checked="checked"' : '';
        $filho_frentecx = $array_filho[2] == 1 ? 'checked="checked"' : '';
        $filho_emis_bol = $array_filho[3] == 1 ? 'checked="checked"' : '';
        $filho_conscred = $array_filho[4] == 1 ? 'checked="checked"' : '';
        $filho_parc_deb = $array_filho[5] == 1 ? 'checked="checked"' : '';
        $filho_negativa = $array_filho[6] == 1 ? 'checked="checked"' : '';
        $filho_listamark = $array_filho[7] == 1 ? 'checked="checked"' : '';

        $triplicar_vendas = $reg['triplicar_vendas'];
        $cad_cliente = $reg['cad_cliente'];
        $prod_estoque = $reg['prod_estoque'];
        $boletos = $reg['boletos'];
        $nota_fiscal = $reg['nota_fiscal'];
        $site = $reg['site'];
        $frente_caixa = $reg['frente_caixa'];
    }
}

$fone1Cad = preg_replace("/[^0-9]/", "", $fone1);
$fone2Cad = preg_replace("/[^0-9]/", "", $fone2);

if (trim($fone2Cad) == '')
    $fone2Cad = $fone1Cad;

$sql_cad = "SELECT
                    codloja
            FROM cs2.cadastro";

$sql_cad .= " WHERE (fone = '$fone1Cad' OR fone = '$fone2Cad') AND sitcli = 0 AND id_franquia != 2";

$sql_cad .= " ORDER BY codLoja DESC LIMIT 1";
$sql_sel .= " ORDER BY id DESC LIMIT 1";

$qry_cad = mysql_query($sql_cad, $con);
$codloja = mysql_fetch_array($qry_cad);

$sql_login = "SELECT
                    login
            FROM base_web_control.webc_usuario
            WHERE id_cadastro = $codloja[0]
            ORDER BY id DESC LIMIT 1";

$qry_login = mysql_query($sql_login, $con);

$login = mysql_fetch_array($qry_login);

?>

</style>


<script type="text/javascript" src="../../../inform/js/prototype.js"></script>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../js/jquery.meio.mask.js"></script>

<script language="javascript">
    function apontarAgendamento() {
        $('#data_agenda').attr('style', 'border: 5px solid #f00');
        $('#hora').attr('style', 'border: 5px solid #f00');
        setTimeout(function() {
            $('#data_agenda').removeAttr('style');
            $('#hora').removeAttr('style');
        }, 3000);
    }

    function apontarContato() {
        $('#fone1').attr('style', 'border: 5px solid #f00');
        $('#fone2').attr('style', 'border: 5px solid #f00');
        setTimeout(function() {
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


    function maiusculo(obj) {
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
            echo "d.action = 'painel.php?pagina1=clientes/a_controle_visitas_grava.php';";
        ?>
        d.submit();
    }

    window.onload = function() {
        inicia_cadastro('<?= $status_venda ?>', '<?= $resultado_demonstra ?>');
    }
</script>

<form name="form" method="post" action="#">
    <input type="hidden" name="b_rel_assistente" value="<?= $_REQUEST['b_rel_assistente'] ?>"> <input type="hidden" name="b_rel_consultor" value="<?= $_REQUEST['b_rel_consultor'] ?>"> <input type="hidden" name="b_rel_datai" value="<?= $_REQUEST['b_rel_datai'] ?>">
    <input type="hidden" name="b_rel_dataf" value="<?= $_REQUEST['b_rel_dataf'] ?>"> <input type="hidden" name="b_rel_franquia" value="<?= $_REQUEST['b_rel_franquia'] ?>">
    <table border="0" width="850px" align="center" cellpadding="0" cellspacing="2" style="border: 1px solid #D1D7DC; background-color: #FFFFFF">
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
                <input type="hidden" id="protocolo" name="protocolo" value="<?= $protocolo ?>" /></td>
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
                        <td><input type="radio" name="triplicar_venda" id="triplicar_venda" value="1" <?php if ($triplicar_vendas == '1') echo "checked"; ?>>Sim <input type="radio" name="triplicar_venda" id="triplicar_venda" value="2" <?php if ($triplicar_vendas == '2') echo "checked"; ?>>Nao
                        </td>
                    </tr>
                    <tr>
                        <td width="200">Cadastros de Clientes</td>
                        <td><input type="radio" name="cad_cliente" value="1" <?php if ($cad_cliente == '1') echo "checked"; ?>>Sim <input type="radio" name="cad_cliente" value="2" <?php if ($cad_cliente == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                    <tr>
                        <td width="200">Cadastro de Produto e Estoque</td>
                        <td><input type="radio" name="prod_estoque" value="1" <?php if ($prod_estoque == '1') echo "checked"; ?>>Sim <input type="radio" name="prod_estoque" value="2" <?php if ($prod_estoque == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                    <tr>
                        <td width="200">Emissão de Boletos ou Carnê Crediário</td>
                        <td><input type="radio" name="boletos" value="1" <?php if ($boletos == '1') echo "checked"; ?>>Sim <input type="radio" name="boletos" value="2" <?php if ($boletos == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                    <tr>
                        <td width="200">Frente de Caixa</td>
                        <td><input type="radio" name="frente_caixa" value="1" <?php if ($frente_caixa == '1') echo "checked"; ?>>Sim <input type="radio" name="frente_caixa" value="2" <?php if ($frente_caixa == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                    <tr>
                        <td width="200">Emissão de Nota Fiscal</td>
                        <td><input type="radio" name="nota_fiscal" value="1" <?php if ($nota_fiscal == '1') echo "checked"; ?>>Sim <input type="radio" name="nota_fiscal" value="2" <?php if ($nota_fiscal == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                    <tr>
                        <td width="200">Site na Internet</td>
                        <td><input type="radio" name="site" value="1" <?php if ($site == '1') echo "checked"; ?>>Sim <input type="radio" name="site" value="2" <?php if ($site == '2') echo "checked"; ?>>Nao</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Fone 1 (Fixo)</td>
            <td class="subtitulopequeno"><input style="float: left;" name="fone1" type="text" id="fone1" size="22" maxlength="13" onFocus="this.className = 'boxover'" onKeyPress="soNumero(); formatar('##-####-####', this)" value="<?= $fone1 ?>" />
                <?php if (!empty($login[0])) : ?>
                    <p style="color: red; float: left; margin: 0px;"><?php echo "Cliente já afiliado: " . $login[0] ?></p>
                <?php endif ?>
            </td>


        </tr>
        <tr>
            <td class="subtitulodireita">Fone 2 (Celular)</td>
            <td class="subtitulopequeno"><input type="text" name="fone2" id="fone2" size="22" maxlength="13" onFocus="this.className = 'boxover'" onKeyPress="soNumero();
                                                        formatar('##-####-####', this)" value="<?= $fone2 ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Respons&aacute;vel</td>
            <td class="subtitulopequeno"><input type="text" name="responsavel" id="responsavel" size="75" maxlength="200" onFocus="this.className = 'boxover'" onBlur="maiusculo(this); this.className = 'boxnormal'" value="<?= $responsavel ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Email</td>
            <td class="subtitulopequeno"><input type="text" name="email" id="email" onFocus="this.className = 'boxover'" value="<?= $email ?>" />
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Empresa</td>
            <td class="subtitulopequeno"><input name="empresa" type="text" id="empresa" size="75" maxlength="60" onFocus="this.className = 'boxover'" onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'" value="<?= $empresa ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">CEP</td>
            <td class="subtitulopequeno"><input name="cep_agendamento" type="text" value="<?php echo $cep ?>" id="cep_agendamento" maxlength="9" onchange="verificaEndereco()" onKeyPress="return MM_formtCep(event, this, '#####-###');" /></td>
        </tr>
        </tr>
        <tr>
            <td class="subtitulodireita">Endere&ccedil;o</td>
            <td class="subtitulopequeno"><input name="endereco" type="text" id="endereco" size="75" maxlength="100" onFocus="this.className = 'boxover'" onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'" value="<?= $endereco ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">N&ordm;</td>
            <td class="subtitulopequeno"><input name="numero" type="text" id="numero" size="75" value="<?php echo $numero ?>" maxlength="10" onFocus="this.className = 'boxover'" onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'" /></td>
        </tr>
        </tr>
        <tr>
            <td class="subtitulodireita">Bairro</td>
            <td class="subtitulopequeno"><input name="bairro" type="text" id="bairro" size="75" maxlength="60" onFocus="this.className = 'boxover'" onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'" value="<?= $bairro ?>" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Cidade</td>
            <td class="subtitulopequeno"><input name="cidade" type="text" id="cidade" size="75" maxlength="50" onFocus="this.className = 'boxover'" onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'" value="<?= $cidade ?>" /></td>
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
                </select>
                <font color="#FF0000">(*)</font>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Ponto de Referencia</td>
            <td class="subtitulopequeno"><input name="ponto_referencia" type="text" id="ponto_referencia" size="75" maxlength="100" onFocus="this.className = 'boxover'" onBlur="maiusculo(this);
                                                        this.className = 'boxnormal'" value="<?= $ponto_referencia ?>" /></td>
        </tr>


        <tr>
            <td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
            <td class="subtitulopequeno"><textarea style="resize: none; width: 68%" name="observacao" id="observacao" rows="5" onFocus="this.className = 'boxover'" onBlur="maiusculo(this);
                            this.className = 'boxnormal'"><?= $observacao ?>
                </textarea></td>
        </tr>
        <tr>
            <td class="subtitulodireita"></td>
            <td class="subtitulopequeno"><input type="checkbox" name="agendarFuturo" id="agendarFuturo" <?php echo $agendarFuturo == 'S' ? 'checked' : '' ?>>Agendar em outra
                oportunidade</td>
        </tr>

        <tr>
            <td class="subtitulodireita">Assistente Comercial</td>
            <td class="subtitulopequeno">
                <?php
                $sql_sel = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia' AND situacao IN('0','1') AND tipo_cliente = 1 ORDER BY nome";
                $qry = mysql_query($sql_sel, $con);
                echo "<select name='id_assistente_grava' style='width:42%'>";
                echo "<option value='0'>Selecione</option>";
                while ($rs = mysql_fetch_array($qry)) {
                    if ($rs['situacao'] == "0") {
                        $sit = "Ativo";
                    } elseif ($rs['situacao'] == "1") {
                        $sit = "Bloqueado";
                    }
                    if ($id_assistente == $rs['id']) {
                ?>
                        <option value="<?= $rs['id'] ?>" selected><?= $rs['nome'] ?> - <?= $sit ?></option>
                    <?php } else { ?>
                        <option value="<?= $rs['id'] ?>"><?= $rs['nome'] ?> - <?= $sit ?></option>
                <?php
                    }
                }
                echo "</select>"
                ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Data do Agendamento</td>
            <td class="subtitulopequeno">
                <input name="data_agenda" type="text" id="data_agenda" size="15" maxlength="10" onFocus="this.className = 'boxover'" onKeyPress="return MM_formtCep(event, this, '##/##/####');" onBlur="this.className = 'boxnormal'" value="<?= $data_agendamento ?>" />
                <input type="checkbox" name="flagVizinhos" id="flagVizinhos" <?php echo $vizinhos == 'on' ? 'checked' : '' ?> />Vizinhos
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Consultor Comercial</td>
            <td class="subtitulopequeno">
                <?php
                $sql_sel = "SELECT * FROM cs2.consultores_assistente
            WHERE id_franquia = '$id_franquia' AND situacao IN('0','1') AND tipo_cliente = 0
            ORDER BY nome";
                $qry = mysql_query($sql_sel, $con);
                echo "<select name='id_consultor' style='width:42%'>";
                echo "  <option value='0' selected>SELECIONE O CONSULTOR</option>";
                while ($rs = mysql_fetch_array($qry)) {
                    if ($rs['situacao'] == "0") {
                        $sit = "Ativo";
                    } elseif ($rs['situacao'] == "1") {
                        $sit = "Bloqueado";
                    }
                    if ($id_consultor == $rs['id']) {
                ?>
                        <option value="<?= $rs['id'] ?>" selected><?= $rs['nome'] ?> - <?= $sit ?></option>
                    <?php } else { ?>
                        <option value="<?= $rs['id'] ?>"><?= $rs['nome'] ?> - <?= $sit ?></option>
                <?php
                    }
                }
                echo "</select>";
                ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Hora</td>
            <td class="subtitulopequeno"><input name="hora" type="text" id="hora" size="10" maxlength="5" onFocus="this.className = 'boxover'" onKeyPress="soNumero();" OnKeyUp="Mascara_Hora(this.value)" value="<?= $hora_agendamento ?>" /></td>

            <?php if ($idFranquiaAux == 163 || $idFranquiaAux == 4) { ?>




        <tr>
            <td class="subtitulodireita">Enviar SMS</td>
            <td class="subtitulopequeno"><input name="sms" type="radio" id="sms" checked value="S" <?php echo $sms == 'S' ? 'checked' : '' ?> /> Sim
                <input name="sms" type="radio" id="sms" value="N" <?php echo $sms == 'S' ? '' : 'checked' ?> /> Não</td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="2" class="subtitulopequeno">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="titulo">RESULTADO DO AGENDAMENTO</td>
    </tr>
    <tr>
        <td colspan="2" class="subtitulopequeno">&nbsp;</td>
    </tr>
    <tr>
        <td class="subtitulodireita">Status</td>
        <td class="subtitulopequeno">
            <label style="cursor: pointer" id="datafinalizacao"> <input type="radio" name="status" value="S" id='divum' onclick="mostrar(this)" <?php if ($status_venda == "S") { ?> checked <?php } ?> /> &nbsp;VENDA REALIZADA</label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label style="cursor: pointer"> <input type="radio" name="status" value="N" id='divdois' onclick="mostrar(this)" <?php if ($status_venda == "N") { ?> checked <?php } ?> />&nbsp;N&Atilde;O TEM INTERESSE </label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label style="cursor: pointer"> <input type="radio" name="status" value="P" id="divtres" onclick="mostrar(this)" <?php if ($status_venda == "P") { ?> checked <?php } ?> />&nbsp;VISITA PENDENTE </label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label style="cursor: pointer"> <input type="radio" name="status" value="R" id="divquatro" onclick="apontarAgendamento()" <?php if ($status_venda == "R") { ?> checked <?php } ?> />&nbsp;REAGENDAMENTO </label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </br>
            <label style="cursor: pointer"> <input type="radio" name="status" value="RL" id="divcinco" onclick="apontarContato()" <?php if ($status_venda == "RL") { ?> checked <?php } ?> />&nbsp;RETORNAR LIGAÇÃO</label>
            </br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="div_data" style="display: none">
                <table border="0" width="850px" align="center" cellpadding="0" cellspacing="2" style="border: 1px solid #D1D7DC; background-color: #FFFFFF">
                    <tr>
                        <td width="30%" class="subtitulodireita"><span id='data_texto'></span>
                        </td>
                        <td width="70%" class="subtitulopequeno"><input type="text" name="data_venda" id="data_venda" value="<?= $data_venda ?>" onFocus="this.className = 'boxover'" onKeyPress="return MM_formtCep(event, this, '##/##/####');" onBlur="this.className = 'boxnormal'" maxlength="10" /></td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="div_codigo" style="display: none">
                <table border="0" width="850px" align="center" cellpadding="0" cellspacing="2" style="border: 1px solid #D1D7DC; background-color: #FFFFFF">
                    <tr>
                        <td class="subtitulodireita" width="30%">C&oacute;digo do Cliente</td>
                        <td class="subtitulopequeno" width="70%"><input type="text" name="codigo_cliente" id="codigo_cliente" value="<?= $codigo_cliente ?>" onFocus="this.className = 'boxover'" onKeyPress="soNumero();" onBlur="this.className = 'boxnormal'" />
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="div_ocorrencia" style="display: none">
                <table border="0" width="850px" align="center" cellpadding="0" cellspacing="2" style="border: 1px solid #D1D7DC; background-color: #FFFFFF">
                    <tr>
                        <td class="subtitulodireita" width="30%">Motivo do N&Atilde;O
                            INTERESSE</td>
                        <td class="subtitulopequeno" width="70%" style="text-align: left">
                            <textarea name="ocorrencia" wrap=physical style="width: 99%" rows="3" onKeyDown="textCounter(this.form.ocorrencia, this.form.remLen, 160);" onKeyUp="textCounter(this.form.ocorrencia, this.form.remLen, 160);"><?= $ocorrencia ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>

    <?php // AQUI ANIMAL
    $display = '"';
    $senha_verificada = false;
    //  echo 'ID FRANQUIA: ' . $id_franquia;
    if ($idFranquiaAux != 163 && $idFranquiaAux != 4 && $idFranquiaAux != 1 || !$senha_verificada) {
        $display = 'style="display: none"';
    }
    ?>

    <tr>
        <td colspan="2" class="titulo">RESULTADO DA CONFER&Ecirc;NCIA</td>
    </tr>
    <tr id="btn_formulario_conferencia">
        <td>
            <a href="javascript:void(0);" onclick="openFormSenha();" style="background:#eee;color:#000;border:1px #ccc solid;padding:1px;">Alterar</a>
        </td>
    </tr>
    <tr>
        <td id="formulario_verifica_senha" style="display:none;">
            <form action="a_controle_visitas_altera.php" method="POST">
                <label for="senha_digitada">Digite sua senha</label>
                <input type="password" name="senha_digitada" id="senha_digitada">
                <input type="submit" value="OK">
            </form>
        </td>
    </tr>
    <tr id="senha_invalida" style="display:none;color:red;">
        <td>Senha inválida!</td>
    </tr>
    <tr <?php echo $display; ?> class="exibe_formulario_conferencia">
        <td colspan="2" class="subtitulopequeno">&nbsp;</td>
    </tr>
    <tr <?php echo $display; ?> class="exibe_formulario_conferencia">
        <td colspan="2" class="subtitulopequeno">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="resultado_visitou" id="resultado_visitou" <?php echo $resultado_visitou; ?>> Visitou no Hor&aacute;rio
            Agendado ?<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="paralelo_sistemas" id="paralelo_sistemas" <?php echo $resultado_paralelo; ?>> Paralelo Entre Sistemas ?<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="resultado_demonstrou" id="resultado_demonstrou" onclick="mostrar_filhos(this)" <?php echo $resultado_demonstrou; ?>> Demonstrou no Computador todas
            as Solu&ccedil;&otilde;es ?<br>
            <input type="hidden" name="id_funcionario_conferencia" id="id_funcionario_conferencia">
            <div id="div_demonstrou" style="display: none">
                <div style='margin-left: 50px;'>
                    <input type="checkbox" name="filho_cadcli" <?php echo $filho_cadcli; ?>> Cadastro de Clientes
                </div>
                <div style='margin-left: 50px;'>
                    <input type="checkbox" name="filho_cadpro" <?php echo $filho_cadpro; ?>> Cadastro de Produtos/Estoque<br>
                </div>
                <div style='margin-left: 50px;'>
                    <input type="checkbox" name="filho_frentecx" <?php echo $filho_frentecx; ?>> Frente de Caixa<br>
                </div>
                <div style='margin-left: 50px;'>
                    <input type="checkbox" name="filho_emis_bol" <?php echo $filho_emis_bol; ?>> Emissão de Boletos<br>
                </div>
                <div style='margin-left: 50px;'>
                    <input type="checkbox" name="filho_conscred" <?php echo $filho_conscred; ?>> Consulta de Crédito<br>
                </div>
                <div style='margin-left: 50px;'>
                    <input type="checkbox" name="filho_parc_deb" <?php echo $filho_parc_deb; ?>> Parcelamento de Débito para
                    devedores<br>
                </div>
                <div style='margin-left: 50px;'>
                    <input type="checkbox" name="filho_negativa" <?php echo $filho_negativa; ?>> Negativação de Devedores<br>
                </div>
                <div style='margin-left: 50px;'>
                    <input type="checkbox" name="filho_listamark" <?php echo $filho_listamark; ?>> Lista Marketing - Clientes da
                    Rua ou Bairro
                </div>
            </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="resultado_levousuper" id="resultado_levousuper" <?php echo $resultado_levousuper; ?>>
            Levou a Super Pasta Preta - Equipamentos ?<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="resultado_ligougerente" id="resultado_ligougerente" <?php echo $resultado_ligougerente; ?>>
            Ligou para o Gerente ? <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="resultado_mousepad" id="resultado_mousepad" <?php echo $resultado_mousepad; ?>>
            Mouse PAD? <br> <br>
            <label style="padding-left: 30px;">Concorrente: </label>
            <select name="id_concorrente">
                <option value="0">Sem Sistema</option>
                <?php foreach ($result as $value) : ?>
                    <option value="<?php echo $value['id'] ?>" <?php echo ($value['id'] == $id_concorrente) ? 'selected="selected"' : "" ?>><?php echo $value['nome'] ?></option>
                <?php endforeach ?>
            </select>
            <a href="painel.php?pagina1=clientes/cadastrar_concorrentes.php" style="padding-left: 10px">Adicionar Concorrente</a>
            <br> <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="resultado_cartaovisita" id="resultado_cartaovisita" <?php echo $resultado_cartaovisita; ?>>
            <font color="red"> Cartões de Visita dos Vizinhos</font> <input type="number" name="iptQtdCartoes" id="iptQtdCartoes" value="<?php echo $qtdCartoes ?>"><br> <br>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center"><input name="Gravar" type="button" value="   Gravar  " onClick="valida_dados2();" /> <input type='button' value='       Voltar        ' style='cursor: pointer' onClick="document.location = 'painel.php?pagina1=clientes/a_controle_visitas3.php'" />
            <input type='button' value='       Fechar        ' style='cursor: pointer' onClick="window.close()" /></td>
    </tr>
    </table>
</form>

<script>
    function openFormSenha() {
        $('#formulario_verifica_senha').css('display', 'block');
    }
</script>

<?php

// AQUI COMEÇA VALIDAÇÃO SENHA
$servername = '10.2.2.3';
$username = "csinform";
$password = "inform4416#scf";
$dbname = 'cs2';

if (!$con = mysql_connect($servername, $username, $password)) {
    echo 'Não foi possível conectar ao mysql';
    exit;
}

if (!mysql_select_db($dbname, $con)) {
    echo 'Não foi possível selecionar o banco de dados';
    exit;
}

if (isset($_POST['senha_digitada'])) {

    $id = '';

    // Create connection
    $senha_digitada = $_POST['senha_digitada'];
    $sql = "SELECT * FROM cs2.funcionario WHERE senha = '{$_POST['senha_digitada']}' and ativo = 'S'";
    $query = mysql_query($sql, $con) or die('erro');
    while ($row = mysql_fetch_assoc($query)) {
        $id = $row['id'];
    }

    if($id > 0) {

?>
    <script>
        $('.exibe_formulario_conferencia').show();
        $('#btn_formulario_conferencia').hide();
        $('#senha_invalida').hide();
        $('#id_funcionario_conferencia').val(<?=$id?>);
    </script>
<?php
    } else { 
?>        
    <script>
        $('#formulario_verifica_senha').css('display', 'block');
        $('#senha_invalida').show();
    </script>
<?php        
    } } // AQUI TERMINA VALIDAÇÃO SENHA
?>

<!-- EXIBE TABELA COM OS DADOS DOS ÚLTIMOS FUNCIONÁRIOS QUE FIZERAM ALGUMA ALTERAÇÃO NESSE "ATUALIZADO" SISTEMA -->
<?php

$id = '';
$nome = '';
$data_hora = '';

$sql = "
        SELECT * FROM cs2.visitas_log vl
        INNER JOIN cs2.funcionario f ON f.id = vl.id_funcionario
        WHERE id_visita = $protocolo
    ";
$query = mysql_query($sql, $con) or die('erro');
?>

<table width="850px" align="center" cellpadding="0" cellspacing="2" style="border: 1px solid #D1D7DC; background-color: #FFFFFF; margin-top:20px;">
    
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr> 

    <tr>
        <td colspan="3" style="text-align:center;"><h2>Últimas Alterações</h2><hr></td>
    </tr>    

    <tr>
        <td>#</td>
        <td>Nome</td>
        <td>Data e Hora</td>
    </tr>

<?php
while ($row = mysql_fetch_assoc($query)) {
    $id = $row['id'];
    $nome = $row['nome'];
    $data_hora = $row['data_hora'];

    if($id > 0) {
        echo "<tr>";
        echo "<td>".$id."</td>";
        echo "<td>".$nome."</td>";
        echo "<td>".$data_hora."</td>";
        echo "</tr>";
        echo "<tr><td colspan='3'><hr></td></tr>";
    }
}
?>
</table>
<!-- FIM EXIBE TABELA COM OS DADOS DOS ÚLTIMOS FUNCIONÁRIOS QUE FIZERAM ALGUMA ALTERAÇÃO NESSE "ATUALIZADO" SISTEMA -->