
CREATE DATABASE sandwiches;

CREATE  TABLE sandwiches.allergen ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(64)  NOT NULL     
 );

CREATE  TABLE sandwiches.break ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	`time`               TIME  NOT NULL     
 );

CREATE  TABLE sandwiches.class ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	year                 INT UNSIGNED NOT NULL,
	section              VARCHAR(1)  NOT NULL     
 );

CREATE  TABLE sandwiches.ingredient ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(64)  NOT NULL,
	description          VARCHAR(128),
	price                DECIMAL(4,2) UNSIGNED,
	extra                BOOLEAN NOT NULL DEFAULT (FALSE),
	quantity             INT UNSIGNED NOT NULL
 );

CREATE  TABLE sandwiches.product_allergen ( 
	product              INT UNSIGNED NOT NULL,
	allergen             INT UNSIGNED NOT NULL
 );

CREATE  TABLE sandwiches.pickup ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(128)  NOT NULL     
 );

CREATE  TABLE sandwiches.pickup_break ( 
	pickup               INT UNSIGNED NOT NULL,
	break                INT UNSIGNED NOT NULL     
 );

CREATE  TABLE sandwiches.product ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(64)  NOT NULL,
	price                DECIMAL(4,2) UNSIGNED NOT NULL,
	description          VARCHAR(128),
	quantity             INT  NOT NULL,
	nutritional_value    INT UNSIGNED NOT NULL,
	active               BOOLEAN  NOT NULL DEFAULT (TRUE)   
 );

CREATE  TABLE sandwiches.nutritional_value ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	kcal                 INT NOT NULL,
	fats                 DECIMAL(4,2) NOT NULL,
	saturated_fats       DECIMAL(4,2),
	carbohydrates        DECIMAL(4,2) NOT NULL,
	sugars               DECIMAL(4,2),
	proteins             DECIMAL(4,2) NOT NULL,
	fiber                DECIMAL(4,2),
	salt                 DECIMAL(4,2)
 );

CREATE  TABLE sandwiches.product_ingredient ( 
	product              INT UNSIGNED NOT NULL,
	ingredient           INT UNSIGNED NOT NULL     
 );

CREATE  TABLE sandwiches.`status` ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	description          VARCHAR(64)  NOT NULL     
 );

CREATE  TABLE sandwiches.tag ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(32)  NOT NULL     
 );

CREATE  TABLE sandwiches.`user` ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(64)  NOT NULL,
	surname              VARCHAR(64)  NOT NULL,
	email                VARCHAR(128)  NOT NULL,
	password             VARCHAR(128)  NOT NULL,
	active               BOOLEAN  NOT NULL DEFAULT (TRUE)    
 );

 CREATE  TABLE sandwiches.user_class (
	user                 INT UNSIGNED NOT NULL,
	class                INT UNSIGNED NOT NULL,
	`year`               YEAR NOT NULL
 );

CREATE  TABLE sandwiches.cart ( 
	`user`               INT UNSIGNED NOT NULL,
	product              INT UNSIGNED NOT NULL,
	quantity             INT UNSIGNED
 );

CREATE  TABLE sandwiches.favourite ( 
	user                 INT UNSIGNED NOT NULL,
	product              INT UNSIGNED NOT NULL,
	created              TIMESTAMP   DEFAULT (CURRENT_TIMESTAMP)    
 );

CREATE  TABLE sandwiches.offer ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	price                DECIMAL(4,2) UNSIGNED NOT NULL,
	`start`              TIMESTAMP  DEFAULT (CURRENT_TIMESTAMP)  NOT NULL,
	expiry               TIMESTAMP  DEFAULT (CURRENT_TIMESTAMP + 604800)  NOT NULL,
	description          VARCHAR(128)       
 );

CREATE  TABLE sandwiches.product_offer ( 
	product              INT UNSIGNED NOT NULL,
	offer                INT UNSIGNED NOT NULL  
 );

CREATE  TABLE sandwiches.`order` ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	`user`               INT UNSIGNED NOT NULL,
	created              TIMESTAMP  NOT NULL DEFAULT (CURRENT_TIMESTAMP),
	pickup               INT UNSIGNED NOT NULL,
	break                INT UNSIGNED NOT NULL,
	`status`             INT UNSIGNED NOT NULL,
	json                 LONGTEXT
 );

CREATE  TABLE sandwiches.product_order ( 
	product              INT UNSIGNED NOT NULL,
	`order`              INT UNSIGNED NOT NULL,
	quantity             INT UNSIGNED NOT NULL DEFAULT (1)
 );

CREATE  TABLE sandwiches.product_tag ( 
	product              INT UNSIGNED NOT NULL,
	tag                  INT UNSIGNED NOT NULL    
 );

CREATE  TABLE sandwiches.reset ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	`user`               INT UNSIGNED NOT NULL,
	password             VARCHAR(128)  NOT NULL,
	requested            TIMESTAMP  NOT NULL DEFAULT (CURRENT_TIMESTAMP),
	expires              TIMESTAMP  NOT NULL DEFAULT (CURRENT_TIMESTAMP + 21600),
	completed            BOOLEAN  NOT NULL DEFAULT (FALSE)
 );

ALTER TABLE sandwiches.cart ADD CONSTRAINT fk_cart_product FOREIGN KEY ( product ) REFERENCES sandwiches.product ( id );

ALTER TABLE sandwiches.cart ADD CONSTRAINT fk_cart_user FOREIGN KEY ( `user` ) REFERENCES sandwiches.`user` ( id );

ALTER TABLE sandwiches.product_allergen ADD CONSTRAINT fk_product_allergen_product FOREIGN KEY ( product ) REFERENCES sandwiches.product ( id );

ALTER TABLE sandwiches.product_allergen ADD CONSTRAINT fk_product_allergen_allergen FOREIGN KEY ( allergen ) REFERENCES sandwiches.allergen ( id );

ALTER TABLE sandwiches.pickup_break ADD CONSTRAINT fk_pickup_break_pickup FOREIGN KEY ( pickup ) REFERENCES sandwiches.pickup ( id );

ALTER TABLE sandwiches.pickup_break ADD CONSTRAINT fk_pickup_break_break FOREIGN KEY ( `break` ) REFERENCES sandwiches.`break` ( id );

ALTER TABLE sandwiches.product_ingredient ADD CONSTRAINT fk_product_ingredient_product FOREIGN KEY ( product ) REFERENCES sandwiches.product ( id );

ALTER TABLE sandwiches.product_ingredient ADD CONSTRAINT fk_product_ingredient_ingredient FOREIGN KEY ( ingredient ) REFERENCES sandwiches.ingredient ( id );

ALTER TABLE sandwiches.favourite ADD CONSTRAINT fk_favourite_user FOREIGN KEY ( `user` ) REFERENCES sandwiches.`user` ( id );

ALTER TABLE sandwiches.favourite ADD CONSTRAINT fk_favourite_product FOREIGN KEY ( product ) REFERENCES sandwiches.product ( id );

ALTER TABLE sandwiches.product_tag  ADD CONSTRAINT fk_product_tag_product FOREIGN KEY ( product ) REFERENCES sandwiches.product ( id );

ALTER TABLE sandwiches.product_tag  ADD CONSTRAINT fk_product_tag_tag FOREIGN KEY ( tag ) REFERENCES sandwiches.tag ( id );

ALTER TABLE sandwiches.product_order  ADD CONSTRAINT fk_product_order_product FOREIGN KEY ( product ) REFERENCES sandwiches.product ( id );

ALTER TABLE sandwiches.product_order  ADD CONSTRAINT fk_product_order_order FOREIGN KEY ( `order` ) REFERENCES sandwiches.`order` ( id );

ALTER TABLE sandwiches.reset  ADD CONSTRAINT fk_reset_user FOREIGN KEY ( `user` ) REFERENCES sandwiches.`user` ( id );

ALTER TABLE sandwiches.`order`  ADD CONSTRAINT fk_order_user FOREIGN KEY ( `user` ) REFERENCES sandwiches.`user` ( id );

ALTER TABLE sandwiches.`order`  ADD CONSTRAINT fk_order_status FOREIGN KEY ( status ) REFERENCES sandwiches.status ( id );

ALTER TABLE sandwiches.`order`  ADD CONSTRAINT fk_order_pickup FOREIGN KEY ( pickup ) REFERENCES sandwiches.pickup ( id );

ALTER TABLE sandwiches.`order`  ADD CONSTRAINT fk_order_break FOREIGN KEY ( break ) REFERENCES sandwiches.break ( id );

ALTER TABLE sandwiches.product  ADD CONSTRAINT fk_product_nutritional_value FOREIGN KEY ( nutritional_value ) REFERENCES sandwiches.nutritional_value ( id );

ALTER TABLE sandwiches.user_class  ADD CONSTRAINT fk_user_class_user FOREIGN KEY ( `user` ) REFERENCES sandwiches.`user` ( id );

ALTER TABLE sandwiches.user_class  ADD CONSTRAINT fk_user_class_class FOREIGN KEY ( class ) REFERENCES sandwiches.class ( id );

ALTER TABLE sandwiches.product_offer ADD CONSTRAINT fk_product_offer_product FOREIGN KEY ( product ) REFERENCES sandwiches.product ( id );

ALTER TABLE sandwiches.product_offer  ADD CONSTRAINT fk_product_offer_offer FOREIGN KEY ( offer ) REFERENCES sandwiches.offer ( id );

INSERT INTO sandwiches.break(`time`)
VALUES
('09:25'),
('11:25');

INSERT INTO sandwiches.class(year, section)
VALUES
(5,'F'),
(5,'E'),
(4,'E'),
(4,'F');

INSERT INTO sandwiches.ingredient(name, quantity, description)
VALUES
('Salame', 60, 'salame de me nonno'),
('Prosciutto', 35, 'miglior prosciutto in cirolazione'),
('Pane', 80, 'pane da panino'),
('Bresaola', 40, 'we gym'),
('Formaggio', 60, 'formaggio del despar');

INSERT INTO sandwiches.pickup(name)
VALUES
('Settore A itis'),
('Settore B itis');

INSERT INTO sandwiches.nutritional_value(kcal, fats, carbohydrates, proteins)
VALUES
(235, 25, 80, 7),
(348, 30, 63, 6),
(249, 17, 65, 25),
(80, 0, 10, 1);

INSERT INTO sandwiches.product(name, price, description, quantity, nutritional_value)
VALUES
('Panino al prosciutto', 3, 'panino fatto col miglior prosciutto in cirolazione', 26, 1),
('Panino al salame', 3, 'panino fatto col salame de me nonno', 17, 2),
('Panino proteico', 3, 'panino che possono mangiare solo i veri gymbro', 15, 3),
('Poca cola', 1, 'bevanda frizzante', 24, 4),
('Panino col formaggio', 1.20, 'panino con il formaggio del despar', 15, 2),
('Piadina al cotto', 3.50, 'piadina con il prosciutto cotto e il formaggio', 7, 3);

INSERT INTO sandwiches.`status`(description)
VALUES
('ordinato'),
('pronto'),
('annullato');

INSERT INTO sandwiches.tag(name)
VALUES
('panino'),
('bevanda'),
('piadina');

INSERT INTO sandwiches.`user`(name, surname, email, password, active)
VALUES
('Mattia', 'Gallo', 'mattia.gallinaro@iisviolamarchesini.edu.it', 'CA71@F', 1),
('Mattia', 'Zanini', 'mattia.zanini@iisviolamarchesini.edu.it', 'SIUUUUU', 0),
('Alessio', 'Modonesi', 'alessio.modonesi@iisviolamarchesini.edu.it', 'CACCIOTTI', 1),
('Cristian', 'Mondini', 'cristian.mondini@iisviolamarchesini.edu.it', 'FORZAROMA', 1),
('Matteo', 'Formenton', 'matteo.formenton@iisviolamarchesini.edu.it', 'STROPPARE', 1),
('Mattia', 'Buoso', 'mattia.buoso@iisviolamarchesini.edu.it', 'SKATER', 0),
('Michael', 'Mantoan', 'michael.mantoan@iisviolamarchesini.edu.it', 'FORTNITE', 1),
('Francesco', 'Pirra', 'francesco.pirra@iisviolamarchesini.edu.it', 'FORZACANADA', 1);

INSERT INTO sandwiches.`cart`(`user`, product, quantity)
VALUES
('1', '2', '4'),
('2', '1', '3'),
('3', '3', '2');

INSERT INTO sandwiches.offer(price, expiry, description)
VALUES
('10', '2022/01/21', 'offerta n. 1'),
('20', '2021/03/01', 'offerta n. 2'),
('15', '2022/12/31', 'offerta n. 3');

INSERT INTO sandwiches.allergen(name)
VALUES
('Latte e derivati'),
('Uova e derivati'),
('Frutta con guscio'),
('Glutine'),
('Cereali'),
('Soia'),
('Arachidi e derivati'),
('Sesamo e derivati');

INSERT INTO sandwiches.`order`(`user`, pickup, break, `status`)
VALUES
(1, 1, 1, 2),
(2, 2, 1, 3),
(3, 1, 2, 1),
(1, 2, 1, 3),
(5, 1, 2, 2),
(6, 2, 2, 1),
(7, 2, 1, 2);

INSERT INTO sandwiches.product_order(product, `order`)
VALUES
(1, 2),
(1, 3),
(2, 3),
(2, 4),
(3, 4),
(3, 2);

INSERT INTO sandwiches.user_class(`user`, class, `year`)
VALUES
(1,1, '2022'),
(2,3, '2021'),
(3,2, '2022'),
(1,3, '2021'),
(5,2, '2022'),
(5,3, '2021'),
(6,3, '2022'),
(7,4, '2022'),
(8,3, '2021'),
(8,3, '2021');

INSERT INTO sandwiches.product_ingredient(product, ingredient)
VALUES
(1, 3),
(2, 3),
(3, 3),
(5, 3),
(1, 2),
(2, 1),
(3, 4),
(5, 5);


INSERT INTO sandwiches.reset(`user`, password, expires, completed)
VALUES
(1, 'EHV0L3V1', Now(), TRUE),
(2, '',  Now() , FALSE),
(4, 'C4P0BRANC0D31P4GUR1', Now(), TRUE);


INSERT INTO sandwiches.favourite(`user`, product)
VALUES
(1, 6),
(2, 3),
(3, 2),
(4, 4);


INSERT INTO sandwiches.product_allergen(product, allergen)
VALUES
(2, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(5, 1);


INSERT INTO sandwiches.pickup_break(pickup, break)
VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2);


INSERT INTO product_tag(product, tag)
VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 2),
(5, 1),
(6, 3);


INSERT INTO sandwiches.product_offer(product, offer)
VALUES
(1, 1),
(2, 1),
(4, 1),
(6, 2),
(2, 3);


