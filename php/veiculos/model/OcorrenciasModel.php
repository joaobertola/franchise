<?php

class OcorrenciasModel
{

    public function __construct(OcorrenciasController $ocorrencia)
    {
        try {
            $this->ocorrencia = $ocorrencia;
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
        $sql = "INSERT INTO cs2.ocorrencia (idVeiculo, condutor, condutorProvisorio, atendente, ocorrencia, dataInicial, dataFinal, local, garantia, descricao, chave_reserva, imagem)
       VALUES (:idVeiculo, :condutor, :condutorProvisorio, :atendente, :ocorrencia, :dataInicial, :dataFinal, :local, :garantia, :descricao, :chave_reserva, :imagem)";

        $values = [
            ':idVeiculo'          => $this->ocorrencia->getIdVeiculo(),
            ':condutor'           => $this->ocorrencia->getCondutor(),
            ':condutorProvisorio' => $this->ocorrencia->getCondutorProvisorio(),
            ':atendente'          => $this->ocorrencia->getAtendente(),
            ':ocorrencia'         => $this->ocorrencia->getOcorrencia(),
            ':dataInicial'        => $this->ocorrencia->getDataInicial(),
            ':dataFinal'          => $this->ocorrencia->getDataFinal(),
            ':local'              => $this->ocorrencia->getLocal(),
            ':garantia'           => $this->ocorrencia->getGarantia(),
            ':descricao'          => $this->ocorrencia->getDescricao(),
            ':chave_reserva'      => $this->ocorrencia->getChaveReserva(),
            ':imagem'             => $this->ocorrencia->getImagem()
        ];

        $pdo = $this->conexao->pdo->prepare($sql);

        return $pdo->execute($values);
    }

    /**
     * Lista os Registros no Banco de Dados
     */
    public function selectAll($idVeiculo)
    {
        try {

            $sql = "SELECT o.*, f.nome AS nome_atendente
            FROM cs2.ocorrencia o
            LEFT JOIN cs2.funcionario f
            ON o.atendente = f.id
            WHERE o.idVeiculo = :idVeiculo
            ORDER BY o.idOcorrencia DESC";

            $pdo = $this->conexao->pdo->prepare($sql);

            $values = [
                ':idVeiculo' => $idVeiculo
            ];

            $pdo->execute($values);

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
