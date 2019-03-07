<?php

require "connect/funcoes.php";
// require "connect/conexao_conecta.php";

//echo "<pre>";
// print_r( $_REQUEST );exit;
//print_r( $_FILES );

$banco         = $_REQUEST['banco'];
$nome_banco    = $_REQUEST['nome_banco'];
$agencia       = $_REQUEST['agencia'];
$tipo_conta    = $_REQUEST['tipo_conta'];
$numero_conta  = $_REQUEST['numero_conta'];
$dv_conta      = $_REQUEST['dv_conta'];
$doc_conta     = $_REQUEST['doc_conta'];
$nome_conta    = $_REQUEST['nome_conta'];
$data_deposito = $_REQUEST['data_deposito'];
$data_deposito = data_mysql($data_deposito);

$id_pedido     = $_REQUEST['id_pedido'];
$id_franquia   = $_REQUEST['id_franquia'];

$dt_cad = date("Y-m-d");
$hr_cad = date("H:i:s");

$sql = "UPDATE cs2.solicitacao_valores
			SET
				banco = '$banco',
				nomebanco = '$nome_banco',
				agencia = '$agencia',
				tpconta = '$tipo_conta',
				conta = '$numero_conta',
				dv = '$dv_conta',
				doc = '$doc_conta',
				nome = '$nome_conta',
				dt_cad = '$dt_cad',
				hr_cad = '$hr_cad'
				
		WHERE id = '$id_pedido'
		";
 
$qry = mysql_query($sql, $con) or die("Erro:  $sql");

# APAGA TODOS OS ITENS E CADASTRA NOVAMENTE

$sql = "DELETE FROM cs2.solicitacao_valores_item 
		WHERE id_sol = '$id_pedido'";
$qry = mysql_query($sql, $con) or die("Erro:  $sql");

// ITENS

$data         = $_REQUEST['data'];
$descricao    = $_REQUEST['descricao'];
$valor        = $_REQUEST['valor'];
$valor        = str_replace('.','',$valor);
$valor        = str_replace(',','.',$valor);


// echo $descricao;exit;
// "SELECT date_format(data,'%d/%m/%Y') as data ,descricao,valor FROM cs2.solicitacao_valores_item WHERE id_sol = $id_pedido";

$qtd =  sizeof($data);
if ( $qtd > 0 ){
	$seq = $qtd - 1;
	for ( $i = 0 ; $i <= $seq ; $i++ ){
		$datai       = $data[$i];
		$datai       = data_mysql($datai);
		$descricaoi  = strtoupper(trim($descricao[$i]));
		$valori      = $valor[$i];
		
		if ( $valori > 0 ){
			$sql_tem = "SELECT count(*) as qtd FROM cs2.solicitacao_valores_item
					WHERE id_sol = $id_pedido AND data = '$datai' AND descricao = '$descricaoi' AND valor = '$valori'";
			$qry_tem = mysql_query($sql_tem, $con) or die("Erro:  $sql_tem");
			$qtd_t = mysql_result($qry_tem,0,'qtd');
		
			if ( $qtd_t == 0 ){
						
				$sql = "INSERT INTO cs2.solicitacao_valores_item
							( id_sol , data , descricao, valor, dt_cad, hr_cad )
						VALUES( '$id_pedido' , '$datai' , '$descricaoi' , '$valori' , NOW(), NOW() )";
				$qry = mysql_query($sql, $con) or die("Erro:  $sql");
			}
		}
	}
}

// ARQUIVOS
$foto1 = $_FILES['documento']['name'];
$qtd =  sizeof($foto1);
if ( $qtd > 0 ){
	$seq = $qtd - 1;
	for ( $i = 0 ; $i <= $seq ; $i++ ){
		$max_file      = "5"; // Tamanho máximo do arquivo (em MB)
		$userfile_name = $_FILES['documento']['name'][$i];
		$userfile_tmp  = $_FILES['documento']['tmp_name'][$i];
		$userfile_size = $_FILES['documento']['size'][$i];
		$userfile_type = $_FILES['documento']['type'][$i];
		$filename = basename($_FILES['documento']['name'][$i]);
		$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
		if((!empty($_FILES["documento"])) && ($_FILES['documento']['error'][$i] == 0)) {
			foreach ($allowed_image_types as $mime_type => $ext) {
				if($file_ext==$ext && $userfile_type==$mime_type){
					$error = "";
					break;
				}else{
					$error = "Só <strong>".$image_ext."</strong>Imagens são aceitas para upload<br />";
				}
			}
			if ($userfile_size > ($max_file*1048576)) {
				$error .= "Os arquivos precisam estar dentro do limite de ".$max_file."MB";
			}
			if ( $error ){
				echo "$error";
				exit;
			}
			
			$userfile_name = strtoupper($userfile_name);
			$userfile_name = str_replace(' ','_',$userfile_name);
			$nome_aterado = seo2($userfile_name);
			
			// Gera um nome único para a imagem 
			$nome_imagem = $id_franquia.'_'.$id.'_'.$nome_aterado;
			// Caminho de onde ficará a imagem 
			$caminho_imagem = "area_restrita/upload/arquivo_solicitacao/" . $nome_imagem;   
			// Faz o upload da imagem para seu respectivo caminho 
			move_uploaded_file($userfile_tmp, $caminho_imagem);
			
			// INSERE A FOTO
			$sql = "INSERT INTO cs2.solicitacao_valores_arq
					( id_sol , nome_arquivo )
					VALUES
					( '$id_pedido' , '$nome_imagem' )";
			$qry = mysql_query($sql, $con) or die("Erro:  $sql");
		}
	}
}

// Enviando por Email
echo "<script>alert('Registro gravado com sucesso.')</script>";

echo "<meta http-equiv='refresh' content=\"0; url= painel.php?pagina1=area_restrita/a_solicitacao_valores3.php&id_pedido=$id_pedido\";>";


?>