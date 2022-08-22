USE tdw;

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Home', '/', 'home.php', 'home', '');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Login', '/login', 'auth/access.php', 'login', 'Login');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Dashboard', '/admin', 'admin/index.php', 'admin', 'Dashboard');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Product', '/product/%', 'product.php', 'product', 'Product Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Categories', '/categories', 'categories.php', 'categories', 'Categories Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Category', '/category/%', 'category.php', 'category', 'Category Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Contact Us', '/contact', 'contact.php', 'contact', 'Contacts Page');