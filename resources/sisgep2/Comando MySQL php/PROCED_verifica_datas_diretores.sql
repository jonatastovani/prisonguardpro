DROP PROCEDURE IF EXISTS PROCED_verifica_datas_diretores;
DELIMITER $$
CREATE PROCEDURE PROCED_verifica_datas_diretores(IN intIDPerm INT, IN intIDPermExist INT, IN blnTEMPORARIO BOOL, IN dateDataInicio DATE, IN dateDataTermino DATE, IN intIDCADASTRO INT, IN chrIPCADASTRO varchar(20))
BEGIN
	DECLARE intExistente INT DEFAULT 0;
	DECLARE dateInicioExistente DATE;
	DECLARE dateTerminoExistente DATE;
	
	IF intIDPermExist IS NULL THEN
		SET intIDPermExist = 0;
	END IF;

	IF blnTEMPORARIO = 1 THEN
		UPDATE tab_usuariospermissoes SET IDEXCLUSOREGISTRO = intIDCADASTRO, IPEXCLUSOREGISTRO = chrIPCADASTRO WHERE IDPERMISSAO = intIDPerm AND DATAINICIO >= dateDataInicio AND DATATERMINO <= dateDataTermino AND TEMPORARIO = 1 AND IDEXCLUSOREGISTRO IS NULL;

		repeticao: LOOP
			SET intExistente = (SELECT ID FROM tab_usuariospermissoes WHERE IDPERMISSAO = intIDPerm AND ID > intExistente AND ID <> intIDPermExist AND TEMPORARIO = 1 AND (DATAINICIO <= dateDataInicio AND  DATATERMINO >= dateDataInicio AND DATATERMINO <= dateDataTermino OR DATAINICIO >= dateDataInicio AND DATAINICIO <= dateDataTermino AND DATATERMINO >= dateDataTermino OR DATAINICIO <= dateDataInicio AND DATATERMINO >= dateDataTermino) AND IDEXCLUSOREGISTRO IS NULL ORDER BY ID ASC LIMIT 1);
			
			IF intExistente IS NULL THEN
				LEAVE repeticao;
			ELSE
				SET dateInicioExistente = (SELECT DATAINICIO FROM tab_usuariospermissoes WHERE ID = intExistente);
				SET dateTerminoExistente = (SELECT DATATERMINO FROM tab_usuariospermissoes WHERE ID = intExistente);
				
				IF dateInicioExistente < dateDataInicio THEN
					#Caso a permissão o intervalo inserido estiver no meio de um período de outro diretor, então se insere uma permissão com os dias restantes do diretor que será interrompido
					IF dateTerminoExistente > dateDataTermino THEN
                        INSERT INTO tab_usuariospermissoes (IDUSUARIO, IDPERMISSAO, TEMPORARIO, SUBSTITUTO, DATAINICIO, DATATERMINO, IDCADASTRO, IPCADASTRO, DATACADASTRO)
                        SELECT IDUSUARIO, IDPERMISSAO, TEMPORARIO, SUBSTITUTO, DATE_ADD(dateDataTermino, INTERVAL 1 DAY), DATATERMINO, IDCADASTRO, IPCADASTRO, DATACADASTRO FROM tab_usuariospermissoes WHERE ID = intExistente;
					END IF;
					
					UPDATE tab_usuariospermissoes SET DATATERMINO = DATE_ADD(dateDataInicio, INTERVAL -1 DAY), IDATUALIZACAO = intIDCADASTRO, IPATUALIZACAO = chrIPCADASTRO WHERE ID = intExistente;
				ELSE #IF dateTerminoExistente > dateDataTermino THEN
					UPDATE tab_usuariospermissoes SET DATAINICIO = DATE_ADD(dateDataTermino, INTERVAL 1 DAY), IDATUALIZACAO = intIDCADASTRO, IPATUALIZACAO = chrIPCADASTRO WHERE ID = intExistente;
				END IF;
			END IF;
			
		END LOOP;
        
	ELSE
		repeticao: LOOP
			SET intExistente = (SELECT ID FROM tab_usuariospermissoes WHERE IDPERMISSAO = intIDPerm AND ID > intExistente AND TEMPORARIO = 0 AND SUBSTITUTO = 0 AND DATATERMINO IS NULL AND IDEXCLUSOREGISTRO IS NULL ORDER BY ID ASC LIMIT 1);
			
			IF intExistente IS NULL THEN
				LEAVE repeticao;
			ELSE
				SET dateInicioExistente = (SELECT DATAINICIO FROM tab_usuariospermissoes WHERE ID = intExistente);
				SET dateTerminoExistente = CURRENT_DATE;
				
				#SE O INÍCIO FOR MENOR AO INÍCIO EXISTENTE
				IF dateTerminoExistente > dateInicioExistente THEN
					UPDATE tab_usuariospermissoes SET DATATERMINO = DATE_ADD(dateDataInicio, INTERVAL -1 DAY), IDATUALIZACAO = intIDCADASTRO, IPATUALIZACAO = chrIPCADASTRO WHERE ID = intExistente;
				ELSE
					UPDATE tab_usuariospermissoes SET IDEXCLUSOREGISTRO = intIDCADASTRO, IPEXCLUSOREGISTRO = chrIPCADASTRO WHERE ID = intExistente;
				END IF;
            END IF;
            
		END LOOP;
        
    END IF;
    
END; $$

desc tab_usuariospermissoes;