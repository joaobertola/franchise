<?php
require "connect/sessao.php";

$go 	   = $_POST['go'];
$codigo    = $_POST['codigo'];
$tp_libera = $_POST['tp_libera'];

if (empty($go)) {
?>

<script language="javascript">
	//fun��o para aceitar somente numeros em determinados campos
	function mascara(o,f){
	    v_obj=o;
	    v_fun=f;
	    setTimeout("execmascara()",1);
	}
	function execmascara(){
	    v_obj.value=v_fun(v_obj.value);
	}
	function soNumeros(v){
	    return v.replace(/\D/g,"");
	}

	function baixa_lote(){
		frm = document.form;
		frm.action = 'Franquias/ver_lote_confirma.php';
		frm.submit();

	}
</script>

<script type="text/javascript" src="../../../inform/js/prototype.js"></script>
<br>
<form name="form" method="post" action="#">
<table class="table table-striped table-responsive col65" align="center">
	<thead>
		<tr>
			<th colspan="3"><h4 class="text-center">EMISSÃO DE LOTE PREFEITURA</h4> </th>
		</tr>
		<tr>
			<th colspan="3"><h5 class="text-center">NFSe- Curitiba / PR</h5></th>
		</tr>
		<tr>
			<th colspan="3"><h5 class="text-center">Emissão de nota fiscal Avulsa : <a class="btn-link" href="painel.php?pagina1=Franquias/b_notafiscal_avulsa.php">Clique AQUI</a></h5></th>
		</tr>
		<tr>
			<th colspan="3">
				<h5 class="text-center">Informe o faturamento para geração do XML a ser enviado a PREFEITURA</h5>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td width="25%" class="text-right">Faturamento</td>
			<td width="50%" class="text-center">
		<?php
			$sql_sel = "SELECT * FROM cs2.controle_faturamento ORDER BY data_emissao DESC LIMIT 3";
			$qry = mysql_query($sql_sel, $con);
			echo "<select name='id_faturamento' id='id_faturamento' style='width:42%'>";
			while($rs = mysql_fetch_array($qry)) {?>
				<option value="<?=$rs['mesano']?>"><?=substr($rs['mesano'],0,2)?> / <?=substr($rs['mesano'],2,4)?></option>
			<?php
			}
		echo "	</select>";
			?>
				<input type="hidden" name="go" value="ingressar" />	</td>
				<td width="25%" class="text-center"><input type="submit" value="Envia" name="envia" onClick="return check(this.form);" class="botao3d"/></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3">
				<input type="button" onClick="var myAjax = new Ajax.Updater('busca_lote', 'Franquias/ver_lote.php', {method: 'post', parameters: 'foo=bar'})" value=" LIBERACAO DE LOTE " class="botao3d" />
			</td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<td colspan="3">
				<div class="text-center" id="busca_lote">..</div>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3">
				<input name="button" type="button" onClick="javascript: history.back();" value="Voltar" class="botao3d"/>
			</td>
		</tr>
	</tfoot>
</table>
</form>


<?php
} // if go=null

if ($go=='ingressar'){
    try{
            $id_faturamento = $_REQUEST['id_faturamento'];
            echo "<meta http-equiv=\"refresh\" content=\"0; url=http://177.39.53.139/luciano_teste/nota_fiscal.php?id_faturamento=$id_faturamento\";>";
    } catch (Exception $e) {
            echo 'Erro ao efetuar o Download do arquivo XML. ',  $e->getMessage(), "\n";
    }
    echo "ARQUIVO GERADO COM SUCESSO, Aguarde... Estou validando o arquivo junto a PREFEITURA. <br><br>Em caso de ERRO - Contate o CPD imediatamente.";
}


?>