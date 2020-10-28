<?php
require "connect/sessao.php";

$go = $_POST['go'];
$codigo = $_POST['codigo'];

if (empty($go)) {
    ?>
    <script language="javascript">
        //função para aceitar somente numeros em determinados campos
        function mascara(o, f) {
            v_obj = o
            v_fun = f
            setTimeout("execmascara()", 1)
        }

        function execmascara() {
            v_obj.value = v_fun(v_obj.value)
        }
        function soNumeros(v) {
            return v.replace(/\D/g, "")
        }

        window.onload = function () {
            document.form.codigo.focus();
        }
    </script>
    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="form">
        <table width=70% align="center">
            <tr>
                <td colspan="2" align="center" class="titulo">VISUALIZAR FATURAS DE CLIENTES <br> Tamb&eacute;m Visualiza Faturas Pagas</td>
            </tr>
            <tr>
                <td width="173" class="subtitulodireita">&nbsp;</td>
                <td width="224" class="campoesquerda">&nbsp;</td>
            </tr>
            <tr>
                <td class="subtitulodireita">C&oacute;digo do cliente</td>
                <td class="campoesquerda">
                    <input type="text" name="codigo" id="codigo" size="10" maxlength="6" onKeyPress="mascara(this, soNumeros)" />
                    <input type="hidden" name="go" value="ingressar" />
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">


                    <a href="painel.php?pagina1=clientes/lista_sms.php&id_franquia=<?php echo $_SESSION['id']; ?>">Fazer cobran&ccedil;as por (SMS)</a>


                    <?php
                    if ($_SESSION['id'] == 163 or $_SESSION['id'] == 1204 or $_SESSION['id'] == 46) { // somente Wellington, Ananias e Luciana
                        ?>
                        <br><br><br>
                        <a href="painel.php?pagina1=clientes/relatorio_recebimento.php">Relat&oacute;rio de Recebimento de Cobran&ccedil;as</a>    
                    <?php } ?>
                </td>
                <td class="campoesquerda"><?php echo $nome_franquia; ?></td>
            </tr>
            <tr>
                <td colspan="2" class="titulo">&nbsp;</td>
            </tr>
            <tr align="right">
                <td colspan="2"><input type="submit" value=" Enviar Consulta" name="enviar" /></td>
            </tr>
        </table>
    </form>
    <?php
} // if go=null

if ($go == 'ingressar') {
    if (($tipo == "a") || ($tipo == "c")) {
        $sql = "SELECT CAST(MID(a.logon,1,6) AS UNSIGNED)  as logon, b.id_franquia, b.codloja, b.razaosoc 
				FROM logon a
				INNER JOIN cadastro b ON a.codloja=b.codloja
				WHERE CAST(MID(a.logon,1,6) AS UNSIGNED)='$codigo'";
    } else {
        $sql = "SELECT CAST(MID(a.logon,1,6) AS UNSIGNED)  AS logon, b.id_franquia, b.codloja, b.razaosoc 
				FROM logon a
				INNER JOIN cadastro b ON a.codloja=b.codloja
				WHERE CAST(MID(a.logon,1,6) AS UNSIGNED)='$codigo' AND id_franquia='$id_franquia'";
    }
    $resulta = mysql_query($sql,$con);
    $linha = mysql_num_rows($resulta);
    if ($linha == 0) {
        print"<script>alert(\"Cliente não existe ou não pertence à sua franquia!\");history.back();</script>";
    } else {
        $matriz = mysql_fetch_array($resulta);
        $codloja = $matriz['codloja'];
        $logon = $matriz['logon'];
        $razaosoc = $matriz['razaosoc'];
        echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_ver_faturas.php&codloja=$codloja&logon=$logon&razaosoc=$razaosoc\";>";
    }
}
?>