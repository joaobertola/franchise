<?php
session_start();

$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];

$id_logado = $_SESSION['id'];
$id = $_GET['id'];

$comando = "select * from franquia where id='$id'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$id			 = $matriz['id'];
$senha       = $matriz['senha'];
$senha_restrita = $matriz['senha_restrita'];
$franquia	 = $matriz['fantasia'];
$razao       = $matriz['razaosoc'];
$cnpj        = $matriz['cpfcnpj'];
$endereco    = $matriz['endereco'];
$numero    = $matriz['numero'];
$complemento    = $matriz['complemento'];
$bairro      = $matriz['bairro'];
$cidade      = $matriz['cidade'];
$uf          = $matriz['uf'];
$cep         = $matriz['cep'];
$telefone    = $matriz['fone1'];
$fone_res    = $matriz['fone2'];
$email       = $matriz['email'];
$nome_prop1  = $matriz['nom01socio'];
$cpf1        = $matriz['doc01socio'];
$celular1    = $matriz['cel01socio'];
$nome_prop2  = $matriz['nom02socio'];
$cpf2        = $matriz['doc02socio'];
$celular2    = $matriz['cel02socio'];
$ctacte		 = $matriz['ctacte'];
$banco		 = $matriz['banco'];
$agencia	 = $matriz['agencia'];
$tpconta	 = $matriz['tpconta'];
$conta		 = $matriz['conta'];
$titular	 = $matriz['titular'];
$cpftitular	 = $matriz['cpftitular'];
$data_abertura = $matriz['data_abertura'];
$data_apoio	 = $matriz['data_apoio'];
$dt_cadastro = $matriz['dt_cad'];
$tx_adesao = $matriz['tx_adesao'];
$tx_adesao = str_replace('.',',',$tx_adesao);
$tx_pacote = $matriz['tx_pacote'];
$tx_pacote = str_replace('.',',',$tx_pacote);
$tx_software = $matriz['tx_software'];
$tx_software = str_replace('.',',',$tx_software);
$gerente    = $matriz['gerente'];
$comissao    = $matriz['comissao_frqjr'];
$habilitar_ranking = $matriz['habilitar_ranking'];

$data_abertura = implode(preg_match("~\/~", $data_abertura) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_abertura) == 0 ? "-" : "/", $data_abertura)));

$data_apoio = implode(preg_match("~\/~", $data_apoio) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_apoio) == 0 ? "-" : "/", $data_apoio)));

$dt_cadastro = implode(preg_match("~\/~", $dt_cadastro) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $dt_cadastro) == 0 ? "-" : "/", $dt_cadastro)));

?>
<script src="../../js/funcoes.js"></script>
<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>
<script type="text/javascript">
(function($){
	// call setMask function on the document.ready event
	$(
                function(){
                        $('input:text').setMask();
                }
	);
})(jQuery);

function retorna(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=franqueado_jr/rel_franqueado_jr_tela.php';
	frm.submit();
} 

function atualiza(){
 	frm = document.form;
    frm.action = 'franqueado_jr/atualiza_franqueado_jr.php';
	frm.submit();
} 
</script>

<body>
<form method="post" action="#" name="form">
<table border="0" align="center" width="650">
  <tr>
    <td colspan="4" class="titulo"><br>
    ALTERA&Ccedil;&Atilde;O DE FRANQUEADO JUNIOR<br><br></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno" colspan="3"><font color="#000000">(*) Preenchimento obrigat&oacute;rio</font></td>
    </tr>
  <?php
  if ( $id_logado == 163 ){ ?>
    <tr>
          <td class="subtitulodireita">Data Cadastro</td>
          <td class="subtitulopequeno"><input name="dt_cad" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $dt_cadastro; ?>" size="10" maxlength="10" /></td>
          <td class="subtitulodireita">Habilitar Ranking</td>
          <td class="subtitulopequeno">
              <select name="habilitar_ranking">
                  <option value="S" <?php if ( $habilitar_ranking == 'S' ) echo 'selected';?>>SIM</option>
                  <option value="N" <?php if ( $habilitar_ranking == 'N' ) echo 'selected';?>>NAO</option>
              </select>
          </td>
    </tr>
    <?php }else{ ?>
      <tr>
          <td class="subtitulodireita">Data Cadastro</td>
          <td class="subtitulopequeno" colspan="3"><input name="dt_cad" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $dt_cadastro; ?>" size="10" maxlength="10" /></td>
    </tr>
    <?php
    }
    ?>
  <tr>
    <td width="156" class="subtitulodireita">C&oacute;digo do Franqueado</td>
    <td class="campojustificado" colspan="3"><?php echo "&nbsp;&nbsp;$id"; ?>
      <input name="id" type="hidden" id="id" value="<?php echo "$id"; ?>" /></td>
   </tr>

  <tr>
    <td class="subtitulodireita">Senha de acesso</td>
    <td class="subtitulopequeno" colspan="3"><input name="senha" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $senha; ?>" size="10" maxlength="6" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nome do Franqueado</td>
    <td class="subtitulopequeno" colspan="3"><input name="franquia" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $franquia; ?>" size="75" maxlength="80" />
      * </td>
  </tr>

  <tr>
    <td class="subtitulodireita">CPF/CNPJ</td>
    <td class="subtitulopequeno" colspan="3"><input name="cnpj" type="text" onKeyPress="soNumero();formatar('##.###.###/####-##', this)" value="<?php echo $cnpj; ?>" size="22" maxlength="18"  class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      *</td>
  </tr>
  <tr>
      <td class="subtitulodireita">Endere&ccedil;o</td>
    <td class="subtitulopequeno" colspan="3"><input name="endereco" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $endereco; ?>" size="75" maxlength="200" />
*</td>
    </tr>

  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td class="subtitulopequeno" colspan="3"><input name="bairro" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $bairro; ?>" size="40" maxlength="200" />
 *</td>
    </tr>
  <tr>
    <td class="subtitulodireita">UF</td>
    <td class="subtitulopequeno" colspan="3"><input type="text" name="uf" size="4" maxlength="2" value="<?php echo $uf; ?>"  class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      * </td>
    </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno" colspan="3"><input type="text" name="cidade" size="40" maxlength="30" value="<?php echo $cidade; ?>" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
*</td>
    </tr>
  <tr>
    <td class="subtitulodireita">CEP</td>
    <td class="subtitulopequeno" colspan="3"><input name="cep" type="text" onKeyPress="formatar('##.###-###', this)" value="<?php echo $cep; ?>" size="12" maxlength="10" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      *</td>
    </tr>
  <tr>
    <td class="subtitulodireita">Telefone</td>
    <td class="subtitulopequeno" colspan="3"><input name="telefone" type="text" onKeyPress="formatar('##-####-####', this)" value="<?php echo $telefone; ?>" size="25" maxlength="12" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      *</td>
    </tr>

  <tr>
    <td class="subtitulodireita">Telefone
      Residencial</td>
    <td class="subtitulopequeno" colspan="3"><input name="fone_res" type="text" onKeyPress="formatar('##-####-####', this)" value="<?php echo $fone_res; ?>" size="25" maxlength="12" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/></td>
  </tr>

  <tr>
    <td class="subtitulodireita">E-mail</td>
    <td class="subtitulopequeno" colspan="3"><input name="email" type="text" value="<?php echo $email; ?>" size="25" maxlength="200" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
    </tr>
  </tr>
  <tr>
    <td colspan="4" class="titulo">Dados Banc&aacute;rios</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Comiss&atilde;o Franqueado JUNIOR</td>
    <td class="subtitulopequeno" colspan="4">
      <input name="comissao" type="text" size="10" maxlength="5" onKeyPress="soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $comissao; ?>" />
      %
    </td>
  <tr>
  <tr>
    <td class="subtitulodireita">Banco</td>
    <td class="subtitulopequeno" colspan="3"><select name="banco" class="boxnormal">
      <option value="0">:: Escolha o Banco ::</option>
      <?php
		$sql = "select * from consulta.banco order by nbanco";
		$resposta = mysql_query($sql);
		while ($array = mysql_fetch_array($resposta)) {
			$bco  = $array["banco"];
			$nbanco = $array["nbanco"];
			echo "<option value=\"$bco\"";
			if ($bco==$banco) echo "selected";
			echo ">$bco - $nbanco</option>\n";
		}
		?>
    </select></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Agencia</td>
    <td class="subtitulopequeno" colspan="3"><input name="agencia" type="text" size="17" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $agencia; ?>" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Tipo de Conta</td>
    <td class="subtitulopequeno" colspan="3">
    	<input type="radio" name="tpconta" value="1" <?php if ($tpconta == 1) echo "checked"; ?>>Conta Corrente
        <input type="radio" name="tpconta" value="2" <?php if ($tpconta == 2) echo "checked"; ?>>Poupan&ccedil;a    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Conta</td>
    <td class="subtitulopequeno" colspan="3"><input name="conta" type="text" size="17" maxlength="11" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $conta; ?>" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Titular da Conta</td>
    <td class="subtitulopequeno" colspan="3"><input class="boxnormal" name="titular" type="text" size="75" maxlength="40" onFocus="this.className='boxover'"onBlur="maiusculo(this); this.className='boxnormal'" value="<?php echo $titular; ?>" /></td>
  </tr>
  
  
  <tr>
    <td class="subtitulodireita">CPF do Titular</td>
    <td class="subtitulopequeno" colspan="3"><input name="cpftitular" type="text" size="17" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $cpftitular; ?>" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Taxa de Implantacao</td>
    <td class="subtitulopequeno" colspan="4"><input name="tx_adesao" value="<?php echo $tx_adesao; ?>" alt="decimal" type="text" size="17" maxlength="12" onKeydown="FormataValor(this,20,event,2)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Taxa do Pacote</td>
    <td class="subtitulopequeno" colspan="4"><input name="tx_pacote" value="<?php echo $tx_pacote; ?>" alt="decimal" type="text" size="17" maxlength="12" onKeydown="FormataValor(this,20,event,2)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Taxa de Software</td>
    <td class="subtitulopequeno" colspan="4"><input name="tx_software"  value="<?php echo $tx_software; ?>" alt="decimal" type="text" size="17" maxlength="12" onKeydown="FormataValor(this,20,event,2)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr> 
  <tr>
    <td colspan="4" class="titulo">&nbsp;</td>
  </tr>
</table>
<table align="center">
        <tr align="center">
          <td><input name="Modificar" type="button" value="     Modificar    " onClick="atualiza()" /></td>
		  <td><input name="Voltar" type="button" value="       Voltar       " onClick="retorna()" /></td>
		</tr>
  </table>
</form>
</body>