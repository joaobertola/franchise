<?php

require "../connect/sessao.php";
include("../connect/conexao_conecta.php");

function fTransformaRealParaBd($p_paramento){

    return $p_paramento;
}

function fConverteDataGravaBanco($p_data_padrao){
    $dia = substr($p_data_padrao, 0,2);
    $mes = substr($p_data_padrao, 3,2);
    $ano = substr($p_data_padrao, 6,9);	
    $data_bd.=$ano;
    $data_bd.="-";
    $data_bd.=$mes;
    $data_bd.="-";
    $data_bd.=$dia;
    return ($data_bd);
} 

$contano = $_REQUEST['contano'];
$contmes = $_REQUEST['contmes'];
$opcao   = $_REQUEST['opcao'];
$ordenacao = $_REQUEST['ordenacao'];
$canceladoprecancelado = $_REQUEST['canceladoprecancelado'];
$franqueado = $_REQUEST['franqueado'];


if($_REQUEST['acao'] == '1'){
	
    $escolhe = $_REQUEST['destino_pgto'];

    if ( $escolhe == 'VVI' ){
            
        if( ($_REQUEST['data_pgto'] != '') and ($_REQUEST['valor_pgto'] != '') ){
            
            $valor_pgto  = fTransformaRealParaBd(str_replace(".00", "",$_REQUEST['valor_pgto']));
            $data_pgto   = fConverteDataGravaBanco($_REQUEST['data_pgto'] );
            $sql = "UPDATE  cadastro SET 
                            dt_pgto_comissao_vendedor = '$data_pgto',
                            valor_comissao_vendedor   = '$valor_pgto'
                    WHERE
                            codloja = '{$_REQUEST['codloja']}'";
            $qry = mysql_query($sql, $con) or die("Erro SQL : $sql");
            header("Location: ../painel.php?pagina1=Franquias/b_extrato.php&contano=$contano&contmes=$contmes&opcao=$opcao&ordenacao=$ordenacao&canceladoprecancelado=$canceladoprecancelado&franqueado=$franqueado&ok_baixa=1");

        } else {
            echo "<script>alert('Todos os campos s�o obrigatorios ! ');history.back()</script>";
        }
                
    }elseif ( $escolhe == 'FIX' ){

        // BAIXA DE PAGAMENTO DE FIXO
        if( ($_REQUEST['data_pgto'] != '') and ($_REQUEST['valor_pgto'] != '') ){
            $valor_pgto   = fTransformaRealParaBd($_REQUEST['valor_pgto']);
            $data_pgto    = fConverteDataGravaBanco($_REQUEST['data_pgto'] );	
            $sql = "UPDATE  cadastro SET 
                        dt_pgto_fixo = '$data_pgto',
                        vr_pgto_fixo = '$valor_pgto'
                    WHERE
                        codloja = '{$_REQUEST['codloja']}'";
            $qry = mysql_query($sql, $con) or die("Erro SQL : $sql");

            header("Location: ../painel.php?pagina1=Franquias/b_extrato.php&contano=$contano&contmes=$contmes&opcao=$opcao&ordenacao=$ordenacao&canceladoprecancelado=$canceladoprecancelado&franqueado=$franqueado&ok_baixa=1");
				
    	} else {
            echo "<script>alert('Todos os campos s�o obrigatorios ! ');history.back()</script>";
        }
    }
    elseif ( $escolhe == 'ADE' ){

        // BAIXA DE PAGAMENTO DE FIXO
        if( ($_REQUEST['data_pgto'] != '') and ($_REQUEST['valor_pgto'] != '') ){
            
            $valor_pgto   = fTransformaRealParaBd($_REQUEST['valor_pgto']);
            $data_pgto    = fConverteDataGravaBanco($_REQUEST['data_pgto'] );	
            $sql = "UPDATE  cadastro SET 
                        dt_pgto_adesao = '$data_pgto',
                        vr_pgto_adesao = '$valor_pgto'
                    WHERE
                        codloja = '{$_REQUEST['codloja']}'";
            $qry = mysql_query($sql, $con) or die("Erro SQL : $sql");

            header("Location: ../painel.php?pagina1=Franquias/b_extrato.php&contano=$contano&contmes=$contmes&opcao=$opcao&ordenacao=$ordenacao&canceladoprecancelado=$canceladoprecancelado&franqueado=$franqueado&ok_baixa=1");
				
    	} else {
            echo "<script>alert('Todos os campos s�o obrigatorios ! ');history.back()</script>";
        }
    }
}
?>