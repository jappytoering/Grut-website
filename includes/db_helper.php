<?php
/**
 * Database Helper voor Contact & Lead-Intake Module
 */

function get_db_connection() {
    $configPath = __DIR__ . '/../config/contact.php';
    if (!file_exists($configPath)) {
        throw new Exception("Configuratiebestand niet gevonden.");
    }

    $config = require $configPath;
    $dbPath = $config['db_path'];

    // Ensure the storage directory exists and is writable
    $storageDir = dirname($dbPath);
    if (!is_dir($storageDir)) {
        if (!mkdir($storageDir, 0755, true)) {
            throw new Exception("Kan de storage map niet aanmaken.");
        }
    }

    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        // Zet de error mode op exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Performance Optimalisaties voor SQLite
        $pdo->exec('PRAGMA journal_mode = WAL;');
        $pdo->exec('PRAGMA synchronous = NORMAL;');
        $pdo->exec('PRAGMA foreign_keys = ON;');
        
        // Initialiseer tabellen indien ze nog niet bestaan
        init_db_tables($pdo);

        return $pdo;
    } catch (PDOException $e) {
        if ($config['debug']) {
            throw new Exception("Database connectiefout: " . $e->getMessage());
        } else {
            throw new Exception("Fout bij het verbinden met de database.");
        }
    }
}

function init_db_tables($pdo) {
    // Tabel 'submissions' aanmaken volgens de acceptatiecriteria
    $query = "
        CREATE TABLE IF NOT EXISTS submissions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            preset_id TEXT NOT NULL,
            source_url TEXT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT,
            company TEXT,
            service_type TEXT,
            budget TEXT,
            message TEXT,
            payload_json TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ";
    
    $pdo->exec($query);
}
