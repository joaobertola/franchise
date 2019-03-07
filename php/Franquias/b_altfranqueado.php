<style>
.divImagem{
	width: 65px;
	height: 95px;
	border: 1px solid #ccc;
	position: relative;
	margin: 10px;
}
.btnFechar{
	width: 20px;
	height: 20px;
	position: absolute;
	content: url(../img/icone-fechar.png);
	float: right;
	top: -10px;
	right: -10px;
	
}
</style>
<script type="text/javascript">
/* M�scaras ER */
function xmascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("xexecmascara()",1)
}
function xexecmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que n�o � d�gito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca par�nteses em volta dos dois primeiros d�gitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca h�fen entre o quarto e o quinto d�gitos
    return v;
}
function id( el ){
	return document.getElementById( el );
}

function AlterarFoto(id_franquia,p_altura,p_largura){
	window.open('area_restrita/alterar_recortar_imagens.php?id_franquia='+id_franquia, 'foto', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+750+',height='+700+',left='+0+', top='+0+',screenX='+0+',screenY='+0); 
}

window.onload = function(){
	id('celular1').onkeypress = function(){
		xmascara( this, mtel );
	}
	id('celular2').onkeypress = function(){
		xmascara( this, mtel );
	}
}
</script>
<link rel="stylesheet" href="../../franquias/css/font-glyphicons.css" type="text/css">
<?php
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a") && ($tipo != "d") && ($tipo != "c")) exit;
  
$go = $_POST['go'];
$id = $_POST['id'];
if (empty($go)) $id = $_GET['id'];

if (empty($go)) {
//pagina inicial
$comando = "select * from cs2.franquia where id='$id'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$id			 = $matriz['id'];
$franquia	 = $matriz['fantasia'];
$razao       = $matriz['razaosoc'];
$cnpj        = $matriz['cpfcnpj'];
$endereco    = $matriz['endereco'];
$bairro      = $matriz['bairro'];
$cidade      = $matriz['cidade'];
$uf          = $matriz['uf'];
$cep         = $matriz['cep'];
$telefone    = $matriz['fone1'];
$fone_res    = $matriz['fone2'];
$fone3       = $matriz['fone3'];
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
$gerente	 = $matriz['gerente'];
$data_abertura = $matriz['data_abertura'];
$data_apoio	 = $matriz['data_apoio'];
$id_gerente	 = $matriz['id_gerente'];
$operadora_1 = $matriz['operadora_1'];
$operadora_2 = $matriz['operadora_2'];
$qtd_contrato_mes = $matriz['qtd_contrato_mes'];

$data_abertura = implode(preg_match("~\/~", $data_abertura) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_abertura) == 0 ? "-" : "/", $data_abertura)));

$data_apoio = implode(preg_match("~\/~", $data_apoio) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_apoio) == 0 ? "-" : "/", $data_apoio)));

//seleciona os gerntes de franquia
$sql_gerente = "SELECT id, nome FROM gerente WHERE situacao = 'A' ORDER BY nome";
$qry_gerente = mysql_query ($sql_gerente, $con);

if($tipo != "a"){
	$desabilita = " disabled='disabled' ";
}else{
	unset($desabilita);
}

// Verifica se tem foto em arquivo
$sql     = "SELECT nome_foto FROM cs2.franquia_foto WHERE id_franquia = $id";
$qry_sql = mysql_query($sql,$con);		

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
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="grava_1" value="<?=$grava_1?>">
<input type="hidden" name="grava_2" value="<?=$grava_2?>">
<input type="hidden" name="grava_3" value="<?=$grava_3?>">
<input type="hidden" name="id_1" value="<?=$id_1?>">
<input type="hidden" name="id_2" value="<?=$id_2?>">
<input type="hidden" name="id_3" value="<?=$id_3?>">
<input type="hidden" name="id_franquia" value="<?=$_REQUEST['id']?>">
<table border="0" align="center" width="650">
	<tr>
		<td colspan="3" class="titulo">ALTERA&Ccedil;&Atilde;O DE FRANQUEADOS
			<input name="id" type="hidden" id="id" value="<?php echo "$id"; ?>"  />
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">&nbsp;</td>
		<td class="subtitulopequeno" colspan="2">
			<font color="#000000">(*) Preenchimento obrigat&oacute;rio</font>
		</td>
	</tr>
	<tr>
		<td width="156" class="subtitulodireita">
			C&oacute;digo do Franqueado
		</td>
		<td class="campojustificado" colspan="2">
			&nbsp;&nbsp;<strong><?php echo $matriz['usuario']; ?></strong>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Nome da Franquia </td>
		<td class="subtitulopequeno" colspan="2">
			<input name="franquia" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $franquia; ?>" size="75" maxlength="80" />* 
        </td>
	</tr>
	<tr>
		<td class="subtitulodireita">Raz&atilde;o Social</td>
		<td class="subtitulopequeno" colspan="2">
			<input name="razao" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $razao; ?>" size="75" maxlength="80" />*
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">CNPJ</td>
		<td class="subtitulopequeno" colspan="2">
			<?php if($_SESSION['ss_tipo'] == "a"){?>
				<input name="cnpj" type="text" onKeyPress="soNumero();formatar('##.###.###/####-##', this)" value="<?php echo $cnpj; ?>" size="22" maxlength="18"  class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
			<?php }else {?>
				<?php echo $cnpj; ?>
				<input name="cnpj" type="hidden" onKeyPress="soNumero();formatar('##.###.###/####-##', this)" value="<?php echo $cnpj; ?>" size="22" maxlength="18"  class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
			<?php } ?></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Endere&ccedil;o</td>
		<td class="subtitulopequeno" colspan="2">
			<input name="endereco" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $endereco; ?>" size="75" maxlength="200" />*
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Bairro</td>
		<td class="subtitulopequeno" colspan="2">
			<input name="bairro" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $bairro; ?>" size="40" maxlength="200" />*
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">UF</td>
		<td class="subtitulopequeno" colspan="2">
        	<input type="text" name="uf" size="4" maxlength="2" value="<?php echo $uf; ?>"  class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
		* </td>
	</tr>
	<tr>
		<td class="subtitulodireita">Cidade</td>
		<td class="subtitulopequeno" colspan="2">
        	<input type="text" name="cidade" size="40" maxlength="30" value="<?php echo $cidade; ?>" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
		*</td>
	</tr>
	<tr>
		<td class="subtitulodireita">CEP</td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="cep" type="text" value="<?php echo $cep; ?>" size="15" maxlength="10" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
		*</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Telefone 1 </td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="telefone" type="text" value="<?php echo $telefone; ?>" size="25" maxlength="15" onKeyPress="soNumero()" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
		*</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Telefone 2 </td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="fone3" type="text" value="<?php echo $fone3; ?>" size="25" maxlength="15" onKeyPress="soNumero()" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Telefone Residencial </td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="fone_res" type="text"  value="<?php echo $fone_res; ?>" size="25" maxlength="15" onKeyPress="soNumero()" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">E-mail</td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="email" type="text" value="<?php echo $email; ?>" size="25" maxlength="200" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Propriet&aacute;rio 1 </td>
		<td class="subtitulopequeno" colspan="2">
        	<table border="0">
				<tr>
					<td class="subtitulodireita">Nome</td>
					<td class="campoesquerda">
                    	<input name="nome_prop1" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $nome_prop1; ?>" size="60" maxlength="100" />
					</td>
				</tr>
				<tr>
					<td class="subtitulodireita">CPF 1</td>
					<td class="campoesquerda">
                    	<input name="cpf1" type="text" onKeyPress="formatar('###.###.###-##', this); soNumero()" value="<?php echo $cpf1; ?>" size="17" maxlength="14" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
                    </td>
				</tr>
				<tr>
					<td class="subtitulodireita">Celular  </td>
					<td class="campoesquerda">
                    	<input name="celular1" id="celular1" type="text" value="<?php echo $celular1; ?>" size="25" maxlength="15" class="boxnormal" onKeyPress="soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
                    </td>
				</tr>
				<tr>
					<td class="subtitulodireita">Operadora</td>
					<td class="campoesquerda">
						<select name="operadora_1" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" style="width:35%" class="boxnormal"/>
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
			</table>
		</td>
	</tr>
    <tr>
	    <td class="subtitulodireita">Propriet&aacute;rio 2</td>
    	<td class="subtitulopequeno"  colspan="2">
            <table border="0">
                <tr>
                    <td class="subtitulodireita">Nome</td>
                    <td class="campoesquerda">
                    	<input name="nome_prop2" type="text" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $nome_prop2; ?>" size="60" maxlength="200" />
					</td>
                </tr>
                <tr>
                    <td class="subtitulodireita">CPF 2</td>
                    <td class="campoesquerda">
                    	<input name="cpf2" type="text" onKeyPress="formatar('###.###.###-##', this); soNumero()" value="<?php echo $cpf2; ?>" size="17" maxlength="14" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
					</td>
                </tr>
                <tr>
                    <td class="subtitulodireita">Celular </td>
                    <td class="campoesquerda">
                    	<input type="text" name="celular2" id="celular2"  value="<?php echo $celular2; ?>" size="25" maxlength="15" onKeyPress="soNumero()" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>
					</td>
                </tr>
                <tr>
                    <td class="subtitulodireita">Operadora</td>
                    <td class="campoesquerda">
                        <select name="operadora_2" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" style="width:35%" class="boxnormal"/>
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
            </table>
		</td>
    </tr>
    <tr>
	    <td class="subtitulodireita">Conta Corrente </td>
	    <td class="subtitulopequeno" colspan="2">
        	<textarea name="ctacte" cols="70" rows="3" class="boxover"><?php echo $ctacte; ?></textarea>
		</td>
    </tr>
    <tr>
	    <td class="subtitulodireita">Gerente de Franqu&iacute;a Respons&aacute;vel</td>
	    <td class="subtitulopequeno"  colspan="2">
		    <select name="id_gerente" id="gerente" class="boxnormal" style="width:25%" <?=$desabilita?>>
			    <option value="0">Selecione</option>
			    <?php while($rs_gerente = mysql_fetch_array($qry_gerente)){?>
				    <?php if($rs_gerente['id'] == $id_gerente){?>
					    <option value="<?=$rs_gerente['id']?>" selected="selected"><?=$rs_gerente['nome']?></option>
				    <?php }else{?>
					    <option value="<?=$rs_gerente['id']?>"><?=$rs_gerente['nome']?></option>
				    <?php }?>    
			    <?php } ?>
		    </select>
		</td>
    </tr>
    <tr>
	    <td class="subtitulodireita">Data da Inaugura&ccedil;&atilde;o</td>
	    <td class="subtitulopequeno"  colspan="2">
        	<input type="text" name="data_abertura" id="data_abertura" onKeyPress="formatar('##/##/####', this)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10" value="<?php echo $data_abertura; ?>">
		</td>
    </tr>
    <tr>
	    <td class="subtitulodireita">Data de Apoio Local</td>
	    <td class="subtitulopequeno"  colspan="2">
        	<label>
		    	<input type="text" name="data_apoio" id="data_apoio" onKeyPress="formatar('##/##/####', this)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10" value="<?php echo $data_apoio; ?>">
		    </label>
		</td>
    </tr>
    <tr>
	    <td class="subtitulodireita">Meta do M&ecirc;s</td>
	    <td class="subtitulopequeno" colspan="2">
        	<label>
		    	<input type="text" name="qtd_contrato_mes" class="boxnormal" onFocus="this.className='boxover'" value="<?php echo $qtd_contrato_mes; ?>" onBlur="this.className='boxnormal'" maxlength="3">
		    </label>
		</td>
    </tr>

    <tr>
	    <td class="subtitulodireita">Foto</td>
	    <td class="subtitulopequeno">
        	<div class="divImagem">
        	<?php
				if ( mysql_num_rows($qry_sql) > 0 ){
					while ( $reg_foto = mysql_fetch_array($qry_sql) ){
						$link_foto = $reg_foto['nome_foto'];
						echo "<img src='area_restrita/$link_foto' height='95px' >";
					}
				}else 
					echo "<img src='ranking/d_gera.php?idx=$id' height='95px'>";
			?>
            	<a href="#"><span class="btnFechar"></span></a>
            </div>
        </td>
	    <td class="subtitulopequeno" >
			<a href="#" onClick="AlterarFoto(<?php echo $id; ?>,95,64);">Inserir/Alterar Foto</a>
		</td>
    </tr>
    
    <tr>
    	<td colspan="3" class="titulo" align="center">Relação de Jornais / Rádios</td>
    </tr>
    <tr>
    	<td class="subtitulodireita">Cidade</td>
    	<td class="subtitulopequeno" colspan="2">
    		<table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
			    <tr>
				    <td width="50%">
                    	<input name="j_cidade1" value="<?=$j_cidade1?>" type="text" style="width:90%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
                    </td>
				    <td width="40%" align="right">UF&nbsp;
                    	<input name="j_uf1" value="<?=$j_uf1?>" type="text" style="width:40%" maxlength="2" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>&nbsp;&nbsp;
					</td>
				    <td>&nbsp;</td>
			    </tr>
		    </table>
		</td>
    </tr> 
    <tr>
    	<td class="subtitulodireita">Fone 1</td>
	    <td class="subtitulopequeno" colspan="2">
		    <table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
			    <tr>
				    <td width="30%">
                    	<input name="j_fone1" value="<?=$j_fone1?>" type="text" style="width:80%" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="soNumero()" class="boxnormal"/>
					</td>
				    <td width="30%" align="right">Fone 2&nbsp;</td>
    				<td width="30%">
                    	<input name="j_fone2" value="<?=$j_fone2?>" type="text" style="width:93%" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="soNumero()" class="boxnormal"/>
					</td>
				    <td>&nbsp;</td>
			    </tr>
		    </table>
		</td>
    </tr> 
    <tr>
	    <td class="subtitulodireita">Contato</td>
    	<td class="subtitulopequeno" colspan="2">
        	<input name="j_contato1" value="<?=$j_contato1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr> 
    <tr>
		<td class="subtitulodireita">Nome do Jornal ou Rádio</td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="j_jornal_radio1" value="<?=$j_jornal_radio1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr> 
    <tr>
		<td class="subtitulodireita">Titular da Conta</td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="j_titular_conta1" value="<?=$j_titular_conta1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr> 
    <tr>
		<td class="subtitulodireita">CPF/CNPJ do Titular da Conta</td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="j_cpf_cnpj1" value="<?=$j_cpf_cnpj1?>" type="text" style="width:88%" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr> 
    <tr>
		<td class="subtitulodireita">Banco</td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="j_banco1" value="<?=$j_banco1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr> 
    <tr>
		<td class="subtitulodireita">Agência</td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="j_agencia1" value="<?=$j_agencia1?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr>   
    <tr>
		<td class="subtitulodireita">Conta</td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="j_conta1" value="<?=$j_conta1?>" type="text" style="width:88%" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr>
    <tr>
		<td class="subtitulodireita">E-mail</td>
		<td class="subtitulopequeno" colspan="2">
        	<input name="j_email1" value="<?=$j_email1?>" type="text" style="width:88%" maxlength="50" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr>
    <tr>
		<td colspan="2" class="titulo">&nbsp;</td>
	</tr>    
    <tr>
    	<td class="subtitulodireita">Cidade</td>
    	<td class="subtitulopequeno" colspan="2">
    		<table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
    			<tr>
    				<td width="50%">
                    	<input name="j_cidade2" value="<?=$j_cidade2?>" type="text" style="width:90%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
					</td>
    				<td width="40%" align="right">UF&nbsp;
                    	<input name="j_uf2" value="<?=$j_uf2?>" type="text" style="width:40%" maxlength="2" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>&nbsp;&nbsp;
					</td>
    				<td>&nbsp;</td>
			    </tr>
		    </table>
		</td>
    </tr> 
    <tr>
	    <td class="subtitulodireita">Fone 1</td>
    	<td class="subtitulopequeno" colspan="2">
		    <table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
	    		<tr>
				    <td width="30%">
                    	<input name="j_fone3" value="<?=$j_fone3?>" type="text" style="width:80%" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="soNumero()" class="boxnormal"/>
					</td>
				    <td width="30%" align="right">Fone 2&nbsp;</td>
				    <td width="30%">
                    	<input name="j_fone4" value="<?=$j_fone4?>" type="text" style="width:93%" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="soNumero()" class="boxnormal"/>
					</td>
			    	<td>&nbsp;</td>
		    	</tr>
	    	</table>
		</td>
    </tr> 
    <tr>
	    <td class="subtitulodireita">Contato</td>
    	<td class="subtitulopequeno" colspan="2">
        	<input name="j_contato2" value="<?=$j_contato2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr> 
    <tr>
	    <td class="subtitulodireita">Nome do Jornal ou Rádio</td>
    	<td class="subtitulopequeno" colspan="2">
        	<input name="j_jornal_radio2" value="<?=$j_jornal_radio2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
        </td>
    </tr> 
    <tr>
	    <td class="subtitulodireita">Titular da Conta</td>
    	<td class="subtitulopequeno" colspan="2">
        	<input name="j_titular_conta2" value="<?=$j_titular_conta2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
       	</td>
    </tr> 
    
    <tr>
    	<td class="subtitulodireita">CPF/CNPJ do Titular da Conta</td>
    	<td class="subtitulopequeno" colspan="2">
        	<input name="j_cpf_cnpj2" value="<?=$j_cpf_cnpj2?>" type="text" style="width:88%" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
        </td>
    </tr> 
    
    <tr>
   	 	<td class="subtitulodireita">Banco</td>
    	<td class="subtitulopequeno" colspan="2">
        	<input name="j_banco2" value="<?=$j_banco2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
        </td>
    </tr> 
    
    <tr>
    	<td class="subtitulodireita">Agência</td>
    	<td class="subtitulopequeno" colspan="2">
        	<input name="j_agencia2" value="<?=$j_agencia2?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr> 
    
    <tr>
   	 	<td class="subtitulodireita">Conta</td>
   	 	<td class="subtitulopequeno" colspan="2">
        	<input name="j_conta2" value="<?=$j_conta2?>" type="text" style="width:88%" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr>
    <tr>
    	<td class="subtitulodireita">E-mail</td>
    	<td class="subtitulopequeno" colspan="2">
        	<input name="j_email2" value="<?=$j_email2?>" type="text" style="width:88%" maxlength="50" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" class="boxnormal"/>
		</td>
    </tr>
    
    <tr>
    	<td colspan="2" class="titulo">&nbsp;</td>
	</tr>
    
    <tr>
    	<td class="subtitulodireita">Cidade</td>
    	<td class="subtitulopequeno" colspan="2">
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
    	<td class="subtitulopequeno" colspan="2">
    		<table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
    			<tr>
				    <td width="30%"><input name="j_fone5" value="<?=$j_fone5?>" type="text" style="width:80%" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="soNumero()" class="boxnormal"/></td>
				    <td width="30%" align="right">Fone 2&nbsp;</td>
				    <td width="30%"><input name="j_fone6" value="<?=$j_fone6?>" type="text" style="width:93%" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="soNumero()" class="boxnormal"/></td>
				    <td>&nbsp;</td>
			    </tr>
		    </table>
		</td>
    </tr> 
    
    <tr>
	    <td class="subtitulodireita">Contato</td>
    	<td class="subtitulopequeno" colspan="2"><input name="j_contato3" value="<?=$j_contato3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
    </tr> 
    
    <tr>
    	<td class="subtitulodireita">Nome do Jornal ou Rádio</td>
	    <td class="subtitulopequeno" colspan="2"><input name="j_jornal_radio3" value="<?=$j_jornal_radio3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
    </tr> 
    
    <tr>
    	<td class="subtitulodireita">Titular da Conta</td>
	    <td class="subtitulopequeno" colspan="2"><input name="j_titular_conta3" value="<?=$j_titular_conta3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
    </tr> 
    
    <tr>
	    <td class="subtitulodireita">CPF/CNPJ do Titular da Conta</td>
	    <td class="subtitulopequeno" colspan="2"><input name="j_cpf_cnpj3" value="<?=$j_cpf_cnpj3?>" type="text" style="width:88%" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
    </tr> 
    
    <tr>
	    <td class="subtitulodireita">Banco</td>
	    <td class="subtitulopequeno" colspan="2"><input name="j_banco3" value="<?=$j_banco3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"class="boxnormal" /></td>
    </tr> 
    
    <tr>
	    <td class="subtitulodireita">Agência</td>
	    <td class="subtitulopequeno" colspan="2"><input name="j_agencia3" value="<?=$j_agencia3?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
    </tr> 
    
    <tr>
	    <td class="subtitulodireita">Conta</td>
	    <td class="subtitulopequeno" colspan="2"><input name="j_conta3" value="<?=$j_conta3?>" type="text" style="width:88%" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
    </tr>
    <tr>
	    <td class="subtitulodireita">E-mail</td>
	    <td class="subtitulopequeno" colspan="2"><input name="j_email3" value="<?=$j_email3?>" type="text" style="width:88%" maxlength="50" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" class="boxnormal"/></td>
    </tr>
    
    <tr>
	    <td class="titulo" colspan="3"><input type="hidden" name="go" value="alterar" /></td>
    </tr>
</table>
<br>
<?php
if ($tipo == "a") {
?>
<table border="0" align="center" width="650">
    <tr>
    	<td colspan="2" class="titulo">Dados Banc&aacute;rios</td>
    </tr>
    <tr>
    	<td class="subtitulodireita">Banco</td>
   		<td class="subtitulopequeno">
        	<select name="banco" class="boxnormal">
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
    		</select>
		</td>
    </tr>
    <tr>
    	<td class="subtitulodireita">Agencia</td>
    	<td class="subtitulopequeno">
    		<input name="agencia" type="text" size="17" maxlength="14" class="boxnormal"
    onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"
    value="<?php echo $agencia; ?>" />
    	</td>
    </tr>
    <tr>
	    <td class="subtitulodireita">Tipo de Conta</td>
	    <td class="subtitulopequeno">
		    <input type="radio" name="tpconta" value="1" <?php if ($tpconta == 1) echo "checked"; ?>>Conta Corrente
		    <input type="radio" name="tpconta" value="2" <?php if ($tpconta == 2) echo "checked"; ?>>Poupan&ccedil;a
		</td>
	</tr>
    <tr>
    	<td class="subtitulodireita">Conta</td>
	    <td class="subtitulopequeno"><input name="conta" type="text" size="17" maxlength="11" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $conta; ?>" /></td>
    </tr>
    <tr>
    	<td class="subtitulodireita">Titular da Conta</td>
	    <td class="subtitulopequeno"><input class="boxnormal" name="titular" type="text" size="75" maxlength="40" onFocus="this.className='boxover'"onBlur="maiusculo(this); this.className='boxnormal'" value="<?php echo $titular; ?>" /></td>
    </tr>
    <tr>
	    <td class="subtitulodireita">CPF do Titular</td>
	    <td class="subtitulopequeno"><input name="cpftitular" type="text" size="17" maxlength="14" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $cpftitular; ?>" onKeyPress="formatar('###.###.###-##', this); soNumero()" /></td>
    </tr>
    <tr>
	    <td colspan="2" class="titulo">&nbsp;</td>
    </tr>
</table>
<?php
} //fim if
?>

<?php if ( ($tipo == "a") || ($tipo == "d") ) { ?>
<table align="center">
    <tr align="center">
		<td><input name="submit" type="submit" value="     Modificar    " /></td>
		<td><input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
    </tr>
</table>
<?php } ?>  
  
</form>
<hr />
<?php
include "ranking/mensagem_franqueados.php"; 
} //fim empty go

if ($go == 'alterar') {
$data = date('Y-m-d H:i:s');
$id			 = $_POST['id'];
$franquia	 = $_POST['franquia'];
$razao       = $_POST['razao'];
$cnpj        = $_POST['cnpj'];
$endereco    = $_POST['endereco'];
$bairro      = $_POST['bairro'];
$cidade      = $_POST['cidade'];
$uf          = $_POST['uf'];
$cep         = $_POST['cep'];
$telefone    = $_POST['telefone'];
$fone_res    = $_POST['fone_res'];
$fone3       = $_POST['fone3'];
$email       = $_POST['email'];
$nome_prop1  = $_POST['nome_prop1'];
$cpf1        = $_POST['cpf1'];
$celular1    = $_POST['celular1'];
$operadora_1 = $_POST['operadora_1'];
$nome_prop2  = $_POST['nome_prop2'];
$cpf2        = $_POST['cpf2'];
$celular2    = $_POST['celular2'];
$operadora_2 = $_POST['operadora_2'];
$ctacte		 = $_POST['ctacte'];
$banco 		 = $_POST['banco'];
$agencia 	 = $_POST['agencia'];
$tpconta 	 = $_POST['tpconta'];
$conta 		 = $_POST['conta'];
$titular 	 = $_POST['titular'];
$cpftitular  = $_POST['cpftitular'];
$gerente	 = $_POST['gerente'];
$data_abertura = $_POST['data_abertura'];
$data_apoio	 = $_POST['data_apoio'];
$ctacte  	 = $_POST['ctacte'];
$id_gerente  = $_POST['id_gerente'];
$qtd_contrato_mes = $_POST['qtd_contrato_mes'];

//trata as variaveis para o formato padr�o
$telefone=str_replace("(","",$telefone);
$telefone=str_replace(")","",$telefone);
$telefone=str_replace("-","",$telefone);

$celular1=str_replace("(","",$celular1);
$celular1=str_replace(")","",$celular1);
$celular1=str_replace("-","",$celular1);

$celular2 =str_replace("(","",$celular2);
$celular2=str_replace(")","",$celular2);
$celular2=str_replace("-","",$celular2);

$fone_res=str_replace("(","",$fone_res);
$fone_res=str_replace(")","",$fone_res);
$fone_res=str_replace("-","",$fone_res);

$fone3 = str_replace("(","",$fone3);
$fone3 = str_replace(")","",$fone3);
$fone3 = str_replace("-","",$fone3);

$cnpj=str_replace("/","",$cnpj);
$cnpj=str_replace("-","",$cnpj);
$cnpj=str_replace(".","",$cnpj);

$cpf1=str_replace("/","",$cpf1);
$cpf1=str_replace("-","",$cpf1);
$cpf1=str_replace(".","",$cpf1);

$cpf2=str_replace("/","",$cpf2);
$cpf2=str_replace("-","",$cpf2);
$cpf2=str_replace(".","",$cpf2);

$cpftitular=str_replace("/","",$cpftitular);
$cpftitular=str_replace("-","",$cpftitular);
$cpftitular=str_replace(".","",$cpftitular);

$data_abertura = implode(preg_match("~\/~", $data_abertura) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_abertura) == 0 ? "-" : "/", $data_abertura)));

$data_apoio = implode(preg_match("~\/~", $data_apoio) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_apoio) == 0 ? "-" : "/", $data_apoio)));

$comando .= "UPDATE cs2.franquia SET
			fantasia	= '$franquia',
			razaosoc	= '$razao',
			cpfcnpj		= '$cnpj',
			endereco	= '$endereco',
			bairro		= '$bairro',
			cidade		= '$cidade',
			uf			= '$uf',
			cep			= '$cep',
			fone1		= '$telefone',
			fone2		= '$fone_res',
			fone3		= '$fone3',
			email		= '$email',
			nom01socio	= '$nome_prop1',
			doc01socio	= '$cpf1',
			cel01socio	= '$celular1',
			nom02socio 	= '$nome_prop2',
			doc02socio 	= '$cpf2',
			ctacte   	= '$ctacte',
			cel02socio 	= '$celular2',
			operadora_1	= '$operadora_1',
			operadora_2	= '$operadora_2',
			qtd_contrato_mes = '$qtd_contrato_mes' ";

if($tipo == "a") $comando .= " , id_gerente	= '$id_gerente' ";
			
			
if (!empty($banco)) {
	$comando .= ", ctacte		= '$ctacte',
			banco		= '$banco',
			agencia		= '$agencia',
			tpconta		= '$tpconta',
			conta		= '$conta',
			titular		= '$titular',
			cpftitular	= '$cpftitular'";
}
$comando .= "
			, data_abertura = '$data_abertura',
			data_apoio	= '$data_apoio'
			WHERE id = '$id'";			
mysql_query($comando,$con);

//tratamento dos jornais
$j_cidade1       = $_REQUEST['j_cidade1'];
$j_uf1           = $_REQUEST['j_uf1'];
$j_fone1		 = str_replace("-","",$_REQUEST['j_fone1']);
$j_fone2		 = str_replace("-","",$_REQUEST['j_fone2']);
$j_contato1      = $_REQUEST['j_contato1'];
$j_jornal_radio1 = $_REQUEST['j_jornal_radio1'];
$j_titular_conta1= $_REQUEST['j_titular_conta1'];
$j_cpf_cnpj1     = $_REQUEST['j_cpf_cnpj1'];
$j_banco1        = $_REQUEST['j_banco1'];
$j_agencia1 	 = $_REQUEST['j_agencia1'];
$j_conta1        = $_REQUEST['j_conta1'];
$j_email1        = $_REQUEST['j_email1'];

$j_cidade2       = $_REQUEST['j_cidade2'];
$j_uf2           = $_REQUEST['j_uf2'];
$j_fone3		 = str_replace("-","",$_REQUEST['j_fone3']);
$j_fone4		 = str_replace("-","",$_REQUEST['j_fone4']);
$j_contato2      = $_REQUEST['j_contato2'];
$j_jornal_radio2 = $_REQUEST['j_jornal_radio2'];
$j_titular_conta2= $_REQUEST['j_titular_conta2'];
$j_cpf_cnpj2     = $_REQUEST['j_cpf_cnpj2'];
$j_banco2        = $_REQUEST['j_banco2'];
$j_agencia2 	 = $_REQUEST['j_agencia2'];
$j_conta2        = $_REQUEST['j_conta2'];
$j_email2        = $_REQUEST['j_email2'];

$j_cidade3       = $_REQUEST['j_cidade3'];
$j_uf3           = $_REQUEST['j_uf3'];
$j_fone5		 = str_replace("-","",$_REQUEST['j_fone5']);
$j_fone6		 = str_replace("-","",$_REQUEST['j_fone6']);
$j_contato3      = $_REQUEST['j_contato3'];
$j_jornal_radio3 = $_REQUEST['j_jornal_radio3'];
$j_titular_conta3= $_REQUEST['j_titular_conta3'];
$j_cpf_cnpj3     = $_REQUEST['j_cpf_cnpj3'];
$j_banco3        = $_REQUEST['j_banco3'];
$j_agencia3 	 = $_REQUEST['j_agencia3'];
$j_conta3        = $_REQUEST['j_conta3'];
$j_email3        = $_REQUEST['j_email3'];
//echo "<pre>";
//print_r($_REQUEST);
//echo "=>>>  ".$j_cidade1;
if($_REQUEST['grava_1'] == 1){
	$sql_jornal_1 = "UPDATE cs2.franquia_relacao_jornal SET
					cidade        = '$j_cidade1', 
					uf			  = '$j_uf1', 
					fone_1		  = '$j_fone1', 
					fone_2		  = '$j_fone2', 
					contato		  = '$j_contato1', 
					jornal_radio  = '$j_jornal_radio1', 
					titular_conta = '$j_titular_conta1', 
					cpf_cnpj      = '$j_cpf_cnpj1', 
					banco		  = '$j_banco1', 
					agencia       = '$j_agencia1', 
					conta         = '$j_conta1',
					email         = '$j_email1'
					WHERE
						id        = '{$_REQUEST['id_1']}'
					AND
					   id_franquia = '{$_REQUEST['id_franquia']}'";					      
	$qry_jornal_1 = mysql_query ($sql_jornal_1, $con);	
}else{
	$sql_jornal_1 = "INSERT INTO cs2.franquia_relacao_jornal (id_franquia, cidade, uf, fone_1, fone_2, contato, jornal_radio, titular_conta, cpf_cnpj, banco, agencia, conta, data_hora_cadastro, email)
					 VALUES('{$_REQUEST['id_franquia']}', '$j_cidade1', '$j_uf1', '$j_fone1', '$j_fone2', '$j_contato1', '$j_jornal_radio1', '$j_titular_conta1', '$j_cpf_cnpj1', '$j_banco1', '$j_agencia1', '$j_conta1', now()), '$j_email1'";
	$qry_jornal_1 = mysql_query ($sql_jornal_1, $con);
}

if($_REQUEST['grava_2'] == 1){
	$sql_jornal_2 = "UPDATE cs2.franquia_relacao_jornal SET
					cidade        = '$j_cidade2', 
					uf			  = '$j_uf2', 
					fone_1		  = '$j_fone3', 
					fone_2		  = '$j_fone4', 
					contato		  = '$j_contato2', 
					jornal_radio  = '$j_jornal_radio2', 
					titular_conta = '$j_titular_conta2', 
					cpf_cnpj      = '$j_cpf_cnpj2', 
					banco		  = '$j_banco2', 
					agencia       = '$j_agencia2', 
					conta         = '$j_conta2',
					email         = '$j_email2'
					WHERE
						id        = '{$_REQUEST['id_2']}'
					AND
					   id_franquia = '{$_REQUEST['id_franquia']}'";					      
	$qry_jornal_2 = mysql_query ($sql_jornal_2, $con);	
}else{
	$sql_jornal_2 = "INSERT INTO cs2.franquia_relacao_jornal (id_franquia, cidade, uf, fone_1, fone_2, contato, jornal_radio, titular_conta, cpf_cnpj, banco, agencia, conta, data_hora_cadastro, email)
					 VALUES('{$_REQUEST['id_franquia']}', '$j_cidade2', '$j_uf2', '$j_fone3', '$j_fone4', '$j_contato2', '$j_jornal_radio2', '$j_titular_conta2', '$j_cpf_cnpj2', '$j_banco2', '$j_agencia2', '$j_conta2', now(), '$j_email2')";
	$qry_jornal_2 = mysql_query ($sql_jornal_2, $con);
}

if($_REQUEST['grava_3'] == 1){
	$sql_jornal_3 = "UPDATE cs2.franquia_relacao_jornal SET
					cidade        = '$j_cidade3', 
					uf			  = '$j_uf3', 
					fone_1		  = '$j_fone5', 
					fone_2		  = '$j_fone6', 
					contato		  = '$j_contato3', 
					jornal_radio  = '$j_jornal_radio3', 
					titular_conta = '$j_titular_conta3', 
					cpf_cnpj      = '$j_cpf_cnpj3', 
					banco		  = '$j_banco3', 
					agencia       = '$j_agencia3', 
					conta         = '$j_conta3',
					email         = '$j_email3'
					WHERE
						id        = '{$_REQUEST['id_3']}'
					AND
					   id_franquia = '{$_REQUEST['id_franquia']}'";					      
	$qry_jornal_3 = mysql_query ($sql_jornal_3, $con);
}else{
	$sql_jornal_3 = "INSERT INTO cs2.franquia_relacao_jornal (id_franquia, cidade, uf, fone_1, fone_2, contato, jornal_radio, titular_conta, cpf_cnpj, banco, agencia, conta, data_hora_cadastro, email)
					 VALUES('{$_REQUEST['id_franquia']}', '$j_cidade3', '$j_uf3', '$j_fone5', '$j_fone6', '$j_contato3', '$j_jornal_radio3', '$j_titular_conta3', '$j_cpf_cnpj3', '$j_banco3', '$j_agencia3', '$j_conta3', now(), '$j_email3')";
	$qry_jornal_3 = mysql_query ($sql_jornal_3, $con);
}
echo "<script>alert(\"Franqueado atualizado com sucesso!\");</script>";
echo "<meta http-equiv=\"refresh\" content=\"0; url=painel.php?pagina1=Franquias/b_altfranqueado.php&id=$id\";>";

$res = mysql_close ($con);

} //fim go = alterar
?>