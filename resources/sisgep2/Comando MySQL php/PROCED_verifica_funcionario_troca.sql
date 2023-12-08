DROP PROCEDURE IF EXISTS PROCED_verifica_funcionario_troca;
DELIMITER $$
CREATE PROCEDURE PROCED_verifica_funcionario_troca(IN intIDUsuario INT, IN intIDPosto INT, IN intIDFunc INT, IN intIDCADASTRO INT, IN chrIPCADASTRO varchar(20))
BEGIN
	DECLARE intExistente INT;
	DECLARE blnInserir BOOL DEFAULT FALSE;
    
    #Verifica se existe uma troca para o ID do funcionário informado
	SET intExistente = (SELECT ID FROM funcionarios_escalaplantao_troca WHERE IDFUNC = intIDFunc AND IDEXCLUSOREGISTRO IS NULL ORDER BY ID DESC LIMIT 1);
    
	#Se não existe então vai ser inserido
    IF intExistente IS NULL THEN
		SET blnInserir = TRUE;
    ELSE
		#Se existe então é verificado se é o mesmo troca informado, se não for o mesmo troca então é excluído o troca existente para poder inserir o novo troca
		IF (SELECT IDUSUARIO FROM funcionarios_escalaplantao_troca WHERE ID = intExistente) <> intIDUsuario THEN
			UPDATE funcionarios_escalaplantao_troca SET IDEXCLUSOREGISTRO = intIDCADASTRO, IPEXCLUSOREGISTRO = chrIPCADASTRO WHERE IDFUNC = intIDFunc AND IDEXCLUSOREGISTRO IS NULL;
            SET blnInserir = TRUE;
		ELSE
			#Se for o mesmo troca então se atualiza o posto
			UPDATE funcionarios_escalaplantao_troca SET IDPOSTO = intIDPosto, IDATUALIZACAO = intIDCADASTRO, IPATUALIZACAO = chrIPCADASTRO WHERE IDFUNC = intIDFunc AND IDEXCLUSOREGISTRO IS NULL;
        END IF;
    END IF;
    
    #Insere o troca
    IF blnInserir = TRUE THEN
		INSERT INTO funcionarios_escalaplantao_troca (IDUSUARIO,IDPOSTO,IDFUNC,IDCADASTRO,IPCADASTRO) VALUES (intIDUsuario,intIDPosto,intIDFunc,intIDCADASTRO,chrIPCADASTRO);
    END IF;
    
END; $$

DESC funcionarios_escalaplantao_troca;