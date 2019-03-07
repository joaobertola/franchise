<?php

$id = $_GET['id'];
$comando = "SELECT id,fantasia FROM franquia WHERE id='$id'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res); 
$franqueado = $matriz['id'];
$franquia = $matriz['fantasia'];
$res = mysql_close ($con);
?>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>
<script>

function Cancelar(){
    $('#localizar').prop( "disabled", false );
    $('#mostra').html('');
    $('#protocolo').val('');
    $('#protocolo').focus();
}

function Excluir(var1,var2){

  var result = confirm("Confirma Exclusão deste Protocolo [ "+var1+"] ?");
  if (result==true) {

    $.ajax({
              url: '../php/area_restrita/BuscaProtocolo.php',
              data: {
                      id_franquia: var2,
                      protocolo: var1,
                      action: 'apaga'
              },
              type: 'POST',
              dataType: 'text',
              success: function(data){
                   alert( data );
                   $('#localizar').prop( "disabled", false );
                   $('#mostra').html('');
                   $('#protocolo').val('');
                   $('#protocolo').focus();
              }
      });
  }else{
     $('#protocolo').focus();
  }

}


function Pesquisa_Protocolo(){
  var id_franquia = <?php echo $id; ?>;
  var protocolo = $('#protocolo').val();
  $.ajax({
          url: '../php/area_restrita/BuscaProtocolo.php',
          data: {
                  id_franquia: id_franquia,
                  protocolo: protocolo,
                  action: 'pesquisar'
          },
          type: 'POST',
          dataType: 'text',
          success: function(data){

            if ( data == '0' ){

               alert('Nenhum registro encontrado!');
               $('#protocolo').focus();

            }else{

               $('#mostra').html(data);
               $('#localizar').prop( "disabled", true );

            }
          },
          error: function(){
              
          }
  });
}

window.onload = function(){
  document.form.protocolo.focus(); 
}

</script>
<body>
<form name='form'>
  <table width=560 border="0" align="center">
     <tr class="titulo">
       <td colspan="2">CONTA CORRENTE DA <?php echo $franquia; ?>
         <input type="hidden" name="franqueado" value="<?php echo $franqueado; ?>"></td>
     </tr>
     <tr>
       <td class="subtitulodireita">&nbsp;</td>
       <td class="campoesquerda">&nbsp;</td>
     </tr>
     <tr>
       <td class="subtitulodireita">Protocolo</td>
       <td class="campoesquerda">
          <input type="text" id="protocolo" maxlength="10" size="15">
          &nbsp;&nbsp;
          <input id="localizar" type="button" value=" Localizar " onclick="Pesquisa_Protocolo()">
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <span id="mostra"></span>
        </td>
      </tr>
    </table>
  </form>
</body>