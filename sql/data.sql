USE tdw;

-- Inserisco una personalizzazione iniziale
-- Passo preliminare obbligatorio!
INSERT INTO customization (customization_id, phone_number, email_address, personal_address, logo, site_name) VALUES (1, '39', 'wolmart@email.it', 'Via del Corso, Roma', 'logo.png', 'Wolmart');

-- Inserisci gli utenti
# User admin Password admin
INSERT INTO `user` (`user_id`, `name`,`surname`, `phone_number`, `email_address`, `password`) VALUES (1, 'admin', 'admin', '1234567890', 'admin@wolmart.it', '67f43efc5701784db1504e4993d7e393');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('luca@email.it', 'Luca', 'Di Donato', '+39 33315020000', 'lucadido', 'pswluca');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('gaia@email.it', 'Gaia', 'Flammini', '+39 33320102000', 'gaiafla', 'pswgaia');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('francesco@email.it', 'Francesco', 'Ambrosini', '+39 33320101998', 'fraambro', 'pswfrancesco');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('fabio@email.it', 'Fabio', 'Di Donato', '+39 33324121963', 'fabiodido', 'pswfabio');

-- Un metodo di pagamento di test
INSERT INTO payment_method (payment_code, type, credentials, validity, user_id) VALUES ('CRD-081', 'Credit Card', '55551111 08', '2025-01-01 00:00:00', 2);

-- Un ordine di test
INSERT INTO `order` (order_code, updated_at, total, progress_status, user_id, payment_id) VALUES ('ORD-081', NOW(), 150.00, 'processing', 2, 2);

-- Alcuni indirizzi di test
INSERT INTO shipment_address (city, address, province, country, postal_code, user_id) VALUES ("L'Aquila", 'Via Vetoio', "L'Aquila", 'Italia', '67100', 2);
INSERT INTO shipment_address (city, address, province, country, postal_code, user_id) VALUES ("Pescara", 'Via Vezzola', "Pescara", 'Italia', '65128', 2);

-- Inserisco alcune categorie
INSERT INTO category (category_name, category_description, category_image) VALUES ('Libri', 'Romanzi, Fumetti...', 'books.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Musica', 'CD, DVD, Vinili...', 'music.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Sport', 'Palloni, Racchette...', 'sports.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Elettronica', 'Computer, Tablet, Smartphone...', 'electronics.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Abbigliamento', 'Maglieria, Scarpe...', 'clothing.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Cibo e Bevande', 'Cibo e Bevande', 'food.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Beauty', 'Shampoo, Saponi...', 'beauty.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Strumenti Musicali', 'Chitarre, Tastiere...', 'instruments.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Cucina', 'Padelle, Pentole, Teglie...', 'cooking.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Mobili', 'Sedie, Tavoli, Letti...', 'furniture.jpg');
INSERT INTO category (category_name, category_description, category_image) VALUES ('Neonati', 'Biberon, Ciucci...', 'kids.jpg');

-- Inserisco un brand
INSERT INTO brand (brand_name, brand_image, website, phone_number, email_address, address) VALUES ('Adelphi', 'adelphi.jpg', 'adelphi.it', '+39 333', 'adelphi@email.it', 'Via Adelphi, Milano');
INSERT INTO brand (brand_name, brand_image, website, phone_number, email_address, address) VALUES ('Einaudi', 'einaudi.jpg', 'einaudi.it', '+39 3334', 'einaudi@email.it', 'Via Einaudi, Torino');
INSERT INTO brand (brand_name, brand_image, website, phone_number, email_address, address) VALUES ("Levi's", 'levis.jpg', 'levis.com', '+39 3335', 'levis@email.com', "Via Levi's, USA");
INSERT INTO brand (brand_name, brand_image, website, phone_number, email_address, address) VALUES ("Lee", 'lee.jpg', 'lee.com', '+39 3336', 'lee@email.com', "Via Lee, USA");
INSERT INTO brand (brand_name, brand_image, website, phone_number, email_address, address) VALUES ("Emporio Armani", 'emporio_armani.jpg', 'emporioarmani.it', '+39 3337', 'emporioarmani@email.it', "Via Emporio Armani, Milano");
INSERT INTO brand (brand_name, brand_image, website, phone_number, email_address, address) VALUES ('One Little Independent', 'oli.jpg', 'onelittleindependent.com', '+39 3338', 'oli@email.com', 'Via OLI, USA');
INSERT INTO brand (brand_name, brand_image, website, phone_number, email_address, address) VALUES ('Pink Floyd Music', 'pfmusic.jpg', 'pfmusic.com', '+39 3339', 'pfmusic@email.com', 'Via pfmusic, UK');
INSERT INTO brand (brand_name, brand_image, website, phone_number, email_address, address) VALUES ('AC/DC Music', 'acdc.jpg', 'acdcmusic.com', '+39 33310', 'acdcmusic@email.com', 'Via acdcmusic, UK');
INSERT INTO brand (brand_name, brand_image, website, phone_number, email_address, address) VALUES ('Apple', 'apple.jpg', 'apple.com', '+39 33311', 'apple@email.com', 'Via Apple, USA');

-- Inserisco alcuni prodotti
-- Alcuni libri
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ("Delitto e castigo", 12.82, 30, "Raskol'nikov ?? un giovane che ?? stato espulso dall'universit?? e che uccide una vecchia usuraia per un'idea, per affermare la propria libert?? e per dimostrare di essere superiore agli uomini comuni e alla loro morale. Una volta compiuto l'omicidio, per??, scopre di essere governato non dalla logica, ma dal caso, dalla malattia, dall'irrazionale che affiora nei sogni e negli impulsi autodistruttivi. Si lancia cosi in allucinati vagabondaggi, percorrendo una Pietroburgo afosa e opprimente, una citt??-incubo popolata da reietti, da carnefici e vittime con cui ?? costretto a scontrarsi e a dialogare, alla disperata ricerca di una via d'uscita.", 'DCG-001', 2, 2);
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ('Il nome della rosa', 15.00, 30, 'Un libro magnifico di Umberto Eco', 'NDR-001', 2, 2);
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ('La vegetariana', 12.00, 20, "???? tutt'altro che un'opera ascetica: ?? un romanzo pieno di sesso ai limiti del consenziente, di atti di alimentazione forzata e purificazione ??? in altri termini di violenza sessuale e disordini alimentari, mai chiamati per nome nell'universo di Han Kang ... Il racconto di Han Kang non ?? un monito per l'onnivoro, e quello di Yeong-hye verso il vegetarianesimo non ?? un viaggio felice. Astenersi dal mangiare esseri viventi non conduce all'illuminazione. Via via che Yeong-hye si spegne, l'autrice, come una vera divinit??, ci lascia a interrogarci su cosa sia meglio, che la protagonista viva o muoia. E da questa domanda ne nasce un'altra, la domanda ultima che non vogliamo davvero affrontare: 'Perch??, ?? cos?? terribile morire?'??. (The New York Times)", 'VGR-001', 1, 2);
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ("G??del, Escher, Bach. Un'eterna ghirlanda brillante", 19.00, 25, "??Mi resi conto che per me G??del, Escher e Bach erano solo ombre proiettate in diverse direzioni da una qualche solida essenza centrale. Ho tentato di ricostruire l'oggetto centrale e ne ?? uscito questo libro.??", 'GEB-001', 1, 2);

-- Alcuni capi d'abbigliamento
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ("Levi's 501 Original Jeans Uomo", 65.00, 100, 'I jeans pi?? famosi al mondo', '501-001', 3, 6);
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ("Lee Pantaloncini Uomo", 45.00, 75, 'Comodissimi e dotati di cinque pratiche tasche', 'LEE-001', 4, 6);

-- Della musica (buona)
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ("Homogenic - Bj??rk", 18.65, 300, "Il terzo (quarto) album in studio di Bj??rk", 'HOM-001', 6, 3);
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ("Post - Bj??rk", 21.59, 300, "Il secondo album in studio di Bj??rk", 'POS-001', 6, 3);
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ("Animals - Pink Floyd", 54.98, 100, "Un capolavoro in vinile", 'ANM-001', 7, 3);
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ("The Division Bell - Pink Floyd", 35.99, 100, "Un altro capolavoro in vinile", 'TDB-001', 7, 3);

-- Degli articoli elettronici
INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) VALUES ("MacBook Pro 14'' 512 GB", 2009.50, 100, "Chip Apple M1 Pro, CPU 8???core o 10-core con 6 o 8 performance core e 2 efficiency core, GPU 14???core o 16-core, Neural Engine 16???core, 200 GBps di banda di memoria", 'PRO-001', 9, 5);

-- Inserisco alcune immagini per i prodotti
INSERT INTO product_image(file_name, type, product_id) VALUES ('1.jpeg', 'main', 1);
INSERT INTO product_image(file_name, type, product_id) VALUES ('dec_2.jpeg', 'standard', 1);
INSERT INTO product_image(file_name, type, product_id) VALUES ('ndr.jpg', 'main', 2);
INSERT INTO product_image(file_name, type, product_id) VALUES ('vgt.jpg', 'main', 3);
INSERT INTO product_image(file_name, type, product_id) VALUES ('geb.jpg', 'main', 4);

INSERT INTO product_image(file_name, type, product_id) VALUES ('front.jpg', 'main', 5);
INSERT INTO product_image(file_name, type, product_id) VALUES ('rear.jpg', 'default', 5);
INSERT INTO product_image(file_name, type, product_id) VALUES ('full.jpg', 'default', 5);
INSERT INTO product_image(file_name, type, product_id) VALUES ('front.jpg', 'main', 6);
INSERT INTO product_image(file_name, type, product_id) VALUES ('rear.jpg', 'default', 6);

INSERT INTO product_image(file_name, type, product_id) VALUES ('homogenic.jpg', 'main', 10);
INSERT INTO product_image(file_name, type, product_id) VALUES ('post.jpg', 'main', 9);
INSERT INTO product_image(file_name, type, product_id) VALUES ('animals.jpg', 'main', 8);
INSERT INTO product_image(file_name, type, product_id) VALUES ('the_division_bell.jpg', 'main', 7);

INSERT INTO product_image(file_name, type, product_id) VALUES ('mac1.jpg', 'main', 11);
INSERT INTO product_image(file_name, type, product_id) VALUES ('mac2.jpg', 'standard', 11);
INSERT INTO product_image(file_name, type, product_id) VALUES ('mac3.jpg', 'standard', 11);

-- Inserisco alcune recensioni
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Uno dei miei libri preferiti!', 5, NOW(), 2, 1);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Un libro meraviglioso', 4.50, NOW(), 3, 3);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Disco fantastico', 5.00, NOW(), 2, 7);

-- Inserisco un'offerta
INSERT INTO offer (description, percentage, start_date, end_date, product_id) VALUES ("Sconto sul disco dei Pink Floyd", 20, NOW(), '2022-09-14 00:00:00', 7);

-- E un coupon sconto
INSERT INTO coupon (coupon_code, percentage, start_date, expiration_date, description) VALUES ('PROMO15', 15, NOW(), '2022-09-15 00:00:00', "Sconto del 15% su tutto l'ordine");