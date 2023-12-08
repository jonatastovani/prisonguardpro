DROP TRIGGER IF EXISTS cimic_apresentacoes_internas_presos_INSERT_AFTER;

delimiter $
CREATE TRIGGER cimic_apresentacoes_internas_presos_INSERT_AFTER
AFTER INSERT ON cimic_apresentacoes_internas_presos
FOR EACH ROW

BEGIN
    #INSERIR A SITUAÇÃO
     INSERT INTO cimic_apresentacoes_internas_presossituacao (IDREFERENCIA, IDSITUACAO, IDCADASTRO, IPCADASTRO, DATACADASTRO) VALUES 
     (NEW.ID, NEW.IDSITUACAO, NEW.IDCADASTRO, NEW.IPCADASTRO, NEW.DATACADASTRO);
         
 END $
DESC cimic_apresentacoes_internas_presos;