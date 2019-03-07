<?php

/**
 * @file devolucaoCorrespondencias.php
 * @brief
 * @author Jamilson Bine
 * @date 18/08/2017
 * @version 1.0
 **/

require "../php/connect/sessao_r.php";
require "../php/connect/sessao.php";
//require "../php/connect/conexao_conecta.php";
require "../php/connect/funcoes.php";

require_once('../../webcontrol/Constants.php');
$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
Constants::sessionStart();

$i = (count($_SESSION['itens']) >= 0 ? count($_SESSION['itens'])+1 : 0) ;

if (isset($_POST['motivo']) && isset($_POST['codBarras'])) {

    if ($_POST['motivo'] && !is_null($_POST['codBarras']) && is_numeric($_POST['codBarras'])) {

        $id = $_POST['codBarras'];
        $motivo = $_POST['motivo'];

        $data = date("d/m/Y");
        $data = explode("/", $data);
        $hora = date("H:i");
        $hora = explode(":", $hora);
        list($dia, $mes, $ano) = $data;
        list($horas, $minutos) = $hora;

        $data = $dia . "/" . $mes . "/" . $ano;
        $horas = $horas;
        $hora = $horas . ":" . $minutos;
        $data_e_hora = $data . " " . $hora;

        //$objConexao = new DbConnection();
        $sql = "SELECT a.razaosoc FROM	cs2.cadastro as a INNER JOIN base_web_control.webc_usuario as b ON b.id_cadastro = a.codloja AND login = '$id' LIMIT 1";

        $resposta = mysql_query($sql, $con);
        //$array = mysql_result($resposta);
        $array = mysql_fetch_array($resposta);

        /**
         * Preparando SQL
         */
        //$pdo = $objConexao->pdo->prepare($sql);

        /**
         * Substituindo paramentros por seus valores e executa comando SQL
         */
        //$pdo->execute();
        //$resultado = $pdo->fetch(PDO::FETCH_OBJ);

        $_SESSION['itens'][$i] = array('motivo' => $_POST['motivo'] , 'codloja' => $_POST['codBarras'], 'data_e_hora' => $data_e_hora, 'razaosoc' => $array['razaosoc']);
        //$resposta = mysql_close ($con);
    } else {
        //echo "Sem c&oacute;digo do cliente inserido!";
        if(mysql_fetch_array($sql) > 0) {
            echo "<script>alert('Codigo incorreto!');</script>";
        }
    }

}

if(empty(trim($_POST["enviar"]))){
    //echo "Não inserir ainda" ;
} else {
    //$alterar = "ALTER TABLE cs2.devolucao_de_correspondencias CHANGE COLUMN cliente codloja VARCHAR(70) NOT NULL";
    //$ql = mysql_query($alterar, $con);


    //echo "Inserir dados" ;
    //$objConexao = new DbConnection();
    foreach ($_SESSION['itens'] as $k => $v) {
        $id = $_SESSION['itens'][$k]['codloja'];
        $sql = "SELECT a.codloja FROM cs2.cadastro as a INNER JOIN base_web_control.webc_usuario as b ON b.id_cadastro = a.codloja AND login = '$id' LIMIT 1";

        $resposta = mysql_query($sql, $con);
        //$array = mysql_result($resposta);
        $array = mysql_fetch_array($resposta);
        //echo "<pre>";
        //print_r($array);

        //$codigo = $_SESSION['itens'][$k]['codloja'];
        $codigo = $array['codloja'];
        $mot = $_SESSION['itens'][$k]['motivo'];

        $sql_add = "insert into cs2.devolucao_de_correspondencias(codloja, motivo) values ($codigo, '$mot');";
        //echo "<pre>";
        //print_r($sql_add);
        //$ql = mysql_query($sql_add, $con) ;
        $ql = mysql_query($sql_add, $con) or die ("Erro ao inserir dados ".mysql_error());

        /**
         * Preparando SQL
         */
        //$pdo = $objConexao->pdo->prepare($sql_add);
        //print_r($pdo);die();
        /**
         * Substituindo paramentros por seus valores e executa comando SQL
         */
        //$pdo->execute();
        //@mysql_free_result($ql);
    } ?>

    <script>alert('Dados inseridos na base de dados!')</script>
    <meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=Franquias/devolucao_de_correspondencias.php\";>
    <?php
    $_SESSION['itens'] = array();
    //session_unset();
}

/*
// Função para apresentar os registros armazenados na base de dados
if(empty(trim($_POST["apresentar"]))){
    // Não apresentar
} else{
    $sql = "select * from cs2.devolucao_de_correspondencias;";

    $resposta = mysql_query($sql, $con);
    $array = mysql_fetch_array($resposta);

    if($linha = mysql_num_rows($resposta) == 0){ ?>
        <script>alert('Sem correspondências a exibir')</script>
    <?php } else { ?>
        <table border="0" align="center" width="643">
        <td colspan='5' class="titulo">Correspond&ecirc;ncias devolvidas<br></td>
        <tr>
            <td colspan="0" class="subtitulodireita" align="center">Cliente</td>
            <td colspan="0" class="subtitulodireita" align="center">Motivo</td>
            <td colspan="0" class="subtitulodireita" align="center">Data/Hora</td>
            <td colspan="0" class="subtitulodireita" align="center">A&ccedil;&atilde;o</td>
            <td colspan="0" class="subtitulodireita" align="center">Apagar</td>
        </tr>

        <?php
        $hr = substr($array['data_e_hora'], -8);
        $dt = substr($array['data_e_hora'], 0, 10);

        $d = substr($dt, -2);
        $m = substr($dt, 4, -2);
        $a = substr($dt, 0, 4);

        do {
            ?>
            <tr colspan="2">
                <td colspan="0" class="subtitulopequeno"><?php echo $linha['codloja'];?></td>
                <td colspan="0" class="subtitulopequeno"><?php echo $linha['motivo'];?> </td>
                <td colspan="0" class="subtitulopequeno"><?php echo $d."".$m."".$a." ".$hr;?> </td>
                <td colspan="0" class="subtitulopequeno"><?php echo $linha['acao']; ?> </td>
                <td colspan="0" class="subtitulopequeno" align="center">
                    <form action="" method="post"'>
                    <input type="submit" id="excluir" name="excluir" value="Excluir Registro">
                    </form>
                </td>
            </tr>
        <?php }while($linha = mysql_fetch_assoc($resposta));


        //$linha--;
        //}
        //while ($array = mysql_fetch_array($sresposta)) {
        ?>
        </table><?php

    }
}*/

/*
// Função para excluir registro
if(empty(trim($_POST["excluir"]))){
    // Não apagar
} else{

    $excluir = $array['codloja'];
    $sql = "delete from cs2.devolucao_de_correspondencias where codloja = $excluir";
    $del = mysql_query($sql) or die('Erro ao apagar');
    $resposta = mysql_query($sql, $con);
    //$array = mysql_fetch_array($resposta);

    //$resp = mysql_query($sql, $con);
    $verifica = mysql_affected_rows();

    if($verifica <> 0 ){ ?>
        <script>alert(Registro apagado com sucesso!)</script>
    <?php }else{
        echo "nao encontrado na base de dados";
    }
}


*/?>

<body>
<table border="0" align="center" width="643">
    <td colspan='2' class="titulo">DEVOLU&Ccedil;&Atilde;O DE CORRESPOND&Ecirc;NCIA<br></td>
        <tr>
            <td class="subtitulodireita">&nbsp;</td>
            <td colspan="0" class="subtitulopequeno">(*) Preenchimento obrigat&oacute;rio</td>
        </tr>
        <form name="formulario" id="form1" method="post">
            <tr>
                <td class="subtitulodireita">Motivo Devolu&ccedil;&atilde;o</td>
                <td colspan="0" class="subtitulopequeno">
                    <select name="motivo" id="motivo">
                        <option value="Mudou-se">Mudou-se</option>
                        <option value="Endere&ccedil;o Insuficiente">Endere&ccedil;o Insuficiente</option>
                        <option value="N&atilde;o existe o n&uacute;mero indicado">N&atilde;o existe o n&uacute;mero indicado</option>
                        <option value="Desconhecido">Desconhecido</option>
                        <option value="N&atilde;o procurado">N&atilde;o procurado</option>
                        <option value="Ausente">Ausente</option>
                        <option value="Falecido">Falecido</option>
                        <option value="Recusado">Recusado</option>
                        <option value="Inf. escrita pelo porteiro ou s&iacute;ndico">Inf. escrita pelo porteiro ou s&iacute;ndico</option>
                        <option value="Outros">Outros</option>
                    </select>
            (*)</td>
            </tr>
            <tr>
                <td class="subtitulodireita">C&oacute;digo cliente</td>
                <td colspan="0" class="subtitulopequeno"><input style='' type="text" name="codBarras">(*)</td>
            </tr>
            <br>
        </form>

        <tr>
            <td colspan="2" class="titulo"></td>
        </tr>
</table>
<br>

<?php if (!empty($_SESSION['itens'])) {?>
    <table border="0" align="center" width="643">
        <td colspan='4' class="titulo">Dados a serem inseridos<br></td>
            <tr>
                <td colspan="0" class="subtitulodireita" align="center">C&oacute;digo</td>
                <td colspan="0" class="subtitulodireita" align="center">Cliente</td>
                <td colspan="0" class="subtitulodireita" align="center">Motivo</td>
                <td colspan="0" class="subtitulodireita" align="center">Data/Hora</td>
            </tr>
        <?php

        krsort($_SESSION['itens']);
        //echo "<pre>";
        //print_r($_SESSION['itens']);

        foreach ($_SESSION['itens'] as $k => $v) {?>
            <tr colspan="0">
                <td colspan="0" class="subtitulopequeno"><?php echo $_SESSION['itens'][$k]['codloja'];?></td>
                <td colspan="0" class="subtitulopequeno"><?php echo $_SESSION['itens'][$k]['razaosoc'];?> </td>
                <td colspan="0" class="subtitulopequeno"><?php echo $_SESSION['itens'][$k]['motivo']; ?> </td>
                <td colspan="0" class="subtitulopequeno"><?php echo $_SESSION['itens'][$k]['data_e_hora'];?> </td>
            </tr>
        <?php } ?>
            <tr>
                <td colspan="4" class="titulo"><input type="hidden" name="go" value="cadastrar" /></td>
            </tr>
            <tr>
                <td colspan="4" align="center">
                    <form action="" method="post"'>
                        <input type="submit" id="enviar" name="enviar" value="Confirmar Registro">
                     </form>
                </td>
            </tr>
    </table>

<?php } else{/*?>
        <br>
        <table border="0" align="center" width="643">
            <td colspan='3' class="titulo">Visualizar devolu&ccedil;&otilde;es<br></td>
            <tr>
                <td colspan="0" style="font-size: 8pt; color: black; font-family: Arial; background-color: rgba(1,124,194,0.8); text-align: center; padding-right:5px;">
                    <form action="" method="post"'>
                    <input type="submit" id="apresentar" name="apresentar" value="Visualizar Devolu&ccedil;&otilde;es">
                    </form>
                </td>
            </tr>
        </table>
        <br><br>
        <table border="0" align="center" width="643">
        <td colspan='3' class="titulo">Apagar registro da base de dados<br></td>
        <tr><td class="subtitulodireita">C&oacute;digo cliente</td>
            <form action='' method='post'>
                <td colspan="2" class="subtitulopequeno"><input style='' type="text" id="excluir" name="excluir" placeholder="Excluir Registro"></td>
            </form>
        </tr>
    </table>
    <?php
    //echo 'Sem itens a exibir!';
*/}?>

</body>