<?php

include("Includes/Connection_inc.php");
include("Includes/FusionCharts.php");
include("Includes/FusionCharts_Gen.php");
include("Includes/PageLayout.php");
include("DataGen.php");

?>
<html>
<head>
	<title>
	FusionCharts v3 Demo Application
	</title>
	<?php
	//You need to include the following JS file, if you intend to embed the chart using JavaScript.
	//Embedding using JavaScripts avoids the "Click to Activate..." issue in Internet Explorer
	//When you make your own charts, make sure that the path to this JS file is correct. Else, you would get JavaScript errors.
	?>	
	<script LANGUAGE="Javascript" SRC="FusionCharts/FusionCharts.js"></script>		
	<script LANGUAGE="JavaScript">				
	//We keep flags to check whether the charts have loaded successfully.
	//By default, we assume them to false. When each chart loads, it calls
	//a JavaScript function FC_Rendered, in which we'll update the flags
	var empChartLoaded=false;
	var catChartLoaded=false;
	var prodChartLoaded=false;
	
	
	/**
	 * FC_Rendered function is invoked by all FusionCharts charts which are registered
	 * with JavaScript. To this function, the chart passes its own DOM Id. We can check
	 * against the DOM id and update charts or loading flags.
	 *	@param	DOMId	Dom Id of the chart that was succesfully loaded.
	*/
	function FC_Rendered(DOMId){
		//Here, we update the loaded flags for each chart
		//Since we already know the charts in the page, we use conditional loop
		switch(DOMId){
			case "TopEmployees":				
				empChartLoaded = true;
				break;
			case "SalesByCat":				
				catChartLoaded = true;
				break;
			case "SalesByProd":				
				prodChartLoaded = true;
				break;
		}
		return;
	}
	/** 
	 * updateCharts method is invoked when the user clicks on a column in the sales by year chart.
	 * In this method, we get the year as value, after which we request for XML data
	 * to update the sales by category chart and top employees chart.
	 *	@param	year	Year for which we've to show data.
	*/		
	function updateCharts(year){	
		//Update the checkboxes present on the same page.
		var i;
		//Iterate through all checkboxes and match the selected year
		for (i=0; i<document.frmYr.year.length; i++){			
			if(parseInt(document.frmYr.year[i].value,10)==year){
				document.frmYr.year[i].checked = true;				
			}else{
				document.frmYr.year[i].checked = false;
			}
		}		
		
		//Now, update the Sales by category chart, if it's loaded
		if (catChartLoaded){
			//DataURL for the categories chart
			var strURL = "Data_SalesByCategory.php?year=" + year;
		    //Sometimes, the above URL and XML data gets cached by the browser.
			//If you want your charts to get new XML data on each request,
			//you can add the following line:
			strURL = strURL + "&currTime=" + getTimeForURL();
			//getTimeForURL method is defined below and needs to be included
			//This basically adds a ever-changing parameter which bluffs
			//the browser and forces it to re-load the XML data every time.
		
			//We cache the data for the categories chart, as this data is
			//not frequently changing. So, it will enhance user's experience.
								
			//URLEncode it - NECESSARY.
			strURL = escape(strURL);		
			//Get reference to chart object using Dom ID "SalesByCat"
			var chartObj = getChartFromId("SalesByCat");		
			//Send request for XML
			chartObj.setDataURL(strURL);		
		}else{
			//The chart has not loaded till now. We need to wait.
			//So either show some message to the user or do something as your requirements...
			alert("Please wait for the charts to load");
			return;
		}
		
		//Now, update the employees chart, if loaded.
		if (empChartLoaded){
			var strURL = "Data_TopEmployees.php?year=" + year + "&count=5";		
			strURL = escape(strURL);
			var chartObj = getChartFromId("TopEmployees");
			chartObj.setDataURL(strURL);
		}else{
			alert("Please wait for the charts to load");
			return;
		}
	}
	/**
	 * updateProductChart method is called when the user selects a particular
	 * category on the category chart. Here, we send a request to get product wise
	 * XML data for the chart.
	 *	@param	intYear		Year for which the user is viewing data
	 *	@param	intMonth	Month for which drill down is required.
	 *	@param	intCatId	Category Id for which we need product wise data.
	*/
	function updateProductChart(intYear, intMonth, intCatId){	
		//Now, update the Sales By Products chart.
		var strURL = "Data_SalesByProd.php?year=" + intYear + "&month=" + intMonth + "&catId=" + intCatId;
		strURL = escape(strURL);
		var chartObj = getChartFromId("SalesByProd");
		chartObj.setDataURL(strURL);
	}
	/**
	 * getTimeForURL method returns the current time 
	 * in a URL friendly format, so that it can be appended to
	 * dataURL for effective non-caching.
	*/
	function getTimeForURL(){
		var dt = new Date();
		var strOutput = "";
		strOutput = dt.getHours() + "_" + dt.getMinutes() + "_" + dt.getSeconds() + "_" + dt.getMilliseconds();
		return strOutput;
	}
	/**
	 * openNewWindow method helps open a new JavaScript pop-up window.
	 * It also adds the year to the end of URL
	*/	
	function openNewWindow(theURL,winName,features) {
		 window.open(theURL + "?year=" + getSelectedYear(),winName,features);
	}
	/**
	 * getSelectedYear method returns the selected year
	*/
	function getSelectedYear(){
		var selYear;
		for (i=0; i<document.frmYr.year.length; i++){			
			if(document.frmYr.year[i].checked){				 
				selYear = document.frmYr.year[i].value;
			}
		}
		return selYear;
	}
	</script>
	<link REL="stylesheet" HREF="Style.css" />
</head>
<body topmargin="0" leftmargin="0" bottomMargin="0" rightMargin="0" bgColor="#EEEEEE">

<?php
    //Request the year for which we've to show data.
	//If year has not been provided, we default to 1996
	if (!isset($_POST['year']) || $_POST['year']=="")
        $intYear=1996;
    else
		$intYear = $_POST['year'];
	
	//Also get status for animation
	//If not defined, set a default
    if (!isset($_POST['animate']) || $_POST['animate']=="")
		$animateCharts = "1";
    else
		$animateCharts = $_POST['animate'];

    //Update Global Application Settings - Session for Demo in this app
	$_SESSION['animation'] = $animateCharts;

	//Render page headers
	echo render_pageHeader();

	//Render year selection form
	echo render_yearSelectionFrm($intYear);
	
	//Render the main table open
	echo render_pageTableOpen();
?>

<a name='start' />
<table width="875" align="center" cellspacing="0" cellpadding="0">
	<tr>
	<td align="left" width="500">
	<?php	
	# Create Object of FusionCharts class
	$FC = new FusionCharts("MSColumn3DLineDY",450,325,"SalesByYear");
	
	# set SWF Path
	$FC->setSWFPath("FusionCharts/");
	# Define Charts Parameter
	$strParam  = "caption=Yearly Sales Comparison;XAxisName=Year;palette=" . getPalette() . "; animation=" . getAnimationState() . ";subcaption=(Click on a column to drill-down to monthly sales in the chart below);formatNumberScale=0;numberPrefix=$;showValues=0;seriesNameInToolTip=0";
	# Set Chart Parameter
    $FC->setChartParams($strParam);
	# Get Sales by year XML	
	getSalesByYear($FC);

	# Add some styles to increase caption font size
	$FC->defineStyle("CaptionFont","font","color=" . getCaptionFontColor() . ";size=15");
	$FC->defineStyle("SubCaptionFont","font","bold=0");
	
	# Apply style to Chart’s CAPTION and SUBCAPTION
    $FC->applyStyle("caption","CaptionFont");
    $FC->applyStyle("SubCaption","SubCaptionFont");

	# Set Register With JS true
	$FC->setInitParam("registerwithjs",true);
	# Render objects to XML, Create Chart Output
 	$FC->renderChart();

	?>
	</td>
	<td width="3">
	</td>
	<td valign="top" style="Border-left:#EEEEEE 1px solid;">		
		<table width="90%" align="right">
			<tr>
				<td><?php
				# Create Object of FusionCharts class
				$FC1 = new FusionCharts("Pie3D",400,225,"TopEmployees");
				# set SWF Path
				$FC1->setSWFPath("FusionCharts/");
				
				// Define Charts Parameter
				$strParam  = "caption=Top 5 Employees for " . $intYear . ";palette=" . getPalette() . ";animation=" . getAnimationState() . ";subCaption=(Click to slice out or right click to choose rotation mode);YAxisName=Sales Achieved;YAxisName=Quantity;showValues=0;numberPrefix=$;formatNumberScale=0;showPercentInToolTip=0>";
				# Set Chart Parameter
				$FC1->setChartParams($strParam);
				
				# Get Sale per employee XML
				getSalePerEmpXML($intYear,5,false,false,false,$FC1);
				
				# Add some styles to increase caption font size
				$FC1->defineStyle("CaptionFont","font","color=" . getCaptionFontColor() . ";size=15");
				$FC1->defineStyle("SubCaptionFont","font","bold=0");
	
				# apply style to Chart’s CAPTION and SUBCAPTION
    			$FC1->applyStyle("caption","CaptionFont");
    			$FC1->applyStyle("SubCaption","SubCaptionFont");

                # Set Register With JS true
				$FC1->setInitParam("registerwithjs",true);
				# Render objects to XML, Create Chart Output
 				$FC1->renderChart();
	
				?></td>
		  </tr>
			<tr>
				<td valign="top">
				<table width="100%" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td background="Images/orangeTab.gif" colspan="3" align="left" height="26" width="405">
					<span class="textBoldDark">
						&nbsp;&nbsp; More Charts...					</span>					</td>
					<td>					</td>
				</tr>
				<tr>
				<td height="5">				</td>
				</tr>
				<tr height="27">
				<td width="127" align="center" valign="bottom" height="27">
					<a href="#start"><img src="Images/btnTopIndicators.jpg" border="0" onClick="openNewWindow('Chart_TopIndicators.php','chart_topI','status=yes,scrollbars=yes,resizable=yes,width=1000,height=700');" alt="My Top Selected Indicators in Charts" WIDTH="126" HEIGHT="27"></a>				</td>
				<td width="131" align="center" valign="bottom" height="27">
					<a href="#start"><img src="Images/btnEmployee.jpg" border="0" onClick="openNewWindow('Chart_SalesByEmployee.php','chart_employee','status=yes,scrollbars=yes,resizable=yes,width=1000,height=700');" alt="Sales By Employee" WIDTH="126" HEIGHT="27"></a>				</td>
				<td align="left" valign="bottom" height="27">
					<a href="#start"><img src="Images/btnInventory.jpg" border="0" onClick="openNewWindow('Chart_InventoryByCat.php','chart_inventory','status=yes,scrollbars=yes,resizable=yes,width=1000,height=700');" alt="Inventory By Categories" WIDTH="126" HEIGHT="27"></a>				</td>
				<td>				</td>
				</tr>
				
				<tr height="27">
				<td width="127" align="center" valign="top" height="27">
					<a href="#start"><img src="Images/btnSalesByCountry.jpg" border="0" onClick="openNewWindow('Chart_SalesByCountry.php','chart_country','status=yes,scrollbars=yes,resizable=yes,width=1000,height=700');" alt="Sales By Country" height="27"></a>				</td>
				<td width="131" align="center" valign="top" height="27">
					<a href="#start"><img src="Images/btnSalesByCat.jpg" border="0" onClick="openNewWindow('Chart_YearlySalesByCat.php','chart_cat_cum','status=yes,scrollbars=yes,resizable=yes,width=1000,height=700');" alt="Sales By Categories (Cumulative)" height="27"></a>				</td>
				<td align="left" valign="top" height="27">
					<a href="#start"><img src="Images/btnShipping.jpg" border="0" onClick="openNewWindow('Chart_ShippingDelay.php','chart_shipping','status=yes,scrollbars=yes,resizable=yes,width=1000,height=700');" alt="Average delay in Shipping" height="27"></a>				</td>
				<td>				</td>
				</tr>				
				</table>				</td>
			</tr>
		</table>
	</td>
	</tr>
</table>

<!-- Some blank space between two charts -->		
<table width="875" height="10">
	<tr>
		<td>
		</td>
	</tr>
</table>
		
<table width="875" align="center" cellpadding="0" cellspacing="0" border="0" style="Border-top:#EEEEEE 1px solid;">
	<tr>
	<td align="left">
	<?php	
	//For the "Sales by category" chart, we request data from Data_SalesByCategory.php.
	//So, create the dataURL for it and pass the year to it. We create the dataURL in noCache format.
	$strCatChartDataURL = encodeDataURL("Data_SalesByCategory.php?year=" . $intYear,true);

	echo renderChart("FusionCharts/MSColumn3D.swf", $strCatChartDataURL,"","SalesByCat", 875, 350, false, true);
	?>
	</td>
	</tr>
</table>

<!-- Some blank space between two charts -->		
<table width="875" height="10">
	<tr>
		<td>
		</td>
	</tr>
</table>

<table width="875" align="center" cellpadding="0" cellspacing="0" border="0" style="Border-top:#EEEEEE 1px solid;">
	<tr>
	<td align="left">
	<?php	
	//MSColumn3DLineDY.swf Chart with changed "No data to display" message
	//We initialize the chart with <chart></chart>
	echo renderChart("FusionCharts/MSColumn3DLineDY.swf?ChartNoDataText=Please select a product category in the above chart to see product-wise sales.", "","<chart></chart>","SalesByProd", 875, 350, false, true);
	?>
	</td>
	</tr>
</table>

<?php
//Close the main table
echo render_pageTableClose();
?>

</body>
</html>
