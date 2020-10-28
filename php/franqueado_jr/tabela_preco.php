<?php
require "connect/sessao.php";

if ( $class == 'J' )
	$nome_classe = "Franqueado Junior";
elseif ( $class == 'X' )
	$nome_classe = "Micro Franqueado";
	
$go 	= $_POST['go'];
$codigo = $_POST['codigo'];

if (empty($go)) {
?>
<script language="javascript">
//fun��o para aceitar somente numeros em determinados campos
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function soNumeros(v){
    return v.replace(/\D/g,"")
}
</script>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
<table width=70% align="center">
  <tr>
    <td colspan="2" align="center" class="titulo">TABELA DE PRE&Ccedil;OS</td>
  </tr>
  <tr>
    <td width="173" class="subtitulodireita">&nbsp;</td>
    <td width="224" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo do Cliente</td>
    <td class="campoesquerda">
       <input type="text" name="codigo" id="codigo" size="10" maxlength="6" onKeyPress="mascara(this,soNumeros)" />
	   <input type="hidden" name="go" value="ingressar" />
	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franquia MASTER</td>
    <td class="campoesquerda"><?php echo $nome_franquia_master; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita"><?=$nome_classe?></td>
    <td class="campoesquerda"><?php echo $nome_franquia; ?></td>
  </tr>

  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr align="right">
    <td colspan="2"><input type="submit" value=" Enviar Consulta" name="enviar" /></td>
  </tr>
</table>
</form>
<?php
} // if go=null

if ($go=='ingressar') {
	
	if ( $class == 'J' ){
		$sql = "select mid(a.logon,1,5) as logon, b.id_franquia, b.codloja, b.razaosoc from logon a
				inner join cadastro b on a.codloja=b.codloja
				where CAST(MID(logon,1,6) AS UNSIGNED)='$codigo' 
				and b.id_franquia='$id_franquia_master' and b.id_franquia_jr='$id_franquia'";
	}else{
		$sql = "select mid(a.logon,1,5) as logon, b.id_franquia, b.codloja, b.razaosoc from logon a
					inner join cadastro b on a.codloja=b.codloja
					where CAST(MID(logon,1,6) AS UNSIGNED)='$codigo' 
					and b.id_franquia='$id_franquia'";		
	}

	$resulta = mysql_query( $sql );
	$linha = mysql_num_rows($resulta);
	if ($linha == 0)
	{
		print"<script>alert(\"Cliente n�o Existe ou n�o pertence � sua Franquia!\");history.back();</script>";
	}
	else 
	{
		$matriz = mysql_fetch_array($resulta); 
		$codloja = $matriz['codloja'];
		$logon = $matriz['logon'];
		$razaosoc = $matriz['razaosoc'];
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=franqueado_jr/tabela_preco_1.php&codigo=$codigo\";>";
	}
}
?>