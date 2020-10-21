<?php

class VeiculosModel
{

    public function __construct(VeiculosController $veiculo)
    {
        try {
            $this->veiculo = $veiculo;
            $this->conexao = new DbConnection();
        } catch (PDOException $e) {
            throw new Exception('Não foi possível efetuar a conexão com o banco de dados.');
        }
    }

    /**
     * Insere Registros no Banco de Dados
     */
    public function insert()
    {
        $sql = "INSERT INTO cs2.veiculo (modelo, placa, ano_modelo, renavam, chassi, chave_reserva, credencial, condutor, condutorProvisorio)
       VALUES (:modelo, :placa, :ano_modelo, :renavam, :chassi, :chave_reserva, :credencial, :condutor, :condutorProvisorio)";

        $values = [
            ':modelo'               => $this->veiculo->getModelo(),
            ':placa'                => $this->veiculo->getPlaca(),
            ':ano_modelo'           => $this->veiculo->getAnoModelo(),
            ':renavam'              => $this->veiculo->getRenavam(),
            ':chassi'               => $this->veiculo->getChassi(),
            ':chave_reserva'        => $this->veiculo->getChaveReserva(),
            ':credencial'           => $this->veiculo->getCredencial(),
            ':condutor'             => $this->veiculo->getCondutor(),
            ':condutorProvisorio'   => $this->veiculo->getCondutorProvisorio()
        ];

        $pdo = $this->conexao->pdo->prepare($sql);

        return $pdo->execute($values);
    }

    /**
     * Lista os Registros no Banco de Dados
     */
    public function selectAll()
    {
        try {

            $sql = "SELECT * FROM cs2.veiculo v ORDER BY v.idVeiculo DESC";

            $pdo = $this->conexao->pdo->prepare($sql);

            $pdo->execute();

            return $pdo->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Erro ao selecionar registros.');
        }
    }

    /**
     * Lista os Registros do Banco de Dados
     */
    public function selectOne($idVeiculo)
    {
        try {

            $sql = "SELECT * FROM cs2.veiculo v WHERE idVeiculo = :idVeiculo";

            $values[':idVeiculo'] = $idVeiculo;

            $pdo = $this->conexao->pdo->prepare($sql);

            $pdo->execute($values);

            return $pdo->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Erro ao selecionar registros.');
        }
    }

    /**
     * Lista os Funcionarios ATIVOS do Banco de Dados
     */
    public function selectFuncionarios()
    {
        try {

            $sql = "SELECT * FROM cs2.funcionario f WHERE f.ativo = 'S' ORDER BY nome ASC";

            $pdo = $this->conexao->pdo->prepare($sql);

            $pdo->execute();

            return $pdo->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Erro ao selecionar registros.');
        }
    }
}
