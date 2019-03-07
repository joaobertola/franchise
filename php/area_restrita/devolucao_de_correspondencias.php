<?php

/**
 * @file devolucaoCorrespondencias.php
 * @brief
 * @author Jamilson Bine
 * @date 18/08/2017
 * @version 1.0
 **/

if (!file_exists('./Constants.php')) {
    require_once('./Constants.php');

} else {
    require_once('./Constants.php');

}

Constants::sessionStart();

require_once('./classes/DbConnection.class.php');
//$i = 0;
//sessions_unset();
$i = (count($_SESSION['itens']) >= 0 ? count($_SESSION['itens'])+1 : 0) ;

//echo count($_SESSION['itens']);
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
        $horas = $horas - 3;
        $hora = $horas . ":" . $minutos;
        $data_e_hora = $data . ", " . $hora;

        $objConexao = new DbConnection();

        $sql = "select razaosoc from cs2.cadastro where codloja like $id";

        /**
         * Preparando SQL
         */
        $pdo = $objConexao->pdo->prepare($sql);

        /**
         * Substituindo paramentros por seus valores e executa comando SQL
         */
        $pdo->execute();

        $resultado = $pdo->fetch(PDO::FETCH_OBJ);

        $_SESSION['itens'][$i] = array('motivo' => $_POST['motivo'] , 'codCliente' => $_POST['codBarras'], 'data_e_hora' => $data_e_hora, 'razaosoc' => $resultado->razaosoc);
        //$_SESSION['itens'] = array_reverse($_SESSION['itens']);
        //$_SESSION['itens'] = rsort($_SESSION['itens']);
        //$dados['id'] = $_POST['motivo'];
        //$dados['cliente'] = SELECT DE RAZAO SOCIAL;
        //$dados['motivo'] = $_POST['motivo'];
        //$dados['dataHora'] = $data_e_hora;

        // INSERÇÃO DE DADOS-> INSERT INTO cs2.devolucao_de_correspondencias VALUES ($codBarras, $razao_social, $motivo, $data_e_hora);
        // SELECT razaosoc FROM cs2.cadastro WHERE
    } else {
        //echo "Sem c&oacute;digo do cliente inserido!";
        if(mysql_fetch_array($sql)>0) {
            echo "<script>alert('Codigo incorreto!');</script>";
        }
    }
}

//echo "<pre>";
//print_r($_SESSION['itens']);

if(empty(trim($_POST["enviar"]))){
    //echo "nome vazio" ;
} else {
    if (!empty(trim($_POST["enviar"]))) {
        //echo "Inserir dados" ;
        $objConexao = new DbConnection();
        $cnt = 0;
        foreach ($_SESSION['itens'] as $k => $v) {
            $codigo = $_SESSION['itens'][$k]['codCliente'];
            $mot = $_SESSION['itens'][$k]['motivo'];
            $dthr = $_SESSION['itens'][$k]['data_e_hora'];

            $sql_add = "insert into cs2.devolucao_de_correspondencias(cliente, motivo) values ('$codigo', '$mot');";
//            echo "<pre>";
//            print_r($sql_add);

            /**
             * Preparando SQL
             */
            $pdo = $objConexao->pdo->prepare($sql_add);
            //print_r($pdo);die();
            /**
             * Substituindo paramentros por seus valores e executa comando SQL
             */
            $pdo->execute();
        }

        echo "<script>alert('Dados inseridos na base de dados!');</script>";

        session_unset();
    }
}

echo "<body>
<td colspan='2'>DEVOLU&Ccedil;&Atilde;O DE CORRESPOND&Ecirc;NCIA<br></td>
    <form name='formulario' id='form1' method='post'>
        <p>Motivo Devolu&ccedil;&atilde;o: <select name='motivo'>
            <option value='Mudou-se'>Mudou-se</option>
            <option value='Endere&ccedil;o Insuficiente'>Endere&ccedil;o Insuficiente</option>
            <option value='N&atilde;o existe o n&&uacute;mero indicado'>N&atilde;o existe o n&&uacute;mero indicado</option>
            <option value='Desconhecido'>Desconhecido</option>
            <option value='N&atilde;o procurado'>N&atilde;o procurado</option>
            <option value='Ausente'>Ausente</option>
            <option value='Falecido'>Falecido</option>
            <option value='Recusado'>Recusado</option>
            <option value='Inf. escrita pelo porteiro ou s&iacute;ndico'>Inf. escrita pelo porteiro ou s&iacute;ndico</option>
            <option value='Outros'>Outros</option>
            </select>
        </p>
        <p>C&oacute;digo cliente: <input style='margin-left: 26px;' type='text' name='codBarras' ></p>
        <br>
    </form>

    <br>
    <hr style='margin-right: 25%;'/>";

if (!empty($_SESSION['itens'])) {
    echo "<p>Dados a serem inseridos:</p>
            <table id='tabela' style='border:1px solid;  margin-rigth: 0;'>
                <tr style='border:1px solid';>
                    <td style='border:1px solid';>C&oacute;digo</td>
                    <td style='border:1px solid';>Cliente</td>
                    <td style='border:1px solid';>Motivo</td>
                    <td style='border:1px solid';>Data/Hora</td>
                </tr>";
    $cont = count($_SESSION['itens']);
    //echo"<pre>";

    rsort($_SESSION['itens']);
    //array_reverse($_SESSION['itens']);
    //print_r(rsort($_SESSION['itens']['data_e_hora']));
    foreach ($_SESSION['itens'] as $k => $v) {

        echo "<tr style='border:1px solid';>
                <td style='border:1px solid';>"; echo $_SESSION['itens'][$k]['codCliente']; echo" </td>";
        echo "<td style='border:1px solid';>"; echo $_SESSION['itens'][$k]['razaosoc']; echo" </td>";
        echo "<td style='border:1px solid';>"; echo $_SESSION['itens'][$k]['motivo']; echo " </td>";
        echo "<td style='border:1px solid';>"; echo $_SESSION['itens'][$k]['data_e_hora']; echo " </td>";
        $cont--;
        echo " </tr>";
    }

    echo "</table>
        <br><br>
            <hr style='margin-right: 25%;'/>

     <form action='' method='post''>
            <input type='submit' id='enviar' name='enviar' value='Confirmar Registro'>
     </form>
        </body>";
  } else{
    echo "Sem itens a exibir!";
}
?>