<?php
session_start();

$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];
$data_cadastro = date("Y-m-d");

/*
if ( $name == "" ){
	session_unregister($_SESSION['name']);
	session_destroy();
	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
	die;
}				   
*/

$sql = "SELECT * FROM pretendentes_status";
$qry = mysql_query($sql, $con);	
?>

<script language="javascript">
function consulta(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/pretendentes_relacao_mensagem_contato.php';
	frm.submit();
} 
</script>
<form action="#" method="post" name="form">

  <table border="0" width="60%" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
<tr><td colspan="2" align="center" class="titulo">Mensagem p/ Pretendentes a Franqueados</td></tr>
<tr>
	<td width="25%" class="subtitulodireita">Descri&ccedil;&atilde;o</td>
    <td width="75%" class="subtitulopequeno"><select name="id_status" class="boxnormal" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" style="width:80%">
		<?php while($rs = mysql_fetch_array($qry)) { ?>        	
			<?php if($rs['id'] == $_REQUEST['id_status']){?>
        		<option value="<?=$rs['id']?>" selected="selected"><?=$rs['descricao']?></option>
            <?php }else{ ?>
	        	<option value="<?=$rs['id']?>"><?=$rs['descricao']?></option>
          	<?php } ?>              
        <?php } ?>    
        </select>
    </td>
</tr>

<tr bgcolor="#CCCCCC"><td>&nbsp;</td>
<td><input type="button" name="Consulta" value="Consulta Descri&ccedil;&atilde;o" onclick="consulta()"></td></tr>
</table>

<?php if($_REQUEST['id_status']) { ?>
<iframe src="https://www.webcontrolempresas.com.br/franquias/php/area_restrita/fckeditor/_samples/php/pretendente_altera_texto.php?id_status=<?=$_REQUEST['id_status']?>" name="pretendente" width="100%" height="600"></iframe>
<?php } ?>
</form>