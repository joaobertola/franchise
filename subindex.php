    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta  charset=iso-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex,nofollow">
        <meta name="robots" content="noarchive">
        <meta name="author" content="Sergio Figueroa">
       <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">-->
        <title>Web Control Empresas - Portal Franquia</title>
        <link rel="icon" href="../favicon.ico" type="image/x-icon">
        <script src="https://code.jquery.com/jquery-1.9.0.js"></script>
        <script src="https://code.jquery.com/jquery-migrate-1.4.1.js"></script>
        <script type="text/javascript" src="js/jquery-3.1.1.js"></script>
        <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
        <link href="css/style.css" rel="stylesheet" type="text/css" >
        <link href="css/tabela.css" rel="stylesheet" type="text/css" >
        <link href="css/galeria.css" rel="stylesheet" type="text/css" >
    <script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
    </head>
    <body onLoad="document.entrada.usuario.focus();" class="bg">
    <div class="noprint toplog" id="header">
        <div class="h_logo">
            <div class="logcentro">
                <a href="https://webcontrolempresas.com.br" tabindex="-1" target="_parent">
                        <img src="img/logowebcontrol.png" border="0" alt="Web Control Empresas" />
                    </a>
            </div>
        </div>
    </div>
    <div class="login">
        <div class="centro">
            <table cellspacing="0" >
            <tr>
                <td>
                <form name="entrada" action="php/connect/conexao_log.php" method="post" >
                    <table border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td colspan="2"><span class="logintitle">Login</span></td>
                        </tr>
                        <!--<tr>
                            <td colspan="2" align="center" class="pageName">Identifique-se</td>
                        </tr> -->
                        <tr>
                            <td colspan="2" class="desclog">Entre aqui com o seu Login e Senha para acessar o sistema.</td>
                        </tr>
                        <tr>
                            <td colspan="2" >
                                <label  class="fontlabel" for="usuario">Login</label>
                                <input name="usuario" type="text" id="usuario" size="30" maxlength="20" class="inputlog" onBlur="maiusculo(this); this.className='boxnormal'" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label   class="fontlabel" for="senha">Senha</label>
                                <input name="senha" type="password" id="senha" size="30" maxlength="20" class="inputlog"  onBlur="maiusculo(this); this.className='boxnormal'" />
                            </td>
                        </tr>
                    </table>
                <table cellspacing="0" cellpadding="0" style="float: left; width: 100%;">
                    <tr>
                      <td>
                            <input class="btn btn-primary" name="Submit" type="submit" value="        Enviar" />
                      </td>
                        <td width="10%"></td>
                        <td>
                            <input class="btn btn-primary" name="Submit2" type="reset" value="Limpar        " />
                      </td>
                    </tr>
                </table>
            </form>
            </td>
        </tr>
    </table>
    </div>
    </div>
    <p>
    </p>
    </body>
    </html>