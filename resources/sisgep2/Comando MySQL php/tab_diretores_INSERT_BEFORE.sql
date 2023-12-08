DROP TRIGGER IF EXISTS tab_diretores_INSERT_BEFORE;

delimiter $
CREATE TRIGGER tab_diretores_INSERT_BEFORE
before insert ON tab_diretores
FOR EACH ROW

begin
	SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
        
END; $

desc tab_diretores;