<?php

include("connect/conexao_conecta.php");

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
		d.action = 'painel.php?pagina1=clientes/lista_consultores.php';
		d.submit();
	}

	function novoRegistro(){
		d = document.form;
		d.action = 'painel.php?pagina1=clientes/cadastro_consultores.php';
		d.submit();
	}

	function cancelar(){
		d = document.form;
		d.action = 'painel.php?pagina1=clientes/a_controle_visitas0.php';
		d.submit();
	}

	var marked_row;
	var color_over = '#FFFFDD';

	function set_bgcolor(lin, color) {
		row = document.getElementById('row_'+lin);
		marked_row = lin;
		row.style.backgroundColor = color;
	}

	function carregaTela(p_id_consultor) {
		window.opener.location.href="../painel.php?pagina1=clientes/a_controle_visitas.php&assitente=<?=$_REQUEST['nome_tmp']?>&data_agenda=<?=$_REQUEST['data_agenda_tmp']?>&id_consultor="+p_id_consultor;
		window.close();
	}
</script>

<style>
	body{
		font-family:Arial, Verdana, sans-serif;
	}

	.botao {
		background-color: #87b5ff;
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
		background-color: #F5F5F5;
		height:22;
		font-size: 13px;
		border: 1px solid #999999;
		border: 1px solid #999999;
	}

	.topo{
		font-size: 20px;
		height:45px;
		background: #87b5ff;
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
	<table border="0" width="640px" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #F5F5F5; background-color:#FFFFFF">
		<tr>
			<td colspan="2" class="topo">Cadastro de Assistentes e Consultores</td>
		</tr>

		<tr height="45px">
			<td width="20%" bgColor="#F5F5F5"><b>Pesquisa:</b></td>
			<td width="80%" bgColor="#F0F0F6" align="left">
				<input type="text" id="pesquisa" name="pesquisa" maxlength="60" style="width:35%" class="frm_input" autofocus="autofocus"/>
				&nbsp;&nbsp;
				<input type="button" value=" Pesquisar " onClick="localizar()" class="botao"/>
			</td>
		</tr>

		<?php
		$registro = 0;
		$sql_seleciona = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia' ";
		if($_REQUEST['pesquisa'] != ""){
			$sql_seleciona .= " AND nome LIKE '%{$_REQUEST['pesquisa']}%'";
		}

		$sql_seleciona .= " ORDER BY situacao, nome ";

		if($_REQUEST['pesquisa'] == ""){
			$sql_seleciona .= " LIMIT 1000 ";
		}

		$qry_seleciona = mysql_query($sql_seleciona, $con);

		if ( mysql_num_rows($qry_seleciona) > 0 ){
		?>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr valign="top">
			<td colspan="2">
				<table border="0" width="100%" cellpadding="0" cellspacing="1">
					<tr height="22px" bgcolor="#87b5ff" style="font-size:11px; font-weight:bold">
						<td>&nbsp;Nome</td>
						<td width="15%">&nbsp;CPF</td>
						<td width="15%" align="center">Telefone</td>
						<td width="15%" align="center">Celular</td>
						<td width="12%" align="center">Status</td>
						<td width="10%" align="center"></td>
					</tr>
					<?php
					while ( $reg = mysql_fetch_array($qry_seleciona) ){
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
						<td>&nbsp;
							<?php if($sit == "2"){?>
								<a href='#' onClick="window.alert('Consultor cancelado não pode ser escolhido ! ')" class='classe1'><?=$nome?></a>
							<?php } else { ?>
								<a href='#' onClick="javascript: carregaTela(<?=$reg['id']?>)" class='classe1'><?=$nome?></a>
							<?php }  ?>
						</td>

						<?php
						echo "<td>&nbsp;$cpf</td>
        <td align='center'>$fon</td>
				<td align='center'>$cel</td>
				<td align='center'>$desc</td>
				<td align='center'><a href='painel.php?pagina1=clientes/cadastro_consultores.php&id_update=$id&nome_tmp={$_REQUEST['nome_tmp']}&data_agenda_tmp={$_REQUEST['data_agenda_tmp']}' class='classe1' title='Clique para editar'><img src='../../web_control/img/editar.gif' width='20px' height='16px' border='0'></a></td>
			</tr>";
						}
						echo "<tr height='24'><td colspan='6' align='right' style='font-size:11px'>Total de <b>[ $registro ]</b> regitro(s) encontrado(s)</td></tr>";
						}else{
							echo "<tr><td colspan='6' align='center'><font color='red'>Nenhum Assistente ou Consultor Cadastrado.</font></td></tr>";
						}
						?>
				</table>

				<div align="center">
					<input type="button" value=" Novo registro " onclick="novoRegistro()" class="botao" />
					&nbsp;&nbsp;
					<input type="button" value=" Voltar " onclick="cancelar()" class="botao" />
				</div>
