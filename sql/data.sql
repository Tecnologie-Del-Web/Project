USE tdw;

-- Inserisci gli utenti
INSERT INTO `user` (`user_id`, `name`,`surname`, `phone_number`, `email_address`, `password`) VALUES (1, 'admin', 'admin', '1234567890', 'admin@wolmart.it', '696d29e0940a4957748fe3fc9efd22a3');

-- Inserisco alcune categorie
INSERT INTO category (category_name, category_description, category_image) VALUES ('Musica', 'CD, DVD, Vinili...', 'category.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Sport', 'Palloni, Racchette...', 'category.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Libri', 'Romanzi, Fumetti...', 'category.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Elettronica', 'Computer, Tablet, Smartphone...', 'category.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Abbigliamento', 'Maglieria, Scarpe...', 'category.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Cibo e Bevande', 'Cibo e Bevande', 'category.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Beauty', 'Shampoo, Saponi...', 'category.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Strumenti Musicali', 'Chitarre, Tastiere...', 'category.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Cucina', 'Padelle, Pentole, Teglie...', 'category.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Mobili', 'Sedie, Tavoli, Letti...', 'category.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Neonati', 'Biberon, Ciucci...', 'category.jpg');

-- Inserisco un brand
INSERT INTO brand (brand_name, website, phone_number, email_address, address, brand_image) VALUES ('Adelphi', 'adelphi.it', '+39 333', 'adelphi@email.it', 'Via Adelphi, Milano', 'brand.jpg');
INSERT INTO brand (brand_name, website, phone_number, email_address, address, brand_image) VALUES ('Einaudi', 'einaudi.it', '+39 3334', 'einaudi@email.it', 'Via Einaudi, Torino', 'brand.jpg');

-- Inserisco alcuni prodotti
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ('Il nome della rosa', 15.00, 30, 'Un libro magnifico di Umberto Eco', 1, 1);