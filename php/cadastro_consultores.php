<?php

$nome = '';

//require("../connect/conexao_conecta.php");
//require("../connect/sessao.php");
//require("../connect/sessao_r.php");
//require("../../../web_control/funcao_php/mascaras.php");

function telefoneConverte2($p_telefone){
     if ($p_telefone == '') {
	   return ('');	   
	 } else { 	   
	   $a = substr($p_telefone, 0,2);   
	   $b = substr($p_telefone, 2,5);   
	   $c = substr($p_telefone, 7,4);   
	   
	   $telefone_mascarado  = "(";
   	 $telefone_mascarado .= $a;
	   $telefone_mascarado .= ")&nbsp;";
	   $telefone_mascarado .= $b;
	   $telefone_mascarado .= "-";
	   $telefone_mascarado .= $c;
	   return ($telefone_mascarado);
	 }  
}

if($_REQUEST['id_update']){
	$sql          = "SELECT * FROM cs2.consultores_assistente WHERE id = '{$_REQUEST['id_update']}'";
	$qry          = mysql_query($sql);
	$nome         = mysql_result($qry,0,'nome');
	$rg           = mysql_result($qry,0,'rg');
	$uf           = mysql_result($qry,0,'estado_rg');
	$cpf          = mysql_result($qry,0,'cpf');    
	$celular      = mysql_result($qry,0,'celular');
	$fone         = mysql_result($qry,0,'fone');
  if(strlen($celular) == 10) {
      $cel = telefoneConverte($celular);
  }elseif(strlen($celular) == 11) {
      $cel = telefoneConverte2($celular);
  }else{
     $cel = $celular;
  }
  if(strlen($fone) == 10) {
      $fon = telefoneConverte($fone);
  }elseif(strlen($fone) == 11) {
      $fon = telefoneConverte2($fone);
  }else{
      $fon = $fone;
  }

  $tipo_cliente = mysql_result($qry,0,'tipo_cliente');
  $situacao     = mysql_result($qry,0,'situacao');
  $acao = "2";  
}else{
  $acao = "1";
}

$id_franquia = $_SESSION['id'];

if ( $id_franquia == 163 ) $id_franquia = 1;
?>

<style>
body{
  font-family:Arial, Verdana, sans-serif;
}

.botao {
	background-color: #87b5ff;
	font-family:Arial, Verdana, sans-serif;
	font-weight: bold;
	font-size: 12px;
	height:25px;
	vertical-align: middle;
	border: 1px solid #999999;
	border: 1px solid #999999;
	margin: 0px;
	padding: 0px;
	color: #333333;
  cursor:pointer;
}

.frm_input{
	background-color: #F5F5F5;
	height:22;
  font-size: 13px;
  border: 1px solid #999999;
	border: 1px solid #999999;
}

.topo{
	font-size: 20px;
  height:45px;
	background: #87b5ff;
	font-weight: bold;
  text-align: center;
  font-weight:bold;
}
</style>

<script type="text/javascript" src="../web_control/js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../web_control/js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../web_control/js/jquery.meio.mask.js"></script>
<script type="text/javascript" src="../web_control/js/funcoesJavaDiversas.js"></script>


<script>
(function($){
    // call setMask function on the document.ready event
      $(function(){
        $('input:text').setMask();
      }
    );
  })(jQuery);
  

function retorna(){
 	d = document.form;
    d.action = 'painel.php?pagina1=clientes/lista_consultores.php';
	d.submit();
} 

function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espa�o em branco
                      
function gravar(){
	
 	d = document.form;  
	if(trim(d.nome.value) == ""){
		alert("Falta informar o Nome !");
		d.nome.focus();
		return false;
	}
  if(trim(d.rg.value) == ""){
		alert("Falta informar o RG !");
		d.rg.focus();
		return false;
	}
  if(trim(d.uf.value) == ""){
		alert("Falta informar o Estado do RG !");
		d.uf.focus();
		return false;
	}
  if(trim(d.cpf.value) == ""){
		alert("Falta informar o CPF !");
		d.cpf.focus();
		return false;
	}
  if(trim(d.fone.value) == ""){
		alert("Falta informar o Telefone !");
		d.fone.focus();
		return false;
	}
  if(trim(d.celular.value) == ""){
		alert("Falta informar o Celular !");
		d.celular.focus();
		return false;
	}
  if ((!d.tipo_cliente[0].checked) && (!d.tipo_cliente[1].checked) ){
		alert("Falta informar o Tipo do Consultor !");
		return false;
	}

    d.action = 'painel.php?pagina1=clientes/cadastro_consultores.php&acao=<?=$acao?>';
	d.submit();
} 

(function ($) {
    function mascaraCelular(campo){
     var valor,primeiroBloco,segundoBloco;
 
     valor = $(campo).val();
 
     if(valor.length==0 || valor.length==1){
             $(campo).val("("+valor);
    }else if (valor.length == 3){
             $(campo).val(valor+")");
    }else if (valor.length == 8){
             valor = valor.replace(/-/g,'');
    $(campo).val(valor+"-");
        }else if (valor.length == 13){
        valor = valor.replace(/-/g,'');
    
    primeiroBloco = valor.slice(0,9);
    segundoBloco  = valor.slice(9,13);            
    
    $(campo).val(primeiroBloco+'-'+segundoBloco);
    }
}                       
    function mascaraCelularApagando(campo){
    var valor,primeiroBloco,segundoBloco;
 
    valor = $(campo).val();
 
    if (valor.length == 14){
      valor = valor.replace(/-/g,'');
      primeiroBloco = valor.slice(0,8);
      segundoBloco  = valor.slice(8,12);
    $(campo).val(primeiroBloco+'-'+segundoBloco);
    }
}
    $(document).ready(function(){
    $("input#telefone").keydown(function(event){
    if(event.keyCode in { 48:1, 49:1, 50:1, 51:1, 52:1, 53:1, 54:1, 55:1, 56:1, 57:1, 96:1, 97:1, 98:1, 99:1, 100:1, 101:1, 102:1, 103:1, 104:1, 105:1}){
            mascaraCelular(this);
    }else if (event.keyCode == 8 || event.keyCode == 9){
        return true;
    }else{
        return false;
    }
    });
    $("input#telefone").keyup(function(event){
    if(event.keyCode in {8:1,9:1}){
        mascaraCelularApagando(this);
    }
    });    
});
})(jQuery);    
 

(function ($) {
    function mascaraCelular2(campo){
     var valor,primeiroBloco2, segundoBloco2;
 
     valor = $(campo).val();
 
     if(valor.length==0 || valor.length==1){
             $(campo).val("("+valor);
    }else if (valor.length == 3){
             $(campo).val(valor+")");
    }else if (valor.length == 8){
             valor = valor.replace(/-/g,'');
    $(campo).val(valor+"-");
        }else if (valor.length == 13){
        valor = valor.replace(/-/g,'');
    
    primeiroBloco2 = valor.slice(0,9);
    segundoBloco2  = valor.slice(9,13);            
    
    $(campo).val(primeiroBloco2+'-'+segundoBloco2);
    }
}                       
    function mascaraCelularApagando2(campo){
    var valor,primeiroBloco2, segundoBloco2;
 
    valor = $(campo).val();
 
    if (valor.length == 14){
      valor = valor.replace(/-/g,'');
      primeiroBloco2 = valor.slice(0,8);
      segundoBloco2  = valor.slice(8,12);
    $(campo).val(primeiroBloco2+'-'+segundoBloco2);
    }
}
    $(document).ready(function(){
    $("input#celular").keydown(function(event){
    if(event.keyCode in { 48:1, 49:1, 50:1, 51:1, 52:1, 53:1, 54:1, 55:1, 56:1, 57:1, 96:1, 97:1, 98:1, 99:1, 100:1, 101:1, 102:1, 103:1, 104:1, 105:1}){
        mascaraCelular2(this);
    }else if (event.keyCode == 8 || event.keyCode == 9){
        return true;
    }else{
        return false;
    }
    });
    $("input#celular").keyup(function(event){
    if(event.keyCode in {8:1,9:1}){
        mascaraCelularApagando2(this);
    }
    });    
}); 
})(jQuery);     


</script>  

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/tabela.css" rel="stylesheet" type="text/css" />

<form name="form" method="post" action="#">
<input type="hidden" name="id_update" value="<?=$_REQUEST['id_update']?>">
<input type="hidden" name="nome_tmp" value="<?=$_REQUEST['nome_tmp']?>">
<input type="hidden" name="data_agenda_tmp" value="<?=$_REQUEST['data_agenda_tmp']?>">

<p>&nbsp;</p>
<table border="0" width="640px" height="245px" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #F5F5F5; background-color:#FFFFFF">
  <tr><td colspan="3"  class="topo">Cadastro de Assistentes e Consultores</td></tr>
  <tr>
    <td width="25%" class="subtitulodireita" style="width: 40%">Nome</td>
    <td width="75%" class="subtitulopequeno" colspan="2"><input style="width:90%" onKeyUp="upperCase(this.id)" name="nome" id="id_nome" value="<?=$nome?>" type="text" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" autofocus="autofocus"/></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita" style="width: 40%">RG</td>
    <td class="subtitulopequeno" colspan="2"><input style="width:25%" name="rg" value="<?=$rg?>" type="text" maxlength="20" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita" style="width: 40%">Estado RG</td>
    <td class="subtitulopequeno" colspan="2"><?php include("../funcoes/estado.php"); ?></td>
  </tr>
  
	<tr>
    <td class="subtitulodireita" style="width: 40%">CPF</td>
    <td class="subtitulopequeno" colspan="2"><input style="width:25%" onKeyPress="soNumero(); formatar('###.###.###-##', this)" value="<?=$cpf?>" name="cpf" maxlength="14" type="text" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
    
  <tr>
    <td class="subtitulodireita" style="width: 40%">Fone</td>
    <td class="subtitulopequeno" colspan="2"> <input style="width:25%" name="fone" value="<?=$fon?>" type="text" id="telefone" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>

  <tr>
    <td class="subtitulodireita" style="width: 40%">Celular</td>
    <td class="subtitulopequeno" colspan="2"><input style="width:25%" type="text" name="celular" value="<?=$cel?>" id="celular" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita" style="width: 40%">Tipo Consultor</td>
    <td class="subtitulopequeno" colspan="2">
      <label style="cursor:pointer">Assistente<input type="radio" name="tipo_cliente" value="1"  <?php if($tipo_cliente == "1") { ?> checked <?php } ?> /></label>
      &nbsp;&nbsp;
      <label style="cursor:pointer">Consultor<input type="radio" name="tipo_cliente" value="0"   <?php if($tipo_cliente == "0") { ?> checked <?php } ?> /></label>
    </td>
  </tr>

  <?php if($_REQUEST['id_update']) { ?>
  <tr>
    <td class="subtitulodireita" style="width: 40%">Situação</td>
    <td class="subtitulopequeno" colspan="2">
      <label style="cursor:pointer">Ativo<input type="radio" name="situacao" value="0"     <?php if($situacao == "0") { ?> checked <?php } ?> /></label>
      &nbsp;&nbsp;
      <label style="cursor:pointer">bloqueado<input type="radio" name="situacao" value="1" <?php if($situacao == "1") { ?> checked <?php } ?> /></label>
      &nbsp;&nbsp;
      <label style="cursor:pointer">Cancelado<input type="radio" name="situacao" value="2" <?php if($situacao == "2") { ?> checked <?php } ?> /></label>     
    </td>
  </tr>
  <?php } ?>

    <?php if($id_franquia == 4){ ?>
        <tr>
            <td class="subtitulodireita" style="width: 40%">Foto: </td>
            <td class="subtitulopequeno" >
                Foto 1<input type="file" name="iptFoto1" id="iptFoto1" placeholder="Foto 1">
            </td>
            <td class="subtitulopequeno">
                Foto 2<input type="file" name="iptFoto2" id="iptFoto2" placeholder="Foto 2">
            </td>
        </tr>
    <?php } ?>

  <tr bgColor="#F0F0F6">
    <td colspan="3" align="center">
      <input name="Gravar" type="button" value="    Gravar     " onClick="gravar();"  class="botao" />
      &nbsp;&nbsp;
      <input name="Retorna" type="button" value="     Voltar     " onClick="retorna();"  class="botao" />
    </td>
  </tr>
</table>                                                       
</form>



<?php


 if( ($_REQUEST['acao'] == "1") or ($_REQUEST['acao'] == "2") ) { 
    $nome         = $_REQUEST['nome'];
    $rg           = $_REQUEST['rg'];
    $estado_rg    = $_REQUEST['uf'];
    $cpf          = limpaMascaraCpf($_REQUEST['cpf']);
    $fone         = limparMascaraTelefone($_REQUEST['fone']);
    $celular      = limparMascaraTelefone($_REQUEST['celular']);
    $tipo_cliente = $_REQUEST['tipo_cliente'];    
    $situacao     = $_REQUEST['situacao'];
    $id_update    = $_REQUEST['id_update'];    
}

if($_REQUEST['acao'] == "1") {    
    $sql = "INSERT INTO cs2.consultores_assistente (data_inicio, nome, rg, estado_rg, cpf, fone, celular, tipo_cliente, id_franquia) 
            VALUES (now(), '$nome', '$rg', '$estado_rg', '$cpf', '$fone', '$celular', '$tipo_cliente', '$id_franquia')";
    $qry = mysql_query($sql);

    if($qry){  ?>
         <script>alert("Gravado com Sucesso !");</script>
    <?php
      $acao = 0;
    }else{ ?>
         <script>alert("Erro ao Gravar !");</script>
    <?php
      $acao = 0;
    }
} 

if($_REQUEST['acao'] == "2") {

    if($id_update == 1969){
        var_dump($_REQUEST);
        die;
    }

    $sql = "UPDATE cs2.consultores_assistente SET
                      nome         = '$nome', 
                      rg           = '$rg', 
                      estado_rg    = '$estado_rg', 
                      cpf          = '$cpf',
                      fone         = '$fone', 
                      celular      = '$celular', 
                      tipo_cliente = '$tipo_cliente', 
                      situacao     = '$situacao'
            WHERE           
                      id = '$id_update'";
    $qry = mysql_query($sql);

    if($qry){  ?>
         <script>alert("Alterado com sucesso !");</script>
         <meta http-equiv='refresh' content='0; url=painel.php?pagina1=clientes/cadastro_consultores.php&id_update=<?=$id_update?>&nome_tmp=<?=$_REQUEST['nome_tmp']?>&data_agenda_tmp=<?=$_REQUEST['data_agenda_tmp']?>'>
    <?php
      $acao = 0;
    }else{ ?>
         <script>alert("Erro na alteração tente novamente !");</script>
    <?php
      $acao = 0;
    } 
}
?>