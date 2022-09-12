# Gestione del template front-end

## Viste
- Home page
- Dettaglio prodotto
- Categorie
- Prodotti di una categoria
- About us
- Contatti
- Sign In/Sign Up (vista indipendente?)
- Carrello (vista indipendente?)
- Wishlist
- Privacy Policy
- Metodi di pagamento
- Metodi di spedizione
- T&C

## Parti rimosse
- **nav (.main-nav)**: appena alla destra del dropdown delle categorie, riporta quasi esclusivamente link utili a convincere all'acquisto del template. Analogo per la visualizzazione mobile
- **Rimosse** TUTTE le pagine blog. **SE** necessario, possiamo recuperarle dal template
- **Rimosse** TUTTE le pagine elements per quanto detto prima. **SE** necessario, le recuperiamo dal template
- **Rimosse** TUTTE le pagine product, tranne quella di default. **SE** necessario, possiamo recuperarne dal template
- **Rimosse** TUTTE le pagine relative ai vendor, in quanto non li gestiamo. **SE** necessario, possiamo recuperarne dal template
- **Rimossa** la sezione dei prodotti provenienti dal vendor del prodotto che l'utente sta osservando. Non gestiamo i vendor, sembra inopportuno.
- Semplificata **notevolmente** la pagina di dettaglio di un prodotto. Rimosse alcune sezioni, tra cui quella dei prodotti spesso acquistati con quello corrente: superflua e richiede la realizzazione di un algoritmino semplice.
- Modificato manualmente il CSS per permettere la corretta visualizzazione della pagine di dettaglio prodotto: non ho ancora finito i test, **potrebbe** generare qualche piccolo problemino. Nulla che non si possa risolvere in trenta secondi, però :).
- **Rimossa** sezione degli articoli recentemente visualizzati dall'utente nella home page. Non credo riusciremmo a realizzare la funzionalità, ma la possibilità c'è...


## Parti modificate
- **Rinominato** demo1.html in **index.html**


## Parti aggiunte
