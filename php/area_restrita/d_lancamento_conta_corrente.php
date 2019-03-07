<script language="javascript">
    function escolha( opcao){
        frm = document.form;
        if ( opcao == 'CLIENTE' ){
            // cliente
            frm.action = 'painel.php?pagina1=area_restrita/d_ctaincluir_cliente.php';
        }else{
            // franquia
            frm.action = 'painel.php?pagina1=area_restrita/d_ctacte.php';
        }
        frm.submit();
    }
</script>
<form name="form" method="post" action="#">
<table width=700 border="0" align="center">
  <tr class="titulo">
      <td colspan="2">LAN&Ccedil;AMENTO EM CONTA CORRENTE</td>
  </tr>
  <tr>
    <td class="campoesquerda" colspan="2">&nbsp;</td>
  </tr>
  <tr>
      <td class="campoesquerda" colspan="2">
        <br>
        <input type="radio" name="opcao" value="CLIENTE" checked="checked"> Cliente
        <br><br>
        <input type="radio" name="opcao" value="FRANQUIA"> Franquia
        <br>
        <br>
    </td>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><input type="button" value="    Confirmar    " onclick="escolha(opcao.value)" />
  </tr>
</table>
</form>
</body>