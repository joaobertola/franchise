<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";
require "../connect/funcoes.php"; 

function grava_cons_liberada_logon($con,$codloja,$logon){

	//insere tabela de pre�os e consultas liberadas
	$sql = "select codcons,valor from cs2.valcons";
	$inserre = mysql_query($sql,$con) or die ("Erro: $sql");
	while ($registro = mysql_fetch_array($inserre)) {
		$codcons = $registro["codcons"];
		$valcons = $registro["valor"];
		$qtd = '50'; # Qtd Padrao
		if ( $codcons == 'A0208' || $codcons == 'A0301' )
			$qtd = '25'; // 05 consultas para Pesquisa Ligth e Pesquisa Restritiva
		else if ( $codcons == 'A0203' || $codcons == 'A0115' )
			$qtd = '10'; // 10 consultas para Pesquisa Cartorial e Pesquisa Empresarial
		else if ( $codcons == 'A0100' ) $qtd = '100'; // 100 consultas para Pesquisa BACEN
		if ( substr($codcons,0,1) == 'F' ) $qtd = '20'; // Features
   		$sql_liberadas_logon = "insert into cs2.cons_liberada_logon values('$codloja','$logon','$codcons','$qtd','0')";
		$result2 = mysql_query($sql_liberadas_logon, $con);
	}
}

function grava_cons_liberada($con,$codloja){

	//insere tabela de pre�os e consultas liberadas
	$sql = "select codcons,valor from cs2.valcons";
	$inserre = mysql_query($sql,$con) or die ("Erro: $sql");
	while ($registro = mysql_fetch_array($inserre)) {
		$codcons = $registro["codcons"];
		$valcons = $registro["valor"];
		$qtd = '50'; # Qtd Padrao
		if ( $codcons == 'A0208' || $codcons == 'A0301' )
			$qtd = '25'; // 05 consultas para Pesquisa Ligth e Pesquisa Restritiva
		else if ( $codcons == 'A0203' || $codcons == 'A0115' )
			$qtd = '10'; // 10 consultas para Pesquisa Cartorial e Pesquisa Empresarial
		else if ( $codcons == 'A0100' ) $qtd = '100'; // 100 consultas para Pesquisa BACEN
		if ( substr($codcons,0,1) == 'F' ) $qtd = '20'; // Features
   		$sql_liberadas_logon = "insert into cs2.cons_liberada values('$codloja','$codcons','$qtd','0')";
		$result2 = mysql_query($sql_liberadas_logon, $con);
	}
}


function substitui_acentos($value){ 
	$trocaeste = array( "(", ")","'","�","�","�","�","�","�","�","�","�","�","�","�","�","�",";","'","�"); 
	$poreste   = array( "", "","","O","C","U","U","O","O","O","O","A","A","A","A","E","I","","",""); 
	$value     = str_replace($trocaeste,$poreste,$value); 
	$value     = strtoupper($value);
	return $value; 
}

$codloja         = $_POST['codloja'];
$razao           = str_replace("'","",$_POST['razao']);
$nomef           = str_replace("'","",$_POST['nomef']);
$razaosoc        = substitui_acentos($razaosoc);
$nomefantasia    = substitui_acentos($nomefantasia);
$endereco  	     = substitui_acentos($_POST['endereco']);
$numero	  	     = substitui_acentos($_POST['numero_endereco']);
$complemento     = substitui_acentos($_POST['complemento']);
$bairro          = substitui_acentos($_POST['bairro']);
$cidade          = substitui_acentos($_POST['cidade']);
$uf              = $_POST['uf'];
$cep    		 = $_POST['cep'];
$cnpj   		 = $_POST['cnpj'];
$telefone 	     = $_POST['telefone'];
$fax 		     = $_POST['fax'];
$email 		     = $_POST['email'];
$fone_res 	     = $_POST['fone_res'];
$celular 	     = $_POST['celular'];
$whatsapp 	     = $_POST['fone_whatsapp'];
$nome_prop1      = substitui_acentos($_POST['nome_prop1']);
$cpf1 		     = $_POST['cpf1'];
$nome_prop2      = substitui_acentos($_POST['nome_prop2']);
$cpf2 		     = $_POST['cpf2'];
$ramo 		     = substitui_acentos($_POST['ramo']);
$vendedor 	     = substitui_acentos($_POST['vendedor']);
$fatura 	     = $_POST['fatura'];
$hora_cadastro   = $_POST['hr_cad'];
$obs 		     = substitui_acentos($_POST['obs']);
$franqueado      = substitui_acentos($_POST['franqueado']);
$banco_cliente   = $_POST['banco_cliente'];
$agencia_cliente = $_POST['agencia_cliente'];
$conta_cliente   = $_POST['conta_cliente'];
$cpfcnpj_doc     = $_POST['cpfcnpj_doc'];
$nome_doc        = $_POST['nome_doc'];
$tpconta         = $_POST['tpconta'];
$id_franquia_jr  = $_POST['id_franquia_jr'];
$pct_pesquisa    = str_replace(',', '.',$_POST['pct_pesquisa']);
$pct_solucoes    = str_replace(',', '.',$_POST['pct_solucoes']);
$tipo_cliente    = $_POST['tipo_cliente'];
$emitir_nfs      = $_POST['emitir_nfs'];
$agendador       = $_POST['agendador'];
$id_agendador    = $_POST['id_agendador'];
$id_consultor    = $_POST['id_consultor'];

// DADOS CONTADOR

$contador_nome     = $_POST['contador_nome'];
$contador_telefone = soNumero($_POST['contador_telefone']);
$contador_celular  = soNumero($_POST['contador_celular']);
$contador_email1   = $_POST['contador_email1'];
$contador_email2   = $_POST['contador_email2'];
$contadorSN        = $_POST['contadorSN'];

if ( $contadorSN == 'on' ) $contadorSN = 'S';
else $contadorSN = 'N';

// DADOS NOTA FISCAL 

$inscricao_estadual            = str_replace("'","",$_REQUEST['inscricao_estadual']);
$inscricao_estadual            = str_replace(" ","",$inscricao_estadual);
$cnae_fiscal                   = str_replace("'","",$_REQUEST['cnae_fiscal']);
$cnae_fiscal                   = str_replace(" ","",$cnae_fiscal);
$inscricao_municipal           = str_replace("'","",$_REQUEST['inscricao_municipal']);
$inscricao_municipal           = str_replace(" ","",$inscricao_municipal);
$inscricao_estadual_tributario = str_replace("'","",$_REQUEST['inscricao_estadual_tributario']);
$inscricao_estadual_tributario = str_replace(" ","",$inscricao_estadual_tributario);

$data_bd  = data_mysql($_POST['renegociacao_tabela']); 
$dt_cad   = data_mysql($_POST['dt_cad']);
$telefone = soNumero($telefone);
$fax      = soNumero($fax);
$celular  = soNumero($celular);
$whatsapp  = soNumero($whatsapp);
$fone_res = soNumero($fone_res);
$cnpj     = soNumero($cnpj);
$cpf1     = soNumero($cpf1);
$cpf2     = soNumero($cpf2);
$cep      = soNumero($cep);

$sql_cel_cliente = "SELECT celular, id_operadora FROM cs2.cadastro WHERE codloja = '$codloja'";
$qry_cel_cliente  = mysql_query($sql_cel_cliente,$con);
$registro_cel       = mysql_fetch_array($qry_cel_cliente);
$cel_antigo = $registro_cel['celular'];
$idOperadora = $registro_cel['id_operadora'];
//echo '<pre>';
//echo "Cel Antigo:" .$cel_antigo;
//echo '<pre>';
//echo "Cel Novo:" .$celular;
//echo '<pre>';
//echo "Operadora:" .$idOperadora;
//die;
if($cel_antigo != $celular){

	$servidor = 'http://consultaoperadora.telein.com.br/sistema/consultas_resumidas.php';
	$dadosEnv = 'chave=8d1b12d23b5362695071&numeros='.$celular;

	$ch = curl_init();
//endereço para envio do post
	curl_setopt ($ch, CURLOPT_URL, $servidor);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
// envio do parametros
	curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosEnv);
	$conteudo = curl_exec($ch);
	if (curl_errno($ch)) {
		print curl_error($ch);
	} else {
		curl_close($ch);
	}

	$arrRetorno = explode('#',$conteudo);
	$idOperadora = $arrRetorno[0];

}


if ( empty($banco_cliente) ) 
  $banco_cliente='NULL';
  
if ( empty($agencia_cliente) ) 
	$agencia_cliente = 'NULL';
else
	$agencia_cliente = str_replace("-","",$agencia_cliente);

if ( empty($conta_cliente) ) 
	$conta_cliente = 'NULL';
else
	$conta_cliente = str_replace("-","",$conta_cliente);

$conexao = $con;
include("cliente_grava_nomes.php");
if ( strlen($cnpj) > 11 ) $Tipo = '1';
	grava_nomes($cnpj, $Tipo, $razao, $data_nascimento, $numero_titulo, $endereco, $id_tipo_log, $numero_endereco, $complemento, $bairro, $cidade, $uf, $cep, $email, $nome_mae, $telefone, $celular, $fax, $fone_res, $empresa_trabalha, $cargo, $endereco_empresa, $rg, $nome_referencia, $fax, $fone_empresa);

# Verificando o tipo do cliente, antes de realizar a atualiza��o.

$sql_tp_cliente = "SELECT tipo_cliente FROM cs2.cadastro WHERE codloja = '$codloja'";
$qry_tpcliente  = mysql_query($sql_tp_cliente,$con);
$registro       = mysql_fetch_array($qry_tpcliente);
$tipo_cliente_antigo = $registro['tipo_cliente'];



# Procedendo a ATUALIZA��O DO CADASTRO

$query = "UPDATE cadastro SET 
            razaosoc                      = '$razao',
            nomefantasia                  = '$nomef',
            end 		  	              = '$endereco', 
            numero 		                  = '$numero',
            complemento                   = '$complemento',
            bairro                        = '$bairro',
            cidade                        = '$cidade',
            uf                            = '$uf',
            cep                           = '$cep',
            insc                          = '$cnpj',
            fone                          = '$telefone',
            fax                           = '$fax',
            email                         = '$email',
            fone_res                      = '$fone_res',
            celular                       = '$celular',
            id_operadora                  = '$idOperadora',
            socio1                        = '$nome_prop1',
            cpfsocio1                     = '$cpf1',
            socio2                        = '$nome_prop2',
            cpfsocio2                     = '$cpf2',
            ramo_atividade                = '$ramo',
            vendedor                      = '$vendedor',
            agendador                     = '$agendador',
            emissao_financeiro            = '$fatura',
            obs                           = '$obs',
            renegociacao_tabela           = '$data_bd',
            inscricao_estadual            = '$inscricao_estadual', 
            cnae_fiscal                   = '$cnae_fiscal', 
            inscricao_municipal           = '$inscricao_municipal',
            hora_cadastro                 = '$hora_cadastro',
            contador_nome                 = '$contador_nome',
            contador_telefone             = '$contador_telefone',
            contador_celular              = '$contador_celular',
            contador_email1               = '$contador_email1',
            contador_email2               = '$contador_email2',
			inscricao_estadual_tributario = '$inscricao_estadual_tributario',
			whatsapp                      = '$whatsapp'";

if($_SESSION['usuario'] == "franquiasnacional")
    $query .= " ,emitir_nfs           = '$emitir_nfs'";

        
if($_SESSION['ss_tipo'] == "a"){
    $query .= " , 
    id_franquia          = '$franqueado',
    id_franquia_jr       = '$id_franquia_jr',
    tx_mens              = '$pct_pesquisa',
    mensalidade_solucoes = '$pct_solucoes',
    tipo_cliente         = '$tipo_cliente',
    emitir_nfs           = '$emitir_nfs',
    dt_cad		 = '$dt_cad',
    contadorSN           = '$contadorSN',
    id_agendador         = '$id_agendador',
    id_consultor         = '$id_consultor'
    ";
}


if ( $_SESSION['id'] == 1204 ){
    $query .= " , emitir_nfs           = '$emitir_nfs'";
}

if($_SESSION['id'] == '163'){

	$vr_max_limite_crediario = $_REQUEST['vr_max_limite_crediario'];
	if ( strpos($vr_max_limite_crediario,',') > 0 ){
		$vr_max_limite_crediario = str_replace('.','',$vr_max_limite_crediario);
		$vr_max_limite_crediario = str_replace(',','.',$vr_max_limite_crediario);
	}
	$query .= " , vr_max_limite_crediario = '$vr_max_limite_crediario'";
}

$query .= " WHERE codloja ='$codloja'";

try{
    mysql_query($query,$con);
}catch (Exception $e) {
    echo 'Erro da Atualiza��o dos DADOS DO CLIENTE, Contate a WEB CONTROL EMPRESAS: ',  $e->getMessage(), "\n";
}
	
if($_SESSION['ss_tipo'] == "a")
	# Verificando se houve mudan�a no TIPO DO cliente
	$tpmudanca = $tipo_cliente_antigo.$tipo_cliente;
	if ( $tipo_cliente_antigo <> $tipo_cliente ){
		# Qual mudan�a ? De [A]dministrador para [N]ormal  ou   de  [N]ormal para [A]dministrador
		if ( $tpmudanca == 'NA'){ # [N]ormal para [A]dministrador
			# 10. Passo -> Apagando os registros da tabela CONS_LIBERADA, pois o mesmo ser� contralado pela CONS_LIBERADA_LOGON
			$sql_delete = "DELETE FROM cs2.cons_liberada WHERE codloja = '$codloja'";
			//mysql_query($sql_delete,$con);
			# 2o. Passo - Selecionando todos os LOGONS (CODIGO) Para que possa ser Inserido na tabela CONS_LIBERADA_LOGON as libera��es de CONSULTAS.
			$sql_logon = "SELECT logon FROM cs2.logon WHERE codloja = '$codloja'";
			$qry_logon = mysql_query($sql_logon,$con);
			while ( $reg_logon = mysql_fetch_array($qry_logon) ){
				$logon = $reg_logon['logon'];
				grava_cons_liberada_logon($con,$codloja,$logon);
			}
		}else if ( $tpmudanca == 'AN'){
			# mudar o CONSUMO para a tabela CONS_LIBERADA
			$sql_delete = "DELETE FROM cs2.cons_liberada_logon WHERE codloja = '$codloja'";
			mysql_query($sql_delete,$con);
			grava_cons_liberada($con,$codloja);
		}
	}

// registrando log
$teste = str_replace(chr(39),'',$query);
$sql   = "insert into cs2.sql_cadastro(comando_sql,datahora) values('$teste',now())";
mysql_query($sql, $con);

// Fechando a conex�o
mysql_close($con);

// Verificando se o cliente tem site (VIRTUALFLEX) cadastrado
require "../connect/conexao_conecta_virtual.php"; 
$sql_site    = "SELECT count(*) qtd from dbsites.tbl_framecliente WHERE fra_codloja = '$codloja'";
$qry_site    = mysql_query($sql_site, $con_virtual);
$qtd_virtual = mysql_result($qry_site,0,'qtd');

if ( $qtd_virtual > 0 ){
	$end_virtual   = "http://maps.google.com.br/maps?q=";
	$end_virtual2  = "$endereco $numero $cidade $uf $cep";
	$end_virtual2  = str_replace(' ','+',$end_virtual2);
	$end_virtual  .= $end_virtual2;
	
	$sql_atualiza_endereco = "UPDATE dbsitesv2.tbl_adicionais
								SET adi_maplink = '$end_virtual'
								WHERE adi_codloja = $codloja";
	$qry_atualiza_endereco = mysql_query($sql_atualiza_endereco,$con_virtual) or die("Erro Atualizar V.F: $sql_atualiza_endereco");
 }

header("Location: ../painel.php?pagina1=clientes/most_cliente.php&codloja=$codloja "); 	
?>