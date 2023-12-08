DROP TRIGGER IF EXISTS cimic_transferencias_apres_UPDATE_BEFORE;

delimiter $
CREATE TRIGGER cimic_transferencias_apres_UPDATE_BEFORE
BEFORE UPDATE ON cimic_transferencias_apres
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
    -- DATAAPRES
    IF NEW.DATAAPRES IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.DATAAPRES; END IF;
    IF OLD.DATAAPRES IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.DATAAPRES; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cimic_transferencias_apres_log (IDREFERENCIA, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'DATAAPRES', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    
    -- IDMOTIVOAPRES
    IF NEW.IDMOTIVOAPRES IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.IDMOTIVOAPRES; END IF;
    IF OLD.IDMOTIVOAPRES IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.IDMOTIVOAPRES; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cimic_transferencias_apres_log (IDREFERENCIA, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'IDMOTIVOAPRES', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    
    -- PROCESSO
    IF NEW.PROCESSO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.PROCESSO; END IF;
    IF OLD.PROCESSO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.PROCESSO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cimic_transferencias_apres_log (IDREFERENCIA, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'PROCESSO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    
    SET NEW.DATAATUALIZACAO = NULL;
	SET NEW.IPATUALIZACAO = NULL;
	SET NEW.IDATUALIZACAO = NULL;
 END; $

DESC cimic_transferencias_apres;