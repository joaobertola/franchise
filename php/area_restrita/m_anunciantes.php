<?php

/**
 * Desenvolvido por um programador Web control
 */

require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";

if (isset($_POST["cancelar_anunciante"])) {
	$sql = "UPDATE cs2.anunciantes SET ativo = 'N' WHERE id = {$_POST["cancelar_anunciante"]}";
	$qry = mysql_query($sql, $con);

	if ($qry) {
		echo "<p><label style='color:blue'>Cancelado com sucesso!</label></p>";
	}
}

if (isset($_POST["ativar_anunciante"])) {
	$sql = "UPDATE cs2.anunciantes SET ativo = 'S' WHERE id = {$_POST["ativar_anunciante"]}";
	$qry = mysql_query($sql, $con);

	if ($qry) {
		echo "<p><label style='color:blue'>Renovado com sucesso!</label></p>";
	}
}

$sql = "SELECT cs2.anunciantes.*, cs2.cadastro.nomefantasia, count(base_web_control.log_anuncios_relatorio.id_anuncio) as acessos 
FROM cs2.anunciantes 
LEFT JOIN base_web_control.log_anuncios_relatorio 
ON base_web_control.log_anuncios_relatorio.id_anuncio = cs2.anunciantes.id 
INNER JOIN cs2.cadastro
ON cs2.anunciantes.codloja = cs2.cadastro.codloja
GROUP BY cs2.anunciantes.id";

$qry 	= mysql_query($sql, $con) or die($sql);
$total 	= mysql_num_rows($qry);

?>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>

<form method="post" action="">
	<table class="anunciantes-table" border="0" align="center" width="640">

		<thead bgcolor="#CFCFCF">
			<tr>
				<td colspan="9" class="titulo">CADASTRO DE ANUNCIANTES</td>
				<td><a href="painel.php?pagina1=area_restrita/m_incluir_anunciantes.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><input type="button" name="submit" value="Adicionar" /></a></td>
			</tr>
			<tr>
				<th>Campanha</th>
				<th>Id Cliente</th>
				<th>Nome Fanstasia</th>
				<th>Tipo</th>
				<th>Data Inicio</th>
				<th>Data Fim</th>
				<th>Tipo Sistema</th>
				<th>Clicks</th>
				<th>Situacao</th>
				<th>Operacao</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$a_cor = array("#eee", "#FFFFFF");
			$cont = 0;

			while ($res = mysql_fetch_array($qry)) {
			
				echo "<tr bgcolor='{$a_cor[$cont % 2]}'>";

				echo "<td class='tdSel' align=center>{$res["id"]}</td>";
				echo "<td class='tdSel'>{$res["codloja"]}</td>";
				echo "<td class='tdSel'>{$res["nomefantasia"]}</td>";

				if ($res["tipo"] == "B") {
					echo "<td class='tdSel'>BOLETO</td>";
				}
				if ($res["tipo"] == "S") {
					echo "<td class='tdSel'>SISTEMA</td>";
				}
				if ($res["tipo"] == "C") {
					echo "<td class='tdSel'>CONSULTOR</td>";
				}

				//echo "<td class='tdSel'>R$ ".number_format($res["valor"],2,",",".")."</td>";
				echo "<td class='tdSel'>" . date("d/m/Y", strtotime($res["data_inicio"])) . "</td>";
				echo "<td class='tdSel'>" . date("d/m/Y", strtotime($res["data_fim"])) . "</td>";
				echo "<td class='tdSel' style = 'text-transform: uppercase;' align=center>" . $res["tipo_sistema"] . "</td>";
				echo "<td class='tdSel' align = center><a style='color: blue;' href = 'painel.php?pagina1=area_restrita/m_anunciantes_relatorio.php&id_anuncio={$res["id"]}'>" . $res["acessos"] . "</a></td>";

				if ($res["ativo"] == "S") {
					echo "<td class='tdSel'>Ativo</td>";
				}
				if ($res["ativo"] == "N") {
					echo "<td class='tdSel'>Nao Ativo</td>";
				}

				echo "<td style='text-align:center'>";
				echo "<a style='color: blue;' href = 'painel.php?pagina1=area_restrita/m_editar_anunciantes.php&id_anuncio={$res["id"]}'><img src= 'https://www.pngfind.com/pngs/m/70-704184_png-file-svg-pencil-edit-icon-png-transparent.png' width = '30'/></a>";
				echo "</td>";

				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</form>