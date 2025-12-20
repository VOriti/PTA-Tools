<?php
/**
 * UNITOOLS - INTEGRATED UTILITY DASHBOARD
 * -----------------------------------------------------
 * A standalone PHP utility suite for administrative tasks.
 * No external dependencies required.
 *
 * @author  Vincenzo Oriti
 * @version 2.1
 * @license CC BY-NC-SA 4.0
 */

// ---------------------------------------------------------
// SECTION 1: BOOTSTRAP & CONFIGURATION
// ---------------------------------------------------------
session_start();

// Disable error display for production environment.
// Set to 1 during development to see all errors.
error_reporting(E_ALL);
ini_set('display_errors', 0); 

// Language Management: Detects language from GET parameter or session,
// defaulting to Italian ('it'). The chosen language is stored in the session.
$lingua = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'it');
$_SESSION['lang'] = $lingua;

// ---------------------------------------------------------
// SECTION 2: TRANSLATION DICTIONARY
// ---------------------------------------------------------
$traduzioni = [
    'it' => [
        // -- GENERAL UI --
        'app_name' => 'UniTools',
        'home_title' => 'Dashboard Principale',
        'back_dash' => 'Torna alla Dashboard',
        'copied' => 'Copiato negli appunti!',
        
        // -- DASHBOARD CATEGORIES --
        'cat_links' => 'Link di Ateneo',
        'intro_links' => 'Accesso rapido alle principali piattaforme e servizi dell\'università.',
        'cat_time' => 'Gestione Tempo',
        'intro_time' => 'Strumenti per il calcolo ore lavorate, verifica timbrature e conversioni.',
        'cat_account' => 'Contabilità',
        'intro_account' => 'Utility per calcolo IVA, verifica codici bancari e operazioni fiscali.',
        'cat_office' => 'Ufficio & Utilità',
        'intro_office' => 'Tool per pulizia testi, liste email e sicurezza.',
        'sect_tools' => 'Tools',
        'intro_tools' => 'Strumenti operativi per calcoli, conversioni e gestione dati.',

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

        // -- LINKS --
        'tool_calendari_aule' => 'Calendari Occupazione Aule',
        'desc_short_calendari_aule' => 'Visualizza i calendari di occupazione dei poli didattici.',
        'link_aggregatore' => 'Aggregatore Applicativi',
        'desc_short_aggregatore' => 'Portale unico per l\'accesso ai servizi di ateneo.',
        'link_rubrica' => 'Rubrica di Ateneo',
        'desc_short_rubrica' => 'Cerca contatti del personale docente e tecnico-amministrativo.',
        'link_cartellino' => 'Gestionale Cartellino',
        'desc_short_cartellino' => 'Accesso al sistema di rilevazione presenze StartWeb.',
        'link_ticketing_interno' => 'Ticketing Interno (SOS)',
        'desc_short_ticketing_interno' => 'Apri una richiesta di supporto tecnico al servizio informatico.',
        'link_ticketing_manutenzioni' => 'Ticketing Manutenzioni',
        'desc_short_ticketing_manutenzioni' => 'Segnala guasti o richiedi interventi di manutenzione.',
    ],
    'en' => [
        // -- GENERAL UI --
        'app_name' => 'UniTools',
        'home_title' => 'Main Dashboard',
        'back_dash' => 'Back to Dashboard',
        'copied' => 'Copied to clipboard!',
        
        // -- DASHBOARD CATEGORIES --
        'cat_links' => 'University Links',
        'intro_links' => 'Organized collection of quick links to internal platforms.',
        'cat_time' => 'Time Management',
        'intro_time' => 'Tools for working hours calculation and overtime conversion.',
        'cat_account' => 'Accounting',
        'intro_account' => 'Utilities for VAT calculation and bank code verification.',
        'cat_office' => 'Office Utilities',
        'intro_office' => 'Tools for text cleaning, email lists and password generation.',
        'sect_tools' => 'Tools',
        'intro_tools' => 'Operational tools for calculations, conversions, and data management.',

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

        // -- LINKS --
        'tool_calendari_aule' => 'Classroom Occupation Calendars',
        'desc_short_calendari_aule' => 'View the occupation calendars for the university buildings.',
        'link_aggregatore' => 'Application Aggregator',
        'desc_short_aggregatore' => 'Single portal for accessing university services.',
        'link_rubrica' => 'University Address Book',
        'desc_short_rubrica' => 'Search for contacts of teaching and administrative staff.',
        'link_cartellino' => 'Time Tracking System',
        'desc_short_cartellino' => 'Access the StartWeb attendance tracking system.',
        'link_ticketing_interno' => 'Internal Ticketing (SOS)',
        'desc_short_ticketing_interno' => 'Open a technical support request to the IT service.',
        'link_ticketing_manutenzioni' => 'Maintenance Ticketing',
        'desc_short_ticketing_manutenzioni' => 'Report failures or request maintenance interventions.',
    ]
];

// ---------------------------------------------------------
// SECTION 3: HELPER FUNCTIONS
// ---------------------------------------------------------

/**
 * Retrieves a translated string for a given key.
 * It looks for the key in the global translation array for the current language.
 * If the translation is not found, it returns the key itself as a fallback.
 *
 * @param string $chiave The key for the translation string.
 * @return string The translated string or the key itself.
 */
function traduci($chiave) { 
    global $traduzioni, $lingua; 
    return $traduzioni[$lingua][$chiave] ?? $chiave; 
}

/**
 * Builds a URL with query parameters for language and tool selection.
 * It preserves the current language if a new one is not specified.
 *
 * @param string|null $strumento The tool ID to include in the URL.
 * @param string|null $nuova_lingua The new language code (e.g., 'it', 'en').
 * @return string The generated URL string.
 */
function ottieniUrl($strumento = null, $nuova_lingua = null) { 
    global $lingua; 
    $l = $nuova_lingua ?? $lingua;
    return "?lang=$l" . ($strumento ? "&tool=$strumento" : ""); 
}

// ---------------------------------------------------------
// SECTION 4: TOOLS CATALOG CONFIGURATION
// ---------------------------------------------------------
$CATALOGO = [
    'links' => [
        'label_key' => 'cat_links', 
        'intro_key' => 'intro_links', 
        'icon' => '🏛️',
        'items' => [
            'aggregatore_ateneo' => [
                'type' => 'direct_link',
                'key' => 'link_aggregatore',
                'desc_short' => 'desc_short_aggregatore',
                'url' => 'https://io.unipv.it',
                'featured' => true // Flag to identify the featured link
            ],
            'rubrica' => [
                'type' => 'direct_link',
                'key' => 'link_rubrica',
                'desc_short' => 'desc_short_rubrica',
                'url' => 'http://rubrica.unipv.it'
            ],
            'cartellino' => [
                'type' => 'direct_link',
                'key' => 'link_cartellino',
                'desc_short' => 'desc_short_cartellino',
                'url' => 'http://startweb.unipv.it'
            ],
            'calendari_aule' => [
                'type' => 'link_group',
                'key' => 'tool_calendari_aule',
                'desc_short' => 'desc_short_calendari_aule',
                'func' => 'visualizza_gruppo_link',
                'links' => [
                    'polo_centrale' => ['titolo' => 'Polo Centrale', 'desc' => 'Aule del polo centrale e di Palazzo Botta.', 'url' => '#'],
                    'polo_scientifico' => ['titolo' => 'Polo Cravino', 'desc' => 'Aule del polo scientifico (Cravino).', 'url' => '#'],
                    'polo_cremona' => ['titolo' => 'Sede di Cremona', 'desc' => 'Aule della sede di Cremona.', 'url' => '#'],
                ]
            ],
            'ticketing_interno' => [
                'type' => 'direct_link',
                'key' => 'link_ticketing_interno',
                'desc_short' => 'desc_short_ticketing_interno',
                'url' => 'https://sos.unipv.it'
            ],
            'ticketing_manutenzioni' => [
                'type' => 'direct_link',
                'key' => 'link_ticketing_manutenzioni',
                'desc_short' => 'desc_short_ticketing_manutenzioni',
                'url' => 'https://unipv.infocad.fm/'
            ],
        ]
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

// Determine current tool from URL, sanitizing the input.
$id_strumento_corrente = isset($_GET['tool']) ? htmlspecialchars($_GET['tool']) : null;
$info_strumento_corrente = null;

// Find the current tool's information in the catalog.
foreach($CATALOGO as $categoria) {
    if(isset($categoria['items'][$id_strumento_corrente])) { 
        $info_strumento_corrente = $categoria['items'][$id_strumento_corrente]; 
        break; 
    }
}

// Special case for the dedicated links page
if ($id_strumento_corrente === 'link_page') {
    $info_strumento_corrente = [
        'key' => 'cat_links',
        'func' => 'visualizza_pagina_link'
    ];
}

// ---------------------------------------------------------
// SECTION 5: BACKEND LOGIC (FORM PROCESSING ROUTER)
// ---------------------------------------------------------
$dati_risultato = null;

// Process form data only if the request method is POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $azione = $_POST['action'] ?? '';

    // Find the processing function from the catalog based on the submitted action.
    $funzione_processore = null;
    foreach($CATALOGO as $categoria) {
        if(isset($categoria['items'][$azione])) {
            $funzione_processore = $categoria['items'][$azione]['proc'] ?? null;
            break;
        }
    }

    // If a valid processor function is found, call it to process the data.
    if ($funzione_processore && function_exists($funzione_processore)) {
        $dati_risultato = call_user_func($funzione_processore);
    }
}

// ==========================================
// SECTION 6: PROCESSOR FUNCTIONS
// ==========================================

/**
 * Processes the "Work Hours Calc" form submission.
 * It sums multiple time intervals.
 * @return array An array with the calculated total time in HH:MM format and a descriptive string.
 */
function processa_intervalli() {
    $tot_secondi = 0;
    if (isset($_POST['h_start'])) {
        for ($i = 0; $i < count($_POST['h_start']); $i++) {
            $ora_inizio = (int)$_POST['h_start'][$i]; $min_inizio = (int)$_POST['m_start'][$i];
            $ora_fine = (int)$_POST['h_end'][$i];   $min_fine = (int)$_POST['m_end'][$i];
            
            $inizio = mktime($ora_inizio, $min_inizio, 0, 1, 1, 2000);
            $fine   = mktime($ora_fine, $min_fine, 0, 1, 1, 2000);
            
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
 * Processes the "Overtime Converter" form submission.
 * It calculates how many full work days can be covered by an overtime balance.
 * @return array An array with the end date of leave and the remaining time.
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
        $data_finale = clone $data; // Will hold the last covered day
        
        while ($saldo_minuti > 0) {
            $giorno_settimana = $data->format('N'); // 1 (for Monday) through 7 (for Sunday)
            if ($giorno_settimana >= 6) { // Skip weekends
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
                break; // Not enough balance for another full day
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
 * Processes the "Deadline & Duration" form submission.
 * It calculates an end time from a start time and duration, or vice-versa.
 * @return array An array with the calculated date/time.
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
 * Processes the "Date Difference" form submission.
 * It calculates the interval between two dates.
 * @return array An array with the calculated difference in years, months, days, and total days.
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
 * Processes the "VAT Manager" form submission.
 * It can add, remove, or just calculate VAT on a given amount.
 * @return array An array containing the result as an HTML string.
 */
function processa_iva() {
    $importo = floatval(str_replace(',', '.', $_POST['importo']));
    $aliquota = ($_POST['aliquota'] == 'other') ? floatval($_POST['aliquota_other']) : floatval($_POST['aliquota']);
    $operazione = $_POST['operazione'];
    
    if($operazione == 'scorporo') { // Unbundle VAT from gross amount
        $imponibile_netto = $importo / (1 + ($aliquota/100)); 
        $iva_valore = $importo - $imponibile_netto; 
        $totale = $importo; 
    } elseif ($operazione == 'add') { // Add VAT to net amount
        $imponibile_netto = $importo; 
        $iva_valore = $importo * ($aliquota/100); 
        $totale = $importo + $iva_valore; 
    } else { // Calculate VAT only
        $imponibile_netto = $importo; 
        $iva_valore = $importo * ($aliquota/100); 
        $totale = 0; // Total is not relevant here
    }
    
    return ['html' => "<div style='display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; text-align:center;'>
        <div><small>".traduci('lbl_net')."</small><div style='font-weight:bold'>€ ".number_format($imponibile_netto,2,',','.')."</div></div>
        <div><small>".traduci('lbl_vat')."</small><div style='font-weight:bold; color:#d97706'>€ ".number_format($iva_valore,2,',','.')."</div></div>
        <div><small>".traduci('lbl_gross')."</small><div style='font-weight:bold; color:#059669'>€ ".number_format($totale,2,',','.')."</div></div></div>"];
}

/**
 * Processes the "IBAN Validator" form submission.
 * It performs a mathematical check on the IBAN's structure.
 * @return array An array with the validation result message and a color code.
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
 * Processes the "Text Sanitizer" form submission.
 * It cleans and reformats a given text string.
 * @return array An array with the sanitized text.
 */
function processa_testo() {
    $testo_input = $_POST['text_in'];
    $operazione = $_POST['text_op'];
    
    if ($operazione == 'title') $testo_output = mb_convert_case($testo_input, MB_CASE_TITLE, "UTF-8");
    elseif ($operazione == 'upper') $testo_output = mb_strtoupper($testo_input, "UTF-8");
    elseif ($operazione == 'lower') $testo_output = mb_strtolower($testo_input, "UTF-8");
    elseif ($operazione == 'oneline') $testo_output = str_replace(["\r", "\n"], ' ', $testo_input);
    
    // Remove multiple spaces
    $testo_output = preg_replace('/\s+/', ' ', $testo_output ?? $testo_input);
    return ['raw' => trim($testo_output)];
}

/**
 * Processes the "Email List Formatter" form submission.
 * It converts a list of emails (one per line) into a single, separator-based string.
 * @return array An array with the formatted email list.
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
 * Processes the "Password Generator" form submission.
 * It creates a pronounceable, yet secure, password.
 * @return array An array with the generated password.
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS VARIABLES & RESET */
        :root {
            --primary: #4338ca; 
            --primary-soft: #e0e7ff;
            --bg-body: #f3f4f6;
            --bg-card: #ffffff;
            --text-main: #1f2937;
            --text-sub: #6b7280;
            --sidebar-w: 280px;
            --radius: 8px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-body); color: var(--text-main); margin: 0; display: flex; min-height: 100vh; }
        * { box-sizing: border-box; }
        a { text-decoration: none; color: inherit; }

        /* SIDEBAR STYLES */
        .sidebar { width: var(--sidebar-w); background: white; border-right: 1px solid #e5e7eb; position: fixed; height: 100%; z-index: 50; transition: 0.3s; display: flex; flex-direction: column; }
        .logo-area { height: 60px; display: flex; align-items: center; padding: 0 20px; font-weight: 800; font-size: 20px; color: var(--primary); border-bottom: 1px solid #e5e7eb; }
        .nav-scroll { flex: 1; overflow-y: auto; padding: 20px 0; }
        
        .cat-header { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-sub); font-weight: 700; padding: 15px 20px 5px; }
        .nav-item { display: flex; align-items: center; padding: 10px 20px; font-size: 14px; font-weight: 500; color: #374151; border-left: 3px solid transparent; }
        .nav-item:hover { background: #f9fafb; color: var(--primary); }
        .nav-item.active { background: var(--primary-soft); color: var(--primary); border-left-color: var(--primary); font-weight: 600; }
        .back-link { font-weight: 600; color: var(--primary); border-bottom: 1px solid #eee; margin-bottom: 10px; }

        /* MAIN CONTENT AREA */
        .main-content { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; width: 100%; transition: 0.3s; }
        
        /* HEADER (DESKTOP & MOBILE) */
        .top-bar { height: 60px; background: white; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; position: sticky; top: 0; z-index: 40; }
        .mobile-header { display: none; background: white; height: 60px; padding: 0 15px; align-items: center; justify-content: space-between; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 40; }
        .burger-btn { background: none; border: none; font-size: 24px; cursor: pointer; }
        
        .lang-switch a { margin-left: 10px; font-size: 20px; opacity: 0.5; transition: 0.2s; }
        .lang-switch a.active { opacity: 1; transform: scale(1.1); }

        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; width: 100%; }
        
        /* UI COMPONENTS */
        .card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 25px; margin-bottom: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .tool-title { font-size: 20px; font-weight: 700; margin-bottom: 10px; color: var(--text-main); }
        .tool-desc { color: var(--text-sub); font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        
        /* DASHBOARD GRID */
        .dash-section { margin-bottom: 40px; }
        .dash-sec-title { font-size: 18px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 10px; margin-bottom: 5px; }
        .dash-sec-intro { font-size: 14px; color: var(--text-sub); margin-bottom: 20px; max-width: 700px; }
        .sub-cat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .link-card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; transition: 0.2s; cursor: pointer; display: block; }
        .link-card:hover { border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .lc-head { font-weight: 600; color: var(--primary); margin-bottom: 5px; }
        .lc-desc { font-size: 12px; color: var(--text-sub); }

        /* FORMS & INPUTS */
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
            grid-column: 1 / -1; /* Make it span the full width of the grid */
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
        }
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
    </style>
</head>
<body>

<div class="overlay" id="overlay" onclick="toggleMenu()"></div>

<aside class="sidebar" id="sidebar">
    <div class="logo-area">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:10px"><path d="M3 21h18M5 21V7l8-4 8 4v14M8 21v-9a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v9"/></svg>
        UniTools
    </div>
    <nav class="nav-scroll">
        <a href="<?php echo ottieniUrl(); ?>" class="nav-item back-link <?php echo !$id_strumento_corrente ? 'active' : ''; ?>">
            <?php echo traduci('back_dash'); ?>
        </a>

        <a href="<?php echo ottieniUrl('link_page'); ?>" class="nav-item <?php echo $id_strumento_corrente == 'link_page' ? 'active' : ''; ?>">
            <?php echo traduci('cat_links'); ?>
        </a>

        <?php foreach($CATALOGO as $id_cat => $cat): if($id_cat == 'links') continue; // Keep hiding from tool list ?>
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
        </div>
    </div>

    <header class="mobile-header">
        <button class="burger-btn" onclick="toggleMenu()">☰</button>
        <div style="font-weight:700; color:var(--primary)">UniTools</div>
        <div class="lang-switch">
            <a href="<?php echo ottieniUrl($id_strumento_corrente, 'it'); ?>" class="<?php echo $lingua=='it'?'active':''; ?>">🇮🇹</a>
            <a href="<?php echo ottieniUrl($id_strumento_corrente, 'en'); ?>" class="<?php echo $lingua=='en'?'active':''; ?>">🇬🇧</a>
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
            foreach($CATALOGO as $id_cat => $cat):
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
</div>

<script>
/**
 * Toggles the visibility of the mobile sidebar menu and the overlay.
 */
function toggleMenu() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('active');
}

/**
 * Copies the text content of a given element to the clipboard.
 * It handles both standard elements (reading innerText) and input/textarea elements (reading value).
 * @param {string} elementId The ID of the element to copy text from.
 */
function copyText(elementId) {
    var elemento = document.getElementById(elementId);
    if(!elemento) return;
    var testo = elemento.innerText || elemento.value;

    // Fallback per contesti non sicuri (HTTP) o browser legacy
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
</script>
</body>
</html>

<?php
// ==========================================
// SECTION 7: VIEW RENDER FUNCTIONS
// ==========================================

/**
 * Renders the dedicated page for the "Link di Ateneo" category.
 * It displays a featured link prominently and the rest in a grid.
 * @param array|null $risultato Not used, but required by the caller.
 */
function visualizza_pagina_link($risultato) {
    ?>
    <h1 style="margin-bottom:10px"><?php echo traduci('cat_links'); ?></h1>
    <?php render_links_content(); ?>
    <?php
}

/**
 * Renders the content for the links category (separators, featured, grid).
 * Shared between dashboard and dedicated page.
 */
function render_links_content() {
    global $CATALOGO;
    $link_items = $CATALOGO['links']['items'];
    $featured_link = null;
    $other_links = [];

    foreach ($link_items as $id => $item) {
        if (isset($item['featured']) && $item['featured'] === true) {
            $featured_link = $item;
            $featured_link['id'] = $id;
        } else {
            $other_links[$id] = $item;
        }
    }
    ?>
    
    <p class="separator-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.</p>

    <div class="sub-cat-grid">
        <?php
        // Render the featured link first
        if ($featured_link) {
            render_link_card($featured_link, true);
        }
        ?>
    </div>

    <p class="separator-text">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

    <div class="sub-cat-grid">
        <?php
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
 * Helper function to render a single link card.
 * @param array $item The link item from the catalog.
 * @param bool $is_featured Whether the card should have a featured style.
 */
function render_link_card($item, $is_featured = false) {
    $tipo = $item['type'] ?? 'tool';
    $class = 'link-card' . ($is_featured ? ' featured-card' : '');
    
    if ($tipo == 'direct_link') { ?>
        <a href="<?php echo htmlspecialchars($item['url']); ?>" class="<?php echo $class; ?>" target="_blank" rel="noopener noreferrer">
            <div class="lc-head"><?php echo traduci($item['key']); ?></div>
            <div class="lc-desc"><?php echo traduci($item['desc_short']); ?></div>
        </a>
    <?php } else { // Covers 'tool' and 'link_group' ?>
        <a href="<?php echo ottieniUrl($item['id']); ?>" class="<?php echo $class; ?>">
            <div class="lc-head"><?php echo traduci($item['key']); ?></div>
            <div class="lc-desc" style="color:#6b7280"><?php echo traduci($item['desc_short']); ?></div>
        </a>
    <?php }
}

/**
 * Renders a page that displays a list of links for a specific group.
 * This function is used for items of type 'link_group'.
 * @param array|null $risultato The result data (not used here, but required by the caller).
 */
function visualizza_gruppo_link($risultato) {
    global $info_strumento_corrente;
    ?>
    <div class="card" style="padding:0;">
        <div style="padding: 20px 20px 0 20px;">
            <div class="tool-title"><?php echo traduci($info_strumento_corrente['key']); ?></div>
            <div class="tool-desc"><?php echo traduci($info_strumento_corrente['desc_short']); ?></div>
        </div>
        
        <div class="link-group-container" style="padding: 20px; display: grid; gap: 15px;">
            <?php foreach($info_strumento_corrente['links'] as $link): ?>
                <a href="<?php echo htmlspecialchars($link['url']); ?>" class="link-card" target="_blank" rel="noopener noreferrer" style="display:block; text-decoration:none; margin:0; border: 1px solid #e5e7eb;">
                    <div class="lc-head"><?php echo htmlspecialchars($link['titolo']); ?></div>
                    <div class="lc-desc"><?php echo htmlspecialchars($link['desc']); ?></div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}


/**
 * Renders the "Work Hours Calc" tool interface.
 * @param array|null $risultato The result data from the processor function.
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
 * Renders the "Overtime Converter" tool interface.
 * @param array|null $risultato The result data from the processor function.
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
 * Renders the "Deadline & Duration" tool interface.
 * @param array|null $risultato The result data from the processor function.
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
 * Renders the "Date Difference" tool interface.
 * @param array|null $risultato The result data from the processor function.
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
 * Renders the "VAT Manager" tool interface.
 * @param array|null $risultato The result data from the processor function.
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
        // Show/hide the custom VAT rate input field based on dropdown selection.
        document.getElementById('aliquota_select').addEventListener('change', function() {
            var wrap = document.getElementById('aliquota_other_wrap');
            wrap.style.display = (this.value === 'other') ? 'block' : 'none';
        });
    </script>
    <?php 
 
}

/**
 * Renders the "IBAN Validator" tool interface.
 * @param array|null $risultato The result data from the processor function.
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
 * Renders the "Text Sanitizer" tool interface.
 * @param array|null $risultato The result data from the processor function.
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
 * Renders the "Email List Formatter" tool interface.
 * @param array|null $risultato The result data from the processor function.
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
 * Renders the "Password Generator" tool interface.
 * @param array|null $risultato The result data from the processor function.
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