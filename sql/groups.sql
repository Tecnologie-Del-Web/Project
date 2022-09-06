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
INSERT INTO service_has_group VALUES (46, 2);
INSERT INTO service_has_group VALUES (47, 2);
INSERT INTO service_has_group VALUES (49, 2);
INSERT INTO service_has_group VALUES (50, 2);