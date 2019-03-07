<?php
  include("../../fckeditor.php");
  include("../../../../connect/conexao_conecta.php") ;
  session_start();

  function unhtmlentities ($string){
    $trans_tbl = get_html_translation_table (HTML_ENTITIES);
    $trans_tbl = array_flip ($trans_tbl);
    return strtr ($string, $trans_tbl);
  }    
    $string = 'Texto padrao';
	$texto = unhtmlentities ($string);
	

	//seleciona a mensagem para enviar o e-mail
	$sql_msg = "SELECT texto_email FROM cs2.pretendentes_status WHERE id = '{$_REQUEST['id_status']}'";
	$qry_msg = mysql_query($sql_msg, $con);
	$texto_email = mysql_result($qry_msg,0,'texto_email');	
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="http://code.jquery.com/jquery-1.9.0.js"></script>
    <script src="https://code.jquery.com/jquery-migrate-1.4.1.js"></script>
    <title>Web Control Empresas - Painel Administrativo</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
		<meta name="robots" content="noindex, nofollow">
		<link href="../sample.css" rel="stylesheet" type="text/css" />
		<link href="../../../css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
  function FCKeditor_OnComplete(editorInstance){
  	var oCombo = document.getElementById('cmbSkins');
  
  	// Get the active skin.
  	var sSkin = editorInstance.Config['SkinPath'];
  	sSkin = sSkin.match( /[^\/]+(?=\/$)/g );
  
  	oCombo.value = sSkin ;
  	oCombo.style.visibility = '' ;
  }
  
  function ChangeSkin(skinName){
  	window.location.href = window.location.pathname + "?Skin=" + skinName ;
  }
  
  function confirma(){
 	frm = document.form1;
    frm.action = 'pretendente_altera_texto_efetiva.php';
	frm.submit();
  } 
</script>
	</head>
	<body>	
<form action="#" method="post" name="form1">
<input type="hidden" name="id_status" value="<?=$_REQUEST['id_status']?>">
<?php
    $sBasePath = $_SERVER['PHP_SELF'] ;
    $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
    
    $oFCKeditor = new FCKeditor('FCKeditor1') ;
    $oFCKeditor->BasePath = $sBasePath ;
    
    if ( isset($_GET['Skin']) )
    	$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/' . htmlspecialchars($_GET['Skin']) . '/' ;
    
    $oFCKeditor-> Value = $texto_email ;
    $oFCKeditor-> Height = "540";
    $oFCKeditor-> Width = "100%";
    $oFCKeditor->Create() ;      
  ?>
   
<table border="0" align="center" width="790" cellspadding="0" cellspacing="0">  
 <tr><td align="center"><input type="button" value="Confirma" class="botao" onClick="confirma()" style="cursor:pointer"></td></tr>
</table> 
</form>
				
	</body>
</html>

