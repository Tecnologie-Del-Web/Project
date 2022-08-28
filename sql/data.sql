USE tdw;

-- Inserisci gli utenti
INSERT INTO `user` (`user_id`, `name`,`surname`, `phone_number`, `email_address`, `password`) VALUES (1, 'admin', 'admin', '1234567890', 'admin@wolmart.it', '696d29e0940a4957748fe3fc9efd22a3');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('luca@email.it', 'Luca', 'Di Donato', '+39 33315020000', 'lucadido', 'pswluca');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('gaia@email.it', 'Gaia', 'Flammini', '+39 33320102000', 'gaiafla', 'pswgaia');
INSERT INTO `user` (email_address, `name`, surname, phone_number, username, `password`) VALUES ('francesco@email.it', 'Francesco', 'Ambrosini', '+39 33320101998', 'fraambro', 'pswfrancesco');

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

-- Inserisco alcuni prodotti
-- Alcuni libri
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ('Il nome della rosa', 15.00, 30, 'Un libro magnifico di Umberto Eco', 1, 1);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ('La vegetariana', 12.00, 20, "«È tutt'altro che un'opera ascetica: è un romanzo pieno di sesso ai limiti del consenziente, di atti di alimentazione forzata e purificazione ― in altri termini di violenza sessuale e disordini alimentari, mai chiamati per nome nell'universo di Han Kang ... Il racconto di Han Kang non è un monito per l'onnivoro, e quello di Yeong-hye verso il vegetarianesimo non è un viaggio felice. Astenersi dal mangiare esseri viventi non conduce all'illuminazione. Via via che Yeong-hye si spegne, l'autrice, come una vera divinità, ci lascia a interrogarci su cosa sia meglio, che la protagonista viva o muoia. E da questa domanda ne nasce un'altra, la domanda ultima che non vogliamo davvero affrontare: 'Perché, è così terribile morire?'». (The New York Times)", 1, 1);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Gödel, Escher, Bach. Un'eterna ghirlanda brillante", 19.00, 25, "«Mi resi conto che per me Gödel, Escher e Bach erano solo ombre proiettate in diverse direzioni da una qualche solida essenza centrale. Ho tentato di ricostruire l'oggetto centrale e ne è uscito questo libro.»", 1, 1);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ('Dialoghi con Leucò', 12.50, 35, "Pubblicati nel 1947, i «Dialoghi con Leucò» ap­partengono alla singolare categoria dei li­bri tanto famosi – Pavese li volle accanto a sé quando, nella notte fra il 26 e il 27 ago­sto 1950, scelse di morire e vi annotò co­me parole di congedo «Non fate troppi pettegolezzi» – quanto negletti. Il che non stupisce: nella sua opera rappresentano una sorta di ramo a parte e oltretutto per­turbante. Si stenta oggi a crederlo, ma al­l’epoca in Italia il mito godeva di pessima fama, mentre Pavese, sin da quando, nel 1933, aveva letto Frazer, stava scoprendo l’opera di grandi antropologi che in que­gli anni si ponevano il quesito: «Che cos’è il mito?», sulla base di testi sino allora i­gnorati o poco conosciuti. Così era nata, in stretta collaborazione con Ernesto De Martino, la Viola di Einaudi, collana che rimane una gloria dell’editoria italiana. E così nacquero i «Dialoghi con Leucò». Tanto più preziosa sarà oggi, a distanza di più di settant’anni, la lettura di questo libro se si vorrà acquisire una visione stereoscopica del paesaggio in cui è nato, dove non mancarono forti reazioni di ripulsa (per la Vio­la) o di elusiva diffidenza (per i «Dialoghi con Leucò»).", 1, 1);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ('La settimana bianca', 11.40, 15, "'Ero solo, in una casetta in Bretagna, davanti al computer,' ha raccontato una volta Emmanuel Carrère 'e a mano a mano che procedevo nella storia ero sempre più terrorizzato'. All'inizio, infatti, il piccolo Nicolas ha tutta l'aria di un bambino normale. Anche se allo chalet in cui trascorrerà la settimana bianca ci arriva in macchina, portato dal padre, e non in pullman insieme ai compagni. E anche se, rispetto a loro, appare più chiuso, più fragile, più bisognoso di protezione. Ben presto, poi, scopriamo che le sue notti sono abitate da incubi, che di nascosto dai genitori legge un libro, dal quale è morbosamente attratto, intitolato Storie spaventose, e che, con una sorta di torbido compiacimento, insegue altre storie, partorite dalla sua fosca immaginazione: storie di assassini, di rapimenti, di orfanità. E sentiamo, con vaga ma crescente angoscia, che su di lui incombe un'oscura minaccia - quella che i suoi incubi possano, da un momento all'altro, assumere una forma reale, travolgendo ogni possibile difesa, condannandolo a vivere per sempre nell'inferno di quei mostri infantili. Questo perturbante, stringatissimo noir è da molti considerato il romanzo più perfetto di Emmanuel Carrère - l'ultimo da lui scritto prima di scegliere una strada diversa dalla narrativa di invenzione.", 1, 1);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("'L'avversario", 16.14, 35, "'Il 9 gennaio 1993 Jean-Claude Romand ha ucciso la moglie, i figli e i genitori, poi ha tentato di suicidarsi, ma invano. L'inchiesta ha rivelato che non era affatto un medico come sosteneva e, cosa ancor più difficile da credere, che non era nient'altro. Da diciott'anni mentiva, e quella menzogna non nascondeva assolutamente nulla. Sul punto di essere scoperto, ha preferito sopprimere le persone il cui sguardo non sarebbe riuscito a sopportare. È stato condannato all'ergastolo. Sono entrato in contatto con lui e ho assistito al processo. Ho cercato di raccontare con precisione, giorno per giorno, quella vita di solitudine, di impostura e di assenza. Di immaginare che cosa passasse per la testa di quell'uomo durante le lunghe ore vuote, senza progetti e senza testimoni, che tutti presumevano trascorresse al lavoro, e che trascorreva invece nel parcheggio di un'autostrada o nei boschi del Giura. Di capire, infine, che cosa, in un'esperienza umana tanto estrema, mi abbia così profondamente turbato - e turbi, credo, ciascuno di noi.' (Emmanuel Carrère)", 1, 1);

INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("L'idiota ", 13.77, 35, "Pubblicato nel 1868, è la storia della sconfitta di un uomo 'assolutamente buono', il principe Myskin. Un romanzo intricatissimo di avvenimenti, pieno di affetti opposti e di opposti sentimenti morali che dominano tutta l'opera entro cui si agitano bene e male, odio e amore.", 2, 1);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("I fratelli Karamazov", 25.00, 25, "«Sto per terminare i Karamazov», scrive Dostoevskij il 16 agosto del 1880. «Quest'ultima parte, lo vedo e lo sento da me, è così originale e diversa da come scrivono gli altri, che non mi aspetto alcuna approvazione dalla critica. Il pubblico, i lettori sono un'altra storia: mi hanno sempre sostenuto». A un secolo e mezzo dalla sua comparsa, dapprima sulla rivista «Russkij vestnik» (Il messaggero russo) e poi in un'edizione in due volumi che andò esaurita nel giro di qualche settimana, questa scrittura diversa e originale, «madre della prosa moderna e che ha portato alla sua intensità attuale » (James Joyce), questi «vortici in ebollizione, turbinose tempeste di sabbia, getti d'acqua che sibilano e ribollono e ci risucchiano » dentro pagine composte «essenzialmente e completamente della materia di cui è fatta l'anima» (Virginia Woolf), questa «vetta della letteratura di ogni tempo » (Albert Einstein), questo «libro che può insegnarti tutto quello che serve sapere sulla vita» (Kurt Vonnegut), questo autore «che sovrasta con la sua statura le nostre letterature e la nostra storia» e che «oggi ancora ci aiuta a vivere e sperare» (Albert Camus), questa lettura «nevrotica » (Vladimir Nabokov) ma umanissima del cristianesimo, non ha perso nulla della sua potenza letteraria. E ancora oggi, mentre assistiamo al parricidio più famoso delle lettere moderne e ne seguiamo l'esaltante iter giudiziario, siamo costretti a scendere con Ivan, Dmitrij e Alësa Karamazov nelle profondità più scomode dell'animo umano, a interrogarci sugli istinti peggiori dell'individuo e della società, a incidere come un patologo le cancrene della nostra coscienza, in un percorso in cui realtà e incubo non sempre hanno contorni netti, in cui la tragedia si accompagna alla farsa, e la disperazione si danna per alimentare una pur esile fiammella di speranza. 'I fratelli Karamazov' è il testamento letterario, e non solo, di Dostoevskij, il romanzo di chi guarda al sublime da una pozza di fango, delle idee che prendono fuoco, di coloro che «non respirano mai tranquillamente né mai si riposano (...), di chi vive nella febbre, nella convulsione, nello spasimo» (Stefan Zweig).", 2, 1);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Delitto e castigo", 12.82, 30, "Raskol'nikov è un giovane che è stato espulso dall'università e che uccide una vecchia usuraia per un'idea, per affermare la propria libertà e per dimostrare di essere superiore agli uomini comuni e alla loro morale. Una volta compiuto l'omicidio, però, scopre di essere governato non dalla logica, ma dal caso, dalla malattia, dall'irrazionale che affiora nei sogni e negli impulsi autodistruttivi. Si lancia cosi in allucinati vagabondaggi, percorrendo una Pietroburgo afosa e opprimente, una città-incubo popolata da reietti, da carnefici e vittime con cui è costretto a scontrarsi e a dialogare, alla disperata ricerca di una via d'uscita. Nuova traduzione di Emanuela Guercetti. Prefazione di Natalia Ginzburg e saggio introduttivo di Leonid Grossman.", 2, 1);


-- Alcuni capi d'abbigliamento
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Levi's 501 Original Jeans Uomo", 65.00, 100, 'I jeans più famosi al mondo', 3, 5);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Lee Extreme Motion, Jeans Uomo", 35.00, 80, 'Jeans resistenti e spessi, adatti anche alle stagioni più rigide', 4, 5);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Lee Pantaloncini Uomo", 45.00, 75, 'Comodissimi e dotati di cinque pratiche tasche', 4, 5);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Trousers Iconic Terry Pantaloni Sportivi Uomo", 82.00, 25, 'Un nuovo tessuto per la serie French Terry: questa gamma completa e versatile di garments è ora rinfrescata nello stile, grazie alla toppa ricamata ispirata al Campus; la serie è disponibile anche per le donne.', 5, 5);

-- Della musica (buona)
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Homogenic - Björk", 18.65, 300, "Bjork's third (fourth) studio album", 6, 2);
INSERT INTO product (product_name, price, quantity_available, product_description, brand_id, category_id) VALUES ("Post - Björk", 21.59, 300, "Bjork's second studio album", 6, 2);

-- Inserisco alcune varianti
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'DEC-001', true, 1);

INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Med Indigo - Worn In', 'color', 'Variante di Default', 'LEV-001-MI', true, 11);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Nero', 'color', 'Variante Nera', 'LEV-001-NR', 0, 11);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('40W/32L', 'size', 'Variante 40W/32L', 'LEV-001-40-32', 0, 11);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('36W/32L', 'size', 'Variante 36W/32L', 'LEV-001-36-32', 0, 11);

INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'HOM-001', 1, 15);
INSERT INTO product_variant (variant_name, type, description, sku, `default`, product_id) VALUES ('Default', 'default', 'Variante di Default', 'POS-001', 1, 16);

-- Inserisco alcune immagini per le varianti
INSERT INTO product_image(file_name, type, variant_id) VALUES ('1.jpeg', 'main', 1);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('dec_2.jpeg', 'standard', 1);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('front.jpg', 'main', 2);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('rear.jpg', 'default', 2);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('full.jpg', 'default', 2);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('front.jpg', 'main', 3);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('full.jpg', 'default', 3);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('side.jpg', 'main', 4);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('full.jpg', 'main', 5);

INSERT INTO product_image(file_name, type, variant_id) VALUES ('homogenic.jpg', 'main', 6);
INSERT INTO product_image(file_name, type, variant_id) VALUES ('post.jpg', 'main', 8);

-- Inserisco alcune recensioni
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Uno dei miei libri preferiti!', 5, NOW(), 2, 4);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Un libro meraviglioso', 4.50, NOW(), 3, 1);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('I miei pantaloni preferiti!', 5.00, NOW(), 2, 11);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Pantaloni comodissimi', 4.5, NOW(), 4, 11);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Pantaloni fantastici!', 5, NOW(), 2, 11);
INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('Capolavoro indiscusso!', 5, NOW(), 2, 15);