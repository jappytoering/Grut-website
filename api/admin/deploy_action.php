<?php
require_once __DIR__ . '/../../includes/auth_helper.php';

AuthEngine::require_login();
if (!AuthEngine::has_role('super_admin')) {
    echo json_encode(['success' => false, 'error' => 'Geen rechten']);
    exit;
}

header('Content-Type: application/json');

try {
    $baseDir = realpath(__DIR__ . '/../../');
    
    // Copy seed files
    $dbOriginal = $baseDir . '/storage/content.sqlite';
    $dbSeed = $baseDir . '/storage/content.sqlite.seed';
    
    $formsOriginal = $baseDir . '/storage/forms.json';
    $formsSeed = $baseDir . '/storage/forms.json.seed';
    
    if (file_exists($dbOriginal)) {
        copy($dbOriginal, $dbSeed);
    }
    
    if (file_exists($formsOriginal)) {
        copy($formsOriginal, $formsSeed);
    }
    
    // Execute git commands
    $output = [];
    $return_var = 0;
    
    // Add seed files and commit
    exec("cd " . escapeshellarg($baseDir) . " && git add storage/content.sqlite.seed storage/forms.json.seed && git commit -m \"Auto-deploy DB seed from admin\" 2>&1", $output, $return_var);
    
    // If nothing to commit, return_var is 1, which is fine
    $commitOutput = implode("\n", $output);
    
    // Push
    $outputPush = [];
    exec("cd " . escapeshellarg($baseDir) . " && git push origin HEAD:test 2>&1", $outputPush, $returnVarPush);
    
    $finalOutput = $commitOutput . "\n\n" . implode("\n", $outputPush);
    
    if ($returnVarPush !== 0) {
        throw new Exception("Git push mislukt:\n" . $finalOutput);
    }
    
    echo json_encode(['success' => true, 'output' => $finalOutput]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
