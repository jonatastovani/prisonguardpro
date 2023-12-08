DROP TRIGGER IF EXISTS chefia_movimentacoespresos_UPDATE_AFTER;

delimiter $
CREATE TRIGGER chefia_movimentacoespresos_UPDATE_AFTER
after update ON chefia_movimentacoespresos
FOR EACH ROW

begin
	DECLARE strNomeRaioOrigem varchar(6) default NULL;
	DECLARE strNumeroCelaOrigem varchar(2) default NULL;
	DECLARE strNomeRaioDestino varchar(6) default NULL;
	DECLARE strNumeroCelaDestino varchar(2) default NULL;
    
    SET strNomeRaioOrigem = (SELECT RAIOORIGEM FROM chefia_movimentacoespresos WHERE ID = OLD.ID);
    SET strNumeroCelaOrigem = (SELECT CELAORIGEM FROM chefia_movimentacoespresos WHERE ID = OLD.ID);
    
    SET strNomeRaioDestino = (SELECT RAIOORIGEM FROM chefia_movimentacoespresos WHERE ID = OLD.ID);
    SET strNumeroCelaDestino = (SELECT CELAORIGEM FROM chefia_movimentacoespresos WHERE ID = OLD.ID);
    
	IF NEW.IDREALIZADO IS NOT NULL AND OLD.IDREALIZADO IS NOT NULL THEN
		-- Subtrai da cela que o preso saiu
		IF strNomeRaioOrigem = 'A' THEN
			UPDATE chefia_contagem SET A = A - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'B' THEN
			UPDATE chefia_contagem SET B = B - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'C' THEN
			UPDATE chefia_contagem SET C = C - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'D' THEN
			UPDATE chefia_contagem SET D = D - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'ENF' THEN
			UPDATE chefia_contagem SET ENF = ENF - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'HOSP' THEN
			UPDATE chefia_contagem SET HOSP = HOSP - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'INCL' THEN
			UPDATE chefia_contagem SET INCL = INCL - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'MOD E' THEN
			UPDATE chefia_contagem SET E = E - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'MSP' THEN
			UPDATE chefia_contagem SET MSP = MSP - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'PARL' THEN
			UPDATE chefia_contagem SET PARL = PARL - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'RCD' THEN
			UPDATE chefia_contagem SET RCD = RCD - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'TRAB' THEN
			UPDATE chefia_contagem SET TRAB = TRAB - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        ELSEIF strNomeRaioOrigem = 'TRI' THEN
			UPDATE chefia_contagem SET TRI = TRI - 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaOrigem; 
        END IF;
        
        IF OLD.IDTIPOMOVIMENTACAO = 10 THEN
		-- Adiciona na cela que o preso foi
		IF strNomeRaioDestino = 'A' THEN
			UPDATE chefia_contagem SET A = A + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'B' THEN
			UPDATE chefia_contagem SET B = B + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'C' THEN
			UPDATE chefia_contagem SET C = C + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'D' THEN
			UPDATE chefia_contagem SET D = D + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'ENF' THEN
			UPDATE chefia_contagem SET ENF = ENF + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'HOSP' THEN
			UPDATE chefia_contagem SET HOSP = HOSP + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'INCL' THEN
			UPDATE chefia_contagem SET INCL = INCL + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'MOD E' THEN
			UPDATE chefia_contagem SET E = E + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'MSP' THEN
			UPDATE chefia_contagem SET MSP = MSP + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'PARL' THEN
			UPDATE chefia_contagem SET PARL = PARL + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'RCD' THEN
			UPDATE chefia_contagem SET RCD = RCD + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'TRAB' THEN
			UPDATE chefia_contagem SET TRAB = TRAB + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        ELSEIF strNomeRaioDestino = 'TRI' THEN
			UPDATE chefia_contagem SET TRI = TRI + 1 WHERE IDBOLETIM = OLD.IDBOLETIM AND TIPOCONTAGEM = 'ALTERACOES' AND CELA = strNumeroCelaDestino; 
        END IF;
        
        END IF;
        
   END IF;
    
    -- Caso for excluso o registro então primeiro alimenta-se as informações de quem está excluindo o registro.
    IF NEW.IDEXCLUSOREGISTRO IS NOT NULL AND OLD.IDEXCLUSOREGISTRO IS NULL THEN
		SET NEW.DATAEXCLUSOREGISTRO = CURRENT_TIMESTAMP;
    END IF;
	
END; $