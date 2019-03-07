<?php
include "ocorrencias/config.php";
include "ocorrencias/javascript.php";
$conexao = @mysql_pconnect($db[host], $db[user], $db[senha]);

$pagina = $_GET['pagina'];
$sql = "SELECT * FROM mural WHERE id order by id desc LIMIT 0, 30";
$conex = mysql_query($sql, $conexao);
$ordem = mysql_num_rows($conex);

// Faz os calculos da pagina��o
$sql2 = mysql_query("SELECT * FROM mural WHERE id order by id desc");
$total = mysql_num_rows($sql2); // Esta fun��o ir� retornar o total de linhas na tabela
$paginas = ceil($total / $lpp); // Retorna o total de p�ginas
if(!isset($pagina)) { $pagina = 0; } // Especifica uma valor para variavel pagina caso a mesma n�o esteja setada
$inicio = $pagina * $lpp; // Retorna qual ser� a primeira linha a ser mostrada no MySQL
$sql2 = mysql_query("SELECT * FROM mural WHERE id order by id desc LIMIT $inicio, $lpp"); // Executa a query no MySQL com o limite de linhas.

?>
<table width="840" align="center">
    <tr>
        <td class="titulo" align="center">.:: Últimas Noticias ::.</td>
    </tr>
</table>
<p>
<?php
if ($total == 0) {
echo "<p align=\"center\"><font size=\"1\" face=\"Verdana\">N&atilde;o h&aacute; nenhum recado registrado at&eacute; o presente momento.</font></p>";
} else {
while($valor = mysql_fetch_array($sql2)) { ?>
<table align="center" border=0 width="840">
 <tr>
  <td>
<table align="center" border=0 width="100%">
 <tr>
  <td class="titulo">
  <?php echo "$valor[de] <a href=\"mailto:$valor[email]\"> ($valor[msn])</a> registrou para $valor[para]."; ?>
  </td>
 </tr>
 <tr>
  <td class="campoesquerda" style="padding:10px;">
   <?= nl2br( "<font face=\"Arial\" size=\"2\"><b>Descri&ccedil;&atilde;o: </b></a> $valor[msg]</font>"); ?>
  </td>
 </tr>
 <tr>
  <td class="titulo" height="7"></td>
 </tr>
</table>
  </td>
 </tr>
</table>
</p>
<?php
;
}
?>
<center>
<?php
}
// Pagina��o
if($pagina > 0) {
   $menos = $pagina - 1;
   $url = "$paginacao[link]pagina1=ranking/mensagens.php&pagina=$menos";
   echo "<a href=\"$url\"><font face=\"Verdana\" size=\"1\">Anterior</font></a>"; // Vai para a p�gina anterior
}
for($i=0;$i<$paginas;$i++) { // Gera um loop com o link para as p�ginas
   $url = "$paginacao[link]pagina1=ranking/mensagens.php&pagina=$i";
   echo " | <a href=\"$url\"><font face=\"Verdana\" size=\"1\">$i</font></a>";
}
if($pagina < ($paginas - 1)) {
   $mais = $pagina + 1;
   $url = "$paginacao[link]pagina1=ranking/mensagens.php&pagina=$mais";
   echo " | <a href=\"$url\"><font face=\"Verdana\" size=\"1\">Próxima</font></a>";
}
?>
</center>
<font face="Arial" size="2">
<p align="center">
Temos um total de <b><?php echo $ordem ?></b> <?php if ($ordem == "1") echo "recado registrado"; else echo "recados registrados"; ?>!
</p>
<p align=center>
<input type="button" value="Novo recado" class="buttons" style="cursor:pointer" onClick="document.location='?act=painel.php&pagina1=ranking/postar.php'" <?php if ($tipo <> "a") echo "disabled"; ?>/>
</p> 
<p align="center"><?php echo "<a onclick=\"abrir('$link[ranking]')\" href=\"#\"><font color=#FF0000>&Aacute;rea restrita</font></a>"; ?></p>
</font>