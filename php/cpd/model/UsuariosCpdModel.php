<?php

class UsuarioCpdModel
{

    public function __construct(UsuariosCpdController $usuariocpd)
    {
        try {
            $this->usuarioCpd = $usuariocpd;
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
        $sql = "INSERT INTO cs2.ordem_atendimento_recurso (nome, funcao, perfil, imagem, ativo, user, pass) VALUES (:nome, :funcao, :perfil, :imagem, :ativo, :user, :pass)";
        $values = [
            ':nome'   => $this->usuarioCpd->getNome(),
            ':funcao' => $this->usuarioCpd->getFuncao(),
            ':perfil' => $this->usuarioCpd->getPerfil(),
            ':imagem' => $this->usuarioCpd->getFoto(),
            ':ativo'  => $this->usuarioCpd->getStatus(),
            ':user'   => $this->usuarioCpd->getLogin(),
            ':pass'   => $this->usuarioCpd->getSenha(),
        ];

        // echo '<pre>';
        // var_dump($sql, $values);
        // exit;

        $pdo = $this->conexao->pdo->prepare($sql);

        return $pdo->execute($values);
    }

    /**
     * Insere Registros no Banco de Dados
     */
    public function update()
    {
        $sql = "UPDATE cs2.ordem_atendimento_recurso SET ";

        $values = [
            'nome'   => $this->usuarioCpd->getNome(),
            'funcao' => $this->usuarioCpd->getFuncao(),
            'perfil' => $this->usuarioCpd->getPerfil(),
            'ativo'  => $this->usuarioCpd->getStatus(),
            'user'   => $this->usuarioCpd->getLogin()
        ];

        if (!empty($this->usuarioCpd->getSenha())) {
            $values['pass'] = $this->usuarioCpd->getSenha();
        }

        if (!empty($this->usuarioCpd->getFoto())) {
            $values['imagem'] = $this->usuarioCpd->getFoto();
        }

        $set = '';

        $last = count($values);

        $i = 0;

        foreach ($values as $key => $v) {
            $v = str_replace("'", "''", $v);
            $v = str_replace('"', "''", $v);
            $set .= $key . " = '" . $v . "'";
            if ($i < ($last - 1)) {
                $set .= ", ";
            }
            $i++;
        }

        $sql .= $set;

        $sql .= ' WHERE id =' . $this->usuarioCpd->getIdUsuarioCpd();

        $pdo = $this->conexao->pdo->prepare($sql);

        return $pdo->execute($values);
    }

    /**
     * Lista os Registros no Banco de Dados
     */
    public function selectAll()
    {
        try {

            $sql = "SELECT * FROM cs2.ordem_atendimento_recurso oar WHERE oar.ativo = 1 ORDER BY oar.funcao ASC";

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
    public function selectOne($id)
    {
        try {

            $sql = "SELECT * FROM cs2.ordem_atendimento_recurso oar WHERE oar.id = :id";

            $values[':id'] = $id;

            $pdo = $this->conexao->pdo->prepare($sql);

            $pdo->execute($values);

            return $pdo->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Erro ao selecionar registros.');
        }
    }
}
