<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/auth_helper.php';

// Only super_admin can access this page
if (!AuthEngine::has_role('super_admin')) {
    echo "<div style='padding: 2rem;'><h2>Toegang geweigerd</h2><p>Je hebt niet de juiste rechten om deze pagina te bekijken.</p></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$dbPath = __DIR__ . '/../storage/content.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->query("SELECT id, email, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="margin: 0;">Gebruikersbeheer</h1>
    <button class="btn btn-primary" onclick="document.getElementById('add-user-modal').style.display='flex'">Nieuwe Gebruiker</button>
</div>

<style>
    .content-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .content-table th, .content-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
    .content-table th { background: #f8f9fa; font-weight: 600; color: var(--color-primary); }
    .modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
    .modal-content { background: white; padding: 2rem; border-radius: 12px; width: 90%; max-width: 400px; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; margin-bottom: 0.3rem; font-weight: 600; font-size: 0.9rem; }
    .form-group input, .form-group select { width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
</style>

<div style="overflow-x: auto;">
    <table class="content-table">
        <thead>
            <tr>
                <th>E-mailadres</th>
                <th>Rol</th>
                <th>Aangemaakt op</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($user['email']); ?></strong></td>
                <td>
                    <span style="background: <?php echo $user['role'] === 'super_admin' ? '#dbeafe' : '#fef3c7'; ?>; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">
                        <?php echo htmlspecialchars($user['role'] === 'super_admin' ? 'SuperAdmin' : 'Redacteur'); ?>
                    </span>
                </td>
                <td><?php echo date('d-m-Y H:i', strtotime($user['created_at'])); ?></td>
                <td>
                    <?php if ($user['email'] !== $_SESSION['user_email']): ?>
                        <button class="btn btn-outline" style="padding: 0.2rem 0.6rem; font-size: 0.85rem; color: red; border-color: red;" onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>')">Verwijder</button>
                    <?php else: ?>
                        <span style="color: #999; font-size: 0.85rem;">Ingelogd</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add User Modal -->
<div class="modal" id="add-user-modal">
    <div class="modal-content">
        <h2 style="margin-top:0;">Nieuwe Gebruiker</h2>
        <form id="add-user-form">
            <div class="form-group">
                <label>E-mailadres</label>
                <input type="email" id="new_email" required>
            </div>
            <div class="form-group">
                <label>Wachtwoord</label>
                <input type="password" id="new_password" required minlength="6">
            </div>
            <div class="form-group">
                <label>Rol</label>
                <select id="new_role">
                    <option value="editor">Redacteur (CMS & Media)</option>
                    <option value="super_admin">SuperAdmin (Alles)</option>
                </select>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('add-user-modal').style.display='none'">Annuleren</button>
                <button type="submit" class="btn btn-primary">Aanmaken</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('add-user-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('new_email').value;
    const password = document.getElementById('new_password').value;
    const role = document.getElementById('new_role').value;
    
    try {
        const res = await fetch('/api/admin/user_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'create', email, password, role })
        });
        const json = await res.json();
        if (json.success) {
            window.location.reload();
        } else {
            alert('Fout: ' + json.error);
        }
    } catch(e) {
        alert('Netwerk fout');
    }
});

async function deleteUser(id, email) {
    if (!confirm('Weet je zeker dat je ' + email + ' wilt verwijderen?')) return;
    try {
        const res = await fetch('/api/admin/user_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'delete', id: id })
        });
        const json = await res.json();
        if (json.success) {
            window.location.reload();
        } else {
            alert('Fout: ' + json.error);
        }
    } catch(e) {
        alert('Netwerk fout');
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
