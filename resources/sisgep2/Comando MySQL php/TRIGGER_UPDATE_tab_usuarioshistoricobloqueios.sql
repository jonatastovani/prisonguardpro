DROP TRIGGER IF EXISTS TRIGGER_UPDATE_tab_usuarioshistoricobloqueios;

delimiter $
CREATE TRIGGER TRIGGER_UPDATE_tab_usuarioshistoricobloqueios
BEFORE UPDATE ON tab_usuarioshistoricobloqueios
FOR EACH ROW

begin
    IF NEW.PRAZOINTERROMPIDO = TRUE THEN
		SET NEW.HORAINTERROMPIDO = CURRENT_TIMESTAMP;
    END IF;
            
END; $

