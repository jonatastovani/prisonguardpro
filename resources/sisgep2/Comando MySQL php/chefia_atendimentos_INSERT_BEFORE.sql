DROP TRIGGER IF EXISTS chefia_atendimentos_INSERT_BEFORE;

delimiter $
CREATE TRIGGER chefia_atendimentos_INSERT_BEFORE
BEFORE INSERT ON chefia_atendimentos
FOR EACH ROW

BEGIN
    IF NEW.IDBOLETIM IS NULL OR NEW.IDBOLETIM = '' OR NEW.IDBOLETIM = 0 THEN
		SET NEW.IDBOLETIM = DEFAULT;
	END IF;
	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
    
    -- O ID SITUAÇÃO SÓ PODE ESTAR ENTRE OS PERMITIDOS
    IF NEW.IDSITUACAO NOT IN (SELECT IDSITUACAO FROM tab_situacaofiltro WHERE IDTIPO = 7) THEN
		SET NEW.IDSITUACAO = DEFAULT;
    END IF;

	#SE A SITUAÇÃO FOR A AGUARDANDO RESPOSTA E TEM DATA MARCADA, ENTÃO SE ALTERA PARA AGENDADADO
    IF NEW.IDSITUACAO = 10 AND (SELECT DATAATEND FROM chefia_atendimentos_requis WHERE ID = NEW.IDREQ) IS NOT NULL THEN
		SET NEW.IDSITUACAO = 11;
    END IF;
	
	IF NEW.IDSITUACAO = 17 OR NEW.IDSITUACAO = 13 THEN
		SET NEW.IDBOLETIM = (SELECT ID FROM chefia_boletim WHERE BOLETIMDODIA = TRUE ORDER BY ID DESC LIMIT 1);
	END IF;

 END $
DESC chefia_atendimentos;