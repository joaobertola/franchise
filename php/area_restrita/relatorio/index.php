<form action="exemplo.html" id="frm-filtro">
<p>
  <label for="pesquisar">Pesquisar</label>
  <input type="text" id="pesquisar" size="30" />
</p>
</form>
    
    <table width="800" cellspacing="0">
      <thead>
        <tr>
          <th>ID</th>
          <th>Data Pedido</th>
          <th>Valor</th>
          <th>Data Dep&oacute;sito</th>
          <th>A&ccedil;&otilde;es</th>
        </tr>
      </thead>
      <tbody>
        <tr>
		  <td>1</td>
          <td>18/03/2014</td>
          <td>3.450,00</td>
          <td>19/03/2014</td>
          <td>
            <a href="#"><img src="edit.png" width="16" height="16" /></a>
            <a href="#"><img src="delete.png" width="16" height="16" /></a>
          </td>
        </tr>
        <tr>
      </tbody>
    </table>
    
    <div id="pager" class="pager">
    	<form>
				<span>
					Exibir <select class="pagesize">
							<option selected="selected"  value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option  value="40">40</option>
					</select> registros
				</span>

				<img src="first.png" class="first"/>
    		<img src="prev.png" class="prev"/>
    		<img src="next.png" class="next"/>
    		<img src="last.png" class="last"/>
    	</form>
    </div>
  </body>
</html>