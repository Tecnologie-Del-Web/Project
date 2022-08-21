USE tdw;

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Home', '/', 'home.php', 'home', '');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Login', '/login', 'auth/access.php', 'login', 'Login');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Dashboard', '/admin', 'admin/index.php', 'admin', 'Dashboard');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Product', '/product/%', 'product.php', 'product', 'Product Page');