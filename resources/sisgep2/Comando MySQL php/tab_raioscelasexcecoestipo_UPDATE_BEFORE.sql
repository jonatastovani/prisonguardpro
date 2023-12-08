DROP TRIGGER IF EXISTS tab_raioscelasexcecoestipo_UPDATE_BEFORE;

delimiter $
CREATE TRIGGER tab_raioscelasexcecoestipo_UPDATE_BEFORE
BEFORE UPDATE ON tab_raioscelasexcecoestipo
FOR EACH ROW

BEGIN
	DECLARE valorAntigo blob;
	DECLARE valorNovo blob;
    
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
    -- NOME
    IF NEW.NOME IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.NOME; END IF;
    IF OLD.NOME IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.NOME; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO tab_raioscelasexcecoestipo_log (IDREFERENCIA, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'NOME', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    
    -- CELAREMISSAO
    IF NEW.CELAREMISSAO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.CELAREMISSAO; END IF;
    IF OLD.CELAREMISSAO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.CELAREMISSAO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO tab_raioscelasexcecoestipo_log (IDREFERENCIA, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'CELAREMISSAO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    
    -- DESCRICAOPOSTO
    IF NEW.DESCRICAOPOSTO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.DESCRICAOPOSTO; END IF;
    IF OLD.DESCRICAOPOSTO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.DESCRICAOPOSTO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO tab_raioscelasexcecoestipo_log (IDREFERENCIA, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'DESCRICAOPOSTO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    
    SET NEW.DATAATUALIZACAO = NULL;
	SET NEW.IPATUALIZACAO = NULL;
	SET NEW.IDATUALIZACAO = NULL;
 END; $

DESC tab_raioscelasexcecoestipo;