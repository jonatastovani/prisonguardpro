DROP TRIGGER IF EXISTS funcionarios_escalapostos_INSERT_BEFORE;

delimiter $
CREATE TRIGGER funcionarios_escalapostos_INSERT_BEFORE
BEFORE INSERT ON funcionarios_escalapostos
FOR EACH ROW

BEGIN
 	declare intNumeroDeOrdem int default 1;
 	declare intUltimaOrdem int;
    
    SET intUltimaOrdem = (SELECT MAX(ORDEM) + 1 FROM funcionarios_escalapostos WHERE IDTURNO = NEW.IDTURNO AND IDTIPO = NEW.IDTIPO);
 	
    -- SETA o número de ordem automaticamente
     IF intUltimaOrdem IS NOT NULL THEN
 		SET intNumeroDeOrdem = intUltimaOrdem;
 	END IF;
    
	SET NEW.ORDEM = intNumeroDeOrdem;

	IF NEW.DATACADASTRO IS NULL OR NEW.DATACADASTRO = '' OR NEW.DATACADASTRO = '0000-00-00 00:00:00' THEN
		SET NEW.DATACADASTRO = CURRENT_TIMESTAMP;
	END IF;
 END $
DESC funcionarios_escalapostos;