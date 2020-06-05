<?php

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$id_franquia = $_REQUEST['id_franquia'];

if ( $id_franquia == 4 || $id_franquia == 5 || $id_franquia == 163 || $id_franquia == 247 )
	$id_franquia = 1;

	if ($_REQUEST) {
		$html = '';
		$html2 = '';
		switch ($_REQUEST['action']) {

			case 'buscarConsultorAgendador' :

				$sit = $_REQUEST['ativo'];

				if ( $sit == 'S' ) $situacao = " AND situacao = '0' ";
				else $situacao = " AND situacao IN('0', '1') ";

				$sql_sel = "SELECT
				*
				FROM cs2.consultores_assistente
				WHERE id_franquia = '$id_franquia'
				AND tipo_cliente = '0'
				$situacao
				ORDER BY situacao, nome";

				$qry = mysql_query($sql_sel,$con);
				while ($rs = mysql_fetch_array($qry)) {
					if ($rs['situacao'] == "0") {
						$sit = "Ativo";
					} elseif ($rs['situacao'] == "1") {
						$sit = "Bloqueado";
					} elseif ($rs['situacao'] == "2") {
						$sit = "Cancelado";
					}
					$html .= "<option value='" . $rs['id'] . "'>" . $rs['nome'] . " - " . $sit . "</option>";
				}

				$sql_sel = "SELECT
				*
				FROM cs2.consultores_assistente
				WHERE id_franquia = '$id_franquia'
				AND tipo_cliente = '1'
				AND situacao IN('0','1')
				ORDER BY situacao, nome";

				$qry = mysql_query($sql_sel,$con);
				while ($rs = mysql_fetch_array($qry)) {
					if ($rs['situacao'] == "0") {
						$sit = "Ativo";
					} elseif ($rs['situacao'] == "1") {
						$sit = "Bloqueado";
					} elseif ($rs['situacao'] == "2") {
						$sit = "Cancelado";
					}
					$html2 .= "<option value='" . $rs['id'] . "'>" . $rs['nome'] . " - " . $sit . "</option>";

				}
				echo $html . ';' .$html2;
				//echo $html2;

				break;
			case 'buscarConsultorAgendadorEquipamento' :

				$sit = $_REQUEST['ativo'];
				$situacao = " AND ativo = 'S' ";

				$sql_sel = "SELECT * from cs2.funcionario
				WHERE (funcao = 10 or id_funcao = 10)
				and id_franqueado = ". $id_franquia . $situacao;

				$qry = mysql_query($sql_sel,$con);
				while ($rs = mysql_fetch_array($qry)) {
					if ($rs['situacao'] == "0") {
						$sit = "Ativo";
					} elseif ($rs['situacao'] == "1") {
						$sit = "Bloqueado";
					} elseif ($rs['situacao'] == "2") {
						$sit = "Cancelado";
					}
					$html .= "<option value='" . $rs['id'] . "'>" . $rs['nome'] . " - " . $sit . "</option>";
				}

				$sql_sel = "SELECT
				*
				FROM cs2.consultores_assistente
				WHERE id_franquia = '$id_franquia'
				AND tipo_cliente = '1'
				AND situacao IN('0','1')
				ORDER BY situacao, nome";

				$qry = mysql_query($sql_sel,$con);
				while ($rs = mysql_fetch_array($qry)) {
					if ($rs['situacao'] == "0") {
						$sit = "Ativo";
					} elseif ($rs['situacao'] == "1") {
						$sit = "Bloqueado";
					} elseif ($rs['situacao'] == "2") {
						$sit = "Cancelado";
					}
					$html2 .= "<option value='" . $rs['id'] . "'>" . $rs['nome'] . " - " . $sit . "</option>";

				}
				echo $html . ';' .$html2;
				//echo $html2;

				break;

			case 'buscarFuncionario' :

				if ( $id_franquia == 1 ){
					$sql_sel = "SELECT id, nome FROM cs2.funcionario WHERE id_empregador IN (1,2) and ativo = 'S'
                            ORDER BY nome";
					$qry = mysql_query($sql_sel,$con);
					while ($rs = mysql_fetch_array($qry)) {
						$html .= "<option value='" . $rs['id'] . "'>" . $rs['nome'] ."</option>";
					}
					$html2 = '';
					$html3 = '';
					$sqlFuncionario = "SELECT
											id,
											nome
										FROM cs2.funcionario
										WHERE id_funcao = 19 OR id_funcao = 9
										ORDER BY nome ASC;";
					$resFuncionario = mysql_query($sqlFuncionario,$con);
					while ($rs = mysql_fetch_array($resFuncionario)) {
						$html3 .= "<option value='" . $rs['id'] . "'>" . $rs['nome'] ."</option>";
					}

				}else{
					$html = "<option value='0'>Funcionario - Franquia</option>";
					$html2 = '';
				}
				echo $html . ';' .$html2 .';'. $html3;

				break;

			case 'buscarFuncionarioFuncao' :

				$idFuncao = $_POST['idFuncao'];

				$ativo = $_POST['ativo'];
				if(empty($_POST['ativo'])){
					$ativo = 0;
				}

				$sqlFuncionario = "
									SELECT
										id,
										nome
									FROM cs2.funcionario
									WHERE id_funcao = '$idFuncao'
									AND IF('$ativo' = '0', 0=0, ativo = '$ativo')";
				$resFuncao = mysql_query($sqlFuncionario,$con);

				$html = '';
				while($arrFuncionario = mysql_fetch_array($resFuncao)){

					$html.= "<option value='".$arrFuncionario['id']."'>".$arrFuncionario['nome']."</option>";

				}

				echo json_encode($html);

				break;

			case 'buscaEnderecoCEP' :

				$cep = $_REQUEST['cep'];
				$html = 0;
				$sql_sel = "SELECT
				e.endereco,
				c.cidade,
				b.bairro
				FROM cep_brasil.tend_endereco e
				LEFT JOIN cep_brasil.tend_cidade c
				ON e.id_cidade = c.id_cidade
				LEFT JOIN cep_brasil.tend_bairro b
				ON e.id_bairro = b.id_bairro
				WHERE e.cep = '$cep'
				";

				$qry = mysql_query($sql_sel);
				while ($rs = mysql_fetch_array($qry)) {
					$html = $rs['endereco']. '|' .   $rs['bairro']. '|' .  $rs['cidade'] ;

				}
				echo $html;

				break;

			case 'buscaAgendamento' :

// 				echo 'chegou';

				$fone = $_REQUEST['telefone'];

			//	var_dump($fone);die;

				break;

			case 'buscarParcelas' :

				$saida = "<select name='qtd_parcela' id='qtd_parcela'onchange='mostra_parcelas(this.value)' class='qtd_parcela'>";
				if ( $id_franquia == 1 ){
					$saida .= "<option value='99'> CONSIGNACAO </option>";
				}else{
					$saida .= "<option value='0'>.. Selecione ..</option>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                        <option value='6'>6</option>
                        <option value='7'>7</option>
                        <option value='8'>8</option>
                        <option value='9'>9</option>
                        <option value='10'>10</option>
                        <option value='11'>11</option>
                        <option value='12'>12</option>";
				}
				$saida .= "</select>";

				echo $saida;
				break;

			case 'buscarAgendador' :

				$sit = $_REQUEST['ativo'];

				if ( $sit == 'S' ) $situacao = " AND situacao = '0' ";
				else $situacao = " AND situacao IN('0', '1') ";

				$sql_sel = "SELECT
				*
				FROM cs2.consultores_assistente
				WHERE id_franquia = '$id_franquia'
				AND tipo_cliente = '1'
				AND situacao IN('0')
				ORDER BY situacao, nome";

				$qry = mysql_query($sql_sel,$con);
				while ($rs = mysql_fetch_array($qry)) {
					if ($rs['situacao'] == "0") {
						$sit = "Ativo";
					} elseif ($rs['situacao'] == "1") {
						$sit = "Bloqueado";
					} elseif ($rs['situacao'] == "2") {
						$sit = "Cancelado";
					}
					$html2 .= "<option value='" . $rs['id'] . "'>" . $rs['nome'] . " - " . $sit . "</option>";

				}
				echo $html2;
				break;


			case 'buscarAgenCons' :

				$sit = $_REQUEST['ativo'];
				$tip = $_REQUEST['tipo'];
				if ( $tip == 'A' ) $tip = '1';
				else if ( $tip == 'C' ) $tip = '0';
				else $tip = '9';

				if ( $sit == 'S' ) $situacao = " AND situacao = '0' ";
				else $situacao = " AND situacao IN('0', '1') ";

				$sql_sel = "SELECT
				*
				FROM cs2.consultores_assistente
				WHERE id_franquia = '$id_franquia'
				AND tipo_cliente = '$tip'
				AND situacao IN('0')
				ORDER BY situacao, nome";

				$qry = mysql_query($sql_sel,$con);
				while ($rs = mysql_fetch_array($qry)) {
					$sit = "Ativo";
					$html2 .= "<option value='" . $rs['id'] . "'>" . $rs['nome'] . " - " . $sit . "</option>";

				}
				echo $html2;

				break;
		}

	}

	if($_GET){


		switch($_GET['action']){

			case 'buscaAgendamento' :


				$fone = $_REQUEST['telefone'];
				$foneOrig = $fone;
				$fone = str_replace(' ','',str_replace('-','',str_replace(')','',str_replace('(','',$fone))));
				$id_franquia = $_REQUEST['id_franquia'];

				$html = 0;
				$sql_sel = "SELECT
				id,
				DATE_FORMAT(data_cadastro,'%d/%m/%Y') AS data_cadastro,
				DATE_FORMAT(data_agendamento,'%d/%m/%Y') AS data_agendamento,
				fone1,
				fone2,
				responsavel,
				email,
				tipo,
				empresa,
				cep,
				endereco,
				numero,
				bairro,
				cidade,
				uf,
				ponto_referencia,
				observacao,
				agendar_futuro,
				id_assistente,
				id_consultor,
				hora_agendamento,
				triplicar_vendas,
				cad_cliente,
				prod_estoque,
				boletos,
				frente_caixa,
				nota_fiscal,
				consultas_credito,
				site
				FROM cs2.controle_comercial_visitas
				WHERE id_franquia = '$id_franquia'
				";

				if($_REQUEST['tipoTelefone'] == 'telefone'){
					$sql_sel.= " AND REPLACE(REPLACE(REPLACE(REPLACE(fone1,'-',''),'(',''),')',''),' ','') = '$fone'";
				}else{
					$sql_sel.= " AND REPLACE(REPLACE(REPLACE(REPLACE(fone2,'-',''),'(',''),')',''),' ','') = '$fone'";
				}

				$sql_sel.= " ORDER BY id DESC LIMIT 1";

//				            echo $sql_sel;
//				            die;
				$qry = mysql_query($sql_sel,$con);

				while ($rs = mysql_fetch_array($qry)) {
					$html = $rs['id']. '|' .$rs['data_cadastro']. '|' .$rs['data_agendamento']. '|' .$rs['fone1']. '|' .$rs['fone2']. '|' .$rs['responsavel']. '|' .
							$rs['email']. '|' .$rs['tipo']. '|' .$rs['empresa']. '|' .$rs['cep']. '|' .$rs['endereco']. '|' .$rs['numero']. '|' .$rs['bairro']. '|' .
							$rs['cidade']. '|' .$rs['uf']. '|' .$rs['ponto_referencia']. '|' .$rs['observacao']. '|' .$rs['agendar_futuro']. '|' .$rs['id_assistente']. '|' .
							$rs['id_consultor']. '|' .$rs['hora_agendamento']. '|' .$rs['triplicar_vendas']. '|' .$rs['cad_cliente']. '|' .$rs['prod_estoque']. '|' .$rs['boletos']. '|' .
							$rs['frente_caixa']. '|' .$rs['nota_fiscal']. '|' .$rs['consultas_credito']. '|' .$rs['site'];

				}

				$foneOrig = $fone;
				$fone = str_replace(' ','',str_replace('-','',str_replace(')','',str_replace('(','',$fone))));

				$sql_sel = "SELECT
								*
                        FROM cs2.cadastro";
				if($_REQUEST['tipoTelefone'] == 'telefone'){
					$sql_sel.= " WHERE (fone = '$fone' OR fone = '$foneOrig') AND sitcli = 0 AND id_franquia != 2";
				}else{
					$sql_sel.= " WHERE (celular = '$fone' OR celular = '$foneOrig') AND sitcli = 0 AND id_franquia != 2";
				}
				$sql_sel.= " ORDER BY codLoja DESC LIMIT 1;
                        ";
//				            echo $sql_sel;
//				            die;
				$qry = mysql_query($sql_sel,$con);

				while ($rs = mysql_fetch_array($qry)) {
					$html = '1|' .$rs['nomefantasia'] . '|' . $rs['cidade'] . '|'.  $rs['uf'] . '|' .$rs['fone1']. '|' .$rs['fone2']. '|' . $rs['razaosoc'] . '|' . $rs['atendente_resp'] . '|' . $rs['email'] . '|' . $rs['cep'] . '|' . $rs['end'] . '|' . $rs['numero'] . '|' . $rs['bairro'] . '|' . $rs['cidade'] . '|' . $rs['uf'];

				}

				echo $html;

				break;

			case 'buscaAgendamentoNew' :

				$fone = $_REQUEST['telefone'];
				$foneOrig = $fone;
				$fone = str_replace(' ','',str_replace('-','',str_replace(')','',str_replace('(','',$fone))));
				$id_franquia = $_REQUEST['id_franquia'];

				$html = 0;
				$sql_sel = "SELECT
				id,
				DATE_FORMAT(data_cadastro,'%d/%m/%Y') AS data_cadastro,
				DATE_FORMAT(data_agendamento,'%d/%m/%Y') AS data_agendamento,
				fone1,
				fone2,
				responsavel,
				email,
				tipo,
				empresa,
				cep,
				endereco,
				numero,
				bairro,
				cidade,
				uf,
				ponto_referencia,
				observacao,
				agendar_futuro,
				id_assistente,
				id_consultor,
				hora_agendamento,
				triplicar_vendas,
				cad_cliente,
				prod_estoque,
				boletos,
				frente_caixa,
				nota_fiscal,
				consultas_credito,
				site,
				vizinhos
				FROM cs2.controle_comercial_visitas
				WHERE id_franquia = '$id_franquia'
				";

				if($_REQUEST['tipoTelefone'] == 'telefone'){
					$sql_sel.= " AND REPLACE(REPLACE(REPLACE(REPLACE(fone1,'-',''),'(',''),')',''),' ','') = '$fone'";
				}else{
					$sql_sel.= " AND REPLACE(REPLACE(REPLACE(REPLACE(fone2,'-',''),'(',''),')',''),' ','') = '$fone'";
				}

				$sql_cad = "SELECT
									codloja
	                        FROM cs2.cadastro";	
	                        
				if($_REQUEST['tipoTelefone'] == 'telefone'){
					$sql_cad.= " WHERE (fone = '$fone' OR fone = '$foneOrig') AND sitcli = 0 AND id_franquia != 2";
				}else{
					$sql_cad.= " WHERE (celular = '$fone' OR celular = '$foneOrig') AND sitcli = 0 AND id_franquia != 2";
				}

				$sql_cad.= " ORDER BY codLoja DESC LIMIT 1";                        			
				$sql_sel.= " ORDER BY id DESC LIMIT 1";
				

				$qry 	 = mysql_query($sql_sel,$con);
				$qry_cad = mysql_query($sql_cad,$con);

				$codloja = mysql_fetch_array($qry_cad);

				if (!empty($codloja)) {
					$sql_login = "SELECT
										login
		                        FROM base_web_control.webc_usuario
		                        WHERE id_cadastro = $codloja[0]
		                        ORDER BY id DESC LIMIT 1";	

					$qry_login = mysql_query($sql_login,$con);

					$login = mysql_fetch_array($qry_login);
				}

				while ($rs = mysql_fetch_array($qry)) {
					$html = $rs['id']. '|' .$rs['data_cadastro']. '|' .$rs['data_agendamento']. '|' .$rs['fone1']. '|' .$rs['fone2']. '|' .$rs['responsavel']. '|' .
							$rs['email']. '|' .$rs['tipo']. '|' .$rs['empresa']. '|' .$rs['cep']. '|' .$rs['endereco']. '|' .$rs['numero']. '|' .$rs['bairro']. '|' .
							$rs['cidade']. '|' .$rs['uf']. '|' .$rs['ponto_referencia']. '|' .$rs['observacao']. '|' .$rs['agendar_futuro']. '|' .$rs['id_assistente']. '|' .
							$rs['id_consultor']. '|' .$rs['hora_agendamento']. '|' .$rs['triplicar_vendas']. '|' .$rs['cad_cliente']. '|' .$rs['prod_estoque']. '|' .$rs['boletos']. '|' .
							$rs['frente_caixa']. '|' .$rs['nota_fiscal']. '|' .$rs['consultas_credito']. '|' .$rs['site'] . '|' . $rs['vizinhos'] . '|' . $login[0];

				}

				if (mysql_num_rows($qry) == 0) {
					$foneOrig = $fone;
					$fone = str_replace(' ','',str_replace('-','',str_replace(')','',str_replace('(','',$fone))));

					$sql_sel = "SELECT
									*
	                        FROM cs2.cadastro";
					if($_REQUEST['tipoTelefone'] == 'telefone'){
						$sql_sel.= " WHERE (fone = '$fone' OR fone = '$foneOrig') AND sitcli = 0 AND id_franquia != 2";
					}else{
						$sql_sel.= " WHERE (celular = '$fone' OR celular = '$foneOrig') AND sitcli = 0 AND id_franquia != 2";
					}
					$sql_sel.= " ORDER BY codLoja DESC LIMIT 1;
	                        ";
	//				            echo $sql_sel;
	//				            die;
					$qry = mysql_query($sql_sel,$con);

					while ($rs = mysql_fetch_array($qry)) {
						$html = '1|' .$rs['nomefantasia'] . '|' . $rs['cidade'] . '|'.  $rs['uf'] . '|' .$rs['fone1']. '|' .$rs['fone2']. '|' . $rs['razaosoc'] . '|' . $rs['atendente_resp'] . '|' . $rs['email'] . '|' . $rs['cep'] . '|' . $rs['end'] . '|' . $rs['numero'] . '|' . $rs['bairro'] . '|' . $rs['cidade'] . '|' . $rs['uf'];

					}
				}

				echo $html;

				break;				

				break;

		}
	}