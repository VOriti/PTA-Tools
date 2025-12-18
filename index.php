<?php
// ==========================================
// 1. CONFIGURAZIONE E BOOTSTRAP
// ==========================================
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // Nascondi errori a video in produzione

// Gestione Lingua
$lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'it');
$_SESSION['lang'] = $lang;

// --- DIZIONARIO TRADUZIONI ---
$trans = [
    'it' => [
        'app_name' => 'UniTools',
        'home_title' => 'Dashboard Operativa',
        'select_tool' => 'Seleziona uno strumento dal menu',
        'back' => 'Home',
        'calc' => 'Esegui Calcolo',
        'result' => 'Risultato Elaborazione',
        'copy' => 'Copia',
        'copied' => 'Copiato!',
        
        // Categorie
        'cat_dashboard' => 'Principale',
        'cat_time' => 'Gestione Orari & Presenze',
        'cat_account' => 'Contabilità & Fisco',
        'cat_office' => 'Utilità Ufficio & Segreteria',

        // Tool: Dashboard
        'tool_links' => 'Link Rapidi Ateneo',
        'desc_links' => 'Accesso veloce a YouPlanner, Ticket e risorse non indicizzate.',

        // Tool: Intervalli
        'tool_intervalli' => 'Somma Ore Lavorate',
        'desc_intervalli' => 'Calcolo del totale ore da timbrature multiple (entrata/uscita).',
        'lbl_from' => 'Dalle', 'lbl_to' => 'Alle',

        // Tool: Recuperi
        'tool_recuperi' => 'Convertitore Recuperi & Ferie',
        'desc_recuperi' => 'Converte un saldo ore in eccesso in giorni di ferie/permesso.',
        'lbl_saldo' => 'Saldo Ore da smaltire',
        'lbl_std_day' => 'Durata giornata standard',
        'res_recuperi' => 'Puoi assentarti per:',

        // Tool: Differenza Date
        'tool_dates' => 'Calcolo Anzianità / Scadenze',
        'desc_dates' => 'Calcola la distanza esatta tra due date (anni, mesi, giorni).',
        'lbl_start_date' => 'Data Inizio', 'lbl_end_date' => 'Data Fine',

        // Tool: IVA
        'tool_iva' => 'Gestione IVA & Scorporo',
        'desc_iva' => 'Scorporo, applicazione e calcolo aliquote IVA.',
        'lbl_amount' => 'Importo',
        'lbl_rate' => 'Aliquota IVA (%)',
        'op_scorporo' => 'Scorpora IVA (Da Lordo a Netto)',
        'op_add' => 'Applica IVA (Da Netto a Lordo)',
        'op_calc' => 'Calcola solo valore IVA',
        'res_net' => 'Imponibile (Netto)',
        'res_vat' => 'Imposta (IVA)',
        'res_gross' => 'Totale (Lordo)',

        // Tool: IBAN
        'tool_iban' => 'Verifica Formale IBAN',
        'desc_iban' => 'Controlla se un codice IBAN è formalmente corretto (evita errori di battitura).',
        'lbl_iban' => 'Codice IBAN',
        'msg_iban_ok' => 'L\'IBAN è formalmente CORRETTO.',
        'msg_iban_ko' => 'ERRORE: L\'IBAN non è valido.',

        // Tool: Text Cleaner
        'tool_text' => 'Sanificatore Testi',
        'desc_text' => 'Pulisce testi copiati da PDF, corregge maiuscole e spazi.',
        'lbl_text_in' => 'Testo originale',
        'op_titlecase' => 'Converti in Titolo (Mario Rossi)',
        'op_uppercase' => 'TUTTO MAIUSCOLO',
        'op_lowercase' => 'tutto minuscolo',
        'op_oneline' => 'Rimuovi a capo (Unrisci righe)',

        // Tool: Email List
        'tool_email' => 'Formattatore Liste Email',
        'desc_email' => 'Trasforma una colonna Excel in una lista pronta per Outlook/Gmail.',
        'lbl_email_col' => 'Incolla colonna Excel qui',
        'lbl_separator' => 'Separatore desiderato',
        'sep_semicolon' => 'Punto e virgola (Outlook)',
        'sep_comma' => 'Virgola (Gmail/Standard)',

        // Tool: Password
        'tool_pass' => 'Generatore Password Sicure',
        'desc_pass' => 'Crea password pronunciabili per reset utenze studenti/personale.',
        'lbl_pass_len' => 'Lunghezza',
        'btn_gen_pass' => 'Genera Nuova'
    ],
    'en' => [
        // English placeholders (simplified for brevity as requested mostly Italian)
        'app_name' => 'UniTools',
        'home_title' => 'Staff Dashboard',
        'select_tool' => 'Select a tool',
        'back' => 'Home',
        'calc' => 'Calculate',
        'cat_dashboard' => 'Main',
        'cat_time' => 'Time Mgmt',
        'cat_account' => 'Accounting',
        'cat_office' => 'Office Utils',
        'tool_links' => 'Quick Links',
        'desc_links' => 'Access YouPlanner and hidden resources.',
        'tool_intervalli' => 'Time Sum',
        'desc_intervalli' => 'Sum multiple time intervals.',
        'tool_recuperi' => 'Overtime Converter',
        'desc_recuperi' => 'Convert hours to days off.',
        'tool_dates' => 'Date Difference',
        'desc_dates' => 'Calculate exact duration between dates.',
        'tool_iva' => 'VAT Calculator',
        'desc_iva' => 'Extract or Add VAT.',
        'tool_iban' => 'IBAN Validator',
        'desc_iban' => 'Check IBAN format.',
        'tool_text' => 'Text Cleaner',
        'desc_text' => 'Fix capitalization and spaces.',
        'tool_email' => 'Email List Builder',
        'desc_email' => 'Excel column to email list.',
        'tool_pass' => 'Password Generator',
        'desc_pass' => 'Create readable passwords.'
    ]
];

function t($key) { global $trans, $lang; return $trans[$lang][$key] ?? $key; }
function getUrl($tool = null) { global $lang; return "?lang=$lang" . ($tool ? "&tool=$tool" : ""); }

// --- CATALOGO STRUMENTI ---
$CATALOG = [
    'dashboard' => [
        'label_key' => 'cat_dashboard', 'icon' => '🏠',
        'items' => [
            'links' => ['title_key' => 'tool_links', 'desc_key' => 'desc_links', 'func' => 'render_links']
        ]
    ],
    'time' => [
        'label_key' => 'cat_time', 'icon' => '⏱️',
        'items' => [
            'intervalli' => ['title_key' => 'tool_intervalli', 'desc_key' => 'desc_intervalli', 'func' => 'render_intervalli'],
            'recuperi'   => ['title_key' => 'tool_recuperi', 'desc_key' => 'desc_recuperi', 'func' => 'render_recuperi'],
            'dates'      => ['title_key' => 'tool_dates', 'desc_key' => 'desc_dates', 'func' => 'render_dates'],
        ]
    ],
    'account' => [
        'label_key' => 'cat_account', 'icon' => '💶',
        'items' => [
            'iva'  => ['title_key' => 'tool_iva', 'desc_key' => 'desc_iva', 'func' => 'render_iva'],
            'iban' => ['title_key' => 'tool_iban', 'desc_key' => 'desc_iban', 'func' => 'render_iban'],
        ]
    ],
    'office' => [
        'label_key' => 'cat_office', 'icon' => '📝',
        'items' => [
            'text'  => ['title_key' => 'tool_text', 'desc_key' => 'desc_text', 'func' => 'render_text'],
            'email' => ['title_key' => 'tool_email', 'desc_key' => 'desc_email', 'func' => 'render_email'],
            'pass'  => ['title_key' => 'tool_pass', 'desc_key' => 'desc_pass', 'func' => 'render_pass'],
        ]
    ]
];

$current_tool_id = $_GET['tool'] ?? null;
$current_tool_info = null;
foreach($CATALOG as $cat) {
    if(isset($cat['items'][$current_tool_id])) { $current_tool_info = $cat['items'][$current_tool_id]; break; }
}

// ==========================================
// 2. LOGICA BACKEND (PROCESSORI)
// ==========================================
$result_data = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    // --- LOGICA INTERVALLI ---
    if ($action == 'intervalli') {
        $tot_sec = 0;
        if (isset($_POST['h_start'])) {
            for ($i = 0; $i < count($_POST['h_start']); $i++) {
                $start = mktime((int)$_POST['h_start'][$i], (int)$_POST['m_start'][$i], 0, 1, 1, 2000);
                $end   = mktime((int)$_POST['h_end'][$i], (int)$_POST['m_end'][$i], 0, 1, 1, 2000);
                if ($end < $start) $end = mktime((int)$_POST['h_end'][$i], (int)$_POST['m_end'][$i], 0, 1, 2, 2000);
                $tot_sec += ($end - $start);
            }
        }
        $rh = floor($tot_sec / 3600); $rm = floor(($tot_sec / 60) % 60);
        $result_data = ['main' => sprintf("%02d:%02d", $rh, $rm), 'sub' => "$rh ore e $rm minuti totali"];
    }

    // --- LOGICA RECUPERI ---
    if ($action == 'recuperi') {
        $hrs = (int)$_POST['saldo_h']; $mins = (int)$_POST['saldo_m'];
        $std_h = (int)$_POST['std_h']; $std_m = (int)$_POST['std_m'];
        
        $total_minutes_saldo = ($hrs * 60) + $mins;
        $total_minutes_day = ($std_h * 60) + $std_m;
        
        if ($total_minutes_day > 0) {
            $days = floor($total_minutes_saldo / $total_minutes_day);
            $rem_mins_total = $total_minutes_saldo % $total_minutes_day;
            $rem_h = floor($rem_mins_total / 60);
            $rem_m = $rem_mins_total % 60;
            $result_data = [
                'main' => "$days Giorni",
                'sub' => "Resto: $rem_h ore e $rem_m minuti"
            ];
        }
    }

    // --- LOGICA DATE ---
    if ($action == 'dates') {
        try {
            $d1 = new DateTime($_POST['d1']);
            $d2 = new DateTime($_POST['d2']);
            $diff = $d1->diff($d2);
            $result_data = [
                'main' => $diff->y . " Anni, " . $diff->m . " Mesi, " . $diff->d . " Giorni",
                'sub' => "Giorni totali: " . $diff->days
            ];
        } catch(Exception $e) { $result_data = ['main' => 'Errore Date']; }
    }

    // --- LOGICA IVA (Rifatto da zero) ---
    if ($action == 'iva') {
        $imp = floatval(str_replace(',', '.', $_POST['importo']));
        $rate = floatval($_POST['aliquota']);
        $op = $_POST['operazione'];
        
        if($op == 'scorporo') {
            // Da Lordo a Netto
            $imponibile = $imp / (1 + ($rate/100));
            $iva = $imp - $imponibile;
            $totale = $imp;
        } elseif ($op == 'add') {
            // Da Netto a Lordo
            $imponibile = $imp;
            $iva = $imp * ($rate/100);
            $totale = $imp + $iva;
        } else {
            // Solo calcolo percentuale
            $imponibile = $imp; // "Base di calcolo"
            $iva = $imp * ($rate/100);
            $totale = 0; // Irrilevante
        }
        
        // Formattazione
        $f_imp = number_format($imponibile, 2, ',', '.');
        $f_iva = number_format($iva, 2, ',', '.');
        $f_tot = number_format($totale, 2, ',', '.');
        
        $result_data = ['html' => "
            <div style='display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; text-align:center;'>
                <div><small>".t('res_net')."</small><div style='font-size:1.2em; font-weight:bold'>€ $f_imp</div></div>
                <div><small>".t('res_vat')." ($rate%)</small><div style='font-size:1.2em; font-weight:bold; color:#d97706'>€ $f_iva</div></div>
                <div><small>".t('res_gross')."</small><div style='font-size:1.2em; font-weight:bold; color:#059669'>€ $f_tot</div></div>
            </div>
        "];
    }

    // --- LOGICA IBAN ---
    if ($action == 'iban') {
        $iban = strtoupper(str_replace(' ', '', $_POST['iban']));
        // Sposta i primi 4 caratteri alla fine
        $check = substr($iban, 4) . substr($iban, 0, 4);
        // Sostituisci lettere con numeri (A=10, B=11...)
        $num_iban = '';
        foreach (str_split($check) as $char) {
            $num_iban .= is_numeric($char) ? $char : (ord($char) - 55);
        }
        // Modulo 97 su stringa lunga
        $remainder = 0;
        for ($i = 0; $i < strlen($num_iban); $i++) {
            $remainder = ($remainder * 10 + (int)$num_iban[$i]) % 97;
        }
        
        if ($remainder == 1 && strlen($iban) == 27) { // Italia è 27
            $result_data = ['main' => t('msg_iban_ok'), 'color' => 'green'];
        } else {
            $result_data = ['main' => t('msg_iban_ko'), 'color' => 'red'];
        }
    }

    // --- LOGICA TESTO ---
    if ($action == 'text') {
        $txt = $_POST['text_in'];
        $op = $_POST['text_op'];
        
        if ($op == 'title') $out = mb_convert_case($txt, MB_CASE_TITLE, "UTF-8");
        elseif ($op == 'upper') $out = mb_strtoupper($txt, "UTF-8");
        elseif ($op == 'lower') $out = mb_strtolower($txt, "UTF-8");
        elseif ($op == 'oneline') $out = str_replace(["\r", "\n"], ' ', $txt); // Sostituisci a capo con spazio
        
        // Pulizia spazi doppi sempre attiva
        $out = preg_replace('/\s+/', ' ', $out);
        $result_data = ['raw' => trim($out)];
    }

    // --- LOGICA EMAIL ---
    if ($action == 'email') {
        $raw = $_POST['email_list'];
        $sep = ($_POST['separator'] == 'semicolon') ? '; ' : ', ';
        // Esplodi per a capo, trimma, rimuovi vuoti
        $lines = preg_split("/\r\n|\n|\r/", $raw);
        $clean = [];
        foreach($lines as $l) {
            $l = trim($l);
            if(!empty($l)) $clean[] = $l;
        }
        $out = implode($sep, $clean);
        $result_data = ['raw' => $out];
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('app_name'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS RESET & VARS */
        :root {
            --primary: #4338ca; /* Indigo 700 */
            --primary-light: #e0e7ff;
            --text-main: #1f2937;
            --text-sub: #6b7280;
            --bg-body: #f3f4f6;
            --bg-card: #ffffff;
            --border: #e5e7eb;
            --sidebar-w: 280px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-body); color: var(--text-main); margin: 0; display: flex; min-height: 100vh; overflow-x: hidden; }
        * { box-sizing: border-box; }
        a { text-decoration: none; color: inherit; }

        /* LAYOUT */
        .sidebar {
            width: var(--sidebar-w); background: var(--bg-card); border-right: 1px solid var(--border);
            display: flex; flex-direction: column; position: fixed; height: 100%; z-index: 50;
            transition: transform 0.3s ease;
        }
        .main-content { flex: 1; margin-left: var(--sidebar-w); display: flex; flex-direction: column; min-height: 100vh; transition: margin-left 0.3s ease; }
        
        /* HEADER MOBILE & BURGER */
        .mobile-header { display: none; background: white; padding: 15px; border-bottom: 1px solid var(--border); align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 40; }
        .burger-btn { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-main); }
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 45; }

        /* SIDEBAR CONTENT */
        .logo-area { height: 70px; display: flex; align-items: center; padding: 0 20px; border-bottom: 1px solid var(--border); font-weight: 800; font-size: 20px; color: var(--primary); gap: 10px; }
        .nav-scroll { flex: 1; overflow-y: auto; padding: 20px 0; }
        .cat-title { font-size: 11px; font-weight: 700; color: var(--text-sub); text-transform: uppercase; letter-spacing: 0.5px; padding: 15px 20px 5px; }
        .nav-item { display: flex; align-items: center; padding: 10px 20px; font-size: 14px; font-weight: 500; color: var(--text-main); border-left: 3px solid transparent; }
        .nav-item:hover { background: var(--primary-light); color: var(--primary); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); border-left-color: var(--primary); font-weight: 600; }

        /* MAIN AREA */
        .top-bar { height: 70px; background: var(--bg-card); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 30px; }
        .page-title { font-size: 18px; font-weight: 600; }
        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; width: 100%; }

        /* COMPONENTS */
        .card { background: var(--bg-card); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 30px; border: 1px solid var(--border); margin-bottom: 20px; }
        .wiz-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .wiz-card { background: white; border: 1px solid var(--border); padding: 25px; border-radius: 12px; transition: 0.2s; display: block; }
        .wiz-card:hover { border-color: var(--primary); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .wiz-title { font-weight: 700; margin-bottom: 5px; color: var(--primary); }
        .wiz-desc { font-size: 13px; color: var(--text-sub); line-height: 1.5; }

        /* FORMS */
        .input-group { margin-bottom: 15px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px; color: var(--text-main); }
        input[type="text"], input[type="number"], select, textarea { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 15px; font-family: inherit; outline: none; }
        input:focus, textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1); }
        .btn { background: var(--primary); color: white; border: none; padding: 12px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; width: 100%; font-size: 15px; transition: 0.2s; }
        .btn:hover { background: #3730a3; }
        .row-inputs { display: flex; gap: 10px; align-items: center; margin-bottom: 10px; }
        
        /* RESULTS */
        .result-box { background: #ecfdf5; border: 1px solid #d1fae5; border-radius: 8px; padding: 20px; text-align: center; margin-top: 20px; }
        .res-main { font-size: 24px; font-weight: 800; color: #065f46; margin: 5px 0; }
        .res-sub { color: #047857; font-size: 14px; }
        
        /* RESULT RAW TEXT AREA */
        .res-raw { background: #f9fafb; border: 1px solid #e5e7eb; padding: 15px; border-radius: 6px; font-family: monospace; font-size: 14px; word-break: break-all; text-align: left; }

        /* MOBILE RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 280px; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .mobile-header { display: flex; }
            .top-bar { display: none; } /* Use mobile header instead */
            .overlay.active { display: block; }
        }
    </style>
</head>
<body>

<div class="overlay" id="overlay" onclick="toggleMenu()"></div>

<aside class="sidebar" id="sidebar">
    <div class="logo-area">
        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        <?php echo t('app_name'); ?>
    </div>
    <div class="nav-scroll">
        <a href="<?php echo getUrl(); ?>" class="nav-item <?php echo !$current_tool_id ? 'active' : ''; ?>">
            Dashboard
        </a>
        <?php foreach($CATALOG as $cat_key => $cat): ?>
            <div class="cat-title"><?php echo t($cat['label_key']); ?></div>
            <?php foreach($cat['items'] as $item_key => $item): ?>
                <a href="<?php echo getUrl($item_key); ?>" class="nav-item <?php echo $current_tool_id == $item_key ? 'active' : ''; ?>">
                    <?php echo t($item['title_key']); ?>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</aside>

<div class="main-content">
    <header class="mobile-header">
        <button class="burger-btn" onclick="toggleMenu()">☰</button>
        <div style="font-weight:700; color:var(--primary);">UniTools</div>
        <div style="width:24px;"></div> </header>

    <div class="top-bar">
        <div class="page-title">
            <?php echo $current_tool_info ? t($current_tool_info['title_key']) : t('home_title'); ?>
        </div>
        <div style="font-size:14px; color:var(--text-sub);">
            <?php echo date('d/m/Y'); ?>
        </div>
    </div>

    <main class="container">
        
        <?php if (!$current_tool_id): ?>
            <div style="text-align:center; margin-bottom:40px;">
                <h2><?php echo t('select_tool'); ?></h2>
            </div>
            <div class="wiz-grid">
                <?php foreach($CATALOG as $cat): foreach($cat['items'] as $k => $i): ?>
                    <a href="<?php echo getUrl($k); ?>" class="wiz-card">
                        <div class="wiz-title"><?php echo t($i['title_key']); ?></div>
                        <div class="wiz-desc"><?php echo t($i['desc_key']); ?></div>
                    </a>
                <?php endforeach; endforeach; ?>
            </div>

        <?php elseif ($current_tool_info): ?>
            <?php call_user_func($current_tool_info['func'], $result_data); ?>
        <?php endif; ?>

    </main>
</div>

<script>
    function toggleMenu() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('active');
    }
    
    // Funzione Copia
    function copyToClipboard(id) {
        var copyText = document.getElementById(id);
        copyText.select();
        copyText.setSelectionRange(0, 99999); 
        navigator.clipboard.writeText(copyText.value);
        alert("<?php echo t('copied'); ?>");
    }
</script>
</body>
</html>

<?php
// ==========================================
// 3. FUNZIONI DI RENDER (VIEWS)
// ==========================================

// --- RENDER LINKS ---
function render_links($res) {
    // Esempio statico di link utili
    echo '<div class="wiz-grid">';
    $links = [
        ['YouPlanner', 'Occupazione aule e spazi', '#'],
        ['Ticket System', 'Apertura segnalazioni guasti', '#'],
        ['Modulistica', 'Repository moduli in PDF', '#'],
        ['Rubrica', 'Cerca persone e interni', '#']
    ];
    foreach($links as $l) {
        echo "<a href='{$l[2]}' target='_blank' class='wiz-card' style='text-align:center'>
                <div style='font-size:24px; margin-bottom:10px'>🔗</div>
                <div class='wiz-title'>{$l[0]}</div>
                <div class='wiz-desc'>{$l[1]}</div>
              </a>";
    }
    echo '</div>';
}

// --- RENDER INTERVALLI ---
function render_intervalli($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="intervalli">
        <p style="margin-bottom:20px; color:#666; font-size:14px;"><?php echo t('desc_intervalli'); ?></p>
        
        <div id="rows-container">
            <?php 
            $count = isset($_POST['h_start']) ? count($_POST['h_start']) : 1;
            for($i=0; $i<$count; $i++): ?>
            <div class="row-inputs">
                <span style="font-size:12px; width:40px; font-weight:bold"><?php echo t('lbl_from'); ?></span>
                <?php echo _sel("h_start[]", 23, $_POST['h_start'][$i]??8); ?> :
                <?php echo _sel("m_start[]", 59, $_POST['m_start'][$i]??0); ?>
                <span style="font-size:12px; width:40px; font-weight:bold; text-align:right"><?php echo t('lbl_to'); ?></span>
                <?php echo _sel("h_end[]", 23, $_POST['h_end'][$i]??12); ?> :
                <?php echo _sel("m_end[]", 59, $_POST['m_end'][$i]??0); ?>
            </div>
            <?php endfor; ?>
        </div>
        
        <button type="button" class="btn" style="background:#e0e7ff; color:#4338ca; margin:10px 0" onclick="addIntRow()">+ Aggiungi Riga</button>
        <button type="submit" class="btn"><?php echo t('calc'); ?></button>

        <?php if($res): ?>
            <div class="result-box">
                <div class="res-main"><?php echo $res['main']; ?></div>
                <div class="res-sub"><?php echo $res['sub']; ?></div>
            </div>
        <?php endif; ?>
    </form>
    <script>
        function addIntRow() {
            var div = document.createElement('div');
            div.className = 'row-inputs';
            div.innerHTML = document.querySelector('.row-inputs').innerHTML;
            document.getElementById('rows-container').appendChild(div);
        }
    </script>
    <?php
}

// --- RENDER RECUPERI ---
function render_recuperi($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="recuperi">
        
        <label><?php echo t('lbl_saldo'); ?></label>
        <div class="row-inputs">
            <input type="number" name="saldo_h" placeholder="Ore" value="<?php echo $_POST['saldo_h']??''; ?>" required>
            <input type="number" name="saldo_m" placeholder="Min" value="<?php echo $_POST['saldo_m']??'00'; ?>">
        </div>

        <label><?php echo t('lbl_std_day'); ?></label>
        <div class="row-inputs">
            <select name="std_h">
                <option value="6" <?php echo ($_POST['std_h']??'')==6?'selected':''; ?>>6 Ore (Part-time)</option>
                <option value="7" <?php echo ($_POST['std_h']??7)==7?'selected':''; ?>>7 Ore (Standard)</option>
                <option value="8" <?php echo ($_POST['std_h']??'')==8?'selected':''; ?>>8 Ore</option>
            </select>
            <select name="std_m">
                <option value="0" <?php echo ($_POST['std_m']??'')==0?'selected':''; ?>>00 Min</option>
                <option value="12" <?php echo ($_POST['std_m']??12)==12?'selected':''; ?>>12 Min (Standard)</option>
                <option value="30" <?php echo ($_POST['std_m']??'')==30?'selected':''; ?>>30 Min</option>
            </select>
        </div>

        <button type="submit" class="btn" style="margin-top:15px"><?php echo t('calc'); ?></button>

        <?php if($res): ?>
            <div class="result-box">
                <div style="font-size:12px; text-transform:uppercase"><?php echo t('res_recuperi'); ?></div>
                <div class="res-main"><?php echo $res['main']; ?></div>
                <div class="res-sub"><?php echo $res['sub']; ?></div>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- RENDER DATES ---
function render_dates($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="dates">
        <div class="input-group">
            <label><?php echo t('lbl_start_date'); ?></label>
            <input type="date" name="d1" value="<?php echo $_POST['d1']??''; ?>" required>
        </div>
        <div class="input-group">
            <label><?php echo t('lbl_end_date'); ?></label>
            <input type="date" name="d2" value="<?php echo $_POST['d2']??date('Y-m-d'); ?>" required>
        </div>
        <button type="submit" class="btn"><?php echo t('calc'); ?></button>

        <?php if($res): ?>
            <div class="result-box">
                <div class="res-main"><?php echo $res['main']; ?></div>
                <div class="res-sub"><?php echo $res['sub']; ?></div>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- RENDER IVA (NEW DESIGN) ---
function render_iva($res) {
    $sel_op = $_POST['operazione'] ?? 'scorporo';
    $sel_al = $_POST['aliquota'] ?? '22';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="iva">
        
        <div class="input-group">
            <label><?php echo t('lbl_amount'); ?> (€)</label>
            <input type="text" name="importo" placeholder="es. 1220,50" value="<?php echo $_POST['importo']??''; ?>" required style="font-size:18px; font-weight:bold;">
        </div>

        <div class="row-inputs" style="margin-bottom:20px;">
            <div style="flex:1">
                <label><?php echo t('lbl_rate'); ?></label>
                <select name="aliquota">
                    <option value="4" <?php echo $sel_al=='4'?'selected':''; ?>>4% (Minima)</option>
                    <option value="10" <?php echo $sel_al=='10'?'selected':''; ?>>10% (Ridotta)</option>
                    <option value="22" <?php echo $sel_al=='22'?'selected':''; ?>>22% (Ordinaria)</option>
                </select>
            </div>
        </div>

        <label>Tipo Operazione</label>
        <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:20px;">
            <label style="font-weight:400; background:#f9fafb; padding:10px; border:1px solid #e5e7eb; border-radius:6px; cursor:pointer">
                <input type="radio" name="operazione" value="scorporo" <?php echo $sel_op=='scorporo'?'checked':''; ?>> 
                <strong><?php echo t('op_scorporo'); ?></strong>
            </label>
            <label style="font-weight:400; background:#f9fafb; padding:10px; border:1px solid #e5e7eb; border-radius:6px; cursor:pointer">
                <input type="radio" name="operazione" value="add" <?php echo $sel_op=='add'?'checked':''; ?>> 
                <?php echo t('op_add'); ?>
            </label>
            <label style="font-weight:400; background:#f9fafb; padding:10px; border:1px solid #e5e7eb; border-radius:6px; cursor:pointer">
                <input type="radio" name="operazione" value="calc" <?php echo $sel_op=='calc'?'checked':''; ?>> 
                <?php echo t('op_calc'); ?>
            </label>
        </div>

        <button type="submit" class="btn"><?php echo t('calc'); ?></button>

        <?php if($res): ?>
            <div class="result-box" style="padding:15px; text-align:left;">
                <?php echo $res['html']; ?>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- RENDER IBAN ---
function render_iban($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="iban">
        <label><?php echo t('lbl_iban'); ?></label>
        <input type="text" name="iban" placeholder="IT00X..." value="<?php echo $_POST['iban']??''; ?>" style="text-transform:uppercase" required>
        <button type="submit" class="btn" style="margin-top:15px">Verifica</button>
        
        <?php if($res): ?>
            <div class="result-box" style="background:<?php echo $res['color']=='green'?'#ecfdf5':'#fef2f2'; ?>; border-color:<?php echo $res['color']=='green'?'#d1fae5':'#fecaca'; ?>">
                <strong style="color:<?php echo $res['color']; ?>"><?php echo $res['main']; ?></strong>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- RENDER TEXT ---
function render_text($res) {
    $sel = $_POST['text_op'] ?? 'title';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="text">
        <label><?php echo t('lbl_text_in'); ?></label>
        <textarea name="text_in" rows="5"><?php echo $_POST['text_in']??''; ?></textarea>
        
        <div style="margin:15px 0;">
            <select name="text_op">
                <option value="title" <?php echo $sel=='title'?'selected':''; ?>><?php echo t('op_titlecase'); ?></option>
                <option value="oneline" <?php echo $sel=='oneline'?'selected':''; ?>><?php echo t('op_oneline'); ?></option>
                <option value="upper" <?php echo $sel=='upper'?'selected':''; ?>><?php echo t('op_uppercase'); ?></option>
                <option value="lower" <?php echo $sel=='lower'?'selected':''; ?>><?php echo t('op_lowercase'); ?></option>
            </select>
        </div>
        <button type="submit" class="btn">Pulisci Testo</button>

        <?php if($res): ?>
            <div style="margin-top:20px;">
                <label>Risultato (Clicca per copiare):</label>
                <textarea id="resTxt" rows="5" readonly><?php echo $res['raw']; ?></textarea>
                <button type="button" class="btn" style="margin-top:5px; background:#6b7280" onclick="copyToClipboard('resTxt')"><?php echo t('copy'); ?></button>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- RENDER EMAIL ---
function render_email($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="email">
        <label><?php echo t('lbl_email_col'); ?></label>
        <textarea name="email_list" rows="6" placeholder="mario.rossi@unipv.it&#10;luigi.verdi@unipv.it"><?php echo $_POST['email_list']??''; ?></textarea>
        
        <label style="margin-top:15px"><?php echo t('lbl_separator'); ?></label>
        <select name="separator">
            <option value="semicolon"><?php echo t('sep_semicolon'); ?></option>
            <option value="comma"><?php echo t('sep_comma'); ?></option>
        </select>
        
        <button type="submit" class="btn" style="margin-top:15px">Formatta</button>

        <?php if($res): ?>
            <div style="margin-top:20px;">
                <label>Risultato:</label>
                <textarea id="resEmail" rows="4" readonly><?php echo $res['raw']; ?></textarea>
                <button type="button" class="btn" style="margin-top:5px; background:#6b7280" onclick="copyToClipboard('resEmail')"><?php echo t('copy'); ?></button>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- RENDER PASSWORD ---
function render_pass($res) {
    // Generazione locale JS per velocità, ma faccio in PHP per coerenza "no deps"
    $pass = '';
    if (isset($_POST['gen'])) {
        $syllables = ["ba","be","bi","bo","bu","da","de","di","do","du","ka","ke","ki","ko","ku","ma","me","mi","mo","mu","na","ne","ni","no","nu","ra","re","ri","ro","ru","ta","te","ti","to","tu","va","ve","vi","vo","vu"];
        $nums = [2,3,4,5,6,7,8,9];
        $syms = ['@','#','!','$','?'];
        
        $p = ucfirst($syllables[array_rand($syllables)]) . 
             $syllables[array_rand($syllables)] . 
             $nums[array_rand($nums)] . $nums[array_rand($nums)] . 
             ucfirst($syllables[array_rand($syllables)]) . 
             $syms[array_rand($syms)];
        $pass = $p;
    }
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="gen" value="1">
        <div style="text-align:center; padding:20px;">
            <?php if($pass): ?>
                <div style="font-size:32px; font-family:monospace; font-weight:bold; color:#4338ca; margin-bottom:20px; word-break:break-all;">
                    <?php echo $pass; ?>
                </div>
                <input type="hidden" id="passHidden" value="<?php echo $pass; ?>">
                <button type="button" class="btn" style="background:#059669; margin-bottom:15px;" onclick="copyToClipboard('passHidden')"><?php echo t('copy'); ?></button>
            <?php else: ?>
                <div style="color:#aaa; font-style:italic; margin-bottom:20px;">Premi genera...</div>
            <?php endif; ?>
            
            <button type="submit" class="btn"><?php echo t('btn_gen_pass'); ?></button>
        </div>
    </form>
    <?php
}

// Helper Select
function _sel($name, $max, $sel=0) {
    $h = "<select name='$name'>";
    for($i=0; $i<=$max; $i++) {
        $v = sprintf("%02d", $i);
        $s = ($i == $sel) ? 'selected' : '';
        $h .= "<option value='$v' $s>$v</option>";
    }
    $h .= "</select>";
    return $h;
}
?>