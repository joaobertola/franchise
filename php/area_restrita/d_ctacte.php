<?php

// require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv='refresh' content='0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php';>";
//	die;
//}

$comando = "SELECT id, razaosoc, fantasia from franquia 
            WHERE classificacao <> 'J' and tipo ='b' order by id";
$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0") {
	echo "<table width='440' border='0' cellpadding='0' cellspacing='0'>
			<tr>
				<td align='center' class='titulo'>Nenhum franqueado cadastrado!</td>
			</tr>
		</table>";
	} else {
	echo "<div class='titulo'>LAN&Ccedil;AMENTOS EM CONTA CORRENTE</div><br>";
	echo "<table width='750' border='0' cellpadding='0' cellspacing='0' class='bodyText' align='center'>
	 		<tr>
				<td colspan='10' height='1' bgcolor='#999999'></td>
			</tr>
			<tr height='20' class='titulo'>
				<th align='center'>C&oacute;digo</th>
				<th align='center'>Nome da Franquia</th>
				<th align='center'>Raz&atilde;o Social</th>
				<th align='center'><font color='#006666'>Lan&ccedil;ar</font>&nbsp;</th>
				<th align='center'><font color='#006666'>Excluir</font>&nbsp;</th>
				<th align='center'><font color='#006666'>Mostrar</font></th>
				<th align='center'><font color='#006666'>&nbsp;</font></th>
			</tr>
			<tr>
				<td colspan='10' height='1' bgcolor='#666666'>
				</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$id = $matriz['id'];
	  	$razao = $matriz['razaosoc'];
	  	$nome = $matriz['fantasia'];
	  	echo "<tr ";
				if (($a%2) == 0) {
			echo "bgcolor='#E5E5E5'>";
		} else {
			echo ">";
		}
	  	echo " 	<td class='tabela' align='center'>$id</td>
	  	      	<td class='tabela'>
	  	      		<a href='painel.php?pagina1=area_restrita/d_lancamento.php&id_frq=$id'><font color='#0000ff'>$nome</font></a>
  	      		</td>
	  	      	<td class='tabela' align='left'>$razao</td>
			  	<td class='tabela' align='center'>
			  		<a href='painel.php?pagina1=area_restrita/d_ctaincluir.php&id=$id' onMouseOver='window.status='Lan&ccedil;amento'; return true'><IMG SRC='../img/edit.gif' border='0'></a>
			  	</td>
			  	<td class='tabela' align='center'>
			  		<a href='painel.php?pagina1=area_restrita/d_ctaexcluir.php&id=$id' onMouseOver='window.status='Exclus&atilde;o de Lan&ccedil;amentos'; return true'><IMG SRC='../img/delete.png' border='0'></a>
			  	</td>
			  	
			  	<td class='tabela' align='center'>
			  		<a href='painel.php?pagina1=area_restrita/d_lancamento.php&id_frq=$id' onMouseOver='window.status='Mostrar'; return true'><IMG SRC='../img/alt.gif' border='0'></a>
			  	</td>
				<td class='tabela' align='center'>
					<a href='painel.php?pagina1=area_restrita/d_limite.php&id=$id' onMouseOver='window.status='Limite de Credito para Antecipacao'; return true'> Limite </a>
				</td>
								
	  	      	</tr>";
		}
		echo "<tr>
				<td colspan='6' align='right' height='1' bgcolor='#666666'>
				</td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>