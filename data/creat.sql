--DATABASE TABLES CREATE
--TEAM MAD POTATOES
--CZ

-- V 1.0
--2016/2/23

DROP TABLE IF EXISTS Reservation;
DROP TABLE IF EXISTS Authentication;
DROP TABLE IF EXISTS User;

CREATE TABLE User(
	U_id int NOT NULL AUTO_INCREMENT,
	FirstName varchar(30) NOT NULL,
	LastName varchar(30) NOT NULL,
	Email varchar(50) NOT NULL,
	DL_Number varchar(10),
	Phone_Num varchar(10),
	PRIMARY KEY(U_id)
);

CREATE TABLE Authentication(
	U_id int NOT NULL PRIMARY KEY,
	password_hash varchar(45) NOT NULL,
	salt varchar(45) NOT NULL,
	FOREIGN KEY (U_id) REFERENCES User(U_id)
);


DROP TABLE IF EXISTS Event;
DROP TABLE IF EXISTS PKLot;

CREATE TABLE Event(
	E_id int NOT NULL AUTO_INCREMENT,
	EventName varchar(45) NOT NULL,
	Date date,
	StartTime TIME,
	TeamA varchar(45) NOT NULL,
	TeamB varchar(45) NOT NULL,
	PRIMARY KEY(E_id)
);

CREATE TABLE PKLot(
	P_id int NOT NULL AUTO_INCREMENT,
	Name varchar(45) NOT NULL,
	TotalNum int,
	Occupied int,
	Price float,
	PRIMARY KEY(P_id)
);

CREATE TABLE Reservation(
	Res_id int NOT NULL AUTO_INCREMENT,
	Res_date DATE,
	ResNo varchar(30) NOT NULL,
	U_id int,
	P_id int,
	E_id int,
	PRIMARY KEY(Res_id),
	FOREIGN KEY(U_id) REFERENCES User(U_id),
	FOREIGN KEY(P_id) REFERENCES PKLot(P_id),
	FOREIGN KEY(E_id) REFERENCES Event(E_id)
);
	
