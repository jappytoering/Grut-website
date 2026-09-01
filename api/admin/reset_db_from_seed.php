<?php
require_once __DIR__ . '/../../includes/auth_helper.php';
AuthEngine::require_login();

$seed = __DIR__ . '/../../storage/content.sqlite.seed';
$db = __DIR__ . '/../../storage/content.sqlite';

if (file_exists($seed)) {
    if (copy($seed, $db)) {
        echo "<h1>Succes!</h1><p>De database op de testserver is succesvol gekopieerd vanuit de seed. Alle tabellen en lokale pagina's staan nu op de testserver.</p>";
    } else {
        echo "<h1>Fout!</h1><p>Kon de database niet overschrijven. Controleer de maprechten van /storage/.</p>";
    }
} else {
    echo "<h1>Fout!</h1><p>Seed bestand (content.sqlite.seed) niet gevonden op de server. Zorg dat je de code pullt.</p>";
}
