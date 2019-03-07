<link rel="stylesheet" href="../../web_control/css/popup.css" type="text/css">
<style type="text/css" media="print">
.noprint {
	display:none;
}
</style>
<?php
	include('../../../inform/funcoes.php');
	global $conexao;
	conecex();

	$dia = date('%d');
	$_dia = date('d');
	global $conexao;
	
	$sql_banner = "SELECT nome_arquivo FROM cs2.banner WHERE tempo LIKE('%,$dia,%')";
	$qr_banner = mysql_query($sql_banner,$conexao) or die ("Erro:  $sql_banner");
	$nome_arquivo = mysql_result($qr_banner,0,'nome_arquivo');	
	if( ($_dia == 06) or ($_dia == 12) or ($_dia == 29) or ($_dia == 03) or ($_dia == 17) or ($_dia == 31) or 
	    ($_dia == 24) or ($_dia == 01) or ($_dia == 15) or ($_dia == 09) or ($_dia == 19) or ($_dia == 20) or 
		($_dia == 26) or ($_dia == 02) or ($_dia == 16) or ($_dia == 22) or ($_dia == 08) or ($_dia == 18) or 
		($_dia == 07) or ($_dia == 21) or ($_dia == 28) or ($_dia == 13) or ($_dia == 04) or ($_dia == 14) or
		($_dia == 27) or ($_dia == 05)){		
		$largura = '520';
		$altura  = '800';	
	}
	
	if( ($_dia == 10) or ($_dia == 23) or ($_dia == 30) ){
		$largura = '650';
		$altura  = '450';	
	} 
	
	if( ($_dia == 11) or ($_dia == 25) ){
		$largura = '650';
		$altura  = '525';	
	}
?>

<script src="../../../Scripts/swfobject_modified.js" type="text/javascript"></script>

<table border="0" width="450px" align="center" cellpadding="0" cellspacing="2" style="border: 1px solid #F5F5F5; background-color:#FFFFFF">

<tr valign="top">
  <td valign="top"> 
  <object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<?=$largura?>" height="<?=$altura?>">
    <param name="movie" value="../../../inform/images/<?=$nome_arquivo?>" />
    <param name="quality" value="high" />
    <param name="wmode" value="opaque" />
    <param name="swfversion" value="8.0.35.0" />
   
    <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
    <param name="expressinstall" value="../../../Scripts/expressInstall.swf" />
    <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
    <!--[if !IE]>-->
    <object type="application/x-shockwave-flash" data="../../../inform/images/<?=$nome_arquivo?>" width="<?=$largura?>" height="<?=$altura?>">
      <!--<![endif]-->
      <param name="quality" value="high" />
      <param name="wmode" value="opaque" />
      <param name="swfversion" value="8.0.35.0" />
      <param name="expressinstall" value="../../../Scripts/expressInstall.swf" />
      <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
      <div>
        <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
        <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
      </div>
      <!--[if !IE]>-->
    </object>
    <!--<![endif]-->
  </object>

  </td>
  </tr>	     
</table>

<script type="text/javascript">
<!--
swfobject.registerObject("FlashID");
swfobject.registerObject("FlashID");
//-->
</script>
