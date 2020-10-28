<script type="text/javascript">
var ieBlink = (document.all)?true:false;
function doBlink(){
	if(ieBlink){
		obj = document.getElementsByTagName('BLINK');
		for(i=0;i<obj.length;i++){
			tag=obj[i];
			tag.style.visibility=(tag.style.visibility=='hidden')?'visible':'hidden';
		}
	}
}
</script>
<body onLoad="if(ieBlink){setInterval('doBlink()',450)}">


  <tr class="menu">
    <td align="center">&nbsp;</td>
    <td>Clientes</td>
  </tr>
	<?php
	if ( $id_franquia != 1205 && $id_franquia != 25 ) {
	?>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=clientes/a_incclient.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Inclusão no cadastro de clientes</a></td>
		</tr>
	<?php
	}
	?>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=clientes/a_altcliente.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Alteração nos dados do cliente </a></td>
  </tr>
  <?php if ( $id_franquia != 1205 ) {?>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=clientes/a_altsenha.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Alteração de senha</a></td>
  </tr>
  <?php } ?>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=clientes/a_extratoconsulta.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Extrato de Soluções e Pesquisas</a></td>
  </tr>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=clientes/a_formconsulta.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="#0033FF"><b>Visualizar cad. e tabela de clientes</b></font></a></td>
  </tr>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=clientes/a_faturas.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Faturas do Cliente</a></td>
  </tr>
  <?php if ( $id_franquia == 163 ){ ?>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=clientes/a_faturas_acordo.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Faturas do Cliente ( ACORDO )</a></td>
  </tr>
  <?php
   }
   if ( $id_franquia != 1205 ) {?>
    <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=Franquias/b_cadatendente.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Cadastrar Atendentes</a></td>
  </tr>
  <?php } ?>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=ocorrencias/a_ocorrencia.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="red"><b>Registro de Atendimentos</b></font></a></td>
  </tr>
  <?php if ( $id_franquia != 1205 ) {?>
  <tr>
    <td align="center" class="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=clientes/a_ordem_atendimento.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="red"><b>Ordens de Atendimento</b></font></a></td>
  </tr>
  <tr>
    <td align="center" class="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=veiculos/view/controle_veiculos.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><b>Controle de Veículos</b></a></td>
  </tr>

  <tr>
    <td align="center" class="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=clientes/a_serasa.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"> Verificar Negativação Equifax</a></td>
  </tr>
  
   <tr>
    <td align="center" class="center"><?php echo $i++; ?></td>
    <td><a href="../web/restrito/equifax.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Gerar Termo Negativação Equifax</a></td>
  </tr>

  <tr>
  	<td align="center"><?php echo $i++; ?></td>
  	<td><a href="painel.php?pagina1=area_restrita/d_pontos_ranking.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="blue"><b>Pontos Brincadeira</b></font></a></td>
  </tr>
  
	<?php } ?>
  <?php if ($id_franquia != '01') {  ?>

		<?php if ( $id_franquia != 1205 ) {?>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/d_crediario_recupere.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Extrato Crediario / Recupere / Boleto</a></td>
		</tr>
        <?php } ?>
        
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=Franquias/b_boleto.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="blue"><b>Boletos - Segunda Via</b></font></a></td>
		</tr>
		
		<?php if ( $tipo != 'b' )
			if ( $id_franquia != 1205 ) {?>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=Franquias/b_correspondencia.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Correspondência para Franquias</a></td>
		</tr>
		<?php } ?>

		<?php if($id_franquia == 4 || $id_franquia == 163){ ?>
		<tr>
        	<td align="center"><?php echo $i++; ?></td>
        	<td><a href="painel.php?pagina1=Franquias/devolucao_de_correspondencias.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Devolução de Correspondências</a></td>
		</tr>
		<?php }?>	

		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/d_equipamentos0.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="#BA55D3"><b>Controle Produtos/Equipamentos</a></b></td>
		</tr>
		
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/m_anunciantes.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><b>Anunciantes</a></b></td>
		</tr>

        <?php if ( $id_franquia != 1205 ) {?>
		<tr class="menu">
			<td>&nbsp;</td>
			<td>Operacional</td>
		</tr>
        <?php if ($id_franquia != '247') {  ?>
                <tr>
                    <td align="center"><?=$i++?></td>
                    <td><a href="painel.php?pagina1=clientes/relatorio_indica_amigo.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Relatório Indique um Amigo</a></td>
                </tr>
		<?php } ?>
                <tr>
                    <td align="center"><?=$i++?></td>
                    <td><a href="painel.php?pagina1=clientes/relatorio_reativacao.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Relatório de Reativação</a></td>
                </tr>

			<?php if($id_franquia == 4 || $id_franquia == 163){ ?>
				<tr>
					<td align="center"><?=$i++?></td>
					<td><a href="painel.php?pagina1=clientes/habilitacao_notas_view.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Habilitação de Notas (NFC, NFE, NFS)</a></td>
				</tr>
			<?php } ?>

		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=clientes/a_controle_visitas0.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="#00CC00"><b><blink>Controle Comercial</blink></b></font></a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/a_solicitacao_valores.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="#00CC00"><b><blink>Requisições Financeiras</blink></b></font></a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=graficos/i_graficos.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="#FF00FF"><b>Gráfico de Desempenho</b></font></a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=ranking/c_minhafranquia.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Minha Franquia, Minha Vida..!</a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=ocorrencias/a_desempenho.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Relatório de Cobrança / Atendimento</a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=clientes/a_recusado.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Registros de Negativação Recusados</a></td>
		</tr>
        <?php  if ( $id_franquia != 5 ){ ?>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=Franquias/b_liberaconsulta.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Liberação do limite de consultas</a></td>
		</tr>
        <?php } ?>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/d_libera_web_control.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Liberação Web-Control</a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/web_control_extrato_usuarios.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Listagem Usuário Web-Control</a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/web_contabil_extrato_usuarios.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Listagem Usuário Contábil-Solution</a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=virtualflex/v_relatorio.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="blue"><b>Relatório Usuários Virtual Flex</b></font></a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/virtual_flex_busca_excluir_dominio.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="red"><b>Excluir Informações Virtual - Flex</b></font></a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/web_control_eliminar_informacoes_busca_cliente.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="red"><b>Excluir Informações WebControl</b></font></a></td>
		</tr>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=Franquias/logomarca_buscar_cliente.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="#009966"><b>Inserir Logomarca do Cliente</b></font></a></td>
		</tr>
		<?php }
		if ($tipo != 'b' or $id_franquia == 1205 ) {
		?>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/d_copia_documentos.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Cópia de Documentos</a></td>
		</tr>
		<?php }
		if ( $usuario == 'danillo' or $usuario == 'franquiasnacional' ){ ?>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=area_restrita/d_envio_email_franqueados.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Envio Email Franqueados</a></td>
		</tr>
		<?php }
		if ( $id_franquia == 163 ){
// 		if ( $id_franquia == 4 or $id_franquia == 46 or $id_franquia == 163 or $id_franquia == 1204 ){
		?>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=clientes/boleto_novo_avulso.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">NOVO BOLETO</a></td>
		</tr>
		<?php
		} // $usuario == 'franquias'
	} // $id_franquia != '01'
	?>
    <tr>
    	<td align="center"><?php echo $i++; ?></td>
    	<td><a href="painel.php?pagina1=area_restrita/d_nota_fiscal.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="#0000FF"><b>Liberar NFe/NFCe/CF/NFSe/CTe/MDFe</b></font></a></td>
	</tr>
    <?php
	if ( $id_franquia == 4 or $id_franquia == 46 or $id_franquia == 163 or $id_franquia == 1204 ){
	?>
	<tr>
		<td align="center"><?php echo $i++; ?></td>
		<td><a href="painel.php?pagina1=Franquias/b_notafiscal_new.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><b>Emissão de Nota Fiscal - Web Control</b></td>
	</tr>

	<?php } ?>

	<?php
	if($id_franquia == 163){ ?>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=clientes/a_altcliente_dados_bancarios.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><b>Alteração de Contas</b></td>
		</tr>

	<?php	} ?>
		<tr>
			<td align="center"><?php echo $i++; ?></td>
			<td><a href="painel.php?pagina1=devolucao/devolucao_correspondencia_correios.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><b>Devolução de Correspondência Correios</b></td>
		</tr>
</body>