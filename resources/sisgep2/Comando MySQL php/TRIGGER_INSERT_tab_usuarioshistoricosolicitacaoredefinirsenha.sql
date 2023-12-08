DROP TRIGGER IF EXISTS TRIGGER_INSERT_tab_usuarioshistoricosolicitacaoredefinirsenha;

delimiter $
CREATE TRIGGER TRIGGER_INSERT_tab_usuarioshistoricosolicitacaoredefinirsenha
BEFORE INSERT ON tab_usuarioshistoricosolicitacaoredefinirsenha
FOR EACH ROW

begin
	SET NEW.HORA = CURRENT_TIMESTAMP;
	UPDATE tab_usuarios SET SOLICITACAOREDEFINIRSENHA = TRUE WHERE ID = NEW.IDUSUARIO;
	
END; $

select * from tab_usuarioshistoricosolicitacaoredefinirsenha;
desc tab_usuarioshistoricosolicitacaoredefinirsenha;