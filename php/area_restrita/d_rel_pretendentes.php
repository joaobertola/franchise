<?php
session_start();

$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];
/*
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

$go = $_POST['go'];

if (!empty($go)) {
    $doc = $_POST['doc'];
    $nome = $_POST['nome'];
    $af = $_POST['af'];
    $at = $_POST['at'];
    $t4 = $_POST['t4'];
    $ab = $_POST['ab'];
    $data1 = $_POST['data1'];
    $data1 = implode(preg_match("~\/~", $data1) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data1) == 0 ? "-" : "/", $data1)));
    $data2 = $_POST['data2'];
    $data2 = implode(preg_match("~\/~", $data2) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data2) == 0 ? "-" : "/", $data2)));
    $data3 = $_POST['data3'];
    $data3 = implode(preg_match("~\/~", $data3) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data3) == 0 ? "-" : "/", $data3)));
    $data4 = $_POST['data4'];
    $data4 = implode(preg_match("~\/~", $data4) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data4) == 0 ? "-" : "/", $data4)));

    $busca_cpf = "where cpfcnpj = '$doc'";
    if ((empty($doc)) && (!empty($nome)))
        $busca_cpf = "where nome like '%$nome%'";
    elseif (empty($doc))
        $busca_cpf = "where cpfcnpj = cpfcnpj";
    if (empty($af))
        $busca_cpf .= " and apresenta is not null";
    elseif ($af == 1)
        $busca_cpf .= " and apresenta is null";

    if (isset($at))
        $busca_cpf .= " and agenda_treina between '$data1' and '$data2'";
    if (isset($t4))
        $busca_cpf .= " and treina between '$data3' and '$data4'";
    if (isset($ab))
        $busca_cpf .= " and abertura is not null";
}
$ref = '>';
if (isset($_GET['antiga'])) {
    $ref = '<';
}
$total_colspan = "8";
$sql = "select id, nome, cpfcnpj, cidade, uf, date_format(data_envio,'%d/%m/%Y') as envio, upper(cid_atuacao) as cid_atuacao, apresenta, agenda_treina, treina, abertura, date_format(data_retorno,'%d/%m/%Y') as data_retorno, hora_retorno from cs2.pretendentes $busca_cpf WHERE data_envio {$ref} '2017-02-08' order by data_envio desc, nome asc";
$res = mysql_query($sql, $con);
$linhas = mysql_num_rows($res);
$linhas1 = $linhas + 3;

if ($linhas == "0") {
    echo "<table width=\"80%\">
			<tr>
				<td class=titulo align=center width=\"100%\">Nenhum item encontrado para este filtro de pesquisa!</td>
			</tr>
		  </table>";
} else {
    ?>
    <script>
        function mascara(o, f) {
            v_obj = o
            v_fun = f
            setTimeout("execmascara()", 1)
        }

        function execmascara() {
            v_obj.value = v_fun(v_obj.value)
        }

        // formato mascara data
        function data(v) {
            v = v.replace(/\D/g, "")                    //Remove tudo o que nao é digito
            v = v.replace(/(\d{2})(\d)/, "$1/$2")
            v = v.replace(/(\d{2})(\d)/, "$1/$2")

            return v
        }
    </script>
    <?php
    if ($_REQUEST['af'] == 1) {
        $total_colspan = "10";
    }
    echo "<table width=\"100%\">
			<tr><td class=titulo>PRETENDENTES A FRANQUEADOS</td></tr>
		  </table><br>";
    echo "<form method=post action=\"" . $_SERVER['PHP_SELF'] . "?pagina1=area_restrita/d_rel_pretendentes.php\" >";
    echo "<table width=60% align=center>
			<tr>
				<td>
					<table  width=\"100%\">
						<tr><td class=titulo colspan=3>Procurar</td></tr>
						<tr>
							<td class=subtitulodireita>CPF:</td>
							<td class=subtitulopequeno colspan=2>
								<input type=text name=doc onkeypress=\"soNumero()\" maxlength=\"11\"  class=\"boxnormal\" onFocus=\"this.className='boxover'\" onBlur=\"this.className='boxnormal'\">
								<input type=hidden value=\"filtrar\" name=\"go\" >
							</td>
						</tr>
						<tr>
							<td class=subtitulodireita>Nome:</td>
							<td class=subtitulopequeno colspan=2>
								<input type=text name=nome maxlength=\"20\"  class=\"boxnormal\" onFocus=\"this.className='boxover'\" onBlur=\"this.className='boxnormal'\">
							</td>
						</tr>
						<tr>
							<td class=subtitulodireita width=40%>Apresentado Fone?</td>
							<td class=subtitulopequeno colspan=2><select name=af class=boxnormal>
								<option>Seleciona</option>
								<option value=1>Nao</option>
								<option value=0>Sim</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class=subtitulodireita>Agendado Treinamento?</td>
							<td class=subtitulopequeno><input type=checkbox name=at></td>
							<td class=subtitulopequeno><input type=text name=data1 id=data1 size=12 onKeyPress=\"formatar('##/##/####', this); soNumero()\" maxlength=10> a <input type=text name=data2 id=data2 size=12 onKeyPress=\"formatar('##/##/####', this); soNumero()\" maxlength=10></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align=center><input type=submit value=\"Procurar\"></td>
			</tr>
                        <tr>
                            <td colspan='2'> 
                                    <a href='https://webcontrolempresas.com.br/franquias/php/painel.php?pagina1=area_restrita/d_rel_pretendentes.php" . (isset($_GET['antiga']) ? '' : '&antiga') . "' style='float:right;background:rgba(1, 124, 194, 0.8);color:#FFF;padding:10px;border-radius:5px;margin:5px;font-size:11px;'>" . (isset($_GET['antiga']) ? 'Visualizar Novos' : 'Visualizar Antigas') . "</a>
                            </td>
                        </tr>
			</table>
		  </form>";
    echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\">
	 		<tr>
				<td colspan=\"$total_colspan\" height=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\" bgcolor=\"#eeeeee\">
				<td>&nbsp;</td>
				<td align=center>Data de contato</td>

				<td align=center>Nome</td>
				<td align=center>Cidade</td>
				<td align=center>UF</td>
				<td align=center><strong>Cidade Pretendida</strong></td>";

    if ($_REQUEST['af'] == 1) {
        echo "<td align=center><strong>Data</strong></td><td align=center><strong>Hora</strong></td>";
    }

    echo "<td align=center>Apres. Fone</td>
				<td align=center>Agendado Treinamento</td>";
    echo "	
			</tr>
			<tr>
				<td colspan=\"8\" height=\"1\" bgcolor=\"#666666\"></td>
			</tr>
			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"painel.php?pagina1=area_restrita/d_exc_pretendente.php\">";
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
        echo " 	<td class=\"tabela\" align=center><input name=\"selected[]\" type=\"checkbox\" value=\"$id\" /></td>
				<td class=\"tabela\" align=center>$data_envio</td>

	  	      	<td class=\"tabela\" align=\"left\">&nbsp;<a href=\"painel.php?pagina1=area_restrita/d_pretendentes.php&id=$id\"><font color=\"#0000ff\">";
        for ($num = 0; $num < $limite; $num++) {
            print(strtoupper($string[$num]));
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

        if ($_REQUEST['af'] == 1) {
            echo "<td class=\"tabela\" align=center>$data_retorno </td>";
            echo "<td class=\"tabela\" align=center>$hora_retorno</td>";
        }

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
				<td></td>
				<td colspan=\"6\">Número de total de pretendentes</td>
				<td><b>$a&nbsp;&nbsp;</b></td>
				<td></td>
			</tr>
			<tr>
				<td colspan=\"8\" height=\"1\" bgcolor=\"#666666\"></td>
			</tr>
			<tr>
				<td></td>
				<td colspan=\"8\" align=\"right\">
				<input  style='background:rgba(1, 124, 194, 0.8);color:#FFF;padding:10px;border-radius:5px;margin:5px;font-size:11px;' class=\"botao3d\" type=\"submit\" value=\"Excluir registros selecionados\" />
				</td>
			</tr>
			</form>
		</table>
		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\">
			<tr>
				<td align=right>
					<input  style='float:right;background:rgba(1, 124, 194, 0.8);color:#FFF;padding:10px;border-radius:5px;margin:5px;font-size:11px;' class=\"botao3d\" type=\"button\" value=\"Novo Pretendente\" onClick=\"document.location.href='painel.php?pagina1=area_restrita/d_pretendentes.php'\" />
				</td>
			</tr>
		</table>";
}
$res = mysql_close($con);
?>
<p>&nbsp;</p>