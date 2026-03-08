# PTA-Tools - Dashboard di Utilità per la Pubblica Amministrazione

**Versione:** 2.5.0 | **Licenza:** [CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/) | **Lingue:** Italiano, Inglese | **Changelog:** [Vedi novità](CHANGELOG.md)

---

**[ITA - English below]**

---

### Perché un altro tool? Perché i tuoi dati sono solo tuoi.

Ogni giorno utilizziamo decine di piccoli tool online: "calcola IVA", "converti data", "formatta testo". Ma ti sei mai chiesto dove finiscono i dati che inserisci? Un IBAN, una lista di email, un importo... sono informazioni sensibili.

**PTA-Tools nasce per risolvere questo problema.** È una suite di utilità open-source che installi una sola volta sul tuo server (o su un hosting PHP a basso costo).

*   ✅ **Privacy Totale**: Nessun dato viene mai inviato a server esterni. Tutto viene elaborato e cancellato sul momento.
*   ✅ **Zero Dipendenze**: Non richiede database, librerie complesse o manutenzione. Solo PHP.
*   ✅ **Completamente Personalizzabile**: Adatta loghi, colori e, soprattutto, i link utili per il tuo ente in pochi minuti.
*   ✅ **Gratuito e Open Source**: Usa, modifica e adatta il codice secondo le tue necessità, nel rispetto della licenza.

Per informazioni dettagliate sulle pratiche di sicurezza, consulta il file dedicato: **➡️ [SECURITY.md](SECURITY.md)**

È lo strumento perfetto per un dipartimento, un ufficio o un intero ateneo che vuole fornire un set di utility sicure e brandizzate al proprio personale tecnico-amministrativo.

---

## 🛠️ Strumenti Inclusi

### Gestione Tempo
*   **Calcolo Ore Lavorate**: Somma intervalli di tempo (es. 8:00-12:30, 13:30-17:00) oppure somma un permesso non ancora conteggiato dall'applicativo delle timbrature per avere il vero totale delle ore lavorate.
*   **Convertitore Recuperi**: Converte un monte ore di straordinario in giorni di permesso, tenendo conto dell'orario settimanale, delle festività nazionali, Pasquetta e giorni del Santo Patrono.
*   **Orari Entrata e Uscita**: Calcola l'ora di fine o di inizio di un'attività.
*   **Differenza Date**: Calcola l'intervallo tra due date o aggiunge giorni a una data, con opzione per escludere weekend e festivi (inclusa Pasquetta calcolata automaticamente).

### Contabilità
*   **Gestione IVA**: Scorpora, applica o calcola l'IVA da un importo.
*   **Verifica IBAN**: Controlla la validità formale di un codice IBAN e ne mostra la scomposizione visiva (Paese, CIN, ABI, CAB, Conto) con link per la verifica dell'istituto bancario.

### Ufficio & Utilità
*   **Sanificatore Testo**: Strumento avanzato per la pulizia e la formattazione:
    *   **Filtro Privacy**: Oscura automaticamente dati sensibili (CF, IBAN, Email, Cellulari) per incollare testi in sicurezza.
    *   **Latinismi**: Riconosce ed evidenzia in corsivo le locuzioni latine comuni.
    *   **Formattazione**: Rimuove spazi doppi, righe vuote, gestisce gli "a capo" intelligenti (preservando i paragrafi) e corregge le maiuscole.
*   **Gestione Liste & Email**: Trasforma una colonna di email in una riga pronta per Outlook o Gmail, estrae indirizzi da un testo e altro.
*   **Generatore Password**: Crea password sicure ma facili da dettare.

## 💻 Requisiti di Sistema

*   **Server Web**: Apache, Nginx, IIS o qualsiasi server con supporto PHP.
*   **PHP**: Versione 7.4 o superiore.
*   **Estensioni PHP**: `mbstring` (per la gestione dei caratteri), `calendar` (per il calcolo delle festività mobili).
*   **Database**: Nessuno! L'applicazione è *stateless* e non richiede database.

---

## 🚀 Installazione in 30 secondi

Installare PTA-Tools è semplicissimo.

1.  **Scarica:** Vai alla [pagina delle Releases](https://github.com/VOriti/PTA-Tools/releases) e scarica l'ultima versione del file `index.php`.
2.  **Carica:** Carica il file `index.php` in una cartella del tuo server web abilitato per PHP.
3.  **Fatto!** Apri l'URL corrispondente nel tuo browser. L'applicazione è pronta.

---

## 🎨 Guida alla Personalizzazione

PTA-Tools è progettato per essere facilmente adattato da qualsiasi ente. Tutta la configurazione (colori, logo, link, testi) è centralizzata all'inizio del file `index.php`.

Per una guida dettagliata su come personalizzare ogni aspetto dell'applicazione, consulta il file:

**➡️ [GUIDA_PERSONALIZZAZIONE.md](GUIDA_PERSONALIZZAZIONE.md)**

---

## 📜 Licenza

Questo progetto è rilasciato sotto licenza **Creative Commons BY-NC-SA 4.0**.
Questo significa che sei libero di:
*   **Condividere**: copiare e ridistribuire il materiale in qualsiasi formato.
*   **Adattare**: modificare e costruire sul materiale.

Alle seguenti condizioni:
*   **Attribuzione (BY)**: Devi dare credito all'autore originale (Vincenzo Oriti) e fornire un link al repository originale. Se modifichi il codice, puoi impostare `$CONFIG_GENERAL['mostra_credits_originali'] = true;`.
*   **Non Commerciale (NC)**: Non puoi usare il materiale per scopi commerciali.
*   **Condividi allo stesso modo (SA)**: Se modifichi il materiale, devi distribuire i tuoi contributi sotto la stessa licenza.

---

## 🤝 Contributi

I contributi sono benvenuti! Se hai idee per nuovi tool, miglioramenti o bug fix, sentiti libero di aprire una "Issue" o una "Pull Request".

---

<br><br>

---

## PTA-Tools - Utility Dashboard for Public Administration

**Version:** 2.5.0 | **License:** [CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/) | **Languages:** Italian, English | **Changelog:** [See changes](CHANGELOG.md)

---

**[ENG VERSION]**

---

### Why another tool? Because your data is yours alone.

Every day we use dozens of small online tools: "calculate VAT", "convert date", "format text". But have you ever wondered where the data you enter ends up? An IBAN, a list of emails, an amount... these are sensitive pieces of information.

**PTA-Tools was created to solve this problem.** It is an open-source utility suite that you install once on your server (or on low-cost PHP hosting).

*   ✅ **Total Privacy**: No data is ever sent to external servers. Everything is processed and deleted instantly.
*   ✅ **Zero Dependencies**: Requires no database, complex libraries, or maintenance. Just PHP.
*   ✅ **Fully Customizable**: Adapt logos, colors, and, above all, useful links for your organization in minutes.
*   ✅ **Free and Open Source**: Use, modify, and adapt the code according to your needs, respecting the license.

For detailed information on security practices, consult the dedicated file: **➡️ [SECURITY.md](SECURITY.md)**

It is the perfect tool for a department, an office, or an entire university that wants to provide a set of secure and branded utilities to its technical-administrative staff.

---

## 🛠️ Included Tools

### Time Management
*   **Work Hours Calc**: Sums time intervals (e.g., 8:00-12:30, 13:30-17:00) or adds an interval of non-yet-counted overtime by the timekeeping system to get the true total of hours worked.
*   **Overtime Converter**: Converts an overtime balance into days off, accounting for weekly schedules, national holidays, Easter Monday, and Patron Saint days.
*   **Entry & Exit Times**: Calculates the end or start time of an activity.
*   **Date Difference**: Calculates the interval between two dates or adds days to a date, with options to exclude weekends and holidays (including automatically calculated Easter Monday).

### Accounting
*   **VAT Manager**: Unbundles, applies, or calculates VAT on an amount.
*   **IBAN Validator**: Checks the formal validity of an IBAN code and provides a visual breakdown (Country, Check, ABI, CAB, Account) with a link for bank verification.

### Office & Utilities
*   **Text Sanitizer**: Advanced tool for text cleaning and formatting:
    *   **Privacy Filter**: Automatically masks sensitive data (Tax ID, IBAN, Email, Mobile) for safe pasting.
    *   **Latinisms**: Detects and italicizes common Latin phrases.
    *   **Formatting**: Removes double spaces, blank rows, handles smart line breaks (preserving paragraphs), and fixes capitalization.
*   **List & Email Tools**: Turns a column of emails into a single row for Outlook or Gmail, extracts addresses from text, and more.
*   **Password Generator**: Creates secure but easy-to-dictate passwords.

## 💻 System Requirements

*   **Web Server**: Apache, Nginx, IIS, or any server supporting PHP.
*   **PHP**: Version 7.4 or higher.
*   **PHP Extensions**: `mbstring` (for string manipulation), `calendar` (for holiday calculation).
*   **Database**: None! The application is *stateless* and requires no database.

---

## 🚀 Installation in 30 seconds

Installing PTA-Tools is very simple.

1.  **Download:** Go to the Releases page and download the latest version of the `index.php` file.
2.  **Upload:** Upload the `index.php` file to a folder on your PHP-enabled web server.
3.  **Done!** Open the corresponding URL in your browser. The application is ready.

---

## 🎨 Customization Guide

PTA-Tools is designed to be easily adapted by any organization. All configuration (colors, logo, links, texts) is centralized at the top of the `index.php` file.

For a detailed guide on how to customize every aspect of the application, consult the file:

**➡️ GUIDA_PERSONALIZZAZIONE.md**

---

## 📜 License

This project is released under the **Creative Commons BY-NC-SA 4.0** license.
This means you are free to:
*   **Share**: copy and redistribute the material in any medium or format.
*   **Adapt**: remix, transform, and build upon the material.

Under the following terms:
*   **Attribution (BY)**: You must give appropriate credit to the original author (Vincenzo Oriti) and provide a link to the original repository. If you modify the code, you can set `$CONFIG_GENERAL['mostra_credits_originali'] = true;`.
*   **NonCommercial (NC)**: You may not use the material for commercial purposes.
*   **ShareAlike (SA)**: If you remix, transform, or build upon the material, you must distribute your contributions under the same license as the original.

---

## 🤝 Contributions

Contributions are welcome! If you have ideas for new tools, improvements, or bug fixes, feel free to open an "Issue" or a "Pull Request".

---
