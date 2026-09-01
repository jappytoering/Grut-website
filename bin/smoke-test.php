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
        $this->runTest('test_dummy_failure', 'Dynamic Block Renderer');
        
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
    
    private function test_dummy_failure() {
        // Opzettelijke fout voor de test
        throw new Exception("component 'faq_accordion' mist 'question' array key.");
    }
}

// Als via CLI uitgevoerd, print de resultaten
if (php_sapi_name() === 'cli') {
    $suite = new SmokeTestSuite();
    $res = $suite->run();
    print_r($res);
}
