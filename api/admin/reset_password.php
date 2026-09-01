<?php
require_once __DIR__ . '/../../includes/db_helper.php';
$pdo = get_cms_connection();
$email = 'jappy@grutdesigners.nl';
$password = 'grut2026';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$hash, $email]);
    echo "Password updated for $email. ";
} else {
    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, 'super_admin')");
    $stmt->execute([$email, $hash]);
    echo "User $email created. ";
}
