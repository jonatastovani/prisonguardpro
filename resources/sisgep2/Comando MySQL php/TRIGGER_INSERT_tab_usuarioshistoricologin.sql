DROP TRIGGER IF EXISTS TRIGGER_INSERT_tab_usuarioshistoricologin;

delimiter $
CREATE TRIGGER TRIGGER_INSERT_tab_usuarioshistoricologin
BEFORE INSERT ON tab_usuarioshistoricologin
FOR EACH ROW

begin
    SET NEW.HORA = CURRENT_TIMESTAMP;
    
    UPDATE tab_usuarios SET QTDERROSENHA = 0, QTDERROPERGUNTASEGURANCA = 0, SOLICITACAOREDEFINIRSENHA = FALSE WHERE ID = NEW.IDUSUARIO;
        
	UPDATE tab_usuarioshistoricobloqueios SET PRAZOINTERROMPIDO = TRUE WHERE IDUSUARIO = NEW.IDUSUARIO AND TIMESTAMPDIFF(MINUTE,HORALIBERACAO,CURRENT_TIMESTAMP) <= 0;
        
END; $