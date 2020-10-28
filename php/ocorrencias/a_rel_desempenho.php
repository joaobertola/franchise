<?php
require "connect/sessao.php";
require "connect/conexao_conecta.php";

$franquia  = $_SESSION['id'];
$in_franquia = "($franquia)";
$codigo    = $_REQUEST['codigo'];
$inicial   = $_REQUEST['inicial'];
$final 	   = $_REQUEST['final'];
$atendente = $_REQUEST['atendente'];
$id_atendente = $_REQUEST['atendente'];
$relatorio = $_REQUEST['relatorio'];

$inicio = implode(preg_match("~\/~", $inicial) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $inicial) == 0 ? "-" : "/", $inicial)));

$fim 	= implode(preg_match("~\/~", $final) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $final) == 0 ? "-" : "/", $final)));

switch ($franquia){
	case 1:
	case 4:
	case 5:
	case 46:
	case 59:
	case 247:
	case 163:
	case 1204:
	case 1205:
		$in_franquia = '(1,4,2,46,59,163,247,1204,1205)';
}
	

//ser for pelo codigo do cliente
$sql_cli = "SELECT b.codloja
            FROM logon a
			INNER JOIN cadastro b on a.codloja=b.codloja
			WHERE CAST(MID(a.logon,1,6) AS UNSIGNED) = '{$codigo}' ";
$qry_cli = mysql_query ($sql_cli, $con);
$codloja = @mysql_result($qry_cli,0,0);

$sql = "SELECT
		  o.id_atendente, o.codigo, o.atendente, o.franquia, o.tipo_ocorr, 
		  a.atendente, o.ocorrencia, o.protocolo, 
		  date_format(o.data,'%d/%m/%Y - %h:%i')AS data, CAST(MID(l.logon,1,6) AS UNSIGNED) as logon
		FROM
		  atendentes a 
		INNER JOIN ocorrencias o ON o.id_atendente = a.id 
		INNER JOIN logon l on o.codigo = l.codloja
		WHERE
		  o.franquia IN $in_franquia";
		  
		 //busca por codigo do cliente 
		if($_REQUEST['relatorio'] == 1){   
			$sql .=" AND l.codloja = '$codloja' ";
		}		
		//busca por data
		if($_REQUEST['relatorio'] == 2){   
			$sql .=" AND date_format(o.data,'%Y-%m-%d') BETWEEN '$inicio' AND '$fim' ";
		}		
		//busca por todos os atendente
		if($_REQUEST['atendente'] > 0){ 
			$sql .=" AND a.id = '{$_REQUEST['atendente']}' ";
		}	

$sql .=" ORDER BY o.data  ";
//echo '<pre>';
//echo $sql;
//die;
$qry = mysql_query ($sql, $con);
$total = mysql_num_rows ($qry);
?>
<script language="javascript">
function retorna(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=ocorrencias/a_desempenho.php';
	frm.submit();
} 
</script>
<form name="form" method="post" action="#">

<?php if($total == 0){?>
<table width="740" border="0" cellpadding="0" cellspacing="0">
<tr height="20" class="titulo">
<td align="center" width="100%">Nenhuma Ocorr&ecirc;ncia Encontrada</td></tr>
<td align="center" width="100%"><input type="button" value="Voltar" name="Voltar" onclick="retorna()"></td></tr>
</table>
<?php exit; }  ?>

<table width="740" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td class="titulo">RELATORIO DE DESEMPENHO DE COBRANÇAS</td>
</tr>
</table>

<table width="740" border="0" cellpadding="0" cellspacing="1" class="bodyText">
<tr bgColor='#B6CBF6'>
<td width='9%'><b>C&oacute;digo</b></td>
<td width='9%'><b>Protocolo</b></td>
<td width='14%'><b>Data</b></td>
<td width='14%'><b>Atendente</b></td>
<td align='justify'><b>Ocorr&ecirc;ncias</b></td>
</tr>
</tr>

<?php 
$a_cor = array('#FFFFFF', '#F6F6F9');
$cont=0;
while($rs = mysql_fetch_array($qry)){
$cont++;	
?>
	<tr bgcolor="<?=$a_cor[$cont % 2]?>">
    	<td><?=$rs['logon']?></td>
        <td><?=$rs['protocolo']?></td>
        <td><?=$rs['data']?></td>
        <td>
		<?php
            if($rs['atendente'] == ''){
                $sql_at = "SELECT atendente AS n_atendente FROM cs2.atendentes WHERE id = '{$rs[id_atendente]}'";	
                $qry_at = mysql_query ($sql_at, $con);
                 echo mysql_result($qry_at,0,'n_atendente');
            }else{
                 echo $rs['atendente'];
            }				
        ?>
        </td>        
        <td><?=$rs['ocorrencia']?></td>
    </tr>
<?php } ?>

<tr>
<td colspan="3" align="left"><input type="button" value="Voltar" name="Voltar" onclick="retorna()"></td>
<td colspan="2" align="right"><b> Total de <font color="#FF0000"><?=$total?></font> Ocorr&ecirc;ncias no Periodo <font color="#FF0000"><?=$inicial?></font> - <font color="#FF0000"><?=$final?></font></b></td></tr>

<tr><td colspan="5">&nbsp;</td></tr>
</table>

</form>  
