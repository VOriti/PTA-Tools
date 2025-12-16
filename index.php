<?php
// --- CONFIGURAZIONE E TRADUZIONI ---
session_start();

// Gestione Lingua
$lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'it');
$_SESSION['lang'] = $lang;

// Dizionario Traduzioni
$trans = [
    'it' => [
        'title_main' => 'Strumenti Calcolo Tempo',
        'wiz_title' => 'Cosa vuoi calcolare?',
        'wiz_opt1' => 'Tempo Trascorso',
        'wiz_opt1_desc' => 'Somma vari intervalli di tempo (Differenza Ore)',
        'wiz_opt2' => 'Scadenza / Inizio',
        'wiz_opt2_desc' => 'Calcola data finale o iniziale basata su una durata',
        'back' => 'Torna al Menu',
        'calc' => 'Calcola',
        'add_row' => '+ Nuovo Intervallo',
        'opt_seconds' => 'Abilita Secondi',
        'from' => 'dalle',
        'to' => 'alle',
        'days' => 'giorni',
        'hours' => 'ore',
        'mins' => 'min',
        'secs' => 'sec',
        'result_total' => 'Tempo Totale Trascorso',
        'result_end' => 'L\'attività termina il',
        'result_start' => 'L\'attività deve iniziare il',
        'at_time' => 'alle ore',
        'mode_end' => 'Calcola l\'ora di fine',
        'mode_start' => 'Calcola l\'ora di inizio',
        'label_start_time' => 'L\'attività inizia alle',
        'label_end_time' => 'L\'attività finisce alle',
        'label_duration' => 'Ha una durata di',
        'label_pause' => 'Con una pausa di',
        'select_calc_type' => 'Seleziona cosa vuoi calcolare:',
        'of_date' => 'del',
        'optional' => '(opzionale)'
    ],
    'en' => [
        'title_main' => 'Time Calculation Tools',
        'wiz_title' => 'What do you want to calculate?',
        'wiz_opt1' => 'Elapsed Time',
        'wiz_opt1_desc' => 'Sum multiple time intervals (Time Difference)',
        'wiz_opt2' => 'Deadline / Start',
        'wiz_opt2_desc' => 'Calculate end date or start date based on duration',
        'back' => 'Back to Menu',
        'calc' => 'Calculate',
        'add_row' => '+ New Interval',
        'opt_seconds' => 'Enable Seconds',
        'from' => 'from',
        'to' => 'to',
        'days' => 'days',
        'hours' => 'hours',
        'mins' => 'mins',
        'secs' => 'secs',
        'result_total' => 'Total Elapsed Time',
        'result_end' => 'Activity ends on',
        'result_start' => 'Activity must start on',
        'at_time' => 'at',
        'mode_end' => 'Calculate End Time',
        'mode_start' => 'Calculate Start Time',
        'label_start_time' => 'Activity starts at',
        'label_end_time' => 'Activity ends at',
        'label_duration' => 'Duration is',
        'label_pause' => 'With a break of',
        'select_calc_type' => 'Select calculation type:',
        'of_date' => 'on',
        'optional' => '(optional)'
    ]
];

// Funzione helper per tradurre
function t($key) {
    global $trans, $lang;
    return isset($trans[$lang][$key]) ? $trans[$lang][$key] : $key;
}

// Helper per mantenere i parametri URL (tool) quando si cambia lingua
function getUrl($newLang = null, $newTool = null) {
    global $lang;
    $l = $newLang ? $newLang : $lang;
    $t = $newTool ? $newTool : (isset($_GET['tool']) ? $_GET['tool'] : '');
    return "?lang=$l" . ($t ? "&tool=$t" : "");
}

// --- LOGICA DI CALCOLO PHP ---
$result_html = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // TOOL 1: INTERVALLI
    if (isset($_POST['action']) && $_POST['action'] == 'intervalli') {
        $tot_sec = 0;
        if (isset($_POST['h_start'])) {
            for ($i = 0; $i < count($_POST['h_start']); $i++) {
                $s_h = (int)$_POST['h_start'][$i];
                $s_m = (int)$_POST['m_start'][$i];
                $s_s = isset($_POST['s_start'][$i]) ? (int)$_POST['s_start'][$i] : 0;
                
                $e_h = (int)$_POST['h_end'][$i];
                $e_m = (int)$_POST['m_end'][$i];
                $e_s = isset($_POST['s_end'][$i]) ? (int)$_POST['s_end'][$i] : 0;

                // Creiamo timestamp fittizi per oggi
                $start = mktime($s_h, $s_m, $s_s, 1, 1, 2000);
                $end   = mktime($e_h, $e_m, $e_s, 1, 1, 2000);

                // Se fine < inizio, aggiungi 24 ore (giorno dopo)
                if ($end < $start) {
                    $end = mktime($e_h, $e_m, $e_s, 1, 2, 2000);
                }
                $tot_sec += ($end - $start);
            }
        }
        
        $rh = floor($tot_sec / 3600);
        $rm = floor(($tot_sec / 60) % 60);
        $rs = $tot_sec % 60;
        $result_string = sprintf("%02d:%02d:%02d", $rh, $rm, $rs);
        $result_html = "<strong>" . t('result_total') . ":</strong> <span style='font-size:1.2em; color:#d32f2f;'>$result_string</span>";
    }

    // TOOL 2: SCADENZA
    if (isset($_POST['action']) && $_POST['action'] == 'scadenza') {
        $base = new DateTime();
        // Imposta ora base
        $base->setTime((int)$_POST['base_h'], (int)$_POST['base_m'], (int)($_POST['base_s'] ?? 0));
        // Imposta data base se presente
        if (!empty($_POST['base_d']) && !empty($_POST['base_mo']) && !empty($_POST['base_y'])) {
            $base->setDate((int)$_POST['base_y'], (int)$_POST['base_mo'], (int)$_POST['base_d']);
        }

        // Durata
        $di = "P" . (int)$_POST['d_d'] . "DT" . (int)$_POST['d_h'] . "H" . (int)$_POST['d_m'] . "M" . (int)($_POST['d_s']??0) . "S";
        // Pausa
        $pi = "P" . (int)$_POST['p_d'] . "DT" . (int)$_POST['p_h'] . "H" . (int)$_POST['p_m'] . "M" . (int)($_POST['p_s']??0) . "S";

        try {
            $durata = new DateInterval($di);
            $pausa = new DateInterval($pi);
            
            if ($_POST['type'] == 'fine') {
                $base->add($durata);
                $base->add($pausa);
                $label = t('result_end');
            } else {
                $base->sub($durata);
                $base->sub($pausa);
                $label = t('result_start');
            }
            
            $result_html = "$label <strong>" . $base->format('d/m/Y') . "</strong> " . t('at_time') . " <strong>" . $base->format('H:i:s') . "</strong>";

        } catch (Exception $e) {
            $result_html = "Error in date calculation.";
        }
    }
}

// Helper per generare le select
function renderSelect($name, $max, $sel=0, $cls='') {
    $h = "<select name='$name' class='$cls'>";
    for($i=0; $i<=$max; $i++) {
        $v = sprintf("%02d", $i);
        $s = ($i == $sel) ? 'selected' : '';
        $h .= "<option value='$v' $s>$v</option>";
    }
    $h .= "</select>";
    return $h;
}

$current_tool = isset($_GET['tool']) ? $_GET['tool'] : null;
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('title_main'); ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; color: #333; }
        .top-bar { display: flex; justify-content: flex-end; margin-bottom: 20px; align-items: center; }
        .lang-btn { text-decoration: none; font-size: 24px; margin-left: 10px; opacity: 0.6; transition: 0.3s; border: 1px solid transparent; padding: 2px 5px; border-radius: 4px; }
        .lang-btn:hover, .lang-btn.active { opacity: 1; background: #fff; border-color: #ccc; }
        
        .container { max-width: 800px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: #f9f9c5; padding: 20px; text-align: center; border-bottom: 1px solid #e0e0a0; }
        .header h1 { margin: 0; font-size: 22px; color: #555; text-transform: uppercase; letter-spacing: 1px; }
        
        .content { padding: 30px; }

        /* WIZARD STYLES */
        .wizard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .wizard-card { background: #fffbe6; border: 2px solid #e0e0a0; padding: 30px; text-align: center; cursor: pointer; border-radius: 8px; transition: 0.2s; text-decoration: none; color: #333; }
        .wizard-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); background: #fff8cc; }
        .wizard-card h3 { margin-top: 0; color: #d35400; }
        
        /* FORM STYLES */
        .tool-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .back-link { text-decoration: none; color: #666; font-size: 14px; display: flex; align-items: center; }
        .back-link:hover { color: #000; }

        .row { display: flex; align-items: center; flex-wrap: wrap; background: #fcfcfc; border: 1px solid #eee; padding: 10px; margin-bottom: 10px; border-radius: 4px; }
        .row-label { margin: 0 10px; font-weight: bold; color: #555; }
        
        select, input[type=number] { padding: 6px; border: 1px solid #ccc; border-radius: 4px; margin: 0 2px; }
        input[type=number] { width: 50px; }
        
        .btn { background: linear-gradient(to bottom, #b8e1fc, #a9d2f3); border: 1px solid #89accc; padding: 10px 25px; border-radius: 4px; cursor: pointer; font-weight: bold; color: #333; font-size: 16px; transition: 0.2s; }
        .btn:hover { background: #9bcbf0; }
        
        .btn-add { background: none; border: none; color: #27ae60; cursor: pointer; font-size: 15px; font-weight: bold; padding: 10px; display: block; margin: 0 auto; }
        .btn-add:hover { color: #2ecc71; text-decoration: underline; }

        .result-box { background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; padding: 20px; margin-top: 20px; border-radius: 4px; text-align: center; font-size: 18px; }

        .options-bar { background: #f9f9f9; padding: 10px; border-radius: 4px; margin-bottom: 15px; display: flex; align-items: center; justify-content: flex-end; font-size: 14px; }
        .options-bar label { cursor: pointer; display: flex; align-items: center; gap: 5px; }

        /* SECONDS TOGGLE LOGIC */
        .sec-group { display: none; } /* Hidden by default in CSS */
        .show-seconds .sec-group { display: inline-block; } /* Visible if parent has class */
        
        /* Mobile responsive */
        @media (max-width: 600px) { .wizard-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body class="<?php echo isset($_POST['use_seconds']) ? 'show-seconds' : ''; ?>">

    <div class="top-bar">
        <a href="<?php echo getUrl('it'); ?>" class="lang-btn <?php echo $lang=='it'?'active':''; ?>" title="Italiano">🇮🇹</a>
        <a href="<?php echo getUrl('en'); ?>" class="lang-btn <?php echo $lang=='en'?'active':''; ?>" title="English">🇬🇧</a>
    </div>

    <div class="container">
        
        <div class="header">
            <h1><?php echo t('title_main'); ?></h1>
        </div>

        <div class="content">

            <?php if (!$current_tool): ?>
                <h2 style="text-align: center; margin-bottom: 30px;"><?php echo t('wiz_title'); ?></h2>
                <div class="wizard-grid">
                    <a href="<?php echo getUrl(null, 'intervalli'); ?>" class="wizard-card">
                        <h3>⏱ <?php echo t('wiz_opt1'); ?></h3>
                        <p><?php echo t('wiz_opt1_desc'); ?></p>
                    </a>
                    <a href="<?php echo getUrl(null, 'scadenza'); ?>" class="wizard-card">
                        <h3>📅 <?php echo t('wiz_opt2'); ?></h3>
                        <p><?php echo t('wiz_opt2_desc'); ?></p>
                    </a>
                </div>
            <?php endif; ?>


            <?php if ($current_tool == 'intervalli'): ?>
                <div class="tool-header">
                    <h2><?php echo t('wiz_opt1'); ?></h2>
                    <a href="<?php echo getUrl(null, ''); ?>" class="back-link">⬅ <?php echo t('back'); ?></a>
                </div>

                <form method="POST" action="<?php echo getUrl(); ?>" id="form1">
                    <input type="hidden" name="action" value="intervalli">
                    
                    <div class="options-bar">
                        <label>
                            <input type="checkbox" name="use_seconds" id="toggleSec" value="1" 
                                <?php echo isset($_POST['use_seconds']) ? 'checked' : ''; ?>
                                onchange="document.body.classList.toggle('show-seconds')">
                            <?php echo t('opt_seconds'); ?>
                        </label>
                    </div>

                    <div id="rows-container">
                        <?php 
                        $count = isset($_POST['h_start']) ? count($_POST['h_start']) : 1;
                        for($i=0; $i<$count; $i++): 
                        ?>
                        <div class="row">
                            <span class="row-label"><?php echo t('from'); ?></span>
                            <?php echo renderSelect('h_start[]', 23, $_POST['h_start'][$i] ?? 8); ?> :
                            <?php echo renderSelect('m_start[]', 59, $_POST['m_start'][$i] ?? 0); ?>
                            <span class="sec-group"> : <?php echo renderSelect('s_start[]', 59, $_POST['s_start'][$i] ?? 0); ?></span>

                            <span class="row-label"><?php echo t('to'); ?></span>
                            <?php echo renderSelect('h_end[]', 23, $_POST['h_end'][$i] ?? 12); ?> :
                            <?php echo renderSelect('m_end[]', 59, $_POST['m_end'][$i] ?? 0); ?>
                            <span class="sec-group"> : <?php echo renderSelect('s_end[]', 59, $_POST['s_end'][$i] ?? 0); ?></span>
                        </div>
                        <?php endfor; ?>
                    </div>

                    <button type="button" class="btn-add" onclick="addRow()"><?php echo t('add_row'); ?></button>

                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn"><?php echo t('calc'); ?></button>
                    </div>
                </form>
            <?php endif; ?>


            <?php if ($current_tool == 'scadenza'): ?>
                <div class="tool-header">
                    <h2><?php echo t('wiz_opt2'); ?></h2>
                    <a href="<?php echo getUrl(null, ''); ?>" class="back-link">⬅ <?php echo t('back'); ?></a>
                </div>

                <form method="POST" action="<?php echo getUrl(); ?>">
                    <input type="hidden" name="action" value="scadenza">
                    
                    <div class="options-bar">
                        <label>
                            <input type="checkbox" name="use_seconds" id="toggleSec" value="1" 
                                <?php echo isset($_POST['use_seconds']) ? 'checked' : ''; ?>
                                onchange="document.body.classList.toggle('show-seconds')">
                            <?php echo t('opt_seconds'); ?>
                        </label>
                    </div>

                    <p><strong><?php echo t('select_calc_type'); ?></strong></p>
                    <div class="row" style="justify-content: flex-start; gap: 20px;">
                        <label>
                            <input type="radio" name="type" value="fine" <?php echo (!isset($_POST['type']) || $_POST['type']=='fine')?'checked':''; ?> onclick="updateLabels('fine')"> 
                            <?php echo t('mode_end'); ?>
                        </label>
                        <label>
                            <input type="radio" name="type" value="inizio" <?php echo (isset($_POST['type']) && $_POST['type']=='inizio')?'checked':''; ?> onclick="updateLabels('inizio')"> 
                            <?php echo t('mode_start'); ?>
                        </label>
                    </div>

                    <p id="label-base"><strong><?php echo t('label_start_time'); ?>:</strong></p>
                    <div class="row">
                        H: <?php echo renderSelect('base_h', 23, $_POST['base_h']??9); ?>
                        m: <?php echo renderSelect('base_m', 59, $_POST['base_m']??0); ?>
                        <span class="sec-group"> s: <?php echo renderSelect('base_s', 59, $_POST['base_s']??0); ?></span>
                        &nbsp;&nbsp; <strong><?php echo t('of_date'); ?>:</strong> &nbsp;
                        <input type="number" name="base_d" placeholder="DD" min="1" max="31" value="<?php echo $_POST['base_d']??''; ?>"> /
                        <input type="number" name="base_mo" placeholder="MM" min="1" max="12" value="<?php echo $_POST['base_mo']??''; ?>"> /
                        <input type="number" name="base_y" placeholder="YYYY" min="2000" max="2100" value="<?php echo $_POST['base_y']??date('Y'); ?>">
                        <small style="color:#888; margin-left:5px;"><?php echo t('optional'); ?></small>
                    </div>

                    <p><strong><?php echo t('label_duration'); ?>:</strong></p>
                    <div class="row">
                        <input type="number" name="d_d" value="<?php echo $_POST['d_d']??0; ?>"> <?php echo t('days'); ?>, 
                        <input type="number" name="d_h" value="<?php echo $_POST['d_h']??0; ?>"> <?php echo t('hours'); ?>, 
                        <input type="number" name="d_m" value="<?php echo $_POST['d_m']??0; ?>"> <?php echo t('mins'); ?>
                        <span class="sec-group">, <input type="number" name="d_s" value="<?php echo $_POST['d_s']??0; ?>"> <?php echo t('secs'); ?></span>
                    </div>

                    <p><strong><?php echo t('label_pause'); ?>:</strong></p>
                    <div class="row">
                        <input type="number" name="p_d" value="<?php echo $_POST['p_d']??0; ?>"> <?php echo t('days'); ?>, 
                        <input type="number" name="p_h" value="<?php echo $_POST['p_h']??0; ?>"> <?php echo t('hours'); ?>, 
                        <input type="number" name="p_m" value="<?php echo $_POST['p_m']??0; ?>"> <?php echo t('mins'); ?>
                        <span class="sec-group">, <input type="number" name="p_s" value="<?php echo $_POST['p_s']??0; ?>"> <?php echo t('secs'); ?></span>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn"><?php echo t('calc'); ?></button>
                    </div>
                </form>
            <?php endif; ?>


            <?php if ($result_html): ?>
                <div class="result-box">
                    <?php echo $result_html; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

<script>
    // Inizializza visibilità secondi al caricamento
    (function(){
        var chk = document.getElementById('toggleSec');
        if(chk && chk.checked) {
            document.body.classList.add('show-seconds');
        }
    })();

    function updateLabels(mode) {
        var lbl = document.getElementById('label-base');
        if(mode === 'fine') {
            lbl.innerText = "<?php echo t('label_start_time'); ?>:"; // Se calcolo fine, chiedo inizio
        } else {
            lbl.innerText = "<?php echo t('label_end_time'); ?>:"; // Se calcolo inizio, chiedo fine
        }
    }

    function addRow() {
        var container = document.getElementById('rows-container');
        if(!container) return;
        
        // Clona la prima riga
        var firstRow = container.querySelector('.row');
        var newRow = firstRow.cloneNode(true);
        
        // Resetta i valori della nuova riga
        var selects = newRow.querySelectorAll('select');
        selects.forEach(function(sel) { sel.value = "00"; });
        
        // Imposta valori default per ore (opzionale, es. 8:00 - 12:00)
        selects[0].value = "08"; // Start H
        selects[3].value = "12"; // End H

        container.appendChild(newRow);
    }
</script>

</body>
</html>