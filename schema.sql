CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
	id_category int (10) AUTO_INCREMENT,
	name varchar(20),
	symbol_code varchar(3),
	PRIMARY KEY (id_category)
);

CREATE TABLE users (
	id_user int (10) AUTO_INCREMENT,
	registration_date date,
	name varchar(20),
	email varchar(70),
	password varchar(30),
	avatar varchar(200),
	contacts varchar(20),
	PRIMARY KEY (id_user)
);
CREATE INDEX reg_date ON users(registration_date);
CREATE UNIQUE INDEX email ON users(email);

CREATE TABLE items (
	id_item int (10) AUTO_INCREMENT,
	start_date date,
	description text,
	image VARCHAR(200),
	price double(15, 2),
	finish_date date,
	step double(15, 2),
	id_author int (10),
	id_winner int (10),
	id_category int (10),
	PRIMARY KEY (id_item),
	FOREIGN KEY (id_author) REFERENCES users (id_user),
	FOREIGN KEY (id_winner) REFERENCES users (id_user),
	FOREIGN KEY (id_category) REFERENCES categories (id_category)
);
CREATE INDEX start_date ON items(start_date);
CREATE INDEX finish_date ON items(finish_date);
CREATE INDEX id_author ON items(id_author);
CREATE INDEX id_winner ON items(id_winner);
CREATE INDEX id_category ON items(id_category);

CREATE TABLE bets (
	id_bet int (10) AUTO_INCREMENT,
	bet_date date,
	bet_sum double(15, 2),
	id_user int (10),
	id_item int (10),
	PRIMARY KEY (id_bet),
	FOREIGN KEY (id_user) REFERENCES users (id_user),
	FOREIGN KEY (id_item) REFERENCES items (id_item)
);
CREATE INDEX bet_date ON bets(bet_date);
CREATE INDEX id_user ON bets(id_user);
CREATE INDEX id_item ON bets(id_item);
CREATE INDEX symbol_code ON categories(symbol_code);



