<?php
//require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

$codigo = $_POST['codigo'];

$resulta = mysql_query("select a.logon, b.id_franquia from logon a
						inner join cadastro b on a.codloja=b.codloja
						where CAST(MID(a.logon,1,6) AS UNSIGNED)='$codigo'",$con);
$linha = mysql_num_rows($resulta);
if (!$linha) {
	echo "<script>alert(\"Cliente não existe!\"); javascript: history.back();</script>";
}
else 
{
$comando = "select a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, a.bairro, a.end, a.cep, a.fone,
			a.sitcli, d.descsit,CAST(MID(b.logon,1,6) AS UNSIGNED) as logon from cadastro a
			inner join logon b on a.codloja=b.codloja
			inner join situacao d on a.sitcli=d.codsit
			where CAST(MID(b.logon,1,6) AS UNSIGNED)='$codigo'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res); 
?>
<form method="post" action="painel.php?pagina1=area_restrita/update_exclui.php">
<table border="0" align="center" width="775">
  <tr>
    <td colspan="2" class="titulo"><br>
      EXCLUS&Atilde;O DE CLIENTES DO REGISTRO <br></td>
  </tr>
  <tr>
    <td colspan="2">
	 <table width="370" align="center" bgcolor="#FFFF00">
      <tr class="formulario">
        <td align="center"><SCRIPT LANGUAGE="JavaScript">
							function initArray() {
							this.length = initArray.arguments.length;
							for (var i = 0; i < this.length; i++) {
							this[i] = initArray.arguments[i];
							}
							}
							var ctext = "ATEN&Ccedil;&Atilde;O!! ";
							var speed = 1000;
							var x = 0;
							var color = new initArray(
							"black", 
							"white", 
							"black", 
							"white", 
							"black",
							"white"
							);
							if(navigator.appName == "Netscape") {
							document.write('<layer id="c"><center>'+ctext+'</center></layer><br>');
							}
							if (navigator.appVersion.indexOf("MSIE") != -1){
							document.write('<div id="c"><center>'+ctext+'</center></div>');
							}
							function chcolor(){ 
							if(navigator.appName == "Netscape") {
							document.c.document.write('<center><font color="'+color[x]);
							document.c.document.write('">'+ctext+'</font></center>');
							document.c.document.close();
							}
							else if (navigator.appVersion.indexOf("MSIE") != -1){
							document.all.c.style.color = color[x];
							}
							(x < color.length-1) ? x++ : x = 0;
							}
							setInterval("chcolor()",100);
						</SCRIPT>
		Tem certeza que deseja Excluir o registro??</td>
      </tr>
      <tr class="formulario">
        <td><li>Caso precise s&oacute; modificar alg&uacute;m &iacute;tem, clique <a href="painel.php?pagina1=clientes/a_altcliente.php"><font color="#FF0000">AQUI</font></a> </td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td width="163" class="subtitulodireita">ID</td>
    <td width="602" class="subtitulopequeno"><?php echo $matriz['codloja']; ?>
      <input name="codloja" type="hidden" id="codloja" value="<?php echo $matriz['codloja']; ?>" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo de Cliente </td>
    <td class="campojustificado"><?php echo $matriz['logon']; ?></td>
    </tr>
  
  <tr>
      <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nome Fantasia</td>
    <td class="subtitulopequeno"><?php echo $matriz['nomefantasia']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">CNPJ</td>
    <td class="subtitulopequeno"><?php echo $matriz['insc']; ?></td>
  </tr>
  
  <tr>
      <td class="subtitulodireita">Endere&ccedil;o</td>
    <td class="subtitulopequeno"><?php echo $matriz['end']; ?></td>
    </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td class="subtitulopequeno"><?php echo $matriz['bairro']; ?></td>
    </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno"><?php echo $matriz['cidade']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">UF</td>
    <td class="subtitulopequeno"><?php echo $matriz['uf']; ?></td>
    </tr>
  <tr>
    <td class="subtitulodireita">CEP</td>
    <td class="subtitulopequeno"><?php echo $matriz['cep']; ?></td>
    </tr>
  
  <tr>
    <td class="subtitulodireita">Telefone</td>
    <td class="subtitulopequeno"><?php echo $matriz['fone']; ?></td>
    </tr>
  <tr>
    <td class="subtitulodireita">Situa&ccedil;&atilde;o do Contrato</td>
    <td class="formulario" <?php if ($matriz['sitcli'] == 0) {
								echo "bgcolor=\"#33CC66\"";
								} else {
								echo "bgcolor=\"#FF0000\"";} ?> ><font color="#FFFFFF"><?php echo $matriz['descsit']; ?></font></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
</table>
  <table width="234" align="center">
        <tr align="center">
          <td><input name="submit" type="submit" value=" Excluir registro " /></td>
		  <td><input name="cancela" type="button" value="    Cancela     " onClick="javascript:history.back()" /></td>
        </tr>
  </table>
</form>
<?php } ?>