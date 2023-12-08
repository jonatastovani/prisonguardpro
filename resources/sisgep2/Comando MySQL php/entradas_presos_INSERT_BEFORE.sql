DROP TRIGGER IF EXISTS entradas_presos_INSERT_BEFORE;

delimiter $
CREATE TRIGGER entradas_presos_INSERT_BEFORE
BEFORE INSERT ON entradas_presos
FOR EACH ROW

begin
	IF NEW.MATRICULA IS NULL OR NEW.MATRICULA = ''  OR NEW.MATRICULA = '0' THEN
		SET NEW.MATRICULA = DEFAULT;
	END IF;
    IF NEW.RG IS NULL OR NEW.RG = ''THEN
		SET NEW.RG = DEFAULT;
	END IF;
    IF NEW.INFORMACOES IS NULL OR NEW.INFORMACOES = ''THEN
		SET NEW.INFORMACOES = DEFAULT;
	END IF;
    IF NEW.OBSERVACOES IS NULL OR NEW.OBSERVACOES = ''THEN
		SET NEW.OBSERVACOES = DEFAULT;
	END IF;
	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
 
 END $