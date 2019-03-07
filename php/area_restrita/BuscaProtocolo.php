<?php

require "../connect/conexao_conecta.php";
require "../connect/sessao.php";
require "../connect/funcoes.php";

$id_franquia = $_REQUEST['id_franquia'];
$protocolo = $_REQUEST['protocolo'];
$action = $_REQUEST['action'];

if ( $action == 'pesquisar'){

	$sql = "SELECT discriminacao, operacao, valor from cs2.contacorrente WHERE franqueado = '$id_franquia' AND id = '$protocolo'";
	$qry = mysql_query($sql,$con) or die('Erro SQL');

	if ( mysql_num_rows( $qry) > 0 ){
		$discriminacao = mysql_result($qry, 0, 'discriminacao');
		$operacao = mysql_result($qry, 0, 'operacao');
		$operacao = $operacao == '0' ? 'CREDITO' : 'DEBITO';
		$valor = mysql_result($qry, 0, 'valor');
		$valor = number_format($valor,2,',','.');

		$saida = "<table width=560 border='0' align='center'>
		              <tr>
		                 <td class='subtitulodireita'>Descri&ccedil;&atilde;o</td>
		                 <td class='campoesquerda'>$discriminacao</td>
		              </tr>
		              <tr>
		                 <td class='subtitulodireita'>Opera&ccedil;&atilde;o</td>
		                 <td class='campoesquerda'>$operacao</td>
		              </tr>
		              <tr>
		                 <td class='subtitulodireita'>Valor</td>
		                 <td class='campoesquerda'>R$ $valor</td>
		              </tr>
		              <tr>
		              		<td colspan='2' align='center'>
		                  <input type='button' name='excluir' onClick='Excluir($protocolo,$id_franquia)' value='    Confirmar Exclusão   ' />
		                  &nbsp;&nbsp;&nbsp;&nbsp;
                          <input name='button' type='button' onClick='Cancelar()' value='  Cancelar ' />
                          <td
                      </td>
		              </tr>
		          </table>";
		echo $saida;

	}else{

		echo "0";

	}

} elseif ( $action == 'apaga'){

	$sql = "DELETE FROM cs2.contacorrente WHERE franqueado = '$id_franquia' AND id = '$protocolo'";
	$qry = mysql_query($sql,$con) or die("Erro SQL : $sql ");
	
	echo "Registro Excluido com sucesso !";

}
?>