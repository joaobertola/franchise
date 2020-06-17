<?php 

/**
 * Desenvolvido por um programador Web control
 */

require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";

if(isset($_GET["id_anuncio"]))
{
	$sql 		= "SELECT tipo_sistema FROM cs2.anunciantes WHERE id = ".$_GET["id_anuncio"];
   	$qry 		= mysql_query($sql, $con) or die($sql);
   	$anuncio 	= mysql_fetch_row($qry);

   	if($anuncio[0] == 'lead') 
   	{
   		$sql = "SELECT relatorio.nome, relatorio.fone, relatorio.email, relatorio.data_cadastro 
   		 		FROM base_web_control.log_anuncios_relatorio as relatorio
    			INNER JOIN cs2.anunciantes ON anunciantes.id = relatorio.id_anuncio
    			WHERE id_anuncio = ".$_GET["id_anuncio"];
    }
    else
    {
    	$sql = "SELECT cadastro.razaosoc, relatorio.data_cadastro 
    			FROM base_web_control.log_anuncios_relatorio as relatorio
    			INNER JOIN cs2.anunciantes ON anunciantes.id = relatorio.id_anuncio
    			INNER JOIN cs2.cadastro ON cadastro.codloja = relatorio.id_cadastro
    			WHERE id_anuncio = ".$_GET["id_anuncio"];
    }

	if($_POST['dataInicio']) {
   		$sql .= " AND relatorio.data_cadastro >= '".$_POST['dataInicio']."'";
   	}

   	if($_POST['dataFim']) {
   		$sql .= " AND relatorio.data_cadastro <= '".$_POST['dataFim']."'";
   	}

   	$qry 	= mysql_query($sql, $con) or die($sql);
	$total 	= mysql_num_rows($qry);
}
else
{
    echo "<p><label style='color:red'>Pesquisa inválida!</label></p>";
}
?>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>

<form action="" method="post">
	<table border="0" align="center" width="640" style='margin-bottom: 10px;'>
		<tbody bgcolor="#CFCFCF">
			<tr>
				<th>Data Inicio</th>
				<td><input type='date' name='dataInicio' style="width: 100% important;"></td>
			</tr>
			<tr>
				<th>Data Fim</th>
				<td><input type='date' name='dataFim' style="width: 100% important;"></td>
			</tr>
			<tr>
    			<td>&nbsp;</td>
    			<td>
    				<input type='submit' name='pesquisar' value="Pesquisar" />
    				<input type='reset' name='' value='Limpar' />
    			</td>
    		</tr>
		</tbody>
	</table>
</form>

<?php
	if(empty($total) && isset($_GET["id_anuncio"])) {
    	echo "<p><label style='color:red'>Nenhum registro encontrado</label></p>";
	}
	else if($anuncio[0] == 'lead')
	{		
?>
    	<table border="0" align="center" width="640">
      
     	<thead bgcolor="#CFCFCF">
     		<tr>
            	<td colspan="8" class="titulo">Relatório de anúncios</td>
        	</tr>
     		<tr>
     			<th>Nome</th>
     			<th>Fone</th>
     			<th>Email</th>
				<th>Data</th>
     		</tr>
     	</thead> 
	 	<tbody>
	 	<?php 
    	 	$a_cor = array("#eee", "#FFFFFF");
    	 	$cont = 0;
	 
	   		while ($res = mysql_fetch_array($qry)) { 
	       		echo "<tr bgcolor='{$a_cor[$cont % 2]}'>";
	       			echo "<td class='tdSel' align=center>{$res["nome"]}</td>";
		   			echo "<td class='tdSel'>".mascaraFone($res["fone"])."</td>";
		   			echo "<td class='tdSel' align=center>".$res["email"]."</td>";
	       			echo "<td class='tdSel' align=center>".date("d/m/Y",strtotime($res["data_cadastro"]))."</td>";
	       		echo "</tr>";
	   		}
	 	?>
	 	</tbody>      
	</table>
<?php
	}else{
?>
	<table border="0" align="center" width="640">
      
     	<thead bgcolor="#CFCFCF">
     		<tr>
            	<td colspan="8" class="titulo">Relatório de anúncios</td>
        	</tr>
     		<tr>
     			<th>Razão social</th>
				<th>Data</th>
     		</tr>
     	</thead> 
	 	<tbody>
	 	<?php 
    	 	$a_cor = array("#eee", "#FFFFFF");
    	 	$cont = 0;
	 
	   		while ($res = mysql_fetch_array($qry)) { 
	       		echo "<tr bgcolor='{$a_cor[$cont % 2]}'>";
	       			echo "<td class='tdSel' align=center>{$res["razaosoc"]}</td>";
	       			echo "<td class='tdSel' align=center>".date("d/m/Y",strtotime($res["data_cadastro"]))."</td>";
	       		echo "</tr>";
	   		}
	 	?>
	 	</tbody>      
	</table>
<?php
	}
?>