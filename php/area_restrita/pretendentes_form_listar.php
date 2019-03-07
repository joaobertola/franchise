<?php
session_start();
/*
  $name = $_SESSION["ss_name"];
  $tipo = $_SESSION["ss_tipo"];
  if (($name=="") || ($tipo!="a")){
  session_unregister($_SESSION['name']);
  session_destroy();
  echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
  die;
  }
 */

function cpf($cpf) {
    $cpf = substr($cpf, 0, 3) . "." . substr($cpf, 3, 3) . "." . substr($cpf, 6, 3) . "-" . substr($cpf, 9, 2);
    return $cpf;
}

$go = $_REQUEST['go'];
$busca_cpf = '';
if (!empty($go)) {
    $doc = $_REQUEST['doc'];
    $nome = $_REQUEST['nome'];
    $af = $_REQUEST['af'];
    $at = $_REQUEST['at'];
    $t4 = $_REQUEST['t4'];
    $ab = $_REQUEST['ab'];
    $data1 = $_REQUEST['data1'];
    $data1 = implode(preg_match("~\/~", $data1) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data1) == 0 ? "-" : "/", $data1)));
    $data2 = $_REQUEST['data2'];
    $data2 = implode(preg_match("~\/~", $data2) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data2) == 0 ? "-" : "/", $data2)));
    $data_envio1 = $_REQUEST['data_envio1'];
    $data_envio1 = implode(preg_match("~\/~", $data_envio1) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_envio1) == 0 ? "-" : "/", $data_envio1)));
    $data_envio2 = $_REQUEST['data_envio2'];
    $data_envio2 = implode(preg_match("~\/~", $data_envio2) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_envio2) == 0 ? "-" : "/", $data_envio2)));

    $busca_cpf = " WHERE 1=1  ";
    if (!empty($_REQUEST['id_status']))
        $busca_cpf .= " AND status = '{$_REQUEST['id_status']}' ";
    if (!empty($doc))
        $busca_cpf .= " AND cpfcnpj = '$doc' ";
    if (!empty($nome))
        $busca_cpf .= " AND nome LIKE '%$nome%' ";
    if ($af == 0)
        $busca_cpf .= " AND apresenta IS NOT NULL ";
    if ($af == 1)
        $busca_cpf .= " AND apresenta IS NULL ";
    if (!empty($at))
        $busca_cpf .= " AND agenda_treina between '$data1' AND '$data2' ";

    if ($data_envio1) {
        $busca_cpf .= " AND data_envio >= '$data_envio1' ";
    }
    if ($data_envio2) {
        $busca_cpf .= " AND data_envio <= '$data_envio2' ";
    }
}

$busca_cpf .= " AND data_envio > '2017-01-01' ";

$sql = "SELECT id, nome, cpfcnpj, cidade, uf, date_format(data_envio,'%d/%m/%Y') AS envio, 
		UPPER(cid_atuacao) AS cid_atuacao, apresenta, agenda_treina, treina, abertura, 
		date_format(data_retorno,'%d/%m/%Y') AS data_retorno, hora_retorno, email
		FROM cs2.pretendentes $busca_cpf ORDER BY data_envio DESC, nome ASC";
$res = mysql_query($sql, $con);
$linhas = mysql_num_rows($res);
?>

<script language="javascript">
    ok = false;
    function CheckAll() {
        if (!ok) {
            for (var i = 0; i < document.form1.elements.length; i++) {
                var x = document.form1.elements[i];
                if (x.name == 'selected[]') {
                    x.checked = true;
                    ok = true;
                }
            }
        } else {
            for (var i = 0; i < document.form1.elements.length; i++) {
                var x = document.form1.elements[i];
                if (x.name == 'selected[]') {
                    x.checked = false;
                    ok = false;
                }
            }
        }
    }

    function novo() {
        frm = document.form1;
        frm.action = 'painel.php?pagina1=area_restrita/d_pretendentes.php';
        frm.submit();
    }

    function excluir() {
        frm = document.form1;
        frm.action = 'painel.php?pagina1=area_restrita/d_exc_pretendente.php';
        frm.submit();
    }

    function contaCheckbox(selecionados) {
        var inputs, x, selecionados = 0;
        inputs = document.getElementsByTagName('input');
        for (x = 0; x < inputs.length; x++) {
            if (inputs[x].type == 'checkbox') {
                if (inputs[x].checked == true && inputs[x].id == 'selected') {
                    selecionados++;
                }
            }
        }
        return selecionados;
    }

    function email() {
        var total;
        total = contaCheckbox();
        if (total > 0) {
            frm = document.form1;
            frm.action = 'painel.php?pagina1=area_restrita/pretendentes_mostra_texto.php';
            frm.submit();
        } else {
            alert("Selecione pelo menos um pretendente para enviar e-mail ! ");
        }
    }
</script>
<?php include("pretendentes_form.php"); ?>
<style>
    .botao3d{background:rgba(1, 124, 194, 0.8);padding:5px 10px;border-radius:5px;}
    .botao3d:hover{background:rgba(1,124,194,1);}
</style>
<?php if ($linhas > 0) { ?>
    <form id="form1" name="form1" method="post" action="#">
        <input type="hidden" name="doc" value="<?= $_REQUEST['doc'] ?>">
        <input type="hidden" name="go" value="<?= $_REQUEST['go'] ?>">
        <input type="hidden" name="nome" value="<?= $_REQUEST['nome'] ?>">
        <input type="hidden" name="af" value="<?= $_REQUEST['af'] ?>">
        <input type="hidden" name="at" value="<?= $_REQUEST['at'] ?>">
        <input type="hidden" name="data1" value="<?= $_REQUEST['data1'] ?>">
        <input type="hidden" name="data2" value="<?= $_REQUEST['data2'] ?>">
        <input type="hidden" name="data_envio1" value="<?= $_REQUEST['data_envio1'] ?>">
        <input type="hidden" name="data_envio2" value="<?= $_REQUEST['data_envio2'] ?>">
        <input type="hidden" name="id_status" value="<?= $_REQUEST['id_status'] ?>">

        <table border="0" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF" class="bodyText">

            <tr><td colspan="9" bgcolor="#999999"></td></tr>

            <tr height="25" bgcolor="#eeeeee" align="center">
                <td><input type="checkbox"  onClick="CheckAll();" style="cursor:pointer"></td>
                <td><b>Contato</b></td>
                <td><b>Nome</b></td>
                <td><b>Cidade</b></td>
                <td><b>UF</b></td>
                <td><b>Cidade Pretendida</b></td>
                <td><b>Data</b></td>
                <td><b>Hora</b></td>
                <td><b>Apres. Fone</b></td>
                <td><b>Agendado Treinamento</b></td>	
            </tr>

            <tr><td colspan="9" bgcolor="#666666"></td></tr>
            <?php
            for ($a = 0; $a < $linhas; $a++) {
                $matriz = mysql_fetch_array($res);
                $id = $matriz['id'];
                $nome = $matriz['nome'];

                if ($matriz['data_retorno'] == "00/00/0000") {
                    $data_retorno = "";
                } else {
                    $data_retorno = $matriz['data_retorno'];
                }
                unset($hora_retorno);
                if ($matriz['hora_retorno'] == "00:00:00") {
                    $data_retorno = "";
                } else {
                    $hora_retorno = substr($matriz['hora_retorno'], 0, 5);
                }

                $cpfcnpj = $matriz['cpfcnpj'];
                if (!empty($cpfcnpj))
                    $cpfcnpj = cpf($cpfcnpj);
                $cidade = $matriz['cidade'];
                $cid_atuacao = $matriz['cid_atuacao'];
                $uf = $matriz['uf'];
                $data_envio = $matriz['envio'];
                $apresenta = $matriz['apresenta'];
                $agenda_treina = $matriz['agenda_treina'];
                $treina = $matriz['treina'];
                $abertura = $matriz['abertura'];
                $string = $nome;
                $limite = 20;
                $sizeName = strlen($string);
                //
                $string0 = $cid_atuacao;
                $limite0 = 25;
                $sizeName0 = strlen($string0);

                echo "<tr height=\"22\"";
                if (($a % 2) != 0) {
                    echo "bgcolor=\"#E5E5E5\">";
                } else {
                    echo ">";
                }
                echo "<td class=\"tabela\" align=center>";

                if (!empty($matriz['email'])) {
                    echo "<input name=\"selected[]\" id=\"selected\" type=\"checkbox\" value=\"$id\" />";
                } else {
                    echo "<input name=\"selected[]\" id=\"selected\" type=\"checkbox\" value=\"$id\" disabled=\"disabled\" title=\"E-mail em branco\"/>";
                }

                echo "</td><td class=\"tabela\" align=center>$data_envio</td>

	  	      	<td class=\"tabela\" align=\"left\">&nbsp;<a href=\"painel.php?pagina1=area_restrita/d_pretendentes.php&id=$id\"><font color=\"#0000ff\">";
                for ($num = 0; $num < $limite; $num++) {
                    print($string[$num]);
                }
                if ($sizeName > $limite) {
                    echo"...";
                }
                echo "</font></a></td>
				<td class=\"tabela\">$cidade</td>
				<td align=center>$uf</td>";
                echo "</td>
	  	      	<td class=\"tabela\" align=\"left\">&nbsp;<font color=\"#ff0000\">";
                for ($num0 = 0; $num0 < $limite0; $num0++) {
                    print($string0[$num0]);
                }
                if ($sizeName0 > $limite0) {
                    echo"...";
                }

                echo "<td class=\"tabela\" align=center>$data_retorno</td>";
                echo "<td class=\"tabela\" align=center>$hora_retorno</td>";

                echo "</font></td>
				<td class=\"tabela\" align=center><input type=radio ";
                if ($apresenta != 0)
                    echo "checked";
                echo "></td>";

                if ($agenda_treina != "") {
                    $checked = "checked ";
                } else {
                    $checked = " ";
                }
                echo "<td class=\"tabela\" align='center'><input type='radio' $checked></td>";

                echo "</tr>";
            } //fim for
            echo "<tr class=\"subtitulodireita\">
				<td colspan=\"10\" align='right'>Total de pretendentes encontrados <b>$a&nbsp;&nbsp;</b></td>
			  </tr>
			
			<tr><td colspan=\"10\" height=\"1\" bgcolor=\"#666666\"></td></tr>
			
			<tr align=\"center\">
				<td colspan=\"4\"><input class=\"botao3d\" type=\"button\" value=\"Excluir Registros Selecionados\" onClick=\"excluir()\" style=\"cursor:pointer\" /></td>				
				<td colspan=\"3\"><input class=\"botao3d\" type=\"button\" value=\"Novo Pretendente\" onClick=\"novo()\"  style=\"cursor:pointer\"/></td>				
				<td colspan=\"3\"><input class='botao3d' type='button' value='Enviar E-mail' onClick='email()'  style='cursor:pointer'></td>
			</tr>			
		</table></form>";

            $res = mysql_close($con);
        } else {
            ?>
            <div align="center"><b>Nenhum item encontrado para este filtro de pesquisa !</b></div>
            <?php
        }
        ?>
        <p>&nbsp;</p>