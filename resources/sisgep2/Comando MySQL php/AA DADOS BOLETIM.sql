SET @intIDBoletim = (SELECT ID FROM chefia_boletim WHERE BOLETIMDODIA = TRUE);

SET @blnDiurno = (SELECT TUR.PERIODODIURNO FROM chefia_boletim CBOL
INNER JOIN tab_turnos TUR ON TUR.ID = CBOL.IDTURNO WHERE CBOL.ID = @intIDBoletim);

SET @intIDTurno = (SELECT CBOL.IDTURNO FROM chefia_boletim CBOL WHERE ID = @intIDBoletim);

SET @intIDDiretor = (SELECT IDDIRETOR FROM chefia_boletim CBOL WHERE CBOL.ID = @intIDBoletim);

SET @intIDTurnoSeguinte = (SELECT TU.IDTURNOSEGUINTE FROM chefia_boletim CBOL INNER JOIN tab_turnos TU ON TU.ID = CBOL.IDTURNO WHERE CBOL.ID = @intIDBoletim);

SET @horaPlantao = (SELECT HORAPLANTAOASP FROM tab_dadosunidade WHERE ID = 1);
SET @dataBoletimCurta = (SELECT DATABOLETIM FROM chefia_boletim WHERE ID = @intIDBoletim);
SET @dataBoletim = concat((SELECT DATABOLETIM FROM chefia_boletim WHERE ID = @intIDBoletim), ' ', @horaPlantao);

SET @dataInicio = CASE @blnDiurno WHEN 1 THEN @dataBoletim ELSE date_add(@dataBoletim, INTERVAL 12 HOUR) END;
SET @dataFim = CASE @blnDiurno WHEN 1 THEN date_add(@dataBoletim, INTERVAL 12 HOUR) ELSE date_add(@dataBoletim, INTERVAL 24 HOUR) END;

SET @nomeTurnoAtual = (SELECT NOME FROM tab_turnos WHERE ID = @intIDTurno);
SET @nomeTurnoSeguinte = (SELECT NOME FROM tab_turnos WHERE ID = @intIDTurnoSeguinte);