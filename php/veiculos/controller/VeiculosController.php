<?php

/**
 * @author João Vitor Ferreira em 19/10/2020
 */

require __DIR__ . '/../model/VeiculosModel.php';

class VeiculosController
{
    private $modelo;
    private $placa;
    private $anoModelo;
    private $renavam;
    private $chassi;
    private $chaveReserva;
    private $credencial;
    private $condutor;
    private $condutorProvisorio;

    /**
     * Get the value of modelo
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * Set the value of modelo
     *
     * @return  self
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get the value of placa
     */
    public function getPlaca()
    {
        return $this->placa;
    }

    /**
     * Set the value of placa
     *
     * @return  self
     */
    public function setPlaca($placa)
    {
        $this->placa = $placa;

        return $this;
    }

    /**
     * Get the value of anoModelo
     */
    public function getAnoModelo()
    {
        return $this->anoModelo;
    }

    /**
     * Set the value of anoModelo
     *
     * @return  self
     */
    public function setAnoModelo($anoModelo)
    {
        $this->anoModelo = $anoModelo;

        return $this;
    }

    /**
     * Get the value of renavam
     */
    public function getRenavam()
    {
        return $this->renavam;
    }

    /**
     * Set the value of renavam
     *
     * @return  self
     */
    public function setRenavam($renavam)
    {
        $this->renavam = $renavam;

        return $this;
    }

    /**
     * Get the value of chassi
     */
    public function getChassi()
    {
        return $this->chassi;
    }

    /**
     * Set the value of chassi
     *
     * @return  self
     */
    public function setChassi($chassi)
    {
        $this->chassi = $chassi;

        return $this;
    }

    /**
     * Get the value of chaveReserva
     */
    public function getChaveReserva()
    {
        return $this->chaveReserva;
    }

    /**
     * Set the value of chaveReserva
     *
     * @return  self
     */
    public function setChaveReserva($chaveReserva)
    {
        $this->chaveReserva = $chaveReserva;

        return $this;
    }

    /**
     * Get the value of credencial
     */
    public function getCredencial()
    {
        return $this->credencial;
    }

    /**
     * Set the value of credencial
     *
     * @return  self
     */
    public function setCredencial($credencial)
    {
        $this->credencial = $credencial;

        return $this;
    }

    /**
     * Get the value of condutor
     */
    public function getCondutor()
    {
        return $this->condutor;
    }

    /**
     * Set the value of condutor
     *
     * @return  self
     */
    public function setCondutor($condutor)
    {
        $this->condutor = $condutor;

        return $this;
    }

    /**
     * Get the value of condutorProvisorio
     */
    public function getCondutorProvisorio()
    {
        return $this->condutorProvisorio;
    }

    /**
     * Set the value of condutorProvisorio
     *
     * @return  self
     */
    public function setIdCondutorProvisorio($condutorProvisorio)
    {
        $this->condutorProvisorio = $condutorProvisorio;

        return $this;
    }

    /**
     * Insert novo veículo no banco
     */
    public function insert()
    {
        try {
            $veiculo = new VeiculosModel($this);
            return $veiculo->insert();
        } catch (Exception $e) {
            throw new Exception('Não foi possível salvar registro.');
        }
    }

    /**
     * Lista veículos do banco
     */
    public function selectAll()
    {
        try {
            $veiculo = new VeiculosModel($this);
            return $veiculo->selectAll();
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
            $veiculo = new VeiculosModel($this);
            return $veiculo->selectOne($idVeiculo);
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
            $veiculo = new VeiculosModel($this);
            return $veiculo->selectFuncionarios();
        } catch (Exception $e) {
            throw new Exception('Erro ao listar registros.');
        }
    }
}
