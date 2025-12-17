<?php
// ==========================================
// 1. CONFIGURAZIONE E CATALOGO STRUMENTI
// ==========================================
session_start();

// Gestione Lingua
$lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'it');
$_SESSION['lang'] = $lang;

// --- DIZIONARIO TRADUZIONI ---
$trans = [
    'it' => [
        'app_name' => 'TimeTools',
        'home_title' => 'Dashboard',
        'select_tool' => 'Seleziona uno strumento dal menu laterale',
        'cat_time' => 'Gestione Tempo',
        'back' => 'Home',
        'calc' => 'Calcola',
        'result' => 'Risultato',
        'add_row' => '+ Aggiungi Riga',
        'opt_seconds' => 'Mostra Secondi',
        
        // Etichette Generiche
        'from' => 'DA',
        'to' => 'A',
        'days' => 'gg',
        'hours' => 'h',
        'mins' => 'm',
        'secs' => 's',
        
        // Tool Intervalli
        'tool_intervalli' => 'Somma Intervalli',
        'desc_intervalli' => 'Somma ore di inizio e fine multiple.',
        
        // Tool Scadenza
        'tool_scadenza' => 'Scadenza e Durata',
        'desc_scadenza' => 'Calcola fine o inizio basandosi sulla durata.',
        'calc_end' => 'Calcola Fine',
        'calc_start' => 'Calcola Inizio',
        'base_time' => 'Orario Base',
        'date_label' => 'Data',
        'duration_label' => 'Durata',
        'pause_label' => 'Pausa',
        'result_end_label' => 'Termina il:',
        'result_start_label' => 'Inizia il:'
    ],
    'en' => [
        'app_name' => 'TimeTools',
        'home_title' => 'Dashboard',
        'select_tool' => 'Select a tool from the sidebar',
        'cat_time' => 'Time Management',
        'back' => 'Home',
        'calc' => 'Calculate',
        'result' => 'Result',
        'add_row' => '+ Add Row',
        'opt_seconds' => 'Show Seconds',

        // Generic Labels
        'from' => 'FROM',
        'to' => 'TO',
        'days' => 'd',
        'hours' => 'h',
        'mins' => 'm',
        'secs' => 's',

        // Tool Intervals
        'tool_intervalli' => 'Elapsed Time',
        'desc_intervalli' => 'Sum multiple start/end intervals.',

        // Tool Deadline
        'tool_scadenza' => 'Deadline & Duration',
        'desc_scadenza' => 'Calculate end or start based on duration.',
        'calc_end' => 'Calculate End Time',
        'calc_start' => 'Calculate Start Time',
        'base_time' => 'Base Time',
        'date_label' => 'Date',
        'duration_label' => 'Duration',
        'pause_label' => 'Pause / Break',
        'result_end_label' => 'Ends on:',
        'result_start_label' => 'Starts on:'
    ]
];

function t($key) { global $trans, $lang; return $trans[$lang][$key] ?? $key; }
function getUrl($tool = null) {
    global $lang; 
    $t = $tool ? "&tool=$tool" : "";
    return "?lang=$lang$t"; 
}

// --- CATALOGO STRUMENTI ---
$CATALOG = [
    'time' => [
        'label_key' => 'cat_time',
        'icon' => '⏱️',
        'items' => [
            'intervalli' => [
                'title_key' => 'tool_intervalli',
                'desc_key' => 'desc_intervalli',
                'func' => 'render_tool_intervalli'
            ],
            'scadenza' => [
                'title_key' => 'tool_scadenza',
                'desc_key' => 'desc_scadenza',
                'func' => 'render_tool_scadenza'
            ]
        ]
    ]
];

// Recupera tool corrente
$current_tool_id = isset($_GET['tool']) ? $_GET['tool'] : null;
$current_tool_info = null;

foreach($CATALOG as $cat) {
    if(isset($cat['items'][$current_tool_id])) {
        $current_tool_info = $cat['items'][$current_tool_id];
        break;
    }
}

// --- LOGICA DI CALCOLO ---
$result_data = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Processore Intervalli
    if ($_POST['action'] == 'intervalli') {
        $tot_sec = 0;
        if (isset($_POST['h_start'])) {
            for ($i = 0; $i < count($_POST['h_start']); $i++) {
                $s_h = (int)$_POST['h_start'][$i]; $s_m = (int)$_POST['m_start'][$i]; $s_s = (int)($_POST['s_start'][$i]??0);
                $e_h = (int)$_POST['h_end'][$i];   $e_m = (int)$_POST['m_end'][$i];   $e_s = (int)($_POST['s_end'][$i]??0);
                $start = mktime($s_h, $s_m, $s_s, 1, 1, 2000);
                $end   = mktime($e_h, $e_m, $e_s, 1, 1, 2000);
                if ($end < $start) $end = mktime($e_h, $e_m, $e_s, 1, 2, 2000);
                $tot_sec += ($end - $start);
            }
        }
        $rh = floor($tot_sec / 3600); $rm = floor(($tot_sec / 60) % 60); $rs = $tot_sec % 60;
        $result_data = ['main' => sprintf("%02d:%02d:%02d", $rh, $rm, $rs), 'sub' => "$rh h, $rm m"];
    }

    // Processore Scadenza
    if ($_POST['action'] == 'scadenza') {
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
                $lbl = t('result_end_label');
            } else {
                $base->sub($durata); $base->sub($pausa);
                $lbl = t('result_start_label');
            }
            $result_data = ['label' => $lbl, 'main' => $base->format('H:i:s'), 'sub' => $base->format('d/m/Y')];
        } catch (Exception $e) { $result_data = ['main' => 'Errore Dati']; }
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
        :root {
            --primary: #4F46E5;
            --primary-light: #EEF2FF;
            --sidebar-bg: #FFFFFF;
            --sidebar-width: 260px;
            --bg-body: #F3F4F6;
            --border: #E5E7EB;
            --text-main: #111827;
            --text-sub: #6B7280;
        }
        
        body { font-family: 'Inter', sans-serif; background: var(--bg-body); margin: 0; display: flex; height: 100vh; overflow: hidden; color: var(--text-main); }
        * { box-sizing: border-box; }

        /* SIDEBAR STYLES */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            overflow-y: auto;
        }

        .logo-area {
            height: 70px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border);
        }
        .logo-placeholder { font-weight: 800; font-size: 20px; color: var(--primary); display: flex; align-items: center; gap: 10px; }

        .nav-group { padding: 20px 15px 0 15px; }
        .nav-cat-title { font-size: 11px; font-weight: 700; color: var(--text-sub); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; padding-left: 10px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            text-decoration: none;
            color: var(--text-main);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
            transition: 0.2s;
        }
        .nav-item:hover { background: var(--primary-light); color: var(--primary); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }

        /* MAIN CONTENT */
        .main-wrapper { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        
        .top-header {
            height: 70px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
        }
        .lang-switch a { text-decoration: none; font-size: 20px; margin-left: 10px; opacity: 0.6; }
        .lang-switch a.active { opacity: 1; }

        .content-scroll { flex: 1; overflow-y: auto; padding: 30px; }
        .container { max-width: 800px; margin: 0 auto; }

        /* CARDS & FORMS */
        .card { background: white; border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 30px; border: 1px solid var(--border); }
        
        .wiz-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .wiz-card { background: white; border: 1px solid var(--border); padding: 25px; border-radius: 12px; text-decoration: none; color: inherit; transition: 0.2s; text-align: center; }
        .wiz-card:hover { border-color: var(--primary); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }

        /* UI ELEMENTS */
        .btn { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px; width: 100%; transition: 0.2s; }
        .btn:hover { background: #4338ca; }
        
        .input-row { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; padding: 12px; background: #FAFAFA; border-radius: 8px; margin-bottom: 10px; }
        
        select, input { padding: 8px 10px; border: 1px solid #D1D5DB; border-radius: 6px; font-family: inherit; font-size: 15px; outline: none; }
        select:focus, input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
        
        .input-wide { width: 75px; text-align: center; } 
        
        .toggle-switch { display: flex; align-items: center; cursor: pointer; gap: 10px; font-size: 14px; font-weight: 500; }
        .toggle-switch input { display: none; }
        .slider { width: 40px; height: 22px; background: #ccc; border-radius: 20px; position: relative; transition: .3s; }
        .slider:before { content: ""; position: absolute; height: 18px; width: 18px; left: 2px; top: 2px; background: white; border-radius: 50%; transition: .3s; }
        input:checked + .slider { background: var(--primary); }
        input:checked + .slider:before { transform: translateX(18px); }

        .result-box { margin-top: 25px; background: #ECFDF5; border: 1px solid #D1FAE5; padding: 20px; border-radius: 10px; text-align: center; color: #065F46; }
        .result-main { font-size: 32px; font-weight: 800; margin: 5px 0; }

        .sec-hidden { display: none !important; }

        @media(max-width: 768px) {
            body { flex-direction: column; height: auto; overflow: auto; }
            .sidebar { width: 100%; height: auto; border-right: none; border-bottom: 1px solid var(--border); }
            .main-wrapper { height: auto; overflow: visible; }
            .content-scroll { padding: 20px; }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-area">
            <div class="logo-placeholder">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                TimeTools
            </div>
        </div>

        <nav>
            <div class="nav-group">
                <div class="nav-cat-title">Menu</div>
                <a href="<?php echo getUrl(); ?>" class="nav-item <?php echo !$current_tool_id ? 'active' : ''; ?>">
                    🏠 <?php echo t('home_title'); ?>
                </a>
            </div>

            <?php foreach($CATALOG as $cat_key => $cat): ?>
            <div class="nav-group">
                <div class="nav-cat-title"><?php echo t($cat['label_key']) . ' ' . $cat['icon']; ?></div>
                <?php foreach($cat['items'] as $item_key => $item): ?>
                    <a href="<?php echo getUrl($item_key); ?>" class="nav-item <?php echo $current_tool_id == $item_key ? 'active' : ''; ?>">
                        <?php echo t($item['title_key']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="top-header">
            <h2 style="margin:0; font-size:18px;">
                <?php echo $current_tool_info ? t($current_tool_info['title_key']) : t('home_title'); ?>
            </h2>
            <div class="lang-switch">
                <a href="?lang=it&tool=<?php echo $current_tool_id; ?>" class="<?php echo $lang=='it'?'active':''; ?>">🇮🇹</a>
                <a href="?lang=en&tool=<?php echo $current_tool_id; ?>" class="<?php echo $lang=='en'?'active':''; ?>">🇬🇧</a>
            </div>
        </header>

        <main class="content-scroll">
            <div class="container">
                
                <?php if (!$current_tool_id): ?>
                    <div style="text-align:center; margin-bottom:40px;">
                        <h3><?php echo t('select_tool'); ?></h3>
                    </div>
                    <div class="wiz-grid">
                        <?php foreach($CATALOG as $cat): foreach($cat['items'] as $k => $i): ?>
                            <a href="<?php echo getUrl($k); ?>" class="wiz-card">
                                <h3><?php echo t($i['title_key']); ?></h3>
                                <p style="font-size:14px; color:#666;"><?php echo t($i['desc_key']); ?></p>
                            </a>
                        <?php endforeach; endforeach; ?>
                    </div>
                
                <?php elseif ($current_tool_info): ?>
                    <?php call_user_func($current_tool_info['func'], $result_data); ?>
                <?php endif; ?>

            </div>
        </main>
    </div>

<script>
    function toggleSec() {
        var chk = document.getElementById('secCheck').checked;
        document.querySelectorAll('.sec-group').forEach(el => {
            el.classList.toggle('sec-hidden', !chk);
        });
    }
    window.onload = function() { if(document.getElementById('secCheck')) toggleSec(); };
</script>
</body>
</html>

<?php
// ==========================================
// FUNZIONI DI RENDER (AGGIORNATE E TRADOTTE)
// ==========================================

function render_tool_intervalli($res) {
    global $lang;
    $chk = isset($_POST['use_seconds']) ? 'checked' : '';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="intervalli">
        
        <div style="display:flex; justify-content:flex-end; margin-bottom:20px;">
            <label class="toggle-switch">
                <input type="checkbox" id="secCheck" name="use_seconds" value="1" <?php echo $chk; ?> onchange="toggleSec()">
                <div class="slider"></div>
                <span><?php echo t('opt_seconds'); ?></span>
            </label>
        </div>

        <div id="rows-container">
            <?php 
            $count = isset($_POST['h_start']) ? count($_POST['h_start']) : 1;
            for($i=0; $i<$count; $i++): 
            ?>
            <div class="input-row">
                <span style="font-weight:600; font-size:13px; color:#666; width:40px;"><?php echo t('from'); ?>:</span>
                <?php echo renderSelect("h_start[]", 23, $_POST['h_start'][$i]??8); ?> :
                <?php echo renderSelect("m_start[]", 59, $_POST['m_start'][$i]??0); ?>
                <span class="sec-group"> : <?php echo renderSelect("s_start[]", 59, $_POST['s_start'][$i]??0); ?></span>

                <span style="font-weight:600; font-size:13px; color:#666; width:40px; margin-left:auto; text-align:right;"><?php echo t('to'); ?>:</span>
                <?php echo renderSelect("h_end[]", 23, $_POST['h_end'][$i]??12); ?> :
                <?php echo renderSelect("m_end[]", 59, $_POST['m_end'][$i]??0); ?>
                <span class="sec-group"> : <?php echo renderSelect("s_end[]", 59, $_POST['s_end'][$i]??0); ?></span>
            </div>
            <?php endfor; ?>
        </div>

        <button type="button" class="btn" style="background:#eef2ff; color:#4F46E5; margin-bottom:15px;" onclick="addRow()">+ <?php echo t('add_row'); ?></button>
        <button type="submit" class="btn"><?php echo t('calc'); ?></button>

        <?php if($res): ?>
            <div class="result-box">
                <div style="font-size:14px; text-transform:uppercase; letter-spacing:1px;"><?php echo t('result'); ?></div>
                <div class="result-main"><?php echo $res['main']; ?></div>
                <div style="opacity:0.8"><?php echo $res['sub']; ?></div>
            </div>
        <?php endif; ?>
    </form>
    <script>
    function addRow() {
        var div = document.createElement('div');
        div.className = 'input-row';
        div.innerHTML = document.querySelector('.input-row').innerHTML;
        div.querySelectorAll('select').forEach(s => s.value = '00');
        document.getElementById('rows-container').appendChild(div);
        toggleSec(); 
    }
    </script>
    <?php
}

function render_tool_scadenza($res) {
    global $lang;
    $chk = isset($_POST['use_seconds']) ? 'checked' : '';
    ?>
    <form method="POST" class="card">
        <input type="hidden" name="action" value="scadenza">
        
        <div style="display:flex; justify-content:flex-end; margin-bottom:20px;">
            <label class="toggle-switch">
                <input type="checkbox" id="secCheck" name="use_seconds" value="1" <?php echo $chk; ?> onchange="toggleSec()">
                <div class="slider"></div>
                <span><?php echo t('opt_seconds'); ?></span>
            </label>
        </div>

        <div class="input-row" style="justify-content:center; gap:20px;">
            <label><input type="radio" name="type" value="fine" checked> <?php echo t('calc_end'); ?></label>
            <label><input type="radio" name="type" value="inizio" <?php echo (isset($_POST['type'])&&$_POST['type']=='inizio')?'checked':''; ?>> <?php echo t('calc_start'); ?></label>
        </div>

        <div style="margin-top:20px;">
            <label style="display:block; font-weight:600; margin-bottom:5px;"><?php echo t('base_time'); ?></label>
            <div class="input-row">
                <?php echo t('hours'); ?>: <?php echo renderSelect("base_h", 23, $_POST['base_h']??9); ?>
                <?php echo t('mins'); ?>: <?php echo renderSelect("base_m", 59, $_POST['base_m']??0); ?>
                <span class="sec-group"><?php echo t('secs'); ?>: <?php echo renderSelect("base_s", 59, $_POST['base_s']??0); ?></span>
                
                <span style="margin:0 10px; color:#ccc;">|</span>
                <?php echo t('date_label'); ?>: 
                <input type="number" name="base_d" placeholder="GG" style="width:50px" value="<?php echo $_POST['base_d']??''; ?>">
                <input type="number" name="base_mo" placeholder="MM" style="width:50px" value="<?php echo $_POST['base_mo']??''; ?>">
                <input type="number" name="base_y" placeholder="AAAA" style="width:60px" value="<?php echo $_POST['base_y']??date('Y'); ?>">
            </div>
        </div>

        <div style="margin-top:20px;">
            <label style="display:block; font-weight:600; margin-bottom:5px;"><?php echo t('duration_label'); ?></label>
            <div class="input-row">
                <input type="number" name="d_d" class="input-wide" value="<?php echo $_POST['d_d']??0; ?>"> <?php echo t('days'); ?>
                <input type="number" name="d_h" class="input-wide" value="<?php echo $_POST['d_h']??0; ?>"> <?php echo t('hours'); ?>
                <input type="number" name="d_m" class="input-wide" value="<?php echo $_POST['d_m']??0; ?>"> <?php echo t('mins'); ?>
                <span class="sec-group"><input type="number" name="d_s" class="input-wide" value="<?php echo $_POST['d_s']??0; ?>"> <?php echo t('secs'); ?></span>
            </div>
        </div>

        <div style="margin-top:20px;">
            <label style="display:block; font-weight:600; margin-bottom:5px;"><?php echo t('pause_label'); ?></label>
            <div class="input-row">
                <input type="number" name="p_d" class="input-wide" value="<?php echo $_POST['p_d']??0; ?>"> <?php echo t('days'); ?>
                <input type="number" name="p_h" class="input-wide" value="<?php echo $_POST['p_h']??0; ?>"> <?php echo t('hours'); ?>
                <input type="number" name="p_m" class="input-wide" value="<?php echo $_POST['p_m']??0; ?>"> <?php echo t('mins'); ?>
                <span class="sec-group"><input type="number" name="p_s" class="input-wide" value="<?php echo $_POST['p_s']??0; ?>"> <?php echo t('secs'); ?></span>
            </div>
        </div>

        <button type="submit" class="btn" style="margin-top:20px;"><?php echo t('calc'); ?></button>

        <?php if($res): ?>
            <div class="result-box">
                <div style="font-size:14px; text-transform:uppercase;"><?php echo $res['label'] ?? t('result'); ?></div>
                <div class="result-main"><?php echo $res['main']; ?></div>
                <div style="opacity:0.8"><?php echo $res['sub']; ?></div>
            </div>
        <?php endif; ?>
    </form>
    <?php
}

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
?>