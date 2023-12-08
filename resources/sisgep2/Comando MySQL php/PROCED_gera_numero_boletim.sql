DROP PROCEDURE IF EXISTS PROCED_gera_numero_boletim;
DELIMITER $$
CREATE PROCEDURE PROCED_gera_numero_boletim(IN intIDCADASTRO INT, IN chrIPCADASTRO varchar(20))
BEGIN
	DECLARE intUltimo INT;
    DECLARE dateData DATE;
    DECLARE dateNovaData DATE;
    DECLARE blnPeriodo BOOL;
    DECLARE intNumero INT;
    DECLARE intTurno INT;
    DECLARE intIDDiretor INT;
    
    SET intUltimo = (SELECT MAX(ID) FROM chefia_boletim where BOLETIMDODIA = TRUE);
    SET dateData = (SELECT DATABOLETIM FROM chefia_boletim WHERE ID = intUltimo);
    SET intTurno = (SELECT IDTURNO FROM chefia_boletim WHERE ID = intUltimo);
    SET intNumero = (SELECT NUMERO FROM chefia_boletim WHERE ID = intUltimo);
    SET blnPeriodo = (SELECT PERIODODIURNO FROM tab_turnos WHERE ID = intTurno);
    
    #DEFINE AS INFORMAÇÕES DO PRÓXIMO BOLETIM DA SEQUÊNCIA
    IF blnPeriodo = TRUE THEN
		SET blnPeriodo = FALSE;
		SET dateNovaData = dateData;
	ELSE
		SET blnPeriodo = TRUE;
		#NOVA DATA
		SET dateNovaData = DATE_ADD(dateData, INTERVAL 1 DAY);
        IF date_format(dateNovaData, '%Y') = date_format(dateData, '%Y') THEN
			SET intNumero = (SELECT NUMERO + 1 FROM chefia_boletim WHERE ID = intUltimo);
        ELSE
			SET intNumero = 1;
        END IF;
    END IF;
    
    #DEFINE O PRÓXIMO TURNO
    IF intTurno < 4 THEN
		SET intTurno = intTurno + 1;
	ELSE
		SET intTurno = 1;
	END IF;
    
    #TIRA O BOLETIMDODIA DOS OUTROS REGISTROS
    UPDATE chefia_boletim SET BOLETIMDODIA = FALSE, IDATUALIZACAO = intIDCADASTRO, IPATUALIZACAO = chrIPCADASTRO WHERE BOLETIMDODIA = TRUE;
    
    #SET intIDDiretor = (SELECT ID FROM tab_diretores WHERE IDTURNO = intTurno AND (DATATERMINO >= dateNovaData OR DATATERMINO IS NULL) AND IDEXCLUSOREGISTRO IS NULL ORDER BY TEMPORARIO DESC, ID LIMIT 1);
    
    #INSERE O NOVO BOLETIM DO DIA
	INSERT INTO chefia_boletim (NUMERO, IDTURNO, DATABOLETIM, IDBOLETIMANTERIOR, IDCADASTRO, IPCADASTRO)
	VALUES (intNumero, intTurno, dateNovaData, intUltimo, intIDCADASTRO, chrIPCADASTRO);
END; $$