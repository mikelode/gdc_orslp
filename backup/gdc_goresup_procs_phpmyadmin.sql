DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `fnTramDateTimeDFromParent`(idParent INT) RETURNS datetime
BEGIN

  DECLARE timeDerived DATETIME;

  SELECT h.thisDateTimeD into timeDerived FROM tramHistorial h WHERE h.thisId = idParent;

  IF timeDerived IS NULL then
    SET timeDerived = NULL;
  END if;

  RETURN timeDerived;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `fnDescDependencia`(depId VARCHAR(12)) RETURNS varchar(1000) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN

  DECLARE des VARCHAR(1000);
  SELECT depDsc into des FROM tramdependencia tgd WHERE tgd.depId = depId;
  
  IF des IS NULL then
  	SET des = 'No identidicado';
  END if;

  RETURN des;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `fnTramDateDiff`(startDate DATETIME, endDate DATETIME) RETURNS int(11)
BEGIN

  declare dias int;

  set @E = endDate;
  set @S = startDate;


 set dias = 5 * (DATEDIFF(@E, @S) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(@S) + WEEKDAY(@E) + 1, 1);

  return dias;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `fnTramGetDateDif`(fecha1 DATETIME, fecha2 DATETIME, tipo VARCHAR(10)) RETURNS varchar(500) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN

  DECLARE temp VARCHAR(100);
  DECLARE horas INT;
  DECLARE minutos INT;
  DECLARE tempMINUTOS INT;
  DECLARE segundos BIGINT;
  DECLARE cod varchar(5);

  SET temp ='...';
  set segundos = timestampdiff(SECOND,fecha1,fecha2);

  IF segundos < 3600 THEN

    SET minutos =  FLOOR(segundos / 60);
    SET segundos = segundos - minutos * 60;
    
    IF tipo = 1 then
        SET temp = concat('0 Horas ', cast(minutos as char(2)), ' Minutos ', cast(segundos as char(2)), ' Segundos');
    ELSE            
        SET temp = concat('00:', cast(minutos as char(2)), ':',  cast(segundos as char(2)));
    end if;
  ELSE
    SET horas = FLOOR(segundos / 3600);
    SET tempMINUTOS = segundos % 3600;
    SET minutos = FLOOR(tempMINUTOS / 60);
    SET segundos = tempMINUTOS % 60;

    IF tipo = 1 then
      SET temp = concat(cast(horas as char(2)), ' Horas ', cast(minutos as char(2)), ' Minutos ', cast(segundos as char(2)), ' Segundos');
    ELSE            
      SET temp = concat(cast(horas as char(2)), ':', cast(minutos as char(2)), ':', cast(segundos as char(2)));
    END IF;
  END IF;
  RETURN temp;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `fnTramGetDestinatario`(cadena VARCHAR(12)) RETURNS varchar(1000) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN

  DECLARE destinatario VARCHAR(250);
  DECLARE iguion INT;
  DECLARE iddestinatario INT;
  DECLARE idtabla VARCHAR(10);
  DECLARE tabla VARCHAR(20);

  SET iguion = locate('-',cadena);

  IF (iguion = 0) then
  	RETURN '';
  END if;
    

  SET iddestinatario = CAST(SUBSTRING(cadena,1,iguion-1) AS unsigned);
  SET idtabla = SUBSTRING(cadena,iguion+1,LENgth(cadena)-iguion);

  IF (idtabla = '1') then
  	/* para tramPersona */
    SELECT p.tprFulName into destinatario FROM tramPersona p WHERE p.tprId = iddestinatario;
  ELSE 
    /* cuando tabla es 2: tramDependencia */
    SELECT d.depDsc into destinatario FROM tramDependencia d WHERE d.depId = iddestinatario;
  END if;

  RETURN destinatario;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `fnTramDscDFromParent`(idParent INT) RETURNS varchar(1000) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN

  DECLARE dscDerived VARCHAR(1000);

  SELECT h.thisDscD into dscDerived FROM tramHistorial h WHERE h.thisId = idParent;

  IF dscDerived IS NULL then
    SET dscDerived = NULL;
  END if;

  RETURN dscDerived;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `fnTramGetDscFromId`(tabla VARCHAR(50), idTabla VARCHAR(20)) RETURNS varchar(100) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN

  DECLARE dsc VARCHAR(100);

	IF (tabla = 'tramDependencia') then
  	SELECT tgd.depDsc into dsc FROM tramdependencia tgd WHERE tgd.depID = idTabla;
  END if;

	IF (tabla = 'tramTipoDocumento') then
    SELECT td.ttypDesc into dsc FROM tramTipoDocumento td WHERE td.ttypDoc = idTabla;
  END if;
    
  IF dsc IS NULL then
  	SET dsc = 'No Encontrado';
  END if;
    
  RETURN dsc;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `fnTramGetRegistroRef`(idHist INT) RETURNS varchar(20) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN

  DECLARE docId INT;
  DECLARE docReg VARCHAR(20);

  SELECT h.thisDoc into docId FROM tramHistorial h WHERE h.thisId = idhist;

  SELECT d.tdocRegistro into docReg FROM tramDocumento d WHERE d.tdocId = docId;

  RETURN docReg;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `fnTramGetTimeAtention`(doc INT, hist INT, docr INT) RETURNS varchar(50) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN

  DECLARE ref VARCHAR(10);
  DECLARE tiat VARCHAR(50);
  DECLARE startDate DATETIME;
  DECLARE endDate DATETIME;

  SELECT d.tdocRef into ref FROM tramDocumento d WHERE d.tdocId = doc;

  IF (ref IS NULL) then
    SELECT h.thisDateTimeR into startDate FROM tramHistorial h WHERE h.thisId = hist;
    SELECT h.thisDateTimeD into endDate FROM tramHistorial h WHERE h.thisId = hist;
  	SET tiat = fnTramGetDateDif(startDate, endDate, 2);
    RETURN tiat;
  END if;

  SELECT h.thisDateTimeD into startDate FROM tramDocumento d INNER JOIN tramHistorial h ON d.tdocId = h.thisDoc WHERE d.tdocId = docr;
  SELECT h.thisDateTimeR into endDate FROM tramHistorial h WHERE h.thisId = hist;

  SET tiat = fnTramGetDateDif(startDate, endDate, 2);
  RETURN tiat;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `generar_codigo`(IN prefijo VARCHAR(3), OUT Codigo_generado VARCHAR(10))
BEGIN

  DECLARE cod_act VARCHAR(10);
  DECLARE anio_hoy INT;
  DECLARE anio_ayer INT;
  DECLARE contador INT;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
      SHOW ERRORS LIMIT 1;
      ROLLBACK; 
    END;
  
  DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
      SHOW ERRORS LIMIT 1;
      ROLLBACK; 
    END;

  IF (prefijo = 'DOC') THEN      
      set cod_act = (SELECT MAX(last_doc) FROM cod_cont);
  ELSEIF (prefijo = 'EXP') THEN
     	set cod_act = (SELECT MAX(last_exp) FROM cod_cont);
  END IF;   

  set anio_hoy  = YEAR(now())-2000;
  set anio_ayer = LEFT(RIGHT(cod_act,7),2);
  set contador = RIGHT(cod_act,5);
  
  IF (anio_hoy - anio_ayer) > 0 then
      SET contador = 1;
  ELSE
      SET contador = contador + 1;
  END IF;
  
  SET Codigo_generado = concat(prefijo, CAST(anio_hoy AS CHAR(2)), RIGHT(concat('00000', LTRIM(RTRIM(contador))),5));

  START TRANSACTION;

  IF (prefijo = 'DOC') then
      set @query = concat('UPDATE cod_cont SET last_doc = "',Codigo_generado,'" WHERE id = 1');
      prepare query from @query;
      execute query;
  ELSEIF (prefijo = 'EXP') then
     	set @query = concat('UPDATE cod_cont SET last_exp = "',Codigo_generado,'" WHERE id = 1');
      prepare query from @query;
      execute query;
  END IF;

  IF @@error_count > 0 THEN
    ROLLBACK;
  ELSE
    SELECT prefijo AS pref, codigo_generado AS codigo;
  END IF;
  
  COMMIT;
END$$
DELIMITER ;