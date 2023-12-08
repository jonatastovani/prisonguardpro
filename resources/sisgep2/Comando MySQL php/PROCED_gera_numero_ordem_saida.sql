DROP PROCEDURE IF EXISTS PROCED_gera_numero_ordem_saida;
DELIMITER $$
CREATE PROCEDURE PROCED_gera_numero_ordem_saida(IN intANO INT, IN intTIPOORDEM INT, IN intIDCADASTRO INT, IN chrIPCADASTRO varchar(20), OUT intID INT)
BEGIN
	DECLARE intUltimo integer;
    
    SET intUltimo = (SELECT MAX(NUMERO) FROM tab_ordemsaida WHERE ANO = intANO);
    
    IF intUltimo IS NULL THEN
		INSERT INTO tab_ordemsaida (NUMERO, ANO, IDTIPOORDEM, IDCADASTRO, IPCADASTRO)
        VALUES (1, intANO, intTIPOORDEM, intIDCADASTRO, chrIPCADASTRO);
		SET intUltimo = (SELECT MAX(ID) FROM tab_ordemsaida WHERE ANO = intANO AND NUMERO = 1);
		SET intID = intUltimo;
	ELSE
		INSERT INTO tab_ordemsaida (NUMERO, ANO, IDTIPOORDEM, IDCADASTRO, IPCADASTRO)
        VALUES (intUltimo + 1, intANO, intTIPOORDEM, intIDCADASTRO, chrIPCADASTRO);
		SET intUltimo = (SELECT MAX(ID) FROM tab_ordemsaida WHERE ANO = intANO AND NUMERO = intUltimo + 1);
		SET intID = intUltimo;
    END IF;
END; $$

