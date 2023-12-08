select * from chefia_boletim;
SET @dataalterar = '2023-04-21';

SELECT DATEDIFF(@dataalterar, '2023-01-01') + 1;
UPDATE chefia_boletim SET DATABOLETIM = @dataalterar, NUMERO = DATEDIFF(@dataalterar, '2023-01-01') + 1 WHERE BOLETIMDODIA = TRUE;	