USE tdw;

-- Inserisci gli utenti
INSERT INTO `user` (`user_id`, `name`,`surname`, `phone_number`, `email_address`, `password`) VALUES (1, 'admin', 'admin', '1234567890', 'admin@wolmart.it', '696d29e0940a4957748fe3fc9efd22a3');

-- Inserisco un brand
INSERT INTO brand (brand_name, website, phone_number, email_address, address) VALUES ('Adelphi', 'adelphi.it', '+39 333', 'adelphi@email.it', 'Via Adelphi, Milano');
INSERT INTO brand (brand_name, website, phone_number, email_address, address) VALUES ('Einaudi', 'einaudi.it', '+39 3334', 'einaudi@email.it', 'Via Einaudi, Torino');

-- Inserisco alcuni prodotti
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id) VALUES ('Il nome della rosa', 15.00, 30, 'Un libro magnifico di Umberto Eco', brand_id);

-- Inserisco alcune categorie
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