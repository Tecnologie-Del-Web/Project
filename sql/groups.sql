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
INSERT INTO user_has_service VALUES (2, 14);
INSERT INTO user_has_service VALUES (2, 15);
INSERT INTO user_has_service VALUES (2, 16);
INSERT INTO user_has_service VALUES (2, 17);
INSERT INTO user_has_service VALUES (2, 18);
INSERT INTO user_has_service VALUES (2, 19);
INSERT INTO user_has_service VALUES (2, 20);
INSERT INTO user_has_service VALUES (2, 21);
INSERT INTO user_has_service VALUES (2, 22);
INSERT INTO user_has_service VALUES (2, 23);
INSERT INTO user_has_service VALUES (2, 24);