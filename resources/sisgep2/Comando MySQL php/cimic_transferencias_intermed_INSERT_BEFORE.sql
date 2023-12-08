DROP TRIGGER IF EXISTS cimic_transferencias_intermed_INSERT_BEFORE;

delimiter $
CREATE TRIGGER cimic_transferencias_intermed_INSERT_BEFORE
BEFORE INSERT ON cimic_transferencias_intermed
FOR EACH ROW

BEGIN
	DECLARE intAnoOficio INT;
    DECLARE intIDORDEM INT;
    
    #Obtem o ID da ordem de saída
    SET intIDORDEM = (
		SELECT CMO.ID FROM cimic_ordens_transferencias CMO
		INNER JOIN cimic_transferencias CT ON CT.IDORDEMSAIDAMOV = CMO.ID
		WHERE CT.ID = NEW.IDMOVIMENTACAO);
    
    #Obtem a data da ordem de saída para gerar o ofício
    SET intAnoOficio = (SELECT date_format(DATASAIDA, '%Y') FROM cimic_ordens_transferencias WHERE ID = intIDORDEM);
	
	CALL PROCED_verifica_retorna_numero_oficio_unidade_intermediaria(intIDORDEM, NEW.IDDESTINOINTERM, intAnoOficio, 1, NEW.IDCADASTRO, NEW.IPCADASTRO, @intIDOFICIO);
	SET NEW.IDOFICIOINTERM = @intIDOFICIO;
	SET @intIDOFICIO = 0;

	IF NEW.DATAINTERM IS NULL OR NEW.DATAINTERM = '' OR NEW.DATAINTERM = '0000-00-00' THEN
		SET NEW.DATAINTERM = DEFAULT;
	END IF;
    
	IF NEW.COMENTARIO IS NULL OR NEW.COMENTARIO = '' THEN
		SET NEW.COMENTARIO = NULL;
	END IF;
    
	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
 END $
DESC cimic_transferencias_intermed;