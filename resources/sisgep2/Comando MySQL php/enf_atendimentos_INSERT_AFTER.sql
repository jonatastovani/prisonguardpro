DROP TRIGGER IF EXISTS enf_atendimentos_INSERT_AFTER;

delimiter $
CREATE TRIGGER enf_atendimentos_INSERT_AFTER
AFTER INSERT ON enf_atendimentos
FOR EACH ROW

begin
     -- Insere a primeira situação
     INSERT INTO enf_atendimentossituacao (IDREFERENCIA, IDSITUACAO, IDCADASTRO, IPCADASTRO, DATACADASTRO) VALUES 
     (NEW.ID, NEW.IDSITUACAO, NEW.IDCADASTRO, NEW.IPCADASTRO, NEW.DATACADASTRO);
          
END; $
DESC enf_atendimentos;
