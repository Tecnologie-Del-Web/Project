use tdw;

-- Lanciare prima data.sql per generare gli utenti

INSERT INTO `group` (group_id, group_name, group_description)
VALUES (1, 'Admin', 'Administrator');
INSERT INTO `group` (group_id, group_name, group_description)
VALUES (2, 'User', 'User');

-- ASSEGNAZIONE GRUPPI AGLI UTENTI
-- ADMIN:    admin@example.net     password
-- UTENTE:   utente@example.net    password
INSERT INTO `user_has_group` (user_id, group_id)
VALUES (1, 1);
INSERT INTO `user_has_group` (user_id, group_id)
VALUES (2, 2);

-- ASSEGNAZIONE SERVICE AI GRUPPI
-- Ad esempio, gli utenti (loggati!) possono accedere alla pagina del loro carrello
INSERT INTO service_has_group VALUES (14, 2);
INSERT INTO service_has_group VALUES (15, 2);
INSERT INTO service_has_group VALUES (16, 2);
INSERT INTO service_has_group VALUES (17, 2);
INSERT INTO service_has_group VALUES (18, 2);
INSERT INTO service_has_group VALUES (19, 2);
INSERT INTO service_has_group VALUES (20, 2);
INSERT INTO service_has_group VALUES (21, 2);
INSERT INTO service_has_group VALUES (22, 2);
INSERT INTO service_has_group VALUES (23, 2);
INSERT INTO service_has_group VALUES (24, 2);
INSERT INTO service_has_group VALUES (68, 2);
INSERT INTO service_has_group VALUES (69, 2);