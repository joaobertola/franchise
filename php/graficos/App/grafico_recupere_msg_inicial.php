<?php
include("Includes/Connection_inc.php");
include("Includes/FusionCharts_Gen.php");
include("Includes/FusionCharts.php");
include("Includes/PageLayout.php");
include("DataGen.php");
?>
<HTML>
<HEAD>
	<TITLE>
		Graficos InformSystem
	</TITLE>

	<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/FusionCharts.js"></SCRIPT>		
</HEAD>
<BODY topmargin='0' leftmargin='0' bottomMargin='0' rightMargin='0' bgColor='#EEEEEE'>

<?php
	//Render page headers
	//echo render_pageHeader();
?>

<table align='center' cellspacing='0' cellpadding='0'>
	<tr>
    	<td align='center'></td>
    </tr>
	<tr>
	<td align='center'>

	<?php
	
	$comprimento = $_REQUEST['x'];
	if ( empty($comprimento) ) $comprimento = '680';
		
	$largura = $_REQUEST['y'];
	if ( empty($largura) ) $largura = '400';

	$idfranquia = $_REQUEST['franqueado'];
	$FC = new FusionCharts("Column3D",$comprimento,$largura," ");
	$FC -> setSWFPath("FusionCharts/");

	if ( ($idfranquia == '9999999') or (empty($idfranquia)) ){
		$nome_franquia = 'Todas as Franquias';
	} else{
		$selecao = " a.id_franquia = $idfranquia AND "; 
		$nome_franquia = nome_franquia($idfranquia);
	}
	
	$data = date('d/m/Y');
	# Define Charts Parameter
	$strParam = "caption=Recupere System ;subCaption=$nome_franquia - Últimos 12 Meses;yAxisName=;xAxisName=;palette=1;animation=".getAnimationState(). ";showValues=1;formatNumberScale=5;numberSuffix=; labelDisplay=ROTATE;numDivLines=5;slantLabels=1";
	# Set Chart Parameter
	$FC->setChartParams($strParam);
	# Get average shiping time xml
	grafico_franquia_03($intYear,$selecao,true,false, $FC);
	# Add some styles to increase caption font size
	$FC->defineStyle("CaptionFont","font","color=" . getCaptionFontColor() . ";size=30");
	$FC->defineStyle("SubCaptionFont","font","bold=1 ; size=20");
	# apply style to Chart’s CAPTION and SUBCAPTION
    $FC->applyStyle("caption","CaptionFont");
    $FC->applyStyle("SubCaption","SubCaptionFont");
	# Set Register With JS true
	$FC->setInitParam("registerwithjs",true);
	# Render objects to XML, Create Chart Output
	$FC->renderChart();
	?>
	</td>
	</tr>
</table>
</BODY>
</HTML>
