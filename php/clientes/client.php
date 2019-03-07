<?php

/* CONFIGURA��ES DO SQL */

define("HOSTDB", "10.2.2.3");
define("USERDB", "csinform");
define("PASSDB", "inform4416#scf");
define("BASEDB", "cep");
define("TYPEDB", "mysql");

$cep = $_GET["cep"];

$cep_uf = substr($cep, 0, 5);

echo $cep_uf;
exit;

	$conexxx = mysql_connect(HOSTDB,USERDB,PASSDB) or  die("Erro de conexao com Mysql");
	$selecionabancouser = mysql_select_db(BASEDB, $conexxx);
	$datacallx = mysql_query("SELECT UF FROM uf WHERE Cep1 <= '".substr($cep, 0, 5)."' AND Cep2 >= '".substr($cep, 0, 5)."'") or die("Erro no SELECT");
		while ($linhadf = mysql_fetch_array($datacallx)) {
		//Variaveis do usu�rio
		$datad = $linhadf['UF'];
		}
		$estadooooo = $datad;
		$datad = strtolower($datad);

	$datacallxz = mysql_query("SELECT * FROM ".$datad." WHERE CEP = '$cep'") or die("Erro no SELECT2");
	$checa_ex = mysql_num_rows($datacallxz);
	/*
	Nos casos de pesquisa em ceps especiais,
	traz endere�o completo incluindo complemento e n�mero
	*/
	//PESQUISA NAS GRANDES -- IMPLEMENTADO NA VERS�O 4.0
	if($checa_ex=="0"){
	$datacallxz = mysql_query("SELECT * FROM grandes WHERE CEP = '$cep'") or die("Erro no SELECT2");
	$checa_exx = mysql_num_rows($datacallxz);
	while ($linhadfz = mysql_fetch_array($datacallxz)) {
	$datad = strtolower($linhadfz['UFE_SG']);
	$explode = explode(",", $linhadfz['GRU_ENDERECO']);
	$dataaza = $explode[0];
	$complem = $explode[1];
	$explodee = explode(" ", $complem);
	$dataazf = $explodee[1];
	$dataazu = ltrim(str_replace($explodee[1],"",$complem));
	$bairro = $linhadfz['BAI_NU_SEQUENCIAL'];
	}
	}
	//PESQUISA NA CIDADE -- IMPLEMENTADO NA VERS�O 4.0
	if($checa_exx=="0"){
	$datacallxz = mysql_query("SELECT * FROM cidade WHERE CEP = '$cep'") or die("Erro no SELECT2");
	$checa_exxx = mysql_num_rows($datacallxz);
	while ($linhadfz = mysql_fetch_array($datacallxz)) {
	$dataazd = $linhadfz['Nome'];

	}
	}
	//FIM DA CIDADE
	//PESQUISA NAS AGENCIAS -- IMPLEMENTADO NA VERS�O 4.0
	if($checa_exxx=="0"){
	$datacallxz = mysql_query("SELECT * FROM agencias WHERE CEP = '$cep'") or die("Erro no SELECT2");
	while ($linhadfz = mysql_fetch_array($datacallxz)) {
	$datad = strtolower($linhadfz['UFE_SG']);
	$explode = explode(",", $linhadfz['UOP_ENDERECO']);
	$dataaza = $explode[0];
	$complem = $explode[1];
	$explodee = explode(" ", $complem);
	$dataazf = $explodee[1];
	$dataazu = ltrim(str_replace($explodee[1],"",$complem));
	$bairro = $linhadfz['BAI_NU_SEQUENCIAL'];
	}
	}
	//FIM DAS AGENCIAS



	//TRAZENDO O BAIRRO DAS GRANDES -- IMPLEMENTADO NA VERS�O 4.0
	$sel_bairro = mysql_query("SELECT * FROM bairro WHERE Seq = '$bairro'") or die("Erro no SELECT2");
	while ($res_bairro = mysql_fetch_array($sel_bairro)){
	$dataazc = $res_bairro['Nome'];
	$cidade = $res_bairro['Localidade'];
	//TRAZENDO A CIDADE DAS GRANDES -- IMPLEMENTADO NA VERS�O 4.0
	$sel_cidade = mysql_query("SELECT Nome FROM cidade WHERE Seq = '$cidade'") or die("Erro no SELECT2");
	while ($res_cidade = mysql_fetch_array($sel_cidade)){
	$dataazd = $res_cidade['Nome'];


	}
	}
	//FIM DAS GRANDES
		while ($linhadfz = mysql_fetch_array($datacallxz)) {

		//Variaveis do usu�rio

		$dataaza = $linhadfz['LOGRADOURO']." ".$linhadfz['Nome'];

		$dataazb = $linhadfz['BAI_INI'];

		$dataazc = $linhadfz['BAI_FIM'];

		$dataazd = $linhadfz['Localidade'];

		}



		echo urlencode($dataaza);

		echo ":";
		echo urlencode($dataazu); //-- IMPLEMENTADO NA VERS�O 4.0
		echo ":";

		echo urlencode($dataazb);
		
		echo urlencode($dataazc);

		echo ":";

		echo urlencode($dataazd);

		echo ":";

		echo urlencode($dataazf);

		echo ":";
		

		echo $estadooooo;
		
	
		

		echo ";";

?>