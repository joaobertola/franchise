<?php

function mensagem_clientes($Codigo,$cSenha){

	global $conexao;
	
	if ( empty($Codigo) ) {
		echo "C&oacute;digo ou Senha Inv&aacute;lidos";
		exit;
	}
	
	$model = new FastTemplate("../consulta/templates/mensagem/");
	$model -> define(array('principal'=>'mensagem_cliente.htm') );

	$model->parse('OUTPUT','principal');
	$model->FastPrint('OUTPUT');

}
?>