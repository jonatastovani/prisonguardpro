INSERT INTO tab_usuarios (USUARIO, RSUSUARIO, NOME, APELIDO, RG, CPF, IDTURNO, IDCADASTRO, IPCADASTRO) VALUES
('michel', 15417840, 'Michel Skuja do Nascimento','Pé Grande','33.289.016-8','29306864809',5,2,'172.14.239.101');

select * from tab_usuarios;


insert into tab_usuariospermissoes (IDUSUARIO, IDPERMISSAO,IDCADASTRO,IPCADASTRO) VALUES
(7,9,2,'172.14.239.101')
;


SELECT * FROM tab_permissoes;
SELECT * FROM tab_usuariospermissoes;
