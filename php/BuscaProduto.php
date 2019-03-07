<?php
/**
* @file BuscaProduto.php
* @brief
* @author ARLLON DIAS
* @date 06/01/2017
* @version 1.0
**/

require "connect/sessao.php";
require "connect/conexao_conecta.php";

if($_POST){

    switch($_POST['action']){

        case 'buscaProdutoCodigoBarra':

            $codigoBarra = str_replace('-','',$_POST['codigo_barra']);

            $sqlProd = "
                        SELECT
                             descricao,
                             custo_medio_venda,
                             pcc.controle_qtd AS controle_qtd
                       FROM base_web_control.produto p
                       INNER JOIN base_web_control.produto_configuracoes_comercial pcc
                       ON pcc.id_produto = p.id
                       WHERE p.id_cadastro = 62735
                       AND p.codigo_barra = '$codigoBarra'
                       ORDER BY descricao ASC
                            ";

//            echo $sqlProd;
//            die;
            $qryProd = mysql_query($sqlProd,$con);

            $strNomeProd = '';
            $strValorProd = '';
            $controleQtd = 'N';
            // 0: FALSE(NÃO EXISTE PRODUTO)
            $blnResult = 0;
            if(mysql_num_rows($qryProd) > 0){
                // 1: TRUE(EXISTE PRODUTO)
                $blnResult = 1;
                while($rs = mysql_fetch_array($qryProd)){
                    $strNomeProd = $rs['descricao'];
                    $strValorProd = $rs['custo_medio_venda'];
                    $controleQtd = $rs['controle_qtd'];
                }

            }
            $arrProdutos['result'] = $blnResult;
            $arrProdutos['descricao'] = $strNomeProd;
            $arrProdutos['custo_medio_venda'] = $strValorProd;
            $arrProdutos['controle_qtd'] = $controleQtd;

            echo json_encode($arrProdutos);

            break;

        case 'retornaSenha' :

            $sql = 'SELECT senha FROM cs2.funcionario WHERE id = 289';

            $qry = mysql_query($sql,$con);

            $result = mysql_fetch_assoc($qry);

            echo json_encode($result['senha']);

            break;

        case 'estornarConsignacao' :

            $numero_serie = $_POST['numero_serie'];
            $motivo = $_POST['motivo'];
            $tipoEstorno = $_POST['tipoEstorno'];
            $id_franquia = $_POST['id_franquia'];

            if($tipoEstorno == 'C'){

                $sql2 = "INSERT INTO cs2.log_estono_consignacao(
                            id_franquia,
                            numero_serie,
                            id_funcionario,
                            codigo_barra,
                            data_hora,
                            motivo
                            )
                        SELECT
                            '$id_franquia',
                            '$numero_serie',
                            id_funcionario,
                            codigo_barra,
                            NOW(),
                            '$motivo'
                        FROM cs2.franquia_equipamento fe
                        INNER JOIN cs2.franquia_equipamento_descricao fed
                        ON fed.id_franquia_equipamento = fe.id
                        WHERE fed.numero_serie = '$numero_serie';";

                $qry2 = mysql_query($sql2,$con);

                $sql = "DELETE FROM cs2.franquia_equipamento_descricao WHERE numero_serie = '$numero_serie'";

    //            echo $sql;
    //            die;
                $qry = mysql_query($sql,$con);

            }else{

                $sql2 = "INSERT INTO cs2.log_estorno_venda(
                            id_franquia,
                            numero_serie,
                            id_funcionario,
                            codigo_barra,
                            data_hora,
                            motivo
                            )
                         SELECT
                            '$id_franquia',
                            '$numero_serie',
                            f.id,
                            codigo_barra,
                            NOW(),
                            '$motivo'
                        FROM cs2.cadastro_equipamento fe
                        INNER JOIN cs2.cadastro_equipamento_descricao fed
                        ON fed.id_cadastro_equipamento = fe.id
                        LEFT JOIN cs2.funcionario f
                        ON f.id_consultor_assistente = fe.id_consultor
                        WHERE fed.numero_serie = '$numero_serie';";
//                echo '<pre>';
//                echo $sql2;
                $qry2 = mysql_query($sql2,$con);

                $sql = "DELETE FROM cs2.cadastro_equipamento_descricao WHERE numero_serie = '$numero_serie'";

//                            echo $sql;
//                            die;
                $qry = mysql_query($sql,$con);

            }

            if($qry){
                echo json_encode(1);
            }else{
                echo json_encode(0);
            }

            break;




        case 'buscaVendaConsignacao':

            $id = $_POST['id'];

            $sqlProd = "
                        SELECT
                            fe.id,
                            fed.codigo_barra,
                            fed.numero_serie
                        FROM cs2.franquia_equipamento fe
                        INNER JOIN cs2.franquia_equipamento_descricao fed
                        ON fed.id_franquia_equipamento = fe.id
                        WHERE fe.id = '$id'
                            ";

//            echo $sqlProd;
//            die;
            $qryProd = mysql_query($sqlProd,$con);

            $strCodigoBarra = '';
            $strNumeroSerie = '';
            // 0: FALSE(NÃO EXISTE PRODUTO)
            $blnResult = 0;
            if(mysql_num_rows($qryProd) > 0){
                // 1: TRUE(EXISTE PRODUTO)
                $blnResult = 1;
                while($rs = mysql_fetch_array($qryProd)){
                    $strCodigoBarra = $rs['codigo_barra'];
                    $strNumeroSerie = $rs['numero_serie'];
                }

            }
            $arrProdutos['result'] = $blnResult;
            $arrProdutos['codigo_barra'] = $strCodigoBarra;
            $arrProdutos['numero_serie'] = $strNumeroSerie;

            echo json_encode($arrProdutos);

            break;

        case 'excluiConsignacao':

            $id = $_POST['id'];

            $sqlProd = "
                        UPDATE cs2.franquia_equipamento SET consignacao = 'N' WHERE id = '$id';
                            ";

//            echo $sqlProd;
//            die;
            $qryProd = mysql_query($sqlProd,$con);

            echo json_encode($arrProdutos);

            break;



    }

}