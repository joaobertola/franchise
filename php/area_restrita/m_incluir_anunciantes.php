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
    
    $valor = floatval(str_replace(",", ".", $valor));
    
    $sql = "INSERT INTO cs2.anunciantes (codloja,tipo,data_fim,ativo,valor) VALUE ({$codloja},'{$tipo}', '{$data_fim}','{$situacao}',{$valor})";
    
    $qry = mysql_query($sql, $con);
    
    if($qry){
        echo "<p><label style='color:blue'>Cadastrado com sucesso!</label></p>";
    }else{
        echo "<p><label style='color:red'>Nao foi possivel cadastrar anunciante!</label></p>";
    }
    
    $qry = mysql_close($con);
}
?>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>

<form action="" method="post">
	<table border="0" align="center" width="640">
		<thead bgcolor="#CFCFCF">
			<tr>
				<th colspan="2" class="titulo">CADASTRAR NOVO ANUNCIANTE</th>
			</tr>
		</thead>
		<tbody bgcolor="#CFCFCF">
			<tr>
				<th>Codigo Cliente</th>
				<td><input type='text' name='codloja' /></td>
			</tr>
			<tr>
				<th>Tipo</th>
				<td>
					<select name='tipo'>
						<option value='B'>Boleto</option>
						<option value='C'>Consultor</option>
						<option value='S'>Sistema</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>Valor</th>
				<td><input type='text' name='valor' /></td>
			</tr>
			<tr>
				<th>Data Fim</th>
				<td><input type='date' name='data_fim' /></td>
			</tr>
			<tr>
				<th>Situcao</th>
				<td>
					<select name='situacao'>
						<option value='S'>Ativo</option>
						<option value='N'>Nao Ativo</option>
					</select>
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