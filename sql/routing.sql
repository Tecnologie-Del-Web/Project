USE tdw;
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Public', '/', 'home.php', 'home', '');
INSERT INTO service (tag,  url, script, callback,service_description) VALUES ('Public', '/login', 'auth/access.php', 'login', 'Login');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Dashboard', '/admin', 'admin/index.php', 'admin', 'Dashboard');
