<?php
require "connect/sessao.php";
require "connect/conexao_conecta.php";

$codloja   = $_REQUEST['codloja'];
$logon     = $_REQUEST['logon'];
$opcao_carta  = $_REQUEST['opcao_carta'];

$data_atual2 = date('d/m/Y');

switch ($opcao_carta){
	case 1 : $opcao1 = ' selected '; break;
	case 2 : $opcao2 = ' selected '; break;
	case 3 : $opcao3 = ' selected '; break;
	case 4 : $opcao4 = ' selected '; break;
	case 5 : $opcao5 = ' selected '; break;
	case 6 : $opcao6 = ' selected '; break;
	case 7 : $opcao7 = ' selected '; break;
	case 8 : $opcao8 = ' selected '; break;
	case 9 : $opcao9 = ' selected '; break;
}

$sql_dados = "SELECT razaosoc, nomefantasia, end, numero, complemento, bairro, cidade, uf, cep, email
              FROM cs2.cadastro
              WHERE codloja = $codloja";
$qry_dados = mysql_query($sql_dados,$con);

$razaosoc     = mysql_result($qry_dados,0,'razaosoc');
$nomefantasia = mysql_result($qry_dados,0,'nomefantasia');
$end          = mysql_result($qry_dados,0,'end');
$numero       = mysql_result($qry_dados,0,'numero');
$complemento  = mysql_result($qry_dados,0,'complemento');
$bairro       = mysql_result($qry_dados,0,'bairro');
$cidade       = mysql_result($qry_dados,0,'cidade');
$uf           = mysql_result($qry_dados,0,'uf');
$cep          = mysql_result($qry_dados,0,'cep');
$email        = mysql_result($qry_dados,0,'email');

$endereco     = trim($end);
if ( $numero ) $endereco .= ', '.$numero;
if ( $complemento ) $endereco .= ' - '.$complemento;
if ( $bairro ) $endereco .= ' - '.$bairro;
if ( $cidade ) $endereco .= ' - '.$cidade;
if ( $uf ) $endereco .= ' / '.$uf;
if ( $cep ) $endereco .= ' - CEP: '.$cep;


?>

<script language="javascript">

	function muda( dados ){
		document.form.submit();
	}
	
	function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espaço em branco
	
	function gravar(){
		d = document.form;
		
		if ( trim(d.email.value) == '' ){
			if(confirm("CONFIRMA GRAVACAO sem Enviar Email ?")) {
				d.action = 'painel.php?pagina1=clientes/correspondencia_grava_envia.php';
			}else{
				return false;
			}
		}else{
			d.action = 'painel.php?pagina1=clientes/correspondencia_grava_envia.php';
		}
		d.submit();				
	}
</script>
<form name="form" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
<table width="80%" cellpadding="0" cellspacing="1">
	<tr>
    	<td colspan="2" class="titulo">Correspond&ecirc;ncias &agrave; Cliente</td>
    </tr>
	<tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
	<tr>
    	<td class="subtitulodireita" width="200">C&oacute;digo : </td>
    	<td class="campoesquerda"><?=$logon?></td>
    </tr>
	<tr>
    	<td class="subtitulodireita" width="200">Raz&atilde;o Social : </td>
    	<td class="campoesquerda"><?=$razaosoc?></td>
    </tr>
	<tr>
    	<td class="subtitulodireita" width="200">Nome Fantasia : </td>
    	<td class="campoesquerda"><?=$nomefantasia?></td>
    </tr>
	<tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
	<tr>
    	<td class="subtitulodireita">Escolha o Assunto: </td>
        <td class="campoesquerda">
        	<select name="opcao_carta" onchange="muda(this.value)">
            	<option value="0" <?=$opcao0?> >... Selecione ...</option>
            	<option value="1" <?=$opcao1?> >Documentos Pendentes para Cancelamento</option>
                <option value="2" <?=$opcao2?> >Resposta de Cancelamento - Pagamento de Multa</option>
                <option value="3" <?=$opcao3?> >Resposta de Cancelamento - Dentro do Prazo Contratual</option>
                <option value="0" >---------------------------------------------------------------------</option> 
                <option value="4" <?=$opcao4?> >PROCON - COM d&eacute;bito</option>
                <option value="5" <?=$opcao5?> >PROCON - SEM d&eacute;bito</option>
                <option value="0" >---------------------------------------------------------------------</option> 
                <option value="6" <?=$opcao6?> >PETI&Ccedil;&Atilde;O DE ACORDO - COM INDENIZA&Ccedil;&Atilde;O - WC SISTEMAS</option>
                <option value="7" <?=$opcao7?> >PETI&Ccedil;&Atilde;O DE ACORDO - SEM INDENIZA&Ccedil;&Atilde;O - WC SISTEMAS</option>
                <option value="0" >---------------------------------------------------------------------</option> 
                <option value="8" <?=$opcao8?> >PETI&Ccedil;&Atilde;O DE ACORDO - COM INDENIZA&Ccedil;&Atilde;O - INFORM SYSTEM</option>
                <option value="9" <?=$opcao9?> >PETI&Ccedil;&Atilde;O DE ACORDO - SEM INDENIZA&Ccedil;&Atilde;O - INFORM SYSTEM</option>
            </select>
        </td>
    </tr>
	<tr>
    	<td colspan="2" ali>&nbsp;</td>
    </tr>

	<tr>
    	<td colspan="2">
                <?php
				
				$escolha = $_REQUEST['opcao_carta'];
				
				$dia = date('d');$mes = date('m');$ano = date('Y');
				// configuração mes
 				switch ($mes){
					case 1: $mes = "JANEIRO"; break;
					case 2: $mes = "FEVEREIRO"; break;
					case 3: $mes = "MARÇO"; break;
					case 4: $mes = "ABRIL"; break;
					case 5: $mes = "MAIO"; break;
					case 6: $mes = "JUNHO"; break;
					case 7: $mes = "JULHO"; break;
					case 8: $mes = "AGOSTO"; break;
					case 9: $mes = "SETEMBRO"; break;
					case 10: $mes = "OUTUBRO"; break;
					case 11: $mes = "NOVEMBRO"; break;
					case 12: $mes = "DEZEMBRO"; break;
				}
				$data_atual = "$dia de $mes de $ano";
				
				if ( $escolha > 0 ){
                    $sql = "SELECT texto
                            FROM cs2.correspondencia
                            WHERE id = $escolha";
                    $qry = mysql_query($sql,$con);
                    $pag_texto1 = mysql_result($qry ,0,'texto');
				}
				
				switch ($opcao_carta) {
					case 2 :
					case 3 :
					case 4 :
					    // Busca a data do pagamento da multa CONTRATUAL
                        $sql = "SELECT 
                                        DATE_FORMAT(datapg,'%d/%m/%Y') AS datapg,
                                        DATE_FORMAT(datapg,'%m') AS mes 
                                FROM cs2.titulos
                                WHERE codloja = $codloja AND referencia = 'MULTA' AND
                                        valorpg > 0";
                        $qry = mysql_query($sql,$con);
                        if ($qry && mysql_num_rows($qry)) {
                            $data_pgto_multa = mysql_result($qry ,0,'datapg');
                            $mes_pgto_multa = mysql_result($qry ,0,'mes');
                        }
                        // Buscando data do Documento enviado pelo cliente
                        // Caso nao tenha, ficará data vazia.

                        $sql = "SELECT 
                                    DATE_FORMAT(data_documento,'%d/%m/%Y') AS data_documento,
                                    DATE_FORMAT(ultima_fatura,'%d/%m/%Y') AS ultima_fatura,
                                    ultima_fatura AS data_ultima_mysql,
                                    DATE_FORMAT(ultima_fatura,'%m') AS mes_ultima_fatura,
                                    DATE_FORMAT(ultima_fatura,'%Y') AS ano_ultima_fatura
                                FROM cs2.pedidos_cancelamento
                                WHERE codloja = $codloja";
                        $qry = mysql_query($sql,$con);
                        if ($qry && mysql_num_rows($qry)) {
                            $data_documento     = mysql_result($qry ,0,'data_documento');
                            $data_atual2        = mysql_result($qry ,0,'data_documento');
                            $ultima_fatura      = mysql_result($qry ,0,'ultima_fatura');
                            $venc_ultfat        = mysql_result($qry ,0,'data_ultima_mysql');
                            $mes_ultima_fatura  = str_pad(mysql_result($qry ,0,'mes_ultima_fatura')-1,2,0,STR_PAD_LEFT);
                            $mes_ultima_fatura .= '/'.mysql_result($qry ,0,'ano_ultima_fatura');
                        }

                        // Busca de Faturas em Atraso
                        $sql = "SELECT 
                                        DATE_FORMAT(vencimento,'%d/%m/%Y') AS vencimento,
                                        DATE_FORMAT(dtf,'%m/%Y') AS referente
                                FROM cs2.titulos
                                WHERE codloja = $codloja 
                                    AND 
                                        referencia <> 'MULTA' 
                                    AND
                                        valorpg IS NULL";
                        $qry = mysql_query($sql,$con);
                        $vencimentos_nao_pagos = '';
                        while ( $reg = mysql_fetch_array($qry) ){
                            $vencimentos_nao_pagos .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$reg['vencimento'].' ( Referente Servi&ccedil;o dispon&iacute;vel m&ecirc;s: '.$reg['referente'].' )<br> ';
                        }
					    break;
				}
				
				if ($opcao_carta == 2 or $opcao_carta == 3 or $opcao_carta == 4){
                                    
                                    $mes_pgto_multa = date('m');
                                    $vencimentos_nao_pagos .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                    
                                    // verificando se já existe o boleto com vencimento da ULTIMA FATURA, se tiver nao gera a linha abaixo                                               
                                    $sql_tem_titulo = "SELECT count(*) AS qtd FROM cs2.titulos WHERE codloja = $codloja and vencimento = '$venc_ultfat'";
                                    $qryt = mysql_query($sql_tem_titulo,$con);
                                    @$qtd_tit_existente = mysql_result($qryt,0,'qtd');
                                    if ( $qtd_tit_existente > 0){
                                        // nao faz nada
                                    }else
                                        $vencimentos_nao_pagos .= "$ultima_fatura ( Referente Servi&ccedil;o dispon&iacute;vel m&ecirc;s: $mes_ultima_fatura )";
                                            
                }else{
                
                    $mes_pgto_multa *= 1;
                    $mes_pgto_multa_ref = $mes_pgto_multa-1;
                    $mes_pgto_multa_ref  = str_pad($mes_pgto_multa_ref,2,0,STR_PAD_LEFT);

                    $mes_pgto_multa += 1;								
                    $mes_pgto_multa  = str_pad($mes_pgto_multa,2,0,STR_PAD_LEFT);
                    $vencimentos_nao_pagos .= "$ultima_fatura ( Referente Servi&ccedil;o dispon&iacute;vel m&ecirc;s: $mes_ultima_fatura )";
                    
                }

				$pag_texto1 = str_replace('{cliente_razao}',$razaosoc,$pag_texto1);
				$pag_texto1 = str_replace('{codigo}',$logon,$pag_texto1);
				$pag_texto1 = str_replace('{fantasia}',$nomefantasia,$pag_texto1);
				$pag_texto1 = str_replace('{data_atual}',$data_atual,$pag_texto1);
				$pag_texto1 = str_replace('{endereco}',$endereco,$pag_texto1);
				$pag_texto1 = str_replace('{data_pgto_multa}',$data_pgto_multa,$pag_texto1);
				$pag_texto1 = str_replace('{vencimentos_nao_pagos}',$vencimentos_nao_pagos,$pag_texto1);
				$pag_texto1 = str_replace('{data_documento}',$data_documento,$pag_texto1);
				$pag_texto1 = str_replace('{data_atual2}',$data_atual2,$pag_texto1);


                if ( $opcao_carta == 6 or $opcao_carta == 7 ){
                    $pag_texto1 = str_replace('{assinatura_administrativo}', '<img src="https://www.webcontrolempresas.com.br/franquias/img/Assinatura_WC_Wellington.jpg" width="300" />', $pag_texto1);
                }
                if ( $opcao_carta == 8 or $opcao_carta == 9 ){
                    $pag_texto1 = str_replace('{assinatura_administrativo}', '<img src="https://www.webcontrolempresas.com.br/franquias/img/Assinatura_Inform_Wellington.jpg" width="300" />', $pag_texto1);
                }

				
				include ("fckeditor/fckeditor.php"); //Chama a classe fckeditor
				$editor = new FCKeditor("conteudoPag");//Nomeia a area de texto
				$editor-> BasePath = "fckeditor/"; //Informa a pasta do FKC Editor
				$editor-> Value = $pag_texto1;//Informa o valor inicial do campo, no exemplo está vazio
				$editor-> Width = "930"; //informa a largura do editor
				$editor-> Height = "1000";//informa a altura do editor
				$editor-> Create();// Cria o editor
				?>
        </td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td class="subtitulodireita">Enviar por Email:</td>
        <td class="campoesquerda">
        	<input type="hidden" name="codloja" value="<?=$codloja?>"  />
            <input type="hidden" name="escolha" value="<?=$opcao_carta?>"  />
        	<input type="text" name="email" value="<?=$email?>"  />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" value="   GRAVAR e Enviar por Email   " onclick="gravar()"  />
		</td>
    </tr>
</table>
</form>