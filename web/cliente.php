<title>Inform System</title>
<link href="sct/geral.css" rel="stylesheet" type="text/css">
<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p> 
<script>
function soNumero() {
    var tecla;
    tecla = event.keyCode;
    if (tecla < 48 || tecla > 57)  event.returnValue = false;
}
// formato mascara CNPJ, telefone e CPF
function formatar(mascara, documento){
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
	documento.value += texto.substring(0,1);
  }
}
</script>

<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0','width','140','height','23','src','swf/tit_cliente','quality','high','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','swf/tit_cliente' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="140" height="23">
    <param name="movie" value="swf/tit_cliente.swf">
    <param name="quality" value="high">
    <embed src="swf/tit_cliente.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="140" height="23"></embed></object></noscript>
</p>
<ul>
  <li> Solu&ccedil;&otilde;es Empresariais Exclusivas.</li>
  <li>Solu&ccedil;&otilde;es para Localiza&ccedil;&atilde;o M&aacute;xima de Pessoas.</li>
  <li>Solu&ccedil;&otilde;es para Triplicar as suas Vendas no Boleto.</li>
  <li>Solu&ccedil;&otilde;es para Recuperar  at&eacute; 80% da sua Inadimpl&ecirc;ncia.</li>
  <li>Solu&ccedil;&otilde;es para Bloqueio de Devedores.</li>
  <li>Pesquisas de Cr&eacute;dito Inteligentes - ATUAL, MODERNA, COMPLETA.</li>
  <li>Reduza em at&eacute; 95% do seu &iacute;ndice 
    de inadimpl&ecirc;ncia.
    <p>Informa&ccedil;&otilde;es de abrang&ecirc;ncia nacional, custo diferenciado, tecnologia de ponta, v&aacute;rias 
      modalidades de pesquisas, meios de acesso, s&atilde;o somente algumas das 
    vantagens que temos a oferecer, tudo adequado a necessidade de sua empresa.</p>
    <p>Preencha o formul&aacute;rio abaixo e receba a visita de um dos nossos 
      consultores.<br>
    </p>
  </li>
</ul>
<div class="hr"></div> 
      <div>
        <form name="senddata" method="post" action="index.php?web=sendcliente">
          <br>
          <TABLE style="FONT-SIZE: 11px; FONT-FAMILY: verdana" cellSpacing=0 cellPadding=5 align="center">
            <TBODY>
              <TR>
                <TD width="156"><b>CNPJ</b></TD>
                <TD width="507"><INPUT onKeyPress="soNumero(); formatar('##.###.###/####-##', this)" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=18 size=18 name=cnpj>
                </strong></TD>
              </TR>
              <TR> 
                <TD><B>Raz&atilde;o Social / Nome Fantasia</B></TD>
                <TD><INPUT 
                                style="FONT-SIZE: 11px; FONT-FAMILY: verdana" 
                                maxLength=80 size=50 name=nome></TD>
              </TR>
              <TR> 
                <TD><B>Endere&ccedil;o</B></TD>
                <TD><?
						require "restrito/conexao_conecta.php";
						$combo = "<select name='id_tp_log'>
			    	              <option value=''>Selecione</option>";
						$sql_tipo = 'Select id,descricao from apoio.Tipo_Log';
						$qry_sql_tipo = mysql_query($sql_tipo,$con);
						while($rst_tipo = mysql_fetch_array($qry_sql_tipo)) {
							$id_x  = $rst_tipo['id'];
							$descricao  = $rst_tipo['descricao'];                         	
							if ($tplog == $id_x){
								$combo .= "<option selected value='$id_x'>$descricao</option>";
							}else{
								$combo .= "<option value='$id_x'>$descricao</option>";
							}
						}
						echo $combo;
		            ?>
                	<INPUT style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=50 size=40 name=endereco>
                </TD>
              </TR>
              <TR> 
                <TD><B>Bairro</B></TD>
                <TD><INPUT 
                                style="FONT-SIZE: 11px; FONT-FAMILY: verdana" 
                                maxLength=25 size=26 name=bairro>
                  <font color="#FFFFFF" size="1">-</font></TD>
              </TR>
              <TR> 
                <TD><B>Cidade</B></TD>
                <TD><INPUT style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=30 size=26 name=cidade>
                  <strong>CEP</strong>
                  <INPUT style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=25 size=15 name=cep>                  </font><strong>UF</strong> <font color="#FFFFFF" size="1">-</font> 
                  <INPUT style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=25 size=2 name=uf></TD>
              </TR>
              <TR> 
                <TD><p><B>E-mail</B></p>
                </TD>
                <TD><INPUT 
                                style="FONT-SIZE: 11px; FONT-FAMILY: verdana" 
                                maxLength=80 size=50 name=email></TD>
              </TR>
              <TR> 
                <TD><strong>Telefone</strong></TD>
                <TD><INPUT onKeyPress="soNumero(); formatar('##-####-####', this)"style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=25 size=18 name=fone>
                  <strong> <font color="#FFFFFF" size="1">-</font><strong></strong>Celular</strong> 
                  <INPUT onKeyPress="soNumero(); formatar('##-####-####', this)" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=25 size=18 name=cel></TD>
              </TR>
              <TR> 
                <TD><strong>CPF</strong></TD>
                <TD><strong> 
                  <INPUT onKeyPress="soNumero(); formatar('###.###.###-##', this)" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=25 size=18 name=cpf>
                  <strong> <font color="#FFFFFF" size="1">-</font></strong></strong></TD>
              </TR>
              <TR>
                <TD><B>Contato</B></TD>
                <TD><INPUT style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=80 size=50 name=contato></TD>
              </TR>
              <TR>
                <TD valign="top"><b>Mensagem</b></TD>
                <TD><textarea style="FONT-SIZE: 11px; FONT-FAMILY: verdana" name=mensagem rows=5 cols=49></textarea></TD>
              </TR>
              <tr>
              <td colspan="2" align="center">
                  <strong>(41) 3207-1700</strong><br>
                  <strong>(41) 3026-1558</strong>
               </td>
              </tr>
            </TBODY>
          </TABLE>
          <table border="0" cellspacing="0" cellpadding="0" align="center">
            <tr> 
              <td width="100" height="50">  <i><b> 
                <input type="submit" name="enviar2" value="Enviar">
                </b></i></td>
              <td width="100"><i><b> 
                <input type="reset" name="reset" value="Limpar">
                </b></i></td>
            </tr>
          </table>
        </form>
      </div>
    <div class="hr"></div>
