<?php
/**
 * Centrale Configuratie voor Modulaire Contact- & Lead-Intake Module
 */

return [
    // Het vaste e-mailadres waar alle leads naartoe worden gestuurd
    'receiver_email' => 'Letsgo@grutdesigners.nl',

    // Pad naar de lokale SQLite database (vanuit de root van de applicatie)
    'db_path' => __DIR__ . '/../storage/leads.sqlite',

    // Debug-modus (zet op true tijdens ontwikkeling, false in productie)
    'debug' => true,

    // Afzender adres voor transactionele e-mails
    'sender_email' => 'noreply@grutdesigners.nl',
    
    // Testmodus voor e-mails (true = e-mails worden lokaal opgeslagen als HTML ipv verstuurd)
    // Automatisch op true als server op localhost of .test draait.
    'mail_sandbox' => isset($_SERVER['HTTP_HOST']) && (
        strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
        strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
        strpos($_SERVER['HTTP_HOST'], '.test') !== false
    ),
];
