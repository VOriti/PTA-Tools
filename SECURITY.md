# Politica di Sicurezza

## Privacy e Gestione dei Dati

PTA-Tools è progettato con un approccio "Privacy First" per garantire la sicurezza dei dati amministrativi.

*   **Architettura Stateless**: L'applicazione non utilizza un database. Nessun dato utente (IBAN, email, testi, password) viene mai memorizzato sul disco del server o nel database, perché non esiste nessun database!
*   **Elaborazione Effimera**: Tutti i calcoli e l'elaborazione dei dati avvengono nella memoria di runtime di PHP per la durata della richiesta e vengono eliminati immediatamente dopo l'invio della risposta.
*   **Nessuna Chiamata Esterna**: L'applicazione non effettua chiamate ad API o server esterni per elaborare i tuoi dati. Tutto è contenuto all'interno della tua istanza server.
*   **Sicurezza della Sessione**: Il tool utilizza sessioni PHP standard e token CSRF per proteggere i moduli dalla falsificazione delle richieste tra siti (Cross-Site Request Forgery).
*   **Intestazioni di Sicurezza HTTP**: L'applicazione invia intestazioni di sicurezza (come `X-Frame-Options`, `X-Content-Type-Options`, etc.) per istruire il browser ad attivare protezioni aggiuntive contro attacchi comuni come il Clickjacking e il Cross-Site Scripting (XSS).

## Best Practice per il Deployment

Nonostante PTA-Tools è progettato nativamente in questo modo, se decidi di hostarlo sul tuo server devi seguire questi accorgimenti per garantire la massima sicurezza durante il deployment di PTA-Tools in un ambiente di produzione:

*   **HTTPS**: Servire sempre l'applicazione tramite HTTPS per crittografare i dati in transito.
*   **Controllo Accessi**: Se gli strumenti sono solo per uso interno, limitare l'accesso tramite configurazione del server (es. whitelist IP, Basic Auth o SSO/Shibboleth).
*   **Configurazione PHP**: Assicurarsi che `display_errors` sia impostato su `Off` in produzione (questo è gestito dall'impostazione `$CONFIG_GENERAL['debug_mode']` in `index.php`, ma la configurazione a livello di server è più sicura).
*   **Aggiornamenti**: Mantenere aggiornata la versione PHP del server.
*   **Gestione dei Log**: Configurare la rotazione e la cancellazione regolare dei log del server web (es. Apache/Nginx `access.log` e `error.log`). I log di accesso registrano metadati come IP e URL richiesti, mentre i log di errore potrebbero inavvertitamente catturare dati sensibili in caso di eccezioni o configurazioni di debug aggressive.

---

# Security Policy (English)

## Data Privacy & Handling

PTA-Tools is designed with a "Privacy First" approach to ensure the safety of administrative data.

*   **Stateless Architecture**: The application does not use a database. No user data (IBANs, emails, texts, passwords) is ever stored on the server's disk or database, because there is no database!
*   **Ephemeral Processing**: All calculations and data processing happen in the PHP runtime memory for the duration of the request and are discarded immediately after the response is sent.
*   **No External Calls**: The application does not make calls to external APIs or servers to process your data. Everything is contained within your server instance.
*   **Session Security**: The tool uses standard PHP sessions and CSRF tokens to protect forms against Cross-Site Request Forgery.
*   **HTTP Security Headers**: The application sends security headers (like `X-Frame-Options`, `X-Content-Type-Options`, etc.) to instruct the browser to enable additional protections against common attacks such as Clickjacking and Cross-Site Scripting (XSS).

## Best Practices for Deployment

Notwithstanding that PTA-Tools is designed natively this way, if you decide to host it on your server, you must follow these precautions to ensure maximum security when deploying PTA-Tools in a production environment:

*   **HTTPS**: Always serve the application over HTTPS to encrypt data in transit.
*   **Access Control**: If the tools are for internal use only, restrict access via server configuration (e.g., IP allowlist, Basic Auth, or SSO/Shibboleth).
*   **PHP Configuration**: Ensure `display_errors` is set to `Off` in production (this is handled by the `$CONFIG_GENERAL['debug_mode']` setting in `index.php`, but server-level config is safer).
*   **Updates**: Keep your server's PHP version updated.
*   **Log Management**: Configure rotation and regular deletion of web server logs (e.g., Apache/Nginx `access.log` and `error.log`). Access logs record metadata like IPs and requested URLs, while error logs could inadvertently capture sensitive data in case of exceptions or aggressive debug configurations.