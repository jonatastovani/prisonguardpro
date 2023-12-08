DROP TRIGGER IF EXISTS entradas_artigos_UPDATE_BEFORE;

delimiter $
CREATE TRIGGER entradas_artigos_UPDATE_BEFORE
BEFORE UPDATE ON entradas_artigos
FOR EACH ROW

begin
	DECLARE valorAntigo text;
	DECLARE valorNovo text;

    IF NEW.OBSERVACOES IS NULL OR NEW.OBSERVACOES = '' THEN
		SET NEW.OBSERVACOES = NULL;
	END IF;
	IF NEW.DATAATUALIZACAO IS NULL OR NEW.DATAATUALIZACAO = '' THEN
		SET NEW.DATAATUALIZACAO = CURRENT_TIMESTAMP;
    END IF;

    IF NEW.IDEXCLUSOREGISTRO IS NOT NULL AND OLD.IDEXCLUSOREGISTRO IS NULL THEN
		IF NEW.DATAEXCLUSOREGISTRO IS NULL OR NEW.DATAEXCLUSOREGISTRO = '' THEN
			SET NEW.DATAEXCLUSOREGISTRO = CURRENT_TIMESTAMP;
		END IF;
	END IF;
    
    -- OBSERVACOES
    IF NEW.OBSERVACOES IS NULL THEN SET valorNovo = '**VAZIO**'; ELSE  SET valorNovo = NEW.OBSERVACOES; END IF;
    IF OLD.OBSERVACOES IS NULL THEN SET valorAntigo = '**VAZIO**'; ELSE  SET valorAntigo = OLD.OBSERVACOES; END IF;
    IF valorAntigo <> valorNovo THEN
		INSERT INTO entradas_artigos_log (IDARTIGO, CAMPOATUALIZADO, ANTIGO, NOVO, IDATUALIZACAO, DATAATUALIZACAO, IPATUALIZACAO)
        VALUES (OLD.ID, 'OBSERVACOES', valorAntigo, valorNovo, NEW.IDATUALIZACAO, NEW.DATAATUALIZACAO, NEW.IPATUALIZACAO);
    END IF;
    
    SET NEW.DATAATUALIZACAO = NULL;
	SET NEW.IPATUALIZACAO = NULL;
	SET NEW.IDATUALIZACAO = NULL;

 END $
 DESC entradas_artigos;
SELECT * FROM entradas_artigos_log;