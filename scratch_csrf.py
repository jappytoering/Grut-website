import os
import re

files = [
    'api/admin/cms_actions.php',
    'api/admin/form_actions.php',
    'api/admin/menu_actions.php',
    'api/admin/user_actions.php',
    'api/admin/upload_media.php',
    'api/admin/media_api.php',
    'api/admin/deploy_action.php'
]

csrf_check = """
// CSRF Check for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Ongeldig CSRF token']);
        exit;
    }
}
"""

for file in files:
    if not os.path.exists(file):
        continue
        
    with open(file, 'r') as f:
        content = f.read()

    # Find the line after header('Content-Type: application/json'); or AuthEngine::require_login();
    if 'CSRF Check' in content:
        continue # already added
        
    # Insert after AuthEngine::require_login();
    content = re.sub(r'(AuthEngine::require_login\(\);[^\n]*)', r'\1\n' + csrf_check, content, count=1)
    
    with open(file, 'w') as f:
        f.write(content)
        
    print(f"Added CSRF to {file}")
