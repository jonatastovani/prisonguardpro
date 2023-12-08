DROP TRIGGER IF EXISTS funcionarios_escalaplantao_troca_INSERT_BEFORE;

delimiter $
CREATE TRIGGER funcionarios_escalaplantao_troca_INSERT_BEFORE
BEFORE INSERT ON funcionarios_escalaplantao_troca
FOR EACH ROW

BEGIN
	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
 END $
DESC funcionarios_escalaplantao_troca;