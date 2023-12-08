DROP TRIGGER IF EXISTS chefia_movimentacoespresos_INSERT_BEFORE;

delimiter $
CREATE TRIGGER chefia_movimentacoespresos_INSERT_BEFORE
before insert ON chefia_movimentacoespresos
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
	
IF NEW.IDREALIZADO IS NOT NULL THEN
	SET NEW.DATAREALIZADO = CURRENT_TIMESTAMP;

	-- Subtrai da onde o preso estava
	CALL AtualizaAlteracaoCelas_chefia_contagem(NEW.IDBOLETIM,FALSE,NEW.RAIOORIGEM,NEW.CELAORIGEM);
	
	IF NEW.IDTIPOMOVIMENTACAO = 10 THEN
		-- Quando a mudança foi realizada no raio, então se atualiza o SISGEP. Não será mais possível
		-- excluir essa movimentação após ser realizado a mudança.
	
		-- Somente se altera no sisgep se o preso em questão já estiver vinculado no sisgep
		IF NEW.IDRECIBO IS NULL THEN

			-- Completa informações da cela anteriormente ocupada pelo preso.    
			UPDATE sisgepteste.celas UPCelas
			JOIN (SELECT SLCelas.Cod_Id IDCELAANTIGA FROM sisgepteste.celas SLCelas WHERE Matric_Cel = NEW.MATRICULA ORDER BY Cod_Id DESC LIMIT 1) SL
			SET Fim_Cel = CURRENT_DATE, Dl_Cel = '*', 
			User_Cel = (SELECT RSUSUARIO FROM tab_usuarios WHERE ID = NEW.IDAPROVACAO), Ip_Cel = NEW.IPAPROVACAO
			WHERE Cod_Id = SL.IDCELAANTIGA;
			-- Insere a nova cela que o preso irá morar.
			INSERT INTO sisgepteste.celas (Matric_Cel, Cela_Cel, Pav_Cel, Ini_Cel, Dl_Cel, User_Cel, Ip_Cel) VALUES
			(NEW.MATRICULA, NEW.CELADESTINO, NEW.RAIODESTINO, CURRENT_DATE, '+',
			(SELECT RSUSUARIO FROM tab_usuarios WHERE ID = NEW.IDAPROVACAO), NEW.IPAPROVACAO);
			SET NEW.DATAREALIZADO = CURRENT_TIMESTAMP;
			
		END IF;
        
		CALL AtualizaAlteracaoCelas_chefia_contagem(NEW.IDBOLETIM,TRUE,NEW.RAIODESTINO,NEW.CELADESTINO);
		
	END IF;

END IF;
   
    SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	SET NEW.DATAATUALIZACAO = CURRENT_TIMESTAMP;
    SET NEW.IDATUALIZACAO = NEW.IDCADASTRO;
    SET NEW.IPATUALIZACAO = NEW.IPCADASTRO;
    SET NEW.NOMECOMPUTADORATUALIZACAO = NEW.NOMECOMPUTADORCADASTRO;
    SET NEW.INCLUSAOFAXINATRABALHO = blnINCLUSAOFAXINATRABALHO;
    SET NEW.EXCLUSAOFAXINATRABALHO = blnEXCLUSAOFAXINATRABALHO;
    
    IF NEW.IDAPROVACAO IS NOT NULL THEN
		SET NEW.DATAAPROVACAO = CURRENT_TIMESTAMP;
    END IF;
        
END; $
