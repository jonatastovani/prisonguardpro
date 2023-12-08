DROP TRIGGER IF EXISTS funcionarios_escalaplantao_INSERT_BEFORE;

delimiter $
CREATE TRIGGER funcionarios_escalaplantao_INSERT_BEFORE
BEFORE INSERT ON funcionarios_escalaplantao
FOR EACH ROW

BEGIN
    IF NEW.IDBOLETIM IS NULL OR NEW.IDBOLETIM = 0 THEN
		SET NEW.IDBOLETIM = (SELECT ID FROM chefia_boletim WHERE BOLETIMDODIA = TRUE);
	END IF;

	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
 END $
DESC funcionarios_escalaplantao;