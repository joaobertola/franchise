<?php

/**
 * Alterado por Simeia Avellar Pedroso
 * Data 01/09/2015
 * Projeto Ordem de Atendimento
 */
class DbConnection extends PDO
{
	/**
	 * Atributos de conexao com o banco de dados DESENVOLVIMENTO
	 */

	private $host = '10.2.2.3';
	private $dbname = 'cs2';
	private $username = 'csinform';
	private $password = 'inform4416#scf';

	public $pdo = '';
	public $sql;
	/**
	 * Gets e Sets
	 */
	public function getSql()
	{
		return $this->sql;
	}

	public function setSql($sql)
	{
		$this->sql = $sql;
		return $this;
	}
	/**
	 * M�todo Construtor
	 */
	function __construct()
	{
		$this->connect();
	}
	/**
	 * M�todo de conexão
	 */
	function connect()
	{
		try {
			$this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			$this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
		} catch (PDOException $e) {
			throw new Exception("N�o foi poss�vel conectar com o banco de dados");
		}
	}
	/**
	 * Retorn �ltimo di (CHAMADA)
	 */
	/*public function lastInsertId() {
	    return $this->pdo->lastInsertId();
	}  */
	/**
	 * Prepara sql a ser executada
	 */
	public function preparaSQL()
	{
		return $this->pdo->prepare($this->getSql());
	}
	/**
	 * M�todo rowCount - Conta quantidade de linhas retornadas
	 */
	public function rowCount()
	{
		return $this->pdo->rowCount();
	}
}
