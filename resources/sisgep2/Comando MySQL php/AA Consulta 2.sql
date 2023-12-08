
SET @dateDataInicio = '2022-10-13';
SET @dateDataTermino = '2022-10-19';
SET @intIDPerm = 54;
CALL PROCED_verifica_datas_diretores(@intIDPerm, 13, 1, @dateDataInicio, @dateDataTermino, 2, '172.14.239.103');

SET @intExistente = (SELECT ID FROM tab_usuariospermissoes WHERE IDUSUARIO = 37 AND IDPERMISSAO = @intIDPerm AND (DATAINICIO IS NULL AND DATATERMINO IS NULL OR DATAINICIO IS NOT NULL AND DATATERMINO IS NULL OR DATAINICIO >= @dateDataInicio AND DATATERMINO >= @dateDataInicio) AND IDEXCLUSOREGISTRO IS NULL ORDER BY ID DESC LIMIT 1);
SELECT @intExistente;
    

SET @intExistente = (SELECT ID FROM tab_usuariospermissoes WHERE IDPERMISSAO = @intIDPerm AND ID > 0 AND ID <> 0 AND TEMPORARIO = 1 AND (DATAINICIO <= @dateDataInicio AND  DATATERMINO >= @dateDataInicio AND DATATERMINO <= @dateDataTermino OR DATAINICIO >= @dateDataInicio AND DATAINICIO <= @dateDataTermino AND DATATERMINO >= @dateDataTermino OR DATAINICIO <= @dateDataInicio AND DATATERMINO >= @dateDataTermino) AND IDEXCLUSOREGISTRO IS NULL ORDER BY ID ASC LIMIT 1);
SELECT @intExistente;

SET @dateInicioExistente = (SELECT DATAINICIO FROM tab_usuariospermissoes WHERE ID = @intExistente);
SET @dateTerminoExistente = (SELECT DATATERMINO FROM tab_usuariospermissoes WHERE ID = @intExistente);
				
SELECT @intExistente, @dateInicioExistente, @dateTerminoExistente ;

SELECT @dateInicioExistente < '2022-10-19';

SELECT * FROM tab_usuariospermissoes ORDER BY ID DESC;
