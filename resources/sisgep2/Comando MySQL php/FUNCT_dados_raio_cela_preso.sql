DROP FUNCTION IF EXISTS FUNCT_dados_raio_cela_preso;
DELIMITER $$
CREATE FUNCTION FUNCT_dados_raio_cela_preso(intIDPreso INT, dateDataHora DATETIME, intTipoRetorno INT)
RETURNS VARCHAR(255)
DETERMINISTIC
BEGIN
	DECLARE intIDMudanca INT;
	DECLARE intIDMudancaAtual INT;
	DECLARE intIDRAIO INT;
	DECLARE chrRAIO VARCHAR(6);
	DECLARE intCELA INT;
	DECLARE chrRetorno VARCHAR(6);
    
    #BUSCA O ID DA MUDANÇA DE CELA NA DATA INFORMADA
	SET intIDMudanca = (SELECT ID FROM cadastros_mudancacela WHERE IDPRESO = intIDPreso AND DATACADASTRO <= dateDataHora AND DATAATUALIZACAO > dateDataHora ORDER BY ID DESC LIMIT 1);
	
	#BUSCA O LOCAL ATUAL
	SET intIDMudancaAtual = (SELECT ID FROM cadastros_mudancacela WHERE IDPRESO = intIDPreso AND DATACADASTRO <= dateDataHora AND (DATAATUALIZACAO >= dateDataHora OR DATAATUALIZACAO IS NULL) ORDER BY ID DESC LIMIT 1);

    IF intIDMudanca IS NULL THEN
		SET intIDMudanca = intIDMudancaAtual;
    END IF;
    
    SET intIDRAIO = (SELECT RAIO FROM cadastros_mudancacela WHERE ID = intIDMudanca);
    SET chrRAIO = (SELECT RC.NOME FROM cadastros_mudancacela CADMC
					INNER JOIN tab_raioscelas RC ON RC.ID = CADMC.RAIO
					WHERE CADMC.ID = intIDMudanca);
    SET intCELA = (SELECT CELA FROM cadastros_mudancacela WHERE ID = intIDMudanca);
    
	#TIPO 1 = RETORNA IDRAIO
	IF intTipoRetorno = 1 THEN
    	SET chrRetorno = intIDRAIO;
    
	#TIPO 2 = RETORNA NOME DO RAIO
	ELSEIF intTipoRetorno = 2 THEN
		SET chrRetorno = chrRAIO;
        
	#TIPO 3 = RETORNA CELA
	ELSEIF intTipoRetorno = 3 THEN
		SET chrRetorno = intCELA;
	
	#TIPO 4 = RETORNA IDMUDANCA
	ELSEIF intTipoRetorno = 4 THEN
		SET chrRetorno = intIDMudanca;
	
    END IF;
    
    return chrRetorno;
END; $$
