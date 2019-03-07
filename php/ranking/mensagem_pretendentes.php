<?php
 
include "ocorrencias/config.php";
include "ocorrencias/javascript.php";
$conexao = @mysql_connect($db[host], $db[user], $db[senha]);
mysql_set_charset('utf8', $conexao);
$pagina = $_GET['pagina'];
$sql = "SELECT * FROM ocorr_pretendentes WHERE pretendente = '$id' order by id desc LIMIT 0, 30";
$conex = @mysql_db_query($db[nome], $sql, $conexao);
$ordem = mysql_num_rows($conex);

// Faz os calculos da pagina��o
$sql2 = @mysql_query("SELECT * FROM ocorr_pretendentes WHERE pretendente = '$id' order by id desc");
$total = mysql_num_rows($sql2); // Esta fun��o ir� retornar o total de linhas na tabela
$paginas = ceil($total / $lpp); // Retorna o total de p�ginas
if (!isset($pagina)) {
    $pagina = 0;
} // Especifica uma valor para variavel pagina caso a mesma n�o esteja setada
$inicio = $pagina * $lpp; // Retorna qual ser� a primeira linha a ser mostrada no MySQL
$sql2 = @mysql_query("SELECT date_format(data,'%d/%m/%Y - %H:%i:%S') as data, msg FROM ocorr_pretendentes WHERE pretendente = '$id' order by id desc LIMIT $inicio, $lpp"); // Executa a query no MySQL com o limite de linhas.
?>
<table border="0" width="650px" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
    <tr>
        <td class="titulo" align="center" colspan="2">Registro de Andamento da Franquia</td>
    </tr>

    <tr bgcolor="#CCCCCC" align="left">
        <td width="22%"><b>Data - Hora</b></td>
        <td width="78%"><b>Descri&ccedil;&atilde;o</b></td>
    </tr>
    <?php
    if ($total == 0) {
        echo "<p align=\"center\" class=\"bodyText\">N&atilde;o h&aacute; nenhuma ocorrencia registrada at&eacute; o presente momento.</font></p>";
    } else {

        $a_cor = array("#FFFFFF", "#CCCCCC");
        $cont = 0;
        while ($valor = mysql_fetch_array($sql2)) {
            $cont++;
            ?>
            <tr bgcolor="<?= $a_cor[$cont % 2] ?>" height="25">
                <td><font style="font-size:15px"><?= $valor['data'] ?></font></td>
                <td><font style="font-size:15px"><?= $valor['msg'] ?></font></td>
            </tr>
        <?php } ?>
    </table>

    <?php
}
// Pagina��o
if ($pagina > 0) {
    $menos = $pagina - 1;
    $url = "$paginacao[link]pagina1=ranking/mensagens.php&pagina=$menos";
    echo "<a href=\"$url\"><font face=\"Verdana\" size=\"1\">Anterior</font></a>"; // Vai para a p�gina anterior
}
for ($i = 0; $i < $paginas; $i++) { // Gera um loop com o link para as p�ginas
    $url = "$paginacao[link]pagina1=ranking/mensagens.php&pagina=$i";
    echo " | <a href=\"$url\"><font face=\"Verdana\" size=\"1\">$i</font></a>";
}
if ($pagina < ($paginas - 1)) {
    $mais = $pagina + 1;
    $url = "$paginacao[link]pagina1=ranking/mensagens.php&pagina=$mais";
    echo " | <a href=\"$url\"><font face=\"Verdana\" size=\"1\">Pr�xima</font></a>";
}
?>
</center>
<font face="Arial" size="2">
<p align="center">
    Temos um total de <b><?php echo $ordem ?></b> <?php
    if ($ordem == "1")
        echo "comentario registrado";
    else
        echo "comentarios registrados";
    ?>!
</p>
<p align="center">
<form method="post" action="painel.php?pagina1=ranking/post_pretendente.php">
    <input name="id" type="hidden" value="<?php echo $id; ?>" />
    <div align="center">
        <input type="submit" value="Incluir Comentario" />
    </div>
</form>
</p> 
</font>