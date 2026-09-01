<?php
/**
 * Database Helper voor Contact & Lead-Intake Module
 */

class DBHelper {
    private static $pdo = null;
    private static $cmsPdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
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
                self::$pdo = new PDO('sqlite:' . $dbPath);
                // Zet de error mode op exception
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Performance Optimalisaties voor SQLite
                self::$pdo->exec('PRAGMA journal_mode = WAL;');
                self::$pdo->exec('PRAGMA synchronous = NORMAL;');
                self::$pdo->exec('PRAGMA foreign_keys = ON;');
                
                // Index toevoegen voor snellere lookups op preset_id
                self::init_db_tables(self::$pdo);
                
            } catch (PDOException $e) {
                if ($config['debug'] ?? false) {
                    throw new Exception("Database connectiefout: " . $e->getMessage());
                } else {
                    throw new Exception("Fout bij het verbinden met de database.");
                }
            }
        }
        return self::$pdo;
    }

    private static function init_db_tables($pdo) {
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
        
        // Index toevoegen voor performance
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_submissions_preset_id ON submissions(preset_id);");
    }
    private static function init_cms_tables($pdo) {
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_pages_slug ON pages(slug);");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_page_blocks_page_id_sort_order ON page_blocks(page_id, sort_order);");
        
        // Zorg dat nieuwe kolommen automatisch worden toegevoegd op de testserver
        $columns_to_add = [
            "template TEXT DEFAULT 'default'",
            "form_id INTEGER DEFAULT NULL",
            "seo_title TEXT",
            "meta_description TEXT",
            "status TEXT DEFAULT 'draft'"
        ];
        
        foreach ($columns_to_add as $colDef) {
            try {
                $pdo->exec("ALTER TABLE pages ADD COLUMN $colDef");
            } catch (PDOException $e) {
                // Kolom bestaat al, negeren
            }
        }
    }

    public static function getCmsConnection() {
        if (self::$cmsPdo === null) {
            $dbPath = __DIR__ . '/../storage/content.sqlite';
            try {
                self::$cmsPdo = new PDO('sqlite:' . $dbPath);
                self::$cmsPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                self::$cmsPdo->exec('PRAGMA journal_mode = WAL;');
                self::$cmsPdo->exec('PRAGMA synchronous = NORMAL;');
                self::$cmsPdo->exec('PRAGMA busy_timeout = 5000;');
                self::$cmsPdo->exec('PRAGMA foreign_keys = ON;');
                
                self::init_cms_tables(self::$cmsPdo);
            } catch (PDOException $e) {
                throw new Exception("Fout bij het verbinden met de CMS database.");
            }
        }
        return self::$cmsPdo;
    }
}

// Backward compatibility function
function get_db_connection() {
    return DBHelper::getConnection();
}

// Global CMS DB Helper
function get_cms_connection() {
    return DBHelper::getCmsConnection();
}
