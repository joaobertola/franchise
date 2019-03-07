<?php

require("connect/sessao_r.php");

function limpaGeralMascaras($p_paramento){
	$p_paramento = str_replace(".","",$p_paramento);
	$p_paramento = str_replace("-","",$p_paramento);
	$p_paramento = str_replace("/","",$p_paramento);
	$p_paramento = str_replace(" ","",$p_paramento);
	$p_paramento = str_replace("'","",$p_paramento);
	$p_paramento = str_replace("(","",$p_paramento);
	$p_paramento = str_replace(")","",$p_paramento);
	return $p_paramento;
}

function limpaStringMaiscula($p_paramento){
	$p_paramento = str_replace("'","",$p_paramento);
	$p_paramento = strtoupper($p_paramento);
	return $p_paramento;
}

function converteDataGravaBancoFuncionario($p_data_padrao){
       $dia = substr($p_data_padrao, 0,2);
	   $mes = substr($p_data_padrao, 3,2);
	   $ano = substr($p_data_padrao, 6,9);	
	   $data_bd.=$ano;
	   $data_bd.="-";
	   $data_bd.=$mes;
	   $data_bd.="-";
	   $data_bd.=$dia;
	   return $data_bd;
}

//echo "<pre>";
//print_r( $_REQUEST );
//die;


$cpf 	       = limpaGeralMascaras($_REQUEST['cpf']);
$rg            = limpaGeralMascaras($_REQUEST['rg']);
$nome          = limpaStringMaiscula($_REQUEST['nome']);
$funcao        = $_REQUEST['funcao'];
$horario       = $_REQUEST['horario'];
$salario       = $_REQUEST['salario'];
$salario_bruto = str_replace(".","",$salario);
$salario_bruto = str_replace(",",".",$salario_bruto);
$vt            = $_REQUEST['vt'];
$vt            = str_replace(".","",$vt);
$vt            = str_replace(",",".",$vt);
$adiantamento  = $_REQUEST['adiantamento'];
$adiantamento  = str_replace(".","",$adiantamento);
$adiantamento  = str_replace(",",".",$adiantamento);
$tp_conta      = $_REQUEST['tp_conta'];
$banco         = $_REQUEST['banco'];
$nr_banco      = $_REQUEST['nr_banco'];
$agencia       = $_REQUEST['agencia'];
$conta         = $_REQUEST['conta'];
$cep           = limpaGeralMascaras($_REQUEST['cep']);
$endereco      = limpaStringMaiscula($_REQUEST['endereco']);
$numero        = limpaStringMaiscula($_REQUEST['numero']);
$complemento   = limpaStringMaiscula($_REQUEST['complemento']);
$bairro	       = limpaStringMaiscula($_REQUEST['bairro']);
$cidade	       = limpaStringMaiscula($_REQUEST['cidade']);
$fone1	       = limpaGeralMascaras($_REQUEST['fone1']);
$fone2	       = limpaGeralMascaras($_REQUEST['fone2']);
$data_admissao = converteDataGravaBancoFuncionario($_REQUEST['data_admissao']);
$data_demissao = converteDataGravaBancoFuncionario($_REQUEST['data_demissao']);
$obs           = $_REQUEST['obs'];
$senha         = $_REQUEST['senha'];
$ativo         = $_REQUEST['ativo'];
$id_franquia   = $_REQUEST['id_franquia'];
$id_empregador = $_REQUEST['id_empregador'];
$comissao_afiliacao   = $_REQUEST['iptComissaoAfiliacao'];
$comissao_afiliacao   = str_replace(".","",$comissao_afiliacao);
$comissao_afiliacao   = str_replace(",",".",$comissao_afiliacao);
$comissao_equipamento = $_REQUEST['iptComissaoEquipamentos'];
$comissao_equipamento = str_replace(",",".",$comissao_equipamento);
$veiculo = $_REQUEST['iptCarro'];
$placa = $_REQUEST['iptPlaca'];
$consultor_assistente = $_REQUEST['consultor_assistente'];
$id_consultor_assistente = $_REQUEST['id_consultor_assistente'];

if($_REQUEST['acao'] == 'G'){
//VERIFICA SE O CPF ENVIADO JA ESTÁ CADATRADO CASO SIM VAI PARA A TELA DE CADASTO E NAO FAZ NADA
$sql = "SELECT id FROM cs2.funcionario WHERE cpf = '$cpf'";
$qry = mysql_query($sql, $con);
$id = mysql_result($qry,0,'id');
$total = mysql_num_rows($qry);
if($total > 0){ ?>
	<script language="javascript">
		alert("O CPF informado ja esta cadastrado ! ");
        window.location.href="painel.php?pagina1=Franquias/funcionario_alterar.php&id=<?=$id?>";
    </script>
<?php
exit;
}

	$sql = "INSERT INTO cs2.funcionario(
                obs,
                adiantamento,
                nr_banco,
                vt,
                cpf,
                rg,
                nome,
                funcao,
                salario_bruto,
                salario,
                tp_conta,
                banco,
                agencia,
                conta,
                cep,
                endereco,
                numero,
                complemento,
                bairro,
                cidade,
                fone1,
                fone2,
                data_admissao,
                horario,
                id_franqueado,
                id_empregador,
                comissao_afiliacao,
                comissao_equipamento,
                veiculo,
                placa,
                id_funcao,
                consultor_assistente,
                id_consultor_assistente
            )
            VALUES(
                '$obs',
                '$adiantamento',
                '$nr_banco',
                '$vt',
                '$cpf',
                '$rg',
                '$nome',
                '$funcao',
                '$salario_bruto',
                '$salario',
                '$tp_conta',
                '$banco',
                '$agencia',
                '$conta',
                '$cep',
                '$endereco',
                '$numero',
                '$complemento',
                '$bairro',
                '$cidade',
                '$fone1',
                '$fone2',
                '$data_admissao',
                '$horario',
                '$id_franquia',
                '$id_empregador',
                '$comissao_afiliacao',
                '$comissao_equipamento',
                '$veiculo',
                '$placa',
                '$funcao',
                '$consultor_assistente',
                '$id_consultor_assistente'
            )";
	$qry = mysql_query($sql, $con) or die ("Falha ao gravar o Funcionario ".$sql);
	$id = mysql_insert_id($con);
	
	if($qry){ ?>
            <script language="javascript">
		window.location.href="painel.php?pagina1=Franquias/funcionario_alterar.php&id=<?=$id?>";
            </script>
        <?php 
        }else{ ?>
            <script language="javascript">
		alert("Erro ao cadastrar o Funcionario ! ");
		window.location.href="painel.php?pagina1=Franquias/funcionario_novo.php";
            </script>	
	<?php }
}

if($_REQUEST['acao'] == 'A'){
    $sql = "UPDATE cs2.funcionario SET
                adiantamento  = '$adiantamento',
                rg            = '$rg',
                cpf           = '$cpf',
                vt            = '$vt',            
                nome	      = '$nome', 
                funcao	      = '$funcao',
                salario_bruto = '$salario_bruto', 
                id_funcao     = '$funcao',
                salario	      = '$salario', 
                tp_conta      = '$tp_conta', 
                banco	      = '$banco',
                nr_banco      = '$nr_banco',
                agencia	      = '$agencia', 
                conta	      = '$conta', 
                cep           = '$cep', 
                endereco      = '$endereco', 
                numero	      = '$numero', 
                complemento   = '$complemento', 
                bairro	      = '$bairro', 
                cidade	      = '$cidade', 
                fone1	      = '$fone1', 
                fone2	      = '$fone2', 
                data_admissao = '$data_admissao',
                ativo         = '$ativo',
                horario       = '$horario',
                senha         = '$senha',
                data_demissao = '$data_demissao',
                obs           = '$obs',
                id_franqueado = '$id_franquia',
                id_empregador = '$id_empregador',
                comissao_afiliacao = '$comissao_afiliacao',
                comissao_equipamento = '$comissao_equipamento',
                veiculo  = '$veiculo',
                placa = '$placa',
                consultor_assistente    = '$consultor_assistente',
                id_consultor_assistente = '$id_consultor_assistente'
            WHERE id = '{$_REQUEST['id']}'";
    $qry = mysql_query($sql, $con);
  
    if($qry){ ?>
        <script language="javascript">
                window.location.href="painel.php?pagina1=Franquias/funcionario_alterar.php&id=<?=$_REQUEST['id']?>";
        </script>
    <?php }else{ ?>
        <script language="javascript">
            alert("Erro ao alterar o Funcionario ! ");
            window.location.href="painel.php?pagina1=Franquias/funcionario_alterar.php&id=<?=$_REQUEST['id']?>";
        </script>	
    <?php }
}

?>