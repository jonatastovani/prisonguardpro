DROP TRIGGER IF EXISTS enf_atendimentos_requis_INSERT_BEFORE;

delimiter $
CREATE TRIGGER enf_atendimentos_requis_INSERT_BEFORE
BEFORE INSERT ON enf_atendimentos_requis
FOR EACH ROW

BEGIN
    IF NEW.DATAATEND IS NULL OR NEW.DATAATEND = '' THEN
		SET NEW.DATAATEND = DEFAULT;
	END IF;
    IF NEW.REQUISITANTE IS NULL OR NEW.REQUISITANTE = '' THEN
		SET NEW.REQUISITANTE = DEFAULT;
	ELSE
		CALL PROCED_verificasugestao(NEW.REQUISITANTE,2,NEW.IDCADASTRO,NEW.IPCADASTRO);
    END IF;

	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
 END $
DESC enf_atendimentos_requis;