<?php
// ==========================================
// 1. CONFIGURAZIONE E LOGICA PHP (BACKEND)
// ==========================================
session_start();

// Gestione Lingua
$lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'it');
$_SESSION['lang'] = $lang;

// Dizionario Traduzioni
$trans = [
    'it' => [
        'app_name' => 'TimeTools',
        'wiz_title' => 'Cosa vuoi calcolare oggi?',
        'wiz_subtitle' => 'Seleziona uno strumento per iniziare',
        'wiz_opt1' => 'Somma Intervalli',
        'wiz_opt1_desc' => 'Calcola il tempo totale lavorato sommando vari orari di inizio e fine.',
        'wiz_opt2' => 'Scadenza e Durata',
        'wiz_opt2_desc' => 'Calcola a che ora finirà un\'attività basandosi sulla durata e le pause.',
        'back' => 'Torna alla Home',
        'calc' => 'Calcola Risultato',
        'add_row' => 'Aggiungi Riga',
        'opt_seconds' => 'Mostra Secondi',
        'from' => 'Dalle',
        'to' => 'Alle',
        'days' => 'gg',
        'hours' => 'h',
        'mins' => 'm',
        'secs' => 's',
        'result_total' => 'Tempo Totale:',
        'result_end' => 'Termine previsto:',
        'result_start' => 'Inizio necessario:',
        'at_time' => 'alle ore',
        'mode_end' => 'Voglio sapere quando <strong>Finisco</strong>',
        'mode_start' => 'Voglio sapere quando <strong>Iniziare</strong>',
        'label_start_time' => 'Orario di Inizio',
        'label_end_time' => 'Orario di Fine',
        'label_duration' => 'Durata Attività',
        'label_pause' => 'Pausa Totale',
        'date_label' => 'Data (opzionale)',
        'error' => 'Errore nel calcolo'
    ],
    'en' => [
        'app_name' => 'TimeTools',
        'wiz_title' => 'What do you want to calculate?',
        'wiz_subtitle' => 'Select a tool to get started',
        'wiz_opt1' => 'Elapsed Time',
        'wiz_opt1_desc' => 'Calculate total time by summing up multiple start/end intervals.',
        'wiz_opt2' => 'Deadline & Duration',
        'wiz_opt2_desc' => 'Calculate when a task will finish based on duration and breaks.',
        'back' => 'Back to Home',
        'calc' => 'Calculate Result',
        'add_row' => 'Add Row',
        'opt_seconds' => 'Show Seconds',
        'from' => 'From',
        'to' => 'To',
        'days' => 'd',
        'hours' => 'h',
        'mins' => 'm',
        'secs' => 's',
        'result_total' => 'Total Time:',
        'result_end' => 'Expected End:',
        'result_start' => 'Must Start:',
        'at_time' => 'at',
        'mode_end' => 'I want to know the <strong>End Time</strong>',
        'mode_start' => 'I want to know the <strong>Start Time</strong>',
        'label_start_time' => 'Start Time',
        'label_end_time' => 'End Time',
        'label_duration' => 'Duration',
        'label_pause' => 'Total Break',
        'date_label' => 'Date (optional)',
        'error' => 'Calculation Error'
    ]
];

function t($key) { global $trans, $lang; return $trans[$lang][$key] ?? $key; }
function getUrl($newLang = null) {
    global $lang;
    $l = $newLang ?: $lang;
    $t = $_GET['tool'] ?? '';
    return "?lang=$l" . ($t ? "&tool=$t" : "");
}

// --- LOGICA DI CALCOLO ---
$result_data = null; // Array per contenere titolo e valore del risultato

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // TOOL 1: INTERVALLI
    if (isset($_POST['action']) && $_POST['action'] == 'intervalli') {
        $tot_sec = 0;
        if (isset($_POST['h_start'])) {
            for ($i = 0; $i < count($_POST['h_start']); $i++) {
                $s_h = (int)$_POST['h_start'][$i]; $s_m = (int)$_POST['m_start'][$i]; $s_s = (int)($_POST['s_start'][$i]??0);
                $e_h = (int)$_POST['h_end'][$i];   $e_m = (int)$_POST['m_end'][$i];   $e_s = (int)($_POST['s_end'][$i]??0);

                $start = mktime($s_h, $s_m, $s_s, 1, 1, 2000);
                $end   = mktime($e_h, $e_m, $e_s, 1, 1, 2000);

                if ($end < $start) $end = mktime($e_h, $e_m, $e_s, 1, 2, 2000); // Giorno dopo
                $tot_sec += ($end - $start);
            }
        }
        $rh = floor($tot_sec / 3600);
        $rm = floor(($tot_sec / 60) % 60);
        $rs = $tot_sec % 60;
        
        $result_data = [
            'label' => t('result_total'),
            'value' => sprintf("%02d:%02d:%02d", $rh, $rm, $rs),
            'sub' => "$rh " . t('hours') . ", $rm " . t('mins')
        ];
    }

    // TOOL 2: SCADENZA
    if (isset($_POST['action']) && $_POST['action'] == 'scadenza') {
        try {
            $base = new DateTime();
            $base->setTime((int)$_POST['base_h'], (int)$_POST['base_m'], (int)($_POST['base_s']??0));
            if (!empty($_POST['base_d']) && !empty($_POST['base_mo']) && !empty($_POST['base_y'])) {
                $base->setDate((int)$_POST['base_y'], (int)$_POST['base_mo'], (int)$_POST['base_d']);
            }

            $di = "P" . (int)$_POST['d_d'] . "DT" . (int)$_POST['d_h'] . "H" . (int)$_POST['d_m'] . "M" . (int)($_POST['d_s']??0) . "S";
            $pi = "P" . (int)$_POST['p_d'] . "DT" . (int)$_POST['p_h'] . "H" . (int)$_POST['p_m'] . "M" . (int)($_POST['p_s']??0) . "S";
            
            $durata = new DateInterval($di);
            $pausa = new DateInterval($pi);
            
            if ($_POST['type'] == 'fine') {
                $base->add($durata); $base->add($pausa);
                $label = t('result_end');
            } else {
                $base->sub($durata); $base->sub($pausa);
                $label = t('result_start');
            }
            
            $result_data = [
                'label' => $label,
                'value' => $base->format('H:i:s'),
                'sub' => $base->format('d/m/Y')
            ];

        } catch (Exception $e) {
            $result_data = ['label' => 'Error', 'value' => 'Invalid Data', 'sub' => ''];
        }
    }
}

// Helper Select
function renderSelect($name, $max, $sel=0) {
    $h = "<select name='$name'>";
    for($i=0; $i<=$max; $i++) {
        $v = sprintf("%02d", $i);
        $s = ($i == $sel) ? 'selected' : '';
        $h .= "<option value='$v' $s>$v</option>";
    }
    $h .= "</select>";
    return $h;
}

$current_tool = isset($_GET['tool']) ? $_GET['tool'] : null;
$show_seconds = isset($_POST['use_seconds']) || (isset($_GET['s']) && $_GET['s']==1);
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Calculation App</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* =========================================
           2. MODERN CSS VARIABLES & RESET
           ========================================= */
        :root {
            --primary: #4F46E5; /* Indigo moderno */
            --primary-hover: #4338ca;
            --bg-body: #f3f4f6;
            --bg-card: #ffffff;
            --text-main: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding-bottom: 40px;
        }

        * { box-sizing: border-box; }

        /* =========================================
           3. HEADER & LOGO
           ========================================= */
        .app-header {
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 0 20px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .logo-container {
            /* Dimensioni richieste: circa 100x40mm proporzionali */
            width: 250px; 
            height: 60px;
            display: flex;
            align-items: center;
        }

        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain; /* Mantiene le proporzioni */
            object-position: left;
        }
        
        /* Placeholder logo style se non c'è immagine */
        .logo-placeholder {
            font-weight: 800;
            font-size: 24px;
            color: var(--primary);
            letter-spacing: -1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .lang-switch {
            display: flex;
            gap: 10px;
        }

        .lang-btn {
            text-decoration: none;
            font-size: 20px;
            padding: 5px 8px;
            border-radius: 6px;
            background: #f9fafb;
            border: 1px solid var(--border);
            transition: 0.2s;
        }
        .lang-btn.active {
            background: #e0e7ff;
            border-color: var(--primary);
        }

        /* =========================================
           4. LAYOUT & CARDS
           ========================================= */
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 20px;
            border: 1px solid var(--border);
        }

        .section-title {
            margin-top: 0;
            font-size: 24px;
            color: var(--text-main);
            margin-bottom: 5px;
        }
        
        .section-subtitle {
            color: var(--text-light);
            margin-bottom: 30px;
            display: block;
        }

        /* =========================================
           5. WIZARD (HOME)
           ========================================= */
        .wizard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .wizard-card {
            background: var(--bg-card);
            border: 2px solid transparent; /* default */
            border-radius: var(--radius);
            padding: 40px 30px;
            text-decoration: none;
            color: var(--text-main);
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .wizard-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .wiz-icon { font-size: 48px; margin-bottom: 20px; }
        .wiz-h { font-size: 20px; font-weight: 700; margin-bottom: 10px; color: var(--text-main); }
        .wiz-desc { color: var(--text-light); line-height: 1.5; font-size: 15px; }

        /* =========================================
           6. FORMS & INPUTS
           ========================================= */
        .top-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 15px;
        }

        .back-btn {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.2s;
        }
        .back-btn:hover { color: var(--primary); }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            gap: 10px;
        }
        .toggle-input { display: none; }
        .toggle-rail {
            width: 44px; height: 24px;
            background-color: #e5e7eb;
            border-radius: 20px;
            position: relative;
            transition: 0.3s;
        }
        .toggle-knob {
            width: 18px; height: 18px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 3px; left: 3px;
            transition: 0.3s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .toggle-input:checked + .toggle-rail { background-color: var(--primary); }
        .toggle-input:checked + .toggle-rail .toggle-knob { transform: translateX(20px); }

        /* Rows */
        .input-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid transparent;
            transition: 0.2s;
        }
        .input-row:focus-within {
            border-color: #c7d2fe;
            background: #fff;
        }

        .label-text { font-size: 13px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; }
        
        select, input[type="number"], input[type="text"] {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-family: inherit;
            font-size: 15px;
            background-color: white;
            outline: none;
            transition: border-color 0.2s;
        }
        select:focus, input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        select { appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='gray' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 8px center; background-size: 14px; padding-right: 30px; }
        
        .time-group { display: flex; align-items: center; gap: 5px; }
        .separator { color: #9ca3af; font-weight: bold; }

        /* Radio Group Custom */
        .radio-group { display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap; }
        .radio-card {
            flex: 1;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .radio-card:hover { background: #f9fafb; }
        .radio-card.selected { border-color: var(--primary); background: #eef2ff; color: var(--primary); }
        .radio-card input { margin: 0; }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: inline-block;
            width: 100%;
        }
        .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-1px); }
        
        .btn-text {
            background: none; border: none; color: var(--primary);
            font-weight: 600; cursor: pointer; padding: 10px;
            display: block; margin: 10px auto; font-size: 14px;
        }
        .btn-text:hover { text-decoration: underline; }

        /* Result */
        .result-panel {
            margin-top: 30px;
            padding: 25px;
            background: #ecfdf5; /* Verde molto chiaro */
            border: 1px solid #d1fae5;
            border-radius: var(--radius);
            text-align: center;
        }
        .result-label { font-size: 14px; color: #065f46; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; }
        .result-value { font-size: 42px; font-weight: 800; color: #047857; margin: 10px 0; }
        .result-sub { color: #064e3b; font-size: 16px; opacity: 0.8; }

        /* Utility visibility */
        .sec-hidden { display: none !important; }

        @media(max-width: 600px) {
            .app-header { padding: 0 15px; height: 60px; }
            .logo-placeholder span { display: none; } /* Nasconde testo logo su mobile */
            .input-row { flex-direction: column; align-items: flex-start; }
            .time-group { width: 100%; justify-content: space-between; }
            select { flex: 1; }
        }
    </style>
</head>
<body>

    <header class="app-header">
        <div class="logo-container">
            <div class="logo-placeholder">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>TimeTools</span>
            </div>
        </div>
        
        <div class="lang-switch">
            <a href="<?php echo getUrl('it'); ?>" class="lang-btn <?php echo $lang=='it'?'active':''; ?>">🇮🇹</a>
            <a href="<?php echo getUrl('en'); ?>" class="lang-btn <?php echo $lang=='en'?'active':''; ?>">🇬🇧</a>
        </div>
    </header>

    <main class="container">

        <?php if (!$current_tool): ?>
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 class="section-title"><?php echo t('wiz_title'); ?></h1>
                <span class="section-subtitle"><?php echo t('wiz_subtitle'); ?></span>
            </div>
            
            <div class="wizard-grid">
                <a href="<?php echo getUrl(null).'&tool=intervalli'; ?>" class="wizard-card">
                    <div class="wiz-icon">⏱️</div>
                    <div class="wiz-h"><?php echo t('wiz_opt1'); ?></div>
                    <div class="wiz-desc"><?php echo t('wiz_opt1_desc'); ?></div>
                </a>
                <a href="<?php echo getUrl(null).'&tool=scadenza'; ?>" class="wizard-card">
                    <div class="wiz-icon">📅</div>
                    <div class="wiz-h"><?php echo t('wiz_opt2'); ?></div>
                    <div class="wiz-desc"><?php echo t('wiz_opt2_desc'); ?></div>
                </a>
            </div>
        <?php endif; ?>


        <?php if ($current_tool == 'intervalli'): ?>
            <form method="POST" class="card" id="formIntervalli" action="<?php echo getUrl(); ?>">
                <input type="hidden" name="action" value="intervalli">
                
                <div class="top-toolbar">
                    <a href="<?php echo getUrl(null).'&tool='; ?>" class="back-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        <?php echo t('back'); ?>
                    </a>
                    <h2 style="margin:0; font-size:18px;"><?php echo t('wiz_opt1'); ?></h2>
                    
                    <label class="toggle-switch">
                        <input type="checkbox" name="use_seconds" class="toggle-input" id="secToggle" value="1" 
                            <?php echo $show_seconds ? 'checked' : ''; ?> onchange="toggleSeconds()">
                        <div class="toggle-rail"><div class="toggle-knob"></div></div>
                        <span>Sec</span>
                    </label>
                </div>

                <div id="rows-wrapper">
                    <?php 
                    $count = isset($_POST['h_start']) ? count($_POST['h_start']) : 1;
                    for($i=0; $i<$count; $i++): 
                    ?>
                    <div class="input-row">
                        <span class="label-text" style="width: 50px;"><?php echo t('from'); ?></span>
                        <div class="time-group">
                            <?php echo renderSelect('h_start[]', 23, $_POST['h_start'][$i] ?? 8); ?>
                            <span class="separator">:</span>
                            <?php echo renderSelect('m_start[]', 59, $_POST['m_start'][$i] ?? 0); ?>
                            <span class="sec-group <?php echo !$show_seconds?'sec-hidden':''; ?>">
                                <span class="separator">:</span>
                                <?php echo renderSelect('s_start[]', 59, $_POST['s_start'][$i] ?? 0); ?>
                            </span>
                        </div>

                        <span class="label-text" style="width: 50px; margin-left: auto; text-align:right; margin-right:10px;"><?php echo t('to'); ?></span>
                        <div class="time-group">
                            <?php echo renderSelect('h_end[]', 23, $_POST['h_end'][$i] ?? 12); ?>
                            <span class="separator">:</span>
                            <?php echo renderSelect('m_end[]', 59, $_POST['m_end'][$i] ?? 0); ?>
                            <span class="sec-group <?php echo !$show_seconds?'sec-hidden':''; ?>">
                                <span class="separator">:</span>
                                <?php echo renderSelect('s_end[]', 59, $_POST['s_end'][$i] ?? 0); ?>
                            </span>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <button type="button" class="btn-text" onclick="addRow()">+ <?php echo t('add_row'); ?></button>
                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-primary"><?php echo t('calc'); ?></button>
                </div>
            </form>
        <?php endif; ?>


        <?php if ($current_tool == 'scadenza'): ?>
            <form method="POST" class="card" action="<?php echo getUrl(); ?>">
                <input type="hidden" name="action" value="scadenza">

                <div class="top-toolbar">
                    <a href="<?php echo getUrl(null).'&tool='; ?>" class="back-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        <?php echo t('back'); ?>
                    </a>
                    <h2 style="margin:0; font-size:18px;"><?php echo t('wiz_opt2'); ?></h2>
                    
                    <label class="toggle-switch">
                        <input type="checkbox" name="use_seconds" class="toggle-input" id="secToggle" value="1" 
                            <?php echo $show_seconds ? 'checked' : ''; ?> onchange="toggleSeconds()">
                        <div class="toggle-rail"><div class="toggle-knob"></div></div>
                        <span>Sec</span>
                    </label>
                </div>

                <div class="radio-group">
                    <label class="radio-card <?php echo (!isset($_POST['type'])||$_POST['type']=='fine')?'selected':''; ?>" onclick="selectRadio(this)">
                        <input type="radio" name="type" value="fine" checked hidden>
                        <span><?php echo t('mode_end'); ?></span>
                    </label>
                    <label class="radio-card <?php echo (isset($_POST['type'])&&$_POST['type']=='inizio')?'selected':''; ?>" onclick="selectRadio(this)">
                        <input type="radio" name="type" value="inizio" hidden>
                        <span><?php echo t('mode_start'); ?></span>
                    </label>
                </div>

                <div class="input-row">
                    <span class="label-text" style="min-width: 120px;" id="label-base"><?php echo t('label_start_time'); ?></span>
                    <div class="time-group">
                        <?php echo renderSelect('base_h', 23, $_POST['base_h']??9); ?> <span class="separator">:</span>
                        <?php echo renderSelect('base_m', 59, $_POST['base_m']??0); ?>
                        <span class="sec-group <?php echo !$show_seconds?'sec-hidden':''; ?>">
                            <span class="separator">:</span>
                            <?php echo renderSelect('base_s', 59, $_POST['base_s']??0); ?>
                        </span>
                    </div>
                    
                    <div class="time-group" style="margin-left:auto; border-left: 1px solid #ddd; padding-left: 15px;">
                        <span class="label-text" style="margin-right: 5px;"><?php echo t('date_label'); ?></span>
                        <input type="number" name="base_d" placeholder="DD" style="width:45px" value="<?php echo $_POST['base_d']??''; ?>">
                        <input type="number" name="base_mo" placeholder="MM" style="width:45px" value="<?php echo $_POST['base_mo']??''; ?>">
                        <input type="number" name="base_y" placeholder="YYYY" style="width:60px" value="<?php echo $_POST['base_y']??date('Y'); ?>">
                    </div>
                </div>

                <div class="input-row">
                    <span class="label-text" style="min-width: 120px;"><?php echo t('label_duration'); ?></span>
                    <div class="time-group" style="gap: 10px;">
                        <div><input type="number" name="d_h" value="<?php echo $_POST['d_h']??0; ?>" style="width:50px"> <?php echo t('hours'); ?></div>
                        <div><input type="number" name="d_m" value="<?php echo $_POST['d_m']??0; ?>" style="width:50px"> <?php echo t('mins'); ?></div>
                        <div class="sec-group <?php echo !$show_seconds?'sec-hidden':''; ?>"><input type="number" name="d_s" value="<?php echo $_POST['d_s']??0; ?>" style="width:50px"> <?php echo t('secs'); ?></div>
                    </div>
                </div>

                <div class="input-row">
                    <span class="label-text" style="min-width: 120px;"><?php echo t('label_pause'); ?></span>
                    <div class="time-group" style="gap: 10px;">
                        <div><input type="number" name="p_h" value="<?php echo $_POST['p_h']??0; ?>" style="width:50px"> <?php echo t('hours'); ?></div>
                        <div><input type="number" name="p_m" value="<?php echo $_POST['p_m']??0; ?>" style="width:50px"> <?php echo t('mins'); ?></div>
                        <div class="sec-group <?php echo !$show_seconds?'sec-hidden':''; ?>"><input type="number" name="p_s" value="<?php echo $_POST['p_s']??0; ?>" style="width:50px"> <?php echo t('secs'); ?></div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-primary"><?php echo t('calc'); ?></button>
                </div>
            </form>
        <?php endif; ?>


        <?php if ($result_data): ?>
            <div class="result-panel">
                <div class="result-label"><?php echo $result_data['label']; ?></div>
                <div class="result-value"><?php echo $result_data['value']; ?></div>
                <div class="result-sub"><?php echo $result_data['sub']; ?></div>
            </div>
        <?php endif; ?>

    </main>

<script>
    function toggleSeconds() {
        const checked = document.getElementById('secToggle').checked;
        const els = document.querySelectorAll('.sec-group');
        els.forEach(el => {
            if(checked) el.classList.remove('sec-hidden');
            else el.classList.add('sec-hidden');
        });
    }

    function addRow() {
        const wrap = document.getElementById('rows-wrapper');
        const first = wrap.querySelector('.input-row');
        const clone = first.cloneNode(true);
        
        // Reset values
        clone.querySelectorAll('select').forEach(s => s.value = '00');
        // Default business hours hint
        const selects = clone.querySelectorAll('select');
        if(selects.length > 0) selects[0].value = '09'; // start h
        if(selects.length > 3) selects[3].value = '13'; // end h
        
        wrap.appendChild(clone);
    }

    function selectRadio(card) {
        // Visual selection logic
        document.querySelectorAll('.radio-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        card.querySelector('input').checked = true;

        // Label logic
        const val = card.querySelector('input').value;
        const lbl = document.getElementById('label-base');
        if(val === 'fine') lbl.innerText = "<?php echo t('label_start_time'); ?>";
        else lbl.innerText = "<?php echo t('label_end_time'); ?>";
    }
</script>

</body>
</html>