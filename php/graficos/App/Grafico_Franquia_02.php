<?php
include("Includes/Connection_inc.php");
include("Includes/FusionCharts_Gen.php");
include("Includes/FusionCharts.php");
include("Includes/PageLayout.php");
include("DataGen.php");
?>
<HTML>

<HEAD>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<TITLE>
		Gráficos WEB CONTROL EMPRESAS
	</TITLE>
	<style>

	</style>

	<SCRIPT LANGUAGE="Javascript" SRC="js/jquery.min.js"></SCRIPT>
	<SCRIPT LANGUAGE="Javascript" SRC="js/loader.js"></SCRIPT>
</HEAD>

<BODY topmargin='0' leftmargin='0' bottomMargin='0' rightMargin='0' bgColor='#EEEEEE'>

	<?php
	//Render page headers
	if (!empty($_REQUEST['franqueado'])) {
		echo render_pageHeader();
	}
	?>

	<table align='center' cellspacing='0' cellpadding='0'>
		<tr>
			<td align='left'>
				<?php if (!empty($_REQUEST['franqueado'])) { ?>
				<a href="../../painel.php?pagina1=graficos/i_graficos.php">Retorna</a>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td align='center'>
				<?php

				$FC = new FusionCharts("Column3D", $comprimento, $largura, " ");
				$FC->setSWFPath("FusionCharts/");

				if (($idfranquia == '9999999') or (empty($idfranquia))) {
					$nome_franquia = 'Todas as Franquias';
				} else {
					$selecao = " a.id_franquia = $idfranquia AND ";
					$nome_franquia = nome_franquia($idfranquia);
				}

				#Define Charts Parameter
				$strParam = "caption=Gráfico de Solucoes: Localiza Max ;subCaption=$nome_franquia - últimos 12 Meses;yAxisName=;xAxisName=;palette=1;animation=" . getAnimationState() . ";showValues=1;formatNumberScale=5;numberSuffix=; labelDisplay=ROTATE;numDivLines=5;slantLabels=1";
				# Set Chart Parameter
				$FC->setChartParams($strParam);
				# Get average shiping time xml

				$res = grafico_franquia_novo_02($intYear, $selecao, true, false, $FC);
				$i = 0;

				foreach ($res as $key) {
					$resjs .= "['" . $key['Country'] . "', " . $key['Average'] . ", '" . $key['color'] . "'],";
				}

				?>
				<script type="text/javascript">
					google.charts.load("current", {
						packages: ['corechart']
					});
					google.charts.setOnLoadCallback(drawChart);

					function drawChart() {

						var data = google.visualization.arrayToDataTable([
							["Element", "Quantidade", {
								role: "style"
							}],
							<?php
							echo $resjs;
							?>
						]);

						var view = new google.visualization.DataView(data);
						view.setColumns([0, 1,
							{
								calc: "stringify",
								sourceColumn: 1,
								type: "string",
								role: "annotation"
							},
							2
						]);

						var options = {
							title: "Gráfico de Pesquisas",
							width: 1700,
							height: 800,
							bar: {
								groupWidth: "95%"
							},
							legend: {
								position: "top"
							},
						};
						var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
						chart.draw(view, options);
					}
				</script>
				<div id="columnchart_values" style="width: 100%; height: 300px;"></div>
			</td>
		</tr>

		<?php if (($_REQUEST['id_franquia_session'] != '163') and ($_REQUEST['id_franquia_session'] != "")) { ?>
		<tr>
			<td bgcolor="#CCCCCC">&nbsp;</td>
		</tr>
		<tr>
			<td align="center">
				<iframe src="Grafico_Franquia_02.php" width="100%" height="700" frameborder="0" scrolling="0"></iframe>
			</td>
		</tr>
		<tr>
			<td height="100">&nbsp;</td>
		</tr>
		<?php } ?>
	</table>
</BODY>

</HTML>