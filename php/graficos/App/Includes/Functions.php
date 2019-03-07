<?php
//This page contains functions which would be used by other pages
//We can store application states, messages and constants here
//Or create functions to handle data.

session_start();

//escapeXML function helps you escape special characters in XML
function escapeXML($strItem, $forDataURL) {
	if ($forDataURL) {
		//Convert ' to &apos; if dataURL
		$strItem = str_replace("'","&apos;", $strItem);        
	} else {
		//Else for dataXML 		
		//Convert % to %25 ... ' to %26apos;  ...  & to %26
		$findStr = array("%", "'", "&", "<", ">");
		$repStr  = array("%25", "%26apos;", "%26", "&lt;", "&gt;");
		$strItem = str_replace($findStr, $repStr, $strItem);        
	}
	//Common replacements
    $findStr = array("<", ">");
    $repStr  = array("&lt;", "&gt;");
    $strItem = str_replace($findStr, $repStr, $strItem);        
	//We've not considered any special characters here. 
	//You can add them as per your language and requirements.
	//Return
	return $strItem;
}

//getPalette method returns a value between 1-5 depending on which
//paletter the user wants to plot the chart with. 
//Here, we just read from Session variable and show it
//In your application, you could read this configuration from your 
//User Configuration Manager, database, or global application settings
function getPalette() {
	//Return
	return (((!isset($_SESSION['palette'])) || ($_SESSION['palette']=="")) ? "2" : $_SESSION['palette']);
}

//getAnimationState returns 0 or 1, depending on whether we've to
//animate chart. Here, we just read from Session variable and show it
//In your application, you could read this configuration from your 
//User Configuration Manager, database, or global application settings
function getAnimationState() {
	//Return	
	return (($_SESSION['animation']<>"0") ? "1" : "0");
}

//getCaptionFontColor function returns a color code for caption. Basic
//idea to use this is to demonstrate how to centralize your cosmetic 
//attributes for the chart
function getCaptionFontColor() {
	//Return a hex color code without #
	//FFC30C - Yellow Color
    return "666666";
}

// MonthName function converts a numeric integer into a month name
// Param: $intMonth - a numver between 1-12, otherwise defaults to 1
// Param: $flag -  if true, short name; if false, long name;
function MonthName($intMonth,$flag) {
    
	if($flag)
     return date("M",mktime(0,0,0,$intMonth,2,1994));
	else
	 return date("F",mktime(0,0,0,$intMonth,2,1994)); 
	
}
?>