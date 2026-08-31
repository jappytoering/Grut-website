<?php
/**
 * API Endpoint: Submit Contact Form
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Voorkom directe toegang als het geen POST is
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Methode niet toegestaan.']);
    exit;
}

require_once __DIR__ . '/../includes/db_helper.php';
require_once __DIR__ . '/../includes/mail_helper.php';
$config = require __DIR__ . '/../config/contact.php';

try {
    // 1. Bot Defense: Honeypot check
    if (!empty($_POST['website_hp'])) {
        // Geruisloos afvangen voor bots
        echo json_encode(['success' => true, 'message' => 'Bericht succesvol verzonden.']);
        exit;
    }

    // 2. Bot Defense: Time-based (minimum 2 seconden tussen laden en submitten)
    if (!empty($_POST['browser_timestamp'])) {
        $clientTime = strtotime($_POST['browser_timestamp']);
        $currentTime = time();
        if (($currentTime - $clientTime) < 2) {
            // Te snel ingevuld, waarschijnlijk een bot
            echo json_encode(['success' => true, 'message' => 'Bericht succesvol verzonden.']);
            exit;
        }
    }

    // 3. Data ophalen & sanitizen
    $preset_id  = filter_input(INPUT_POST, 'preset_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $source_url = filter_input(INPUT_POST, 'source_url', FILTER_SANITIZE_URL);
    
    $first_name = trim(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $last_name = trim(filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    
    $name    = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    if (empty($name) && (!empty($first_name) || !empty($last_name))) {
        $name = trim($first_name . ' ' . $last_name);
    }
    
    $email   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone   = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $company = trim(filter_input(INPUT_POST, 'company', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $service_type = trim(filter_input(INPUT_POST, 'service_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $budget  = trim(filter_input(INPUT_POST, 'budget', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    
    $message_body = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $case_description = trim(filter_input(INPUT_POST, 'case_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    if (empty($message_body) && !empty($case_description)) {
        $message_body = $case_description;
    }
    
    // Voor dynamische velden slaan we de volledige POST payload op (gesanitized)
    $payload = [];
    foreach ($_POST as $key => $value) {
        if (!in_array($key, ['website_hp', 'browser_timestamp'])) {
            $payload[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
    }

    // 4. Server-side validatie
    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Vul een geldige naam en e-mailadres in.']);
        exit;
    }

    // 5. Opslaan in SQLite database
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("
        INSERT INTO submissions (preset_id, source_url, name, email, phone, company, service_type, budget, message, payload_json)
        VALUES (:preset_id, :source_url, :name, :email, :phone, :company, :service_type, :budget, :message, :payload_json)
    ");
    
    $stmt->execute([
        ':preset_id'    => $preset_id,
        ':source_url'   => $source_url,
        ':name'         => $name,
        ':email'        => $email,
        ':phone'        => $phone,
        ':company'      => $company,
        ':service_type' => $service_type,
        ':budget'       => $budget,
        ':message'      => $message_body,
        ':payload_json' => json_encode($payload, JSON_UNESCAPED_UNICODE)
    ]);

    // 6. E-mail Notificatie Versturen (Transactionele E-mailengine)
    // We bouwen de definitieve leadData array op voor de mail helper
    $leadData = $payload;
    $leadData['preset_id'] = $preset_id;
    $leadData['source_url'] = $source_url;
    $leadData['name'] = $name;
    $leadData['email'] = $email;
    $leadData['message'] = $message_body;

    try {
        // Verstuur naar admin
        send_admin_lead_notification($leadData);
        // Verstuur naar klant
        send_customer_confirmation($leadData);
    } catch (Exception $mailEx) {
        // Log mail error, maar laat dit de flow niet breken (silent fail voor de bezoeker)
        error_log("Mail verzenden mislukt voor lead {$email}: " . $mailEx->getMessage());
    }

    // 7. Succes response
    echo json_encode(['success' => true, 'message' => 'Bericht succesvol verzonden.']);

} catch (Exception $e) {
    http_response_code(500);
    $error_msg = $config['debug'] ? $e->getMessage() : 'Er is een interne fout opgetreden bij het verwerken van het verzoek.';
    echo json_encode(['success' => false, 'message' => $error_msg]);
}
