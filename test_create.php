<?php
$_SERVER['REQUEST_METHOD'] = 'POST';
$data = [
    'action' => 'save_page',
    'id' => '',
    'slug' => 'test-page-' . time(),
    'status' => 'draft',
    'template' => 'default',
    'form_id' => '',
    'seo_title' => 'Test',
    'meta_description' => 'Test desc'
];
require 'includes/db_helper.php';
$pdo = get_cms_connection();
$stmt = $pdo->prepare("INSERT INTO pages (slug, status, template, form_id, seo_title, meta_description, created_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
try {
    $stmt->execute([$data['slug'], $data['status'], $data['template'], null, $data['seo_title'], $data['meta_description']]);
    echo "Success: " . $pdo->lastInsertId();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
