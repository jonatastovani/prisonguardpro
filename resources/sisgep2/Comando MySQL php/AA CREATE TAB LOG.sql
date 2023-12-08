DROP TABLE IF exists `nome_tabela_log`;
CREATE TABLE `nome_tabela_log` (
	`ID` INT(11) NOT NULL AUTO_INCREMENT,
	`IDREFERENCIA` INT(11) NOT NULL,
	`CAMPOATUALIZADO` VARCHAR(30) NOT NULL COLLATE 'utf8_general_ci',
	`ANTIGO` TEXT NULL DEFAULT NULL,
	`NOVO` TEXT NOT NULL,
	`IDATUALIZACAO` INT(11) NOT NULL,
	`DATAATUALIZACAO` DATETIME NOT NULL,
	`IPATUALIZACAO` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	PRIMARY KEY (`ID`),
	INDEX `IDREFERENCIA` (`IDREFERENCIA`),
	INDEX `IDATUALIZACAO` (`IDATUALIZACAO`),
	CONSTRAINT `nome_tabela_log_ibfk_1` FOREIGN KEY (`IDREFERENCIA`) REFERENCES `sistemaphp`.`nome_tabela` (`ID`) ON UPDATE RESTRICT ON DELETE RESTRICT,
	CONSTRAINT `nome_tabela_log_ibfk_2` FOREIGN KEY (`IDATUALIZACAO`) REFERENCES `sistemaphp`.`tab_usuarios` (`ID`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
DESC nome_tabela