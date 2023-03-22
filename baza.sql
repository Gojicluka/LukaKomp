create database lukaKomp;

use lukaKomp;

create table proizvodjac(
    id int primary key AUTO_INCREMENT not null,
    ime varchar(50) not null,
    slika varchar(50)
);

create table graficke(
    id int primary key AUTO_INCREMENT not null,
    Ime varchar(100) not null,
    opis LONGTEXT not null,
    tipMemorije varchar(50) not null,
    vram varchar(50) not null,
    slika LONGTEXT,
    proizvodjac int not null,
    cena int not null,
    kolicina int not null,
    constraint proizvodjacfk foreign key(proizvodjac) references proizvodjac(id)
);

create table procesori(
    id int primary key AUTO_INCREMENT not null,
    Ime varchar(100) not null,
    opis LONGTEXT not null,
    brzina varchar(20) not null,
    overclock boolean not null,
    socket varchar(50) not null,
    broj_jezgara varchar(50) not null,
    slika LONGTEXT,
    proizvodjac int not null,
    cena int not null,
    kolicina int not null,
    constraint proizvodjacfkProc foreign key(proizvodjac) references proizvodjac(id)
);

create table kopmponente(
	id int primary key AUTO_INCREMENT,
    Ime varchar(100) not null,
   	opis longtext,
    slika LONGTEXT,
   	proizvodjac int,
    cena int not null,
    kolicina int not null,
    tip varchar(100) not null,
    filteri longtext not null,
    constraint proizvodjackfk foreign key(proizvodjac) references proizvodjac(id)
);


create table imageFolderPath(
    imagePath text not null
);
create table tabele(
    sveTabele longtext not null
);


/*Nije dodato*/
create table korisnik(
    id int primary key AUTO_INCREMENT not null,
    username varchar(50) not null,
    email varchar(50) not null,
    privilegija enum("admin","user") default "user",
    sifra varchar(200) not null
);

create table logintokens(
    id int primary key AUTO_INCREMENT not null,
    userid int not null,
    token varchar(200) not null,
    ipadress varchar(200) not null,
    DateCreated varchar(200) default CURRENT_TIMESTAMP
);


create table korpa(
	korisnik_id int,
    komponenta_id int,
    kolciina int not null default 1,
    primary key(korisnik_id,komponenta_id)
);



/*Ambiciozno mozda*/
create table racun(
    id int primary key AUTO_INCREMENT not null,
    dostavljeno tinyint default 0,
    korisnik_id int not null,
    datum datetime default CURRENT_TIMESTAMP
);

create table racun_medjuTabela(
    idracun int not null,
    kolicina int,
    cena int default 0,
    idDrugeTabele int not null,
    primary key(idracun,idDrugeTabele)
);
create table konfiguracije(
    ime varchar(300),
    idKomponente int,
    kolicina int,
    primary key(ime,idKomponente) 
);

/*mozda logintokens tabela ali to bas mozda*/


/* NIJE DEO FINALNE TABELE */


/*Imamo tip tabele koje stavimo ukoliko stavimo graficka onda selektujemo tabelu graficka
ukoliko stavimo procesor selektujemo procesor itd a komponenta id jeste id komponente koju smo uneli
NE MOZEMO da koristimo constraint foreign key za to zato sto jednstavno ne moze da se dogodi ne mozemo
da constraintujemo jedan column na vise tabela
*/

create table komponente(
    id int primary key AUTO_INCREMENT not null,
    komponenta_id int not null,
    komponenta_tip enum('graficka','procesor') not null,
    proizvodjac int not null,
    cena int not null,
    kolicina int not null,
    constraint proizvodjacfk foreign key(proizvodjac) references proizvodjac(id)
);

/*
LISTA PROCEDURA

DELIMITER // CREATE PROCEDURE loginProc(in input varchar(100)) BEGIN select * from korisnik where lower(input)=lower(username) OR lower(input)=lower(email); END // DELIMITER ;
DELIMITER // CREATE PROCEDURE registerUserProc( IN usernameIn varchar(100), IN emailIn varchar(100), in passwordIn varchar(100) ) BEGIN INSERT INTO korisnik(username,email,sifra) VALUES (usernameIn,emailIn,passwordIn); END // DELIMITER ;

DELIMITER // 
CREATE PROCEDURE dodajGraficku(
    in imeIn varchar(100), in opisIn varchar(100),
    in tipMemorijeIn varchar(100), in vramIn varchar(100),
    in slikaIn varchar(100),in proizvodjacIn varchar(100),
    in cenaIn int,in kolicinaIn int
) 
BEGIN 
INSERT INTO graficke( Ime, opis,tipMemorije, vram, slika, proizvodjac, cena,kolicina) VALUES (imeIn,opisIn,tipMemorijeIn,vramIn,slikaIn,proizvodjacIn,cenaIn,kolicinaIn);
END // 
DELIMITER ;

*/