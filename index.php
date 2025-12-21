<?php
/**
 * PTA-Tools - INTEGRATED UTILITY DASHBOARD
 * Kindly developed for University of Pavia (UNIPV)
 * -----------------------------------------------------
 * Una suite di utilità PHP autonoma per compiti amministrativi.
 * A standalone PHP utility suite for administrative tasks.
 * -----------------------------------------------------
 * Nessuna dipendenza esterna richiesta.
 * No external dependencies required.
 * ----------------------------------------------------
 * Author Information / Informazioni sull'autore
 * @author  Vincenzo Oriti
 * @contact vincenzo.oriti@unipv.it
 * @personal_page https://oriti.net
 * ---------------------------------------------------------
 * Project information / Informazioni sul progetto
 * @project_page https://github.com/VOriti/PTA-Tools
 * @version 2.2.0 (2024-12-21)
 * @license CC BY-NC-SA 4.0
 * @license_url https://creativecommons.org/licenses/by-nc-sa/4.0/    
 */

// ---------------------------------------------------------
// SEZIONE 1: BOOTSTRAP
// ---------------------------------------------------------

session_start(); // Inizializza sessione PHP / Initialize PHP session

// Generazione Token CSRF per la sicurezza dei form (se non esiste)
// Generate CSRF Token for form security (if not exists)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ---------------------------------------------------------
// SEZIONE 1: CONFIGURAZIONE / CONFIGURATION
// ---------------------------------------------------------

// ---------------------------------------------------------
// SEZIONE 1.1: PERSONALIZZAZIONE BASE (BRANDING) / BASIC CUSTOMIZATION (BRANDING)
// ---------------------------------------------------------
// Modifica qui le informazioni principali per adattare il tool al tuo ente.
// Modify main information here to adapt the tool to your organization.

$CONFIG_GENERAL = [
    'nome_app'      => 'PTA-Tools',           // Nome dell'applicazione / Application name
    'ente_acronimo' => 'UNIPV',               // Acronimo Ente (es. UNIPV, UNIMI) / Organization Acronym
    'sviluppatore'  => 'Vincenzo Oriti',      // Nome sviluppatore/ufficio / Developer name/office
    'email_contatto'=> 'vincenzo.oriti@unipv.it', // Email per supporto / Support email
    'nome_progetto' => 'PTA-Tools Project',   // Nome progetto per copyright / Project name for copyright
    'url_repo'      => 'https://github.com/VOriti/PTA-Tools', // Link al repository / Repository link
    // Icona SVG (Path 'd' attribute). Default: Tempio/Università. ViewBox: 0 0 24 24
    // Icona SVG (Attributo 'd' del path). Default: Tempio/Università. ViewBox: 0 0 24 24
    'icona_svg_path'=> 'M12 2L2 7h20L12 2z M6 7v15 M10 7v15 M14 7v15 M18 7v15 M2 22h20', 
    // Mostra crediti originali nella footer (obbligatorio per licenza CC BY-NC-SA 4.0)
    // Show original credits in footer (required for CC BY-NC-SA 4.0 license)
    'mostra_credits_originali' => false,      // IMPORTANTE: Imposta a true se modifichi il progetto... / IMPORTANT: Set to true if modifying...
    'debug_mode'    => false,                 // Abilita visualizzazione errori / Enable error display
    'lingua_default'=> 'it'                   // Lingua predefinita / Default language
];

// ---------------------------------------------------------
// SEZIONE 1.2: CONFIGURAZIONE TEMA (CSS) / THEME CONFIGURATION (CSS)
// ---------------------------------------------------------
// Modifica qui i colori e lo stile dell'applicazione.
// Modify application colors and style here.

$CONFIG_THEME = [
    'primary'       => '#4338ca', // Colore primario (bottoni, link, header) / Primary color (buttons, links, header)
    'primary_soft'  => '#e0e7ff', // Sfondo chiaro per elementi attivi/hover / Light background for active/hover elements
    'bg_body'       => '#f3f4f6', // Sfondo pagina / Page background
    'bg_card'       => '#ffffff', // Sfondo card/contenitori / Card/container background
    'text_main'     => '#1f2937', // Colore testo principale / Main text color
    'text_sub'      => '#6b7280', // Colore testo secondario / Secondary text color
    'sidebar_width' => '280px',   // Larghezza sidebar / Sidebar width
    'radius'        => '8px',     // Arrotondamento bordi / Border radius
    'font_url'      => 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap', // URL Font (Google Fonts)
    'font_family'   => "'Inter', sans-serif" // Famiglia Font CSS / CSS Font Family
];

// ---------------------------------------------------------
// SEZIONE 1.3: CONFIGURAZIONE LAYOUT LINK / LINK LAYOUT CONFIGURATION
// ---------------------------------------------------------
// Definisce i testi e le etichette per la sezione dei link.
// Defines texts and labels for the links section.

$CONFIG_LINKS_LAYOUT = [
    'separator_featured' => [
        'it' => 'Link in Evidenza', // Testo separatore per link evidenziati / Separator text for featured links
        'en' => 'Featured Links',
    ],
    'separator_other' => [
        'it' => 'Altri Link Utili', // Testo separatore per altri link / Separator text for other links
        'en' => 'Other Useful Links',
    ],
    'main_title_links' => [
        'it' => 'Link di Ateneo', // Titolo per la sezione link nella dashboard / Title for the links section on the dashboard
        'en' => 'University Links',
    ],
    'main_intro_links' => [
        'it' => 'Accesso rapido alle principali piattaforme e servizi dell\'università.', // Intro per la sezione link / Intro for the links section
        'en' => 'Organized collection of quick links to internal platforms.',
    ],
    'main_title_tools' => [
        'it' => 'Tools', // Titolo per la sezione strumenti nella dashboard / Title for the tools section on the dashboard
        'en' => 'Tools',
    ],
    'main_intro_tools' => [
        'it' => 'Strumenti operativi per calcoli, conversioni e gestione dati.', // Intro per la sezione strumenti / Intro for the tools section
        'en' => 'Operational tools for calculations, conversions, and data management.',
    ]
];

// ---------------------------------------------------------
// SEZIONE 1.4: LISTA LINK PERSONALIZZATI / CUSTOM LINKS LIST
// ---------------------------------------------------------
// ISTRUZIONI PER L'ADATTAMENTO:
// Modifica questo array per cambiare i link, i titoli e le descrizioni
// per il tuo Ateneo/Ente. Il sistema genererà automaticamente le voci.
//
// ADAPTATION INSTRUCTIONS:
// Modify this array to change links, titles, and descriptions
// for your University/Organization. The system will automatically generate the entries.

$CONFIG_LINKS_ITEMS = [
    'aggregatore_ateneo' => [
        'tipo' => 'link', // 'link' per collegamento diretto, 'gruppo' per menu a tendina / 'link' for direct link, 'gruppo' for dropdown
        'url' => 'https://io.unipv.it',
        'featured' => true, // Evidenzia il link. Nella home viene anche posto in cima, nei gruppi mantiene l'ordine. / Highlights the link. In home it also moves to top, in groups keeps order.
        'testi' => [
            'it' => ['titolo' => 'Aggregatore Applicativi', 'desc' => 'Portale unico per l\'accesso ai servizi di ateneo.'],
            'en' => ['titolo' => 'Application Aggregator', 'desc' => 'Single portal for accessing university services.'],
            // aggiungi altre lingue se necessario / add other languages if needed
        ]
    ],
    'rubrica' => [
        'tipo' => 'gruppo',
        'testi' => [
            'it' => ['titolo' => 'Rubrica e Contatti', 'desc' => 'Cerca persone, competenze e strutture.'],
            'en' => ['titolo' => 'Address Book & Contacts', 'desc' => 'Search for people, skills, and structures.'],
        ],
        'sottolink' => [
            'rubrica_ateneo' => [
                'url' => 'http://rubrica.unipv.it',
                'testi' => [
                    'it' => ['titolo' => 'Rubrica di Ateneo', 'desc' => 'Cerca contatti del personale docente e tecnico-amministrativo.'],
                    'en' => ['titolo' => 'University Address Book', 'desc' => 'Search for contacts of teaching and administrative staff.'],
                ]
            ],
            'organigramma' => [
                'url' => 'https://portale.unipv.it/it/ateneo/organizzazione/amministrazione/organigramma',
                'testi' => [
                    'it' => ['titolo' => 'Organigramma di Ateneo', 'desc' => 'Struttura organizzativa dell\'amministrazione.'],
                    'en' => ['titolo' => 'University Organization Chart', 'desc' => 'Organizational structure of the administration.'],
                ]
            ],
            'unifind' => [
                'url' => 'https://unipv.unifind.cineca.it/',
                'testi' => [
                    'it' => ['titolo' => 'UniFind', 'desc' => 'Portale della ricerca e delle competenze.'],
                    'en' => ['titolo' => 'UniFind', 'desc' => 'Research and skills portal.'],
                ]
            ],
            'redazione_unifind' => [
                'url' => 'https://redazione-unifind.unipv.it',
                'testi' => [
                    'it' => ['titolo' => 'Area Redazionale UniFind', 'desc' => 'Accesso riservato per la gestione dei profili UniFind.'],
                    'en' => ['titolo' => 'UniFind Editorial Area', 'desc' => 'Reserved access for UniFind profile management.'],
                ]
            ],
            'iris' => [
                'url' => 'https://iris.unipv.it/',
                'testi' => [
                    'it' => ['titolo' => 'IRIS - Archivio della Ricerca', 'desc' => 'Catalogo dei prodotti della ricerca di Ateneo.'],
                    'en' => ['titolo' => 'IRIS - Research Archive', 'desc' => 'University research products catalog.'],
                ]
            ]
        ]
    ],
    'calendari_aule' => [
        'tipo' => 'gruppo',
        'testi' => [
            'it' => ['titolo' => 'Calendari Occupazione Aule', 'desc' => 'Visualizza i calendari di occupazione dei poli didattici.'],
            'en' => ['titolo' => 'Classroom Occupation Calendars', 'desc' => 'View the occupation calendars for the university buildings.'],
        ],
        'sottolink' => [
            'accesso_riservato' => [
                'url' => 'https://unipv.prod.up.cineca.it/login',
                'featured' => true,
                'testi' => [
                    'it' => ['titolo' => 'Accesso riservato UPlanner', 'desc' => 'Login per inserimento occupazioni e prenotazioni.'],
                    'en' => ['titolo' => 'UPlanner Reserved Access', 'desc' => 'Login for inserting occupations and reservations.'],
                ]
            ],
            'swagger' => [
                'url' => 'http://testing-2022.unipv.it/aule/',
                'featured' => true,
                'testi' => [
                    'it' => ['titolo' => 'Swagger UPlanner API', 'desc' => 'Documentazione API per l\'occupazione delle aule.'],
                    'en' => ['titolo' => 'Swagger UPlanner API', 'desc' => 'API documentation for classroom occupation.'],
                ]
            ],
            'polo_a' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=6005470d85e1a2001879f060',
                'full_width' => true, // Imposta a true per visualizzare il link a tutta larghezza ma senza le migliorie di 'featured' / Set to true to display the link  in full width but without the enhancements of 'featured'
                'testi' => [
                    'it' => ['titolo' => 'Aule Polo A', 'desc' => 'Centrale, S. Tommaso, S. Felice, Via Luino.'],
                    'en' => ['titolo' => 'Campus A Classrooms', 'desc' => 'Centrale, S. Tommaso, S. Felice, Via Luino.'],
                ]
            ],
            'polo_b' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=6005421a85e1a2001879f044',
                'full_width' => true,
                'testi' => [
                    'it' => ['titolo' => 'Aule Polo B', 'desc' => 'Taramelli, Forlanini, Bassi, Cravino, Policlinico.'],
                    'en' => ['titolo' => 'Campus B Classrooms', 'desc' => 'Taramelli, Forlanini, Bassi, Cravino, Policlinico.'],
                ]
            ],
            'polo_c' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=60054313a5ba2f00176e68d9',
                'full_width' => true,
                'testi' => [
                    'it' => ['titolo' => 'Aule Polo C', 'desc' => 'Orto Botanico, Via Ferrata, Campus Acquae.'],
                    'en' => ['titolo' => 'Campus C Classrooms', 'desc' => 'Orto Botanico, Via Ferrata, Campus Acquae.'],
                ]
            ],
            'san_felice' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=5e3d320d8409120018939b0a',
                'testi' => [
                    'it' => ['titolo' => 'San Felice', 'desc' => 'Calendario aule San Felice.'],
                    'en' => ['titolo' => 'San Felice', 'desc' => 'San Felice classrooms calendar.'],
                ]
            ],
            'aule_informatiche' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=63ee293350fdb719fb068430',
                'testi' => [
                    'it' => ['titolo' => 'Aule Informatiche (IDCD & Ing)', 'desc' => 'Calendario delle aule informatiche dedicate.'],
                    'en' => ['titolo' => 'IT Classrooms (IDCD & Eng)', 'desc' => 'Calendar of dedicated IT classrooms.'],
                ]
            ],
            'palazzo_centrale_orto' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=5f71a49a60030b0017f111a4',
                'testi' => [
                    'it' => ['titolo' => 'Aule Palazzo Centrale + Orto Botanico', 'desc' => 'Calendario aule Palazzo Centrale e Orto Botanico.'],
                    'en' => ['titolo' => 'Central Building + Botanical Garden', 'desc' => 'Central Building and Botanical Garden classrooms calendar.'],
                ]
            ],
            'san_tommaso' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=5f71a5599e03440018b94306',
                'testi' => [
                    'it' => ['titolo' => 'San Tommaso', 'desc' => 'Calendario aule San Tommaso.'],
                    'en' => ['titolo' => 'San Tommaso', 'desc' => 'San Tommaso classrooms calendar.'],
                ]
            ],
            'chimica_farmacia' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=5f71a955fbaba40017abd40c',
                'testi' => [
                    'it' => ['titolo' => 'Aule Chimica & Farmacia', 'desc' => 'Calendario aule di Chimica e Scienze del Farmaco.'],
                    'en' => ['titolo' => 'Chemistry & Pharmacy Classrooms', 'desc' => 'Calendar for Chemistry and Pharmaceutical Sciences classrooms.'],
                ]
            ],
            'policlinico_salute' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=6218dc04b39bfb002ba037c3',
                'testi' => [
                    'it' => ['titolo' => 'Policlinico & Campus Salute', 'desc' => 'Calendario aule del Policlinico e Campus Salute.'],
                    'en' => ['titolo' => 'Policlinico & Health Campus', 'desc' => 'Calendar for Policlinico and Health Campus classrooms.'],
                ]
            ],
            'biochimica' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=5f7c788888eb3b0012c04c98',
                'testi' => [
                    'it' => ['titolo' => 'Biochimica (incrocio Via Taramelli)', 'desc' => 'Calendario aule Biochimica.'],
                    'en' => ['titolo' => 'Biochemistry (Via Taramelli)', 'desc' => 'Biochemistry classrooms calendar.'],
                ]
            ],
            'cravino_forlanini_bassi' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=5f71aac34515210017760275',
                'testi' => [
                    'it' => ['titolo' => 'Cascina Cravino + Via Forlanini + Fisica Via Bassi', 'desc' => 'Calendario aule Cravino, Forlanini e Fisica.'],
                    'en' => ['titolo' => 'Cravino + Forlanini + Physics', 'desc' => 'Cravino, Forlanini and Physics classrooms calendar.'],
                ]
            ],
            'via_ferrata' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=5f71abcc60030b0017f1146a',
                'testi' => [
                    'it' => ['titolo' => 'Via Ferrata', 'desc' => 'Calendario aule Via Ferrata.'],
                    'en' => ['titolo' => 'Via Ferrata', 'desc' => 'Via Ferrata classrooms calendar.'],
                ]
            ],
            'campus_acquae' => [
                'url' => 'https://unipv.prod.up.cineca.it/calendarioPubblico/linkCalendarioId=5f71ad23fbaba40017abd40c',
                'testi' => [
                    'it' => ['titolo' => 'Campus Acquae', 'desc' => 'Calendario aule Campus Acquae.'],
                    'en' => ['titolo' => 'Campus Acquae', 'desc' => 'Campus Acquae classrooms calendar.'],
                ]
            ]
        ]
    ],
    'kiro_platforms' => [
        'tipo' => 'gruppo',
        'testi' => [
            'it' => ['titolo' => "The Kiros", 'desc' => 'Piattaforme di didattica e formazione.'],
            'en' => ['titolo' => "The Kiros", 'desc' => 'Teaching and training platforms.'],
        ],
        'sottolink' => [
            'kiro_formazione' => [
                'url' => 'https://elearning-fo.unipv.it/',
                'testi' => [
                    'it' => ['titolo' => 'Kiro - Formazione personale', 'desc' => 'Piattaforma per la formazione del personale.'],
                    'en' => ['titolo' => 'Kiro - Staff Training', 'desc' => 'Platform for staff training.'],
                ]
            ],
            'kiro_curriculare' => [
                'url' => 'https://elearning.unipv.it/',
                'testi' => [
                    'it' => ['titolo' => 'Kiro - Didattica Curriculare', 'desc' => 'Piattaforma principale per i corsi di laurea.'],
                    'en' => ['titolo' => 'Kiro - Curricular Teaching', 'desc' => 'Main platform for degree courses.'],
                ]
            ],
            'kiro_extracurriculare' => [
                'url' => 'https://elearning-excu.unipv.it/',
                'testi' => [
                    'it' => ['titolo' => 'Kiro - Didattica Extra Curriculare', 'desc' => 'Master, corsi di perfezionamento e altro.'],
                    'en' => ['titolo' => 'Kiro - Extra-Curricular', 'desc' => 'Masters, specialization courses and more.'],
                ]
            ],
            'panopto' => [
                'url' => 'https://unipv.cloud.panopto.eu/Panopto/Pages/Home.aspx',
                'testi' => [
                    'it' => ['titolo' => 'Panopto', 'desc' => 'Piattaforma video per la didattica.'],
                    'en' => ['titolo' => 'Panopto', 'desc' => 'Video platform for teaching.'],
                ]
            ]
        ]
    ],
    'cartellino' => [
        'tipo' => 'link',
        'url' => 'http://startweb.unipv.it',
        'testi' => [
            'it' => ['titolo' => 'Gestionale Cartellino', 'desc' => 'Accesso al sistema di rilevazione presenze StartWeb.'],
            'en' => ['titolo' => 'Time Tracking System', 'desc' => 'Access the StartWeb attendance tracking system.'],
        ]
    ],
    'ticketing_interno' => [
        'tipo' => 'link',
        'url' => 'https://sos.unipv.it',
        'testi' => [
            'it' => ['titolo' => 'Ticketing Interno (SOS)', 'desc' => 'Apri una richiesta di supporto tecnico al servizio informatico.'],
            'en' => ['titolo' => 'Internal Ticketing (SOS)', 'desc' => 'Open a technical support request to the IT service.'],
        ]
    ],
    'ticketing_manutenzioni' => [
        'tipo' => 'link',
        'url' => 'https://unipv.infocad.fm/',
        'testi' => [
            'it' => ['titolo' => 'Ticketing Manutenzioni', 'desc' => 'Segnala guasti o richiedi interventi di manutenzione.'],
            'en' => ['titolo' => 'Maintenance Ticketing', 'desc' => 'Report failures or request maintenance interventions.'],
        ]
    ],
];

// ---------------------------------------------------------
// SEZIONE 2: DIZIONARIO TRADUZIONI / TRANSLATION DICTIONARY
// ---------------------------------------------------------

$CONFIG_TRANSLATIONS = [
    'it' => [ // inizio traduzioni italiano / start Italian translations
        // -- INTERFACCIA GENERALE / GENERAL UI --
        'app_name' => $CONFIG_GENERAL['nome_app'],
        'home_title' => 'Dashboard Principale',
        'back_dash' => 'Torna alla Dashboard',
        'copied' => 'Copiato negli appunti!',
        
        // -- DASHBOARD CATEGORIES --
        'cat_time' => 'Gestione Tempo',
        'intro_time' => 'Strumenti per il calcolo ore lavorate, verifica timbrature e conversioni.',
        'cat_account' => 'Contabilità',
        'intro_account' => 'Utility per calcolo IVA, verifica codici bancari e operazioni fiscali.',
        'cat_office' => 'Ufficio & Utilità',
        'intro_office' => 'Tool per pulizia testi, liste email e sicurezza.',
        
        // -- COMMON LABELS & BUTTONS --
        'lbl_from' => 'DA',
        'lbl_to' => 'A',
        'lbl_days' => 'Giorni',
        'lbl_hours' => 'Ore',
        'lbl_mins' => 'Min',
        'lbl_start_date' => 'Data Inizio',
        'lbl_end_date' => 'Data Fine',
        'lbl_today' => 'Oggi',
        'lbl_result' => 'Risultato',
        'calc' => 'Calcola',
        'verify' => 'Verifica',
        'copy' => 'Copia',
        'clean' => 'Pulisci Testo',
        'format' => 'Formatta',
        'generate' => 'Genera',
        'type_link' => 'Link',
        'type_group' => 'Gruppo',
        'type_tool' => 'Tool',
        
        // -- CALENDAR / DAYS --
        'day_mon' => 'Lunedì',
        'day_tue' => 'Martedì',
        'day_wed' => 'Mercoledì',
        'day_thu' => 'Giovedì',
        'day_fri' => 'Venerdì',
        'day_sat' => 'Sabato',
        'day_sun' => 'Domenica',
        
        // -- TOOL: CALCOLO ORE (Intervalli) --
        'tool_intervalli' => 'Calcolo Ore Lavorate',
        'desc_short_intervalli' => 'Somma intervalli di tempo per calcolare le ore totali lavorate.',
        'desc_long_intervalli' => 'A volte il nostro sistema di cartellino (Startweb) non visualizza il tempo passato in casi particolari (servizio esterno, formazione, timbratire manuali, ecc.) finché queste non vengono approvate dai responsabili. con questo tool basta digitare gli orari di inizio fine delle varie fasi della giornata per avere un calcolo delle ore lavorate in tempo reale',
        'lbl_warning' => 'ATTENZIONE',
        'note_intervalli' => 'Se la pausa pranzo è inferiore a 10 minuti StartWeb toglie in automatico i 10 minuti minimi, si prega di tenerne conto o il risultato del calcolatore sarà fallace',
        
        // -- TOOL: CONVERTITORE RECUPERI --
        'tool_recuperi' => 'Convertitore Recuperi',
        'desc_short_recuperi' => 'Converte il saldo di straordinario in giorni di permesso e calcola il resto.',
        'desc_long_recuperi' => 'Spesso ci si ritrova con un numero dispari di tempo in straordinario a recupero (ad esempio 17 ore e 43 minuti) e bisogna sapere a quante giornate corrispondono quando si usa il permesso "recupero straordinari a giornata intera". Ad esempio se ho 5 giorni lavorativi da 7 ore e 12 minuti devo fare una divisione complessa dei resti. Questo tool permette di inserire la propria settimana lavorativa e il giorno di inizio e dice quanti giorni di permesso si possono avere e il resto in ore e minuti.',
        'lbl_balance_hours' => 'Saldo Ore',
        'lbl_week_schedule' => 'Orario Settimanale',
        'res_recuperi_intro' => 'Partendo dal giorno selezionato potresti assentarti per <strong>%s</strong> giorni. Pertanto:',
        'res_recuperi_period' => 'Puoi assentarti dal %s fino al %s',
        'res_recuperi_rem' => 'Ti resterà un saldo di:',
        'warn_recuperi_holidays' => 'Attenzione: il calcolo è fatto assumendo che tutti i giorni tra lunedì e venerdì siano lavorativi. Ricordati se ci fossero festività in mezzo alla settimana di considerarle come ulteriori giorni in più.',
        
        // -- TOOL: SCADENZA E DURATA --
        'tool_scadenza' => 'Scadenza e Durata',
        'desc_short_scadenza' => 'Calcola l\'ora di fine partendo da inizio e durata, o viceversa.',
        'desc_long_scadenza' => 'Calcola l\'ora di fine o di inizio in base alla durata.',
        'lbl_want_end' => 'Voglio sapere quando FINISCO',
        'lbl_want_start' => 'Voglio sapere quando INIZIARE',
        'lbl_ref_time' => 'Orario Inizio/Fine',
        'lbl_date_opt' => 'Data (Opzionale)',
        'ph_dd' => 'GG',
        'ph_mm' => 'MM',
        'ph_yyyy' => 'AAAA',
        'ph_hh' => 'HH',
        'lbl_duration' => 'Durata Attività',
        'lbl_pause' => 'Pausa Totale',
        'lbl_end_time' => 'Ora Fine:',
        'lbl_start_time' => 'Ora Inizio:',
        
        // -- TOOL: DIFFERENZA DATE --
        'tool_dates' => 'Differenza Date',
        'desc_short_dates' => 'Calcola anni, mesi e giorni trascorsi tra due date specifiche.',
        'desc_long_dates' => 'Calcola l\'intervallo esatto (anni, mesi, giorni) tra due date.',
        'lbl_diff_dates' => 'Differenza tra Date',
        'lbl_calc_working' => 'Solo giorni lavorativi (Lun-Ven)',
        'lbl_add_days_date' => 'Aggiungi Giorni a Data',
        'lbl_days_to_add' => 'Giorni da aggiungere',
        'lbl_years' => 'Anni',
        'lbl_months' => 'Mesi',
        'lbl_total_days' => 'Giorni totali',
        'lbl_all_days' => 'conteggiando tutti i giorni',
        
        // -- TOOL: GESTIONE IVA --
        'tool_iva' => 'Gestione IVA',
        'desc_short_iva' => 'Scorpora o applica l\'IVA da un importo lordo o netto.',
        'desc_long_iva' => 'Scorporo e applicazione aliquote IVA.',
        'lbl_amount' => 'Importo (€)',
        'ph_amount' => 'es. 1220,00',
        'lbl_rate' => 'Aliquota',
        'rate_22' => '22% (Ordinaria)',
        'rate_10' => '10% (Ridotta)',
        'rate_4' => '4% (Minima)',
        'lbl_other' => 'Altro (libero)',
        'lbl_custom_rate' => 'Aliquota personalizzata (%)',
        'lbl_op' => 'Operazione',
        'lbl_op_scorpora' => 'Scorpora (Lordo -> Netto)',
        'lbl_op_add' => 'Applica (Netto -> Lordo)',
        'lbl_op_calc' => 'Solo Calcolo IVA',
        'lbl_net' => 'Imponibile',
        'lbl_vat' => 'IVA',
        'lbl_gross' => 'Lordo',
        
        // -- TOOL: VERIFICA IBAN --
        'tool_iban' => 'Verifica IBAN',
        'desc_short_iban' => 'Controlla la validità formale di un codice IBAN nazionale o internazionale.',
        'desc_long_iban' => 'Controllo formale della correttezza di un codice IBAN.',
        'lbl_iban_code' => 'Codice IBAN',
        'msg_iban_ok' => 'IBAN formalmente CORRETTO',
        'msg_iban_ko' => 'ERRORE: IBAN non valido',
        
        // -- TOOL: SANIFICATORE TESTO --
        'tool_text' => 'Sanificatore Testo',
        'desc_short_text' => 'Correggi maiuscole, spazi e a capo per testi puliti.',
        'desc_long_text' => 'Pulisce testi da PDF, rimuove spazi doppi e corregge maiuscole.',
        'lbl_input_text' => 'Testo Input',
        'opt_oneline' => 'Rimuovi A Capo',
        'opt_spaces' => 'Rimuovi Spazi Doppi',
        'opt_title' => 'Iniziali Maiuscole (Mario Rossi)',
        'opt_upper' => 'TUTTO MAIUSCOLO',
        'opt_lower' => 'tutto minuscolo',
        
        // -- TOOL: LISTA EMAIL --
        'tool_email' => 'Lista Email',
        'desc_short_email' => 'Trasforma un elenco di email in una riga per client di posta.',
        'desc_long_email' => 'Formatta colonne Excel in liste per Outlook/Gmail.',
        'lbl_input_list' => 'Lista Input (Colonna Excel)',
        'ph_email_list' => 'mario.rossi@unipv.it&#10;luigi.verdi@unipv.it',
        'lbl_separator' => 'Separatore',
        'opt_comma' => ', (Gmail)',
        'opt_semicolon' => '; (Outlook)',
        
        // -- TOOL: GENERATORE PASSWORD --
        'tool_pass' => 'Generatore Password',
        'desc_short_pass' => 'Crea password sicure, facili da dettare e ricordare.',
        'desc_long_pass' => 'Crea password sicure ma pronunciabili per helpdesk.',
        'msg_press_generate' => 'Premi genera...',
        
        // -- FOOTER --
        'footer_dev_info' => 'Sviluppato da <strong>%s</strong>. Il codice è Open Source e adattabile a qualsiasi Ente/Ateneo liberamente, nei limiti della licenza CC BY-NC-SA 4.0 (obbligo di attribuzione, non commerciale, condivisione alle stesse condizioni).',
        'footer_disclaimer' => 'Distribuito "così com\'è" senza garanzie esplicite o implicite. Questo software è uno strumento di supporto amministrativo open-source e non sostituisce i dati ufficiali degli applicativi di ateneo.',
        'footer_credits_orig' => 'Basato sul progetto originale <strong>PTA-Tools</strong> di Vincenzo Oriti. Distribuito con licenza CC BY-NC-SA 4.0. <a href=\'https://github.com/VOriti/PTA-Tools\' target=\'_blank\' style=\'text-decoration:underline\'>Repository GitHub</a>.',
    ],

    'en' => [ // inizio traduzioni inglese / start English translations
        // -- GENERAL UI --
        'app_name' => $CONFIG_GENERAL['nome_app'],
        'home_title' => 'Main Dashboard',
        'back_dash' => 'Back to Dashboard',
        'copied' => 'Copied to clipboard!',
        
        // -- DASHBOARD CATEGORIES --
        'cat_time' => 'Time Management',
        'intro_time' => 'Tools for working hours calculation and overtime conversion.',
        'cat_account' => 'Accounting',
        'intro_account' => 'Utilities for VAT calculation and bank code verification.',
        'cat_office' => 'Office Utilities',
        'intro_office' => 'Tools for text cleaning, email lists and password generation.',
        
        // -- COMMON LABELS & BUTTONS --
        'lbl_from' => 'FROM',
        'lbl_to' => 'TO',
        'lbl_days' => 'Days',
        'lbl_hours' => 'Hrs',
        'lbl_mins' => 'Mins',
        'lbl_start_date' => 'Start Date',
        'lbl_end_date' => 'End Date',
        'lbl_today' => 'Today',
        'lbl_result' => 'Result',
        'calc' => 'Calculate',
        'verify' => 'Verify',
        'copy' => 'Copy',
        'clean' => 'Clean Text',
        'format' => 'Format',
        'generate' => 'Generate',
        'type_link' => 'Link',
        'type_group' => 'Group',
        'type_tool' => 'Tool',
        
        // -- CALENDAR / DAYS --
        'day_mon' => 'Monday',
        'day_tue' => 'Tuesday',
        'day_wed' => 'Wednesday',
        'day_thu' => 'Thursday',
        'day_fri' => 'Friday',
        'day_sat' => 'Saturday',
        'day_sun' => 'Sunday',
        
        // -- TOOL: WORK HOURS (Intervalli) --
        'tool_intervalli' => 'Work Hours Calc',
        'desc_short_intervalli' => 'Sum time intervals to calculate total work hours.',
        'desc_long_intervalli' => 'Manual calculation of working time by summing intervals.',
        'lbl_warning' => 'WARNING',
        'note_intervalli' => 'System automatically deducts 10 mins if lunch break is shorter, please take this into account.',
        
        // -- TOOL: OVERTIME CONVERTER --
        'tool_recuperi' => 'Overtime Converter',
        'desc_short_recuperi' => 'Convert overtime balance into days off and calculate remainder.',
        'desc_long_recuperi' => 'Calculate days off based on overtime balance.',
        'lbl_balance_hours' => 'Overtime Balance',
        'lbl_week_schedule' => 'Week Schedule',
        'res_recuperi_intro' => 'Starting from the selected day you could be away for <strong>%s</strong> days. Therefore:',
        'res_recuperi_period' => 'You can be away from %s until %s',
        'res_recuperi_rem' => 'Remaining balance:',
        'warn_recuperi_holidays' => 'Warning: calculation assumes all days Mon-Fri are working days. Please account for any holidays manually.',
        
        // -- TOOL: DEADLINE & DURATION --
        'tool_scadenza' => 'Deadline & Duration',
        'desc_short_scadenza' => 'Calculate end time from start and duration, or vice versa.',
        'desc_long_scadenza' => 'Calculate end time or start time based on duration.',
        'lbl_want_end' => 'I want to know when I FINISH',
        'lbl_want_start' => 'I want to know when to START',
        'lbl_ref_time' => 'Start/End Time',
        'lbl_date_opt' => 'Date (Optional)',
        'ph_dd' => 'DD',
        'ph_mm' => 'MM',
        'ph_yyyy' => 'YYYY',
        'ph_hh' => 'HH',
        'lbl_duration' => 'Activity Duration',
        'lbl_pause' => 'Total Break',
        'lbl_end_time' => 'End Time:',
        'lbl_start_time' => 'Start Time:',
        
        // -- TOOL: DATE DIFFERENCE --
        'tool_dates' => 'Date Difference',
        'desc_short_dates' => 'Calculate the years, months, and days between two dates.',
        'desc_long_dates' => 'Calculate exact interval (years, months, days) between dates.',
        'lbl_diff_dates' => 'Date Difference',
        'lbl_calc_working' => 'Working days only (Mon-Fri)',
        'lbl_add_days_date' => 'Add Days to Date',
        'lbl_days_to_add' => 'Days to add',
        'lbl_years' => 'Years',
        'lbl_months' => 'Months',
        'lbl_total_days' => 'Total days',
        'lbl_all_days' => 'counting all days',
        
        // -- TOOL: VAT MANAGER --
        'tool_iva' => 'VAT Manager',
        'desc_short_iva' => 'Add or remove VAT from a gross or net amount.',
        'desc_long_iva' => 'Extract or apply VAT rates.',
        'lbl_amount' => 'Amount (€)',
        'ph_amount' => 'e.g. 1220.00',
        'lbl_rate' => 'Rate',
        'rate_22' => '22% (Standard)',
        'rate_10' => '10% (Reduced)',
        'rate_4' => '4% (Minimum)',
        'lbl_other' => 'Other (custom)',
        'lbl_custom_rate' => 'Custom Rate (%)',
        'lbl_op' => 'Operation',
        'lbl_op_scorpora' => 'Unbundle (Gross -> Net)',
        'lbl_op_add' => 'Apply (Net -> Gross)',
        'lbl_op_calc' => 'VAT Calculation Only',
        'lbl_net' => 'Net',
        'lbl_vat' => 'VAT',
        'lbl_gross' => 'Gross',
        
        // -- TOOL: IBAN VALIDATOR --
        'tool_iban' => 'IBAN Validator',
        'desc_short_iban' => 'Check the formal validity of a national or international IBAN code.',
        'desc_long_iban' => 'Formal validation of IBAN codes.',
        'lbl_iban_code' => 'IBAN Code',
        'msg_iban_ok' => 'IBAN is VALID',
        'msg_iban_ko' => 'ERROR: Invalid IBAN',
        
        // -- TOOL: TEXT SANITIZER --
        'tool_text' => 'Text Sanitizer',
        'desc_short_text' => 'Fix capitalization, spacing, and line breaks for clean text.',
        'desc_long_text' => 'Clean text from PDFs, fix caps and spacing.',
        'lbl_input_text' => 'Input Text',
        'opt_oneline' => 'Remove Newlines',
        'opt_spaces' => 'Remove Double Spaces',
        'opt_title' => 'Title Case (Mario Rossi)',
        'opt_upper' => 'UPPERCASE',
        'opt_lower' => 'lowercase',
        
        // -- TOOL: EMAIL LIST --
        'tool_email' => 'Email List Formatter',
        'desc_short_email' => 'Turn a list of emails into a single line for mail clients.',
        'desc_long_email' => 'Convert Excel columns to Outlook/Gmail lists.',
        'lbl_input_list' => 'Input List (Excel Column)',
        'ph_email_list' => 'john.doe@unipv.it&#10;jane.smith@unipv.it',
        'lbl_separator' => 'Separator',
        'opt_comma' => ', (Gmail)',
        'opt_semicolon' => '; (Outlook)',
        
        // -- TOOL: PASSWORD GENERATOR --
        'tool_pass' => 'Password Generator',
        'desc_short_pass' => 'Create secure passwords that are easy to dictate and remember.',
        'desc_long_pass' => 'Create readable secure passwords.',
        'msg_press_generate' => 'Press generate...',
        
        // -- FOOTER --
        'footer_dev_info' => 'Developed by <strong>%s</strong>. The code is Open Source and freely adaptable to any Institution/University, within the limits of the CC BY-NC-SA 4.0 license (Attribution, NonCommercial, ShareAlike).',
        'footer_disclaimer' => 'Provided "as is" without warranty of any kind. This software is an open-source administrative aid and does not replace official university records.',
        'footer_credits_orig' => 'Based on the original project <strong>PTA-Tools</strong> by Vincenzo Oriti. Distributed under CC BY-NC-SA 4.0 license. <a href=\'https://github.com/VOriti/PTA-Tools\' target=\'_blank\' style=\'text-decoration:underline\'>GitHub Repository</a>.',
    ],

    // ---------------------------------------------------------
    // AGGIUNGI QUI NUOVE LINGUE / ADD NEW LANGUAGES HERE
    // ---------------------------------------------------------
    // Esempio / Example:
    // 'fr' => [
    //     'app_name' => $CONFIG_GENERAL['nome_app'],
    //     ... copia le chiavi da 'it' o 'en' e traduci ...
    //     ... copy keys from 'it' or 'en' and translate ...
    // ]
];

// ---------------------------------------------------------
// SEZIONE 3: IMPOSTAZIONI DI RUNTIME / RUNTIME SETTINGS
// ---------------------------------------------------------

// APPLICAZIONE CONFIGURAZIONE / APPLY CONFIGURATION
// Disabilita la visualizzazione degli errori per l'ambiente di produzione.
// Disable error display for production environment.
error_reporting(E_ALL);
ini_set('display_errors', $CONFIG_GENERAL['debug_mode'] ? 1 : 0);

// Gestione Lingua: Rileva la lingua dal parametro GET o dalla sessione.
// Language Management: Detects language from GET parameter or session.
$lingua = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : $CONFIG_GENERAL['lingua_default']);

// Validazione: Assicura che la lingua esista nella configurazione, altrimenti usa default
// Validation: Ensure language exists in configuration, otherwise use default
if (!array_key_exists($lingua, $CONFIG_TRANSLATIONS)) {
    $lingua = $CONFIG_GENERAL['lingua_default'];
}
$_SESSION['lang'] = $lingua;

// ---------------------------------------------------------
// SEZIONE 4: LOGICA INTEGRAZIONE LINK (NON MODIFICARE) / LINK INTEGRATION LOGIC (DO NOT MODIFY)
// ---------------------------------------------------------
// Questa parte elabora la configurazione definita in SEZIONE 1.3/1.4 e aggiorna le traduzioni
// This part processes the configuration defined in SECTION 1.3/1.4 and updates translations

// Recupera le lingue disponibili dal dizionario traduzioni
// Retrieve available languages from translation dictionary
$lingue_disponibili = array_keys($CONFIG_TRANSLATIONS);

// Elaborazione Layout / Layout Processing
if (isset($CONFIG_LINKS_LAYOUT)) {
    $conf = $CONFIG_LINKS_LAYOUT;
    
    // Mappa chiavi configurazione -> chiavi traduzione
    // Map configuration keys -> translation keys
    $layout_map = [
        'separator_featured' => 'links_separator_featured',
        'separator_other'    => 'links_separator_other',
        'main_title_links'   => 'cat_links',
        'main_intro_links'   => 'intro_links',
        'main_title_tools'   => 'sect_tools',
        'main_intro_tools'   => 'intro_tools'
    ];

    foreach ($layout_map as $conf_key => $trans_key) {
        if (isset($conf[$conf_key])) {
            foreach ($lingue_disponibili as $lang) {
                if (isset($conf[$conf_key][$lang])) {
                    $CONFIG_TRANSLATIONS[$lang][$trans_key] = $conf[$conf_key][$lang];
                }
            }
        }
    }
}

$LINKS_ITEMS_PROCESSED = [];
foreach($CONFIG_LINKS_ITEMS as $id => $conf) {
    // Generazione chiavi univoche per le traduzioni
    // Generating unique keys for translations
    $key_titolo = "link_custom_{$id}_titolo";
    $key_desc = "link_custom_{$id}_desc";
    
    // Inserimento dinamico nel dizionario traduzioni
    // Dynamic insertion into translation dictionary
    foreach($lingue_disponibili as $lang) {
        if(isset($conf['testi'][$lang])) {
            $CONFIG_TRANSLATIONS[$lang][$key_titolo] = $conf['testi'][$lang]['titolo'];
            $CONFIG_TRANSLATIONS[$lang][$key_desc] = $conf['testi'][$lang]['desc'];
        }
    }
    
    // Costruzione Item Catalogo
    // Building Catalog Item
    if ($conf['tipo'] === 'gruppo') {
        $processed_sublinks = [];
        if (isset($conf['sottolink']) && is_array($conf['sottolink'])) {
            foreach($conf['sottolink'] as $sub_id => $sub_conf) {
                $key_sub_titolo = "link_custom_{$id}_sub_{$sub_id}_titolo";
                $key_sub_desc = "link_custom_{$id}_sub_{$sub_id}_desc";
                
                foreach($lingue_disponibili as $lang) {
                    if(isset($sub_conf['testi'][$lang])) {
                        $CONFIG_TRANSLATIONS[$lang][$key_sub_titolo] = $sub_conf['testi'][$lang]['titolo'];
                        $CONFIG_TRANSLATIONS[$lang][$key_sub_desc] = $sub_conf['testi'][$lang]['desc'];
                    }
                }
                $processed_sublinks[] = ['url' => $sub_conf['url'], 'key_titolo' => $key_sub_titolo, 'key_desc' => $key_sub_desc, 'featured' => $sub_conf['featured'] ?? false, 'full_width' => $sub_conf['full_width'] ?? false];
            }
        }

        $LINKS_ITEMS_PROCESSED[$id] = [
            'type' => 'link_group',
            'key' => $key_titolo,
            'desc_short' => $key_desc,
            'func' => 'visualizza_gruppo_link',
            'links' => $processed_sublinks
        ];
    } else {
        $LINKS_ITEMS_PROCESSED[$id] = [
            'type' => 'direct_link',
            'key' => $key_titolo,
            'desc_short' => $key_desc,
            'url' => $conf['url'],
            'featured' => $conf['featured'] ?? false
        ];
    }
}

// ---------------------------------------------------------
// SEZIONE 5: FUNZIONI DI SUPPORTO / HELPER FUNCTIONS
// ---------------------------------------------------------

/**
 * Recupera una stringa tradotta per una data chiave.
 * Cerca la chiave nell'array globale delle traduzioni per la lingua corrente.
 * Se la traduzione non viene trovata, restituisce la chiave stessa come fallback.
 * 
 * Retrieves a translated string for a given key.
 * It looks for the key in the global translation array for the current language.
 * If the translation is not found, it returns the key itself as a fallback.
 *
 * @param string $chiave La chiave per la stringa di traduzione. / The key for the translation string.
 * @return string La stringa tradotta o la chiave stessa. / The translated string or the key itself.
 */
function traduci($chiave) { 
    global $CONFIG_TRANSLATIONS, $lingua; 
    return $CONFIG_TRANSLATIONS[$lingua][$chiave] ?? $chiave; 
}

/**
 * Costruisce un URL con parametri di query per la selezione della lingua e dello strumento.
 * Preserva la lingua corrente se non ne viene specificata una nuova.
 * 
 * Builds a URL with query parameters for language and tool selection.
 * It preserves the current language if a new one is not specified.
 *
 * @param string|null $strumento L'ID dello strumento da includere nell'URL. / The tool ID to include in the URL.
 * @param string|null $nuova_lingua Il nuovo codice lingua (es. 'it', 'en'). / The new language code (e.g., 'it', 'en').
 * @return string La stringa URL generata. / The generated URL string.
 */
function ottieniUrl($strumento = null, $nuova_lingua = null) { 
    global $lingua; 
    $l = $nuova_lingua ?? $lingua;
    return "?lang=$l" . ($strumento ? "&tool=$strumento" : ""); 
}

// ---------------------------------------------------------
// SEZIONE 6: CONFIGURAZIONE CATALOGO STRUMENTI / TOOLS CATALOG CONFIGURATION
// ---------------------------------------------------------

$CONFIG_TOOLS_CATALOG = [
    'links' => [
        'label_key' => 'cat_links', 
        'intro_key' => 'intro_links', 
        'icon' => '🏛️',
        'separator_featured_key' => 'links_separator_featured',
        'separator_other_key' => 'links_separator_other',
        'items' => $LINKS_ITEMS_PROCESSED
    ],
    'time' => [
        'label_key' => 'cat_time', 
        'intro_key' => 'intro_time', 
        'icon' => '⏱️',
        'items' => [
            'intervalli' => ['type' => 'tool', 'key' => 'tool_intervalli', 'desc_short' => 'desc_short_intervalli', 'desc_long' => 'desc_long_intervalli', 'func' => 'visualizza_intervalli', 'proc' => 'processa_intervalli'],
            'recuperi'   => ['type' => 'tool', 'key' => 'tool_recuperi',   'desc_short' => 'desc_short_recuperi',   'desc_long' => 'desc_long_recuperi',   'func' => 'visualizza_recuperi',   'proc' => 'processa_recuperi'],
            'scadenza'   => ['type' => 'tool', 'key' => 'tool_scadenza',   'desc_short' => 'desc_short_scadenza',   'desc_long' => 'desc_long_scadenza',   'func' => 'visualizza_scadenza',   'proc' => 'processa_scadenza'],
            'date'      => ['type' => 'tool', 'key' => 'tool_dates',      'desc_short' => 'desc_short_dates',      'desc_long' => 'desc_long_dates',      'func' => 'visualizza_date',      'proc' => 'processa_date'],
        ]
    ],
    'account' => [
        'label_key' => 'cat_account', 
        'intro_key' => 'intro_account', 
        'icon' => '💶',
        'items' => [
            'iva'  => ['type' => 'tool', 'key' => 'tool_iva',  'desc_short' => 'desc_short_iva',  'desc_long' => 'desc_long_iva',  'func' => 'visualizza_iva',  'proc' => 'processa_iva'],
            'iban' => ['type' => 'tool', 'key' => 'tool_iban', 'desc_short' => 'desc_short_iban', 'desc_long' => 'desc_long_iban', 'func' => 'visualizza_iban', 'proc' => 'processa_iban'],
        ]
    ],
    'office' => [
        'label_key' => 'cat_office', 
        'intro_key' => 'intro_office', 
        'icon' => '📝',
        'items' => [
            'text'  => ['type' => 'tool', 'key' => 'tool_text', 'desc_short' => 'desc_short_text', 'desc_long' => 'desc_long_text', 'func' => 'visualizza_testo', 'proc' => 'processa_testo'],
            'email' => ['type' => 'tool', 'key' => 'tool_email','desc_short' => 'desc_short_email','desc_long' => 'desc_long_email','func' => 'visualizza_email', 'proc' => 'processa_email'],
            'pass'  => ['type' => 'tool', 'key' => 'tool_pass', 'desc_short' => 'desc_short_pass', 'desc_long' => 'desc_long_pass', 'func' => 'visualizza_password', 'proc' => 'processa_password'],
        ]
    ]
];

// Determina lo strumento corrente dall'URL, sanificando l'input.
// Determine current tool from URL, sanitizing the input.
$id_strumento_corrente = isset($_GET['tool']) ? htmlspecialchars($_GET['tool']) : null;
$info_strumento_corrente = null;

// Trova le informazioni dello strumento corrente nel catalogo.
// Find the current tool's information in the catalog.
foreach($CONFIG_TOOLS_CATALOG as $categoria) {
    if(isset($categoria['items'][$id_strumento_corrente])) { 
        $info_strumento_corrente = $categoria['items'][$id_strumento_corrente]; 
        break; 
    }
}

// Caso speciale per la pagina dedicata ai link
// Special case for the dedicated links page
if ($id_strumento_corrente === 'link_page') {
    $info_strumento_corrente = [
        'key' => 'cat_links',
        'func' => 'visualizza_pagina_link'
    ];
}

// ---------------------------------------------------------
// SEZIONE 7: LOGICA BACKEND (ROUTER ELABORAZIONE FORM) / BACKEND LOGIC (FORM PROCESSING ROUTER)
// ---------------------------------------------------------

$dati_risultato = null;

// Elabora i dati del form solo se il metodo di richiesta è POST.
// Process form data only if the request method is POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica Sicurezza CSRF / CSRF Security Check
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Errore di sicurezza: Token CSRF non valido o scaduto. Ricarica la pagina.");
    }

    $azione = $_POST['action'] ?? '';

    // Trova la funzione di elaborazione dal catalogo in base all'azione inviata.
    // Find the processing function from the catalog based on the submitted action.
    $funzione_processore = null;
    foreach($CONFIG_TOOLS_CATALOG as $categoria) {
        if(isset($categoria['items'][$azione])) {
            $funzione_processore = $categoria['items'][$azione]['proc'] ?? null;
            break;
        }
    }

    // Se viene trovata una funzione di elaborazione valida, chiamala per elaborare i dati.
    // If a valid processor function is found, call it to process the data.
    if ($funzione_processore && function_exists($funzione_processore)) {
        $dati_risultato = call_user_func($funzione_processore);
    }
}

// ==========================================
// SEZIONE 8: FUNZIONI DI ELABORAZIONE / PROCESSOR FUNCTIONS
// ==========================================

/**
 * Elabora l'invio del form "Calcolo Ore Lavorate".
 * Somma intervalli di tempo multipli.
 * 
 * Processes the "Work Hours Calc" form submission.
 * It sums multiple time intervals.
 * 
 * @return array Un array con il tempo totale calcolato in formato HH:MM e una stringa descrittiva. / An array with the calculated total time in HH:MM format and a descriptive string.
 */
function processa_intervalli() {
    $tot_secondi = 0;
    if (isset($_POST['h_start'])) {
        for ($i = 0; $i < count($_POST['h_start']); $i++) {
            $ora_inizio = (int)$_POST['h_start'][$i]; $min_inizio = (int)$_POST['m_start'][$i];
            $ora_fine = (int)$_POST['h_end'][$i];   $min_fine = (int)$_POST['m_end'][$i];
            
            $inizio = mktime($ora_inizio, $min_inizio, 0, 1, 1, 2000);
            $fine   = mktime($ora_fine, $min_fine, 0, 1, 1, 2000);
            
            // Gestisce intervalli notturni (fine < inizio)
            // Handle overnight intervals
            if ($fine < $inizio) $fine = mktime($ora_fine, $min_fine, 0, 1, 2, 2000); 
            $tot_secondi += ($fine - $inizio);
        }
    }
    $ore_risultato = floor($tot_secondi / 3600); 
    $min_risultato = floor(($tot_secondi / 60) % 60);
    return ['main' => sprintf("%02d:%02d", $ore_risultato, $min_risultato), 'sub' => "$ore_risultato h $min_risultato min"];
}

/**
 * Elabora l'invio del form "Convertitore Recuperi".
 * Calcola quanti giorni lavorativi completi possono essere coperti da un saldo straordinari.
 * 
 * Processes the "Overtime Converter" form submission.
 * It calculates how many full work days can be covered by an overtime balance.
 * 
 * @return array Un array con la data di fine del congedo e il tempo rimanente. / An array with the end date of leave and the remaining time.
 */
function processa_recuperi() {
    global $lingua;
    $saldo_minuti = ((int)$_POST['saldo_h'] * 60) + (int)$_POST['saldo_m'];
    $orario_settimanale = [];
    for($giorno=1; $giorno<=5; $giorno++) {
        list($ore_giorno, $min_giorno) = explode(':', $_POST["day_$giorno"]);
        $orario_settimanale[$giorno] = ($ore_giorno * 60) + $min_giorno;
    }

    $giorni_coperti = 0;

    try {
        $data = new DateTime($_POST['start_date']);
        $data_inizio = clone $data;
        $data_finale = clone $data; // Conterrà l'ultimo giorno coperto / Will hold the last covered day
        
        while ($saldo_minuti > 0) {
            $giorno_settimana = $data->format('N'); // 1 (per Lunedì) a 7 (per Domenica) / 1 (for Monday) through 7 (for Sunday)
            if ($giorno_settimana >= 6) { // Salta i weekend / Skip weekends
                $data->modify('+1 day');
                continue;
            }
            $richiesto = $orario_settimanale[$giorno_settimana];
            
            if ($saldo_minuti >= $richiesto) {
                $saldo_minuti -= $richiesto;
                $giorni_coperti++;
                $data_finale = clone $data;
                $data->modify('+1 day');
            } else {
                break; // Saldo insufficiente per un altro giorno intero / Not enough balance for another full day
            }
        }
        $ore_rimanenti = floor($saldo_minuti / 60);
        $min_rimanenti = $saldo_minuti % 60;
        
        $map = [
            1 => traduci('day_mon'),
            2 => traduci('day_tue'),
            3 => traduci('day_wed'),
            4 => traduci('day_thu'),
            5 => traduci('day_fri'),
            6 => traduci('day_sat'),
            7 => traduci('day_sun')
        ];

        return [
            'type' => 'recuperi',
            'days' => $giorni_coperti,
            'start' => $map[$data_inizio->format('N')] . ' ' . $data_inizio->format('d/m/Y'),
            'end' => $map[$data_finale->format('N')] . ' ' . $data_finale->format('d/m/Y'),
            'resto' => sprintf("%02d h %02d min", $ore_rimanenti, $min_rimanenti)
        ];
    } catch (Exception $e) { return ['error' => 'Invalid Date']; }
}

/**
 * Elabora l'invio del form "Scadenza e Durata".
 * Calcola un orario di fine da un orario di inizio e durata, o viceversa.
 * 
 * Processes the "Deadline & Duration" form submission.
 * It calculates an end time from a start time and duration, or vice-versa.
 * 
 * @return array Un array con data/ora calcolati. / An array with the calculated date/time.
 */
function processa_scadenza() {
    try {
        $data_base = new DateTime();
        $data_base->setTime((int)$_POST['start_h'], (int)$_POST['start_m']);
        if(!empty($_POST['date_d'])) {
            $data_base->setDate((int)$_POST['date_y'], (int)$_POST['date_m'], (int)$_POST['date_d']);
        }
        
        $intervallo_durata_str = "PT" . (int)$_POST['dur_h'] . "H" . (int)$_POST['dur_m'] . "M";
        $intervallo_pausa_str = "PT" . (int)$_POST['pau_h'] . "H" . (int)$_POST['pau_m'] . "M";
        
        $durata = new DateInterval($intervallo_durata_str);
        $pausa = new DateInterval($intervallo_pausa_str);
        
        if ($_POST['calc_mode'] == 'end') {
            $data_base->add($durata)->add($pausa);
            $etichetta = traduci('lbl_end_time');
        } else {
            $data_base->sub($durata)->sub($pausa);
            $etichetta = traduci('lbl_start_time');
        }
        return ['main' => $data_base->format('H:i'), 'sub' => $etichetta . " " . $data_base->format('d/m/Y')];
    } catch (Exception $e) { return ['main' => 'Error']; }
}

/**
 * Elabora l'invio del form "Differenza Date".
 * Calcola l'intervallo tra due date.
 * 
 * Processes the "Date Difference" form submission.
 * It calculates the interval between two dates.
 * 
 * @return array Un array con la differenza calcolata in anni, mesi, giorni e giorni totali. / An array with the calculated difference in years, months, days, and total days.
 */
function processa_date() {
    global $lingua;
    $mode = $_POST['mode'] ?? 'diff';

    $map = [
        1 => traduci('day_mon'),
        2 => traduci('day_tue'),
        3 => traduci('day_wed'),
        4 => traduci('day_thu'),
        5 => traduci('day_fri'),
        6 => traduci('day_sat'),
        7 => traduci('day_sun')
    ];

    if ($mode === 'add') {
        try {
            $start = new DateTime($_POST['d_start']);
            $days = (int)$_POST['days_add'];
            $working = isset($_POST['only_working_add']);
            
            $end = clone $start;
            if ($working) {
                for($i=0; $i<$days; $i++) {
                    $end->modify('+1 day');
                    while($end->format('N') >= 6) $end->modify('+1 day');
                }
                $desc = traduci('lbl_calc_working');
            } else {
                $end->modify("+$days days");
                $desc = traduci('lbl_all_days');
            }
            return ['main' => $end->format('d/m/Y'), 'sub' => $map[$end->format('N')] . " ($desc)"];
        } catch(Exception $e) { return ['main' => 'Error']; }
    } else {
        try {
            $data1 = new DateTime($_POST['d1']);
            $data2 = new DateTime($_POST['d2']);
            
            if (isset($_POST['only_working_diff'])) {
                if ($data1 > $data2) { $t=$data1; $data1=$data2; $data2=$t; }
                $days = 0;
                $period = new DatePeriod($data1, new DateInterval('P1D'), $data2);
                foreach($period as $dt) { if ($dt->format('N') < 6) $days++; }
                return ['main' => $days . " " . traduci('lbl_days'), 'sub' => traduci('lbl_calc_working')];
            }
            $diff = $data1->diff($data2);
            return ['main' => $diff->y." ".traduci('lbl_years').", ".$diff->m." ".traduci('lbl_months').", ".$diff->d." ".traduci('lbl_days'), 'sub' => traduci('lbl_total_days').": " . $diff->days];
        } catch(Exception $e) { return ['main' => 'Error']; }
    }
}
/**
 * Elabora l'invio del form "Gestione IVA".
 * Può aggiungere, rimuovere o semplicemente calcolare l'IVA su un determinato importo.
 * 
 * Processes the "VAT Manager" form submission.
 * It can add, remove, or just calculate VAT on a given amount.
 * 
 * @return array Un array contenente il risultato come stringa HTML. / An array containing the result as an HTML string.
 */
function processa_iva() {
    $importo = floatval(str_replace(',', '.', $_POST['importo']));
    $aliquota = ($_POST['aliquota'] == 'other') ? floatval($_POST['aliquota_other']) : floatval($_POST['aliquota']);
    $operazione = $_POST['operazione'];
    
    if($operazione == 'scorporo') { // Scorpora IVA dall'importo lordo / Unbundle VAT from gross amount
        $imponibile_netto = $importo / (1 + ($aliquota/100)); 
        $iva_valore = $importo - $imponibile_netto; 
        $totale = $importo; 
    } elseif ($operazione == 'add') { // Aggiunge IVA all'importo netto / Add VAT to net amount
        $imponibile_netto = $importo; 
        $iva_valore = $importo * ($aliquota/100); 
        $totale = $importo + $iva_valore; 
    } else { // Solo calcolo IVA / Calculate VAT only
        $imponibile_netto = $importo; 
        $iva_valore = $importo * ($aliquota/100); 
        $totale = 0; // Il totale non è rilevante qui / Total is not relevant here
    }
    
    return ['html' => "<div style='display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; text-align:center;'>
        <div><small>".traduci('lbl_net')."</small><div style='font-weight:bold'>€ ".number_format($imponibile_netto,2,',','.')."</div></div>
        <div><small>".traduci('lbl_vat')."</small><div style='font-weight:bold; color:#d97706'>€ ".number_format($iva_valore,2,',','.')."</div></div>
        <div><small>".traduci('lbl_gross')."</small><div style='font-weight:bold; color:#059669'>€ ".number_format($totale,2,',','.')."</div></div></div>"];
}

/**
 * Elabora l'invio del form "Verifica IBAN".
 * Esegue un controllo matematico sulla struttura dell'IBAN.
 * 
 * Processes the "IBAN Validator" form submission.
 * It performs a mathematical check on the IBAN's structure.
 * 
 * @return array Un array con il messaggio del risultato della validazione e un codice colore. / An array with the validation result message and a color code.
 */
function processa_iban() {
    $iban = strtoupper(str_replace(' ', '', $_POST['iban']));
    $controllo = substr($iban, 4) . substr($iban, 0, 4);
    $iban_numerico = '';
    foreach (str_split($controllo) as $carattere) {
        $iban_numerico .= is_numeric($carattere) ? $carattere : (ord($carattere) - 55);
    }
    $resto = '0';
    for ($i = 0; $i < strlen($iban_numerico); $i++) {
        $resto = ($resto . $iban_numerico[$i]) % 97;
    }
    
    $e_valido = ($resto == 1 && strlen($iban) >= 15 && strlen($iban) <= 34);
    return [
        'main' => $e_valido ? traduci('msg_iban_ok') : traduci('msg_iban_ko'), 
        'color' => $e_valido ? 'green' : 'red'
    ];
}

/**
 * Elabora l'invio del form "Sanificatore Testo".
 * Pulisce e riformatta una data stringa di testo.
 * 
 * Processes the "Text Sanitizer" form submission.
 * It cleans and reformats a given text string.
 * 
 * @return array Un array con il testo sanificato. / An array with the sanitized text.
 */
function processa_testo() {
    $testo_input = $_POST['text_in'];
    $operazione = $_POST['text_op'];
    
    if ($operazione == 'title') $testo_output = mb_convert_case($testo_input, MB_CASE_TITLE, "UTF-8");
    elseif ($operazione == 'upper') $testo_output = mb_strtoupper($testo_input, "UTF-8");
    elseif ($operazione == 'lower') $testo_output = mb_strtolower($testo_input, "UTF-8");
    elseif ($operazione == 'oneline') $testo_output = str_replace(["\r", "\n"], ' ', $testo_input);
    
    // Rimuove spazi multipli
    // Remove multiple spaces
    $testo_output = preg_replace('/\s+/', ' ', $testo_output ?? $testo_input);
    return ['raw' => trim($testo_output)];
}

/**
 * Elabora l'invio del form "Lista Email".
 * Converte una lista di email (una per riga) in una singola stringa basata su separatore.
 * 
 * Processes the "Email List Formatter" form submission.
 * It converts a list of emails (one per line) into a single, separator-based string.
 * 
 * @return array Un array con la lista email formattata. / An array with the formatted email list.
 */
function processa_email() {
    $lista_grezza = $_POST['email_list'];
    $separatore = ($_POST['separator'] == 'semicolon') ? '; ' : ', ';
    $righe = preg_split("/\r\n|\n|\r/", $lista_grezza);
    $lista_pulita = [];
    foreach($righe as $riga) {
        $riga = trim($riga);
        if(!empty($riga)) $lista_pulita[] = $riga;
    }
    return ['raw' => implode($separatore, $lista_pulita)];
}

/**
 * Elabora l'invio del form "Generatore Password".
 * Crea una password pronunciabile, ma sicura.
 * 
 * Processes the "Password Generator" form submission.
 * It creates a pronounceable, yet secure, password.
 * 
 * @return array Un array con la password generata. / An array with the generated password.
 */
function processa_password() {
    $sillabe = [
        "ba","be","bi","bo","bu","ca","ce","ci","co","cu","da","de","di","do","du","fa","fe","fi","fo","fu",
        "ga","ge","gi","go","gu","ka","ke","ki","ko","ku","la","le","li","lo","lu","ma","me","mi","mo","mu",
        "na","ne","ni","no","nu","pa","pe","pi","po","pu","ra","re","ri","ro","ru","sa","se","si","so","su",
        "ta","te","ti","to","tu","va","ve","vi","vo","vu","za","ze","zi","zo","zu"
    ];
    $numeri = [2,3,4,5,6,7,8,9];
    $simboli = ['@','#','!','$','?'];
    
    // Parola pronunciabile (3 sillabe) + Numeri + Simbolo alla fine per leggibilità
    // Pronounceable word (3 syllables) + Numbers + Symbol at the end for readability
    $password = ucfirst($sillabe[array_rand($sillabe)]) . $sillabe[array_rand($sillabe)] . $sillabe[array_rand($sillabe)] . 
                $numeri[array_rand($numeri)] . $numeri[array_rand($numeri)] . 
                $simboli[array_rand($simboli)];
    return ['raw' => $password];
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lingua; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo traduci('app_name'); ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234338ca' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='<?php echo $CONFIG_GENERAL['icona_svg_path']; ?>'/></svg>">
    <link href="<?php echo $CONFIG_THEME['font_url']; ?>" rel="stylesheet">
    <style>
        /* VARIABILI CSS & RESET / CSS VARIABLES & RESET */
        :root {
            --primary: <?php echo $CONFIG_THEME['primary']; ?>; 
            --primary-soft: <?php echo $CONFIG_THEME['primary_soft']; ?>;
            --bg-body: <?php echo $CONFIG_THEME['bg_body']; ?>;
            --bg-card: <?php echo $CONFIG_THEME['bg_card']; ?>;
            --text-main: <?php echo $CONFIG_THEME['text_main']; ?>;
            --text-sub: <?php echo $CONFIG_THEME['text_sub']; ?>;
            --sidebar-w: <?php echo $CONFIG_THEME['sidebar_width']; ?>;
            --radius: <?php echo $CONFIG_THEME['radius']; ?>;
            --font-main: <?php echo $CONFIG_THEME['font_family']; ?>;
        }
        body { font-family: var(--font-main); background: var(--bg-body); color: var(--text-main); margin: 0; display: flex; min-height: 100vh; }
        * { box-sizing: border-box; }
        a { text-decoration: none; color: inherit; }

        /* STILI SIDEBAR / SIDEBAR STYLES */
        .sidebar { width: var(--sidebar-w); background: white; border-right: 1px solid #e5e7eb; position: fixed; height: 100%; z-index: 50; transition: 0.3s; display: flex; flex-direction: column; }
        .logo-area { height: 60px; display: flex; align-items: center; padding: 0 20px; font-weight: 800; font-size: 20px; color: var(--primary); border-bottom: 1px solid #e5e7eb; }
        .nav-scroll { flex: 1; overflow-y: auto; padding: 20px 0; }
        
        .cat-header { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--primary); font-weight: 800; padding: 8px 20px; margin-top: 15px; margin-bottom: 5px; background: linear-gradient(90deg, var(--primary-soft) 0%, transparent 100%); }
        .nav-item { display: flex; align-items: center; padding: 10px 20px; font-size: 14px; font-weight: 500; color: #374151; border-left: 3px solid transparent; }
        .nav-item:hover { background: #f9fafb; color: var(--primary); }
        .nav-item.active { background: var(--primary-soft); color: var(--primary); border-left-color: var(--primary); font-weight: 600; }
        .back-link { font-weight: 600; color: var(--primary); border-bottom: 1px solid #eee; margin-bottom: 10px; }

        /* AREA CONTENUTO PRINCIPALE / MAIN CONTENT AREA */
        .main-content { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; width: 100%; transition: 0.3s; }
        
        /* INTESTAZIONE (DESKTOP & MOBILE) / HEADER (DESKTOP & MOBILE) */
        .top-bar { height: 60px; background: white; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; position: sticky; top: 0; z-index: 40; }
        .mobile-header { display: none; background: white; height: 60px; padding: 0 15px; align-items: center; justify-content: space-between; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 40; }
        .burger-btn { background: none; border: none; font-size: 24px; cursor: pointer; }
        
        .lang-switch a { margin-left: 10px; font-size: 20px; opacity: 0.5; transition: 0.2s; }
        .lang-switch a.active { opacity: 1; transform: scale(1.1); }

        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; width: 100%; }
        
        /* COMPONENTI UI / UI COMPONENTS */
        .card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 25px; margin-bottom: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .tool-title { font-size: 20px; font-weight: 700; margin-bottom: 10px; color: var(--text-main); }
        .tool-desc { color: var(--text-sub); font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        
        /* GRIGLIA DASHBOARD / DASHBOARD GRID */
        .dash-section { margin-bottom: 40px; }
        .dash-sec-title { font-size: 18px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 10px; margin-bottom: 5px; }
        .dash-sec-intro { font-size: 14px; color: var(--text-sub); margin-bottom: 20px; max-width: 700px; }
        .sub-cat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .link-card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; transition: 0.2s; cursor: pointer; display: block; position: relative; }
        .link-card:hover { border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .type-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            background: #f3f4f6;
            padding: 3px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            border: 1px solid #e5e7eb;
            z-index: 10;
        }
        .lc-head { font-weight: 600; color: var(--primary); margin-bottom: 5px; padding-right: 60px; }
        .lc-desc { font-size: 12px; color: var(--text-sub); }

        /* FORM & INPUT / FORMS & INPUTS */
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #374151; }
        input[type="text"], input[type="number"], input[type="date"], select, textarea { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; outline: none; font-family: inherit; font-size: 14px; }
        input:focus, textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1); }
        
        .tab-group { display: flex; gap: 10px; margin-bottom: 20px; background: #f3f4f6; padding: 4px; border-radius: 8px; }
        .tab-radio { display: none; }
        .tab-label { flex: 1; text-align: center; padding: 10px; cursor: pointer; border-radius: 6px; font-size: 14px; font-weight: 500; color: #6b7280; transition: 0.2s; }
        .tab-radio:checked + .tab-label { background: white; color: var(--primary); font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }

        .btn { background: var(--primary); color: white; border: none; padding: 12px; border-radius: 6px; width: 100%; font-weight: 600; cursor: pointer; margin-top: 15px; }
        .btn:hover { background: #3730a3; }
        
        .result-box { background: #ecfdf5; border: 1px solid #d1fae5; border-radius: 8px; padding: 20px; text-align: center; margin-top: 20px; }
        .res-main { font-size: 24px; font-weight: 800; color: #065f46; margin: 5px 0; }
        .res-raw { background: #f9fafb; padding: 15px; border-radius: 6px; font-family: monospace; word-break: break-all; margin-top: 5px; }
        
        .featured-card {
            grid-column: 1 / -1; /* Occupa l'intera larghezza della griglia / Make it span the full width of the grid */
            background-color: #f4f4f7;
            border: 1px solid #d1d5db;
            border-left: 6px solid var(--primary);
            text-align: center;
            padding: 25px 20px;
            transition: all 0.2s ease-in-out;
        }
        .featured-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.07);
            border-color: var(--primary);
        }
        .featured-card .lc-head {
            color: var(--primary);
            font-size: 1.25em;
            font-weight: 600;
            padding-right: 0;
        }
        .full-width-card { grid-column: 1 / -1; }
        .separator-text {
            color: #6b7280;
            font-size: 0.9em;
            text-align: center;
            margin: 30px 0 20px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }
        
        .main-section-title { font-size: 26px; font-weight: 800; color: var(--text-main); margin: 50px 0 15px 0; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb; }
        .main-section-desc { color: var(--text-sub); font-size: 16px; margin-bottom: 30px; line-height: 1.6; max-width: 800px; }

        .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 45; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .top-bar { display: none; }
            .mobile-header { display: flex; }
            .overlay.active { display: block; }
        }

        /* STILI FOOTER / FOOTER STYLES */
        .main-content {
            /* Aggiungi questo padding per evitare che il contenuto finisca sotto il footer fisso */
            /* Add this padding to prevent content from going under the fixed footer */
            padding-bottom: 140px; 
            position: relative;
        }

        .app-footer {
            position: fixed;
            bottom: 0;
            right: 0;
            /* La larghezza si adatta se c'è la sidebar o meno (desktop/mobile) */
            /* Width adapts if sidebar is present or not (desktop/mobile) */
            width: 100%; 
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 15px 30px;
            z-index: 30;
            font-size: 13px;
            color: var(--text-sub);
            transition: 0.3s;
        }

        /* Adattamento Desktop: sposta il footer a destra della sidebar */
        /* Desktop Adaptation: moves footer to the right of the sidebar */
        @media (min-width: 769px) {
            .app-footer {
                width: calc(100% - var(--sidebar-w));
            }
        }

        .footer-container {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-section { font-weight: 600; color: var(--text-main); display: flex; align-items: center; gap: 8px; }
        .brand-x { opacity: 0.5; font-weight: 400; font-size: 0.9em; }
        .brand-uni { color: var(--primary); font-weight: 700; background: var(--primary-soft); padding: 2px 6px; border-radius: 4px; font-size: 0.9em; }

        .footer-links { display: flex; gap: 15px; align-items: center; }
        .footer-links a { display: flex; align-items: center; gap: 5px; transition: 0.2s; }
        .footer-links a:hover { color: var(--primary); }

        /* Stili specifici per la versione lunga */
        /* Specific styles for the long version */
        .footer-long { flex-direction: column; align-items: flex-start; gap: 15px; padding: 20px 30px; }
        .footer-long .top-row { display: flex; justify-content: space-between; width: 100%; border-bottom: 1px solid #f3f4f6; padding-bottom: 10px; margin-bottom: 10px; }
        .footer-long .disclaimer { font-size: 11px; line-height: 1.4; color: #9ca3af; max-width: 600px; }
        .license-badge { display: inline-flex; align-items: center; gap: 4px; background: #f3f4f6; padding: 4px 8px; border-radius: 100px; font-size: 11px; font-weight: 600; }

        .footer-credits { font-size: 11px; color: #d1d5db; text-align: right; }

        .footer-toggle-btn { display: none; }

        @media (max-width: 600px) {
            .app-footer { padding: 10px 15px; transition: padding 0.3s ease; }
            .footer-long { padding: 10px 15px; gap: 8px; }
            .footer-container { flex-direction: column; gap: 8px; text-align: center; transition: gap 0.3s ease; }
            .footer-long .top-row { 
                flex-direction: column; align-items: center; gap: 8px; padding-bottom: 8px; margin-bottom: 8px; 
                transition: border-bottom-color 0.3s ease, padding-bottom 0.3s ease, margin-bottom 0.3s ease;
            }
            .disclaimer { font-size: 10px; line-height: 1.2; }
            .footer-credits { text-align: center; margin-top: 5px; }
            .main-content { padding-bottom: 220px; } /* Più spazio su mobile per footer lungo / More space on mobile for long footer */

            /* Stili per il toggle Show/Hide */
            /* Styles for Show/Hide toggle */
            .footer-toggle-btn { display: inline-block; margin-left: 8px; background: #f3f4f6; border: 1px solid #d1d5db; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight:600; cursor: pointer; color: var(--text-sub); vertical-align: middle; }
            
            .footer-links, .footer-secondary {
                overflow: hidden;
                transition: max-height 0.4s ease, opacity 0.3s ease, margin 0.3s ease;
                max-height: 500px;
                opacity: 1;
            }

            .app-footer.mobile-hidden .footer-links,
            .app-footer.mobile-hidden .footer-secondary { 
                max-height: 0; 
                opacity: 0; 
                margin: 0; 
                pointer-events: none;
            }
            .app-footer.mobile-hidden .top-row { border-bottom-color: transparent; margin-bottom: 0; padding-bottom: 0; }
            .app-footer.mobile-hidden .footer-container { gap: 0; }
            .app-footer.mobile-hidden { padding: 12px 15px; }
        }

        /* Pulsante Torna Su / Back to Top Button */
        #backToTopBtn {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 30px;
            z-index: 40;
            border: none;
            outline: none;
            background-color: var(--primary);
            color: white;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        #backToTopBtn:hover { background-color: #3730a3; transform: translateY(-3px); }
        
        @media (max-width: 600px) {
            #backToTopBtn { bottom: 80px; right: 20px; }
        }
    </style>
</head>
<body>

<div class="overlay" id="overlay" onclick="toggleMenu()"></div>

<aside class="sidebar" id="sidebar">
    <div class="logo-area">
        <a href="<?php echo ottieniUrl(); ?>" style="display:flex; align-items:center;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:10px"><path d="<?php echo $CONFIG_GENERAL['icona_svg_path']; ?>"/></svg>
            <?php echo $CONFIG_GENERAL['nome_app']; ?>
        </a>
    </div>
    <nav class="nav-scroll">
        <a href="<?php echo ottieniUrl(); ?>" class="nav-item back-link <?php echo !$id_strumento_corrente ? 'active' : ''; ?>">
            <?php echo traduci('back_dash'); ?>
        </a>

        <a href="<?php echo ottieniUrl('link_page'); ?>" class="nav-item <?php echo $id_strumento_corrente == 'link_page' ? 'active' : ''; ?>">
            <?php echo traduci('cat_links'); ?>
        </a>

        <?php foreach($CONFIG_TOOLS_CATALOG as $id_cat => $cat): if($id_cat == 'links') continue; // Keep hiding from tool list ?>
            <div class="cat-header"><?php echo traduci($cat['label_key']); ?></div>
            <?php foreach($cat['items'] as $id_item => $item): ?>
                <a href="<?php echo ottieniUrl($id_item); ?>" class="nav-item <?php echo $id_strumento_corrente == $id_item ? 'active' : ''; ?>">
                    <?php echo traduci($item['key']); ?>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </nav>
</aside>

<div class="main-content">
    
    <div class="top-bar">
        <div style="font-weight:600">
            <?php echo $info_strumento_corrente ? traduci($info_strumento_corrente['key']) : traduci('home_title'); ?>
        </div>
        <div class="lang-switch">
            <a href="<?php echo ottieniUrl($id_strumento_corrente, 'it'); ?>" class="<?php echo $lingua=='it'?'active':''; ?>" title="Italiano">🇮🇹</a>
            <a href="<?php echo ottieniUrl($id_strumento_corrente, 'en'); ?>" class="<?php echo $lingua=='en'?'active':''; ?>" title="English">🇬🇧</a>
            <!-- AGGIUNGI QUI IL LINK PER LA NUOVA LINGUA / ADD NEW LANGUAGE LINK HERE -->
            <!-- <a href="<?php echo ottieniUrl($id_strumento_corrente, 'fr'); ?>" class="<?php echo $lingua=='fr'?'active':''; ?>" title="Français">🇫🇷</a> -->
        </div>
    </div>

    <header class="mobile-header">
        <button class="burger-btn" onclick="toggleMenu()">☰</button>
        <div style="font-weight:700; color:var(--primary)">
            <a href="<?php echo ottieniUrl(); ?>"><?php echo $CONFIG_GENERAL['nome_app']; ?></a>
        </div>
        <div class="lang-switch">
            <a href="<?php echo ottieniUrl($id_strumento_corrente, 'it'); ?>" class="<?php echo $lingua=='it'?'active':''; ?>">🇮🇹</a>
            <a href="<?php echo ottieniUrl($id_strumento_corrente, 'en'); ?>" class="<?php echo $lingua=='en'?'active':''; ?>">🇬🇧</a>
            <!-- AGGIUNGI QUI IL LINK PER LA NUOVA LINGUA / ADD NEW LANGUAGE LINK HERE -->
            <!-- <a href="<?php echo ottieniUrl($id_strumento_corrente, 'fr'); ?>" class="<?php echo $lingua=='fr'?'active':''; ?>">🇫🇷</a> -->
        </div>
    </header>

    <main class="container">
        
        <?php if (!$id_strumento_corrente): ?>
            <h1 style="margin-bottom:30px"><?php echo traduci('home_title'); ?></h1>

            <!-- SEZIONE LINK -->
            <h2 class="main-section-title"><?php echo traduci('cat_links'); ?></h2>
            <p class="main-section-desc"><?php echo traduci('intro_links'); ?></p>
            
            <section class="dash-section">
                <?php render_links_content(); ?>
            </section>

            <!-- SEZIONE TOOLS -->
            <h2 class="main-section-title"><?php echo traduci('sect_tools'); ?></h2>
            <p class="main-section-desc"><?php echo traduci('intro_tools'); ?></p>

            <?php
            foreach($CONFIG_TOOLS_CATALOG as $id_cat => $cat):
                if ($id_cat === 'links') continue;
            ?>
                <section class="dash-section">
                    <div class="dash-sec-title"><?php echo $cat['icon'] . ' ' . traduci($cat['label_key']); ?></div>
                    <div class="dash-sec-intro"><?php echo traduci($cat['intro_key']); ?></div>
                    <div class="sub-cat-grid">
                        <?php
                        foreach($cat['items'] as $id_item => $item) {
                            $item['id'] = $id_item;
                            render_link_card($item);
                        }
                        ?>
                    </div>
                </section>
            <?php endforeach; ?>

        <?php elseif ($info_strumento_corrente): ?>
            <?php call_user_func($info_strumento_corrente['func'], $dati_risultato); ?>
        <?php endif; ?>

    </main>

<footer class="app-footer footer-long mobile-hidden" id="app-footer">
        <div class="footer-container" style="flex-direction: column; width:100%; align-items: normal;">
            
            <div class="top-row">
                <div class="brand-section" style="font-size:16px;">
                    <?php echo $CONFIG_GENERAL['nome_app']; ?> <span class="brand-x">x</span> <span class="brand-uni"><?php echo $CONFIG_GENERAL['ente_acronimo']; ?></span>
                    <button class="footer-toggle-btn" onclick="toggleFooterMobile()" id="ft-btn">Show</button>
                </div>
                <div class="footer-links">
                    <a href="<?php echo $CONFIG_GENERAL['url_repo']; ?>" target="_blank" style="background:var(--primary-soft); color:var(--primary); padding:6px 12px; border-radius:6px; font-weight:600; text-decoration:none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
                        Repository
                    </a>
                    <div style="display:flex; align-items:center; gap:4px; margin-left:5px; color:#9ca3af;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span style="font-size:11px;">Licenza:</span>
                    </div>
                    <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank" class="license-badge" style="text-decoration:none; color:inherit;">
                        CC BY-NC-SA 4.0
                    </a>
                </div>
            </div>

            <div class="footer-secondary" style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:20px;">
                <div class="disclaimer">
                    <p style="margin:0 0 6px 0;"><?php echo sprintf(traduci('footer_dev_info'), $CONFIG_GENERAL['sviluppatore']); ?></p>
                    <p style="margin:0;"><?php echo traduci('footer_disclaimer'); ?></p>
                <?php if (!empty($CONFIG_GENERAL['mostra_credits_originali']) && $CONFIG_GENERAL['mostra_credits_originali']): ?>
                    <p style="margin:8px 0 0 0; padding-top:8px; border-top:1px dashed #d1d5db; font-style:italic;">
                        <?php echo traduci('footer_credits_orig'); ?>
                    </p>
                <?php endif; ?>
                </div>
                <div class="footer-credits">
                    <div>&copy; <?php echo date('Y'); ?> <?php echo $CONFIG_GENERAL['nome_progetto']; ?></div>
                    <div style="margin-top:4px;">
                        <a href="mailto:<?php echo $CONFIG_GENERAL['email_contatto']; ?>" style="color:#9ca3af; text-decoration:none;"><?php echo $CONFIG_GENERAL['email_contatto']; ?></a>
                    </div>
                </div>
            </div>

        </div>
    </footer>

    <button onclick="scrollToTop()" id="backToTopBtn" title="Torna su">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg>
    </button>

</div>

<script>
/**
 * Alterna la visibilità del menu sidebar mobile e dell'overlay.
 * Toggles the visibility of the mobile sidebar menu and the overlay.
 */
function toggleMenu() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('active');
}

/**
 * Copia il contenuto testuale di un dato elemento negli appunti.
 * Copies the text content of a given element to the clipboard.
 * Gestisce sia elementi standard (leggendo innerText) che input/textarea (leggendo value).
 * It handles both standard elements (reading innerText) and input/textarea elements (reading value).
 * @param {string} elementId L'ID dell'elemento da cui copiare il testo. / The ID of the element to copy text from.
 */
function copyText(elementId) {
    var elemento = document.getElementById(elementId);
    if(!elemento) return;
    var testo = elemento.innerText || elemento.value;

    // Fallback per contesti non sicuri (HTTP) o browser legacy
    // Fallback for insecure contexts (HTTP) or legacy browsers
    var textArea = document.createElement("textarea");
    textArea.value = testo;
    textArea.style.position = "fixed"; 
    textArea.style.left = "-9999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        alert('<?php echo traduci('copied'); ?>');
    } catch (err) {
        console.error('Errore copia:', err);
        alert('Errore durante la copia.');
    }
    document.body.removeChild(textArea);
}

/**
 * Gestisce l'espansione/compressione del footer su mobile
 * Handles footer expansion/collapse on mobile
 */
function toggleFooterMobile() {
    var footer = document.getElementById('app-footer');
    var btn = document.getElementById('ft-btn');
    if (footer.classList.contains('mobile-hidden')) {
        footer.classList.remove('mobile-hidden');
        btn.innerText = 'Hide';
    } else {
        footer.classList.add('mobile-hidden');
        btn.innerText = 'Show';
    }
}

// Logica Torna Su / Back to Top Logic
var backToTopBtn = document.getElementById("backToTopBtn");

window.addEventListener('scroll', function() {
    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
        backToTopBtn.style.display = "block";
    } else {
        backToTopBtn.style.display = "none";
    }
});

function scrollToTop() {
    window.scrollTo({top: 0, behavior: 'smooth'});
}
</script>
</body>
</html>

<?php
// ==========================================
// SEZIONE 9: FUNZIONI DI RENDERIZZAZIONE VISTA / VIEW RENDER FUNCTIONS
// ==========================================

/**
 * Renderizza la pagina dedicata per la categoria "Link di Ateneo".
 * Mostra un link in evidenza e il resto in una griglia.
 * 
 * Renders the dedicated page for the "Link di Ateneo" category.
 * It displays a featured link prominently and the rest in a grid.
 * 
 * @param array|null $risultato Non usato, ma richiesto dal chiamante. / Not used, but required by the caller.
 */
function visualizza_pagina_link($risultato) {
    ?>
    <h1 style="margin-bottom:10px"><?php echo traduci('cat_links'); ?></h1>
    <?php render_links_content(); ?>
    <?php
}

/**
 * Renderizza il contenuto per la categoria link (separatori, in evidenza, griglia).
 * Condiviso tra dashboard e pagina dedicata.
 * 
 * Renders the content for the links category (separators, featured, grid).
 * Shared between dashboard and dedicated page.
 */
function render_links_content() {
    global $CONFIG_TOOLS_CATALOG;
    $link_config = $CONFIG_TOOLS_CATALOG['links'];
    $link_items = $link_config['items'];
    $featured_links = [];
    $other_links = [];

    foreach ($link_items as $id => $item) {
        if (isset($item['featured']) && $item['featured'] === true) {
            $item['id'] = $id;
            $featured_links[] = $item;
        } else {
            $other_links[$id] = $item;
        }
    }
    ?>
    
    <?php if (!empty($featured_links)): ?>
        <p class="separator-text"><?php echo traduci($link_config['separator_featured_key']); ?></p>

        <div class="sub-cat-grid">
            <?php
            // Renderizza i link in evidenza
            // Render the featured links
            foreach ($featured_links as $link) {
                render_link_card($link, true);
            }
            ?>
        </div>
    <?php endif; ?>

    <p class="separator-text"><?php echo traduci($link_config['separator_other_key']); ?></p>

    <div class="sub-cat-grid">
        <?php
        // Renderizza il resto dei link
        // Render the rest of the links
        foreach ($other_links as $id => $item) {
            $item['id'] = $id;
            render_link_card($item);
        }
        ?>
    </div>
    <?php
}

/**
 * Funzione helper per renderizzare una singola card link.
 * Helper function to render a single link card.
 * 
 * @param array $item L'elemento link dal catalogo. / The link item from the catalog.
 * @param bool $is_featured Se la card deve avere uno stile in evidenza. / Whether the card should have a featured style.
 */
function render_link_card($item, $is_featured = false) {
    $tipo = $item['type'] ?? 'tool';
    $class = 'link-card' . ($is_featured ? ' featured-card' : '');
    
    $badge_key = 'type_tool';
    if ($tipo == 'direct_link') $badge_key = 'type_link';
    elseif ($tipo == 'link_group') $badge_key = 'type_group';
    
    if ($tipo == 'direct_link') { ?>
        <a href="<?php echo htmlspecialchars($item['url']); ?>" class="<?php echo $class; ?>" target="_blank" rel="noopener noreferrer">
            <span class="type-badge"><?php echo traduci($badge_key); ?></span>
            <div class="lc-head"><?php echo traduci($item['key']); ?></div>
            <div class="lc-desc"><?php echo traduci($item['desc_short']); ?></div>
        </a>
    <?php } else { // Copre 'tool' e 'link_group' / Covers 'tool' and 'link_group' ?>
        <a href="<?php echo ottieniUrl($item['id']); ?>" class="<?php echo $class; ?>">
            <span class="type-badge"><?php echo traduci($badge_key); ?></span>
            <div class="lc-head"><?php echo traduci($item['key']); ?></div>
            <div class="lc-desc" style="color:#6b7280"><?php echo traduci($item['desc_short']); ?></div>
        </a>
    <?php }
}

/**
 * Renderizza una pagina che mostra una lista di link per un gruppo specifico.
 * Questa funzione è usata per elementi di tipo 'link_group'.
 * 
 * Renders a page that displays a list of links for a specific group.
 * This function is used for items of type 'link_group'.
 * 
 * @param array|null $risultato I dati del risultato (non usati qui, ma richiesti dal chiamante). / The result data (not used here, but required by the caller).
 */
function visualizza_gruppo_link($risultato) {
    global $info_strumento_corrente;
    ?>
    <div class="card" style="padding:0;">
        <div style="padding: 20px 20px 0 20px;">
            <div class="tool-title"><?php echo traduci($info_strumento_corrente['key']); ?></div>
            <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_short']); ?></div>
        </div>
        
        <div class="sub-cat-grid" style="padding: 20px;">
            <?php foreach($info_strumento_corrente['links'] as $link): 
                $is_featured = $link['featured'] ?? false;
                $is_full = $link['full_width'] ?? false;
                $class = 'link-card' . ($is_featured ? ' featured-card' : ($is_full ? ' full-width-card' : ''));
            ?>
                <a href="<?php echo htmlspecialchars($link['url']); ?>" class="<?php echo $class; ?>" target="_blank" rel="noopener noreferrer" style="display:block; text-decoration:none; margin:0;">
                    <span class="type-badge"><?php echo traduci('type_link'); ?></span>
                    <div class="lc-head"><?php echo traduci($link['key_titolo']); ?></div>
                    <div class="lc-desc"><?php echo traduci($link['key_desc']); ?></div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}


/**
 * Renderizza l'interfaccia dello strumento "Calcolo Ore Lavorate".
 * Renders the "Work Hours Calc" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_intervalli($risultato) {
    global $info_strumento_corrente;
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="intervalli">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_intervalli'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <div style="background:#fef2f2; color:#991b1b; padding:10px; border-radius:6px; font-size:13px; margin-bottom:20px; border:1px solid #fecaca;">
            <strong><?php echo traduci('lbl_warning'); ?>:</strong> <?php echo traduci('note_intervalli'); ?>
        </div>

        <div id="rows-wrap">
            <?php 
            $conteggio = isset($_POST['h_start']) ? count($_POST['h_start']) : 1;
            for($i=0; $i<$conteggio; $i++): 
                $ora_i = isset($_POST['h_start'][$i]) ? htmlspecialchars($_POST['h_start'][$i]) : '';
                $min_i = isset($_POST['m_start'][$i]) ? htmlspecialchars($_POST['m_start'][$i]) : '';
                $ora_f = isset($_POST['h_end'][$i]) ? htmlspecialchars($_POST['h_end'][$i]) : '';
                $min_f = isset($_POST['m_end'][$i]) ? htmlspecialchars($_POST['m_end'][$i]) : '';
            ?>
            <div class="row-inputs" style="display:flex; gap:10px; margin-bottom:10px; align-items:center;">
                <span style="font-size:12px; width:30px; font-weight:bold"><?php echo traduci('lbl_from'); ?>:</span>
                <input type="number" name="h_start[]" min="0" max="23" placeholder="HH" value="<?php echo $ora_i; ?>" required> :
                <input type="number" name="m_start[]" min="0" max="59" placeholder="MM" value="<?php echo $min_i; ?>">
                
                <span style="font-size:12px; width:30px; font-weight:bold; text-align:right"><?php echo traduci('lbl_to'); ?>:</span>
                <input type="number" name="h_end[]" min="0" max="23" placeholder="HH" value="<?php echo $ora_f; ?>" required> :
                <input type="number" name="m_end[]" min="0" max="59" placeholder="MM" value="<?php echo $min_f; ?>">
            </div>
            <?php endfor; ?>
        </div>
        
        <button type="button" onclick="aggiungiRigaIntervallo()" style="background:#f3f4f6; color:#374151; border:1px solid #d1d5db; padding:8px; width:100%; border-radius:6px; margin-top:10px; cursor:pointer">+ <?php echo traduci('lbl_hours'); ?></button>
        <button type="submit" class="btn"><?php echo traduci('calc'); ?></button>

        <?php if($risultato): ?>
            <div class="result-box">
                <div class="res-main"><?php echo htmlspecialchars($risultato['main']); ?></div>
                <div class="res-sub"><?php echo htmlspecialchars($risultato['sub']); ?></div>
            </div>
        <?php endif; ?>
    </form>
    <script>
        /**
         * Aggiunge una nuova riga per l'input dell'intervallo di tempo al form.
         * Adds a new row for time interval input to the form.
         */
        function aggiungiRigaIntervallo() {
            var div = document.createElement('div');
            div.className = 'row-inputs'; 
            div.style.cssText = 'display:flex; gap:10px; margin-bottom:10px; align-items:center;';
            div.innerHTML = document.querySelector('.row-inputs').innerHTML;
            div.querySelectorAll('input').forEach(i => i.value = '');
            document.getElementById('rows-wrap').appendChild(div);
        }
    </script>
    <?php
}

/**
 * Renderizza l'interfaccia dello strumento "Convertitore Recuperi".
 * Renders the "Overtime Converter" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_recuperi($risultato) {
    global $info_strumento_corrente;
    $saldo_ore = isset($_POST['saldo_h']) ? htmlspecialchars($_POST['saldo_h']) : '';
    $saldo_min = isset($_POST['saldo_m']) ? htmlspecialchars($_POST['saldo_m']) : '';
    $data_inizio = isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : date('Y-m-d');
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="recuperi">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_recuperi'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
            <div>
                <label><?php echo traduci('lbl_balance_hours'); ?></label>
                <div style="display:flex; gap:5px;">
                    <input type="number" name="saldo_h" placeholder="HH" required value="<?php echo $saldo_ore; ?>">
                    <input type="number" name="saldo_m" placeholder="MM" value="<?php echo $saldo_min; ?>">
                </div>
            </div>
            <div>
                <label><?php echo traduci('lbl_start_date'); ?></label>
                <div style="display:flex; gap:5px;">
                    <input type="date" name="start_date" id="start_date" required value="<?php echo $data_inizio; ?>" style="flex:1">
                    <button type="button" style="padding:0 15px; background:#f3f4f6; border:1px solid #d1d5db; border-radius:6px; cursor:pointer; color:#374151; font-weight:500;" onclick="var d = new Date(); d.setMinutes(d.getMinutes() - d.getTimezoneOffset()); document.getElementById('start_date').value = d.toISOString().slice(0,10);"><?php echo traduci('lbl_today'); ?></button>
                </div>
            </div>
        </div>

        <label style="margin-bottom:10px; display:block; border-bottom:1px solid #eee; padding-bottom:5px;"><?php echo traduci('lbl_week_schedule'); ?>:</label>
        <?php 
        $giorni = [traduci('day_mon'), traduci('day_tue'), traduci('day_wed'), traduci('day_thu'), traduci('day_fri')];
        $opzioni = ['7:12'=>'7h 12m', '8:00'=>'8h 00m', '9:00'=>'9h 00m', '6:00'=>'6h 00m', '4:00'=>'4h 00m'];
        foreach($giorni as $k => $g): $indice = $k+1; ?>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; font-size:14px;">
                <span><?php echo $g; ?></span>
                <select name="day_<?php echo $indice; ?>" style="width:140px; padding:6px;">
                    <?php foreach($opzioni as $val => $etichetta): 
                        $giorno_selezionato = isset($_POST["day_$indice"]) ? $_POST["day_$indice"] : '7:12';
                    ?>
                        <option value="<?php echo $val; ?>" <?php echo $giorno_selezionato == $val ? 'selected' : ''; ?>><?php echo $etichetta; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn"><?php echo traduci('calc'); ?></button>

        <?php if($risultato && isset($risultato['type']) && $risultato['type']=='recuperi'): ?>
            <div class="result-box">
                <div style="font-size:15px; color:#047857; margin-bottom:10px;">
                    <?php echo sprintf(traduci('res_recuperi_intro'), $risultato['days']); ?>
                </div>
                <div class="res-main" style="font-size:18px; margin-bottom:15px;">
                    <?php echo sprintf(traduci('res_recuperi_period'), $risultato['start'], $risultato['end']); ?>
                </div>
                <div style="font-size:16px; color:#064e3b; font-weight:bold; border-top:1px solid #d1fae5; padding-top:10px;">
                    <?php echo traduci('res_recuperi_rem'); ?> <?php echo htmlspecialchars($risultato['resto']); ?>
                </div>
            </div>
            <div style="background:#fef2f2; color:#991b1b; padding:15px; border-radius:8px; font-size:13px; margin-top:15px; border:1px solid #fecaca; text-align:left;">
                <?php echo traduci('warn_recuperi_holidays'); ?>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

/**
 * Renderizza l'interfaccia dello strumento "Scadenza e Durata".
 * Renders the "Deadline & Duration" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_scadenza($risultato) {
    global $info_strumento_corrente;
    $modalita = isset($_POST['calc_mode']) ? htmlspecialchars($_POST['calc_mode']) : 'end';
    $ora_inizio = isset($_POST['start_h']) ? htmlspecialchars($_POST['start_h']) : '09';
    $min_inizio = isset($_POST['start_m']) ? htmlspecialchars($_POST['start_m']) : '00';
    $giorno_data = isset($_POST['date_d']) ? htmlspecialchars($_POST['date_d']) : '';
    $mese_data = isset($_POST['date_m']) ? htmlspecialchars($_POST['date_m']) : '';
    $anno_data = isset($_POST['date_y']) ? htmlspecialchars($_POST['date_y']) : date('Y');
    $ore_durata = isset($_POST['dur_h']) ? htmlspecialchars($_POST['dur_h']) : 0;
    $min_durata = isset($_POST['dur_m']) ? htmlspecialchars($_POST['dur_m']) : 0;
    $ore_pausa = isset($_POST['pau_h']) ? htmlspecialchars($_POST['pau_h']) : 0;
    $min_pausa = isset($_POST['pau_m']) ? htmlspecialchars($_POST['pau_m']) : 0;
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="scadenza">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_scadenza'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <div class="tab-group">
            <input type="radio" name="calc_mode" id="m_end" value="end" class="tab-radio" <?php echo $modalita=='end'?'checked':''; ?>>
            <label for="m_end" class="tab-label"><?php echo traduci('lbl_want_end'); ?></label>
            
            <input type="radio" name="calc_mode" id="m_start" value="start" class="tab-radio" <?php echo $modalita=='start'?'checked':''; ?>>
            <label for="m_start" class="tab-label"><?php echo traduci('lbl_want_start'); ?></label>
        </div>

        <div style="background:#f9fafb; padding:15px; border-radius:8px; margin-bottom:15px;">
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="flex:1; min-width:120px;">
                    <label><?php echo traduci('lbl_ref_time'); ?></label>
                    <div style="display:flex; gap:5px;">
                        <input type="number" name="start_h" placeholder="<?php echo traduci('ph_hh'); ?>" value="<?php echo $ora_inizio; ?>"> :
                        <input type="number" name="start_m" placeholder="<?php echo traduci('ph_mm'); ?>" value="<?php echo $min_inizio; ?>">
                    </div>
                </div>
                <div style="flex:2; min-width:200px;">
                    <label><?php echo traduci('lbl_date_opt'); ?></label>
                    <div style="display:flex; gap:5px;">
                        <input type="number" name="date_d" placeholder="<?php echo traduci('ph_dd'); ?>" value="<?php echo $giorno_data; ?>"> /
                        <input type="number" name="date_m" placeholder="<?php echo traduci('ph_mm'); ?>" value="<?php echo $mese_data; ?>"> /
                        <input type="number" name="date_y" placeholder="<?php echo traduci('ph_yyyy'); ?>" value="<?php echo $anno_data; ?>">
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-bottom:15px;">
            <label><?php echo traduci('lbl_duration'); ?></label>
            <div style="display:flex; gap:10px; align-items:center;">
                <input type="number" name="dur_h" value="<?php echo $ore_durata; ?>"> h
                <input type="number" name="dur_m" value="<?php echo $min_durata; ?>"> m
            </div>
        </div>
        
        <div style="margin-bottom:15px;">
            <label><?php echo traduci('lbl_pause'); ?></label>
            <div style="display:flex; gap:10px; align-items:center;">
                <input type="number" name="pau_h" value="<?php echo $ore_pausa; ?>"> h
                <input type="number" name="pau_m" value="<?php echo $min_pausa; ?>"> m
            </div>
        </div>

        <button type="submit" class="btn"><?php echo traduci('calc'); ?></button>
        
        <?php if($risultato && !isset($risultato['type'])): ?>
            <div class="result-box">
                <div class="res-main"><?php echo htmlspecialchars($risultato['main']); ?></div>
                <div class="res-sub"><?php echo htmlspecialchars($risultato['sub']); ?></div>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

/**
 * Renderizza l'interfaccia dello strumento "Differenza Date".
 * Renders the "Date Difference" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_date($risultato) {
    global $info_strumento_corrente;
    $data1 = isset($_POST['d1']) ? htmlspecialchars($_POST['d1']) : '';
    $data2 = isset($_POST['d2']) ? htmlspecialchars($_POST['d2']) : '';
    $d_start = isset($_POST['d_start']) ? htmlspecialchars($_POST['d_start']) : '';
    $days_add = isset($_POST['days_add']) ? htmlspecialchars($_POST['days_add']) : '';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="date">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_dates'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <h3 style="font-size:16px; margin-top:0; border-bottom:1px solid #eee; padding-bottom:5px; color:var(--primary)"><?php echo traduci('lbl_diff_dates'); ?></h3>
        <div class="tab-group" style="background:none; padding:0; gap:20px;">
            <div style="flex:1">
                <label><?php echo traduci('lbl_start_date'); ?></label>
                <div style="display:flex; gap:5px;">
                    <input type="date" name="d1" id="d1" value="<?php echo $data1; ?>" style="flex:1">
                    <button type="button" style="padding:0 15px; background:#f3f4f6; border:1px solid #d1d5db; border-radius:6px; cursor:pointer; color:#374151; font-weight:500;" onclick="var d = new Date(); d.setMinutes(d.getMinutes() - d.getTimezoneOffset()); document.getElementById('d1').value = d.toISOString().slice(0,10);"><?php echo traduci('lbl_today'); ?></button>
                </div>
            </div>
            <div style="flex:1">
                <label><?php echo traduci('lbl_end_date'); ?></label>
                <div style="display:flex; gap:5px;">
                    <input type="date" name="d2" id="d2" value="<?php echo $data2; ?>" style="flex:1">
                    <button type="button" style="padding:0 15px; background:#f3f4f6; border:1px solid #d1d5db; border-radius:6px; cursor:pointer; color:#374151; font-weight:500;" onclick="var d = new Date(); d.setMinutes(d.getMinutes() - d.getTimezoneOffset()); document.getElementById('d2').value = d.toISOString().slice(0,10);"><?php echo traduci('lbl_today'); ?></button>
                </div>
            </div>
        </div>
        <label style="margin-top:10px; display:flex; align-items:center; gap:8px; font-weight:normal; cursor:pointer">
            <input type="checkbox" name="only_working_diff" <?php echo isset($_POST['only_working_diff'])?'checked':''; ?>> <?php echo traduci('lbl_calc_working'); ?>
        </label>
        <button type="submit" name="mode" value="diff" class="btn"><?php echo traduci('calc'); ?></button>

        <h3 style="font-size:16px; margin-top:40px; border-bottom:1px solid #eee; padding-bottom:5px; color:var(--primary)"><?php echo traduci('lbl_add_days_date'); ?></h3>
        <div class="tab-group" style="background:none; padding:0; gap:20px;">
            <div style="flex:1">
                <label><?php echo traduci('lbl_start_date'); ?></label>
                <div style="display:flex; gap:5px;">
                    <input type="date" name="d_start" id="d_start" value="<?php echo $d_start; ?>" style="flex:1">
                    <button type="button" style="padding:0 15px; background:#f3f4f6; border:1px solid #d1d5db; border-radius:6px; cursor:pointer; color:#374151; font-weight:500;" onclick="var d = new Date(); d.setMinutes(d.getMinutes() - d.getTimezoneOffset()); document.getElementById('d_start').value = d.toISOString().slice(0,10);"><?php echo traduci('lbl_today'); ?></button>
                </div>
            </div>
            <div style="flex:1">
                <label><?php echo traduci('lbl_days_to_add'); ?></label>
                <input type="number" name="days_add" value="<?php echo $days_add; ?>" placeholder="00">
            </div>
        </div>
        <label style="margin-top:10px; display:flex; align-items:center; gap:8px; font-weight:normal; cursor:pointer">
            <input type="checkbox" name="only_working_add" <?php echo isset($_POST['only_working_add'])?'checked':''; ?>> <?php echo traduci('lbl_calc_working'); ?>
        </label>
        <button type="submit" name="mode" value="add" class="btn"><?php echo traduci('calc'); ?></button>

        <?php if($risultato): ?>
            <div class="result-box">
                <div class="res-main"><?php echo htmlspecialchars($risultato['main']); ?></div>
                <div class="res-sub"><?php echo htmlspecialchars($risultato['sub']); ?></div>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

/**
 * Renderizza l'interfaccia dello strumento "Gestione IVA".
 * Renders the "VAT Manager" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_iva($risultato) {
    global $info_strumento_corrente;
    $op_selezionata = isset($_POST['operazione']) ? htmlspecialchars($_POST['operazione']) : 'scorporo';
    $ali_selezionata = isset($_POST['aliquota']) ? htmlspecialchars($_POST['aliquota']) : '22';
    $importo = isset($_POST['importo']) ? htmlspecialchars($_POST['importo']) : '';
    $aliquota_altra = isset($_POST['aliquota_other']) ? htmlspecialchars($_POST['aliquota_other']) : '';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="iva">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_iva'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <div style="margin-bottom:15px">
            <label><?php echo traduci('lbl_amount'); ?></label>
            <input type="text" name="importo" value="<?php echo $importo; ?>" placeholder="<?php echo traduci('ph_amount'); ?>" required>
        </div>
        <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
            <div style="flex:1; min-width: 120px;">
                <label><?php echo traduci('lbl_rate'); ?></label>
                <select name="aliquota" id="aliquota_select">
                    <option value="22" <?php echo $ali_selezionata=='22'?'selected':''; ?>><?php echo traduci('rate_22'); ?></option>
                    <option value="10" <?php echo $ali_selezionata=='10'?'selected':''; ?>><?php echo traduci('rate_10'); ?></option>
                    <option value="4" <?php echo $ali_selezionata=='4'?'selected':''; ?>><?php echo traduci('rate_4'); ?></option>
                    <option value="other" <?php echo $ali_selezionata=='other'?'selected':''; ?>><?php echo traduci('lbl_other'); ?></option>
                </select>
            </div>
            <div style="flex:1; min-width: 120px; display: <?php echo $ali_selezionata=='other'?'block':'none'; ?>;" id="aliquota_other_wrap">
                <label><?php echo traduci('lbl_custom_rate'); ?></label>
                <input type="number" step="0.01" name="aliquota_other" value="<?php echo $aliquota_altra; ?>">
            </div>
        </div>

        <label><?php echo traduci('lbl_op'); ?></label>
        <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:20px;">
            <label style="font-weight:400; background:#f9fafb; padding:10px; border:1px solid #e5e7eb; border-radius:6px; cursor:pointer; display:flex; align-items:center; gap:10px;">
                <input type="radio" name="operazione" value="scorporo" <?php echo $op_selezionata=='scorporo'?'checked':''; ?>> 
                <span><?php echo traduci('lbl_op_scorpora'); ?></span>
            </label>
            <label style="font-weight:400; background:#f9fafb; padding:10px; border:1px solid #e5e7eb; border-radius:6px; cursor:pointer; display:flex; align-items:center; gap:10px;">
                <input type="radio" name="operazione" value="add" <?php echo $op_selezionata=='add'?'checked':''; ?>> 
                <span><?php echo traduci('lbl_op_add'); ?></span>
            </label>
            <label style="font-weight:400; background:#f9fafb; padding:10px; border:1px solid #e5e7eb; border-radius:6px; cursor:pointer; display:flex; align-items:center; gap:10px;">
                <input type="radio" name="operazione" value="calc" <?php echo $op_selezionata=='calc'?'checked':''; ?>> 
                <span><?php echo traduci('lbl_op_calc'); ?></span>
            </label>
        </div>
        <button type="submit" class="btn"><?php echo traduci('calc'); ?></button>
        <?php if($risultato && isset($risultato['html'])) echo "<div class='result-box' style='padding:15px'>{$risultato['html']}</div>"; ?>
    </form>
    <script>
        // Mostra/nascondi il campo input aliquota personalizzata in base alla selezione.
        // Show/hide the custom VAT rate input field based on dropdown selection.
        document.getElementById('aliquota_select').addEventListener('change', function() {
            var wrap = document.getElementById('aliquota_other_wrap');
            wrap.style.display = (this.value === 'other') ? 'block' : 'none';
        });
    </script>
    <?php 

}

/**
 * Renderizza l'interfaccia dello strumento "Verifica IBAN".
 * Renders the "IBAN Validator" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_iban($risultato) {
    global $info_strumento_corrente;
    $iban = isset($_POST['iban']) ? htmlspecialchars($_POST['iban']) : '';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="iban">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_iban'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <label><?php echo traduci('lbl_iban_code'); ?></label>
        <input type="text" name="iban" placeholder="IT00X..." value="<?php echo $iban; ?>" style="text-transform:uppercase" required>
        <button type="submit" class="btn" style="margin-top:15px"><?php echo traduci('verify'); ?></button>
        
        <?php if($risultato): ?>
            <div class="result-box" style="background:<?php echo $risultato['color']=='green'?'#ecfdf5':'#fef2f2'; ?>; border-color:<?php echo $risultato['color']=='green'?'#d1fae5':'#fecaca'; ?>">
                <strong style="color:<?php echo $risultato['color']; ?>"><?php echo htmlspecialchars($risultato['main']); ?></strong>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

/**
 * Renderizza l'interfaccia dello strumento "Sanificatore Testo".
 * Renders the "Text Sanitizer" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_testo($risultato) {
    global $info_strumento_corrente;
    $selezione = isset($_POST['text_op']) ? htmlspecialchars($_POST['text_op']) : 'oneline';
    $testo_input = isset($_POST['text_in']) ? htmlspecialchars($_POST['text_in']) : '';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="text">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_text'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <label><?php echo traduci('lbl_input_text'); ?></label>
        <textarea name="text_in" rows="5" style="font-family:monospace"><?php echo $testo_input; ?></textarea>
        
        <div style="margin:15px 0;">
            <select name="text_op">
                <option value="oneline" <?php echo $selezione=='oneline'?'selected':''; ?>><?php echo traduci('opt_oneline'); ?></option>
                <option value="spaces" <?php echo $selezione=='spaces'?'selected':''; ?>><?php echo traduci('opt_spaces'); ?></option>
                <option value="title" <?php echo $selezione=='title'?'selected':''; ?>><?php echo traduci('opt_title'); ?></option>
                <option value="upper" <?php echo $selezione=='upper'?'selected':''; ?>><?php echo traduci('opt_upper'); ?></option>
                <option value="lower" <?php echo $selezione=='lower'?'selected':''; ?>><?php echo traduci('opt_lower'); ?></option>
            </select>
        </div>
        <button type="submit" class="btn"><?php echo traduci('clean'); ?></button>

        <?php if($risultato): ?>
            <div style="margin-top:20px;">
                <label><?php echo traduci('lbl_result'); ?></label>
                <div class="res-raw" id="resTxt"><?php echo htmlspecialchars($risultato['raw']); ?></div>
                <button type="button" class="btn" style="margin-top:5px; background:#6b7280" onclick="copyText('resTxt')"><?php echo traduci('copy'); ?></button>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

/**
 * Renderizza l'interfaccia dello strumento "Lista Email".
 * Renders the "Email List Formatter" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_email($risultato) {
    global $info_strumento_corrente;
    $lista_email = isset($_POST['email_list']) ? htmlspecialchars($_POST['email_list']) : '';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="email">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_email'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <label><?php echo traduci('lbl_input_list'); ?></label>
        <textarea name="email_list" rows="6" placeholder="<?php echo traduci('ph_email_list'); ?>"><?php echo $lista_email; ?></textarea>
        
        <label style="margin-top:15px"><?php echo traduci('lbl_separator'); ?></label>
        <select name="separator">
            <option value="comma"><?php echo traduci('opt_comma'); ?></option>
            <option value="semicolon"><?php echo traduci('opt_semicolon'); ?></option>
        </select>
        
        <button type="submit" class="btn" style="margin-top:15px"><?php echo traduci('format'); ?></button>

        <?php if($risultato): ?>
            <div style="margin-top:20px;">
                <label><?php echo traduci('lbl_result'); ?></label>
                <div class="res-raw" id="resEmail"><?php echo htmlspecialchars($risultato['raw']); ?></div>
                <button type="button" class="btn" style="margin-top:5px; background:#6b7280" onclick="copyText('resEmail')"><?php echo traduci('copy'); ?></button>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

/**
 * Renderizza l'interfaccia dello strumento "Generatore Password".
 * Renders the "Password Generator" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_password($risultato) {
    global $info_strumento_corrente;
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="pass">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_pass'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <div style="text-align:center; padding:20px;">
            <?php if(isset($risultato['raw'])): ?>
                <div style="font-size:32px; font-family:monospace; font-weight:bold; color:#4338ca; margin-bottom:20px; word-break:break-all;" id="passTxt">
                    <?php echo htmlspecialchars($risultato['raw']); ?>
                </div>
                <button type="button" class="btn" style="background:#059669; margin-bottom:15px;" onclick="copyText('passTxt')"><?php echo traduci('copy'); ?></button>
            <?php else: ?>
                <div style="color:#aaa; font-style:italic; margin-bottom:20px;"><?php echo traduci('msg_press_generate'); ?></div>
            <?php endif; ?>
            
            <button type="submit" class="btn"><?php echo traduci('generate'); ?></button>
        </div>
    </form>
    <?php
}
?>