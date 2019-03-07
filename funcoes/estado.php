<?php
require_once('conexao.php');
?>

<select name="uf" class="boxnormal" onfocus="this.className='boxover'" onblur="this.className='boxnormal';" style="width:25%" id="uf" />
<option value=""></option>
<?php                                   
	$sql = "SELECT sigla, descricao FROM base_web_control.estado ORDER BY descricao ASC";
	$qry = mysql_query($sql);
	while($rs = mysql_fetch_array($qry))
	{
		$sigla     = $rs['sigla'];
		$descricao = $rs['descricao'];
		if($sigla == $uf) {
		echo "<option value='$sigla' selected>$descricao</option>";
		} else {
		echo "<option value='$sigla'>$descricao</option>";
		}
	}
?>
</select>