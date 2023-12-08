
ALTER table nome_tabela 
ADD column IDCADASTRO INT NOT NULL,
ADD column IPCADASTRO varchar(20) default NULL,
ADD column DATACADASTRO datetime NOT NULL,
ADD column IDATUALIZACAO INT default NULL,
ADD column IPATUALIZACAO varchar(20) default NULL,
ADD column DATAATUALIZACAO datetime default NULL,
ADD column IDEXCLUSOREGISTRO INT default NULL,
ADD column IPEXCLUSOREGISTRO varchar(20) default NULL,
ADD column DATAEXCLUSOREGISTRO datetime default NULL,
ADD foreign key(IDCADASTRO) references tab_usuarios(ID),
ADD foreign key(IDATUALIZACAO) references tab_usuarios(ID),
ADD foreign key(IDEXCLUSOREGISTRO) references tab_usuarios(ID);
