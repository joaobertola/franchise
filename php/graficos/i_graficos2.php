<?php
require "connect/sessao.php";

echo $opcao =  $_REQUEST['opcao'];
echo $id =  $_SESSION['id'];

exit;

if ($opcao == 1){	
	header("Location: ../painel.php?pagina1=graficos/App/Grafico_Franquia_01.php&idfranquia=$id");
	
//	require("/franquias/php/graficos/App/Grafico_Franquia_01.php?idfranquia=$id");
	//echo "<a href=\"$url\" class=\"bodyText\">Gr&aacute;fico de Pesquisas</a>";
}
?>