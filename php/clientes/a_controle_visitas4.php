<?php
require "connect/sessao.php";

//echo "<pre>";
//print_r( $_REQUEST );

if ( $_REQUEST['rel_franquia'] == '' )
	$id_franquia = $_SESSION['id'];
else
	$id_franquia = $_REQUEST['rel_franquia'];

if (  $id_franquia == 1 or $id_franquia == 163 or $id_franquia == 46 or $id_franquia == 59 )
	$id_franquia = 247;

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

?>
<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>

<script type="text/javascript">

function combo(valor) {
	self.location = "<?php $_SERVER['PHP_SELF']; ?>painel.php?pagina1=clientes/a_controle_visitas4.php&wanted_week="+valor+"&abre_form="+2+"&ano_esc=<?php echo $_REQUEST['ano_esc']?>"+"&rel_franquia=<?php echo $id_franquia?>";
}

var win = null;
function NovaJanela(pagina,nome,w,h,scroll){
	pagina = 'clientes/fotos_franquias/'+pagina;
	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
	win = window.open(pagina,nome,settings);
}

function vaiPraSegundoForm(){
	frm = document.form;
    frm.action = 'painel.php?pagina1=clientes/a_controle_visitas4.php&abre_form=2';
	frm.submit();
}

function vaiPraPrimeiroForm(){
	frm = document.form;
    frm.action = 'painel.php?pagina1=clientes/a_controle_visitas4.php&abre_form=1';
	frm.submit();
}



</script>

<script type="text/javascript">
/* Funçao jQuery para remover linha */
$.removeLinha1 = function (element)
{
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir tr').length;
	/* Condiçao que mantem pelo menos uma linha na tabela */
	if (linha_total > 1)
	{
		/* Remove os elementos da linha onde esta o botao clicado */
		$(element).parent().parent().remove();
	}
	/* Avisa usuario de que nao pode remover a ultima linha */
	else
	{
		alert("Desculpe, mas você não pode remover esta última linha!");
	}
};
/* Quando o documento estiver carregado */
$.adicionaLinha1 = function (element)
{
	/* Variável que armazena limite de linhas (zero é ilimitada) */
	var limite_linhas = 3;
	/* Quando o botao adicionar for clicado... */
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir tr').length;
	/* Condicao que verifica se existe limite de linhas e, se existir, testa se usuario atingiu limite */
	if (limite_linhas && limite_linhas > linha_total)
	{
		/* Pega uma linha existente */
		var linha = $('tbody#repetir tr').html();
		/* Conta quantidade de linhas na tabela */
		var linha_total = $('tbody#repetir tr').length;
		/* Pega a ID da linha atual */
		var linha_id = $('tbody#repetir tr').attr('id');
		/* Acrescenta uma nova linha, incluindo a nova ID da linha */
		$('tbody#repetir').append('<tr id="linha_' + (linha_total + 1) + '">' + linha + '</tr>');
	}
	/* Se usuario atingiu limite de linhas */
	else
	{
		alert("Desculpe, mas você só pode adicionar até " + limite_linhas + " linhas!");
	}
};
$.removeLinha2 = function (element)
{
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir2 tr').length;
	/* Condicaoo que mantem pelo menos uma linha na tabela */
	if (linha_total > 1)
	{
		/* Remove os elementos da linha onde esta o botao clicado */
		$(element).parent().parent().remove();
	}
	/* Avisa usuario de que nao pode remover a ultima linha */
	else
	{
		alert("Desculpe, mas você não pode remover esta última linha!");
	}
};
/* Quando o documento estiver carregado */
$.adicionaLinha2 = function (element)
{
	/* Varia�vel que armazena limite de linhas (zero � ilimitada) */
	var limite_linhas = 3;
	/* Quando o bot�o adicionar for clicado... */
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir2 tr').length;
	/* Condi��o que verifica se existe limite de linhas e, se existir, testa se usu�rio atingiu limite */
	if (limite_linhas && limite_linhas > linha_total)
	{
		/* Pega uma linha existente */
		var linha = $('tbody#repetir2 tr').html();
		/* Conta quantidade de linhas na tabela */
		var linha_total = $('tbody#repetir2 tr').length;
		/* Pega a ID da linha atual */
		var linha_id = $('tbody#repetir2 tr').attr('id');
		/* Acrescenta uma nova linha, incluindo a nova ID da linha */
		$('tbody#repetir2').append('<tr id="linha_' + (linha_total + 1) + '">' + linha + '</tr>');
	}
	/* Se usu�rio atingiu limite de linhas� */
	else
	{
		alert("Desculpe, mas você só pode adicionar até " + limite_linhas + " linhas!");
	}
};
$.removeLinha3 = function (element)
{
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir3 tr').length;
	/* Condi��o que mant�m pelo menos uma linha na tabela */
	if (linha_total > 1)
	{
		/* Remove os elementos da linha onde est� o bot�o clicado */
		$(element).parent().parent().remove();
	}
	/* Avisa usu�rio de que n�o pode remover a �ltima linha */
	else
	{
		alert("Desculpe, mas você não pode remover esta última linha!");
	}
};
/* Quando o documento estiver carregado� */
$.adicionaLinha3 = function (element)
{
	/* Vari�vel que armazena limite de linhas (zero � ilimitada) */
	var limite_linhas = 3;
	/* Quando o bot�o adicionar for clicado... */
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir3 tr').length;
	/* Condi��o que verifica se existe limite de linhas e, se existir, testa se usu�rio atingiu limite */
	if (limite_linhas && limite_linhas > linha_total)
	{
		/* Pega uma linha existente */
		var linha = $('tbody#repetir3 tr').html();
		/* Conta quantidade de linhas na tabela */
		var linha_total = $('tbody#repetir3 tr').length;
		/* Pega a ID da linha atual */
		var linha_id = $('tbody#repetir3 tr').attr('id');
		/* Acrescenta uma nova linha, incluindo a nova ID da linha */
		$('tbody#repetir3').append('<tr id="linha_' + (linha_total + 1) + '">' + linha + '</tr>');
	}
	/* Se usu�rio atingiu limite de linhas� */
	else
	{
		alert("Desculpe, mas você só pode adicionar até " + limite_linhas + " linhas!");
	}
};
$.removeLinha3 = function (element)
{
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir3 tr').length;
	/* Condi��o que mant�m pelo menos uma linha na tabela */
	if (linha_total > 1)
	{
		/* Remove os elementos da linha onde est� o bot�o clicado */
		$(element).parent().parent().remove();
	}
	/* Avisa usu�rio de que n�o pode remover a �ltima linha */
	else
	{
		alert("Desculpe, mas você não pode remover esta última linha!");
	}
};
/* Quando o documento estiver carregado� */
$.adicionaLinha4 = function (element)
{
	/* Vari�vel que armazena limite de linhas (zero � ilimitada) */
	var limite_linhas = 3;
	/* Quando o bot�o adicionar for clicado... */
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir4 tr').length;
	/* Condi��o que verifica se existe limite de linhas e, se existir, testa se usu�rio atingiu limite */
	if (limite_linhas && limite_linhas > linha_total)
	{
		/* Pega uma linha existente */
		var linha = $('tbody#repetir4 tr').html();
		/* Conta quantidade de linhas na tabela */
		var linha_total = $('tbody#repetir4 tr').length;
		/* Pega a ID da linha atual */
		var linha_id = $('tbody#repetir4 tr').attr('id');
		/* Acrescenta uma nova linha, incluindo a nova ID da linha */
		$('tbody#repetir4').append('<tr id="linha_' + (linha_total + 1) + '">' + linha + '</tr>');
	}
	/* Se usu�rio atingiu limite de linhas� */
	else
	{
		alert("Desculpe, mas você só pode adicionar até " + limite_linhas + " linhas!");
	}
};
$.removeLinha4 = function (element)
{
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir4 tr').length;
	/* Condi��o que mant�m pelo menos uma linha na tabela */
	if (linha_total > 1)
	{
		/* Remove os elementos da linha onde est� o bot�o clicado */
		$(element).parent().parent().remove();
	}
	/* Avisa usu�rio de que n�o pode remover a �ltima linha */
	else
	{
		alert("Desculpe, mas você não pode remover esta última linha!");
	}
};

/* Quando o documento estiver carregado� */
$.adicionaLinha5 = function (element)
{
	/* Vari�vel que armazena limite de linhas (zero � ilimitada) */
	var limite_linhas = 3;
	/* Quando o bot�o adicionar for clicado... */
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir5 tr').length;
	/* Condi��o que verifica se existe limite de linhas e, se existir, testa se usu�rio atingiu limite */
	if (limite_linhas && limite_linhas > linha_total)
	{
		/* Pega uma linha existente */
		var linha = $('tbody#repetir5 tr').html();
		/* Conta quantidade de linhas na tabela */
		var linha_total = $('tbody#repetir5 tr').length;
		/* Pega a ID da linha atual */
		var linha_id = $('tbody#repetir5 tr').attr('id');
		/* Acrescenta uma nova linha, incluindo a nova ID da linha */
		$('tbody#repetir5').append('<tr id="linha_' + (linha_total + 1) + '">' + linha + '</tr>');
	}
	/* Se usu�rio atingiu limite de linhas� */
	else
	{
		alert("Desculpe, mas você só pode adicionar até " + limite_linhas + " linhas!");
	}
};
$.removeLinha5 = function (element)
{
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir5 tr').length;
	/* Condi��o que mant�m pelo menos uma linha na tabela */
	if (linha_total > 1)
	{
		/* Remove os elementos da linha onde est� o bot�o clicado */
		$(element).parent().parent().remove();
	}
	/* Avisa usu�rio de que n�o pode remover a �ltima linha */
	else
	{
		alert("Desculpe, mas você não pode remover esta última linha!");
	}
};

/* Quando o documento estiver carregado� */
$.adicionaLinha6 = function (element)
{
	/* Vari�vel que armazena limite de linhas (zero � ilimitada) */
	var limite_linhas = 3;
	/* Quando o bot�o adicionar for clicado... */
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir6 tr').length;
	/* Condi��o que verifica se existe limite de linhas e, se existir, testa se usu�rio atingiu limite */
	if (limite_linhas && limite_linhas > linha_total)
	{
		/* Pega uma linha existente */
		var linha = $('tbody#repetir6 tr').html();
		/* Conta quantidade de linhas na tabela */
		var linha_total = $('tbody#repetir6 tr').length;
		/* Pega a ID da linha atual */
		var linha_id = $('tbody#repetir6 tr').attr('id');
		/* Acrescenta uma nova linha, incluindo a nova ID da linha */
		$('tbody#repetir6').append('<tr id="linha_' + (linha_total + 1) + '">' + linha + '</tr>');
	}
	/* Se usu�rio atingiu limite de linhas� */
	else
	{
		alert("Desculpe, mas você só pode adicionar até " + limite_linhas + " linhas!");
	}
};
$.removeLinha6 = function (element)
{
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir6 tr').length;
	/* Condi��o que mant�m pelo menos uma linha na tabela */
	if (linha_total > 1)
	{
		/* Remove os elementos da linha onde est� o bot�o clicado */
		$(element).parent().parent().remove();
	}
	/* Avisa usu�rio de que n�o pode remover a �ltima linha */
	else
	{
		alert("Desculpe, mas você não pode remover esta última linha!");
	}
};

</script>
<?php if($abre_form == 1) { ?>
<form name="form" action="#" method="post">
	<table width="70%" border="0" align="center">
		<tr>
			<td colspan="3" class="titulo">Relat&oacute;rio de Tarefas Gerenciais</td>
		</tr>
		<tr>
			<td width="38%" class="subtitulodireita">&nbsp;</td>
			<td colspan="2" class="campoesquerda">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Franquia</td>
			<td colspan="2" class="subtitulopequeno">
				<?php
				if (($tipo == "a") || ($tipo == "c") || ($tipo == "d")) {
					echo "<select name='rel_franquia' class='boxnormal'  >";
					$sql = "SELECT id, fantasia FROM franquia 
					WHERE sitfrq = 0 AND classificacao <> 'J'
					ORDER BY id";
					$resposta = mysql_query($sql, $con);
					while ($array = mysql_fetch_array($resposta)) {
						$franquia   = $array["id"];
						$nome_franquia = $franquia.' - '.$array["fantasia"];
						if ( $franquia == $id_franquia ) $select = 'selected';
						else $select = '';
						echo "<option value='$franquia' $select>$nome_franquia</option>\n";
					}
					echo "</select>";
				}else {
					echo $nome_franquia;
					echo "<input name='franqueado' type='hidden' id='franqueado' value= $id_franquia />";
				}
				?>
			</td>
		</tr>

		<tr>
			<td width="38%" class="subtitulodireita">Ano</td>
			<td colspan="2" class="subtitulopequeno">
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
		<tr>
			<td>&nbsp;</td>
			<td colspan="2" align="left"><input type="button" name="Confirma" value="    Confirma    " onclick="vaiPraSegundoForm()" />
			</td>
		</tr>
	</table>
</form>
<?php } ?>

<?php
if($abre_form == 2) {

	$qtd_cartazes_coletado          = 0;
	$qtd_panfletos_trabalhe_conosco = 0;
	$qtd_panfletos_rest             = 0;
	$qtd_entrevista_realizada       = 0;
	$qtd_treinamento_1_dia          = 0;
	$qtd_treinamento_2_dia          = 0;
	$qtd_treinamento_3_dia          = 0;
	$qtd_cartoes_recolhidos         = 0;
	$qtd_reuniao_quarta             = 0;
	$qtd_reuniao_sexta              = 0;

?>
<form method="post" action="a_controle_visita4_gravar.php" enctype="multipart/form-data" name="form">
	<input type="hidden" name="ano_esc" value="<?php echo $_REQUEST['ano_esc']?>">
	<table width="90%" border="0" align="center">
		<tr>
			<td colspan="3" class="titulo">Relatorio de Tarefas Gerenciais</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Semana</td>
			<td width="22%" class="campoesquerda">
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
			<td width="40%" class="campoesquerda">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Per&iacute;odo</td>
			<td colspan="2" class="campoesquerda">
				<?php
				if (empty($wanted_week)) $wanted_week = $week;
				$week_diff = $wanted_week - $week;
				$ts_week = strtotime("$week_diff week");
				$day_of_week = date('w', $ts_week);

				//VERIFICA SE O ANO é BISSEXTO
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
				for ($i = $inicio; $i <= $fim; $i++) {
					// TimeStamp contendo os dias da semana de domingo a sabado
					$ts = strtotime( ( $i - $day_of_week )." days", $ts_week );
					if ($i == $inicio) {
						$primeiro = date($ano_esc."-m-d",$ts);
						if($sel == "01"){
							$_ano_esc = $ano_esc - 1;
							echo date("d/m/".$_ano_esc, $ts) . " ap&oacute;s 18:00:00 <br>";
						}else{
							echo date("d/m/".$ano_esc, $ts) . " ap&oacute;s 18:00:00 <br>";
						}
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
				<input type="hidden" name="primeiro" value="<?php echo $primeiro?>" />
				<input type="hidden" name="ultimo" value="<?php echo $ultimo?>" /></td>
		</tr>
		<tr>
			<td colspan="3" class="titulo">&nbsp;</td>
		</tr>
	</table>
    <?php

	// Verifico se tem TAREFAS GERENCIAIS ja cadastrada

	$sql = " SELECT * FROM cs2.tarefas_gerenciais WHERE ano = '$ano_esc' AND semana = '$sel' and id_franquia = $id_franquia ";
	$qry = mysql_query($sql, $con);
	$id_tarefa                      = @mysql_result($qry,0);
	$qtd_cartazes_coletado          = @mysql_result($qry,0);
	$qtd_panfletos_trabalhe_conosco = @mysql_result($qry,0);
	$qtd_entrevista_realizada       = @mysql_result($qry,0);
	$qtd_treinamento_1_dia          = @mysql_result($qry,0);
	$qtd_treinamento_2_dia          = @mysql_result($qry,0);
	$qtd_treinamento_3_dia          = @mysql_result($qry,0);
	$qtd_cartoes_recolhidos         = @mysql_result($qry,0);
	$qtd_panfletos_rest             = @mysql_result($qry,0);
	$qtd_reuniao_quarta             = @mysql_result($qry,0);
	$qtd_reuniao_sexta              = @mysql_result($qry,0);



	// Verifico se tem TAREFAS GERENCIAIS [FOTOS] ja cadastrada
	echo "<center>ID: $id_tarefa</center>";
    $sql = "SELECT * FROM cs2.tarefas_gerenciais_fotos
			WHERE id_tarefa = '$id_tarefa'";
	$qry = mysql_query($sql, $con);
	while ( $reg = mysql_fetch_array($qry) ){
		$treinamento  = $reg['tipo_foto'];
		$nome_arquivo = $reg['nome_arquivo'];
		$n_treina    = substr($treinamento,7,1);
		$seq_treina  = substr($treinamento,9,1);
		if ( $n_treina == 1 ){
			if ($seq_treina  == '1' ) $treina_1 = $nome_arquivo;
			if ($seq_treina  == '2' ) $treina_2 = $nome_arquivo;
			if ($seq_treina  == '3' ) $treina_3 = $nome_arquivo;
		}elseif ( $n_treina == 2 ){
			if ($seq_treina  == '1' ) $treina_4 = $nome_arquivo;
			if ($seq_treina  == '2' ) $treina_5 = $nome_arquivo;
			if ($seq_treina  == '3' ) $treina_6 = $nome_arquivo;
		}elseif ( $n_treina == 3 ){
			if ($seq_treina  == '1' ) $treina_7 = $nome_arquivo;
			if ($seq_treina  == '2' ) $treina_8 = $nome_arquivo;
			if ($seq_treina  == '3' ) $treina_9 = $nome_arquivo;
		}elseif ( $n_treina == 4 ){
			if ($seq_treina  == '1' ) $reuniao_quarta_1 = $nome_arquivo;
			if ($seq_treina  == '2' ) $reuniao_quarta_2 = $nome_arquivo;
			if ($seq_treina  == '3' ) $reuniao_quarta_3 = $nome_arquivo;
		}elseif ( $n_treina == 5 ){
			if ($seq_treina  == '1' ) $reuniao_sexta_1 = $nome_arquivo;
			if ($seq_treina  == '2' ) $reuniao_sexta_2 = $nome_arquivo;
			if ($seq_treina  == '3' ) $reuniao_sexta_3 = $nome_arquivo;
		}
	}
	// Verifico se tem pessoas entrevistadas cadastrados
    $sql = "SELECT * FROM cs2.tarefas_gerenciais_entrevistados
			WHERE id_tarefa = '$id_tarefa'";
	$qry = mysql_query($sql, $con);
	$entrevistados = '<br>';
	while ( $reg = mysql_fetch_array($qry) ){
		$nome      = $reg['nome'];
		$telefone  = $reg['telefone'];
		$celular   = $reg['celular'];
		$email     = $reg['email'];
		$entrevistados .= "<tr >
								<td width='50%'>$nome</td>
								<td width='15%'>$telefone</td>
								<td width='15%'>$celular</td>
								<td width='20%'>$email</td>
						   </tr>";
	}


	?>

<script type="text/javascript">

function verificaTamanho(src){
	var img=document.createElement("img")
	img.src=src
	setTimeout(
		function(){
			alert(img.width)
			alert(img.height)
		}, 1)
	}

function gravar(){
	frm = document.form;
	frm.action = 'painel.php?pagina1=clientes/a_controle_visitas4_gravar.php';
	frm.submit();
}
</script>

	<table width="90%" border="0" align="center">
		<tr>
			<td width="32%" class="subtitulodireita">&nbsp;</td>
			<td class="campoesquerda" style="text-align:center">Qtd</td>
			<td class="campoesquerda">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Quantos ANUNCIOS foram colados nas ruas ?</td>
			<td class="campoesquerda" colspan="2">
				<input type="text" name="qtd01" value="<?php echo $qtd_cartazes_coletado?>" style="width:50px;text-align:center" />
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Quantos TRABALHE CONOSCO foram distribuidos ?</td>
			<td class="campoesquerda"  colspan="2">
				<input type="text" name="qtd02" value="<?php echo $qtd_panfletos_trabalhe_conosco?>" style="width:50px;text-align:center" />
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Panfletos em Restaurantes, Lanchonetes e Estacionamentos ?</td>
			<td class="campoesquerda"  colspan="2">
				<input type="text" name="qtd08" value="<?php echo $qtd_panfletos_rest?>" style="width:50px;text-align:center" />
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Quantas ENTREVISTAS foram realizadas ?</td>
			<td class="campoesquerda">
				<input type="text" name="qtd03" value="<?php echo $qtd_entrevista_realizada?>" style="width:50px;text-align:center" />
			</td>
			<td class="campoesquerda">
				<div id="entrevista">
					<table border="0" width="100%" align="center">

						<tr>
							<td colspan="2">
								<table width="100%" cellpadding="4" cellspacing="0" border="1">
									<thead>
									<tr>
										<td align="center">Nome</td>
										<td align="center">Fixo</td>
										<td align="center">Celular</td>
										<td align="center">Email</td>
										<td align="center">&nbsp;</td>
									</tr>
									</thead>
									<tbody id="repetir4">
									<tr class="linha_1">
										<td>
											<input type="text" name="nome_entrevistado[]" size="25"/>
										</td>
										<td>
											<input type="text" name="fone_fixo[]" size="10" onKeyPress="soNumero(); formatar('##-####-####', this)"/>
										</td>
										<td>
											<input type="text" name="celular[]" size="10" onKeyPress="soNumero(); formatar('##-####-####', this)"/>
										</td>
										<td>
											<input type="text" name="nome_email[]" size="30"/>
										</td>
										<td align="center">
											<img src="../img/plus.png" id="remove" onclick="$.adicionaLinha4(this);"/>

											&nbsp;&nbsp;&nbsp;
											<img src="../img/minus.png" id="remove" onclick="$.removeLinha4(this);"/>
										</td>
									</tr>
									</tbody>
									<tr>
										<td colspan="5" style="font-size:12px; font-family:'Courier New', Courier, monospace; color:#00F">
											<table width="100%">
												<tr>
													<td colspan="4">Registros Encontrados:
													</td>
												</tr>
												<?php echo $entrevistados?>
											</table>
										</td>
									</tr>
								</table>
							</td>
					</table>
				</div>
			</td>

		</tr>
		<tr>
			<td class="subtitulodireita">Quantos TREINADOS no 1&deg; dia ?</td>
			<td class="campoesquerda">
				<input type="text" name="qtd04" value="<?php echo $qtd_treinamento_1_dia?>" style="width:50px;text-align:center" />
			</td>
			<td class="campoesquerda">
				<div id="foto1dia">
					<table border="0" width="100%" align="center">

						<tr>
							<td colspan="2">
								<table width="100%" cellpadding="4" cellspacing="0" border="1">
									<thead>
									<tr>
										<td colspan="2">
											<?php if ( $treina_1 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $treina_1?>" onclick="NovaJanela('<?php echo $treina_1?>','Foto','600','400','yes');return false"  /></a> &nbsp;
											<?php }
											if ( $treina_2 <> '' ) { ?>

												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $treina_2?>" onclick="NovaJanela('<?php echo $treina_2?>','Foto','600','400','yes');return false" /></a> &nbsp;
											<?php }
											if ( $treina_3 <> '' ) { ?>

												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $treina_3?>" onclick="NovaJanela('<?php echo $treina_3?>','Foto','600','400','yes');return false" /></a>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td align="center">Fotos</td>
										<td align="center">&nbsp;</td>
									</tr>
									</thead>
									<tbody id="repetir">
									<tr class="linha_1">

										<td width="80%">
											<input type="file" name="foto[]" size="30" style="cursor:pointer"/>
										</td>
										<td align="center">
											<img src="../img/plus.png" id="add" onclick="$.adicionaLinha1(this);"/>

											&nbsp;&nbsp;&nbsp;
											<img src="../img/minus.png" id="remove" onclick="$.removeLinha1(this);"/>
										</td>
									</tr>
									</tbody>
								</table>
							</td>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Quantos TREINADOS no  2&deg; dia ?</td>
			<td class="campoesquerda">
				<input type="text" name="qtd05" value="<?php echo $qtd_treinamento_2_dia?>" style="width:50px;text-align:center" />
			</td>
			<td class="campoesquerda">
				<div id="foto2dia">
					<table border="0" width="100%" align="center">

						<tr>
							<td colspan="2">
								<table width="100%" cellpadding="4" cellspacing="0" border="1">
									<thead>
									<tr>
										<td colspan="2">
											<?php if ( $treina_4 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $treina_4?>" onclick="NovaJanela('<?php echo $treina_4?>','Foto','600','400','yes');return false" /></a> &nbsp;
											<?php }
											if ( $treina_5 <> '' ) { ?>

												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $treina_5?>" onclick="NovaJanela('<?php echo $treina_5?>','Foto','600','400','yes');return false" /></a> &nbsp;
											<?php }
											if ( $treina_6 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $treina_6?>" onclick="NovaJanela('<?php echo $treina_6?>','Foto','600','400','yes');return false" /></a>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td align="center">Fotos</td>
										<td align="center">&nbsp;</td>
									</tr>
									</thead>
									<tbody id="repetir2">
									<tr class="linha_1">

										<td width="80%">
											<input type="file" name="foto2[]" size="30" style="cursor:pointer"/>
										</td>
										<td align="center">
											<img src="../img/plus.png" id="add" onclick="$.adicionaLinha2(this);"/>

											&nbsp;&nbsp;&nbsp;
											<img src="../img/minus.png" id="remove" onclick="$.removeLinha2(this);"/>
										</td>
									</tr>
									</tbody>
								</table>
							</td>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Quantos TREINADOS no 3&deg; dia ?</td>
			<td class="campoesquerda">
				<input type="text" name="qtd06" value="<?php echo $qtd_treinamento_3_dia?>" style="width:50px;text-align:center" />
			</td>
			<td class="campoesquerda">
				<div id="foto3dia">
					<table border="0" width="100%" align="center">
						<tr>
							<td colspan="2">
								<table width="100%" cellpadding="4" cellspacing="0" border="1">
									<thead>
									<tr>
										<td colspan="2">
											<?php if ( $treina_7 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $treina_7?>" onclick="NovaJanela('<?php echo $treina_7?>','Foto','600','400','yes');return false" /></a> &nbsp;
											<?php }
											if ( $treina_8 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $treina_8?>" onclick="NovaJanela('<?php echo $treina_8?>','Foto','600','400','yes');return false" /></a> &nbsp;
											<?php }
											if ( $treina_9 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $treina_9?>" onclick="NovaJanela('<?php echo $treina_9?>','Foto','600','400','yes');return false" /></a>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td align="center">Fotos</td>
										<td align="center">&nbsp;</td>
									</tr>
									</thead>
									<tbody id="repetir3">
									<tr class="linha_1">

										<td width="80%">
											<input type="file" name="foto3[]" size="30" style="cursor:pointer"/>
										</td>
										<td align="center">
											<img src="../img/plus.png" id="add" onclick="$.adicionaLinha3(this);"/>

											&nbsp;&nbsp;&nbsp;
											<img src="../img/minus.png" id="remove" onclick="$.removeLinha3(this);"/>
										</td>
									</tr>
									</tbody>
								</table>
							</td>
					</table>
				</div>
			</td>
		</tr>

		<tr>
			<td class="subtitulodireita">REUNI&Atilde;O QUARTA-FEIRA</td>
			<td class="campoesquerda">
				<input type="text" name="qtd09" value="<?php echo $qtd_reuniao_quarta?>" style="width:50px;text-align:center" />
			</td>
			<td class="campoesquerda">
				<div id="reuniao_quarta">
					<table border="0" width="100%" align="center">
						<tr>
							<td colspan="2">
								<table width="100%" cellpadding="4" cellspacing="0" border="1">
									<thead>
									<tr>
										<td colspan="2">
											<?php if ( $reuniao_quarta_1 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $reuniao_quarta_1?>" onclick="NovaJanela('<?php echo $reuniao_quarta_1?>','Foto','600','400','yes');return false" /></a> &nbsp;
											<?php }
											if ( $reuniao_quarta_2 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $reuniao_quarta_2?>" onclick="NovaJanela('<?php echo $reuniao_quarta_2?>','Foto','600','400','yes');return false" /></a> &nbsp;
											<?php }
											if ( $reuniao_quarta_3 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $reuniao_quarta_3?>" onclick="NovaJanela('<?php echo $reuniao_quarta_3?>','Foto','600','400','yes');return false" /></a>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td align="center">Fotos</td>
										<td align="center">&nbsp;</td>
									</tr>
									</thead>
									<tbody id="repetir5">
									<tr class="linha_1">

										<td width="80%">
											<input type="file" name="foto4[]" size="30" style="cursor:pointer"/>
										</td>
										<td align="center">
											<img src="../img/plus.png" id="add" onclick="$.adicionaLinha5(this);"/>

											&nbsp;&nbsp;&nbsp;
											<img src="../img/minus.png" id="remove" onclick="$.removeLinha5(this);"/>
										</td>
									</tr>
									</tbody>
								</table>
							</td>
					</table>
				</div>
			</td>
		</tr>

		<tr>
			<td class="subtitulodireita">REUNI&Atilde;O SEXTA-FEIRA</td>
			<td class="campoesquerda">
				<input type="text" name="qtd10" value="<?php echo $qtd_reuniao_sexta?>" style="width:50px;text-align:center" />
			</td>
			<td class="campoesquerda">
				<div id="reuniao_quarta">
					<table border="0" width="100%" align="center">
						<tr>
							<td colspan="2">
								<table width="100%" cellpadding="4" cellspacing="0" border="1">
									<thead>
									<tr>
										<td colspan="2">
											<?php if ( $reuniao_sexta_1 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $reuniao_sexta_1?>" onclick="NovaJanela('<?php echo $reuniao_sexta_1?>','Foto','600','400','yes');return false" /></a> &nbsp;
											<?php }
											if ( $reuniao_sexta_2 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $reuniao_sexta_2?>" onclick="NovaJanela('<?php echo $reuniao_sexta_2?>','Foto','600','400','yes');return false" /></a> &nbsp;
											<?php }
											if ( $reuniao_sexta_3 <> '' ) { ?>
												<a href="#"><img width="100" height="100" title="Clique para ampliar a Foto" src="clientes/fotos_franquias/<?php echo $reuniao_sexta_3?>" onclick="NovaJanela('<?php echo $reuniao_sexta_3?>','Foto','600','400','yes');return false" /></a>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td align="center">Fotos</td>
										<td align="center">&nbsp;</td>
									</tr>
									</thead>
									<tbody id="repetir6">
									<tr class="linha_1">

										<td width="80%">
											<input type="file" name="foto5[]" size="30" style="cursor:pointer"/>
										</td>
										<td align="center">
											<img src="../img/plus.png" id="add" onclick="$.adicionaLinha6(this);"/>

											&nbsp;&nbsp;&nbsp;
											<img src="../img/minus.png" id="remove" onclick="$.removeLinha6(this);"/>
										</td>
									</tr>
									</tbody>
								</table>
							</td>
					</table>
				</div>
			</td>
		</tr>

		<tr>
			<td class="subtitulodireita">Quantos CART&Otilde;ES foram colhidos ?</td>
			<td class="campoesquerda" colspan="2">
				<input type="text" name="qtd07" value="<?php echo $qtd_cartoes_recolhidos?>" style="width:50px;text-align:center" />
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<input type="hidden" name="rel_franquia" value="<?php echo $id_franquia?>"/>
				<input type="submit" name="upload" value="    Gravar    " onclick="gravar()" />
				&nbsp;&nbsp;
				<input name="button" type="button" onclick="vaiPraPrimeiroForm()" value="     Voltar    " />
			</td>
		</tr>
	</table>
</form>
<?php
}
?>

