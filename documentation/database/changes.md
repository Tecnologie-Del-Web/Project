- Aggiunto 'sku' a 'product', è un identificatore univoco del prodotto, utilizzato per distinguere i vari prodotto
- Modificato expiration_date and start_date in discount con datetime, mi si sono verificati questi errori. Con datetime tutto ok

[2022-08-16 21:42:26] [42000][1067] Invalid default value for 'expiration_date'
[2022-08-16 21:42:26] [HY000][1005] Can't create table `tdw`.`discounted_by` (errno: 150 "Foreign key constraint is
incorrectly formed")
[2022-08-16 21:42:26] Summary: 21 of 21 statements executed, 2 failed in
1 sec, 964 ms (5,796 symbols in file)

- Rinominate tabelle
- Cambiato 'code' con 'id'
- Aggiunto campo password a 'user', rimosso 'username'. La nuova chiave di 'user' diventa la 'email', infatti uno username non è realmente necessario, mentre l'e-mail deve essere unica