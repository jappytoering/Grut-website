<?php
/**
 * Grut Website Deploy Script
 * Dit script zorgt ervoor dat de (test)server na een git pull netjes in orde wordt gemaakt.
 * 
 * Run via: php bin/deploy.php
 */

echo "🚀 Start Grut Deploy Script...\n\n";

$storageDir = __DIR__ . '/../storage';
$dbPath = $storageDir . '/content.sqlite';
$leadsPath = $storageDir . '/leads.sqlite';

// 1. Rechten fixen
echo "[1/4] Mappen controleren en rechten zetten...\n";
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0775, true);
    echo "  -> Map 'storage' aangemaakt.\n";
} else {
    chmod($storageDir, 0775);
    echo "  -> Rechten op 'storage' gezet.\n";
}

// 2. Database initialisatie
echo "[2/4] Databases initialiseren (via init-db.php)...\n";
$initDbScript = __DIR__ . '/../init-db.php';
if (file_exists($initDbScript)) {
    // Run init-db.php via PHP CLI, of include
    // We include it so it runs in this context
    ob_start();
    require $initDbScript;
    $initOutput = ob_get_clean();
    echo "  -> Databases gecontroleerd/aangemaakt.\n";
} else {
    echo "  -> ⚠️ init-db.php niet gevonden.\n";
}

// 3. Content Seeding
echo "[3/4] Content Seeding (via seed_prototype_content.php)...\n";
$seedScript = __DIR__ . '/seed_prototype_content.php';
if (file_exists($seedScript)) {
    ob_start();
    require $seedScript;
    $seedOutput = ob_get_clean();
    echo "  -> Actuele vertalingen/content ge-update in SQLite.\n";
} else {
    echo "  -> ⚠️ seed_prototype_content.php niet gevonden.\n";
}

// 4. Cache legen
echo "[4/4] Caches opschonen...\n";
$contentHelper = __DIR__ . '/../includes/content_helper.php';
if (file_exists($contentHelper)) {
    require_once $contentHelper;
    if (class_exists('ContentEngine')) {
        ContentEngine::clear_cache();
        echo "  -> Content cache geleegd (APCu & JSON).\n";
    }
} else {
    echo "  -> ⚠️ content_helper.php niet gevonden.\n";
}

echo "\n✅ Deploy afgerond! De omgeving is up-to-date en draait op maximale snelheid.\n";
