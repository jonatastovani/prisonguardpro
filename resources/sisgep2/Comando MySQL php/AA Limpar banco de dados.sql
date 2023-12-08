

truncate cimic_apresentacoes_log;
delete from cimic_apresentacoes;
ALTER TABLE cimic_apresentacoes auto_increment = 1;
truncate cimic_ordens_apresentacoes_log;
delete from cimic_ordens_apresentacoes;
ALTER TABLE cimic_ordens_apresentacoes auto_increment = 1;

truncate cimic_apresentacoes_internas_presos_log;
delete from cimic_apresentacoes_internas_presos;
ALTER TABLE cimic_apresentacoes_internas_presos auto_increment = 1;
truncate cimic_apresentacoes_internas_log;
delete from cimic_apresentacoes_internas;
ALTER TABLE cimic_apresentacoes_internas auto_increment = 1;

truncate cimic_transferencias_apres_log;
delete from cimic_transferencias_apres;
ALTER TABLE cimic_transferencias_apres auto_increment = 1;
truncate cimic_transferencias_intermed_log;
delete from cimic_transferencias_intermed;
ALTER TABLE cimic_transferencias_intermed auto_increment = 1;
truncate cimic_transferencias_log;
delete from cimic_transferencias;
ALTER TABLE cimic_transferencias auto_increment = 1;
truncate cimic_ordens_transferencias_log;
delete from cimic_ordens_transferencias;
ALTER TABLE cimic_ordens_transferencias auto_increment = 1;

truncate cimic_recebimentos_log;
delete from cimic_recebimentos;
ALTER TABLE cimic_recebimentos auto_increment = 1;

delete from tab_oficios;
ALTER TABLE tab_oficios auto_increment = 1;
delete from tab_ordemsaida;
ALTER TABLE tab_ordemsaida auto_increment = 1;

truncate chefia_mudancacelasituacao;
truncate chefia_mudancacela_log;
DELETE FROM chefia_mudancacela;
ALTER TABLE chefia_mudancacela auto_increment = 1;

truncate cadastros_telefones;
truncate cadastros_vulgos;
truncate cadastros_condenacao_log;
DELETE FROM cadastros_condenacao;
ALTER TABLE cadastros_condenacao auto_increment = 1;
truncate cadastros_movimentacoes_log;
DELETE FROM cadastros_movimentacoes;
ALTER TABLE cadastros_movimentacoes auto_increment = 1;
DELETE FROM cadastros_mudancacela;
ALTER TABLE cadastros_mudancacela auto_increment = 1;
truncate cadastros_log;
DELETE FROM cadastros;

truncate inc_pertences_log;
DELETE FROM inc_pertences WHERE ID > 0;
ALTER TABLE inc_pertences auto_increment = 1;

truncate entradas_artigos_log;
DELETE FROM entradas_artigos;
truncate entradas_presos_log;
DELETE FROM entradas_presos WHERE ID > 0;
ALTER TABLE entradas_presos auto_increment = 1;

DELETE FROM entradas_log;
DELETE FROM entradas WHERE ID > 0;
ALTER TABLE entradas auto_increment = 1;



