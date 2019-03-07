<?php
/**
 * @file DepartamentoFuncoes.php
 * @brief
 * @author ARLLON DIAS
 * @date 26/01/2017
 * @version 1.0
 **/

require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";


if ($_POST) {

    $action = $_POST['action'];

    switch ($action) {

        case 'adicionarDepartamento' :

            $idFranquia = $_POST['iptFranquia'];
            $strDepartamento = $_POST['iptDepartamentoDescricao'];

            $sql = "INSERT INTO cs2.departamento(id_franquia, descricao, ativo)
                    VALUES('$idFranquia','$strDepartamento','1')";

            $rs = mysql_query($sql, $con);

            $arrResult['id'] = 0;
            if ($rs) {
                $arrResult['id'] = mysql_insert_id($con);
            }

            echo json_encode($arrResult);
            die;
            break;

        case 'adicionarFuncao' :

            $strFuncao = $_POST['iptFuncaoDescricao'];
            $idDepartamento = $_POST['iptDepartamento'];
            $idFranquia = $_POST['iptFranquia'];

            $sql = "INSERT INTO cs2.funcao(id_franquia,id_departamento,descricao,ativo)
                    VALUES('$idFranquia','$idDepartamento','$strFuncao','1')";


            $rs = mysql_query($sql, $con);

            $arrResult['id'] = 0;
            if ($rs) {
                $arrResult['id'] = mysql_insert_id($con);
            }

            echo json_encode($arrResult);
            die;
            break;

        case 'listarFuncoesByDepartamento' :


            $idDepartamento = $_POST['iptDepartamento'];
            $idFranquia = $_POST['idFranquia'];

            $sql = "SELECT
                    f.id,
                    d.descricao AS departamento,
                    f.descricao AS funcao,
                    f.ativo AS ativo,
                    IF(f.ativo = 1, 'Ativo', 'Inativo') AS ativo_label
                FROM cs2.funcao f
                INNER JOIN cs2.departamento d
                ON d.id = f.id_departamento
                WHERE (d.id = '$idDepartamento' || $idDepartamento = 0)
                AND f.ativo = 1";

            $res = mysql_query($sql, $con);

            $html = '';
            if ($res) {
                while ($arrResult = mysql_fetch_array($res)) {

                    $strSpan = '<span class="glyphicon glyphicon-repeat btnReativarFuncao cursorpointer"></span>';
                    if ($arrResult['ativo'] == 1) {
                        $strSpan = '<span class="glyphicon glyphicon-remove btnRemoverFuncao cursorpointer" style="color: red"></span>';
                    }

                    $html .= '<tr data-id="' . $arrResult['id'] . '">
                                    <td>' . $arrResult['departamento'] . '</td>
                                    <td>' . $arrResult['funcao'] . '</td>
                                    <td class="text-center">' . $strSpan . '</td>
                                </tr>';

                }
            } else {
                $html = '<tr>
                            <td colspan="4">Nenhum Registro Encontrado</td>
                        </tr>';
            }

            echo json_encode($html);

            die;
            break;

        case 'removerFuncao':

            $id = $_POST['id'];

            $sqlFuncionario = "SELECT
                                      COUNT(*) AS qtd
                               FROM cs2.funcionario
                                WHERE id_funcao = '$id'";

            $resFunc = mysql_query($sqlFuncionario, $con);

            $temFuncionario = mysql_result($resFunc, 0, 'qtd');

            if ($temFuncionario == 0) {


                $sql = "UPDATE cs2.funcao SET ativo = 0 WHERE id = '$id'";

                $res = mysql_query($sql, $con);
                $arrRetorno['retorno'] = 0;

                if ($res) {
                    $arrRetorno['retorno'] = 1;
                }
            }else{
                $arrRetorno['retorno'] = 2;
            }


            echo json_encode($arrRetorno);
            die;
            break;

        case 'adicionarLancamento' :

            $idFuncionario = $_POST['idFuncionario'];
            $tipoLancamento = $_POST['tipo'];
            $tipoLancamento2 = $_POST['tipo'];
            
            if ( $tipoLancamento == 'E' ){
                $tipoLancamento = 'D';
            }
            $valor = str_replace(',', '.', str_replace('.', '', $_POST['valor']));
            $data = data_mysql($_POST['dataLancamento']);
            $descricao = $_POST['descricao'];

            $sql = "INSERT INTO cs2.lancamento_funcionario(id_funcionario,valor,data_folha,tipo_lancamento, data_lancamento, descricao)
                    VALUES('$idFuncionario','$valor','$data','$tipoLancamento', NOW(), '$descricao')";

            $res = mysql_query($sql, $con);
            $id = mysql_insert_id($con);
            
            $arrRetorno['retorno'] = 0;
            if ($res) {
                $arrRetorno['retorno'] = 1;
            }
            if ( $tipoLancamento2 == 'E' ){
                $arrRetorno['retorno'] = 2;
                $arrRetorno['id'] = $id;
                echo json_encode($arrRetorno);
            }else{
                echo json_encode($arrRetorno);
            }
            die;
            break;

        case 'removerLancamento' :

            $id = $_POST['id'];

            $sql = "DELETE FROM cs2.lancamento_funcionario WHERE id = '$id'";
            $res = mysql_query($sql, $con);

            $arrRetorno['retorno'] = 0;
            if ($res) {
                $arrRetorno['retorno'] = 1;
            }

            echo json_encode($arrRetorno);

            die;
            break;

    }

}