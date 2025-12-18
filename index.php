<?php
/**
 * UNITOOLS - INTEGRATED UTILITY DASHBOARD
 * -----------------------------------------------------
 * A standalone PHP utility suite for administrative tasks.
 * No external dependencies required.
 *
 * @author  Vincenzo Oriti
 * @version 1.6
 * @license CC BY-NC-SA 4.0
 */

// ---------------------------------------------------------
// 1. BOOTSTRAP & CONFIGURATION
// ---------------------------------------------------------
session_start();

// Disable error display for production environment
error_reporting(E_ALL);
ini_set('display_errors', 0); 

// Language Management (Session based)
$lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'it');
$_SESSION['lang'] = $lang;

// ---------------------------------------------------------
// 2. TRANSLATION DICTIONARY
// ---------------------------------------------------------
$trans = [
    'it' => [
        // GLOBAL UI
        'app_name' => 'UniTools',
        'home_title' => 'Dashboard Operativa',
        'select_tool' => 'Seleziona uno strumento',
        'back_dash' => '← Torna alla Dashboard',
        'calc' => 'Calcola Risultato',
        'verify' => 'Verifica',
        'format' => 'Formatta',
        'clean' => 'Pulisce Testo',
        'generate' => 'Genera',
        'copy' => 'Copia',
        'copied' => 'Copiato negli appunti!',
        
        // CATEGORIES (SIDEBAR)
        'cat_links' => 'Link di Ateneo',
        'intro_links' => 'Raccolta organizzata di collegamenti rapidi alle piattaforme interne e modulistica.',
        'cat_time' => 'Gestione Orari',
        'intro_time' => 'Strumenti per il calcolo ore lavorate, verifica timbrature e conversioni.',
        'cat_account' => 'Contabilità',
        'intro_account' => 'Utility per calcolo IVA, verifica codici bancari e operazioni fiscali.',
        'cat_office' => 'Ufficio & Utilità',
        'intro_office' => 'Tool per pulizia testi, liste email e sicurezza.',

        // TOOLS TITLES & DESCRIPTIONS
        'tool_intervalli' => 'Calcolo Ore Lavorate',
        'desc_intervalli' => 'Calcolo manuale del tempo lavorato sommando vari intervalli.',
        'note_intervalli' => 'N.B. Se la pausa pranzo è inferiore a 10 minuti, il sistema toglie in automatico i 10 minuti minimi.',
        
        'tool_recuperi' => 'Convertitore Recuperi',
        'desc_recuperi' => 'Calcola giorni di ferie copribili con saldo straordinari.',
        'res_recuperi_ok' => 'Puoi assentarti fino al:',
        'res_recuperi_rem' => 'Ti resterà un saldo di:',
        
        'tool_scadenza' => 'Scadenza e Durata',
        'desc_scadenza' => 'Calcola l\'ora di fine o di inizio in base alla durata.',
        'lbl_ref_time' => 'Orario Inizio/Fine',
        
        'tool_dates' => 'Differenza Date',
        'desc_dates' => 'Calcola l\'intervallo esatto (anni, mesi, giorni) tra due date.',
        
        'tool_iva' => 'Gestione IVA',
        'desc_iva' => 'Scorporo e applicazione aliquote IVA.',
        
        'tool_iban' => 'Verifica IBAN',
        'desc_iban' => 'Controllo formale della correttezza di un codice IBAN.',
        
        'tool_text' => 'Sanificatore Testo',
        'desc_text' => 'Pulisce testi da PDF, rimuove spazi doppi e corregge maiuscole.',
        
        'tool_email' => 'Lista Email',
        'desc_email' => 'Formatta colonne Excel in liste per Outlook/Gmail.',
        
        'tool_pass' => 'Generatore Password',
        'desc_pass' => 'Crea password sicure ma pronunciabili per helpdesk.',

        // GENERIC LABELS
        'lbl_from' => 'DA', 'lbl_to' => 'A',
        'lbl_days' => 'Giorni', 'lbl_hours' => 'Ore', 'lbl_mins' => 'Min',
        'lbl_start_date' => 'Data Inizio', 'lbl_end_date' => 'Data Fine',
        'lbl_amount' => 'Importo (€)', 'lbl_rate' => 'Aliquota', 'lbl_op' => 'Operazione',
        'lbl_iban_code' => 'Codice IBAN',
        'lbl_input_text' => 'Testo Input', 'lbl_result' => 'Risultato',
        'lbl_separator' => 'Separatore',
        
        // MESSAGES
        'msg_iban_ok' => 'IBAN formalmente CORRETTO',
        'msg_iban_ko' => 'ERRORE: IBAN non valido',
    ],
    'en' => [
        // GLOBAL UI
        'app_name' => 'UniTools',
        'home_title' => 'Operations Dashboard',
        'select_tool' => 'Select a tool',
        'back_dash' => '← Back to Dashboard',
        'calc' => 'Calculate',
        'verify' => 'Verify',
        'format' => 'Format',
        'clean' => 'Clean Text',
        'generate' => 'Generate',
        'copy' => 'Copy',
        'copied' => 'Copied to clipboard!',
        
        // CATEGORIES
        'cat_links' => 'University Links',
        'intro_links' => 'Organized collection of quick links to internal platforms.',
        'cat_time' => 'Time Management',
        'intro_time' => 'Tools for working hours calculation and overtime conversion.',
        'cat_account' => 'Accounting',
        'intro_account' => 'Utilities for VAT calculation and bank code verification.',
        'cat_office' => 'Office Utilities',
        'intro_office' => 'Tools for text cleaning, email lists and password generation.',

        // TOOLS
        'tool_intervalli' => 'Work Hours Calc',
        'desc_intervalli' => 'Manual calculation of working time by summing intervals.',
        'note_intervalli' => 'Note: System automatically deducts 10 mins if lunch break is shorter.',
        
        'tool_recuperi' => 'Overtime Converter',
        'desc_recuperi' => 'Calculate days off based on overtime balance.',
        'res_recuperi_ok' => 'You can be away until:',
        'res_recuperi_rem' => 'Remaining balance:',
        
        'tool_scadenza' => 'Deadline & Duration',
        'desc_scadenza' => 'Calculate end time or start time based on duration.',
        'lbl_ref_time' => 'Start/End Time',
        
        'tool_dates' => 'Date Difference',
        'desc_dates' => 'Calculate exact interval (years, months, days) between dates.',
        
        'tool_iva' => 'VAT Manager',
        'desc_iva' => 'Extract or apply VAT rates.',
        
        'tool_iban' => 'IBAN Validator',
        'desc_iban' => 'Formal validation of IBAN codes.',
        
        'tool_text' => 'Text Sanitizer',
        'desc_text' => 'Clean text from PDFs, fix caps and spacing.',
        
        'tool_email' => 'Email List Formatter',
        'desc_email' => 'Convert Excel columns to Outlook/Gmail lists.',
        
        'tool_pass' => 'Password Generator',
        'desc_pass' => 'Create readable secure passwords.',

        // GENERIC LABELS
        'lbl_from' => 'FROM', 'lbl_to' => 'TO',
        'lbl_days' => 'Days', 'lbl_hours' => 'Hrs', 'lbl_mins' => 'Mins',
        'lbl_start_date' => 'Start Date', 'lbl_end_date' => 'End Date',
        'lbl_amount' => 'Amount (€)', 'lbl_rate' => 'Rate', 'lbl_op' => 'Operation',
        'lbl_iban_code' => 'IBAN Code',
        'lbl_input_text' => 'Input Text', 'lbl_result' => 'Result',
        'lbl_separator' => 'Separator',
        
        // MESSAGES
        'msg_iban_ok' => 'IBAN is VALID',
        'msg_iban_ko' => 'ERROR: Invalid IBAN',
    ]
];

// Helper: Translation function
function t($key) { 
    global $trans, $lang; 
    return $trans[$lang][$key] ?? $key; 
}

// Helper: URL Builder
function getUrl($tool = null, $newLang = null) { 
    global $lang; 
    $l = $newLang ?? $lang;
    return "?lang=$l" . ($tool ? "&tool=$tool" : ""); 
}

// ---------------------------------------------------------
// 3. TOOLS CATALOG CONFIGURATION
// ---------------------------------------------------------
$CATALOG = [
    'links' => [
        'label_key' => 'cat_links', 
        'intro_key' => 'intro_links', 
        'icon' => '🏛️',
        'items' => [
            // Placeholder item for Dashboard Link logic
            'dashboard' => ['title' => 'Dashboard', 'func' => 'render_dashboard_links'] 
        ]
    ],
    'time' => [
        'label_key' => 'cat_time', 
        'intro_key' => 'intro_time', 
        'icon' => '⏱️',
        'items' => [
            'intervalli' => ['key' => 'tool_intervalli', 'desc' => 'desc_intervalli', 'func' => 'render_intervalli'],
            'recuperi'   => ['key' => 'tool_recuperi', 'desc' => 'desc_recuperi', 'func' => 'render_recuperi'],
            'scadenza'   => ['key' => 'tool_scadenza', 'desc' => 'desc_scadenza', 'func' => 'render_scadenza'],
            'dates'      => ['key' => 'tool_dates', 'desc' => 'desc_dates', 'func' => 'render_dates'],
        ]
    ],
    'account' => [
        'label_key' => 'cat_account', 
        'intro_key' => 'intro_account', 
        'icon' => '💶',
        'items' => [
            'iva'  => ['key' => 'tool_iva', 'desc' => 'desc_iva', 'func' => 'render_iva'],
            'iban' => ['key' => 'tool_iban', 'desc' => 'desc_iban', 'func' => 'render_iban'],
        ]
    ],
    'office' => [
        'label_key' => 'cat_office', 
        'intro_key' => 'intro_office', 
        'icon' => '📝',
        'items' => [
            'text'  => ['key' => 'tool_text', 'desc' => 'desc_text', 'func' => 'render_text'],
            'email' => ['key' => 'tool_email', 'desc' => 'desc_email', 'func' => 'render_email'],
            'pass'  => ['key' => 'tool_pass', 'desc' => 'desc_pass', 'func' => 'render_pass'],
        ]
    ]
];

// Determine current tool from URL
$current_tool_id = $_GET['tool'] ?? null;
$current_tool_info = null;

foreach($CATALOG as $cat) {
    if(isset($cat['items'][$current_tool_id])) { 
        $current_tool_info = $cat['items'][$current_tool_id]; 
        break; 
    }
}

// ---------------------------------------------------------
// 4. BACKEND LOGIC (PROCESSORS)
// ---------------------------------------------------------
$result_data = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    // --- TOOL: INTERVALS SUM ---
    if ($action == 'intervalli') {
        $tot_sec = 0;
        if (isset($_POST['h_start'])) {
            for ($i = 0; $i < count($_POST['h_start']); $i++) {
                $s_h = (int)$_POST['h_start'][$i]; $s_m = (int)$_POST['m_start'][$i];
                $e_h = (int)$_POST['h_end'][$i];   $e_m = (int)$_POST['m_end'][$i];
                
                $start = mktime($s_h, $s_m, 0, 1, 1, 2000);
                $end   = mktime($e_h, $e_m, 0, 1, 1, 2000);
                
                // Handle midnight crossover
                if ($end < $start) $end = mktime($e_h, $e_m, 0, 1, 2, 2000); 
                $tot_sec += ($end - $start);
            }
        }
        $rh = floor($tot_sec / 3600); 
        $rm = floor(($tot_sec / 60) % 60);
        $result_data = ['main' => sprintf("%02d:%02d", $rh, $rm), 'sub' => "$rh h $rm min"];
    }

    // --- TOOL: OVERTIME CONVERTER ---
    if ($action == 'recuperi') {
        $saldo_min = ((int)$_POST['saldo_h'] * 60) + (int)$_POST['saldo_m'];
        $schedule = [];
        // Map weekly schedule
        for($d=1; $d<=5; $d++) {
            $val = $_POST["day_$d"];
            list($dh, $dm) = explode(':', $val);
            $schedule[$d] = ($dh * 60) + $dm;
        }

        try {
            $date = new DateTime($_POST['start_date']);
            $final_date = clone $date;
            
            while ($saldo_min > 0) {
                $dow = $date->format('N'); // 1-7
                if ($dow >= 6) { // Skip weekends
                    $date->modify('+1 day');
                    continue;
                }
                $required = $schedule[$dow];
                
                if ($saldo_min >= $required) {
                    $saldo_min -= $required;
                    $final_date = clone $date;
                    $date->modify('+1 day');
                } else {
                    break; 
                }
            }
            $rem_h = floor($saldo_min / 60);
            $rem_m = $saldo_min % 60;
            
            $result_data = [
                'type' => 'recuperi',
                'date' => $final_date->format('d/m/Y') . " (" . $final_date->format('l') . ")",
                'resto' => sprintf("%02d h %02d min", $rem_h, $rem_m)
            ];
        } catch (Exception $e) { $result_data = ['error' => 'Invalid Date']; }
    }

    // --- TOOL: DEADLINE CALCULATOR ---
    if ($action == 'scadenza') {
        try {
            $base = new DateTime();
            $base->setTime((int)$_POST['start_h'], (int)$_POST['start_m']);
            if(!empty($_POST['date_d'])) {
                $base->setDate((int)$_POST['date_y'], (int)$_POST['date_m'], (int)$_POST['date_d']);
            }
            
            $di = "PT" . (int)$_POST['dur_h'] . "H" . (int)$_POST['dur_m'] . "M";
            $pi = "PT" . (int)$_POST['pau_h'] . "H" . (int)$_POST['pau_m'] . "M";
            
            $durata = new DateInterval($di);
            $pausa = new DateInterval($pi);
            
            $mode = $_POST['calc_mode'];
            if ($mode == 'end') {
                $base->add($durata);
                $base->add($pausa);
                $lbl = "End Time:";
            } else {
                $base->sub($durata);
                $base->sub($pausa);
                $lbl = "Start Time:";
            }
            $result_data = ['main' => $base->format('H:i'), 'sub' => $lbl . " " . $base->format('d/m/Y')];
        } catch (Exception $e) { $result_data = ['main' => 'Error']; }
    }

    // --- TOOL: DATE DIFFERENCE ---
    if ($action == 'dates') {
        try {
            $d1 = new DateTime($_POST['d1']);
            $d2 = new DateTime($_POST['d2']);
            $diff = $d1->diff($d2);
            $result_data = [
                'main' => $diff->y . " Y, " . $diff->m . " M, " . $diff->d . " D",
                'sub' => "Total days: " . $diff->days
            ];
        } catch(Exception $e) { $result_data = ['main' => 'Error']; }
    }

    // --- TOOL: VAT MANAGER ---
    if ($action == 'iva') {
        $imp = floatval(str_replace(',', '.', $_POST['importo']));
        $rate = floatval($_POST['aliquota']);
        $op = $_POST['operazione'];
        
        if($op == 'scorporo') { 
            $imp_n = $imp / (1 + ($rate/100)); 
            $iva = $imp - $imp_n; 
            $tot = $imp; 
        } elseif ($op == 'add') { 
            $imp_n = $imp; 
            $iva = $imp * ($rate/100); 
            $tot = $imp + $iva; 
        } else { // Calc only
            $imp_n = $imp; 
            $iva = $imp * ($rate/100); 
            $tot = 0; 
        }
        
        $result_data = ['html' => "<div style='display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; text-align:center;'>
            <div><small>Net</small><div style='font-weight:bold'>€ ".number_format($imp_n,2,',','.')."</div></div>
            <div><small>VAT</small><div style='font-weight:bold; color:#d97706'>€ ".number_format($iva,2,',','.')."</div></div>
            <div><small>Gross</small><div style='font-weight:bold; color:#059669'>€ ".number_format($tot,2,',','.')."</div></div></div>"];
    }

    // --- TOOL: IBAN VALIDATOR ---
    if ($action == 'iban') {
        $iban = strtoupper(str_replace(' ', '', $_POST['iban']));
        $check = substr($iban, 4) . substr($iban, 0, 4);
        $num_iban = '';
        foreach (str_split($check) as $char) {
            $num_iban .= is_numeric($char) ? $char : (ord($char) - 55);
        }
        $remainder = 0;
        for ($i = 0; $i < strlen($num_iban); $i++) {
            $remainder = ($remainder * 10 + (int)$num_iban[$i]) % 97;
        }
        
        $isValid = ($remainder == 1 && strlen($iban) >= 15 && strlen($iban) <= 34); // Basic len check
        $result_data = [
            'main' => $isValid ? t('msg_iban_ok') : t('msg_iban_ko'), 
            'color' => $isValid ? 'green' : 'red'
        ];
    }

    // --- TOOL: TEXT CLEANER ---
    if ($action == 'text') {
        $txt = $_POST['text_in'];
        $op = $_POST['text_op'];
        
        if ($op == 'title') $out = mb_convert_case($txt, MB_CASE_TITLE, "UTF-8");
        elseif ($op == 'upper') $out = mb_strtoupper($txt, "UTF-8");
        elseif ($op == 'lower') $out = mb_strtolower($txt, "UTF-8");
        elseif ($op == 'oneline') $out = str_replace(["\r", "\n"], ' ', $txt);
        
        $out = preg_replace('/\s+/', ' ', $out ?? $txt); // Always clean spaces
        $result_data = ['raw' => trim($out)];
    }

    // --- TOOL: EMAIL LIST ---
    if ($action == 'email') {
        $raw = $_POST['email_list'];
        $sep = ($_POST['separator'] == 'semicolon') ? '; ' : ', ';
        $lines = preg_split("/\r\n|\n|\r/", $raw);
        $clean = [];
        foreach($lines as $l) {
            $l = trim($l);
            if(!empty($l)) $clean[] = $l;
        }
        $result_data = ['raw' => implode($sep, $clean)];
    }

    // --- TOOL: PASSWORD ---
    if ($action == 'pass') {
        $syls = ["ba","be","bi","bo","bu","da","de","di","do","du","ka","ke","ki","ko","ku","ma","me","mi","mo","mu","na","ne","ni","no","nu","ra","re","ri","ro","ru","ta","te","ti","to","tu","va","ve","vi","vo","vu"];
        $nums = [2,3,4,5,6,7,8,9];
        $syms = ['@','#','!','$','?'];
        
        $p = ucfirst($syls[array_rand($syls)]) . $syls[array_rand($syls)] . 
             $nums[array_rand($nums)] . $nums[array_rand($nums)] . 
             ucfirst($syls[array_rand($syls)]) . $syms[array_rand($syms)];
        $result_data = ['raw' => $p];
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
        .top-bar { height: 60px; background: white; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; }
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
        <a href="<?php echo getUrl(); ?>" class="nav-item back-link <?php echo !$current_tool_id ? 'active' : ''; ?>">
            <?php echo t('back_dash'); ?>
        </a>

        <?php foreach($CATALOG as $cat_id => $cat): if($cat_id == 'links') continue; ?>
            <div class="cat-header"><?php echo t($cat['label_key']); ?></div>
            <?php foreach($cat['items'] as $item_id => $item): ?>
                <a href="<?php echo getUrl($item_id); ?>" class="nav-item <?php echo $current_tool_id == $item_id ? 'active' : ''; ?>">
                    <?php echo t($item['key']); ?>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </nav>
</aside>

<div class="main-content">
    
    <div class="top-bar">
        <div style="font-weight:600">
            <?php echo $current_tool_info ? t($current_tool_info['key']) : t('home_title'); ?>
        </div>
        <div class="lang-switch">
            <a href="<?php echo getUrl($current_tool_id, 'it'); ?>" class="<?php echo $lang=='it'?'active':''; ?>" title="Italiano">🇮🇹</a>
            <a href="<?php echo getUrl($current_tool_id, 'en'); ?>" class="<?php echo $lang=='en'?'active':''; ?>" title="English">🇬🇧</a>
        </div>
    </div>

    <header class="mobile-header">
        <button class="burger-btn" onclick="toggleMenu()">☰</button>
        <div style="font-weight:700; color:var(--primary)">UniTools</div>
        <div class="lang-switch">
            <a href="<?php echo getUrl($current_tool_id, 'it'); ?>" class="<?php echo $lang=='it'?'active':''; ?>">🇮🇹</a>
            <a href="<?php echo getUrl($current_tool_id, 'en'); ?>" class="<?php echo $lang=='en'?'active':''; ?>">🇬🇧</a>
        </div>
    </header>

    <main class="container">
        
        <?php if (!$current_tool_id): ?>
            <h1 style="margin-bottom:30px"><?php echo t('home_title'); ?></h1>

            <section class="dash-section">
                <div class="dash-sec-title">🏛️ <?php echo t('cat_links'); ?></div>
                <div class="dash-sec-intro"><?php echo t('intro_links'); ?></div>
                <div class="sub-cat-grid">
                    <div class="link-card">
                        <div class="lc-head">Didattica & Aule</div>
                        <div class="lc-desc">YouPlanner, Prenotazione Aule, Orari Lezioni, Esse3 Docenti.</div>
                    </div>
                    <div class="link-card">
                        <div class="lc-head">Personale & Presenze</div>
                        <div class="lc-desc">Cartellino Web, Cedolino (U-Gov), Piano Ferie, Ticket HR.</div>
                    </div>
                    <div class="link-card">
                        <div class="lc-head">Amministrazione</div>
                        <div class="lc-desc">Protocollo Titulus, U-Gov Contabilità, Repertorio Decreti.</div>
                    </div>
                </div>
            </section>

            <?php foreach($CATALOG as $cat_id => $cat): if($cat_id == 'links') continue; ?>
                <section class="dash-section">
                    <div class="dash-sec-title"><?php echo $cat['icon'] . ' ' . t($cat['label_key']); ?></div>
                    <div class="dash-sec-intro"><?php echo t($cat['intro_key']); ?></div>
                    <div class="sub-cat-grid">
                        <?php foreach($cat['items'] as $item_id => $item): ?>
                            <a href="<?php echo getUrl($item_id); ?>" class="link-card" style="text-decoration:none">
                                <div class="lc-head"><?php echo t($item['key']); ?></div>
                                <div class="lc-desc" style="color:#6b7280"><?php echo t($item['desc']); ?></div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>

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
function copyText(id) {
    var copyText = document.getElementById(id);
    if(copyText) {
        navigator.clipboard.writeText(copyText.innerText || copyText.value);
        alert('<?php echo t('copied'); ?>');
    }
}
</script>
</body>
</html>

<?php
// ==========================================
// 5. VIEW RENDER FUNCTIONS
// ==========================================

// --- TOOL: INTERVALS ---
function render_intervalli($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="intervalli">
        <div class="tool-title"><?php echo t('tool_intervalli'); ?></div>
        <div class="tool-desc"><?php echo t('desc_intervalli'); ?></div>
        
        <div style="background:#fef2f2; color:#991b1b; padding:10px; border-radius:6px; font-size:13px; margin-bottom:20px; border:1px solid #fecaca;">
            <strong>ATTENZIONE:</strong> <?php echo t('note_intervalli'); ?>
        </div>

        <div id="rows-wrap">
            <?php $count = isset($_POST['h_start']) ? count($_POST['h_start']) : 1;
            for($i=0; $i<$count; $i++): ?>
            <div class="row-inputs" style="display:flex; gap:10px; margin-bottom:10px; align-items:center;">
                <span style="font-size:12px; width:30px; font-weight:bold"><?php echo t('lbl_from'); ?>:</span>
                <input type="number" name="h_start[]" min="0" max="23" placeholder="HH" value="<?php echo $_POST['h_start'][$i]??''; ?>" required> :
                <input type="number" name="m_start[]" min="0" max="59" placeholder="MM" value="<?php echo $_POST['m_start'][$i]??''; ?>">
                
                <span style="font-size:12px; width:30px; font-weight:bold; text-align:right"><?php echo t('lbl_to'); ?>:</span>
                <input type="number" name="h_end[]" min="0" max="23" placeholder="HH" value="<?php echo $_POST['h_end'][$i]??''; ?>" required> :
                <input type="number" name="m_end[]" min="0" max="59" placeholder="MM" value="<?php echo $_POST['m_end'][$i]??''; ?>">
            </div>
            <?php endfor; ?>
        </div>
        
        <button type="button" onclick="addIntRow()" style="background:#f3f4f6; color:#374151; border:1px solid #d1d5db; padding:8px; width:100%; border-radius:6px; margin-top:10px; cursor:pointer">+ <?php echo t('lbl_hours'); ?></button>
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
            div.style.cssText = 'display:flex; gap:10px; margin-bottom:10px; align-items:center;';
            div.innerHTML = document.querySelector('.row-inputs').innerHTML;
            div.querySelectorAll('input').forEach(i => i.value = '');
            document.getElementById('rows-wrap').appendChild(div);
        }
    </script>
    <?php
}

// --- TOOL: OVERTIME ---
function render_recuperi($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="recuperi">
        <div class="tool-title"><?php echo t('tool_recuperi'); ?></div>
        <div class="tool-desc"><?php echo t('desc_recuperi'); ?></div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
            <div>
                <label>Saldo Ore</label>
                <div style="display:flex; gap:5px;">
                    <input type="number" name="saldo_h" placeholder="HH" required value="<?php echo $_POST['saldo_h']??''; ?>">
                    <input type="number" name="saldo_m" placeholder="MM" value="<?php echo $_POST['saldo_m']??'00'; ?>">
                </div>
            </div>
            <div>
                <label><?php echo t('lbl_start_date'); ?></label>
                <input type="date" name="start_date" required value="<?php echo $_POST['start_date']??date('Y-m-d'); ?>">
            </div>
        </div>

        <label style="margin-bottom:10px; display:block; border-bottom:1px solid #eee; padding-bottom:5px;">Week Schedule:</label>
        <?php 
        $days = ['Lunedì','Martedì','Mercoledì','Giovedì','Venerdì'];
        $opts = ['7:12'=>'7h 12m (Std)', '8:00'=>'8h 00m', '6:00'=>'6h 00m', '4:00'=>'4h 00m'];
        foreach($days as $k => $d): $idx = $k+1; ?>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; font-size:14px;">
                <span><?php echo $d; ?></span>
                <select name="day_<?php echo $idx; ?>" style="width:140px; padding:6px;">
                    <?php foreach($opts as $val => $lbl): ?>
                        <option value="<?php echo $val; ?>" <?php echo ($_POST["day_$idx"]??'7:12')==$val?'selected':''; ?>><?php echo $lbl; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn"><?php echo t('calc'); ?></button>

        <?php if($res && isset($res['type']) && $res['type']=='recuperi'): ?>
            <div class="result-box">
                <div style="font-size:13px; color:#047857; text-transform:uppercase"><?php echo t('res_recuperi_ok'); ?></div>
                <div class="res-main" style="font-size:28px"><?php echo $res['date']; ?></div>
                <div style="margin-top:10px; font-size:14px; color:#064e3b">
                    <?php echo t('res_recuperi_rem'); ?> <strong><?php echo $res['resto']; ?></strong>
                </div>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- TOOL: DEADLINE ---
function render_scadenza($res) {
    $mode = $_POST['calc_mode'] ?? 'end';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="scadenza">
        <div class="tool-title"><?php echo t('tool_scadenza'); ?></div>
        <div class="tool-desc"><?php echo t('desc_scadenza'); ?></div>
        
        <div class="tab-group">
            <input type="radio" name="calc_mode" id="m_end" value="end" class="tab-radio" <?php echo $mode=='end'?'checked':''; ?>>
            <label for="m_end" class="tab-label">Voglio sapere quando FINISCO</label>
            
            <input type="radio" name="calc_mode" id="m_start" value="start" class="tab-radio" <?php echo $mode=='start'?'checked':''; ?>>
            <label for="m_start" class="tab-label">Voglio sapere quando INIZIARE</label>
        </div>

        <div style="background:#f9fafb; padding:15px; border-radius:8px; margin-bottom:15px;">
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="flex:1; min-width:120px;">
                    <label><?php echo t('lbl_ref_time'); ?></label>
                    <div style="display:flex; gap:5px;">
                        <input type="number" name="start_h" placeholder="HH" value="<?php echo $_POST['start_h']??'09'; ?>"> :
                        <input type="number" name="start_m" placeholder="MM" value="<?php echo $_POST['start_m']??'00'; ?>">
                    </div>
                </div>
                <div style="flex:2; min-width:200px;">
                    <label>Data (Opzionale)</label>
                    <div style="display:flex; gap:5px;">
                        <input type="number" name="date_d" placeholder="GG" value="<?php echo $_POST['date_d']??''; ?>"> /
                        <input type="number" name="date_m" placeholder="MM" value="<?php echo $_POST['date_m']??''; ?>"> /
                        <input type="number" name="date_y" placeholder="AAAA" value="<?php echo $_POST['date_y']??date('Y'); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-bottom:15px;">
            <label>Durata Attività</label>
            <div style="display:flex; gap:10px; align-items:center;">
                <input type="number" name="dur_h" value="<?php echo $_POST['dur_h']??0; ?>"> h
                <input type="number" name="dur_m" value="<?php echo $_POST['dur_m']??0; ?>"> m
            </div>
        </div>
        
        <div style="margin-bottom:15px;">
            <label>Pausa Totale</label>
            <div style="display:flex; gap:10px; align-items:center;">
                <input type="number" name="pau_h" value="<?php echo $_POST['pau_h']??0; ?>"> h
                <input type="number" name="pau_m" value="<?php echo $_POST['pau_m']??0; ?>"> m
            </div>
        </div>

        <button type="submit" class="btn"><?php echo t('calc'); ?></button>
        
        <?php if($res && !isset($res['type'])): ?>
            <div class="result-box">
                <div class="res-main"><?php echo $res['main']; ?></div>
                <div class="res-sub"><?php echo $res['sub']; ?></div>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- TOOL: DATE DIFF ---
function render_dates($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="dates">
        <div class="tool-title"><?php echo t('tool_dates'); ?></div>
        <div class="tool-desc"><?php echo t('desc_dates'); ?></div>
        
        <div class="tab-group" style="background:none; padding:0; gap:20px;">
            <div style="flex:1">
                <label><?php echo t('lbl_start_date'); ?></label>
                <input type="date" name="d1" value="<?php echo $_POST['d1']??''; ?>" required>
            </div>
            <div style="flex:1">
                <label><?php echo t('lbl_end_date'); ?></label>
                <input type="date" name="d2" value="<?php echo $_POST['d2']??date('Y-m-d'); ?>" required>
            </div>
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

// --- TOOL: VAT ---
function render_iva($res) {
    $sel_op = $_POST['operazione'] ?? 'scorporo';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="iva">
        <div class="tool-title"><?php echo t('tool_iva'); ?></div>
        <div class="tool-desc"><?php echo t('desc_iva'); ?></div>
        
        <div style="margin-bottom:15px">
            <label><?php echo t('lbl_amount'); ?></label>
            <input type="text" name="importo" value="<?php echo $_POST['importo']??''; ?>" placeholder="es. 1220,00" required>
        </div>
        <div style="display:flex; gap:10px; margin-bottom:15px;">
            <div style="flex:1">
                <label><?php echo t('lbl_rate'); ?></label>
                <select name="aliquota">
                    <option value="22" <?php echo ($_POST['aliquota']??'')=='22'?'selected':''; ?>>22% (Ord.)</option>
                    <option value="10" <?php echo ($_POST['aliquota']??'')=='10'?'selected':''; ?>>10% (Rid.)</option>
                    <option value="4" <?php echo ($_POST['aliquota']??'')=='4'?'selected':''; ?>>4% (Min.)</option>
                </select>
            </div>
            <div style="flex:2">
                <label><?php echo t('lbl_op'); ?></label>
                <select name="operazione">
                    <option value="scorporo" <?php echo $sel_op=='scorporo'?'selected':''; ?>>Scorpora (Lordo -> Netto)</option>
                    <option value="add" <?php echo $sel_op=='add'?'selected':''; ?>>Applica (Netto -> Lordo)</option>
                    <option value="calc" <?php echo $sel_op=='calc'?'selected':''; ?>>Solo Calcolo IVA</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn"><?php echo t('calc'); ?></button>
        <?php if($res && isset($res['html'])) echo "<div class='result-box' style='padding:15px'>{$res['html']}</div>"; ?>
    </form>
    <?php 
}

// --- TOOL: IBAN ---
function render_iban($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="iban">
        <div class="tool-title"><?php echo t('tool_iban'); ?></div>
        <div class="tool-desc"><?php echo t('desc_iban'); ?></div>
        
        <label><?php echo t('lbl_iban_code'); ?></label>
        <input type="text" name="iban" placeholder="IT00X..." value="<?php echo $_POST['iban']??''; ?>" style="text-transform:uppercase" required>
        <button type="submit" class="btn" style="margin-top:15px"><?php echo t('verify'); ?></button>
        
        <?php if($res): ?>
            <div class="result-box" style="background:<?php echo $res['color']=='green'?'#ecfdf5':'#fef2f2'; ?>; border-color:<?php echo $res['color']=='green'?'#d1fae5':'#fecaca'; ?>">
                <strong style="color:<?php echo $res['color']; ?>"><?php echo $res['main']; ?></strong>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- TOOL: TEXT ---
function render_text($res) {
    $sel = $_POST['text_op'] ?? 'title';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="text">
        <div class="tool-title"><?php echo t('tool_text'); ?></div>
        <div class="tool-desc"><?php echo t('desc_text'); ?></div>
        
        <label><?php echo t('lbl_input_text'); ?></label>
        <textarea name="text_in" rows="5" style="font-family:monospace"><?php echo $_POST['text_in']??''; ?></textarea>
        
        <div style="margin:15px 0;">
            <select name="text_op">
                <option value="title" <?php echo $sel=='title'?'selected':''; ?>>Title Case (Mario Rossi)</option>
                <option value="oneline" <?php echo $sel=='oneline'?'selected':''; ?>>Remove Newlines</option>
                <option value="upper" <?php echo $sel=='upper'?'selected':''; ?>>UPPERCASE</option>
                <option value="lower" <?php echo $sel=='lower'?'selected':''; ?>>lowercase</option>
            </select>
        </div>
        <button type="submit" class="btn"><?php echo t('clean'); ?></button>

        <?php if($res): ?>
            <div style="margin-top:20px;">
                <label><?php echo t('lbl_result'); ?></label>
                <div class="res-raw" id="resTxt"><?php echo $res['raw']; ?></div>
                <button type="button" class="btn" style="margin-top:5px; background:#6b7280" onclick="copyText('resTxt')"><?php echo t('copy'); ?></button>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- TOOL: EMAIL ---
function render_email($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="email">
        <div class="tool-title"><?php echo t('tool_email'); ?></div>
        <div class="tool-desc"><?php echo t('desc_email'); ?></div>
        
        <label>Input List (Excel Column)</label>
        <textarea name="email_list" rows="6" placeholder="mario.rossi@unipv.it&#10;luigi.verdi@unipv.it"><?php echo $_POST['email_list']??''; ?></textarea>
        
        <label style="margin-top:15px"><?php echo t('lbl_separator'); ?></label>
        <select name="separator">
            <option value="semicolon">; (Outlook)</option>
            <option value="comma">, (Gmail)</option>
        </select>
        
        <button type="submit" class="btn" style="margin-top:15px"><?php echo t('format'); ?></button>

        <?php if($res): ?>
            <div style="margin-top:20px;">
                <label><?php echo t('lbl_result'); ?></label>
                <div class="res-raw" id="resEmail"><?php echo $res['raw']; ?></div>
                <button type="button" class="btn" style="margin-top:5px; background:#6b7280" onclick="copyText('resEmail')"><?php echo t('copy'); ?></button>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

// --- TOOL: PASSWORD ---
function render_pass($res) {
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="pass">
        <div class="tool-title"><?php echo t('tool_pass'); ?></div>
        <div class="tool-desc"><?php echo t('desc_pass'); ?></div>
        
        <div style="text-align:center; padding:20px;">
            <?php if(isset($res['raw'])): ?>
                <div style="font-size:32px; font-family:monospace; font-weight:bold; color:#4338ca; margin-bottom:20px; word-break:break-all;" id="passTxt">
                    <?php echo $res['raw']; ?>
                </div>
                <button type="button" class="btn" style="background:#059669; margin-bottom:15px;" onclick="copyText('passTxt')"><?php echo t('copy'); ?></button>
            <?php else: ?>
                <div style="color:#aaa; font-style:italic; margin-bottom:20px;">Press generate...</div>
            <?php endif; ?>
            
            <button type="submit" class="btn"><?php echo t('generate'); ?></button>
        </div>
    </form>
    <?php
}
?>