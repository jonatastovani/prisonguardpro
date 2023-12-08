DROP TRIGGER IF EXISTS rol_visitantes_INSERT_AFTER;

delimiter $
CREATE TRIGGER rol_visitantes_INSERT_AFTER
AFTER INSERT ON rol_visitantes
FOR EACH ROW

begin
     -- Insere a primeira situação
     INSERT INTO rol_visitantessituacao (IDREFERENCIA, IDSITUACAO, COMENTARIO, IDCADASTRO, IPCADASTRO, DATACADASTRO) VALUES 
     (NEW.ID, NEW.IDSITUACAO, NEW.COMENTARIO, NEW.IDCADASTRO, NEW.IPCADASTRO, NEW.DATACADASTRO);
          
END; $
DESC rol_visitantes;