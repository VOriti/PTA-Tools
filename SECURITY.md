# Politica di Sicurezza

## Privacy e Gestione dei Dati

PTA-Tools è progettato con un approccio "Privacy First" per garantire la sicurezza dei dati amministrativi.

*   **Architettura Stateless**: L'applicazione non utilizza un database. Nessun dato utente (IBAN, email, testi, password) viene mai memorizzato sul disco del server o nel database, perché non esiste nessun database!
*   **Elaborazione Effimera**: Tutti i calcoli e l'elaborazione dei dati avvengono nella memoria di runtime di PHP per la durata della richiesta e vengono eliminati immediatamente dopo l'invio della risposta.
*   **Nessuna Chiamata Esterna**: L'applicazione non effettua chiamate ad API o server esterni per elaborare i tuoi dati. Tutto è contenuto all'interno della tua istanza server.
*   **Sicurezza della Sessione**: Il tool utilizza sessioni PHP standard e token CSRF per proteggere i moduli dalla falsificazione delle richieste tra siti (Cross-Site Request Forgery).

## Best Practice per il Deployment

Per garantire la massima sicurezza durante il deployment di PTA-Tools in un ambiente di produzione:

*   **HTTPS**: Servire sempre l'applicazione tramite HTTPS per crittografare i dati in transito.
*   **Controllo Accessi**: Se gli strumenti sono solo per uso interno, limitare l'accesso tramite configurazione del server (es. whitelist IP, Basic Auth o SSO/Shibboleth).
*   **Configurazione PHP**: Assicurarsi che `display_errors` sia impostato su `Off` in produzione (questo è gestito dall'impostazione `$CONFIG_GENERAL['debug_mode']` in `index.php`, ma la configurazione a livello di server è più sicura).
*   **Aggiornamenti**: Mantenere aggiornata la versione PHP del server.

---

# Security Policy (English)

## Data Privacy & Handling

PTA-Tools is designed with a "Privacy First" approach to ensure the safety of administrative data.

*   **Stateless Architecture**: The application does not use a database. No user data (IBANs, emails, texts, passwords) is ever stored on the server's disk or database, because there is no database!
*   **Ephemeral Processing**: All calculations and data processing happen in the PHP runtime memory for the duration of the request and are discarded immediately after the response is sent.
*   **No External Calls**: The application does not make calls to external APIs or servers to process your data. Everything is contained within your server instance.
*   **Session Security**: The tool uses standard PHP sessions and CSRF tokens to protect forms against Cross-Site Request Forgery.

## Best Practices for Deployment

To ensure maximum security when deploying PTA-Tools in a production environment:

*   **HTTPS**: Always serve the application over HTTPS to encrypt data in transit.
*   **Access Control**: If the tools are for internal use only, restrict access via server configuration (e.g., IP allowlist, Basic Auth, or SSO/Shibboleth).
*   **PHP Configuration**: Ensure `display_errors` is set to `Off` in production (this is handled by the `$CONFIG_GENERAL['debug_mode']` setting in `index.php`, but server-level config is safer).
*   **Updates**: Keep your server's PHP version updated.