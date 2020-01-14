<?php

class CreateBaseDadosController {

    private $database;
    private $table;
    private $campos;

    public function __construct()
    {
        $this->table = '';
        $this->campos = '';
    }

    /**
     * Get the value of database
     */ 
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Set the value of database
     *
     * @return  self
     */ 
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * Get the value of table
     */ 
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set the value of table
     *
     * @return  self
     */ 
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get the value of campos
     */ 
    public function getCampos()
    {
        return $this->campos;
    }

    /**
     * Set the value of campos
     *
     * @return  self
     */ 
    public function setCampos($campos)
    {
        $this->campos = $campos;

        return $this;
    }

    // Métodos Model

    public function create_data_base() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_data_base();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a base de dados.' );
        }
    }

    public function create_table_acesso_filiacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_acesso_filiacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela acesso_filiacao.' );
        }
    }

    public function create_table_agenda() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_agenda();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela agenda.' );
        }
    }

    public function create_table_agenda_usuario_parceiro() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_agenda_usuario_parceiro();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_agenda_usuario_parceiro.' );
        }
    }

    public function create_table_agendamento_tarefa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_agendamento_tarefa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_agendamento_tarefa.' );
        }
    }

    public function create_table_agendamento_tarefa_log() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_agendamento_tarefa_log();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_agendamento_tarefa_log.' );
        }
    }

    public function create_table_assistencia_tecnica() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_assistencia_tecnica();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_assistencia_tecnica.' );
        }
    }

    public function create_table_assistencia_tecnica_conclusao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_assistencia_tecnica_conclusao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_assistencia_tecnica_conclusao.' );
        }
    }

    public function create_table_assistencia_tecnica_garantia() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_assistencia_tecnica_garantia();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_assistencia_tecnica_garantia.' );
        }
    }

    public function create_table_assistencia_tecnica_marcas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_assistencia_tecnica_marcas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_assistencia_tecnica_marcas.' );
        }
    }

    public function create_table_assistencia_tecnica_observacoes() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_assistencia_tecnica_observacoes();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_assistencia_tecnica_observacoes.' );
        }
    }

    public function create_table_assistencia_tecnica_produtos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_assistencia_tecnica_produtos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_assistencia_tecnica_produtos.' );
        }
    }

    public function create_table_assistencia_tecnica_voltagem() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_assistencia_tecnica_voltagem();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_assistencia_tecnica_voltagem.' );
        }
    }

    public function create_table_atendimento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_atendimento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_atendimento.' );
        }
    }

    public function create_table_atendimento_fornecedor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_atendimento_fornecedor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_atendimento_fornecedor.' );
        }
    }

    public function create_table_atendimento_funcionario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_atendimento_funcionario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_atendimento_funcionario.' );
        }
    }

    public function create_table_atendimento_tipo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_atendimento_tipo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_atendimento_tipo.' );
        }
    }

    public function create_table_atendimento_transportadora() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_atendimento_transportadora();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_atendimento_transportadora.' );
        }
    }

    public function create_table_autorizacao_cielo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_autorizacao_cielo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_autorizacao_cielo.' );
        }
    }

    public function create_table_aux_venda_faturado_impressao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_aux_venda_faturado_impressao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_aux_venda_faturado_impressao.' );
        }
    }

    public function create_table_auxiliar_envio_sms() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_auxiliar_envio_sms();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_auxiliar_envio_sms.' );
        }
    }

    public function create_table_auxiliar_importacao_produto() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_auxiliar_importacao_produto();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_auxiliar_importacao_produto.' );
        }
    }

    public function create_table_banco() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_banco();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_banco.' );
        }
    }

    public function create_table_boleto_doc() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_boleto_doc();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_boleto_doc.' );
        }
    }

    public function create_table_cadastro() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cadastro();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cadastro.' );
        }
    }

    public function create_table_cadastro_aut_notas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cadastro_aut_notas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cadastro_aut_notas.' );
        }
    }

    public function create_table_cadastro_controles() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cadastro_controles();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cadastro_controles.' );
        }
    }

    public function create_table_cadastro_convenio_bancario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cadastro_convenio_bancario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cadastro_convenio_bancario.' );
        }
    }

    public function create_table_cadastro_imposto_padrao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cadastro_imposto_padrao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cadastro_imposto_padrao.' );
        }
    }

    public function create_table_cadastro_imposto_padrao_hist() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cadastro_imposto_padrao_hist();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cadastro_imposto_padrao_hist.' );
        }
    }

    public function create_table_cargo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cargo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cargo.' );
        }
    }

    public function create_table_carne() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_carne();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_carne.' );
        }
    }

    public function create_table_carrinho() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_carrinho();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_carrinho.' );
        }
    }

    public function create_table_cartaofid_cartao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cartaofid_cartao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cartaofid_cartao.' );
        }
    }

    public function create_table_cartaofid_config() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cartaofid_config();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cartaofid_config.' );
        }
    }

    public function create_table_cartaofid_historico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cartaofid_historico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cartaofid_historico.' );
        }
    }

    public function create_table_cartaofid_modelo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cartaofid_modelo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cartaofid_modelo.' );
        }
    }

    public function create_table_cartaofid_pedido_grafica() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cartaofid_pedido_grafica();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cartaofid_pedido_grafica.' );
        }
    }

    public function create_table_catalogo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_catalogo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_catalogo.' );
        }
    }

    public function create_table_cest() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cest();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cest.' );
        }
    }

    public function create_table_cestt() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cestt();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cestt.' );
        }
    }

    public function create_table_cest2() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cest2();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cest2.' );
        }
    }

    public function create_table_cfop() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cfop();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cfop.' );
        }
    }

    public function create_table_cidade() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cidade();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cidade.' );
        }
    }

    public function create_table_classificacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_classificacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_classificacao.' );
        }
    }

    public function create_table_classificacao_alteracao_valores() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_classificacao_alteracao_valores();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_classificacao_alteracao_valores.' );
        }
    }

    public function create_table_classificacao_bancodeimagens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_classificacao_bancodeimagens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_classificacao_bancodeimagens.' );
        }
    }

    public function create_table_classificacao_contas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_classificacao_contas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_classificacao_contas.' );
        }
    }

    public function create_table_classificacao_sub() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_classificacao_sub();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_classificacao_sub.' );
        }
    }

    public function create_table_classificacoes_removidas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_classificacoes_removidas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_classificacoes_removidas.' );
        }
    }

    public function create_table_cli_recebafacil() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cli_recebafacil();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cli_recebafacil.' );
        }
    }

    public function create_table_cliente() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cliente();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cliente.' );
        }
    }

    public function create_table_cliente_agendamentos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cliente_agendamentos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cliente_agendamentos.' );
        }
    }

    public function create_table_cliente_documento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cliente_documento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cliente_documento.' );
        }
    }

    public function create_table_cliente_documentos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cliente_documentos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cliente_documentos.' );
        }
    }

    public function create_table_cliente_forma_pagamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cliente_forma_pagamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cliente_forma_pagamento.' );
        }
    }

    public function create_table_cliente_optica() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cliente_optica();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cliente_optica.' );
        }
    }

    public function create_table_cliente_veiculo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cliente_veiculo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cliente_veiculo.' );
        }
    }

    public function create_table_cliente_veiculos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cliente_veiculos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cliente_veiculos.' );
        }
    }

    public function create_table_clientes_removidos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_clientes_removidos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_clientes_removidos.' );
        }
    }

    public function create_table_cm_comanda() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cm_comanda();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cm_comanda.' );
        }
    }

    public function create_table_cm_historico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cm_historico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cm_historico.' );
        }
    }

    public function create_table_cm_mesa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cm_mesa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cm_mesa.' );
        }
    }

    public function create_table_cm_producao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cm_producao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cm_producao.' );
        }
    }

    public function create_table_cm_reserva() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cm_reserva();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cm_reserva.' );
        }
    }

    public function create_table_cm_reserva_mesa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cm_reserva_mesa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cm_reserva_mesa.' );
        }
    }

    public function create_table_cnae() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cnae();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cnae.' );
        }
    }

    public function create_table_cnae_issqn() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cnae_issqn();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cnae_issqn.' );
        }
    }

    public function create_table_compartilhamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_compartilhamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_compartilhamento.' );
        }
    }

    public function create_table_compromisso() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_compromisso();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_compromisso.' );
        }
    }

    public function create_table_conferencia_estoque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_conferencia_estoque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_conferencia_estoque.' );
        }
    }

    public function create_table_conferencia_estoque_itens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_conferencia_estoque_itens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_conferencia_estoque_itens.' );
        }
    }

    public function create_table_conferencia_estoque_itens_temp() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_conferencia_estoque_itens_temp();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_conferencia_estoque_itens_temp.' );
        }
    }

    public function create_table_conta_corrente() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_conta_corrente();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_conta_corrente.' );
        }
    }

    public function create_table_conta_corrente_movimentacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_conta_corrente_movimentacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_conta_corrente_movimentacao.' );
        }
    }

    public function create_table_contador_cliente() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_contador_cliente();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_contador_cliente.' );
        }
    }


    public function create_table_contas_comprovante() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_contas_comprovante();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_contas_comprovante.' );
        }
    }


    public function create_table_contas_empresa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_contas_empresa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_contas_empresa.' );
        }
    }

    public function create_table_contas_pagar() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_contas_pagar();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_contas_pagar.' );
        }
    }

    public function create_table_contas_pagar_bkp() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_contas_pagar_bkp();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_contas_pagar_bkp.' );
        }
    }

    public function create_table_contas_pagar_tpdoc() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_contas_pagar_tpdoc();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_contas_pagar_tpdoc.' );
        }
    }

    public function create_table_controle_notafiscal() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_controle_notafiscal();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_controle_notafiscal.' );
        }
    }

    public function create_table_credenciadora_cartao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_credenciadora_cartao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_credenciadora_cartao.' );
        }
    }

    public function create_table_credenciadoras_fixas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_credenciadoras_fixas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_credenciadoras_fixas.' );
        }
    }

    public function create_table_credenciadoras_fixas_ignorar() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_credenciadoras_fixas_ignorar();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_credenciadoras_fixas_ignorar.' );
        }
    }

    public function create_table_cst() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_cst();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_cst.' );
        }
    }

    public function create_table_dados_avaliacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_dados_avaliacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_dados_avaliacao.' );
        }
    }

    public function create_table_descricao_contas_pagar() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_descricao_contas_pagar();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_descricao_contas_pagar.' );
        }
    }

    public function create_table_descricao_contas_pagar_padrao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_descricao_contas_pagar_padrao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_descricao_contas_pagar_padrao.' );
        }
    }

    public function create_table_documentos_arquivado() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_documentos_arquivado();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_documentos_arquivado.' );
        }
    }

    public function create_table_documentos_pasta() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_documentos_pasta();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_documentos_pasta.' );
        }
    }

    public function create_table_encaminhamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_encaminhamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_encaminhamento.' );
        }
    }

    public function create_table_encaminhamento_endereco() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_encaminhamento_endereco();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_encaminhamento_endereco.' );
        }
    }

    public function create_table_encaminhamento_produtos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_encaminhamento_produtos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_encaminhamento_produtos.' );
        }
    }

    public function create_table_encaminhamento_tipo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_encaminhamento_tipo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_encaminhamento_tipo.' );
        }
    }

    public function create_table_envio_sms_boleto() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_envio_sms_boleto();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_envio_sms_boleto.' );
        }
    }

    public function create_table_estado() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_estado();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_estado.' );
        }
    }

    public function create_table_estado_civil() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_estado_civil();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_estado_civil.' );
        }
    }

    public function create_table_estados() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_estados();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_estados.' );
        }
    }

    public function create_table_estoque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_estoque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_estoque.' );
        }
    }

    public function create_table_estoque_apoio() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_estoque_apoio();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_estoque_apoio.' );
        }
    }

    public function create_table_estoque_apoio_() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_estoque_apoio_();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_estoque_apoio_.' );
        }
    }

    public function create_table_estoque_produto() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_estoque_produto();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_estoque_produto.' );
        }
    }

    public function create_table_exclusao_info() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_exclusao_info();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_exclusao_info.' );
        }
    }

    public function create_table_exclusao_info_relacionados() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_exclusao_info_relacionados();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_exclusao_info_relacionados.' );
        }
    }

    public function create_table_fila_tarefas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_fila_tarefas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_fila_tarefas.' );
        }
    }

    public function create_table_financeiro_apoio() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_financeiro_apoio();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_financeiro_apoio.' );
        }
    }

    public function create_table_financeiro_funcionario_banco() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_financeiro_funcionario_banco();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_financeiro_funcionario_banco.' );
        }
    }

    public function create_table_financeiro_funcionario_valor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_financeiro_funcionario_valor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_financeiro_funcionario_valor.' );
        }
    }

    public function create_table_fluxo_caixa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_fluxo_caixa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_fluxo_caixa.' );
        }
    }

    public function create_table_forma_pagamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_forma_pagamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_forma_pagamento.' );
        }
    }

    public function create_table_forma_pagamento_bandeira() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_forma_pagamento_bandeira();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_forma_pagamento_bandeira.' );
        }
    }

    public function create_table_forma_pagamento_cliente() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_forma_pagamento_cliente();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_forma_pagamento_cliente.' );
        }
    }

    public function create_table_forma_pagamento_ecommerce() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_forma_pagamento_ecommerce();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_forma_pagamento_ecommerce.' );
        }
    }

    public function create_table_fornecedor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_fornecedor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_fornecedor.' );
        }
    }

    public function create_table_fornecedor_banco() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_fornecedor_banco();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_fornecedor_banco.' );
        }
    }

    public function create_table_fornecedor_pedido() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_fornecedor_pedido();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_fornecedor_pedido.' );
        }
    }

    public function create_table_fornecedor_pedido_item() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_fornecedor_pedido_item();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_fornecedor_pedido_item.' );
        }
    }

    public function create_table_fornecedor_produto() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_fornecedor_produto();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_fornecedor_produto.' );
        }
    }

    public function create_table_fornecedor_servico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_fornecedor_servico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_fornecedor_servico.' );
        }
    }

    public function create_table_fornecedor_transportadora() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_fornecedor_transportadora();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_fornecedor_transportadora.' );
        }
    }

    public function create_table_funcionario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_funcionario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_funcionario.' );
        }
    }

    public function create_table_funcionario2() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_funcionario2();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_funcionario2.' );
        }
    }

    public function create_table_funcionario_agendamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_funcionario_agendamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_funcionario_agendamento.' );
        }
    }

    public function create_table_funcionario_comissao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_funcionario_comissao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_funcionario_comissao.' );
        }
    }

    public function create_table_funcionario_funcao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_funcionario_funcao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_funcionario_funcao.' );
        }
    }

    public function create_table_funcionario_horario_trabalho() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_funcionario_horario_trabalho();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_funcionario_horario_trabalho.' );
        }
    }

    public function create_table_grade() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_grade();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_grade.' );
        }
    }

    public function create_table_grade_arrumar_estoque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_grade_arrumar_estoque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_grade_arrumar_estoque.' );
        }
    }

    public function create_table_grade_atributo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_grade_atributo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_grade_atributo.' );
        }
    }

    public function create_table_grade_atributo_valor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_grade_atributo_valor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_grade_atributo_valor.' );
        }
    }

    public function create_table_grade_foto() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_grade_foto();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_grade_foto.' );
        }
    }

    public function create_table_grade_historico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_grade_historico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_grade_historico.' );
        }
    }

    public function create_table_grade_promocao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_grade_promocao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_grade_promocao.' );
        }
    }

    public function create_table_grade_saida_estoque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_grade_saida_estoque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_grade_saida_estoque.' );
        }
    }

    public function create_table_grau_instrucao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_grau_instrucao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_grau_instrucao.' );
        }
    }

    public function create_table_historico_doc_fiscais() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_historico_doc_fiscais();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_historico_doc_fiscais.' );
        }
    }

    public function create_table_horario_trabalho() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_horario_trabalho();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_horario_trabalho.' );
        }
    }

    public function create_table_ibptax() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_ibptax();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_ibptax.' );
        }
    }

    public function create_table_importacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_importacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_importacao.' );
        }
    }

    public function create_table_indica_amigo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_indica_amigo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_indica_amigo.' );
        }
    }

    public function create_table_indica_amigo_log() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_indica_amigo_log();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_indica_amigo_log.' );
        }
    }

    public function create_table_lancamentos_empresas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_lancamentos_empresas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_lancamentos_empresas.' );
        }
    }

    public function create_table_limite_funcionario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_limite_funcionario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_limite_funcionario.' );
        }
    }

    public function create_table_link() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_link();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_link.' );
        }
    }

    public function create_table_log_acesso_offline() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_acesso_offline();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_acesso_offline.' );
        }
    }

    public function create_table_log_acoes_notasfiscais() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_acoes_notasfiscais();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_acoes_notasfiscais.' );
        }
    }

    public function create_table_log_dados_cadastro() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_dados_cadastro();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_dados_cadastro.' );
        }
    }

    public function create_table_log_envia_email() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_envia_email();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_envia_email.' );
        }
    }

    public function create_table_log_erro_sessao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_erro_sessao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_erro_sessao.' );
        }
    }

    public function create_table_log_estoque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_estoque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_estoque.' );
        }
    }

    public function create_table_log_mensage_atencao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_mensage_atencao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_mensage_atencao.' );
        }
    }

    public function create_table_log_monitoramento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_monitoramento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_monitoramento.' );
        }
    }

    public function create_table_log_sync_loja() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_sync_loja();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_sync_loja.' );
        }
    }

    public function create_table_log_sync_loja_itens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_sync_loja_itens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_sync_loja_itens.' );
        }
    }

    public function create_table_log_web_control() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_log_web_control();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_log_web_control.' );
        }
    }

    public function create_table_mailmkt_campanha() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mailmkt_campanha();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mailmkt_campanha.' );
        }
    }

    public function create_table_mailmkt_campanha_agendamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mailmkt_campanha_agendamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mailmkt_campanha_agendamento.' );
        }
    }

    public function create_table_mailmkt_campanha_fixa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mailmkt_campanha_fixa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mailmkt_campanha_fixa.' );
        }
    }

    public function create_table_mailmkt_campanha_fixa_ignorar() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mailmkt_campanha_fixa_ignorar();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mailmkt_campanha_fixa_ignorar.' );
        }
    }

    public function create_table_mailmkt_config() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mailmkt_config();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mailmkt_config.' );
        }
    }

    public function create_table_mailmkt_config_master() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mailmkt_config_master();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mailmkt_config_master.' );
        }
    }

    public function create_table_mailmkt_lista() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mailmkt_lista();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mailmkt_lista.' );
        }
    }

    public function create_table_mailmkt_lista_emails() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mailmkt_lista();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mailmkt_lista_emails.' );
        }
    }

    public function create_table_mailmkt_log() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mailmkt_log();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mailmkt_log.' );
        }
    }

    public function create_table_manifest() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_manifest();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_manifest.' );
        }
    }

    public function create_table_manifest_condutor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_manifest_condutor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_manifest_condutor.' );
        }
    }

    public function create_table_manifest_documentos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_manifest_documentos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_manifest_documentos.' );
        }
    }

    public function create_table_manifest_reboque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_manifest_reboque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_manifest_reboque.' );
        }
    }

    public function create_table_manifest_uf_percurso() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_manifest_uf_percurso();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_manifest_uf_percurso.' );
        }
    }

    public function create_table_manifest_veictracao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_manifest_veictracao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_manifest_veictracao.' );
        }
    }

    public function create_table_manifesto_informacoes() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_manifesto_informacoes();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_manifesto_informacoes.' );
        }
    }

    public function create_table_manifesto_modal() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_manifesto_modal();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_manifesto_modal.' );
        }
    }

    public function create_table_manifesto_modal_condutor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_manifesto_modal_condutor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_manifesto_modal_condutor.' );
        }
    }

    public function create_table_manifesto_modal_reboque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_manifesto_modal_reboque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_manifesto_modal_reboque.' );
        }
    }

    public function create_table_marcas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_marcas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_marcas.' );
        }
    }

    public function create_table_matriz_filial() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_matriz_filial();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_matriz_filial.' );
        }
    }

    public function create_table_matriz_filial_historico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_matriz_filial_historico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_matriz_filial_historico.' );
        }
    }

    public function create_table_matriz_permissao_modulo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_matriz_permissao_modulo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_matriz_permissao_modulo.' );
        }
    }

    public function create_table_mensagens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mensagens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mensagens.' );
        }
    }

    public function create_table_mercado_livre_produto() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_mercado_livre_produto();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_mercado_livre_produto.' );
        }
    }

    public function create_table_modalidade_calculo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_modalidade_calculo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_modalidade_calculo.' );
        }
    }

    public function create_table_modalidade_calculo_st() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_modalidade_calculo_st();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_modalidade_calculo_st.' );
        }
    }

    public function create_table_modelo_contrato() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_modelo_contrato();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_modelo_contrato.' );
        }
    }

    public function create_table_modulo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_modulo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_modulo.' );
        }
    }

    public function create_table_modulos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_modulos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_modulos.' );
        }
    }

    public function create_table_movimento_titulo_recebafacil() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_movimento_titulo_recebafacil();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_movimento_titulo_recebafacil.' );
        }
    }

    public function create_table_municipio_rf() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_municipio_rf();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_municipio_rf.' );
        }
    }

    public function create_table_ncm() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_ncm();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_ncm.' );
        }
    }

    public function create_table_nf_devolucao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_devolucao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_devolucao.' );
        }
    }

    public function create_table_nf_devolucao_cobranca() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_devolucao_cobranca();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_devolucao_cobranca.' );
        }
    }

    public function create_table_nf_devolucao_itens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_devolucao_itens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_devolucao_itens.' );
        }
    }

    public function create_table_nf_devolucao_itens_COFINS() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_devolucao_itens_COFINS();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_devolucao_itens_COFINS.' );
        }
    }

    public function create_table_nf_devolucao_itens_COFINSST() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_devolucao_itens_COFINSST();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_devolucao_itens_COFINSST.' );
        }
    }

    public function create_table_nf_devolucao_itens_ICMS() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_devolucao_itens_ICMS();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_devolucao_itens_ICMS.' );
        }
    }

    public function create_table_nf_devolucao_itens_II() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_devolucao_itens_II();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_devolucao_itens_II.' );
        }
    }

    public function create_table_nf_devolucao_itens_IPI() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_devolucao_itens_IPI();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_devolucao_itens_IPI.' );
        }
    }

    public function create_table_nf_devolucao_itens_PIS() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_devolucao_itens_PIS();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_devolucao_itens_PIS.' );
        }
    }

    public function create_table_nf_devolucao_itens_PISST() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_devolucao_itens_PISST();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_devolucao_itens_PISST.' );
        }
    }

    public function create_table_nf_entrada() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_entrada();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_entrada.' );
        }
    }

    public function create_table_nf_entrada_estoque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_entrada_estoque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_entrada_estoque.' );
        }
    }

    public function create_table_nf_entrada_estoque_itens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_entrada_estoque_itens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_entrada_estoque_itens.' );
        }
    }

    public function create_table_nf_entrada_itens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_entrada_itens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_entrada_itens.' );
        }
    }

    public function create_table_nf_entrada_xml() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_entrada_xml();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_entrada_xml.' );
        }
    }

    public function create_table_nf_inutilizadas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_inutilizadas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_inutilizadas.' );
        }
    }

    public function create_table_nf_municipio_RF() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_municipio_RF();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_municipio_RF.' );
        }
    }

    public function create_table_nf_natureza() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_natureza();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_natureza.' );
        }
    }

    public function create_table_nf_paises() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_paises();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_paises.' );
        }
    }

    public function create_table_nf_servico_assinadas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_servico_assinadas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_servico_assinadas.' );
        }
    }

    public function create_table_nf_tributos_itens_COFINS() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_tributos_itens_COFINS();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_tributos_itens_COFINS.' );
        }
    }

    public function create_table_nf_tributos_itens_COFINSST() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_tributos_itens_COFINSST();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_tributos_itens_COFINSST.' );
        }
    }

    public function create_table_nf_tributos_itens_ICMS() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_tributos_itens_ICMS();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_tributos_itens_ICMS.' );
        }
    }

    public function create_table_nf_tributos_itens_II() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_tributos_itens_II();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_tributos_itens_II.' );
        }
    }

    public function create_table_nf_tributos_itens_IPI() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_tributos_itens_IPI();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_tributos_itens_IPI.' );
        }
    }

    public function create_table_nf_tributos_itens_PIS() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_tributos_itens_PIS();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_tributos_itens_PIS.' );
        }
    }

    public function create_table_nf_tributos_itens_PISST() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_tributos_itens_PISST();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_tributos_itens_PISST.' );
        }
    }

    public function create_table_nf_tributos_venda() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nf_tributos_venda();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nf_tributos_venda.' );
        }
    }

    public function create_table_nfe_cupom_fiscal() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_cupom_fiscal();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_cupom_fiscal.' );
        }
    }

    public function create_table_nfe_exigibilidade() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_exigibilidade();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_exigibilidade.' );
        }
    }

    public function create_table_nfe_icms_interestaduais() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_icms_interestaduais();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_icms_interestaduais.' );
        }
    }

    public function create_table_nfe_icms_interestaduais_cliente() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_icms_interestaduais_cliente();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_icms_interestaduais_cliente.' );
        }
    }

    public function create_table_nfe_icms_situacao_tributaria() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_icms_situacao_tributaria();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_icms_situacao_tributaria.' );
        }
    }

    public function create_table_nfe_lista_servico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_lista_servico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_lista_servico.' );
        }
    }

    public function create_table_nfe_modalidade() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_modalidade();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_modalidade.' );
        }
    }

    public function create_table_nfe_motivo_desoneracao_icms() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_motivo_desoneracao_icms();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_motivo_desoneracao_icms.' );
        }
    }

    public function create_table_nfe_municipio() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_municipio();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_municipio.' );
        }
    }

    public function create_table_nfe_mvat() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_mvat();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_mvat.' );
        }
    }

    public function create_table_nfe_origem() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_origem();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_origem.' );
        }
    }

    public function create_table_nfe_paises() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_paises();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_paises.' );
        }
    }

    public function create_table_nfe_produto_COFINS() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_produto_COFINS();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_produto_COFINS.' );
        }
    }

    public function create_table_nfe_produto_COFINSST() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_produto_COFINSST();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_produto_COFINSST.' );
        }
    }

    public function create_table_nfe_produto_ICMS() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_produto_ICMS();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_produto_ICMS.' );
        }
    }

    public function create_table_nfe_produto_II() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_produto_II();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_produto_II.' );
        }
    }

    public function create_table_nfe_produto_IPI() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_produto_IPI();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_produto_IPI.' );
        }
    }

    public function create_table_nfe_produto_ISSQN() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_produto_ISSQN();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_produto_ISSQN.' );
        }
    }

    public function create_table_nfe_produto_Opcoes() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_produto_Opcoes();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_produto_Opcoes.' );
        }
    }

    public function create_table_nfe_produto_PIS() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_produto_PIS();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_produto_PIS.' );
        }
    }

    public function create_table_nfe_produto_PISST() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_produto_PISST();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_produto_PISST.' );
        }
    }

    public function create_table_nfe_situacao_tributaria() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_situacao_tributaria();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_situacao_tributaria.' );
        }
    }

    public function create_table_nfe_tipo_especifico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_tipo_especifico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_tipo_especifico.' );
        }
    }

    public function create_table_nfe_transportadora() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_transportadora();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_transportadora.' );
        }
    }

    public function create_table_nfe_uf() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfe_uf();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfe_uf.' );
        }
    }

    public function create_table_nfs_server() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfs_server();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfs_server.' );
        }
    }

    public function create_table_nfs_situacao_tributaria() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfs_situacao_tributaria();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfs_situacao_tributaria.' );
        }
    }

    public function create_table_nfse_erros() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nfse_erros();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nfse_erros.' );
        }
    }

    public function create_table_nota_credito() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nota_credito();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nota_credito.' );
        }
    }

    public function create_table_nota_credito_historico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nota_credito_historico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nota_credito_historico.' );
        }
    }

    public function create_table_nota_fiscal() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nota_fiscal();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nota_fiscal.' );
        }
    }

    public function create_table_nota_fiscal_tmp() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_nota_fiscal_tmp();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_nota_fiscal_tmp.' );
        }
    }

    public function create_table_notas_promissorias() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_notas_promissorias();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_notas_promissorias.' );
        }
    }

    public function create_table_oauth_clients() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_oauth_clients();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_oauth_clients.' );
        }
    }

    public function create_table_oauth_tokens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_oauth_tokens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_oauth_tokens.' );
        }
    }

    public function create_table_orcamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_orcamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_orcamento.' );
        }
    }

    public function create_table_orcamento_cliente() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_orcamento_cliente();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_orcamento_cliente.' );
        }
    }

    public function create_table_orcamento_itens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_orcamento_itens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_orcamento_itens.' );
        }
    }

    public function create_table_ordem_servico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_ordem_servico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_ordem_servico.' );
        }
    }

    public function create_table_ordem_servico_itens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_ordem_servico_itens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_ordem_servico_itens.' );
        }
    }

    public function create_table_ordem_servico_tipo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_ordem_servico_tipo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_ordem_servico_tipo.' );
        }
    }

    public function create_table_origem() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_origem();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_origem.' );
        }
    }

    public function create_table_pagamento_notas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_pagamento_notas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_pagamento_notas.' );
        }
    }

    public function create_table_pais() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_pais();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_pais.' );
        }
    }

    public function create_table_parcela() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_parcela();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_parcela.' );
        }
    }

    public function create_table_permissao_usuario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_permissao_usuario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_permissao_usuario.' );
        }
    }

    public function create_table_posto_registros() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_posto_registros();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_posto_registros.' );
        }
    }

    public function create_table_pro_parametro() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_pro_parametro();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_pro_parametro.' );
        }
    }

    public function create_table_pro_parametro_valor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_pro_parametro_valor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_pro_parametro_valor.' );
        }
    }

    public function create_table_pro_valor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_pro_valor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_pro_valor.' );
        }
    }

    public function create_table_produto() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto.' );
        }
    }

    public function create_table_produto2() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto2();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto2.' );
        }
    }

    public function create_table_produto_apoio() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_apoio();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_apoio.' );
        }
    }

    public function create_table_produto_arrumar_estoque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_arrumar_estoque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_arrumar_estoque.' );
        }
    }

    public function create_table_produto_arrumar_estoque_tmp() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_arrumar_estoque_tmp();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_arrumar_estoque_tmp.' );
        }
    }

    public function create_table_produto_beneficio_fiscal() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_beneficio_fiscal();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_beneficio_fiscal.' );
        }
    }

    public function create_table_produto_busca_prevenda() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_busca_prevenda();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_busca_prevenda.' );
        }
    }

    public function create_table_produto_busca_prevenda_ordem() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_busca_prevenda_ordem();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_busca_prevenda_ordem.' );
        }
    }

    public function create_table_produto_combNF() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_combNF();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_combNF.' );
        }
    }

    public function create_table_produto_configuracoes_comercial() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_configuracoes_comercial();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_configuracoes_comercial.' );
        }
    }

    public function create_table_produto_contabil() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_contabil();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_contabil.' );
        }
    }

    public function create_table_produto_fornecedor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_fornecedor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_fornecedor.' );
        }
    }

    public function create_table_produto_foto() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_foto();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_foto.' );
        }
    }

    public function create_table_produto_icms() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_icms();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_icms.' );
        }
    }

    public function create_table_produto_info_nutricionais() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_info_nutricionais();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_info_nutricionais.' );
        }
    }

    public function create_table_produto_num_parcelas_aux() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_num_parcelas_aux();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_num_parcelas_aux.' );
        }
    }

    public function create_table_produto_pedido_equipamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produto_pedido_equipamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produto_pedido_equipamento.' );
        }
    }

    public function create_table_produtos_apoio() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_apoio();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_apoio.' );
        }
    }

    public function create_table_produtos_composicao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_composicao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_composicao.' );
        }
    }

    public function create_table_produtos_deletados() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_deletados();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_deletados.' );
        }
    }

    public function create_table_produtos_detalhes() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_detalhes();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_detalhes.' );
        }
    }

    public function create_table_produtos_detalhes_agrupagem() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_detalhes_agrupagem();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_detalhes_agrupagem.' );
        }
    }

    public function create_table_produtos_detalhes_dimensoes_caixa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_detalhes_dimensoes_caixa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_detalhes_dimensoes_caixa.' );
        }
    }

    public function create_table_produtos_detalhes_dimensoes_palete() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_detalhes_dimensoes_palete();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_detalhes_dimensoes_palete.' );
        }
    }

    public function create_table_produtos_detalhes_dimensoes_unidade() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_detalhes_dimensoes_unidade();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_detalhes_dimensoes_unidade.' );
        }
    }

    public function create_table_produtos_excluidos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_excluidos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_excluidos.' );
        }
    }

    public function create_table_produtos_reciclagem() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_reciclagem();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_reciclagem.' );
        }
    }

    public function create_table_produtos_removidos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_produtos_removidos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_produtos_removidos.' );
        }
    }

    public function create_table_promocao_kit() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_promocao_kit();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_promocao_kit.' );
        }
    }

    public function create_table_promocao_kit_grade() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_promocao_kit_grade();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_promocao_kit_grade.' );
        }
    }

    public function create_table_promocao_mix() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_promocao_mix();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_promocao_mix.' );
        }
    }

    public function create_table_promocao_mix_desconto() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_promocao_mix_desconto();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_promocao_mix_desconto.' );
        }
    }

    public function create_table_promocao_mix_tempo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_promocao_mix_tempo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_promocao_mix_tempo.' );
        }
    }

    public function create_table_promocao_quantidade() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_promocao_quantidade();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_promocao_quantidade.' );
        }
    }

    public function create_table_relatorio_estoque_mensal() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_relatorio_estoque_mensal();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_relatorio_estoque_mensal.' );
        }
    }

    public function create_table_relatorio_estoque_mensal_produtos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_relatorio_estoque_mensal_produtos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_relatorio_estoque_mensal_produtos.' );
        }
    }

    public function create_table_relatorios_campos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_relatorios_campos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_relatorios_campos.' );
        }
    }

    public function create_table_relatorios_tabelas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_relatorios_tabelas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_relatorios_tabelas.' );
        }
    }

    public function create_table_remetente() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_remetente();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_remetente.' );
        }
    }

    public function create_table_sequencia_assistencia() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_sequencia_assistencia();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_sequencia_assistencia.' );
        }
    }

    public function create_table_sequencia_orcamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_sequencia_orcamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_sequencia_orcamento.' );
        }
    }

    public function create_table_sequencia_ordem_servico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_sequencia_ordem_servico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_sequencia_ordem_servico.' );
        }
    }

    public function create_table_sequencia_pedido() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_sequencia_pedido();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_sequencia_pedido.' );
        }
    }

    public function create_table_setor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_setor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_setor.' );
        }
    }

    public function create_table_solicitacao_reativacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_solicitacao_reativacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_solicitacao_reativacao.' );
        }
    }

    public function create_table_sugestao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_sugestao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_sugestao.' );
        }
    }

    public function create_table_tabela_codigo_anp() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tabela_codigo_anp();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tabela_codigo_anp.' );
        }
    }

    public function create_table_tabela_ncm() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tabela_ncm();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tabela_ncm.' );
        }
    }

    public function create_table_tabela_ncm_vigente() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tabela_ncm_vigente();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tabela_ncm_vigente.' );
        }
    }

    public function create_table_test_apoio() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_test_apoio();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_test_apoio.' );
        }
    }

    public function create_table_tipo_entrada() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tipo_entrada();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tipo_entrada.' );
        }
    }

    public function create_table_tipo_log() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tipo_log();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tipo_log.' );
        }
    }

    public function create_table_tipo_residencia() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tipo_residencia();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tipo_residencia.' );
        }
    }

    public function create_table_tipo_unidade_tmp() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tipo_unidade_tmp();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tipo_unidade_tmp.' );
        }
    }

    public function create_table_tipos_lancamentos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tipos_lancamentos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tipos_lancamentos.' );
        }
    }

    public function create_table_titulos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_titulos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_titulos.' );
        }
    }

    public function create_table_titulos_movimentacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_titulos_movimentacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_titulos_movimentacao.' );
        }
    }

    public function create_table_titulos_recebafacil() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_titulos_recebafacil();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_titulos_recebafacil.' );
        }
    }

    public function create_table_titulos_recebafacil_historico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_titulos_recebafacil_historico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_titulos_recebafacil_historico.' );
        }
    }

    public function create_table_titulos_retorno() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_titulos_retorno();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_titulos_retorno.' );
        }
    }

    public function create_table_tmp_datas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_datas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_datas.' );
        }
    }

    public function create_table_tmp_datas_afiliacoes() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_datas_afiliacoes();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_datas_afiliacoes.' );
        }
    }

    public function create_table_tmp_datas_afiliacoes1() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_datas_afiliacoes1();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_datas_afiliacoes1.' );
        }
    }

    public function create_table_tmp_datas_afiliacoes_comparar() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_datas_afiliacoes_comparar();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_datas_afiliacoes_comparar.' );
        }
    }

    public function create_table_tmp_datas_atendimento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_datas_atendimento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_datas_atendimento.' );
        }
    }

    public function create_table_tmp_datas_cancelamentos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_datas_cancelamentos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_datas_cancelamentos.' );
        }
    }

    public function create_table_tmp_datas_equipamentos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_datas_equipamentos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_datas_equipamentos.' );
        }
    }

    public function create_table_tmp_datas_teste() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_datas_teste();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_datas_teste.' );
        }
    }

    public function create_table_tmp_fat_bonificada() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_fat_bonificada();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_fat_bonificada.' );
        }
    }

    public function create_table_tmp_grafico_afiliacoes() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_grafico_afiliacoes();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_grafico_afiliacoes.' );
        }
    }

    public function create_table_tmp_grafico_afiliacoes_consultor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_grafico_afiliacoes_consultor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_grafico_afiliacoes_consultor.' );
        }
    }

    public function create_table_tmp_grafico_cancelados() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_grafico_cancelados();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_grafico_cancelados.' );
        }
    }

    public function create_table_tmp_imp_classificacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_imp_classificacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_imp_classificacao.' );
        }
    }

    public function create_table_tmp_imp_cliente() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_imp_cliente();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_imp_estoque.' );
        }
    }

    public function create_table_tmp_imp_estoque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_imp_estoque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela agenda.' );
        }
    }

    public function create_table_tmp_imp_fornecedor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_imp_fornecedor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_imp_fornecedor.' );
        }
    }

    public function create_table_tmp_imp_produto() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_imp_produto();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_imp_produto.' );
        }
    }

    public function create_table_tmp_meses_label() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_meses_label();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_meses_label.' );
        }
    }

    public function create_table_tmp_produto_aux() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_produto_aux();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_produto_aux.' );
        }
    }

    public function create_table_tmp_ranking_agendamento_diario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_ranking_agendamento_diario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_ranking_agendamento_diario.' );
        }
    }

    public function create_table_tmp_ranking_atendimento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_ranking_atendimento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_ranking_atendimento.' );
        }
    }

    public function create_table_tmp_ranking_geral() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_ranking_geral();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_ranking_geral.' );
        }
    }

    public function create_table_tmp_sped_150() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_sped_150();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_sped_150.' );
        }
    }

    public function create_table_tmp_sped_190() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_sped_190();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_sped_190.' );
        }
    }

    public function create_table_tmp_sped_unidade() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tmp_sped_unidade();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tmp_sped_unidade.' );
        }
    }

    public function create_table_torpedo_campanha() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_campanha();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_campanha.' );
        }
    }

    public function create_table_torpedo_campanha_agendamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_campanha_agendamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_campanha_agendamento.' );
        }
    }

    public function create_table_torpedo_campanha_bkp_excluidos() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_campanha_bkp_excluidos();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_campanha_bkp_excluidos.' );
        }
    }

    public function create_table_torpedo_campanha_fixa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_campanha_fixa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_campanha_fixa.' );
        }
    }

    public function create_table_torpedo_campanha_fixa_ignorar() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_campanha_fixa_ignorar();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_campanha_fixa_ignorar.' );
        }
    }

    public function create_table_torpedo_campanha_lista() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_campanha_lista();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_campanha_lista.' );
        }
    }

    public function create_table_torpedo_lista() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_lista();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_lista.' );
        }
    }

    public function create_table_torpedo_lista_telefones() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_lista_telefones();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_lista_telefones.' );
        }
    }

    public function create_table_torpedo_lista_log() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_lista_log();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_lista_log.' );
        }
    }

    public function create_table_torpedo_master() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_master();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_master.' );
        }
    }

    public function create_table_torpedo_user() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_torpedo_user();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_torpedo_user.' );
        }
    }

    public function create_table_transportadora() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_transportadora();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_transportadora.' );
        }
    }

    public function create_table_transportadora_placa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_transportadora_placa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_transportadora_placa.' );
        }
    }

    public function create_table_tributacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_tributacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_tributacao.' );
        }
    }

    public function create_table_unidade() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_unidade();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_unidade.' );
        }
    }

    public function create_table_unidades_fracionamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_unidades_fracionamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_unidades_fracionamento.' );
        }
    }

    public function create_table_users() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_users();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_users.' );
        }
    }

    public function create_table_usuario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_usuario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_usuario.' );
        }
    }

    public function create_table_vale_presente() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_vale_presente();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_vale_presente.' );
        }
    }

    public function create_table_vale_presente_historico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_vale_presente_historico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_vale_presente_historico.' );
        }
    }

    public function create_table_vale_presente_new() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_vale_presente_new();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_vale_presente_new.' );
        }
    }

    public function create_table_valor() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_valor();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_valor.' );
        }
    }

    public function create_table_valor_extra_caixa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_valor_extra_caixa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_valor_extra_caixa.' );
        }
    }

    public function create_table_valor_inicial_caixa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_valor_inicial_caixa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_valor_inicial_caixa.' );
        }
    }

    public function create_table_valor_sangria() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_valor_sangria();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_valor_sangria.' );
        }
    }

    public function create_table_venda() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda.' );
        }
    }

    public function create_table_venda_adiantamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_adiantamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_adiantamento.' );
        }
    }

    public function create_table_venda_consignacao_devolucao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_consignacao_devolucao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_consignacao_devolucao.' );
        }
    }

    public function create_table_venda_devolucao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_devolucao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_informacoes.' );
        }
    }

    public function create_table_venda_informacoes() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_informacoes();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela agenda.' );
        }
    }

    public function create_table_venda_itens() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_itens();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_itens.' );
        }
    }

    public function create_table_venda_itens_automoveis() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_itens_automoveis();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_itens_automoveis.' );
        }
    }

    public function create_table_venda_itens_devolucao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_itens_devolucao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_itens_devolucao.' );
        }
    }

    public function create_table_venda_juros_parcelamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_juros_parcelamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_juros_parcelamento.' );
        }
    }

    public function create_table_venda_locacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_locacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_locacao.' );
        }
    }

    public function create_table_venda_nfse_informacoes() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_nfse_informacoes();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_nfse_informacoes.' );
        }
    }

    public function create_table_venda_notas_eletronicas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_notas_eletronicas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_notas_eletronicas.' );
        }
    }

    public function create_table_venda_notas_eletronicas_lixo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_notas_eletronicas_lixo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_notas_eletronicas_lixo.' );
        }
    }

    public function create_table_venda_optica() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_optica();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_optica.' );
        }
    }

    public function create_table_venda_pagamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_pagamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_pagamento.' );
        }
    }

    public function create_table_venda_pagamento_cheque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_pagamento_cheque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_pagamento_cheque.' );
        }
    }

    public function create_table_venda_pagamento_ecommerce() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_pagamento_ecommerce();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_pagamento_ecommerce.' );
        }
    }

    public function create_table_venda_parcelas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_parcelas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_parcelas.' );
        }
    }

    public function create_table_venda_pgto_temp() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_venda_pgto_temp();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_venda_pgto_temp.' );
        }
    }

    public function create_table_vendas_funcionario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_vendas_funcionario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_vendas_funcionario.' );
        }
    }

    public function create_table_view_venda_parcelas() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_view_venda_parcelas();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_view_venda_parcelas.' );
        }
    }

    public function create_table_vp_historico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_vp_historico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_vp_historico.' );
        }
    }

    public function create_table_wc_menu() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_wc_menu();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_wc_menu.' );
        }
    }

    public function create_table_wc_permissao_menu() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_wc_permissao_menu();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_wc_permissao_menu.' );
        }
    }

    public function create_table_wc_submenu() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_wc_submenu();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_wc_submenu.' );
        }
    }

    public function create_table_webc_configuracoes_sistema() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_configuracoes_sistema();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_configuracoes_sistema.' );
        }
    }

    public function create_table_webc_grupo_usuarios() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_grupo_usuarios();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_grupo_usuarios.' );
        }
    }

    public function create_table_webc_grupo_usuarios_cadastro() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_grupo_usuarios_cadastro();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_grupo_usuarios_cadastro.' );
        }
    }

    public function create_table_webc_modulo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_modulo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_modulo.' );
        }
    }

    public function create_table_webc_permissao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_permissao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_permissao.' );
        }
    }

    public function create_table_webc_permissao_grupo_usuarios() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_permissao_grupo_usuarios();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_permissao_grupo_usuarios.' );
        }
    }

    public function create_table_webc_permissao_modulo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_permissao_modulo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_permissao_modulo.' );
        }
    }

    public function create_table_webc_permissao_usuario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_permissao_usuario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_permissao_usuario.' );
        }
    }

    public function create_table_webc_posto_bomba() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_posto_bomba();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_posto_bomba.' );
        }
    }

    public function create_table_webc_posto_bomba_bico() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_posto_bomba_bico();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_posto_bomba_bico.' );
        }
    }

    public function create_table_webc_posto_tanque() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_posto_tanque();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_posto_tanque.' );
        }
    }

    public function create_table_webc_situacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_situacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_situacao.' );
        }
    }

    public function create_table_webc_sub_modulo() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_sub_modulo();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_sub_modulo.' );
        }
    }

    public function create_table_webc_tipo_permissao_usuario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_tipo_permissao_usuario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_tipo_permissao_usuario.' );
        }
    }

    public function create_table_webc_tipo_venda() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_tipo_venda();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_tipo_venda.' );
        }
    }

    public function create_table_webc_usuario() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_usuario();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_usuario.' );
        }
    }

    public function create_table_webc_vfx_syncloja() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_vfx_syncloja();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_vfx_syncloja.' );
        }
    }

    public function create_table_webc_visualizacao_imediata() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_visualizacao_imediata();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_visualizacao_imediata.' );
        }
    }

    public function create_table_webc_visualizacao_imediata_dados() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_webc_visualizacao_imediata_dados();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_webc_visualizacao_imediata_dados.' );
        }
    }

    public function create_table_whatsapp_campanha() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_whatsapp_campanha();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_whatsapp_campanha.' );
        }
    }

    public function create_table_whatsapp_campanha_agendamento() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_whatsapp_campanha_agendamento();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_whatsapp_campanha_agendamento.' );
        }
    }

    public function create_table_whatsapp_campanha_fixa() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_whatsapp_campanha_fixa();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_whatsapp_campanha_fixa.' );
        }
    }

    public function create_table_whatsapp_campanha_fixa_ignorar() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_whatsapp_campanha_fixa_ignorar();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_whatsapp_campanha_fixa_ignorar.' );
        }
    }

    public function create_table_whatsapp_lista() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_whatsapp_lista();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_whatsapp_lista.' );
        }
    }

    public function create_table_whatsapp_lista_telefones() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_whatsapp_lista_telefones();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_whatsapp_lista_telefones.' );
        }
    }

    public function create_table_whatsapp_log() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_whatsapp_log();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_whatsapp_log.' );
        }
    }

    public function create_table_whatsapp_master() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_whatsapp_master();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_whatsapp_master.' );
        }
    }

    public function create_table_whatsapp_transacao() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_whatsapp_transacao();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_whatsapp_transacao.' );
        }
    }

    public function create_table_whatsapp_user() {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_table_whatsapp_user();
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_table_whatsapp_user.' );
        }
    }

    public function create_acesso_webcontrol($codloja,$nomefantasia,$cpfsocio1,$email,$login,$senha, $uf) {
        try{
            $model = new CreateBaseDadosModel($this);
            return $model->create_acesso_webcontrol($codloja,$nomefantasia,$cpfsocio1,$email,$login,$senha, $uf);
        }catch(Exception $e){
            throw new Exception ( 'Não foi possível criar a tabela create_acesso_webcontrol.' );
        }
    }

}