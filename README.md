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
    - [x] Creazione delle pagine web
        - [x] Home
        - [x] Login
        - [x] Creazione della offerta
        - [x] Info personali
        - [x] Carello
        - [x] Account (Dati Personali)
        - [x] Registrazione
        - [x] Inserimento del libro
        - [x] Error
- [ ] Back-End
    - [x] Models
        - [x] Users
        - [x] Books
        - [x] Orders
        - [x] Listings
        - [x] Utils 
    - [ ] Controller
        - [x] Login
            - [x] Funzionamento generale
            - [x] Gestione dei errori
            - [x] Verifica dei dati personali (solo studenti della scuola)
            - [x] Controllo di logout
        - [x] Offerte
            - [x] creare una offerta di vendita
            - [x] creazione del libro
        - [ ] User
            - [ ] modificare anagrafe/email/password
            - [ ] eliminare utente
            - [x] eliminazione di una offerta
            - [x] gestione della offerta/ordine del utente
            - [ ] acquistare un libro
            - [ ] verifica di chiusura del ordine
        - [x] Error
            - [x] Gestione del Errore
- [ ] Test 
- [ ] Pubblicazione

