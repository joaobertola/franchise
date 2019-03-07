<?php

$rel_assistente	 = $_REQUEST['rel_assistente'];
$rel_consultor	 = $_REQUEST['rel_consultor'];
$rel_franquia	 = $_REQUEST['rel_franquia'];

if ( empty($rel_franquia) )  $rel_franquia = $_SESSION['id'];

if ( $rel_franquia == 1 )
    $rel_franquia = 247;

$rel_datai = $_REQUEST['rel_datai'];
$rel_datai = substr($rel_datai,6,4).'-'.substr($rel_datai,3,2).'-'.substr($rel_datai,0,2);
$rel_dataf = $_REQUEST['rel_dataf'];

if ( empty($rel_dataf) )
    $rel_dataf = date('Y-m-d');
else
    $rel_dataf = substr($rel_dataf,6,4).'-'.substr($rel_dataf,3,2).'-'.substr($rel_dataf,0,2);
	

include("connect/conexao_conecta.php");
include("connect/sessao.php");

//echo "<pre>";
//print_r($_REQUEST);
//die;

#tratamento para pesquisa
 
$sql_cont = "WHERE 1=1 ";              


if ( ! empty($rel_assistente) ){
	$xrel_assistente = $rel_assistente;               
	$sql_cont .= " AND id_assistente = '$rel_assistente'";
}else{
	$nome_assistente = 'Nao Informado';
}


if ( ! empty($rel_consultor) ){
	$xrel_consultor = $rel_consultor;
	$sql_cont .= " AND id_consultor = '$rel_consultor'";
}else{
	$xrel_consultor = ' TODOS ';
}

if ( $_REQUEST['tipo_periodo'] == 'dtAge' ){
	$sql_cont .= " AND data_agendamento BETWEEN '$rel_datai' AND '$rel_dataf' ";
	$outros_order = 'data_agendamento';
}else{
	$sql_cont .= " AND data_cadastro BETWEEN '$rel_datai' AND '$rel_dataf' ";
	$outros_order = 'data_cadastro';
}

if ( $rel_franquia <> "TODAS" ){
	$sql_cont .= " AND id_franquia = '$rel_franquia'";
	$sql = "SELECT fantasia FROM cs2.franquia WHERE id = '$rel_franquia'";
	$qry = mysql_query($sql, $con);
	$nom_franquia = mysql_result($qry,0,'fantasia');
}else
	$nom_franquia = 'TODAS AS FRANQUIAS';

if($rel_consultor) {
	$sql = "SELECT * FROM consultores_assistente WHERE id = '$rel_consultor'";
	$qry = mysql_query($sql, $con);
	$xrel_consultor = mysql_result($qry,0,'nome');
}

if ($rel_assistente){
	$sql = "SELECT * FROM consultores_assistente WHERE id = '$rel_assistente'";
	$qry = mysql_query($sql, $con);
	$nome_assistente = mysql_result($qry,0,'nome');
}

if ( $_REQUEST['tipo_periodo'] == 'visit' ){
    include('a_relatorio_aproveitamento.php');
    exit;
};


$sql_pesquisa = " SELECT date_format(data_cadastro,'%d/%m/%Y') as data_cadastro,
                                date_format(hora_cadastro,'%H:%i') as hora_cadastro,
                                date_format(data_agendamento,'%d/%m/%Y') as data_agendamento2, 
                                data_agendamento, hora_agendamento, 
                                id_consultor, empresa, endereco, fone1, fone2, responsavel,
                                status_venda, id, codigo_cliente, id_franquia,
                                date_format(data_venda,'%d/%m/%Y') as data_venda, id_assistente
                 FROM cs2.controle_comercial_visitas 
                 $sql_cont
                 ORDER BY id_franquia, id_assistente, ".$outros_order.", hora_cadastro";

//echo "<pre>";
//print_r($sql_pesquisa);
//die;
         
$qry_pesquisa = mysql_query($sql_pesquisa, $con);
if (@mysql_num_rows($qry_pesquisa) == 0 ){
	echo "	
	<table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
            <tr>
                <td align='center' colspan='12' height='1' bgcolor='#CCCCCC'>
                    <input type='button' value='  Voltar  ' style='cursor:pointer' onClick=\"document.location='painel.php?pagina1=clientes/a_controle_visitas3.php'\"/>
                </td>
            </tr>
            <tr>
                <td align='center' colspan='7' height='1' bgcolor='#CCCCCC'>RELAT&Oacute;RIO DE CONTROLE COMERCIAL</td>
            </tr>
            <tr>
                <td align='center' colspan='7' height='1' bgcolor='#CCCCCC'><font size='2' color='#FF0000'> Nenhum registro encontrado !<b></font></td>
            </tr>	
	</table>";
	exit;
}	

echo "
    <table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
        <tr>
            <td align='center' colspan='12' class=\"titulo\"'>RELAT&Oacute;RIO DE CONTROLE COMERCIAL</td>
        </tr>
        <tr>
            <td colspan='12' height='1' bgcolor='#CCCCCC' align='center'>
                FRANQUIA: $nom_franquia <br>
                ASSISTENTE COMERCIAL : $nome_assistente <br>
                CONSULTOR: $xrel_consultor <br>
                PERIODO : ".$_REQUEST['rel_datai']." a ".$_REQUEST['rel_dataf']."
            </td>
        </tr>
        <tr height='20' bgcolor='87b5ff' style='font-size:s9px'>
            <td height='5' align='left'>Ref.</td>
            <td height='15' align='center'>Data Cadastro</td>
            <td height='15' align='left'>Franquia</td>
            <td height='15' align='left'>Data Agendamento</td>
            <td height='15' align='left'>Assistente</td>
            <td height='15' align='left'>Consultor</td>
            <td height='15' align='left'>Empresa</td>
            <td height='15' align='left' >Fones</td>
            <td height='15' align='left'>Respons&aacute;vel</td>
            <td height='15' align='left'>Data Finaliza&ccedil;&atilde;o</td>
            <td height='15' align='left'>C&oacute;digo do Cliente</td>
            <td height='5' align='left'>Status</td>
        </tr>
        <tr>
            <td colspan='12' height='1' bgcolor='#666666'></td>
        </tr>";

    $registro = 0;
    $ctr_fechado = 0;
    $ctr_nao_fechado = 0;
    $tot_agendamento = 0;
    $ctr_pendente = 0;

    $rel_assistente = $_REQUEST['rel_assistente'];
    $rel_consultor  = $_REQUEST['rel_consultor'];
    $rel_datai      = $_REQUEST['rel_datai'];
    $rel_dataf      = $_REQUEST['rel_dataf'];
    $rel_franquia   = $_REQUEST['rel_franquia'];

	while ( $reg = mysql_fetch_array($qry_pesquisa) ){
		$registro++;
		$data_cadastro       = $reg['data_cadastro'];
		$hora_cadastro       = $reg['hora_cadastro'];
		$data_agendamento    = $reg['data_agendamento2'];
		$hora_agendamento    = $reg['hora_agendamento'];
		$id_consultor        = $reg['id_consultor'];
		$empresa             = $reg['empresa'];
		$endereco            = $reg['endereco'];
		$fone1               = $reg['fone1'];
		$fone2               = $reg['fone2'];
		$responsavel         = $reg['responsavel'];
		$status              = $reg['status_venda'];
		$protocolo           = $reg['id'];
		$data_venda          = $reg['data_venda'];
		$codigo_cliente      = $reg['codigo_cliente'];
		$id_assistente       = $reg['id_assistente'];
		$id_franquia         = $reg['id_franquia'];

        $query = "SELECT * FROM cs2.franquia WHERE id = '$id_franquia'";
		$sql = mysql_query($query, $con);
//        $nome_franquia = mysql_result($sql,0,'fantasia');
        if (!empty($id_franquia)) {
            $nome_franquia = mysql_result($sql,0,'fantasia');
        } else {
            $nome_franquia = '';
        }


        $query = "SELECT * FROM cs2.consultores_assistente WHERE id = '$id_assistente'";
		$sql = mysql_query($query, $con);
//		$assistente = mysql_result($sql,0,'nome');
        if (!empty($id_assistente)) {
            $assistente = mysql_result($sql,0,'nome');
        } else {
            $assistente = '';
        }

        $query = "SELECT * FROM cs2.consultores_assistente WHERE id = '$id_consultor'";
		$sql = mysql_query($query, $con);
		//$consultor = mysql_result($sql,0,'nome');
        if (!empty($id_consultor)){
            $consultor = mysql_result($sql,0,'nome');
        } else {
            $consultor = '';
        }


		$tot_agendamento++;
		
		if ( $status == 'S' ) $ctr_fechado++;
		else if ( $status == 'N' ) $ctr_nao_fechado++;
		else $ctr_pendente++;
		
		if ( $status == 'S' ) $status = "<font color='#0000FF'>$status</font>";
		else if ( $status == 'N' ) $status = "<font color='#FF0000'>$status</font>";
		else $status = "<font color='#009900' size='+1'><b>$status</b></font>";
		
		echo "<tr height='24' style='font-size:9px' ";
		if (($registro%2) <> 0) {
			echo "bgcolor='#E5E5E5'>";
		} else {
			echo ">";
		}
		echo "	
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$protocolo</a>
				</td>
				<td align='center'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$data_cadastro - $hora_cadastro</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$nome_franquia</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$data_agendamento</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$assistente</b></a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$consultor</a>
				</td>
				<td align='left'
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$empresa</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$fone1 - $fone2</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$responsavel</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$data_venda</a>
				</td>
				<td align='left'>
					<a href='painel.php?pagina1=clientes/a_controle_visitas_altera.php&protocolo=$protocolo&b_rel_assistente=$id_assistente&b_rel_consultor=$id_consultor&b_rel_datai=$rel_datai&b_rel_dataf=$rel_dataf&b_rel_franquia=$rel_franquia&assistente=$assistente&origem=1'>$codigo_cliente
				</td>
				<td align='left'>
					$status
				</td>
				";
		echo "</tr>";
	}
	echo "<tr bgcolor='#E5E5E5'>
			<td colspan='50' height='1'>&nbsp;</td>
		</tr>
		<tr bgcolor='#E5E5E5'>
			<td>&nbsp;</td>
			<td><font color='#000000'>Total de Agendamentos:</font><br></td>
			<td><font color='#000000'><b>$tot_agendamento</b></font><br></td>
			<td colspan='9' height='1'></td>
		</tr>
		<tr bgcolor='#E5E5E5'>
			<td>&nbsp;</td>
			<td><font color='#0000FF'>Total de vendas realizadas:</font><br></td>
			<td><font color='#0000FF'><b>$ctr_fechado</b></font><br></td>
			<td colspan='9' height='1'></td>
		  </tr>
		  <tr bgcolor='#E5E5E5'>
			<td>&nbsp;</td>
			<td><font color='#FF0000'>Total de vendas n&atilde;o realizadas:</font><br></td>
			<td><font color='#FF0000'><b>$ctr_nao_fechado</b></font><br></td>
			<td colspan='9' height='1'></td>
		  </tr>
		  <tr bgcolor='#E5E5E5'>
			<td>&nbsp;</td>
			<td><font color='#009900'>Total de VISITAS PENDENTE:</font><br></td>
			<td><font color='#009900'><b>$ctr_pendente</b></font><br></td>
			<td colspan='9' height='1'></td>
		  </tr>
	 	 <tr>
		 	<td colspan='4'>&nbsp;</td>
		</tr>
	 	 <tr>
			<td align='center' colspan='12' height='1' bgcolor='#CCCCCC'>
				<input type='button' value='       Voltar        ' style='cursor:pointer' onClick=\"document.location='painel.php?pagina1=clientes/a_controle_visitas3.php'\"/>
			</td>
		</tr>
	 	 <tr>
		 	<td colspan='4'>&nbsp;</td>
		</tr>				
   </table>";
?>