<script type="text/javascript">
    /* M�scaras ER */
    function xmascara(o, f) {
        v_obj = o
        v_fun = f
        setTimeout("xexecmascara()", 1)
    }
    function xexecmascara() {
        v_obj.value = v_fun(v_obj.value)
    }
    function mtel(v) {
        v = v.replace(/\D/g, "");             //Remove tudo o que n�o � d�gito
        v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); //Coloca par�nteses em volta dos dois primeiros d�gitos
        v = v.replace(/(\d)(\d{4})$/, "$1-$2");    //Coloca h�fen entre o quarto e o quinto d�gitos
        return v;
    }
    function id(el) {
        return document.getElementById(el);
    }
    window.onload = function () {
        id('celular').onkeypress = function () {
            xmascara(this, mtel);
        }
        id('celular2').onkeypress = function () {
            xmascara(this, mtel);
        }
    }
</script>

<?php
session_start();
include("class.send.php");
/*
  $name = $_SESSION["ss_name"];
  $tipo = $_SESSION["ss_tipo"];
  if (($name=="") || ($tipo!="a")){
  session_unregister($_SESSION['name']);
  session_destroy();
  echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
  die;
  }
 */

$go = $_REQUEST['go'];
$id = $_REQUEST['id'];

if (empty($go)) {

    $comando = "SELECT 	nome, cpfcnpj, naturalidade, endereco, bairro, cidade, uf, fone, cep, complemento,
					celular, celular2, email, tp_residencia, upper(cid_atuacao) as cid_atuacao, obs,
					date_format(data_envio,'%d/%m/%Y') as data_envio, 
					date_format(data_contato,'%d/%m/%Y') as data_contato,
					date_format(apresenta,'%d/%m/%Y') as apresenta, 
					date_format(agenda_treina,'%d/%m/%Y') as agenda_treina,
					date_format(treina,'%d/%m/%Y') as treina, 
					date_format(abertura,'%d/%m/%Y') as abertura, espiao,
					date_format(data_retorno,'%d/%m/%Y') AS data_retorno, hora_retorno
			FROM pretendentes 
			WHERE id='$id'";
    $res = mysql_query($comando, $con);
    $matriz = mysql_fetch_array($res);
    ?>

    <script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
    <script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>
    <script type="text/javascript" src="../js/jquery.meio.mask.js"></script>
    <script src="../js/funcoes.js"></script>
    <script>
        function mascara(o, f) {
            v_obj = o
            v_fun = f
            setTimeout("execmascara()", 1)
        }

        function execmascara() {
            v_obj.value = v_fun(v_obj.value)
        }

        function enviar_proposta(tipo) {

            frm = document.form;
            if (tipo == 'franquia')
                frm.action = 'area_restrita/d_envio_email_candidatos.php?tipo=' + tipo + '&id=' +<?= $id ?>;
            else if (tipo == 'micro_franquia')
                frm.action = 'area_restrita/d_envio_email_candidatos.php?tipo=' + tipo + '&id=' +<?= $id ?>;
            frm.submit();

        }

    // formato mascara data

        function data(v) {
            v = v.replace(/\D/g, "")                    //Remove tudo o que n�o � d�gito
            v = v.replace(/(\d{2})(\d)/, "$1/$2")
            v = v.replace(/(\d{2})(\d)/, "$1/$2")

            return v
        }

        (function ($) {
            // call setMask function on the document.ready event
            $(function () {
                $('input:text').setMask();
            }
            );
        })(jQuery);

        jQuery(function ($) {
            $("#hora_retorno").mask("99:99");
            $("#data_retorno").mask("99/99/9999");
        });

        window.onload = function () {
            document.form.nome.focus();
        }

    </script>

    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="form">
        <table border="0" width="650px" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
            <tr>
                <td colspan="2" class="titulo">PRETENDENTES A FRANQUEADOS</td>
            </tr>
            <tr>
                <td class="subtitulodireita" width="50%">Nome</td>
                <td class="subtitulopequeno" width="50%"><input name="nome" type="text" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal';
                        maiusculo(this)" value="<?php echo strtoupper($matriz['nome']); ?>" size="50" maxlength="80" /></td>
            </tr>


            <tr>
                <td class="subtitulodireita">CPF</td>
                <td class="subtitulopequeno"><input name="cpfcnpj" type="text" onKeyPress="soNumero();
                        formatar('###.###.###-##', this)" value="<?php echo strtoupper($matriz['cpfcnpj']); ?>" size="22" maxlength="18"  class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Endere�o</td>
                <td class="subtitulopequeno"><input name="endereco" type="text" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal';maiusculo(this)" value="<?php echo strtoupper($matriz['endereco']); ?>" size="50" maxlength="80" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Complemento</td>
                <td class="subtitulopequeno"><input name="complemento" type="text" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal';maiusculo(this)" value="<?php echo strtoupper($matriz['complemento']); ?>" size="50" maxlength="80" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Bairro</td>
                <td class="subtitulopequeno"><input name="bairro" type="text" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal';
                        maiusculo(this)" value="<?php echo strtoupper($matriz['bairro']); ?>" size="50" maxlength="80" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Cidade</td>
                <td class="subtitulopequeno"><input type="text" name="cidade" size="40" maxlength="30" value="<?php echo strtoupper($matriz['cidade']); ?>" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal';maiusculo(this)" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">UF</td>
                <td class="subtitulopequeno"><input type="text" name="uf" size="4" maxlength="2" value="<?php echo strtoupper($matriz['uf']); ?>"  class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal';maiusculo(this)" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">CEP</td>
                <td class="subtitulopequeno"><input type="text" name="cep" size="10" maxlength="10" value="<?php echo strtoupper($matriz['cep']); ?>"  class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal';maiusculo(this)" /></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Telefone</td>
                <td class="subtitulopequeno"><input name="fone" type="text" onKeyPress="formatar('##-####-####', this)" value="<?php echo strtoupper($matriz['fone']); ?>" size="25" maxlength="12" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" /></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Celular 1</td>
                <td class="subtitulopequeno"><input name="celular" id="celular"type="text" value="<?php echo strtoupper($matriz['celular']); ?>" size="25" maxlength="15" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'"/></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Celular 2</td>
                <td class="subtitulopequeno"><input name="celular2" id="celular2" type="text" value="<?php echo strtoupper($matriz['celular2']); ?>" size="25" maxlength="15" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'"/></td>
            </tr>

            <tr>
                <td class="subtitulodireita">E-mail</td>
                <td class="subtitulopequeno"><input name="email" type="text" value="<?php echo strtoupper($matriz['email']); ?>" size="25" maxlength="200" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" /></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Tempo que reside na cidade</td>
                <td class="subtitulopequeno"><textarea name="tp_residencia" cols="40" rows="3"><?php echo strtoupper($matriz['tp_residencia']); ?></textarea></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Cidade / Regi&atilde;o de interesse para atua&ccedil;&atilde;o da franquia:</td>
                <td class="subtitulopequeno"><textarea name="cid_atuacao" cols="40" rows="3"><?php echo strtoupper($matriz['cid_atuacao']); ?></textarea></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Observa&ccedil;&otilde;es Diversas / Mensagem:</td>
                <td class="subtitulopequeno"><textarea name="obs" cols="40" rows="3"><?php echo strtoupper($matriz['obs']); ?></textarea></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Data que o prentendente preencheu a proposta</td>
                <td class="subtitulopequeno"><font size="2"><b><?php echo strtoupper($matriz['data_envio']); ?></b></font></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Espi&atilde;o da Concorr&ecirc;ncia</td>
                <td class="subtitulopequeno"><input type="checkbox" name="espiao" <?php if ($matriz['espiao'] == 1) echo "checked"; ?> /></td>
            </tr>
            <tr>
                <?php
                if ($matriz['data_retorno'] == "00/00/0000") {
                    $data_retorno = "";
                } else {
                    $data_retorno = $matriz['data_retorno'];
                }
                unset($hora_retorno);
                if ($matriz['hora_retorno'] == "00:00:00") {
                    $data_retorno = "";
                } else {
                    $hora_retorno = substr($matriz['hora_retorno'], 0, 5);
                }
                ?>    
                <td bgcolor="#FFCC66" align="right"><font color="#FF0000" size="-1"><b>Dia e Hora de Retorno</b></font></td>
                <td class="subtitulopequeno"><input type="text" maxlength="10" name="data_retorno" id="data_retorno" class="boxnormal" onfocus="this.className = 'boxover'" onblur="this.className = 'boxnormal'" value="<?= $data_retorno ?>" style="width:20%" />
                    &nbsp;&nbsp;&nbsp;
                    <input type="text" name="hora_retorno" id="hora_retorno" maxlength="5" class="boxnormal" onfocus="this.className = 'boxover'" onblur="this.className = 'boxnormal'" value="<?= $hora_retorno ?>" style="width:15%" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Realizada Apresenta&ccedil;&atilde;o pelo telefone?</td>
                <td class="subtitulopequeno"><input type="text" name="apresenta" maxlength="10" size="12" onKeyPress="mascara(this, data)" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" value="<?php echo strtoupper($matriz['apresenta']); ?>" /></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Envio de Proposta</td>
                <td class="subtitulopequeno"> 
                    <!--<input type="submit" name="script" value="Enviar proposta de MICRO FRANQUIA" onclick="enviar_proposta('micro_franquia')" /><br>-->
                    <input type="submit" name="script" value="Enviar proposta de FRANQUIA" onclick="enviar_proposta('franquia')" />

                </td>
            </tr>

            <tr>
                <td class="subtitulodireita">Agendado treinamento na Matriz?</td>
                <td class="subtitulopequeno"><input type="text" name="agenda_treina" maxlength="10" size="12" onKeyPress="mascara(this, data)" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" value="<?php echo $matriz['agenda_treina']; ?>" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Realizado treinamento 4 dias?</td>
                <td class="subtitulopequeno"><input type="text" name="treina" maxlength="10" size="12" onKeyPress="mascara(this, data)" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" value="<?php echo $matriz['treina']; ?>" /></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Abertura da Franquia?</td>
                <td class="subtitulopequeno"><input type="text" name="abertura" maxlength="10" size="12" onKeyPress="mascara(this, data)" class="boxnormal" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" value="<?php echo $matriz['abertura']; ?>" /></td>
            </tr>
            <tr>
                <td colspan="2" class="titulo">
                    <input type="hidden" name="go" value="altera">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" >    </td>
            </tr>
        </table>
        <table align="center">
            <tr align="center">
                <td><input name="submit" type="submit" 
                    <?php
                    if (empty($id))
                        echo "value=\"     Incluir    \"";
                    else
                        echo "value=\"     Modificar    \"";
                    ?> /></td>
                <td>
                    <input name="button" type="button" onClick="document.location.href = 'painel.php?pagina1=area_restrita/pretendentes_form_listar.php&id_status=1&go=1&af=2'" value="Menu principal" />&nbsp;&nbsp;
                    <input name="button" type="button" onClick="document.location.href = 'area_restrita/pretendentes_bd.php?id_status=1&go=1&af=2&id=<?= $id ?>&acao=1'" value="Recusado" />
                    &nbsp;&nbsp;
                    <input name="button" type="button" onClick="document.location.href = 'area_restrita/pretendentes_bd.php?id_status=1&go=1&af=2&id=<?= $id ?>&acao=2'" value="N&atilde;o Obtive Contato" />
                </td>
            </tr>
        </table>
    </form>
    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>?pagina1=area_restrita/pretendentes_form_listar.php&af=1&go=1" >
        <table align="center">
            <tr align="center">
                <td>
                    <input type="hidden" name=af value="1" />
                    <input type=hidden value="filtrar" name="go" >
                    <input name="button" type="submit" value="N&atilde;o apresentados" />
                </td>
                <td>
                    <input type="button" name="script" value="Script de Venda" onclick="window.open('area_restrita/d_script.php');" />
                </td>
            </tr>
        </table>
    </form>
    <?php
    include "ranking/mensagem_pretendentes.php";
} //fim empty go

if ($go == 'altera') {

    $comando = "SELECT apresenta, agenda_treina, treina, abertura FROM pretendentes WHERE id='$id'";
    $res = mysql_query($comando, $con);
    $antigo_apresenta = mysql_result($res, 0, 'apresenta');
    $antigo_agenda_treina = mysql_result($res, 0, 'agenda_treina');
    $antigo_treina = mysql_result($res, 0, 'treina');
    $antigo_abertura = mysql_result($res, 0, 'abertura');

    //envia e-mail para o Teixeira
    if (!empty($_REQUEST['agenda_treina']) and empty($antigo_agenda_treina)) {

        # Buscando o conte�do do TEXTO para envio.

        $sql_texto = "SELECT texto_email from cs2.pretendentes_status WHERE id = 5";
        $res_texto = mysql_query($sql_texto, $con);

        $texto_email = mysql_result($res_texto, 0, 'texto_email');
        $texto_email = str_replace('{nome_candidato}', $_REQUEST['nome'], $texto_email);
        $texto_email = str_replace('{data_agendada}', $_REQUEST['agenda_treina'], $texto_email);

        $contato = new SendEmail;
        $contato->nomeEmail = "Gerente de Franquias";          // Nome do Responsavel
        $contato->paraEmail = $_REQUEST['email'];             // Email do destinatario
        $contato->copiaOculta = "teixeira@webcontrolempresas.com.br";
        $contato->copiaEmail = "danillo@webcontrolempresas.com.br";
        $contato->copiaNome = "Danillo Araujo";
        $contato->configHost = "10.2.2.7";                     // Endere�o do seu SMTP
        $contato->configPort = 25;                             // Porta Padr�o 25
        $contato->configUsuario = "danillo@webcontrolempresas.com.br"; // Login do email que ira utilizar
        $contato->configSenha = "25031964";                   // Senha do email
        $contato->remetenteEmail = "danillo@webcontrolempresas.com.br"; // Remetente da mensagem
        $contato->remetenteNome = "Gerente de Franquias";          // Um nome para o remetente
        $contato->assuntoEmail = "CONFIRMACAO DE TREINAMENTO DE FRANQUIA - WEB CONTROL EMPRESAS"; // Assunto da mensagem
        $contato->conteudoEmail = $texto_email;                   // Conteudo da mensagem
        $contato->confirmacao = 1;                                 // Se for 1 exibi a mensagem de confirma��o
        $contato->erroMsg = "Uma mensagem de erro aqui";           // pode colocar uma mensagem de erro aqui!!
        $contato->confirmacaoErro = 1;                             // 1 = exibi o erro 0 = n�o exibi o erro 

        try {
            $contato->enviar(); // envia a mensagem
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            exit;
        }
        //grava a ocorrencia
        $sql_oco = "insert into cs2.ocorr_pretendentes (pretendente, msg, data) values ('{$_REQUEST['id']}', 'LIGOU E AGENDOU TREINAMENTO DE FRANQUIA. MANDEI EMAIL DE CONFIRMA��O', now())";
        $qr_oco = mysql_query($sql_oco, $con) or die("erro ao incluir o comentario" . mysql_error());
    }


    $id = $_POST['id'];
    $apresenta = $_POST['apresenta'];
    $nome = $_POST['nome'];
    $cpfcnpj = $_POST['cpfcnpj'];
    $naturalidade = $_POST['naturalidade'];
    $endereco = $_POST['endereco'];
    $complemento = $_POST['complemento'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $uf = $_POST['uf'];
    $cep = $_POST['cep'];
    $fone = $_POST['fone'];
    $celular = $_POST['celular'];
    $celular2 = $_POST['celular2'];
    $email = $_POST['email'];
    $tp_residencia = $_POST['tp_residencia'];
    $cid_atuacao = $_POST['cid_atuacao'];
    $obs = $_POST['obs'];

    $agenda_treina = $_POST['agenda_treina'];
    $treina = $_POST['treina'];
    $abertura = $_POST['abertura'];
    $data = date("Y-m-d");
    $espiao = $_POST['espiao'];

    $data_retorno = converteDataGravaBanco($_REQUEST['data_retorno']);
    $hora_retorno = $_REQUEST['hora_retorno'];

    if (!isset($espiao))
        $espiao = 0;
    else
        $espiao = 1;

    $cpfcnpj = str_replace("/", "", $cpfcnpj);
    $cpfcnpj = str_replace("-", "", $cpfcnpj);
    $cpfcnpj = str_replace(".", "", $cpfcnpj);
    $cpfcnpj = trim($cpfcnpj);

    $fone = str_replace("(", "", $fone);
    $fone = str_replace(")", "", $fone);
    $fone = str_replace("-", "", $fone);

    $celular = str_replace("(", "", $celular);
    $celular = str_replace(")", "", $celular);
    $celular = str_replace("-", "", $celular);

    $celular2 = str_replace("(", "", $celular2);
    $celular2 = str_replace(")", "", $celular2);
    $celular2 = str_replace("-", "", $celular2);


    if (!empty($apresenta)) {
        $apresenta1 = implode(preg_match("~\/~", $apresenta) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $apresenta) == 0 ? "-" : "/", $apresenta)));
        $apresenta2 = "apresenta = '$apresenta1',";
    } else
        $apresenta2 = "apresenta = null,";

    if (!empty($agenda_treina)) {
        $agenda_treina1 = implode(preg_match("~\/~", $agenda_treina) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $agenda_treina) == 0 ? "-" : "/", $agenda_treina)));
        $agenda_treina2 = "agenda_treina = '$agenda_treina1', status = '5', ";
    } else
        $agenda_treina2 = "agenda_treina = null,";

    if (!empty($treina)) {
        $treina1 = implode(preg_match("~\/~", $treina) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $treina) == 0 ? "-" : "/", $treina)));
        $treina2 = "treina = '$treina1',";
    } else
        $treina2 = "treina = null,";

    if (!empty($abertura)) {
        $abertura1 = implode(preg_match("~\/~", $abertura) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $abertura) == 0 ? "-" : "/", $abertura)));
        $abertura2 = "abertura = '$abertura1',";
    } else
        $abertura2 = "abertura = null,";

    if ($id != '') {

        $sql = "update cs2.pretendentes set
			nome 	= '$nome',				
		data_retorno = '$data_retorno',
		hora_retorno = '$hora_retorno',		
		cpfcnpj = '$cpfcnpj',
		naturalidade = '$naturalidade',
		endereco = '$endereco',
		complemento = '$complemento',
		bairro = '$bairro',
		cidade 	= '$cidade',
		uf 		= '$uf',
		cep 		= '$cep',
		fone 	= '$fone',
		celular = '$celular',
		celular2 = '$celular2',
		email 	= '$email',
		tp_residencia = '$tp_residencia',
		cid_atuacao = '$cid_atuacao',
		obs 	= '$obs',
		$apresenta2
		$agenda_treina2
		$treina2
		$abertura2
		espiao = '$espiao'
		where id = '$id'";
    } else {
        $sql = "insert into pretendentes (nome, cpfcnpj, naturalidade, endereco, cidade, uf, fone, celular, email, tp_residencia, cid_atuacao, obs, data_envio, apresenta, agenda_treina, treina, abertura, espiao) values ('$nome', '$cpfcnpj', '$naturalidade', '$endereco', '$cidade', '$uf', '$fone', '$celular', '$email', '$tp_residencia', '$cid_atuacao', '$obs', '$data', '$apresenta1', '$agenda_treina1', '$treina1', '$abertura1', '$espiao')";
    }

    $qr = mysql_query($sql, $con) or die("Erro:   $sql");
    $res = mysql_close($con);
    echo "<script>alert(\"Pretendente atualizado com sucesso!\");</script>";
    if ($id != '')
        echo "<meta http-equiv=\"refresh\" content=\"0; url=painel.php?pagina1=area_restrita/d_pretendentes.php&id=$id\";>";
    else
        echo "<meta http-equiv=\"refresh\" content=\"0; url=painel.php?pagina1=area_restrita/d_rel_pretendentes.php\";>";
} //fim altera
?>