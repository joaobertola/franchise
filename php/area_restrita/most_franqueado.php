<?php
//require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

$id = $_GET['id'];
if (empty($id)) $id = $_POST['id'];

$comando = "SELECT * FROM cs2.franquia WHERE id = '{$_REQUEST['id']}'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res); 
$id = $matriz['id'];

//seleciona os jornais
$sql_jornal = "SELECT * FROM cs2.franquia_relacao_jornal 
			   WHERE id_franquia = '{$_REQUEST['id']}'
			   ORDER BY id";
$qry_jornal = mysql_query ($sql_jornal, $con);
$total_jornal = mysql_num_rows($qry_jornal);

function mascaraFoneFranquia($p_telefone){
    if ($p_telefone == '') {
	return ('');
    } else {

	$a = substr($p_telefone, 0, 2); // ddd

	if (strlen($p_telefone) <= 10) {

	    $b = substr($p_telefone, 2, 4); // 4 primeiras posicoes
	    $c = substr($p_telefone, 6, 4); // 4 ultimas posicoes
	} else {

	    $b = substr($p_telefone, 2, 5); // 5 primeiras posicoes
	    $c = substr($p_telefone, 7, 4); // 4 ultimas posicoes
	}
	$telefone_mascarado = "(";
	$telefone_mascarado .= $a;
	$telefone_mascarado .= ")&nbsp;";
	$telefone_mascarado .= $b;
	$telefone_mascarado .= "-";
	$telefone_mascarado .= $c;

	return ($telefone_mascarado);
    }  
}
?>
<script language="javascript">
function novoCadastro(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/d_cadfranqueado.php';
	frm.submit();
}

function alterarCadastro(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/d_altfranqueado.php&id=<?=$matriz['id']?>';
	frm.submit();
}
</script>
<body>
<form name="form" method="post" action="#">
<table border="0" align="center" width="80%">
  <tr>
    <td colspan="4" class="titulo">VISUALIZA&Ccedil;&Atilde;O DE FRANQUEADOS</td>
  </tr>
  
  <tr>
    <td width="33%" class="subtitulodireita">ID do Franqueado</td>
    <td colspan="3" class="campojustificado"><?php echo $matriz['id'];?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">SITUA&Ccedil;&Atilde;O da Franquia</td>
    <td width="23%" class="subtitulopequeno">
        <select name="sitfrq" disabled>
            <option value="0" <?php if ($matriz['sitfrq'] == "0") { echo "selected"; } ?> >ATIVA</option>
            <option value="1" <?php if ($matriz['sitfrq'] == "1") { echo "selected"; } ?> >BLOQUEADA</option>
            <option value="2" <?php if ($matriz['sitfrq'] == "2") { echo "selected"; } ?> >CANCELADA</option>
        </select>
	</td>
    <td width="23%" class="subtitulodireita">SITUA&Ccedil;&Atilde;O do Repasse</td>
    <td width="21%" class="subtitulopequeno"><select name="sitfrq2" disabled>
      <option value="0" <?php if ($matriz['situacao_repasse'] == "0") { echo "selected"; } ?> >ATIVO</option>
      <option value="1" <?php if ($matriz['situacao_repasse'] == "1") { echo "selected"; } ?> >BLOQUEADO</option>

    </select></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Senha</td>
    <td class="subtitulopequeno"><?php echo $matriz['senha'];?></td>
    <td class="subtitulopequeno">Classificacao</td>
    <td class="subtitulopequeno">
    	<?php

        if ($matriz['classificacao'] == "M") echo "MASTER";
			elseif ($matriz['classificacao'] == "J") echo "JUNIOR";
			elseif ($matriz['classificacao'] == "X") echo "MICRO FRANQUIA";
		?>
		
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Senha da &aacute;rea restrita </td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['senha_restrita']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nome da Franquia </td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['fantasia']; ?></td>
  </tr>
  <tr>
      <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">CNPJ</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['cpfcnpj']; ?></td>
  </tr>
  <tr>
      <td class="subtitulodireita">Endere&ccedil;o</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['endereco']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['bairro']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">UF</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['uf']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['cidade']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">CEP</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['cep']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Telefone 1</td>
    <td colspan="3" class="subtitulopequeno"><?php echo mascaraFoneFranquia( str_replace(' ','',$matriz['fone1']) ); ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Telefone 2</td>
    <td colspan="3" class="subtitulopequeno"><?php echo mascaraFoneFranquia(str_replace(' ','',$matriz['fone3'])); ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Telefone
      Residencial</td>
    <td colspan="3" class="subtitulopequeno"><?php echo mascaraFoneFranquia(str_replace(' ','',$matriz['fone2'])); ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">E-mail</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['email']; ?></td>
  </tr>
  <tr>
      <td class="subtitulodireita">Propriet&aacute;rio 1 </td>
    <td colspan="3" class="subtitulopequeno"><table border="0">
      <tr>
        <td class="subtitulodireita">Nome</td>
        <td class="campoesquerda"><?php echo $matriz['nom01socio']; ?></td>
      </tr>
      <tr>
        <td class="subtitulodireita">CPF 1</td>
        <td class="campoesquerda"><?php echo $matriz['doc01socio']; ?></td>
      </tr>
      <tr>
        <td class="subtitulodireita">Celular</td>
        <td class="campoesquerda"><?php echo mascaraFoneFranquia(str_replace(' ','',$matriz['cel01socio'])); ?></td>
      </tr>
       <tr>
        <td class="subtitulodireita">Operadora</td>
        <td class="campoesquerda">
			<?php 
				$sql_op_1 = "SELECT operadora_1 FROM cs2.franquia WHERE id = '{$_REQUEST['id']}'";
				$qry_ope_1 = mysql_query($sql_op_1,$con);
				@$operadora_1     = mysql_result($qry_ope_1,0,'operadora_1');				
				$sql_op_1 = "SELECT descricao FROM cs2.operadora WHERE id = '$operadora_1'";
				$qry_ope_1 = mysql_query($sql_op_1,$con);
				@$descricao = mysql_result($qry_ope_1,0,'descricao');
                                echo $descricao;
			?>
            </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Propriet&aacute;rio 2</td>
    <td colspan="3" class="subtitulopequeno"><table border="0">
      <tr>
        <td class="subtitulodireita">Nome</td>
        <td class="campoesquerda"><?php echo $matriz['nom02socio']; ?></td>
      </tr>
      <tr>
        <td class="subtitulodireita">CPF 2</td>
        <td class="campoesquerda"><?php echo $matriz['doc02socio']; ?></td>
      </tr>
      <tr>
        <td class="subtitulodireita">Celular</td>
        <td class="campoesquerda"><?php echo mascaraFoneFranquia($matriz['cel02socio']); ?></td>
      </tr>
      <tr>
        <td class="subtitulodireita">Operadora</td>
        <td class="campoesquerda">
			<?php 
				$sql_op_2 = "SELECT operadora_2 FROM cs2.franquia WHERE id = '{$_REQUEST['id']}'";
				$qry_ope_2 = mysql_query($sql_op_2,$con);
				@$operadora_2 = mysql_result($qry_ope_2,0,'operadora_2');				
				$sql_op_2 = "SELECT descricao FROM cs2.operadora WHERE id = '$operadora_2'";
				$qry_ope_2 = mysql_query($sql_op_2,$con);
				@$descricao = mysql_result($qry_ope_2,0,'descricao');
                                echo $descricao;
			?>
            </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Conta Corrente</td>
    <td colspan="3" class="subtitulopequeno"><textarea name="obs" cols="50" rows="3" disabled><?php echo $matriz['ctacte']; ?></textarea>	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Banco</td>
    <td colspan="3" class="subtitulopequeno">
    <?php
		$banco = trim($matriz['banco']);
                if ( $banco != '' ){
                    $sql = "select banco, nbanco from consulta.banco where banco='$banco'";
                    $qr_sql = mysql_query($sql,$con);
                    @$bco  = mysql_result($qr_sql,0,'banco');
                    @$nbanco = mysql_result($qr_sql,0,'nbanco');
                    echo "$bco - $nbanco";
                }
		?>    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Agencia</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['agencia']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Conta</td>
    <td colspan="3" class="subtitulopequeno">
		<?php 
		$tpconta = $matriz['tpconta'];
		if ($tpconta == 2) echo "Poupan&ccedil;a";
		else echo "Conta Corrente";
		echo " - ";
		echo $matriz['conta']; ?>    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Titular</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['titular']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">CPF Titular</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['cpftitular']; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Taxa de Implanta&ccedil;&atilde;o</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['tx_adesao']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Taxa do Pacote</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['tx_pacote']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Taxa de Software</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['tx_software']; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Gerente de Franquia Respons&aacute;vel</td>
    <td colspan="3" class="subtitulopequeno">
		<?php
        	$sql_gerente = "SELECT nome FROM gerente WHERE id = '{$matriz['id_gerente']}'";
			$qry_gerente = mysql_query($sql_gerente,$con);
			@$nome = mysql_result($qry_gerente,0,'nome');
                        echo $nome;
		?>
     </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data da Inaugura&ccedil;&atilde;o</td>
    <td colspan="3" class="subtitulopequeno">
	<?php 
	$data_abertura =  $matriz['data_abertura']; 
	$data_abertura = implode(preg_match("~\/~", $data_abertura) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_abertura) == 0 ? "-" : "/", $data_abertura)));
	echo $data_abertura;
	?>
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data de Apoio Local</td>
    <td colspan="3" class="subtitulopequeno"><?php 
	$data_apoio =  $matriz['data_apoio']; 
	$data_apoio = implode(preg_match("~\/~", $data_apoio) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_apoio) == 0 ? "-" : "/", $data_apoio)));
	echo $data_apoio;
	?></td>
  </tr>
  
   <tr>
       <td class="subtitulodireita">Quantidade de Contrato M&ecirc;s</td>
    <td colspan="3" class="subtitulopequeno"><?php echo $matriz['qtd_contrato_mes']; ?></td>
  </tr>
  
  <?php if($total_jornal > 0){?>
  <tr>
      <td colspan="5" class="titulo" align="center">Rela&ccedil;&atilde;o de Jornais / R&aacute;dios</td>
  </tr>
  <?php } ?>
  
  <?php 
  $cont=0;
  while($rs = mysql_fetch_array($qry_jornal)){
  $cont++;
  ?>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno" colspan="4">
    	<table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%"><?=$rs['cidade']?></td>               
    <td width="40%" align="right">UF:&nbsp;<?=strtoupper($rs['uf'])?></td>
    <td>&nbsp;</td>
            </tr>
        </table>
    </td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Fone 1</td>
    <td class="subtitulopequeno" colspan="4">
    	<table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="30%"><?=mascaraFoneFranquia($rs['fone_1'])?></td>
                <td width="30%" align="right">Fone 2:&nbsp;</td>
    <td width="30%"><?=mascaraFoneFranquia($rs['fone_2'])?></td>
    <td>&nbsp;</td>
            </tr>
        </table>
    </td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Contato</td>
    <td class="subtitulopequeno" colspan="4"><?=$rs['contato']?></td>
  </tr> 
  
  <tr>
      <td class="subtitulodireita">Nome do Jornal ou R&aacute;dio</td>
    <td class="subtitulopequeno" colspan="4"><?=$rs['jornal_radio']?></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Titular da Conta</td>
    <td class="subtitulopequeno" colspan="4"><?=$rs['titular_conta']?></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">CPF/CNPJ do Titular da Conta</td>
    <td class="subtitulopequeno" colspan="4"><?=$rs['cpf_cnpj']?></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Banco</td>
    <td class="subtitulopequeno" colspan="4"><?=$rs['banco']?></td>
  </tr> 
  
  <tr>
      <td class="subtitulodireita">Ag&ecirc;ncia</td>
    <td class="subtitulopequeno" colspan="4"><?=$rs['agencia']?></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Conta</td>
    <td class="subtitulopequeno" colspan="4"><?=$rs['conta']?></td>
  </tr>
  
  <tr><td colspan="4" class="titulo">&nbsp;</td></tr>
  
  <?php } ?>
  
  <?php if($total_jornal == 0){?>
  	<tr><td colspan="4" class="titulo">&nbsp;</td></tr>
  <?php } ?>
  
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr class="PAACDestaque">
    <td colspan="4">O login de acesso do franqueado  ser&aacute; <u><font color="#FF0000"><?php echo $matriz['id']; ?></font></u> e a senha <u><font color="#FF0000"><?php echo $matriz['senha']; ?></font></u></td>
  </tr>
  <tr class="PAACDestaque">
    <td colspan="4">A senha  para a &aacute;rea restita ser&aacute; <u><font color="#FF0000"><?php echo $matriz['senha_restrita']; ?></font></u></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>                               
  <tr align="center">
    <td colspan="4">
    <input name="incluir" type="button" value="Incluir novo Franqueado" onClick="novoCadastro()" style="cursor:pointer"/>
    &nbsp;&nbsp;&nbsp;
    <input name="alterar" type="button" value="Alterar os dados do Franqueado" onClick="alterarCadastro()" style="cursor:pointer"/></td>
  </tr>
</table>
  </form>
<?php
  $res = mysql_query ($comando, $con);
  $res = mysql_close ($con);
  ?>
</body>