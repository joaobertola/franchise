<?php

    require "connect/conexao_conecta.php";

    $codloja     = $_REQUEST['codloja'];
    $iptIdVenda  = $_REQUEST['iptIdVenda'];
    $data_compra = $_REQUEST['data_compra'];
    $data_compra = substr($data_compra,6,4)."-".substr($data_compra,3,2)."-".substr($data_compra,0,2);
    $vr_desconto = $_REQUEST['valor_desconto'];
    
    // Verifica se existe algum pagamento, se nao houver, retorna com a mensagem que nao ha recebimento para esta venda
    
    $sql = "SELECT count(*) AS qtd FROM cs2.cadastro_equipamento_pagamento
            WHERE id_venda = '$iptIdVenda'";
    $res = mysql_query($sql,$con);
    $qtd = mysql_result($res,0,'qtd');
    if ( $qtd == 0 ){
        echo "<script>alert('Nao há recebimento para esta venda.');history.back()</script>";
        die;
    }

    $sql = "UPDATE cs2.cadastro_equipamento
                SET
                    data_compra = '$data_compra',
                    vr_desconto = '$vr_desconto',
                    venda_finalizada = 'S'
            WHERE id = $iptIdVenda";
    $res = mysql_query($sql,$con);

    // Verificando se o cliente ja esta cadastrado.
    $sql = "SELECT insc FROM cs2.cadastro WHERE codloja = $codloja";
    $qry = mysql_query($sql,$con);
    $insc = mysql_result($qry,0,'insc');

    $sql = "SELECT id FROM base_web_control.cliente WHERE id_cadastro = 62735 AND cnpj_cpf = $insc";   
    $qry = mysql_query($sql,$con);
    $id = mysql_result($qry,0,'id');
    
    if ( $id == 0 or $id == '' ){
            // Buscando dados do cliente para cadastramento junto ao webcontrol empresas
            $sql = "INSERT INTO base_web_control.cliente
                        (id_cadastro, id_usuario, cnpj_cpf, inscricao_estadual, inscricao_municipal, tipo_pessoa, nome, 
                        razao_social, id_tipo_log, endereco, numero, complemento, bairro, cidade, uf, cep, email, telefone, celular,
                        socio1, socio2)
                    SELECT 
                        62735, 6, insc, inscricao_estadual, inscricao_municipal, 'J', nomefantasia, 
                        razaosoc, 1, end, numero, complemento, bairro, cidade, uf, cep, email, fone, fax,
                        socio1, socio2
                    FROM
                        cs2.cadastro 
                    WHERE 
                        codloja = $codloja";
          
            $res = mysql_query($sql,$con);
    }
    
//    print_r($sql);
//    die;
           
    // Selecionando todos os produtos vendido e gravando para as franquia.
    $sql_produtos_vendidos = "SELECT c.descricao, b.numero_serie, ( (b.valor_unitario * c.comissao_valor) / 100) as valor_comissao, d.id_franquia
                              FROM cs2.cadastro_equipamento a
                              INNER JOIN cs2.cadastro_equipamento_descricao b ON a.id = b.id_cadastro_equipamento
                              INNER JOIN base_web_control.produto c           ON c.codigo_barra = b.codigo_barra
                              INNER JOIN cs2.cadastro d                       ON a.codloja = d.codloja
                              WHERE a.id = $iptIdVenda AND c.id_cadastro = 62735 ";
    $qry_produtos_vendidos = mysql_query($sql_produtos_vendidos,$con);
    while ( $res = mysql_fetch_array($qry_produtos_vendidos)){
        $id_franquia = $res['id_franquia'];
        $descricao = $res['descricao'];
        $numero_serie = $res['numero_serie'];
        $valor_comissao = $res['valor_comissao'];
        $msg = 'Comissao ('.$descricao.' Serie: '.$numero_serie.')';
        if ( trim($numero_serie) != '' ){
            $sql_ins_com = "INSERT INTO cs2.contacorrente(franqueado, data, discriminacao, valor, operacao, numero_serie) "
                         . "VALUES( $id_franquia, NOW(), '$msg', $valor_comissao, '0', '$numero_serie' )";
        }else{
            $sql_ins_com = "INSERT INTO cs2.contacorrente(franqueado, data, discriminacao, valor, operacao) "
                         . "VALUES( $id_franquia, NOW(), '$msg', $valor_comissao, '0' )";
        }
       
        $qry_ins_com = mysql_query($sql_ins_com,$con); // or die ( "Erro ao gravar comissao franquia");
        
        // gravando o pagamento da comissao
        if ( trim($numero_serie) != '' ){ 
            // somente será gravado em qual registro se tiver o numero de serie
            // exemplo: bobina nao será gravado o registro referenciado.
            $data = date('d/m/Y');
            $vlrcomissao = number_format($valor_comissao,2,'.',',');
            $txt = "COMISSAO 7% CREDITADA NO DIA: $data - VALOR:  R$ $vlrcomissao";
            $sql_update = "SELECT id FROM cs2.cadastro_emprestimo_franquia WHERE descricao_deposito like '%$numero_serie%'";
            $qry_update = mysql_query($sql_update,$con);
            while ( $res_update = mysql_fetch_array($qry_update)){
                $id_update = $res_update['id'];
                if ( $id_update > 0 ){
                    
                    // gravando no log para monitoriamento, temporario até achar onde está o BUG
                    $sql_update_final = 'UPDATE cs2.cadastro_emprestimo_franquia SET observacao = ['.$txt.'] WHERE id = '.$id_update;
                    
                    $sql_x = "INSERT INTO cs2.monitoramento_comissao_equipamento_franquia(comando_sql)
                              VALUES('$sql_update_final')";
                    $qry_update_final = mysql_query($sql_x,$con);
                    
                    if ( trim( $id_update ) == '' ){
                        echo "ENTRE EM CONTATO COM O T.I URGENTE<BR>REGISTRO COMISSAO NAO LANÇADA NA TABELA 'cs2.cadastro_emprestimo_franquia'";
                        die;
                    }else{
                      $sql_update_final = "UPDATE cs2.cadastro_emprestimo_franquia SET observacao = '$txt' WHERE id = '$id_update'";
                      $qry_update_final = mysql_query($sql_update_final,$con);
                    }
                }
            }
        }
    }
    
    
    echo "1";

?>