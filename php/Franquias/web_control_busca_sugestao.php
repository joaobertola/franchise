<?php
require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") && ($tipo!="a") && ($tipo!="d")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}
$sql = "SELECT  id, razaosoc, fantasia, sitfrq FROM cs2.franquia ORDER BY id";
$qry = mysql_query($sql, $con);

?>
<form method="post" action="painel.php?pagina1=Franquias/web_control_listagem_sugestao.php" name="form">

<table border="0" align="center" width="600">

<tr><td colspan="2" align="center" class="titulo">Relatório de Sugestão WEB-CONTROL</td></tr>

  <tr>
    <td class="subtitulodireita" width="25%">Franquia</td>
    <td class="subtitulopequeno" width="75%">
      <select name="cod_franquia">
       <option value="">-- selecione --</option>
 	   <?php while ($rs = mysql_fetch_array($qry)){?>
          <option value="<?=$rs['id']?>"><?=$rs['fantasia']?></option>  
       <?php } ?>      
     </select> 
     </td>
  </tr>

<tr><td class="subtitulodireita">Lidas</td>
<td class="subtitulopequeno">&nbsp;
		Em Análise<input type="radio" name="lida" value="N" checked="checked"/>
        &nbsp;&nbsp;&nbsp;&nbsp;
        Em Desenvolvimento<input type="radio"name="lida" value="D">
        &nbsp;&nbsp;&nbsp;&nbsp;
        Já foram Desenvolvidas<input type="radio"name="lida" value="S">
</td></tr>

<tr><td class="subtitulopequeno">&nbsp;</td><td class="subtitulopequeno"><input type="submit" value="Confirma Listagem"></td></tr>
</table>

</form>