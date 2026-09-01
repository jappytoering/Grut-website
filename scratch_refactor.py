import os
import re

files = [
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
]

for file in files:
    if not os.path.exists(file):
        continue
        
    with open(file, 'r') as f:
        content = f.read()

    relPath = '../' * file.count('/') + 'includes/db_helper.php'
    if file.startswith('includes/'):
        relPath = 'db_helper.php'
    elif file.startswith('bin/'):
        relPath = '../includes/db_helper.php'
    elif file.startswith('admin/'):
        relPath = '../includes/db_helper.php'
    elif file.startswith('api/admin/'):
        relPath = '../../includes/db_helper.php'
    elif file == 'index.php':
        relPath = 'includes/db_helper.php'

    if 'db_helper.php' not in content and 'db_helper.php' not in file:
        requireLine = f"\nrequire_once __DIR__ . '/{relPath}';\n"
        content = re.sub(r'<\?php\s+', f"<?php\n{requireLine}", content, count=1)

    content = re.sub(r'\$dbPath\s*=\s*__DIR__\s*\.\s*[^;]+;\s*', '', content)
    content = re.sub(r'\$pdo\s*=\s*new\s*PDO\([^)]+\);\s*', "$pdo = get_cms_connection();\n", content)
    content = re.sub(r'\$pdo->setAttribute\(PDO::ATTR_ERRMODE,\s*PDO::ERRMODE_EXCEPTION\);\s*', '', content)

    with open(file, 'w') as f:
        f.write(content)
        
    print(f"Refactored {file}")
