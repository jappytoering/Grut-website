<?php

class SmokeTestSuite {
    
    private string $backlogPath;
    private array $results = [];
    
    public function __construct() {
        $this->backlogPath = __DIR__ . '/../BACKLOG.md';
    }
    
    /**
     * Run alle tests
     */
    public function run(): array {
        $this->results = [
            'success' => true,
            'tests' => [],
            'new_stories_added' => 0
        ];
        
        $this->runTest('test_db_connection', 'Database Connectie');
        $this->runTest('test_e2e_page_creation', 'Pagina Aanmaken');
        $this->runTest('test_e2e_component_assignment', 'Component (Block) Toewijzen');
        $this->runTest('test_e2e_media_assignment', 'Media Aanmaken');
        
        // Controleer de algehele status
        foreach ($this->results['tests'] as $test) {
            if ($test['status'] === 'FAIL') {
                $this->results['success'] = false;
                break;
            }
        }
        
        return $this->results;
    }
    
    private function runTest(string $methodName, string $testName) {
        try {
            $result = $this->$methodName();
            if ($result === true) {
                $this->results['tests'][] = [
                    'name' => $testName,
                    'status' => 'PASS',
                    'message' => 'Test geslaagd.'
                ];
            } else {
                throw new Exception("Test '$testName' retourneerde false zonder specifieke foutmelding.");
            }
        } catch (Exception $e) {
            $this->results['tests'][] = [
                'name' => $testName,
                'status' => 'FAIL',
                'message' => $e->getMessage(),
                'file' => $e->getFile()
            ];
            
            // Auto-backlog logic
            if ($this->log_to_backlog($testName, $e->getMessage(), $e->getFile())) {
                $this->results['new_stories_added']++;
            }
        }
    }
    
    /**
     * Schrijf gefaalde tests weg naar BACKLOG.md
     */
    private function log_to_backlog(string $testName, string $errorMsg, string $file): bool {
        if (!file_exists($this->backlogPath)) {
            return false;
        }
        
        $content = file_get_contents($this->backlogPath);
        
        // Unieke titel voor deze story
        $storyTitle = "### [BUG / STORY] Fix: {$testName}";
        
        // Check of deze story al in backlog staat (voorkom duplicates)
        if (strpos($content, $storyTitle) !== false) {
            return false; // Bestaat al
        }
        
        // Bouw de nieuwe markdown string
        $storyMd = "\n{$storyTitle}\n";
        $storyMd .= "- **Status:** [TODO]\n";
        $storyMd .= "- **Probleem:** Test faalde tijdens de geautomatiseerde test-suite.\n";
        $storyMd .= "- **Foutmelding/Symptoom:** `{$errorMsg}`\n";
        $storyMd .= "- **Betrokken bestand(en):** `{$file}`\n";
        $storyMd .= "- **Acceptatiecriteria:**\n";
        $storyMd .= "  - [ ] Onderliggend probleem oplossen.\n";
        $storyMd .= "  - [ ] Test in `admin/test-suite.php` slaagt met `[PASS]`\n";
        
        // Zoek naar de bugs sectie
        $targetSection = "## 🐛 Gevonden Bugs & Openstaande Reparaties";
        
        if (strpos($content, $targetSection) !== false) {
            // Append na de titel van de sectie
            $content = str_replace($targetSection, $targetSection . "\n" . $storyMd, $content);
        } else {
            // Append onderaan als de sectie niet bestaat
            $content .= "\n\n" . $targetSection . "\n" . $storyMd;
        }
        
        file_put_contents($this->backlogPath, $content);
        return true;
    }
    
    // --- TEST DEFINITIES ---
    
    private function test_db_connection() {
        require_once __DIR__ . '/../includes/db_helper.php';
        $pdo = get_cms_connection();
        if (!$pdo) {
            throw new Exception("Kan geen verbinding maken met storage/content.sqlite");
        }
        return true;
    }
    
    private function test_e2e_page_creation() {
        require_once __DIR__ . '/../includes/db_helper.php';
        $pdo = get_cms_connection();
        
        $slug = 'test-e2e-' . time();
        $stmt = $pdo->prepare("INSERT INTO pages (slug, status, template, form_id, seo_title, meta_description) VALUES (?, 'draft', 'default', NULL, 'E2E Test', 'Desc')");
        $success = $stmt->execute([$slug]);
        
        if (!$success) {
            throw new Exception("Kan geen pagina toevoegen aan database (schema mismatch?).");
        }
        
        $pageId = $pdo->lastInsertId();
        if (empty($pageId)) {
            throw new Exception("Geen ID teruggekregen na pagina creatie.");
        }
        return true;
    }
    
    private function test_e2e_component_assignment() {
        require_once __DIR__ . '/../includes/db_helper.php';
        $pdo = get_cms_connection();
        
        // Zoek een test pagina of maak er een
        $stmt = $pdo->query("SELECT id FROM pages ORDER BY id DESC LIMIT 1");
        $pageId = $stmt->fetchColumn();
        
        if (!$pageId) {
            throw new Exception("Geen pagina gevonden om block aan toe te wijzen.");
        }
        
        $stmt = $pdo->prepare("INSERT INTO page_blocks (page_id, block_type, sort_order, content_json) VALUES (?, 'hero', 99, '{}')");
        $success = $stmt->execute([$pageId]);
        
        if (!$success) {
            throw new Exception("Kan component (block) niet opslaan in page_blocks tabel.");
        }
        
        return true;
    }

    private function test_e2e_media_assignment() {
        require_once __DIR__ . '/../includes/db_helper.php';
        $pdo = get_cms_connection();
        
        $asset_id = 'test-asset-' . time();
        $stmt = $pdo->prepare("INSERT INTO media_assets (asset_id, original_filename, title, width, height) VALUES (?, 'test.jpg', 'Test Afbeelding', 800, 600)");
        $success = $stmt->execute([$asset_id]);
        
        if (!$success) {
            throw new Exception("Kan media asset niet opslaan in media_assets tabel.");
        }
        return true;
    }
}

// Als via CLI uitgevoerd, print de resultaten
if (php_sapi_name() === 'cli') {
    $suite = new SmokeTestSuite();
    $res = $suite->run();
    print_r($res);
}
