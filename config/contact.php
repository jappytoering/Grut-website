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
];
