<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php"; 

$valorpg = $_POST['valorpg'];
$juros 	 = $_POST['juros'];
$desconto= $_POST['desconto'];
$numdoc  = $_POST['numdoc'];
$codloja = $_POST['codloja'];
$datapg  = $_POST['datapg'];

$situacao 	= $_POST['situacao'];
$vencimento1 = $_POST['vencimento1'];
$vencimento2 = $_POST['vencimento2'];
$franqueado = $_POST['franqueado'];
$cobranca 	= $_POST['cobranca'];
$ordem 		= $_POST['ordem'];
$lpp 		= $_POST['lpp'];
$pagina 	= $_POST['pagina'];

$origem = $_POST['origem'];

//trata a variavel datapg para o formato padrão data
	$dia = substr($datapg,0,2);
	$mes = substr($datapg,3,2);
	$ano = substr($datapg,6,4);
	
	$dt = ($ano."-".$mes."-".$dia);
	
	$valorpg=str_replace(".","",$valorpg);
	$valorpg=str_replace(",",".",$valorpg);
	
	$juros=str_replace(".","",$juros);
	$juros=str_replace(",",".",$juros);
	
	if (empty($juros) ) $juros='0';

	$query = "UPDATE titulos SET 
				valorpg = $valorpg, 
				datapg = '$dt', 
				juros = $juros, 
				origem_pgto='$origem' 
				WHERE numdoc = $numdoc";
	mysql_query($query,$con);

	$sql="select subdate(now(), interval 10 day) data ";
	$qr=mysql_query($sql,$con)or die ("ERRO:  Segundo SQL  ==>  $sql");
	$campos=mysql_fetch_array($qr);
	$data=substr($campos["data"],0,10);
	$codloja=substr($numdoc,4,9);
	$sql="select count(*) qtd from cs2.titulos where codloja=$codloja and vencimento <= '$data' and datapg is null";
	$qr=mysql_query($sql,$con)or die ("ERRO:  Terceiro SQL  ==>  $sql");
	$campos=mysql_fetch_array($qr);
	$xqtd=substr($campos["qtd"],0,10);

	if ( $xqtd == 0 ){
		$sqlx="Update cs2.logon set sitlog=0 where codloja=$codloja";
		mysql_query($sqlx,$con)or die ("ERRO:  Quinto SQL  ==>  $sqlx");
	}

$numdoc      = $_REQUEST['numdoc'];
$situacao    = $_REQUEST['situacao'];
$codigo1     = $_REQUEST['codigo1'];
$codigo2     = $_REQUEST['codigo2'];
$vencimento1 = $_REQUEST['vencimento1'];
$vencimento2 = $_REQUEST['vencimento2'];
$franqueado  = $_REQUEST['franqueado'];
$cobranca    = $_REQUEST['cobranca'];
$ordem       = $_REQUEST['ordem'];
$lpp         = $_REQUEST['lpp'];
$pagina      = $_REQUEST['pagina'];
$periodo     = $_REQUEST['periodo'];

mysql_close($con);
#echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=Franquias/b_baixafatura.php\";>";

if($_REQUEST['retorna'] == '1'){
header("Location: ../painel.php?pagina1=clientes/a_cons_id.php&id={$_REQUEST['codloja']}");
}else{
header("Location: ../painel.php?pagina1=Franquias/b_baixaf.php&situacao=&codigo1=$codigo1&franqueado=$franqueado&ordem=$ordem&ok=1");
}
//echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=Franquias/b_baixaf.php&situacao=&codigo1=$codigo1&franqueado=$franqueado&ordem=$ordem\";>";
?>