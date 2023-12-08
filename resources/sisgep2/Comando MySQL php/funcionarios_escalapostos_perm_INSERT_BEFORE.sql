DROP TRIGGER IF EXISTS funcionarios_escalapostos_perm_INSERT_BEFORE;

delimiter $
CREATE TRIGGER funcionarios_escalapostos_perm_INSERT_BEFORE
BEFORE INSERT ON funcionarios_escalapostos_perm
FOR EACH ROW

BEGIN
	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
 END $
DESC funcionarios_escalapostos_perm;