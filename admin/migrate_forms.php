<?php

require_once __DIR__ . '/../includes/db_helper.php';
// admin/migrate_forms.php
$formsFile = __DIR__ . '/../storage/forms.json';

try {
    $pdo = get_cms_connection();
// 1. Schema updates
    echo "Updating schema...\n";

    $pdo->exec("CREATE TABLE IF NOT EXISTS forms (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT UNIQUE NOT NULL,
        title TEXT,
        subtitle TEXT,
        submit_label TEXT,
        admin_email TEXT,
        success_message TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS form_fields (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        form_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        label TEXT NOT NULL,
        type TEXT NOT NULL,
        required INTEGER DEFAULT 0,
        autocomplete TEXT,
        options TEXT,
        maxlength INTEGER,
        width TEXT,
        sort_order INTEGER DEFAULT 0,
        FOREIGN KEY (form_id) REFERENCES forms(id) ON DELETE CASCADE
    )");

    // Add form_id to pages table if not exists
    $columns = $pdo->query("PRAGMA table_info(pages)")->fetchAll(PDO::FETCH_ASSOC);
    $hasFormId = false;
    foreach ($columns as $col) {
        if ($col['name'] === 'form_id') {
            $hasFormId = true;
            break;
        }
    }
    
    if (!$hasFormId) {
        $pdo->exec("ALTER TABLE pages ADD COLUMN form_id INTEGER DEFAULT NULL");
        echo "Added form_id column to pages.\n";
    }

    // 2. Migration
    if (file_exists($formsFile)) {
        echo "Migrating forms from JSON...\n";
        $formsData = json_decode(file_get_contents($formsFile), true);
        
        $insertForm = $pdo->prepare("INSERT OR IGNORE INTO forms (slug, title, subtitle, submit_label, admin_email, success_message) VALUES (?, ?, ?, ?, ?, ?)");
        $insertField = $pdo->prepare("INSERT INTO form_fields (form_id, name, label, type, required, autocomplete, options, maxlength, width, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($formsData as $form) {
            $slug = $form['id']; // Used as slug
            
            $stmt = $pdo->prepare("SELECT id FROM forms WHERE slug = ?");
            $stmt->execute([$slug]);
            $existing = $stmt->fetchColumn();
            
            if (!$existing) {
                $insertForm->execute([
                    $slug,
                    $form['title'] ?? '',
                    $form['subtitle'] ?? '',
                    $form['submit_label'] ?? '',
                    $form['admin_email'] ?? '',
                    $form['success_message'] ?? ''
                ]);
                $formDbId = $pdo->lastInsertId();
                echo "Migrated form: $slug\n";

                // Migrate fields
                if (!empty($form['fields'])) {
                    $sortOrder = 0;
                    foreach ($form['fields'] as $field) {
                        $insertField->execute([
                            $formDbId,
                            $field['name'] ?? '',
                            $field['label'] ?? '',
                            $field['type'] ?? 'text',
                            !empty($field['required']) ? 1 : 0,
                            $field['autocomplete'] ?? null,
                            $field['options'] ?? null,
                            $field['maxlength'] ?? null,
                            $field['width'] ?? '100',
                            $sortOrder
                        ]);
                        $sortOrder++;
                    }
                }
            } else {
                echo "Form $slug already migrated.\n";
            }
        }
    } else {
        echo "No forms.json found.\n";
    }

    echo "Migration completed successfully!\n";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
