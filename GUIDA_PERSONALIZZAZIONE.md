# Guida alla Personalizzazione Avanzata di PTA-Tools (v2.4.0)

Questa guida ti mostrerà come trasformare PTA-Tools in una dashboard perfettamente integrata con l'identità e le necessità del tuo ente. Tutte le modifiche avvengono nella parte alta del file `index.php`, modificando degli array di configurazione, poi il codice sottostante li integra in automatico nella dashboard corretta.

## Panoramica: Struttura di Configurazione

Il file `index.php` è organizzato in sezioni di configurazione logiche che ti permettono di personalizzare ogni aspetto dell'applicazione. Ecco l'ordine delle sezioni da modificare:

1. **`$CONFIG_GENERAL`** - Informazioni base dell'applicazione (nome, ente, sviluppatore, ecc.)
2. **`$CONFIG_THEME`** - Colori, font e stile grafico
3. **`$CONFIG_LINKS_LAYOUT`** - Etichette e testi per la sezione link/strumenti *(novità v2.4.0)*
4. **`$CONFIG_LINKS_ITEMS`** - Definizione dei singoli link e gruppi
5. **`$CONFIG_TRANSLATIONS`** - Dizionario completo delle traduzioni

Questa struttura modulare ti permette di personalizzare ogni aspetto dell'applicazione senza dover modificare il codice sottostante. Le sezioni seguenti descrivono in dettaglio come configurare ciascun array.

---

### 1. Branding e Aspetto Grafico

La prima cosa da fare è "vestire" l'applicazione con i colori e i loghi del tuo ente.

#### 1.1. Informazioni Generali (`$CONFIG_GENERAL`)

Questo array controlla le informazioni di base dell'applicazione.

```php
$CONFIG_GENERAL = [
    'nome_app'      => 'Il Mio Tool PA',      // Il nome che appare nel logo e nel titolo pagina
    'ente_acronimo' => 'MIOENTE',             // Acronimo che appare nel footer (es. UNIPV)
    'sviluppatore'  => 'Ufficio Sviluppo',    // Il tuo nome o quello del tuo ufficio
    'email_contatto'=> 'support@mioente.it',  // Email per supporto che appare nel footer
    'nome_progetto' => 'MioEnte Tools',      // Nome per il copyright nel footer
    'url_repo'      => 'https://github.com/MIOENTE/mio-tools', // Link al TUO repository se fai un fork
    
    // Cambia il path SVG per un logo personalizzato (deve avere viewBox 24x24)
    'icona_svg_path'=> 'M12 2L2 7h20L12 2z M6 7v15 M10 7v15 M14 7v15 M18 7v15 M2 22h20', 
    
    // Obbligatorio su true per la licenza, vedi sezione dedicata
    'mostra_credits_originali' => true,   // Mostra crediti originali nella footer
    
    'debug_mode'    => false,                 // Imposta a true solo per test, mostra errori PHP
    'lingua_default'=> 'it'                   // Lingua predefinita ('it' o 'en')
];
```

#### 1.2. Tema Grafico (`$CONFIG_THEME`)

Qui puoi definire l'intera palette di colori e i font per allinearli alla brand identity del tuo ente.

```php
$CONFIG_THEME = [
    'primary'       => '#d97706', // Colore principale (bottoni, link attivi, header)
    'primary_soft'  => '#fef3c7', // Sfondo chiaro per elementi attivi/hover
    'bg_body'       => '#f9fafb', // Sfondo pagina
    'bg_card'       => '#ffffff', // Sfondo delle card
    'text_main'     => '#1f2937', // Colore testo principale
    'text_sub'      => '#6b7280', // Colore testo secondario (descrizioni, etc.)
    'sidebar_width' => '280px',   // Larghezza della sidebar
    'radius'        => '8px',     // Arrotondamento bordi di card e bottoni
    // Per usare un font custom (es. da Google Fonts)
    'font_url'      => 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap',
    'font_family'   => "'Roboto', sans-serif" 
];
```

#### 1.3. Layout e Etichette della Sezione Link (`$CONFIG_LINKS_LAYOUT`)

Questo array ti permette di personalizzare tutti i testi che appaiono nella dashboard relativi alla sezione link e strumenti. È particolarmente utile se vuoi cambiare i titoli delle sezioni o i separatori senza toccare il codice HTML.

```php
$CONFIG_LINKS_LAYOUT = [
    // Testo del separatore che appare sopra i link in evidenza
    'separator_featured' => [
        'it' => 'Link in Evidenza',
        'en' => 'Featured Links',
    ],
    // Testo del separatore per gli altri link (non in evidenza)
    'separator_other' => [
        'it' => 'Altri Link Utili',
        'en' => 'Other Useful Links',
    ],
    // Titolo principale della categoria "Link" nella dashboard
    'main_title_links' => [
        'it' => 'Link di Ateneo',
        'en' => 'University Links',
    ],
    // Descrizione introduttiva per la sezione link
    'main_intro_links' => [
        'it' => 'Accesso rapido alle principali piattaforme e servizi dell\'università.',
        'en' => 'Organized collection of quick links to internal platforms.',
    ],
    // Titolo principale della categoria "Strumenti" nella dashboard
    'main_title_tools' => [
        'it' => 'Tools',
        'en' => 'Tools',
    ],
    // Descrizione introduttiva per la sezione strumenti
    'main_intro_tools' => [
        'it' => 'Strumenti operativi per calcoli, conversioni e gestione dati.',
        'en' => 'Operational tools for calculations, conversions, and data management.',
    ]
];
```

**Nota Importante**: Come per tutti gli array multilingua, ricordati di aggiungere le traduzioni per ogni lingua che hai attivato nella tua installazione. Se aggiungi una nuova lingua (vedi Sezione 3), dovrai aggiornare anche questo array.

### 2. Gestione Avanzata dei Link (`$CONFIG_LINKS_ITEMS`)

Questa è la sezione più potente. Permette di creare una dashboard dinamica con diverse tipologie di link.

#### 2.1. Tipi di Link e Visualizzazioni

Ogni elemento che aggiungi può avere una visualizzazione diversa in base a poche semplici chiavi.

*   **Link Diretto (`'tipo' => 'link'`)**: È l'elemento base. Crea una card cliccabile che porta direttamente all'URL specificato.
*   **Gruppo di Link (`'tipo' => 'gruppo'`)**: Crea una card che, se cliccata, apre una nuova pagina contenente tutti i link definiti nel suo array `'sottolink'`. È perfetto per raggruppare servizi correlati (es. tutti i link per la didattica).
*   **Link in Evidenza (`'featured' => true`)**:
    *   Se usato su un link nella **dashboard principale**, questo verrà spostato in una sezione separata in cima alla lista, con uno stile grafico più grande e a tutta larghezza per dargli massima visibilità.
    *   Se usato su un `'sottolink'` **all'interno di un gruppo**, il link manterrà la sua posizione ma avrà uno stile grafico evidenziato (es. bordo colorato) per distinguerlo dagli altri.
*   **Link a Tutta Larghezza (`'full_width' => true`)**: Questa opzione è utile **solo all'interno di un gruppo**. Fa sì che un `'sottolink'` occupi l'intera larghezza della griglia, ma senza lo stile speciale di un link `'featured'`. È ideale per creare dei separatori visivi o per link importanti ma non primari.

#### 2.2. Esempio Pratico Completo

Ecco come potresti definire un nuovo gruppo "Servizi IT" con diversi tipi di link al suo interno.

```php
'servizi_it' => [
    'tipo' => 'gruppo', // Questo lo rende una card che apre una pagina dedicata
    'testi' => [
        'it' => ['titolo' => 'Servizi IT e Supporto', 'desc' => 'Accesso a piattaforme di ticketing, webmail e guide.'],
        'en' => ['titolo' => 'IT Services & Support', 'desc' => 'Access ticketing platforms, webmail, and guides.'],
    ],
    'sottolink' => [ // Tutti i link che appariranno nella pagina del gruppo
        'helpdesk' => [
            'url' => 'https://helpdesk.mioente.it',
            'featured' => true, // Sarà evidenziato all'interno del gruppo
            'testi' => [
                'it' => ['titolo' => 'Apri un Ticket', 'desc' => 'Richiedi supporto tecnico al servizio IT.'],
                'en' => ['titolo' => 'Open a Ticket', 'desc' => 'Request technical support from the IT service.'],
            ]
        ],
        'webmail' => [
            'url' => 'https://webmail.mioente.it',
            'testi' => [
                'it' => ['titolo' => 'Webmail di Ateneo', 'desc' => 'Accesso alla casella di posta elettronica istituzionale.'],
                'en' => ['titolo' => 'University Webmail', 'desc' => 'Access your institutional email account.'],
            ]
        ],
        'guide_separator' => [
            'url' => '#', // URL fittizio, non verrà usato
            'full_width' => true, // Occuperà tutta la larghezza
            'testi' => [
                'it' => ['titolo' => '--- Guide e Tutorial ---', 'desc' => 'Consulta le nostre guide per i servizi più comuni.'],
                'en' => ['titolo' => '--- Guides & Tutorials ---', 'desc' => 'Check our guides for common services.'],
            ]
        ],
        'guida_wifi' => [
            'url' => 'https://guide.mioente.it/wifi',
            'testi' => [
                'it' => ['titolo' => 'Guida Wi-Fi', 'desc' => 'Come connettersi alla rete Eduroam.'],
                'en' => ['titolo' => 'Wi-Fi Guide', 'desc' => 'How to connect to the Eduroam network.'],
            ]
        ],
    ]
],
```

### 3. Aggiungere una Nuova Lingua (es. Francese `fr`)

L'applicazione è predisposta per essere multilingua. Segui questi 4 passi per aggiungere ad esempio il Francese.

#### Passo 1: Dichiarare la Nuova Lingua

Vai all'array `$CONFIG_TRANSLATIONS` e, dopo la chiusura dell'array `'en'`, aggiungi la nuova chiave `'fr'`.

```php
'en' => [
    // ... tutte le traduzioni inglesi ...
],

// AGGIUNGI QUI LA NUOVA LINGUA
'fr' => [
    // Qui andranno tutte le traduzioni in francese
]
```

#### Passo 2: Tradurre le Stringhe dell'Interfaccia

Copia tutte le chiavi (es. `'app_name'`, `'home_title'`, etc.) dall'array `'it'` o `'en'` e incollale dentro l'array `'fr'`. A questo punto, traduci tutti i valori in francese.

**Attenzione**: Non dimenticare di tradurre anche le chiavi relative a `$CONFIG_LINKS_LAYOUT` che potrebbero essere state integrate automaticamente nel dizionario (come `'separator_featured'`, `'main_title_links'`, etc.).

#### Passo 3: Tradurre i Titoli e le Descrizioni dei Link

Ora vai all'array `$CONFIG_LINKS_ITEMS`. Per ogni link e gruppo, aggiungi la traduzione francese all'interno del suo array `'testi'`.

Inoltre, se hai personalizzato `$CONFIG_LINKS_LAYOUT`, aggiungi anche la chiave `'fr'` con le relative traduzioni in quell'array.

#### Passo 4: Attivare il Selettore della Lingua

Scorri fino in fondo al file, nella parte HTML. Troverai due sezioni (una per desktop, una per mobile) con la classe `.lang-switch`. Aggiungi il link per la nuova lingua. Il codice ha già un esempio commentato.

```html
<!-- Dentro la classe .lang-switch -->
<a href="<?php echo ottieniUrl($id_strumento_corrente, 'it'); ?>" class="<?php echo $lingua=='it'?'active':''; ?>" title="Italiano">🇮🇹</a>
<a href="<?php echo ottieniUrl($id_strumento_corrente, 'en'); ?>" class="<?php echo $lingua=='en'?'active':''; ?>" title="English">🇬🇧</a>
<!-- DECOMMENTA E MODIFICA LA RIGA SEGUENTE -->
<a href="<?php echo ottieniUrl($id_strumento_corrente, 'fr'); ?>" class="<?php echo $lingua=='fr'?'active':''; ?>" title="Français">🇫🇷</a>
```

### 4. Attribuzione dei Crediti (Obbligo di Licenza)

Il progetto è rilasciato con licenza **CC BY-NC-SA 4.0**, che richiede l'attribuzione all'autore originale se il codice viene modificato e distribuito. Per rispettare la licenza in modo semplice e automatico:

1.  Vai all'array `$CONFIG_GENERAL`.
2.  Trova la chiave `'mostra_credits_originali'`.
3.  **Imposta il suo valore su `true`**.

```php
$CONFIG_GENERAL = [
    // ...
    // Mostra crediti originali nella footer (obbligatorio per licenza CC BY-NC-SA 4.0)
    'mostra_credits_originali' => true, // <-- CAMBIA QUESTO VALORE
    // ...
];
```