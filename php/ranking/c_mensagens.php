<?php
require "connect/sessao.php";

$sql = "SELECT * FROM $table_name WHERE id order by id desc LIMIT 0, 30";
$conex = mysql_db_query($db[nome], $sql, $conexao);
$ordem = mysql_num_rows($conex);

// Faz os calculos da pagina��o
$sql2 = mysql_query("SELECT * FROM $table_name WHERE id order by id desc");
$total = mysql_num_rows($sql2); // Esta fun��o ir� retornar o total de linhas na tabela
$paginas = ceil($total / $lpp); // Retorna o total de p�ginas
if(!isset($pagina)) { $pagina = 0; } // Especifica uma valor para variavel pagina caso a mesma n�o esteja setada
$inicio = $pagina * $lpp; // Retorna qual ser� a primeira linha a ser mostrada no MySQL
$sql2 = mysql_query("SELECT * FROM $table_name WHERE id order by id desc LIMIT $inicio, $lpp"); // Executa a query no MySQL com o limite de linhas.

echo "<p align=center><font face=\"verdana\"><b>$titulo</b></font></p>";
echo "<p align=center><font face=\"Verdana\" size=\"2\">�ltimas ocorr�ncias registradas</font></p>";
echo "<p align=center><input type=\"button\" value=\"Nova ocorr�ncia\" onclick=\"vai()\" /></p>"; ?>  
</center>
<p>
<?php
echo "[$total]";
if ($total == 0) {
echo "<p align=\"center\"><font size=\"1\" face=\"Verdana\">N�o h� nenhuma ocorr�ncia registrada at� o presente momento.</font></p>";
} else {

?>
<?php while($valor = mysql_fetch_array($sql2)) { ?>
<table align="center" border=0 width="80%">
 <tr>
  <td>
<table align="center" border=0 width="100%">
 <tr>
  <td bgcolor="<?php echo "$cor[topico]"; ?>">
  <?php echo "<font face=\"Arial\" size=\"2\"><a href=\"mailto:$valor[email]\"><b>$valor[de]</b> (Data:$valor[icq])</a> registrou para <b>$valor[para]</b>."; ?>
  </td>
 </tr>
 <tr>
  <td>
   <?php echo "<font face=\"Arial\" size=\"2\"><b>Descri��o: </b></a> $valor[msg]</font>"; ?>
  </td>
 </tr>
 <tr>
  <td><center><font face="Verdana" size="1">________________________________________________</font></center></td>
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
   $url = "$paginacao[link]&pagina=$menos";
   echo "<a href=\"$url\"><font face=\"Verdana\" size=\"1\">Anterior</font></a>"; // Vai para a p�gina anterior
}
for($i=0;$i<$paginas;$i++) { // Gera um loop com o link para as p�ginas
   $url = "$paginacao[link]pagina=$i";
   echo " | <a href=\"$url\"><font face=\"Verdana\" size=\"1\">$i</font></a>";
}
if($pagina < ($paginas - 1)) {
   $mais = $pagina + 1;
   $url = "$paginacao[link]pagina=$mais";
   echo " | <a href=\"$url\"><font face=\"Verdana\" size=\"1\">Pr�xima</font></a>";
}
?>
</center>
<font face="Arial" size="2">
<p align="center">
Temos um total de <b><?php echo $ordem ?></b> <?php if ($ordem == "1") echo "ocorr�ncia registrada"; else echo "ocorr�ncias registradas"; ?>!
</p>
<p align="center"><?php echo "<a onclick=\"abrir('$link[login]')\" href=\"#\"><font color=#FF0000>�rea restrita</font></a>"; ?></p>
</font>