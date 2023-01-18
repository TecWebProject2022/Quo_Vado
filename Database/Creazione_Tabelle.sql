drop table if exists Valutazione;
drop table if exists Credenziale;
drop table if exists Iscrizione;
drop table if exists Utente;
drop table if exists CorsodiStudio;
drop table if exists ClassediLaurea;
drop table if exists Ateneo;
drop table if exists Domande;
create table Ateneo (
nome varchar(50) primary key,
link varchar(30) not null,
citta varchar(50) not null,
regione varchar(16) not null
);

create table ClassediLaurea (
num_classe char(4) primary key,
denominazione varchar(250) not null,
illustrazione text,
area_disciplinare varchar(40) not null,
gruppo_disciplinare varchar(50) not null,
durata ENUM('triennale', 'magistrale', 'a ciclo unico') not null
);

create table CorsodiStudio (
ateneo varchar(50),
classe_laurea char(4),
nome varchar(80),
PRIMARY KEY(ateneo, classe_laurea, nome),
accesso ENUM('Accesso programmato','Accesso libero con prova','Accesso a numero chiuso','Accesso libero cronologico') not null,
link varchar(2000) not null,
foreign key (ateneo) references Ateneo(nome),
foreign key (classe_laurea) references ClassediLaurea(num_classe)
);

create table Utente (
nome_utente varchar(40) primary key,
nome varchar(20) not null,
cognome varchar(30) not null,
data_nascita date not null,
genere ENUM('M','F','ND') not null,
scuola_sup ENUM('tecnico industriale','tecnico commerciale','scientifico','linguistico','classico') not null
);

create table Valutazione (
nome_utente varchar(40),
classe_laurea char(4),
datav date not null,
commento varchar(200) not null,
tag ENUM('1','2') not null,
PRIMARY KEY(nome_utente,classe_laurea,tag),
p_complessivo   int not null check(p_complessivo>0 and p_complessivo<6),
p_acc_fisica int  not null check(p_acc_fisica>0 and p_acc_fisica<6),
p_servizio_inclusione int  not null check(p_servizio_inclusione>0 and p_servizio_inclusione<6),
tempestivita_burocratica int  not null check(tempestivita_burocratica>0 and tempestivita_burocratica<6),
p_insegnamento  int not null check(p_insegnamento>0 and p_insegnamento<6),
foreign key (nome_utente) references Utente(nome_utente) ON UPDATE CASCADE,
foreign key (classe_laurea) references ClassediLaurea(num_classe) ON UPDATE CASCADE ON DELETE CASCADE
);

create table Credenziale(
pw varchar(20),
data_inserimento date, 
utente varchar(40),
attuale boolean default 0,
PRIMARY KEY(pw,utente),
FOREIGN KEY(utente) REFERENCES Utente(nome_utente) ON UPDATE CASCADE ON DELETE CASCADE
);
create table Iscrizione(
    ateneo varchar(50),
    classe char(4), 
    corso varchar(80), 
    nome_utente varchar(40),
    datai date not null,
    PRIMARY KEY(ateneo,classe,corso,nome_utente), 
    punteggio_scuola_provenienza int   not null check(punteggio_scuola_provenienza>0 and punteggio_scuola_provenienza<6), 
    dataf date not null ,
    FOREIGN KEY(ateneo,classe,corso) REFERENCES CorsodiStudio(ateneo, classe_laurea, nome) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(nome_utente) REFERENCES Utente(nome_utente) ON UPDATE CASCADE
);
create table Domande(
  email varchar(40), 
  data datetime,
  descrizione varchar(500),
  visto boolean default false,
  PRIMARY KEY(email,data,descrizione)
);
