<?php
/* CONFIGURA��ES DO SQL */

define("HOSTDB", "10.2.2.3");
define("USERDB", "csinform");
define("PASSDB", "inform4416#scf");
define("BASEDB", "cep");
define("TYPEDB", "mysql");

$cep = $_REQUEST["cep"];

$cep = str_replace('-','',$cep);


$conexxx = mysql_connect(HOSTDB,USERDB,PASSDB) or  die("Erro de conexao com Mysql");
$selecionabancouser = mysql_select_db(BASEDB, $conexxx);
$sql_cep = "
SELECT d.id, UPPER(substr(a.endereco,1,locate(' ',a.endereco)-1 ) ) tplog,
       UPPER( substr(a.endereco,locate(' ',a.endereco) + 1, length(a.endereco) ) ) log,
       UPPER(b.bairro) AS bairro, UPPER(c.cidade) AS cidade, UPPER(c.uf) AS uf
 FROM cep_brasil.tend_endereco a
 INNER JOIN cep_brasil.tend_bairro b on a.id_bairro = b.id_bairro
 INNER JOIN cep_brasil.tend_cidade c on a.id_cidade = c.id_cidade
 INNER JOIN apoio.Tipo_Log d on substr(a.endereco,1,locate(' ',a.endereco) ) = d.descricao
 WHERE a.cep = '$cep'
";

$datacallx = mysql_query($sql_cep) or die("Erro no SELECT");
while ($linhadf = mysql_fetch_array($datacallx)) {

	$id_tplog = $linhadf['id'];
	$tplog = $linhadf['tplog'];
	$log = $linhadf['log'];
	$bairro = $linhadf['bairro'];
	$cidade = $linhadf['cidade'];
	$uf = $linhadf['uf'];
	
} 

echo urlencode($id_tplog);
echo ":";
echo urlencode($tplog);
echo ":";
echo urlencode($log);
echo ":";
echo urlencode($bairro);
echo ":";
echo urlencode($cidade);
echo ":";
echo urlencode($uf);
echo ";";
?>