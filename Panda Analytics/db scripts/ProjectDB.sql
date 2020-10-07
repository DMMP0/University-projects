
-- SET DI ROBA VARIA
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- CREAZIONE DB
CREATE DATABASE IF NOT EXISTS `FLOW_DB` DEFAULT CHARACTER SET utf8;
USE `FLOW_DB`;




-- TABELLA UTENTE
CREATE TABLE IF NOT EXISTS `UTENTE`(
	`ID` INT AUTO_INCREMENT NOT NULL, -- PRIMARY KEY
	`NOME` VARCHAR(45) NOT NULL UNIQUE,
    `SALT` VARCHAR(20) NOT NULL,
    `PASSWORD` VARCHAR(200) NOT NULL,
	`MAIL` VARCHAR(40) NOT NULL,
	`access_level` varchar(16) NOT NULL,
	`status` int(11) NOT NULL COMMENT '0=pending,1=confirmed',
	
	PRIMARY KEY(`ID`),
	UNIQUE INDEX `ID_UNIQUE`(`ID`),
	key(`NOME`)	
	);
	
	

	
	
-- TABELLA CASH FLOW AZIENDA
CREATE TABLE IF NOT EXISTS `FLOW`(
	`ID` INT AUTO_INCREMENT NOT NULL,
	`NOME` VARCHAR(45) NOT NULL,
	`DMI` INT NOT NULL COMMENT 'Disponibilità monetaria iniziale',
	`status` int(11) NOT NULL COMMENT '0=first year,1=not first year',
	`fondazione` DATE NOT NULL COMMENT 'Data fondazione',
	
	`ID_UTENTE` INT NOT NULL,
	PRIMARY KEY (`ID`),
	UNIQUE INDEX `ID_UNIQUE`(`ID` ASC),
	CONSTRAINT `fk_UTENTE`
		FOREIGN KEY (`ID_UTENTE`)
		REFERENCES `FLOW_DB`.`UTENTE`(`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
	); 

	

-- codice: proprietario, flow, anno, codice in ordine

	
-- TABELLA TRANSAZIONI
CREATE TABLE IF NOT EXISTS `TRANSAZIONI`(
	`ID` INT AUTO_INCREMENT NOT NULL,
	`TIPO` VARCHAR(3) NOT NULL default "000",
	`AMOUNT` DECIMAL(10,2) NOT NULL,
	`MACROAREA` BIT(1) NOT NULL COMMENT '0=FONTI,1=IMPIEGHI',
	`ANNO` YEAR NOT NULL,
	`RIPETIZIONI_ANNUALI`INT(3) NOT NULL DEFAULT 0,
	`ID_FLOW` INT NOT NULL,
	PRIMARY KEY (`ID`),
	UNIQUE INDEX `ID_UNIQUE`(`ID` ASC),
	CONSTRAINT `fk_FLOW`
		FOREIGN KEY (`ID_FLOW`)
		REFERENCES `FLOW_DB`.`FLOW` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
	
	);
	
	
-- TABELLA TRANSAZIONE PERIODO PRECISO
CREATE TABLE IF NOT EXISTS `TPRECISA`(
	`T_DATE` DATE NOT NULL,
	`ID_TRANSAZIONE` INT NOT NULL,
	PRIMARY KEY(`T_DATE`,`ID_TRANSAZIONE`),
	CONSTRAINT `fk_TPRECISA`
		FOREIGN KEY (`ID_TRANSAZIONE`)
		REFERENCES `FLOW_DB`.`TRANSAZIONI`(`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);




-- TABELLA TRANSAZIONE PERIODO BOH
CREATE TABLE IF NOT EXISTS `TBOH`(
	`T_MESE` INT(2) NOT NULL,
	`ID_TRANSAZIONE` INT NOT NULL,
	PRIMARY KEY(`T_MESE`,`ID_TRANSAZIONE`),
	CONSTRAINT `fk_TBOH`
		FOREIGN KEY (`ID_TRANSAZIONE`)
		REFERENCES `FLOW_DB`.`TRANSAZIONI` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);



-- PROCEDURES


	
	-- STORED PROCEDURE PER TUTTE LE TRANSAZIONI CON DATA PRECISA DELL' ANNO anno DEL FLOW id DELL'UTENTE id
	DELIMITER //
	CREATE PROCEDURE IF NOT EXISTS GetPreciseCurrentYearTransaction(IN anno YEAR, IN UserId INT, IN FlowId INT)
	BEGIN
		SELECT T.ID,T.MACROAREA,T.TIPO,T.AMOUNT,T.ANNO,TP.T_DATE
		FROM ((UTENTE AS U INNER JOIN FLOW AS F ON U.ID=F.ID_UTENTE)INNER JOIN TRANSAZIONI AS T ON T.ID_FLOW = F.ID)INNER JOIN TPRECISA AS TP ON TP.ID_TRANSAZIONE=T.ID
		WHERE U.ID = UserId AND F.ID = FlowId AND T.ANNO = anno;
	END //
	DELIMITER ;
	
	-- STORED PROCEDURE PER TUTTE LE TRANSAZIONI CON DATA IMPRECISA DELL' ANNO anno DEL FLOW id DELL'UTENTE id
	DELIMITER //
	CREATE PROCEDURE IF NOT EXISTS GetPreciseCurrentYearTransaction(IN anno YEAR, IN UserId INT, IN FlowId INT)
	BEGIN
		SELECT T.ID,T.MACROAREA,T.TIPO,T.AMOUNT,T.ANNO,TP.T_MESE
		FROM ((UTENTE AS U INNER JOIN FLOW AS F ON U.ID=F.ID_UTENTE)INNER JOIN TRANSAZIONI AS T ON T.ID_FLOW = F.ID)INNER JOIN TBOH AS TP ON TP.ID_TRANSAZIONE=T.ID
		WHERE U.ID = UserId AND F.ID = FlowId AND T.ANNO = anno;
	END //
	DELIMITER ;
	
	
	
	-- STORED PROCEDURE PER L'UPDATE DEL VALORE
	DELIMITER //
	CREATE PROCEDURE IF NOT EXISTS UPDATE_TVALUE(IN val DECIMAL(10,2), IN UserId INT, IN FlowId INT,IN TR_ID int)
	BEGIN
		DECLARE _rollback BOOL DEFAULT 0;
		DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET _rollback=1;
		START TRANSACTION;
		SAVEPOINT before_update;
		UPDATE TRANSAZIONI as T JOIN FLOW AS F ON T.ID_FLOW=F.ID JOIN UTENTE AS U ON F.ID_UTENTE=U.ID
			SET T.AMOUNT=val
			WHERE U.ID = UserId AND F.ID = FlowId AND T.ID=TR_ID;
		IF _rollback THEN
			ROLLBACK TO before_update;		
		ELSE
			COMMIT;
		END IF;
	END //
	DELIMITER ;
	
	
-- non vanno
-- TRIGGERS
	
	
	
	
	-- TRIGGER PER SOMMARE le fonti
--	CREATE TRIGGER IF NOT EXISTS SUM_FONTI 
--		BEFORE INSERT ON TRANSAZIONI 
--		FOR EACH ROW WHERE MACROAREA=0 
--		SET @SF=@SF+NEW.AMOUNT;
	
	
	-- TRIGGER PER SOMMARE gli impieghi
--	CREATE TRIGGER IF NOT EXISTS SUM_FONTI 
--		BEFORE INSERT ON TRANSAZIONI 
--		FOR EACH ROW WHERE MACROAREA=1 
--		SET @SI=@SI+NEW.AMOUNT;
	
	
	
--	.-----------------.
--	|  PRECISAZIONI   |
--  '-----------------'
--	
--	Nella tabella TRANSAZIONI
--		MACROAREA = 0  -->  Fonti
--		MACROAREA = 1  -->  IMPIEGHI
--
--		TIPO viene memorizzato come codice con struttura xyz
--		CODICI:
--
--
--																	   .- z=1 -> depositi bancari e postali
--																	   | 
--										.- y=1 -> disponibilità liquide'- z=2 -> denaro e valori in cassa
--										|
--										|									
--										|								   .- z=1 -> crediti verso clienti
--										|								   |
--			x=1  -> è un attivo corrente:- y=2 -> disponibilità finanziarie'- z=2 -> ratei e risconti
--										|
--										|				   .- z=1 -> materie prime,sussidiarie e di consumo
--										|				   |
--										'- y=3 -> rimanenze:- z=2 -> prodotti in corso di lavorazione e semilavorate
--														   |
--														   '- z=3 -> prodotti fini e merci
--                                       							    
--                                      	 .- y=1 -> immobilitazioni materiali - z=1 -> diritti di brevetto industriale e diritti di utilizzatore
--                                      	 |
--                                     		 |									
--                                      	 |								   .- z=1 -> terreni e fabbricati
--                                   		 |								   |
--     		x=2  -> è un attivo immobilizzato:- y=2 -> disponibilità finanziarie:- z=2 -> attrezzature industriali e commerciali
--											 |								   |
--											 |								   '- z=3 -> altri beni
--                                      	 |
--                                      	 '- y=3 -> immobilitazioni finanziarie - z=1 -> crediti verso i clienti
--
--  																  	.- z=1 -> fondi rischi e oneri
--																	  	| 
--																		:- z=2 -> debiti per tfr
--																		|
--																		:- z=3 -> obbligazioni
--																	    |
--			x=3  -> capitale di debito:- y=1 -> debiti a breve scadenza :- z=4 -> debiti verso banche
--									  |						 			|
--									  |							  		.- z=5 -> debiti verso fornitori
--									  |							  		| 
--									  |									:- z=6 -> debiti tributari
--									  |									|
--									  |									:- z=7 -> debiti verso istituti di previdenza
--									  |							    	|
--									  |									'- z=8 -> ratei e risconti
--									  |				   
--									  |	
--									  '										 .- z=1 -> debiti per tfr
--									  |										 |
--									  '- y=2 -> debiti a media/lunga scadenza:- z=2 -> obbligazioni
--									  										 |
--																			 '- z=3 -> debiti verso banche
--									  
--									.- y=1 -> capitale sociale - 0
--									|
--									|									
--									|				   .- z=1 -> Riserva legale
--									|				   |
--			x=4  -> capitale proprio:- y=2 -> Riserve -:- z=2 -> Riserva straordinaria
--													   |
--													   '- z=3 -> utile(perdita) dell' esercizio - dividendi