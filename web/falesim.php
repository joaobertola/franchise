<title>Inform System</title>
<link href="sct/geral.css" rel="stylesheet" type="text/css">
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p> 
  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="140" height="23">
    <param name="movie" value="swf/tit_cliente.swf">
    <param name="quality" value="high">
    <embed src="swf/tit_cliente.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="140" height="23"></embed></object>
</p>
<ul>
  <li> Teremos o maior prazer em lhe atender.
    <p>Tire suas d&uacute;vidas, envie sugest&otilde;es, obtenha informa&ccedil;&otilde;es 
      e orienta&ccedil;&otilde;es sobre neg&oacute;cios, produtos e servi&ccedil;os.</p>
    <div class="hr">
<div align="center"><br>
        <form name="senddata" method="post" action="index.php?web=sendsim">
          <br>
          <TABLE 
                              style="FONT-SIZE: 11px; FONT-FAMILY: verdana" 
                              cellSpacing=0 cellPadding=5>
            <TBODY>
              <TR> 
                <TD><B>Departamento</B></TD>
                <TD> <input type="radio" name="departamento" value="automacao">
                  Automa&ccedil;&atilde;o 
                  <input type="radio" name="departamento" value="cobranca">
                  Cobran&ccedil;a 
                  <input type="radio" name="departamento" value="atendimento">
                  Atendimento 
                  <input type="radio" name="departamento" value="comercial">
                  Comercial</TD>
              </TR>
              <TR> 
                <TD><B>C&oacute;digo</B></TD>
                <TD><INPUT 
                                style="FONT-SIZE: 11px; FONT-FAMILY: verdana" 
                                maxLength=80 size=50 name=codigo></TD>
              </TR>
              <TR> 
                <TD><b>Contato</b></TD>
                <TD><INPUT 
                                style="FONT-SIZE: 11px; FONT-FAMILY: verdana" 
                                maxLength=80 size=50 name=contato></TD>
              </TR>
              <TR> 
                <TD><b>E-mail</b></TD>
                <TD> <strong> <strong> 
                  <input 
                                style="FONT-SIZE: 11px; FONT-FAMILY: verdana" 
                                maxlength=80 size=50 name=email>
                  </strong></strong></TD>
              </TR>
              <TR> 
                <TD><strong>Fone</strong></TD>
                <TD><input 
                                style="FONT-SIZE: 11px; FONT-FAMILY: verdana" 
                                maxlength=25 size=18 name=fone>
                  <strong>Fone Cel</strong> <input 
                                style="FONT-SIZE: 11px; FONT-FAMILY: verdana" 
                                maxlength=25 size=18 name=cel></TD>
              </TR>
              <TR>
                <TD><b>Mensagem</b></TD>
                <TD><textarea style="FONT-SIZE: 11px; FONT-FAMILY: verdana" name=mensagem rows=5 cols=49></textarea></TD>
              </TR>
            </TBODY>
          </TABLE>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="100">&nbsp;</td>
              <td width="100" height="50"> <div align="center"> <i><b> 
                  <input type="submit" name="enviar2" value="Enviar">
                  </b></i></div></td>
              <td width="100"><i><b> 
                <input type="reset" name="reset" value="Limpar">
                </b></i></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </li>
</ul>
