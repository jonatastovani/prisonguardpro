DROP FUNCTION IF EXISTS FUNCT_dados_cela_excecao;
DELIMITER $$
CREATE FUNCTION FUNCT_dados_cela_excecao(intIDMudanca INT, intTipoRetorno INT)
RETURNS VARCHAR(255)
DETERMINISTIC

BEGIN
	DECLARE intIDMudancaAnterior INT;
	DECLARE intIDMudancaFinal INT;
	DECLARE intIDRAIOORIGEM INT;
	DECLARE intCELAORIGEM INT;
	DECLARE dateDATAORIGEM DATETIME;
	DECLARE intIDRAIODESTINO INT;
	DECLARE intCELADESTINO INT;
	DECLARE dateDATADESTINO DATETIME;
	/*DECLARE intIDRAIOFINAL INT DEFAULT NULL;
	DECLARE intCELADFINAL INT DEFAULT NULL;
	DECLARE dateDATAFINAL DATETIME DEFAULT NULL;*/
	DECLARE intRetornoOrigem INT;
	DECLARE intRetornoDestino INT;
	DECLARE chrRetorno VARCHAR(4) DEFAULT 0;
    
	IF intTipoRetorno IN (1,2,4) THEN

		SET intIDMudancaAnterior = (SELECT CMUD.ID FROM cadastros_mudancacela CMUD WHERE CMUD.IDPRESO = (SELECT CMUD2.IDPRESO FROM cadastros_mudancacela CMUD2 WHERE CMUD2.ID = intIDMudanca) AND CMUD.ID < intIDMudanca AND CMUD.IDEXCLUSOREGISTRO IS NULL ORDER BY CMUD.ID DESC LIMIT 1);
	
	#SET intIDMudancaFinal = (SELECT CMUD.ID FROM cadastros_mudancacela CMUD WHERE CMUD.IDPRESO = (SELECT CMUD2.IDPRESO FROM cadastros_mudancacela CMUD2 WHERE CMUD2.ID = intIDMudanca) AND CMUD.ID > intIDMudanca AND CMUD.IDEXCLUSOREGISTRO IS NULL ORDER BY CMUD.ID DESC LIMIT 1);
	
		SET intIDRAIOORIGEM = (SELECT RAIO FROM cadastros_mudancacela WHERE ID = intIDMudancaAnterior);
		SET intCELAORIGEM = (SELECT CELA FROM cadastros_mudancacela WHERE ID = intIDMudancaAnterior);
		SET dateDATAORIGEM = (SELECT DATACADASTRO FROM cadastros_mudancacela WHERE ID = intIDMudancaAnterior);
    
    END IF;
    
    IF intTipoRetorno IN (1,2,3) THEN
    
		#BUSCA OS DADOS DA CELA ATUAL PARA VER SE É CELA DE EXCEÇÃO DE TRABALHO
		SET intIDRAIODESTINO = (SELECT RAIO FROM cadastros_mudancacela WHERE ID = intIDMudanca);
		SET intCELADESTINO = (SELECT CELA FROM cadastros_mudancacela WHERE ID = intIDMudanca);
		SET dateDATADESTINO = (SELECT DATACADASTRO FROM cadastros_mudancacela WHERE ID = intIDMudanca);
	
    END IF;
    
    /*#SE NÃO HAVER MAIS REGISTRO DE MUDANÇAS ENTÃO É VERIFICADO SE A ULTIMA CELA FOI PREENCHIDA, PARA DAR ENCERRAMENTO AO PERIODO DE TRABALHO
    IF intIDMudancaFinal IS NULL THEN
		SET intIDRAIOFINAL = (SELECT RAIOALTERADO FROM cadastros_mudancacela WHERE ID = intIDMudanca);
		SET intCELADFINAL = (SELECT CELAALTERADO FROM cadastros_mudancacela WHERE ID = intIDMudanca);
		SET dateDATAFINAL = (SELECT DATAATUALIZACAO FROM cadastros_mudancacela WHERE ID = intIDMudanca);
	END IF;*/
    
    /***********************/
    # intTipoRetorno = 1 e 2
		# Funções que verifica se o ID informado é de uma mudança de cela de entrada, saída ou ambos (saindo e entrando) das celas de remissão
        # intTipoRetorno = 1: Retornado a quantidade de registro encontrado
        # intTipoRetorno = 2: Retornado o tipo de registro encontrado
			# Z = O preso está saindo e entrando em cela de remissão
			# X = O preso está entrando  em cela de remissão
			# Y = O preso está saindo de cela de remissão
			# 0 = Essa movimentação não é de cela de remissão
    # intTipoRetorno = 3
		# Funções que verifica se o ID informado é de uma mudança de cela de entrada em uma cela de remissão
			# 1 = para verdadeiro
			# 0 = para falso
    # intTipoRetorno = 4
		# Funções que verifica se o ID informado é de uma mudança de cela de saída de uma cela de remissão
			# 1 = para verdadeiro
			# 0 = para falso
	    /***********************/

    
    IF intTipoRetorno = 1 THEN
		SET chrRetorno = (SELECT count(*) FROM tab_raioscelasexcecoes WHERE IDTIPO IN (SELECT ID FROM tab_raioscelasexcecoestipo WHERE CELAREMISSAO = TRUE) AND
		((IDRAIO = intIDRAIOORIGEM AND CELA = intCELAORIGEM AND DATAINICIO <= dateDATAORIGEM AND (DATATERMINO >= dateDATAORIGEM OR DATATERMINO IS NULL)) OR 
		(IDRAIO = intIDRAIODESTINO AND CELA = intCELADESTINO AND DATAINICIO <= dateDATADESTINO AND (DATATERMINO >= dateDATADESTINO OR DATATERMINO IS NULL)) /*OR 
		(IDRAIO = intIDRAIOFINAL AND CELA = intCELADFINAL AND DATAINICIO <= dateDATAFINAL AND (DATATERMINO >= dateDATAFINAL OR DATATERMINO IS NULL))*/));
    
    ELSEIF intTipoRetorno IN (2, 3, 4) THEN
    
		IF intTipoRetorno IN (2, 3) THEN
			SET intRetornoDestino = (SELECT count(*) FROM tab_raioscelasexcecoes WHERE IDTIPO IN (SELECT ID FROM tab_raioscelasexcecoestipo WHERE CELAREMISSAO = TRUE) AND (IDRAIO = intIDRAIODESTINO AND CELA = intCELADESTINO AND DATAINICIO <= dateDATADESTINO AND (DATATERMINO >= dateDATADESTINO OR DATATERMINO IS NULL)));
		END IF;
        
        IF intTipoRetorno IN (2, 4) THEN
			SET intRetornoOrigem = (SELECT count(*) FROM tab_raioscelasexcecoes WHERE IDTIPO IN (SELECT ID FROM tab_raioscelasexcecoestipo WHERE CELAREMISSAO = TRUE) AND (IDRAIO = intIDRAIOORIGEM AND CELA = intCELAORIGEM AND DATAINICIO <= dateDATAORIGEM AND (DATATERMINO >= dateDATAORIGEM OR DATATERMINO IS NULL)));
		END IF;
        
        IF intTipoRetorno = 2 THEN
			IF intRetornoDestino > 0 AND intRetornoOrigem > 0 THEN
				SET chrRetorno = 'Z';
			ELSEIF intRetornoDestino > 0 THEN
				SET chrRetorno = 'X';
			ELSEIF intRetornoOrigem > 0 THEN
				SET chrRetorno = 'Y';
			ELSE
				SET chrRetorno = '0';
			END IF;
		ELSE
			IF intTipoRetorno = 3 THEN
				SET chrRetorno = intRetornoDestino;
			ELSEIF intTipoRetorno = 4 THEN
				SET chrRetorno = intRetornoOrigem;
			END IF;
		END IF;
    
    END IF;

    RETURN chrRetorno;
END; $$
