/*
    Modifiche effettuate
    Aggiunto campo marca e descrizione in PRODOTTI
    Create tabelle PREFERITI e CARRELLI
    Aggiunti attributi ridondanti numero preferiti e numero carello in CLIENTI con relativi trigger
    Aggiunto attributo ridondante disponibili in PRODOTTI con relativi trigger (INSERT, UPDATE, DELETE) in FORNITURE e COMPOSIZIONI
    Aggiunto attributo ridondante Valutazioni in PRODOTTI con relativi trigger (INSERT, DELETE) on RECENSIONI
    Aggiunta procedura per creare un ordine, a partire dal carrello, di un dato utente in ingresso
    Modifiche da effettuare
    Aggiunto trigger che non mi fa inserire un ordine se un prodotto non è disponibile (aggiornata business rule)
    Aggiunto IdCliente in recensione
    Ristrutturata la specializzazione di ordine, modificata di conseguenza query 1
    Aggiunto campo password in Impiegato
*/
CREATE DATABASE pirriShop;
USE pirriShop;

CREATE TABLE IMPIEGATI (
	Id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
    CF VARCHAR(16) UNIQUE,
    Nome VARCHAR(255), 
    Cognome VARCHAR(255),
    DataDiNascita DATE,
    Indirizzo VARCHAR(255), 
    Telefono VARCHAR(255),
    Password VARCHAR(255)  /*Aggiunto questo campo*/
);

CREATE TABLE MAGAZZINI (
	Id TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    Indirizzo VARCHAR(255), 
    Città VARCHAR(255), 
    UNIQUE (Indirizzo, Città)
);

CREATE TABLE FORNITORI(
	PIVA BIGINT UNSIGNED PRIMARY KEY,
    Nome VARCHAR(255), 
    Indirizzo VARCHAR(255), 
    Email VARCHAR(255), 
    Telefono VARCHAR(255)
);

CREATE TABLE CATEGORIE(
	Codice TINYINT UNSIGNED PRIMARY KEY,
    Nome VARCHAR(255), 
    Descrizione VARCHAR(255)    
);

CREATE TABLE PRODOTTI(
	EAN BIGINT UNSIGNED PRIMARY KEY,
    Marca VARCHAR(255), /*Ho aggiunto questo campo*/
    Nome VARCHAR(255), 
    Descrizione VARCHAR(255), /*Ho aggiunto questo campo*/
    Prezzo DECIMAL (8,2),
    Peso FLOAT,
    Costo DECIMAL (8,2),
    Disponibili INTEGER DEFAULT 0, /*Ho aggiunto questo campo*/
    Valutazioni INTEGER DEFAULT 0, /*Ho aggiunto questo campo*/
    Valutazione FLOAT DEFAULT NULL,
    CodiceCategoria TINYINT UNSIGNED,
    FOREIGN KEY (CodiceCategoria) REFERENCES CATEGORIE(Codice),
    INDEX idx_categoria (CodiceCategoria)
);

CREATE TABLE FORNITURE (
	Id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    PIVAFornitore BIGINT UNSIGNED,
    EANProdotto BIGINT UNSIGNED,
    IdMagazzino TINYINT UNSIGNED,
    Data date,
    Quantità INTEGER,
    UNIQUE(PIVAFornitore, EANProdotto, IdMagazzino, Data),
    INDEX idx_fornitore (PIVAFornitore),
    INDEX idx_prodotto (EANProdotto),
    INDEX idx_magazzino(IdMagazzino),
    FOREIGN KEY(PIVAFornitore) REFERENCES FORNITORI(PIVA),
    FOREIGN KEY(EANProdotto) REFERENCES PRODOTTI(EAN),
    FOREIGN KEY(IdMagazzino) REFERENCES MAGAZZINI(Id)
);

CREATE TABLE DIRIGENZE(
	IdImpiegato INTEGER UNSIGNED PRIMARY KEY, 
	IdMagazzino TINYINT UNSIGNED,
    INDEX idx_Magazzino(IdMagazzino),
    INDEX idx_impiegato(IdImpiegato),
    FOREIGN KEY(IdImpiegato) REFERENCES IMPIEGATI(Id),
    FOREIGN KEY(IdMagazzino) REFERENCES MAGAZZINI(Id)
);

CREATE TABLE IMPIEGHI(
	Id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    IdImpiegato INTEGER UNSIGNED,
    IdMagazzino TINYINT UNSIGNED,
    DataInizio DATE,
    DataFine DATE,
    Salario DECIMAL(8,2),
    Tipo VARCHAR(255),
    UNIQUE(IdImpiegato, IdMagazzino, DataInizio),
    INDEX idx_magazzino2 (IdMagazzino),
    INDEX idx_impiegato2 (IdImpiegato),
    FOREIGN KEY(IdImpiegato) REFERENCES IMPIEGATI(Id),
    FOREIGN KEY(IdMagazzino) REFERENCES MAGAZZINI(Id)
);

CREATE TABLE CLIENTI (
	Id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    Email VARCHAR(255),
    Password VARCHAR(255),
    Nome VARCHAR(255),
    Cognome VARCHAR(255),
    Indirizzo VARCHAR(255),
    Telefono VARCHAR(255),
    NPreferiti INTEGER DEFAULT 0, /*AGGIUNTI QUESTI 2 CAMPI*/
    NCarrello INTEGER DEFAULT 0,
    UNIQUE (Email)
);

CREATE TABLE RECENSIONI( 
	Id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    CodiceRecensione MEDIUMINT UNSIGNED,
    Stelle TINYINT CHECK(Stelle<=5 && Stelle>=1),
    Titolo VARCHAR(255), 
    Descrizione VARCHAR(255),
    EANProdotto BIGINT UNSIGNED, 
    IdCliente INTEGER UNSIGNED,  /*Aggiunto questo campo*/
    FOREIGN KEY (EANProdotto) REFERENCES PRODOTTI(EAN),
    UNIQUE(CodiceRecensione, EANProdotto),
    UNIQUE(IdCliente, EANProdotto),
    FOREIGN KEY (IdCliente) REFERENCES CLIENTI(Id),
    INDEX idx_EANProdotto (EANProdotto),
    INDEX idx_IdClienteR (IdCliente)
);

/*Aggiunta questa tabella*/
CREATE TABLE PREFERITI (
    Id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    IdCliente INTEGER UNSIGNED,
    EANProdotto BIGINT UNSIGNED,
    INDEX idx1_Cliente (IdCliente),
    FOREIGN KEY (IdCliente) REFERENCES CLIENTI(Id),
    INDEX idx1_EAN (EANProdotto),
    FOREIGN KEY (EANProdotto) REFERENCES PRODOTTI(EAN),
    UNIQUE(IdCliente, EANProdotto)
);

/*Aggiunta questa tabella*/
CREATE TABLE CARRELLI (
    Id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    IdCliente INTEGER UNSIGNED,
    EANProdotto BIGINT UNSIGNED,
    Quantità INTEGER,
    INDEX idx1_Cliente (IdCliente),
    FOREIGN KEY (IdCliente) REFERENCES CLIENTI(Id),
    INDEX idx1_EAN (EANProdotto),
    FOREIGN KEY (EANProdotto) REFERENCES PRODOTTI(EAN),
    UNIQUE(IdCliente, EANProdotto)
);

CREATE TABLE ORDINI (
	Id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    Data date,
    IdCliente INTEGER UNSIGNED,
    Stato VARCHAR(255), /*Aggiunto questo attributo*/
    INDEX idx_Cliente (IdCliente),
    FOREIGN KEY (IdCliente) REFERENCES CLIENTI(Id)
);

CREATE TABLE PAGAMENTI(
	Id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    Modalità VARCHAR(255),
    DataPagamento DATE,
    IdOrdine INTEGER UNSIGNED,
    INDEX idx_Ordine (IdOrdine),
    FOREIGN KEY (IdOrdine) REFERENCES ORDINI(Id)
);

CREATE TABLE SPEDIZIONI(
	Id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    Tracking VARCHAR(255),
    NomeCorriere VARCHAR(255),
    Prezzo DECIMAL(8,2),
    Modalità VARCHAR (255),
    IdOrdine INTEGER UNSIGNED,
    UNIQUE(Tracking),
    INDEX idx_Ordine2 (IdOrdine),
    FOREIGN KEY (IdOrdine) REFERENCES ORDINI(Id)
);
/* ELIMINATE QUESTE TABELLE
CREATE TABLE CONFERMATI(
	IdOrdine INTEGER UNSIGNED PRIMARY KEY,
    INDEX idx_Ordine3 (IdOrdine),
    FOREIGN KEY (IdOrdine) REFERENCES ORDINI(Id)
);

CREATE TABLE PAGATI(
	IdOrdine INTEGER UNSIGNED PRIMARY KEY,
    INDEX idx_Ordine4 (IdOrdine),
    FOREIGN KEY (IdOrdine) REFERENCES ORDINI(Id)
);

CREATE TABLE SPEDITI(
	IdOrdine INTEGER UNSIGNED PRIMARY KEY,
    INDEX idx_Ordine5 (IdOrdine),
    FOREIGN KEY (IdOrdine) REFERENCES ORDINI(Id)
);

CREATE TABLE ANNULLATI(
	IdOrdine INTEGER UNSIGNED PRIMARY KEY,
    INDEX idx_Ordine6 (IdOrdine),
    FOREIGN KEY (IdOrdine) REFERENCES ORDINI(Id)
);

*/

CREATE TABLE COMPOSIZIONI(
	IdOrdine INTEGER UNSIGNED,
    EANProdotto BIGINT UNSIGNED,
    Quantità INTEGER,
    PRIMARY KEY (IdOrdine, EANProdotto),
    INDEX idx_Ordine7 (IdOrdine),
    INDEX idx_EANProdotto2 (EANProdotto),
    FOREIGN KEY (IdOrdine) REFERENCES ORDINI(Id),
    FOREIGN KEY (EANProdotto) REFERENCES PRODOTTI (EAN)
);

/* CREAZIONE TRIGGER PER MANTENERE ALLINEATO L'ATTRIBUTO RIDONDANTE VALUTAZIONE 
CREO UNA PROCEDURA CHE RICHIAMO NEI 3 TRIGGER (INSERT, UPDATE, DELETE)*/
DELIMITER //
CREATE PROCEDURE ProceduraTrigger(IN EAN BIGINT UNSIGNED)
BEGIN
	UPDATE PRODOTTI SET Valutazione = (
		SELECT avg(Stelle) 
        FROM RECENSIONI
		WHERE EANProdotto = EAN
        GROUP BY EANProdotto
    )
    WHERE PRODOTTI.EAN = EAN;
END//
DELIMITER ;
DELIMITER //
CREATE TRIGGER TriggerValutazioneInsert
AFTER INSERT ON RECENSIONI
FOR EACH ROW
BEGIN
IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=NEW.EANProdotto) THEN
	CALL ProceduraTrigger(NEW.EANProdotto);
END IF;
END //
DELIMITER ;
DELIMITER //
CREATE TRIGGER TriggerValutazioneUpdate
AFTER UPDATE ON RECENSIONI
FOR EACH ROW
BEGIN
IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=NEW.EANProdotto) THEN
	CALL ProceduraTrigger(NEW.EANProdotto);
END IF;
END //
DELIMITER ;
DELIMITER //
CREATE TRIGGER TriggerValutazioneDelete
AFTER DELETE ON RECENSIONI
FOR EACH ROW
BEGIN
	IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=OLD.EANProdotto) THEN
		CALL ProceduraTrigger(OLD.EANProdotto);
	END IF;
END //
DELIMITER ;
/* CREAZIONE TRIGGER PER MANTENERE ALLINEATO L'ATTRIBUTO RIDONDANTE NPreferiti INSERT, DELETE)*/
DELIMITER //
CREATE TRIGGER TriggerPreferitiInsert
AFTER INSERT ON PREFERITI
FOR EACH ROW
BEGIN

IF EXISTS (SELECT Id FROM CLIENTI WHERE Id=NEW.IdCliente) THEN
	UPDATE CLIENTI SET NPreferiti = NPreferiti+1 WHERE Id=NEW.IdCliente;
END IF;
END //
DELIMITER ;
DELIMITER //
CREATE TRIGGER TriggerPreferitiDelete
BEFORE DELETE ON PREFERITI
FOR EACH ROW
BEGIN

IF EXISTS (SELECT Id FROM CLIENTI WHERE Id=OLD.IdCliente) THEN
	UPDATE CLIENTI SET NPreferiti = NPreferiti-1 WHERE Id=OLD.IdCliente;
END IF;
END //
DELIMITER ;

/* CREAZIONE TRIGGER PER MANTENERE ALLINEATO L'ATTRIBUTO RIDONDANTE NCarrello INSERT, DELETE, UPDATE*/
DELIMITER //
CREATE TRIGGER TriggerCarrelloInsert
AFTER INSERT ON CARRELLI
FOR EACH ROW
BEGIN

IF EXISTS (SELECT Id FROM CLIENTI WHERE Id=NEW.IdCliente) THEN
	UPDATE CLIENTI SET NCarrello = NCarrello+NEW.Quantità WHERE Id=NEW.IdCliente;
END IF;
END //
DELIMITER ;
DELIMITER //
CREATE TRIGGER TriggerCarrelloDelete
BEFORE DELETE ON CARRELLI
FOR EACH ROW
BEGIN

IF EXISTS (SELECT Id FROM CLIENTI WHERE Id=OLD.IdCliente) THEN
	UPDATE CLIENTI SET NCarrello = NCarrello-OLD.Quantità WHERE Id=OLD.IdCliente;
END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER TriggerCarrelloUpdate
BEFORE UPDATE ON CARRELLI
FOR EACH ROW
BEGIN

IF EXISTS (SELECT Id FROM CLIENTI WHERE Id=OLD.IdCliente) THEN
	UPDATE CLIENTI SET NCarrello = NCarrello-OLD.Quantità+NEW.Quantità WHERE Id=OLD.IdCliente;
END IF;
END //
DELIMITER ;

/* CREAZIONE TRIGGER PER MANTENERE ALLINEATO L'ATTRIBUTO RIDONDANTE Disponibili INSERT, DELETE, UPDATE in FORNITURE e in COMPOSIZIONI*/
DELIMITER //
CREATE TRIGGER TriggerDisponibiliInsertForniture
AFTER INSERT ON FORNITURE
FOR EACH ROW
BEGIN

IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=NEW.EANProdotto) THEN
	UPDATE PRODOTTI SET Disponibili = Disponibili+NEW.Quantità  WHERE EAN=NEW.EANProdotto;
END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER TriggerDisponibiliDeleteForniture
AFTER DELETE ON FORNITURE
FOR EACH ROW
BEGIN

IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=OLD.EANProdotto) THEN
	UPDATE PRODOTTI SET Disponibili = Disponibili-OLD.Quantità  WHERE EAN=OLD.EANProdotto;
END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER TriggerDisponibiliUpdateForniture
AFTER UPDATE ON FORNITURE
FOR EACH ROW
BEGIN

IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=OLD.EANProdotto) THEN
	UPDATE PRODOTTI SET Disponibili = Disponibili-OLD.Quantità+ NEW.Quantità WHERE EAN=OLD.EANProdotto;
END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER TriggerDisponibiliInsertComposizioni
AFTER INSERT ON COMPOSIZIONI
FOR EACH ROW
BEGIN

IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=NEW.EANProdotto) THEN
	UPDATE PRODOTTI SET Disponibili = Disponibili-NEW.Quantità  WHERE EAN=NEW.EANProdotto;
END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER TriggerDisponibiliDeleteComposizioni
AFTER DELETE ON COMPOSIZIONI
FOR EACH ROW
BEGIN

IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=OLD.EANProdotto) THEN
	UPDATE PRODOTTI SET Disponibili = Disponibili+OLD.Quantità  WHERE EAN=OLD.EANProdotto;
END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER TriggerDisponibiliUpdateComposizioni
AFTER UPDATE ON COMPOSIZIONI
FOR EACH ROW
BEGIN

IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=OLD.EANProdotto) THEN
	UPDATE PRODOTTI SET Disponibili = Disponibili+OLD.Quantità-NEW.Quantità  WHERE EAN=OLD.EANProdotto;
END IF;
END //
DELIMITER ;

/* CREAZIONE TRIGGER PER MANTENERE ALLINEATO L'ATTRIBUTO RIDONDANTE NPreferiti INSERT, DELETE)*/
DELIMITER //
CREATE TRIGGER TriggerValutazioniInsert
AFTER INSERT ON RECENSIONI
FOR EACH ROW
BEGIN

IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=NEW.EANProdotto) THEN
	UPDATE PRODOTTI SET Valutazioni = Valutazioni+1 WHERE EAN=NEW.EANProdotto;
END IF;
END //
DELIMITER ;
DELIMITER //
CREATE TRIGGER TriggerValutazioniDelete
BEFORE DELETE ON RECENSIONI
FOR EACH ROW
BEGIN

IF EXISTS (SELECT EAN FROM PRODOTTI WHERE EAN=OLD.EANProdotto) THEN
	UPDATE PRODOTTI SET Valutazioni = Valutazioni-1 WHERE EAN=OLD.EANProdotto;
END IF;
END //
DELIMITER ;

/* VISTA*/
CREATE VIEW VistaComposizioneProdotti AS
SELECT * , (Prezzo-Costo)*Quantità AS Ricavo
FROM PRODOTTI P JOIN COMPOSIZIONI C ON C.EANProdotto=P.EAN;

/*BUSINESS RULE Ogni ordine non può contenre oggetti per più di 100kg*/
/*VECHHIA
DELIMITER //
CREATE TRIGGER BusinessRule
AFTER INSERT ON COMPOSIZIONI
FOR EACH ROW		
BEGIN
	IF EXISTS (SELECT * FROM ORDINI WHERE NEW.IdOrdine=Id) THEN
		IF ((SELECT SUM(Peso*Quantità) FROM VistaComposizioneProdotti WHERE IdOrdine = NEW.IdOrdine)>100) THEN
			SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "L'ordine non può contenre oggetti per più di 100kg";
		END IF;
	END IF;
END //
DELIMITER ;
*/


DELIMITER //
CREATE TRIGGER BusinessRule
AFTER INSERT ON COMPOSIZIONI
FOR EACH ROW		
BEGIN
	IF EXISTS (SELECT * FROM ORDINI WHERE NEW.IdOrdine=Id) THEN
		IF ((SELECT SUM(Peso*Quantità) FROM VistaComposizioneProdotti WHERE IdOrdine = NEW.IdOrdine)>100) THEN
			BEGIN
			SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "L'ordine non può contenre oggetti per più di 100kg";
            END;
            ELSE
            BEGIN
				IF((SELECT T.Disponibili 
					FROM (SELECT Disponibili+NEW.Quantità AS Disponibili, @messaggio := CONCAT(Marca, " ", Nome, " non disponibile. Disponibili ", Disponibili+NEW.Quantità) 
						  FROM PRODOTTI 
						  WHERE EAN=NEW.EANProdotto) AS T
					) <  NEW.Quantità) 
				THEN
					SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = @messaggio;
                END IF;
            END;
		END IF;
	END IF;
END //
DELIMITER ;


INSERT INTO CATEGORIE VALUES
	(1, "Abbigliamento", "Migliaia di capi dei migliori marchi" ),
    (2, "Elettronica", "Ampia gamma di articoli che comprende Fotocamere e Videocamere, Televisioni, Telefonia, cuffie e auricolari, navigatori satellitari, tecnologia indossabile, strumenti musicali, prodotti ricondizionati e molto altro ancora"),
    (3, "Pulizia e cura della casa", "Tutto quello che ti serve per prenderti cura della tua casa"),
    (4, "Alimentari", "Puoi trovare i prodotti a più lunga conservazione"),
    (5, "Libri", "Libri per bambini, libri scolastici e universitari, romanzi, biografie e molto altro"),
    (6, "Auto e Moto", "Accessori e parti per auto e moto, attrezzatura ed elettronica per i tuoi veicoli"), 
    (7, "Sport e tempo libero", "Camping e Outdoor, Fitness, Ciclismo, Golf, Corsa, oltre ai Dispositivi elettronici e all'abbigliamento sportivo delle migliori marche"),
    (8, "Bellezza e salute", "Cosmetici e prodotti per la cura della persona"),
    (9, "Cancelleria e prodotti per l'ufficio", "Quaderni, risme di carta, penne, arredamento per l'ufficio e molto altro"),
    (10, "Giochi e giocattoli", "Puoi scegliere tra un'ampia selezione di giochi per bambini e adulti e troverai tutte le più grandi marche"),
    (11, "Musica", "Tutte le più grandi hit di sempre");
    
INSERT INTO PRODOTTI (EAN, Marca, Nome, Descrizione, Prezzo, Peso, Costo, CodiceCategoria) VALUES 
	(2412345678901, "MA-FRA","Pulitore cerchi e gomme", "ll Pulitore Cerchi e Gomme è un detergente con alto potere sgrassante appositamente studiato per garantire i massimi risultati su qualunque modello di cerchione: dai cerchi in lega di magnesio a quelli in acciaio, fino ai copricerchi in plastica.", 12.5,3.2, 10, 6),
    (2412345678903, "Levi's", "Felpa", "Felpa con cappuccio. 100% Cotone Chiusura: Senza chiusura. Lavare in lavatrice. Manica lunga.", 50,7,35, 1),
    (4618274091822, "DECATHLON", "Manubri 2kg", "Apprezzerai: - L'impugnatura perfetta - La forma studiata appositamente per non rotolare. Impazzirai per: l'aspetto polivalente che permette di intensificare squat, affondi, bicipiti curl!", 24.32,2.1,10,7),
    (2125262674533, "Lego", "Fiat 500", "Celebra un'icona del design italiano. Ricrea l'atmosfera della 'dolce vita' con la nuova Fiat 500 Lego", 231.53,9.2,189.2,10),
    (1521523213157, "Dove", "Shampoo Idratazione Quotidiana", "Lo shampoo Dove Idratazione Quotidiana contribuisce, insieme al balsamo della stessa linea, a fornirgli l’idratazione di cui hai bisogno. Il nostro sistema unico rafforza e idrata i capelli, per capelli fino a 10 volte più resistenti.",1.2,0.72,0.3, 8),
    (3463724719821, "Quasar", "Vetri formula originale", "Quasar Vetri rimuove efficacemente lo sporco facendo brillare e scintillare vetri, cristalli e specchi senza lasciare tracce e aloni." ,3.2, 1.2, 2.5, 3),
    (6790869532349, "Sony", "Alpha 7RIV", "Mirrorless full-frame, offre un’esperienza fotografica eccezionale, racchiudendo in un corpo compatto l’abilità espressiva tipica delle fotocamere di medio formato e un’eccezionale velocità.", 3499, 4.2, 2099, 2),
    (6851982355113, "Pavesi", "Gocciole", "Ingredienti: Farina di frumento, Zucchero, Cioccolato 14,6% (zucchero, pasta di cacao, burro di cacao, cacao magro, emulsionante: lecitina di soia), Olio di girasole, Burro, Sciroppo di glucosio, Amido di frumento, Agenti lievitanti, Sale, Vanillina.",3.29, 0.7, 1.30, 4),
    (1246574213862, "QUECHUA", "Tenda campeggio 2 persone", "Una tenda accessibile che supera tutti i nostri test di resistenza e impermeabilità. La struttura a igloo autoportante consente di spostarla, anche montata, per scegliere la posizione migliore.", 78.92, 4.2, 45.3, 7),
    (5418541254121, "Fabriano", "A4 500 Fogli", "Carta naturale. Elevato punto di bianco. Prodotta con il 100% di pura cellulosa E.C.F. (Elemental Chlorine Free) certificata FSC®. Ideale per fotocopie, stampa laser, inkjet e fax.",3.59, 3.2, 2.73, 9),
    (2481982541931, "Paolo Giordano", "La solitudine dei numeri primi", "", 13.5, 0.56, 5.23, 5),
    (1242352318219, "Colapesce, Dimartino", "Musica leggerissima", "", 1.19, 0, 0.20, 11);

INSERT INTO CLIENTI (Email, Password, Nome, Cognome, Indirizzo, Telefono) VALUES 
	("mattia.pirri@gmail.com", "password segreta", "Mattia", "Pirri", "Via Londa 111, Acireale (CT)", "2839419420"),
    ("alessio.pirri_99@gmail.com", "password segreta alessio", "Alessio", "Pirri", "Via Londa 111, Acireale (CT)", "1234625245"),
    ("giuseppe.musso@aruba.it", "La_MiAPAssW0rD", "Giuseppe", "Musso", "Via Galatea 37, Milano (MI)", "3468367302"),
    ("info@andremarino.it", "AnDReaMarino_", "Andrea", "Marino", "Via Regina Margherita 3, Firenze (FI)", "3458206345"),
    ("salvo.lombardo@live.it", "SalvuccioL","Salvatore", "Lombardo", "Via Lombardia 15, Messina (ME)", "3428152740"),
    ("musso.giuseppe@gmail.com", "GiuSeppe12*_", "Giuseppe", "Musso", "Via Riccardo Wagner 34, Venezia (VE)", "3457128631"),
    ("carla.piano31@hotmail.it", "CaRLa__31!pIAno", "Carla", "Piano", "Viale  Umberto 835, Torino (TO)", "3472435193"),
    ("nataleasia@gmail.com", "AsiiaNatale25", "Asia", "Natale", "Piazza Dante 4, Napoli (NA)", "3447825374"),
    ("ratti.amedeo@hotmail.it", "RaTT14m3de0*", "Amedeo", "Ratti", "Via Nazionale 12, Bari (BA)", "3417253963"),
    ("deborah_visconti@gmail.com", "VisC0nt1DeBoraH", "Deborah", "Visconti", "Corso Italia 23, Bologna (BO)", "3456132435"),
    ("novello.gabriel@tiscali.it", "PaSSwordG4bR13L!","Gabriel", "Novello", "Via Napoleone 124, Modena (MO)", "3489142532"),
    ("mattia.pirri@gmail.coma", "$2y$10$6G/tW7LpHcB6OYvshc2/lejtdFd366F/c0b0vPEgm69lZhSSIYwwq", "Mattia", "Pirri", "Via Londa 111", "1234567890");
    
INSERT INTO ORDINI(Data, IdCliente, Stato) VALUES 
	("2020-12-22", 1, "Confermato"),
    ("2020-12-10", 1, "Confermato"),
    ("2019-12-22", 2, "Annullato"),
    ("2019-8-3", 1, "Annullato"),
    ("2019-5-2", 3, "Spedito"),
    ("2020-3-20", 9, "Pagato"),
    ("2019-7-7", 6, "Annullato"),
    ("2019-7-8", 10, "Spedito"),
    ("2020-12-10", 5, "Confermato"),
    ("2020-11-5", 8, "Confermato"),
    ("2019-10-4", 4, "Pagato"),
    ("2017-4-19", 7, "Spedito"),
    ("2019-10-4",1, "Spedito"),
    ("2020-5-10", 9, "Pagato"),
    ("2019-6-12", 6, "Annullato"),
    ("2019-9-18", 10, "Spedito"),
    ("2020-1-12", 5, "Pagato"),
    ("2020-12-15", 8, "Confermato"),
    ("2019-11-13", 4, "Pagato"),
    ("2017-5-28", 7, "Spedito"),
    ("2019-11-14",1, "Annullato");
/*
INSERT INTO ANNULLATI VALUES 
	(3),
    (4),
    (7),
    (15),
    (21);
INSERT INTO CONFERMATI VALUES 
	(1),
    (2),
    (9),
    (10),
    (18);
INSERT INTO PAGATI VALUES
	(14),
    (6),
    (17),
    (19),
    (11);
INSERT INTO SPEDITI VALUES 
	(5),
    (8),
    (12),
    (13),
    (16),
    (20);
*/
INSERT INTO SPEDIZIONI (Tracking, NomeCorriere, Prezzo, Modalità, IdOrdine) VALUES 
	("GLS43698", "GLS", 7.9, "ESPRESSO", 5),
    ("SDA913461", "SDA", 5.5, "ESPRESSO", 8),
    ("P42141212", "Poste Italiane", 2.99, "STANDARD", 12),
    ("BRT23412312", "Bartolini", 4.7, "STANDARD", 13),
    ("BRT54761291", "Bartolini", 5.10, "STANDARD", 16),
    ("UPS231411233", "UPS", 10.2 ,"ESPRESSO", 20);
INSERT INTO PAGAMENTI (Modalità, DataPagamento, IdOrdine)VALUES
	("PayPal", "2020-5-10",14),
    ("Contrassegno", "2020-03-24", 6),
    ("CC", "2020-01-13", 17),
    ("PayPal","2019-11-13",19),
    ("PayPal", "2019-10-04", 11),
    ("PayPal", "2019-05-02", 5),
    ("PayPal", "2019-07-08", 8),
    ("PayPal", "2017-04-19", 12),
    ("CC", "2019-10-06", 13),
    ("PayPal", "2019-09-18", 16),
    ("PayPal", "2017-05-28", 20);

INSERT INTO RECENSIONI (CodiceRecensione,Stelle,Titolo,Descrizione, EANProdotto, IdCliente) VALUES 
	(1,5, "Ottimo pulitore", "Sgrassa in modo ottimale",2412345678901, 1),
    (2,3, "Si può fare di meglio", "Lascia qualche macchia",2412345678901, 2),
    (3,5, "Non userò piu la concorrenza", "Ottimo articolo, niente da aggiungere",2412345678901,3),
    (1,5, "Perfetta", "Vestibilità nella norma", 2412345678903, 4),
    (1,5, "Ottima tenda", "Tenda per perfetta per 2 persone, facile da montare. Consigliata", 1246574213862, 5),
    (2,4, "Buona tenda", "Due persone ci stanno comode. Qualche spiffero di troppo!", 1246574213862, 2),
    (1,4, "Ottimo prodotto", "4 stelle per il prezzo un pò altino", 2125262674533, 4),
    (2,5, "Bellissimo gioco", "Ho passato dei bei momenti con mio figlio", 2125262674533, 6),
    (1,3, "Credo non siano 2kg", "Ho la ragione di credere che uno dei due pesi meno di 2kg", 4618274091822, 7),
    (2,5, "Ottimo per rimanere in forma", "Nulla da aggiungere", 4618274091822, 8),
    (1,5, "Ottimo shampoo", "Perfetto", 1521523213157, 10),
    (1,4, "Buon prodotto", "Lascia qualche alone", 3463724719821, 9),
    (1,5, "La mia preferita", "Ottima mirrorless fullframe", 6790869532349, 7),
    (1,5, "Buonissimi", "I migliori biscotti sul mercato", 6851982355113, 11),
    (1,4, "Buona carta", "Una delle migliori marche di carta sul mercato", 5418541254121, 6),
    (1,4, "Libro bello", "Libro un pò triste", 2481982541931, 3);

INSERT INTO MAGAZZINI(Indirizzo, Città) VALUES
	("Via Torrente 4", "Messina"),
    ("Via Etnea 9", "Catania"),
    ("Via Pesce 10", "Cagliari"),
    ("Via Delfino 1", "Campobasso"),
    ("Corso Savoia 1", "Acireale"),
    ("Via Dante Alighieri 963", "Milano"),
    ("Via Mela 612", "Torino"),
    ("Via Cioccolato 8", "Torino"),
    ("Via Trionfo 94", "Bari"),
    ("Corso Umberto 84", "Acireale");
    
INSERT INTO IMPIEGATI (CF, Nome, Cognome, DataDiNascita, Indirizzo, Telefono, Password) VALUES 
	("PRRMTT99S02C351E", "Mattia", "Pirri", "1999-11-02", "Via Londa 111, Acireale (CT)", "3488825240", MD5("Password1")),
    ("RSSMRR88A03D123A", "Mario", "Rossi", "1988-01-03", "Via Roma 33, Roma (RM)", "3451721412", MD5("Password2")),
    ("RSSGSP89A19D178D", "Giuseppe", "Rossi", "1988-01-19", "Via Strada 99, Milano (MI)", "3454252123", MD5("Password3")),
    ("BTTMRZ94B28H101J", "Maurizio", "Battiato", "1994-02-28", "Via Stradone 1, Catania (CT)", "3488823123", MD5("Password4")),
    ("BTTCRM94D30H101J", "Carmine", "Battiato", "1994-04-30", "Via Collina 411, Messina (ME)", "3488882523", MD5("Password5"));
INSERT INTO IMPIEGHI (IdImpiegato, IdMagazzino, DataInizio, DataFine, Salario, Tipo) VALUES
	(1,2,"2017-01-01", "2018-12-01", null, "PASSATO"),
    (1,3,"2018-01-01", "2019-12-01", null, "PASSATO"),
    (1,4,"2020-01-01", null, 1300.45, "CORRENTE"),
	(2,10,"2006-04-30", "2008-04-30", null, "PASSATO"),
    (2,7,"2009-04-30", "2010-04-30", null, "PASSATO"),
    (2,4,"2012-04-30",null, 800.21, "CORRENTE"),
    (3,2,"2000-05-17", null, 1700.47, "CORRENTE"),
    (4,2,"2010-04-21", null, 1807.4, "CORRENTE"),
    (5,4,"2012-01-19", "2012-04-19", null, "PASSATO");
    
INSERT INTO DIRIGENZE VALUES	
	(1,4),
    (4,2);
    
INSERT INTO FORNITORI VALUES
	(2473949356, "Pavesi", "Via Mantova 166, Parma", "info@barilla.it", "3459326342"),
    (4387261129, "Forniture S.r.l", "Via Galileo 34, Roma", "contattaci@forniture.it", "3425613123"),
    (1391263721, "EuroForniture", "Via Garibaldi 826, Milano", "mail@euroforniture.it", "3429172362"),
    (9266719382, "Giochi più", "Corso Savoia 36, Cagliari", "giochipiu@aruba.it", "3456234173"),
    (2342365123, "P&G", "Via Roma 456, Bologna", "info@pg.it", "3449251732");
    
INSERT INTO FORNITURE (PIVAFornitore, EANProdotto, IdMagazzino, Data, Quantità)VALUES
	(2473949356, 6851982355113, 3, "2019-01-01", 150),
    (9266719382, 2125262674533, 2, "2017-05-02", 30),
    (2342365123, 2412345678901, 1, "2017-04-21", 40),
    (2342365123, 1521523213157, 1, "2017-04-21", 51),
    (2342365123, 3463724719821, 1, "2017-04-21", 60),
    (4387261129, 2412345678903, 5, "2015-2-1", 37),
	(4387261129, 4618274091822, 5, "2015-2-1", 20),
    (4387261129, 1246574213862, 5, "2015-2-1", 67),
    (1391263721, 6790869532349, 6, "2020-10-2", 5),
    (1391263721, 5418541254121, 6, "2020-10-2", 50),
    (1391263721, 2481982541931, 6, "2020-10-2", 10);

INSERT INTO COMPOSIZIONI VALUES
	(1,2412345678901,3),
    (1,2412345678903,5),
    (1,2125262674533,6),
    (2,2412345678901,3),
    (3,2412345678903,5),
    (3,4618274091822,10),
    (3,1521523213157,7),
    (4,2125262674533,7),
    (5,2481982541931,1),
    (5,6851982355113,3),
    (7,6790869532349,1),
    (7,5418541254121,2),
    (6,2125262674533,1),
    (8,6790869532349,1),
    (9,4618274091822,2),
    (10,3463724719821,2),
    (11,1246574213862,1),
    (12,2481982541931,1),
    (13,5418541254121,2),
    (14,6790869532349,1),
    (15,1521523213157,5),
    (16,3463724719821,1),
    (17,6790869532349,1),
    (18,4618274091822,4),
    (19,1246574213862,1),
    (20,2412345678903,1),
    (21,3463724719821,2);


/* QUERY 1 Calcolare il ricavo perso a causa di tutti gli ordini annullati*/
/*
DELIMITER //
CREATE PROCEDURE P1 (OUT Risultato DECIMAL (8,2))
BEGIN
SELECT SUM(Ricavo) INTO Risultato
FROM ANNULLATI A JOIN VistaComposizioneProdotti C ON A.IdOrdine=C.IdOrdine;
END //
DELIMITER ;

CALL P1(@Ris);
SELECT @Ris;
*/


/*QUERY 1 Modificata dopo restrutturazione*/
DELIMITER //
CREATE PROCEDURE P1 (OUT Risultato DECIMAL (8,2))
BEGIN
SELECT SUM(Ricavo) INTO Risultato
FROM ORDINI O JOIN VistaComposizioneProdotti C ON O.Id=C.IdOrdine
WHERE Stato="Annullato";
END //
DELIMITER ;

CALL P1(@Ris);
SELECT @Ris;



/*QUERY 2  Mostrare i magazzini per cui lavorano o hanno lavorato almeno 3 impiegati con sede in una città che inizia per una data stringa*/
DELIMITER //
CREATE PROCEDURE P2(IN Stringa VARCHAR(255))
BEGIN
DROP TEMPORARY TABLE IF EXISTS temp;
CREATE TEMPORARY TABLE temp (
	Id TINYINT UNSIGNED,
    Indirizzo VARCHAR(255), 
    Città VARCHAR(255),
    Impiegati INTEGER
);
INSERT INTO temp 
SELECT M.*, COUNT(*) as Impiegati
FROM MAGAZZINI M JOIN IMPIEGHI I ON I.IdMagazzino=M.Id
WHERE Città LIKE CONCAT(Stringa, "%")
GROUP BY M.Id, M.Indirizzo, M.città
HAVING Impiegati >=3;

SELECT * FROM temp;
END //
DELIMITER ;

CALL P2("Ca");

/*QUERY 3 Inserire un dipendente e assegnarlo alla sede di Catania 
se è nato dopo il 1990 altrimenti assegnarlo alla sede con più dipendenti*/
DELIMITER //
CREATE PROCEDURE P3 (IN codicefiscale VARCHAR(16), IN nome VARCHAR(255), IN cognome VARCHAR(255), IN data_di_nascita date,
	IN indirizzo VARCHAR(255), IN telefono VARCHAR(255), IN salario DECIMAL (8,2))
BEGIN
INSERT INTO IMPIEGATI (CF, Nome, Cognome, DataDiNascita, Indirizzo, Telefono ) VALUES (codicefiscale, nome, cognome, data_di_nascita, indirizzo, telefono);

INSERT INTO IMPIEGHI (IdImpiegato, IdMagazzino, DataInizio, Salario, Tipo) 
	VALUE (
			(SELECT Id FROM IMPIEGATI WHERE CF=codicefiscale),
            (CASE 
				WHEN YEAR(data_di_nascita) <1990 THEN 
					(SELECT Id FROM MAGAZZINI WHERE Città = "Catania" LIMIT 1)
                ELSE (	SELECT IdMagazzino 
						FROM IMPIEGHI I
						WHERE Tipo = "CORRENTE" 
						GROUP BY (IdMagazzino) 
						HAVING COUNT(IdMagazzino) >= ALL(SELECT COUNT(IdMagazzino) 
														 FROM IMPIEGHI I
														 WHERE Tipo = "CORRENTE" 
														 GROUP BY (IdMagazzino)) 
						LIMIT 1)   
			END), 
            CURRENT_DATE,
			salario,
            "CORRENTE");
END//
DELIMITER ;

CALL P3("PRRMTT89S02C351G","Mattia", "Pirri","1991-11-02", "Via Indirizzo numero", "3825403794", 8000);

/*QUERY 4 Mostrare tutti i prodotti contenuti in ordini effettuati nel 2019 da omonimi*/
DELIMITER //
CREATE PROCEDURE P4 ()
BEGIN
DROP TEMPORARY TABLE IF EXISTS temp2;
CREATE TEMPORARY TABLE temp2(
	EAN BIGINT UNSIGNED,
    Marca VARCHAR(255), 
    Nome VARCHAR(255), 
    Prezzo DECIMAL (8,2),
    Peso FLOAT,
    Costo DECIMAL (8,2),
    Valutazione FLOAT DEFAULT NULL
);
INSERT INTO temp2 
	SELECT EAN, Marca, Nome, Prezzo, Peso, Costo, Valutazione
	FROM VistaComposizioneProdotti  V JOIN ORDINI O ON V.IdOrdine=O.Id
	WHERE YEAR(Data)=2019 AND IdCliente IN (
											SELECT Id
											FROM CLIENTI C1 
											WHERE EXISTS(SELECT * 
														 FROM CLIENTI C2 
														 WHERE C1.Nome = C2.Nome AND
															C1.Cognome = C2.Cognome AND
															C1.Id<>C2.Id)
						);
SELECT * FROM temp2;
END//
DELIMITER ;

CALL P4;


/*QUERY SCRITTE DURANTE L'ORALE DI DICEMBRE

--Prodotto meno gradito e quello meno fortunato
USE ECOMMERCE;
SELECT EANProdotto, COUNT(*) as n_annullato
FROM ANNULLATI A JOIN COMPOSIZIONI C ON A.IdOrdine=C.IdOrdine
GROUP BY EANProdotto
HAVING n_annullato >= ALL (SELECT COUNT(*) as n_annullato
						FROM ANNULLATI A JOIN COMPOSIZIONI C ON A.IdOrdine=C.IdOrdine
						GROUP BY EANProdotto);
                        
                        SELECT * FROM ANNULLATI A JOIN COMPOSIZIONI C ON A.IdOrdine=C.IdOrdine;
                        
                        
--IdCliente, Num_confermati, Num_Spediti,Num_annullati
CREATE TABLE Statistiche (
	IdCliente INT PRIMARY KEY,
    num_confermati INTEGER,
    num_spediti INTEGER,
    num_annullati INTEGER
);
INSERT INTO Statistiche 
SELECT count(*)
FROM  ORDINI O LEFT JOIN CONFERMATI C ON O.IdOrdine = C.IdOrdine
GROUP BY IdCliente

*/

/*PRODCEURA PER CREARE UN ORDINE A PARTIRE DA UN CARRELLO, L'HANDLER CATTURA I MESSAGGI DI CARRELLO VUOTO E QUELLO LANCIATO DALLA BUSINESS RULE*/
DELIMITER //
CREATE PROCEDURE carrelloToOrdine(IN IdClienteIn INTEGER UNSIGNED)
BEGIN
	DECLARE exit handler for sqlexception
	BEGIN
		ROLLBACK;
		RESIGNAL;
	END;
	START TRANSACTION;
	IF EXISTS(SELECT IdCliente FROM CARRELLI WHERE IdCliente=IdClienteIn) THEN
    BEGIN
		INSERT INTO ORDINI (Data, IdCliente, Stato)VALUES(CURRENT_DATE(),IdClienteIn, "Confermato");
        SET @last_id = LAST_INSERT_ID();
        /*
        INSERT INTO CONFERMATI VALUE (@last_id);
        */
		INSERT INTO COMPOSIZIONI(IdOrdine, EANProdotto, Quantità) 
				SELECT @last_id,EANProdotto, Quantità
				FROM CARRELLI
				WHERE IdCliente=IdClienteIn;
		DELETE FROM CARRELLI WHERE IdCliente=IdClienteIN;
		COMMIT;
	END;
    ELSE
    BEGIN
		SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Il carrello è vuoto";
    END;
    END IF;
END //
DELIMITER ;

