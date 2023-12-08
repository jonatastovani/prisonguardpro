DROP TRIGGER IF EXISTS chefia_movimentacoespresos_UPDATE_BEFORE;

delimiter $
CREATE TRIGGER chefia_movimentacoespresos_UPDATE_BEFORE
before update ON chefia_movimentacoespresos
FOR EACH ROW

begin
	-- Se a movimentação inserida for uma exclusão ou inclusão nas celas de faxina ou trabalho então
    -- é feito o SET nos campos respectivos
	DECLARE blnINCLUSAOFAXINATRABALHO BOOL DEFAULT FALSE;
	DECLARE blnEXCLUSAOFAXINATRABALHO BOOL DEFAULT FALSE;
    
    IF NEW.RAIODESTINO IS NOT NULL THEN
		IF (SELECT ID FROM chefia_celasfaxinatrabalho WHERE NOME = NEW.RAIODESTINO AND CELA = NEW.CELADESTINO) IS NOT NULL THEN 
			SET blnINCLUSAOFAXINATRABALHO = TRUE;
		END IF;
	END IF;
	IF (SELECT ID FROM chefia_celasfaxinatrabalho WHERE NOME = NEW.RAIOORIGEM AND CELA = NEW.CELAORIGEM) IS NOT NULL THEN 
		SET blnEXCLUSAOFAXINATRABALHO = TRUE;
	END IF;

IF NEW.IDREALIZADO IS NOT NULL AND OLD.IDREALIZADO IS NULL THEN
	SET NEW.DATAREALIZADO = CURRENT_TIMESTAMP;

	-- Subtrai da onde o preso estava
	IF NEW.RAIOORIGEM IS NOT NULL THEN
		CALL AtualizaAlteracaoCelas_chefia_contagem(OLD.IDBOLETIM,FALSE,NEW.RAIOORIGEM,NEW.CELAORIGEM);
	ELSE
		CALL AtualizaAlteracaoCelas_chefia_contagem(OLD.IDBOLETIM,FALSE,OLD.RAIOORIGEM,OLD.CELAORIGEM);
	END IF;
	
	IF OLD.IDTIPOMOVIMENTACAO = 10 THEN
		-- Quando a mudança foi realizada no raio, então se atualiza o SISGEP. Não será mais possível
		-- excluir essa movimentação após ser realizado a mudança.

		-- Somente se altera no sisgep se o preso em questão já estiver vinculado no sisgep
		IF OLD.IDRECIBO IS NULL THEN

			-- Completa informações da cela anteriormente ocupada pelo preso.    
			UPDATE sisgepteste.celas UPCelas
			JOIN (SELECT SLCelas.Cod_Id IDCELAANTIGA FROM sisgepteste.celas SLCelas WHERE Matric_Cel = OLD.MATRICULA ORDER BY Cod_Id DESC LIMIT 1) SL
			SET Fim_Cel = CURRENT_DATE, Dl_Cel = '*', 
			User_Cel = (SELECT RSUSUARIO FROM tab_usuarios WHERE ID = NEW.IDAPROVACAO), Ip_Cel = NEW.IPAPROVACAO
			WHERE Cod_Id = SL.IDCELAANTIGA;
			-- Insere a nova cela que o preso irá morar.
			INSERT INTO sisgepteste.celas (Matric_Cel, Cela_Cel, Pav_Cel, Ini_Cel, Dl_Cel, User_Cel, Ip_Cel) VALUES
			(OLD.MATRICULA, NEW.CELADESTINO, NEW.RAIODESTINO, CURRENT_DATE, '+',
			(SELECT RSUSUARIO FROM tab_usuarios WHERE ID = NEW.IDAPROVACAO), NEW.IPAPROVACAO);
			SET NEW.DATAREALIZADO = CURRENT_TIMESTAMP;
            
		END IF;
		
		IF NEW.RAIODESTINO IS NOT NULL THEN
			CALL AtualizaAlteracaoCelas_chefia_contagem(OLD.IDBOLETIM,TRUE,NEW.RAIODESTINO,NEW.CELADESTINO);
		ELSE
			CALL AtualizaAlteracaoCelas_chefia_contagem(OLD.IDBOLETIM,TRUE,OLD.RAIODESTINO,OLD.CELADESTINO);
		END IF;
		
	END IF;

   END IF;
    
    -- Caso for excluso o registro então primeiro alimenta-se as informações de quem está excluindo o registro.
    IF NEW.IDEXCLUSOREGISTRO IS NOT NULL AND OLD.IDEXCLUSOREGISTRO IS NULL THEN
		SET NEW.DATAEXCLUSOREGISTRO = CURRENT_TIMESTAMP;
        
		-- Quando se exclui o registro então soma-se na cela que o preso estava
		CALL AtualizaAlteracaoCelas_chefia_contagem(OLD.IDBOLETIM,TRUE,OLD.RAIOORIGEM,OLD.CELAORIGEM);
        IF OLD.IDTIPOMOVIMENTACAO = 10 THEN
			CALL AtualizaAlteracaoCelas_chefia_contagem(OLD.IDBOLETIM,FALSE,OLD.RAIODESTINO,OLD.CELADESTINO);
        END IF;
    END IF;
	
    IF NEW.IDAPROVACAO IS NOT NULL AND OLD.IDAPROVACAO IS NULL THEN
		SET NEW.DATAAPROVACAO = CURRENT_TIMESTAMP;
        SET NEW.IDNEGADO = DEFAULT;
        SET NEW.IPNEGADO = DEFAULT;
        SET NEW.NOMECOMPUTADORNEGADO = DEFAULT;
        SET NEW.DATANEGADO = DEFAULT;
    END IF;
    
    IF NEW.IDNEGADO IS NOT NULL AND OLD.IDNEGADO IS NULL THEN
		SET NEW.DATANEGADO = CURRENT_TIMESTAMP;
        SET NEW.IDAPROVACAO = DEFAULT;
        SET NEW.IPAPROVACAO = DEFAULT;
        SET NEW.NOMECOMPUTADORAPROVACAO = DEFAULT;
        SET NEW.DATAAPROVACAO = DEFAULT;
    END IF;
    
    -- Altera para uma mudança de preso que trabalha caso a cela de origem ou destino dele passar a ser cela de trabalho.
    SET NEW.INCLUSAOFAXINATRABALHO = blnINCLUSAOFAXINATRABALHO;
    SET NEW.EXCLUSAOFAXINATRABALHO = blnEXCLUSAOFAXINATRABALHO;
    
	SET NEW.DATAATUALIZACAO = CURRENT_TIMESTAMP;
    
END; $