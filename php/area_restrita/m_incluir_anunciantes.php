<?php 
/**
 * Desenvolvido por um programador Web control
 */
require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";

$info = "";

if(isset($_POST["cadastro_anunciantes"])){
    foreach ($_POST as $i=>$v){
        ${$i} = trim(utf8_decode(utf8_encode($v)));
	}

	switch($tipo) {
		case 'S':
			$dominio = 'anunciantes/'.$codloja;
	
			$tmp_name 	= $_FILES['banner']['tmp_name'];
			$error 		= $_FILES['banner']['error'];
			$typeFile = substr($_FILES['banner']['name'], (strpos($_FILES['banner']['name'], '.')));

			$nameUpload = time().$typeFile;

			$caminhoUpload 	= '../'.$dominio;
			$caminhoBD = $_SERVER['HTTP_ORIGIN'].'/franquias/'.$dominio.'/'.$nameUpload;

			if (!is_dir($caminhoUpload)) {
				mkdir($caminhoUpload, 0777, true);
			}
			
			if ($error === UPLOAD_ERR_OK && move_uploaded_file($tmp_name, $caminhoUpload.'/'.$nameUpload))
			{

				$sql = "INSERT INTO cs2.anunciantes 
							(codloja, tipo, data_cadastro, data_inicio, data_fim, ativo, tipo_sistema, url, banner) 
						VALUE ({$codloja}, '{$tipo}', now(), '{$data_inicio}', '{$data_fim}', '{$situacao}', '{$tipo_sistema}', '{$url}', '{$caminhoBD}')";			
				$qry = mysql_query($sql, $con);

				if($qry)
				{
        			echo "<p><label style='color:blue'>Cadastrado com sucesso!</label></p>";
				}
				else
				{
        			echo "<p><label style='color:red'>Nao foi possivel cadastrar anunciante!</label></p>";
    			}
    
    			$qry = mysql_close($con);
			} 
			else
			{
				echo 'Erro ao fazer o upload:', $error;
			}
		break;
	}

	

    
    
    
}
?>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>

<form enctype="multipart/form-data" action="" method="post">
	<table border="0" align="center" width="640">
		<thead bgcolor="#CFCFCF">
			<tr>
				<th colspan="2" class="titulo">CADASTRAR NOVO ANUNCIANTE</th>
			</tr>
		</thead>
		<tbody bgcolor="#CFCFCF">
			<tr>
				<th>ID do cliente</th>
				<td><input type='text' name='codloja' required/></td>
			</tr>
			<tr>
				<th>Tipo</th>
				<td>
					<select name='tipo' required>
						<option value='B'>Boleto</option>
						<option value='C'>Consultor</option>
						<option value='S'>Sistema</option>
					</select>
				</td>
			</tr>
			<!--<tr>
				<th>Valor</th>
				<td><input type='text' name='valor' /></td>
			</tr>-->
		</tbody>
		<tbody  bgcolor="#CFCFCF" id = "sistema">
		<tr>
				<th>Data Início</th>
				<td>
					<input type='date' name='data_inicio' required/>
				</td>
			</tr>
			<tr>
				<th>Data Fim</th>
				<td>
					<input type='date' name='data_fim' required/>
				</td>
			</tr>
			<tr>
				<th>Situação</th>
				<td>
					<select name='situacao'>
						<option value='S'>Ativo</option>
						<option value='N'>Nao Ativo</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>Banner</th>
				<td><input type = "file" name = "banner" required></td>
			</tr>
			<tr>
				<td colspan = "2" align="center">
					<label for = "type_lead">Lead</label>
					<input id = "type_lead" type = "radio" name = "tipo_sistema" value = "lead" checked>
					
					<label for = "type_link">Link</label>
					<input id = "type_link" type = "radio" name = "tipo_sistema" value = "link">
				</td>
			</tr>
			<!--<tr id = "lead">
				<td colspan = 2 align= "center">
					<input type = "text" name = "nome" placeholder="nome">
					<input type = "text" name = "telefone" placeholder="telefone">
					<input type = "text" name = "email" placeholder="e-mail">
				</td>
			</tr>-->
			<tr id = "link">
				<td colspan = 2 align= "center">
					<input type = "text" name = "url" placeholder="url" style="width: 98%;">
				</td>
			</tr>
		</tbody>
		<tfooter>
			<tr><td>&nbsp;</td></tr>
			<tr>
    			<td>&nbsp;</td>
    			<td>
    				<input type='submit' name='cadastro_anunciantes' value="Salvar" />
    				<input type='reset' name='' value='Limpar' />
    			</td>
    		</tr>
		</tfooter>
	</table>
</form>

<script>
	$(document).ready(function () {
		$('#link').hide();
		$('#sistema').hide();
		
		$('select[name=tipo]').change(function () {
			var valor = $('select[name=tipo] option').filter(':selected').val();
			
			switch(valor)
			{
				case 'S':
					$('#sistema').show();
					break;
				default:
					$('#sistema').hide();
					break;
			}
		});

		$('input[name = "tipo_sistema"]').change(function () {
			var valor = $('input[name = "tipo_sistema"]:checked').val();
			
			switch(valor)
			{
				case 'lead':
					$('#link').hide();
					//$('#lead').show();
					break;
				case 'link':
					$('#link').show();
					//$('#lead').hide();
					break;
			}
		});
	});



</script>