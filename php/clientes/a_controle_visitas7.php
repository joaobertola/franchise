<?php
/**
 * @file a_controle_visitas7.php
 * @brief
 * @author ARLLON DIAS
 * @date 30/09/2016
 * @version 1.0
 **/

// include_once("../connect/conexao_conecta.php");

function telefoneConverte2($p_telefone){
    if ($p_telefone == '') {
        return ('');
    } else {
        $a = substr($p_telefone, 0,2);
        $b = substr($p_telefone, 2,5);
        $c = substr($p_telefone, 7,4);

        $telefone_mascarado  = "(";
        $telefone_mascarado .= $a;
        $telefone_mascarado .= ")&nbsp;";
        $telefone_mascarado .= $b;
        $telefone_mascarado .= "-";
        $telefone_mascarado .= $c;
        return ($telefone_mascarado);
    }
}

$id_franquia = $_SESSION['id'];

if ( $id_franquia == 163 || $id_franquia == 4 || $id_franquia == 247) $id_franquia = 1;
?>

<script>
    function localizar(){
        d = document.form;
        d.action = 'painel.php?pagina1=clientes/a_controle_visitas7.php';
        d.submit();
    }

</script>

<style>
    body{
        font-family:Arial, Verdana, sans-serif;
    }

    .botao {
        background-color: #007cc3;
        font-family:Arial, Verdana, sans-serif;
        font-weight: bold;
        font-size: 12px;
        height:25px;
        vertical-align: middle;
        border: 1px solid #999999;
        border: 1px solid #999999;
        margin: 0px;
        padding: 0px;
        color: #333333;
        cursor:pointer;
    }

    .frm_input{
        background-color: #007cc3;
        height:22;
        font-size: 13px;
        border: 1px solid #999999;
        border: 1px solid #999999;
    }

    .topo{
        font-size: 20px;
        height:45px;
        background: #007cc3;
        font-weight: bold;
        text-align: center;
        font-weight:bold;
    }

    a.classe1:link, a.classe1:visited {
        text-decoration: none
    }
    a.classe1:hover {
        text-decoration: underline;
        color: #f00;
    }
    a.classe1:active {
        text-decoration: none
    }
</style>

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/tabela.css" rel="stylesheet" type="text/css" />
<form name="form" method="post" action="#">
    <input type="hidden" name="nome_tmp" value="<?=$_REQUEST['nome_tmp']?>">
    <input type="hidden" name="data_agenda_tmp" value="<?=$_REQUEST['data_agenda_tmp']?>">

    <p>&nbsp;</p>
    <table border="0" width="800px" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #F5F5F5; background-color:#FFFFFF">
        <tr>
            <td colspan="2" class="topo">Cadastro de Assistentes e Consultores</td>
        </tr>

        <tr height="45px">
            <td width="20%" bgColor="#F5F5F5"><b>Período:</b></td>
            <td width="65%" bgColor="#F0F0F6" align="left">
                <input name="dt_inicio" type="text" id="dt_inicio" size="15" maxlength="10" onFocus="this.className = 'boxover'" onKeyPress="return MM_formtCep(event, this, '##/##/####');" onBlur="this.className = 'boxnormal'" value="<?php echo $_REQUEST['dt_inicio']?>" />
                &nbsp;&nbsp;&nbsp;&agrave;&nbsp;&nbsp;&nbsp;
                <input name="dt_fim" type="text" id="dt_fim" size="15" maxlength="10" onFocus="this.className = 'boxover'" onKeyPress="return MM_formtCep(event, this, '##/##/####');" onBlur="this.className = 'boxnormal'" value="<?php echo $_REQUEST['dt_fim'] ?>" />
            </td>
            <td>
                &nbsp;&nbsp;
                <input type="button" value=" Pesquisar " onClick="localizar()" class="botao"/>
            </td>
        </tr>

        <?php
        $registro = 0;

        $strDataInicio = 'base_web_control.fn_get_mes_inicio_filtro()';
        $strDataFim = 'NOW()';
        if($_REQUEST['dt_inicio'] != ""){

//            echo 'Lugar Errado';

            $strDataInicio = "'". substr($_REQUEST['dt_inicio'],6,4).'-'.substr($_REQUEST['dt_inicio'],3,2).'-'.substr($_REQUEST['dt_inicio'],0,2) . " 00:00:00" . "'";
            $strDataFim = "'". substr($_REQUEST['dt_fim'],6,4).'-'.substr($_REQUEST['dt_fim'],3,2).'-'.substr($_REQUEST['dt_fim'],0,2) . " 23:59:00" . "'";
        }

        $sql_seleciona = "SELECT
                    aux.nome_consultor,
                    aux.qtd_visitas,
                    aux.resultado_visitou,
                    aux.resultado_demonstrou,
                    aux.resultado_levousuper,
                    aux.resultado_ligougerente,
                    aux.id_consultor,
                    aux.resultado_cartaovisita,
                    (
                    (aux.resultado_visitou * 1) +
                    (aux.resultado_demonstrou * 4) +
                    (aux.resultado_levousuper * 3) +
                    (aux.resultado_ligougerente * 1) +
                    (aux.resultado_cartaovisita * 1)
                    ) AS total,
                    aux.id_consultor AS id,
                    aux.total_visitas,
                    aux.total_cartoes

                FROM (
                SELECT
                    ca.nome AS nome_consultor,
                    COUNT(cv.id) AS qtd_visitas,
                    SUM(IFNULL(cv.resultado_visitou,0)) AS resultado_visitou,
                    SUM(IFNULL(cv.resultado_demonstrou,0)) AS resultado_demonstrou,
                    SUM(IFNULL(cv.resultado_levousuper,0)) AS resultado_levousuper,
                    SUM(IFNULL(cv.resultado_ligougerente,0)) AS resultado_ligougerente,
                    SUM(IFNULL(cv.qtd_cartoes,0)) AS total_cartoes,
                    ca.id AS id_consultor,
                    cv.resultado_cartaovisita,
                    SUM(IF(cv.resultado_visitou != 0 AND cv.resultado_visitou IS NOT NULL,1,
                    IF(cv.resultado_demonstrou != 0 AND cv.resultado_demonstrou IS NOT NULL,1,
                    IF(cv.resultado_levousuper != 0 AND cv.resultado_levousuper IS NOT NULL,1,
                    IF(cv.resultado_ligougerente != 0 AND cv.resultado_ligougerente IS NOT NULL,1,0))))) AS total_visitas


                    FROM cs2.consultores_assistente ca
                    LEFT JOIN cs2.controle_comercial_visitas cv
                    ON cv.id_consultor = ca.id
                    AND CONCAT(cv.data_agendamento, ' ', cv.hora_agendamento) BETWEEN $strDataInicio AND $strDataFim
                    WHERE FIND_IN_SET(ca.id_franquia ,'$id_franquia')
                    AND ca.situacao = 0
                    AND ca.tipo_cliente = 0
                    AND ca.tipo_funcionario = 'I'
                    GROUP BY ca.id
                    ORDER BY ca.nome) AS aux

                 ORDER BY total DESC, nome_consultor ASC;";
//        echo '<pre>';
//        echo $sql_seleciona;
//        die;



        $qry_seleciona = mysql_query($sql_seleciona, $con);
//        var_dump($sql_seleciona);
//        die;

        if ( mysql_num_rows($qry_seleciona) > 0 ){
        ?>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr valign="top">
            <td colspan="2">
                <table border="0" width="100%" cellpadding="0" cellspacing="1">
                    <tr height="22px" bgcolor="#007cc3" style="font-size:11px; font-weight:bold">
                        <td>&nbsp;Nome</td>
                        <td >&nbsp;Visitas Agendadas</td>
                        <td  align="center">Visitas Realizadas</td>
                        <td  align="center">Cartões Vizinhos</td>
                        <td  align="center">Horario Correto</td>
                        <td  align="center">Demonstração Completa</td>
                        <td align="center">Super Pasta</td>
                        <td align="center">Ligação Gerente</td>
                        <td width="60px" align="center">B�nus</td>
                    </tr>
                    <?php
                    while ( $reg = mysql_fetch_array($qry_seleciona) ){
                    	
//                     print_r($reg);exit;	
                    	
                    $id       = $reg['id'];
                    $cpf      = mascaraCpf($reg['cpf']);
                    $nome     = $reg['nome'];
                    $fone     = $reg['fone'];
                    $celular  = $reg['celular'];

                    if(strlen($celular) == 10) {
                        $cel = telefoneConverte($celular);
                    }elseif(strlen($celular) == 11) {
                        $cel = telefoneConverte2($celular);
                    }else{
                        $cel = $celular;
                    }
                    if(strlen($fone) == 10) {
                        $fon = telefoneConverte($fone);
                    }elseif(strlen($fone) == 11) {
                        $fon = telefoneConverte2($fone);
                    }else{
                        $fon = $fone;
                    }

                    $sit  = $reg['situacao'];
                    if($sit == "0"){
                        $desc = "<font color=green>Ativo</font>";
                    }elseif($sit == "1"){
                        $desc = "<font color=#FFA500>Bloqueado</font>";
                    }elseif($sit == "2"){
                        $desc = "<font color=red>Cancelado</font>";
                    }
                    $fundo = '';
                    $registro++;
                    if (($registro%2) <> 0)
                        $fundo = " bgcolor='#E5E5E5' ";
                    ?>
                    <tr height="21px" style='font-size:10px' <?=$fundo?> align="left" bgcolor="<?=$a_cor[$registro % 2]?>" onMouseOver="set_bgcolor(<?=$registro?>, color_over);" onMouseOut="set_bgcolor(<?=$registro?>, '<?=$a_cor[$registro % 2]?>');" id="row_<?=$registro?>">
                        <td><?php echo $reg['nome_consultor']?></td>
                        <td><?php echo $reg['qtd_visitas']?></td>
                        <td><?php echo $reg['total_visitas']?></td>
                        <td><?php echo $reg['total_cartoes']?></td>
                        <td><?php echo $reg['resultado_visitou']?></td>
                        <td><?php echo $reg['resultado_demonstrou']?></td>
                        <td><?php echo $reg['resultado_levousuper']?></td>
                        <td><?php echo $reg['resultado_ligougerente']?></td>
                        <td width="60px;"><?php echo 'R$ '. number_format($reg['total'],2)?></td>

                     <?php } ?>
                     <?php } ?>
                </table>
                </td>
        </tr>
    </table>
</form>

<script>

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

</script>
