DROP PROCEDURE IF EXISTS AtualizaAlteracaoCelas_chefia_contagem;

delimiter $
CREATE PROCEDURE AtualizaAlteracaoCelas_chefia_contagem(IN intIDBoletim int, IN blnSoma bool, IN strNomeRaio varchar(6),IN strNumeroCela varchar(2))

begin
	
	IF blnSoma = FALSE THEN
		IF strNomeRaio = 'A' THEN
			UPDATE chefia_contagem SET A = A - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'B' THEN
			UPDATE chefia_contagem SET B = B - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'C' THEN
			UPDATE chefia_contagem SET C = C - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'D' THEN
			UPDATE chefia_contagem SET D = D - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'ENF' THEN
			UPDATE chefia_contagem SET ENF = ENF - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'HOSP' THEN
			UPDATE chefia_contagem SET HOSP = HOSP - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'INCL' THEN
			UPDATE chefia_contagem SET INCL = INCL - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'MOD E' THEN
			UPDATE chefia_contagem SET E = E - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'MSP' THEN
			UPDATE chefia_contagem SET MSP = MSP - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'PARL' THEN
			UPDATE chefia_contagem SET PARL = PARL - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'RCD' THEN
			UPDATE chefia_contagem SET RCD = RCD - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'TRAB' THEN
			UPDATE chefia_contagem SET TRAB = TRAB - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'TRI' THEN
			UPDATE chefia_contagem SET TRI = TRI - 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        END IF;
        
	ELSE
		-- Adiciona na cela que o preso foi
		IF strNomeRaio = 'A' THEN
			UPDATE chefia_contagem SET A = A + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'B' THEN
			UPDATE chefia_contagem SET B = B + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'C' THEN
			UPDATE chefia_contagem SET C = C + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'D' THEN
			UPDATE chefia_contagem SET D = D + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'ENF' THEN
			UPDATE chefia_contagem SET ENF = ENF + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'HOSP' THEN
			UPDATE chefia_contagem SET HOSP = HOSP + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'INCL' THEN
			UPDATE chefia_contagem SET INCL = INCL + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'MOD E' THEN
			UPDATE chefia_contagem SET E = E + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'MSP' THEN
			UPDATE chefia_contagem SET MSP = MSP + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'PARL' THEN
			UPDATE chefia_contagem SET PARL = PARL + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'RCD' THEN
			UPDATE chefia_contagem SET RCD = RCD + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'TRAB' THEN
			UPDATE chefia_contagem SET TRAB = TRAB + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
        ELSEIF strNomeRaio = 'TRI' THEN
			UPDATE chefia_contagem SET TRI = TRI + 1 WHERE IDBOLETIM = intIDBoletim AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCela; 
		END IF;
        
	END IF;
        
END; $

