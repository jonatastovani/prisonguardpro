DROP TRIGGER IF EXISTS cadastros_UPDATE_BEFORE;

delimiter $
CREATE TRIGGER cadastros_UPDATE_BEFORE
BEFORE UPDATE ON cadastros
FOR EACH ROW

begin
	DECLARE valorAntigo blob;
	DECLARE valorNovo blob;
	DECLARE idPresoRegistroAlteracao int;
    
    IF NEW.IDPRESO IS NULL OR NEW.IDPRESO = 0 THEN
		SET NEW.IDPRESO = NULL;
        SET idPresoRegistroAlteracao = OLD.IDPRESO;
	ELSE
        SET idPresoRegistroAlteracao = NEW.IDPRESO;
	END IF;
    
    IF NEW.IDCIDADENASC IS NULL OR NEW.IDCIDADENASC = 0 THEN
		SET NEW.IDCIDADENASC = NULL;
	END IF;
    IF NEW.IDESTADONASC IS NULL OR NEW.IDESTADONASC = 0 THEN
		SET NEW.IDESTADONASC = NULL;
	END IF;
    IF NEW.RG IS NULL OR NEW.RG = '' THEN
		SET NEW.RG = NULL;
	END IF;
    IF NEW.CPF IS NULL OR NEW.CPF = '' OR NEW.CPF = 0 THEN
		SET NEW.CPF = NULL;
	END IF;
    IF NEW.OUTRODOC IS NULL OR NEW.OUTRODOC = '' THEN
		SET NEW.OUTRODOC = NULL;
	END IF;
    IF NEW.PAI IS NULL OR NEW.PAI = '' THEN
		SET NEW.PAI = NULL;
	END IF;
    IF NEW.MAE IS NULL OR NEW.MAE = '' THEN
		SET NEW.MAE = NULL;
	END IF;
    IF NEW.OBSERVACOES IS NULL OR NEW.OBSERVACOES = '' THEN
		SET NEW.OBSERVACOES = NULL;
	END IF;
    IF NEW.CUTIS IS NULL OR NEW.CUTIS = 0 THEN
		SET NEW.CUTIS = NULL;
	END IF;
    IF NEW.TIPOCABELO IS NULL OR NEW.TIPOCABELO = 0 THEN
		SET NEW.TIPOCABELO = NULL;
	END IF;
    IF NEW.CORCABELO IS NULL OR NEW.CORCABELO = 0 THEN
		SET NEW.CORCABELO = NULL;
	END IF;
    IF NEW.OLHOS IS NULL OR NEW.OLHOS = 0 THEN
		SET NEW.OLHOS = NULL;
	END IF;
    IF NEW.ESTATURA IS NULL OR NEW.ESTATURA = '' THEN
		SET NEW.ESTATURA = NULL;
	END IF;
    IF NEW.PESO IS NULL OR NEW.PESO = '' THEN
		SET NEW.PESO = NULL;
	END IF;
    IF NEW.PROFISSAO IS NULL OR NEW.PROFISSAO = '' THEN
		SET NEW.PROFISSAO = NULL;
	END IF;
    IF NEW.ESTATURA IS NULL OR NEW.ESTATURA = '' THEN
		SET NEW.ESTATURA = NULL;
	END IF;
    IF NEW.INSTRUCAO IS NULL OR NEW.INSTRUCAO = 0 THEN
		SET NEW.INSTRUCAO = NULL;
	END IF;
    IF NEW.ESTADOCIVIL IS NULL OR NEW.ESTADOCIVIL = 0 THEN
		SET NEW.ESTADOCIVIL = NULL;
	END IF;
    IF NEW.RELIGIAO IS NULL OR NEW.RELIGIAO = 0 THEN
		SET NEW.RELIGIAO = NULL;
	END IF;
    IF NEW.ENDERECO IS NULL OR NEW.ENDERECO = '' THEN
		SET NEW.ENDERECO = NULL;
	END IF;
    IF NEW.IDCIDADEMORADIA IS NULL OR NEW.IDCIDADEMORADIA = 0 THEN
		SET NEW.IDCIDADEMORADIA = NULL;
	END IF;
    IF NEW.IDESTADOMORADIA IS NULL OR NEW.IDESTADOMORADIA = 0 THEN
		SET NEW.IDESTADOMORADIA = NULL;
	END IF;
    IF NEW.SINAIS IS NULL OR NEW.SINAIS = '' THEN
		SET NEW.SINAIS = NULL;
	END IF;

	IF NEW.DATAATUALIZACAO IS NULL OR NEW.DATAATUALIZACAO = '' THEN
		SET NEW.DATAATUALIZACAO = CURRENT_TIMESTAMP;
    END IF;

    IF NEW.IDEXCLUSOREGISTRO IS NOT NULL AND OLD.IDEXCLUSOREGISTRO IS NULL THEN
		IF NEW.DATAEXCLUSOREGISTRO IS NULL OR NEW.DATAEXCLUSOREGISTRO = '' THEN
			SET NEW.DATAEXCLUSOREGISTRO = CURRENT_TIMESTAMP;
		END IF;
		IF NEW.IPEXCLUSOREGISTRO IS NULL OR NEW.IPEXCLUSOREGISTRO = '' THEN
			SET NEW.IPEXCLUSOREGISTRO = NULL;
		END IF;
	END IF;

	-- Salva os log de alterações
    -- IDPRESO
    IF NEW.IDPRESO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.IDPRESO; END IF;
    IF OLD.IDPRESO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.IDPRESO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'IDPRESO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- NOME
    IF NEW.NOME IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.NOME; END IF;
    IF OLD.NOME IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.NOME; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'NOME', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- DATANASC
    IF NEW.DATANASC IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.DATANASC; END IF;
    IF OLD.DATANASC IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.DATANASC; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'DATANASC', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- IDCIDADENASC
    IF NEW.IDCIDADENASC IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.IDCIDADENASC; END IF;
    IF OLD.IDCIDADENASC IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.IDCIDADENASC; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'IDCIDADENASC', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- IDESTADONASC
    IF NEW.IDESTADONASC IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.IDESTADONASC; END IF;
    IF OLD.IDESTADONASC IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.IDESTADONASC; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'IDESTADONASC', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- NACIONALIDADE
    IF NEW.NACIONALIDADE IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.NACIONALIDADE; END IF;
    IF OLD.NACIONALIDADE IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.NACIONALIDADE; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'NACIONALIDADE', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- REGIME
    IF NEW.REGIME IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.REGIME; END IF;
    IF OLD.REGIME IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.REGIME; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'REGIME', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- PROVISORIO
    IF NEW.PROVISORIO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.PROVISORIO; END IF;
    IF OLD.PROVISORIO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.PROVISORIO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'PROVISORIO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- REINCIDENTE
    IF NEW.REINCIDENTE IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.REINCIDENTE; END IF;
    IF OLD.REINCIDENTE IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.REINCIDENTE; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'REINCIDENTE', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- RG
    IF NEW.RG IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.RG; END IF;
    IF OLD.RG IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.RG; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'RG', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- CPF
    IF NEW.CPF IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.CPF; END IF;
    IF OLD.CPF IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.CPF; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'CPF', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- OUTRODOC
    IF NEW.OUTRODOC IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.OUTRODOC; END IF;
    IF OLD.OUTRODOC IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.OUTRODOC; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'OUTRODOC', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- PAI
    IF NEW.PAI IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.PAI; END IF;
    IF OLD.PAI IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.PAI; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'PAI', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- MAE
    IF NEW.MAE IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.MAE; END IF;
    IF OLD.MAE IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.MAE; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'MAE', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- OBSERVACOES
    IF NEW.OBSERVACOES IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.OBSERVACOES; END IF;
    IF OLD.OBSERVACOES IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.OBSERVACOES; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'OBSERVACOES', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- CUTIS
    IF NEW.CUTIS IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.CUTIS; END IF;
    IF OLD.CUTIS IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.CUTIS; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'CUTIS', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- TIPOCABELO
    IF NEW.TIPOCABELO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.TIPOCABELO; END IF;
    IF OLD.TIPOCABELO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.TIPOCABELO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'TIPOCABELO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- CORCABELO
    IF NEW.CORCABELO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.CORCABELO; END IF;
    IF OLD.CORCABELO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.CORCABELO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'CORCABELO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- OLHOS
    IF NEW.OLHOS IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.OLHOS; END IF;
    IF OLD.OLHOS IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.OLHOS; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'OLHOS', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- ESTATURA
    IF NEW.ESTATURA IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.ESTATURA; END IF;
    IF OLD.ESTATURA IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.ESTATURA; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'ESTATURA', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- PESO
    IF NEW.PESO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.PESO; END IF;
    IF OLD.PESO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.PESO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'PESO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- PROFISSAO
    IF NEW.PROFISSAO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.PROFISSAO; END IF;
    IF OLD.PROFISSAO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.PROFISSAO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'PROFISSAO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- INSTRUCAO
    IF NEW.INSTRUCAO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.INSTRUCAO; END IF;
    IF OLD.INSTRUCAO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.INSTRUCAO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'INSTRUCAO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- ESTADOCIVIL
    IF NEW.ESTADOCIVIL IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.ESTADOCIVIL; END IF;
    IF OLD.ESTADOCIVIL IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.ESTADOCIVIL; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'ESTADOCIVIL', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- RELIGIAO
    IF NEW.RELIGIAO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.RELIGIAO; END IF;
    IF OLD.RELIGIAO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.RELIGIAO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'RELIGIAO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- ENDERECO
    IF NEW.ENDERECO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.ENDERECO; END IF;
    IF OLD.ENDERECO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.ENDERECO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'ENDERECO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- IDCIDADEMORADIA
    IF NEW.IDCIDADEMORADIA IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.IDCIDADEMORADIA; END IF;
    IF OLD.IDCIDADEMORADIA IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.IDCIDADEMORADIA; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'IDCIDADEMORADIA', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- IDESTADOMORADIA
    IF NEW.IDESTADOMORADIA IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.IDESTADOMORADIA; END IF;
    IF OLD.IDESTADOMORADIA IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.IDESTADOMORADIA; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'IDESTADOMORADIA', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    -- SINAIS
    IF NEW.SINAIS IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.SINAIS; END IF;
    IF OLD.SINAIS IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.SINAIS; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cadastros_log (IDREFERENCIA, IDPRESO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.MATRICULA, idPresoRegistroAlteracao, 'SINAIS', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    
    -- INSERE OS CADASTROS DE TELEFONE E VULGO NESTA NOVA PASSAGEM DO PRESO NA UNIDADE, PARA PRESERVAR AS INFORMAÇÕES ANTERIORES
	IF NEW.IDPRESO <> 0 AND OLD.IDPRESO IS NULL THEN
		INSERT INTO cadastros_telefones (IDPRESO, NOMECONTATO, NUMERO, IDCADASTRO, IPCADASTRO, DATACADASTRO) 
        SELECT NEW.IDPRESO, NOMECONTATO, NUMERO, IDCADASTRO, IPCADASTRO, DATACADASTRO FROM cadastros_telefones 
        WHERE IDPRESO = (SELECT MAX(ID) FROM entradas_presos WHERE MATRICULA = OLD.MATRICULA AND ID <> NEW.IDPRESO) 
        AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL;
        
		INSERT INTO cadastros_vulgos (IDPRESO, NOME, IDCADASTRO, IPCADASTRO, DATACADASTRO) 
        SELECT NEW.IDPRESO, NOME, IDCADASTRO, IPCADASTRO, DATACADASTRO FROM cadastros_vulgos 
        WHERE IDPRESO = (SELECT MAX(ID) FROM entradas_presos WHERE MATRICULA = OLD.MATRICULA AND ID <> NEW.IDPRESO) 
        AND IDEXCLUSOREGISTRO IS NULL AND DATAEXCLUSOREGISTRO IS NULL;
    END IF;

    SET NEW.DATAATUALIZACAO = NULL;
	SET NEW.IPATUALIZACAO = NULL;
	SET NEW.IDATUALIZACAO = NULL;
 END $