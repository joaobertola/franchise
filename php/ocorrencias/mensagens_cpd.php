<?php


require "connect/sessao.php";

$codloja = $_REQUEST['codloja'];
$pagina = $_REQUEST['pagina'];

include "ocorrencias/config.php";
include "ocorrencias/javascript.php";

$natureza_ocorr = array("geral", "Cobran&ccedil;a", "Atendimento", "Administrativo", "Comercial");

$sql3 = "SELECT MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, a.codloja, a.nomefantasia, a.fone, a.fone_res, a.celular 
		FROM cadastro a
		INNER JOIN logon b ON a.codloja=b.codloja
		WHERE a.codloja='$codloja' LIMIT 1";
$resulta = mysql_query($sql3, $con);
$matriz = mysql_fetch_array($resulta); 

$sql = "SELECT atendente, tipo_ocorr, ocorrencia, date_format(data,'%d/%m/%Y %H:%i') as data, protocolo 
		FROM $table_name WHERE codigo='$codloja' order by id desc LIMIT 0, 30";
$conex = mysql_query($sql, $con);
$ordem = mysql_num_rows($conex);

// Faz os calculos da pagina��o
$sql2 = "SELECT a.atendente, a.tipo_ocorr, a.ocorrencia, date_format(a.data,'%d/%m/%Y %H:%i') as data, 
				a.protocolo, b.atendente as atdte
		 FROM cs2.ocorrencias a
		 LEFT OUTER JOIN cs2.atendentes b on a.id_atendente = b.id
		 WHERE a.codigo='$codloja' and a.atendente = 'Tecnologia' order by a.id desc";

$qry_sql2 = mysql_query($sql2, $con);
$total = mysql_num_rows($qry_sql2); // Esta fun��o ir� retornar o total de linhas na tabela
$paginas = ceil($total / $lpp); // Retorna o total de p�ginas
if(!isset($pagina)) { $pagina = 0; } // Especifica uma valor para variavel pagina caso a mesma n�o esteja setada
$inicio = $pagina * $lpp; // Retorna qual ser� a primeira linha a ser mostrada no MySQL

$sql2 = mysql_query("SELECT a.id, a.atendente, a.tipo_ocorr, a.ocorrencia, 
					date_format(a.data,'%d/%m/%Y %H:%i') as data, a.protocolo, b.atendente as atdte
					FROM cs2.ocorrencias a
					LEFT OUTER JOIN cs2.atendentes b on a.id_atendente = b.id
					WHERE a.codigo='$codloja' and a.atendente = 'Tecnologia' order by a.id desc
					LIMIT $inicio, $lpp", $con); // Executa a query no MySQL com o limite de linhas.

function telefoneConverteOco($p_telefone){
     if ($p_telefone == '') {
	   return ('');	   
	 } else { 	   
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

<table class="table table-striped table-responsive col65" align="center">
    <thead>
        <tr>
            <th>
                <h4 class="text-center"> <?php echo $titulo; ?> DO CLIENTE</h4>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <table>
                    <tr>
                        <td width="50%" class="text-right">C&oacute;digo:</td>
                        <td width="50%" class="text-left"><?=$matriz['logon']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Nome de Fantasia</td>
                        <td class="text-left"><?=$matriz['nomefantasia']?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Telefone Comercial</td>
                        <td class="text-left"><?=telefoneConverteOco($matriz['fone'])?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Telefone Residencial</td>
                        <td class="text-left"><?=telefoneConverteOco($matriz['fone_res'])?></td>
                    </tr>
                    <tr>
                        <td class="text-right">Telefone Celular</td>
                        <td class="text-left"><?=telefoneConverteOco($matriz['celular'])?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
          <td class="text-center"> Ocorrências Registradas pelo CPD ( Tecnologia )</td></tr>
        <tr>
    </tbody>
    <tfoot>
        <tr>
            <td>
                <input type="submit" value="Nova Ocorrência" name="nova_ocorr" class="botao3d" />
            </td>
        </tr>
	</tfoot>
</table>
<p>
<?php
if ($total == 0) {
echo "<p align=\"center\"><font size=\"1\" face=\"Verdana\">Não há nenhuma ocorrência registrada pelo CPD até o presente momento.</font></p>";
} else {
while($valor = mysql_fetch_array($sql2)) { ?>
<p>
	<table  class="table table-striped table-responsive col65" style="width: 100%;" align="center">
		<tr>
			<td class="text-right col50"><b>Protocolo de Atendimento:</b></td>
			<td class="text-left"><?php echo $valor[protocolo]; ?></td>
		    <td align="center">
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
		  <td class="text-right col50"><b>Data e Hora da Ocorrência:</b></td>
		  <td  class="text-left"><?php echo $valor[data]; ?></td>
          <td>&nbsp;</td>
	    </tr>
		
        <tr>
		  <td class="text-right col50"><b>Tipo de Ocorrência:</b></td>
		  <td class="text-left"><?php $xuxa = $valor['tipo_ocorr'];
			  	echo $natureza_ocorr[$xuxa]."<br>";
			  ?></td>
		  <td align="center">
            	<?php if($_SESSION['id'] == 163) { ?>
           	<a href="painel.php?pagina1=ocorrencias/mensagem_bd.php&id=<?=$valor['id']?>&codloja=<?=$codloja?>&acao=1&pagina=<?=$_REQUEST['pagina']?>" onclick="return deletar()"><u><b><font style="font-size:12px">Excluir</font></b></u></a>
                <?php } ?>
            </td>
		</tr>
		
        <tr>
		  <td class="text-right col50"><b>Atendente:</b></td>
		  <td class="text-left"><?=$valor['atendente']?><?=$valor['atdte']?></td>
          <td>&nbsp;</td>
	    </tr>
		
        <tr class="campoesquerda">
			<td class="text-right col50" valign="top"><b>Descrição:</b></td>
			<td  class="text-left">
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

//Pagina��o
if($pagina > 0) {
   $menos = $pagina - 1;
   $url = "$paginacao[link]pagina1=ocorrencias/mensagens.php&codloja=$codloja&pagina=$menos";
   echo "<a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Anterior'; return true\">Anterior</a>"; // Vai para a p�gina anterior
}
for($i=0;$i<$paginas;$i++) { // Gera um loop com o link para as p�ginas
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
	<script>alert('Ocorr�ncia enviada com sucesso !');</script>
<?php } ?>