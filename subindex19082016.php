<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Web Control Empresas - Portal franquias</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
<meta name="robots" content="noindex,nofollow">
<meta name="robots" content="noarchive">
<meta name="author" content="Sergio Figueroa">
<link href="css/style.css" rel="stylesheet" type="text/css" >
<link href="css/tabela.css" rel="stylesheet" type="text/css" >
<link href="css/galeria.css" rel="stylesheet" type="text/css" >
<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
<body onLoad="document.entrada.usuario.focus();">
<div style="text-align:right; padding-top:50px; padding-right:20px"><a href="http://www.webcontrolempresas.com.br" tabindex="-1" target="_parent"><a href="http://www.webcontrolempresas.com.br" tabindex="-1" target="_parent"><img src="img/logo.gif" border="0" alt="Inform System" /></a></div>
<table width="330" height="300" align="center" cellspacing="0" style="background:url(img/background.jpg); background-position:top; background-repeat:no-repeat">
<tr>
        <td>
        <form name="entrada" action="php/connect/conexao_log.php" method="post" >
            <table width="232" border="0" align="center" cellpadding="1" cellspacing="0">
                
                <tr>
                	<td colspan="2" align="center" class="pageName">Identifique-se</td>
                </tr>
                <tr> 
                	<td colspan="2" class="titulo">Entre aqui com o seu Login e Senha para acessar o sistema.</td>
                </tr>
                <tr> 
                	<td width="76" class="subtitulodireita"><label for="usuario">Login</label></td>
                	<td width="180" class="campoesquerda">
                    	<input name="usuario" type="text" id="usuario" size="30" maxlength="20" class="boxnormal" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />                    </td>
                </tr>
                <tr>
                	<td class="subtitulodireita"><label for="senha">Senha</label></td>
                	<td class="campoesquerda">
                    	<input name="senha" type="password" id="senha" size="30" maxlength="20" class="boxnormal" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />                    </td>
                </tr>
                <tr> 
                	<td class="subtitulodireita" >&nbsp;</td>
                	<td class="campoesquerda">&nbsp;</td>
                </tr>
            </table>
            <table width="226" border="0" align="center" cellspacing="1" cellpadding="0">
                <tr>
                  <td width="45%" align="right">
                    	<input name="Submit" type="submit" value="        Enviar" />
                  </td>
                    <td width="10%"></td>
                    <td width="45%">
                    	<input name="Submit2" type="reset" value="Limpar        " />
                  </td>
                </tr>
            </table>
        </form>
        </td>
    </tr>
</table>
<p>
</p>
</body>
</html>