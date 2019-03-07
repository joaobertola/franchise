<?php

    require "../connect/conexao_conecta.php";
	
    $id_emprestimo = $_REQUEST['id_emprestimo'];
    $id_franquia   = $_REQUEST['id_franquia'];
    $vr_extornado  = $_REQUEST['vr_extornado'];

    #
    $sql = "SELECT b.numero_serie, DATE_FORMAT(a.data_vencimento,'%d/%m/%Y') AS data_vencimento, p.descricao FROM cs2.cadastro_emprestimo_franquia a
            INNER JOIN cs2.franquia_equipamento_descricao b ON a.protocolo = b.id_franquia_equipamento
            INNER JOIN base_web_control.produto p ON p.codigo_barra = b.codigo_barra AND p.id_cadastro = 62735
            WHERE a.id = $id_emprestimo";
    $qry2 = mysql_query($sql, $con) or die ("Erro SQL : $comando");
    while ( $res = mysql_fetch_array($qry2)){
        $numero_serie    = $res['numero_serie'];
        $data_vencimento = $res['data_vencimento'];
        $descricao       =  $res['descricao'];
                
        $msg = "Estorno de Debito ( $descricao - Número Série: $numero_serie ) Venc: $data_vencimento";
        $comando = "INSERT INTO cs2.contacorrente(franqueado,data,discriminacao,valor,operacao)
                    VALUES('$id_franquia',NOW(),'$msg','$vr_extornado','0')";
        $conex = mysql_query($comando, $con) or die ("Erro SQL : $comando");
    }
    
    #
    $comando = "UPDATE cs2.cadastro_emprestimo_franquia 
                    SET data_pagamento = NULL , valor_pagamento = NULL
                WHERE id = '$id_emprestimo'";
    $conex = mysql_query($comando, $con) or die ("Erro SQL : $comando");
    
    
    echo "<script>alert('Parcela ESTORNADA com Sucesso.');</script>";
    mysql_close($con);

    echo "<meta http-equiv='refresh' content=\"0; url=../php/painel.php?pagina1=area_restrita/d_lancamento.php&id_frq=$id_franquia\";>";

?>