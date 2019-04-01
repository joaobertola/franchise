<?php
require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//$data_cadastro = date("Y-m-d");
//
//if ( $name == "" ){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

# Conexao BD Site Web Control Empresas

$con 	= @mysql_pconnect("10.2.2.3",'csinform','inform4416#scf')or die ("Problemas ao conectar no servidor de banco de dados".mail("lucianomancini@gmail.com","problemas na conexao com mysql"," From:lucianomancini@gmail.com"));
$bdx 	= mysql_select_db('cs2',$con) or die("Nao foi posivel selecionar o banco de dados contate o administrador erro 30");

$sql_f = "DELETE FROM cs2.cadastro_virtual WHERE codloja = '{$_REQUEST['id_cadastro']}'";
$qry_f = mysql_query($sql_f, $con) or die ("Erro : $sql_f" );

# Conexao BD Site Virtual Flex

$usermy="csinform";
$passwordmy	= "inform4416#scf";
$nomedb		= "dbsites";
$conexao	= @mysql_pconnect("10.2.2.3",$usermy,$passwordmy)or die ("Problemas ao conectar no servidor de banco de dados".mail("lucianomancini@gmail.com","problemas na conexao com mysql"," From:lucianomancini@gmail.com"));
$bd			= mysql_select_db($nomedb,$conexao) or die("Nao foi posivel selecionar o banco de dados contate o administrador erro 30");

$dominio       = $_REQUEST['dominio'];
$id_cadastro   = $_REQUEST['id_cadastro'];

if ( empty($dominio) ){
	echo "DOMINIO A SER EXCLUIDO INVALIDO - VAZIO OU NULO";
	exit;
}

if ( $id_cadastro == 1 ) {
	echo "DOMINIO NAO PODE SER EXCLUIDO";
	exit;
}

?>
<form name="form" method="post" action="#"><?php

  //FAZ INSERTE NA TABELA INFORMADO OS PARAMETROS DE EXCLUS�O **********************
  $sql_rec = "SELECT * FROM dbsites.tbl_framecliente WHERE fra_codloja = '{$_REQUEST['id_cadastro']}'";
  $qry_rec = mysql_query($sql_rec);
  $site         = mysql_result($qry_rec,0,'fra_nomesite');	
  $dominio      = mysql_result($qry_rec,0,'fra_dominio');
  $versao       = mysql_result($qry_rec,0,'fra_versao');
  $frame        = mysql_result($qry_rec,0,'fra_nomeframe');
  $ramo         = mysql_result($qry_rec,0,'fra_ramo');
  $data_criacao = mysql_result($qry_rec,0,'fra_data_hora');
  $id_franquia  = mysql_result($qry_rec,0,'fra_idfranquia');
  
  $sql_insert = "INSERT INTO dbsites.site_excluido (id_cadastro, data_criacao, site, dominio, versao, frame, ramo, data_exclusao, id_franquia, usuario) 
                 VALUES 
                 ('{$_REQUEST['id_cadastro']}', '$data_criacao', '$site', '$dominio', '$versao', '$frame', '$ramo', now(), '$id_franquia', '{$_SESSION['usuario']}')";
  $qry_insert = mysql_query($sql_insert);
  //********************************************************************************
      
$sql_a = "DELETE FROM dbsites.tbl_galeria WHERE gal_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_a = mysql_query($sql_a);

$sql_b = "DELETE FROM dbsites.tbl_fotos WHERE fot_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_b = mysql_query($sql_b);

$sql_c = "DELETE FROM dbsites.tbl_fotos_site WHERE fot_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_c = mysql_query($sql_c);

$sql_d = "DELETE FROM dbsites.tbl_framecliente WHERE fra_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_d = mysql_query($sql_d);

$sql_e = "DELETE FROM dbsites.tbl_paginas WHERE pag_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_e = mysql_query($sql_e);

$sql_f = "DELETE FROM dbsites.tbl_adicionais WHERE adi_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_f = mysql_query($sql_f);



/*VERSAO 2.0*/
$sql_g = "DELETE FROM dbsitesv2.tbl_galeria WHERE gal_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_g = mysql_query($sql_g);

$sql_h = "DELETE FROM dbsitesv2.tbl_fotos WHERE fot_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_h = mysql_query($sql_h);

$sql_i = "DELETE FROM dbsitesv2.tbl_fotos_site WHERE fot_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_i = mysql_query($sql_i);

$sql_j = "DELETE FROM dbsitesv2.tbl_paginas WHERE pag_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_j = mysql_query($sql_j);

$sql_k = "DELETE FROM dbsitesv2.tbl_adicionais WHERE adi_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_k = mysql_query($sql_k);

$sql_l = "DELETE FROM dbsitesv2.tbl_blog WHERE blo_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_l = mysql_query($sql_l);

$sql_m = "DELETE FROM dbsitesv2.tbl_blog_conteudo WHERE blc_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_m = mysql_query($sql_m);

$sql_n = "DELETE FROM dbsitesv2.tbl_links WHERE lin_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_n = mysql_query($sql_n);

$sql_o = "DELETE FROM dbsitesv2.tbl_produtos WHERE prod_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_o = mysql_query($sql_o);

$sql_p = "DELETE FROM dbsitesv2.tbl_subgaleria WHERE sub_codloja = '{$_REQUEST['id_cadastro']}'";
$qry_p = mysql_query($sql_p);

/*FIM DA NOVA  VERSAO 2.0*/

?>
<script language="javascript">
	frm = document.form;
	frm.action = 'http://www.vfx.net.br/excluir_dominio.php?site=<?=$site?>&dominio=<?=$dominio?>&id_franquia=<?=$_SESSION['id']?>&id_cadastro=<?=$_REQUEST['id_cadastro']?>';
	frm.submit();
	alert('Site [ <?=$dominio?> ] foi exclu�do com sucesso ! ');
	window.location.href="https://www.webcontrolempresas.com.br/franquias/php/painel.php?pagina1=area_restrita/virtual_flex_busca_excluir_dominio.php";
</script>

</form>
