CREATE DATABASE paninara;

CREATE  TABLE paninara.allergen ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(64)  NOT NULL     
 );

CREATE  TABLE paninara.break ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	`time`               TIME  NOT NULL     
 );

CREATE  TABLE paninara.class ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	year                 INT UNSIGNED NOT NULL,
	section              VARCHAR(1)  NOT NULL     
 );

CREATE  TABLE paninara.ingredient ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(64)  NOT NULL,
	description          VARCHAR(128),
	price                DECIMAL(4,2) UNSIGNED,
	extra                BOOLEAN NOT NULL DEFAULT (FALSE),
	quantity             INT UNSIGNED NOT NULL
 );

CREATE  TABLE paninara.product_allergen ( 
	product              INT UNSIGNED NOT NULL,
	allergen             INT UNSIGNED NOT NULL
 );

CREATE  TABLE paninara.pickup ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(128)  NOT NULL     
 );

CREATE  TABLE paninara.pickup_break ( 
	pickup               INT UNSIGNED NOT NULL,
	break                INT UNSIGNED NOT NULL     
 );

CREATE  TABLE paninara.product ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(64)  NOT NULL,
	price                DECIMAL(4,2) UNSIGNED NOT NULL,
	description          VARCHAR(128),
	quantity             INT  NOT NULL,
	nutritional_value    INT UNSIGNED NOT NULL,
	active               BOOLEAN  NOT NULL DEFAULT (TRUE)   
 );

CREATE  TABLE paninara.nutritional_value ( 
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

CREATE  TABLE paninara.product_ingredient ( 
	product              INT UNSIGNED NOT NULL,
	ingredient           INT UNSIGNED NOT NULL     
 );

CREATE  TABLE paninara.`status` ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	description          VARCHAR(64)  NOT NULL     
 );

CREATE  TABLE paninara.tag ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(32)  NOT NULL     
 );

CREATE  TABLE paninara.`user` ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(64)  NOT NULL,
	surname              VARCHAR(64)  NOT NULL,
	email                VARCHAR(128)  NOT NULL,
	password             VARCHAR(128)  NOT NULL,
	active               BOOLEAN  NOT NULL DEFAULT (TRUE)    
 );

 CREATE  TABLE paninara
.user_class (
	user                 INT UNSIGNED NOT NULL,
	class                INT UNSIGNED NOT NULL,
	`year`               YEAR NOT NULL
 );

CREATE  TABLE paninara.cart ( 
	`user`               INT UNSIGNED NOT NULL,
	product              INT UNSIGNED NOT NULL,
	quantity             INT UNSIGNED
 );

CREATE  TABLE paninara.favourite ( 
	user                 INT UNSIGNED NOT NULL,
	product              INT UNSIGNED NOT NULL,
	created              TIMESTAMP   DEFAULT (CURRENT_TIMESTAMP)    
 );

CREATE  TABLE paninara.offer ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	price                DECIMAL(4,2) UNSIGNED NOT NULL,
	`start`              TIMESTAMP  DEFAULT (CURRENT_TIMESTAMP)  NOT NULL,
	expiry               TIMESTAMP  DEFAULT (CURRENT_TIMESTAMP + 604800)  NOT NULL,
	description          VARCHAR(128)       
 );

CREATE  TABLE paninara.product_offer ( 
	product              INT UNSIGNED NOT NULL,
	offer                INT UNSIGNED NOT NULL  
 );

CREATE  TABLE paninara.`order` ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	`user`               INT UNSIGNED NOT NULL,
	created              TIMESTAMP  NOT NULL DEFAULT (CURRENT_TIMESTAMP),
	pickup               INT UNSIGNED NOT NULL,
	break                INT UNSIGNED NOT NULL,
	`status`             INT UNSIGNED NOT NULL,
	json                 LONGTEXT
 );

CREATE  TABLE paninara.product_order ( 
	product              INT UNSIGNED NOT NULL,
	`order`              INT UNSIGNED NOT NULL
 );

CREATE  TABLE paninara.product_tag ( 
	product              INT UNSIGNED NOT NULL,
	tag                  INT UNSIGNED NOT NULL    
 );

CREATE  TABLE paninara.reset ( 
	id                   INT UNSIGNED NOT NULL   AUTO_INCREMENT  PRIMARY KEY,
	`user`               INT UNSIGNED NOT NULL,
	password             VARCHAR(128)  NOT NULL,
	requested            TIMESTAMP  NOT NULL DEFAULT (CURRENT_TIMESTAMP),
	expires              TIMESTAMP  NOT NULL DEFAULT (CURRENT_TIMESTAMP + 21600),
	completed            BOOLEAN  NOT NULL DEFAULT (FALSE)
 );

ALTER TABLE paninara.cart ADD CONSTRAINT fk_cart_product FOREIGN KEY ( product ) REFERENCES paninara.product ( id );

ALTER TABLE paninara.cart ADD CONSTRAINT fk_cart_user FOREIGN KEY ( `user` ) REFERENCES paninara.`user` ( id );

ALTER TABLE paninara.product_allergen ADD CONSTRAINT fk_product_allergen_product FOREIGN KEY ( product ) REFERENCES paninara.product ( id );

ALTER TABLE paninara.product_allergen ADD CONSTRAINT fk_product_allergen_allergen FOREIGN KEY ( allergen ) REFERENCES paninara.allergen ( id );

ALTER TABLE paninara.pickup_break ADD CONSTRAINT fk_pickup_break_pickup FOREIGN KEY ( pickup ) REFERENCES paninara.pickup ( id );

ALTER TABLE paninara.pickup_break ADD CONSTRAINT fk_pickup_break_break FOREIGN KEY ( `break` ) REFERENCES paninara.`break` ( id );

ALTER TABLE paninara.product_ingredient ADD CONSTRAINT fk_product_ingredient_product FOREIGN KEY ( product ) REFERENCES paninara.product ( id );

ALTER TABLE paninara.product_ingredient ADD CONSTRAINT fk_product_ingredient_ingredient FOREIGN KEY ( ingredient ) REFERENCES paninara.ingredient ( id );

ALTER TABLE paninara.favourite ADD CONSTRAINT fk_favourite_user FOREIGN KEY ( `user` ) REFERENCES paninara.`user` ( id );

ALTER TABLE paninara.favourite ADD CONSTRAINT fk_favourite_product FOREIGN KEY ( product ) REFERENCES paninara.product ( id );

ALTER TABLE paninara.product_tag  ADD CONSTRAINT fk_product_tag_product FOREIGN KEY ( product ) REFERENCES paninara.product ( id );

ALTER TABLE paninara.product_tag  ADD CONSTRAINT fk_product_tag_tag FOREIGN KEY ( tag ) REFERENCES paninara.tag ( id );

ALTER TABLE paninara.product_order  ADD CONSTRAINT fk_product_order_product FOREIGN KEY ( product ) REFERENCES paninara.product ( id );

ALTER TABLE paninara.product_order  ADD CONSTRAINT fk_product_order_order FOREIGN KEY ( `order` ) REFERENCES paninara.`order` ( id );

ALTER TABLE paninara.reset  ADD CONSTRAINT fk_reset_user FOREIGN KEY ( `user` ) REFERENCES paninara.`user` ( id );

ALTER TABLE paninara.`order`  ADD CONSTRAINT fk_order_user FOREIGN KEY ( `user` ) REFERENCES paninara.`user` ( id );

ALTER TABLE paninara.`order`  ADD CONSTRAINT fk_order_status FOREIGN KEY ( status ) REFERENCES paninara.status ( id );

ALTER TABLE paninara.`order`  ADD CONSTRAINT fk_order_pickup FOREIGN KEY ( pickup ) REFERENCES paninara.pickup ( id );

ALTER TABLE paninara.`order`  ADD CONSTRAINT fk_order_break FOREIGN KEY ( break ) REFERENCES paninara.break ( id );

ALTER TABLE paninara.product  ADD CONSTRAINT fk_product_nutritional_value FOREIGN KEY ( nutritional_value ) REFERENCES paninara.nutritional_value ( id );

ALTER TABLE paninara.user_class  ADD CONSTRAINT fk_user_class_user FOREIGN KEY ( `user` ) REFERENCES paninara.`user` ( id );

ALTER TABLE paninara.user_class  ADD CONSTRAINT fk_user_class_class FOREIGN KEY ( class ) REFERENCES paninara.class ( id );

ALTER TABLE paninara.product_offer ADD CONSTRAINT fk_product_offer_product FOREIGN KEY ( product ) REFERENCES paninara.product ( id );

ALTER TABLE paninara.product_offer  ADD CONSTRAINT fk_product_offer_offer FOREIGN KEY ( offer ) REFERENCES paninara.offer ( id );
