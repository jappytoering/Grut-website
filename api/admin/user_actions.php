<?php

require_once __DIR__ . '/../../includes/db_helper.php';
require_once __DIR__ . '/../../includes/auth_helper.php';

AuthEngine::require_login(); // Ensure logged in

// CSRF Check for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Ongeldig CSRF token']);
        exit;
    }
}

if (!AuthEngine::has_role('super_admin')) {
    echo json_encode(['success' => false, 'error' => 'Geen rechten']);
    exit;
}

header('Content-Type: application/json');
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'error' => 'Ongeldige input']);
    exit;
}

$pdo = get_cms_connection();
$action = $data['action'];

try {
    if ($action === 'create') {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $role = $data['role'] ?? 'editor';
        
        if (empty($email) || empty($password)) {
            throw new Exception("E-mail en wachtwoord zijn verplicht.");
        }
        
        if (strlen($password) < 6) {
            throw new Exception("Wachtwoord moet minimaal 6 tekens zijn.");
        }
        
        // Check if exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Er bestaat al een gebruiker met dit e-mailadres.");
        }
        
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $hash, $role]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'delete') {
        $id = $data['id'] ?? null;
        if (empty($id)) throw new Exception("ID is verplicht");
        
        // Prevent deleting yourself
        if ($id == $_SESSION['user_id']) {
            throw new Exception("Je kunt jezelf niet verwijderen.");
        }
        
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    throw new Exception("Onbekende actie: " . htmlspecialchars($action));
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
