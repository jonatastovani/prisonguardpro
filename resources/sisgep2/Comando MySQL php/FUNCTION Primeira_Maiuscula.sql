DROP FUNCTION IF EXISTS Primeira_Maiuscula;
delimiter $$
CREATE FUNCTION Primeira_Maiuscula (input VARCHAR(255))

RETURNS VARCHAR(255)

DETERMINISTIC

BEGIN
    DECLARE len INT;
    DECLARE i INT;
	DECLARE palavra VARCHAR(255);
    DECLARE ipalavra INT;
    DECLARE iletras INT;
    
    SET len   = CHAR_LENGTH(input);
    SET input = LOWER(input); -- Opcional mas pode ser util quando queres formatar nomes
    SET i = 0;

    verifica_texto: WHILE (i < len) DO
        IF (MID(input,i,1) = ' ' OR i = 0) THEN
            IF (i < len) THEN
				
                SET ipalavra = i+1;
				SET iletras = 1;
                #Inicia a verificação da palavra completa
                verifica_palavra: WHILE (ipalavra <= len) DO
                
                    IF MID(input,ipalavra,1) = ' ' OR ipalavra = len THEN
						#Obtem  a próxima palavra completa
						SET palavra = MID(input,i+1,iletras-1);
                        #Verifica se a palavra é uma das abaixos para não deixar maiúscula
                        IF palavra IN ('da', 'das', 'de', 'do', 'dos', 'e', 'por', 'ou', 'em') THEN
							#Se for então continua o loop para a próxima palavra
                            SET i = ipalavra;
							ITERATE verifica_texto;
                            
						#Lista de Palavras que sempre serão maiúsculas, como por exemplo: Números Romanos
						ELSEIF palavra IN ('i', 'ii', 'iii', 'iv', 'v', 'vi') THEN
							SET input = CONCAT(
								LEFT(input,i),
								UPPER(MID(input,i + 1,iletras)),
								RIGHT(input,len - i - iletras)
							);
                            
							#Se for então continua o loop para a próxima palavra
                            SET i = ipalavra;
							ITERATE verifica_texto;
                        END IF;
                        
                        #Após verificar a palavra, então se saí deste loop
						LEAVE verifica_palavra;
                    END IF;
                    
					SET iletras = iletras + 1;
                    SET ipalavra = ipalavra + 1;
                END WHILE;
                
                #coloca a primeira letra da palavra em Maiúscula
                SET input = CONCAT(
                    LEFT(input,i),
                    UPPER(MID(input,i + 1,1)),
                    RIGHT(input,len - i - 1)
                );
            END IF;
        END IF;
        SET i = i + 1;
    END WHILE;

    RETURN input;
END; $$

