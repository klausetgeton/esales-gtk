PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE author (
       id serial primary key,
       name varchar(100));
INSERT INTO "author" VALUES(1,'Rohmer, Sax');
INSERT INTO "author" VALUES(2,'Whitman, Walt');
INSERT INTO "author" VALUES(3,'Austen, Jane');
INSERT INTO "author" VALUES(4,'Joyce, James');
INSERT INTO "author" VALUES(5,'Doyle, Arthur Conan');
INSERT INTO "author" VALUES(6,'Ambler, Scott');
INSERT INTO "author" VALUES(7,'Sadalage, Pramodkumar');
INSERT INTO "author" VALUES(8,'Hunt, Andrew');
INSERT INTO "author" VALUES(9,'Thomas, David');
INSERT INTO "author" VALUES(10,'Fowler, Martin');
INSERT INTO "author" VALUES(11,'Leffingwell, Dean');
INSERT INTO "author" VALUES(12,'Widrig, Don');
INSERT INTO "author" VALUES(13,'Cockburn, Alistair');
INSERT INTO "author" VALUES(14,'Kotler, Philip');
INSERT INTO "author" VALUES(15,'Clarke');
INSERT INTO "author" VALUES(16,'Fox, Karen');
CREATE TABLE subject (
       id serial primary key,
       name varchar(100));
INSERT INTO "subject" VALUES(1,'Education');
INSERT INTO "subject" VALUES(2,'Computing');
INSERT INTO "subject" VALUES(3,'Biology');
INSERT INTO "subject" VALUES(4,'Psico
y');
INSERT INTO "subject" VALUES(5,'Physics');
INSERT INTO "subject" VALUES(6,'History');
INSERT INTO "subject" VALUES(7,'Romance');
INSERT INTO "subject" VALUES(8,'Tales');
INSERT INTO "subject" VALUES(9,'Police');
INSERT INTO "subject" VALUES(10,'Programming');
INSERT INTO "subject" VALUES(11,'Databases');
INSERT INTO "subject" VALUES(12,'Modeling');
INSERT INTO "subject" VALUES(13,'Software requirements');
INSERT INTO "subject" VALUES(14,'Health');
INSERT INTO "subject" VALUES(15,'Marketing');
INSERT INTO "subject" VALUES(16,'Administration');
CREATE TABLE publisher (
       id serial primary key,
       name varchar(100));
INSERT INTO "publisher" VALUES(1,'Gutemberg project');
INSERT INTO "publisher" VALUES(2,'Addison-Wesley');
INSERT INTO "publisher" VALUES(3,'Prentice Hall');
CREATE TABLE collection (
       id serial primary key,
       description varchar(100));
INSERT INTO "collection" VALUES(1,'book');
INSERT INTO "collection" VALUES(2,'periodocal');
INSERT INTO "collection" VALUES(3,'dvd');
INSERT INTO "collection" VALUES(4,'cd');
CREATE TABLE classification (
       id serial primary key,
       description varchar(100));
INSERT INTO "classification" VALUES(1,'Music');
INSERT INTO "classification" VALUES(2,'Medicine');
INSERT INTO "classification" VALUES(3,'Science');
INSERT INTO "classification" VALUES(4,'History');
INSERT INTO "classification" VALUES(5,'Administration');
INSERT INTO "classification" VALUES(6,'Law');
INSERT INTO "classification" VALUES(7,'Health');
INSERT INTO "classification" VALUES(8,'Literature');
CREATE TABLE city (
       id serial primary key,
       name varchar(100));
INSERT INTO "city" VALUES(1,'São Paulo');
INSERT INTO "city" VALUES(2,'Rio de Janeiro');
INSERT INTO "city" VALUES(3,'Porto Alegre');
INSERT INTO "city" VALUES(4,'Belo Horizonte');
CREATE TABLE status (
       id serial primary key,
       description varchar(100));
INSERT INTO "status" VALUES(1,'Available');
INSERT INTO "status" VALUES(2,'Checked out');
INSERT INTO "status" VALUES(3,'Lost');
CREATE TABLE category (
       id serial primary key,
       description varchar(100));
INSERT INTO "category" VALUES(1,'student');
INSERT INTO "category" VALUES(2,'staff');
INSERT INTO "category" VALUES(3,'teacher');
CREATE TABLE role (
    id integer NOT NULL PRIMARY KEY,
    description text NOT NULL,
    mnemonic text
);
INSERT INTO "role" VALUES(1,'Administrator','ADMINISTRATOR');
INSERT INTO "role" VALUES(2,'Librarian','LIBRARIAN');
INSERT INTO "role" VALUES(3,'Operator','OPERATOR');
CREATE TABLE member (
       id serial primary key,
       name varchar(100),
       birthdate date,
       address varchar(100),
       city_id integer,
       phone_number varchar(100),
       email varchar(100),
       login varchar(100),
       password varchar(100),
       category_id integer,
       registration date,
       expiration date,
       FOREIGN KEY(category_id) REFERENCES category(id),
       FOREIGN KEY(city_id) REFERENCES city(id)
);
INSERT INTO "member" VALUES(1,'Ayrton Senna','21/03/1970','Rua Bento Gonçalves, 123',1,'9393-7474','ayrton.senna@gmail.com','ayrton','senna',1,'02/07/2012','31/07/2013');
INSERT INTO "member" VALUES(2,'Alain Prost','24/02/1955','Rua Avelino Tallini, 171',2,'9374-3739','alain.prost@gmail.com','alain','prost',2,'02/07/2012','02/07/2013');
INSERT INTO "member" VALUES(3,'Gerhard Berger','27/08/1959','Rua Benjamin Constant, 123',3,'9384-4729','gerhard.berger@gmail.com','gerhard','berger',3,'02/07/2012','02/07/2013');
INSERT INTO "member" VALUES(4,'Thierry Boutsen','13/07/1957','Rua Júlio de Castilhos, 848',4,'9383-4748','thierry.boutsen@gmail.com','thierry','boutsen',1,'02/07/2012','02/07/2013');
INSERT INTO "member" VALUES(5,'Michele Alboreto','23/12/1956','Rua Alberto Torres, 123',1,'8404-2827','michele.alboreto@gmail.com','michele','alboreto',1,'02/07/2012','02/07/2013');
INSERT INTO "member" VALUES(6,'Nelson Piquet','17/08/1952','Rua Bento Gonçalves, 837',1,'8364-3733','nelson.piquet@gmail.com','nelson','piquet',1,'02/07/2012','02/07/2013');
INSERT INTO "member" VALUES(7,'Ivan Capelli','24/05/1963','Rua Alberto Pasqualini, 939',2,'9473-0273','ivan.capelli@gmail.com','ivan','capelli',1,'02/07/2012','02/07/2013');
INSERT INTO "member" VALUES(8,'Derek Warwick','27/08/1954','Rua Júlio de Castilhos, 837',3,'7492-8302','derek.warwick@gmail.com','derek','warwick',1,'02/07/2012','02/07/2013');
INSERT INTO "member" VALUES(9,'Nigel Mansell','08/08/1953','Rua Benjamin Constant, 938',3,'7492-8278','nigel.mansell@gmail.com','nigel','mansell',1,'02/07/2012','02/07/2013');
INSERT INTO "member" VALUES(10,'Alessandro Nannini','07/07/1959','Rua Alberto Pasqualini, 837',3,'7492-4729','alessandro.nannini@gmail.com','alessandro','nannini',3,'02/07/2012','02/07/2013');
CREATE TABLE book (
       id serial primary key,
       title varchar(100),
       isbn varchar(100),
       call_number varchar(100),
       author_id integer,
       edition varchar(100),
       volume varchar(100),
       collection_id integer,
       classification_id integer,
       publisher_id integer,
       publish_place varchar(100),
       publish_date date,
       abstract text,
       notes text,
       FOREIGN KEY(collection_id) REFERENCES collection(id),
       FOREIGN KEY(author_id) REFERENCES author(id),
       FOREIGN KEY(classification_id) REFERENCES classification(id),
       FOREIGN KEY(publisher_id) REFERENCES publisher(id)
);
INSERT INTO "book" VALUES(1,'Tales of Secret Egypt ','849228485048','T833 R345',1,'1','1',1,8,1,'New York','02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(2,'Leaves of Grass','23424635645','L234 W724',2,'1','1',1,8,1,'New York','02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(3,'Pride and Prejudice','29293038483','P937 A942',3,'1','1',1,8,1,'New York','02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(4,'Ulysses','029298389395654','U938 J935',4,'1','1',1,8,1,'New York','02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(5,'The Adventures of Sherlock Holmes','0289302949423','T938 D729',5,'1','1',1,8,1,'New York','02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(6,'Refactoring Databases: Evolutionary Database Design','978-0321293534','R938 A838',6,'1','1',1,3,2,'New York','02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(7,'The Pragmatic Programmer: From Journeyman to Master','978-0201616224','P938 ',8,'1','1',1,3,2,'New York','02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(8,'UML Distilled: A Brief Guide to the Standard Object Modeling Language','978-0321193681','U938 F947',10,'1','1',1,3,2,NULL,'02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(9,'Managing Software Requirements: A Use Case Approach','978-0321122476','M873 L738',11,'1','1',1,3,2,NULL,'02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(10,'Writing Effective Use Cases','978-0201702255','W837 C836',13,'1','1',1,3,2,'New York','02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(11,'Marketing for Health Care Organizations','978-0135575628','M838 K893',14,'1','1',1,5,3,'New York','02/07/2012',NULL,NULL);
INSERT INTO "book" VALUES(12,'Strategic Marketing for Educational Institutions','978-0136689898','S938 K938',14,'1','1',1,5,3,'New York','02/07/2012',NULL,NULL);
CREATE TABLE book_author (
       id serial primary key,
       book_id integer,
       author_id integer,
       FOREIGN KEY(book_id) REFERENCES book(id),
       FOREIGN KEY(author_id) REFERENCES author(id)
);
INSERT INTO "book_author" VALUES(2,3,3);
INSERT INTO "book_author" VALUES(3,4,4);
INSERT INTO "book_author" VALUES(4,5,5);
INSERT INTO "book_author" VALUES(5,2,2);
INSERT INTO "book_author" VALUES(6,6,6);
INSERT INTO "book_author" VALUES(7,6,7);
INSERT INTO "book_author" VALUES(8,7,8);
INSERT INTO "book_author" VALUES(9,7,9);
INSERT INTO "book_author" VALUES(10,8,10);
INSERT INTO "book_author" VALUES(11,8,NULL);
INSERT INTO "book_author" VALUES(12,9,11);
INSERT INTO "book_author" VALUES(13,9,12);
INSERT INTO "book_author" VALUES(14,10,13);
INSERT INTO "book_author" VALUES(15,11,14);
INSERT INTO "book_author" VALUES(16,11,15);
INSERT INTO "book_author" VALUES(17,12,14);
INSERT INTO "book_author" VALUES(18,12,16);
INSERT INTO "book_author" VALUES(19,1,1);
CREATE TABLE book_subject (
       id serial primary key,
       book_id integer,
       subject_id integer,
       FOREIGN KEY(book_id) REFERENCES book(id),
       FOREIGN KEY(subject_id) REFERENCES subject(id)
);
INSERT INTO "book_subject" VALUES(5,3,7);
INSERT INTO "book_subject" VALUES(6,4,4);
INSERT INTO "book_subject" VALUES(7,4,7);
INSERT INTO "book_subject" VALUES(8,5,6);
INSERT INTO "book_subject" VALUES(9,5,7);
INSERT INTO "book_subject" VALUES(10,2,7);
INSERT INTO "book_subject" VALUES(11,6,2);
INSERT INTO "book_subject" VALUES(12,6,10);
INSERT INTO "book_subject" VALUES(13,7,2);
INSERT INTO "book_subject" VALUES(14,7,11);
INSERT INTO "book_subject" VALUES(15,8,2);
INSERT INTO "book_subject" VALUES(16,8,12);
INSERT INTO "book_subject" VALUES(17,9,2);
INSERT INTO "book_subject" VALUES(18,9,13);
INSERT INTO "book_subject" VALUES(19,10,2);
INSERT INTO "book_subject" VALUES(20,10,13);
INSERT INTO "book_subject" VALUES(21,11,15);
INSERT INTO "book_subject" VALUES(22,11,14);
INSERT INTO "book_subject" VALUES(23,11,16);
INSERT INTO "book_subject" VALUES(24,12,16);
INSERT INTO "book_subject" VALUES(25,12,15);
INSERT INTO "book_subject" VALUES(26,12,1);
INSERT INTO "book_subject" VALUES(27,1,6);
INSERT INTO "book_subject" VALUES(28,1,7);
INSERT INTO "book_subject" VALUES(29,1,8);
CREATE TABLE item (
       id serial primary key,
       barcode varchar(100),
       status_id integer,
       cost float,
       acquire_date date,
       book_id integer,
       notes varchar(100),
       FOREIGN KEY(book_id) REFERENCES book(id)
);
INSERT INTO "item" VALUES(3,'0003',1,10.3,'02/07/2012',3,NULL);
INSERT INTO "item" VALUES(4,'0004',2,10.4,'02/07/2012',4,NULL);
INSERT INTO "item" VALUES(5,'0005',1,10.5,'02/07/2012',5,NULL);
INSERT INTO "item" VALUES(2,'0002',2,10.2,'02/07/2012',2,NULL);
INSERT INTO "item" VALUES(6,'0006',2,10.6,'02/07/2012',6,NULL);
INSERT INTO "item" VALUES(7,'0007',1,10.7,'02/07/2012',7,NULL);
INSERT INTO "item" VALUES(8,'0008',2,10.8,'02/07/2012',8,NULL);
INSERT INTO "item" VALUES(9,'0009',1,10.9,'02/07/2012',9,NULL);
INSERT INTO "item" VALUES(10,'0010',2,10.1,'02/07/2012',10,NULL);
INSERT INTO "item" VALUES(11,'0011',1,10.11,'02/07/2012',11,NULL);
INSERT INTO "item" VALUES(12,'0012',1,10.12,'02/07/2012',12,NULL);
INSERT INTO "item" VALUES(1,'0001',1,10.1,'02/07/2012',1,NULL);
CREATE TABLE loan (
       id serial primary key,
       member_id integer,
       barcode varchar(100),
       loan_date date,
       due_date date,
       arrive_date date,
       FOREIGN KEY(member_id) REFERENCES member(id),
       FOREIGN KEY(barcode) REFERENCES item(barcode)
);
INSERT INTO "loan" VALUES(1,1,'0001','2012-07-02','2012-07-09','2012-07-02');
INSERT INTO "loan" VALUES(2,2,'0002','2012-07-02','2012-07-09',NULL);
INSERT INTO "loan" VALUES(3,3,'0003','2012-07-02','2012-07-09','2012-07-02');
INSERT INTO "loan" VALUES(4,4,'0004','2012-07-02','2012-07-09',NULL);
INSERT INTO "loan" VALUES(5,5,'0005','2012-07-02','2012-07-09','2012-07-02');
INSERT INTO "loan" VALUES(6,6,'0006','2012-07-02','2012-07-09',NULL);
INSERT INTO "loan" VALUES(7,7,'0007','2012-07-02','2012-07-09','2012-07-02');
INSERT INTO "loan" VALUES(8,8,'0008','2012-07-02','2012-07-09',NULL);
INSERT INTO "loan" VALUES(9,9,'0009','2012-07-02','2012-07-09','2012-07-02');
INSERT INTO "loan" VALUES(10,10,'0010','2012-07-02','2012-07-09',NULL);
INSERT INTO "loan" VALUES(11,1,'0003','2012-11-10','2012-11-17','2012-11-10');
CREATE TABLE sysuser (
       id serial primary key,
       login text,
       name text,
       password text,
       id_role int,
       FOREIGN KEY (id_role) REFERENCES role (id)
);
INSERT INTO "sysuser" VALUES(1,'admin','Administrator','admin',1);
INSERT INTO "sysuser" VALUES(2,'ana','Ana Librarian','test',2);
INSERT INTO "sysuser" VALUES(3,'luciele','Luciele Operator','test',3);
CREATE UNIQUE INDEX item_barcode_idx ON item (barcode);
CREATE UNIQUE INDEX author_id_idx ON author (id);
CREATE INDEX author_name_idx ON author (name);
CREATE UNIQUE INDEX subject_id_idx ON subject (id);
CREATE INDEX subject_name_idx ON subject (name);
CREATE UNIQUE INDEX publisher_id_idx ON publisher (id);
CREATE INDEX publisher_name_idx ON publisher (name);
CREATE UNIQUE INDEX collection_id_idx ON collection (id);
CREATE INDEX collection_description_idx ON collection (description);
CREATE UNIQUE INDEX classification_id_idx ON classification (id);
CREATE INDEX classification_description_idx ON classification (description);
CREATE UNIQUE INDEX city_id_idx ON city (id);
CREATE INDEX city_name_idx ON city (name);
CREATE UNIQUE INDEX status_id_idx ON status (id);
CREATE INDEX status_description_idx ON status (description);
CREATE UNIQUE INDEX category_id_idx ON category (id);
CREATE INDEX category_description_idx ON category (description);
CREATE UNIQUE INDEX book_author_id_idx ON book_author (id);
CREATE UNIQUE INDEX book_subject_id_idx ON book_subject (id);
CREATE UNIQUE INDEX item_id_idx ON item (id);
CREATE UNIQUE INDEX member_id_idx ON member (id);
CREATE INDEX member_name_idx ON member (name);
CREATE UNIQUE INDEX book_id_idx ON book (id);
CREATE INDEX book_title_idx ON book (title);
CREATE UNIQUE INDEX loan_id_idx ON loan (id);
CREATE UNIQUE INDEX user_id_idx ON sysuser (id);
COMMIT;
