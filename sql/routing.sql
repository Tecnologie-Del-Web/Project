USE tdw;

#Pulisce la tabella prima di caricare il routing
DELETE FROM service WHERE TRUE;

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Home', '/', 'home.php', 'home', '');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Login', '/login', 'auth/access.php', 'login', 'Login');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Product', '/product/%', 'product.php', 'product', 'Product Page');

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Dashboard', '/admin', 'admin/index.php', 'admin', 'Dashboard');
INSERT INTO service(tag, url, script, callback, service_description) VALUES ('Gestione ordini', '/admin/orders', 'admin/orders.php', 'orders','Visualizza ordini');

INSERT INTO service ( tag, service_description, url, script, callback) VALUES ( 'Categorie', 'Categorie', '/admin/categories', 'admin/categories.php', 'categories');
INSERT INTO service ( tag, service_description, url, script, callback) VALUES ( 'Categorie', 'Categoria', '/admin/categories/%', 'admin/categories.php', 'show');
INSERT INTO service ( tag, service_description, url, script, callback) VALUES ( 'Categorie', 'Modifica categoria', '/admin/categories/%/edit', 'admin/categories.php', 'edit');
INSERT INTO service ( tag, service_description, url, script, callback) VALUES ( 'Categorie', 'Elimina categoria', '/admin/categories/%/delete', 'admin/categories.php', 'delete');

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Categories', '/categories', 'categories.php', 'categories', 'Categories Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Category', '/category/%', 'category.php', 'category', 'Category Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Contact Us', '/contact', 'contact.php', 'contact', 'Contacts Page');

INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione utenti' , 'Visualizza utenti', '/admin/users', 'admin/users.php', 'users');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione brand', 'Visualizza brand', '/admin/brands', 'admin/brands.php', 'brands');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione coupon', 'Visualizza coupon', '/admin/coupons', 'admin/coupons.php', 'coupons');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione gruppi', 'Visualizza gruppi', '/admin/groups', 'admin/groups.php', 'groups');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione prodotti', 'Visualizza prodotti', '/admin/products', 'admin/products.php', 'products');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione offerte', 'Visualizza offerte', '/admin/offers', 'admin/offers.php', 'offers');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione ordini', 'Visualizza ordini', '/admin/orders', 'admin/orders.php', 'orders');
