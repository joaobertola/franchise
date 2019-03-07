<?php
require "connect/sessao.php";

$week = date('W');
$wanted_week = $_GET['wanted_week'];

if (empty($wanted_week)) $sel = $week;
else $sel = $wanted_week;
?>
<script type="text/javascript">
function combo(valor) {
	self.location = "<?php $_SERVER['PHP_SELF']; ?>painel.php?pagina1=franqueado_jr/ranking_semanal.php&wanted_week="+valor;
}
</script>
<form method="post" action="painel.php?pagina1=franqueado_jr/ranking_semanal_ver.php">
<table width="70%" border="0" align="center">
  <tr>
    <td colspan="2" class="titulo">RANKING SEMANAL DE VENDAS ( Franqueado JUNIOR )</td>
  </tr>
  <tr>
    <td width="38%" class="subtitulodireita">&nbsp;</td>
    <td width="62%" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Semana</td>
    <td class="campoesquerda">
		<select name="wanted_week" class="boxnormal" onchange="combo(this.value)" >
			<?php
			for ($i = 1; $i <= 52; $i++) {
				echo "<option value=\"$i\"";
				if ($sel == $i) echo " selected";
				echo ">$i&ordm; semana</option>";
			}
			?>
		</select>	 </td>
  </tr>
  
  
  <tr>
    <td class="subtitulodireita">Per&iacute;odo</td>
    <td class="campoesquerda">
		<?php
		
		if (empty($wanted_week)) $wanted_week = $week;
		
		$week_diff = $wanted_week - $week;
		$ts_week = strtotime("$week_diff week");
		$day_of_week = date('w', $ts_week);
		for ($i = -1; $i <= 5; $i++) {
			// TimeStamp contendo os dias da semana de domingo a sabado
			$ts = strtotime( ($i-$day_of_week)." days", $ts_week );
			if ($i == -1) $primeiro = date('Y-m-d',$ts);
			if ($i == 5) $ultimo = date('Y-m-d',$ts);
			echo date('d/m/Y', $ts) . "<br>";
		}
		?>
		<input type="hidden" name="primeiro" value="<?php echo $primeiro; ?>"  />
		<input type="hidden" name="ultimo" value="<?php echo $ultimo; ?>"  />
	</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><input type="submit" name="pesq1" value="    Pesquisar    " />
    <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
  </tr>
</table>
</form>