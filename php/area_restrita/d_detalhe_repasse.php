<?php
require "../connect/conexao_conecta.php"; 

$id = $_GET['protocolo'];

if (!empty($id)){
	$sql = "select date_format(data,'%d/%m/%Y') as data, comprovante, valor from cs2.contacorrente_recebafacil where id='$id'";
	$Result=mysql_query($sql,$con) or die ("Erro ao selecionar os dados da tabela");
	$resultV = mysql_fetch_array($Result);
	
	$data_repasse = $resultV['data'];
	$descricao = $resultV['comprovante'];
	$valorpg = $resultV['valor'];
?>
<link href="https://www.webcontrolempresas.com.br/inform/css/estilos.css" rel="stylesheet" type="text/css" />
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="650" border="0" cellspacing="0" cellpadding="3" align="center">
  <tr align="center">
    <td colspan="2" bgcolor="#eeeeee" class="formulario">Repasse de Pagamento</td>
  </tr>
  <tr>
    <td class="tabela" width="30%">Data do Repasse:</td>
    <td class="html"><?php echo $data_repasse; ?></td>
  </tr>
  <tr>
    <td class="tabela">Valor Pago:</td>
    <td class="html"><?php echo $valorpg; ?></td>
  </tr>
  <tr>
    <td class="tabela">Descri&ccedil;&atilde;o do Repasse:</td>
    <td class="html"><?php echo nl2br($descricao); ?></td>
  </tr>
</table>
<?php
} else {
	echo "<h1>Selecione ao menos um protocolo</h1>";
}

mysql_close($con);
echo "<p align=center><input type=button name=fechar id=fechar value=\"Fechar Janela\" onClick=\"window.close()\" class=botao_laranja ></p>";
?>