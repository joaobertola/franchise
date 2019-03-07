<?php

ini_set('default_charset', 'utf-8');

$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");

$cep = $_GET["cep"];

$cep = str_replace('-','',$cep);

$sql = "select a.endereco, b.bairro, c.cidade, c.uf from cep_brasil.tend_endereco a
        inner join cep_brasil.tend_bairro b on a.id_bairro = b.id_bairro
        inner join cep_brasil.tend_cidade c ON a.id_cidade = c.id_cidade
        where cep = '$cep'";

$sql = "SELECT 
           concat(a.Tipo_Oficial,' ', a.Nome_Oficial) AS endereco, 
           a.Bairro1_Oficial AS bairro, 
           b.CIDADE_OFICIAL AS cidade, a.UF 
        FROM cep_brasil.ceplog_012018 a
        INNER JOIN cep_brasil.cepcid_012018 b ON a.CHAVE = b.CHAVE
        WHERE a.CEP8 = '$cep'";
$qry = mysql_query($sql, $con) or die("Erro no SELECT");
while ($linhadf = mysql_fetch_array($qry)) {

    $saida = 
            $linhadf['endereco'].'::'.
            $linhadf['bairro'].':'.
            $linhadf['cidade'].'::'.
            $linhadf['uf'].';';
}
echo $saida;

?>