DROP TRIGGER IF EXISTS enf_medic_assistido_INSERT_BEFORE;

delimiter $
CREATE TRIGGER enf_medic_assistido_INSERT_BEFORE
BEFORE INSERT ON enf_medic_assistido
FOR EACH ROW

BEGIN

    IF NEW.DATAINICIO IS NULL OR NEW.DATAINICIO = '' OR NEW.DATAINICIO = '0000-00-00 00:00:00' THEN
		SET NEW.DATAINICIO = CURRENT_TIMESTAMP;
	END IF;

	IF NEW.DATATERMINO IS NULL OR NEW.DATATERMINO = '' OR NEW.DATATERMINO = '0000-00-00 00:00:00' THEN
		SET NEW.DATATERMINO = DEFAULT;
	END IF;
    
	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
 END $
DESC enf_medic_assistido;