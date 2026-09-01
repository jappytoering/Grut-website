<?php
require_once __DIR__ . '/../../includes/db_helper.php';

// In een echte productie-omgeving zou je hier controleren of de user is ingelogd.
// Voor deze demo/prototype laten we het door als we lokaal/test draaien of we kunnen het simpelweg toelaten.

header('Content-Type: application/json');

require_once __DIR__ . '/../../bin/smoke-test.php';

try {
    $suite = new SmokeTestSuite();
    $results = $suite->run();
    echo json_encode($results);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'tests' => [],
        'new_stories_added' => 0,
        'error' => 'Fatale fout tijdens uitvoeren tests: ' . $e->getMessage()
    ]);
}
