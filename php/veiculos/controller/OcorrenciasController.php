<?php

/**
 * @author João Vitor Ferreira em 19/10/2020
 */

require __DIR__ . '/../model/OcorrenciasModel.php';

class OcorrenciasController
{
    private $idVeiculo;
    private $condutor;
    private $condutorProvisorio;
    private $atendente;
    private $ocorrencia;
    private $dataInicial;
    private $dataFinal;
    private $local;
    private $garantia;
    private $descricao;
    private $chaveReserva;
    private $imagem;

    /**
     * Get the value of descricao
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set the value of descricao
     *
     * @return  self
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get the value of garantia
     */
    public function getGarantia()
    {
        return $this->garantia;
    }

    /**
     * Set the value of garantia
     *
     * @return  self
     */
    public function setGarantia($garantia)
    {
        $this->garantia = $garantia;

        return $this;
    }

    /**
     * Get the value of local
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Set the value of local
     *
     * @return  self
     */
    public function setLocal($local)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * Get the value of dataFinal
     */
    public function getDataFinal()
    {
        return $this->dataFinal;
    }

    /**
     * Set the value of dataFinal
     *
     * @return  self
     */
    public function setDataFinal($dataFinal)
    {
        $this->dataFinal = $dataFinal;

        return $this;
    }

    /**
     * Get the value of dataInicial
     */
    public function getDataInicial()
    {
        return $this->dataInicial;
    }

    /**
     * Set the value of dataInicial
     *
     * @return  self
     */
    public function setDataInicial($dataInicial)
    {
        $this->dataInicial = $dataInicial;

        return $this;
    }

    /**
     * Get the value of ocorrencia
     */
    public function getOcorrencia()
    {
        return $this->ocorrencia;
    }

    /**
     * Set the value of ocorrencia
     *
     * @return  self
     */
    public function setOcorrencia($ocorrencia)
    {
        $this->ocorrencia = $ocorrencia;

        return $this;
    }

    /**
     * Get the value of atendente
     */
    public function getAtendente()
    {
        return $this->atendente;
    }

    /**
     * Set the value of atendente
     *
     * @return  self
     */
    public function setAtendente($atendente)
    {
        $this->atendente = $atendente;

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
    public function setCondutorProvisorio($condutorProvisorio)
    {
        $this->condutorProvisorio = $condutorProvisorio;

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
     * Get the value of idVeiculo
     */
    public function getIdVeiculo()
    {
        return $this->idVeiculo;
    }

    /**
     * Set the value of idVeiculo
     *
     * @return  self
     */
    public function setIdVeiculo($idVeiculo)
    {
        $this->idVeiculo = $idVeiculo;

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
     * Get the value of imagem
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * Set the value of imagem
     *
     * @return  self
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;

        return $this;
    }

    /**
     * Insert novo veículo no banco
     */
    public function insert()
    {
        try {
            $ocorrencia = new OcorrenciasModel($this);
            return $ocorrencia->insert();
        } catch (Exception $e) {
            throw new Exception('Não foi possível salvar registro.');
        }
    }

    /**
     * Lista ocorrencias do banco
     */
    public function selectAll($idVeiculo)
    {
        try {
            $ocorrencia = new OcorrenciasModel($this);
            return $ocorrencia->selectAll($idVeiculo);
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
