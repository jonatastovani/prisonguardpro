DROP TRIGGER IF EXISTS tab_diretores_temporarios_INSERT_BEFORE;

delimiter $
CREATE TRIGGER tab_diretores_temporarios_INSERT_BEFORE
before insert ON tab_diretores_temporarios
FOR EACH ROW

begin
	SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
        
END; $

desc tab_diretores_temporarios;
