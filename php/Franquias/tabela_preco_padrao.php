<?php
	include("connect/sessao_r.php");
	include("../connect/conexao_conecta.php");
	include("../connect/funcoes.php");

//print_r($_REQUEST);
function telefoneConverte($p_telefone){
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style>

.borda{
	border-collapse: collapse;	font-size: 10px;font-family:Verdana, Arial, sans-serif;
}
.logotipo{
	font-size: 10px;font-family:Verdana, Arial, sans-serif;
}
.topo{
	font-size: 13px;font-weight: bold;font-family:Verdana, Arial, sans-serif;
}
.topo1{
	font-size: 10px;background-color: #D3D3D3;font-weight: bold;font-family:Verdana, Arial, sans-serif;	height:20;
}
.topo2{
	font-size: 10px;background-color: #EEEEFF;font-weight: bold;font-family:Verdana, Arial, sans-serif;	height:20;
}
.coluna1{
	font-size: 9px;	font-family:Verdana, Arial, sans-serif;height:19;background-color: #F5F5F5;
}
.coluna2{
	font-size: 9px;font-family:Verdana, Arial, sans-serif;height:19;background-color: #FFFFFF;
}
.rodape{
	font-size: 15px;font-family:Verdana, Arial, sans-serif;	font-weight: bold;color:#999;
}
.topo_direito{
	font-size: 10px;font-family:Verdana, Arial, sans-serif;background-color: #FFFFFF;
}
.Titulo_consulta {
	font-family:Verdana, Arial, sans-serif;font-size: 14px;font-weight: bold;
}
</style>
<?php
$sql = "SELECT codcons, nome, valor FROM cs2.valcons WHERE  codcons IN ('A0700', 'A0230', 'A0200', 'A0710', 'A0202', 'A0700', 'A0203', 'A0201','A0207', 'A0208', 'A0301', 'A0406', 'A0408', 'A0405', 'A0407', 'A0410', 'A0400', 'A0401', 'A0404', 'A0403', 'A0402', 'AB201', 'T0001', 'T0002','B201', 'C201', 'B201', 'U0200','U0201','U0202','U0301','A0710','A0208', 'D201', 'E201', 'A0302' , 'A0711' , 'A0115' , 'A0231') ORDER BY codcons ASC ";
$qry = mysql_query($sql);
while($rs = mysql_fetch_array($qry)){
	$codcons = $rs['codcons'];
	if($codcons == 'A0201'){
			$A0201_valor = $rs['valor'];
			$A0201_nome  = $rs['nome'];						
	}else if($codcons == 'A0115'){
			$A0115_valor = $rs['valor'];
			$A0115_nome  = $rs['nome'];
	}else if($codcons == 'A0230'){
			$A0230_valor = $rs['valor'];
			$A0230_nome  = $rs['nome'];			
	}else if($codcons == 'A0231'){
			$A0231_valor = $rs['valor'];
			$A0231_nome  = $rs['nome'];			
	}else if($codcons == 'A0200'){
			$A0200_valor = $rs['valor'];
			$A0200_nome  = $rs['nome'];
	}else if($codcons == 'A0710'){
			$A0710_valor = $rs['valor'];
			$A0710_nome  = $rs['nome'];
	}else if($codcons == 'A0202'){
			$A0202_valor = $rs['valor'];
			$A0202_nome  = $rs['nome'];
	}else if($codcons == 'A0700'){
			$A0700_valor = $rs['valor'];
			$A0700_nome  = $rs['nome'];
	}else if($codcons == 'A0203'){
			$A0203_valor = $rs['valor'];
			$A0203_nome  = $rs['nome'];
	}else if($codcons == 'A0207'){
			$A0207_valor = $rs['valor'];
			$A0207_nome  = $rs['nome'];
	}else if($codcons == 'A0208'){
			$A0208_valor = $rs['valor'];
			$A0208_nome  = $rs['nome'];
	}else if($codcons == 'A0301'){
			$A0301_valor = $rs['valor'];
			$A0301_nome  = $rs['nome'];
	}else if($codcons == 'A0302'){
			$A0302_valor = $rs['valor'];
			$A0302_nome  = $rs['nome'];
	}else if($codcons == 'A0406'){
			$A0406_valor = $rs['valor'];
			$A0406_nome  = $rs['nome'];
	}else if($codcons == 'A0408'){
			$A0408_valor = $rs['valor'];
			$A0408_nome  = $rs['nome'];
	}else if($codcons == 'A0405'){
			$A0405_valor = $rs['valor'];
			$A0405_nome  = $rs['nome'];
	}else if($codcons == 'A0407'){
			$A0407_valor = $rs['valor'];
			$A0407_nome  = $rs['nome'];
	}else if($codcons == 'A0410'){
			$A0410_valor = $rs['valor'];
			$A0410_nome  = $rs['nome'];
	}else if($codcons == 'A0400'){
			$A0400_valor = $rs['valor'];
			$A0400_nome  = $rs['nome'];
	}else if($codcons == 'A0401'){
			$A0401_valor = $rs['valor'];
			$A0401_nome  = $rs['nome'];
	}else if($codcons == 'A0404'){
			$A0404_valor = $rs['valor'];
			$A0404_nome  = $rs['nome'];
	}else if($codcons == 'A0403'){
			$A0403_valor = $rs['valor'];
			$A0403_nome  = $rs['nome'];
	}else if($codcons == 'A0402'){
			$A0402_valor = $rs['valor'];
			$A0402_nome  = $rs['nome'];
	}else if($codcons == 'A0711'){
			$A0711_valor = $rs['valor'];
			$A0711_nome  = $rs['nome'];
	}else if($codcons == 'AB201'){
			$AB201_valor = $rs['valor'];
			$AB201_nome  = $rs['nome'];
	}else if($codcons == 'T0001'){
			$T0001_valor = $rs['valor'];
			$T0001_nome  = $rs['nome'];
	}else if($codcons == 'T0002'){
			$T0002_valor = $rs['valor'];
			$T0002_nome  = $rs['nome'];
	}else if($codcons == 'B201'){
			$B201_valor = $rs['valor'];
			$B201_nome  = $rs['nome'];
	}else if($codcons == 'C201'){
			$C201_valor = $rs['valor'];
			$C201_nome  = $rs['nome'];
	}else if($codcons == 'B201'){
			$B201_valor = $rs['valor'];
			$B201_nome  = $rs['nome'];	
	}else if($codcons == 'U0200'){
			$U0200_valor = $rs['valor'];
			$U0200_nome  = $rs['nome'];
	}else if($codcons == 'U0201'){
			$U0201_valor = $rs['valor'];
			$U0201_nome  = $rs['nome'];
	}else if($codcons == 'U0202'){
			$U0202_valor = $rs['valor'];
			$U0202_nome  = $rs['nome'];
	}else if($codcons == 'U0301'){
			$U0301_valor = $rs['valor'];
			$U0301_nome  = $rs['nome'];
	}else if($codcons == 'A0710'){
			$A0710_valor = $rs['valor'];
			$A0710_nome  = $rs['nome'];
	}else if($codcons == 'A0208'){
			$A0208_valor = $rs['valor'];
			$A0208_nome  = $rs['nome'];
	}else if($codcons == 'D201'){
			$D201_valor = $rs['valor'];
			$D201_nome  = $rs['nome'];
	}else if($codcons == 'E201'){
			$E201_valor = $rs['valor'];
			$E201_nome  = $rs['nome'];
	}
}

if($_REQUEST['tabela'] != 100){

	$sql_grid_cli = "	SELECT 
							   a.nome, a.qtd, b.nome as nome_consulta, a.qtd2, c.nome AS descricao2  
					    FROM tabela_valor a 
						INNER JOIN valcons b ON a.tpcons = b.codcons
						LEFT OUTER JOIN cs2.valcons c on a.tpcons2 = c.codcons 
						WHERE a.categoria = '{$_REQUEST['tabela']}' AND mostrar = 0 ORDER BY a.qtd ASC";
	$qry_grid_cli = mysql_query($sql_grid_cli);
	$total = mysql_num_rows($qry_grid_cli );
}

//homologa��o
$sql_homologa = "SELECT  tx_adesao AS homologa, tx_pacote AS homologa2 
				 FROM franquia WHERE id = '{$_REQUEST['id']}'";
$qry_homologa = mysql_query($sql_homologa);
$homologa = number_format(mysql_result($qry_homologa,0,'homologa'),2,',','.');
$homologa2 = number_format(mysql_result($qry_homologa,0,'homologa2'),2,',','.');

//nome da franquia responsavel
$sql_resp = "SELECT endereco, fone1, fantasia from franquia WHERE id= '{$_REQUEST['id']}'";
$qry_resp = mysql_query($sql_resp);
$endereco = mysql_result($qry_resp,0,'endereco');
$fone     = telefoneConverte(mysql_result($qry_resp,0,'fone1'));
$nome     = mysql_result($qry_resp,0,'fantasia');
?>

<meta charset="iso-8859-1" />
<table border="1" width="99%" align="center" cellpadding="0" cellspacing="2" style="border: 0px solid #D1D7DC; background-color:#FFFFFF"> 
	<tr>
		<td width="40%" valign="top">
			<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr class="coluna2">
					<td align="center"><img src="../../img/tabela_valores/inform.jpg" border="0" height="50" /></td>
				</tr>
				<tr>
					<td align="center" class="logotipo"><b>WEB CONTROL EMPRESAS - Sites, Solu&ccedil;&otilde;es e Pesquisas</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="center" class="logotipo"><b>&nbsp;Franquia Respons�vel:</b>&nbsp;<?=$nome?></td>
				</tr>
				<tr>
					<td align="center" class="logotipo"><b>&nbsp;Endere�o:</b>&nbsp;<?=$endereco?></td>
				</tr>
				<tr>
					<td align="center" class="logotipo"><b>&nbsp;Telefone:</b>&nbsp;<?=$fone?></td> 
				</tr>
			</table>
		</td>
		<td width="60%" valign="top">
			<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF"> 
				<tr>
					<td width="40%" align="center">           
						<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
							<tr>
								<td align="center" class="topo_direito" height="30"><b>RECIBO/ACEITE</b></td>
							</tr>
						</table>
					</td>
					<td width="60%">
						<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
							<tr>
								<td align="left" class="topo_direito" height="30" style="font-size:12px"><b>R$ <?
               echo $homologa; 
			   $x = $homologa;
			   $x = str_replace(',','.',$x);
			   $valor_extenso = trim(extenso($x,true));
			   echo " ($valor_extenso)"?></b>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="30" class="topo_direito"><b>Recebemos de:</b>_________________________________________________</td>
				</tr>
				<tr>
					<td colspan="2" height="25" class="topo_direito"><b>Referente a pagamento de Taxa de Homologa&ccedil;&atilde;o ao Sistema <font color="#0000FF">( J&Aacute; INCLUSO O TREINAMENTO E CONSULTORIA ).</font><br><br>
Obs.: Nenhum outro valor dever&aacute; ser pago no ato da afilia&ccedil;&atilde;o</b></td>
				</tr>
				<tr>
					<td height="40" valign="middle" align="left">_________________________</td>
					<td>_________________________</td>
				</tr>
				<tr align="left" class="topo_direito">
					<td><b>Consultor de Solu��es&nbsp;</b></td>
					<td><b>Aceite do Associado</b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table border="0" align="center" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" class="topo" height="30"><b>
			<?php
			$sql_topo = "SELECT nome FROM tabela_tipo WHERE id = '{$_REQUEST['tabela']}'";
			$qry_topo  = mysql_query($sql_topo);
			echo $nome = mysql_result($qry_topo,0,'nome')."&nbsp;&nbsp;";
			if($_REQUEST['tabela'] == 9) echo "(Acesso: Telefone 0800)";
			else echo "( Internet )";
			?>
			</b>
		</td>
	</tr>
</table>

<?php
# Tabela PROMOCIONAL VEICULO
if($_REQUEST['tabela'] == 15){ ?>
<table border="0" align="center" width="99%" cellpadding="0" cellspacing="0">
<tr>
	<td width="64%" valign="top">
    	<table class="borda" border="1" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">  
        	<tr align="center" class="topo1">
            	<td width="45%">Quantidade</td>
            	<td width="10%">15</td>
                <td width="10%">30</td>
                <td width="10%">60</td>
                <td width="10%">100</td>
                <td width="15%">Homologa&ccedil;&atilde;o</td>
            </tr>
            <tr class="coluna2">
              <?php
			  $cont_1 = 0;
              $sql_1="SELECT
						  tv.categoria, tp.nome AS nome_tabela, tv.nome AS valor, tv.qtd
						FROM
						  tabela_valor tv INNER JOIN
						  tabela_tipo tp ON tv.categoria = tp.id
						WHERE
						  tv.categoria IN(15)";
				$qry_1  = mysql_query($sql_1);
				while($rs_1 = mysql_fetch_array($qry_1)){
			    $cont_1 ++;					  
			  ?>
              
				  <?php if($cont_1 == 1) { ?><td class="coluna2" align="left"><?=$rs_1['nome_tabela']; ?></td> <?php } ?>
				  <?php if($cont_1 == 1) { ?><td class="coluna2" align="center"><?=$rs_1['valor']; ?></td> <?php } ?>
                  <?php if($cont_1 == 2) { ?><td class="coluna2" align="center"><?=$rs_1['valor']; ?></td><?php } ?>
                  <?php if($cont_1 == 3) { ?><td class="coluna2" align="center"><?=$rs_1['valor']; ?></td><?php } ?>
                  <?php if($cont_1 == 4) { ?><td class="coluna2" align="center"><?=$rs_1['valor']; ?></td><?php } ?>
              <?php } ?>
              <td rowspan="10" align="center" class="coluna1">
              	<b>Pesquisas</b><br>
                R$ <?=$homologa?><p>
                <b>Solu��es</b><br>
                R$ <?=$homologa2?>
              </td>
            </tr>
            <tr align="center" class="coluna2">
             <?php
			  $cont_2 = 0;
              $sql_2 = "SELECT
						  tv.categoria, tp.nome AS nome_tabela, tv.nome AS valor, tv.qtd
						FROM
						  tabela_valor tv INNER JOIN
						  tabela_tipo tp ON tv.categoria = tp.id
						WHERE
						  tv.categoria IN(16)";
				$qry_2  = mysql_query($sql_2);
				while($rs_2 = mysql_fetch_array($qry_2)){
			    $cont_2 ++;					  
			  ?>
              
				  <?php if($cont_2 == 1) { ?><td class="coluna2" align="left"><?=$rs_2['nome_tabela']; ?></td> <?php } ?>
				  <?php if($cont_2 == 1) { ?><td class="coluna2" align="center"><?=$rs_2['valor']; ?></td> <?php } ?>
                  <?php if($cont_2 == 2) { ?><td class="coluna2" align="center"><?=$rs_2['valor']; ?></td><?php } ?>
                  <?php if($cont_2 == 3) { ?><td class="coluna2" align="center"><?=$rs_2['valor']; ?></td><?php } ?>
                  <?php if($cont_2 == 4) { ?><td class="coluna2" align="center"><?=$rs_2['valor']; ?></td><?php } ?>
              <?php } ?>
            </tr>
            <tr align="center" class="coluna2">
             <?php
			  $cont_3 = 0;
              $sql_3= "SELECT
						  tv.categoria, tp.nome AS nome_tabela, tv.nome AS valor, tv.qtd
						FROM
						  tabela_valor tv INNER JOIN
						  tabela_tipo tp ON tv.categoria = tp.id
						WHERE
						  tv.categoria IN(17)";
				$qry_3  = mysql_query($sql_3);
				while($rs_3 = mysql_fetch_array($qry_3)){
			    $cont_3 ++;					  
			  ?>
              
				  <?php if($cont_3 == 1) { ?><td class="coluna2" align="left"><?=$rs_3['nome_tabela']; ?></td> <?php } ?>
				  <?php if($cont_3 == 1) { ?><td class="coluna2" align="center"><?=$rs_3['valor']; ?></td> <?php } ?>
                  <?php if($cont_3 == 2) { ?><td class="coluna2" align="center"><?=$rs_3['valor']; ?></td><?php } ?>
                  <?php if($cont_3 == 3) { ?><td class="coluna2" align="center"><?=$rs_3['valor']; ?></td><?php } ?>
                  <?php if($cont_3 == 4) { ?><td class="coluna2" align="center"><?=$rs_3['valor']; ?></td><?php } ?>
              <?php } ?>
            </tr>
            <tr align="center" class="coluna2">
              <?php
			  $cont_4 = 0;
              $sql_4= "SELECT
						  tv.categoria, tp.nome AS nome_tabela, tv.nome AS valor, tv.qtd
						FROM
						  tabela_valor tv INNER JOIN
						  tabela_tipo tp ON tv.categoria = tp.id
						WHERE
						  tv.categoria IN(18)";
				$qry_4  = mysql_query($sql_4);
				while($rs_4 = mysql_fetch_array($qry_4)){
			    $cont_4 ++;					  
			  ?>
              
				  <?php if($cont_4 == 1) { ?><td class="coluna2" align="left"><?=$rs_4['nome_tabela']; ?></td> <?php } ?>
				  <?php if($cont_4 == 1) { ?><td class="coluna2" align="center"><?=$rs_4['valor']; ?></td> <?php } ?>
                  <?php if($cont_4 == 2) { ?><td class="coluna2" align="center"><?=$rs_4['valor']; ?></td><?php } ?>
                  <?php if($cont_4 == 3) { ?><td class="coluna2" align="center"><?=$rs_4['valor']; ?></td><?php } ?>
                  <?php if($cont_4 == 4) { ?><td class="coluna2" align="center"><?=$rs_4['valor']; ?></td><?php } ?>
              <?php } ?>
            </tr>
            <tr align="center" class="coluna2">
              <?php
			  $cont_5 = 0;
              $sql_5= "SELECT
						  tv.categoria, tp.nome AS nome_tabela, tv.nome AS valor, tv.qtd
						FROM
						  tabela_valor tv INNER JOIN
						  tabela_tipo tp ON tv.categoria = tp.id
						WHERE
						  tv.categoria IN(19)";
				$qry_5  = mysql_query($sql_5);
				while($rs_5 = mysql_fetch_array($qry_5)){
			    $cont_5 ++;					  
			  ?>
              
				  <?php if($cont_5 == 1) { ?><td class="coluna2" align="left"><?=$rs_5['nome_tabela']; ?></td> <?php } ?>
				  <?php if($cont_5 == 1) { ?><td class="coluna2" align="center"><?=$rs_5['valor']; ?></td> <?php } ?>
                  <?php if($cont_5 == 2) { ?><td class="coluna2" align="center"><?=$rs_5['valor']; ?></td><?php } ?>
                  <?php if($cont_5 == 3) { ?><td class="coluna2" align="center"><?=$rs_5['valor']; ?></td><?php } ?>
                  <?php if($cont_5 == 4) { ?><td class="coluna2" align="center"><?=$rs_5['valor']; ?></td><?php } ?>
              <?php } ?>
            </tr>
            <tr class="coluna2">
               <?php
			  $cont_6 = 0;
              $sql_6= "SELECT
						  tv.categoria, tp.nome AS nome_tabela, tv.nome AS valor, tv.qtd
						FROM
						  tabela_valor tv INNER JOIN
						  tabela_tipo tp ON tv.categoria = tp.id
						WHERE
						  tv.categoria IN(20)";
				$qry_6  = mysql_query($sql_6);
				while($rs_6 = mysql_fetch_array($qry_6)){
			    $cont_6 ++;					  
			  ?>
              
				  <?php if($cont_6 == 1) { ?><td class="coluna2" align="left"><?=$rs_6['nome_tabela']; ?></td> <?php } ?>
				  <?php if($cont_6 == 1) { ?><td class="coluna2" align="center"><?=$rs_6['valor']; ?></td> <?php } ?>
                  <?php if($cont_6 == 2) { ?><td class="coluna2" align="center"><?=$rs_6['valor']; ?></td><?php } ?>
                  <?php if($cont_6 == 3) { ?><td class="coluna2" align="center"><?=$rs_6['valor']; ?></td><?php } ?>
                  <?php if($cont_6 == 4) { ?><td class="coluna2" align="center"><?=$rs_6['valor']; ?></td><?php } ?>
              <?php } ?>

            </tr>
            <tr align="center" class="coluna2">
              <?php
			  $cont_7 = 0;
              $sql_7= "SELECT
						  tv.categoria, tp.nome AS nome_tabela, tv.nome AS valor, tv.qtd
						FROM
						  tabela_valor tv INNER JOIN
						  tabela_tipo tp ON tv.categoria = tp.id
						WHERE
						  tv.categoria IN(21)";
				$qry_7  = mysql_query($sql_7);
				while($rs_7 = mysql_fetch_array($qry_7)){
			    $cont_7 ++;					  
			  ?>
              
				  <?php if($cont_7 == 1) { ?><td class="coluna2" align="left"><?=$rs_7['nome_tabela']; ?></td> <?php } ?>
				  <?php if($cont_7 == 1) { ?><td class="coluna2" align="center"><?=$rs_7['valor']; ?></td> <?php } ?>
                  <?php if($cont_7 == 2) { ?><td class="coluna2" align="center"><?=$rs_7['valor']; ?></td><?php } ?>
                  <?php if($cont_7 == 3) { ?><td class="coluna2" align="center"><?=$rs_7['valor']; ?></td><?php } ?>
                  <?php if($cont_7 == 4) { ?><td class="coluna2" align="center"><?=$rs_7['valor']; ?></td><?php } ?>
              <?php } ?>
            </tr>
            
            <tr align="center" class="coluna2">
           	<?php
			  $cont_8 = 0;
              $sql_8= "SELECT
						  tv.categoria, tp.nome AS nome_tabela, tv.nome AS valor, tv.qtd
						FROM
						  tabela_valor tv INNER JOIN
						  tabela_tipo tp ON tv.categoria = tp.id
						WHERE
						  tv.categoria IN(21)";
				$qry_8  = mysql_query($sql_8);
				while($rs_8 = mysql_fetch_array($qry_8)){
			    $cont_8 ++;					  
			  ?>
              
				  <?php if($cont_8 == 1) { ?><td class="coluna2" align="left"><?=$rs_8['nome_tabela']; ?></td> <?php } ?>
				  <?php if($cont_8 == 1) { ?><td class="coluna2" align="center"><?=$rs_8['valor']; ?></td> <?php } ?>
                  <?php if($cont_8 == 2) { ?><td class="coluna2" align="center"><?=$rs_8['valor']; ?></td><?php } ?>
                  <?php if($cont_8 == 3) { ?><td class="coluna2" align="center"><?=$rs_8['valor']; ?></td><?php } ?>
                  <?php if($cont_8 == 4) { ?><td class="coluna2" align="center"><?=$rs_8['valor']; ?></td><?php } ?>
              <?php } ?>
            </tr>
			<tr align="center" class="coluna2">
           	<?php
			  $cont_9 = 0;
              $sql_9= "SELECT
						  tv.categoria, tp.nome AS nome_tabela, tv.nome AS valor, tv.qtd
						FROM
						  tabela_valor tv INNER JOIN
						  tabela_tipo tp ON tv.categoria = tp.id
						WHERE
						  tv.categoria IN(22)";
				$qry_9  = mysql_query($sql_9);
				while($rs_9 = mysql_fetch_array($qry_9)){
			    $cont_9 ++;					  
			  ?>
              
				  <?php if($cont_9 == 1) { ?><td class="coluna2" align="left"><?=$rs_9['nome_tabela']; ?></td> <?php } ?>
				  <?php if($cont_9 == 1) { ?><td class="coluna2" align="center"><?=$rs_9['valor']; ?></td> <?php } ?>
                  <?php if($cont_9 == 2) { ?><td class="coluna2" align="center"><?=$rs_9['valor']; ?></td><?php } ?>
                  <?php if($cont_9 == 3) { ?><td class="coluna2" align="center"><?=$rs_9['valor']; ?></td><?php } ?>
                  <?php if($cont_9 == 4) { ?><td class="coluna2" align="center"><?=$rs_9['valor']; ?></td><?php } ?>
              <?php } ?>
            </tr>

              <tr align="center" class="coluna2">
           	<?php
			  $cont_10 = 0;
              $sql_10= "SELECT
						  tv.categoria, tp.nome AS nome_tabela, tv.nome AS valor, tv.qtd
						FROM
						  tabela_valor tv INNER JOIN
						  tabela_tipo tp ON tv.categoria = tp.id
						WHERE
						  tv.categoria IN(23)";
				$qry_10  = mysql_query($sql_10);
				while($rs_10 = mysql_fetch_array($qry_10)){
			    $cont_10 ++;					  
			  ?>
              
				  <?php if($cont_10 == 1) { ?><td class="coluna2" align="left"><?=$rs_10['nome_tabela']; ?></td> <?php } ?>
				  <?php if($cont_10 == 1) { ?><td class="coluna2" align="center"><?=$rs_10['valor']; ?></td> <?php } ?>
                  <?php if($cont_10 == 2) { ?><td class="coluna2" align="center"><?=$rs_10['valor']; ?></td><?php } ?>
                  <?php if($cont_10 == 3) { ?><td class="coluna2" align="center"><?=$rs_10['valor']; ?></td><?php } ?>
                  <?php if($cont_10 == 4) { ?><td class="coluna2" align="center"><?=$rs_10['valor']; ?></td><?php } ?>
              <?php } ?>
			</tr>
		</table>
	</td>
	<td width="1%">&nbsp;</td>
		<td width="35%" valign="top">
			<table class="borda" border="1" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
				<tr>
					<td colspan="2" align="center" class="topo1">Outras Solu&ccedil;&otilde;es</td>
				</tr>
				<tr>
					<td width="50%" class="coluna1">Bloqueio Registro Online Brasil</td>
					<td width="50%" class="coluna2">&nbsp;&nbsp;R$ <?=$B201_valor?></td>
				</tr>
				<tr>
					<td class="coluna1">Desbloqueio de Devedores</td>
					<td class="coluna2">&nbsp;&nbsp;R$ <?=$C201_valor?></td>
				</tr>
				<tr>
					<td class="coluna1">Adicional M&ecirc;s de Dezembro</td>
					<td class="coluna2">&nbsp;&nbsp;Valor Mensal Pesquisas e Solu&ccedil;&otilde;es</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php } ?>

<?php if($_REQUEST['tabela'] == 1199999){ ?>
<table border="0" align="center" width="99%" cellpadding="0" cellspacing="0">
<tr>
	<td width="55%" valign="top">
    	<table class="borda" border="1" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">  
        	<tr align="center" class="topo1">
            	<td width="25%">Op&ccedil;&atilde;o</td>
                <td width="25%">Mensal Pesquisas</td>
                <td width="25%">Mensal Solu&ccedil;&otilde;es</td>
                <td width="25%">Homologa&ccedil;&atilde;o</td>
            </tr>
           <?php
				$cont=0;
				while($rs_grid_cli = mysql_fetch_array($qry_grid_cli)) { 
				$cont++;
			?>
            <tr align="center" class="coluna2">
            	<td class="coluna1">(&nbsp;&nbsp;&nbsp;)</td>
                <td class="coluna2">R$  <?=$rs_grid_cli['nome']?></td>
                <td class="coluna2">R$ 19,90</td>
                <?php if($cont == 1){?> <td rowspan="<?=$total?>"> <?php }?>                
                	
                    <?php if($cont == 1){?>
                    	<b>Pesquisas</b><br>
                     	R$ <?=$homologa?><p>
                        <b>Solu��es</b><br>
                        R$ <?=$homologa2?>
				    
					<?php }?>
                    
                <?php if($cont == $total){?></td><?php }?>
            </tr>
            <?php } ?>
        </table>
    </td>
    <td width="1%">&nbsp;</td>
    <td width="44%" valign="top">
   <table class="borda" border="1" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
        	<tr><td colspan="2" align="center" class="topo1">Outros Servi&ccedil;os</td></tr>
            <tr><td width="50%" class="coluna1">Bloqueio Registro Online Brasil</td><td width="50%" class="coluna2">R$ <?=$B201_valor?></td></tr>
            <tr><td class="coluna1">Desbloqueio de Devedores</td><td class="coluna2">R$ <?=$C201_valor?></td></tr>
            <tr><td class="coluna1">Adicional M&ecirc;s de Dezembro</td>
            <td class="coluna2">Valor Mensal Pesquisas e Solu&ccedil;&otilde;es</td></tr>            
      </table>
    </td>
</tr>
	<tr>
    <td valign="top">
    	<table class="borda" border="1" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
            <tr class="topo1" align="center"><td width="50%">Pesquisas</td><td>Valor Unit�rio</td></tr>              
            <tr align="center"><td class="coluna1">R$&nbsp;<?=$U0200_valor?></td><td class="coluna2"><?=$U0200_nome?></td></tr>   
            <tr align="center"><td class="coluna1">R$&nbsp;<?=$U0201_valor?></td><td class="coluna2"><?=$U0201_nome?></td></tr>   
            <tr align="center"><td class="coluna1">R$&nbsp;<?=$U0202_valor?></td><td class="coluna2"><?=$U0202_nome?></td></tr>   
            <tr align="center"><td class="coluna1">R$&nbsp;<?=$U0301_valor?></td><td class="coluna2"><?=$U0301_nome?></td></tr>   
            <tr align="center"><td class="coluna1">R$&nbsp;<?=$A0710_valor?></td><td class="coluna2"><?=$A0710_nome?></td></tr>   
            <tr align="center"><td class="coluna1">R$&nbsp;<?=$A0208_valor?></td><td class="coluna2"><?=$A0208_nome?></td></tr>   
      </table>
    </td>
	<td></td>
    </tr>	
</table>
<?php }


if($_REQUEST['tabela'] == 100){ ?>
	<table border="0" width="99%" align="center" cellpadding="0" cellspacing="0">  
    <tr><td align="center">
    
    <table class="borda" border="1" width="30%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">      
    <tr><td colspan="2" align="center" class="topo1">Desconto M�ximo Revers�o</td></tr>
    <tr align="center"><td width="20%">(&nbsp;&nbsp;&nbsp;)</td><td width="80%">5%</td></tr>
    <tr align="center"><td>(&nbsp;&nbsp;&nbsp;)</td><td>10%</td></tr>
    <tr align="center"><td>(&nbsp;&nbsp;&nbsp;)</td><td>15%</td></tr>
    <tr align="center"><td>(&nbsp;&nbsp;&nbsp;)</td><td>20%</td></tr>	
    </table>
    </td></tr>
    
    <tr><td align="center"><b>OBS:</b> N�O SER� PERMITIDO DESCONTOS ACIMA DE 20% SOBRE O<br> PACOTE DE PESQUISAS  E SOLU��ES.</td></tr>
    </table>    
<?php } ?>

<?php if( ($_REQUEST['tabela'] == 1) or ($_REQUEST['tabela'] == 2)or ($_REQUEST['tabela'] == 3)or ($_REQUEST['tabela'] == 26)or ($_REQUEST['tabela'] == 6)or ($_REQUEST['tabela'] == 5) or ($_REQUEST['tabela'] == 27)or ($_REQUEST['tabela'] == 24) or ($_REQUEST['tabela'] == 10) or ($_REQUEST['tabela'] == 12) or ($_REQUEST['tabela'] == 11) or ($_REQUEST['tabela'] == 13) or ($_REQUEST['tabela'] == 9) or ($_REQUEST['tabela'] == 29) or ($_REQUEST['tabela'] == 31)){
	?>
<table border="0" align="center" width="99%" cellpadding="0" cellspacing="0">
<tr>
	<td width="60%" valign="top">
    	<table class="borda" border="1" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">  
        	<tr align="center" class="topo1">
            	<td width="5%"> Op&ccedil;&atilde;o</td>
                <td width="15%">Pacote Solu��es</td>
                <td width="20%">Licen�as Softwares</td>
                <td width="25%">Gratuidade Pesquisas</td>                
                <td width="35%">Homologa&ccedil;&atilde;o</td>
            </tr>
            <?php
				$cont=0;
				while($rs_grid_cli = mysql_fetch_array($qry_grid_cli)) { 
				$cont++;
			?>
            <tr align="center" class="coluna2">
            	<td class="coluna1">(&nbsp;&nbsp;&nbsp;)</td>
                <td class="coluna2">R$ <?=$rs_grid_cli['nome']?></td>
                <td class="coluna1">R$ 19,90</td>
                <td class="coluna2">
				<?php
					if($_REQUEST['tabela'] == 1 or $_REQUEST['tabela'] == 31){
						echo $rs_grid_cli['qtd2'].' '.$rs_grid_cli['descricao2']." - ";	
						echo $rs_grid_cli['qtd'].' '.$rs_grid_cli['nome_consulta'];
					}else if($_REQUEST['tabela'] == 29){
						echo $rs_grid_cli['qtd2'].' '.$rs_grid_cli['descricao2']." - ";	
						echo $rs_grid_cli['qtd'].' '.$rs_grid_cli['nome_consulta'];
					}else{
					    echo $rs_grid_cli['qtd'].' '.$rs_grid_cli['nome_consulta'];
					}
				?></td>                
                <?php if($cont == 1){?> <td rowspan="<?=$total?>"> <?php }?>                
                	
                    <?php if($cont == 1){?>
                    	<?php
                        	$homologa = str_replace(".","",$homologa);
							$homologa = str_replace(",",".",$homologa);
							
							$homologa2 = str_replace(".","",$homologa2);
							$homologa2 = str_replace(",",".",$homologa2);
							
							$tmp_total = $homologa  +$homologa2;
							echo "R$ ".number_format($tmp_total,2,',','.');
							/*
							echo "<font color='#0000FF'><br><br>
							1 Recomenda&ccedil;&atilde;o = 05% Desconto<br>
							2 Recomenda&ccedil;&atilde;o = 10% Desconto<br>
							3 Recomenda&ccedil;&atilde;o = 20% Desconto<br>
							4 Recomenda&ccedil;&atilde;o = 30% Desconto<br>
							</font>";
							*/
						?>
				    <?php }?>
                    
                <?php if($cont == $total){?></td><?php }?>
            </tr>
            <?php } ?>
        </table>
    </td>
    <td width="1%">&nbsp;</td>
    <td width="44%" valign="top">
   <table class="borda" border="1" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
        	<tr>
            <td colspan="2" align="center" class="topo1">Outros Servi&ccedil;os</td></tr>
            <tr><td width="50%" class="coluna1">Bloqueio Registro Online Brasil</td>
            <td width="50%" class="coluna2">R$ <?=$B201_valor?></td></tr>
            
            <tr><td class="coluna1">Desbloqueio de Devedores</td><td class="coluna2">R$ <?=$C201_valor?></td></tr>
            
             <tr>
              <td class="coluna1">Bloqueio Devedores - Outros Conv&ecirc;nios</td>
              <td class="coluna2">R$ <?=$D201_valor?></td>
            </tr>
            
            <tr>
              <td class="coluna1">Desbloqueio Devedores - Outros Conv&ecirc;nios</td>
              <td class="coluna2">R$ <?=$E201_valor?></td>
            </tr>
          
            <tr><td class="coluna1">Adicional M&ecirc;s de Dezembro</td>
            <td class="coluna2">Valor Mensal Pesquisas e Solu&ccedil;&otilde;es</td></tr>            
      </table>
    </td>
</tr>
</table>
<?php } ?>
<?php if ( $_REQUEST['tabela'] == 29 ){ ?>
    <td width="1%"><font color="#FF0000">&nbsp;* Pre&ccedil;o promocional, v&aacute;lido somente para os 3 primeiros meses.</font></td>
<?php } ?>

<table border="0" align="center" width="99%" cellspacing="1" cellpadding="0">
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td class="Titulo_consulta" style=" background:url(../images/fd_titulo.jpg); background-repeat:no-repeat;"><font color="#ff6600">Solu&ccedil;&otilde;es Exclusivas</font> para sua Empresa</td>
  </tr>
</table>
  


<table border="0" align="center" width="99%" cellspacing="1" cellpadding="0">
<tr><td>

<table border="0" align="center" width="99%" cellspacing="1" cellpadding="0">  
  <tr align="center">
   <td colspan="2">
   	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" height="70" width="25%"><img src="../../../../images/virtual_flex.png" alt="" height="70" border="0" width="120" /></td></tr></table>
   </td>
   
   <td colspan="2">
   	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr>
   	  <td align="center" height="70" width="25%"><img src="https://www.webcontrolempresas.com.br/images/logo_boleto_system.png" border="0" height="75" width="120" /></td></tr></table>
   </td>
   
   <td colspan="2">
   	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" height="70" width="25%"><img src="../../img/tabela_valores/recupere.jpg" border="0" height="75" width="120"/></td></tr></table>
   </td>   
   
    <td colspan="2">
   	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" height="70" width="25%"><img src="../../img/tabela_valores/web_control.jpg" border="0" height="75" width="120" /></td></tr></table>
   </td>   
  
  </tr>
 <tr class="coluna2">
    <td width="16%" align="left">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr>
    	  <td align="center" class="coluna2">Seu Site na Internet</td></tr></table>    </td>
    
    <td align="center" width="9%">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr>
    	  <td align="center" class="coluna2">Incluso no Pacote</td></tr></table>
    </td>
        
    <td width="16%" align="left">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" class="coluna2">Gerador de Boletos</td></tr></table>
    </td>
    
    <td align="center" width="9%">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" class="coluna2">2% T.A.</td></tr></table>
    </td>
    
    <td width="16%" align="left">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" class="coluna2">Parcelamento para Devedores</td></tr></table>
    </td>   
    
    <td align="center" width="9%">
	    <table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr>
	      <td align="center" class="coluna2">2% T.A.</td></tr></table>
    </td>
    
    <td width="15%" align="left">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" class="coluna2">Controle Total da sua Empresa</td></tr></table>
    </td>
    
    <td align="center" width="10%">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" class="coluna2">Incluso no Pacote</td></tr></table>
    </td>
  </tr>
</table>


</td></tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
  
  
<table border="0" align="center" width="99%" cellspacing="1" cellpadding="0">  
  <tr align="center">
   <td colspan="2">
   	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" height="70" width="25%"><img src="../../img/tabela_valores/localiza_max_2.jpg" border="0" width="60" height="50" /><br>
        <img src="../../img/tabela_valores/localiza_max.jpg" border="0" width="100" height="25" /></td></tr></table>
   </td>
   
   <td colspan="2">
   	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" height="70" width="25%"><img src="../../img/tabela_valores/cartorio.jpg" border="0" height="70" width="120" /></td></tr></table>
   </td>
   
   <td colspan="2">
   	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" height="70" width="25%"><img src="../../../../images/logo_contabil_solution.jpg" border="0" height="70" width="120" /></td></tr></table>
   </td>   
   
    <td colspan="2">
   	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" height="70" width="25%"><img src="../../img/tabela_valores/registro_online.jpg" alt="" height="70" border="0" width="120" /></td></tr></table>
   </td>   
  
  </tr>
  <tr class="coluna2">
    <td align="left" width="15%">
      <table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr>
      <td align="center" class="coluna2">Localiza��o M�xima Pessoas<br>Localiza Max Marketing/Propaganda
      </td></tr></table>
    </td>
    
    <td width="10%" align="center"><table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr>
      <td align="center" class="coluna2">R$ <?=$A0230_valor?><br>
      R$ <?=$A0231_valor?> unidade</td>
    </tr>
    </table></td> 			
    
    <td width="15%" align="left">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" class="coluna2">Encaminhamento Protesto</td></tr></table>
    </td>
    
    <td width="10%" align="center">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" class="coluna2">R$ <?=$T0001_valor?></td></tr></table>
    </td>
    
    <td width="15%" align="left">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr>
    	  <td align="center" class="coluna2">Solu&ccedil;&otilde;es Cont&aacute;beis</td></tr></table>
    </td>   
    
    <td width="10%" align="center">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr>
    	  <td align="center" class="coluna2">Incluso no Pacote</td></tr></table>
    </td>   
    
     <td width="15%" align="left">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr>
    	  <td align="center" class="coluna2">Inform Nacional<br>
    	   Outros Conv&ecirc;nios</td></tr></table>
    </td>   
    
    <td width="10%" align="center">
    	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="2" style="border: 2px solid #D1D7DC; background-color:#FFFFFF"><tr><td align="center" class="coluna2">
        R$ <?=$B201_valor?><br>
        R$ <?=$D201_valor?></td></tr></table>
    </td>   
    
  </tr>
</table>




<?php if($_REQUEST['tabela'] != 9) { ?>
<table border="0" align="center" width="99%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
  <tr>
    <td class="Titulo_consulta" style=" background:url(../images/fd_titulo.jpg); background-repeat:no-repeat;"><font color="#ff6600">Pesquisas</font> e Cadastro de Cr&eacute;dito</td>
  </tr>
</table>
  
  
<table class="borda" border="1" width="99%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">  
  <tr align="center" class="topo2">
    <td colspan="2">Pesquisa para CPF</td>
    <td colspan="2">Pesquisa para CNPJ</td>
    <td colspan="2">Pesquisa para Cheques</td>
    <td colspan="2">An&aacute;lise Veicular</td>
  </tr>
  <tr>
    <td width="14%" class="coluna1"><?=$A0230_nome?></td>
    <td width="7%" class="coluna2">R$ <?=$A0230_valor?></td>
    <td width="17%" class="coluna1"><?=$A0200_nome?></td>
    <td width="8%" class="coluna2">R$ <?=$A0200_valor?></td>
    <td width="17%" class="coluna1"><?=$A0200_nome?></td>
    <td width="8%" class="coluna2">R$ <?=$A0200_valor?></td>
    <td width="22%" class="coluna1"><?=$A0405_nome?></td>
    <td width="8%" class="coluna2">R$ <?=$A0405_valor?></td>
  </tr>
  
  <tr>
    <td class="coluna1"><?=$A0200_nome?></td>
    <td class="coluna2">R$ <?=$A0200_valor?></td>
    <td class="coluna1"><?=$A0710_nome?></td>
    <td class="coluna2">R$ <?=$A0710_valor?></td>
    <td class="coluna1"><?=$A0201_nome?></td>
    <td class="coluna2">R$ <?=$A0201_valor?></td>
    <td class="coluna1"><?=$A0408_nome?></td>
    <td class="coluna2">R$ <?=$A0408_valor?></td>
  </tr>
  <tr>
    <td class="coluna1"><?=$A0710_nome?></td>
    <td class="coluna2">R$ <?=$A0710_valor?></td>
    <td class="coluna1"><?=$A0302_nome?></td>
    <td class="coluna2">R$ <?=$A0302_valor?></td>
    <td class="coluna1">&nbsp;</td>
    <td class="coluna2">&nbsp;</td>
    <td class="coluna1"><?=$A0406_nome?></td>
    <td class="coluna2">R$ <?=$A0406_valor?></td>
  </tr>
  <tr>
    <td class="coluna1"><?=$A0202_nome?></td>
    <td class="coluna2">R$ <?=$A0202_valor?></td>
    <td class="coluna1"><?=$A0115_nome?></td>
    <td class="coluna2">R$ <?=$A0115_valor?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="coluna1"><?=$A0407_nome?></td>
    <td class="coluna2">R$ <?=$A0407_valor?></td>
  </tr>
  <tr>
    <td class="coluna1"><?=$A0207_nome?></td>
    <td class="coluna2">R$ 
    <?=$A0207_valor?></td>
    <td class="coluna1"><?=$A0711_nome?></td>
    <td class="coluna2">R$ <?=$A0711_valor?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="coluna1"><?=$A0410_nome?></td>
    <td class="coluna2">R$ <?=$A0410_valor?></td>
  </tr>
  <tr>
    <td class="coluna1"><?=$A0301_nome?></td>
    <td class="coluna2">R$
    <?=$A0301_valor?></td>
    <td class="coluna1">&nbsp;</td>
    <td class="coluna2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="coluna1"><?=$A0400_nome?></td>
    <td class="coluna2">R$ <?=$A0400_valor?></td>
  </tr>
  <tr>
    <td class="coluna1">&nbsp;</td>
    <td class="coluna2">&nbsp;</td>
    <td class="coluna1">&nbsp;</td>
    <td class="coluna2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="coluna1"><?=$A0401_nome?></td>
    <td class="coluna2">R$ <?=$A0401_valor?></td>
  </tr>
  <tr>
    <td class="coluna1">&nbsp;</td>
    <td class="coluna2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="coluna1"><?=$A0404_nome?></td>
    <td class="coluna2">R$ <?=$A0404_valor?></td>
  </tr>
  <tr>
    <td class="coluna1">&nbsp;</td>
    <td class="coluna2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="coluna1"><?=$A0403_nome?></td>
    <td class="coluna2">R$ <?=$A0403_valor?></td>
  </tr>
  <tr>
    <td class="coluna1">&nbsp;</td>
    <td class="coluna2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="coluna1"><?=$A0402_nome?></td>
    <td class="coluna2">R$ <?=$A0402_valor?></td>
  </tr>
</table>
<?php } ?>

<?php if($_REQUEST['tabela'] != 9) { ?>
<table border="0" align="center" width="99%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
  <tr>
    <td class="Titulo_consulta" style=" background:url(../images/fd_titulo.jpg); background-repeat:no-repeat;"><font color="#ff6600">Adicionais</font> Tarifados &agrave; Parte</td>
  </tr>
</table>


<table border="0" align="center" width="99%" cellspacing="0" cellpadding="0">
 <tr>      
<td width="50%">
<table class="borda" border="1" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF"> 
        	<tr><td width="70%" class="coluna1">Participa&ccedil;&otilde;es</td>
        	<td width="30%" class="coluna2">R$ 3,75</td>
       	    <tr><td class="coluna1">Quadro Social</td>
       	    <td class="coluna2">R$ 3,75</td></tr>
            <tr><td class="coluna1">Avalia&ccedil;&atilde;o de Risco - Credit Riskscoring</td>
            <td class="coluna2">R$ 3,75</td>
            </tr>
        </table>
    </td>
	
    <td width="50%">
      <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center"><img src="../../img/tabela_valores/rodape.jpg" border="0" height="50" width="300"></td></tr>   
      </table>
    </td>
  </tr>
</table>
<?php } else { ?>
<table border="0" align="center" width="99%" cellspacing="0" cellpadding="0">
 <tr>      
<td width="50%">
<table class="borda" border="1" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF"> 
<tr><td align="center"><img src="../../img/tabela_valores/rodape.jpg" border="0" height="50" width="300"></td></tr>
</table>
<?php } ?>