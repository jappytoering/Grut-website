<?php
require_once __DIR__ . '/../../includes/auth_helper.php';
AuthEngine::require_login(); // Alleen admins

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'error' => 'Ongeldige input']);
    exit;
}

$dbPath = __DIR__ . '/../../storage/content.sqlite';

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($data['action'] === 'save_form') {
        if (!isset($data['form']) || !is_array($data['form'])) {
            throw new Exception("Geen geldige form data meegegeven.");
        }
        
        $form = $data['form'];
        
        $pdo->beginTransaction();
        
        if (empty($form['id'])) {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO forms (slug, title, subtitle, submit_label, admin_email, success_message) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $form['slug'],
                $form['title'] ?? '',
                $form['subtitle'] ?? '',
                $form['submit_label'] ?? '',
                $form['admin_email'] ?? '',
                $form['success_message'] ?? ''
            ]);
            $formId = $pdo->lastInsertId();
        } else {
            // Update
            $formId = $form['id'];
            $stmt = $pdo->prepare("UPDATE forms SET slug=?, title=?, subtitle=?, submit_label=?, admin_email=?, success_message=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
            $stmt->execute([
                $form['slug'],
                $form['title'] ?? '',
                $form['subtitle'] ?? '',
                $form['submit_label'] ?? '',
                $form['admin_email'] ?? '',
                $form['success_message'] ?? '',
                $formId
            ]);
        }
        
        // Fields: delete all and re-insert
        $pdo->prepare("DELETE FROM form_fields WHERE form_id=?")->execute([$formId]);
        
        if (!empty($form['fields']) && is_array($form['fields'])) {
            $insertField = $pdo->prepare("INSERT INTO form_fields (form_id, name, label, type, required, autocomplete, options, maxlength, width, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sortOrder = 0;
            foreach ($form['fields'] as $field) {
                $req = (!empty($field['required']) && $field['required'] != '0' && $field['required'] !== false) ? 1 : 0;
                $insertField->execute([
                    $formId,
                    $field['name'] ?? '',
                    $field['label'] ?? '',
                    $field['type'] ?? 'text',
                    $req,
                    $field['autocomplete'] ?? null,
                    $field['options'] ?? null,
                    $field['maxlength'] ?? null,
                    $field['width'] ?? '100',
                    $sortOrder
                ]);
                $sortOrder++;
            }
        }
        
        $pdo->commit();
        echo json_encode(['success' => true, 'id' => $formId]);
        exit;
    }
    
    throw new Exception("Onbekende actie: " . htmlspecialchars($data['action']));
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
