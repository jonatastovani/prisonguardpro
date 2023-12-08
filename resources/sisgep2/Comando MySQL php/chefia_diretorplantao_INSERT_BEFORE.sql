DROP TRIGGER IF EXISTS chefia_diretorplantao_INSERT_BEFORE;

delimiter $
CREATE TRIGGER chefia_diretorplantao_INSERT_BEFORE
before insert ON chefia_diretorplantao
FOR EACH ROW

begin
	SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
        
END; $
