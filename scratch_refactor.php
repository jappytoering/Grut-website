<?php
$files = [
    'admin/pages.php',
    'admin/users.php',
    'scripts/migrate_overlays.php',
    'admin/form_editor.php',
    'admin/media.php',
    'admin/migrate_forms.php',
    'admin/login.php',
    'admin/page_editor.php',
    'api/admin/form_actions.php',
    'api/admin/user_actions.php',
    'api/admin/media_api.php',
    'index.php',
    'bin/optimize-images.php',
    'bin/seed_prototype_content.php',
    'includes/auth_helper.php',
    'includes/form_helper.php',
    'api/admin/cms_actions.php',
    'admin/forms.php',
    'includes/media_helper.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Add require_once __DIR__ . '/.../includes/db_helper.php'; if not present
    $relPath = str_repeat('../', substr_count($file, '/')) . 'includes/db_helper.php';
    if (strpos($file, 'includes/') === 0) {
        $relPath = 'db_helper.php';
    } elseif (strpos($file, 'bin/') === 0) {
        $relPath = '../includes/db_helper.php';
    } elseif (strpos($file, 'admin/') === 0) {
        $relPath = '../includes/db_helper.php';
    } elseif (strpos($file, 'api/admin/') === 0) {
        $relPath = '../../includes/db_helper.php';
    } elseif ($file === 'index.php') {
        $relPath = 'includes/db_helper.php';
    }
    
    if (strpos($content, 'db_helper.php') === false && strpos($file, 'db_helper.php') === false) {
        // Add require_once after <?php
        $requireLine = "\nrequire_once __DIR__ . '/$relPath';\n";
        $content = preg_replace('/<\?php\s+/', "<?php\n$requireLine", $content, 1);
    }

    // Replace PDO instantiations and attributes
    $content = preg_replace('/\$dbPath\s*=\s*__DIR__\s*\.\s*[^;]+;\s*/', '', $content);
    $content = preg_replace('/\$pdo\s*=\s*new\s*PDO\([^)]+\);\s*/', "\$pdo = get_cms_connection();\n", $content);
    $content = preg_replace('/\$pdo->setAttribute\(PDO::ATTR_ERRMODE,\s*PDO::ERRMODE_EXCEPTION\);\s*/', '', $content);
    
    file_put_contents($file, $content);
    echo "Refactored $file\n";
}
