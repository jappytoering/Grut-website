<?php
/**
 * Centrale Omgevingsconfiguratie
 */

return [
    // Opties: 'development', 'staging', 'production'
    'environment' => getenv('APP_ENV') ?: 'development',
    
    'mail' => [
        'from_address' => 'letsgo@grutdesigners.nl',
        'from_name' => 'Grut Designers'
    ]
];
