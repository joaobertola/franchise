<?php
  require_once("../../fckeditor.php") ;
  require_once("../../../conexao.php");
	$cod_menu = $_REQUEST['cod_menu'];
	$codigo   = $_REQUEST['codigo'];
	
	$sql = mysql_query("SELECT COUNT(*)AS total FROM evento WHERE status IN(1,2)");
  $total    = @mysql_result($sql,0,'total');

	$sql_dados = mysql_query("SELECT * FROM cronograma_anual");
	$string    = mysql_result($sql_dados,0,'conteudo');
	$codigo    = mysql_result($sql_dados,0,'codigo');
	
  function unhtmlentities ($string)
  {
    $trans_tbl = get_html_translation_table (HTML_ENTITIES);
    $trans_tbl = array_flip ($trans_tbl);
    return strtr ($string, $trans_tbl);
  }    
    $texto = unhtmlentities ($string);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title>&Aacute;rea Interna LPJJ</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="robots" content="noindex, nofollow">
		<link href="../sample.css" rel="stylesheet" type="text/css" />
		<link href="../../../css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
  function FCKeditor_OnComplete( editorInstance )
  {
  	var oCombo = document.getElementById( 'cmbSkins' ) ;
  
  	// Get the active skin.
  	var sSkin = editorInstance.Config['SkinPath'] ;
  	sSkin = sSkin.match( /[^\/]+(?=\/$)/g ) ;
  
  	oCombo.value = sSkin ;
  	oCombo.style.visibility = '' ;
  }
  
  function ChangeSkin( skinName )
  {
  	window.location.href = window.location.pathname + "?Skin=" + skinName ;
  }
</script>
	</head>
	<?php require_once("menu_2.php");?>
	<body>	
		<form action="criar.cronograma.anual.efetiva.php" method="post">
		<input type="hidden" name="codigo" value="<?=$codigo?>">
  <div align="center">
  <?php
    $sBasePath = $_SERVER['PHP_SELF'] ;
    $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
    
    $oFCKeditor = new FCKeditor('FCKeditor1') ;
    $oFCKeditor->BasePath = $sBasePath ;
    
    if ( isset($_GET['Skin']) )
    	$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/' . htmlspecialchars($_GET['Skin']) . '/' ;
    
    $oFCKeditor-> Value = $texto ;
    $oFCKeditor-> Height = "500";
    $oFCKeditor-> Width = "100%";
    $oFCKeditor->Create() ;      
  ?>
	</div>
 <table border="0" align="center" width="790" cellspadding="0" cellspacing="0">  
  <tr><td align="left"><input type="submit" value="Confirma" class="botao"></td></tr>
 </table> 

		</form>
				
	</body>
</html>
