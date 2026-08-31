<?php
/**
 * Content Helper voor Gecentraliseerde Content Database
 * Verzorgt in-memory caching van vertalingen en dynamische A/B content.
 */

class ContentEngine {
    private static $translations = null;
    private static $locale = 'nl'; // Standaard taal
    
    /**
     * Haal de huidige taal op (bijv. op basis van $_SERVER['REQUEST_URI'])
     */
    public static function get_locale() {
        // Basis logica om locale uit URL te halen (bijv. /en/ of /fy/)
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        if (preg_match('#^/(en|fy)/#', $uri, $matches)) {
            return $matches[1];
        }
        return self::$locale;
    }
    
    /**
     * Laad alle vertalingen voor de actieve taal in één keer in het geheugen (Single Query Cache)
     */
    private static function load_translations() {
        if (self::$translations !== null) {
            return; // Al geladen
        }
        
        self::$translations = [];
        $current_locale = self::get_locale();
        $dbPath = __DIR__ . '/../storage/content.sqlite';
        
        try {
            if (!file_exists($dbPath)) return;
            
            $pdo = new PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Haal vertalingen voor actieve taal, én fallback (nl) als een specifieke key ontbreekt
            $query = "
                SELECT k.key_name, t.value, t.locale 
                FROM content_keys k
                LEFT JOIN content_translations t ON k.id = t.key_id 
                WHERE t.locale = :locale OR t.locale = 'nl'
                ORDER BY CASE WHEN t.locale = :locale THEN 1 ELSE 2 END
            ";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([':locale' => $current_locale]);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Als de key nog niet bestaat in de array, of als deze row de specifieke locale is (overschrijf nl fallback)
                if (!isset(self::$translations[$row['key_name']]) || $row['locale'] === $current_locale) {
                    self::$translations[$row['key_name']] = $row['value'];
                }
            }
        } catch (Exception $e) {
            error_log("Fout bij inladen content database: " . $e->getMessage());
        }
    }
    
    /**
     * Haal een tekst op via key
     */
    public static function get($key, $fallback = '') {
        self::load_translations();
        
        if (isset(self::$translations[$key])) {
            return self::$translations[$key];
        }
        return $fallback;
    }
}

/**
 * Helper: Eenvoudige tekst
 * @param string $key De unieke identifier
 * @param string $fallback De standaardtekst als key niet bestaat
 */
function t($key, $fallback = '') {
    $text = ContentEngine::get($key, $fallback);
    return htmlspecialchars($text);
}

/**
 * Helper: Rijke content (Markdown block)
 * Let op: vereist idealiter een Markdown parser (bijv Parsedown), maar we vallen hier terug op nl2br/veilig HTML
 */
function content_block($key, $fallback = '') {
    $text = ContentEngine::get($key, $fallback);
    // Simpele markdown sanitization voor basis tags (bold, italic, links)
    $text = htmlspecialchars($text);
    $text = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $text);
    $text = preg_replace('/_(.*?)_/s', '<em>$1</em>', $text);
    $text = preg_replace('/\[(.*?)\]\((.*?)\)/s', '<a href="$2">$1</a>', $text);
    
    return nl2br($text);
}

/**
 * Helper: Dynamische content (bijv. voor A/B testen via parameters)
 * @param string $key De unieke identifier
 * @param array $options Configuratie (bijv. 'default' en 'test_id')
 */
function t_dynamic($key, $options = []) {
    // In een volledige A/B setup zou dit sessie-gebonden controleren welke variant de bezoeker krijgt.
    // Voor nu is het een pass-through naar t().
    $fallback = $options['default'] ?? '';
    return t($key, $fallback);
}
