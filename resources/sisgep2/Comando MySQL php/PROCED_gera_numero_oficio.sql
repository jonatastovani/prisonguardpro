DROP PROCEDURE IF EXISTS PROCED_gera_numero_oficio;
DELIMITER $$
CREATE PROCEDURE PROCED_gera_numero_oficio(IN intANO INT, IN intTIPOOFICIO INT, IN intIDCADASTRO INT, IN chrIPCADASTRO varchar(20), OUT intID INT)
BEGIN
	DECLARE intUltimo integer;
    
    SET intUltimo = (SELECT MAX(NUMERO) FROM tab_oficios WHERE ANO = intANO);
    
    IF intUltimo IS NULL THEN
		INSERT INTO tab_oficios (NUMERO, ANO, TIPOOFICIO, IDCADASTRO, IPCADASTRO)
        VALUES (1, intANO, intTIPOOFICIO, intIDCADASTRO, chrIPCADASTRO);
		SET intUltimo = (SELECT MAX(ID) FROM tab_oficios WHERE ANO = intANO AND NUMERO = 1);
		SET intID = intUltimo;
	ELSE
		INSERT INTO tab_oficios (NUMERO, ANO, TIPOOFICIO, IDCADASTRO, IPCADASTRO)
        VALUES (intUltimo + 1, intANO, intTIPOOFICIO, intIDCADASTRO, chrIPCADASTRO);
		SET intUltimo = (SELECT MAX(ID) FROM tab_oficios WHERE ANO = intANO AND NUMERO = intUltimo + 1);
		SET intID = intUltimo;
    END IF;
END; $$

