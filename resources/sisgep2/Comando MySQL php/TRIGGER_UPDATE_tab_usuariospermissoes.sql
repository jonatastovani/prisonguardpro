DROP TRIGGER IF EXISTS TRIGGER_UPDATE_tab_usuariospermissoes;

delimiter $
CREATE TRIGGER TRIGGER_UPDATE_tab_usuariospermissoes
BEFORE UPDATE ON tab_usuariospermissoes
FOR EACH ROW

begin
    
    SET NEW.DATAALTERACAO = CURRENT_TIMESTAMP;
    
END; $
