DROP TRIGGER INSERT_NovoUsuario;

delimiter $
CREATE TRIGGER INSERT_NovoUsuario
after insert ON tab_usuarios
FOR EACH ROW

begin
    insert into tab_usuariospermissoes (IDUSUARIO, DATACADASTRO, DATAALTERACAO) 
    values (NEW.ID, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
    
END; $
