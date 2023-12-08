DROP PROCEDURE IF EXISTS PROCED_verifica_permissoes;
DELIMITER $$
CREATE PROCEDURE PROCED_verifica_permissoes(IN intIDFunc INT, IN intIDPerm INT, IN blnValor INT, IN blnSubstituto BOOL, IN intIDBoletim INT, IN blnTEMPORARIO BOOL, IN dateDataInicio DATE, IN dateDataTermino DATE, IN intIDCADASTRO INT, IN chrIPCADASTRO varchar(20))

BEGIN
	DECLARE intExistente INT;
	DECLARE blnPermDiretor BOOL;
	DECLARE blnEncerraExistente BOOL DEFAULT FALSE;
	DECLARE dateInicioExistente DATE;
	DECLARE dateTerminoExistente DATE;
	DECLARE blnTemporarioExistente BOOL;
	DECLARE blnSubstitutoExistente BOOL;

   IF dateDataInicio = 0 THEN
		SET dateDataInicio = CURRENT_DATE;
	END IF;
	
    #Verifica se existe a permissão para este usuário
    SET intExistente = (SELECT ID FROM tab_usuariospermissoes WHERE IDUSUARIO = intIDFunc AND IDPERMISSAO = intIDPerm AND (DATAINICIO IS NULL AND DATATERMINO IS NULL OR DATAINICIO IS NOT NULL AND DATATERMINO IS NULL OR DATATERMINO >= dateDataInicio) AND IDEXCLUSOREGISTRO IS NULL ORDER BY ID DESC LIMIT 1);
    
    #Verifica se é uma permissão de diretor
    SET blnPermDiretor = (SELECT DIRETOR FROM tab_permissoes WHERE ID = intIDPerm);
    
    IF blnPermDiretor = 1 THEN
		#Se existe é verificado se é substituto ou temporária
		IF intExistente IS NOT NULL AND blnValor = 1 THEN
			#INSERT INTO teste (NOME) VALUES(concat(case when intExistente is null then 'vazio' else intExistente end,' (',CURRENT_TIMESTAMP, ') Linha 29'));
            
			SET dateInicioExistente = (SELECT DATAINICIO FROM tab_usuariospermissoes WHERE ID = intExistente);
			SET dateTerminoExistente = (SELECT DATATERMINO FROM tab_usuariospermissoes WHERE ID = intExistente);
			SET blnTemporarioExistente = (SELECT TEMPORARIO FROM tab_usuariospermissoes WHERE ID = intExistente);
			SET blnSubstitutoExistente = (SELECT SUBSTITUTO FROM tab_usuariospermissoes WHERE ID = intExistente);
			
			#Se a permissão existente é diferente a que está sendo inserida em relação a ser temporária, se exclui a temporária para inserir a oficial, do contrário a permissão oficial tem preferência
			IF blnTemporarioExistente <> blnTEMPORARIO THEN
				#INSERT INTO teste (NOME) VALUES(concat(case when intExistente is null then 'vazio' else intExistente end,' (',CURRENT_TIMESTAMP, ') Linha 38'));
                
				#Exclui ou termina o período com data anterior porque vai deixar de ser temporária
				IF blnTEMPORARIO = 0 THEN
					#Se o início da permissão termporária for menor que a indeterminada, então se encerra a temporária com a data do dia anterior
					IF dateInicioExistente >= dateDataInicio THEN
						SET blnEncerraExistente = TRUE;
					ELSEIF dateInicioExistente < dateDataInicio THEN
						UPDATE tab_usuariospermissoes SET DATATERMINO = DATE_ADD(dateDataInicio, INTERVAL -1 DAY), IDATUALIZACAO = intIDCADASTRO, IPATUALIZACAO = chrIPCADASTRO WHERE ID = intExistente;
					END IF;
                /*ELSE
					#Se a permissão temporária iniciar maior que a data de início da indeterminada, então se encerra a indeterminada um dia antes
					IF dateInicioExistente < dateDataInicio THEN
						UPDATE tab_usuariospermissoes SET DATATERMINO = DATE_ADD(dateDataInicio, INTERVAL -1 DAY), IDATUALIZACAO = intIDCADASTRO, IPATUALIZACAO = chrIPCADASTRO WHERE ID = intExistente;
					ELSE
						SET blnEncerraExistente = TRUE;
					END IF;*/
                END IF;
                
			#Se for temporário tanto a que existe quando a que está inserindo
			ELSE
				#Se for temporária então se atualiza o período do término caso não for alterado a questão de ser substituto
				IF blnTEMPORARIO = 1 AND blnSubstitutoExistente = blnSubstituto THEN
					#INSERT INTO teste (NOME) VALUES(concat(case when intExistente is null then 'vazio' else intExistente end,' ',CURRENT_TIMESTAMP, ' Linha 48'));
                    
                    #Somente modifica as datas se não for substituto
                    IF blnSubstituto = 0 THEN
						CALL PROCED_verifica_datas_diretores(intIDPerm, intExistente, blnTEMPORARIO, dateDataInicio, dateDataTermino, intIDCADASTRO, chrIPCADASTRO);
                    END IF;
                    
					#INSERT INTO teste (NOME) VALUES(concat((SELECT IDEXCLUSOREGISTRO FROM tab_usuariospermissoes WHERE ID = intExistente),' ',CURRENT_TIMESTAMP, ' Valor do IDEXCLUSO 55'));
                    
                    #Somente se aumenta as datas, não reduz. Para reduzir terá que excluir a existente e inserir com o perídodo menor
                    #Aumenta a data término pois a permissão tem o mesmo início
                    IF dateInicioExistente = dateDataInicio AND dateTerminoExistente < dateDataTermino THEN
						UPDATE tab_usuariospermissoes SET DATATERMINO = dateDataTermino, IDATUALIZACAO = intIDCADASTRO, IPATUALIZACAO = chrIPCADASTRO WHERE ID = intExistente;
					#Aumenta a data início pois foi aumentado o período da permissão, tendo o mesmo término
                    ELSEIF dateInicioExistente > dateDataInicio AND dateTerminoExistente = dateDataTermino THEN
						UPDATE tab_usuariospermissoes SET DATAINICIO = dateDataInicio, IDATUALIZACAO = intIDCADASTRO, IPATUALIZACAO = chrIPCADASTRO WHERE ID = intExistente;
                    ELSE
						#Libera para inserir o novo perído, pois não tem o que fazer com a permissão existente devido ela compreender a outro período
						SET intExistente = NULL;
                    END IF;
					
                END IF;
			END IF;
			
			#Quando a permissão permite ter substitutos
			IF blnSubstitutoExistente <> blnSubstituto THEN
				SET blnEncerraExistente = TRUE;
			END IF;
			
			IF blnEncerraExistente = TRUE THEN
				UPDATE tab_usuariospermissoes SET IDEXCLUSOREGISTRO = intIDCADASTRO, IPEXCLUSOREGISTRO = chrIPCADASTRO WHERE ID = intExistente;
				SET intExistente = NULL;
			END IF;
		END IF;
	END IF;
    
    IF intExistente IS NOT NULL AND blnValor = 0 THEN
		#Para excluir por aqui, será somente excluído permissões oficiais (Não temporárias)
		UPDATE tab_usuariospermissoes SET IDEXCLUSOREGISTRO = intIDCADASTRO, IPEXCLUSOREGISTRO = chrIPCADASTRO WHERE ID = intExistente AND TEMPORARIO = 0;
	
	ELSEIF intExistente IS NULL AND blnValor = 1 THEN
		SET @intUsuario = intIDFunc;
		#Se for permissão de Diretor então faz as alterações de permissões de diretores
		IF blnPermDiretor = 1 THEN
            SET intIDBoletim = NULL;
            
			IF dateDataInicio = 0 THEN
				SET dateDataInicio = CURRENT_DATE;
			END IF;

			#Se não for substituto então se encerra o diretor e insere este novo
			IF blnSubstituto = 0 THEN
            
				#Altera as datas de início e término
				CALL PROCED_verifica_datas_diretores(intIDPerm, 0, blnTEMPORARIO, dateDataInicio, dateDataTermino, intIDCADASTRO, chrIPCADASTRO);
				/*IF blnTEMPORARIO = 0 THEN
					UPDATE tab_usuariospermissoes SET DATATERMINO = dateDataInicio, IDATUALIZACAO = intIDCADASTRO, IPATUALIZACAO = chrIPCADASTRO WHERE IDPERMISSAO = intIDPerm AND DATATERMINO IS NULL AND DATAINICIO <= dateDataInicio AND TEMPORARIO = 0 AND IDEXCLUSOREGISTRO IS NULL;
                    
				END IF;*/
			
            END IF;
            
			#Insere o novo diretor
			INSERT INTO tab_usuariospermissoes (IDUSUARIO, IDPERMISSAO, SUBSTITUTO, DATAINICIO, DATATERMINO, IDBOLETIMPERMISSAO, TEMPORARIO, IDCADASTRO, IPCADASTRO)
			VALUES (intIDFunc, intIDPerm, blnSubstituto, dateDataInicio, dateDataTermino, intIDBoletim, blnTEMPORARIO, intIDCADASTRO, chrIPCADASTRO);

		ELSE
			IF blnTEMPORARIO = 0 THEN
				SET dateDataInicio = 0;
            END IF;
			#Se não for diretor então se insere normalmente a permissão
			INSERT INTO tab_usuariospermissoes (IDUSUARIO, IDPERMISSAO, DATAINICIO, DATATERMINO, IDBOLETIMPERMISSAO, TEMPORARIO, IDCADASTRO, IPCADASTRO)
            VALUES (intIDFunc, intIDPerm, dateDataInicio, dateDataTermino, intIDBoletim, blnTEMPORARIO, intIDCADASTRO, chrIPCADASTRO);
		END IF;

    END IF;
END; $$

DESC tab_usuariospermissoes;