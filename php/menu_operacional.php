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
		<td width="18" align="center">&nbsp;</td>
		<td>Operacional</td>
	</tr>

	 <?php // if ($id_franquia != '247') {  ?>
	 <tr>
            <td align="center"><?=$i++?></td>
            <td><a href="painel.php?pagina1=clientes/relatorio_indica_amigo.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Relatório Indique um Amigo</a></td>
     </tr>
	<?php // } ?>

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
		<td><a href="painel.php?pagina1=area_restrita/a_solicitacao_valores.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><font color="#00CC00"><b><blink>kmnhbuggvkgh  ghv  jh </blink></b></font></a></td>
	</tr>
</body>