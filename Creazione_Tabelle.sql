drop table if exists Ateneo;
drop table if exists Classe di Laurea;
drop table if exists Corso di Studio;
drop table if exists Valutazione;
drop table if exists Utente;

create table Ateneo (
nome varchar(50) primary key,
URL varchar(30) not null,
tipologia varchar(8) not null,
citta varchar(50) not null,
regione varchar(16) not null,
introduzione varchar(200),
costo_medio integer,
num_borsaStudio integer,
posizioneGraduatoria integer not null,
);

create table ClassediLaurea (
num_classe char(4) primary key,
denominazione varchar(30) not null,
illustrazione ???,
area_disciplinare varchar(30) not null,
gruppo_disciplinare varchar(30) not null,
durata_stimata integer
);

create table CorsodiStudio (
ateneo varchar(50),
classe_laurea char(4),
nome varchar(50) not null,
PRIMARY KEY(ateneo,classe_laurea,nome),
accesso tipo not null,
URL varchar(30) not null,
foreign key (ateneo) references Ateneo(nome),
foreign key (classe_laurea) references ClassediLaurea(num_classe)
);

create table Utente (
nome_utente varchar(20) primary key,
password varchar(20) not null,
nome varchar(20) not null,
cognome varchar(30) not null,
data_nascita date not null,
genere char(1) not null,
scuola_sup superiore not null
);

create table Valutazione (
nome_utente varchar(20),
classe_laurea char(4),
PRIMARY KEY(nome_utente,classe_laurea),
datav date not null,
commento varchar(200) not null,
p_complessivo varchar(2) not null,
p_acc_fisica varchar(2) not null,
p_servizio_inclusione varchar(2) not null,
tempestivita_burocratica varchar(2) not null,
p_insegnamento varchar(2) not null,
foreign key (nome_utente) references Utente(nome_utente) ON UPDATE CASCADE,
foreign key (codice_attivita) references ClassediLaurea(num_classe) ON UPDATE CASCADE ON DELETE CASCADE
);
create table Password(sigla varchar(10),
                      data date, 
                      utente varchar(20),
                      PRIMARY KEY(sigla,data,utente));