CREATE DATABASE vereniging2;
USE vereniging2;

CREATE TABLE postcode(
postcode CHAR(6) NOT NULL,
adres VARCHAR(128) NOT NULL,
woonplaats VARCHAR(128) NOT NULL,
PRIMARY KEY (postcode)
) ENGINE INNODB;

INSERT INTO postcode(postcode,adres,woonplaats) VALUES
('1212LE','Lerkeind','Doorn'),
('3434ZX','Wakkerdam','Doorn'),
('5656RE','Rezolade','Meidoorn');

CREATE TABLE lid (
lidnummer INT UNSIGNED NOT NULL AUTO_INCREMENT,
naam VARCHAR(128) NOT NULL,
voornaam VARCHAR(128) NOT NULL,
postcode CHAR(6) NOT NULL,
huisnummer VARCHAR(20) NOT NULL,
PRIMARY KEY (lidnummer),
FOREIGN KEY (postcode) REFERENCES postcode(postcode)
)ENGINE INNODB;

INSERT INTO lid(naam,voornaam,postcode,huisnummer) VALUES 
('Vollens','Theresa','1212LE','86'),
('Hamoen','Gert','3434ZX','52'),
('Klinkers','Sarah','5656RE','4');

CREATE TABLE email(
emailadres VARCHAR(128) NOT NULL,
lidnummer INT UNSIGNED NOT NULL,
PRIMARY KEY (emailadres),
FOREIGN KEY (lidnummer) REFERENCES lid(lidnummer)
) ENGINE INNODB;

INSERT INTO email(emailadres,lidnummer) VALUES 
('tvollens@gmail.com','1'),
('gerthamoen@hotmail.com','2'),
('sarklink@live.nl','3');

CREATE TABLE telefoonnummers(
telefoonnummer VARCHAR(10) NOT NULL,
lidnummer INT UNSIGNED NOT NULL,
PRIMARY KEY (telefoonnummer),
FOREIGN KEY (lidnummer) REFERENCES lid(lidnummer)
) ENGINE INNODB;

INSERT INTO telefoonnummers(telefoonnummer,lidnummer) VALUES
('0687456661','1'),
('0623574568','2'),
('0694026157','3');
