<?php
require_once __DIR__ . '/../../includes/db_helper.php';
$pdo = get_cms_connection();
$stmt = $pdo->query("SELECT * FROM pages");
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($pages);
