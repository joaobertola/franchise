<?php

class CreateBaseDadosModel
{

    private $create_base_dados;
    private $log;

    public function __construct(CreateBaseDadosController $create_base_dados)
    {
        try {
            $this->create_base_dados = $create_base_dados; //objeto controller
            $this->log = '';
        } catch (PDOException $e) {
            throw new Exception('Não foi possível efetuar a conexão com o banco de dados.');
        }
    }

    function create_data_base()
    {
        try
        {
            $data_base_name = $this->create_base_dados->getDatabase();
            $conn = new PDO("mysql:host=10.2.2.3", 'csinform', 'inform4416#scf');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE IF NOT EXISTS  $data_base_name";
            $conn->exec($sql);
            $sql = "use $data_base_name";
            $conn->exec($sql);
        }
        catch(PDOException $e)
        {
            echo "Error".$e->getMessage();
        }
    }

    function useDataBase() {
        $data_base_name = $this->create_base_dados->getDatabase();
        $conn = new PDO("mysql:host=10.2.2.3", 'csinform', 'inform4416#scf');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    function create_table_acesso_filiacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `acesso_filiacao`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_matriz_filial` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NOT NULL,
                `id_cadastro_filial` INT(10) NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `cpf_funcionario` VARCHAR(12) NULL DEFAULT NULL,
                `permissoes` VARCHAR(1000) NULL DEFAULT NULL COMMENT 'lista de ids de permissoes do usuario, concatenado com ;',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `usuario_alteracao` INT(10) UNSIGNED NOT NULL,
                `ativo` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - inativo, 1 - ativo',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ACESSO FILIAÇÃO criada com sucesso.<br>");
        } catch (PDOException $e) { 
            echo "Error".$e->getMessage();
        }
    }

    function create_table_agenda()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `agenda`
            (
                `evento_id` INT(11) NOT NULL AUTO_INCREMENT,
                `usuario_id` INT(11) NULL,
                `codloja` INT(11) NULL DEFAULT NULL,
                `title` VARCHAR(50) NULL DEFAULT NULL,
                `allday` TINYINT(1) NULL DEFAULT NULL,
                `start` DATETIME NULL DEFAULT NULL,
                `end` DATETIME NULL DEFAULT NULL,
                `url` VARCHAR(100) NULL DEFAULT NULL,
                `description` VARCHAR(200) NULL DEFAULT NULL,
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                `id_parceiro` INT(11) NULL DEFAULT NULL,
                `ativo` TINYINT(4) NULL DEFAULT '1',
                PRIMARY KEY (`evento_id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela AGENDA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_agenda_usuario_parceiro()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `agenda_usuario_parceiro`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `Nome` VARCHAR(50) NULL DEFAULT NULL,
                `Telefone` VARCHAR(15) NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela AGENDA USUÁRIO PARCEIRO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_agendamento_tarefa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `agendamento_tarefa`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) UNSIGNED NOT NULL,
                `informacoes` JSON NOT NULL,
                `status` ENUM('A', 'C', 'R', 'F') NOT NULL DEFAULT 'A' COMMENT 'A - Aguardando\<br>C - Cancelado\<br>R - Recorrente\<br>F - Finalizado',
                `data_agendamento` DATETIME NOT NULL,
                `data_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_concluido` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela AGENDAMENTO TAREFA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_agendamento_tarefa_log()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `agendamento_tarefa_log`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `data_envio` DATETIME NULL DEFAULT NULL,
                `status` VARCHAR(1) NULL DEFAULT NULL,
                `email` VARCHAR(45) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela AGENDAMENTO TAREFA LOG criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_assistencia_tecnica()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `assistencia_tecnica`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `id_garantia` INT(11) NULL DEFAULT NULL,
                `aparelho` VARCHAR(255) NULL DEFAULT NULL,
                `marca` INT(11) NULL DEFAULT NULL,
                `modelo` VARCHAR(255) NULL DEFAULT NULL,
                `n_serie` VARCHAR(255) NULL DEFAULT NULL,
                `voltagem` INT(11) NULL DEFAULT NULL,
                `servico` LONGTEXT NULL DEFAULT NULL,
                `servico_prazo` DATE NULL DEFAULT NULL,
                `defeitos` LONGTEXT NULL DEFAULT NULL,
                `acessorios` LONGTEXT NULL DEFAULT NULL,
                `observacao` LONGTEXT NULL DEFAULT NULL,
                `status` INT(11) NULL DEFAULT '0' COMMENT '0    \'Em Andamento\',\\\<br>1   \'Aguardando Peças\',\\\<br>2    \'Liberado para Entrega\',\\\<br>3   \'Finalizado\',\\\<br>4    \'Aguardando Aprovação\',\\\<br>5    \'Aprovado\',\\\<br>6    \'Não Aprovado\', 7  \'aguardando avaliacao\' ',
                `data_cadastro` DATETIME NULL DEFAULT NULL,
                `delete_date` DATETIME NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ASSISTENCIA TECNICA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_assistencia_tecnica_conclusao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `assistencia_tecnica_conclusao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_assistencia` INT(11) NULL DEFAULT NULL,
                `conclusao` LONGTEXT NULL DEFAULT NULL,
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                `valor` FLOAT NULL DEFAULT NULL,
                `save_date` DATETIME NULL DEFAULT NULL,
                `update_date` DATETIME NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ASSISTENCIA_TECNICA_CONCLUSAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_assistencia_tecnica_garantia()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `assistencia_tecnica_garantia`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(125) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ASSISTENCIA_TECNICA_GARANTIA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_assistencia_tecnica_marcas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `assistencia_tecnica_marcas`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `nome` VARCHAR(45) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ASSISTENCIA_TECNICA_MARCAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_assistencia_tecnica_observacoes()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `assistencia_tecnica_observacoes`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `apelido` VARCHAR(150) NULL DEFAULT NULL,
                `descricao` TEXT NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ASSISTENCIA_TECNICA_OBSERVACOES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_assistencia_tecnica_produtos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `assistencia_tecnica_produtos`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_assistencia` INT(11) NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `insert_date` DATETIME NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ASSISTENCIA_TECNICA_PRODUTOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_assistencia_tecnica_voltagem()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `assistencia_tecnica_voltagem`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(45) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ASSISTENCIA_TECNICA_VOLTAGEM criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_atendimento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `atendimento`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_cliente` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data_atendimento` DATE NULL DEFAULT NULL,
                `hora_atendimento` TIME NULL DEFAULT NULL,
                `descricao_atendimento` TEXT NULL DEFAULT NULL,
                `id_tipo_atendimento` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ATENDIMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_atendimento_fornecedor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `atendimento_fornecedor`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_fornecedor` INT(11) NULL DEFAULT NULL,
                `data_atendimento` DATE NULL DEFAULT NULL,
                `hora_atendimento` TIME NULL DEFAULT NULL,
                `descricao_atendimento` TEXT NULL DEFAULT NULL,
                `id_tipo_atendimento` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ATENDIMENTO_FORNECEDOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_atendimento_funcionario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `atendimento_funcionario`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                `data_atendimento` DATE NULL DEFAULT NULL,
                `hora_atendimento` TIME NULL DEFAULT NULL,
                `descricao_atendimento` TEXT NULL DEFAULT NULL,
                `id_tipo_atendimento` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ATENDIMENTO_FUNCIONARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_atendimento_tipo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `atendimento_tipo`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ATENDIMENTO_TIPO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_atendimento_transportadora()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `atendimento_transportadora`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_transportadora` INT(11) NULL DEFAULT NULL,
                `data_atendimento` DATE NULL DEFAULT NULL,
                `hora_atendimento` TIME NULL DEFAULT NULL,
                `descricao_atendimento` TEXT NULL DEFAULT NULL,
                `id_tipo_atendimento` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ATENDIMENTO_TRANSPORTADORA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_autorizacao_cielo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `autorizacao_cielo`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `venda_id` INT(11) NULL DEFAULT NULL,
                `tid` VARCHAR(50) NULL DEFAULT NULL,
                `data_autenticacao` DATE NULL DEFAULT NULL,
                `hora_autenticacao` TIME NULL DEFAULT NULL,
                `dt_autorizacao` DATE NULL DEFAULT NULL,
                `hora_autorizacao` TIME NULL DEFAULT NULL,
                `data_captura` DATE NULL DEFAULT NULL,
                `hora_captura` TIME NULL DEFAULT NULL,
                `status` ENUM('A', 'R', 'C', 'N', 'X') NULL DEFAULT 'A' COMMENT 'Aguardando, Realizado, Confirmado, Negada, X=Cancelado',
                `ip_autorizacao` VARCHAR(15) NULL DEFAULT NULL,
                `xml_retorno` LONGTEXT NULL DEFAULT NULL,
                `valor` VARCHAR(15) NULL DEFAULT NULL,
                `data_postagem` DATE NULL DEFAULT NULL,
                `nr_objeto_correio` VARCHAR(20) NULL DEFAULT NULL,
                `funcionario_id_correio` INT(11) NULL DEFAULT NULL,
                `funcionario_id_estorno` INT(11) NULL DEFAULT NULL,
                `data_estorno` DATE NULL DEFAULT NULL,
                `obs_estorno` TEXT NULL DEFAULT NULL,
                `vencimento_bol` DATE NULL DEFAULT NULL,
                `funcionario_id_cancelamento` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela AUTORIZACAO_CIELO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_aux_venda_faturado_impressao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `aux_venda_faturado_impressao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `impresso` INT(11) NULL DEFAULT '0',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela AUX_VENDA_FATURADO_IMPRESSAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_auxiliar_envio_sms()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `auxiliar_envio_sms`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `usuario` VARCHAR(255) NULL DEFAULT NULL,
                `cod_campanha` VARCHAR(11) NULL DEFAULT NULL,
                `data_envio` VARCHAR(50) NULL DEFAULT NULL,
                `codigo_status` VARCHAR(10) NULL DEFAULT NULL COMMENT 'T - Sucesso, F - Falha',
                `status` VARCHAR(30) NULL DEFAULT NULL,
                `origem` VARCHAR(10) NULL DEFAULT NULL COMMENT 'S - Recebida, E - Enviada',
                `celular` VARCHAR(255) NULL DEFAULT NULL,
                `mensagem` TEXT NULL DEFAULT NULL,
                `servidor_saida` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela AUXILIAR_ENVIO_SMS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_auxiliar_importacao_produto()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `auxiliar_importacao_produto`
            (
                `id_cadastro` INT(11) NULL,
                `data_hora_importacao` DATETIME NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela AUCILIAR_IMPORTACAO_PRODUTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_banco()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `banco`
            (
                `id` INT NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(60) NULL DEFAULT NULL,
                `logo` TEXT NULL DEFAULT NULL,
                `habilitado_wc` ENUM('S', 'N') NULL DEFAULT 'N',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela BANCO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_boleto_doc()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `boleto_doc`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `codloja` INT(11) NULL DEFAULT NULL,
                `numdoc` VARCHAR(105) NULL DEFAULT NULL,
                `dia` DATE NULL DEFAULT NULL,
                `hora` TIME NULL DEFAULT NULL,
                `log_ref` INT(11) NULL DEFAULT NULL COMMENT '1 - log boleto gerado  (inform/boleto/boleto-log.php)',
                `count` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela BOLETO_DOC criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cadastro()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cadastro`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `razaosoc` VARCHAR(60) NOT NULL DEFAULT '',
                `insc` VARCHAR(14) NOT NULL DEFAULT '',
                `nomefantasia` VARCHAR(50) NULL DEFAULT NULL,
                `uf` CHAR(2) NOT NULL DEFAULT '',
                `cidade` VARCHAR(30) NOT NULL DEFAULT '',
                `bairro` VARCHAR(30) NOT NULL DEFAULT '',
                `end` VARCHAR(60) NOT NULL DEFAULT '',
                `numero` VARCHAR(10) NULL DEFAULT NULL,
                `complemento` VARCHAR(30) NULL DEFAULT NULL,
                `cep` VARCHAR(10) NOT NULL DEFAULT '',
                `fone` VARCHAR(15) NULL DEFAULT NULL,
                `fax` VARCHAR(15) NULL DEFAULT NULL,
                `email` VARCHAR(60) NULL DEFAULT NULL,
                `email2` VARCHAR(60) NULL DEFAULT NULL,
                `tx_mens` DECIMAL(5,2) NOT NULL DEFAULT '0.00',
                `tx_adesao` DECIMAL(6,2) NOT NULL DEFAULT '0.00',
                `debito` ENUM('B', 'A') NOT NULL DEFAULT 'B',
                `boleto` DECIMAL(4,2) NOT NULL DEFAULT '0.00',
                `carteira` TINYINT(4) NOT NULL DEFAULT '0',
                `diapagto` TINYINT(4) NOT NULL DEFAULT '0',
                `id_franquia` BIGINT(20) NOT NULL DEFAULT '0',
                `codv` INT(5) NOT NULL DEFAULT '0',
                `dt_cad` DATE NOT NULL DEFAULT '0000-00-00',
                `dt_exp` DATE NOT NULL DEFAULT '0000-00-00',
                `dt_recis` DATE NULL DEFAULT NULL,
                `sitcli` TINYINT(3) NOT NULL DEFAULT '0' COMMENT '0 - Ativo  1 - Bloqueado  2 - Cancelado  3 - Bloqueio Virtual',
                `obs` LONGTEXT NULL DEFAULT NULL,
                `tx_renpac` DECIMAL(4,2) NULL DEFAULT NULL,
                `classificacao` VARCHAR(25) NOT NULL DEFAULT 'Mensal',
                `classe` INT(3) NULL DEFAULT '0',
                `tit_atrazado` INT(1) NOT NULL DEFAULT '0',
                `codf` INT(5) NOT NULL DEFAULT '0',
                `contrato` VARCHAR(20) NOT NULL DEFAULT '',
                `id_ramo` INT(5) NULL DEFAULT NULL,
                `registro` VARCHAR(15) NOT NULL DEFAULT '',
                `origem` INT(3) NULL DEFAULT NULL,
                `celular` VARCHAR(15) NULL DEFAULT NULL,
                `id_operadora` INT(11) NULL DEFAULT NULL,
                `fone_res` VARCHAR(15) NULL DEFAULT NULL,
                `socio1` VARCHAR(35) NULL DEFAULT NULL,
                `socio2` VARCHAR(35) NULL DEFAULT NULL,
                `cpfsocio1` VARCHAR(14) NULL DEFAULT NULL,
                `cpfsocio2` VARCHAR(14) NULL DEFAULT NULL,
                `emissao_financeiro` INT(1) NULL DEFAULT '1',
                `pendencia_contratual` INT(1) NULL DEFAULT '1',
                `dt_regularizacao` DATE NULL DEFAULT NULL,
                `sit_cobranca` INT(1) NULL DEFAULT '0',
                `vendedor` VARCHAR(20) NULL DEFAULT NULL,
                `ramo_atividade` VARCHAR(25) NULL DEFAULT NULL,
                `banco_cliente` INT(3) NULL DEFAULT NULL,
                `agencia_cliente` VARCHAR(5) NULL DEFAULT NULL,
                `conta_cliente` VARCHAR(20) NULL DEFAULT NULL,
                `tpconta` INT(1) NOT NULL DEFAULT '1' COMMENT '1-conta corrente; 2-poupança',
                `cpfcnpj_doc` VARCHAR(20) NULL DEFAULT NULL,
                `nome_doc` VARCHAR(60) NULL DEFAULT NULL,
                `tx_mens_anterior` DECIMAL(5,2) NOT NULL DEFAULT '0.00',
                `qtd_acessos` BIGINT(10) UNSIGNED NOT NULL DEFAULT '0',
                `fx_inicio` VARCHAR(10) NULL DEFAULT NULL,
                `fx_final` VARCHAR(10) NULL DEFAULT NULL,
                `logomarca` BLOB NULL DEFAULT NULL,
                `tx_extra` ENUM('SIM', 'NAO') NULL DEFAULT 'SIM',
                `ctrl_neg_equifax` INT(11) NULL DEFAULT '0',
                `renegociacao_tabela` DATE NULL DEFAULT NULL,
                `id_franquia_jr` BIGINT(5) NULL DEFAULT '0',
                `liberado_web_control` ENUM('S', 'N') NULL DEFAULT 'N',
                `dt_libera_web` DATE NULL DEFAULT NULL,
                `hora_cadastro` TIME NULL DEFAULT NULL,
                `atendente_resp` VARCHAR(100) NULL DEFAULT NULL,
                `mensalidade_solucoes` DECIMAL(12,2) NULL DEFAULT '19.90' COMMENT 'Licença Mensal - Software e Soluções',
                `senha_baixatitulo` BIGINT(6) NULL DEFAULT '0',
                `codigo_equifax` BIGINT(12) NULL DEFAULT NULL,
                `cx_equifax_usuario` BIGINT(8) NULL DEFAULT NULL,
                `cx_equifax_senha` VARCHAR(8) NULL DEFAULT NULL,
                `inscricao_estadual` VARCHAR(14) NULL DEFAULT NULL,
                `cnae_fiscal` VARCHAR(7) NULL DEFAULT NULL,
                `inscricao_municipal` VARCHAR(14) NULL DEFAULT NULL,
                `inscricao_estadual_tributario` VARCHAR(14) NULL DEFAULT NULL,
                `numero_endereco` VARCHAR(10) NULL DEFAULT NULL,
                `tipo_cliente` ENUM('A', 'N') NULL DEFAULT 'N' COMMENT 'A-Administrador  N-Normal',
                `liberado_contabil` ENUM('S', 'N') NULL DEFAULT 'N',
                `dt_libera_contabil` DATE NULL DEFAULT NULL,
                `dt_pgto_comissao_vendedor` DATE NULL DEFAULT NULL,
                `valor_comissao_vendedor` DECIMAL(10,2) NULL DEFAULT '0.00',
                `dt_atualizacao_email` DATE NULL DEFAULT NULL,
                `ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `dt_envio_email_cobranca` DATE NULL DEFAULT NULL,
                `dt_atualizacao_virtual` DATE NULL DEFAULT NULL,
                `permissao_acesso_pp` ENUM('0', '1') NULL DEFAULT '1' COMMENT '0 - Liberado  - 1 - Negado',
                `pendencia_contrato` INT(1) NULL DEFAULT '0' COMMENT '0 - Normal   1 - Pendente',
                `dt_pgto_fixo` DATE NULL DEFAULT NULL,
                `vr_pgto_fixo` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
                `data_consultoria` DATE NULL DEFAULT NULL,
                `nome_consultoria` VARCHAR(30) NULL DEFAULT NULL,
                `regime_tributacao` INT(1) NULL DEFAULT '0' COMMENT '0 - Tributacao Normal      1 - Simples Nacional  2 - Simples Nacional  / Excesso sublime  de receita bruta',
                `liberar_nfe` ENUM('S', 'N') NULL DEFAULT 'N' COMMENT 'Liberar acesso a NFe',
                `tipo_nfe` ENUM('NFe', 'NFCe') NULL DEFAULT NULL,
                `emitir_nfs` ENUM('S', 'N') NULL DEFAULT 'N',
                `status_nfe` ENUM('H', 'P', 'N') NULL DEFAULT 'N' COMMENT 'H - Homologação   P- Produção',
                `vr_max_limite_crediario` DECIMAL(12,2) NULL DEFAULT '5000.00',
                `limite_credito` DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT '0.00',
                `limite_credito_liberado` ENUM('S', 'N') NULL DEFAULT 'N',
                `user_pendencia` INT(11) NOT NULL DEFAULT '0',
                `qtd_pdv_caixa` INT(1) NULL DEFAULT '1',
                `qtd_codigo_coluna_balanca` INT(1) NULL DEFAULT '4',
                `nfe` ENUM('S', 'N') NULL DEFAULT 'N',
                `nfce` ENUM('S', 'N') NULL DEFAULT 'N',
                `cte` ENUM('S', 'N') NULL DEFAULT 'N',
                `nfse` ENUM('S', 'N') NULL DEFAULT 'N',
                `cfiscal` ENUM('S', 'N') NULL DEFAULT 'N',
                `mdfe` ENUM('S', 'N') NULL DEFAULT 'N',
                `casas_decimais` INT(2) NULL DEFAULT '2',
                `contador_nome` VARCHAR(50) NOT NULL,
                `contador_telefone` VARCHAR(15) NOT NULL,
                `contador_celular` VARCHAR(15) NOT NULL,
                `contador_email1` VARCHAR(100) NOT NULL,
                `contador_email2` VARCHAR(100) NOT NULL,
                `contador_cpfcnpj` VARCHAR(20) NULL DEFAULT NULL,
                `contador_crc` VARCHAR(25) NULL DEFAULT NULL,
                `contador_cep` VARCHAR(10) NULL DEFAULT NULL,
                `contador_endereco` VARCHAR(60) NULL DEFAULT NULL,
                `contador_numero` VARCHAR(10) NULL DEFAULT NULL,
                `contador_complemento` VARCHAR(30) NULL DEFAULT NULL,
                `contador_bairro` VARCHAR(30) NULL DEFAULT NULL,
                `contador_cod_mun` VARCHAR(10) NULL DEFAULT NULL,
                `multa_contratual` INT(1) NULL DEFAULT '0' COMMENT '0 - Nao - 1 Sim',
                `iss_padrao` VARCHAR(9) NULL DEFAULT 'ABRASF',
                `dt_pgto_adesao` DATE NULL DEFAULT NULL,
                `vr_pgto_adesao` DECIMAL(10,2) NULL DEFAULT '0.00',
                `contadorSN` ENUM('S', 'N') NULL DEFAULT 'N',
                `baixa_automatica` ENUM('S', 'N') NOT NULL DEFAULT 'S' COMMENT 'CAMPO USADO PARA DAR BAIXA AUTOMATICA NAS CONTAS A RECEBER QUANDO FOR CARTAO DE CREDITO OU CHEQUE',
                `agendador` VARCHAR(255) NULL DEFAULT NULL,
                `id_consultor` BIGINT(11) NOT NULL,
                `id_agendador` BIGINT(11) NOT NULL,
                `liberacao_receita_nfc` ENUM('S', 'N') NULL DEFAULT 'N',
                `liberacao_receita_nfe` ENUM('S', 'N') NULL DEFAULT 'N',
                `hash` VARCHAR(60) NULL DEFAULT NULL,
                `vr_pgto_comissao` DECIMAL(10,2) NULL DEFAULT NULL,
                `dt_pgto_comissao` DATETIME NULL DEFAULT NULL,
                `compartilhar_comanda` ENUM('S', 'N') NULL DEFAULT 'N' COMMENT 'FLAG PARA COMPARTILHAMENTO DE COMANDA ENTRE MAIS DE UMA PESSOA',
                `pendencia_frente_caixa` TINYINT(4) NULL DEFAULT '0',
                `cnpj_empresa_faturar` VARCHAR(14) NULL DEFAULT NULL,
                `flag_resp_pgto1` CHAR(1) NULL DEFAULT '0' COMMENT '0-False 1-True',
                `flag_resp_pgto2` CHAR(1) NULL DEFAULT '0' COMMENT '0-False 1-True',
                `flag_resp_pgto3` CHAR(1) NULL DEFAULT '0' COMMENT '0-False 1-True',
                `nom_resp_pgto1` VARCHAR(40) NULL DEFAULT NULL,
                `nom_resp_pgto2` VARCHAR(40) NULL DEFAULT NULL,
                `nom_resp_pgto3` VARCHAR(40) NULL DEFAULT NULL,
                `data_sync_inativo` DATETIME NULL DEFAULT NULL,
                `nfs_wc_obrigatorio` ENUM('S', 'N') NULL DEFAULT 'N',
                `email_host_server` VARCHAR(60) NULL DEFAULT NULL,
                `email_password` VARCHAR(30) NULL DEFAULT NULL,
                `blq_pendencia_senha` INT(1) NULL DEFAULT '0' COMMENT '0 - Bloqueado - 1 Liberado',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CADASTRO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cadastro_aut_notas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cadastro_aut_notas`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` BIGINT(20) NULL DEFAULT NULL,
                `cpfcnpj` BIGINT(20) NULL DEFAULT NULL,
                `email` VARCHAR(60) NULL DEFAULT NULL,
                `tipo_doc` ENUM('1', '2') NULL DEFAULT '2' COMMENT '1-CPF     2-CNPJ',
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CADASTRO_AUT_NOTAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cadastro_controles()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cadastro_controles`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_forma_pagamento` INT(11) NOT NULL DEFAULT '0',
                `numero` INT(11) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CADASTRO_CONTROLE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cadastro_convenio_bancario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cadastro_convenio_bancario`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_banco` CHAR(3) NOT NULL DEFAULT '0',
                `carteira` INT(11) NOT NULL,
                `agencia` VARCHAR(11) NOT NULL DEFAULT '0',
                `agencia_dv` CHAR(1) NULL DEFAULT NULL,
                `conta` INT(11) UNSIGNED NOT NULL,
                `conta_dv` INT(11) UNSIGNED NOT NULL,
                `seq_boleto` INT(11) NULL DEFAULT '1',
                `ativo` BIT(1) NULL DEFAULT b'0',
                `cod_convenio` INT(11) UNSIGNED NULL DEFAULT NULL,
                `chave_crypto` VARCHAR(245) NULL DEFAULT NULL,
                `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `data_atualizacao` DATETIME NULL DEFAULT NULL,
                `id_usuario_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario_atualizacao` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`, `id_cadastro`, `id_banco`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CADASTRO_CONVENIO_BANCARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cadastro_imposto_padrao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cadastro_imposto_padrao`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `origem_nfe` INT(1) NULL DEFAULT '0',
                `cfop_dentro_estado` VARCHAR(6) NOT NULL DEFAULT '',
                `cfop_fora_estado` VARCHAR(6) NOT NULL DEFAULT '',
                `tipo_imposto` ENUM('ICMS', 'ISSQN') NULL DEFAULT 'ICMS',
                `icms` INT(6) NULL DEFAULT NULL,
                `icms_modbc` CHAR(1) NULL DEFAULT NULL,
                `icms_predbc` DECIMAL(10,3) NULL DEFAULT NULL,
                `icms_pICMS` DECIMAL(10,4) NULL DEFAULT NULL,
                `icms_modbcst` CHAR(1) NULL DEFAULT NULL,
                `icms_pmvast` DECIMAL(10,3) NULL DEFAULT NULL,
                `icms_predbcst` DECIMAL(10,3) NULL DEFAULT NULL,
                `icms_picmsst` DECIMAL(10,4) NULL DEFAULT NULL,
                `icms_regimes` ENUM('T', 'S') NULL DEFAULT 'T',
                `icms_popepropria` DECIMAL(10,3) NULL DEFAULT NULL,
                `icms_uf` CHAR(2) NULL DEFAULT NULL,
                `icms_vl_aliq_calc_cre` DECIMAL(10,3) NULL DEFAULT NULL,
                `icms_bc_icms_st_ret_ant` DECIMAL(10,3) NULL DEFAULT NULL,
                `icms_valor_desoneracao` DECIMAL(10,2) NULL DEFAULT NULL,
                `icms_motivo_desoneracao` VARCHAR(60) NULL DEFAULT NULL,
                `icms_percentual_diferimento` DECIMAL(10,2) NULL DEFAULT NULL,
                `icms_st_uf_retido_remetente` CHAR(2) NULL DEFAULT NULL,
                `icms_st_uf_destino` CHAR(2) NULL DEFAULT NULL,
                `ipi` INT(2) UNSIGNED ZEROFILL NOT NULL DEFAULT '00',
                `ipi_cIEnq` CHAR(5) NULL DEFAULT NULL,
                `ipi_CNPJProd` CHAR(14) NULL DEFAULT NULL,
                `ipi_cSelo` VARCHAR(60) NULL DEFAULT NULL,
                `ipi_qSelo` DOUBLE NULL DEFAULT NULL,
                `ipi_cEnq` CHAR(3) NULL DEFAULT NULL,
                `ipi_qUnid` DOUBLE NULL DEFAULT NULL,
                `ipi_pIPI` DECIMAL(10,2) NULL DEFAULT NULL,
                `ipi_tp_calculo` ENUM('P', 'V') NULL DEFAULT 'P',
                `ipi_v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `pis` INT(2) UNSIGNED ZEROFILL NOT NULL DEFAULT '00',
                `pis_tp_calculo` CHAR(1) NULL DEFAULT 'N' COMMENT 'N - Nulo    P - Percentual  V-Valores',
                `pis_pPIS` DECIMAL(10,2) NULL DEFAULT NULL,
                `pis_v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `pisST_tp_calculo` CHAR(1) NULL DEFAULT 'N' COMMENT 'N-Nulo   P - Percentual   V - Valor',
                `pisST_pPIS` DECIMAL(10,2) NULL DEFAULT NULL,
                `pisST_v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `cofins` INT(2) UNSIGNED ZEROFILL NOT NULL DEFAULT '00',
                `cofins_tpcalculo` ENUM('P', 'V') NULL DEFAULT NULL,
                `cofins_aliq_perc` DECIMAL(10,2) NULL DEFAULT NULL,
                `cofins_aliq_vlr` DECIMAL(10,2) NULL DEFAULT NULL,
                `cofins_st_tpcalculo` ENUM('P', 'V') NULL DEFAULT NULL,
                `cofins_st_aliq_perc` DECIMAL(10,2) NULL DEFAULT NULL,
                `cofins_st_aliq_vlr` DECIMAL(10,2) NULL DEFAULT NULL,
                `regime_tributacao` CHAR(1) NOT NULL DEFAULT '',
                `issqn_regime_tributacao` CHAR(1) NULL DEFAULT NULL,
                `issqn_percentual_aliquota` DECIMAL(12,2) NULL DEFAULT NULL,
                `issqn_uf` CHAR(2) NULL DEFAULT NULL,
                `issqn_id_municipio` CHAR(7) NULL DEFAULT NULL,
                `issqn_id_lista_servicos` VARCHAR(4) NULL DEFAULT NULL,
                `issqn_id_exigibilidade` INT(11) NULL DEFAULT NULL,
                `issqn_incentivo_fiscal` ENUM('S', 'N') NULL DEFAULT 'S',
                `issqn_valor_deducoes` DECIMAL(10,2) NULL DEFAULT NULL,
                `issqn_valor_outras_retencoes` DECIMAL(10,2) NULL DEFAULT NULL,
                `issqn_valor_desconto_condicionado` DECIMAL(10,2) NULL DEFAULT NULL,
                `issqn_valor_retencao` DECIMAL(10,2) NULL DEFAULT NULL,
                `issqn_codigo_servico` VARCHAR(60) NULL DEFAULT NULL,
                `issqn_uf_incidencia` CHAR(2) NULL DEFAULT NULL,
                `issqn_id_municipio_incidencia` INT(11) NULL DEFAULT NULL,
                `issqn_processo` VARCHAR(60) NULL DEFAULT NULL,
                `issqn_situacao` ENUM('tp', 'tt', 'tf', 'is', 'nt', 'si', 'ca') NULL DEFAULT 'tp',
                `issqn_cmc` VARCHAR(20) NULL DEFAULT NULL,
                `issqn_cpf` VARCHAR(20) NULL DEFAULT NULL,
                `issqn_senha_cmc_cpf` VARCHAR(40) NULL DEFAULT NULL,
                `issqn_padrao` VARCHAR(55) NULL DEFAULT NULL,
                `issqn_assinar_nfs` ENUM('S', 'N') NULL DEFAULT NULL,
                `issqn_info_nota_fiscal` LONGTEXT NULL DEFAULT NULL,
                `cfop_dev_d` VARCHAR(6) NULL DEFAULT NULL,
                `cfop_dev_f` VARCHAR(6) NULL DEFAULT NULL,
                `cfop_dev_gar_d` VARCHAR(6) NULL DEFAULT NULL,
                `cfop_dev_gar_f` VARCHAR(6) NULL DEFAULT NULL,
                `cfop_dev_out_d` VARCHAR(6) NULL DEFAULT NULL,
                `cfop_dev_out_f` VARCHAR(6) NULL DEFAULT NULL,
                `cfop_ent_d` VARCHAR(6) NULL DEFAULT NULL,
                `cfop_ent_f` VARCHAR(6) NULL DEFAULT NULL,
                `nome_arquivo_certificado` VARCHAR(200) NOT NULL,
                `senha_certificado` VARCHAR(50) NOT NULL,
                `nfe_tipo_ambiente` ENUM('P', 'H') NULL DEFAULT 'P',
                `nfe_sequencia_nota` INT(11) NULL DEFAULT '1',
                `nfe_formato` ENUM('R', 'P') NULL DEFAULT 'P',
                `nfce_tipo_ambiente` ENUM('P', 'H') NULL DEFAULT 'P',
                `nfce_sequencia_nota` INT(11) NULL DEFAULT '1',
                `nfce_csc_token` VARCHAR(50) NULL DEFAULT NULL,
                `nfce_idtoken` VARCHAR(50) NULL DEFAULT NULL,
                `nfce_data_ativacao` DATE NULL DEFAULT NULL,
                `nfce_hora_ativacao` TIME NULL DEFAULT NULL,
                `id_usuario` INT(11) NOT NULL,
                `tributacao_lucro` ENUM('S', 'N') NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `solicitar_cod_cartao` TINYINT(4) NULL DEFAULT '1',
                `relatorio_mensal` INT(11) NULL DEFAULT '0',
                `issqn_codigo_obra` VARCHAR(8) NULL DEFAULT NULL,
                `issqn_serie_nf` VARCHAR(5) NULL DEFAULT NULL,
                `issqn_cod_ativ_mun` VARCHAR(5) NULL DEFAULT NULL,
                `issqn_anocertificado` INT(4) NULL DEFAULT '2016',
                `calcular_difal` INT(1) NULL DEFAULT '1' COMMENT '1-Sim, 0-Nao',
                `nfce_serie` INT(2) NULL DEFAULT '1',
                `nfe_serie` INT(2) NULL DEFAULT '1',
                `xPed` ENUM('S', 'N') NULL DEFAULT 'N',
                `codBenef` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Codigo Beneficiario, usado para cliente do Regime Normal',
                `cte_tipo_ambiente` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Producao 2 - Homologacao',
                `cte_sequencia_nota` INT(11) NOT NULL,
                `cte_formato` ENUM('P', 'H') NOT NULL DEFAULT 'P' COMMENT 'P - Producao H - Homologacao',
                `cte_serie` INT(2) NOT NULL,
                `mdfe_tipo_ambiente` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Producao 2 - Homologacao',
                `mdfe_sequencia_nota` INT(11) NOT NULL,
                `mdfe_formato` ENUM('P', 'H') NOT NULL DEFAULT 'P' COMMENT 'P - Producao H - Homologacao',
                `mdfe_serie` INT(2) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CADASTRO_IMPOSTO_PADRAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cadastro_imposto_padrao_hist()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cadastro_imposto_padrao_hist`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `origem_nfe` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `origem_nfe_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dentro_estado` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dentro_estado_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_fora_estado` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_fora_estado_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `tipo_imposto` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `tipo_imposto_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_modbc` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_modbc_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_predbc` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_predbc_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_pICMS` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_pICMS_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_modbcst` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_modbcst_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_pmvast` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_pmvast_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_predbcst` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_predbcst_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_picmsst` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_picmsst_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_regimes` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_regimes_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_popepropria` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_popepropria_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_uf` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_uf_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_vl_aliq_calc_cre` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_vl_aliq_calc_cre_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_bc_icms_st_ret_ant` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_bc_icms_st_ret_ant_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_valor_desoneracao` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_valor_desoneracao_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_motivo_desoneracao` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_motivo_desoneracao_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_percentual_diferimento` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_percentual_diferimento_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_st_uf_retido_remetente` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_st_uf_retido_remetente_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_st_uf_destino` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `icms_st_uf_destino_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_cIEnq` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_cIEnq_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_CNPJProd` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_CNPJProd_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_cSelo` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_cSelo_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_qSelo` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_qSelo_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_cEnq` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_cEnq_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_qUnid` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_qUnid_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_pIPI` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_pIPI_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_tp_calculo` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_tp_calculo_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_v_aliq` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `ipi_v_aliq_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pis` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pis_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pis_tp_calculo` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pis_tp_calculo_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pis_pPIS` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pis_pPIS_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pis_v_aliq` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pis_v_aliq_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pisST_tp_calculo` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pisST_tp_calculo_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pisST_pPIS` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pisST_pPIS_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pisST_v_aliq` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `pisST_v_aliq_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_tpcalculo` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_tpcalculo_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_aliq_perc` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_aliq_perc_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_aliq_vlr` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_aliq_vlr_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_st_tpcalculo` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_st_tpcalculo_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_st_aliq_perc` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_st_aliq_perc_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_st_aliq_vlr` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cofins_st_aliq_vlr_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `regime_tributacao` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `regime_tributacao_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_regime_tributacao` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_regime_tributacao_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_percentual_aliquota` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_percentual_aliquota_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_uf` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_uf_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_id_municipio` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_id_municipio_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_id_lista_servicos` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_id_lista_servicos_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_id_exigibilidade` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_id_exigibilidade_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_incentivo_fiscal` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_incentivo_fiscal_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_valor_deducoes` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_valor_deducoes_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_valor_outras_retencoes` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_valor_outras_retencoes_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_valor_desconto_condicionado` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_valor_desconto_condicionado_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_valor_retencao` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_valor_retencao_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_codigo_servico` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_codigo_servico_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_uf_incidencia` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_uf_incidencia_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_id_municipio_incidencia` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_id_municipio_incidencia_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_processo` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_processo_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_situacao` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_situacao_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_cmc` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_cmc_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_cpf` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_cpf_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_senha_cmc_cpf` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_senha_cmc_cpf_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_padrao` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_padrao_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_assinar_nfs` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `issqn_assinar_nfs_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_d` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_d_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_f` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_f_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_gar_d` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_gar_d_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_gar_f` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_gar_f_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_out_d` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_out_d_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_out_f` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_dev_out_f_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_ent_d` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_ent_d_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_ent_f` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `cfop_ent_f_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nome_arquivo_certificado` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nome_arquivo_certificado_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `senha_certificado` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `senha_certificado_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfe_tipo_ambiente` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfe_tipo_ambiente_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfe_sequencia_nota` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfe_sequencia_nota_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfe_formato` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfe_formato_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_tipo_ambiente` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_tipo_ambiente_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_sequencia_nota` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_sequencia_nota_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_csc_token` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_csc_token_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_idtoken` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_idtoken_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_data_ativacao` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_data_ativacao_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_hora_ativacao` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `nfce_hora_ativacao_old` VARCHAR(50) CHARACTER SET 'utf8' NULL DEFAULT '',
                `id_usuario` INT(11) NULL DEFAULT '0',
                `id_cadastro` INT(11) NULL DEFAULT '0',
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `solicitar_cod_cartao` INT(11) NULL DEFAULT NULL,
                `solicitar_cod_cartao_old` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CADASTRO_IMPOSTO_PADRAO_HIST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cargo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cargo`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `descricao` VARCHAR(150) NULL DEFAULT NULL,
                `ativo` ENUM('S', 'N') NULL DEFAULT NULL,
                `id_setor` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CARGO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_carne()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `carne`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `data_emissao` DATETIME NULL DEFAULT NULL,
                `num_contrato` VARCHAR(20) NOT NULL,
                `valor` DECIMAL(15,2) NOT NULL,
                `vencimento` DATE NOT NULL,
                `parcela` VARCHAR(7) NOT NULL,
                `multa_atraso` DECIMAL(4,2) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `observacoes` VARCHAR(100) NULL DEFAULT NULL,
                `id_usuario` INT(11) NOT NULL,
                `data_baixa` DATETIME NULL DEFAULT NULL,
                `valor_baixa` DECIMAL(15,2) NULL DEFAULT NULL,
                `id_usuario_baixa` INT(11) NULL DEFAULT NULL,
                `situacao` ENUM('P', 'I', 'B') NOT NULL DEFAULT 'P' COMMENT 'P - Pendente  I - Inativo  B - Baixado',
                `id_abertura_caixa` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `taxa_juros` DECIMAL(4,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`, `id_cadastro`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CARNE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_carrinho()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `carrinho`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `cod` INT(11) NOT NULL DEFAULT '0',
                `nome` VARCHAR(150) NULL DEFAULT NULL,
                `preco` DOUBLE(10,2) NOT NULL DEFAULT '0.00',
                `qtd` INT(11) NOT NULL DEFAULT '0',
                `sessao` TEXT NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CARRINHO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cartaofid_cartao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cartaofid_cartao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_config` INT(11) NULL DEFAULT NULL COMMENT 'Id da configuracao tabela config que gerou isso, necessario caso seja modificado o sistema para trabalhar com mais de um modelo por cliente',
                `num_cartao` VARCHAR(30) NULL DEFAULT NULL,
                `cpf_cliente` CHAR(50) NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CARTAOFID_CARTAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cartaofid_config()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cartaofid_config`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `tipo_cartao` CHAR(1) NULL DEFAULT NULL COMMENT 'P - Premiacao / D - Desconto na compra',
                `tipo_modelo` CHAR(1) NULL DEFAULT NULL COMMENT 'E - Existente / P - Próprio',
                `id_modelo` INT(11) NULL DEFAULT NULL,
                `tpd_reais_eq_ponto_compra` DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'X reais na compra equivalem a 1 ponto',
                `tpd_ponto_eq_reais_gasto` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '1 ponto na compra equivale a X reais',
                `min_pontos` INT(5) NULL DEFAULT NULL COMMENT 'minimo de pontos para uso como desconto',
                `validade_pontos` INT(5) NULL DEFAULT NULL COMMENT 'por quantos dias valem os pontos - validad em dias',
                `regulamento` VARCHAR(255) NULL DEFAULT NULL COMMENT 'regulamento que sera impresso no cartao',
                `informacao_frente` VARCHAR(255) NULL DEFAULT NULL,
                `nome_cartao` VARCHAR(255) NULL DEFAULT NULL,
                `cartoes_gerados` CHAR(5) NULL DEFAULT '0' COMMENT 'tota de cartoes gerados',
                `grafica` CHAR(1) NULL DEFAULT NULL COMMENT '0 - 1 enviado p grafica',
                `dt_grafica` TIMESTAMP NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CARTAOFID_CONFIG criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cartaofid_historico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cartaofid_historico`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `num_cartao` VARCHAR(30) NULL DEFAULT NULL,
                `pontos_ganhos_venda` DECIMAL(10,2) NULL DEFAULT '0.00' COMMENT 'pontos ganhos nesta venda',
                `pontos_gastos_venda` DECIMAL(10,2) NULL DEFAULT '0.00' COMMENT 'pontos gastos nesta venda',
                `valor_conversao_pr` DECIMAL(10,2) NULL DEFAULT '0.00' COMMENT 'valor base em reais para conversao dos pontos em reais na data de adicao ',
                `pontos_usados` DECIMAL(10,2) NULL DEFAULT '0.00' COMMENT 'pontos reduzidos de outras compras onde foram usados os pontos',
                `status_pontos_venda` CHAR(1) NULL DEFAULT NULL COMMENT 'A - Ativo , I - Inativo (caso a venda seja cancelada os pontos ganhos aqui não são mais válidos)',
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CARTAOFID_HISTORICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cartaofid_modelo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cartaofid_modelo`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT '0',
                `imagem_view` VARCHAR(255) NULL DEFAULT NULL,
                `imagem_front` VARCHAR(255) NULL DEFAULT NULL,
                `imagem_back` VARCHAR(255) NULL DEFAULT NULL,
                `tipo_modelo` CHAR(1) NULL DEFAULT NULL COMMENT 'P - Padrão / C - cliente',
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CARTAOFID_MODELO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cartaofid_pedido_grafica()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cartaofid_pedido_grafica`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_config` INT(11) NULL DEFAULT NULL,
                `qtde_solicitada` CHAR(10) NULL DEFAULT NULL,
                `range_start` CHAR(50) NULL DEFAULT NULL,
                `range_end` CHAR(50) NULL DEFAULT NULL,
                `dt_solicitado` TIMESTAMP NULL DEFAULT NULL,
                `resultado_cron` CHAR(5) NULL DEFAULT NULL,
                `dt_update_cron` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CARTAOFID_PEDIDO_GRAFICA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_catalogo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `catalogo`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `slug` VARCHAR(50) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `cod_loja` INT(11) NULL DEFAULT NULL,
                `mostrar_foto` INT(11) NULL DEFAULT NULL COMMENT '0-1',
                `mostrar_preco` INT(11) NULL DEFAULT NULL,
                `mostrar_desc` INT(11) NULL DEFAULT NULL,
                `mostrar_grade` INT(11) NOT NULL DEFAULT '0',
                `mostrar_estoque` INT(11) NOT NULL DEFAULT '0',
                `mostrar_loja_virtual` INT(11) NOT NULL DEFAULT '0',
                `pedido_online` INT(11) NOT NULL DEFAULT '0',
                `situacao` INT(11) NULL DEFAULT '0' COMMENT '0-inativo/1-ativo',
                `codigo_barras` INT(11) NULL DEFAULT NULL,
                `obs` LONGTEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CATALOGO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cest()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `CEST`
            (
                `CEST_ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `CEST_ITEM` VARCHAR(20) NULL DEFAULT NULL,
                `CEST_CEST` VARCHAR(15) NULL DEFAULT NULL,
                `CEST_NCM` VARCHAR(15) NULL DEFAULT NULL,
                `CEST_DESCRICAO` LONGTEXT NULL DEFAULT NULL,
                PRIMARY KEY (`CEST_ID`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CEST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cestt()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cest`
            (
                `numero_cest` VARCHAR(10) NOT NULL,
                `descricao_cest` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`numero_cest`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela cest criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cest2()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cest2`
            (
                `numero_cest` VARCHAR(10) NOT NULL,
                `ncm` VARCHAR(10) NULL DEFAULT NULL,
                `descricao_cest` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`numero_cest`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CEST2 criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cfop()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `cfop`
            (
                `codigo` INT(5) NOT NULL,
                `descricao` VARCHAR(200) NULL DEFAULT NULL,
                `status` ENUM('A', 'I') NULL DEFAULT 'A',
                PRIMARY KEY (`codigo`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CFOP criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cidade()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cidade`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(120) NULL DEFAULT NULL,
                `estado` INT(5) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CIDADE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_classificacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `classificacao`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(150) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A',
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_usuario` INT(11) UNSIGNED NULL DEFAULT NULL,
                `id_anterior` INT(11) NULL DEFAULT NULL,
                `ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `lixo` VARCHAR(10) NULL DEFAULT NULL,
                `id_class_master` VARCHAR(11) NULL DEFAULT NULL,
                `imagem` VARCHAR(255) NULL DEFAULT NULL,
                `show_comanda` TINYINT(4) NULL DEFAULT '0' COMMENT '\'0\',\'1\'',
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `id_pai` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_mercadolivre` VARCHAR(20) NULL DEFAULT NULL,
                `favoritoloja` TINYINT(1) NULL DEFAULT '0' COMMENT '0 = nao favorito e padrao do campo 1 = favorito e fixo na barra de classificacoes do layout 2 da loja virtual2',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLASSIFICACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_classificacao_alteracao_valores()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `classificacao_alteracao_valores`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_classificacao` INT(11) NOT NULL DEFAULT '0',
                `tipo_alteracao` ENUM('D', 'A') NULL DEFAULT NULL COMMENT 'D - DIMINUIR / A - AUMENTAR',
                `fator_alteracao` ENUM('P', 'R') NULL DEFAULT NULL COMMENT 'P - PERCENTUAL / R - REAIS',
                `valor_alteracao` DECIMAL(15,2) NULL DEFAULT NULL,
                `id_usuario_alteracao` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `desfeito` TINYINT(4) NULL DEFAULT '0',
                `id_fornecedor` INT(10) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLASSIFICACAO_ALTERACAO_VALORES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_classificacao_bancodeimagens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `classificacao_bancodeimagens`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `url_imagem` VARCHAR(255) NULL DEFAULT NULL,
                `descricao` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLASSIFICACAO_BANCODEIMAGENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_classificacao_contas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `classificacao_contas`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(70) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) UNSIGNED NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A',
                `tipo` ENUM('P', 'R') NULL DEFAULT 'P',
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLASSIFICACAO_CONTAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_classificacao_sub()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `classificacao_sub`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_classificacao` BIGINT(20) NOT NULL,
                `descricao` VARCHAR(30) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLASSIFICACAO_SUB criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_classificacoes_removidas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `classificacoes_removidas`
            (
                `id` INT(10) UNSIGNED NOT NULL,
                `descricao` VARCHAR(150) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A',
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_usuario` INT(11) UNSIGNED NULL DEFAULT NULL,
                `id_anterior` INT(11) NULL DEFAULT NULL,
                `ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `lixo` VARCHAR(10) NULL DEFAULT NULL,
                `id_class_master` VARCHAR(11) NULL DEFAULT NULL,
                `imagem` VARCHAR(255) NULL DEFAULT NULL,
                `show_comanda` TINYINT(4) NULL DEFAULT '0' COMMENT '\'0\',\'1\'',
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `id_pai` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLASSIFICACOES_REMOVIDAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cli_recebafacil()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cli_recebafacil`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLI_RECEBAFACIL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cliente()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cliente`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) NULL DEFAULT NULL,
                `tipo_pessoa` ENUM('F', 'J', 'B', 'E') CHARACTER SET 'latin1' NULL DEFAULT NULL COMMENT 'Fisica, Juridica, Babaca, Extrangeiro',
                `cnpj_cpf` VARCHAR(15) CHARACTER SET 'latin1' NULL DEFAULT '00000000000',
                `rg` VARCHAR(20) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `inscricao_municipal` VARCHAR(19) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `inscricao_estadual` VARCHAR(14) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `inscricao_suframa` VARCHAR(14) CHARACTER SET 'latin1' NULL DEFAULT NULL COMMENT 'usando o mesmo campo para gravar INSC. RURAL',
                `nome` VARCHAR(60) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `razao_social` VARCHAR(60) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `id_tipo_log` INT(10) UNSIGNED NULL DEFAULT NULL,
                `endereco` VARCHAR(50) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `numero` VARCHAR(10) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `complemento` VARCHAR(55) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `bairro` VARCHAR(50) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `cidade` VARCHAR(50) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `uf` CHAR(2) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `cep` VARCHAR(8) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `pais` VARCHAR(40) CHARACTER SET 'latin1' NULL DEFAULT 'BRASIL',
                `informacoes_adicionais` TEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `email` VARCHAR(50) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `telefone` VARCHAR(11) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `celular` VARCHAR(11) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `fax` VARCHAR(11) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I', 'E') CHARACTER SET 'latin1' NOT NULL DEFAULT 'A',
                `renda_media` DECIMAL(10,2) NULL DEFAULT NULL,
                `empresa_trabalha` VARCHAR(50) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `cargo` VARCHAR(100) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `fone_empresa` VARCHAR(11) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `endereco_empresa` VARCHAR(100) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `nome_referencia_comercial` VARCHAR(100) CHARACTER SET 'latin1' NULL DEFAULT NULL COMMENT 'Nome Referencia Comercial',
                `referencia_comercial` VARCHAR(11) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `nome_referencia` VARCHAR(100) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `referencia_pessoal` VARCHAR(11) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `data_nascimento` DATE NULL DEFAULT NULL,
                `nome_pai` VARCHAR(50) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `nome_mae` VARCHAR(50) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `numero_titulo` BIGINT(12) NULL DEFAULT NULL,
                `zona` VARCHAR(3) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `secao` VARCHAR(4) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `placa` LONGTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `fone_pai` VARCHAR(11) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `fone_mae` VARCHAR(11) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `socio1` VARCHAR(50) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `socio2` VARCHAR(50) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `fone_socio1` VARCHAR(11) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `fone_socio2` VARCHAR(11) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `senha_ecommerce` VARCHAR(10) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `isento_icms` ENUM('S', 'N') CHARACTER SET 'latin1' NULL DEFAULT 'S',
                `sincronizado` INT(11) NOT NULL DEFAULT '0' COMMENT '0-Nao 1-Sim',
                `obs` LONGTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `tabela_preco` INT(11) NULL DEFAULT '1',
                `estado_civil` INT(11) NULL DEFAULT NULL,
                `nome_conjuge` CHAR(60) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `tipo_residencia` CHAR(1) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `foto` VARCHAR(500) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `orgao_expedidor` VARCHAR(20) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `naturalidade` VARCHAR(255) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                `limite_credito` DECIMAL(10,2) UNSIGNED NULL DEFAULT '0.00',
                `limite_credito_cc` DECIMAL(15,3) NOT NULL DEFAULT '0.000',
                `tipo_compra` ENUM('A', 'V') CHARACTER SET 'latin1' NOT NULL DEFAULT 'V',
                `origem_cadastro` INT(11) NULL DEFAULT NULL,
                `data_cadastro_user` DATE NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `substituto_tributario` INT(1) NULL DEFAULT '0',
                `senha` VARCHAR(55) CHARACTER SET 'latin1' NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLIENTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cliente_agendamentos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cliente_agendamentos`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(255) NULL DEFAULT NULL,
                `email` VARCHAR(255) NULL DEFAULT NULL,
                `fone` VARCHAR(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLIENTE_AGENDAMENTOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cliente_documento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cliente_documento`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `documento` VARCHAR(255) NULL DEFAULT NULL,
                `data_criacao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLIENTE_DOCUMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cliente_documentos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cliente_documentos`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cliente` INT(11) NOT NULL,
                `url_documento` VARCHAR(500) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLIENTE_DOCUMENTOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cliente_forma_pagamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cliente_forma_pagamento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_forma_pagamento` INT(11) NULL DEFAULT NULL,
                `num_parcelas` INT(11) NULL DEFAULT '1',
                `juro_mes` DECIMAL(5,2) NULL DEFAULT NULL,
                `cod_convenio` VARCHAR(15) NULL DEFAULT NULL,
                `cnpj_adm` CHAR(15) NULL DEFAULT NULL,
                `chave_e_commerce` VARCHAR(70) NULL DEFAULT NULL,
                `ativo` TINYINT(4) UNSIGNED NULL DEFAULT '1',
                `loja_virtual` TINYINT(4) NULL DEFAULT '0',
                `entrada` TINYINT(4) NULL DEFAULT '0',
                `ordem_visual` INT(11) NULL DEFAULT NULL,
                `baixa_automatica` ENUM('S', 'N') NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `avista` DECIMAL(10,2) NULL DEFAULT NULL,
                `aprazo` DECIMAL(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLIENTE_FORMA_PAGAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cliente_optica()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cliente_optica`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cliente` INT(11) NOT NULL,
                `longe_od_esferico` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_cilindrico` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_eixo` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_adicao` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_dnp` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_altura` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_esferico` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_cilindrico` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_eixo` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_adicao` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_dnp` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_altura` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_esferico` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_cilindrico` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_eixo` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_adicao` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_dnp` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_altura` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_esferico` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_cilindrico` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_eixo` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_adicao` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_dnp` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_altura` VARCHAR(45) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLIENTE_OPTICA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cliente_veiculo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cliente_veiculo`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cliente` INT(11) NOT NULL,
                `placa` VARCHAR(255) NOT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `modelo` VARCHAR(30) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLIENTE_VEICULO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cliente_veiculos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cliente_veiculos`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cliente` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `id_cadastro` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `veiculo` VARCHAR(50) NOT NULL DEFAULT '0',
                `modelo` VARCHAR(50) NOT NULL DEFAULT '0',
                `ano` SMALLINT(4) NOT NULL DEFAULT '0',
                `placa` VARCHAR(50) NOT NULL DEFAULT '0',
                `km_atual` VARCHAR(50) NOT NULL DEFAULT '0',
                `cor` VARCHAR(50) NOT NULL DEFAULT '0',
                `chassi` VARCHAR(50) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLIENTE_VEICULOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_clientes_removidos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `clientes_removidos`
            (
                `id` INT(10) UNSIGNED NOT NULL,
                `id_cadastro` INT(10) NULL DEFAULT NULL,
                `tipo_pessoa` ENUM('F', 'J', 'B', 'E') NULL DEFAULT NULL,
                `cnpj_cpf` VARCHAR(15) NULL DEFAULT '00000000000',
                `rg` VARCHAR(20) NULL DEFAULT NULL,
                `inscricao_municipal` VARCHAR(19) NULL DEFAULT NULL,
                `inscricao_estadual` VARCHAR(14) NULL DEFAULT NULL,
                `inscricao_suframa` INT(11) NULL DEFAULT NULL,
                `nome` VARCHAR(60) NULL DEFAULT NULL,
                `razao_social` VARCHAR(60) NULL DEFAULT NULL,
                `id_tipo_log` INT(10) UNSIGNED NULL DEFAULT NULL,
                `endereco` VARCHAR(50) NULL DEFAULT NULL,
                `numero` VARCHAR(10) NULL DEFAULT NULL,
                `complemento` VARCHAR(55) NULL DEFAULT NULL,
                `bairro` VARCHAR(50) NULL DEFAULT NULL,
                `cidade` VARCHAR(50) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `cep` VARCHAR(8) NULL DEFAULT NULL,
                `pais` VARCHAR(6) NULL DEFAULT 'BRASIL',
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `email` VARCHAR(50) NULL DEFAULT NULL,
                `telefone` VARCHAR(11) NULL DEFAULT NULL,
                `celular` VARCHAR(11) NULL DEFAULT NULL,
                `fax` VARCHAR(11) NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I', 'E') NOT NULL DEFAULT 'A',
                `renda_media` DECIMAL(10,2) NULL DEFAULT NULL,
                `empresa_trabalha` VARCHAR(50) NULL DEFAULT NULL,
                `cargo` VARCHAR(100) NULL DEFAULT NULL,
                `fone_empresa` VARCHAR(11) NULL DEFAULT NULL,
                `endereco_empresa` VARCHAR(100) NULL DEFAULT NULL,
                `nome_referencia_comercial` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Nome Referencia Comercial',
                `referencia_comercial` VARCHAR(11) NULL DEFAULT NULL,
                `nome_referencia` VARCHAR(100) NULL DEFAULT NULL,
                `referencia_pessoal` VARCHAR(11) NULL DEFAULT NULL,
                `data_nascimento` DATE NULL DEFAULT NULL,
                `nome_pai` VARCHAR(50) NULL DEFAULT NULL,
                `nome_mae` VARCHAR(50) NULL DEFAULT NULL,
                `numero_titulo` BIGINT(12) NULL DEFAULT NULL,
                `zona` VARCHAR(3) NULL DEFAULT NULL,
                `secao` VARCHAR(4) NULL DEFAULT NULL,
                `placa` LONGTEXT NULL DEFAULT NULL,
                `fone_pai` VARCHAR(11) NULL DEFAULT NULL,
                `fone_mae` VARCHAR(11) NULL DEFAULT NULL,
                `socio1` VARCHAR(50) NULL DEFAULT NULL,
                `socio2` VARCHAR(50) NULL DEFAULT NULL,
                `fone_socio1` VARCHAR(11) NULL DEFAULT NULL,
                `fone_socio2` VARCHAR(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `senha_ecommerce` VARCHAR(10) NULL DEFAULT NULL,
                `isento_icms` ENUM('S', 'N') NULL DEFAULT 'S',
                `sincronizado` INT(11) NOT NULL DEFAULT '0' COMMENT '0-Nao 1-Sim',
                `obs` LONGTEXT NULL DEFAULT NULL,
                `tabela_preco` INT(11) NULL DEFAULT '1',
                `estado_civil` INT(11) NULL DEFAULT NULL,
                `nome_conjuge` CHAR(60) NULL DEFAULT NULL,
                `tipo_residencia` CHAR(1) NULL DEFAULT NULL,
                `foto` VARCHAR(500) NULL DEFAULT NULL,
                `orgao_expedidor` VARCHAR(20) NULL DEFAULT NULL,
                `naturalidade` VARCHAR(255) NULL DEFAULT NULL,
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                `limite_credito` DECIMAL(10,2) UNSIGNED NULL DEFAULT '0.00',
                `limite_credito_cc` DECIMAL(15,3) NOT NULL DEFAULT '0.000',
                `tipo_compra` ENUM('A', 'V') NOT NULL DEFAULT 'V',
                `origem_cadastro` INT(11) NULL DEFAULT NULL,
                `data_cadastro_user` DATE NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `substituto_tributario` INT(1) NULL DEFAULT '0')
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CLIENTES_REMOVIDOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cm_comanda()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cm_comanda`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `num_comanda` CHAR(10) NULL DEFAULT NULL,
                `status` INT(1) NULL DEFAULT NULL COMMENT '0 - Vazia, 1 - Ocupada, 2 - Desativada',
                `id_off` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `qtde_pessoas` INT(3) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CM_COMANDA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cm_historico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cm_historico`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `id_mesa` INT(11) NULL DEFAULT '0' COMMENT 'id da mesa quando for mesa, no caso de comanda fica 0',
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `num_cm` CHAR(20) NULL DEFAULT NULL COMMENT 'número da comanda ou mesa',
                `tipo_cm` CHAR(1) NULL DEFAULT NULL COMMENT 'C - Comanda, M - Mesa',
                `status` INT(10) NULL DEFAULT NULL COMMENT '0 - Fechado, 1 - Aberto',
                `datahora_abertura` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `last_id_impresso` CHAR(10) NULL DEFAULT '0' COMMENT 'dado do ultimo id impresso para cozinha',
                `id_reserva` INT(11) NULL DEFAULT NULL COMMENT 'caso seja um pedido aberto a partir de uma reserva reserva o id estara aqui',
                `num_pessoas` INT(3) NULL DEFAULT '1' COMMENT 'numero de pessoas na comanda ou mesa',
                `id_off` BIGINT(20) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CM_HISTORICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cm_mesa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cm_mesa`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `num_mesa` CHAR(5) NULL DEFAULT NULL,
                `status` INT(1) NULL DEFAULT NULL COMMENT '0 - Vazia, 1 - Ocupada, 2 - Desativada, 3 - reservada',
                `qtde_pessoas` CHAR(5) NULL DEFAULT NULL,
                `id_off` BIGINT(20) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CM_MESA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cm_producao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cm_producao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NOT NULL DEFAULT '0',
                `idvenda_item` INT(11) NOT NULL DEFAULT '0',
                `enviado_producao` ENUM('S', 'N') NOT NULL DEFAULT 'N',
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `id_off` BIGINT(20) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CM_PRODUCAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cm_reserva()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cm_reserva`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `nome_pessoa` VARCHAR(50) NULL DEFAULT NULL,
                `cpf_pessoa` CHAR(15) NULL DEFAULT NULL,
                `telefone_pessoa` CHAR(15) NULL DEFAULT NULL,
                `qtde_pessoas` CHAR(5) NULL DEFAULT NULL,
                `data_reserva` DATE NULL DEFAULT NULL,
                `hora_reserva` TIME NULL DEFAULT NULL,
                `situacao` CHAR(1) NOT NULL DEFAULT '1' COMMENT '0 - Efetivada, 1 - aberta, 2 - cancelada',
                `id_off` BIGINT(20) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincromismo` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CM_RESERVA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cm_reserva_mesa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cm_reserva_mesa`
            (
                `id_reserva` INT(11) NULL DEFAULT NULL,
                `id_mesa` INT(11) NULL DEFAULT NULL,
                `id_off` VARCHAR(20) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CM_RESERVA_MESA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cnae()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cnae`
            (
                `codigo` CHAR(15) NULL DEFAULT NULL,
                `descricao` TEXT NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CNAE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cnae_issqn()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cnae_issqn`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `codigo` CHAR(7) NULL DEFAULT NULL,
                `descricao` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CNAE_ISSQN criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_compartilhamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `compartilhamento`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_compartilhamento` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela COMPARTILHAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_compromisso()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `compromisso`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_cadastro` INT(10) NULL DEFAULT NULL,
                `titulo` VARCHAR(50) NULL DEFAULT NULL,
                `hora_compromisso` TIME NULL DEFAULT NULL,
                `data_compromisso` DATE NULL DEFAULT NULL,
                `descricao_compromisso` TEXT NULL DEFAULT NULL,
                `hora_baixa_compromisso` TIME NULL DEFAULT NULL,
                `data_baixa_compromisso` DATE NULL DEFAULT NULL,
                `descricao_baixa_compromisso` TEXT NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A',
                `data_cadastro` DATE NULL DEFAULT NULL,
                `hora_cadastro` TIME NULL DEFAULT NULL,
                `contato` VARCHAR(50) NULL DEFAULT NULL,
                `local` VARCHAR(50) NULL DEFAULT NULL,
                `telefone` VARCHAR(10) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela COMPROMISSO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_conferencia_estoque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `conferencia_estoque`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONFERENCIA_ESTOQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_conferencia_estoque_itens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `conferencia_estoque_itens`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_conferencia` INT(11) NULL DEFAULT NULL,
                `id_grade` INT(11) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(60) NULL DEFAULT NULL,
                `qtd_estoque` INT(11) NULL DEFAULT NULL,
                `qtd_conferencia` INT(11) NULL DEFAULT NULL,
                `qtd_diferenca` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONFERENCIA_ESTOQUE_ITENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_conferencia_estoque_itens_temp()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `conferencia_estoque_itens_temp`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_conferencia` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_grade` INT(11) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(60) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONFERENCIA_ESTOQUE_ITENS_TEMP criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_conta_corrente()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `conta_corrente`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_cliente` INT(11) NOT NULL,
                `data_abertura` DATETIME NOT NULL,
                `data_alteracao` DATETIME NOT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `data_fechamento` DATETIME NOT NULL,
                `saldo` DECIMAL(15,3) NOT NULL,
                `ativo` ENUM('A', 'E', 'I') NOT NULL DEFAULT 'A',
                `id_usuario_abertura` INT(11) NOT NULL,
                `id_usuario_alteracao` INT(11) NULL DEFAULT NULL,
                `id_usuario_fechamento` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONTA_CORRENTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_conta_corrente_movimentacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `conta_corrente_movimentacao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_conta_corrente` INT(11) NOT NULL,
                `id_cadastro` INT(11) NOT NULL,
                `tipo_movimentacao` ENUM('D', 'C') NOT NULL,
                `data_movimentacao` DATETIME NOT NULL,
                `id_usuario_movimentacao` INT(11) NOT NULL,
                `valor_movimentacao` DECIMAL(15,3) NOT NULL,
                `descricao` VARCHAR(255) NOT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONTA_CORRENTE_MOVIMENTACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_contador_cliente()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `contador_cliente`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_contador` INT(11) NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONTADOR_CLIENTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_contas_comprovante()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `contas_comprovante`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_conta` INT(11) NULL DEFAULT NULL,
                `comprovante_hash` TEXT NULL DEFAULT NULL COMMENT 'nome do arquivo enviado com hash',
                `nome_arquivo` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONTAS_COMPROVANTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_contas_empresa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `contas_empresa`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_banco` CHAR(3) NULL DEFAULT NULL,
                `agencia` INT(11) NULL DEFAULT NULL,
                `conta` INT(11) NULL DEFAULT NULL,
                `saldo_inicial` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
                `limite` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
                `status_conta` CHAR(1) NULL DEFAULT 'A' COMMENT 'A - Ativa, I - Inativa',
                `dt_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `id_usuario` BIGINT(20) NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONTAS_EMPRESA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_contas_pagar()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `contas_pagar`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario_cadastro` INT(11) NULL DEFAULT NULL,
                `id_descricao_conta_pagar` INT(11) NULL DEFAULT NULL,
                `data_vencimento` DATE NULL DEFAULT NULL,
                `valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `situacao` ENUM('A', 'P', 'C') NULL DEFAULT 'A' COMMENT 'Ativa, Paga, Cancelada',
                `valor_baixa` DECIMAL(10,2) NULL DEFAULT NULL,
                `data_baixa` DATETIME NULL DEFAULT NULL,
                `id_usuario_baixa` INT(11) NULL DEFAULT NULL,
                `informacaoes_adicionais_baixa` TEXT NULL DEFAULT NULL,
                `data_cadastro_baixa` DATETIME NULL DEFAULT NULL,
                `extornada` ENUM('S', 'N') NULL DEFAULT 'N',
                `tp_conta` ENUM('P', 'R') NULL DEFAULT 'P' COMMENT 'P - Pagar   R - Receber',
                `origem` ENUM('G', 'O') NULL DEFAULT 'G' COMMENT 'Geral, Ordem Serviço',
                `id_os_orcamento` BIGINT(10) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(50) NULL DEFAULT NULL,
                `numero_documento` VARCHAR(15) NULL DEFAULT NULL,
                `id_formapgto` INT(11) NULL DEFAULT NULL,
                `id_classificacao` INT(11) NULL DEFAULT NULL,
                `id_fornecedor` INT(11) NULL DEFAULT NULL,
                `qtd_parcela` INT(11) NULL DEFAULT NULL,
                `chave` VARCHAR(10) NULL DEFAULT NULL,
                `parcela` VARCHAR(7) NULL DEFAULT '01/01',
                `id_tipo_documento` INT(11) NULL DEFAULT NULL,
                `nome_devedor` VARCHAR(255) NULL DEFAULT NULL,
                `cod_banco` VARCHAR(3) NULL DEFAULT NULL,
                `id_contas_pagar_pai` INT(11) NULL DEFAULT NULL COMMENT 'Usado qnd o pagamento for parcial, assim grava o id da conta anterior',
                `multa_atraso` DECIMAL(4,2) NULL DEFAULT NULL,
                `baixa_automatica` ENUM('S', 'N') NULL DEFAULT 'N',
                `id_abertura_caixa` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `juros_parcelamento` DECIMAL(12,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONTAS_PAGAR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_contas_pagar_bkp()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `contas_pagar_bkp`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario_cadastro` INT(11) NULL DEFAULT NULL,
                `id_descricao_conta_pagar` INT(11) NULL DEFAULT NULL,
                `data_vencimento` DATE NULL DEFAULT NULL,
                `valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `situacao` ENUM('A', 'P', 'C') NULL DEFAULT 'A' COMMENT 'Ativa, Paga, Cancelada',
                `valor_baixa` DECIMAL(10,2) NULL DEFAULT NULL,
                `data_baixa` DATETIME NULL DEFAULT NULL,
                `id_usuario_baixa` INT(11) NULL DEFAULT NULL,
                `informacaoes_adicionais_baixa` TEXT NULL DEFAULT NULL,
                `data_cadastro_baixa` DATETIME NULL DEFAULT NULL,
                `extornada` ENUM('S', 'N') NULL DEFAULT 'N',
                `tp_conta` ENUM('P', 'R') NULL DEFAULT 'P' COMMENT 'P - Pagar   R - Receber',
                `origem` ENUM('G', 'O') NULL DEFAULT 'G' COMMENT 'Geral, Ordem Serviço',
                `id_os_orcamento` BIGINT(10) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(50) NULL DEFAULT NULL,
                `numero_documento` VARCHAR(15) NULL DEFAULT NULL,
                `id_formapgto` INT(11) NULL DEFAULT NULL,
                `id_classificacao` INT(11) NULL DEFAULT NULL,
                `id_fornecedor` INT(11) NULL DEFAULT NULL,
                `qtd_parcela` INT(11) NULL DEFAULT NULL,
                `chave` VARCHAR(10) NULL DEFAULT NULL,
                `parcela` VARCHAR(7) NULL DEFAULT '01/01',
                `id_tipo_documento` INT(11) NULL DEFAULT NULL,
                `nome_devedor` VARCHAR(255) NULL DEFAULT NULL,
                `cod_banco` VARCHAR(3) NULL DEFAULT NULL,
                `id_contas_pagar_pai` INT(11) NULL DEFAULT NULL COMMENT 'Usado qnd o pagamento for parcial, assim grava o id da conta anterior',
                `multa_atraso` DECIMAL(4,2) NULL DEFAULT NULL,
                `baixa_automatica` ENUM('S', 'N') NULL DEFAULT 'N',
                `id_abertura_caixa` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `juros_parcelamento` DECIMAL(12,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONTAS_PAGAR_BKP criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_contas_pagar_tpdoc()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `contas_pagar_tpdoc`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(100) NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONTAS_PAGAR_TPDOC criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_controle_notafiscal()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `controle_notafiscal`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `sequencia` INT(10) UNSIGNED NULL DEFAULT '0',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CONTROLE_NOTAFISCAL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_credenciadora_cartao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `credenciadora_cartao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_forma_pagamento` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `nome` VARCHAR(255) NULL DEFAULT NULL,
                `cnpj` VARCHAR(15) NOT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CREDENCIADORA_CARTAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_credenciadoras_fixas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `credenciadoras_fixas`
            (
                `id_credenciadora` INT(11) NOT NULL AUTO_INCREMENT,
                `cnpj` VARCHAR(15) NULL DEFAULT NULL,
                `nome` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id_credenciadora`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CREDENCIADORAS_FIXAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_credenciadoras_fixas_ignorar()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `credenciadoras_fixas_ignorar`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_credenciadora_fixa` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CREDENCIADORAS_FIXAS_IGNORAR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_cst()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `cst`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `codigo` INT(2) UNSIGNED ZEROFILL NULL DEFAULT NULL,
                `referencia` VARCHAR(10) NOT NULL,
                `descricao` VARCHAR(150) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela CST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_dados_avaliacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `dados_avaliacao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT '0',
                `id_usuario` INT(11) NULL DEFAULT '0',
                `regiao_usuario` CHAR(2) NULL DEFAULT NULL COMMENT 'S - Sul, SE - Sudeste, SO - Sudoeste, C - Centro, N - Norte, NE - Nordeste, NO - Noroeste, O - Oeste, L - Leste',
                `estado_usuario` CHAR(2) NULL DEFAULT NULL,
                `nome_usuario` CHAR(60) NULL DEFAULT NULL,
                `telefone_usuario` CHAR(12) NULL DEFAULT NULL,
                `tipo_avaliacao` CHAR(3) NULL DEFAULT NULL COMMENT 'ASS - Avaliacao de Satisfacao do Sistema',
                `avaliacao` CHAR(5) NULL DEFAULT NULL,
                `data_avaliacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `obs_avaliacao` VARCHAR(2048) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela DADOS_AVALIACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_descricao_contas_pagar()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `descricao_contas_pagar`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(100) NULL DEFAULT NULL,
                `data_cadastro` DATE NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A',
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela DESCRICAO_CONTAS_PAGAR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_descricao_contas_pagar_padrao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `descricao_contas_pagar_padrao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela DESCRICAO_CONTAS_PAGAR_PADRAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_documentos_arquivado()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `documentos_arquivado`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_documentos_pasta` INT(11) NULL DEFAULT NULL,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `arquivo` VARCHAR(100) NULL DEFAULT NULL,
                `data_hora_criacao` DATETIME NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `extensao` VARCHAR(4) NULL DEFAULT NULL,
                `situacao` ENUM('S', 'N', 'C') NULL DEFAULT 'N' COMMENT 'Campo documento contador, S -> Ativo, N-> Inativo, C -> Cancelado',
                `data_vencimento` DATETIME NULL DEFAULT NULL COMMENT 'Data de Vencimento',
                `data_baixa` DATETIME NULL DEFAULT NULL COMMENT 'Data da baixa do documento',
                `id_pai` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'referencia do documento pai',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela DOCUMENTOS_ARQUIVADO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_documentos_pasta()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `documentos_pasta`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_pai` INT(11) NULL DEFAULT NULL,
                `id_dono_pasta` INT(11) NULL DEFAULT NULL,
                `tipo_dono_pasta` CHAR(1) NOT NULL DEFAULT 'M' COMMENT 'C - Cliente, F - Funcionario, T - Transportadora, O - Fornecedor, U - Usuario, M - Master, A - Acessou',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela DOCUMENTOS_PASTA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_encaminhamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `encaminhamento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT '0',
                `id_venda` INT(10) UNSIGNED NULL DEFAULT '0',
                `id_funcionario` INT(10) UNSIGNED NULL DEFAULT '0',
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ENCAMINHAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_encaminhamento_endereco()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `encaminhamento_endereco`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_encaminhamento` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_funcionario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `bairro` VARCHAR(60) NULL DEFAULT NULL,
                `cep` VARCHAR(9) NULL DEFAULT NULL,
                `cidade` VARCHAR(50) NULL DEFAULT NULL,
                `complemento` VARCHAR(225) NULL DEFAULT NULL,
                `estado` VARCHAR(2) NULL DEFAULT NULL,
                `logradouro` INT(2) NULL DEFAULT NULL,
                `numero` VARCHAR(6) NULL DEFAULT NULL,
                `endereco` VARCHAR(50) NULL DEFAULT NULL,
                `pais` VARCHAR(50) NULL DEFAULT NULL,
                `data_criacao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ENCAMINHAMENTO_ENDERECO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_encaminhamento_produtos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `encaminhamento_produtos`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_encaminhamento` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_endereco` INT(10) UNSIGNED NULL DEFAULT NULL,
                `codigo_barra` CHAR(20) NULL DEFAULT NULL,
                `quantidade` FLOAT NULL DEFAULT NULL,
                `tipo_encaminhamento` INT(11) NULL DEFAULT NULL,
                `prazo_entrega` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ENCAMINHAMENTO_PRODUTOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_encaminhamento_tipo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `encaminhamento_tipo`
            (
                `id` INT(10) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(60) NOT NULL,
                `tipo_dados` VARCHAR(60) NOT NULL COMMENT 'C - Cliente, E - Endereco, P - Produto, V - Valores',
                `ordenacao` INT(10) UNSIGNED NOT NULL COMMENT 'Ordenacao de apresentacao',
                `ativo` CHAR(1) NULL DEFAULT 'A' COMMENT 'A - Ativo, I - Inativo',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ENCAMINHAMENTO_TIPO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_envio_sms_boleto()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `envio_sms_boleto`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_titulo_recebafacil` INT(11) NULL DEFAULT NULL,
                `data_envio` DATE NULL DEFAULT NULL,
                `celular` VARCHAR(15) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ENVIO_SMS_BOLETO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_estado()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `estado`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `sigla` CHAR(2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ESTADO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_estado_civil()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `estado_civil`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(15) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ESTADO_CIVIL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_estados()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `estados`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `sigla` CHAR(2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ESTADOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_estoque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `estoque`
            (
                `código` TEXT NULL DEFAULT NULL,
                `nome` TEXT NULL DEFAULT NULL,
                `estoque` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ESTOQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_estoque_apoio()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `Estoque_apoio`
            (
                `Nr. loja` INT(11) NULL DEFAULT NULL,
                `Fabric./Fornec.` TEXT NULL DEFAULT NULL,
                `Grupo` TEXT NULL DEFAULT NULL,
                `Cod. exibição` INT(11) NULL DEFAULT NULL,
                `Referência` FLOAT NULL DEFAULT NULL,
                `cod_barras` TEXT NULL DEFAULT NULL,
                `Descrição` TEXT NULL DEFAULT NULL,
                `Loja` TEXT NULL DEFAULT NULL,
                `Vlr. total compra` TEXT NULL DEFAULT NULL,
                `Vlr. compra` TEXT NULL DEFAULT NULL,
                `Vlr. total venda` TEXT NULL DEFAULT NULL,
                `Vlr. venda` TEXT NULL DEFAULT NULL,
                `Quantidade` TEXT NULL DEFAULT NULL,
                `Qtde. total estoque` TEXT NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ESTOQUE_APOIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_estoque_apoio_()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `estoque_apoio`
            (
                `id_grade` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `id_grade_atributo_valor` INT(11) NULL DEFAULT NULL,
                `id_usuario_alterou` INT(11) NULL DEFAULT NULL,
                `codigo_barra_pai` TEXT NULL DEFAULT NULL,
                `codigo_barra` TEXT NULL DEFAULT NULL,
                `codigo_interno` TEXT NULL DEFAULT NULL,
                `qtd_atual` FLOAT NULL DEFAULT NULL,
                `qtd_minima` FLOAT NULL DEFAULT NULL,
                `qtd_locacao` FLOAT NULL DEFAULT NULL,
                `qtd_locacao_locado` FLOAT NULL DEFAULT NULL,
                `valor_custo` FLOAT NULL DEFAULT NULL,
                `valor_varejo_avista` INT(11) NULL DEFAULT NULL,
                `valor_varejo_aprazo` FLOAT NULL DEFAULT NULL,
                `valor_atacado_avista` INT(11) NULL DEFAULT NULL,
                `valor_atacado_aprazo` FLOAT NULL DEFAULT NULL,
                `porc_varejo_avista` INT(11) NULL DEFAULT NULL,
                `porc_varejo_aprazo` FLOAT NULL DEFAULT NULL,
                `porc_atacado_avista` INT(11) NULL DEFAULT NULL,
                `porc_atacado_aprazo` INT(11) NULL DEFAULT NULL,
                `ativo` INT(11) NULL DEFAULT NULL,
                `data_alteracao` INT(11) NULL DEFAULT NULL,
                `data_sincronismo` INT(11) NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ESTOQUE_APOIO_ criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_estoque_produto()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `estoque_produto`
            (
                `ID` INT(11) NULL DEFAULT NULL,
                `EMP` INT(11) NULL DEFAULT NULL,
                `ID_ESTOQUE` INT(11) NULL DEFAULT NULL,
                `ID_PRODUTO` INT(11) NULL DEFAULT NULL,
                `NOME` TEXT NULL DEFAULT NULL,
                `QUANT_DISPO` TEXT NULL DEFAULT NULL,
                `QUANT_REQUE` TEXT NULL DEFAULT NULL,
                `QUANT_TOTAL` TEXT NULL DEFAULT NULL,
                `LOCAL` INT(11) NULL DEFAULT NULL,
                `SEL_INA` INT(11) NULL DEFAULT NULL,
                `SEL_LOTE` INT(11) NULL DEFAULT NULL,
                `DELL` INT(11) NULL DEFAULT NULL,
                `CRIADO` INT(11) NULL DEFAULT NULL,
                `ATUALIZADO` INT(11) NULL DEFAULT NULL,
                `DATA_POST` TEXT NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ESTOQUE_PRODUTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_exclusao_info()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `exclusao_info`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `info` VARCHAR(55) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela EXCLUSAO_INFO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_exclusao_info_relacionados()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `exclusao_info_relacionados`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_info` INT(11) NULL DEFAULT NULL,
                `desc` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela EXCLUSAO_INFO_RELACIONADOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_fila_tarefas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `fila_tarefas`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `descricao` VARCHAR(100) NULL DEFAULT NULL,
                `url` VARCHAR(200) NOT NULL,
                `parametros` TEXT NULL DEFAULT NULL,
                `metodo` ENUM('GET', 'POST') NOT NULL,
                `status` ENUM('A', 'C', 'E') NOT NULL DEFAULT 'A',
                `data_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `data_concluido` TIMESTAMP NULL DEFAULT NULL,
                `requisicoes` INT(11) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FILA_TAREFAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_financeiro_apoio()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `financeiro_apoio`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cadastro` BIGINT(20) NOT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `tp_pagamento` ENUM('FPC', 'CC', 'FF') NULL DEFAULT 'FPC',
                `descricao` VARCHAR(20) NOT NULL,
                `cpfcnpj` VARCHAR(14) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FINANCEIRO_APOIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_financeiro_funcionario_banco()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `financeiro_funcionario_banco`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario_cadastro` INT(11) NULL DEFAULT NULL,
                `id_banco` INT(11) NULL DEFAULT NULL,
                `agencia` VARCHAR(50) NULL DEFAULT NULL,
                `conta_corrente` VARCHAR(15) NULL DEFAULT NULL,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `data` DATE NULL DEFAULT NULL,
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                `titular` VARCHAR(75) NULL DEFAULT NULL,
                `cpf_titular` VARCHAR(11) NULL DEFAULT NULL,
                `id_financeiro_funcionario_valor` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FINANCEIRO_FUNCIONARIO_BANCO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_financeiro_funcionario_valor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `financeiro_funcionario_valor`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_funcionario` VARCHAR(255) NULL DEFAULT NULL,
                `data_cadastro` DATE NULL DEFAULT NULL,
                `salario` DECIMAL(10,2) NULL DEFAULT NULL,
                `comissao` DECIMAL(10,2) NULL DEFAULT NULL,
                `id_usuario_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FINANCEIRO_FUNCIONARIO_VALOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_fluxo_caixa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `fluxo_caixa`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `mes` TINYINT(3) NOT NULL DEFAULT '0',
                `ano` SMALLINT(6) NOT NULL DEFAULT '0',
                `valor_inicial` DECIMAL(10,3) NOT NULL DEFAULT '0.000',
                `debito` DECIMAL(10,3) NOT NULL DEFAULT '0.000',
                `credito` DECIMAL(10,3) NOT NULL DEFAULT '0.000',
                `valor_final` DECIMAL(10,3) NOT NULL DEFAULT '0.000',
                `conta` CHAR(20) NULL DEFAULT '',
                `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FLUXO_CAIXA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_forma_pagamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `forma_pagamento`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(30) NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A',
                `nome_reduzido` VARCHAR(10) NULL DEFAULT NULL,
                `sigla_bandeira` CHAR(1) NULL DEFAULT NULL,
                `logo` VARCHAR(50) NULL DEFAULT NULL,
                `nome_reduzido2` VARCHAR(15) NULL DEFAULT NULL,
                `ordem_visual` INT(11) NULL DEFAULT NULL,
                `cod_receita` INT(2) UNSIGNED ZEROFILL NULL DEFAULT '00',
                `baixa_automatica` ENUM('S', 'N') NULL DEFAULT 'N',
                `tipo_cobranca` ENUM('C', 'D', 'O') NULL DEFAULT 'O' COMMENT 'C - Credito / D - Debito / O - Outros',
                `sinc_pdv` TINYINT(4) NULL DEFAULT '1',
                `contas_pagar` ENUM('S', 'N') NULL DEFAULT 'S' COMMENT 'vai para contas a pagar ???',
                `parcelas` INT(11) NULL DEFAULT '12',
                `fluxo_contabil` ENUM('S', 'N') NULL DEFAULT 'S',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORMA_PAGAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_forma_pagamento_bandeira()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `forma_pagamento_bandeira`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(50) NULL DEFAULT NULL,
                `ativo` ENUM('S', 'N') NULL DEFAULT 'S',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORMA_PAGAMENTO_BANDEIRA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_forma_pagamento_cliente()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `forma_pagamento_cliente`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_formapgto` INT(11) NOT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'I',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORMA_PAGAMENTO_CLIENTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_forma_pagamento_ecommerce()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `forma_pagamento_ecommerce`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_bandeira` VARCHAR(10) NULL DEFAULT NULL,
                `nr_parcelas` VARCHAR(2) NULL DEFAULT NULL,
                `juro_mes` DECIMAL(10,2) NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `ativo` ENUM('S', 'N') NULL DEFAULT 'N',
                `tp_pgto` INT(1) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `cod_convenio` VARCHAR(15) NULL DEFAULT NULL,
                `chave_e_commerce` VARCHAR(70) NULL DEFAULT NULL,
                `cnpj_adm` CHAR(15) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORMA_PAGAMENTO_ECOMMERCE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_fornecedor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `fornecedor`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `razao_social` VARCHAR(100) NULL DEFAULT NULL,
                `fantasia` VARCHAR(100) NULL DEFAULT NULL,
                `contato` VARCHAR(50) NULL DEFAULT NULL,
                `cnpj_cpf` VARCHAR(15) NULL DEFAULT NULL,
                `telefone` VARCHAR(11) NULL DEFAULT NULL,
                `fax` VARCHAR(11) NULL DEFAULT NULL,
                `celular` VARCHAR(11) NULL DEFAULT NULL,
                `email` VARCHAR(50) NULL DEFAULT NULL,
                `site` VARCHAR(50) NULL DEFAULT NULL,
                `skype` VARCHAR(50) NULL DEFAULT NULL,
                `endereco` VARCHAR(50) NULL DEFAULT NULL,
                `numero` VARCHAR(10) NULL DEFAULT NULL,
                `complemento` VARCHAR(50) NULL DEFAULT NULL,
                `cep` VARCHAR(8) NULL DEFAULT NULL,
                `bairro` VARCHAR(50) NULL DEFAULT NULL,
                `cidade` VARCHAR(50) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `tipo_pessoa` ENUM('F', 'J', 'E') NULL DEFAULT 'J',
                `ativo` ENUM('A', 'I', 'E') NULL DEFAULT 'A',
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_tipo_log` INT(10) UNSIGNED NULL DEFAULT '0',
                `tipo_cadastro` ENUM('F', 'C') NULL DEFAULT 'F' COMMENT 'F = Fornecedor, C=Contato (agenda telefonica)',
                `id_fornecedor_servico` INT(11) NULL DEFAULT NULL,
                `id_tmp` INT(11) NULL DEFAULT NULL,
                `rgie` VARCHAR(30) NULL DEFAULT NULL,
                `fone_tmp` VARCHAR(15) NULL DEFAULT NULL,
                `insc_estadual` VARCHAR(15) NULL DEFAULT NULL,
                `insc_municipal` VARCHAR(15) NULL DEFAULT NULL,
                `id_forn_master` INT(10) NULL DEFAULT NULL,
                `data_fundacao` DATE NULL DEFAULT NULL,
                `prazo_entrega_produtos` INT(5) NULL DEFAULT NULL,
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `isento_icms` ENUM('S', 'N') NULL DEFAULT 'N',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `pais` VARCHAR(20) NULL DEFAULT 'BRASIL',
                `id_pais` INT(11) NULL DEFAULT '1058',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORNECEDOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_fornecedor_banco()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `fornecedor_banco`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_fornecedor` INT(11) NULL DEFAULT NULL,
                `id_banco` INT(3) NULL DEFAULT NULL,
                `agencia` VARCHAR(6) NULL DEFAULT NULL,
                `conta` VARCHAR(10) NULL DEFAULT NULL,
                `titular` VARCHAR(60) NULL DEFAULT NULL,
                `titular_cpfcnpj` VARCHAR(20) NULL DEFAULT NULL,
                `tipo_conta` ENUM('C', 'P') NULL DEFAULT 'C',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORNECEDOR_BANCO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_fornecedor_pedido()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `fornecedor_pedido`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_fornecedor` INT(11) NOT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `data_pedido` DATETIME NULL DEFAULT NULL COMMENT 'Data em que o pedido foi realizado',
                `statos_pedido` ENUM('A', 'C', 'E', 'P') NULL DEFAULT NULL COMMENT 'A - Aberto, C - Cancelado, E - Entregue, P - Pago',
                `data_entrega` DATETIME NULL DEFAULT NULL COMMENT 'Data em que o pedido foi entregue',
                `data_previsao_entrega` DATETIME NULL DEFAULT NULL COMMENT 'Data passada pelo fornecedor como previsao de entrega',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORNECEDOR_PEDIDO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_fornecedor_pedido_item()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `fornecedor_pedido_item`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_pedido` INT(11) NOT NULL,
                `id_grade` INT(11) NULL DEFAULT NULL,
                `id_usuario_criacao` INT(11) NULL DEFAULT NULL,
                `nome_item` VARCHAR(50) NULL DEFAULT NULL,
                `valor_custo` DECIMAL(10,3) NULL DEFAULT NULL,
                `quantidade` DECIMAL(8,3) NULL DEFAULT NULL,
                `data_criacao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `tipo_registro` ENUM('A', 'L') NOT NULL DEFAULT 'A' COMMENT 'A - Ativo, L - Log',
                `statos_item` ENUM('A', 'C') NOT NULL DEFAULT 'A' COMMENT 'A - Ativo, C- Cancelado',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORNECEDOR_PEDIDO_ITEM criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_fornecedor_produto()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `fornecedor_produto`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_fornecedor` INT(11) NULL DEFAULT NULL,
                `descricao` VARCHAR(60) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORNECEDOR_PRODUTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_fornecedor_servico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `fornecedor_servico`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(100) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `data_cadastro` DATE NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORNECEDOR_SERVICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_fornecedor_transportadora()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `fornecedor_transportadora`
            (
                `id_transportadora` INT(11) NOT NULL AUTO_INCREMENT,
                `id_fornecedor` INT(11) NULL DEFAULT NULL,
                `descricao` VARCHAR(60) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_transportadora`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FORNECEDOR_TRANSPORTADORA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_funcionario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `funcionario`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(50) NULL DEFAULT NULL,
                `funcao` VARCHAR(100) NULL DEFAULT NULL,
                `email` VARCHAR(50) NULL DEFAULT NULL,
                `telefone` VARCHAR(11) NULL DEFAULT NULL,
                `celular` VARCHAR(11) NULL DEFAULT NULL,
                `data_nascimento` DATE NULL DEFAULT NULL,
                `sexo` CHAR(1) NULL DEFAULT NULL,
                `nome_pai` VARCHAR(50) NULL DEFAULT NULL,
                `nome_mae` VARCHAR(50) NULL DEFAULT NULL,
                `naturalidade` VARCHAR(50) NULL DEFAULT NULL,
                `uf_naturalidade` CHAR(3) NULL DEFAULT NULL,
                `nacionalidade` VARCHAR(50) NULL DEFAULT NULL,
                `estado_civil` INT(1) UNSIGNED NULL DEFAULT NULL,
                `qtde_filho` INT(10) UNSIGNED NULL DEFAULT NULL,
                `grau_instrucao` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_tipo_log` INT(10) UNSIGNED NULL DEFAULT NULL,
                `endereco` VARCHAR(50) NULL DEFAULT NULL,
                `numero` VARCHAR(10) NULL DEFAULT NULL,
                `complemento` VARCHAR(50) NULL DEFAULT NULL,
                `cep` VARCHAR(8) NULL DEFAULT NULL,
                `bairro` VARCHAR(50) NULL DEFAULT NULL,
                `cidade` VARCHAR(50) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `cpf` VARCHAR(11) NULL DEFAULT NULL,
                `rg` VARCHAR(15) NULL DEFAULT NULL,
                `data_admissao` DATE NULL DEFAULT NULL,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT NULL,
                `id_cadastro` INT(11) UNSIGNED NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I', 'E') NULL DEFAULT 'A',
                `id_usuario_excluir` INT(11) NULL DEFAULT NULL,
                `pis` VARCHAR(15) NULL DEFAULT NULL,
                `sincronizado` INT(1) NOT NULL DEFAULT '0' COMMENT '0-Nao 1-Sim',
                `classificacao` INT(2) NULL DEFAULT '0' COMMENT '0-Operador  1-Supervisor',
                `comissao` DECIMAL(10,2) NULL DEFAULT '0.00',
                `comissao_servico` DECIMAL(10,2) NULL DEFAULT '0.00',
                `pessoa_recado1` VARCHAR(50) NULL DEFAULT NULL,
                `pessoa_recado2` VARCHAR(50) NULL DEFAULT NULL,
                `fone_recado1` VARCHAR(11) NULL DEFAULT NULL,
                `fone_recado2` VARCHAR(11) NULL DEFAULT NULL,
                `tipo_conta` ENUM('C', 'P') NULL DEFAULT 'C',
                `id_banco` INT(11) NULL DEFAULT NULL,
                `agencia` VARCHAR(6) NULL DEFAULT NULL,
                `conta` VARCHAR(10) NULL DEFAULT NULL,
                `titular` VARCHAR(60) NULL DEFAULT NULL,
                `titular_cpfcnpj` VARCHAR(14) NULL DEFAULT NULL,
                `salario` DECIMAL(10,2) NULL DEFAULT NULL,
                `tipo_comissao` ENUM('R', 'P') NULL DEFAULT 'R',
                `tipo_comissao_servico` ENUM('R', 'P') NULL DEFAULT 'R',
                `tp_funcionario` ENUM('G', 'S', 'F', 'P') NULL DEFAULT 'F',
                `mot_demissao` VARCHAR(40) NULL DEFAULT NULL,
                `data_demissao` DATE NULL DEFAULT NULL,
                `foto` VARCHAR(500) NULL DEFAULT NULL,
                `orgao_expedidor` VARCHAR(20) NULL DEFAULT NULL,
                `agenda` TINYINT(4) NULL DEFAULT '1',
                `tipo_funcionario` ENUM('G', 'N') NULL DEFAULT 'N' COMMENT 'G - Gerente, N - Normal',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_setor` INT(11) NULL DEFAULT NULL,
                `id_cargo` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FUNCIONARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_funcionario2()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `funcionario2`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(50) NULL DEFAULT NULL,
                `funcao` VARCHAR(100) NULL DEFAULT NULL,
                `email` VARCHAR(50) NULL DEFAULT NULL,
                `telefone` VARCHAR(10) NULL DEFAULT NULL,
                `celular` VARCHAR(10) NULL DEFAULT NULL,
                `data_nascimento` DATE NULL DEFAULT NULL,
                `sexo` CHAR(1) NULL DEFAULT NULL,
                `nome_pai` VARCHAR(50) NULL DEFAULT NULL,
                `nome_mae` VARCHAR(50) NULL DEFAULT NULL,
                `naturalidade` VARCHAR(50) NULL DEFAULT NULL,
                `nacionalidade` VARCHAR(50) NULL DEFAULT NULL,
                `estado_civil` INT(1) UNSIGNED NULL DEFAULT NULL,
                `qtde_filho` INT(10) UNSIGNED NULL DEFAULT NULL,
                `grau_instrucao` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_tipo_log` INT(10) UNSIGNED NULL DEFAULT NULL,
                `endereco` VARCHAR(50) NULL DEFAULT NULL,
                `numero` VARCHAR(10) NULL DEFAULT NULL,
                `complemento` VARCHAR(50) NULL DEFAULT NULL,
                `cep` VARCHAR(8) NULL DEFAULT NULL,
                `bairro` VARCHAR(50) NULL DEFAULT NULL,
                `cidade` VARCHAR(50) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `cpf` VARCHAR(11) NULL DEFAULT NULL,
                `rg` VARCHAR(15) NULL DEFAULT NULL,
                `data_admissao` DATE NULL DEFAULT NULL,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_cadastro` INT(11) UNSIGNED NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I', 'E') NULL DEFAULT 'A',
                `id_usuario_excluir` INT(11) NULL DEFAULT NULL,
                `pis` VARCHAR(15) NULL DEFAULT NULL,
                `sincronizado` INT(1) NOT NULL DEFAULT '0' COMMENT '0-Nao 1-Sim',
                `classificacao` INT(2) NULL DEFAULT '0' COMMENT '0-Operador  1-Supervisor',
                `comissao` DECIMAL(10,2) NULL DEFAULT '0.00',
                `comissao_servico` DECIMAL(10,2) NULL DEFAULT '0.00',
                `pessoa_recado1` VARCHAR(50) NULL DEFAULT NULL,
                `pessoa_recado2` VARCHAR(50) NULL DEFAULT NULL,
                `fone_recado1` VARCHAR(11) NULL DEFAULT NULL,
                `fone_recado2` VARCHAR(11) NULL DEFAULT NULL,
                `tipo_conta` ENUM('C', 'P') NULL DEFAULT 'C',
                `id_banco` INT(11) NULL DEFAULT NULL,
                `agencia` VARCHAR(6) NULL DEFAULT NULL,
                `conta` VARCHAR(10) NULL DEFAULT NULL,
                `titular` VARCHAR(60) NULL DEFAULT NULL,
                `titular_cpfcnpj` VARCHAR(14) NULL DEFAULT NULL,
                `salario` DECIMAL(10,2) NULL DEFAULT NULL,
                `tipo_comissao` ENUM('R', 'P') NULL DEFAULT 'R',
                `tipo_comissao_servico` ENUM('R', 'P') NULL DEFAULT 'R',
                `tp_funcionario` ENUM('G', 'S', 'F', 'P') NULL DEFAULT 'F',
                `mot_demissao` VARCHAR(40) NULL DEFAULT NULL,
                `data_demissao` DATE NULL DEFAULT NULL,
                `foto` VARCHAR(500) NULL DEFAULT NULL,
                `orgao_expedidor` VARCHAR(20) NULL DEFAULT NULL,
                `agenda` TINYINT(4) NULL DEFAULT '1',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FUNCIONARIO2 criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_funcionario_agendamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `funcionario_agendamento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_funcionario` INT(11) NOT NULL,
                `id_cadastro` INT(11) NOT NULL,
                `id_cliente_agendamentos` INT(11) NOT NULL,
                `data` DATE NULL DEFAULT NULL,
                `hora_inicio` TIME NULL DEFAULT NULL,
                `hora_fim` TIME NULL DEFAULT NULL,
                `status` ENUM('A', 'R', 'C') NULL DEFAULT 'A' COMMENT 'A = agendado, R = realizado, C = cancelado',
                `observacao` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`, `id_funcionario`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FUNCIONARIO_AGENDAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_funcionario_comissao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `funcionario_comissao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_funcionario` INT(11) NOT NULL DEFAULT '0',
                `id_produto` INT(11) NOT NULL DEFAULT '0',
                `tipo_comissao` ENUM('R', 'P') NULL DEFAULT NULL,
                `valor_comissao` DECIMAL(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FUNCIONARIO_COMISSAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_funcionario_funcao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `funcionario_funcao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `descricao` VARCHAR(150) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FUNCIONARIO_FUNCAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_funcionario_horario_trabalho()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `funcionario_horario_trabalho`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_semana` ENUM('0', '1', '2', '3', '4', '5', '6', '7') NULL DEFAULT NULL,
                `entrada_1` VARCHAR(5) NULL DEFAULT NULL,
                `saida_1` VARCHAR(5) NULL DEFAULT NULL,
                `entrada_2` VARCHAR(5) NULL DEFAULT NULL,
                `saida_2` VARCHAR(5) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `tempo_medio_atendimento` INT(11) NULL DEFAULT NULL,
                `antecedencia_agendamento` INT(11) NULL DEFAULT NULL,
                `choque_horario` ENUM('S', 'N') NULL DEFAULT NULL,
                `choque_qtde` INT(2) NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela FUNCIONARIO_HORARIO_TRABALHO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_grade()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `grade`
            (
                `id_grade` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) UNSIGNED NOT NULL,
                `id_produto` INT(11) UNSIGNED NOT NULL,
                `id_grade_atributo_valor` VARCHAR(255) NULL DEFAULT NULL,
                `id_usuario_alterou` INT(10) UNSIGNED NULL DEFAULT NULL,
                `codigo_barra_pai` VARCHAR(22) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(22) NULL DEFAULT NULL,
                `codigo_interno` VARCHAR(22) NULL DEFAULT NULL,
                `qtd_atual` DECIMAL(10,3) NULL DEFAULT '0.000',
                `qtd_minima` DECIMAL(10,3) NULL DEFAULT '0.000',
                `qtd_locacao` DECIMAL(10,3) NULL DEFAULT '0.000',
                `qtd_locacao_locado` DECIMAL(10,3) NULL DEFAULT '0.000' COMMENT 'Armazena o total locado, somar ao locar e remover na devolução',
                `valor_custo` DECIMAL(10,5) NULL DEFAULT NULL,
                `valor_varejo_avista` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_varejo_aprazo` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_atacado_avista` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_atacado_aprazo` DECIMAL(10,2) NULL DEFAULT NULL,
                `porc_varejo_avista` DECIMAL(18,15) NULL DEFAULT NULL,
                `porc_varejo_aprazo` DECIMAL(18,15) NULL DEFAULT NULL,
                `porc_atacado_avista` DECIMAL(18,15) NULL DEFAULT NULL,
                `porc_atacado_aprazo` DECIMAL(18,15) NULL DEFAULT NULL,
                `ativo` TINYINT(1) UNSIGNED NULL DEFAULT '1' COMMENT '1 = ativo, 0 inativo',
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `alteracao` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id_grade`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela GRADE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_grade_arrumar_estoque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `grade_arrumar_estoque`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_grade` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `qtd_arrumar` DECIMAL(15,3) NULL DEFAULT NULL,
                `data_hora` DATETIME NULL DEFAULT NULL,
                `id_usuario` INT(11) NOT NULL,
                `finalizado` ENUM('N', 'S') NOT NULL DEFAULT 'N',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela GRADE_ARRUMAR_ESTOQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_grade_atributo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `grade_atributo`
            (
                `id_grade_atributo` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `atributo` VARCHAR(100) NULL DEFAULT NULL,
                `ativo` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Ativo, 0=Desativado',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_grade_atributo`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela GRADE_ATRIBUTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_grade_atributo_valor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `grade_atributo_valor`
            (
                `id_grade_atributo_valor` INT(11) NOT NULL AUTO_INCREMENT,
                `id_atributo` INT(11) NULL DEFAULT NULL,
                `valor` VARCHAR(100) NULL DEFAULT NULL,
                `ativo` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Ativo, 0=Desativado',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_grade_atributo_valor`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela GRADE_ATRIBUTO_VALOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_grade_foto()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `grade_foto`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_grade` BIGINT(20) NOT NULL COMMENT 'id_grade',
                `caminho_imagem` VARCHAR(150) NULL DEFAULT NULL,
                PRIMARY KEY (`id`, `id_grade`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela GRADE_ATRIBUTO_FOTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_grade_historico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `grade_historico`
            (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_grade` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `qtd_antigo` DECIMAL(11,3) NULL DEFAULT NULL,
                `qtd_atual` DECIMAL(11,3) NULL DEFAULT NULL,
                `codigo_barra_antigo` CHAR(20) NULL DEFAULT NULL,
                `codigo_barra` CHAR(20) NULL DEFAULT NULL,
                `valor_custo_antigo` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_custo` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_varejo_aprazo_antigo` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_varejo_aprazo` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_atacado_aprazo_antigo` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_atacado_aprazo` DECIMAL(10,2) NULL DEFAULT NULL,
                `ativo_antigo` TINYINT(1) UNSIGNED NULL DEFAULT '1',
                `ativo` TINYINT(1) UNSIGNED NULL DEFAULT '1',
                `data_hora_alteracao` DATETIME NULL DEFAULT NULL,
                `origem_alteracao` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela GRADE_HISTORICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_grade_promocao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `grade_promocao`
            (
                `id_grade_promocao` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_grade` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
                `nome` VARCHAR(100) NULL DEFAULT NULL,
                `disponivel_inicio` DATETIME NULL DEFAULT NULL,
                `disponivel_final` DATETIME NULL DEFAULT NULL,
                `promo_valor_varejo_avista` DECIMAL(10,2) NULL DEFAULT NULL,
                `promo_valor_varejo_aprazo` DECIMAL(10,2) NULL DEFAULT NULL,
                `promo_valor_atacado_avista` DECIMAL(10,2) NULL DEFAULT NULL,
                `promo_valor_atacado_aprazo` DECIMAL(10,2) NULL DEFAULT NULL,
                `promo_porc_varejo_avista` DECIMAL(18,15) NULL DEFAULT NULL,
                `promo_porc_varejo_aprazo` DECIMAL(18,15) NULL DEFAULT NULL,
                `promo_porc_atacado_avista` DECIMAL(18,15) NULL DEFAULT NULL,
                `promo_porc_atacado_aprazo` DECIMAL(18,15) NULL DEFAULT NULL,
                `id_usuario_cadastrou` INT(11) NULL DEFAULT NULL,
                `cadastro` DATETIME NULL DEFAULT NULL,
                `id_usuario_deletou` INT(11) NULL DEFAULT NULL,
                `deletou` DATETIME NULL DEFAULT NULL,
                `ativo` CHAR(1) NULL DEFAULT '1',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_grade_promocao`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela GRADE_PROMOCAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_grade_saida_estoque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `grade_saida_estoque`
            (
                `id_grade_saida_estoque` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_grade` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
                `id_usuario` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `id_cliente` BIGINT(20) NULL DEFAULT '0',
                `qtd_estoque_anterior` DECIMAL(10,3) NULL DEFAULT '0.000',
                `qtd_movimentacao` DECIMAL(10,3) NULL DEFAULT '0.000',
                `motivo_principal` VARCHAR(255) NULL DEFAULT NULL,
                `motivo_secundario` VARCHAR(255) NULL DEFAULT NULL,
                `data_hora_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `unidade_destino` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id_grade_saida_estoque`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela GRADE_SAIDA_ESTOQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_grau_instrucao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `grau_instrucao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela GRAU_INSTRUCAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_historico_doc_fiscais()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `historico_doc_fiscais`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `data` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `comando` LONGTEXT NULL DEFAULT NULL,
                `status` INT(1) NULL DEFAULT NULL COMMENT '0 - Com Erro   -  1 - Sem Erro',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela HISTORICO_DOC_FISCAIS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_horario_trabalho()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `horario_trabalho`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `horario` VARCHAR(50) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela HORARIO_TRABALHO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_ibptax()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `ibptax`
            (
                `id_ibptax` BIGINT(1) UNSIGNED NOT NULL AUTO_INCREMENT,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `codigo` INT(11) NOT NULL,
                `ex` INT(11) NOT NULL,
                `tipo` VARCHAR(20) NULL DEFAULT NULL,
                `descricao` TEXT NULL DEFAULT NULL,
                `aliqNac` FLOAT NOT NULL,
                `aliqImp` FLOAT NOT NULL,
                `estadual` FLOAT NOT NULL,
                `municipal` FLOAT NOT NULL,
                `vigencia_inicio` VARCHAR(10) NOT NULL,
                `vigencia_fim` VARCHAR(10) NOT NULL,
                `chave` VARCHAR(15) NULL DEFAULT NULL,
                `versao` VARCHAR(7) NOT NULL,
                `fonte` VARCHAR(5) NULL DEFAULT NULL,
                PRIMARY KEY (`id_ibptax`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela IBPTAX criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_importacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `importacao`
            (
                `id_importacao` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL COMMENT 'ID DO CADASTRO QUE ESTA SENDO IMPORTADO',
                `id_usuario_importacao` INT(11) NOT NULL,
                `id_usuario_aprovacao` INT(11) NULL DEFAULT NULL,
                `data_importacao` DATETIME NULL DEFAULT NULL,
                `data_aprovacao` DATETIME NULL DEFAULT NULL,
                `nome_tabela` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`id_importacao`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela IMPORTACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_indica_amigo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `indica_amigo`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `codigo_associado` INT(11) NULL DEFAULT NULL,
                `nome_amigo` VARCHAR(100) NULL DEFAULT NULL,
                `fone_amigo1` CHAR(50) NULL DEFAULT NULL,
                `fone_amigo2` CHAR(50) NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `fatura_bonificar` VARCHAR(50) NULL DEFAULT NULL,
                `id_agendador` INT(11) NULL DEFAULT NULL,
                `tipo_recebimento` INT(11) NULL DEFAULT NULL,
                `quem_indicou` VARCHAR(255) NULL DEFAULT NULL,
                `funcao_empresa` VARCHAR(255) NULL DEFAULT NULL,
                `conta_bancaria` VARCHAR(45) NULL DEFAULT NULL,
                `banco` VARCHAR(45) NULL DEFAULT NULL,
                `agencia` VARCHAR(45) NULL DEFAULT NULL,
                `tipo_conta` INT(11) NULL DEFAULT NULL,
                `n_conta` VARCHAR(45) NULL DEFAULT NULL,
                `nome_titular` VARCHAR(255) NULL DEFAULT NULL,
                `cnpj_cpf` VARCHAR(45) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela INDICA_AMIGO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_indica_amigo_log()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `indica_amigo_log`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_indicacao` INT(11) NULL DEFAULT NULL,
                `status_indicacao` CHAR(2) NULL DEFAULT NULL COMMENT 'VR - Venda Realizada | SI - Sem Interesse | RE - Reagendado | SC - Sem Contato',
                `cod_cliente_vr` CHAR(10) NULL DEFAULT NULL COMMENT 'Codigo do cliente caso a venda tenha sido realizada',
                `dt_nota` TIMESTAMP NULL DEFAULT NULL,
                `desc_nota` TEXT NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `num_doc` VARCHAR(20) NULL DEFAULT NULL,
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela INDICA_AMIGO_LOG criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_lancamentos_empresas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `lancamentos_empresas`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_tipo_lan` INT(11) NULL DEFAULT NULL,
                `id_conta` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `credor` VARCHAR(60) NULL DEFAULT NULL,
                `valor` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
                `operacao` CHAR(1) NOT NULL COMMENT 'D - Decrementa, I - Incrementa',
                `data_lan` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LANCAMENTOS_EMPRESAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_limite_funcionario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `limite_funcionario`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_funcionario` INT(11) NOT NULL,
                `data_inicio` DATE NOT NULL,
                `qtd_dias` INT(11) NOT NULL,
                `valor` DECIMAL(15,2) NOT NULL,
                `renovar` ENUM('S', 'N') NOT NULL DEFAULT 'N',
                `ativo` ENUM('A', 'I') NOT NULL DEFAULT 'A',
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LIMITE_FUNCIONARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_link()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `link`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `a_link` VARCHAR(50) NULL DEFAULT NULL,
                `referencia` VARCHAR(100) NULL DEFAULT NULL,
                `descricao` VARCHAR(150) NULL DEFAULT NULL,
                `ordem_link` INT(10) UNSIGNED NULL DEFAULT '0',
                `id_modulo` INT(11) NULL DEFAULT NULL,
                `cod_permissao` INT(11) NULL DEFAULT NULL,
                `cod_permissao_dupla` VARCHAR(20) NULL DEFAULT NULL,
                `status` ENUM('A', 'I') NULL DEFAULT 'A',
                `link` ENUM('S', 'N') NULL DEFAULT 'S',
                `visivel` ENUM('S', 'N') NULL DEFAULT 'S',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LINK criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_acesso_offline()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_acesso_offline`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `data` DATE NULL DEFAULT NULL,
                `hora` TIME NULL DEFAULT NULL,
                `tipo_acesso` VARCHAR(1) NULL DEFAULT NULL COMMENT '0 - Login | 1 - Sincronismo Geral | 2 - Sincronismo Parcial',
                `terminal` VARCHAR(10) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_ACESSO_OFFLINE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_acoes_notasfiscais()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_acoes_notasfiscais`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cadastro` BIGINT(20) NULL DEFAULT NULL,
                `id_usuario` BIGINT(20) NULL DEFAULT NULL,
                `id_venda` BIGINT(20) NULL DEFAULT NULL,
                `tipo_nota` ENUM('NFE', 'NFC', 'NFS') NULL DEFAULT NULL,
                `acao` ENUM('E', 'C', 'CC', 'A', 'R') NULL DEFAULT NULL COMMENT 'E - Email C - Cancelamento CC - Carta de Correção A - Apagada R - Recuperada',
                `numero_nota` VARCHAR(50) NULL DEFAULT NULL,
                `email_enviado` VARCHAR(255) NULL DEFAULT NULL,
                `data_hora_acao` DATETIME NULL DEFAULT NULL,
                `endereco_cc` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_ACOES_NOTASFISCAIS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_dados_cadastro()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_dados_cadastro`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `email` VARCHAR(100) NULL DEFAULT NULL,
                `telefone` VARCHAR(50) NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `origem` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_DADOS_CADASTRO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_envia_email()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_envia_email`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `id_usuario_envio` INT(11) NULL DEFAULT NULL,
                `email_destino` VARCHAR(150) NOT NULL COMMENT 'email do destinatario',
                `origem_envio` VARCHAR(150) NULL DEFAULT NULL COMMENT 'parte do sistema de onde foi solicitado o envio do email',
                `data_hora_envio` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'data e hora do envio do email',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_ENVIA_EMAIL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_erro_sessao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_erro_sessao`
            (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `pagina_anterior` CHAR(254) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_ERRO_SESSAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_estoque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_estoque`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(45) NULL DEFAULT NULL,
                `qtd` VARCHAR(45) NULL DEFAULT NULL,
                `descricao` VARCHAR(45) NULL DEFAULT NULL,
                `preco_custo` DECIMAL(15,3) NULL DEFAULT NULL,
                `preco_venda` DECIMAL(15,3) NULL DEFAULT NULL,
                `data_log` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_ESTOQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_mensage_atencao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_mensage_atencao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_alert` INT(11) NULL DEFAULT NULL,
                `save_data` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_MENSAGE_ATENCAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_monitoramento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_monitoramento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `log` TEXT NOT NULL,
                `data_hora` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_MONITORAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_sync_loja()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_sync_loja`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `data_sync` DATETIME NOT NULL,
                `id_usuario` INT(11) NOT NULL,
                `status` ENUM('O', 'E') NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_SYNC_LOJA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_sync_loja_itens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_sync_loja_itens`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_sync` INT(11) NOT NULL,
                `codigo_barra` VARCHAR(25) NOT NULL,
                `descricao` VARCHAR(150) NOT NULL,
                `classificacao` VARCHAR(255) NOT NULL,
                `cla_ativa` ENUM('A', 'I') NULL DEFAULT NULL,
                `cla_ecommerce` ENUM('S', 'N') NULL DEFAULT NULL,
                `prod_ativo` ENUM('A', 'I') NULL DEFAULT NULL,
                `prod_ecommerce` ENUM('S', 'N') NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_SYNC_LOJA_ITENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_log_web_control()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `log_web_control`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `acessos` LONGTEXT NULL DEFAULT NULL,
                `criado` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `classificacao` VARCHAR(50) NULL DEFAULT NULL,
                `tipo_reajuste` VARCHAR(50) NULL DEFAULT NULL,
                `indice` VARCHAR(50) NULL DEFAULT NULL,
                `nome_usuario` CHAR(60) NULL DEFAULT NULL,
                `informacao` TEXT NOT NULL,
                `tipo_log` VARCHAR(5) NULL DEFAULT NULL COMMENT 'DelCV - Exclusão de informações, ReJProd - Reajuste de preços de produto, ZCEST - Zerar Cest Produtos',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela LOG_WEB_CONTROL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mailmkt_campanha()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mailmkt_campanha`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT '0',
                `nome_campanha` VARCHAR(150) NULL DEFAULT NULL,
                `assunto_email` VARCHAR(50) NULL DEFAULT NULL,
                `conteudoHtml` MEDIUMTEXT NULL DEFAULT NULL,
                `conteudoText` MEDIUMTEXT NULL DEFAULT NULL,
                `data_envio` DATE NULL DEFAULT NULL,
                `hora_envio` TIME NULL DEFAULT NULL,
                `status_campanha` CHAR(1) NULL DEFAULT NULL COMMENT 'E - Enviado / A - Agendado / R - Rascunho / P - Pausada / C - Cancelada',
                `lista` TEXT NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MAILMKT_CAMPANHA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mailmkt_campanha_agendamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mailmkt_campanha_agendamento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_mailmkt_campanha` INT(11) NOT NULL,
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL,
                `hora_agendamento` TIME NULL DEFAULT NULL,
                `status_agendamento` ENUM('A', 'E') NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MAILMKT_CAMPANHA_AGENDAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mailmkt_campanha_fixa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mailmkt_campanha_fixa`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome_campanha` VARCHAR(150) NULL DEFAULT NULL,
                `texto` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MAILMKT_CAMPANHA_FIXA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mailmkt_campanha_fixa_ignorar()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mailmkt_campanha_fixa_ignorar`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_torpedo_campanha_fixa` INT(11) NULL DEFAULT NULL,
                `data_exclusao` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MAILMKT_CAMPANHA_FIXA_IGNORAR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mailmkt_config()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mailmkt_config`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `nome_remetente` VARCHAR(100) NOT NULL,
                `email_remetente` VARCHAR(100) NOT NULL,
                `horario_gmt` VARCHAR(5) NOT NULL,
                `subaccount_id` INT(11) NOT NULL DEFAULT '0',
                `subaccount_key` CHAR(50) NOT NULL DEFAULT '0',
                `subaccount_shortkey` CHAR(10) NOT NULL DEFAULT '0',
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MAILMKT_CONFIG criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mailmkt_config_master()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mailmkt_config_master`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `sparkpost_supperaccount_key` CHAR(50) NOT NULL DEFAULT '0',
                `sender_domain` VARCHAR(100) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MAILMKT_CONFIG_MASTER criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mailmkt_lista()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mailmkt_lista`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `nome_lista` VARCHAR(200) NOT NULL,
                `emails_lista` LONGTEXT NOT NULL,
                `tipo_lista` CHAR(1) NULL DEFAULT NULL COMMENT 'C - Clientes Webcontrol / D - Emails Digitados / I - Importados',
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `fixa` ENUM('S', 'N') NULL DEFAULT 'N',
                `status` ENUM('A', 'I') NULL DEFAULT 'A',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MAILMKT_LISTA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mailmkt_lista_emails()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mailmkt_lista_emails`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_lista` INT(11) NULL DEFAULT NULL,
                `email` VARCHAR(150) NULL DEFAULT NULL,
                `nome` VARCHAR(250) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MAILMKT_LISTA_EMAILS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mailmkt_log()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mailmkt_log`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_campanha` INT(11) NULL DEFAULT NULL,
                `id_transmissao_spkp` CHAR(50) NULL DEFAULT NULL COMMENT 'ID que retorna de cada transmissao pelo sparkpost',
                `transmissao_aceitos` CHAR(20) NULL DEFAULT NULL,
                `transmissao_rejeitados` CHAR(20) NULL DEFAULT NULL,
                `total_emails_enviados` INT(11) NULL DEFAULT NULL,
                `action` CHAR(1) NULL DEFAULT NULL COMMENT 'A - Agendamento / E - Envio / R - Rascunho / C - Cancelada / X - Excluida',
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `email` VARCHAR(60) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MAILMKT_LOG criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_manifest()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `manifest`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `tipo_transporte` INT(11) NULL DEFAULT NULL,
                `rntrc` BIGINT(8) NULL DEFAULT NULL,
                `ciot` BIGINT(12) NULL DEFAULT NULL,
                `veictracao` INT(11) NULL DEFAULT NULL,
                `condutor` INT(11) NULL DEFAULT NULL,
                `veicreboque` INT(11) NULL DEFAULT NULL,
                `vvaleped` VARCHAR(45) NULL DEFAULT NULL,
                `disp` TEXT NULL DEFAULT NULL,
                `cnpjform` VARCHAR(14) NULL DEFAULT NULL,
                `cnpjpg` VARCHAR(14) NULL DEFAULT NULL,
                `ncompra` VARCHAR(20) NULL DEFAULT NULL,
                `createdat` DATETIME NULL DEFAULT NULL,
                `deletedat` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MANIFEST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_manifest_condutor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `manifest_condutor`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `nome` VARCHAR(100) NULL DEFAULT NULL,
                `cpf` VARCHAR(45) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MANIFEST_CONDUTOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_manifest_documentos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `manifest_documentos`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `manifesto_id` INT(11) NULL DEFAULT NULL,
                `tipo_doc` VARCHAR(50) NULL DEFAULT NULL,
                `municipio` VARCHAR(155) NULL DEFAULT NULL,
                `id_doc` TEXT NULL DEFAULT NULL,
                `qtd` VARCHAR(45) NULL DEFAULT NULL,
                `peso_total` DECIMAL(10,3) NULL DEFAULT NULL,
                `valor_total` FLOAT NULL DEFAULT NULL,
                `xml` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MANIFEST_DOCUMENTOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_manifest_reboque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `manifest_reboque`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `placa` VARCHAR(45) NULL DEFAULT NULL,
                `tara` DECIMAL(12,3) NULL DEFAULT NULL,
                `capkg` DECIMAL(12,3) NULL DEFAULT NULL,
                `capm3` DECIMAL(12,3) NULL DEFAULT NULL,
                `prop` VARCHAR(255) NULL DEFAULT NULL,
                `cpf_cnpj` VARCHAR(255) NULL DEFAULT NULL,
                `rntrc` VARCHAR(45) NULL DEFAULT NULL,
                `xnome` VARCHAR(65) NULL DEFAULT NULL,
                `ie` BIGINT(20) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `tpprop` INT(11) NULL DEFAULT NULL COMMENT '01 - Truck | 02 - Toco | 03 - Cavalo Mecânico | 04 - VAN | 05 - Utilitário | 06 - Outros',
                `tpcar` INT(11) NULL DEFAULT NULL COMMENT '00 - não aplicável | 01 - Aberta | 02 - Fechada/Baú | 03 - Granelera | 04 - Porta Container | 05 - Sider',
                `tprodado` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MANIFEST_REBOQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_manifest_uf_percurso()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `manifest_uf_percurso`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `manifest_id` INT(11) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MANIFEST_UF_PERCURSO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_manifest_veictracao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `manifest_veictracao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `placa` VARCHAR(45) NULL DEFAULT NULL,
                `tara` DECIMAL(12,3) NULL DEFAULT NULL,
                `capkg` DECIMAL(12,3) NULL DEFAULT NULL,
                `capm3` DECIMAL(12,3) NULL DEFAULT NULL,
                `prop` VARCHAR(255) NULL DEFAULT NULL,
                `ie` BIGINT(20) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `tpprop` INT(11) NULL DEFAULT NULL COMMENT '0-TAC ( Agregado ) |  1-TAC (Independente | 2 Outros',
                `tprod` INT(11) NULL DEFAULT NULL COMMENT '01 - Truck | 02 - Toco | 03 Cavalo Mecânico | 04 - Van | 05 - Utilitário | 06 Outros',
                `cnpj_cpf` VARCHAR(45) NULL DEFAULT NULL,
                `tpcar` INT(11) NULL DEFAULT NULL COMMENT '00 - não aplicável | 01 - Aberta | 02 - Fechada/Baú | 03 - Granelera | 04 - Porta Container | 05 - Sider',
                `rntrc` VARCHAR(145) NULL DEFAULT NULL,
                `ciot` VARCHAR(145) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MANIFEST_VEICTRACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_manifesto_informacoes()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `manifesto_informacoes`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `codloja` INT(11) NULL DEFAULT NULL,
                `modal` INT(11) NULL DEFAULT NULL,
                `carregamento_uf` CHAR(2) NULL DEFAULT NULL,
                `carregamento_cidade` VARCHAR(100) NULL DEFAULT NULL,
                `descarregamento_uf` CHAR(2) NULL DEFAULT NULL,
                `descarregamento_cidade` VARCHAR(145) NULL DEFAULT NULL,
                `numero` VARCHAR(45) NULL DEFAULT NULL,
                `geracao_automatica` TINYINT(1) NULL DEFAULT NULL,
                `serie` VARCHAR(45) NULL DEFAULT NULL,
                `tipo_emitente` INT(11) NULL DEFAULT NULL,
                `data_emissao` DATETIME NULL DEFAULT NULL,
                `descricao` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MANIFEST_INFORMACOES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_manifesto_modal()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `manifesto_informacoes`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `manifesto_id` INT(11) NULL DEFAULT NULL,
                `ciot` VARCHAR(105) NULL DEFAULT NULL,
                `rntrc` VARCHAR(105) NULL DEFAULT NULL,
                `veic_tracao` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MANIFEST_INFORMACOES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_manifesto_modal_condutor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `manifesto_modal_condutor`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `manifesto_id` INT(11) NULL DEFAULT NULL,
                `id_condutor` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MANIFEST_INFORMACOES_CONDUTOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_manifesto_modal_reboque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `manifesto_modal_reboque`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `manifesto_id` INT(11) NULL DEFAULT NULL,
                `id_reboque` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MANIFEST_MODAL_REBOQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_marcas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `marcas`
            (
                `id` INT(10) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `nome_marca` VARCHAR(50) NULL DEFAULT NULL,
                `imagem` VARCHAR(255) NULL DEFAULT NULL,
                `loja_virtual` TINYINT(4) NULL DEFAULT '0',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MARCAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_matriz_filial()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `matriz_filial`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_matriz` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_filial` INT(10) UNSIGNED NULL DEFAULT NULL,
                `tipo_filiacao` TINYINT(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 - filiacao parcial(compartilhamento), 2 - filiacao total(fusao)',
                `convite_filial` TINYINT(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 - convite enviado, 2 - convite recusado, 3 - convite aceito',
                `ativo` TINYINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 - inativo, 1 - ativo',
                `data_criacao` DATETIME NOT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MATRIZ_FILIAL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_matriz_filial_historico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `matriz_filial_historico`
            (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_acesso_filiacao` INT(10) UNSIGNED NULL DEFAULT NULL,
                `sql_solicitado` TEXT NULL DEFAULT NULL,
                `data_acao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `tipo_acao` CHAR(1) NOT NULL COMMENT 'C - Create, U - Update , D - Delete',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MATRIZ_FILIAL_HISTORICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_matriz_permissao_modulo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `matriz_permissao_modulo`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome_permissao` VARCHAR(100) NULL DEFAULT NULL,
                `id_modulo` INT(11) NULL DEFAULT NULL,
                `id_submodulo` INT(11) NULL DEFAULT NULL,
                `ordem_apresentacao` INT(11) NULL DEFAULT NULL COMMENT 'Ordem de apresentacao para o usuario, de preferencia, colocar numeros com range amplo para atualizacoes',
                `ativo` TINYINT(4) NULL DEFAULT '1',
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MATRIZ_PERMISSAO_MODULO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mensagens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mensagens`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome_permissao` VARCHAR(100) NULL DEFAULT NULL,
                `id_modulo` INT(11) NULL DEFAULT NULL,
                `id_submodulo` INT(11) NULL DEFAULT NULL,
                `ordem_apresentacao` INT(11) NULL DEFAULT NULL COMMENT 'Ordem de apresentacao para o usuario, de preferencia, colocar numeros com range amplo para atualizacoes',
                `ativo` TINYINT(4) NULL DEFAULT '1',
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MENSAGENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_mercado_livre_produto()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `mercado_livre_produto`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_mercado_livre` VARCHAR(255) NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `created_at` DATETIME NULL DEFAULT NULL,
                `updated_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MERCADO_LIVRE_PRODUTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_modalidade_calculo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `modalidade_calculo`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(25) NULL DEFAULT NULL,
                `situacao` ENUM('A', 'I') NULL DEFAULT 'A',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MODALIDADE_CALCULO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_modalidade_calculo_st()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `modalidade_calculo_st`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `situacao` ENUM('A', 'I') NULL DEFAULT 'A',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MODALIDADE_CALCULO_ST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_modelo_contrato()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `modelo_contrato`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `nome_modelo` VARCHAR(255) NULL DEFAULT NULL,
                `data_criacao` DATETIME NULL DEFAULT NULL,
                `ativo` TINYINT(4) NULL DEFAULT '1',
                `texto_clausulas` TEXT NULL DEFAULT NULL,
                `tipo_contrato` ENUM('L', 'OS') NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MODELO_CONTRATO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_modulo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `modulo`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `faixa_permissao` VARCHAR(10) NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A',
                `ordem_modulo` INT(2) NULL DEFAULT NULL,
                `yotube` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MODULO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_modulos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `modulos`
            (
                `id_modulo` INT(11) NOT NULL AUTO_INCREMENT,
                `cod_modulo` INT(11) NOT NULL,
                `nome_modulo` CHAR(50) NOT NULL,
                `descricao_modulo` CHAR(254) NOT NULL,
                PRIMARY KEY (`id_modulo`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MODULOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_movimento_titulo_recebafacil()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `movimento_titulo_recebafacil`
            (
                `id` BIGINT(11) NOT NULL AUTO_INCREMENT,
                `numboleto` BIGINT(20) NOT NULL,
                `acao` ENUM('I', 'B', 'E') NULL DEFAULT 'I',
                `data_movimento` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `vencimento` DATE NULL DEFAULT NULL,
                `valor` DECIMAL(12,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MOVIMENTO_TITULO_RECEBAFACIL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_municipio_rf()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `municipio_rf`
            (
                `id` BIGINT(1) NOT NULL AUTO_INCREMENT,
                `codigo` INT(4) UNSIGNED ZEROFILL NOT NULL DEFAULT '0000',
                `cidade` VARCHAR(57) NOT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela MUNICIPIO_RF criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_ncm()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `ncm`
            (
                `codigo` VARCHAR(20) NOT NULL,
                `descricao` VARCHAR(100) NOT NULL DEFAULT '',
                PRIMARY KEY (`codigo`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NCM criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_devolucao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_devolucao`
            (
                `id` BIGINT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `data` DATE NOT NULL,
                `hora` TIME NOT NULL,
                `id_fornecedor` INT(11) NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `id_natureza` INT(2) NULL DEFAULT NULL,
                `id_transportadora` INT(11) NULL DEFAULT NULL,
                `modalidade_frete` INT(1) NULL DEFAULT NULL,
                `info_adicionais` LONGTEXT NULL DEFAULT NULL,
                `danfe_saida` LONGTEXT NULL DEFAULT NULL,
                `num_nota_saida` INT(10) NOT NULL,
                `finalizada` ENUM('S', 'N') NULL DEFAULT 'N',
                `danfe_entrada` LONGTEXT NULL DEFAULT NULL,
                `ano_emissao` VARCHAR(45) NULL DEFAULT NULL,
                `outras_despesas` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `tipo_nota` INT(1) NULL DEFAULT '0' COMMENT '0 - Entrada   1 - Saida',
                `id_venda` INT(11) NULL DEFAULT NULL,
                `status` ENUM('A', 'S', 'C', 'R', 'D') NULL DEFAULT 'A' COMMENT 'A - Aguardando, S - Nota Gerada com Sucesso , C - Nota Cancelada - R -Nota Rejeitada, D - Denegada',
                `finalidade` ENUM('D', 'S', 'E') NULL DEFAULT 'D' COMMENT 'Devolucao - Saida - Entrada',
                `natureza` VARCHAR(255) NULL DEFAULT NULL,
                `qtd_volume` INT(3) NULL DEFAULT '1',
                `descricao_volume` VARCHAR(10) NULL DEFAULT 'BOX',
                `numero_nota_sefaz` INT(11) NULL DEFAULT NULL,
                `vlr_base_calc_st` DECIMAL(13,3) NULL DEFAULT NULL,
                `vlr_icms_st` DECIMAL(13,3) NULL DEFAULT NULL,
                `peso_bruto` DECIMAL(12,3) NULL DEFAULT NULL,
                `peso_liquido` DECIMAL(12,3) NULL DEFAULT NULL,
                `valor_desconto` DECIMAL(12,2) NULL DEFAULT NULL,
                `tipo_finalidade` ENUM('1', '2', '3', '4') NULL DEFAULT NULL COMMENT '1- NOTA NORMAL 2- NOTA COMPLEMENTAR 3- NOTA DE AJUSTE 4- NOTA DE DEVOLUÇÃO',
                `ambiente` INT(1) UNSIGNED NULL DEFAULT '1' COMMENT '1 - Producao 2 - Homologacao',
                `valor_frete` DECIMAL(15,3) NULL DEFAULT NULL,
                `data_emissao` DATE NULL DEFAULT NULL,
                `xml` LONGTEXT NULL DEFAULT NULL,
                `placa` VARCHAR(20) NULL DEFAULT NULL,
                `cod_antt` VARCHAR(70) NULL DEFAULT NULL,
                `id_placa` INT(11) NULL DEFAULT NULL,
                `numeracao` VARCHAR(70) NULL DEFAULT NULL,
                `forma_pgto` INT(1) NULL DEFAULT '0' COMMENT '0 -  A vista, 1 - Prazo, 2 - Outros',
                `ndi` INT(11) NULL DEFAULT NULL,
                `datadi` DATE NULL DEFAULT NULL,
                `datadesembarco` DATE NULL DEFAULT NULL,
                `localdesembarco` VARCHAR(255) NULL DEFAULT NULL,
                `transporte` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_DEVOLUCAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_devolucao_cobranca()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_devolucao_cobranca`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_nota_devolucao` INT(11) NULL DEFAULT NULL,
                `dup_numero` VARCHAR(10) NULL DEFAULT NULL,
                `dup_venc` DATE NULL DEFAULT NULL,
                `dup_valor` DECIMAL(12,2) NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_DEVOLUCAO_COBRANCA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_devolucao_itens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_devolucao_itens`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_nota_devolucao` INT(11) NULL DEFAULT NULL,
                `numero_nota` INT(11) NOT NULL,
                `id_cadastro` INT(11) NOT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(20) NOT NULL,
                `qtd` DECIMAL(10,4) NULL DEFAULT NULL,
                `vr_unit` DECIMAL(12,4) NULL DEFAULT NULL,
                `cfop` VARCHAR(5) NOT NULL,
                `cest` INT(11) NULL DEFAULT NULL,
                `icms` INT(11) NOT NULL,
                `pis` INT(11) NOT NULL,
                `cofins` INT(11) NOT NULL,
                `ipi` INT(2) NULL DEFAULT NULL,
                `finalizado` ENUM('S', 'N') NULL DEFAULT 'N',
                `id_grade` BIGINT(20) NULL DEFAULT NULL,
                `vlr_base_calc_st` DECIMAL(13,4) NULL DEFAULT NULL,
                `vlr_icms_st` DECIMAL(13,2) NULL DEFAULT NULL,
                `vr_bc_cfop` DECIMAL(12,4) NULL DEFAULT NULL,
                `vr_bc_icms` DECIMAL(12,4) NULL DEFAULT NULL,
                `vr_bc_ipi` DECIMAL(12,4) NULL DEFAULT NULL,
                `vr_bc_pis` DECIMAL(12,4) NULL DEFAULT NULL,
                `vr_bc_cofins` DECIMAL(12,4) NULL DEFAULT NULL,
                `unidade` VARCHAR(6) NULL DEFAULT NULL,
                `ordem_compra` VARCHAR(20) NULL DEFAULT NULL,
                `vr_bc_afrmm` DECIMAL(8,2) NULL DEFAULT '0.00',
                `vr_bc_import` DECIMAL(8,2) NULL DEFAULT '0.00',
                `produto_descricao` VARCHAR(255) NULL DEFAULT NULL,
                `nitemped` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_DEVOLUCAO_ITENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_devolucao_itens_COFINS()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_devolucao_itens_COFINS`
            (
                `id_nf_devolucao_itens` INT(11) NOT NULL AUTO_INCREMENT,
                `CST` INT(2) UNSIGNED ZEROFILL NULL DEFAULT '00',
                `pCOFINS` DECIMAL(10,3) NULL DEFAULT NULL,
                `qBCProd` DOUBLE NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_calculo` ENUM('P', 'V') NULL DEFAULT 'P',
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `p_aliq` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id_nf_devolucao_itens`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_DEVOLUCAO_ITENS_COFINS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_devolucao_itens_COFINSST()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_devolucao_itens_COFINSST`
            (
                `id_nf_devolucao_itens` INT(11) NOT NULL AUTO_INCREMENT,
                `imposto_id` INT(11) NULL DEFAULT NULL,
                `pCOFINS` DECIMAL(10,2) NULL DEFAULT NULL,
                `qBCProd` DECIMAL(10,2) NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_calculo` ENUM('P', 'V') NULL DEFAULT 'P',
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_nf_devolucao_itens`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_DEVOLUCAO_ITENS_COFINSST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_devolucao_itens_ICMS()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_devolucao_itens_ICMS`
            (
                `id_nf_devolucao_itens` INT(11) NOT NULL AUTO_INCREMENT,
                `orig` CHAR(1) NULL DEFAULT NULL,
                `CST` CHAR(3) NULL DEFAULT NULL,
                `modBC` CHAR(1) NULL DEFAULT NULL,
                `pRedBC` DECIMAL(10,3) NULL DEFAULT NULL,
                `pICMS` DECIMAL(10,2) NULL DEFAULT NULL,
                `modBCST` CHAR(1) NULL DEFAULT NULL,
                `pMVAST` DECIMAL(10,3) NULL DEFAULT NULL,
                `pRedBCST` DECIMAL(10,3) NULL DEFAULT NULL,
                `pICMSST` DECIMAL(10,2) NULL DEFAULT NULL,
                `regimes` ENUM('T', 'S') NULL DEFAULT 'T',
                `pOpePropria` DECIMAL(10,3) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `vl_aliq_calc_cre` DECIMAL(10,3) NULL DEFAULT NULL,
                `bc_icms_st_ret_ant` DECIMAL(10,3) NULL DEFAULT NULL,
                `pMVAST_LR` DECIMAL(10,3) NULL DEFAULT NULL,
                `valor_desoneracao_icms` DECIMAL(10,2) NULL DEFAULT NULL,
                `motivo_desoneracao_icms` VARCHAR(60) NULL DEFAULT NULL,
                `percentual_diferimento_icms` DECIMAL(10,2) NULL DEFAULT NULL,
                `uf_retido_remetente_icms_st` CHAR(2) NULL DEFAULT NULL,
                `uf_destino_icms_st` CHAR(2) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_origem` VARCHAR(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_nf_devolucao_itens`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_DEVOLUCAO_ITENS_ICMS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_devolucao_itens_II()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_devolucao_itens_II`
            (
                `id_nf_devolucao_itens` INT(11) NOT NULL AUTO_INCREMENT,
                `vBC` DOUBLE NULL DEFAULT NULL,
                `vDespAdu` DOUBLE NULL DEFAULT NULL,
                `vII` DOUBLE NULL DEFAULT NULL,
                `vIOF` DOUBLE NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_nf_devolucao_itens`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_DEVOLUCAO_ITENS_II criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_devolucao_itens_IPI()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_devolucao_itens_IPI`
            (
                `id_nf_devolucao_itens` INT(11) NOT NULL AUTO_INCREMENT,
                `cIEnq` CHAR(5) NULL DEFAULT NULL,
                `CNPJProd` CHAR(14) NULL DEFAULT NULL,
                `cSelo` VARCHAR(60) NULL DEFAULT NULL,
                `qSelo` DOUBLE NULL DEFAULT NULL,
                `cEnq` CHAR(3) NULL DEFAULT NULL,
                `CST` INT(2) UNSIGNED ZEROFILL NULL DEFAULT '00',
                `qUnid` DOUBLE NULL DEFAULT NULL,
                `pIPI` DOUBLE NULL DEFAULT NULL,
                `tp_calculo` ENUM('P', 'V') NULL DEFAULT 'P',
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_nf_devolucao_itens`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_DEVOLUCAO_ITENS_IPI criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_devolucao_itens_PIS()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_devolucao_itens_PIS`
            (
                `id_nf_devolucao_itens` INT(11) NOT NULL AUTO_INCREMENT,
                `tp_calculo` CHAR(1) NULL DEFAULT 'N' COMMENT 'N - Nulo    P - Percentual  V-Valores',
                `CST` INT(2) UNSIGNED ZEROFILL NULL DEFAULT '00',
                `pPIS` DECIMAL(10,2) NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_nf_devolucao_itens`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_DEVOLUCAO_ITENS_PIS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_devolucao_itens_PISST()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_devolucao_itens_PISST`
            (
                `id_nf_devolucao_itens` INT(11) NOT NULL AUTO_INCREMENT,
                `tp_calculo` CHAR(1) NULL DEFAULT 'N' COMMENT 'N - Nulo    P - Percentual  V-Valores',
                `pPIS` DOUBLE NULL DEFAULT NULL,
                `qBCProd` DOUBLE NULL DEFAULT NULL,
                `vAliqProd` DOUBLE NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_nf_devolucao_itens`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_DEVOLUCAO_ITENS_PISST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_entrada()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_entrada`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `id_fornecedor` INT(11) UNSIGNED NULL DEFAULT NULL,
                `data_cadastro` DATE NULL DEFAULT NULL,
                `hora_cadastro` TIME NULL DEFAULT NULL,
                `numero_nota` INT(11) NULL DEFAULT NULL,
                `vr_icms` DECIMAL(12,2) NOT NULL,
                `vr_icms_st` DECIMAL(12,2) NOT NULL,
                `vr_ipi` DECIMAL(12,2) NOT NULL,
                `vr_pis` DECIMAL(12,2) NOT NULL,
                `vr_cofins` DECIMAL(12,2) NOT NULL,
                `vr_frete` DECIMAL(12,2) NOT NULL,
                `vr_seguro` DECIMAL(12,2) NOT NULL,
                `outras_despesas` DECIMAL(12,2) NOT NULL,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `finalizado` ENUM('S', 'N') NULL DEFAULT 'N',
                `status` ENUM('A', 'C', 'S', 'D', 'R') NULL DEFAULT 'A',
                `ambiente` INT(1) NULL DEFAULT '1' COMMENT '1 - Producao   2 - Homologacao',
                `numero_nota_sefaz` INT(11) NULL DEFAULT NULL,
                `natureza_operacao` VARCHAR(60) NOT NULL DEFAULT 'OUTRAS ENTRADAS NAO ESPECIFICADAS',
                `finalidade_nota` ENUM('1', '2', '3', '4') NULL DEFAULT '1' COMMENT '1- Nota Normal  2-Nota Complementar 3-Nota de Ajuste    4-Nota de Devolucao',
                `vlr_total_nota` DECIMAL(15,3) NULL DEFAULT NULL,
                `danfe` LONGTEXT NULL DEFAULT NULL,
                `data_emissao` DATE NULL DEFAULT NULL,
                `xml` LONGTEXT NULL DEFAULT NULL,
                `id_transportadora` INT(11) NULL DEFAULT NULL,
                `modalidade_frete` INT(1) NULL DEFAULT '9' COMMENT '0-Emitente   1-Dest/Rem  2-Terceiros  9-Sem Frete',
                `qtd_volume` INT(3) NULL DEFAULT NULL,
                `descricao_volume` VARCHAR(10) NULL DEFAULT NULL,
                `vr_desconto` DECIMAL(12,2) NULL DEFAULT NULL,
                `ndi` VARCHAR(200) NULL DEFAULT NULL,
                `datadi` DATE NULL DEFAULT NULL,
                `datadesembaraco` DATE NULL DEFAULT NULL,
                `localdesembaraco` VARCHAR(255) NULL DEFAULT NULL,
                `transporte` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_ENTRADA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_entrada_estoque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_entrada_estoque`
            (
                `id_nf_entrada_estoque` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_fornecedor` INT(11) NULL DEFAULT NULL,
                `id_usuario` VARCHAR(45) NULL DEFAULT NULL,
                `num_danfe` VARCHAR(70) NULL DEFAULT NULL,
                `num_nf` VARCHAR(50) NULL DEFAULT NULL,
                `num_serie` VARCHAR(20) NULL DEFAULT NULL,
                `data_emissao` DATETIME NULL DEFAULT NULL,
                `data_saida` DATETIME NULL DEFAULT NULL,
                `vlr_total_nf` DECIMAL(20,4) NULL DEFAULT NULL,
                `num_processo` INT(11) NULL DEFAULT NULL,
                `tp_emissao` INT(11) NULL DEFAULT NULL,
                `ind_finalidade` INT(11) NULL DEFAULT NULL,
                `tp_operacao` INT(11) NULL DEFAULT NULL,
                `forma_pagamento` INT(11) NULL DEFAULT NULL,
                `base_calc_icms` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_icms` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_icms_desonerado` DECIMAL(20,4) NULL DEFAULT NULL,
                `base_calculo_icms` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_icms_substituicao` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_total_prod` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_frete` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_seguro` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_outros` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_ipi` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_nf` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_descontos` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_total_ii` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_pis` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_cofins` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_aprox_tributo` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_total_icms_fcp` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_uf_destino` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_uf_remitente` DECIMAL(20,4) NULL DEFAULT NULL,
                `mod_frete` INT(11) NULL DEFAULT NULL,
                `frete_cnpj` VARCHAR(30) NULL DEFAULT NULL,
                `frete_razao_social` VARCHAR(255) NULL DEFAULT NULL,
                `frete_insc_estadual` VARCHAR(45) NULL DEFAULT NULL,
                `frete_endereco` VARCHAR(255) NULL DEFAULT NULL,
                `frete_cod_municipio` VARCHAR(45) NULL DEFAULT NULL,
                `frete_uf` CHAR(2) NULL DEFAULT NULL,
                PRIMARY KEY (`id_nf_entrada_estoque`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_ENTRADA_ESTOQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_entrada_estoque_itens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_entrada_estoque_itens`
            (
                `id_nf_entrada_estoque_item` INT(11) NOT NULL AUTO_INCREMENT,
                `id_nf_entrada_estoque` INT(11) NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `qtd` DECIMAL(20,4) NULL DEFAULT NULL,
                `und_comercial` VARCHAR(20) NULL DEFAULT NULL,
                `valor_item` DECIMAL(20,4) NULL DEFAULT NULL,
                `cod_ncm` VARCHAR(50) NULL DEFAULT NULL,
                `cod_cest` VARCHAR(50) NULL DEFAULT NULL,
                `cod_ext_tipi` VARCHAR(45) NULL DEFAULT NULL,
                `cfop` VARCHAR(45) NULL DEFAULT NULL,
                `valor_outros` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_desconto` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_total_frete` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_seguro` DECIMAL(20,4) NULL DEFAULT NULL,
                `cod_ean_c` VARCHAR(45) NULL DEFAULT NULL,
                `cod_ean_t` VARCHAR(45) NULL DEFAULT NULL,
                `und_tributavel` VARCHAR(45) NULL DEFAULT NULL,
                `qtd_tributavel` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_unit_c` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_unit_t` DECIMAL(20,4) NULL DEFAULT NULL,
                `vlr_aprox_t` DECIMAL(20,4) NULL DEFAULT NULL,
                `num_fci` VARCHAR(45) NULL DEFAULT NULL,
                `orig_mercadoria` VARCHAR(20) NULL DEFAULT NULL,
                `tributo_icms` VARCHAR(20) NULL DEFAULT NULL,
                `valor_bc_icms_st_retido` DECIMAL(20,4) NULL DEFAULT NULL,
                `valor_icms_st_retido` DECIMAL(20,4) NULL DEFAULT NULL,
                `pis_cst` VARCHAR(20) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(100) NULL DEFAULT NULL,
                `fator` VARCHAR(20) NULL DEFAULT NULL,
                PRIMARY KEY (`id_nf_entrada_estoque_item`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_ENTRADA_ESTOQUE_ITENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_entrada_itens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_entrada_itens`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL DEFAULT '0',
                `id_nota_entrada` INT(11) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(20) NOT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `qtd` DECIMAL(10,3) NULL DEFAULT NULL,
                `vr_unit` DECIMAL(20,10) NULL DEFAULT NULL,
                `cfop` VARCHAR(5) NOT NULL,
                `icms` INT(11) NOT NULL,
                `pis` INT(11) NOT NULL,
                `cofins` INT(11) NOT NULL,
                `ipi` INT(2) NULL DEFAULT NULL,
                `vr_bc_icms` DECIMAL(12,4) NULL DEFAULT NULL,
                `vr_bc_ipi` DECIMAL(12,4) NULL DEFAULT NULL,
                `vr_bc_pis` DECIMAL(12,4) NULL DEFAULT NULL,
                `vr_bc_cofins` DECIMAL(12,4) NULL DEFAULT NULL,
                `vr_import` DECIMAL(10,4) NULL DEFAULT '0.0000',
                `vr_bc_import` DECIMAL(10,4) NULL DEFAULT '0.0000',
                `vr_afrmm` DECIMAL(10,4) NULL DEFAULT '0.0000',
                `vr_bc_afrmm` DECIMAL(10,4) NULL DEFAULT '0.0000',
                `produto_descricao` VARCHAR(255) NULL DEFAULT NULL,
                `vr_outras_despesas` DECIMAL(12,4) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_ENTRADA_ITENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_entrada_xml()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_entrada_xml`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `data_hora_importacao` DATETIME NOT NULL,
                `numero_nota` INT(11) NULL DEFAULT NULL,
                `arquivo_xml` LONGTEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_ENTRADA_XML criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_inutilizadas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_inutilizadas`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `num_nota` INT(11) NOT NULL,
                `tipo_nota` ENUM('NFE', 'NFC', 'NFS') NULL DEFAULT NULL,
                `data_hora_inutilizacao` DATETIME NOT NULL,
                `protocolo` VARCHAR(255) NULL DEFAULT NULL,
                `motivo_inutilizacao` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_INUTILIZADAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_municipio_RF()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_municipio_RF`
            (
                `id` BIGINT(1) NOT NULL AUTO_INCREMENT,
                `codigo` INT(4) UNSIGNED ZEROFILL NOT NULL DEFAULT '0000',
                `cidade` VARCHAR(57) NOT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_MUNICIPIO_RF criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_natureza()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_natureza`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `tipo` ENUM('DF') NULL DEFAULT NULL,
                `Valor` INT(11) NULL DEFAULT '0',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_NATUREZA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_paises()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_paises`
            (
                `codigo` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(60) NOT NULL,
                PRIMARY KEY (`codigo`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_PAISES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_servico_assinadas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_servico_assinadas`
            (
                `ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `LOTE` INT(11) NOT NULL,
                `QTDE` INT(11) NOT NULL,
                `XML` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL,
                `ARQUIVO` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL,
                `SITUACAO` ENUM('A', 'C') CHARACTER SET 'utf8' NOT NULL DEFAULT 'A' COMMENT 'A - Nota OK   -  C - Nota Cancelada',
                `EMPRESA` VARCHAR(50) NULL DEFAULT NULL,
                `RETORNO` TEXT NULL DEFAULT NULL,
                `NUMERO_PEDIDO` BIGINT(20) NULL DEFAULT NULL,
                `LINK_NFS` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`ID`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_SERVICO_ASSINADAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_tributos_itens_COFINS()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_tributos_itens_COFINS`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_nf_devolucao_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_entrada_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_venda_itens` INT(11) NOT NULL DEFAULT '0',
                `CST` INT(2) UNSIGNED ZEROFILL NULL DEFAULT '00',
                `pCOFINS` DECIMAL(10,3) NULL DEFAULT NULL,
                `qBCProd` DOUBLE NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_calculo` ENUM('P', 'V') NULL DEFAULT 'P',
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `p_aliq` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_TRIBUTOS_COFINS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_tributos_itens_COFINSST()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_tributos_itens_COFINSST`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_nf_devolucao_itens` INT(11) NOT NULL,
                `id_nf_entrada_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_venda_itens` INT(11) NOT NULL DEFAULT '0',
                `imposto_id` INT(11) NULL DEFAULT NULL,
                `pCOFINS` DECIMAL(10,2) NULL DEFAULT NULL,
                `qBCProd` DECIMAL(10,2) NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_calculo` ENUM('P', 'V') NULL DEFAULT 'P',
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_TRIBUTOS_ITENS_COFINSST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_tributos_itens_ICMS()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_tributos_itens_ICMS`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_nf_devolucao_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_entrada_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_venda_itens` INT(11) NOT NULL DEFAULT '0',
                `orig` CHAR(1) NULL DEFAULT NULL,
                `CST` CHAR(3) NULL DEFAULT NULL,
                `modBC` CHAR(1) NULL DEFAULT NULL,
                `pRedBC` DECIMAL(10,3) NULL DEFAULT NULL,
                `pICMS` DECIMAL(10,2) NULL DEFAULT NULL,
                `modBCST` CHAR(1) NULL DEFAULT NULL,
                `pMVAST` DECIMAL(10,3) NULL DEFAULT NULL,
                `pRedBCST` DECIMAL(10,3) NULL DEFAULT NULL,
                `pICMSST` DECIMAL(10,2) NULL DEFAULT NULL,
                `regimes` ENUM('T', 'S') NULL DEFAULT 'T',
                `pOpePropria` DECIMAL(10,3) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `vl_aliq_calc_cre` DECIMAL(10,3) NULL DEFAULT NULL,
                `bc_icms_st_ret_ant` DECIMAL(10,3) NULL DEFAULT NULL,
                `pMVAST_LR` DECIMAL(10,3) NULL DEFAULT NULL,
                `valor_desoneracao_icms` DECIMAL(10,2) NULL DEFAULT NULL,
                `motivo_desoneracao_icms` VARCHAR(60) NULL DEFAULT NULL,
                `percentual_diferimento_icms` DECIMAL(10,2) NULL DEFAULT NULL,
                `uf_retido_remetente_icms_st` CHAR(2) NULL DEFAULT NULL,
                `uf_destino_icms_st` CHAR(2) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_origem` VARCHAR(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_TRIBUTOS_ITENS_ICMS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_tributos_itens_II()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_tributos_itens_II`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_nf_devolucao_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_entrada_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_venda_itens` INT(11) NOT NULL DEFAULT '0',
                `vBC` DOUBLE NULL DEFAULT NULL,
                `vDespAdu` DOUBLE NULL DEFAULT NULL,
                `vII` DOUBLE NULL DEFAULT NULL,
                `vIOF` DOUBLE NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_TRIBUTOS_ITENS_II criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_tributos_itens_IPI()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_tributos_itens_IPI`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_nf_devolucao_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_entrada_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_venda_itens` INT(11) NOT NULL DEFAULT '0',
                `cIEnq` CHAR(5) NULL DEFAULT NULL,
                `CNPJProd` CHAR(14) NULL DEFAULT NULL,
                `cSelo` VARCHAR(60) NULL DEFAULT NULL,
                `qSelo` DOUBLE NULL DEFAULT NULL,
                `cEnq` CHAR(3) NULL DEFAULT NULL,
                `CST` INT(2) UNSIGNED ZEROFILL NULL DEFAULT '00',
                `qUnid` DOUBLE NULL DEFAULT NULL,
                `pIPI` DOUBLE NULL DEFAULT NULL,
                `tp_calculo` ENUM('P', 'V') NULL DEFAULT 'P',
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_TRIBUTOS_ITENS_IPI criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_tributos_itens_PIS()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_tributos_itens_PIS`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_nf_devolucao_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_entrada_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_venda_itens` INT(11) NOT NULL DEFAULT '0',
                `tp_calculo` CHAR(1) NULL DEFAULT 'N' COMMENT 'N - Nulo    P - Percentual  V-Valores',
                `CST` INT(2) UNSIGNED ZEROFILL NULL DEFAULT '00',
                `pPIS` DECIMAL(10,2) NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_TRIBUTOS_ITENS_PIS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_tributos_itens_PISST()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_tributos_itens_PISST`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_nf_devolucao_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_entrada_itens` INT(11) NOT NULL DEFAULT '0',
                `id_nf_venda_itens` INT(11) NOT NULL DEFAULT '0',
                `tp_calculo` CHAR(1) NULL DEFAULT 'N' COMMENT 'N - Nulo    P - Percentual  V-Valores',
                `pPIS` DOUBLE NULL DEFAULT NULL,
                `qBCProd` DOUBLE NULL DEFAULT NULL,
                `vAliqProd` DOUBLE NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_TRIBUTOS_ITENS_PISST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nf_tributos_venda()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nf_tributos_venda`
            (
                `id` BIGINT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `data` DATE NOT NULL,
                `hora` TIME NOT NULL,
                `id_fornecedor` INT(11) NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `id_natureza` INT(2) NULL DEFAULT NULL,
                `id_transportadora` INT(11) NULL DEFAULT NULL,
                `modalidade_frete` INT(1) NULL DEFAULT NULL,
                `info_adicionais` LONGTEXT NULL DEFAULT NULL,
                `danfe_saida` LONGTEXT NULL DEFAULT NULL,
                `num_nota_saida` INT(10) NOT NULL,
                `finalizada` ENUM('S', 'N') NULL DEFAULT 'N',
                `danfe_entrada` LONGTEXT NULL DEFAULT NULL,
                `outras_despesas` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `tipo_nota` INT(1) NULL DEFAULT '0' COMMENT '0 - Entrada   1 - Saida',
                `id_venda` INT(11) NULL DEFAULT NULL,
                `status` ENUM('A', 'S', 'C', 'R', 'D') NULL DEFAULT 'A' COMMENT 'A - Aguardando, S - Nota Gerada com Sucesso , C - Nota Cancelada - R -Nota Rejeitada, D - Denegada',
                `finalidade` ENUM('D', 'S', 'E', 'V') NULL DEFAULT 'V' COMMENT 'Devolucao - Saida - Entrada - Venda',
                `natureza` VARCHAR(255) NULL DEFAULT NULL,
                `qtd_volume` INT(3) NULL DEFAULT '1',
                `descricao_volume` VARCHAR(10) NULL DEFAULT 'BOX',
                `numero_nota_sefaz` INT(11) NULL DEFAULT NULL,
                `vlr_base_calc_st` DECIMAL(13,3) NULL DEFAULT NULL,
                `vlr_icms_st` DECIMAL(13,3) NULL DEFAULT NULL,
                `peso_bruto` DECIMAL(12,3) NULL DEFAULT NULL,
                `peso_liquido` DECIMAL(12,3) NULL DEFAULT NULL,
                `valor_desconto` DECIMAL(12,2) NULL DEFAULT NULL,
                `tipo_finalidade` ENUM('1', '2', '3', '4') NULL DEFAULT NULL COMMENT '1- NOTA NORMAL 2- NOTA COMPLEMENTAR 3- NOTA DE AJUSTE 4- NOTA DE DEVOLUÇÃO',
                `ambiente` INT(1) UNSIGNED NULL DEFAULT '1' COMMENT '1 - Producao 2 - Homologacao',
                `valor_frete` DECIMAL(15,3) NULL DEFAULT NULL,
                `data_emissao` DATE NULL DEFAULT NULL,
                `xml` LONGTEXT NULL DEFAULT NULL,
                `transportadora` VARCHAR(255) NULL DEFAULT NULL,
                `placa` VARCHAR(20) NULL DEFAULT NULL,
                `cod_antt` VARCHAR(70) NULL DEFAULT NULL,
                `id_placa` INT(11) NULL DEFAULT NULL,
                `numeracao` VARCHAR(70) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NF_TRIBUTOS_VENDA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_cupom_fiscal()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_cupom_fiscal`
            (
                `id_cupom_fiscal` INT(11) NOT NULL AUTO_INCREMENT,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `id_cfop` INT(11) NULL DEFAULT NULL,
                `ncm` VARCHAR(8) NULL DEFAULT NULL,
                `sped` VARCHAR(5) NULL DEFAULT NULL,
                `percentual_issqn` DECIMAL(10,2) NULL DEFAULT NULL,
                `cst` INT(11) NULL DEFAULT NULL,
                `codigo_balanca` VARCHAR(255) NULL DEFAULT NULL,
                `percentual_icms` DECIMAL(10,2) NULL DEFAULT NULL,
                `percentual_pis` DECIMAL(10,2) NULL DEFAULT NULL,
                `csosn` VARCHAR(60) NULL DEFAULT NULL,
                `totalizador_parcial` VARCHAR(10) NULL DEFAULT NULL,
                `percentual_ipi` DECIMAL(10,2) NULL DEFAULT NULL,
                `percentual_cofins` DECIMAL(10,2) NULL DEFAULT NULL,
                `icmsst` VARCHAR(60) NULL DEFAULT NULL,
                `situacao_tributaria` CHAR(1) NULL DEFAULT NULL,
                `iat` VARCHAR(20) NULL DEFAULT NULL,
                `ippt` VARCHAR(10) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_cupom_fiscal`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_CUPOM_FISCAL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_exigibilidade()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_exigibilidade`
            (
                `id_exigibilidade` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(60) NULL DEFAULT NULL,
                `dt_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_exigibilidade`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_EXIGIBILIDADE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_icms_interestaduais()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_icms_interestaduais`
            (
                `uf_destino` CHAR(2) NOT NULL,
                `AC` INT(2) NOT NULL,
                `AL` INT(2) NOT NULL,
                `AM` INT(2) NOT NULL,
                `AP` INT(2) NOT NULL,
                `BA` INT(2) NOT NULL,
                `CE` INT(2) NOT NULL,
                `DF` INT(2) NOT NULL,
                `ES` INT(2) NOT NULL,
                `GO` INT(2) NOT NULL,
                `MA` INT(2) NOT NULL,
                `MT` INT(2) NOT NULL,
                `MS` INT(2) NOT NULL,
                `MG` INT(2) NOT NULL,
                `PA` INT(2) NOT NULL,
                `PB` INT(2) NOT NULL,
                `PR` INT(2) NOT NULL,
                `PE` INT(2) NOT NULL,
                `PI` INT(2) NOT NULL,
                `RN` INT(2) NOT NULL,
                `RS` INT(2) NOT NULL,
                `RJ` INT(2) NOT NULL,
                `RO` DECIMAL(10,2) NULL DEFAULT NULL,
                `RR` INT(2) NOT NULL,
                `SC` INT(2) NOT NULL,
                `SP` INT(2) NOT NULL,
                `SE` INT(2) NOT NULL,
                `TOC` INT(2) NOT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_ICMS_INTERESTADUAIS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_icms_interestaduais_cliente()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_icms_interestaduais_cliente`
            (
                `id_cadastro` INT(11) NOT NULL,
                `uf` CHAR(2) NOT NULL,
                `percentual` DECIMAL(12,3) NOT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_ICMS_INTERESTADUAIS_CLIENTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_icms_situacao_tributaria()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_icms_situacao_tributaria`
            (
                `id` INT(3) NOT NULL AUTO_INCREMENT,
                `codigo` INT(3) NULL DEFAULT NULL,
                `descricao` VARCHAR(200) NULL DEFAULT NULL,
                `regime` ENUM('S', 'T') NULL DEFAULT NULL COMMENT 'T - Tributacao Normal   S - Simples Nacional',
                `valor` INT(3) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_ICMS_SITUACAO_TRIBUTARIA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_lista_servico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_lista_servico`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` TEXT NULL DEFAULT NULL,
                `codigo` INT(4) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_LISTA_SERVICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_modalidade()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_modalidade`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `tipo` ENUM('1', '2', '3') NULL DEFAULT NULL,
                `valor` INT(1) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_MODALIDADE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_motivo_desoneracao_icms()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_motivo_desoneracao_icms`
            (
                `id_motivo_desoneracao` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(60) NULL DEFAULT NULL,
                `dt_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_motivo_desoneracao`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_MOTIVO_DESONERACAO_ICMS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_municipio()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_municipio`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_estado` INT(10) NULL DEFAULT '0',
                `sigla` VARCHAR(5) NULL DEFAULT '',
                `descricao` VARCHAR(100) NULL DEFAULT '',
                `url_endereco` TEXT NULL DEFAULT NULL,
                `url_endereco_hom` TEXT NULL DEFAULT NULL,
                `url_endereco_prod` TEXT NULL DEFAULT NULL,
                `padrao_xml` VARCHAR(15) NULL DEFAULT NULL,
                `habilitado_emissao_nota` INT(1) NULL DEFAULT '0',
                `arquivo_xml_php` VARCHAR(40) NULL DEFAULT NULL,
                `msg_observacao_nfs` LONGTEXT NULL DEFAULT NULL,
                `msg_info_interno` LONGTEXT NULL DEFAULT NULL,
                `msg_info_intermunicipal` LONGTEXT NULL DEFAULT NULL,
                `class_php` VARCHAR(50) NULL DEFAULT NULL,
                `class_php_new` VARCHAR(50) NULL DEFAULT NULL,
                `entidade_equiplano` INT(3) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_MUNICIPIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_mvat()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_mvat`
            (
                `id` BIGINT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `uf_destino` CHAR(2) NOT NULL,
                `mvat_sn` DECIMAL(12,3) NOT NULL,
                `mvat_lr` DECIMAL(12,3) NOT NULL,
                `pICMS_Interno` DECIMAL(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_MVAT criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_origem()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_origem`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `codigo` INT(2) NULL DEFAULT NULL,
                `descricao` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_ORIGEM criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_paises()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_paises`
            (
                `codigo` BIGINT(11) NOT NULL DEFAULT '0',
                `nome` VARCHAR(60) NOT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_PAISES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_produto_COFINS()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_Produto_COFINS`
            (
                `id_produto` INT(11) NOT NULL DEFAULT '0',
                `p_aliq` DECIMAL(10,3) NOT NULL,
                `CST` INT(2) UNSIGNED ZEROFILL NULL DEFAULT '00',
                `pCOFINS` DECIMAL(10,3) NULL DEFAULT NULL,
                `qBCProd` DOUBLE NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_calculo` ENUM('P', 'V') NULL DEFAULT 'P',
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_PRODUTO_COFINS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_produto_COFINSST()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_Produto_COFINSST`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `imposto_id` INT(11) NULL DEFAULT NULL,
                `pCOFINS` DECIMAL(10,2) NULL DEFAULT NULL,
                `qBCProd` DECIMAL(10,2) NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_calculo` ENUM('P', 'V') NULL DEFAULT 'P',
                `produto_id` INT(11) UNSIGNED NULL DEFAULT '0',
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_PRODUTO_COFINSST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_produto_ICMS()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_Produto_ICMS`
            (
                `id_produto` INT(11) NOT NULL DEFAULT '0',
                `orig` CHAR(1) NULL DEFAULT NULL,
                `CST` CHAR(3) NULL DEFAULT NULL,
                `modBC` CHAR(1) NULL DEFAULT NULL,
                `pRedBC` DECIMAL(10,3) NULL DEFAULT NULL,
                `pICMS` DECIMAL(10,4) NULL DEFAULT NULL,
                `modBCST` CHAR(1) NULL DEFAULT NULL,
                `pMVAST` DECIMAL(10,3) NULL DEFAULT NULL,
                `pRedBCST` DECIMAL(10,3) NULL DEFAULT NULL,
                `pICMSST` DECIMAL(10,2) NULL DEFAULT NULL,
                `regimes` ENUM('T', 'S') NULL DEFAULT 'T',
                `pOpePropria` DECIMAL(10,3) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `vl_aliq_calc_cre` DECIMAL(10,3) NULL DEFAULT NULL,
                `bc_icms_st_ret_ant` DECIMAL(10,3) NULL DEFAULT NULL,
                `pMVAST_LR` DECIMAL(10,3) NULL DEFAULT NULL,
                `valor_desoneracao_icms` DECIMAL(10,2) NULL DEFAULT NULL,
                `motivo_desoneracao_icms` VARCHAR(60) NULL DEFAULT NULL,
                `percentual_diferimento_icms` DECIMAL(10,2) NULL DEFAULT NULL,
                `uf_retido_remetente_icms_st` CHAR(2) NULL DEFAULT NULL,
                `uf_destino_icms_st` CHAR(2) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_PRODUTO_ICMS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_produto_II()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_Produto_II`
            (
                `id_produto` INT(11) NOT NULL DEFAULT '0',
                `vBC` DOUBLE NULL DEFAULT NULL,
                `vDespAdu` DOUBLE NULL DEFAULT NULL,
                `vII` DOUBLE NULL DEFAULT NULL,
                `vIOF` DOUBLE NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_PRODUTO_II criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_produto_IPI()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_Produto_IPI`
            (
                `id_produto` INT(11) NOT NULL DEFAULT '0',
                `cIEnq` CHAR(5) NULL DEFAULT NULL,
                `CNPJProd` CHAR(14) NULL DEFAULT NULL,
                `cSelo` VARCHAR(60) NULL DEFAULT NULL,
                `qSelo` DOUBLE NULL DEFAULT NULL,
                `cEnq` CHAR(3) NULL DEFAULT NULL,
                `CST` INT(2) UNSIGNED ZEROFILL NULL DEFAULT '00',
                `qUnid` DOUBLE NULL DEFAULT NULL,
                `pIPI` DOUBLE NULL DEFAULT NULL,
                `tp_calculo` ENUM('P', 'V') NULL DEFAULT 'P',
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_PRODUTO_IPI criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_produto_ISSQN()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_Produto_ISSQN`
            (
                `ISSQN_id` INT(11) NOT NULL AUTO_INCREMENT,
                `imposto_id` INT(11) NULL DEFAULT NULL,
                `pAliq` DOUBLE(4,2) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `cMunFG` CHAR(7) NULL DEFAULT NULL,
                `cListServ` VARCHAR(4) NULL DEFAULT NULL,
                `tributacao` ENUM('N', 'R', 'S', 'I') NULL DEFAULT NULL,
                `produto_id` INT(11) NULL DEFAULT NULL,
                `id_exigibilidade` INT(11) NULL DEFAULT NULL,
                `incentivo_fiscal` ENUM('S', 'N') NULL DEFAULT 'S',
                `valor_deducoes` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_outras_retencoes` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_desconto_condicionado` DECIMAL(10,2) NULL DEFAULT NULL,
                `valor_retencao` DECIMAL(10,2) NULL DEFAULT NULL,
                `codigo_servico` VARCHAR(5) NULL DEFAULT NULL,
                `uf_incidencia` CHAR(2) NULL DEFAULT NULL,
                `id_municipio_incidencia` INT(11) NULL DEFAULT NULL,
                `processo` VARCHAR(60) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`ISSQN_id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_PRODUTO_ISSQN criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_produto_Opcoes()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_Produto_Opcoes`
            (
                `id_produto` INT(11) NULL DEFAULT NULL,
                `tributacao_lucro` ENUM('S', 'N') NULL DEFAULT 'N')
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_PRODUTO_Opcoes criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_produto_PIS()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_Produto_PIS`
            (
                `id_produto` INT(11) NOT NULL DEFAULT '0',
                `tp_calculo` CHAR(1) NULL DEFAULT 'N' COMMENT 'N - Nulo    P - Percentual  V-Valores',
                `CST` INT(2) UNSIGNED ZEROFILL NULL DEFAULT '00',
                `pPIS` DECIMAL(10,2) NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_PRODUTO_PIS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_produto_PISST()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_Produto_PISST`
            (
                `id_produto` INT(11) NOT NULL DEFAULT '0',
                `tp_calculo` CHAR(1) NULL DEFAULT 'N' COMMENT 'N - Nulo    P - Percentual  V-Valores',
                `pPIS` DOUBLE NULL DEFAULT NULL,
                `qBCProd` DOUBLE NULL DEFAULT NULL,
                `vAliqProd` DOUBLE NULL DEFAULT NULL,
                `v_aliq` DECIMAL(10,2) NULL DEFAULT NULL,
                `tp_imposto` ENUM('A', 'B') NULL DEFAULT 'A',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_PRODUTO_PISST criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_situacao_tributaria()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_situacao_tributaria`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `codigo` INT(3) NULL DEFAULT NULL,
                `descricao` VARCHAR(150) NULL DEFAULT NULL,
                `tipo` VARCHAR(6) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_SITUACAO_TRIBUTARIA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_tipo_especifico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_tipo_especifico`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `situacao` ENUM('0', '1') NULL DEFAULT '1',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_TIPO_ESPECIFICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_transportadora()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_transportadora`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_modalidade_frete` INT(2) NULL DEFAULT NULL,
                `id_transportadora` INT(11) NULL DEFAULT NULL,
                `quantidade_volumes` INT(11) NULL DEFAULT NULL,
                `peso_liquido` DECIMAL(10,2) NULL DEFAULT NULL,
                `peso_bruto` DECIMAL(10,2) NULL DEFAULT NULL,
                `especie` VARCHAR(60) NULL DEFAULT NULL,
                `id_nfe` BIGINT(20) NULL DEFAULT NULL,
                `lacre` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_TRANSPORTADORA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfe_uf()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfe_uf`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `sigla` CHAR(2) NULL DEFAULT NULL,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `CONTINGENCIA` ENUM('S', 'N') NULL DEFAULT 'N',
                `FCP_percentual` DECIMAL(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_UF criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfs_server()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfs_server`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `cidade` VARCHAR(60) NOT NULL,
                `uf` CHAR(2) NOT NULL,
                `endereco_xsd` VARCHAR(50) NOT NULL,
                `endereco_producao` VARCHAR(200) NOT NULL DEFAULT '',
                `endereco_homologacao` VARCHAR(200) NOT NULL DEFAULT '',
                `xml_assinado` ENUM('S', 'N') NULL DEFAULT 'S',
                `xml_padrao` VARCHAR(15) NULL DEFAULT NULL,
                `informacao` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFE_SERVER criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfs_situacao_tributaria()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfs_situacao_tributaria`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `padrao` VARCHAR(10) NULL DEFAULT NULL,
                `codigo_st` INT(2) NULL DEFAULT NULL,
                `descricao` LONGTEXT NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFS_SITUACAO_TRIBUTARIA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nfse_erros()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nfse_erros`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `idCidade` INT(11) NULL DEFAULT NULL,
                `codigo` VARCHAR(10) NULL DEFAULT NULL,
                `mensagem` LONGTEXT NULL DEFAULT NULL,
                `correcao` LONGTEXT NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NFS_ERROS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nota_credito()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nota_credito`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_venda_origem` INT(11) NOT NULL,
                `id_venda_devol` INT(11) NULL DEFAULT NULL,
                `id_cliente` INT(11) NOT NULL,
                `id_cadastro` INT(11) NOT NULL,
                `data_cadastro` DATE NOT NULL,
                `hora_cadastro` TIME NOT NULL,
                `valor_credito` DECIMAL(12,2) NOT NULL,
                `id_func_cadastro` INT(11) NOT NULL,
                `id_venda_resgate` INT(11) NOT NULL,
                `data_resgate` DATE NOT NULL,
                `hora_resgate` TIME NOT NULL,
                `valor_resgate` DECIMAL(12,2) NOT NULL,
                `id_func_resgate` INT(11) NOT NULL,
                `justificativa` VARCHAR(100) NOT NULL,
                `status` ENUM('A', 'E') NOT NULL DEFAULT 'A' COMMENT 'A = Ativo, E = Excluida',
                `id_usuario_exclusao` INT(11) NOT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NOTA_CREDITO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nota_credito_historico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nota_credito_historico`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL DEFAULT '0',
                `idnota_credito` BIGINT(20) NOT NULL DEFAULT '0',
                `data_hora` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `id_venda` INT(11) NOT NULL,
                `valor_inicial` DECIMAL(12,2) NOT NULL,
                `valor_abatido` DECIMAL(12,2) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NOTA_CREDITO_HISTORICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nota_fiscal()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nota_fiscal`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `numero_nota` VARCHAR(50) NULL DEFAULT NULL,
                `serie` VARCHAR(20) NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `hora_cadastro` TIME NULL DEFAULT NULL,
                `id_produto` INT(10) UNSIGNED NULL DEFAULT NULL,
                `qtd_produto` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `id_fornecedor` INT(10) UNSIGNED NULL DEFAULT NULL,
                `vlr_unitario` DECIMAL(16,6) UNSIGNED NULL DEFAULT NULL,
                `vlr_venda` DECIMAL(10,2) NULL DEFAULT NULL,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(30) NULL DEFAULT NULL,
                `fator` INT(3) NOT NULL DEFAULT '1',
                `ncm` VARCHAR(10) NULL DEFAULT NULL,
                `picms` DECIMAL(5,2) NULL DEFAULT NULL,
                `vr_ipi` DECIMAL(12,2) NULL DEFAULT NULL,
                `vr_pis` DECIMAL(12,2) NULL DEFAULT NULL,
                `vr_cofins` DECIMAL(12,2) NULL DEFAULT NULL,
                `vlr_total` DECIMAL(15,2) NULL DEFAULT NULL,
                `vlr_total_prod` DECIMAL(15,2) NULL DEFAULT NULL,
                `status` ENUM('A', 'E') NOT NULL DEFAULT 'A' COMMENT 'A - Ativo, E - Excluido',
                `cest` VARCHAR(8) NULL DEFAULT NULL,
                `codigo_anp` VARCHAR(50) NULL DEFAULT NULL,
                `codigo_barra_ean` VARCHAR(50) NULL DEFAULT NULL,
                `margem_lucro` DECIMAL(10,2) NULL DEFAULT NULL,
                `vr_icmsst` DECIMAL(10,2) NULL DEFAULT NULL,
                `nf_data_emissao` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NOTA_FISCAL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_nota_fiscal_tmp()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `nota_fiscal_tmp`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `numero_nota` VARCHAR(50) NULL DEFAULT NULL,
                `serie` VARCHAR(20) NULL DEFAULT NULL,
                `data_cadastro` DATE NULL DEFAULT NULL,
                `hora_cadastro` TIME NULL DEFAULT NULL,
                `id_produto` INT(10) UNSIGNED NULL DEFAULT NULL,
                `qtd_produto` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `id_fornecedor` INT(10) UNSIGNED NULL DEFAULT NULL,
                `vlr_unitario` DECIMAL(16,4) NULL DEFAULT NULL,
                `vlr_venda` DECIMAL(16,4) NULL DEFAULT NULL,
                `vlr_total` DECIMAL(12,2) NULL DEFAULT NULL,
                `vlr_nota` DECIMAL(12,2) NULL DEFAULT NULL,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(30) NULL DEFAULT NULL,
                `nome` VARCHAR(60) NULL DEFAULT NULL,
                `tipo` VARCHAR(5) NULL DEFAULT NULL,
                `fator` INT(3) NULL DEFAULT NULL,
                `ncm` VARCHAR(10) NULL DEFAULT NULL,
                `nfci` VARCHAR(60) NULL DEFAULT NULL,
                `infAdProd` VARCHAR(255) NULL DEFAULT NULL,
                `id_origem` INT(1) NULL DEFAULT NULL,
                `picms` DECIMAL(5,2) NULL DEFAULT NULL,
                `vr_ipi` DECIMAL(12,2) NULL DEFAULT NULL,
                `vr_pis` DECIMAL(12,2) NULL DEFAULT NULL,
                `vr_cofins` DECIMAL(12,2) NULL DEFAULT NULL,
                `vr_total` DECIMAL(12,2) NULL DEFAULT NULL,
                `id_pro_parametro_valor` INT(11) NULL DEFAULT NULL,
                `grade` VARCHAR(400) NULL DEFAULT '0',
                `cest` VARCHAR(8) NULL DEFAULT NULL,
                `codigo_anp` VARCHAR(50) NULL DEFAULT NULL,
                `codigo_barra_ean` VARCHAR(50) NULL DEFAULT NULL,
                `margem_lucro` DECIMAL(10,3) NULL DEFAULT NULL,
                `vr_icmsst` DECIMAL(10,2) NULL DEFAULT NULL,
                `vlr_atacado` DECIMAL(16,4) NULL DEFAULT NULL,
                `id_classificacao` INT(11) NULL DEFAULT NULL,
                `fracionar_qtditens` DECIMAL(16,3) NULL DEFAULT NULL,
                `nf_data_emissao` DATETIME NULL DEFAULT NULL,
                `tipo_calculo` ENUM('P', 'R', 'F') NOT NULL DEFAULT 'P',
                `id_unidade` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NOTA_FISCAL_TMP criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_notas_promissorias()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `notas_promissorias`
            (
                `promissoria_id` INT(11) NOT NULL AUTO_INCREMENT,
                `promissoria_chave` VARCHAR(10) NOT NULL DEFAULT '0',
                `codloja` INT(11) NULL DEFAULT NULL,
                `debito_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `parcela_quantidade` SMALLINT(4) NULL DEFAULT NULL,
                `parcela_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `parcela_data` DATE NULL DEFAULT NULL,
                `emissao_data` DATE NULL DEFAULT NULL,
                `consumidor_cpf` VARCHAR(14) NULL DEFAULT NULL,
                `consumidor_nome` VARCHAR(100) NULL DEFAULT NULL,
                `consumidor_end_tipo` INT(11) NULL DEFAULT NULL,
                `consumidor_end_logradouro` VARCHAR(100) NULL DEFAULT NULL,
                `consumidor_end_numero` INT(11) NULL DEFAULT NULL,
                `consumidor_end_complemento` VARCHAR(50) NULL DEFAULT NULL,
                `consumidor_end_bairro` VARCHAR(50) NULL DEFAULT NULL,
                `consumidor_end_cidade` VARCHAR(50) NULL DEFAULT NULL,
                `consumidor_end_uf` VARCHAR(2) NULL DEFAULT NULL,
                `consumidor_end_cep` VARCHAR(10) NULL DEFAULT NULL,
                `consumidor_tel_residencial` VARCHAR(20) NULL DEFAULT NULL,
                PRIMARY KEY (`promissoria_id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela NOTAS_PROMISSORIAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_oauth_clients()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `oauth_clients`
            (
                `client_id` INT(11) NOT NULL,
                `client_secret` VARCHAR(256) NOT NULL,
                `redirect_uri` VARCHAR(256) NOT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela OAUTHS_CLIENTS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_oauth_tokens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `oauth_tokens`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `access_token` VARCHAR(256) NOT NULL,
                `access_token_expires_on` TIMESTAMP NOT NULL,
                `client_id` VARCHAR(256) NOT NULL,
                `refresh_token` VARCHAR(256) NOT NULL,
                `refresh_token_expires_on` TIMESTAMP NOT NULL,
                `user_id` INT(11) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela OAUTHS_TOKENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_orcamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `orcamento`
            (
                `id` BIGINT(1) NOT NULL AUTO_INCREMENT,
                `cliente` VARCHAR(50) NULL DEFAULT NULL,
                `cpf` VARCHAR(14) NULL DEFAULT NULL,
                `email` VARCHAR(50) NULL DEFAULT NULL,
                `telefone` VARCHAR(10) NULL DEFAULT NULL,
                `celular` VARCHAR(10) NULL DEFAULT NULL,
                `endereco` VARCHAR(100) NULL DEFAULT NULL,
                `observacao` TEXT NULL DEFAULT NULL,
                `data_hora_orcamento` DATETIME NULL DEFAULT NULL,
                `data_validade` DATE NULL DEFAULT NULL,
                `id_cadastro` BIGINT(20) NULL DEFAULT NULL,
                `id_usuario` BIGINT(20) NULL DEFAULT NULL,
                `id_forma_pagamento` INT(11) NULL DEFAULT NULL,
                `status` ENUM('A', 'C') NULL DEFAULT 'A' COMMENT 'Aberto, Concluído',
                `id_cliente` BIGINT(20) NULL DEFAULT NULL,
                `tabela` ENUM('O', 'C') NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ORCAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_orcamento_cliente()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `orcamento_cliente`
            (
                `id` BIGINT(1) NOT NULL AUTO_INCREMENT,
                `cliente` VARCHAR(50) NULL DEFAULT NULL,
                `cpf` VARCHAR(14) NULL DEFAULT NULL,
                `email` VARCHAR(50) NULL DEFAULT NULL,
                `telefone` VARCHAR(10) NULL DEFAULT NULL,
                `celular` VARCHAR(10) NULL DEFAULT NULL,
                `endereco` VARCHAR(100) NULL DEFAULT NULL,
                `id_cadastro` BIGINT(20) NULL DEFAULT NULL,
                `id_usuario` BIGINT(20) NULL DEFAULT NULL,
                `id_tplog` INT(11) NULL DEFAULT NULL,
                `numero` VARCHAR(5) NULL DEFAULT NULL,
                `complemento` VARCHAR(25) NULL DEFAULT NULL,
                `bairro` VARCHAR(40) NULL DEFAULT NULL,
                `cidade` VARCHAR(50) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ORCAMENTO_CLIENTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_orcamento_itens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `orcamento_itens`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_orcamento` BIGINT(20) NULL DEFAULT NULL,
                `qtd` VARCHAR(10) NULL DEFAULT NULL,
                `descricao` VARCHAR(100) NULL DEFAULT NULL,
                `valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `id_unidade` INT(2) NULL DEFAULT '10',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ORCAMENTO_ITENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_ordem_servico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `ordem_servico`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `ativo` TINYINT(4) NULL DEFAULT '0',
                `enviar_torpedo` TINYINT(4) NULL DEFAULT '0',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ORDEM_SERVICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_ordem_servico_itens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `ordem_servico_itens`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `descricao` VARCHAR(255) NULL DEFAULT NULL,
                `ativo` TINYINT(4) NULL DEFAULT '1',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ORDEM_SERVICO_ITENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_ordem_servico_tipo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `ordem_servico_tipo`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(70) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `data_hora_cadastro` DATETIME NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A' COMMENT 'Ativo, Inativo',
                `valor_padrao` DECIMAL(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ORDEM_SERVICO_TIPO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_origem()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `origem`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `situacao` ENUM('A', 'I') NULL DEFAULT 'A',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela ORIGEM criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_pagamento_notas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `Pagamento_notas`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_nota` VARCHAR(255) NULL DEFAULT NULL,
                `tipo_nota` VARCHAR(255) NULL DEFAULT NULL COMMENT 'E = Entrada, D = Devolucao, S = Saida',
                `meio_pagamento` INT(11) NULL DEFAULT NULL,
                `valor_pagamento` DECIMAL(10,2) NULL DEFAULT NULL,
                `forma_pagamento` INT(11) NULL DEFAULT NULL COMMENT '0 = A vista, 1 = A prazo',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PAGAMENTO_NOTAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_pais()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `pais`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(60) NULL DEFAULT NULL,
                `sigla` VARCHAR(10) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PAIS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_parcela()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `parcela`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(30) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PARCELA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_permissao_usuario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `permissao_usuario`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_modulo` INT(11) NULL DEFAULT NULL,
                `id_cod_permissao` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PARCELA_USUARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_posto_registros()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `posto_registros`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(255) NULL DEFAULT NULL,
                `descricao` TEXT NULL DEFAULT NULL,
                `data_fechamento` DATE NULL DEFAULT NULL,
                `estoque_inicio_dia` FLOAT NULL DEFAULT NULL,
                `volume_entradas` FLOAT NULL DEFAULT NULL,
                `volume_disponivel` FLOAT NULL DEFAULT NULL,
                `volume_saidas` FLOAT NULL DEFAULT NULL,
                `estoque_escritural` FLOAT NULL DEFAULT NULL,
                `valor_perda` FLOAT NULL DEFAULT NULL,
                `valor_ganho` FLOAT NULL DEFAULT NULL,
                `estoque_fechamento` FLOAT NULL DEFAULT NULL,
                `created_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela POSTO_REGISTROS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_pro_parametro()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `pro_parametro`
            (
                `id_pro_parametro` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `parametro` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`id_pro_parametro`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRO_PARAMETRO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_pro_parametro_valor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `pro_parametro_valor`
            (
                `id_pro_parametro_valor` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `id_valor` INT(11) NULL DEFAULT NULL,
                `qtd` DECIMAL(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id_pro_parametro_valor`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRO_PARAMETRO_VALOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_pro_valor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `pro_valor`
            (
                `id_pro_valor` INT(11) NOT NULL AUTO_INCREMENT,
                `id_parametro` INT(11) NULL DEFAULT NULL,
                `valor_parametro` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`id_pro_valor`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRO_VALOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto`
            (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(120) NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_classificacao` INT(10) UNSIGNED NULL DEFAULT NULL,
                `cor` VARCHAR(15) NULL DEFAULT NULL,
                `id_cor` INT(5) NULL DEFAULT NULL,
                `tamanho` VARCHAR(15) NULL DEFAULT NULL,
                `custo` DECIMAL(10,5) NULL DEFAULT NULL,
                `custo_medio_venda` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_prazo` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_varejo` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_atacado` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_custo_venda` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_venda_prazo` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_atacado_avista` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_atacado_aprazo` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `qtd_atacado` INT(10) UNSIGNED NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I', 'E') NOT NULL DEFAULT 'A' COMMENT 'A - Ativo, I - Inativo, E - Excluido',
                `qtd_minima` DECIMAL(10,3) NULL DEFAULT '0.000',
                `peso` DECIMAL(12,4) NULL DEFAULT '0.0000',
                `codigo_barra` VARCHAR(22) NULL DEFAULT NULL,
                `barra` VARCHAR(20) NULL DEFAULT NULL,
                `sincronizado` INT(1) NOT NULL DEFAULT '0' COMMENT '0-Nao 1-Sim',
                `iss` INT(2) NULL DEFAULT '0',
                `icms` DECIMAL(4,2) NULL DEFAULT '0.00',
                `id_unidade` INT(2) NOT NULL DEFAULT '2',
                `localizacao` VARCHAR(50) NULL DEFAULT NULL,
                `id_fornecedor` BIGINT(20) NULL DEFAULT NULL,
                `fabricante` VARCHAR(50) NULL DEFAULT NULL,
                `ean` VARCHAR(13) NULL DEFAULT NULL,
                `ex_tipi` VARCHAR(3) NULL DEFAULT NULL,
                `ncm` VARCHAR(8) NULL DEFAULT NULL,
                `cest` VARCHAR(8) NULL DEFAULT NULL,
                `unidade_trib` VARCHAR(6) NULL DEFAULT NULL,
                `qtd_trib` VARCHAR(10) NULL DEFAULT NULL,
                `vlr_unit_trib` DECIMAL(10,2) NULL DEFAULT NULL,
                `genero_produto` INT(2) NULL DEFAULT NULL,
                `id_tributacao` INT(11) NULL DEFAULT NULL,
                `id_origem` INT(11) NULL DEFAULT '0' COMMENT '0 - Nacional, 1 - Importacao Direta, 2 - Adquirida no mercado Interno',
                `id_especifico` INT(11) NULL DEFAULT NULL,
                `id_cfop` INT(11) NULL DEFAULT NULL,
                `id_cfop_itens` INT(11) NULL DEFAULT NULL,
                `desconto` INT(3) NULL DEFAULT '0',
                `vender_estoque_zerado` ENUM('S', 'N') NULL DEFAULT 'S',
                `descricao_detalhada` TEXT NULL DEFAULT NULL,
                `ecommerce` ENUM('S', 'N') NULL DEFAULT 'N' COMMENT 'Enviar para Loja Virtual',
                `promocao_ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `produto_destaque_ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `altura` DECIMAL(12,2) NULL DEFAULT '0.00',
                `largura` DECIMAL(12,2) NULL DEFAULT '0.00',
                `comprimento` DECIMAL(12,2) NULL DEFAULT '0.00',
                `id_marca` INT(11) NULL DEFAULT NULL,
                `destaque` ENUM('P', 'L', 'N') NULL DEFAULT 'N' COMMENT 'Principal= 1 apenas, Lateral=4 apenas, N=Sem destaque',
                `valor_frete` DECIMAL(10,2) NULL DEFAULT '0.00',
                `cofins` VARCHAR(5) NULL DEFAULT NULL,
                `pis` VARCHAR(5) NULL DEFAULT NULL,
                `data_fabricacao` DATE NULL DEFAULT NULL,
                `data_validade` DATE NULL DEFAULT NULL,
                `lote_produto` VARCHAR(15) NULL DEFAULT NULL,
                `nr_edicao` VARCHAR(15) NULL DEFAULT NULL,
                `peso_bruto` DECIMAL(12,4) NULL DEFAULT '0.0000',
                `pis_aliquota` DECIMAL(4,2) NULL DEFAULT NULL,
                `pis_cst` INT(11) NULL DEFAULT NULL,
                `ipi_aliquota` INT(3) NULL DEFAULT NULL,
                `ipi_cst` INT(11) NULL DEFAULT NULL,
                `cofins_aliquota` DECIMAL(4,2) NULL DEFAULT NULL,
                `cofins_cst` INT(11) NULL DEFAULT NULL,
                `icms_cst` INT(2) NULL DEFAULT NULL,
                `icms_modalidade` INT(11) NULL DEFAULT NULL,
                `peso_caixa` DECIMAL(12,4) NULL DEFAULT '0.0000',
                `alt_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `larg_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `comp_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `margem_lucro_tipo` ENUM('P', 'V') NULL DEFAULT NULL,
                `margem_lucro_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `desconto_maximo_tipo` ENUM('P', 'V') NULL DEFAULT NULL,
                `desconto_maximo_percentual` DECIMAL(10,2) NULL DEFAULT NULL,
                `desconto_maximo_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `infos_nutricionais` ENUM('S', 'N') NULL DEFAULT 'N',
                `prod_serv` ENUM('P', 'S') NOT NULL DEFAULT 'P',
                `identificacao_interna` VARCHAR(22) NULL DEFAULT NULL,
                `solicitar_vrtotal` ENUM('S', 'N') NULL DEFAULT 'N',
                `casas_decimais` INT(1) UNSIGNED NULL DEFAULT '2',
                `locacao_quantidade` DECIMAL(10,3) UNSIGNED NULL DEFAULT '0.000',
                `obs_preco` TEXT NULL DEFAULT NULL,
                `id_bomba_bico` INT(11) NULL DEFAULT NULL,
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `perc_dif_uf` DECIMAL(5,2) NULL DEFAULT NULL,
                `qtd_unidade` DECIMAL(10,3) NULL DEFAULT NULL,
                `truncar_vlr_total` ENUM('S', 'N') NULL DEFAULT 'S',
                `codigo_anp` VARCHAR(10) NULL DEFAULT NULL,
                `env_prod` ENUM('S', 'N') NULL DEFAULT 'S' COMMENT 'Enviar para Producao (comanda)',
                `peso_liquido` DECIMAL(12,4) NULL DEFAULT '0.0000',
                `estoque_lojavirtual` TINYINT(4) NULL DEFAULT '1',
                `deletar` ENUM('S', 'N') NOT NULL DEFAULT 'S',
                `comissao_valor` DECIMAL(12,2) NULL DEFAULT NULL,
                `num_parcelas` INT(11) NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `fcp` CHAR(1) NULL DEFAULT 'S',
                `glp` CHAR(1) NULL DEFAULT 'N',
                `exibir_grafico` INT(1) NULL DEFAULT '0',
                `id_ibptax` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto2()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto2`
            (
                `descricao` VARCHAR(120) NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_classificacao` INT(10) UNSIGNED NULL DEFAULT NULL,
                `cor` VARCHAR(15) NULL DEFAULT NULL,
                `id_cor` INT(5) NULL DEFAULT NULL,
                `tamanho` VARCHAR(15) NULL DEFAULT NULL,
                `custo` DECIMAL(10,5) NULL DEFAULT NULL,
                `custo_medio_venda` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_prazo` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_varejo` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_atacado` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_custo_venda` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_venda_prazo` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_atacado_avista` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_atacado_aprazo` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `qtd_atacado` INT(10) UNSIGNED NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I', 'E') NOT NULL DEFAULT 'A' COMMENT 'A - Ativo, I - Inativo, E - Excluido',
                `qtd_minima` DECIMAL(10,3) NULL DEFAULT '0.000',
                `peso` DECIMAL(12,4) NULL DEFAULT '0.0000',
                `codigo_barra` VARCHAR(22) NULL DEFAULT NULL,
                `barra` VARCHAR(20) NULL DEFAULT NULL,
                `sincronizado` INT(1) NOT NULL DEFAULT '0' COMMENT '0-Nao 1-Sim',
                `iss` INT(2) NULL DEFAULT '0',
                `icms` DECIMAL(4,2) NULL DEFAULT '0.00',
                `id_unidade` INT(2) NOT NULL DEFAULT '2',
                `localizacao` VARCHAR(50) NULL DEFAULT NULL,
                `id_fornecedor` BIGINT(20) NULL DEFAULT NULL,
                `fabricante` VARCHAR(50) NULL DEFAULT NULL,
                `ean` VARCHAR(13) NULL DEFAULT NULL,
                `ex_tipi` VARCHAR(3) NULL DEFAULT NULL,
                `ncm` VARCHAR(8) NULL DEFAULT NULL,
                `cest` VARCHAR(8) NULL DEFAULT NULL,
                `unidade_trib` VARCHAR(6) NULL DEFAULT NULL,
                `qtd_trib` VARCHAR(10) NULL DEFAULT NULL,
                `vlr_unit_trib` DECIMAL(10,2) NULL DEFAULT NULL,
                `genero_produto` INT(2) NULL DEFAULT NULL,
                `id_tributacao` INT(11) NULL DEFAULT NULL,
                `id_origem` INT(11) NULL DEFAULT '0' COMMENT '0 - Nacional, 1 - Importacao Direta, 2 - Adquirida no mercado Interno',
                `id_especifico` INT(11) NULL DEFAULT NULL,
                `id_cfop` INT(11) NULL DEFAULT NULL,
                `id_cfop_itens` INT(11) NULL DEFAULT NULL,
                `desconto` INT(3) NULL DEFAULT '0',
                `vender_estoque_zerado` ENUM('S', 'N') NULL DEFAULT 'S',
                `descricao_detalhada` TEXT NULL DEFAULT NULL,
                `ecommerce` ENUM('S', 'N') NULL DEFAULT 'N' COMMENT 'Enviar para Loja Virtual',
                `promocao_ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `produto_destaque_ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `altura` DECIMAL(12,2) NULL DEFAULT '0.00',
                `largura` DECIMAL(12,2) NULL DEFAULT '0.00',
                `comprimento` DECIMAL(12,2) NULL DEFAULT '0.00',
                `id_marca` INT(11) NULL DEFAULT NULL,
                `destaque` ENUM('P', 'L', 'N') NULL DEFAULT 'N' COMMENT 'Principal= 1 apenas, Lateral=4 apenas, N=Sem destaque',
                `valor_frete` DECIMAL(10,2) NULL DEFAULT '0.00',
                `cofins` VARCHAR(5) NULL DEFAULT NULL,
                `pis` VARCHAR(5) NULL DEFAULT NULL,
                `data_fabricacao` DATE NULL DEFAULT NULL,
                `data_validade` DATE NULL DEFAULT NULL,
                `lote_produto` VARCHAR(15) NULL DEFAULT NULL,
                `nr_edicao` VARCHAR(15) NULL DEFAULT NULL,
                `peso_bruto` DECIMAL(12,4) NULL DEFAULT '0.0000',
                `pis_aliquota` DECIMAL(4,2) NULL DEFAULT NULL,
                `pis_cst` INT(11) NULL DEFAULT NULL,
                `ipi_aliquota` INT(3) NULL DEFAULT NULL,
                `ipi_cst` INT(11) NULL DEFAULT NULL,
                `cofins_aliquota` DECIMAL(4,2) NULL DEFAULT NULL,
                `cofins_cst` INT(11) NULL DEFAULT NULL,
                `icms_cst` INT(2) NULL DEFAULT NULL,
                `icms_modalidade` INT(11) NULL DEFAULT NULL,
                `peso_caixa` DECIMAL(12,4) NULL DEFAULT '0.0000',
                `alt_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `larg_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `comp_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `margem_lucro_tipo` ENUM('P', 'V') NULL DEFAULT NULL,
                `margem_lucro_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `desconto_maximo_tipo` ENUM('P', 'V') NULL DEFAULT NULL,
                `desconto_maximo_percentual` DECIMAL(10,2) NULL DEFAULT NULL,
                `desconto_maximo_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `infos_nutricionais` ENUM('S', 'N') NULL DEFAULT 'N',
                `prod_serv` ENUM('P', 'S') NOT NULL DEFAULT 'P',
                `identificacao_interna` VARCHAR(22) NULL DEFAULT NULL,
                `solicitar_vrtotal` ENUM('S', 'N') NULL DEFAULT 'N',
                `casas_decimais` INT(1) UNSIGNED NULL DEFAULT '2',
                `locacao_quantidade` DECIMAL(10,3) UNSIGNED NULL DEFAULT '0.000',
                `obs_preco` TEXT NULL DEFAULT NULL,
                `id_bomba_bico` INT(11) NULL DEFAULT NULL,
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `perc_dif_uf` DECIMAL(5,2) NULL DEFAULT NULL,
                `qtd_unidade` DECIMAL(10,3) NULL DEFAULT NULL,
                `truncar_vlr_total` ENUM('S', 'N') NULL DEFAULT 'S',
                `codigo_anp` VARCHAR(10) NULL DEFAULT NULL,
                `env_prod` ENUM('S', 'N') NULL DEFAULT 'S' COMMENT 'Enviar para Producao (comanda)',
                `peso_liquido` DECIMAL(12,4) NULL DEFAULT '0.0000',
                `estoque_lojavirtual` TINYINT(4) NULL DEFAULT '1',
                `deletar` ENUM('S', 'N') NOT NULL DEFAULT 'S',
                `comissao_valor` DECIMAL(12,2) NULL DEFAULT NULL,
                `num_parcelas` INT(11) NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `fcp` CHAR(1) NULL DEFAULT 'S',
                `glp` CHAR(1) NULL DEFAULT 'N',
                `exibir_grafico` INT(1) NULL DEFAULT '0')
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO2 criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_apoio()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_apoio`
            (
                `ID` INT(11) NULL DEFAULT NULL,
                `UID` TEXT NULL DEFAULT NULL,
                `Codigo` TEXT NULL DEFAULT NULL,
                `Descricao` TEXT NULL DEFAULT NULL,
                `Unid` TEXT NULL DEFAULT NULL,
                `Preco` INT(11) NULL DEFAULT NULL,
                `PrecoAuto` TEXT NULL DEFAULT NULL,
                `Margem` TEXT NULL DEFAULT NULL,
                `Obs` INT(11) NULL DEFAULT NULL,
                `Imagem` INT(11) NULL DEFAULT NULL,
                `Categoria` INT(11) NULL DEFAULT NULL,
                `Fornecedor` INT(11) NULL DEFAULT NULL,
                `SubCateg` INT(11) NULL DEFAULT NULL,
                `EstoqueAtual` INT(11) NULL DEFAULT NULL,
                `EstoquePend` INT(11) NULL DEFAULT NULL,
                `EstoqueTot` INT(11) NULL DEFAULT NULL,
                `brtrib` INT(11) NULL DEFAULT NULL,
                `CustoUnitario` TEXT NULL DEFAULT NULL,
                `PodeAlterarPreco` INT(11) NULL DEFAULT NULL,
                `PermiteVendaFracionada` INT(11) NULL DEFAULT NULL,
                `NaoControlaEstoque` TEXT NULL DEFAULT NULL,
                `EstoqueMin` INT(11) NULL DEFAULT NULL,
                `EstoqueMax` INT(11) NULL DEFAULT NULL,
                `AbaixoMin` TEXT NULL DEFAULT NULL,
                `AbaixoMinDesde` TEXT NULL DEFAULT NULL,
                `EstoqueRepor` INT(11) NULL DEFAULT NULL,
                `ComissaoPerc` INT(11) NULL DEFAULT NULL,
                `ComissaoLucro` INT(11) NULL DEFAULT NULL,
                `PesoBruto` INT(11) NULL DEFAULT NULL,
                `PesoLiq` INT(11) NULL DEFAULT NULL,
                `tax_id` INT(11) NULL DEFAULT NULL,
                `Ativo` TEXT NULL DEFAULT NULL,
                `Fidelidade` TEXT NULL DEFAULT NULL,
                `FidPontos` INT(11) NULL DEFAULT NULL,
                `NCM` INT(11) NULL DEFAULT NULL,
                `NCM_Ex` INT(11) NULL DEFAULT NULL,
                `cest` INT(11) NULL DEFAULT NULL,
                `modST` INT(11) NULL DEFAULT NULL,
                `MVA` INT(11) NULL DEFAULT NULL,
                `Pauta` INT(11) NULL DEFAULT NULL,
                `CadastroRapido` TEXT NULL DEFAULT NULL,
                `IncluidoEm` TEXT NULL DEFAULT NULL,
                `AlteradoEm` TEXT NULL DEFAULT NULL,
                `AlteradoPor` TEXT NULL DEFAULT NULL,
                `RecVer` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_APOIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_arrumar_estoque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_arrumar_estoque`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `data_hora_arrumo` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_cadastro` BIGINT(20) NULL DEFAULT NULL,
                `id_usuario` BIGINT(20) NULL DEFAULT NULL,
                `id_produto` BIGINT(20) NULL DEFAULT NULL,
                `qtd_atual` DECIMAL(10,3) NULL DEFAULT NULL,
                `qtd_retiro_inseriu` DECIMAL(10,3) NULL DEFAULT NULL,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `inserir_retirar` ENUM('I', 'R') NULL DEFAULT 'I',
                `fico` DECIMAL(10,3) NULL DEFAULT NULL,
                `id_motivo` INT(11) NULL DEFAULT NULL,
                `motivo` VARCHAR(255) NULL DEFAULT NULL,
                `tipo` ENUM('E', 'S', 'A') NULL DEFAULT NULL,
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `id_grade` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_ARRUMAR_ESTOQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_arrumar_estoque_tmp()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_arrumar_estoque_tmp`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `data_hora_arrumo` DATETIME NULL DEFAULT NULL,
                `id_cliente` BIGINT(20) NULL DEFAULT NULL,
                `id_cadastro` BIGINT(20) NULL DEFAULT NULL,
                `id_usuario` BIGINT(20) NULL DEFAULT NULL,
                `id_produto` BIGINT(20) NULL DEFAULT NULL,
                `qtd_atual` DECIMAL(10,3) NULL DEFAULT NULL,
                `qtd_retiro_inseriu` DECIMAL(10,3) NULL DEFAULT NULL,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `inserir_retirar` ENUM('I', 'R') NULL DEFAULT 'I',
                `fico` DECIMAL(10,3) NULL DEFAULT NULL,
                `id_motivo` VARCHAR(255) NULL DEFAULT NULL,
                `motivo` VARCHAR(255) NULL DEFAULT NULL,
                `tipo` ENUM('E', 'S', 'A') NULL DEFAULT NULL,
                `cod_barra` VARCHAR(20) NULL DEFAULT NULL,
                `unidade_destino` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_ARRUMAR_ESTOQUE_TMP criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_beneficio_fiscal()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_beneficio_fiscal`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_produto` BIGINT(20) NULL DEFAULT NULL,
                `cst` INT(3) NULL DEFAULT NULL,
                `cBeneFiscal` VARCHAR(8) NULL DEFAULT '',
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_BENEFICIO_FISCAL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_busca_prevenda()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_busca_prevenda`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(105) NULL DEFAULT NULL,
                `chave` VARCHAR(105) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_BUSCA_PREVENDA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_busca_prevenda_ordem()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_busca_prevenda_ordem`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_busca_prevenda` INT(11) NULL DEFAULT NULL,
                `ordem` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_BUSCA_PREVENDA_ORDEM criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_combNF()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_combNF`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_produto` INT(11) NOT NULL,
                `descANP` VARCHAR(20) NOT NULL,
                `pGLP` DECIMAL(12,2) NOT NULL,
                `CODIF` INT(11) NOT NULL,
                `qTemp` DECIMAL(12,4) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_COMBNF criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_configuracoes_comercial()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_configuracoes_comercial`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `controle_qtd` ENUM('S', 'N') NULL DEFAULT 'N',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_CONFIGURACOES_COMERCIAL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_contabil()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_contabil`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_produto` BIGINT(20) NULL DEFAULT NULL,
                `codBenef` VARCHAR(10) NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_CONTABIL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_fornecedor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_fornecedor`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_produto` BIGINT(20) UNSIGNED NOT NULL,
                `id_fornecedor` INT(10) UNSIGNED NOT NULL,
                `id_usuario` INT(10) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_FORNECEDOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_foto()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_foto`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_produto` BIGINT(20) NULL DEFAULT NULL COMMENT 'id_grade ou id_produto ',
                `caminho_imagem` VARCHAR(150) NULL DEFAULT NULL,
                `id_grade` BIGINT(20) NULL DEFAULT NULL COMMENT 'id_grade ou id_produto ',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_FOTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_icms()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_icms`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_tributacao` INT(11) NULL DEFAULT NULL,
                `id_origem` INT(11) NULL DEFAULT NULL,
                `id_base_calculo` INT(11) NULL DEFAULT NULL,
                `aliquota_icms` DECIMAL(10,2) NULL DEFAULT NULL,
                `percentual_base_calculo` DECIMAL(10,2) NULL DEFAULT NULL,
                `id_modalidade_st` INT(11) NULL DEFAULT NULL,
                `aliquota_icms_st` DECIMAL(10,2) NULL DEFAULT NULL,
                `percentual_reducao` DECIMAL(10,2) NULL DEFAULT NULL,
                `percentual_margem` DECIMAL(10,2) NULL DEFAULT NULL,
                `id_produto` BIGINT(20) NULL DEFAULT NULL,
                `id_usuario` BIGINT(20) NULL DEFAULT NULL,
                `id_cadastro` BIGINT(20) NULL DEFAULT NULL,
                `data_hora_cadastro` DATETIME NULL DEFAULT NULL,
                `situacao` ENUM('A', 'E') NULL DEFAULT 'A',
                `data_hora_excluido` DATETIME NULL DEFAULT NULL,
                `id_usuario_deleto` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_ICMS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_info_nutricionais()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_info_nutricionais`
            (
                `id_produto` INT(6) NOT NULL,
                `dias` INT(2) NOT NULL,
                `porcao` VARCHAR(35) NOT NULL,
                `calorias` INT(5) NOT NULL,
                `caboidrato` INT(5) NOT NULL,
                `proteinas` INT(5) NOT NULL,
                `gord_tot` INT(5) NOT NULL,
                `gord_sat` INT(5) NOT NULL,
                `colesterol` INT(5) NOT NULL,
                `gord_trans` INT(5) NOT NULL,
                `fibra` INT(5) NOT NULL,
                `calcio` INT(5) NOT NULL,
                `ferro` INT(5) NOT NULL,
                `sodio` INT(5) NOT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_INFO_NUTRICIONAIS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_num_parcelas_aux()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_num_parcelas_aux`
            (
                `id_produto` INT(11) NULL DEFAULT NULL,
                `num_parcelas` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_NUM_PARCELAS_AUX criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produto_pedido_equipamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produto_pedido_equipamento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'TABELA USADA PARA TABELA DE PEDIDOS DA PAGINA DE FRANQUIAS',
                `id_cadastro` INT(11) NOT NULL,
                `id_produto` INT(11) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTO_PEDIDO_EQUIPAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_apoio()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_apoio`
            (
                `COD_PRD` INT(11) NULL DEFAULT NULL,
                `DESCRI_PRD` TEXT NULL DEFAULT NULL,
                `UNIDADE_PRD` TEXT NULL DEFAULT NULL,
                `VLRUNIDADE_PRD` TEXT NULL DEFAULT NULL,
                `FORN_PRD` INT(11) NULL DEFAULT NULL,
                `QTDE_PRD` INT(11) NULL DEFAULT NULL,
                `MINIMO_PRD` INT(11) NULL DEFAULT NULL,
                `VLRCOMPRA_PRD` TEXT NULL DEFAULT NULL,
                `INDCUSTO_PRD` TEXT NULL DEFAULT NULL,
                `LOCAL1_PRD` INT(11) NULL DEFAULT NULL,
                `LOCAL2_PRD` INT(11) NULL DEFAULT NULL,
                `DPTO_PRD` INT(11) NULL DEFAULT NULL,
                `CODBAR_PRD` FLOAT NULL DEFAULT NULL,
                `SUJVDA_PRD` INT(11) NULL DEFAULT NULL,
                `CUSPOND_PRD` INT(11) NULL DEFAULT NULL,
                `OFERTA_PRD` TEXT NULL DEFAULT NULL,
                `DTOFERTA_PRD` TEXT NULL DEFAULT NULL,
                `VLROFERTA_PRD` INT(11) NULL DEFAULT NULL,
                `SUBDPTO_PRD` INT(11) NULL DEFAULT NULL,
                `PRECAD_PRD` TEXT NULL DEFAULT NULL,
                `MAXDESC_PRD` INT(11) NULL DEFAULT NULL,
                `CTRVCTO_PRD` TEXT NULL DEFAULT NULL,
                `SCTAPRODUCAO_PRD` TEXT NULL DEFAULT NULL,
                `tipo_prd` INT(11) NULL DEFAULT NULL,
                `Cargabal_prd` TEXT NULL DEFAULT NULL,
                `ALIQUOTA_PRD` INT(11) NULL DEFAULT NULL,
                `Atual_prd` TEXT NULL DEFAULT NULL,
                `VDDBALANCA_PRD` INT(11) NULL DEFAULT NULL,
                `CODFAB_PRD` INT(11) NULL DEFAULT NULL,
                `VLRTETO_PRD` INT(11) NULL DEFAULT NULL,
                `TPAGREG_PRD` TEXT NULL DEFAULT NULL,
                `VLIMPORG_PRD` INT(11) NULL DEFAULT NULL,
                `VLIMPAGRE_PRD` INT(11) NULL DEFAULT NULL,
                `Atacado_prd` TEXT NULL DEFAULT NULL,
                `PREMONTA_PRD` TEXT NULL DEFAULT NULL,
                `ATLFRENTE_PRD` FLOAT NULL DEFAULT NULL,
                `Paranet_prd` TEXT NULL DEFAULT NULL,
                `Ativo_prd` TEXT NULL DEFAULT NULL,
                `CODEX_PRD` INT(11) NULL DEFAULT NULL,
                `CL_PRD` INT(11) NULL DEFAULT NULL,
                `CODGN_PRD` INT(11) NULL DEFAULT NULL,
                `SIT_PRD` TEXT NULL DEFAULT NULL,
                `ICMS_PRD` INT(11) NULL DEFAULT NULL,
                `ORIGEM_PRD` INT(11) NULL DEFAULT NULL,
                `STPIS_PRD` INT(11) NULL DEFAULT NULL,
                `ALQPIS_PRD` INT(11) NULL DEFAULT NULL,
                `STCOFINS_PRD` INT(11) NULL DEFAULT NULL,
                `ALQCOFINS_PRD` INT(11) NULL DEFAULT NULL,
                `CFOP_PRD` INT(11) NULL DEFAULT NULL,
                `Forcatrib_prd` TEXT NULL DEFAULT NULL,
                `ipi_prd` INT(11) NULL DEFAULT NULL,
                `CSOSN_PRD` INT(11) NULL DEFAULT NULL,
                `SLTAVLR_PRD` TEXT NULL DEFAULT NULL,
                `PRODUZONDE_PRD` INT(11) NULL DEFAULT NULL,
                `SLTAVLRUNI_PRD` TEXT NULL DEFAULT NULL,
                `COMBUSTIVEL_PRD` TEXT NULL DEFAULT NULL,
                `CODANP_PRD` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_APOIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_composicao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_composicao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `id_produto_composicao` INT(11) NULL DEFAULT NULL,
                `qtd` DECIMAL(15,3) NULL DEFAULT NULL,
                `tipo_baixa_composicao` ENUM('F', 'V') NULL DEFAULT 'F',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_COMPOSICAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_deletados()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_deletados`
            (
                `id` INT(11) NULL DEFAULT NULL,
                `descricao` TEXT NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `data_cadastro` TEXT NULL DEFAULT NULL,
                `id_classificacao` INT(11) NULL DEFAULT NULL,
                `cor` INT(11) NULL DEFAULT NULL,
                `id_cor` INT(11) NULL DEFAULT NULL,
                `tamanho` INT(11) NULL DEFAULT NULL,
                `custo` FLOAT NULL DEFAULT NULL,
                `custo_medio_venda` INT(11) NULL DEFAULT NULL,
                `custo_medio_venda_prazo` INT(11) NULL DEFAULT NULL,
                `custo_medio_venda_varejo` INT(11) NULL DEFAULT NULL,
                `custo_medio_venda_atacado` INT(11) NULL DEFAULT NULL,
                `porcentagem_custo_venda` INT(11) NULL DEFAULT NULL,
                `porcentagem_venda_prazo` INT(11) NULL DEFAULT NULL,
                `porcentagem_atacado_avista` INT(11) NULL DEFAULT NULL,
                `porcentagem_atacado_aprazo` INT(11) NULL DEFAULT NULL,
                `qtd_atacado` INT(11) NULL DEFAULT NULL,
                `ativo` TEXT NULL DEFAULT NULL,
                `qtd_minima` FLOAT NULL DEFAULT NULL,
                `peso` INT(11) NULL DEFAULT NULL,
                `codigo_barra` TEXT NULL DEFAULT NULL,
                `barra` INT(11) NULL DEFAULT NULL,
                `sincronizado` INT(11) NULL DEFAULT NULL,
                `iss` INT(11) NULL DEFAULT NULL,
                `icms` FLOAT NULL DEFAULT NULL,
                `id_unidade` INT(11) NULL DEFAULT NULL,
                `localizacao` INT(11) NULL DEFAULT NULL,
                `id_fornecedor` INT(11) NULL DEFAULT NULL,
                `fabricante` INT(11) NULL DEFAULT NULL,
                `ean` INT(11) NULL DEFAULT NULL,
                `ex_tipi` INT(11) NULL DEFAULT NULL,
                `ncm` INT(11) NULL DEFAULT NULL,
                `cest` INT(11) NULL DEFAULT NULL,
                `unidade_trib` INT(11) NULL DEFAULT NULL,
                `qtd_trib` INT(11) NULL DEFAULT NULL,
                `vlr_unit_trib` INT(11) NULL DEFAULT NULL,
                `genero_produto` INT(11) NULL DEFAULT NULL,
                `id_tributacao` INT(11) NULL DEFAULT NULL,
                `id_origem` INT(11) NULL DEFAULT NULL,
                `id_especifico` INT(11) NULL DEFAULT NULL,
                `id_cfop` INT(11) NULL DEFAULT NULL,
                `id_cfop_itens` INT(11) NULL DEFAULT NULL,
                `desconto` INT(11) NULL DEFAULT NULL,
                `vender_estoque_zerado` TEXT NULL DEFAULT NULL,
                `descricao_detalhada` INT(11) NULL DEFAULT NULL,
                `ecommerce` TEXT NULL DEFAULT NULL,
                `promocao_ecommerce` TEXT NULL DEFAULT NULL,
                `produto_destaque_ecommerce` TEXT NULL DEFAULT NULL,
                `altura` INT(11) NULL DEFAULT NULL,
                `largura` INT(11) NULL DEFAULT NULL,
                `comprimento` INT(11) NULL DEFAULT NULL,
                `id_marca` INT(11) NULL DEFAULT NULL,
                `destaque` TEXT NULL DEFAULT NULL,
                `valor_frete` FLOAT NULL DEFAULT NULL,
                `cofins` INT(11) NULL DEFAULT NULL,
                `pis` INT(11) NULL DEFAULT NULL,
                `data_fabricacao` INT(11) NULL DEFAULT NULL,
                `data_validade` INT(11) NULL DEFAULT NULL,
                `lote_produto` INT(11) NULL DEFAULT NULL,
                `nr_edicao` INT(11) NULL DEFAULT NULL,
                `peso_bruto` INT(11) NULL DEFAULT NULL,
                `pis_aliquota` INT(11) NULL DEFAULT NULL,
                `pis_cst` INT(11) NULL DEFAULT NULL,
                `ipi_aliquota` INT(11) NULL DEFAULT NULL,
                `ipi_cst` INT(11) NULL DEFAULT NULL,
                `cofins_aliquota` INT(11) NULL DEFAULT NULL,
                `cofins_cst` INT(11) NULL DEFAULT NULL,
                `icms_cst` INT(11) NULL DEFAULT NULL,
                `icms_modalidade` INT(11) NULL DEFAULT NULL,
                `peso_caixa` INT(11) NULL DEFAULT NULL,
                `alt_caixa` INT(11) NULL DEFAULT NULL,
                `larg_caixa` INT(11) NULL DEFAULT NULL,
                `comp_caixa` INT(11) NULL DEFAULT NULL,
                `margem_lucro_tipo` INT(11) NULL DEFAULT NULL,
                `margem_lucro_valor` INT(11) NULL DEFAULT NULL,
                `desconto_maximo_tipo` INT(11) NULL DEFAULT NULL,
                `desconto_maximo_percentual` INT(11) NULL DEFAULT NULL,
                `desconto_maximo_valor` INT(11) NULL DEFAULT NULL,
                `infos_nutricionais` TEXT NULL DEFAULT NULL,
                `prod_serv` TEXT NULL DEFAULT NULL,
                `identificacao_interna` INT(11) NULL DEFAULT NULL,
                `solicitar_vrtotal` TEXT NULL DEFAULT NULL,
                `casas_decimais` INT(11) NULL DEFAULT NULL,
                `locacao_quantidade` FLOAT NULL DEFAULT NULL,
                `obs_preco` INT(11) NULL DEFAULT NULL,
                `id_bomba_bico` INT(11) NULL DEFAULT NULL,
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TEXT NULL DEFAULT NULL,
                `perc_dif_uf` INT(11) NULL DEFAULT NULL,
                `qtd_unidade` INT(11) NULL DEFAULT NULL,
                `truncar_vlr_total` TEXT NULL DEFAULT NULL,
                `codigo_anp` INT(11) NULL DEFAULT NULL,
                `env_prod` TEXT NULL DEFAULT NULL,
                `peso_liquido` INT(11) NULL DEFAULT NULL,
                `estoque_lojavirtual` INT(11) NULL DEFAULT NULL,
                `deletar` TEXT NULL DEFAULT NULL,
                `comissao_valor` INT(11) NULL DEFAULT NULL,
                `num_parcelas` INT(11) NULL DEFAULT NULL,
                `data_sincronismo` INT(11) NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_DELETADOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_detalhes()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_detalhes`
            (
                `id_produto` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(255) NULL DEFAULT NULL,
                `codigo_barra_ean` BIGINT(20) NOT NULL,
                `codigo_barra_dun` BIGINT(20) NULL DEFAULT NULL,
                `ncm` VARCHAR(10) NULL DEFAULT NULL,
                `validade_dias` INT(5) NULL DEFAULT NULL,
                `imagem_produto` VARCHAR(255) NULL DEFAULT NULL,
                `nome_sem_acento` VARCHAR(255) NULL DEFAULT NULL,
                `nome_upper` VARCHAR(255) NULL DEFAULT NULL,
                `nome_upper_sem_acento` VARCHAR(255) NULL DEFAULT NULL,
                `nome_lower` VARCHAR(255) NULL DEFAULT NULL,
                `nome_lowe_sem_acento` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_DETALHES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_detalhes_agrupagem()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_detalhes_agrupagem`
            (
                `id_produto_detalhes` BIGINT(20) NOT NULL,
                `unidade_por_caixa` DECIMAL(12,3) NULL DEFAULT NULL,
                `unidade_por_palete` DECIMAL(12,3) NULL DEFAULT NULL,
                `caixa_por_palete` DECIMAL(12,3) NULL DEFAULT NULL,
                `caixa_por_camada` DECIMAL(12,3) NULL DEFAULT NULL,
                `camadas_por_palete` DECIMAL(12,3) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto_detalhes`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_DETALHES_AGRUPAGEM criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_detalhes_dimensoes_caixa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_detalhes_dimensoes_caixa`
            (
                `id_produto_detalhes` BIGINT(20) NOT NULL,
                `altura` DECIMAL(12,3) NULL DEFAULT NULL,
                `comprimento` DECIMAL(12,3) NULL DEFAULT NULL,
                `largura` DECIMAL(12,3) NULL DEFAULT NULL,
                `peso` DECIMAL(12,3) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto_detalhes`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_DETALHES_DIMENSOES_CAIXA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_detalhes_dimensoes_palete()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_detalhes_dimensoes_palete`
            (
                `id_produto_detalhes` BIGINT(20) NOT NULL,
                `altura` DECIMAL(12,3) NULL DEFAULT NULL,
                `comprimento` DECIMAL(12,3) NULL DEFAULT NULL,
                `largura` DECIMAL(12,3) NULL DEFAULT NULL,
                `peso` DECIMAL(12,3) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto_detalhes`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_DETALHES_DIMENSOES_PALETE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_detalhes_dimensoes_unidade()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_detalhes_dimensoes_unidade`
            (
                `id_produto_detalhes` BIGINT(20) NOT NULL,
                `altura` DECIMAL(12,3) NULL DEFAULT NULL,
                `comprimento` DECIMAL(12,3) NULL DEFAULT NULL,
                `largura` DECIMAL(12,3) NULL DEFAULT NULL,
                `capacidade` DECIMAL(12,3) NULL DEFAULT NULL,
                PRIMARY KEY (`id_produto_detalhes`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_DETALHES_DIMENSOES_UNIDADE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_excluidos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_excluidos`
            (
                `id` BIGINT(20) UNSIGNED NOT NULL,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_classificacao` INT(10) UNSIGNED NULL DEFAULT NULL,
                `cor` VARCHAR(15) NULL DEFAULT NULL,
                `id_cor` INT(5) NULL DEFAULT NULL,
                `tamanho` VARCHAR(15) NULL DEFAULT NULL,
                `custo` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_prazo` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_varejo` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_atacado` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_custo_venda` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_venda_prazo` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_atacado_avista` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_atacado_aprazo` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `qtd_atacado` INT(10) UNSIGNED NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I', 'E') NOT NULL DEFAULT 'A' COMMENT 'A - Ativo, I - Inativo, E - Excluido',
                `qtd_minima` INT(11) NULL DEFAULT '0',
                `peso` VARCHAR(10) NULL DEFAULT '0',
                `codigo_barra` VARCHAR(20) NULL DEFAULT NULL,
                `barra` VARCHAR(20) NULL DEFAULT NULL,
                `sincronizado` INT(1) NOT NULL DEFAULT '0' COMMENT '0-Nao 1-Sim',
                `iss` INT(2) NULL DEFAULT '0',
                `icms` DECIMAL(4,2) NULL DEFAULT '0.00',
                `id_unidade` INT(2) NULL DEFAULT '2',
                `localizacao` VARCHAR(50) NULL DEFAULT NULL,
                `id_fornecedor` BIGINT(20) NULL DEFAULT NULL,
                `fabricante` VARCHAR(50) NULL DEFAULT NULL,
                `ean` VARCHAR(13) NULL DEFAULT NULL,
                `ex_tipi` VARCHAR(3) NULL DEFAULT NULL,
                `ncm` VARCHAR(8) NULL DEFAULT NULL,
                `cest` VARCHAR(8) NULL DEFAULT NULL,
                `unidade_trib` VARCHAR(6) NULL DEFAULT NULL,
                `qtd_trib` VARCHAR(10) NULL DEFAULT NULL,
                `vlr_unit_trib` DECIMAL(10,2) NULL DEFAULT NULL,
                `genero_produto` INT(2) NULL DEFAULT NULL,
                `id_tributacao` INT(11) NULL DEFAULT NULL,
                `id_origem` INT(11) NULL DEFAULT '1',
                `id_especifico` INT(11) NULL DEFAULT NULL,
                `id_cfop` INT(11) NULL DEFAULT NULL,
                `id_cfop_itens` INT(11) NULL DEFAULT NULL,
                `desconto` INT(3) NULL DEFAULT '0',
                `vender_estoque_zerado` ENUM('S', 'N') NULL DEFAULT 'S',
                `descricao_detalhada` TEXT NULL DEFAULT NULL,
                `ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `promocao_ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `produto_destaque_ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `altura` INT(2) NULL DEFAULT '0',
                `largura` INT(2) NULL DEFAULT '0',
                `comprimento` INT(2) NULL DEFAULT '0',
                `id_marca` INT(11) NULL DEFAULT NULL,
                `destaque` ENUM('P', 'L', 'N') NULL DEFAULT 'N' COMMENT 'Principal= 1 apenas, Lateral=4 apenas, N=Sem destaque',
                `valor_frete` DECIMAL(10,2) NULL DEFAULT '0.00',
                `cofins` VARCHAR(5) NULL DEFAULT NULL,
                `pis` VARCHAR(5) NULL DEFAULT NULL,
                `data_fabricacao` DATE NULL DEFAULT NULL,
                `data_validade` DATE NULL DEFAULT NULL,
                `lote_produto` VARCHAR(15) NULL DEFAULT NULL,
                `nr_edicao` VARCHAR(15) NULL DEFAULT NULL,
                `peso_bruto` VARCHAR(10) NULL DEFAULT NULL,
                `pis_aliquota` DECIMAL(4,2) NULL DEFAULT NULL,
                `pis_cst` INT(11) NULL DEFAULT NULL,
                `ipi_aliquota` INT(3) NULL DEFAULT NULL,
                `ipi_cst` INT(11) NULL DEFAULT NULL,
                `cofins_aliquota` DECIMAL(4,2) NULL DEFAULT NULL,
                `cofins_cst` INT(11) NULL DEFAULT NULL,
                `icms_cst` INT(2) NULL DEFAULT NULL,
                `icms_modalidade` INT(11) NULL DEFAULT NULL,
                `peso_caixa` INT(11) NULL DEFAULT NULL,
                `alt_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `larg_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `comp_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `margem_lucro_tipo` ENUM('P', 'V') NULL DEFAULT NULL,
                `margem_lucro_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `desconto_maximo_tipo` ENUM('P', 'V') NULL DEFAULT NULL,
                `desconto_maximo_percentual` DECIMAL(10,2) NULL DEFAULT NULL,
                `desconto_maximo_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `infos_nutricionais` ENUM('S', 'N') NULL DEFAULT 'N',
                `prod_serv` ENUM('P', 'S') NOT NULL DEFAULT 'P',
                `identificacao_interna` VARCHAR(10) NULL DEFAULT NULL,
                `solicitar_vrtotal` ENUM('S', 'N') NULL DEFAULT 'N',
                `casas_decimais` INT(1) UNSIGNED NULL DEFAULT '2',
                `locacao_quantidade` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `obs_preco` TEXT NULL DEFAULT NULL,
                `id_bomba_bico` INT(11) NULL DEFAULT NULL,
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `perc_dif_uf` DECIMAL(5,2) NULL DEFAULT NULL,
                `qtd_unidade` DECIMAL(10,3) NULL DEFAULT NULL,
                `truncar_vlr_total` ENUM('S', 'N') NULL DEFAULT 'S',
                `codigo_anp` VARCHAR(10) NULL DEFAULT NULL,
                `env_prod` ENUM('S', 'N') NULL DEFAULT 'S',
                `peso_liquido` VARCHAR(10) NULL DEFAULT NULL,
                `estoque_lojavirtual` TINYINT(4) NULL DEFAULT NULL,
                `deletar` ENUM('S', 'N') NOT NULL DEFAULT 'S',
                `comissao_valor` DECIMAL(12,2) NULL DEFAULT NULL,
                `num_parcelas` INT(11) NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_EXCLUIDOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_reciclagem()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_reciclagem`
            (
                `id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
                `descricao` VARCHAR(80) NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_classificacao` INT(10) UNSIGNED NULL DEFAULT NULL,
                `cor` VARCHAR(15) NULL DEFAULT NULL,
                `id_cor` INT(5) NULL DEFAULT NULL,
                `tamanho` VARCHAR(15) NULL DEFAULT NULL,
                `custo` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_prazo` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_varejo` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_atacado` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_custo_venda` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_venda_prazo` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_atacado_avista` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_atacado_aprazo` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `qtd_atacado` INT(10) UNSIGNED NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I', 'E') NOT NULL DEFAULT 'A' COMMENT 'A - Ativo, I - Inativo, E - Excluido',
                `qtd_minima` DECIMAL(10,3) NULL DEFAULT '0.000',
                `peso` VARCHAR(10) NULL DEFAULT '0',
                `codigo_barra` VARCHAR(22) NULL DEFAULT NULL,
                `barra` VARCHAR(20) NULL DEFAULT NULL,
                `sincronizado` INT(1) NOT NULL DEFAULT '0' COMMENT '0-Nao 1-Sim',
                `iss` INT(2) NULL DEFAULT '0',
                `icms` DECIMAL(4,2) NULL DEFAULT '0.00',
                `id_unidade` INT(2) NOT NULL DEFAULT '2',
                `localizacao` VARCHAR(50) NULL DEFAULT NULL,
                `id_fornecedor` BIGINT(20) NULL DEFAULT NULL,
                `fabricante` VARCHAR(50) NULL DEFAULT NULL,
                `ean` VARCHAR(13) NULL DEFAULT NULL,
                `ex_tipi` VARCHAR(3) NULL DEFAULT NULL,
                `ncm` VARCHAR(8) NULL DEFAULT NULL,
                `cest` VARCHAR(8) NULL DEFAULT NULL,
                `unidade_trib` VARCHAR(6) NULL DEFAULT NULL,
                `qtd_trib` VARCHAR(10) NULL DEFAULT NULL,
                `vlr_unit_trib` DECIMAL(10,2) NULL DEFAULT NULL,
                `genero_produto` INT(2) NULL DEFAULT NULL,
                `id_tributacao` INT(11) NULL DEFAULT NULL,
                `id_origem` INT(11) NULL DEFAULT '1' COMMENT '0 - Nacional, 1 - Importacao Direta, 2 - Adquirida no mercado Interno',
                `id_especifico` INT(11) NULL DEFAULT NULL,
                `id_cfop` INT(11) NULL DEFAULT NULL,
                `id_cfop_itens` INT(11) NULL DEFAULT NULL,
                `desconto` INT(3) NULL DEFAULT '0',
                `vender_estoque_zerado` ENUM('S', 'N') NULL DEFAULT 'S',
                `descricao_detalhada` TEXT NULL DEFAULT NULL,
                `ecommerce` ENUM('S', 'N') NULL DEFAULT 'N' COMMENT 'Enviar para Loja Virtual',
                `promocao_ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `produto_destaque_ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `altura` INT(2) NULL DEFAULT '0',
                `largura` INT(2) NULL DEFAULT '0',
                `comprimento` INT(2) NULL DEFAULT '0',
                `id_marca` INT(11) NULL DEFAULT NULL,
                `destaque` ENUM('P', 'L', 'N') NULL DEFAULT 'N' COMMENT 'Principal= 1 apenas, Lateral=4 apenas, N=Sem destaque',
                `valor_frete` DECIMAL(10,2) NULL DEFAULT '0.00',
                `cofins` VARCHAR(5) NULL DEFAULT NULL,
                `pis` VARCHAR(5) NULL DEFAULT NULL,
                `data_fabricacao` DATE NULL DEFAULT NULL,
                `data_validade` DATE NULL DEFAULT NULL,
                `lote_produto` VARCHAR(15) NULL DEFAULT NULL,
                `nr_edicao` VARCHAR(15) NULL DEFAULT NULL,
                `peso_bruto` VARCHAR(10) NULL DEFAULT NULL,
                `pis_aliquota` DECIMAL(4,2) NULL DEFAULT NULL,
                `pis_cst` INT(11) NULL DEFAULT NULL,
                `ipi_aliquota` INT(3) NULL DEFAULT NULL,
                `ipi_cst` INT(11) NULL DEFAULT NULL,
                `cofins_aliquota` DECIMAL(4,2) NULL DEFAULT NULL,
                `cofins_cst` INT(11) NULL DEFAULT NULL,
                `icms_cst` INT(2) NULL DEFAULT NULL,
                `icms_modalidade` INT(11) NULL DEFAULT NULL,
                `peso_caixa` INT(11) NULL DEFAULT NULL,
                `alt_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `larg_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `comp_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `margem_lucro_tipo` ENUM('P', 'V') NULL DEFAULT NULL,
                `margem_lucro_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `desconto_maximo_tipo` ENUM('P', 'V') NULL DEFAULT NULL,
                `desconto_maximo_percentual` DECIMAL(10,2) NULL DEFAULT NULL,
                `desconto_maximo_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `infos_nutricionais` ENUM('S', 'N') NULL DEFAULT 'N',
                `prod_serv` ENUM('P', 'S') NOT NULL DEFAULT 'P',
                `identificacao_interna` VARCHAR(22) NULL DEFAULT NULL,
                `solicitar_vrtotal` ENUM('S', 'N') NULL DEFAULT 'N',
                `casas_decimais` INT(1) UNSIGNED NULL DEFAULT '2',
                `locacao_quantidade` DECIMAL(10,3) UNSIGNED NULL DEFAULT '0.000',
                `obs_preco` TEXT NULL DEFAULT NULL,
                `id_bomba_bico` INT(11) NULL DEFAULT NULL,
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT NULL,
                `perc_dif_uf` DECIMAL(5,2) NULL DEFAULT NULL,
                `qtd_unidade` DECIMAL(10,3) NULL DEFAULT NULL,
                `truncar_vlr_total` ENUM('S', 'N') NULL DEFAULT 'S',
                `codigo_anp` VARCHAR(10) NULL DEFAULT NULL,
                `env_prod` ENUM('S', 'N') NULL DEFAULT 'S' COMMENT 'Enviar para Producao (comanda)',
                `peso_liquido` VARCHAR(10) NULL DEFAULT NULL,
                `estoque_lojavirtual` TINYINT(4) NULL DEFAULT '1',
                `deletar` ENUM('S', 'N') NOT NULL DEFAULT 'S',
                `comissao_valor` DECIMAL(12,2) NULL DEFAULT NULL,
                `num_parcelas` INT(11) NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `data_exclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_RECICLAGEM criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_produtos_removidos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `produtos_removidos`
            (
                `id` BIGINT(20) UNSIGNED NOT NULL,
                `descricao` VARCHAR(80) NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_classificacao` INT(10) UNSIGNED NULL DEFAULT NULL,
                `cor` VARCHAR(15) NULL DEFAULT NULL,
                `id_cor` INT(5) NULL DEFAULT NULL,
                `tamanho` VARCHAR(15) NULL DEFAULT NULL,
                `custo` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_prazo` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_varejo` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `custo_medio_venda_atacado` DECIMAL(12,3) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_custo_venda` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_venda_prazo` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_atacado_avista` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `porcentagem_atacado_aprazo` DECIMAL(18,15) UNSIGNED NULL DEFAULT NULL,
                `qtd_atacado` INT(10) UNSIGNED NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I', 'E') NOT NULL DEFAULT 'A' COMMENT 'A - Ativo, I - Inativo, E - Excluido',
                `qtd_minima` DECIMAL(10,3) NULL DEFAULT '0.000',
                `peso` VARCHAR(10) NULL DEFAULT '0',
                `codigo_barra` VARCHAR(22) NULL DEFAULT NULL,
                `barra` VARCHAR(20) NULL DEFAULT NULL,
                `sincronizado` INT(1) NOT NULL DEFAULT '0' COMMENT '0-Nao 1-Sim',
                `iss` INT(2) NULL DEFAULT '0',
                `icms` DECIMAL(4,2) NULL DEFAULT '0.00',
                `id_unidade` INT(2) NOT NULL DEFAULT '2',
                `localizacao` VARCHAR(50) NULL DEFAULT NULL,
                `id_fornecedor` BIGINT(20) NULL DEFAULT NULL,
                `fabricante` VARCHAR(50) NULL DEFAULT NULL,
                `ean` VARCHAR(13) NULL DEFAULT NULL,
                `ex_tipi` VARCHAR(3) NULL DEFAULT NULL,
                `ncm` VARCHAR(8) NULL DEFAULT NULL,
                `cest` VARCHAR(8) NULL DEFAULT NULL,
                `unidade_trib` VARCHAR(6) NULL DEFAULT NULL,
                `qtd_trib` VARCHAR(10) NULL DEFAULT NULL,
                `vlr_unit_trib` DECIMAL(10,2) NULL DEFAULT NULL,
                `genero_produto` INT(2) NULL DEFAULT NULL,
                `id_tributacao` INT(11) NULL DEFAULT NULL,
                `id_origem` INT(11) NULL DEFAULT '1',
                `id_especifico` INT(11) NULL DEFAULT NULL,
                `id_cfop` INT(11) NULL DEFAULT NULL,
                `id_cfop_itens` INT(11) NULL DEFAULT NULL,
                `desconto` INT(3) NULL DEFAULT '0',
                `vender_estoque_zerado` ENUM('S', 'N') NULL DEFAULT 'S',
                `descricao_detalhada` TEXT NULL DEFAULT NULL,
                `ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `promocao_ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `produto_destaque_ecommerce` ENUM('S', 'N') NULL DEFAULT 'N',
                `altura` INT(2) NULL DEFAULT '0',
                `largura` INT(2) NULL DEFAULT '0',
                `comprimento` INT(2) NULL DEFAULT '0',
                `id_marca` INT(11) NULL DEFAULT NULL,
                `destaque` ENUM('P', 'L', 'N') NULL DEFAULT 'N' COMMENT 'Principal= 1 apenas, Lateral=4 apenas, N=Sem destaque',
                `valor_frete` DECIMAL(10,2) NULL DEFAULT '0.00',
                `cofins` VARCHAR(5) NULL DEFAULT NULL,
                `pis` VARCHAR(5) NULL DEFAULT NULL,
                `data_fabricacao` DATE NULL DEFAULT NULL,
                `data_validade` DATE NULL DEFAULT NULL,
                `lote_produto` VARCHAR(15) NULL DEFAULT NULL,
                `nr_edicao` VARCHAR(15) NULL DEFAULT NULL,
                `peso_bruto` VARCHAR(10) NULL DEFAULT NULL,
                `pis_aliquota` DECIMAL(4,2) NULL DEFAULT NULL,
                `pis_cst` INT(11) NULL DEFAULT NULL,
                `ipi_aliquota` INT(3) NULL DEFAULT NULL,
                `ipi_cst` INT(11) NULL DEFAULT NULL,
                `cofins_aliquota` DECIMAL(4,2) NULL DEFAULT NULL,
                `cofins_cst` INT(11) NULL DEFAULT NULL,
                `icms_cst` INT(2) NULL DEFAULT NULL,
                `icms_modalidade` INT(11) NULL DEFAULT NULL,
                `peso_caixa` INT(11) NULL DEFAULT NULL,
                `alt_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `larg_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `comp_caixa` DECIMAL(5,2) NULL DEFAULT NULL,
                `margem_lucro_tipo` ENUM('P', 'V') NULL DEFAULT NULL,
                `margem_lucro_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `desconto_maximo_tipo` ENUM('P', 'V') NULL DEFAULT NULL,
                `desconto_maximo_percentual` DECIMAL(10,2) NULL DEFAULT NULL,
                `desconto_maximo_valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `infos_nutricionais` ENUM('S', 'N') NULL DEFAULT 'N',
                `prod_serv` ENUM('P', 'S') NOT NULL DEFAULT 'P',
                `identificacao_interna` VARCHAR(22) NULL DEFAULT NULL,
                `solicitar_vrtotal` ENUM('S', 'N') NULL DEFAULT 'N',
                `casas_decimais` INT(1) UNSIGNED NULL DEFAULT '2',
                `locacao_quantidade` DECIMAL(10,3) UNSIGNED NULL DEFAULT '0.000',
                `obs_preco` TEXT NULL DEFAULT NULL,
                `id_bomba_bico` INT(11) NULL DEFAULT NULL,
                `id_importacao` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT NULL,
                `perc_dif_uf` DECIMAL(5,2) NULL DEFAULT NULL,
                `qtd_unidade` DECIMAL(10,3) NULL DEFAULT NULL,
                `truncar_vlr_total` ENUM('S', 'N') NULL DEFAULT 'S',
                `codigo_anp` VARCHAR(10) NULL DEFAULT NULL,
                `env_prod` ENUM('S', 'N') NULL DEFAULT 'S',
                `peso_liquido` VARCHAR(10) NULL DEFAULT NULL,
                `estoque_lojavirtual` TINYINT(4) NULL DEFAULT '1',
                `deletar` ENUM('S', 'N') NOT NULL DEFAULT 'S',
                `comissao_valor` DECIMAL(12,2) NULL DEFAULT NULL,
                `num_parcelas` INT(11) NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PRODUTOS_REMOVIDOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_promocao_kit()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `promocao_kit`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `descricao` VARCHAR(100) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(25) NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I', 'E') NOT NULL DEFAULT 'A',
                `id_usuario_alteracao` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PROMOCAO_KIT criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_promocao_kit_grade()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `promocao_kit_grade`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_promocao_kit` INT(11) NOT NULL,
                `id_grade` INT(11) NULL DEFAULT NULL,
                `vlr_custo_original` DECIMAL(25,15) NULL DEFAULT NULL,
                `vlr_custo_promocao` DECIMAL(25,15) NULL DEFAULT NULL,
                `preco_fixo` ENUM('S', 'N') NULL DEFAULT 'N',
                `valor_desconto_perc` DECIMAL(5,2) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `qtde` FLOAT(6,3) NULL DEFAULT '1.000',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PROMOCAO_KIT_GRADE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_promocao_mix()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `promocao_mix`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `descricao` VARCHAR(150) NULL DEFAULT NULL,
                `array_id_produto` TEXT NULL DEFAULT NULL,
                `qtd` FLOAT(5,3) NULL DEFAULT NULL,
                `valor` FLOAT(10,2) NULL DEFAULT NULL,
                `data_inicio` TIMESTAMP NULL DEFAULT NULL,
                `data_fim` TIMESTAMP NULL DEFAULT NULL,
                `status` TINYINT(4) NULL DEFAULT '1',
                `total_desconto` FLOAT(10,2) NULL DEFAULT '0.00',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PROMOCAO_MIX criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_promocao_mix_desconto()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `promocao_mix_desconto`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_promocao_mix` INT(11) NOT NULL,
                `id_produto` INT(11) NOT NULL,
                `valor_desconto` FLOAT(10,3) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PROMOCAO_MIX_DESCONTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_promocao_mix_tempo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `promocao_mix_tempo`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `id_promo_mix` INT(11) NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                `qtd` FLOAT(10,3) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(45) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PROMOCAO_MIX_TEMPO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_promocao_quantidade()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `promocao_quantidade`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_grade` INT(11) NOT NULL,
                `id_usuario` INT(11) NOT NULL,
                `data_inicio` DATETIME NOT NULL,
                `data_fim` DATETIME NOT NULL,
                `qtd_promocao` INT(11) NOT NULL,
                `valor_desconto` DECIMAL(15,3) NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NOT NULL DEFAULT 'A',
                `id_usuario_alterou` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela PROMOCAO_QUANTIDADE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_relatorio_estoque_mensal()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `relatorio_estoque_mensal`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `dia` DATE NULL DEFAULT NULL,
                `hora` TIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela RELATORIO_ESTOQUE_MENSAL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_relatorio_estoque_mensal_produtos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `relatorio_estoque_mensal_produtos`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_relatoriomensal` INT(11) NULL DEFAULT NULL,
                `codigo_barra` TEXT NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `descricao` TEXT NULL DEFAULT NULL,
                `unid` DECIMAL(9,3) NULL DEFAULT NULL,
                `custo` DECIMAL(9,3) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela RELATORIO_ESTOQUE_MENSAL_PRODUTOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_relatorios_campos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `relatorios_campos`
            (
                `id_campo` INT(11) NOT NULL AUTO_INCREMENT,
                `id_tabela` INT(11) NULL DEFAULT NULL,
                `nome_campo` VARCHAR(100) NULL DEFAULT NULL,
                `tamanho_campo` INT(11) NOT NULL,
                `nome_campo_form` VARCHAR(100) NULL DEFAULT NULL,
                `tabela_forenign` INT(11) NULL DEFAULT NULL,
                `campo_forenign` INT(11) NULL DEFAULT NULL,
                `categoria` VARCHAR(30) NULL DEFAULT NULL,
                `mascara` VARCHAR(50) NULL DEFAULT NULL,
                `ordemApresentacao` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id_campo`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela RELATORIO_CAMPOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_relatorios_tabelas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `relatorios_tabelas`
            (
                `id_tabela` INT(11) NOT NULL AUTO_INCREMENT,
                `nome_tabela` VARCHAR(100) NOT NULL,
                `nome_banco` VARCHAR(100) NOT NULL,
                PRIMARY KEY (`id_tabela`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela RELATORIO_TABELAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_remetente()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `remetente`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `cpfCnpj` VARCHAR(14) NULL DEFAULT NULL,
                `rg` VARCHAR(14) NULL DEFAULT NULL,
                `inscricaoEstadual` VARCHAR(14) NULL DEFAULT NULL,
                `nome` VARCHAR(60) NULL DEFAULT NULL,
                `nomeFantasia` VARCHAR(60) NULL DEFAULT NULL,
                `fone` VARCHAR(12) NULL DEFAULT NULL,
                `logradouro` VARCHAR(255) NULL DEFAULT NULL,
                `numero` VARCHAR(60) NULL DEFAULT NULL,
                `complemento` VARCHAR(60) NULL DEFAULT NULL,
                `bairro` VARCHAR(60) NULL DEFAULT NULL,
                `municipio` VARCHAR(60) NULL DEFAULT NULL,
                `idMunicipio` VARCHAR(7) NULL DEFAULT NULL,
                `cep` VARCHAR(8) NULL DEFAULT NULL,
                `uf` VARCHAR(2) NULL DEFAULT NULL,
                `suframa` VARCHAR(9) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela REMETENTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_sequencia_assistencia()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `sequencia_assistencia`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `sequencia` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela SEQUENCIA_ASSISTENCIA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_sequencia_orcamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `sequencia_orcamento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `sequencia` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela SEQUENCIA_ORCAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_sequencia_ordem_servico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `sequencia_ordem_servico`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `sequencia` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela SEQUENCIA_ORDEM_SERVICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_sequencia_pedido()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `sequencia_pedido`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `sequencia` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela SEQUENCIA_PEDIDO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_setor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `setor`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `descricao` VARCHAR(150) NULL DEFAULT NULL,
                `ativo` ENUM('S', 'N') NULL DEFAULT 'S',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela SETOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_solicitacao_reativacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `solicitacao_reativacao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `logon` INT(11) NULL DEFAULT NULL,
                `nome_empresa` VARCHAR(255) NULL DEFAULT NULL,
                `nome_proprietario` VARCHAR(255) NULL DEFAULT NULL,
                `telefone` CHAR(50) NULL DEFAULT NULL,
                `email` VARCHAR(50) NULL DEFAULT NULL,
                `status_reativacao` CHAR(2) NULL DEFAULT NULL,
                `dt_reativacao` TIMESTAMP NULL DEFAULT NULL,
                `desc_reativacao` VARCHAR(250) NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela SOLICITACAO_REATIVACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_sugestao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `sugestao`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `descricao_envio` TEXT NULL DEFAULT NULL,
                `data_envio` DATETIME NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario_envio` INT(10) UNSIGNED NULL DEFAULT NULL,
                `lida` ENUM('N', 'S', 'D') NULL DEFAULT 'N',
                `data_lida` DATETIME NULL DEFAULT NULL,
                `id_franquia_registra_baixa` INT(10) UNSIGNED NULL DEFAULT NULL,
                `descricao_lida` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela SUGESTAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tabela_codigo_anp()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tabela_codigo_anp`
            (
                `descricao` VARCHAR(200) NULL DEFAULT NULL,
                `codigo` VARCHAR(10) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TABELA_CODIGO_ANP criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tabela_ncm()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tabela_ncm`
            (
                `ncm` VARCHAR(8) NOT NULL,
                `categoria` VARCHAR(100) NULL DEFAULT NULL,
                `descricao` VARCHAR(150) NULL DEFAULT NULL,
                `ipi_percentual` DECIMAL(5,2) NULL DEFAULT NULL,
                `inicio_vigencia` DATE NULL DEFAULT NULL,
                `fim_vigencia` DATE NULL DEFAULT NULL,
                `unid_tributada` VARCHAR(5) NULL DEFAULT NULL,
                `desc_unid_tributada` VARCHAR(25) NULL DEFAULT NULL,
                `gtin_producao` DATE NULL DEFAULT NULL,
                `gtin_homologacao` DATE NULL DEFAULT NULL,
                `observacao` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`ncm`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TABELA_NCM criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tabela_ncm_vigente()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tabela_ncm_vigente`
            (
                `ncm` VARCHAR(10) NULL DEFAULT NULL,
                `data_inicio` VARCHAR(10) NULL DEFAULT NULL,
                `data_fim` VARCHAR(10) NULL DEFAULT NULL,
                `dt_ini` DATE NULL DEFAULT NULL,
                `dt_fim` DATE NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TABELA_NCM_VIGENTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_test_apoio()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `test_apoio`
            (
                `Controle` INT(11) NULL DEFAULT NULL,
                `Codigo` INT(11) NULL DEFAULT NULL,
                `CodInterno` TEXT NULL DEFAULT NULL,
                `Produto` TEXT NULL DEFAULT NULL,
                `LkSetor` INT(11) NULL DEFAULT NULL,
                `Fabricante` TEXT NULL DEFAULT NULL,
                `LkFornec` INT(11) NULL DEFAULT NULL,
                `PrecoCusto` TEXT NULL DEFAULT NULL,
                `CustoMedio` TEXT NULL DEFAULT NULL,
                `PrecoVenda` TEXT NULL DEFAULT NULL,
                `Quantidade` INT(11) NULL DEFAULT NULL,
                `EstMinimo` INT(11) NULL DEFAULT NULL,
                `Unidade` TEXT NULL DEFAULT NULL,
                `Lucro` TEXT NULL DEFAULT NULL,
                `Moeda` TEXT NULL DEFAULT NULL,
                `UltReaj` TEXT NULL DEFAULT NULL,
                `Foto` TEXT NULL DEFAULT NULL,
                `Obs` INT(11) NULL DEFAULT NULL,
                `NaoSaiTabela` TEXT NULL DEFAULT NULL,
                `Inativo` TEXT NULL DEFAULT NULL,
                `CodIPI` TEXT NULL DEFAULT NULL,
                `IPI` INT(11) NULL DEFAULT NULL,
                `CST` TEXT NULL DEFAULT NULL,
                `ICMS` INT(11) NULL DEFAULT NULL,
                `BaseCalculo` INT(11) NULL DEFAULT NULL,
                `PesoBruto` INT(11) NULL DEFAULT NULL,
                `PesoLiq` INT(11) NULL DEFAULT NULL,
                `LkModulo` INT(11) NULL DEFAULT NULL,
                `Armazenamento` TEXT NULL DEFAULT NULL,
                `QntEmbalagem` INT(11) NULL DEFAULT NULL,
                `ELV` TEXT NULL DEFAULT NULL,
                `Previsao` INT(11) NULL DEFAULT NULL,
                `DataFoto` INT(11) NULL DEFAULT NULL,
                `DataInc` TEXT NULL DEFAULT NULL,
                `LkUserInc` INT(11) NULL DEFAULT NULL,
                `CodEx` TEXT NULL DEFAULT NULL,
                `IVA_ST` INT(11) NULL DEFAULT NULL,
                `PFC` INT(11) NULL DEFAULT NULL,
                `IPI_CST` TEXT NULL DEFAULT NULL,
                `IPI_BaseCalc` INT(11) NULL DEFAULT NULL,
                `IPPT` TEXT NULL DEFAULT NULL,
                `IAT` TEXT NULL DEFAULT NULL,
                `DataUltMov` INT(11) NULL DEFAULT NULL,
                `EAD` TEXT NULL DEFAULT NULL,
                `cEAN` TEXT NULL DEFAULT NULL,
                `cEANTrib` TEXT NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TEST_APOIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tipo_entrada()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tipo_entrada`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TIPO_ENTRADA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tipo_log()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tipo_log`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `reduzido` VARCHAR(30) NULL DEFAULT NULL,
                `descricao` VARCHAR(30) NOT NULL DEFAULT '',
                `visivel` ENUM('S', 'N') NULL DEFAULT 'N',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TIPO_LOG criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tipo_residencia()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tipo_residencia`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` CHAR(30) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TIPO_RESIDÊNCIA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tipo_unidade_tmp()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tipo_unidade_tmp`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                `qtd` INT(10) UNSIGNED NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TIPO_UNIDADE_TMP criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tipos_lancamentos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tipos_lancamentos`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `descricao` CHAR(60) NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TIPOS_LANCAMENTOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_titulos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `titulos`
            (
                `id` BIGINT(10) NOT NULL DEFAULT '0',
                `numdoc` BIGINT(20) NOT NULL DEFAULT '0',
                `insc` VARCHAR(14) NOT NULL DEFAULT '',
                `codloja` INT(11) NOT NULL DEFAULT '0',
                `carteira` INT(11) NOT NULL DEFAULT '0',
                `debito` ENUM('B', 'A') NOT NULL DEFAULT 'B',
                `emissao` DATE NOT NULL DEFAULT '0000-00-00',
                `vencimento` DATE NOT NULL DEFAULT '0000-00-00',
                `dti` DATE NOT NULL DEFAULT '0000-00-00',
                `dtf` DATE NOT NULL DEFAULT '0000-00-00',
                `valor` DECIMAL(8,2) NOT NULL DEFAULT '0.00',
                `valorpg` DECIMAL(8,2) NULL DEFAULT NULL,
                `datapg` DATE NULL DEFAULT NULL,
                `juros` DECIMAL(4,2) NULL DEFAULT NULL,
                `outras_cob` DECIMAL(8,2) NULL DEFAULT NULL,
                `desconto` DECIMAL(4,2) NULL DEFAULT NULL,
                `obs` LONGTEXT NULL DEFAULT NULL,
                `numboleto` VARCHAR(17) NULL DEFAULT NULL,
                `numboleto_bradesco` BIGINT(20) NULL DEFAULT NULL,
                `origem_pgto` ENUM('BANCO', 'FRANQUIA', 'NEGATIVADO', 'COMP') NULL DEFAULT NULL,
                `referencia` ENUM('OUT', 'BOL', 'MULTA', 'ADESAO') NOT NULL DEFAULT 'BOL',
                `banco_faturado` INT(3) NOT NULL DEFAULT '237',
                `isento_juros` ENUM('S', 'N') NULL DEFAULT 'N',
                `numboleto2` VARCHAR(17) NULL DEFAULT NULL,
                `mensal` DECIMAL(12,2) NULL DEFAULT NULL,
                `data_movimentacao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `num_lote` CHAR(2) NULL DEFAULT NULL,
                `data_gravacao_lote` TIMESTAMP NULL DEFAULT NULL,
                `banco_registro` INT(3) NULL DEFAULT NULL,
                `agencia_registro` VARCHAR(4) NULL DEFAULT NULL,
                `conta_registro` VARCHAR(8) NULL DEFAULT NULL,
                `cod_liquidacao` CHAR(2) NULL DEFAULT NULL,
                `data_registro` DATE NULL DEFAULT NULL,
                `protocolo_nf` VARCHAR(20) NOT NULL,
                `expirado` INT(1) NULL DEFAULT '0' COMMENT '0 - Nao Expirado   1 - Expirado',
                `data_baixa_contabilidade` DATE NULL DEFAULT NULL,
                `acrescimo_boleto` DECIMAL(12,2) NULL DEFAULT '0.00',
                `vencimento_alterado` DATE NULL DEFAULT NULL,
                `abatimento` DECIMAL(4,2) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TITULOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_titulos_movimentacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `titulos_movimentacao`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `numdoc` BIGINT(20) NOT NULL,
                `vencimento` DATE NULL DEFAULT NULL,
                `vr_desconto` DECIMAL(12,2) NULL DEFAULT NULL,
                `descricao` VARCHAR(100) NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TITULOS_MOVIMENTACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_titulos_recebafacil()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `titulos_recebafacil`
            (
                `numdoc` VARCHAR(20) NOT NULL DEFAULT '',
                `codloja` INT(11) NOT NULL,
                `emissao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `vencimento` DATE NOT NULL DEFAULT '0000-00-00',
                `valor` DECIMAL(8,2) NOT NULL DEFAULT '0.00',
                `valorpg` DECIMAL(8,2) NULL DEFAULT NULL,
                `datapg` DATE NULL DEFAULT NULL,
                `juros` DECIMAL(4,2) NULL DEFAULT NULL,
                `numboleto` BIGINT(20) NULL DEFAULT NULL,
                `numboleto_itau` BIGINT(20) NULL DEFAULT NULL,
                `numboleto_bradesco` BIGINT(20) NULL DEFAULT NULL,
                `numboleto_hsbc` BIGINT(20) NULL DEFAULT NULL,
                `cpfcnpj_devedor` BIGINT(20) NULL DEFAULT NULL,
                `tp_notificacao` VARCHAR(50) NULL DEFAULT NULL,
                `data_repasse` DATE NULL DEFAULT NULL,
                `descricao_repasse` LONGTEXT NULL DEFAULT NULL,
                `tp_recebafacil` INT(1) NOT NULL DEFAULT '0' COMMENT '0- COM NOTIFICACAO   1- SEM NOTIFICACAO    2 - Crediario System',
                `chavebol` BIGINT(20) NULL DEFAULT NULL,
                `bco` ENUM('001', '237', '341') NULL DEFAULT '341',
                `tp_titulo` ENUM('1', '2', '3') NULL DEFAULT NULL COMMENT '3 - Boleto',
                `contrato` INT(11) UNSIGNED ZEROFILL NULL DEFAULT '00000000000',
                `tx_adm` DECIMAL(12,2) NULL DEFAULT NULL,
                `exibir` ENUM('0', '1') NULL DEFAULT '0' COMMENT '0 - exibir / 1 - nao exibir',
                `txjur` INT(2) NULL DEFAULT '2',
                `automatico` ENUM('S', 'N') NOT NULL DEFAULT 'N',
                `impresso` INT(1) NOT NULL DEFAULT '2' COMMENT '1-Sim  2-Nao',
                `data_impresso` DATE NULL DEFAULT NULL,
                `referencia` ENUM('C', 'W') NULL DEFAULT 'C',
                `cod_pedido_web_control` INT(11) NULL DEFAULT '0',
                `radio_desconto` ENUM('S', 'N') NULL DEFAULT 'N' COMMENT 'Sim, Não',
                `vr_desconto` DECIMAL(8,2) NULL DEFAULT NULL,
                `radio_msg_boleto` ENUM('S', 'N') NULL DEFAULT 'N',
                `texto_msg_boleto` VARCHAR(60) NULL DEFAULT NULL,
                `id_usuariobaixa` BIGINT(20) NULL DEFAULT NULL,
                `tipo_notificacao` INT(1) NOT NULL DEFAULT '2' COMMENT '1o. Nível  -  2o. Nível - 3o. Nível',
                `parcela` VARCHAR(5) NOT NULL,
                `vr_total_divida` DECIMAL(12,2) NOT NULL,
                `porcentagem_desconto_avista` INT(2) NULL DEFAULT NULL,
                `porcentagem_desconto_aprazo` INT(2) NULL DEFAULT NULL,
                `id_motivo_exclusao` INT(1) NULL DEFAULT NULL,
                `data_exclusao` DATETIME NULL DEFAULT NULL,
                `nome` VARCHAR(55) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT NULL,
                `num_lote` CHAR(2) NOT NULL,
                `data_gravacao_lote` TIMESTAMP NULL DEFAULT NULL,
                `banco_registro` INT(3) NULL DEFAULT NULL,
                `agencia_registro` VARCHAR(4) NULL DEFAULT NULL,
                `conta_registro` VARCHAR(8) NULL DEFAULT NULL,
                `cod_liquidacao` CHAR(2) NULL DEFAULT NULL,
                `data_registro` DATE NULL DEFAULT NULL,
                `id_abertura_caixa` INT(11) NULL DEFAULT NULL,
                `data_baixa_contabilidade` DATE NULL DEFAULT NULL,
                `expirado` INT(1) NULL DEFAULT '0' COMMENT '0-Nao 1 - Sim',
                `carteira` VARCHAR(3) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TITULOS_RECEBAFACIL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_titulos_recebafacil_historico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `titulos_recebafacil_historico`
            (
                `id` BIGINT(20) NOT NULL,
                `id_cadastro` BIGINT(20) NOT NULL,
                `banco` INT(3) NULL DEFAULT NULL,
                `numero_lote` INT(11) NOT NULL,
                `arquivo_envio` VARCHAR(60) NULL DEFAULT NULL,
                `conteudo_arq_envio` LONGTEXT NULL DEFAULT NULL,
                `data_hora_geracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `qtd_reg_enviado` INT(11) NOT NULL,
                `arquivo_retorno` VARCHAR(60) NULL DEFAULT NULL,
                `conteudo_arq_retorno` LONGTEXT NULL DEFAULT NULL,
                `qtd_reg_erro` INT(11) NOT NULL,
                `data_hora_retorno` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TITULOS_RECEBAFACIL_HISTORICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_titulos_retorno()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `titulos_retorno`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `id_cadastro` INT(11) NOT NULL,
                `id_banco` INT(11) NOT NULL,
                `conteudo` TEXT NULL DEFAULT NULL,
                `erros` INT(11) NULL DEFAULT NULL,
                `link_arquivo` VARCHAR(256) NULL DEFAULT NULL,
                `lote` INT(11) NULL DEFAULT NULL,
                `arquivo_retorno` VARCHAR(256) NULL DEFAULT NULL,
                `data_hora_retorno` TIMESTAMP NULL DEFAULT NULL,
                `tp_arq` ENUM('E', 'R') NULL DEFAULT 'E',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TITULOS_RETORNO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_datas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_datas`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `data_fatura` DATE NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_DATAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_datas_afiliacoes()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_datas_afiliacoes`
            (
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_DATAS_AFILIACOES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_datas_afiliacoes1()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_datas_afiliacoes1`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `valor` INT(11) NULL DEFAULT NULL,
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_DATAS_AFILIACOES1 criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_datas_afiliacoes_comparar()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_datas_afiliacoes_comparar`
            (
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_DATAS_AFILIACOES_COMPARAR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_datas_atendimento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_datas_atendimento`
            (
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_DATAS_ATENDIMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_datas_cancelamentos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_datas_cancelamentos`
            (
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_DATAS_CANCELAMENTOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_datas_equipamentos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_datas_equipamentos`
            (
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_DATAS_EQUIPAMENTOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_datas_teste()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_datas_teste`
            (
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_DATAS_TESTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_fat_bonificada()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_fat_bonificada`
            (
                `fat_bonificada` VARCHAR(20) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_FAT_BONIFICADA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_grafico_afiliacoes()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_grafico_afiliacoes`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `valor` INT(11) NULL DEFAULT NULL,
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_GRAFICO_AFILIACOES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_grafico_afiliacoes_consultor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_grafico_afiliacoes_consultor`
            (
                `valor` INT(11) NULL DEFAULT '0',
                `data_inicio` DATE NULL DEFAULT NULL,
                `data_fim` DATE NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_GRAFICO_AFILIACOES_CONSULTOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_grafico_cancelados()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_grafico_cancelados`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `valor` INT(11) NULL DEFAULT NULL,
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_GRAFICO_CANCELADOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_imp_classificacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_imp_classificacao`
            (
                `id_cadastro` VARCHAR(255) NULL DEFAULT NULL,
                `id_usuario` VARCHAR(255) NULL DEFAULT NULL,
                `id_class_master` VARCHAR(255) NULL DEFAULT NULL,
                `descricao` TEXT NULL DEFAULT NULL,
                `status` ENUM('P', 'AP') NULL DEFAULT 'P' COMMENT 'P - Pendente AP - Aguardado Aprovacao',
                `id_usuario_importacao` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_IMP_CLASSIFICACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_imp_cliente()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_imp_cliente`
            (
                `id_cadastro` VARCHAR(255) NULL DEFAULT NULL,
                `id_usuario` VARCHAR(255) NULL DEFAULT NULL,
                `nome` VARCHAR(255) NULL DEFAULT NULL,
                `razao_social` VARCHAR(255) NULL DEFAULT NULL,
                `tipo_pessoa` VARCHAR(20) NULL DEFAULT NULL,
                `endereco` VARCHAR(255) NULL DEFAULT NULL,
                `numero` VARCHAR(20) NULL DEFAULT NULL,
                `complemento` VARCHAR(100) NULL DEFAULT NULL,
                `bairro` VARCHAR(100) NULL DEFAULT NULL,
                `cidade` VARCHAR(100) NULL DEFAULT NULL,
                `uf` VARCHAR(50) NULL DEFAULT NULL,
                `cep` VARCHAR(255) NULL DEFAULT NULL,
                `cnpj_cpf` VARCHAR(255) NULL DEFAULT NULL,
                `inscricao_municipal` VARCHAR(255) NULL DEFAULT NULL,
                `inscricao_estadual` VARCHAR(255) NULL DEFAULT NULL,
                `rg` VARCHAR(255) NULL DEFAULT NULL,
                `telefone` VARCHAR(100) NULL DEFAULT NULL,
                `fax` VARCHAR(100) NULL DEFAULT NULL,
                `celular` VARCHAR(100) NULL DEFAULT NULL,
                `fone_empresa` VARCHAR(100) NULL DEFAULT NULL,
                `nome_referencia` VARCHAR(255) NULL DEFAULT NULL,
                `nome_pai` VARCHAR(255) NULL DEFAULT NULL,
                `nome_mae` VARCHAR(255) NULL DEFAULT NULL,
                `renda_media` VARCHAR(255) NULL DEFAULT NULL,
                `empresa_trabalha` VARCHAR(255) NULL DEFAULT NULL,
                `email` VARCHAR(255) NULL DEFAULT NULL,
                `informacoes_adicionais` VARCHAR(255) NULL DEFAULT NULL,
                `status` ENUM('P', 'AP') NULL DEFAULT 'P' COMMENT 'P - Pendente AP - Aguardado Aprovacao',
                `id_usuario_importacao` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_IMP_CLIENTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_imp_estoque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_imp_estoque`
            (
                `id_cadastro` VARCHAR(255) NULL DEFAULT NULL,
                `id_usuario` VARCHAR(255) NULL DEFAULT NULL,
                `id_produto` VARCHAR(255) NULL DEFAULT NULL,
                `qtd_atual` VARCHAR(255) NULL DEFAULT NULL,
                `qtd_retiro_inseriu` VARCHAR(255) NULL DEFAULT NULL,
                `fico` VARCHAR(255) NULL DEFAULT NULL,
                `status` ENUM('P', 'AP') NULL DEFAULT 'P' COMMENT 'P - Pendente AP - Aguardado Aprovacao',
                `id_usuario_importacao` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_IMP_ESTOQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_imp_fornecedor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_imp_fornecedor`
            (
                `id_cadastro` VARCHAR(255) NULL DEFAULT NULL,
                `id_usuario` VARCHAR(255) NULL DEFAULT NULL,
                `id_forn_master` VARCHAR(255) NULL DEFAULT NULL,
                `fantasia` VARCHAR(255) NULL DEFAULT NULL,
                `razao_social` VARCHAR(255) NULL DEFAULT NULL,
                `tipo_pessoa` VARCHAR(255) NULL DEFAULT NULL,
                `endereco` VARCHAR(255) NULL DEFAULT NULL,
                `numero` VARCHAR(255) NULL DEFAULT NULL,
                `complemento` VARCHAR(255) NULL DEFAULT NULL,
                `bairro` VARCHAR(255) NULL DEFAULT NULL,
                `cidade` VARCHAR(255) NULL DEFAULT NULL,
                `uf` VARCHAR(255) NULL DEFAULT NULL,
                `cep` VARCHAR(255) NULL DEFAULT NULL,
                `cnpj_cpf` VARCHAR(255) NULL DEFAULT NULL,
                `insc_municipal` VARCHAR(255) NULL DEFAULT NULL,
                `insc_estadual` VARCHAR(255) NULL DEFAULT NULL,
                `rgie` VARCHAR(255) NULL DEFAULT NULL,
                `telefone` VARCHAR(255) NULL DEFAULT NULL,
                `fax` VARCHAR(255) NULL DEFAULT NULL,
                `celular` VARCHAR(255) NULL DEFAULT NULL,
                `contato` VARCHAR(255) NULL DEFAULT NULL,
                `email` VARCHAR(255) NULL DEFAULT NULL,
                `informacoes_adicionais` VARCHAR(255) NULL DEFAULT NULL,
                `status` ENUM('P', 'AP') NULL DEFAULT 'P' COMMENT 'P - Pendente AP - Aguardado Aprovacao',
                `id_usuario_importacao` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_IMP_FORNECEDOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_imp_produto()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_imp_produto`
            (
                `id_cadastro` VARCHAR(255) NULL DEFAULT NULL,
                `id_usuario` VARCHAR(255) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(255) NULL DEFAULT NULL,
                `descricao` VARCHAR(255) NULL DEFAULT NULL,
                `descricao_detalhada` TEXT NULL DEFAULT NULL,
                `id_unidade` VARCHAR(255) NULL DEFAULT NULL,
                `id_classificacao` VARCHAR(255) NULL DEFAULT NULL,
                `id_fornecedor` VARCHAR(255) NULL DEFAULT NULL,
                `custo` VARCHAR(255) NULL DEFAULT NULL,
                `custo_medio_venda` VARCHAR(255) NULL DEFAULT NULL,
                `ncm` VARCHAR(255) NULL DEFAULT NULL,
                `fabricante` VARCHAR(255) NULL DEFAULT NULL,
                `id_cfop` VARCHAR(255) NULL DEFAULT NULL,
                `identificacao_interna` VARCHAR(255) NULL DEFAULT NULL,
                `localizacao` VARCHAR(255) NULL DEFAULT NULL,
                `prod_serv` VARCHAR(255) NULL DEFAULT NULL,
                `qtd_minima` VARCHAR(255) NULL DEFAULT NULL,
                `tamanho` VARCHAR(255) NULL DEFAULT NULL,
                `cor` VARCHAR(255) NULL DEFAULT NULL,
                `barra` VARCHAR(255) NULL DEFAULT NULL,
                `ean` VARCHAR(30) NULL DEFAULT NULL,
                `cest` VARCHAR(30) NULL DEFAULT NULL,
                `status` ENUM('P', 'AP') NULL DEFAULT 'P' COMMENT 'P - Pendente AP - Aguardado Aprovacao',
                `margem_valor_lucro` VARCHAR(255) NULL DEFAULT NULL,
                `id_usuario_importacao` INT(11) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_IMP_PRODUTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_meses_label()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_meses_label`
            (
                `num_mes` INT(11) NULL DEFAULT NULL,
                `mes_label` VARCHAR(20) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_MESES_LABEL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_produto_aux()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_produto_aux`
            (
                `id_produto` INT(11) NULL DEFAULT NULL,
                `custo` DECIMAL(15,3) NULL DEFAULT NULL,
                `custo_medio_venda` DECIMAL(15,3) NULL DEFAULT NULL,
                `valor_custo` DECIMAL(15,3) NULL DEFAULT NULL,
                `valor_varejo_aprazo` DECIMAL(15,3) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_PRODUTO_AUX criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_ranking_agendamento_diario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_ranking_agendamento_diario`
            (
                `id_assistente` INT(11) NULL DEFAULT NULL,
                `d1` INT(11) NULL DEFAULT NULL,
                `d2` INT(11) NULL DEFAULT NULL,
                `d3` INT(11) NULL DEFAULT NULL,
                `d4` INT(11) NULL DEFAULT NULL,
                `d5` INT(11) NULL DEFAULT NULL,
                `d6` INT(11) NULL DEFAULT NULL,
                `d7` INT(11) NULL DEFAULT NULL,
                `d1_label` CHAR(10) NULL DEFAULT NULL,
                `d2_label` CHAR(10) NULL DEFAULT NULL,
                `d3_label` CHAR(10) NULL DEFAULT NULL,
                `d4_label` CHAR(10) NULL DEFAULT NULL,
                `d5_label` CHAR(10) NULL DEFAULT NULL,
                `d6_label` CHAR(10) NULL DEFAULT NULL,
                `d7_label` CHAR(10) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_RANKING_AGENDAMENTO_DIARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_ranking_atendimento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_ranking_atendimento`
            (
                `valor` INT(11) NOT NULL,
                `data_inicio` VARCHAR(255) NULL DEFAULT NULL,
                `data_fim` VARCHAR(255) NULL DEFAULT NULL,
                `mes` INT(11) NULL DEFAULT NULL,
                `nome` VARCHAR(255) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_RANKING_ATENDIMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_ranking_geral()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_ranking_geral`
            (
                `total` INT(11) NOT NULL,
                `mes1` INT(11) NULL DEFAULT '0',
                `mes2` INT(11) NULL DEFAULT '0',
                `mes3` INT(11) NULL DEFAULT '0',
                `mes4` INT(11) NULL DEFAULT '0',
                `mes5` INT(11) NULL DEFAULT '0',
                `mes6` INT(11) NULL DEFAULT '0',
                `mes7` INT(11) NULL DEFAULT '0',
                `mes8` INT(11) NULL DEFAULT '0',
                `mes9` INT(11) NULL DEFAULT '0',
                `mes10` INT(11) NULL DEFAULT '0',
                `mes11` INT(11) NULL DEFAULT '0',
                `mes12` INT(11) NULL DEFAULT '0',
                `mes1_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes2_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes3_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes4_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes5_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes6_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes7_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes8_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes9_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes10_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes11_label` VARCHAR(255) NULL DEFAULT NULL,
                `mes12_label` VARCHAR(255) NULL DEFAULT NULL,
                `nome` VARCHAR(255) NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_RANKING_GERAL criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_sped_150()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_sped_150`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `COD_PART` VARCHAR(60) NULL DEFAULT NULL COMMENT 'cpf ou cnpj do destinatario da nota',
                `NOME` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Nome do destinatario',
                `COD_PAIS` INT(5) NULL DEFAULT '1058' COMMENT 'Codigo do Pais - 1058 (Brasil)',
                `CNPJ` VARCHAR(14) NULL DEFAULT NULL COMMENT 'Cnpj do destinatario',
                `CPF` VARCHAR(11) NULL DEFAULT NULL COMMENT 'CPF do destinatario',
                `IE` VARCHAR(14) NULL DEFAULT NULL COMMENT 'Insc Estadual do destinatario',
                `COD_MUN` BIGINT(5) NULL DEFAULT NULL COMMENT 'Codigo da cidade do destinatario',
                `SUFRAMA` VARCHAR(9) NULL DEFAULT NULL COMMENT 'Codigo Suframa do destinatario',
                `END` VARCHAR(60) NULL DEFAULT NULL COMMENT 'Endereco do destinatario',
                `NUM` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Numero do endereco do destinatario',
                `COMPL` VARCHAR(40) NULL DEFAULT NULL COMMENT 'Complemento do endereco do destinatario',
                `BAIRRO` VARCHAR(60) NULL DEFAULT NULL COMMENT 'Bairro do endereco do destinatario',
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_SPED_150 criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_sped_190()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_sped_190`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `unidade` VARCHAR(11) NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_SPED_190 criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tmp_sped_unidade()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tmp_sped_unidade`
            (
                `Id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `unidade` VARCHAR(11) NULL DEFAULT NULL,
                PRIMARY KEY (`Id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TMP_SPED_UNIDADE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_campanha()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_campanha`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_lista` INT(11) NULL DEFAULT NULL,
                `listas` TEXT NULL DEFAULT NULL,
                `nome_campanha` VARCHAR(200) NULL DEFAULT NULL,
                `texto` VARCHAR(200) NULL DEFAULT NULL COMMENT 'ate 160 chars',
                `data_envio` DATE NULL DEFAULT NULL,
                `hora_envio` TIME NULL DEFAULT NULL,
                `status_campanha` CHAR(5) NULL DEFAULT NULL COMMENT 'E - Enviado / A - Agendado / R - Rascunho / P - Pausada / C - Cancelada',
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `id_campanha_allcance` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_CAMPANHA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_campanha_agendamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_campanha_agendamento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_torpedo_campanha` INT(11) NOT NULL,
                `data_inicio` DATETIME NOT NULL,
                `data_fim` DATETIME NOT NULL,
                `hora_agendamento` TIME NOT NULL,
                `status_agendamento` ENUM('A', 'E') NULL DEFAULT NULL COMMENT 'Não utilizado, criado só Deus sabe o porque...',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_CAMPANHA_AGENDAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_campanha_bkp_excluidos()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_campanha_bkp_excluidos`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_lista` INT(11) NULL DEFAULT NULL,
                `listas` TEXT NULL DEFAULT NULL,
                `nome_campanha` VARCHAR(200) NULL DEFAULT NULL,
                `texto` VARCHAR(200) NULL DEFAULT NULL COMMENT 'ate 160 chars',
                `data_envio` DATE NULL DEFAULT NULL,
                `hora_envio` TIME NULL DEFAULT NULL,
                `status_campanha` CHAR(5) NULL DEFAULT NULL COMMENT 'E - Enviado / A - Agendado / R - Rascunho / P - Pausada / C - Cancelada',
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `id_campanha_allcance` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_CAMPANHA_BKP_EXCLUIDOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_campanha_fixa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_campanha_fixa`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nome_campanha` VARCHAR(200) NOT NULL,
                `texto` VARCHAR(160) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_CAMPANHA_FIXA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_campanha_fixa_ignorar()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_campanha_fixa_ignorar`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_torpedo_campanha_fixa` INT(11) NULL DEFAULT NULL,
                `data_exclusao` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_CAMPANHA_FIXA_IGNORAR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_campanha_lista()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_campanha_lista`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_campanha` VARCHAR(11) NULL DEFAULT NULL,
                `codigo_status` VARCHAR(10) NULL DEFAULT NULL COMMENT 'T - Sucesso, F - Falha',
                `descricao_status` VARCHAR(30) NULL DEFAULT NULL,
                `celular` VARCHAR(255) NULL DEFAULT NULL,
                `codigo_campanha` BIGINT(20) NULL DEFAULT NULL,
                `dh_entrada` TIMESTAMP NULL DEFAULT NULL,
                `dh_entrega` TIMESTAMP NULL DEFAULT NULL,
                `operadora` VARCHAR(10) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_CAMPANHA_LISTA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_lista()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_lista`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `nome_lista` VARCHAR(200) NULL DEFAULT NULL,
                `numeros_lista` TEXT NULL DEFAULT NULL,
                `tipo_lista` CHAR(5) NULL DEFAULT NULL COMMENT 'D - Emails Digitados / I - Emails Importados',
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `fixa` ENUM('S', 'N') NULL DEFAULT 'N',
                `status` ENUM('A', 'I') NULL DEFAULT 'A' COMMENT 'A - Ativo I - Inativo',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_LISTA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_lista_telefones()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_lista_telefones`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_lista` INT(11) NULL DEFAULT NULL,
                `telefone` VARCHAR(15) NULL DEFAULT NULL,
                `nome` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_LISTA_TELEFONES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_lista_log()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_lista_log`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL COMMENT '0 - cron (considerar usuario que agendou )',
                `id_campanha` INT(11) NULL DEFAULT NULL,
                `id_transmissao` CHAR(50) NULL DEFAULT NULL,
                `status_transmissao` INT(11) NULL DEFAULT NULL,
                `msg_transmissao` VARCHAR(200) NULL DEFAULT NULL,
                `total_torpedos_enviados` CHAR(50) NULL DEFAULT NULL COMMENT 'total de torpedos enviados',
                `action` CHAR(50) NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_LISTA_LOG criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_master()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_master`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `allcance_lgn` VARCHAR(50) NOT NULL DEFAULT '0',
                `allcance_pwd` VARCHAR(50) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_MASTER criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_torpedo_user()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `torpedo_user`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `senha` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Senha do sistema Allcance',
                `nome_user` VARCHAR(50) NULL DEFAULT NULL,
                `celular_user` CHAR(50) NULL DEFAULT NULL COMMENT 'Serve como login no sistema Allcance',
                `email_user` VARCHAR(50) NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TORPEDO_USER criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_transportadora()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `transportadora`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cadastro` BIGINT(20) NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_usuario` BIGINT(20) NULL DEFAULT NULL,
                `situacao` ENUM('A', 'I', 'E') NULL DEFAULT 'A',
                `nome_razao_social` VARCHAR(100) NULL DEFAULT NULL,
                `cnpj_cpf` VARCHAR(14) NULL DEFAULT NULL,
                `tipo_pessoa` ENUM('F', 'J') NULL DEFAULT NULL,
                `id_tipo_log` INT(11) NULL DEFAULT NULL,
                `cep` VARCHAR(8) NULL DEFAULT NULL,
                `endereco` VARCHAR(50) NULL DEFAULT NULL,
                `numero` VARCHAR(10) NULL DEFAULT NULL,
                `complemento` VARCHAR(50) NULL DEFAULT NULL,
                `bairro` VARCHAR(50) NULL DEFAULT NULL,
                `cidade` VARCHAR(50) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `email` VARCHAR(50) NULL DEFAULT NULL,
                `telefone` VARCHAR(11) NULL DEFAULT NULL,
                `celular` VARCHAR(11) NULL DEFAULT NULL,
                `fax` VARCHAR(11) NULL DEFAULT NULL,
                `fone_gratuito` VARCHAR(11) NULL DEFAULT NULL,
                `informacoes_adicionais` TEXT NULL DEFAULT NULL,
                `pessoa_contato` VARCHAR(50) NULL DEFAULT NULL,
                `insc_estadual` VARCHAR(14) NULL DEFAULT NULL,
                `isento_icms` ENUM('S', 'N') NULL DEFAULT 'N',
                `ie_rg` VARCHAR(20) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` TIMESTAMP NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TRANSPORTADORA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_transportadora_placa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `transportadora_placa`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_transportadora` BIGINT(20) NULL DEFAULT NULL,
                `placa` VARCHAR(7) NULL DEFAULT NULL,
                `data_hora_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `situacao` ENUM('A', 'I') NULL DEFAULT 'A',
                `id_usuario` BIGINT(20) NULL DEFAULT NULL,
                `id_cadastro` BIGINT(20) NULL DEFAULT NULL,
                `uf` CHAR(2) NULL DEFAULT NULL,
                `rntc` VARCHAR(20) NULL DEFAULT NULL,
                `cod_antt` VARCHAR(30) NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TRANSPORTADORA_PLACA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_tributacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `tributacao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `sigla` VARCHAR(10) NULL DEFAULT NULL,
                `descricao` VARCHAR(100) NULL DEFAULT NULL,
                `situacao` ENUM('A', 'I') NULL DEFAULT 'A',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela TRIBUTACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_unidade()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `unidade`
            (
                `id` INT(2) NOT NULL,
                `sigla` CHAR(6) NULL DEFAULT NULL,
                `descricao` VARCHAR(20) NULL DEFAULT NULL,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A',
                `vlr_quebrado` ENUM('S', 'N') NULL DEFAULT 'N',
                `id_frentecaixa` INT(1) NULL DEFAULT NULL,
                `qtd_casas_decimais` INT(11) NULL DEFAULT NULL,
                `unidade_sped` CHAR(3) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela UNIDADE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_unidades_fracionamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `unidades_fracionamento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_unidade_pai` INT(11) NOT NULL,
                `id_unidade_filho` INT(11) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela UNIDADE_FRACIONAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_users()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `users`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `username` TEXT NOT NULL,
                `password` TEXT NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela USERS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_usuario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `usuario`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `nome_usuario` VARCHAR(40) NULL DEFAULT NULL,
                `login` VARCHAR(20) NULL DEFAULT NULL,
                `senha` VARCHAR(20) NULL DEFAULT NULL,
                `data_criacao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `ativo` ENUM('A', 'I') NULL DEFAULT 'A',
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                `login_master` ENUM('S', 'N') NULL DEFAULT 'N',
                `email` VARCHAR(50) NULL DEFAULT NULL,
                `data_desabilita` DATE NULL DEFAULT NULL,
                `config_venda` ENUM('N', 'E', 'I') NULL DEFAULT 'I' COMMENT 'Nova venda balcao, Outro cliente, Imprimir',
                `cod_empresa` INT(11) NULL DEFAULT NULL,
                `cnpj_cpf` VARCHAR(14) NULL DEFAULT NULL,
                `id_tipo_permissao_usuario` INT(11) NOT NULL DEFAULT '1',
                `percentual_desconto_autorizado` DECIMAL(10,2) NULL DEFAULT NULL,
                `versao_sistema` INT(1) NULL DEFAULT '1' COMMENT '1 - Versao WebControl  2 - Versao WebEmpresa',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela USUARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_vale_presente()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `vale_presente`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `valor` DECIMAL(15,2) NULL DEFAULT NULL,
                `data_emissao` DATETIME NULL DEFAULT NULL,
                `data_resgate` DATETIME NULL DEFAULT NULL,
                `validade` DATETIME NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `chave_acesso` VARCHAR(64) NULL DEFAULT NULL,
                `ativo` TINYINT(4) NULL DEFAULT '1' COMMENT '0 - Inativo 1 - Ativo',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VALE_PRESENTE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_vale_presente_historico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `vale_presente_historico`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_valepresente` INT(11) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `valor_atual` FLOAT(10,2) NULL DEFAULT NULL COMMENT 'Valor Atual = Valor anterior do vale presente - valor utilizado na venda',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VALE_PRESENTE_HISTORICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_vale_presente_new()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `vale_presente_new`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `id_produto` INT(11) NULL DEFAULT NULL,
                `data_emissao` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                `chave_acesso` VARCHAR(45) NULL DEFAULT NULL,
                `ativo` TINYINT(1) NULL DEFAULT '1',
                `qtde` INT(11) NULL DEFAULT NULL,
                `valor` FLOAT(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VALE_PRESENTE_NEW criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_valor()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `valor`
            (
                `COD_PRODUTO` INT(11) NULL DEFAULT NULL,
                `UNIDADE` TEXT NULL DEFAULT NULL,
                `VALOR` TEXT NULL DEFAULT NULL,
                `VALOR_MATERIAL` TEXT NULL DEFAULT NULL,
                `VALOR_MO` TEXT NULL DEFAULT NULL,
                `VALOR_LUCRO` TEXT NULL DEFAULT NULL,
                `PCT_LUCRO` TEXT NULL DEFAULT NULL,
                `VALOR_MINIMO` TEXT NULL DEFAULT NULL,
                `VALOR_PADRAO` TEXT NULL DEFAULT NULL)
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VALOR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_valor_extra_caixa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `valor_extra_caixa`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_abertura_caixa` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
                `id_cliente` INT(10) NULL DEFAULT NULL,
                `numero_caixa` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
                `valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `motivo` VARCHAR(500) NULL DEFAULT NULL,
                `data_entrada` DATETIME NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VALOR_EXTRA_CAIXA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_valor_inicial_caixa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `valor_inicial_caixa`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `vlr_inicial` DECIMAL(10,2) NULL DEFAULT '0.00',
                `vlr_final` DECIMAL(10,2) NULL DEFAULT NULL,
                `data_hora_inicial` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `data_hora_final` DATETIME NULL DEFAULT NULL,
                `id_cadastro` BIGINT(20) NOT NULL DEFAULT '0',
                `id_usuario` BIGINT(20) NOT NULL DEFAULT '0',
                `status` ENUM('I', 'F', 'M') NULL DEFAULT 'I' COMMENT 'I - Iniciado, F - Finalizado, M - finalizado pelo usuario Master',
                `num_caixa` INT(3) UNSIGNED ZEROFILL NULL DEFAULT '000',
                `vr_extra_caixa` DECIMAL(12,2) NULL DEFAULT '0.00',
                `origem_caixa` INT(1) NULL DEFAULT '1' COMMENT '1 Web - 2 Pdv  -  3 OffLine',
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VALOR_INICIAL_CAIXA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_valor_sangria()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `valor_sangria`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `data_hora` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `id_cadastro` BIGINT(20) NULL DEFAULT NULL,
                `id_usuario_retiro` BIGINT(20) NULL DEFAULT NULL,
                `valor` DECIMAL(10,2) NULL DEFAULT NULL,
                `id_valor_inicial_caixa` BIGINT(20) NULL DEFAULT NULL,
                `id_user_logado` BIGINT(20) NULL DEFAULT NULL,
                `obs` TEXT NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VALOR_SANGRIA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda`
            (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_tipo_venda` INT(10) UNSIGNED NULL DEFAULT '0' COMMENT '0 Venda - 1 Pedido - 2 Orçamento - 3 Ordem Serviço - 4 Consignação - 5 Locação - 6 Comanda - 7 Loja Online - 8 Assistencia Técnica',
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario_fecha_venda` INT(11) NULL DEFAULT NULL,
                `id_usuario_orcamento` INT(11) NULL DEFAULT NULL COMMENT 'usuario que fez o orçamento',
                `id_usuario_extorno` INT(11) NULL DEFAULT NULL COMMENT 'usuario que fez o extorno da venda',
                `id_usuario_autoriza_desconto` INT(11) NULL DEFAULT NULL,
                `id_usuario_exclusao` INT(11) NULL DEFAULT NULL,
                `id_funcionario` INT(11) UNSIGNED NULL DEFAULT NULL,
                `id_cliente` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_venda_local` BIGINT(20) NULL DEFAULT NULL,
                `id_forma_pagamento` CHAR(2) NULL DEFAULT NULL,
                `id_parcela` INT(11) NULL DEFAULT NULL,
                `id_nota_devolucao` INT(11) NULL DEFAULT NULL,
                `data_venda` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `hora_venda` TIME NULL DEFAULT '00:00:00',
                `situacao` ENUM('A', 'C', 'E', 'X', 'Y', 'I', 'G', 'N', 'F') NULL DEFAULT 'A' COMMENT 'A - Aguardando Aprovação, C - Encerrada , E Cancelada, X -Aprovado, Y-Aguardando Peças, I - Pedido Impresso Para Cozinha G - Comanda Agrupada, N - Não aprovado F - Faturado',
                `tipo_pgto` CHAR(2) NULL DEFAULT NULL COMMENT 'Dinheiro, Boleto, Cheque, Visa, Master',
                `sessao_venda` VARCHAR(100) NULL DEFAULT NULL,
                `data_orcamento` DATE NULL DEFAULT NULL COMMENT 'Data que foi feito o orçamento',
                `hora_orcamento` TIME NULL DEFAULT NULL COMMENT 'Hora que foi feito o orçamento',
                `orcamento` ENUM('S', 'N') NULL DEFAULT 'N',
                `data_validade` DATE NULL DEFAULT NULL COMMENT 'Até que data o orçamento é valido',
                `data_hora_extorno` DATETIME NULL DEFAULT NULL COMMENT 'Data e hora que foi feito o extorno',
                `descricao_extorno` TEXT NULL DEFAULT NULL COMMENT 'Descrição porque fez o extorno da venda',
                `descricao_venda` TEXT NULL DEFAULT NULL COMMENT 'Descriçaõ da venda',
                `a_vista` ENUM('P', 'V') NULL DEFAULT 'V',
                `origem_venda` ENUM('W', 'L', 'CF', 'CNF', 'CAT', 'OFF') NULL DEFAULT 'W' COMMENT 'W web-control, L loja-virtual, CF Cupom Fiscal, CNF Cupom nao Fiscal, CAT - Catálogo Online, OFF - Sinc Offiline',
                `pago` ENUM('S', 'N') NULL DEFAULT 'S',
                `valor_final_desconto` DECIMAL(10,2) NULL DEFAULT NULL,
                `nfe_status` ENUM('0', '1', '2', '3', '4', '5', '6', '7', '8') NULL DEFAULT '0' COMMENT '0 - não solicitado, 1 - solicitada, 2 - em andamento, 3 - cancelada, 4 - inutilizada, 5 -ok, 6 - falha,  7 - denegada, 8- rejeitada',
                `troco` DECIMAL(12,2) NULL DEFAULT NULL,
                `tp_nf` ENUM('NFE', 'NFC') NULL DEFAULT NULL,
                `ambiente_nf` INT(1) NULL DEFAULT NULL COMMENT '1 Producao , 2 Homologacao',
                `observacao` TEXT NULL DEFAULT NULL,
                `data_previsao_entrega` DATE NULL DEFAULT NULL,
                `hora_precisao_entrega` TIME NOT NULL DEFAULT '00:00:00',
                `prazo_devolucao` VARCHAR(5) NULL DEFAULT NULL,
                `valor_multa_diaria` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `valor_comissao_percentual` DECIMAL(12,2) NULL DEFAULT '0.00',
                `valor_comissao_em_reais` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `qtd_garantia` INT(11) NULL DEFAULT NULL,
                `tp_garantia` CHAR(1) NULL DEFAULT NULL COMMENT 'M - Meses - D Dia - S - Semana',
                `numero_nota_sefaz` INT(11) NULL DEFAULT NULL,
                `id_comanda` INT(11) NULL DEFAULT NULL,
                `id_abertura_caixa` BIGINT(20) NULL DEFAULT NULL,
                `data_excluido` DATETIME NULL DEFAULT NULL,
                `id_comandas_agrupadas` VARCHAR(100) NULL DEFAULT NULL,
                `id_venda_destino` INT(11) NULL DEFAULT NULL,
                `valor_entrada` DECIMAL(15,3) NULL DEFAULT '0.000',
                `id_cupom_venda` INT(11) NULL DEFAULT NULL,
                `id_objeto_cliente` INT(11) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` BIGINT(22) NULL DEFAULT NULL,
                `id_local` INT(11) NULL DEFAULT NULL,
                `situacao_anterior` CHAR(1) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_adiantamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_adiantamento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_venda` INT(11) NOT NULL,
                `id_abertura_caixa` INT(11) NOT NULL,
                `id_forma_pagamento` INT(11) NOT NULL,
                `valor` DECIMAL(15,3) NOT NULL,
                `data_adiantamento` DATETIME NOT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_ADIANTAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_consignacao_devolucao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_consignacao_devolucao`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `id_venda_item` INT(11) NULL DEFAULT NULL,
                `id_usuario` INT(11) NULL DEFAULT NULL,
                `qtd` FLOAT NULL DEFAULT NULL,
                `qtd_anterior` FLOAT NULL DEFAULT NULL,
                `data_cadastro` DATE NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_CONSIGNACAO_DEVOLUCAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_devolucao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_devolucao`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_venda` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data` DATE NULL DEFAULT NULL,
                `hora` TIME NULL DEFAULT NULL,
                `id_nota_credito` INT(11) NULL DEFAULT NULL,
                `valor_devolucao` DECIMAL(12,2) NULL DEFAULT NULL,
                `nota_credito` DECIMAL(12,2) NULL DEFAULT NULL,
                `finalizada` ENUM('S', 'N') NULL DEFAULT 'N',
                `nfe_status` ENUM('0', '1', '2', '3', '4', '5', '6', '7', '8') NULL DEFAULT '0' COMMENT '0 - não solicitado, 1 - solicitada, 2 - em andamento, 3 - cancelada, 4 - inutilizada, 5 -ok, 6 - falha, 7 - denegada, 8- rejeitada',
                `doc_cliente` VARCHAR(14) NULL DEFAULT NULL,
                `id_cliente` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_DEVOLUCAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_informacoes()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_informacoes`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NOT NULL,
                `valor_frete` DECIMAL(12,2) NOT NULL,
                `info_adicional` LONGTEXT NULL DEFAULT NULL,
                `volumes` INT(11) NULL DEFAULT '1',
                `doc_consumidor` VARCHAR(14) NULL DEFAULT NULL,
                `desc_volume` VARCHAR(10) NULL DEFAULT NULL,
                `nat_operacao` VARCHAR(50) NULL DEFAULT NULL,
                `data_saida` DATE NULL DEFAULT NULL,
                `hora_saida` TIME NULL DEFAULT NULL,
                `frete_por_conta` INT(1) NULL DEFAULT '0',
                `id_transportadora` INT(11) NULL DEFAULT '0',
                `id_placa` INT(11) NULL DEFAULT '0',
                `indicador_presenca` INT(11) NULL DEFAULT '0',
                `outras_despesas` DECIMAL(15,3) NULL DEFAULT NULL,
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `peso_bruto` DECIMAL(15,3) NULL DEFAULT NULL,
                `peso_liquido` DECIMAL(15,3) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `numeracao` VARCHAR(50) NULL DEFAULT NULL,
                `ordem_compra` VARCHAR(15) NULL DEFAULT NULL,
                `tipo_operacao` INT(1) NULL DEFAULT '1',
                `consumidor_final` INT(1) NULL DEFAULT '0',
                `nf_forma_pgto` INT(1) NULL DEFAULT '0' COMMENT '0 - A Vista   1 - A Prazo   2 - Outros',
                `id_id_itens` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_INFORMACOES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_itens()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_itens`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `qtd` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `id_venda` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_produto` INT(10) UNSIGNED NULL DEFAULT NULL,
                `nome_produto` VARCHAR(120) NULL DEFAULT NULL,
                `preco_tabela` DECIMAL(25,15) NULL DEFAULT NULL,
                `nome_classificacao` VARCHAR(50) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(20) NULL DEFAULT NULL,
                `preco_venda` DECIMAL(25,15) NULL DEFAULT NULL,
                `id_autoriza_desconto` INT(11) NULL DEFAULT NULL,
                `id_autoriza_cancelamento` INT(11) NULL DEFAULT '0',
                `id_unidade` INT(11) UNSIGNED NULL DEFAULT '0',
                `estornado` ENUM('S', 'N') NULL DEFAULT 'N',
                `data_hora_estorno` DATETIME NULL DEFAULT NULL,
                `desconto` ENUM('S', 'N') NULL DEFAULT 'N',
                `cfop` INT(11) NULL DEFAULT NULL,
                `percentual_desconto` DECIMAL(10,4) NULL DEFAULT NULL,
                `valor_preco_custo` DECIMAL(12,3) NULL DEFAULT NULL,
                `seq_ecf` SMALLINT(6) NULL DEFAULT NULL,
                `observacoes_cozinha` VARCHAR(255) NULL DEFAULT NULL,
                `id_grade` BIGINT(20) NULL DEFAULT NULL,
                `id_promocao` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
                `id_kit` INT(11) NULL DEFAULT NULL,
                `informacoes_item` TEXT NULL DEFAULT NULL,
                `atacado_varejo` ENUM('A', 'V') NOT NULL DEFAULT 'V',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `xped` VARCHAR(20) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_ITENS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_itens_automoveis()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_itens_automoveis`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_venda_item` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `veicProd` TEXT NULL DEFAULT NULL,
                `tpOp` INT(11) NULL DEFAULT NULL,
                `chassi` VARCHAR(17) NULL DEFAULT NULL,
                `cCor` VARCHAR(4) NULL DEFAULT NULL,
                `xCor` VARCHAR(40) NULL DEFAULT NULL,
                `pot` VARCHAR(4) NULL DEFAULT NULL,
                `cilin` VARCHAR(4) NULL DEFAULT NULL,
                `pesoL` VARCHAR(9) NULL DEFAULT NULL,
                `pesoB` VARCHAR(9) NULL DEFAULT NULL,
                `nSerie` VARCHAR(9) NULL DEFAULT NULL,
                `tpComb` VARCHAR(9) NULL DEFAULT NULL,
                `nMotor` VARCHAR(21) NULL DEFAULT NULL,
                `cmt` VARCHAR(9) NULL DEFAULT NULL,
                `dist` VARCHAR(4) NULL DEFAULT NULL,
                `anoMod` INT(11) NULL DEFAULT NULL,
                `anoFab` INT(11) NULL DEFAULT NULL,
                `tpPint` CHAR(1) NULL DEFAULT NULL,
                `tpVeic` INT(11) NULL DEFAULT NULL,
                `espVeic` INT(11) NULL DEFAULT NULL,
                `vin` CHAR(1) NULL DEFAULT NULL,
                `condVeic` INT(11) NULL DEFAULT NULL,
                `cMod` INT(11) NULL DEFAULT NULL,
                `cCorDENATRAN` INT(11) NULL DEFAULT NULL,
                `lota` INT(11) NULL DEFAULT NULL,
                `tpRest` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_ITENS_AUTOMOVEIS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_itens_devolucao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_itens_devolucao`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_venda_devol` INT(10) NULL DEFAULT NULL,
                `id_venda` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_venda_itens` INT(10) UNSIGNED NULL DEFAULT NULL,
                `qtd` DECIMAL(10,3) UNSIGNED NULL DEFAULT NULL,
                `nome_produto` VARCHAR(100) NULL DEFAULT NULL,
                `codigo_barra` VARCHAR(22) NULL DEFAULT NULL,
                `preco_venda` DECIMAL(10,2) NULL DEFAULT NULL,
                `data` DATE NULL DEFAULT NULL,
                `hora` TIME NULL DEFAULT NULL,
                `motivo_devol` CHAR(3) NULL DEFAULT NULL,
                `seq_item_nota` INT(2) NULL DEFAULT NULL,
                `finalizados` ENUM('S', 'N') NULL DEFAULT 'N',
                `cfop` INT(6) NULL DEFAULT NULL,
                `id_grade` BIGINT(20) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_ITENS_DEVOLUCAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_juros_parcelamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_juros_parcelamento`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `valor` DECIMAL(10,1) NULL DEFAULT NULL,
                `descricao` VARCHAR(10) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_JUROS_PARCELAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_locacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_locacao`
            (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_venda` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
                `id_modelo_contrato` INT(11) NULL DEFAULT NULL,
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL,
                `valor` DECIMAL(12,2) NULL DEFAULT NULL,
                `valor_multa_diaria` DECIMAL(12,2) NULL DEFAULT NULL,
                `tipo_faturamento` ENUM('H', 'D', 'S', 'Q', 'M', 'T') NULL DEFAULT NULL COMMENT 'H - Horista, D - Diária, S - Semanal, Q - Quinzenal, M - Mensal, T - Trimestral',
                `fiador` CHAR(1) NULL DEFAULT '0',
                `nome_fiador` VARCHAR(255) NULL DEFAULT NULL,
                `rg_fiador` CHAR(20) NULL DEFAULT NULL,
                `cpf_fiador` CHAR(20) NULL DEFAULT NULL,
                `end_fiador` VARCHAR(200) NULL DEFAULT NULL,
                `fone_fiador` CHAR(20) NULL DEFAULT NULL,
                `fone_fiador2` CHAR(20) NULL DEFAULT NULL,
                `obs` TEXT NULL DEFAULT NULL,
                `status_loc` CHAR(1) NULL DEFAULT 'R' COMMENT 'L - Locado, R - Reservado, D - Devolvido, T - Teste/Prova, C - Cancelado',
                `data_hora_acao` DATETIME NULL DEFAULT NULL,
                `valor_desconto` DECIMAL(12,2) NULL DEFAULT NULL,
                `data_devolucao` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_LOCACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_nfse_informacoes()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_nfse_informacoes`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NOT NULL,
                `status_nota` CHAR(1) NULL DEFAULT '3',
                `protocolo` VARCHAR(20) NOT NULL,
                `ambiente` CHAR(1) NULL DEFAULT 'P',
                `protocolo_cancelamento` VARCHAR(20) NULL DEFAULT NULL,
                `data_cancelamento` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_NFSE_INFORMACOES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_notas_eletronicas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_notas_eletronicas`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NOT NULL,
                `data_hora` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `tipo_nota` ENUM('NFE', 'NFC', 'NFS', 'CFE') NOT NULL DEFAULT 'NFE',
                `status` ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9') NULL DEFAULT '1' COMMENT '0 - não solicitado, 1 - solicitada, 2 - em andamento, 3 - cancelada, 4 - inutilizada, 5 -ok, 6 - falha, 7 - denegada, 8- rejeitada, 9 Contingencia',
                `numero_nota` BIGINT(20) NULL DEFAULT NULL,
                `ambiente_nf` INT(1) NULL DEFAULT '1' COMMENT '1 Producao , 2 Homologacao',
                `danfe` VARCHAR(44) NULL DEFAULT NULL,
                `xml` LONGTEXT NULL DEFAULT NULL,
                `LOTE` INT(11) NULL DEFAULT NULL,
                `QTDE` INT(11) NULL DEFAULT NULL,
                `ARQUIVO` VARCHAR(100) NULL DEFAULT NULL,
                `RETORNO` LONGTEXT NULL DEFAULT NULL,
                `LINK_NFS` VARCHAR(255) NULL DEFAULT NULL,
                `data_cancelamento` DATETIME NULL DEFAULT NULL,
                `xml_cancelamento` LONGTEXT NULL DEFAULT NULL,
                `retorno_cancelamento_prefeitura` LONGTEXT NULL DEFAULT NULL,
                `protocolo` VARCHAR(60) NULL DEFAULT '',
                `vr_total_prod` DECIMAL(12,2) NOT NULL,
                `vr_total_nota` DECIMAL(12,2) NOT NULL,
                `vr_total_desconto` DECIMAL(12,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_NOTAS_ELETRONICAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_notas_eletronicas_lixo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_notas_eletronicas_lixo`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NOT NULL,
                `data_hora` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `tipo_nota` ENUM('NFE', 'NFC', 'NFS', 'CFE') NOT NULL DEFAULT 'NFE',
                `status_nota` ENUM('0', '1', '2', '3', '4', '5', '6', '7', '8') NULL DEFAULT '1' COMMENT '0 - não solicitado, 1 - solicitada, 2 - em andamento, 3 - cancelada, 4 - inutilizada, 5 -ok, 6 - falha, 7 - denegada, 8- rejeitada, 9 Contingencia',
                `numero_nota` BIGINT(20) NULL DEFAULT NULL,
                `ambiente_nf` INT(1) NULL DEFAULT '1' COMMENT '1 Producao , 2 Homologacao',
                `danfe` VARCHAR(44) NULL DEFAULT NULL,
                `xml` TEXT NULL DEFAULT NULL,
                `LOTE` INT(11) NULL DEFAULT NULL,
                `QTDE` INT(11) NULL DEFAULT NULL,
                `ARQUIVO` VARCHAR(100) NULL DEFAULT NULL,
                `RETORNO` TEXT NULL DEFAULT NULL,
                `LINK_NFS` VARCHAR(255) NULL DEFAULT NULL,
                `data_cancelamento` DATETIME NULL DEFAULT NULL,
                `xml_cancelamento` LONGTEXT NULL DEFAULT NULL,
                `retorno_cancelamento_prefeitura` LONGTEXT NULL DEFAULT NULL,
                `protocolo` VARCHAR(30) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_NOTAS_ELETRONICAS_LIXO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_optica()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_optica`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `data_receita` DATE NULL DEFAULT NULL,
                `medico` VARCHAR(45) NULL DEFAULT NULL,
                `crm` VARCHAR(45) NULL DEFAULT NULL,
                `uf` VARCHAR(45) NULL DEFAULT NULL,
                `convenio` VARCHAR(45) NULL DEFAULT NULL,
                `ponte` VARCHAR(45) NULL DEFAULT NULL,
                `altura_horizontal` VARCHAR(45) NULL DEFAULT NULL,
                `altura_vertical` VARCHAR(45) NULL DEFAULT NULL,
                `diagonal` VARCHAR(45) NULL DEFAULT NULL,
                `armacao` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_esferico` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_cilindrico` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_eixo` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_adicao` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_dnp` VARCHAR(45) NULL DEFAULT NULL,
                `longe_od_altura` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_esferico` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_cilindrico` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_eixo` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_adicao` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_dnp` VARCHAR(45) NULL DEFAULT NULL,
                `longe_oe_altura` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_esferico` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_cilindrico` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_eixo` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_adicao` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_dnp` VARCHAR(45) NULL DEFAULT NULL,
                `perto_od_altura` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_esferico` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_cilindrico` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_eixo` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_adicao` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_dnp` VARCHAR(45) NULL DEFAULT NULL,
                `perto_oe_altura` VARCHAR(45) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_OPTICA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_pagamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_pagamento`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NOT NULL,
                `id_forma_pgto` INT(11) NOT NULL,
                `valor_pgto` DECIMAL(12,2) NOT NULL,
                `cmc7` VARCHAR(30) NULL DEFAULT NULL,
                `vencimento` DATE NULL DEFAULT NULL,
                `doc_cheque` VARCHAR(20) NULL DEFAULT NULL,
                `codigo_consulta` INT(11) NULL DEFAULT NULL,
                `qtd_parcela` INT(2) NULL DEFAULT '0',
                `cod_aut_cartao` VARCHAR(50) NULL DEFAULT NULL,
                `id_credenciadora` INT(11) NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `cnpj_credenciadora` VARCHAR(20) NULL DEFAULT NULL,
                `vlr_troco` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
                `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_PAGAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_pagamento_cheque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_pagamento_cheque`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `cmc7_1` CHAR(8) NULL DEFAULT NULL,
                `cmc7_2` CHAR(10) NULL DEFAULT NULL,
                `cmc7_3` VARCHAR(12) NULL DEFAULT NULL,
                `id_Venda` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_PAGAMENTO_CHEQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_pagamento_ecommerce()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_pagamento_ecommerce`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `amount` FLOAT NULL DEFAULT NULL,
                `cielo_order` TEXT NULL DEFAULT NULL,
                `criacao` DATE NULL DEFAULT NULL,
                `customer_name` TEXT NULL DEFAULT NULL,
                `customer_phone` TEXT NULL DEFAULT NULL,
                `customer_identity` TEXT NULL DEFAULT NULL,
                `customer_email` TEXT NULL DEFAULT NULL,
                `address_zipcode` TEXT NULL DEFAULT NULL,
                `address_district` TEXT NULL DEFAULT NULL,
                `address_city` TEXT NULL DEFAULT NULL,
                `address_state` TEXT NULL DEFAULT NULL,
                `address_line` TEXT NULL DEFAULT NULL,
                `address_number` TEXT NULL DEFAULT NULL,
                `method_type` INT(11) NULL DEFAULT NULL,
                `method_brand` INT(11) NULL DEFAULT NULL,
                `maskedcreditcard` TEXT NULL DEFAULT NULL,
                `installments` INT(11) NULL DEFAULT NULL,
                `status` INT(11) NULL DEFAULT NULL COMMENT '0 - Criada | 1 - Pendente | 2 - Pago | 3 - Negado | 4 - Expirado | 5 - Cancelado | 6 - Não finalizado | 7 - Autorizado | 8 - ChargeBack',
                `tid` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_PAGAMENTO_ECOMMERCE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_parcelas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_parcelas`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_PARCELAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_venda_pgto_temp()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `venda_pgto_temp`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_venda` INT(11) NOT NULL,
                `id_forma_pgto` INT(11) NOT NULL,
                `valor_pgto` DECIMAL(12,2) NOT NULL,
                `cmc7` VARCHAR(30) NULL DEFAULT NULL,
                `vencimento` DATE NULL DEFAULT NULL,
                `doc_cheque` VARCHAR(20) NULL DEFAULT NULL,
                `codigo_consulta` INT(11) NULL DEFAULT NULL,
                `qtd_parcela` INT(2) NULL DEFAULT '0',
                `cod_aut_cartao` VARCHAR(50) NULL DEFAULT NULL,
                `id_credenciadora` INT(11) NULL DEFAULT NULL,
                `vlr_troco` DECIMAL(15,2) NULL DEFAULT NULL,
                `data_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDA_PGTO_TEMP criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_vendas_funcionario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `vendas_funcionario`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_funcionario` INT(11) NOT NULL,
                `id_venda` INT(11) NOT NULL,
                `valor` DECIMAL(15,2) NOT NULL,
                `data` DATE NOT NULL,
                `pago` ENUM('S', 'N') NULL DEFAULT 'N',
                `data_pagamento` DATETIME NULL DEFAULT NULL,
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VENDAS_FUNCIONARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_view_venda_parcelas()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `view_venda_parcelas`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VIEW_VENDA_PARCELAS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_vp_historico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `vp_historico`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_vale_presente_new` INT(11) NULL DEFAULT NULL,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `id_venda` INT(11) NULL DEFAULT NULL,
                `valor_atual` FLOAT(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela VP_HISTORICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_wc_menu()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `wc_menu`
            (
                `id` BIGINT(21) NOT NULL AUTO_INCREMENT,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WC_MENU criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_wc_permissao_menu()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `wc_permissao_menu`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_usuario` BIGINT(20) NOT NULL,
                `id_submenu` INT(11) NOT NULL,
                `permissao` INT(1) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WC_PERMISSAO_MENU criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_wc_submenu()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `wc_submenu`
            (
                `id` BIGINT(21) NOT NULL AUTO_INCREMENT,
                `id_menu` BIGINT(21) NULL DEFAULT NULL,
                `descricao` VARCHAR(50) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WC_SUBMENU criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_configuracoes_sistema()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `webc_configuracoes_sistema`
            (
                `id_config` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `caminho_logomarca` VARCHAR(100) NULL DEFAULT '0',
                `cor_sistema` VARCHAR(10) NULL DEFAULT '0',
                `email_fc` VARCHAR(50) NULL DEFAULT NULL,
                PRIMARY KEY (`id_config`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_CONFIGURACOES_SISTEMA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_grupo_usuarios()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS `webc_grupo_usuarios`
            (
                `id_grupo_usuarios` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `id_tipo_permissao_usuario` INT(11) NOT NULL DEFAULT '1',
                `nome` VARCHAR(50) NOT NULL,
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `id_usuario_cadastro` INT(11) NOT NULL,
                PRIMARY KEY (`id_grupo_usuarios`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_GRUPO_USUARIOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_grupo_usuarios_cadastro()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_grupo_usuarios_cadastro`
            (
                `id_grupo_usuarios_cadastro` INT(11) NOT NULL AUTO_INCREMENT,
                `id_grupo_usuarios` INT(11) NOT NULL DEFAULT '0',
                `id_usuario` INT(11) NOT NULL DEFAULT '0',
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `id_usuario_cadastro` INT(11) NOT NULL,
                PRIMARY KEY (`id_grupo_usuarios_cadastro`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_GRUPO_USUARIOS_CADASTRO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_modulo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_modulo`
            (
                `id_modulo` INT(21) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(50) NOT NULL,
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_modulo`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_MODULO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_permissao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_permissao`
            (
                `id_permissao` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(70) NOT NULL DEFAULT '',
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `id_usuario_cadastro` INT(11) NOT NULL,
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_permissao`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_PERMISSAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_permissao_grupo_usuarios()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_permissao_grupo_usuarios`
            (
                `permissao_grupo_usuarios` INT(11) NOT NULL AUTO_INCREMENT,
                `id_grupo_usuarios` INT(11) NOT NULL,
                `id_permissao` INT(11) NOT NULL,
                `id_usuario_cadastro` INT(11) NOT NULL,
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`permissao_grupo_usuarios`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_PERMISSAO_GRUPO_USUARIOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_permissao_modulo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_permissao_modulo`
            (
                `id_permissao_modulo` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_permissao` INT(10) UNSIGNED NOT NULL,
                `id_modulo` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_sub_modulo` INT(11) UNSIGNED NULL DEFAULT NULL,
                `id_cadastro` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_usuario` INT(10) UNSIGNED NULL DEFAULT NULL,
                `id_situacao` INT(10) UNSIGNED NOT NULL DEFAULT '1',
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_permissao_modulo`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_PERMISSAO_MODULO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_permissao_usuario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_permissao_usuario`
            (
                `id_permissao_usuario` INT(11) NOT NULL AUTO_INCREMENT,
                `id_permissao` INT(11) NOT NULL,
                `id_usuario` INT(11) NOT NULL,
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `id_usuario_cadastro` INT(11) NOT NULL,
                PRIMARY KEY (`id_permissao_usuario`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_PERMISSAO_USUARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_posto_bomba()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_posto_bomba`
            (
                `id_bomba` INT(11) NOT NULL AUTO_INCREMENT,
                `numero` INT(11) NOT NULL,
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `id_cadastro` INT(11) NOT NULL,
                PRIMARY KEY (`id_bomba`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_POSTO_BOMBA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_posto_bomba_bico()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_posto_bomba_bico`
            (
                `id_bomba_bico` INT(11) NOT NULL AUTO_INCREMENT,
                `id_tanque` INT(11) NULL DEFAULT NULL,
                `id_bomba` INT(11) NOT NULL,
                `numero` INT(11) NOT NULL,
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `encerrante` DECIMAL(15,3) NULL DEFAULT NULL,
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_bomba_bico`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_POSTO_BOMBA_BICO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_posto_tanque()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_posto_tanque`
            (
                `id_tanque` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NOT NULL,
                `numero` INT(11) NOT NULL,
                `conteudo` VARCHAR(60) NOT NULL,
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `cod_ANP` VARCHAR(60) NULL DEFAULT NULL,
                PRIMARY KEY (`id_tanque`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_POSTO_TANQUE criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_situacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_situacao`
            (
                `id_situacao` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(20) NULL DEFAULT NULL,
                `dt_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_situacao`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_SITUACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_sub_modulo()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_sub_modulo`
            (
                `id_sub_modulo` INT(21) NOT NULL AUTO_INCREMENT,
                `id_modulo` INT(21) NOT NULL,
                `nome` VARCHAR(50) NOT NULL,
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_sub_modulo`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_SUB_MODULO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_tipo_permissao_usuario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_tipo_permissao_usuario`
            (
                `id_tipo_permissao_usuario` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(50) NULL DEFAULT NULL,
                `id_situacao` INT(11) NULL DEFAULT '1',
                `dt_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_tipo_permissao_usuario`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_TIPO_PERMISSAO_USUARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_tipo_venda()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_tipo_venda`
            (
                `id_tipo_venda` INT(11) NOT NULL AUTO_INCREMENT,
                `nome` VARCHAR(50) NOT NULL,
                `id_situacao` INT(11) NOT NULL DEFAULT '1',
                `dt_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_tipo_venda`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_TIPO_VENDA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_usuario()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_usuario`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `nome_usuario` VARCHAR(40) NULL DEFAULT NULL,
                `login` VARCHAR(20) NULL DEFAULT NULL,
                `senha` VARCHAR(20) NULL DEFAULT NULL,
                `data_criacao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `ativo` ENUM('A', 'I', 'E') NULL DEFAULT 'A',
                `id_funcionario` INT(11) NULL DEFAULT NULL,
                `login_master` ENUM('S', 'N', 'V') NULL DEFAULT 'N',
                `email` VARCHAR(50) NULL DEFAULT NULL,
                `data_desabilita` DATE NULL DEFAULT NULL,
                `percentual_desconto_autorizado` DECIMAL(10,2) NULL DEFAULT NULL,
                `percentual_desconto_item` DECIMAL(10,2) NULL DEFAULT NULL,
                `cnpj_cpf` VARCHAR(14) NULL DEFAULT NULL,
                `id_tipo_permissao_usuario` INT(11) NULL DEFAULT '1',
                `array_permissao` TEXT NULL DEFAULT NULL,
                `agenda` TINYINT(4) NULL DEFAULT '1',
                `horario_inicio` TIME NULL DEFAULT '00:00:00',
                `horario_fim` TIME NULL DEFAULT '23:59:59',
                `data_alteracao` DATETIME NULL DEFAULT NULL,
                `data_sincronismo` DATETIME NULL DEFAULT NULL,
                `id_off` INT(11) NULL DEFAULT NULL,
                `dias_semana` VARCHAR(13) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_USUARIO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_vfx_syncloja()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_vfx_syncloja`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `data_inicio` TIMESTAMP NULL DEFAULT NULL,
                `data_final` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `situacao` VARCHAR(50) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_VFX_SYNCLOJA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_visualizacao_imediata()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_visualizacao_imediata`
            (
                `id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
                `tela` VARCHAR(15) NULL DEFAULT NULL,
                `campo` VARCHAR(250) NULL DEFAULT NULL,
                `nomenclatura` VARCHAR(30) NULL DEFAULT NULL,
                `mascara` VARCHAR(50) NULL DEFAULT NULL,
                `ordem` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_VISUALIZACAO_IMEDIATA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_webc_visualizacao_imediata_dados()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `webc_visualizacao_imediata_dados`
            (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(10) UNSIGNED NOT NULL,
                `campos` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WEBC_VISUALIZACAO_IMEDIATA_DADOS criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_whatsapp_campanha()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `whatsapp_campanha`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) UNSIGNED NOT NULL,
                `id_lista` INT(11) UNSIGNED NOT NULL,
                `listas` TEXT NULL DEFAULT NULL,
                `nome_campanha` VARCHAR(200) NULL DEFAULT NULL,
                `texto` VARCHAR(1000) NULL DEFAULT NULL,
                `data_envio` DATE NULL DEFAULT NULL,
                `hora_envio` TIME NULL DEFAULT NULL,
                `status_campanha` CHAR(1) NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `imagem` VARCHAR(100) NULL DEFAULT NULL,
                `titulo` VARCHAR(50) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WHATSAPP_CAMPANHA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_whatsapp_campanha_agendamento()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `whatsapp_campanha_agendamento`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_whatsapp_campanha` INT(11) UNSIGNED NULL DEFAULT NULL,
                `data_inicio` DATETIME NULL DEFAULT NULL,
                `data_fim` DATETIME NULL DEFAULT NULL,
                `hora_agendamento` TIME NULL DEFAULT NULL,
                `status_agendamento` CHAR(1) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WHATSAPP_CAMPANHA_AGENDAMENTO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_whatsapp_campanha_fixa()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `whatsapp_campanha_fixa`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `nome_campanha` VARCHAR(200) NOT NULL,
                `texto` VARCHAR(1000) NOT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WHATSAPP_CAMPANHA_FIXA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_whatsapp_campanha_fixa_ignorar()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `whatsapp_campanha_fixa_ignorar`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) UNSIGNED NOT NULL,
                `id_whatsapp_campanha_fixa` INT(11) UNSIGNED NOT NULL,
                `data_exclusao` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WHATSAPP_CAMPANHA_FIXA_IGNORAR criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_whatsapp_lista()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `whatsapp_lista`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) UNSIGNED NOT NULL,
                `nome_lista` VARCHAR(200) NULL DEFAULT NULL,
                `numeros_lista` TEXT NULL DEFAULT NULL,
                `tipo_lista` CHAR(5) NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT NULL,
                `fixa` ENUM('S', 'N') NULL DEFAULT 'N',
                `status` ENUM('A', 'I') NULL DEFAULT 'A',
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WHATSAPP_LISTA criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_whatsapp_lista_telefones()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `whatsapp_lista_telefones`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) UNSIGNED NOT NULL,
                `id_lista` INT(11) UNSIGNED NOT NULL,
                `telefone` VARCHAR(15) NULL DEFAULT NULL,
                `nome` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WHATSAPP_LISTA_TELEFONES criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_whatsapp_log()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `whatsapp_log`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) UNSIGNED NOT NULL,
                `id_usuario` INT(11) UNSIGNED NOT NULL,
                `id_campanha` INT(11) UNSIGNED NOT NULL,
                `id_transmissao` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
                `status_transmissao` INT(1) UNSIGNED NULL DEFAULT NULL,
                `total_msg_enviadas` INT(11) UNSIGNED NULL DEFAULT NULL,
                `msg_transmissao` VARCHAR(50) NULL DEFAULT NULL,
                `action` CHAR(1) NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WHATSAPP_LOG criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_whatsapp_master()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `whatsapp_master`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `allcance_lgn` VARCHAR(50) NULL DEFAULT NULL,
                `allcance_pwd` VARCHAR(50) NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WHATSAPP_MASTER criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_whatsapp_transacao()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `whatsapp_transacao`
            (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) UNSIGNED NOT NULL,
                `id_usuario` INT(11) UNSIGNED NOT NULL,
                `id_campanha` INT(11) UNSIGNED NOT NULL,
                `id_transmissao` BIGINT(20) NULL DEFAULT NULL,
                `id_mensagem` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
                `status_transmissao` INT(1) NULL DEFAULT NULL COMMENT '1-unverified, 2-waiting, 3-sending, 4-sent, 5-delivered, 6-invalid number, 7-inactive whatsapp, 8-read, 9-closed',
                `msg_transmissao` VARCHAR(1000) NULL DEFAULT NULL,
                `telefone` BIGINT(14) NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WHATSAPP_TRANSACAO criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_table_whatsapp_user()
    {
        try {

            $conn = $this->useDataBase();
            $data_base_name = $this->create_base_dados->getDatabase();
            $sql = "use $data_base_name";
            $conn->exec($sql);
            $sql = "CREATE TABLE IF NOT EXISTS  `whatsapp_user`
            (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_cadastro` INT(11) NULL DEFAULT NULL,
                `senha` VARCHAR(50) NULL DEFAULT NULL,
                `nome_user` VARCHAR(50) NULL DEFAULT NULL,
                `celular_user` VARCHAR(50) NULL DEFAULT NULL,
                `email_user` VARCHAR(50) NULL DEFAULT NULL,
                `dt_creation` TIMESTAMP NULL DEFAULT NULL,
                `dt_last_update` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`))
            ENGINE = MyISAM
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_bin;";
            $conn->exec($sql);
            print("Tabela WHATSAPP_USER criada com sucesso.<br>");
        } catch (PDOException $e) { }
    }

    function create_acesso_webcontrol($codloja,$nomefantasia,$cpfsocio1,$email,$login,$senha, $uf){

        $conn = $this->useDataBase();
        $data_base_name = $this->create_base_dados->getDatabase();
        $sql = "use $data_base_name";
        $conn->exec($sql);
        
        // Verificando se o Funcionario Master ja existe, se existir, pego o seu ID
        
        $sql = "SELECT id 
                 FROM funcionario
                 WHERE id_cadastro = '$codloja' 
                 AND tp_funcionario = 'P'";
        $pdo = $conn->prepare($sql);
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
    
        if (count($result) == 0) {
            $id_funcionario = 0;
        }
    
        if ( $id_funcionario == 0 ){
            // Cadastrando o Vendedor Padrao.
            $sql = "INSERT INTO funcionario
                    ( nome, cpf, id_cadastro, tp_funcionario )
                    VALUES
                    ( 'Funcionario MASTER', '$cpfsocio1', '$codloja', 'P' )";
            $pdo = $conn->prepare($sql);
            $pdo->execute();
            $id_funcionario = $conn->lastInsertId();
        }

        // Inicio Usuário

        $sql = "SELECT id 
                 FROM webc_usuario
                 WHERE id_cadastro = '$codloja'";
        $pdo = $conn->prepare($sql);
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) == 0) {
            $id_usuario = 0;
        }
    
        if ( $id_usuario == 0 ){
            // Cadastrando o usuario
            $sql = "INSERT INTO webc_usuario
                 (nome_usuario, login, senha, id_cadastro, login_master, id_funcionario, cnpj_cpf, email )
                 VALUES
                ('FUNCIONARIO MASTER','$login','$senha','$codloja','S', '$id_funcionario', '$cpfsocio1', '$email')";
            $pdo = $conn->prepare($sql);
            $pdo->execute();
            $id_usuario = $conn->lastInsertId();
        }

        // Fim Usuário
        
        // Cadastrando o Permissoes
        
        // Selecionando todos os modulos 
        $sql = "SELECT id_permissao FROM webc_permissao";
        $pdo = $conn->prepare($sql);
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        $id_permissao = '';
        $cont = 0;
        while ( $cont < count($result) ) {
            $id_permissao .= $result['id_permissao'].',';
            $cont++;
        }
    
        $id_permissao = substr($id_permissao,0,strlen($id_permissao)-1);
        // Gravando as permissoes
        $sql = "UPDATE webc_usuario 
                  SET array_permissao = '$id_permissao'
                  WHERE id = '$id_usuario'";
        $pdo = $conn->prepare($sql);
        $pdo->execute();
        
        // Criando CLIENTE BALCAO
    
        $sql = "INSERT INTO cliente
                    ( 
                        id_cadastro, id_usuario, tipo_pessoa, cnpj_cpf, nome, razao_social, id_tipo_log, 
                        endereco, bairro, cidade, uf
                    )
                 VALUES
                     ( 
                     '$codloja' , '$id_usuario', 'B', '00000000000', 'CLIENTE BALCAO', 'CLIENTE BALCAO', '1' ,
                    'CLIENTE BALCAO', 'CLIENTE BALCAO', 'CLIENTE BALCAO', '$uf'
                    )";
        $pdo = $conn->prepare($sql);
        $pdo->execute();
        
    }

}
