<?php

require_once("connect/sessao.php");
$id_usuario = $_SESSION['id'];
?>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../js/jquery.meio.mask.js"></script>

<script type="text/javascript">
(function($){
$(
  function(){
    $('input:text').setMask();
  }
);
})(jQuery);

jQuery(function($){
  $("#data").mask("99/99/9999");
});

function Chr(AsciiNum)
{
  return String.fromCharCode(AsciiNum)
}

function verifica_cliente(codigo) {
        if (codigo != '') {
            var req = new XMLHttpRequest();
            var idfranquia;
            req.open('GET', 'carrega_cliente.php?codigo=' + codigo, false);
            req.send(null);
            if (req.status != 200) return '';
            var retorno = req.responseText;
            var array = retorno.split('][');

            $('#nome_cliente').text(array[0] + ' / ' + array[2]);

            if ( array[4] == 'CANCELADO' ){
               $('#nome_cliente').text('--- CLIENTE CANCELADO ---');
               $('#codigo').text('');
               $('#codigo').focus();
            }
            
        }
    }
    

function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espaço em branco

function buscaCodigo(){
    frm = document.form;
    if(trim(frm.codigo.value) == ""){
        alert("Falta Informar a Codigo do Cliente !");
        frm.codigo.focus();
        return false;
    } 
    frm.action = 'painel.php?pagina1=clientes/boleto_novo_gerar.php&acao=1';
    frm.submit();
}

function confirma(){
    frm = document.form;  
  if(trim(frm.codigo.value) == ""){
    alert("Falta Informar a Codigo do Cliente !");
    frm.codigo.focus();
    return false;
  } 
  if(trim(frm.vencimento.value) == ""){
    alert("Falta Informar o Vencimento !");
    frm.vencimento.focus();
    return false;
  }
  if(trim(frm.valor.value) == "0,00"){
    alert("Falta Informar o Valor do Boleto !");
    valor();
    return false;
  }
  frm.action = 'painel.php?pagina1=clientes/boleto_novo_gerar.php&acao=2';
    frm.submit();
} 

function mostra_dados(){
  frm = document.form;
  // Texto 1
  if ( frm.texto1.checked ){
     frm.obs.value = 'Referente a TAXA DE IMPLANTAÇÃO WEB CONTROL EMPRESAS'+Chr(13);
  } else {
    // texto2 desmarcado, limpar do textarea
    var newtext = frm.obs.value;
    frm.obs.value = newtext.replace('Referente a TAXA DE IMPLANTAÇÃO WEB CONTROL EMPRESAS' , '');
  }
  // Texto 2
  if ( frm.texto2.checked ){
     frm.obs.value = 'Referente a DIVERGÊNCIA DE PAGAMENTO DA FATURA'+Chr(13);
  } else {
    // texto2 desmarcado, limpar do textarea
    var newtext = frm.obs.value;
    frm.obs.value = newtext.replace('Referente a DIVERGÊNCIA DE PAGAMENTO DA FATURA' , '');
  }
  // Texto 4
  if ( frm.texto4.checked ){
     frm.obs.value = 'Referente a FATURAS EM ATRASO e CANCELAMENTO CONTRATUAL - VENCIMENTOS : '+Chr(13);
  } else {
    var newtext = frm.obs.value;
    frm.obs.value = newtext.replace('Referente a FATURAS EM ATRASO e CANCELAMENTO CONTRATUAL - VENCIMENTOS : ' , '');
  }
  // Texto 3
  if ( frm.texto3.checked ){
     frm.obs.value = 'Referente a MULTA DE RESCISÃO CONTRATUAL (3 MESES)'+Chr(13);
  } else {
    // texto2 desmarcado, limpar do textarea
    var newtext = frm.obs.value;
    frm.obs.value = newtext.replace('Referente a MULTA DE RESCISÃO CONTRATUAL (3 MESES)' , '');
  }

}

window.onload = function() {
  document.form.codigo.focus(); 
}

</script>
<form method="post" action="#" name="form">
<input type="hidden" name="codloja" value="<?=$_REQUEST['codloja']?>"/>

<table width="70%" align="center" cellpadding="1" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
  <tr>
    <td colspan="2" align="center" class="titulo">Boleto Avulso</td>
  </tr>
  <tr>
    <td width="40%" class="subtitulodireita">C&oacutedigo do cliente</td>
    <td width="60%" class="campoesquerda">
        <input type="text" id="codigo" name="codigo" value="<?=$_REQUEST['codigo']?>" style="width:20%;" maxlength="6" onblur="verifica_cliente(this.value)"/>
        <span id="nome_cliente"></span>
  </tr>
  <tr>
    <td class="subtitulodireita">Vencimento</td>
    <td class="campoesquerda">
       <input type="text" name="vencimento" id="data" value="<?=date('d/m/Y')?>" style="width:20%;" />     
  </td>
  </tr>
  <tr>
  <td class="subtitulodireita">Valor</td>
  <td class="campoesquerda">
            <input type="text" name="valor" alt="decimal" id="valor" style="width:20%;"/>
            <span id_nome_cliente=""></span>
  </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Mensagem</td>
    <td class="campoesquerda">
       <input type="checkbox" name="texto1" onclick="mostra_dados()"/> 
       Referente a TAXA DE IMPLANTAÇÃO WEB CONTROL EMPRESAS
       <?php if ( ( $id_usuario == 163 ) or ( $id_usuario == 1204 ) or ($id_usuario == 46) or ($id_usuario == 4)){ ?>
       <br>
       <input type="checkbox" name="texto2" onclick="mostra_dados()"/> 
       Referente a DIVERGÊNCIA DE PAGAMENTO DA FATURA
    <br>
       <input type="checkbox" name="texto4" onclick="mostra_dados()"/> 
        Referente a FATURAS EM ATRASO e CANCELAMENTO CONTRATUAL - VENCIMENTOS :
        <br>
       <input type="checkbox" name="texto3" onclick="mostra_dados()"/> 
       Referente a MULTA DE RESCISÃO CONTRATUAL (3 MESES)
         <?php } ?>
  </td>
  </tr>
  <tr>  
    <td class="subtitulodireita">Observação</td>
    <td class="campoesquerda" valign="top">
        <textarea style="width:80%;" rows='5' name='obs' id='obs'></textarea>
    </td>
  </tr>
 
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" value="Confirma" name="Confirma" onclick="confirma()" style="cursor:pointer"/></td>
  </tr>
</table>
</form>

<?php if($_REQUEST['acao'] <> '' ) { ?>
  <script type="text/javascript">alert('O Cliente não foi localizado ! ');</script>
<?php } ?>