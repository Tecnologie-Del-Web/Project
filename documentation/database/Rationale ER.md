# Rationale ER
## Commenti alle scelte fatte nella realizzazione del modello ER

**NB: Quanto indicato di seguito si intende valido in relazione alla natura puramente concettuale del modello ER!**

- Un brand è univocamente determinato dal suo nome, come per le ragioni sociali
- NON esistono, nel modello concettuale, identificatori sintetici. I codici, ove presenti, fanno riferimento (come indicato anche su GitHub) a codici esistenti o verosimili, e in ogni caso alfanumerici.
- Con riferimento al punto precedente, le recensioni sono ora identificate univocamente dalla data di inserimento, dall'autore e dal prodotto di riferimento. Questo significa, per come abbiamo realizzato il modello, che è possibile che un utente scriva più di una recensione per lo stesso prodotto. Possiamo prenderla come la possibilità di aggiornare una recensione scritta nel passato o di scrivere una nuova a seguito del cambiamento delle caratteristiche di un prodotto.
- Per noi, le varianti di un prodotto HANNO TUTTE lo stesso prezzo. NON consideriamo varianti di un ipotetico prodotto base le configurazioni che ne modifichino il prezzo. Ad esempio, un iPhone da 128 GB e uno da 64 GB sono DUE PRODOTTI, e non lo stesso. Al contrario, l'iPhone da 128 GB può avere una variante di colore argento, una di colore blu, una di colore rosso...
- Brand e categorie hanno un'immagine (UNA sola) rappresentativa.
- Un'offerta è (ora) associato a un SOLO prodotto. Di contro, un prodotto può non essere in offerta. Non ci riferiamo, dunque, a sconti su più prodotti.
- Un coupon, al contrario, riduce il prezzo totale di un ordine. Ovviamente, un ordine può essere pagato a prezzo pieno.
- Prevediamo solo due tipi di variante: di colore e di taglia. A questo scopo, l'entità "Product Variant" ha  un attributo di tipo. Questa situazione si verifica sovente in seguito alla ristrutturazione di un ER contenente una generalizzazione. Dal momento che abbiamo a disposizione un solo diagramma da mostrare al Professore, preferiamo per chiarezza mostrare l'attributo già da ora.