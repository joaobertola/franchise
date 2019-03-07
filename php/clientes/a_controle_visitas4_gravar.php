<?php
error_reporting (E_ALL ^ E_NOTICE);

//echo "<pre>";
//print_r( $_REQUEST );
//print_r( $_FILES );
//exit;

if ( $_REQUEST['rel_franquia'] == '' )
	$id_franquia = $_SESSION['id'];
else
	$id_franquia = $_REQUEST['rel_franquia'];

if (  $id_franquia == 1 or $id_franquia == 163 or $id_franquia == 46 or $id_franquia == 59 )
	$id_franquia = 247;
	
$semana = $_REQUEST['wanted_week'];
$ano    = $_REQUEST['ano_esc'];
$dti    = $_REQUEST['primeiro'];
$dtf    = $_REQUEST['ultimo'];
$qtd01  = $_REQUEST['qtd01'];
$qtd02  = $_REQUEST['qtd02'];
$qtd03  = $_REQUEST['qtd03'];
$qtd04  = $_REQUEST['qtd04'];
$qtd05  = $_REQUEST['qtd05'];
$qtd06  = $_REQUEST['qtd06'];
$qtd07  = $_REQUEST['qtd07'];
$qtd08  = $_REQUEST['qtd08'];
$qtd09  = $_REQUEST['qtd09'];
$qtd10  = $_REQUEST['qtd10'];

// Entrevistados
$nome_entrevistado  = $_REQUEST['nome_entrevistado'];
$fone_fixo          = $_REQUEST['fone_fixo'];
$celular            = $_REQUEST['celular'];
$nome_email         = $_REQUEST['nome_email'];

$sql = "SELECT id FROM cs2.tarefas_gerenciais
		WHERE ano = $ano AND semana = '$semana' AND id_franquia = $id_franquia";
$qry = mysql_query($sql, $con) or die("Erro:  $sql");
$id  = mysql_result($qry,0);

if ( $id == 0 ){

	// Registro Inexistente - Incluindo um novo
	$sql = "INSERT INTO cs2.tarefas_gerenciais
				(
				id_franquia, ano, semana, datainicio, datafinal, 
				qtd_01, qtd_02, qtd_03, qtd_04, qtd_05, qtd_06, qtd_07, qtd_08, qtd_09, qtd_10
				)
			VALUES
				(
				'$id_franquia' , '$ano' , '$semana' , '$dti' , '$dtf',
				'$qtd01' , '$qtd02' , '$qtd03' , '$qtd04' , '$qtd05' , '$qtd06' , '$qtd07' , '$qtd08' , '$qtd09', '$qtd10'
				);";
	$qry = mysql_query($sql, $con) or die("Erro:  $sql");
	$id = mysql_insert_id();

}else{
	// Registro Existente - Atualizando
	$sql = "UPDATE cs2.tarefas_gerenciais
				SET 
					qtd_01 = '$qtd01',
					qtd_02 = '$qtd02',
					qtd_03 = '$qtd03',
					qtd_04 = '$qtd04',
					qtd_05 = '$qtd05',
					qtd_06 = '$qtd06',
					qtd_07 = '$qtd07',
					qtd_08 = '$qtd08',
					qtd_09 = '$qtd09',
					qtd_10 = '$qtd10'
			WHERE id = '$id'";
	$qry = mysql_query($sql, $con) or die("Erro:  $sql");
}


// Gravando Entrevistados

$nome_entrevistado  = $_REQUEST['nome_entrevistado'];
$qtd =  sizeof($nome_entrevistado);
if ( $qtd > 0 ){
	$seq = $qtd - 1;
	for ( $i = 0 ; $i <= $seq ; $i++ ){
		
		$nome  = trim($nome_entrevistado[$i]);
		$fone  = $fone_fixo[$i];
		$cel   = $celular[$i];
		$email = $nome_email[$i];
		
		if ( $nome <> '' ){
			$sql = "INSERT INTO cs2.tarefas_gerenciais_entrevistados
						( id_tarefa , nome , telefone, celular, email )
					VALUES( '$id' , '$nome' , '$fone' , '$cel' , '$email' )";
			$qry = mysql_query($sql, $con) or die("Erro:  $sql");
		}
	}
}


	// Gravando Fotos

	// Fotos Treinamento 1
	
	$foto1 = $_FILES['foto']['name'];
	$qtd =  sizeof($foto1);
	if ( $qtd > 0 ){
		$seq = $qtd - 1;
		for ( $i = 0 ; $i <= $seq ; $i++ ){
			if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $_FILES['foto']['type'][$i])){ 
				// passa
			}
			else{
				$max_file     = "5"; // Tamanho máximo do arquivo (em MB)
				$userfile_name = $_FILES['foto']['name'][$i];
				$userfile_tmp  = $_FILES['foto']['tmp_name'][$i];
				$userfile_size = $_FILES['foto']['size'][$i];
				$userfile_type = $_FILES['foto']['type'][$i];
				$filename = basename($_FILES['foto']['name'][$i]);
				$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
				if((!empty($_FILES["foto"])) && ($_FILES['foto']['error'][$i] == 0)) {
		
					foreach ($allowed_image_types as $mime_type => $ext) {
						if($file_ext==$ext && $userfile_type==$mime_type){
							$error = "";
							break;
						}else{
							$error = "Só <strong>".$image_ext."</strong>Imagens são aceitas para upload<br />";
						}
					}
					if ($userfile_size > ($max_file*1048576)) {
						$error .= "As imagens precisam estar dentro do limite de ".$max_file."MB";
					}
				}else{
					$error = "Selecione uma imagem para tratamento";
				}
				if ( $error ){
					echo "$error";
					exit;
				}
				// Gera um nome único para a imagem 
				$nome_imagem = $id_franquia.'_'.$ano.'_'.$semana.'_primeiro_treinamento_'.$i.'_.jpg';
				// Caminho de onde ficará a imagem 
				$caminho_imagem = "clientes/fotos_franquias/" . $nome_imagem;   
				// Faz o upload da imagem para seu respectivo caminho 
				unlink($caminho_imagem);
				move_uploaded_file($userfile_tmp, $caminho_imagem);
				
				$sequencia = $i + 1;
				$tipo_foto = "TREINA_1_".$sequencia;
				
				// Verifico se para este Treinamento existe foto
				$sql = "SELECT id, nome_arquivo FROM cs2.tarefas_gerenciais_fotos
						WHERE id_tarefa = '$id' AND tipo_foto = '$tipo_foto'";
				$qry = mysql_query($sql) or die("Erro:  $sql");
				$id_foto = mysql_result($qry,0,'id');
				$nome_arquivo = mysql_result($qry,0,'nome_arquivo');		
				if ( $id_foto == 0 ){
					// Nao tem foto, cadastro
					$sql = "INSERT INTO cs2.tarefas_gerenciais_fotos
							( id_tarefa , tipo_foto , nome_arquivo )
							VALUES( '$id' , '$tipo_foto' , '$nome_imagem' )";
					$qry = mysql_query($sql, $con) or die("Erro:  $sql");
				}else{
					// Já existe
					// apaga a foto anterior se for diferente o nome
					//lsif ( $nome_arquivo <> $nome_imagem ) unlink($nome_arquivo);
					$sql = "UPDATE cs2.tarefas_gerenciais_fotos
							 SET
							 	nome_arquivo = '$nome_imagem'
							WHERE 
									id_tarefa = $id 
								AND
									tipo_foto = '$tipo_foto'
							";
					$qry = mysql_query($sql, $con) or die("Erro:  $sql");
					
					// cadastra a outra
				}
			}
		}
	}

	// Fotos Treinamento 2
	
	$foto1 = $_FILES['foto2']['name'];
	$qtd =  sizeof($foto1);
	if ( $qtd > 0 ){
		$seq = $qtd - 1;
		for ( $i = 0 ; $i <= $seq ; $i++ ){
			if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $_FILES['foto2']['type'][$i])){ 
				// passa
			}
			else{
				$max_file      = "5"; // Tamanho máximo do arquivo (em MB)
				$userfile_name = $_FILES['foto2']['name'][$i];
				$userfile_tmp  = $_FILES['foto2']['tmp_name'][$i];
				$userfile_size = $_FILES['foto2']['size'][$i];
				$userfile_type = $_FILES['foto2']['type'][$i];
				$filename = basename($_FILES['foto2']['name'][$i]);
				$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
				if((!empty($_FILES["foto2"])) && ($_FILES['foto2']['error'][$i] == 0)) {
		
					foreach ($allowed_image_types as $mime_type => $ext) {
						if($file_ext==$ext && $userfile_type==$mime_type){
							$error = "";
							break;
						}else{
							$error = "Só <strong>".$image_ext."</strong>Imagens são aceitas para upload<br />";
						}
					}
					if ($userfile_size > ($max_file*1048576)) {
						$error .= "As imagens precisam estar dentro do limite de ".$max_file."MB";
					}
				}else{
					$error = "Selecione uma imagem para tratamento";
				}
				// Gera um nome único para a imagem 
				$nome_imagem = $id_franquia.'_'.$ano.'_'.$semana.'_segundo_treinamento_'.$i.'_.jpg';
				// Caminho de onde ficará a imagem 
				$caminho_imagem = "clientes/fotos_franquias/" . $nome_imagem;   
				// Faz o upload da imagem para seu respectivo caminho 
				unlink($caminho_imagem);
				move_uploaded_file($userfile_tmp, $caminho_imagem);
				
				$sequencia = $i + 1;
				$tipo_foto = "TREINA_2_".$sequencia;
				
				// Verifico se para este Treinamento existe foto
				$sql = "SELECT id, nome_arquivo FROM cs2.tarefas_gerenciais_fotos
						WHERE id_tarefa = '$id' AND tipo_foto = '$tipo_foto'";
				$qry = mysql_query($sql, $con) or die("Erro:  $sql");
				$id_foto = mysql_result($qry,0,'id');
				$nome_arquivo = mysql_result($qry,0,'nome_arquivo');		
				if ( $id_foto == 0 ){
					// Nao tem foto, cadastro
					$sql = "INSERT INTO cs2.tarefas_gerenciais_fotos
							( id_tarefa , tipo_foto , nome_arquivo )
							VALUES( '$id' , '$tipo_foto' , '$nome_imagem' )";
					$qry = mysql_query($sql, $con) or die("Erro:  $sql");
				}else{
					// Já existe
					// apaga a foto anterior se for diferente o nome
					//lsif ( $nome_arquivo <> $nome_imagem ) unlink($nome_arquivo);
					$sql = "UPDATE cs2.tarefas_gerenciais_fotos
							 SET
							 	nome_arquivo = '$nome_imagem'
							WHERE 
									id_tarefa = $id 
								AND
									tipo_foto = '$tipo_foto'
							";
					$qry = mysql_query($sql, $con) or die("Erro:  $sql");
					
					// cadastra a outra
				}
			}
		}
	}

	// Fotos Treinamento 3
	
	$foto1 = $_FILES['foto3']['name'];
	$qtd =  sizeof($foto1);

	if ( $qtd > 0 ){
		$seq = $qtd - 1;
		for ( $i = 0 ; $i <= $seq ; $i++ ){
			if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $_FILES['foto3']['type'][$i])){ 
				// passa
			}
			else{
				$max_file     = "5"; // Tamanho máximo do arquivo (em MB)
				$userfile_name = $_FILES['foto3']['name'][$i];
				$userfile_tmp  = $_FILES['foto3']['tmp_name'][$i];
				$userfile_size = $_FILES['foto3']['size'][$i];
				$userfile_type = $_FILES['foto3']['type'][$i];
				$filename = basename($_FILES['foto3']['name'][$i]);
				$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
				if((!empty($_FILES["foto3"])) && ($_FILES['foto3']['error'][$i] == 0)) {
		
					foreach ($allowed_image_types as $mime_type => $ext) {
						if($file_ext==$ext && $userfile_type==$mime_type){
							$error = "";
							break;
						}else{
							$error = "Só <strong>".$image_ext."</strong>Imagens são aceitas para upload<br />";
						}
					}
					if ($userfile_size > ($max_file*1048576)) {
						$error .= "As imagens precisam estar dentro do limite de ".$max_file."MB";
					}
				}else{
					$error = "Selecione uma imagem para tratamento";
				}
				// Gera um nome único para a imagem 
				$nome_imagem = $id_franquia.'_'.$ano.'_'.$semana.'_terceiro_treinamento_'.$i.'_.jpg';
				// Caminho de onde ficará a imagem 
				$caminho_imagem = "clientes/fotos_franquias/" . $nome_imagem;   
				// Faz o upload da imagem para seu respectivo caminho 
				try{
					unlink($caminho_imagem);
					move_uploaded_file($userfile_tmp, $caminho_imagem);
				}catch (SoapFault $e){
					return $e->getMessage();
					exit;
				}
				$sequencia = $i + 1;
				$tipo_foto = "TREINA_3_".$sequencia;
				
				// Verifico se para este Treinamento existe foto
				$sql = "SELECT id, nome_arquivo FROM cs2.tarefas_gerenciais_fotos
						WHERE id_tarefa = '$id' AND tipo_foto = '$tipo_foto'";
				
				$qry = mysql_query($sql) or die("Erro:  $sql");
				$id_foto = mysql_result($qry,0,'id');
				$nome_arquivo = mysql_result($qry,0,'nome_arquivo');		
				if ( $id_foto == 0 ){
					// Nao tem foto, cadastro
					$sql = "INSERT INTO cs2.tarefas_gerenciais_fotos
							( id_tarefa , tipo_foto , nome_arquivo )
							VALUES( '$id' , '$tipo_foto' , '$nome_imagem' )";
					$qry = mysql_query($sql, $con) or die("Erro:  $sql");
				}else{
					// Já existe
					// apaga a foto anterior se for diferente o nome
					//lsif ( $nome_arquivo <> $nome_imagem ) 
					 $sql = "UPDATE cs2.tarefas_gerenciais_fotos
							 SET
							 	nome_arquivo = '$nome_imagem'
							WHERE 
									id_tarefa = $id 
								AND
									tipo_foto = '$tipo_foto'
							";
					$qry = mysql_query($sql, $con) or die("Erro:  $sql");
					
					// cadastra a outra
				}
			}
		}
	}

	
	// Fotos Reuniao Quarta
	
	$foto1 = $_FILES['foto4']['name'];
	$qtd =  sizeof($foto1);

	if ( $qtd > 0 ){
		$seq = $qtd - 1;
		for ( $i = 0 ; $i <= $seq ; $i++ ){
			if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $_FILES['foto4']['type'][$i])){ 
				// passa
			}
			else{
				$max_file     = "5"; // Tamanho máximo do arquivo (em MB)
				$userfile_name = $_FILES['foto4']['name'][$i];
				$userfile_tmp  = $_FILES['foto4']['tmp_name'][$i];
				$userfile_size = $_FILES['foto4']['size'][$i];
				$userfile_type = $_FILES['foto4']['type'][$i];
				$filename = basename($_FILES['foto4']['name'][$i]);
				$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
				if((!empty($_FILES["foto4"])) && ($_FILES['foto4']['error'][$i] == 0)) {
		
					foreach ($allowed_image_types as $mime_type => $ext) {
						if($file_ext==$ext && $userfile_type==$mime_type){
							$error = "";
							break;
						}else{
							$error = "Só <strong>".$image_ext."</strong>Imagens são aceitas para upload<br />";
						}
					}
					if ($userfile_size > ($max_file*1048576)) {
						$error .= "As imagens precisam estar dentro do limite de ".$max_file."MB";
					}
				}else{
					$error = "Selecione uma imagem para tratamento";
				}
				// Gera um nome único para a imagem 
				$nome_imagem = $id_franquia.'_'.$ano.'_'.$semana.'_reuniao_quarta_'.$i.'_.jpg';
				// Caminho de onde ficará a imagem 
				$caminho_imagem = "clientes/fotos_franquias/" . $nome_imagem;   
				// Faz o upload da imagem para seu respectivo caminho 
				unlink($caminho_imagem);				
				move_uploaded_file($userfile_tmp, $caminho_imagem);
				
				$sequencia = $i + 1;
				$tipo_foto = "TREINA_4_".$sequencia;
				
				// Verifico se para este Treinamento existe foto
				$sql = "SELECT id, nome_arquivo FROM cs2.tarefas_gerenciais_fotos
						WHERE id_tarefa = '$id' AND tipo_foto = '$tipo_foto'";
				$qry = mysql_query($sql) or die("Erro:  $sql");
				$id_foto = mysql_result($qry,0,'id');
				$nome_arquivo = mysql_result($qry,0,'nome_arquivo');		
				if ( $id_foto == 0 ){
					// Nao tem foto, cadastro
					$sql = "INSERT INTO cs2.tarefas_gerenciais_fotos
							( id_tarefa , tipo_foto , nome_arquivo )
							VALUES( '$id' , '$tipo_foto' , '$nome_imagem' )";
					$qry = mysql_query($sql, $con) or die("Erro:  $sql");
				}else{
					// Já existe
					// apaga a foto anterior se for diferente o nome
					//lsif ( $nome_arquivo <> $nome_imagem ) unlink($nome_arquivo);
					$sql = "UPDATE cs2.tarefas_gerenciais_fotos
							 SET
							 	nome_arquivo = '$nome_imagem'
							WHERE 
									id_tarefa = $id 
								AND
									tipo_foto = '$tipo_foto'
							";
					$qry = mysql_query($sql, $con) or die("Erro:  $sql");
					
					// cadastra a outra
				}
			}
		}
	}
	
	// Fotos Reuniao Sexta
	
	$foto1 = $_FILES['foto5']['name'];
	$qtd =  sizeof($foto1);
	
	if ( $qtd > 0 ){
		$seq = $qtd - 1;
		for ( $i = 0 ; $i <= $seq ; $i++ ){
			if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $_FILES['foto5']['type'][$i])){ 
				// passa
			}
			else{
				$max_file     = "5"; // Tamanho máximo do arquivo (em MB)
				$userfile_name = $_FILES['foto5']['name'][$i];
				$userfile_tmp  = $_FILES['foto5']['tmp_name'][$i];
				$userfile_size = $_FILES['foto5']['size'][$i];
				$userfile_type = $_FILES['foto5']['type'][$i];
				$filename = basename($_FILES['foto5']['name'][$i]);
				$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
				if((!empty($_FILES["foto5"])) && ($_FILES['foto5']['error'][$i] == 0)) {
		
					foreach ($allowed_image_types as $mime_type => $ext) {
						if($file_ext==$ext && $userfile_type==$mime_type){
							$error = "";
							break;
						}else{
							$error = "Só <strong>".$image_ext."</strong>Imagens são aceitas para upload<br />";
						}
					}
					if ($userfile_size > ($max_file*1048576)) {
						$error .= "As imagens precisam estar dentro do limite de ".$max_file."MB";
					}
				}else{
					$error = "Selecione uma imagem para tratamento";
				}
				// Gera um nome único para a imagem 
				$nome_imagem = $id_franquia.'_'.$ano.'_'.$semana.'_reuniao_sexta_'.$i.'_.jpg';
				// Caminho de onde ficará a imagem 
				$caminho_imagem = "clientes/fotos_franquias/" . $nome_imagem; 
				
				// Faz o upload da imagem para seu respectivo caminho
				
				unlink($caminho_imagem);
				move_uploaded_file($userfile_tmp, $caminho_imagem);
				
				$sequencia = $i + 1;
				$tipo_foto = "TREINA_5_".$sequencia;

				// Verifico se para este Treinamento existe foto
				$sql = "SELECT id, nome_arquivo FROM cs2.tarefas_gerenciais_fotos
						WHERE id_tarefa = '$id' AND tipo_foto = '$tipo_foto'";
				
				$qry = mysql_query($sql) or die("Erro:  $sql");
				$id_foto = mysql_result($qry,0,'id');
				$nome_arquivo = mysql_result($qry,0,'nome_arquivo');		
				if ( $id_foto == 0 ){
					// Nao tem foto, cadastro
					$sql = "INSERT INTO cs2.tarefas_gerenciais_fotos
							( id_tarefa , tipo_foto , nome_arquivo )
							VALUES( '$id' , '$tipo_foto' , '$nome_imagem' )";
					$qry = mysql_query($sql, $con) or die("Erro:  $sql");
				}else{
					// Já existe
					// apaga a foto anterior se for diferente o nome
					//lsif ( $nome_arquivo <> $nome_imagem ) unlink($nome_arquivo);
					$sql = "UPDATE cs2.tarefas_gerenciais_fotos
							 SET
							 	nome_arquivo = '$nome_imagem'
							WHERE 
									id_tarefa = $id 
								AND
									tipo_foto = '$tipo_foto'
							";
					$qry = mysql_query($sql, $con) or die("Erro:  $sql");
				}
			}
		}
	}
	
echo "<script>alert('Registro gravado com sucesso.')</script>";

echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_controle_visitas4.php&wanted_week=$semana&abre_form=2&ano_esc=$ano&rel_franquia=$id_franquia\";>";

?>