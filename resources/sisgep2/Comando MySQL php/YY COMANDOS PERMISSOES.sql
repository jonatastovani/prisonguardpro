select * from tab_usuarios;
select * from tab_permissoes ORDER BY ID DESC;
select * from tab_usuariospermissoes;

UPDATE tab_permissoes SET NOME = 'DDCSD', DESCRICAO = 'Diretor de Divisão do Centro de Segurança e Disciplina. Permissão total à todas áreas da responsabilidade da Disciplina e Segurança, podendo atribuir permissões para usuários neste seguimento.', IDSETORPAI = 2, IDGRUPO = 2 WHERE ID = 53;

#Criar permissões
INSERT INTO tab_permissoes (NOME, DESCRICAO, IDSETORPAI, IDGRUPO, ORDEM) VALUES
('Penal Turno I', 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.', 54,16,NULL),
('Penal Turno II', 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.', 55,16,NULL),
('Penal Turno III', 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.', 56,16,NULL),
('Penal Turno IV', 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.', 57,16,NULL)
;


#Inserir permissões para usuários
INSERT INTO tab_usuariospermissoes (IDUSUARIO, IDPERMISSAO, IDCADASTRO, IPCADASTRO) VALUES
(4, 9, 2, '172.14.239.101');

UPDATE tab_permissoesgrupo SET ID = ID + 1 WHERE ID > 1 ORDER BY ID DESC;
UPDATE tab_permissoesgrupo SET NOME = 'Gerenciar Administradores de Núcleos' WHERE ID = 3;
UPDATE tab_permissoes SET NOME = 'Administrador Setor Chefia' WHERE ID = 41;
UPDATE tab_permissoes SET DESCRICAO = 'Imprimir Ofício de Escolta dos Presos para as Transferências.' WHERE ID = 52;

INSERT INTO tab_permissoesgrupo (NOME) VALUES
('Gerenciar Penal')
;

select * from tab_permissoesgrupo ORDER BY ID DESC;
SELECT * FROM tab_permissoes WHERE IDSETORPAI = 9 AND IDGRUPO = 12 order by ORDEM;

set @idgrupo = 12;

UPDATE tab_permissoes SET IDGRUPO = @idgrupo WHERE ID = 42;
UPDATE tab_permissoes SET IDGRUPO = @idgrupo WHERE ID = 40;
UPDATE tab_permissoes SET IDGRUPO = @idgrupo WHERE ID = 41;
UPDATE tab_permissoes SET IDGRUPO = @idgrupo WHERE ID = 37;
UPDATE tab_permissoes SET IDGRUPO = @idgrupo WHERE ID = 38;
UPDATE tab_permissoes SET IDGRUPO = @idgrupo WHERE ID = 39;

SELECT * FROM tab_permissoes ;



