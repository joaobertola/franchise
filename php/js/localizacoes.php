<?php
/*
 * Bloco para testar a conexão
 */
try {
	/*
	 * Usei o banco mysql com o PDO.
	 * Se você estiver usando um servidor que não possua acesso ao PDO
	 * Ou não saiba como instalar o PDO, a lógica para trocar por funções mysql ou mysqli 
	 * são visivelmente fáceis.
	 */
	
	/*
	 * Variáveis de conexao
	 */
	$servidor = '10.2.2.3';
	$usuario = 'csinform';
	$senha = 'inform4416#scf';
	$banco = 'cs2';
	
	$con = new PDO("mysql:host={$servidor};dbname={$banco}", $usuario, $senha);
	$con->query('SET NAMES utf8');
	/*
	 * Através deste bloco vamos alterar nossas consultas dinâmicas
	 *
	 * $sql = consulta própria do tipo
	 * $option = titulo que aparece no primeiro option da listagem
	 * $string = auxiliador da busca de resultados
	 */
	switch($_GET['tipo'])
	{
		case 'pais' :     //franquia
			//$sql = "SELECT * FROM dados_paises WHERE status = 1";
      $sql = "select * from franquia where sitfrq='0' order by id";
			$option = 'o Pais';
			$string = 'iso3';
			break;
		/*case 'estado' : 
			$sql = sprintf("SELECT * FROM dados_estados WHERE iso3 = '%s' AND status = 1", $_GET['pais']);
			$option = 'o Estado';
			$string = 'uf';
			break;
    */  
		case 'estado' :
			//$sql = sprintf("SELECT * FROM dados_cidades WHERE uf = '%s'", $_GET['estado']);
      $sql = sprintf("SELECT DISTINCT cidade FROM cs2.cadastro WHERE sitcli < 2 AND id_franquia = '%s'", $_GET['estado']);
      //$sql = "SELECT DISTINCT cidade FROM cs2.cadastro WHERE id_franquia = $id_franquia AND sitcli < 2";
			$option = 'a Cidade';
			$string = 'nome';			
			break;			
		case 'bairro' :
			//$sql = sprintf("SELECT * FROM dados_bairros WHERE cidade = '%s'", utf8_encode($_GET['cidade']));
      $sql = "SELECT DISTINCT bairro FROM cs2.cadastro WHERE cidade = '{$_REQUEST['estado']}'";
      $option = ' o Bairro';
			$string = 'nome';	
			break;			
	}
	/*
	 * Executamos o SQL aqui
	 */
	$consulta = $con->query($sql);
	/*
	 * Iniciamos o resultado como null
	 */
	$resultado = null;
	/*
	 * O primeiro option que vai aparecer já com o titulo dinâmico a partir de $option
	 */
	$resultado = sprintf(
		'<option value="%s">Escolha %s</option>', 
		0,
		$option
	);
	/*
	 * Um espaçador pra ficar elegante
	 */
	$resultado .= sprintf(
		'<option value="%s">- - - - - - - - - - - - -</option>',
		0
	);
	/*
	 * Buscamos os resultados usando $c->string para identificar o ID dinamico pois varia, no caso temos iso3, uf, nome e nome
	 */
	while($c = $consulta->fetch(PDO::FETCH_OBJ)) {
		$resultado .= sprintf(
			'<option value="%s">%s</option>',
			$c->$string,
			// o campo nome é comum a todos
			$c->nome			
		); 
	}
	/*
	 * Imprimimos o resultado
	 */
	print $resultado;
	
} catch(Exception $e) {
	/*
	 * Se não aparecer nada é porque a conexao falhou
	 */
	return null;
}