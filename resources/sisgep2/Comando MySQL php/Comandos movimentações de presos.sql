delete from chefia_boletim;
alter table chefia_boletim
auto_increment = 1;
-- Inserir novo número de Boletim
insert into chefia_boletim (NUMEROBOLETIM, PERIODODIURNO, DATABOLETIM, IDCADASTRO) VALUES
(DATEDIFF(CURRENT_DATE, CONCAT(YEAR(CURRENT_DATE),'-01-01')), TRUE, CURRENT_DATE, 2) ;
desc chefia_boletim;
select * from chefia_boletim;
select DATEDIFF(CURRENT_DATE, CONCAT(YEAR(CURRENT_DATE),'-01-01')) NUMEROBOLETIM;

-- Comando inserir mudança de cela.
truncate chefia_movimentacoespresos ;
DESC chefia_movimentacoespresos;

INSERT INTO chefia_movimentacoespresos (IDBOLETIM, MATRICULA, IDTIPOMOVIMENTACAO, RAIOORIGEM, CELAORIGEM, RAIODESTINO, CELADESTINO, IDCADASTRO, IPCADASTRO) VALUES
(23, 6773576, 10, 'MSP','01','TRAB','01',2,'172.14.239.101');
update chefia_movimentacoespresos SET RAIODESTINO = 'A',CELADESTINO = '03' WHERE ID =1;

update chefia_movimentacoespresos SET IDAPROVACAO = 2, IPAPROVACAO = '172.14.239.101' WHERE ID = 1;
update chefia_movimentacoespresos SET IDREALIZADO = 2, IPREALIZADO = '172.14.239.101' WHERE ID = 1;

-- Alvará
INSERT INTO chefia_movimentacoespresos (IDBOLETIM, MATRICULA, IDTIPOMOVIMENTACAO, RAIOORIGEM, CELAORIGEM, IDCADASTRO, IPCADASTRO) VALUES
(23, 6773576, 4, 'A','05',2,'172.14.239.101');
update chefia_movimentacoespresos SET RAIOORIGEM = 'B', CELAORIGEM = '01', IDAPROVACAO = 2, IPAPROVACAO = '172.14.239.101', IDREALIZADO = 2, IPREALIZADO = '172.14.239.101' WHERE ID = 2;
-- Deletenado Registro
update chefia_movimentacoespresos SET IDEXCLUSOREGISTRO = 2, IPEXCLUSOREGISTRO = '172.14.239.101' WHERE ID = 1;

select distinct(IDBOLETIM) from chefia_contagem WHERE IDBOLETIM = 24;
select * from chefia_movimentacoespresos WHERE IDBOLETIM = 24 ;
select * from inc_tiposmovimentacoes;

INSERT INTO chefia_movimentacoespresos (IDBOLETIM, MATRICULA, IDTIPOMOVIMENTACAO, RAIODESTINO, CELADESTINO, RAIOORIGEM, CELAORIGEM, IDCADASTRO, IPCADASTRO, NOMECOMPUTADORCADASTRO, IDAPROVACAO, IPAPROVACAO, NOMECOMPUTADORAPROVACAO, IDREALIZADO, IPREALIZADO, NOMECOMPUTADORREALIZADO) VALUES 
(24, '4410700', 10, 'D', '02', 'B' , '03' , 2 , '192.168.32.1' , 'CDAME-101' , 2 , '192.168.32.1' , 'CDAME-101' , 2 , '192.168.32.1' , 'CDAME-101' );