SELECT * FROM funcionarios_escalatipo;
SELECT * FROM chefia_escalapostos;
SELECT * FROM tab_usuarios;
desc tab_usuarios;

#Inserindo funcionários na escala mensal
INSERT INTO chefia_escalamensal (IDTURNO, IDTIPO, IDPOSTO, IDUSUARIO, IDCADASTRO, IPCADASTRO) VALUES 
(1,1,4,9,2,'172.14.239.101');
select * from chefia_escalamensal;

#INSERINDO UMA ESCALA DE PLANTÃO DO DIA
INSERT INTO chefia_escalaplantao (IDTIPO,IDCADASTRO,IPCADASTRO) VALUES (1,2,'172.14.239.101');

select * from chefia_escalaplantao order by ID DESC;

#INSERINDO FUNCIONÁRIOS DA ESCALA MENSAL NA ESCALA DE PLANTÃO, MAS NA VERDADE SERÁ INSERIDO UM POR UM COM AS ALTERAÇÕES DA CHEFIA
INSERT INTO chefia_escalaplantao_func (IDESCALA, IDPOSTO, IDUSUARIO, IDCADASTRO, IPCADASTRO)
SELECT 1, IDPOSTO, IDUSUARIO, 2, '172.14.239.101' FROM chefia_escalamensal WHERE IDTURNO = 1 AND IDTIPO = 1 AND IDEXCLUSOREGISTRO IS NULL;

select * from chefia_escalaplantao_func;


