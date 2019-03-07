<?php
require "connect/sessao.php";

$week = date('W');
$wanted_week = $_GET['wanted_week'];

if (empty($wanted_week)) $sel = $week;
else $sel = $wanted_week;
?>
<script type="text/javascript">
function combo(valor) {
	self.location = "<?php $_SERVER['PHP_SELF']; ?>painel.php?pagina1=ranking/c_ranksemanal.php&wanted_week="+valor;
}
</script>
<form method="post" action="painel.php?pagina1=ranking/c_ranksemanal2.php">
  <table width="70%" border="0" align="center">
    <tr>
      <td colspan="3" class="titulo">RANKING SEMANAL DE VENDAS </td>
    </tr>
    <tr>
      <td width="38%" class="subtitulodireita">&nbsp;</td>
      <td colspan="2" class="campoesquerda">&nbsp;</td>
    </tr>
    <tr>
      <td class="subtitulodireita">Semana</td>
      <td width="22%" class="campoesquerda"><select name="wanted_week" class="boxnormal" onchange="combo(this.value)" >
        <?php
			for ($i = 1; $i <= 52; $i++) {
				echo "<option value=\"$i\"";
				if ($sel == $i) echo " selected";
				echo ">$i&ordm; semana</option>";
			}
			?>
      </select></td>
		<?php if ( $tipo == "a" ){ ?>
      <td colspan="2" class="campoesquerda">
   	  <input type="checkbox" name="automatico" id="automatico"/>Lan&ccedil;ar Cr&eacute;ditos      </td>
		<?php }else{ ?>
        <?php } ?>
    </tr>
    <tr>
      <td class="subtitulodireita">Per&iacute;odo</td>
      <td colspan="2" class="campoesquerda"><?php
		
		if (empty($wanted_week)) $wanted_week = $week;
		
		$week_diff = $wanted_week - $week;
		$ts_week = strtotime("$week_diff week");
		$day_of_week = date('w', $ts_week);
		for ($i = -2; $i <= 5; $i++) {
			// TimeStamp contendo os dias da semana de domingo a sabado
			$ts = strtotime( ($i-$day_of_week)." days", $ts_week );
			if ($i == -2) {
				$primeiro = date('Y-m-d',$ts);
				echo date('d/m/Y', $ts) . " ap�s as 18:00:00 <br>";
			}elseif ($i == 5) {
				$ultimo = date('Y-m-d',$ts);
				echo date('d/m/Y', $ts) . " at� as 18:00:00 <br>";
			}else
				echo date('d/m/Y', $ts) . "<br>";
			
		}
		?>
        <input type="hidden" name="primeiro" value="<?php echo $primeiro; ?>"  />
        <input type="hidden" name="ultimo" value="<?php echo $ultimo; ?>"  /></td>
    </tr>
    <tr>
      <td class="subtitulodireita">&nbsp;</td>
      <td colspan="2" class="campoesquerda"><?php echo $nome_franquia; ?></td>
    </tr>
    <tr>
      <td colspan="3" class="titulo">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center"><input type="submit" name="pesq1" value="    Pesquisar    " />
        <input name="button" type="button" onclick="javascript: history.back();" value="       Voltar       " /></td>
    </tr>
  </table>
</form>