<?php
require_once __DIR__ . '/includes/header.php';

$forms_file = __DIR__ . '/../storage/forms.json';
$forms = [];
if (file_exists($forms_file)) {
    $forms = json_decode(file_get_contents($forms_file), true) ?? [];
}
?>

<div class="admin-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="margin: 0;">Formulieren Beheer</h1>
    <a href="form_editor.php?new=1" class="btn" style="background: transparent; color: var(--color-primary); border: 2px solid var(--color-primary); padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600;">Nieuw Formulier</a>
</div>

<style>
    .content-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .content-table th, .content-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    .content-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: var(--color-primary);
    }
    .action-link {
        color: var(--color-accent);
        text-decoration: none;
        font-weight: 500;
        margin-right: 1rem;
    }
    .action-link:hover {
        text-decoration: underline;
    }
    .text-muted {
        color: var(--color-text-light);
        font-size: 0.85em;
    }
</style>

<div style="overflow-x: auto;">
    <table class="content-table">
        <thead>
            <tr>
                <th>ID / Slug</th>
                <th>Titel</th>
                <th>E-mailadres Ontvanger</th>
                <th>Velden</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($forms)): ?>
            <tr>
                <td colspan="5" style="text-align: center; color: #666;">Geen formulieren gevonden. Maak er een aan!</td>
            </tr>
            <?php else: ?>
                <?php foreach ($forms as $index => $form): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($form['id'] ?? 'onbekend') ?></strong></td>
                    <td><?= htmlspecialchars($form['title'] ?? 'Naamloos') ?></td>
                    <td><?= htmlspecialchars($form['admin_email'] ?? '-') ?></td>
                    <td><span class="text-muted"><?= count($form['fields'] ?? []) ?> velden</span></td>
                    <td>
                        <a href="form_editor.php?index=<?= $index ?>" class="action-link">Bewerken</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
