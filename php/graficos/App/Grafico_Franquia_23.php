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
        Gráficos WEB CONTROL EMPRESAS
    </TITLE>
    <SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/FusionCharts.js"></SCRIPT>		
</HEAD>
<BODY topmargin='0' leftmargin='0' bottomMargin='0' rightMargin='0' bgColor='#EEEEEE'>

<?php
    //Render page headers
    if (!empty($_REQUEST['franqueado'])){
        echo render_pageHeader();
    }
?>

<table align='center' cellspacing='0' cellpadding='0'>
    <tr>
    	<td align='left'>
        <?php if (!empty($_REQUEST['franqueado'])){ ?>
        <a href="../../painel.php?pagina1=graficos/i_graficos.php">Retorna</a>
        <?php } ?>
        </td>
    </tr>
    <tr>
	<td align='center'>
            <?php	
            $comprimento = $_REQUEST['x'];
            if ( empty($comprimento) ) $comprimento = '800';

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
            #Define Charts Parameter
            $strParam = "caption=Gráfico - Carnê Próprio;subCaption=$nome_franquia;yAxisName=;xAxisName=;palette=1;animation=".getAnimationState(). ";showValues=1;formatNumberScale=5;numberSuffix=; labelDisplay=ROTATE;numDivLines=5;slantLabels=1";
            # Set Chart Parameter
            $FC->setChartParams($strParam);
            # Get average shiping time xml
            grafico_franquia_23($intYear,$selecao,true,false, $FC);
            # Add some styles to increase caption font size
            $FC->defineStyle("CaptionFont","font","color=" . getCaptionFontColor() . ";size=30");
            $FC->defineStyle("SubCaptionFont","font","bold=1 ; size=20");
            # apply style to Chart�s CAPTION and SUBCAPTION
            $FC->applyStyle("caption","CaptionFont");
            $FC->applyStyle("SubCaption","SubCaptionFont");
            # Set Register With JS true
            $FC->setInitParam("registerwithjs",true);
            # Render objects to XML, Create Chart Output
            $FC->renderChart();
            ?>
	</td>
    </tr>
    
    <?php if( ($_REQUEST['id_franquia_session'] != '163') and ($_REQUEST['id_franquia_session'] != "") ){ ?>
    	<tr><td bgcolor="#CCCCCC">&nbsp;</td></tr>
        <tr>
        <td align="center">
          <iframe src="Grafico_Franquia_14.php" width="100%" height="700" frameborder="0" scrolling="0"></iframe>
        </td>
        </tr>	
		    <tr><td height="100">&nbsp;</td></tr>
    <?php } ?>
</table>
</BODY>
</HTML>
