<?php
require "connect/sessao_r.php";

$go = $_POST['go'];

$sql = "select id, atendente from cs2.atendentes WHERE franquia=1 AND situacao = 'A' order by atendente";
$at = mysql_query($sql, $con);
// $fAtendente = mysql_fetch_array($at);

// echo '<pre>';
// print_r($fAtendente);
// die();

if (empty($go)) {
?>
<script type="text/javascript">
//validador
function validaAtendente(){
d = document.cadAtendente;
if (d.atendente.value == ""){
	alert("O nome do atendente deve ser preenchido!");
	d.atendente.focus();
	return false;
}
return true;
}
</script>

<form name="cadAtendente" method="post" action="<?php $_SERVER['PHP_SELF']; ?>" onSubmit="validaAtendente()">
<table border="0" align="center" width="640">
  <tr>
    <td colspan="2" class="titulo">CADASTRO DE ATENDENTES</td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">(*) Preenchimento obrigat&oacute;rio</td>
    </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo da Franquia</td>
    <td class="subtitulopequeno">
		<?php
    	if (($tipo == "a") || ($tipo == "c")) {
			echo "<select name=\"franqueado\" class=boxnormal>";
			$sql = "select * from cs2.franquia where tipo='b' order by id";
			$resposta = mysql_query($sql, $con);
			while ($array = mysql_fetch_array($resposta))
				{
				$franquia   = $array["id"];
				$nome_franquia = $array["fantasia"];
				echo "<option value=\"$franquia\">$nome_franquia</option>\n";
				}
			echo "</select>";
		}
		else {
			echo $nome_franquia;
			echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia; />";
			}
		?> 
        <input type="hidden" name="go" value="cadastrar">
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Atendente</td>
    <td class="subtitulopequeno"><input type="text" name="atendente" size="50" maxlength="50" class="boxnormal" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /> *</td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
</table>
<table align="center">
    <input type="hidden" value="tipo" name="tipo">
        <tr align="center">
        	<td>
            	<input name="submit" type="submit" value="Cadastrar           " />
	        </td>
    	  	<td>
        		<input name="submit2" type="reset" value="             Cancela" />        </td>
    </tr>
  </table>
</form>

<br>
	<?php if($_SESSION['id'] == 1 || $_SESSION['id'] == 4){ ?>
	  <table border="1" class="" id="" width="640" align="center" cellpadding="4" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
		<thead>
		  <tr>
		    <td colspan="2" class="titulo">LISTAGEM DE ATENDENTES</td>
		  </tr>
		  <tr  bgcolor="#CFCFCF">
			<th>Id</th>
			<th>Nome</th>
		  </tr>
		</thead>
		<tbody>
		  <?php
		  $a_cor = array("#eee", "#FFFFFF");
		  $cont=0;
		  while($res = mysql_fetch_array($at)){
			$cont++;
			?>
			<tr bgcolor="<?=$a_cor[$cont % 2]?>">
			  <td><?=$res['id']?></td>
			  <td><?=$res['atendente']?></td>
			</tr>
			<?php
		  }
		  ?>
		</tbody>
	  </table>
  <?php } ?>
<?php
@mysql_free_result($resposta);
} //fim empty go

if ($go == 'cadastrar') {
	$franqueado = $_POST['franqueado'];
	$atendente = $_POST['atendente'];
	
	$sql = "select * from cs2.atendentes where atendente = '$atendente' and franquia = '$franqueado'";
	$qr = mysql_query($sql, $con) or die ("erro ao procurar registros duplicados".mysql_error());
	$linhas = mysql_num_rows($qr);
	if ($linhas > 0) {
		echo "<script>alert('Esse atendente ja foi cadastrado!');history.back()</script>";
		exit;
	} else {
		$sql = "insert into cs2.atendentes (atendente, franquia) values ('$atendente', '$franqueado')";
		$qr = mysql_query($sql, $con) or die ("erro ao inserir atendente".mysql_error());
		echo "<script>alert('Atendente cadastrado com sucesso!');</script>";
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=Franquias/b_relatendente.php\";>";
	}
@mysql_free_result($qr);
}

?>