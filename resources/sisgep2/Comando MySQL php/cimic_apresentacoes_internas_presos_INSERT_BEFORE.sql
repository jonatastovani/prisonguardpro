DROP TRIGGER IF EXISTS cimic_apresentacoes_internas_presos_INSERT_BEFORE;

delimiter $
CREATE TRIGGER cimic_apresentacoes_internas_presos_INSERT_BEFORE
BEFORE INSERT ON cimic_apresentacoes_internas_presos
FOR EACH ROW

BEGIN
 	DECLARE intAnoOficio INT;
     
     #Obtem a data do local de apresentacao para gerar o ofício
     SET intAnoOficio = (SELECT date_format(DATASAIDA, '%Y') FROM cimic_apresentacoes_internas WHERE ID = NEW.IDAPRES);
 	
 	CALL PROCED_gera_numero_oficio(intAnoOficio, 6, NEW.IDCADASTRO, NEW.IPCADASTRO, @intIDOFICIO);
 	SET NEW.IDOFICIOAPRES = @intIDOFICIO;
 	SET @intIDOFICIO = 0;
 
 	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
 		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
 	END IF;
    
	-- O ID SITUAÇÃO SÓ PODE ESTAR ENTRE 11 E 16
    IF NEW.IDSITUACAO IS NULL OR NEW.IDSITUACAO NOT BETWEEN 11 AND 16 THEN
		SET NEW.IDSITUACAO = DEFAULT;
    END IF;

  END $
DESC cimic_apresentacoes_internas_presos;