<script language="javascript">

function valida(){
	frm = document.form;
	if (d.frq.value == ""){
		alert("O destinatario deve ser selecionado!");
		d.frq.focus();
		return false;
	}
	if (d.assunto.value == ""){
		alert("O campo " + d.assunto.name + " deve ser preenchido!");
		d.assunto.focus();
		return false;
	}
	if (d.recado.value == ""){
		alert("O campo " + d.recado.name + " deve ser preenchido!");
		d.recado.focus();
		return false;
	}
	processa();	
}

function processa(){
	frm = document.form;
 	frm.action = 'area_restrita/d_envio_email_franqueados.php?iniciar=1';
 	frm.submit();
}

</script>

<form enctype="multipart/form-data" action="#" method="POST" name="form">
    <table width=80% align="center">
        <tr>
            <td width="100%" align="right" class="pageName">Envio de Mensagem para Franqueados</td>
        </tr>
    </table>
    <table width=80% align="center">
        <tr>
            <td colspan="2" bgcolor="#CCCCCC">&nbsp;</td>
        </tr>
        <tr>
            <td width="130" class="subtitulodireita">&nbsp;</td>
            <td width="614" class="campoesquerda">&nbsp;</td>
        </tr>
        <input type='hidden' name='iniciar' id='iniciar' value='1'>
        <tr>
            <td class="subtitulodireita">De: </td>
            <td class="campoesquerda">
                <?php if ( $id_franquia == 163 ){
                    echo '&nbsp;'.$nome_franquia;
                    echo "<input type='hidden' name='nome' id='nome' value='$id_franquia'></td></tr>";
                }else{?>
                <select name="remetente" class="boxnormal">
                    <option selected>:: Selecione o Remetente ::</option>
                    <option value='franquias'>Franquias</option>
                    <option value='danillo'>Danillo</option>
                </select></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Senha do Remetente:</td>
            <td class="campoesquerda"><input type="password" name="senha_remetente" id="senha_remetente" size="30" maxlength="20" class="boxnormal" /></td>
        </tr>

        <?php
        }
        ?>
        <tr>
            <td class="subtitulodireita">Para franquia:</td>
            <td class="campoesquerda">
                <select name="frq" class="boxnormal">
                    <option selected>:: Selecione o Destinat&aacute;rio ::</option>
                    <option value="todos">Todas as Franquias</option>
                </select>
                <?php
                /*
                  if ($tipo == "b") {
                      $sql = "select id, razaosoc from franquia where sitfrq <> 1 and (tipo = 'a' or tipo = 'c') and id_franquia_master = 0 order by id";
                      $resposta = mysql_query($sql);
                      while ($array = mysql_fetch_array($resposta))
                      {
                          $franquia   = $array["id"];
                          $nome_franquia = $array["razaosoc"];
                          echo "<option value=\"$franquia\">$nome_franquia</option>\n";
                      }
                  } else {
                      $sql = "select id, razaosoc from franquia where sitfrq <> 1 and classificacao = 'M' order by id ";
                      $resposta = mysql_query($sql);
                      while ($array = mysql_fetch_array($resposta)){
                          $franquia   = $array["id"];
                          $nome_franquia = $array["razaosoc"];
                          echo "<option value=\"$franquia\">$nome_franquia</option>\n";
                      }
                      echo "<option value=\"todos\">Todas as Franquias</option>";
                  }
                  */
                ?>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Anexar Arquivo:</td>
            <td class="campoesquerda"><input  style="width:80%" name="uploaded" type="file" /></td>
        </tr>

        <tr>
            <td class="subtitulodireita">Assunto:</td>
            <td class="campoesquerda"><input type="text" name="assunto" id="assunto" size="80" maxlength="50" class="boxnormal" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Mensagem</td>
            <td class="campoesquerda">
                <?php
                include "fckeditor/fckeditor.php"; //Chama a classe fckeditor

                $editor = new FCKeditor("recado");//Nomeia a area de texto
                $editor-> BasePath = "fckeditor/"; //Informa a pasta do FKC Editor
                $editor-> Value = $pag_texto;//Informa o valor inicial do campo, no exemplo est� vazio
                $editor-> Width = "100%"; //informa a largura do editor
                $editor-> Height = "500";//informa a altura do editor
                $editor-> Create();// Cria o editor
                ?>
            </td>
        </tr>

        <tr>
            <td class="subtitulodireita">&nbsp;</td>
            <td class="campoesquerda">&nbsp;</td>
        </tr>

        <tr>
        <tr>
            <td colspan="2" bgcolor="#CCCCCC" align="center">&nbsp;
                <input type="image" id="submit" src="../img/mail.gif" alt="Enviar Email" align="middle"  onclick="valida()"/>&nbsp;</td>
        </tr>
        </tr>

    </table>

<?php
global $arquivo, $remetente, $senha_remetente;

	$nome            = $_REQUEST['nome'];
	$frq             = $_REQUEST['frq'];
	$assunto         = $_REQUEST['assunto'];
	$conteudo        = $_REQUEST['recado'];
	$remetente       = $_REQUEST['remetente'];
	$senha_remetente = $_REQUEST['senha_remetente'];
	
	if ( $_REQUEST['iniciar']=='1' ){
		# Nome do arquivo para processamento
		
		$target = "/var/www/html/franquias/php/area_restrita/upload/"; 
		$target = $target . basename( $_FILES['uploaded']['name']);
		$arquivo =  basename( $_FILES['uploaded']['name']);
		
		if ( !empty($arquivo) ){
			try{
				if( move_uploaded_file($_FILES['uploaded']['tmp_name'], $target) ){
					$arquivo = $target;
				}
			} catch (Exception $e) {
				var_dump($e->getMessage());
				echo " erro";
				exit;
			}
			// Fim do Envio do arquivo para o Servidor
		}
		
		$cnx_email = @mysql_pconnect('10.2.2.7','root','cntos43')or die ("BD em Manutencao");
		$bdx = mysql_select_db('vpopmail',$cnx_email) or die("Impossivel selecionar o BD");
			
		if ( $id_franquia == 163 ){
			$sql_email = "SELECT pw_name, pw_clear_passwd FROM vpopmail.webcontrolempresas_com_br
						  WHERE pw_name = 'administrativo'";
		}else{
			$sql_email = "SELECT pw_name, pw_clear_passwd FROM vpopmail.webcontrolempresas_com_br
						  WHERE pw_name = '$remetente' AND pw_clear_passwd = '$senha_remetente'";
		}
		$qry_email = mysql_query($sql_email, $cnx_email) or die("Erro SQL: $sql_email");
		if ( mysql_num_rows($qry_email) == 0 ){
			echo "<script>alert(\"SENHA DO REMETENTE INVALIDO!\");</script>";
			exit;
		}else{
				$reg = mysql_fetch_array($qry_email);
				$usuario_smtp = $reg['pw_name'].'@webcontrolempresas.com.br';
				$senha_smtp   = $reg['pw_clear_passwd'];
		}
		
		//include ("area_restrita/smtp.class.php");
		
		// Inicio do envio do Email
		if ($frq == 'todos') {
	

			require("area_restrita/class.phpmailer.php"); 
			// Inicia a classe PHPMailer
				
			$mail = new PHPMailer();
			$mail->IsSMTP(); // Define que a mensagem ser� SMTP
			$mail->Host = "10.2.2.7"; // Endere�o do servidor SMTP
			$mail->SMTPAuth = true; // Usar autentica��o SMTP (obrigat�rio para smtp.seudom�nio.com.br)
			$mail->Username = $usuario_smtp; // Usu�rio do servidor SMTP
			$mail->Password = $senha_smtp; // Senha do servidor SMTP
			
			$lista = '';
			
			$sql = "SELECT id, email, fantasia, razaosoc FROM franquia
					WHERE sitfrq <> 1 AND id_franquia_master = 0 AND classificacao = 'M' AND LENGTH(email)>0
					ORDER BY id";
			$ql = mysql_query($sql, $con);
			$linha = mysql_num_rows ($ql);
			while($registro = mysql_fetch_array($ql)){
				$email = $registro['email'];
				$fantasia = $registro['fantasia'];
				
				$lista .= "Email: $email - $fantasia<br>";
				
				$mail->AddBCC($email, 'Web Control Empresas'); // C�pia Oculta
			}
				
			// Define o remetente
			$mail->From = $usuario_smtp; // Seu e-mail
			$mail->Sender = $usuario_smtp; // Seu e-mail
			$mail->FromName = "Web Control Empresas"; // Seu nome
			// Define os destinat�rio(s)
			$mail->AddAddress('lucianomancini@hotmail.com', 'Web Control Empresas');
				
			// Define os dados t�cnicos da Mensagem
			$mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
			//$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
			// Define a mensagem (Texto e Assunto)
			$mail->Subject  =  $assunto; // Assunto da mensagem
			$mail->Body = $conteudo;
			//$mail->AltBody = 'Este � o corpo da mensagem de teste, em Texto Plano!";
 
			// Define os anexos (opcional)
			if ( !empty($arquivo) )
				$mail->AddAttachment($target, $arquivo);  // Insere um anexo
				

				
			$enviado = $mail->Send();
			// Limpa os destinat�rios e os anexos
			if ($enviado) {
				$mensagem .= "Emails enviados com Sucesso !";
			} else {
				$mensagem .= "ERRO[$email] ". $mail->ErrorInfo;
			}
			$mail->ClearAllRecipients();
			$mail->ClearAttachments();
			// Exibe uma mensagem de resultado

			echo "<script>alert(\"$mensagem\");</script>";
			
			echo "Email enviados para: <br><br>$lista";
			
		}else{
			
			$sql = "SELECT id, email, razaosoc FROM franquia 
					WHERE sitfrq <> 1 AND id_franquia_master = 0 AND LENGTH(email)>0 and id = $frq";
			$ql = mysql_query($sql, $con);
			$linha = mysql_num_rows ($ql);
			while($registro = mysql_fetch_array($ql)){
				$email = $registro['email'];
			}
			mysql_close($con);
			// Fim do Envio do Email
		}
	}
?>

</form>