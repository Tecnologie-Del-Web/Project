- Aggiunto 'sku' a 'product'
- Modificato expiration_date and start_date in discount con DATETIME, mi si sono verificati gli errori seguenti. Con datetime tutto ok.

[2022-08-16 21:42:26] [42000][1067] Invalid default value for 'expiration_date'
[2022-08-16 21:42:26] [HY000][1005] Can't create table `tdw`.`discounted_by` (errno: 150 "Foreign key constraint is
incorrectly formed")
[2022-08-16 21:42:26] Summary: 21 of 21 statements executed, 2 failed in
1 sec, 964 ms (5,796 symbols in file)

- Rinominate tabelle
- Cambiato 'code' con 'id'
- Aggiunto campo password a 'user'. Ora la chiave di 'user' è data dall'indirizzo e-mail, non è ovviamente possibile avere username duplicati

- l'url in service è diventato unique
- order -> coupon_id puo essere null, aggiunto campo update_at