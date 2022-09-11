use tdw;
DELETE FROM service_has_group WHERE 1;
-- Lanciare prima data.sql per generare gli utenti

INSERT INTO `group` (group_id, group_name, group_description)
VALUES (1, 'Admin', 'Administrator');
INSERT INTO `group` (group_id, group_name, group_description)
VALUES (2, 'User', 'User');

-- ASSEGNAZIONE GRUPPI AGLI UTENTI
-- ADMIN:    admin@example.net     password
-- UTENTE:   utente@example.net    password
INSERT INTO user_has_group (user_id, group_id) VALUES (1, 1);
INSERT INTO user_has_group (user_id, group_id) VALUES (1, 2);
INSERT INTO user_has_group (user_id, group_id) VALUES (2, 2);

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

INSERT INTO service_has_group VALUES (7, 1);
INSERT INTO service_has_group VALUES (26, 1);
INSERT INTO service_has_group VALUES (27, 1);
INSERT INTO service_has_group VALUES (28, 1);
INSERT INTO service_has_group VALUES (29, 1);
INSERT INTO service_has_group VALUES (30, 1);
INSERT INTO service_has_group VALUES (31, 1);
INSERT INTO service_has_group VALUES (32, 1);
INSERT INTO service_has_group VALUES (33, 1);
INSERT INTO service_has_group VALUES (34, 1);
INSERT INTO service_has_group VALUES (35, 1);
INSERT INTO service_has_group VALUES (36, 1);
INSERT INTO service_has_group VALUES (37, 1);
INSERT INTO service_has_group VALUES (38, 1);
INSERT INTO service_has_group VALUES (39, 1);
INSERT INTO service_has_group VALUES (40, 1);
INSERT INTO service_has_group VALUES (41, 1);
INSERT INTO service_has_group VALUES (42, 1);
INSERT INTO service_has_group VALUES (43, 1);
INSERT INTO service_has_group VALUES (44, 1);
INSERT INTO service_has_group VALUES (45, 1);
INSERT INTO service_has_group VALUES (46, 1);
INSERT INTO service_has_group VALUES (47, 1);
INSERT INTO service_has_group VALUES (48, 1);
INSERT INTO service_has_group VALUES (49, 1);
INSERT INTO service_has_group VALUES (50, 1);
INSERT INTO service_has_group VALUES (51, 1);
INSERT INTO service_has_group VALUES (52, 1);
INSERT INTO service_has_group VALUES (53, 1);
INSERT INTO service_has_group VALUES (54, 1);
INSERT INTO service_has_group VALUES (55, 1);
INSERT INTO service_has_group VALUES (56, 1);
INSERT INTO service_has_group VALUES (57, 1);
INSERT INTO service_has_group VALUES (58, 1);
INSERT INTO service_has_group VALUES (59, 1);
INSERT INTO service_has_group VALUES (60, 1);
INSERT INTO service_has_group VALUES (61, 1);
INSERT INTO service_has_group VALUES (62, 1);
INSERT INTO service_has_group VALUES (63, 1);
INSERT INTO service_has_group VALUES (64, 1);
INSERT INTO service_has_group VALUES (65, 1);
INSERT INTO service_has_group VALUES (66, 1);
INSERT INTO service_has_group VALUES (67, 1);
INSERT INTO service_has_group VALUES (68, 1);
INSERT INTO service_has_group VALUES (69, 1);
INSERT INTO service_has_group VALUES (70, 1);
INSERT INTO service_has_group VALUES (71, 1);
INSERT INTO service_has_group VALUES (72, 1);