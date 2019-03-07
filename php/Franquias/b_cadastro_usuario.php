<script language="javascript">
function cancelar_edit(){
	frm = document.funcionario;	
	frm.action = "?pagina1=Franquias/b_cadastro_usuario.php&acao=cancela";
	frm.submit();
}

function gravar_registro(){
	frm = document.funcionario;	
	if(frm.cpf.value == ""){
		alert("Falta informar o CPF ! ");	
		frm.cpf.focus();
		return false;
	}
	if(frm.cpf.value.length < 11){
		alert("O CPF deve ter 11 caracteres ! ");
		frm.cpf.focus();
		return false;
	}
	if(frm.nome.value == ""){
		alert("Falta informar o Nome ! ");	
		frm.nome.focus();
		return false;
	}
	if(frm.senha1.value == ""){
		alert("Falta informar a Senha ! ");	
		frm.senha1.focus();
		return false;
	}
	if(frm.senha1.value.length < 5){
		alert("A Senha deve ter no minimo 5 caracteres ! ");
		frm.senha1.focus();
		return false;
	} 
	if(frm.senha2.value == ""){
		alert("Falta informar a Senha ! ");	
		frm.senha2.focus();
		return false;
	}
	if(frm.senha1.value != frm.senha2.value) {
		alert("As Senhas nao conferem ! ");	
		return false;
	}	
	frm.action = "?pagina1=Franquias/b_cadastro_usuario.php&acao=gravaregistro";
	frm.submit();
}
</script>
<?
include("connect/sessao.php");
include("connect/conexao_conecta.php");

function mascara_cpf($p_cpf_banco){
 	   $a = substr($p_cpf_banco, 0,3);   
	   $b = substr($p_cpf_banco, 3,3);   
	   $c = substr($p_cpf_banco, 6,3);   
	   $d = substr($p_cpf_banco, 9,2);   
	   $cpf_view.=$a;
	   $cpf_view.=".";
	   $cpf_view.=$b;
	   $cpf_view.=".";
	   $cpf_view.=$c;
	   $cpf_view.="-";
	   $cpf_view.=$d;	
	   return ($cpf_view);
}

function formata_data($p_paramento){
 	   $dia = substr($p_paramento, 8,2);   
	   $mes = substr($p_paramento, 5,2);   
	   $ano = substr($p_paramento, 0,4);   	   
	   $hora = substr($p_paramento, 11,8);  	   
	   $data_hora_view.=$dia;
	   $data_hora_view.="/";
	   $data_hora_view.=$mes;
	   $data_hora_view.="/";
	   $data_hora_view.=$ano;	   
	   $data_hora_view.=" - ";	   
	   $data_hora_view.=$hora;	   
	   return ($data_hora_view);
} 

$acao     = $_REQUEST['acao'];
$cpf      = $_REQUEST['cpf'];
$nome_fun = $_REQUEST['nome'];
$situacao = $_REQUEST['situacao'];
$senha1   = md5($_REQUEST['senha1']);

//ALTERA O NOME DOS BOTOES
if($_REQUEST['acao'] == 'alterar'){
	$nome_button = "Alterar";
}else{
	$nome_button = "Gravar";
}

if ( $acao == 'cancela' ){  // BOTAO CANCELAR
	$cpf = '';
	$nome_fun = '';
	$readonly = '';
}
elseif ( $acao == 'alterar' ){ // BOTAO ALTERA
	$readonly = "readonly='readonly'";
}
elseif ( $acao == 'status' ){ // BOTAO STATUS
	if($_REQUEST['situacao'] == 'A'){
		 $sit_recebe = 'I';
	}else{	 
		 $sit_recebe = 'A';
	}
	$sql_update = "UPDATE cs2.franquia_usuario 
				   SET 
				   	situacao = '$sit_recebe'
				   WHERE cpf = '{$_REQUEST['cpf']}'";
	$qry_update = mysql_query($sql_update,$con) or die("Erro SQL [$sql_update]");
	$readonly = '';
	$cpf       = '';
	$nome_fun  = '';
}
elseif ( $acao == 'gravaregistro' ){  // BOTAO GRAVAR
		
	$sql_ver = "SELECT COUNT(*) qtd FROM cs2.franquia_usuario WHERE cpf = '$cpf'";
	$qry_ver = mysql_query($sql_ver,$con);
	$xqtd = mysql_result($qry_ver,'0','qtd');
	if ( $xqtd > 0 ){
		$sql_update ="UPDATE cs2.franquia_usuario SET
			         nome    = '$nome_fun',
					 senha   = '$senha1'
			 WHERE 
			         cpf     = '$cpf'";
		$qry_update = mysql_query($sql_update,$con) or die("Erro SQL [$sql_update]");
		$cpf       = '';
		$nome_fun  = '';			 
	}else{ 
		$sql_ins = "INSERT INTO cs2.franquia_usuario(cpf, nome, senha, data_hora)VALUES('$cpf', '$nome_fun', '$senha1', now())";
		$qry_ins = mysql_query($sql_ins,$con) or die("Erro SQL [$sql_update]");
		$cpf       = '';
		$nome_fun  = '';
		$readonly = '';
	}
}
?>

<script language="javascript">
	window.onload = function() { document.form.nome.focus();  }
</script>
<form name="funcionario" action = "#" method="post">
<table border="0" width="95%" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
	<tr>
	  <td colspan="2" class="titulo" align="center" height="22px">Cadastro de Funcion&aacute;rios</td>
	</tr>
	<tr>
		<td width="20%" class="subtitulodireita"><b>CPF</b></td>
	  <td width="80%" class="subtitulopequeno">
			<input maxlength="11" name="cpf" style="width:20%" id="cpf" value="<?=$cpf?>" <?=$readonly?> />
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita"><b>Nome</b></td>
		<td class="subtitulopequeno">
       	  <input maxlength="50" name="nome" style="width:40%" value="<?=$nome_fun?>" onKeyUp="upperCase(this.id)"/>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita"><b>Senha</b></td>
		<td class="subtitulopequeno">
       	  <input type="password" maxlength="10" name="senha1" style="width:20%"/></td>
	</tr> 
	<tr>
		<td class="subtitulodireita"><b>Repetir Senha</b></td>
		<td class="subtitulopequeno">
       	  <input type="password" maxlength="10" name="senha2" style="width:20%"/></td>
	</tr>
	<tr class="titulo" >
       <td class="subtitulodireita"></td>
		<td align="left">
        	<input type="button" name="Gravar" value="<?=$nome_button?>" onclick="gravar_registro()" style="cursor:pointer"/>
            <?php if($_REQUEST['acao'] == 'alterar'){ ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" name="Cancelar" value="Cancelar"  onclick="cancelar_edit()" style="cursor:pointer"/>
            <?php } ?>
         </td>
	</tr>
</table>
<p>
<table border="0" width="95%" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
	<tr>
		<td colspan="5" class="titulo" align="center" height="22px">Listagem de Funcion&aacute;rios</td>
	</tr>
	<tr class="subtitulopequeno" style="background-color:#B6CBF6" height="22px">
    	<td width="15%"><b>&nbsp;CPF</b></td>
        <td><b>&nbsp;Nome</b></td>
        <td width="15%"><b>&nbsp;Situa&ccedil;&atilde;o</b></td>
        <td width="15%"><b>&nbsp;Cadastro</b></td>
        <td width="10%">&nbsp;</td>
    </tr>
	<?php
		$lin = 0;
		$sql_listagem = "SELECT cpf, nome, situacao, data_hora, 			
							CASE situacao 
								WHEN 'A' THEN '<font color=green><b>ATIVO</b></font>' 
								WHEN 'I' THEN '<font color=red><b>INATIVO</b></font>' 
							END as sit2 
						 FROM cs2.franquia_usuario
						 ORDER BY situacao, nome ASC ";
		$qry_listagem = mysql_query($sql_listagem,$con) or die ("ERRO SQL [$sql_listagem]");
		if ( mysql_num_rows($qry_listagem) > 0 ){
			while ( $res = mysql_fetch_array($qry_listagem) ){
				$cpf = ($res['cpf']);
				$nome      = strtoupper($res['nome']);
				$sit       = strtoupper($res['situacao']);
				$data_hora = $res['data_hora'];
				$lin++;
				$cor = ($lin%2==0) ? '#FBFBFB' : '#CCC';
				$ativo = $res['sit2'];
				echo "<tr class='subtitulopequeno' style='background-color:$cor' height='20px'>";
				echo "<td>&nbsp;".mascara_cpf($cpf)."</td>";
				echo "<td>&nbsp;$nome</td>";
				echo "<td>&nbsp;<a href='?pagina1=Franquias/b_cadastro_usuario.php&acao=status&cpf=$cpf&situacao=$sit'>$ativo</td>";
				echo "<td>&nbsp;".formata_data($data_hora)."</td>";
				echo "<td align='center'><a href='?pagina1=Franquias/b_cadastro_usuario.php&acao=alterar&cpf=$cpf&nome=$nome'><b>Alterar</b></a></td>";
				echo "</tr>";
			}
		}
		?>
</table>
</p>
</form>