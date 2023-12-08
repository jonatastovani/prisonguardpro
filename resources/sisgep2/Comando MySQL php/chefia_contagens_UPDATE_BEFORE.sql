DROP TRIGGER IF EXISTS chefia_contagens_UPDATE_BEFORE;

delimiter $
CREATE TRIGGER chefia_contagens_UPDATE_BEFORE
BEFORE UPDATE ON chefia_contagens
FOR EACH ROW

BEGIN
	DECLARE valorAntigo blob;
	DECLARE valorNovo blob;
    
    IF NEW.IDUSUARIO = 0 OR NEW.IDUSUARIO = '' THEN
		SET NEW.IDUSUARIO = NULL;
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
    -- IDUSUARIO
    IF NEW.IDUSUARIO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.IDUSUARIO; END IF;
    IF OLD.IDUSUARIO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.IDUSUARIO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO chefia_contagens_log (IDREFERENCIA, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'IDUSUARIO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
       
    -- AUTENTICADO
    IF NEW.AUTENTICADO IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.AUTENTICADO; END IF;
    IF OLD.AUTENTICADO IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.AUTENTICADO; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO chefia_contagens_log (IDREFERENCIA, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'AUTENTICADO', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
       
    SET NEW.DATAATUALIZACAO = NULL;
	SET NEW.IPATUALIZACAO = NULL;
	SET NEW.IDATUALIZACAO = NULL;
 END; $

DESC chefia_contagens;