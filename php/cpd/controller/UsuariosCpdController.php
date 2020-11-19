<?php

/**
 * @author João Vitor Ferreira em 19/10/2020
 */

require __DIR__ . '/../model/UsuariosCpdModel.php';

class UsuariosCpdController
{

    private $idUsuarioCpd;
    private $nome;
    private $funcao;
    private $foto;
    private $status;
    private $login;
    private $senha;
    private $perfil = 2;

    /**
     * Get the value of senha
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set the value of senha
     *
     * @return  self
     */
    public function setSenha($senha)
    {
        $this->senha = !empty($senha) ? md5($senha) : '';

        return $this;
    }

    /**
     * Get the value of login
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set the value of login
     *
     * @return  self
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of foto
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set the value of foto
     *
     * @return  self
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get the value of funcao
     */
    public function getFuncao()
    {
        return $this->funcao;
    }

    /**
     * Set the value of funcao
     *
     * @return  self
     */
    public function setFuncao($funcao)
    {
        $this->funcao = $funcao;

        return $this;
    }

    /**
     * Get the value of nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of idUsuarioCpd
     */
    public function getIdUsuarioCpd()
    {
        return $this->idUsuarioCpd;
    }

    /**
     * Set the value of idUsuarioCpd
     *
     * @return  self
     */
    public function setIdUsuarioCpd($idUsuarioCpd)
    {
        $this->idUsuarioCpd = $idUsuarioCpd;

        return $this;
    }

    /**
     * Get the value of perfil
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set the value of perfil
     *
     * @return  self
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Insert novo veículo no banco
     */
    public function insert()
    {
        try {
            $usuariocpd = new UsuarioCpdModel($this);
            return $usuariocpd->insert();
        } catch (Exception $e) {
            throw new Exception('Não foi possível salvar registro.');
        }
    }

    /**
     * Insert novo veículo no banco
     */
    public function update()
    {
        try {
            $usuariocpd = new UsuarioCpdModel($this);
            return $usuariocpd->update();
        } catch (Exception $e) {
            throw new Exception('Não foi possível salvar registro.');
        }
    }

    /**
     * deleta o veículo no banco
     */
    public function delete($idVeiculo)
    {
        try {
            $usuariocpd = new UsuarioCpdModel($this);
            return $usuariocpd->delete($idVeiculo);
        } catch (Exception $e) {
            throw new Exception('Não foi possível deletar o registro.');
        }
    }

    /**
     * Lista veículos do banco
     */
    public function selectAll()
    {
        try {
            $usuariocpd = new UsuarioCpdModel($this);
            return $usuariocpd->selectAll();
        } catch (Exception $e) {
            throw new Exception('Erro ao listar registros.');
        }
    }

    /**
     * Lista um veículo do banco
     */
    public function selectOne($idVeiculo)
    {
        try {
            $usuariocpd = new UsuarioCpdModel($this);
            return $usuariocpd->selectOne($idVeiculo);
        } catch (Exception $e) {
            throw new Exception('Erro ao listar registros.');
        }
    }

    /**
     * Lista os funcionarios da WebControl
     */
    public function selectFuncionarios()
    {
        try {
            $usuariocpd = new UsuarioCpdModel($this);
            return $usuariocpd->selectFuncionarios();
        } catch (Exception $e) {
            throw new Exception('Erro ao listar registros.');
        }
    }
}
