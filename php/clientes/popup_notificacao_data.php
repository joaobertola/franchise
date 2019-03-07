<script type="text/javascript" src="../../js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput-1.1.4.pack.js"/></script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#data").mask("99/99/9999");
	});
</script> 

<body topmargin="5" leftmargin="0" onLoad="javascript:document.frm.data_notificacao.focus();">

<form action="popup_gera_notificacao.php" method="post" name="frm">
<input type="hidden" name="codloja" value="<?=$_REQUEST['codloja']?>">
<input type="hidden" name="soma" value="<?=$_REQUEST['soma']?>">
<table width="600" border="1" cellspacing="0" cellpadding="5" align="center" class="Grande">
  <tr>
    <td colspan="4" bgcolor="#eeeeee" align="center" style="padding-top:10; padding-bottom:10" class="Grande"><strong>DADOS DO CREDOR</strong></td>
  </tr>  
  
  <tr>
    <td width="20%" bgcolor="#eeeeee"><strong>Vencimento:</strong></td>
    <td width="30%"><input type="text" name="data_notificacao"  id="data" alt="data" style="width:50%"></td>
    <td width="20%" bgcolor="#eeeeee"><strong>Valor:</strong></td>
    <td width="30%">R$ <?php echo number_format((float)$_REQUEST['soma'], 2, ',', '.'); ?></td>
  </tr> 
  <tr>
    <td colspan="4" align="center">
			    <input type="submit" value="Gera Notifica��o">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			    <input type="button" value="Fechar" onclick="window.close();">    			
    </td>
  </tr>
</table>

</form>

</body>