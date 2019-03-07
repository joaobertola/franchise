<?php
include "ocorrencias/config.php";
include "ocorrencias/javascript.php";
$conexao = @mysql_connect($db[host], $db[user], $db[senha]);

$pagina = $_GET['pagina'];
$id = $_REQUEST['id'];
$sql = "SELECT * FROM ocorr_franqueados WHERE franqueado = '$id' order by id desc LIMIT 0, 30";
$conex = @mysql_db_query($db[nome], $sql, $conexao);
$ordem = mysql_num_rows($conex);

// Faz os calculos da pagina��o
$sql2 = mysql_query("SELECT * FROM ocorr_franqueados WHERE franqueado = '$id' order by id desc");
$total = mysql_num_rows($sql2); // Esta fun��o ir� retornar o total de linhas na tabela
$paginas = ceil($total / $lpp); // Retorna o total de p�ginas
if(!isset($pagina)) { $pagina = 0; } // Especifica uma valor para variavel pagina caso a mesma n�o esteja setada
$inicio = $pagina * $lpp; // Retorna qual ser� a primeira linha a ser mostrada no MySQL
$sql2 = mysql_query("SELECT * FROM ocorr_franqueados WHERE franqueado = '$id' order by id desc LIMIT $inicio, $lpp"); // Executa a query no MySQL com o limite de linhas.

?>
<table width="650" align="center">
    <tr>
        <td class="titulo" align="center">Registro de Andamento da Franquia</td>
    </tr>
</table>
<p>
<?php
if ($total == 0) {
echo "<p align=\"center\" class=\"bodyText\">N&atilde;o h&aacute; nenhuma ocorrencia registrada at&eacute; o presente momento.</font></p>";
} else {
while($valor = mysql_fetch_array($sql2)) { ?>
<table align="center" border=0 width="650">
 <tr>
  <td>
<table align="center" border=0 width="650">
 <tr>
  <td class="titulo">
  <?php echo "registrado em $valor[data]"; ?>
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
   $url = "$paginacao[link]pagina1=ranking/mensagem_franqueados.php&pagina=$menos&id=$id";
   echo "<a href=\"$url\"><font face=\"Verdana\" size=\"1\">Anterior</font></a>"; // Vai para a p�gina anterior
}
for($i=0;$i<$paginas;$i++) { // Gera um loop com o link para as p�ginas
   $url = "$paginacao[link]pagina1=ranking/mensagem_franqueados.php&pagina=$i&id=$id";
   echo " | <a href=\"$url\"><font face=\"Verdana\" size=\"1\">$i</font></a>";
}
if($pagina < ($paginas - 1)) {
   $mais = $pagina + 1;
   $url = "$paginacao[link]pagina1=ranking/mensagem_franqueados.php&pagina=$mais&id=$id";
   echo " | <a href=\"$url\"><font face=\"Verdana\" size=\"1\">Pr�xima</font></a>";
}

/*
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
   echo " | <a href=\"$url\"><font face=\"Verdana\" size=\"1\">Pr�xima</font></a>";
}*/
?>
</center>
<font face="Arial" size="2">
<p align="center">
Temos um total de <b><?php echo $ordem ?></b> <?php if ($ordem == "1") echo "comentario registrado"; else echo "comentarios registrados"; ?>!
</p>
<form method="post" action="painel.php?pagina1=ranking/post_franqueado.php">
<p align="center">
	<input name="id" type="hidden" value="<?php echo $id; ?>" />
    <input type="submit" value="Incluir Comentario" />
</p> 
</form>
</font>