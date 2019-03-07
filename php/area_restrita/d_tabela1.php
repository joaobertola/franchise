<?php
$go = $_POST['go'];


if ($go == "alt_tabela") {
	$assinatura = $_POST['assinatura'];
	$pacote 	= $_POST['pacote'];
	$codloja 	= $_POST['codloja'];
	if ((empty($assinatura)) || (empty($pacote)) || (empty($codloja))) echo "<script>alert('valor invalido');history.back();</script>";

	// caso for somente negativa��o
	if ( $assinatura == '7' ) {
		mysql_query("update cs2.cadastro set classe='1' where codloja='$codloja'");
	}
	
	//insere tabela de pre�os e consultas liberadas
	$sql = "select codcons,valor from cs2.valcons";
	$inserre = mysql_query($sql,$con);
	
	while ($registro= mysql_fetch_array($inserre)) {
	   $codcons = $registro["codcons"];
	   $valcons = $registro["valor"];
	   $qtd = '50';
	   //n�o libera nem cartorial nem empresarial
	   if ( $codcons == 'A0203' || $codcons == 'A0115' ) $qtd = '5';
	   //n�o libera nada se for somente negativa��o
	   if ( $assinatura == '7' ) $qtd= '0';
	   
	   $tabela = "update cs2.valconscli set valorcons = '$valcons' where codloja = '$codloja' and codcons = '$codcons'";
	   $result1 = mysql_query($tabela, $con) or die ("Erro: $tabela");
	   $liberadas = "update cs2.cons_liberada set qtd = '$qtd'  where codloja = '$codloja' and tpcons = '$codcons'";
	   $result2 = mysql_query($liberadas, $con)  or die ("Erro: $liberadas");
	} 
	
	//insere consultas bonificadas
	$sql = "select nome, tpcons, qtd from cs2.tabela_valor where id = '$pacote'";
	$qr = mysql_query($sql, $con) or die ("erro ao buscar o pacote".mysql_error());
	$tpcons = mysql_result($qr,0,'tpcons');
	$quant = mysql_result($qr,0,'qtd');
	$mensalidade = mysql_result($qr,0,'nome');
	
	$sql = "update cs2.bonificadas set tpcons = '$tpcons', qtd = '$quant' where codloja = '$codloja'";
	$resposta = mysql_query($sql, $con);
	
	$sql = "update cs2.cadastro set tx_mens = '$mensalidade' where codloja = '$codloja'";
	$qr = mysql_query($sql, $con) or die ("erro ao atualizar o cadastro".mysql_error());
	
	mysql_free_result($sql);
	$res = mysql_close ($con);
	echo "<script>alert(\"Cliente cadastrado com sucesso!\");</script>";
	echo "<meta http-equiv=\"refresh\" content=\"0; url=painel.php?pagina1=area_restrita/d_tabela.php\";>";
}

if (empty($go)) {
//pega o tipo de pacote da tabela Clientes
$codloja = $_GET['codloja'];

$resulta = "SELECT a.razaosoc, a.tx_mens, mid(b.logon,1,5) as logon FROM cs2.cadastro a
			inner join cs2.logon b on a.codloja=b.codloja
			WHERE a.codloja='$codloja'";
$resulta = mysql_query($resulta, $con);
$logon = mysql_result($resulta,0,'logon'); 
$razao = mysql_result($resulta,0,'razaosoc');
$tx_mens = mysql_result($resulta,0,'tx_mens');
$tx_mens = number_format($tx_mens, 2, ",", ".");

$sql = "select a.codcons, b.nome, a.valorcons, b.vr_custo, c.qtd from cs2.valconscli a 
		inner join cs2.valcons b on a.codcons=b.codcons
		left join cs2.bonificadas c on a.codloja = c.codloja and a.codcons = c.tpcons
		where a.codloja='$codloja'";
$qr = mysql_query($sql, $con) or die ("nao achei nenhuma lista de precos para este cliente".mysql_error());
?>
<script language="javascript">
// fun��o para o drop down das tabelas de pre�o
function pesquisar_dados( valor )
{
  http.open("GET", "consulta.php?id=" + valor, true);
  http.onreadystatechange = handleHttpResponse;
  http.send(null);
}
//pega o resultado e apresenta  no seu devido lugar
function handleHttpResponse()
{
  campo_select = document.forms[0].pacote;
  if (http.readyState == 4) {
    campo_select.options.length = 0;
    results = http.responseText.split(",");
    for( i = 0; i < results.length; i++ )
    {
      string = results[i].split( "|" );
      campo_select.options[i] = new Option( string[0], string[1] );
    }
  }
}

//a pesar que parece coment�rio, � melhor n�o mexer nisto, pq reconhece os browsers
function getHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}
var http = getHTTPObject();

//validar pacotes
function validaPacote(){
d = document.form1;
if (d.assinatura.value == ""){
	alert("O campo " + d.assinatura.name + " deve ser selecionado!");
	d.assinatura.focus();
	return false;
}
return true;
}
</script>
<style type"text/css">
h1 {
	font-size: 140%;
}
form {
	margin: 30px 50px 0;
	font-family: Arial;
	font-size: 8pt;
}
form input {
	font-family: Arial;
	font-size: 8pt;
	text-align:right;	
}
</style>
<form method="post" onSubmit="return validaPacote();" action="<?php $_SERVER['PHP_SELF']; ?>" name="form1">
<table align="center" width="700" cellspacing="1">
  <tr>
    <td colspan="2" class="titulo">DADOS DO CLIENTE</td>
  </tr>
  <tr>

  <tr>
    <td class="subtitulodireita" width="40%">ID</td>
    <td class="subtitulopequeno" width="60%"><?php echo $codloja; ?><input type="hidden" name="codloja" value="<?php echo $codloja; ?>" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C�digo</td>
    <td class="subtitulopequeno"><?php echo $logon; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Raz�o Social </td>
    <td class="subtitulopequeno"><?php echo $razao; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Valor Atual da Assinatura Mensal</td>
    <td class="subtitulopequeno">R$ <?php echo $tx_mens; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno"><input type="hidden" name="go" value="alt_tabela" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Assinatura</td>
	<td class="subtitulopequeno">
    	<select name="assinatura" class="boxnormal" onChange="pesquisar_dados( this.value )">
            <option></option>
            <?php
				$consulta = mysql_query('SELECT * FROM tabela_tipo ORDER BY nome ASC');
				while( $row = mysql_fetch_assoc($consulta) ) {
					echo "<option value=\"{$row['id']}\">{$row['nome']}</option>\n";
				}
			?>
        </select>    </td>
  </tr>
 <tr>
    <td class="subtitulodireita"><span class="subtitulodireita">Pacote</span></td>
	<td class="subtitulopequeno">
    	<select name="pacote" class="boxnormal">
        </select></td>
 </tr>  
   <tr>
    <td colspan="2" class="titulo" align="center"><input type="submit" value="Alterar" style="width:20%;">	</td>
	</tr>  
</table>
</form>
<br/>
<form name="form2" method="post" action="<?php $_SERVER['PHP_SELF']; ?>" >
<table align="center" class="bodyText">
  <tr>
    <td class="subtitulo">C&oacute;digo</td>
    <td class="subtitulo">Produto</td>
    <td align="center" class="subtitulo">Custo</td>
    <td align="center" class="subtitulo">Venda</td>
    <td align="center" class="subtitulo">Gratuidade</td>
    <td align="center" class="subtitulo">&nbsp;</td>
  </tr>
<?php
while($matrix = mysql_fetch_array($qr)) {
	
	$codigo = $matrix['codcons'];
	$produto = $matrix['nome'];
	$venda = $matrix['valorcons'];
	$custo = $matrix['vr_custo'];
	$gratuidade = $matrix['qtd'];
	echo "<tr ";
	if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
	echo "	<td align=\"center\">$codigo</td>
			<td>$produto</td>
			<td align=right>$custo</td>
			<td><input type=text value=\"$venda\" size=6 onKeyPress=\"soNumero();\" onFocus=\"this.className='boxover'\" onBlur=\"this.className='boxnormal';\" onKeyDown=\"FormataValor(this,20,event,2)\"></td>
			<td><input type=text value=\"$gratuidade\" size=6 onKeyPress=\"soNumero();\" onFocus=\"this.className='boxover'\" onBlur=\"this.className='boxnormal';\" onKeyDown=\"FormataValor(this,20,event,2)\"></td>
			<td><input type=image src=\"../img/disquette.gif\" alt=\"Gravar\"></td>
		</tr>";
	$a = $a + 1;
}
?>
  <tr>
    <td colspan="6" class="titulo" align="center"><input type="button" value="Alterar">
	</td>
  </tr>
</table>
</form>
<center><input class="botao3d" name="button" type="button" onClick="javascript: history.back();" value="Voltar" /></center>

<?php
} //fim empty go
?>