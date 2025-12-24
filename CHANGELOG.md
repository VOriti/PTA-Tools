CHANGELOG - PTA-Tools
===================================================================

v2.4.0 - The "Polishing & Privacy" Update
-------------------------------------------------------------------
### Nuove Funzionalità
- **Sanificatore Testo Potenziato**:
  - **Filtro Privacy**: Oscura automaticamente dati sensibili (Codice Fiscale, IBAN, Email, Cellulari) per poter condividere o incollare testi in sicurezza.
  - **Latinismi**: Riconosce e formatta in corsivo automaticamente centinaia di locuzioni latine comuni (es. *ad hoc*, *una tantum*).
  - **Evidenziazione Multipla**: Ora è possibile cercare ed evidenziare più parole contemporaneamente, applicando stili diversi (grassetto, corsivo, TUTTO MAIUSCOLO) nello stesso passaggio.
  - **Formattazione Intelligente**: La funzione "Rimuovi a capo" ora è più smart: unisce le righe spezzate ma preserva i paragrafi (non unisce se c'è un punto).
  - **Correzioni Testo**: Nuovo layout coerente per le opzioni di correzione automatica della maiuscola dopo il punto e per convertire l'intero testo in MAIUSCOLO, minuscolo o Titolo.

- **Analisi IBAN**:
  - Oltre alla verifica formale, il tool ora scompone visivamente l'IBAN italiano mostrando chiaramente ABI, CAB e numero di conto.
  - Aggiunto link diretto per verificare a quale banca appartiene il codice ABI.

- **Gestione Festività**:
  - Nei tool "Differenza Date" e "Convertitore Recuperi" è ora possibile specificare il giorno del Santo Patrono e date di chiusura ufficio personalizzate per calcoli lavorativi precisi.

  - **Pasquetta Automatica**: Il sistema calcola ora automaticamente il giorno di Pasquetta per l'anno selezionato, includendolo nei festivi.

### Miglioramenti Interfaccia
- **Input Rapido (Autotab)**: Nei campi orario (HH:MM), il cursore passa automaticamente ai minuti dopo aver digitato le due cifre delle ore.
- **Gestione Righe**: Aggiunti pulsanti "X" per rimuovere facilmente le righe di orari o parole aggiunte per errore.
- **Mobile**: Su smartphone è ora possibile nascondere il footer per avere più spazio sullo schermo.
- **Copia Avanzata**: Migliorata la funzione di copia per supportare il testo formattato (RTF/HTML) per incollare direttamente in Word o Outlook mantenendo colori e stili.


v2.2.0 - The "Configuration & Theming" Update
-------------------------------------------------------------------
### Novità Principali

- **Personalizzazione Totale**: Il software è stato riorganizzato per permettere a qualsiasi ente di personalizzarlo facilmente. Colori, loghi, testi e link sono ora gestiti in un'unica configurazione centrale.

- **Nuovo Design**:
  - **Footer Informativo**: Nuova barra a fondo pagina con crediti e link utili, che si adatta allo schermo.
  - **Navigazione**: Migliorata la barra laterale e aggiunte etichette ("Link", "Gruppo", "Tool") sulle card per identificare subito il contenuto.
  - **Torna Su**: Aggiunto un comodo pulsante che appare scorrendo la pagina per tornare velocemente in cima.
- **Gestione Link Avanzata**:
  - Introdotta la possibilità di creare gruppi di link (sottomenu) e di mettere in evidenza ("Featured") le risorse più importanti direttamente nella dashboard.
- **Multilingua**: Migliorata la gestione delle traduzioni per rendere l'interfaccia coerente in ogni sua parte.


v2.1.0 - Prima Release valida per testing
-------------------------------------------------------------------
### Funzionalità
- **Link System**: Introduzione logica per link "In Evidenza" (Featured) e raggruppamenti.
- **Pagina Link**: Creazione vista dedicata per la gestione dei link di ateneo.
- **Tool Date**: Aggiunta opzione per calcolo giorni lavorativi (esclusione weekend) e aggiunta giorni a data.

### UI
- **Featured Cards**: Nuovo stile grafico per i link in evidenza (bordo colorato, dimensione maggiore).
- **Grid Layout**: Ottimizzazione griglia dashboard per contenuti misti (tool e link).

### Bug Fix
- **Validazione IBAN**: Il controllo di validità dell'IBAN ora include anche la verifica della lunghezza, prevenendo errori con codici incompleti.
- **Calcolo Date**: La funzione "Differenza Date" ora funziona correttamente anche se le date vengono inserite in ordine non cronologico.
- **Copia Testo**: Migliorata la compatibilità della funzione "Copia" (JS) per funzionare correttamente su più browser e contesti.

v1.8.0 - v1.9.0
-------------------------------------------------------------------
### Bug Fix / Sicurezza
- **CSRF Protection**: Aggiunto un token anti-CSRF a tutti i form per prevenire attacchi di tipo cross-site request forgery.
- **Session Hardening**: Rinforzate le impostazioni dei cookie di sessione (HttpOnly, Secure, SameSite) per mitigare il rischio di furto di sessione.
- **Input Sanitization**: Sanificazione degli input dell'utente (es. parametri GET con `htmlspecialchars`) per prevenire attacchi XSS.

v1.6.0
-------------------------------------------------------------------
### Funzionalità
- **Configurazione Centralizzata**: Separazione della configurazione (Tema, Link, Info Generali) dalla logica applicativa.
- **Dynamic Links**: Generazione menu e dashboard basata su array di configurazione.

### UI
- **Footer**: Introduzione footer informativo con crediti, licenza e disclaimer.
- **Back to Top**: Aggiunta pulsante flottante per scorrimento rapido a inizio pagina.
- **Theming**: Iniezione variabili CSS dinamiche da configurazione PHP.

v1.5.0
-------------------------------------------------------------------
### UI
- **Redesign Tool IVA**: Nuova interfaccia con radio button per selezione operazione e input importo in evidenza.
- **Redesign Tool Password**: Feedback visivo migliorato e pulsante copia integrato.

### Funzionalità
- **JS Copy**: Miglioramento funzione copia negli appunti (supporto textarea e input).

### Bug Fix
- **Funzione Copia**: Risolto un bug per cui la funzione di copia del testo non funzionava in modo affidabile su tutti gli elementi (es. `textarea`).

v1.4.0
-------------------------------------------------------------------
### Funzionalità
- **Revisione Testi**: Aggiornamento descrizioni strumenti per maggiore chiarezza operativa (es. note specifiche su timbrature).

### Bug Fix
- **Stabilità**: Migliorata la gestione degli errori per input di data non validi, che potevano causare calcoli errati o crash.

v1.3.0
-------------------------------------------------------------------
### UI
- **Visual Identity**: Aggiunta logo SVG vettoriale personalizzato.
- **Polishing**: Affinamento palette colori e spaziature sidebar.

### Funzionalità
- Pulizia codice e standardizzazione chiamate render.

v1.2.0
-------------------------------------------------------------------
### Funzionalità
- **Dashboard Link**: Introduzione sezione link rapidi organizzati per categoria (Didattica, Personale, Amministrazione).
- **Tool Recuperi**: Logica avanzata con mappatura orario settimanale e calcolo su calendario reale.
- **Tool Scadenza**: Aggiunta tab per selezione modalità (Calcola Fine / Calcola Inizio).

### UI
- **Mobile UX**: Header mobile con menu "Burger" e overlay oscurante.
- **Titoli**: Aggiornamento naming in "Dashboard Operativa".

### Bug Fix
- **Calcolo Ore**: Corretto il calcolo della durata per intervalli di tempo che superano la mezzanotte (es. 22:00 - 02:00).
- **Layout Mobile**: Risolti problemi di visualizzazione e interazione con la sidebar su schermi di piccole dimensioni.

v1.1.0
-------------------------------------------------------------------
### Funzionalità
- **Nuovi Strumenti**:
  - Convertitore Recuperi (Ore -> Giorni).
  - Differenza Date.
  - Gestione IVA (Scorporo/Calcolo).
  - Verifica IBAN (Controllo formale).
  - Sanificatore Testo.
  - Formattatore Liste Email.
  - Generatore Password.
- **Categorie**: Espansione catalogo (Contabilità, Ufficio).

v1.0.0
-------------------------------------------------------------------
### Funzionalità
- **Architettura Modulare**: Introduzione sistema a Catalogo (`$CATALOG`) per gestione scalabile degli strumenti.
- **Routing**: Gestione viste dinamiche basate su parametro URL.

### UI
- **Layout Dashboard**: Struttura con Sidebar laterale e area contenuti principale.
- **Navigazione**: Raggruppamento strumenti per categorie.

v0.2.0
-------------------------------------------------------------------
### UI
- **Modern UI**: Adozione font 'Inter' e variabili CSS.
- **Responsive**: Layout adattivo con sidebar fissa.
- **Wizard**: Nuova home page con card di selezione strumenti.
- **Controlli**: Toggle switch per opzioni (es. mostra secondi).

### Bug Fix
- **Stabilità**: Aggiunta gestione degli errori per input non validi (es. orari come "25:70") per prevenire crash dell'applicazione.
- **Calcolo Ore**: Implementato il calcolo corretto per gli intervalli di tempo che includono la mezzanotte.

v0.1.0
-------------------------------------------------------------------
### Funzionalità
- Rilascio Iniziale.
- Tool: Somma Intervalli Orari.
- Tool: Calcolo Scadenza/Inizio.
- Supporto multilingua base (IT/EN).