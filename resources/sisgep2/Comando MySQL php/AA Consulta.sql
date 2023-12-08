TRUNCATE tab_computadores_aute;

ALTER TABLE tab_computadores_aute
ADD column DESCRICAO tinytext NOT NULL after IDPERMISSAO;

INSERT INTO tab_computadores_aute (IP,IDPERMISSAO,DESCRICAO,IDCADASTRO,IPCADASTRO) VALUES ('172.14.239.101',1,'PC CPD',2,'172.14.239.101');

SELECT * FROM tab_computadores_aute;
