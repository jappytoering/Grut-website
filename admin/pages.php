<?php
require_once __DIR__ . '/includes/header.php';

$dbPath = __DIR__ . '/../storage/content.sqlite';
$pages = [];

try {
    if (file_exists($dbPath)) {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query("SELECT id, slug, template, status, created_at FROM pages ORDER BY created_at DESC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pages[] = $row;
        }
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Fout bij inladen pagina's: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>

<div class="admin-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="margin: 0;">Pagina Beheer</h1>
    <a href="page_editor.php" class="btn btn-primary" style="background: var(--color-purple); color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none;">Nieuwe Pagina</a>
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
        color: var(--color-purple);
    }
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 99px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .status-published { background: #d1fae5; color: #065f46; }
    .status-draft { background: #fef3c7; color: #92400e; }
    .action-link {
        color: var(--color-yellow);
        text-decoration: none;
        font-weight: 500;
    }
    .action-link:hover {
        text-decoration: underline;
    }
</style>

<div style="overflow-x: auto;">
    <table class="content-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Slug / URL</th>
                <th>Template</th>
                <th>Status</th>
                <th>Aangemaakt op</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pages)): ?>
            <tr>
                <td colspan="6" style="text-align: center; color: #666;">Geen pagina's gevonden. Maak er een aan!</td>
            </tr>
            <?php else: ?>
                <?php foreach ($pages as $page): ?>
                <tr>
                    <td><?= $page['id'] ?></td>
                    <td><strong>/<?= htmlspecialchars($page['slug']) ?></strong></td>
                    <td><?= htmlspecialchars($page['template'] ?? 'default') ?></td>
                    <td>
                        <span class="status-badge status-<?= $page['status'] === 'published' ? 'published' : 'draft' ?>">
                            <?= ucfirst($page['status']) ?>
                        </span>
                    </td>
                    <td><?= date('d-m-Y H:i', strtotime($page['created_at'])) ?></td>
                    <td>
                        <a href="page_editor.php?id=<?= $page['id'] ?>" class="action-link">Bewerken</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
