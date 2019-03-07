<?php
require "connect/sessao.php";

$week = date('W');

$wanted_week = $_GET['wanted_week'];

if (empty($wanted_week)) $sel = $week;
else $sel = $wanted_week;

$ano_esc = $_REQUEST['ano_esc'];
if(empty($ano_esc)){
	$ano_esc = date('Y');
}else{
	$ano_esc = $_REQUEST['ano_esc'];
}

$abre_form = $_REQUEST['abre_form'];
if(empty($abre_form)){
	$abre_form = "1";
}else{
	$abre_form = $_REQUEST['abre_form'];
}

//echo "<pre>";
//print_r($_REQUEST);

?>
<script type="text/javascript">
function combo(valor) {
	self.location = "<?php $_SERVER['PHP_SELF']; ?>painel.php?pagina1=clientes/c_ranksemanal.php&wanted_week="+valor+"&abre_form="+2+"&ano_esc=<?=$_REQUEST['ano_esc']?>";
}

function listarSemana(){
	frm = document.form2;
	frm.action = 'painel.php?pagina1=ranking/c_ranksemanal.php&abre_form=2';
	frm.submit();
}

function vaiPraSegundoForm(){
	frm = document.form;
    frm.action = 'painel.php?pagina1=ranking/c_ranksemanal.php&abre_form=2';
	frm.submit();
}
 
function vaiPraPrimeiroForm(){
	frm = document.form2;
    frm.action = 'painel.php?pagina1=ranking/c_ranksemanal.php&abre_form=1';
	frm.submit();
}

function relatorio(){ 	
	frm = document.form2;
    frm.action = 'painel.php?pagina1=ranking/c_ranksemanal2.php';
	frm.submit();
}

</script>
<?php if($abre_form == 1) { ?>
<form method="post" action="#" name="form">
<!-- painel.php?pagina1=ranking/c_ranksemanal2.php -->
  <table class="table table-striped table-responsive col60" align="center">
	  <thead>
		<tr>
		  <th colspan="3"><h4 class="text-center"> Relatorio de Tarefas Gerenciais</h4></th>
		</tr>
	  </thead>
	  <tbody>
		<tr>
		  <td width="38%" class="text-right">&nbsp;</td>
		  <td colspan="2">&nbsp;</td>
		</tr>

		 <tr>
		  <td width="38%" class="text-right">Ano</td>
		  <td colspan="2">
			<select name="ano_esc" class="boxnormal" style="width:20%">
				<?php
					$ano = date('Y');
					for ( $i = 1 ; $i <= 5 ; $i++ ){
						echo "<option value='$ano'>$ano</option>";
						$ano--;
					}
				?>
			</select>
		  </td>
		</tr>
	  </tbody>
	  <tfoot>
		<tr>
		  <td>&nbsp;</td>
		  <td colspan="2"><input type="button" name="Confirma" value="Confirma" onclick="vaiPraSegundoForm()" class="botao3d" />
		 </td>
		</tr>
	  </tfoot>
 </table>
</form>
<?php } ?>

<?php if($abre_form == 2) {
	
	 ?>
<form method="post" action="#" name="form2">
<input type="hidden" name="ano_esc" value="<?=$_REQUEST['ano_esc']?>">
  <table class="table table-striped table-responsive col60" align="center">
    <tr>
      <td class="text-right">Semana</td>
      <td width="22%">
       <!-- select name="wanted_week" class="boxnormal" onchange="combo(this.value)" -->
       <select name="wanted_week" class="boxnormal" onchange="combo(this.value)">
       <?php
			for ($i = 1; $i <= 52; $i++) {
				echo "<option value=\"$i\"";
				if ($sel == $i) echo " selected";
				echo ">$i&ordm; semana</option>";
	   		}
	  ?>
      </select>
      </td>
		<?php if ( $tipo == "a" ){ ?>
      <td width="40%">
      	<input type="checkbox" name="automatico" id="automatico"/>Lan&ccedil;ar Cr&eacute;ditos
      </td>
		<?php }else{ ?>
        <td width="40%">&nbsp;</td>
        <?php } ?>
    </tr>
    
    <tr>
      <td class="text-right">Per&iacute;odo</td>
      <td colspan="2"><?php
		

		if (empty($wanted_week)) $wanted_week = $week;
	
		$week_diff = $wanted_week - $week;

		$ts_week = strtotime("$week_diff week");

		$day_of_week = date('w', $ts_week);
		

		//VERIFICA SE O ANO �BISSEXTO
		if ((($ano_esc % 4) == 0 and ($ano_esc % 100)!=0) or ($ano_esc % 400)==0){
			$bissexto = TRUE;
		}else{
			$bissexto = FALSE;
		}


		if(($_REQUEST['wanted_week'] == 1) and ($bissexto == TRUE) ){
			$fim = 5;
			$inicio = -2;
		}elseif($bissexto == TRUE){
			$fim = 5;
			$inicio = -2;
		}else{
			//$fim = 7;
			//$inicio = 0;
			
			$fim = 5;
			$inicio = -2;
			
			
		}
			//for ($i = -2; $i <= 5; $i++) {
			for ($i = $inicio; $i <= $fim; $i++) {
			
			// TimeStamp contendo os dias da semana de domingo a sabado
			$ts = strtotime( ( $i - $day_of_week )." days", $ts_week );
	
			//if ($i == -2) {
			if ($i == $inicio) {
			

				$primeiro = date($ano_esc."-m-d",$ts);
				if($sel == "01"){
					$_ano_esc = $ano_esc - 1;
					echo date("d/m/".$_ano_esc, $ts) . " ap&oacute;s 18:00:00 <br>";
				}else{
					echo date("d/m/".$ano_esc, $ts) . " ap&oacute;s 18:00:00 <br>";
				}	
			//}elseif ($i == 5) {			
			  }elseif ($i == $fim) {			
				$ultimo = date($ano_esc."-m-d",$ts);
				echo date("d/m/".$ano_esc, $ts) . " at&eacute; as 18:00:00 <br>";
			}else
				echo date("d/m/".$ano_esc, $ts) . "<br>";							
		}
		
		if($sel == "01"){
			$tmp = substr($primeiro, 0,4);
			$tmp = $tmp - 1;
			$dt_tmp = substr($primeiro, 4,6);
			$primeiro = $tmp;
			$primeiro .= $dt_tmp;
		}
		?>
        <input type="hidden" name="primeiro" value="<?=$primeiro?>" />
        <input type="hidden" name="ultimo" value="<?=$ultimo?>" /></td>
    </tr>
    <tr>
      <td class="text-right">&nbsp;</td>
      <td colspan="2"><?php echo $nome_franquia; ?></td>
    </tr>
    <tr>
      <td colspan="3" class="titulo">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="left">
        <input type="button" name="pesq1" value="    Pesquisar    " onclick="relatorio()" />
        &nbsp;&nbsp;
        <input name="button" type="button" onclick="vaiPraPrimeiroForm()" value="       Escolher Outro Ano       " />
     </td> 
    </tr>
  </table>
</form>
 <?php } ?>

