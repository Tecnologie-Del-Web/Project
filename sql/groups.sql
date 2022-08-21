use tdw;

# Lanciare prima data.sql per generare gli utenti

INSERT INTO `group` (group_id, group_name, group_description)
VALUES (1, 'Admin', 'Administrator');
INSERT INTO `group` (group_id, group_name, group_description)
VALUES (2, 'User', 'User');

# ASSEGNAZIONE GRUPPI AGLI UTENTI
# ADMIN:    admin@example.net     password
# UTENTE:   utente@example.net    password
INSERT INTO `user_has_group` (user_id, group_id)
VALUES (1, 1);