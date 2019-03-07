<?php
$mail = $_GET['mail'];
?>
<br />
<table width=100% align="center">
    <tr>
        <td colspan="2" align="center" class="titulo">SISTEMA DE EMAILS PARA FRANQUEADOS </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="152" border="0" class="bodyText">
                <tr>
                    <td width="5%"><img src="../img/message.gif" alt="Nova mensagem"></td>
                    <td><a href="painel.php?pagina1=area_restrita/d_email.php&mail=area_restrita/d_recados.php">Nova Mensagem</a></td>
                </tr>
                <tr>
                    <td><img src="../img/inbox.gif" alt="Caixa de Entrada" width="41" height="42"></td>
                    <td><a href="painel.php?pagina1=area_restrita/d_email.php&mail=area_restrita/d_lista_email.php">Caixa de Entrada</a></td>
                </tr>
                <tr>
                    <td><img src="../img/MailSend.gif" alt="Itens Enviados" width="41" height="42"></td>
                    <td><a href="painel.php?pagina1=area_restrita/d_email.php&mail=area_restrita/d_enviados_email.php">Itens Enviados</a></td>
                </tr>
            </table>	  </td>
        <td width="83%" valign="top">
            <?php include($mail); ?>
        </td>
    </tr>
</table>