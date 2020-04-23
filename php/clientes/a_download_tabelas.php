<?
require "connect/sessao.php";
?>

<table width=90% border="0" align="center">
  <tr> 
    <td colspan="3" class="titulo">DEPARTAMENTO COMERCIAL </td>
  </tr>
  <tr> 
    <td class="subtituloesquerda" width="15%">&nbsp;</td>
    <td class="subtituloesquerda" colspan="2">&nbsp;</td>
  </tr>

  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda btnTabelaPromo" data-id="<?php echo $_SESSION['id']?>" style="cursor: pointer;"><font color="#009900"> 1 - Tabela PROMOCIONAL</font></a></td>

    <td class="subtituloesquerda">
      <?php if($id_franquia == 4 || $id_franquia == 163 || $id_franquia == 247) { ?>
      TAXA DE HOMOLOGAÇÃO&nbsp;<input type="radio" id="iptTxHomolog" name="iptTxHomolog" value="200" checked>&nbsp;R$ 200,00
      &nbsp;&nbsp;<input type="radio" id="iptTxHomolog" name="iptTxHomolog" value="150">&nbsp;R$ 150,00
      &nbsp;&nbsp;<input type="radio" id="iptTxHomolog" name="iptTxHomolog" value="100">&nbsp;R$ 100,00
        <input type="radio" id="iptTxHomolog" name="iptTxHomolog" value=""> BRANCO
      <?php  } ?>
    </td>

  </tr>
  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda" colspan="2"><a href="clientes/tabela-franquias.php?id=<?=$_SESSION['id']?>&tabela=3" target="_blank"><font color="#009900"> 2 - Tabela de Reativação</font></a></td>
  </tr>
  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda" colspan="2"><a href="clientes/tabela-franquias.php?id=<?=$_SESSION['id']?>&tabela=2" target="_blank"><font color="#009900"> 3 - Exclusiva para Contadores</font></a></td>
  </tr>
  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda" colspan="2"><a href="clientes/tabela_precoequipamentos.php" target="_blank"><font color="#009900"> 4 - Tabela Preço de Equipamentos</font></a></td>
  </tr>
    
   <!--
  <tr> 
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=1"><font color="#009900"> 2 - Tabela PROMOCIONAL DE ANIVERS&Aacute;RIO</font></a></td>
  </tr>
  <tr> 
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_padrao.htm"><font color="#FF0000" > 2 - Tabela Padr&atilde;o - WEB CONTROL EMPRESAS</font></a></td>
  </tr>
  <tr> 
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=31"><font color="#009900"> 11 - Tabela PARCERIA CONTADORES</font></a></td>
  </tr>


  <tr> 
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=2">2 - Tabela Potencial Master</a></td>
  </tr>
  <tr> 
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=3">3 - Tabela Potencial Extra Master</a></td>
  </tr>
  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=26">4 - Tabela Potencial Protesto Nacional</a></td>
  </tr>
  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=6">5 - Tabela Potencial Crediario Nacional</a></td>
  </tr>
  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=5">6 - Tabela Potencial Restritiva</a></td>
  </tr>
  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=27">7 - Tabela Potencial Light</a></td>
  </tr>
  <tr> 
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=24">8 - Tabela Potencial Cartorial Light</a></td>
  </tr>
  

  <tr> 
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=10">9 - Tabela Potencial Cartorial</a></td>
  </tr>

  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=12">10 - Tabela Potencial Personnalite</a></td>
  </tr>
  <tr>
    <td height="23" class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=11">11 - Tabela Potencial Empresarial</a></td>
  </tr>
  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=13">12 - Tabela Potencial Localiza Max</a></td>
  </tr>
 
  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=9">14 - Tabela Meio de Acesso 0800 URA</a></td>
  </tr>

  <tr>
    <td class="subtituloesquerda">&nbsp;</td>
    <td class="subtituloesquerda"><a href="Franquias/tabela_preco_padrao.php?id=<?=$_SESSION['id']?>&tabela=15">15 - Tabela Potencial Ve�culo Total</a></td>
  -->
  </tr>

  <tr> 
    <td colspan="3" class="titulo">&nbsp;</td>
  </tr>
</table>
<center><input name="button" type="button" onClick="javascript: history.back();" value="Voltar" /></center>
<script>
  $(document).ready(function(){

    $('.btnTabelaPromo').on('click', function(){

      var id = $(this).data('id');
      var valorHomolog = $('input[name="iptTxHomolog"]:checked').val();

      window.open('../php/clientes/tabela-franquias.php?id='+id+'&tabela=1&vlrHomolog='+valorHomolog,'_blank');
    })

  })
</script>

<div style="display:none;">
  <img src="../../img/rodapeContrato.png">
  <img src="../../img/cabecalhoContrato3.png">
</div>