drop table if exists Valutazione;
drop table if exists Credenziale;
drop table if exists Utente;
drop table if exists CorsodiStudio;
drop table if exists ClassediLaurea;
drop table if exists Ateneo;
create table Ateneo (
nome varchar(50) primary key,
link varchar(30) not null,
citta varchar(50) not null,
regione varchar(16) not null
);

create table ClassediLaurea (
num_classe char(4) primary key,
denominazione varchar(30) not null,
illustrazione text,
area_disciplinare varchar(30) not null,
gruppo_disciplinare varchar(30) not null,
durata ENUM('triennale', 'magistrale', 'a ciclo unico') not null
);

create table CorsodiStudio (
ateneo varchar(50),
classe_laurea char(4),
nome varchar(50) not null,
PRIMARY KEY(ateneo, classe_laurea, nome),
accesso ENUM('libero', 'chiuso', 'programmato') not null,
link varchar(30) not null,
foreign key (ateneo) references Ateneo(nome),
foreign key (classe_laurea) references ClassediLaurea(num_classe)
);

create table Utente (
nome_utente varchar(20) primary key,
nome varchar(20) not null,
cognome varchar(30) not null,
data_nascita date not null,
genere ENUM('M','F') not null,
scuola_sup ENUM('tecnico industriale','tecnico commerciale','scientifico','linguistico','classico') not null
);

create table Valutazione (
nome_utente varchar(20),
classe_laurea char(4),
PRIMARY KEY(nome_utente,classe_laurea),
data date not null,
commento varchar(200) not null,
tag ENUM('1','2') not null,
p_complessivo int not null,
p_acc_fisica int not null,
p_servizio_inclusione int not null,
tempestivita_burocratica int not null,
p_insegnamento int not null,
foreign key (nome_utente) references Utente(nome_utente) ON UPDATE CASCADE,
foreign key (classe_laurea) references ClassediLaurea(num_classe) ON UPDATE CASCADE ON DELETE CASCADE
);

create table Credenziale(
pw varchar(10),
data_inserimento date, 
utente varchar(20),
PRIMARY KEY(pw,data_inserimento,utente),
FOREIGN KEY(utente) REFERENCES Utente(nome_utente) ON UPDATE CASCADE ON DELETE CASCADE
);
