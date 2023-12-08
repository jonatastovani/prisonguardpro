DROP TRIGGER IF EXISTS chefia_mudancacela_INSERT_AFTER;

delimiter $
CREATE TRIGGER chefia_mudancacela_INSERT_AFTER
AFTER INSERT ON chefia_mudancacela
FOR EACH ROW

begin
     -- Insere a primeira situação
     INSERT INTO chefia_mudancacelasituacao (IDMUDANCA, IDSITUACAO, IDCADASTRO, IPCADASTRO, DATACADASTRO) VALUES 
     (NEW.ID, NEW.IDSITUACAO, NEW.IDCADASTRO, NEW.IPCADASTRO, NEW.DATACADASTRO);
          
END; $
DESC chefia_mudancacela;
