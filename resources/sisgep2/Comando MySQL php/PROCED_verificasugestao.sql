DROP PROCEDURE IF EXISTS PROCED_verificasugestao;
DELIMITER $$
CREATE PROCEDURE PROCED_verificasugestao(IN strNome varchar(255), IN intTipo INT, IN intIDCADASTRO INT, IN chrIPCADASTRO varchar(20))
BEGIN
	DECLARE intExistente INT;
    
	IF intTipo = 1 THEN
		SET intExistente = (SELECT ID FROM chefia_atendimentosnomes WHERE NOME = strNome);
	ELSEIF intTipo = 2 THEN
		SET intExistente = (SELECT ID FROM enf_atendimentosnomes WHERE NOME = strNome);
    END IF;
    
    IF intExistente IS NULL THEN
		IF intTipo = 1 THEN
			INSERT INTO chefia_atendimentosnomes (NOME,IDCADASTRO,IPCADASTRO) VALUES (strNome,intIDCADASTRO,chrIPCADASTRO);
		ELSEIF intTipo = 2 THEN
			INSERT INTO enf_atendimentosnomes (NOME,IDCADASTRO,IPCADASTRO) VALUES (strNome,intIDCADASTRO,chrIPCADASTRO);
		END IF;
    END IF;
    
END; $$