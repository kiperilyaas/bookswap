# bookswap

# Tutte le modifiche facciamo solo su nostri rami e dopo aver finito mandiamo la richesta PULL per modificare il main

# commandi da seguire

#### se non hai ancora il git
0. nella powershell di windows metti il commando --> winget install -e --id Git.Git

#### per scaricare la cartella
0.1  git clone https://github.com/kiperilyaas/bookswap.git
0.2  cd bookswap

#### se hai gia la cartella
1. git checkout il-tuo-cognome --> per spostarti sul tuo ramo
2. git pull origin main --> per sinconizarti con il codice funzionante(main)

#### FAI LE MODIFICHE DEI FILE

3. git status --> per vedere cosa hai modificato
4. git add * --> per aggiungere nel git tutti i file che hai modificato
5. git commit -m "scrivi qua il commento che ritieni giusto, che riasume quello che hai fatto"
6. git push origin il-tuo-cognome

#### APRI IL BROWSER CON QUESTO LINK
https://github.com/kiperilyaas/bookswap

#### nella sezione dei branch sceglio il tuo cognome e dopo trova il buttone `CONTRIBUTE` e fai una pool richiesta


# to do
- [x] Progettazione del Database
    - [x] Diagramma ER
    - [x] Progettazione Fisica
- [ ] Front-End
    - [x] Idea visiva
        - [x] Schermata Home
        - [x] Schermata Vendi
        - [x] Schermata Login
        - [x] Schermata Personali
        - [x] Schermata Carello
    - [ ] Creazione delle pagine web
        - [ ] Home
        - [x] Login
        - [x] Creazione della offerta
        - [x] Info personali
        - [ ] Carello
        - [x] Account (Dati Personali)
        - [x] Registrazione
        - [x] Inserimento del libro
        - [ ] Error
- [ ] Back-End
    - [ ] Models
        - [x] CRUD per users
        - [x] CRUD per books
        - [x] CRUD per orders
        - [x] gestione del login
        - [x] select Model #tutti select utili
    - [ ] Controller
        - [x] Login
            - [x] Funzionamento generale
            - [x] Gestione dei errori
            - [x] Verifica dei dati personali (solo studenti della scuola)
            - [ ] Controllo di logout
        - [ ] Offerte
            - [x] creare una offerta di vendita
            - [x] creazione del libro
        - [ ] User
            - [x] eliminazione di una offerta
            - [x] gestione della offerta/ordine del utente
            - [ ] aggiungere/eliminare i libri dal carrello
            - [ ] acquistare un libro
        - [x] Error
            - [x] Gestinoe del Errore
- [ ] Test 
- [ ] Pubblicazione

