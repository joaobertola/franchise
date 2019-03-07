<style>
	.divImagem{
		width: 95px;
		height: 95px;
		border: 1px solid #ccc;
		position: relative;
	}
	.btnFechar{
		width: 20px;
		height: 20px;
		position: absolute;
		content: url(icone-fechar.png);
		float: right;
		top: -10px;
		right: -10px;
	}
</style>

<script type="text/javascript">
/* Mascaras ER */
function xmascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("xexecmascara()",1)
}
function xexecmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que nao e digito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parenteses em volta dos dois primeiros digitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hifen entre o quarto e o quinto digitos
    return v;
}
function id( el ){
	return document.getElementById( el );
}
window.onload = function(){
	id('telefone').onkeypress = function(){
		xmascara( this, mtel );
	}
	id('fone3').onkeypress = function(){
		xmascara( this, mtel );
	}
	id('fone_res').onkeypress = function(){
		xmascara( this, mtel );
	}
	id('celular1').onkeypress = function(){
		xmascara( this, mtel );
	}
	id('celular2').onkeypress = function(){
		xmascara( this, mtel );
	}
}

function AlterarFoto(id_franquia,p_altura,p_largura){
	window.open('area_restrita/alterar_recortar_imagens.php?large_photo_exists=&id_franquia='+id_franquia, 'foto', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+750+',height='+700+',left='+0+', top='+0+',screenX='+0+',screenY='+0); 
}

function excluir_foto(idfoto){

 	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/excluir_foto_franquia.php&id_foto='+idfoto+'&id_franquia='+<?php echo $_GET['id'];?>;
	frm.submit();
	
}


function confirma(){

 	frm = document.form;
    frm.action = 'area_restrita/update_franqueado.php';
	frm.submit();
	
}

</script>

<?php
// require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

$id = $_GET['id'];

if ( $_REQUEST['acao'] == 3 ){
	echo "<script>alert('Foto Excluida com Sucesso!');</script>";
}

$sql     = "SELECT id, nome_foto FROM cs2.franquia_foto WHERE id_franquia = $id";
$qry_sql = mysql_query($sql,$con);
$link_foto = '';
if ( mysql_num_rows($qry_sql) > 0 ){
	$link_foto = "";
	while ( $reg_foto = mysql_fetch_array($qry_sql) ){
		$id_foto    = $reg_foto['id'];
		$link_foto .= "<img src=area_restrita/".$reg_foto['nome_foto'].">";
		$link_foto .= "<a href='#' onClick='excluir_foto($id_foto)'><img src='../img/icone-fechar.png'></a>";
	}
}else{
	$link_foto = "<img src=ranking/d_gera.php?idx=$id>";
}

$comando = "select * from franquia where id='$id'";
$res     = mysql_query ($comando, $con);
$matriz  = mysql_fetch_array($res);
$id		   	      = $matriz['id'];
$senha            = $matriz['senha'];
$senha_restrita   = $matriz['senha_restrita'];
$franquia	      = $matriz['fantasia'];
$razao            = $matriz['razaosoc'];
$cnpj             = $matriz['cpfcnpj'];
$endereco         = $matriz['endereco'];
$bairro           = $matriz['bairro'];
$cidade           = $matriz['cidade'];
$uf               = $matriz['uf'];
$cep              = $matriz['cep'];
$telefone         = $matriz['fone1'];
$fone_res         = $matriz['fone2'];
$fone3            = $matriz['fone3'];
$email            = $matriz['email'];
$nome_prop1       = $matriz['nom01socio'];
$cpf1             = $matriz['doc01socio'];
$celular1         = $matriz['cel01socio'];
$nome_prop2       = $matriz['nom02socio'];
$cpf2             = $matriz['doc02socio'];
$celular2         = $matriz['cel02socio'];
$ctacte		      = $matriz['ctacte'];
$banco		      = $matriz['banco'];
$agencia	      = $matriz['agencia'];
$tpconta	      = $matriz['tpconta'];
$conta		      = $matriz['conta'];
$titular	      = $matriz['titular'];
$cpftitular	      = $matriz['cpftitular'];
$data_abertura    = $matriz['data_abertura'];
$data_apoio	      = $matriz['data_apoio'];
$dt_cadastro      = $matriz['dt_cad'];
$tx_adesao        = $matriz['tx_adesao'];
$tx_adesao        = number_format($tx_adesao,2,',','.');

$tx_pacote        = $matriz['tx_pacote'];
$tx_pacote        = number_format($tx_pacote,2,',','.');

$tx_software      = $matriz['tx_software'];
$tx_software      = number_format($tx_software,2,',','.');

$id_gerente       = $matriz['id_gerente'];
$operadora_1      = $matriz['operadora_1'];
$operadora_2      = $matriz['operadora_2'];
$classificacao    = $matriz['classificacao'];
$qtd_contrato_mes = $matriz['qtd_contrato_mes'];
$sitfrq           = $matriz['sitfrq'];
$situacao_repasse = $matriz['situacao_repasse'];
$participacao_frq = $matriz['comissao_frqjr'];
$data_abertura    = implode(preg_match("~\/~", $data_abertura) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_abertura) == 0 ? "-" : "/", $data_abertura)));

$data_apoio = implode(preg_match("~\/~", $data_apoio) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_apoio) == 0 ? "-" : "/", $data_apoio)));

$dt_cadastro = implode(preg_match("~\/~", $dt_cadastro) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $dt_cadastro) == 0 ? "-" : "/", $dt_cadastro)));

//seleciona os gerentes de franquia
$sql_gerente = "SELECT id, nome FROM gerente 
				WHERE situacao = 'A' ORDER BY nome";
$qry_gerente = mysql_query ($sql_gerente, $con);

//seleciona os jornais
$sql_jornal = "SELECT * FROM cs2.franquia_relacao_jornal 
			   WHERE id_franquia = '{$_REQUEST['id']}'
			   ORDER BY id";
$qry_jornal = mysql_query ($sql_jornal, $con);
$contador = 1;
$grava_1 = 0;
$grava_2 = 0;
$grava_3 = 0;
while($rs_j = mysql_fetch_array($qry_jornal)){
	if($contador == 1){
		$grava_1 = 1;
		$id_1             = $rs_j['id'];
		$j_cidade1        = $rs_j['cidade'];
		$j_uf1            = $rs_j['uf'];
		$j_fone1          = $rs_j['fone_1'];
		$j_fone2          = $rs_j['fone_2'];
		$j_contato1       = $rs_j['contato'];
		$j_jornal_radio1  = $rs_j['jornal_radio'];
		$j_titular_conta1 = $rs_j['titular_conta'];
		$j_cpf_cnpj1      = $rs_j['cpf_cnpj'];
		$j_banco1         = $rs_j['banco'];
		$j_agencia1       = $rs_j['agencia'];
		$j_conta1         = $rs_j['conta'];
		$j_email1         = $rs_j['email'];
	}
	
	if($contador == 2){		
		$grava_2 = 1;
		$id_2             = $rs_j['id'];
		$j_cidade2        = $rs_j['cidade'];
		$j_uf2            = $rs_j['uf'];
		$j_fone3          = $rs_j['fone_1'];
		$j_fone4          = $rs_j['fone_2'];
		$j_contato2       = $rs_j['contato'];
		$j_jornal_radio2  = $rs_j['jornal_radio'];
		$j_titular_conta2 = $rs_j['titular_conta'];
		$j_cpf_cnpj2      = $rs_j['cpf_cnpj'];
		$j_banco2         = $rs_j['banco'];
		$j_agencia2       = $rs_j['agencia'];
		$j_conta2         = $rs_j['conta'];
		$j_email2         = $rs_j['email'];
	}
	
	if($contador == 3){
		$grava_3 = 1;
		$id_3             = $rs_j['id'];
		$j_cidade3        = $rs_j['cidade'];
		$j_uf3            = $rs_j['uf'];
		$j_fone5          = $rs_j['fone_1'];
		$j_fone6          = $rs_j['fone_2'];
		$j_contato3       = $rs_j['contato'];
		$j_jornal_radio3  = $rs_j['jornal_radio'];
		$j_titular_conta3 = $rs_j['titular_conta'];
		$j_cpf_cnpj3      = $rs_j['cpf_cnpj'];
		$j_banco3         = $rs_j['banco'];
		$j_agencia3       = $rs_j['agencia'];
		$j_conta3         = $rs_j['conta'];
		$j_email3         = $rs_j['email'];
	}
	$contador+=1;
}

function mascaraFoneFranquia($p_telefone){
     if ($p_telefone == '') {
	   return ('');	   
	 } else { 	   
	   $a = substr($p_telefone, 0,2);   
	   $b = substr($p_telefone, 2,4);   
	   $c = substr($p_telefone, 6,4);   
	   
	   $telefone_mascarado  = "(";
   	   $telefone_mascarado .= $a;
	   $telefone_mascarado .= ")&nbsp;";
	   $telefone_mascarado .= $b;
	   $telefone_mascarado .= "-";
	   $telefone_mascarado .= $c;
	   return ($telefone_mascarado);
	 }  
}

//seleciona as operadoras
$sql_operadora = "SELECT * FROM cs2.operadora 
				  WHERE situacao = 'A'
				  ORDER BY descricao";
$qry_operadora_1 = mysql_query ($sql_operadora, $con);
$qry_operadora_2 = mysql_query ($sql_operadora, $con);	
?>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>

<body>
	<form method="post" action="#" name="form">

        <input type="hidden" name="grava_1" value="<?=$grava_1?>">
        <input type="hidden" name="grava_2" value="<?=$grava_2?>">
        <input type="hidden" name="grava_3" value="<?=$grava_3?>">
        <input type="hidden" name="id_1" value="<?=$id_1?>">
        <input type="hidden" name="id_2" value="<?=$id_2?>">
        <input type="hidden" name="id_3" value="<?=$id_3?>">
        <input type="hidden" name="id_franquia" value="<?=$_REQUEST['id']?>">

<table border="0" align="center" width="650">
	<tr>
		<td colspan="4" class="titulo"><br>ALTERA&Ccedil;&Atilde;O DE FRANQUEADOS<br></td>
	</tr>
	<tr>
		<td class="subtitulodireita">&nbsp;</td>
		<td colspan="3" class="subtitulopequeno"><font color="#000000">(*) Preenchimento obrigat&oacute;rio</font></td>
	</tr>
	<tr>
		<td class="subtitulodireita">SITUAÇÃO da Franquia</td>
		<td width="167" class="subtitulopequeno">
			<?php if ( ($_SESSION['id'] == 163) or ($_SESSION['id']==46) or ($_SESSION['id']==59) ){ ?>
				<select name="sitfrq">
					<option value="0" <?php if ($matriz['sitfrq'] == "0") { echo "selected"; } ?> >ATIVA</option>
					<option value="1" <?php if ($matriz['sitfrq'] == "1") { echo "selected"; } ?> >BLOQUEADA</option>
					<option value="2" <?php if ($matriz['sitfrq'] == "2") { echo "selected"; } ?> >CANCELADA</option>
				</select>
                <?php } ?>
		</td>
    	<td width="157" class="subtitulodireita">SITUAÇÃO do Repasse</td>
		<td width="152" class="subtitulopequeno">
			<select name="sitrep">
				<option value="0" <?php if ($matriz['situacao_repasse'] == "0") { echo "selected"; } ?> >ATIVO</option>
				<option value="1" <?php if ($matriz['situacao_repasse'] == "1") { echo "selected"; } ?> >BLOQUEADO</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Data Cadastro</td>
	  <td class="subtitulopequeno">
        	<input name="dt_cad" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $dt_cadastro; ?>" size="10" maxlength="10" />
		</td>
<td width="157" class="subtitulodireita">Classifica&ccedil;&atilde;o</td>
		<td width="152" class="subtitulopequeno">
        	<select name="classificacao">
				<option value="M" <?php if ($matriz['classificacao'] == "M") { echo "selected"; } ?> >MASTER</option>
				<option value="J" <?php if ($matriz['classificacao'] == "J") { echo "selected"; } ?> >JUNIOR</option>
				<option value="X" <?php if ($matriz['classificacao'] == "X") { echo "selected"; } ?> >MICRO FRANQUIA</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="156" class="subtitulodireita">C&oacute;digo do Franqueado</td>
		<td colspan="3" class="campojustificado"><?php echo "&nbsp;&nbsp;$id"; ?>
			<input name="id" type="hidden" id="id" value="<?php echo "$id"; ?>" />
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Senha de acesso</td>
		<td colspan="3" class="subtitulopequeno"><input name="senha" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $senha; ?>" size="10" maxlength="6" /></td>
	</tr>

  <tr>
    <td class="subtitulodireita">Senha da &aacute;rea restrita </td>
    <td colspan="3" class="subtitulopequeno"><input name="senha_restrita" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $senha_restrita; ?>" size="10" maxlength="4" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nome da Franquia </td>
    <td colspan="3" class="subtitulopequeno"><input name="franquia" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $franquia; ?>" size="75" maxlength="80" />
      * </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Razão Social</td>
    <td colspan="3" class="subtitulopequeno"><input name="razao" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $razao; ?>" size="75" maxlength="80" />
      *</td>
  </tr>

  <tr>
    <td class="subtitulodireita">CNPJ</td>
    <td colspan="3" class="subtitulopequeno"><input name="cnpj" type="text" onKeyPress="soNumero();formatar('##.###.###/####-##', this)" value="<?php echo $cnpj; ?>" size="22" maxlength="18"  class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
*</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endereço</td>
    <td colspan="3" class="subtitulopequeno"><input name="endereco" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $endereco; ?>" size="75" maxlength="200" />
*</td>
    </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td colspan="3" class="subtitulopequeno"><input name="bairro" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $bairro; ?>" size="40" maxlength="200" />
 *</td>
    </tr>
  <tr>
    <td class="subtitulodireita">UF</td>
    <td colspan="3" class="subtitulopequeno"><input type="text" name="uf" size="4" maxlength="2" value="<?php echo $uf; ?>"  class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      * </td>
    </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td colspan="3" class="subtitulopequeno"><input type="text" name="cidade" size="40" maxlength="30" value="<?php echo $cidade; ?>" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
*</td>
    </tr>
  <tr>
    <td class="subtitulodireita">CEP</td>
    <td colspan="3" class="subtitulopequeno"><input name="cep" type="text" onKeyPress="formatar('##.###-###', this)" value="<?php echo $cep; ?>" size="12" maxlength="10" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      *</td>
    </tr>
  <tr>
    <td class="subtitulodireita">Telefone 1 </td>
    <td colspan="3" class="subtitulopequeno"><input name="telefone" id="telefone" type="text" value="<?php echo $telefone; ?>" size="25" maxlength="15" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      *</td>
    </tr>
  <tr>
    <td class="subtitulodireita">Telefone 2 </td>
    <td colspan="3" class="subtitulopequeno"><input name="fone3" id="fone3" type="text" value="<?php echo $fone3; ?>" size="25" maxlength="15" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/></td>
  </tr>  <tr>
    <td class="subtitulodireita">Telefone Residencial </td>
    <td colspan="3" class="subtitulopequeno"><input name="fone_res" id="fone_res" type="text" value="<?php echo $fone_res; ?>" size="25" maxlength="15" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/></td>
  </tr>

  <tr>
    <td class="subtitulodireita">E-mail</td>
    <td colspan="3" class="subtitulopequeno"><input name="email" type="text" value="<?php echo $email; ?>" size="25" maxlength="200" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
    </tr>

  <tr>
    <td class="subtitulodireita">Proprietário 1 </td>
    <td colspan="3" class="subtitulopequeno"><table border="0">
      <tr>
        <td class="subtitulodireita">Nome</td>
        <td class="campoesquerda"><input name="nome_prop1" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $nome_prop1; ?>" size="60" maxlength="100" /></td>
      </tr>
      <tr>
        <td class="subtitulodireita">CPF
          1</td>
        <td class="campoesquerda"><input name="cpf1" type="text" onKeyPress="formatar('###.###.###-##', this)" value="<?php echo $cpf1; ?>" size="17" maxlength="14" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
      </tr>
      <tr>
          <td class="subtitulodireita">Celular  </td>
		  <td class="campoesquerda"><input name="celular1" id="celular1"type="text" value="<?php echo $celular1; ?>" size="17" maxlength="15" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/></td>
      </tr>
      <tr>
        <td class="subtitulodireita">Operadora</td>
        <td class="campoesquerda"><select name="operadora_1" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" style="width:35%" class="boxnormal"/>
        		<option value="0"></option>
        	<?php while($rs_oper_1 = mysql_fetch_array($qry_operadora_1)){?>
        		<?php if($rs_oper_1['id'] == $operadora_1){?>
					<option value="<?=$rs_oper_1['id']?>" selected><?=$rs_oper_1['descricao']?></option>                
				<?php }else{?>
                	<option value="<?=$rs_oper_1['id']?>"><?=$rs_oper_1['descricao']?></option>
                <?php } ?>
            <?php } ?>
        </select>
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Propriet&aacute;rio 2</td>
    <td colspan="3" class="subtitulopequeno"><table border="0">
      <tr>
        <td class="subtitulodireita">Nome</td>
        <td class="campoesquerda"><input name="nome_prop2" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $nome_prop2; ?>" size="60" maxlength="200" /></td>
      </tr>
      <tr>
        <td class="subtitulodireita">CPF 2</td>
        <td class="campoesquerda"><input name="cpf2" type="text" onKeyPress="formatar('###.###.###-##', this)" value="<?php echo $cpf2; ?>" size="17" maxlength="14" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
      </tr>
      <tr>
        <td class="subtitulodireita">Celular </td>
        <td class="campoesquerda"><input name="celular2" type="text" id="celular2" value="<?php echo $celular2; ?>" size="17" maxlength="15" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/></td>
      </tr>
      <tr>
        <td class="subtitulodireita">Operadora</td>
        <td class="campoesquerda"><select name="operadora_2" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" style="width:35%" class="boxnormal"/>
        		<option value="0"></option>
        	<?php while($rs_oper_2 = mysql_fetch_array($qry_operadora_2)){?>
        		<?php if($rs_oper_2['id'] == $operadora_2){?>
					<option value="<?=$rs_oper_2['id']?>" selected><?=$rs_oper_2['descricao']?></option>                
				<?php }else{?>
                	<option value="<?=$rs_oper_2['id']?>"><?=$rs_oper_2['descricao']?></option>
                <?php } ?>
            <?php } ?>
        </select>
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Conta Corrente </td>
    <td colspan="3" class="subtitulopequeno"><textarea name="ctacte" cols="50" rows="3"><?php echo $ctacte; ?></textarea></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Gerente de Franqu&iacute;a Respons&aacute;vel</td>
    <td colspan="3" class="subtitulopequeno"><select name="id_gerente" id="gerente" class="boxnormal" style="width:25%">
      <option value="0">Selecione</option>
      <?php while($rs_gerente = mysql_fetch_array($qry_gerente)){?>
         <?php if($rs_gerente['id'] == $id_gerente){?>
	        <option value="<?=$rs_gerente['id']?>" selected="selected"><?=$rs_gerente['nome']?></option>
         <?php }else{?>
            <option value="<?=$rs_gerente['id']?>"><?=$rs_gerente['nome']?></option>
         <?php }?>    
      <?php } ?>
    </select>    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data da Inaugura&ccedil;&atilde;o</td>
    <td colspan="3" class="subtitulopequeno"><input type="text" name="data_abertura" id="data_abertura" onKeyPress="formatar('##/##/####', this)" value="<?php echo $data_abertura; ?>" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10"></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Data de Apoio Local</td>
    <td colspan="3" class="subtitulopequeno"><label>
      <input type="text" name="data_apoio" id="data_apoio" onKeyPress="formatar('##/##/####', this)" class="boxnormal" onFocus="this.className='boxover'" value="<?php echo $data_apoio; ?>" onBlur="this.className='boxnormal'" maxlength="10">
    </label></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Quantidade de Contrato Mês</td>
    <td colspan="3" class="subtitulopequeno"><label>
      <input type="text" name="qtd_contrato_mes" class="boxnormal" onFocus="this.className='boxover'" value="<?php echo $qtd_contrato_mes; ?>" onBlur="this.className='boxnormal'" maxlength="3">
    </label></td>
  </tr>
  
  	<?php if ( $classificacao == 'X' ){ ?>
	<tr>
		<td class="subtitulodireita">Participa&ccedil;&atilde;o Micro-Franquia</td>
		<td colspan="3" class="subtitulopequeno">
			<input type="text" name="participacao_frq" class="boxnormal" onFocus="this.className='boxover'" value="<?php echo $participacao_frq*1; ?>" onBlur="this.className='boxnormal'" maxlength="5"> %
		</td>
	</tr>
    <?php } ?>
  
  </tr>
    <tr>
    <td class="subtitulodireita">Foto</td>
    <td colspan="2" class="subtitulopequeno"><?php echo $link_foto; ?></td>
    <td class="subtitulopequeno" style="text-align:left"><a href="#" onClick="AlterarFoto(<?php echo $id; ?>,95,64);">Inserir/Alterar Foto</a></td>
  </tr>
  <tr>
    <td colspan="4" class="titulo">Dados Bancarios</td>
    </tr>
  <tr>
    <td class="subtitulodireita">Banco</td>
    <td colspan="3" class="subtitulopequeno"><select name="banco" class="boxnormal">
      <option value="0">:: Escolha o Banco ::</option>
      <?php
		$sql = "select * from consulta.banco order by nbanco";
		$resposta = mysql_query($sql,$con);
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
    <td colspan="3" class="subtitulopequeno"><input name="agencia" type="text" size="17" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $agencia; ?>" class="boxnormal"/></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Tipo de Conta</td>
    <td colspan="3" class="subtitulopequeno">
    	<input type="radio" name="tpconta" value="1" <?php if ($tpconta == 1) echo "checked"; ?>>Conta Corrente
        <input type="radio" name="tpconta" value="2" <?php if ($tpconta == 2) echo "checked"; ?>>Poupan&ccedil;a    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Conta</td>
    <td colspan="3" class="subtitulopequeno"><input name="conta" type="text" size="17" maxlength="20" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $conta; ?>" class="boxnormal"/></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Titular da Conta</td>
    <td colspan="3" class="subtitulopequeno"><input class="boxnormal" name="titular" type="text" size="75" maxlength="40" onFocus="this.className='boxover'"onBlur="maiusculo(this); this.className='boxnormal'" value="<?php echo $titular; ?>"/></td>
  </tr>
  <tr>
    <td class="subtitulodireita">CPF do Titular</td>
    <td colspan="3" class="subtitulopequeno"><input name="cpftitular" type="text" size="17" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $cpftitular; ?>" class="boxnormal"/></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Taxa de Implantação</td>
    <td class="subtitulopequeno" colspan="4"><input name="tx_adesao" value="<?php echo $tx_adesao; ?>" type="text" size="17" maxlength="11" onKeydown="FormataValor(this,20,event,2)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Taxa do Pacote</td>
    <td class="subtitulopequeno" colspan="4"><input name="tx_pacote" value="<?php echo $tx_pacote; ?>" type="text" size="17" maxlength="11" onKeydown="FormataValor(this,20,event,2)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Taxa de Software</td>
    <td class="subtitulopequeno" colspan="4"><input name="tx_software"  value="<?php echo $tx_software; ?>" type="text" size="17" maxlength="11" onKeydown="FormataValor(this,20,event,2)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td colspan="5" class="titulo" align="center">Relação de Jornais / Rádios</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno" colspan="4">
    	<table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%"><input name="j_cidade1" value="<?=$j_cidade1?>" type="text" style="width:90%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>               
    <td width="40%" align="right">UF&nbsp;<input name="j_uf1" value="<?=$j_uf1?>" type="text" style="width:40%" maxlength="2" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>&nbsp;&nbsp;</td>
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
            	<td width="30%"><input name="j_fone1" value="<?=$j_fone1?>" type="text" style="width:80%" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="formatar('##-####-####', this); soNumero()" class="boxnormal"/></td>
                <td width="30%" align="right">Fone 2&nbsp;</td>
    <td width="30%"><input name="j_fone2" value="<?=$j_fone2?>" type="text" style="width:93%" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="formatar('##-####-####', this); soNumero()" class="boxnormal"/></td>
    <td>&nbsp;</td>
            </tr>
        </table>
    </td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Contato</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_contato1" value="<?=$j_contato1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Nome do Jornal ou Rádio</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_jornal_radio1" value="<?=$j_jornal_radio1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Titular da Conta</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_titular_conta1" value="<?=$j_titular_conta1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">CPF/CNPJ do Titular da Conta</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_cpf_cnpj1" value="<?=$j_cpf_cnpj1?>" type="text" style="width:88%" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Banco</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_banco1" value="<?=$j_banco1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Agência</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_agencia1" value="<?=$j_agencia1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Conta</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_conta1" value="<?=$j_conta1?>" type="text" style="width:88%" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">E-mail</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_email1" value="<?=$j_email1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr>
  
  <tr><td colspan="4" class="titulo">&nbsp;</td></tr>

  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno" colspan="4">
    	<table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%"><input name="j_cidade2" value="<?=$j_cidade2?>" type="text" style="width:90%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>               
    <td width="40%" align="right">UF&nbsp;<input name="j_uf2" value="<?=$j_uf2?>" type="text" style="width:40%" maxlength="2" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>&nbsp;&nbsp;</td>
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
            	<td width="30%"><input name="j_fone3" value="<?=$j_fone3?>" type="text" style="width:80%" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="formatar('##-####-####', this); soNumero()" class="boxnormal"/></td>
                <td width="30%" align="right">Fone 2&nbsp;</td>
    <td width="30%"><input name="j_fone4" value="<?=$j_fone4?>" type="text" style="width:93%" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="formatar('##-####-####', this); soNumero()" class="boxnormal"/></td>
    <td>&nbsp;</td>
            </tr>
        </table>
    </td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Contato</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_contato2" value="<?=$j_contato2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Nome do Jornal ou Rádio</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_jornal_radio2" value="<?=$j_jornal_radio2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Titular da Conta</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_titular_conta2" value="<?=$j_titular_conta2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">CPF/CNPJ do Titular da Conta</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_cpf_cnpj2" value="<?=$j_cpf_cnpj2?>" type="text" style="width:88%" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Banco</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_banco2" value="<?=$j_banco2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Agência</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_agencia2" value="<?=$j_agencia2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Conta</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_conta2" value="<?=$j_conta2?>" type="text" style="width:88%" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">E-mail</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_email2" value="<?=$j_email2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr>
  
  <tr><td colspan="4" class="titulo">&nbsp;</td></tr>

  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno" colspan="4">
    	<table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%"><input name="j_cidade3" value="<?=$j_cidade3?>" type="text" style="width:90%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>               
    <td width="40%" align="right">UF&nbsp;<input name="j_uf3" value="<?=$j_uf3?>" type="text" style="width:40%" maxlength="2" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>&nbsp;&nbsp;</td>
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
            	<td width="30%"><input name="j_fone5" value="<?=$j_fone5?>" type="text" style="width:80%" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="formatar('##-####-####', this); soNumero()" class="boxnormal"/></td>
                <td width="30%" align="right">Fone 2&nbsp;</td>
    <td width="30%"><input name="j_fone6" value="<?=$j_fone6?>" type="text" style="width:93%" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="formatar('##-####-####', this); soNumero()" class="boxnormal"/></td>
    <td>&nbsp;</td>
            </tr>
        </table>
    </td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Contato</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_contato3" value="<?=$j_contato3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Nome do Jornal ou Rádio</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_jornal_radio3" value="<?=$j_jornal_radio3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Titular da Conta</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_titular_conta3" value="<?=$j_titular_conta3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">CPF/CNPJ do Titular da Conta</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_cpf_cnpj3" value="<?=$j_cpf_cnpj3?>" type="text" style="width:88%" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Banco</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_banco3" value="<?=$j_banco3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"class="boxnormal" /></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Agência</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_agencia3" value="<?=$j_agencia3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Conta</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_conta3" value="<?=$j_conta3?>" type="text" style="width:88%" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">E-mail</td>
    <td class="subtitulopequeno" colspan="4"><input name="j_email3" value="<?=$j_email3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
  </tr>
  
  <tr><td colspan="4" class="titulo">&nbsp;</td></tr>
    
</table>
<table align="center">
        <tr align="center">
			<td>
          		<input name="alterar" type="button" value=" Modificar " onClick="confirma()" />
                &nbsp;&nbsp;&nbsp;&nbsp;
          		<input type="button" onClick="javascript: history.back();" value="       Voltar       " style="cursor:pointer"/></td>
		</tr>
  </table>
</form>
</body>