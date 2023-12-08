DROP PROCEDURE IF EXISTS PROCED_verifica_permissoes;
DELIMITER $$
CREATE PROCEDURE PROCED_verifica_permissoes(IN intIDFunc INT, IN intIDPerm INT, IN blnValor INT, IN intIDCADASTRO INT, IN chrIPCADASTRO varchar(20))
BEGIN
	DECLARE intExistente INT;
    
    SET intExistente = (SELECT ID FROM tab_usuariospermissoes WHERE IDUSUARIO = intIDFunc AND IDPERMISSAO = intIDPerm AND IDEXCLUSOREGISTRO IS NULL);
    
    IF intExistente IS NOT NULL AND blnValor = 0 THEN
		UPDATE tab_usuariospermissoes SET IDEXCLUSOREGISTRO = intIDCADASTRO, IPEXCLUSOREGISTRO = chrIPCADASTRO WHERE ID = intExistente;
	ELSEIF intExistente IS NULL AND blnValor = 1 THEN
		INSERT INTO tab_usuariospermissoes (IDUSUARIO, IDPERMISSAO, IDCADASTRO, IPCADASTRO) VALUES (intIDFunc, intIDPerm, intIDCADASTRO, chrIPCADASTRO);
    END IF;
END; $$