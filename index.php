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
 * @version 2.4.0 (2025-12-24)
 * @license CC BY-NC-SA 4.0
 * @license_url https://creativecommons.org/licenses/by-nc-sa/4.0/    
 */

// ---------------------------------------------------------
// SEZIONE 1: BOOTSTRAP
// ---------------------------------------------------------

// Inizializza sessione PHP 
// Initialize PHP session
session_start(); 

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
        'reset' => 'Reset',
        
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
        'lbl_sat_work' => 'Considera Sabato lavorativo',
        'lbl_sun_work' => 'Considera Domenica lavorativa',
        'lbl_skip_holidays' => 'Salta festivi e Pasquetta',
        'lbl_skip_dates' => 'Altre date da escludere dal calcolo (gg/mm/aaaa, una per riga)',
        
        // -- TOOL: ORARI ENTRATA/USCITA --
        'tool_times' => 'Orari Entrata e Uscita',
        'desc_short_times' => 'Calcola l\'ora di fine partendo da inizio e durata, o viceversa.',
        'desc_long_times' => 'Calcola l\'ora di fine o di inizio in base alla durata.',
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
        'lbl_add_days_date' => 'Aggiungi Giorni a Data (calcola scadenza effettiva)',
        'lbl_days_to_add' => 'Giorni da aggiungere',
        'lbl_years' => 'Anni',
        'lbl_months' => 'Mesi',
        'lbl_total_days' => 'Giorni totali',
        'lbl_all_days' => 'conteggiando tutti i giorni',
        'lbl_use_holidays' => 'Escludi Festivi Nazionali e Pasquetta',
        'lbl_patron' => 'Santo Patrono (gg/mm)',
        'lbl_closures' => 'Chiusure Ateneo (gg/mm/aaaa, una per riga)',
        
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
        'lbl_iban_details' => 'Scomposizione IBAN Italiano',
        'lbl_bi_link' => 'Consulta Albi su Banca d\'Italia',
        'lbl_bi_desc' => 'Consulta gli Albi e gli Elenchi sul portale GIAVA per verificare a che banca appartiene il codice ABI <strong>%s</strong>.',
        'btn_bi_open' => 'Apri Albi ed Elenchi',
        'iban_country' => 'Paese',
        'iban_check' => 'Check',
        'iban_cin' => 'CIN',
        'iban_abi' => 'ABI',
        'iban_cab' => 'CAB',
        'iban_account' => 'Conto',
        'iban_tooltip_country' => 'Codice paese (ISO 3166-1)',
        'iban_tooltip_check' => 'Cifre di controllo internazionali (check digits)',
        'iban_tooltip_cin' => 'Control Internal Number: carattere di controllo nazionale',
        'iban_tooltip_abi' => 'Codice ABI (Associazione Bancaria Italiana): identifica la banca',
        'iban_tooltip_cab' => 'Codice di Avviamento Bancario: identifica la filiale',
        'iban_tooltip_account' => 'Numero di Conto Corrente',
        
        // -- TOOL: SANIFICATORE TESTO --
        'tool_text' => 'Sanificatore Testo',
        'desc_short_text' => 'Correggi maiuscole, spazi e a capo per testi puliti.',
        'desc_long_text' => 'Pulisce testi da PDF, rimuove spazi doppi e corregge maiuscole.',
        'lbl_input_text' => 'Testo Input',
        'opt_oneline' => 'Rimuovi A Capo (Tutti)',
        'opt_spaces' => 'Rimuovi Spazi Doppi',
        'opt_title' => 'Iniziali Maiuscole (Mario Rossi)',
        'opt_upper' => 'TUTTO MAIUSCOLO',
        'opt_lower' => 'tutto minuscolo',
        'opt_smart_newline' => 'Rimuovi A Capo Intelligente (escludi dopo il punto)',
        'opt_privacy' => 'Filtro Privacy (Oscura CF, IBAN, Contatti)',
        'opt_fix_caps' => 'Correggi Maiuscole dopo il punto',
        'msg_privacy_warn' => 'Nota: Il filtro privacy è automatico, ricontrolla sempre il risultato.',
        'opt_no_conv' => '-- Nessuna conversione MAIUSCOLO/minuscolo --',
        'lbl_case_conv' => 'Conversione Maiuscole/Minuscole',
        'opt_latin' => 'Evidenzia Latinismi (_corsivo_)',
        'hint_latin' => 'Usa \"copia testo\" per: WhatsApp, Teams, Slack, Jira (formattazione Markdown). <br> Usa \"Copia Formattata\" per Word, Outlook, Gmail (formattazione HTML).',
        'copy_rich' => 'Copia Formattata',
        'copy_simple' => 'Copia Testo',
        'tip_copy_rich' => 'Mantiene la formattazione usando HTML/RTF. Ideale per Word, Outlook, Email.',
        'tip_copy_simple' => 'Copia il testo puro con la formattazione Markdown: *grassetto*, _corsivo_, ~barrato~ (ideale per WhatsApp, Teams, Slack, Jira).',
        'lbl_highlight_word' => 'Evidenzia Parola',
        'lbl_highlight_style' => 'Stile',
        'style_bold' => 'Grassetto (*txt*)',
        'style_italic' => 'Corsivo (_txt_)',
        'style_underline' => 'Sottolineato (solo HTML)',
        'style_strike' => 'Barrato (~txt~)',
        'style_uppercase' => 'TUTTO MAIUSCOLO',
        
        // -- TOOL: GESTIONE LISTE --
        'tool_lists' => 'Gestione Liste & Email',
        'desc_short_lists' => 'Formatta liste, estrae email e converte elenchi.',
        'desc_long_lists' => 'Strumenti per unire righe, dividere elenchi orizzontali ed estrarre indirizzi email da testi complessi.',
        'lbl_input_list' => 'Testo o Lista di Input',
        'ph_email_list' => 'mario.rossi@unipv.it&#10;luigi.verdi@unipv.it',
        'lbl_separator' => 'Separatore',
        'opt_comma' => ', (Gmail)',
        'opt_semicolon' => '; (Outlook)',
        'lbl_mode' => 'Modalità',
        'mode_join' => 'Unisci Righe (Excel -> Outlook)',
        'mode_split' => 'Dividi Elenco (Outlook -> Excel)',
        'mode_extract' => 'Estrai Email da Testo',
        'opt_auto' => 'Automatico ( , ; | )',
        
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
        'reset' => 'Reset',
        
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
        'lbl_sat_work' => 'Consider Saturday as working day',
        'lbl_sun_work' => 'Consider Sunday as working day',
        'lbl_skip_holidays' => 'Skip holidays and Easter Monday',
        'lbl_skip_dates' => 'Dates to skip (dd/mm/yyyy, one per line)',
        
        // -- TOOL: ENTRY & EXIT TIMES --
        'tool_times' => 'Entry & Exit Times',
        'desc_short_times' => 'Calculate end time from start and duration, or vice versa.',
        'desc_long_times' => 'Calculate end time or start time based on duration.',
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
        'lbl_add_days_date' => 'Add Days to Date (calculate effective deadline)',
        'lbl_days_to_add' => 'Days to add',
        'lbl_years' => 'Years',
        'lbl_months' => 'Months',
        'lbl_total_days' => 'Total days',
        'lbl_all_days' => 'counting all days',
        'lbl_use_holidays' => 'Exclude National Holidays & Easter Monday',
        'lbl_patron' => 'Patron Saint (dd/mm)',
        'lbl_closures' => 'University Closures (dd/mm/yyyy, one per line)',
        
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
        'lbl_iban_details' => 'Italian IBAN Breakdown',
        'lbl_bi_link' => 'Consult Registers on Bank of Italy',
        'lbl_bi_desc' => 'Consult the Registers and Lists on the GIAVA portal to check which bank the ABI code <strong>%s</strong> belongs to.',
        'btn_bi_open' => 'Open Registers and Lists',
        'iban_country' => 'Country',
        'iban_check' => 'Check',
        'iban_cin' => 'CIN',
        'iban_abi' => 'ABI',
        'iban_cab' => 'CAB',
        'iban_account' => 'Account',
        'iban_tooltip_country' => 'Country code (ISO 3166-1)',
        'iban_tooltip_check' => 'International check digits',
        'iban_tooltip_cin' => 'Control Internal Number: national check character',
        'iban_tooltip_abi' => 'ABI Code (Italian Banking Association): identifies the bank',
        'iban_tooltip_cab' => 'CAB Code (Branch Code): identifies the bank branch',
        'iban_tooltip_account' => 'Bank Account Number',
        
        // -- TOOL: TEXT SANITIZER --
        'tool_text' => 'Text Sanitizer',
        'desc_short_text' => 'Fix capitalization, spacing, and line breaks for clean text.',
        'desc_long_text' => 'Clean text from PDFs, fix caps and spacing.',
        'lbl_input_text' => 'Input Text',
        'opt_oneline' => 'Remove Newlines (All)',
        'opt_spaces' => 'Remove Double Spaces',
        'opt_title' => 'Title Case (Mario Rossi)',
        'opt_upper' => 'UPPERCASE',
        'opt_lower' => 'lowercase',
        'opt_smart_newline' => 'Smart Remove Newlines (exclude after period)',
        'opt_privacy' => 'Privacy Filter (Mask sensitive data)',
        'opt_fix_caps' => 'Fix Capitalization after period',
        'msg_privacy_warn' => 'Note: Privacy filter is automated, always review the output.',
        'opt_no_conv' => '-- No conversion UPPER/lower --',
        'lbl_case_conv' => 'Case Conversion',
        'opt_latin' => 'Highlight Latinisms (_italics_)',
        'hint_latin' => 'Use "Text Copy" for: WhatsApp, Teams, Slack, Jira (Markdown formatting). <br> Use "Formatted Copy" for Word, Outlook, Gmail (HTML formatting).',
        'copy_rich' => 'Formatted Copy',
        'copy_simple' => 'Text Copy',
        'tip_copy_rich' => 'Maintains formatting using HTML/RTF. Ideal for Word, Outlook, Email.',
        'tip_copy_simple' => 'Copies the plain text with Markdown formatting: *bold*, _italic_, ~strikethrough~ (ideal for WhatsApp, Teams, Slack, Jira).',
        'lbl_highlight_word' => 'Highlight Word',
        'lbl_highlight_style' => 'Style',
        'style_bold' => 'Bold (*txt*)',
        'style_italic' => 'Italic (_txt_)',
        'style_underline' => 'Underline (HTML only)',
        'style_strike' => 'Strikethrough (~txt~)',
        'style_uppercase' => 'UPPERCASE',
        
        // -- TOOL: LIST TOOLS --
        'tool_lists' => 'List & Email Tools',
        'desc_short_lists' => 'Format lists, extract emails and convert arrays.',
        'desc_long_lists' => 'Tools to join lines, split horizontal lists and extract email addresses from complex text.',
        'lbl_input_list' => 'Input Text or List',
        'ph_email_list' => 'john.doe@unipv.it&#10;jane.smith@unipv.it',
        'lbl_separator' => 'Separator',
        'opt_comma' => ', (Gmail)',
        'opt_semicolon' => '; (Outlook)',
        'lbl_mode' => 'Mode',
        'mode_join' => 'Join Lines (Excel -> Outlook)',
        'mode_split' => 'Split List (Outlook -> Excel)',
        'mode_extract' => 'Extract Emails from Text',
        'opt_auto' => 'Auto ( , ; | )',
        
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

/**
 * Verifica se una data è festiva (Nazionali, Pasquetta, Patrono, Chiusure personalizzate).
 * Checks if a date is a holiday (National, Easter Mon, Patron, Custom closures).
 */
function is_festivo($data, $patrono = '', $chiusure = []) {
    $d_mese = $data->format('d/m');      // es. 25/12
    $d_full = $data->format('d/m/Y');    // es. 25/12/2024
    $anno = $data->format('Y');
    
    // 1. Festività Nazionali Fisse
    $fissi = ['01/01', '06/01', '25/04', '01/05', '02/06', '15/08', '01/11', '08/12', '25/12', '26/12'];
    if (in_array($d_mese, $fissi)) return true;

    // 2. Santo Patrono (Input utente es. "09/12")
    if ($patrono && $d_mese === $patrono) return true;

    // 3. Chiusure Ateneo (Input utente es. "24/12/2024")
    if (in_array($d_full, $chiusure)) return true;

    // 4. Pasquetta (Calcolo dinamico)
    // easter_date richiede il timestamp Unix. Nota: funziona bene per anni 1970-2037 su sistemi 32bit, ok su 64bit.
    $pasqua_ts = easter_date($anno);
    $pasquetta = (new DateTime())->setTimestamp($pasqua_ts)->modify('+1 day');
    if ($d_full === $pasquetta->format('d/m/Y')) return true;

    return false;
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
            'times'      => ['type' => 'tool', 'key' => 'tool_times',      'desc_short' => 'desc_short_times',      'desc_long' => 'desc_long_times',      'func' => 'visualizza_times',      'proc' => 'processa_times'],
            'recuperi'   => ['type' => 'tool', 'key' => 'tool_recuperi',   'desc_short' => 'desc_short_recuperi',   'desc_long' => 'desc_long_recuperi',   'func' => 'visualizza_recuperi',   'proc' => 'processa_recuperi'],
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
            'lists' => ['type' => 'tool', 'key' => 'tool_lists','desc_short' => 'desc_short_lists','desc_long' => 'desc_long_lists','func' => 'visualizza_lists', 'proc' => 'processa_lists'],
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

    // Trova la funzione di elaborazione dal catalogo in base all'azione inviata.
    // Find the processing function from the catalog based on the submitted action.
    $funzione_processore = null;
    $azione = $_POST['action'] ?? '';
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
    $saldo_minuti = ((int)$_POST['saldo_h'] * 60) + (int)$_POST['saldo_m'];
    $orario_settimanale = [];
    for($giorno=1; $giorno<=7; $giorno++) {
        $val = $_POST["day_$giorno"] ?? '0:00';
        list($ore_giorno, $min_giorno) = explode(':', $val);
        $orario_settimanale[$giorno] = ($ore_giorno * 60) + $min_giorno;
    }

    $sat_work = isset($_POST['sat_work']);
    $sun_work = isset($_POST['sun_work']);
    $skip_holidays = isset($_POST['use_holidays']);
    $patrono = trim($_POST['patron_day'] ?? '');
    $custom_closures = [];
    if (!empty($_POST['ateneo_closures'])) {
        foreach(explode("\n", $_POST['ateneo_closures']) as $r) {
            $r = trim($r);
            if($r) $custom_closures[] = $r;
        }
    }

    $giorni_coperti = 0;

    try {
        $data = new DateTime($_POST['start_date']);
        $data_inizio = clone $data;
        $data_finale = clone $data; // Conterrà l'ultimo giorno coperto / Will hold the last covered day
        
        while ($saldo_minuti > 0) {
            $giorno_settimana = $data->format('N'); // 1 (per Lunedì) a 7 (per Domenica) / 1 (for Monday) through 7 (for Sunday)
            
            // Gestione Weekend / Weekend handling
            if ($giorno_settimana == 6 && !$sat_work) { $data->modify('+1 day'); continue; }
            if ($giorno_settimana == 7 && !$sun_work) { $data->modify('+1 day'); continue; }

            // Gestione Festività / Holidays handling
            $is_holiday = false;
            if ($skip_holidays) {
                if (is_festivo($data, $patrono, $custom_closures)) $is_holiday = true;
            } else {
                if (in_array($data->format('d/m/Y'), $custom_closures)) $is_holiday = true;
            }

            if ($is_holiday) {
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
 * Elabora l'invio del form "Orari Entrata e Uscita".
 * Calcola un orario di fine da un orario di inizio e durata, o viceversa.
 * 
 * Processes the "Entry & Exit Times" form submission.
 * It calculates an end time from a start time and duration, or vice-versa.
 * 
 * @return array Un array con data/ora calcolati. / An array with the calculated date/time.
 */
function processa_times() {
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
    $mode = $_POST['mode'] ?? 'diff';
    $map = [1=>traduci('day_mon'), 2=>traduci('day_tue'), 3=>traduci('day_wed'), 4=>traduci('day_thu'), 5=>traduci('day_fri'), 6=>traduci('day_sat'), 7=>traduci('day_sun')];

    if ($mode === 'add') {
        try {
            $curr = new DateTime($_POST['d_start']);
            $days_to_add = (int)$_POST['days_add'];
            
            // Opzioni avanzate
            $only_working = isset($_POST['only_working_add']); // Sab-Dom
            $use_holidays = isset($_POST['use_holidays']);     // Festivi
            $patrono = trim($_POST['patron_day'] ?? '');
            
            // Parsing chiusure ateneo (una per riga)
            $chiusure_raw = explode("\n", $_POST['ateneo_closures'] ?? '');
            $chiusure = [];
            foreach($chiusure_raw as $c) {
                $c = trim($c);
                if($c) $chiusure[] = $c;
            }

            for ($i = 0; $i < $days_to_add; $i++) {
                // Aggiunge 1 giorno solare
                $curr->modify('+1 day');
                
                // Se dobbiamo saltare i festivi/weekend, continuiamo ad avanzare finché non troviamo un giorno valido
                if ($only_working) {
                    // Finché è Sab(6)/Dom(7) OPPURE è festivo (se opzione attiva)
                    while (
                        $curr->format('N') >= 6 || 
                        ($use_holidays && is_festivo($curr, $patrono, $chiusure))
                    ) {
                        $curr->modify('+1 day');
                    }
                }
            }
            
            $desc = $only_working ? traduci('lbl_calc_working') : traduci('lbl_all_days');
            if ($use_holidays && $only_working) $desc .= " + " . traduci('lbl_use_holidays');

            return ['main' => $curr->format('d/m/Y'), 'sub' => $map[$curr->format('N')] . " ($desc)"];
        } catch(Exception $e) { return ['main' => 'Error']; }
    } else {
        // Logica differenza date (invariata o leggera pulizia)
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
    
    // Validazione matematica Modulo 97
    $controllo = substr($iban, 4) . substr($iban, 0, 4);
    $iban_num = '';
    foreach (str_split($controllo) as $c) { $iban_num .= is_numeric($c) ? $c : (ord($c) - 55); }
    $resto = '0';
    for ($i = 0; $i < strlen($iban_num); $i++) { $resto = ($resto . $iban_num[$i]) % 97; }
    
    $e_valido = ($resto == 1 && strlen($iban) >= 15 && strlen($iban) <= 34);
    
    $ris = [
        'main' => $e_valido ? traduci('msg_iban_ok') : traduci('msg_iban_ko'), 
        'color' => $e_valido ? 'green' : 'red',
        'valido' => $e_valido
    ];

    // Se valido e Italiano (27 caratteri), scomponi
    if ($e_valido && substr($iban, 0, 2) === 'IT' && strlen($iban) === 27) {
        $ris['parti'] = [
            'paese' => substr($iban, 0, 2),
            'check' => substr($iban, 2, 2),
            'cin'   => substr($iban, 4, 1),
            'abi'   => substr($iban, 5, 5),
            'cab'   => substr($iban, 10, 5),
            'conto' => substr($iban, 15)
        ];
    }
    return $ris;
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
    $t = $_POST['text_in'];
    // Ora gestiamo un array di opzioni (checkbox)
    $ops = $_POST['ops'] ?? []; 
    // Compatibilità se arriva ancora come stringa singola
    if (!is_array($ops) && !empty($ops)) $ops = [$ops];

    // 1. PIPELINE: Smart Newline (Unisci righe spezzate)
    if (in_array('smart_newline', $ops)) {
        // Sostituisce a capo con spazio SOLO se NON preceduto da punto (o punto e spazio)
        // Negative Lookbehind: (?<!...)
        $t = preg_replace('/(?<!\.|\. )\r?\n/', ' ', $t);
    } elseif (in_array('oneline', $ops)) {
        // Metodo classico brutale
        $t = str_replace(["\r", "\n"], ' ', $t);
    }

    // 2. PIPELINE: Filtro Privacy
    if (in_array('privacy', $ops)) {
        $patterns = [
            '/[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]/i', // Codice Fiscale
            '/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i',  // Email
            '/IT\d{2}[A-Z]\d{10}[A-Z0-9]{12}/i',         // IBAN IT
            '/(?:\+|00)39[\s\.]?[0-9]{3}[\s\.]?[0-9]{6,7}/', // Cellulari IT generico
            '/@[\w\.]+/',                                // Nickname (@user)
        ];
        $t = preg_replace($patterns, '[OMISSIS]', $t);
    }

    // 3. PIPELINE: Normalizzazione Spazi
    if (in_array('spaces', $ops)) {
        $t = preg_replace('/[ \t]+/', ' ', $t);
    }

    // 4. PIPELINE: Case Transformation (Maiuscolo/Minuscolo/Titolo)
    // Nota: L'utente ne può scegliere solo una dal select finale, che aggiungiamo manualmente all'array ops se presente
    if (in_array('upper', $ops)) $t = mb_strtoupper($t, "UTF-8");
    elseif (in_array('lower', $ops)) $t = mb_strtolower($t, "UTF-8");
    elseif (in_array('title', $ops)) $t = mb_convert_case($t, MB_CASE_TITLE, "UTF-8");
    $case_type = $_POST['case_type'] ?? '';
    if (in_array('convert_case', $ops) && !empty($case_type)) {
        if ($case_type === 'upper') $t = mb_strtoupper($t, "UTF-8");
        elseif ($case_type === 'lower') $t = mb_strtolower($t, "UTF-8");
        elseif ($case_type === 'title') $t = mb_convert_case($t, MB_CASE_TITLE, "UTF-8");
    }

    // 5. PIPELINE: Fix Caps (Dopo il punto)
    // Eseguiamo alla fine per correggere eventuali minuscole rimaste dopo lo smart newline
    if (in_array('fix_caps', $ops)) {
        // Cerca punto seguito da spazi e una lettera minuscola
        $t = preg_replace_callback('/(\.\s+)([a-z])/', function($m) {
            return $m[1] . strtoupper($m[2]);
        }, $t);
    }

    $t_html = htmlspecialchars($t);

    // 6. PIPELINE: Latinismi (Markdown Italics)
    // Eseguiamo alla fine per non interferire con Fix Caps o altre trasformazioni
    if (in_array('latin', $ops)) {
        $latin_phrases = [
            'ab eterno', 'ab immemorabili', 'ab imo pectore', 'ab initio', 'ab intestato', 'ab irato', 'ab origine', 'ab ovo', 
            'absit iniuria verbis', 'ad absurdum', 'ad acta', 'ad adiuvandum', 'ad arbitrium', 'ad augusta per angusta', 
            'ad captandum vulgus', 'ad diem', 'ad exemplum', 'ad hoc', 'ad honorem', 'ad infinitum', 'ad interim', 
            'ad libitum', 'ad limina', 'ad literam', 'ad litteram', 'ad maiora', 'ad meliora', 'ad memoriam', 'ad metalla', 
            'ad multos annos', 'ad nauseam', 'ad nutum', 'ad oculos', 'ad pedem litterae', 'ad perpetuam rei memoriam', 
            'ad personam', 'ad probandum', 'ad quem', 'ad referendum', 'ad rem', 'ad usum delphini', 'ad valorem', 
            'ad verecundiam', 'ad vitam aeternam', 'addenda', 'a fortiori', 'a latere', 'alias', 'alibi', 'alma mater', 
            'alter ego', 'amicus curiae', 'ante litteram', 'ante meridiem', 'a posteriori', 'a priori', 'a quo', 
            'argumentum ad hominem', 'ars gratia artis', 'a simili', 'audere semper', 'aurea mediocritas', 'aut aut', 
            'ave atque vale', 'bona fide', 'brevis', 'calamo currente', 'caput mundi', 'carpe diem', 'casus belli', 
            'caveat emptor', 'ceteris paribus', 'circa', 'cogito ergo sum', 'compos sui', 'conditio sine qua non', 
            'confer', 'contra legem', 'coram populo', 'corpus delicti', 'credo quia absurdum', 'cui prodest', 
            'cuique suum', 'cum grano salis', 'cum laude', 'curriculum vitae', 'de auditu', 'de cetera', 'de cuius', 
            'de facto', 'de gustibus non est disputandum', 'de iure', 'de jure', 'de minimis', 'de plano', 'de profundis', 
            'de relato', 'de visu', 'deo gratias', 'desiderata', 'deus ex machina', 'dies a quo', 'dies ad quem', 
            'dies irae', 'divide et impera', 'do ut des', 'doctus cum libro', 'dulcis in fundo', 'dum roma consulitur', 
            'dura lex sed lex', 'ecce homo', 'editio princeps', 'eiusdem furfuris', 'erga omnes', 'ergo', 'errata corrige', 
            'et alii', 'et cetera', 'et similia', 'ex abrupto', 'ex adverso', 'ex aequo', 'ex ante', 'ex cathedra', 
            'excusatio non petita', 'excursus', 'exempli gratia', 'exeunt', 'ex grege', 'ex lege', 'ex libris', 
            'ex nihilo', 'ex novo', 'ex nunc', 'ex officio', 'ex opere operato', 'ex parte', 'ex post', 'ex professo', 
            'ex tunc', 'ex voto', 'fac simile', 'facsimile', 'fiat lux', 'fiat voluntas dei', 'forma mentis', 'forum', 
            'gaudeamus', 'gratis', 'grosso modo', 'habeas corpus', 'hic et nunc', 'hic manebimus optime', 'hic sunt leones', 
            'hoc opus hic labor', 'homo homini lupus', 'honoris causa', 'horribile dictu', 'horror vacui', 'ibidem', 
            'idem', 'id est', 'ignorantia legis non excusat', 'imprimatur', 'in absentia', 'in abstracto', 'in aeternum', 
            'in albis', 'in alto loco', 'in anima vili', 'in articulo mortis', 'in bonis', 'in camera', 'in cauda venenum', 
            'incipit', 'in corpore vili', 'in dubio pro reo', 'in extenso', 'in extremis', 'in fieri', 'in hoc signo vinces', 
            'in illo tempore', 'in itinere', 'in limine', 'in loco', 'in media res', 'in medias res', 'in memoriam', 
            'in naturalibus', 'in nuce', 'in pectore', 'in peius', 'in perpetuum', 'in primis', 'in re ipsa', 'in rem', 
            'in saecula saeculorum', 'in situ', 'in solido', 'in spe', 'in toto', 'intelligenti pauca', 'inter alia', 
            'interim', 'inter nos', 'inter pares', 'inter partes', 'inter vivos', 'in utroque iure', 'in vacuo', 
            'in vino veritas', 'in vitro', 'in vivo', 'ipse dixit', 'ipsissima verba', 'ipso facto', 'ipso iure', 
            'ipso jure', 'item', 'iura novit curia', 'iure sanguinis', 'iure soli', 'ius primae noctis', 'ius sanguinis', 
            'ius soli', 'lapsus', 'lato sensu', 'lectio magistralis', 'lex specialis', 'loco citato', 'lupus in fabula', 
            'magister dixit', 'magna charta', 'magna cum laude', 'mala fide', 'mala tempora currunt', 'manu militari', 
            'mare magnum', 'margaritas ante porcos', 'maxima debetur puero reverentia', 'mea culpa', 'memento mori', 
            'memorandum', 'mens sana in corpore sano', 'minus habens', 'modus operandi', 'modus vivendi', 'more solito', 
            'more uxorio', 'mors tua vita mea', 'motu proprio', 'mutatis mutandis', 'natu', 'ne bis in idem', 
            'nec recisa recedit', 'nemine contradicente', 'nemo propheta in patria', 'ne varietur', 'nihil obstat', 
            'nihil sub sole novum', 'nolens volens', 'noli me tangere', 'nomen omen', 'non expedit', 'non olet', 
            'non plus ultra', 'non possumus', 'non sequitur', 'nosce te ipsum', 'nota bene', 'nulla osta', 
            'nulla poena sine lege', 'nullum crimen sine lege', 'numerus clausus', 'obiter dictum', 'obtorto collo', 
            'omen', 'omnia munda mundis', 'omnia vincit amor', 'onus probandi', 'ope legis', 'opera omnia', 'opere citato', 
            'opus', 'ora et labora', 'ora pro nobis', 'o tempora o mores', 'pacta sunt servanda', 'panem et circenses', 
            'par condicio', 'passim', 'pater familias', 'patria potestas', 'peccatum originale', 'per aspera ad astra', 
            'per capita', 'per diem', 'per intervalla insaniae', 'per se', 'per tabulas', 'persona non grata', 'placebo', 
            'plenus venter non studet libenter', 'plus', 'post eventum', 'post factum', 'post hoc ergo propter hoc', 
            'post meridiem', 'post mortem', 'post partum', 'post scriptum', 'prima facie', 'primum movens', 
            'primum non nocere', 'primus inter pares', 'pro bono', 'pro capite', 'pro die', 'pro domo sua', 'pro forma', 
            'pro indiviso', 'pro loco', 'pro memoria', 'pro rata', 'pro tempore', 'pro veritate', 'punctum dolens', 
            'quaestio', 'quantum', 'qui pro quo', 'quid', 'quid pluris', 'quid pro quo', 'quorum', 'rara avis', 'ratio', 
            'ratio decidendi', 'ratio legis', 'rebus sic stantibus', 'rectius', 'referendum', 'relata refero', 
            'repetita iuvant', 'requiem', 'res gestae', 'res nullius', 'res publica', 'rigor mortis', 'semel in anno', 
            'semper fidelis', 'sensu lato', 'sensu stricto', 'sic', 'sic et simpliciter', 'sic itur ad astra', 
            'sic stantibus rebus', 'sic transit gloria mundi', 'similia similibus curantur', 'sine cura', 'sine die', 
            'sine ira et studio', 'sine loco', 'sine nobilitate', 'sine qua non', 'sit venia verbo', 'sol lucet omnibus', 
            'specimen', 'spes ultima dea', 'sponte sua', 'statu quo', 'status quo', 'stricto sensu', 'sub condicione', 
            'sub iudice', 'sub judice', 'sub specie aeternitatis', 'sui generis', 'summa cum laude', 'summa iniuria', 
            'summum ius', 'super partes', 'sursum corda', 'tabula rasa', 'tantundem', 'te deum', 'tempus fugit', 
            'terminus ad quem', 'terminus ante quem', 'terminus a quo', 'terminus post quem', 'tertium non datur', 
            'tot capita tot sententiae', 'totus tuus', 'tout court', 'ubi maior minor cessat', 'ubi mel ibi apes', 'ultima ratio', 
            'ultimatum', 'una tantum', 'urbi et orbi', 'usus', 'uti singuli', 'vacatio legis', 'vade mecum', 'vademecum', 
            'vae victis', 'vanitas vanitatum', 'variatio delectat', 'veluti', 'veni vidi vici', 'verba volant scripta manent', 
            'verbatim', 'versus', 'vexata quaestio', 'via', 'via crucis', 'vice versa', 'vide', 'vis', 'vis comica', 
            'vis maior', 'vis polemica', 'vita naturalis durante', 'viva vox', 'vox populi', 'vox populi vox dei', 'vulgo'
        ];
        
        // Ordina per lunghezza decrescente per evitare match parziali
        usort($latin_phrases, function($a, $b) { return strlen($b) - strlen($a); });
        
        // Crea pattern regex con word boundaries (\b)
        $pattern = '/\b(' . implode('|', array_map(function($s){ return preg_quote($s, '/'); }, $latin_phrases)) . ')\b/i';
        $t = preg_replace($pattern, '_$0_', $t);
        $t_html = preg_replace($pattern, '<i>$0</i>', $t_html);
    }

    // 7. PIPELINE: Custom Highlighting (Evidenziazione parola specifica)
    if (in_array('highlight', $ops)) {
        $hl_words = $_POST['highlight_word'] ?? [];
        $hl_styles = $_POST['highlight_style'] ?? [];
        
        // Normalizza in array se arriva stringa singola (compatibilità)
        if (!is_array($hl_words)) $hl_words = [$hl_words];
        if (!is_array($hl_styles)) $hl_styles = [$hl_styles];

        $map_raw = [
            'bold' => '*$0*',
            'italic' => '_$0_',
            'underline' => '$0', 
            'strike' => '~$0~'
        ];
        
        $map_html = [
            'bold' => '<b>$0</b>',
            'italic' => '<i>$0</i>',
            'underline' => '<u>$0</u>',
            'strike' => '<s>$0</s>'
        ];
        
        for ($i = 0; $i < count($hl_words); $i++) {
            $hl_word = trim($hl_words[$i] ?? '');
            $hl_style = $hl_styles[$i] ?? 'bold';
            
            if (!empty($hl_word)) {
                // Escape regex characters in the word
                $pattern = '/\b' . preg_quote($hl_word, '/') . '\b/i';
                
                if (isset($map_raw[$hl_style])) {
                    if ($hl_style === 'uppercase') {
                    $callback = function($m) { return mb_strtoupper($m[0], 'UTF-8'); };
                    $t = preg_replace_callback($pattern, $callback, $t);
                    $t_html = preg_replace_callback($pattern, $callback, $t_html);
                } elseif (isset($map_raw[$hl_style])) {
                    $t = preg_replace($pattern, $map_raw[$hl_style], $t);
                    $t_html = preg_replace($pattern, $map_html[$hl_style], $t_html);
                }
                }
            }
        }
    }

    // Converte newlines in <br> per la copia HTML
    $t_html = nl2br($t_html);

    return ['raw' => trim($t), 'html' => $t_html];
}

/**
 * Elabora l'invio del form "Gestione Liste".
 * Gestisce unione, divisione ed estrazione di liste ed email.
 * 
 * Processes the "List Tools" form submission.
 * Handles joining, splitting, and extracting lists and emails.
 * 
 * @return array Un array con il risultato formattato. / An array with the formatted result.
 */
function processa_lists() {
    $text = $_POST['list_input'] ?? '';
    $mode = $_POST['mode'] ?? 'join';
    $sep_type = $_POST['separator'] ?? 'comma';
    
    $result = '';
    
    if ($mode === 'join') {
        // Vertical -> Horizontal
        $sep = ($sep_type == 'semicolon') ? '; ' : ', ';
        $lines = preg_split("/\r\n|\n|\r/", $text);
        $clean = array_filter(array_map('trim', $lines));
        $result = implode($sep, $clean);
    } 
    elseif ($mode === 'split') {
        // Horizontal -> Vertical
        $pattern = '/\s*[,;|\t]\s*/'; // Default Auto
        if ($sep_type === 'comma') $pattern = '/\s*,\s*/';
        if ($sep_type === 'semicolon') $pattern = '/\s*;\s*/';
        
        $items = preg_split($pattern, $text, -1, PREG_SPLIT_NO_EMPTY);
        $result = implode("\n", array_map('trim', $items));
    }
    elseif ($mode === 'extract') {
        // Extract Emails
        $pattern = '/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i';
        preg_match_all($pattern, $text, $matches);
        $emails = array_unique($matches[0]);
        $result = implode("\n", $emails);
        if (empty($result)) $result = "Nessuna email trovata / No emails found.";
    }
    
    return ['raw' => $result];
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
        .res-raw { background: #f9fafb; padding: 15px; border-radius: 6px; font-family: monospace; word-break: break-all; margin-top: 5px; white-space: pre-wrap; text-align: left; }
        
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

        /* TOOLTIP STYLES */
        .tooltip { position: relative; display: inline-block; cursor: help; margin-left: 5px; color: var(--primary); }
        .tooltip .tooltiptext { visibility: hidden; width: 220px; background-color: #333; color: #fff; text-align: center; border-radius: 6px; padding: 10px; position: absolute; z-index: 100; bottom: 135%; left: 50%; margin-left: -110px; opacity: 0; transition: opacity 0.3s; font-size: 11px; font-weight: normal; line-height: 1.4; pointer-events: none; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .tooltip:hover .tooltiptext { visibility: visible; opacity: 1; }
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
 * Copia il contenuto HTML di un elemento negli appunti (Rich Text).
 * Copies the HTML content of an element to the clipboard (Rich Text).
 */
function copyHtml(elementId) {
    var element = document.getElementById(elementId);
    if (!element) return;

    var tempEl = document.createElement("div");
    tempEl.contentEditable = true;
    tempEl.innerHTML = element.innerHTML;
    tempEl.style.position = "fixed";
    tempEl.style.left = "-9999px";
    document.body.appendChild(tempEl);

    var range = document.createRange();
    range.selectNodeContents(tempEl);
    var sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);

    try {
        document.execCommand('copy');
        alert('<?php echo traduci('copied'); ?>');
    } catch (err) {
        console.error('Errore copia HTML:', err);
        alert('Errore durante la copia.');
    }
    document.body.removeChild(tempEl);
    sel.removeAllRanges();
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
    global $info_strumento_corrente, $id_strumento_corrente;
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
                <input type="number" name="h_start[]" min="0" max="23" maxlength="2" placeholder="HH" value="<?php echo $ora_i; ?>" class="autotab" required> :
                <input type="number" name="m_start[]" min="0" max="59" maxlength="2" placeholder="MM" value="<?php echo $min_i; ?>" class="autotab">
                
                <span style="font-size:12px; width:30px; font-weight:bold; text-align:right"><?php echo traduci('lbl_to'); ?>:</span>
                <input type="number" name="h_end[]" min="0" max="23" maxlength="2" placeholder="HH" value="<?php echo $ora_f; ?>" class="autotab" required> :
                <input type="number" name="m_end[]" min="0" max="59" maxlength="2" placeholder="MM" value="<?php echo $min_f; ?>" class="autotab">
                <button type="button" onclick="rimuoviRigaIntervallo(this)" style="background:none; border:none; color:#ef4444; cursor:pointer; font-weight:bold; font-size:18px; padding:0 5px; margin-left:5px;" title="Rimuovi riga">&times;</button>
            </div>
            <?php endfor; ?>
        </div>
        
        <button type="button" onclick="aggiungiRigaIntervallo()" style="background:#f3f4f6; color:#374151; border:1px solid #d1d5db; padding:8px; width:100%; border-radius:6px; margin-top:10px; cursor:pointer">+ <?php echo traduci('lbl_hours'); ?></button>
        <div style="display:flex; gap:10px; margin-top:15px;">
            <a href="<?php echo ottieniUrl($id_strumento_corrente); ?>" class="btn" style="background:#9ca3af; width:auto; margin:0;"><?php echo traduci('reset'); ?></a>
            <button type="submit" class="btn" style="margin:0; flex:1;"><?php echo traduci('calc'); ?></button>
        </div>

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

        function rimuoviRigaIntervallo(btn) {
            var rows = document.querySelectorAll('.row-inputs');
            if (rows.length > 1) {
                btn.closest('.row-inputs').remove();
            } else {
                btn.closest('.row-inputs').querySelectorAll('input').forEach(i => i.value = '');
            }
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
    global $info_strumento_corrente, $id_strumento_corrente;
    $saldo_ore = isset($_POST['saldo_h']) ? htmlspecialchars($_POST['saldo_h']) : '';
    $saldo_min = isset($_POST['saldo_m']) ? htmlspecialchars($_POST['saldo_m']) : '';
    $data_inizio = isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : date('Y-m-d');
    $sat_checked = isset($_POST['sat_work']) ? 'checked' : '';
    $sun_checked = isset($_POST['sun_work']) ? 'checked' : '';
    $skip_holidays_checked = ($_SERVER["REQUEST_METHOD"] != "POST" || isset($_POST['use_holidays'])) ? 'checked' : '';
    $patrono = isset($_POST['patron_day']) ? htmlspecialchars($_POST['patron_day']) : '09/12';
    $chiusure = isset($_POST['ateneo_closures']) ? htmlspecialchars($_POST['ateneo_closures']) : '';
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
                    <input type="number" name="saldo_h" placeholder="HH" required value="<?php echo $saldo_ore; ?>" class="autotab" maxlength="2">
                    <input type="number" name="saldo_m" placeholder="MM" value="<?php echo $saldo_min; ?>" class="autotab" maxlength="2">
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
        
        <div style="margin-bottom:15px; display:flex; gap:15px; flex-wrap:wrap;">
            <label style="font-weight:400; cursor:pointer; display:flex; align-items:center; gap:5px;">
                <input type="checkbox" name="sat_work" id="chk_sat" <?php echo $sat_checked; ?> onchange="toggleWeekendDays()"> <?php echo traduci('lbl_sat_work'); ?>
            </label>
            <label style="font-weight:400; cursor:pointer; display:flex; align-items:center; gap:5px;">
                <input type="checkbox" name="sun_work" id="chk_sun" <?php echo $sun_checked; ?> onchange="toggleWeekendDays()"> <?php echo traduci('lbl_sun_work'); ?>
            </label>
        </div>

        <?php 
        $giorni = [
            1 => traduci('day_mon'), 2 => traduci('day_tue'), 3 => traduci('day_wed'), 
            4 => traduci('day_thu'), 5 => traduci('day_fri'), 
            6 => traduci('day_sat'), 7 => traduci('day_sun')
        ];
        $opzioni = ['7:12'=>'7h 12m', '8:00'=>'8h 00m', '9:00'=>'9h 00m', '6:00'=>'6h 00m', '4:00'=>'4h 00m'];
        
        foreach($giorni as $indice => $g): 
            $display_style = ($indice >= 6) ? 'display:none' : 'display:flex';
            // Se è stato postato ed era attivo, mantieni visibile / If posted and active, keep visible
            if ($indice == 6 && $sat_checked) $display_style = 'display:flex';
            if ($indice == 7 && $sun_checked) $display_style = 'display:flex';
        ?>
            <div id="row_day_<?php echo $indice; ?>" style="<?php echo $display_style; ?>; align-items:center; justify-content:space-between; margin-bottom:8px; font-size:14px;">
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

        <div style="background:#f9fafb; padding:15px; border-radius:8px; margin-top:15px; border:1px solid #e5e7eb;">
            <label style="margin-bottom:15px; display:flex; align-items:center; gap:8px; cursor:pointer; color:#b45309;">
                <input type="checkbox" name="use_holidays" <?php echo $skip_holidays_checked; ?>> 
                <?php echo traduci('lbl_use_holidays'); ?>
            </label>
            
            <div style="display:flex; gap:15px; flex-wrap:wrap;">
                <div style="flex:1; min-width:150px;">
                    <label style="font-size:11px; text-transform:uppercase; color:#6b7280;"><?php echo traduci('lbl_patron'); ?></label>
                    <input type="text" name="patron_day" value="<?php echo $patrono; ?>" placeholder="09/12">
                </div>
                <div style="flex:2; min-width:200px;">
                    <label style="font-size:11px; text-transform:uppercase; color:#6b7280;"><?php echo traduci('lbl_skip_dates'); ?></label>
                    <textarea name="ateneo_closures" rows="1" placeholder="24/12/2024" style="resize:vertical; min-height:38px;"><?php echo $chiusure; ?></textarea>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:15px;">
            <a href="<?php echo ottieniUrl($id_strumento_corrente); ?>" class="btn" style="background:#9ca3af; width:auto; margin:0;"><?php echo traduci('reset'); ?></a>
            <button type="submit" class="btn" style="margin:0; flex:1;"><?php echo traduci('calc'); ?></button>
        </div>

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
        <?php endif; ?>
    </form>
    <script>
    function toggleWeekendDays() {
        var sat = document.getElementById('chk_sat').checked;
        var sun = document.getElementById('chk_sun').checked;
        document.getElementById('row_day_6').style.display = sat ? 'flex' : 'none';
        document.getElementById('row_day_7').style.display = sun ? 'flex' : 'none';
    }
    </script>
    <?php
}

/**
 * Renderizza l'interfaccia dello strumento "Orari Entrata e Uscita".
 * Renders the "Entry & Exit Times" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_times($risultato) {
    global $info_strumento_corrente, $id_strumento_corrente;
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
        <input type="hidden" name="action" value="times">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_times'); ?></div>
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
                        <input type="number" name="start_h" placeholder="<?php echo traduci('ph_hh'); ?>" value="<?php echo $ora_inizio; ?>" class="autotab" maxlength="2"> :
                        <input type="number" name="start_m" placeholder="<?php echo traduci('ph_mm'); ?>" value="<?php echo $min_inizio; ?>" class="autotab" maxlength="2">
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
                <input type="number" name="dur_h" value="<?php echo $ore_durata; ?>" class="autotab" maxlength="2"> h
                <input type="number" name="dur_m" value="<?php echo $min_durata; ?>" class="autotab" maxlength="2"> m
            </div>
        </div>
        
        <div style="margin-bottom:15px;">
            <label><?php echo traduci('lbl_pause'); ?></label>
            <div style="display:flex; gap:10px; align-items:center;">
                <input type="number" name="pau_h" value="<?php echo $ore_pausa; ?>" class="autotab" maxlength="2"> h
                <input type="number" name="pau_m" value="<?php echo $min_pausa; ?>" class="autotab" maxlength="2"> m
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:15px;">
            <a href="<?php echo ottieniUrl($id_strumento_corrente); ?>" class="btn" style="background:#9ca3af; width:auto; margin:0;"><?php echo traduci('reset'); ?></a>
            <button type="submit" class="btn" style="margin:0; flex:1;"><?php echo traduci('calc'); ?></button>
        </div>
        
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
    global $info_strumento_corrente, $id_strumento_corrente;
    $data1 = $_POST['d1'] ?? '';
    $data2 = $_POST['d2'] ?? '';
    $d_start = $_POST['d_start'] ?? '';
    $days_add = $_POST['days_add'] ?? '';
    $patrono = $_POST['patron_day'] ?? '09/12'; // Default Pavia San Siro
    $chiusure = $_POST['ateneo_closures'] ?? '';

    // Defaults
    $chk_working_diff = ($_SERVER["REQUEST_METHOD"] != "POST" || isset($_POST['only_working_diff'])) ? 'checked' : '';
    $chk_working_add = ($_SERVER["REQUEST_METHOD"] != "POST" || isset($_POST['only_working_add'])) ? 'checked' : '';
    $chk_holidays = ($_SERVER["REQUEST_METHOD"] != "POST" || isset($_POST['use_holidays'])) ? 'checked' : '';
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
                    <button type="button" class="btn" style="margin:0; width:auto; padding:0 15px; background:#f3f4f6; color:#374151;" onclick="document.getElementById('d1').value = new Date().toISOString().slice(0,10);"><?php echo traduci('lbl_today'); ?></button>
                </div>
            </div>
            <div style="flex:1">
                <label><?php echo traduci('lbl_end_date'); ?></label>
                <div style="display:flex; gap:5px;">
                    <input type="date" name="d2" id="d2" value="<?php echo $data2; ?>" style="flex:1">
                    <button type="button" class="btn" style="margin:0; width:auto; padding:0 15px; background:#f3f4f6; color:#374151;" onclick="document.getElementById('d2').value = new Date().toISOString().slice(0,10);"><?php echo traduci('lbl_today'); ?></button>
                </div>
            </div>
        </div>
        <label style="margin-top:10px; display:flex; align-items:center; gap:8px; font-weight:normal; cursor:pointer">
            <input type="checkbox" name="only_working_diff" <?php echo $chk_working_diff; ?>> <?php echo traduci('lbl_calc_working'); ?>
        </label>
        <div style="display:flex; gap:10px; margin-top:15px;">
            <a href="<?php echo ottieniUrl($id_strumento_corrente); ?>" class="btn" style="background:#9ca3af; width:auto; margin:0;"><?php echo traduci('reset'); ?></a>
            <button type="submit" name="mode" value="diff" class="btn" style="margin:0; flex:1;"><?php echo traduci('calc'); ?></button>
        </div>

        <h3 style="font-size:16px; margin-top:40px; border-bottom:1px solid #eee; padding-bottom:5px; color:var(--primary)"><?php echo traduci('lbl_add_days_date'); ?></h3>
        <div class="tab-group" style="background:none; padding:0; gap:20px;">
            <div style="flex:1">
                <label><?php echo traduci('lbl_start_date'); ?></label>
                <div style="display:flex; gap:5px;">
                    <input type="date" name="d_start" id="d_start" value="<?php echo $d_start; ?>" style="flex:1">
                    <button type="button" class="btn" style="margin:0; width:auto; padding:0 15px; background:#f3f4f6; color:#374151;" onclick="document.getElementById('d_start').value = new Date().toISOString().slice(0,10);"><?php echo traduci('lbl_today'); ?></button>
                </div>
            </div>
            <div style="flex:1">
                <label><?php echo traduci('lbl_days_to_add'); ?></label>
                <input type="number" name="days_add" value="<?php echo $days_add; ?>" placeholder="00">
            </div>
        </div>
        
        <div style="background:#f9fafb; padding:15px; border-radius:8px; margin-top:15px; border:1px solid #e5e7eb;">
            <label style="margin-bottom:10px; display:flex; align-items:center; gap:8px; cursor:pointer">
                <input type="checkbox" name="only_working_add" <?php echo $chk_working_add; ?>> 
                <?php echo traduci('lbl_calc_working'); ?>
            </label>
            
            <label style="margin-bottom:15px; display:flex; align-items:center; gap:8px; cursor:pointer; color:#b45309;">
                <input type="checkbox" name="use_holidays" <?php echo $chk_holidays; ?>> 
                <?php echo traduci('lbl_use_holidays'); ?>
            </label>

            <div style="display:flex; gap:15px; flex-wrap:wrap;">
                <div style="flex:1; min-width:150px;">
                    <label style="font-size:11px; text-transform:uppercase; color:#6b7280;"><?php echo traduci('lbl_patron'); ?></label>
                    <input type="text" name="patron_day" value="<?php echo $patrono; ?>" placeholder="09/12">
                </div>
                <div style="flex:2; min-width:200px;">
                    <label style="font-size:11px; text-transform:uppercase; color:#6b7280;"><?php echo traduci('lbl_closures'); ?></label>
                    <textarea name="ateneo_closures" rows="1" placeholder="24/12/2024" style="resize:vertical; min-height:38px;"><?php echo $chiusure; ?></textarea>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:15px;">
            <a href="<?php echo ottieniUrl($id_strumento_corrente); ?>" class="btn" style="background:#9ca3af; width:auto; margin:0;"><?php echo traduci('reset'); ?></a>
            <button type="submit" name="mode" value="add" class="btn" style="margin:0; flex:1;"><?php echo traduci('calc'); ?></button>
        </div>

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
    global $info_strumento_corrente, $id_strumento_corrente;
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
        <div style="display:flex; gap:10px; margin-top:15px;">
            <a href="<?php echo ottieniUrl($id_strumento_corrente); ?>" class="btn" style="background:#9ca3af; width:auto; margin:0;"><?php echo traduci('reset'); ?></a>
            <button type="submit" class="btn" style="margin:0; flex:1;"><?php echo traduci('calc'); ?></button>
        </div>
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
    global $info_strumento_corrente, $id_strumento_corrente;
    $iban = isset($_POST['iban']) ? htmlspecialchars($_POST['iban']) : '';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="iban">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_iban'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <label><?php echo traduci('lbl_iban_code'); ?></label>
        <input type="text" name="iban" placeholder="IT00X..." value="<?php echo $iban; ?>" style="text-transform:uppercase; font-family:monospace; font-size:16px; letter-spacing:1px;" required>
        <div style="display:flex; gap:10px; margin-top:15px;">
            <a href="<?php echo ottieniUrl($id_strumento_corrente); ?>" class="btn" style="background:#9ca3af; width:auto; margin:0;"><?php echo traduci('reset'); ?></a>
            <button type="submit" class="btn" style="margin:0; flex:1;"><?php echo traduci('verify'); ?></button>
        </div>
        
        <?php if($risultato): ?>
            <div class="result-box" style="background:<?php echo $risultato['color']=='green'?'#ecfdf5':'#fef2f2'; ?>; border-color:<?php echo $risultato['color']=='green'?'#d1fae5':'#fecaca'; ?>">
                <strong style="color:<?php echo $risultato['color']; ?>"><?php echo htmlspecialchars($risultato['main']); ?></strong>
            </div>

            <?php if (isset($risultato['parti'])): ?>
                <div style="margin-top:20px; background:#f8fafc; padding:15px; border-radius:8px; border:1px solid #e2e8f0; text-align:left;">
                    <label style="color:var(--primary); margin-bottom:10px; text-align:center;"><?php echo traduci('lbl_iban_details'); ?>:</label>
                    <div style="display:flex; flex-wrap:wrap; gap:5px; font-family:monospace; font-size:14px; justify-content:center; align-items:stretch;">
                        <div class="tooltip" style="background:#fff; border:1px solid #ddd; padding:8px; border-radius:4px; text-align:center; flex:1; min-width:50px; display:block; margin:0; color:inherit;">
                            <div style="font-size:9px; text-transform:uppercase; color:#9ca3af; font-weight:bold;"><?php echo traduci('iban_country'); ?></div>
                            <div style="font-weight:bold;"><?php echo $risultato['parti']['paese']; ?></div>
                            <span class="tooltiptext"><?php echo traduci('iban_tooltip_country'); ?></span>
                        </div>
                        <div class="tooltip" style="background:#fff; border:1px solid #ddd; padding:8px; border-radius:4px; text-align:center; flex:1; min-width:50px; display:block; margin:0; color:inherit;">
                            <div style="font-size:9px; text-transform:uppercase; color:#9ca3af; font-weight:bold;"><?php echo traduci('iban_check'); ?></div>
                            <div style="font-weight:bold;"><?php echo $risultato['parti']['check']; ?></div>
                            <span class="tooltiptext"><?php echo traduci('iban_tooltip_check'); ?></span>
                        </div>
                        <div class="tooltip" style="background:#fff7ed; border:1px solid #fdba74; padding:8px; border-radius:4px; text-align:center; flex:1; min-width:50px; display:block; margin:0; color:inherit;">
                            <div style="font-size:9px; text-transform:uppercase; color:#9ca3af; font-weight:bold;"><?php echo traduci('iban_cin'); ?></div>
                            <div style="font-weight:bold;"><?php echo $risultato['parti']['cin']; ?></div>
                            <span class="tooltiptext"><?php echo traduci('iban_tooltip_cin'); ?></span>
                        </div>
                        <div class="tooltip" style="background:#eff6ff; border:1px solid #93c5fd; padding:8px; border-radius:4px; text-align:center; flex:1; min-width:80px; display:block; margin:0; color:#1e40af;">
                            <div style="font-size:9px; text-transform:uppercase; color:#60a5fa; font-weight:bold;"><?php echo traduci('iban_abi'); ?></div>
                            <div style="font-weight:bold;"><?php echo $risultato['parti']['abi']; ?></div>
                            <span class="tooltiptext"><?php echo traduci('iban_tooltip_abi'); ?></span>
                        </div>
                        <div class="tooltip" style="background:#f0fdf4; border:1px solid #86efac; padding:8px; border-radius:4px; text-align:center; flex:1; min-width:80px; display:block; margin:0; color:#166534;">
                            <div style="font-size:9px; text-transform:uppercase; color:#4ade80; font-weight:bold;"><?php echo traduci('iban_cab'); ?></div>
                            <div style="font-weight:bold;"><?php echo $risultato['parti']['cab']; ?></div>
                            <span class="tooltiptext"><?php echo traduci('iban_tooltip_cab'); ?></span>
                        </div>
                        <div class="tooltip" style="background:#fff; border:1px solid #ddd; padding:8px; border-radius:4px; text-align:center; flex-grow:2; min-width:120px; display:block; margin:0; color:inherit;">
                            <div style="font-size:9px; text-transform:uppercase; color:#9ca3af; font-weight:bold;"><?php echo traduci('iban_account'); ?></div>
                            <div style="font-weight:bold;"><?php echo $risultato['parti']['conto']; ?></div>
                            <span class="tooltiptext"><?php echo traduci('iban_tooltip_account'); ?></span>
                        </div>
                    </div>
                    
                    <div style="margin-top:15px; border-top:1px dashed #cbd5e1; padding-top:15px; font-size:12px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                        <div>
                            <strong><?php echo traduci('lbl_bi_link'); ?>:</strong><br>
                            <?php echo sprintf(traduci('lbl_bi_desc'), $risultato['parti']['abi']); ?>
                        </div>
                        <a href="https://www.bancaditalia.it/compiti/vigilanza/albi-elenchi/" target="_blank" class="btn" style="width:auto; margin:0; padding:8px 15px; font-size:12px; background:#0f172a;">
                            <?php echo traduci('btn_bi_open'); ?> ↗
                        </a>
                    </div>
                </div>
            <?php endif; ?>
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
    global $info_strumento_corrente, $id_strumento_corrente;
    $testo_input = $_POST['text_in'] ?? '';
    $ops = $_POST['ops'] ?? ['spaces']; // Default spaces attivi
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="text">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_text'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <label><?php echo traduci('lbl_input_text'); ?></label>
        <textarea name="text_in" rows="8" style="font-family:monospace; font-size:13px;" placeholder="Incolla qui il testo..."><?php echo htmlspecialchars($testo_input); ?></textarea>
        
        <div style="margin-top:20px; display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:12px;">
            <label style="font-weight:400; cursor:pointer; display:flex; align-items:flex-start; gap:8px;">
                <input type="checkbox" name="ops[]" value="oneline" style="margin-top:3px; transform:scale(1.1);" <?php if(in_array('oneline', $ops)) echo 'checked'; ?>> 
                <?php echo traduci('opt_oneline'); ?>
            </label>
            <label style="font-weight:400; cursor:pointer; display:flex; align-items:flex-start; gap:8px;">
                <input type="checkbox" name="ops[]" value="smart_newline" style="margin-top:3px; transform:scale(1.1);" <?php if(in_array('smart_newline', $ops)) echo 'checked'; ?>> 
                <?php echo traduci('opt_smart_newline'); ?>
            </label>
            <label style="font-weight:400; cursor:pointer; display:flex; align-items:flex-start; gap:8px;">
                <input type="checkbox" name="ops[]" value="spaces" style="margin-top:3px; transform:scale(1.1);" <?php if(in_array('spaces', $ops)) echo 'checked'; ?>> 
                <?php echo traduci('opt_spaces'); ?>
            </label>
            <label style="font-weight:400; cursor:pointer; display:flex; align-items:flex-start; gap:8px;">
                <input type="checkbox" name="ops[]" value="fix_caps" style="margin-top:3px; transform:scale(1.1);" <?php if(in_array('fix_caps', $ops)) echo 'checked'; ?>> 
                🔠 <?php echo traduci('opt_fix_caps'); ?>
            </label>
            <label style="font-weight:400; cursor:pointer; display:flex; align-items:flex-start; gap:8px;">
                <input type="checkbox" name="ops[]" value="convert_case" id="chk_case" onchange="toggleCase()" style="margin-top:3px; transform:scale(1.1);" <?php if(in_array('convert_case', $ops)) echo 'checked'; ?>> 
                🔠 <?php echo traduci('lbl_case_conv'); ?>
            </label>
            <label style="font-weight:400; cursor:pointer; display:flex; align-items:flex-start; gap:8px;">
                <input type="checkbox" name="ops[]" value="latin" style="margin-top:3px; transform:scale(1.1);" <?php if(in_array('latin', $ops)) echo 'checked'; ?>> 
                <span>🏛️ <?php echo traduci('opt_latin'); ?></span>
                <span class="tooltip">ℹ️<span class="tooltiptext"><?php echo traduci('hint_latin'); ?></span></span>
            </label>
            <label style="font-weight:400; cursor:pointer; display:flex; align-items:flex-start; gap:8px;">
                <input type="checkbox" name="ops[]" value="highlight" id="chk_highlight" onchange="toggleHighlight()" style="margin-top:3px; transform:scale(1.1);" <?php if(in_array('highlight', $ops)) echo 'checked'; ?>> 
                <span>🖊️ <?php echo traduci('lbl_highlight_word'); ?></span>
                <span class="tooltip">ℹ️<span class="tooltiptext"><?php echo traduci('hint_latin'); ?></span></span>
            </label>
            <label style="font-weight:400; cursor:pointer; display:flex; align-items:flex-start; gap:8px; color:#b91c1c;">
                <input type="checkbox" name="ops[]" value="privacy" style="margin-top:3px; transform:scale(1.1);" <?php if(in_array('privacy', $ops)) echo 'checked'; ?>> 
                🛡️ <?php echo traduci('opt_privacy'); ?>
            </label>
        </div>

        <div id="highlight_box" style="margin-top:15px; background:#f9fafb; padding:15px; border-radius:6px; border:1px solid #e5e7eb; display:none; flex-direction:column; gap:10px;">
            <div id="highlight_rows_container" style="display:flex; flex-direction:column; gap:10px;">
                <?php 
                $hl_words = $_POST['highlight_word'] ?? [''];
                if (!is_array($hl_words)) $hl_words = [$hl_words];
                $hl_styles = $_POST['highlight_style'] ?? ['bold'];
                if (!is_array($hl_styles)) $hl_styles = [$hl_styles];
                
                for($i=0; $i<count($hl_words); $i++):
                    $curr_w = htmlspecialchars($hl_words[$i]);
                    $curr_s = $hl_styles[$i] ?? 'bold';
                ?>
                <div class="highlight-row" style="display:flex; gap:15px; align-items:flex-end;">
                    <div style="flex:2">
                        <label style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:bold; margin-bottom:5px;">Parola</label>
                        <input type="text" name="highlight_word[]" value="<?php echo $curr_w; ?>" placeholder="es. Importante" style="margin-bottom:0;">
                    </div>
                    <div style="flex:1">
                        <label style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:bold; margin-bottom:5px;"><?php echo traduci('lbl_highlight_style'); ?></label>
                        <select name="highlight_style[]" style="background:#fff; margin-bottom:0;">
                            <option value="bold" <?php echo $curr_s=='bold'?'selected':''; ?>><?php echo traduci('style_bold'); ?></option>
                            <option value="italic" <?php echo $curr_s=='italic'?'selected':''; ?>><?php echo traduci('style_italic'); ?></option>
                            <option value="underline" <?php echo $curr_s=='underline'?'selected':''; ?>><?php echo traduci('style_underline'); ?></option>
                            <option value="strike" <?php echo $curr_s=='strike'?'selected':''; ?>><?php echo traduci('style_strike'); ?></option>
                            <option value="uppercase" <?php echo $curr_s=='uppercase'?'selected':''; ?>><?php echo traduci('style_uppercase'); ?></option>
                        </select>
                    </div>
                    <button type="button" onclick="removeHighlightRow(this)" style="background:#fee2e2; border:1px solid #fca5a5; color:#b91c1c; cursor:pointer; border-radius:4px; padding:8px 12px; margin-bottom:0;" title="Rimuovi">&times;</button>
                </div>
                <?php endfor; ?>
            </div>
            <button type="button" onclick="addHighlightRow()" style="align-self:flex-start; background:#e5e7eb; border:1px solid #d1d5db; padding:6px 12px; border-radius:4px; cursor:pointer; font-size:12px; color:#374151;">+ Aggiungi altra parola</button>
        </div>

        <div id="case_box" style="margin-top:15px; background:#f9fafb; padding:15px; border-radius:6px; border:1px solid #e5e7eb; display:none; gap:15px; align-items:center;">
            <div style="flex:1">
                <label style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:bold; margin-bottom:5px;"><?php echo traduci('lbl_case_conv'); ?></label>
                <select name="case_type" style="background:#fff; margin-bottom:0;">
                    <?php $c = $_POST['case_type'] ?? 'title'; ?>
                    <option value="title" <?php echo $c=='title'?'selected':''; ?>><?php echo traduci('opt_title'); ?></option>
                    <option value="upper" <?php echo $c=='upper'?'selected':''; ?>><?php echo traduci('opt_upper'); ?></option>
                    <option value="lower" <?php echo $c=='lower'?'selected':''; ?>><?php echo traduci('opt_lower'); ?></option>
                </select>
            </div>
        </div>
        
        <?php if(in_array('privacy', $ops)): ?>
            <div style="font-size:11px; color:#ef4444; margin-top:5px; font-style:italic;">
                <?php echo traduci('msg_privacy_warn'); ?>
            </div>
        <?php endif; ?>

        <div style="display:flex; gap:10px; margin-top:15px;">
            <a href="<?php echo ottieniUrl($id_strumento_corrente); ?>" class="btn" style="background:#9ca3af; width:auto; margin:0;"><?php echo traduci('reset'); ?></a>
            <button type="submit" class="btn" style="margin:0; flex:1;"><?php echo traduci('clean'); ?></button>
        </div>

        <?php if($risultato): ?>
            <div style="margin-top:20px;">
                <label><?php echo traduci('lbl_result'); ?></label>
                <div class="res-raw" id="resTxt"><?php echo htmlspecialchars($risultato['raw']); ?></div>
                <div id="resHtml" style="position:absolute; left:-9999px; top:0;"><?php echo $risultato['html']; ?></div>
                <div style="display:flex; align-items:center; gap:5px; margin-top:5px;">
                    <button type="button" class="btn" style="background:#4338ca; width:auto; padding:8px 15px; margin:0;" onclick="copyHtml('resHtml')"><?php echo traduci('copy_rich'); ?></button>
                    <span class="tooltip">ℹ️<span class="tooltiptext"><?php echo traduci('tip_copy_rich'); ?></span></span>
                    
                    <button type="button" class="btn" style="background:#6b7280; width:auto; padding:8px 15px; margin:0 0 0 10px;" onclick="copyText('resTxt')"><?php echo traduci('copy_simple'); ?></button>
                    <span class="tooltip">ℹ️<span class="tooltiptext"><?php echo traduci('tip_copy_simple'); ?></span></span>
                </div>
            </div>
        <?php endif; ?>
    </form>
    <script>
    function toggleHighlight() {
        var chk = document.getElementById('chk_highlight');
        var box = document.getElementById('highlight_box');
        box.style.display = chk.checked ? 'flex' : 'none';
    }
    toggleHighlight();
    
    function addHighlightRow() {
        var container = document.getElementById('highlight_rows_container');
        var firstRow = container.querySelector('.highlight-row');
        var newRow = firstRow.cloneNode(true);
        newRow.querySelector('input').value = '';
        newRow.querySelector('select').selectedIndex = 0;
        container.appendChild(newRow);
    }

    function removeHighlightRow(btn) {
        var container = document.getElementById('highlight_rows_container');
        if (container.querySelectorAll('.highlight-row').length > 1) {
            btn.closest('.highlight-row').remove();
        } else {
             var row = btn.closest('.highlight-row');
             row.querySelector('input').value = '';
             row.querySelector('select').selectedIndex = 0;
        }
    }

    function toggleCase() {
        var chk = document.getElementById('chk_case');
        var box = document.getElementById('case_box');
        box.style.display = chk.checked ? 'flex' : 'none';
    }
    toggleCase();
    </script>
    <?php
}

/**
 * Renderizza l'interfaccia dello strumento "Gestione Liste".
 * Renders the "List Tools" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_lists($risultato) {
    global $info_strumento_corrente, $id_strumento_corrente;
    $testo_input = isset($_POST['list_input']) ? htmlspecialchars($_POST['list_input']) : '';
    $mode = $_POST['mode'] ?? 'join';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="lists">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="tool-title"><?php echo traduci('tool_lists'); ?></div>
        <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_long']); ?></div>
        
        <label><?php echo traduci('lbl_input_list'); ?></label>
        <textarea name="list_input" rows="8" placeholder="<?php echo traduci('ph_email_list'); ?>"><?php echo $testo_input; ?></textarea>
        
        <div style="margin-top:15px; background:#f9fafb; padding:15px; border-radius:8px; border:1px solid #e5e7eb;">
            <label style="margin-bottom:10px; color:var(--primary);"><?php echo traduci('lbl_mode'); ?></label>
            <div style="display:flex; flex-direction:column; gap:8px;">
                <label style="font-weight:400; cursor:pointer; display:flex; align-items:center; gap:8px;">
                    <input type="radio" name="mode" value="join" <?php echo $mode=='join'?'checked':''; ?> onclick="toggleSep(true)"> 
                    ⬇️➡️ <?php echo traduci('mode_join'); ?>
                </label>
                <label style="font-weight:400; cursor:pointer; display:flex; align-items:center; gap:8px;">
                    <input type="radio" name="mode" value="split" <?php echo $mode=='split'?'checked':''; ?> onclick="toggleSep(true)"> 
                    ➡️⬇️ <?php echo traduci('mode_split'); ?>
                </label>
                <label style="font-weight:400; cursor:pointer; display:flex; align-items:center; gap:8px;">
                    <input type="radio" name="mode" value="extract" <?php echo $mode=='extract'?'checked':''; ?> onclick="toggleSep(false)"> 
                    🔍 <?php echo traduci('mode_extract'); ?>
                </label>
            </div>

            <div id="sep_box" style="margin-top:15px; display:<?php echo $mode=='extract'?'none':'block'; ?>;">
                <label style="font-size:12px;"><?php echo traduci('lbl_separator'); ?></label>
                <select name="separator" style="background:white;">
                    <option value="comma"><?php echo traduci('opt_comma'); ?></option>
                    <option value="semicolon"><?php echo traduci('opt_semicolon'); ?></option>
                    <option value="auto"><?php echo traduci('opt_auto'); ?></option>
                </select>
            </div>
        </div>
        
        <div style="display:flex; gap:10px; margin-top:15px;">
            <a href="<?php echo ottieniUrl($id_strumento_corrente); ?>" class="btn" style="background:#9ca3af; width:auto; margin:0;"><?php echo traduci('reset'); ?></a>
            <button type="submit" class="btn" style="margin:0; flex:1;"><?php echo traduci('format'); ?></button>
        </div>

        <?php if($risultato): ?>
            <div style="margin-top:20px;" id="res_container">
                <label><?php echo traduci('lbl_result'); ?></label>
                <div class="res-raw" id="resEmail"><?php echo htmlspecialchars($risultato['raw']); ?></div>
                <button type="button" class="btn" style="margin-top:5px; background:#6b7280" onclick="copyText('resEmail')"><?php echo traduci('copy'); ?></button>
            </div>
        <?php endif; ?>
    </form>
    <script>
    function toggleSep(show) {
        document.getElementById('sep_box').style.display = show ? 'block' : 'none';
    }
    </script>
    <?php
}

/**
 * Renderizza l'interfaccia dello strumento "Generatore Password".
 * Renders the "Password Generator" tool interface.
 * 
 * @param array|null $risultato I dati del risultato dalla funzione processore. / The result data from the processor function.
 */
function visualizza_password($risultato) {
    global $info_strumento_corrente, $id_strumento_corrente;
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
            
            <div style="display:flex; gap:10px; margin-top:15px;">
                <a href="<?php echo ottieniUrl($id_strumento_corrente); ?>" class="btn" style="background:#9ca3af; width:auto; margin:0;"><?php echo traduci('reset'); ?></a>
                <button type="submit" class="btn" style="margin:0; flex:1;"><?php echo traduci('generate'); ?></button>
            </div>
        </div>
    </form>
    <?php
}
?>
<script>
/**
 * Gestisce l'avanzamento automatico del focus per i campi orario (HH:MM).
 * Handles automatic focus advancement for time fields (HH:MM).
 */
document.addEventListener('input', function (e) {
    if (e.target.classList.contains('autotab') && e.target.value.length >= 2) {
        // Trova tutti gli input autotab nel form corrente
        // Find all autotab inputs in the current form
        let inputs = Array.from(e.target.form.querySelectorAll('input.autotab'));
        let index = inputs.indexOf(e.target);
        // Se esiste un elemento successivo, sposta il focus
        // If a next element exists, move focus
        if (index > -1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    }
});
</script>