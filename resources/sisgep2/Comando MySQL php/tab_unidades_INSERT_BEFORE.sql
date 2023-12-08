DROP TRIGGER IF EXISTS tab_unidades_INSERT_BEFORE;

delimiter $
CREATE TRIGGER tab_unidades_INSERT_BEFORE
BEFORE INSERT ON tab_unidades
FOR EACH ROW

BEGIN
    IF NEW.DIRETOR IS NULL OR NEW.DIRETOR = '' THEN
		SET NEW.DIRETOR = DEFAULT;
	END IF;
    
    IF NEW.EMAILNOTES IS NULL OR NEW.EMAILNOTES = '' THEN
		SET NEW.EMAILNOTES = DEFAULT;
	END IF;
    
    IF NEW.EMAILCIMIC IS NULL OR NEW.EMAILCIMIC = '' THEN
		SET NEW.EMAILCIMIC = DEFAULT;
	END IF;
    
    IF NEW.ENDERECO IS NULL OR NEW.ENDERECO = '' THEN
		SET NEW.ENDERECO = DEFAULT;
	END IF;
    
    IF NEW.CEP IS NULL OR NEW.CEP = '' THEN
		SET NEW.CEP = DEFAULT;
	END IF;
    
    IF NEW.TELEFONES IS NULL OR NEW.TELEFONES = '' THEN
		SET NEW.TELEFONES = DEFAULT;
	END IF;

	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
 END $

DESC tab_unidades;