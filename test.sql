insert into paninara.class (year, section)
values (1,'A'), 
(1,'B'), 
(1,'C'), 
(2,'A'),
(2,'B'),
(4,'D');

insert into paninara.`user` (name, surname, email, `password`, active)
values ('Francesco','Pirra','francesco@gmail.com', '1234', 1),
('Nicolo','Chinaglia','chinaglia@gmail.com', 'react', 1),
('Marcelo','VienQua','marcelo@gmail.com', '1234', 1),
('Claudio','Stressadore','matematica04@gmail.com', '1234', 1),
('Matteo','Wheat','padovino@gmail.com', 'stroppare', 1),
('Luca','Medio','medio@gmail.com', '1234', 1),
('Giulio','Schizzato','mad@gmail.com', 'palestra', 1),
('Joshua','Bugatti','bugatti@gmail.com', 'portoviro', 0),
('Matteo','Birra', 'franceschino@gmail.com', '1234', 1);

insert into paninara.user_class (`user`, class, `year`)
values (1, 1, 'A');