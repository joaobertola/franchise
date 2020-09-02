<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.meio.mask.js"></script>

<script language="javascript">
    (function($){
        $(function(){
                $('input:text').setMask();
            }
        );
    })(jQuery);
    jQuery(function($){
        $("#id_data").mask("99/99/9999");
    });
    function MM_formtCep(e, src, mask) {
        if (window.event) { _TXT = e.keyCode; }
        else if (e.which) { _TXT = e.which; }
        if (_TXT > 47 && _TXT < 58) {
            var i = src.value.length; var saida = mask.substring(0, 1); var texto = mask.substring(i)
            if (texto.substring(0, 1) != saida) { src.value += texto.substring(0, 1); }
            return true; } else { if (_TXT != 8) { return false; }
        else { return true; }
        }
    }

    function abrir(URL) {
        var width = 800;
        var height = 600;
        var left = 0;
        var top = 0;
        window.open(URL, 'janela', 'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');
    }

    function abrir2(URL) {
        var width = 1300;
        var height = 800;
        var left = 0;
        var top = 0;
        window.open(URL, 'janela', 'width=' + width + ', height=' + height + ' , top=' + top + ', left=' + left + ', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');
    }

</script>

<?php
require("connect/sessao.php");
require "connect/funcoes.php";
//include("../../../web_control/funcao_php/mascaras.php");

$id = $_REQUEST['id'];
$codigo = $_REQUEST['codigo'];

function ver_imagem_contrato($codloja) {
    global $con;
    $sql_img = "SELECT count(*)AS total FROM base_inform.cadastro_imagem WHERE codloja= $codloja";
    $qry_img = mysql_query($sql_img, $con) or die("erro");
    $total = mysql_result($qry_img, 0, 'total');
    if ($total > 0) {
        echo "<a href='painel.php?pagina1=clientes/a_scanner_exibe.php&codloja=$codloja'>Imagens: Clique Aqui</a>";
    }
}

if ($tipo == "b")
    $frq = "and a.id_franquia='{$_SESSION['usuario']}'";
else
    $frq = "";

if (isset($id)) {
    $comando = "select a.numero, a.id_franquia_jr, c.cpfcnpj, a.atendente_resp, a.renegociacao_tabela, a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, 
            a.cidade, a.bairro, a.end, a.complemento, a.cep, a.fone,  date_format(a.dt_regularizacao, '%d/%m/%Y') AS dt_regularizacao,
            pendencia_contratual, a.fax, a.email, a.tx_mens, a.id_franquia, c.fantasia, date_format(a.dt_cad, '%d/%m/%Y') as data,
            a.sitcli, a.mensalidade_solucoes, a.sit_cobranca, a.sitcli, a.pendencia_contrato,
            d.descsit, a.ramo_atividade, a.obs, a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, a.cpfsocio2, a.id_operadora,
            a.emissao_financeiro, a.vendedor, mid(b.logon,1,5) as logon, mid(b.logon,8,10) as senha, e.descricao, f.nbanco,
            a.agencia_cliente, a.conta_cliente, a.cpfcnpj_doc, a.tpconta, a.nome_doc, a.tx_mens_anterior, a.emitir_nfs, a.limite_credito, a.liberar_nfe, a.status_nfe, a.user_pendencia, a.tipo_nfe,
            a.contador_nome, a.contador_telefone, a.contador_celular, a.contador_email1, a.contador_email2,
            a.multa_contratual, IF(a.id_consultor = 0 OR a.id_consultor IS NULL OR a.id_consultor = '', a.vendedor, g.nome) as nome_consultor, h.nome as nome_agendador, o.descricao AS operadora,
                        o.logomarca, (SELECT senha FROM base_web_control.webc_usuario WHERE id_cadastro = a.codLoja AND login_master = 'S' LIMIT 1) AS senha,
                        date_format(a.data_suspenso,'%d/%m/%Y') AS data_suspenso,
            a.modulo_loja_vitual, a.modulo_receber_deved, a.modulo_pesq_credito, a.modulo_aumentar_vendas 
            FROM cadastro a
            LEFT OUTER JOIN logon b on a.codloja = b.codloja
            inner join franquia c on a.id_franquia=c.id
            inner join situacao d on a.sitcli=d.codsit
            inner join classif_cadastro e on a.classe=e.id
            left outer join consulta.banco f on a.banco_cliente=f.banco
            left outer join cs2.concorrentes f on a.origem=f.id
                        left outer join cs2.consultores_assistente g on g.id = a.id_consultor
                        left outer join cs2.consultores_assistente h on h.id = a.id_agendador
                        LEFT JOIN operadora o on o.id = a.id_operadora
            where a.codloja='$id' $frq";
} elseif (isset($codigo)) {

    $sql = "select codloja from logon where logon like '$codigo%'";
    $res = mysql_query($sql, $con);
    $codloja = mysql_result($res, 0, 'codloja');

    $comando = "select a.numero, a.id_franquia_jr, c.cpfcnpj, a.atendente_resp, a.renegociacao_tabela,  
            a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, a.bairro, a.end, a.complemento, a.cep, a.fone, 
            date_format(a.dt_regularizacao, '%d/%m/%Y') AS dt_regularizacao,  pendencia_contratual, a.hora_cadastro,
            a.fax, a.email, a.tx_mens, a.id_franquia, c.fantasia, date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, 
            a.mensalidade_solucoes, a.sit_cobranca, a.pendencia_contrato,
            d.descsit, a.ramo_atividade, a.obs, a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, a.cpfsocio2, a.id_operadora,
            a.emissao_financeiro, a.vendedor, mid(b.logon,1,5) as logon, mid(b.logon,8,10) as senha, e.descricao, f.nbanco,
            a.agencia_cliente, a.conta_cliente, a.cpfcnpj_doc, a.tpconta, a.nome_doc, a.tx_mens_anterior, a.emitir_nfs, a.limite_credito, a.liberar_nfe, a.status_nfe, a.user_pendencia, a.tipo_nfe,
            a.contador_nome, a.contador_telefone, a.contador_celular, a.contador_email1, a.contador_email2, a.agendador,
            a.multa_contratual, IF(a.id_consultor = 0 OR a.id_consultor IS NULL OR a.id_consultor = '', a.vendedor, g.nome) as nome_consultor, h.nome as nome_agendador,
            o.descricao AS operadora, o.logomarca, (SELECT senha FROM base_web_control.webc_usuario WHERE id_cadastro = a.codLoja AND login_master = 'S' LIMIT 1) AS senha,
            date_format(a.data_suspenso,'%d/%m/%Y') AS data_suspenso,
            a.modulo_loja_vitual, a.modulo_receber_deved, a.modulo_pesq_credito, a.modulo_aumentar_vendas 
            FROM cadastro a
            LEFT OUTER JOIN logon b on a.codloja = $codloja
            inner join franquia c on a.id_franquia=c.id
            inner join situacao d on a.sitcli=d.codsit
            inner join classif_cadastro e on a.classe=e.id
            left outer join consulta.banco f on a.banco_cliente=f.banco
                        left outer join cs2.consultores_assistente g on g.id = a.id_consultor
                        left outer join cs2.consultores_assistente h on h.id = a.id_agendador
                        LEFT JOIN operadora o on o.id = a.id_operadora
            where mid(b.logon,1,5)='$codigo' $frq limit 1";
}

///echo "<pre>".$comando;
///die;

$res = mysql_query($comando, $con);
$linhas = mysql_num_rows($res);
if ($linhas == "0") {
    echo "<br>
            <table width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                <tr height=\"20\" class=\"titulo\">
                    <td align=\"center\" width=\"100%\" >Nenhum cliente cadastrado!</td>
                </tr>
            </table>";
} else {

$matriz = mysql_fetch_array($res);
$codloja = $matriz['codloja'];
$multa_contratual = $matriz['multa_contratual'];
$franqueado = $matriz['id_franquia'];
$nomefantasia = $matriz['nomefantasia'];
$id_operadora = $matriz['id_operadora'];
$razao = $matriz['razaosoc'];

$limite_credito = $matriz['limite_credito'];
$liberar_nfe = $matriz['liberar_nfe'];
$tipo_nfe = $matriz['tipo_nfe'];
$limite_credito = 'R$ ' . number_format($limite_credito, 2, ',', '.');

// verifica_email($franqueado,$codloja,$nomefantasia);

/*
  require "connect/conexao_conecta_virtual.php";

  $sql_virtual = "Select fra_nomesite , fra_dominio
  FROM dbsites.tbl_framecliente
  WHERE fra_codloja = $codloja";
  $qry_virtual = mysql_query($sql_virtual,$con_virtual) or die ('Erro: '.$sql_virtual);
  $fra_nomesite = mysql_result($qry_virtual,0,'fra_nomesite');
  $fra_dominio = mysql_result($qry_virtual,0,'fra_dominio');
  mysql_close($con_virtual);

  if ( !empty($fra_nomesite) )
  $site = "$fra_nomesite.$fra_dominio";
 */

$user_pendencia = $matriz['user_pendencia'];
if ($user_pendencia > 0) {
    $sql_l = "SELECT nome FROM cs2.funcionario WHERE id = $user_pendencia";
    $qry_l = mysql_query($sql_l, $con) or die('Erro: ' . $sql_l);
    $nome_user_lib_pendencia = mysql_result($qry_l, 0, 'nome');
}

// Verificando carta.

$sql_corresp = "SELECT id, DATE_FORMAT(data,'%d/%m/%Y')as data, hora 
                FROM cs2.correspondencia_grava
                WHERE codloja = $codloja";
$qry_corresp = mysql_query($sql_corresp, $con) or die('Erro: ' . $sql_corresp);
$corresp = '';
while ($rx = mysql_fetch_array($qry_corresp)) {
    $id = $rx['id'];
    $data = $rx['data'];
    $hora = $rx['hora'];
    $corresp .= "<a href=\"javascript:abrir2('clientes/correspondencias_ver.php?id=$id')\">
                $data - $hora
                </a><br>";
}

$sql = "select mid(logon,1,5), mid(logon,7,10), sitlog from logon where codloja='$codloja' limit 1";
$resposta = mysql_query($sql, $con);
$log = mysql_fetch_array($resposta);
$logon = $log['mid(logon,1,5)'];

require "connect/conexao_conecta_virtual.php";
$sql = "SELECT concat(fra_nomesite, '.', fra_dominio) url FROM dbsites.tbl_framecliente WHERE fra_codloja = $codloja LIMIT 1;";
$resposta = mysql_query($sql, $con_virtual);
$site = (mysql_fetch_array($resposta)) ? mysql_result($resposta, 0) : '';

$command = "select a.codcons, b.nome, a.valorcons, b.vr_custo, c.qtd from valconscli a 
            inner join valcons b on a.codcons=b.codcons
            left join bonificadas c on a.codloja = c.codloja  and a.codcons = c.tpcons
            where a.codloja=$codloja and a.codcons<>'A0409'";
$result = mysql_query($command, $con);
@$linhas = mysql_num_rows($result);
$linhas1 = $linhas + 3;

//tratamento para agencia e conta corrente
$agencia_cliente = $matriz['agencia_cliente'];
$agencia_cliente = strtoupper($agencia_cliente);

if (strlen($agencia_cliente) > 4) {
    $agencia_cliente = substr($agencia_cliente, 0, 4) . '-' . substr($agencia_cliente, 4, 1);
} else {
    $agencia_cliente = substr($agencia_cliente, 0, 4);
}

$conta_cliente = $matriz['conta_cliente'];
$conta_cliente = substr($conta_cliente, 0, -1) . '-' . substr($conta_cliente, -1, 1);
$senha = $matriz['senha'];

$renegociacao_tabela = $matriz['renegociacao_tabela'];
$dia = substr($renegociacao_tabela, 8, 10);
$mes = substr($renegociacao_tabela, 5, 2);
$ano = substr($renegociacao_tabela, 0, 4);
$data_view.=$dia;
$data_view.="/";
$data_view.=$mes;
$data_view.="/";
$data_view.=$ano;


if ($matriz['pendencia_contratual'] == "0") {
    $cor = "#33CC66";
    $des_cpendencia_contratual = "NORMALIZADA";
    $alterar = "N";
}

if ($matriz['pendencia_contratual'] == "1") {
    $cor = "red";
    $des_cpendencia_contratual = "PENDENTE";
    $alterar = "S";
}

$flag = 0;
if ($codloja > 0) {
    $sql_l = "SELECT venda_equipamento FROM cs2.cadastro WHERE codloja = '$codloja' ";
    $qry_l = mysql_query($sql_l, $con) or die('Erro: ' . $sql_l);
    $flag = mysql_result($qry_l, 0, 'venda_equipamento');
}
?>

<script language="javascript">
    function gravarCarta(){
        frm = document.form;
        frm.action = 'painel.php?pagina1=clientes/a_grava_carta.php&acao=1';
        frm.submit();
    }

    function cancelarCarta(){
        frm = document.form;
        frm.action = 'painel.php?pagina1=clientes/a_grava_carta.php&acao=2';
        frm.submit();
    }

    function novoCliente(){
        frm = document.form;
        frm.action = 'painel.php?pagina1=clientes/a_incclient.php';
        frm.submit();
    }

    function alterarCliente(){
        frm = document.form;
        frm.action = 'painel.php?pagina1=clientes/a_altcliente1.php&codloja=<?= $codloja ?>';
        frm.submit();
    }

    function novoatendimento(){

        frm = document.getElementById('novo').submit()
        // window.open("http://webempresa.net.br/atendimento/novo_atendimento.php?iptIdCliente=<?= $codloja ?>");
    }

    function Extrato_Crediario_Recupere(logon){
        frm = document.form;
        frm.action = 'painel.php?pagina1=area_restrita/d_extrato_crediario.php&codigo=' + logon + '&go=ingressar';
        frm.submit();
    }

    function CadastroEquipamentos(codigo){
        frm = document.form;
        frm.action = 'painel.php?pagina1=clientes/a_equipamentos.php&codigo=' + codigo + '&go=ingressar';
        frm.submit();
    }

    function UsoeConsumo(codigo){
        frm = document.form;
        frm.action = 'painel.php?pagina1=clientes/a_usoeconsumo.php&codigo=' + codigo + '&go=ingressar';
        frm.submit();
    }

    function aplicarMultaContratual(acao, cliente, codigo){
        frm = document.form;
        frm.action = 'painel.php?pagina1=clientes/a_grava_multa.php&acao=' + acao + '&codloja=' + cliente + '&codigo=' + codigo;
        frm.submit();
    }

    function CancelarRegularizacao(cliente){
        frm = document.form;
        frm.action = 'painel.php?pagina1=clientes/a_grava_cancelamento_data_regularizacao.php&codloja=' + cliente;
        frm.submit();
    }


    function strzero(num, n){
        var txt = num + '';
        var vezes = (parseFloat(n) - parseFloat(txt.length));
        var saida = txt;
        for (var i = 0; i < vezes; i++){
            var saida = "0" + saida;
        }
        return saida;
    }

    function calculaUltimaFatura(){
        var data_doc = document.form.data_doc.value;
        var dia = data_doc.substr(0, 2);
        var mes = data_doc.substr(3, 2);
        var ano = data_doc.substr(6, 4);
        var ano_p = parseInt(ano) + 1;
        var mes_p = (parseFloat(mes) + parseFloat('1'));

        if (mes == 12)
            var ultima_fatura = '30' + '/01/' + ano_p;
        else if (mes == 01)
            var ultima_fatura = '28' + '/02/' + ano;
        else
            var ultima_fatura = '30/' + strzero(mes_p, 2) + '/' + ano;
        document.form.ultima_fatura.value = ultima_fatura;
        alert("Favor conferir a data da última Fatura desde Cliente ! ");
    }

    function deletar(){
        if (confirm("Tem certeza que deseja Excluir ?")) {
        } else {
            return false
        }
    }

    function mostra(form, idDiv,valor) {
        if ( valor == 5 ){
           div = document.getElementById(idDiv);
           if (div.style.display == 'none') div.style.display = 'block';
        }
        else div.style.display = 'none';
    }


    function afixar(form, idDiv) {
        div = document.getElementById(idDiv);
        if (div.style.display == 'none') div.style.display = 'block';
        else div.style.display = 'none';
    }

    function limpar(form, idDiv) {
        document.form.dt_regularizacao.value = null;
        document.form.senha_user.value = null;
        $('#nome_usuario').text('');
        div = document.getElementById(idDiv);
        if (div.style.display == 'none') div.style.display = 'block';
        else div.style.display = 'none';
    }

    function valida_user(){
        frm = document.form;
        var usuario = frm.senha_user.value;
        if (usuario == ''){
            alert('Favor informar a senha para autorizacao');
            exit;
        }
        var req = new XMLHttpRequest();
        req.open('GET', "connect/valida_user.php?usuario=" + usuario, false);
        req.send(null);
        if (req.status != 200) return '';
        var resposta = req.responseText;
        var array = resposta.split(';');
        var id = array[0] - 1;
        var nome = array[1];
        $('#nome_usuario').text(nome);
        if (id > 0){
            var data = new Date();
            var dia = data.getDate();
            var mes = data.getMonth();
            var mes_tmp = data.getMonth();
            var resultado = new Number(mes_tmp + 1);
            if (resultado < 10){
                resultado = "0" + resultado;
            }
            var ano4 = data.getFullYear();
            var datas = strzero(dia, 2) + '/' + (resultado) + '/' + ano4;
            document.form.dt_regularizacao.value = datas;
        } else {
            alert('Senha inválida!');
        }
    }

    function VendaComEquipamento(){
        $('#senha__venda_com_equipamento_div').show();
    }

    function VendaComEquipamentoExec() {

        var codloja = '<?php echo $codloja; ?>';
        var senha = $('#senha__venda_com_equipamento').val();

        var flag;
        if($('#check_venda_com_equipamento:checked').val()){
            flag = 1;
        }else{
            flag = 0;
        }

        var req = new XMLHttpRequest();
        req.open('GET', "connect/venda_com_equipamento.php?codloja=" + codloja + '&flag='+flag+'&senha='+senha, false);
        req.send(null);

        if (req.status != 200)
            return '';

        var resposta = JSON.parse(req.responseText);

        alert(resposta.mensagem);

        if(resposta.status == 1){
            $('#senha__venda_com_equipamento_div').hide();
        }

    }

    function LogarWebControl(){

        document.getElement

        document.getElementById('btnEnviarNovaAba').click();

    }

</script>
<form name="form" method="post" action="#">
    <input type="hidden" name="codloja" value="<?= $codloja ?>">
    <input type="hidden" name="alterar" value="<?= $alterar ?>">

    <table border="0" align="center" width="700">
        <tr>
            <td colspan="4" class="titulo" align="center">CLIENTES WEB CONTROL </td>
        </tr>
        <tr>
            <td width="283" class="subtitulodireita">ID</td>
            <td width="170" colspan="3" class="subtitulopequeno"><?php echo $codloja; ?></td>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Funcion&aacute;rio  Franquia</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['atendente_resp']; ?></td>
        </tr>

        <tr>
            <td class="subtitulodireita">C&oacute;digo de Cliente </td>
            <td colspan="2" class="campojustificado"><?php echo $log['mid(logon,1,5)']; ?></td>

            <td width="170" class="subtitulopequeno">
                <?php if (in_array($_SESSION['id'], array(163,4,11,25,28,4,12,128,1388))) { ?>
                    <input type="button" value="Web Control Empresas" onclick='LogarWebControl( <?php echo $log['mid(logon,1,5)']; ?>)'/>
                <?php } ?>
            </td>


        </tr>
        <tr>
            <td class="subtitulodireita">Raz&atilde;o Social</td>
            <td colspan="2" class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
            <td class="subtitulopequeno">
                <input type="button" value="Ordem de Atendimento" onclick="novoatendimento()" />
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Nome Fantasia</td>
            <td colspan="2" class="subtitulopequeno"><?php echo $matriz['nomefantasia']; ?></td>
            <td width="170" class="subtitulopequeno">
                <?php if (in_array($_SESSION['id'], array(163,4,11,25,28,4,12,128,1388))) { ?>
                    <input type="button" value="Extrato Crediario/Recupere" onclick='Extrato_Crediario_Recupere( <?php echo $log['mid(logon,1,5)']; ?>)'/>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">CNPJ</td>
            <td colspan="2" class="subtitulopequeno"><?php echo $matriz['insc']; ?></td>
            <td width="170" class="subtitulopequeno">
                <?php if (in_array($_SESSION['id'], array(163,4,11,25,28,4,12,128,1388))) { ?>
                    <input type="button" value="Controle de Equipamentos" onclick='CadastroEquipamentos( <?php echo $codloja; ?> )'/>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Endere&ccedil;o</td>
            <td colspan="2" class="subtitulopequeno"><?php echo $matriz['end']; ?></td>
            <td width="170" class="subtitulopequeno">
                <?php if (in_array($_SESSION['id'], array(163,4,11,25,28,4,12,128,1388))) { ?>
                    <input type="button" value="USO E Consumo" onclick='UsoeConsumo( <?php echo $codloja; ?> )'/>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">N&uacute;mero</td>
            <td colspan="2" class="subtitulopequeno"><?= $matriz['numero'] ?></td>
            <td width="170" class="subtitulopequeno">
                <?php if (in_array($_SESSION['id'], array(163,4,11,25,28,4,12,128,1388))) { ?>
                    <input type="checkbox" <?php echo ($flag == 1) ? 'checked' : '' ?> id="check_venda_com_equipamento" onclick="VendaComEquipamento( <?php echo $codloja; ?> )"/> Venda Com Equipamento
                <?php } ?>
                <div id="senha__venda_com_equipamento_div" style="display:none;">
                    <label>Senha:</label><br/>
                    <input type="password" id="senha__venda_com_equipamento"/><br/><br/>
                    <a style="background:#F9F9F9;color:#222;border-radius:2px;padding:5px;margin:5px 0px;" onclick="VendaComEquipamentoExec()">ATUALIZAR</a>
                </div>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Complemento</td>
            <td colspan="3" class="subtitulopequeno"><?= $matriz['complemento'] ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Bairro</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['bairro']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">UF</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['uf']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Cidade</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['cidade']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">CEP</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['cep']; ?></td>
        </tr>

        <tr>
            <td class="subtitulodireita">Telefone</td>
            <td colspan="3" class="subtitulopequeno"><?php echo mascara_celular_wc($matriz['fone']); ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Fax</td>
            <td colspan="3" class="subtitulopequeno"><?php echo mascara_celular_wc($matriz['fax']); ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Celular</td>
            <td class="subtitulopequeno"><?php echo mascara_celular_wc($matriz['celular']); ?>
            <td width="170" class="subtitulopequeno">Operadora:</td>
            <td width="170" class="subtitulopequeno">
                <?php
                if ( $matriz['logomarca'] != '') {
                    echo "<img src='../img/operadoras/{$matriz['logomarca']}'>";
                } else {
                    echo $matriz['operadora'];
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Telefone
                Residencial</td>
            <td colspan="3" class="subtitulopequeno"><?php echo mascara_celular_wc($matriz['fone_res']); ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Site - Virtual Flex</td>
            <td colspan="3" class="subtitulopequeno"><a href="http://www.<?php echo $site; ?>" target="_blank"><font color="#0000FF"><b>www.<?= $site ?></b></font></a></td>
        </tr>
        <tr>
            <td class="subtitulodireita">E-mail</td>
            <td colspan="3" class="subtitulopequeno"><?php echo strtolower($matriz['email']); ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Propriet&aacute;rio 1 </td>
            <td colspan="3" class="subtitulopequeno">
                <table border="0">
                    <tr>
                        <td class="subtitulodireita">Nome</td>
                        <td class="campoesquerda"><?php echo $matriz['socio1']; ?></td>
                    </tr>
                    <tr>
                        <td class="subtitulodireita">CPF 1</td>
                        <td class="campoesquerda"><?php echo $matriz['cpfsocio1']; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Propriet&aacute;rio 2</td>
            <td colspan="3" class="subtitulopequeno">
                <table border="0">
                    <tr>
                        <td class="subtitulodireita">Nome</td>
                        <td class="campoesquerda"><?php echo $matriz['socio2']; ?></td></tr>
                    <tr>
                        <td class="subtitulodireita">CPF 2</td>
                        <td class="campoesquerda"><?php echo $matriz['cpfsocio2']; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Nome Contador</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['contador_nome']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Telefone Contador</td>
            <td colspan="3" class="subtitulopequeno"><?php echo mascara_celular_wc($matriz['contador_telefone']); ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Celular</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['contador_celular']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Email 1</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['contador_email1']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Email 2</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['contador_email2']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Segmento Empresarial</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['ramo_atividade']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Consultor</td>
            <td class="subtitulopequeno"><?php echo $matriz['nome_consultor']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Agendador</td>
            <td class="subtitulopequeno"><?php echo $matriz['nome_agendador']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Dados da Conta Corrente Receba F&aacute;cil</td>
            <td colspan="3" class="subtitulopequeno">
                <table border="0" class="subtitulopequeno">
                    <tr>
                        <td class="campoesquerda">Banco</td>
                        <td><?php echo $matriz['nbanco']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="campoesquerda">Ag&ecirc;ncia + DV (ex.: 1234-5) </td>
                        <td class="subtitulopequeno"><?php echo $agencia_cliente; ?></td>
                    </tr>
                    <tr>
                        <td class="campoesquerda">Conta  + DV (ex.: 123456-7) </td>
                        <td class="subtitulopequeno"><?php echo $conta_cliente; ?></td>
                    </tr>
                    <tr>
                        <td class="campoesquerda">Tipo de Conta</td>
                        <td class="subtitulopequeno">
                            <?php
                            if ($matriz['tpconta'] == 2)
                                echo "Poupan&ccedil;a";
                            else if ($matriz['tpconta'] == 1)
                                echo "Conta Corrente";
                            else
                                echo "";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="campoesquerda">Nome do Correntista </td>
                        <td class="subtitulopequeno"><?php echo $matriz['nome_doc']; ?></td>
                    </tr>
                    <tr>
                        <td class="campoesquerda">CPF / CNPJ do Titular </td>
                        <td class="subtitulopequeno"><?php echo $matriz['cpfcnpj_doc']; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Franqueado</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['fantasia']; ?></td>
        </tr>

        <?php
        if ($matriz['id_franquia_jr'] > 0) {
            $sql_jr = "SELECT fantasia AS nome_jr FROM cs2.franquia WHERE id = '{$matriz['id_franquia_jr']}'";
            $qry_jr = mysql_query($sql_jr, $con);
            ?>
            <tr>
                <td class="subtitulodireita">Franqueado J&uacute;nior</td>
                <td colspan="3" class="subtitulopequeno"><?= @mysql_result($qry_jr, 0, 'nome_jr'); ?></td>
            </tr>
        <?php } ?>

        <tr>
            <td class="subtitulodireita">Data de afilia&ccedil;&atilde;o</td>
            <td colspan="3" class="subtitulopequeno"><?php echo $matriz['data'] . ' ' . $matriz['hora_cadastro']; ?></td>
        </tr>

        <?php if (($_SESSION['ss_tipo'] == "a") or ( $_SESSION["id"] == '1204')) { ?>
            <tr>
                <td class="subtitulodireita">Emiss&atilde;o de Nota Fiscal de Servi&ccedil;o</td>
                <td colspan="3" valign="top" class="subtitulopequeno">
                    <select name="emitir_nfs" disabled="disabled">
                        <option value="" <?php if ($matriz['emitir_nfs'] == "") {
                            echo "selected";
                        } ?> >Selecione</option>
                        <option value="S" <?php if ($matriz['emitir_nfs'] == "S") { echo "selected"; } ?> >SIM</option>
                        <option value="N" <?php if ($matriz['emitir_nfs'] == "N") {
                            echo "selected";
                        } ?> >N&Atilde;O</option>
                    </select>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td class="subtitulodireita">Renegocia&ccedil;&atilde;o de Tabela</td>
            <td colspan="3" class="subtitulopequeno"><?= $data_view ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">
                <a href="https://www.webcontrolempresas.com.br/inform/limite_cliente.php?codloja=<?= $codloja ?>" target="_blank"><font color="#0000FF"><b>Limite de Antecipa&ccedil;&atilde;o de Boletos</b></font></a></td>
            <td colspan="3" class="subtitulopequeno"><?= $limite_credito ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Pacote Anterior ao reajuste</td>
            <td colspan="3" class="subtitulopequeno">R$&nbsp;<?php echo $matriz['tx_mens_anterior']; ?></td>
        <tr>
            <td class="subtitulodireita">Pacote Sistema Gestão</td>
            <td colspan="3" class="subtitulopequeno">R$&nbsp;<?php echo $matriz['tx_mens']; ?></td>
        </tr>

        <tr>
            <td class="subtitulodireita">Licen&ccedil;as - Softwares de Solu&ccedil;&otilde;es</td>
            <td colspan="3" class="subtitulopequeno">R$ <?php echo $matriz['mensalidade_solucoes']; ?></td>
        </tr>

        <tr>
            <td class="subtitulodireita">Módulo Loja Virtual E-commerce</td>
            <td colspan="3" class="subtitulopequeno">R$ <?php echo $matriz['modulo_loja_vitual']; ?></td>
        </tr>

        <tr>
            <td class="subtitulodireita">Módulo Receber de Devedores</td>
            <td colspan="3" class="subtitulopequeno"><?php 
                echo $matriz['modulo_receber_deved'] == '' ? 'nenhum' : 'R$ '.$matriz['modulo_receber_deved'] ;?>
            </td>
        </tr>

        <tr>
            <td class="subtitulodireita">Módulo Consulta de Crédito</td>
            <td colspan="3" class="subtitulopequeno"><?php
                echo $matriz['modulo_pesq_credito'] == '' ? 'nenhum' : 'R$ '.$matriz['modulo_pesq_credito'] ;?>
            </td>
        </tr>

        <tr>
            <td class="subtitulodireita">Módulo Aumentar Clientes e Faturamento</td>
            <td colspan="3" class="subtitulopequeno"><?php
                echo $matriz['modulo_aumentar_vendas'] == '' ? 'nenhum' : 'R$ '.$matriz['modulo_aumentar_vendas'] ;?>
            </td>
        </tr>

        <tr>
            <td class="subtitulodireita">Tabela de Pre&ccedil;os</td>
            <td colspan="3">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="7" height="1" bgcolor="#999999"></td>
                    </tr>
                    <tr>
                        <td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
                    </tr>
                    <tr height="20">
                        <td align="center" class="campoesquerda">C&oacute;digo</td>
                        <td align="center" class="campoesquerda">Produto</td>
                        <td align="center" class="campoesquerda">Venda</td>
                        <td align="center" class="campoesquerda">Gratuidade</td>
                        <td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
                    </tr>
                    <tr>
                        <td colspan="6" height="1" bgcolor="#666666">               </td>
                    </tr>
                    <?php
                    for ($a = 1; $a <= $linhas; $a++) {
                        $matrix = mysql_fetch_array($result);
                        $codigo = $matrix['codcons'];
                        $produto = $matrix['nome'];
                        $venda = $matrix['valorcons'];
                        $custo = $matrix['vr_custo'];
                        $gratuidade = $matrix['qtd'];
                        echo "<tr height=\"22\">
                                        <td align=\"center\" class=\"subtitulopequeno\">$codigo</td>
                                        <td align=\"left\" class=\"subtitulopequeno\">$produto</td>
                                        <td align=\"right\" class=\"subtitulopequeno\">$venda</td>
                                        <td align=\"center\" class=\"subtitulopequeno\">$gratuidade</td>
                                </tr>";
                    }
                    echo "<tr>
                                        <td colspan=\"5\" align=\"right\" height=\"1\" bgcolor=\"#666666\"></td>
                                </tr>";
                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
            <td colspan="3" class="formulario"><textarea name="obs" cols="50" rows="3"><?php echo $matriz['obs']; ?></textarea></td>
        </tr>
        <?php
        if (($_SESSION['id'] == 163) or ( $_SESSION['id'] == 4) or ( $_SESSION['id'] == 1204)) {

            if ($multa_contratual == 0)
                $texto = 'Aplicar MULTA CONTRATUAL';
            else
                $texto = 'Estornar MULTA CONTRATUAL';

            if ($matriz['pendencia_contratual'] == "0") {
                ?>
                <tr>
                    <td class="subtitulodireita">Documenta&ccedil;&atilde;o Cliente (contrato, tabela, ..)</td>
                    <td colspan="2" bgcolor="<?= $cor ?>"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b><?= $des_cpendencia_contratual ?></b></font></td>
                    <td>
                        <?php if ( $_SESSION['id'] == 163 ) { ?>
                            <input type="button" name="bt1" value="<?= $texto ?>" onclick="aplicarMultaContratual(<?php echo $multa_contratual . ',' . $codloja . ',' . $log['mid(logon,1,5)']; ?>)" />
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td class="subtitulodireita">Data da Regulariza&ccedil;&atilde;o da Documenta&ccedil;&atilde;o</td>
                    <td colspan="2" bgcolor="<?= $cor ?>"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b><?= $matriz['dt_regularizacao'] . ' - ' . $nome_user_lib_pendencia ?></b></font></td>
                    <td>
                        <?php if ( $_SESSION['id'] == 163 ) { ?>
                            <input type="button" name="bt1" value="Cancelar Data Regulariza&ccedil;&atilde;o" onclick="CancelarRegularizacao(<?php echo $codloja; ?>)" />
                        <?php } ?>
                    </td>
                </tr>
                <?php
            } else {
                ?>
                <tr>
                    <td class="subtitulodireita">Documenta&ccedil;&atilde;o Cliente (contrato, tabela, ..)</td>
                    <td colspan="2" bgcolor="<?= $cor ?>">
                        <label>
                            <input type="radio" name="pendencia_contratual" value="1" onClick="limpar(form, 'senhauser')" checked="checked"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b>PENDENTE</b></font>
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="pendencia_contratual" value="0" onClick="afixar(form, 'senhauser')"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b>NORMALIZADA</b></font>
                            <div id="senhauser" style='display: none;'>
                                Favor Informar a Senha :
                                <input type="password" name="senha_user" />
                                <input type="button" value="[ OK ]" onclick="valida_user()" />
                            </div>
                        </label>
                    </td>
                    <td>
                        <?php if ( $_SESSION['id'] == 163 ) { ?>
                            <input type="button" name="bt1" value="<?= $texto ?>" onclick="aplicarMultaContratual(<?php echo $multa_contratual . ',' . $codloja . ',' . $log['mid(logon,1,5)']; ?>)" />
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td class="subtitulodireita">Data da Regulariza&ccedil;&atilde;o da Documenta&ccedil;&atilde;o</td>
                    <td colspan="3" bgcolor="<?= $cor ?>">
                        <input type='text' name='dt_regularizacao' onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" maxlength="10" readonly="readonly">
                        <div id='nome_usuario'></div>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td class="subtitulodireita">Documenta&ccedil;&atilde;o Cliente (contrato, tabela, ..)</td>
                <td colspan="3" bgcolor="<?= $cor ?>"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b><?= $des_cpendencia_contratual ?></b></font></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Data da Regulariza&ccedil;&atilde;o da Documenta&ccedil;&atilde;o</td>
                <td colspan="3" bgcolor="<?= $cor ?>"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b><?= $matriz['dt_regularizacao'] ?></b></font></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td class="subtitulodireita">Situa&ccedil;&atilde;o do Contrato</td>
            <td colspan="3" class="formulario" 
                <?php
                if ($matriz['sitcli'] == 0) {
                    echo " style='color:#FFFFFF'  bgcolor='#33CC66'>".$matriz['descsit'];
                } elseif ($matriz['sitcli'] == 5) {
                    echo " style='color:#000000' bgcolor='#FFFF00'>".$matriz['descsit']." até ".$matriz['data_suspenso'];
                } else {
                    echo " style='color:#FFFFFF'  bgcolor='#FF0000'>".$matriz['descsit'];
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Acesso</td>
            <?php
            echo "<td class='formulario' style='color:#FFFFFF'";
            if ($log['sitlog'] == 0)
                echo "bgcolor='#33CC66'>ATIVO";
            elseif ($log['sitlog'] == 1)
                echo "bgcolor='#FFCC00'>BLOQUEADO";
            else
                echo "bgcolor='#FF0000'>CANCELADO";
            ?>
            <td colspan="3"></td>
        <tr>
        <tr>
            <td class="subtitulodireita">Situa&ccedil;&atilde;o de Cobran&ccedil;a</td>
            <td colspan="3" class="subtitulopequeno"><b>
                    <?php
                    if ($matriz['sit_cobranca'] == 0)
                        echo "ATIVO";
                    else
                        echo "NEGATIVADO";
                    ?></b>
            </td>
        </tr>
        <?php
        $ssql = "SELECT date_format(a.data_documento,'%d/%m/%Y') as documento, 
                            a.tipo_documento, b.motivo,
                            date_format(a.ultima_fatura,'%d/%m/%Y') as ultima 
                     FROM cs2.pedidos_cancelamento a
                     INNER JOIN cs2.motivo_cancel b ON a.id_mot_cancelamento = b.id
                     WHERE codloja='$codloja'";
        $rs = mysql_query($ssql, $con);
        $line = mysql_num_rows($rs);
        if ($line != 0) {
            while ($fila = mysql_fetch_object($rs)) {
                echo "
                            <tr>
                              <td class=\"subtitulodireita\">Dados do Cancelamento </td>
                              <td class=\"subtitulopequeno\"><table>
                                <tr>
                                  <td class=\"subtitulodireita\">Data do Documento</td>
                                  <td class=\"campoesquerda\">$fila->documento</td>
                                </tr>
                                <tr>
                                  <td class=\"subtitulodireita\">Doc. de Cancelamento</td>
                                  <td class=\"campoesquerda\">$fila->tipo_documento</td>
                                </tr>
                                <tr>
                                  <td class=\"subtitulodireita\">Motivo do Cancelamento</td>
                                  <td class=\"campoesquerda\">$fila->motivo</td>
                                </tr>
                                <tr>
                                  <td class=\"subtitulodireita\">&Uacute;ltima Fatura</td>
                                  <td class=\"campoesquerda\">$fila->ultima</td>
                                </tr>
                              </table></td>
                            </tr>";
            }
            mysql_free_result($rs);
        }
        ?>
        <tr>
            <td class="subtitulodireita">Faturas Pendentes</td>
            <?php
            $sql_boleto = " SELECT vencimento, valor, numdoc, date_format(vencimento,'%d/%m/%Y') as venc2 
                                FROM cs2.titulos 
                                WHERE referencia <> 'MULTA' AND codloja='$codloja' AND datapg IS NULL 
                                ORDER BY vencimento ASC";
            $qr_boleto = mysql_query($sql_boleto, $con) or die("\n erro no segundo\n" . mysql_error() . "\n\n");
            $nreg = mysql_num_rows($qr_boleto);
            if ($nreg == 0) {
                echo "<td class=\"campojustificado\" style=\"padding-left:5px\">
                            <b>Este cliente n&atilde;o registra boletos em aberto para este periodo<br><br><br><br><br><br></b>
                        </td>";
            } else {
                $boleto2 = "";
                $boleto3 = "";
                $boleto4 = "";
                $boleto5 = "";
                $boleto6 = "";
                $boleto7 = "";
                $boleto8 = "";
                $boleto9 = "";
                $boleto10 = "";
                $envelope2 = "";
                $envelope3 = "";
                $envelope4 = "";
                $envelope5 = "";
                $envelope6 = "";
                $envelope7 = "";
                $envelope8 = "";
                $envelope9 = "";
                $envelope10 = "";
                for ($i = 0; $i < $nreg; $i++) {
                    $mes_ano = mysql_result($qr_boleto, $i, "vencimento");
                    $venc = mysql_result($qr_boleto, $i, "venc2");
                    $mes_ano = substr($mes_ano, 5, 2) . "/" . substr($mes_ano, 0, 4);
                    if ($i == 0) {
                        $boleto1 = $venc;
                        $numdoc1 = mysql_result($qr_boleto, $i, "numdoc");
                    }
                    if ($i == 1) {
                        $boleto2 = $venc;
                        $numdoc2 = mysql_result($qr_boleto, $i, "numdoc");
                    }
                    if ($i == 2) {
                        $boleto3 = $venc;
                        $numdoc3 = mysql_result($qr_boleto, $i, "numdoc");
                    }
                    if ($i == 3) {
                        $boleto4 = $venc;
                        $numdoc4 = mysql_result($qr_boleto, $i, "numdoc");
                    }
                    if ($i == 4) {
                        $boleto5 = $venc;
                        $numdoc5 = mysql_result($qr_boleto, $i, "numdoc");
                    }
                    if ($i == 5) {
                        $boleto6 = $venc;
                        $numdoc6 = mysql_result($qr_boleto, $i, "numdoc");
                    }
                    if ($i == 6) {
                        $boleto7 = $venc;
                        $numdoc7 = mysql_result($qr_boleto, $i, "numdoc");
                    }
                    if ($i == 7) {
                        $boleto8 = $venc;
                        $numdoc8 = mysql_result($qr_boleto, $i, "numdoc");
                    }
                    if ($i == 8) {
                        $boleto9 = $venc;
                        $numdoc9 = mysql_result($qr_boleto, $i, "numdoc");
                    }
                    if ($i == 9) {
                        $boleto10 = $venc;
                        $numdoc10 = mysql_result($qr_boleto, $i, "numdoc");
                    }
                }
                if ( $_SESSION['id'] == 163 ) {
                    echo "<td class=\"subtitulopequeno\" style=\"padding-left:5px\">";
                    echo "<a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc1\">$boleto1</a>&nbsp;&nbsp;";
                    if ($i > 0) {
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc1&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\" onclick='return deletar()' title='Clique para excluir o boleto'><img src=\"../img/exc.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc1&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar o boleto'><IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                    }

                    echo "<br><a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc2\">$boleto2</a>&nbsp;&nbsp;";
                    if ($i > 1) {
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc2&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\" onclick='return deletar()' title='Clique para excluir o boleto'><img src=\"../img/exc.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc2&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar o boleto'><IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                    }

                    echo "<br><a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc3\">$boleto3</a>&nbsp;&nbsp;";
                    if ($i > 2) {
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc3&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\" onclick='return deletar()' title='Clique para excluir o boleto'><img src=\"../img/exc.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc3&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar o boleto'><IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                    }

                    echo "<br><a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc4\">$boleto4</a>&nbsp;&nbsp;";
                    if ($i > 3) {
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc4&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\" onclick='return deletar()' title='Clique para excluir o boleto'><img src=\"../img/exc.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc4&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar o boleto'><IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                    }

                    echo "<br><a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc5\">$boleto5</a>&nbsp;&nbsp;";
                    if ($i > 4) {
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc5&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\" onclick='return deletar()' title='Clique para excluir o boleto'><img src=\"../img/exc.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc5&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar o boleto'><IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                    }

                    echo "<br><a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc6\">$boleto6</a>&nbsp;&nbsp;";
                    if ($i > 5) {
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc6&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\" onclick='return deletar()' title='Clique para excluir o boleto'><img src=\"../img/exc.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc6&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar o boleto'><IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                    }

                    echo "<br><a href=\"https:://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc7\">$boleto7</a>&nbsp;&nbsp;";
                    if ($i > 6) {
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc8&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\" onclick='return deletar()' title='Clique para excluir o boleto'><img src=\"../img/exc.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc8&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar o boleto'><IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                    }
                    echo "<br><a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc8\">$boleto8</a>&nbsp;&nbsp;";
                    if ($i > 7) {
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc8&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\" onclick='return deletar()' title='Clique para excluir o boleto'><img src=\"../img/exc.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc8&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar o boleto'><IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                    }
                    echo "<br><a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc9\">$boleto9</a>&nbsp;&nbsp;";
                    if ($i > 8) {
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc9&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\" onclick='return deletar()' title='Clique para excluir o boleto'><img src=\"../img/exc.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc9&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar o boleto'><IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                    }
                    echo "<br><a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc10\">$boleto10</a>&nbsp;&nbsp;";
                    if ($i > 9) {
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_excluir_fatura.php&numdoc=$numdoc10&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='EXCLUIR T&iacute;tulo'; return true\" onclick='return deletar()' title='Clique para excluir o boleto'><img src=\"../img/exc.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"painel.php?pagina1=Franquias/b_baixa.php&numdoc=$numdoc10&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar o boleto'><IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
                    }
                    echo "</td>";
                } else {
                    echo "<td class=\"subtitulopequeno\" style=\"padding-left:5px\">
                                <a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc1\">$boleto1</a>&nbsp;&nbsp;
                                <br>
                                <a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc2\">$boleto2</a>&nbsp;&nbsp;
                                <br>
                                <a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc3\">$boleto3</a>&nbsp;&nbsp;
                                <br>
                                <a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc4\">$boleto4</a>&nbsp;&nbsp;
                                <br>
                                <a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc5\">$boleto5</a>&nbsp;&nbsp;
                                <br>
                                <a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc6\">$boleto6</a>&nbsp;&nbsp;
                                <br>
                                <a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc7\">$boleto7</a>&nbsp;&nbsp;
                                <br>
                                <a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc8\">$boleto8</a>&nbsp;&nbsp;
                                <br>
                                <a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc9\">$boleto9</a>&nbsp;&nbsp;
                                <br>
                                <a href=\"https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$numdoc10\">$boleto10</a>&nbsp;&nbsp
                              </td>";
                }
            } //fim else
            ?>
            <td class="subtitulodireita" style="padding-left:5px ; text-align:center; width:5%" >Consultoria<br>e<br>Treinamento</td>
            <td class="subtitulopequeno"  style="padding-left:5px; width:30%">
                <?php
                $sql = "SELECT id,
                                    CASE tp_imagem
                                        WHEN 'C1' THEN '1&ordm; - Treinamento'
                                        WHEN 'C2' THEN '2&ordm; - Treinamento'
                                        WHEN 'C3' THEN '3&ordm; - Treinamento'
                                        WHEN 'C4' THEN '4&ordm; - Treinamento'
                                        WHEN 'C5' THEN '5&ordm; - Treinamento'
                                        WHEN 'C6' THEN '6&ordm; - Treinamento'
                                        WHEN 'C7' THEN '7&ordm; - Treinamento'
                                        WHEN 'C8' THEN '8&ordm; - Treinamento'
                                        WHEN 'C9' THEN '9&ordm; - Treinamento'
                                        WHEN 'C10' THEN '10&ordm; - Treinamento'
                                        WHEN 'C11' THEN '11&ordm; - Treinamento'
                                        WHEN 'C12' THEN '12&ordm; - Treinamento'
                                        WHEN 'C13' THEN '13&ordm; - Treinamento'
                                        WHEN 'C14' THEN '14&ordm; - Treinamento'
                                        WHEN 'C15' THEN '15&ordm; - Treinamento'
                                        WHEN 'C16' THEN '16&ordm; - Treinamento'
                                        WHEN 'C17' THEN '17&ordm; - Treinamento'
                                        WHEN 'C18' THEN '18&ordm; - Treinamento'
                                        WHEN 'C19' THEN '19&ordm; - Treinamento'
                                        WHEN 'C20' THEN '20&ordm; - Treinamento'

                                    END AS tp_imagem, 
                                    DATE_FORMAT(data_consultoria,'%d/%m/%Y') AS data_consultoria, consultora
                                FROM base_inform.cadastro_imagem
                                WHERE codloja = '$codloja' AND mid(tp_imagem,1,1) = 'C'
                                ORDER BY id ";
                $qry = mysql_query($sql, $con) or die("Erro 1 SQL: $sql");
                $saida = "";
                if (mysql_num_rows($qry) > 0) {
                    while ($reg = mysql_fetch_array($qry)) {
                        $id = $reg['id'];
                        $tx = $reg['tp_imagem'] . ' ' . $reg['data_consultoria'] . ' ' . trim($reg['consultora']);
                        $saida .= "<a href=javascript:abrir('clientes/mostra_documento.php?id=$id');>$tx</a><br>";
                    }
                }
                echo $saida;
                ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">CPF / CNPJ do Franqueado</td>
            <td colspan="3" class="subtitulopequeno">
                <?php
                $total_doc = strlen($matriz['cpfcnpj']);
                if ($total_doc == "11") {
                    echo mascaraCpf($matriz['cpfcnpj']);
                    echo "&nbsp;- <b>Fis&iacute;ca</b>";
                }
                if ($total_doc == "14") {
                    echo mascaraCnpj($matriz['cpfcnpj']);
                    echo "&nbsp;- <b>Jur&iacute;dica</b>";
                } ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">&nbsp;</td>
            <td colspan="3" class="subtitulopequeno">&nbsp;</td>
        </tr>
        <?php
        if ($_SESSION['id'] == 163 or $_SESSION['id'] == 4 or $_SESSION['id'] == 1204) {
            ?>
            <tr>
                <td colspan="4">
                    <table border="0" align="center" width="700">
                        <tr>
                            <td colspan="3" class="titulo" align="center">STATUS DO CLIENTE</td>
                        </tr>
                        <tr>
                            <td width="198" class="subtitulodireita">Venda / Treinamento</td>
                            <td width="120" class="subtitulopequeno">
                                <?php if ($_SESSION['id'] == 163 or $_SESSION['id'] == 46) { ?>
                                    <select name="pendencia_contrato">
                                        <option value="0" <?php if ($matriz['pendencia_contrato'] == "0") {
                                            echo "selected";
                                        } ?> >NORMAL</option>
                                        <option value="1" <?php if ($matriz['pendencia_contrato'] == "1") {
                                            echo "selected";
                                        } ?> >IRREGULAR</option>
                                    </select>
                                <?php } ?>
                            </td>
                            <td width="100" rowspan="3" class="subtitulopequeno" style="text-align:center">
                                <a href="javascript:abrir2('painel.php?pagina1=clientes/correspondencias.php&codloja=<?= $codloja ?>&logon=<?= $logon ?>')">
                                    Carta / Peti&ccedil;&atilde;o Resposta ao Associado
                                </a>
                                <br><hr><br>
                                <?= $corresp ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="198" class="subtitulodireita">Situa&ccedil;&atilde;o do CONTRATO</td>
                            <td class="subtitulopequeno">
                                <?php
                                if (($_SESSION['id'] == 163) or ( $_SESSION['id'] == 4)) {
                                    if ($_SESSION['id'] == 4)
                                        $disabled = "disabled='disabled'";
                                    ?>
                                    <select onChange="mostra(form,'dataSuspenso', this.value)" name="sitcli" <?php echo $disabled; ?> >
                                        <option value="0" <?php if ($matriz['sitcli'] == "0") {
                                            echo "selected";
                                        } ?> >ATIVO</option>
                                        <option value="1" <?php if ($matriz['sitcli'] == "1") {
                                            echo "selected";
                                        } ?> >BLOQUEADO</option>
                                        <option value="2" <?php if ($matriz['sitcli'] == "2") {
                                            echo "selected";
                                        } ?> >CANCELADO</option>
                                        <option value="3" <?php if ($matriz['sitcli'] == "3") {
                                            echo "selected";
                                        } ?> >BLQ VIRTUAL</option>
                                        <option value="5" <?php if ($matriz['sitcli'] == "5") {
                                            echo "selected";
                                        } ?> >SUSPENSO</option>
                                    </select>
                                <?php } ?>
                                <div id="dataSuspenso" style='display: <?= $matriz['sitcli'] == '5' ? 'block' : 'none';?>' >
                                   <br><br>
                                   Data limite SUSPENSÃO :<br>
                                   <input type="text" name="data_suspensao" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength='10' value=<?= $matriz['data_suspenso']?> >
                                </div>
                                <?php
                                    echo "<br><br>";
                                    $cmd = "SELECT date_format(data,'%d/%m/%Y - %H:%i:%s') as data, acao FROM cs2.cadastro_log
                                            WHERE codloja = $codloja
                                            ORDER BY id asc";
                                    $rst = mysql_query($cmd, $con);
                                    while ( $reg = mysql_fetch_array( $rst )){
                                        echo $reg['data'].' - '.$reg['acao']."<br>";
                                    }
                                ?></td>
                        </tr>
                        <tr>
                            <td class="subtitulodireita">Situa&ccedil;&atilde;o da COBRAN&Ccedil;A</td>
                            <td class="subtitulopequeno">
                                <select name="sit_cobranca" <?php echo $disabled; ?>>
                                    <option value="0" <?php if ($matriz['sit_cobranca'] == "0") {
                                        echo "selected";
                                    } ?> >ATIVO</option>
                                    <option value="1" <?php if ($matriz['sit_cobranca'] == "1") {
                                        echo "selected";
                                    } ?> >NEGATIVADO</option>
                                </select>
                            </td>
                        </tr>
                        <?php
                        $ssql = "select date_format(a.data_documento,'%d/%m/%Y') as documento, a.tipo_documento, b.motivo,
                                            date_format(a.ultima_fatura,'%d/%m/%Y') as ultima,
                                            a.id_mot_cancelamento
                                            from cs2.pedidos_cancelamento a
                                            inner join cs2.motivo_cancel b on a.id_mot_cancelamento=b.id
                                            where codloja='$codloja'";
                        $rs = mysql_query($ssql, $con);
                        $line = mysql_num_rows($rs);
                        if ($line != 0) {
                            while ($fila = mysql_fetch_object($rs)) {
                                $data_doc = $fila->documento;
                                $tipo_documento = $fila->tipo_documento;
                                $motivo = $fila->motivo;
                                $ultima = $fila->ultima;
                                $id_mot_cancelamento = $fila->id_mot_cancelamento;
                            }
                        }
                        echo "
                                      <tr>
                                              <td class=\"subtitulodireita\">Dados do Cancelamento </td>
                                              <td class=\"subtitulopequeno\" colspan='2' >
                                                    <table>
                                                            <tr>
                                                              <td class='subtitulodireita'>Data do Documento</td>
                                                              <td class='campoesquerda'>
                                                                    <input type='text' name='data_doc' id='data_doc' value='$data_doc' onChange='' onKeyPress=\"return MM_formtCep(event,this,'##/##/####');\" onFocus=\"this.className='boxover'\" onBlur=\"this.className='boxnormal'; calculaUltimaFatura()\" maxlength='10' />
                                                              </td>
                                                        </tr>";
                        ?>
                        <tr>
                            <td class='subtitulodireita'>Doc. de Cancelamento</td>
                            <td colspan="2" class='campoesquerda'>
                                <select name='tipo_documento'>
                                    <option value=''></option>
                                    <option value='CARTA CLIENTE'<?php if ($tipo_documento == "CARTA CLIENTE") {
                                        echo "selected";
                                    } ?>>CARTA CLIENTE</option>
                                    <option value="CARTA FRANQUIA"<?php if ($tipo_documento == "CARTA FRANQUIA") {
                                        echo "selected";
                                    } ?>>CARTA FRANQUIA</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class='subtitulodireita'>Motivo do Cancelamento</td>
                            <td colspan="2" class='campoesquerda'>
                                <?php
                                $sql_cancel = "SELECT id, motivo FROM cs2.motivo_cancel ORDER BY motivo";
                                $qry_cancel = mysql_query($sql_cancel, $con);
                                ?>
                                <select name='id_mot_cancelamento'>
                                    <option value=''></option>
                                    <?php
                                    while ($reg_cancel = mysql_fetch_array($qry_cancel)) {
                                        $id = $reg_cancel['id'];
                                        $mot = $reg_cancel['motivo'];
                                        echo "<option value='$id'";
                                        if ($id == $id_mot_cancelamento) {
                                            echo 'selected';
                                        }
                                        echo "> $id $mot </option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class='subtitulodireita'>&Uacute;ltima Fatura</td>
                            <td colspan="2" class='campoesquerda'><input type='text' name='ultima_fatura' id='ultima' value='<?php echo $ultima; ?>' onChange="" onKeyPress="return MM_formtCep(event, this, '##/##/####');" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" maxlength="10"> </td>
                        </tr>
                        <tr>
                            <td class='subtitulodireita'>&nbsp;</td>
                            <td colspan="2" class='campoesquerda'></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php
            mysql_free_result($rs);
        }
        } //fim else
        $res = mysql_close($con);
        ?>
        <tr align="right">
            <td colspan="3">
                <input name='gravar' type='button' value='GRAVAR' onclick='gravarCarta()' />
                <?php
                if (!empty($id_mot_cancelamento))
                    if (($_SESSION['id'] == 163) or ( $_SESSION['id'] == 46)) {
                        ?>
                        <input name='incluir' type='button' value='CANCELAR CARTA'  onclick='cancelarCarta()'/>
                        <?php
                    }
                ?>
                &nbsp;&nbsp;
                <input name="incluir" type="button" value="Incluir novo Cliente" onclick="novoCliente()" />
                &nbsp;&nbsp;
                <input name="alterar" type="submit" value="Alterar os dados do Cliente" onclick="alterarCliente()" />
            </td>
        </tr>
    </table>
</form>
<form id="novo" method="POST" action="https://www.webcontrolempresas.com.br/atendimento/novo_atendimento.php" target="_blank">
    <input type="hidden" name="iptIdCliente" value="<?=$codloja?>" />
    <input type="hidden" name="iptCodCliente" value="<?=$logon?>" />
    <input type="hidden" name="id_franquia" value="<?=$franqueado?>" />
</form>

<form name="loginWebControl" id="loginWebControl" method="post" action="https://www.webcontrolempresas.com.br/webcontrol/efetiva/LoginEfetiva.php">
    <input type="hidden" name="iptIdCadastro" value="<?=$logon?>">
    <input type="hidden" name="iptSenha" value="<?=$senha?>">
    <input type='submit' id="btnEnviarNovaAba" class="hidden" onclick="this.form.target='_blank';return true;" style="display: none;">
</form>
