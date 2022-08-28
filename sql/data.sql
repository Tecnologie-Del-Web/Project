USE tdw;

-- Inserisci gli utenti
INSERT INTO `user` (`user_id`, `name`,`surname`, `phone_number`, `email_address`, `password`) VALUES (1, 'admin', 'admin', '1234567890', 'admin@wolmart.it', '696d29e0940a4957748fe3fc9efd22a3');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('luca@email.it', 'Luca', 'Di Donato', '+39 33315020000', 'lucadido', 'pswluca');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('gaia@email.it', 'Gaia', 'Flammini', '+39 33320102000', 'gaiafla', 'pswgaia');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('francesco@email.it', 'Francesco', 'Ambrosini', '+39 33320101998', 'fraambro', 'pswfrancesco');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('fabio@email.it', 'Fabio', 'Di Donato', '+39 33324121963', 'fabiodido', 'pswfabio');

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

-- Inserisco alcuni prodotti
-- Alcuni libri
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Delitto e castigo", 12.82, 30, "Raskol'nikov è un giovane che è stato espulso dall'università e che uccide una vecchia usuraia per un'idea, per affermare la propria libertà e per dimostrare di essere superiore agli uomini comuni e alla loro morale. Una volta compiuto l'omicidio, però, scopre di essere governato non dalla logica, ma dal caso, dalla malattia, dall'irrazionale che affiora nei sogni e negli impulsi autodistruttivi. Si lancia cosi in allucinati vagabondaggi, percorrendo una Pietroburgo afosa e opprimente, una città-incubo popolata da reietti, da carnefici e vittime con cui è costretto a scontrarsi e a dialogare, alla disperata ricerca di una via d'uscita. Nuova traduzione di Emanuela Guercetti. Prefazione di Natalia Ginzburg e saggio introduttivo di Leonid Grossman.", 2, 1);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ('Il nome della rosa', 15.00, 30, 'Un libro magnifico di Umberto Eco', 1, 1);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ('La vegetariana', 12.00, 20, "«È tutt'altro che un'opera ascetica: è un romanzo pieno di sesso ai limiti del consenziente, di atti di alimentazione forzata e purificazione ― in altri termini di violenza sessuale e disordini alimentari, mai chiamati per nome nell'universo di Han Kang ... Il racconto di Han Kang non è un monito per l'onnivoro, e quello di Yeong-hye verso il vegetarianesimo non è un viaggio felice. Astenersi dal mangiare esseri viventi non conduce all'illuminazione. Via via che Yeong-hye si spegne, l'autrice, come una vera divinità, ci lascia a interrogarci su cosa sia meglio, che la protagonista viva o muoia. E da questa domanda ne nasce un'altra, la domanda ultima che non vogliamo davvero affrontare: 'Perché, è così terribile morire?'». (The New York Times)", 1, 1);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Gödel, Escher, Bach. Un'eterna ghirlanda brillante", 19.00, 25, "«Mi resi conto che per me Gödel, Escher e Bach erano solo ombre proiettate in diverse direzioni da una qualche solida essenza centrale. Ho tentato di ricostruire l'oggetto centrale e ne è uscito questo libro.»", 1, 1);

-- Alcuni capi d'abbigliamento
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Levi's 501 Original Jeans Uomo", 65.00, 100, 'I jeans più famosi al mondo', 3, 5);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Lee Extreme Motion, Jeans Uomo", 35.00, 80, 'Jeans resistenti e spessi, adatti anche alle stagioni più rigide', 4, 5);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Lee Pantaloncini Uomo", 45.00, 75, 'Comodissimi e dotati di cinque pratiche tasche', 4, 5);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Trousers Iconic Terry Pantaloni Sportivi Uomo", 82.00, 25, 'Un nuovo tessuto per la serie French Terry: questa gamma completa e versatile di garments è ora rinfrescata nello stile, grazie alla toppa ricamata ispirata al Campus; la serie è disponibile anche per le donne.', 5, 5);

-- Della musica (buona)
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Homogenic - Björk", 18.65, 300, "Il terzo (quarto) album in studio di Björk", 6, 2);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Post - Björk", 21.59, 300, "Il secondo album in studio di Björk", 6, 2);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Animals - Pink Floyd", 54.98, 100, "Un capolavoro in vinile", 7, 2);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("The Division Bell - Pink Floyd", 35.99, 100, "Un altro capolavoro in vinile", 7, 2);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Dirty Deeds Done Dirt Cheap - AC/DC", 19.67, 100, "Vinile", 8, 2);

-- Inserisco alcune varianti
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'DEC-001', true, 1);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'NDR-001', true, 2);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'VGT-001', true, 3);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'GEB-001', true, 4);

INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Med Indigo - Worn In', 'color', 'Variante di Default', 'LEV-001-MI', true, 11);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Nero', 'color', 'Variante Nera', 'LEV-001-NR', 0, 11);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('40W/32L', 'size', 'Variante 40W/32L', 'LEV-001-40-32', 0, 11);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('36W/32L', 'size', 'Variante 36W/32L', 'LEV-001-36-32', 0, 11);

INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'HOM-001', 1, 15);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'POS-001', 1, 16);

INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'ANM-001', 1, 17);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'TDB-001', 1, 18);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'DDC-001', 1, 19);

-- Inserisco alcune immagini per le varianti
INSERT INTO product_image(file_name, type, variant_id) VALUES ('1.jpeg', 'main', 1);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('dec_2.jpeg', 'standard', 1);
INSERT INTO product_image (file_name, type, variant_id) VALUES ('ndr.jpg', 'main', 12);
INSERT INTO product_image (file_name, type, variant_id) VALUES ('vgt.jpg', 'main', 13);
INSERT INTO product_image (file_name, type, variant_id) VALUES ('geb.jpg', 'main', 14);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('front.jpg', 'main', 2);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('rear.jpg', 'default', 2);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('full.jpg', 'default', 2);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('front.jpg', 'main', 3);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('full.jpg', 'default', 3);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('side.jpg', 'main', 4);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('full.jpg', 'main', 5);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('homogenic.jpg', 'main', 6);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('post.jpg', 'main', 8);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('animals.jpg', 'main', 9);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('the_division_bell.jpg', 'main', 10);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('dirty_deeds.jpg', 'main', 11);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('back.jpg', 'standard', 11);

-- Inserisco alcune recensioni
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Uno dei miei libri preferiti!', 5, NOW(), 2, 4);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Un libro meraviglioso', 4.50, NOW(), 3, 1);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Fantastico!', 4.50, NOW(), 4, 1);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('I miei pantaloni preferiti!', 5.00, NOW(), 2, 11);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Comprati neri, comodissimi', 5.00, NOW(), 5, 11);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Pantaloni comodissimi', 4.5, NOW(), 4, 11);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Pantaloni fantastici!', 5, NOW(), 2, 11);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Capolavoro indiscusso!', 5, NOW(), 2, 15);