DROP TRIGGER IF EXISTS cimic_ordens_transferencias_UPDATE_BEFORE;

delimiter $
CREATE TRIGGER cimic_ordens_transferencias_UPDATE_BEFORE
BEFORE UPDATE ON cimic_ordens_transferencias
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
    -- IDDESTINO
    IF NEW.IDDESTINO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.IDDESTINO; END IF;
    IF OLD.IDDESTINO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.IDDESTINO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cimic_ordens_transferencias_log (IDREFERENCIA, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'IDDESTINO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    
    -- DATASAIDA
    IF NEW.DATASAIDA IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.DATASAIDA; END IF;
    IF OLD.DATASAIDA IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.DATASAIDA; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO cimic_ordens_transferencias_log (IDREFERENCIA, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'DATASAIDA', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    
    SET NEW.DATAATUALIZACAO = NULL;
	SET NEW.IPATUALIZACAO = NULL;
	SET NEW.IDATUALIZACAO = NULL;
 END; $

DESC cimic_ordens_transferencias;