USE tdw;

# Inserisci gli utenti
INSERT INTO `user` (`user_id`, `name`,`surname`, `phone_number`, `email_address`, `password`) VALUES (1, 'admin', 'admin', '1234567890', 'admin@wolmart.it', '696d29e0940a4957748fe3fc9efd22a3');

# Inserisco alcune categorie
INSERT INTO category (category_name, category_description) VALUES ('Musica', 'CD, DVD, Vinili...');
INSERT INTO category (category_name, category_description) VALUES ('Sport', 'Palloni, Racchette...');
INSERT INTO category (category_name, category_description) VALUES ('Libri', 'Romanzi, Fumetti...');
INSERT INTO category (category_name, category_description) VALUES ('Elettronica', 'Computer, Tablet, Smartphone...');
INSERT INTO category (category_name, category_description) VALUES ('Abbigliamento', 'Maglieria, Scarpe...');
INSERT INTO category (category_name, category_description) VALUES ('Cibo e Bevande', 'Cibo e Bevande');
INSERT INTO category (category_name, category_description) VALUES ('Beauty', 'Shampoo, Saponi...');
INSERT INTO category (category_name, category_description) VALUES ('Strumenti Musicali', 'Chitarre, Tastiere...');
INSERT INTO category (category_name, category_description) VALUES ('Cucina', 'Padelle, Pentole, Teglie...');
INSERT INTO category (category_name, category_description) VALUES ('Mobili', 'Sedie, Tavoli, Letti...');
INSERT INTO category (category_name, category_description) VALUES ('Neonati', 'Biberon, Ciucci...');