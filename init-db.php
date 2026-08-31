<?php
/**
 * Zero-Config DB Init (CLI Script)
 * 
 * Dit script controleert of de benodigde SQLite databases en tabellen bestaan,
 * en maakt ze aan indien ze ontbreken.
 */

echo "🚀 Database Initialisatie Start...\n";

// Ensure storage maps exist
$storageDirs = [
    __DIR__ . '/storage',
    __DIR__ . '/storage/media',
    __DIR__ . '/storage/exports',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        echo "📁 Aanmaken map: " . basename($dir) . "\n";
        mkdir($dir, 0755, true);
    }
}

// 1. Leads Database (vanuit eerdere module)
try {
    echo "⚙️  Controleren leads database...\n";
    require_once __DIR__ . '/includes/db_helper.php';
    get_db_connection(); // Dit script regelt intern de tabellen al
    echo "✅ Leads database is actief en tabellen bestaan.\n";
} catch (Exception $e) {
    echo "❌ Fout bij instellen leads database: " . $e->getMessage() . "\n";
}

// 2. Content Database (Nieuwe setup)
try {
    echo "⚙️  Controleren content database...\n";
    $contentDbPath = __DIR__ . '/storage/content.sqlite';
    $pdoContent = new PDO('sqlite:' . $contentDbPath);
    $pdoContent->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Maak tabellen aan
    $queries = [
        "CREATE TABLE IF NOT EXISTS pages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            slug TEXT UNIQUE NOT NULL,
            template TEXT,
            status TEXT DEFAULT 'draft',
            seo_title TEXT,
            meta_description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS content_keys (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            key_name TEXT UNIQUE NOT NULL,
            description TEXT
        )",
        "CREATE TABLE IF NOT EXISTS content_translations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            key_id INTEGER NOT NULL,
            locale TEXT NOT NULL,
            value TEXT NOT NULL,
            version INTEGER DEFAULT 1,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (key_id) REFERENCES content_keys(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS content_variants (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            key_id INTEGER NOT NULL,
            variant_key TEXT NOT NULL,
            weight_percentage INTEGER DEFAULT 50,
            active INTEGER DEFAULT 1,
            FOREIGN KEY (key_id) REFERENCES content_keys(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS media_assets (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            asset_id TEXT UNIQUE NOT NULL,
            original_filename TEXT,
            title TEXT,
            alt_text TEXT,
            width INTEGER,
            height INTEGER,
            tags TEXT,
            variants_json TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL,
            role TEXT NOT NULL DEFAULT 'editor',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )"
    ];

    foreach ($queries as $query) {
        $pdoContent->exec($query);
    }

    // Seed default admin if users table is empty
    $count = $pdoContent->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($count == 0) {
        $defaultEmail = 'jappy@grutdesigners.nl';
        $defaultPass = password_hash('wachtwoord123', PASSWORD_BCRYPT);
        $stmt = $pdoContent->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)");
        $stmt->execute([$defaultEmail, $defaultPass, 'super_admin']);
        echo "✅ Default admin gebruiker ($defaultEmail) aangemaakt.\n";
    }

    echo "✅ Content database is actief en tabellen bestaan.\n";
} catch (Exception $e) {
    echo "❌ Fout bij instellen content database: " . $e->getMessage() . "\n";
}

echo "🎉 Initialisatie afgerond!\n";
