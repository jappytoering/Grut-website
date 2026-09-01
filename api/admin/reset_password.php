<?php
require_once __DIR__ . '/../../includes/db_helper.php';
$pdo = get_cms_connection();
$hash = password_hash('grut2026', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = 'jappy@grutdesigners.nl'");
$stmt->execute([$hash]);
echo "Password reset to grut2026";
