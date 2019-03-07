<?php

require "connect/sessao.php";

/*
echo "<pre>";
print_r ( $_SESSION );

print_r($_REQUEST );
*/

$codloja = $_GET['codloja'];
$pagina = $_GET['pagina'];

include "ocorrencias/config.php";
include "ocorrencias/javascript.php";

$natureza_ocorr = array("geral", "Cobran&ccedil;a", "Atendimento", "Administrativo", "Comercial");

$sql3 = "SELECT MID(b.logon,1,5) as logon, a.codloja, a.razaosoc, a.nomefantasia, a.fone, a.fone_res, a.celular 
		FROM cadastro a
		INNER JOIN logon b ON a.codloja=b.codloja
		WHERE a.codloja='$codloja' LIMIT 1";
$resulta = mysql_query($sql3, $con);
$matriz = mysql_fetch_array($resulta);

$sql = "SELECT atendente, tipo_ocorr, ocorrencia, date_format(data,'%d/%m/%Y %H:%i') as data, protocolo 
		FROM $table_name WHERE codigo='$codloja' order by id desc LIMIT 0, 30";
$conex = mysql_query($sql, $con);
$ordem = mysql_num_rows($conex);

// Faz os calculos da paginacao
$sql2 = mysql_query("SELECT a.atendente, a.tipo_ocorr, a.ocorrencia, date_format(a.data,'%d/%m/%Y %H:%i') as data, 
					a.protocolo, b.atendente as atdte
					FROM cs2.ocorrencias a
					LEFT OUTER JOIN cs2.atendentes b on a.id_atendente = b.id
					WHERE a.codigo='$codloja' and a.atendente <> 'Tecnologia' order by a.id desc",$con);
$total = mysql_num_rows($sql2); // Esta funcao ira retornar o total de linhas na tabela
$paginas = ceil($total / $lpp); // Retorna o total de paginas
if(!isset($pagina)) { $pagina = 0; } // Especifica uma valor para variavel pagina caso a mesma nao esteja setada
$inicio = $pagina * $lpp; // Retorna qual sera a primeira linha a ser mostrada no MySQL
$sql2 = mysql_query("SELECT a.id, a.atendente, a.tipo_ocorr, a.ocorrencia, 
					date_format(a.data,'%d/%m/%Y %H:%i') as data, a.protocolo, b.atendente as atdte
					FROM cs2.ocorrencias a
					LEFT OUTER JOIN cs2.atendentes b on a.id_atendente = b.id
					WHERE a.codigo='$codloja' and a.atendente <> 'Tecnologia' order by a.id desc
					LIMIT $inicio, $lpp",$con); // Executa a query no MySQL com o limite de linhas.

function telefoneConverteOco($p_telefone){
     if ($p_telefone == '') {
	   return ('');
	 } else if (strlen($p_telefone) == 11) {
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
	 }  else if (strlen($p_telefone) == 10) {
         $a = substr($p_telefone, 0,2);
         $b = substr($p_telefone, 2,4);
         $c = substr($p_telefone, 6,4);

         $telefone_mascarado  = "(";
         $telefone_mascarado .= $a;
         $telefone_mascarado .= ")&nbsp;";
         $telefone_mascarado .= $b;
         $telefone_mascarado .= "-";
         $telefone_mascarado .= $c;
         return ($telefone_mascarado);
     }
}
?>

<script language="javascript">
function deletar(){
	if(confirm("Tem certeza que deseja Excluir a Mensagem ?")) {
	} else {
		return false
	}
}

function alterar(){
	frm = document.form;
	frm.action = 'painel.php?pagina1=ocorrencias/mensagem_bd.php';
	frm.submit();
}

function Envio_CPD(){
	frm = document.form;
	frm.action = 'painel.php?pagina1=ocorrencias/mensagens_cpd.php';
	frm.submit();
}

</script>
<style>
.bordasimples{
	border-collapse: collapse;
	border-top-width:1px;
	border-right-width:1px;
	border-bottom-width:1px;
	border-left-width:1px;
	font-size: 11px;
	color: #000000;
	font-face: verdana, Arial, helvetica, sans-serif;
	height: 100px
}
</style>
<form method="post" action="painel.php?pagina1=ocorrencias/postar.php&codloja=<?=$codloja?>" name="form" >
<input type="hidden" name="codloja" value="<?=$codloja?>" />

<table align="center" border="0" width="100%">
<tr>
<td class="titulo">
<?php echo $titulo; ?> DO CLIENTE</td>
</tr>
<tr>
	<td align="center">
		  <?php
		  //echo '<pre>';
		  //print_r($matriz);
		  //echo '</pre>';
		  ?>
		<table width="80%" border="0">
			<tr>
				<td width="50%" class="subtitulodireita">C&oacute;digo:</td>
				<td width="50%" class="subtitulopequeno"><?=$matriz['logon']?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Razão Social</td>
				<td class="subtitulopequeno"><?=$matriz['razaosoc']?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Nome de Fantasia</td>
				<td class="subtitulopequeno"><?=$matriz['nomefantasia']?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Telefone Comercial</td>
				<td class="subtitulopequeno"><?=telefoneConverteOco($matriz['fone'])?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Telefone Residencial</td>
				<td class="subtitulopequeno"><?=telefoneConverteOco($matriz['fone_res'])?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Telefone Celular</td>
				<td class="subtitulopequeno"><?=telefoneConverteOco($matriz['celular'])?></td>
			</tr>
		</table>
	</td>
</tr>

<tr><td class="titulo">&Uacute;ltimas Ocorr&ecirc;ncias Registradas</td></tr>

<tr><td align="center">
	<input type="submit" value="Nova Ocorr&ecirc;ncia" name="nova_ocorr" />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Boleto Eletr&ocirc;nico" onclick="Envio_CPD()" /></td></tr>

</table>
<p>
<?php
if ($total == 0) {
echo "<p align=\"center\"><font size=\"1\" face=\"Verdana\">N&atilde;o h&aacute; nenhuma ocorr&ecirc;ncia registrada at&eacute; o presente momento.</font></p>";
} else {
while($valor = mysql_fetch_array($sql2)) { ?>
<p>
<div class="bordaBox">
<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
<div class="conteudo">
	<table width="100%" border="1" cellpadding="1" cellspacing="1" class="bordasimples">
		<tr>
			<td width="15%"><b>Protocolo de Atendimento:</b></td>
			<td width="75%"><?php echo $valor[protocolo]; ?></td>
		    <td width="10%" align="center">
            	<?php if($_SESSION['id'] == 163) { ?>
                    <?php if( ($_REQUEST['alterar'] == 'S') and ($_REQUEST['id'] == $valor['id']) ){ ?>
                    <a href="painel.php?pagina1=ocorrencias/mensagens.php&id=<?=$valor['id']?>&codloja=<?=$codloja?>&acao=1&alterar=N"><u><b><font style="font-size:12px">Cancela</font></b></u></a>
                    <?php } else { ?>
                    <a href="painel.php?pagina1=ocorrencias/mensagens.php&id=<?=$valor['id']?>&codloja=<?=$codloja?>&acao=1&alterar=S&pagina=<?=$_REQUEST['pagina']?>"><u><b><font style="font-size:12px">Editar</font></b></u></a>
                    <?php } ?>
                <?php } ?>
            </td>
		</tr>

		<tr>
		  <td width="15%"><b>Data e Hora da Ocorr&ecirc;ncia:</b></td>
		  <td width="75%"><?php echo $valor[data]; ?></td>
          <td width="10%">&nbsp;</td>
	    </tr>

        <tr>
		  <td width="15%"><b>Tipo de Ocorr&ecirc;ncia:</b></td>
		  <td width="75%"><?php $xuxa = $valor['tipo_ocorr'];
			  	echo $natureza_ocorr[$xuxa]."<br>";
			  ?></td> 
		  <td width="10%" align="center">
            	<?php if($_SESSION['id'] == 163) { ?>
           	<a href="painel.php?pagina1=ocorrencias/mensagem_bd.php&id=<?=$valor['id']?>&codloja=<?=$codloja?>&acao=1&pagina=<?=$_REQUEST['pagina']?>" onclick="return deletar()"><u><b><font style="font-size:12px">Excluir</font></b></u></a>
                <?php } ?>
            </td>
		</tr>

        <tr>
		  <td width="15%"><b>Atendente:</b></td>
		  <td width="75%"><?php echo ($valor['atendente'] == ''? $valor['atdte'] : $valor['atendente']  )  ?> </td>
          <td width="10%">&nbsp;</td>
	    </tr>

        <tr class="campoesquerda">
			<td width="15%" valign="top"><b>Descri&ccedil;&atilde;o:</b></td>
			<td width="75%">
				<?php if( ($_REQUEST['alterar'] == 'S') and ($_REQUEST['id'] == $valor['id']) and ($_SESSION['id'] == 163) ){ ?>
			<textarea rows="5" style="width:100%" name="ocorrencia" class="bordasimples"><?=$valor[ocorrencia];?></textarea>
                    <input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
                    <input type="hidden" name="codloja" value="<?=$_REQUEST['codloja']?>">
                    <input type="hidden" name="pagina" value="<?=$_REQUEST['pagina']?>">
                    <input type="button" value="Confirma a Altera&ccedil;&atilde;o" onclick="alterar()" />
                    <input type="hidden" name="acao" value="2">
				<?php }else {
					echo $valor[ocorrencia];
				}
				?>
            </td>
            <td width="10%">&nbsp;</td>
	    </tr>

	</table>

</div>
<b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>
</div>
</p>
<hr noshade="noshade" size="1" width="60%" color="#c0c8c0" align="center" />
</p>
<?php
 }
}
?>
    </form>
<p align="center">
<?php
//destroi o array da natureza da ocorrencia
unset($natureza_ocorr);

//Paginacao
if($pagina > 0) {
   $menos = $pagina - 1;
   $url = "$paginacao[link]pagina1=ocorrencias/mensagens.php&codloja=$codloja&pagina=$menos";
   echo "<a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Anterior'; return true\">Anterior</a>"; // Vai para a pagina anterior
}
for($i=0;$i<$paginas;$i++) { // Gera um loop com o link para as paginas
   $url = "$paginacao[link]pagina1=ocorrencias/mensagens.php&codloja=$codloja&pagina=$i";

   if($_REQUEST['pagina'] == $i){
   	echo " | <a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Pagina $i'; return true\"><b><font color='red'><u>$i</u></font></b></a>";
   }else{
	echo " | <a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Pagina $i'; return true\">$i</a>";
   }
}
if($pagina < ($paginas - 1)) {
   $mais = $pagina + 1;
   $url = "$paginacao[link]pagina1=ocorrencias/mensagens.php&codloja=$codloja&pagina=$mais";
   echo " | <a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Proxima'; return true\">Próxima</a>";
}
?>
</p>
<font face="Arial" size="2">
<p align="center">
Temos um total de <b><?php echo $total ?></b> <?php if ($ordem == "1") echo "ocorr&ecirc;ncia registrada"; else echo "ocorr&ecirc;ncias registradas"; ?>!
</p>

<!--
<p align="center">< ? echo "<a onclick=\"abrir('$link[login]')\" href=\"#\"><font color=#FF0000>&Aacute;rea restrita</font></a>"; ?></p>
</font>
-->

<?php if($_REQUEST['mens'] == 1){?>
	<script>alert('Ocorrência enviada com sucesso !');</script>
<?php } ?>