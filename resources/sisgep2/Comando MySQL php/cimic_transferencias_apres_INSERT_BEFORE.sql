DROP TRIGGER IF EXISTS cimic_apresentacoes_INSERT_BEFORE;

delimiter $
CREATE TRIGGER cimic_apresentacoes_INSERT_BEFORE
BEFORE INSERT ON cimic_apresentacoes
FOR EACH ROW

BEGIN
	DECLARE intAnoOficio INT;
    
    #Obtem a data da ordem de saída para gerar o ofício
    SET intAnoOficio = (SELECT date_format(DATASAIDA, '%Y') FROM cimic_ordens_apresentacoes WHERE ID = NEW.IDORDEMSAIDAMOV);
	
	CALL PROCED_gera_numero_oficio(intAnoOficio, 4, NEW.IDCADASTRO, NEW.IPCADASTRO, @intIDOFICIO);
	SET NEW.IDOFICIOAPRES = @intIDOFICIO;
	SET @intIDOFICIO = 0;

	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
 END $
DESC cimic_apresentacoes;